<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/i18n.php';
require_once '../app/helpers/translate.php';

// Require login
requireLogin();

$pageTitle = 'Báo cáo công việc';
$currentPage = 'reports';

$user_id = $_SESSION['user_id'];
$role = getUserRole();
$department_id = getUserDepartment();

// Handle actions
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        handleCreate();
        break;
    case 'store':
        handleStore();
        break;
    case 'view':
        handleView();
        break;
    case 'edit':
        handleEdit();
        break;
    case 'update':
        handleUpdate();
        break;
    case 'delete':
        handleDelete();
        break;
    case 'export':
        handleExport();
        break;
    default:
        handleIndex();
        break;
}

function handleIndex() {
    global $conn, $user_id, $role, $department_id;
    
    // Handle delete
    if (isset($_GET['delete'])) {
        $delete_id = intval($_GET['delete']);
        
        // Lấy thông tin báo cáo trước khi xóa
        $stmt = $conn->prepare('SELECT r.user_id, r.department_id, u.role as user_role FROM reports r JOIN users u ON r.user_id = u.id WHERE r.id = ?');
        $stmt->bind_param('i', $delete_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $report = $result->fetch_assoc();
        $stmt->close();
        
        if ($report && canDeleteReportByRole($report['user_id'], $report['user_role'], $report['department_id'])) {
            $stmt = $conn->prepare('DELETE FROM reports WHERE id = ?');
            $stmt->bind_param('i', $delete_id);
            $stmt->execute();
            $stmt->close();
            header('Location: /work/public/reports');
            exit;
        }
    }
    
    // Build query based on role
    $where = [];
    $params = [];
    $types = '';
    
    if ($role === 'admin') {
        // Admin sees all reports
        $users = $conn->query('SELECT id, name FROM users ORDER BY name');
        $departments = $conn->query('SELECT id, name FROM departments ORDER BY name');
        if (!empty($_GET['user_id'])) {
            $where[] = 'reports.user_id = ?';
            $params[] = $_GET['user_id'];
            $types .= 'i';
        }
        if (!empty($_GET['department_id'])) {
            $where[] = 'reports.department_id = ?';
            $params[] = $_GET['department_id'];
            $types .= 'i';
        }
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(reports.created_at) >= ?';
            $params[] = $_GET['from_date'];
            $types .= 's';
        }
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(reports.created_at) <= ?';
            $params[] = $_GET['to_date'];
            $types .= 's';
        }
    } elseif ($role === 'quanly') {
        // Quản lý thấy báo cáo của nhóm trưởng và user trong ban của mình
        $users = $conn->prepare('SELECT id, name FROM users WHERE department_id = ? AND (role = "nhomtruong" OR role = "user") ORDER BY name');
        $users->bind_param('i', $department_id);
        $users->execute();
        $users = $users->get_result();
        
        if (!empty($_GET['user_id'])) {
            $where[] = 'reports.user_id = ?';
            $params[] = $_GET['user_id'];
            $types .= 'i';
        }
        $where[] = 'reports.department_id = ?';
        $params[] = $department_id;
        $types .= 'i';
        $where[] = '(users.role = "nhomtruong" OR users.role = "user")';
        
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(reports.created_at) >= ?';
            $params[] = $_GET['from_date'];
            $types .= 's';
        }
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(reports.created_at) <= ?';
            $params[] = $_GET['to_date'];
            $types .= 's';
        }
    } elseif ($role === 'nhomtruong') {
        // Nhóm trưởng chỉ thấy báo cáo của user trong ban của mình
        $users = $conn->prepare('SELECT id, name FROM users WHERE department_id = ? AND role = "user" ORDER BY name');
        $users->bind_param('i', $department_id);
        $users->execute();
        $users = $users->get_result();
        
        if (!empty($_GET['user_id'])) {
            $where[] = 'reports.user_id = ?';
            $params[] = $_GET['user_id'];
            $types .= 'i';
        }
        $where[] = 'reports.department_id = ?';
        $params[] = $department_id;
        $types .= 'i';
        $where[] = 'users.role = "user"';
        
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(reports.created_at) >= ?';
            $params[] = $_GET['from_date'];
            $types .= 's';
        }
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(reports.created_at) <= ?';
            $params[] = $_GET['to_date'];
            $types .= 's';
        }
    } else {
        // User sees only their reports
        $where[] = 'reports.user_id = ?';
        $params[] = $user_id;
        $types .= 'i';
        
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(reports.created_at) >= ?';
            $params[] = $_GET['from_date'];
            $types .= 's';
        }
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(reports.created_at) <= ?';
            $params[] = $_GET['to_date'];
            $types .= 's';
        }
    }
    
    $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = 10;
    $offset = ($page - 1) * $limit;
    
    // Count total reports
    $count_sql = "SELECT COUNT(*) FROM reports JOIN users ON reports.user_id = users.id $where_sql";
    $count_stmt = $conn->prepare($count_sql);
    if ($params) $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $count_stmt->bind_result($total);
    $count_stmt->fetch();
    $count_stmt->close();
    $total_pages = ceil($total / $limit);
    
    // Get reports
    $sql = "SELECT reports.id, reports.title, reports.title_zh, reports.content, reports.content_zh, users.name, users.name_zh, reports.user_id, reports.created_at, reports.updated_at, departments.name as department_name, users.role as user_role, reports.department_id
            FROM reports 
            JOIN users ON reports.user_id = users.id 
            LEFT JOIN departments ON reports.department_id = departments.id 
            $where_sql 
            ORDER BY reports.created_at DESC 
            LIMIT $limit OFFSET $offset";
    
    $stmt = $conn->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    $stmt->close();
    
    // Include view
    include '../app/views/reports/index.php';
}

