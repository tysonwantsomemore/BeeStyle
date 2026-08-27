@extends('layouts.client')

@section('title', 'Danh Mục Sản Phẩm Thời Trang Nam | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Danh mục sản phẩm</li>
    </ol>
  </nav>

  <!-- CATEGORY HERO BANNER -->
  <div class="card border-0 text-white overflow-hidden mb-5 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);">
    <div class="card-body p-4 p-md-5 text-center position-relative">
      <div class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3">
        <i class="fa-solid fa-layer-group me-1"></i> DANH MỤC THỜI TRANG CAO CẤP
      </div>
      <h1 class="display-6 fw-bold mb-3 text-white">Khám Phá Các Dòng Sản Phẩm BeeStyle</h1>
      <p class="text-light-subtle mx-auto mb-4" style="max-width: 720px; font-size: 1rem; line-height: 1.6;">
        Tổng hợp đầy đủ các danh mục thời trang nam chuẩn Casual &amp; Smart Casual. Từ áo polo thoáng khí, sơ mi công sở cao cấp đến các bộ sưu tập theo mùa đầy cá tính.
      </p>

      <!-- Category Quick Stats -->
      <div class="d-flex justify-content-center align-items-center gap-3 gap-md-5 flex-wrap pt-2 border-top border-secondary-subtle">
        <div class="text-center">
          <span class="fs-4 fw-black text-warning d-block">{{ $categories->count() }}</span>
          <span class="small text-white-50 text-uppercase" style="font-size: 0.75rem;">Danh Mục Độc Quyền</span>
        </div>
        <div class="vr bg-secondary-subtle d-none d-sm-block"></div>
        <div class="text-center">
          <span class="fs-4 fw-black text-white d-block">{{ $totalProducts }}+</span>
          <span class="small text-white-50 text-uppercase" style="font-size: 0.75rem;">Mẫu Thiết Kế Mới</span>
        </div>
        <div class="vr bg-secondary-subtle d-none d-sm-block"></div>
        <div class="text-center">
          <span class="fs-4 fw-black text-success d-block">100%</span>
          <span class="small text-white-50 text-uppercase" style="font-size: 0.75rem;">Chất Liệu Tuyển Chọn</span>
        </div>
      </div>
    </div>
  </div>

  <!-- CATEGORIES GRID SHOWCASE -->
  <div class="row g-4 mb-5">
    @forelse($categories as $cat)
      <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 18px; overflow: hidden; background: #ffffff;">
          <div class="row g-0 h-100">
            
            <!-- Category Image / Banner -->
            <div class="col-sm-5 position-relative bg-light overflow-hidden" style="min-height: 240px;">
              <img src="{{ asset($cat->image ?? '/assets/img/products/polo_01.jpg') }}" alt="{{ $cat->name }}" class="w-100 h-100" style="object-fit: cover; object-position: center; transition: transform 0.4s ease;">
              <div class="position-absolute top-0 start-0 m-3">
                <span class="badge bg-dark bg-opacity-75 text-warning fw-bold px-3 py-1.5 rounded-pill shadow-sm" style="backdrop-filter: blur(4px);">
                  <i class="{{ $cat->icon ?? 'fa-solid fa-shirt' }} me-1"></i> {{ $cat->name }}
                </span>
              </div>
              <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-to-t" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                <span class="text-white small fw-bold">
                  <i class="fa-solid fa-boxes-stacked me-1 text-warning"></i> {{ $cat->products_count }} Sản phẩm
                </span>
              </div>
            </div>

            <!-- Category Details & Action -->
            <div class="col-sm-7 p-4 d-flex flex-column justify-content-between">
              <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h4 class="fw-bold text-dark mb-0 fs-5">
                    <a href="{{ route('client.categories.show', $cat->slug) }}" class="text-decoration-none text-dark hover-warning">
                      {{ $cat->name }}
                    </a>
                  </h4>
                  <span class="badge bg-warning-subtle text-dark fw-bold rounded-pill" style="font-size: 0.75rem;">
                    HOT
                  </span>
                </div>

                <p class="text-secondary small mb-3" style="line-height: 1.6; min-height: 48px;">
                  {{ $cat->description ?? 'Dòng sản phẩm thời trang nam thiết kế tỉ mỉ, chất lượng cao cấp, mang lại sự thoải mái và tự tin cho phái mạnh.' }}
                </p>

                <!-- Sub categories or Preview Samples -->
                @if($cat->activeChildren && $cat->activeChildren->count() > 0)
                  <div class="mb-3">
                    <span class="small text-muted fw-semibold d-block mb-1.5" style="font-size: 0.75rem;">Phân loại nổi bật:</span>
                    <div class="d-flex flex-wrap gap-1">
                      @foreach($cat->activeChildren as $child)
                        <a href="{{ route('client.categories.show', $child->slug) }}" class="badge bg-light text-secondary border text-decoration-none py-1 px-2 hover-warning" style="font-size: 0.72rem;">
                          {{ $child->name }}
                        </a>
                      @endforeach
                    </div>
                  </div>
                @endif
              </div>

              <div class="pt-3 border-top d-flex gap-2">
                <a href="{{ route('client.categories.show', $cat->slug) }}" class="btn btn-bee-primary btn-sm px-3 fw-bold rounded-2 flex-grow-1">
                  Xem Danh Mục <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
                <a href="{{ route('client.products.index', ['category' => $cat->slug]) }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-2" title="Lọc trên trang Cửa hàng">
                  <i class="fa-solid fa-filter"></i>
                </a>
              </div>
            </div>

          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <i class="fa-solid fa-box-open fs-1 text-muted mb-3"></i>
        <p class="text-muted">Đang cập nhật danh mục sản phẩm.</p>
      </div>
    @endforelse
  </div>

  <!-- FEATURED PRODUCTS SECTION -->
  @if(isset($featuredProducts) && $featuredProducts->count() > 0)
    <div class="card border-0 shadow-sm p-4 p-md-5 mb-4" style="border-radius: 20px; background: #ffffff;">
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
          <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1 rounded-pill mb-1">GỢI Ý HÔM NAY</span>
          <h3 class="fw-bold text-dark mb-0">Sản Phẩm Tiêu Biểu Theo Danh Mục</h3>
        </div>
        <a href="{{ route('client.products.index') }}" class="btn btn-outline-warning text-dark btn-sm fw-bold rounded-2">
          Xem Tất Cả Sản Phẩm <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="row g-3">
        @foreach($featuredProducts as $item)
          <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 14px; overflow: hidden; background: #fdfdfd;">
              <div class="position-relative bg-light p-3 text-center" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                @if($item->discount_percent > 0)
                  <span class="position-absolute top-0 start-0 m-2 badge bg-danger rounded-pill">-{{ $item->discount_percent }}%</span>
                @endif
                <a href="{{ route('client.products.show', $item->id) }}">
                  <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="img-fluid" style="max-height: 170px; object-fit: contain;">
                </a>
              </div>
              <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div>
                  <small class="text-warning fw-bold d-block mb-1">{{ $item->category->name ?? 'Thời trang nam' }}</small>
                  <h6 class="fw-bold text-dark text-truncate-2 mb-2" style="font-size: 0.85rem; min-height: 38px;">
                    <a href="{{ route('client.products.show', $item->id) }}" class="text-decoration-none text-dark hover-warning">
                      {{ $item->name }}
                    </a>
                  </h6>
                </div>
                <div>
                  <div class="d-flex align-items-baseline gap-2 mb-2">
                    <strong class="text-danger fw-bold" style="font-size: 0.95rem;">{{ number_format($item->price, 0, ',', '.') }}₫</strong>
                    @if($item->original_price && $item->original_price > $item->price)
                      <small class="text-muted text-decoration-line-through" style="font-size: 0.75rem;">{{ number_format($item->original_price, 0, ',', '.') }}₫</small>
                    @endif
                  </div>
                  <a href="{{ route('client.products.show', $item->id) }}" class="btn btn-outline-warning text-dark btn-sm w-100 fw-bold rounded-2" style="font-size: 0.78rem;">
                    Xem Chi Tiết
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

</div>
@endsection
