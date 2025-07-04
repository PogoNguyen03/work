<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/i18n.php';

// Require login
requireLogin();

$pageTitle = 'Quản lý người dùng';
$currentPage = 'users';

// Check permissions - cho phép admin, quản lý và nhóm trưởng
if (!isAdmin() && !isManager() && !isTeamLeader()) {
    header('Location: /work/public/dashboard');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = getUserRole();
$department_id = getUserDepartment();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        handleCreate();
        break;
    case 'store':
        handleStore();
        break;
    case 'edit':
        handleEdit();
        break;
    case 'update':
        handleUpdate();
        break;
    case 'verify':
        handleVerify();
        break;
    default:
        handleIndex();
        break;
}

function getDepartments() {
    global $conn, $role, $department_id;
    $departments = [];
    
    if ($role === 'admin') {
        // Admin thấy tất cả phòng ban
        $result = $conn->query('SELECT id, name FROM departments ORDER BY name');
    } else {
        // Quản lý và nhóm trưởng chỉ thấy phòng ban của mình
        $stmt = $conn->prepare('SELECT id, name FROM departments WHERE id = ? ORDER BY name');
        $stmt->bind_param('i', $department_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }
    
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
    return $departments;
}

function handleIndex() {
    global $conn, $role, $department_id;
    $users = [];
    
    if ($role === 'admin') {
        // Admin thấy tất cả người dùng
        $stmt = $conn->prepare("
            SELECT u.id, u.name, u.name_zh, u.email, u.role, u.created_at, d.name as department_name, u.department_id, u.is_verified
            FROM users u 
            LEFT JOIN departments d ON u.department_id = d.id 
            ORDER BY u.created_at DESC
        ");
        $stmt->execute();
    } else {
        // Quản lý và nhóm trưởng chỉ thấy người dùng trong phòng ban của mình
        $stmt = $conn->prepare("
            SELECT u.id, u.name, u.name_zh, u.email, u.role, u.created_at, d.name as department_name, u.department_id, u.is_verified
            FROM users u 
            LEFT JOIN departments d ON u.department_id = d.id 
            WHERE u.department_id = ?
            ORDER BY u.created_at DESC
        ");
        $stmt->bind_param('i', $department_id);
        $stmt->execute();
    }
    
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    $stmt->close();
    
    include '../app/views/users/index.php';
}

function handleCreate() {
    global $role;
    $pageTitle = 'Thêm người dùng mới';
    $currentPage = 'users';
    $isEdit = false;
    $departments = getDepartments();
    $user = [];
    include '../app/views/users/form.php';
}

function handleStore() {
    global $conn, $role, $department_id;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $name_zh = trim($_POST['name_zh'] ?? '');
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $user_department_id = intval($_POST['department_id']);
        $user_role = $_POST['role'];
        $is_verified = isset($_POST['is_verified']) ? 1 : 0;
        
        if (!$name || !$email || !$password) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin.';
            header('Location: /work/public/users/create');
            exit;
        }
        
        // Kiểm tra quyền: quản lý và nhóm trưởng chỉ được thêm user vào phòng ban của mình
        if ($role !== 'admin' && $user_department_id !== $department_id) {
            $_SESSION['error'] = 'Bạn chỉ được thêm người dùng vào phòng ban của mình.';
            header('Location: /work/public/users/create');
            exit;
        }
        
        // Kiểm tra quyền role: quản lý và nhóm trưởng không được tạo admin
        if ($role !== 'admin' && $user_role === 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền tạo tài khoản admin.';
            header('Location: /work/public/users/create');
            exit;
        }
        
        // Nếu chưa có tên tiếng Trung thì dịch tự động
        if (empty($name_zh)) {
            require_once '../app/helpers/translate.php';
            $name_zh = translateText($name, 'zh');
        }
        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (name, name_zh, email, password, role, department_id, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssii', $name, $name_zh, $email, $hash, $user_role, $user_department_id, $is_verified);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Đã thêm người dùng mới!';
            header('Location: /work/public/users');
        } else {
            $_SESSION['error'] = 'Email đã tồn tại hoặc lỗi hệ thống!';
            header('Location: /work/public/users/create');
        }
        $stmt->close();
        exit;
    }
}

function handleEdit() {
    global $conn, $role, $department_id;
    $pageTitle = 'Chỉnh sửa người dùng';
    $currentPage = 'users';
    $isEdit = true;
    $departments = getDepartments();
    $id = intval($_GET['id'] ?? 0);
    $user = [];
    
    if ($role === 'admin') {
        $stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->bind_param('i', $id);
    } else {
        // Quản lý và nhóm trưởng chỉ được sửa user trong phòng ban của mình
        $stmt = $conn->prepare('SELECT * FROM users WHERE id = ? AND department_id = ?');
        $stmt->bind_param('ii', $id, $department_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (!$user) {
        $_SESSION['error'] = 'Người dùng không tồn tại hoặc bạn không có quyền chỉnh sửa!';
        header('Location: /work/public/users');
        exit;
    }
    
    include '../app/views/users/form.php';
}

function handleUpdate() {
    global $conn, $role, $department_id;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $name_zh = trim($_POST['name_zh'] ?? '');
        $user_department_id = intval($_POST['department_id']);
        $user_role = $_POST['role'];
        $is_verified = isset($_POST['is_verified']) ? 1 : 0;
        
        if (!$name) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin.';
            header('Location: /work/public/users/edit?id=' . $id);
            exit;
        }
        
        // Kiểm tra quyền: quản lý và nhóm trưởng chỉ được sửa user trong phòng ban của mình
        if ($role !== 'admin') {
            $check_stmt = $conn->prepare('SELECT department_id FROM users WHERE id = ?');
            $check_stmt->bind_param('i', $id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $user_data = $check_result->fetch_assoc();
            $check_stmt->close();
            
            if (!$user_data || $user_data['department_id'] !== $department_id) {
                $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa người dùng này!';
                header('Location: /work/public/users');
                exit;
            }
            
            // Quản lý và nhóm trưởng không được chuyển user ra khỏi phòng ban của mình
            if ($user_department_id !== $department_id) {
                $_SESSION['error'] = 'Bạn chỉ được quản lý người dùng trong phòng ban của mình.';
                header('Location: /work/public/users/edit?id=' . $id);
                exit;
            }
        }
        
        // Kiểm tra quyền role: quản lý và nhóm trưởng không được tạo admin
        if ($role !== 'admin' && $user_role === 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền tạo tài khoản admin.';
            header('Location: /work/public/users/edit?id=' . $id);
            exit;
        }
        
        // Nếu chưa có tên tiếng Trung thì dịch tự động
        if (empty($name_zh)) {
            require_once '../app/helpers/translate.php';
            $name_zh = translateText($name, 'zh');
        }
        
        $stmt = $conn->prepare('UPDATE users SET name=?, name_zh=?, department_id=?, role=?, is_verified=? WHERE id=?');
        $stmt->bind_param('ssisii', $name, $name_zh, $user_department_id, $user_role, $is_verified, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Đã cập nhật người dùng!';
            header('Location: /work/public/users');
        } else {
            $_SESSION['error'] = 'Lỗi hệ thống!';
            header('Location: /work/public/users/edit?id=' . $id);
        }
        $stmt->close();
        exit;
    }
}

function handleVerify() {
    global $conn, $role, $department_id;
    $id = intval($_GET['id'] ?? 0);
    
    // Kiểm tra quyền: quản lý và nhóm trưởng chỉ được verify user trong phòng ban của mình
    if ($role !== 'admin') {
        $check_stmt = $conn->prepare('SELECT department_id FROM users WHERE id = ?');
        $check_stmt->bind_param('i', $id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $user_data = $check_result->fetch_assoc();
        $check_stmt->close();
        
        if (!$user_data || $user_data['department_id'] !== $department_id) {
            $_SESSION['error'] = 'Bạn không có quyền xác nhận tài khoản này!';
            header('Location: /work/public/users');
            exit;
        }
    }
    
    $stmt = $conn->prepare('UPDATE users SET is_verified=1 WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success'] = 'Đã xác nhận tài khoản!';
    header('Location: /work/public/users');
    exit;
} 