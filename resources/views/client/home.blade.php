@extends('layouts.client')

@section('title', 'BeeStyle - Thương Hiệu Thời Trang Đẳng Cấp & Thanh Lịch')

@section('content')
<div class="container py-4">

  <!-- HERO BANNER -->
  <div class="bee-hero-section">
    <div class="row align-items-center">
      <div class="col-lg-7 text-start">
        <span class="bee-hero-badge">
          <i class="fa-solid fa-sparkles"></i> BST Thu Đông 2026 Mới Ra Mắt
        </span>
        <h1 class="display-5 fw-bold text-white mb-3" style="line-height: 1.2;">
          Phong Cách Đẳng Cấp &amp; <br>
          <span class="text-warning">Thanh Lịch Cho Người Hiện Đại</span>
        </h1>
        <p class="text-white-50 lead mb-4 pe-lg-5 fs-6">
          Khám phá bộ sưu tập thời trang thiết kế độc quyền từ BeeStyle. Từng đường kim mũi chỉ được hoàn thiện tinh xảo, tôn vinh khí chất của bạn trong mọi khoảnh khắc.
        </p>
        <div class="d-flex flex-wrap gap-3">
          <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary px-4 py-3 fs-6">
            <i class="fa-solid fa-bag-shopping me-1"></i> Khám Phá Ngay
          </a>
          <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer']) }}" class="btn btn-outline-light px-4 py-3 fs-6 rounded-2">
            Xem Bộ Sưu Tập Blazer
          </a>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block text-center position-relative">
        <img src="{{ asset('assets/img/e-commerce/whooping_banner_product.png') }}" alt="BeeStyle Fashion" class="img-fluid" style="max-height: 380px; filter: drop-shadow(0 20px 30px rgba(0,0,0,0.5));">
      </div>
    </div>
  </div>

  <!-- TRUST BADGES -->
  <div class="bee-trust-bar">
    <div class="row g-4">
      <div class="col-lg-3 col-sm-6">
        <div class="bee-trust-item">
          <div class="bee-trust-icon"><i class="fa-solid fa-truck-fast"></i></div>
          <div>
            <h6 class="fw-bold mb-1">Giao Hàng Toàn Quốc</h6>
            <p class="text-muted small mb-0">Miễn phí cho đơn từ 300k</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="bee-trust-item">
          <div class="bee-trust-icon"><i class="fa-solid fa-arrows-rotate"></i></div>
          <div>
            <h6 class="fw-bold mb-1">Đổi Trả 30 Ngày</h6>
            <p class="text-muted small mb-0">Hỗ trợ đổi size tận nhà</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="bee-trust-item">
          <div class="bee-trust-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <h6 class="fw-bold mb-1">100% Chính Hãng</h6>
            <p class="text-muted small mb-0">Cam kết chất liệu cao cấp</p>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6">
        <div class="bee-trust-item">
          <div class="bee-trust-icon"><i class="fa-solid fa-headset"></i></div>
          <div>
            <h6 class="fw-bold mb-1">Tư Vấn Chuyên Nghiệp</h6>
            <p class="text-muted small mb-0">Hỗ trợ 24/7 tận tâm</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CATEGORIES SHOWCASE -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">Danh Mục Nổi Bật</h2>
        <p class="bee-section-subtitle">Lựa chọn những phong cách thời trang dẫn đầu xu hướng</p>
      </div>
      <a href="{{ route('client.products.index') }}" class="text-warning fw-bold small text-decoration-none">
        Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-3">
      @foreach($categories as $cat)
        <div class="col-lg-2 col-md-4 col-6">
          <a href="{{ route('client.products.index', ['category' => $cat['slug']]) }}" class="bee-category-card">
            <div class="bee-category-icon">
              <i class="{{ $cat['icon'] }}"></i>
            </div>
            <h6 class="fw-bold mb-1 fs-9 text-dark">{{ $cat['name'] }}</h6>
            <small class="text-muted">{{ $cat['item_count'] }}+ sản phẩm</small>
          </a>
        </div>
      @endforeach
    </div>
  </div>

  <!-- FLASH SALE / HOT DEALS -->
  <div class="card border-0 shadow-sm mb-5 p-4" style="background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%); border: 1.5px solid #fef3c7 !important; border-radius: 16px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill"><i class="fa-solid fa-bolt me-1"></i> FLASH SALE</span>
        <h3 class="fw-bold text-dark mb-0 fs-5">Ưu Đãi Trong Ngày</h3>
      </div>
      <div class="d-flex align-items-center gap-2 text-muted small">
        <span>Kết thúc sau:</span>
        <span class="badge bg-dark text-warning p-2 font-monospace fs-6">08</span> :
        <span class="badge bg-dark text-warning p-2 font-monospace fs-6">45</span> :
        <span class="badge bg-dark text-warning p-2 font-monospace fs-6">19</span>
      </div>
    </div>

    <div class="row g-3">
      @foreach(array_slice($products, 0, 4) as $product)
        <div class="col-lg-3 col-md-6">
          <div class="bee-product-card">
            <span class="bee-product-badge sale">-{{ $product['discount'] }}%</span>
            
            <div class="bee-product-actions">
              <a href="{{ route('client.products.show', $product['id']) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
              <a href="{{ route('client.cart') }}" class="btn-action" title="Thêm vào giỏ"><i class="fa-solid fa-cart-plus"></i></a>
            </div>

            <div class="bee-product-img-wrapper">
              <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}">
            </div>

            <div class="bee-product-body">
              <span class="bee-product-category">{{ $product['category'] }}</span>
              <a href="{{ route('client.products.show', $product['id']) }}" class="bee-product-title">
                {{ $product['name'] }}
              </a>

              <div class="bee-product-rating">
                @for($i=1; $i<=5; $i++)
                  <i class="fa-solid fa-star"></i>
                @endfor
                <span class="rating-count">({{ $product['reviews_count'] }})</span>
                <span class="text-muted ms-auto small">Đã bán {{ $product['sold_count'] }}</span>
              </div>

              <div class="bee-product-price-row">
                <span class="bee-product-price">{{ number_format($product['price'], 0, ',', '.') }}₫</span>
                <span class="bee-product-old-price">{{ number_format($product['original_price'], 0, ',', '.') }}₫</span>
              </div>

              <a href="{{ route('client.products.show', $product['id']) }}" class="btn btn-bee-primary btn-sm w-100 mt-3">
                <i class="fa-solid fa-bag-shopping me-1"></i> Mua Ngay
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- FEATURED PRODUCTS / BEST SELLERS -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">Sản Phẩm Bán Chạy</h2>
        <p class="bee-section-subtitle">Những mẫu trang phục được yêu thích nhất mùa này</p>
      </div>
      <a href="{{ route('client.products.index') }}" class="btn btn-bee-outline btn-sm">
        Xem Toàn Bộ Cửa Hàng
      </a>
    </div>

    <div class="row g-4">
      @foreach($products as $product)
        <div class="col-lg-3 col-md-6 col-6">
          <div class="bee-product-card">
            @if($product['is_new'])
              <span class="bee-product-badge new">MỚI</span>
            @elseif($product['discount'] > 0)
              <span class="bee-product-badge sale">-{{ $product['discount'] }}%</span>
            @endif
            
            <div class="bee-product-actions">
              <a href="{{ route('client.products.show', $product['id']) }}" class="btn-action" title="Xem nhanh"><i class="fa-solid fa-eye"></i></a>
              <a href="{{ route('client.cart') }}" class="btn-action" title="Thêm vào giỏ"><i class="fa-solid fa-cart-plus"></i></a>
            </div>

            <div class="bee-product-img-wrapper">
              <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}">
            </div>

            <div class="bee-product-body">
              <span class="bee-product-category">{{ $product['category'] }}</span>
              <a href="{{ route('client.products.show', $product['id']) }}" class="bee-product-title">
                {{ $product['name'] }}
              </a>

              <div class="bee-product-rating">
                <i class="fa-solid fa-star"></i>
                <span class="fw-bold text-dark">{{ $product['rating'] }}</span>
                <span class="rating-count">({{ $product['reviews_count'] }})</span>
                <span class="text-muted ms-auto small d-none d-sm-inline">Đã bán {{ $product['sold_count'] }}</span>
              </div>

              <div class="bee-product-price-row">
                <span class="bee-product-price">{{ number_format($product['price'], 0, ',', '.') }}₫</span>
                @if($product['original_price'] > $product['price'])
                  <span class="bee-product-old-price">{{ number_format($product['original_price'], 0, ',', '.') }}₫</span>
                @endif
              </div>

              <a href="{{ route('client.products.show', $product['id']) }}" class="btn btn-bee-primary btn-sm w-100 mt-3">
                <i class="fa-solid fa-bag-shopping me-1"></i> Xem Chi Tiết
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- MID-PAGE PROMO BANNER -->
  <div class="row g-4 mb-5">
    <div class="col-md-6">
      <div class="card border-0 text-white overflow-hidden p-4 d-flex justify-content-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 16px; min-height: 220px;">
        <span class="badge bg-warning text-dark align-self-start mb-2 px-3 py-1 fw-bold">MEN COLLECTION</span>
        <h3 class="fw-bold text-white mb-2">Thời Trang Quý Ông Lịch Lãm</h3>
        <p class="text-white-50 small mb-3">Polo dệt tổ ong, sơ mi lụa & quần tây cạp êm thoải mái suốt cả ngày.</p>
        <div>
          <a href="{{ route('client.products.index', ['category' => 'thoi-trang-nam']) }}" class="btn btn-warning text-dark fw-bold btn-sm px-3">
            Mua Ngay <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-0 text-white overflow-hidden p-4 d-flex justify-content-center" style="background: linear-gradient(135deg, #7c2d12 0%, #431407 100%); border-radius: 16px; min-height: 220px;">
        <span class="badge bg-danger align-self-start mb-2 px-3 py-1 fw-bold">WOMEN LUXURY</span>
        <h3 class="fw-bold text-white mb-2">Đầm Thiết Kế Sang Trọng</h3>
        <p class="text-white-50 small mb-3">Tôn vinh vẻ đẹp kiêu sa, quyến rũ trong từng sự kiện và buổi tiệc.</p>
        <div>
          <a href="{{ route('client.products.index', ['category' => 'thoi-trang-nu']) }}" class="btn btn-light text-dark fw-bold btn-sm px-3">
            Khám Phá <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- COUPON VOUCHERS -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h2 class="bee-section-title">Mã Giảm Giá Đang Có Sẵn</h2>
        <p class="bee-section-subtitle">Thu thập voucher để nhận ưu đãi thanh toán tốt nhất</p>
      </div>
    </div>

    <div class="row g-3">
      @foreach($coupons as $coupon)
        <div class="col-lg-4 col-md-6">
          <div class="card border-0 shadow-sm p-3 h-100" style="background: #ffffff; border-left: 4px solid #f59e0b !important; border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <span class="badge bg-warning-subtle text-dark fw-bold font-monospace px-2 py-1">{{ $coupon['code'] }}</span>
                <h6 class="fw-bold text-dark mt-2 mb-1">{{ $coupon['title'] }}</h6>
              </div>
              <i class="fa-solid fa-ticket text-warning fs-3"></i>
            </div>
            <p class="text-muted small mb-3">Hạn sử dụng: {{ $coupon['expires_at'] }} • Đã dùng {{ $coupon['used_count'] }}/{{ $coupon['total_limit'] }}</p>
            <div class="mt-auto">
              <a href="{{ route('client.products.index') }}" class="btn btn-sm btn-outline-warning text-dark fw-bold w-100">
                Dùng Mã Ngay
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</div>
@endsection
