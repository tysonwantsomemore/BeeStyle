# TÀI LIỆU KỸ THUẬT VÀ LUỒNG VẬN HÀNH HỆ THỐNG BEESTYLE ATELIER
**Hệ Thống Thương Mại Điện Tử & May Đo Thời Trang Cao Cấp (Phoenix v1.24.0)**

---

## MỤC LỤC
1. [Tổng Quan Kiến Trúc & Công Nghệ](#1-tổng-quan-kiến-trúc--công-nghệ)
2. [Cấu Trúc Thư Mục Dự Án](#2-cấu-trúc-thư-mục-dự-án)
3. [Kiến Trúc Cơ Sở Dữ Liệu (Database Schema)](#3-kiến-trúc-cơ-sở-dữ-liệu-database-schema)
4. [Tầng Dữ Liệu Client (BeeDB In-Memory Store)](#4-tầng-dữ-liệu-client-beedb-in-memory-store)
5. [Chi Tiết Bộ Điều Khiển Trung Tâm (BeeCore Engine)](#5-chi-tiết-bộ-điều-khiển-trung-tâm-beecore-engine)
6. [Logic Hoạt Động Của Các Trang Giao Diện (Page Controllers)](#6-logic-hoạt-động-của-các-trang-giao-diện-page-controllers)
   - [6.1. Trang Chủ (index.html / 1.html)](#61-trang-chủ-indexhtml--1html)
   - [6.2. Trang Danh Mục & Cửa Hàng (shop.html)](#62-trang-danh-mục--cửa-hàng-shophtml)
   - [6.3. Trang Chi Tiết Sản Phẩm (product-detail.html)](#63-trang-chi-tiết-sản-phẩm-product-detailhtml)
   - [6.4. Trang Đặt Hàng & Thanh Toán (checkout.html)](#64-trang-đặt-hàng--thanh-toán-checkouthtml)
   - [6.5. Trang Xác Nhận Hoàn Tất (order-success.html)](#65-trang-xác-nhận-hoàn-tất-order-successhtml)
7. [Các Luồng Vận Hành Toàn Diện (End-to-End Operational Flows)](#7-các-luồng-vận-hành-toàn-diện-end-to-end-operational-flows)
8. [Hướng Dẫn Khởi Chạy & Triển Khai (Deployment Guide)](#8-hướng-dẫn-khởi-chạy--triển-khai-deployment-guide)

---

## 1. TỔNG QUAN KIẾN TRÚC & CÔNG NGHỆ

### 1.1. Giới Thiệu Dự Án
**BeeStyle Atelier** là nền tảng thương mại điện tử chuyên biệt cho dòng thời trang may đo (*bespoke/ready-to-wear*) cao cấp và tối giản. Dự án được xây dựng theo kiến trúc **Multi-Page Application (MPA)** kết hợp cơ chế quản lý trạng thái động (*State Management*) tại Client, đem lại trải nghiệm mượt mà không thua kém các Single-Page Application (SPA).

### 1.2. Danh Mục Công Nghệ (Tech Stack)
- **Frontend Core:** HTML5 Semantic + JavaScript ES6+ (Module / Namespace Pattern).
- **Giao diện & Thiết kế:** Tailwind CSS v3.4.17 (JIT Engine CDN) kết hợp Vanilla CSS Micro-Animations.
- **Typography & Biểu tượng:** 
  - *Cormorant Garamond*: Tiêu đề sang trọng phong cách tạp chí thời trang Haute Couture.
  - *Libre Franklin & Plus Jakarta Sans*: Thân bài tối giản, dễ đọc.
  - *Lucide Icons*: Hệ thống icon vector đồng bộ.
- **Client Persistence:** HTML5 `localStorage` lưu trữ giỏ hàng, danh sách yêu thích, thông tin thành viên, mã giảm giá và lịch sử đơn hàng.
- **Backend & Cơ Sở Dữ Liệu:** 
  - File lược đồ CSDL chuẩn MySQL/MariaDB (`database.sql`) gồm 23 bảng quan hệ.
  - Web Server cục bộ viết bằng PowerShell `System.Net.HttpListener` (`server.ps1`).

---

## 2. CẤU TRÚC THƯ MỤC DỰ ÁN

```
c:/phoenix/
├── index.html / 1.html          # Trang chủ (Hero Showcase, Zara Mega Menu, Lookbook, Storytelling)
├── shop.html                   # Trang danh mục & bộ lọc sản phẩm đa chiều
├── product-detail.html         # Trang chi tiết sản phẩm, chọn biến thể màu/size, reviews, specs
├── checkout.html               # Trang điền thông tin giao hàng, chọn thanh toán & áp coupon
├── order-success.html          # Trang thông báo đặt hàng thành công & theo dõi timeline vận chuyển
├── assets/
│   └── js/
│       └── app-core.js         # Core Engine: Client DB (BeeDB), Controller (BeeCore), Modal Injection
├── database.sql                # File DDL/DML CSDL quan hệ MySQL/MariaDB hoàn chỉnh (23 bảng)
├── server.ps1                  # HTTP Server cục bộ (Chạy trên cổng 8080)
├── v1.24.0/                    # Bộ template Phoenix Admin & Frontend v1.24.0 gốc
└── README.md                   # Hướng dẫn cài đặt và sử dụng
```

---

## 3. KIẾN TRÚC CƠ SỞ DỮ LIỆU (DATABASE SCHEMA)

CSDL quan hệ trong file `database.sql` bao gồm **23 bảng** được chuẩn hóa theo tiêu chuẩn InnoDB, bảng mã `utf8mb4_unicode_ci`:

```
+-----------------------------------------------------------------------------------+
|                                 DATABASE SCHEMA                                   |
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

### 3.1. Bảng Người Dùng & Địa Chỉ
- `users`: Lưu trữ thông tin tài khoản, email, SĐT duy nhất, vai trò (`customer`, `employee`, `admin`), trạng thái (`active`, `inactive`), thông tin tài khoản ngân hàng để hoàn tiền.
- `user_addresses`: Sổ địa chỉ người dùng (1 User có nhiều địa chỉ), có cờ `id_default` đánh dấu địa chỉ mặc định.

### 3.2. Bảng Danh Mục & Thương Hiệu
- `categories`: Hỗ trợ cây danh mục đa cấp (`parent_id`), cờ `is_active` và xóa mềm `deleted_at`.
- `brands`: Thông tin thương hiệu con (ví dụ: *Beestyle Atelier, Beestyle Studio, Beestyle Tailored, Beestyle Leather, Beestyle Footwear*).
- `category_product`: Bảng trung gian giải quyết quan hệ nhiều - nhiều (N-N) giữa sản phẩm và danh mục.

### 3.3. Bảng Sản Phẩm, Biến Thể & Thuộc Tính Động
- `products`: Thông tin sản phẩm tổng quan (Tên, SKU cha, giá gốc, giá sale, cờ `is_sale`, loại `single`/`variant`, lượt xem `views`).
- `attributes` & `attribute_values`: Thuộc tính động (ví dụ: Thuộc tính "Màu sắc" có giá trị: *Trắng Ngà, Đen Obsidian, Xanh Rêu*; "Kích cỡ": *S, M, L, XL, 39, 40*).
- `product_variants`: Biến thể SKU con cụ thể (SKU riêng, giá bán riêng, ảnh đại diện riêng theo màu, trạng thái hiển thị).
- `attribute_value_product_variant`: Bảng map tổ hợp thuộc tính cho từng biến thể SKU.
- `product_galleries`: Bộ sưu tập nhiều hình ảnh chi tiết cho sản phẩm.
- `product_stocks`: Quản lý số lượng tồn kho theo sản phẩm đơn lẻ hoặc theo từng biến thể SKU.

### 3.4. Bảng Đơn Hàng & Vận Chuyển
- `coupons`: Mã giảm giá theo % (`percent`) hoặc số tiền cố định (`fix_amount`), giới hạn lượt dùng (`usage_limit`, `usage_count`), thời hạn áp dụng.
- `payments`: Phương thức thanh toán (`COD`, `BANK`, `MOMO`).
- `orders`: Đơn hàng chính (Mã đơn `code` duy nhất, thông tin người nhận, địa chỉ, tổng tiền `total_amount`, cờ `is_paid`, `coupon_id`, chiết khấu).
- `order_items`: Chi tiết các món hàng trong đơn (lưu snapshot giá tại thời điểm mua, tên biến thể và JSON thuộc tính `attributes_variant`).
- `order_statuses`: Danh mục trạng thái (`pending`, `processing`, `shipping`, `failed_delivery`, `completed`, `cancel`).
- `order_order_status`: Lịch sử vết trạng thái đơn hàng (Tracking Log), lưu người cập nhật (`modified_by`), ghi chú, minh chứng giao hàng của nhân viên dạng JSON (`employee_evidence`) và xác nhận từ khách hàng (`customer_confirmation`).

### 3.5. Bảng Đổi Trả & Đánh Giá
- `reviews`: Đánh giá sao (1-5★), nội dung nhận xét, cờ duyệt `is_active` chống spam.
- `comments`: Hỏi đáp và bình luận tư vấn sản phẩm.
- `refunds` & `refund_items`: Quản lý khiếu nại đổi trả, hoàn tiền, lý do khách hàng, ảnh bằng chứng lỗi (`reason_image`), tài khoản ngân hàng nhận tiền và trạng thái chuyển tiền (`is_send_money`).

---

## 4. TẦNG DỮ LIỆU CLIENT (BEEDB IN-MEMORY STORE)

Trong file `assets/js/app-core.js`, đối tượng toàn cục `window.BeeDB` mô phỏng CSDL runtime giúp ứng dụng phản hồi tức thì mà không bị gián đoạn mạng:

| Trường Dữ Liệu | Kiểu | Mô Tả |
| :--- | :--- | :--- |
| `BeeDB.categories` | `Array<Object>` | 4 danh mục chính (Thời Trang Nam, Nữ, Phụ Kiện Da, Giày Thủ Công) kèm Banner & Icon. |
| `BeeDB.brands` | `Array<Object>` | 5 thương hiệu thiết kế trực thuộc Atelier. |
| `BeeDB.coupons` | `Array<Object>` | Danh sách mã ưu đãi (`BEESTYLE15`: giảm 15%, `BEESTYLE50`: giảm 50k, `FREESHIP`: giảm 30k ship). |
| `BeeDB.payments` | `Array<Object>` | 3 cổng thanh toán: COD khi nhận hàng, VietQR Ngân hàng, Ví MoMo. |
| `BeeDB.products` | `Array<Object>` | 12 sản phẩm may đo hoàn chỉnh, bao gồm cấu trúc `variants` (color, size, price, stock, thumbnail), `specs` (thông số kỹ thuật), `galleries` và `care_guide`. |
| `BeeDB.reviews` | `Array<Object>` | Đánh giá mẫu có ảnh feedback từ khách hàng VIP. |
| `BeeDB.orders` | `Array<Object>` | Đơn hàng mẫu có cấu trúc `timeline` động để phục vụ tính năng Tra cứu đơn. |

---

## 5. CHI TIẾT BỘ ĐIỀU KHIỂN TRUNG TÂM (BEECORE ENGINE)

Đối tượng `window.BeeCore` trong `assets/js/app-core.js` là Controller xử lý logic chính của toàn bộ hệ thống.

```
+------------------------------------------------------------------------------------+
|                                  BEECORE ENGINE                                    |
+------------------------------------------------------------------------------------+
|  • State: cart, wishlist, currentUser, appliedCoupon, selectedAddressId            |
|  • Init & Storage: init(), loadState(), saveCart(), saveWishlist(), saveUser()     |
|  • Cart Ops: addToCart(), updateCartQty(), removeFromCart(), clearCart()           |
|  • Calculations: getCartCalculations(), applyCoupon(), removeCoupon()              |
|  • Drawer & Badges: toggleCart(), updateCartBadge(), renderCartDrawerContent()     |
|  • Wishlist Ops: toggleWishlist(), updateWishlistBadge(), openWishlistModal()      |
|  • Auth & User: renderAuthStatus(), openAuthModal(), submitAuth(), logout()        |
|  • Order & Refund: searchOrder(), renderOrderDetails(), submitRefund()             |
|  • Search & Sizing: searchModalQuery(), openSizeGuideModal()                       |
|  • Core Injection: injectSharedModals() (Tự động tiêm 7 Modals toàn cục)           |
+------------------------------------------------------------------------------------+
```

### 5.1. Nhóm Hàm Khởi Tạo & Đồng Bộ Dữ Liệu
- `BeeCore.init()`: Tự động chạy khi sự kiện `DOMContentLoaded` phát sinh. Nạp state từ `localStorage`, gọi `injectSharedModals()`, cập nhật badge giỏ hàng/yêu thích, render trạng thái tài khoản và render lại Lucide Icons.
- `BeeCore.loadState()`: Đọc các khóa `beestyle_cart`, `beestyle_wishlist`, `beestyle_user`, `beestyle_coupon` từ `localStorage`.
- `BeeCore.saveCart()`: Ghi đè mảng giỏ hàng vào `localStorage`, cập nhật số lượng badge và gọi render lại nội dung giỏ hàng.
- `BeeCore.saveWishlist()`: Lưu danh sách ID sản phẩm yêu thích và cập nhật badge trái tim trên Header.
- `BeeCore.saveUser()`: Lưu hoặc xóa session người dùng đăng nhập.

### 5.2. Nhóm Hàm Tiện Ích Giao Diện (UI Utilities)
- `BeeCore.formatMoney(amount)`: Định dạng số nguyên thành chuỗi tiền tệ Việt Nam (`680000` -> `680.000₫`).
- `BeeCore.showToast(message, type = 'success')`: Tạo thông báo popup nổi góc trên bên phải với icon và màu sắc tương ứng (`success`: xanh/đen, `error`: đỏ hồng), tự hủy sau 3 giây.
- `BeeCore.copyCode(code)`: Ghi mã giảm giá vào Clipboard và tự động áp dụng trực tiếp vào đơn hàng.

### 5.3. Nhóm Hàm Xử Lý Giỏ Hàng (Cart Module)
- `BeeCore.addToCart(productId, variantId = null, qty = 1)`:
  1. Tra cứu thông tin sản phẩm trong `BeeDB.products`.
  2. Lấy biến thể chỉ định hoặc lấy biến thể đầu tiên làm mặc định.
  3. Xác định đơn giá (nếu có biến thể lấy `variant.price`, ngược lại xét `sale_price`/`price`).
  4. Kiểm tra item đã tồn tại trong `this.cart` theo cặp `(product_id, variant_id)`:
     - Nếu đã có: Tăng thêm `quantity += qty`.
     - Nếu chưa: Thêm object mới vào mảng `this.cart`.
  5. Gọi `saveCart()`, bắn Toast thông báo và tự động mở Drawer giỏ hàng (`toggleCart(true)`).
- `BeeCore.updateCartQty(index, delta)`: Tăng/giảm số lượng món hàng tại chỉ mục `index`. Nếu số lượng `<= 0`, tự động xóa khỏi giỏ.
- `BeeCore.removeFromCart(index)`: Xóa hẳn sản phẩm khỏi giỏ hàng.
- `BeeCore.clearCart()`: Xóa toàn bộ giỏ hàng và xóa mã giảm giá đang áp dụng.
- `BeeCore.applyCoupon(code)`: Kiểm tra mã trong `BeeDB.coupons`. Nếu hợp lệ, gán vào `this.appliedCoupon`, lưu `localStorage` và hiển thị thông báo.
- `BeeCore.removeCoupon()`: Hủy mã giảm giá đang áp dụng.
- `BeeCore.getCartCalculations()`:
  - **Tạm tính (`subtotal`):** $\sum (\text{price} \times \text{quantity})$.
  - **Giảm giá (`discount`):** Tính theo % hoặc số tiền cố định từ coupon.
  - **Phí giao hàng (`shipping`):** Miễn phí ($0₫$) nếu $\text{subtotal} \ge 500.000₫$, ngược lại là $30.000₫$.
  - **Tổng thanh toán (`total`):** $\max(0, \text{subtotal} - \text{discount} + \text{shipping})$.
  - **Tổng số lượng món (`totalCount`):** $\sum \text{quantity}$.
- `BeeCore.toggleCart(isOpen)`: Bật/tắt thanh trượt Drawer giỏ hàng bên phải và lớp overlay mờ.
- `BeeCore.updateCartBadge()`: Cập nhật số lượng và tổng tiền hiển thị trên Header.
- `BeeCore.renderCartDrawerContent()`: Render động danh sách sản phẩm, các nút điều chỉnh số lượng, ô nhập coupon, bảng tính chi phí và nút thanh toán.

### 5.4. Nhóm Hàm Danh Sách Yêu Thích (Wishlist Module)
- `BeeCore.toggleWishlist(productId)`: Thêm hoặc xóa ID sản phẩm khỏi mảng `this.wishlist`.
- `BeeCore.updateWishlistBadge()`: Đồng bộ số lượng trên icon trái tim Header.
- `BeeCore.openWishlistModal()` / `closeWishlistModal()`: Mở modal hiển thị danh sách các món đồ đã thích kèm nút "Thêm Giỏ" nhanh.

### 5.5. Nhóm Hàm Quản Lý Tài Khoản, Đổi Mật Khẩu & Xác Thực (Profile & Auth Module)
- `BeeCore.renderAuthStatus()`: Render avatar/tên khách hàng và Dropdown Menu cá nhân nếu đã đăng nhập (gồm: Card VIP Gold & Điểm thưởng, *Thông tin tài khoản*, *Đổi mật khẩu*, *Đơn hàng của tôi*, *Sổ địa chỉ & Ngân hàng*, *Đổi trả & Hoàn tiền*, *Đăng xuất*), hoặc nút "Tài Khoản" nếu là khách vãng lai.
- `BeeCore.openProfileModal(tab)` / `closeProfileModal()`: Mở/đóng modal quản lý tài khoản khách hàng `#profile-modal` với 4 tab chuyên biệt (*info*, *password*, *orders*, *address*).
- `BeeCore.switchProfileTab(tabName)`: Chuyển đổi tab linh hoạt bên trong modal tài khoản.
- `BeeCore.updateProfile(e)`: Tiếp nhận và lưu trữ thông tin cá nhân (Họ tên, SĐT, Giới tính, Ngày sinh, Địa chỉ, Tài khoản ngân hàng hoàn tiền) theo đúng schema bảng `users` & `user_addresses`.
- `BeeCore.changePassword(e)`: Xử lý đổi mật khẩu bảo mật (kiểm tra mật khẩu cũ, độ dài >= 6 ký tự, xác nhận mật khẩu mới, cập nhật cờ `is_change_password = 1`).
- `BeeCore.togglePasswordVisibility(inputId, btn)`: Ẩn/hiện mật khẩu dạng text/password.
- `BeeCore.renderProfileOrders()`: Hiển thị danh sách đơn hàng đã đặt của thành viên kèm trạng thái và nút xem timeline hành trình.
- `BeeCore.openAuthModal(mode)` / `closeAuthModal()` / `setAuthMode(mode)`: Chuyển đổi giữa chế độ Đăng Nhập và Đăng Ký.
- `BeeCore.submitAuth(e)`: Giả lập đăng nhập/tạo tài khoản, nạp đầy đủ thông tin từ `BeeDB.users` và cập nhật giao diện.
- `BeeCore.logout()`: Đăng xuất tài khoản và xóa session.

### 5.6. Nhóm Hàm Tra Cứu Đơn Hàng & Đổi Trả
- `BeeCore.openCheckout()`: Kiểm tra giỏ hàng và chuyển hướng sang `checkout.html`.
- `BeeCore.openOrderLookup()` / `closeOrderLookup()`: Mở modal tra cứu đơn hàng.
- `BeeCore.searchOrder()`: Tìm kiếm đơn hàng theo mã (Ví dụ: `BEE-2026-001` hoặc mã ngẫu nhiên vừa đặt).
- `BeeCore.renderOrderDetails(order)`: Render giao diện đơn hàng chi tiết kèm tiến trình vận chuyển Timeline từng chặng (*Đã tiếp nhận ➔ Bàn giao vận chuyển ➔ Đang giao ➔ Thành công*).
- `BeeCore.openRefundModal()` / `closeRefundModal()` / `submitRefund(e)`: Tiếp nhận yêu cầu đổi size, đổi màu hoặc hoàn tiền.

### 5.7. Nhóm Hàm Tìm Kiếm & Bảng Size
- `BeeCore.openSearchModal()` / `closeSearchModal()`: Mở modal tìm kiếm nhanh.
- `BeeCore.searchModalQuery(val)`: Lọc tức thì sản phẩm theo tên, SKU, mô tả theo thời gian thực (*Live search*).
- `BeeCore.openSizeGuideModal()` / `closeSizeGuideModal()`: Mở bảng hướng dẫn chọn size chuẩn may đo Atelier (*Nam, Nữ, Giày*).
- `BeeCore.injectSharedModals()`: Tự động tiêm mã HTML của 7 Modal dùng chung vào cuối thẻ `<body>` trên tất cả các trang, đảm bảo không cần viết lặp code HTML.

---

## 6. LOGIC HOẠT ĐỘNG CỦA CÁC TRANG GIAO DIỆN (PAGE CONTROLLERS)

---

### 6.1. Trang Chủ (`index.html` / `1.html`)
- **Zara Mega Menu:** Menu thả đa tầng hiển thị toàn màn hình khi hover vào mục "SẢN PHẨM", gồm 3 phân khu: Typography phân loại (Nam/Nữ/Phụ kiện/Giày), danh sách bộ sưu tập nổi bật và 4 ảnh lookbook trực quan.
- **Controller `MainPage`:**
  - `MainPage.toggleMobileNav()`: Bật/tắt thanh điều hướng di động.
  - `MainPage.subscribeNewsletter(e)`: Xử lý submit form nhận bản tin thời trang Beestyle Privé.

---

### 6.2. Trang Danh Mục & Cửa Hàng (`shop.html`)
- **Controller `ShopPage`:**
  - `ShopPage.init()`: Đọc tham số `?cat=` trên URL để chọn tab ban đầu.
  - `ShopPage.filterCategory(cat, updateUrl)`: Lọc theo danh mục active, cập nhật URL trình duyệt qua `history.pushState` mà không load lại trang.
  - `ShopPage.renderProducts()`: 
    - Lọc theo Danh mục (hoặc cờ `is_sale`).
    - Lọc theo Thương hiệu (`#brand-select`).
    - Lọc theo Mức giá (`#price-filter`: dưới 800k, 800k-1.5tr, trên 1.5tr).
    - Sắp xếp (`#sort-select`: phổ biến, giá tăng dần, giá giảm dần, mới nhất).
    - Render thẻ sản phẩm gồm: Badge Sale, Badge Tồn kho, Nút Mua nhanh và Nút Trái tim yêu thích.

---

### 6.3. Trang Chi Tiết Sản Phẩm (`product-detail.html`)
- **Controller `ProductPage`:**
  - `ProductPage.init()`: Trích xuất `?id=` từ URL để tìm nạp sản phẩm tương ứng từ `BeeDB.products`.
  - `ProductPage.renderProductDetails()`: Điền tên, thương hiệu, mã SKU, lượt xem, giá, mô tả ngắn, mô tả dài, thông số kỹ thuật `specs`, hướng dẫn giặt ủi `care_guide` và danh sách ảnh gallery.
  - `ProductPage.changeMainImage(imgUrl, el)`: Đổi ảnh đại diện lớn khi click vào ảnh thumbnail nhỏ.
  - `ProductPage.renderVariantSelectors()`: Tự động trích xuất các màu sắc và kích thước tương ứng có sẵn của sản phẩm.
  - `ProductPage.selectColor(colName)` / `selectVariant(variantId)`: Khi đổi màu hoặc size, tự động đổi ảnh đại diện sang biến thể đó, cập nhật lại SKU, giá tiền và số lượng tồn kho qua hàm `updatePriceAndStock()`.
  - `ProductPage.changeQty(delta)`: Tăng giảm số lượng mua trong giới hạn tồn kho.
  - `ProductPage.addToBag()`: Thêm sản phẩm cùng biến thể đang chọn vào giỏ hàng.
  - `ProductPage.buyNow()`: Thêm vào giỏ và chuyển hướng ngay sang trang `checkout.html`.
  - `ProductPage.switchTab(tabId)`: Chuyển đổi qua lại giữa 4 tab (*Mô tả, Thông số kỹ thuật, Bảo quản giặt ủi, Chính sách giao hàng*).
  - `ProductPage.submitReview(e)`: Thêm đánh giá trải nghiệm thực tế vào danh sách nhận xét.
  - `ProductPage.renderRelatedProducts()`: Gợi ý 4 sản phẩm phối đồ tương đồng (*Complete the Look*).

---

### 6.4. Trang Đặt Hàng & Thanh Toán (`checkout.html`)
- **Controller `CheckoutPage`:**
  - `CheckoutPage.init()` & `renderCheckoutSummary()`: Nếu giỏ hàng rỗng, hiển thị màn hình cảnh báo giỏ rỗng. Nếu có hàng, hiển thị danh sách sản phẩm, tạm tính, phí vận chuyển, giảm giá và tổng tiền.
  - `CheckoutPage.applyCoupon()` / `removeCoupon()`: Áp dụng mã ưu đãi trực tiếp trong trang thanh toán.
  - `CheckoutPage.submitOrder(e)`:
    1. Thu thập dữ liệu từ Form: Họ tên, SĐT, Email, Địa chỉ nhận hàng, Ghi chú, Phương thức thanh toán (COD / QR Bank).
    2. Sinh mã đơn hàng chuẩn format: `BEE-[NĂM]-[SỐ_NGẪU_NHIÊN]` (Ví dụ: `BEE-2026-7241`).
    3. Đóng gói đối tượng `newOrder` kèm Timeline trạng thái ban đầu `"Đã Tiếp Nhận"`.
    4. Thêm đơn hàng vào `BeeDB.orders`, lưu vào `localStorage.beestyle_orders` và `localStorage.beestyle_latest_order`.
    5. Làm rỗng giỏ hàng (`BeeCore.clearCart()`).
    6. Chuyển hướng người dùng sang trang `order-success.html?code=BEE-2026-XXXX`.

---

### 6.5. Trang Xác Nhận Hoàn Tất (`order-success.html`)
- Nhận mã đơn hàng từ query string trên URL.
- Hiển thị banner cảm ơn, mã đơn hàng, ngày tạo, phương thức thanh toán và dự kiến giao hàng (1 - 2 ngày).
- Hiển thị **Tiến Trình Xử Lý Đơn Hàng (Realtime Timeline)**:
  - Chặng 1: *Đã tiếp nhận đơn hàng thành công* (Đang kích hoạt).
  - Chặng 2: *Kiểm tra chất lượng & Đóng hộp quà cao cấp* (Dự kiến 2-4 giờ tới).
  - Chặng 3: *Bàn giao đơn vị vận chuyển GHN Express*.

---

## 7. CÁC LUỒNG VẬN HÀNH TOÀN DIỆN (END-TO-END OPERATIONAL FLOWS)

### 7.1. Luồng Mua Sắm & Đặt Hàng Trực Tuyến
```
[Trang Chủ index.html]
       │
       ▼ (Duyệt Lookbook hoặc Menu Zara)
[Cửa Hàng shop.html]
       │
       ▼ (Lọc Danh mục / Mức giá / Thương hiệu)
[Chi Tiết Sản Phẩm product-detail.html?id=X]
       │
       ▼ (Chọn Màu sắc, Size, Số lượng)
       ├─────────────────────────────────┐
       ▼ (Bấm "Thêm Vào Giỏ")            ▼ (Bấm "Mua Ngay")
[Drawer Giỏ Hàng Mở Ra]                  │
       │                                 │
       ▼ (Bấm "Tiến Hành Thanh Toán")    │
[Trang Thanh Toán checkout.html] ◄───────┘
       │
       ▼ (Nhập Địa chỉ, Áp mã BEESTYLE15, Chọn COD/Bank)
[Bấm "Xác Nhận Đặt Hàng"]
       │
       ▼ (Sinh mã BEE-2026-XXXX, Lưu LocalStorage, Xóa Giỏ)
[Trang Thành Công order-success.html]
```

### 7.2. Luồng Tra Cứu Tiến Trình Vận Chuyển
```
Khách Hàng Click "Tra Cứu Đơn Hàng" trên Topbar
       │
       ▼
Modal Tra Cứu Mở Ra (`BeeCore.openOrderLookup()`)
       │
       ▼
Khách Hàng Nhập Mã Đơn Hàng (Ví dụ: BEE-2026-001)
       │
       ▼
Hệ Thống Tìm Kiếm Trong BeeDB.orders & LocalStorage
       │
       ▼
Hiển Thị Timeline Từng Chặng Vận Chuyển + Danh Sách Sản Phẩm + Tổng Tiền
```

### 7.3. Luồng Đổi Trả & Bảo Hành 30 Ngày
```
Khách Hàng Click "Đổi Trả & Hoàn Tiền" trên Topbar
       │
       ▼
Modal Đổi Trả Mở Ra (`BeeCore.openRefundModal()`)
       │
       ▼
Khách Hàng Nhập Mã Đơn + Chọn Lý Do (Đổi size / Đổi màu / Lỗi NSX) + Ghi Chú
       │
       ▼
Bấm "Gửi Yêu Cầu Đổi Trả" (`BeeCore.submitRefund()`)
       │
       ▼
Hệ Thống Tiếp Nhận Yêu Cầu & Bắn Toast Thông Báo CSKH Sẽ Phản Hồi Trong 24h
```

---

## 8. HƯỚNG DẪN KHỞI CHẠY & TRIỂN KHAI (DEPLOYMENT GUIDE)

### 8.1. Khởi Chạy Local Web Server
Chạy lệnh sau trong cửa sổ PowerShell tại thư mục gốc của dự án:
```powershell
powershell -ExecutionPolicy Bypass -File .\server.ps1
```
Server sẽ tự động lắng nghe và mở trình duyệt tại:
```
http://localhost:8080
```

### 8.2. Thiết Lập Cơ Sở Dữ Liệu MySQL / MariaDB
1. Mở phpMyAdmin, MySQL Workbench hoặc DBeaver.
2. Tạo một cơ sở dữ liệu mới (ví dụ: `beestyle_db`, bảng mã `utf8mb4_unicode_ci`).
3. Thực thi toàn bộ lệnh SQL trong tệp `database.sql` để khởi tạo cấu trúc 23 bảng và dữ liệu danh mục trạng thái ban đầu.

---
*Tài liệu được biên soạn đồng bộ với phiên bản mã nguồn của dự án BeeStyle Atelier.*
