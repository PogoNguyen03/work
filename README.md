# Work Management System

Hệ thống quản lý công việc mở rộng với dashboard hiện đại, tích hợp module báo cáo công việc từ project gốc.

## 🚀 Tính năng chính

### ✅ Đã hoàn thành
- **Dashboard hiện đại** với giao diện responsive
- **Module báo cáo công việc** (tích hợp từ project gốc)
  - Tạo, xem, chỉnh sửa, xóa báo cáo
  - Phân quyền theo vai trò (admin, quản lý, nhóm trưởng, user)
  - Lọc báo cáo theo ngày
  - Phân trang
- **Hệ thống xác thực** (đăng nhập/đăng xuất)
- **Quản lý người dùng** (cho admin và quản lý)
- **Hồ sơ cá nhân**

### 🔄 Đang phát triển
- **Quản lý công việc** (Task Management)
- **Hệ thống thông báo** (Notifications)
- **Export Excel** cho báo cáo
- **Thống kê và biểu đồ**

## 🏗️ Cấu trúc thư mục

```
work/
├── app/
│   ├── controllers/          # Controllers xử lý logic
│   │   ├── DashboardController.php
│   │   ├── ReportController.php      # Module báo cáo công việc
│   │   ├── UserController.php
│   │   ├── TaskController.php
│   │   ├── NotificationController.php
│   │   ├── AuthController.php
│   │   └── ProfileController.php
│   ├── models/              # Models (để phát triển)
│   ├── views/               # Views giao diện
│   │   ├── reports/         # Views cho module báo cáo
│   │   ├── users/
│   │   ├── tasks/
│   │   └── layouts/         # Layout chung
│   └── helpers/             # Helper functions
│       ├── db.php           # Kết nối database
│       ├── auth.php         # Xác thực và phân quyền
│       └── ...
├── public/                  # Entry point và assets
│   ├── index.php           # Main entry point
│   ├── .htaccess           # URL rewriting cho public
│   └── assets/             # CSS, JS, images
├── routes/                 # Định nghĩa routes
├── vendor/                 # Dependencies
├── .htaccess              # URL rewriting cho root
├── test_routes.php         # File test đường dẫn
└── composer.json
```

## 🛠️ Cài đặt

### Yêu cầu hệ thống
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx với mod_rewrite

### Bước 1: Clone và cài đặt
```bash
# Clone project
git clone <repository-url>
cd work

# Cài đặt dependencies (nếu có)
composer install
```

### Bước 2: Cấu hình database
1. Tạo database `baocao`
2. Import file `sql_schema.sql` từ project gốc
3. Cập nhật thông tin database trong `app/helpers/db.php`

### Bước 3: Cấu hình web server
- Đảm bảo mod_rewrite được bật
- Document root trỏ đến thư mục chứa folder `work`

### Bước 4: Kiểm tra cài đặt
Truy cập: `http://localhost/work/test_routes.php` để kiểm tra:
- Kết nối database
- Đường dẫn file
- Routing system
- Session configuration

### Bước 5: Truy cập hệ thống
- URL: `http://localhost/work/public/`
- Đăng nhập với tài khoản từ database

## 🔗 Cấu trúc URL

| URL | Mô tả |
|-----|-------|
| `/work/public/` | Dashboard chính |
| `/work/public/reports` | Danh sách báo cáo |
| `/work/public/reports/create` | Tạo báo cáo mới |
| `/work/public/reports/view?id=1` | Xem báo cáo |
| `/work/public/reports/edit?id=1` | Chỉnh sửa báo cáo |
| `/work/public/users` | Quản lý người dùng |
| `/work/public/tasks` | Quản lý công việc |
| `/work/public/notifications` | Thông báo |
| `/work/public/login` | Đăng nhập |
| `/work/public/profile` | Hồ sơ cá nhân |

## 👥 Phân quyền hệ thống

| Vai trò | Quyền hạn |
|---------|-----------|
| **Admin** | Toàn quyền hệ thống |
| **Quản lý** | Quản lý phòng ban, xem báo cáo phòng ban |
| **Nhóm trưởng** | Xem báo cáo user trong phòng ban |
| **User** | Tạo và quản lý báo cáo cá nhân |

## 📊 Module báo cáo công việc

Module này được tích hợp từ project gốc với các tính năng:

- **Tạo báo cáo**: Form nhập liệu với validation
- **Xem báo cáo**: Hiển thị chi tiết với định dạng đẹp
- **Chỉnh sửa**: Cập nhật báo cáo đã tạo
- **Xóa báo cáo**: Với xác nhận
- **Lọc và tìm kiếm**: Theo ngày tháng
- **Phân trang**: Hiển thị 10 báo cáo/trang

## 🎨 Giao diện

- **Responsive design** với Bootstrap 5
- **Modern UI** với gradient và shadow effects
- **Sidebar navigation** với icons
- **Card-based layout** cho dashboard
- **Interactive elements** với hover effects

## 🔧 Phát triển

### Thêm module mới
1. Tạo controller trong `app/controllers/`
2. Tạo views trong `app/views/`
3. Cập nhật routing trong `public/index.php`
4. Thêm menu item trong `layouts/header.php`

### Cấu trúc Controller
```php
<?php
require_once '../helpers/db.php';
require_once '../helpers/auth.php';

// Require login
requireLogin();

$pageTitle = 'Tên trang';
$currentPage = 'current_page';

// Logic xử lý...

// Include view
include '../views/layouts/header.php';
// Include specific view
include '../views/layouts/footer.php';
?>
```

## 🐛 Troubleshooting

### Lỗi thường gặp

1. **404 Not Found**
   - Kiểm tra mod_rewrite đã được bật
   - Kiểm tra file .htaccess có quyền đọc
   - Kiểm tra đường dẫn document root

2. **Database connection failed**
   - Kiểm tra thông tin database trong `app/helpers/db.php`
   - Đảm bảo database `baocao` đã được tạo
   - Kiểm tra quyền truy cập database

3. **Session không hoạt động**
   - Kiểm tra session_start() được gọi
   - Kiểm tra quyền ghi thư mục session

4. **Routing không hoạt động**
   - Chạy `test_routes.php` để kiểm tra
   - Kiểm tra file controller tồn tại
   - Kiểm tra cấu hình .htaccess

### Kiểm tra hệ thống
```bash
# Kiểm tra PHP version
php -v

# Kiểm tra mod_rewrite
apache2ctl -M | grep rewrite

# Kiểm tra quyền file
ls -la work/.htaccess
ls -la work/public/.htaccess
```

## 📝 Ghi chú

- Project sử dụng cấu trúc MVC đơn giản
- Database schema được kế thừa từ project gốc
- Tất cả chức năng báo cáo đã được tích hợp đầy đủ
- Các module khác đang trong giai đoạn phát triển
- File `test_routes.php` giúp kiểm tra cài đặt

## 🤝 Đóng góp

1. Fork project
2. Tạo feature branch
3. Commit changes
4. Push to branch
5. Tạo Pull Request

## 📄 License

MIT License - xem file LICENSE để biết thêm chi tiết. 