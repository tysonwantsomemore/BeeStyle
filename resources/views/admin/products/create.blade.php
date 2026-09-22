@extends('layouts.admin')

@section('title', 'Tạo Mới Sản Phẩm | BeeStyle Admin')

@push('styles')
<style>
  /* ========================================================================= */
  /* GIAO DIỆN QUẢN TRỊ SẢN PHẨM CAO CẤP - TINH GỌN, SANG TRỌNG & DỄ NHÌN     */
  /* ========================================================================= */
  .admin-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    margin-bottom: 24px;
    overflow: hidden;
  }
  .admin-card-header {
    padding: 14px 20px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .admin-card-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
  }
  .form-control, .form-select {
    font-size: 13.5px;
    border-color: #cbd5e1;
    border-radius: 8px;
    padding: 8px 12px;
    transition: all 0.15s ease;
  }
  .form-control:focus, .form-select:focus {
    border-color: #f59e0b !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15) !important;
  }
  .helper-text {
    font-size: 12px;
    color: #64748b;
    margin-top: 4px;
  }

  /* Rich Text Editor Styling */
  .rich-editor-wrapper {
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    overflow: hidden;
    background: #ffffff;
    transition: border-color 0.2s, box-shadow 0.2s;
  }
  .rich-editor-wrapper:focus-within {
    border-color: #f59e0b !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15) !important;
  }
  .rich-editor-toolbar {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 6px 8px;
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
  }
  .rich-editor-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.18s ease;
  }
  .rich-editor-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
    border-color: #94a3b8;
  }
  /* KHI ĐƯỢC CHỌN / ĐANG KÍCH HOẠT: HIỆN LÊN KHÁC BIỆT CỰC KỲ DỄ NHÌN */
  .rich-editor-btn.active {
    background: #0f172a !important; /* Nền xanh đen sâu sang trọng */
    color: #f59e0b !important; /* Vàng hổ phách rực rỡ */
    border-color: #0f172a !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4) !important;
    transform: translateY(-1px) scale(1.06);
    font-weight: 800 !important;
  }
  .rich-editor-select {
    height: 32px;
    font-size: 12px;
    padding: 2px 8px;
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    background: #ffffff;
    color: #334155;
    font-weight: 600;
    transition: all 0.18s ease;
  }
  .rich-editor-select:focus {
    border-color: #f59e0b;
    outline: none;
  }
  .rich-editor-select.active {
    background-color: #fef3c7 !important;
    border-color: #f59e0b !important;
    color: #92400e !important;
    font-weight: 700 !important;
    box-shadow: 0 0 0 2.5px rgba(245, 158, 11, 0.3) !important;
  }
  .rich-editor-color-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    padding: 2px;
    background: #ffffff;
    cursor: pointer;
    height: 32px;
    width: 32px;
    transition: all 0.18s ease;
  }
  .rich-editor-color-wrapper.active {
    border-color: #f59e0b !important;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4) !important;
    transform: translateY(-1px);
  }
  .rich-editor-color-picker {
    width: 24px;
    height: 24px;
    border: none;
    padding: 0;
    cursor: pointer;
    background: transparent;
    border-radius: 4px;
  }
  .rich-editor-box {
    padding: 12px 14px;
    background: #ffffff;
    outline: none;
    font-size: 13.5px;
    line-height: 1.65;
    color: #1e293b;
    overflow-y: auto;
  }
  @media (min-width: 992px) {
    .border-end-lg {
      border-right: 1px solid #e2e8f0 !important;
    }
  }
  .rich-editor-box[placeholder]:empty:before {
    content: attr(placeholder);
    color: #94a3b8;
    pointer-events: none;
    display: block;
  }

  /* Color swatches */
  .color-swatch-item {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #e2e8f0;
    position: relative;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    transition: all 0.2s ease;
  }
  .color-swatch-item:hover {
    transform: scale(1.12);
    border-color: #0f172a;
  }
  .color-swatch-item.active {
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.35);
  }
  .color-swatch-item.active::after {
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
  .color-swatch-item[data-color="Trắng"].active::after,
  .color-swatch-item[data-color="Beige"].active::after,
  .color-swatch-item[data-color="Vàng Cát"].active::after {
    color: #0f172a !important;
    text-shadow: none !important;
  }
  .tag-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    color: #1e293b;
  }
  .tag-badge .btn-remove {
    cursor: pointer;
    color: #94a3b8;
  }
  .tag-badge .btn-remove:hover {
    color: #ef4444;
  }
  /* Size button */
  .size-btn {
    min-width: 44px;
    height: 38px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    transition: all 0.15s ease;
  }
  .size-btn:hover {
    border-color: #0f172a;
    color: #0f172a;
  }
  .size-btn.active {
    background: #0f172a !important;
    border-color: #0f172a !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
  }
  /* Upload Box */
  .dropzone-box {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .dropzone-box:hover {
    border-color: #f59e0b;
    background: #fffdf5;
  }
  /* Radio card */
  .radio-card {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    background: #ffffff;
  }
  .radio-card:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
  }
  .radio-card.active {
    border-color: #10b981;
    background: #f0fdf4;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
  }
  .radio-card.inactive {
    border-color: #94a3b8;
    background: #f8fafc;
  }
  /* Matrix table */
  .matrix-table-wrap {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow-x: auto;
    background: #ffffff;
  }
  .matrix-table th {
    background-color: #f8fafc;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: #475569;
    padding: 10px 14px;
    border-bottom: 1.5px solid #e2e8f0;
    letter-spacing: 0.03em;
  }
  .matrix-table td {
    padding: 10px 14px;
    font-size: 13px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
  }
  .matrix-table tr:hover td {
    background-color: #fffdf5;
  }

  /* Shortened Live Preview Card */
  .storefront-card-preview {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.25s ease;
  }
  .storefront-card-preview:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
  }
  .storefront-img-compact {
    height: 140px;
    width: 100%;
    object-fit: cover;
    background: #f1f5f9;
  }

  .price-variant-input {
    font-size: 13.5px;
    height: 38px;
    border-radius: 8px 0 0 8px !important;
    border: 1.5px solid #cbd5e1;
    font-weight: 700;
  }
  .price-variant-addon {
    font-size: 12px;
    font-weight: 800;
    border-radius: 0 8px 8px 0 !important;
    border: 1.5px solid #cbd5e1;
    border-left: 0;
    background-color: #f8fafc;
  }
  .price-variant-subtext {
    font-size: 10.5px;
    font-weight: 700;
    margin-top: 2px;
    color: #2563eb;
    min-height: 15px;
  }
</style>
@endpush

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm px-2.5 py-1 bg-white border text-dark fw-semibold" title="Quay lại danh sách">
        <i class="fa-solid fa-arrow-left me-1"></i> Tất Cả Sản Phẩm
      </a>
      <span class="badge bg-warning text-dark fs-10 fw-bold px-2 py-1 shadow-xs"><i class="fa-solid fa-shirt me-1"></i> BỘ SƯU TẬP BEESTYLE 2026</span>
    </div>
    <h2 class="mb-0 text-dark fw-bolder fs-4" style="color: #0f172a !important;">Tạo Mới Sản Phẩm Thời Trang Nam</h2>
    <p class="text-muted mb-0 fw-medium" style="color: #475569 !important;">Nhập thông tin sản phẩm, quản lý biến thể đa kích cỡ, thiết lập giá bán và thư viện ảnh lookbook</p>
  </div>
  <div class="col-auto">
    <div class="d-flex align-items-center gap-2">
      <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1" id="headerStatusBadge">
        <i class="fa-solid fa-circle-check me-1"></i> ĐANG MỞ BÁN
      </span>
      <button type="button" onclick="submitProductForm('inactive', 'save_index')" class="btn btn-outline-secondary fw-bold px-3 py-2 bg-white shadow-xs" style="font-size: 13px;">
        <i class="fa-solid fa-floppy-disk me-1.5"></i> Lưu Bản Nháp
      </button>
      <button type="button" onclick="submitProductForm('active', 'save_index')" class="btn btn-primary fw-bold px-3.5 py-2 shadow-xs" style="font-size: 13px;">
        <i class="fa-solid fa-cloud-arrow-up me-1.5"></i> Đăng Mở Bán
      </button>
    </div>
  </div>
