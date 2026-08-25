@extends('layouts.client')

@section('title', 'Cửa Hàng Thời Trang Nam | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Áo nam cao cấp</li>
      @if($categorySlug)
        @php
          $activeCat = $categories->firstWhere('slug', $categorySlug);
        @endphp
        <li class="breadcrumb-item active text-warning fw-semibold">{{ $activeCat->name ?? $categorySlug }}</li>
      @endif
      @if($brandSlug)
        <li class="breadcrumb-item active text-warning fw-semibold">Thương hiệu: {{ $brandSlug }}</li>
      @endif
    </ol>
  </nav>

  <div class="row g-4">
    <!-- FILTER SIDEBAR -->
    <div class="col-lg-3">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 12px; position: sticky; top: 90px; background: #ffffff;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-sliders me-2 text-warning"></i> Bộ Lọc</h5>
          @if($categorySlug || $brandSlug || request('q') || request('sort') || request('price_range') || request('size') || request('color'))
            <a href="{{ route('client.products.index') }}" class="small text-danger text-decoration-none fw-semibold">
              <i class="fa-solid fa-xmark me-1"></i> Xóa lọc
            </a>
          @endif
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- 1. CATEGORIES FILTER -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">
            <i class="fa-solid fa-layer-group me-1 text-warning"></i> Danh Mục Sản Phẩm
          </h6>
          <div class="d-flex flex-column gap-2 small">
            <a href="{{ route('client.products.index', array_merge(request()->except(['category', 'page']))) }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ empty($categorySlug) ? 'fw-bold text-warning' : 'text-muted' }}">
              <span><i class="fa-solid fa-border-all me-1"></i> Tất cả danh mục</span>
            </a>

            @foreach($categories as $parent)
              <div class="category-tree-item">
                <a href="{{ route('client.products.index', array_merge(request()->except('page'), ['category' => $parent->slug])) }}" class="d-flex justify-content-between align-items-center text-decoration-none fw-semibold {{ $categorySlug === $parent->slug ? 'text-warning' : 'text-dark' }} py-1">
                  <span><i class="{{ $parent->icon ?? 'fa-solid fa-shirt' }} me-1 text-secondary"></i> {{ $parent->name }}</span>
                  <span class="badge bg-light text-dark rounded-pill">{{ $parent->products_count }}</span>
                </a>

                <!-- Sub-categories (Con) -->
                @if($parent->activeChildren && $parent->activeChildren->count() > 0)
                  <div class="ps-3 d-flex flex-column gap-1 border-start ms-2 my-1">
                    @foreach($parent->activeChildren as $child)
                      <a href="{{ route('client.products.index', array_merge(request()->except('page'), ['category' => $child->slug])) }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ $categorySlug === $child->slug ? 'fw-bold text-warning' : 'text-muted' }} py-0.5">
                        <span>— {{ $child->name }}</span>
                        <span class="badge bg-light text-secondary rounded-pill" style="font-size: 0.7rem;">{{ $child->products_count }}</span>
                      </a>
                    @endforeach
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- 2. BRANDS FILTER -->
        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark small text-uppercase mb-0">
              <i class="fa-solid fa-crown me-1 text-warning"></i> Thương Hiệu
            </h6>
          </div>
          <div class="d-flex flex-column gap-2 small">
            @foreach($brands as $b)
              <a href="{{ route('client.products.index', array_merge(request()->except('page'), ['brand' => (request('brand') === $b->slug ? null : $b->slug)])) }}" class="d-flex justify-content-between align-items-center text-decoration-none {{ request('brand') === $b->slug ? 'text-warning fw-bold' : 'text-muted' }}">
                <span>
                  <i class="fa-regular {{ request('brand') === $b->slug ? 'fa-square-check text-warning' : 'fa-square text-secondary' }} me-1"></i>
                  {{ $b->name }}
                </span>
                <span class="badge bg-light text-dark rounded-pill">{{ $b->products_count }}</span>
              </a>
            @endforeach
          </div>
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- 3. PRICE RANGE FILTER -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">Khoảng Giá (VNĐ)</h6>
          <div class="d-flex flex-column gap-2 small">
            <a href="{{ route('client.products.index', array_merge(request()->except(['price_range', 'page']), ['price_range' => (request('price_range') === 'under_500' ? null : 'under_500')])) }}" class="text-decoration-none {{ request('price_range') === 'under_500' ? 'text-warning fw-bold' : 'text-muted' }}">
              <i class="fa-regular {{ request('price_range') === 'under_500' ? 'fa-circle-dot text-warning' : 'fa-circle text-secondary' }} me-1"></i> Dưới 500.000₫
            </a>
            <a href="{{ route('client.products.index', array_merge(request()->except(['price_range', 'page']), ['price_range' => (request('price_range') === '500_1000' ? null : '500_1000')])) }}" class="text-decoration-none {{ request('price_range') === '500_1000' ? 'text-warning fw-bold' : 'text-muted' }}">
              <i class="fa-regular {{ request('price_range') === '500_1000' ? 'fa-circle-dot text-warning' : 'fa-circle text-secondary' }} me-1"></i> 500.000₫ - 1.000.000₫
            </a>
            <a href="{{ route('client.products.index', array_merge(request()->except(['price_range', 'page']), ['price_range' => (request('price_range') === 'over_1000' ? null : 'over_1000')])) }}" class="text-decoration-none {{ request('price_range') === 'over_1000' ? 'text-warning fw-bold' : 'text-muted' }}">
              <i class="fa-regular {{ request('price_range') === 'over_1000' ? 'fa-circle-dot text-warning' : 'fa-circle text-secondary' }} me-1"></i> Trên 1.000.000₫
            </a>
          </div>
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- 4. SIZE FILTER -->
        <div class="mb-4">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">
            <i class="fa-solid fa-ruler-combined me-1 text-warning"></i> Kích Thước (Size Áo)
          </h6>
          <div class="d-flex flex-wrap gap-2">
            @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL'] as $size)
              <a href="{{ route('client.products.index', array_merge(request()->except('page'), ['size' => (request('size') === $size ? null : $size)])) }}" class="btn btn-sm {{ request('size') === $size ? 'btn-bee-primary text-white fw-bold' : 'btn-outline-secondary' }} px-3 py-1 fw-semibold rounded-2" style="font-size: 0.8rem;">
                {{ $size }}
              </a>
            @endforeach
          </div>
        </div>

        <hr class="my-2 border-secondary-subtle">

        <!-- 5. COLOR FILTER -->
        <div class="mb-3">
          <h6 class="fw-bold text-dark small text-uppercase mb-3">
            <i class="fa-solid fa-palette me-1 text-warning"></i> Gam Màu Phổ Biến
          </h6>
          <div class="d-flex flex-wrap gap-1.5">
            @foreach(['Đen', 'Trắng', 'Xanh Navy', 'Xám', 'Xanh Rêu', 'Beige'] as $col)
              <a href="{{ route('client.products.index', array_merge(request()->except('page'), ['color' => (request('color') === $col ? null : $col)])) }}" class="badge {{ request('color') === $col ? 'bg-dark text-warning border-warning' : 'bg-light text-dark border' }} py-1.5 px-2 text-decoration-none fw-semibold rounded-pill" style="font-size: 0.75rem;">
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
      @if($categorySlug === 'ao-polo-nam' || (isset($activeCat) && str_contains(strtolower($activeCat->slug ?? ''), 'polo')))
        <!-- HERO BANNER POLO HERITAGE -->
        <div class="card border-0 shadow-sm mb-4 text-white overflow-hidden" style="border-radius: 16px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);">
          <div class="card-body p-4 p-md-4 position-relative">
            <div class="position-absolute end-0 top-0 bottom-0 d-none d-md-flex align-items-center opacity-10 pe-4" style="font-size: 8rem; pointer-events: none;">
              <i class="fa-solid fa-shirt"></i>
            </div>
            <div class="position-relative" style="z-index: 2; max-width: 620px;">
              <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill mb-2 shadow-xs">
                <i class="fa-solid fa-crown me-1"></i> BST POLO SIGNATURE 2026
              </span>
              <h4 class="fw-bold text-white mb-1.5" style="letter-spacing: -0.3px;">
                ÁO POLO NAM CAO CẤP • CHUẨN FORM QUÝ ÔNG
              </h4>
              <p class="text-white-50 small mb-2.5" style="font-size: 0.85rem;">
                100% Sợi Cotton dệt tổ ong kháng khuẩn độc quyền • Co giãn 4 chiều tự nhiên • Đứng form, thoáng khí suốt ngày dài làm việc và sự kiện.
              </p>
              <div class="d-flex flex-wrap gap-2 gap-md-3 pt-2 border-top border-secondary-subtle small text-warning" style="font-size: 0.78rem;">
                <span><i class="fa-solid fa-circle-check me-1"></i> Chuẩn Form Quý Ông</span>
                <span><i class="fa-solid fa-shield-halved me-1"></i> Kháng khuẩn 99%</span>
                <span><i class="fa-solid fa-rotate-left me-1"></i> Đổi size 7 ngày</span>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- TOP TOOLBAR -->
      <div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: 12px; background: #ffffff;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <span class="text-muted small">Hiển thị <strong>{{ $products->count() }}</strong> trên tổng <strong>{{ $products->total() }}</strong> sản phẩm</span>
            @if(request('q'))
              <span class="badge bg-warning-subtle text-dark ms-2">Tìm kiếm: "{{ request('q') }}"</span>
            @endif
            @if(request('brand'))
              <span class="badge bg-dark text-white ms-1">Brand: {{ request('brand') }}</span>
            @endif
            @if(request('size'))
              <span class="badge bg-dark text-white ms-1">Size: {{ request('size') }}</span>
            @endif
          </div>

          <div class="d-flex align-items-center gap-2">
            <label class="small text-muted text-nowrap">Sắp xếp:</label>
            <select class="form-select form-select-sm" style="width: 170px;" onchange="location = this.value;">
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>Bán chạy nhất</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
              <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
            </select>
          </div>
        </div>
      </div>

      <!-- PRODUCT CARDS -->
      <div class="row g-3">
        @forelse($products as $product)
          <div class="col-6 col-md-4">
            <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 16px; overflow: hidden; background: #ffffff; border: 1px solid rgba(0,0,0,0.05) !important;">
              <div class="position-relative bg-light p-3 text-center" style="height: 230px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if($product->discount_percent > 0)
                  <span class="position-absolute top-0 start-0 m-2.5 badge bg-danger rounded-pill shadow-xs" style="font-size: 0.72rem; z-index: 2;">-{{ $product->discount_percent }}%</span>
                @endif
                
                <!-- NÚT TRÁI TIM YÊU THÍCH -->
                <button type="button" class="btn btn-sm btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active' : '' }} position-absolute top-0 end-0 m-2.5 rounded-circle shadow-xs" 
                  onclick="toggleWishlist({{ $product->id }}, this)" 
                  title="Yêu thích sản phẩm" style="width: 32px; height: 32px; z-index: 4; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(0,0,0,0.08);">
                  <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart text-dark' }} fs-6"></i>
                </button>

                <a href="{{ route('client.products.show', $product->id) }}" class="d-block w-100 h-100 d-flex align-items-center justify-content-center">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid transition-all" style="max-height: 195px; object-fit: contain;">
                </a>
              </div>

              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-warning fw-bold text-uppercase" style="font-size: 0.75rem;">{{ $product->category->name ?? 'Thời trang nam' }}</small>
                    
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
                </div>

                <div>
                  <div class="d-flex align-items-baseline gap-2 mb-2.5">
                    <strong class="text-danger fw-bold fs-6">{{ number_format($product->price, 0, ',', '.') }}₫</strong>
                    @if($product->original_price && $product->original_price > $product->price)
                      <small class="text-muted text-decoration-line-through" style="font-size: 0.8rem;">{{ number_format($product->original_price, 0, ',', '.') }}₫</small>
                    @endif
                  </div>

                  <!-- 2 NÚT THÊM VÀO GIỎ HÀNG & MUA HÀNG NGAY (MỞ MODAL CHỌN MÀU & SIZE) -->
                  <div class="d-flex gap-1.5 mt-1">
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap shadow-xs" 
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
                      title="Thêm vào giỏ hàng (Chọn màu & size)" style="font-size: 0.8rem; padding-top: 6px; padding-bottom: 6px;">
                      <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Giỏ
                    </button>
                    <button type="button" class="btn btn-bee-primary btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap shadow-xs" 
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
                      title="Mua hàng ngay (Chọn màu & size)" style="font-size: 0.8rem; padding-top: 6px; padding-bottom: 6px;">
                      <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
                    </button>
                  </div>
                </div>

              </div>
            </div>
          </div>
        @empty
          <div class="col-12 text-center py-5">
            <i class="fa-solid fa-box-open text-muted fs-1 mb-3"></i>
            <h5 class="fw-bold text-dark">Không tìm thấy sản phẩm nào</h5>
            <p class="text-muted small">Hãy thử tìm kiếm với từ khóa khác hoặc xóa bộ lọc để xem toàn bộ sản phẩm.</p>
            <a href="{{ route('client.products.index') }}" class="btn btn-warning btn-sm px-4 fw-bold">Xóa Bộ Lọc</a>
          </div>
        @endforelse
      </div>


      <!-- PAGINATION -->
      <div class="d-flex justify-content-center mt-5">
        {{ $products->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection