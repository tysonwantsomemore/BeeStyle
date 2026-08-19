<?php

namespace App\Services;

class EcommerceDataService
{
    public static function getCategories()
    {
        return [
            [
                'id' => 1,
                'slug' => 'thoi-trang-nam',
                'name' => 'Thời Trang Nam',
                'icon' => 'fas fa-male',
                'item_count' => 128,
                'image' => '/assets/img/products/1.png',
                'description' => 'Áo polo, sơ mi, quần tây & áo khoác nam cao cấp'
            ],
            [
                'id' => 2,
                'slug' => 'thoi-trang-nu',
                'name' => 'Thời Trang Nữ',
                'icon' => 'fas fa-female',
                'item_count' => 245,
                'image' => '/assets/img/products/2.png',
                'description' => 'Váy đầm, áo kiểu, chân váy & set đồ công sở thanh lịch'
            ],
            [
                'id' => 3,
                'slug' => 'ao-khoac-blazer',
                'name' => 'Áo Khoác & Blazer',
                'icon' => 'fas fa-user-tie',
                'item_count' => 84,
                'image' => '/assets/img/products/3.png',
                'description' => 'Blazer phong cách Hàn Quốc & áo khoác gió thời thượng'
            ],
            [
                'id' => 4,
                'slug' => 'giay-dep-cao-cap',
                'name' => 'Giày Dép Cao Cấp',
                'icon' => 'fas fa-shoe-prints',
                'item_count' => 96,
                'image' => '/assets/img/products/4.png',
                'description' => 'Giày da Oxford, Loafer, Sneaker trẻ trung & cao gót nữ'
            ],
            [
                'id' => 5,
                'slug' => 'tui-vi-phu-kien',
                'name' => 'Túi Xách & Phụ Kiện',
                'icon' => 'fas fa-shopping-bag',
                'item_count' => 112,
                'image' => '/assets/img/products/5.png',
                'description' => 'Thắt lưng da bò, túi xách cao cấp, kính mát & đồng hồ'
            ],
            [
                'id' => 6,
                'slug' => 'bo-suu-tap-moi',
                'name' => 'Bộ Sưu Tập Mới',
                'icon' => 'fas fa-fire',
                'item_count' => 60,
                'image' => '/assets/img/products/6.png',
                'description' => 'Bộ sưu tập thời trang Thu Đông phong cách tối giản'
            ],
        ];
    }

