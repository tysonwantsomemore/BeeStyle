-- =======================================================
-- Database Schema for E-Commerce Platform
-- Generated from DBML Schema
-- Target: MySQL / MariaDB (InnoDB, utf8mb4)
-- =======================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `product_stocks`;
DROP TABLE IF EXISTS `refund_items`;
DROP TABLE IF EXISTS `refunds`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `order_order_status`;
DROP TABLE IF EXISTS `order_statuses`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `coupons`;
DROP TABLE IF EXISTS `product_galleries`;
DROP TABLE IF EXISTS `attribute_value_product_variant`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `attribute_value_product`;
DROP TABLE IF EXISTS `attribute_values`;
DROP TABLE IF EXISTS `attributes`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `category_product`;
DROP TABLE IF EXISTS `brands`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `user_addresses`;
DROP TABLE IF EXISTS `users`;

-- -------------------------------------------------------
-- 1. USERS & ADDRESSES
-- -------------------------------------------------------
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

-- -------------------------------------------------------
-- 2. CATEGORIES & BRANDS
-- -------------------------------------------------------
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID danh mục',
  `parent_id` INT NULL COMMENT 'ID danh mục cha',
  `icon` VARCHAR(255) NULL COMMENT 'Icon của danh mục',
  `name` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Tên danh mục (duy nhất)',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '1 là danh mục đang hiển thị, 0 nếu ẩn',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo danh mục',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật danh mục',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian xóa mềm',
  CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `brands` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID thương hiệu',
  `name` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Tên thương hiệu (duy nhất)',
  `logo` VARCHAR(255) NULL COMMENT 'Logo thương hiệu',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '1 nếu thương hiệu đang hiển thị, 0 nếu ẩn',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo thương hiệu',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật thương hiệu',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian xóa mềm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 3. PRODUCTS & ATTRIBUTES
