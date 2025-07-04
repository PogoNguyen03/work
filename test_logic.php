<?php
// Script test để kiểm tra logic hiển thị badge "Đã cập nhật"
require_once 'app/helpers/db.php';
require_once 'app/helpers/auth.php';

echo "=== Test Logic Hiển Thị Badge 'Đã Cập Nhật' ===\n\n";

// Giả lập session cho admin
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';

// Tạo một báo cáo test với updated_at khác created_at
echo "Tạo báo cáo test...\n";
$stmt = $conn->prepare("INSERT INTO reports (user_id, title, content, department_id, created_at, updated_at) VALUES (1, 'Báo cáo test', 'Nội dung test', 3, NOW() - INTERVAL 1 HOUR, NOW())");
$stmt->execute();
$testReportId = $conn->insert_id;
$stmt->close();

echo "Báo cáo test đã tạo với ID: $testReportId\n\n";

// Lấy thông tin báo cáo test
$stmt = $conn->prepare("SELECT id, title, created_at, updated_at FROM reports WHERE id = ?");
$stmt->bind_param('i', $testReportId);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();
$stmt->close();

echo "Báo cáo test: ID = {$report['id']}, Title = {$report['title']}\n";
echo "Created at: {$report['created_at']}\n";
echo "Updated at: {$report['updated_at']}\n\n";

// Test 1: Kiểm tra xem admin đã xem báo cáo này chưa
$hasViewed = hasUserViewedReportAfterUpdate($report['id'], 1);
echo "Admin đã xem báo cáo sau khi cập nhật: " . ($hasViewed ? 'CÓ' : 'CHƯA') . "\n";

// Test 2: Kiểm tra có nên hiển thị badge không
$shouldShow = shouldShowUpdatedBadge($report['id'], $report['updated_at'], $report['created_at']);
echo "Nên hiển thị badge 'Đã cập nhật': " . ($shouldShow ? 'CÓ' : 'KHÔNG') . "\n\n";

// Test 3: Ghi lại việc admin đã xem
echo "Ghi lại việc admin đã xem báo cáo...\n";
markReportAsViewed($report['id'], 1);

// Test 4: Kiểm tra lại sau khi đã xem
$hasViewedAfter = hasUserViewedReportAfterUpdate($report['id'], 1);
echo "Admin đã xem báo cáo sau khi cập nhật (sau khi ghi): " . ($hasViewedAfter ? 'CÓ' : 'CHƯA') . "\n";

$shouldShowAfter = shouldShowUpdatedBadge($report['id'], $report['updated_at'], $report['created_at']);
echo "Nên hiển thị badge 'Đã cập nhật' (sau khi ghi): " . ($shouldShowAfter ? 'CÓ' : 'KHÔNG') . "\n\n";

// Test 5: Kiểm tra với user khác (giả lập user ID = 2)
echo "=== Test với User khác (ID = 2) ===\n";
$hasViewedUser2 = hasUserViewedReportAfterUpdate($report['id'], 2);
echo "User ID = 2 đã xem báo cáo sau khi cập nhật: " . ($hasViewedUser2 ? 'CÓ' : 'CHƯA') . "\n";

// Giả lập session cho user 2
$_SESSION['user_id'] = 2;
$shouldShowUser2 = shouldShowUpdatedBadge($report['id'], $report['updated_at'], $report['created_at']);
echo "User ID = 2 nên hiển thị badge 'Đã cập nhật': " . ($shouldShowUser2 ? 'CÓ' : 'KHÔNG') . "\n\n";

echo "=== Kết quả ===\n";
if ($shouldShow && !$shouldShowAfter) {
    echo "✓ Logic hoạt động đúng: Badge hiển thị trước khi xem, mất sau khi xem\n";
} else {
    echo "✗ Logic có vấn đề\n";
}

if ($shouldShowUser2) {
    echo "✓ Logic phân biệt người dùng đúng: User khác vẫn thấy badge\n";
} else {
    echo "✗ Logic phân biệt người dùng có vấn đề\n";
}

// Xóa báo cáo test
$stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
$stmt->bind_param('i', $testReportId);
$stmt->execute();
$stmt->close();
echo "\nĐã xóa báo cáo test.\n";

$conn->close();
?> 