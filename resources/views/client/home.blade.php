@extends('layouts.client')

@section('title', 'BeeStyle Menswear - Always Be Casual | Thời Trang Áo Nam Cao Cấp')

@section('content')
<div class="container py-3 py-md-4">

  <!-- 1. 5-SLIDE HERO BANNER CAROUSEL -->
  <div id="beeHeroCarousel" class="carousel slide carousel-fade bee-hero-carousel" data-bs-ride="carousel" data-bs-interval="3000">
    <!-- Indicators -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#beeHeroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#beeHeroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#beeHeroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      <button type="button" data-bs-target="#beeHeroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
      <button type="button" data-bs-target="#beeHeroCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
      <!-- Slide 1: Blazer & Suit -->
      <div class="carousel-item active" style="background-image: url('{{ asset('assets/img/products/outerwear_01.jpg') }}');">
        <div class="carousel-overlay">
          <div class="row w-100 align-items-center">
            <div class="col-lg-7 text-start">
              <span class="bee-hero-badge gold">
                <i class="fa-solid fa-crown me-1"></i> BLAZER &amp; SUIT COLLECTION 2026
              </span>
              <h1 class="bee-hero-title">
                ĐẲNG CẤP QUÝ ÔNG <br>
                <span class="text-warning">LỊCH LÃM &amp; PHONG TRẦN</span>
              </h1>
              <p class="bee-hero-subtitle">
                Thiết kế phom suông Hàn Quốc, đệm vai tự nhiên tôn vóc dáng nam tính. Vải tuyết mưa 2 lớp cao cấp chống nhăn chuẩn may đo quốc tế.
              </p>
              <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="btn btn-bee-accent px-4 py-3">
                  <i class="fa-solid fa-bag-shopping me-1"></i> XEM BLAZER &amp; ÁO KHOÁC
                </a>
                <a href="{{ route('client.products.index') }}" class="btn btn-outline-light px-4 py-3 fw-bold" style="border-radius: 8px;">
                  KHÁM PHÁ CỬA HÀNG
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 2: Polo Cotton -->
      <div class="carousel-item" style="background-image: url('{{ asset('assets/img/products/polo_01.jpg') }}');">
        <div class="carousel-overlay">
          <div class="row w-100 align-items-center">
            <div class="col-lg-7 text-start">
              <span class="bee-hero-badge">
                <i class="fa-solid fa-sparkles me-1"></i> PREMIUM POLO • CÔNG NGHỆ KHÁNG KHUẨN
              </span>
              <h1 class="bee-hero-title">
                ÁO POLO NAM <br>
                <span class="text-danger">DỆT TỔ ONG THOÁNG KHÍ</span>
              </h1>
              <p class="bee-hero-subtitle">
                100% Sợi bông Cotton chải kỹ khử mùi vượt trội, giữ phom cổ bẻ thẳng thớm không quăn mép suốt 24 giờ năng động.
              </p>
              <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="btn btn-bee-accent px-4 py-3">
                  <i class="fa-solid fa-shirt me-1"></i> XEM BỘ SƯU TẬP POLO
                </a>
                <a href="{{ route('client.products.index', ['price_range' => 'under_500']) }}" class="btn btn-outline-light px-4 py-3 fw-bold" style="border-radius: 8px;">
                  ƯU ĐÃI DƯỚI 500K
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 3: Sơ mi Bamboo -->
      <div class="carousel-item" style="background-image: url('{{ asset('assets/img/products/somi_02.jpg') }}');">
        <div class="carousel-overlay">
          <div class="row w-100 align-items-center">
            <div class="col-lg-7 text-start">
              <span class="bee-hero-badge cyan">
                <i class="fa-solid fa-user-tie me-1"></i> SMART CASUAL • SƠ MI SỢI TRE
              </span>
              <h1 class="bee-hero-title">
                SƠ MI CÔNG SỞ <br>
                <span class="text-info">KHÁNG NHĂN TỰ NHIÊN</span>
              </h1>
              <p class="bee-hero-subtitle">
                Chất liệu sợi tre Bamboo mát lạnh, thấm hút mồ hôi gấp 3 lần cotton thường. Cổ áo ép keo công nghệ Đức không lo phồng rộp.
              </p>
              <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="btn btn-bee-accent px-4 py-3">
                  <i class="fa-solid fa-cart-shopping me-1"></i> XEM ÁO SƠ MI NAM
                </a>
                <a href="{{ route('client.products.index') }}" class="btn btn-outline-light px-4 py-3 fw-bold" style="border-radius: 8px;">
                  XEM TẤT CẢ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 4: T-Shirt Streetwear -->
      <div class="carousel-item" style="background-image: url('{{ asset('assets/img/products/tshirt_01.jpg') }}');">
        <div class="carousel-overlay">
          <div class="row w-100 align-items-center">
            <div class="col-lg-7 text-start">
              <span class="bee-hero-badge">
                <i class="fa-solid fa-bolt me-1"></i> STREETWEAR 250GSM • FOAM BOXY
              </span>
              <h1 class="bee-hero-title">
                ÁO PHÔNG NAM <br>
                <span class="text-warning">DÀY DẶN &amp; CÁ TÍNH</span>
              </h1>
              <p class="bee-hero-subtitle">
                Định lượng Heavyweight 250GSM chuẩn xuất khẩu, form suông rộng thoải mái che khuyết điểm hoàn hảo cho giới trẻ năng động.
              </p>
              <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('client.products.index', ['category' => 'ao-phong-tshirt-nam']) }}" class="btn btn-bee-accent px-4 py-3">
                  <i class="fa-solid fa-fire me-1"></i> MUA ÁO PHÔNG NGAY
                </a>
                <a href="{{ route('client.products.index', ['category' => 'bo-suu-tap-ao-moi']) }}" class="btn btn-outline-light px-4 py-3 fw-bold" style="border-radius: 8px;">
                  HÀNG MỚI VỀ
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 5: Sweater & Hoodie -->
      <div class="carousel-item" style="background-image: url('{{ asset('assets/img/products/sweater_03.jpg') }}');">
        <div class="carousel-overlay">
          <div class="row w-100 align-items-center">
            <div class="col-lg-7 text-start">
              <span class="bee-hero-badge gold">
                <i class="fa-solid fa-snowflake me-1"></i> WINTER ESSENTIALS • NỈ BÔNG ẤM ÁP
              </span>
              <h1 class="bee-hero-title">
                SWEATER &amp; HOODIE <br>
                <span class="text-warning">ẤM ÁP &amp; PHONG CÁCH</span>
              </h1>
              <p class="bee-hero-subtitle">
                Nỉ da cá &amp; nỉ chân cua 350GSM dày dặn, giữ nhiệt tối ưu cho mùa đông, không xù lông rụng sợi ra quần áo bên trong.
              </p>
              <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('client.products.index', ['category' => 'ao-sweater-ni-nam']) }}" class="btn btn-bee-accent px-4 py-3">
                  <i class="fa-solid fa-mitten me-1"></i> KHÁM PHÁ ÁO SWEATER
                </a>
                <a href="{{ route('client.products.index') }}" class="btn btn-outline-light px-4 py-3 fw-bold" style="border-radius: 8px;">
                  TẤT CẢ SẢN PHẨM
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Carousel Controls -->
    <button class="carousel-control-prev bee-carousel-nav-btn ms-3" type="button" data-bs-target="#beeHeroCarousel" data-bs-slide="prev">
      <i class="fa-solid fa-chevron-left"></i>
      <span class="visually-hidden">Trước</span>
    </button>
    <button class="carousel-control-next bee-carousel-nav-btn me-3" type="button" data-bs-target="#beeHeroCarousel" data-bs-slide="next">
      <i class="fa-solid fa-chevron-right"></i>
      <span class="visually-hidden">Sau</span>
    </button>
  </div>

  <!-- 2. TRUST COMMITMENT BAR (4 CAM KẾT VÀNG) -->
  <div class="bee-trust-bar mb-5">
    <div class="row g-4">
      <div class="col-lg-3 col-sm-6">
        <div class="bee-trust-item">
          <div class="bee-trust-icon"><i class="fa-solid fa-truck-fast"></i></div>
          <div>
            <h6 class="fw-bold mb-1 fs-9 text-uppercase">Miễn Phí Giao Hàng</h6>
            <p class="text-muted small mb-0">Toàn quốc cho đơn từ 300k</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="bee-trust-item">
          <div class="bee-trust-icon"><i class="fa-solid fa-arrows-rotate"></i></div>
          <div>
            <h6 class="fw-bold mb-1 fs-9 text-uppercase">Đổi Trả 30 Ngày</h6>
            <p class="text-muted small mb-0">Hỗ trợ đổi size tận nhà</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="bee-trust-item">
          <div class="bee-trust-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <h6 class="fw-bold mb-1 fs-9 text-uppercase">100% Vải Cao Cấp</h6>
            <p class="text-muted small mb-0">Cotton &amp; Bamboo kiểm định</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="bee-trust-item">
          <div class="bee-trust-icon"><i class="fa-solid fa-headset"></i></div>
          <div>
            <h6 class="fw-bold mb-1 fs-9 text-uppercase">Tư Vấn Size Chuẩn</h6>
            <p class="text-muted small mb-0">Hotline 24/7: 1900 8888</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. TOP CATEGORIES SHOWCASE WITH REAL FASHION PHOTOS -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">DANH MỤC ÁO NAM NỔI BẬT</h2>
        <p class="bee-section-subtitle">Lựa chọn phong cách phù hợp cho công sở, dạo phố và thể thao</p>
      </div>
      <a href="{{ route('client.products.index') }}" class="text-dark fw-bold small text-decoration-none text-uppercase">
        Xem tất cả 50+ mẫu <i class="fa-solid fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-3">
      <!-- Cat 1: Polo -->
      <div class="col-lg-4 col-md-6">
        <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="bee-cat-card-modern">
          <img src="{{ asset('assets/img/products/polo_01.jpg') }}" alt="Áo Polo Nam" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO POLO NAM</h5>
            <span class="cat-count"><i class="fa-solid fa-shirt me-1 text-warning"></i> 10 Mẫu cao cấp</span>
          </div>
        </a>
      </div>

      <!-- Cat 2: Sơ mi -->
      <div class="col-lg-4 col-md-6">
        <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="bee-cat-card-modern">
          <img src="{{ asset('assets/img/products/somi_02.jpg') }}" alt="Áo Sơ Mi Nam" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO SƠ MI NAM</h5>
            <span class="cat-count"><i class="fa-solid fa-user-tie me-1 text-info"></i> 10 Mẫu công sở &amp; casual</span>
          </div>
        </a>
      </div>

      <!-- Cat 3: T-shirt -->
      <div class="col-lg-4 col-md-6">
        <a href="{{ route('client.products.index', ['category' => 'ao-phong-tshirt-nam']) }}" class="bee-cat-card-modern">
          <img src="{{ asset('assets/img/products/tshirt_01.jpg') }}" alt="Áo Phông Nam" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO PHÔNG &amp; T-SHIRT</h5>
            <span class="cat-count"><i class="fa-solid fa-tshirt me-1 text-danger"></i> 10 Mẫu 250GSM Boxy</span>
          </div>
        </a>
      </div>

      <!-- Cat 4: Blazer -->
      <div class="col-lg-6 col-md-6">
        <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="bee-cat-card-modern" style="height: 220px;">
          <img src="{{ asset('assets/img/products/outerwear_01.jpg') }}" alt="Áo Khoác & Blazer" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO KHOÁC &amp; BLAZER NAM</h5>
            <span class="cat-count"><i class="fa-solid fa-vest me-1 text-warning"></i> 10 Mẫu may đo Hàn Quốc &amp; Da thật</span>
          </div>
        </a>
      </div>

      <!-- Cat 5: Sweater -->
      <div class="col-lg-6 col-md-6">
        <a href="{{ route('client.products.index', ['category' => 'ao-sweater-ni-nam']) }}" class="bee-cat-card-modern" style="height: 220px;">
          <img src="{{ asset('assets/img/products/sweater_01.jpg') }}" alt="Áo Sweater & Nỉ" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO SWEATER &amp; HOODIE NỈ</h5>
            <span class="cat-count"><i class="fa-solid fa-snowflake me-1 text-primary"></i> 10 Mẫu nỉ da cá &amp; len dệt</span>
          </div>
        </a>
      </div>
    </div>
  </div>

  <!-- 4. FLASH SALE & DEAL OF THE DAY -->
  <div class="card border-0 shadow-sm mb-5 p-4" style="background: #ffffff; border: 1.5px solid #ffe4e6 !important; border-radius: 16px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-bolt me-1"></i> FLASH SALE</span>
        <h3 class="fw-bold text-dark mb-0 fs-5 text-uppercase" style="font-family: var(--atino-font-heading);">ƯU ĐÃI TRONG NGÀY</h3>
      </div>
      <div class="d-flex align-items-center gap-2 text-muted small">
        <span class="fw-bold text-dark">KẾT THÚC SAU:</span>
        <span class="badge bg-dark text-white p-2 font-monospace fs-6" id="cdHours">08</span> :
        <span class="badge bg-dark text-white p-2 font-monospace fs-6" id="cdMinutes">45</span> :
        <span class="badge bg-dark text-white p-2 font-monospace fs-6" id="cdSeconds">19</span>
      </div>
    </div>

    <div class="row g-3">
      @foreach($featuredProducts->take(4) as $product)
        <div class="col-lg-3 col-md-6 col-6">
          <div class="bee-product-card">
            @if($product->discount_percent > 0)
              <span class="bee-product-badge sale">-{{ $product->discount_percent }}%</span>
            @endif
            
            <div class="bee-product-actions">
              <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
              <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Thêm vào giỏ"><i class="fa-solid fa-cart-plus"></i></a>
            </div>

            <div class="bee-product-img-wrapper">
              <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            </div>

            <div class="bee-product-body">
              <span class="bee-product-category">{{ $product->category->name ?? 'Áo Nam' }}</span>
              <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title">
                {{ $product->name }}
              </a>

              <div class="bee-product-rating">
                @for($i=1; $i<=5; $i++)
                  <i class="fa-solid fa-star text-warning"></i>
                @endfor
                <span class="text-muted ms-auto small d-none d-sm-inline">Đã bán {{ $product->sold_count }}</span>
              </div>

              <div class="bee-product-price-row">
                <span class="bee-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                @if($product->original_price && $product->original_price > $product->price)
                  <span class="bee-product-old-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                @endif
              </div>

              <a href="{{ route('client.products.show', $product->id) }}" class="btn btn-bee-primary btn-sm w-100 mt-2">
                <i class="fa-solid fa-cart-shopping me-1"></i> XEM CHI TIẾT
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- 5. BEST SELLERS SECTION (ÁO NAM BÁN CHẠY NHẤT) -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">SẢN PHẨM BÁN CHẠY NHẤT</h2>
        <p class="bee-section-subtitle">Những mẫu áo nam nhận được trên 95% đánh giá 5 sao từ khách mua</p>
      </div>
      <a href="{{ route('client.products.index') }}" class="btn btn-bee-outline btn-sm">
        Xem Tất Cả Sản Phẩm
      </a>
    </div>

    <div class="row g-4">
      @foreach($bestSellers->take(8) as $product)
        <div class="col-lg-3 col-md-6 col-6">
          <div class="bee-product-card">
            @if($product->is_new)
              <span class="bee-product-badge new">MỚI</span>
            @elseif($product->discount_percent > 0)
              <span class="bee-product-badge sale">-{{ $product->discount_percent }}%</span>
            @endif
            
            <div class="bee-product-actions">
              <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
              <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Thêm vào giỏ"><i class="fa-solid fa-cart-plus"></i></a>
            </div>

            <div class="bee-product-img-wrapper">
              <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            </div>

            <div class="bee-product-body">
              <span class="bee-product-category">{{ $product->category->name ?? 'Thời Trang Nam' }}</span>
              <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title">
                {{ $product->name }}
              </a>

              <div class="bee-product-rating">
                <i class="fa-solid fa-star text-warning"></i>
                <span class="fw-bold text-dark">{{ $product->rating }}</span>
                <span class="text-muted ms-auto small d-none d-sm-inline">Đã bán {{ $product->sold_count }}</span>
              </div>

              <div class="bee-product-price-row">
                <span class="bee-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                @if($product->original_price && $product->original_price > $product->price)
                  <span class="bee-product-old-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                @endif
              </div>

              <a href="{{ route('client.products.show', $product->id) }}" class="btn btn-bee-primary btn-sm w-100 mt-2">
                <i class="fa-solid fa-bag-shopping me-1"></i> MUA NGAY
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- 6. STYLE GUIDE / LOOKBOOK (GỢI Ý PHỐI ĐỒ QUÝ ÔNG) -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">GỢI Ý PHỐI ĐỒ • STYLE LOOKBOOK</h2>
        <p class="bee-section-subtitle">Định hình phong cách thời trang chuẩn quý ông hiện đại cho từng hoàn cảnh</p>
      </div>
    </div>

    <div class="row g-4">
      <!-- Lookbook 1: Smart Casual -->
      <div class="col-lg-4 col-md-6">
        <div class="bee-lookbook-card">
          <img src="{{ asset('assets/img/products/outerwear_01.jpg') }}" alt="Smart Casual" class="bee-lookbook-img">
          <div class="p-4">
            <span class="badge bg-dark-subtle text-dark fw-bold text-uppercase mb-2">SMART CASUAL CÔNG SỞ</span>
            <h5 class="fw-bold text-dark mb-2">Áo Sơ Mi Bamboo + Blazer Hàn Quốc</h5>
            <p class="text-muted small mb-3">
              Set đồ hoàn hảo cho các buổi họp kinh doanh, đàm phán hợp đồng hay môi trường văn phòng đòi hỏi sự lịch lãm, tinh tế.
            </p>
            <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="btn btn-bee-outline btn-sm w-100">
              Khám Phá Set Đồ <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Lookbook 2: Weekend Casual -->
      <div class="col-lg-4 col-md-6">
        <div class="bee-lookbook-card">
          <img src="{{ asset('assets/img/products/polo_01.jpg') }}" alt="Weekend Casual" class="bee-lookbook-img">
          <div class="p-4">
            <span class="badge bg-warning-subtle text-dark fw-bold text-uppercase mb-2">WEEKEND DẠO PHỐ</span>
            <h5 class="fw-bold text-dark mb-2">Áo Polo Tổ Ong + Quần Kaki Trẻ Trung</h5>
            <p class="text-muted small mb-3">
              Phong cách năng động, phóng khoáng cho những buổi hẹn hò cà phê cuối tuần hoặc du lịch dã ngoại cùng người thân.
            </p>
            <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="btn btn-bee-outline btn-sm w-100">
              Khám Phá Set Đồ <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Lookbook 3: Streetwear -->
      <div class="col-lg-4 col-md-6">
        <div class="bee-lookbook-card">
          <img src="{{ asset('assets/img/products/sweater_02.jpg') }}" alt="Urban Streetwear" class="bee-lookbook-img">
          <div class="p-4">
            <span class="badge bg-danger-subtle text-danger fw-bold text-uppercase mb-2">URBAN STREETWEAR</span>
            <h5 class="fw-bold text-dark mb-2">Áo Phông 250GSM + Hoodie Nỉ Bông</h5>
            <p class="text-muted small mb-3">
              Cá tính đường phố đậm chất Gen Z với form dáng thụng Boxy, mang lại cảm giác thoải mái tối đa và cực kỳ ăn ảnh.
            </p>
            <a href="{{ route('client.products.index', ['category' => 'ao-sweater-ni-nam']) }}" class="btn btn-bee-outline btn-sm w-100">
              Khám Phá Set Đồ <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 7. COUPON VOUCHERS (MÃ GIẢM GIÁ ĐANG CÓ SẴN) -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">MÃ GIẢM GIÁ ĐANG CÓ SẴN</h2>
        <p class="bee-section-subtitle">Thu thập voucher để nhận ưu đãi thanh toán tốt nhất từ BeeStyle</p>
      </div>
    </div>

    <div class="row g-3">
      @foreach($coupons as $coupon)
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm p-3 h-100" style="background: #ffffff; border-left: 4px solid var(--atino-red) !important; border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <span class="badge bg-danger-subtle text-danger fw-bold font-monospace px-2 py-1">{{ $coupon->code }}</span>
                <h6 class="fw-bold text-dark mt-2 mb-1 fs-9" style="font-family: var(--atino-font-heading);">{{ $coupon->title }}</h6>
              </div>
              <i class="fa-solid fa-ticket text-danger fs-4"></i>
            </div>
            <p class="text-muted fs-11 mb-3">
              Hạn: {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Vô thời hạn' }} • Đã dùng {{ $coupon->used_count }}/{{ $coupon->total_limit }}
            </p>
            <div class="mt-auto">
              <button type="button" class="btn btn-sm btn-bee-outline w-100" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); alert('Đã sao chép mã: {{ $coupon->code }}');">
                <i class="fa-regular fa-copy me-1"></i> SAO CHÉP MÃ
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- 8. CUSTOMER REVIEWS & TESTIMONIALS (KHÁCH HÀNG ĐÁNH GIÁ 5 SAO) -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">KHÁCH HÀNG NÓI VỀ BEESTYLE</h2>
        <p class="bee-section-subtitle">Hơn 50.000+ quý ông trên toàn quốc đã tin tưởng và đồng hành</p>
      </div>
    </div>

    <div class="row g-3">
      @forelse($reviews as $rev)
        <div class="col-lg-4 col-md-6">
          <div class="bee-review-box">
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="text-warning">
                @for($i=1; $i<=$rev->rating; $i++)
                  <i class="fa-solid fa-star"></i>
                @endfor
              </div>
              <span class="badge bg-success-subtle text-success ms-auto fs-11">
                <i class="fa-solid fa-circle-check me-1"></i> Đã mua hàng
              </span>
            </div>
            <p class="text-dark small mb-3 fst-italic flex-grow-1">
              "{{ $rev->comment }}"
            </p>
            <div class="d-flex align-items-center gap-2 pt-2 border-top">
              <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                {{ mb_substr($rev->user_name ?? 'KH', 0, 1) }}
              </div>
              <div>
                <div class="fw-bold text-dark fs-9">{{ $rev->user_name ?? 'Khách Hàng Thân Thiết' }}</div>
                <small class="text-muted fs-11">{{ $rev->product->name ?? 'Áo Nam BeeStyle' }}</small>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center text-muted py-4">Đang cập nhật đánh giá khách hàng...</div>
      @endforelse
    </div>
  </div>

  <!-- 9. VIP CLUB BANNER & NEWSLETTER -->
  <div class="bee-vip-card mb-5">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1 text-uppercase mb-2">
          <i class="fa-solid fa-crown me-1"></i> BEESTYLE VIP CLUB
        </span>
        <h2 class="fw-bold text-white mb-2" style="font-family: var(--atino-font-heading);">
          GIA NHẬP HỘI VIÊN &amp; NHẬN NGAY VOUCHER 50K
        </h2>
        <p class="text-white-50 small mb-0">
          Tích điểm trên mọi hóa đơn (100k = 10 điểm), đổi điểm lấy quà tặng áo nam độc quyền và nhận ưu đãi sinh nhật giảm 20%.
        </p>
      </div>
      <div class="col-lg-5 text-lg-end">
        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
          <a href="{{ route('auth.register') }}" class="btn btn-bee-accent px-4 py-3">
            <i class="fa-solid fa-user-plus me-1"></i> ĐĂNG KÝ HỘI VIÊN
          </a>
          <a href="{{ route('client.products.index') }}" class="btn btn-outline-light px-4 py-3 fw-bold" style="border-radius: 8px;">
            MUA SẮM NGAY
          </a>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- JavaScript: Auto-playing Carousel & Live Flash Sale Countdown -->