function handleCreate() {
    $pageTitle = 'Tạo báo cáo mới';
    $currentPage = 'reports';
    $isEdit = false;
    include '../app/views/reports/form.php';
}

function handleStore() {
    global $conn, $user_id, $department_id;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $titleZh = isset($_POST['title_zh']) ? trim($_POST['title_zh']) : '';
        $contentZh = isset($_POST['content_zh']) ? trim($_POST['content_zh']) : '';
        
        if (empty($title) || empty($content)) {
            $_SESSION['error'] = __('please_fill_all_fields');
            header('Location: /work/public/reports/create');
            exit;
        }
        
        // Nếu chưa có bản dịch thì mới dịch
        if (empty($titleZh)) {
            $titleZh = translateText($title, 'zh');
        }
        if (empty($contentZh)) {
            $contentZh = translateText($content, 'zh');
        }
        
        $stmt = $conn->prepare('INSERT INTO reports (user_id, title, title_zh, content, content_zh, department_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('issssi', $user_id, $title, $titleZh, $content, $contentZh, $department_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = __('report_created_success');
            header('Location: /work/public/reports');
        } else {
            $_SESSION['error'] = __('error_occurred');
            header('Location: /work/public/reports/create');
        }
        $stmt->close();
        exit;
    }
}

function handleView() {
    global $conn, $user_id, $role, $department_id;
    $pageTitle = 'Chi tiết báo cáo';
    $currentPage = 'reports';
    
    $report_id = intval($_GET['id'] ?? 0);
    if (!$report_id) {
        header('Location: /work/public/reports');
        exit;
    }
    
    // Get report with user and department info
    $sql = "SELECT reports.*, users.name as user_name, users.name_zh as user_name_zh, users.role as user_role, departments.name as department_name 
            FROM reports 
            JOIN users ON reports.user_id = users.id 
            LEFT JOIN departments ON reports.department_id = departments.id 
            WHERE reports.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();
    $stmt->close();
    
    if (!$report) {
        header('Location: /work/public/reports');
        exit;
    }
    
    // Check permissions using new logic
    if (!canViewReport($report['user_id'], $report['user_role'], $report['department_id'])) {
        header('Location: /work/public/reports');
        exit;
    }
    
    // Ghi lại việc người dùng đã xem báo cáo
    markReportAsViewed($report_id, $user_id);
    
    include '../app/views/reports/view.php';
}

