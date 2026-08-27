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
                <a href="{{ route('client.products.index', ['sort' => 'newest']) }}" class="btn btn-outline-light px-4 py-3 fw-bold" style="border-radius: 8px;">
                  HÀNG MỚI VỀ
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
        Xem tất cả sản phẩm <i class="fa-solid fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-3">
      <!-- Cat 1: Polo -->
      <div class="col-lg-4 col-md-4 col-6">
        <a href="{{ route('client.categories.show', 'ao-polo-nam') }}" class="bee-cat-card-modern" style="height: 200px;">
          <img src="{{ asset('assets/img/products/polo_01.jpg') }}" alt="Áo Polo Nam" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO POLO NAM</h5>
            <span class="cat-count"><i class="fa-solid fa-shirt me-1 text-warning"></i> 10 Mẫu cao cấp</span>
          </div>
        </a>
      </div>

      <!-- Cat 2: Sơ mi -->
      <div class="col-lg-4 col-md-4 col-6">
        <a href="{{ route('client.categories.show', 'ao-so-mi-nam') }}" class="bee-cat-card-modern" style="height: 200px;">
          <img src="{{ asset('assets/img/products/somi_01.jpg') }}" alt="Áo Sơ Mi Nam" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO SƠ MI NAM</h5>
            <span class="cat-count"><i class="fa-solid fa-user-tie me-1 text-info"></i> 10 Mẫu công sở</span>
          </div>
        </a>
      </div>

      <!-- Cat 3: T-shirt -->
      <div class="col-lg-4 col-md-4 col-6">
        <a href="{{ route('client.categories.show', 'ao-phong-tshirt-nam') }}" class="bee-cat-card-modern" style="height: 200px;">
          <img src="{{ asset('assets/img/products/tshirt_01.jpg') }}" alt="Áo Phông Nam" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO PHÔNG &amp; T-SHIRT</h5>
            <span class="cat-count"><i class="fa-solid fa-vest-patches me-1 text-danger"></i> 10 Mẫu 250GSM</span>
          </div>
        </a>
      </div>

      <!-- Cat 4: Blazer -->
      <div class="col-lg-4 col-md-4 col-6">
        <a href="{{ route('client.categories.show', 'ao-khoac-blazer-nam') }}" class="bee-cat-card-modern" style="height: 200px;">
          <img src="{{ asset('assets/img/products/outerwear_01.jpg') }}" alt="Áo Khoác & Blazer" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO KHOÁC &amp; BLAZER</h5>
            <span class="cat-count"><i class="fa-solid fa-vest me-1 text-warning"></i> 10 Mẫu may đo Hàn</span>
          </div>
        </a>
      </div>

      <!-- Cat 5: Áo Thun -->
      <div class="col-lg-4 col-md-4 col-6">
        <a href="{{ route('client.categories.show', 'ao-thun-nam') }}" class="bee-cat-card-modern" style="height: 200px;">
          <img src="{{ asset('assets/img/products/tshirt_black.jpg') }}" alt="Áo Thun Nam" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO THUN NAM</h5>
            <span class="cat-count"><i class="fa-solid fa-layer-group me-1 text-warning"></i> 10 Mẫu co giãn 4 chiều</span>
          </div>
        </a>
      </div>

      <!-- Cat 6: Áo Thu Đông -->
      <div class="col-lg-4 col-md-4 col-6">
        <a href="{{ route('client.categories.show', 'ao-thu-dong-nam') }}" class="bee-cat-card-modern" style="height: 200px;">
          <img src="{{ asset('assets/img/products/hoodie_1.jpg') }}" alt="Áo Thu Đông Nam" class="cat-bg-img">
          <div class="cat-overlay"></div>
          <div class="cat-content">
            <h5 class="cat-title">ÁO THU ĐÔNG &amp; HOODIE</h5>
            <span class="cat-count"><i class="fa-solid fa-snowflake me-1 text-info"></i> 10 Mẫu nỉ bông ấm</span>
          </div>
        </a>
      </div>
    </div>
  </div>

  <!-- 3.5 BỘ SƯU TẬP THƯƠNG HIỆU ĐỐI TÁC (BRANDS SHOWCASE) -->
  @if(isset($brands) && $brands->isNotEmpty())
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;">
            <i class="fa-solid fa-crown me-1"></i> BỘ SƯU TẬP ĐỘC QUYỀN
          </span>
        </div>
        <h2 class="bee-section-title">THƯƠNG HIỆU THỜI TRANG ĐẲNG CẤP</h2>
        <p class="bee-section-subtitle">Khám phá các thương hiệu thời trang nam cao cấp độc quyền tại BeeStyle</p>
      </div>
      <a href="{{ route('client.brands.index') }}" class="text-dark fw-bold small text-decoration-none text-uppercase">
        Xem tất cả thương hiệu <i class="fa-solid fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-3">
      @foreach($brands as $brand)
        <div class="col-lg-3 col-md-6 col-6">
          <a href="{{ route('client.brands.show', $brand->slug) }}" class="card border-0 shadow-sm text-decoration-none h-100 transition-all hover-lift" style="border-radius: 16px; overflow: hidden; background: #ffffff; border: 1px solid rgba(0,0,0,0.06);">
            <div class="card-body p-4 text-center d-flex flex-column justify-content-between align-items-center">
              <div class="bg-light rounded-circle p-3 mb-3 d-flex align-items-center justify-content-center shadow-xs" style="width: 70px; height: 70px;">
                @if(!empty($brand->logo))
                  <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 45px; max-height: 45px; object-fit: contain;" onerror="this.onerror=null; this.src=''; this.classList.add('d-none'); this.nextElementSibling.classList.remove('d-none');">
                  <i class="fa-solid fa-crown text-warning fs-3 d-none"></i>
                @else
                  <i class="fa-solid fa-crown text-warning fs-3"></i>
                @endif
              </div>
              <div>
                <h6 class="fw-bold text-dark mb-1">{{ $brand->name }}</h6>
                <p class="text-secondary fs-11 mb-2 text-truncate-2" style="min-height: 32px; line-height: 1.4;">
                  {{ Str::limit($brand->description, 60) }}
                </p>
              </div>
              <span class="badge bg-warning-subtle text-dark fw-bold px-3 py-1 rounded-pill fs-11 mt-auto">
                Khám Phá <i class="fa-solid fa-arrow-right ms-1"></i>
              </span>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  <!-- 4. FLASH SALE & DEAL OF THE DAY (CHỈ HIỂN THỊ KHI CÓ ƯU ĐÃI ĐANG TRONG KHUNG GIỜ VÀNG) -->
  @if(isset($runningDailyDeals) && $runningDailyDeals->isNotEmpty())
    <div id="flash-sale" class="card border-0 shadow-sm mb-5 p-4" style="background: #ffffff; border: 1.5px solid #ffe4e6 !important; border-radius: 16px;">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-bolt me-1"></i> FLASH SALE</span>
          <h3 class="fw-bold text-dark mb-0 fs-5 text-uppercase" style="font-family: var(--atino-font-heading);">ƯU ĐÃI TRONG NGÀY</h3>
          @if(!empty($currentSlotName))
            <span class="badge bg-warning-subtle text-dark border border-warning px-2.5 py-1 fw-bold fs-9">
              <i class="fa-regular fa-clock me-1 text-warning"></i> {{ $currentSlotName }}
            </span>
          @endif
        </div>
        <div class="d-flex align-items-center gap-3 flex-wrap">
          <div class="d-flex align-items-center gap-2 text-muted small" id="dealCountdownWrapper" data-target="{{ $targetCountdown }}">
            <span class="fw-bold text-dark">KẾT THÚC SAU:</span>
            <span class="badge bg-dark text-white p-2 font-monospace fs-6" id="cdHours">00</span> :
            <span class="badge bg-dark text-white p-2 font-monospace fs-6" id="cdMinutes">00</span> :
            <span class="badge bg-dark text-white p-2 font-monospace fs-6" id="cdSeconds">00</span>
          </div>
          <a href="{{ route('client.daily-deals.index') }}" class="btn btn-outline-danger btn-sm px-3 fw-bold rounded-pill shadow-xs">
            Xem Thêm <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <div class="row g-3">
        @foreach($runningDailyDeals as $deal)
          @php
            $product = $deal->product;
          @endphp
          @if($product)
            <div class="col-lg-3 col-md-6 col-6">
              <div class="bee-product-card h-100 d-flex flex-column position-relative">
                <!-- BADGE KHUYẾN MÃI % -->
                <span class="bee-product-badge sale shadow-xs">-{{ $deal->discount_percent }}%</span>
                
                <div class="bee-product-actions">
                  <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
                  <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Mua ngay"><i class="fa-solid fa-cart-plus"></i></a>
                </div>

                <div class="bee-product-img-wrapper">
                  <a href="{{ route('client.products.show', $product->id) }}">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                  </a>
                </div>

                <div class="bee-product-body d-flex flex-column flex-grow-1">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="bee-product-category">{{ $product->category->name ?? 'Áo Nam' }}</span>
                    @if($deal->slot_name)
                      <span class="badge bg-light text-danger border border-danger-subtle fs-10 px-1.5 py-0.5">{{ $deal->slot_name }}</span>
                    @endif
                  </div>

                  <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title">
                    {{ $product->name }}
                  </a>

                  <!-- TIẾN ĐỘ BÁN HÀNG HOẶC ĐÁNH GIÁ -->
                  <div class="my-1.5">
                    @if($deal->quantity_limit > 0)
                      @php
                        $soldPct = min(100, round(($deal->sold_count / $deal->quantity_limit) * 100));
                      @endphp
                      <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                        <span><i class="fa-solid fa-fire text-danger me-1"></i>Đã bán {{ $deal->sold_count }}</span>
                        <span>Còn {{ max(0, $deal->quantity_limit - $deal->sold_count) }} suất</span>
                      </div>
                      <div class="progress mt-1" style="height: 5px; border-radius: 99px;">
                        <div class="progress-bar bg-danger" style="width: {{ $soldPct }}%"></div>
                      </div>
                    @else
                      <div class="bee-product-rating">
                        @for($i=1; $i<=5; $i++)
                          <i class="fa-solid fa-star text-warning"></i>
                        @endfor
                        <span class="text-muted ms-auto small d-none d-sm-inline">Đã bán {{ $product->sold_count }}</span>
                      </div>
                    @endif
                  </div>

                  <!-- GIÁ BÁN FLASH SALE -->
                  <div class="bee-product-price-row mt-auto">
                    <span class="bee-product-price text-danger">{{ number_format($deal->deal_price, 0, ',', '.') }}₫</span>
                    <span class="bee-product-old-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                  </div>

                  <a href="{{ route('client.products.show', $product->id) }}" class="btn btn-bee-primary btn-sm w-100 mt-2">
                    <i class="fa-solid fa-bolt me-1"></i> SĂN DEAL NGAY
                  </a>
                </div>
              </div>
            </div>
          @endif
        @endforeach
      </div>

      <!-- NÚT XEM TẤT CẢ SẢN PHẨM KHUYẾN MÃI -->
      <div class="text-center mt-4 pt-3 border-top border-danger border-opacity-10">
        <a href="{{ route('client.daily-deals.index') }}" class="btn btn-danger px-4 py-2.5 fw-bold rounded-pill shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
          <i class="fa-solid fa-bolt text-warning"></i>
          <span>XEM TẤT CẢ SẢN PHẨM KHUYẾN MÃI HÔM NAY</span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  @endif

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

  <!-- 6. CATEGORY SPOTLIGHT TABS (KHÁM PHÁ THEO TỪNG DÒNG ÁO NAM) -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">BỘ SƯU TẬP THEO DÒNG SẢN PHẨM</h2>
        <p class="bee-section-subtitle">Tuyển tập các thiết kế cao cấp độc quyền từ BeeStyle Menswear</p>
      </div>
      <ul class="nav nav-pills gap-2 flex-wrap" id="spotlightTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active btn-sm px-3 py-1.5 fw-bold rounded-pill" id="tab-polo" data-bs-toggle="pill" data-bs-target="#content-polo" type="button" role="tab">
            <i class="fa-solid fa-shirt me-1"></i> Áo Polo
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link btn-sm px-3 py-1.5 fw-bold rounded-pill" id="tab-shirt" data-bs-toggle="pill" data-bs-target="#content-shirt" type="button" role="tab">
            <i class="fa-solid fa-user-tie me-1"></i> Áo Sơ Mi
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link btn-sm px-3 py-1.5 fw-bold rounded-pill" id="tab-blazer" data-bs-toggle="pill" data-bs-target="#content-blazer" type="button" role="tab">
            <i class="fa-solid fa-vest me-1"></i> Blazer &amp; Khoác
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link btn-sm px-3 py-1.5 fw-bold rounded-pill" id="tab-thun" data-bs-toggle="pill" data-bs-target="#content-thun" type="button" role="tab">
            <i class="fa-solid fa-layer-group me-1"></i> Áo Thun Nam
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link btn-sm px-3 py-1.5 fw-bold rounded-pill" id="tab-thudong" data-bs-toggle="pill" data-bs-target="#content-thudong" type="button" role="tab">
            <i class="fa-solid fa-snowflake me-1"></i> Áo Thu Đông
          </button>
        </li>
      </ul>
    </div>

    <div class="tab-content" id="spotlightTabsContent">
      <!-- Tab 1: Polo -->
      <div class="tab-pane fade show active" id="content-polo" role="tabpanel">
        <div class="row g-4">
          @foreach($poloSpotlight as $product)
            <div class="col-lg-3 col-md-6 col-6">
              <div class="bee-product-card">
                @if($product->discount_percent > 0)
                  <span class="bee-product-badge sale">-{{ $product->discount_percent }}%</span>
                @endif
                <div class="bee-product-actions">
                  <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
                  <button type="button" class="btn-action btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active text-danger' : '' }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Yêu thích sản phẩm">
                    <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }}"></i>
                  </button>
                </div>
                <div class="bee-product-img-wrapper">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </div>
                <div class="bee-product-body">
                  <span class="bee-product-category">Áo Polo Cao Cấp</span>
                  <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title">{{ $product->name }}</a>
                  <div class="bee-product-price-row">
                    <span class="bee-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @if($product->original_price && $product->original_price > $product->price)
                      <span class="bee-product-old-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                    @endif
                  </div>
                  <div class="d-flex gap-1.5 mt-2">
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Polo Cao Cấp"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, false, this)" 
                      title="Thêm vào giỏ hàng (Chọn màu & size)" style="font-size: 0.76rem;">
                      <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Vào Giỏ Hàng
                    </button>
                    <button type="button" class="btn btn-bee-primary btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Polo Cao Cấp"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, true, this)" 
                      title="Mua hàng ngay (Chọn màu & size)" style="font-size: 0.78rem;">
                      <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
                    </button>
                  </div>
                </div>

              </div>
            </div>
          @endforeach
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="btn btn-bee-primary px-4 py-2">
            Xem Tất Cả Mẫu Polo <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Tab 2: Shirt -->
      <div class="tab-pane fade" id="content-shirt" role="tabpanel">
        <div class="row g-4">
          @foreach($shirtSpotlight as $product)
            <div class="col-lg-3 col-md-6 col-6">
              <div class="bee-product-card">
                @if($product->discount_percent > 0)
                  <span class="bee-product-badge sale">-{{ $product->discount_percent }}%</span>
                @endif
                <div class="bee-product-actions">
                  <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
                  <button type="button" class="btn-action btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active text-danger' : '' }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Yêu thích sản phẩm">
                    <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }}"></i>
                  </button>
                </div>
                <div class="bee-product-img-wrapper">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </div>

                <div class="bee-product-body">
                  <span class="bee-product-category">Áo Sơ Mi Nam</span>
                  <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title">{{ $product->name }}</a>
                  <div class="bee-product-price-row">
                    <span class="bee-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @if($product->original_price && $product->original_price > $product->price)
                      <span class="bee-product-old-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                    @endif
                  </div>
                  <div class="d-flex gap-1.5 mt-2">
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Sơ Mi Nam"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, false, this)" 
                      title="Thêm vào giỏ hàng (Chọn màu & size)" style="font-size: 0.76rem;">
                      <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Vào Giỏ Hàng
                    </button>
                    <button type="button" class="btn btn-bee-primary btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Sơ Mi Nam"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, true, this)" 
                      title="Mua hàng ngay (Chọn màu & size)" style="font-size: 0.78rem;">
                      <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
                    </button>
                  </div>
                </div>


              </div>
            </div>
          @endforeach
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="btn btn-bee-primary px-4 py-2">
            Xem Tất Cả Mẫu Sơ Mi <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Tab 3: Blazer -->
      <div class="tab-pane fade" id="content-blazer" role="tabpanel">
        <div class="row g-4">
          @foreach($blazerSpotlight as $product)
            <div class="col-lg-3 col-md-6 col-6">
              <div class="bee-product-card">
                @if($product->discount_percent > 0)
                  <span class="bee-product-badge sale">-{{ $product->discount_percent }}%</span>
                @endif
                <div class="bee-product-actions">
                  <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
                  <button type="button" class="btn-action btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active text-danger' : '' }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Yêu thích sản phẩm">
                    <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }}"></i>
                  </button>
                </div>
                <div class="bee-product-img-wrapper">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </div>
                <div class="bee-product-body">
                  <span class="bee-product-category">Áo Khoác &amp; Blazer</span>
                  <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title">{{ $product->name }}</a>
                  <div class="bee-product-price-row">
                    <span class="bee-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @if($product->original_price && $product->original_price > $product->price)
                      <span class="bee-product-old-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                    @endif
                  </div>
                  <div class="d-flex gap-1.5 mt-2">
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Khoác & Blazer"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, false, this)" 
                      title="Thêm vào giỏ hàng (Chọn màu & size)" style="font-size: 0.76rem;">
                      <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Vào Giỏ Hàng
                    </button>
                    <button type="button" class="btn btn-bee-primary btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Khoác & Blazer"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, true, this)" 
                      title="Mua hàng ngay (Chọn màu & size)" style="font-size: 0.78rem;">
                      <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="btn btn-bee-primary px-4 py-2">
            Xem Tất Cả Mẫu Blazer <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Tab 4: Áo Thun -->
      <div class="tab-pane fade" id="content-thun" role="tabpanel">
        <div class="row g-4">
          @foreach($thunSpotlight as $product)
            <div class="col-lg-3 col-md-6 col-6">
              <div class="bee-product-card">
                @if($product->discount_percent > 0)
                  <span class="bee-product-badge sale">-{{ $product->discount_percent }}%</span>
                @endif
                <div class="bee-product-actions">
                  <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
                  <button type="button" class="btn-action btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active text-danger' : '' }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Yêu thích sản phẩm">
                    <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }}"></i>
                  </button>
                </div>
                <div class="bee-product-img-wrapper">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </div>
                <div class="bee-product-body">
                  <span class="bee-product-category">Áo Thun Nam Cao Cấp</span>
                  <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title">{{ $product->name }}</a>
                  <div class="bee-product-price-row">
                    <span class="bee-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @if($product->original_price && $product->original_price > $product->price)
                      <span class="bee-product-old-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                    @endif
                  </div>
                  <div class="d-flex gap-1.5 mt-2">
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Thun Nam"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, false, this)" 
                      title="Thêm vào giỏ hàng (Chọn màu & size)" style="font-size: 0.76rem;">
                      <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Vào Giỏ Hàng
                    </button>
                    <button type="button" class="btn btn-bee-primary btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Thun Nam"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, true, this)" 
                      title="Mua hàng ngay (Chọn màu & size)" style="font-size: 0.78rem;">
                      <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}" class="btn btn-bee-primary px-4 py-2">
            Xem Tất Cả Mẫu Áo Thun <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>

      <!-- Tab 5: Thu Dong -->
      <div class="tab-pane fade" id="content-thudong" role="tabpanel">
        <div class="row g-4">
          @foreach($thuDongSpotlight as $product)
            <div class="col-lg-3 col-md-6 col-6">
              <div class="bee-product-card">
                @if($product->discount_percent > 0)
                  <span class="bee-product-badge sale">-{{ $product->discount_percent }}%</span>
                @endif
                <div class="bee-product-actions">
                  <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
                  <button type="button" class="btn-action btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active text-danger' : '' }}" onclick="toggleWishlist({{ $product->id }}, this)" title="Yêu thích sản phẩm">
                    <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }}"></i>
                  </button>
                </div>
                <div class="bee-product-img-wrapper">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </div>

                <div class="bee-product-body">
                  <span class="bee-product-category">Áo Thu Đông &amp; Hoodie</span>
                  <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title">{{ $product->name }}</a>
                  <div class="bee-product-price-row">
                    <span class="bee-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @if($product->original_price && $product->original_price > $product->price)
                      <span class="bee-product-old-price">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
                    @endif
                  </div>
                  <div class="d-flex gap-1.5 mt-2">
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Thu Đông & Hoodie"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, false, this)" 
                      title="Thêm vào giỏ hàng (Chọn màu & size)" style="font-size: 0.76rem;">
                      <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Vào Giỏ Hàng
                    </button>
                    <button type="button" class="btn btn-bee-primary btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="Áo Thu Đông & Hoodie"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, true, this)" 
                      title="Mua hàng ngay (Chọn màu & size)" style="font-size: 0.78rem;">
                      <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('client.products.index', ['category' => 'ao-thu-dong-nam']) }}" class="btn btn-bee-primary px-4 py-2">
            Xem Tất Cả Áo Thu Đông <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
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

  <!-- 8. SMART SIZE FINDER & FABRIC TECHNOLOGY (BẢNG TRA CỨU SIZE & 4 CHẤT LIỆU VÀNG) -->
  <div class="mb-5" id="size-fabric-section">
    <div class="bee-section-header">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;">
            <i class="fa-solid fa-ruler-combined me-1"></i> TIỆN ÍCH MUA SẮM THÔNG MINH
          </span>
        </div>
        <h2 class="bee-section-title">BẢNG TRA CỨU SIZE &amp; CÔNG NGHỆ CHẤT LIỆU</h2>
        <p class="bee-section-subtitle">Tự tin chọn đúng size chuẩn may đo quý ông và trải nghiệm 4 chất liệu thượng hạng độc quyền từ BeeStyle</p>
      </div>
      <button type="button" class="btn btn-bee-outline btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#sizeGuideModal">
        <i class="fa-solid fa-table-cells me-1 text-warning"></i> Bảng Thông Số Chi Tiết
      </button>
    </div>

    <div class="row g-4 align-items-stretch">
      <!-- CỘT TRÁI: BỘ TRA CỨU SIZE NHANH (SMART SIZE FINDER) -->
      <div class="col-lg-5">
        <div class="bee-size-finder-card p-4 h-100 d-flex flex-column justify-content-between">
          <div>
            <!-- Header Tra Cứu -->
            <div class="d-flex align-items-center justify-content-between mb-3.5 pb-2 border-bottom">
              <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-3 bg-warning text-dark d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                  <i class="fa-solid fa-calculator fs-5"></i>
                </div>
                <div>
                  <h5 class="fw-bold text-dark mb-0">Tra Cứu Size Nhanh</h5>
                  <small class="text-muted">Nhập số đo để nhận gợi ý form áo chuẩn</small>
                </div>
              </div>
              <span class="badge bg-danger-subtle text-danger fw-bold border border-danger-subtle px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                <i class="fa-solid fa-sparkles me-1 text-warning"></i> Chuẩn 99%
              </span>
            </div>

            <!-- Ô nhập Chiều cao & Cân nặng -->
            <div class="row g-3 mb-3">
              <!-- Ô 1: Chiều cao -->
              <div class="col-6">
                <label for="inputHeight" class="form-label small fw-bold text-dark mb-1.5 d-flex align-items-center justify-content-between">
                  <span><i class="fa-solid fa-arrows-up-down text-warning me-1"></i> Chiều cao</span>
                  <span class="text-muted fs-11 fw-normal">(140 - 210)</span>
                </label>
                <div class="bee-size-input-group d-flex align-items-center">
                  <input type="number" id="inputHeight" placeholder="170" min="130" max="220" value="170">
                  <span class="unit-tag">cm</span>
                </div>
              </div>

              <!-- Ô 2: Cân nặng -->
              <div class="col-6">
                <label for="inputWeight" class="form-label small fw-bold text-dark mb-1.5 d-flex align-items-center justify-content-between">
                  <span><i class="fa-solid fa-weight-scale text-warning me-1"></i> Cân nặng</span>
                  <span class="text-muted fs-11 fw-normal">(40 - 130)</span>
                </label>
                <div class="bee-size-input-group d-flex align-items-center">
                  <input type="number" id="inputWeight" placeholder="65" min="35" max="150" value="65">
                  <span class="unit-tag">kg</span>
                </div>
              </div>
            </div>

            <!-- Lựa chọn Form Áo Segmented Control -->
            <div class="mb-3.5">
              <label class="form-label small fw-bold text-dark mb-1.5 d-block">
                <i class="fa-solid fa-person text-secondary me-1"></i> Phong cách mặc mong muốn:
              </label>
              <div class="bee-fit-segmented" id="fitSelector">
                <input type="radio" class="btn-check" name="fitStyle" id="fitSlim" value="slim" autocomplete="off">
                <label class="btn" for="fitSlim"><i class="fa-solid fa-compress-alt me-1"></i> Ôm vừa</label>

                <input type="radio" class="btn-check" name="fitStyle" id="fitRegular" value="regular" autocomplete="off" checked>
                <label class="btn" for="fitRegular"><i class="fa-solid fa-user-check me-1"></i> Tiêu chuẩn</label>

                <input type="radio" class="btn-check" name="fitStyle" id="fitLoose" value="loose" autocomplete="off">
                <label class="btn" for="fitLoose"><i class="fa-solid fa-expand-alt me-1"></i> Rộng rãi</label>
              </div>
            </div>

            <!-- Nút Xác Nhận Tra Cứu Size -->
            <div class="mb-3">
              <button type="button" id="btnConfirmSize" class="btn btn-bee-primary w-100 py-2.5 fw-bold shadow d-flex align-items-center justify-content-center gap-2" style="font-size: 0.95rem; border-radius: 12px;">
                <i class="fa-solid fa-wand-magic-sparkles text-dark"></i>
                <span>XÁC NHẬN TÌM SIZE CHUẨN</span>
              </button>
            </div>
          </div>

          <!-- Kết quả gợi ý size -->
          <div class="mt-auto">
            <div class="bee-size-result-box mb-2.5" id="sizeResultContainer">
              <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                  <div class="d-flex align-items-center gap-1.5 text-warning fw-bold fs-11 text-uppercase" style="letter-spacing: 0.05em;">
                    <i class="fa-solid fa-circle-check"></i> SIZE GỢI Ý DÀNH CHO BẠN:
                  </div>
                  <h6 class="fw-bold text-white mt-1 mb-1" id="suggestedSizeDesc">Size L (Vừa vặn chuẩn đẹp, tôn dáng quý ông)</h6>
                  <small class="text-white-50 fs-11 d-block" id="suggestedSizeFitDetails">Khuyên dùng: Chiều cao 1m65 - 1m75 • Cân nặng 63kg - 72kg</small>
                </div>
                <div class="bee-size-badge-pulse" id="suggestedSizeBadge">
                  L
                </div>
              </div>
            </div>

            <a href="{{ route('client.products.index', ['size' => 'L']) }}" id="viewSizeProductsBtn" class="btn btn-bee-accent w-100 py-2.5 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
              <i class="fa-solid fa-shirt"></i>
              <span>XEM TẤT CẢ SẢN PHẨM SIZE <strong id="btnSizeLabel">L</strong></span>
              <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- CỘT PHẢI: 4 CÔNG NGHỆ CHẤT LIỆU VÀNG (FABRIC TECH) -->
      <div class="col-lg-7">
        <div class="row g-3 h-100">
          <!-- 1. Sợi Tre Bamboo -->
          <div class="col-md-6">
            <div class="bee-fabric-card bamboo">
              <div class="d-flex justify-content-between align-items-start">
                <div class="bee-fabric-icon-wrapper bamboo">
                  <i class="fa-solid fa-leaf"></i>
                </div>
                <span class="badge bg-success-subtle text-success fw-bold px-2 py-1 fs-11 rounded-pill">
                  <i class="fa-solid fa-snowflake me-1"></i> AIR-COOL
                </span>
              </div>
              <h5 class="fw-bold text-dark mb-1 fs-6">Sợi Tre Bamboo Tự Nhiên</h5>
              <div class="text-success small fw-semibold mb-2">
                <i class="fa-solid fa-circle-check me-1"></i> Kháng khuẩn 99% • Chống tia UV
              </div>
              <p class="text-muted small mb-0 leading-relaxed">
                Chiết xuất từ thân tre già tự nhiên, bề mặt sợi siêu mịn mát lạnh tức thì, thấm hút mồ hôi gấp 3 lần cotton thông thường và khử mùi vượt trội.
              </p>
            </div>
          </div>

          <!-- 2. Cotton Pima 100% -->
          <div class="col-md-6">
            <div class="bee-fabric-card cotton">
              <div class="d-flex justify-content-between align-items-start">
                <div class="bee-fabric-icon-wrapper cotton">
                  <i class="fa-solid fa-certificate"></i>
                </div>
                <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1 fs-11 rounded-pill">
                  <i class="fa-solid fa-crown me-1 text-warning"></i> 100% PIMA
                </span>
              </div>
              <h5 class="fw-bold text-dark mb-1 fs-6">Cotton Pima Dệt Tổ Ong</h5>
              <div class="text-warning small fw-semibold mb-2">
                <i class="fa-solid fa-circle-check me-1 text-warning"></i> Bền màu 500 lần giặt • Không xù lông
              </div>
              <p class="text-muted small mb-0 leading-relaxed">
                Sợi bông dài quý hiếm bậc nhất dệt theo cấu trúc tổ ong thoáng khí đa chiều, tạo độ đàn hồi tự nhiên 4 chiều mà không làm mất phom áo ban đầu.
              </p>
            </div>
          </div>

          <!-- 3. Đũi Linen Tự Nhiên -->
          <div class="col-md-6">
            <div class="bee-fabric-card linen">
              <div class="d-flex justify-content-between align-items-start">
                <div class="bee-fabric-icon-wrapper linen">
                  <i class="fa-solid fa-wind"></i>
                </div>
                <span class="badge bg-info-subtle text-info fw-bold px-2 py-1 fs-11 rounded-pill">
                  <i class="fa-solid fa-sun me-1"></i> SIÊU NHẸ XỐP
                </span>
              </div>
              <h5 class="fw-bold text-dark mb-1 fs-6">Đũi Linen Tự Nhiên</h5>
              <div class="text-info small fw-semibold mb-2">
                <i class="fa-solid fa-circle-check me-1"></i> Thoát nhiệt tức thì • Phong trần
              </div>
              <p class="text-muted small mb-0 leading-relaxed">
                Dệt từ sợi lanh tự nhiên thô mộc xốp nhẹ, cấu trúc sợi rỗng giải nhiệt mùa nóng cực nhanh, mang lại phong thái phóng khoáng, lãng tử và thanh lịch.
              </p>
            </div>
          </div>

          <!-- 4. Vải Tuyết Mưa Ép Nhiệt -->
          <div class="col-md-6">
            <div class="bee-fabric-card tuyetmua">
              <div class="d-flex justify-content-between align-items-start">
                <div class="bee-fabric-icon-wrapper tuyetmua">
                  <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1 fs-11 rounded-pill">
                  <i class="fa-solid fa-user-tie me-1"></i> FORM HÀN QUỐC
                </span>
              </div>
              <h5 class="fw-bold text-dark mb-1 fs-6">Vải Tuyết Mưa Kháng Nhăn</h5>
              <div class="text-primary small fw-semibold mb-2">
                <i class="fa-solid fa-circle-check me-1"></i> Kháng nhăn 24h • Chuẩn may đo
              </div>
              <p class="text-muted small mb-0 leading-relaxed">
                Ứng dụng công nghệ ép nhiệt chống nhăn cao cấp, mặt vải đanh mịn giữ đứng form cổ áo và vai áo suốt 24 giờ năng động mà không cần là ủi cầu kỳ.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL: BẢNG THÔNG SỐ SIZE NAM CHUẨN CHI TIẾT -->
  <div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-labelledby="sizeGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="modal-header bg-dark text-white border-0 py-3 px-4">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-warning text-dark p-1.5 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
              <i class="fa-solid fa-ruler-combined"></i>
            </div>
            <div>
              <h5 class="modal-title fw-bold text-white mb-0" id="sizeGuideModalLabel">Bảng Quy Đổi Thông Số Size Áo Nam Chuẩn</h5>
              <small class="text-warning">Chuẩn may đo phom người Việt Nam (Sai số ±1cm)</small>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="table-responsive mb-4">
            <table class="table table-bordered text-center align-middle small mb-0">
              <thead class="table-light">
                <tr class="fw-bold text-dark text-uppercase fs-11">
                  <th>Size Áo</th>
                  <th>Chiều Cao</th>
                  <th>Cân Nặng</th>
                  <th>Rộng Ngực</th>
                  <th>Rộng Vai</th>
                  <th>Dài Áo</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong class="badge bg-dark text-warning fs-6 px-2.5">S</strong></td>
                  <td>1m55 - 1m65</td>
                  <td>48kg - 56kg</td>
                  <td>92 cm</td>
                  <td>41 cm</td>
                  <td>66 cm</td>
                </tr>
                <tr class="table-warning bg-warning-subtle">
                  <td><strong class="badge bg-warning text-dark fs-6 px-2.5">M</strong></td>
                  <td>1m60 - 1m70</td>
                  <td>55kg - 64kg</td>
                  <td>96 cm</td>
                  <td>43 cm</td>
                  <td>68 cm</td>
                </tr>
                <tr class="table-light">
                  <td><strong class="badge bg-dark text-warning fs-6 px-2.5">L</strong></td>
                  <td>1m65 - 1m75</td>
                  <td>63kg - 72kg</td>
                  <td>100 cm</td>
                  <td>45 cm</td>
                  <td>70 cm</td>
                </tr>
                <tr>
                  <td><strong class="badge bg-dark text-warning fs-6 px-2.5">XL</strong></td>
                  <td>1m70 - 1m82</td>
                  <td>70kg - 80kg</td>
                  <td>104 cm</td>
                  <td>47 cm</td>
                  <td>72 cm</td>
                </tr>
                <tr>
                  <td><strong class="badge bg-dark text-warning fs-6 px-2.5">XXL</strong></td>
                  <td>1m75 - 1m88</td>
                  <td>78kg - 88kg</td>
                  <td>108 cm</td>
                  <td>49 cm</td>
                  <td>74 cm</td>
                </tr>
                <tr>
                  <td><strong class="badge bg-dark text-warning fs-6 px-2.5">3XL</strong></td>
                  <td>1m78 - 1m92</td>
                  <td>85kg - 95kg</td>
                  <td>112 cm</td>
                  <td>51 cm</td>
                  <td>76 cm</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="p-3 bg-light rounded-3 border">
            <div class="d-flex align-items-center gap-2 mb-1">
              <i class="fa-solid fa-circle-info text-warning fs-5"></i>
              <strong class="text-dark small">Mẹo chọn size từ chuyên gia BeeStyle:</strong>
            </div>
            <ul class="text-muted small mb-0 ps-3">
              <li>Nếu số đo của bạn nằm ở giữa 2 size: hãy chọn <strong>size lớn hơn</strong> nếu thích mặc thoải mái hoặc có bụng, chọn <strong>size nhỏ hơn</strong> nếu thích ôm dáng gọn gàng.</li>
              <li>Hỗ trợ <strong>đổi size tận nhà hoàn toàn miễn phí</strong> trong 30 ngày nếu không vừa vặn.</li>
            </ul>
          </div>
        </div>
        <div class="modal-footer bg-light border-0 py-3 px-4 justify-content-between">
          <span class="text-muted small"><i class="fa-solid fa-phone text-warning me-1"></i> Hotline tư vấn size: <strong>1900 8888</strong></span>
          <button type="button" class="btn btn-bee-primary px-4 fw-bold" data-bs-dismiss="modal">Đã Hiểu</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 9. LỜI CẢM ƠN TRI ÂN KHÁCH HÀNG -->
  <div class="bee-vip-card mb-5 p-4 p-md-5" style="border-radius: 20px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(245, 158, 11, 0.35); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);">
    <div class="row align-items-center g-4">
      <div class="col-lg-8">
        <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 text-uppercase mb-3 shadow-xs" style="font-size: 0.82rem; letter-spacing: 0.5px;">
          <i class="fa-solid fa-heart me-1.5 text-danger"></i> THƯ TRI ÂN TỪ BEESTYLE MENSWEAR
        </span>
        <h2 class="fw-bold text-white mb-2.5" style="font-family: var(--atino-font-heading); font-size: 1.6rem; line-height: 1.35;">
          CẢM ƠN QUÝ KHÁCH ĐÃ TIN TƯỞNG &amp; ĐỒNG HÀNH CÙNG BEESTYLE
        </h2>
        <p class="text-white-50 small mb-0 leading-relaxed" style="font-size: 0.95rem; line-height: 1.65;">
          BeeStyle xin gửi lời cảm ơn chân thành và sâu sắc nhất đến Quý khách hàng đã luôn tin tưởng, lựa chọn và mua sắm tại cửa hàng. Mỗi đơn hàng của Quý khách là niềm vinh hạnh và nguồn động lực to lớn để chúng tôi không ngừng hoàn thiện chất lượng sản phẩm chuẩn may đo và dịch vụ tận tâm nhất. Kính chúc Quý khách luôn lịch lãm, tự tin và có những trải nghiệm thật tuyệt vời!
        </p>
      </div>
      <div class="col-lg-4 text-lg-end">
        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
          <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary px-4 py-3 fw-bold shadow-sm">
            <i class="fa-solid fa-bag-shopping me-1.5"></i> KHÁM PHÁ BỘ SƯU TẬP
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

    // 2. Đồng hồ đếm ngược Flash Sale thời gian thực
    const countdownWrapper = document.getElementById("dealCountdownWrapper");
    const hEl = document.getElementById("cdHours");
    const mEl = document.getElementById("cdMinutes");
    const sEl = document.getElementById("cdSeconds");

    if (countdownWrapper && hEl && mEl && sEl) {
      const targetStr = countdownWrapper.getAttribute("data-target");
      let targetTime = targetStr ? new Date(targetStr).getTime() : (Date.now() + 8 * 3600 * 1000 + 45 * 60 * 1000);

      function updateCountdown() {
        const now = Date.now();
        const diff = targetTime - now;

        if (diff <= 0) {
          // Khi hết thời gian ưu đãi -> Tự động ẩn khối Flash Sale ngay lập tức
          const flashSaleSection = document.getElementById("flash-sale");
          if (flashSaleSection) {
            flashSaleSection.style.transition = "opacity 0.6s ease, transform 0.6s ease";
            flashSaleSection.style.opacity = "0";
            flashSaleSection.style.transform = "translateY(-10px)";
            setTimeout(() => {
              flashSaleSection.remove();
            }, 600);
          }
          return;
        }

        const totalSecs = Math.floor(diff / 1000);
        const hours = Math.floor(totalSecs / 3600);
        const minutes = Math.floor((totalSecs % 3600) / 60);
        const seconds = totalSecs % 60;

        hEl.textContent = String(hours).padStart(2, '0');
        mEl.textContent = String(minutes).padStart(2, '0');
        sEl.textContent = String(seconds).padStart(2, '0');
      }

      updateCountdown();
      const timerInterval = setInterval(function () {
        updateCountdown();
        if (targetTime - Date.now() <= 0) {
          clearInterval(timerInterval);
        }
      }, 1000);
    }

    // 3. Smart Size Finder logic (Tra cứu size thông minh với ô nhập số và nút Xác Nhận)
    const inputHeight = document.getElementById("inputHeight");
    const inputWeight = document.getElementById("inputWeight");
    const btnConfirmSize = document.getElementById("btnConfirmSize");
    const suggestedBadge = document.getElementById("suggestedSizeBadge");
    const suggestedDesc = document.getElementById("suggestedSizeDesc");
    const suggestedDetails = document.getElementById("suggestedSizeFitDetails");
    const btnSizeLabel = document.getElementById("btnSizeLabel");
    const viewSizeBtn = document.getElementById("viewSizeProductsBtn");
    const fitRadios = document.querySelectorAll('input[name="fitStyle"]');
    const resultBox = document.getElementById("sizeResultContainer");

    function executeSizeCalculation(triggerAnimation = true) {
      let height = parseInt(inputHeight ? inputHeight.value : 170);
      let weight = parseInt(inputWeight ? inputWeight.value : 65);

      if (isNaN(height) || height <= 0) height = 170;
      if (isNaN(weight) || weight <= 0) weight = 65;

      let fit = "regular";
      fitRadios.forEach(r => { if (r.checked) fit = r.value; });

      // Xác định size cơ bản theo Chiều cao & Cân nặng
      let size = "M";
      let desc = "";
      let details = "";

      if (weight < 56 || (height <= 165 && weight <= 55)) {
        size = "S";
      } else if (weight <= 64 || (height <= 170 && weight <= 63)) {
        size = "M";
      } else if (weight <= 72 || (height <= 175 && weight <= 71)) {
        size = "L";
      } else if (weight <= 80 || (height <= 182 && weight <= 79)) {
        size = "XL";
      } else if (weight <= 88 || (height <= 188 && weight <= 87)) {
        size = "XXL";
      } else {
        size = "3XL";
      }

      // Điều chỉnh theo form mặc mong muốn
      const sizeOrder = ["S", "M", "L", "XL", "XXL", "3XL"];
      let idx = sizeOrder.indexOf(size);
      if (fit === "loose" && idx < sizeOrder.length - 1) {
        idx += 1;
      } else if (fit === "slim" && idx > 0) {
        idx -= 1;
      }
      size = sizeOrder[idx];

      // Mô tả chi tiết
      switch(size) {
        case "S":
          desc = "Size S (Chuẩn dáng thon gọn, vừa vặn tự nhiên)";
          details = "Phù hợp chiều cao 1m55 - 1m65 • cân nặng 48kg - 56kg";
          break;
        case "M":
          desc = "Size M (Phom tiêu chuẩn trẻ trung, tôn vóc dáng)";
          details = "Phù hợp chiều cao 1m60 - 1m70 • cân nặng 55kg - 64kg";
          break;
        case "L":
          desc = "Size L (Vừa vặn chuẩn đẹp, tôn dáng quý ông)";
          details = "Phù hợp chiều cao 1m65 - 1m75 • cân nặng 63kg - 72kg";
          break;
        case "XL":
          desc = "Size XL (Rộng rãi thoải mái, che khuyết điểm tốt)";
          details = "Phù hợp chiều cao 1m70 - 1m82 • cân nặng 70kg - 80kg";
          break;
        case "XXL":
          desc = "Size XXL (Thoáng mát tối đa, cử động dễ dàng)";
          details = "Phù hợp chiều cao 1m75 - 1m88 • cân nặng 78kg - 88kg";
          break;
        case "3XL":
          desc = "Size 3XL (Form rộng lớn, thoải mái cho người đậm đà)";
          details = "Phù hợp chiều cao 1m78 - 1m92 • cân nặng 85kg - 95kg";
          break;
      }

      if (suggestedBadge) {
        suggestedBadge.textContent = size;
        if (triggerAnimation) {
          suggestedBadge.style.transform = "scale(1.25)";
          setTimeout(() => { suggestedBadge.style.transform = "scale(1)"; }, 200);
        }
      }
      if (suggestedDesc) suggestedDesc.textContent = desc;
      if (suggestedDetails) suggestedDetails.textContent = details;
      if (btnSizeLabel) btnSizeLabel.textContent = size;
      if (viewSizeBtn) {
        viewSizeBtn.href = "{{ route('client.products.index') }}?size=" + encodeURIComponent(size);
      }

      if (triggerAnimation && resultBox) {
        resultBox.style.boxShadow = "0 0 0 3px rgba(245, 158, 11, 0.4)";
        setTimeout(() => { resultBox.style.boxShadow = "none"; }, 500);
      }
    }

    if (btnConfirmSize) {
      btnConfirmSize.addEventListener("click", function () {
        executeSizeCalculation(true);
      });
    }

    if (inputHeight) {
      inputHeight.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
          e.preventDefault();
          executeSizeCalculation(true);
        }
      });
    }

    if (inputWeight) {
      inputWeight.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
          e.preventDefault();
          executeSizeCalculation(true);
        }
      });
    }

    fitRadios.forEach(r => r.addEventListener("change", function () {
      executeSizeCalculation(false);
    }));

    // Khởi tạo mặc định
    executeSizeCalculation(false);
  });
</script>
@endpush
@endsection