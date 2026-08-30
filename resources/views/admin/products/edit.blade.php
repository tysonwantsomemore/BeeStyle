@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Sản Phẩm | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Chỉnh Sửa Sản Phẩm: {{ $product->name }}</h3>
      <p class="text-muted small mb-0">Cập nhật thông tin chi tiết, giá bán và thuộc tính sản phẩm</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại
    </a>
  </div>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="row g-4">
    <!-- LEFT: MAIN INFO -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">1. Thông Tin Cơ Bản</h5>
        
        <div class="mb-3">
          <label class="form-label small fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Mã SKU <span class="text-danger">*</span></label>
            <input type="text" name="sku" class="form-control font-monospace" value="{{ old('sku', $product->sku) }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Danh mục thời trang <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select" required>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Thương hiệu</label>
            <select name="brand_id" class="form-select">
              <option value="">-- Chưa chọn thương hiệu --</option>
              @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                  {{ $brand->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Mô tả ngắn</label>
          <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
        </div>

        <div class="mb-0">
          <label class="form-label small fw-semibold">Mô tả chi tiết sản phẩm</label>
          <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
        </div>
      </div>

      <!-- VARIANTS -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">2. Thuộc Tính &amp; Biến Thể</h5>
        
        @php
          $prodColors = is_array($product->colors) ? $product->colors : [];
          $prodSizes = is_array($product->sizes) ? $product->sizes : [];
        @endphp

        <div class="mb-3">
          <label class="form-label small fw-semibold">Màu sắc đang áp dụng</label>
          <div class="d-flex flex-wrap gap-2">
            @foreach(['Đen', 'Trắng', 'Xanh Navy', 'Beige', 'Xám Tro', 'Nâu Cafe', 'Xanh Rêu', 'Xanh Mint'] as $c)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $c }}" id="c_{{ $loop->index }}" {{ in_array($c, $prodColors) ? 'checked' : '' }}>
                <label class="form-check-label small" for="c_{{ $loop->index }}">{{ $c }}</label>
              </div>
            @endforeach
          </div>
        </div>

        <div class="mb-0">
          <label class="form-label small fw-semibold">Kích thước (Size) đang áp dụng</label>
          <div class="d-flex flex-wrap gap-2">
            @foreach(['S', 'M', 'L', 'XL', 'XXL', '38', '39', '40', '41', '42', '43'] as $s)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $s }}" id="s_{{ $loop->index }}" {{ in_array($s, $prodSizes) ? 'checked' : '' }}>
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
          <input type="number" name="price" class="form-control fw-bold text-danger" value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Giá gốc</label>
          <input type="number" name="original_price" class="form-control" value="{{ old('original_price', $product->original_price) }}">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Số lượng trong kho</label>
          <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Trạng thái</label>
          <select name="status" class="form-select">
            <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Đang bán (Hiển thị)</option>
            <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Tạm ẩn (Bản nháp)</option>
          </select>
        </div>

        <div class="mb-0">
          <div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
            <label class="form-check-label small" for="is_featured">Sản phẩm nổi bật</label>
          </div>
          <div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="is_best_seller" {{ $product->is_best_seller ? 'checked' : '' }}>
            <label class="form-check-label small" for="is_best_seller">Bán chạy nhất</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_new" value="1" id="is_new" {{ $product->is_new ? 'checked' : '' }}>
            <label class="form-check-label small" for="is_new">Hàng mới về</label>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm p-4 mb-4 text-center" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3 text-start">4. Ảnh Đại Diện</h5>
        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded border p-1 bg-light mb-3" style="max-height: 220px; width: 100%; object-fit: cover;">
        <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*">
        <input type="text" name="image_url" class="form-control form-control-sm mb-3" value="{{ $product->image }}" placeholder="Hoặc nhập URL ảnh">

        @if($product->images && $product->images->count() > 0)
          <div class="border-top pt-3 text-start">
            <label class="form-label small fw-semibold text-muted mb-2">Ảnh phụ trong thư viện ({{ $product->images->count() }} ảnh):</label>
            <div class="d-flex gap-2 flex-wrap">
              @foreach($product->images as $gImg)
                <div class="position-relative">
                  <img src="{{ asset($gImg->image_path) }}" alt="Gallery image" class="rounded border" style="width: 54px; height: 54px; object-fit: cover;">
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <button type="submit" class="btn btn-bee-primary w-100 py-3 fs-6">
        <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Thay Đổi
      </button>
    </div>
  </div>
</form>
@endsection
