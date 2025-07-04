<?php
require_once 'app/helpers/db.php';

echo "=== Tạo báo cáo test chưa có bản dịch ===\n";

// Tạo báo cáo test với title_zh và content_zh rỗng
$title = 'Báo cáo công việc test ' . date('Y-m-d H:i:s');
$content = 'Đây là nội dung báo cáo test để kiểm tra tính năng tự động dịch. Tôi đã hoàn thành các nhiệm vụ được giao và chuẩn bị cho tuần tới.';
$user_id = 1; // Giả sử user_id = 1
$department_id = 1; // Giả sử department_id = 1
$titleZh = ''; // Rỗng để test
$contentZh = ''; // Rỗng để test

$stmt = $conn->prepare('INSERT INTO reports (user_id, title, title_zh, content, content_zh, department_id) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('issssi', $user_id, $title, $titleZh, $content, $contentZh, $department_id);

if ($stmt->execute()) {
    echo "Đã tạo báo cáo test thành công!\n";
    echo "Title: $title\n";
    echo "Content: $content\n";
    echo "ID: " . $conn->insert_id . "\n";
} else {
    echo "Lỗi khi tạo báo cáo: " . $stmt->error . "\n";
}
$stmt->close();

echo "=== Hoàn thành ===\n";
?> 