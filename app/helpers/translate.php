<?php
/**
 * Translation helper functions
 */

// Sử dụng đường dẫn tuyệt đối để tránh lỗi open_basedir
$base_path = dirname(dirname(__DIR__));
require_once $base_path . '/app/helpers/i18n.php';

// Load API keys
$apiKeys = include $base_path . '/app/config/api_keys.php';

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

    $apiKeysList = $apiKeys['gemini_api_keys'] ?? [];
    if (empty($text) || empty($apiKeysList) || !is_array($apiKeysList)) {
        return '';
    }

    // Prompt mạnh hơn
    if ($targetLang === 'zh') {
        $prompt = "Dịch văn bản sau từ tiếng Việt (vi) sang tiếng Trung (zh). Chỉ trả về bản dịch tiếng Trung, không giải thích, không giữ lại tiếng Việt: $text";
    } else {
        $prompt = "Dịch văn bản sau từ tiếng Trung (zh) sang tiếng Việt (vi). Chỉ trả về bản dịch tiếng Việt, không giải thích, không giữ lại tiếng Trung: $text";
    }

    error_log('[Gemini] Prompt: ' . $prompt);

    $postData = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
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
        $retry = 0;
        while ($retry < 2) {
            $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            error_log('[Gemini] HTTP ' . $httpCode . ' - Response: ' . $response);

            if ($httpCode === 200) {
                $data = json_decode($response, true);
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $result = trim($data['candidates'][0]['content']['parts'][0]['text']);
                    error_log('[Gemini] Result: ' . $result);
                    // Nếu dịch sang tiếng Việt mà kết quả vẫn là tiếng Trung, thử lại với prompt khác hoặc fallback
                    if ($targetLang === 'vi' && preg_match('/[\x{4e00}-\x{9fff}]/u', $result)) {
                        error_log('[Gemini] Result still Chinese, will try next key or fallback.');
                        break;
                    }
                    return $result;
                }
            } else {
                error_log("Gemini API Error (key $apiKey): HTTP $httpCode - $response");
            }
            $retry++;
        }
    }
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
    $retry = 0;
    while ($retry < 2) {
        error_log('[OpenAI] Input: ' . $text . ' | Target: ' . $targetLanguage);
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
        error_log('[OpenAI] HTTP ' . $httpCode . ' - Response: ' . $response);
        if ($httpCode === 200) {
            $result = json_decode($response, true);
            if (isset($result['choices'][0]['message']['content'])) {
                $translation = trim($result['choices'][0]['message']['content']);
                error_log('[OpenAI] Result: ' . $translation);
                return $translation;
            }
        } else {
            error_log("OpenAI API Error: HTTP $httpCode - $response");
        }
        $retry++;
    }
    return '';
}

/**
 * Dịch văn bản (sử dụng Gemini làm mặc định, OpenAI làm backup) + CACHE
 */
function translateText($text, $targetLang = 'zh') {
    global $conn;
    if (!isset($conn) || !$conn) {
        // Kết nối DB nếu chưa có
        $base_path = dirname(dirname(__DIR__));
        require_once $base_path . '/app/helpers/db.php';
    }
    // Tạo bảng cache nếu chưa có
    $createTableSql = "CREATE TABLE IF NOT EXISTS translation_cache (
        id INT AUTO_INCREMENT PRIMARY KEY,
        source_text TEXT NOT NULL,
        source_lang VARCHAR(10) NOT NULL,
        target_lang VARCHAR(10) NOT NULL,
        translated_text TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_translation (source_text(255), source_lang, target_lang)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $conn->query($createTableSql);
    $sourceLang = 'vi';
    if ($targetLang === 'vi') {
        $sourceLang = 'zh';
    }
    // Kiểm tra cache trước
    $sql = "SELECT translated_text FROM translation_cache WHERE source_text = ? AND source_lang = ? AND target_lang = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $text, $sourceLang, $targetLang);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        return $row['translated_text'];
    }
    $stmt->close();
    // Nếu chưa có cache, gọi API dịch
    $translation = translateWithGemini($text, $targetLang);
    if (empty($translation)) {
        $translation = translateWithOpenAI($text, $targetLang);
    }
    // Lưu vào cache nếu có kết quả
    if (!empty($translation)) {
        $insertSql = "INSERT INTO translation_cache (source_text, source_lang, target_lang, translated_text) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE translated_text = VALUES(translated_text), updated_at = CURRENT_TIMESTAMP";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param('ssss', $text, $sourceLang, $targetLang, $translation);
        $insertStmt->execute();
        $insertStmt->close();
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

/**
 * Thêm request dịch vào queue
 */
function addToTranslationQueue($text, $sourceLang, $targetLang) {
    global $conn;
    if (!isset($conn) || !$conn) {
        $base_path = dirname(dirname(__DIR__));
        require_once $base_path . '/app/helpers/db.php';
    }
    // Tạo bảng queue nếu chưa có
    $createTableSql = "CREATE TABLE IF NOT EXISTS translation_queue (
        id INT AUTO_INCREMENT PRIMARY KEY,
        source_text TEXT NOT NULL,
        source_lang VARCHAR(10) NOT NULL,
        target_lang VARCHAR(10) NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'pending',
        translated_text TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_queue (source_text(255), source_lang, target_lang)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $conn->query($createTableSql);
    // Thêm vào queue nếu chưa có
    $sql = "INSERT IGNORE INTO translation_queue (source_text, source_lang, target_lang, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $text, $sourceLang, $targetLang);
    $stmt->execute();
    $stmt->close();
}

/**
 * Lấy kết quả dịch từ queue nếu đã xử lý xong
 */
function getQueuedTranslation($text, $sourceLang, $targetLang) {
    global $conn;
    if (!isset($conn) || !$conn) {
        $base_path = dirname(dirname(__DIR__));
        require_once $base_path . '/app/helpers/db.php';
    }
    $sql = "SELECT translated_text, status FROM translation_queue WHERE source_text = ? AND source_lang = ? AND target_lang = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $text, $sourceLang, $targetLang);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    if ($row && $row['status'] === 'done' && !empty($row['translated_text'])) {
        return $row['translated_text'];
    }
    return false;
}

/**
 * Kiểm tra rate limit dịch cho user
 */
function checkTranslationRateLimit($userId, $limit = 10, $intervalSeconds = 60) {
    global $conn;
    if (!isset($conn) || !$conn) {
        $base_path = dirname(dirname(__DIR__));
        require_once $base_path . '/app/helpers/db.php';
    }
    // Tạo bảng nếu chưa có
    $createTableSql = "CREATE TABLE IF NOT EXISTS translation_rate_limit (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_time (user_id, created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $conn->query($createTableSql);
    // Đếm số lần dịch trong khoảng intervalSeconds
    $sql = "SELECT COUNT(*) as cnt FROM translation_rate_limit WHERE user_id = ? AND created_at >= (NOW() - INTERVAL ? SECOND)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $userId, $intervalSeconds);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    if ($row && $row['cnt'] >= $limit) {
        return false;
    }
    // Ghi log lần dịch mới
    $insert = $conn->prepare("INSERT INTO translation_rate_limit (user_id) VALUES (?)");
    $insert->bind_param('i', $userId);
    $insert->execute();
    $insert->close();
    return true;
}
?> 