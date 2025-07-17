<?php
// File cài đặt bảng cache dịch
require_once 'app/helpers/db.php';

echo "Đang tạo bảng translation_cache...\n";

$sql = "CREATE TABLE IF NOT EXISTS translation_cache (
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
    $conn->query($sql);
    echo "✓ Bảng translation_cache đã được tạo thành công!\n";
    
    // Tạo index
    $indexSql = "CREATE INDEX idx_translation_lookup ON translation_cache(source_lang, target_lang)";
    $conn->query($indexSql);
    echo "✓ Index đã được tạo thành công!\n";
    
    $indexSql2 = "CREATE INDEX idx_translation_updated ON translation_cache(updated_at)";
    $conn->query($indexSql2);
    echo "✓ Index updated_at đã được tạo thành công!\n";
    
    echo "\nTính năng dịch tự động đã sẵn sàng sử dụng!\n";
    echo "Bạn có thể:\n";
    echo "1. Cập nhật nội dung website để dịch tự động\n";
    echo "2. Sử dụng nút 'Dịch sang tiếng Trung' để dịch thủ công\n";
    echo "3. Xem file TRANSLATION_README.md để biết thêm chi tiết\n";
    
} catch (Exception $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
}
?> 