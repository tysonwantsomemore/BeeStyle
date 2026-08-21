@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Thời Trang | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">KHO HÀNG</span>
        <h3 class="fw-bold text-dark mb-0">Danh Sách Sản Phẩm Thời Trang</h3>
      </div>
      <p class="text-muted small mb-0">Quản lý kho hàng, giá bán niêm yết, tồn kho và các biến thể size/màu sắc của BeeStyle</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-bee-primary btn-sm px-3">
      <i class="fa-solid fa-plus me-1.5"></i> Thêm Sản Phẩm Mới
    </a>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm tên hoặc mã SKU..." style="width: 260px;">
      <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 200px;">
        <option value="">Tất cả danh mục</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-sm btn-outline-secondary">Lọc</button>
      @if(request('q') || request('category_id'))
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>
    <div class="text-muted small">
      Tổng số: <strong>{{ $products->total() }}</strong> sản phẩm
    </div>
  </div>

  <div class="table-responsive">
    <table class="table align-middle mb-0">
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
        @forelse($products as $product)
          <tr>
            <td><span class="font-monospace fw-bold text-secondary">{{ $product->sku }}</span></td>
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 44px; height: 44px; object-fit: contain;" class="rounded border bg-white">
                <div>
                  <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="fw-bold small text-dark text-decoration-none d-block text-truncate" style="max-width: 220px;">
                    {{ $product->name }}
                  </a>
                  <small class="text-muted">{{ $product->variants->count() }} biến thể màu/size</small>
                </div>
              </div>
            </td>
            <td><span class="badge bg-light text-dark border">{{ $product->category->name ?? 'Thời trang nam' }}</span></td>
            <td><strong class="text-danger">{{ number_format($product->price, 0, ',', '.') }}₫</strong></td>
            <td><span class="text-muted text-decoration-line-through small">{{ number_format($product->original_price, 0, ',', '.') }}₫</span></td>
            <td>
              @if($product->stock <= 5)
                <span class="badge bg-danger-subtle text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Còn {{ $product->stock }}</span>
              @else
                <span class="fw-semibold text-dark">{{ $product->stock }} cái</span>
              @endif
            </td>
            <td><span class="badge bg-success-subtle text-success fw-bold">{{ $product->sold_count }}</span></td>
            <td>
              <span class="text-warning small fw-bold">
                <i class="fa-solid fa-star"></i> {{ $product->rating }}
              </span>
              <small class="text-muted">({{ $product->reviews_count }})</small>
            </td>
            <td>
              @if($product->is_active)
                <span class="badge bg-success-subtle text-success fw-bold py-1 px-2"><i class="fa-solid fa-circle-check me-1"></i> Kinh doanh</span>
              @else
                <span class="badge bg-secondary-subtle text-muted fw-bold py-1 px-2"><i class="fa-solid fa-eye-slash me-1"></i> Tạm dừng</span>
              @endif
            </td>
            <td class="text-end">
              <div class="d-flex align-items-center justify-content-end gap-1.5">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-dark py-1 px-2.5 fw-bold" title="Chỉnh sửa sản phẩm">
                  <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi hệ thống?');" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Xóa">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="text-center py-5 text-muted">
              <i class="fa-solid fa-shirt fs-2 text-muted mb-2 d-block"></i>
              Không tìm thấy sản phẩm nào phù hợp.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($products->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $products->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
