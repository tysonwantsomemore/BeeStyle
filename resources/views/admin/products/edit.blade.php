@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Sản Phẩm | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Chỉnh Sửa Sản Phẩm: {{ $product['name'] }}</h3>
      <p class="text-muted small mb-0">Cập nhật thông tin chi tiết, giá bán và thuộc tính sản phẩm</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại
    </a>
  </div>
</div>

<form action="{{ route('admin.products.update', $product['id']) }}" method="POST">
  @csrf
  @method('PUT')
  <div class="row g-4">
    <!-- LEFT: MAIN INFO -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">1. Thông Tin Cơ Bản</h5>
        
        <div class="mb-3">
          <label class="form-label small fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" value="{{ $product['name'] }}" required>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Mã SKU <span class="text-danger">*</span></label>
            <input type="text" name="sku" class="form-control font-monospace" value="{{ $product['sku'] }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Danh mục thời trang <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select" required>
              @foreach($categories as $cat)
                <option value="{{ $cat['id'] }}" {{ $product['category'] === $cat['name'] ? 'selected' : '' }}>
                  {{ $cat['name'] }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Mô tả ngắn</label>
          <textarea name="short_description" class="form-control" rows="2">{{ $product['short_description'] }}</textarea>
        </div>

        <div class="mb-0">
          <label class="form-label small fw-semibold">Mô tả chi tiết sản phẩm</label>
          <textarea name="description" class="form-control" rows="5">{{ $product['description'] }}</textarea>
        </div>
      </div>

      <!-- VARIANTS -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">2. Thuộc Tính &amp; Biến Thể</h5>
        
        <div class="mb-3">
          <label class="form-label small fw-semibold">Màu sắc đang áp dụng</label>
          <div class="d-flex flex-wrap gap-2">
            @foreach(['Đen', 'Trắng', 'Xanh Navy', 'Beige', 'Xám Tro', 'Nâu Cafe', 'Hồng Pastel', 'Xanh Mint'] as $c)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $c }}" id="c_{{ $loop->index }}" {{ in_array($c, $product['colors']) ? 'checked' : '' }}>
                <label class="form-check-label small" for="c_{{ $loop->index }}">{{ $c }}</label>
              </div>
            @endforeach
          </div>
        </div>

        <div class="mb-0">
          <label class="form-label small fw-semibold">Kích thước đang áp dụng</label>
          <div class="d-flex flex-wrap gap-2">
            @foreach(['S', 'M', 'L', 'XL', 'XXL', '38', '39', '40', '41', '42', '43'] as $s)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $s }}" id="s_{{ $loop->index }}" {{ in_array($s, $product['sizes']) ? 'checked' : '' }}>
                <label class="form-check-label small" for="s_{{ $loop->index }}">{{ $s }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">3. Giá Bán &amp; Kho</h5>
        
        <div class="mb-3">
          <label class="form-label small fw-semibold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
          <input type="number" name="price" class="form-control fw-bold text-danger" value="{{ $product['price'] }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Giá gốc</label>
          <input type="number" name="original_price" class="form-control" value="{{ $product['original_price'] }}">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Số lượng trong kho</label>
          <input type="number" name="stock" class="form-control" value="{{ $product['stock'] }}" required>
        </div>

        <div class="mb-0">
          <label class="form-label small fw-semibold">Trạng thái</label>
          <select name="status" class="form-select">
            <option value="1" selected>Đang bán</option>
            <option value="0">Tạm ẩn</option>
          </select>
        </div>
      </div>

      <div class="card border-0 shadow-sm p-4 mb-4 text-center" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3 text-start">4. Ảnh Hiện Tại</h5>
        <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="img-fluid rounded border p-2 bg-light mb-3" style="max-height: 180px;">
        <input type="file" class="form-control form-control-sm">
      </div>

      <button type="submit" class="btn btn-bee-primary w-100 py-3 fs-6">
        <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Thay Đổi
      </button>
    </div>
  </div>
</form>
@endsection
