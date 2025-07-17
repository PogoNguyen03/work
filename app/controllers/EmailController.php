<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/i18n.php';

requireLogin();
$role = getUserRole();
if ($role !== 'admin' && $role !== 'quanly') {
    header('Location: /work/public/dashboard');
    exit();
}
$pageTitle = 'Quản lý email liên hệ';
$currentPage = 'email';

// Xử lý hành động
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'delete' && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success_message'] = 'Đã xóa email liên hệ!';
        }
        if ($_POST['action'] === 'mark_read' && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success_message'] = 'Đã đánh dấu đã đọc!';
        }
    }
    header('Location: /work/public/email');
    exit();
}
// Lấy danh sách email
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$email_list = [];
while ($row = $result->fetch_assoc()) {
    $email_list[] = $row;
}
// Lấy chi tiết nếu có id
$email_detail = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $email_detail = $res->fetch_assoc();
    $stmt->close();
}
include '../app/views/email/index.php'; 