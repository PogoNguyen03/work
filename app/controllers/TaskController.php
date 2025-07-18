<?php
// Sử dụng đường dẫn tuyệt đối để tránh lỗi open_basedir
$base_path = realpath(__DIR__ . '/../..');
require_once $base_path . '/app/middleware/IpRestrictionMiddleware.php';
IpRestrictionMiddleware::handle();
require_once $base_path . '/app/helpers/db.php';
require_once $base_path . '/app/helpers/auth.php';
require_once $base_path . '/app/helpers/i18n.php';

// Require login
requireLogin();

$pageTitle = 'Quản lý công việc';
$currentPage = 'tasks';

// Check permissions - chỉ admin và quản lý mới được quản lý công việc
if (!isAdmin() && !isManager()) {
    header('Location: /dashboard');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = getUserRole();
$department_id = getUserDepartment();

// Xóa các include header/footer/layout cũ, chỉ render view
include $base_path . '/app/views/tasks/index.php'; 