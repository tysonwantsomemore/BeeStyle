@extends('layouts.client')

@section('title', $category->name . ' - Danh Mục Sản Phẩm | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('client.products.index') }}" class="text-decoration-none text-muted">Sản phẩm</a></li>
      @if($category->parent)
        <li class="breadcrumb-item"><a href="{{ route('client.categories.show', $category->parent->slug) }}" class="text-decoration-none text-muted">{{ $category->parent->name }}</a></li>
      @endif
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $category->name }}</li>
    </ol  <!-- CATEGORY HERO BANNER (DYNAMICS BY CATEGORY SLUG) -->
  @if($category->slug === 'ao-polo-nam')
    <div class="card border-0 shadow-sm mb-4 text-white overflow-hidden" style="border-radius: 18px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);">
      <div class="card-body p-4 p-md-5 position-relative">
        <div class="position-absolute end-0 top-0 bottom-0 d-none d-md-flex align-items-center opacity-10 pe-4" style="font-size: 8rem; pointer-events: none;">
          <i class="fa-solid fa-shirt"></i>
        </div>
        <div class="position-relative" style="z-index: 2; max-width: 680px;">
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill shadow-xs">
              <i class="fa-solid fa-crown me-1"></i> BST POLO SIGNATURE 2026
            </span>
            <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill small">
              {{ $products->total() }} sản phẩm
            </span>
          </div>
          <h2 class="fw-bold text-white mb-2" style="font-family: var(--atino-font-heading); letter-spacing: -0.3px;">
            ÁO POLO NAM CAO CẤP • CHUẨN FORM QUÝ ÔNG
          </h2>
          <p class="text-white-50 small mb-3" style="font-size: 0.88rem; line-height: 1.6;">
            100% Sợi Cotton dệt tổ ong kháng khuẩn độc quyền • Co giãn 4 chiều tự nhiên • Đứng form, thoáng khí suốt ngày dài làm việc và sự kiện.
          </p>
          <div class="d-flex flex-wrap gap-2 gap-md-3 pt-2 border-top border-secondary-subtle small text-warning" style="font-size: 0.8rem;">
            <span><i class="fa-solid fa-circle-check me-1"></i> Chuẩn Form Quý Ông</span>
            <span><i class="fa-solid fa-shield-halved me-1"></i> Kháng khuẩn 99%</span>
            <span><i class="fa-solid fa-rotate-left me-1"></i> Đổi size 30 ngày</span>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="card border-0 text-white overflow-hidden mb-4 shadow-sm" style="border-radius: 18px; background: linear-gradient(135deg, #111827 0%, #1f2937 60%, #374151 100%);">
      <div class="card-body p-4 p-md-5">
        <div class="d-flex align-items-center gap-4 flex-wrap">
          <div class="bg-white rounded-circle p-3 shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 80px; height: 80px;">
            <i class="{{ $category->icon ?? 'fa-solid fa-shirt' }} text-warning fs-2"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
              <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">
                DANH MỤC THỜI TRANG
              </span>
              <span class="badge bg-white bg-opacity-20 text-white px-3 py-1 rounded-pill small">
                {{ $products->total() }} sản phẩm
              </span>
            </div>
            <h2 class="fw-bold text-white mb-2">{{ $category->name }}</h2>
            <p class="text-light-subtle small mb-0" style="max-width: 750px; line-height: 1.6;">
              {{ $category->description ?? 'Tuyển chọn các thiết kế thời trang chất lượng, chuẩn form dáng, mang phong cách trẻ trung và lịch lãm.' }}
            </p>
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="row g-4">
    <!-- SIDEBAR FILTER -->
    <div class="col-lg-3">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 14px; position: sticky; top: 90px; background: #ffffff;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0 fs-6">
            <i class="fa-solid fa-sliders me-2 text-warning"></i> Bộ Lọc Tìm Kiếm
          </h5>
          @if($brandSlug || request('q') || request('sort') || request('price_range') || request('size') || request('color'))
            <a href="{{ route('client.categories.show', $category->slug) }}" class="small text-danger text-decoration-none fw-semibold">
              <i class="fa-solid fa-xmark me-1"></i> Xóa lọc
            </a>
          @endif
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- 1. CATEGORIES QUICK LIST -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">
            <i class="fa-solid fa-layer-group me-1 text-warning"></i> Tất Cả Danh Mục
          </h6>
          <div class="d-flex flex-column gap-2 small">
            @foreach($allCategories as $cat)
              <a href="{{ route('client.categories.show', $cat->slug) }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ $category->id === $cat->id ? 'fw-bold text-warning' : 'text-muted' }} py-1 hover-warning">
                <span>
                  <i class="{{ $cat->icon ?? 'fa-solid fa-shirt' }} me-1.5 {{ $category->id === $cat->id ? 'text-warning' : 'text-secondary' }}"></i>
                  {{ $cat->name }}
                </span>
                <span class="badge {{ $category->id === $cat->id ? 'bg-warning text-dark' : 'bg-light text-dark' }} rounded-pill">
                  {{ $cat->products_count }}
                </span>
              </a>
            @endforeach
          </div>
        </div>

        @if($brands && $brands->count() > 0)
          <hr class="my-2 border-secondary-subtle">
          <!-- 2. BRANDS FILTER -->
          <div class="mb-4">
            <h6 class="fw-bold text-dark small text-uppercase mb-3">
              <i class="fa-solid fa-crown me-1 text-warning"></i> Thương Hiệu
            </h6>
            <div class="d-flex flex-column gap-2 small">
              @foreach($brands as $b)
                <a href="{{ route('client.categories.show', array_merge(['slug' => $category->slug], request()->except(['brand', 'page']), ['brand' => (request('brand') === $b->slug ? null : $b->slug)])) }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ request('brand') === $b->slug ? 'text-warning fw-bold' : 'text-muted' }}">
                  <span>
                    <i class="fa-regular {{ request('brand') === $b->slug ? 'fa-square-check text-warning' : 'fa-square text-secondary' }} me-1"></i>
                    {{ $b->name }}
                  </span>
                  <span class="badge bg-light text-dark rounded-pill">{{ $b->products_count }}</span>
                </a>
              @endforeach
            </div>
          </div>
        @endif

        <hr class="my-2 border-secondary-subtle">

        <!-- 3. PRICE RANGE -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">
            <i class="fa-solid fa-tag me-1 text-warning"></i> Khoảng Giá (VNĐ)
          </h6>
          <div class="d-flex flex-column gap-2 small">
            <a href="{{ route('client.categories.show', array_merge(['slug' => $category->slug], request()->except(['price_range', 'page']), ['price_range' => (request('price_range') === 'under_500' ? null : 'under_500')])) }}" class="text-decoration-none {{ request('price_range') === 'under_500' ? 'text-warning fw-bold' : 'text-muted' }}">
              <i class="fa-regular {{ request('price_range') === 'under_500' ? 'fa-circle-dot text-warning' : 'fa-circle text-secondary' }} me-1"></i> Dưới 500.000₫
            </a>
            <a href="{{ route('client.categories.show', array_merge(['slug' => $category->slug], request()->except(['price_range', 'page']), ['price_range' => (request('price_range') === '500_1000' ? null : '500_1000')])) }}" class="text-decoration-none {{ request('price_range') === '500_1000' ? 'text-warning fw-bold' : 'text-muted' }}">
              <i class="fa-regular {{ request('price_range') === '500_1000' ? 'fa-circle-dot text-warning' : 'fa-circle text-secondary' }} me-1"></i> 500.000₫ - 1.000.000₫
            </a>
            <a href="{{ route('client.categories.show', array_merge(['slug' => $category->slug], request()->except(['price_range', 'page']), ['price_range' => (request('price_range') === 'over_1000' ? null : 'over_1000')])) }}" class="text-decoration-none {{ request('price_range') === 'over_1000' ? 'text-warning fw-bold' : 'text-muted' }}">
              <i class="fa-regular {{ request('price_range') === 'over_1000' ? 'fa-circle-dot text-warning' : 'fa-circle text-secondary' }} me-1"></i> Trên 1.000.000₫
            </a>
          </div>
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- 4. SIZE -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">
            <i class="fa-solid fa-ruler-combined me-1 text-warning"></i> Kích Thước (Size)
          </h6>
          <div class="d-flex flex-wrap gap-2">
            @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL'] as $size)
              <a href="{{ route('client.categories.show', array_merge(['slug' => $category->slug], request()->except(['size', 'page']), ['size' => (request('size') === $size ? null : $size)])) }}" class="btn btn-sm {{ request('size') === $size ? 'btn-bee-primary text-white fw-bold' : 'btn-outline-secondary' }} px-3 py-1 fw-semibold rounded-2" style="font-size: 0.8rem;">
                {{ $size }}
              </a>
            @endforeach
          </div>
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- 5. COLOR -->
        <div class="mb-2">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">
            <i class="fa-solid fa-palette me-1 text-warning"></i> Gam Màu Phổ Biến
          </h6>
          <div class="d-flex flex-wrap gap-1.5">
            @foreach(['Đen', 'Trắng', 'Xanh Navy', 'Xám', 'Xanh Rêu', 'Beige'] as $col)
              <a href="{{ route('client.categories.show', array_merge(['slug' => $category->slug], request()->except(['color', 'page']), ['color' => (request('color') === $col ? null : $col)])) }}" class="badge {{ request('color') === $col ? 'bg-dark text-warning border-warning' : 'bg-light text-dark border' }} py-1.5 px-2 text-decoration-none fw-semibold rounded-pill" style="font-size: 0.75rem;">
                <i class="fa-solid fa-circle me-1 {{ $col === 'Trắng' ? 'text-secondary' : ($col === 'Đen' ? 'text-dark' : ($col === 'Xanh Navy' ? 'text-primary' : 'text-warning')) }}"></i>
                {{ $col }}
              </a>
            @endforeach
          </div>
        </div>

      </div>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="col-lg-9">
      <!-- HEADER TOOLBAR -->
      <div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: 14px; background: #ffffff;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <span class="text-muted small">
            Hiển thị <strong>{{ $products->count() }}</strong> trên tổng <strong>{{ $products->total() }}</strong> sản phẩm thuộc danh mục <strong>{{ $category->name }}</strong>
          </span>
          <div class="d-flex align-items-center gap-2">
            <label class="small text-muted text-nowrap">Sắp xếp:</label>
            <select class="form-select form-select-sm" style="width: 175px;" onchange="location = this.value;">
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular', 'page' => null]) }}" {{ request('sort', 'popular') === 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'views_desc', 'page' => null]) }}" {{ request('sort') === 'views_desc' ? 'selected' : '' }}>Xem nhiều nhất</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest', 'page' => null]) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc', 'page' => null]) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc', 'page' => null]) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating', 'page' => null]) }}" {{ request('sort') === 'rating' ? 'selected' : '' }}>Đánh giá cao nhất</option>
            </select>
          </div>
        </div>
      </div>

      <!-- PRODUCTS -->
      <div class="row g-3">
        @forelse($products as $product)
          <div class="col-6 col-md-4">
            <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 16px; overflow: hidden; background: #ffffff; border: 1px solid rgba(0,0,0,0.05) !important;">
              <div class="position-relative bg-light p-3 text-center" style="height: 230px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if($product->discount_percent > 0)
                  <span class="position-absolute top-0 start-0 m-2.5 badge bg-danger rounded-pill shadow-xs" style="font-size: 0.72rem; z-index: 2;">-{{ $product->discount_percent }}%</span>
                @endif
                @if($product->is_featured)
                  <span class="position-absolute top-0 start-0 m-2.5 badge bg-warning text-dark rounded-pill fw-bold shadow-xs" style="font-size: 0.7rem; z-index: 2; {{ $product->discount_percent > 0 ? 'margin-top: 32px !important;' : '' }}"><i class="fa-solid fa-fire me-1"></i> HOT</span>
                @endif

                <!-- NÚT TRÁI TIM YÊU THÍCH -->
                <button type="button" class="btn btn-sm btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active' : '' }} position-absolute top-0 end-0 m-2.5 rounded-circle shadow-xs" 
                  onclick="toggleWishlist({{ $product->id }}, this)" 
                  title="Yêu thích sản phẩm" style="width: 32px; height: 32px; z-index: 4; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(0,0,0,0.08); background: #ffffff;">
                  <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart text-dark' }} fs-6"></i>
                </button>

                <a href="{{ route('client.products.show', $product->id) }}" class="d-block w-100 h-100 d-flex align-items-center justify-content-center">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid transition-all" style="max-height: 195px; object-fit: contain;">
                </a>
              </div>

              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-warning fw-bold text-uppercase" style="font-size: 0.75rem;">{{ $product->brand->name ?? 'BeeStyle' }}</small>
                    
                    <!-- PREVIEW CHẤM MÀU SẮC -->
                    <div class="d-flex align-items-center gap-1">
                      @foreach(array_slice($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy'], 0, 4) as $cDot)
                        @php
                          $cDotLower = strtolower($cDot);
                          $dotBg = '#1e293b';
                          if (str_contains($cDotLower, 'trắng')) $dotBg = '#ffffff';
                          elseif (str_contains($cDotLower, 'navy') || str_contains($cDotLower, 'than')) $dotBg = '#1e3a8a';
                          elseif (str_contains($cDotLower, 'xám') || str_contains($cDotLower, 'ghi')) $dotBg = '#64748b';
                          elseif (str_contains($cDotLower, 'đỏ')) $dotBg = '#881337';
                          elseif (str_contains($cDotLower, 'be') || str_contains($cDotLower, 'khaki')) $dotBg = '#d4b996';
                          elseif (str_contains($cDotLower, 'rêu')) $dotBg = '#365314';
                        @endphp
                        <span class="rounded-circle d-inline-block border" style="width: 10px; height: 10px; background-color: {{ $dotBg }};" title="{{ $cDot }}"></span>
                      @endforeach
                    </div>
                  </div>

                  <h6 class="fw-bold text-dark text-truncate-2 mb-2" style="font-size: 0.92rem; min-height: 42px; line-height: 1.35;">
                    <a href="{{ route('client.products.show', $product->id) }}" class="text-decoration-none text-dark hover-warning">
                      {{ $product->name }}
                    </a>
                  </h6>

                  <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="text-warning small" style="font-size: 0.72rem;">
                      <i class="fa-solid fa-star text-warning"></i> {{ number_format($product->rating, 1) }}
                    </div>
                    <span class="text-muted small" style="font-size: 0.72rem;">• Đã bán {{ number_format($product->sold_count ?? 120) }}</span>
                  </div>
                </div>

                <div>
                  <div class="d-flex align-items-baseline gap-2 mb-2.5">
                    <strong class="text-danger fw-bold fs-6">{{ number_format($product->price, 0, ',', '.') }}₫</strong>
                    @if($product->original_price && $product->original_price > $product->price)
                      <small class="text-muted text-decoration-line-through" style="font-size: 0.8rem;">{{ number_format($product->original_price, 0, ',', '.') }}₫</small>
                    @endif
                  </div>

                  <!-- 2 NÚT THAO TÁC: THÊM VÀO GIỎ HÀNG & MUA NGAY -->
                  <div class="d-flex gap-1.5 mt-2">
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap shadow-xs" 
                      data-id="{{ $product->id }}"
                      onclick="openQuickVariantModal({{ $product->id }}, false, this)" 
                      title="Chọn màu & size thêm vào giỏ hàng" style="font-size: 0.76rem;">
                      <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Vào Giỏ Hàng
                    </button>
                    <button type="button" class="btn btn-bee-primary text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap shadow-xs" 
                      data-id="{{ $product->id }}"
                      onclick="openQuickVariantModal({{ $product->id }}, true, this)" 
                      title="Mua ngay chuyển sang thanh toán" style="font-size: 0.76rem;">
                      <i class="fa-solid fa-bolt me-1 text-dark"></i> Mua Ngay
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12 text-center py-5">
            <i class="fa-solid fa-shirt fs-1 text-muted mb-3"></i>
            <p class="text-muted">Chưa có sản phẩm nào phù hợp với bộ lọc trong danh mục này.</p>
            <a href="{{ route('client.categories.show', $category->slug) }}" class="btn btn-bee-primary btn-sm">Xóa Bộ Lọc</a>
          </div>
        @endforelse
      </div>

      <!-- PAGINATION -->
      <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection
