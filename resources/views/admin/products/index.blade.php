@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Thời Trang | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">KHO HÀNG &amp; SẢN PHẨM</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Sản Phẩm Thời Trang</h2>
    </div>
    <p class="text-body-tertiary mb-0">Theo dõi tồn kho, giá bán niêm yết, bật/tắt kinh doanh và quản lý các phân loại màu sắc &amp; kích cỡ</p>
  </div>
  <div class="col-auto">
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
      <span class="fas fa-plus me-1"></span> Thêm Sản Phẩm Mới
    </a>
  </div>
</div>

<!-- 4 THẺ KPI TỔNG QUAN KHO HÀNG -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng Sản Phẩm -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="openKpiModal('all')">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10" style="letter-spacing: 0.05em;">Tổng Mẫu Trong Kho</h6>
            <h3 class="text-body-emphasis mb-0 fw-bold">{{ $totalProductsCount }} <span class="fs-9 text-body-tertiary fw-normal">mẫu</span></h3>
          </div>
          <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-shirt fs-8"></i>
          </div>
        </div>
        <div class="fs-10 text-body-secondary mb-2 pt-1">
          <div><i class="fa-solid fa-boxes-stacked me-1 text-primary"></i> Tồn kho: <strong class="text-body-emphasis">{{ $kpiDetailData['all']['metrics'][1]['value'] }}</strong></div>
          <div class="text-truncate"><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị: <strong class="text-danger">{{ $kpiDetailData['all']['metrics'][2]['value'] }}</strong></div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent">
          <span class="text-primary fs-10 fw-semibold"><i class="fa-solid fa-arrow-pointer me-1"></i> Xem chi tiết</span>
          <span class="badge badge-phoenix badge-phoenix-primary fs-10">Tất cả</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Đang Kinh Doanh -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="openKpiModal('active')">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10" style="letter-spacing: 0.05em;">Đang Mở Bán</h6>
            <h3 class="text-success mb-0 fw-bold">{{ $activeProductsCount }} <span class="fs-9 text-body-tertiary fw-normal">mẫu</span></h3>
          </div>
          <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-circle-check fs-8"></i>
          </div>
        </div>
        <div class="fs-10 text-body-secondary mb-2 pt-1">
          <div><i class="fa-solid fa-globe me-1 text-success"></i> Mở bán: <strong class="text-body-emphasis">{{ $kpiDetailData['active']['metrics'][1]['value'] }}</strong></div>
          <div class="text-truncate"><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị: <strong class="text-danger">{{ $kpiDetailData['active']['metrics'][2]['value'] }}</strong></div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent">
          <span class="text-success fs-10 fw-semibold"><i class="fa-solid fa-arrow-pointer me-1"></i> Xem chi tiết</span>
          <span class="badge badge-phoenix badge-phoenix-success fs-10">Kinh doanh</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Đang Tạm Dừng -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="openKpiModal('inactive')">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10" style="letter-spacing: 0.05em;">Đang Ẩn / Tạm Dừng</h6>
            <h3 class="text-secondary mb-0 fw-bold">{{ $inactiveProductsCount }} <span class="fs-9 text-body-tertiary fw-normal">mẫu</span></h3>
          </div>
          <div class="d-flex align-items-center justify-content-center bg-secondary-subtle text-secondary rounded-circle" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-eye-slash fs-8"></i>
          </div>
        </div>
        <div class="fs-10 text-body-secondary mb-2 pt-1">
          <div><i class="fa-solid fa-lock me-1 text-secondary"></i> Lưu kho: <strong class="text-body-emphasis">{{ $kpiDetailData['inactive']['metrics'][1]['value'] }}</strong></div>
          <div class="text-truncate"><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị: <strong class="text-danger">{{ $kpiDetailData['inactive']['metrics'][2]['value'] }}</strong></div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent">
          <span class="text-secondary fs-10 fw-semibold"><i class="fa-solid fa-arrow-pointer me-1"></i> Xem chi tiết</span>
          <span class="badge badge-phoenix badge-phoenix-secondary fs-10">Tạm dừng</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Cảnh Báo Tồn Kho -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100" style="cursor: pointer;" onclick="openKpiModal('low_stock')">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10" style="letter-spacing: 0.05em;">Cảnh Báo Tồn Kho (≤ 5)</h6>
            <h3 class="text-danger mb-0 fw-bold">{{ $lowStockProductsCount }} <span class="fs-9 text-body-tertiary fw-normal">mẫu</span></h3>
          </div>
          <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-triangle-exclamation fs-8"></i>
          </div>
        </div>
        <div class="fs-10 text-body-secondary mb-2 pt-1">
          <div><i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Hết kho: <strong class="text-danger">{{ $kpiDetailData['low_stock']['metrics'][1]['value'] }}</strong></div>
          <div class="text-truncate"><i class="fa-solid fa-triangle-exclamation me-1 text-warning"></i> Còn ít (1-5): <strong class="text-warning">{{ $kpiDetailData['low_stock']['metrics'][2]['value'] }}</strong></div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent">
          <span class="text-danger fs-10 fw-semibold"><i class="fa-solid fa-arrow-pointer me-1"></i> Xem chi tiết</span>
          <span class="badge badge-phoenix badge-phoenix-danger fs-10">Cần nhập</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- PRODUCTS TABLE CARD -->
