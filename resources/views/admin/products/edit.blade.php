@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Sản Phẩm | BeeStyle Admin')

@push('styles')
<style>
  .color-swatch-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid #e2e8f0;
    position: relative;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }
  .color-swatch-btn:hover {
    transform: scale(1.15);
    border-color: #0f172a;
  }
  .color-swatch-btn.active {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.35);
  }
  .color-swatch-btn.active::after {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    font-size: 11px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #ffffff;
    text-shadow: 0 0 2px rgba(0,0,0,0.8);
  }
  .color-swatch-btn[data-color="Trắng"].active::after,
  .color-swatch-btn[data-color="Beige"].active::after,
  .color-swatch-btn[data-color="Vàng Cát"].active::after {
    color: #0f172a !important;
    text-shadow: none !important;
  }
  .tag-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    color: #1e293b;
    transition: all 0.2s ease;
  }
  .tag-chip:hover {
    background: #e2e8f0;
  }
  .tag-chip .chip-remove {
    cursor: pointer;
    color: #94a3b8;
    transition: color 0.15s ease;
  }
  .tag-chip .chip-remove:hover {
    color: #ef4444;
  }
</style>
@endpush

@section('content')
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-primary fs-10 fw-bold px-2 py-1">CẬP NHẬT</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Chỉnh Sửa Sản Phẩm: {{ $product->name }}</h2>
    </div>
    <p class="text-body-tertiary mb-0">Cập nhật thông tin chi tiết, giá bán, thư viện ảnh và ma trận biến thể đồng bộ</p>
  </div>
  <div class="col-auto d-flex align-items-center gap-2">
    <a href="{{ route('admin.products.index') }}" class="btn btn-phoenix-secondary">
      <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại
    </a>
    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này cùng toàn bộ biến thể và hình ảnh? Hành động này không thể hoàn tác!');" class="d-inline">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-phoenix-danger">
        <i class="fa-regular fa-trash-can me-1"></i> Xóa Sản Phẩm
      </button>
    </form>
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

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
  @csrf
  @method('PUT')
  <div class="row g-4">
    <!-- LEFT: MAIN INFO & VARIANTS -->
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-bottom border-translucent bg-body-emphasis">
          <h5 class="fw-bold text-body-emphasis mb-0">1. Thông Tin Cơ Bản</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="name" id="productEditNameInput" class="form-control" value="{{ old('name', $product->name) }}" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fs-9 fw-semibold">Mã SKU <span class="text-danger">*</span></label>
              <input type="text" name="sku" id="skuEditInput" class="form-control font-monospace fw-bold" value="{{ old('sku', $product->sku) }}" required oninput="renderVariantMatrix()">
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

      <!-- 2. THUỘC TÍNH & BIẾN THỂ ĐỒNG BỘ -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-info-subtle text-info-emphasis d-inline-flex align-items-center justify-content-center fs-9 fw-bold">2</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Thuộc Tính Màu Sắc &amp; Kích Thước (Biến Thể)</h5>
          </div>
          <span class="badge bg-primary-subtle text-primary fw-bold" id="totalVariantsCounterBadge">{{ $product->variants->count() }} biến thể</span>
        </div>
        <div class="card-body p-4">
          
          <!-- PHẦN A: BẢNG MÀU SẮC SẢN PHẨM -->
          <div class="mb-4 pb-3 border-bottom border-translucent">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fs-9 fw-bold text-dark mb-0">
                <i class="fa-solid fa-palette text-primary me-1"></i> Bảng Màu Sắc Áp Dụng (Chọn hoặc gõ thêm màu mới):
              </label>
              <span class="fs-10 text-muted">Nhấn vào màu để bật/tắt</span>
            </div>

            <!-- Swatches Palette -->
            @php
              $prodColors = is_array($product->colors) ? $product->colors : [];
              $prodSizes = is_array($product->sizes) ? $product->sizes : [];
              $palette = [
                ['name' => 'Đen', 'code' => '#111827'],
                ['name' => 'Trắng', 'code' => '#FFFFFF'],
                ['name' => 'Xanh Navy', 'code' => '#1E3A8A'],
                ['name' => 'Xám Tro', 'code' => '#6B7280'],
                ['name' => 'Xám Ghi', 'code' => '#9CA3AF'],
                ['name' => 'Beige', 'code' => '#E5D9C5'],
                ['name' => 'Nâu Cafe', 'code' => '#78350F'],
                ['name' => 'Xanh Rêu', 'code' => '#365314'],
                ['name' => 'Xanh Mint', 'code' => '#6EE7B7'],
                ['name' => 'Đỏ Đô', 'code' => '#881337'],
                ['name' => 'Vàng Cát', 'code' => '#FDE047'],
              ];
            @endphp
            <div class="d-flex flex-wrap align-items-center gap-2.5 mb-3" id="colorSwatchesContainer">
              @foreach($palette as $item)
                <button type="button" 
                        class="color-swatch-btn {{ in_array($item['name'], $prodColors) ? 'active' : '' }}" 
                        style="background-color: {{ $item['code'] }};" 
                        data-color="{{ $item['name'] }}" 
                        data-code="{{ $item['code'] }}" 
                        title="{{ $item['name'] }}" 
                        onclick="toggleColorSwatch(this)">
                </button>
              @endforeach
            </div>

            <!-- Thêm màu tùy chỉnh & Danh sách màu đang chọn -->
            <div class="row g-2 align-items-center mb-2">
              <div class="col-sm-5 col-8">
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-plus text-secondary"></i></span>
                  <input type="text" id="customColorInput" class="form-control fs-9" placeholder="Thêm màu mới (VD: Rượu Vang)...">
                  <button type="button" class="btn btn-phoenix-primary" onclick="addCustomColor()">Thêm</button>
                </div>
              </div>
              <div class="col-sm-7 col-12">
                <div class="d-flex flex-wrap gap-1.5" id="activeColorChips"></div>
              </div>
            </div>
            <!-- Hidden inputs container for colors[] -->
            <div id="hiddenColorsContainer"></div>
          </div>

          <!-- PHẦN B: KÍCH THƯỚC (SIZE) -->
          <div class="mb-4 pb-3 border-bottom border-translucent">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fs-9 fw-bold text-dark mb-0">
                <i class="fa-solid fa-ruler-combined text-primary me-1"></i> Kích Thước (Size) Áp Dụng:
              </label>
              <div class="d-flex gap-1.5">
                <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="applySizePreset(['S', 'M', 'L', 'XL'])">Form Chuẩn (S-XL)</button>
                <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="applySizePreset(['S', 'M', 'L', 'XL', 'XXL', '3XL'])">Đủ Dải (S-3XL)</button>
              </div>
            </div>

            <!-- Preset Size Buttons -->
            <div class="d-flex flex-wrap gap-2 mb-3" id="sizeButtonsContainer">
              @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL', 'Freesize'] as $s)
                <button type="button" 
                        class="btn btn-sm btn-outline-secondary px-2.5 py-1 fs-9 fw-bold size-pill-btn {{ in_array($s, $prodSizes) ? 'btn-dark text-white border-dark active' : '' }}" 
                        data-size="{{ $s }}" 
                        onclick="toggleSizePill(this)">
                  {{ $s }}
                </button>
              @endforeach
            </div>

            <!-- Thêm size tự do -->
            <div class="row g-2 align-items-center">
              <div class="col-sm-5 col-8">
                <div class="input-group input-group-sm">
                  <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-plus text-secondary"></i></span>
                  <input type="text" id="customSizeInput" class="form-control fs-9" placeholder="Thêm size mới...">
                  <button type="button" class="btn btn-phoenix-primary" onclick="addCustomSize()">Thêm</button>
                </div>
              </div>
              <div class="col-sm-7 col-12">
                <div class="d-flex flex-wrap gap-1.5" id="activeSizeChips"></div>
              </div>
            </div>
            <!-- Hidden inputs container for sizes[] -->
            <div id="hiddenSizesContainer"></div>
          </div>

          <!-- PHẦN C: BẢNG MA TRẬN BIẾN THỂ ĐỒNG BỘ -->
          <div>
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
              <div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                  <span class="fs-9 fw-bold text-dark d-block">
                    <i class="fa-solid fa-table-cells text-warning me-1"></i> Ma Trận Biến Thể Tự Sinh (Variant Matrix)
                  </span>
                  <span class="badge badge-phoenix badge-phoenix-success fs-11" id="distributeNoticeBadge">
                    <i class="fa-solid fa-check me-1"></i>Đồng bộ kho
                  </span>
                </div>
                <span class="fs-10 text-muted">Hệ thống luôn tự động lưu tồn kho chính xác từng màu sắc và kích cỡ</span>
              </div>
              <div class="d-flex gap-1.5 flex-wrap">
                <button type="button" class="btn btn-phoenix-warning btn-xs py-1 px-2 fs-10 fw-bold" onclick="setEachVariantStock(1000)">
                  <i class="fa-solid fa-bolt me-1"></i>Mỗi Mẫu = 1.000 Cái
                </button>
                <button type="button" class="btn btn-phoenix-primary btn-xs py-1 px-2 fs-10" onclick="setEachVariantStock(100)">
                  <i class="fa-solid fa-layer-group me-1"></i>Mỗi Mẫu = 100 Cái
                </button>
                <button type="button" class="btn btn-phoenix-secondary btn-xs py-1 px-2 fs-10" onclick="distributeStockEqually()">
                  <i class="fa-solid fa-calculator me-1"></i>Chia Đều Tổng Kho
                </button>
              </div>
            </div>

            <!-- Variant Matrix Table -->
            <div class="table-responsive border rounded-3 bg-white max-h-[320px] overflow-y-auto">
              <table class="table table-sm table-hover mb-0 fs-9 align-middle" id="variantMatrixTable">
                <thead class="bg-body-secondary text-body-emphasis sticky-top">
                  <tr>
                    <th class="ps-3 py-2" style="width: 25%;">Màu Sắc</th>
                    <th class="py-2" style="width: 15%;">Kích Thước</th>
                    <th class="py-2" style="width: 30%;">Mã SKU Con</th>
                    <th class="py-2" style="width: 15%;">Giá Bán</th>
                    <th class="pe-3 py-2 text-end" style="width: 15%;">Tồn Kho</th>
                  </tr>
                </thead>
                <tbody id="variantMatrixBody">
                  <!-- Rendered dynamically by JavaScript -->
                </tbody>
              </table>
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
        <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex align-items-center justify-content-between">
          <h5 class="fw-bold text-body-emphasis mb-0">3. Giá Bán &amp; Kho</h5>
          <span class="badge bg-danger-subtle text-danger fw-bold" id="editDiscountBadge">Giảm {{ $product->discount_percent }}%</span>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="number" name="price" id="productEditPriceInput" class="form-control fw-bold text-danger fs-8" value="{{ old('price', $product->price) }}" required min="0" step="1000" oninput="handleEditPriceCalculation()">
              <span class="input-group-text fw-bold text-dark fs-9">₫</span>
            </div>
            <span class="fs-10 text-muted mt-1 d-block" id="editFormattedPriceText">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
          </div>

          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label fs-9 fw-semibold mb-0">Giá gốc niêm yết</label>
              <span class="fs-10 text-muted" id="editSavingsText">
                @if($product->original_price && $product->original_price > $product->price)
                  Tiết kiệm: {{ number_format($product->original_price - $product->price, 0, ',', '.') }} ₫
                @else
                  Không giảm giá
                @endif
              </span>
            </div>
            <div class="input-group input-group-sm">
              <input type="number" name="original_price" id="productEditOriginalPriceInput" class="form-control fs-9 text-secondary" value="{{ old('original_price', $product->original_price ?: $product->price) }}" min="0" step="1000" oninput="handleEditPriceCalculation()">
              <span class="input-group-text fs-9">₫</span>
            </div>
            <!-- Quick Discount Buttons -->
            <div class="d-flex gap-1.5 flex-wrap mt-2">
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="applyEditDiscountPercent(10)">-10%</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="applyEditDiscountPercent(20)">-20%</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="applyEditDiscountPercent(30)">-30%</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="applyEditDiscountPercent(50)">-50%</button>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Số lượng trong kho (Cái) <span class="text-danger">*</span></label>
            <input type="number" name="stock" id="productEditStockInput" class="form-control fw-bold" value="{{ old('stock', $product->stock) }}" required min="0" oninput="handleStockChange(this.value)">
            <div class="d-flex gap-1.5 flex-wrap mt-2">
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="setQuickStock(100)">100 cái</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="setQuickStock(500)">500 cái</button>
              <button type="button" class="btn btn-phoenix-primary btn-xs py-0.5 px-2 fs-10 fw-bold" onclick="setQuickStock(1000)"><i class="fa-solid fa-bolt me-1"></i>1.000 cái</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="setQuickStock(2000)">2.000 cái</button>
            </div>
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

