@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm Mới | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Thêm Sản Phẩm Thời Trang Mới</h3>
      <p class="text-muted small mb-0">Cung cấp thông tin hình ảnh, giá cả và biến thể size/màu cho sản phẩm</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại Danh Sách
    </a>
  </div>
</div>

@if ($errors->any())
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

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="row g-4">
    <!-- LEFT: MAIN INFO -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">1. Thông Tin Cơ Bản</h5>
        
        <div class="mb-3">
          <label class="form-label small fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ví dụ: Áo Polo Nam Cotton Dệt Tổ Ong..." required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Mã SKU <small class="text-muted fw-normal">(Tùy chọn)</small></label>
            <input type="text" name="sku" class="form-control font-monospace @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="Tự sinh nếu để trống (BEE-XXXXXX)">
            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Danh mục thời trang <span class="text-danger">*</span></label>
            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
              <option value="">-- Chọn danh mục --</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-md-4">
            <label class="form-label small fw-semibold">Thương hiệu</label>
            <select name="brand_id" class="form-select">
              <option value="">-- Chọn thương hiệu --</option>
              @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Mô tả ngắn</label>
          <textarea name="short_description" class="form-control" rows="2" placeholder="Mô tả tóm tắt chất liệu, form dáng, tính năng...">{{ old('short_description') }}</textarea>
        </div>

        <div class="mb-0">
          <label class="form-label small fw-semibold">Mô tả chi tiết sản phẩm</label>
          <textarea name="description" class="form-control" rows="5" placeholder="Chi tiết đường may, hướng dẫn giặt ủi, bảng size...">{{ old('description') }}</textarea>
        </div>
      </div>

      <!-- VARIANTS: SIZES & COLORS -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">2. Thuộc Tính &amp; Biến Thể</h5>
        
        <div class="mb-3">
          <label class="form-label small fw-semibold">Màu sắc có sẵn</label>
          <div class="d-flex flex-wrap gap-2">
            @php
              $oldColors = old('colors', ['Đen', 'Trắng']);
            @endphp
            @foreach(['Đen', 'Trắng', 'Xanh Navy', 'Beige', 'Xám Tro', 'Nâu Cafe', 'Xanh Rêu', 'Xanh Mint'] as $c)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $c }}" id="c_{{ $loop->index }}" {{ in_array($c, $oldColors) ? 'checked' : '' }}>
                <label class="form-check-label small" for="c_{{ $loop->index }}">{{ $c }}</label>
              </div>
            @endforeach
          </div>
        </div>

        <div class="mb-0">
          <label class="form-label small fw-semibold">Kích thước (Size) có sẵn</label>
          <div class="d-flex flex-wrap gap-2">
            @php
              $oldSizes = old('sizes', ['S', 'M', 'L', 'XL']);
            @endphp
            @foreach(['S', 'M', 'L', 'XL', 'XXL', '38', '39', '40', '41', '42', '43'] as $s)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $s }}" id="s_{{ $loop->index }}" {{ in_array($s, $oldSizes) ? 'checked' : '' }}>
                <label class="form-check-label small" for="s_{{ $loop->index }}">{{ $s }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- GALLERY IMAGES -->
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-2"><i class="fa-solid fa-images text-warning me-2"></i>5. Thư Viện Ảnh Phụ (Gallery Images)</h5>
        <p class="text-muted small mb-3">Tải lên nhiều ảnh sản phẩm ở các góc chụp khác nhau để hiển thị slider trang chi tiết</p>
        
        <div class="border border-dashed p-3 text-center rounded-3 bg-light">
          <i class="fa-solid fa-images fs-2 text-secondary mb-2"></i>
          <p class="small text-muted mb-2">Chọn một hoặc nhiều file ảnh từ máy tính</p>
          <input type="file" name="gallery_images[]" class="form-control form-control-sm" accept="image/*" multiple>
        </div>
      </div>
    </div>

    <!-- RIGHT: PRICING & MEDIA -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">3. Giá Bán &amp; Tồn Kho</h5>
        
        <div class="mb-3">
          <label class="form-label small fw-semibold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
          <input type="number" name="price" class="form-control fw-bold text-danger @error('price') is-invalid @enderror" value="{{ old('price', 390000) }}" placeholder="390000" required>
          @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Giá gốc (Gạch ngang nếu giảm giá)</label>
          <input type="number" name="original_price" class="form-control" value="{{ old('original_price', 490000) }}" placeholder="490000">
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Số lượng trong kho <span class="text-danger">*</span></label>
          <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 100) }}" required>
          @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Trạng thái kinh doanh</label>
          <select name="status" class="form-select">
            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Đang mở bán công khai</option>
            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tạm ẩn / Bản nháp</option>
          </select>
        </div>

        <div class="mb-0">
          <div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', 1) ? 'checked' : '' }}>
            <label class="form-check-label small" for="is_featured">Sản phẩm nổi bật (Featured)</label>
          </div>
          <div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="is_best_seller" {{ old('is_best_seller') ? 'checked' : '' }}>
            <label class="form-check-label small" for="is_best_seller">Bán chạy nhất (Best Seller)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_new" value="1" id="is_new" {{ old('is_new', 1) ? 'checked' : '' }}>
            <label class="form-check-label small" for="is_new">Hàng mới về (New Arrival)</label>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">4. Hình Ảnh Đại Diện</h5>
        <div class="border border-dashed p-3 text-center rounded-3 bg-light mb-3">
          <i class="fa-solid fa-cloud-arrow-up fs-2 text-warning mb-2"></i>
          <p class="small text-muted mb-2">Tải file ảnh từ máy tính</p>
          <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
        </div>
        <div>
          <label class="form-label small fw-semibold">Hoặc nhập URL / Asset ảnh demo:</label>
          <input type="text" name="image_url" class="form-control form-control-sm" value="{{ old('image_url', '/assets/img/products/polo_01.jpg') }}" placeholder="/assets/img/products/polo_01.jpg">
        </div>
      </div>

      <button type="submit" class="btn btn-bee-primary w-100 py-3 fs-6">
        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu &amp; Đăng Sản Phẩm
      </button>
    </div>
  </div>
</form>
@endsection
