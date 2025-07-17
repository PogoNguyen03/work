<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/i18n.php';
require_once '../app/services/TranslationService.php';

// Require login
requireLogin();

// Check if user has permission (admin or HR)
$role = getUserRole();
if ($role !== 'admin' && $role !== 'quanly') {
    header('Location: /work/public/dashboard');
    exit();
}

$pageTitle = __('website_management');
$currentPage = 'website';

// Initialize translation service
$translationService = new TranslationService($conn);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_homepage':
            // Update homepage content
            $title = clean_input($_POST['title']);
            $description = clean_input($_POST['description']);
            $content = $_POST['content'];
            
            $homepageData = [
                'title' => $title,
                'description' => $description,
                'content' => $content
            ];
            
            // Update in database first to ensure record exists
            updateWebsiteContent('homepage', $homepageData);
            
            // Check if content has changed and translate
            $recordId = getWebsiteContentId('homepage');
            if ($recordId && $translationService->hasContentChanged($homepageData, 'homepage', $recordId)) {
                // Auto-translate to Chinese
                $translatedData = $translationService->translateWebsiteContent($homepageData, 'homepage', $recordId);
                $_SESSION['translation_message'] = 'Nội dung đã được dịch tự động sang tiếng Trung';
            }
            
            $_SESSION['success_message'] = __('homepage_updated_successfully');
            break;
            
        case 'update_about':
            // Update about page content
            $title = clean_input($_POST['title']);
            $content = $_POST['content'];
            
            $aboutData = [
                'title' => $title,
                'content' => $content
            ];
            
            // Update in database first to ensure record exists
            updateWebsiteContent('about', $aboutData);
            
            // Check if content has changed and translate
            $recordId = getWebsiteContentId('about');
            if ($recordId && $translationService->hasContentChanged($aboutData, 'about', $recordId)) {
                // Auto-translate to Chinese
                $translatedData = $translationService->translateWebsiteContent($aboutData, 'about', $recordId);
                $_SESSION['translation_message'] = 'Nội dung đã được dịch tự động sang tiếng Trung';
            }
            
            $_SESSION['success_message'] = __('about_page_updated_successfully');
            break;
            
        case 'update_contact':
            // Update contact information
            $email = clean_input($_POST['email']);
            $phone = clean_input($_POST['phone']);
            $address = clean_input($_POST['address']);
            $hours = clean_input($_POST['hours'] ?? '');
            $facebook = clean_input($_POST['facebook'] ?? '');
            $twitter = clean_input($_POST['twitter'] ?? '');
            $linkedin = clean_input($_POST['linkedin'] ?? '');
            $instagram = clean_input($_POST['instagram'] ?? '');
            $map = $_POST['map'] ?? '';
            
            $contactData = [
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'hours' => $hours,
                'facebook' => $facebook,
                'twitter' => $twitter,
                'linkedin' => $linkedin,
                'instagram' => $instagram,
                'map' => $map
            ];
            
            // Update in database first to ensure record exists
            updateWebsiteContent('contact', $contactData);
            
            // Check if content has changed and translate
            $recordId = getWebsiteContentId('contact');
            if ($recordId && $translationService->hasContentChanged($contactData, 'contact', $recordId)) {
                // Auto-translate to Chinese
                $translatedData = $translationService->translateWebsiteContent($contactData, 'contact', $recordId);
                $_SESSION['translation_message'] = 'Thông tin liên hệ đã được dịch tự động sang tiếng Trung';
            }
            
            $_SESSION['success_message'] = __('contact_info_updated_successfully');
            break;

        case 'update_settings':
            // Update website settings
            $primary_color = clean_input($_POST['primary_color']);
            $font_family = clean_input($_POST['font_family']);
            $layout = clean_input($_POST['layout']);
            $site_name = clean_input($_POST['site_name'] ?? '');
            // Xử lý upload logo/banner nếu có
            $logo = $_FILES['logo']['name'] ?? '';
            $banner = $_FILES['banner']['name'] ?? '';
            $logo_path = '';
            $banner_path = '';
            if ($logo && $_FILES['logo']['tmp_name']) {
                $logo_path = 'uploads/' . basename($logo);
                move_uploaded_file($_FILES['logo']['tmp_name'], '../public/' . $logo_path);
            }
            if ($banner && $_FILES['banner']['tmp_name']) {
                $banner_path = 'uploads/' . basename($banner);
                move_uploaded_file($_FILES['banner']['tmp_name'], '../public/' . $banner_path);
            }
            updateWebsiteSettings([
                'primary_color' => $primary_color,
                'font_family' => $font_family,
                'layout' => $layout,
                'logo' => $logo_path,
                'banner' => $banner_path,
                'site_name' => $site_name
            ]);
            $_SESSION['success_message'] = __('settings_updated_successfully');
            break;
        case 'add_feedback':
            $name = clean_input($_POST['name']);
            $position = clean_input($_POST['position']);
            $content = clean_input($_POST['content']);
            $avatar = $_FILES['avatar']['name'] ?? '';
            $avatar_path = '';
            if ($avatar && $_FILES['avatar']['tmp_name']) {
                $avatar_path = 'uploads/' . basename($avatar);
                move_uploaded_file($_FILES['avatar']['tmp_name'], '../public/' . $avatar_path);
            }
            addWebsiteFeedback($name, $position, $content, $avatar_path);
            $_SESSION['success_message'] = 'Thêm feedback thành công!';
            break;
        case 'delete_feedback':
            $id = intval($_POST['id']);
            deleteWebsiteFeedback($id);
            $_SESSION['success_message'] = 'Xóa feedback thành công!';
            break;
        case 'delete_contact_email':
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success_message'] = 'Đã xóa email liên hệ!';
            break;
        case 'mark_read_contact_email':
            $id = intval($_POST['id']);
            $stmt = $conn->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success_message'] = 'Đã đánh dấu đã đọc!';
            break;
        case 'translate_content':
            // Manual translation trigger
            $page = clean_input($_POST['page']);
            $recordId = getWebsiteContentId($page);
            $content = getWebsiteContent($page);
            
            if ($content) {
                $translatedData = $translationService->translateWebsiteContent($content, $page, $recordId);
                $_SESSION['success_message'] = 'Đã dịch nội dung sang tiếng Trung thành công!';
            } else {
                $_SESSION['error_message'] = 'Không tìm thấy nội dung để dịch!';
            }
            break;
    }
    
    $redirect_tab = $_POST['active_tab'] ?? '';
    $redirect_url = '/work/public/website' . ($redirect_tab ? ('#' . $redirect_tab) : '');
    header('Location: ' . $redirect_url);
    exit();
}

