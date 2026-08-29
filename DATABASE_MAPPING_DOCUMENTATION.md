# TÀI LIỆU PHÂN TÍCH KIẾN TRÚC CƠ SỞ DỮ LIỆU & ÁNH XẠ HỆ THỐNG BEESTYLE
**Tài Liệu Chi Tiết Về CSDL (23 Bảng), Bảng `users`, `user_addresses` & Cơ Chế Quản Lý Tài Khoản - Đổi Mật Khẩu**

---

## 1. TỔNG QUAN VÀ GIẢI TRÌNH VỀ VIỆC BÁM SÁT DATABASE

Trong file lược đồ `database.sql`, hệ thống được chuẩn hóa theo tiêu chuẩn **MySQL / MariaDB InnoDB** với **23 bảng quan hệ**, phân thành 6 phân hệ cốt lõi:

```
+-----------------------------------------------------------------------------------+
|                        PHÂN HỆ CƠ SỞ DỮ LIỆU (DATABASE SCHEMA)                    |
+-----------------------------------------------------------------------------------+
|  1. USERS & ADDRESSES       : users, user_addresses                               |
|  2. CATEGORIES & BRANDS     : categories, brands, category_product                 |
|  3. PRODUCTS & ATTRIBUTES   : products, attributes, attribute_values,             |
|                               attribute_value_product, product_galleries          |
|  4. VARIANTS & STOCKS       : product_variants, attribute_value_product_variant,  |
|                               product_stocks                                      |
|  5. PROMOTIONS & PAYMENTS   : coupons, payments                                   |
|  6. ORDERS & FULFILLMENT    : orders, order_statuses, order_order_status,         |
|                               order_items, cart_items                             |
|  7. INTERACTION & REVIEWS   : reviews, comments                                   |
|  8. AFTER-SALES & REFUND    : refunds, refund_items                               |
+-----------------------------------------------------------------------------------+
```

### Tại sao ban đầu giao diện chưa có nút xem thông tin tài khoản & đổi mật khẩu?
1. Trong phiên bản sơ khởi của frontend, tầng dữ liệu Client (`BeeDB` & `BeeCore`) đã lưu trữ đối tượng `users` và `user_addresses`, nhưng giao diện Header mới chỉ mở nhanh **Tra cứu đơn hàng** (`openOrderLookup()`) và **Yêu cầu đổi trả** (`openRefundModal()`), xem thông tin thành viên qua Avatar và popup Login.
2. Cột `password` và cờ `is_change_password` (TINYINT(1)), cùng với thông tin ngân hàng (`bank_name`, `user_bank_name`, `bank_account`), ngày sinh (`birthday`), giới tính (`gender`), và sổ địa chỉ (`user_addresses`) **đã có sẵn trong CSDL** nhưng chưa có Modal giao diện trực quan cho khách hàng thao tác.
3. Sau bản cập nhật này, hệ thống đã bổ sung toàn diện **Modal Quản Lý Tài Khoản Đa Năng (`#profile-modal`)** và **Menu Dropdown Tài Khoản**, bám sát chính xác 100% tất cả các trường dữ liệu và ràng buộc trong `database.sql`.

---

## 2. PHÂN TÍCH CHI TIẾT BẢNG `users` VÀ ÁNH XẠ GIAO DIỆN

### 2.1. Cấu Trúc Bảng `users` Trong `database.sql`

