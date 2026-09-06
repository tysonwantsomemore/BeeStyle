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
<!-- TOP HEADER & CONTROLS -->
<div class="mb-4 no-print">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" title="Quay lại Dashboard">
          <i class="fa-solid fa-arrow-left fs-12"></i>
        </a>
        <h3 class="fw-bold text-dark mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">
          Báo Cáo Doanh Thu Tháng {{ $parsedDate->format('m/Y') }}
        </h3>
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">
          <i class="fa-solid fa-chart-line me-1"></i> EXECUTIVE REPORT
        </span>
      </div>
      <p class="text-muted small mb-0 ms-4 ps-2">
        Số liệu phân tích tổng hợp doanh thu bán lẻ, biến động theo ngày, cơ cấu thanh toán và xếp hạng khách hàng VIP.
      </p>
    </div>

    <!-- Bộ Chọn Tháng & Xuất Báo Cáo -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <form action="{{ route('admin.revenue.monthly') }}" method="GET" class="d-flex align-items-center gap-2">
        <div class="input-group input-group-sm shadow-xs">
          <span class="input-group-text bg-white border-warning-subtle text-warning">
            <i class="fa-solid fa-calendar-days"></i>
          </span>
          <select name="month" class="form-select form-select-sm fw-bold border-warning-subtle text-dark" onchange="this.form.submit()" style="min-width: 175px;">
            @foreach($availableMonths as $val => $label)
              <option value="{{ $val }}" {{ $selectedMonth === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </form>

      <button type="button" onclick="exportTableToCSV('bee-revenue-report-{{ $selectedMonth }}.csv')" class="btn btn-outline-success btn-sm px-3 shadow-xs fw-semibold">
        <i class="fa-solid fa-file-excel me-1.5"></i> Xuất CSV
      </button>

      <button type="button" onclick="window.print()" class="btn btn-dark btn-sm px-3 shadow-xs fw-semibold">
        <i class="fa-solid fa-print me-1.5"></i> In Báo Cáo
      </button>
    </div>
  </div>
</div>

<!-- 4 THẺ CHỈ SỐ DOANH THU THÁNG (EXECUTIVE KPI CARDS) -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng Doanh Thu Tháng -->
  <div class="col-xl-3 col-sm-6">
    <div class="bee-kpi-card gold">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Doanh Thu Tháng</span>
          <h3 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.65rem;">{{ number_format($monthlyRevenue, 0, ',', '.') }}₫</h3>
        </div>
        <div class="bee-kpi-icon gold">
          <i class="fa-solid fa-sack-dollar"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $growthRate }} so tháng trước
        </span>
        <span class="badge bg-warning-subtle text-dark fw-bold" style="font-size: 0.7rem;">Tháng {{ $parsedDate->format('m/Y') }}</span>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Đơn Hàng Trong Tháng -->
  <div class="col-xl-3 col-sm-6">
    <div class="bee-kpi-card emerald">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Đơn Hàng Tháng</span>
          <h3 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.65rem;">{{ $monthlyOrdersCount }} <span class="fs-6 fw-normal text-muted">Đơn</span></h3>
        </div>
        <div class="bee-kpi-icon emerald">
          <i class="fa-solid fa-receipt"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-circle-check me-1"></i> {{ $completedOrdersCount }} hoàn tất
        </span>
        @if($cancelledOrdersCount > 0)
          <span class="text-danger small fw-semibold">
            <i class="fa-solid fa-xmark me-1"></i> {{ $cancelledOrdersCount }} đã hủy
          </span>
        @else
          <span class="text-muted small">0 đơn hủy</span>
        @endif
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Khách Hàng Mua Trong Tháng -->
  <div class="col-xl-3 col-sm-6">
    <div class="bee-kpi-card blue">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Khách Mua Trong Tháng</span>
          <h3 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.65rem;">{{ $totalCustomersInMonth }} <span class="fs-6 fw-normal text-muted">Khách</span></h3>
        </div>
        <div class="bee-kpi-icon blue">
          <i class="fa-solid fa-users"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-primary small fw-semibold">
          <i class="fa-solid fa-crown me-1"></i> 100% Khách mua thật
        </span>
        <span class="text-muted small">Thành viên &amp; vãng lai</span>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Giá Trị Đơn Trung Bình (AOV) -->
  <div class="col-xl-3 col-sm-6">
    <div class="bee-kpi-card indigo">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Giá Trị TB / Đơn (AOV)</span>
          <h3 class="fw-bold text-dark mb-0 mt-1" style="font-size: 1.65rem;">{{ number_format($aovMonth, 0, ',', '.') }}₫</h3>
        </div>
        <div class="bee-kpi-icon indigo">
          <i class="fa-solid fa-tags"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-muted small">Doanh thu / Đơn hợp lệ</span>
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-award me-1"></i> Chuẩn VIP
        </span>
      </div>
    </div>
  </div>