@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // 1. Tự động chạy Carousel Banner 5 ảnh (3.0 giây chuyển 1 lần)
    const carouselEl = document.getElementById('beeHeroCarousel');
    if (carouselEl) {
      // Khởi tạo Bootstrap Carousel với chu kỳ 3000ms
      if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
        const bsCarousel = new bootstrap.Carousel(carouselEl, {
          interval: 3000,
          ride: 'carousel',
          pause: 'hover',
          wrap: true
        });
        bsCarousel.cycle();
      }

      // Fallback Timer đảm bảo 100% tự động trượt mượt mà (3.0s)
      let autoSlideTimer = setInterval(function () {
        const nextBtn = carouselEl.querySelector('.carousel-control-next');
        if (nextBtn && !carouselEl.matches(':hover')) {
          nextBtn.click();
        }
      }, 3000);

      // Tạm dừng khi rê chuột và tiếp tục khi rê ra ngoài
      carouselEl.addEventListener('mouseenter', function () {
        clearInterval(autoSlideTimer);
      });
      carouselEl.addEventListener('mouseleave', function () {
        clearInterval(autoSlideTimer);
        autoSlideTimer = setInterval(function () {
          const nextBtn = carouselEl.querySelector('.carousel-control-next');
          if (nextBtn) nextBtn.click();
        }, 3000);
      });
    }

    // 2. Đồng hồ đếm ngược Flash Sale
    let hours = 8, minutes = 45, seconds = 19;
    setInterval(function () {
      if (seconds > 0) {
        seconds--;
      } else {
        seconds = 59;
        if (minutes > 0) {
          minutes--;
        } else {
          minutes = 59;
          if (hours > 0) hours--;
        }
      }
      const hEl = document.getElementById("cdHours");
      const mEl = document.getElementById("cdMinutes");
      const sEl = document.getElementById("cdSeconds");
      if (hEl) hEl.textContent = String(hours).padStart(2, '0');
      if (mEl) mEl.textContent = String(minutes).padStart(2, '0');
      if (sEl) sEl.textContent = String(seconds).padStart(2, '0');
    }, 1000);
  });
</script>
@endpush
@endsection