<?php
/**
 * Translation helper functions
 */

require_once __DIR__ . '/i18n.php';

// Load API keys
$apiKeys = include __DIR__ . '/../config/api_keys.php';

/**
 * Translate text to Chinese using OpenAI API
 * Note: You need to set your OpenAI API key in config
 */
function translate_to_chinese($text) {
    // Check if OpenAI API key is configured
    $apiKey = getenv('OPENAI_API_KEY') ?? 'YOUR_OPENAI_API_KEY';
    
    if ($apiKey === 'YOUR_OPENAI_API_KEY') {
        // If no API key, return original text
        return $text;
    }
    
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system', 
                'content' => 'You are a professional translator. Translate the following Vietnamese text to Simplified Chinese. Only return the translated text, no explanations.'
            ],
            [
                'role' => 'user', 
                'content' => $text
            ]
        ],
        'max_tokens' => 1000,
        'temperature' => 0.3
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $result = json_decode($response, true);
        if (isset($result['choices'][0]['message']['content'])) {
            return trim($result['choices'][0]['message']['content']);
        }
    }
    
    // Return original text if translation fails
    return $text;
}

/**
 * Dịch văn bản sử dụng Gemini API
 */
function translateWithGemini($text, $targetLang = 'zh') {
    global $apiKeys;
    
    // Lấy danh sách API key Gemini
    $apiKeysList = $apiKeys['gemini_api_keys'] ?? [];
    if (empty($text) || empty($apiKeysList) || !is_array($apiKeysList)) {
        return '';
    }
    
    // Xác định ngôn ngữ đích
    $targetLanguage = ($targetLang === 'zh') ? 'tiếng Trung' : 'tiếng Việt';
    $postData = [
        'contents' => [
            [
                'parts' => [
                    ['text' => "Dịch văn bản sau sang $targetLanguage, chỉ trả về bản dịch không có giải thích thêm: $text"]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.3,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 2048,
        ]
    ];

    foreach ($apiKeysList as $apiKey) {
        if (empty($apiKey) || $apiKey === 'YOUR_GEMINI_API_KEY_HERE') continue;
        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return trim($data['candidates'][0]['content']['parts'][0]['text']);
            }
        } else {
            error_log("Gemini API Error (key $apiKey): HTTP $httpCode - $response");
            // Nếu lỗi quota hoặc key bị block thì thử key tiếp theo
            continue;
        }
    }
    // Nếu tất cả key đều lỗi
    error_log("Gemini API Error: All keys failed");
    return '';
}

/**
 * Dịch văn bản sử dụng OpenAI API (giữ lại để backup)
 */
function translateWithOpenAI($text, $targetLang = 'zh') {
    global $apiKeys;
    
    $apiKey = $apiKeys['openai_api_key'];
    
    if (empty($text) || $apiKey === 'YOUR_OPENAI_API_KEY_HERE') {
        return '';
    }
    
    $targetLanguage = ($targetLang === 'zh') ? 'Chinese' : 'Vietnamese';
    
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system',
                'content' => "You are a professional translator. Translate the following text to $targetLanguage. Only return the translation, no explanations."
            ],
            [
                'role' => 'user',
                'content' => $text
            ]
        ],
        'max_tokens' => 1000,
        'temperature' => 0.3
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        error_log("OpenAI API Error: HTTP $httpCode - $response");
        return '';
    }

    $result = json_decode($response, true);
    
    if (isset($result['choices'][0]['message']['content'])) {
        return trim($result['choices'][0]['message']['content']);
    }
    
    return '';
}

/**
 * Dịch văn bản (sử dụng Gemini làm mặc định, OpenAI làm backup)
 */
function translateText($text, $targetLang = 'zh') {
    // Thử Gemini trước
    $translation = translateWithGemini($text, $targetLang);
    
    // Nếu Gemini thất bại, thử OpenAI
    if (empty($translation)) {
        $translation = translateWithOpenAI($text, $targetLang);
    }
    
    return $translation;
}

/**
 * Auto-translate and save report content in both languages
 */
function autoTranslateReportContent($contentVi) {
    $contentZh = translate_to_chinese($contentVi);
    
    return [
        'content_vi' => $contentVi,
        'content_zh' => $contentZh
    ];
}

/**
 * Auto-translate and save report content in both languages
 */
function autoTranslateAndSaveReport($reportId, $title, $content) {
    global $conn;
    
    // Dịch tiêu đề
    $titleZh = translateText($title, 'zh');
    
    // Dịch nội dung
    $contentZh = translateText($content, 'zh');
    
    // Cập nhật database
    $stmt = $conn->prepare('UPDATE reports SET title_zh = ?, content_zh = ? WHERE id = ?');
    $stmt->bind_param('ssi', $titleZh, $contentZh, $reportId);
    $result = $stmt->execute();
    $stmt->close();
    
    return $result;
}

/**
 * Tự động dịch các báo cáo chưa có bản dịch tiếng Trung
 */
function autoTranslateUndoneReports() {
    global $conn;
    
    // Lấy danh sách báo cáo chưa có bản dịch tiếng Trung
    $sql = "SELECT id, title, content, title_zh, content_zh 
            FROM reports 
            WHERE (title_zh IS NULL OR title_zh = '' OR content_zh IS NULL OR content_zh = '')
            ORDER BY created_at DESC 
            LIMIT 10"; // Giới hạn 10 báo cáo mỗi lần để tránh quá tải
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $translatedCount = 0;
    while ($report = $result->fetch_assoc()) {
        $needsUpdate = false;
        $titleZh = $report['title_zh'];
        $contentZh = $report['content_zh'];
        
        // Dịch tiêu đề nếu chưa có
        if (empty($titleZh)) {
            $titleZh = translateText($report['title'], 'zh');
            $needsUpdate = true;
        }
        
        // Dịch nội dung nếu chưa có
        if (empty($contentZh)) {
            $contentZh = translateText($report['content'], 'zh');
            $needsUpdate = true;
        }
        
        // Cập nhật database nếu có thay đổi
        if ($needsUpdate) {
            $updateStmt = $conn->prepare('UPDATE reports SET title_zh = ?, content_zh = ? WHERE id = ?');
            $updateStmt->bind_param('ssi', $titleZh, $contentZh, $report['id']);
            if ($updateStmt->execute()) {
                $translatedCount++;
            }
            $updateStmt->close();
            
            // Delay nhỏ để tránh rate limit
            usleep(500000); // 0.5 giây
        }
    }
    $stmt->close();
    
    // Log số lượng báo cáo đã dịch
    if ($translatedCount > 0) {
        error_log("Auto-translated $translatedCount reports to Chinese");
    }
    
    return $translatedCount;
}
?> 