-- -------------------------------------------------------
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID sản phẩm',
  `brand_id` INT NOT NULL COMMENT 'ID thương hiệu',
  `name` VARCHAR(250) NOT NULL COMMENT 'Tên sản phẩm',
  `views` INT DEFAULT 0 COMMENT 'Số lượt xem sản phẩm',
  `short_description` VARCHAR(255) NULL COMMENT 'Mô tả ngắn của sản phẩm',
  `description` TEXT NULL COMMENT 'Mô tả chi tiết sản phẩm',
  `thumbnail` VARCHAR(255) NOT NULL COMMENT 'Ảnh đại diện của sản phẩm',
  `type` ENUM('single', 'variant') DEFAULT 'single' COMMENT 'Loại sản phẩm',
  `sku` VARCHAR(255) NULL COMMENT 'Mã SKU của sản phẩm',
  `price` DECIMAL(11,2) NULL COMMENT 'Giá gốc sản phẩm',
  `sale_price` DECIMAL(11,2) NULL COMMENT 'Giá giảm khuyến mãi',
  `is_sale` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 nếu sản phẩm đang sale, 0 nếu không',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 nếu sản phẩm đang hiển thị, 0 nếu ẩn',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo sản phẩm',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật sản phẩm',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian xóa mềm',
  CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `category_product` (
  `category_id` INT NOT NULL COMMENT 'ID danh mục liên kết',
  `product_id` INT NOT NULL COMMENT 'ID sản phẩm liên kết',
  PRIMARY KEY (`category_id`, `product_id`),
  CONSTRAINT `fk_cp_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cp_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `attributes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID thuộc tính',
  `name` VARCHAR(255) NULL COMMENT 'Tên thuộc tính',
  `is_variant` TINYINT(1) DEFAULT 1 COMMENT '1 nếu là thuộc tính của biến thể, 0 nếu là thông số kĩ thuật',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '1 nếu thuộc tính đang hiển thị, 0 nếu ẩn',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo thuộc tính',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật thuộc tính',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian xóa mềm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `attribute_values` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID giá trị thuộc tính',
  `attribute_id` INT NOT NULL COMMENT 'ID thuộc tính liên kết',
  `value` VARCHAR(255) NOT NULL COMMENT 'Giá trị thuộc tính',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 nếu giá trị thuộc tính đang hiển thị, 0 nếu ẩn',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo giá trị thuộc tính',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật giá trị thuộc tính',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian xóa mềm',
  CONSTRAINT `fk_attr_values_attr` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `attribute_value_product` (
  `product_id` INT NOT NULL COMMENT 'ID sản phẩm liên kết',
  `attribute_value_id` BIGINT NOT NULL COMMENT 'ID giá trị thuộc tính liên kết',
  PRIMARY KEY (`product_id`, `attribute_value_id`),
  CONSTRAINT `fk_avp_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_avp_attribute_value` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_variants` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID biến thể sản phẩm',
  `product_id` INT NOT NULL COMMENT 'ID sản phẩm chính liên kết',
  `sku` VARCHAR(255) NULL COMMENT 'Mã SKU của biến thể',
  `price` DECIMAL(11,2) NOT NULL COMMENT 'Giá bán của biến thể',
  `sale_price` DECIMAL(11,2) NULL COMMENT 'Giá khuyến mãi của biến thể',
  `thumbnail` VARCHAR(255) NOT NULL COMMENT 'Ảnh đại diện của biến thể',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 nếu sản phẩm biến thể đang hiển thị, 0 nếu ẩn',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo biến thể',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật biến thể',
  CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `attribute_value_product_variant` (
  `product_variant_id` INT NOT NULL COMMENT 'ID biến thể sản phẩm',
  `attribute_value_id` BIGINT NOT NULL COMMENT 'ID giá trị thuộc tính',
  PRIMARY KEY (`product_variant_id`, `attribute_value_id`),
  CONSTRAINT `fk_avpv_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_avpv_attribute_value` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_galleries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID hình ảnh sản phẩm',
  `product_id` INT NOT NULL COMMENT 'ID sản phẩm liên kết',
  `image` VARCHAR(255) NOT NULL COMMENT 'URL hình ảnh sản phẩm',
  CONSTRAINT `fk_galleries_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_stocks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
  `product_id` INT NULL COMMENT 'ID sản phẩm',
  `product_variant_id` INT NULL COMMENT 'ID sản phẩm biến thể',
  `stock` INT DEFAULT 0 COMMENT 'Số lượng tồn kho',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo tồn kho sản phẩm',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật tồn kho sản phẩm',
  CONSTRAINT `fk_stocks_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_stocks_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 4. COUPONS, PAYMENTS & ORDERS
-- -------------------------------------------------------
CREATE TABLE `coupons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID mã giảm giá',
  `code` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã giảm giá (duy nhất)',
  `title` VARCHAR(50) NULL COMMENT 'Tiêu đề của mã giảm giá',
  `description` VARCHAR(255) NULL COMMENT 'Mô tả chi tiết của mã giảm giá',
  `discount_type` ENUM('fix_amount', 'percent') DEFAULT 'percent' COMMENT 'Kiểu giảm giá',
  `discount_value` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Giá trị giảm giá áp dụng',
  `usage_limit` INT NULL COMMENT 'Số lần sử dụng tối đa',
  `usage_count` INT DEFAULT 0 COMMENT 'Số lần mã giảm giá đã được sử dụng',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 nếu mã đang kích hoạt, 0 nếu không',
  `is_notified` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 nếu mã đã được thông báo, 0 nếu chưa',
  `start_date` TIMESTAMP NULL DEFAULT NULL COMMENT 'Ngày bắt đầu áp dụng mã giảm giá',
  `end_date` TIMESTAMP NULL DEFAULT NULL COMMENT 'Ngày kết thúc áp dụng mã giảm giá',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo mã giảm giá',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật mã giảm giá',
  `deleted_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Thời gian xóa mềm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID phương thức thanh toán',
  `name` VARCHAR(255) NOT NULL COMMENT 'Tên phương thức thanh toán',
  `logo` VARCHAR(255) NULL COMMENT 'Logo phương thức thanh toán',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '1 nếu đang kích hoạt, 0 nếu không',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo phương thức thanh toán',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật phương thức thanh toán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID đơn hàng',
  `code` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã đơn hàng (duy nhất)',
  `user_id` INT NULL COMMENT 'ID người dùng đặt hàng',
  `payment_id` INT NULL COMMENT 'ID phương thức thanh toán',
  `phone_number` VARCHAR(20) NULL COMMENT 'Số điện thoại liên lạc của người mua',
  `email` VARCHAR(255) NULL COMMENT 'Email liên lạc của người mua',
  `fullname` VARCHAR(255) NULL COMMENT 'Họ và tên của người nhận',
  `address` VARCHAR(255) NULL COMMENT 'Địa chỉ giao hàng',
  `note` VARCHAR(255) NULL COMMENT 'Ghi chú của khách hàng',
  `total_amount` DECIMAL(12,2) NOT NULL COMMENT 'Tổng tiền thanh toán cho đơn hàng',
  `is_paid` TINYINT(1) DEFAULT 0 COMMENT '1 nếu đã thanh toán, 0 nếu chưa',
  `is_refund` TINYINT(1) DEFAULT 0 COMMENT '1 nếu là đơn hoàn, 0 nếu không',
  `coupon_id` INT NULL COMMENT 'ID mã giảm giá',
  `coupon_code` VARCHAR(50) NULL COMMENT 'Code mã giảm giá',
  `coupon_description` VARCHAR(50) NULL COMMENT 'Mô tả giảm giá',
  `coupon_discount_type` VARCHAR(50) NULL COMMENT 'Loại giảm giá',
  `coupon_discount_value` VARCHAR(50) NULL COMMENT 'Giá trị giảm của mã giảm giá',
  `max_discount_value` DECIMAL(11,2) NULL COMMENT 'Giá trị giảm tối đa',
  `is_refund_cancel` TINYINT(1) DEFAULT 0 COMMENT '1 Nếu hủy hàng, 0 Nếu không',
  `check_refund_cancel` TINYINT(1) DEFAULT 0 COMMENT '1 Nếu đã chuyển tiền, 0 Nếu chưa',
  `img_send_refund_money` VARCHAR(255) NULL COMMENT 'Ảnh minh chứng khi đã trả tiền đơn hoàn',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo đơn hàng',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật đơn hàng',
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_orders_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_statuses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID trạng thái đơn hàng',
  `name` VARCHAR(255) NOT NULL COMMENT 'Tên trạng thái'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Khởi tạo dữ liệu trạng thái đơn hàng
INSERT INTO `order_statuses` (`id`, `name`) VALUES
(1, 'pending'),
(2, 'processing'),
(3, 'shipping'),
(4, 'failed_delivery'),
(5, 'completed'),
(6, 'cancel')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

CREATE TABLE `order_order_status` (
  `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
  `order_id` BIGINT NOT NULL COMMENT 'ID đơn hàng (Đồng bộ kiểu BIGINT với orders.id)',
  `order_status_id` INT NOT NULL COMMENT 'ID trạng thái đơn hàng',
  `modified_by` INT NOT NULL COMMENT 'ID người xử lý đơn hàng',
  `note` VARCHAR(255) NULL COMMENT 'Ghi chú của người xử lý',
  `employee_evidence` JSON NULL COMMENT 'Minh chứng của nhân viên',
  `customer_confirmation` TINYINT(1) NULL COMMENT 'null nếu nhân viên mới gửi minh chứng, 1 xác nhận nhận được hàng, 0 xác nhận không nhận được',
  `is_current` TINYINT(1) DEFAULT 1 COMMENT '1 nếu là trạng thái hiện tại, 0 nếu là trạng thái cũ',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo trạng thái đơn hàng',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật trạng thái đơn hàng',
  CONSTRAINT `fk_oos_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_oos_status` FOREIGN KEY (`order_status_id`) REFERENCES `order_statuses` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_oos_user` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID chi tiết đơn hàng',
  `order_id` BIGINT NOT NULL COMMENT 'ID đơn hàng liên kết',
  `product_id` INT NULL COMMENT 'ID sản phẩm',
  `product_variant_id` INT NULL COMMENT 'ID biến thể sản phẩm',
  `name` VARCHAR(255) NULL COMMENT 'Tên sản phẩm',
  `price` DECIMAL(11,2) NULL COMMENT 'Giá sản phẩm',
  `old_price` DECIMAL(11,2) NULL COMMENT 'Giá cũ sản phẩm',
  `old_price_variant` DECIMAL(11,2) NULL COMMENT 'Giá cũ sản phẩm biến thể',
  `quantity` INT DEFAULT 1 COMMENT 'Số lượng sản phẩm trong đơn hàng',
  `name_variant` VARCHAR(255) NULL COMMENT 'Tên biến thể của sản phẩm',
  `attributes_variant` JSON NULL COMMENT 'Thông tin thuộc tính biến thể (dạng JSON)',
  `price_variant` DECIMAL(11,2) NULL COMMENT 'Giá của biến thể sản phẩm',
  `quantity_variant` INT NULL COMMENT 'Số lượng của biến thể sản phẩm',
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cart_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID giỏ hàng',
  `user_id` INT NOT NULL COMMENT 'ID người dùng liên kết',
  `product_id` INT NULL COMMENT 'ID sản phẩm',
  `product_variant_id` INT NULL COMMENT 'ID biến thể sản phẩm',
  `quantity` INT NOT NULL DEFAULT 1 COMMENT 'Số lượng sản phẩm trong giỏ hàng',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo giỏ hàng',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật giỏ hàng',
  CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cart_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 5. REVIEWS & COMMENTS
-- -------------------------------------------------------
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID đánh giá',
  `product_id` INT NOT NULL COMMENT 'ID sản phẩm được đánh giá',
  `order_id` BIGINT NOT NULL COMMENT 'ID đơn hàng liên quan',
  `user_id` INT NULL COMMENT 'ID người dùng đánh giá',
  `rating` INT NOT NULL COMMENT 'Số sao đánh giá (1-5)',
  `review_text` TEXT NULL COMMENT 'Nội dung đánh giá',
  `reason` VARCHAR(255) NULL COMMENT 'Lý do không duyệt đánh giá',
  `is_active` TINYINT(1) DEFAULT 1 COMMENT '1: là trạng thái duyệt, 0: là trạng thái không duyệt',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo đánh giá',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật đánh giá',
  CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID bình luận',
  `product_id` INT NOT NULL COMMENT 'ID sản phẩm được bình luận',
  `user_id` INT NOT NULL COMMENT 'ID người dùng bình luận',
  `content` TEXT NOT NULL COMMENT 'Nội dung bình luận',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo bình luận',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật bình luận',
  CONSTRAINT `fk_comments_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 6. REFUNDS & REFUND ITEMS
-- -------------------------------------------------------
CREATE TABLE `refunds` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
  `order_id` BIGINT NULL COMMENT 'ID đơn hàng (Đồng bộ kiểu BIGINT với orders.id)',
  `user_id` INT NULL COMMENT 'ID người dùng',
  `total_amount` DECIMAL(12,2) NULL COMMENT 'Tổng tiền',
  `bank_account` VARCHAR(255) NULL COMMENT 'Tài khoản ngân hàng',
  `user_bank_name` VARCHAR(255) NULL COMMENT 'Tên tài khoản ngân hàng',
  `phone_number` VARCHAR(20) NULL COMMENT 'Số điện thoại',
  `bank_name` VARCHAR(100) NULL COMMENT 'Tên ngân hàng thụ hưởng',
  `reason` TEXT NULL COMMENT 'Lý do của khách hàng',
  `fail_reason` TEXT NULL COMMENT 'Lý do lỗi',
  `img_fail_or_completed` TEXT NULL COMMENT 'Ảnh khi đơn hàng bị lỗi',
  `reason_image` TEXT NULL COMMENT 'Ảnh hoặc video của sản phẩm lỗi',
  `admin_reason` TEXT NULL COMMENT 'Lý do của admin khi từ chối',
  `is_send_money` TINYINT(1) DEFAULT 0 COMMENT '1 Nếu đã chuyển tiền, 0 nếu chưa chuyển tiền',
  `status` ENUM('pending', 'receiving', 'completed', 'rejected', 'failed', 'cancel') DEFAULT 'pending' COMMENT 'Trạng thái hoàn hàng',
  `bank_account_status` ENUM('unverified', 'sent', 'verified') DEFAULT 'unverified' COMMENT 'Trạng thái tài khoản ngân hàng',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  CONSTRAINT `fk_refunds_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_refunds_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `refund_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID chi tiết hoàn hàng',
  `refund_id` INT NOT NULL COMMENT 'ID hoàn hàng',
  `product_id` INT NULL COMMENT 'ID sản phẩm',
  `variant_id` INT NULL COMMENT 'ID biến thể sản phẩm',
  `name` VARCHAR(255) NULL COMMENT 'Tên sản phẩm',
  `name_variant` VARCHAR(255) NULL COMMENT 'Tên biến thể của sản phẩm',
  `quantity` INT DEFAULT 1 COMMENT 'Số lượng sản phẩm trong đơn hàng',
  `price` DECIMAL(11,2) NULL COMMENT 'Giá sản phẩm',
  `price_variant` DECIMAL(11,2) NULL COMMENT 'Giá của biến thể sản phẩm',
  `quantity_variant` INT NULL COMMENT 'Số lượng của biến thể sản phẩm',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  CONSTRAINT `fk_refund_items_refund` FOREIGN KEY (`refund_id`) REFERENCES `refunds` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_refund_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_refund_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
