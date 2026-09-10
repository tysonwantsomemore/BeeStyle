@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu Tháng ' . $parsedDate->format('m/Y') . ' | BeeStyle Admin')

@push('styles')
<style>
  /* Monthly Revenue Dashboard Luxury Styling */
  .bee-kpi-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
  }
  .bee-kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
  }
  .bee-kpi-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
  }
  .bee-kpi-card.gold::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
  .bee-kpi-card.emerald::before { background: linear-gradient(180deg, #10b981, #059669); }
  .bee-kpi-card.blue::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
  .bee-kpi-card.indigo::before { background: linear-gradient(180deg, #8b5cf6, #7c3aed); }

  .bee-kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
  }
  .bee-kpi-icon.gold { background: #fef3c7; color: #b45309; }
  .bee-kpi-icon.emerald { background: #d1fae5; color: #047857; }
  .bee-kpi-icon.blue { background: #dbeafe; color: #1d4ed8; }
  .bee-kpi-icon.indigo { background: #ede9fe; color: #6d28d9; }

  .bee-chart-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
  }

  .bee-vip-rank-1 { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
  .bee-vip-rank-2 { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
  .bee-vip-rank-3 { background: #fed7aa; color: #c2410c; border: 1px solid #fdba74; }
  .bee-vip-rank-default { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

  @media print {
    .navbar-vertical, .navbar-top, .footer, .no-print {
      display: none !important;
    }
    .main {
      padding: 0 !important;
      margin: 0 !important;
    }
    .bee-chart-card, .bee-kpi-card {
      box-shadow: none !important;
      border: 1px solid #ccc !important;
    }
  }
</style>
@endpush

@section('content')
<!-- HEADER BÁO CÁO DOANH THU THÁNG -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-auto">
    <div class="d-flex align-items-center gap-2 mb-1">
      <a href="{{ route('admin.dashboard') }}" class="btn btn-phoenix-secondary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Quay lại Dashboard">
        <span class="fa-solid fa-arrow-left fs-10"></span>
      </a>
      <span class="badge badge-phoenix badge-phoenix-warning text-uppercase fw-bold fs-10">Báo Cáo Tài Chính</span>
    </div>
    <h2 class="mb-0 text-body-emphasis fw-bold">
      Báo Cáo Doanh Thu &amp; Khách Mua Hàng {{ 'Tháng ' . $parsedDate->format('m/Y') }}
    </h2>
    <p class="text-body-tertiary mb-0">Theo dõi toàn diện các chỉ số kinh doanh, danh sách đơn hàng và phân loại khách mua trong tháng</p>
  </div>

  <!-- Bộ Chọn Tháng & Nút In Báo Cáo -->
  <div class="col-auto d-flex align-items-center gap-2 flex-wrap">
    <form action="{{ route('admin.revenue.monthly') }}" method="GET" class="d-flex align-items-center gap-2">
      <div class="input-group input-group-sm">
        <span class="input-group-text bg-body-emphasis border-end-0 text-primary">
          <span class="fa-solid fa-calendar-days"></span>
        </span>
        <select name="month" class="form-select form-select-sm fw-bold border-start-0" onchange="this.form.submit()" style="min-width: 170px;">
          @foreach($availableMonths as $val => $label)
            <option value="{{ $val }}" {{ $selectedMonth === $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
    </form>

    <button onclick="window.print()" class="btn btn-phoenix-secondary btn-sm px-3">
      <span class="fa-solid fa-print me-1.5"></span>In Báo Cáo
    </button>
  </div>
</div>

<!-- 4 THẺ CHỈ SỐ DOANH THU THÁNG -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng Doanh Thu Tháng -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="badge-phoenix-icon me-2 badge-phoenix-primary">
              <span class="fa-solid fa-sack-dollar fs-9"></span>
            </div>
            <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Tổng Doanh Thu Tháng</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-body-emphasis fw-bolder">{{ number_format($monthlyRevenue, 0, ',', '.') }}₫</h3>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent d-flex align-items-center justify-content-between fs-10">
          <span class="text-success fw-semibold">
            <span class="fa-solid fa-arrow-trend-up me-1"></span>{{ $growthRate }} so tháng trước
          </span>
          <span class="badge badge-phoenix badge-phoenix-warning fs-11">{{ $parsedDate->format('m/Y') }}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Đơn Hàng Trong Tháng -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="badge-phoenix-icon me-2 badge-phoenix-success">
              <span class="fa-solid fa-receipt fs-9"></span>
            </div>
            <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Tổng Đơn Hàng</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-success fw-bolder">{{ $monthlyOrdersCount }}</h3>
          <span class="fs-10 text-body-tertiary">đơn phát sinh</span>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent d-flex align-items-center justify-content-between fs-10">
          <span class="text-success fw-semibold">
            <span class="fa-solid fa-check me-1"></span>{{ $completedOrdersCount }} hoàn tất
          </span>
          @if($cancelledOrdersCount > 0)
            <span class="text-danger fw-semibold">
              <span class="fa-solid fa-xmark me-1"></span>{{ $cancelledOrdersCount }} đã hủy
            </span>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Khách Hàng Mua Trong Tháng -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100 position-relative" 
         style="cursor: pointer;"
         data-bs-toggle="modal" 
         data-bs-target="#monthlyCustomersModal"
         title="Bấm để xem danh sách tất cả {{ $totalCustomersInMonth }} khách hàng mua trong tháng {{ $parsedDate->format('m/Y') }}">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="badge-phoenix-icon me-2 badge-phoenix-info">
              <span class="fa-solid fa-users fs-9"></span>
            </div>
            <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Khách Mua Trong Tháng</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-info fw-bolder">{{ $totalCustomersInMonth }}</h3>
          <span class="fs-10 text-body-tertiary">người mua</span>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent d-flex align-items-center justify-content-between fs-10">
          <span class="text-primary fw-semibold">
            <span class="fa-solid fa-arrow-pointer me-1"></span>Xem toàn bộ {{ $totalCustomersInMonth }} khách
          </span>
          <span class="badge badge-phoenix badge-phoenix-primary fs-11">
            <span class="fa-regular fa-eye me-1"></span>Chi tiết
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Giá Trị Đơn Trung Bình (AOV) -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="badge-phoenix-icon me-2 badge-phoenix-warning">
              <span class="fa-solid fa-tag fs-9"></span>
            </div>
            <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Giá Trị TB / Đơn (AOV)</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-warning fw-bolder">{{ number_format($aovMonth, 0, ',', '.') }}₫</h3>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent d-flex align-items-center justify-content-between fs-10">
          <span class="text-body-tertiary">Doanh thu / Đơn hợp lệ</span>
          <span class="badge badge-phoenix badge-phoenix-success fs-11">Chuẩn VIP</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BẢNG DANH SÁCH TẤT CẢ KHÁCH HÀNG & ĐƠN HÀNG MUA TRONG THÁNG -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <span class="fa-solid fa-list-check text-primary fs-8"></span>
      <h5 class="mb-0 text-body-emphasis">Danh Sách Đơn Hàng Tháng {{ $parsedDate->format('m/Y') }}</h5>
      <span class="badge badge-phoenix badge-phoenix-secondary ms-2">{{ $orders->total() }} đơn</span>
    </div>

    <!-- Ô tìm kiếm đơn hàng trong tháng -->
    <form action="{{ route('admin.revenue.monthly') }}" method="GET" class="d-flex align-items-center gap-2">
      <input type="hidden" name="month" value="{{ $selectedMonth }}">
      <div class="input-group input-group-sm" style="width: 280px;">
        <input type="text" name="q" class="form-control" placeholder="Tìm tên khách, SĐT, mã đơn..." value="{{ $search }}">
        <button class="btn btn-primary" type="submit">
          <span class="fa-solid fa-magnifying-glass"></span>
        </button>
      </div>

      <!-- Search Form -->
      <form action="{{ route('admin.revenue.monthly') }}" method="GET" class="d-flex align-items-center gap-2">
        <input type="hidden" name="month" value="{{ $selectedMonth }}">
        @if(!empty($status))
          <input type="hidden" name="status" value="{{ $status }}">
        @endif
        <div class="input-group input-group-sm" style="width: 250px;">
          <input type="text" name="q" class="form-control" placeholder="Tìm tên khách, SĐT, mã..." value="{{ $search }}">
          <button class="btn btn-bee-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
      </form>
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle table-hover">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-4 py-3">MÃ ĐƠN</th>
            <th class="py-3" style="min-width: 200px;">KHÁCH HÀNG</th>
            <th class="py-3" style="min-width: 240px;">SẢN PHẨM ĐÃ MUA</th>
            <th class="py-3">TỔNG TIỀN</th>
            <th class="py-3">THỜI GIAN MUA</th>
            <th class="py-3">THANH TOÁN</th>
            <th class="py-3">GIAO HÀNG</th>
            <th class="pe-4 py-3 text-end">THAO TÁC</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($orders as $order)
            <tr>
              <!-- Mã Đơn -->
              <td class="ps-4 py-3">
                <span class="font-monospace fw-bold text-primary">{{ $order->order_code }}</span>
              </td>

              <!-- Khách Hàng & Avatar -->
              <td class="py-3">
                <div class="d-flex align-items-center gap-2.5">
                  <img src="{{ asset($order->user->avatar_url ?? '/assets/img/team/40x40/58.webp') }}" 
                       alt="{{ $order->customer_name }}" 
                       class="avatar avatar-m rounded-circle border border-translucent object-fit-cover">
                  <div>
                    <strong class="text-body-emphasis d-block fs-9">{{ $order->customer_name }}</strong>
                    <div class="fs-10 text-body-tertiary">
                      <span class="fa-solid fa-phone me-1 text-body-tertiary"></span>{{ $order->customer_phone }}
                    </div>
                    @if($order->customer_email)
                      <div class="fs-11 text-body-tertiary text-truncate" style="max-width: 160px;">{{ $order->customer_email }}</div>
                    @endif
                  </div>
                </div>
              </td>

              <!-- Sản Phẩm Đã Mua -->
              <td class="py-3">
                <div class="d-flex flex-column gap-1.5" style="max-width: 260px;">
                  @foreach($order->items->take(2) as $item)
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ asset($item->product->image ?? 'assets/img/products/1.png') }}" 
                           alt="{{ $item->product_name }}" 
                           style="width: 30px; height: 30px; object-fit: cover;" 
                           class="rounded border border-translucent bg-body flex-shrink-0">
                      <div class="text-truncate">
                        <span class="fs-9 fw-semibold text-body-emphasis text-truncate d-block">{{ $item->product_name }}</span>
                        <div class="fs-11 text-body-tertiary">
                          {{ $item->size ? 'Size: ' . $item->size : '' }} {{ $item->color ? '| Màu: ' . $item->color : '' }} x{{ $item->quantity }}
                        </div>
                      </div>
                    </div>
                  @endforeach
                  @if($order->items->count() > 2)
                    <small class="text-body-tertiary fst-italic fs-11">+{{ $order->items->count() - 2 }} sản phẩm khác</small>
                  @endif
                </div>
              </td>

              <!-- Tổng Tiền -->
              <td class="py-3">
                <strong class="text-danger fs-9">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                @if($order->discount_amount > 0)
                  <div class="text-success fs-11">Giảm: -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</div>
                @endif
              </td>

              <!-- Thời Gian Mua -->
              <td class="py-3">
                <span class="fs-9 text-body-emphasis fw-semibold d-block">{{ $order->created_at ? $order->created_at->format('d/m/Y') : '' }}</span>
                <span class="fs-10 text-body-tertiary">{{ $order->created_at ? $order->created_at->format('H:i') : '' }}</span>
              </td>

              <!-- Trạng Thái Thanh Toán -->
              <td class="py-3">
                @if($order->payment_status === 'paid')
                  <span class="badge badge-phoenix badge-phoenix-success py-1 px-2">
                    <span class="fa-solid fa-circle-check me-1"></span>Đã thanh toán
                  </span>
                @elseif($order->payment_status === 'refunded')
                  <span class="badge badge-phoenix badge-phoenix-danger py-1 px-2">
                    <span class="fa-solid fa-rotate-left me-1"></span>Đã hoàn tiền
                  </span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-warning py-1 px-2">
                    <span class="fa-solid fa-clock me-1"></span>Chưa thanh toán
                  </span>
                @endif
                <div class="fs-11 text-body-tertiary mt-0.5">{{ $order->payment_method_name }}</div>
              </td>

              <!-- Trạng Thái Vận Chuyển -->
              <td class="py-3">
                @if($order->shipping_status === 'completed')
                  <span class="badge badge-phoenix badge-phoenix-success"><span class="fa-solid fa-circle-check me-1"></span>Hoàn tất</span>
                @elseif($order->shipping_status === 'delivered')
                  <span class="badge badge-phoenix badge-phoenix-success"><span class="fa-solid fa-box-open me-1"></span>Đã giao</span>
                @elseif($order->shipping_status === 'shipping')
                  <span class="badge badge-phoenix badge-phoenix-warning"><span class="fa-solid fa-truck-fast me-1"></span>Đang giao</span>
                @elseif($order->shipping_status === 'cancelled')
                  <span class="badge badge-phoenix badge-phoenix-danger"><span class="fa-solid fa-xmark me-1"></span>Đã hủy</span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-info"><span class="fa-solid fa-box me-1"></span>{{ $order->status_label }}</span>
                @endif
              </td>

              <!-- Thao Tác -->
              <td class="pe-4 py-3 text-end">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-phoenix-secondary btn-sm px-2.5 py-1">
                  <span class="fa-regular fa-eye me-1"></span>Chi Tiết
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="py-3">
                  <span class="fa-solid fa-receipt text-body-tertiary fs-2 mb-2 d-block"></span>
                  <h6 class="text-body-emphasis fw-bold mb-1">Không có đơn hàng nào trong tháng {{ $parsedDate->format('m/Y') }}</h6>
                  <p class="text-body-tertiary fs-9 mb-0">Các đơn mua phát sinh trong tháng này sẽ tự động được ghi nhận tại đây.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($orders->hasPages())
    <div class="card-footer border-top border-translucent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="fs-9 text-body-tertiary">
        Hiển thị từ <span class="fw-semibold text-body-emphasis">{{ $orders->firstItem() }}</span> đến <span class="fw-semibold text-body-emphasis">{{ $orders->lastItem() }}</span> trong tổng số <span class="fw-semibold text-body-emphasis">{{ $orders->total() }}</span> đơn hàng
      </div>
      <div>
        {{ $orders->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>

<!-- MODAL XEM TOÀN BỘ DANH SÁCH KHÁCH HÀNG MUA TRONG THÁNG -->
<div class="modal fade" id="monthlyCustomersModal" tabindex="-1" aria-labelledby="monthlyCustomersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg">
      <!-- Modal Header -->
      <div class="modal-header border-bottom border-translucent bg-body-emphasis py-3 px-4">
        <div class="d-flex align-items-center gap-3">
          <div class="badge-phoenix-icon badge-phoenix-primary p-2 rounded-circle">
            <span class="fa-solid fa-users-viewfinder fs-9"></span>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="monthlyCustomersModalLabel">
              Toàn Bộ Khách Hàng Mua Sắm {{ 'Tháng ' . $parsedDate->format('m/Y') }}
            </h5>
            <small class="text-body-tertiary">
              Tổng cộng {{ $totalCustomersInMonth }} khách hàng • {{ $monthlyOrdersCount }} đơn hàng • Tổng chi tiêu: {{ number_format($monthlyRevenue, 0, ',', '.') }}₫
            </small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body bg-body p-4">
        <!-- 4 THẺ TÓM TẮT TRONG MODAL -->
        <div class="row g-3 mb-4">
          <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm text-center">
              <div class="card-body p-3">
                <span class="text-body-tertiary fs-10 fw-bold text-uppercase d-block mb-1">Tổng Khách Mua Hàng</span>
                <h4 class="fw-bolder text-primary mb-0">{{ $totalCustomersInMonth }} <small class="fs-9 text-body-tertiary fw-normal">Khách</small></h4>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm text-center">
              <div class="card-body p-3">
                <span class="text-body-tertiary fs-10 fw-bold text-uppercase d-block mb-1">Tổng Đơn Đã Đặt</span>
                <h4 class="fw-bolder text-body-emphasis mb-0">{{ $monthlyOrdersCount }} <small class="fs-9 text-body-tertiary fw-normal">Đơn</small></h4>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm text-center">
              <div class="card-body p-3">
                <span class="text-body-tertiary fs-10 fw-bold text-uppercase d-block mb-1">Doanh Số Khách Mua</span>
                <h4 class="fw-bolder text-danger mb-0">{{ number_format($monthlyRevenue, 0, ',', '.') }}₫</h4>
              </div>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm text-center">
              <div class="card-body p-3">
                <span class="text-body-tertiary fs-10 fw-bold text-uppercase d-block mb-1">Chi Tiêu TB / Khách</span>
                <h4 class="fw-bolder text-success mb-0">{{ $totalCustomersInMonth > 0 ? number_format(round($monthlyRevenue / $totalCustomersInMonth), 0, ',', '.') : 0 }}₫</h4>
              </div>
            </div>
          </div>
        </div>

        <!-- Ô TÌM KIẾM NHANH KHÁCH HÀNG TRONG MODAL -->
        <div class="card border-0 shadow-sm p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="input-group input-group-sm" style="max-width: 380px;">
              <span class="input-group-text bg-body-emphasis border text-body-tertiary"><span class="fa-solid fa-magnifying-glass"></span></span>
              <input type="text" id="filterMonthlyCustomersInput" class="form-control" placeholder="Tìm nhanh tên khách, SĐT, email...">
            </div>
            <div class="text-body-tertiary fs-10">
              Hiển thị: <strong id="visibleCustomerCount" class="text-body-emphasis">{{ count($monthlyCustomersList) }}</strong> / {{ $totalCustomersInMonth }} khách hàng
            </div>
          </div>
        </div>

        <!-- BẢNG CHI TIẾT TỪNG KHÁCH HÀNG -->
        <div class="card border-0 shadow-sm overflow-hidden">
          <div class="table-responsive scrollbar">
            <table class="table table-sm fs-9 mb-0 align-middle table-hover" id="monthlyCustomersTable">
              <thead class="bg-body-tertiary text-body-tertiary">
                <tr>
                  <th style="width: 50px;" class="text-center">#</th>
                  <th>KHÁCH HÀNG</th>
                  <th>LIÊN HỆ</th>
                  <th>HẠNG THÀNH VIÊN</th>
                  <th class="text-center">ĐƠN MUA THÁNG NÀY</th>
                  <th class="text-end">CHI TIÊU THÁNG NÀY</th>
                  <th style="min-width: 200px;">DANH SÁCH ĐƠN</th>
                  <th class="text-end pe-3" style="width: 110px;">THAO TÁC</th>
                </tr>
              </thead>
              <tbody class="list">
                @forelse($monthlyCustomersList as $index => $c)
                  <tr class="customer-row" data-search="{{ mb_strtolower($c['name'] . ' ' . $c['phone'] . ' ' . $c['email']) }}">
                    <!-- Xếp hạng chi tiêu trong tháng -->
                    <td class="text-center">
                      @if($index == 0)
                        <span class="badge badge-phoenix badge-phoenix-warning fw-bold rounded-circle p-2" title="Top 1 chi tiêu tháng" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                          <span class="fa-solid fa-trophy"></span>
                        </span>
                      @elseif($index == 1)
                        <span class="badge badge-phoenix badge-phoenix-secondary fw-bold rounded-circle p-2" title="Top 2 chi tiêu tháng" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                          2
                        </span>
                      @elseif($index == 2)
                        <span class="badge badge-phoenix badge-phoenix-danger fw-bold rounded-circle p-2" title="Top 3 chi tiêu tháng" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                          3
                        </span>
                      @else
                        <span class="text-body-tertiary fw-bold fs-10">{{ $index + 1 }}</span>
                      @endif
                    </td>

                    <!-- Tên & Avatar -->
                    <td>
                      <div class="d-flex align-items-center gap-2.5">
                        <img src="{{ $c['avatar'] }}" alt="{{ $c['name'] }}" class="avatar avatar-m rounded-circle border border-translucent object-fit-cover">
                        <div>
                          <strong class="text-body-emphasis d-block fs-9 mb-0.5">{{ $c['name'] }}</strong>
                          @if($c['is_registered'])
                            <span class="badge badge-phoenix badge-phoenix-success py-0.5 px-1.5 fs-11">
                              <span class="fa-solid fa-shield-check me-0.5"></span>Thành viên
                            </span>
                          @else
                            <span class="badge badge-phoenix badge-phoenix-secondary py-0.5 px-1.5 fs-11">
                              Khách vãng lai
                            </span>
                          @endif
                        </div>
                      </div>
                    </td>

                    <!-- Liên hệ -->
                    <td>
                      <div class="fs-9 fw-semibold text-body-emphasis">{{ $c['phone'] }}</div>
                      <small class="text-body-tertiary text-truncate d-block fs-11" style="max-width: 180px;">{{ $c['email'] }}</small>
                    </td>

                    <!-- Hạng thành viên -->
                    <td>
                      <span class="badge badge-phoenix badge-phoenix-warning fs-10">
                        <span class="fa-solid fa-crown me-1"></span>{{ $c['rank'] }}
                      </span>
                    </td>

                    <!-- Số đơn trong tháng -->
                    <td class="text-center">
                      <span class="badge badge-phoenix badge-phoenix-primary fs-10">
                        {{ $c['orders_count'] }} đơn
                      </span>
                      @if($c['completed_count'] > 0)
                        <div class="text-success mt-0.5 fs-11 fw-semibold">{{ $c['completed_count'] }} hoàn tất</div>
                      @endif
                    </td>

                    <!-- Tổng chi tiêu trong tháng -->
                    <td class="text-end">
                      <strong class="text-danger fs-9">{{ $c['total_spent_in_month_formatted'] }}</strong>
                    </td>

                    <!-- Danh sách các đơn đã mua -->
                    <td>
                      <div class="d-flex flex-wrap gap-1" style="max-width: 260px;">
                        @foreach($c['orders'] as $ord)
                          <a href="{{ route('admin.orders.show', $ord['id']) }}" target="_blank" 
                             class="badge badge-phoenix badge-phoenix-secondary text-decoration-none py-1 px-1.5 fs-11"
                             title="Đơn #{{ $ord['order_code'] }} - {{ $ord['total_amount_formatted'] }} ({{ $ord['shipping_status_label'] }})">
                            #{{ $ord['order_code'] }}
                          </a>
                        @endforeach
                      </div>
                    </td>

                    <!-- Thao tác -->
                    <td class="text-end pe-3">
                      @if($c['user_id'])
                        <a href="{{ route('admin.customers.show', $c['user_id']) }}" target="_blank" class="btn btn-phoenix-secondary btn-sm px-2 py-1 fs-10">
                          <span class="fa-regular fa-id-card me-1"></span>Hồ Sơ
                        </a>
                      @else
                        <a href="{{ route('admin.revenue.monthly', ['month' => $selectedMonth, 'q' => $c['phone']]) }}" class="btn btn-phoenix-secondary btn-sm px-2 py-1 fs-10">
                          <span class="fa-solid fa-filter me-1"></span>Lọc đơn
                        </a>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-5 text-body-tertiary">
                      <span class="fa-solid fa-users-slash fs-2 mb-2 d-block"></span>
                      Không có dữ liệu khách mua hàng trong tháng {{ $parsedDate->format('m/Y') }}.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer border-top border-translucent bg-body-emphasis py-2.5 px-4 d-flex justify-content-between align-items-center">
        <span class="fs-10 text-body-tertiary">
          <span class="fa-solid fa-circle-info text-primary me-1"></span>Dữ liệu được tính tự động dựa trên toàn bộ đơn hàng trong tháng {{ $parsedDate->format('m/Y') }}
        </span>
        <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">
          Đóng
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // 1. BIỂU ĐỒ DOANH THU THEO NGÀY (DAILY REVENUE LINE CHART)
    const dailyCtx = document.getElementById('dailyRevenueChart');
    if (dailyCtx) {
      const dailyLabels = @json($dailyLabels);
      const dailyRevenue = @json($dailyRevenueData);
      const dailyOrders = @json($dailyOrdersData);

      new Chart(dailyCtx, {
        type: 'line',
        data: {
          labels: dailyLabels,
          datasets: [
            {
              label: 'Doanh Thu (VNĐ)',
              data: dailyRevenue,
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.12)',
              borderWidth: 2.5,
              fill: true,
              tension: 0.35,
              pointBackgroundColor: '#f59e0b',
              pointBorderColor: '#ffffff',
              pointBorderWidth: 2,
              pointRadius: 3.5,
              pointHoverRadius: 6,
              yAxisID: 'y'
            },
            {
              label: 'Số Lượng Đơn',
              data: dailyOrders,
              borderColor: '#3b82f6',
              backgroundColor: 'transparent',
              borderWidth: 1.8,
              borderDash: [4, 4],
              tension: 0.3,
              pointRadius: 2.5,
              pointHoverRadius: 5,
              yAxisID: 'y1'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false,
          },
          plugins: {
            legend: {
              position: 'top',
              labels: {
                boxWidth: 12,
                font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '600' }
              }
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  let label = context.dataset.label || '';
                  if (label) label += ': ';
                  if (context.datasetIndex === 0) {
                    label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                  } else {
                    label += context.parsed.y + ' đơn';
                  }
                  return label;
                }
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 } }
            },
            y: {
              type: 'linear',
              display: true,
              position: 'left',
              grid: { color: 'rgba(226, 232, 240, 0.6)' },
              ticks: {
                font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 },
                callback: function(value) {
                  if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                  if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                  return value;
                }
              }
            },
            y1: {
              type: 'linear',
              display: true,
              position: 'right',
              grid: { drawOnChartArea: false },
              ticks: {
                font: { family: "'Plus Jakarta Sans', sans-serif", size: 10 },
                stepSize: 1,
                precision: 0
              }
            }
          }
        }
      });
    }

    // 2. BIỂU ĐỒ CƠ CẤU THANH TOÁN (PAYMENT METHOD DOUGHNUT CHART)
    const paymentCtx = document.getElementById('paymentMethodChart');
    if (paymentCtx) {
      const paymentLabels = @json($paymentLabels);
      const paymentData = @json($paymentData);

      const colorPalette = ['#f59e0b', '#ec4899', '#3b82f6', '#10b981', '#8b5cf6', '#64748b'];

      new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
          labels: paymentLabels.length > 0 ? paymentLabels : ['Chưa có giao dịch'],
          datasets: [{
            data: paymentData.length > 0 ? paymentData : [1],
            backgroundColor: paymentData.length > 0 ? colorPalette.slice(0, paymentLabels.length) : ['#e2e8f0'],
            borderWidth: 2,
            borderColor: '#ffffff',
            hoverOffset: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                boxWidth: 10,
                font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '500' }
              }
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const val = context.parsed;
                  return ' ' + context.label + ': ' + new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
                }
              }
            }
          },
          cutout: '68%'
        }
      });
    }
  });

  // 3. EXPORT TABLE TO CSV UTILITY
  function exportTableToCSV(filename) {
    const table = document.getElementById('monthlyOrdersTable');
    if (!table) return;

    let csv = [];
    const rows = table.querySelectorAll('tr');

    for (let i = 0; i < rows.length; i++) {
      let row = [], cols = rows[i].querySelectorAll('td, th');
      for (let j = 0; j < cols.length - 1; j++) { // bỏ cột thao tác
        let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/\s+/g, ' ').trim();
        text = text.replace(/"/g, '""');
        row.push('"' + text + '"');
      }
      if (row.length > 0) {
        csv.push(row.join(','));
      }
    }

    const csvFile = new Blob(['\uFEFF' + csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const downloadLink = document.createElement('a');
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
  }
</script>
@endpush
@endsection
