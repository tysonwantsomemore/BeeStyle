@extends('layouts.admin')

@section('title', 'Chi Tiết Sản Phẩm #' . $product->sku . ' | BeeStyle Admin')

@section('content')
<nav class="mb-3" aria-label="breadcrumb">
  <ol class="breadcrumb mb-0 fs-9">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Quản lý Sản phẩm</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
  </ol>
</nav>

<!-- TOP ACTION HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
      <span class="badge badge-phoenix fs-10 fw-bold px-2 py-1 {{ $product->is_active ? 'badge-phoenix-success' : 'badge-phoenix-secondary' }}">
        <i class="fa-solid {{ $product->is_active ? 'fa-circle-check' : 'fa-eye-slash' }} me-1"></i>
        {{ $product->is_active ? 'ĐANG KINH DOANH' : 'TẠM DỪNG' }}
      </span>
      <span class="badge badge-phoenix badge-phoenix-primary font-monospace fs-10 fw-bold">SKU: {{ $product->sku }}</span>
      @if($product->is_featured)
        <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold"><i class="fa-solid fa-star me-1"></i>NỔI BẬT</span>
      @endif
      @if($product->is_new)
        <span class="badge badge-phoenix badge-phoenix-info fs-10 fw-bold"><i class="fa-solid fa-sparkles me-1"></i>HÀNG MỚI</span>
      @endif
    </div>
    <h2 class="mb-0 text-body-emphasis fw-bold fs-5 fs-md-4">{{ $product->name }}</h2>
    <p class="text-body-tertiary mb-0 fs-9">
      Danh mục: <strong class="text-body-highlight">{{ $product->category->name ?? 'Thời trang' }}</strong> 
      @if($product->brand)
        | Thương hiệu: <strong class="text-body-highlight">{{ $product->brand->name }}</strong>
      @endif
      | Ngày tạo: <span class="font-monospace">{{ $product->created_at->format('d/m/Y H:i') }}</span>
    </p>
  </div>

  <div class="col-auto d-flex align-items-center gap-2 flex-wrap">
    <a href="{{ route('admin.products.index') }}" class="btn btn-phoenix-secondary btn-sm">
      <i class="fa-solid fa-arrow-left me-1"></i> Danh Sách
    </a>
    <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="btn btn-phoenix-info btn-sm">
      <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Xem Ngoài Web
    </a>
    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">
      <i class="fa-solid fa-pen-to-square me-1"></i> Chỉnh Sửa
    </a>
    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này cùng toàn bộ biến thể và hình ảnh?');" class="d-inline">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-phoenix-danger btn-sm">
        <i class="fa-regular fa-trash-can me-1"></i> Xóa
      </button>
    </form>
  </div>
</div>

