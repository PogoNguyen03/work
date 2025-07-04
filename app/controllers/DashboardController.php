<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/i18n.php';

// Require login
requireLogin();

$pageTitle = __('dashboard');
$currentPage = 'dashboard';

// Get statistics
$user_id = $_SESSION['user_id'];
$role = getUserRole();
$department_id = getUserDepartment();

// Get total reports count
$reports_count = 0;
if ($role === 'admin') {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM reports");
    $stmt->execute();
    $stmt->bind_result($reports_count);
    $stmt->fetch();
    $stmt->close();
} elseif ($role === 'quanly') {
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM reports r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.department_id = ? AND (u.role = 'nhomtruong' OR u.role = 'user')
    ");
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $stmt->bind_result($reports_count);
    $stmt->fetch();
    $stmt->close();
} elseif ($role === 'nhomtruong') {
    $stmt = $conn->prepare("
        SELECT COUNT(*) FROM reports r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.department_id = ? AND u.role = 'user'
    ");
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $stmt->bind_result($reports_count);
    $stmt->fetch();
    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM reports WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->bind_result($reports_count);
    $stmt->fetch();
    $stmt->close();
}

// Get total users count
$users_count = 0;
if ($role === 'admin') {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $stmt->bind_result($users_count);
    $stmt->fetch();
    $stmt->close();
} elseif ($role === 'quanly') {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE department_id = ? AND (role = 'nhomtruong' OR role = 'user')");
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $stmt->bind_result($users_count);
    $stmt->fetch();
    $stmt->close();
} elseif ($role === 'nhomtruong') {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE department_id = ? AND role = 'user'");
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $stmt->bind_result($users_count);
    $stmt->fetch();
    $stmt->close();
}

// Get recent reports
$recent_reports = [];
if ($role === 'admin') {
    $stmt = $conn->prepare("
        SELECT r.id, r.title, r.title_zh, r.created_at, r.updated_at, u.name as user_name, d.name as department_name, u.role as user_role
        FROM reports r 
        JOIN users u ON r.user_id = u.id 
        LEFT JOIN departments d ON r.department_id = d.id 
        ORDER BY r.created_at DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recent_reports[] = $row;
    }
    $stmt->close();
} elseif ($role === 'quanly') {
    $stmt = $conn->prepare("
        SELECT r.id, r.title, r.title_zh, r.created_at, r.updated_at, u.name as user_name, d.name as department_name, u.role as user_role
        FROM reports r 
        JOIN users u ON r.user_id = u.id 
        LEFT JOIN departments d ON r.department_id = d.id 
        WHERE r.department_id = ? AND (u.role = 'nhomtruong' OR u.role = 'user')
        ORDER BY r.created_at DESC 
        LIMIT 5
    ");
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recent_reports[] = $row;
    }
    $stmt->close();
} elseif ($role === 'nhomtruong') {
    $stmt = $conn->prepare("
        SELECT r.id, r.title, r.title_zh, r.created_at, r.updated_at, u.name as user_name, d.name as department_name, u.role as user_role
        FROM reports r 
        JOIN users u ON r.user_id = u.id 
        LEFT JOIN departments d ON r.department_id = d.id 
        WHERE r.department_id = ? AND u.role = 'user'
        ORDER BY r.created_at DESC 
        LIMIT 5
    ");
    $stmt->bind_param('i', $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recent_reports[] = $row;
    }
    $stmt->close();
} else {
    $stmt = $conn->prepare("
        SELECT r.id, r.title, r.title_zh, r.created_at, r.updated_at, u.name as user_name, d.name as department_name, u.role as user_role
        FROM reports r 
        JOIN users u ON r.user_id = u.id 
        LEFT JOIN departments d ON r.department_id = d.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC 
        LIMIT 5
    ");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $recent_reports[] = $row;
    }
    $stmt->close();
}

// Include dashboard view (sẽ tự động include layout mới)
include '../app/views/dashboard/index.php'; 