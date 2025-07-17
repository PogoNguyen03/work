<?php

class TranslationService {
    private $db;
    private $apiKey;
    private $cacheTable = 'translation_cache';
    
    public function __construct($db) {
        $this->db = $db;
        $this->apiKey = 'your_google_translate_api_key'; // Cần thay bằng API key thực
        $this->initCacheTable();
    }
    
    /**
     * Khởi tạo bảng cache nếu chưa tồn tại
     */
    private function initCacheTable() {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->cacheTable} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            source_text TEXT NOT NULL,
            source_lang VARCHAR(10) NOT NULL,
            target_lang VARCHAR(10) NOT NULL,
            translated_text TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_translation (source_text(255), source_lang, target_lang)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        try {
            $this->db->query($sql);
        } catch (Exception $e) {
            error_log("Error creating translation cache table: " . $e->getMessage());
        }
    }
    
    /**
     * Dịch text từ ngôn ngữ nguồn sang ngôn ngữ đích
     */
    public function translate($text, $sourceLang = 'vi', $targetLang = 'zh') {
        if (empty($text)) {
            return '';
        }
        
        // Kiểm tra cache trước
        $cached = $this->getCachedTranslation($text, $sourceLang, $targetLang);
        if ($cached !== false) {
            return $cached;
        }
        
        // Dịch bằng API (nếu có key) hoặc fallback
        $translated = false;
        
        if ($this->apiKey !== 'your_google_translate_api_key') {
            $translated = $this->translateWithAPI($text, $sourceLang, $targetLang);
        }
        
        // Nếu API thất bại hoặc không có key, sử dụng fallback
        if ($translated === false) {
            $translated = $this->translateWithCurl($text, $sourceLang, $targetLang);
        }
        
        if ($translated !== false) {
            // Lưu vào cache
            $this->cacheTranslation($text, $sourceLang, $targetLang, $translated);
            return $translated;
        }
        
        return $text; // Trả về text gốc nếu dịch thất bại
    }
    
    /**
     * Lấy bản dịch từ cache
     */
    private function getCachedTranslation($text, $sourceLang, $targetLang) {
        $sql = "SELECT translated_text FROM {$this->cacheTable} 
                WHERE source_text = ? AND source_lang = ? AND target_lang = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sss', $text, $sourceLang, $targetLang);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            return $row ? $row['translated_text'] : false;
        } catch (Exception $e) {
            error_log("Error getting cached translation: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lưu bản dịch vào cache
     */
    private function cacheTranslation($sourceText, $sourceLang, $targetLang, $translatedText) {
        $sql = "INSERT INTO {$this->cacheTable} (source_text, source_lang, target_lang, translated_text) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                translated_text = VALUES(translated_text), 
                updated_at = CURRENT_TIMESTAMP";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssss', $sourceText, $sourceLang, $targetLang, $translatedText);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Error caching translation: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Dịch bằng Google Translate API
     */
    private function translateWithAPI($text, $sourceLang, $targetLang) {
        // Sử dụng Google Translate API (cần API key)
        $url = "https://translation.googleapis.com/language/translate/v2?key=" . $this->apiKey;
        
        $data = [
            'q' => $text,
            'source' => $sourceLang,
            'target' => $targetLang,
            'format' => 'html'
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === false) {
            error_log("Translation API request failed");
            return false;
        }
        
        $response = json_decode($result, true);
        
        if (isset($response['data']['translations'][0]['translatedText'])) {
            return $response['data']['translations'][0]['translatedText'];
        }
        
        error_log("Translation API response error: " . json_encode($response));
        return false;
    }
    
    /**
     * Dịch fallback sử dụng cURL (không cần API key)
     */
    private function translateWithCurl($text, $sourceLang, $targetLang) {
        $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl={$sourceLang}&tl={$targetLang}&dt=t&q=" . urlencode($text);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false || $httpCode !== 200) {
            error_log("Translation cURL request failed: HTTP {$httpCode}");
            return false;
        }
        
        $data = json_decode($response, true);
        
        if (isset($data[0][0][0])) {
            return $data[0][0][0];
        }
        
        error_log("Translation cURL response error: " . json_encode($data));
        return false;
    }
    
    /**
     * Dịch nội dung website và cập nhật database
     */
    public function translateWebsiteContent($content, $contentType, $recordId) {
        // Dịch các trường text
        $translatedContent = [];
        
        foreach ($content as $key => $value) {
            if (is_string($value) && !empty($value)) {
                // Bỏ qua các trường không cần dịch
                if (in_array($key, ['email', 'phone', 'facebook', 'twitter', 'linkedin', 'instagram', 'map'])) {
                    $translatedContent[$key] = $value;
                } else {
                    $translatedContent[$key] = $this->translate($value, 'vi', 'zh');
                }
            } else {
                $translatedContent[$key] = $value;
            }
        }
        
        // Lưu bản dịch vào database (không ghi đè nội dung gốc)
        $this->saveTranslatedContent($contentType, $recordId, $translatedContent);
        
        return $translatedContent;
    }
    
    /**
     * Lưu bản dịch vào database (tạo record riêng cho tiếng Trung)
     */
    private function saveTranslatedContent($contentType, $recordId, $translatedContent) {
        $table = 'website_content';
        
        // Kiểm tra xem đã có bản dịch tiếng Trung chưa
        $sql = "SELECT id FROM {$table} WHERE page = ? AND lang = 'zh'";
        
        try {
            $stmt = $this->db->prepare($sql);
            $pageZh = $contentType . '_zh';
            $stmt->bind_param('s', $pageZh);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Cập nhật bản dịch hiện có
                $sql = "UPDATE {$table} SET content = ?, updated_at = NOW() WHERE page = ? AND lang = 'zh'";
                $stmt = $this->db->prepare($sql);
                $contentJson = json_encode($translatedContent);
                $stmt->bind_param('ss', $contentJson, $pageZh);
            } else {
                // Tạo bản dịch mới
                $sql = "INSERT INTO {$table} (page, lang, content, created_at, updated_at) VALUES (?, 'zh', ?, NOW(), NOW())";
                $stmt = $this->db->prepare($sql);
                $contentJson = json_encode($translatedContent);
                $stmt->bind_param('ss', $pageZh, $contentJson);
            }
            
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            error_log("Error saving translated content: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy nội dung theo ngôn ngữ
     */
    public function getContentByLanguage($page, $lang = 'vi') {
        $table = 'website_content';
        
        if ($lang === 'zh') {
            $page = $page . '_zh';
        }
        
        $sql = "SELECT content FROM {$table} WHERE page = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $page);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $stmt->close();
                return json_decode($row['content'], true);
            }
            
            $stmt->close();
            return [];
        } catch (Exception $e) {
            error_log("Error getting content by language: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Kiểm tra xem nội dung có thay đổi không
     */
    public function hasContentChanged($content, $contentType, $recordId) {
        $table = 'website_content';
        
        // Lấy nội dung tiếng Việt hiện tại
        $sql = "SELECT content FROM {$table} WHERE id = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $recordId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            if (!$row) {
                return true; // Nếu chưa có record thì coi như có thay đổi
            }
            
            $oldContent = json_decode($row['content'], true);
            $newContent = $content;
            
            // So sánh nội dung
            return $this->compareContent($oldContent, $newContent);
            
        } catch (Exception $e) {
            error_log("Error checking content changes: " . $e->getMessage());
            return true; // Coi như có thay đổi nếu có lỗi
        }
    }
    
    /**
     * Kiểm tra xem đã có bản dịch tiếng Trung chưa
     */
    public function hasChineseTranslation($contentType) {
        $table = 'website_content';
        $pageZh = $contentType . '_zh';
        
        $sql = "SELECT id FROM {$table} WHERE page = ?";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('s', $pageZh);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result->num_rows > 0;
        } catch (Exception $e) {
            error_log("Error checking Chinese translation: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * So sánh nội dung cũ và mới
     */
    private function compareContent($oldContent, $newContent) {
        if (!is_array($oldContent) || !is_array($newContent)) {
            return $oldContent !== $newContent;
        }
        
        // So sánh từng trường
        foreach ($newContent as $key => $value) {
            if (!isset($oldContent[$key]) || $oldContent[$key] !== $value) {
                // Chỉ dịch nếu trường này cần dịch và có thay đổi
                if (!in_array($key, ['email', 'phone', 'facebook', 'twitter', 'linkedin', 'instagram', 'map'])) {
                    return true; // Có thay đổi ở trường cần dịch
                }
            }
        }
        
        // Kiểm tra xem có trường nào bị xóa không
        foreach ($oldContent as $key => $value) {
            if (!isset($newContent[$key])) {
                if (!in_array($key, ['email', 'phone', 'facebook', 'twitter', 'linkedin', 'instagram', 'map'])) {
                    return true; // Có thay đổi ở trường cần dịch
                }
            }
        }
        
        return false; // Không có thay đổi ở trường cần dịch
    }
    
    /**
     * Lấy thống kê cache
     */
    public function getCacheStats() {
        $sql = "SELECT 
                    COUNT(*) as total_translations,
                    COUNT(DISTINCT source_lang) as source_languages,
                    COUNT(DISTINCT target_lang) as target_languages,
                    MIN(created_at) as oldest_cache,
                    MAX(updated_at) as latest_update
                FROM {$this->cacheTable}";
        
        try {
            $result = $this->db->query($sql);
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error getting cache stats: " . $e->getMessage());
            return false;
        }
    }
} 