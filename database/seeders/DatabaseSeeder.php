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

class DatabaseSeeder extends Seeder
{
    /**
     * Khởi tạo dữ liệu mẫu cho cơ sở dữ liệu hệ thống BeeStyle.
     */
    public function run(): void
    {
        // 1. TÀI KHOẢN QUẢN TRỊ VIÊN (ADMIN) & KHÁCH HÀNG MẪU
        $admin = User::create([
            'name' => 'Quản Trị Viên BeeStyle',
            'email' => 'admin@beestyle.com',
            'phone' => '0901234567',
            'gender' => 'Nam',
            'dob' => '1992-05-15',
            'role' => 'admin',
            'rank' => 'Admin Quản Trị',
            'points' => 9999,
            'total_spent' => 0,
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/57.webp',
            'bank_name' => 'Vietcombank',
            'bank_account_number' => '0071001234567',
            'bank_account_name' => 'QUAN TRI VIEN BEESTYLE',
            'bank_branch' => 'Chi nhánh TP. Hồ Chí Minh',
            'password' => Hash::make('password'),
            'password_changed_at' => now(),
        ]);

        $customer1 = User::create([
            'name' => 'Nguyễn Văn Hùng',
            'email' => 'hung.nguyen@gmail.com',
            'phone' => '0987654321',
            'gender' => 'Nam',
            'dob' => '1995-10-20',
            'role' => 'customer',
            'rank' => 'Thành viên Bạc (Silver)',
            'points' => 1250,
            'total_spent' => 4580000,
            'address' => 'Số 45 Đường Lê Duẩn, Phường Bến Nghé, Quận 1',
            'city' => 'Hồ Chí Minh',
            'district' => 'Quận 1',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/58.webp',
            'bank_name' => 'Techcombank',
            'bank_account_number' => '19034567890012',
            'bank_account_name' => 'NGUYEN VAN HUNG',
            'bank_branch' => 'Chi nhánh Sài Gòn',
            'password' => Hash::make('password'),
            'password_changed_at' => now()->subDays(10),
        ]);

        $customer2 = User::create([
            'name' => 'Lê Hoàng Long',
            'email' => 'hoanglong.le@gmail.com',
            'phone' => '0903888999',
            'gender' => 'Nam',
            'dob' => '1990-03-12',
            'role' => 'customer',
            'rank' => 'Thành viên Vàng (Gold)',
            'points' => 3800,
            'total_spent' => 8950000,
            'address' => '24 Nguyễn Văn Linh, Phường Nam Dương',
            'city' => 'Đà Nẵng',
            'district' => 'Hải Châu',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/30.webp',
            'bank_name' => 'MB Bank (Quân Đội)',
            'bank_account_number' => '0903888999',
            'bank_account_name' => 'LE HOANG LONG',
            'bank_branch' => 'Chi nhánh Đà Nẵng',
            'password' => Hash::make('password'),
            'password_changed_at' => now()->subDays(30),
        ]);

        // Sổ địa chỉ giao hàng mẫu cho khách hàng Nguyễn Văn Hùng
        UserAddress::create([
            'user_id' => $customer1->id,
            'recipient_name' => 'Nguyễn Văn Hùng',
            'phone' => '0987654321',
            'city' => 'Hồ Chí Minh',
            'district' => 'Quận 1',
            'ward' => 'Phường Bến Nghé',
            'address' => 'Số 45 Đường Lê Duẩn',
            'label' => 'Nhà riêng',
            'is_default' => true,
            'notes' => 'Giao giờ hành chính hoặc gọi trước khi giao',
        ]);

        UserAddress::create([
            'user_id' => $customer1->id,
            'recipient_name' => 'Nguyễn Văn Hùng (Văn phòng)',
            'phone' => '0987654321',
            'city' => 'Hồ Chí Minh',
            'district' => 'Quận 3',
            'ward' => 'Phường Võ Thị Sáu',
            'address' => 'Tòa nhà Bitexco Nam Long, 63 Hai Bà Trưng, Tầng 8',
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

        // 3. CÂY DANH MỤC PHÂN CẤP (CHA - CON)
        // Danh mục Cha 1: Áo Nam
        $parentTop = Category::create([
            'name' => 'Áo Nam Thời Trang',
            'slug' => 'ao-nam-thoi-trang',
            'icon' => 'fa-solid fa-shirt',
            'image' => '/assets/img/products/1.png',
            'description' => 'Bộ sưu tập áo nam cao cấp đa dạng từ áo polo, áo sơ mi đến áo khoác gió & blazer',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catPolo = Category::create([
            'parent_id' => $parentTop->id,
            'name' => 'Áo Polo Nam',
            'slug' => 'ao-polo-nam',
            'icon' => 'fa-solid fa-shirt',
            'image' => '/assets/img/products/1.png',
            'description' => 'Áo polo cotton dệt tổ ong thoáng khí, co giãn 4 chiều chuẩn phom',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catShirt = Category::create([
            'parent_id' => $parentTop->id,
            'name' => 'Áo Sơ Mi Công Sở',
            'slug' => 'ao-so-mi-cong-so',
            'icon' => 'fa-solid fa-user-tie',
            'image' => '/assets/img/products/4.png',
            'description' => 'Sơ mi lụa kháng nhăn cao cấp, phom slimfit tôn dáng tôn da nơi công sở',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catBlazer = Category::create([
            'parent_id' => $parentTop->id,
            'name' => 'Áo Khoác & Blazer Nam',
            'slug' => 'ao-khoac-blazer-nam',
            'icon' => 'fa-solid fa-vest',
            'image' => '/assets/img/products/2.png',
            'description' => 'Blazer phong cách Hàn Quốc tối giản & áo khoác gió trượt nước 2 lớp thời thượng',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Danh mục Cha 2: Quần Nam
        $parentBottom = Category::create([
            'name' => 'Quần Nam Cao Cấp',
            'slug' => 'quan-nam-cao-cap',
            'icon' => 'fa-solid fa-tags',
            'image' => '/assets/img/products/5.png',
            'description' => 'Tổng hợp các mẫu quần tây, quần kaki, quần short co giãn cao cấp',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catPants = Category::create([
            'parent_id' => $parentBottom->id,
            'name' => 'Quần Âu & Quần Kaki',
            'slug' => 'quan-au-kaki-nam',
            'icon' => 'fa-solid fa-tags',
            'image' => '/assets/img/products/5.png',
            'description' => 'Quần tây cạp âu thông minh tăng giảm 4cm, chất vải chéo Ý không nhăn',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catShorts = Category::create([
            'parent_id' => $parentBottom->id,
            'name' => 'Quần Short & Thể Thao',
            'slug' => 'quan-short-the-thao',
            'icon' => 'fa-solid fa-person-running',
            'image' => '/assets/img/products/6.png',
            'description' => 'Quần short đũi thoáng mát & quần đùi thể thao dạo phố trẻ trung',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Danh mục Cha 3: Phụ Kiện Nam
        $parentAccessories = Category::create([
            'name' => 'Giày & Phụ Kiện Quý Ông',
            'slug' => 'giay-phu-kien-quy-ong',
            'icon' => 'fa-solid fa-glasses',
            'image' => '/assets/img/products/3.png',
            'description' => 'Giày da bò nguyên tấm, thắt lưng da, ví da và phụ kiện đẳng cấp',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catShoes = Category::create([
            'parent_id' => $parentAccessories->id,
            'name' => 'Giày Da & Loafer Nam',
            'slug' => 'giay-da-loafer-nam',
            'icon' => 'fa-solid fa-shoe-prints',
            'image' => '/assets/img/products/3.png',
            'description' => 'Giày tây Oxford & Loafer da bò nhập khẩu đế cao su êm ái chống trơn',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 4. SẢN PHẨM THỜI TRANG, THÔNG SỐ KỸ THUẬT VÀ BIẾN THỂ (MÀU/SIZE/GIÁ)
        $p1 = Product::create([
            'category_id' => $catPolo->id,
            'brand_id' => $brandSignature->id,
            'sku' => 'BS-POLO-01',
            'name' => 'Áo Polo Nam Cotton Dệt Tổ Ong Phom Slimfit BeeStyle',
            'slug' => 'ao-polo-nam-cotton-det-to-ong-phom-slimfit-beestyle',
            'product_type' => 'variant',
            'price' => 389000,
            'original_price' => 499000,
            'stock' => 150,
            'sold_count' => 1240,
            'rating' => 4.9,
            'reviews_count' => 86,
            'image' => '/assets/img/products/1.png',
            'short_description' => 'Chất liệu 95% Cotton Organic dệt kiểu tổ ong (Pique Waffle) cao cấp, thấm hút mồ hôi vượt trội, co giãn 4 chiều.',
            'description' => '<p>Áo Polo BeeStyle Signature là sự kết hợp hoàn hảo giữa phong cách thanh lịch công sở và sự năng động thường nhật. Được dệt từ sợi bông Organic cao cấp nhập khẩu, bề mặt vải tổ ong thoáng khí giúp bạn luôn tự tin và dễ chịu suốt cả ngày dài.</p><h6>Đặc điểm nổi bật:</h6><ul><li>Sợi Cotton dệt kim kháng khuẩn, chống tia UV hiệu quả.</li><li>Cổ áo bo dệt chống quăn góc sau nhiều lần giặt.</li><li>Logo ong kim loại mạ vàng sang trọng đính ngực áo.</li></ul>',
            'colors' => ['Xanh Navy Đậm', 'Trắng Tinh Khôi', 'Đen Sang Trọng', 'Beige Thanh Lịch'],
            'sizes' => ['M (55-65kg)', 'L (65-75kg)', 'XL (75-85kg)', 'XXL (85-95kg)'],
            'specifications' => [
                'Chất liệu' => '95% Cotton Organic Pique, 5% Spandex co giãn',
                'Phom dáng' => 'Slimfit vừa vặn tôn dáng',
                'Kiểu cổ áo' => 'Cổ bẻ bo dệt cao cấp 3 cúc',
                'Họa tiết' => 'Dệt tổ ong vi mô (Micro Waffle)',
                'Xuất xứ' => 'Việt Nam (Gia công tiêu chuẩn xuất khẩu)',
                'Hướng dẫn giặt' => 'Giặt máy chế độ nhẹ, không dùng chất tẩy mạnh, ủi ở nhiệt độ dưới 150°C',
            ],
            'is_new' => true,
            'is_featured' => true,
            'is_best_seller' => true,
            'status' => 'active',
        ]);

        // Danh sách biến thể (Màu sắc / Kích thước) cho Sản phẩm 1
        $colorsP1 = [
            ['name' => 'Xanh Navy Đậm', 'hex' => '#1e3a8a', 'price' => 389000],
            ['name' => 'Trắng Tinh Khôi', 'hex' => '#f8fafc', 'price' => 389000],
            ['name' => 'Đen Sang Trọng', 'hex' => '#0f172a', 'price' => 389000],
            ['name' => 'Beige Thanh Lịch', 'hex' => '#d4c5b9', 'price' => 399000],
        ];
        $sizesP1 = ['M (55-65kg)', 'L (65-75kg)', 'XL (75-85kg)', 'XXL (85-95kg)'];
        foreach ($colorsP1 as $cIndex => $col) {
            foreach ($sizesP1 as $sIndex => $sz) {
                ProductVariant::create([
                    'product_id' => $p1->id,
                    'sku' => "BS-POLO-01-" . ($cIndex + 1) . "-" . substr($sz, 0, 2),
                    'color' => $col['name'],
                    'color_code' => $col['hex'],
                    'size' => $sz,
                    'price' => $col['price'],
                    'original_price' => 499000,
                    'stock' => rand(15, 45),
                    'image' => '/assets/img/products/1.png',
                    'status' => 'active',
                ]);
            }
        }

        $p2 = Product::create([
            'category_id' => $catShirt->id,
            'brand_id' => $brandLuxury->id,
            'sku' => 'BS-SHIRT-02',
            'name' => 'Áo Sơ Mi Nam Lụa Kháng Nhăn Easy-Iron Luxury BeeStyle',
            'slug' => 'ao-so-mi-nam-lua-khang-nhan-easy-iron-luxury-beestyle',
            'product_type' => 'variant',
            'price' => 499000,
            'original_price' => 650000,
            'stock' => 120,
            'sold_count' => 950,
            'rating' => 4.8,
            'reviews_count' => 54,
            'image' => '/assets/img/products/4.png',
            'short_description' => 'Vải sợi tre Modal kết hợp lụa chống nhăn 100%, bề mặt sáng bóng mịn màng, thiết kế cổ bẻ chuẩn quý ông.',
            'description' => '<p>Mẫu sơ mi công sở cao cấp Bee Luxury Line mang đến diện mạo chỉn chu, đĩnh đạc cho các cuộc họp và sự kiện quan trọng. Công nghệ dệt chống nhăn thông minh giúp tiết kiệm thời gian là ủi mỗi sáng.</p>',
            'colors' => ['Trắng Tinh', 'Xanh Pastel', 'Xám Khói'],
            'sizes' => ['38 (S)', '39 (M)', '40 (L)', '41 (XL)', '42 (XXL)'],
            'specifications' => [
                'Chất liệu' => '70% Sợi tre Bamboo, 30% Silk lụa kháng nhăn',
                'Phom dáng' => 'Regular fit thoải mái',
                'Kiểu cổ áo' => 'Cổ áo nhọn Classic Spread',
                'Họa tiết' => 'Trơn bóng nhẹ sang trọng',
                'Xuất xứ' => 'Việt Nam',
                'Hướng dẫn giặt' => 'Nên giặt tay hoặc giặt máy túi giặt, phơi nơi râm mát',
            ],
            'is_new' => true,
            'is_featured' => true,
            'is_best_seller' => true,
            'status' => 'active',
        ]);

        $colorsP2 = [
            ['name' => 'Trắng Tinh', 'hex' => '#ffffff', 'price' => 499000],
            ['name' => 'Xanh Pastel', 'hex' => '#93c5fd', 'price' => 499000],
            ['name' => 'Xám Khói', 'hex' => '#94a3b8', 'price' => 520000],
        ];
        $sizesP2 = ['38 (S)', '39 (M)', '40 (L)', '41 (XL)', '42 (XXL)'];
        foreach ($colorsP2 as $cIndex => $col) {
            foreach ($sizesP2 as $sIndex => $sz) {
                ProductVariant::create([
                    'product_id' => $p2->id,
                    'sku' => "BS-SHIRT-02-" . ($cIndex + 1) . "-S" . substr($sz, 0, 2),
                    'color' => $col['name'],
                    'color_code' => $col['hex'],
                    'size' => $sz,
                    'price' => $col['price'],
                    'original_price' => 650000,
                    'stock' => rand(10, 30),
                    'image' => '/assets/img/products/4.png',
                    'status' => 'active',
                ]);
            }
        }

        $p3 = Product::create([
            'category_id' => $catBlazer->id,
            'brand_id' => $brandLuxury->id,
            'sku' => 'BS-BLAZER-03',
            'name' => 'Áo Blazer Nam Phong Cách Hàn Quốc 2 Lớp Form Regular Fit',
            'slug' => 'ao-blazer-nam-phong-cach-han-quoc-2-lop-form-regular-fit',
            'product_type' => 'variant',
            'price' => 899000,
            'original_price' => 1250000,
            'stock' => 60,
            'sold_count' => 430,
            'rating' => 5.0,
            'reviews_count' => 38,
            'image' => '/assets/img/products/2.png',
            'short_description' => 'Blazer nam 2 lớp chuẩn form Hàn Quốc, độn vai tự nhiên định hình phom dáng quý ông hiện đại.',
            'description' => '<p>Áo Blazer BeeStyle thiết kế 2 khuy cài cổ điển, lớp lót lụa cao cấp êm ái thoáng mát không gây bí bách khi mặc cả ngày.</p>',
            'colors' => ['Đen Nhám', 'Xám Ghi', 'Nâu Tây'],
            'sizes' => ['M', 'L', 'XL', 'XXL'],
            'specifications' => [
                'Chất liệu' => 'Vải Tweed dạ dệt kim cao cấp, lót lụa Habutai',
                'Phom dáng' => 'Regular fit có đệm vai nhẹ',
                'Kiểu áo' => 'Blazer 2 khuy cài, xẻ tà sau đôi',
                'Xuất xứ' => 'Việt Nam',
            ],
            'is_new' => true,
            'is_featured' => true,
            'is_best_seller' => false,
            'status' => 'active',
        ]);

        foreach (['Đen Nhám' => '#18181b', 'Xám Ghi' => '#64748b', 'Nâu Tây' => '#78350f'] as $cName => $cHex) {
            foreach (['M', 'L', 'XL', 'XXL'] as $sz) {
                ProductVariant::create([
                    'product_id' => $p3->id,
                    'sku' => "BS-BLAZER-03-" . substr($cName, 0, 2) . "-{$sz}",
                    'color' => $cName,
                    'color_code' => $cHex,
                    'size' => $sz,
                    'price' => 899000,
                    'original_price' => 1250000,
                    'stock' => rand(5, 20),
                    'image' => '/assets/img/products/2.png',
                    'status' => 'active',
                ]);
            }
        }

        $p4 = Product::create([
            'category_id' => $catPants->id,
            'brand_id' => $brandSignature->id,
            'sku' => 'BS-PANT-04',
            'name' => 'Quần Âu Nam Cạp Tăng Giảm Thông Minh Vải Chéo Ý Co Giãn',
            'slug' => 'quan-au-nam-cap-tang-giam-thong-minh-vai-cheo-y-co-gian',
            'product_type' => 'variant',
            'price' => 450000,
            'original_price' => 590000,
            'stock' => 180,
            'sold_count' => 1520,
            'rating' => 4.9,
            'reviews_count' => 120,
            'image' => '/assets/img/products/5.png',
            'short_description' => 'Thiết kế cạp quần ẩn tăng giảm co giãn tự động 4cm, tiện lợi tối đa khi ăn no hay vận động.',
            'description' => '<p>Quần âu BeeStyle cạp chun sườn thông minh, chất vải chéo Ý đứng dáng, không bám bụi và chống xù lông tuyệt đối.</p>',
            'colors' => ['Đen', 'Xanh Than', 'Ghi Xám', 'Be'],
            'sizes' => ['29', '30', '31', '32', '33', '34'],
            'specifications' => [
                'Chất liệu' => 'Vải chéo Ý 80% Polyester, 18% Rayon, 2% Spandex',
                'Phom dáng' => 'Slim cropped ôm vừa phải tôn chiều cao',
                'Cạp quần' => 'Cạp tăng giảm thông minh 4cm',
                'Xuất xứ' => 'Việt Nam',
            ],
            'is_new' => false,
            'is_featured' => true,
            'is_best_seller' => true,
            'status' => 'active',
        ]);

        foreach (['Đen' => '#000000', 'Xanh Than' => '#1e293b', 'Ghi Xám' => '#475569', 'Be' => '#d6d3d1'] as $cName => $cHex) {
            foreach (['29', '30', '31', '32', '33', '34'] as $sz) {
                ProductVariant::create([
                    'product_id' => $p4->id,
                    'sku' => "BS-PANT-04-" . substr($cName, 0, 2) . "-{$sz}",
                    'color' => $cName,
                    'color_code' => $cHex,
                    'size' => "Size {$sz}",
                    'price' => 450000,
                    'original_price' => 590000,
                    'stock' => rand(15, 40),
                    'image' => '/assets/img/products/5.png',
                    'status' => 'active',
                ]);
            }
        }

        $p5 = Product::create([
            'category_id' => $catShoes->id,
            'brand_id' => $brandLuxury->id,
            'sku' => 'BS-SHOE-05',
            'name' => 'Giày Tây Nam Loafer Da Bò Ý Dập Họa Tiết Quý Tộc BeeStyle',
            'slug' => 'giay-tay-nam-loafer-da-bo-y-dap-hoa-tiet-quy-toc-beestyle',
            'product_type' => 'variant',
            'price' => 1250000,
            'original_price' => 1800000,
            'stock' => 45,
            'sold_count' => 310,
            'rating' => 5.0,
            'reviews_count' => 29,
            'image' => '/assets/img/products/3.png',
            'short_description' => 'Da bò nguyên tấm Full-grain nhập khẩu Ý, đệm lót memory foam êm chân, đế cao su khâu viền chắc chắn.',
            'description' => '<p>Giày Loafer da bò thật 100% đánh màu thủ công Patina sang trọng, thiết kế ôm chân và chống mỏi tối đa.</p>',
            'colors' => ['Đen Bóng Derby', 'Nâu Hạt Dẻ Patina'],
            'sizes' => ['39', '40', '41', '42', '43'],
            'specifications' => [
                'Chất liệu' => 'Da bò tự nhiên Full-grain loại 1',
                'Đế giày' => 'Đế cao su nhiệt dẻo khâu chỉ dù chống trơn',
                'Lót trong' => 'Da cừu khử mùi êm ái',
                'Xuất xứ' => 'Việt Nam (Thủ công mỹ nghệ da)',
            ],
            'is_new' => true,
            'is_featured' => true,
            'is_best_seller' => false,
            'status' => 'active',
        ]);

        foreach (['Đen Bóng Derby' => '#111827', 'Nâu Hạt Dẻ Patina' => '#5c2c16'] as $cName => $cHex) {
            foreach (['39', '40', '41', '42', '43'] as $sz) {
                ProductVariant::create([
                    'product_id' => $p5->id,
                    'sku' => "BS-SHOE-05-" . substr($cName, 0, 2) . "-{$sz}",
                    'color' => $cName,
                    'color_code' => $cHex,
                    'size' => "Size {$sz}",
                    'price' => 1250000,
                    'original_price' => 1800000,
                    'stock' => rand(5, 15),
                    'image' => '/assets/img/products/3.png',
                    'status' => 'active',
                ]);
            }
        }

        $p6 = Product::create([
            'category_id' => $catShorts->id,
            'brand_id' => $brandUrban->id,
            'sku' => 'BS-SHORT-06',
            'name' => 'Quần Short Kaki Nam Co Giãn 4 Chiều Phong Cách Dạo Phố',
            'slug' => 'quan-short-kaki-nam-co-gian-4-chieu-phong-cach-dao-pho',
            'product_type' => 'variant',
            'price' => 289000,
            'original_price' => 380000,
            'stock' => 110,
            'sold_count' => 780,
            'rating' => 4.7,
            'reviews_count' => 45,
            'image' => '/assets/img/products/6.png',
            'short_description' => 'Quần short kaki đùi dài ngang gối, chất liệu mềm mại, cạp chun thoải mái.',
            'description' => '<p>Mẫu short kaki thích hợp cho các chuyến du lịch, dạo phố hoặc thể thao cuối tuần cùng bạn bè.</p>',
            'colors' => ['Xanh Rêu', 'Đen', 'Be Sáng'],
            'sizes' => ['M (29-30)', 'L (31-32)', 'XL (33-34)'],
            'specifications' => [
                'Chất liệu' => 'Kaki Cotton 98%, 2% Spandex',
                'Phom dáng' => 'Ngang gối trẻ trung',
                'Xuất xứ' => 'Việt Nam',
            ],
            'is_new' => false,
            'is_featured' => false,
            'is_best_seller' => true,
            'status' => 'active',
        ]);

        // 5. MÃ GIẢM GIÁ (COUPONS) KHUYẾN MÃI VÀ VẬN CHUYỂN
        Coupon::create([
            'code' => 'BEESTYLEVIP',
            'title' => 'Giảm 50.000₫ cho đơn hàng từ 499.000₫',
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
            'code' => 'FREESHIP',
            'title' => 'Miễn phí vận chuyển toàn quốc cho đơn từ 299.000₫',
            'discount_type' => 'shipping',
            'discount_value' => 30000,
            'min_order_value' => 299000,
            'max_discount_value' => 30000,
            'total_limit' => 1000,
            'used_count' => 340,
            'is_active' => true,
            'expires_at' => now()->addMonths(12),
        ]);

        Coupon::create([
            'code' => 'GIAM10',
            'title' => 'Giảm 10% tổng giá trị đơn hàng từ 1.000.000₫',
            'discount_type' => 'percent',
            'discount_value' => 10,
            'min_order_value' => 1000000,
            'max_discount_value' => 200000,
            'total_limit' => 200,
            'used_count' => 60,
            'is_active' => true,
            'expires_at' => now()->addMonths(3),
        ]);

        // 6. ĐƠN HÀNG MẪU VỚI CÁC TRẠNG THÁI GIAO HÀNG KHÁC NHAU
        $order1 = Order::create([
            'user_id' => $customer1->id,
            'order_code' => 'BS-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'customer_name' => $customer1->name,
            'customer_phone' => $customer1->phone,
            'customer_email' => $customer1->email,
            'shipping_address' => 'Số 45 Đường Lê Duẩn, Phường Bến Nghé, Quận 1',
            'city' => 'Hồ Chí Minh',
            'district' => 'Quận 1',
            'total_amount' => 839000,
            'subtotal' => 839000,
            'shipping_fee' => 0,
            'discount_amount' => 0,
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'shipping_status' => 'completed',
            'status_step' => 6,
            'notes' => 'Giao hàng giờ hành chính',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'product_name' => $p1->name,
            'product_sku' => 'BS-POLO-01',
            'image' => $p1->image,
            'price' => $p1->price,
            'quantity' => 1,
            'subtotal' => 389000,
            'color' => 'Xanh Navy Đậm',
            'size' => 'L (65-75kg)',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p4->id,
            'product_name' => $p4->name,
            'product_sku' => 'BS-PANT-04',
            'image' => $p4->image,
            'price' => $p4->price,
            'quantity' => 1,
            'subtotal' => 450000,
            'color' => 'Đen',
            'size' => 'Size 31',
        ]);

        // 7. ĐÁNH GIÁ & PHẢN HỒI MẪU TỪ KHÁCH HÀNG
        Review::create([
            'product_id' => $p1->id,
            'user_id' => $customer1->id,
            'user_name' => $customer1->name,
            'rating' => 5,
            'comment' => 'Áo polo mặc cực kỳ êm và đứng phom, vải dệt tổ ong thoáng mát không bị nhăn sau khi giặt máy. Rất đáng tiền!',
            'status' => 'approved',
        ]);
    }
}
