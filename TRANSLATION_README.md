# Tính năng Dịch Tự Động cho Website

## Tổng quan

Hệ thống đã được tích hợp tính năng dịch tự động từ tiếng Việt sang tiếng Trung cho tất cả nội dung website. Tính năng này sử dụng Google Translate API và có hệ thống cache để tối ưu hiệu suất.

## Cách hoạt động

### 1. Dịch tự động
- Khi admin cập nhật nội dung website (trang chủ, giới thiệu, liên hệ), hệ thống sẽ tự động kiểm tra xem nội dung có thay đổi không
- Nếu có thay đổi, hệ thống sẽ tự động dịch sang tiếng Trung và lưu vào database
- Nếu không có thay đổi, hệ thống sẽ sử dụng bản dịch đã cache

### 2. Dịch thủ công
- Admin có thể nhấn nút "Dịch sang tiếng Trung" trên mỗi tab để dịch thủ công
- Hệ thống sẽ dịch toàn bộ nội dung hiện tại và cập nhật database

### 3. Hệ thống Cache
- Tất cả bản dịch được lưu trong bảng `translation_cache`
- Mỗi cặp text gốc - bản dịch được lưu với key duy nhất
- Tránh gọi API lặp lại cho cùng một nội dung

## Cài đặt

### 1. Tạo bảng cache
Chạy file SQL để tạo bảng cache:
```sql
-- Chạy file: work/translation_cache_schema.sql
```

### 2. Cấu hình API Key (Tùy chọn)
Để sử dụng Google Translate API chính thức:
1. Đăng ký Google Cloud Platform
2. Bật Google Translate API
3. Tạo API key
4. Cập nhật trong file `TranslationService.php`:
```php
$this->apiKey = 'your_actual_api_key_here';
```

**Lưu ý**: Nếu không có API key, hệ thống sẽ sử dụng fallback method (không cần key nhưng có thể bị giới hạn).

## Cấu trúc Database

### Bảng translation_cache
```sql
CREATE TABLE translation_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source_text TEXT NOT NULL,           -- Text gốc
    source_lang VARCHAR(10) NOT NULL,    -- Ngôn ngữ nguồn (vi)
    target_lang VARCHAR(10) NOT NULL,    -- Ngôn ngữ đích (zh)
    translated_text TEXT NOT NULL,       -- Bản dịch
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_translation (source_text(255), source_lang, target_lang)
);
```

## Sử dụng

### 1. Dịch tự động
- Chỉ cần cập nhật nội dung website bình thường
- Hệ thống sẽ tự động dịch khi có thay đổi
- Thông báo sẽ hiển thị: "Nội dung đã được dịch tự động sang tiếng Trung"

### 2. Dịch thủ công
- Vào tab muốn dịch (Trang chủ, Giới thiệu, Liên hệ)
- Nhấn nút "Dịch sang tiếng Trung"
- Xác nhận và chờ hệ thống xử lý

### 3. Xem kết quả
- Nội dung tiếng Trung được lưu trong database
- Website sẽ hiển thị nội dung theo ngôn ngữ được chọn
- Sử dụng language switcher để chuyển đổi ngôn ngữ

## Tối ưu hóa

### 1. Cache Management
```php
// Xóa cache cũ (30 ngày)
$translationService->clearOldCache(30);

// Xem thống kê cache
$stats = $translationService->getCacheStats();
```

### 2. Trường không dịch
Các trường sau sẽ không được dịch:
- Email
- Số điện thoại
- Link mạng xã hội
- Mã nhúng (iframe)

### 3. Error Handling
- Nếu dịch thất bại, hệ thống sẽ giữ nguyên text gốc
- Lỗi được log trong error log
- Thông báo lỗi hiển thị cho admin

## Troubleshooting

### 1. Dịch không hoạt động
- Kiểm tra kết nối internet
- Kiểm tra API key (nếu sử dụng)
- Xem error log để debug

### 2. Cache không hoạt động
- Kiểm tra bảng `translation_cache` đã được tạo
- Kiểm tra quyền ghi database
- Xóa cache và thử lại

### 3. Hiệu suất chậm
- Kiểm tra index trên bảng cache
- Xóa cache cũ định kỳ
- Sử dụng API key chính thức thay vì fallback

## Bảo mật

### 1. API Key
- Không commit API key vào git
- Sử dụng environment variables
- Rotate API key định kỳ

### 2. Rate Limiting
- Fallback method có thể bị giới hạn request
- Sử dụng cache để giảm số lượng API call
- Monitor usage để tránh quota limit

## Monitoring

### 1. Log Files
- Translation errors: `error_log`
- API responses: `error_log`
- Cache operations: `error_log`

### 2. Database Monitoring
- Kiểm tra kích thước bảng cache
- Monitor query performance
- Clean up old cache entries

## Future Enhancements

### 1. Tính năng có thể thêm
- Dịch sang nhiều ngôn ngữ khác
- Batch translation
- Translation quality review
- Custom translation memory

### 2. Tối ưu hóa
- Async translation
- Queue system
- CDN for translations
- Machine learning improvements 