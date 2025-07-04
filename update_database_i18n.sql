-- Cập nhật database để hỗ trợ đa ngôn ngữ
-- Chạy từng lệnh một để đảm bảo an toàn

-- 1. Thêm cột content_zh vào bảng reports
ALTER TABLE `reports` 
ADD COLUMN `content_zh` TEXT DEFAULT NULL AFTER `content`;

-- 2. Thêm cột title_zh vào bảng reports (nếu muốn dịch cả tiêu đề)
ALTER TABLE `reports` 
ADD COLUMN `title_zh` VARCHAR(255) DEFAULT NULL AFTER `title`;

-- 3. Cập nhật dữ liệu hiện tại (copy content sang content_zh tạm thời)
UPDATE `reports` SET `content_zh` = `content` WHERE `content_zh` IS NULL;
UPDATE `reports` SET `title_zh` = `title` WHERE `title_zh` IS NULL;

-- 4. Kiểm tra kết quả
SELECT 'Cập nhật database đa ngôn ngữ hoàn tất!' as status;
SELECT COUNT(*) as total_reports FROM reports;
SELECT COUNT(*) as reports_with_chinese_content FROM reports WHERE content_zh IS NOT NULL; 