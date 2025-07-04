<?php
require_once '../app/helpers/i18n.php';
require_once '../app/helpers/db.php';
require_once '../app/helpers/translate.php';

// Handle language switching
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    
    // Validate language code
    $availableLanguages = getAvailableLanguages();
    if (array_key_exists($lang, $availableLanguages)) {
        setLanguage($lang);
        
        // Nếu chuyển sang tiếng Trung, tự động dịch các báo cáo chưa có bản dịch
        if ($lang === 'zh') {
            $translatedCount = autoTranslateUndoneReports();
            if ($translatedCount > 0) {
                // Có thể thêm thông báo tạm thời nếu muốn
                $_SESSION['info'] = "Đã tự động dịch $translatedCount báo cáo sang tiếng Trung";
            }
        }
    }
    
    // Redirect back to the previous page
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/work/public/dashboard';
    header("Location: $redirectUrl");
    exit;
}

// If accessed directly, redirect to dashboard
header('Location: /work/public/dashboard');
exit;
?> 