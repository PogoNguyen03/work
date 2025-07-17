-- Tạo bảng website_content để lưu trữ nội dung website
CREATE TABLE IF NOT EXISTS `website_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(50) NOT NULL COMMENT 'Tên trang (homepage, about, contact)',
  `content` longtext NOT NULL COMMENT 'Nội dung JSON',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu
INSERT INTO `website_content` (`page`, `content`) VALUES
('homepage', '{"title":"Thiên Cơ Trí Liên SEO","description":"Công ty SEO hàng đầu Việt Nam - Tối ưu hóa website, tăng thứ hạng Google","content":"<h2>Chào mừng đến với Thiên Cơ Trí Liên SEO</h2><p>Chúng tôi là đối tác tin cậy trong hành trình tối ưu hóa website và tăng thứ hạng Google của bạn.</p><div class=\"row mt-4\"><div class=\"col-md-4\"><div class=\"text-center\"><i class=\"fas fa-chart-line fa-3x text-primary mb-3\"></i><h4>Tối ưu SEO</h4><p>Chiến lược SEO toàn diện giúp website của bạn lên top Google</p></div></div><div class=\"col-md-4\"><div class=\"text-center\"><i class=\"fas fa-mobile-alt fa-3x text-success mb-3\"></i><h4>Responsive Design</h4><p>Thiết kế website tương thích mọi thiết bị</p></div></div><div class=\"col-md-4\"><div class=\"text-center\"><i class=\"fas fa-rocket fa-3x text-warning mb-3\"></i><h4>Tốc độ nhanh</h4><p>Tối ưu hóa tốc độ tải trang</p></div></div></div>"}'),
('about', '{"title":"Về chúng tôi","content":"<h2>Thiên Cơ Trí Liên SEO</h2><p>Được thành lập với sứ mệnh mang lại giải pháp SEO hiệu quả cho các doanh nghiệp Việt Nam, chúng tôi tự hào là đối tác tin cậy trong lĩnh vực tối ưu hóa công cụ tìm kiếm.</p><h3>Dịch vụ của chúng tôi</h3><ul><li><strong>SEO On-page:</strong> Tối ưu hóa nội dung, meta tags, cấu trúc website</li><li><strong>SEO Off-page:</strong> Xây dựng backlinks chất lượng</li><li><strong>Technical SEO:</strong> Tối ưu hóa kỹ thuật website</li><li><strong>Content Marketing:</strong> Tạo nội dung chất lượng</li><li><strong>Local SEO:</strong> Tối ưu hóa cho tìm kiếm địa phương</li></ul><h3>Cam kết của chúng tôi</h3><p>Chúng tôi cam kết mang lại kết quả thực tế và bền vững cho website của bạn, giúp doanh nghiệp tăng doanh số và phát triển bền vững.</p>"}'),
('contact', '{"email":"info@thiencotrilien.com","phone":"0909.123.456","address":"Tầng 5, Tòa nhà ABC, 123 Đường XYZ, Quận 1, TP.HCM"}'); 