@extends('layouts.admin')

@section('title', 'Báo Cáo Doanh Thu Tháng ' . $parsedDate->format('m/Y') . ' | BeeStyle Admin')

@section('content')
<!-- HEADER BÁO CÁO DOANH THU THÁNG -->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Quay lại Dashboard">
          <i class="fa-solid fa-arrow-left fs-12"></i>
        </a>
        <h3 class="fw-bold text-dark mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">
          Báo Cáo Doanh Thu &amp; Khách Mua Hàng {{ 'Tháng ' . $parsedDate->format('m/Y') }}
        </h3>
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">MONTHLY REPORT</span>
      </div>
      <p class="text-muted small mb-0 ms-4 ps-2">Xem chi tiết toàn bộ khách hàng mua đơn và tổng doanh thu bán lẻ trong tháng</p>
    </div>

    <!-- Bộ Chọn Tháng & Nút Thao Tác -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <form action="{{ route('admin.revenue.monthly') }}" method="GET" class="d-flex align-items-center gap-2">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white border-warning-subtle text-warning"><i class="fa-solid fa-calendar-days"></i></span>
          <select name="month" class="form-select form-select-sm fw-bold border-warning-subtle text-dark" onchange="this.form.submit()" style="min-width: 170px;">
            @foreach($availableMonths as $val => $label)
              <option value="{{ $val }}" {{ $selectedMonth === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </form>

      <button onclick="window.print()" class="btn btn-outline-dark btn-sm px-3">
        <i class="fa-solid fa-print me-1.5"></i> In Báo Cáo
      </button>
    </div>
  </div>
</div>

<!-- 4 THẺ CHỈ SỐ DOANH THU THÁNG (LUXURY KPI CARDS) -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng Doanh Thu Tháng -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card border-warning-subtle shadow-sm" style="border-left: 4px solid var(--atino-gold) !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Doanh Thu Tháng</span>
          <h3 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.65rem;">{{ number_format($monthlyRevenue, 0, ',', '.') }}₫</h3>
        </div>
        <div class="bee-stat-icon primary">
          <i class="fa-solid fa-sack-dollar"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $growthRate }} so tháng trước
        </span>
        <span class="badge bg-warning-subtle text-dark fw-bold" style="font-size: 0.7rem;">{{ $parsedDate->format('m/Y') }}</span>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Đơn Hàng Trong Tháng -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Đơn Hàng Tháng</span>
          <h3 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.65rem;">{{ $monthlyOrdersCount }} <span class="fs-6 fw-normal text-muted">Đơn</span></h3>
        </div>
        <div class="bee-stat-icon success">
          <i class="fa-solid fa-receipt"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-check me-1"></i> {{ $completedOrdersCount }} hoàn tất
        </span>
        @if($cancelledOrdersCount > 0)
          <span class="text-danger small fw-semibold"><i class="fa-solid fa-xmark me-1"></i> {{ $cancelledOrdersCount }} đã hủy</span>
        @endif
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Khách Hàng Mua Trong Tháng (Bấm để xem danh sách toàn bộ khách hàng) -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card shadow-sm transition-all hover-lift position-relative" 
         style="cursor: pointer; border-left: 4px solid #0284c7 !important; background: #ffffff;"
         data-bs-toggle="modal" 
         data-bs-target="#monthlyCustomersModal"
         title="Bấm để xem danh sách tất cả {{ $totalCustomersInMonth }} khách hàng mua trong tháng {{ $parsedDate->format('m/Y') }}">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Khách Mua Trong Tháng</span>
          <h3 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.65rem;">{{ $totalCustomersInMonth }} <span class="fs-6 fw-normal text-muted">Khách</span></h3>
        </div>
        <div class="bee-stat-icon info shadow-sm">
          <i class="fa-solid fa-users"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-primary small fw-bold">
          <i class="fa-solid fa-arrow-pointer me-1 text-primary"></i> Xem toàn bộ {{ $totalCustomersInMonth }} khách
        </span>
        <span class="badge bg-primary text-white fw-bold px-2 py-0.5" style="font-size: 0.68rem;">
          <i class="fa-regular fa-eye me-1"></i> Chi tiết
        </span>
      </div>
    </div>
  </div>


  <!-- Thẻ 4: Giá Trị Đơn Trung Bình (AOV) -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Giá Trị TB / Đơn (AOV)</span>
          <h3 class="fw-bold text-danger mb-0 mt-1" style="font-size: 1.65rem;">{{ number_format($aovMonth, 0, ',', '.') }}₫</h3>
        </div>
        <div class="bee-stat-icon danger">
          <i class="fa-solid fa-tag"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-muted small">Doanh thu / Đơn hợp lệ</span>
        <span class="text-success small fw-semibold">Chuẩn VIP</span>
      </div>
    </div>
  </div>
