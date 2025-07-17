-- Tạo bảng cache dịch
CREATE TABLE IF NOT EXISTS translation_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source_text TEXT NOT NULL,
    source_lang VARCHAR(10) NOT NULL,
    target_lang VARCHAR(10) NOT NULL,
    translated_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_translation (source_text(255), source_lang, target_lang)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm index để tối ưu hiệu suất tìm kiếm
CREATE INDEX idx_translation_lookup ON translation_cache(source_lang, target_lang);
CREATE INDEX idx_translation_updated ON translation_cache(updated_at);

-- Thêm comment cho bảng
ALTER TABLE translation_cache COMMENT = 'Bảng cache lưu trữ các bản dịch để tránh gọi API lặp lại'; 