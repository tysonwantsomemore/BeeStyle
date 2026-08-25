@extends('layouts.client')

@section('title', 'Thương Hiệu Thời Trang Nam | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Thương hiệu đối tác</li>
    </ol>
  </nav>

  <!-- BRAND HERO BANNER -->
  <div class="card border-0 text-white overflow-hidden mb-5 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);">
    <div class="card-body p-4 p-md-5 text-center position-relative">
      <div class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3">
        <i class="fa-solid fa-crown me-1"></i> BỘ SƯU TẬP THƯƠNG HIỆU
      </div>
      <h1 class="display-6 fw-bold mb-3 text-white">Thương Hiệu Thời Trang Đẳng Cấp</h1>
      <p class="text-light-subtle max-w-2xl mx-auto mb-0" style="max-width: 680px;">
        Khám phá các dòng sản phẩm mang bản sắc riêng từ BeeStyle — Từ phong cách công sở quý phái, thời trang đường phố năng động đến dòng sản phẩm thể thao công nghệ cao.
      </p>
    </div>
  </div>

  <!-- BRANDS GRID -->
  <div class="row g-4 mb-5">
    @forelse($brands as $brand)
      <div class="col-lg-6">
        <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 18px; overflow: hidden; background: #ffffff;">
          <div class="row g-0 h-100 align-items-center">
            <!-- Brand Logo Column -->
            <div class="col-sm-4 p-4 text-center bg-light d-flex flex-column justify-content-center align-items-center border-end">
              <div class="bg-white rounded-circle shadow-sm p-3 mb-2 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                @if(!empty($brand->logo))
                  <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 55px; max-height: 55px; object-fit: contain;" onerror="this.onerror=null; this.src=''; this.classList.add('d-none'); this.nextElementSibling.classList.remove('d-none');">
                  <i class="fa-solid fa-crown text-warning fs-1 d-none"></i>
                @else
                  <i class="fa-solid fa-crown text-warning fs-1"></i>
                @endif
              </div>
              <span class="badge bg-warning-subtle text-dark fw-bold px-3 py-1 rounded-pill small">
                {{ $brand->products_count }} sản phẩm
              </span>
            </div>

            <!-- Brand Info Column -->
            <div class="col-sm-8 p-4">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h4 class="fw-bold text-dark mb-0">{{ $brand->name }}</h4>
                <span class="badge bg-success-subtle text-success small"><i class="fa-solid fa-check me-1"></i> Chính hãng</span>
              </div>
              
              <p class="text-secondary small mb-4" style="line-height: 1.6;">
                {{ $brand->description }}
              </p>

              <div class="d-flex gap-2">
                <a href="{{ route('client.brands.show', $brand->slug) }}" class="btn btn-bee-primary btn-sm px-3 fw-bold rounded-2">
                  Xem Bộ Sưu Tập <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
                <a href="{{ route('client.products.index', ['brand' => $brand->slug]) }}" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-2">
                  Lọc Sản Phẩm
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <p class="text-muted">Đang cập nhật danh sách thương hiệu.</p>
      </div>
    @endforelse
  </div>
</div>
@endsection
