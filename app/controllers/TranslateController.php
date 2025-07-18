<?php
$base_path = realpath(__DIR__ . '/../..');
require_once $base_path . '/app/middleware/IpRestrictionMiddleware.php';
IpRestrictionMiddleware::handle();
require_once $base_path . '/app/helpers/db.php';
require_once $base_path . '/app/helpers/auth.php';
require_once $base_path . '/app/helpers/translate.php';

// Require login
requireLogin();

// Handle AJAX translation requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['text']) && isset($input['target_lang'])) {
        $text = trim($input['text']);
        $targetLang = $input['target_lang'];
        $sourceLang = ($targetLang === 'vi') ? 'zh' : 'vi';
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId && !checkTranslationRateLimit($userId, 10, 60)) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Bạn đã vượt quá giới hạn dịch 10 lần/phút. Vui lòng thử lại sau!'
            ]);
            exit;
        }
        
        if (!empty($text)) {
            // Kiểm tra cache trước
            $translation = translateText($text, $targetLang);
            if (!empty($translation)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'translation' => $translation
                ]);
                exit;
            }
            // Nếu chưa có cache, kiểm tra queue
            $queued = getQueuedTranslation($text, $sourceLang, $targetLang);
            if ($queued !== false) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'translation' => $queued
                ]);
                exit;
            }
            // Nếu chưa có, thêm vào queue
            addToTranslationQueue($text, $sourceLang, $targetLang);
            header('Content-Type: application/json');
            echo json_encode([
                    'success' => false,
                'pending' => true,
                'message' => 'Đang xử lý dịch, vui lòng thử lại sau.'
                ]);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Empty text'
            ]);
            exit;
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Invalid parameters'
        ]);
        exit;
    }
}

// If accessed directly, redirect to dashboard
header('Location: /dashboard');
exit;
?> 