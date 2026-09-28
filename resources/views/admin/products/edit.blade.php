@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Sản Phẩm | BeeStyle Admin')

@push('styles')
<style>
  .price-variant-input::-webkit-outer-spin-button,
  .price-variant-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  .price-variant-input {
    -moz-appearance: textfield;
    font-size: 15.5px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    background-color: #ffffff !important;
    border: 1.5px solid #cbd5e1 !important;
    border-right: none !important;
    border-top-left-radius: 8px !important;
    border-bottom-left-radius: 8px !important;
    height: 42px !important;
    letter-spacing: 0.5px;
    font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }
  .price-variant-input:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    background-color: #ffffff !important;
    outline: none !important;
    position: relative;
    z-index: 3;
  }
  .price-variant-addon {
    font-size: 14px !important;
    font-weight: 800 !important;
    color: #334155 !important;
    background-color: #f1f5f9 !important;
    border: 1.5px solid #cbd5e1 !important;
    border-left: 1px solid #e2e8f0 !important;
    border-top-right-radius: 8px !important;
    border-bottom-right-radius: 8px !important;
    padding: 0 14px !important;
    height: 42px !important;
    display: flex;
    align-items: center;
  }
  .price-variant-subtext {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    margin-top: 4px;
    padding-left: 3px;
    letter-spacing: 0.2px;
    min-height: 18px;
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
    <p class="text-body-tertiary mb-0">Cập nhật thông tin chi tiết, giá bán và thuộc tính sản phẩm</p>
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
              @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL'] as $s)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $s }}" id="s_{{ $loop->index }}" {{ in_array($s, $prodSizes) ? 'checked' : '' }}>
                  <label class="form-check-label fs-9 text-body-emphasis" for="s_{{ $loop->index }}">{{ $s }}</label>
                </div>
              @endforeach
            </div>
          </div>

          @if($product->variants && $product->variants->count() > 0)
            <div class="mt-4 pt-3 border-top border-translucent">
              <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">
                <label class="form-label fs-9 fw-bold text-dark mb-0">
                  <i class="fa-solid fa-table-cells text-warning me-1"></i> Danh Sách Biến Thể (Chất Liệu &amp; Giá Bán Từng Mẫu)
                </label>
                <button type="button" class="btn btn-phoenix-primary btn-xs py-0.5 px-2.5 fw-bold" onclick="syncPriceToAllVariants()" title="Gán giá bán chung hiện tại cho toàn bộ các biến thể">
                  <i class="fa-solid fa-arrows-rotate me-1"></i>Đồng Bộ Theo Giá Bán Chung
                </button>
              </div>
              <p class="fs-10 text-muted mb-2">Giá bán từng biến thể có thể điều chỉnh độc lập hoặc dùng nút Đồng Bộ để cập nhật tất cả theo giá bán chung</p>
              <div class="table-responsive border rounded-3 bg-white" style="max-height: 380px; overflow-y: auto;">
                <table class="table table-hover mb-0 align-middle">
                  <thead class="bg-body-secondary text-body-emphasis sticky-top">
                    <tr>
                      <th class="ps-3 py-2.5">Màu Sắc</th>
                      <th class="py-2.5">Kích Cỡ</th>
                      <th class="py-2.5">Chất Liệu</th>
                      <th class="py-2.5">Mã SKU Con</th>
                      <th class="py-2.5" style="width: 220px; min-width: 210px;">Giá Bán (VNĐ)</th>
                      <th class="pe-3 py-2.5 text-end" style="width: 100px;">Tồn Kho</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($product->variants as $v)
                      <tr>
                        <td class="ps-3 py-2 fw-semibold">
                          <span class="rounded-circle border d-inline-block me-1.5 align-middle" style="width: 13px; height: 13px; background-color: {{ $v->color_code ?: '#333' }};"></span>
                          {{ $v->color }}
                        </td>
                        <td class="py-2 fw-bold text-primary">Size {{ $v->size }}</td>
                        <td class="py-2">
                          <input type="text" name="variant_material[{{ $v->id }}]" value="{{ old('variant_material.' . $v->id, $v->material) }}" placeholder="VD: Lụa, Cotton..." class="form-control" style="font-size: 13px; height: 42px; border-radius: 8px; border: 1.5px solid #cbd5e1;" title="Chất liệu của biến thể này">
                        </td>
                        <td class="py-2 font-monospace fs-10 text-muted">{{ $v->sku }}</td>
                        <td class="py-2">
                          <div class="input-group" style="min-width: 185px;">
                            <input type="number" 
                                   name="variant_price[{{ $v->id }}]" 
                                   value="{{ old('variant_price.' . $v->id, $v->price) }}" 
                                   step="1000" 
                                   min="0" 
                                   class="form-control price-variant-input px-3" 
                                   title="Giá bán riêng cho biến thể này"
                                   oninput="const sub = document.getElementById('edit_price_sub_{{ $v->id }}'); if (sub) { sub.innerText = (this.value > 0 ? ('= ' + parseInt(this.value).toLocaleString('vi-VN') + ' ₫') : ''); }">
                            <span class="input-group-text price-variant-addon">₫</span>
                          </div>
                          <div id="edit_price_sub_{{ $v->id }}" class="price-variant-subtext">
                            {{ (old('variant_price.' . $v->id, $v->price) > 0) ? ('= ' . number_format(old('variant_price.' . $v->id, $v->price), 0, ',', '.') . ' ₫') : '' }}
                          </div>
                        </td>
                        <td class="pe-3 py-2 text-end">
                          <input type="number" name="variant_stock[{{ $v->id }}]" value="{{ old('variant_stock.' . $v->id, $v->stock) }}" min="0" class="form-control text-end fw-bold font-monospace d-inline-block" style="width: 85px; font-size: 14px; height: 42px; border-radius: 8px; border: 1.5px solid #cbd5e1;">
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif
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
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label fs-9 fw-semibold mb-0">Giá bán (VNĐ) <span class="text-danger">*</span></label>
              <button type="button" class="btn btn-link btn-xs p-0 text-decoration-none fw-bold" onclick="syncPriceToAllVariants()" title="Gán ngay giá này cho toàn bộ biến thể">
                <i class="fa-solid fa-arrows-rotate me-1"></i>Áp dụng cho biến thể
              </button>
            </div>
            <input type="number" 
                   name="price" 
                   id="adminProductPriceInput" 
                   class="form-control fw-bold text-danger fs-8" 
                   value="{{ old('price', $product->price) }}" 
                   required 
                   step="1000" 
                   min="0" 
                   oninput="onMainPriceChange(this.value)">
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Giá gốc (Gạch ngang)</label>
            <input type="number" 
                   name="original_price" 
                   id="adminProductOriginalPriceInput" 
                   class="form-control" 
                   value="{{ old('original_price', $product->original_price) }}" 
                   step="1000" 
                   min="0">
          </div>

          <!-- Đồng bộ giá cho toàn bộ biến thể -->
          <div class="form-check form-switch mb-3 p-2 bg-light rounded border border-translucent">
            <input class="form-check-input ms-0 me-2" type="checkbox" name="sync_variant_prices" id="syncVariantPricesSwitch" value="1" checked>
            <label class="form-check-label fs-9 fw-semibold text-primary" for="syncVariantPricesSwitch">
              <i class="fa-solid fa-sync me-1"></i>Tự động cập nhật giá mới này cho tất cả biến thể
            </label>
            <div class="fs-10 text-muted mt-0.5">Khi lưu, giá của các biến thể con sẽ tự động được cập nhật theo giá bán này để khách hàng thấy giá mới ngay lập tức.</div>
          </div>

          <!-- THỜI HẠN KHUYẾN MÃI -->
          <div class="mb-3 pt-2 border-top border-translucent">
            <label class="form-label fs-9 fw-semibold text-dark">
              <i class="fa-regular fa-calendar-days text-primary me-1"></i>Thời Hạn Khuyến Mãi (Sale Period)
            </label>
            <div class="row g-2">
              <div class="col-6">
                <label class="form-label fs-10 text-muted mb-0.5">Bắt đầu:</label>
                <input type="datetime-local" 
                       name="sale_starts_at" 
                       class="form-control form-control-sm fs-9 font-monospace" 
                       value="{{ old('sale_starts_at', $product->sale_starts_at ? $product->sale_starts_at->format('Y-m-d\TH:i') : '') }}">
              </div>
              <div class="col-6">
                <label class="form-label fs-10 text-muted mb-0.5">Kết thúc (Hết hạn):</label>
                <input type="datetime-local" 
                       name="sale_ends_at" 
                       class="form-control form-control-sm fs-9 font-monospace" 
                       value="{{ old('sale_ends_at', $product->sale_ends_at ? $product->sale_ends_at->format('Y-m-d\TH:i') : '') }}">
              </div>
            </div>
            <span class="fs-10 text-muted d-block mt-1">Khi hết hạn, hệ thống tự động quay về Giá Gốc và hủy gạch ngang</span>
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Số lượng trong kho (Cái) <span class="text-danger">*</span></label>
            <input type="number" name="stock" id="productEditStockInput" class="form-control fw-bold" value="{{ old('stock', $product->stock) }}" required min="0">
            <div class="d-flex gap-1.5 flex-wrap mt-2">
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="document.getElementById('productEditStockInput').value = 100">100 cái</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="document.getElementById('productEditStockInput').value = 500">500 cái</button>
              <button type="button" class="btn btn-phoenix-primary btn-xs py-0.5 px-2 fs-10 fw-bold" onclick="document.getElementById('productEditStockInput').value = 1000"><i class="fa-solid fa-bolt me-1"></i>1.000 cái</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="document.getElementById('productEditStockInput').value = 2000">2.000 cái</button>
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

@push('scripts')
<script>
  function onMainPriceChange(newVal) {
    const switchEl = document.getElementById('syncVariantPricesSwitch');
    if (switchEl && switchEl.checked && newVal > 0) {
      document.querySelectorAll('.price-variant-input').forEach(input => {
        input.value = newVal;
        const subId = input.name.replace('variant_price[', 'edit_price_sub_').replace(']', '');
        const sub = document.getElementById(subId);
        if (sub) {
          sub.innerText = '= ' + parseInt(newVal).toLocaleString('vi-VN') + ' ₫';
        }
      });
    }
  }

  function syncPriceToAllVariants() {
    const mainVal = document.getElementById('adminProductPriceInput')?.value;
    if (!mainVal || mainVal <= 0) {
      alert('Vui lòng nhập giá bán hợp lệ trước.');
      return;
    }
    document.querySelectorAll('.price-variant-input').forEach(input => {
      input.value = mainVal;
      const subId = input.name.replace('variant_price[', 'edit_price_sub_').replace(']', '');
      const sub = document.getElementById(subId);
      if (sub) {
        sub.innerText = '= ' + parseInt(mainVal).toLocaleString('vi-VN') + ' ₫';
      }
    });
    const switchEl = document.getElementById('syncVariantPricesSwitch');
    if (switchEl) switchEl.checked = true;

    // Toast nhỏ thông báo
    if (typeof showToast === 'function') {
      showToast('Đã áp dụng giá ' + parseInt(mainVal).toLocaleString('vi-VN') + '₫ cho tất cả biến thể', 'success');
    }
  }
</script>
@endpush
@endsection