function handleEdit() {
    global $conn, $user_id, $role, $department_id;
    $pageTitle = 'Chỉnh sửa báo cáo';
    $currentPage = 'reports';
    $isEdit = true;
    
    $report_id = intval($_GET['id'] ?? 0);
    if (!$report_id) {
        header('Location: /work/public/reports');
        exit;
    }
    
    // Lấy thông tin báo cáo với role của người tạo
    $stmt = $conn->prepare('SELECT r.*, u.role as user_role FROM reports r JOIN users u ON r.user_id = u.id WHERE r.id = ?');
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();
    $stmt->close();
    
    if (!$report) {
        header('Location: /work/public/reports');
        exit;
    }
    
    // Check permissions using new logic
    if (!canEditReportByRole($report['user_id'], $report['user_role'], $report['department_id'])) {
        header('Location: /work/public/reports');
        exit;
    }
    
    include '../app/views/reports/form.php';
}

function handleUpdate() {
    global $conn, $user_id, $role, $department_id;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $report_id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $titleZh = isset($_POST['title_zh']) ? trim($_POST['title_zh']) : '';
        $contentZh = isset($_POST['content_zh']) ? trim($_POST['content_zh']) : '';
        
        if (!$report_id || empty($title) || empty($content)) {
            $_SESSION['error'] = __('please_fill_all_fields');
            header('Location: /work/public/reports/edit?id=' . $report_id);
            exit;
        }
        
        // Check permissions
        $stmt = $conn->prepare('SELECT r.user_id, r.department_id, u.role as user_role, r.title, r.content, r.title_zh, r.content_zh FROM reports r JOIN users u ON r.user_id = u.id WHERE r.id = ?');
        $stmt->bind_param('i', $report_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $report = $result->fetch_assoc();
        $stmt->close();
        
        if (!$report) {
            header('Location: /work/public/reports');
            exit;
        }
        
        if (!canEditReportByRole($report['user_id'], $report['user_role'], $report['department_id'])) {
            header('Location: /work/public/reports');
            exit;
        }
        
        // Sử dụng bản dịch từ form, nếu trống thì dịch tự động
        if (empty($titleZh)) {
            $titleZh = translateText($title, 'zh');
        }
        if (empty($contentZh)) {
            $contentZh = translateText($content, 'zh');
        }
        
        // Update report
        $stmt = $conn->prepare('UPDATE reports SET title = ?, title_zh = ?, content = ?, content_zh = ?, updated_at = NOW() WHERE id = ?');
        $stmt->bind_param('ssssi', $title, $titleZh, $content, $contentZh, $report_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = __('report_updated_success');
            header('Location: /work/public/reports');
        } else {
            $_SESSION['error'] = __('error_occurred');
            header('Location: /work/public/reports/edit?id=' . $report_id);
        }
        $stmt->close();
        exit;
    }
}

function handleDelete() {
    global $conn, $user_id, $role, $department_id;
    
    $report_id = intval($_GET['id'] ?? 0);
    if (!$report_id) {
        header('Location: /work/public/reports');
        exit;
    }
    
    // Check permissions
    $stmt = $conn->prepare('SELECT r.user_id, r.department_id, u.role as user_role FROM reports r JOIN users u ON r.user_id = u.id WHERE r.id = ?');
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();
    $stmt->close();
    
    if (!$report) {
        header('Location: /work/public/reports');
        exit;
    }
    
    if (!canDeleteReportByRole($report['user_id'], $report['user_role'], $report['department_id'])) {
        header('Location: /work/public/reports');
        exit;
    }
    
    // Delete report
    $stmt = $conn->prepare('DELETE FROM reports WHERE id = ?');
    $stmt->bind_param('i', $report_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Báo cáo đã được xóa thành công';
    } else {
        $_SESSION['error'] = 'Có lỗi xảy ra khi xóa báo cáo';
    }
    $stmt->close();
    
    header('Location: /work/public/reports');
    exit;
}

