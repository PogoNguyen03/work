<?php
// Worker xử lý queue dịch tự động
$base_path = __DIR__;
require_once $base_path . '/app/helpers/db.php';
require_once $base_path . '/app/helpers/translate.php';

// Lấy tối đa 5 request pending
$sql = "SELECT id, source_text, source_lang, target_lang FROM translation_queue WHERE status = 'pending' ORDER BY created_at ASC LIMIT 5";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $text = $row['source_text'];
    $sourceLang = $row['source_lang'];
    $targetLang = $row['target_lang'];
    // Đánh dấu đang xử lý
    $conn->query("UPDATE translation_queue SET status = 'processing', updated_at = NOW() WHERE id = $id");
    // Dịch
    $translation = translateText($text, $targetLang);
    if (!empty($translation)) {
        // Lưu vào cache và queue
        $stmt = $conn->prepare("UPDATE translation_queue SET translated_text = ?, status = 'done', updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('si', $translation, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Đánh dấu lỗi nếu dịch thất bại
        $conn->query("UPDATE translation_queue SET status = 'error', updated_at = NOW() WHERE id = $id");
    }
    // Nghỉ 0.5s để tránh spam API
    usleep(500000);
} 