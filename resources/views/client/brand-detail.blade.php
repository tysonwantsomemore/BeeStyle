@extends('layouts.client')

@section('title', $brand->name . ' - Bộ Sưu Tập Thương Hiệu | BeeStyle')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('client.brands.index') }}" class="text-decoration-none text-muted">Thương hiệu</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $brand->name }}</li>
    </ol>
  </nav>

  <!-- BRAND HERO BANNER -->
  <div class="card border-0 text-white overflow-hidden mb-4 shadow-sm" style="border-radius: 18px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="card-body p-4 p-md-5">
      <div class="d-flex align-items-center gap-4 flex-wrap">
        <div class="bg-white rounded-circle p-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
          <i class="fa-solid fa-crown text-warning fs-2"></i>
        </div>
        <div>
          <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2">THƯƠNG HIỆU ĐỘC QUYỀN</span>
          <h2 class="fw-bold text-white mb-1">{{ $brand->name }}</h2>
          <p class="text-light-subtle small mb-0" style="max-width: 650px;">{{ $brand->description }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- SIDEBAR CATEGORIES FILTER -->
    <div class="col-lg-3">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 14px; position: sticky; top: 100px;">
        <h6 class="fw-bold text-dark text-uppercase small mb-3">
          <i class="fa-solid fa-list me-2 text-warning"></i> Danh Mục Thuộc Thương Hiệu
        </h6>
        <div class="d-flex flex-column gap-2 small">
          <a href="{{ route('client.brands.show', $brand->slug) }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ empty(request('category')) ? 'fw-bold text-warning' : 'text-muted' }}">
            <span>Tất cả sản phẩm</span>
            <span class="badge bg-light text-dark rounded-pill">{{ $products->total() }}</span>
          </a>
          @foreach($categories as $cat)
            <a href="{{ route('client.brands.show', ['slug' => $brand->slug, 'category' => $cat->slug]) }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ request('category') === $cat->slug ? 'fw-bold text-warning' : 'text-muted' }}">
              <span>{{ $cat->name }}</span>
              <span class="badge bg-light text-dark rounded-pill">{{ $cat->products_count }}</span>
            </a>
          @endforeach
        </div>

        <hr class="my-3 border-secondary-subtle">

        <!-- ALL BRANDS QUICK NAV -->
        <h6 class="fw-bold text-dark text-uppercase small mb-2">Thương Hiệu Khác</h6>
        <a href="{{ route('client.brands.index') }}" class="btn btn-outline-warning text-dark btn-sm w-100 fw-bold">
          Xem Tất Cả Thương Hiệu <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="col-lg-9">
      <!-- HEADER TOOLBAR -->
      <div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: 14px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <span class="text-muted small">
            Hiển thị <strong>{{ $products->count() }}</strong> trên tổng <strong>{{ $products->total() }}</strong> sản phẩm của <strong>{{ $brand->name }}</strong>
          </span>
          <div class="d-flex align-items-center gap-2">
            <label class="small text-muted text-nowrap">Sắp xếp:</label>
            <select class="form-select form-select-sm" style="width: 170px;" onchange="location = this.value;">
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" {{ request('sort') === 'rating' ? 'selected' : '' }}>Đánh giá cao nhất</option>
            </select>
          </div>
        </div>
      </div>

      <!-- PRODUCTS -->
      <div class="row g-3">
        @forelse($products as $product)
          <div class="col-6 col-md-4">
            <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 14px; overflow: hidden;">
              <div class="position-relative bg-light p-3 text-center" style="height: 220px; display: flex; align-items: center; justify-content: center;">
                @if($product->discount_percent > 0)
                  <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill">-{{ $product->discount_percent }}%</span>
                @endif
                <!-- NÚT TRÁI TIM YÊU THÍCH -->
                <button type="button" class="btn btn-sm btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active' : '' }} position-absolute top-0 end-0 m-2.5 rounded-circle shadow-xs" 
                  onclick="toggleWishlist({{ $product->id }}, this)" 
                  title="Yêu thích sản phẩm" style="width: 32px; height: 32px; z-index: 4; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(0,0,0,0.08);">
                  <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart text-dark' }} fs-6"></i>
                </button>

                <a href="{{ route('client.products.show', $product->id) }}">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 190px; object-fit: contain;">
                </a>
              </div>

              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div>
                  <small class="text-warning fw-bold d-block mb-1">{{ $product->category->name ?? 'Thời trang nam' }}</small>
                  <h6 class="fw-bold text-dark text-truncate-2 mb-2" style="font-size: 0.9rem; min-height: 40px;">
                    <a href="{{ route('client.products.show', $product->id) }}" class="text-decoration-none text-dark hover-warning">
                      {{ $product->name }}
                    </a>
                  </h6>
                </div>
                <div>
                  <div class="d-flex align-items-baseline gap-2 mb-2">
                    <strong class="text-danger fw-bold fs-6">{{ number_format($product->price, 0, ',', '.') }}₫</strong>
                    @if($product->original_price && $product->original_price > $product->price)
                      <small class="text-muted text-decoration-line-through">{{ number_format($product->original_price, 0, ',', '.') }}₫</small>
                    @endif
                  </div>
                  <!-- 2 NÚT THÊM VÀO GIỎ HÀNG & MUA HÀNG NGAY (MỞ MODAL CHỌN MÀU & SIZE) -->
                  <div class="d-flex gap-1.5 mt-2">
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="{{ $product->category->name ?? 'Thời trang nam' }}"
                      data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                      data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                      data-stock="{{ $product->stock ?? 999 }}"
                      onclick="openQuickVariantModal({{ $product->id }}, false, this)" 
                      title="Thêm vào giỏ hàng (Chọn màu & size)" style="font-size: 0.78rem;">
                      <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Giỏ
                    </button>
                    <button type="button" class="btn btn-bee-primary btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap" 
                      data-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-price="{{ $product->price }}"
                      data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                      data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                      data-discount="{{ $product->discount_percent ?? 0 }}"
                      data-image="{{ asset($product->image) }}"
                      data-category="{{ $product->category->name ?? 'Thời trang nam' }}"
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
          </div>

        @empty
          <div class="col-12 text-center py-5">
            <p class="text-muted">Chưa có sản phẩm nào thuộc thương hiệu này.</p>
            <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary btn-sm">Xem Tất Cả Sản Phẩm</a>
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