</div>

<!-- BIỂU ĐỒ TRỰC QUAN DOANH THU & PHƯƠNG THỨC THANH TOÁN (CHART.JS ROW) -->
<div class="row g-3 mb-4">
  <!-- Biểu đồ đường: Doanh thu theo từng ngày trong tháng -->
  <div class="col-lg-8">
    <div class="bee-chart-card h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">
            <i class="fa-solid fa-chart-area text-warning me-2"></i> Xu Hướng Doanh Thu Theo Ngày (Tháng {{ $parsedDate->format('m/Y') }})
          </h5>
          <small class="text-muted">Biểu diễn dòng tiền doanh thu bán hàng mỗi ngày trong tháng</small>
        </div>
        <span class="badge bg-warning-subtle text-dark fw-bold border border-warning">
          <i class="fa-solid fa-bolt me-1 text-warning"></i> Real-time DB
        </span>
      </div>
      <div style="position: relative; height: 300px; width: 100%;">
        <canvas id="dailyRevenueChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Biểu đồ tròn: Cơ cấu phương thức thanh toán -->
  <div class="col-lg-4">
    <div class="bee-chart-card h-100 d-flex flex-column justify-content-between">
      <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.05rem;">
              <i class="fa-solid fa-chart-pie text-primary me-2"></i> Phương Thức Thanh Toán
            </h5>
            <small class="text-muted">Tỷ trọng doanh số theo cổng</small>
          </div>
          <span class="badge bg-primary-subtle text-primary fw-bold">Tổng hợp</span>
        </div>
        <div style="position: relative; height: 210px; width: 100%;">
          <canvas id="paymentMethodChart"></canvas>
        </div>
      </div>
      
      <!-- Breakdown Summary list -->
      <div class="mt-3 pt-3 border-top">
        <div class="d-flex flex-column gap-2 small">
          @forelse($paymentLabels as $idx => $label)
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-muted"><i class="fa-solid fa-circle-dot me-1.5 text-warning"></i> {{ $label }} ({{ $paymentCounts[$idx] ?? 0 }} đơn):</span>
              <strong class="text-dark">{{ number_format($paymentData[$idx] ?? 0, 0, ',', '.') }}₫</strong>
            </div>
          @empty
            <div class="text-muted text-center py-2">Chưa có giao dịch thanh toán trong tháng này</div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TOP 5 KHÁCH HÀNG VIP CHI TIÊU NHIỀU NHẤT TRONG THÁNG -->
