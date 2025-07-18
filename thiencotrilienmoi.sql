-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for baocao
CREATE DATABASE IF NOT EXISTS `baocao` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `baocao`;

-- Dumping structure for table baocao.contact_messages
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.contact_messages: ~2 rows (approximately)
DELETE FROM `contact_messages`;
INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `company`, `subject`, `message`, `is_read`, `created_at`) VALUES
	(2, 'Nguyễn văn vui', 'aaaaaaaaaaaaaa@gmail.com', '0773652784', '', 'seo-consultation', 'giúp tôi', 0, '2025-07-05 07:12:53'),
	(3, 'Nguyễn văn vui', 'aaaaaaaaaaaaaa@gmail.com', '0773652784', '', 'seo-consultation', 'giúp tôi', 0, '2025-07-05 07:13:35');

-- Dumping structure for table baocao.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.departments: ~3 rows (approximately)
DELETE FROM `departments`;
INSERT INTO `departments` (`id`, `name`) VALUES
	(1, 'HR'),
	(2, 'SEO'),
	(3, 'IT');

-- Dumping structure for table baocao.reports
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_zh` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `content_zh` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_reports_department` (`department_id`),
  CONSTRAINT `fk_reports_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.reports: ~16 rows (approximately)
DELETE FROM `reports`;
INSERT INTO `reports` (`id`, `user_id`, `title`, `title_zh`, `content`, `content_zh`, `created_at`, `updated_at`, `department_id`) VALUES
	(4, 3, 'Report 28/06/2025', 'Report 28/06/2025', '- Report 28/06/2025\r\n	+ Cập nhật các link quảng cáo\r\n	+ Lấy bài viết đăng lên admin Bangladesh\r\n	+ Kiểm tra tất cả web và sửa một số web, 3 web lỗi\r\n	+ Tìm hiểu craw dữ liệu từ web khác, sau dó tự động thêm vào web 74mao\r\n		Lấy nội dung chi tiết, check links không lấy quảng cáo, bỏ qua links trung gian\r\n		Cần làm thêm: Kiểm tra kĩ hơn để lấy tdk\r\n	+ Sửa lỗi các trang 74mao\r\n		V-host server lỗi không tạo/cập nhật được\r\n		Lỗi hiển thị sau khi DNS qua Clouflare', '2025年6月28日报告\n\n+ 更新广告链接\n+ 将文章发布到孟加拉国管理员\n+ 检查所有网站并修复部分网站，共修复3个网站\n+ 研究从其他网站抓取数据，然后自动添加到74mao网站\n    获取详细信息，检查链接，不采集广告，跳过中间链接\n    待办事项：更仔细地检查以获取TDK\n+ 修复74mao网站的错误\n    虚拟主机服务器错误，无法创建/更新\n    Cloudflare DNS解析后显示错误', '2025-06-28 01:37:00', '2025-06-30 08:37:19', 3),
	(12, 1, '30-6-2025', '30-6-2025', '- Lọc cache dữ liệu trong server 3 và server 4\r\n- Check soulua bangladesh, trung quốc và cloudflare\r\n- Làm web báo cáo :\r\n	+ Làm giao diện người dùng phân quyền admin và user\r\n	+ Có thể báo cáo theo thời gian thực và báo cáo mới nhất sẽ ở trên\r\n	+ Có thể xem xóa sửa cơ bản\r\n	+ Có bộ lọc trong admin và user lọc thời gian báo cáo và admin có phần lọc user', '- 清除服务器3和服务器4的缓存数据\n- 检查soulua孟加拉国、中国和Cloudflare\n- 制作网页报表：\n    + 制作具有管理员和用户权限的界面\n    + 可以进行实时报表，最新的报表显示在最上面\n    + 可以查看、删除、基本编辑\n    + 管理员和用户都有报表时间过滤器，管理员另有用户过滤器', '2025-06-30 03:29:00', '2025-06-30 10:35:01', 3),
	(13, 3, 'Report 30/06/2025', 'Report 30/06/2025', '- Report 30/06/2025\r\n	+ Cập nhật các link quảng cáo\r\n	+ Lấy bài viết đăng lên admin Bangladesh\r\n	+ Kiểm tra tất cả web và sửa một số web\r\n	+ Tool lấy app: \r\n		Lấy app mới về và bỏ qua những app đã có trước đó\r\n		Thêm phần sửa/xóa mỗi app sau khi cài', '2025年6月30日报告\n+ 更新广告链接\n+ 将文章发布到孟加拉国管理员\n+ 检查所有网站并修复部分网站\n+ 应用获取工具：\n    获取新的应用并跳过已有的应用\n    添加安装后修改/删除每个应用的功能', '2025-06-30 03:33:32', NULL, 3),
	(18, 9, '1-7-2025', '1-7-2025', '- Kiểm tra domain bangladesh, trung quốc và cloudflare\r\n- Check soulua bangladesh, trung quốc và cloudflare\r\n- Vận chuyển vật dụng máy tính qua công ty mới:\r\n	+ Lắp ráp máy tính\r\n	+ setup văn phòng mới', '- 检查孟加拉国、中国和Cloudflare的域名\n- 检查孟加拉国、中国和Cloudflare的soulua\n- 通过新公司运输电脑设备：\n    + 组装电脑\n    + 新办公室设置', '2025-07-01 10:30:00', '2025-07-04 03:10:41', 3),
	(21, 9, '2-7-2025', '2-7-2025', '- Sửa lại giao diện trang 74mao.net\r\n- Lọc cache dữ liệu trong server 3 và server 4\r\n- Check soulua bangladesh, trung quốc và cloudflare\r\n- Làm 3domain trên 6 domain mới tương tự 182.run và dns cho 3 domain \r\n	+ Lên 3 trang mới\r\n- Làm 10 domain trên tổng 20 domain cho phần cdn', '- 修改74mao.net页面界面\n- 清理服务器3和服务器4的缓存数据\n- 检查soulua孟加拉国、中国和cloudflare\n- 制作6个新域名中3个与182.run类似的域名及其DNS\n    + 建立3个新页面\n- 为CDN部分制作20个域名中的10个', '2025-07-02 10:42:00', '2025-07-03 02:35:11', 3),
	(22, 3, 'Report 1/7/2025', 'Report 1/7/2025', '+ Cập nhật các link quảng cáo\r\n+ Lấy bài viết đăng lên admin Bangladesh\r\n+ Kiểm tra tất cả web và sửa một số web\r\n+ Vận chuyển, setup máy tính cho văn phòng mới', '+ 更新广告链接\n+ 将文章发布到孟加拉国管理员\n+ 检查所有网站并修复一些网站\n+ 新办公室的电脑运输和设置', '2025-07-01 11:02:00', '2025-07-02 11:02:12', 3),
	(23, 3, 'Report 2/7/2025', 'Report 2/7/2025', '+ Cập nhật các link quảng cáo\r\n+ Lấy bài viết đăng lên admin Bangladesh\r\n+ Kiểm tra tất cả web và sửa một số web\r\n+ Tool lấy app: \r\n	Thay đổi linh hoạt các bước crawl và không cần theo tuần tự\r\n	Tối ưu tốc độ crwal\r\n+ Lên 3 trang mới', '+ 更新广告链接\n+ 将文章发布到孟加拉国管理员\n+ 检查所有网站并修复一些网站\n+ 应用抓取工具：\n    灵活更改抓取步骤，无需按顺序进行\n    优化抓取速度\n+ 建立3个新页面', '2025-07-02 11:02:32', NULL, 3),
	(25, 9, '3-7-2025', '3-7-2025', '- Làm 10 domain sài uu1.run trên tổng 20 domain cho phần cdn\r\n- Check soulua bangladesh, trung quốc và cloudflare\r\n- Làm web báo cáo :\r\n	+ Làm phân phòng ban có admin, quản lý, trưởng nhóm, user\r\n	+ Làm thông báo để nhắc nhở user để báo cáo, họp,...\r\n	+ Các role có quyền cao có thể phân các quyền cho người dùng có quyền nhỏ hơn\r\n	+ Làm thêm chức năng tải file excel cho người dùng có thể lọc dữ liệu cần tải hoặc tải tất cả\r\n	+ Làm thêm chức năng bộ lọc để lọc (ngày, ban, người gửi) tùy theo quyền của tài khoản', '- 使用uu1.run搭建10个域名，总共20个域名用于CDN\r\n- 检查孟加拉国、中国和Cloudflare的服务器\r\n- 开发报表网站：\r\n    +  设置部门权限，包括管理员、管理者、组长、用户\r\n    +  创建提醒功能，用于提醒用户提交报表、参加会议等\r\n    +  高级角色可以分配权限给低级用户\r\n    +  添加Excel文件下载功能，允许用户筛选数据后下载或下载所有数据\r\n    +  添加筛选功能，根据账户权限筛选（日期、部门、发送人）', '2025-07-04 03:01:09', '2025-07-04 10:11:14', 3),
	(26, 3, 'Report 3/7/2025', 'Report 3/7/2025', '+ Lấy bài viết đăng lên admin Bangladesh\r\n+ Kiểm tra tất cả web, 6 web hết hạn\r\n+ Tạo links CDN lấy iframe, 2 links\r\n+ Tạo 20 pages cloudflare', '+ 将文章发布到孟加拉国管理员\n+ 检查所有网站，6个网站已过期\n+ 创建CDN链接获取iframe，2个链接\n+ 创建20个Cloudflare页面', '2025-07-04 03:01:26', '2025-07-04 03:05:30', 3),
	(35, 9, '4-7-2025', '2025年7月4日', '- Check soulua bangladesh, trung quốc và cloudflare\r\n- Đổi link thống kê cho domain za\r\n- Làm web báo cáo :\r\n	+ Chuyển web báo cáo theo dạng MVC\r\n	+ Hiển thị báo cáo theo phòng ban và role cao sẽ có nhiều quyền và báo cáo\r\n	+ Cập nhật các logic cho phù hợp\r\n	+ Làm chức năng ngôn ngữ trung-việt:\r\n		1. Các phần tiếng việt mặt định sẽ không cần phải dịch sẽ lưu vào file vi và zh riêng\r\n		2. Các phần như báo cáo và tên người dùng sẽ sử dụng api của gemini để dịch tự động và lưu vào database khi đã tồn tại dữ liệu thì không cần dịch lại', '- 检查soulua孟加拉国、中国和Cloudflare\n- 更改za域名统计链接\n- 制作报表网站：\n	+ 将报表网站改成MVC模式\n	+ 根据部门显示报表，高权限角色拥有更多报表权限\n	+ 更新相关逻辑\n	+ 制作中-越语言功能：\n		1. 默认的越南语部分无需翻译，分别保存到vi和zh文件中\n		2. 报表和用户名等部分使用Gemini API自动翻译，并保存到数据库，已存在数据无需重复翻译', '2025-07-04 10:11:48', NULL, 3),
	(36, 3, 'Report 4/7/2025', '2025年7月4日报告', '+ Lấy bài viết đăng lên admin Bangladesh\r\n+ Kiểm tra tất cả web, 8 web hết hạn\r\n+ Tìm hiểu đưa tool crawl lên server', '+ 将文章发布到孟加拉国管理员\n+ 检查所有网站，8个网站已过期\n+ 研究将爬虫工具部署到服务器', '2025-07-04 10:31:14', NULL, 3),
	(37, 3, 'Report 4/7/2025', '2025年7月4日报告', '+ Lấy bài viết đăng lên admin Bangladesh\r\n+ Kiểm tra tất cả web, 8 web hết hạn\r\n+ Tìm hiểu đưa tool crawl lên server', '+ 将文章发布到孟加拉国管理员\n+ 检查所有网站，8个网站已过期\n+ 研究将爬虫工具部署到服务器', '2025-07-04 10:31:16', NULL, 3),
	(39, 3, 'Report 5/7/2025', '2025年7月5日报告', '+ Lấy bài viết đăng lên admin Bangladesh\r\n+ Kiểm tra tất cả web, 10 web hết hạn\r\n+ Cài đặt môi trường và deploy tool để sử dụng\r\n+ Chỉnh sửa links quảng cáo trong các web basa8\r\n+ Tìm cách sử dụng và test proxy để fake IP \r\n+ Viết document cho người dùng mới (đang làm)', '+ 将文章发布到孟加拉国管理员\r\n+ 检查所有网站\r\n+ 设置环境和部署工具以供使用', '2025-07-05 03:47:09', '2025-07-05 10:50:08', 3),
	(40, 9, '5-7-2025', '2025年7月5日', '- Check soulua bangladesh, trung quốc và cloudflare\r\n- Thêm phần nhảy trang cho 20 trang basa8\r\n- Làm lại web basa8pc.com\r\n- Làm web báo cáo :\r\n	+ Bổ sung phần website công ty để dễ dàng quản lý giao diện\r\n	+ Phân thêm quyền cho HR mới xem được phần quản lý website', '- 检查soulua孟加拉国、中国和Cloudflare\r\n- 为basa8的20页添加分页功能\r\n- 重做basa8pc.com网站\r\n- 制作报表网站：\r\n	+ 添加公司网站部分，以便轻松管理界面\r\n	+ 为新HR添加权限，使其可以查看网站管理部分', '2025-07-05 10:49:04', '2025-07-05 10:53:50', 3),
	(41, 9, '7-7-2025', '2025年7月7日', '- Check soulua bangladesh, trung quốc và cloudflare\r\n- Sửa lại 6 domain uu1.run, uu2.run, uu3.run, za51.run, za52.run, za53.run\r\n- Lọc cache dữ liệu trong server 3 và server 4\r\n- Kiểm tra và xóa toàn bộ cache của 20 trang basa wordpress\r\n- Kiểm tra các miền sài baladesh đã chuyển sang ba8.co \r\n- Làm phần truy cập tăng lượt truy cập bằng ip của bangladesh', '- 检查soulua孟加拉国、中国和Cloudflare\r\n- 修改6个域名uu1.run，uu2.run，uu3.run，za51.run，za52.run，za53.run\r\n- 清理服务器3和服务器4的缓存数据\r\n- 检查并清除20个basa wordpress网站的全部缓存\r\n- 检查孟加拉国域名是否已迁移到ba8.co\r\n- 使用孟加拉国IP地址增加访问量', '2025-07-07 10:40:01', '2025-07-07 10:40:32', 3),
	(42, 3, 'Report 7/7/2025', '2025年7月7日报告', '+ Cập nhật các link quảng cáo\r\n+ Tìm hiểu fake proxy spam truy cập từ basa8 qua ba8.co', '+ 更新广告链接\n+ 调查来自basa8通过ba8.co的虚假代理垃圾邮件访问', '2025-07-07 10:50:03', NULL, 3);

-- Dumping structure for table baocao.report_views
CREATE TABLE IF NOT EXISTS `report_views` (
  `id` int NOT NULL AUTO_INCREMENT,
  `report_id` int NOT NULL,
  `user_id` int NOT NULL,
  `viewed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_report_user` (`report_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `report_views_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  CONSTRAINT `report_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.report_views: ~18 rows (approximately)
