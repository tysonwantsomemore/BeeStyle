@extends('layouts.admin')

@section('title', 'Báo Cáo Tồn Kho & Tình Trạng Sản Phẩm | BeeStyle Admin')

@section('content')
<!-- BREADCRUMB & HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge bg-warning text-dark fs-10 fw-bold px-2 py-1 shadow-xs">
        <i class="fa-solid fa-boxes-stacked me-1"></i> BÁO CÁO KHO HÀNG &amp; SẢN PHẨM
      </span>
      <span class="badge bg-primary-subtle text-primary fs-10 fw-bold border border-primary-subtle">
        <i class="fa-solid fa-bolt me-1"></i> THỜI GIAN THỰC
      </span>
    </div>
    <h2 class="mb-0 text-dark fw-bolder fs-4" style="color: #0f172a !important;">Báo Cáo &amp; Phân Tích Tồn Kho Sản Phẩm</h2>
    <p class="text-muted mb-0 fw-medium" style="color: #475569 !important;">
      Theo dõi định giá tài sản lưu kho, tình trạng mở bán, phân bổ theo danh mục và cảnh báo các mẫu sắp hết hàng cần nhập gấp
    </p>
  </div>
  <div class="col-auto d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary text-dark fw-bold bg-white shadow-xs btn-sm px-3">
      <i class="fa-solid fa-shirt me-1.5 text-primary"></i> Quản Lý Sản Phẩm
    </a>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary fw-bold bg-white shadow-xs btn-sm px-3">
      <i class="fa-solid fa-chart-pie me-1.5"></i> Báo Cáo Doanh Thu
    </a>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-phoenix-success fw-bold shadow-xs btn-sm px-3">
      <i class="fa-solid fa-file-excel me-1.5"></i> Xuất File CSV
    </a>
    <button type="button" class="btn btn-outline-dark fw-bold bg-white shadow-xs btn-sm px-3" onclick="window.print()">
      <i class="fa-solid fa-print me-1.5"></i> In Báo Cáo
    </button>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-subtle-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <span class="fa-solid fa-circle-check text-success fs-8 me-2"></span>
    <span class="fs-9 fw-semibold">{{ session('success') }}</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- 4 THẺ HERO KPI TỔNG QUAN KHO HÀNG -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng Mẫu Trong Kho -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px; border-top: 4px solid #3b82f6 !important;">
      <div class="card-body p-3 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="text-secondary text-uppercase fw-bold fs-10 tracking-wider d-block mb-1" style="letter-spacing: 0.05em; color: #475569 !important;">
                TỔNG MẪU TRONG KHO
              </span>
              <h3 class="text-body-emphasis mb-0 fw-bold font-monospace">
                {{ $totalProductsCount }} <span class="fs-9 text-body-tertiary fw-normal">mẫu</span>
              </h3>
            </div>
            <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle shadow-xs" style="width: 44px; height: 44px;">
              <i class="fa-solid fa-shirt fs-8"></i>
            </div>
          </div>
          <div class="fs-10 text-body-secondary py-2 border-top border-translucent">
            <div class="d-flex justify-content-between mb-1">
              <span><i class="fa-solid fa-boxes-stacked me-1 text-primary"></i> Tồn kho:</span>
              <strong class="text-body-emphasis font-monospace">{{ number_format($totalStock, 0, ',', '.') }} cái</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị:</span>
              <strong class="text-danger font-monospace">{{ number_format($totalStockValue, 0, ',', '.') }}₫</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span><i class="fa-solid fa-truck-fast me-1 text-success"></i> Đã bán:</span>
              <strong class="text-success font-monospace">{{ number_format($totalSold, 0, ',', '.') }} cái</strong>
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent mt-2">
          <a href="{{ route('admin.reports.inventory', ['tab' => 'all']) }}" class="text-primary fs-10 fw-semibold text-decoration-none">
            <i class="fa-solid fa-arrow-pointer me-1"></i> Xem danh sách
          </a>
          <span class="badge badge-phoenix badge-phoenix-primary fs-10">Tất cả</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Đang Mở Bán -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px; border-top: 4px solid #10b981 !important;">
      <div class="card-body p-3 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="text-secondary text-uppercase fw-bold fs-10 tracking-wider d-block mb-1" style="letter-spacing: 0.05em; color: #475569 !important;">
                ĐANG MỞ BÁN
              </span>
              <h3 class="text-success mb-0 fw-bold font-monospace">
                {{ $activeProductsCount }} <span class="fs-9 text-body-tertiary fw-normal">mẫu</span>
              </h3>
            </div>
            <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle shadow-xs" style="width: 44px; height: 44px;">
              <i class="fa-solid fa-circle-check fs-8"></i>
            </div>
          </div>
          <div class="fs-10 text-body-secondary py-2 border-top border-translucent">
            <div class="d-flex justify-content-between mb-1">
              <span><i class="fa-solid fa-globe me-1 text-success"></i> Mở bán:</span>
              <strong class="text-body-emphasis font-monospace">{{ number_format($activeStock, 0, ',', '.') }} cái</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị:</span>
              <strong class="text-danger font-monospace">{{ number_format($activeStockValue, 0, ',', '.') }}₫</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span><i class="fa-solid fa-tags me-1 text-info"></i> Giá TB:</span>
              <strong class="text-primary font-monospace">{{ number_format(round($avgActivePrice), 0, ',', '.') }}₫</strong>
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent mt-2">
          <a href="{{ route('admin.reports.inventory', ['tab' => 'active']) }}" class="text-success fs-10 fw-semibold text-decoration-none">
            <i class="fa-solid fa-arrow-pointer me-1"></i> Xem danh sách
          </a>
          <span class="badge badge-phoenix badge-phoenix-success fs-10">Kinh doanh</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Đang Ẩn / Tạm Dừng -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px; border-top: 4px solid #64748b !important;">
      <div class="card-body p-3 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="text-secondary text-uppercase fw-bold fs-10 tracking-wider d-block mb-1" style="letter-spacing: 0.05em; color: #475569 !important;">
                ĐANG ẨN / TẠM DỪNG
              </span>
              <h3 class="text-secondary mb-0 fw-bold font-monospace">
                {{ $inactiveProductsCount }} <span class="fs-9 text-body-tertiary fw-normal">mẫu</span>
              </h3>
            </div>
            <div class="d-flex align-items-center justify-content-center bg-secondary-subtle text-secondary rounded-circle shadow-xs" style="width: 44px; height: 44px;">
              <i class="fa-solid fa-eye-slash fs-8"></i>
            </div>
          </div>
          <div class="fs-10 text-body-secondary py-2 border-top border-translucent">
            <div class="d-flex justify-content-between mb-1">
              <span><i class="fa-solid fa-lock me-1 text-secondary"></i> Lưu kho:</span>
              <strong class="text-body-emphasis font-monospace">{{ number_format($inactiveStock, 0, ',', '.') }} cái</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span><i class="fa-solid fa-sack-dollar me-1 text-warning"></i> Giá trị:</span>
              <strong class="text-danger font-monospace">{{ number_format($inactiveStockValue, 0, ',', '.') }}₫</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span><i class="fa-solid fa-percent me-1 text-muted"></i> Tỷ lệ đóng băng:</span>
              <strong class="text-secondary font-monospace">{{ $totalProductsCount > 0 ? round(($inactiveProductsCount / $totalProductsCount) * 100, 1) : 0 }}%</strong>
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent mt-2">
          <a href="{{ route('admin.reports.inventory', ['tab' => 'inactive']) }}" class="text-secondary fs-10 fw-semibold text-decoration-none">
            <i class="fa-solid fa-arrow-pointer me-1"></i> Xem danh sách
          </a>
          <span class="badge badge-phoenix badge-phoenix-secondary fs-10">Tạm dừng</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Cảnh Báo Tồn Kho (≤ 5) -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px; border-top: 4px solid #ef4444 !important;">
      <div class="card-body p-3 d-flex flex-column justify-content-between">
        <div>
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <span class="text-secondary text-uppercase fw-bold fs-10 tracking-wider d-block mb-1" style="letter-spacing: 0.05em; color: #475569 !important;">
                CẢNH BÁO TỒN KHO (≤ 5)
              </span>
              <h3 class="text-danger mb-0 fw-bold font-monospace">
                {{ $lowStockProductsCount }} <span class="fs-9 text-body-tertiary fw-normal">mẫu</span>
              </h3>
            </div>
            <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle shadow-xs" style="width: 44px; height: 44px;">
              <i class="fa-solid fa-triangle-exclamation fs-8"></i>
            </div>
          </div>
          <div class="fs-10 text-body-secondary py-2 border-top border-translucent">
            <div class="d-flex justify-content-between mb-1">
              <span><i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Hết kho (0):</span>
              <strong class="text-danger font-monospace">{{ $outOfStockCount }} mẫu</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span><i class="fa-solid fa-triangle-exclamation me-1 text-warning"></i> Còn ít (1-5):</span>
              <strong class="text-warning font-monospace">{{ $lowStockOnlyCount }} mẫu</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span><i class="fa-solid fa-cubes me-1 text-danger"></i> Tổng còn lại:</span>
              <strong class="text-body-emphasis font-monospace">{{ number_format($lowStockTotalPieces, 0, ',', '.') }} cái</strong>
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent mt-2">
          <a href="{{ route('admin.reports.inventory', ['tab' => 'low_stock']) }}" class="text-danger fs-10 fw-semibold text-decoration-none">
            <i class="fa-solid fa-arrow-pointer me-1"></i> Xem danh sách
          </a>
          <span class="badge badge-phoenix badge-phoenix-danger fs-10">Cần nhập</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ANALYTICS ROW: BIỂU ĐỒ TRẠNG THÁI & TOP GIÁ TRỊ TỒN -->
