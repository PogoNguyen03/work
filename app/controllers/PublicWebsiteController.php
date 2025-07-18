<?php
// Sử dụng đường dẫn tuyệt đối để tránh lỗi open_basedir
$base_path = dirname(dirname(__DIR__));
require_once $base_path . '/app/helpers/db.php';
require_once $base_path . '/app/helpers/i18n.php';

// Get current language from session or default to Vietnamese
$current_lang = $_SESSION['lang'] ?? 'vi';

// Get current page
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Remove 'public' from path if present
$path = str_replace('public', '', $path);
$path = trim($path, '/');

// Determine which page to show based on exact path match
$page = 'homepage'; // default
if ($path === 'about') {
    $page = 'about';
} elseif ($path === 'contact') {
    $page = 'contact';
}

// Get website content based on current language
$homepage_content = getWebsiteContentByLanguage('homepage', $current_lang);
$about_content = getWebsiteContentByLanguage('about', $current_lang);
$contact_content = getWebsiteContentByLanguage('contact', $current_lang);

// Get website settings
$website_settings = getWebsiteSettings();

// Set page title and meta
switch ($page) {
    case 'about':
        $pageTitle = $about_content['title'] ?? __('about_us');
        $pageDescription = __('about_us_description');
        break;
    case 'contact':
        $pageTitle = __('contact');
        $pageDescription = __('contact_description');
        break;
    default:
        $pageTitle = $homepage_content['title'] ?? __('company_name');
        $pageDescription = $homepage_content['description'] ?? __('company_description');
        break;
}

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $page === 'contact') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name && $email && $message) {
        saveContactMessage($name, $email, $phone, $company, $subject, $message);
        $success_message = __('contact_success_message');
    } else {
        $error_message = __('contact_error_message');
    }
}

// Include the appropriate view
include $base_path . "/app/views/public/{$page}.php";

// Helper function to get content by language
function getWebsiteContentByLanguage($page, $lang = 'vi') {
    global $conn;
    
    // Nếu là tiếng Trung, tìm trong bảng với page_zh
    if ($lang === 'zh') {
        $page = $page . '_zh';
    }
    
    $stmt = $conn->prepare("SELECT content FROM website_content WHERE page = ? AND lang = ?");
    $stmt->bind_param('ss', $page, $lang);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return json_decode($row['content'], true);
    }
    
    // Nếu không tìm thấy bản dịch, trả về nội dung tiếng Việt
    if ($lang === 'zh') {
        return getWebsiteContentByLanguage(str_replace('_zh', '', $page), 'vi');
    }
    
    // Return default content for Vietnamese
    switch ($page) {
        case 'homepage':
            return [
                'title' => __('company_name'),
                'description' => __('company_description'),
                'content' => '<p>' . __('homepage_content') . '</p>'
            ];
        case 'about':
            return [
                'title' => __('about_us'),
                'content' => '<p>' . __('about_content') . '</p>'
            ];
        case 'contact':
            return [
                'email' => 'info@thiencotrilien.com',
                'phone' => '0909.123.456',
                'address' => __('company_address')
            ];
        default:
            return [];
    }
}

// Legacy function for backward compatibility
function getWebsiteContent($page) {
    return getWebsiteContentByLanguage($page, 'vi');
}

function getWebsiteSettings() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM website_settings WHERE id = 1");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function saveContactMessage($name, $email, $phone, $company, $subject, $message) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, company, subject, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $name, $email, $phone, $company, $subject, $message);
    $stmt->execute();
    $stmt->close();
}
?> 