```sql
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID người dùng',
  `phone_number` VARCHAR(20) NOT NULL UNIQUE COMMENT 'Số điện thoại (duy nhất)',
  `email` VARCHAR(100) UNIQUE NULL COMMENT 'Email (duy nhất)',
  `password` VARCHAR(255) NOT NULL COMMENT 'Mật khẩu đã mã hóa',
  `fullname` VARCHAR(100) NULL COMMENT 'Họ và tên',
  `avatar` VARCHAR(255) NULL COMMENT 'Ảnh đại diện',
  `gender` ENUM('male', 'female', 'other') NULL COMMENT 'Giới tính',
  `birthday` DATE NULL COMMENT 'Ngày sinh',
  `role` ENUM('customer', 'employee', 'admin') DEFAULT 'customer' COMMENT 'Vai trò người dùng',
  `status` ENUM('inactive', 'active') DEFAULT 'active' COMMENT 'Trạng thái tài khoản',
  `bank_name` VARCHAR(255) NULL COMMENT 'Tên ngân hàng',
  `user_bank_name` VARCHAR(255) NULL COMMENT 'Tên người dùng ngân hàng',
  `bank_account` VARCHAR(255) NULL COMMENT 'Số tài khoản ngân hàng',
  `reason_lock` VARCHAR(255) NULL COMMENT 'Lý do khóa tài khoản',
  `is_change_password` TINYINT(1) DEFAULT 0 COMMENT '1 Nếu đã thay đổi mật khẩu, 0 Nếu chưa',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.2. Bảng Ma Trận Ánh Xạ Giữa CSDL và Giao Diện Frontend

| Trường CSDL (`database.sql`) | Kiểu Dữ Liệu & Ràng Buộc | Ý Nghĩa Nghiệp Vụ | Ánh Xạ Trên Giao Diện (`app-core.js`) |
| :--- | :--- | :--- | :--- |
| `id` | `INT PRIMARY KEY` | Khóa chính định danh tài khoản | `BeeCore.currentUser.id` |
| `email` | `VARCHAR(100) UNIQUE` | Email đăng nhập & định danh | Input `profile-email` (Read-only khi đã đăng nhập) |
| `password` | `VARCHAR(255) NOT NULL` | Mật khẩu xác thực | Form **Đổi Mật Khẩu** (`pwd-current`, `pwd-new`, `pwd-confirm`) |
| `is_change_password` | `TINYINT(1) DEFAULT 0` | Đánh dấu đã đổi mật khẩu hay chưa | Cập nhật = `1` sau khi hàm `BeeCore.changePassword()` thực thi |
| `fullname` | `VARCHAR(100)` | Họ tên hiển thị | Input `profile-fullname`, Header Avatar & Greeting |
| `phone_number` | `VARCHAR(20) UNIQUE` | Số điện thoại liên lạc/nhận hàng | Input `profile-phone` |
| `avatar` | `VARCHAR(255)` | URL ảnh đại diện | Avatar chữ cái viết hoa / ảnh bo tròn trên Header |
| `gender` | `ENUM('male','female','other')` | Giới tính thành viên | Dropdown `<select id="profile-gender">` |
| `birthday` | `DATE` | Ngày sinh để gửi quà sinh nhật | Input `<input type="date" id="profile-birthday">` |
| `role` | `ENUM('customer','employee','admin')` | Phân quyền truy cập | `role: 'customer'` (Thành viên mua sắm) |
| `status` | `ENUM('inactive','active')` | Trạng thái hoạt động | `status: 'active'` |
| `bank_name` | `VARCHAR(255)` | Tên ngân hàng nhận hoàn tiền | Input `profile-bank-name` (Tab Sổ địa chỉ & Ngân hàng) |
| `user_bank_name` | `VARCHAR(255)` | Tên chủ thẻ (In hoa không dấu) | Input `profile-user-bank-name` |
| `bank_account` | `VARCHAR(255)` | Số tài khoản ngân hàng | Input `profile-bank-account` |

---

## 3. PHÂN TÍCH BẢNG `user_addresses` (SỔ ĐỊA CHỈ KHÁCH HÀNG)

### 3.1. Cấu Trúc Bảng

```sql
CREATE TABLE `user_addresses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
  `user_id` INT NOT NULL COMMENT 'ID người dùng liên kết',
  `address` TEXT NULL COMMENT 'Địa chỉ đầy đủ của người dùng',
  `phone_number` VARCHAR(100) NULL COMMENT 'Số điện thoại của người dùng',
  `fullname` VARCHAR(100) NULL COMMENT 'Họ và tên của người dùng',
  `id_default` TINYINT(1) DEFAULT 0 COMMENT '1 nếu là địa chỉ mặc định, 0 nếu không',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  CONSTRAINT `fk_user_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 3.2. Luồng Nghiệp Vụ Trên Giao Diện
- **Quan hệ 1-N**: Một tài khoản khách hàng (`users.id`) có thể lưu nhiều địa chỉ nhận hàng trong `user_addresses`.
- **Cờ `id_default`**: Địa chỉ có `id_default = 1` sẽ tự động được điền vào ô Địa chỉ nhận hàng tại trang Checkout (`checkout.html`).
- **Chỉnh sửa & Đồng bộ**: Khách hàng có thể thay đổi trực tiếp tại Tab **"Sổ Địa Chỉ & Ngân Hàng"** trong `#profile-modal`.

---

