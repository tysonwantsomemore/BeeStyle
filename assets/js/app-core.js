/**
 * BEESTYLE ATELIER - CORE DATA & CLIENT STATE MANAGEMENT
 * Version: 2.0 (Multi-Page E-Commerce Architecture)
 */

window.BeeDB = {
  categories: [
    { id: 1, name: "Thời Trang Nam", slug: "nam", icon: "user", banner: "https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?q=80&w=1200&auto=format&fit=crop" },
    { id: 2, name: "Thời Trang Nữ", slug: "nu", icon: "sparkles", banner: "https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1200&auto=format&fit=crop" },
    { id: 3, name: "Phụ Kiện Da Cao Cấp", slug: "phu-kien", icon: "briefcase", banner: "https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=1200&auto=format&fit=crop" },
    { id: 4, name: "Giày & Dép Thủ Công", slug: "giay", icon: "tag", banner: "https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=1200&auto=format&fit=crop" }
  ],

  brands: [
    { id: 1, name: "Beestyle Atelier", desc: "Dòng may đo thủ công bespoke cao cấp" },
    { id: 2, name: "Beestyle Studio", desc: "Thời trang thiết kế tối giản ứng dụng" },
    { id: 3, name: "Beestyle Tailored", desc: "Âu phục & Blazer chuẩn Ý đương đại" },
    { id: 4, name: "Beestyle Leather", desc: "Đồ da Mill và Nappa thuộc tự nhiên" },
    { id: 5, name: "Beestyle Footwear", desc: "Giày thủ công đóng mạch Goodyear" }
  ],

  order_statuses: [
    { id: 1, name: "pending", label: "Chờ xử lý" },
    { id: 2, name: "processing", label: "Đang xử lý" },
    { id: 3, name: "shipping", label: "Đang giao hàng" },
    { id: 4, name: "failed_delivery", label: "Giao hàng thất bại" },
    { id: 5, name: "completed", label: "Hoàn thành" },
    { id: 6, name: "cancel", label: "Đã hủy" }
  ],

  users: [
    {
      id: 1,
      phone_number: "0988776655",
      email: "customer@beestyle.vn",
      password: "123456",
      fullname: "Nguyễn Văn An",
      avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop",
      gender: "male",
      birthday: "1995-08-15",
      role: "customer",
      status: "active",
      bank_name: "Vietcombank",
      user_bank_name: "NGUYEN VAN AN",
      bank_account: "0071001234567",
      reason_lock: null,
      is_change_password: 0,
      tier: "VIP Gold",
      points: 1250,
      address: "88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh"
    }
  ],

  user_addresses: [
    {
      id: 1,
      user_id: 1,
      fullname: "Nguyễn Văn An",
      phone_number: "0988776655",
      address: "88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh",
      id_default: 1
    },
    {
      id: 2,
      user_id: 1,
      fullname: "Nguyễn Văn An (Văn phòng)",
      phone_number: "0988776655",
      address: "Tòa nhà Bitexco, Số 2 Hải Triều, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh",
      id_default: 0
    }
  ],

  coupons: [
    { id: 1, code: "BEESTYLE15", title: "Giảm 15% Thành Viên", description: "Áp dụng cho mọi thành viên Beestyle", discount_type: "percent", discount_value: 15, usage_limit: 100, usage_count: 24, is_active: 1, is_notified: 1 },
    { id: 2, code: "BEESTYLE50", title: "Giảm 50.000₫ Trực Tiếp", description: "Giảm 50k cho đơn từ 500k", discount_type: "fix_amount", discount_value: 50000, usage_limit: 50, usage_count: 10, is_active: 1, is_notified: 1 },
    { id: 3, code: "FREESHIP", title: "Miễn Phí Giao Hàng", description: "Miễn phí vận chuyển toàn quốc", discount_type: "fix_amount", discount_value: 30000, usage_limit: 200, usage_count: 58, is_active: 1, is_notified: 1 }
  ],

  payments: [
    { id: 1, name: "Thanh toán COD khi nhận hàng", code: "COD", icon: "banknote", logo: "cod.png", is_active: 1, desc: "Kiểm tra hàng rồi thanh toán trực tiếp cho shipper" },
    { id: 2, name: "Chuyển khoản QR Bank (VietQR)", code: "BANK", icon: "qr-code", logo: "vietqr.png", is_active: 1, desc: "Quét mã QR qua mọi ứng dụng ngân hàng 24/7" },
    { id: 3, name: "Ví điện tử MoMo / VNPay", code: "MOMO", icon: "wallet", logo: "momo.png", is_active: 1, desc: "Thanh toán một chạm tiện lợi, an toàn" }
  ],

  products: [
    {
      id: 1,
      brand_id: 1,
      category_id: 1,
      name: "Áo Sơ Mi Lụa Dệt Tinh Xảo",
      sku: "BST-SILK-01",
      price: 850000,
      sale_price: 680000,
      is_sale: 1,
      views: 2450,
      short_description: "Lụa tự nhiên dệt thủ công tạo cảm giác mát lạnh, cúc vỏ ốc khâu tay sắc sảo.",
      description: "Chất liệu 100% lụa tơ tằm dệt thủ công mang lại sự mềm mịn thượng hạng và độ thoáng khí tuyệt đối. Phom dáng Regular-Fit thanh lịch, cổ áo được ép mex cao cấp không gãy gập, đường may cuộn mép tinh xảo theo tiêu chuẩn Haute Couture.",
      care_guide: "Giặt tay nhẹ nhàng bằng nước lạnh hoặc giặt khô. Không vắt xoắn mạnh. Là ủi ở nhiệt độ thấp với mặt trái của vải.",
      thumbnail: "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "100% Sợi Lụa Tự Nhiên (Mulberry Silk)",
        "Kiểu dệt": "Satin Weave 19 Momme",
        "Phom dáng": "Modern Regular Fit",
        "Khuy áo": "Cúc xà cừ tự nhiên khâu tay",
        "Xuất xứ": "Việt Nam Atelier",
        "Bảo hành": "12 tháng đổi mới đường may"
      },
      variants: [
        { id: 101, sku: "BST-SILK-01-WHITE-M", color: "Trắng Ngà", size: "M", price: 680000, stock: 15, thumbnail: "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=800&auto=format&fit=crop" },
        { id: 102, sku: "BST-SILK-01-WHITE-L", color: "Trắng Ngà", size: "L", price: 680000, stock: 22, thumbnail: "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=800&auto=format&fit=crop" },
        { id: 103, sku: "BST-SILK-01-BLACK-L", color: "Đen Than", size: "L", price: 680000, stock: 8, thumbnail: "https://images.unsplash.com/photo-1596755094514-f87e34085b2c?q=80&w=800&auto=format&fit=crop" },
        { id: 104, sku: "BST-SILK-01-GREEN-XL", color: "Xanh Rêu", size: "XL", price: 680000, stock: 12, thumbnail: "https://images.unsplash.com/photo-1603252109303-2751441dd157?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=800&auto=format&fit=crop",
        "https://images.unsplash.com/photo-1596755094514-f87e34085b2c?q=80&w=800&auto=format&fit=crop",
        "https://images.unsplash.com/photo-1603252109303-2751441dd157?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 2,
      brand_id: 3,
      category_id: 1,
      name: "Blazer May Đo 2 Hàng Khuy Chuẩn Ý",
      sku: "BST-BLAZER-02",
      price: 1850000,
      sale_price: 1550000,
      is_sale: 1,
      views: 3120,
      short_description: "Áo khoác blazer may đo phom Double-Breasted chuẩn Ý, lót lụa tơ tằm mềm rủ.",
      description: "Thiết kế đệm vai nhẹ tạo phom đứng dáng mạnh mẽ nhưng không gò bó. Từng đường cắt sắc sảo tôn vinh vóc dáng người mặc. Lớp lót trong bằng lụa satin mượt mà giúp việc mặc vào tháo ra dễ dàng.",
      care_guide: "Khuyên dùng giặt khô chuyên nghiệp. Treo áo bằng mắc gỗ bản to để giữ phom cầu vai hoàn hảo.",
      thumbnail: "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "Wool pha Viscose 320gsm",
        "Lót trong": "100% Cupro Silk thoáng khí",
        "Kiểu ve áo": "Peak Lapel (Ve nhọn sang trọng)",
        "Hàng cúc": "6 cúc mạ đồng xước cổ điển",
        "Xuất xứ": "Ý / Xưởng Atelier Sài Gòn",
        "Bảo hành": "24 tháng sửa phom miễn phí"
      },
      variants: [
        { id: 201, sku: "BST-BLZ-02-BLK-M", color: "Đen Obsidian", size: "M", price: 1550000, stock: 10, thumbnail: "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?q=80&w=800&auto=format&fit=crop" },
        { id: 202, sku: "BST-BLZ-02-BLK-L", color: "Đen Obsidian", size: "L", price: 1550000, stock: 18, thumbnail: "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?q=80&w=800&auto=format&fit=crop" },
        { id: 203, sku: "BST-BLZ-02-GRY-L", color: "Ghi Khói", size: "L", price: 1550000, stock: 7, thumbnail: "https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?q=80&w=800&auto=format&fit=crop",
        "https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 3,
      brand_id: 2,
      category_id: 2,
      name: "Đầm Lụa Maxi Thắt Nơ Lưng Quyến Rũ",
      sku: "BST-DRESS-03",
      price: 1290000,
      sale_price: 1290000,
      is_sale: 0,
      views: 4200,
      short_description: "Đầm maxi lụa tơ tằm buông rủ quyến rũ, điểm nhấn thắt nơ hở lưng tinh tế.",
      description: "Được thiết kế riêng cho các buổi dạ tiệc hoàng hôn và sự kiện quan trọng. Phom váy bay bổng uyển chuyển theo từng bước đi, chi tiết thắt nơ sau lưng tạo điểm nhấn gợi cảm đầy e ấp.",
      care_guide: "Giặt tay hoặc giặt hấp chuyên biệt. Không ngâm trong chất tẩy rửa mạnh.",
      thumbnail: "https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "100% Silk Twill dệt mịn",
        "Độ dài": "Maxi phủ gót thướt tha",
        "Thiết kế lưng": "Khoét chữ V có dây lụa thắt nơ",
        "Xuất xứ": "Việt Nam Studio",
        "Bảo hành": "Đổi size miễn phí 30 ngày"
      },
      variants: [
        { id: 301, sku: "BST-DRS-03-RED-S", color: "Đỏ Mận", size: "S", price: 1290000, stock: 9, thumbnail: "https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop" },
        { id: 302, sku: "BST-DRS-03-RED-M", color: "Đỏ Mận", size: "M", price: 1290000, stock: 14, thumbnail: "https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop" },
        { id: 303, sku: "BST-DRS-03-BLK-S", color: "Đen Dạ Tiệc", size: "S", price: 1290000, stock: 12, thumbnail: "https://images.unsplash.com/photo-1539008835657-9e8e9680c956?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop",
        "https://images.unsplash.com/photo-1539008835657-9e8e9680c956?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 4,
      brand_id: 2,
      category_id: 2,
      name: "Áo Dệt Kim Cổ Tim Phom Relaxed",
      sku: "BST-KNIT-04",
      price: 650000,
      sale_price: 520000,
      is_sale: 1,
      views: 1890,
      short_description: "Sợi dệt kim viscose pha cotton mềm mại, ôm nhẹ cơ thể tôn dáng thanh mảnh.",
      description: "Chiếc áo hoàn hảo cho mọi outfit thường ngày, dễ dàng phối cùng chân váy hay quần tây suông. Sợi dệt thoáng khí, không xù lông sau nhiều lần giặt.",
      care_guide: "Giặt bằng túi giặt ở chế độ đồ len nhẹ, phơi phẳng trên mặt phẳng ngang để tránh dão áo.",
      thumbnail: "https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "Cotton Modal dệt kim mịn",
        "Kiểu cổ": "Cổ tim nhẹ nhàng nữ tính",
        "Độ co giãn": "Co giãn 4 chiều tự nhiên",
        "Xuất xứ": "Việt Nam Studio",
        "Bảo hành": "Đổi trả 30 ngày"
      },
      variants: [
        { id: 401, sku: "BST-KNT-04-CREAM-S", color: "Kem Vanilla", size: "S", price: 520000, stock: 20, thumbnail: "https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=800&auto=format&fit=crop" },
        { id: 402, sku: "BST-KNT-04-CREAM-M", color: "Kem Vanilla", size: "M", price: 520000, stock: 25, thumbnail: "https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1576566588028-4147f3842f27?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 5,
      brand_id: 4,
      category_id: 3,
      name: "Túi Da Bò Mill Minimalist Crossbody",
      sku: "BST-BAG-05",
      price: 1450000,
      sale_price: 1450000,
      is_sale: 0,
      views: 2980,
      short_description: "Da bò Mill thượng hạng Ý, khóa nam châm giấu kín, đường may tay thủ công.",
      description: "Thiết kế túi xách đeo chéo nhỏ gọn nhưng đựng vừa điện thoại, son, ví tiền và các vật dụng thiết yếu. Da bò vân sần tự nhiên chống xước và tăng độ bóng đẹp theo thời gian sử dụng.",
      care_guide: "Tránh để tiếp xúc nước mưa trực tiếp. Dưỡng da định kỳ bằng kem dưỡng chuyên dụng Beestyle Leather Care.",
      thumbnail: "https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "Da Bò Mill Ý nhập khẩu nguyên tấm",
        "Kích thước": "22 x 15 x 7 cm",
        "Khóa phụ kiện": "Hợp kim mạ vàng 18K không gỉ",
        "Dây đeo": "Dây da điều chỉnh độ dài linh hoạt",
        "Bảo hành": "Trọn đời phụ kiện & đường may"
      },
      variants: [
        { id: 501, sku: "BST-BAG-05-TAN", color: "Nâu Tan", size: "Freesize", price: 1450000, stock: 11, thumbnail: "https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=800&auto=format&fit=crop" },
        { id: 502, sku: "BST-BAG-05-BLK", color: "Đen Tuyền", size: "Freesize", price: 1450000, stock: 8, thumbnail: "https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=800&auto=format&fit=crop",
        "https://images.unsplash.com/photo-1548036328-c9fa89d128fa?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 6,
      brand_id: 5,
      category_id: 4,
      name: "Giày Penny Loafer Da Thật Cổ Điển",
      sku: "BST-SHOE-06",
      price: 1650000,
      sale_price: 1390000,
      is_sale: 1,
      views: 3400,
      short_description: "Giày penny loafer đế phíp cao cấp, đệm lót êm chân không gây đau gót.",
      description: "Đôi giày khẳng định vị thế quý ông với chất da bóng bẩy và lớp đệm khí bên trong êm ái. Cấu trúc đế khâu mạch bền bỉ cho phép sử dụng hàng ngày trong nhiều năm mà không lo bong keo.",
      care_guide: "Đánh xi sáp định kỳ, sử dụng cây giữ phom giày (Shoe tree) gỗ tuyết tùng sau khi đi.",
      thumbnail: "https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu ngoài": "Da Bò Nappa Full-Grain",
        "Lót trong": "Da cừu khử mùi êm ái",
        "Cấu trúc đế": "Đế Phíp Khâu Mạch Blake/Goodyear",
        "Xuất xứ": "Xưởng Đóng Giày Thủ Công Sài Gòn",
        "Bảo hành": "12 tháng keo & đế"
      },
      variants: [
        { id: 601, sku: "BST-SH-06-39", color: "Đen Bóng", size: "39", price: 1390000, stock: 6, thumbnail: "https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop" },
        { id: 602, sku: "BST-SH-06-40", color: "Đen Bóng", size: "40", price: 1390000, stock: 12, thumbnail: "https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop" },
        { id: 603, sku: "BST-SH-06-41", color: "Đen Bóng", size: "41", price: 1390000, stock: 15, thumbnail: "https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop" },
        { id: 604, sku: "BST-SH-06-42", color: "Đen Bóng", size: "42", price: 1390000, stock: 8, thumbnail: "https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 7,
      brand_id: 1,
      category_id: 1,
      name: "Quần Âu Linen Pháp Xếp Ly Ống Suông",
      sku: "BST-PANTS-07",
      price: 790000,
      sale_price: 790000,
      is_sale: 0,
      views: 1650,
      short_description: "Vải linen Pháp 100% tự nhiên, xếp ly trước tạo phom đứng dáng thanh tao.",
      description: "Chất liệu vải đũi linen nhập khẩu từ Pháp với độ mềm rủ tự nhiên, thấm hút mồ hôi tối đa trong khí hậu nhiệt đới. Thiết kế cạp quần có đai chun phụ giấu bên trong giúp điều chỉnh vừa vặn vòng eo.",
      care_guide: "Giặt nhẹ nhàng với nước lạnh, là ủi khi vải còn hơi ẩm để giữ nếp ly sắc nét.",
      thumbnail: "https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "100% French Natural Linen",
        "Phom dáng": "Wide-Leg Relaxed Pleated",
        "Chi tiết": "Cạp xếp ly đôi cổ điển",
        "Xuất xứ": "Pháp / Gia công Việt Nam",
        "Bảo hành": "Đổi size 30 ngày"
      },
      variants: [
        { id: 701, sku: "BST-PNT-07-WHT-29", color: "Trắng Sữa", size: "29", price: 790000, stock: 8, thumbnail: "https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=800&auto=format&fit=crop" },
        { id: 702, sku: "BST-PNT-07-WHT-30", color: "Trắng Sữa", size: "30", price: 790000, stock: 14, thumbnail: "https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=800&auto=format&fit=crop" },
        { id: 703, sku: "BST-PNT-07-WHT-31", color: "Trắng Sữa", size: "31", price: 790000, stock: 10, thumbnail: "https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1506629082955-511b1aa562c8?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 8,
      brand_id: 2,
      category_id: 2,
      name: "Chân Váy Lụa Xếp Ly Dáng Chữ A",
      sku: "BST-SKIRT-08",
      price: 890000,
      sale_price: 710000,
      is_sale: 1,
      views: 2200,
      short_description: "Chân váy midi chữ A dập ly nhuyễn mềm mại, tạo hiệu ứng chuyển động thướt tha.",
      description: "Cạp chun ẩn co giãn êm ái kết hợp cùng dải lụa thướt tha, là item không thể thiếu trong tủ đồ phái đẹp. Thích hợp kết hợp cùng áo sơ mi lụa hoặc áo dệt kim cho vẻ ngoài đài các.",
      care_guide: "Treo bằng móc kẹp hai bên cạp váy, không vò xoắn làm gãy nếp dập ly.",
      thumbnail: "https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "Lụa Satin cao cấp dập nhiệt",
        "Độ dài váy": "Midi qua gối thanh lịch",
        "Kiểu cạp": "Cạp phẳng trước, thun ẩn sau",
        "Xuất xứ": "Việt Nam Studio",
        "Bảo hành": "Đổi size 30 ngày"
      },
      variants: [
        { id: 801, sku: "BST-SKT-08-PINK-S", color: "Hồng Tro", size: "S", price: 710000, stock: 11, thumbnail: "https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?q=80&w=800&auto=format&fit=crop" },
        { id: 802, sku: "BST-SKT-08-PINK-M", color: "Hồng Tro", size: "M", price: 710000, stock: 16, thumbnail: "https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 9,
      brand_id: 3,
      category_id: 1,
      name: "Măng Tô Dạ Cashmere Dáng Dài Thu Đông",
      sku: "BST-COAT-09",
      price: 2650000,
      sale_price: 2250000,
      is_sale: 1,
      views: 3950,
      short_description: "Dạ Cashmere 100% nhập khẩu giữ ấm tuyệt hảo, phom dáng dài uy quyền.",
      description: "Được mệnh danh là biểu tượng của sự quyền quý, chiếc áo măng tô Cashmere mang đến cảm giác ấm áp vượt trội với trọng lượng siêu nhẹ. Thiết kế ve áo to bản và đai lưng thắt eo tôn dáng chuẩn mực.",
      care_guide: "Chỉ giặt khô tại các tiệm giặt ủi uy tín. Bảo quản trong túi bọc áo chuyên dụng.",
      thumbnail: "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "100% Cashmere Wool 580gsm",
        "Lót trong": "Lụa Bemberg kháng tĩnh điện",
        "Độ dài": "Over-knee (Qua gối)",
        "Xuất xứ": "Ý / Xưởng Atelier Sài Gòn",
        "Bảo hành": "Trọn đời bảo dưỡng sợi dạ"
      },
      variants: [
        { id: 901, sku: "BST-COT-09-CAMEL-M", color: "Nâu Camel", size: "M", price: 2250000, stock: 5, thumbnail: "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?q=80&w=800&auto=format&fit=crop" },
        { id: 902, sku: "BST-COT-09-CAMEL-L", color: "Nâu Camel", size: "L", price: 2250000, stock: 8, thumbnail: "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 10,
      brand_id: 4,
      category_id: 3,
      name: "Thắt Lưng Da Bò Ý Khóa Kim Brass",
      sku: "BST-BELT-10",
      price: 590000,
      sale_price: 590000,
      is_sale: 0,
      views: 1420,
      short_description: "Da bò Vegetable-Tanned dày 3.5mm nguyên tấm, đầu khóa đồng đúc mờ tinh tế.",
      description: "Thắt lưng da bò thuộc thảo mộc tự nhiên theo phương pháp truyền thống của vùng Tuscany (Ý). Mặt khóa đồng nguyên khối chắc chắn mang phong cách cổ điển vượt thời gian.",
      care_guide: "Bôi kem sáp ong dưỡng da sau mỗi 6 tháng để da dẻo dai và bóng đẹp.",
      thumbnail: "https://images.unsplash.com/photo-1624222247344-550fb60583dc?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "Da Bò Veg-Tan Ý nguyên tấm",
        "Bản rộng": "3.4 cm (Chuẩn âu phục & jeans)",
        "Khóa": "Solid Brass đúc nguyên khối",
        "Xuất xứ": "Việt Nam Leather Studio",
        "Bảo hành": "5 năm đổi mới nếu nổ da"
      },
      variants: [
        { id: 1001, sku: "BST-BLT-10-BRN", color: "Nâu Espresso", size: "115cm", price: 590000, stock: 20, thumbnail: "https://images.unsplash.com/photo-1624222247344-550fb60583dc?q=80&w=800&auto=format&fit=crop" },
        { id: 1002, sku: "BST-BLT-10-BLK", color: "Đen Nhám", size: "115cm", price: 590000, stock: 15, thumbnail: "https://images.unsplash.com/photo-1624222247344-550fb60583dc?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1624222247344-550fb60583dc?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 11,
      brand_id: 5,
      category_id: 4,
      name: "Giày Chelsea Boot Da Lộn Đế Cao Su Crepe",
      sku: "BST-BOOT-11",
      price: 1890000,
      sale_price: 1590000,
      is_sale: 1,
      views: 3100,
      short_description: "Da lộn Ý kháng nước nhẹ, đế cao su tự nhiên Crepe êm ái bám đường.",
      description: "Dòng Chelsea Boot kinh điển kết hợp hoàn hảo giữa phong cách lãng tử phóng khoáng và sự thoải mái tối đa. Cổ thun co giãn đàn hồi cao giúp việc xỏ chân vô cùng nhanh chóng.",
      care_guide: "Xịt dung dịch Nano chống nước trước khi đi, dùng bàn chải cao su chuyên dụng vệ sinh bụi bẩn trên da lộn.",
      thumbnail: "https://images.unsplash.com/photo-1638247025967-b4e38f787b76?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu da": "Da bò Suede (Da lộn) nhập Ý",
        "Đế giày": "Cao su non tự nhiên Crepe",
        "Cổ thun": "Dệt co giãn 4 chiều siêu bền",
        "Xuất xứ": "Việt Nam Footwear",
        "Bảo hành": "12 tháng đổi mới đế"
      },
      variants: [
        { id: 1101, sku: "BST-BOT-11-SAND-40", color: "Vàng Cát", size: "40", price: 1590000, stock: 8, thumbnail: "https://images.unsplash.com/photo-1638247025967-b4e38f787b76?q=80&w=800&auto=format&fit=crop" },
        { id: 1102, sku: "BST-BOT-11-SAND-41", color: "Vàng Cát", size: "41", price: 1590000, stock: 12, thumbnail: "https://images.unsplash.com/photo-1638247025967-b4e38f787b76?q=80&w=800&auto=format&fit=crop" },
        { id: 1103, sku: "BST-BOT-11-SAND-42", color: "Vàng Cát", size: "42", price: 1590000, stock: 9, thumbnail: "https://images.unsplash.com/photo-1638247025967-b4e38f787b76?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1638247025967-b4e38f787b76?q=80&w=800&auto=format&fit=crop"
      ]
    },
    {
      id: 12,
      brand_id: 2,
      category_id: 2,
      name: "Áo Corset Dạ Tweed Dệt Sợi Ánh Kim",
      sku: "BST-TWEED-12",
      price: 1150000,
      sale_price: 920000,
      is_sale: 1,
      views: 2850,
      short_description: "Dạ tweed dệt thủ công pha sợi kim tuyến lấp lánh, phom corset định hình thon gọn.",
      description: "Một tuyệt tác tôn vinh đường cong phái đẹp với kỹ thuật may nẹp gọng mềm định hình vòng eo mà vẫn dễ chịu khi cử động. Hoàn hảo để phối cùng quần âu cạp cao hoặc chân váy dạ tiệc.",
      care_guide: "Giặt tay nhẹ nhàng trong túi giặt, không sấy nhiệt cao.",
      thumbnail: "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop",
      specs: {
        "Chất liệu": "Dạ Tweed sợi len & Lurex ánh kim",
        "Nẹp gọng": "Gọng nhựa dẻo Plastic Boning êm ái",
        "Khóa lưng": "Dây kéo kim loại giấu kín",
        "Xuất xứ": "Việt Nam Studio",
        "Bảo hành": "Đổi size 30 ngày"
      },
      variants: [
        { id: 1201, sku: "BST-TWD-12-IVORY-S", color: "Trắng Ánh Kim", size: "S", price: 920000, stock: 14, thumbnail: "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop" },
        { id: 1202, sku: "BST-TWD-12-IVORY-M", color: "Trắng Ánh Kim", size: "M", price: 920000, stock: 18, thumbnail: "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop" }
      ],
      galleries: [
        "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop"
      ]
    }
  ],

  reviews: [
    { id: 1, product_id: 1, user_fullname: "Trần Bảo Nam", rating: 5, review_text: "Vải lụa mặc mát rượi, đường may cúc vỏ ốc rất tinh tế. Xứng đáng 5 sao!", created_at: "10/08/2026", photos: ["https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=300&auto=format&fit=crop"] },
    { id: 2, product_id: 1, user_fullname: "Lê Minh Hằng", rating: 5, review_text: "Mua tặng bạn trai ai cũng khen áo đẹp và sang chảnh. Đóng gói hộp rất cao cấp.", created_at: "12/08/2026" },
    { id: 3, product_id: 2, user_fullname: "Hoàng Gia Huy", rating: 5, review_text: "Blazer phom chuẩn như may đo tại Ý, lót lụa không bị dính người, rất ưng ý.", created_at: "14/08/2026" },
    { id: 4, product_id: 3, user_fullname: "Nguyễn Thùy Linh", rating: 5, review_text: "Đầm đẹp xuất sắc, mặc đi tiệc cưới ai cũng hỏi mua ở đâu. Lụa bay bổng lắm!", created_at: "15/08/2026" },
    { id: 5, product_id: 5, user_fullname: "Đỗ Quốc Việt", rating: 5, review_text: "Túi da bò thật cầm đầm tay, phụ kiện mạ đồng sắc sảo. Giao hàng rất nhanh.", created_at: "16/08/2026" },
    { id: 6, product_id: 6, user_fullname: "Phan Văn Quân", rating: 5, review_text: "Giày êm không bị cọ gót, đế khâu chắc nịch, đóng hộp sang trọng.", created_at: "16/08/2026" }
  ],

  comments: [
    { id: 1, product_id: 1, user_fullname: "Vũ Tuấn Anh", content: "Áo này giặt máy có bị nhăn không shop ơi?", created_at: "15/08/2026" },
    { id: 2, product_id: 1, user_fullname: "CSKH Beestyle", content: "Dạ chào anh, lụa tự nhiên khuyến khích giặt tay hoặc dùng túi giặt chế độ Delicate để giữ sợi vải bền đẹp nhất ạ!", created_at: "15/08/2026" }
  ],

  orders: [
    {
      id: 1001,
      code: "BEE-2026-001",
      user_id: 1,
      payment_id: 1,
      fullname: "Nguyễn Văn An",
      phone_number: "0988776655",
      email: "customer@beestyle.vn",
      address: "88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh",
      note: "Giao trong giờ hành chính",
      payment_name: "Thanh toán khi nhận hàng (COD)",
      total_amount: 2230000,
      is_paid: 1,
      is_refund: 0,
      coupon_id: 1,
      coupon_code: "BEESTYLE15",
      coupon_description: "Giảm 15% Thành Viên",
      coupon_discount_type: "percent",
      coupon_discount_value: "15",
      max_discount_value: null,
      is_refund_cancel: 0,
      check_refund_cancel: 0,
      order_status_id: 5, // 5 -> completed (Giao hàng thành công)
      status_name: "Hoàn Thành (Delivered)",
      created_at: "10/08/2026 09:30",
      delivered_at: "12/08/2026 14:15",
      items: [
        { product_id: 1, product_variant_id: 101, name: "Áo Sơ Mi Lụa Dệt Tinh Xảo", name_variant: "Màu Trắng Ngà - Size M", price: 680000, quantity: 1, thumbnail: "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=800&auto=format&fit=crop" },
        { product_id: 2, product_variant_id: 201, name: "Blazer May Đo 2 Hàng Khuy Chuẩn Ý", name_variant: "Đen Obsidian - Size M", price: 1550000, quantity: 1, thumbnail: "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?q=80&w=800&auto=format&fit=crop" }
      ],
      timeline: [
        { status: "Chờ xử lý (Pending)", note: "Đơn hàng đã được tạo và tiếp nhận", time: "10/08 - 09:30", done: true },
        { status: "Đang xử lý (Processing)", note: "Kho Atelier TP.HCM hoàn tất đóng kiện", time: "10/08 - 14:15", done: true },
        { status: "Đang giao hàng (Shipping)", note: "Shipper đã bàn giao tận tay khách hàng", time: "12/08 - 14:00", done: true },
        { status: "Hoàn thành (Completed)", note: "Khách hàng đã nhận hàng & thanh toán thành công", time: "12/08 - 14:15", done: true, current: true }
      ]
    },
    {
      id: 1002,
      code: "BEE-2026-002",
      user_id: 1,
      payment_id: 2,
      fullname: "Nguyễn Văn An",
      phone_number: "0988776655",
      email: "customer@beestyle.vn",
      address: "88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh",
      note: "Gói quà sang trọng",
      payment_name: "Chuyển khoản QR Bank (VietQR)",
      total_amount: 1450000,
      is_paid: 1,
      is_refund: 0,
      order_status_id: 5,
      status_name: "Hoàn Thành (Delivered)",
      created_at: "18/08/2026 15:00",
      delivered_at: "20/08/2026 11:30",
      items: [
        { product_id: 5, product_variant_id: 501, name: "Túi Da Bò Mill Minimalist Crossbody", name_variant: "Màu Nâu Tan - Freesize", price: 1450000, quantity: 1, thumbnail: "https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=800&auto=format&fit=crop" }
      ],
      timeline: [
        { status: "Chờ xử lý (Pending)", note: "Đơn hàng đã được tạo", time: "18/08 - 15:00", done: true },
        { status: "Đang xử lý (Processing)", note: "Xưởng đồ da đã đóng gói hộp quà", time: "18/08 - 17:30", done: true },
        { status: "Đang giao hàng (Shipping)", note: "Vận chuyển hỏa tốc nội thành", time: "20/08 - 09:00", done: true },
        { status: "Hoàn thành (Completed)", note: "Giao hàng thành công", time: "20/08 - 11:30", done: true, current: true }
      ]
    },
    {
      id: 1003,
      code: "BEE-2026-003",
      user_id: 1,
      payment_id: 1,
      fullname: "Nguyễn Văn An",
      phone_number: "0988776655",
      email: "customer@beestyle.vn",
      address: "88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh",
      note: "Gọi trước khi giao",
      payment_name: "Thanh toán khi nhận hàng (COD)",
      total_amount: 1390000,
      is_paid: 0,
      is_refund: 0,
      order_status_id: 3,
      status_name: "Đang Giao Hàng (Shipping)",
      created_at: "28/08/2026 10:15",
      items: [
        { product_id: 6, product_variant_id: 602, name: "Giày Penny Loafer Da Thật Cổ Điển", name_variant: "Màu Đen Bóng - Size 40", price: 1390000, quantity: 1, thumbnail: "https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop" }
      ],
      timeline: [
        { status: "Chờ xử lý (Pending)", note: "Đơn hàng đã tạo", time: "28/08 - 10:15", done: true },
        { status: "Đang xử lý (Processing)", note: "Kiểm định đóng gói xưởng giày", time: "28/08 - 14:00", done: true },
        { status: "Đang giao hàng (Shipping)", note: "Shipper đang giao hàng tới địa chỉ của bạn", time: "Hôm nay", done: true, current: true },
        { status: "Hoàn thành (Completed)", note: "Khách hàng nhận hàng và thanh toán", time: "Dự kiến chiều nay", done: false }
      ]
    }
  ],

  refunds: [
    {
      id: 1,
      refund_code: "REF-2026-08101",
      order_code: "BEE-2026-001",
      order_id: 1001,
      user_id: 1,
      user_fullname: "Nguyễn Văn An",
      phone_number: "0988776655",
      product_name: "Áo Sơ Mi Lụa Dệt Tinh Xảo (Size M)",
      reason: "Đổi sang size L do mặc hơi rộng",
      customer_notes: "Áo còn nguyên tem mác Atelier, đã quay clip unbox và kiểm tra cúc áo.",
      refund_amount: 680000,
      refund_method: "bank",
      bank_name: "Vietcombank",
      bank_account: "0071001234567",
      bank_account_name: "NGUYEN VAN AN",
      images: [
        "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=600&auto=format&fit=crop"
      ],
      video_proof: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4",
      video_name: "clip_unbox_aomi_beestyle.mp4",
      status: "completed", // pending, inspecting, picking_up, completed, rejected
      status_badge: "Đã Hoàn Tiền Thành Công",
      transaction_code: "VCB-883920148",
      created_at: "15/08/2026 10:20",
      completed_at: "16/08/2026 15:45",
      timeline: [
        { step: 1, title: "Đã gửi yêu cầu đổi trả", desc: "Hệ thống đã tiếp nhận yêu cầu kèm Ảnh & Video Unbox", time: "15/08 10:20", done: true },
        { step: 2, title: "Thẩm định video unbox & kiểm tra tem mác", desc: "Chuyên viên QC đã thẩm định video unbox nguyên vẹn", time: "15/08 14:00", done: true },
        { step: 3, title: "Thu hồi sản phẩm về xưởng", desc: "Shipper đã nhận lại hàng tại 88 Lê Lợi, Quận 1", time: "16/08 09:30", done: true },
        { step: 4, title: "Hoàn tất chuyển khoản hoàn tiền", desc: "Đã giải ngân 680.000₫ về STK Vietcombank (0071001234567) - Mã GD: VCB-883920148", time: "16/08 15:45", done: true, current: true }
      ]
    },
    {
      id: 2,
      refund_code: "REF-2026-08202",
      order_code: "BEE-2026-002",
      order_id: 1002,
      user_id: 1,
      user_fullname: "Nguyễn Văn An",
      phone_number: "0988776655",
      product_name: "Túi Da Bò Mill Minimalist Crossbody",
      reason: "Muốn đổi sang mẫu túi màu đen tuyền",
      customer_notes: "Gửi clip mở hộp và ảnh chi tiết khóa đồng.",
      refund_amount: 1450000,
      refund_method: "bank",
      bank_name: "Vietcombank",
      bank_account: "0071001234567",
      bank_account_name: "NGUYEN VAN AN",
      images: [
        "https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=600&auto=format&fit=crop"
      ],
      video_proof: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4",
      video_name: "video_unbox_tui_da.mp4",
      status: "inspecting",
      status_badge: "Đang Thẩm Định Video Unbox",
      transaction_code: null,
      created_at: "28/08/2026 15:30",
      timeline: [
        { step: 1, title: "Đã gửi yêu cầu đổi trả", desc: "Đã nộp ảnh tem mác và video unbox", time: "28/08 15:30", done: true },
        { step: 2, title: "Đang thẩm định video unbox", desc: "Bộ phận CSKH & QC đang đối soát video mở hộp", time: "Hôm nay", done: true, current: true },
        { step: 3, title: "Thu hồi sản phẩm", desc: "Chờ điều phối shipper thu hồi hàng tận nơi", time: "Dự kiến ngày mai", done: false },
        { step: 4, title: "Chuyển tiền hoàn", desc: "Hoàn 1.450.000₫ về tài khoản ngân hàng sau khi nhận hàng", time: "Dự kiến 2 ngày tới", done: false }
      ]
    }
  ]
};

/**
 * CLIENT APPLICATION CONTROLLER (BeeCore)
 */
window.BeeCore = {
  cart: [],
  wishlist: [],
  currentUser: null,
  appliedCoupon: null,
  selectedAddressId: 1,
  selectedPaymentId: 1,

  init: function() {
    this.loadState();
    this.injectSharedModals();
    this.updateCartBadge();
    this.updateWishlistBadge();
    this.renderAuthStatus();
    lucide.createIcons();
  },

  loadState: function() {
    try {
      const c = localStorage.getItem('beestyle_cart');
      if (c) this.cart = JSON.parse(c);
      const w = localStorage.getItem('beestyle_wishlist');
      if (w) this.wishlist = JSON.parse(w);
      const u = localStorage.getItem('beestyle_user');
      if (u) this.currentUser = JSON.parse(u);
      const cp = localStorage.getItem('beestyle_coupon');
      if (cp) this.appliedCoupon = JSON.parse(cp);
      const rv = localStorage.getItem('beestyle_reviews');
      if (rv) BeeDB.reviews = JSON.parse(rv);
    } catch(e) {
      console.error('Error loading localStorage state', e);
    }
  },

  saveCart: function() {
    localStorage.setItem('beestyle_cart', JSON.stringify(this.cart));
    this.updateCartBadge();
    this.renderCartDrawerContent();
  },

  saveWishlist: function() {
    localStorage.setItem('beestyle_wishlist', JSON.stringify(this.wishlist));
    this.updateWishlistBadge();
  },

  saveUser: function() {
    if (this.currentUser) {
      localStorage.setItem('beestyle_user', JSON.stringify(this.currentUser));
    } else {
      localStorage.removeItem('beestyle_user');
    }
    this.renderAuthStatus();
  },

  formatMoney: function(amount) {
    if (!amount && amount !== 0) return '0₫';
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', '') + '₫';
  },

  showToast: function(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    const bg = type === 'success' ? 'bg-neutral-900 text-white border-neutral-700' : 'bg-rose-900 text-white border-rose-700';
    const icon = type === 'success' ? 'check-circle' : 'alert-circle';

    toast.className = `${bg} border shadow-2xl px-4 py-3 rounded-lg text-xs flex items-center gap-2.5 transform transition-all duration-300 pointer-events-auto animate-toast`;
    toast.innerHTML = `
      <i data-lucide="${icon}" class="w-4 h-4 shrink-0 text-amber-400"></i>
      <span class="flex-grow">${message}</span>
    `;

    container.appendChild(toast);
    lucide.createIcons();

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-10px)';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  },

  copyCode: function(code) {
    navigator.clipboard.writeText(code).then(() => {
      this.showToast(`Đã sao chép mã ưu đãi: ${code}`);
      this.applyCoupon(code);
    });
  },

  // ================= CART OPERATIONS =================
  addToCart: function(productId, variantId = null, qty = 1) {
    if (!this.currentUser) {
      this.showToast('Vui lòng đăng nhập tài khoản để thêm vào giỏ hàng!', 'error');
      this.openAuthModal('login');
      return;
    }

    const p = BeeDB.products.find(item => item.id === productId);
    if (!p) return;

    const variant = variantId 
      ? p.variants?.find(v => v.id === variantId) 
      : (p.variants && p.variants.length > 0 ? p.variants[0] : null);

    const price = variant ? variant.price : (p.is_sale ? p.sale_price : p.price);
    const variantName = variant ? `${variant.color} - ${variant.size}` : 'Tiêu Chuẩn';
    const thumb = variant?.thumbnail || p.thumbnail;
    const finalVariantId = variant ? variant.id : null;

    const existingIndex = this.cart.findIndex(item => item.product_id === p.id && item.variant_id === finalVariantId);
    if (existingIndex > -1) {
      this.cart[existingIndex].quantity += qty;
    } else {
      this.cart.push({
        product_id: p.id,
        variant_id: finalVariantId,
        name: p.name,
        variant_name: variantName,
        price: price,
        quantity: qty,
        thumbnail: thumb
      });
    }

    this.saveCart();
    this.showToast(`Đã thêm "${p.name}" vào giỏ hàng!`);
    this.toggleCart(true);
  },

  updateCartQty: function(index, delta) {
    if (this.cart[index]) {
      this.cart[index].quantity += delta;
      if (this.cart[index].quantity <= 0) {
        this.cart.splice(index, 1);
        this.showToast('Đã xóa sản phẩm khỏi giỏ hàng.');
      }
      this.saveCart();
    }
  },

  removeFromCart: function(index) {
    if (this.cart[index]) {
      this.cart.splice(index, 1);
      this.saveCart();
      this.showToast('Đã xóa sản phẩm khỏi giỏ hàng.');
    }
  },

  clearCart: function() {
    this.cart = [];
    this.appliedCoupon = null;
    localStorage.removeItem('beestyle_coupon');
    this.saveCart();
  },

  applyCoupon: function(code) {
    const cp = BeeDB.coupons.find(c => c.code.toUpperCase() === code.trim().toUpperCase());
    if (cp) {
      this.appliedCoupon = cp;
      localStorage.setItem('beestyle_coupon', JSON.stringify(cp));
      this.showToast(`Áp dụng mã giảm giá "${cp.code}" thành công!`);
      this.saveCart();
    } else {
      this.showToast(`Mã giảm giá "${code}" không hợp lệ hoặc đã hết hạn!`, 'error');
    }
  },

  removeCoupon: function() {
    this.appliedCoupon = null;
    localStorage.removeItem('beestyle_coupon');
    this.showToast('Đã hủy áp dụng mã giảm giá.');
    this.saveCart();
  },

  getCartCalculations: function() {
    const subtotal = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    let discount = 0;
    if (this.appliedCoupon && subtotal > 0) {
      if (this.appliedCoupon.discount_type === 'percent') {
        discount = Math.round(subtotal * (this.appliedCoupon.discount_value / 100));
      } else {
        discount = this.appliedCoupon.discount_value;
      }
    }
    const shipping = (subtotal >= 500000 || subtotal === 0) ? 0 : 30000;
    const total = Math.max(0, subtotal - discount + shipping);
    const totalCount = this.cart.reduce((sum, item) => sum + item.quantity, 0);

    return { subtotal, discount, shipping, total, totalCount };
  },

  toggleCart: function(isOpen) {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    if (!drawer) return;

    if (isOpen) {
      this.renderCartDrawerContent();
      drawer.classList.remove('translate-x-full');
      overlay.classList.remove('hidden');
    } else {
      drawer.classList.add('translate-x-full');
      overlay.classList.add('hidden');
    }
  },

  updateCartBadge: function() {
    const { totalCount, total } = this.getCartCalculations();
    const badges = document.querySelectorAll('.cart-count-badge');
    badges.forEach(b => {
      b.innerText = totalCount;
      b.style.display = totalCount > 0 ? 'flex' : 'none';
    });

    const headerTotals = document.querySelectorAll('.cart-header-total');
    headerTotals.forEach(t => {
      t.innerText = this.formatMoney(total);
    });
  },

  renderCartDrawerContent: function() {
    const container = document.getElementById('cart-items-container');
    const summaryContainer = document.getElementById('cart-summary-container');
    if (!container) return;

    const { subtotal, discount, shipping, total, totalCount } = this.getCartCalculations();

    if (this.cart.length === 0) {
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center h-full py-16 text-center text-neutral-400">
          <i data-lucide="shopping-bag" class="w-16 h-16 stroke-1 mb-4 opacity-40"></i>
          <p class="font-serif-luxury text-xl text-neutral-700 mb-1">Túi mua hàng đang trống</p>
          <p class="text-xs text-neutral-500 max-w-xs mb-6 font-light">Hãy khám phá các tác phẩm thời trang may đo và thiết kế độc quyền từ Beestyle.</p>
          <a href="shop.html" onclick="BeeCore.toggleCart(false)" class="px-6 py-3 bg-neutral-900 text-white text-xs tracking-widest uppercase font-semibold rounded hover:bg-neutral-800 transition-colors">
            Khám Phá Cửa Hàng
          </a>
        </div>
      `;
      if (summaryContainer) summaryContainer.innerHTML = '';
      lucide.createIcons();
      return;
    }

    container.innerHTML = this.cart.map((item, idx) => `
      <div class="flex gap-4 p-3 bg-neutral-50 rounded-lg border border-neutral-200/70 items-center">
        <img src="${item.thumbnail}" alt="${item.name}" class="w-16 h-20 object-cover rounded bg-neutral-200 shrink-0">
        <div class="flex-grow min-w-0">
          <h4 class="text-xs font-semibold text-neutral-900 truncate">${item.name}</h4>
          <span class="text-[11px] text-neutral-500 block mb-1">${item.variant_name}</span>
          <span class="font-serif-luxury text-sm font-bold text-neutral-900">${this.formatMoney(item.price)}</span>
          
          <div class="flex items-center justify-between mt-2">
            <div class="flex items-center border border-neutral-300 rounded bg-white text-xs">
              <button onclick="BeeCore.updateCartQty(${idx}, -1)" class="px-2 py-0.5 text-neutral-600 hover:bg-neutral-100">-</button>
              <span class="px-2 py-0.5 font-semibold text-[11px]">${item.quantity}</span>
              <button onclick="BeeCore.updateCartQty(${idx}, 1)" class="px-2 py-0.5 text-neutral-600 hover:bg-neutral-100">+</button>
            </div>
            <button onclick="BeeCore.removeFromCart(${idx})" class="text-neutral-400 hover:text-rose-600 text-xs p-1">
              <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
            </button>
          </div>
        </div>
      </div>
    `).join('');

    if (summaryContainer) {
      summaryContainer.innerHTML = `
        <div class="space-y-2 text-xs border-t border-neutral-200 pt-4">
          <div class="flex justify-between text-neutral-600">
            <span>Tạm tính:</span>
            <span class="font-medium text-neutral-900">${this.formatMoney(subtotal)}</span>
          </div>

          ${discount > 0 ? `
            <div class="flex justify-between text-emerald-700">
              <span class="flex items-center gap-1">
                Giảm giá (${this.appliedCoupon?.code}):
                <button onclick="BeeCore.removeCoupon()" class="text-neutral-400 hover:text-rose-600">×</button>
              </span>
              <span class="font-medium">-${this.formatMoney(discount)}</span>
            </div>
          ` : ''}

          <div class="flex justify-between text-neutral-600">
            <span>Phí giao hàng:</span>
            <span class="font-medium ${shipping === 0 ? 'text-emerald-600' : 'text-neutral-900'}">${shipping === 0 ? 'FREESHIP' : this.formatMoney(shipping)}</span>
          </div>

          <!-- Coupon input -->
          <div class="flex gap-2 pt-2">
            <input type="text" id="cart-coupon-input" placeholder="Mã giảm giá..." class="bg-white border border-neutral-300 rounded px-3 py-2 text-xs flex-grow focus:outline-none focus:border-neutral-900 uppercase">
            <button onclick="BeeCore.applyCoupon(document.getElementById('cart-coupon-input').value)" class="px-3 py-2 bg-neutral-800 hover:bg-neutral-950 text-white rounded text-xs font-semibold uppercase">Áp Dụng</button>
          </div>

          <div class="flex justify-between text-sm font-bold text-neutral-900 border-t border-neutral-200 pt-3 mt-3">
            <span>Tổng thanh toán:</span>
            <span class="font-serif-luxury text-lg text-amber-900">${this.formatMoney(total)}</span>
          </div>

          <button onclick="BeeCore.openCheckout()" class="w-full py-3.5 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.2em] uppercase rounded shadow-lg transition-all mt-3">
            Tiến Hành Thanh Toán
          </button>
        </div>
      `;
    }

    lucide.createIcons();
  },

  // ================= WISHLIST OPERATIONS =================
  toggleWishlist: function(productId) {
    if (!this.currentUser) {
      this.showToast('Vui lòng đăng nhập để lưu danh sách yêu thích!', 'error');
      this.openAuthModal('login');
      return;
    }

    const idx = this.wishlist.indexOf(productId);
    const p = BeeDB.products.find(item => item.id === productId);
    if (idx > -1) {
      this.wishlist.splice(idx, 1);
      this.showToast(`Đã xóa "${p ? p.name : 'sản phẩm'}" khỏi danh sách yêu thích.`);
    } else {
      this.wishlist.push(productId);
      this.showToast(`Đã thêm "${p ? p.name : 'sản phẩm'}" vào danh sách yêu thích!`);
    }
    this.saveWishlist();
  },

  updateWishlistBadge: function() {
    const count = this.wishlist.length;
    const badges = document.querySelectorAll('.wishlist-count-badge');
    badges.forEach(b => {
      b.innerText = count;
      b.style.display = count > 0 ? 'flex' : 'none';
    });
  },

  openWishlistModal: function() {
    if (!this.currentUser) {
      this.showToast('Vui lòng đăng nhập để xem danh sách yêu thích!', 'error');
      this.openAuthModal('login');
      return;
    }

    const modal = document.getElementById('wishlist-modal');
    const container = document.getElementById('wishlist-items-content');
    if (!modal || !container) return;

    const favProducts = BeeDB.products.filter(p => this.wishlist.includes(p.id));

    if (favProducts.length === 0) {
      container.innerHTML = `
        <div class="text-center py-12 text-neutral-500">
          <i data-lucide="heart" class="w-12 h-12 stroke-1 mx-auto mb-3 opacity-40"></i>
          <p class="font-serif-luxury text-lg text-neutral-800">Chưa có sản phẩm yêu thích nào</p>
          <p class="text-xs text-neutral-400 mt-1">Bấm vào biểu tượng trái tim ở sản phẩm để lưu lại xem sau.</p>
        </div>
      `;
    } else {
      container.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[65vh] overflow-y-auto pr-1">
          ${favProducts.map(p => `
            <div class="flex gap-3 p-3 bg-neutral-50 rounded-lg border border-neutral-200">
              <a href="product-detail.html?id=${p.id}" class="w-20 h-24 bg-neutral-200 rounded overflow-hidden shrink-0">
                <img src="${p.thumbnail}" alt="${p.name}" class="w-full h-full object-cover">
              </a>
              <div class="flex flex-col justify-between flex-grow min-w-0">
                <div>
                  <a href="product-detail.html?id=${p.id}" class="text-xs font-semibold text-neutral-900 truncate hover:underline block">${p.name}</a>
                  <span class="font-serif-luxury text-sm font-bold text-neutral-900">${this.formatMoney(p.is_sale ? p.sale_price : p.price)}</span>
                </div>
                <div class="flex items-center gap-2 pt-2">
                  <button onclick="BeeCore.addToCart(${p.id}); BeeCore.toggleWishlist(${p.id});" class="flex-grow py-1.5 bg-neutral-900 text-white rounded text-[10px] font-semibold tracking-wider uppercase hover:bg-neutral-800">Thêm Giỏ</button>
                  <button onclick="BeeCore.toggleWishlist(${p.id}); BeeCore.openWishlistModal();" class="p-1.5 text-neutral-400 hover:text-rose-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                </div>
              </div>
            </div>
          `).join('')}
        </div>
      `;
    }

    modal.classList.remove('hidden');
    lucide.createIcons();
  },

  closeWishlistModal: function() {
    document.getElementById('wishlist-modal')?.classList.add('hidden');
  },

  // ================= AUTH / USER OPERATIONS =================
  renderAuthStatus: function() {
    const areas = document.querySelectorAll('.header-auth-area');
    areas.forEach(area => {
      if (this.currentUser) {
        area.innerHTML = `
          <div class="flex items-center gap-2 cursor-pointer group relative" onclick="BeeCore.toggleUserDropdown(event)">
            <div class="w-8 h-8 rounded-full bg-neutral-900 text-amber-300 flex items-center justify-center font-serif text-xs font-bold ring-2 ring-amber-400/40 shadow-sm">
              ${this.currentUser.fullname ? this.currentUser.fullname.charAt(0).toUpperCase() : 'U'}
            </div>
            <div class="hidden lg:flex flex-col text-left">
              <span class="text-xs font-semibold text-neutral-900 leading-tight">${this.currentUser.fullname}</span>
              <span class="text-[9px] uppercase tracking-wider text-amber-700 font-medium">${this.currentUser.tier || 'VIP Gold'}</span>
            </div>
            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-neutral-400"></i>

            <!-- Luxury User Dropdown -->
            <div id="user-dropdown-menu" class="hidden absolute right-0 top-full mt-2 w-64 bg-white border border-neutral-200 rounded-xl shadow-2xl py-2 z-50 text-xs animate-fade-in divide-y divide-neutral-100">
              <div class="px-4 py-3 bg-gradient-to-r from-neutral-900 to-neutral-800 text-white rounded-t-xl -mt-2">
                <div class="flex items-center justify-between">
                  <p class="font-semibold text-sm truncate text-white">${this.currentUser.fullname}</p>
                  <span class="px-2 py-0.5 bg-amber-400/20 text-amber-300 rounded text-[9px] font-bold uppercase tracking-wider border border-amber-400/30">${this.currentUser.tier || 'VIP Gold'}</span>
                </div>
                <p class="text-[11px] text-neutral-300 truncate mt-0.5">${this.currentUser.email}</p>
                <div class="mt-2 pt-2 border-t border-neutral-700/60 flex justify-between items-center text-[10px] text-amber-200/90">
                  <span>Điểm tích lũy Atelier:</span>
                  <strong class="font-mono text-amber-300">${(this.currentUser.points || 1250).toLocaleString()} pts</strong>
                </div>
              </div>

              <div class="py-1">
                <a href="javascript:void(0)" onclick="BeeCore.openProfileModal('info')" class="flex items-center gap-2.5 px-4 py-2.5 text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="user" class="w-4 h-4 text-neutral-500"></i>
                  <span>Thông Tin Tài Khoản</span>
                </a>
                <a href="javascript:void(0)" onclick="BeeCore.openProfileModal('password')" class="flex items-center gap-2.5 px-4 py-2.5 text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="key-round" class="w-4 h-4 text-amber-600"></i>
                  <span>Đổi Mật Khẩu</span>
                </a>
                <a href="javascript:void(0)" onclick="BeeCore.openProfileModal('orders')" class="flex items-center gap-2.5 px-4 py-2.5 text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="package" class="w-4 h-4 text-neutral-500"></i>
                  <span>Đơn Hàng Của Tôi</span>
                </a>
                <a href="javascript:void(0)" onclick="BeeCore.openProfileModal('address')" class="flex items-center gap-2.5 px-4 py-2.5 text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="map-pin" class="w-4 h-4 text-neutral-500"></i>
                  <span>Sổ Địa Chỉ Giao Hàng</span>
                </a>
              </div>

              <div class="py-1">
                <a href="javascript:void(0)" onclick="BeeCore.openRefundModal()" class="flex items-center gap-2.5 px-4 py-2.5 text-neutral-700 hover:bg-neutral-50 transition-colors">
                  <i data-lucide="rotate-ccw" class="w-4 h-4 text-neutral-400"></i>
                  <span>Đổi Trả & Hoàn Tiền</span>
                </a>
                <button onclick="BeeCore.logout()" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-rose-600 hover:bg-rose-50 font-medium transition-colors">
                  <i data-lucide="log-out" class="w-4 h-4"></i>
                  <span>Đăng Xuất</span>
                </button>
              </div>
            </div>
          </div>
        `;
      } else {
        area.innerHTML = `
          <button onclick="BeeCore.openAuthModal('login')" class="flex items-center gap-1.5 text-xs uppercase tracking-wider text-neutral-700 hover:text-black font-medium transition-colors">
            <i data-lucide="user" class="w-4 h-4"></i>
            <span class="hidden lg:inline">Tài Khoản</span>
          </button>
        `;
      }
    });
    lucide.createIcons();
  },

  toggleUserDropdown: function(e) {
    e?.stopPropagation();
    const drop = document.getElementById('user-dropdown-menu');
    if (drop) drop.classList.toggle('hidden');
  },

  openAuthModal: function(mode = 'login') {
    const modal = document.getElementById('auth-modal');
    if (!modal) return;
    this.setAuthMode(mode);
    modal.classList.remove('hidden');
  },

  closeAuthModal: function() {
    document.getElementById('auth-modal')?.classList.add('hidden');
  },

  setAuthMode: function(mode) {
    const title = document.getElementById('auth-modal-title');
    const registerFields = document.getElementById('auth-register-fields');
    const submitBtn = document.getElementById('auth-submit-btn');
    const toggleText = document.getElementById('auth-toggle-text');

    if (mode === 'register') {
      if (title) title.innerText = 'Đăng Ký Tài Khoản';
      if (registerFields) registerFields.classList.remove('hidden');
      if (submitBtn) submitBtn.innerText = 'Tạo Tài Khoản';
      if (toggleText) toggleText.innerHTML = 'Đã có tài khoản? <a href="javascript:void(0)" onclick="BeeCore.setAuthMode(\'login\')" class="font-semibold text-neutral-950 underline">Đăng nhập ngay</a>';
    } else {
      if (title) title.innerText = 'Đăng Nhập Thành Viên';
      if (registerFields) registerFields.classList.add('hidden');
      if (submitBtn) submitBtn.innerText = 'Đăng Nhập';
      if (toggleText) toggleText.innerHTML = 'Chưa có tài khoản? <a href="javascript:void(0)" onclick="BeeCore.setAuthMode(\'register\')" class="font-semibold text-neutral-950 underline">Đăng ký thành viên</a>';
    }
  },

  submitAuth: function(e) {
    e?.preventDefault();
    const email = document.getElementById('auth-email')?.value?.trim() || 'customer@beestyle.vn';
    const fullname = document.getElementById('auth-fullname')?.value?.trim() || 'Nguyễn Văn An';
    const password = document.getElementById('auth-password')?.value || '123456';

    const defaultUser = BeeDB.users.find(u => u.email === email) || BeeDB.users[0];

    this.currentUser = {
      ...defaultUser,
      fullname: fullname || defaultUser.fullname,
      email: email || defaultUser.email,
      phone: defaultUser.phone_number || '0988776655',
      phone_number: defaultUser.phone_number || '0988776655',
      gender: defaultUser.gender || 'male',
      birthday: defaultUser.birthday || '1995-08-15',
      address: defaultUser.address || '88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
      bank_name: defaultUser.bank_name || 'Vietcombank',
      user_bank_name: defaultUser.user_bank_name || (fullname || 'NGUYEN VAN AN').toUpperCase(),
      bank_account: defaultUser.bank_account || '0071001234567',
      tier: defaultUser.tier || 'VIP Gold',
      points: defaultUser.points || 1250,
      password: password || defaultUser.password || '123456',
      is_change_password: defaultUser.is_change_password || 0
    };

    this.saveUser();
    this.closeAuthModal();
    this.showToast(`Chào mừng ${this.currentUser.fullname} đã đăng nhập!`);

    // Re-render components across active views if applicable
    if (typeof CheckoutPage !== 'undefined' && CheckoutPage.init) {
      CheckoutPage.init();
    }
    if (typeof ShopPage !== 'undefined' && ShopPage.renderProducts) {
      ShopPage.renderProducts();
    }
    if (typeof ProductDetail !== 'undefined' && ProductDetail.updateFavIcon) {
      ProductDetail.updateFavIcon();
    }
  },

  logout: function() {
    this.currentUser = null;
    this.saveUser();
    this.showToast('Bạn đã đăng xuất tài khoản.');

    // Re-render components across active views if applicable
    if (typeof CheckoutPage !== 'undefined' && CheckoutPage.init) {
      CheckoutPage.init();
    }
    if (typeof ShopPage !== 'undefined' && ShopPage.renderProducts) {
      ShopPage.renderProducts();
    }
    if (typeof ProductDetail !== 'undefined' && ProductDetail.updateFavIcon) {
      ProductDetail.updateFavIcon();
    }
  },

  // ================= PROFILE & PASSWORD OPERATIONS =================
  openProfileModal: function(tab = 'info') {
    if (!this.currentUser) {
      this.showToast('Vui lòng đăng nhập để xem thông tin tài khoản!', 'error');
      this.openAuthModal('login');
      return;
    }
    const modal = document.getElementById('profile-modal');
    if (!modal) return;

    // Populate user profile data in fields
    const u = this.currentUser;
    const fnInput = document.getElementById('profile-fullname');
    const emInput = document.getElementById('profile-email');
    const phInput = document.getElementById('profile-phone');
    const gdInput = document.getElementById('profile-gender');
    const bdInput = document.getElementById('profile-birthday');
    const adInput = document.getElementById('profile-address');
    const adDetail = document.getElementById('profile-address-detail');
    const bnInput = document.getElementById('profile-bank-name');
    const baInput = document.getElementById('profile-bank-account');
    const buInput = document.getElementById('profile-user-bank-name');

    if (fnInput) fnInput.value = u.fullname || '';
    if (emInput) emInput.value = u.email || '';
    if (phInput) phInput.value = u.phone_number || u.phone || '';
    if (gdInput) gdInput.value = u.gender || 'male';
    if (bdInput) bdInput.value = u.birthday || '1995-08-15';
    if (adInput) adInput.value = u.address || '88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh';
    if (adDetail) adDetail.value = u.address || '88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh';
    if (bnInput) bnInput.value = u.bank_name || 'Vietcombank';
    if (baInput) baInput.value = u.bank_account || '0071001234567';
    if (buInput) buInput.value = u.user_bank_name || (u.fullname || '').toUpperCase();

    // Summary elements
    const avatarEl = document.getElementById('profile-avatar-letter');
    const nameEl = document.getElementById('profile-display-name');
    const tierEl = document.getElementById('profile-display-tier');

    if (avatarEl) avatarEl.innerText = u.fullname ? u.fullname.charAt(0).toUpperCase() : 'U';
    if (nameEl) nameEl.innerText = u.fullname || 'Thành viên Beestyle';
    if (tierEl) tierEl.innerText = u.tier || 'VIP Gold';

    // Render my orders tab
    this.renderProfileOrders();

    // Switch to requested tab
    this.switchProfileTab(tab);

    modal.classList.remove('hidden');
    lucide.createIcons();
  },

  closeProfileModal: function() {
    document.getElementById('profile-modal')?.classList.add('hidden');
  },

  switchProfileTab: function(tabName) {
    const tabs = ['info', 'password', 'orders', 'address'];
    tabs.forEach(t => {
      const btn = document.getElementById(`profile-tab-btn-${t}`);
      const pane = document.getElementById(`profile-tab-pane-${t}`);
      if (btn) {
        if (t === tabName) {
          btn.className = 'w-full text-left px-4 py-3 rounded-lg bg-neutral-900 text-white font-semibold text-xs flex items-center gap-2.5 transition-all shadow-sm';
        } else {
          btn.className = 'w-full text-left px-4 py-3 rounded-lg text-neutral-600 hover:bg-neutral-100 font-medium text-xs flex items-center gap-2.5 transition-all';
        }
      }
      if (pane) {
        if (t === tabName) {
          pane.classList.remove('hidden');
        } else {
          pane.classList.add('hidden');
        }
      }
    });
    lucide.createIcons();
  },

  updateProfile: function(e) {
    e?.preventDefault();
    if (!this.currentUser) return;

    const fullname = document.getElementById('profile-fullname')?.value?.trim();
    const phone = document.getElementById('profile-phone')?.value?.trim();
    const gender = document.getElementById('profile-gender')?.value;
    const birthday = document.getElementById('profile-birthday')?.value;
    const address = document.getElementById('profile-address')?.value?.trim() || document.getElementById('profile-address-detail')?.value?.trim();
    const bank_name = document.getElementById('profile-bank-name')?.value?.trim();
    const bank_account = document.getElementById('profile-bank-account')?.value?.trim();
    const user_bank_name = document.getElementById('profile-user-bank-name')?.value?.trim();

    if (!fullname) {
      this.showToast('Vui lòng nhập họ và tên!', 'error');
      return;
    }

    this.currentUser.fullname = fullname;
    this.currentUser.phone = phone;
    this.currentUser.phone_number = phone;
    this.currentUser.gender = gender;
    this.currentUser.birthday = birthday;
    if (address) this.currentUser.address = address;
    if (bank_name) this.currentUser.bank_name = bank_name;
    if (bank_account) this.currentUser.bank_account = bank_account;
    if (user_bank_name) this.currentUser.user_bank_name = user_bank_name;

    this.saveUser();
    this.showToast('Cập nhật hồ sơ tài khoản thành công!');

    // Update displays in header and profile modal summary
    this.renderAuthStatus();
    const nameEl = document.getElementById('profile-display-name');
    const avatarEl = document.getElementById('profile-avatar-letter');
    if (nameEl) nameEl.innerText = fullname;
    if (avatarEl) avatarEl.innerText = fullname.charAt(0).toUpperCase();
  },

  changePassword: function(e) {
    e?.preventDefault();
    if (!this.currentUser) return;

    const currentPass = document.getElementById('pwd-current')?.value;
    const newPass = document.getElementById('pwd-new')?.value;
    const confirmPass = document.getElementById('pwd-confirm')?.value;

    const storedPass = this.currentUser.password || '123456';

    if (currentPass !== storedPass) {
      this.showToast('Mật khẩu hiện tại không chính xác!', 'error');
      return;
    }

    if (!newPass || newPass.length < 6) {
      this.showToast('Mật khẩu mới phải chứa ít nhất 6 ký tự!', 'error');
      return;
    }

    if (newPass !== confirmPass) {
      this.showToast('Xác nhận mật khẩu mới không trùng khớp!', 'error');
      return;
    }

    if (newPass === currentPass) {
      this.showToast('Mật khẩu mới không được giống mật khẩu cũ!', 'error');
      return;
    }

    this.currentUser.password = newPass;
    this.currentUser.is_change_password = 1;
    this.saveUser();

    // Clear password inputs
    const p1 = document.getElementById('pwd-current');
    const p2 = document.getElementById('pwd-new');
    const p3 = document.getElementById('pwd-confirm');
    if (p1) p1.value = '';
    if (p2) p2.value = '';
    if (p3) p3.value = '';

    this.showToast('Đổi mật khẩu thành công! Hãy ghi nhớ mật khẩu mới.');
  },

  togglePasswordVisibility: function(inputId, iconBtn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      iconBtn.innerHTML = '<i data-lucide="eye-off" class="w-4 h-4 text-neutral-600"></i>';
    } else {
      input.type = 'password';
      iconBtn.innerHTML = '<i data-lucide="eye" class="w-4 h-4 text-neutral-400"></i>';
    }
    lucide.createIcons();
  },

  // ================= VERIFIED BUYER, REVIEW & ORDER HELPERS =================
  getOrders: function() {
    try {
      const stored = localStorage.getItem('beestyle_orders');
      if (stored) return JSON.parse(stored);
    } catch(e) {}
    return BeeDB.orders || [];
  },

  getRefunds: function() {
    try {
      const stored = localStorage.getItem('beestyle_refunds');
      if (stored) return JSON.parse(stored);
    } catch(e) {}
    return BeeDB.refunds || [];
  },

  saveRefunds: function(list) {
    localStorage.setItem('beestyle_refunds', JSON.stringify(list));
  },

  getReviews: function() {
    try {
      const stored = localStorage.getItem('beestyle_reviews');
      if (stored) return JSON.parse(stored);
    } catch(e) {}
    return BeeDB.reviews || [];
  },

  saveReviews: function(list) {
    BeeDB.reviews = list;
    localStorage.setItem('beestyle_reviews', JSON.stringify(list));
  },

  getUserReviewedItems: function() {
    try {
      const stored = localStorage.getItem('beestyle_user_reviewed_items');
      if (stored) return JSON.parse(stored);
    } catch(e) {}
    return [];
  },

  saveUserReviewedItems: function(list) {
    localStorage.setItem('beestyle_user_reviewed_items', JSON.stringify(list));
  },

  hasUserReviewedItem: function(productId, orderCode) {
    const list = this.getUserReviewedItems();
    return list.includes(`${orderCode}_${productId}`);
  },

  // ================= REVIEW MODAL LOGIC & STATE =================
  _tempReviewRating: 5,
  _tempReviewImages: [],
  _tempReviewProduct: null,

  openProductReviewModal: function(productId, orderCode, variantName, productName, productThumb) {
    if (!this.currentUser) {
      this.showToast('Vui lòng đăng nhập để đánh giá sản phẩm!', 'error');
      this.openAuthModal('login');
      return;
    }

    const p = BeeDB.products.find(item => item.id === productId);
    const modal = document.getElementById('product-review-modal');
    if (!modal) return;

    this._tempReviewProduct = {
      productId: productId,
      orderCode: orderCode,
      variantName: variantName || 'Mặc định',
      productName: productName || (p ? p.name : 'Sản phẩm Atelier'),
      thumbnail: productThumb || (p ? p.thumbnail : 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=300')
    };

    this._tempReviewRating = 5;
    this._tempReviewImages = [];

    // Populate modal DOM
    const thumbEl = document.getElementById('review-modal-product-thumb');
    const nameEl = document.getElementById('review-modal-product-name');
    const variantEl = document.getElementById('review-modal-product-variant');
    const orderEl = document.getElementById('review-modal-order-code');
    const textEl = document.getElementById('review-modal-comment');
    const previewContainer = document.getElementById('review-modal-images-preview');
    const fileInput = document.getElementById('review-modal-images-input');

    if (thumbEl) thumbEl.src = this._tempReviewProduct.thumbnail;
    if (nameEl) nameEl.innerText = this._tempReviewProduct.productName;
    if (variantEl) variantEl.innerText = `Phân loại: ${this._tempReviewProduct.variantName}`;
    if (orderEl) orderEl.innerText = `Đơn hàng: #${this._tempReviewProduct.orderCode}`;
    if (textEl) textEl.value = '';
    if (previewContainer) previewContainer.replaceChildren();
    if (fileInput) fileInput.value = '';

    this.setReviewRating(5);

    modal.classList.remove('hidden');
    lucide.createIcons();
  },

  closeProductReviewModal: function() {
    document.getElementById('product-review-modal')?.classList.add('hidden');
  },

  setReviewRating: function(rating) {
    this._tempReviewRating = rating;
    const starButtons = document.querySelectorAll('#review-star-selector button');
    const ratingLabel = document.getElementById('review-rating-label');
    const labels = {
      1: 'Rất không hài lòng (1 sao)',
      2: 'Chưa hài lòng (2 sao)',
      3: 'Bình thường / Tạm ổn (3 sao)',
      4: 'Hài lòng (4 sao)',
      5: 'Tuyệt vời / Rất hài lòng (5 sao)'
    };
    if (ratingLabel) ratingLabel.innerText = labels[rating] || `${rating} sao`;

    starButtons.forEach((btn, idx) => {
      const starIcon = btn.querySelector('svg') || btn.querySelector('i');
      if (idx < rating) {
        btn.className = 'text-amber-500 hover:scale-110 transition-transform p-1';
        if (starIcon) {
          starIcon.classList.add('fill-amber-400', 'text-amber-500');
          starIcon.classList.remove('text-neutral-300');
        }
      } else {
        btn.className = 'text-neutral-300 hover:scale-110 transition-transform p-1';
        if (starIcon) {
          starIcon.classList.remove('fill-amber-400', 'text-amber-500');
          starIcon.classList.add('text-neutral-300');
        }
      }
    });
  },

  handleReviewModalImages: function(input) {
    if (!input.files || input.files.length === 0) return;
    const previewContainer = document.getElementById('review-modal-images-preview');
    if (!previewContainer) return;

    Array.from(input.files).forEach((file) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const url = e.target.result;
        this._tempReviewImages.push(url);

        const wrap = document.createElement('div');
        wrap.className = 'relative w-16 h-20 rounded-lg border border-neutral-300 overflow-hidden shrink-0 group shadow-sm';
        wrap.innerHTML = `
          <img src="${url}" class="w-full h-full object-cover">
          <button type="button" class="absolute top-1 right-1 bg-neutral-900/80 hover:bg-neutral-950 text-white w-4 h-4 flex items-center justify-center text-[10px] rounded-full opacity-0 group-hover:opacity-100 transition-opacity">✕</button>
        `;
        wrap.querySelector('button').onclick = () => {
          this._tempReviewImages = this._tempReviewImages.filter(img => img !== url);
          wrap.remove();
        };
        previewContainer.appendChild(wrap);
      };
      reader.readAsDataURL(file);
    });
  },

  submitProductReview: function(e) {
    e?.preventDefault();
    if (!this._tempReviewProduct) return;

    const commentText = document.getElementById('review-modal-comment')?.value?.trim();
    if (!commentText) {
      this.showToast('Vui lòng nhập nhận xét của bạn về sản phẩm!', 'error');
      document.getElementById('review-modal-comment')?.focus();
      return;
    }

    const { productId, orderCode, variantName, productName } = this._tempReviewProduct;
    const user = this.currentUser || { fullname: 'Khách hàng Atelier' };

    const newReview = {
      id: Date.now(),
      product_id: productId,
      order_code: orderCode,
      variant_name: variantName,
      user_fullname: user.fullname || 'Nguyễn Văn An',
      rating: this._tempReviewRating || 5,
      review_text: commentText,
      photos: [...this._tempReviewImages],
      created_at: new Date().toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' })
    };

    // Save to reviews list
    const reviews = this.getReviews();
    reviews.unshift(newReview);
    this.saveReviews(reviews);

    // Mark product in order as reviewed
    const reviewedItems = this.getUserReviewedItems();
    if (!reviewedItems.includes(`${orderCode}_${productId}`)) {
      reviewedItems.push(`${orderCode}_${productId}`);
      this.saveUserReviewedItems(reviewedItems);
    }

    this.closeProductReviewModal();
    this.showToast(`Đã gửi đánh giá thành công cho sản phẩm "${productName}"! Cảm ơn bạn.`);

    // Re-render profile orders if open
    this.renderProfileOrders();

    // If on product detail page, re-render reviews
    if (window.ProductPage && typeof window.ProductPage.renderReviews === 'function') {
      window.ProductPage.renderReviews();
    }
  },

  /**
   * Kiểm tra khách hàng đã từng mua sản phẩm này và đơn hàng đã hoàn tất/giao thành công hay chưa
   */
  hasPurchasedProduct: function(productId) {
    const user = this.currentUser;
    if (!user) {
      return { purchased: false, reason: 'not_logged_in', message: 'Vui lòng đăng nhập để kiểm tra điều kiện đánh giá!' };
    }

    const orders = this.getOrders().filter(o => o.user_id === user.id || o.email === user.email || o.phone_number === user.phone || o.phone_number === user.phone_number);

    for (let order of orders) {
      const isDelivered = order.order_status_id === 5 || (order.status_name && (order.status_name.toLowerCase().includes('hoàn thành') || order.status_name.toLowerCase().includes('delivered') || order.status_name.toLowerCase().includes('giao')));
      if (isDelivered) {
        const item = (order.items || []).find(it => it.product_id === productId);
        if (item) {
          return {
            purchased: true,
            orderCode: order.code,
            orderId: order.id,
            deliveredAt: order.delivered_at || order.created_at,
            item: item
          };
        }
      }
    }

    return {
      purchased: false,
      reason: 'not_purchased',
      message: 'Chỉ khách hàng đã đặt mua và nhận hàng thành công mới có thể gửi đánh giá sản phẩm này.'
    };
  },

  // ================= PROFILE & PASSWORD OPERATIONS =================
  openProfileModal: function(tab = 'info') {
    if (!this.currentUser) {
      this.showToast('Vui lòng đăng nhập để xem thông tin tài khoản!', 'error');
      this.openAuthModal('login');
      return;
    }
    const modal = document.getElementById('profile-modal');
    if (!modal) return;

    // Populate user profile data in fields
    const u = this.currentUser;
    const fnInput = document.getElementById('profile-fullname');
    const emInput = document.getElementById('profile-email');
    const phInput = document.getElementById('profile-phone');
    const gdInput = document.getElementById('profile-gender');
    const bdInput = document.getElementById('profile-birthday');
    const adInput = document.getElementById('profile-address');
    const adDetail = document.getElementById('profile-address-detail');
    const bnInput = document.getElementById('profile-bank-name');
    const baInput = document.getElementById('profile-bank-account');
    const buInput = document.getElementById('profile-user-bank-name');

    if (fnInput) fnInput.value = u.fullname || '';
    if (emInput) emInput.value = u.email || '';
    if (phInput) phInput.value = u.phone_number || u.phone || '';
    if (gdInput) gdInput.value = u.gender || 'male';
    if (bdInput) bdInput.value = u.birthday || '1995-08-15';
    if (adInput) adInput.value = u.address || '88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh';
    if (adDetail) adDetail.value = u.address || '88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh';
    if (bnInput) bnInput.value = u.bank_name || 'Vietcombank';
    if (baInput) baInput.value = u.bank_account || '0071001234567';
    if (buInput) buInput.value = u.user_bank_name || (u.fullname || '').toUpperCase();

    // Summary elements
    const avatarEl = document.getElementById('profile-avatar-letter');
    const nameEl = document.getElementById('profile-display-name');
    const tierEl = document.getElementById('profile-display-tier');

    if (avatarEl) avatarEl.innerText = u.fullname ? u.fullname.charAt(0).toUpperCase() : 'U';
    if (nameEl) nameEl.innerText = u.fullname || 'Thành viên Beestyle';
    if (tierEl) tierEl.innerText = u.tier || 'VIP Gold';

    // Render my orders & refunds tab
    this.renderProfileOrders();
    this.renderProfileRefunds();

    // Switch to requested tab
    this.switchProfileTab(tab);

    modal.classList.remove('hidden');
    lucide.createIcons();
  },

  closeProfileModal: function() {
    document.getElementById('profile-modal')?.classList.add('hidden');
  },

  switchProfileTab: function(tabName) {
    const tabs = ['info', 'password', 'orders', 'address', 'refunds'];
    tabs.forEach(t => {
      const btn = document.getElementById(`profile-tab-btn-${t}`);
      const pane = document.getElementById(`profile-tab-pane-${t}`);
      if (btn) {
        if (t === tabName) {
          btn.className = 'w-full text-left px-4 py-3 rounded-lg bg-neutral-900 text-white font-semibold text-xs flex items-center gap-2.5 transition-all shadow-sm';
        } else {
          btn.className = 'w-full text-left px-4 py-3 rounded-lg text-neutral-600 hover:bg-neutral-100 font-medium text-xs flex items-center gap-2.5 transition-all';
        }
      }
      if (pane) {
        if (t === tabName) {
          pane.classList.remove('hidden');
        } else {
          pane.classList.add('hidden');
        }
      }
    });
    lucide.createIcons();
  },

  renderProfileOrders: function() {
    const container = document.getElementById('profile-orders-list');
    if (!container) return;

    const userOrders = this.getOrders();
    if (userOrders.length === 0) {
      container.innerHTML = `
        <div class="text-center py-10 text-neutral-400">
          <i data-lucide="package" class="w-12 h-12 stroke-1 mx-auto mb-2 opacity-50"></i>
          <p class="text-sm">Bạn chưa có đơn hàng nào.</p>
        </div>
      `;
      lucide.createIcons();
      return;
    }

    container.innerHTML = userOrders.map(order => {
      const isDelivered = order.order_status_id === 5 || (order.status_name && order.status_name.toLowerCase().includes('hoàn thành'));
      
      const itemsHtml = (order.items || []).map(it => {
        const alreadyReviewed = this.hasUserReviewedItem(it.product_id, order.code);
        const safeName = (it.name || '').replace(/'/g, "\\'");
        const safeVariant = (it.name_variant || 'Mặc định').replace(/'/g, "\\'");
        const safeThumb = (it.thumbnail || '').replace(/'/g, "\\'");

        const reviewBtn = isDelivered ? (
          alreadyReviewed
            ? `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-semibold flex items-center gap-1">
                 <i data-lucide="badge-check" class="w-3 h-3 text-emerald-600"></i> ✓ Đã Đánh Giá
               </span>`
            : `<button onclick="BeeCore.openProductReviewModal(${it.product_id}, '${order.code}', '${safeVariant}', '${safeName}', '${safeThumb}')" class="px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-300 rounded text-[10px] font-semibold hover:bg-amber-100 flex items-center gap-1 transition-colors shadow-sm">
                 <i data-lucide="star" class="w-3 h-3 fill-amber-500 text-amber-500"></i> ⭐ Đánh Giá
               </button>`
        ) : '';

        return `
          <div class="flex items-center justify-between text-xs text-neutral-700 py-2 border-b border-neutral-100 last:border-0">
            <div class="flex items-center gap-2.5 truncate pr-2">
              <img src="${it.thumbnail || 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=100'}" class="w-10 h-12 rounded-lg object-cover border border-neutral-200 shrink-0">
              <div class="min-w-0">
                <a href="product-detail.html?id=${it.product_id}" class="font-medium text-neutral-900 hover:underline truncate block">${it.name}</a>
                <span class="text-[11px] text-neutral-400">Phân loại: ${it.name_variant || 'Mặc định'} × ${it.quantity}</span>
              </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
              <span class="font-semibold text-neutral-900">${this.formatMoney(it.price * it.quantity)}</span>
              ${reviewBtn}
            </div>
          </div>
        `;
      }).join('');

      return `
        <div class="border border-neutral-200 rounded-xl p-4 bg-white hover:border-neutral-300 transition-all shadow-sm">
          <div class="flex flex-wrap justify-between items-start gap-2 border-b border-neutral-100 pb-3">
            <div>
              <div class="flex items-center gap-2">
                <strong class="font-mono text-neutral-900 font-bold">${order.code}</strong>
                <span class="px-2.5 py-0.5 ${isDelivered ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'} rounded-full text-[10px] font-bold uppercase">${order.status_name}</span>
              </div>
              <p class="text-[11px] text-neutral-400 mt-0.5">Đặt ngày: ${order.created_at}</p>
            </div>
            <div class="text-right">
              <span class="text-[10px] text-neutral-400 uppercase tracking-wider block">Tổng thanh toán</span>
              <strong class="font-serif-luxury text-sm font-bold text-neutral-900">${this.formatMoney(order.total_amount)}</strong>
            </div>
          </div>

          <div class="py-2 space-y-1">
            ${itemsHtml}
          </div>

          <div class="flex flex-wrap justify-between items-center pt-3 border-t border-neutral-100 text-xs gap-2">
            <span class="text-[11px] text-neutral-500">PTTT: ${order.payment_name || 'COD'}</span>
            <div class="flex items-center gap-2">
              ${isDelivered ? `
                <button onclick="BeeCore.closeProfileModal(); BeeCore.openRefundModal('${order.code}')" class="px-3 py-1 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 rounded text-[11px] font-semibold flex items-center gap-1">
                  <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Đổi Trả / Hoàn Tiền
                </button>
              ` : ''}
              <button onclick="BeeCore.closeProfileModal(); BeeCore.openOrderSuccess(BeeCore.getOrders().find(o => o.code === '${order.code}'))" class="px-3 py-1 bg-neutral-900 text-white rounded text-[11px] font-medium hover:bg-neutral-800 flex items-center gap-1">
                <i data-lucide="truck" class="w-3 h-3"></i> Theo Dõi Đơn
              </button>
            </div>
          </div>
        </div>
      `;
    }).join('');
    lucide.createIcons();
  },

  renderProfileRefunds: function() {
    const container = document.getElementById('profile-refunds-list');
    if (!container) return;

    const userRefunds = this.getRefunds();
    if (userRefunds.length === 0) {
      container.innerHTML = `
        <div class="text-center py-10 text-neutral-400">
          <i data-lucide="rotate-ccw" class="w-12 h-12 stroke-1 mx-auto mb-2 opacity-50"></i>
          <p class="text-sm">Bạn chưa có yêu cầu đổi trả hoặc hoàn tiền nào.</p>
        </div>
      `;
      lucide.createIcons();
      return;
    }

    container.innerHTML = userRefunds.map(ref => {
      const isDone = ref.status === 'completed';
      const isInspecting = ref.status === 'inspecting' || ref.status === 'pending';
      return `
        <div class="border border-neutral-200 rounded-xl p-4 bg-white hover:border-neutral-400 transition-all space-y-3">
          <div class="flex flex-wrap justify-between items-start gap-2 border-b border-neutral-100 pb-2.5">
            <div>
              <div class="flex items-center gap-2">
                <strong class="font-mono text-neutral-950 font-bold text-xs">${ref.refund_code}</strong>
                <span class="px-2 py-0.5 ${isDone ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800'} rounded text-[10px] font-bold uppercase">${ref.status_badge || ref.status}</span>
              </div>
              <p class="text-[11px] text-neutral-400 mt-0.5">Áp dụng cho đơn: <strong class="text-neutral-700 font-mono">${ref.order_code}</strong> — Tạo ngày: ${ref.created_at}</p>
            </div>
            <div class="text-right">
              <span class="text-[10px] text-neutral-400 uppercase tracking-wider block">Số tiền hoàn</span>
              <strong class="font-serif-luxury text-sm font-bold text-rose-700">${this.formatMoney(ref.refund_amount)}</strong>
            </div>
          </div>

          <div class="text-xs text-neutral-700 space-y-1 bg-neutral-50 p-2.5 rounded-lg border border-neutral-100">
            <p><span class="font-semibold text-neutral-900">Sản phẩm:</span> ${ref.product_name || 'Toàn bộ đơn hàng'}</p>
            <p><span class="font-semibold text-neutral-900">Lý do:</span> ${ref.reason}</p>
            <p><span class="font-semibold text-neutral-900">Tài khoản nhận:</span> ${ref.bank_name} - ${ref.bank_account} (${ref.bank_account_name || ref.user_bank_name})</p>
            ${ref.transaction_code ? `<p><span class="font-semibold text-emerald-800">Mã GD chuyển khoản:</span> <strong class="font-mono text-emerald-700">${ref.transaction_code}</strong></p>` : ''}
          </div>

          <!-- Video & Image attachments preview summary -->
          <div class="flex items-center justify-between pt-1 border-t border-neutral-100 text-xs">
            <div class="flex items-center gap-3 text-[11px] text-neutral-500">
              <span class="flex items-center gap-1 text-emerald-700 font-medium"><i data-lucide="video" class="w-3.5 h-3.5"></i> Video Unbox đính kèm</span>
              <span class="flex items-center gap-1 text-neutral-600"><i data-lucide="image" class="w-3.5 h-3.5"></i> ${(ref.images || []).length} ảnh minh chứng</span>
            </div>
            <button onclick="BeeCore.closeProfileModal(); BeeCore.openRefundTracking('${ref.refund_code}')" class="px-3 py-1 bg-neutral-900 text-white rounded text-[11px] font-semibold hover:bg-neutral-800 flex items-center gap-1">
              <i data-lucide="search" class="w-3 h-3"></i> Xem Tiến Trình
            </button>
          </div>
        </div>
      `;
    }).join('');
    lucide.createIcons();
  },

  clearCart: function() {
    this.cart = [];
    this.appliedCoupon = null;
    localStorage.removeItem('beestyle_cart');
    localStorage.removeItem('beestyle_coupon');
    this.updateCartBadge();
  },

  // ================= CHECKOUT OPERATIONS =================
  openCheckout: function() {
    if (!this.currentUser) {
      this.showToast('Vui lòng đăng nhập để tiến hành thanh toán!', 'error');
      this.openAuthModal('login');
      return;
    }

    if (this.cart.length === 0) {
      this.showToast('Túi mua hàng của bạn đang trống!', 'error');
      return;
    }

    this.toggleCart(false);
    window.location.href = 'checkout.html';
  },

  openOrderSuccess: function(order) {
    this.openOrderLookup('order', order?.code || '');
  },

  // ================= ORDER & REFUND LOOKUP MODAL =================
  openOrderLookup: function(tab = 'order', code = '') {
    const modal = document.getElementById('order-lookup-modal');
    if (!modal) return;

    this.switchLookupTab(tab);
    if (code) {
      if (tab === 'order') {
        const input = document.getElementById('order-search-code');
        if (input) input.value = code;
        this.searchOrder();
      } else {
        const input = document.getElementById('refund-search-code');
        if (input) input.value = code;
        this.searchRefund(code);
      }
    }

    modal.classList.remove('hidden');
    lucide.createIcons();
  },

  closeOrderLookup: function() {
    document.getElementById('order-lookup-modal')?.classList.add('hidden');
  },

  switchLookupTab: function(tab) {
    const orderBtn = document.getElementById('lookup-tab-btn-order');
    const refundBtn = document.getElementById('lookup-tab-btn-refund');
    const orderPane = document.getElementById('lookup-tab-pane-order');
    const refundPane = document.getElementById('lookup-tab-pane-refund');

    if (tab === 'order') {
      orderBtn?.classList.add('border-neutral-900', 'text-neutral-900', 'font-bold');
      orderBtn?.classList.remove('border-transparent', 'text-neutral-500');
      refundBtn?.classList.remove('border-neutral-900', 'text-neutral-900', 'font-bold');
      refundBtn?.classList.add('border-transparent', 'text-neutral-500');

      orderPane?.classList.remove('hidden');
      refundPane?.classList.add('hidden');
    } else {
      refundBtn?.classList.add('border-neutral-900', 'text-neutral-900', 'font-bold');
      refundBtn?.classList.remove('border-transparent', 'text-neutral-500');
      orderBtn?.classList.remove('border-neutral-900', 'text-neutral-900', 'font-bold');
      orderBtn?.classList.add('border-transparent', 'text-neutral-500');

      refundPane?.classList.remove('hidden');
      orderPane?.classList.add('hidden');
    }
    lucide.createIcons();
  },

  openRefundTracking: function(refundCode) {
    this.openOrderLookup('refund', refundCode);
  },

  searchOrder: function() {
    const code = document.getElementById('order-search-code')?.value?.trim().toUpperCase();
    if (!code) {
      this.showToast('Vui lòng nhập mã đơn hàng!', 'error');
      return;
    }
    const order = this.getOrders().find(o => o.code.toUpperCase() === code);
    if (order) {
      this.renderOrderDetails(order);
    } else {
      document.getElementById('order-lookup-result').innerHTML = `
        <div class="text-center py-8 text-neutral-500">
          <i data-lucide="package-x" class="w-12 h-12 stroke-1 mx-auto mb-2 text-rose-500 opacity-60"></i>
          <p class="font-serif-luxury text-base text-neutral-800">Không tìm thấy đơn hàng "${code}"</p>
          <p class="text-xs text-neutral-400 mt-1">Vui lòng kiểm tra lại mã đơn hàng hoặc đăng nhập để xem lịch sử.</p>
        </div>
      `;
      lucide.createIcons();
    }
  },

  renderOrderDetails: function(order) {
    const content = document.getElementById('order-lookup-result');
    if (!content) return;

    const isDelivered = order.order_status_id === 5 || (order.status_name && order.status_name.toLowerCase().includes('hoàn thành'));

    content.innerHTML = `
      <div class="bg-neutral-50 p-4 rounded-xl border border-neutral-200 space-y-4">
        <div class="flex justify-between items-start border-b border-neutral-200 pb-3">
          <div>
            <span class="text-[10px] tracking-widest uppercase text-neutral-500 block font-semibold">MÃ ĐƠN HÀNG</span>
            <strong class="font-mono text-sm text-neutral-950">${order.code}</strong>
            <p class="text-xs text-neutral-500 mt-0.5">Đặt lúc: ${order.created_at}</p>
          </div>
          <span class="px-3 py-1 ${isDelivered ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'} rounded-full text-xs font-semibold">${order.status_name}</span>
        </div>

        <div>
          <span class="text-[10px] tracking-wider uppercase text-neutral-500 font-semibold block mb-2">TIẾN TRÌNH VẬN CHUYỂN</span>
          <div class="space-y-3 pl-2 border-l-2 border-neutral-300">
            ${(order.timeline || []).map(t => `
              <div class="relative pl-4">
                <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full ${t.done ? (t.current ? 'bg-amber-600 ring-4 ring-amber-100' : 'bg-neutral-900') : 'bg-neutral-300'}"></div>
                <p class="text-xs font-semibold ${t.current ? 'text-amber-800' : 'text-neutral-900'}">${t.status} <span class="text-[10px] text-neutral-400 font-normal">(${t.time})</span></p>
                <p class="text-[11px] text-neutral-500 font-light">${t.note}</p>
              </div>
            `).join('')}
          </div>
        </div>

        <div class="border-t border-neutral-200 pt-3">
          <span class="text-[10px] tracking-wider uppercase text-neutral-500 font-semibold block mb-2">SẢN PHẨM (${order.items?.length || 0})</span>
          <div class="space-y-2">
            ${(order.items || []).map(it => `
              <div class="flex justify-between items-center text-xs">
                <span>${it.name} <span class="text-neutral-400 font-normal">(${it.name_variant || 'Mặc định'}) × ${it.quantity}</span></span>
                <div class="flex items-center gap-2">
                  <span class="font-serif-luxury font-bold">${this.formatMoney(it.price * it.quantity)}</span>
                  ${isDelivered ? `
                    <button onclick="BeeCore.closeOrderLookup(); BeeCore.openProductReviewModal(${it.product_id}, '${order.code}', '${(it.name_variant || 'Mặc định').replace(/'/g, "\\'")}', '${(it.name || '').replace(/'/g, "\\'")}', '${(it.thumbnail || '').replace(/'/g, "\\'")}')" class="px-2 py-0.5 bg-amber-50 text-amber-900 border border-amber-300 rounded text-[10px] font-semibold hover:bg-amber-100 flex items-center gap-1 transition-colors">⭐ Đánh giá</button>
                  ` : ''}
                </div>
              </div>
            `).join('')}
          </div>
          <div class="flex justify-between items-center text-xs font-bold border-t border-neutral-200 pt-2 mt-2 text-neutral-900">
            <span>Tổng giá trị:</span>
            <span class="font-serif-luxury text-base text-amber-900">${this.formatMoney(order.total_amount)}</span>
          </div>
        </div>

        ${isDelivered ? `
          <div class="pt-2 border-t border-neutral-200 flex justify-end">
            <button onclick="BeeCore.closeOrderLookup(); BeeCore.openRefundModal('${order.code}')" class="px-4 py-2 bg-amber-50 text-amber-900 border border-amber-300 rounded text-xs font-semibold hover:bg-amber-100 flex items-center gap-1.5 shadow-sm">
              <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Yêu Cầu Đổi Trả / Hoàn Tiền Đơn Này
            </button>
          </div>
        ` : ''}
      </div>
    `;
    lucide.createIcons();
  },

  searchRefund: function(customCode = null) {
    const code = customCode || document.getElementById('refund-search-code')?.value?.trim().toUpperCase();
    if (!code) {
      this.showToast('Vui lòng nhập mã hoàn tiền (REF-...) hoặc mã đơn hàng!', 'error');
      return;
    }
    const refund = this.getRefunds().find(r => r.refund_code.toUpperCase() === code || r.order_code.toUpperCase() === code);
    if (refund) {
      this.renderRefundTrackingDetails(refund);
    } else {
      document.getElementById('refund-lookup-result').innerHTML = `
        <div class="text-center py-8 text-neutral-500">
          <i data-lucide="rotate-ccw" class="w-12 h-12 stroke-1 mx-auto mb-2 text-amber-600 opacity-60"></i>
          <p class="font-serif-luxury text-base text-neutral-800">Không tìm thấy yêu cầu hoàn tiền nào khớp với "${code}"</p>
          <p class="text-xs text-neutral-400 mt-1">Vui lòng kiểm tra lại mã yêu cầu (ví dụ: REF-2026-08101) hoặc mã đơn hàng.</p>
        </div>
      `;
      lucide.createIcons();
    }
  },

  renderRefundTrackingDetails: function(refund) {
    const content = document.getElementById('refund-lookup-result');
    if (!content) return;

    const isDone = refund.status === 'completed';

    content.innerHTML = `
      <div class="bg-neutral-50 p-5 rounded-xl border border-neutral-200 space-y-4">
        <!-- Header Info -->
        <div class="flex flex-wrap justify-between items-start border-b border-neutral-200 pb-3 gap-2">
          <div>
            <span class="text-[10px] tracking-widest uppercase text-amber-800 font-bold block">YÊU CẦU HOÀN TIỀN / RMA</span>
            <strong class="font-mono text-sm text-neutral-950">${refund.refund_code}</strong>
            <p class="text-xs text-neutral-500 mt-0.5">Đơn hàng gốc: <strong class="text-neutral-800 font-mono">${refund.order_code}</strong></p>
          </div>
          <div class="text-right">
            <span class="px-3 py-1 ${isDone ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800'} rounded-full text-xs font-bold uppercase">${refund.status_badge || refund.status}</span>
            <span class="font-serif-luxury text-base font-bold text-rose-700 block mt-1">${this.formatMoney(refund.refund_amount)}</span>
          </div>
        </div>

        <!-- Refund Stepper Timeline -->
        <div>
          <span class="text-[10px] tracking-wider uppercase text-neutral-600 font-semibold block mb-3">TIẾN TRÌNH XỬ LÝ HOÀN TIỀN</span>
          <div class="space-y-4 pl-2 border-l-2 border-amber-300">
            ${(refund.timeline || []).map(t => `
              <div class="relative pl-4">
                <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full ${t.done ? (t.current ? 'bg-amber-600 ring-4 ring-amber-100' : 'bg-emerald-600') : 'bg-neutral-300'}"></div>
                <p class="text-xs font-semibold ${t.current ? 'text-amber-800' : (t.done ? 'text-emerald-900' : 'text-neutral-400')}">
                  ${t.title} <span class="text-[10px] text-neutral-400 font-normal">(${t.time})</span>
                </p>
                <p class="text-[11px] text-neutral-600 font-light mt-0.5">${t.desc}</p>
              </div>
            `).join('')}
          </div>
        </div>

        <!-- Attached Proofs (Unbox Video & Images) -->
        <div class="border-t border-neutral-200 pt-3 space-y-2">
          <span class="text-[10px] tracking-wider uppercase text-neutral-600 font-semibold block">CHỨNG CỨ ĐÃ CUNG CẤP</span>
          
          <!-- Unbox Video Stream Box -->
          ${refund.video_proof ? `
            <div class="p-3 bg-white rounded-lg border border-neutral-200 space-y-2">
              <div class="flex items-center justify-between text-xs font-semibold text-neutral-900">
                <span class="flex items-center gap-1.5 text-emerald-700">
                  <i data-lucide="video" class="w-4 h-4"></i> Video Unbox Mở Hộp Sản Phẩm
                </span>
                <span class="text-[10px] text-neutral-400 font-mono">${refund.video_name || 'video_unbox.mp4'}</span>
              </div>
              <div class="aspect-video bg-neutral-900 rounded overflow-hidden max-h-48">
                <video src="${refund.video_proof}" controls class="w-full h-full object-contain" poster="${refund.images?.[0] || ''}">
                  Trình duyệt không hỗ trợ xem video.
                </video>
              </div>
            </div>
          ` : `
            <p class="text-xs text-amber-700 bg-amber-50 p-2 rounded">Chưa có video unbox đính kèm.</p>
          `}

          <!-- Images Proof Grid -->
          ${(refund.images && refund.images.length > 0) ? `
            <div class="flex gap-2 overflow-x-auto py-1">
              ${refund.images.map(img => `
                <img src="${img}" class="w-16 h-20 object-cover rounded border border-neutral-300 shrink-0 cursor-pointer" onclick="window.open('${img}', '_blank')">
              `).join('')}
            </div>
          ` : ''}
        </div>

        <!-- Bank Account & Transaction Info -->
        <div class="border-t border-neutral-200 pt-3 bg-amber-50/70 p-3 rounded-lg text-xs space-y-1">
          <div class="flex justify-between">
            <span class="text-neutral-600">Ngân hàng thụ hưởng:</span>
            <strong class="text-neutral-900">${refund.bank_name} - ${refund.bank_account}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-600">Chủ tài khoản:</span>
            <strong class="text-neutral-900 uppercase">${refund.bank_account_name || refund.user_bank_name}</strong>
          </div>
          ${refund.transaction_code ? `
            <div class="flex justify-between border-t border-amber-200 pt-1 mt-1 text-emerald-800 font-bold">
              <span>Mã giao dịch ngân hàng (Ủy nhiệm chi):</span>
              <span class="font-mono">${refund.transaction_code}</span>
            </div>
          ` : `
            <div class="text-[11px] text-neutral-500 pt-1 italic">
              * Sau khi chuyên viên QC thẩm định video unbox & nhận lại hàng, số tiền sẽ được chuyển tự động vào STK trên trong 24h.
            </div>
          `}
        </div>
      </div>
    `;
    lucide.createIcons();
  },

  // ================= REFUND & RETURN OPERATIONS =================
  _tempRefundImages: [],
  _tempRefundVideo: null,

  openRefundModal: function(prefilledOrderCode = null) {
    if (!this.currentUser) {
      this.showToast('Vui lòng đăng nhập để tạo yêu cầu đổi trả / hoàn tiền!', 'error');
      this.openAuthModal('login');
      return;
    }

    const modal = document.getElementById('refund-modal');
    if (!modal) return;

    // Reset temp uploads
    this._tempRefundImages = [];
    this._tempRefundVideo = null;
    document.getElementById('refund-images-preview')?.replaceChildren();
    const vidBox = document.getElementById('refund-video-preview-box');
    if (vidBox) vidBox.classList.add('hidden');

    // Populate order dropdown
    const orderSelect = document.getElementById('refund-order-select');
    const userOrders = this.getOrders();
    const deliveredOrders = userOrders.filter(o => o.order_status_id === 5 || (o.status_name && o.status_name.toLowerCase().includes('hoàn thành')));

    if (orderSelect) {
      if (deliveredOrders.length === 0) {
        orderSelect.innerHTML = `<option value="">(Không có đơn hàng đã giao - Vui lòng nhập mã)</option>`;
      } else {
        orderSelect.innerHTML = deliveredOrders.map(o => `
          <option value="${o.code}" ${prefilledOrderCode === o.code ? 'selected' : ''}>
            Đơn #${o.code} - ${this.formatMoney(o.total_amount)} (${o.items?.map(it => it.name).join(', ')})
          </option>
        `).join('');
      }
    }

    // Prefill bank account from user profile
    const u = this.currentUser;
    const bName = document.getElementById('refund-bank-name');
    const bAcc = document.getElementById('refund-bank-account');
    const bUser = document.getElementById('refund-user-bank-name');
    const bPhone = document.getElementById('refund-phone');

    if (bName) bName.value = u.bank_name || 'Vietcombank';
    if (bAcc) bAcc.value = u.bank_account || '0071001234567';
    if (bUser) bUser.value = (u.user_bank_name || u.fullname || 'NGUYEN VAN AN').toUpperCase();
    if (bPhone) bPhone.value = u.phone_number || u.phone || '0988776655';

    modal.classList.remove('hidden');
    lucide.createIcons();
  },

  closeRefundModal: function() {
    document.getElementById('refund-modal')?.classList.add('hidden');
  },

  handleRefundImagesSelect: function(input) {
    if (!input.files || input.files.length === 0) return;
    const previewContainer = document.getElementById('refund-images-preview');
    if (!previewContainer) return;

    Array.from(input.files).forEach((file) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        const url = e.target.result;
        this._tempRefundImages.push(url);
        
        const wrap = document.createElement('div');
        wrap.className = 'relative w-16 h-20 rounded border border-neutral-300 overflow-hidden shrink-0 group';
        wrap.innerHTML = `
          <img src="${url}" class="w-full h-full object-cover">
          <button type="button" class="absolute top-0 right-0 bg-neutral-900/80 text-white w-4 h-4 flex items-center justify-center text-[10px] rounded-bl opacity-0 group-hover:opacity-100 transition-opacity">×</button>
        `;
        wrap.querySelector('button').onclick = () => {
          this._tempRefundImages = this._tempRefundImages.filter(img => img !== url);
          wrap.remove();
        };
        previewContainer.appendChild(wrap);
      };
      reader.readAsDataURL(file);
    });
  },

  handleRefundVideoSelect: function(input) {
    if (!input.files || input.files.length === 0) return;
    const file = input.files[0];
    const vidBox = document.getElementById('refund-video-preview-box');
    const vidPlayer = document.getElementById('refund-video-player');
    const vidName = document.getElementById('refund-video-filename');
    if (!vidBox || !vidPlayer) return;

    const objUrl = URL.createObjectURL(file);
    this._tempRefundVideo = {
      url: objUrl,
      name: file.name,
      size: (file.size / (1024 * 1024)).toFixed(1) + ' MB'
    };

    vidPlayer.src = objUrl;
    if (vidName) vidName.innerText = `${file.name} (${this._tempRefundVideo.size})`;
    vidBox.classList.remove('hidden');
    lucide.createIcons();
  },

  removeRefundVideo: function() {
    this._tempRefundVideo = null;
    const vidBox = document.getElementById('refund-video-preview-box');
    const vidPlayer = document.getElementById('refund-video-player');
    const fileInput = document.getElementById('refund-video-input');
    if (vidBox) vidBox.classList.add('hidden');
    if (vidPlayer) vidPlayer.src = '';
    if (fileInput) fileInput.value = '';
  },

  submitRefund: function(e) {
    e?.preventDefault();

    const orderCode = document.getElementById('refund-order-select')?.value || document.getElementById('refund-order-code')?.value?.trim().toUpperCase();
    const reason = document.getElementById('refund-reason')?.value || 'Đổi size do rộng/chật';
    const notes = document.getElementById('refund-notes')?.value?.trim();
    const bankName = document.getElementById('refund-bank-name')?.value?.trim();
    const bankAccount = document.getElementById('refund-bank-account')?.value?.trim();
    const userBankName = document.getElementById('refund-user-bank-name')?.value?.trim();
    const phone = document.getElementById('refund-phone')?.value?.trim();

    // 1. Validate Order
    if (!orderCode) {
      this.showToast('Vui lòng chọn hoặc nhập mã đơn hàng cần hoàn trả!', 'error');
      return;
    }

    const order = this.getOrders().find(o => o.code.toUpperCase() === orderCode) || {
      id: 1001,
      code: orderCode,
      total_amount: 1360000,
      items: [{ name: 'Sản phẩm may đo Beestyle' }]
    };

    // 2. VALIDATION CHÍNH XÁC: Bắt buộc phải có HÌNH ẢNH sản phẩm/tem mác
    if (!this._tempRefundImages || this._tempRefundImages.length === 0) {
      this.showToast('⚠️ Bắt buộc: Vui lòng tải lên ít nhất 1 ảnh chụp sản phẩm hoặc tem mác để làm chứng cứ!', 'error');
      document.getElementById('refund-images-input')?.focus();
      return;
    }

    // 3. VALIDATION CHÍNH XÁC: Bắt buộc phải có VIDEO UNBOX mở hộp
    if (!this._tempRefundVideo || !this._tempRefundVideo.url) {
      this.showToast('⚠️ Bắt buộc: Vui lòng tải lên Video Clip Unbox mở hộp sản phẩm để nhân viên QC đối soát quyền lợi!', 'error');
      document.getElementById('refund-video-input')?.focus();
      return;
    }

    // 4. Validate Bank info
    if (!bankAccount || !userBankName) {
      this.showToast('Vui lòng nhập đầy đủ Số tài khoản & Tên chủ thẻ ngân hàng nhận tiền hoàn!', 'error');
      return;
    }

    // Generate refund record
    const refundCode = 'REF-2026-' + Math.floor(10000 + Math.random() * 90000);
    const newRefund = {
      id: Date.now(),
      refund_code: refundCode,
      order_code: order.code,
      order_id: order.id,
      user_id: this.currentUser?.id || 1,
      user_fullname: this.currentUser?.fullname || 'Nguyễn Văn An',
      phone_number: phone || this.currentUser?.phone_number || '0988776655',
      product_name: order.items?.map(it => it.name).join(', ') || 'Sản phẩm theo đơn ' + order.code,
      reason: reason,
      customer_notes: notes,
      refund_amount: order.total_amount || 680000,
      refund_method: 'bank',
      bank_name: bankName,
      bank_account: bankAccount,
      bank_account_name: userBankName.toUpperCase(),
      images: [...this._tempRefundImages],
      video_proof: this._tempRefundVideo.url,
      video_name: this._tempRefundVideo.name,
      status: 'inspecting',
      status_badge: 'Đang Thẩm Định Video Unbox',
      transaction_code: null,
      created_at: new Date().toLocaleString('vi-VN', { hour12: false }),
      timeline: [
        { step: 1, title: 'Đã gửi yêu cầu đổi trả', desc: 'Hệ thống đã ghi nhận video unbox và ảnh chứng cứ', time: 'Vừa xong', done: true },
        { step: 2, title: 'Đang thẩm định video unbox', desc: 'Bộ phận CSKH & QC đang kiểm tra clip mở hộp', time: 'Hôm nay', done: true, current: true },
        { step: 3, title: 'Thu hồi sản phẩm', desc: 'Chờ shipper đến nhận hàng tận nơi', time: 'Dự kiến ngày mai', done: false },
        { step: 4, title: 'Chuyển tiền hoàn', desc: `Hoàn tiền vào STK ${bankName} (${bankAccount}) sau khi kiểm tra`, time: 'Dự kiến 2 ngày tới', done: false }
      ]
    };

    const refunds = this.getRefunds();
    refunds.unshift(newRefund);
    this.saveRefunds(refunds);

    this.showToast(`Yêu cầu đổi trả #${refundCode} (Kèm Video Unbox) đã được gửi thành công!`);
    this.closeRefundModal();

    // Directly open refund tracking modal for instant verification
    setTimeout(() => {
      this.openRefundTracking(refundCode);
    }, 400);
  },

  // ================= SEARCH MODAL =================
  openSearchModal: function() {
    const modal = document.getElementById('search-modal');
    if (!modal) return;
    modal.classList.remove('hidden');
    document.getElementById('search-modal-input')?.focus();
    lucide.createIcons();
  },

  closeSearchModal: function() {
    document.getElementById('search-modal')?.classList.add('hidden');
  },

  searchModalQuery: function(val) {
    const resultsContainer = document.getElementById('search-modal-results');
    if (!resultsContainer) return;

    if (!val || val.trim() === '') {
      resultsContainer.innerHTML = '<p class="text-xs text-neutral-400 text-center py-8">Nhập từ khóa (áo sơ mi, blazer, túi da, đầm...) để tìm kiếm sản phẩm.</p>';
      return;
    }

    const q = val.toLowerCase().trim();
    const matches = BeeDB.products.filter(p => p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q) || p.description.toLowerCase().includes(q));

    if (matches.length === 0) {
      resultsContainer.innerHTML = `<p class="text-xs text-neutral-400 text-center py-8">Không tìm thấy sản phẩm nào khớp với "${val}".</p>`;
      return;
    }

    resultsContainer.innerHTML = `
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[60vh] overflow-y-auto pr-1">
        ${matches.map(p => `
          <a href="product-detail.html?id=${p.id}" class="flex gap-3 p-2.5 bg-neutral-50 hover:bg-neutral-100 rounded-lg border border-neutral-200 transition-colors">
            <img src="${p.thumbnail}" alt="${p.name}" class="w-14 h-16 object-cover rounded bg-neutral-200 shrink-0">
            <div class="min-w-0">
              <h4 class="text-xs font-semibold text-neutral-900 truncate">${p.name}</h4>
              <span class="text-[10px] text-neutral-500 block font-mono">SKU: ${p.sku}</span>
              <span class="font-serif-luxury text-xs font-bold text-neutral-900">${this.formatMoney(p.is_sale ? p.sale_price : p.price)}</span>
            </div>
          </a>
        `).join('')}
      </div>
    `;
  },

  // ================= SIZE GUIDE MODAL =================
  openSizeGuideModal: function() {
    const modal = document.getElementById('size-guide-modal');
    if (modal) modal.classList.remove('hidden');
  },

  closeSizeGuideModal: function() {
    document.getElementById('size-guide-modal')?.classList.add('hidden');
  },

  // ================= INJECT SHARED MODALS HTML =================
  injectSharedModals: function() {
    if (document.getElementById('shared-modals-root')) return;

    const div = document.createElement('div');
    div.id = 'shared-modals-root';
    div.innerHTML = `
      <!-- Toast Container -->
      <div id="toast-container" class="fixed top-6 right-6 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none"></div>

      <!-- Cart Drawer Overlay & Drawer -->
      <div id="cart-overlay" onclick="BeeCore.toggleCart(false)" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden transition-opacity"></div>
      <div id="cart-drawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
        <div class="p-5 border-b border-neutral-200 flex justify-between items-center bg-brand-50">
          <div class="flex items-center gap-2">
            <i data-lucide="shopping-bag" class="w-5 h-5 text-neutral-900"></i>
            <h3 class="font-serif-luxury text-xl font-bold tracking-wider text-neutral-900 uppercase">Túi Mua Hàng</h3>
          </div>
          <button onclick="BeeCore.toggleCart(false)" class="p-1.5 text-neutral-400 hover:text-black rounded">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
        <div id="cart-items-container" class="flex-grow overflow-y-auto p-5 space-y-3">
          <!-- Rendered by JS -->
        </div>
        <div id="cart-summary-container" class="p-5 bg-brand-50 border-t border-neutral-200">
          <!-- Rendered by JS -->
        </div>
      </div>

      <!-- Auth Modal (Sign In / Register) -->
      <div id="auth-modal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-8 shadow-2xl relative border border-neutral-200 animate-fade-in">
          <button onclick="BeeCore.closeAuthModal()" class="absolute top-5 right-5 text-neutral-400 hover:text-black">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
          <div class="text-center mb-6">
            <span class="font-serif-luxury text-2xl font-bold tracking-[0.2em] text-neutral-900 block">BEESTYLE</span>
            <h3 class="font-serif-luxury text-xl text-neutral-800 mt-1" id="auth-modal-title">Đăng Nhập Thành Viên</h3>
          </div>
          <form onsubmit="BeeCore.submitAuth(event)" class="space-y-4">
            <div id="auth-register-fields" class="space-y-4 hidden">
              <div>
                <label class="block text-xs font-semibold uppercase text-neutral-600 mb-1">Họ và Tên</label>
                <input type="text" id="auth-fullname" placeholder="Nguyễn Văn An" class="w-full bg-neutral-50 border border-neutral-200 rounded px-3 py-2.5 text-xs focus:outline-none focus:border-neutral-900">
              </div>
            </div>
            <div>
              <label class="block text-xs font-semibold uppercase text-neutral-600 mb-1">Email</label>
              <input type="email" id="auth-email" value="customer@beestyle.vn" required class="w-full bg-neutral-50 border border-neutral-200 rounded px-3 py-2.5 text-xs focus:outline-none focus:border-neutral-900">
            </div>
            <div>
              <label class="block text-xs font-semibold uppercase text-neutral-600 mb-1">Mật khẩu</label>
              <input type="password" id="auth-password" value="123456" required class="w-full bg-neutral-50 border border-neutral-200 rounded px-3 py-2.5 text-xs focus:outline-none focus:border-neutral-900">
            </div>
            <button type="submit" id="auth-submit-btn" class="w-full py-3 bg-neutral-950 text-white text-xs font-semibold tracking-widest uppercase hover:bg-neutral-800 rounded transition-all">
              Đăng Nhập
            </button>
          </form>
          <p class="text-center text-xs text-neutral-500 mt-6" id="auth-toggle-text">
            Chưa có tài khoản? <a href="javascript:void(0)" onclick="BeeCore.setAuthMode('register')" class="font-semibold text-neutral-950 underline">Đăng ký thành viên</a>
          </p>
        </div>
      </div>

      <!-- Order & Refund Lookup Modal -->
      <div id="order-lookup-modal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl relative border border-neutral-200 animate-fade-in my-8 max-h-[92vh] flex flex-col">
          <button onclick="BeeCore.closeOrderLookup()" class="absolute top-5 right-5 text-neutral-400 hover:text-black">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
          
          <div class="border-b border-neutral-200 pb-3 mb-4">
            <span class="text-[10px] tracking-widest uppercase text-neutral-500 font-semibold block">TRUNG TÂM TRA CỨU TRỰC TUYẾN</span>
            <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Theo Dõi Đơn Hàng & Hoàn Tiền</h3>
          </div>

          <!-- Tabs: Tra Cứu Vận Chuyển VS Theo Dõi Hoàn Tiền -->
          <div class="flex border-b border-neutral-200 mb-4 text-xs">
            <button id="lookup-tab-btn-order" onclick="BeeCore.switchLookupTab('order')" class="flex-1 pb-3 text-center border-b-2 border-neutral-900 text-neutral-900 font-bold uppercase tracking-wider flex items-center justify-center gap-1.5 transition-all">
              <i data-lucide="truck" class="w-4 h-4"></i>
              <span>Vận Chuyển Đơn Hàng</span>
            </button>
            <button id="lookup-tab-btn-refund" onclick="BeeCore.switchLookupTab('refund')" class="flex-1 pb-3 text-center border-b-2 border-transparent text-neutral-500 hover:text-neutral-900 font-medium uppercase tracking-wider flex items-center justify-center gap-1.5 transition-all">
              <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
              <span>Tiến Trình Hoàn Tiền (RMA)</span>
            </button>
          </div>

          <!-- Pane 1: Order Tracking -->
          <div id="lookup-tab-pane-order" class="space-y-4 overflow-y-auto pr-1">
            <div class="flex gap-2">
              <input type="text" id="order-search-code" placeholder="Nhập mã đơn hàng (vd: BEE-2026-001)" class="bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs flex-grow focus:outline-none focus:border-neutral-900 uppercase font-mono">
              <button onclick="BeeCore.searchOrder()" class="px-4 py-2 bg-neutral-900 text-white rounded text-xs font-semibold uppercase hover:bg-neutral-800 flex items-center gap-1">
                <i data-lucide="search" class="w-3.5 h-3.5"></i> Tra Cứu
              </button>
            </div>
            <div id="order-lookup-result">
              <p class="text-xs text-neutral-400 text-center py-8">Nhập mã đơn hàng của bạn để kiểm tra lộ trình giao vận.</p>
            </div>
          </div>

          <!-- Pane 2: Refund / Return Tracking -->
          <div id="lookup-tab-pane-refund" class="space-y-4 hidden overflow-y-auto pr-1">
            <div class="flex gap-2">
              <input type="text" id="refund-search-code" placeholder="Nhập mã hoàn tiền (vd: REF-2026-08101) hoặc mã đơn..." class="bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs flex-grow focus:outline-none focus:border-neutral-900 uppercase font-mono">
              <button onclick="BeeCore.searchRefund()" class="px-4 py-2 bg-amber-600 text-white rounded text-xs font-semibold uppercase hover:bg-amber-700 flex items-center gap-1">
                <i data-lucide="search" class="w-3.5 h-3.5"></i> Kiểm Tra
              </button>
            </div>
            <div id="refund-lookup-result">
              <p class="text-xs text-neutral-400 text-center py-8">Nhập mã yêu cầu đổi trả (REF-...) hoặc mã đơn để theo dõi tiến độ thẩm định video unbox và hoàn tiền ngân hàng.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Refund / Return Request Modal with Video & Photo Unbox Proofs -->
      <div id="refund-modal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative border border-neutral-200 animate-fade-in my-8 max-h-[92vh] flex flex-col">
          <button onclick="BeeCore.closeRefundModal()" class="absolute top-5 right-5 text-neutral-400 hover:text-black">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
          
          <div class="border-b border-neutral-200 pb-3 mb-4">
            <span class="text-[10px] tracking-widest uppercase text-amber-700 font-bold block">CHÍNH SÁCH ĐỔI TRẢ ATELIER 30 NGÀY</span>
            <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Yêu Cầu Đổi Trả / Hoàn Tiền</h3>
            <p class="text-xs text-neutral-500 mt-1">Yêu cầu cần cung cấp hình ảnh & video unbox mở hộp để shop đối soát nhanh nhất.</p>
          </div>

          <form onsubmit="BeeCore.submitRefund(event)" class="space-y-3.5 text-xs overflow-y-auto pr-1">
            <!-- Order selection -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1">Chọn Đơn Hàng Hoàn Trả *</label>
              <select id="refund-order-select" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900 font-medium">
                <!-- Populated dynamically by openRefundModal() -->
              </select>
            </div>

            <!-- Return Reason -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1">Lý Do Đổi Trả / Hoàn Tiền *</label>
              <select id="refund-reason" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900">
                <option value="Đổi size do mặc rộng/chật">Đổi kích cỡ (Size) do mặc không vừa</option>
                <option value="Đổi sang màu sắc khác">Muốn đổi sang màu sắc / phiên bản khác</option>
                <option value="Sản phẩm lỗi đường may/chất vải">Lỗi sản xuất (Lỗi vải, đường may, phụ kiện)</option>
                <option value="Giao sai mẫu/sai sản phẩm">Giao sai sản phẩm so với đơn đặt</option>
                <option value="Hoàn tiền trả hàng nguyên vẹn">Không ưng ý, muốn trả hàng và hoàn tiền 100%</option>
              </select>
            </div>

            <!-- Notes -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1">Mô Tả Chi Tiết Vấn Đề</label>
              <textarea id="refund-notes" rows="2" placeholder="Ghi chú chi tiết về tình trạng sản phẩm, yêu cầu đổi size mong muốn..." class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900"></textarea>
            </div>

            <!-- REQUIRED PHOTO PROOFS -->
            <div class="p-3 bg-amber-50/70 rounded-xl border border-amber-200 space-y-2">
              <div class="flex items-center justify-between">
                <label class="font-bold uppercase text-neutral-900 text-xs flex items-center gap-1.5">
                  <i data-lucide="camera" class="w-4 h-4 text-amber-700"></i>
                  <span>1. Hình Ảnh Sản Phẩm / Tem Mác <span class="text-rose-600">* (Bắt buộc)</span></span>
                </label>
                <span class="text-[10px] text-amber-800 font-semibold">Tối thiểu 1 ảnh</span>
              </div>
              <input type="file" id="refund-images-input" accept="image/*" multiple onchange="BeeCore.handleRefundImagesSelect(this)" class="w-full text-xs text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-neutral-900 file:text-white hover:file:bg-neutral-800 cursor-pointer">
              <div id="refund-images-preview" class="flex gap-2 overflow-x-auto py-1 empty:hidden"></div>
            </div>

            <!-- REQUIRED VIDEO UNBOX PROOF -->
            <div class="p-3 bg-rose-50/70 rounded-xl border border-rose-200 space-y-2">
              <div class="flex items-center justify-between">
                <label class="font-bold uppercase text-neutral-900 text-xs flex items-center gap-1.5">
                  <i data-lucide="video" class="w-4 h-4 text-rose-700"></i>
                  <span>2. Video Clip Unbox Mở Hộp <span class="text-rose-600">* (Bắt buộc)</span></span>
                </label>
                <span class="text-[10px] text-rose-800 font-semibold">Định dạng MP4/MOV</span>
              </div>
              <p class="text-[11px] text-neutral-500 leading-tight">Clip quay liền mạch từ lúc nguyên bao bì niêm phong đến khi kiểm tra sản phẩm để bảo vệ 100% quyền lợi hoàn tiền.</p>
              
              <input type="file" id="refund-video-input" accept="video/*" onchange="BeeCore.handleRefundVideoSelect(this)" class="w-full text-xs text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-rose-900 file:text-white hover:file:bg-rose-800 cursor-pointer">
              
              <!-- Video Player Preview -->
              <div id="refund-video-preview-box" class="hidden bg-black/90 p-2 rounded-lg relative space-y-1">
                <div class="flex items-center justify-between text-white text-[11px] px-1">
                  <span id="refund-video-filename" class="truncate max-w-[240px] font-mono">video.mp4</span>
                  <button type="button" onclick="BeeCore.removeRefundVideo()" class="text-rose-400 hover:text-white font-bold text-xs">Xóa clip ✕</button>
                </div>
                <video id="refund-video-player" controls class="w-full max-h-40 rounded object-contain bg-black"></video>
              </div>
            </div>

            <!-- Bank Refund Details -->
            <div class="pt-2 border-t border-neutral-200">
              <span class="font-semibold uppercase text-neutral-700 block mb-2 text-xs">Thông Tin Tài Khoản Nhận Tiền Hoàn *</span>
              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block text-[11px] font-medium text-neutral-600 mb-0.5">Tên Ngân Hàng</label>
                  <input type="text" id="refund-bank-name" value="Vietcombank" required class="w-full bg-neutral-50 border border-neutral-300 rounded px-2.5 py-1.5 text-xs">
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-neutral-600 mb-0.5">Số Tài Khoản</label>
                  <input type="text" id="refund-bank-account" value="0071001234567" required class="w-full bg-neutral-50 border border-neutral-300 rounded px-2.5 py-1.5 text-xs font-mono">
                </div>
              </div>
              <div class="grid grid-cols-2 gap-2 mt-2">
                <div>
                  <label class="block text-[11px] font-medium text-neutral-600 mb-0.5">Tên Chủ Tài Khoản</label>
                  <input type="text" id="refund-user-bank-name" value="NGUYEN VAN AN" required class="w-full bg-neutral-50 border border-neutral-300 rounded px-2.5 py-1.5 text-xs uppercase">
                </div>
                <div>
                  <label class="block text-[11px] font-medium text-neutral-600 mb-0.5">Số Điện Thoại</label>
                  <input type="tel" id="refund-phone" value="0988776655" required class="w-full bg-neutral-50 border border-neutral-300 rounded px-2.5 py-1.5 text-xs">
                </div>
              </div>
            </div>

            <button type="submit" class="w-full py-3 bg-neutral-950 text-white rounded font-semibold uppercase tracking-wider hover:bg-neutral-800 transition-colors shadow-lg flex items-center justify-center gap-2 mt-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-amber-400"></i>
              <span>Gửi Yêu Cầu Hoàn Tiền (Kèm Video & Ảnh)</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Search Modal -->
      <div id="search-modal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-md flex items-start justify-center pt-20 p-4 hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative border border-neutral-200 animate-fade-in">
          <button onclick="BeeCore.closeSearchModal()" class="absolute top-5 right-5 text-neutral-400 hover:text-black">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
          <div class="flex items-center gap-3 border-b-2 border-neutral-900 pb-3 mb-4">
            <i data-lucide="search" class="w-5 h-5 text-neutral-700"></i>
            <input type="text" id="search-modal-input" oninput="BeeCore.searchModalQuery(this.value)" placeholder="Tìm kiếm trang phục, phụ kiện, chất liệu lụa, blazer..." class="w-full text-sm text-neutral-900 placeholder-neutral-400 focus:outline-none">
          </div>
          <div id="search-modal-results">
            <p class="text-xs text-neutral-400 text-center py-8">Nhập từ khóa để tìm kiếm trong danh mục thời trang Beestyle.</p>
          </div>
        </div>
      </div>

      <!-- Customer Profile & Security Modal (With 5 Tabs including Refunds) -->
      <div id="profile-modal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-4xl w-full p-6 md:p-8 shadow-2xl relative border border-neutral-200 animate-fade-in max-h-[92vh] flex flex-col">
          <button onclick="BeeCore.closeProfileModal()" class="absolute top-5 right-5 text-neutral-400 hover:text-black transition-colors z-10">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>

          <!-- Modal Header -->
          <div class="border-b border-neutral-200 pb-4 mb-5 flex items-center justify-between">
            <div>
              <span class="text-[10px] tracking-[0.25em] uppercase text-amber-700 font-semibold block">ATELIER PRIVILEGE</span>
              <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Quản Lý Tài Khoản Khách Hàng</h3>
            </div>
          </div>

          <!-- Modal Body Grid: Tabs on Left / Content on Right -->
          <div class="grid grid-cols-1 md:grid-cols-12 gap-6 flex-grow overflow-hidden">
            
            <!-- Left Sidebar Navigation -->
            <div class="md:col-span-4 flex flex-col justify-between border-b md:border-b-0 md:border-r border-neutral-200 pb-4 md:pb-0 md:pr-4">
              <div class="space-y-1.5">
                <!-- User Quick Info -->
                <div class="flex items-center gap-3 p-3 bg-neutral-50 rounded-xl border border-neutral-200 mb-3">
                  <div id="profile-avatar-letter" class="w-10 h-10 rounded-full bg-neutral-900 text-amber-300 flex items-center justify-center font-serif text-base font-bold ring-2 ring-amber-400/40 shrink-0">
                    A
                  </div>
                  <div class="overflow-hidden">
                    <h4 id="profile-display-name" class="font-semibold text-xs text-neutral-900 truncate">Nguyễn Văn An</h4>
                    <span id="profile-display-tier" class="inline-block px-1.5 py-0.5 bg-amber-100 text-amber-800 rounded text-[9px] font-bold uppercase mt-0.5">VIP Gold</span>
                  </div>
                </div>

                <button id="profile-tab-btn-info" onclick="BeeCore.switchProfileTab('info')" class="w-full text-left px-4 py-3 rounded-lg bg-neutral-900 text-white font-semibold text-xs flex items-center gap-2.5 transition-all shadow-sm">
                  <i data-lucide="user" class="w-4 h-4"></i>
                  <span>Thông Tin Cá Nhân</span>
                </button>

                <button id="profile-tab-btn-password" onclick="BeeCore.switchProfileTab('password')" class="w-full text-left px-4 py-3 rounded-lg text-neutral-600 hover:bg-neutral-100 font-medium text-xs flex items-center gap-2.5 transition-all">
                  <i data-lucide="key-round" class="w-4 h-4"></i>
                  <span>Đổi Mật Khẩu</span>
                </button>

                <button id="profile-tab-btn-orders" onclick="BeeCore.switchProfileTab('orders')" class="w-full text-left px-4 py-3 rounded-lg text-neutral-600 hover:bg-neutral-100 font-medium text-xs flex items-center gap-2.5 transition-all">
                  <i data-lucide="package" class="w-4 h-4"></i>
                  <span>Đơn Hàng Của Tôi</span>
                </button>

                <button id="profile-tab-btn-refunds" onclick="BeeCore.switchProfileTab('refunds')" class="w-full text-left px-4 py-3 rounded-lg text-neutral-600 hover:bg-neutral-100 font-medium text-xs flex items-center gap-2.5 transition-all">
                  <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                  <span>Đổi Trả & Hoàn Tiền (RMA)</span>
                </button>

                <button id="profile-tab-btn-address" onclick="BeeCore.switchProfileTab('address')" class="w-full text-left px-4 py-3 rounded-lg text-neutral-600 hover:bg-neutral-100 font-medium text-xs flex items-center gap-2.5 transition-all">
                  <i data-lucide="map-pin" class="w-4 h-4"></i>
                  <span>Sổ Địa Chỉ & Ngân Hàng</span>
                </button>
              </div>

              <!-- Membership Perks Info Box -->
              <div class="hidden md:block p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl text-[11px] text-neutral-600 space-y-1">
                <div class="flex items-center gap-1.5 font-bold text-neutral-900 text-xs">
                  <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-600"></i>
                  <span>Chính Sách Khách Hàng</span>
                </div>
                <p>• Đổi trả 30 ngày tận nhà</p>
                <p>• Bắt buộc video unbox bảo vệ quyền lợi</p>
                <p>• Hoàn tiền trong 24h sau khi duyệt</p>
              </div>
            </div>

            <!-- Right Content Panes -->
            <div class="md:col-span-8 overflow-y-auto pr-1">
              
              <!-- Tab 1: Profile Info Form -->
              <div id="profile-tab-pane-info" class="space-y-4">
                <div class="border-b border-neutral-100 pb-2">
                  <h4 class="font-serif-luxury text-lg font-bold text-neutral-900">Hồ Sơ Thành Viên</h4>
                  <p class="text-xs text-neutral-500">Quản lý thông tin định danh và tùy chỉnh tài khoản của bạn.</p>
                </div>

                <form onsubmit="BeeCore.updateProfile(event)" class="space-y-3 text-xs">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                      <label class="block font-semibold uppercase text-neutral-700 mb-1">Họ và Tên *</label>
                      <input type="text" id="profile-fullname" required class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900">
                    </div>
                    <div>
                      <label class="block font-semibold uppercase text-neutral-700 mb-1">Email (Định danh)</label>
                      <input type="email" id="profile-email" disabled class="w-full bg-neutral-100 border border-neutral-200 rounded px-3 py-2 text-xs text-neutral-500 cursor-not-allowed">
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                      <label class="block font-semibold uppercase text-neutral-700 mb-1">Số Điện Thoại</label>
                      <input type="tel" id="profile-phone" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900">
                    </div>
                    <div>
                      <label class="block font-semibold uppercase text-neutral-700 mb-1">Giới Tính</label>
                      <select id="profile-gender" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900">
                        <option value="male">Nam</option>
                        <option value="female">Nữ</option>
                        <option value="other">Khác</option>
                      </select>
                    </div>
                  </div>

                  <div>
                    <label class="block font-semibold uppercase text-neutral-700 mb-1">Ngày Sinh</label>
                    <input type="date" id="profile-birthday" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900">
                  </div>

                  <div>
                    <label class="block font-semibold uppercase text-neutral-700 mb-1">Địa Chỉ Nhận Hàng Mặc Định</label>
                    <input type="text" id="profile-address" placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành..." class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900">
                  </div>

                  <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-neutral-950 text-white rounded text-xs font-semibold uppercase tracking-wider hover:bg-neutral-800 transition-colors shadow-md flex items-center gap-1.5">
                      <i data-lucide="save" class="w-3.5 h-3.5"></i> Lưu Thay Đổi
                    </button>
                  </div>
                </form>
              </div>

              <!-- Tab 2: Change Password Form -->
              <div id="profile-tab-pane-password" class="space-y-4 hidden">
                <div class="border-b border-neutral-100 pb-2">
                  <h4 class="font-serif-luxury text-lg font-bold text-neutral-900">Đổi Mật Khẩu Bảo Mật</h4>
                  <p class="text-xs text-neutral-500">Mật khẩu cần tối thiểu 6 ký tự để đảm bảo an toàn cho tài khoản.</p>
                </div>

                <div class="bg-amber-50/80 border border-amber-200/80 rounded-lg p-3 text-[11px] text-amber-900 flex items-start gap-2">
                  <i data-lucide="shield-alert" class="w-4 h-4 text-amber-700 shrink-0 mt-0.5"></i>
                  <span>Gợi ý: Mật khẩu mặc định demo là <strong>123456</strong>. Bạn có thể đổi sang mật khẩu mới bất kỳ để trải nghiệm tính năng.</span>
                </div>

                <form onsubmit="BeeCore.changePassword(event)" class="space-y-3 text-xs">
                  <div>
                    <label class="block font-semibold uppercase text-neutral-700 mb-1">Mật Khẩu Hiện Tại *</label>
                    <div class="relative">
                      <input type="password" id="pwd-current" required placeholder="Nhập mật khẩu đang sử dụng" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs pr-10 focus:outline-none focus:border-neutral-900">
                      <button type="button" onclick="BeeCore.togglePasswordVisibility('pwd-current', this)" class="absolute right-2.5 top-2.5 text-neutral-400 hover:text-black">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                      </button>
                    </div>
                  </div>

                  <div>
                    <label class="block font-semibold uppercase text-neutral-700 mb-1">Mật Khẩu Mới *</label>
                    <div class="relative">
                      <input type="password" id="pwd-new" required minlength="6" placeholder="Tối thiểu 6 ký tự" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs pr-10 focus:outline-none focus:border-neutral-900">
                      <button type="button" onclick="BeeCore.togglePasswordVisibility('pwd-new', this)" class="absolute right-2.5 top-2.5 text-neutral-400 hover:text-black">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                      </button>
                    </div>
                  </div>

                  <div>
                    <label class="block font-semibold uppercase text-neutral-700 mb-1">Xác Nhận Mật Khẩu Mới *</label>
                    <div class="relative">
                      <input type="password" id="pwd-confirm" required minlength="6" placeholder="Nhập lại mật khẩu mới" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs pr-10 focus:outline-none focus:border-neutral-900">
                      <button type="button" onclick="BeeCore.togglePasswordVisibility('pwd-confirm', this)" class="absolute right-2.5 top-2.5 text-neutral-400 hover:text-black">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                      </button>
                    </div>
                  </div>

                  <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-neutral-950 text-white rounded text-xs font-semibold uppercase tracking-wider hover:bg-amber-600 transition-colors shadow-md flex items-center gap-1.5">
                      <i data-lucide="key" class="w-3.5 h-3.5"></i> Cập Nhật Mật Khẩu
                    </button>
                  </div>
                </form>
              </div>

              <!-- Tab 3: Order History -->
              <div id="profile-tab-pane-orders" class="space-y-4 hidden">
                <div class="border-b border-neutral-100 pb-2 flex justify-between items-center">
                  <div>
                    <h4 class="font-serif-luxury text-lg font-bold text-neutral-900">Đơn Hàng Của Bạn</h4>
                    <p class="text-xs text-neutral-500">Lịch sử đơn hàng và quyền viết đánh giá cho các sản phẩm đã nhận.</p>
                  </div>
                  <button onclick="BeeCore.closeProfileModal(); BeeCore.openOrderLookup('order')" class="text-xs text-amber-700 hover:underline font-semibold flex items-center gap-1">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i> Tra cứu mã khác
                  </button>
                </div>

                <div id="profile-orders-list" class="space-y-3">
                  <!-- Rendered dynamically by BeeCore.renderProfileOrders() -->
                </div>
              </div>

              <!-- Tab 4: Refund History -->
              <div id="profile-tab-pane-refunds" class="space-y-4 hidden">
                <div class="border-b border-neutral-100 pb-2 flex justify-between items-center">
                  <div>
                    <h4 class="font-serif-luxury text-lg font-bold text-neutral-900">Yêu Cầu Đổi Trả & Hoàn Tiền (RMA)</h4>
                    <p class="text-xs text-neutral-500">Theo dõi quá trình thẩm định video unbox và giải ngân hoàn tiền vào STK.</p>
                  </div>
                  <button onclick="BeeCore.closeProfileModal(); BeeCore.openRefundModal()" class="px-3 py-1.5 bg-amber-600 text-white rounded text-xs font-semibold hover:bg-amber-700 flex items-center gap-1 shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tạo Yêu Cầu Mới
                  </button>
                </div>

                <div id="profile-refunds-list" class="space-y-3">
                  <!-- Rendered dynamically by BeeCore.renderProfileRefunds() -->
                </div>
              </div>

              <!-- Tab 5: Address & Bank Account -->
              <div id="profile-tab-pane-address" class="space-y-4 hidden">
                <div class="border-b border-neutral-100 pb-2">
                  <h4 class="font-serif-luxury text-lg font-bold text-neutral-900">Sổ Địa Chỉ & Tài Khoản Ngân Hàng</h4>
                  <p class="text-xs text-neutral-500">Thông tin nhận hàng và tài khoản thụ hưởng khi yêu cầu hoàn tiền đổi trả.</p>
                </div>

                <form onsubmit="BeeCore.updateProfile(event)" class="space-y-3 text-xs">
                  <div>
                    <label class="block font-semibold uppercase text-neutral-700 mb-1">Địa Chỉ Nhận Hàng Mặc Định</label>
                    <textarea id="profile-address-detail" rows="2" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs focus:outline-none focus:border-neutral-900" oninput="document.getElementById('profile-address').value = this.value">88 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh</textarea>
                  </div>

                  <div class="pt-2 border-t border-neutral-100">
                    <span class="font-semibold uppercase text-neutral-700 block mb-2 text-xs">Tài Khoản Ngân Hàng Thụ Hưởng (Dùng khi hoàn tiền)</span>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                      <div>
                        <label class="block font-semibold text-neutral-600 mb-1">Tên Ngân Hàng</label>
                        <input type="text" id="profile-bank-name" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs">
                      </div>
                      <div>
                        <label class="block font-semibold text-neutral-600 mb-1">Số Tài Khoản</label>
                        <input type="text" id="profile-bank-account" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs font-mono">
                      </div>
                      <div class="md:col-span-2">
                        <label class="block font-semibold text-neutral-600 mb-1">Tên Chủ Tài Khoản (In hoa không dấu)</label>
                        <input type="text" id="profile-user-bank-name" class="w-full bg-neutral-50 border border-neutral-300 rounded px-3 py-2 text-xs uppercase">
                      </div>
                    </div>
                  </div>

                  <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-neutral-950 text-white rounded text-xs font-semibold uppercase tracking-wider hover:bg-neutral-800 transition-colors shadow-md">
                      Lưu Thông Tin Sổ Địa Chỉ & Ngân Hàng
                    </button>
                  </div>
                </form>
              </div>

            </div>

          </div>
        </div>
      </div>

      <!-- Size Guide Modal -->
      <div id="size-guide-modal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 md:p-8 shadow-2xl relative border border-neutral-200 animate-fade-in overflow-y-auto max-h-[90vh]">
          <button onclick="BeeCore.closeSizeGuideModal()" class="absolute top-5 right-5 text-neutral-400 hover:text-black">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
          <div class="border-b border-neutral-200 pb-3 mb-6">
            <span class="text-[10px] tracking-widest uppercase text-neutral-500 font-semibold block">ATELIER STANDARD SIZING</span>
            <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Bảng Hướng Dẫn Chọn Kích Cỡ Chuẩn</h3>
          </div>
          <div class="space-y-6 text-xs text-neutral-700">
            <div>
              <h4 class="font-bold text-neutral-900 uppercase tracking-wider mb-2">1. Thời Trang Nam (Áo Sơ Mi, Blazer, Măng Tô)</h4>
              <table class="w-full border border-neutral-200 text-center">
                <thead class="bg-neutral-100 font-semibold text-neutral-900">
                  <tr>
                    <th class="p-2 border">Size</th>
                    <th class="p-2 border">Chiều Cao (cm)</th>
                    <th class="p-2 border">Cân Nặng (kg)</th>
                    <th class="p-2 border">Vòng Ngực (cm)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td class="p-2 border font-bold">S</td><td class="p-2 border">160 - 168</td><td class="p-2 border">52 - 60</td><td class="p-2 border">86 - 90</td></tr>
                  <tr><td class="p-2 border font-bold">M</td><td class="p-2 border">168 - 175</td><td class="p-2 border">60 - 68</td><td class="p-2 border">90 - 95</td></tr>
                  <tr><td class="p-2 border font-bold">L</td><td class="p-2 border">173 - 180</td><td class="p-2 border">68 - 76</td><td class="p-2 border">96 - 102</td></tr>
                  <tr><td class="p-2 border font-bold">XL</td><td class="p-2 border">178 - 188</td><td class="p-2 border">76 - 85</td><td class="p-2 border">103 - 110</td></tr>
                </tbody>
              </table>
            </div>

            <div>
              <h4 class="font-bold text-neutral-900 uppercase tracking-wider mb-2">2. Thời Trang Nữ (Đầm Lụa, Chân Váy, Corset)</h4>
              <table class="w-full border border-neutral-200 text-center">
                <thead class="bg-neutral-100 font-semibold text-neutral-900">
                  <tr>
                    <th class="p-2 border">Size</th>
                    <th class="p-2 border">Vòng Ngực (cm)</th>
                    <th class="p-2 border">Vòng Eo (cm)</th>
                    <th class="p-2 border">Vòng Mông (cm)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td class="p-2 border font-bold">S</td><td class="p-2 border">80 - 84</td><td class="p-2 border">62 - 66</td><td class="p-2 border">86 - 90</td></tr>
                  <tr><td class="p-2 border font-bold">M</td><td class="p-2 border">85 - 89</td><td class="p-2 border">67 - 71</td><td class="p-2 border">91 - 95</td></tr>
                  <tr><td class="p-2 border font-bold">L</td><td class="p-2 border">90 - 94</td><td class="p-2 border">72 - 76</td><td class="p-2 border">96 - 100</td></tr>
                </tbody>
              </table>
            </div>

            <div>
              <h4 class="font-bold text-neutral-900 uppercase tracking-wider mb-2">3. Giày Tây & Boots Nam (Chuẩn EU)</h4>
              <table class="w-full border border-neutral-200 text-center">
                <thead class="bg-neutral-100 font-semibold text-neutral-900">
                  <tr>
                    <th class="p-2 border">Size EU</th>
                    <th class="p-2 border">39</th>
                    <th class="p-2 border">40</th>
                    <th class="p-2 border">41</th>
                    <th class="p-2 border">42</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td class="p-2 border font-semibold">Chiều dài chân (cm)</td><td class="p-2 border">24.5</td><td class="p-2 border">25.0</td><td class="p-2 border">25.5</td><td class="p-2 border">26.0</td></tr>
                </tbody>
              </table>
            </div>

            <p class="text-neutral-500 italic">* Nếu số đo của bạn nằm giữa hai size, chúng tôi khuyên bạn nên chọn size lớn hơn hoặc liên hệ dịch vụ may đo riêng của Atelier.</p>
          </div>
        </div>
      </div>

      <!-- Product Review Modal (Dedicated Modal from My Orders) -->
      <div id="product-review-modal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl relative border border-neutral-200 animate-fade-in my-8 max-h-[92vh] flex flex-col">
          <button onclick="BeeCore.closeProductReviewModal()" class="absolute top-5 right-5 text-neutral-400 hover:text-black">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>

          <div class="border-b border-neutral-200 pb-3 mb-4">
            <span class="text-[10px] tracking-widest uppercase text-amber-700 font-bold block">TRẢI NGHIỆM THỰC TẾ SẢN PHẨM</span>
            <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Đánh Giá & Nhận Xét</h3>
            <p class="text-xs text-neutral-500 mt-1">Chia sẻ cảm nhận chân thực để cộng đồng Atelier cùng trải nghiệm.</p>
          </div>

          <!-- Product Item Card -->
          <div class="flex items-center gap-3 p-3 bg-neutral-50 rounded-xl border border-neutral-200 mb-4">
            <img id="review-modal-product-thumb" src="" alt="Thumbnail" class="w-14 h-16 object-cover rounded-lg border border-neutral-200 shrink-0 bg-white">
            <div class="min-w-0">
              <h4 id="review-modal-product-name" class="font-semibold text-xs text-neutral-900 truncate">Tên Sản Phẩm</h4>
              <p id="review-modal-product-variant" class="text-[11px] text-neutral-500 mt-0.5">Phân loại: ...</p>
              <span id="review-modal-order-code" class="text-[10px] font-mono text-amber-800 font-bold bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200 inline-block mt-1">Đơn hàng: #...</span>
            </div>
          </div>

          <form onsubmit="BeeCore.submitProductReview(event)" class="space-y-4 text-xs overflow-y-auto pr-1">
            <!-- Rating Star Selector -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">1. Bạn Đánh Giá Tác Phẩm Này Mấy Sao? *</label>
              <div class="flex items-center gap-2">
                <div id="review-star-selector" class="flex items-center gap-1 bg-amber-50/60 p-2 rounded-xl border border-amber-200">
                  <button type="button" onclick="BeeCore.setReviewRating(1)" class="p-1 text-amber-500 hover:scale-110 transition-transform"><i data-lucide="star" class="w-6 h-6 fill-amber-400 text-amber-500"></i></button>
                  <button type="button" onclick="BeeCore.setReviewRating(2)" class="p-1 text-amber-500 hover:scale-110 transition-transform"><i data-lucide="star" class="w-6 h-6 fill-amber-400 text-amber-500"></i></button>
                  <button type="button" onclick="BeeCore.setReviewRating(3)" class="p-1 text-amber-500 hover:scale-110 transition-transform"><i data-lucide="star" class="w-6 h-6 fill-amber-400 text-amber-500"></i></button>
                  <button type="button" onclick="BeeCore.setReviewRating(4)" class="p-1 text-amber-500 hover:scale-110 transition-transform"><i data-lucide="star" class="w-6 h-6 fill-amber-400 text-amber-500"></i></button>
                  <button type="button" onclick="BeeCore.setReviewRating(5)" class="p-1 text-amber-500 hover:scale-110 transition-transform"><i data-lucide="star" class="w-6 h-6 fill-amber-400 text-amber-500"></i></button>
                </div>
                <span id="review-rating-label" class="text-xs font-bold text-amber-800 ml-2">Tuyệt vời / Rất hài lòng (5 sao)</span>
              </div>
            </div>

            <!-- Review Text Area -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1">2. Nhận Xét Chi Tiết Về Chất Liệu, Phom Dáng, Đường May *</label>
              <textarea id="review-modal-comment" rows="3" required placeholder="Ví dụ: Vải lụa mềm mát, màu sắc y hình, đường may cúc vỏ ốc rất tinh tế. Mặc vừa vặn thoải mái..." class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-3 text-xs focus:outline-none focus:border-neutral-900 leading-relaxed"></textarea>
            </div>

            <!-- Review Photos Upload -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200 space-y-2">
              <div class="flex items-center justify-between">
                <label class="font-bold uppercase text-neutral-900 text-xs flex items-center gap-1.5">
                  <i data-lucide="camera" class="w-4 h-4 text-amber-700"></i>
                  <span>3. Tải Lên Hình Ảnh Thực Tế (Không bắt buộc)</span>
                </label>
                <span class="text-[10px] text-neutral-500">Tối đa 5 ảnh</span>
              </div>
              <input type="file" id="review-modal-images-input" accept="image/*" multiple onchange="BeeCore.handleReviewModalImages(this)" class="w-full text-xs text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-neutral-900 file:text-white hover:file:bg-neutral-800 cursor-pointer">
              <div id="review-modal-images-preview" class="flex gap-2 overflow-x-auto py-1 empty:hidden"></div>
            </div>

            <button type="submit" class="w-full py-3 bg-neutral-950 text-white rounded-xl font-semibold uppercase tracking-wider hover:bg-neutral-800 transition-colors shadow-lg flex items-center justify-center gap-2 mt-2">
              <i data-lucide="send" class="w-4 h-4 text-amber-400"></i>
              <span>Gửi Đánh Giá Sản Phẩm</span>
            </button>
          </form>
        </div>
      </div>
    `;

    document.body.appendChild(div);
  }
};

// Close user dropdown menu when clicking outside
document.addEventListener('click', (e) => {
  const drop = document.getElementById('user-dropdown-menu');
  if (drop && !drop.classList.contains('hidden')) {
    if (!e.target.closest('.header-auth-area')) {
      drop.classList.add('hidden');
    }
  }
});

// Auto init when document is loaded
document.addEventListener('DOMContentLoaded', () => {
  window.BeeCore.init();
});
