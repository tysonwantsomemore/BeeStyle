@extends('layouts.client')

@section('title', $product->name . ' | BeeStyle Menswear')

@push('styles')
<style>
  /* ========================================================
     TIER-1 E-COMMERCE PRODUCT DETAIL STYLES - BEESTYLE
     ======================================================== */
  :root {
    --bee-primary: #f59e0b;
    --bee-primary-hover: #d97706;
    --bee-dark: #0f172a;
    --bee-danger: #e11d48;
    --bee-border: #e2e8f0;
    --bee-bg-light: #f8fafc;
  }

  /* Sticky Gallery on Desktop */
  .bee-gallery-sticky {
    position: sticky;
    top: 90px;
    z-index: 10;
  }

  /* Main Gallery Image Box */
  .bee-main-gallery-box {
    position: relative;
    background: #f8fafc;
    border-radius: 16px;
    border: 1px solid var(--bee-border);
    min-height: 440px;
    height: 440px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    cursor: crosshair;
    user-select: none;
  }

  .bee-main-gallery-box img#mainProductImg {
    max-height: 410px;
    width: 100%;
    object-fit: contain;
    transition: transform 0.12s cubic-bezier(0.2, 0, 0.2, 1);
    pointer-events: none;
  }

  /* Gallery Navigation Arrows */
  .bee-gallery-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid var(--bee-border);
    color: var(--bee-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 4;
    transition: all 0.2s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }
  .bee-gallery-arrow:hover {
    background: var(--bee-dark);
    color: #ffffff;
    border-color: var(--bee-dark);
  }
  .bee-gallery-arrow.prev { left: 12px; }
  .bee-gallery-arrow.next { right: 12px; }

  /* Gallery Thumbnails Filmstrip */
  .bee-thumb-strip {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 14px;
  }

  .bee-thumb-item {
    width: 72px;
    height: 72px;
    border-radius: 10px;
    border: 2px solid var(--bee-border);
    background: #ffffff;
    padding: 3px;
    cursor: pointer;
    transition: all 0.2s ease;
    overflow: hidden;
    position: relative;
  }

  .bee-thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
    transition: transform 0.2s;
  }

  .bee-thumb-item:hover {
    border-color: #cbd5e1;
    transform: translateY(-2px);
  }

  .bee-thumb-item.active {
    border-color: #f59e0b !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25);
    transform: translateY(-2px);
  }

  /* Flash Sale / Deal Banner */
  .bee-deal-banner {
    background: linear-gradient(135deg, #e11d48 0%, #be123c 50%, #9f1239 100%);
    color: #ffffff;
    border-radius: 12px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
    box-shadow: 0 4px 16px rgba(225, 29, 72, 0.25);
  }

  .bee-deal-timer-unit {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    background: rgba(0, 0, 0, 0.35);
    padding: 3px 8px;
    border-radius: 6px;
    min-width: 32px;
    font-weight: 800;
    font-size: 0.95rem;
    line-height: 1.1;
  }

  /* Price Area */
  .bee-price-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px 20px;
    border: 1px solid var(--bee-border);
    margin-bottom: 18px;
  }

  /* Voucher Mini Tickets */
  .bee-voucher-ticket {
    background: #ffffff;
    border: 1px dashed #f59e0b;
    border-radius: 8px;
    padding: 6px 12px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    cursor: pointer;
  }
  .bee-voucher-ticket:hover {
    background: #fffbeb;
    border-color: #d97706;
    transform: translateY(-1px);
  }

  /* Swatches: Color */
  .bee-color-btn {
    border: 2px solid #e2e8f0;
    background: #ffffff;
    border-radius: 50px;
    padding: 6px 14px;
    font-size: 0.84rem;
    font-weight: 600;
    color: #1e293b;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.15s ease;
  }
  .bee-color-btn:hover {
    border-color: #94a3b8;
    background: #f8fafc;
  }
  .btn-check:checked + .bee-color-btn {
    border-color: #0f172a !important;
    background: #0f172a !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25);
  }

  /* Swatches: Size */
  .bee-size-btn {
    border: 2px solid #e2e8f0;
    background: #ffffff;
    border-radius: 10px;
    padding: 8px 12px;
    min-width: 72px;
    text-align: center;
    cursor: pointer;
    transition: all 0.15s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .bee-size-btn:hover {
    border-color: #94a3b8;
    background: #f8fafc;
  }
  .btn-check:checked + .bee-size-btn {
    border-color: #0f172a !important;
    background: #0f172a !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25);
  }
  .btn-check:checked + .bee-size-btn .text-muted {
    color: #cbd5e1 !important;
  }
  .btn-check:checked + .bee-size-btn .size-stock-tag {
    color: #fde047 !important;
  }

  .bee-size-btn.out-of-stock {
    opacity: 0.45;
    background: #f1f5f9;
    border-style: dashed;
    text-decoration: line-through;
    cursor: not-allowed;
  }

  /* Trust Badges Card */
  .bee-service-pill {
    padding: 12px 14px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid var(--bee-border);
    transition: all 0.2s ease;
    height: 100%;
  }
  .bee-service-pill:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }

  /* Tabs Styling */
  .bee-product-tabs .nav-link {
    font-size: 0.95rem;
    font-weight: 700;
    color: #64748b;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 14px 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.2s;
  }
  .bee-product-tabs .nav-link:hover {
    color: #0f172a;
  }
  .bee-product-tabs .nav-link.active {
    color: #e11d48;
    border-bottom-color: #e11d48;
    background: transparent;
  }

  /* Floating Sticky Purchase Bar */
  .bee-sticky-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-top: 1px solid var(--bee-border);
    box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.12);
    z-index: 1040;
    padding: 12px 0;
    transform: translateY(120%);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
  }

  /* Star Breakdown Bars */
  .bee-star-bar {
    height: 8px;
    border-radius: 10px;
    background: #e2e8f0;
    overflow: hidden;
    flex-grow: 1;
  }
  .bee-star-fill {
    height: 100%;
    background: #f59e0b;
    border-radius: 10px;
  }

  /* Smart AI Size Calculator Box */
  .bee-smart-size-box {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 1px solid #bbf7d0;
    border-radius: 14px;
    padding: 18px 20px;
  }

  /* Animation pulse */
  @keyframes flameGlow {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.75; transform: scale(1.15); }
  }
  .animate-pulse {
    animation: flameGlow 1.5s infinite;
  }
</style>
@endpush

