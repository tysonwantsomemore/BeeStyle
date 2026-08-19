@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Thời Trang | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Danh Sách Sản Phẩm</h3>
      <p class="text-muted small mb-0">Quản lý kho hàng, giá bán, biến thể size và màu sắc của BeeStyle Menswear</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-bee-primary btn-sm">
      <i class="fa-solid fa-plus me-1"></i> Thêm Sản Phẩm Mới
    </a>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm tên hoặc mã SKU..." style="width: 250px;">
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
        @forelse($products as $product)
          <tr>
            <td><span class="font-monospace fw-bold text-secondary">{{ $product->sku }}</span></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 45px; height: 45px; object-fit: contain;" class="border rounded bg-light">
                <div>
                  <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="fw-bold small text-dark text-decoration-none">
                    {{ $product->name }}
                  </a>
                  <div class="text-muted fs-10">
                    Màu: {{ is_array($product->colors) ? implode(', ', $product->colors) : 'Tiêu chuẩn' }} | Size: {{ is_array($product->sizes) ? implode(', ', $product->sizes) : 'Free Size' }}
                  </div>
                </div>
              </div>
            </td>
            <td><span class="badge bg-light text-dark border">{{ $product->category->name ?? 'Thời trang nam' }}</span></td>
            <td><strong class="text-danger">{{ number_format($product->price, 0, ',', '.') }}₫</strong></td>
            <td>
              @if($product->original_price)
                <small class="text-muted text-decoration-line-through">{{ number_format($product->original_price, 0, ',', '.') }}₫</small>
              @else
                <small class="text-muted">-</small>
              @endif
            </td>
            <td><span class="fw-bold text-dark">{{ $product->stock }}</span></td>
            <td><span class="badge bg-success-subtle text-success fw-bold">{{ $product->sold_count }}</span></td>
            <td>
              <span class="text-warning"><i class="fa-solid fa-star"></i> {{ $product->rating }}</span>
              <small class="text-muted">({{ $product->reviews_count }})</small>
            </td>
            <td>
              @if($product->status === 'active' && $product->stock > 0)
                <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Đang bán</span>
              @elseif($product->stock <= 0)
                <span class="badge bg-danger-subtle text-danger fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Hết hàng</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary fw-bold">Tạm ẩn</span>
              @endif
            </td>
            <td class="text-end">
              <div class="d-flex justify-content-end gap-1">
                <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Xem chi tiết">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning text-dark" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi hệ thống?');" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="text-center py-4 text-muted">Không tìm thấy sản phẩm nào trong kho.</td>
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