<div class="row g-3 mb-4">
  <!-- Cột 1: Biểu đồ cơ cấu trạng thái tồn kho -->
  <div class="col-12 col-xl-5">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px;">
      <div class="card-header bg-transparent border-bottom border-translucent py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark fs-9">
          <i class="fa-solid fa-chart-pie me-1 text-primary"></i> CƠ CẤU TRẠNG THÁI SẢN PHẨM &amp; TỒN KHO
        </h6>
        <span class="badge bg-light text-secondary border fs-11">Toàn bộ kho</span>
      </div>
      <div class="card-body p-3 d-flex flex-column justify-content-center">
        <div class="row align-items-center g-3">
          <div class="col-sm-6 text-center">
            <div style="max-width: 180px; margin: 0 auto; position: relative;">
              <canvas id="inventoryStatusChart" width="180" height="180"></canvas>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="d-flex flex-column gap-2 fs-10">
              <div class="p-2 rounded bg-light border border-translucent d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <span class="d-inline-block rounded-circle bg-success" style="width: 10px; height: 10px;"></span>
                  <span class="fw-semibold">Đang mở bán</span>
                </div>
                <strong class="text-dark font-monospace">{{ $activeProductsCount }} mẫu</strong>
              </div>
              <div class="p-2 rounded bg-light border border-translucent d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <span class="d-inline-block rounded-circle bg-secondary" style="width: 10px; height: 10px;"></span>
                  <span class="fw-semibold">Đang tạm dừng</span>
                </div>
                <strong class="text-dark font-monospace">{{ $inactiveProductsCount }} mẫu</strong>
              </div>
              <div class="p-2 rounded bg-light border border-translucent d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <span class="d-inline-block rounded-circle bg-warning" style="width: 10px; height: 10px;"></span>
                  <span class="fw-semibold">Sắp hết (1-5)</span>
                </div>
                <strong class="text-warning font-monospace">{{ $lowStockOnlyCount }} mẫu</strong>
              </div>
              <div class="p-2 rounded bg-light border border-translucent d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <span class="d-inline-block rounded-circle bg-danger" style="width: 10px; height: 10px;"></span>
                  <span class="fw-semibold">Hết hàng (0)</span>
                </div>
                <strong class="text-danger font-monospace">{{ $outOfStockCount }} mẫu</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Cột 2: Top 5 sản phẩm chiếm nhiều vốn tồn kho nhất -->
  <div class="col-12 col-xl-7">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px;">
      <div class="card-header bg-transparent border-bottom border-translucent py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark fs-9">
          <i class="fa-solid fa-sack-dollar me-1 text-warning"></i> TOP 5 SẢN PHẨM GIÁ TRỊ LƯU KHO CAO NHẤT (VỐN TỒN)
        </h6>
        <span class="badge bg-warning-subtle text-dark border border-warning-subtle fs-11">Giá trị = Giá bán × Tồn kho</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm fs-9 mb-0 align-middle">
            <thead class="bg-light text-body-tertiary">
              <tr>
                <th class="ps-3 py-2">Sản phẩm</th>
                <th class="py-2 text-end">Đơn giá</th>
                <th class="py-2 text-center">Tồn kho</th>
                <th class="py-2 text-end">Tổng giá trị tồn</th>
                <th class="py-2 text-center pe-3">Trạng thái</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topValuedProducts as $item)
                <tr class="border-bottom border-translucent">
                  <td class="ps-3 py-2">
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" style="width: 34px; height: 34px; object-fit: contain;" class="rounded border border-translucent bg-white">
                      <div class="text-truncate" style="max-width: 220px;">
                        <a href="{{ route('admin.products.edit', $item->id) }}" class="fw-bold text-dark text-decoration-none d-block text-truncate fs-9">
                          {{ $item->name }}
                        </a>
                        <small class="text-muted font-monospace fs-10">{{ $item->sku }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-2 text-end font-monospace">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                  <td class="py-2 text-center">
                    <span class="badge badge-phoenix {{ $item->stock <= 5 ? ($item->stock <= 0 ? 'badge-phoenix-danger' : 'badge-phoenix-warning') : 'badge-phoenix-primary' }} font-monospace">
                      {{ number_format($item->stock, 0, ',', '.') }} cái
                    </span>
                  </td>
                  <td class="py-2 text-end">
                    <strong class="text-danger font-monospace">{{ number_format($item->inventory_value, 0, ',', '.') }}₫</strong>
                  </td>
                  <td class="py-2 text-center pe-3">
                    @if($item->status === 'active')
                      <span class="badge badge-phoenix badge-phoenix-success fs-11">Mở bán</span>
                    @else
                      <span class="badge badge-phoenix badge-phoenix-secondary fs-11">Tạm dừng</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-3 text-muted">Chưa có dữ liệu sản phẩm.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BẢNG PHÂN BỔ THEO DANH MỤC & THƯƠNG HIỆU -->
<div class="row g-3 mb-4">
  <!-- Phân bổ theo Danh mục -->
  <div class="col-12 col-lg-7">
    <div class="card border-0 shadow-sm bg-white h-100" style="border-radius: 14px;">
      <div class="card-header bg-transparent border-bottom border-translucent py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark fs-9">
          <i class="fa-solid fa-grid-2 me-1 text-primary"></i> THỐNG KÊ TỒN KHO THEO DANH MỤC SẢN PHẨM
        </h6>
        <span class="badge bg-light text-secondary border fs-11">{{ $categoryStats->count() }} danh mục</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm fs-9 mb-0 align-middle">
            <thead class="bg-light text-body-tertiary">
              <tr>
                <th class="ps-3 py-2">Danh mục</th>
                <th class="py-2 text-center">Số mẫu</th>
                <th class="py-2 text-center">Tổng tồn kho</th>
                <th class="py-2 text-end">Tổng giá trị lưu kho</th>
                <th class="py-2 text-center pe-3">Cần nhập (≤5)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($categoryStats as $cat)
                <tr class="border-bottom border-translucent">
                  <td class="ps-3 py-2">
                    <a href="{{ route('admin.reports.inventory', ['category_id' => $cat->id]) }}" class="fw-bold text-dark text-decoration-none">
                      {{ $cat->name }}
                    </a>
                  </td>
                  <td class="py-2 text-center font-monospace">{{ $cat->products_count }} mẫu</td>
                  <td class="py-2 text-center">
                    <strong class="text-body-emphasis font-monospace">{{ number_format($cat->total_stock, 0, ',', '.') }} cái</strong>
                  </td>
                  <td class="py-2 text-end font-monospace text-danger fw-bold">
                    {{ number_format($cat->stock_value, 0, ',', '.') }}₫
                  </td>
                  <td class="py-2 text-center pe-3">
                    @if($cat->low_stock_count > 0)
                      <span class="badge badge-phoenix badge-phoenix-danger font-monospace">{{ $cat->low_stock_count }} mẫu</span>
                    @else
                      <span class="badge badge-phoenix badge-phoenix-success font-monospace">0</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Phân bổ theo Thương hiệu -->
  <div class="col-12 col-lg-5">
    <div class="card border-0 shadow-sm bg-white h-100" style="border-radius: 14px;">
      <div class="card-header bg-transparent border-bottom border-translucent py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark fs-9">
          <i class="fa-solid fa-award me-1 text-warning"></i> THỐNG KÊ TỒN KHO THEO THƯƠNG HIỆU
        </h6>
        <span class="badge bg-light text-secondary border fs-11">{{ $brandStats->count() }} thương hiệu</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm fs-9 mb-0 align-middle">
            <thead class="bg-light text-body-tertiary">
              <tr>
                <th class="ps-3 py-2">Thương hiệu</th>
                <th class="py-2 text-center">Số mẫu</th>
                <th class="py-2 text-center">Tổng tồn kho</th>
                <th class="py-2 text-end pe-3">Giá trị lưu kho</th>
              </tr>
            </thead>
            <tbody>
              @foreach($brandStats as $brand)
                <tr class="border-bottom border-translucent">
                  <td class="ps-3 py-2">
                    <a href="{{ route('admin.reports.inventory', ['brand_id' => $brand->id]) }}" class="fw-bold text-dark text-decoration-none">
                      {{ $brand->name }}
                    </a>
                  </td>
                  <td class="py-2 text-center font-monospace">{{ $brand->products_count }} mẫu</td>
                  <td class="py-2 text-center">
                    <strong class="text-body-emphasis font-monospace">{{ number_format($brand->total_stock, 0, ',', '.') }} cái</strong>
                  </td>
                  <td class="py-2 text-end pe-3 font-monospace text-danger fw-bold">
                    {{ number_format($brand->stock_value, 0, ',', '.') }}₫
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TABS & BẢNG CHI TIẾT TỒN KHO TỪNG SẢN PHẨM -->
<div class="card border-0 shadow-sm mb-4 bg-white" style="border-radius: 14px;">
  <!-- TAB HEADER -->
  <div class="card-header border-bottom border-translucent bg-light py-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div>
        <h5 class="mb-0 text-dark fw-bold fs-8">
          <i class="fa-solid fa-list-check me-1 text-primary"></i> DANH SÁCH CHI TIẾT TỒN KHO &amp; CẢNH BÁO NHẬP HÀNG
        </h5>
        <small class="text-muted">Theo dõi và cập nhật tồn kho từng mẫu sản phẩm theo thời gian thực</small>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-sm btn-outline-success fw-bold">
          <i class="fa-solid fa-file-excel me-1"></i> Xuất CSV Nhóm Này
        </a>
      </div>
    </div>

    <!-- 5 FILTER TABS -->
    <ul class="nav nav-pills gap-1 flex-wrap">
      <li class="nav-item">
        <a class="nav-link fs-10 fw-bold py-1.5 px-3 {{ $tab === 'all' ? 'active' : 'bg-white border text-dark' }}" 
           href="{{ route('admin.reports.inventory', array_merge(request()->query(), ['tab' => 'all', 'page' => 1])) }}">
          <i class="fa-solid fa-border-all me-1"></i> Tất Cả ({{ $totalProductsCount }})
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link fs-10 fw-bold py-1.5 px-3 {{ $tab === 'active' ? 'active bg-success' : 'bg-white border text-dark' }}" 
           href="{{ route('admin.reports.inventory', array_merge(request()->query(), ['tab' => 'active', 'page' => 1])) }}">
          <i class="fa-solid fa-circle-check me-1 text-success"></i> Đang Mở Bán ({{ $activeProductsCount }})
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link fs-10 fw-bold py-1.5 px-3 {{ $tab === 'inactive' ? 'active bg-secondary' : 'bg-white border text-dark' }}" 
           href="{{ route('admin.reports.inventory', array_merge(request()->query(), ['tab' => 'inactive', 'page' => 1])) }}">
          <i class="fa-solid fa-eye-slash me-1 text-secondary"></i> Tạm Dừng / Ẩn ({{ $inactiveProductsCount }})
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link fs-10 fw-bold py-1.5 px-3 {{ $tab === 'low_stock' ? 'active bg-warning text-dark' : 'bg-white border text-dark' }}" 
           href="{{ route('admin.reports.inventory', array_merge(request()->query(), ['tab' => 'low_stock', 'page' => 1])) }}">
          <i class="fa-solid fa-triangle-exclamation me-1 text-warning"></i> Cảnh Báo Tồn (≤ 5) ({{ $lowStockProductsCount }})
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link fs-10 fw-bold py-1.5 px-3 {{ $tab === 'out_of_stock' ? 'active bg-danger text-white' : 'bg-white border text-dark' }}" 
           href="{{ route('admin.reports.inventory', array_merge(request()->query(), ['tab' => 'out_of_stock', 'page' => 1])) }}">
          <i class="fa-solid fa-circle-xmark me-1 text-danger"></i> Đã Hết Sạch Kho ({{ $outOfStockCount }})
        </a>
      </li>
    </ul>
  </div>

  <!-- BỘ LỌC TÌM KIẾM & SẮP XẾP -->
  <div class="card-body border-bottom border-translucent bg-white p-3">
    <form method="GET" action="{{ route('admin.reports.inventory') }}" class="row g-2 align-items-center">
      <input type="hidden" name="tab" value="{{ $tab }}">

      <div class="col-12 col-md-3">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-light text-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm tên hoặc SKU...">
        </div>
      </div>

      <div class="col-6 col-md-2">
        <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">-- Tất cả danh mục --</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="brand_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">-- Tất cả thương hiệu --</option>
          @foreach($brands as $b)
            <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-6 col-md-3">
        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="default" {{ $sort === 'default' ? 'selected' : '' }}>Mặc định (Mới nhất)</option>
          <option value="stock_asc" {{ $sort === 'stock_asc' ? 'selected' : '' }}>Tồn kho: Ít nhất ➔ Nhiều nhất (Ưu tiên nhập)</option>
          <option value="stock_desc" {{ $sort === 'stock_desc' ? 'selected' : '' }}>Tồn kho: Nhiều nhất ➔ Ít nhất</option>
          <option value="value_desc" {{ $sort === 'value_desc' ? 'selected' : '' }}>Giá trị tồn kho: Cao nhất ➔ Thấp nhất</option>
          <option value="sold_desc" {{ $sort === 'sold_desc' ? 'selected' : '' }}>Số lượng bán: Bán chạy nhất</option>
          <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Tên sản phẩm: A ➔ Z</option>
        </select>
      </div>

      <div class="col-6 col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-sm btn-primary fw-bold flex-grow-1">
          <i class="fa-solid fa-filter me-1"></i> Lọc
        </button>
        @if(request('q') || request('category_id') || request('brand_id') || request('sort'))
          <a href="{{ route('admin.reports.inventory', ['tab' => $tab]) }}" class="btn btn-sm btn-outline-danger" title="Xóa bộ lọc">
            <i class="fa-solid fa-rotate-left"></i>
          </a>
        @endif
      </div>
    </form>
  </div>

  <!-- TABLE -->
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-sm fs-9 mb-0 align-middle">
        <thead class="bg-light text-body-tertiary">
          <tr>
            <th class="ps-3 py-2 text-center" style="width: 40px;">STT</th>
            <th class="py-2" style="width: 110px;">Mã SKU</th>
            <th class="py-2">Sản phẩm</th>
            <th class="py-2">Danh mục</th>
            <th class="py-2 text-end">Đơn giá</th>
            <th class="py-2 text-center">Tồn kho</th>
            <th class="py-2 text-center">Đã bán</th>
            <th class="py-2 text-end">Tổng giá trị tồn</th>
            <th class="py-2 text-center">Trạng thái</th>
            <th class="py-2 text-end pe-3" style="width: 140px;">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $idx => $prod)
            @php
              $totalValue = $prod->stock * $prod->price;
            @endphp
            <tr class="border-bottom border-translucent {{ $prod->stock <= 0 ? 'bg-danger-subtle bg-opacity-10' : ($prod->stock <= 5 ? 'bg-warning-subtle bg-opacity-10' : '') }}">
              <td class="text-center text-body-tertiary fw-semibold fs-10 ps-3">
                {{ $products->firstItem() + $idx }}
              </td>
              <td>
                <span class="font-monospace fw-bold text-primary">{{ $prod->sku }}</span>
              </td>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}" style="width: 40px; height: 40px; object-fit: contain;" class="rounded border border-translucent bg-white shadow-xs">
                  <div class="text-truncate" style="max-width: 250px;">
                    <a href="{{ route('admin.products.edit', $prod->id) }}" class="fw-bold text-dark text-decoration-none d-block text-truncate fs-9">
                      {{ $prod->name }}
                    </a>
                    <small class="text-body-tertiary fs-10">{{ $prod->brand->name ?? 'BeeStyle' }}</small>
                  </div>
                </div>
              </td>
              <td>
                <span class="badge badge-phoenix badge-phoenix-secondary">{{ $prod->category->name ?? 'N/A' }}</span>
              </td>
              <td class="text-end font-monospace fw-semibold">
                {{ number_format($prod->price, 0, ',', '.') }}₫
              </td>
              <td class="text-center">
                @if($prod->stock <= 0)
                  <span class="badge badge-phoenix badge-phoenix-danger px-2 py-1 font-monospace">
                    <i class="fa-solid fa-xmark me-1"></i> Hết hàng (0)
                  </span>
                @elseif($prod->stock <= 5)
                  <span class="badge badge-phoenix badge-phoenix-warning px-2 py-1 font-monospace">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Còn ít ({{ $prod->stock }})
                  </span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-success px-2 py-1 font-monospace">
                    <i class="fa-solid fa-box me-1"></i> {{ number_format($prod->stock, 0, ',', '.') }} cái
                  </span>
                @endif
              </td>
              <td class="text-center font-monospace">
                <span class="badge bg-light text-dark border font-monospace">{{ number_format($prod->sold_count, 0, ',', '.') }}</span>
              </td>
              <td class="text-end font-monospace fw-bold text-danger">
                {{ number_format($totalValue, 0, ',', '.') }}₫
              </td>
              <td class="text-center">
                @if($prod->status === 'active')
                  @if($prod->stock > 0)
                    <span class="badge badge-phoenix badge-phoenix-success">Mở bán</span>
                  @else
                    <span class="badge badge-phoenix badge-phoenix-danger">Cháy hàng</span>
                  @endif
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary">Tạm dừng</span>
                @endif
              </td>
              <td class="text-end pe-3">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <!-- Nút Cập Nhật Kho Nhanh -->
                  <button type="button" class="btn btn-sm btn-phoenix-warning px-2 py-1" 
                          onclick="openQuickStockModal({{ $prod->id }}, '{{ addslashes($prod->name) }}', '{{ $prod->sku }}', {{ $prod->stock }})"
                          title="Cập nhật nhanh tồn kho">
                    <i class="fa-solid fa-boxes-packing"></i>
                  </button>
                  <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn btn-sm btn-phoenix-primary px-2 py-1" title="Chỉnh sửa chi tiết">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </a>
                  <a href="{{ route('client.products.show', $prod->id) }}" target="_blank" class="btn btn-sm btn-phoenix-secondary px-2 py-1" title="Xem ngoài trang web">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center py-5 text-muted">
                <i class="fa-solid fa-box-open fs-3 text-body-tertiary mb-2 d-block"></i>
                Không tìm thấy sản phẩm nào phù hợp với bộ lọc.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- FOOTER & PAGINATION -->
  <div class="card-footer border-top border-translucent bg-light py-2.5 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="text-muted fs-10">
      Hiển thị từ <strong class="text-dark">{{ $products->firstItem() ?? 0 }}</strong> đến 
      <strong class="text-dark">{{ $products->lastItem() ?? 0 }}</strong> trong tổng số 
      <strong class="text-dark">{{ $products->total() }}</strong> sản phẩm
    </div>
    <div>
      {{ $products->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

<!-- MODAL CẬP NHẬT NHANH TỒN KHO -->
<div class="modal fade" id="quickStockModal" tabindex="-1" aria-labelledby="quickStockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form action="{{ route('admin.reports.inventory.quickStock') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" id="quickStockProductId">
        
        <div class="modal-header bg-light border-bottom border-translucent">
          <h6 class="modal-title fw-bold text-dark fs-9" id="quickStockModalLabel">
            <i class="fa-solid fa-boxes-packing me-1 text-warning"></i> CẬP NHẬT TỒN KHO NHANH
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fs-10 fw-bold text-uppercase text-muted">Sản phẩm</label>
            <div id="quickStockProductName" class="fw-bold text-dark fs-9 mb-1"></div>
            <div class="badge bg-light text-primary border font-monospace" id="quickStockProductSku"></div>
          </div>
          
          <div class="mb-3">
            <label class="form-label fs-10 fw-bold text-uppercase text-dark" for="quickStockInput">
              Số lượng tồn kho mới (cái) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <button class="btn btn-outline-secondary" type="button" onclick="adjustQuickStock(-5)">-5</button>
              <button class="btn btn-outline-secondary" type="button" onclick="adjustQuickStock(-1)">-1</button>
              <input type="number" name="stock" id="quickStockInput" class="form-control text-center font-monospace fw-bold fs-8" min="0" required>
              <button class="btn btn-outline-secondary" type="button" onclick="adjustQuickStock(1)">+1</button>
              <button class="btn btn-outline-secondary" type="button" onclick="adjustQuickStock(5)">+5</button>
              <button class="btn btn-outline-secondary" type="button" onclick="adjustQuickStock(20)">+20</button>
            </div>
            <small class="text-muted fs-11 mt-1 d-block">
              Số lượng tồn kho hiện tại: <strong id="quickStockCurrentDisplay" class="text-dark"></strong> cái.
            </small>
          </div>
        </div>
        
        <div class="modal-footer bg-light border-top border-translucent py-2 px-4">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
          <button type="submit" class="btn btn-primary btn-sm fw-bold">
            <i class="fa-solid fa-check me-1"></i> Lưu Cập Nhật
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Khởi tạo Biểu đồ cơ cấu trạng thái tồn kho (Doughnut Chart)
  document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('inventoryStatusChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Đang mở bán', 'Đang tạm dừng', 'Sắp hết (1-5)', 'Hết hàng (0)'],
          datasets: [{
            data: [
              {{ $activeProductsCount }},
              {{ $inactiveProductsCount }},
              {{ $lowStockOnlyCount }},
              {{ $outOfStockCount }}
            ],
            backgroundColor: [
              '#10b981', // green
              '#64748b', // slate
              '#f59e0b', // amber
              '#ef4444'  // red
            ],
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return context.label + ': ' + context.raw + ' mẫu';
                }
              }
            }
          },
          cutout: '70%'
        }
      });
    }
  });

  // Xử lý Modal Cập nhật nhanh tồn kho
  function openQuickStockModal(id, name, sku, currentStock) {
    document.getElementById('quickStockProductId').value = id;
    document.getElementById('quickStockProductName').textContent = name;
    document.getElementById('quickStockProductSku').textContent = 'Mã SKU: ' + sku;
    document.getElementById('quickStockInput').value = currentStock;
    document.getElementById('quickStockCurrentDisplay').textContent = currentStock;

    const modal = new bootstrap.Modal(document.getElementById('quickStockModal'));
    modal.show();
  }

  function adjustQuickStock(delta) {
    const input = document.getElementById('quickStockInput');
    let val = parseInt(input.value) || 0;
    val = Math.max(0, val + delta);
    input.value = val;
  }
</script>
@endpush
@endsection