@section('content')
<div class="container py-3 py-lg-4">
  <!-- 1. BREADCRUMBS -->
  <nav aria-label="breadcrumb" class="mb-3 mb-lg-4">
    <ol class="breadcrumb small mb-0">
      <li class="breadcrumb-item">
        <a href="{{ route('client.home') }}" class="text-decoration-none text-muted">
          <i class="fa-solid fa-house me-1"></i> Trang chủ
        </a>
      </li>
      @if($product->category)
        <li class="breadcrumb-item">
          <a href="{{ route('client.products.index', ['category' => $product->category->slug]) }}" class="text-decoration-none text-muted">
            {{ $product->category->name }}
          </a>
        </li>
      @endif
      @if($product->brand)
        <li class="breadcrumb-item">
          <a href="{{ route('client.products.index', ['brand' => $product->brand->slug]) }}" class="text-decoration-none text-muted">
            {{ $product->brand->name }}
          </a>
        </li>
      @endif
      <li class="breadcrumb-item active text-dark fw-bold text-truncate" aria-current="page" style="max-width: 320px;">
        {{ $product->name }}
      </li>
    </ol>
  </nav>

  <!-- 2. MAIN PRODUCT DETAIL CARD -->
  <div class="card border-0 shadow-sm p-3 p-md-4 p-lg-5 mb-5" style="border-radius: 20px; background: #ffffff; border: 1px solid var(--bee-border) !important;">
    <div class="row g-4 g-lg-5">
      
      <!-- ==========================================
           LEFT COLUMN: HIGH-END GALLERY
           ========================================== -->
      @php
        $allGalleryImages = collect([$product->image]);
        if ($product->images && $product->images->count() > 0) {
          foreach ($product->images as $pImg) {
            if ($pImg->image_path && $pImg->image_path !== $product->image) {
              $allGalleryImages->push($pImg->image_path);
            }
          }
        }
        $allGalleryImages = $allGalleryImages->unique()->values();

        // Tính % giảm giá nếu có
        $hasDiscount = ($product->original_price && $product->original_price > $product->price);
        $discountPercent = $hasDiscount ? round((($product->original_price - $product->price) / $product->original_price) * 100) : 0;
        
        // Kiểm tra Running Deal / Flash Sale
        $isDealActive = (bool)$runningDeal;
        $effectivePrice = $product->price;
        if ($isDealActive && isset($runningDeal->deal_price) && $runningDeal->deal_price < $product->price) {
            $effectivePrice = $runningDeal->deal_price;
            $discountPercent = $runningDeal->discount_percent ?: $discountPercent;
        }
      @endphp
      <div class="col-lg-6 mb-3 mb-lg-0">
        <div class="bee-gallery-sticky">
          <!-- MAIN IMAGE DISPLAY (WITH HOVER ZOOM & NAVIGATION ARROWS) -->
          <div class="bee-main-gallery-box" id="mainImgZoomContainer">
            <!-- Discount Badge -->
            @if($isDealActive)
              <span class="position-absolute top-0 start-0 m-3 badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm" style="z-index: 3;">
                <i class="fa-solid fa-bolt me-1"></i> DEAL -{{ $discountPercent }}%
              </span>
            @elseif($hasDiscount)
              <span class="position-absolute top-0 start-0 m-3 badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm" style="z-index: 3;">
                -{{ $discountPercent }}%
              </span>
            @endif

            <!-- Best Seller / Hot Badge -->
            @if($product->is_best_seller)
              <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark fw-bold px-2.5 py-1.5 rounded-pill shadow-xs" style="z-index: 3;">
                <i class="fa-solid fa-fire text-danger me-1"></i> BÁN CHẠY
              </span>
            @endif

            <!-- Main Product Image -->
            <img id="mainProductImg" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid">

            <!-- Next & Previous Arrows -->
            @if($allGalleryImages->count() > 1)
              <button type="button" class="bee-gallery-arrow prev" onclick="navigateGallery(-1)" title="Ảnh trước">
                <i class="fa-solid fa-chevron-left"></i>
              </button>
              <button type="button" class="bee-gallery-arrow next" onclick="navigateGallery(1)" title="Ảnh tiếp theo">
                <i class="fa-solid fa-chevron-right"></i>
              </button>
            @endif

            <!-- Lightbox expand button & Hover Zoom Indicator -->
            <div class="position-absolute bottom-0 start-0 end-0 p-3 d-flex justify-content-between align-items-center" style="pointer-events: none; z-index: 3;">
              <span class="badge bg-dark bg-opacity-75 text-white px-2.5 py-1.5 rounded-pill shadow-xs" style="font-size: 0.72rem;">
                <i class="fa-solid fa-magnifying-glass-plus text-warning me-1"></i> Rê chuột để phóng to
              </span>
              <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm" style="pointer-events: auto; width: 34px; height: 34px;" onclick="openMainGalleryLightbox()" title="Xem ảnh toàn màn hình">
                <i class="fa-solid fa-expand text-dark"></i>
              </button>
            </div>
          </div>

          <!-- THUMBNAILS FILMSTRIP -->
          @if($allGalleryImages->count() > 1)
            <div class="bee-thumb-strip" id="galleryThumbStrip">
              @foreach($allGalleryImages as $idx => $gImg)
                <div class="bee-thumb-item {{ $idx === 0 ? 'active' : '' }}" onclick="changeMainImg('{{ asset($gImg) }}', this, {{ $idx }})">
                  <img src="{{ asset($gImg) }}" alt="Thumbnail {{ $idx + 1 }}">
                </div>
              @endforeach
            </div>
          @endif

          <!-- TRUST ASSURANCE ROW UNDER GALLERY -->
          <div class="row g-2 mt-3 text-center">
            <div class="col-6 col-md-3">
              <div class="p-2 rounded-3 bg-light border text-muted d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.72rem;">
                <i class="fa-solid fa-camera text-warning"></i> 100% Ảnh Thật
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded-3 bg-light border text-muted d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.72rem;">
                <i class="fa-solid fa-rotate-left text-success"></i> 30 Ngày Đổi Trả
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded-3 bg-light border text-muted d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.72rem;">
                <i class="fa-solid fa-shield-check text-primary"></i> Bảo Hành 1 Năm
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="p-2 rounded-3 bg-light border text-muted d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.72rem;">
                <i class="fa-solid fa-box-open text-danger"></i> Kiểm Tra Khi Nhận
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ==========================================
           RIGHT COLUMN: PRODUCT DETAILS & BUY BOX
           ========================================== -->
      <div class="col-lg-6">
        <div class="ps-lg-2">
          
          <!-- BRAND & SKU & LIVE VIEWERS -->
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-dark text-warning fw-bold px-2.5 py-1">
                {{ $product->brand->name ?? 'BeeStyle Menswear' }}
              </span>
              <span class="badge bg-light text-muted border px-2 py-1">
                {{ $product->category->name ?? 'Thời trang nam' }}
              </span>
            </div>

            <!-- LIVE VIEWERS SOCIAL PROOF -->
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.76rem;">
              <i class="fa-solid fa-fire animate-pulse me-1"></i> <span id="liveViewerCount">19</span> người đang xem
            </span>
          </div>

          <!-- PRODUCT TITLE -->
          <h1 class="fw-bold text-dark mb-2" style="font-size: 1.65rem; line-height: 1.35; font-family: var(--atino-font-heading, inherit);">
            {{ $product->name }}
          </h1>

          <!-- RATING, REVIEWS COUNT & SOLD COUNT -->
          <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            <div class="text-warning small d-flex align-items-center gap-0.5">
              @for($i=1; $i<=5; $i++)
                <i class="fa-solid fa-star {{ $i <= round($product->rating) ? 'text-warning' : 'text-secondary-subtle' }}"></i>
              @endfor
            </div>
            <span class="small fw-bold text-dark">{{ number_format($product->rating, 1) }}</span>
            <span class="text-muted small">•</span>
            <a href="#reviews" class="text-muted small text-decoration-underline" onclick="var t=new bootstrap.Tab(document.getElementById('reviews-tab')); t.show();">
              {{ $product->reviews_count }} đánh giá
            </a>
            <span class="text-muted small">•</span>
            <span class="small text-muted">Đã bán <strong class="text-dark">{{ number_format($product->sold_count ?? 128) }}</strong></span>
            <span class="text-muted small">•</span>
            <span class="text-muted small">Mã SP: <strong id="displaySku" class="text-dark font-monospace">{{ $product->sku }}</strong></span>
          </div>

          <!-- FLASH SALE / RUNNING DEAL BANNER (IF ACTIVE) -->
          @if($isDealActive)
            <div class="bee-deal-banner">
              <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-bolt fs-5 text-warning"></i>
                <div>
                  <strong class="d-block" style="font-size: 0.95rem; line-height: 1.2;">FLASH SALE ĐANG DIỄN RA</strong>
                  <small class="opacity-90" style="font-size: 0.75rem;">{{ $runningDeal->title ?? 'Ưu đãi có hạn hôm nay' }}</small>
                </div>
              </div>
              <div class="d-flex align-items-center gap-1.5">
                <small class="me-1 fw-semibold" style="font-size: 0.76rem;">Kết thúc sau:</small>
                <div class="bee-deal-timer-unit" id="dealHours">05</div>:
                <div class="bee-deal-timer-unit" id="dealMins">24</div>:
                <div class="bee-deal-timer-unit" id="dealSecs">18</div>
              </div>
            </div>
          @endif

          <!-- PRICE BOX -->
          <div class="bee-price-box">
            <div class="d-flex align-items-baseline gap-3 flex-wrap">
              <h2 class="fw-bolder text-danger mb-0" id="displayPrice" style="font-size: 2rem; font-family: var(--atino-font-heading, inherit);">
                {{ number_format($effectivePrice, 0, ',', '.') }}₫
              </h2>
              @if($hasDiscount || $isDealActive)
                <span class="text-muted text-decoration-line-through fs-5" id="displayOriginalPrice">
                  {{ number_format($product->original_price ?: $product->price, 0, ',', '.') }}₫
                </span>
                <span class="badge bg-danger text-white fw-bold px-2.5 py-1 rounded-pill" id="displayDiscountBadge" style="font-size: 0.8rem;">
                  Tiết kiệm {{ number_format(($product->original_price ?: $product->price) - $effectivePrice, 0, ',', '.') }}₫ (-{{ $discountPercent }}%)
                </span>
              @endif
            </div>

            <!-- MEMBER POINTS PERK -->
            <div class="mt-2.5 pt-2 border-top border-secondary border-opacity-10 d-flex align-items-center gap-2 text-muted small" style="font-size: 0.8rem;">
              <i class="fa-solid fa-coins text-warning"></i>
              <span>Tích lũy <strong>{{ number_format(round($effectivePrice * 0.03)) }}</strong> BeePoint (3% giá trị đơn) khi hoàn tất mua hàng.</span>
            </div>
          </div>

          <!-- SHOP VOUCHERS / COUPONS (MÃ GIẢM GIÁ CỦA SHOP) -->
          @if(isset($availableCoupons) && $availableCoupons->count() > 0)
            <div class="mb-3.5 p-3 rounded-3 border bg-white">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small fw-bold text-dark mb-0 d-flex align-items-center gap-1.5">
                  <i class="fa-solid fa-ticket text-danger"></i>
                  <span>Mã Giảm Giá Của Shop:</span>
                </label>
                <small class="text-muted fs-11">Bấm vào mã để sao chép</small>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @foreach($availableCoupons as $coupon)
                  <div class="bee-voucher-ticket" onclick="copyCouponCode('{{ $coupon->code }}')" title="Bấm để sao chép mã {{ $coupon->code }}">
                    <i class="fa-solid fa-tag text-warning"></i>
                    <strong class="text-dark">{{ $coupon->code }}</strong>
                    <span class="text-muted">|</span>
                    <span class="text-danger fw-semibold">
                      @if($coupon->discount_amount)
                        -{{ number_format($coupon->discount_amount, 0, ',', '.') }}₫
                      @elseif($coupon->discount_percent)
                        -{{ $coupon->discount_percent }}%
                      @else
                        Ưu đãi
                      @endif
                    </span>
                    <i class="fa-regular fa-copy text-muted ms-1" style="font-size: 0.75rem;"></i>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <!-- SHORT DESCRIPTION -->
          @if($product->short_description)
            <div class="text-secondary small mb-3.5 leading-relaxed p-3 bg-light rounded-3 border">
              <i class="fa-solid fa-circle-info text-primary me-1.5"></i>
              {{ $product->short_description }}
            </div>
          @endif

          <!-- ==========================================
               PURCHASE FORM: COLOR, SIZE, QUANTITY
               ========================================== -->
          <form action="{{ route('client.cart.add') }}" method="POST" id="productForm" onsubmit="return handleProductFormSubmit(event);">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="variant_id" id="selectedVariantId" value="">

            <!-- COLOR SELECTION -->
            @php
              $prodColors = is_array($product->colors) ? $product->colors : ['Đen', 'Trắng', 'Xanh Navy'];
              function getShowColorHex($name) {
                $c = mb_strtolower(trim($name));
                if (str_contains($c, 'đen') || str_contains($c, 'black')) return '#111827';
                if (str_contains($c, 'trắng') || str_contains($c, 'white')) return '#ffffff';
                if (str_contains($c, 'navy') || str_contains($c, 'than')) return '#1e3a8a';
                if (str_contains($c, 'xám ghi') || str_contains($c, 'ghi') || str_contains($c, 'xám')) return '#64748b';
                if (str_contains($c, 'đỏ') || str_contains($c, 'burgundy')) return '#881337';
                if (str_contains($c, 'be') || str_contains($c, 'khaki')) return '#d4b996';
                if (str_contains($c, 'rêu') || str_contains($c, 'olive')) return '#365314';
                if (str_contains($c, 'nâu') || str_contains($c, 'coffee')) return '#78350f';
                if (str_contains($c, 'vàng')) return '#d97706';
                return '#334155';
              }
              function getShowSizeHint($sz) {
                $s = strtoupper(trim($sz));
                if ($s === 'S') return '50-58kg';
                if ($s === 'M') return '58-65kg';
                if ($s === 'L') return '65-72kg';
                if ($s === 'XL') return '72-80kg';
                if ($s === 'XXL' || $s === '2XL') return '80-88kg';
                if ($s === '3XL') return '> 88kg';
                return 'Chuẩn form';
              }
            @endphp
            @if(count($prodColors) > 0)
              <div class="mb-3.5 p-3 rounded-3 border" id="colorGroupSection" style="transition: all 0.3s ease; background: #ffffff;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="form-label small fw-bold text-dark mb-0">
                    <i class="fa-solid fa-palette text-warning me-1"></i> 1. Chọn Màu Sắc:
                    <span class="badge bg-light text-muted border px-2 py-0.5 ms-1 fw-bold" id="selectedColorText">Chưa chọn</span>
                  </label>
                  <span class="badge bg-danger-subtle text-danger fw-bold fs-11">* Bắt buộc chọn</span>
                </div>
                <div class="d-flex flex-wrap gap-2" id="colorOptionList">
                  @foreach($prodColors as $c)
                    @php
                      $cHex = getShowColorHex($c);
                      $isWhite = ($cHex === '#ffffff');
                    @endphp
                    <input type="radio" class="btn-check product-color-radio" name="color" id="color_{{ $loop->index }}" value="{{ $c }}" onchange="selectProductColor('{{ $c }}')">
                    <label class="bee-color-btn" for="color_{{ $loop->index }}">
                      <span class="rounded-circle d-inline-block border" style="width: 16px; height: 16px; background-color: {{ $cHex }}; border-color: {{ $isWhite ? '#cbd5e1' : 'transparent' }} !important;"></span>
                      <span>{{ $c }}</span>
                    </label>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- SIZE SELECTION -->
            @php
              $rawSizes = is_array($product->sizes) ? $product->sizes : (is_string($product->sizes) ? (json_decode($product->sizes, true) ?: array_map('trim', explode(',', $product->sizes))) : ['S', 'M', 'L', 'XL', 'XXL']);
              $prodSizes = !empty($rawSizes) ? array_filter(array_map('trim', (array)$rawSizes)) : ['S', 'M', 'L', 'XL', 'XXL'];
              if (empty($prodSizes)) {
                $prodSizes = ['S', 'M', 'L', 'XL', 'XXL'];
              }
            @endphp
            <div class="mb-3.5 p-3 rounded-3 border" id="sizeGroupSection" style="transition: all 0.3s ease; background: #ffffff;">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small fw-bold text-dark mb-0">
                  <i class="fa-solid fa-ruler-combined text-warning me-1"></i> 2. Chọn Kích Thước (Size Nam):
                  <span class="badge bg-light text-muted border px-2 py-0.5 ms-1 fw-bold" id="selectedSizeText">Chưa chọn</span>
                </label>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-danger-subtle text-danger fw-bold fs-11">* Bắt buộc chọn</span>
                  <a href="#sizeGuideModal" data-bs-toggle="modal" class="text-decoration-none small text-danger fw-bold d-flex align-items-center gap-1" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-ruler-horizontal"></i> Bảng size &amp; AI tính size
                  </a>
                </div>
              </div>
              <div class="d-flex flex-wrap gap-2" id="sizeOptionList">
                @foreach($prodSizes as $sz)
                  <input type="radio" class="btn-check product-size-radio" name="size" id="size_{{ $loop->index }}" value="{{ $sz }}" onchange="selectProductSize('{{ $sz }}', '{{ getShowSizeHint($sz) }}')">
                  <label class="bee-size-btn" id="sizeLabel_{{ $loop->index }}" for="size_{{ $loop->index }}">
                    <span class="fw-bold" style="font-size: 0.95rem;">{{ $sz }}</span>
                    <span class="text-muted fw-normal" style="font-size: 0.68rem;">{{ getShowSizeHint($sz) }}</span>
                    <span class="size-stock-tag fw-semibold text-success" id="sizeStockTag_{{ $loop->index }}" style="font-size: 0.68rem; display: none;"></span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- QUANTITY STEPPER & REALTIME SUBTOTAL -->
            <div class="mb-4 p-3.5 rounded-3 border" style="background: #f8fafc;">
              <div class="d-flex justify-content-between align-items-center mb-2.5">
                <label class="form-label small fw-bold text-dark mb-0 d-flex align-items-center gap-1.5">
                  <i class="fa-solid fa-calculator text-warning"></i>
                  <span>3. Số Lượng Mua:</span>
                </label>
                <span class="badge bg-dark text-warning border border-warning px-2.5 py-1 fw-bold d-inline-flex align-items-center gap-1.5" style="font-size: 0.82rem;">
                  <i class="fa-solid fa-cart-shopping"></i> Đã chọn: <span id="showQtyLiveBadge" class="text-white fs-6 fw-bolder">1</span> sản phẩm
                </span>
              </div>

              <div class="d-flex align-items-center gap-3 flex-wrap">
                <!-- Stepper Box -->
                <div class="bee-qty-stepper">
                  <button type="button" class="btn-step-minus" id="btnMinusQty" onclick="stepProductQty(-1)" title="Giảm 1 sản phẩm">
                    <i class="fa-solid fa-minus"></i>
                  </button>
                  <input type="number" name="quantity" id="productQty" class="qty-display-input" 
                    value="1" min="1" max="10" readonly>
                  <button type="button" class="btn-step-plus" id="btnPlusQty" onclick="stepProductQty(1)" title="Tăng 1 sản phẩm">
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>

                <!-- Live Subtotal Preview & Stock -->
                <div>
                  <div class="d-flex align-items-baseline gap-1.5">
                    <span class="text-muted small">Tạm tính:</span>
                    <strong class="text-danger fs-5 fw-bold" id="productSubtotalLive">{{ number_format($effectivePrice, 0, ',', '.') }}₫</strong>
                  </div>
                  <div class="d-flex align-items-center gap-2 flex-wrap mt-1" id="stockStatusContainer">
                    <small class="text-muted fs-11">
                      <i class="fa-solid fa-boxes-stacked text-warning me-1"></i>
                      <span id="stockLabelText">Tổng kho:</span>
                      <strong class="text-dark fw-bold fs-6" id="displayStockCount">{{ $product->variants->count() > 0 ? (int)$product->variants->sum('stock') : (int)$product->stock }}</strong> cái có sẵn
                    </small>
                    <span class="badge bg-light text-muted border px-2 py-0.5" id="displayStockBadge" style="font-size: 0.75rem;">
                      <i class="fa-solid fa-layer-group me-1 text-warning"></i> Chọn Màu &amp; Size để xem tồn kho
                    </span>
                  </div>
                  <small class="text-muted fs-11 d-block mt-0.5">
                    • Giới hạn đặt mua: tối đa <strong class="text-dark" id="displayMaxQty">10</strong> cái / lần
                  </small>
                </div>
              </div>

              <div id="maxLimitMsg" class="alert alert-warning border-0 py-2 px-3 small rounded-3 mt-2.5 mb-0 d-none fw-semibold" style="font-size: 0.8rem;">
                <i class="fa-solid fa-circle-exclamation text-danger me-1"></i> Quý khách đã chọn số lượng tối đa cho phép trong một lần đặt hàng.
              </div>
            </div>

            <!-- CẢNH BÁO CHƯA CHỌN MÀU / SIZE -->
            <div class="alert alert-danger py-2.5 px-3 rounded-3 mb-3 d-none shadow-xs" id="productFormAlert" style="font-size: 0.85rem;">
              <i class="fa-solid fa-triangle-exclamation me-1.5 fs-6 align-middle"></i>
              <span id="productFormAlertText">Vui lòng chọn Màu sắc và Kích thước (Size) trước khi mua!</span>
            </div>

            <!-- CẢNH BÁO PHÂN LOẠI HẾT HÀNG -->
            <div class="alert alert-danger py-2.5 px-3 rounded-3 mb-3 d-none shadow-xs" id="variantOutOfStockAlert" style="font-size: 0.85rem;">
              <i class="fa-solid fa-circle-xmark me-1.5 fs-6 align-middle text-danger"></i>
              <span id="variantOutOfStockText">Phiên bản này hiện đã tạm hết hàng trong kho! Quý khách vui lòng chọn màu sắc hoặc kích cỡ khác.</span>
            </div>

            <!-- ACTION BUTTONS: ADD TO CART, BUY NOW, WISHLIST -->
            <div class="row g-2 mb-4 align-items-center">
              <div class="col-5">
                <button type="submit" class="btn btn-bee-outline w-100 py-2.5 fs-6 shadow-xs fw-bold" id="btnAddToCart">
                  <i class="fa-solid fa-cart-plus me-1.5"></i> Thêm Vào Giỏ
                </button>
              </div>
              <div class="col-5">
                <button type="submit" name="buy_now" value="1" class="btn btn-bee-primary w-100 py-2.5 fs-6 shadow-xs fw-bold" id="btnBuyNow">
                  <i class="fa-solid fa-bolt me-1.5"></i> Mua Ngay
                </button>
              </div>
              <div class="col-2">
                <button type="button" class="btn btn-outline-secondary w-100 py-2.5 fs-6 btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active text-danger border-danger' : '' }} shadow-xs" 
                  onclick="toggleWishlist({{ $product->id }}, this)" 
                  title="Thêm vào danh sách yêu thích" style="border-radius: 8px;">
                  <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }} fs-5"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- COMMITMENTS & POLICIES (4 GRID CARDS) -->
          <div class="border-top pt-3.5 mt-2">
            <div class="row g-2">
              <div class="col-6">
                <div class="bee-service-pill d-flex align-items-center gap-2.5">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-truck-fast text-warning fs-6"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold text-dark fs-12">Freeship toàn quốc</span>
                    <small class="text-muted fs-11">Đơn hàng từ 300.000₫</small>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="bee-service-pill d-flex align-items-center gap-2.5">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-rotate-left text-warning fs-6"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold text-dark fs-12">Đổi size tận nơi</span>
                    <small class="text-muted fs-11">Miễn phí trong 30 ngày</small>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="bee-service-pill d-flex align-items-center gap-2.5">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-shield-check text-warning fs-6"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold text-dark fs-12">Chính hãng 100%</span>
                    <small class="text-muted fs-11">Chuẩn form may đo</small>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="bee-service-pill d-flex align-items-center gap-2.5">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                    <i class="fa-solid fa-box-open text-warning fs-6"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold text-dark fs-12">Đồng kiểm hàng</span>
                    <small class="text-muted fs-11">Ưng ý mới thanh toán</small>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================
       3. TABS: DESCRIPTION, SPECS & SMART AI SIZE, REVIEWS
       ======================================================== -->
  <div class="card border-0 shadow-sm p-3 p-md-4 p-lg-5 mb-5" style="border-radius: 20px; background: #ffffff; border: 1px solid var(--bee-border) !important;">
    <ul class="nav nav-tabs bee-product-tabs border-bottom mb-4" id="productTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">
          <i class="fa-solid fa-file-lines me-2 text-danger"></i> Chi Tiết Sản Phẩm
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">
          <i class="fa-solid fa-sliders me-2 text-danger"></i> Thông Số &amp; Chọn Size
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
          <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Khách Hàng ({{ $product->reviews_count }})
        </button>
      </li>
    </ul>

    <div class="tab-content" id="productTabsContent">
      <!-- TAB 1: DESCRIPTION -->
      <div class="tab-pane fade show active" id="desc" role="tabpanel">
        <div class="product-description-content text-secondary leading-relaxed fs-6">
          {!! $product->description ?? nl2br(e($product->short_description)) !!}
        </div>

        <!-- HIGHLIGHT CARDS -->
        <div class="row g-3 mt-4 pt-3 border-top">
          <div class="col-md-4">
            <div class="p-3 bg-light rounded-3 border text-center h-100">
              <i class="fa-solid fa-feather text-warning fs-2 mb-2"></i>
              <h6 class="fw-bold text-dark mb-1">Chất Vải Mát Mịn</h6>
              <p class="small text-muted mb-0">Sợi dệt Compact tự nhiên, thoáng mát, không xù lông sau nhiều lần giặt.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 bg-light rounded-3 border text-center h-100">
              <i class="fa-solid fa-arrows-up-down-left-right text-primary fs-2 mb-2"></i>
              <h6 class="fw-bold text-dark mb-1">Co Giãn 4 Chiều</h6>
              <p class="small text-muted mb-0">Độ đàn hồi cao, thoải mái vận động trong mọi hoạt động thường ngày.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 bg-light rounded-3 border text-center h-100">
              <i class="fa-solid fa-shirt text-success fs-2 mb-2"></i>
              <h6 class="fw-bold text-dark mb-1">Phom Dáng Tôn Chuẩn</h6>
              <p class="small text-muted mb-0">Thiết kế chuẩn số đo nam giới Việt Nam, giấu khuyết điểm vòng 2 hiệu quả.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 2: SPECS & EMBEDDED SMART AI SIZE CALCULATOR -->
      <div class="tab-pane fade" id="specs" role="tabpanel">
        <div class="row g-4">
          <!-- Left: Specifications Table -->
          <div class="col-lg-6">
            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
              <i class="fa-solid fa-list-check text-danger"></i> Thông Số Kỹ Thuật:
            </h6>
            <div class="table-responsive">
              <table class="table table-bordered small align-middle mb-4">
                <tbody>
                  <tr>
                    <td class="fw-semibold text-dark bg-light" style="width: 170px;">Thương hiệu</td>
                    <td class="fw-bold text-primary">{{ $product->brand->name ?? 'BeeStyle Menswear' }}</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold text-dark bg-light">Mã sản phẩm (SKU)</td>
                    <td class="font-monospace fw-bold text-dark">{{ $product->sku }}</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold text-dark bg-light">Danh mục</td>
                    <td>{{ $product->category->name ?? 'Thời trang nam' }}</td>
                  </tr>
                  @if(!empty($product->specifications) && is_array($product->specifications))
                    @foreach($product->specifications as $sKey => $sVal)
                      <tr>
                        <td class="fw-semibold text-dark bg-light">{{ $sKey }}</td>
                        <td>{{ $sVal }}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td class="fw-semibold text-dark bg-light">Chất liệu chính</td>
                      <td>Cotton Compact Cá Sấu 100% cao cấp, co giãn 4 chiều đàn hồi</td>
                    </tr>
                    <tr>
                      <td class="fw-semibold text-dark bg-light">Kiểu dáng (Fit)</td>
                      <td>Regular Fit tôn dáng, năng động và thoải mái</td>
                    </tr>
                    <tr>
                      <td class="fw-semibold text-dark bg-light">Tính năng vượt trội</td>
                      <td>Thấm hút mồ hôi nhanh, kháng khuẩn khử mùi, giữ phom bền màu</td>
                    </tr>
                    <tr>
                      <td class="fw-semibold text-dark bg-light">Xuất xứ</td>
                      <td>Việt Nam (Tiêu chuẩn xuất khẩu chất lượng cao)</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>

            <!-- Care Instructions with Visual Icons -->
            <h6 class="fw-bold text-dark mb-2.5 d-flex align-items-center gap-2">
              <i class="fa-solid fa-hands-bubbles text-primary"></i> Hướng Dẫn Bảo Quản &amp; Giặt Ủi:
            </h6>
            <div class="row g-2 text-center small text-muted">
              <div class="col-3">
                <div class="p-2 border rounded-3 bg-light">
                  <i class="fa-solid fa-temperature-half text-dark fs-5 mb-1 d-block"></i>
                  <span class="fs-11">Giặt dưới 30°C</span>
                </div>
              </div>
              <div class="col-3">
                <div class="p-2 border rounded-3 bg-light">
                  <i class="fa-solid fa-ban text-danger fs-5 mb-1 d-block"></i>
                  <span class="fs-11">Không tẩy clo</span>
                </div>
              </div>
              <div class="col-3">
                <div class="p-2 border rounded-3 bg-light">
                  <i class="fa-solid fa-wind text-info fs-5 mb-1 d-block"></i>
                  <span class="fs-11">Phơi mát tự nhiên</span>
                </div>
              </div>
              <div class="col-3">
                <div class="p-2 border rounded-3 bg-light">
                  <i class="fa-solid fa-shirt text-warning fs-5 mb-1 d-block"></i>
                  <span class="fs-11">Ủi ở nhiệt độ vừa</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right: EMBEDDED SMART AI SIZE CALCULATOR -->
          <div class="col-lg-6">
            <div class="bee-smart-size-box">
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-success text-white px-2.5 py-1 fw-bold">
                  <i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI Smart Fit
                </span>
                <h6 class="fw-bold text-dark mb-0">Bộ Tính Size Nam Chuẩn Tự Động</h6>
              </div>
              <p class="small text-muted mb-3">
                Nhập chiều cao và cân nặng thực tế của bạn, trợ lý thông minh BeeStyle sẽ gợi ý chuẩn xác size áo vừa vặn nhất cho bạn!
              </p>

              <div class="row g-3 mb-3">
                <div class="col-6">
                  <label class="form-label small fw-bold text-dark mb-1">
                    <i class="fa-solid fa-arrows-up-down text-warning me-1"></i> Chiều cao (cm):
                  </label>
                  <input type="number" id="tabCalcHeight" class="form-control form-control-sm fw-bold text-center" value="172" min="150" max="200" oninput="calculateTabRecommendedSize()">
                </div>
                <div class="col-6">
                  <label class="form-label small fw-bold text-dark mb-1">
                    <i class="fa-solid fa-weight-scale text-warning me-1"></i> Cân nặng (kg):
                  </label>
                  <input type="number" id="tabCalcWeight" class="form-control form-control-sm fw-bold text-center" value="67" min="40" max="120" oninput="calculateTabRecommendedSize()">
                </div>
              </div>

              <!-- Calculation Result Box -->
              <div id="tabCalcSizeResult" class="p-3 bg-white rounded-3 border border-success mb-3 shadow-xs">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <span class="small text-muted">Kích cỡ đề xuất cho bạn:</span>
                  <span class="badge bg-success fs-6 fw-bold px-3 py-1" id="tabRecommendedSizeBadge">Size L</span>
                </div>
                <p class="small text-dark fw-semibold mb-0" id="tabRecommendedSizeDesc">
                  Vừa vặn thoải mái, tôn dáng chuẩn phom (65-72kg, 1m70-1m77)
                </p>
              </div>

              <button type="button" class="btn btn-success btn-sm w-100 py-2 fw-bold shadow-xs" onclick="applyTabRecommendedSize()">
                <i class="fa-solid fa-check-double me-1"></i> Áp Dụng Chọn Size Này Cho Tôi
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 3: REVIEWS & RATINGS -->
      <div class="tab-pane fade" id="reviews" role="tabpanel">
        <div class="row g-4">
          <!-- Left Column: Rating Overview & Review Form -->
          <div class="col-lg-4 col-md-5 border-end-md pe-lg-4">
            
            <!-- OVERVIEW BOX -->
            <div class="text-center p-3 bg-light rounded-3 mb-3 border">
              <h1 class="display-3 fw-bold text-warning mb-0">{{ number_format($product->rating, 1) }}</h1>
              <div class="text-warning mb-2 fs-5">
                @for($i=1; $i<=5; $i++)
                  <i class="fa-solid fa-star {{ $i <= round($product->rating) ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                @endfor
              </div>
              <p class="text-muted small mb-0">Dựa trên <strong>{{ $product->reviews_count }}</strong> lượt đánh giá từ khách mua</p>
            </div>

            <!-- STAR BREAKDOWN BARS -->
            @php
              $starCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
              $totalRev = $product->reviews->count();
              foreach ($product->reviews as $r) {
                $starCounts[$r->rating] = ($starCounts[$r->rating] ?? 0) + 1;
              }
            @endphp
            <div class="p-3 bg-light rounded-3 mb-4 border small">
              @for($s = 5; $s >= 1; $s--)
                @php
                  $sCount = $starCounts[$s] ?? 0;
                  $pct = $totalRev > 0 ? round(($sCount / $totalRev) * 100) : 0;
                @endphp
                <div class="d-flex align-items-center gap-2 mb-1.5 cursor-pointer" onclick="filterReviews('{{ $s }}', this)">
                  <span class="text-dark fw-semibold" style="width: 40px;">{{ $s }} <i class="fa-solid fa-star text-warning" style="font-size: 0.75rem;"></i></span>
                  <div class="bee-star-bar">
                    <div class="bee-star-fill" style="width: {{ $pct }}%;"></div>
                  </div>
                  <span class="text-muted" style="width: 32px; text-align: right;">{{ $sCount }}</span>
                </div>
              @endfor
            </div>

            <!-- REVIEW SUBMISSION FORM BOX -->
            <div class="card border-0 shadow-sm p-3 rounded-3" style="background: #ffffff; border: 1px solid var(--bee-border) !important;">
              <h6 class="fw-bold text-dark mb-3 text-uppercase" style="font-family: var(--atino-font-heading, inherit);">
                <i class="fa-solid fa-pen-nib me-2 text-danger"></i> <span id="productReviewFormTitle">Gửi Đánh Giá Của Bạn</span>
              </h6>

              <div id="productReviewAlertBox" class="alert d-none mb-3 small py-2.5 px-3 rounded-3"></div>

              @auth
                @if($userHasPurchased)
                  <form id="productPageReviewForm" action="{{ route('client.products.review', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Star Rating Choice -->
                    <div class="mb-3">
                      <label class="form-label small fw-semibold text-dark mb-1">1. Đánh giá chất lượng:</label>
                      <div class="d-flex align-items-center gap-2 flex-wrap">
                        @for($s=5; $s>=1; $s--)
                          <input type="radio" class="btn-check" name="rating" id="star_{{ $s }}" value="{{ $s }}" {{ old('rating', $userReview->rating ?? 5) == $s ? 'checked' : '' }} required>
                          <label class="btn btn-sm btn-outline-warning text-dark fw-bold px-2.5 py-1" for="star_{{ $s }}">
                            {{ $s }} <i class="fa-solid fa-star text-warning"></i>
                          </label>
                        @endfor
                      </div>
                    </div>

                    <!-- Comment textarea -->
                    <div class="mb-3">
                      <label class="form-label small fw-semibold text-dark mb-1">2. Chia sẻ cảm nhận của bạn:</label>
                      <textarea name="comment" id="productPageCommentInput" class="form-control form-control-sm" rows="3" placeholder="Chất liệu vải, form dáng, độ vừa vặn khi mặc..." required>{{ old('comment', $userReview->comment ?? '') }}</textarea>
                    </div>

                    <!-- Photo Upload Box with Instant Preview -->
                    <div class="mb-3">
                      <label class="form-label small fw-semibold text-dark mb-1 d-flex align-items-center justify-content-between">
                        <span><i class="fa-solid fa-camera text-warning me-1"></i> 3. Đính kèm ảnh thực tế:</span>
                        <span class="text-muted fs-11" id="reviewImgCount">Tối đa 5 ảnh</span>
                      </label>
                      <div class="p-2.5 rounded-3 border bg-light text-center position-relative" style="border: 2px dashed #cbd5e1 !important; cursor: pointer;" onclick="document.getElementById('reviewImagesInput').click()">
                        <i class="fa-solid fa-cloud-arrow-up text-warning fs-3 mb-1 d-block"></i>
                        <span class="small text-dark fw-bold d-block">Bấm để chọn hoặc kéo thả ảnh chụp áo</span>
                        <small class="text-muted fs-11">Hỗ trợ JPG, PNG, WEBP (tối đa 5MB/ảnh)</small>
                        <input type="file" name="review_images[]" id="reviewImagesInput" class="d-none" multiple accept="image/*" onchange="previewReviewPhotos(this, 'showReviewPhotoPreview')">
                      </div>
                      
                      <!-- Preview Container -->
                      <div id="showReviewPhotoPreview" class="d-flex gap-2 flex-wrap mt-2">
                        @if(isset($userReview) && !empty($userReview->images_urls))
                          @foreach($userReview->images_urls as $imgUrl)
                            <div class="position-relative">
                              <img src="{{ $imgUrl }}" class="rounded border shadow-xs" style="width: 56px; height: 56px; object-fit: cover;">
                            </div>
                          @endforeach
                        @endif
                      </div>
                    </div>

                    <button type="submit" id="productReviewSubmitBtn" class="btn btn-bee-primary btn-sm w-100 py-2 fw-bold">
                      <i class="fa-solid fa-paper-plane me-1"></i> <span id="productReviewBtnText">{{ $userReview ? 'CẬP NHẬT ĐÁNH GIÁ' : 'GỬI ĐÁNH GIÁ NGAY' }}</span>
                    </button>
                    @if($userReview)
                      <small class="d-block text-center text-success mt-1 fs-11"><i class="fa-solid fa-circle-check"></i> Bạn đã đánh giá sản phẩm này</small>
                    @endif
                  </form>
                @else
                  <div class="alert alert-warning border-0 p-3 mb-0 rounded-3 small">
                    <div class="d-flex align-items-start gap-2">
                      <i class="fa-solid fa-shield-halved text-warning fs-5 mt-1"></i>
                      <div>
                        <strong>Xác thực người mua:</strong>
                        <p class="mb-0 text-muted mt-1">Để đảm bảo tính khách quan và trung thực, chỉ khách hàng <strong>đã từng mua sản phẩm này</strong> mới có thể viết đánh giá kèm hình ảnh.</p>
                      </div>
                    </div>
                  </div>
                @endif
              @else
                <div class="text-center py-3 bg-light rounded-3">
                  <i class="fa-solid fa-user-lock fs-3 text-muted mb-2"></i>
                  <p class="small text-muted mb-3">Vui lòng đăng nhập với tài khoản đã mua hàng để gửi nhận xét &amp; hình ảnh.</p>
                  <a href="{{ route('auth.login') }}" class="btn btn-bee-primary btn-sm px-4 fw-bold">
                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Đăng Nhập Để Đánh Giá
                  </a>
                </div>
              @endauth
            </div>
          </div>

          <!-- Right Column: List of Reviews -->
          <div class="col-lg-8 col-md-7 ps-lg-4">
            <!-- Filter buttons by Stars / Photos -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
              <h5 class="fw-bold text-dark mb-0" style="font-family: var(--atino-font-heading, inherit);">
                Nhận Xét Khách Mua <span id="productPageReviewsCountHeader">({{ $product->reviews->count() }})</span>
              </h5>
              
              <div class="d-flex align-items-center gap-1.5 flex-wrap">
                <button type="button" class="btn btn-dark btn-sm filter-review-btn active shadow-xs" onclick="filterReviews('all', this)">
                  Tất cả ({{ $totalRev }})
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm filter-review-btn" onclick="filterReviews('5', this)">
                  5 ★ ({{ $starCounts[5] ?? 0 }})
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm filter-review-btn" onclick="filterReviews('4', this)">
                  4 ★ ({{ $starCounts[4] ?? 0 }})
                </button>
                @php
                  $reviewsWithPhotos = $product->reviews->filter(fn($r) => !empty($r->images_urls))->count();
                @endphp
                @if($reviewsWithPhotos > 0)
                  <button type="button" class="btn btn-outline-secondary btn-sm filter-review-btn" onclick="filterReviews('photo', this)">
                    <i class="fa-solid fa-camera me-1 text-warning"></i> Có ảnh ({{ $reviewsWithPhotos }})
                  </button>
                @endif
              </div>
            </div>

            <div id="reviewFilterEmptyMsg" class="alert alert-light border text-center p-3 small d-none">
              Không có nhận xét nào phù hợp với bộ lọc này.
            </div>

            <div class="d-flex flex-column gap-3" id="productPageReviewsList">
              @forelse($product->reviews as $rev)
                <div class="p-3 bg-light rounded-3 border review-card-item" data-rating="{{ $rev->rating }}" data-has-photo="{{ !empty($rev->images_urls) ? '1' : '0' }}">
                  <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                    <div class="d-flex align-items-center gap-2 cursor-pointer" onclick="openReviewerModal({{ $rev->id }})" title="Xem hồ sơ khách hàng">
                      <img src="{{ $rev->user_avatar_url }}" alt="{{ $rev->user_name }}" class="rounded-circle border bg-white" style="width: 38px; height: 38px; object-fit: cover;">
                      <div>
                        <strong class="text-dark fs-9 text-hover-primary">{{ $rev->user_name }}</strong>
                        <span class="badge bg-success-subtle text-success ms-1.5 small" style="font-size: 0.72rem;">
                          <i class="fa-solid fa-circle-check me-1"></i> Đã mua hàng
                        </span>
                      </div>
                    </div>
                    <small class="text-muted">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : 'Vừa xong' }}</small>
                  </div>

                  <div class="text-warning small mb-2 d-flex align-items-center gap-1">
                    @for($i=1; $i<=5; $i++)
                      <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                    @endfor
                    <span class="text-dark fw-bold ms-1">({{ $rev->rating }}/5)</span>
                    <span class="badge bg-light text-muted border ms-2" style="font-size: 0.68rem;">Phân loại: Chuẩn form</span>
                  </div>

                  <p class="small text-secondary mb-0 leading-relaxed">
                    {{ $rev->comment }}
                  </p>

                  <!-- Customer Uploaded Review Photos -->
                  @if(!empty($rev->images_urls))
                    <div class="d-flex gap-2 flex-wrap mt-2.5 pt-2 border-top border-secondary border-opacity-10">
                      @foreach($rev->images_urls as $photoUrl)
                        <div class="position-relative" style="cursor: pointer;" onclick="openReviewImageLightbox('{{ $photoUrl }}')">
                          <img src="{{ $photoUrl }}" alt="Ảnh đánh giá" class="rounded border shadow-xs" style="width: 68px; height: 68px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                          <span class="position-absolute bottom-0 end-0 bg-dark text-white px-1 py-0.5 rounded-start" style="font-size: 0.65rem; opacity: 0.85;">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                          </span>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              @empty
                <div class="p-4 bg-light rounded-3 text-center border">
                  <i class="fa-regular fa-comment-dots fs-1 text-muted mb-2"></i>
                  <p class="text-dark fw-semibold mb-1">Chưa có đánh giá nào cho sản phẩm này</p>
                  <p class="small text-muted mb-0">Hãy là người đầu tiên mua và trải nghiệm chất lượng thời trang của BeeStyle!</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================
       4. RELATED PRODUCTS (SẢN PHẨM CÙNG DANH MỤC)
       ======================================================== -->
  @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mb-5">
      <div class="bee-section-header mb-4">
        <div>
          <h3 class="bee-section-title">Gợi Ý Sản Phẩm Cùng Danh Mục</h3>
          <p class="bee-section-subtitle">Có thể bạn cũng sẽ thích những thiết kế thời trang tinh tế này</p>
        </div>
      </div>

      <div class="row g-4">
        @foreach($relatedProducts as $item)
          <div class="col-lg-3 col-md-6 col-6">
            <div class="bee-product-card h-100">
              <div class="bee-product-img-wrapper">
                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                @if($item->original_price && $item->original_price > $item->price)
                  <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-2 py-1 rounded-pill">
                    -{{ round((($item->original_price - $item->price) / $item->original_price) * 100) }}%
                  </span>
                @endif
              </div>
              <div class="bee-product-body d-flex flex-column">
                <span class="bee-product-category">{{ $item->category->name ?? 'Thời Trang Nam' }}</span>
                <a href="{{ route('client.products.show', $item->id) }}" class="bee-product-title">{{ $item->name }}</a>
                <div class="bee-product-price-row mt-auto">
                  <span class="bee-product-price">{{ number_format($item->price, 0, ',', '.') }}₫</span>
                  @if($item->original_price && $item->original_price > $item->price)
                    <span class="text-muted text-decoration-line-through small ms-2">{{ number_format($item->original_price, 0, ',', '.') }}₫</span>
                  @endif
                </div>
                <a href="{{ route('client.products.show', $item->id) }}" class="btn btn-bee-outline btn-sm w-100 mt-2.5">
                  <i class="fa-solid fa-eye me-1"></i> Xem Chi Tiết
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  <!-- ========================================================
       5. RECENTLY VIEWED PRODUCTS (SẢN PHẨM ĐÃ XEM GẦN ĐÂY)
       ======================================================== -->
  @if(isset($recentlyViewedProducts) && $recentlyViewedProducts->count() > 0)
    <div class="mb-5">
      <div class="bee-section-header mb-4">
        <div>
          <h3 class="bee-section-title">Sản Phẩm Bạn Đã Xem Gần Đây</h3>
          <p class="bee-section-subtitle">Dễ dàng xem lại những mẫu trang phục bạn vừa quan tâm</p>
        </div>
      </div>

      <div class="row g-4">
        @foreach($recentlyViewedProducts as $rItem)
          <div class="col-lg-2 col-md-4 col-6">
            <div class="bee-product-card h-100">
              <div class="bee-product-img-wrapper" style="height: 180px;">
                <img src="{{ asset($rItem->image) }}" alt="{{ $rItem->name }}">
              </div>
              <div class="bee-product-body p-2 d-flex flex-column">
                <a href="{{ route('client.products.show', $rItem->id) }}" class="bee-product-title text-truncate" style="font-size: 0.82rem;">{{ $rItem->name }}</a>
                <div class="bee-product-price-row mt-1">
                  <span class="bee-product-price fw-bold text-danger" style="font-size: 0.88rem;">{{ number_format($rItem->price, 0, ',', '.') }}₫</span>
                </div>
                <a href="{{ route('client.products.show', $rItem->id) }}" class="btn btn-bee-outline btn-xs w-100 mt-2 py-1" style="font-size: 0.72rem;">Xem</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>

