<?php
session_start();
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';

if (isset($_GET['debug_session'])) {
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    exit;
}

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Remove 'work/public' from path if present
$path = str_replace('work/public', '', $path);
$path = trim($path, '/');

// Handle sub-paths like reports/create, reports/edit?id=1
$path_parts = explode('/', $path);
$main_path = $path_parts[0] ?? 'dashboard';
$sub_path = $path_parts[1] ?? '';

// Default to dashboard if no path
if (empty($main_path) || $main_path === 'work' || $main_path === 'public') {
    // Nếu chưa đăng nhập thì chuyển về trang login
    if (!isset($_SESSION['user_id'])) {
        header('Location: /work/public/login');
        exit;
    }
    $main_path = 'dashboard';
}

// Route mapping
$routes = [
    'dashboard' => '../app/controllers/DashboardController.php',
    'reports' => '../app/controllers/ReportController.php',
    'users' => '../app/controllers/UserController.php',
    'tasks' => '../app/controllers/TaskController.php',
    'notifications' => '../app/controllers/NotificationController.php',
    'login' => '../app/controllers/AuthController.php',
    'logout' => '../app/controllers/AuthController.php',
    'profile' => '../app/controllers/ProfileController.php',
    'register' => '../app/controllers/RegisterController.php',
    'language' => '../app/controllers/LanguageController.php'
];

// Check if route exists
if (isset($routes[$main_path])) {
    $controller_file = $routes[$main_path];
    if (file_exists($controller_file)) {
        // Pass sub-path as action parameter
        if (!empty($sub_path)) {
            $_GET['action'] = $sub_path;
        }
        if ($main_path === 'logout' && empty($sub_path)) {
            $_GET['action'] = 'logout';
        }
        require_once $controller_file;
    } else {
        http_response_code(404);
        echo "Controller not found: $controller_file";
    }
} else {
    http_response_code(404);
    echo "Page not found: $main_path";
}
?> 