@push('scripts')
<script>
  // Initial state from backend
  const existingVariants = @json($product->variants->map(fn($v) => ['color' => $v->color, 'size' => $v->size, 'stock' => $v->stock, 'sku' => $v->sku]));
  const colorMap = {
    'Đen': '#111827', 'Trắng': '#FFFFFF', 'Xanh Navy': '#1E3A8A', 'Xám Tro': '#6B7280',
    'Xám Ghi': '#9CA3AF', 'Beige': '#E5D9C5', 'Nâu Cafe': '#78350F', 'Xanh Rêu': '#365314',
    'Xanh Mint': '#6EE7B7', 'Đỏ Đô': '#881337', 'Vàng Cát': '#FDE047'
  };

  @php
    $initColors = is_array($product->colors) ? $product->colors : ['Đen', 'Trắng'];
    $initSizes = is_array($product->sizes) ? $product->sizes : ['S', 'M', 'L', 'XL'];
  @endphp

  let selectedColors = [
    @foreach($initColors as $ic)
      { name: '{{ $ic }}', code: colorMap['{{ $ic }}'] || '#334155' },
    @endforeach
  ];

  let selectedSizes = @json($initSizes);

  document.addEventListener('DOMContentLoaded', function() {
    renderColorChips();
    renderSizeChips();
    renderVariantMatrix();
    handleEditPriceCalculation();
  });

  function slugify(text) {
    if (!text) return '';
    return text.toString().toLowerCase()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
      .replace(/[đĐ]/g, 'd')
      .replace(/[^a-z0-9\s-]/g, '')
      .trim()
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-');
  }

  function toggleColorSwatch(btn) {
    const colorName = btn.getAttribute('data-color');
    const colorCode = btn.getAttribute('data-code');

    const index = selectedColors.findIndex(c => c.name === colorName);
    if (index > -1) {
      if (selectedColors.length <= 1) {
        alert('Sản phẩm cần tối thiểu 1 màu sắc!');
        return;
      }
      selectedColors.splice(index, 1);
      btn.classList.remove('active');
    } else {
      selectedColors.push({ name: colorName, code: colorCode });
      btn.classList.add('active');
    }
    renderColorChips();
    renderVariantMatrix();
  }

  function addCustomColor() {
    const input = document.getElementById('customColorInput');
    const val = input.value.trim();
    if (!val) return;

    if (selectedColors.some(c => c.name.toLowerCase() === val.toLowerCase())) {
      alert('Màu này đã có trong danh sách!');
      return;
    }

    selectedColors.push({ name: val, code: '#334155' });
    input.value = '';
    renderColorChips();
    renderVariantMatrix();
  }

  function removeColor(name) {
    if (selectedColors.length <= 1) {
      alert('Sản phẩm cần tối thiểu 1 màu sắc!');
      return;
    }
    selectedColors = selectedColors.filter(c => c.name !== name);
    document.querySelectorAll('.color-swatch-btn').forEach(btn => {
      if (btn.getAttribute('data-color') === name) btn.classList.remove('active');
    });

    renderColorChips();
    renderVariantMatrix();
  }

  function renderColorChips() {
    const container = document.getElementById('activeColorChips');
    const hiddenContainer = document.getElementById('hiddenColorsContainer');
    container.innerHTML = '';
    hiddenContainer.innerHTML = '';

    selectedColors.forEach(c => {
      const chip = document.createElement('span');
      chip.className = 'tag-chip';
      chip.innerHTML = `
        <span class="rounded-circle border" style="width: 10px; height: 10px; background-color: ${c.code};"></span>
        <span>${c.name}</span>
        <i class="fa-solid fa-xmark chip-remove" onclick="removeColor('${c.name}')"></i>
      `;
      container.appendChild(chip);

      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'colors[]';
      hiddenInput.value = c.name;
      hiddenContainer.appendChild(hiddenInput);
    });
  }

  function toggleSizePill(btn) {
    const size = btn.getAttribute('data-size');
    const index = selectedSizes.indexOf(size);

    if (index > -1) {
      if (selectedSizes.length <= 1) {
        alert('Sản phẩm cần tối thiểu 1 kích thước!');
        return;
      }
      selectedSizes.splice(index, 1);
      btn.classList.remove('btn-dark', 'text-white', 'border-dark', 'active');
      btn.classList.add('btn-outline-secondary');
    } else {
      selectedSizes.push(size);
      btn.classList.add('btn-dark', 'text-white', 'border-dark', 'active');
      btn.classList.remove('btn-outline-secondary');
    }
    renderSizeChips();
    renderVariantMatrix();
  }

  function applySizePreset(presetArray) {
    selectedSizes = [...presetArray];
    document.querySelectorAll('.size-pill-btn').forEach(btn => {
      const s = btn.getAttribute('data-size');
      if (selectedSizes.includes(s)) {
        btn.classList.add('btn-dark', 'text-white', 'border-dark', 'active');
        btn.classList.remove('btn-outline-secondary');
      } else {
        btn.classList.remove('btn-dark', 'text-white', 'border-dark', 'active');
        btn.classList.add('btn-outline-secondary');
      }
    });
    renderSizeChips();
    renderVariantMatrix();
  }

  function addCustomSize() {
    const input = document.getElementById('customSizeInput');
    const val = input.value.trim().toUpperCase();
    if (!val) return;

    if (selectedSizes.includes(val)) {
      alert('Kích thước này đã có!');
      return;
    }

    selectedSizes.push(val);
    input.value = '';
    renderSizeChips();
    renderVariantMatrix();
  }

  function removeSize(size) {
    if (selectedSizes.length <= 1) {
      alert('Sản phẩm cần tối thiểu 1 kích thước!');
      return;
    }
    selectedSizes = selectedSizes.filter(s => s !== size);
    document.querySelectorAll('.size-pill-btn').forEach(btn => {
      if (btn.getAttribute('data-size') === size) {
        btn.classList.remove('btn-dark', 'text-white', 'border-dark', 'active');
        btn.classList.add('btn-outline-secondary');
      }
    });

    renderSizeChips();
    renderVariantMatrix();
  }

  function renderSizeChips() {
    const container = document.getElementById('activeSizeChips');
    const hiddenContainer = document.getElementById('hiddenSizesContainer');
    container.innerHTML = '';
    hiddenContainer.innerHTML = '';

    selectedSizes.forEach(s => {
      const chip = document.createElement('span');
      chip.className = 'tag-chip';
      chip.innerHTML = `
        <span>Size ${s}</span>
        <i class="fa-solid fa-xmark chip-remove" onclick="removeSize('${s}')"></i>
      `;
      container.appendChild(chip);

      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'sizes[]';
      hiddenInput.value = s;
      hiddenContainer.appendChild(hiddenInput);
    });
  }

  function renderVariantMatrix() {
    const tbody = document.getElementById('variantMatrixBody');
    const skuBase = document.getElementById('skuEditInput').value.trim() || 'BS-PROD';
    const currentPrice = parseInt(document.getElementById('productEditPriceInput').value) || 0;
    const totalStock = parseInt(document.getElementById('productEditStockInput').value) || 0;

    const totalVariants = selectedColors.length * selectedSizes.length;
    document.getElementById('totalVariantsCounterBadge').innerText = totalVariants + ' biến thể';
    
    const baseStockPerVar = Math.floor(totalStock / Math.max(1, totalVariants));

    tbody.innerHTML = '';

    selectedColors.forEach(c => {
      selectedSizes.forEach(s => {
        const colorSlug = slugify(c.name).toUpperCase();
        const sizeSlug = s.toString().toUpperCase();
        const varSku = `${skuBase}-${colorSlug}-${sizeSlug}`;

        // Tìm tồn kho sẵn có từ biến thể DB
        const matchEv = existingVariants.find(v => v.color.toLowerCase() === c.name.toLowerCase() && v.size.toLowerCase() === s.toString().toLowerCase());
        let initialStock = matchEv ? matchEv.stock : baseStockPerVar;

        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td class="ps-3 py-2 fw-semibold text-dark">
            <span class="rounded-circle border d-inline-block me-1.5 align-middle" style="width: 12px; height: 12px; background-color: ${c.code};"></span>
            <span>${c.name}</span>
          </td>
          <td class="py-2 fw-bold text-primary">Size ${s}</td>
          <td class="py-2 font-monospace fs-10 text-muted">${varSku}</td>
          <td class="py-2 font-monospace fw-semibold text-dark">${currentPrice.toLocaleString('vi-VN')}₫</td>
          <td class="pe-3 py-2 text-end">
            <input type="number" 
                   name="variant_stock[${varSku}]" 
                   value="${initialStock}" 
                   min="0" 
                   class="form-control form-control-sm text-end fw-bold fs-9 font-monospace d-inline-block py-0 px-2" 
                   style="width: 95px; min-width: 90px;" 
                   oninput="recalcTotalStockFromMatrix()">
          </td>
        `;
        tbody.appendChild(tr);
      });
    });
  }

  function setQuickStock(amount) {
    const input = document.getElementById('productEditStockInput');
    if (input) {
      input.value = amount;
      handleStockChange(amount);
    }
  }

  function setEachVariantStock(amount) {
    const inputs = document.querySelectorAll('input[name^="variant_stock"]');
    if (inputs.length === 0) return;
    inputs.forEach(inp => inp.value = amount);
    recalcTotalStockFromMatrix();
  }

  function distributeStockEqually() {
    const totalStock = parseInt(document.getElementById('productEditStockInput').value) || 0;
    const inputs = document.querySelectorAll('input[name^="variant_stock"]');
    const count = inputs.length;
    if (count === 0) return;

    const base = Math.floor(totalStock / count);
    let rem = totalStock % count;

    inputs.forEach(inp => {
      let val = base;
      if (rem > 0) {
        val += 1;
        rem--;
      }
      inp.value = val;
    });

    recalcTotalStockFromMatrix();
  }

  function recalcTotalStockFromMatrix() {
    let sum = 0;
    document.querySelectorAll('input[name^="variant_stock"]').forEach(inp => {
      sum += parseInt(inp.value) || 0;
    });
    document.getElementById('productEditStockInput').value = sum;
  }

  function handleStockChange(val) {
    distributeStockEqually();
  }

  function handleEditPriceCalculation() {
    const priceInput = document.getElementById('productEditPriceInput');
    const origPriceInput = document.getElementById('productEditOriginalPriceInput');
    
    const price = parseInt(priceInput ? priceInput.value : 0) || 0;
    const origPrice = parseInt(origPriceInput ? origPriceInput.value : 0) || 0;
    
    const formattedPriceEl = document.getElementById('editFormattedPriceText');
    if (formattedPriceEl) {
      formattedPriceEl.innerText = price.toLocaleString('vi-VN') + ' VNĐ';
    }
    
    const savingsEl = document.getElementById('editSavingsText');
    const discountBadgeEl = document.getElementById('editDiscountBadge');
    
    if (origPrice > price && price > 0) {
      const savings = origPrice - price;
      const pct = Math.round((savings / origPrice) * 100);
      if (savingsEl) savingsEl.innerText = 'Tiết kiệm: ' + savings.toLocaleString('vi-VN') + ' ₫';
      if (discountBadgeEl) {
        discountBadgeEl.innerText = 'Giảm ' + pct + '%';
        discountBadgeEl.className = 'badge bg-danger-subtle text-danger fw-bold';
      }
    } else {
      if (savingsEl) savingsEl.innerText = 'Không giảm giá';
      if (discountBadgeEl) {
        discountBadgeEl.innerText = 'Giá chuẩn';
        discountBadgeEl.className = 'badge bg-secondary-subtle text-secondary fw-bold';
      }
    }
  }

  function applyEditDiscountPercent(pct) {
    const origPriceInput = document.getElementById('productEditOriginalPriceInput');
    const priceInput = document.getElementById('productEditPriceInput');
    
    let origPrice = parseInt(origPriceInput ? origPriceInput.value : 0) || 0;
    if (origPrice <= 0) {
      const price = parseInt(priceInput ? priceInput.value : 0) || 0;
      origPrice = price > 0 ? price : 500000;
      if (origPriceInput) origPriceInput.value = origPrice;
    }
    
    const discountedPrice = Math.round(origPrice * (1 - (pct / 100)));
    if (priceInput) priceInput.value = discountedPrice;
    
    handleEditPriceCalculation();
  }
</script>
@endpush