<!-- KPI SUMMARY CARDS -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-1">
          <span class="fs-10 fw-bold text-body-tertiary text-uppercase">Giá Bán Hiện Tại</span>
          <i class="fa-solid fa-tag text-danger fs-8"></i>
        </div>
        <h4 class="mb-0 text-danger fw-bolder">{{ number_format($product->price, 0, ',', '.') }}₫</h4>
        @if($product->original_price && $product->original_price > $product->price)
          <div class="fs-10 text-body-tertiary mt-1">
            Gốc: <del>{{ number_format($product->original_price, 0, ',', '.') }}₫</del>
            <span class="text-danger fw-bold ms-1">-{{ $product->discount_percent }}%</span>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-1">
          <span class="fs-10 fw-bold text-body-tertiary text-uppercase">Tồn Kho Khả Dụng</span>
          <i class="fa-solid fa-boxes-stacked text-primary fs-8"></i>
        </div>
        <h4 class="mb-0 fw-bolder {{ $product->stock <= 5 ? 'text-warning' : 'text-body-emphasis' }}">
          {{ $product->stock }} <small class="fs-9 fw-normal text-body-tertiary">cái</small>
        </h4>
        <div class="fs-10 mt-1">
          @if($product->stock <= 0)
            <span class="text-danger fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i>Hết hàng trong kho</span>
          @elseif($product->stock <= 5)
            <span class="text-warning fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i>Sắp hết hàng</span>
          @else
            <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i>Kho dồi dào</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-1">
          <span class="fs-10 fw-bold text-body-tertiary text-uppercase">Đã Bán Ra</span>
          <i class="fa-solid fa-cart-shopping text-success fs-8"></i>
        </div>
        <h4 class="mb-0 text-success fw-bolder">{{ number_format($product->sold_count ?? 0) }} <small class="fs-9 fw-normal text-body-tertiary">sp</small></h4>
        <div class="fs-10 text-body-tertiary mt-1">
          Doanh thu: <strong class="text-body-highlight">{{ number_format(($product->sold_count ?? 0) * $product->price, 0, ',', '.') }}₫</strong>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-1">
          <span class="fs-10 fw-bold text-body-tertiary text-uppercase">Đánh Giá &amp; Lượt Xem</span>
          <i class="fa-solid fa-star text-warning fs-8"></i>
        </div>
        <h4 class="mb-0 text-warning fw-bolder">
          {{ number_format($product->rating, 1) }} <small class="fs-9 fw-normal text-body-tertiary">({{ $product->reviews_count }} lượt)</small>
        </h4>
        <div class="fs-10 text-body-tertiary mt-1">
          Lượt xem trang: <strong class="text-body-highlight">{{ number_format($product->views ?? 0) }}</strong>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- LEFT COLUMN: VARIANTS & DETAILS -->
  <div class="col-12 col-lg-8">

    <!-- 1. BẢNG BIẾN THỂ MÀU VÀ SIZE -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-layer-group text-primary"></i>
          <h5 class="fw-bold text-body-emphasis mb-0">Ma Trận Biến Thể Thời Trang ({{ $product->variants->count() }} biến thể)</h5>
        </div>
        <span class="badge badge-phoenix badge-phoenix-info fs-10">Tự động đồng bộ kho</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm fs-9 mb-0 align-middle">
            <thead class="bg-body-tertiary text-body-tertiary">
              <tr>
                <th class="ps-3 py-2">Mã SKU Biến Thể</th>
                <th class="py-2">Màu Sắc</th>
                <th class="py-2">Kích Cỡ</th>
                <th class="py-2 text-end">Giá Bán</th>
                <th class="py-2 text-center">Tồn Kho</th>
                <th class="py-2 pe-3 text-end">Trạng Thái</th>
              </tr>
            </thead>
            <tbody>
              @forelse($product->variants as $variant)
                <tr class="border-bottom border-translucent">
                  <td class="ps-3 py-2 font-monospace fw-bold text-primary">{{ $variant->sku }}</td>
                  <td class="py-2">
                    <div class="d-flex align-items-center gap-2">
                      <span class="rounded-circle border border-translucent" style="width: 14px; height: 14px; background-color: {{ $variant->color_code ?? '#111827' }};"></span>
                      <span class="fw-semibold text-body-emphasis">{{ $variant->color }}</span>
                    </div>
                  </td>
                  <td class="py-2">
                    <span class="badge badge-phoenix badge-phoenix-secondary fw-bold">{{ $variant->size }}</span>
                  </td>
                  <td class="py-2 text-end fw-semibold text-danger">
                    {{ number_format($variant->price, 0, ',', '.') }}₫
                  </td>
                  <td class="py-2 text-center">
                    @if($variant->stock <= 0)
                      <span class="badge badge-phoenix badge-phoenix-danger">0 (Hết)</span>
                    @elseif($variant->stock <= 5)
                      <span class="badge badge-phoenix badge-phoenix-warning">{{ $variant->stock }} cái</span>
                    @else
                      <span class="fw-bold text-body-emphasis">{{ $variant->stock }} cái</span>
                    @endif
                  </td>
                  <td class="py-2 pe-3 text-end">
                    @if($variant->status === 'active')
                      <span class="badge badge-phoenix badge-phoenix-success fs-10">Đang bán</span>
                    @else
                      <span class="badge badge-phoenix badge-phoenix-secondary fs-10">Tạm dừng</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-4 text-body-tertiary">
                    Chưa có biến thể nào được cấu hình cho sản phẩm này.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- 2. THÔNG SỐ KỸ THUẬT & MAY ĐO -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-scissors text-primary"></i>
          <h5 class="fw-bold text-body-emphasis mb-0">Thông Số May Đo &amp; Tiêu Chuẩn Kỹ Thuật</h5>
        </div>
      </div>
      <div class="card-body">
        @php
          $specs = is_array($product->specifications) ? $product->specifications : [];
        @endphp
        @if(!empty($specs))
          <div class="row g-3">
            @foreach($specs as $key => $val)
              <div class="col-md-6">
                <div class="p-2.5 rounded bg-body-tertiary border border-translucent">
                  <span class="fs-10 text-body-tertiary text-uppercase fw-bold d-block">{{ $key }}</span>
                  <span class="fs-9 fw-semibold text-body-emphasis">{{ $val }}</span>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-body-tertiary fs-9 mb-0">Chưa có thông số may đo chi tiết.</p>
        @endif
      </div>
    </div>

    <!-- 3. MÔ TẢ SẢN PHẨM -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-align-left text-primary"></i>
          <h5 class="fw-bold text-body-emphasis mb-0">Mô Tả Sản Phẩm</h5>
        </div>
      </div>
      <div class="card-body">
        @if($product->short_description)
          <div class="p-3 mb-3 bg-body-tertiary rounded border border-translucent">
            <span class="fs-10 text-body-tertiary fw-bold text-uppercase d-block mb-1">Mô tả ngắn:</span>
            <p class="fs-9 text-body-emphasis mb-0">{{ $product->short_description }}</p>
          </div>
        @endif

        <span class="fs-10 text-body-tertiary fw-bold text-uppercase d-block mb-2">Chi tiết sản phẩm:</span>
        <div class="fs-9 text-body-secondary lh-base">
          {!! nl2br(e($product->description ?? 'Chưa có mô tả chi tiết cho sản phẩm này.')) !!}
        </div>
      </div>
    </div>

  </div>

  <!-- RIGHT COLUMN: IMAGES & REVIEWS -->
  <div class="col-12 col-lg-4">

    <!-- 4. HÌNH ẢNH SẢN PHẨM -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-images text-primary"></i>
          <h5 class="fw-bold text-body-emphasis mb-0">Hình Ảnh &amp; Thư Viện</h5>
        </div>
      </div>
      <div class="card-body text-center">
        <div class="position-relative mb-3">
          <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded border border-translucent p-1 bg-body-tertiary shadow-xs" style="max-height: 260px; width: 100%; object-fit: contain;">
          <span class="position-absolute top-0 start-0 m-2 badge bg-dark fs-10">Ảnh đại diện</span>
        </div>

        @if($product->images && $product->images->count() > 0)
          <div class="border-top border-translucent pt-3 text-start">
            <label class="form-label fs-10 fw-bold text-body-tertiary text-uppercase mb-2">
              Ảnh phụ trong thư viện ({{ $product->images->count() }} ảnh):
            </label>
            <div class="d-flex gap-2 flex-wrap">
              @foreach($product->images as $gImg)
                <a href="{{ asset($gImg->image_path) }}" target="_blank" title="Xem ảnh kích thước gốc">
                  <img src="{{ asset($gImg->image_path) }}" alt="Gallery thumbnail" class="rounded border border-translucent bg-body-emphasis p-0.5" style="width: 58px; height: 58px; object-fit: cover;">
                </a>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>

    <!-- 5. ĐÁNH GIÁ TỪ KHÁCH HÀNG -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-comments text-primary"></i>
          <h5 class="fw-bold text-body-emphasis mb-0">Đánh Giá ({{ $product->reviews->count() }})</h5>
        </div>
        <span class="text-warning fw-bold fs-9"><i class="fa-solid fa-star"></i> {{ number_format($product->rating, 1) }}</span>
      </div>
      <div class="card-body p-3">
        @forelse($product->reviews->take(4) as $rev)
          <div class="border-bottom border-translucent pb-2 mb-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="fw-bold fs-10 text-body-emphasis">{{ $rev->user->name ?? 'Khách hàng' }}</span>
              <span class="text-warning fs-10">
                @for($i = 1; $i <= 5; $i++)
                  <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-body-tertiary' }}"></i>
                @endfor
              </span>
            </div>
            <p class="fs-10 text-body-secondary mb-0">{{ $rev->comment }}</p>
            <small class="text-body-tertiary fs-11">{{ $rev->created_at ? $rev->created_at->diffForHumans() : '' }}</small>
          </div>
        @empty
          <p class="text-body-tertiary fs-10 mb-0 text-center py-2">Sản phẩm chưa nhận đánh giá nào.</p>
        @endforelse
      </div>
    </div>

  </div>
</div>
@endsection
