@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Thời Trang | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Danh Sách Sản Phẩm</h3>
      <p class="text-muted small mb-0">Quản lý kho hàng, giá bán, biến thể size và màu sắc của BeeStyle</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-bee-primary btn-sm">
      <i class="fa-solid fa-plus me-1"></i> Thêm Sản Phẩm Mới
    </a>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-2">
      <input type="text" class="form-control form-control-sm" placeholder="Tìm tên hoặc mã SKU..." style="width: 250px;">
      <select class="form-select form-select-sm" style="width: 180px;">
        <option value="">Tất cả danh mục</option>
        @foreach($categories as $cat)
          <option value="{{ $cat['slug'] }}">{{ $cat['name'] }}</option>
        @endforeach
      </select>
    </div>
    <div class="text-muted small">
      Tổng số: <strong>{{ count($products) }}</strong> sản phẩm
    </div>
  </div>

  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Mã SKU</th>
          <th>Sản Phẩm</th>
          <th>Danh Mục</th>
          <th>Giá Bán</th>
          <th>Giá Gốc</th>
          <th>Tồn Kho</th>
          <th>Đã Bán</th>
          <th>Đánh Giá</th>
          <th>Trạng Thái</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
          <tr>
            <td><span class="font-monospace fw-bold text-secondary">{{ $product['sku'] }}</span></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" style="width: 45px; height: 45px; object-fit: contain;" class="border rounded bg-light">
                <div>
                  <a href="{{ route('client.products.show', $product['id']) }}" target="_blank" class="fw-bold small text-dark text-decoration-none">
                    {{ $product['name'] }}
                  </a>
                  <div class="text-muted fs-10">
                    Màu: {{ implode(', ', $product['colors']) }} | Size: {{ implode(', ', $product['sizes']) }}
                  </div>
                </div>
              </div>
            </td>
            <td><span class="badge bg-light text-dark border">{{ $product['category'] }}</span></td>
            <td><strong class="text-danger">{{ number_format($product['price'], 0, ',', '.') }}₫</strong></td>
            <td><small class="text-muted text-decoration-line-through">{{ number_format($product['original_price'], 0, ',', '.') }}₫</small></td>
            <td><span class="fw-bold text-dark">{{ $product['stock'] }}</span></td>
            <td><span class="badge bg-success-subtle text-success fw-bold">{{ $product['sold_count'] }}</span></td>
            <td>
              <span class="text-warning"><i class="fa-solid fa-star"></i> {{ $product['rating'] }}</span>
              <small class="text-muted">({{ $product['reviews_count'] }})</small>
            </td>
            <td>
              @if($product['stock'] > 0)
                <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Đang bán</span>
              @else
                <span class="badge bg-danger-subtle text-danger fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Hết hàng</span>
              @endif
            </td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('client.products.show', $product['id']) }}" target="_blank" class="btn btn-outline-secondary" title="Xem demo ngoài web">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <a href="{{ route('admin.products.edit', $product['id']) }}" class="btn btn-outline-warning text-dark" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <button type="button" class="btn btn-outline-danger" title="Xóa">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
