<?php
require_once '../app/helpers/db.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/translate.php';

// Require login
requireLogin();

// Handle AJAX translation requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['text']) && isset($input['target_lang'])) {
        $text = trim($input['text']);
        $targetLang = $input['target_lang'];
        
        if (!empty($text)) {
            $translation = translateText($text, $targetLang);
            
            header('Content-Type: application/json');
            if (!empty($translation)) {
                echo json_encode([
                    'success' => true,
                    'translation' => $translation
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Translation failed'
                ]);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Empty text'
            ]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Invalid parameters'
        ]);
    }
    exit;
}

// If accessed directly, redirect to dashboard
header('Location: /work/public/dashboard');
exit;
?> 