</div>

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
    <div class="d-flex align-items-start gap-2">
      <i class="fa-solid fa-triangle-exclamation fs-5 mt-0.5"></i>
      <div>
        <strong class="d-block mb-1">Vui lòng kiểm tra lại các trường thông tin:</strong>
        <ul class="mb-0 ps-3" style="font-size: 13px;">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
  @csrf
  <input type="hidden" name="status" id="formStatusInput" value="{{ old('status', 'active') }}">
  <input type="hidden" name="save_action" id="formSaveActionInput" value="save_index">

  <!-- ========================================================================= -->
  <!-- PHẦN 1: THÔNG TIN CƠ BẢN, GIÁ CẢ & THIẾT LẬP KHO (2 CỘT CÂN ĐỐI)         -->
  <!-- ========================================================================= -->
  <div class="row g-4 mb-4">
    <!-- CỘT TRÁI (8 COLS): KHỐI 1 (THÔNG TIN CHÍNH) & KHỐI 2 (GIÁ CẢ) -->
    <div class="col-12 col-lg-8 d-flex flex-column gap-4">

      <!-- KHỐI 1: THÔNG TIN CƠ BẢN & ĐỊNH DANH -->
      <div class="admin-card mb-0">
        <div class="admin-card-header">
          <h5 class="admin-card-title">
            <i class="fa-solid fa-circle-info text-primary"></i>
            1. Thông Tin Cơ Bản &amp; Định Danh
          </h5>
          <span class="badge bg-light text-muted border" style="font-size: 11px;">Bắt buộc điền</span>
        </div>
        <div class="p-3 p-md-4">
          
          <!-- Tên sản phẩm -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label mb-0">Tên Sản Phẩm Thời Trang <span class="text-danger">*</span></label>
              <span class="text-muted" style="font-size: 11.5px;" id="nameCharCount">0 / 255 ký tự</span>
            </div>
            <input type="text" 
                   name="name" 
                   id="productNameInput" 
                   class="form-control fw-semibold" 
                   style="font-size: 14px; height: 42px;"
                   value="{{ old('name') }}" 
                   placeholder="Nhập tên sản phẩm (VD: Áo Polo Nam Cotton Dệt Tổ Ong Phom Slimfit...)" 
                   required 
                   oninput="handleNameInput(this.value)">
            
            <!-- Gợi ý nhanh danh xưng -->
            <div class="d-flex align-items-center gap-1.5 flex-wrap mt-2">
              <span class="text-muted me-1" style="font-size: 12px;">Gợi ý nhanh:</span>
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11.5px;" onclick="insertNamePrefix('Áo Polo Nam')">+ Áo Polo</button>
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11.5px;" onclick="insertNamePrefix('Áo Sơ Mi Lụa')">+ Áo Sơ Mi</button>
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11.5px;" onclick="insertNamePrefix('Áo Blazer May Đo')">+ Áo Blazer</button>
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11.5px;" onclick="insertNamePrefix('Áo Thun Form Boxy')">+ Áo Thun</button>
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11.5px;" onclick="insertNamePrefix('Quần Âu Sartorial')">+ Quần Âu</button>
            </div>

            <!-- Đường dẫn Slug xem trước -->
            <div class="mt-2 p-2 bg-light rounded-2 border d-flex align-items-center gap-2" style="font-size: 12px;">
              <i class="fa-solid fa-link text-muted"></i>
              <span class="text-muted">Đường dẫn chi tiết:</span>
              <span class="text-primary fw-semibold font-monospace" id="slugPreviewDisplay">beestyle.vn/san-pham/chua-co-ten-san-pham</span>
            </div>
          </div>

          <!-- 3 cột: SKU (NHẬP TAY) - Danh mục - Thương hiệu -->
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label mb-1">Mã SKU Sản Phẩm <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light text-muted font-monospace fw-bold" style="font-size: 13px;">#</span>
                <input type="text" 
                       name="sku" 
                       id="skuInput" 
                       class="form-control font-monospace text-uppercase fw-bold" 
                       style="font-size: 13px;"
                       value="{{ old('sku') }}" 
                       placeholder="Nhập mã SKU thủ công (VD: POLO-01, SM-LUA-02...)" 
                       required 
                       oninput="handleSkuManualInput(this.value)">
              </div>
              <span class="helper-text"><i class="fa-solid fa-keyboard text-secondary me-1"></i>Quản trị viên tự nhập mã định danh SKU.</span>
            </div>

            <div class="col-md-4">
              <label class="form-label mb-1">Danh Mục Thời Trang <span class="text-danger">*</span></label>
              <select name="category_id" id="categorySelect" class="form-select" required onchange="handleCategoryChange(this)">
                <option value="" {{ old('category_id') ? '' : 'selected' }}>-- Chọn danh mục --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label mb-1">Thương Hiệu</label>
              <select name="brand_id" id="brandSelect" class="form-select" onchange="updatePreviewCard()">
                <option value="">-- BeeStyle Atelier (Mặc định) --</option>
                @foreach($brands as $brand)
                  <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Mô tả tóm tắt ngắn (Có bộ công cụ chỉnh Font, Size, Kiểu chữ) -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label mb-0">Mô Tả Tóm Tắt (Short Description)</label>
              <button type="button" class="btn btn-link p-0 text-primary text-decoration-none fw-bold" style="font-size: 11.5px;" onclick="fillShortDescTemplate()">
                <i class="fa-regular fa-file-lines me-1"></i>Chèn mẫu tóm tắt chuẩn
              </button>
            </div>
            
            <div class="rich-editor-wrapper">
              <!-- Toolbar mô tả tóm tắt -->
              <div class="rich-editor-toolbar">
                <select class="rich-editor-select font-select" data-editor="shortDescEditor" onchange="applyFontFamily(this.value, 'shortDescEditor', this)" title="Chọn Phông chữ">
                  <option value="">Phông chữ (Mặc định)</option>
                  <option value="'Plus Jakarta Sans', sans-serif">Plus Jakarta Sans</option>
                  <option value="Arial, sans-serif">Arial</option>
                  <option value="'Times New Roman', serif">Times New Roman</option>
                  <option value="Roboto, sans-serif">Roboto</option>
                  <option value="Georgia, serif">Georgia</option>
                  <option value="Tahoma, sans-serif">Tahoma</option>
                  <option value="Verdana, sans-serif">Verdana</option>
                </select>

                <select class="rich-editor-select size-select" data-editor="shortDescEditor" onchange="applyFontSize(this.value, 'shortDescEditor', this)" title="Chọn Cỡ chữ">
                  <option value="">Cỡ chữ (14px)</option>
                  <option value="12px">12px (Nhỏ)</option>
                  <option value="14px">14px (Chuẩn)</option>
                  <option value="16px">16px (Vừa)</option>
                  <option value="18px">18px (Lớn)</option>
                  <option value="20px">20px (Tiêu đề phụ)</option>
                  <option value="24px">24px (Tiêu đề chính)</option>
                </select>

                <div class="vr mx-1 my-auto" style="height: 18px;"></div>

                <button type="button" class="rich-editor-btn" data-command="bold" data-editor="shortDescEditor" onclick="formatText('bold', null, 'shortDescEditor', this)" title="In đậm (Bold)"><i class="fa-solid fa-bold"></i></button>
                <button type="button" class="rich-editor-btn" data-command="italic" data-editor="shortDescEditor" onclick="formatText('italic', null, 'shortDescEditor', this)" title="In nghiêng (Italic)"><i class="fa-solid fa-italic"></i></button>
                <button type="button" class="rich-editor-btn" data-command="underline" data-editor="shortDescEditor" onclick="formatText('underline', null, 'shortDescEditor', this)" title="Gạch chân (Underline)"><i class="fa-solid fa-underline"></i></button>
                <button type="button" class="rich-editor-btn" data-command="strikeThrough" data-editor="shortDescEditor" onclick="formatText('strikeThrough', null, 'shortDescEditor', this)" title="Gạch ngang"><i class="fa-solid fa-strikethrough"></i></button>
                
                <div class="rich-editor-color-wrapper" title="Chọn màu chữ">
                  <input type="color" class="rich-editor-color-picker" data-editor="shortDescEditor" value="#1e293b" onchange="handleColorChange(this.value, 'shortDescEditor', this)">
                </div>

                <div class="vr mx-1 my-auto" style="height: 18px;"></div>

                <button type="button" class="rich-editor-btn" data-command="justifyLeft" data-editor="shortDescEditor" onclick="formatText('justifyLeft', null, 'shortDescEditor', this)" title="Căn trái"><i class="fa-solid fa-align-left"></i></button>
                <button type="button" class="rich-editor-btn" data-command="justifyCenter" data-editor="shortDescEditor" onclick="formatText('justifyCenter', null, 'shortDescEditor', this)" title="Căn giữa"><i class="fa-solid fa-align-center"></i></button>
                <button type="button" class="rich-editor-btn" data-command="justifyRight" data-editor="shortDescEditor" onclick="formatText('justifyRight', null, 'shortDescEditor', this)" title="Căn phải"><i class="fa-solid fa-align-right"></i></button>
                <button type="button" class="rich-editor-btn" data-command="insertUnorderedList" data-editor="shortDescEditor" onclick="formatText('insertUnorderedList', null, 'shortDescEditor', this)" title="Danh sách gạch đầu dòng"><i class="fa-solid fa-list-ul"></i></button>
                <button type="button" class="rich-editor-btn" data-command="insertOrderedList" data-editor="shortDescEditor" onclick="formatText('insertOrderedList', null, 'shortDescEditor', this)" title="Danh sách số"><i class="fa-solid fa-list-ol"></i></button>
                <button type="button" class="rich-editor-btn" data-command="removeFormat" data-editor="shortDescEditor" onclick="clearFormatting('shortDescEditor', this)" title="Xóa toàn bộ định dạng"><i class="fa-solid fa-eraser"></i></button>
              </div>

              <!-- Vùng soạn thảo tóm tắt -->
              <div id="shortDescEditor" 
                   class="rich-editor-box" 
                   contenteditable="true" 
                   style="min-height: 80px; max-height: 200px;" 
                   placeholder="Tóm tắt chất liệu, form dáng, tính năng nổi bật... (Có thể chỉnh font, cỡ chữ, in đậm...)">{!! old('short_description') !!}</div>
            </div>
            <textarea name="short_description" id="shortDescInput" class="d-none">{{ old('short_description') }}</textarea>
          </div>

          <!-- Mô tả chi tiết sản phẩm (Có bộ công cụ chỉnh Font, Size, Kiểu chữ & Mẫu Mô Tả Chuẩn) -->
          <div class="mb-0">
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">
              <label class="form-label mb-0">Mô Tả Chi Tiết Sản Phẩm &amp; Hướng Dẫn May Đo</label>
              <button type="button" class="btn btn-outline-primary btn-sm py-0.5 px-2.5 border fw-bold" style="font-size: 11.5px;" onclick="insertStandardDetailedDesc()">
                <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Chèn Mẫu Mô Tả Chuẩn
              </button>
            </div>

            <div class="rich-editor-wrapper">
              <!-- Toolbar mô tả chi tiết -->
              <div class="rich-editor-toolbar">
                <select class="rich-editor-select font-select" data-editor="detailedDescEditor" onchange="applyFontFamily(this.value, 'detailedDescEditor', this)" title="Chọn Phông chữ">
                  <option value="">Phông chữ (Mặc định)</option>
                  <option value="'Plus Jakarta Sans', sans-serif">Plus Jakarta Sans</option>
                  <option value="Arial, sans-serif">Arial</option>
                  <option value="'Times New Roman', serif">Times New Roman</option>
                  <option value="Roboto, sans-serif">Roboto</option>
                  <option value="Georgia, serif">Georgia</option>
                  <option value="Tahoma, sans-serif">Tahoma</option>
                  <option value="Verdana, sans-serif">Verdana</option>
                </select>

                <select class="rich-editor-select size-select" data-editor="detailedDescEditor" onchange="applyFontSize(this.value, 'detailedDescEditor', this)" title="Chọn Cỡ chữ">
                  <option value="">Cỡ chữ (14px)</option>
                  <option value="12px">12px (Nhỏ)</option>
                  <option value="14px">14px (Chuẩn)</option>
                  <option value="16px">16px (Vừa)</option>
                  <option value="18px">18px (Lớn)</option>
                  <option value="20px">20px (Tiêu đề phụ)</option>
                  <option value="24px">24px (Tiêu đề chính)</option>
                </select>

                <div class="vr mx-1 my-auto" style="height: 18px;"></div>

                <button type="button" class="rich-editor-btn" data-command="bold" data-editor="detailedDescEditor" onclick="formatText('bold', null, 'detailedDescEditor', this)" title="In đậm (Bold)"><i class="fa-solid fa-bold"></i></button>
                <button type="button" class="rich-editor-btn" data-command="italic" data-editor="detailedDescEditor" onclick="formatText('italic', null, 'detailedDescEditor', this)" title="In nghiêng (Italic)"><i class="fa-solid fa-italic"></i></button>
                <button type="button" class="rich-editor-btn" data-command="underline" data-editor="detailedDescEditor" onclick="formatText('underline', null, 'detailedDescEditor', this)" title="Gạch chân (Underline)"><i class="fa-solid fa-underline"></i></button>
                <button type="button" class="rich-editor-btn" data-command="strikeThrough" data-editor="detailedDescEditor" onclick="formatText('strikeThrough', null, 'detailedDescEditor', this)" title="Gạch ngang"><i class="fa-solid fa-strikethrough"></i></button>
                
                <div class="rich-editor-color-wrapper" title="Chọn màu chữ">
                  <input type="color" class="rich-editor-color-picker" data-editor="detailedDescEditor" value="#1e293b" onchange="handleColorChange(this.value, 'detailedDescEditor', this)">
                </div>

                <div class="vr mx-1 my-auto" style="height: 18px;"></div>

                <button type="button" class="rich-editor-btn" data-command="justifyLeft" data-editor="detailedDescEditor" onclick="formatText('justifyLeft', null, 'detailedDescEditor', this)" title="Căn trái"><i class="fa-solid fa-align-left"></i></button>
                <button type="button" class="rich-editor-btn" data-command="justifyCenter" data-editor="detailedDescEditor" onclick="formatText('justifyCenter', null, 'detailedDescEditor', this)" title="Căn giữa"><i class="fa-solid fa-align-center"></i></button>
                <button type="button" class="rich-editor-btn" data-command="justifyRight" data-editor="detailedDescEditor" onclick="formatText('justifyRight', null, 'detailedDescEditor', this)" title="Căn phải"><i class="fa-solid fa-align-right"></i></button>
                <button type="button" class="rich-editor-btn" data-command="insertUnorderedList" data-editor="detailedDescEditor" onclick="formatText('insertUnorderedList', null, 'detailedDescEditor', this)" title="Danh sách gạch đầu dòng"><i class="fa-solid fa-list-ul"></i></button>
                <button type="button" class="rich-editor-btn" data-command="insertOrderedList" data-editor="detailedDescEditor" onclick="formatText('insertOrderedList', null, 'detailedDescEditor', this)" title="Danh sách số"><i class="fa-solid fa-list-ol"></i></button>
                <button type="button" class="rich-editor-btn" data-command="removeFormat" data-editor="detailedDescEditor" onclick="clearFormatting('detailedDescEditor', this)" title="Xóa toàn bộ định dạng"><i class="fa-solid fa-eraser"></i></button>
              </div>

              <!-- Vùng soạn thảo chi tiết -->
              <div id="detailedDescEditor" 
                   class="rich-editor-box" 
                   contenteditable="true" 
                   style="min-height: 180px; max-height: 380px;" 
                   placeholder="Chi tiết nguồn gốc sợi dệt, quy trình may ráp, hướng dẫn phối đồ và mẹo bảo quản sản phẩm... (Có thể chỉnh font, cỡ chữ, in đậm...)">{!! old('description') !!}</div>
            </div>
            <textarea name="description" id="detailedDescInput" class="d-none">{{ old('description') }}</textarea>
          </div>

        </div>
      </div>

    </div>

    <!-- CỘT PHẢI (4 COLS): QUẢN LÝ NHẬP KHO, LIVE PREVIEW GỌN GÀNG & NÚT XUẤT BẢN -->
    <div class="col-12 col-lg-4 d-flex flex-column gap-3">

      <!-- QUẢN LÝ NHẬP KHO & TRẠNG THÁI -->
      <div class="admin-card mb-0">
        <div class="admin-card-header">
          <h5 class="admin-card-title">
            <i class="fa-solid fa-boxes-stacked text-primary"></i>
            Quản Lý Nhập Kho &amp; Vận Hành
          </h5>
        </div>
        <div class="p-3">
          
          <!-- Số lượng nhập (Đã sửa từ Tổng Số Lượng Tồn Kho) -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label mb-0 fw-bold text-dark">Số Lượng Nhập <span class="text-danger">*</span></label>
              <span class="text-muted" style="font-size: 11.5px;" id="stockStatusText">Chưa nhập kho</span>
            </div>
            <div class="input-group">
              <input type="number" 
                     name="stock" 
                     id="productStockInput" 
                     class="form-control fw-bold font-monospace" 
                     style="font-size: 15px;"
                     value="{{ old('stock') }}" 
                     required 
                     min="0" 
                     placeholder="Ví dụ: 100"
                     oninput="handleStockChange(this.value)">
              <span class="input-group-text fw-semibold">Cái</span>
            </div>
            <!-- Phím tắt số lượng nhập -->
            <div class="d-flex gap-1.5 flex-wrap mt-1.5">
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="setQuickStock(100)">100 cái</button>
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="setQuickStock(500)">500 cái</button>
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="setQuickStock(1000)">1.000 cái</button>
              <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="setQuickStock(2000)">2.000 cái</button>
            </div>
          </div>

          <!-- Trạng thái kinh doanh (Radio cards) -->
          <div class="mb-3">
            <label class="form-label mb-1.5">Trạng Thái Kinh Doanh</label>
            <div class="row g-2">
              <div class="col-6">
                <div class="radio-card active" id="statusActiveCard" onclick="setStatusOption('active')">
                  <i class="fa-solid fa-circle-check text-success fs-5 mb-1 d-block"></i>
                  <strong class="d-block text-dark" style="font-size: 13px;">Đang Mở Bán</strong>
                  <span class="text-muted" style="font-size: 11px;">Hiển thị trên Web</span>
                </div>
              </div>
              <div class="col-6">
                <div class="radio-card inactive" id="statusInactiveCard" onclick="setStatusOption('inactive')">
                  <i class="fa-solid fa-eye-slash text-secondary fs-5 mb-1 d-block"></i>
                  <strong class="d-block text-dark" style="font-size: 13px;">Tạm Ẩn / Nháp</strong>
                  <span class="text-muted" style="font-size: 11px;">Khách chưa thấy</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Huy hiệu tiếp thị -->
          <div class="pt-2 border-top">
            <label class="form-label mb-1.5 d-block">Huy Hiệu Tiếp Thị</label>
            <div class="form-check form-switch mb-1.5">
              <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" checked onchange="updatePreviewCard()">
              <label class="form-check-label fw-semibold text-dark" style="font-size: 12.5px;" for="is_featured">
                Sản phẩm nổi bật (Featured)
              </label>
            </div>
            <div class="form-check form-switch mb-1.5">
              <input class="form-check-input" type="checkbox" name="is_new" value="1" id="is_new" checked onchange="updatePreviewCard()">
              <label class="form-check-label fw-semibold text-dark" style="font-size: 12.5px;" for="is_new">
                Hàng mới về (New Arrival)
              </label>
            </div>
            <div class="form-check form-switch mb-0">
              <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="is_best_seller" onchange="updatePreviewCard()">
              <label class="form-check-label fw-semibold text-dark" style="font-size: 12.5px;" for="is_best_seller">
                Bán chạy nhất (Best Seller)
              </label>
            </div>
          </div>

        </div>
      </div>

      <!-- MÔ PHỎNG THẺ NGOÀI WEB (ẢNH THU NGẮN GỌN GÀNG, KHÔNG DÀI TRÀN) -->
      <div class="admin-card mb-0">
        <div class="admin-card-header py-2.5">
          <h5 class="admin-card-title fs-9">
            <i class="fa-solid fa-desktop text-primary"></i>
            Mô Phỏng Thẻ Ngoài Web
          </h5>
          <span class="badge bg-dark text-white font-monospace" style="font-size: 10px;">LIVE PREVIEW</span>
        </div>
        <div class="p-3">
          <div class="storefront-card-preview mx-auto">
            <!-- Ảnh Preview rút ngắn (Chiều cao 140px, tinh tế và cân đối) -->
            <div class="position-relative overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 140px; width: 100%;">
              <img id="cardPreviewImg" src="" alt="Preview" class="w-100 h-100 object-fit-cover" style="display: none;">
              <div id="cardPreviewEmptyPlaceholder" class="text-center p-2 text-muted">
                <i class="fa-regular fa-image fs-3 text-secondary mb-1 opacity-40"></i>
                <span class="d-block text-muted fw-bold" style="font-size: 11px;">BeeStyle Atelier</span>
              </div>
              
              <!-- Badges trên ảnh -->
              <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1 pointer-events-none">
                <span id="cardBadgeNew" class="badge bg-dark text-white px-2 py-0.5 fw-bold" style="font-size: 9.5px; display: none;">MỚI</span>
                <span id="cardBadgeFeatured" class="badge bg-warning text-dark px-2 py-0.5 fw-bold" style="font-size: 9.5px; display: none;">ATELIER</span>
                <span id="cardBadgeDiscount" class="badge bg-danger text-white px-2 py-0.5 fw-bold" style="font-size: 9.5px; display: none;">-0%</span>
              </div>
            </div>

            <!-- Chữ bên dưới card -->
            <div class="p-2.5 text-start">
              <span class="text-uppercase text-muted fw-bold d-block mb-1" id="cardCategoryText" style="font-size: 10px;">CHƯA CHỌN DANH MỤC</span>
              <h6 class="fw-bold text-dark mb-1 text-truncate" id="cardTitleText" style="font-size: 13px;">Chưa có tên sản phẩm...</h6>
              
              <!-- Chấm màu swatch -->
              <div class="d-flex align-items-center gap-1 mb-1.5" id="cardColorDots">
                <!-- Rendered dynamically -->
              </div>

              <!-- Giá tiền -->
              <div class="d-flex align-items-baseline gap-1.5">
                <span class="fw-black text-danger font-monospace" id="cardPriceText" style="font-size: 14px;">0₫</span>
                <span class="text-muted text-decoration-line-through font-monospace" id="cardOriginalPriceText" style="font-size: 11px; display: none;">0₫</span>
              </div>

              <!-- Tồn kho & Size -->
              <div class="mt-2 pt-1.5 border-top d-flex align-items-center justify-content-between" style="font-size: 11px;">
                <span class="text-muted" id="cardStockText"><i class="fa-solid fa-circle me-1 fs-11 text-secondary"></i>Chưa có kho</span>
                <span class="text-muted fw-bold" id="cardVariantCountText">0 size</span>
              </div>
            </div>
          </div>

          <!-- Nút hành động nhanh trên sidebar -->
          <div class="mt-3">
            <button type="button" onclick="submitProductForm('active', 'save_index')" class="btn btn-primary w-100 py-2 fw-bold shadow-xs mb-2" style="font-size: 13px;">
              <i class="fa-solid fa-cloud-arrow-up me-1.5"></i> Đăng Mở Bán Sản Phẩm
            </button>
            <button type="button" onclick="submitProductForm('inactive', 'save_index')" class="btn btn-outline-secondary w-100 py-1.5 bg-white" style="font-size: 12.5px;">
              <i class="fa-solid fa-floppy-disk me-1.5"></i> Lưu Bản Nháp
            </button>
          </div>

        </div>
      </div>

    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- KHỐI 2: THIẾT LẬP GIÁ BÁN & THỜI HẠN KHUYẾN MÃI (FULL-WIDTH COL-12)       -->
  <!-- ========================================================================= -->
  <div class="row g-4 mb-4">
    <div class="col-12">
      <div class="admin-card mb-0">
        <div class="admin-card-header">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-tag text-warning fs-8"></i>
            <h5 class="admin-card-title mb-0 fs-8">
              2. Thiết Lập Giá Bán &amp; Thời Hạn Khuyến Mãi (Sale Period)
            </h5>
            <span class="badge bg-danger text-white fw-bold px-2 py-0.5" id="discountBadge" style="font-size: 11px; display: none;">Giảm 0%</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-muted border" style="font-size: 11px;">
              <i class="fa-solid fa-arrows-left-right me-1"></i>Trải rộng toàn màn hình
            </span>
          </div>
        </div>
        <div class="p-3 p-md-4">
          <div class="row g-4">
            <!-- CỘT 1: GIÁ BÁN KHÁCH MUA -->
            <div class="col-12 col-lg-4 border-end-lg">
              <label class="form-label mb-1.5 fw-bold text-dark d-flex justify-content-between">
                <span>Giá Bán Khách Mua (VNĐ) <span class="text-danger">*</span></span>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1.5 py-0.5 font-monospace" style="font-size: 10.5px;">GIÁ THỰC THU</span>
              </label>
              <div class="input-group">
                <input type="number" 
                       name="price" 
                       id="productPriceInput" 
                       class="form-control fw-bold text-danger font-monospace" 
                       style="font-size: 17px; height: 44px;"
                       value="{{ old('price') }}" 
                       placeholder="Nhập giá bán (VD: 350000)" 
                       required 
                       min="0" 
                       step="1000" 
                       oninput="handlePriceCalculation()">
                <span class="input-group-text fw-bold text-danger bg-danger-subtle border-danger-subtle font-monospace" style="font-size: 15px;">₫</span>
              </div>
              <div class="mt-2 p-2.5 bg-light rounded-2 border">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-muted fw-semibold" style="font-size: 12px;">Bằng chữ:</span>
                  <span class="fw-bold text-dark font-monospace" style="font-size: 13px;" id="formattedPriceText">Chưa nhập giá bán</span>
                </div>
                <div class="text-success fw-bold font-monospace mt-1.5" style="font-size: 11.5px;">
                  <i class="fa-solid fa-link me-1"></i>Tự động liên kết vào Ma trận biến thể bên dưới
                </div>
              </div>
            </div>

            <!-- CỘT 2: GIÁ GỐC NIÊM YẾT & CHIẾT KHẤU -->
            <div class="col-12 col-lg-4 border-end-lg">
              <label class="form-label mb-1.5 fw-bold text-dark d-flex justify-content-between">
                <span>Giá Gốc Niêm Yết (VNĐ)</span>
                <span class="badge bg-secondary-subtle text-secondary border px-1.5 py-0.5 font-monospace" style="font-size: 10.5px;">GIÁ GẠCH ĐI</span>
              </label>
              <div class="input-group">
                <input type="number" 
                       name="original_price" 
                       id="productOriginalPriceInput" 
                       class="form-control font-monospace fw-bold text-dark" 
                       style="font-size: 16px; height: 44px;"
                       value="{{ old('original_price') }}" 
                       placeholder="Giá gốc chưa giảm (VD: 450000)" 
                       min="0" 
                       step="1000" 
                       oninput="handlePriceCalculation()">
                <span class="input-group-text font-monospace bg-light">₫</span>
              </div>
              <div class="mt-2 p-2.5 bg-light rounded-2 border">
                <div class="d-flex justify-content-between align-items-center mb-1.5">
                  <span class="text-muted fw-semibold" style="font-size: 12px;">Mức giảm:</span>
                  <span class="fw-bold text-danger font-monospace" style="font-size: 12.5px;" id="savingsAmountText">Giá gốc niêm yết</span>
                </div>
                <div class="d-flex align-items-center justify-content-between gap-1">
                  <span class="text-muted" style="font-size: 11px;">Giảm nhanh:</span>
                  <div class="d-flex gap-1 flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm py-0.5 px-2 bg-white" style="font-size: 11px; font-weight: 700;" onclick="applyDiscountPercent(10)">-10%</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm py-0.5 px-2 bg-white" style="font-size: 11px; font-weight: 700;" onclick="applyDiscountPercent(20)">-20%</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm py-0.5 px-2 bg-white" style="font-size: 11px; font-weight: 700;" onclick="applyDiscountPercent(30)">-30%</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm py-0.5 px-2 bg-white" style="font-size: 11px; font-weight: 700;" onclick="applyDiscountPercent(50)">-50%</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- CỘT 3: LỊCH TRÌNH FLASH SALE & KHUNG GIỜ ÁP DỤNG -->
            <div class="col-12 col-lg-4">
              <div class="d-flex justify-content-between align-items-center mb-1.5">
                <label class="form-label mb-0 fw-bold text-dark">
                  <i class="fa-regular fa-calendar-check text-warning me-1"></i>Lịch Flash Sale &amp; Hạn Dùng
                </label>
                <div class="d-flex gap-1">
                  <button type="button" class="btn btn-light btn-sm py-0 px-1.5 border" style="font-size: 10.5px;" onclick="setSaleDuration(3)">3 Ngày</button>
                  <button type="button" class="btn btn-light btn-sm py-0 px-1.5 border" style="font-size: 10.5px;" onclick="setSaleDuration(7)">7 Ngày</button>
                  <button type="button" class="btn btn-light btn-sm py-0 px-1.5 border" style="font-size: 10.5px;" onclick="setSaleDuration(30)">1 Tháng</button>
                  <button type="button" class="btn btn-light btn-sm py-0 px-1.5 border text-danger" style="font-size: 10.5px;" onclick="clearSaleDates()">Xóa hạn</button>
                </div>
              </div>

              <div class="row g-2 mb-2">
                <div class="col-6">
                  <label class="form-label mb-1" style="font-size: 11px;">Bắt Đầu Sale:</label>
                  <input type="datetime-local" 
                         name="sale_starts_at" 
                         id="saleStartsAtInput" 
                         class="form-control form-control-sm font-monospace" 
                         value="{{ old('sale_starts_at') }}" 
                         onchange="updateSaleDateNotice()">
                </div>
                <div class="col-6">
                  <label class="form-label mb-1" style="font-size: 11px;">Kết Thúc Sale:</label>
                  <input type="datetime-local" 
                         name="sale_ends_at" 
                         id="saleEndsAtInput" 
                         class="form-control form-control-sm font-monospace" 
                         value="{{ old('sale_ends_at') }}" 
                         onchange="updateSaleDateNotice()">
                </div>
              </div>

              <div id="saleDateNotice">
                <div class="alert alert-light py-2 px-2.5 mb-0 border bg-white text-muted" style="font-size: 11.5px; line-height: 1.45;">
                  <i class="fa-solid fa-circle-info me-1 text-primary"></i>Nếu không đặt thời gian, giá bán trên sẽ áp dụng liên tục cho đến khi chỉnh sửa.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- PHẦN 2: CÁC KHỐI TOÀN MÀN HÌNH (COL-12 - DÀI RA RỘNG RÃI & SANG TRỌNG)    -->
  <!-- ========================================================================= -->
  <div class="row g-4 mb-4">
    
    <!-- KHỐI 3: THIẾT LẬP PHÂN LOẠI & BẢNG MA TRẬN BIẾN THỂ TỰ ĐỘNG (FULL WIDTH) -->
    <div class="col-12">
      <div class="admin-card mb-0">
        <div class="admin-card-header">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-layer-group text-primary fs-8"></i>
            <h5 class="admin-card-title mb-0 fs-8">
              3. Bảng Ma Trận Biến Thể Tự Động (Variants Matrix)
            </h5>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold fs-11" id="totalVariantsCounterBadge">
              0 biến thể
            </span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-muted border" style="font-size: 11px;">Trải rộng màn hình</span>
          </div>
        </div>
        <div class="p-3 p-md-4">
          
          <div class="row g-4 mb-4">
            <!-- 1. Bảng chọn Màu sắc -->
            <div class="col-12 col-lg-6 border-end-lg">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0 fw-bold text-dark">
                  Chọn Màu Sắc Cho Sản Phẩm <span class="text-danger">*</span>
                </label>
                <span class="text-muted" style="font-size: 11.5px;">(Bấm để chọn / bỏ chọn)</span>
              </div>
              
              <!-- Palette màu có sẵn -->
              <div class="d-flex align-items-center gap-2 flex-wrap mb-2.5" id="colorSwatchesContainer">
                <div class="color-swatch-item" data-color="Đen" data-code="#111827" style="background-color: #111827;" title="Đen" onclick="toggleColor('Đen', '#111827')"></div>
                <div class="color-swatch-item" data-color="Trắng" data-code="#FFFFFF" style="background-color: #FFFFFF;" title="Trắng" onclick="toggleColor('Trắng', '#FFFFFF')"></div>
                <div class="color-swatch-item" data-color="Xanh Navy" data-code="#1E3A8A" style="background-color: #1E3A8A;" title="Xanh Navy" onclick="toggleColor('Xanh Navy', '#1E3A8A')"></div>
                <div class="color-swatch-item" data-color="Xám Tro" data-code="#6B7280" style="background-color: #6B7280;" title="Xám Tro" onclick="toggleColor('Xám Tro', '#6B7280')"></div>
                <div class="color-swatch-item" data-color="Xám Ghi" data-code="#9CA3AF" style="background-color: #9CA3AF;" title="Xám Ghi" onclick="toggleColor('Xám Ghi', '#9CA3AF')"></div>
                <div class="color-swatch-item" data-color="Beige" data-code="#E5D9C5" style="background-color: #E5D9C5;" title="Beige" onclick="toggleColor('Beige', '#E5D9C5')"></div>
                <div class="color-swatch-item" data-color="Nâu Cafe" data-code="#78350F" style="background-color: #78350F;" title="Nâu Cafe" onclick="toggleColor('Nâu Cafe', '#78350F')"></div>
                <div class="color-swatch-item" data-color="Xanh Rêu" data-code="#365314" style="background-color: #365314;" title="Xanh Rêu" onclick="toggleColor('Xanh Rêu', '#365314')"></div>
                <div class="color-swatch-item" data-color="Xanh Mint" data-code="#6EE7B7" style="background-color: #6EE7B7;" title="Xanh Mint" onclick="toggleColor('Xanh Mint', '#6EE7B7')"></div>
                <div class="color-swatch-item" data-color="Đỏ Đô" data-code="#881337" style="background-color: #881337;" title="Đỏ Đô" onclick="toggleColor('Đỏ Đô', '#881337')"></div>
                <div class="color-swatch-item" data-color="Vàng Cát" data-code="#FDE047" style="background-color: #FDE047;" title="Vàng Cát" onclick="toggleColor('Vàng Cát', '#FDE047')"></div>
              </div>

              <!-- Thêm màu tự chọn -->
              <div class="input-group input-group-sm mb-2.5" style="max-width: 320px;">
                <input type="color" id="customColorPicker" class="form-control form-control-color p-0 border" value="#10b981" title="Bấm chọn mã màu tùy ý" style="width: 38px; height: 33px;">
                <input type="text" id="customColorNameInput" class="form-control" placeholder="Tên màu tùy chọn (VD: Xanh Olive...)" style="font-size: 12.5px;">
                <button type="button" class="btn btn-secondary fw-semibold" onclick="addCustomColor()">+ Thêm</button>
              </div>

              <!-- Danh sách chip màu đã chọn -->
              <div class="d-flex align-items-center gap-1.5 flex-wrap" id="activeColorChips">
                <!-- Rendered dynamically -->
              </div>
              <!-- Hidden inputs container for colors[] -->
              <div id="hiddenColorsContainer"></div>
            </div>

            <!-- 2. Bảng chọn Kích thước (Size) -->
            <div class="col-12 col-lg-6 ps-lg-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0 fw-bold text-dark">
                  Chọn Kích Thước (Size) <span class="text-danger">*</span>
                </label>
                <div class="d-flex gap-1.5">
                  <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="selectAllSizes(['S','M','L','XL','2XL'])">Chuẩn S-2XL</button>
                  <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="selectAllSizes(['M','L','XL'])">Bộ M-XL</button>
                  <button type="button" class="btn btn-light btn-sm py-0 px-2 border text-danger" style="font-size: 11px;" onclick="clearAllSizes()">Bỏ chọn</button>
                </div>
              </div>

              <!-- Dãy nút chọn size nhanh -->
              <div class="d-flex align-items-center gap-1.5 flex-wrap mb-2.5" id="sizeButtonsContainer">
                <button type="button" class="size-btn" data-size="S" onclick="toggleSize('S')">S</button>
                <button type="button" class="size-btn" data-size="M" onclick="toggleSize('M')">M</button>
                <button type="button" class="size-btn" data-size="L" onclick="toggleSize('L')">L</button>
                <button type="button" class="size-btn" data-size="XL" onclick="toggleSize('XL')">XL</button>
                <button type="button" class="size-btn" data-size="2XL" onclick="toggleSize('2XL')">2XL</button>
                <button type="button" class="size-btn" data-size="3XL" onclick="toggleSize('3XL')">3XL</button>
                <button type="button" class="size-btn" data-size="FreeSize" onclick="toggleSize('FreeSize')" style="min-width: 70px;">FreeSize</button>
              </div>

              <!-- Thêm size đặc biệt -->
              <div class="input-group input-group-sm mb-2.5" style="max-width: 250px;">
                <input type="text" id="customSizeInput" class="form-control" placeholder="Size riêng (VD: 29, 30, 31...)" style="font-size: 12.5px;">
                <button type="button" class="btn btn-secondary fw-semibold" onclick="addCustomSize()">+ Thêm</button>
              </div>

              <!-- Danh sách chip size đã chọn -->
              <div class="d-flex align-items-center gap-1.5 flex-wrap" id="activeSizeChips">
                <!-- Rendered dynamically -->
              </div>
              <!-- Hidden inputs container for sizes[] -->
              <div id="hiddenSizesContainer"></div>
            </div>
          </div>

          <!-- BẢNG MA TRẬN BIẾN THỂ TRẢI RỘNG TOÀN MÀN HÌNH -->
          <div class="pt-3 border-top">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
              <div>
                <h6 class="fw-bold text-dark mb-0 fs-9">
                  <i class="fa-solid fa-table-cells me-1 text-primary"></i>Danh Sách Chi Tiết Biến Thể Tự Sinh
                </h6>
                <span class="helper-text d-block">
                  Giá Bán Khách Mua và Giá Gốc Niêm Yết tự động liên kết với giá ở Khối 2. Quản trị viên có thể chỉnh riêng từng mẫu nếu cần.
                </span>
              </div>
              <div class="d-flex gap-1.5 flex-wrap">
                <button type="button" class="btn btn-outline-primary btn-sm py-1 px-2.5 border fw-bold shadow-xs" onclick="syncAllVariantPricesFromAbove()" title="Đồng bộ lại toàn bộ giá bán và giá gốc từ trên xuống">
                  <i class="fa-solid fa-arrows-rotate me-1"></i>Đồng Bộ Giá Từ Trên
                </button>
                <button type="button" class="btn btn-light btn-sm py-1 px-2 border" style="font-size: 11.5px;" onclick="setEachVariantStock(100)">Đặt Mỗi Mẫu = 100</button>
                <button type="button" class="btn btn-light btn-sm py-1 px-2 border" style="font-size: 11.5px;" onclick="setEachVariantStock(1000)">Đặt Mỗi Mẫu = 1.000</button>
                <button type="button" class="btn btn-light btn-sm py-1 px-2 border" style="font-size: 11.5px;" onclick="distributeStockEqually()">Chia Đều Tổng Nhập</button>
              </div>
            </div>

            <!-- Bảng ma trận biến thể (ĐÃ XÓA CỘT CHẤT LIỆU, CÓ CỘT GIÁ BÁN & GIÁ GỐC) -->
            <div class="matrix-table-wrap">
              <table class="table mb-0 matrix-table align-middle" id="variantMatrixTable">
                <thead>
                  <tr>
                    <th style="width: 15%; min-width: 130px;">Màu Sắc</th>
                    <th style="width: 10%; min-width: 80px;">Kích Thước</th>
                    <th style="width: 20%; min-width: 160px;">Mã SKU Con</th>
                    <th style="width: 24%; min-width: 200px;">
                      Giá Bán Khách Mua (VNĐ) <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 10px;">Liên kết trên</span>
                    </th>
                    <th style="width: 21%; min-width: 190px;">
                      Giá Gốc Niêm Yết (VNĐ) <span class="badge bg-secondary-subtle text-dark border ms-1" style="font-size: 10px;">Liên kết trên</span>
                    </th>
                    <th style="width: 10%; text-align: right; min-width: 95px;">Tồn Kho</th>
                  </tr>
                </thead>
                <tbody id="variantMatrixBody">
                  <!-- Rendered dynamically by JavaScript -->
                </tbody>
              </table>
            </div>

            <div class="mt-2.5 d-flex justify-content-between align-items-center flex-wrap">
              <small class="text-muted"><i class="fa-solid fa-circle-info text-primary me-1"></i>Mã SKU con được gắn tự động từ Mã SKU sản phẩm nhập tay bên trên.</small>
              <span class="text-muted fw-bold" style="font-size: 12px;" id="distributeNoticeBadge">
                Chờ chọn màu và kích thước để sinh biến thể
              </span>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- KHỐI 4: HÌNH ẢNH SẢN PHẨM & THƯ VIỆN LOOKBOOK (FULL WIDTH) -->
    <div class="col-12">
      <div class="admin-card mb-0">
        <div class="admin-card-header">
          <h5 class="admin-card-title">
            <i class="fa-solid fa-images text-success"></i>
            4. Hình Ảnh Sản Phẩm &amp; Thư Viện Lookbook (Trải Rộng Rãi)
          </h5>
          <span class="badge bg-light text-muted border" id="galleryCountBadge" style="font-size: 11px;">0 ảnh gallery</span>
        </div>
        <div class="p-3 p-md-4">
          <div class="row g-4">
            
            <!-- Cột ảnh bìa chính (Chiếm 4 cols) -->
            <div class="col-12 col-lg-4 text-center border-end-lg pe-lg-4">
              <label class="form-label mb-2 d-block text-start fw-bold">Ảnh Đại Diện Chính (Tỷ lệ 3:4 chuẩn lookbook)</label>

              <!-- Khung xem trước ảnh chính 3:4 -->
              <div class="position-relative mx-auto mb-2.5 rounded-3 overflow-hidden border bg-light d-flex align-items-center justify-content-center shadow-xs" style="width: 180px; height: 240px;">
                <img id="mainImagePreview" 
                     src="" 
                     alt="Preview" 
                     class="w-100 h-100 object-fit-cover"
                     style="display: none;">
                <div id="mainImageEmptyPlaceholder" class="text-center p-2 text-muted">
                  <i class="fa-regular fa-image fs-1 text-secondary mb-1 opacity-40"></i>
                  <span class="d-block fw-semibold" style="font-size: 11px;">Chưa chọn ảnh chính</span>
                </div>
                <button type="button" id="clearMainImgBtn" onclick="clearPrimaryImage()" class="btn btn-sm btn-dark position-absolute top-0 end-0 m-1 p-1 rounded-circle lh-1 opacity-75" title="Gỡ ảnh" style="display: none;">
                  <i class="fa-solid fa-xmark" style="font-size: 10px;"></i>
                </button>
              </div>

              <!-- Upload File -->
              <div class="mb-2">
                <input type="file" 
                       name="image" 
                       id="primaryImageFileInput" 
                       class="form-control form-control-sm" 
                       accept="image/*" 
                       onchange="handlePrimaryFileChange(this)">
              </div>

              <!-- Dán link URL -->
              <div class="mb-2">
                <input type="text" 
                       name="image_url" 
                       id="primaryImageUrlInput" 
                       class="form-control form-control-sm font-monospace" 
                       value="{{ old('image_url') }}" 
                       placeholder="Hoặc dán URL ảnh trực tiếp tại đây" 
                       oninput="handlePrimaryUrlChange(this.value)">
              </div>

              <!-- Ảnh mẫu hệ thống -->
              <div>
                <span class="text-muted d-block mb-1" style="font-size: 11.5px;">Hoặc chọn ảnh mẫu có sẵn:</span>
                <div class="d-flex justify-content-center gap-1 flex-wrap">
                  <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="applyPresetImage('/assets/img/products/polo_01.jpg')">Polo 01</button>
                  <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="applyPresetImage('/assets/img/products/polo_02.jpg')">Polo 02</button>
                  <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="applyPresetImage('/assets/img/products/blazer_01.jpg')">Blazer</button>
                  <button type="button" class="btn btn-light btn-sm py-0 px-2 border" style="font-size: 11px;" onclick="applyPresetImage('/assets/img/products/somi_01.jpg')">Sơ Mi</button>
                </div>
              </div>
            </div>

            <!-- Cột thư viện ảnh chi tiết (Chiếm 8 cols - Rộng rãi, thoáng mắt) -->
            <div class="col-12 col-lg-8 ps-lg-4">
              <label class="form-label mb-1 d-block fw-bold">Thư Viện Ảnh Chi Tiết Phụ (Lookbook Gallery)</label>
              <p class="text-muted mb-2.5" style="font-size: 12px;">
                Tải lên nhiều góc ảnh khác nhau (mặt trước, mặt sau, cận cảnh thêu logo, cận cảnh bề mặt vải, ảnh người mẫu mặc lookbook).
              </p>

              <!-- Upload Dropzone rộng rãi -->
              <div class="dropzone-box mb-3 py-4" onclick="document.getElementById('galleryImagesInput').click()">
                <i class="fa-solid fa-cloud-arrow-up text-primary fs-2 mb-2"></i>
                <h6 class="fw-bold text-dark mb-1" style="font-size: 14px;">Bấm hoặc kéo thả nhiều file ảnh vào đây</h6>
                <p class="text-muted mb-0" style="font-size: 12px;">Hỗ trợ định dạng JPG, PNG, WEBP tỷ lệ chuẩn lookbook 3:4</p>
                <input type="file" 
                       name="gallery_images[]" 
                       id="galleryImagesInput" 
                       class="d-none" 
                       accept="image/*" 
                       multiple 
                       onchange="handleGalleryFiles(this)">
              </div>

              <!-- Thumbnails Preview Grid (6 cột rộng rãi) -->
              <div class="row g-2.5" id="galleryPreviewGrid">
                <!-- Rendered dynamically -->
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- KHỐI 5: TIÊU CHUẨN XUẤT XƯỞNG & THÔNG SỐ KỸ THUẬT MAY ĐO (FULL WIDTH) -->
    <div class="col-12">
      <div class="admin-card mb-0">
        <div class="admin-card-header">
          <h5 class="admin-card-title">
            <i class="fa-solid fa-sliders text-secondary"></i>
            5. Tiêu Chuẩn Xuất Xưởng &amp; Thông Số Kỹ Thuật May Đo (Specifications)
          </h5>
          <span class="badge bg-light text-muted border" style="font-size: 11px;">Tùy chọn bổ sung</span>
        </div>
        <div class="p-3 p-md-4">
          <div class="row g-3">
            <div class="col-12 col-md-3">
              <label class="form-label mb-1">Phom dáng (Fit):</label>
              <input type="text" name="specifications[Phom dáng]" class="form-control" list="fitList" value="{{ old('specifications.Phom dáng', 'Slimfit tôn dáng lịch lãm') }}" placeholder="VD: Slimfit, Regular fit...">
              <datalist id="fitList">
                <option value="Slimfit tôn dáng lịch lãm">
                <option value="Regular fit thoải mái năng động">
                <option value="Form Boxy trẻ trung hiện đại">
                <option value="Oversize phóng khoáng thời thượng">
              </datalist>
            </div>

            <div class="col-12 col-md-3">
              <label class="form-label mb-1">Chất liệu vải chính (Fabric):</label>
              <input type="text" name="specifications[Chất liệu]" id="specDefaultMaterialInput" class="form-control" list="fabricList" value="{{ old('specifications.Chất liệu', '100% Cotton Compact dệt tổ ong kháng khuẩn') }}" placeholder="VD: 100% Cotton Compact...">
              <datalist id="fabricList">
                <option value="100% Cotton Compact dệt tổ ong kháng khuẩn">
                <option value="Lụa Bamboo cao cấp siêu mịn thoáng mát">
                <option value="Cotton pha Spandex co giãn 4 chiều">
                <option value="Vải dệt Dạ Tweed cao cấp giữ ấm">
                <option value="Linen tự nhiên thoáng mát thân thiện">
                <option value="Len Cashmere cao cấp">
              </datalist>
            </div>

            <div class="col-12 col-md-3">
              <label class="form-label mb-1">Xuất xứ &amp; Gia công:</label>
              <input type="text" name="specifications[Xuất xứ]" class="form-control" list="originList" value="{{ old('specifications.Xuất xứ', 'Việt Nam (Tiêu chuẩn may đo cao cấp)') }}" placeholder="VD: Việt Nam (Xuất khẩu)...">
              <datalist id="originList">
                <option value="Việt Nam (Tiêu chuẩn xuất khẩu chất lượng cao)">
                <option value="Hàn Quốc (Gia công thiết kế)">
                <option value="Ý (Vải nhập khẩu cao cấp)">
              </datalist>
            </div>

            <div class="col-12 col-md-3">
              <label class="form-label mb-1">Bảo hành &amp; Đổi trả:</label>
              <input type="text" name="specifications[Bảo hành]" class="form-control" list="warrantyList" value="{{ old('specifications.Bảo hành', 'Đổi size miễn phí trong 30 ngày') }}" placeholder="VD: Đổi size 30 ngày...">
              <datalist id="warrantyList">
                <option value="Đổi size miễn phí trong 30 ngày">
                <option value="Bảo hành đường chỉ may trọn đời">
                <option value="1 đổi 1 ngay lập tức nếu lỗi sợi">
              </datalist>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- THANH TÁC VỤ CỐ ĐỊNH PHÍA DƯỚI -->
  <div class="p-3 bg-white rounded-3 border shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <div>
      <span class="text-muted fw-semibold fs-9"><i class="fa-solid fa-shield-halved text-success me-1"></i>Hệ thống tự động kiểm tra tính hợp lệ của mã SKU và dữ liệu trước khi lưu.</span>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary fw-bold px-3 py-2 bg-white" style="font-size: 13px;">
        Hủy &amp; Quay Lại
      </a>
      <button type="button" onclick="submitProductForm('inactive', 'save_index')" class="btn btn-outline-dark fw-bold px-3 py-2 bg-white shadow-xs" style="font-size: 13px;">
        <i class="fa-solid fa-floppy-disk me-1.5"></i> Lưu Bản Nháp
      </button>
      <button type="button" onclick="submitProductForm('active', 'save_index')" class="btn btn-primary fw-bold px-4 py-2 shadow-xs" style="font-size: 13px;">
        <i class="fa-solid fa-cloud-arrow-up me-1.5"></i> Đăng Mở Bán Sản Phẩm
      </button>
    </div>
  </div>
