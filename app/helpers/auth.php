<?php
// Auth helper

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /work/public/login');
        exit;
    }
}

function getUserRole() {
    return $_SESSION['role'] ?? 'user';
}

function getUserDepartment() {
    return $_SESSION['department_id'] ?? null;
}

function isAdmin() {
    return getUserRole() === 'admin';
}

function isManager() {
    return getUserRole() === 'quanly';
}

function isTeamLeader() {
    return getUserRole() === 'nhomtruong';
}

function canViewAllReports() {
    $role = getUserRole();
    return $role === 'admin' || $role === 'quanly' || $role === 'nhomtruong';
}

// Hàm mới: Kiểm tra quyền xem báo cáo theo role và department
function canViewReport($reportUserId, $reportUserRole, $reportDepartmentId) {
    $role = getUserRole();
    $userId = $_SESSION['user_id'] ?? 0;
    $userDepartmentId = getUserDepartment();
    
    // Admin thấy tất cả
    if ($role === 'admin') {
        return true;
    }
    
    // Quản lý thấy báo cáo của nhóm trưởng và user trong ban của mình
    if ($role === 'quanly') {
        return $reportDepartmentId == $userDepartmentId && 
               ($reportUserRole === 'nhomtruong' || $reportUserRole === 'user');
    }
    
    // Nhóm trưởng chỉ thấy báo cáo của user trong ban của mình
    if ($role === 'nhomtruong') {
        return $reportDepartmentId == $userDepartmentId && $reportUserRole === 'user';
    }
    
    // User chỉ thấy báo cáo của bản thân
    return $reportUserId == $userId;
}

function canDeleteReport($reportUserId, $reportDepartmentId) {
    $role = getUserRole();
    $userId = $_SESSION['user_id'] ?? 0;
    $userDepartmentId = getUserDepartment();
    
    // Admin xóa tất cả
    if ($role === 'admin') {
        return true;
    }
    
    // Quản lý xóa báo cáo của nhóm trưởng và user trong ban của mình
    if ($role === 'quanly') {
        return $reportDepartmentId == $userDepartmentId;
    }
    
    // Nhóm trưởng chỉ xóa báo cáo của user trong ban của mình
    if ($role === 'nhomtruong') {
        return $reportDepartmentId == $userDepartmentId;
    }
    
    // User chỉ xóa báo cáo của bản thân
    return $reportUserId == $userId;
}

// Hàm mới: Kiểm tra quyền xóa theo role của người tạo báo cáo
function canDeleteReportByRole($reportUserId, $reportUserRole, $reportDepartmentId) {
    $role = getUserRole();
    $userId = $_SESSION['user_id'] ?? 0;
    $userDepartmentId = getUserDepartment();
    
    // Admin xóa tất cả
    if ($role === 'admin') {
        return true;
    }
    
    // User chỉ xóa báo cáo của bản thân
    if ($role === 'user') {
        return $reportUserId == $userId;
    }
    
    // Quản lý và nhóm trưởng không được xóa báo cáo
    return false;
}

// Hàm mới: Kiểm tra quyền chỉnh sửa báo cáo theo role của người tạo báo cáo
function canEditReportByRole($reportUserId, $reportUserRole, $reportDepartmentId) {
    $role = getUserRole();
    $userId = $_SESSION['user_id'] ?? 0;
    $userDepartmentId = getUserDepartment();
    
    // Admin chỉnh sửa tất cả
    if ($role === 'admin') {
        return true;
    }
    
    // User chỉ chỉnh sửa báo cáo của bản thân
    if ($role === 'user') {
        return $reportUserId == $userId;
    }
    
    // Quản lý và nhóm trưởng không được chỉnh sửa báo cáo
    return false;
}

// Hàm mới: Kiểm tra xem người dùng đã xem báo cáo sau khi cập nhật chưa
function hasUserViewedReportAfterUpdate($reportId, $userId) {
    global $conn;
    
    // Lấy thông tin báo cáo
    $stmt = $conn->prepare('SELECT updated_at FROM reports WHERE id = ?');
    $stmt->bind_param('i', $reportId);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();
    $stmt->close();
    
    if (!$report || !$report['updated_at']) {
        return true; // Nếu không có updated_at thì coi như đã xem
    }
    
    // Kiểm tra xem người dùng đã xem báo cáo sau khi cập nhật chưa
    $stmt = $conn->prepare('SELECT viewed_at FROM report_views WHERE report_id = ? AND user_id = ?');
    $stmt->bind_param('ii', $reportId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $view = $result->fetch_assoc();
    $stmt->close();
    
    if (!$view) {
        return false; // Chưa xem
    }
    
    // So sánh thời gian xem với thời gian cập nhật
    return strtotime($view['viewed_at']) >= strtotime($report['updated_at']);
}

// Hàm mới: Ghi lại việc người dùng đã xem báo cáo
function markReportAsViewed($reportId, $userId) {
    global $conn;
    
    // Insert hoặc update record trong bảng report_views
    $stmt = $conn->prepare('INSERT INTO report_views (report_id, user_id, viewed_at) VALUES (?, ?, NOW()) 
                           ON DUPLICATE KEY UPDATE viewed_at = NOW()');
    $stmt->bind_param('ii', $reportId, $userId);
    $stmt->execute();
    $stmt->close();
}

// Hàm mới: Kiểm tra xem có nên hiển thị badge "Đã cập nhật" không
function shouldShowUpdatedBadge($reportId, $reportUpdatedAt, $reportCreatedAt) {
    $userId = $_SESSION['user_id'] ?? 0;
    
    // Chỉ hiển thị nếu báo cáo thực sự đã được cập nhật
    if (!$reportUpdatedAt || $reportUpdatedAt == $reportCreatedAt) {
        return false;
    }
    
    // Kiểm tra xem người dùng hiện tại đã xem báo cáo sau khi cập nhật chưa
    return !hasUserViewedReportAfterUpdate($reportId, $userId);
}
?> 