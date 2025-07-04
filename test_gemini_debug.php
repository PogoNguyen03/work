<?php
require_once 'app/helpers/translate.php';

echo "=== Debug Gemini API ===\n";

// Kiểm tra API key
$apiKeys = include 'app/config/api_keys.php';
echo "API Key: " . substr($apiKeys['gemini_api_key'], 0, 10) . "...\n";

// Test với debug
$text = 'Xin chào, đây là báo cáo công việc hôm nay.';
echo "Text to translate: $text\n";

// Gọi API trực tiếp để debug
$apiKey = $apiKeys['gemini_api_key'];
$postData = [
    'contents' => [
        [
            'parts' => [
                ['text' => "Dịch văn bản sau sang tiếng Trung, chỉ trả về bản dịch không có giải thích thêm: $text"]
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

echo "Sending request to Gemini API...\n";

$ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "CURL Error: $error\n";
echo "Response: " . substr($response, 0, 500) . "...\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
        echo "Translation: " . $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
    } else {
        echo "No translation in response\n";
        echo "Full response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
} else {
    echo "API Error: HTTP $httpCode\n";
}

echo "=== Debug hoàn thành ===\n";
?> 