<!-- ========================================================
     6. FLOATING STICKY BOTTOM PURCHASE BAR
     ======================================================== -->
<div class="bee-sticky-bar" id="stickyAddToCartBar">
  <div class="container d-flex align-items-center justify-content-between gap-3">
    <!-- Left: Product Thumb & Info -->
    <div class="d-flex align-items-center gap-2.5 min-w-0">
      <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="rounded-3 border" style="width: 48px; height: 48px; object-fit: cover;">
      <div class="min-w-0">
        <strong class="d-block text-dark text-truncate small" style="max-width: 280px;">{{ $product->name }}</strong>
        <div class="d-flex align-items-center gap-1.5 flex-wrap">
          <span class="badge bg-light text-muted border px-2 py-0.5" id="stickySelectedVariantText">Chưa chọn màu &amp; size</span>
          <strong class="text-danger fw-bolder" id="stickySubtotalText">{{ number_format($effectivePrice, 0, ',', '.') }}₫</strong>
        </div>
      </div>
    </div>

    <!-- Right: Quick Action Buttons -->
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
      <button type="button" class="btn btn-outline-dark btn-sm py-2 px-3 fw-bold shadow-xs" onclick="triggerStickySubmit(false)">
        <i class="fa-solid fa-cart-plus me-1"></i> Thêm Giỏ
      </button>
      <button type="button" class="btn btn-bee-primary btn-sm py-2 px-3 fw-bold shadow-xs" onclick="triggerStickySubmit(true)">
        <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
      </button>
    </div>
  </div>