    public static function getProducts()
    {
        return [
            [
                'id' => 1,
                'sku' => 'BS-PL-001',
                'name' => 'Áo Polo Nam BeeStyle Premium Cotton Dệt Tổ Ong',
                'category' => 'Thời Trang Nam',
                'category_slug' => 'thoi-trang-nam',
                'price' => 389000,
                'original_price' => 499000,
                'discount' => 22,
                'rating' => 4.9,
                'reviews_count' => 128,
                'sold_count' => 850,
                'stock' => 145,
                'is_new' => true,
                'is_featured' => true,
                'is_best_seller' => true,
                'image' => '/assets/img/products/1.png',
                'images' => [
                    '/assets/img/products/1.png',
                    '/assets/img/products/2.png',
                    '/assets/img/products/3.png'
                ],
                'colors' => ['Đen', 'Trắng', 'Xanh Navy', 'Beige'],
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'short_description' => 'Chất liệu 100% Cotton dệt tổ ong thoáng mát, thấm hút mồ hôi vượt trội, phom regular tôn dáng lịch lãm.',
                'description' => 'Áo polo BeeStyle Premium được may từ sợi cotton chải kỹ cao cấp kết hợp sợi spandex co giãn 4 chiều. Thiết kế cổ bẻ dệt bo kháng bai nhão, đường may tỉ mỉ theo tiêu chuẩn may đo Châu Âu.'
            ],
            [
                'id' => 2,
                'sku' => 'BS-BLZ-002',
                'name' => 'Áo Blazer Nam Form Rộng Phong Cách Hàn Quốc Minimalist',
                'category' => 'Áo Khoác & Blazer',
                'category_slug' => 'ao-khoac-blazer',
                'price' => 890000,
                'original_price' => 1150000,
                'discount' => 23,
                'rating' => 4.8,
                'reviews_count' => 96,
                'sold_count' => 430,
                'stock' => 52,
                'is_new' => true,
                'is_featured' => true,
                'is_best_seller' => false,
                'image' => '/assets/img/products/2.png',
                'images' => [
                    '/assets/img/products/2.png',
                    '/assets/img/products/1.png',
                    '/assets/img/products/4.png'
                ],
                'colors' => ['Đen', 'Xám Tro', 'Nâu Cafe'],
                'sizes' => ['M', 'L', 'XL'],
                'short_description' => 'Form suông thoải mái, vải chéo hàn 2 lớp đứng dáng, thích hợp phối đồ đi làm hoặc dạo phố trẻ trung.',
                'description' => 'Blazer BeeStyle Minimalist là item không thể thiếu trong tủ đồ hiện đại. Đệm vai mỏng nhẹ tự nhiên, lớp lót lụa habutai êm ái chống tĩnh điện.'
            ],
            [
                'id' => 3,
                'sku' => 'BS-DR-003',
                'name' => 'Đầm Nữ Dáng Xòe Voan Tơ Hoa Nhí Cổ Vuông Thanh Lịch',
                'category' => 'Thời Trang Nữ',
                'category_slug' => 'thoi-trang-nu',
                'price' => 520000,
                'original_price' => 690000,
                'discount' => 25,
                'rating' => 5.0,
                'reviews_count' => 210,
                'sold_count' => 1200,
                'stock' => 88,
                'is_new' => false,
                'is_featured' => true,
                'is_best_seller' => true,
                'image' => '/assets/img/products/3.png',
                'images' => [
                    '/assets/img/products/3.png',
                    '/assets/img/products/5.png',
                    '/assets/img/products/6.png'
                ],
                'colors' => ['Hoa Nhí Vàng', 'Hoa Nhí Xanh Pastel', 'Trắng Kem'],
                'sizes' => ['S', 'M', 'L'],
                'short_description' => 'Chất liệu voan tơ cao cấp bồng bềnh 2 lớp kín đáo, chiết eo nhẹ nhàng giúp tôn lên vóc dáng nữ tính.',
                'description' => 'Mẫu đầm thiết kế độc quyền BeeStyle với đường cắt may chuẩn form, chi tiết nhún ngực nhẹ nhàng và tay phồng duyên dáng.'
            ],
            [
                'id' => 4,
                'sku' => 'BS-SH-004',
                'name' => 'Áo Sơ Mi Lụa Nữ Dài Tay Phối Nơ Cổ Công Sở Cao Cấp',
                'category' => 'Thời Trang Nữ',
                'category_slug' => 'thoi-trang-nu',
                'price' => 420000,
                'original_price' => 550000,
                'discount' => 24,
                'rating' => 4.7,
                'reviews_count' => 74,
                'sold_count' => 620,
                'stock' => 110,
                'is_new' => false,
                'is_featured' => true,
                'is_best_seller' => false,
                'image' => '/assets/img/products/4.png',
                'images' => [
                    '/assets/img/products/4.png',
                    '/assets/img/products/7.png'
                ],
                'colors' => ['Trắng Sữa', 'Hồng Pastel', 'Xanh Mint'],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'short_description' => 'Lụa ngọc trai mềm mướt không nhăn, điểm nhấn nơ cổ rời biến tấu đa dạng phong cách công sở.',
                'description' => 'Chất lụa cao cấp có độ rũ mềm tự nhiên, thoáng mát cả ngày hè. Dễ dàng mix cùng chân váy bút chì hoặc quần âu ống suông.'
            ],
            [
                'id' => 5,
                'sku' => 'BS-PNT-005',
                'name' => 'Quần Tây Nam Dáng Slimfit Co Giãn Chống Nhăn BeeStyle',
                'category' => 'Thời Trang Nam',
                'category_slug' => 'thoi-trang-nam',
                'price' => 450000,
                'original_price' => 590000,
                'discount' => 24,
                'rating' => 4.8,
                'reviews_count' => 150,
                'sold_count' => 980,
                'stock' => 200,
                'is_new' => false,
                'is_featured' => true,
                'is_best_seller' => true,
                'image' => '/assets/img/products/5.png',
                'images' => [
                    '/assets/img/products/5.png',
                    '/assets/img/products/1.png'
                ],
                'colors' => ['Đen', 'Xám Đậm', 'Xanh Đen', 'Be'],
                'sizes' => ['29', '30', '31', '32', '33', '34'],
                'short_description' => 'Vải tuyết mưa dệt cao cấp, cạp thông minh co giãn 3cm tạo cảm giác dễ chịu khi vận động.',
                'description' => 'Quần tây BeeStyle cạp tăng đơ thông minh, đường ủi ly chết sắc nét, thích hợp cho quý ông công sở năng động.'
            ],
            [
                'id' => 6,
                'sku' => 'BS-OXF-006',
                'name' => 'Giày Da Nam Derby BeeStyle Classic Da Bò Ý Nhập Khẩu',
                'category' => 'Giày Dép Cao Cấp',
                'category_slug' => 'giay-dep-cao-cap',
                'price' => 1250000,
                'original_price' => 1650000,
                'discount' => 24,
                'rating' => 4.9,
                'reviews_count' => 88,
                'sold_count' => 310,
                'stock' => 45,
                'is_new' => true,
                'is_featured' => true,
                'is_best_seller' => false,
                'image' => '/assets/img/products/6.png',
                'images' => [
                    '/assets/img/products/6.png',
                    '/assets/img/products/8.png'
                ],
                'colors' => ['Đen Bóng', 'Nâu Hạt Dẻ'],
                'sizes' => ['39', '40', '41', '42', '43'],
                'short_description' => '100% da bò lớp đầu (Full-grain Leather), đế cao su đúc nguyên khối khâu viền McKay chắc chắn.',
                'description' => 'Đôi giày thể hiện đẳng cấp quý ông với lót da cừu êm chân chống hôi, phom giày chuẩn người Việt Nam.'
            ],
            [
                'id' => 7,
                'sku' => 'BS-BAG-007',
                'name' => 'Túi Xách Nữ Da Thật BeeStyle Monogram Khóa Vàng Gold',
                'category' => 'Túi Xách & Phụ Kiện',
                'category_slug' => 'tui-vi-phu-kien',
                'price' => 790000,
                'original_price' => 990000,
                'discount' => 20,
                'rating' => 4.9,
                'reviews_count' => 115,
                'sold_count' => 540,
                'stock' => 60,
                'is_new' => true,
                'is_featured' => true,
                'is_best_seller' => false,
                'image' => '/assets/img/products/7.png',
                'images' => [
                    '/assets/img/products/7.png',
                    '/assets/img/products/3.png'
                ],
                'colors' => ['Nâu Monogram', 'Đen Tuyển', 'Trắng Kem'],
                'sizes' => ['Size Vừa (22cm)'],
                'short_description' => 'Họa tiết Monogram dập nổi tinh xảo, phụ kiện hợp kim mạ vàng 18K sang trọng chống gỉ sét.',
                'description' => 'Thiết kế sang trọng, quai đeo chéo tháo rời linh hoạt, ngăn chứa rộng rãi đựng vừa điện thoại và mỹ phẩm.'
            ],
            [
                'id' => 8,
                'sku' => 'BS-JKT-008',
                'name' => 'Áo Khoác Gió Nam Nữ 2 Lớp Chống Thấm Nước BeeStyle Eco',
                'category' => 'Áo Khoác & Blazer',
                'category_slug' => 'ao-khoac-blazer',
                'price' => 350000,
                'original_price' => 450000,
                'discount' => 22,
                'rating' => 4.7,
                'reviews_count' => 312,
                'sold_count' => 2100,
                'stock' => 350,
                'is_new' => false,
                'is_featured' => true,
                'is_best_seller' => true,
                'image' => '/assets/img/products/8.png',
                'images' => [
                    '/assets/img/products/8.png',
                    '/assets/img/products/1.png'
                ],
                'colors' => ['Đen', 'Xanh Rêu', 'Ghi Sáng', 'Vàng Cát'],
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'short_description' => 'Công nghệ trượt nước Teflon, lót lưới thông hơi tản nhiệt, khóa kéo YKK chính hãng.',
                'description' => 'Áo khoác gió đa năng tiện dụng cho mọi thời tiết, chống gió, chống bụi và mưa nhẹ hiệu quả.'
            ]
        ];
    }

