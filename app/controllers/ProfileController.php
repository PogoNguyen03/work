<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/translate.php';

// Require login
requireLogin();

$pageTitle = 'Hồ sơ cá nhân';
$currentPage = 'profile';

$user_id = $_SESSION['user_id'];
$role = getUserRole();
$department_id = getUserDepartment();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $name_zh = trim($_POST['name_zh'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($name) || empty($email)) {
        $_SESSION['error'] = __('please_fill_all_fields');
    } else {
        // Nếu chưa có tên tiếng Trung thì dịch tự động
        if (empty($name_zh)) {
            $name_zh = translateText($name, 'zh');
        }
        
        // Cập nhật thông tin người dùng
        $stmt = $conn->prepare('UPDATE users SET name = ?, name_zh = ?, email = ? WHERE id = ?');
        $stmt->bind_param('sssi', $name, $name_zh, $email, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = __('profile_updated_success');
            // Cập nhật session
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
        } else {
            $_SESSION['error'] = __('error_occurred');
        }
        $stmt->close();
    }
    
    header('Location: /work/public/profile');
    exit;
}

// Get user info
$stmt = $conn->prepare("
    SELECT u.*, d.name as department_name 
    FROM users u 
    LEFT JOIN departments d ON u.department_id = d.id 
    WHERE u.id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Xóa các include header/footer/layout cũ, chỉ render view
include '../app/views/profile/index.php'; 