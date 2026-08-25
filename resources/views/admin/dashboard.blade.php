@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển Tổng Quan | BeeStyle Admin')

@section('content')
<!-- WELCOME HEADER -->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">LIVE SYSTEM</span>
        <h3 class="fw-bold text-dark mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">Tổng Quan Hoạt Động Kinh Doanh</h3>
      </div>
      <p class="text-muted small mb-0">Theo dõi doanh thu, đơn hàng, phản hồi khách hàng và tồn kho thời gian thực</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('admin.products.create') }}" class="btn btn-bee-primary btn-sm px-3">
        <i class="fa-solid fa-plus me-1.5"></i> Thêm Sản Phẩm
      </a>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark btn-sm px-3">
        <i class="fa-solid fa-list-check me-1.5"></i> Xử Lý Đơn Hàng
      </a>
      <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-warning text-dark btn-sm px-3 fw-bold">
        <i class="fa-solid fa-star text-warning me-1.5"></i> Đánh Giá Mới
      </a>
    </div>
  </div>
</div>

<!-- KPI STAT CARDS (4 THẺ THỐNG KÊ ĐỒNG BỘ CAO CẤP) -->
<div class="row g-3 mb-4">
  <!-- Revenue -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card position-relative hover-lift" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.revenue.monthly') }}'">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Doanh Thu Hệ Thống</span>
          <h3 class="fw-bold text-dark mb-0 mt-1.5" style="font-size: 1.65rem;">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</h3>
        </div>
        <div class="bee-stat-icon primary">
          <i class="fa-solid fa-wallet"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $stats['revenue_growth'] }}
        </span>
        <a href="{{ route('admin.revenue.monthly') }}" class="text-warning small text-decoration-none fw-bold">
          Xem đơn tháng này <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>


  <!-- Orders -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Đơn Hàng</span>
          <h3 class="fw-bold text-dark mb-0 mt-1.5" style="font-size: 1.65rem;">{{ $stats['total_orders'] }}</h3>
        </div>
        <div class="bee-stat-icon success">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $stats['orders_growth'] }}
        </span>
        <a href="{{ route('admin.orders.index') }}" class="text-primary small text-decoration-none">Xem đơn <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Customers -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Khách Hàng Thành Viên</span>
          <h3 class="fw-bold text-dark mb-0 mt-1.5" style="font-size: 1.65rem;">{{ $stats['total_customers'] }}</h3>
        </div>
        <div class="bee-stat-icon info">
          <i class="fa-solid fa-users"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $stats['customers_growth'] }}
        </span>
        <a href="{{ route('admin.customers.index') }}" class="text-primary small text-decoration-none">Hồ sơ khách <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </div>

  <!-- Reviews & Rating -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Đánh Giá Khách Hàng</span>
          <h3 class="fw-bold text-warning mb-0 mt-1.5" style="font-size: 1.65rem;">
            {{ $stats['total_reviews'] }} 
            <span class="fs-6 text-dark fw-bold">({{ number_format($stats['avg_rating'], 1) }} ⭐)</span>
          </h3>
        </div>
        <div class="bee-stat-icon danger">
          <i class="fa-solid fa-star"></i>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between pt-2 border-top">
        <span class="text-success small fw-semibold">
          <i class="fa-solid fa-circle-check me-1"></i> 98% hài lòng
        </span>
        <a href="{{ route('admin.reviews.index') }}" class="text-danger small text-decoration-none fw-bold">Xem nhận xét <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </div>
</div>

