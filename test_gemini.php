<?php
require_once 'app/helpers/translate.php';

echo "=== Testing Gemini API ===\n";

// Test 1: Dịch tiếng Việt sang tiếng Trung
echo "Test 1: Dịch 'Xin chào, đây là báo cáo công việc hôm nay.'\n";
$result1 = translateWithGemini('Xin chào, đây là báo cáo công việc hôm nay.', 'zh');
echo "Kết quả: " . $result1 . "\n\n";

// Test 2: Dịch tiêu đề
echo "Test 2: Dịch tiêu đề 'Báo cáo công việc tuần này'\n";
$result2 = translateWithGemini('Báo cáo công việc tuần này', 'zh');
echo "Kết quả: " . $result2 . "\n\n";

// Test 3: Test function translateText (có backup)
echo "Test 3: Sử dụng translateText function\n";
$result3 = translateText('Tôi đã hoàn thành các nhiệm vụ được giao.', 'zh');
echo "Kết quả: " . $result3 . "\n\n";

echo "=== Test hoàn thành ===\n";
?> 