<div class="card border-0 shadow-sm mb-4">
  <!-- FILTER TOOLBAR -->
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <div class="position-relative">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm search-input" placeholder="Tìm tên hoặc mã SKU..." style="width: 220px;">
      </div>
      
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

      <button type="submit" class="btn btn-sm btn-phoenix-secondary">Lọc</button>
      @if(request('q') || request('category_id') || request('brand_id') || request('status'))
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>

    <div class="text-body-tertiary fs-10">
      Tổng cộng: <strong class="text-body-emphasis">{{ $products->total() }}</strong> sản phẩm
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2">Mã SKU</th>
            <th class="py-2">Sản Phẩm</th>
            <th class="py-2">Danh Mục / Hiệu</th>
            <th class="py-2">Giá Bán</th>
            <th class="py-2">Giá Gốc</th>
            <th class="py-2">Tồn Kho</th>
            <th class="py-2">Đã Bán</th>
            <th class="py-2">Đánh Giá</th>
            <th class="py-2">Trạng Thái (1-Click)</th>
            <th class="text-end pe-3 py-2">Hành Động</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($products as $product)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <td class="ps-3 py-2"><span class="font-monospace fw-bold text-primary">{{ $product->sku }}</span></td>
              <td class="py-2">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 40px; height: 40px; object-fit: contain;" class="rounded border border-translucent bg-body-emphasis">
                  <div>
                    <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="fw-bold text-body-emphasis text-decoration-none d-block text-truncate fs-9" style="max-width: 200px;">
                      {{ $product->name }}
                    </a>
                    <small class="text-body-tertiary fs-10">{{ $product->variants->count() }} biến thể màu/size</small>
                  </div>
                </div>
              </td>
              <td class="py-2">
                <div class="d-flex flex-column gap-1">
                  <span class="badge badge-phoenix badge-phoenix-secondary w-fit">{{ $product->category->name ?? 'Thời trang nam' }}</span>
                  @if($product->brand)
                    <small class="text-body-tertiary fs-10"><i class="fa-solid fa-tag me-1"></i>{{ $product->brand->name }}</small>
                  @endif
                </div>
              </td>
              <td class="py-2"><strong class="text-danger">{{ number_format($product->price, 0, ',', '.') }}₫</strong></td>
              <td class="py-2"><span class="text-body-tertiary text-decoration-line-through fs-10">{{ number_format($product->original_price, 0, ',', '.') }}₫</span></td>
              <td class="py-2">
                @if($product->stock <= 0)
                  <span class="badge badge-phoenix badge-phoenix-danger"><i class="fa-solid fa-xmark me-1"></i> Hết hàng</span>
                @elseif($product->stock <= 5)
                  <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> Còn {{ $product->stock }}</span>
                @else
                  <span class="fw-semibold text-body-emphasis">{{ $product->stock }} cái</span>
                @endif
              </td>
              <td class="py-2"><span class="badge badge-phoenix badge-phoenix-success">{{ $product->sold_count }}</span></td>
              <td class="py-2">
                <span class="text-warning fs-10 fw-bold">
                  <i class="fa-solid fa-star"></i> {{ $product->rating }}
                </span>
                <small class="text-body-tertiary fs-10">({{ $product->reviews_count }})</small>
              </td>
              <!-- 1-CLICK TOGGLE BUTTON -->
              <td class="py-2">
                <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" class="d-inline">
                  @csrf
                  @if(!$product->is_active)
                    <button type="submit" class="btn btn-sm btn-phoenix-secondary py-1 px-2 fs-10" title="Bấm để mở bán lại">
                      <i class="fa-solid fa-eye-slash me-1"></i> Tạm dừng
                    </button>
                  @elseif($product->stock <= 0)
                    <button type="submit" class="btn btn-sm btn-phoenix-danger py-1 px-2 fs-10" title="Hết hàng (Bấm để ẩn)">
                      <i class="fa-solid fa-circle-exclamation me-1"></i> Hết hàng
                    </button>
                  @else
                    <button type="submit" class="btn btn-sm btn-phoenix-success py-1 px-2 fs-10" title="Bấm để tạm dừng kinh doanh">
                      <i class="fa-solid fa-circle-check me-1"></i> Kinh doanh
                    </button>
                  @endif
                </form>
              </td>
              <td class="text-end pe-3 py-2">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10" title="Chỉnh sửa sản phẩm">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                  </a>
                  <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi hệ thống?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-phoenix-danger py-1 px-2 fs-10" title="Xóa">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-shirt fs-4 text-body-tertiary mb-2 d-block"></i>
                Không tìm thấy sản phẩm nào phù hợp.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($products->hasPages())
    <div class="card-footer d-flex justify-content-center py-3 bg-body-emphasis border-top border-translucent">
      {{ $products->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL XEM THÔNG SỐ CHI TIẾT CHO TỪNG THẺ KPI -->
<div class="modal fade" id="kpiProductDetailModal" tabindex="-1" aria-labelledby="kpiProductDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      <!-- Modal Header -->
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" id="mdlKpiIconContainer" style="width: 38px; height: 38px;">
            <i class="fa-solid fa-shirt" id="mdlKpiIcon"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2">
              <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="mdlKpiTitle">Thông Số Chi Tiết Kho Hàng</h5>
              <span class="badge badge-phoenix fs-10" id="mdlKpiBadge">TỔNG QUAN</span>
            </div>
            <small class="text-body-tertiary" id="mdlKpiSubtitle">Theo dõi toàn bộ số liệu và các mặt hàng trong nhóm</small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4 bg-body-tertiary">
        <!-- 4 THẺ METRICS -->
        <div class="row g-3 mb-4" id="mdlMetricsContainer">
          <!-- Populated by JS -->
        </div>

        <!-- SEARCH BAR TRONG MODAL -->
        <div class="card border-0 shadow-sm p-3 mb-3 bg-body-emphasis">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="input-group input-group-sm" style="max-width: 380px;">
              <span class="input-group-text bg-body-tertiary border-translucent text-body-tertiary"><i class="fa-solid fa-magnifying-glass"></i></span>
              <input type="text" id="kpiSearchInput" class="form-control" placeholder="Tìm nhanh theo tên, mã SKU, danh mục...">
            </div>
            <div class="text-body-tertiary fs-10">
              Hiển thị: <strong class="text-body-emphasis" id="kpiVisibleCount">0</strong> sản phẩm
            </div>
          </div>
        </div>

        <!-- BẢNG DANH SÁCH SẢN PHẨM TRONG NHÓM -->
        <div class="card border-0 shadow-sm bg-body-emphasis">
          <div class="table-responsive scrollbar">
            <table class="table table-sm fs-9 mb-0 align-middle" id="kpiProductsTable">
              <thead class="bg-body-tertiary text-body-tertiary">
                <tr>
                  <th style="width: 50px;" class="ps-3 py-2 text-center">#</th>
                  <th class="py-2">Mã SKU</th>
                  <th class="py-2">Sản Phẩm</th>
                  <th class="py-2">Danh Mục</th>
                  <th class="py-2">Giá Bán</th>
                  <th class="py-2">Tồn Kho</th>
                  <th class="py-2">Đã Bán</th>
                  <th class="py-2">Đánh Giá</th>
                  <th class="py-2">Trạng Thái</th>
                  <th class="text-end pe-3 py-2" style="width: 130px;">Thao Tác</th>
                </tr>
              </thead>
              <tbody id="kpiProductsTbody" class="list">
                <!-- Populated by JS -->
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer border-top border-translucent bg-body-emphasis py-2.5 px-4 d-flex justify-content-between align-items-center">
        <a href="#" id="mdlFilterLink" class="btn btn-sm btn-phoenix-primary">
          <i class="fa-solid fa-filter me-1"></i> Lọc Ngoài Danh Sách Chính
        </a>
        <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">
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
    badgeEl.className = 'badge badge-phoenix fs-10 ' + (data.badge_class.includes('success') ? 'badge-phoenix-success' : (data.badge_class.includes('danger') ? 'badge-phoenix-danger' : (data.badge_class.includes('secondary') ? 'badge-phoenix-secondary' : 'badge-phoenix-primary')));

    const iconEl = document.getElementById('mdlKpiIcon');
    iconEl.className = data.icon;

    document.getElementById('mdlFilterLink').href = data.filter_url;

    const metricsContainer = document.getElementById('mdlMetricsContainer');
    metricsContainer.innerHTML = '';
    data.metrics.forEach(m => {
      metricsContainer.innerHTML += `
        <div class="col-md-3 col-6">
          <div class="p-3 bg-body-emphasis rounded border border-translucent text-center h-100 shadow-sm">
            <span class="text-body-tertiary fs-10 fw-semibold text-uppercase d-block mb-1">${m.label}</span>
            <h4 class="fw-bold mb-0 ${m.color}">${m.value}</h4>
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
          <td colspan="10" class="text-center py-5 text-body-tertiary">
            <i class="fa-solid fa-box-open fs-4 text-body-tertiary mb-2 d-block"></i>
            Không có sản phẩm nào trong nhóm này.
          </td>
        </tr>
      `;
    } else {
      products.forEach((p, idx) => {
        let stockBadge = '';
        if (p.stock <= 0) {
          stockBadge = '<span class="badge badge-phoenix badge-phoenix-danger"><i class="fa-solid fa-xmark me-1"></i> Hết hàng</span>';
        } else if (p.stock <= 5) {
          stockBadge = `<span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> Còn ${p.stock}</span>`;
        } else {
          stockBadge = `<strong class="text-body-emphasis">${p.stock} cái</strong>`;
        }

        let statusBadge = '';
        if (p.status === 'active' && p.stock > 0) {
          statusBadge = '<span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Kinh doanh</span>';
        } else if (p.status === 'active' && p.stock <= 0) {
          statusBadge = '<span class="badge badge-phoenix badge-phoenix-danger"><i class="fa-solid fa-circle-exclamation me-1"></i> Hết hàng</span>';
        } else {
          statusBadge = '<span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-eye-slash me-1"></i> Tạm dừng</span>';
        }

        tbody.innerHTML += `
          <tr class="kpi-prod-row border-bottom border-translucent" data-search="${(p.name + ' ' + p.sku + ' ' + p.category).toLowerCase()}">
            <td class="text-center text-body-tertiary fw-semibold fs-10 ps-3">${idx + 1}</td>
            <td><span class="font-monospace fw-bold text-primary">${p.sku}</span></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="${p.image}" alt="${p.name}" style="width: 36px; height: 36px; object-fit: contain;" class="rounded border border-translucent bg-body-emphasis">
                <div class="text-truncate" style="max-width: 200px;">
                  <strong class="text-body-emphasis d-block fs-9 text-truncate">${p.name}</strong>
                  ${p.brand ? `<small class="text-body-tertiary fs-10">${p.brand}</small>` : ''}
                </div>
              </div>
            </td>
            <td><span class="badge badge-phoenix badge-phoenix-secondary">${p.category}</span></td>
            <td><strong class="text-danger">${p.price_formatted}</strong></td>
            <td>${stockBadge}</td>
            <td><span class="badge badge-phoenix badge-phoenix-success">${p.sold_count}</span></td>
            <td><span class="text-warning fs-10 fw-bold">⭐ ${p.rating}</span></td>
            <td>${statusBadge}</td>
            <td class="text-end pe-3">
              <div class="d-flex align-items-center justify-content-end gap-1">
                <a href="${p.url}" target="_blank" class="btn btn-sm btn-phoenix-secondary py-1 px-2 fs-10" title="Xem ngoài web">
                  <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
                <a href="${p.edit_url}" target="_blank" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10" title="Chỉnh sửa sản phẩm">
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

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  }

  document.addEventListener('DOMContentLoaded', function() {
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