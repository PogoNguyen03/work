<?php
session_start();

// Sử dụng đường dẫn tuyệt đối để tránh lỗi open_basedir
$base_path = realpath(__DIR__ . '/..');
require_once $base_path . '/app/helpers/db.php';
require_once $base_path . '/app/helpers/auth.php';
require_once $base_path . '/app/helpers/ip.php';

if (isset($_GET['debug_session'])) {
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    exit;
}

// Chặn truy cập ngoài mạng nội bộ cho các route không phải public
$user_ip = get_client_ip();
$public_routes = [
    '/', '/index.php', '/homepage', '/about', '/contact', '/assets', '/uploads', '/favicon.ico', '/robots.txt', '/sitemap.xml'
];
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri_path = parse_url($request_uri, PHP_URL_PATH);
$allow = false;
foreach ($public_routes as $pub) {
    if (strpos($uri_path, $pub) === 0) {
        $allow = true;
        break;
    }
}
if (!is_internal_ip($user_ip) && !$allow) {
    // Redirect về trang chủ hoặc trả về 403
    header('Location: /');
    exit;
}

// Simple routing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Remove 'public' from path if present
$path = str_replace('public', '', $path);
$path = trim($path, '/');

// Handle sub-paths like reports/create, reports/edit?id=1
$path_parts = explode('/', $path);
$main_path = $path_parts[0] ?? '';
$sub_path = $path_parts[1] ?? '';

// Check if this is a public website route (not logged in)
$public_routes = ['', 'about', 'contact'];
$is_public_route = in_array($main_path, $public_routes);

// Default to public homepage if no path
if (empty($main_path)) {
    $main_path = '';
    $is_public_route = true;
}

// Route mapping for admin panel
$admin_routes = [
    'dashboard' => $base_path . '/app/controllers/DashboardController.php',
    'reports' => $base_path . '/app/controllers/ReportController.php',
    'users' => $base_path . '/app/controllers/UserController.php',
    'tasks' => $base_path . '/app/controllers/TaskController.php',
    'notifications' => $base_path . '/app/controllers/NotificationController.php',
    'website' => $base_path . '/app/controllers/WebsiteController.php',
    'email' => $base_path . '/app/controllers/EmailController.php',
    'login' => $base_path . '/app/controllers/AuthController.php',
    'logout' => $base_path . '/app/controllers/AuthController.php',
    'profile' => $base_path . '/app/controllers/ProfileController.php',
    'register' => $base_path . '/app/controllers/RegisterController.php',
    'language' => $base_path . '/app/controllers/LanguageController.php'
];

// Handle public website routes
if ($is_public_route) {
    require_once $base_path . '/app/controllers/PublicWebsiteController.php';
    exit;
}

// Handle admin routes
if (isset($admin_routes[$main_path])) {
    $controller_file = $admin_routes[$main_path];
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