@if(isset($topCustomers) && $topCustomers->isNotEmpty())
  <div class="bee-chart-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="d-flex align-items-center gap-2">
        <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
          <i class="fa-solid fa-crown fs-5 text-warning"></i>
        </div>
        <div>
          <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
            Top Khách Hàng VIP Chi Tiêu Nhiều Nhất Tháng {{ $parsedDate->format('m/Y') }}
          </h5>
          <small class="text-muted">Vinh danh các khách hàng đóng góp doanh số cao nhất trong kỳ</small>
        </div>
      </div>
      <span class="badge bg-dark text-white px-2.5 py-1 rounded-pill">Top {{ $topCustomers->count() }} VIP Spenders</span>
    </div>

    <div class="row g-3">
      @foreach($topCustomers as $index => $vip)
        @php
          $rankClass = match($index) {
            0 => 'bee-vip-rank-1',
            1 => 'bee-vip-rank-2',
            2 => 'bee-vip-rank-3',
            default => 'bee-vip-rank-default',
          };
          $rankIcon = match($index) {
            0 => 'fa-crown text-warning',
            1 => 'fa-medal text-secondary',
            2 => 'fa-award text-danger',
            default => 'fa-star text-muted',
          };
        @endphp
        <div class="col-lg col-md-4 col-sm-6">
          <div class="card h-100 p-3 border rounded-3 shadow-2xs position-relative" style="background: #fafafa;">
            <div class="position-absolute top-0 end-0 m-2">
              <span class="badge {{ $rankClass }} rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.75rem;">
                #{{ $index + 1 }}
              </span>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center shadow-xs" style="width: 40px; height: 40px;">
                <i class="fa-solid {{ $rankIcon }} fs-5"></i>
              </div>
              <div class="text-truncate">
                <strong class="text-dark d-block text-truncate small" title="{{ $vip->customer_name }}">{{ $vip->customer_name }}</strong>
                <small class="text-muted" style="font-size: 0.72rem;">{{ $vip->customer_phone }}</small>
              </div>
            </div>
            <div class="pt-2 border-top mt-auto">
              <div class="d-flex justify-content-between align-items-center small">
                <span class="text-muted">Tổng chi:</span>
                <strong class="text-danger fw-bold">{{ number_format($vip->total_spent, 0, ',', '.') }}₫</strong>
              </div>
              <div class="d-flex justify-content-between align-items-center small mt-1">
                <span class="text-muted">Đơn mua:</span>
                <span class="badge bg-light text-dark border">{{ $vip->total_orders }} đơn</span>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endif