</form>
@endsection

@push('scripts')
<script>
  // =========================================================================
  // DỮ LIỆU KHỞI TẠO MÀU SẮC & SIZE
  // =========================================================================
  const initialColors = @json(old('colors', []));
  const initialSizes = @json(old('sizes', []));

  const paletteMap = {
    'Đen': '#111827',
    'Trắng': '#FFFFFF',
    'Xanh Navy': '#1E3A8A',
    'Xám Tro': '#6B7280',
    'Xám Ghi': '#9CA3AF',
    'Beige': '#E5D9C5',
    'Nâu Cafe': '#78350F',
    'Xanh Rêu': '#365314',
    'Xanh Mint': '#6EE7B7',
    'Đỏ Đô': '#881337',
    'Vàng Cát': '#FDE047'
  };

  let selectedColors = [];
  if (Array.isArray(initialColors) && initialColors.length > 0) {
    selectedColors = initialColors.map(c => ({
      name: c,
      code: paletteMap[c] || '#334155'
    }));
  }

  let selectedSizes = Array.isArray(initialSizes) ? [...initialSizes] : [];

  // Khởi tạo khi trang tải xong
  document.addEventListener('DOMContentLoaded', function() {
    renderColorChips();
    renderSizeChips();
    highlightSelectedSwatches();
    highlightSelectedSizeButtons();
    renderVariantMatrix();
    handlePriceCalculation();
    updateSaleDateNotice();
    updatePreviewCard();

    // Lắng nghe sự kiện gõ trên các ô rich editor để đồng bộ tức thì sang textarea
    ['shortDescEditor', 'detailedDescEditor'].forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('input', () => syncEditorToTextarea(id));
        el.addEventListener('blur', () => syncEditorToTextarea(id));
      }
    });
  });

  // =========================================================================
  // BỘ CÔNG CỤ SOẠN THẢO RICH TEXT (MỖI LẦN CHỌN HIỆN MÀU CHỈ DUY NHẤT 1 Ô)
  // =========================================================================

  // Hàm đảm bảo chỉ duy nhất 1 ô trên thanh công cụ được hiện màu (active) tại một thời điểm
  function setExclusiveActive(wrapper, targetEl) {
    if (!wrapper) return;

    // Kiểm tra xem ô mục tiêu trước đó đã hiện màu (active) chưa
    const wasActive = targetEl ? targetEl.classList.contains('active') : false;

    // Tắt màu toàn bộ các ô (nút bấm, dropdown select, ô chọn màu) trên thanh công cụ này
    wrapper.querySelectorAll('.rich-editor-btn').forEach(b => b.classList.remove('active'));
    wrapper.querySelectorAll('.rich-editor-select').forEach(s => s.classList.remove('active'));
    wrapper.querySelectorAll('.rich-editor-color-wrapper').forEach(c => c.classList.remove('active'));

    // Nếu trước đó chưa hiện màu -> bật màu cho duy nhất ô này
    if (targetEl && !wasActive) {
      targetEl.classList.add('active');
    }
  }

  function formatText(command, value, editorId, btnElement) {
    const editor = document.getElementById(editorId);
    if (!editor) return;
    editor.focus();

    const wrapper = editor.closest('.rich-editor-wrapper');
    if (wrapper && btnElement) {
      setExclusiveActive(wrapper, btnElement);
    }

    document.execCommand(command, false, value || null);
    syncEditorToTextarea(editorId);
  }

  function clearFormatting(editorId, btnElement) {
    const editor = document.getElementById(editorId);
    if (!editor) return;
    editor.focus();
    document.execCommand('removeFormat', false, null);
    document.execCommand('unlink', false, null);
    editor.style.fontFamily = '';
    editor.style.fontSize = '';
    
    // Tắt toàn bộ màu trên thanh công cụ
    const wrapper = editor.closest('.rich-editor-wrapper');
    if (wrapper) {
      setExclusiveActive(wrapper, null);
      wrapper.querySelectorAll('.rich-editor-select').forEach(sel => {
        sel.value = '';
      });
      wrapper.querySelectorAll('.rich-editor-color-wrapper').forEach(w => {
        w.style.borderColor = '#cbd5e1';
      });
    }
    syncEditorToTextarea(editorId);
  }

  function applyFontFamily(font, editorId, selectEl) {
    const editor = document.getElementById(editorId);
    if (!editor) return;
    editor.focus();
    
    const wrapper = editor.closest('.rich-editor-wrapper');
    if (wrapper && selectEl) {
      if (font) {
        setExclusiveActive(wrapper, selectEl);
      } else {
        setExclusiveActive(wrapper, null);
      }
    }

    const sel = window.getSelection();
    if (!sel || sel.isCollapsed || !sel.rangeCount) {
      editor.style.fontFamily = font || '';
    } else {
      document.execCommand('fontName', false, font);
    }
    syncEditorToTextarea(editorId);
  }

  function applyFontSize(size, editorId, selectEl) {
    const editor = document.getElementById(editorId);
    if (!editor) return;
    editor.focus();

    const wrapper = editor.closest('.rich-editor-wrapper');
    if (wrapper && selectEl) {
      if (size) {
        setExclusiveActive(wrapper, selectEl);
      } else {
        setExclusiveActive(wrapper, null);
      }
    }

    const sel = window.getSelection();
    if (!sel || sel.isCollapsed || !sel.rangeCount) {
      editor.style.fontSize = size || '';
    } else {
      const range = sel.getRangeAt(0);
      const span = document.createElement('span');
      span.style.fontSize = size;
      span.appendChild(range.extractContents());
      range.insertNode(span);
      sel.removeAllRanges();
      const newRange = document.createRange();
      newRange.selectNodeContents(span);
      sel.addRange(newRange);
    }
    syncEditorToTextarea(editorId);
  }

  function handleColorChange(color, editorId, inputEl) {
    const editor = document.getElementById(editorId);
    if (!editor) return;
    editor.focus();
    document.execCommand('foreColor', false, color);
    
    const wrapper = editor.closest('.rich-editor-wrapper');
    const colorWrapper = inputEl ? inputEl.closest('.rich-editor-color-wrapper') : null;
    if (wrapper && colorWrapper) {
      setExclusiveActive(wrapper, colorWrapper);
      colorWrapper.style.borderColor = color;
    }
    syncEditorToTextarea(editorId);
  }

  function syncEditorToTextarea(editorId) {
    const editor = document.getElementById(editorId);
    if (!editor) return;
    if (editorId === 'shortDescEditor') {
      document.getElementById('shortDescInput').value = editor.innerHTML.trim();
    } else if (editorId === 'detailedDescEditor') {
      document.getElementById('detailedDescInput').value = editor.innerHTML.trim();
    }
  }

  // Mẫu mô tả tóm tắt chuẩn
  function fillShortDescTemplate() {
    const editor = document.getElementById('shortDescEditor');
    editor.innerHTML = '<p>Mẫu thiết kế may đo cao cấp từ xưởng <strong>BeeStyle Atelier</strong>. Sợi dệt tự nhiên chọn lọc thoáng khí, xử lý chống nhăn vượt trội, tôn trọn phong thái lịch lãm và bản lĩnh của quý ông hiện đại.</p>';
    syncEditorToTextarea('shortDescEditor');
  }

  // Mẫu mô tả chi tiết chuẩn
  function insertStandardDetailedDesc() {
    const editor = document.getElementById('detailedDescEditor');
    const template = `
      <p><strong>1. ĐẶC ĐIỂM THIẾT KẾ &amp; PHOM DÁNG:</strong></p>
      <ul>
        <li>Phom dáng Regular Fit lịch lãm, vừa vặn tự nhiên mang lại sự thoải mái và tôn trọn vóc dáng nam tính.</li>
        <li>Đường kim mũi chỉ giấu chỉ tinh tế, đạt tiêu chuẩn may đo khắt khe tại xưởng may BeeStyle Atelier.</li>
      </ul>
      <p><strong>2. CHẤT LIỆU CAO CẤP &amp; CÔNG NGHỆ DỆT:</strong></p>
      <ul>
        <li>Sợi dệt tự nhiên chọn lọc, mềm mịn, thoáng khí vượt trội và co giãn 4 chiều linh hoạt khi vận động.</li>
        <li>Bề mặt vải xử lý chống xù lông, giữ phom dáng bền bỉ và chống nhăn nhàu suốt cả ngày làm việc.</li>
      </ul>
      <p><strong>3. HƯỚNG DẪN PHỐI ĐỒ &amp; BẢO QUẢN:</strong></p>
      <ul>
        <li>Dễ dàng phối cùng quần âu Sartorial, quần jeans hoặc khoác ngoài blazer trong mọi sự kiện, hội họp hay dạo phố.</li>
        <li>Giặt nhẹ nhàng ở nhiệt độ dưới 30°C, phơi trong bóng râm thoáng mát, là ủi ở nhiệt độ vừa.</li>
      </ul>
    `.trim();
    editor.innerHTML = template;
    syncEditorToTextarea('detailedDescEditor');
  }

  // =========================================================================
  // XỬ LÝ TÊN SẢN PHẨM & SKU NHẬP TAY
  // =========================================================================
  function slugify(text) {
    return text.toString().toLowerCase()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
      .replace(/[đĐ]/g, 'd')
      .replace(/[^a-z0-9 -]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-+/, '')
      .replace(/-+$/, '');
  }

  function handleNameInput(val) {
    document.getElementById('nameCharCount').innerText = `${val.length} / 255 ký tự`;
    const slug = slugify(val) || 'chua-co-ten-san-pham';
    document.getElementById('slugPreviewDisplay').innerText = `beestyle.vn/san-pham/${slug}`;
    updatePreviewCard();
  }

  function insertNamePrefix(prefix) {
    const input = document.getElementById('productNameInput');
    if (!input.value.includes(prefix)) {
      input.value = prefix + ' ' + input.value.trim();
    }
    handleNameInput(input.value);
  }

  // Quản trị viên tự nhập mã SKU thủ công
  function handleSkuManualInput(val) {
    updatePreviewCard();
    // Cập nhật lại mã SKU con trên các dòng ma trận biến thể hiện hữu
    const skuBase = val.trim().toUpperCase() || 'BS-PROD';
    document.querySelectorAll('#variantMatrixBody tr').forEach(row => {
      const color = row.dataset.colorSlug;
      const size = row.dataset.sizeSlug;
      if (color && size) {
        const newChildSku = `${skuBase}-${color}-${size}`;
        row.dataset.sku = newChildSku;
        const skuCell = row.querySelector('.child-sku-text');
        if (skuCell) skuCell.innerText = newChildSku;
        
        // Đổi key của các input liên quan
        const pInp = row.querySelector('input[name^="variant_price"]');
        if (pInp) pInp.name = `variant_price[${newChildSku}]`;
        const origInp = row.querySelector('input[name^="variant_original_price"]');
        if (origInp) origInp.name = `variant_original_price[${newChildSku}]`;
        const sInp = row.querySelector('input[name^="variant_stock"]');
        if (sInp) sInp.name = `variant_stock[${newChildSku}]`;
      }
    });
  }

  function handleCategoryChange(sel) {
    updatePreviewCard();
  }

  // =========================================================================
  // XỬ LÝ MÀU SẮC & KÍCH THƯỚC (SIZE)
  // =========================================================================
  function toggleColor(name, code) {
    const idx = selectedColors.findIndex(c => c.name === name);
    if (idx >= 0) {
      selectedColors.splice(idx, 1);
    } else {
      selectedColors.push({ name, code });
    }
    renderColorChips();
    highlightSelectedSwatches();
    renderVariantMatrix();
    updatePreviewCard();
  }

  function removeColor(name) {
    const idx = selectedColors.findIndex(c => c.name === name);
    if (idx >= 0) {
      selectedColors.splice(idx, 1);
      renderColorChips();
      highlightSelectedSwatches();
      renderVariantMatrix();
      updatePreviewCard();
    }
  }

  function addCustomColor() {
    const nameInput = document.getElementById('customColorNameInput');
    const colorPicker = document.getElementById('customColorPicker');
    const name = nameInput.value.trim();
    const code = colorPicker.value;

    if (!name) {
      alert('Vui lòng nhập tên màu sắc!');
      nameInput.focus();
      return;
    }

    if (selectedColors.some(c => c.name.toLowerCase() === name.toLowerCase())) {
      alert('Màu sắc này đã được chọn!');
      return;
    }

    selectedColors.push({ name, code });
    nameInput.value = '';
    renderColorChips();
    renderVariantMatrix();
    updatePreviewCard();
  }

  function renderColorChips() {
    const container = document.getElementById('activeColorChips');
    const hiddenContainer = document.getElementById('hiddenColorsContainer');
    container.innerHTML = '';
    hiddenContainer.innerHTML = '';

    if (selectedColors.length === 0) {
      container.innerHTML = '<span class="text-muted fst-italic" style="font-size: 12px;">Chưa chọn màu nào</span>';
      return;
    }

    selectedColors.forEach(c => {
      const chip = document.createElement('span');
      chip.className = 'tag-badge';
      chip.innerHTML = `
        <span class="rounded-circle border" style="width: 12px; height: 12px; background-color: ${c.code};"></span>
        <span>${c.name}</span>
        <i class="fa-solid fa-xmark btn-remove" onclick="removeColor('${c.name}')"></i>
      `;
      container.appendChild(chip);

      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'colors[]';
      hiddenInput.value = c.name;
      hiddenContainer.appendChild(hiddenInput);
    });
  }

  function highlightSelectedSwatches() {
    document.querySelectorAll('.color-swatch-item').forEach(el => {
      const colorName = el.getAttribute('data-color');
      if (selectedColors.some(c => c.name === colorName)) {
        el.classList.add('active');
      } else {
        el.classList.remove('active');
      }
    });
  }

  function toggleSize(size) {
    const idx = selectedSizes.indexOf(size);
    if (idx >= 0) {
      selectedSizes.splice(idx, 1);
    } else {
      selectedSizes.push(size);
    }
    renderSizeChips();
    highlightSelectedSizeButtons();
    renderVariantMatrix();
    updatePreviewCard();
  }

  function removeSize(size) {
    const idx = selectedSizes.indexOf(size);
    if (idx >= 0) {
      selectedSizes.splice(idx, 1);
      renderSizeChips();
      highlightSelectedSizeButtons();
      renderVariantMatrix();
      updatePreviewCard();
    }
  }

  function addCustomSize() {
    const input = document.getElementById('customSizeInput');
    const size = input.value.trim().toUpperCase();
    if (!size) {
      alert('Vui lòng nhập tên kích thước (Size)!');
      input.focus();
      return;
    }
    if (selectedSizes.includes(size)) {
      alert('Kích thước này đã tồn tại!');
      return;
    }
    selectedSizes.push(size);
    input.value = '';
    renderSizeChips();
    highlightSelectedSizeButtons();
    renderVariantMatrix();
    updatePreviewCard();
  }

  function selectAllSizes(arr) {
    arr.forEach(s => {
      if (!selectedSizes.includes(s)) selectedSizes.push(s);
    });
    renderSizeChips();
    highlightSelectedSizeButtons();
    renderVariantMatrix();
    updatePreviewCard();
  }

  function clearAllSizes() {
    selectedSizes = [];
    renderSizeChips();
    highlightSelectedSizeButtons();
    renderVariantMatrix();
    updatePreviewCard();
  }

  function highlightSelectedSizeButtons() {
    document.querySelectorAll('.size-btn').forEach(btn => {
      const s = btn.getAttribute('data-size');
      if (selectedSizes.includes(s)) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
  }

  function renderSizeChips() {
    const container = document.getElementById('activeSizeChips');
    const hiddenContainer = document.getElementById('hiddenSizesContainer');
    container.innerHTML = '';
    hiddenContainer.innerHTML = '';

    if (selectedSizes.length === 0) {
      container.innerHTML = '<span class="text-muted fst-italic" style="font-size: 12px;">Chưa chọn size nào</span>';
      return;
    }

    selectedSizes.forEach(s => {
      const chip = document.createElement('span');
      chip.className = 'tag-badge';
      chip.innerHTML = `
        <span>Size ${s}</span>
        <i class="fa-solid fa-xmark btn-remove" onclick="removeSize('${s}')"></i>
      `;
      container.appendChild(chip);

      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'sizes[]';
      hiddenInput.value = s;
      hiddenContainer.appendChild(hiddenInput);
    });
  }

  // =========================================================================
  // BẢNG MA TRẬN BIẾN THỂ TỰ ĐỘNG (XÓA CỘT CHẤT LIỆU, LIÊN KẾT GIÁ VỚI BÊN TRÊN)
  // =========================================================================
  function renderVariantMatrix() {
    const tbody = document.getElementById('variantMatrixBody');
    const skuBase = document.getElementById('skuInput').value.trim().toUpperCase() || 'BS-PROD';
    const currentPrice = parseInt(document.getElementById('productPriceInput').value) || 0;
    const currentOriginalPrice = parseInt(document.getElementById('productOriginalPriceInput').value) || 0;
    const totalStock = parseInt(document.getElementById('productStockInput').value) || 0;

    // Lưu lại giá trị đã gõ để không bị mất khi chọn thêm bớt màu/size
    const existingValues = {};
    document.querySelectorAll('#variantMatrixBody tr').forEach(row => {
      const sku = row.dataset.sku;
      if (sku) {
        const pInput = row.querySelector('input[name^="variant_price"]');
        const origInput = row.querySelector('input[name^="variant_original_price"]');
        const sInput = row.querySelector('input[name^="variant_stock"]');
        existingValues[sku] = {
          price: pInput ? pInput.value : '',
          isCustomPrice: pInput ? (pInput.dataset.customized === 'true') : false,
          originalPrice: origInput ? origInput.value : '',
          isCustomOriginalPrice: origInput ? (origInput.dataset.customized === 'true') : false,
          stock: sInput ? sInput.value : ''
        };
      }
    });

    const totalVariants = selectedColors.length * selectedSizes.length;
    const counterBadge = document.getElementById('totalVariantsCounterBadge');
    if (counterBadge) {
      counterBadge.innerText = totalVariants + ' biến thể';
    }

    tbody.innerHTML = '';

    if (selectedColors.length === 0 || selectedSizes.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="text-center py-4 text-muted" style="font-size: 13px;">
            <i class="fa-solid fa-layer-group fs-3 text-secondary mb-2 d-block opacity-40"></i>
            Chưa chọn màu sắc hoặc kích thước. Vui lòng bấm chọn ở trên để hệ thống tự động sinh ma trận biến thể.
          </td>
        </tr>
      `;
      const badge = document.getElementById('distributeNoticeBadge');
      if (badge) badge.innerText = 'Chờ chọn màu và kích thước để sinh biến thể';
      return;
    }

    const baseStockPerVar = totalStock > 0 ? Math.floor(totalStock / Math.max(1, totalVariants)) : 0;
    let remainder = totalStock > 0 ? (totalStock % Math.max(1, totalVariants)) : 0;

    selectedColors.forEach(c => {
      selectedSizes.forEach(s => {
        const colorSlug = slugify(c.name).toUpperCase();
        const sizeSlug = s.toString().toUpperCase();
        const varSku = `${skuBase}-${colorSlug}-${sizeSlug}`;
        
        let initialStock = baseStockPerVar;
        if (remainder > 0) {
          initialStock += 1;
          remainder--;
        }

        const saved = existingValues[varSku] || {};
        const rowStock = (saved.stock !== undefined && saved.stock !== '') ? saved.stock : initialStock;
        
        // Giá bán khách mua: Tự động liên kết với giá bên trên nếu chưa chỉnh riêng
        let rowPrice = '';
        let isCustomPrice = false;
        if (saved.price !== undefined && saved.price !== '') {
          rowPrice = saved.price;
          isCustomPrice = saved.isCustomPrice;
        } else if (currentPrice > 0) {
          rowPrice = currentPrice;
        }

        // Giá gốc niêm yết: Tự động liên kết với giá gốc bên trên nếu chưa chỉnh riêng
        let rowOriginalPrice = '';
        let isCustomOriginalPrice = false;
        if (saved.originalPrice !== undefined && saved.originalPrice !== '') {
          rowOriginalPrice = saved.originalPrice;
          isCustomOriginalPrice = saved.isCustomOriginalPrice;
        } else if (currentOriginalPrice > 0) {
          rowOriginalPrice = currentOriginalPrice;
        }

        const tr = document.createElement('tr');
        tr.dataset.sku = varSku;
        tr.dataset.colorSlug = colorSlug;
        tr.dataset.sizeSlug = sizeSlug;

        const formattedPriceSub = (rowPrice && parseInt(rowPrice) > 0) ? ('= ' + parseInt(rowPrice).toLocaleString('vi-VN') + ' ₫') : '';
        const formattedOrigSub = (rowOriginalPrice && parseInt(rowOriginalPrice) > 0) ? ('= ' + parseInt(rowOriginalPrice).toLocaleString('vi-VN') + ' ₫') : '';

        tr.innerHTML = `
          <td class="fw-bold text-dark">
            <span class="rounded-circle border d-inline-block me-1.5 align-middle shadow-xs" style="width: 14px; height: 14px; background-color: ${c.code};"></span>
            <span>${c.name}</span>
          </td>
          <td class="fw-black text-primary font-monospace fs-9">Size ${s}</td>
          <td class="font-monospace text-muted fw-bold child-sku-text" style="font-size: 11.5px;">${varSku}</td>
          <td>
            <div class="input-group">
              <input type="number" 
                     name="variant_price[${varSku}]" 
                     value="${rowPrice}" 
                     placeholder="${currentPrice || 0}" 
                     min="0" 
                     step="1000" 
                     data-customized="${isCustomPrice ? 'true' : 'false'}"
                     class="form-control price-variant-input px-2.5 text-danger"
                     title="Giá bán khách mua cho biến thể này"
                     oninput="handleVariantPriceInput(this, '${varSku}', 'price')">
              <span class="input-group-text price-variant-addon text-danger font-monospace">₫</span>
            </div>
            <div id="formatted_price_${varSku}" class="price-variant-subtext text-danger">${formattedPriceSub}</div>
          </td>
          <td>
            <div class="input-group">
              <input type="number" 
                     name="variant_original_price[${varSku}]" 
                     value="${rowOriginalPrice}" 
                     placeholder="${currentOriginalPrice || 0}" 
                     min="0" 
                     step="1000" 
                     data-customized="${isCustomOriginalPrice ? 'true' : 'false'}"
                     class="form-control price-variant-input px-2.5 text-dark"
                     title="Giá gốc niêm yết cho biến thể này"
                     oninput="handleVariantPriceInput(this, '${varSku}', 'original_price')">
              <span class="input-group-text price-variant-addon font-monospace">₫</span>
            </div>
            <div id="formatted_orig_price_${varSku}" class="price-variant-subtext text-muted">${formattedOrigSub}</div>
          </td>
          <td style="text-align: right;">
            <input type="number" 
                   name="variant_stock[${varSku}]" 
                   value="${rowStock}" 
                   min="0" 
                   class="form-control text-end fw-bold font-monospace d-inline-block" 
                   style="width: 85px; font-size: 13.5px; height: 38px; border-radius: 8px; border: 1.5px solid #cbd5e1;" 
                   oninput="recalcTotalStockFromMatrix()">
          </td>
        `;
        tbody.appendChild(tr);
      });
    });

    const badge = document.getElementById('distributeNoticeBadge');
    if (badge) {
      if (totalStock > 0) {
        badge.innerHTML = `<i class="fa-solid fa-check text-success me-1"></i>Đang chia đều: ~${baseStockPerVar.toLocaleString('vi-VN')} cái/mẫu (Tổng nhập: ${totalStock.toLocaleString('vi-VN')} cái)`;
      } else {
        badge.innerHTML = `<i class="fa-solid fa-layer-group text-primary me-1"></i>${totalVariants} biến thể sẵn sàng`;
      }
    }
  }

  function handleVariantPriceInput(input, varSku, type) {
    input.dataset.customized = 'true';
    const val = parseInt(input.value) || 0;
    const subtextEl = document.getElementById((type === 'price' ? 'formatted_price_' : 'formatted_orig_price_') + varSku);
    if (subtextEl) {
      subtextEl.innerText = val > 0 ? ('= ' + val.toLocaleString('vi-VN') + ' ₫') : '';
    }
  }

  // Đồng bộ toàn bộ giá biến thể từ 2 ô Giá Bán & Giá Gốc ở Khối 2
  function syncAllVariantPricesFromAbove() {
    const priceVal = parseInt(document.getElementById('productPriceInput').value) || 0;
    const origPriceVal = parseInt(document.getElementById('productOriginalPriceInput').value) || 0;

    document.querySelectorAll('#variantMatrixBody tr').forEach(row => {
      const sku = row.dataset.sku;
      if (!sku) return;

      const pInp = row.querySelector('input[name^="variant_price"]');
      if (pInp) {
        pInp.value = priceVal > 0 ? priceVal : '';
        pInp.dataset.customized = 'false';
        const pSub = document.getElementById('formatted_price_' + sku);
        if (pSub) pSub.innerText = priceVal > 0 ? ('= ' + priceVal.toLocaleString('vi-VN') + ' ₫') : '';
      }

      const origInp = row.querySelector('input[name^="variant_original_price"]');
      if (origInp) {
        origInp.value = origPriceVal > 0 ? origPriceVal : '';
        origInp.dataset.customized = 'false';
        const oSub = document.getElementById('formatted_orig_price_' + sku);
        if (oSub) oSub.innerText = origPriceVal > 0 ? ('= ' + origPriceVal.toLocaleString('vi-VN') + ' ₫') : '';
      }
    });

    alert('Đã đồng bộ toàn bộ Giá Bán Khách Mua và Giá Gốc Niêm Yết từ bên trên vào Ma Trận Biến Thể!');
  }

  // =========================================================================
  // QUẢN LÝ GIÁ BÁN, GIÁ GỐC, KHUYẾN MÃI VÀ CHIẾT KHẤU
  // =========================================================================
  function handlePriceCalculation() {
    const priceInput = document.getElementById('productPriceInput');
    const origPriceInput = document.getElementById('productOriginalPriceInput');
    const priceVal = parseInt(priceInput.value) || 0;
    const origPriceVal = parseInt(origPriceInput.value) || 0;

    const formattedPriceEl = document.getElementById('formattedPriceText');
    if (formattedPriceEl) {
      formattedPriceEl.innerText = priceVal > 0 ? (priceVal.toLocaleString('vi-VN') + ' VNĐ') : 'Chưa nhập giá bán';
    }

    const savingsEl = document.getElementById('savingsAmountText');
    const discountBadge = document.getElementById('discountBadge');

    if (origPriceVal > priceVal && priceVal > 0) {
      const savings = origPriceVal - priceVal;
      const pct = Math.round((savings / origPriceVal) * 100);
      if (savingsEl) savingsEl.innerText = `Tiết kiệm: ${savings.toLocaleString('vi-VN')} ₫`;
      if (discountBadge) {
        discountBadge.innerText = `Giảm ${pct}%`;
        discountBadge.style.display = 'inline-block';
      }
    } else {
      if (savingsEl) savingsEl.innerText = 'Giá gốc niêm yết';
      if (discountBadge) {
        discountBadge.style.display = 'none';
      }
    }

    // Tự động liên kết cập nhật giá vào Ma trận biến thể (cho các dòng chưa sửa riêng)
    document.querySelectorAll('#variantMatrixBody tr').forEach(row => {
      const sku = row.dataset.sku;
      if (!sku) return;

      const pInp = row.querySelector('input[name^="variant_price"]');
      if (pInp) {
        pInp.placeholder = priceVal > 0 ? priceVal : 0;
        if (pInp.dataset.customized !== 'true' && priceVal > 0) {
          pInp.value = priceVal;
          const sub = document.getElementById('formatted_price_' + sku);
          if (sub) sub.innerText = '= ' + priceVal.toLocaleString('vi-VN') + ' ₫';
        }
      }

      const origInp = row.querySelector('input[name^="variant_original_price"]');
      if (origInp) {
        origInp.placeholder = origPriceVal > 0 ? origPriceVal : 0;
        if (origInp.dataset.customized !== 'true' && origPriceVal > 0) {
          origInp.value = origPriceVal;
          const origSub = document.getElementById('formatted_orig_price_' + sku);
          if (origSub) origSub.innerText = '= ' + origPriceVal.toLocaleString('vi-VN') + ' ₫';
        }
      }
    });

    updatePreviewCard();
  }

  function applyDiscountPercent(pct) {
    const origInput = document.getElementById('productOriginalPriceInput');
    let origPrice = parseInt(origInput.value) || 0;
    const priceInput = document.getElementById('productPriceInput');
    let price = parseInt(priceInput.value) || 0;

    if (origPrice <= 0 && price > 0) {
      origPrice = price;
      origInput.value = origPrice;
    }

    if (origPrice > 0) {
      const discounted = Math.round((origPrice * (1 - pct / 100)) / 1000) * 1000;
      priceInput.value = discounted;
      handlePriceCalculation();
    }
  }

  function setSaleDuration(days) {
    const startInput = document.getElementById('saleStartsAtInput');
    const endInput = document.getElementById('saleEndsAtInput');

    const now = new Date();
    const toLocalISO = (d) => {
      const pad = n => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    };

    startInput.value = toLocalISO(now);
    const end = new Date(now.getTime() + days * 24 * 60 * 60 * 1000);
    endInput.value = toLocalISO(end);

    updateSaleDateNotice();
  }

  function clearSaleDates() {
    document.getElementById('saleStartsAtInput').value = '';
    document.getElementById('saleEndsAtInput').value = '';
    updateSaleDateNotice();
  }

  function updateSaleDateNotice() {
    const startVal = document.getElementById('saleStartsAtInput').value;
    const endVal = document.getElementById('saleEndsAtInput').value;
    const noticeEl = document.getElementById('saleDateNotice');
    if (!noticeEl) return;

    if (startVal || endVal) {
      const formatDT = (str) => {
        if (!str) return 'Ngay bây giờ';
        const parts = str.split('T');
        if (parts.length === 2) {
          const [y, m, d] = parts[0].split('-');
          return `${d}/${m}/${y} ${parts[1]}`;
        }
        return str;
      };
      noticeEl.innerHTML = `<i class="fa-regular fa-clock text-warning me-1.5"></i>Thời hạn áp dụng: <strong>${formatDT(startVal)}</strong> đến <strong>${endVal ? formatDT(endVal) : 'Vô thời hạn'}</strong>. (Khi hết hạn, hệ thống tự động bán theo giá gốc niêm yết).`;
      noticeEl.className = 'alert alert-warning py-2 px-3 mb-0 border bg-amber-50 text-dark';
    } else {
      noticeEl.innerHTML = `<i class="fa-solid fa-circle-info me-1.5 text-primary"></i>Không đặt thời hạn: Giá khuyến mãi sẽ áp dụng liên tục cho đến khi chỉnh sửa.`;
      noticeEl.className = 'alert alert-light py-2 px-3 mb-0 border bg-white text-muted';
    }
  }

  // =========================================================================
  // QUẢN LÝ SỐ LƯỢNG NHẬP & TỒN KHO
  // =========================================================================
  function setQuickStock(amount) {
    const input = document.getElementById('productStockInput');
    if (input) {
      input.value = amount;
      handleStockChange(amount);
    }
  }

  function setEachVariantStock(amount) {
    const inputs = document.querySelectorAll('input[name^="variant_stock"]');
    if (inputs.length === 0) return;

    inputs.forEach(inp => {
      inp.value = amount;
    });
    recalcTotalStockFromMatrix();
  }

  function distributeStockEqually() {
    const totalStock = parseInt(document.getElementById('productStockInput').value) || 0;
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

    const badge = document.getElementById('distributeNoticeBadge');
    if (badge) {
      badge.innerHTML = `<i class="fa-solid fa-check text-success me-1"></i>Đang chia đều: ~${base.toLocaleString('vi-VN')} cái/mẫu (Tổng: ${totalStock.toLocaleString('vi-VN')} cái)`;
    }
  }

  function recalcTotalStockFromMatrix() {
    let sum = 0;
    document.querySelectorAll('input[name^="variant_stock"]').forEach(inp => {
      sum += parseInt(inp.value) || 0;
    });
    document.getElementById('productStockInput').value = sum;
    updateStockStatusOnly(sum);

    const badge = document.getElementById('distributeNoticeBadge');
    if (badge) {
      badge.innerHTML = `<i class="fa-solid fa-pen text-primary me-1"></i>Tùy chỉnh: Tổng ${sum.toLocaleString('vi-VN')} cái`;
    }

    updatePreviewCard();
  }

  function handleStockChange(val) {
    const stock = parseInt(val) || 0;
    updateStockStatusOnly(stock);
    distributeStockEqually();
    updatePreviewCard();
  }

  function updateStockStatusOnly(stock) {
    const textEl = document.getElementById('stockStatusText');
    if (!textEl) return;
    if (stock <= 0) {
      textEl.innerText = 'Chưa nhập kho';
      textEl.className = 'text-muted';
    } else if (stock <= 5) {
      textEl.innerText = 'Sắp hết kho (' + stock.toLocaleString('vi-VN') + ' cái)';
      textEl.className = 'text-danger fw-bold';
    } else {
      textEl.innerText = 'Kho sẵn sàng (' + stock.toLocaleString('vi-VN') + ' cái)';
      textEl.className = 'text-success fw-bold';
    }
  }

  // =========================================================================
  // TRẠNG THÁI KINH DOANH
  // =========================================================================
  function setStatusOption(status) {
    document.getElementById('formStatusInput').value = status;
    const activeCard = document.getElementById('statusActiveCard');
    const inactiveCard = document.getElementById('statusInactiveCard');
    const headerBadge = document.getElementById('headerStatusBadge');

    if (status === 'active') {
      activeCard.className = 'radio-card active';
      inactiveCard.className = 'radio-card';
      headerBadge.className = 'badge bg-success-subtle text-success fw-bold px-2.5 py-1';
      headerBadge.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> ĐANG MỞ BÁN';
    } else {
      activeCard.className = 'radio-card';
      inactiveCard.className = 'radio-card inactive';
      headerBadge.className = 'badge bg-secondary-subtle text-secondary fw-bold px-2.5 py-1';
      headerBadge.innerHTML = '<i class="fa-solid fa-eye-slash me-1"></i> TẠM ẨN / NHÁP';
    }
  }

  // =========================================================================
  // QUẢN LÝ ẢNH ĐẠI DIỆN & GALLERY
  // =========================================================================
  function handlePrimaryFileChange(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        showPrimaryImage(e.target.result);
        document.getElementById('primaryImageUrlInput').value = '';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function handlePrimaryUrlChange(url) {
    if (url && url.trim()) {
      showPrimaryImage(url.trim());
    } else {
      clearPrimaryImage();
    }
  }

  function showPrimaryImage(src) {
    const mainImg = document.getElementById('mainImagePreview');
    const emptyPlaceholder = document.getElementById('mainImageEmptyPlaceholder');
    const clearBtn = document.getElementById('clearMainImgBtn');
    const cardImg = document.getElementById('cardPreviewImg');
    const cardPlaceholder = document.getElementById('cardPreviewEmptyPlaceholder');

    mainImg.src = src;
    mainImg.style.display = 'block';
    emptyPlaceholder.style.display = 'none';
    clearBtn.style.display = 'block';

    cardImg.src = src;
    cardImg.style.display = 'block';
    cardPlaceholder.style.display = 'none';
  }

  function clearPrimaryImage() {
    const mainImg = document.getElementById('mainImagePreview');
    const emptyPlaceholder = document.getElementById('mainImageEmptyPlaceholder');
    const clearBtn = document.getElementById('clearMainImgBtn');
    const cardImg = document.getElementById('cardPreviewImg');
    const cardPlaceholder = document.getElementById('cardPreviewEmptyPlaceholder');

    mainImg.src = '';
    mainImg.style.display = 'none';
    emptyPlaceholder.style.display = 'block';
    clearBtn.style.display = 'none';

    cardImg.src = '';
    cardImg.style.display = 'none';
    cardPlaceholder.style.display = 'block';

    document.getElementById('primaryImageFileInput').value = '';
    document.getElementById('primaryImageUrlInput').value = '';
  }

  function applyPresetImage(url) {
    showPrimaryImage(url);
    document.getElementById('primaryImageUrlInput').value = url;
    document.getElementById('primaryImageFileInput').value = '';
  }

  // Gallery Multi-Image Upload Preview
  function handleGalleryFiles(input) {
    const grid = document.getElementById('galleryPreviewGrid');
    grid.innerHTML = '';
    const files = input.files;
    document.getElementById('galleryCountBadge').innerText = files.length + ' ảnh gallery';

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const reader = new FileReader();
      reader.onload = function(e) {
        const col = document.createElement('div');
        col.className = 'col-6 col-sm-4 col-md-3 col-xl-2';
        col.innerHTML = `
          <div class="position-relative rounded-2 overflow-hidden border bg-light shadow-xs" style="aspect-ratio: 3/4;">
            <img src="${e.target.result}" class="w-100 h-100 object-fit-cover">
            <span class="position-absolute bottom-0 start-0 w-100 text-center text-white bg-dark bg-opacity-75" style="font-size: 10px; padding: 2px 0;">${(file.size/1024).toFixed(0)}KB</span>
          </div>
        `;
        grid.appendChild(col);
      };
      reader.readAsDataURL(file);
    }
  }

  // =========================================================================
  // MÔ PHỎNG THẺ NGOÀI WEB (LIVE PREVIEW) - RÚT NGẮN GỌN GÀNG
  // =========================================================================
  function updatePreviewCard() {
    const name = document.getElementById('productNameInput').value.trim() || 'Chưa có tên sản phẩm...';
    document.getElementById('cardTitleText').innerText = name;

    const catSelect = document.getElementById('categorySelect');
    let catName = 'CHƯA CHỌN DANH MỤC';
    if (catSelect && catSelect.selectedOptions.length > 0 && catSelect.value) {
      catName = catSelect.selectedOptions[0].text.trim().toUpperCase();
    }
    document.getElementById('cardCategoryText').innerText = catName;

    const price = parseInt(document.getElementById('productPriceInput').value) || 0;
    const originalPrice = parseInt(document.getElementById('productOriginalPriceInput').value) || 0;

    const priceFormatted = price > 0 ? (price.toLocaleString('vi-VN') + '₫') : '0₫';
    document.getElementById('cardPriceText').innerText = priceFormatted;
    
    const origPriceEl = document.getElementById('cardOriginalPriceText');
    const badgeDiscount = document.getElementById('cardBadgeDiscount');
    
    if (originalPrice > price && price > 0) {
      origPriceEl.innerText = originalPrice.toLocaleString('vi-VN') + '₫';
      origPriceEl.style.display = 'inline';
      const pct = Math.round(((originalPrice - price) / originalPrice) * 100);
      badgeDiscount.innerText = `-${pct}%`;
      badgeDiscount.style.display = 'inline-block';
    } else {
      origPriceEl.style.display = 'none';
      badgeDiscount.style.display = 'none';
    }

    // Badges: New & Featured
    document.getElementById('cardBadgeNew').style.display = document.getElementById('is_new').checked ? 'inline-block' : 'none';
    document.getElementById('cardBadgeFeatured').style.display = document.getElementById('is_featured').checked ? 'inline-block' : 'none';

    // Color swatches in card
    const dotsContainer = document.getElementById('cardColorDots');
    dotsContainer.innerHTML = '';
    selectedColors.slice(0, 5).forEach(c => {
      const dot = document.createElement('span');
      dot.className = 'rounded-circle border';
      dot.style.width = '10px';
      dot.style.height = '10px';
      dot.style.backgroundColor = c.code;
      dotsContainer.appendChild(dot);
    });
    if (selectedColors.length > 5) {
      const more = document.createElement('span');
      more.className = 'text-muted fw-bold';
      more.style.fontSize = '10px';
      more.innerText = `+${selectedColors.length - 5}`;
      dotsContainer.appendChild(more);
    }

    // Stock & Variants text
    const stock = parseInt(document.getElementById('productStockInput').value) || 0;
    const stockText = document.getElementById('cardStockText');
    if (stock > 0) {
      stockText.innerHTML = `<i class="fa-solid fa-circle me-1 text-success" style="font-size: 8px;"></i>Còn (${stock})`;
      stockText.className = 'text-success fw-semibold';
    } else {
      stockText.innerHTML = `<i class="fa-solid fa-circle me-1 text-secondary" style="font-size: 8px;"></i>Chưa có kho`;
      stockText.className = 'text-muted';
    }

    document.getElementById('cardVariantCountText').innerText = `${selectedSizes.length} size`;
  }

  // =========================================================================
  // NỘP BIỂU MẪU (SUBMIT FORM)
  // =========================================================================
  function submitProductForm(status, saveAction) {
    if (status) {
      document.getElementById('formStatusInput').value = status;
    }
    if (saveAction) {
      document.getElementById('formSaveActionInput').value = saveAction;
    }

    // Đồng bộ HTML từ rich editor sang textarea
    syncEditorToTextarea('shortDescEditor');
    syncEditorToTextarea('detailedDescEditor');

    const form = document.getElementById('createProductForm');
    
    if (selectedColors.length === 0) {
      alert('Vui lòng chọn hoặc thêm ít nhất một Màu sắc cho sản phẩm!');
      const colorSection = document.getElementById('colorSwatchesContainer');
      if (colorSection) colorSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }
    if (selectedSizes.length === 0) {
      alert('Vui lòng chọn hoặc thêm ít nhất một Kích thước (Size) cho sản phẩm!');
      const sizeSection = document.getElementById('sizeButtonsContainer');
      if (sizeSection) sizeSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    form.submit();
  }
</script>
@endpush
