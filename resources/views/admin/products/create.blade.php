@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm Mới | BeeStyle Admin')

@push('styles')
<style>
  /* Styling cao cấp cho trang Thêm Sản Phẩm */
  .preview-card-sticky {
    position: sticky;
    top: 80px;
  }
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
  .storefront-preview-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08), 0 8px 10px -6px rgba(0,0,0,0.04);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
  }
  .upload-dropzone {
    border: 2px dashed #cbd5e1;
    border-radius: 14px;
    padding: 24px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .upload-dropzone:hover {
    border-color: #f59e0b;
    background: #fffbeb;
  }
  .status-radio-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .status-radio-card.active {
    border-color: #10b981;
    background: #ecfdf5;
  }
  .status-radio-card.inactive {
    border-color: #64748b;
    background: #f8fafc;
  }
</style>
@endpush

@section('content')
<!-- BREADCRUMB & HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md-7">
    <nav aria-label="breadcrumb" class="mb-1">
      <ol class="breadcrumb fs-9 mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-body-tertiary text-decoration-none">Trang Chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-body-tertiary text-decoration-none">Kho Hàng Thời Trang</a></li>
        <li class="breadcrumb-item active text-body-emphasis" aria-current="page">Tạo Sản Phẩm Mới</li>
      </ol>
    </nav>
    <div class="d-flex align-items-center gap-2">
      <h2 class="mb-0 text-body-emphasis fw-bold">Tạo Mới Sản Phẩm Thời Trang</h2>
      <span class="badge badge-phoenix badge-phoenix-primary fs-10 fw-bold px-2 py-1" id="headerStatusBadge">
        <i class="fa-solid fa-bolt me-1"></i> ĐANG MỞ BÁN
      </span>
    </div>
    <p class="text-body-tertiary small mb-0 mt-1">
      Thiết lập thông tin định danh, kỹ thuật may đo, thư viện ảnh và sinh ma trận biến thể tự động
    </p>
  </div>
  <div class="col-md-5 text-md-end">
    <div class="d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
      <a href="{{ route('admin.products.index') }}" class="btn btn-phoenix-secondary btn-sm px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại
      </a>
      <button type="button" onclick="submitProductForm('inactive', 'save_index')" class="btn btn-outline-secondary btn-sm px-3">
        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Bản Nháp
      </button>
      <button type="button" onclick="submitProductForm('active', 'save_new')" class="btn btn-phoenix-primary btn-sm px-3">
        <i class="fa-solid fa-plus me-1"></i> Lưu &amp; Thêm Tiếp
      </button>
      <button type="button" onclick="submitProductForm('active', 'save_index')" class="btn btn-primary btn-sm px-3 fw-bold shadow-sm">
        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Đăng Mở Bán
      </button>
    </div>
  </div>
</div>

<!-- LỖI VALIDATION (NẾU CÓ) -->
@if (isset($errors) && $errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <div class="d-flex align-items-center gap-2 mb-1">
      <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
      <strong class="fs-6 text-danger">Vui lòng kiểm tra lại các trường thông tin:</strong>
    </div>
    <ul class="mb-0 small ps-4">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- FORM TẠO SẢN PHẨM MỚI -->
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
  @csrf
  <input type="hidden" name="status" id="formStatusInput" value="{{ old('status', 'active') }}">
  <input type="hidden" name="save_action" id="formSaveActionInput" value="save_index">

  <div class="row g-4">
    <!-- ========================================================================= -->
    <!-- CỘT TRÁI (8 COLS): THÔNG TIN CƠ BẢN, THUỘC TÍNH, THÔNG SỐ & BIẾN THỂ -->
    <!-- ========================================================================= -->
    <div class="col-12 col-lg-8">

      <!-- 1. THÔNG TIN CƠ BẢN & ĐỊNH DANH -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center fs-9 fw-bold">1</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Thông Tin Cơ Bản &amp; Định Danh</h5>
          </div>
          <span class="text-muted fs-10">Bắt buộc nhập tên &amp; danh mục</span>
        </div>
        <div class="card-body p-4">
          
          <!-- Tên sản phẩm -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label fs-9 fw-bold text-dark mb-0">
                Tên Sản Phẩm Thời Trang <span class="text-danger">*</span>
              </label>
              <span class="fs-10 text-muted" id="nameCharCount">0 / 255 ký tự</span>
            </div>
            <input type="text" 
                   name="name" 
                   id="productNameInput" 
                   class="form-control form-control-lg fs-8 fw-semibold" 
                   value="{{ old('name', 'Áo Polo Nam Cotton Dệt Tổ Ong Kháng Khuẩn BeeStyle') }}" 
                   placeholder="Ví dụ: Áo Polo Nam Cotton Dệt Tổ Ong Kháng Khuẩn BeeStyle..." 
                   required 
                   oninput="handleNameInput(this.value)">
            
            <!-- Gợi ý nhanh danh xưng thời trang -->
            <div class="d-flex align-items-center gap-1.5 flex-wrap mt-2">
              <span class="fs-10 text-muted me-1">Gợi ý nhanh:</span>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="insertNamePrefix('Áo Polo Nam')">+ Áo Polo</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="insertNamePrefix('Áo Sơ Mi Lụa')">+ Áo Sơ Mi</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="insertNamePrefix('Blazer May Đo')">+ Áo Blazer</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="insertNamePrefix('Áo Thun Form Boxy')">+ Áo Thun</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="insertNamePrefix('Quần Âu Sartorial')">+ Quần Âu</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="insertNamePrefix('Áo Len Dệt Thu Đông')">+ Thu Đông</button>
            </div>

            <!-- Đường dẫn Slug xem trước -->
            <div class="mt-2.5 p-2 bg-body-tertiary rounded-2 border border-translucent fs-9 d-flex align-items-center gap-2">
              <i class="fa-solid fa-link text-primary fs-10"></i>
              <span class="text-muted">Đường dẫn xem chi tiết:</span>
              <span class="text-primary fw-bold font-monospace" id="slugPreviewDisplay">beestyle.vn/san-pham/ao-polo-nam-cotton-det-to-ong</span>
            </div>
          </div>

          <!-- Row 3 cột: Mã SKU - Danh mục - Thương hiệu -->
          <div class="row g-3 mb-3">
            <div class="col-md-4">
              <label class="form-label fs-9 fw-bold text-dark mb-1 d-flex justify-content-between">
                <span>Mã SKU <span class="text-danger">*</span></span>
                <button type="button" onclick="generateSmartSku()" class="btn btn-link p-0 fs-10 text-primary text-decoration-none">
                  <i class="fa-solid fa-wand-magic-sparkles me-1"></i>Tạo mã tự động
                </button>
              </label>
              <div class="input-group">
                <span class="input-group-text bg-body-secondary font-monospace fs-9 fw-bold text-secondary">#</span>
                <input type="text" 
                       name="sku" 
                       id="skuInput" 
                       class="form-control font-monospace fw-bold text-uppercase fs-9" 
                       value="{{ old('sku', 'BS-POLO-' . rand(100, 999)) }}" 
                       placeholder="BS-PL-01" 
                       required 
                       oninput="updatePreviewCard()">
              </div>
            </div>

            <div class="col-md-4">
              <label class="form-label fs-9 fw-bold text-dark mb-1">
                Danh Mục Thời Trang <span class="text-danger">*</span>
              </label>
              <select name="category_id" id="categorySelect" class="form-select fs-9 fw-semibold" required onchange="handleCategoryChange(this)">
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ old('category_id') == $cat->id || $loop->first ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fs-9 fw-bold text-dark mb-1">Thương Hiệu Đối Tác</label>
              <select name="brand_id" id="brandSelect" class="form-select fs-9" onchange="updatePreviewCard()">
                <option value="">-- BeeStyle Atelier (Mặc định) --</option>
                @foreach($brands as $brand)
                  <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Mô tả tóm tắt ngắn -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label fs-9 fw-bold text-dark mb-0">Mô Tả Tóm Tắt (Short Description)</label>
              <button type="button" class="btn btn-link p-0 fs-10 text-primary text-decoration-none" onclick="fillShortDescTemplate()">
                <i class="fa-regular fa-file-lines me-1"></i>Chèn mẫu mô tả chuẩn
              </button>
            </div>
            <textarea name="short_description" 
                      id="shortDescInput" 
                      class="form-control fs-9" 
                      rows="2" 
                      placeholder="Tóm tắt chất liệu, form dáng, tính năng nổi bật... Xuất hiện ngay cạnh giá bán">{{ old('short_description', 'Mẫu áo polo dệt tổ ong thoáng khí từ sợi Cotton tự nhiên, xử lý hoàn thiện bề mặt chống nhăn, phom dáng tôn trọn vóc dáng nam tính.') }}</textarea>
          </div>

          <!-- Mô tả chi tiết -->
          <div class="mb-0">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label fs-9 fw-bold text-dark mb-0">Mô Tả Chi Tiết Sản Phẩm &amp; Hướng Dẫn</label>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="insertTextToDescription('\n- HƯỚNG DẪN GIẶT ỦI:\n+ Giặt máy ở chế độ nhẹ nhàng dưới 30°C\n+ Không dùng chất tẩy chứa Clo\n+ Phơi trong bóng râm, tránh ánh nắng trực tiếp')">
                  + Mẫu Giặt Ủi
                </button>
                <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="insertTextToDescription('\n- CAM KẾT CHẤT LƯỢNG:\n+ 100% đường may tỉ mỉ theo tiêu chuẩn may đo\n+ Đổi size miễn phí trong 30 ngày nếu không vừa')">
                  + Mẫu Cam Kết
                </button>
              </div>
            </div>
            <textarea name="description" 
                      id="detailedDescInput" 
                      class="form-control fs-9" 
                      rows="5" 
                      placeholder="Chi tiết nguồn gốc sợi dệt, quy trình may ráp, hướng dẫn phối đồ và mẹo bảo quản sản phẩm...">{{ old('description', "1. ĐẶC ĐIỂM THIẾT KẾ:\n- Thiết kế cổ dệt bo dệt tinh xảo, chống bai dão sau nhiều lần giặt.\n- Họa tiết dệt tổ ong (Waffle Knit) độc quyền tạo độ sâu sang trọng và độ thông thoáng tối đa.\n- Nẹp áo đính khuy vân đá sang trọng, đường may mí đôi giấu chỉ tinh tế.\n\n2. THÀNH PHẦN & CHẤT LIỆU:\n- 95% Cotton Compact chải kỹ chải sợi dài êm ái, thấm hút mồ hôi vượt trội.\n- 5% Spandex tăng cường độ co giãn linh hoạt 4 chiều giúp vận động tự tin.") }}</textarea>
          </div>

        </div>
      </div>

      <!-- 2. THÔNG SỐ KỸ THUẬT MAY ĐO (FASHION SPECIFICATIONS - JSON) -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-warning-subtle text-warning-emphasis d-inline-flex align-items-center justify-content-center fs-9 fw-bold">2</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Thông Số Kỹ Thuật May Đo (Specifications)</h5>
          </div>
          <span class="text-muted fs-10">Hiển thị tab thông số kỹ thuật chi tiết</span>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fs-9 fw-semibold text-dark mb-1">Phom dáng (Fit)</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-person text-secondary"></i></span>
                <input type="text" name="specifications[Phom dáng]" class="form-control fs-9" list="fitList" value="{{ old('specifications.Phom dáng', 'Regular fit / Slimfit tôn dáng') }}" placeholder="VD: Slimfit tôn dáng...">
                <datalist id="fitList">
                  <option value="Slimfit tôn dáng lịch lãm">
                  <option value="Regular fit thoải mái năng động">
                  <option value="Form Boxy trẻ trung hiện đại">
                  <option value="Oversize phóng khoáng thời thượng">
                </datalist>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fs-9 fw-semibold text-dark mb-1">Chất liệu vải (Fabric)</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-feather text-secondary"></i></span>
                <input type="text" name="specifications[Chất liệu]" class="form-control fs-9" list="fabricList" value="{{ old('specifications.Chất liệu', '100% Cotton Compact dệt tổ ong kháng khuẩn') }}" placeholder="VD: 100% Cotton...">
                <datalist id="fabricList">
                  <option value="100% Cotton Compact dệt tổ ong kháng khuẩn">
                  <option value="Lụa Bamboo cao cấp siêu mịn thoáng mát">
                  <option value="Cotton pha Spandex co giãn 4 chiều">
                  <option value="Vải dệt Dạ Tweed cao cấp giữ ấm">
                  <option value="Linen tự nhiên thoáng mát thân thiện">
                </datalist>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fs-9 fw-semibold text-dark mb-1">Xuất xứ &amp; Gia công (Origin)</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-earth-asia text-secondary"></i></span>
                <input type="text" name="specifications[Xuất xứ]" class="form-control fs-9" list="originList" value="{{ old('specifications.Xuất xứ', 'Việt Nam (Tiêu chuẩn xuất khẩu chất lượng cao)') }}" placeholder="VD: Việt Nam...">
                <datalist id="originList">
                  <option value="Việt Nam (Tiêu chuẩn xuất khẩu chất lượng cao)">
                  <option value="Hàn Quốc (Gia công thiết kế)">
                  <option value="Ý (Vải nhập khẩu cao cấp)">
                </datalist>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label fs-9 fw-semibold text-dark mb-1">Chế độ bảo hành &amp; Đổi size (Warranty)</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-body-secondary"><i class="fa-solid fa-shield-halved text-secondary"></i></span>
                <input type="text" name="specifications[Bảo hành]" class="form-control fs-9" list="warrantyList" value="{{ old('specifications.Bảo hành', 'Đổi size miễn phí trong 30 ngày') }}" placeholder="VD: Đổi size 30 ngày...">
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

      <!-- 3. QUẢN LÝ BIẾN THỂ MÀU SẮC, SIZE & MA TRẬN TỰ ĐỘNG -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-info-subtle text-info-emphasis d-inline-flex align-items-center justify-content-center fs-9 fw-bold">3</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Thuộc Tính Màu Sắc &amp; Kích Thước (Biến Thể)</h5>
          </div>
          <span class="badge bg-primary-subtle text-primary fw-bold" id="totalVariantsCounterBadge">8 biến thể</span>
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
            <div class="d-flex flex-wrap align-items-center gap-2.5 mb-3" id="colorSwatchesContainer">
              @php
                $palette = [
                  ['name' => 'Đen', 'code' => '#111827', 'default' => true],
                  ['name' => 'Trắng', 'code' => '#FFFFFF', 'default' => true],
                  ['name' => 'Xanh Navy', 'code' => '#1E3A8A', 'default' => true],
                  ['name' => 'Xám Tro', 'code' => '#6B7280', 'default' => false],
                  ['name' => 'Xám Ghi', 'code' => '#9CA3AF', 'default' => false],
                  ['name' => 'Beige', 'code' => '#E5D9C5', 'default' => false],
                  ['name' => 'Nâu Cafe', 'code' => '#78350F', 'default' => false],
                  ['name' => 'Xanh Rêu', 'code' => '#365314', 'default' => false],
                  ['name' => 'Xanh Mint', 'code' => '#6EE7B7', 'default' => false],
                  ['name' => 'Đỏ Đô', 'code' => '#881337', 'default' => false],
                  ['name' => 'Vàng Cát', 'code' => '#FDE047', 'default' => false],
                ];
              @endphp
              @foreach($palette as $item)
                <button type="button" 
                        class="color-swatch-btn {{ $item['default'] ? 'active' : '' }}" 
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
                <div class="d-flex flex-wrap gap-1.5" id="activeColorChips">
                  <!-- Rendered dynamically -->
                </div>
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
                <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="applySizePreset(['29', '30', '31', '32', '33'])">Quần Âu (29-33)</button>
              </div>
            </div>

            <!-- Preset Size Buttons -->
            <div class="d-flex flex-wrap gap-2 mb-3" id="sizeButtonsContainer">
              @foreach(['S', 'M', 'L', 'XL', 'XXL', '3XL', '28', '29', '30', '31', '32', '33', '39', '40', '41', '42'] as $s)
                <button type="button" 
                        class="btn btn-sm btn-outline-secondary px-2.5 py-1 fs-9 fw-bold size-pill-btn {{ in_array($s, ['S', 'M', 'L', 'XL']) ? 'btn-dark text-white border-dark active' : '' }}" 
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
                  <input type="text" id="customSizeInput" class="form-control fs-9" placeholder="Thêm size mới (VD: Freesize)...">
                  <button type="button" class="btn btn-phoenix-primary" onclick="addCustomSize()">Thêm</button>
                </div>
              </div>
              <div class="col-sm-7 col-12">
                <div class="d-flex flex-wrap gap-1.5" id="activeSizeChips">
                  <!-- Rendered dynamically -->
                </div>
              </div>
            </div>
            <!-- Hidden inputs container for sizes[] -->
            <div id="hiddenSizesContainer"></div>
          </div>

          <!-- PHẦN C: BẢNG MA TRẬN BIẾN THỂ TỰ ĐỘNG (VARIANT MATRIX PREVIEW) -->
          <div>
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
              <div>
                <span class="fs-9 fw-bold text-dark d-block">
                  <i class="fa-solid fa-table-cells text-warning me-1"></i> Ma Trận Biến Thể Tự Sinh (Variant Matrix)
                </span>
                <span class="fs-10 text-muted">Hệ thống sẽ tự động tạo các bản ghi kho độc lập cho từng mã</span>
              </div>
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-phoenix-secondary btn-xs py-1 px-2 fs-10" onclick="distributeStockEqually()">
                  <i class="fa-solid fa-calculator me-1"></i>Chia Đều Tồn Kho
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

      <!-- 4. THƯ VIỆN ẢNH PHỤ (GALLERY IMAGES STUDIO) -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center fs-9 fw-bold">4</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Thư Viện Ảnh Chi Tiết (Gallery Images)</h5>
          </div>
          <span class="badge bg-success-subtle text-success fs-10 fw-bold" id="galleryCountBadge">0 ảnh đã chọn</span>
        </div>
        <div class="card-body p-4">
          <p class="text-muted fs-9 mb-3">
            Tải lên nhiều góc ảnh khác nhau (mặt trước, mặt sau, cận cảnh thêu logo, chất liệu vải) để hiển thị trong thanh trượt trang chi tiết sản phẩm.
          </p>

          <!-- Upload Dropzone -->
          <div class="upload-dropzone mb-3" onclick="document.getElementById('galleryImagesInput').click()">
            <i class="fa-solid fa-images fs-2 text-warning mb-2"></i>
            <h6 class="fw-bold text-dark mb-1">Kéo thả các file ảnh vào đây hoặc bấm để chọn</h6>
            <p class="fs-10 text-muted mb-2">Hỗ trợ JPG, PNG, WEBP tỷ lệ 3:4 chuẩn thời trang (tối đa 4MB/ảnh)</p>
            <button type="button" class="btn btn-phoenix-secondary btn-sm px-3 fs-9">
              <i class="fa-solid fa-folder-open me-1"></i> Duyệt File Từ Máy Tính
            </button>
            <input type="file" 
                   name="gallery_images[]" 
                   id="galleryImagesInput" 
                   class="d-none" 
                   accept="image/*" 
                   multiple 
                   onchange="handleGalleryFiles(this)">
          </div>

          <!-- Thumbnails Preview Grid -->
          <div class="row g-2" id="galleryPreviewGrid">
            <!-- Rendered dynamically -->
          </div>
        </div>
      </div>

    </div>

    <!-- ========================================================================= -->
    <!-- CỘT PHẢI (4 COLS): GIÁ BÁN, TỒN KHO, ẢNH CHÍNH & LIVE STOREFRONT PREVIEW -->
    <!-- ========================================================================= -->
    <div class="col-12 col-lg-4">

      <!-- 5. GIÁ BÁN, CHIẾT KHẤU & TỒN KHO -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center fs-9 fw-bold">5</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Giá Bán &amp; Tồn Kho</h5>
          </div>
          <span class="badge bg-danger-subtle text-danger fw-bold" id="discountBadge">Giảm 22%</span>
        </div>
        <div class="card-body p-4">
          
          <!-- Giá bán thực tế -->
          <div class="mb-3">
            <label class="form-label fs-9 fw-bold text-dark mb-1">
              Giá Bán Khách Mua (VNĐ) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <input type="number" 
                     name="price" 
                     id="productPriceInput" 
                     class="form-control form-control-lg fw-bold text-danger fs-7" 
                     value="{{ old('price', 389000) }}" 
                     placeholder="389000" 
                     required 
                     min="0" 
                     step="1000" 
                     oninput="handlePriceCalculation()">
              <span class="input-group-text fw-bold text-dark fs-9">₫</span>
            </div>
            <span class="fs-10 text-muted mt-1 d-block" id="formattedPriceText">389.000 VNĐ</span>
          </div>

          <!-- Giá gốc niêm yết -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label fs-9 fw-bold text-dark mb-0">Giá Gốc Niêm Yết (Gạch ngang)</label>
              <span class="fs-10 text-muted" id="savingsAmountText">Tiết kiệm: 110.000 ₫</span>
            </div>
            <div class="input-group input-group-sm">
              <input type="number" 
                     name="original_price" 
                     id="productOriginalPriceInput" 
                     class="form-control fs-9 text-secondary" 
                     value="{{ old('original_price', 499000) }}" 
                     placeholder="499000" 
                     min="0" 
                     step="1000" 
                     oninput="handlePriceCalculation()">
              <span class="input-group-text fs-9">₫</span>
            </div>
            <!-- Quick Discount Buttons -->
            <div class="d-flex gap-1.5 flex-wrap mt-2">
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="applyDiscountPercent(10)">-10%</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="applyDiscountPercent(20)">-20%</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="applyDiscountPercent(30)">-30%</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0 px-1.5 fs-10" onclick="applyDiscountPercent(50)">-50%</button>
            </div>
          </div>

          <!-- Tổng số lượng tồn kho -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label fs-9 fw-bold text-dark mb-0">
                Tổng Số Lượng Tồn Kho <span class="text-danger">*</span>
              </label>
              <span class="fs-10 text-success fw-bold" id="stockStatusText">Kho dồi dào</span>
            </div>
            <div class="input-group">
              <input type="number" 
                     name="stock" 
                     id="productStockInput" 
                     class="form-control fs-8 fw-bold text-dark" 
                     value="{{ old('stock', 100) }}" 
                     required 
                     min="0" 
                     oninput="handleStockChange(this.value)">
              <span class="input-group-text fs-9">Cái</span>
            </div>
            <span class="fs-10 text-muted mt-1 d-block">Tồn kho sẽ tự động chia đều cho các biến thể bên dưới</span>
          </div>

          <!-- Trạng thái kinh doanh (Radio cards) -->
          <div class="mb-0">
            <label class="form-label fs-9 fw-bold text-dark mb-2">Trạng Thái Kinh Doanh</label>
            <div class="row g-2">
              <div class="col-6">
                <div class="status-radio-card active text-center" id="statusActiveCard" onclick="setStatusOption('active')">
                  <i class="fa-solid fa-circle-check text-success fs-5 mb-1 d-block"></i>
                  <span class="fs-9 fw-bold text-dark d-block">Đang Mở Bán</span>
                  <span class="fs-10 text-muted">Hiển thị trên Web</span>
                </div>
              </div>
              <div class="col-6">
                <div class="status-radio-card inactive text-center" id="statusInactiveCard" onclick="setStatusOption('inactive')">
                  <i class="fa-solid fa-eye-slash text-secondary fs-5 mb-1 d-block"></i>
                  <span class="fs-9 fw-bold text-dark d-block">Tạm Ẩn / Nháp</span>
                  <span class="fs-10 text-muted">Khách chưa thấy</span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- 6. HUY HIỆU TIẾP THỊ & QUẢNG BÁ -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-secondary-subtle text-secondary-emphasis d-inline-flex align-items-center justify-content-center fs-9 fw-bold">6</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Huy Hiệu Tiếp Thị (Marketing Badges)</h5>
          </div>
        </div>
        <div class="card-body p-4 space-y-3">
          <div class="form-check form-switch mb-2.5">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" checked onchange="updatePreviewCard()">
            <label class="form-check-label fs-9 fw-semibold text-dark" for="is_featured">
              <span class="text-amber-800 fw-bold">Sản phẩm nổi bật (Featured)</span>
              <span class="d-block fs-10 text-muted">Ưu tiên hiển thị tại trang chủ và banner bộ sưu tập</span>
            </label>
          </div>

          <div class="form-check form-switch mb-2.5">
            <input class="form-check-input" type="checkbox" name="is_new" value="1" id="is_new" checked onchange="updatePreviewCard()">
            <label class="form-check-label fs-9 fw-semibold text-dark" for="is_new">
              <span class="text-dark fw-bold">Hàng mới về (New Arrival)</span>
              <span class="d-block fs-10 text-muted">Gắn nhãn "MỚI" viền đen độc quyền</span>
            </label>
          </div>

          <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" name="is_best_seller" value="1" id="is_best_seller" onchange="updatePreviewCard()">
            <label class="form-check-label fs-9 fw-semibold text-dark" for="is_best_seller">
              <span class="text-danger fw-bold">Bán chạy nhất (Best Seller)</span>
              <span class="d-block fs-10 text-muted">Gắn huy hiệu HOT tại các trang danh mục</span>
            </label>
          </div>
        </div>
      </div>

      <!-- 7. ẢNH ĐẠI DIỆN CHÍNH (PRIMARY COVER STUDIO) -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center fs-9 fw-bold">7</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Ảnh Đại Diện Chính (Primary Cover)</h5>
          </div>
          <span class="text-muted fs-10">Tỷ lệ 3:4</span>
        </div>
        <div class="card-body p-4 text-center">
          <!-- Live Preview Box -->
          <div class="position-relative mx-auto mb-3 rounded-3 overflow-hidden border border-translucent bg-body-tertiary" style="width: 180px; height: 240px;">
            <img id="mainImagePreview" 
                 src="/assets/img/products/polo_01.jpg" 
                 alt="Preview" 
                 class="w-100 h-100 object-fit-cover">
            <button type="button" onclick="clearPrimaryImage()" class="btn btn-sm btn-dark position-absolute top-0 end-0 m-1.5 p-1 rounded-circle lh-1 opacity-75 hover-opacity-100" title="Gỡ ảnh">
              <i class="fa-solid fa-xmark fs-10"></i>
            </button>
          </div>

          <!-- File Upload -->
          <div class="mb-2">
            <input type="file" 
                   name="image" 
                   id="primaryImageFileInput" 
                   class="form-control form-control-sm fs-9" 
                   accept="image/*" 
                   onchange="handlePrimaryFileChange(this)">
          </div>

          <!-- Or URL input -->
          <div class="mb-3">
            <div class="input-group input-group-sm">
              <span class="input-group-text fs-10"><i class="fa-solid fa-globe"></i></span>
              <input type="text" 
                     name="image_url" 
                     id="primaryImageUrlInput" 
                     class="form-control fs-9 font-monospace" 
                     value="/assets/img/products/polo_01.jpg" 
                     placeholder="/assets/img/products/..." 
                     oninput="handlePrimaryUrlChange(this.value)">
            </div>
          </div>

          <!-- Demo Image Presets -->
          <div>
            <span class="fs-10 text-muted d-block mb-1.5">Ảnh mẫu có sẵn trong hệ thống:</span>
            <div class="d-flex justify-content-center gap-1.5 flex-wrap">
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="applyPresetImage('/assets/img/products/polo_01.jpg')">Polo 01</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="applyPresetImage('/assets/img/products/polo_02.jpg')">Polo 02</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="applyPresetImage('/assets/img/products/polo_03.jpg')">Polo 03</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="applyPresetImage('/assets/img/products/blazer_01.jpg')">Blazer</button>
              <button type="button" class="btn btn-phoenix-secondary btn-xs py-0.5 px-2 fs-10" onclick="applyPresetImage('/assets/img/products/somi_01.jpg')">Sơ Mi</button>
            </div>
          </div>
        </div>
      </div>

      <!-- 8. XEM TRƯỚC HIỂN THỊ TRÊN WEBSITE (LIVE STOREFRONT PREVIEW) -->
      <div class="card border-0 shadow-sm mb-4 rounded-3 overflow-hidden preview-card-sticky">
        <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="w-6 h-6 rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center fs-9 fw-bold">8</span>
            <h5 class="fw-bold text-body-emphasis mb-0">Mô Phỏng Thẻ Ngoài Trang Chủ</h5>
          </div>
          <span class="badge bg-dark-subtle text-dark fw-bold fs-10">LIVE PREVIEW</span>
        </div>
        <div class="card-body p-4">
          <!-- Card mô phỏng đúng giao diện của Client Storefront -->
          <div class="storefront-preview-card mx-auto" style="max-width: 260px;">
            <!-- Ảnh & Badges -->
            <div class="position-relative overflow-hidden bg-light" style="aspect-ratio: 3/4;">
              <img id="cardPreviewImg" src="/assets/img/products/polo_01.jpg" alt="Preview" class="w-100 h-100 object-fit-cover">
              
              <!-- Badges -->
              <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1 pointer-events-none">
                <span id="cardBadgeNew" class="badge bg-dark text-white px-2 py-0.5 fs-10 fw-bold">MỚI</span>
                <span id="cardBadgeFeatured" class="badge bg-warning text-dark px-2 py-0.5 fs-10 fw-bold">ATELIER</span>
                <span id="cardBadgeDiscount" class="badge bg-danger text-white px-2 py-0.5 fs-10 fw-bold">-22%</span>
              </div>

              <!-- Heart button -->
              <div class="position-absolute top-0 end-0 m-2">
                <div class="w-7 h-7 rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center text-muted">
                  <i class="fa-regular fa-heart fs-10"></i>
                </div>
              </div>
            </div>

            <!-- Nội dung chữ bên dưới -->
            <div class="p-3 text-start">
              <span class="text-uppercase text-muted fs-10 fw-bold d-block mb-1" id="cardCategoryText">ÁO POLO NAM</span>
              <h6 class="fw-bold text-dark fs-9 mb-1.5 text-truncate" id="cardTitleText">Áo Polo Nam Cotton Dệt Tổ Ong...</h6>
              
              <!-- Chấm màu swatch preview -->
              <div class="d-flex align-items-center gap-1 mb-2" id="cardColorDots">
                <span class="rounded-circle border" style="width: 10px; height: 10px; background: #111827;"></span>
                <span class="rounded-circle border" style="width: 10px; height: 10px; background: #ffffff;"></span>
                <span class="rounded-circle border" style="width: 10px; height: 10px; background: #1E3A8A;"></span>
              </div>

              <!-- Giá bán -->
              <div class="d-flex align-items-baseline gap-2">
                <span class="fw-bold text-dark fs-8" id="cardPriceText">389.000₫</span>
                <span class="text-muted text-decoration-line-through fs-10" id="cardOriginalPriceText">499.000₫</span>
              </div>

              <!-- Tồn kho -->
              <div class="mt-2 pt-2 border-top border-light d-flex align-items-center justify-content-between fs-10">
                <span class="text-success fw-semibold" id="cardStockText"><i class="fa-solid fa-circle me-1 fs-11 text-success"></i>Còn hàng (100 cái)</span>
                <span class="text-muted" id="cardVariantCountText">8 size</span>
              </div>
            </div>
          </div>

          <!-- Nút hành động chính dưới chân card -->
          <div class="mt-4 pt-2">
            <button type="button" onclick="submitProductForm('active', 'save_index')" class="btn btn-primary w-100 py-2.5 fs-9 fw-bold shadow-sm mb-2">
              <i class="fa-solid fa-cloud-arrow-up me-1"></i> Lưu &amp; Đăng Bán Ngay
            </button>
            <button type="button" onclick="submitProductForm('inactive', 'save_index')" class="btn btn-phoenix-secondary w-100 py-2 fs-9">
              <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Dưới Dạng Bản Nháp
            </button>
          </div>

        </div>
      </div>

    </div>
  </div>
</form>
@endsection

@push('scripts')
<script>
  // DỮ LIỆU STATE TOÀN CỤC CỦA TRANG TẠO SẢN PHẨM
  let selectedColors = [
    { name: 'Đen', code: '#111827' },
    { name: 'Trắng', code: '#FFFFFF' },
    { name: 'Xanh Navy', code: '#1E3A8A' }
  ];

  let selectedSizes = ['S', 'M', 'L', 'XL'];

  // 1. Khởi tạo dữ liệu khi tải trang
  document.addEventListener('DOMContentLoaded', function() {
    renderColorChips();
    renderSizeChips();
    renderVariantMatrix();
    handlePriceCalculation();
    updatePreviewCard();
  });

  // 2. Tự động sinh Slug & đếm ký tự tiêu đề
  function handleNameInput(val) {
    document.getElementById('nameCharCount').innerText = val.length + ' / 255 ký tự';
    
    // Tạo slug
    const slug = slugify(val);
    document.getElementById('slugPreviewDisplay').innerText = 'beestyle.vn/san-pham/' + (slug || 'ten-san-pham');
    
    updatePreviewCard();
  }

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

  function insertNamePrefix(prefix) {
    const input = document.getElementById('productNameInput');
    if (!input.value.includes(prefix)) {
      input.value = prefix + ' ' + input.value.trim();
    }
    handleNameInput(input.value);
  }

  // 3. Tự động sinh mã SKU theo chuẩn thời trang
  function generateSmartSku() {
    const catSelect = document.getElementById('categorySelect');
    let prefix = 'BS';
    if (catSelect && catSelect.selectedOptions.length > 0) {
      const slug = catSelect.selectedOptions[0].getAttribute('data-slug') || '';
      if (slug.includes('polo')) prefix = 'BS-PL';
      else if (slug.includes('so-mi')) prefix = 'BS-SM';
      else if (slug.includes('blazer') || slug.includes('khoac')) prefix = 'BS-BZ';
      else if (slug.includes('thun')) prefix = 'BS-TS';
      else if (slug.includes('quan')) prefix = 'BS-TR';
      else prefix = 'BS-FAS';
    }
    const randNum = Math.floor(1000 + Math.random() * 9000);
    const generatedSku = prefix + '-' + randNum;
    document.getElementById('skuInput').value = generatedSku;
    
    // Cập nhật lại matrix SKU
    renderVariantMatrix();
    updatePreviewCard();
  }

  function handleCategoryChange(sel) {
    updatePreviewCard();
  }

  // 4. Mẫu mô tả nhanh
  function fillShortDescTemplate() {
    document.getElementById('shortDescInput').value = 'Mẫu thiết kế may đo cao cấp từ xưởng BeeStyle Atelier. Sợi vải chọn lọc thoáng khí, xử lý chống nhăn, tôn trọn phong thái lịch lãm của quý ông đương đại.';
  }

  function insertTextToDescription(text) {
    const textarea = document.getElementById('detailedDescInput');
    textarea.value = textarea.value.trim() + '\n' + text;
  }

  // 5. Quản lý Color Swatches
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
    updatePreviewCard();
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
    updatePreviewCard();
  }

  function removeColor(name) {
    if (selectedColors.length <= 1) {
      alert('Sản phẩm cần tối thiểu 1 màu sắc!');
      return;
    }
    selectedColors = selectedColors.filter(c => c.name !== name);
    
    // Bỏ active trên swatches
    document.querySelectorAll('.color-swatch-btn').forEach(btn => {
      if (btn.getAttribute('data-color') === name) btn.classList.remove('active');
    });

    renderColorChips();
    renderVariantMatrix();
    updatePreviewCard();
  }

  function renderColorChips() {
    const container = document.getElementById('activeColorChips');
    const hiddenContainer = document.getElementById('hiddenColorsContainer');
    container.innerHTML = '';
    hiddenContainer.innerHTML = '';

    selectedColors.forEach(c => {
      // Render Chip
      const chip = document.createElement('span');
      chip.className = 'tag-chip';
      chip.innerHTML = `
        <span class="rounded-circle border" style="width: 10px; height: 10px; background-color: ${c.code};"></span>
        <span>${c.name}</span>
        <i class="fa-solid fa-xmark chip-remove" onclick="removeColor('${c.name}')"></i>
      `;
      container.appendChild(chip);

      // Hidden form input
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'colors[]';
      hiddenInput.value = c.name;
      hiddenContainer.appendChild(hiddenInput);
    });
  }

  // 6. Quản lý Kích Thước (Size)
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
    updatePreviewCard();
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
    updatePreviewCard();
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
    updatePreviewCard();
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
    updatePreviewCard();
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

  // 7. Render Ma Trận Biến Thể Tự Sinh (Variant Matrix Table)
  function renderVariantMatrix() {
    const tbody = document.getElementById('variantMatrixBody');
    const skuBase = document.getElementById('skuInput').value.trim() || 'BS-PROD';
    const currentPrice = parseInt(document.getElementById('productPriceInput').value) || 0;
    const totalStock = parseInt(document.getElementById('productStockInput').value) || 0;

    const totalVariants = selectedColors.length * selectedSizes.length;
    document.getElementById('totalVariantsCounterBadge').innerText = totalVariants + ' biến thể';
    
    const baseStockPerVar = Math.floor(totalStock / Math.max(1, totalVariants));
    let remainder = totalStock % Math.max(1, totalVariants);

    tbody.innerHTML = '';

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
                   style="width: 75px;" 
                   oninput="recalcTotalStockFromMatrix()">
          </td>
        `;
        tbody.appendChild(tr);
      });
    });
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
  }

  function recalcTotalStockFromMatrix() {
    let sum = 0;
    document.querySelectorAll('input[name^="variant_stock"]').forEach(inp => {
      sum += parseInt(inp.value) || 0;
    });
    document.getElementById('productStockInput').value = sum;
    handleStockChange(sum);
    updatePreviewCard();
  }

  // 8. Tính toán giá bán, chiết khấu & tồn kho
  function handlePriceCalculation() {
    const price = parseInt(document.getElementById('productPriceInput').value) || 0;
    const originalPrice = parseInt(document.getElementById('productOriginalPriceInput').value) || 0;

    document.getElementById('formattedPriceText').innerText = price.toLocaleString('vi-VN') + ' VNĐ';

    let discount = 0;
    let savings = 0;
    if (originalPrice > price && originalPrice > 0) {
      savings = originalPrice - price;
      discount = Math.round((savings / originalPrice) * 100);
    }

    const badge = document.getElementById('discountBadge');
    if (discount > 0) {
      badge.innerText = `Giảm ${discount}%`;
      badge.className = 'badge bg-danger text-white fw-bold';
      document.getElementById('savingsAmountText').innerText = `Tiết kiệm: ${savings.toLocaleString('vi-VN')} ₫`;
    } else {
      badge.innerText = 'Giá chuẩn';
      badge.className = 'badge bg-secondary-subtle text-secondary fw-semibold';
      document.getElementById('savingsAmountText').innerText = 'Không có chiết khấu';
    }

    updatePreviewCard();
  }

  function applyDiscountPercent(pct) {
    const originalPrice = parseInt(document.getElementById('productOriginalPriceInput').value) || 499000;
    const newPrice = Math.round(originalPrice * (1 - pct / 100) / 1000) * 1000;
    document.getElementById('productPriceInput').value = newPrice;
    handlePriceCalculation();
  }

  function handleStockChange(val) {
    const stock = parseInt(val) || 0;
    const textEl = document.getElementById('stockStatusText');
    if (stock <= 0) {
      textEl.innerText = 'Hết hàng (Cần nhập)';
      textEl.className = 'fs-10 text-danger fw-bold';
    } else if (stock <= 5) {
      textEl.innerText = 'Cảnh báo: Sắp hết kho';
      textEl.className = 'fs-10 text-warning-emphasis fw-bold';
    } else {
      textEl.innerText = 'Kho dồi dào (' + stock + ' cái)';
      textEl.className = 'fs-10 text-success fw-bold';
    }
    updatePreviewCard();
  }

  // 9. Quản lý Trạng Thái (Active / Inactive)
  function setStatusOption(status) {
    document.getElementById('formStatusInput').value = status;
    const activeCard = document.getElementById('statusActiveCard');
    const inactiveCard = document.getElementById('statusInactiveCard');
    const headerBadge = document.getElementById('headerStatusBadge');

    if (status === 'active') {
      activeCard.className = 'status-radio-card active text-center';
      inactiveCard.className = 'status-radio-card text-center';
      headerBadge.className = 'badge badge-phoenix badge-phoenix-primary fs-10 fw-bold px-2 py-1';
      headerBadge.innerHTML = '<i class="fa-solid fa-bolt me-1"></i> ĐANG MỞ BÁN';
    } else {
      activeCard.className = 'status-radio-card text-center';
      inactiveCard.className = 'status-radio-card inactive text-center';
      headerBadge.className = 'badge badge-phoenix badge-phoenix-secondary fs-10 fw-bold px-2 py-1';
      headerBadge.innerHTML = '<i class="fa-solid fa-eye-slash me-1"></i> TẠM ẨN (BẢN NHÁP)';
    }
  }

  // 10. Quản lý Ảnh Đại Diện Chính & Thư Viện Ảnh Phụ
  function handlePrimaryFileChange(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('mainImagePreview').src = e.target.result;
        document.getElementById('cardPreviewImg').src = e.target.result;
        document.getElementById('primaryImageUrlInput').value = '';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  function handlePrimaryUrlChange(url) {
    if (url && url.trim()) {
      document.getElementById('mainImagePreview').src = url;
      document.getElementById('cardPreviewImg').src = url;
    }
  }

  function applyPresetImage(url) {
    document.getElementById('mainImagePreview').src = url;
    document.getElementById('cardPreviewImg').src = url;
    document.getElementById('primaryImageUrlInput').value = url;
    document.getElementById('primaryImageFileInput').value = '';
  }

  function clearPrimaryImage() {
    applyPresetImage('/assets/img/products/polo_01.jpg');
  }

  // Gallery Multi-Image Upload Preview
  function handleGalleryFiles(input) {
    const grid = document.getElementById('galleryPreviewGrid');
    grid.innerHTML = '';
    const files = input.files;
    document.getElementById('galleryCountBadge').innerText = files.length + ' ảnh đã chọn';

    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const reader = new FileReader();
      reader.onload = function(e) {
        const col = document.createElement('div');
        col.className = 'col-3 col-md-2';
        col.innerHTML = `
          <div class="position-relative rounded-2 overflow-hidden border border-translucent bg-light" style="aspect-ratio: 1/1;">
            <img src="${e.target.result}" class="w-100 h-100 object-fit-cover">
            <span class="position-absolute bottom-0 start-0 w-100 text-center text-white bg-dark bg-opacity-75 fs-11 py-0.5">${(file.size/1024).toFixed(0)}KB</span>
          </div>
        `;
        grid.appendChild(col);
      };
      reader.readAsDataURL(file);
    }
  }

  // 11. Cập nhật Live Storefront Card Preview
  function updatePreviewCard() {
    const name = document.getElementById('productNameInput').value.trim() || 'Tên sản phẩm thời trang...';
    document.getElementById('cardTitleText').innerText = name;

    const catSelect = document.getElementById('categorySelect');
    let catName = 'THỜI TRANG NAM';
    if (catSelect && catSelect.selectedOptions.length > 0 && catSelect.value) {
      catName = catSelect.selectedOptions[0].text.trim().toUpperCase();
    }
    document.getElementById('cardCategoryText').innerText = catName;

    const price = parseInt(document.getElementById('productPriceInput').value) || 0;
    const originalPrice = parseInt(document.getElementById('productOriginalPriceInput').value) || 0;

    document.getElementById('cardPriceText').innerText = price.toLocaleString('vi-VN') + '₫';
    
    const origPriceEl = document.getElementById('cardOriginalPriceText');
    const badgeDiscount = document.getElementById('cardBadgeDiscount');
    
    if (originalPrice > price) {
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
      more.className = 'fs-11 text-muted';
      more.innerText = `+${selectedColors.length - 5}`;
      dotsContainer.appendChild(more);
    }

    // Stock & Variants text
    const stock = parseInt(document.getElementById('productStockInput').value) || 0;
    const stockText = document.getElementById('cardStockText');
    if (stock > 0) {
      stockText.innerHTML = `<i class="fa-solid fa-circle me-1 fs-11 text-success"></i>Còn hàng (${stock} cái)`;
      stockText.className = 'text-success fw-semibold';
    } else {
      stockText.innerHTML = `<i class="fa-solid fa-circle me-1 fs-11 text-danger"></i>Hết hàng`;
      stockText.className = 'text-danger fw-semibold';
    }

    document.getElementById('cardVariantCountText').innerText = `${selectedSizes.length} size`;
  }

  // 12. Submit Form Action Dispatcher
  function submitProductForm(status, saveAction) {
    if (status) {
      document.getElementById('formStatusInput').value = status;
    }
    if (saveAction) {
      document.getElementById('formSaveActionInput').value = saveAction;
    }

    const form = document.getElementById('createProductForm');
    
    // HTML5 native validation check
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    form.submit();
  }
</script>
@endpush
