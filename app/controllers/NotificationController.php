<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/i18n.php';

// Require login
requireLogin();

$pageTitle = 'Thông báo';
$currentPage = 'notifications';

// Check permissions - admin, quản lý và nhóm trưởng mới được xem thông báo
if (!isAdmin() && !isManager() && !isTeamLeader()) {
    header('Location: /work/public/dashboard');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = getUserRole();
$department_id = getUserDepartment();

// Xóa các include header/footer/layout cũ, chỉ render view
include '../app/views/notifications/index.php'; 