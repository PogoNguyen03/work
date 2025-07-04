<?php
require_once 'app/helpers/db.php';
require_once 'app/helpers/translate.php';

echo "=== Test Auto Translate Feature ===\n";

// Kiểm tra số lượng báo cáo chưa có bản dịch
$sql = "SELECT COUNT(*) as count FROM reports WHERE (title_zh IS NULL OR title_zh = '' OR content_zh IS NULL OR content_zh = '')";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

echo "Số báo cáo chưa có bản dịch: " . $row['count'] . "\n";

if ($row['count'] > 0) {
    echo "Bắt đầu tự động dịch...\n";
    
    // Gọi function tự động dịch
    autoTranslateUndoneReports();
    
    // Kiểm tra lại sau khi dịch
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    echo "Số báo cáo chưa có bản dịch sau khi dịch: " . $row['count'] . "\n";
} else {
    echo "Tất cả báo cáo đã có bản dịch!\n";
}

echo "=== Test hoàn thành ===\n";
?> 