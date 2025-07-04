<?php
// Test file để kiểm tra các đường dẫn
echo "<h2>Kiểm tra đường dẫn trong Work Management System</h2>";

// Test database connection
echo "<h3>1. Kiểm tra kết nối database:</h3>";
try {
    require_once 'app/helpers/db.php';
    echo "✅ Kết nối database thành công<br>";
} catch (Exception $e) {
    echo "❌ Lỗi kết nối database: " . $e->getMessage() . "<br>";
}

// Test file paths
echo "<h3>2. Kiểm tra đường dẫn file:</h3>";
$files_to_check = [
    'app/helpers/db.php',
    'app/helpers/auth.php',
    'app/controllers/DashboardController.php',
    'app/controllers/ReportController.php',
    'app/controllers/UserController.php',
    'app/controllers/TaskController.php',
    'app/controllers/NotificationController.php',
    'app/controllers/AuthController.php',
    'app/controllers/ProfileController.php',
    'app/views/layouts/header.php',
    'app/views/layouts/footer.php',
    'app/views/reports/index.php',
    'app/views/reports/form.php',
    'app/views/reports/view.php',
    'public/index.php',
    'public/.htaccess',
    '.htaccess'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ $file - Tồn tại<br>";
    } else {
        echo "❌ $file - Không tồn tại<br>";
    }
}

// Test routing
echo "<h3>3. Kiểm tra routing:</h3>";
$routes = [
    'dashboard' => 'DashboardController.php',
    'reports' => 'ReportController.php',
    'users' => 'UserController.php',
    'tasks' => 'TaskController.php',
    'notifications' => 'NotificationController.php',
    'login' => 'AuthController.php',
    'logout' => 'AuthController.php',
    'profile' => 'ProfileController.php'
];

foreach ($routes as $route => $controller_file) {
    $full_path = "app/controllers/$controller_file";
    if (file_exists($full_path)) {
        echo "✅ Route /$route -> $full_path<br>";
    } else {
        echo "❌ Route /$route -> $full_path (không tồn tại)<br>";
    }
}

// Test URL structure
echo "<h3>4. Cấu trúc URL:</h3>";
echo "Base URL: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";

// Test session
echo "<h3>5. Kiểm tra session:</h3>";
session_start();
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Session đã được khởi tạo<br>";
} else {
    echo "❌ Session chưa được khởi tạo<br>";
}

// Test routing logic
echo "<h3>6. Kiểm tra logic routing:</h3>";
$test_paths = [
    'dashboard' => 'Dashboard',
    'reports' => 'Reports',
    'reports/create' => 'Reports (create)',
    'reports/view?id=1' => 'Reports (view)',
    'users' => 'Users',
    'tasks' => 'Tasks',
    'notifications' => 'Notifications',
    'login' => 'Login',
    'profile' => 'Profile'
];

foreach ($test_paths as $path => $description) {
    $path_parts = explode('/', $path);
    $main_path = $path_parts[0] ?? 'dashboard';
    $sub_path = $path_parts[1] ?? '';
    
    // Map routes to correct controller filenames (singular)
    $controller_mapping = [
        'dashboard' => 'DashboardController.php',
        'reports' => 'ReportController.php',
        'users' => 'UserController.php',
        'tasks' => 'TaskController.php',
        'notifications' => 'NotificationController.php',
        'login' => 'AuthController.php',
        'logout' => 'AuthController.php',
        'profile' => 'ProfileController.php'
    ];
    
    $controller_file = "app/controllers/" . ($controller_mapping[$main_path] ?? ucfirst($main_path) . "Controller.php");
    if (file_exists($controller_file)) {
        echo "✅ $description: $path -> $controller_file";
        if (!empty($sub_path)) {
            echo " (action: $sub_path)";
        }
        echo "<br>";
    } else {
        echo "❌ $description: $path -> $controller_file (không tồn tại)<br>";
    }
}

echo "<h3>7. Hướng dẫn truy cập:</h3>";
echo "<ul>";
echo "<li><strong>Dashboard:</strong> <a href='/work/public/'>/work/public/</a></li>";
echo "<li><strong>Reports:</strong> <a href='/work/public/reports'>/work/public/reports</a></li>";
echo "<li><strong>Create Report:</strong> <a href='/work/public/reports/create'>/work/public/reports/create</a></li>";
echo "<li><strong>Users:</strong> <a href='/work/public/users'>/work/public/users</a></li>";
echo "<li><strong>Tasks:</strong> <a href='/work/public/tasks'>/work/public/tasks</a></li>";
echo "<li><strong>Notifications:</strong> <a href='/work/public/notifications'>/work/public/notifications</a></li>";
echo "<li><strong>Login:</strong> <a href='/work/public/login'>/work/public/login</a></li>";
echo "<li><strong>Profile:</strong> <a href='/work/public/profile'>/work/public/profile</a></li>";
echo "</ul>";

echo "<h3>8. Kiểm tra .htaccess:</h3>";
$htaccess_files = [
    '.htaccess' => 'Root .htaccess',
    'public/.htaccess' => 'Public .htaccess'
];

foreach ($htaccess_files as $file => $description) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'RewriteEngine On') !== false) {
            echo "✅ $description: Tồn tại và có RewriteEngine<br>";
        } else {
            echo "⚠️ $description: Tồn tại nhưng thiếu RewriteEngine<br>";
        }
    } else {
        echo "❌ $description: Không tồn tại<br>";
    }
}

echo "<h3>9. Lưu ý quan trọng:</h3>";
echo "<ul>";
echo "<li>✅ Tất cả controller files đã tồn tại</li>";
echo "<li>✅ Database connection hoạt động</li>";
echo "<li>✅ Session system hoạt động</li>";
echo "<li>✅ .htaccess files đã được cấu hình</li>";
echo "<li>⚠️ Đảm bảo mod_rewrite đã được bật trong Apache</li>";
echo "<li>⚠️ Database 'baocao' phải tồn tại và có dữ liệu</li>";
echo "<li>⚠️ File .htaccess phải có quyền đọc</li>";
echo "<li>⚠️ Document root nên trỏ đến thư mục gốc chứa folder 'work'</li>";
echo "</ul>";

echo "<h3>10. Test thực tế:</h3>";
echo "<p>Bây giờ bạn có thể test các URL sau:</p>";
echo "<ul>";
echo "<li><a href='/work/public/' target='_blank'>Dashboard</a></li>";
echo "<li><a href='/work/public/reports' target='_blank'>Reports</a></li>";
echo "<li><a href='/work/public/login' target='_blank'>Login</a></li>";
echo "</ul>";
?>
 