// Get current website content
$homepage_content = getWebsiteContent('homepage');
$about_content = getWebsiteContent('about');
$contact_content = getWebsiteContent('contact');

// Get website settings
$website_settings = getWebsiteSettings();
$feedback_list = getWebsiteFeedbackList();

// Kiểm tra xem đã có bản dịch tiếng Trung chưa
$has_chinese_homepage = $translationService->hasChineseTranslation('homepage');
$has_chinese_about = $translationService->hasChineseTranslation('about');
$has_chinese_contact = $translationService->hasChineseTranslation('contact');

// Lấy danh sách email liên hệ
$email_list = [];
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $email_list[] = $row;
}
// Lấy chi tiết nếu có id
$email_detail = null;
if (isset($_GET['email_id'])) {
    $id = intval($_GET['email_id']);
    $stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $email_detail = $res->fetch_assoc();
    $stmt->close();
}

// Include view
include '../app/views/website/index.php';

// Helper functions
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getWebsiteContentId($page) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id FROM website_content WHERE page = ? AND lang = 'vi'");
    $stmt->bind_param('s', $page);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    
    return null;
}

function updateWebsiteContent($page, $data) {
    global $conn;
    
    // Check if content exists
    $stmt = $conn->prepare("SELECT id FROM website_content WHERE page = ? AND lang = 'vi'");
    $stmt->bind_param('s', $page);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing content
        $stmt = $conn->prepare("UPDATE website_content SET content = ?, updated_at = NOW() WHERE page = ? AND lang = 'vi'");
        $content_json = json_encode($data);
        $stmt->bind_param('ss', $content_json, $page);
    } else {
        // Insert new content
        $stmt = $conn->prepare("INSERT INTO website_content (page, lang, content, created_at, updated_at) VALUES (?, 'vi', ?, NOW(), NOW())");
        $content_json = json_encode($data);
        $stmt->bind_param('ss', $page, $content_json);
    }
    
    $stmt->execute();
    $stmt->close();
}

function getWebsiteContent($page) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT content FROM website_content WHERE page = ? AND lang = 'vi'");
    $stmt->bind_param('s', $page);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return json_decode($row['content'], true);
    }
    
    return [];
}

function updateWebsiteSettings($data) {
    global $conn;
    
    // Cập nhật từng trường riêng biệt
    $sql = "UPDATE website_settings SET 
            logo = ?, 
            site_name = ?, 
            primary_color = ?, 
            font_family = ?, 
            layout = ?, 
            banner = ?,
            updated_at = NOW() 
            WHERE id = 1";
    
    $logo = $data['logo'] ?? '';
    $site_name = $data['site_name'] ?? '';
    $primary_color = $data['primary_color'] ?? '#1e40af';
    $font_family = $data['font_family'] ?? 'Inter';
    $layout = $data['layout'] ?? 'A';
    $banner = $data['banner'] ?? '';
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $logo, $site_name, $primary_color, $font_family, $layout, $banner);
    $stmt->execute();
    $stmt->close();
}

function getWebsiteSettings() {
    global $conn;
    
    $settings = [];
    
    // Lấy dữ liệu từ bảng website_settings
    $result = $conn->query("SELECT * FROM website_settings WHERE id = 1");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $settings = [
            'logo' => $row['logo'] ?? '',
            'site_name' => $row['site_name'] ?? '',
            'primary_color' => $row['primary_color'] ?? '#1e40af',
            'font_family' => $row['font_family'] ?? 'Inter',
            'layout' => $row['layout'] ?? 'A',
            'banner' => $row['banner'] ?? ''
        ];
    }
    
    return $settings;
}

function addWebsiteFeedback($name, $position, $content, $avatar) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO website_feedback (name, position, content, avatar, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param('ssss', $name, $position, $content, $avatar);
    $stmt->execute();
    $stmt->close();
}

function deleteWebsiteFeedback($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM website_feedback WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

function getWebsiteFeedbackList() {
    global $conn;
    $feedback = [];
    $result = $conn->query("SELECT * FROM website_feedback ORDER BY created_at DESC");
    while ($row = $result->fetch_assoc()) {
        $feedback[] = $row;
    }
    return $feedback;
}
?> 