    public static function getProductById($id)
    {
        $products = self::getProducts();
        foreach ($products as $p) {
            if ($p['id'] == $id) {
                return $p;
            }
        }
        return $products[0];
    }

    public static function getOrders()
    {
        return [
            [
                'id' => 1001,
                'order_code' => 'BEE-2026-0816-01',
                'customer_name' => 'Nguyễn Văn Hùng',
                'customer_phone' => '0987 654 321',
                'customer_email' => 'hung.nguyen@gmail.com',
                'customer_address' => 'Số 45 Đường Lê Duẩn, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
                'items_count' => 3,
                'total_amount' => 1729000,
                'payment_method' => 'Thanh toán khi nhận hàng (COD)',
                'payment_status' => 'Chưa thanh toán',
                'shipping_status' => 'Đang giao hàng',
                'status_step' => 4, // 1: Chờ xác nhận, 2: Đã xác nhận, 3: Đang đóng gói, 4: Đang giao hàng, 5: Đã giao, 6: Hoàn tất
                'created_at' => '16/08/2026 14:30',
                'items' => [
                    [
                        'name' => 'Áo Polo Nam BeeStyle Premium Cotton Dệt Tổ Ong',
                        'sku' => 'BS-PL-001',
                        'color' => 'Xanh Navy',
                        'size' => 'L',
                        'quantity' => 2,
                        'price' => 389000,
                        'image' => '/assets/img/products/1.png'
                    ],
                    [
                        'name' => 'Áo Blazer Nam Form Rộng Phong Cách Hàn Quốc Minimalist',
                        'sku' => 'BS-BLZ-002',
                        'color' => 'Đen',
                        'size' => 'L',
                        'quantity' => 1,
                        'price' => 890000,
                        'image' => '/assets/img/products/2.png'
                    ]
                ]
            ],
            [
                'id' => 1002,
                'order_code' => 'BEE-2026-0816-02',
                'customer_name' => 'Trần Thị Mai Phương',
                'customer_phone' => '0912 345 678',
                'customer_email' => 'maiphuong.tran@gmail.com',
                'customer_address' => 'Số 12 Ngõ 88 Phố Láng Hạ, Phường Láng Hạ, Quận Đống Đa, Hà Nội',
                'items_count' => 2,
                'total_amount' => 940000,
                'payment_method' => 'Chuyển khoản VietQR',
                'payment_status' => 'Đã thanh toán',
                'shipping_status' => 'Đang đóng gói',
                'status_step' => 3,
                'created_at' => '16/08/2026 11:15',
                'items' => [
                    [
                        'name' => 'Đầm Nữ Dáng Xòe Voan Tơ Hoa Nhí Cổ Vuông Thanh Lịch',
                        'sku' => 'BS-DR-003',
                        'color' => 'Hoa Nhí Vàng',
                        'size' => 'M',
                        'quantity' => 1,
                        'price' => 520000,
                        'image' => '/assets/img/products/3.png'
                    ],
                    [
                        'name' => 'Áo Sơ Mi Lụa Nữ Dài Tay Phối Nơ Cổ Công Sở Cao Cấp',
                        'sku' => 'BS-SH-004',
                        'color' => 'Trắng Sữa',
                        'size' => 'M',
                        'quantity' => 1,
                        'price' => 420000,
                        'image' => '/assets/img/products/4.png'
                    ]
                ]
            ],
            [
                'id' => 1003,
                'order_code' => 'BEE-2026-0815-99',
                'customer_name' => 'Lê Hoàng Long',
                'customer_phone' => '0903 888 999',
                'customer_email' => 'hoanglong.le@gmail.com',
                'customer_address' => '24 Nguyễn Văn Linh, Phường Nam Dương, Quận Hải Châu, Đà Nẵng',
                'items_count' => 1,
                'total_amount' => 1250000,
                'payment_method' => 'Ví Điện Tử VNPAY',
                'payment_status' => 'Đã thanh toán',
                'shipping_status' => 'Hoàn tất',
                'status_step' => 6,
                'created_at' => '15/08/2026 09:20',
                'items' => [
                    [
                        'name' => 'Giày Da Nam Derby BeeStyle Classic Da Bò Ý Nhập Khẩu',
                        'sku' => 'BS-OXF-006',
                        'color' => 'Nâu Hạt Dẻ',
                        'size' => '41',
                        'quantity' => 1,
                        'price' => 1250000,
                        'image' => '/assets/img/products/6.png'
                    ]
                ]
            ],
            [
                'id' => 1004,
                'order_code' => 'BEE-2026-0815-95',
                'customer_name' => 'Phạm Thùy Linh',
                'customer_phone' => '0978 112 233',
                'customer_email' => 'thuylinh.pham@gmail.com',
                'customer_address' => '78 Trần Phú, Phường 4, TP. Vũng Tàu, Bà Rịa - Vũng Tàu',
                'items_count' => 1,
                'total_amount' => 790000,
                'payment_method' => 'Thanh toán khi nhận hàng (COD)',
                'payment_status' => 'Chưa thanh toán',
                'shipping_status' => 'Đã xác nhận',
                'status_step' => 2,
                'created_at' => '15/08/2026 16:45',
                'items' => [
                    [
                        'name' => 'Túi Xách Nữ Da Thật BeeStyle Monogram Khóa Vàng Gold',
                        'sku' => 'BS-BAG-007',
                        'color' => 'Nâu Monogram',
                        'size' => 'Size Vừa',
                        'quantity' => 1,
                        'price' => 790000,
                        'image' => '/assets/img/products/7.png'
                    ]
                ]
            ]
        ];
    }

