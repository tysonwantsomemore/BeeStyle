@extends('layouts.client')

@section('title', $category->name . ' - Danh Mục Sản Phẩm | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('client.categories.index') }}" class="text-decoration-none text-muted">Danh mục</a></li>
      @if($category->parent)
        <li class="breadcrumb-item"><a href="{{ route('client.categories.show', $category->parent->slug) }}" class="text-decoration-none text-muted">{{ $category->parent->name }}</a></li>
      @endif
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $category->name }}</li>
    </ol>
  </nav>

  <!-- CATEGORY HERO BANNER -->
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
            <i class="fa-solid fa-palette me-1 text-warning"></i> Màu Sắc
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
            <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 14px; overflow: hidden; background: #ffffff;">
              <div class="position-relative bg-light p-3 text-center" style="height: 220px; display: flex; align-items: center; justify-content: center;">
                @if($product->discount_percent > 0)
                  <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill">-{{ $product->discount_percent }}%</span>
                @endif
                @if($product->is_featured)
                  <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark rounded-pill fw-bold"><i class="fa-solid fa-fire me-1"></i> HOT</span>
                @endif
                <a href="{{ route('client.products.show', $product->id) }}">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 190px; object-fit: contain;">
                </a>
              </div>
              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-warning fw-bold">{{ $product->brand->name ?? 'BeeStyle' }}</small>
                    @if($product->rating > 0)
                      <small class="text-muted" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-star text-warning"></i> {{ number_format($product->rating, 1) }} ({{ $product->reviews_count ?? $product->reviews->count() }})
                      </small>
                    @endif
                  </div>
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
                      <small class="text-muted text-decoration-line-through" style="font-size: 0.78rem;">{{ number_format($product->original_price, 0, ',', '.') }}₫</small>
                    @endif
                  </div>
                  <a href="{{ route('client.products.show', $product->id) }}" class="btn btn-outline-warning text-dark btn-sm w-100 fw-bold rounded-2">
                    Xem Chi Tiết
                  </a>
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
