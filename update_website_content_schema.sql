-- Cập nhật bảng website_content để hỗ trợ đa ngôn ngữ
ALTER TABLE website_content ADD COLUMN lang VARCHAR(10) DEFAULT 'vi' AFTER page;

-- Tạo index cho lang để tối ưu hiệu suất
CREATE INDEX idx_website_content_lang ON website_content(lang);

-- Cập nhật comment cho bảng
ALTER TABLE website_content COMMENT = 'Bảng lưu trữ nội dung website đa ngôn ngữ';

-- Thêm unique constraint để tránh trùng lặp
ALTER TABLE website_content ADD UNIQUE KEY unique_page_lang (page, lang); 