    public static function getCustomers()
    {
        return [
            [
                'id' => 1,
                'name' => 'Nguyễn Văn Hùng',
                'email' => 'hung.nguyen@gmail.com',
                'phone' => '0987 654 321',
                'total_spent' => 4580000,
                'orders_count' => 4,
                'avatar' => '/assets/img/team/40x40/58.webp',
                'created_at' => '12/03/2026',
                'status' => 'Hoạt động'
            ],
            [
                'id' => 2,
                'name' => 'Trần Thị Mai Phương',
                'email' => 'maiphuong.tran@gmail.com',
                'phone' => '0912 345 678',
                'total_spent' => 3240000,
                'orders_count' => 3,
                'avatar' => '/assets/img/team/40x40/59.webp',
                'created_at' => '05/04/2026',
                'status' => 'Hoạt động'
            ],
            [
                'id' => 3,
                'name' => 'Lê Hoàng Long',
                'email' => 'hoanglong.le@gmail.com',
                'phone' => '0903 888 999',
                'total_spent' => 8950000,
                'orders_count' => 7,
                'avatar' => '/assets/img/team/40x40/30.webp',
                'created_at' => '18/01/2026',
                'status' => 'VIP Silver'
            ],
            [
                'id' => 4,
                'name' => 'Phạm Thùy Linh',
                'email' => 'thuylinh.pham@gmail.com',
                'phone' => '0978 112 233',
                'total_spent' => 2190000,
                'orders_count' => 2,
                'avatar' => '/assets/img/team/40x40/60.webp',
                'created_at' => '22/05/2026',
                'status' => 'Hoạt động'
            ]
        ];
    }

