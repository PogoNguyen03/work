<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/i18n.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $department_id = intval($_POST['department_id'] ?? 0);
    if ($password !== $confirm) {
        $message = 'Mật khẩu xác nhận không khớp!';
    } else if ($department_id <= 0) {
        $message = 'Vui lòng chọn ban/phòng!';
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (name, email, password, department_id) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('sssi', $name, $email, $password_hash, $department_id);
        if ($stmt->execute()) {
            $message = 'Tài khoản của bạn đã được gửi lên admin duyệt. Vui lòng chờ xác nhận.';
        } else {
            $message = 'Email đã tồn tại hoặc lỗi hệ thống!';
        }
        $stmt->close();
    }
}
// Lấy danh sách ban/phòng
$departments = $conn->query('SELECT id, name FROM departments ORDER BY name');
include '../app/views/auth/register.php'; 