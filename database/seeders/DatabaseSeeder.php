<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Khởi tạo dữ liệu mẫu chuẩn hóa toàn bộ hệ thống thời trang BeeStyle.
     */
    public function run(): void
    {
        // 1. TÀI KHOẢN QUẢN TRỊ VIÊN (ADMIN), NHÂN VIÊN & KHÁCH HÀNG MẪU
        $admin = User::create([
            'name' => 'Quản Trị Viên BeeStyle',
            'email' => 'admin@beestyle.com',
            'phone' => '0901234567',
            'gender' => 'Nam',
            'dob' => '1992-05-15',
            'role' => 'admin',
            'address' => 'Tòa nhà BeeStyle Center, Cầu Giấy, Hà Nội',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/57.webp',
            'bank_name' => 'Vietcombank',
            'bank_account_number' => '0071001234567',
            'bank_account_name' => 'QUAN TRI VIEN BEESTYLE',
            'bank_branch' => 'Chi nhánh Hà Nội',
            'password' => Hash::make('password'),
            'password_changed_at' => now(),
            'points' => 0,
        ]);

        $staff = User::create([
            'name' => 'Nhân Viên Bán Hàng',
            'email' => 'staff@beestyle.vn',
            'phone' => '0977777777',
            'gender' => 'Nữ',
            'dob' => '1996-08-20',
            'role' => 'admin',
            'address' => 'Cửa hàng BeeStyle Flagship, Đống Đa, Hà Nội',
            'city' => 'Hà Nội',
            'district' => 'Đống Đa',
            'status' => 'active',
            'password' => Hash::make('password'),
            'password_changed_at' => now(),
            'points' => 0,
        ]);

        $customer1 = User::create([
            'name' => 'Nguyễn Văn Hùng',
            'email' => 'hung.nguyen@gmail.com',
            'phone' => '0987654321',
            'gender' => 'Nam',
            'dob' => '1995-10-20',
            'role' => 'customer',
            'address' => 'Số 18 Phố Huế, Hoàn Kiếm, Hà Nội',
            'city' => 'Hà Nội',
            'district' => 'Hoàn Kiếm',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/58.webp',
            'bank_name' => 'Techcombank',
            'bank_account_number' => '19034567890012',
            'bank_account_name' => 'NGUYEN VAN HUNG',
            'bank_branch' => 'Chi nhánh Hà Nội',
            'password' => Hash::make('password'),
            'password_changed_at' => now()->subDays(10),
            'points' => 1250,
        ]);

        $customer2 = User::create([
            'name' => 'Lê Hoàng Long',
            'email' => 'hoanglong.le@gmail.com',
            'phone' => '0903888999',
            'gender' => 'Nam',
            'dob' => '1990-03-12',
            'role' => 'customer',
            'address' => 'Toà Park 7 Times City, Hai Bà Trưng, Hà Nội',
            'city' => 'Hà Nội',
            'district' => 'Hai Bà Trưng',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/30.webp',
            'bank_name' => 'MB Bank (Quân Đội)',
            'bank_account_number' => '0903888999',
            'bank_account_name' => 'LE HOANG LONG',
            'bank_branch' => 'Chi nhánh Đà Nẵng',
            'password' => Hash::make('password'),
            'password_changed_at' => now()->subDays(30),
            'points' => 480,
        ]);

        $customer3 = User::create([
            'name' => 'Lê Tuấn Anh',
            'email' => 'tuananh.le@gmail.com',
            'phone' => '0945678901',
            'gender' => 'Nam',
            'dob' => '1993-12-05',
            'role' => 'customer',
            'address' => '45 Lê Duẩn, Quận 1, TP. Hồ Chí Minh',
            'city' => 'TP. Hồ Chí Minh',
            'district' => 'Quận 1',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/59.webp',
            'password' => Hash::make('password'),
            'password_changed_at' => now()->subDays(5),
            'points' => 890,
        ]);

        // Sổ địa chỉ nhận hàng
        UserAddress::create([
            'user_id' => $customer1->id,
            'recipient_name' => 'Nguyễn Văn Hùng',
            'phone' => '0987654321',
            'city' => 'Hà Nội',
            'district' => 'Hoàn Kiếm',
            'ward' => 'Phường Hàng Bài',
            'address' => 'Số 18 Phố Huế',
            'label' => 'Nhà riêng',
            'is_default' => true,
            'notes' => 'Giao giờ hành chính hoặc gọi trước khi giao',
        ]);

        UserAddress::create([
            'user_id' => $customer1->id,
            'recipient_name' => 'Nguyễn Văn Hùng (Văn phòng)',
            'phone' => '0987654321',
            'city' => 'Hà Nội',
            'district' => 'Cầu Giấy',
            'ward' => 'Phường Dịch Vọng',
            'address' => 'Tòa nhà FPT Tower, Tầng 12',
            'label' => 'Văn phòng',
            'is_default' => false,
            'notes' => 'Chỉ nhận hàng trong giờ làm việc (8h30 - 17h30)',
        ]);

        // 2. THƯƠNG HIỆU THỜI TRANG (BRANDS)
        $brandSignature = Brand::create([
            'name' => 'BeeStyle Signature',
            'slug' => 'beestyle-signature',
            'logo' => '/assets/img/icons/icon-1.png',
            'banner' => '/assets/img/generic/1.png',
            'description' => 'Dòng thời trang nam cao cấp độc quyền từ BeeStyle, thiết kế tinh xảo theo phong cách thanh lịch hiện đại.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $brandLuxury = Brand::create([
            'name' => 'Bee Luxury Line',
            'slug' => 'bee-luxury-line',
            'logo' => '/assets/img/icons/icon-2.png',
            'banner' => '/assets/img/generic/2.png',
            'description' => 'Bộ sưu tập vest cưới, blazer dự tiệc và sơ mi lụa tơ tằm thượng hạng dành riêng cho quý ông lịch lãm.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $brandUrban = Brand::create([
            'name' => 'Bee Urban Casual',
            'slug' => 'bee-urban-casual',
            'logo' => '/assets/img/icons/icon-3.png',
            'banner' => '/assets/img/generic/3.png',
            'description' => 'Phong cách đường phố trẻ trung, tối giản, năng động dành cho các hoạt động thường ngày và dạo phố.',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $brandSport = Brand::create([
            'name' => 'Bee Sport Tech',
            'slug' => 'bee-sport-tech',
            'logo' => '/assets/img/icons/icon-4.png',
            'banner' => '/assets/img/generic/4.png',
            'description' => 'Trang phục thể thao ứng dụng công nghệ làm mát Air-Cool và co giãn 4 chiều vận động tối ưu.',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // 3. DANH MỤC THỜI TRANG CHUẨN (KHỚP 100% VỚI TÊN VÀ ẢNH ĐẠI DIỆN)
        $catPolo = Category::create([
            'name' => 'Áo Polo Nam',
            'slug' => 'ao-polo-nam',
            'icon' => 'fa-solid fa-shirt',
            'image' => '/assets/img/products/polo_01.jpg',
            'description' => 'Áo polo cotton dệt tổ ong thoáng khí, co giãn 4 chiều chuẩn phom dáng',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catShirt = Category::create([
            'name' => 'Áo Sơ Mi Nam',
            'slug' => 'ao-so-mi-nam',
            'icon' => 'fa-solid fa-user-tie',
            'image' => '/assets/img/products/somi_01.jpg',
            'description' => 'Sơ mi lụa và cotton kháng nhăn cao cấp, phom slimfit tôn dáng nơi công sở',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catTshirt = Category::create([
            'name' => 'Áo Phông & T-Shirt',
            'slug' => 'ao-phong-tshirt-nam',
            'icon' => 'fa-solid fa-vest-patches',
            'image' => '/assets/img/products/tshirt_01.jpg',
            'description' => 'Áo phông nam cotton 250GSM dày dặn, phong cách Streetwear Boxy trẻ trung',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catBlazer = Category::create([
            'name' => 'Áo Khoác & Blazer Nam',
            'slug' => 'ao-khoac-blazer-nam',
            'icon' => 'fa-solid fa-vest',
            'image' => '/assets/img/products/outerwear_01.jpg',
            'description' => 'Blazer phong cách Hàn Quốc tối giản & áo khoác gió trượt nước 2 lớp thời thượng',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $catThun = Category::create([
            'name' => 'Áo Thun Nam',
            'slug' => 'ao-thun-nam',
            'icon' => 'fa-solid fa-layer-group',
            'image' => '/assets/img/products/tshirt_black.jpg',
            'description' => 'Áo thun nam basic trơn, thun co giãn 4 chiều mát lạnh, dạo phố và thể thao',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $catThuDong = Category::create([
            'name' => 'Áo Thu Đông Nam',
            'slug' => 'ao-thu-dong-nam',
            'icon' => 'fa-solid fa-snowflake',
            'image' => '/assets/img/products/hoodie_1.jpg',
            'description' => 'Bộ sưu tập áo hoodie nỉ bông, áo khoác gió lót nỉ ấm áp cho mùa thu đông',
            'sort_order' => 6,
            'is_active' => true,
        ]);

        $catSummer = Category::create([
            'name' => 'Bộ Sưu Tập Mùa Hè',
            'slug' => 'bo-suu-tap-mua-he',
            'icon' => 'fa-solid fa-sun',
            'image' => '/assets/img/products/somi_linen_white.jpg',
            'description' => 'Trang phục mùa hè thoáng mát, chất liệu đũi linen và cotton lụa đi biển dạo phố cực mát.',
            'sort_order' => 7,
            'is_active' => true,
        ]);

        // 4. DANH SÁCH 70 SẢN PHẨM (MỖI DANH MỤC ĐÚNG 10 SẢN PHẨM KHÔNG TRÙNG NHAU)
        $catalog = [
            // DANH MỤC 1: ÁO POLO NAM (10 sản phẩm)
            [
                'cat' => $catPolo, 'brand' => $brandSignature,
                'items' => [
                    ['sku' => 'BS-POLO-01', 'name' => 'Áo Polo Nam Cotton Dệt Tổ Ong Kháng Khuẩn BeeStyle', 'price' => 389000, 'orig' => 499000, 'img' => '/assets/img/products/polo_01.jpg', 'sold' => 1240, 'rating' => 4.9, 'rev' => 86, 'featured' => true],
                    ['sku' => 'BS-POLO-02', 'name' => 'Áo Polo Nam Phối Bo Cổ Viền Kẻ Thể Thao Năng Động', 'price' => 379000, 'orig' => 480000, 'img' => '/assets/img/products/polo_02.jpg', 'sold' => 950, 'rating' => 4.8, 'rev' => 64, 'featured' => false],
                    ['sku' => 'BS-POLO-03', 'name' => 'Áo Polo Nam Trơn Basic Cotton Cá Sấu Co Giãn 4 Chiều', 'price' => 349000, 'orig' => 450000, 'img' => '/assets/img/products/polo_03.jpg', 'sold' => 1430, 'rating' => 4.9, 'rev' => 115, 'featured' => true],
                    ['sku' => 'BS-POLO-04', 'name' => 'Áo Polo Nam Cổ Bẻ Khóa Kéo Hiện Đại Air-Cool', 'price' => 419000, 'orig' => 550000, 'img' => '/assets/img/products/polo_04.jpg', 'sold' => 780, 'rating' => 4.7, 'rev' => 52, 'featured' => false],
                    ['sku' => 'BS-POLO-05', 'name' => 'Áo Polo Nam Dệt Kim Cổ V Lịch Lãm Phong Cách Quý Ông', 'price' => 459000, 'orig' => 590000, 'img' => '/assets/img/products/polo_05.jpg', 'sold' => 610, 'rating' => 5.0, 'rev' => 43, 'featured' => true],
                    ['sku' => 'BS-POLO-06', 'name' => 'Áo Polo Nam Phom Slimfit Cộc Tay Vải Pique Cao Cấp', 'price' => 369000, 'orig' => 470000, 'img' => '/assets/img/products/polo_06.jpg', 'sold' => 880, 'rating' => 4.8, 'rev' => 58, 'featured' => false],
                    ['sku' => 'BS-POLO-07', 'name' => 'Áo Polo Nam Thể Thao Nhanh Khô Dry-Fit Chống Nhăn', 'price' => 329000, 'orig' => 420000, 'img' => '/assets/img/products/polo_07.jpg', 'sold' => 1120, 'rating' => 4.9, 'rev' => 79, 'featured' => true],
                    ['sku' => 'BS-POLO-08', 'name' => 'Áo Polo Nam Cổ Đức Phối Màu Độc Quyền BeeStyle', 'price' => 399000, 'orig' => 510000, 'img' => '/assets/img/products/polo_08.jpg', 'sold' => 690, 'rating' => 4.8, 'rev' => 49, 'featured' => false],
                    ['sku' => 'BS-POLO-09', 'name' => 'Áo Polo Nam Vải Sợi Tre Bamboo Thấm Hút Mồ Hôi', 'price' => 439000, 'orig' => 560000, 'img' => '/assets/img/products/polo_09.jpg', 'sold' => 840, 'rating' => 4.9, 'rev' => 62, 'featured' => true],
                    ['sku' => 'BS-POLO-10', 'name' => 'Áo Polo Nam Tay Dài Cổ Bẻ Dệt Kim Thu Đông', 'price' => 449000, 'orig' => 580000, 'img' => '/assets/img/products/polo_10.jpg', 'sold' => 530, 'rating' => 4.7, 'rev' => 37, 'featured' => false],
                ]
            ],

            // DANH MỤC 2: ÁO SƠ MI NAM (10 sản phẩm)
            [
                'cat' => $catShirt, 'brand' => $brandLuxury,
                'items' => [
                    ['sku' => 'BS-SOMI-01', 'name' => 'Áo Sơ Mi Nam Trắng Công Sở Dài Tay Sợi Tre Kháng Nhăn', 'price' => 499000, 'orig' => 650000, 'img' => '/assets/img/products/somi_01.jpg', 'sold' => 1340, 'rating' => 4.9, 'rev' => 92, 'featured' => true],
                    ['sku' => 'BS-SOMI-02', 'name' => 'Áo Sơ Mi Nam Lụa Tơ Tằm Đen Sang Trọng Easy-Iron', 'price' => 549000, 'orig' => 690000, 'img' => '/assets/img/products/somi_02.jpg', 'sold' => 980, 'rating' => 5.0, 'rev' => 74, 'featured' => true],
                    ['sku' => 'BS-SOMI-03', 'name' => 'Áo Sơ Mi Nam Oxford Cổ Button-Down Phong Cách Ivy League', 'price' => 459000, 'orig' => 580000, 'img' => '/assets/img/products/somi_03.jpg', 'sold' => 810, 'rating' => 4.8, 'rev' => 59, 'featured' => false],
                    ['sku' => 'BS-SOMI-04', 'name' => 'Áo Sơ Mi Nam Xanh Pastel Cổ Bẻ Slimfit Tôn Dáng', 'price' => 489000, 'orig' => 620000, 'img' => '/assets/img/products/somi_04.jpg', 'sold' => 1150, 'rating' => 4.9, 'rev' => 81, 'featured' => true],
                    ['sku' => 'BS-SOMI-05', 'name' => 'Áo Sơ Mi Nam Cộc Tay Vải Đũi Linen Thoáng Mát', 'price' => 399000, 'orig' => 520000, 'img' => '/assets/img/products/somi_05.jpg', 'sold' => 760, 'rating' => 4.7, 'rev' => 48, 'featured' => false],
                    ['sku' => 'BS-SOMI-06', 'name' => 'Áo Sơ Mi Nam Kẻ Sọc Nhỏ Công Sở Chống Nhăn Tuyệt Đối', 'price' => 469000, 'orig' => 590000, 'img' => '/assets/img/products/somi_06.jpg', 'sold' => 640, 'rating' => 4.8, 'rev' => 42, 'featured' => false],
                    ['sku' => 'BS-SOMI-07', 'name' => 'Áo Sơ Mi Nam Cổ Trụ Phom Rộng Dạo Phố Trẻ Trung', 'price' => 439000, 'orig' => 550000, 'img' => '/assets/img/products/somi_07.jpg', 'sold' => 890, 'rating' => 4.9, 'rev' => 66, 'featured' => true],
                    ['sku' => 'BS-SOMI-08', 'name' => 'Áo Sơ Mi Nam Họa Tiết Vi Mô Dệt Nổi Jacquard Cao Cấp', 'price' => 529000, 'orig' => 680000, 'img' => '/assets/img/products/somi_08.jpg', 'sold' => 570, 'rating' => 4.8, 'rev' => 38, 'featured' => false],
                    ['sku' => 'BS-SOMI-09', 'name' => 'Áo Sơ Mi Nam Flannel Kẻ Caro Dày Dặn Vintage Casual', 'price' => 479000, 'orig' => 600000, 'img' => '/assets/img/products/somi_09.jpg', 'sold' => 720, 'rating' => 4.7, 'rev' => 51, 'featured' => false],
                    ['sku' => 'BS-SOMI-10', 'name' => 'Áo Sơ Mi Nam Denim Cotton Dáng Suông Phủi Bụi', 'price' => 499000, 'orig' => 640000, 'img' => '/assets/img/products/somi_10.jpg', 'sold' => 680, 'rating' => 4.8, 'rev' => 45, 'featured' => false],
                ]
            ],

            // DANH MỤC 3: ÁO PHÔNG & T-SHIRT (10 sản phẩm)
            [
                'cat' => $catTshirt, 'brand' => $brandUrban,
                'items' => [
                    ['sku' => 'BS-TSHIRT-01', 'name' => 'Áo Phông Nam Cotton 250GSM Form Boxy Dày Dặn Streetwear', 'price' => 289000, 'orig' => 380000, 'img' => '/assets/img/products/tshirt_01.jpg', 'sold' => 1950, 'rating' => 5.0, 'rev' => 160, 'featured' => true],
                    ['sku' => 'BS-TSHIRT-02', 'name' => 'Áo Phông Nam Cổ Tròn Basic Cotton Compact 100% Kháng Khuẩn', 'price' => 249000, 'orig' => 320000, 'img' => '/assets/img/products/tshirt_02.jpg', 'sold' => 1820, 'rating' => 4.9, 'rev' => 142, 'featured' => true],
                    ['sku' => 'BS-TSHIRT-03', 'name' => 'Áo Phông Nam In Graphic Nghệ Thuật Vintage BeeStyle', 'price' => 299000, 'orig' => 390000, 'img' => '/assets/img/products/tshirt_03.jpg', 'sold' => 870, 'rating' => 4.8, 'rev' => 68, 'featured' => false],
                    ['sku' => 'BS-TSHIRT-04', 'name' => 'Áo Phông Nam Oversize Vai Trễ Phong Cách Hip-Hop', 'price' => 279000, 'orig' => 360000, 'img' => '/assets/img/products/tshirt_04.jpg', 'sold' => 1240, 'rating' => 4.9, 'rev' => 95, 'featured' => true],
                    ['sku' => 'BS-TSHIRT-05', 'name' => 'Áo Phông Nam Tay Lỡ Thêu Chữ Nổi Cao Cấp Bee Urban', 'price' => 269000, 'orig' => 350000, 'img' => '/assets/img/products/tshirt_05.jpg', 'sold' => 930, 'rating' => 4.7, 'rev' => 61, 'featured' => false],
                    ['sku' => 'BS-TSHIRT-06', 'name' => 'Áo Phông Nam Cổ Tim V-Neck Co Giãn 4 Chiều Ôm Nhẹ', 'price' => 239000, 'orig' => 310000, 'img' => '/assets/img/products/tshirt_06.jpg', 'sold' => 680, 'rating' => 4.8, 'rev' => 47, 'featured' => false],
                    ['sku' => 'BS-TSHIRT-07', 'name' => 'Áo Phông Nam Cotton Supima Cao Cấp Mềm Mịn Chống Bai Xù', 'price' => 319000, 'orig' => 420000, 'img' => '/assets/img/products/tshirt_07.jpg', 'sold' => 1100, 'rating' => 5.0, 'rev' => 88, 'featured' => true],
                    ['sku' => 'BS-TSHIRT-08', 'name' => 'Áo Phông Nam Thể Thao Air-Cool Thoáng Khí Chạy Bộ', 'price' => 259000, 'orig' => 340000, 'img' => '/assets/img/products/tshirt_08.jpg', 'sold' => 820, 'rating' => 4.8, 'rev' => 54, 'featured' => false],
                    ['sku' => 'BS-TSHIRT-09', 'name' => 'Áo Phông Nam Wash Acid Loang Màu Cá Tính Phong Trần', 'price' => 309000, 'orig' => 400000, 'img' => '/assets/img/products/tshirt_09.jpg', 'sold' => 740, 'rating' => 4.7, 'rev' => 49, 'featured' => false],
                    ['sku' => 'BS-TSHIRT-10', 'name' => 'Áo Phông Nam Phối Túi Ngực Tiện Lợi Vải Dệt Xước', 'price' => 249000, 'orig' => 330000, 'img' => '/assets/img/products/tshirt_10.jpg', 'sold' => 630, 'rating' => 4.8, 'rev' => 39, 'featured' => false],
                ]
            ],

            // DANH MỤC 4: ÁO KHOÁC & BLAZER NAM (10 sản phẩm)
            [
                'cat' => $catBlazer, 'brand' => $brandLuxury,
                'items' => [
                    ['sku' => 'BS-OUTER-01', 'name' => 'Áo Blazer Nam 2 Lớp Phong Cách Hàn Quốc Phom Regular Fit', 'price' => 899000, 'orig' => 1250000, 'img' => '/assets/img/products/outerwear_01.jpg', 'sold' => 530, 'rating' => 5.0, 'rev' => 48, 'featured' => true],
                    ['sku' => 'BS-OUTER-02', 'name' => 'Áo Blazer Nam Vải Tweed Dạ Dệt Kim Cài 2 Khuy Sang Trọng', 'price' => 989000, 'orig' => 1390000, 'img' => '/assets/img/products/outerwear_02.jpg', 'sold' => 420, 'rating' => 4.9, 'rev' => 36, 'featured' => true],
                    ['sku' => 'BS-OUTER-03', 'name' => 'Áo Khoác Da Nam Cao Cấp Lót Lụa Habutai Chống Nước', 'price' => 1190000, 'orig' => 1650000, 'img' => '/assets/img/products/outerwear_03.jpg', 'sold' => 380, 'rating' => 5.0, 'rev' => 32, 'featured' => true],
                    ['sku' => 'BS-OUTER-04', 'name' => 'Áo Khoác Bomber Nam Vải Gió Dày Dặn Phối Khóa Zip Đồng', 'price' => 689000, 'orig' => 890000, 'img' => '/assets/img/products/outerwear_04.jpg', 'sold' => 850, 'rating' => 4.8, 'rev' => 67, 'featured' => true],
                    ['sku' => 'BS-OUTER-05', 'name' => 'Áo Khoác Gió Nam 2 Lớp Trượt Nước Thể Thao Cản Gió', 'price' => 599000, 'orig' => 790000, 'img' => '/assets/img/products/outerwear_05.jpg', 'sold' => 1120, 'rating' => 4.9, 'rev' => 89, 'featured' => true],
                    ['sku' => 'BS-OUTER-06', 'name' => 'Áo Khoác Kaki Nam Dáng Măng Tô Ngắn Cổ Bẻ Thanh Lịch', 'price' => 759000, 'orig' => 990000, 'img' => '/assets/img/products/outerwear_06.jpg', 'sold' => 470, 'rating' => 4.8, 'rev' => 39, 'featured' => false],
                    ['sku' => 'BS-OUTER-07', 'name' => 'Áo Khoác Jean Denim Nam Rách Nhẹ Phong Cách Retro', 'price' => 699000, 'orig' => 920000, 'img' => '/assets/img/products/outerwear_07.jpg', 'sold' => 640, 'rating' => 4.7, 'rev' => 51, 'featured' => false],
                    ['sku' => 'BS-OUTER-08', 'name' => 'Áo Khoác Chần Bông Siêu Nhẹ Giữ Nhiệt Mùa Lạnh', 'price' => 829000, 'orig' => 1090000, 'img' => '/assets/img/products/outerwear_08.jpg', 'sold' => 590, 'rating' => 4.9, 'rev' => 44, 'featured' => false],
                    ['sku' => 'BS-OUTER-09', 'name' => 'Áo Blazer Nam Kẻ Caro Phong Cách Quý Tộc Anh Quốc', 'price' => 949000, 'orig' => 1290000, 'img' => '/assets/img/products/outerwear_09.jpg', 'sold' => 360, 'rating' => 4.9, 'rev' => 29, 'featured' => false],
                    ['sku' => 'BS-OUTER-10', 'name' => 'Áo Khoác Cardigan Nam Dệt Kim Cổ V Cài Cúc Vintage', 'price' => 529000, 'orig' => 690000, 'img' => '/assets/img/products/outerwear_10.jpg', 'sold' => 610, 'rating' => 4.8, 'rev' => 47, 'featured' => false],
                ]
            ],

            // DANH MỤC 5: ÁO THUN NAM (10 sản phẩm)
            [
                'cat' => $catThun, 'brand' => $brandUrban,
                'items' => [
                    ['sku' => 'BS-THUN-01', 'name' => 'Áo Thun Nam Trơn Đen Sang Trọng Vải Cotton Spandex', 'price' => 219000, 'orig' => 290000, 'img' => '/assets/img/products/tshirt_black.jpg', 'sold' => 1650, 'rating' => 4.9, 'rev' => 128, 'featured' => true],
                    ['sku' => 'BS-THUN-02', 'name' => 'Áo Thun Nam Màu Xám Tro Vải Dệt Melange Độc Đáo', 'price' => 229000, 'orig' => 300000, 'img' => '/assets/img/products/tshirt_charcoal.jpg', 'sold' => 1180, 'rating' => 4.8, 'rev' => 84, 'featured' => false],
                    ['sku' => 'BS-THUN-03', 'name' => 'Áo Thun Nam Xám Ghi Cổ Tròn Co Giãn 4 Chiều', 'price' => 219000, 'orig' => 290000, 'img' => '/assets/img/products/tshirt_grey.jpg', 'sold' => 990, 'rating' => 4.7, 'rev' => 71, 'featured' => false],
                    ['sku' => 'BS-THUN-04', 'name' => 'Áo Thun Nam Xanh Rêu Olive Phom Vừa Vặn Dạo Phố', 'price' => 239000, 'orig' => 310000, 'img' => '/assets/img/products/tshirt_olive.jpg', 'sold' => 840, 'rating' => 4.8, 'rev' => 59, 'featured' => false],
                    ['sku' => 'BS-THUN-05', 'name' => 'Áo Thun Nam Streetwear Họa Tiết Lưng Độc Bản BeeStyle', 'price' => 269000, 'orig' => 350000, 'img' => '/assets/img/products/tshirt_oversize_street.jpg', 'sold' => 1310, 'rating' => 5.0, 'rev' => 97, 'featured' => true],
                    ['sku' => 'BS-THUN-06', 'name' => 'Áo Thun Nam In Chữ Retro Phong Cách Cổ Điển Âu Mỹ', 'price' => 249000, 'orig' => 330000, 'img' => '/assets/img/products/tshirt_vintage_print.jpg', 'sold' => 770, 'rating' => 4.7, 'rev' => 53, 'featured' => false],
                    ['sku' => 'BS-THUN-07', 'name' => 'Áo Thun Nam Trắng Tinh Khôi Cổ Bo Dệt Không Gião', 'price' => 229000, 'orig' => 300000, 'img' => '/assets/img/products/tshirt_white.jpg', 'sold' => 1480, 'rating' => 4.9, 'rev' => 112, 'featured' => true],
                    ['sku' => 'BS-THUN-08', 'name' => 'Áo Thun Nam Thể Thao Tập Gym Thoát Mồ Hôi Tức Thì', 'price' => 239000, 'orig' => 320000, 'img' => '/assets/img/products/tshirt_1.jpg', 'sold' => 920, 'rating' => 4.8, 'rev' => 64, 'featured' => false],
                    ['sku' => 'BS-THUN-09', 'name' => 'Áo Thun Nam Dài Tay Thu Đông Giữ Nhiệt Nhẹ Nhàng', 'price' => 279000, 'orig' => 370000, 'img' => '/assets/img/products/tshirt_2.jpg', 'sold' => 860, 'rating' => 4.8, 'rev' => 60, 'featured' => false],
                    ['sku' => 'BS-THUN-10', 'name' => 'Áo Thun Nam Thể Thao Phối Viền Cổ Thoáng Khí Siêu Co Giãn', 'price' => 239000, 'orig' => 320000, 'img' => '/assets/img/products/polo_sport_dry.jpg', 'sold' => 1050, 'rating' => 4.9, 'rev' => 78, 'featured' => true],
                ]
            ],

            // DANH MỤC 6: ÁO THU ĐÔNG NAM (10 sản phẩm)
            [
                'cat' => $catThuDong, 'brand' => $brandSport,
                'items' => [
                    ['sku' => 'BS-THUDONG-01', 'name' => 'Áo Hoodie Nam Nỉ Bông Dày Dặn Có Mũ Trùm Giữ Ấm', 'price' => 450000, 'orig' => 590000, 'img' => '/assets/img/products/hoodie_1.jpg', 'sold' => 1380, 'rating' => 4.9, 'rev' => 105, 'featured' => true],
                    ['sku' => 'BS-THUDONG-02', 'name' => 'Áo Hoodie Nam Phom Rộng Túi Kangaroo Thể Thao', 'price' => 430000, 'orig' => 560000, 'img' => '/assets/img/products/hoodie_2.jpg', 'sold' => 920, 'rating' => 4.8, 'rev' => 68, 'featured' => false],
                    ['sku' => 'BS-THUDONG-03', 'name' => 'Áo Hoodie Nam Màu Be Vải Nỉ Chân Cua 380GSM Ấm Áp', 'price' => 480000, 'orig' => 630000, 'img' => '/assets/img/products/hoodie_beige_fleece.jpg', 'sold' => 840, 'rating' => 5.0, 'rev' => 72, 'featured' => true],
                    ['sku' => 'BS-THUDONG-04', 'name' => 'Áo Hoodie Nam Đen Nhám Phối Dây Mũ Kim Loại Cao Cấp', 'price' => 460000, 'orig' => 600000, 'img' => '/assets/img/products/hoodie_black.jpg', 'sold' => 1150, 'rating' => 4.9, 'rev' => 89, 'featured' => true],
                    ['sku' => 'BS-THUDONG-05', 'name' => 'Áo Hoodie Nam Xám Than Khóa Zip 2 Chiều Tiện Dụng', 'price' => 470000, 'orig' => 620000, 'img' => '/assets/img/products/hoodie_charcoal.jpg', 'sold' => 760, 'rating' => 4.7, 'rev' => 54, 'featured' => false],
                    ['sku' => 'BS-THUDONG-06', 'name' => 'Áo Nỉ Nam Thu Đông Lót Lông Cừu Siêu Ấm Chống Gió Lạnh', 'price' => 520000, 'orig' => 690000, 'img' => '/assets/img/products/hoodie_fleece_grey.jpg', 'sold' => 670, 'rating' => 4.9, 'rev' => 51, 'featured' => true],
                    ['sku' => 'BS-THUDONG-07', 'name' => 'Áo Hoodie Nam Màu Xám Tiêu Dáng Suông Dễ Phối Đồ', 'price' => 440000, 'orig' => 580000, 'img' => '/assets/img/products/hoodie_grey.jpg', 'sold' => 880, 'rating' => 4.8, 'rev' => 63, 'featured' => false],
                    ['sku' => 'BS-THUDONG-08', 'name' => 'Áo Khoác Nỉ Nam Có Mũ Thể Thao Chạy Bộ Mùa Lạnh', 'price' => 460000, 'orig' => 600000, 'img' => '/assets/img/products/hoodie_men.jpg', 'sold' => 790, 'rating' => 4.8, 'rev' => 58, 'featured' => false],
                    ['sku' => 'BS-THUDONG-09', 'name' => 'Áo Hoodie Nam Phong Cách Đường Phố In Họa Tiết Độc Lạ', 'price' => 450000, 'orig' => 590000, 'img' => '/assets/img/products/hoodie_street.jpg', 'sold' => 930, 'rating' => 4.8, 'rev' => 69, 'featured' => false],
                    ['sku' => 'BS-THUDONG-10', 'name' => 'Áo Gió Nam Thu Đông Lót Nỉ 2 Lớp Chống Thấm Nước', 'price' => 550000, 'orig' => 720000, 'img' => '/assets/img/products/windbreaker_men.jpg', 'sold' => 1250, 'rating' => 4.9, 'rev' => 96, 'featured' => true],
                ]
            ],

            // DANH MỤC 7: BỘ SƯU TẬP MÙA HÈ (10 sản phẩm)
            [
                'cat' => $catSummer, 'brand' => $brandUrban,
                'items' => [
                    ['sku' => 'BS-SUMMER-01', 'name' => 'Áo Sơ Mi Nam Đũi Linen Trắng Mùa Hè Thoáng Mát', 'price' => 429000, 'orig' => 550000, 'img' => '/assets/img/products/somi_linen_white.jpg', 'sold' => 1350, 'rating' => 5.0, 'rev' => 98, 'featured' => true],
                    ['sku' => 'BS-SUMMER-02', 'name' => 'Áo Sơ Mi Nam Đũi Be Mộc Đi Biển Phong Cách Tự Nhiên', 'price' => 439000, 'orig' => 560000, 'img' => '/assets/img/products/somi_linen_beige.jpg', 'sold' => 980, 'rating' => 4.9, 'rev' => 72, 'featured' => true],
                    ['sku' => 'BS-SUMMER-03', 'name' => 'Áo Sơ Mi Nam Xanh Biển Cộc Tay Phong Cách Nghỉ Dưỡng', 'price' => 399000, 'orig' => 520000, 'img' => '/assets/img/products/somi_blue.jpg', 'sold' => 870, 'rating' => 4.8, 'rev' => 64, 'featured' => false],
                    ['sku' => 'BS-SUMMER-04', 'name' => 'Áo Polo Nam Mùa Hè Màu Xanh Mint Mát Lạnh Air-Cool', 'price' => 369000, 'orig' => 480000, 'img' => '/assets/img/products/polo_green.jpg', 'sold' => 1140, 'rating' => 4.9, 'rev' => 85, 'featured' => true],
                    ['sku' => 'BS-SUMMER-05', 'name' => 'Áo Polo Nam Xanh Da Trời Thoáng Khí Chống Tia UV', 'price' => 379000, 'orig' => 490000, 'img' => '/assets/img/products/polo_blue.jpg', 'sold' => 920, 'rating' => 4.8, 'rev' => 69, 'featured' => false],
                    ['sku' => 'BS-SUMMER-06', 'name' => 'Áo Polo Nam Màu Kem Be Thanh Lịch Dạo Phố Mùa Hè', 'price' => 389000, 'orig' => 500000, 'img' => '/assets/img/products/polo_cream.jpg', 'sold' => 760, 'rating' => 4.7, 'rev' => 53, 'featured' => false],
                    ['sku' => 'BS-SUMMER-07', 'name' => 'Áo Polo Nam Đỏ Rượu Vang Bo Cổ Phối Năng Động Hè', 'price' => 399000, 'orig' => 510000, 'img' => '/assets/img/products/polo_wine.jpg', 'sold' => 680, 'rating' => 4.8, 'rev' => 49, 'featured' => false],
                    ['sku' => 'BS-SUMMER-08', 'name' => 'Áo Polo Nam Kẻ Sọc Viền Cổ Phong Cách Du Thuyền Hè', 'price' => 419000, 'orig' => 540000, 'img' => '/assets/img/products/polo_striped_collar.jpg', 'sold' => 830, 'rating' => 4.9, 'rev' => 61, 'featured' => true],
                    ['sku' => 'BS-SUMMER-09', 'name' => 'Áo Ba Lỗ Nam Thể Thao Mùa Hè Thoát Nhiệt Tức Thì', 'price' => 199000, 'orig' => 270000, 'img' => '/assets/img/products/tanktop_1.jpg', 'sold' => 1420, 'rating' => 5.0, 'rev' => 118, 'featured' => true],
                    ['sku' => 'BS-SUMMER-10', 'name' => 'Áo Sơ Mi Nam Oxford Cộc Tay Dáng Rộng Năng Động Hè', 'price' => 449000, 'orig' => 580000, 'img' => '/assets/img/products/somi_oxford.jpg', 'sold' => 790, 'rating' => 4.8, 'rev' => 57, 'featured' => false],
                ]
            ],
        ];

        $allCreatedProducts = [];

        foreach ($catalog as $catGroup) {
            $cat = $catGroup['cat'];
            $brd = $catGroup['brand'];

            foreach ($catGroup['items'] as $index => $item) {
                $p = Product::create([
                    'category_id' => $cat->id,
                    'brand_id' => $brd->id,
                    'sku' => $item['sku'],
                    'name' => $item['name'],
                    'slug' => Str::slug($item['name']) . '-' . strtolower($item['sku']),
                    'product_type' => 'variant',
                    'price' => $item['price'],
                    'original_price' => $item['orig'],
                    'stock' => rand(50, 200),
                    'sold_count' => $item['sold'],
                    'rating' => $item['rating'],
                    'reviews_count' => $item['rev'],
                    'image' => $item['img'],
                    'short_description' => "Mẫu {$item['name']} cao cấp từ BeeStyle, chất liệu sợi tự nhiên thoáng mát, co giãn đàn hồi cao, đường may tỉ mỉ.",
                    'description' => "<p><strong>{$item['name']}</strong> là sự lựa chọn hàng đầu cho phái mạnh hiện đại. Được may tỉ mỉ với đường chỉ đôi sắc sảo, chống xù lông và giữ phom dáng chuẩn mực suốt ngày dài.</p>",
                    'colors' => ['Đen', 'Trắng', 'Xanh Navy', 'Xám Ghi'],
                    'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                    'specifications' => [
                        'Chất liệu' => 'Cotton Compact / Sợi Organic tự nhiên cao cấp',
                        'Phom dáng' => 'Slimfit / Regular fit tôn dáng',
                        'Xuất xứ' => 'Việt Nam (Tiêu chuẩn xuất khẩu chất lượng cao)',
                        'Bảo hành' => 'Đổi size miễn phí trong 30 ngày',
                    ],
                    'is_new' => ($index < 3),
                    'is_featured' => $item['featured'],
                    'is_best_seller' => ($item['sold'] > 900),
                    'status' => 'active',
                ]);

                // Lưu ảnh chính vào bảng ProductImage
                ProductImage::create([
                    'product_id' => $p->id,
                    'image_path' => $p->image,
                    'sort_order' => 1,
                ]);

                // Tạo các biến thể màu sắc và kích cỡ (ProductVariants)
                $variantColors = [
                    1 => ['name' => 'Đen', 'hex' => '#111827'],
                    2 => ['name' => 'Trắng', 'hex' => '#ffffff'],
                    3 => ['name' => 'Xanh Navy', 'hex' => '#1e3a8a'],
                    4 => ['name' => 'Xám Ghi', 'hex' => '#64748b'],
                ];
                $variantSizes = ['S', 'M', 'L', 'XL', 'XXL'];

                foreach ($variantColors as $cIdx => $cVal) {
                    foreach ($variantSizes as $sVal) {
                        ProductVariant::create([
                            'product_id' => $p->id,
                            'sku' => "{$p->sku}-C{$cIdx}-{$sVal}",
                            'color' => $cVal['name'],
                            'color_code' => $cVal['hex'],
                            'size' => $sVal,
                            'price' => $p->price,
                            'original_price' => $p->original_price,
                            'stock' => rand(10, 35),
                            'image' => $p->image,
                            'status' => 'active',
                        ]);
                    }
                }

                $allCreatedProducts[] = $p;
            }
        }

        // 5. MÃ GIẢM GIÁ (COUPONS) KHUYẾN MÃI
        Coupon::create([
            'code' => 'BEESTYLE50',
            'title' => 'Giảm 50.000₫ cho đơn hàng áo nam từ 499.000₫',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'min_order_value' => 499000,
            'max_discount_value' => 50000,
            'total_limit' => 500,
            'used_count' => 125,
            'is_active' => true,
            'expires_at' => now()->addMonths(6),
        ]);

        Coupon::create([
            'code' => 'FREESHIPMAX',
            'title' => 'Miễn phí vận chuyển toàn quốc cho đơn từ 300.000₫',
            'discount_type' => 'shipping',
            'discount_value' => 30000,
            'min_order_value' => 300000,
            'max_discount_value' => 30000,
            'total_limit' => 1000,
            'used_count' => 340,
            'is_active' => true,
            'expires_at' => now()->addMonths(12),
        ]);

        Coupon::create([
            'code' => 'VIPBEE15',
            'title' => 'Giảm 15% tổng hóa đơn cho thành viên thân thiết (Tối đa 200k)',
            'discount_type' => 'percent',
            'discount_value' => 15,
            'min_order_value' => 1000000,
            'max_discount_value' => 200000,
            'total_limit' => 200,
            'used_count' => 60,
            'is_active' => true,
            'expires_at' => now()->addMonths(3),
        ]);

        Coupon::create([
            'code' => 'WELCOMEBEE',
            'title' => 'Giảm 30.000₫ cho khách hàng mới mua đơn đầu tiên từ 299.000₫',
            'discount_type' => 'fixed',
            'discount_value' => 30000,
            'min_order_value' => 299000,
            'max_discount_value' => 30000,
            'total_limit' => 500,
            'used_count' => 88,
            'is_active' => true,
            'expires_at' => now()->addMonths(6),
        ]);

        // 6. ĐƠN HÀNG MẪU VỚI CÁC TRẠNG THÁI TIẾN TRÌNH KHÁC NHAU
        $firstProd = $allCreatedProducts[0];
        $secondProd = $allCreatedProducts[10];
        $thirdProd = $allCreatedProducts[20];

        // Đơn 1: Đã hoàn tất (Cho phép đánh giá sản phẩm)
        $order1 = Order::create([
            'user_id' => $customer1->id,
            'order_code' => 'BEE-20260818-1001',
            'customer_name' => $customer1->name,
            'customer_phone' => $customer1->phone,
            'customer_email' => $customer1->email,
            'shipping_address' => $customer1->address,
            'city' => $customer1->city,
            'district' => $customer1->district,
            'total_amount' => $firstProd->price + $secondProd->price,
            'subtotal' => $firstProd->price + $secondProd->price,
            'shipping_fee' => 0,
            'discount_amount' => 0,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'shipping_status' => 'completed',
            'status_step' => 6,
            'notes' => 'Giao giờ hành chính, gọi trước khi giao.',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $firstProd->id,
            'product_name' => $firstProd->name,
            'product_sku' => $firstProd->sku,
            'image' => $firstProd->image,
            'price' => $firstProd->price,
            'quantity' => 1,
            'subtotal' => $firstProd->price,
            'color' => 'Xanh Navy',
            'size' => 'L',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $secondProd->id,
            'product_name' => $secondProd->name,
            'product_sku' => $secondProd->sku,
            'image' => $secondProd->image,
            'price' => $secondProd->price,
            'quantity' => 1,
            'subtotal' => $secondProd->price,
            'color' => 'Trắng',
            'size' => 'XL',
        ]);

        // Đơn 2: Đang vận chuyển
        $order2 = Order::create([
            'user_id' => $customer1->id,
            'order_code' => 'BEE-20260819-1002',
            'customer_name' => $customer1->name,
            'customer_phone' => $customer1->phone,
            'customer_email' => $customer1->email,
            'shipping_address' => $customer1->address,
            'city' => $customer1->city,
            'district' => $customer1->district,
            'total_amount' => $thirdProd->price,
            'subtotal' => $thirdProd->price,
            'shipping_fee' => 0,
            'discount_amount' => 0,
            'payment_method' => 'vietqr',
            'payment_status' => 'paid',
            'shipping_status' => 'shipping',
            'status_step' => 4,
            'notes' => 'Đã thanh toán chuyển khoản qua VietQR.',
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $thirdProd->id,
            'product_name' => $thirdProd->name,
            'product_sku' => $thirdProd->sku,
            'image' => $thirdProd->image,
            'price' => $thirdProd->price,
            'quantity' => 1,
            'subtotal' => $thirdProd->price,
            'color' => 'Đen',
            'size' => 'M',
        ]);

        // 7. ĐÁNH GIÁ MẪU CỦA KHÁCH HÀNG
        Review::create([
            'product_id' => $firstProd->id,
            'user_id' => $customer1->id,
            'user_name' => $customer1->name,
            'rating' => 5,
            'comment' => 'Áo polo mặc cực kỳ êm và đứng phom, vải dệt tổ ong thoáng mát không bị nhăn sau khi giặt máy. Rất đáng tiền!',
            'status' => 'approved',
            'created_at' => now()->subHours(8),
        ]);

        Review::create([
            'product_id' => $secondProd->id,
            'user_id' => $customer1->id,
            'user_name' => $customer1->name,
            'rating' => 5,
            'comment' => 'Sơ mi lụa trắng rất đẹp và sáng màu, mặc đi họp đối tác cực kỳ tự tin và lịch thiệp.',
            'status' => 'approved',
            'created_at' => now()->subHours(4),
        ]);
    }
}