</div>

<!-- BẢNG DANH SÁCH TẤT CẢ KHÁCH HÀNG & ĐƠN HÀNG MUA TRONG THÁNG -->
<div class="bee-table-card mb-4">
  <!-- Card Header & Bộ Lọc Tình Trạng -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
        <i class="fa-solid fa-list-check fs-11"></i>
      </div>
      <div>
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
          Danh Sách Khách Hàng &amp; Đơn Hàng Tháng {{ $parsedDate->format('m/Y') }}
        </h5>
        <small class="text-muted">Tổng cộng {{ $orders->total() }} lượt mua hàng trong tháng</small>
      </div>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin.revenue.monthly') }}" method="GET" class="d-flex align-items-center gap-2">
      <input type="hidden" name="month" value="{{ $selectedMonth }}">
      <div class="input-group input-group-sm" style="width: 260px;">
        <input type="text" name="q" class="form-control" placeholder="Tìm tên khách, SĐT, mã đơn..." value="{{ $search }}">
        <button class="btn btn-bee-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
    </form>
  </div>

  <!-- Bảng Dữ Liệu -->
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Mã Đơn</th>
          <th>Khách Hàng</th>
          <th>Sản Phẩm Đã Mua</th>
          <th>Tổng Tiền</th>
          <th>Thời Gian Mua</th>
          <th>Thanh Toán</th>
          <th>Giao Hàng</th>
          <th class="text-end">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <!-- Mã Đơn -->
            <td>
              <span class="font-monospace fw-bold text-primary">{{ $order->order_code }}</span>
            </td>

            <!-- Khách Hàng & Avatar -->
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <img src="{{ asset($order->user->avatar_url ?? '/assets/img/team/40x40/58.webp') }}" 
                     alt="{{ $order->customer_name }}" 
                     class="rounded-circle border" 
                     style="width: 40px; height: 40px; object-fit: cover;">
                <div>
                  <strong class="text-dark d-block small">{{ $order->customer_name }}</strong>
                  <div class="text-muted" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-phone me-1 text-muted"></i>{{ $order->customer_phone }}
                  </div>
                  @if($order->customer_email)
                    <div class="text-muted text-truncate" style="font-size: 0.72rem; max-width: 160px;">{{ $order->customer_email }}</div>
                  @endif
                </div>
              </div>
            </td>

            <!-- Sản Phẩm Đã Mua -->
            <td>
              <div class="d-flex flex-column gap-1.5" style="max-width: 260px;">
                @foreach($order->items->take(2) as $item)
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($item->product->image ?? 'assets/img/products/1.png') }}" 
                         alt="{{ $item->product_name }}" 
                         style="width: 32px; height: 32px; object-fit: cover;" 
                         class="rounded border bg-white flex-shrink-0">
                    <div class="text-truncate">
                      <span class="small fw-semibold text-dark text-truncate d-block" style="font-size: 0.8rem;">{{ $item->product_name }}</span>
                      <small class="text-muted" style="font-size: 0.72rem;">
                        {{ $item->size ? 'Size: ' . $item->size : '' }} {{ $item->color ? '| Màu: ' . $item->color : '' }} x{{ $item->quantity }}
                      </small>
                    </div>
                  </div>
                @endforeach
                @if($order->items->count() > 2)
                  <small class="text-muted fst-italic">+{{ $order->items->count() - 2 }} sản phẩm khác</small>
                @endif
              </div>
            </td>

            <!-- Tổng Tiền -->
            <td>
              <strong class="text-danger fs-6">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
              @if($order->discount_amount > 0)
                <div class="text-success" style="font-size: 0.72rem;">Giảm: -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</div>
              @endif
            </td>

            <!-- Thời Gian Mua Trong Tháng -->
            <td>
              <span class="small text-dark fw-semibold d-block">{{ $order->created_at ? $order->created_at->format('d/m/Y') : '' }}</span>
              <small class="text-muted" style="font-size: 0.75rem;">{{ $order->created_at ? $order->created_at->format('H:i') : '' }}</small>
            </td>

            <!-- Trạng Thái Thanh Toán -->
            <td>
              @if($order->payment_status === 'paid')
                <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold" style="font-size: 0.75rem;">
                  <i class="fa-solid fa-circle-check me-1"></i> Đã thanh toán
                </span>
              @elseif($order->payment_status === 'refunded')
                <span class="badge bg-danger-subtle text-danger py-1 px-2 fw-bold" style="font-size: 0.75rem;">
                  <i class="fa-solid fa-rotate-left me-1"></i> Đã hoàn tiền
                </span>
              @else
                <span class="badge bg-warning-subtle text-dark py-1 px-2 fw-bold" style="font-size: 0.75rem;">
                  <i class="fa-solid fa-clock me-1"></i> Chưa thanh toán
                </span>
              @endif
              <div class="text-muted small mt-0.5" style="font-size: 0.72rem;">{{ $order->payment_method_name }}</div>
            </td>

            <!-- Trạng Thái Vận Chuyển -->
            <td>
              @if($order->shipping_status === 'completed')
                <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hoàn tất</span>
              @elseif($order->shipping_status === 'delivered')
                <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold"><i class="fa-solid fa-box-open me-1"></i> Đã giao</span>
              @elseif($order->shipping_status === 'shipping')
                <span class="badge bg-warning-subtle text-dark py-1 px-2 fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> Đang giao</span>
              @elseif($order->shipping_status === 'cancelled')
                <span class="badge bg-danger-subtle text-danger py-1 px-2 fw-bold"><i class="fa-solid fa-xmark me-1"></i> Đã hủy</span>
              @else
                <span class="badge bg-info-subtle text-info py-1 px-2 fw-bold"><i class="fa-solid fa-box me-1"></i> {{ $order->status_label }}</span>
              @endif
            </td>

            <!-- Thao Tác -->
            <td class="text-end">
              <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold py-1 px-2.5" style="font-size: 0.75rem;">
                <i class="fa-regular fa-eye me-1"></i> Chi Tiết
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="fa-solid fa-receipt fs-2 text-secondary-subtle mb-2 d-block"></i>
              Không tìm thấy đơn hàng nào trong tháng {{ $parsedDate->format('m/Y') }}.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  @if($orders->hasPages())
    <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
      <div class="small text-muted">
        Hiển thị từ {{ $orders->firstItem() }} đến {{ $orders->lastItem() }} trong tổng số {{ $orders->total() }} đơn hàng
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
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 18px 24px;">
        <div class="d-flex align-items-center gap-3">
          <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 44px; height: 44px; font-size: 1.2rem;">
            <i class="fa-solid fa-users-viewfinder"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="monthlyCustomersModalLabel">
              Toàn Bộ Khách Hàng Mua Sắm {{ 'Tháng ' . $parsedDate->format('m/Y') }}
            </h5>
            <small class="text-white-50" style="font-size: 0.8rem;">
              Tổng cộng {{ $totalCustomersInMonth }} khách hàng • {{ $monthlyOrdersCount }} đơn hàng • Tổng chi tiêu: {{ number_format($monthlyRevenue, 0, ',', '.') }}₫
            </small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4 bg-light">
        
        <!-- 4 THẺ TÓM TẮT NHANH TRONG MODAL -->
        <div class="row g-3 mb-4">
          <div class="col-md-3 col-6">
            <div class="p-3 bg-white rounded-3 border shadow-xs text-center">
              <span class="text-muted small fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Tổng Khách Mua Hàng</span>
              <h4 class="fw-bold text-primary mb-0">{{ $totalCustomersInMonth }} <small class="fs-6 text-muted fw-normal">Khách</small></h4>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3 bg-white rounded-3 border shadow-xs text-center">
              <span class="text-muted small fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Tổng Đơn Đã Đặt</span>
              <h4 class="fw-bold text-dark mb-0">{{ $monthlyOrdersCount }} <small class="fs-6 text-muted fw-normal">Đơn</small></h4>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3 bg-white rounded-3 border shadow-xs text-center">
              <span class="text-muted small fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Doanh Số Khách Mua</span>
              <h4 class="fw-bold text-danger mb-0">{{ number_format($monthlyRevenue, 0, ',', '.') }}₫</h4>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="p-3 bg-white rounded-3 border shadow-xs text-center">
              <span class="text-muted small fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem;">Chi Tiêu TB / Khách</span>
              <h4 class="fw-bold text-success mb-0">{{ $totalCustomersInMonth > 0 ? number_format(round($monthlyRevenue / $totalCustomersInMonth), 0, ',', '.') : 0 }}₫</h4>
            </div>
          </div>
        </div>

        <!-- Ô TÌM KIẾM NHANH KHÁCH HÀNG TRONG MODAL -->
        <div class="card border-0 shadow-sm p-3 mb-3 bg-white rounded-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="input-group input-group-sm" style="max-width: 380px;">
              <span class="input-group-text bg-light border text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
              <input type="text" id="filterMonthlyCustomersInput" class="form-control" placeholder="Tìm kiếm nhanh tên khách, SĐT, email...">
            </div>
            <div class="text-muted small">
              Hiển thị: <strong id="visibleCustomerCount">{{ count($monthlyCustomersList) }}</strong> / {{ $totalCustomersInMonth }} khách hàng
            </div>
          </div>
        </div>

        <!-- BẢNG CHI TIẾT TỪNG KHÁCH HÀNG MUA TRONG THÁNG -->
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="monthlyCustomersTable">
              <thead class="table-light">
                <tr>
                  <th style="width: 60px;" class="text-center">#</th>
                  <th>Khách Hàng (Tài khoản)</th>
                  <th>Thông Tin Liên Hệ</th>
                  <th>Hạng Thành Viên</th>
                  <th class="text-center">Đơn Mua Tháng Này</th>
                  <th class="text-end">Chi Tiêu Tháng Này</th>
                  <th style="min-width: 220px;">Chi Tiết Đơn Hàng</th>
                  <th class="text-end" style="width: 120px;">Thao Tác</th>
                </tr>
              </thead>
              <tbody>
                @forelse($monthlyCustomersList as $index => $c)
                  <tr class="customer-row" data-search="{{ mb_strtolower($c['name'] . ' ' . $c['phone'] . ' ' . $c['email']) }}">
                    <!-- Xếp hạng chi tiêu trong tháng -->
                    <td class="text-center">
                      @if($index == 0)
                        <span class="badge bg-warning text-dark fw-bold rounded-circle p-2" title="Top 1 chi tiêu tháng" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                          <i class="fa-solid fa-trophy"></i>
                        </span>
                      @elseif($index == 1)
                        <span class="badge bg-secondary text-white fw-bold rounded-circle p-2" title="Top 2 chi tiêu tháng" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                          2
                        </span>
                      @elseif($index == 2)
                        <span class="badge bg-danger-subtle text-danger fw-bold rounded-circle p-2" title="Top 3 chi tiêu tháng" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                          3
                        </span>
                      @else
                        <span class="text-muted fw-bold small">{{ $index + 1 }}</span>
                      @endif
                    </td>

                    <!-- Tên & Avatar -->
                    <td>
                      <div class="d-flex align-items-center gap-2.5">
                        <img src="{{ $c['avatar'] }}" alt="{{ $c['name'] }}" class="rounded-circle border bg-white shadow-xs" style="width: 44px; height: 44px; object-fit: cover;">
                        <div>
                          <strong class="text-dark d-block small mb-0.5">{{ $c['name'] }}</strong>
                          @if($c['is_registered'])
                            <span class="badge bg-success-subtle text-success py-0.5 px-1.5 fw-bold" style="font-size: 0.68rem;">
                              <i class="fa-solid fa-shield-check me-0.5"></i> Thành viên
                            </span>
                          @else
                            <span class="badge bg-light text-muted border py-0.5 px-1.5" style="font-size: 0.68rem;">
                              Khách vãng lai
                            </span>
                          @endif
                        </div>
                      </div>
                    </td>

                    <!-- Liên hệ -->
                    <td>
                      <div class="small fw-semibold text-dark">{{ $c['phone'] }}</div>
                      <small class="text-muted text-truncate d-block" style="max-width: 180px;">{{ $c['email'] }}</small>
                    </td>

                    <!-- Hạng thành viên -->
                    <td>
                      <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-crown me-1 text-warning"></i> {{ $c['rank'] }}
                      </span>
                    </td>

                    <!-- Số đơn trong tháng -->
                    <td class="text-center">
                      <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1" style="font-size: 0.8rem;">
                        {{ $c['orders_count'] }} đơn
                      </span>
                      @if($c['completed_count'] > 0)
                        <div class="text-success mt-0.5 fw-semibold" style="font-size: 0.7rem;">{{ $c['completed_count'] }} hoàn tất</div>
                      @endif
                    </td>

                    <!-- Tổng chi tiêu trong tháng -->
                    <td class="text-end">
                      <strong class="text-danger fs-6">{{ $c['total_spent_in_month_formatted'] }}</strong>
                    </td>

                    <!-- Danh sách các đơn đã mua trong tháng -->
                    <td>
                      <div class="d-flex flex-wrap gap-1.5" style="max-width: 280px;">
                        @foreach($c['orders'] as $ord)
                          <a href="{{ route('admin.orders.show', $ord['id']) }}" target="_blank" 
                             class="badge bg-light text-dark border text-decoration-none py-1 px-1.5 fw-semibold"
                             title="Đơn #{{ $ord['order_code'] }} - {{ $ord['total_amount_formatted'] }} ({{ $ord['shipping_status_label'] }})">
                            <i class="fa-solid fa-receipt text-primary me-0.5"></i> #{{ $ord['order_code'] }}
                          </a>
                        @endforeach
                      </div>
                    </td>

                    <!-- Thao tác -->
                    <td class="text-end">
                      @if($c['user_id'])
                        <a href="{{ route('admin.customers.show', $c['user_id']) }}" target="_blank" class="btn btn-sm btn-outline-dark fw-bold py-1 px-2 text-nowrap" style="font-size: 0.75rem;">
                          <i class="fa-regular fa-id-card me-1"></i> Hồ Sơ
                        </a>
                      @else
                        <a href="{{ route('admin.revenue.monthly', ['month' => $selectedMonth, 'q' => $c['phone']]) }}" class="btn btn-sm btn-outline-secondary fw-bold py-1 px-2 text-nowrap" style="font-size: 0.75rem;">
                          <i class="fa-solid fa-filter me-1"></i> Lọc đơn
                        </a>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                      <i class="fa-solid fa-users-slash fs-2 text-secondary-subtle mb-2 d-block"></i>
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
      <div class="modal-footer border-top bg-white py-2.5 px-4 d-flex justify-content-between align-items-center">
        <span class="small text-muted">
          <i class="fa-solid fa-circle-info text-primary me-1"></i> Dữ liệu được tính tự động dựa trên toàn bộ đơn hàng hợp lệ trong tháng {{ $parsedDate->format('m/Y') }}
        </span>
        <button type="button" class="btn btn-secondary btn-sm px-4 rounded-2 fw-bold" data-bs-dismiss="modal">
          Đóng Hộp Thoại
        </button>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('filterMonthlyCustomersInput');
    const rows = document.querySelectorAll('#monthlyCustomersTable tbody .customer-row');
    const countEl = document.getElementById('visibleCustomerCount');

    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
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

        if (countEl) {
          countEl.textContent = visible;
        }
      });
    }
  });
</script>
@endpush
@endsection