function handleExport() {
    global $conn, $user_id, $role, $department_id;
    require_once '../app/helpers/export_excel.php';
    
    // Build query based on role (same logic as handleIndex)
    $where = [];
    $params = [];
    $types = '';
    
    if ($role === 'admin') {
        // Admin sees all reports
        if (!empty($_GET['user_id'])) {
            $where[] = 'reports.user_id = ?';
            $params[] = $_GET['user_id'];
            $types .= 'i';
        }
        if (!empty($_GET['department_id'])) {
            $where[] = 'reports.department_id = ?';
            $params[] = $_GET['department_id'];
            $types .= 'i';
        }
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(reports.created_at) >= ?';
            $params[] = $_GET['from_date'];
            $types .= 's';
        }
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(reports.created_at) <= ?';
            $params[] = $_GET['to_date'];
            $types .= 's';
        }
    } elseif ($role === 'quanly') {
        // Quản lý thấy báo cáo của nhóm trưởng và user trong ban của mình
        if (!empty($_GET['user_id'])) {
            $where[] = 'reports.user_id = ?';
            $params[] = $_GET['user_id'];
            $types .= 'i';
        }
        $where[] = 'reports.department_id = ?';
        $params[] = $department_id;
        $types .= 'i';
        $where[] = '(users.role = "nhomtruong" OR users.role = "user")';
        
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(reports.created_at) >= ?';
            $params[] = $_GET['from_date'];
            $types .= 's';
        }
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(reports.created_at) <= ?';
            $params[] = $_GET['to_date'];
            $types .= 's';
        }
    } elseif ($role === 'nhomtruong') {
        // Nhóm trưởng chỉ thấy báo cáo của user trong ban của mình
        if (!empty($_GET['user_id'])) {
            $where[] = 'reports.user_id = ?';
            $params[] = $_GET['user_id'];
            $types .= 'i';
        }
        $where[] = 'reports.department_id = ?';
        $params[] = $department_id;
        $types .= 'i';
        $where[] = 'users.role = "user"';
        
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(reports.created_at) >= ?';
            $params[] = $_GET['from_date'];
            $types .= 's';
        }
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(reports.created_at) <= ?';
            $params[] = $_GET['to_date'];
            $types .= 's';
        }
    } else {
        // User sees only their reports
        $where[] = 'reports.user_id = ?';
        $params[] = $user_id;
        $types .= 'i';
        
        if (!empty($_GET['from_date'])) {
            $where[] = 'DATE(reports.created_at) >= ?';
            $params[] = $_GET['from_date'];
            $types .= 's';
        }
        if (!empty($_GET['to_date'])) {
            $where[] = 'DATE(reports.created_at) <= ?';
            $params[] = $_GET['to_date'];
            $types .= 's';
        }
    }
    
    $where_sql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
    
    // Lấy toàn bộ dữ liệu (không phân trang)
    $sql = "SELECT reports.id, reports.title, reports.title_zh, reports.content, reports.content_zh, users.name, users.name_zh, reports.user_id, reports.created_at, reports.updated_at, departments.name as department_name, reports.department_id 
            FROM reports 
            JOIN users ON reports.user_id = users.id 
            LEFT JOIN departments ON reports.department_id = departments.id 
            $where_sql 
            ORDER BY reports.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    $stmt->close();
    
    // Gọi helper để xuất Excel
    exportReportsToExcel($reports, $role, $user_id, $department_id);
    exit;
}
?> 