<!-- BẢNG DANH SÁCH CHI TIẾT TẤT CẢ ĐƠN HÀNG VÀ KHÁCH MUA TRONG THÁNG -->
<div class="bee-table-card mb-4" id="revenueReportTableWrapper">
  <!-- Card Header & Bộ Lọc Tình Trạng -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom py-3">
    <div class="d-flex align-items-center gap-2">
      <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
        <i class="fa-solid fa-receipt fs-6"></i>
      </div>
      <div>
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
          Danh Sách Giao Dịch &amp; Khách Hàng Tháng {{ $parsedDate->format('m/Y') }}
        </h5>
        <small class="text-muted">Gồm {{ $orders->total() }} lượt giao dịch ghi nhận trong hệ thống</small>
      </div>
    </div>

    <!-- Quick Status Filter Pills & Search -->
    <div class="d-flex align-items-center gap-2 flex-wrap no-print">
      <!-- Status Pills -->
      <div class="btn-group btn-group-sm" role="group">
        <a href="{{ route('admin.revenue.monthly', array_merge(request()->query(), ['status' => ''])) }}" 
           class="btn {{ empty($status) ? 'btn-dark' : 'btn-outline-secondary' }}">
          Tất cả ({{ $monthlyOrdersCount }})
        </a>
        <a href="{{ route('admin.revenue.monthly', array_merge(request()->query(), ['status' => 'completed'])) }}" 
           class="btn {{ $status === 'completed' ? 'btn-success' : 'btn-outline-secondary' }}">
          Hoàn tất
        </a>
        <a href="{{ route('admin.revenue.monthly', array_merge(request()->query(), ['status' => 'delivered'])) }}" 
           class="btn {{ $status === 'delivered' ? 'btn-success' : 'btn-outline-secondary' }}">
          Đã giao
        </a>
        <a href="{{ route('admin.revenue.monthly', array_merge(request()->query(), ['status' => 'shipping'])) }}" 
           class="btn {{ $status === 'shipping' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}">
          Đang giao
        </a>
        <a href="{{ route('admin.revenue.monthly', array_merge(request()->query(), ['status' => 'cancelled'])) }}" 
           class="btn {{ $status === 'cancelled' ? 'btn-danger' : 'btn-outline-secondary' }}">
          Đã hủy
        </a>
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

  <!-- Bảng Dữ Liệu -->
  <div class="table-responsive">
    <table class="table align-middle mb-0 table-hover" id="monthlyOrdersTable">
      <thead class="table-light">
        <tr>
          <th style="width: 130px;">Mã Đơn</th>
          <th style="min-width: 200px;">Khách Hàng</th>
          <th style="min-width: 240px;">Sản Phẩm Đã Mua</th>
          <th style="width: 140px;">Tổng Tiền</th>
          <th style="width: 120px;">Thời Gian</th>
          <th style="width: 150px;">Thanh Toán</th>
          <th style="width: 140px;">Vận Chuyển</th>
          <th class="text-end no-print" style="width: 90px;">Chi Tiết</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <!-- Mã Đơn -->
            <td>
              <a href="{{ route('admin.orders.show', $order->id) }}" class="font-monospace fw-bold text-primary text-decoration-none">
                {{ $order->order_code }}
              </a>
            </td>

            <!-- Khách Hàng & Avatar -->
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <img src="{{ asset($order->user->avatar_url ?? '/assets/img/team/40x40/58.webp') }}" 
                     alt="{{ $order->customer_name }}" 
                     class="rounded-circle border" 
                     style="width: 38px; height: 38px; object-fit: cover;">
                <div>
                  <strong class="text-dark d-block small">{{ $order->customer_name }}</strong>
                  <div class="text-muted" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-phone me-1 text-muted"></i>{{ $order->customer_phone }}
                  </div>
                  @if($order->customer_email)
                    <div class="text-muted text-truncate" style="font-size: 0.72rem; max-width: 150px;">{{ $order->customer_email }}</div>
                  @endif
                </div>
              </div>
            </td>

            <!-- Sản Phẩm Đã Mua -->
            <td>
              <div class="d-flex flex-column gap-1.5" style="max-width: 250px;">
                @foreach($order->items->take(2) as $item)
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($item->product->image ?? 'assets/img/products/1.png') }}" 
                         alt="{{ $item->product_name }}" 
                         style="width: 30px; height: 30px; object-fit: cover;" 
                         class="rounded border bg-white flex-shrink-0">
                    <div class="text-truncate">
                      <span class="small fw-semibold text-dark text-truncate d-block" style="font-size: 0.78rem;">{{ $item->product_name }}</span>
                      <small class="text-muted" style="font-size: 0.7rem;">
                        {{ $item->size ? 'Size: ' . $item->size : '' }} {{ $item->color ? '| ' . $item->color : '' }} x{{ $item->quantity }}
                      </small>
                    </div>
                  </div>
                @endforeach
                @if($order->items->count() > 2)
                  <small class="text-muted fst-italic" style="font-size: 0.7rem;">+{{ $order->items->count() - 2 }} sản phẩm khác</small>
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
            <td class="text-end no-print">
              <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark fw-semibold py-1 px-2.5" style="font-size: 0.75rem;" title="Xem chi tiết đơn hàng">
                <i class="fa-regular fa-eye me-1"></i> Chi Tiết
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="fa-solid fa-receipt fs-2 text-secondary-subtle mb-2 d-block"></i>
              Không tìm thấy đơn hàng nào khớp với điều kiện lọc trong tháng {{ $parsedDate->format('m/Y') }}.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  @if($orders->hasPages())
    <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center no-print">
      <div class="small text-muted">
        Hiển thị từ {{ $orders->firstItem() }} đến {{ $orders->lastItem() }} trong tổng số {{ $orders->total() }} đơn hàng
      </div>
      <div>
        {{ $orders->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>
@endsection

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
