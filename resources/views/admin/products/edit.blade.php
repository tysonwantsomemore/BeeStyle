@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Sản Phẩm | BeeStyle Admin')

@section('content')
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-primary fs-10 fw-bold px-2 py-1">CẬP NHẬT</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Chỉnh Sửa Sản Phẩm: {{ $product->name }}</h2>
    </div>
    <p class="text-body-tertiary mb-0">Cập nhật thông tin chi tiết, giá bán và thuộc tính sản phẩm</p>
  </div>
  <div class="col-auto">
    <a href="{{ route('admin.products.index') }}" class="btn btn-phoenix-secondary">
      <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại
    </a>
  </div>
</div>

@if (isset($errors) && $errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <div class="d-flex align-items-center gap-2 mb-1">
      <i class="fa-solid fa-triangle-exclamation fs-5 text-danger"></i>
      <strong class="fs-6">Vui lòng kiểm tra lại thông tin nhập liệu:</strong>
    </div>
    <ul class="mb-0 small ps-4">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="row g-4">
    <!-- LEFT: MAIN INFO -->
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-bottom border-translucent bg-body-emphasis">
          <h5 class="fw-bold text-body-emphasis mb-0">1. Thông Tin Cơ Bản</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fs-9 fw-semibold">Mã SKU <span class="text-danger">*</span></label>
              <input type="text" name="sku" class="form-control font-monospace" value="{{ old('sku', $product->sku) }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fs-9 fw-semibold">Danh mục thời trang <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select" required>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label fs-9 fw-semibold">Thương hiệu</label>
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
            <label class="form-label fs-9 fw-semibold">Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
          </div>

          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Mô tả chi tiết sản phẩm</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
          </div>
        </div>
      </div>

      <!-- VARIANTS -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-bottom border-translucent bg-body-emphasis">
          <h5 class="fw-bold text-body-emphasis mb-0">2. Thuộc Tính &amp; Biến Thể</h5>
        </div>
        <div class="card-body">
          @php
            $prodColors = is_array($product->colors) ? $product->colors : [];
            $prodSizes = is_array($product->sizes) ? $product->sizes : [];
          @endphp

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Màu sắc đang áp dụng</label>
            <div class="d-flex flex-wrap gap-3">
              @foreach(['Đen', 'Trắng', 'Xanh Navy', 'Beige', 'Xám Tro', 'Nâu Cafe', 'Xanh Rêu', 'Xanh Mint'] as $c)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $c }}" id="c_{{ $loop->index }}" {{ in_array($c, $prodColors) ? 'checked' : '' }}>
                  <label class="form-check-label fs-9 text-body-emphasis" for="c_{{ $loop->index }}">{{ $c }}</label>
                </div>
              @endforeach
            </div>
          </div>

          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Kích thước (Size) đang áp dụng</label>
            <div class="d-flex flex-wrap gap-3">
              @foreach(['S', 'M', 'L', 'XL', 'XXL', '38', '39', '40', '41', '42', '43'] as $s)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $s }}" id="s_{{ $loop->index }}" {{ in_array($s, $prodSizes) ? 'checked' : '' }}>
                  <label class="form-check-label fs-9 text-body-emphasis" for="s_{{ $loop->index }}">{{ $s }}</label>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <!-- GALLERY IMAGES -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-images text-warning me-2"></i>5. Thư Viện Ảnh Phụ (Gallery Images)</h5>
        <p class="text-muted small mb-3">Tải thêm ảnh mới hoặc chọn xóa bớt ảnh phụ không cần thiết</p>

        @if($product->images && $product->images->count() > 0)
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Ảnh phụ hiện tại (Tích vào ảnh để XÓA):</label>
            <div class="row g-2">
              @foreach($product->images as $gImg)
                <div class="col-md-3 col-4">
                  <div class="border rounded p-2 text-center bg-light position-relative">
                    <img src="{{ asset($gImg->image_path) }}" alt="Gallery image" class="img-fluid rounded mb-2" style="height: 90px; object-fit: cover; width: 100%;">
                    <div class="form-check d-flex align-items-center justify-content-center gap-1 text-danger small">
                      <input class="form-check-input" type="checkbox" name="delete_gallery_ids[]" value="{{ $gImg->id }}" id="del_g_{{ $gImg->id }}">
                      <label class="form-check-label small fw-bold" for="del_g_{{ $gImg->id }}">Xóa ảnh này</label>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <div class="border border-dashed p-3 text-center rounded-3 bg-light">
          <i class="fa-solid fa-cloud-arrow-up fs-3 text-secondary mb-2"></i>
          <p class="small text-muted mb-2">Tải thêm ảnh phụ mới từ máy tính</p>
          <input type="file" name="gallery_images[]" class="form-control form-control-sm" accept="image/*" multiple>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-bottom border-translucent bg-body-emphasis">
          <h5 class="fw-bold text-body-emphasis mb-0">3. Giá Bán &amp; Kho</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
            <input type="number" name="price" class="form-control fw-bold text-danger" value="{{ old('price', $product->price) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Giá gốc</label>
            <input type="number" name="original_price" class="form-control" value="{{ old('original_price', $product->original_price) }}">
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Số lượng trong kho</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Trạng thái</label>
            <select name="status" class="form-select">
              <option value="active" {{ $product->status === 'active' ? 'selected' : '' }}>Đang bán (Hiển thị)</option>
              <option value="inactive" {{ $product->status === 'inactive' ? 'selected' : '' }}>Tạm ẩn (Bản nháp)</option>
            </select>
          </div>

          <div class="mb-0">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
              <label class="form-check-label fs-9 text-body-emphasis" for="is_featured">Sản phẩm nổi bật</label>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="is_best_seller" {{ $product->is_best_seller ? 'checked' : '' }}>
              <label class="form-check-label fs-9 text-body-emphasis" for="is_best_seller">Bán chạy nhất</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_new" value="1" id="is_new" {{ $product->is_new ? 'checked' : '' }}>
              <label class="form-check-label fs-9 text-body-emphasis" for="is_new">Hàng mới về</label>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm mb-4 text-center">
        <div class="card-header border-bottom border-translucent bg-body-emphasis text-start">
          <h5 class="fw-bold text-body-emphasis mb-0">4. Ảnh Đại Diện &amp; Thư Viện</h5>
        </div>
        <div class="card-body">
          <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded border border-translucent p-1 bg-body-tertiary mb-3" style="max-height: 220px; width: 100%; object-fit: contain;">
          <input type="file" name="image" class="form-control form-control-sm mb-2" accept="image/*">
          <input type="text" name="image_url" class="form-control form-control-sm mb-3" value="{{ $product->image }}" placeholder="Hoặc nhập URL ảnh">

          @if($product->images && $product->images->count() > 0)
            <div class="border-top border-translucent pt-3 text-start">
              <label class="form-label fs-10 fw-semibold text-body-tertiary mb-2">Ảnh phụ trong thư viện ({{ $product->images->count() }} ảnh):</label>
              <div class="d-flex gap-2 flex-wrap">
                @foreach($product->images as $gImg)
                  <div class="position-relative">
                    <img src="{{ asset($gImg->image_path) }}" alt="Gallery image" class="rounded border border-translucent bg-body-emphasis" style="width: 50px; height: 50px; object-fit: cover;">
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100 py-2.5 fs-9 fw-bold">
        <i class="fa-solid fa-floppy-disk me-1"></i> Cập Nhật Thay Đổi
      </button>
    </div>
  </div>
</form>
@endsection
