<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/i18n.php';

// Require login
requireLogin();

$pageTitle = 'Quản lý công việc';
$currentPage = 'tasks';

// Check permissions - chỉ admin và quản lý mới được quản lý công việc
if (!isAdmin() && !isManager()) {
    header('Location: /work/public/dashboard');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = getUserRole();
$department_id = getUserDepartment();

// Xóa các include header/footer/layout cũ, chỉ render view
include '../app/views/tasks/index.php'; 