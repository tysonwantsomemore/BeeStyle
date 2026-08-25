@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Thời Trang | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">KHO HÀNG &amp; SẢN PHẨM</span>
        <h3 class="fw-bold text-dark mb-0">Quản Lý Sản Phẩm Thời Trang</h3>
      </div>
      <p class="text-muted small mb-0">Theo dõi tồn kho, giá bán niêm yết, bật/tắt kinh doanh và quản lý các phân loại màu sắc &amp; kích cỡ</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-bee-primary btn-sm px-3 shadow-xs">
      <i class="fa-solid fa-plus me-1.5"></i> Thêm Sản Phẩm Mới
    </a>
  </div>
</div>

<!-- 4 THẺ KPI TỔNG QUAN KHO HÀNG -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng Sản Phẩm -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card border-warning-subtle shadow-sm transition-all hover-lift" 
         style="border-left: 4px solid var(--atino-gold) !important; cursor: pointer; background: #ffffff;"
         onclick="openKpiModal('all')"
         data-bs-toggle="popover"
         data-bs-trigger="hover"
         data-bs-placement="top"
         data-bs-html="true"
         data-bs-title="<div class='fw-bold text-dark'><i class='fa-solid fa-shirt text-warning me-1.5'></i> Toàn Bộ Mẫu Trong Kho</div>"
         data-bs-content="<div class='small text-dark py-1'><div class='mb-1'>• Tổng mẫu mã: <b>{{ $totalProductsCount }} mẫu</b></div><div class='mb-1'>• Số lượng tồn kho: <b>{{ $kpiDetailData['all']['metrics'][1]['value'] }}</b></div><div class='mb-1'>• Tổng giá trị kho: <b class='text-danger'>{{ $kpiDetailData['all']['metrics'][2]['value'] }}</b></div><div class='mb-1'>• Đã xuất bán: <b class='text-success'>{{ $kpiDetailData['all']['metrics'][3]['value'] }}</b></div><div class='mt-2 pt-1 border-top text-primary fw-bold'><i class='fa-solid fa-arrow-pointer me-1'></i> Bấm để mở danh sách chi tiết</div></div>">
      <div class="d-flex justify-content-between align-items-center mb-1.5">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Mẫu Trong Kho</span>
          <h3 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.65rem;">{{ $totalProductsCount }} <span class="fs-6 text-muted fw-normal">mẫu</span></h3>
        </div>
        <div class="bee-stat-icon primary shadow-xs">
          <i class="fa-solid fa-shirt"></i>
        </div>
      </div>
      
      <div class="small text-muted mb-2 pt-1" style="font-size: 0.76rem; line-height: 1.5;">
        <div><i class="fa-solid fa-boxes-stacked me-1 text-primary"></i> Tồn kho: <strong class="text-dark">{{ $kpiDetailData['all']['metrics'][1]['value'] }}</strong></div>
        <div class="text-truncate"><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị: <strong class="text-danger">{{ $kpiDetailData['all']['metrics'][2]['value'] }}</strong></div>
      </div>

      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-primary small fw-bold"><i class="fa-solid fa-arrow-pointer me-1"></i> Bấm xem chi tiết</span>
        <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Tất cả</span>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Đang Kinh Doanh -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card border-success-subtle shadow-sm transition-all hover-lift" 
         style="border-left: 4px solid #10b981 !important; cursor: pointer; background: #ffffff;"
         onclick="openKpiModal('active')"
         data-bs-toggle="popover"
         data-bs-trigger="hover"
         data-bs-placement="top"
         data-bs-html="true"
         data-bs-title="<div class='fw-bold text-success'><i class='fa-solid fa-circle-check text-success me-1.5'></i> Đang Mở Bán Công Khai</div>"
         data-bs-content="<div class='small text-dark py-1'><div class='mb-1'>• Mẫu đang bán: <b class='text-success'>{{ $activeProductsCount }} mẫu</b></div><div class='mb-1'>• Số cái mở bán: <b>{{ $kpiDetailData['active']['metrics'][1]['value'] }}</b></div><div class='mb-1'>• Giá trị hàng bán: <b class='text-danger'>{{ $kpiDetailData['active']['metrics'][2]['value'] }}</b></div><div class='mb-1'>• Đơn giá trung bình: <b class='text-primary'>{{ $kpiDetailData['active']['metrics'][3]['value'] }}</b></div><div class='mt-2 pt-1 border-top text-success fw-bold'><i class='fa-solid fa-arrow-pointer me-1'></i> Bấm để mở danh sách chi tiết</div></div>">
      <div class="d-flex justify-content-between align-items-center mb-1.5">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Đang Mở Bán Công Khai</span>
          <h3 class="fw-bold text-success mb-0 mt-1" style="font-size: 1.65rem;">{{ $activeProductsCount }} <span class="fs-6 text-muted fw-normal">mẫu</span></h3>
        </div>
        <div class="bee-stat-icon success shadow-xs">
          <i class="fa-solid fa-circle-check"></i>
        </div>
      </div>
      
      <div class="small text-muted mb-2 pt-1" style="font-size: 0.76rem; line-height: 1.5;">
        <div><i class="fa-solid fa-globe me-1 text-success"></i> Mở bán: <strong class="text-dark">{{ $kpiDetailData['active']['metrics'][1]['value'] }}</strong></div>
        <div class="text-truncate"><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị: <strong class="text-danger">{{ $kpiDetailData['active']['metrics'][2]['value'] }}</strong></div>
      </div>

      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-bold"><i class="fa-solid fa-arrow-pointer me-1"></i> Bấm xem chi tiết</span>
        <span class="badge bg-success text-white fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Kinh doanh</span>
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Đang Tạm Dừng -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card border-secondary-subtle shadow-sm transition-all hover-lift" 
         style="border-left: 4px solid #64748b !important; cursor: pointer; background: #ffffff;"
         onclick="openKpiModal('inactive')"
         data-bs-toggle="popover"
         data-bs-trigger="hover"
         data-bs-placement="top"
         data-bs-html="true"
         data-bs-title="<div class='fw-bold text-secondary'><i class='fa-solid fa-eye-slash text-secondary me-1.5'></i> Đang Ẩn / Tạm Dừng</div>"
         data-bs-content="<div class='small text-dark py-1'><div class='mb-1'>• Mẫu tạm dừng: <b class='text-secondary'>{{ $inactiveProductsCount }} mẫu</b></div><div class='mb-1'>• Số cái lưu kho: <b>{{ $kpiDetailData['inactive']['metrics'][1]['value'] }}</b></div><div class='mb-1'>• Giá trị hàng ẩn: <b class='text-danger'>{{ $kpiDetailData['inactive']['metrics'][2]['value'] }}</b></div><div class='mb-1'>• Đã từng bán: <b class='text-muted'>{{ $kpiDetailData['inactive']['metrics'][3]['value'] }}</b></div><div class='mt-2 pt-1 border-top text-secondary fw-bold'><i class='fa-solid fa-arrow-pointer me-1'></i> Bấm để mở danh sách chi tiết</div></div>">
      <div class="d-flex justify-content-between align-items-center mb-1.5">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Đang Ẩn / Tạm Dừng</span>
          <h3 class="fw-bold text-secondary mb-0 mt-1" style="font-size: 1.65rem;">{{ $inactiveProductsCount }} <span class="fs-6 text-muted fw-normal">mẫu</span></h3>
        </div>
        <div class="bee-stat-icon info shadow-xs">
          <i class="fa-solid fa-eye-slash"></i>
        </div>
      </div>
      
      <div class="small text-muted mb-2 pt-1" style="font-size: 0.76rem; line-height: 1.5;">
        <div><i class="fa-solid fa-lock me-1 text-secondary"></i> Lưu kho: <strong class="text-dark">{{ $kpiDetailData['inactive']['metrics'][1]['value'] }}</strong></div>
        <div class="text-truncate"><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị: <strong class="text-danger">{{ $kpiDetailData['inactive']['metrics'][2]['value'] }}</strong></div>
      </div>

      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-secondary small fw-bold"><i class="fa-solid fa-arrow-pointer me-1"></i> Bấm xem chi tiết</span>
        <span class="badge bg-secondary text-white fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Tạm dừng</span>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Cảnh Báo Tồn Kho -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card border-danger-subtle shadow-sm transition-all hover-lift" 
         style="border-left: 4px solid #ef4444 !important; cursor: pointer; background: #ffffff;"
         onclick="openKpiModal('low_stock')"
         data-bs-toggle="popover"
         data-bs-trigger="hover"
         data-bs-placement="top"
         data-bs-html="true"
         data-bs-title="<div class='fw-bold text-danger'><i class='fa-solid fa-triangle-exclamation text-danger me-1.5'></i> Cảnh Báo Tồn Kho (≤ 5)</div>"
         data-bs-content="<div class='small text-dark py-1'><div class='mb-1'>• Tổng mẫu cảnh báo: <b class='text-danger'>{{ $lowStockProductsCount }} mẫu</b></div><div class='mb-1'>• Mẫu hết sạch kho: <b class='text-danger'>{{ $kpiDetailData['low_stock']['metrics'][1]['value'] }}</b></div><div class='mb-1'>• Mẫu sắp hết (1-5 cái): <b class='text-warning'>{{ $kpiDetailData['low_stock']['metrics'][2]['value'] }}</b></div><div class='mb-1'>• Tổng cái còn lại: <b>{{ $kpiDetailData['low_stock']['metrics'][3]['value'] }}</b></div><div class='mt-2 pt-1 border-top text-danger fw-bold'><i class='fa-solid fa-arrow-pointer me-1'></i> Bấm để mở danh sách chi tiết</div></div>">
      <div class="d-flex justify-content-between align-items-center mb-1.5">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Cảnh Báo Tồn Kho (≤ 5)</span>
          <h3 class="fw-bold text-danger mb-0 mt-1" style="font-size: 1.65rem;">{{ $lowStockProductsCount }} <span class="fs-6 text-muted fw-normal">mẫu</span></h3>
        </div>
        <div class="bee-stat-icon danger shadow-xs">
          <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
      </div>
      
      <div class="small text-muted mb-2 pt-1" style="font-size: 0.76rem; line-height: 1.5;">
        <div><i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Hết kho: <strong class="text-danger">{{ $kpiDetailData['low_stock']['metrics'][1]['value'] }}</strong></div>
        <div class="text-truncate"><i class="fa-solid fa-triangle-exclamation me-1 text-warning"></i> Còn ít (1-5): <strong class="text-warning">{{ $kpiDetailData['low_stock']['metrics'][2]['value'] }}</strong></div>
      </div>

      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-danger small fw-bold"><i class="fa-solid fa-arrow-pointer me-1"></i> Bấm xem chi tiết</span>
        <span class="badge bg-danger text-white fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Cần nhập</span>
      </div>
    </div>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR (Unified Category, Brand, Status & Search Filters) -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm tên hoặc mã SKU..." style="width: 240px;">
      
      <!-- Lọc Danh mục -->
      <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 170px;">
        <option value="">Tất cả danh mục</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>

      <!-- Lọc Thương hiệu -->
      <select name="brand_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 170px;">
        <option value="">Tất cả thương hiệu</option>
        @foreach($brands as $b)
          <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
        @endforeach
      </select>

      <!-- Lọc Trạng thái Kinh doanh / Tồn kho -->
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 180px;">
        <option value="">Tất cả trạng thái</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Đang kinh doanh</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>⚪ Đang tạm dừng</option>
        <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>🔴 Sắp hết / Hết hàng (≤5)</option>
      </select>

      <button type="submit" class="btn btn-sm btn-outline-secondary">Lọc</button>
      @if(request('q') || request('category_id') || request('brand_id') || request('status'))
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>

    <div class="text-muted small">
      Hiển thị: <strong>{{ $products->total() }}</strong> sản phẩm
    </div>
  </div>

  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Mã SKU</th>
          <th>Sản Phẩm</th>
          <th>Danh Mục / Thương Hiệu</th>
          <th>Giá Bán</th>
          <th>Giá Gốc</th>
          <th>Tồn Kho</th>
          <th>Đã Bán</th>
          <th>Đánh Giá</th>
          <th>Trạng Thái (1-Click Đổi)</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
          <tr>
            <td><span class="font-monospace fw-bold text-secondary">{{ $product->sku }}</span></td>
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 44px; height: 44px; object-fit: contain;" class="rounded border bg-white shadow-xs">
                <div>
                  <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="fw-bold small text-dark text-decoration-none d-block text-truncate" style="max-width: 200px;">
                    {{ $product->name }}
                  </a>
                  <small class="text-muted">{{ $product->variants->count() }} biến thể màu/size</small>
                </div>
              </div>
            </td>
            <td>
              <div class="d-flex flex-column gap-1">
                <span class="badge bg-light text-dark border w-fit">{{ $product->category->name ?? 'Thời trang nam' }}</span>
                @if($product->brand)
                  <small class="text-muted"><i class="fa-solid fa-tag me-1"></i>{{ $product->brand->name }}</small>
                @endif
              </div>
            </td>
            <td><strong class="text-danger">{{ number_format($product->price, 0, ',', '.') }}₫</strong></td>
            <td><span class="text-muted text-decoration-line-through small">{{ number_format($product->original_price, 0, ',', '.') }}₫</span></td>
            <td>
              @if($product->stock <= 0)
                <span class="badge bg-danger text-white fw-bold"><i class="fa-solid fa-xmark me-1"></i> Hết hàng</span>
              @elseif($product->stock <= 5)
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
            <!-- TRẠNG THÁI: 1-CLICK TOGGLE BUTTON -->
            <td>
              <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" class="d-inline">
                @csrf
                @if(!$product->is_active)
                  <button type="submit" class="btn btn-sm btn-light border py-1 px-2.5 text-muted fw-bold shadow-xs" title="Bấm 1-Click để kích hoạt mở bán lại sản phẩm này">
                    <i class="fa-solid fa-eye-slash me-1 text-secondary"></i> Tạm dừng
                  </button>
                @elseif($product->stock <= 0)
                  <button type="submit" class="btn btn-sm btn-danger-subtle border border-danger-subtle py-1 px-2.5 text-danger fw-bold shadow-xs" title="Kho đã hết hàng (Bấm 1-Click để tạm dừng ẩn sản phẩm)">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> Hết hàng (Bật)
                  </button>
                @else
                  <button type="submit" class="btn btn-sm btn-success-subtle border border-success-subtle py-1 px-2.5 text-success fw-bold shadow-xs" title="Bấm 1-Click để tạm dừng kinh doanh (ẩn khỏi web)">
                    <i class="fa-solid fa-circle-check me-1"></i> Kinh doanh
                  </button>
                @endif
              </form>
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
    <div class="card-footer d-flex justify-content-center py-3 bg-white border-top">
      {{ $products->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL XEM THÔNG SỐ CHI TIẾT CHO TỪNG THẺ KPI -->
<div class="modal fade" id="kpiProductDetailModal" tabindex="-1" aria-labelledby="kpiProductDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 18px 24px;">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center shadow" id="mdlKpiIconContainer" style="width: 44px; height: 44px; font-size: 1.2rem; background: #f59e0b; color: #111827;">
            <i class="fa-solid fa-shirt" id="mdlKpiIcon"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2">
              <h5 class="modal-title fw-bold text-white mb-0" id="mdlKpiTitle">Thông Số Chi Tiết Kho Hàng</h5>
              <span class="badge px-2 py-0.5 fw-bold" id="mdlKpiBadge">TỔNG QUAN</span>
            </div>
            <small class="text-white-50" id="mdlKpiSubtitle">Theo dõi toàn bộ số liệu và các mặt hàng trong nhóm</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4 bg-light">
        
        <!-- 4 THẺ THÔNG SỐ METRICS DYNAMIC -->
        <div class="row g-3 mb-4" id="mdlMetricsContainer">
          <!-- Populated by JS -->
        </div>

        <!-- SEARCH BAR TRONG MODAL -->
        <div class="card border-0 shadow-sm p-3 mb-3 bg-white rounded-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="input-group input-group-sm" style="max-width: 380px;">
              <span class="input-group-text bg-light border text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
              <input type="text" id="kpiSearchInput" class="form-control" placeholder="Tìm nhanh theo tên sản phẩm, mã SKU, danh mục...">
            </div>
            <div class="text-muted small">
              Hiển thị: <strong id="kpiVisibleCount">0</strong> sản phẩm
            </div>
          </div>
        </div>

        <!-- BẢNG DANH SÁCH SẢN PHẨM TRONG NHÓM -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="kpiProductsTable">
              <thead class="table-light">
                <tr>
                  <th style="width: 50px;" class="text-center">#</th>
                  <th>Mã SKU</th>
                  <th>Sản Phẩm</th>
                  <th>Danh Mục</th>
                  <th>Giá Bán</th>
                  <th>Tồn Kho</th>
                  <th>Đã Bán</th>
                  <th>Đánh Giá</th>
                  <th>Trạng Thái</th>
                  <th class="text-end" style="width: 130px;">Thao Tác</th>
                </tr>
              </thead>
              <tbody id="kpiProductsTbody">
                <!-- Populated by JS -->
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer border-top bg-white py-2.5 px-4 d-flex justify-content-between align-items-center">
        <a href="#" id="mdlFilterLink" class="btn btn-sm btn-outline-dark fw-bold px-3">
          <i class="fa-solid fa-filter me-1.5"></i> Lọc Ngoài Danh Sách Chính
        </a>
        <button type="button" class="btn btn-secondary btn-sm px-4 rounded-2 fw-bold" data-bs-dismiss="modal">
          Đóng Hộp Thoại
        </button>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
  const kpiData = @json($kpiDetailData);

  function openKpiModal(groupKey) {
    const data = kpiData[groupKey];
    if (!data) return;

    const modalEl = document.getElementById('kpiProductDetailModal');
    if (!modalEl) return;

    document.getElementById('mdlKpiTitle').textContent = data.title;
    document.getElementById('mdlKpiSubtitle').textContent = data.subtitle;
    
    const badgeEl = document.getElementById('mdlKpiBadge');
    badgeEl.textContent = data.badge;
    badgeEl.className = 'badge px-2 py-0.5 fw-bold ' + data.badge_class;

    const iconEl = document.getElementById('mdlKpiIcon');
    iconEl.className = data.icon;

    document.getElementById('mdlFilterLink').href = data.filter_url;

    const metricsContainer = document.getElementById('mdlMetricsContainer');
    metricsContainer.innerHTML = '';
    data.metrics.forEach(m => {
      metricsContainer.innerHTML += `
        <div class="col-md-3 col-6">
          <div class="p-3 bg-white rounded-3 border shadow-xs text-center h-100">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem;">${m.label}</span>
            <h4 class="fw-bold mb-0 ${m.color}" style="font-size: 1.35rem;">${m.value}</h4>
          </div>
        </div>
      `;
    });

    const tbody = document.getElementById('kpiProductsTbody');
    tbody.innerHTML = '';
    const products = data.products || [];

    if (products.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="10" class="text-center py-5 text-muted">
            <i class="fa-solid fa-box-open fs-2 text-secondary-subtle mb-2 d-block"></i>
            Không có sản phẩm nào trong nhóm này.
          </td>
        </tr>
      `;
    } else {
      products.forEach((p, idx) => {
        let stockBadge = '';
        if (p.stock <= 0) {
          stockBadge = '<span class="badge bg-danger text-white fw-bold"><i class="fa-solid fa-xmark me-1"></i> Hết hàng</span>';
        } else if (p.stock <= 5) {
          stockBadge = `<span class="badge bg-danger-subtle text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Còn ${p.stock}</span>`;
        } else {
          stockBadge = `<strong class="text-dark">${p.stock} cái</strong>`;
        }

        let statusBadge = '';
        if (p.status === 'active' && p.stock > 0) {
          statusBadge = '<span class="badge bg-success-subtle text-success fw-bold py-1 px-2"><i class="fa-solid fa-circle-check me-1"></i> Kinh doanh</span>';
        } else if (p.status === 'active' && p.stock <= 0) {
          statusBadge = '<span class="badge bg-danger-subtle text-danger fw-bold py-1 px-2"><i class="fa-solid fa-circle-exclamation me-1"></i> Hết hàng</span>';
        } else {
          statusBadge = '<span class="badge bg-secondary-subtle text-muted fw-bold py-1 px-2"><i class="fa-solid fa-eye-slash me-1"></i> Tạm dừng</span>';
        }

        tbody.innerHTML += `
          <tr class="kpi-prod-row" data-search="${(p.name + ' ' + p.sku + ' ' + p.category).toLowerCase()}">
            <td class="text-center text-muted fw-bold small">${idx + 1}</td>
            <td><span class="font-monospace fw-bold text-secondary">${p.sku}</span></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="${p.image}" alt="${p.name}" style="width: 38px; height: 38px; object-fit: contain;" class="rounded border bg-white shadow-xs">
                <div class="text-truncate" style="max-width: 200px;">
                  <strong class="text-dark d-block small text-truncate">${p.name}</strong>
                  ${p.brand ? `<small class="text-muted">${p.brand}</small>` : ''}
                </div>
              </div>
            </td>
            <td><span class="badge bg-light text-dark border">${p.category}</span></td>
            <td><strong class="text-danger">${p.price_formatted}</strong></td>
            <td>${stockBadge}</td>
            <td><span class="badge bg-success-subtle text-success fw-bold">${p.sold_count}</span></td>
            <td><span class="text-warning small fw-bold">⭐ ${p.rating}</span></td>
            <td>${statusBadge}</td>
            <td class="text-end">
              <div class="d-flex align-items-center justify-content-end gap-1">
                <a href="${p.url}" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Xem ngoài web">
                  <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
                <a href="${p.edit_url}" target="_blank" class="btn btn-sm btn-outline-dark py-1 px-2 fw-bold" title="Chỉnh sửa sản phẩm">
                  <i class="fa-solid fa-pen-to-square"></i>
                </a>
              </div>
            </td>
          </tr>
        `;
      });
    }

    document.getElementById('kpiVisibleCount').textContent = products.length;
    document.getElementById('kpiSearchInput').value = '';

    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(el => {
      const popInstance = bootstrap.Popover.getInstance(el);
      if (popInstance) popInstance.hide();
    });

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  }

  document.addEventListener('DOMContentLoaded', function() {
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl, {
      sanitize: false,
      delay: { "show": 100, "hide": 100 }
    }));

    const searchInput = document.getElementById('kpiSearchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('#kpiProductsTbody .kpi-prod-row');
        let visible = 0;

        rows.forEach(row => {
          const text = row.getAttribute('data-search') || '';
          if (text.includes(query)) {
            row.style.display = '';
            visible++;
          } else {
            row.style.display = 'none';
          }
        });

        document.getElementById('kpiVisibleCount').textContent = visible;
      });
    }
  });
</script>
@endpush
@endsection