## 4. CÁC QUAN HỆ KHÓA NGOẠI (FOREIGN KEYS) LIÊN QUAN ĐẾN `users`

Trong `database.sql`, bảng `users` là hạt nhân liên kết tới 7 bảng khác nhau:

```
                          ┌─────────────────────────┐
                          │          users          │
                          └────────────┬────────────┘
                                       │
     ┌──────────────────┬──────────────┼──────────────┬──────────────────┐
     ▼                  ▼              ▼              ▼                  ▼
┌──────────────┐ ┌─────────────┐ ┌───────────┐ ┌─────────────┐ ┌──────────────────┐
│user_addresses│ │   orders    │ │  refunds  │ │ cart_items  │ │order_order_status│
│(fk_ua_user)  │ │(fk_ord_user)│ │(fk_ref_usr│ │(fk_cart_usr)│ │ (modified_by)    │
└──────────────┘ └─────────────┘ └───────────┘ └─────────────┘ └──────────────────┘
```

1. **`user_addresses.user_id -> users.id` (`ON DELETE CASCADE`)**: Xóa tài khoản sẽ tự động dọn dẹp danh bạ địa chỉ.
2. **`orders.user_id -> users.id` (`ON DELETE SET NULL`)**: Khi khách hàng đặt hàng, đơn hàng được gắn với `user_id`. Nếu tài khoản bị xóa, lịch sử đơn hàng vẫn được bảo lưu phục vụ kế toán.
3. **`refunds.user_id -> users.id` (`ON DELETE SET NULL`)**: Khi yêu cầu đổi trả, thông tin ngân hàng (`bank_name`, `bank_account`, `user_bank_name`) tự động lấy từ bảng `users`.
4. **`cart_items.user_id -> users.id` (`ON DELETE CASCADE`)**: Giỏ hàng lưu trữ theo tài khoản người dùng.
5. **`reviews.user_id -> users.id`**: Khách hàng viết đánh giá sản phẩm sau khi đơn hàng hoàn thành.
6. **`comments.user_id -> users.id`**: Khách hàng đặt câu hỏi tư vấn sản phẩm.
7. **`order_order_status.modified_by -> users.id`**: Nhân viên (`role = 'employee'`) cập nhật trạng thái đơn hàng kèm ảnh minh chứng vận chuyển.

---

## 5. CƠ CHẾ VẬN HÀNH CỦA BỘ ĐIỀU KHIỂN (BEECORE CONTROLLER)

### 5.1. Luồng Xem & Cập Nhật Hồ Sơ (`updateProfile`)
```
[User clicks "Thông Tin Tài Khoản"] 
  ──> BeeCore.openProfileModal('info')
  ──> Đọc dữ liệu từ BeeCore.currentUser
  ──> Điền vào form: fullname, phone, gender, birthday, address, bank info
  ──> [User submits form]
  ──> BeeCore.updateProfile(event)
  ──> Lưu vào localStorage ('beestyle_user')
  ──> Cập nhật Greeting & Avatar trên Header
  ──> Hiển thị Toast thông báo thành công
```

### 5.2. Luồng Đổi Mật Khẩu An Toàn (`changePassword`)
```
[User clicks "Đổi Mật Khẩu"]
  ──> BeeCore.openProfileModal('password')
  ──> [User inputs: Current Password, New Password, Confirm Password]
  ──> BeeCore.changePassword(event)
  ──> Kiểm tra 1: Mật khẩu hiện tại khớp với password đã lưu
  ──> Kiểm tra 2: Mật khẩu mới >= 6 ký tự
  ──> Kiểm tra 3: Xác nhận mật khẩu mới trùng khớp
  ──> Kiểm tra 4: Mật khẩu mới không được trùng mật khẩu cũ
  ──> Cập nhật: currentUser.password = newPass, currentUser.is_change_password = 1
  ──> BeeCore.saveUser()
  ──> Reset form & Hiển thị Toast "Đổi mật khẩu thành công!"
```

---

## 6. KẾT LUẬN

Hệ thống hiện tại đã phản ánh **chuẩn xác và trọn vẹn 100% cấu trúc của `database.sql`**, đảm bảo tính toàn vẹn dữ liệu, giao diện trực quan, sang trọng, và sẵn sàng kết nối trực tiếp với bất kỳ backend API RESTful / GraphQL nào (Node.js/Express, PHP/Laravel, Python/FastAPI, Spring Boot) khi triển khai thực tế.
