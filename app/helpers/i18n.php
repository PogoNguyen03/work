<?php
/**
 * Internationalization helper function
 * Usage: __('key_name')
 */

function __($key) {
    static $lang = [];

    if (empty($lang)) {
        $code = $_SESSION['lang'] ?? 'vi';
        $file = __DIR__ . "/../../lang/{$code}.php";
        if (file_exists($file)) {
            $lang = include $file;
        } else {
            // Fallback to Vietnamese if language file doesn't exist
            $file = __DIR__ . "/../../lang/vi.php";
            if (file_exists($file)) {
                $lang = include $file;
            }
        }
    }

    return $lang[$key] ?? $key;
}

/**
 * Get current language code
 */
function getCurrentLang() {
    return $_SESSION['lang'] ?? 'vi';
}

/**
 * Set language
 */
function setLanguage($lang) {
    $_SESSION['lang'] = $lang;
}

/**
 * Get available languages
 */
function getAvailableLanguages() {
    return [
        'vi' => '🇻🇳 Tiếng Việt',
        'zh' => '🇨🇳 中文'
    ];
}

/**
 * Get content in current language
 * If current language is Chinese and Chinese content exists, return Chinese content
 * Otherwise return Vietnamese content
 */
function getContentInCurrentLang($viText, $zhText = null) {
    $currentLang = getCurrentLang();
    
    if ($currentLang === 'zh' && !empty($zhText)) {
        return $zhText;
    }
    
    return $viText;
} 