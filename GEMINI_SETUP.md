# 🔧 Hướng dẫn cấu hình Gemini API cho hệ thống dịch thuật

## 📋 Bước 1: Đăng ký Gemini API Key

1. **Truy cập Google AI Studio:**
   - Vào: https://makersuite.google.com/app
   - Đăng nhập bằng tài khoản Google

2. **Tạo API Key:**
   - Click "Get API key" 
   - Chọn "Create API key"
   - Copy API key được tạo

## 📁 Bước 2: Cấu hình API Key

1. **Mở file cấu hình:**
   ```
   work/app/config/api_keys.php
   ```

2. **Thay thế API key:**
   ```php
   return [
       'gemini_api_key' => 'YOUR_ACTUAL_GEMINI_API_KEY_HERE', // Thay thế bằng key thật
       'openai_api_key' => 'YOUR_OPENAI_API_KEY_HERE', // Backup (tùy chọn)
   ];
   ```

## 🚀 Bước 3: Kiểm tra hoạt động

1. **Tạo báo cáo mới:**
   - Đăng nhập vào hệ thống
   - Tạo báo cáo mới với nội dung tiếng Việt
   - Hệ thống sẽ tự động dịch sang tiếng Trung

2. **Chuyển đổi ngôn ngữ:**
   - Sử dụng nút chọn ngôn ngữ trên giao diện
   - Nội dung sẽ hiển thị theo ngôn ngữ đã chọn

## 🔍 Tính năng

### ✅ **Dịch tự động:**
- Tiêu đề báo cáo → Tiếng Trung
- Nội dung báo cáo → Tiếng Trung
- Khi tạo báo cáo mới
- Khi cập nhật báo cáo

### ✅ **Backup system:**
- Gemini API làm chính
- OpenAI API làm backup (nếu Gemini lỗi)

### ✅ **Lưu trữ:**
- Lưu cả bản gốc và bản dịch trong database
- Hiển thị theo ngôn ngữ người dùng chọn

## 🛠️ Troubleshooting

### **Lỗi API không hoạt động:**
1. Kiểm tra API key đã được cấu hình đúng
2. Kiểm tra kết nối internet
3. Xem log lỗi trong file error log

### **Dịch không chính xác:**
- Gemini sẽ cố gắng dịch chính xác nhất có thể
- Có thể cần điều chỉnh prompt trong code nếu cần

## 📝 Lưu ý bảo mật

- **KHÔNG** commit API key vào git
- Thêm `work/app/config/api_keys.php` vào `.gitignore`
- Sử dụng biến môi trường trong production

## 🔄 Cập nhật

Để cập nhật prompt dịch thuật, chỉnh sửa trong file:
```
work/app/helpers/translate.php
```

Thay đổi dòng:
```php
['text' => "Dịch văn bản sau sang $targetLanguage, chỉ trả về bản dịch không có giải thích thêm: $text"]
``` 