<!-- BIỂU ĐỒ ĐƯỜNG (LINE CHART) TĂNG TRƯỞNG DOANH THU & ĐƠN HÀNG HỆ THỐNG CAO CẤP -->
<div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 22px; background: #ffffff; border: 1.5px solid rgba(245, 158, 11, 0.25) !important;">
  
  <!-- Header Control Bar -->
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
      <div class="d-flex align-items-center gap-2.5 mb-1">
        <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px;">
          <i class="fa-solid fa-chart-line fs-6"></i>
        </div>
        <div>
          <div class="d-flex align-items-center gap-2">
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem; font-family: 'Plus Jakarta Sans', sans-serif;">Tăng Trưởng Doanh Thu &amp; Đơn Hàng</h5>
            <span class="badge bg-success-subtle text-success fw-bold px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">
              <i class="fa-solid fa-circle-dot me-1 text-success"></i> LIVE ANALYTICS
            </span>
          </div>
          <small class="text-muted">Dữ liệu phân tích doanh số và quy mô đơn hàng theo thời gian thực</small>
        </div>
      </div>
    </div>

    <!-- Segmented Filter Buttons & Date Range Picker By Calendar -->
    <div class="d-flex align-items-center gap-2.5 flex-wrap">
      <!-- Nút chọn nhanh (Presets) -->
      <div class="btn-group p-1 bg-light rounded-pill border shadow-xs" role="group">
        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1.5" id="btnChart7Days" onclick="setDateRangePreset('7days')">
          7 Ngày
        </button>
        <button type="button" class="btn btn-sm btn-light text-muted fw-semibold rounded-pill px-3 py-1.5" id="btnChart30Days" onclick="setDateRangePreset('30days')">
          30 Ngày
        </button>
        <button type="button" class="btn btn-sm btn-light text-muted fw-semibold rounded-pill px-3 py-1.5" id="btnChartThisMonth" onclick="setDateRangePreset('this_month')">
          Tháng Này
        </button>
        <button type="button" class="btn btn-sm btn-light text-muted fw-semibold rounded-pill px-3 py-1.5" id="btnChartMonths" onclick="setDateRangePreset('12months')">
          12 Tháng (2026)
        </button>
      </div>

      <!-- Bộ lọc Lịch Tùy Chọn Từ Ngày -> Đến Ngày (Calendar Range Picker) -->
      <div class="d-flex align-items-center gap-1.5 p-1 bg-light rounded-3 border shadow-xs">
        <div class="input-group input-group-sm" style="width: 135px;">
          <span class="input-group-text bg-white border-0 text-warning px-1.5"><i class="fa-regular fa-calendar"></i></span>
          <input type="date" id="chartDateStart" class="form-control form-control-sm border-0 bg-white fw-semibold" value="{{ now()->subDays(6)->format('Y-m-d') }}" title="Từ ngày">
        </div>
        <span class="text-muted small fw-bold px-0.5">➔</span>
        <div class="input-group input-group-sm" style="width: 135px;">
          <span class="input-group-text bg-white border-0 text-warning px-1.5"><i class="fa-regular fa-calendar-check"></i></span>
          <input type="date" id="chartDateEnd" class="form-control form-control-sm border-0 bg-white fw-semibold" value="{{ now()->format('Y-m-d') }}" title="Đến ngày">
        </div>
        <button type="button" class="btn btn-sm btn-warning text-dark fw-bold px-3 py-1 rounded-2 shadow-xs" onclick="applyCustomDateFilter()" title="Áp dụng lọc theo lịch">
          <i class="fa-solid fa-filter me-1"></i> Lọc
        </button>
      </div>
    </div>
  </div>


  <!-- 4 Ô CHỈ SỐ TÀI CHÍNH NỔI KHỐI (FINANCIAL KPI GRID) -->
  <div class="row g-3 mb-4">
    <!-- Metric 1: Revenue -->
    <div class="col-xl-3 col-sm-6">
      <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between h-100">
        <div>
          <span class="text-muted small fw-semibold d-block" style="font-size: 0.75rem;">DOANH THU KỲ</span>
          <h4 class="fw-black text-dark mb-0 mt-1" id="summaryRevenueTxt" style="font-size: 1.3rem;">{{ $chartData['seven_days']['summary_revenue'] }}</h4>
          <span class="badge bg-success-subtle text-success fw-bold px-2 py-0.5 mt-1" style="font-size: 0.68rem;" id="summaryGrowthTxt">
            <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $chartData['seven_days']['growth'] }}
          </span>
        </div>
        <div class="rounded-3 bg-warning text-dark p-2.5 text-center shadow-xs">
          <i class="fa-solid fa-wallet fs-5"></i>
        </div>
      </div>
    </div>

    <!-- Metric 2: Orders -->
    <div class="col-xl-3 col-sm-6">
      <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between h-100">
        <div>
          <span class="text-muted small fw-semibold d-block" style="font-size: 0.75rem;">ĐƠN HÀNG HOÀN TẤT</span>
          <h4 class="fw-black text-dark mb-0 mt-1" id="summaryOrdersTxt" style="font-size: 1.3rem;">{{ $chartData['seven_days']['summary_orders'] }}</h4>
          <small class="text-success fw-semibold" style="font-size: 0.72rem;"><i class="fa-solid fa-circle-check me-1"></i> Giao thành công 98.5%</small>
        </div>
        <div class="rounded-3 bg-success text-white p-2.5 text-center shadow-xs">
          <i class="fa-solid fa-box-open fs-5"></i>
        </div>
      </div>
    </div>

    <!-- Metric 3: AOV -->
    <div class="col-xl-3 col-sm-6">
      <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between h-100">
        <div>
          <span class="text-muted small fw-semibold d-block" style="font-size: 0.75rem;">GIÁ TRỊ TB / ĐƠN (AOV)</span>
          <h4 class="fw-black text-danger mb-0 mt-1" style="font-size: 1.3rem;">{{ $stats['total_orders'] > 0 ? number_format($stats['total_revenue'] / $stats['total_orders'], 0, ',', '.') : '389.000' }}₫</h4>
          <small class="text-muted" style="font-size: 0.72rem;">Chuẩn thời trang nam cao cấp</small>
        </div>
        <div class="rounded-3 bg-danger text-white p-2.5 text-center shadow-xs">
          <i class="fa-solid fa-tags fs-5"></i>
        </div>
      </div>
    </div>

    <!-- Metric 4: Conversion Rate -->
    <div class="col-xl-3 col-sm-6">
      <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between h-100">
        <div>
          <span class="text-muted small fw-semibold d-block" style="font-size: 0.75rem;">TỶ LỆ CHUYỂN ĐỔI</span>
          <h4 class="fw-black text-primary mb-0 mt-1" style="font-size: 1.3rem;">{{ $stats['conversion_rate'] }}</h4>
          <small class="text-success fw-semibold" style="font-size: 0.72rem;"><i class="fa-solid fa-arrow-up me-0.5"></i> +1.2% so với tháng trước</small>
        </div>
        <div class="rounded-3 bg-primary text-white p-2.5 text-center shadow-xs">
          <i class="fa-solid fa-chart-pie fs-5"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Chart Canvas with Smooth Height -->
  <div class="position-relative" style="height: 350px; width: 100%;">
    <canvas id="revenueGrowthChart"></canvas>
  </div>