</div>

<!-- ========================================================
     7. MODALS: SIZE GUIDE, REVIEWER PROFILE, LIGHTBOX
     ======================================================== -->

<!-- MODAL 1: SIZE GUIDE & SMART AI FIT -->
<div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-bottom px-4 py-3">
        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
          <i class="fa-solid fa-ruler-horizontal text-warning"></i> Bảng Quy Đổi Size Nam &amp; AI Gợi Ý Size
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        
        <!-- Interactive Smart Fit Box Inside Modal -->
        <div class="bee-smart-size-box mb-4">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-success text-white px-2.5 py-1 fw-bold">
              <i class="fa-solid fa-wand-magic-sparkles me-1"></i> AI Smart Fit
            </span>
            <strong class="text-dark">Tính Size Chuẩn Theo Cân Nặng &amp; Chiều Cao:</strong>
          </div>
          <div class="row g-3 align-items-end">
            <div class="col-md-4 col-6">
              <label class="form-label small fw-bold text-dark mb-1">Chiều cao (cm):</label>
              <input type="number" id="modalCalcHeight" class="form-control form-control-sm text-center fw-bold" value="172" min="150" max="200" oninput="calculateModalSmartFitSize()">
            </div>
            <div class="col-md-4 col-6">
              <label class="form-label small fw-bold text-dark mb-1">Cân nặng (kg):</label>
              <input type="number" id="modalCalcWeight" class="form-control form-control-sm text-center fw-bold" value="67" min="40" max="120" oninput="calculateModalSmartFitSize()">
            </div>
            <div class="col-md-4 col-12">
              <button type="button" class="btn btn-success btn-sm w-100 py-2 fw-bold" onclick="applySuggestedSize()">
                <i class="fa-solid fa-check me-1"></i> Áp Dụng Size Này
              </button>
            </div>
          </div>
          
          <div class="mt-3 p-2.5 bg-white rounded-3 border border-success d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
              <span class="badge bg-success me-2" id="suggestedSizeBadge">Size L</span>
              <strong class="text-dark small" id="suggestedSizeName">Size L (Phom Regular Fit)</strong>
            </div>
            <small class="text-muted" id="suggestedSizeDesc">Gợi ý dựa trên thể trạng nam giới Việt Nam.</small>
          </div>
        </div>

        <!-- Standard Size Chart Table -->
        <h6 class="fw-bold text-dark mb-2.5">Bảng Thông Số Size Nam Chuẩn:</h6>
        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle small mb-0">
            <thead class="table-dark">
              <tr>
                <th>Size</th>
                <th>Chiều Cao</th>
                <th>Cân Nặng</th>
                <th>Rộng Ngực</th>
                <th>Dài Áo</th>
                <th>Dáng Người</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong class="text-warning fs-6">S</strong></td>
                <td>1m55 - 1m65</td>
                <td>50 - 58 kg</td>
                <td>94 cm</td>
                <td>67 cm</td>
                <td>Gọn gàng / Nhỏ</td>
              </tr>
              <tr>
                <td><strong class="text-warning fs-6">M</strong></td>
                <td>1m64 - 1m72</td>
                <td>58 - 65 kg</td>
                <td>98 cm</td>
                <td>69 cm</td>
                <td>Cân đối / Slim</td>
              </tr>
              <tr>
                <td><strong class="text-warning fs-6">L</strong></td>
                <td>1m70 - 1m78</td>
                <td>65 - 72 kg</td>
                <td>102 cm</td>
                <td>71 cm</td>
                <td>Chuẩn / Đậm người</td>
              </tr>
              <tr>
                <td><strong class="text-warning fs-6">XL</strong></td>
                <td>1m75 - 1m83</td>
                <td>72 - 80 kg</td>
                <td>106 cm</td>
                <td>73 cm</td>
                <td>To cao / Đầy đặn</td>
              </tr>
              <tr>
                <td><strong class="text-warning fs-6">XXL</strong></td>
                <td>1m80 - 1m90</td>
                <td>80 - 88 kg</td>
                <td>110 cm</td>
                <td>75 cm</td>
                <td>Ngoại cỡ / Thoải mái</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL 2: CUSTOMER REVIEWER PROFILE -->