DELETE FROM `report_views`;
INSERT INTO `report_views` (`id`, `report_id`, `user_id`, `viewed_at`) VALUES
	(1, 21, 1, '2025-07-04 02:51:26'),
	(2, 22, 1, '2025-07-04 02:51:30'),
	(3, 18, 1, '2025-07-04 03:10:43'),
	(4, 25, 1, '2025-07-04 10:43:12'),
	(8, 26, 1, '2025-07-04 06:26:40'),
	(9, 26, 3, '2025-07-04 03:05:55'),
	(45, 35, 1, '2025-07-04 10:20:48'),
	(46, 35, 4, '2025-07-05 10:56:20'),
	(61, 39, 4, '2025-07-05 10:51:12'),
	(62, 39, 3, '2025-07-05 10:51:07'),
	(67, 40, 4, '2025-07-05 10:53:12'),
	(68, 37, 4, '2025-07-05 10:55:50'),
	(70, 41, 4, '2025-07-07 10:55:53'),
	(72, 42, 3, '2025-07-07 10:50:09'),
	(73, 42, 4, '2025-07-07 10:50:43'),
	(75, 41, 9, '2025-07-07 10:59:47');

-- Dumping structure for table baocao.translation_cache
CREATE TABLE IF NOT EXISTS `translation_cache` (
  `id` int NOT NULL AUTO_INCREMENT,
  `source_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_lang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_lang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `translated_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_translation` (`source_text`(255),`source_lang`,`target_lang`),
  KEY `idx_translation_lookup` (`source_lang`,`target_lang`),
  KEY `idx_translation_updated` (`updated_at`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table baocao.translation_cache: ~12 rows (approximately)
DELETE FROM `translation_cache`;
INSERT INTO `translation_cache` (`id`, `source_text`, `source_lang`, `target_lang`, `translated_text`, `created_at`, `updated_at`) VALUES
	(1, 'Xin chào thế giới', 'vi', 'zh', '你好世界', '2025-07-05 07:56:15', '2025-07-05 07:56:15'),
	(2, 'Thiên Cơ Trí Liên SEO', 'vi', 'zh', 'Thien Co Lien SEO', '2025-07-05 07:57:20', '2025-07-05 07:57:20'),
	(3, 'Công ty SEO hàng đầu', 'vi', 'zh', '顶级SEO公司', '2025-07-05 07:57:20', '2025-07-05 07:57:20'),
	(4, 'Dịch vụ SEO chuyên nghiệp', 'vi', 'zh', '专业SEO服务', '2025-07-05 07:57:22', '2025-07-05 07:57:22'),
	(5, '<p>Nội dung trang chủ</p>', 'vi', 'zh', '<p>家庭内容</p>', '2025-07-05 07:57:23', '2025-07-05 07:57:23'),
	(6, 'Giải pháp SEO &amp; Marketing Đột phá cho doanh nghiệp', 'vi', 'zh', '企业的突破性SEO和营销解决方案', '2025-07-05 08:00:44', '2025-07-05 08:00:44'),
	(7, 'Tối ưu website, tăng trưởng thứ hạng Google, bứt phá doanh thu với đội ngũ chuyên gia của Thiên Cơ Trí Liên', 'vi', 'zh', '网站优化，Google排名，与Thien Co Lien的专家团队中断收入', '2025-07-05 08:00:45', '2025-07-05 08:00:45'),
	(8, '<p><strong>Thiên Cơ Trí Liên SEO</strong> là đơn vị tiên phong trong việc cung cấp các giải pháp tối ưu hóa công cụ tìm kiếm (SEO) chuyên nghiệp tại Việt Nam. Với đội ngũ chuyên gia giàu kinh nghiệm, chúng tôi cam kết đưa website của bạn lên top Google một cách bền vững và an toàn.</p>\r\n\r\n<p>Chúng tôi không chỉ giúp bạn tăng thứ hạng tìm kiếm, mà còn hỗ trợ xây dựng thương hiệu và chuyển đổi khách truy cập thành khách hàng thực sự.</p>\r\n\r\n<ul style="list-style: none; padding-left: 0;">\r\n    <li><i class="fas fa-check text-success me-2"></i> Tư vấn chiến lược SEO phù hợp từng lĩnh vực</li>\r\n    <li><i class="fas fa-check text-success me-2"></i> Nội dung chuẩn SEO, hấp dẫn người đọc</li>\r\n    <li><i class="fas fa-check text-success me-2"></i> Kỹ thuật tối ưu tốc độ, bảo mật và trải nghiệm người dùng</li>\r\n    <li><i class="fas fa-check text-success me-2"></i> Theo dõi & báo cáo hiệu quả định kỳ</li>\r\n</ul>\r\n\r\n\r\n<p>Hãy để chúng tôi đồng hành cùng doanh nghiệp bạn trên hành trình chinh phục thị trường số!</p>\r\n', 'vi', 'zh', '<p> <strong> Thien Co Lien Seo </strong>是在越南提供专业搜索引擎优化解决方案的先驱。有了一个经验丰富的专家团队，我们致力于以可持续和安全的方式将您的网站带到Google的顶端。 </p>\r\n\r\n<p>我们不仅可以帮助您提高搜索排名，还可以支持品牌发展，并将访问者转换为真正的客户。 </p>\r\n\r\n<ul style =“列表风格：无; padding-left：0;”>\r\n    <li> <i class =“ FA-Check Text-Success ME-2”> </i> SEO战略建议适合每个领域</li>\r\n    <li> <i class =“ fas fa-check text-success me-2”> </i> SEO标准的内容，有吸引力的读者</li>\r\n    <li> <i class =“ FA-Check Text-Success ME-2”> </i>最佳速度，安全性和用户体验</li>\r\n    在\r\n</ul>\r\n\r\n\r\n<p>让我们陪伴您的业务征服市场编号！ </p>', '2025-07-05 08:00:46', '2025-07-05 08:00:46'),
	(9, 'Về chúng tôi', 'vi', 'zh', '关于我们', '2025-07-05 08:19:20', '2025-07-05 08:19:20'),
	(10, '<h2>Thiên Cơ Trí Liên SEO</h2>\r\n<p>Được thành lập với sứ mệnh mang lại giải pháp SEO hiệu quả cho các doanh nghiệp Việt Nam, chúng tôi tự hào là đối tác tin cậy trong lĩnh vực tối ưu hóa công cụ tìm kiếm.</p>\r\n\r\n<h3>Dịch vụ của chúng tôi</h3>\r\n<ul style="list-style: none; padding-left: 0;">\r\n  <li><i class="fas fa-check text-success me-2"></i><strong>SEO On-page:</strong> Tối ưu hóa nội dung, thẻ meta, cấu trúc website</li>\r\n  <li><i class="fas fa-check text-success me-2"></i><strong>SEO Off-page:</strong> Xây dựng backlinks chất lượng</li>\r\n  <li><i class="fas fa-check text-success me-2"></i><strong>Technical SEO:</strong> Tối ưu hóa kỹ thuật website</li>\r\n  <li><i class="fas fa-check text-success me-2"></i><strong>Content Marketing:</strong> Tạo nội dung chất lượng</li>\r\n  <li><i class="fas fa-check text-success me-2"></i><strong>Local SEO:</strong> Tối ưu hóa cho tìm kiếm địa phương</li>\r\n</ul>\r\n\r\n<h3>Cam kết của chúng tôi</h3>\r\n<p>Chúng tôi cam kết mang lại kết quả thực tế và bền vững cho website của bạn, giúp doanh nghiệp tăng doanh số và phát triển bền vững.</p>\r\n', 'vi', 'zh', '<h2>天堂三留留置权</h2>\r\n<p>建立的使命是为越南企业带来有效的SEO解决方案，我们很荣幸能成为搜索引擎优化领域的可靠合作伙伴。\r\n\r\n<h3>我们的服务</h3>\r\n<ul style =“列表风格：无; padding-left：0;”>\r\n  <li> <i class =“ FA-Check Text-Success ME-2”> </i> <strong> seo on-Page：</strong>优化内容，元标签，网站结构</li>\r\n  <li> <i class =“ FA-Check Text-Success Me-2”> </i> <strong> SEO eferpage：</strong>构建质量反向链接\r\n  <li> <i class =“ FA-Check Text-Success ME-2”> </i> <strong>技术SEO：</strong>优化网站工程\r\n  <li> <i class =“ FA-Check Text-Success ME-2”> </i> <strong>内容营销：</strong>创建质量内容</li>\r\n  <li> <i class =“ FA-Check Text-Success Me-2”> </i> <strong>本地SEO：</strong>针对本地搜索进行了优化</li>\r\n</ul>\r\n\r\n<h3>我们的承诺</h3>\r\n<p>我们致力于为您的网站带来真正的可持续成果，帮助企业增加销售和可持续发展。 </p>', '2025-07-05 08:19:22', '2025-07-05 08:30:34'),
	(11, 'Nâng Tầm Thương Hiệu Cùng Chiến Lược SEO Đột Phá', 'vi', 'zh', '增强品牌和突破性SEO策略', '2025-07-05 08:27:15', '2025-07-05 08:27:15'),
	(12, 'Tang 5, Toa nha ABC, 123 Duong XYZ, Quan 1, TP.HCM', 'vi', 'zh', 'Tang 5，ABC，123 Duong Xyz，Quan 1，Ho Chi Minh City', '2025-07-05 08:27:20', '2025-07-05 08:27:20'),
	(13, 'Thứ 2 - Thứ 7: 9:00 - 18:00', 'vi', 'zh', '星期一 - 星期六：9：00-18：00', '2025-07-05 08:27:22', '2025-07-05 08:27:22');

-- Dumping structure for table baocao.updatereports
CREATE TABLE IF NOT EXISTS `updatereports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `report_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `title_zh` varchar(255) DEFAULT NULL,
  `content` text,
  `content_zh` text,
  `user_id` int DEFAULT NULL,
  `department_id` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.updatereports: ~3 rows (approximately)
DELETE FROM `updatereports`;
INSERT INTO `updatereports` (`id`, `report_id`, `title`, `title_zh`, `content`, `content_zh`, `user_id`, `department_id`, `updated_at`) VALUES
	(1, 47, 'dui dui dui', '嘟嘟嘟', 'Tôi rất vui. hhhhhhhhhhhhh', '我很快乐', 1, 3, '2025-07-17 06:27:53'),
	(2, 48, 'anh yêu em lắm', '我非常爱你', 'dui dui dui, anh yêu em', '呜呜呜，我爱你', 1, 3, '2025-07-17 08:08:54'),
	(3, 49, '我爱你，你爱我吗？', '我爱你，你爱我吗？', '我爱你，你爱我吗？', '我爱你，你爱我吗？', 1, 3, '2025-07-17 07:48:32'),
	(4, 50, '我欺辱啊', '我欺辱你', '我欺辱啊', '我欺辱你', 1, 3, '2025-07-17 07:53:43'),
	(5, 51, '我叫暖惊峰', '我叫暖惊峰', '我叫暖惊峰', '我叫暖惊峰', 1, 3, '2025-07-17 07:56:07'),
	(6, 52, '我叫暖惊峰', '我叫暖惊峰', '我叫暖惊峰', '我叫暖惊峰', 1, 3, '2025-07-17 08:05:21'),
	(7, 53, '我叫暖惊峰', '我叫暖惊峰', '我叫暖惊峰', '我叫暖惊峰', 1, 3, '2025-07-17 08:09:14'),
	(8, 54, 'Đúng đúng đúng', '对对对', 'Tôi tên là Nhiệt Kinh Phong.', '我叫暖惊峰我叫暖惊峰我叫暖惊峰', 1, 3, '2025-07-17 08:36:54'),
	(9, 55, 'Chào bạn', '您好', 'Chào bạn', '您好', 1, 3, '2025-07-17 08:36:06'),
	(10, 56, 'xin chào bạn', '你好', 'xin chào bạn', '你好', 1, 3, '2025-07-17 08:36:22');

-- Dumping structure for table baocao.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `name_zh` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `verification_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','quanly','nhomtruong','user') NOT NULL DEFAULT 'user',
  `department_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_department` (`department_id`),
  CONSTRAINT `fk_users_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.users: ~7 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `email`, `password`, `name`, `name_zh`, `is_verified`, `verification_code`, `created_at`, `role`, `department_id`) VALUES
	(1, 'nguyencanhphong135@gmail.com', '$2y$10$slrdXvzpPTlez.w5Yp8LQeUtokx.dVU.eqfKfIk2L9QUxbldd5bGG', 'Nguyễn Cảnh Phong', '阮景峰', 1, '7dd69f5e5a95ca3f7982edc1fb8936c7', '2025-06-30 07:49:03', 'admin', 3),
	(3, 'haido30112002@gmail.com', '$2y$10$JCm9Td05FUaNb1oq./UhpejOTs9lS2qu1mpTz34kcQ7MlmTwb66wi', 'thanhhai', '', 1, 'f302e389ac8ec47eddb4597eef89df03', '2025-06-30 08:22:43', 'user', 3),
	(4, 'arkhip04122003@gmail.com', '$2y$10$slrdXvzpPTlez.w5Yp8LQeUtokx.dVU.eqfKfIk2L9QUxbldd5bGG', 'lai long', '', 1, '2cdb20c5df5195dcd982acd860e2aafc', '2025-06-30 08:46:14', 'quanly', 3),
	(9, 'nguyencanhphong246@gmail.com', '$2y$10$At71f0lOwkFBS7.0/hc3COm9SpkrBwV82j5FZetJyG954.gEytd1K', 'Nguyễn Cảnh Phong (Nhân viên)', '阮景峰 (用户)', 1, NULL, '2025-06-30 09:41:42', 'user', 3),
	(11, 'lamtann48@gmail.com', '$2y$10$EKAfWHMhD2geqHCRQ.70xeqvbx3DPL4hoYnoW2N7vn3K.zAGhGMM.', 'Nguyễn Lâm Tấn', '', 1, NULL, '2025-07-01 07:21:03', 'nhomtruong', 2),
	(12, 'nguyencanhphong1178@gmail.com', '$2y$10$bQJ07iUG2KiXY0G8zgrzQOidYqXLHH2OHesydnmTMHsWWhjRyPnve', 'Nguyễn Dui Dui', '', 1, NULL, '2025-07-02 03:09:09', 'quanly', 2),
	(13, 'nguyencanhphong.1178@gmail.com', '$2y$10$OfUvRI//IDG0dJiexQj.p.jBIbdYUTvt5NZzvaz6YM8HDKGb4Hwr6', 'trần văn lăm', '', 1, NULL, '2025-07-03 08:59:38', 'user', 1),
	(14, 'thiencotrilien6688@gmail.com', '$2y$10$6rGFCNw0DbnWQJvzW6fuC.dzG5PDWBGNu2NIohwk3fWzzGbPtARd.', 'Jinyu', '', 1, NULL, '2025-07-04 10:16:23', 'quanly', 2);

-- Dumping structure for table baocao.website_content
CREATE TABLE IF NOT EXISTS `website_content` (
  `id` int NOT NULL AUTO_INCREMENT,
  `page` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Page name (homepage, about, contact)',
  `lang` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'vi',
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'JSON content',
  `custom_blocks` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page` (`page`),
  UNIQUE KEY `unique_page_lang` (`page`,`lang`),
  KEY `idx_website_content_lang` (`lang`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ nội dung website đa ngôn ngữ';

-- Dumping data for table baocao.website_content: ~5 rows (approximately)
DELETE FROM `website_content`;
INSERT INTO `website_content` (`id`, `page`, `lang`, `content`, `custom_blocks`, `created_at`, `updated_at`) VALUES
	(1, 'homepage', 'vi', '{"title":"Gi\\u1ea3i ph\\u00e1p SEO &amp;Marketing \\u0111\\u1ed9t ph\\u00e1 cho doanh nghi\\u1ec7p","description":"N\\u00e2ng T\\u1ea7m Th\\u01b0\\u01a1ng Hi\\u1ec7u C\\u00f9ng Chi\\u1ebfn L\\u01b0\\u1ee3c SEO \\u0110\\u1ed9t Ph\\u00e1","content":"<p><strong>Thi\\u00ean C\\u01a1 Tr\\u00ed Li\\u00ean SEO<\\/strong> l\\u00e0 \\u0111\\u01a1n v\\u1ecb ti\\u00ean phong trong vi\\u1ec7c cung c\\u1ea5p c\\u00e1c gi\\u1ea3i ph\\u00e1p t\\u1ed1i \\u01b0u h\\u00f3a c\\u00f4ng c\\u1ee5 t\\u00ecm ki\\u1ebfm (SEO) chuy\\u00ean nghi\\u1ec7p t\\u1ea1i Vi\\u1ec7t Nam. V\\u1edbi \\u0111\\u1ed9i ng\\u0169 chuy\\u00ean gia gi\\u00e0u kinh nghi\\u1ec7m, ch\\u00fang t\\u00f4i cam k\\u1ebft \\u0111\\u01b0a website c\\u1ee7a b\\u1ea1n l\\u00ean top Google m\\u1ed9t c\\u00e1ch b\\u1ec1n v\\u1eefng v\\u00e0 an to\\u00e0n.<\\/p>\\r\\n\\r\\n<p>Ch\\u00fang t\\u00f4i kh\\u00f4ng ch\\u1ec9 gi\\u00fap b\\u1ea1n t\\u0103ng th\\u1ee9 h\\u1ea1ng t\\u00ecm ki\\u1ebfm, m\\u00e0 c\\u00f2n h\\u1ed7 tr\\u1ee3 x\\u00e2y d\\u1ef1ng th\\u01b0\\u01a1ng hi\\u1ec7u v\\u00e0 chuy\\u1ec3n \\u0111\\u1ed5i kh\\u00e1ch truy c\\u1eadp th\\u00e0nh kh\\u00e1ch h\\u00e0ng th\\u1ef1c s\\u1ef1.<\\/p>\\r\\n\\r\\n<ul style=\\"list-style: none; padding-left: 0;\\">\\r\\n    <li><i class=\\"fas fa-check text-success me-2\\"><\\/i> T\\u01b0 v\\u1ea5n chi\\u1ebfn l\\u01b0\\u1ee3c SEO ph\\u00f9 h\\u1ee3p t\\u1eebng l\\u0129nh v\\u1ef1c<\\/li>\\r\\n    <li><i class=\\"fas fa-check text-success me-2\\"><\\/i> N\\u1ed9i dung chu\\u1ea9n SEO, h\\u1ea5p d\\u1eabn ng\\u01b0\\u1eddi \\u0111\\u1ecdc<\\/li>\\r\\n    <li><i class=\\"fas fa-check text-success me-2\\"><\\/i> K\\u1ef9 thu\\u1eadt t\\u1ed1i \\u01b0u t\\u1ed1c \\u0111\\u1ed9, b\\u1ea3o m\\u1eadt v\\u00e0 tr\\u1ea3i nghi\\u1ec7m ng\\u01b0\\u1eddi d\\u00f9ng<\\/li>\\r\\n    <li><i class=\\"fas fa-check text-success me-2\\"><\\/i> Theo d\\u00f5i & b\\u00e1o c\\u00e1o hi\\u1ec7u qu\\u1ea3 \\u0111\\u1ecbnh k\\u1ef3<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n\\r\\n<p>H\\u00e3y \\u0111\\u1ec3 ch\\u00fang t\\u00f4i \\u0111\\u1ed3ng h\\u00e0nh c\\u00f9ng doanh nghi\\u1ec7p b\\u1ea1n tr\\u00ean h\\u00e0nh tr\\u00ecnh chinh ph\\u1ee5c th\\u1ecb tr\\u01b0\\u1eddng s\\u1ed1!<\\/p>\\r\\n"}', '', '2025-07-05 04:01:08', '2025-07-05 10:53:29'),
	(2, 'about', 'vi', '{"title":"V\\u1ec1 ch\\u00fang t\\u00f4i","content":"<h2>Thi\\u00ean C\\u01a1 Tr\\u00ed Li\\u00ean SEO<\\/h2>\\r\\n<p>\\u0110\\u01b0\\u1ee3c th\\u00e0nh l\\u1eadp v\\u1edbi s\\u1ee9 m\\u1ec7nh mang l\\u1ea1i gi\\u1ea3i ph\\u00e1p SEO hi\\u1ec7u qu\\u1ea3 cho c\\u00e1c doanh nghi\\u1ec7p Vi\\u1ec7t Nam, ch\\u00fang t\\u00f4i t\\u1ef1 h\\u00e0o l\\u00e0 \\u0111\\u1ed1i t\\u00e1c tin c\\u1eady trong l\\u0129nh v\\u1ef1c t\\u1ed1i \\u01b0u h\\u00f3a c\\u00f4ng c\\u1ee5 t\\u00ecm ki\\u1ebfm.<\\/p>\\r\\n\\r\\n<h3>D\\u1ecbch v\\u1ee5 c\\u1ee7a ch\\u00fang t\\u00f4i<\\/h3>\\r\\n<ul style=\\"list-style: none; padding-left: 0;\\">\\r\\n  <li><i class=\\"fas fa-check text-success me-2\\"><\\/i><strong>SEO On-page:<\\/strong> T\\u1ed1i \\u01b0u h\\u00f3a n\\u1ed9i dung, th\\u1ebb meta, c\\u1ea5u tr\\u00fac website<\\/li>\\r\\n  <li><i class=\\"fas fa-check text-success me-2\\"><\\/i><strong>SEO Off-page:<\\/strong> X\\u00e2y d\\u1ef1ng backlinks ch\\u1ea5t l\\u01b0\\u1ee3ng<\\/li>\\r\\n  <li><i class=\\"fas fa-check text-success me-2\\"><\\/i><strong>Technical SEO:<\\/strong> T\\u1ed1i \\u01b0u h\\u00f3a k\\u1ef9 thu\\u1eadt website<\\/li>\\r\\n  <li><i class=\\"fas fa-check text-success me-2\\"><\\/i><strong>Content Marketing:<\\/strong> T\\u1ea1o n\\u1ed9i dung ch\\u1ea5t l\\u01b0\\u1ee3ng<\\/li>\\r\\n  <li><i class=\\"fas fa-check text-success me-2\\"><\\/i><strong>Local SEO:<\\/strong> T\\u1ed1i \\u01b0u h\\u00f3a cho t\\u00ecm ki\\u1ebfm \\u0111\\u1ecba ph\\u01b0\\u01a1ng<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<h3>Cam k\\u1ebft c\\u1ee7a ch\\u00fang t\\u00f4i<\\/h3>\\r\\n<p>Ch\\u00fang t\\u00f4i cam k\\u1ebft mang l\\u1ea1i k\\u1ebft qu\\u1ea3 th\\u1ef1c t\\u1ebf v\\u00e0 b\\u1ec1n v\\u1eefng cho website c\\u1ee7a b\\u1ea1n, gi\\u00fap doanh nghi\\u1ec7p t\\u0103ng doanh s\\u1ed1 v\\u00e0 ph\\u00e1t tri\\u1ec3n b\\u1ec1n v\\u1eefng.<\\/p>\\r\\n"}', NULL, '2025-07-05 04:01:08', '2025-07-05 08:19:16'),
	(3, 'contact', 'vi', '{"email":"info@thiencotrilien.com","phone":"0909.123.456","address":"Tang 5, Toa nha ABC, 123 Duong XYZ, Quan 1, TP.HCM","hours":"Th\\u1ee9 2 - Th\\u1ee9 7: 9:00 - 18:00","facebook":"","twitter":"","linkedin":"","instagram":"","map":""}', NULL, '2025-07-05 04:01:08', '2025-07-05 07:09:23'),
	(11, 'about_zh', 'zh', '{"title":"\\u5173\\u4e8e\\u6211\\u4eec","content":"<h2>\\u5929\\u5802\\u4e09\\u7559\\u7559\\u7f6e\\u6743<\\/h2>\\r\\n<p>\\u5efa\\u7acb\\u7684\\u4f7f\\u547d\\u662f\\u4e3a\\u8d8a\\u5357\\u4f01\\u4e1a\\u5e26\\u6765\\u6709\\u6548\\u7684SEO\\u89e3\\u51b3\\u65b9\\u6848\\uff0c\\u6211\\u4eec\\u5f88\\u8363\\u5e78\\u80fd\\u6210\\u4e3a\\u641c\\u7d22\\u5f15\\u64ce\\u4f18\\u5316\\u9886\\u57df\\u7684\\u53ef\\u9760\\u5408\\u4f5c\\u4f19\\u4f34\\u3002\\r\\n\\r\\n<h3>\\u6211\\u4eec\\u7684\\u670d\\u52a1<\\/h3>\\r\\n<ul style =\\u201c\\u5217\\u8868\\u98ce\\u683c\\uff1a\\u65e0; padding-left\\uff1a0;\\u201d>\\r\\n  <li> <i class =\\u201c FA-Check Text-Success ME-2\\u201d> <\\/i> <strong> seo on-Page\\uff1a<\\/strong>\\u4f18\\u5316\\u5185\\u5bb9\\uff0c\\u5143\\u6807\\u7b7e\\uff0c\\u7f51\\u7ad9\\u7ed3\\u6784<\\/li>\\r\\n  <li> <i class =\\u201c FA-Check Text-Success Me-2\\u201d> <\\/i> <strong> SEO eferpage\\uff1a<\\/strong>\\u6784\\u5efa\\u8d28\\u91cf\\u53cd\\u5411\\u94fe\\u63a5\\r\\n  <li> <i class =\\u201c FA-Check Text-Success ME-2\\u201d> <\\/i> <strong>\\u6280\\u672fSEO\\uff1a<\\/strong>\\u4f18\\u5316\\u7f51\\u7ad9\\u5de5\\u7a0b\\r\\n  <li> <i class =\\u201c FA-Check Text-Success ME-2\\u201d> <\\/i> <strong>\\u5185\\u5bb9\\u8425\\u9500\\uff1a<\\/strong>\\u521b\\u5efa\\u8d28\\u91cf\\u5185\\u5bb9<\\/li>\\r\\n  <li> <i class =\\u201c FA-Check Text-Success Me-2\\u201d> <\\/i> <strong>\\u672c\\u5730SEO\\uff1a<\\/strong>\\u9488\\u5bf9\\u672c\\u5730\\u641c\\u7d22\\u8fdb\\u884c\\u4e86\\u4f18\\u5316<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<h3>\\u6211\\u4eec\\u7684\\u627f\\u8bfa<\\/h3>\\r\\n<p>\\u6211\\u4eec\\u81f4\\u529b\\u4e8e\\u4e3a\\u60a8\\u7684\\u7f51\\u7ad9\\u5e26\\u6765\\u771f\\u6b63\\u7684\\u53ef\\u6301\\u7eed\\u6210\\u679c\\uff0c\\u5e2e\\u52a9\\u4f01\\u4e1a\\u589e\\u52a0\\u9500\\u552e\\u548c\\u53ef\\u6301\\u7eed\\u53d1\\u5c55\\u3002 <\\/p>"}', NULL, '2025-07-05 08:19:22', '2025-07-05 08:30:54'),
	(12, 'homepage_zh', 'zh', '{"title":"\\u4f01\\u4e1a\\u7684\\u7a81\\u7834\\u6027SEO\\u548c\\u8425\\u9500\\u89e3\\u51b3\\u65b9\\u6848","description":"\\u589e\\u5f3a\\u54c1\\u724c\\u548c\\u7a81\\u7834\\u6027SEO\\u7b56\\u7565","content":"<p> <strong> Thien Co Lien Seo <\\/strong>\\u662f\\u5728\\u8d8a\\u5357\\u63d0\\u4f9b\\u4e13\\u4e1a\\u641c\\u7d22\\u5f15\\u64ce\\u4f18\\u5316\\u89e3\\u51b3\\u65b9\\u6848\\u7684\\u5148\\u9a71\\u3002\\u6709\\u4e86\\u4e00\\u4e2a\\u7ecf\\u9a8c\\u4e30\\u5bcc\\u7684\\u4e13\\u5bb6\\u56e2\\u961f\\uff0c\\u6211\\u4eec\\u81f4\\u529b\\u4e8e\\u4ee5\\u53ef\\u6301\\u7eed\\u548c\\u5b89\\u5168\\u7684\\u65b9\\u5f0f\\u5c06\\u60a8\\u7684\\u7f51\\u7ad9\\u5e26\\u5230Google\\u7684\\u9876\\u7aef\\u3002 <\\/p>\\r\\n\\r\\n<p>\\u6211\\u4eec\\u4e0d\\u4ec5\\u53ef\\u4ee5\\u5e2e\\u52a9\\u60a8\\u63d0\\u9ad8\\u641c\\u7d22\\u6392\\u540d\\uff0c\\u8fd8\\u53ef\\u4ee5\\u652f\\u6301\\u54c1\\u724c\\u53d1\\u5c55\\uff0c\\u5e76\\u5c06\\u8bbf\\u95ee\\u8005\\u8f6c\\u6362\\u4e3a\\u771f\\u6b63\\u7684\\u5ba2\\u6237\\u3002 <\\/p>\\r\\n\\r\\n<ul style =\\u201c\\u5217\\u8868\\u98ce\\u683c\\uff1a\\u65e0; padding-left\\uff1a0;\\u201d>\\r\\n    <li> <i class =\\u201c FA-Check Text-Success ME-2\\u201d> <\\/i> SEO\\u6218\\u7565\\u5efa\\u8bae\\u9002\\u5408\\u6bcf\\u4e2a\\u9886\\u57df<\\/li>\\r\\n    <li> <i class =\\u201c fas fa-check text-success me-2\\u201d> <\\/i> SEO\\u6807\\u51c6\\u7684\\u5185\\u5bb9\\uff0c\\u6709\\u5438\\u5f15\\u529b\\u7684\\u8bfb\\u8005<\\/li>\\r\\n    <li> <i class =\\u201c FA-Check Text-Success ME-2\\u201d> <\\/i>\\u6700\\u4f73\\u901f\\u5ea6\\uff0c\\u5b89\\u5168\\u6027\\u548c\\u7528\\u6237\\u4f53\\u9a8c<\\/li>\\r\\n    \\u5728\\r\\n<\\/ul>\\r\\n\\r\\n\\r\\n<p>\\u8ba9\\u6211\\u4eec\\u966a\\u4f34\\u60a8\\u7684\\u4e1a\\u52a1\\u5f81\\u670d\\u5e02\\u573a\\u7f16\\u53f7\\uff01 <\\/p>"}', NULL, '2025-07-05 08:27:15', '2025-07-05 08:27:15'),
	(13, 'contact_zh', 'zh', '{"email":"info@thiencotrilien.com","phone":"0909.123.456","address":"Tang 5\\uff0cABC\\uff0c123 Duong Xyz\\uff0cQuan 1\\uff0cHo Chi Minh City","hours":"\\u661f\\u671f\\u4e00 - \\u661f\\u671f\\u516d\\uff1a9\\uff1a00-18\\uff1a00","facebook":"","twitter":"","linkedin":"","instagram":"","map":""}', NULL, '2025-07-05 08:27:22', '2025-07-05 08:27:22');

-- Dumping structure for table baocao.website_feedback
CREATE TABLE IF NOT EXISTS `website_feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.website_feedback: ~0 rows (approximately)
DELETE FROM `website_feedback`;

-- Dumping structure for table baocao.website_services
CREATE TABLE IF NOT EXISTS `website_services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.website_services: ~0 rows (approximately)
DELETE FROM `website_services`;

-- Dumping structure for table baocao.website_settings
CREATE TABLE IF NOT EXISTS `website_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Thiên C? Trí Liên',
  `primary_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `font_family` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `layout` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'A',
  `banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings_json` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table baocao.website_settings: ~1 rows (approximately)
DELETE FROM `website_settings`;
INSERT INTO `website_settings` (`id`, `logo`, `site_name`, `primary_color`, `font_family`, `layout`, `banner`, `settings_json`, `created_at`, `updated_at`) VALUES
	(1, 'uploads/logo-vi-white.png', 'Thiên cơ trí liên', '#1e40af', 'Inter', 'A', NULL, '{}', '2025-07-05 04:24:47', '2025-07-05 07:09:07');

-- Dumping structure for table baocao.website_stats
CREATE TABLE IF NOT EXISTS `website_stats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `value` varchar(50) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.website_stats: ~0 rows (approximately)
DELETE FROM `website_stats`;

-- Dumping structure for table baocao.website_team
CREATE TABLE IF NOT EXISTS `website_team` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `social` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table baocao.website_team: ~0 rows (approximately)
DELETE FROM `website_team`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