</div>


<!-- KHÁCH HÀNG VỪA ĐÁNH GIÁ MỚI NHẤT -->
<div class="bee-table-card mb-4">

  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
        <i class="fa-solid fa-star fs-11"></i>
      </div>
      <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Khách Hàng Vừa Đánh Giá Sản Phẩm Mới Nhất</h5>
    </div>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-warning text-dark btn-sm fw-bold">
      Quản Lý Toàn Bộ Đánh Giá <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Khách Hàng</th>
          <th>Sản Phẩm Đánh Giá</th>
          <th>Số Sao</th>
          <th style="max-width: 320px;">Nội Dung Nhận Xét</th>
          <th>Thời Gian</th>
          <th>Trạng Thái</th>
          <th>Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentReviews as $rev)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <img src="{{ asset($rev->user->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $rev->user_name }}" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;">
                <div>
                  <strong class="text-dark d-block small">{{ $rev->user_name }}</strong>
                  <small class="text-muted" style="font-size: 0.75rem;">{{ $rev->user->email ?? 'Khách' }}</small>
                </div>
              </div>
            </td>
            <td>
              @if($rev->product)
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($rev->product->image) }}" alt="{{ $rev->product->name }}" style="width: 38px; height: 38px; object-fit: cover;" class="rounded border bg-white">
                  <div>
                    <span class="small fw-bold text-dark text-truncate d-block" style="max-width: 180px;">{{ $rev->product->name }}</span>
                    <span class="text-danger fw-bold" style="font-size: 0.72rem;">{{ number_format($rev->product->price, 0, ',', '.') }}₫</span>
                  </div>
                </div>
              @endif
            </td>
            <td>
              <div class="text-warning text-nowrap small">
                @for($i=1; $i<=5; $i++)
                  <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                @endfor
                <span class="fw-bold text-dark ms-1">({{ $rev->rating }}/5)</span>
              </div>
            </td>
            <td style="max-width: 320px;">
              <p class="small text-dark mb-0 fst-italic text-truncate" style="font-size: 0.82rem;">
                "{{ $rev->comment }}"
              </p>
            </td>
            <td><small class="text-muted text-nowrap">{{ $rev->created_at ? $rev->created_at->diffForHumans() : '' }}</small></td>
            <td>
              @if($rev->status === 'approved')
                <span class="badge bg-success-subtle text-success py-1 px-2" style="font-size: 0.72rem;"><i class="fa-solid fa-circle-check me-1"></i> Đã duyệt</span>
              @else
                <span class="badge bg-secondary-subtle text-muted py-1 px-2" style="font-size: 0.72rem;"><i class="fa-solid fa-eye-slash me-1"></i> Đã ẩn</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.reviews.index', ['q' => $rev->user_name]) }}" class="btn btn-sm btn-outline-dark py-1 px-2.5 fw-bold" style="font-size: 0.75rem;">
                <i class="fa-regular fa-eye me-1"></i> Xem
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">Chưa có đánh giá nào từ khách hàng.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- RECENT ORDERS TABLE (ĐƠN HÀNG MỚI NHẤT) -->
<div class="bee-table-card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
        <i class="fa-solid fa-receipt fs-11"></i>
      </div>
      <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Đơn Hàng Mới Nhất Cần Xử Lý</h5>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Xem tất cả đơn hàng <i class="fa-solid fa-arrow-right ms-1"></i></a>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Mã Đơn</th>
          <th>Khách Hàng</th>
          <th>Sản Phẩm</th>
          <th>Tổng Tiền</th>
          <th>Phương Thức</th>
          <th>Trạng Thái</th>
          <th>Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <td><span class="font-monospace fw-bold text-primary">{{ $order->order_code }}</span></td>
            <td>
              <div class="fw-bold text-dark small">{{ $order->customer_name }}</div>
              <small class="text-muted">{{ $order->customer_phone }}</small>
            </td>
            <td><span class="badge bg-light text-dark fw-normal border">{{ $order->items->count() }} mặt hàng</span></td>
            <td><strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
            <td><span class="small text-muted">{{ $order->payment_method_name }}</span></td>
            <td>
              @if($order->shipping_status === 'completed')
                <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hoàn tất</span>
              @elseif($order->shipping_status === 'delivered')
                <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold"><i class="fa-solid fa-box-open me-1"></i> Đã giao</span>
              @elseif($order->shipping_status === 'shipping')
                <span class="badge bg-warning-subtle text-dark py-1 px-2 fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> Đang giao hàng</span>
              @elseif($order->shipping_status === 'cancelled')
                <span class="badge bg-danger-subtle text-danger py-1 px-2 fw-bold"><i class="fa-solid fa-xmark me-1"></i> Đã hủy</span>
              @else
                <span class="badge bg-info-subtle text-info py-1 px-2 fw-bold"><i class="fa-solid fa-box me-1"></i> {{ $order->status_label }}</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold py-1 px-2.5" style="font-size: 0.75rem;">
                Chi Tiết
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">Chưa có đơn hàng nào trong hệ thống.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- TOP PRODUCTS & CATEGORIES STATS -->
<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="bee-table-card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
            <i class="fa-solid fa-fire fs-11"></i>
          </div>
          <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Top Sản Phẩm Bán Chạy Nhất</h5>
        </div>
        <a href="{{ route('admin.products.index') }}" class="small text-warning text-decoration-none fw-bold">Quản lý kho <i class="fa-solid fa-arrow-right ms-1"></i></a>
      </div>
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Sản phẩm</th>
              <th>Giá niêm yết</th>
              <th>Đã bán</th>
              <th>Tồn kho</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
          <tbody>
            @foreach($products as $p)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2.5">
                    <img src="{{ asset($p->image) }}" alt="{{ $p->name }}" style="width: 40px; height: 40px; object-fit: contain;" class="rounded border bg-white">
                    <div>
                      <div class="fw-bold small text-dark text-truncate" style="max-width: 240px;">{{ $p->name }}</div>
                      <small class="text-muted">{{ $p->category->name ?? 'Thời trang nam' }}</small>
                    </div>
                  </div>
                </td>
                <td><strong>{{ number_format($p->price, 0, ',', '.') }}₫</strong></td>
                <td><span class="badge bg-success-subtle text-success fw-bold px-2 py-1">{{ $p->sold_count }} đã bán</span></td>
                <td>
                  @if($p->stock <= 5)
                    <span class="badge bg-danger-subtle text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Còn {{ $p->stock }}</span>
                  @else
                    <span class="fw-semibold text-dark">{{ $p->stock }} cái</span>
                  @endif
                </td>
                <td><span class="badge bg-success-subtle text-success">Đang bán</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
          <i class="fa-solid fa-layer-group fs-11"></i>
        </div>
        <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Cơ Cấu Danh Mục Áo Nam</h5>
      </div>
      <div class="d-flex flex-column gap-2.5">
        @foreach($categories as $c)
          <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 bg-light border transition-all hover-lift">
            <div class="d-flex align-items-center gap-2.5">
              <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center text-warning" style="width: 32px; height: 32px;">
                <i class="{{ $c->icon }}"></i>
              </div>
              <span class="small fw-bold text-dark">{{ $c->name }}</span>
            </div>
            <span class="badge bg-white text-dark border fw-bold">{{ $c->products_count }} SP</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const chartDataRaw = @json($chartData);
  let revenueChartInstance = null;

  function initRevenueChart() {
    const ctx = document.getElementById('revenueGrowthChart');
    if (!ctx) return;

    const chartContext = ctx.getContext('2d');
    
    // Tạo gradient fill màu vàng hổ phách sang trọng đa tầng
    const goldGradient = chartContext.createLinearGradient(0, 0, 0, 320);
    goldGradient.addColorStop(0, 'rgba(245, 158, 11, 0.40)');
    goldGradient.addColorStop(0.5, 'rgba(245, 158, 11, 0.12)');
    goldGradient.addColorStop(1, 'rgba(245, 158, 11, 0.00)');

    const config = {
      type: 'line',
      data: {
        labels: chartDataRaw.seven_days.labels,
        datasets: [
          {
            label: 'Doanh Thu Thuần (VNĐ)',
            data: chartDataRaw.seven_days.revenue,
            borderColor: '#f59e0b',
            backgroundColor: goldGradient,
            borderWidth: 3.5,
            fill: true,
            tension: 0.42,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#f59e0b',
            pointBorderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 8,
            pointHoverBackgroundColor: '#f59e0b',
            pointHoverBorderColor: '#ffffff',
            pointHoverBorderWidth: 3,
            yAxisID: 'y',
          },
          {
            label: 'Số Đơn Hàng Hoàn Tất (Đơn)',
            data: chartDataRaw.seven_days.orders,
            borderColor: '#10b981',
            backgroundColor: 'transparent',
            borderWidth: 2.2,
            borderDash: [5, 5],
            fill: false,
            tension: 0.42,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#10b981',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 7,
            pointHoverBackgroundColor: '#10b981',
            pointHoverBorderColor: '#ffffff',
            yAxisID: 'y1',
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 900,
          easing: 'easeOutQuart'
        },
        interaction: {
          mode: 'index',
          intersect: false,
        },
        plugins: {
          legend: {
            display: true,
            position: 'top',
            align: 'end',
            labels: {
              usePointStyle: true,
              pointStyle: 'circle',
              padding: 20,
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 12.5,
                weight: '600'
              },
              color: '#334155'
            }
          },
          tooltip: {
            backgroundColor: '#090e17',
            borderColor: 'rgba(245, 158, 11, 0.4)',
            borderWidth: 1,
            titleColor: '#ffffff',
            bodyColor: '#f1f5f9',
            titleFont: {
              family: "'Plus Jakarta Sans', sans-serif",
              size: 13.5,
              weight: 'bold'
            },
            bodyFont: {
              family: "'Plus Jakarta Sans', sans-serif",
              size: 12.5
            },
            padding: 14,
            cornerRadius: 12,
            boxPadding: 6,
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.datasetIndex === 0) {
                  label += (context.parsed.y || 0).toLocaleString('vi-VN') + '₫';
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
            grid: {
              display: false,
            },
            ticks: {
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 12,
                weight: '600'
              },
              color: '#64748b'
            }
          },
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            grid: {
              color: 'rgba(226, 232, 240, 0.7)',
              drawBorder: false,
            },
            ticks: {
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 11.5,
                weight: '500'
              },
              color: '#64748b',
              callback: function(value) {
                if (value >= 1000000) {
                  return (value / 1000000).toFixed(1) + 'M';
                } else if (value >= 1000) {
                  return (value / 1000).toFixed(0) + 'k';
                }
                return value + '₫';
              }
            }
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            grid: {
              drawOnChartArea: false,
            },
            ticks: {
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 11.5,
                weight: '600'
              },
              color: '#10b981',
              stepSize: 1,
              callback: function(value) {
                return value + ' đơn';
              }
            }
          }
        }
      }
    };

    revenueChartInstance = new Chart(ctx, config);
  }

  // Hàm chọn nhanh theo Preset (7 Ngày / 30 Ngày / Tháng Này / 12 Tháng)
  function setDateRangePreset(preset) {
    const btn7 = document.getElementById('btnChart7Days');
    const btn30 = document.getElementById('btnChart30Days');
    const btnThisMonth = document.getElementById('btnChartThisMonth');
    const btnMonths = document.getElementById('btnChartMonths');
    const dateStartInput = document.getElementById('chartDateStart');
    const dateEndInput = document.getElementById('chartDateEnd');

    // Reset styles
    [btn7, btn30, btnThisMonth, btnMonths].forEach(b => {
      if (b) b.className = 'btn btn-sm btn-light text-muted fw-semibold rounded-pill px-3 py-1.5';
    });

    const today = new Date();
    const formatDate = (d) => d.toISOString().split('T')[0];

    if (preset === '7days') {
      btn7.className = 'btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1.5';
      const past7 = new Date();
      past7.setDate(today.getDate() - 6);
      dateStartInput.value = formatDate(past7);
      dateEndInput.value = formatDate(today);
      updateChartWithData(chartDataRaw.seven_days);
    } else if (preset === '30days') {
      btn30.className = 'btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1.5';
      const past30 = new Date();
      past30.setDate(today.getDate() - 29);
      dateStartInput.value = formatDate(past30);
      dateEndInput.value = formatDate(today);
      updateChartWithData(chartDataRaw.thirty_days);
    } else if (preset === 'this_month') {
      btnThisMonth.className = 'btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1.5';
      const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
      dateStartInput.value = formatDate(firstDay);
      dateEndInput.value = formatDate(today);
      fetchRevenueByDateRange(dateStartInput.value, dateEndInput.value);
    } else if (preset === '12months') {
      btnMonths.className = 'btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1.5';
      updateChartWithData(chartDataRaw.monthly);
    }
  }

  // Hàm áp dụng lọc theo khoảng ngày Lịch (Calendar Date Range)
  function applyCustomDateFilter() {
    const startDate = document.getElementById('chartDateStart').value;
    const endDate = document.getElementById('chartDateEnd').value;

    if (!startDate || !endDate) {
      alert('Vui lòng chọn đầy đủ ngày bắt đầu và ngày kết thúc trên lịch.');
      return;
    }

    if (new Date(startDate) > new Date(endDate)) {
      alert('Ngày bắt đầu không được lớn hơn ngày kết thúc!');
      return;
    }

    // Reset active buttons
    ['btnChart7Days', 'btnChart30Days', 'btnChartThisMonth', 'btnChartMonths'].forEach(id => {
      const b = document.getElementById(id);
      if (b) b.className = 'btn btn-sm btn-light text-muted fw-semibold rounded-pill px-3 py-1.5';
    });

    fetchRevenueByDateRange(startDate, endDate);
  }

  // Fetch dữ liệu từ API endpoint theo khoảng ngày
  function fetchRevenueByDateRange(startDate, endDate) {
    const summaryRev = document.getElementById('summaryRevenueTxt');
    if (summaryRev) summaryRev.innerHTML = '<span class="spinner-border spinner-border-sm text-warning"></span> Đang tải...';

    fetch(`/admin/dashboard/revenue-data?start_date=${startDate}&end_date=${endDate}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          updateChartWithData({
            labels: data.labels,
            revenue: data.revenue,
            orders: data.orders,
            summary_revenue: data.summary_revenue,
            summary_orders: data.summary_orders,
            growth: data.growth
          });
        }
      })
      .catch(err => {
        console.error('Lỗi khi tải dữ liệu lịch:', err);
      });
  }

  // Cập nhật dữ liệu vào Chart & Thẻ KPI
  function updateChartWithData(dataset) {
    if (!revenueChartInstance || !dataset) return;

    // Cập nhật số liệu các thẻ KPI
    if (dataset.summary_revenue) document.getElementById('summaryRevenueTxt').textContent = dataset.summary_revenue;
    if (dataset.summary_orders) document.getElementById('summaryOrdersTxt').textContent = dataset.summary_orders;
    if (dataset.growth) document.getElementById('summaryGrowthTxt').innerHTML = `<i class="fa-solid fa-arrow-trend-up me-1"></i> ${dataset.growth}`;

    // Cập nhật biểu đồ
    revenueChartInstance.data.labels = dataset.labels;
    revenueChartInstance.data.datasets[0].data = dataset.revenue;
    if (dataset.orders) {
      revenueChartInstance.data.datasets[1].data = dataset.orders;
      revenueChartInstance.data.datasets[1].hidden = false;
    } else {
      revenueChartInstance.data.datasets[1].hidden = true;
    }
    revenueChartInstance.update();
  }

  document.addEventListener('DOMContentLoaded', () => {
    initRevenueChart();
  });
</script>
@endpush
@endsection