    public static function getCoupons()
    {
        return [
            [
                'id' => 1,
                'code' => 'BEESTYLE50',
                'title' => 'Giảm 50.000₫ cho đơn hàng từ 499.000₫',
                'discount_amount' => 50000,
                'type' => 'fixed',
                'min_order' => 499000,
                'expires_at' => '31/12/2026',
                'used_count' => 420,
                'total_limit' => 1000,
                'status' => 'Đang diễn ra'
            ],
            [
                'id' => 2,
                'code' => 'FREESHIPMAX',
                'title' => 'Miễn phí vận chuyển toàn quốc đơn từ 300.000₫',
                'discount_amount' => 30000,
                'type' => 'shipping',
                'min_order' => 300000,
                'expires_at' => '31/12/2026',
                'used_count' => 850,
                'total_limit' => 2000,
                'status' => 'Đang diễn ra'
            ],
            [
                'id' => 3,
                'code' => 'VIPBEE15',
                'title' => 'Giảm 15% tổng hóa đơn cho thành viên thân thiết',
                'discount_amount' => 15,
                'type' => 'percent',
                'min_order' => 1000000,
                'expires_at' => '30/09/2026',
                'used_count' => 118,
                'total_limit' => 500,
                'status' => 'Đang diễn ra'
            ]
        ];
    }
}