<div class="modal fade" id="customerReviewerProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-bottom px-4 py-3">
        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
          <i class="fa-solid fa-user-check text-success"></i> Hồ Sơ Người Mua Hàng
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Reviewer Card -->
        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-4 border mb-3">
          <img src="" id="revModalAvatar" alt="Avatar" class="rounded-circle border bg-white shadow-xs" style="width: 58px; height: 58px; object-fit: cover;">
          <div class="flex-grow-1 min-w-0">
            <h6 class="fw-bold text-dark mb-1" id="revModalName">Khách Hàng</h6>
            <div class="d-flex align-items-center gap-2 flex-wrap small">
              <span id="revModalRankBadge" class="badge bg-warning text-dark px-2 py-0.5">Hội Viên Vàng</span>
              <span class="text-muted" id="revModalJoined" style="font-size: 0.75rem;">Thành viên từ 2025</span>
            </div>
          </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-2 text-center mb-3">
          <div class="col-6">
            <div class="p-2.5 bg-light rounded-3 border">
              <strong class="fs-5 text-dark d-block" id="revModalOrdersCount">12</strong>
              <small class="text-muted" style="font-size: 0.72rem;">Đơn Hàng Đã Mua</small>
            </div>
          </div>
          <div class="col-6">
            <div class="p-2.5 bg-light rounded-3 border">
              <strong class="fs-5 text-warning d-block" id="revModalReviewsCount">4</strong>
              <small class="text-muted" style="font-size: 0.72rem;">Đánh Giá Đã Viết</small>
            </div>
          </div>
        </div>

        <!-- Other Reviews by This User -->
        <h6 class="fw-bold text-dark mb-2 small text-uppercase">Các đánh giá khác của người mua:</h6>
        <div class="d-flex flex-column gap-2" id="revModalOtherReviewsList" style="max-height: 220px; overflow-y: auto;">
          <!-- Populated by JS -->
        </div>

        <!-- Admin View Link (if Admin) -->
        <div class="mt-3 pt-3 border-top d-none text-center" id="revModalAdminBox">
          <a href="#" id="revModalAdminLink" class="btn btn-outline-danger btn-sm px-3 rounded-pill">
            <i class="fa-solid fa-gear me-1"></i> Xem Chi Tiết Khách Hàng Trong Admin
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL 3: ULTRA-HD LIGHTBOX MODAL -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-0 bg-dark text-white" style="border-radius: 16px;">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title text-white-50 small">{{ $product->name }}</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-3 p-md-4">
        <img src="" id="modalGalleryImg" alt="Xem ảnh lớn" class="img-fluid rounded-3 shadow-lg" style="max-height: 75vh; object-fit: contain;">
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // ========================================================
  // DỮ LIỆU BIẾN THỂ VÀ TỒN KHO THỰC TẾ TỪ DATABASE
  // ========================================================
  const PRODUCT_VARIANTS = {!! json_encode($product->variants->map(function($v) use ($product, $effectivePrice) {
      return [
          'id' => $v->id,
          'color' => trim($v->color),
          'size' => trim($v->size),
          'price' => (int)($v->price ?: $effectivePrice),
          'original_price' => (int)($v->original_price ?: $product->original_price),
          'stock' => (int)$v->stock,
          'sku' => $v->sku,
          'image' => $v->image ? asset($v->image) : null,
          'status' => $v->status,
      ];
  })) !!};

  const GALLERY_IMAGES = {!! json_encode($allGalleryImages->map(fn($img) => asset($img))->all()) !!};
  const PRODUCT_TOTAL_STOCK = {{ $product->variants->count() > 0 ? (int)$product->variants->sum('stock') : (int)$product->stock }};
  const BASE_PRICE = {{ (int)$effectivePrice }};
  const BASE_ORIGINAL_PRICE = {{ (int)($product->original_price ?: $product->price) }};
  const BASE_SKU = "{{ $product->sku }}";

  let currentGalleryIndex = 0;
  let selectedProductColor = '';
  let selectedProductSize = '';
  let currentProductUnitPrice = BASE_PRICE;
  let currentVariantStock = PRODUCT_TOTAL_STOCK;
  let currentMaxQty = Math.min(10, Math.max(0, PRODUCT_TOTAL_STOCK));
  let currentSelectedVariant = null;

  // ========================================================
  // GALLERY CONTROLLER: ZOOM, THUMBNAILS, ARROWS, LIGHTBOX
  // ========================================================
  function setupMainImageZoom() {
    const container = document.getElementById('mainImgZoomContainer');
    const img = document.getElementById('mainProductImg');
    if (!container || !img) return;

    container.addEventListener('mousemove', function(e) {
      const rect = container.getBoundingClientRect();
      const x = Math.max(0, Math.min(100, ((e.clientX - rect.left) / rect.width) * 100));
      const y = Math.max(0, Math.min(100, ((e.clientY - rect.top) / rect.height) * 100));
      img.style.transformOrigin = `${x}% ${y}%`;
      img.style.transform = 'scale(2.1)';
    });

    container.addEventListener('mouseleave', function() {
      img.style.transformOrigin = 'center center';
      img.style.transform = 'scale(1)';
    });
  }

  function changeMainImg(src, el, idx) {
    const img = document.getElementById('mainProductImg');
    if (img) {
      img.src = src;
      img.style.transformOrigin = 'center center';
      img.style.transform = 'scale(1)';
    }
    if (typeof idx !== 'undefined') {
      currentGalleryIndex = idx;
    }
    document.querySelectorAll('.bee-thumb-item').forEach(item => {
      item.classList.remove('active');
    });
    if (el) {
      el.classList.add('active');
    }
  }

  function navigateGallery(step) {
    if (!GALLERY_IMAGES || GALLERY_IMAGES.length <= 1) return;
    currentGalleryIndex = (currentGalleryIndex + step + GALLERY_IMAGES.length) % GALLERY_IMAGES.length;
    const targetSrc = GALLERY_IMAGES[currentGalleryIndex];
    const thumbs = document.querySelectorAll('.bee-thumb-item');
    const targetThumb = thumbs[currentGalleryIndex];
    changeMainImg(targetSrc, targetThumb, currentGalleryIndex);
  }

  function openMainGalleryLightbox() {
    const currentImg = document.getElementById('mainProductImg');
    if (currentImg) {
      openReviewImageLightbox(currentImg.src);
    }
  }

  function openReviewImageLightbox(imgUrl) {
    const modalImg = document.getElementById('modalGalleryImg');
    if (modalImg) {
      modalImg.src = imgUrl;
      const modalEl = document.getElementById('galleryModal');
      if (modalEl) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
      }
    }
  }

  // ========================================================
  // VARIANT SELECTION & REALTIME STOCK SYNC
  // ========================================================
  function selectProductColor(color) {
    selectedProductColor = color;
    const el = document.getElementById('selectedColorText');
    if (el) {
      el.className = 'badge bg-dark text-warning border border-warning px-2.5 py-1 ms-1 fw-bold';
      el.textContent = color;
    }
    const colorSec = document.getElementById('colorGroupSection');
    if (colorSec) {
      colorSec.style.borderColor = '#e2e8f0';
      colorSec.style.backgroundColor = '#ffffff';
    }
    hideFormAlert();

    // Nếu biến thể màu có ảnh, chuyển ảnh chính sang ảnh đó
    const matchedVariantWithImg = PRODUCT_VARIANTS.find(v => 
      v.color.trim().toLowerCase() === color.trim().toLowerCase() && v.image
    );
    if (matchedVariantWithImg && matchedVariantWithImg.image) {
      changeMainImg(matchedVariantWithImg.image);
    }

    syncVariantAndStock();
  }

  function selectProductSize(size, hint) {
    selectedProductSize = size;
    const el = document.getElementById('selectedSizeText');
    if (el) {
      el.className = 'badge bg-dark text-warning border border-warning px-2.5 py-1 ms-1 fw-bold';
      el.textContent = 'Size ' + size + (hint ? ' (' + hint + ')' : '');
    }
    const sizeSec = document.getElementById('sizeGroupSection');
    if (sizeSec) {
      sizeSec.style.borderColor = '#e2e8f0';
      sizeSec.style.backgroundColor = '#ffffff';
    }
    hideFormAlert();
    syncVariantAndStock();
  }

  function updateSizeButtonsAvailability() {
    const sizeRadios = document.querySelectorAll('.product-size-radio');
    sizeRadios.forEach((radio, idx) => {
      const szVal = radio.value.trim().toUpperCase();
      const tagEl = document.getElementById('sizeStockTag_' + idx);
      const labelEl = document.getElementById('sizeLabel_' + idx);
      
      if (!tagEl || !labelEl) return;

      if (selectedProductColor) {
        const matched = PRODUCT_VARIANTS.find(v => 
          v.color.trim().toLowerCase() === selectedProductColor.trim().toLowerCase() && 
          v.size.trim().toUpperCase() === szVal
        );

        tagEl.style.display = 'inline-block';
        if (matched) {
          if (matched.stock <= 0) {
            tagEl.className = 'size-stock-tag text-danger fw-bold';
            tagEl.innerHTML = '(Hết)';
            labelEl.classList.add('out-of-stock');
          } else if (matched.stock <= 5) {
            tagEl.className = 'size-stock-tag text-warning-emphasis fw-bold';
            tagEl.innerHTML = `(Còn ${matched.stock})`;
            labelEl.classList.remove('out-of-stock');
          } else {
            tagEl.className = 'size-stock-tag text-success fw-semibold';
            tagEl.innerHTML = `(Còn ${matched.stock})`;
            labelEl.classList.remove('out-of-stock');
          }
        } else {
          tagEl.className = 'size-stock-tag text-muted';
          tagEl.innerHTML = '(0)';
          labelEl.classList.add('out-of-stock');
        }
      } else {
        const totalSizeStock = PRODUCT_VARIANTS
          .filter(v => v.size.trim().toUpperCase() === szVal)
          .reduce((sum, v) => sum + v.stock, 0);

        tagEl.style.display = 'inline-block';
        if (totalSizeStock <= 0) {
          tagEl.className = 'size-stock-tag text-danger fw-bold';
          tagEl.innerHTML = '(Hết)';
          labelEl.classList.add('out-of-stock');
        } else {
          tagEl.className = 'size-stock-tag text-muted';
          tagEl.innerHTML = `(Kho: ${totalSizeStock})`;
          labelEl.classList.remove('out-of-stock');
        }
      }
    });
  }

  function syncVariantAndStock() {
    const stockCountEl = document.getElementById('displayStockCount');
    const stockLabelEl = document.getElementById('stockLabelText');
    const stockBadgeEl = document.getElementById('displayStockBadge');
    const displayMaxQtyEl = document.getElementById('displayMaxQty');
    const variantIdInput = document.getElementById('selectedVariantId');
    const outOfStockAlert = document.getElementById('variantOutOfStockAlert');
    const btnAddToCart = document.getElementById('btnAddToCart');
    const btnBuyNow = document.getElementById('btnBuyNow');
    const displaySku = document.getElementById('displaySku');
    const displayPrice = document.getElementById('displayPrice');
    const qtyInput = document.getElementById('productQty');

    if (selectedProductColor && selectedProductSize) {
      currentSelectedVariant = PRODUCT_VARIANTS.find(v => 
        v.color.trim().toLowerCase() === selectedProductColor.trim().toLowerCase() && 
        v.size.trim().toUpperCase() === selectedProductSize.trim().toUpperCase()
      );

      if (currentSelectedVariant) {
        currentVariantStock = currentSelectedVariant.stock;
        if (variantIdInput) variantIdInput.value = currentSelectedVariant.id;
        if (displaySku && currentSelectedVariant.sku) displaySku.textContent = currentSelectedVariant.sku;
        
        if (currentSelectedVariant.price) {
          currentProductUnitPrice = currentSelectedVariant.price;
          if (displayPrice) displayPrice.textContent = currentProductUnitPrice.toLocaleString('vi-VN') + '₫';
        }

        if (stockLabelEl) stockLabelEl.textContent = `Kho (${currentSelectedVariant.color} - Size ${currentSelectedVariant.size}):`;
        if (stockCountEl) stockCountEl.textContent = currentVariantStock;

        if (currentVariantStock <= 0) {
          currentMaxQty = 0;
          if (stockBadgeEl) {
            stockBadgeEl.className = 'badge bg-danger text-white px-2.5 py-1 fw-bold';
            stockBadgeEl.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> Tạm hết hàng trong kho';
          }
          if (outOfStockAlert) {
            outOfStockAlert.classList.remove('d-none');
            const alertText = document.getElementById('variantOutOfStockText');
            if (alertText) {
              alertText.textContent = `Phiên bản Màu ${currentSelectedVariant.color} - Size ${currentSelectedVariant.size} hiện đã tạm hết hàng trong kho! Quý khách vui lòng chọn màu sắc hoặc kích cỡ khác.`;
            }
          }
          if (btnAddToCart) {
            btnAddToCart.disabled = true;
            btnAddToCart.classList.add('disabled', 'opacity-50');
            btnAddToCart.innerHTML = '<i class="fa-solid fa-ban me-1.5"></i> Hết Hàng';
          }
          if (btnBuyNow) {
            btnBuyNow.disabled = true;
            btnBuyNow.classList.add('disabled', 'opacity-50');
            btnBuyNow.innerHTML = '<i class="fa-solid fa-ban me-1.5"></i> Hết Hàng';
          }
          updateQtyDisplay(0);
        } else {
          currentMaxQty = Math.min(10, currentVariantStock);
          if (stockBadgeEl) {
            if (currentVariantStock <= 5) {
              stockBadgeEl.className = 'badge bg-warning text-dark px-2.5 py-1 fw-bold';
              stockBadgeEl.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> Sắp hết hàng (Chỉ còn ${currentVariantStock} cái)`;
            } else {
              stockBadgeEl.className = 'badge bg-success text-white px-2.5 py-1 fw-bold';
              stockBadgeEl.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> Còn hàng (${currentVariantStock} cái có sẵn)`;
            }
          }
          if (outOfStockAlert) outOfStockAlert.classList.add('d-none');
          if (btnAddToCart) {
            btnAddToCart.disabled = false;
            btnAddToCart.classList.remove('disabled', 'opacity-50');
            btnAddToCart.innerHTML = '<i class="fa-solid fa-cart-plus me-1.5"></i> Thêm Vào Giỏ';
          }
          if (btnBuyNow) {
            btnBuyNow.disabled = false;
            btnBuyNow.classList.remove('disabled', 'opacity-50');
            btnBuyNow.innerHTML = '<i class="fa-solid fa-bolt me-1.5"></i> Mua Ngay';
          }

          let curVal = parseInt(qtyInput ? qtyInput.value : 1) || 1;
          if (curVal > currentMaxQty) curVal = currentMaxQty;
          if (curVal < 1) curVal = 1;
          updateQtyDisplay(curVal);
        }
      } else {
        if (stockLabelEl) stockLabelEl.textContent = 'Tồn kho:';
        if (stockCountEl) stockCountEl.textContent = '0';
        if (stockBadgeEl) {
          stockBadgeEl.className = 'badge bg-secondary text-white px-2.5 py-1';
          stockBadgeEl.innerHTML = '<i class="fa-solid fa-ban me-1"></i> Phân loại chưa có sẵn';
        }
        if (btnAddToCart) {
          btnAddToCart.disabled = true;
          btnAddToCart.classList.add('disabled', 'opacity-50');
        }
        if (btnBuyNow) {
          btnBuyNow.disabled = true;
          btnBuyNow.classList.add('disabled', 'opacity-50');
        }
      }
    } else if (selectedProductColor) {
      currentSelectedVariant = null;
      if (variantIdInput) variantIdInput.value = '';
      if (displaySku) displaySku.textContent = BASE_SKU;

      const colorVariants = PRODUCT_VARIANTS.filter(v => v.color.trim().toLowerCase() === selectedProductColor.trim().toLowerCase());
      const colorStock = colorVariants.reduce((sum, v) => sum + v.stock, 0);

      if (stockLabelEl) stockLabelEl.textContent = `Kho màu ${selectedProductColor}:`;
      if (stockCountEl) stockCountEl.textContent = colorStock;

      if (stockBadgeEl) {
        stockBadgeEl.className = 'badge bg-info-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold';
        stockBadgeEl.innerHTML = `<i class="fa-solid fa-palette me-1"></i> Còn ${colorStock} cái màu ${selectedProductColor} • Vui lòng chọn Size`;
      }
      if (outOfStockAlert) outOfStockAlert.classList.add('d-none');
      if (btnAddToCart) {
        btnAddToCart.disabled = false;
        btnAddToCart.classList.remove('disabled', 'opacity-50');
        btnAddToCart.innerHTML = '<i class="fa-solid fa-cart-plus me-1.5"></i> Thêm Vào Giỏ';
      }
      if (btnBuyNow) {
        btnBuyNow.disabled = false;
        btnBuyNow.classList.remove('disabled', 'opacity-50');
        btnBuyNow.innerHTML = '<i class="fa-solid fa-bolt me-1.5"></i> Mua Ngay';
      }
      currentMaxQty = Math.min(10, Math.max(1, colorStock));
      updateQtyDisplay(parseInt(qtyInput ? qtyInput.value : 1) || 1);
    } else if (selectedProductSize) {
      currentSelectedVariant = null;
      if (variantIdInput) variantIdInput.value = '';
      if (displaySku) displaySku.textContent = BASE_SKU;

      const sizeVariants = PRODUCT_VARIANTS.filter(v => v.size.trim().toUpperCase() === selectedProductSize.trim().toUpperCase());
      const sizeStock = sizeVariants.reduce((sum, v) => sum + v.stock, 0);

      if (stockLabelEl) stockLabelEl.textContent = `Kho Size ${selectedProductSize}:`;
      if (stockCountEl) stockCountEl.textContent = sizeStock;

      if (stockBadgeEl) {
        stockBadgeEl.className = 'badge bg-info-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold';
        stockBadgeEl.innerHTML = `<i class="fa-solid fa-ruler-combined me-1"></i> Còn ${sizeStock} cái Size ${selectedProductSize} • Vui lòng chọn Màu`;
      }
      if (outOfStockAlert) outOfStockAlert.classList.add('d-none');
      if (btnAddToCart) {
        btnAddToCart.disabled = false;
        btnAddToCart.classList.remove('disabled', 'opacity-50');
        btnAddToCart.innerHTML = '<i class="fa-solid fa-cart-plus me-1.5"></i> Thêm Vào Giỏ';
      }
      if (btnBuyNow) {
        btnBuyNow.disabled = false;
        btnBuyNow.classList.remove('disabled', 'opacity-50');
        btnBuyNow.innerHTML = '<i class="fa-solid fa-bolt me-1.5"></i> Mua Ngay';
      }
      currentMaxQty = Math.min(10, Math.max(1, sizeStock));
      updateQtyDisplay(parseInt(qtyInput ? qtyInput.value : 1) || 1);
    } else {
      currentSelectedVariant = null;
      if (variantIdInput) variantIdInput.value = '';
      if (displaySku) displaySku.textContent = BASE_SKU;

      if (stockLabelEl) stockLabelEl.textContent = 'Tổng kho:';
      if (stockCountEl) stockCountEl.textContent = PRODUCT_TOTAL_STOCK;

      if (stockBadgeEl) {
        stockBadgeEl.className = 'badge bg-light text-muted border px-2.5 py-1';
        stockBadgeEl.innerHTML = '<i class="fa-solid fa-boxes-stacked text-warning me-1"></i> Chọn Màu &amp; Size để xem chi tiết tồn kho';
      }
      if (outOfStockAlert) outOfStockAlert.classList.add('d-none');
      if (btnAddToCart) {
        btnAddToCart.disabled = false;
        btnAddToCart.classList.remove('disabled', 'opacity-50');
        btnAddToCart.innerHTML = '<i class="fa-solid fa-cart-plus me-1.5"></i> Thêm Vào Giỏ';
      }
      if (btnBuyNow) {
        btnBuyNow.disabled = false;
        btnBuyNow.classList.remove('disabled', 'opacity-50');
        btnBuyNow.innerHTML = '<i class="fa-solid fa-bolt me-1.5"></i> Mua Ngay';
      }
      currentMaxQty = Math.min(10, Math.max(1, PRODUCT_TOTAL_STOCK));
      updateQtyDisplay(parseInt(qtyInput ? qtyInput.value : 1) || 1);
    }

    if (displayMaxQtyEl) displayMaxQtyEl.textContent = currentMaxQty;
    updateSizeButtonsAvailability();
    updateStickyBarVariantInfo();
  }

  function updateQtyDisplay(val) {
    const input = document.getElementById('productQty');
    const badge = document.getElementById('showQtyLiveBadge');
    const subtotal = document.getElementById('productSubtotalLive');
    const btnMinus = document.getElementById('btnMinusQty');
    const btnPlus = document.getElementById('btnPlusQty');
    const maxMsg = document.getElementById('maxLimitMsg');

    if (currentMaxQty <= 0) {
      val = 0;
      if (input) input.value = 0;
      if (badge) badge.textContent = 0;
      if (subtotal) subtotal.textContent = '0₫';
      if (btnMinus) { btnMinus.disabled = true; btnMinus.style.opacity = '0.45'; }
      if (btnPlus) { btnPlus.disabled = true; btnPlus.style.opacity = '0.45'; }
      if (maxMsg) maxMsg.classList.add('d-none');
      return;
    }

    if (val < 1) val = 1;
    if (val > currentMaxQty) val = currentMaxQty;

    if (input) input.value = val;
    if (badge) {
      badge.textContent = val;
      badge.classList.remove('animate-scale');
      void badge.offsetWidth;
      badge.classList.add('animate-scale');
    }
    if (subtotal) {
      const total = currentProductUnitPrice * val;
      subtotal.textContent = total.toLocaleString('vi-VN') + '₫';
    }
    if (btnMinus) {
      btnMinus.disabled = (val <= 1);
      btnMinus.style.opacity = (val <= 1) ? '0.45' : '1';
    }
    if (btnPlus) {
      btnPlus.disabled = (val >= currentMaxQty);
      btnPlus.style.opacity = (val >= currentMaxQty) ? '0.45' : '1';
    }
    if (maxMsg) {
      if (val >= currentMaxQty && currentMaxQty > 0) {
        maxMsg.classList.remove('d-none');
        maxMsg.innerHTML = `<i class="fa-solid fa-circle-exclamation text-danger me-1"></i> Quý khách đã chọn số lượng tối đa cho phép (${currentMaxQty} sản phẩm) của phân loại này.`;
      } else {
        maxMsg.classList.add('d-none');
      }
    }
  }

  function stepProductQty(amount) {
    if (currentMaxQty <= 0) return;
    const input = document.getElementById('productQty');
    if (!input) return;
    let val = parseInt(input.value) || 1;
    val += amount;
    if (val < 1) val = 1;
    if (val > currentMaxQty) val = currentMaxQty;
    updateQtyDisplay(val);
  }

  function showFormAlert(text) {
    const alertEl = document.getElementById('productFormAlert');
    const textEl = document.getElementById('productFormAlertText');
    if (alertEl && textEl) {
      textEl.textContent = text;
      alertEl.classList.remove('d-none');
      alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  function hideFormAlert() {
    const alertEl = document.getElementById('productFormAlert');
    if (alertEl) alertEl.classList.add('d-none');
  }

  function handleProductFormSubmit(e) {
    const checkedColor = document.querySelector('input[name="color"]:checked');
    const hasColors = document.querySelectorAll('input[name="color"]').length > 0;
    if (hasColors && !checkedColor) {
      e.preventDefault();
      const colorSec = document.getElementById('colorGroupSection');
      if (colorSec) {
        colorSec.style.borderColor = '#e11d48';
        colorSec.style.backgroundColor = '#fff1f2';
        colorSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      showFormAlert('Vui lòng chọn 1 Màu Sắc cho sản phẩm trước khi mua hàng!');
      return false;
    }

    const checkedSize = document.querySelector('input[name="size"]:checked');
    const hasSizes = document.querySelectorAll('input[name="size"]').length > 0;
    if (hasSizes && !checkedSize) {
      e.preventDefault();
      const sizeSec = document.getElementById('sizeGroupSection');
      if (sizeSec) {
        sizeSec.style.borderColor = '#e11d48';
        sizeSec.style.backgroundColor = '#fff1f2';
        sizeSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      showFormAlert('Vui lòng chọn 1 Kích Thước (Size) cho sản phẩm trước khi mua hàng!');
      return false;
    }

    if (currentSelectedVariant && currentSelectedVariant.stock <= 0) {
      e.preventDefault();
      const outAlert = document.getElementById('variantOutOfStockAlert');
      if (outAlert) {
        outAlert.classList.remove('d-none');
        outAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      return false;
    }

    const qtyInput = document.getElementById('productQty');
    if (qtyInput) {
      let qty = parseInt(qtyInput.value) || 1;
      if (qty > currentMaxQty) {
        qtyInput.value = currentMaxQty;
      }
    }
    return true;
  }

  // ========================================================
  // COPY VOUCHER CODE WITH TOAST FEEDBACK
  // ========================================================
  function copyCouponCode(code) {
    navigator.clipboard.writeText(code).then(() => {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'success',
          title: 'Đã Sao Chép Mã Voucher!',
          text: `Mã "${code}" đã được sao chép vào bộ nhớ tạm. Hãy dán mã tại bước Thanh Toán để nhận ưu đãi!`,
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });
      } else {
        alert('Đã sao chép mã voucher: ' + code);
      }
    });
  }

  // ========================================================
  // SMART AI SIZE CALCULATOR & AUTO-APPLY
  // ========================================================
  function calculateSizeByHeightWeight(h, w) {
    h = parseInt(h) || 170;
    w = parseInt(w) || 65;
    let recSize = 'L';
    let desc = 'Vừa vặn thoải mái, tôn dáng chuẩn phom';

    if (w < 58 && h < 168) {
      recSize = 'S';
      desc = 'Chuẩn phom dáng vừa vặn, gọn gàng (50-58kg, 1m55-1m65)';
    } else if (w <= 65 && h <= 173) {
      recSize = 'M';
      desc = 'Phom dáng Slim Fit hiện đại, ôm nhẹ (58-65kg, 1m65-1m72)';
    } else if (w <= 72 && h <= 178) {
      recSize = 'L';
      desc = 'Vừa vặn thoải mái, vận động tự tin cả ngày (65-72kg, 1m70-1m77)';
    } else if (w <= 80 && h <= 183) {
      recSize = 'XL';
      desc = 'Phom Regular Fit rộng rãi, thoáng mát (72-80kg, 1m75-1m82)';
    } else if (w <= 88) {
      recSize = 'XXL';
      desc = 'Form dáng rộng thoải mái, che khuyết điểm (80-88kg, 1m78-1m88)';
    } else {
      recSize = '3XL';
      desc = 'Form dáng cực rộng thoải mái (> 88kg)';
    }
    return { size: recSize, desc: desc, height: h, weight: w };
  }

  let currentTabCalculatedSize = 'L';
  let currentModalCalculatedSize = 'L';

  function calculateTabRecommendedSize() {
    const h = document.getElementById('tabCalcHeight')?.value || 172;
    const w = document.getElementById('tabCalcWeight')?.value || 67;
    const res = calculateSizeByHeightWeight(h, w);
    currentTabCalculatedSize = res.size;

    const badge = document.getElementById('tabRecommendedSizeBadge');
    const descEl = document.getElementById('tabRecommendedSizeDesc');
    if (badge) badge.textContent = 'Size ' + res.size;
    if (descEl) descEl.textContent = res.desc;
  }

  function applyTabRecommendedSize() {
    applySizeToProductForm(currentTabCalculatedSize);
  }

  function calculateModalSmartFitSize() {
    const h = document.getElementById('modalCalcHeight')?.value || 172;
    const w = document.getElementById('modalCalcWeight')?.value || 67;
    const res = calculateSizeByHeightWeight(h, w);
    currentModalCalculatedSize = res.size;

    const badge = document.getElementById('suggestedSizeBadge');
    const nameEl = document.getElementById('suggestedSizeName');
    const descEl = document.getElementById('suggestedSizeDesc');

    if (badge) badge.textContent = 'Size ' + res.size;
    if (nameEl) nameEl.textContent = `Size ${res.size} (Phom Regular Fit)`;
    if (descEl) descEl.textContent = `Dựa trên chiều cao ${res.height}cm và cân nặng ${res.weight}kg (${res.desc}).`;
  }

  function applySuggestedSize() {
    applySizeToProductForm(currentModalCalculatedSize);
  }

  function applySizeToProductForm(size) {
    if (!size) return;
    const sizeRadios = document.querySelectorAll('.product-size-radio');
    let matched = false;
    sizeRadios.forEach(r => {
      if (r.value.toUpperCase() === size.toUpperCase()) {
        r.checked = true;
        matched = true;
        r.dispatchEvent(new Event('change'));
      }
    });

    selectProductSize(size, getShowSizeHint(size));

    const modalEl = document.getElementById('sizeGuideModal');
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }

    const sizeSec = document.getElementById('sizeGroupSection');
    if (sizeSec) {
      sizeSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
      sizeSec.style.border = '2px solid #f59e0b';
      sizeSec.style.backgroundColor = '#fffbeb';
      setTimeout(() => {
        sizeSec.style.border = '1px solid var(--bee-border)';
        sizeSec.style.backgroundColor = '#ffffff';
      }, 2000);
    }

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'success',
        title: 'Đã Chọn Size Thành Công!',
        text: `Hệ thống đã chọn Size ${size} cho bạn. Bạn chỉ cần chọn thêm Màu sắc là có thể đặt hàng ngay!`,
        toast: true,
        position: 'top-end',
        timer: 3500,
        showConfirmButton: false
      });
    }
  }

  // ========================================================
  // REVIEWS FILTER & REVIEWER PROFILE MODAL
  // ========================================================
  function filterReviews(filterType, btnEl) {
    document.querySelectorAll('.filter-review-btn').forEach(btn => {
      btn.classList.remove('btn-dark', 'active', 'shadow-xs');
      btn.classList.add('btn-outline-secondary');
    });
    if (btnEl) {
      btnEl.classList.remove('btn-outline-secondary');
      btnEl.classList.add('btn-dark', 'active', 'shadow-xs');
    }

    const items = document.querySelectorAll('.review-card-item');
    let visibleCount = 0;
    items.forEach(item => {
      const rating = item.getAttribute('data-rating');
      const hasPhoto = item.getAttribute('data-has-photo');
      let show = false;
      if (filterType === 'all') {
        show = true;
      } else if (filterType === 'photo') {
        show = (hasPhoto === '1');
      } else {
        show = (rating === filterType);
      }

      if (show) {
        item.style.display = '';
        visibleCount++;
      } else {
        item.style.display = 'none';
      }
    });

    const emptyEl = document.getElementById('reviewFilterEmptyMsg');
    if (emptyEl) {
      emptyEl.classList.toggle('d-none', visibleCount > 0);
    }
  }

  function openReviewerModal(reviewId) {
    fetch(`/san-pham/api-reviewer-profile/${reviewId}`)
      .then(res => res.json())
      .then(data => {
        if (!data.success) return;

        document.getElementById('revModalName').textContent = data.user_name || 'Khách Hàng';
        document.getElementById('revModalAvatar').src = data.avatar_url;
        document.getElementById('revModalJoined').innerHTML = `<i class="fa-solid fa-calendar-check me-1 text-warning"></i> ${data.joined_at}`;
        document.getElementById('revModalOrdersCount').textContent = data.total_orders;
        document.getElementById('revModalReviewsCount').textContent = data.total_reviews;

        const rankBadge = document.getElementById('revModalRankBadge');
        if (rankBadge) {
          rankBadge.className = data.rank_class + ' px-2 py-0.5';
          rankBadge.innerHTML = `<i class="fa-solid ${data.rank_icon} me-1"></i> ${data.rank_name}`;
        }

        const listEl = document.getElementById('revModalOtherReviewsList');
        if (listEl) {
          listEl.innerHTML = '';
          if (data.other_reviews && data.other_reviews.length > 0) {
            data.other_reviews.forEach(or => {
              let stars = '';
              for (let i = 1; i <= 5; i++) {
                stars += `<i class="fa-solid fa-star ${i <= or.rating ? 'text-warning' : 'text-secondary-subtle'}"></i>`;
              }
              const itemHtml = `
                <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-2.5">
                  <img src="${or.product_image}" alt="${or.product_name}" class="rounded border shadow-xs" style="width: 46px; height: 46px; object-fit: cover;">
                  <div class="flex-grow-1 min-w-0">
                    <a href="${or.product_url}" class="text-dark fw-bold text-decoration-none d-block text-truncate small" style="font-size: 0.8rem;">${or.product_name}</a>
                    <div class="d-flex align-items-center gap-1.5 small text-warning" style="font-size: 0.7rem;">
                      ${stars} <span class="text-muted ms-1">${or.date}</span>
                    </div>
                    <p class="text-muted mb-0 small text-truncate" style="font-size: 0.72rem;">${or.comment}</p>
                  </div>
                  <a href="${or.product_url}" class="btn btn-outline-dark btn-xs px-2 rounded-pill flex-shrink-0" style="font-size: 0.68rem;">Xem</a>
                </div>
              `;
              listEl.innerHTML += itemHtml;
            });
          } else {
            listEl.innerHTML = `
              <div class="p-3 bg-light rounded-3 text-center border">
                <small class="text-muted">Khách hàng này chưa chia sẻ thêm đánh giá nào khác.</small>
              </div>
            `;
          }
        }

        const adminBox = document.getElementById('revModalAdminBox');
        const adminLink = document.getElementById('revModalAdminLink');
        if (adminBox && adminLink) {
          if (data.is_admin && data.admin_customer_url) {
            adminLink.href = data.admin_customer_url;
            adminBox.classList.remove('d-none');
          } else {
            adminBox.classList.add('d-none');
          }
        }

        const modalEl = document.getElementById('customerReviewerProfileModal');
        if (modalEl) {
          const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
          modal.show();
        }
      })
      .catch(err => console.error('Error loading reviewer profile:', err));
  }

  function previewReviewPhotos(input, previewContainerId) {
    const container = document.getElementById(previewContainerId);
    if (!container) return;
    container.innerHTML = '';

    if (input.files && input.files.length > 0) {
      const maxFiles = Math.min(input.files.length, 5);
      const countEl = document.getElementById('reviewImgCount');
      if (countEl) countEl.textContent = `Đã chọn ${maxFiles}/5 ảnh`;

      for (let i = 0; i < maxFiles; i++) {
        const file = input.files[i];
        const reader = new FileReader();
        reader.onload = function (e) {
          const wrapper = document.createElement('div');
          wrapper.className = 'position-relative animate-scale';
          wrapper.innerHTML = `
            <img src="${e.target.result}" class="rounded border shadow-sm" style="width: 58px; height: 58px; object-fit: cover;">
            <span class="badge bg-danger position-absolute top-0 end-0 p-0.5 rounded-circle" style="transform: translate(30%, -30%); cursor: pointer;" onclick="this.parentElement.remove();">
              <i class="fa-solid fa-xmark" style="font-size: 0.6rem;"></i>
            </span>
          `;
          container.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
      }
    }
  }

  // ========================================================
  // STICKY FLOATING BOTTOM BAR
  // ========================================================
  const stickyBar = document.getElementById('stickyAddToCartBar');

  function updateStickyBarVariantInfo() {
    const variantBadge = document.getElementById('stickySelectedVariantText');
    const subtotalEl = document.getElementById('stickySubtotalText');
    const qtyInput = document.getElementById('productQty');
    const qty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;

    if (variantBadge) {
      if (selectedProductColor && selectedProductSize) {
        if (currentVariantStock <= 0) {
          variantBadge.textContent = `${selectedProductColor} / Size ${selectedProductSize} (HẾT HÀNG)`;
          variantBadge.className = 'badge bg-danger text-white px-2 py-0.5 fw-bold';
        } else {
          variantBadge.textContent = `${selectedProductColor} / Size ${selectedProductSize} (Kho: ${currentVariantStock})`;
          variantBadge.className = 'badge bg-dark text-warning border border-warning px-2 py-0.5 fw-bold';
        }
      } else if (selectedProductColor) {
        variantBadge.textContent = `${selectedProductColor} / Chưa chọn size`;
        variantBadge.className = 'badge bg-light text-warning border border-warning px-2 py-0.5';
      } else if (selectedProductSize) {
        variantBadge.textContent = `Chưa chọn màu / Size ${selectedProductSize}`;
        variantBadge.className = 'badge bg-light text-warning border border-warning px-2 py-0.5';
      } else {
        variantBadge.textContent = 'Chưa chọn màu & size';
        variantBadge.className = 'badge bg-light text-muted border px-2 py-0.5';
      }
    }

    if (subtotalEl) {
      const unitPrice = currentProductUnitPrice || BASE_PRICE;
      const total = unitPrice * qty;
      subtotalEl.textContent = total.toLocaleString('vi-VN') + '₫';
    }
  }

  window.addEventListener('scroll', function () {
    if (!stickyBar) return;
    if (window.scrollY > 480) {
      stickyBar.style.transform = 'translateY(0)';
    } else {
      stickyBar.style.transform = 'translateY(120%)';
    }
  });

  function triggerStickySubmit(isBuyNow) {
    const hasColors = document.querySelectorAll('input[name="color"]').length > 0;
    const hasSizes = document.querySelectorAll('input[name="size"]').length > 0;
    const missingColor = hasColors && !selectedProductColor;
    const missingSize = hasSizes && !selectedProductSize;

    if (missingColor || missingSize) {
      const targetSec = missingColor ? document.getElementById('colorGroupSection') : document.getElementById('sizeGroupSection');
      if (targetSec) {
        targetSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
        targetSec.style.border = '2px solid #ef4444';
        targetSec.style.backgroundColor = '#fef2f2';
        setTimeout(() => {
          targetSec.style.border = '1px solid var(--bee-border)';
          targetSec.style.backgroundColor = '#ffffff';
        }, 2500);
      }

      const alertMsg = (missingColor && missingSize)
        ? 'Quý khách vui lòng chọn Màu sắc và Kích thước (Size) trước khi tiếp tục!'
        : (missingColor ? 'Quý khách vui lòng chọn Màu sắc trước khi tiếp tục!' : 'Quý khách vui lòng chọn Kích thước (Size) trước khi tiếp tục!');

      const alertEl = document.getElementById('productFormAlert');
      if (alertEl) {
        alertEl.classList.remove('d-none');
        document.getElementById('productFormAlertText').textContent = alertMsg;
      }

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Chưa Chọn Đủ Biến Thể',
          text: alertMsg,
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });
      }
      return;
    }

    if (currentVariantStock <= 0) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'error',
          title: 'Tạm Hết Hàng',
          text: `Phiên bản Màu ${selectedProductColor} - Size ${selectedProductSize} hiện đã hết hàng trong kho!`,
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });
      }
      return;
    }

    const form = document.getElementById('productForm');
    if (form) {
      if (isBuyNow) {
        let buyNowInput = form.querySelector('input[name="buy_now"]');
        if (!buyNowInput) {
          buyNowInput = document.createElement('input');
          buyNowInput.type = 'hidden';
          buyNowInput.name = 'buy_now';
          form.appendChild(buyNowInput);
        }
        buyNowInput.value = '1';
      }
      form.submit();
    }
  }

  // ========================================================
  // REALTIME VIEWERS & LIVE PURCHASE TOAST
  // ========================================================
  setInterval(() => {
    const viewerEl = document.getElementById('liveViewerCount');
    if (viewerEl) {
      const current = parseInt(viewerEl.textContent) || 19;
      const delta = (Math.random() > 0.5 ? 1 : -1) * Math.floor(Math.random() * 3 + 1);
      let next = current + delta;
      if (next < 12) next = 15;
      if (next > 34) next = 24;
      viewerEl.textContent = next;
    }
  }, 8000);

  const sampleBuyers = [
    { name: 'Anh Minh Tuấn', loc: 'Đống Đa, Hà Nội', time: '1 phút trước', variant: 'Size L / Đen' },
    { name: 'Anh Hoàng Nam', loc: 'Quận 1, TP.HCM', time: '3 phút trước', variant: 'Size XL / Trắng' },
    { name: 'Anh Đức Hải', loc: 'Hải Châu, Đà Nẵng', time: '6 phút trước', variant: 'Size M / Xanh Navy' },
    { name: 'Anh Quốc Bảo', loc: 'Cầu Giấy, Hà Nội', time: '9 phút trước', variant: 'Size L / Xám Ghi' },
    { name: 'Anh Việt Hưng', loc: 'Thủ Đức, TP.HCM', time: '12 phút trước', variant: 'Size XXL / Đen' },
  ];

  function triggerLivePurchaseToast() {
    const randomBuyer = sampleBuyers[Math.floor(Math.random() * sampleBuyers.length)];
    let toastContainer = document.getElementById('beeLivePurchaseToast');
    
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.id = 'beeLivePurchaseToast';
      toastContainer.style.position = 'fixed';
      toastContainer.style.bottom = '24px';
      toastContainer.style.left = '24px';
      toastContainer.style.zIndex = '1070';
      toastContainer.style.maxWidth = '360px';
      toastContainer.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
      toastContainer.style.transform = 'translateY(120px)';
      toastContainer.style.opacity = '0';
      document.body.appendChild(toastContainer);
    }

    toastContainer.innerHTML = `
      <div class="card border-0 shadow-lg p-2.5 rounded-4 bg-white border border-warning-subtle d-flex flex-row align-items-center gap-2.5">
        <div class="position-relative flex-shrink-0">
          <img src="{{ asset($product->image) }}" class="rounded-3 border bg-light" style="width: 52px; height: 52px; object-fit: contain;">
          <span class="position-absolute top-0 start-0 badge bg-danger rounded-circle p-1" style="transform: translate(-30%, -30%);">
            <i class="fa-solid fa-bolt" style="font-size: 8px;"></i>
          </span>
        </div>
        <div class="flex-grow-1 min-w-0">
          <div class="d-flex justify-content-between align-items-center mb-0.5">
            <strong class="text-dark small text-truncate" style="font-size: 0.82rem;">${randomBuyer.name}</strong>
            <small class="text-muted" style="font-size: 0.7rem;">${randomBuyer.time}</small>
          </div>
          <p class="mb-0 text-muted small text-truncate" style="font-size: 0.74rem;">
            Vừa đặt mua <strong class="text-dark">{{ $product->name }}</strong>
          </p>
          <div class="d-flex align-items-center gap-1.5 mt-0.5" style="font-size: 0.68rem;">
            <span class="text-success fw-bold"><i class="fa-solid fa-circle-check"></i> Đã xác nhận</span>
            <span class="text-muted">• ${randomBuyer.loc}</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-sm p-1 align-self-start" onclick="this.closest('#beeLivePurchaseToast').style.opacity='0'; this.closest('#beeLivePurchaseToast').style.transform='translateY(120px)'" style="font-size: 0.65rem;"></button>
      </div>
    `;

    setTimeout(() => {
      toastContainer.style.opacity = '1';
      toastContainer.style.transform = 'translateY(0)';
    }, 100);

    setTimeout(() => {
      toastContainer.style.opacity = '0';
      toastContainer.style.transform = 'translateY(120px)';
    }, 5500);
  }

  // Tự động kích hoạt toast mua hàng sau 4 giây và lặp lại
  setTimeout(triggerLivePurchaseToast, 4000);
  setInterval(triggerLivePurchaseToast, 18000);

  // ========================================================
  // INITIALIZATION ON DOM READY
  // ========================================================
  document.addEventListener("DOMContentLoaded", function () {
    setupMainImageZoom();
    calculateTabRecommendedSize();
    calculateModalSmartFitSize();

    // Tự động chọn Màu và Size đầu tiên nếu có
    const firstColor = document.querySelector('input[name="color"]');
    if (firstColor) {
      firstColor.checked = true;
      selectProductColor(firstColor.value);
    }
    const firstSize = document.querySelector('input[name="size"]');
    if (firstSize) {
      firstSize.checked = true;
      selectProductSize(firstSize.value, getShowSizeHint(firstSize.value));
    }

    // Scroll to reviews tab if hash is #reviews
    if (window.location.hash === '#reviews' || window.location.search.includes('review=1')) {
      const reviewTabBtn = document.getElementById('reviews-tab');
      if (reviewTabBtn) {
        const tabTrigger = new bootstrap.Tab(reviewTabBtn);
        tabTrigger.show();
        setTimeout(() => {
          const reviewSection = document.getElementById('reviews');
          if (reviewSection) {
            reviewSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        }, 300);
      }
    }

    // AJAX Review Form
    const pageReviewForm = document.getElementById('productPageReviewForm');
    if (pageReviewForm) {
      pageReviewForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const ratingInput = pageReviewForm.querySelector('input[name="rating"]:checked');
        const commentInput = document.getElementById('productPageCommentInput');
        const submitBtn = document.getElementById('productReviewSubmitBtn');
        const alertBox = document.getElementById('productReviewAlertBox');

        if (!ratingInput || !commentInput || !commentInput.value.trim()) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang gửi...';

        const formData = new FormData(pageReviewForm);

        fetch(pageReviewForm.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> CẬP NHẬT ĐÁNH GIÁ';

          if (data.success) {
            if (alertBox) {
              alertBox.className = 'alert alert-success border-0 py-2.5 px-3 rounded-3 mb-3 small';
              alertBox.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> ${data.message}`;
              alertBox.classList.remove('d-none');
            }

            const pTitle = document.getElementById('productReviewFormTitle');
            if (pTitle) pTitle.textContent = 'Cập Nhật Đánh Giá & Hình Ảnh';
            const pBtn = document.getElementById('productReviewBtnText');
            if (pBtn) pBtn.textContent = 'CẬP NHẬT ĐÁNH GIÁ';

            if (data.product_reviews_count) {
              const headerCount = document.getElementById('productPageReviewsCountHeader');
              if (headerCount) headerCount.textContent = `(${data.product_reviews_count})`;
            }

            alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          } else {
            alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
            alertBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> ${data.message || 'Có lỗi xảy ra khi gửi nhận xét'}`;
            alertBox.classList.remove('d-none');
          }
        })
        .catch(err => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> GỬI ĐÁNH GIÁ';
          alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
          alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> Có lỗi xảy ra khi gửi nhận xét.';
          alertBox.classList.remove('d-none');
        });
      });
    }
  });
</script>
@endpush
