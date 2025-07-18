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

$pageTitle = 'Thông báo';
$currentPage = 'notifications';

// Check permissions - admin, quản lý và nhóm trưởng mới được xem thông báo
if (!isAdmin() && !isManager() && !isTeamLeader()) {
    header('Location: /dashboard');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = getUserRole();
$department_id = getUserDepartment();

// Xóa các include header/footer/layout cũ, chỉ render view
include $base_path . '/app/views/notifications/index.php'; 