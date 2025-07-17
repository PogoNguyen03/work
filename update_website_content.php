<?php
// File cập nhật bảng website_content để hỗ trợ đa ngôn ngữ
require_once 'app/helpers/db.php';

echo "=== Cập nhật bảng website_content để hỗ trợ đa ngôn ngữ ===\n\n";

try {
    // 1. Thêm cột lang
    echo "1. Thêm cột lang...\n";
    $sql = "ALTER TABLE website_content ADD COLUMN lang VARCHAR(10) DEFAULT 'vi' AFTER page";
    $conn->query($sql);
    echo "✓ Cột lang đã được thêm\n";
    
    // 2. Tạo index
    echo "2. Tạo index cho lang...\n";
    $sql = "CREATE INDEX idx_website_content_lang ON website_content(lang)";
    $conn->query($sql);
    echo "✓ Index đã được tạo\n";
    
    // 3. Thêm unique constraint
    echo "3. Thêm unique constraint...\n";
    $sql = "ALTER TABLE website_content ADD UNIQUE KEY unique_page_lang (page, lang)";
    $conn->query($sql);
    echo "✓ Unique constraint đã được thêm\n";
    
    // 4. Cập nhật comment
    echo "4. Cập nhật comment...\n";
    $sql = "ALTER TABLE website_content COMMENT = 'Bảng lưu trữ nội dung website đa ngôn ngữ'";
    $conn->query($sql);
    echo "✓ Comment đã được cập nhật\n";
    
    // 5. Kiểm tra cấu trúc mới
    echo "\n5. Kiểm tra cấu trúc bảng mới:\n";
    $result = $conn->query("DESCRIBE website_content");
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']} | {$row['Null']} | {$row['Key']} | {$row['Default']}\n";
    }
    
    echo "\n=== Cập nhật thành công! ===\n";
    echo "Bảng website_content giờ đã hỗ trợ đa ngôn ngữ.\n";
    echo "Nội dung tiếng Việt sẽ được lưu với lang='vi'\n";
    echo "Nội dung tiếng Trung sẽ được lưu với lang='zh'\n";
    
} catch (Exception $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
    
    // Nếu lỗi unique constraint, có thể do dữ liệu trùng lặp
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        echo "\nCó thể có dữ liệu trùng lặp. Đang xử lý...\n";
        
        // Xóa dữ liệu trùng lặp
        $sql = "DELETE t1 FROM website_content t1 
                INNER JOIN website_content t2 
                WHERE t1.id > t2.id AND t1.page = t2.page";
        $conn->query($sql);
        echo "✓ Đã xóa dữ liệu trùng lặp\n";
        
        // Thử lại unique constraint
        $sql = "ALTER TABLE website_content ADD UNIQUE KEY unique_page_lang (page, lang)";
        $conn->query($sql);
        echo "✓ Unique constraint đã được thêm thành công\n";
    }
}
?> 