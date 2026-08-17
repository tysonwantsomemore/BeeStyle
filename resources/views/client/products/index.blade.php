@extends('layouts.client')

@section('title', 'Cửa Hàng Thời Trang | BeeStyle Premium')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Danh sách sản phẩm</li>
      @if($categorySlug)
        <li class="breadcrumb-item active text-warning fw-semibold">{{ $categorySlug }}</li>
      @endif
    </ol>
  </nav>

  <div class="row g-4">
    <!-- FILTER SIDEBAR -->
    <div class="col-lg-3">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 14px; position: sticky; top: 100px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-sliders me-2 text-warning"></i> Bộ Lọc</h5>
          @if($categorySlug || request('q') || request('sort'))
            <a href="{{ route('client.products.index') }}" class="small text-danger text-decoration-none">Xóa lọc</a>
          @endif
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- CATEGORIES FILTER -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">Danh Mục</h6>
          <div class="d-flex flex-column gap-2">
            <a href="{{ route('client.products.index') }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ empty($categorySlug) ? 'fw-bold text-warning' : 'text-muted' }}">
              <span>Tất cả sản phẩm</span>
              <span class="badge bg-light text-dark rounded-pill">{{ count($products) }}</span>
            </a>
            @foreach($categories as $cat)
              <a href="{{ route('client.products.index', ['category' => $cat['slug']]) }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ $categorySlug === $cat['slug'] ? 'fw-bold text-warning' : 'text-muted' }}">
                <span>{{ $cat['name'] }}</span>
                <span class="badge bg-light text-dark rounded-pill">{{ $cat['item_count'] }}</span>
              </a>
            @endforeach
          </div>
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- PRICE RANGE FILTER -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">Khoảng Giá</h6>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="price1">
            <label class="form-check-label small text-muted" for="price1">Dưới 500.000₫</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="price2">
            <label class="form-check-label small text-muted" for="price2">500.000₫ - 1.000.000₫</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="price3">
            <label class="form-check-label small text-muted" for="price3">Trên 1.000.000₫</label>
          </div>
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- SIZE FILTER -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">Kích Thước</h6>
          <div class="d-flex flex-wrap gap-2">
            @foreach(['S', 'M', 'L', 'XL', 'XXL', '39', '40', '41', '42'] as $size)
              <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1 fw-semibold" style="border-radius: 8px;">{{ $size }}</button>
            @endforeach
          </div>
        </div>

        <!-- BANNER PROMO SIDEBAR -->
        <div class="card border-0 p-3 text-white text-center" style="background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 12px;">
          <small class="text-warning fw-bold">ƯU ĐÃI VIP</small>
          <h6 class="fw-bold text-white my-1">Giảm 50k Đơn Đầu Tiên</h6>
          <p class="small text-white-50 mb-2">Nhập mã: <strong>BEESTYLE50</strong></p>
        </div>
      </div>
    </div>

    <!-- PRODUCTS GRID -->
    <div class="col-lg-9">
      <!-- TOP TOOLBAR -->
      <div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: 14px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <span class="text-muted small">Hiển thị <strong>{{ count($products) }}</strong> sản phẩm</span>
            @if(request('q'))
              <span class="badge bg-warning-subtle text-dark ms-2">Từ khóa: "{{ request('q') }}"</span>
            @endif
          </div>

          <div class="d-flex align-items-center gap-2">
            <label class="small text-muted mb-0 text-nowrap">Sắp xếp theo:</label>
            <form action="{{ route('client.products.index') }}" method="GET" class="d-inline">
              @if($categorySlug)
                <input type="hidden" name="category" value="{{ $categorySlug }}">
              @endif
              @if(request('q'))
                <input type="hidden" name="q" value="{{ request('q') }}">
              @endif
              <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 170px; border-radius: 8px;">
                <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
              </select>
            </form>
          </div>
        </div>
      </div>

      <!-- PRODUCTS LIST -->
      @if(count($products) > 0)
        <div class="row g-4">
          @foreach($products as $product)
            <div class="col-lg-4 col-md-6 col-6">
              <div class="bee-product-card">
                @if($product['is_new'])
                  <span class="bee-product-badge new">MỚI</span>
                @elseif($product['discount'] > 0)
                  <span class="bee-product-badge sale">-{{ $product['discount'] }}%</span>
                @endif
                
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

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center mt-5">
          <nav>
            <ul class="pagination">
              <li class="page-item disabled"><a class="page-link" href="#"><i class="fa-solid fa-chevron-left"></i></a></li>
              <li class="page-item active"><a class="page-link bg-warning border-warning text-dark fw-bold" href="#">1</a></li>
              <li class="page-item"><a class="page-link text-dark" href="#">2</a></li>
              <li class="page-item"><a class="page-link text-dark" href="#">3</a></li>
              <li class="page-item"><a class="page-link text-dark" href="#"><i class="fa-solid fa-chevron-right"></i></a></li>
            </ul>
          </nav>
        </div>
      @else
        <div class="text-center py-5">
          <i class="fa-solid fa-magnifying-glass fs-1 text-muted mb-3"></i>
          <h5 class="fw-bold text-dark">Không tìm thấy sản phẩm phù hợp</h5>
          <p class="text-muted small">Hãy thử tìm kiếm với từ khóa khác hoặc xóa bộ lọc hiện tại.</p>
          <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary btn-sm">Xem tất cả sản phẩm</a>
        </div>
      @endif

    </div>
  </div>
</div>
@endsection
