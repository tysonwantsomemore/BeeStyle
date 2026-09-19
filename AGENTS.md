# Quy Định Khởi Động & Phát Triển Dự Án BeeStyle (Laravel)

## 1. Bản Chất Dự Án (Single Source of Truth)
- Dự án này là **Laravel Fullstack Application** (chạy bằng PHP, Laravel Router, Blade views, Controller, SQLite/MySQL).
- Tuyệt đối **KHÔNG chạy web tĩnh** (`php -S 127.0.0.1:8080`) trỏ vào các file prototype `.html` ở thư mục gốc.

## 2. Quy Định Khởi Động Dự Án (Standard Startup Protocol)
Khi người dùng yêu cầu:
- *"chạy đi"*, *"start"*, *"run"*, *"khởi động dự án"*, *"chạy server"*

Agent **BẮT BUỘC** thực hiện lệnh:
```bash
php artisan serve
```
- **Địa chỉ máy chủ:** `http://127.0.0.1:8000`
- **Trang chủ:** `http://127.0.0.1:8000/`
- **Đăng nhập:** `http://127.0.0.1:8000/dang-nhap`
- **Đăng ký:** `http://127.0.0.1:8000/dang-ky`
- **Admin:** `http://127.0.0.1:8000/admin`

## 3. Quy Trình Kiểm Tra Code (Quality Assurance)
- Luôn kiểm tra `php artisan test` trước khi hoàn tất task.
- Không tự động commit code lên Git trừ khi người dùng yêu cầu cụ thể.
