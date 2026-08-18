<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. ADMIN & CUSTOMER USERS
        $admin = User::create([
            'name' => 'Quản Trị Viên BeeStyle',
            'email' => 'admin@beestyle.com',
            'phone' => '0901234567',
            'role' => 'admin',
            'rank' => 'Admin Quản Trị',
            'points' => 9999,
            'total_spent' => 0,
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/57.webp',
            'password' => Hash::make('password'),
        ]);

        $customer1 = User::create([
            'name' => 'Nguyễn Văn Hùng',
            'email' => 'hung.nguyen@gmail.com',
            'phone' => '0987654321',
            'role' => 'customer',
            'rank' => 'Thành viên Bạc (Silver)',
            'points' => 1250,
            'total_spent' => 4580000,
            'address' => 'Số 45 Đường Lê Duẩn, Phường Bến Nghé, Quận 1',
            'city' => 'Hồ Chí Minh',
            'district' => 'Quận 1',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/58.webp',
            'password' => Hash::make('password'),
        ]);

        $customer2 = User::create([
            'name' => 'Lê Hoàng Long',
            'email' => 'hoanglong.le@gmail.com',
            'phone' => '0903888999',
            'role' => 'customer',
            'rank' => 'Thành viên Vàng (Gold)',
            'points' => 3800,
            'total_spent' => 8950000,
            'address' => '24 Nguyễn Văn Linh, Phường Nam Dương',
            'city' => 'Đà Nẵng',
            'district' => 'Hải Châu',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/30.webp',
            'password' => Hash::make('password'),
        ]);

        $customer3 = User::create([
            'name' => 'Trần Minh Quang',
            'email' => 'minhquang.tran@gmail.com',
            'phone' => '0912345678',
            'role' => 'customer',
            'rank' => 'Thành viên Mới',
            'points' => 200,
            'total_spent' => 940000,
            'address' => 'Số 12 Ngõ 88 Phố Láng Hạ, Phường Láng Hạ',
            'city' => 'Hà Nội',
            'district' => 'Đống Đa',
            'status' => 'active',
            'avatar' => '/assets/img/team/40x40/59.webp',
            'password' => Hash::make('password'),
        ]);

        // 2. CATEGORIES (Danh mục thời trang nam BeeStyle)
        $catPolo = Category::create([
            'name' => 'Áo Polo & T-Shirt Nam',
            'slug' => 'ao-polo-tshirt-nam',
            'icon' => 'fa-solid fa-shirt',
            'image' => '/assets/img/products/1.png',
            'description' => 'Áo polo cotton tổ ong dệt kim cao cấp, co giãn 4 chiều, chuẩn phom lịch lãm cho phái mạnh',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catShirt = Category::create([
            'name' => 'Áo Sơ Mi Nam Công Sở',
            'slug' => 'ao-so-mi-nam-cong-so',
            'icon' => 'fa-solid fa-user-tie',
            'image' => '/assets/img/products/4.png',
            'description' => 'Sơ mi lụa kháng nhăn cao cấp, phom slimfit tôn dáng tôn da nơi công sở và sự kiện',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catBlazer = Category::create([
            'name' => 'Áo Khoác & Blazer Nam',
            'slug' => 'ao-khoac-blazer',
            'icon' => 'fa-solid fa-vest',
            'image' => '/assets/img/products/2.png',
            'description' => 'Blazer phong cách Hàn Quốc tối giản & áo khoác gió trượt nước 2 lớp thời thượng',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catPants = Category::create([
            'name' => 'Quần Âu & Quần Kaki Nam',
            'slug' => 'quan-au-kaki-nam',
            'icon' => 'fa-solid fa-tags',
            'image' => '/assets/img/products/5.png',
            'description' => 'Quần âu tuyết mưa cạp tăng đơ thông minh và quần kaki co giãn năng động',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $catShoes = Category::create([
            'name' => 'Giày Da & Loafer Nam',
            'slug' => 'giay-da-loafer-nam',
            'icon' => 'fa-solid fa-shoe-prints',
            'image' => '/assets/img/products/6.png',
            'description' => 'Giày da Oxford, Derby da bò Ý nhập khẩu và Loafer trẻ trung êm chân',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $catAccessories = Category::create([
            'name' => 'Thắt Lưng & Phụ Kiện Nam',
            'slug' => 'that-lung-phu-kien-nam',
            'icon' => 'fa-solid fa-bag-shopping',
            'image' => '/assets/img/products/7.png',
            'description' => 'Thắt lưng da bò nguyên tấm, ví da khóa mạ vàng 18K và cà vạt lụa sang trọng',
            'sort_order' => 6,
            'is_active' => true,
        ]);

        // 3. PRODUCTS (Sản phẩm áo và thời trang nam BeeStyle)
        $p1 = Product::create([
            'category_id' => $catPolo->id,
            'sku' => 'BS-PL-001',
            'name' => 'Áo Polo Nam BeeStyle Premium Cotton Dệt Tổ Ong Kháng Khuẩn',
            'slug' => 'ao-polo-nam-beestyle-premium-cotton-det-to-ong',
            'price' => 389000,
            'original_price' => 499000,
            'stock' => 145,
            'sold_count' => 850,
            'rating' => 4.9,
            'reviews_count' => 128,
            'image' => '/assets/img/products/1.png',
            'colors' => ['Đen', 'Trắng', 'Xanh Navy', 'Beige'],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'short_description' => 'Chất liệu 100% Cotton dệt tổ ong cao cấp thoáng khí, co giãn 4 chiều mềm mại, phom regular tôn dáng lịch thiệp.',
            'description' => 'Áo Polo BeeStyle Premium mang phong cách tối giản thanh lịch. Cổ bẻ dệt bo sợi microfiber giữ phom sau 100+ lần giặt. Nẹp cổ 3 nút vỏ trai khắc laser tinh xảo, đường may chuẩn may đo xuất khẩu Châu Âu.',
            'is_new' => true,
            'is_featured' => true,
            'is_best_seller' => true,
            'status' => 'active',
        ]);

        $p2 = Product::create([
            'category_id' => $catBlazer->id,
            'sku' => 'BS-BLZ-002',
            'name' => 'Áo Blazer Nam Form Suông Rộng Phong Cách Hàn Quốc Minimalist',
            'slug' => 'ao-blazer-nam-form-suong-phong-cach-han-quoc',
            'price' => 890000,
            'original_price' => 1150000,
            'stock' => 52,
            'sold_count' => 430,
            'rating' => 4.8,
            'reviews_count' => 96,
            'image' => '/assets/img/products/2.png',
            'colors' => ['Đen', 'Xám Tro', 'Nâu Cafe'],
            'sizes' => ['M', 'L', 'XL'],
            'short_description' => 'Vải chéo Hàn 2 lớp đứng dáng không nhăn, đệm vai tự nhiên tôn phom quý ông hiện đại.',
            'description' => 'Blazer BeeStyle Minimalist thiết kế 2 hàng khuy tinh tế, lớp lót lụa habutai êm ái chống tĩnh điện. Dễ dàng mix cùng áo phông trắng hoặc sơ mi đi tiệc, đi làm, sự kiện.',
            'is_new' => true,
            'is_featured' => true,
            'is_best_seller' => false,
            'status' => 'active',
        ]);

        $p3 = Product::create([
            'category_id' => $catShirt->id,
            'sku' => 'BS-SH-003',
            'name' => 'Áo Sơ Mi Nam Tay Dài Vải Sợi Tre Bamboo Kháng Nhăn Tuyệt Đối',
            'slug' => 'ao-so-mi-nam-tay-dai-soi-tre-bamboo-khang-nhan',
            'price' => 450000,
            'original_price' => 590000,
            'stock' => 110,
            'sold_count' => 620,
            'rating' => 4.9,
            'reviews_count' => 74,
            'image' => '/assets/img/products/4.png',
            'colors' => ['Trắng Sữa', 'Xanh Nhạt', 'Xanh Navy', 'Ghi Sáng'],
            'sizes' => ['38', '39', '40', '41', '42'],
            'short_description' => 'Vải Bamboo dệt từ sợi tre thiên nhiên siêu mịn mát, thấm hút mồ hôi 60% hơn cotton thường, tự phẳng sau khi phơi.',
            'description' => 'Sơ mi Bamboo BeeStyle với phom dáng Slimfit vừa vặn ôm nhẹ cơ thể, nẹp áo giấu chỉ may cao cấp, cổ áo có nẹp xương giữ cổ đứng suốt ngày dài làm việc.',
            'is_new' => false,
            'is_featured' => true,
            'is_best_seller' => true,
            'status' => 'active',
        ]);

        $p4 = Product::create([
            'category_id' => $catPants->id,
            'sku' => 'BS-PNT-004',
            'name' => 'Quần Tây Nam Dáng Slimfit Cạp Tăng Đơ Co Giãn 3cm BeeStyle Smart',
            'slug' => 'quan-tay-nam-slimfit-cap-tang-do-co-gian',
            'price' => 480000,
            'original_price' => 620000,
            'stock' => 200,
            'sold_count' => 980,
            'rating' => 4.8,
            'reviews_count' => 150,
            'image' => '/assets/img/products/5.png',
            'colors' => ['Đen', 'Xám Đậm', 'Xanh Đen', 'Be Sáng'],
            'sizes' => ['29', '30', '31', '32', '33', '34'],
            'short_description' => 'Vải tuyết mưa dệt cao cấp, cạp thông minh tự co giãn 3cm tạo sự thoải mái tối đa khi đứng ngồi.',
            'description' => 'Quần tây BeeStyle Smart thiết kế ly chết dập nhiệt công nghệ cao, ống đứng vừa vặn chuẩn công sở, vải dày dặn không bai nhão và chống bám bụi hiệu quả.',
            'is_new' => false,
            'is_featured' => true,
            'is_best_seller' => true,
            'status' => 'active',
        ]);

        $p5 = Product::create([
            'category_id' => $catShoes->id,
            'sku' => 'BS-OXF-005',
            'name' => 'Giày Da Nam Derby Classic Da Bò Ý Nhập Khẩu Đế Khâu McKay',
            'slug' => 'giay-da-nam-derby-classic-da-bo-y-nhap-khau',
            'price' => 1250000,
            'original_price' => 1650000,
            'stock' => 45,
            'sold_count' => 310,
            'rating' => 4.9,
            'reviews_count' => 88,
            'image' => '/assets/img/products/6.png',
            'colors' => ['Đen Bóng', 'Nâu Hạt Dẻ'],
            'sizes' => ['39', '40', '41', '42', '43'],
            'short_description' => '100% da bò lớp đầu (Full-grain Leather), đế cao su đúc nguyên khối khâu viền McKay chắc chắn.',
            'description' => 'Giày Derby BeeStyle thể hiện đẳng cấp quý ông với lót da cừu êm ái chống hôi chân, đế đúc chống trơn trượt trên mọi bề mặt.',
            'is_new' => true,
            'is_featured' => true,
            'is_best_seller' => false,
            'status' => 'active',
        ]);

        $p6 = Product::create([
            'category_id' => $catBlazer->id,
            'sku' => 'BS-JKT-006',
            'name' => 'Áo Khoác Gió Nam 2 Lớp Chống Thấm Nước Khóa YKK BeeStyle Shield',
            'slug' => 'ao-khoac-gio-nam-2-lop-chong-nuoc-beestyle-shield',
            'price' => 350000,
            'original_price' => 450000,
            'stock' => 350,
            'sold_count' => 2100,
            'rating' => 4.7,
            'reviews_count' => 312,
            'image' => '/assets/img/products/8.png',
            'colors' => ['Đen', 'Xanh Rêu', 'Ghi Sáng', 'Xanh Dương'],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'short_description' => 'Công nghệ trượt nước Teflon chống mưa nhẹ và gió lạnh, lót lưới tản nhiệt thoáng khí.',
            'description' => 'Áo khoác gió thông minh BeeStyle Shield trang bị khóa kéo YKK chính hãng chống kẹt, mũ tháo rời linh hoạt, túi trong có khóa an toàn đựng điện thoại ví tiền.',
            'is_new' => false,
            'is_featured' => true,
            'is_best_seller' => true,
            'status' => 'active',
        ]);

        $p7 = Product::create([
            'category_id' => $catPolo->id,
            'sku' => 'BS-TS-007',
            'name' => 'Áo Phông Nam Cổ Tròn Cotton 100% 250GSM Dày Dặn Phom Boxy',
            'slug' => 'ao-phong-nam-co-tron-cotton-250gsm-phom-boxy',
            'price' => 249000,
            'original_price' => 320000,
            'stock' => 180,
            'sold_count' => 950,
            'rating' => 4.8,
            'reviews_count' => 140,
            'image' => '/assets/img/products/3.png',
            'colors' => ['Trắng', 'Đen', 'Xám Khói', 'Nâu Đất'],
            'sizes' => ['M', 'L', 'XL', 'XXL'],
            'short_description' => 'Định lượng 250GSM dày dặn đứng phom, bo cổ dệt dày 3cm không lo bai gião.',
            'description' => 'Áo phông nam trơn phom Boxy streetwear hiện đại, chất cotton chải kỹ mịn màng không xù lông sau giặt.',
            'is_new' => true,
            'is_featured' => true,
            'is_best_seller' => false,
            'status' => 'active',
        ]);

        $p8 = Product::create([
            'category_id' => $catAccessories->id,
            'sku' => 'BS-BLT-008',
            'name' => 'Thắt Lưng Nam Da Bò Thật Khóa Tự Động Hợp Kim Cao Cấp',
            'slug' => 'that-lung-nam-da-bo-that-khoa-tu-dong-hop-kim',
            'price' => 390000,
            'original_price' => 520000,
            'stock' => 90,
            'sold_count' => 420,
            'rating' => 4.9,
            'reviews_count' => 65,
            'image' => '/assets/img/products/7.png',
            'colors' => ['Đen Khóa Bạc', 'Đen Khóa Vàng Gold', 'Nâu Khóa Đồng'],
            'sizes' => ['115cm', '120cm', '125cm'],
            'short_description' => 'Da bò nguyên tấm dập vân Saffiano chống xước, mặt khóa hợp kim không gỉ sắc sảo.',
            'description' => 'Thắt lưng nam BeeStyle khóa ray trượt tự động điều chỉnh độ ôm theo vòng bụng mà không cần bấm lỗ mất thẩm mỹ.',
            'is_new' => true,
            'is_featured' => false,
            'is_best_seller' => false,
            'status' => 'active',
        ]);

        // 4. PRODUCT IMAGES GALLERY
        $productsList = [$p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8];
        foreach ($productsList as $index => $prod) {
            ProductImage::create([
                'product_id' => $prod->id,
                'image_path' => $prod->image,
                'sort_order' => 1,
            ]);
            // Thêm 2 ảnh phụ mẫu
            $nextImgIndex = (($index + 1) % 8) + 1;
            ProductImage::create([
                'product_id' => $prod->id,
                'image_path' => "/assets/img/products/{$nextImgIndex}.png",
                'sort_order' => 2,
            ]);
        }

        // 5. COUPONS / VOUCHERS
        Coupon::create([
            'code' => 'BEESTYLE50',
            'title' => 'Giảm 50.000₫ cho đơn hàng thời trang từ 499.000₫',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'min_order_value' => 499000,
            'total_limit' => 1000,
            'used_count' => 420,
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'FREESHIPMAX',
            'title' => 'Miễn phí vận chuyển toàn quốc cho đơn từ 300.000₫',
            'discount_type' => 'shipping',
            'discount_value' => 30000,
            'min_order_value' => 300000,
            'total_limit' => 2000,
            'used_count' => 850,
            'expires_at' => now()->addMonths(6),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'VIPBEE15',
            'title' => 'Giảm 15% tổng hóa đơn cho thành viên thân thiết (Tối đa 200k)',
            'discount_type' => 'percent',
            'discount_value' => 15,
            'min_order_value' => 1000000,
            'max_discount_value' => 200000,
            'total_limit' => 500,
            'used_count' => 118,
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'WELCOMEBEE',
            'title' => 'Giảm 30.000₫ cho khách hàng mới mua đơn đầu tiên từ 299.000₫',
            'discount_type' => 'fixed',
            'discount_value' => 30000,
            'min_order_value' => 299000,
            'total_limit' => 5000,
            'used_count' => 1240,
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        // 6. ORDERS & ORDER ITEMS
        $order1 = Order::create([
            'order_code' => 'BEE-2026-0816-01',
            'user_id' => $customer1->id,
            'customer_name' => 'Nguyễn Văn Hùng',
            'customer_phone' => '0987654321',
            'customer_email' => 'hung.nguyen@gmail.com',
            'shipping_address' => 'Số 45 Đường Lê Duẩn, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
            'city' => 'Hồ Chí Minh',
            'district' => 'Quận 1',
            'notes' => 'Giao hàng trong giờ hành chính, gọi trước khi giao',
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'shipping_status' => 'shipping',
            'status_step' => 4, // 1: Chờ xác nhận, 2: Đã xác nhận, 3: Đang đóng gói, 4: Đang giao hàng, 5: Đã giao, 6: Hoàn tất
            'subtotal' => 1668000,
            'discount_amount' => 50000,
            'shipping_fee' => 0,
            'total_amount' => 1618000,
            'coupon_code' => 'BEESTYLE50',
            'created_at' => now()->subDays(2),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p1->id,
            'product_name' => $p1->name,
            'product_sku' => $p1->sku,
            'color' => 'Xanh Navy',
            'size' => 'L',
            'price' => 389000,
            'quantity' => 2,
            'subtotal' => 778000,
            'image' => $p1->image,
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $p2->id,
            'product_name' => $p2->name,
            'product_sku' => $p2->sku,
            'color' => 'Đen',
            'size' => 'L',
            'price' => 890000,
            'quantity' => 1,
            'subtotal' => 890000,
            'image' => $p2->image,
        ]);

        $order2 = Order::create([
            'order_code' => 'BEE-2026-0816-02',
            'user_id' => $customer3->id,
            'customer_name' => 'Trần Minh Quang',
            'customer_phone' => '0912345678',
            'customer_email' => 'minhquang.tran@gmail.com',
            'shipping_address' => 'Số 12 Ngõ 88 Phố Láng Hạ, Phường Láng Hạ, Quận Đống Đa, Hà Nội',
            'city' => 'Hà Nội',
            'district' => 'Đống Đa',
            'notes' => 'Giao hàng tận tay người nhận',
            'payment_method' => 'vietqr',
            'payment_status' => 'paid',
            'shipping_status' => 'processing',
            'status_step' => 3,
            'subtotal' => 930000,
            'discount_amount' => 50000,
            'shipping_fee' => 0,
            'total_amount' => 880000,
            'coupon_code' => 'BEESTYLE50',
            'created_at' => now()->subDay(),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p3->id,
            'product_name' => $p3->name,
            'product_sku' => $p3->sku,
            'color' => 'Trắng Sữa',
            'size' => '40',
            'price' => 450000,
            'quantity' => 1,
            'subtotal' => 450000,
            'image' => $p3->image,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $p4->id,
            'product_name' => $p4->name,
            'product_sku' => $p4->sku,
            'color' => 'Đen',
            'size' => '31',
            'price' => 480000,
            'quantity' => 1,
            'subtotal' => 480000,
            'image' => $p4->image,
        ]);

        $order3 = Order::create([
            'order_code' => 'BEE-2026-0815-99',
            'user_id' => $customer2->id,
            'customer_name' => 'Lê Hoàng Long',
            'customer_phone' => '0903888999',
            'customer_email' => 'hoanglong.le@gmail.com',
            'shipping_address' => '24 Nguyễn Văn Linh, Phường Nam Dương, Quận Hải Châu, Đà Nẵng',
            'city' => 'Đà Nẵng',
            'district' => 'Hải Châu',
            'notes' => '',
            'payment_method' => 'vnpay',
            'payment_status' => 'paid',
            'shipping_status' => 'completed',
            'status_step' => 6,
            'subtotal' => 1250000,
            'discount_amount' => 187500,
            'shipping_fee' => 0,
            'total_amount' => 1062500,
            'coupon_code' => 'VIPBEE15',
            'created_at' => now()->subDays(3),
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $p5->id,
            'product_name' => $p5->name,
            'product_sku' => $p5->sku,
            'color' => 'Nâu Hạt Dẻ',
            'size' => '41',
            'price' => 1250000,
            'quantity' => 1,
            'subtotal' => 1250000,
            'image' => $p5->image,
        ]);

        // 7. SAMPLE REVIEWS
        Review::create([
            'product_id' => $p1->id,
            'user_id' => $customer1->id,
            'user_name' => 'Nguyễn Văn Hùng',
            'rating' => 5,
            'comment' => 'Áo polo mặc cực kỳ tôn dáng, chất vải tổ ong dày dặn nhưng mặc rất mát và thấm mồ hôi. Đã mua 3 màu!',
            'status' => 'approved',
        ]);

        Review::create([
            'product_id' => $p2->id,
            'user_id' => $customer2->id,
            'user_name' => 'Lê Hoàng Long',
            'rating' => 5,
            'comment' => 'Blazer form Hàn Quốc rất đẹp, vai đệm tự nhiên không bị cứng nhắc. Phối với áo thun hay sơ mi đều chuẩn soái ca.',
            'status' => 'approved',
        ]);
    }
}
