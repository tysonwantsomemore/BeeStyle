@extends('layouts.admin')

@section('title', 'Báo Cáo & Thống Kê Đơn Hàng | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <nav aria-label="breadcrumb" class="mb-1">
      <ol class="breadcrumb mb-0" style="font-size: 12.5px;">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Trang Chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted">Quản Lý Đơn Hàng</a></li>
        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Báo Cáo &amp; Thống Kê</li>
      </ol>
    </nav>
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-primary fs-10 fw-bold px-2 py-1">FULFILLMENT &amp; ANALYTICS</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Báo Cáo &amp; Thống Kê Đơn Hàng</h2>
    </div>
    <p class="text-body-tertiary mb-0">Theo dõi tiến trình xử lý, tài khoản đặt hàng, đóng gói, vận chuyển và đối soát doanh thu</p>
  </div>
  <div class="col-auto d-flex align-items-center gap-2">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-phoenix-secondary btn-sm px-3">
      <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại Danh Sách Đơn Hàng
    </a>
    <a href="{{ route('admin.orders.export') }}" class="btn btn-phoenix-primary btn-sm px-3 fw-bold">
      <i class="fa-solid fa-file-excel me-1 text-success"></i> Xuất Báo Cáo Excel
    </a>
  </div>
</div>

<!-- 4 THẺ FULFILLMENT KPI METRICS CARDS (EXECUTIVE DASHBOARD) -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng đơn hàng -->
  <div class="col-xl-3 col-md-6 col-12">
    <a href="{{ route('admin.orders.index') }}" class="card border-0 shadow-xs h-100 bg-white p-3 text-decoration-none hover-shadow transition-all" style="border-radius: 16px; border-left: 4px solid #3b82f6 !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Tổng Đơn Hàng</span>
          <h3 class="fw-black text-dark mb-0 mt-1 font-monospace">{{ number_format($statusCounts['all'] ?? 0) }}</h3>
          <small class="text-muted" style="font-size: 0.72rem;">Toàn bộ giao dịch hệ thống</small>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 48px; height: 48px; font-size: 1.25rem;">
          <i class="fa-solid fa-boxes-stacked"></i>
        </div>
      </div>
    </a>
  </div>

  <!-- Thẻ 2: Cần Xử Lý Ngay (Chờ duyệt + Đóng gói) -->
  @php
    $actionRequiredCount = ($statusCounts['pending'] ?? 0) + ($statusCounts['confirmed'] ?? 0) + ($statusCounts['processing'] ?? 0);
  @endphp
  <div class="col-xl-3 col-md-6 col-12">
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="card border-0 shadow-xs h-100 bg-white p-3 text-decoration-none hover-shadow transition-all" style="border-radius: 16px; border-left: 4px solid #f59e0b !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="d-flex align-items-center gap-1.5">
            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Cần Xử Lý Ngay</span>
            @if(($statusCounts['pending'] ?? 0) > 0)
              <span class="badge bg-danger text-white rounded-pill" style="font-size: 0.65rem;">MỚI</span>
            @endif
          </div>
          <h3 class="fw-black text-warning mb-0 mt-1 font-monospace">{{ number_format($actionRequiredCount) }}</h3>
          <small class="text-muted" style="font-size: 0.72rem;">
            Chờ duyệt: <strong>{{ $statusCounts['pending'] ?? 0 }}</strong> • Gói hàng: <strong>{{ $statusCounts['processing'] ?? 0 }}</strong>
          </small>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning" style="width: 48px; height: 48px; font-size: 1.25rem;">
          <i class="fa-solid fa-bell"></i>
        </div>
      </div>
    </a>
  </div>

  <!-- Thẻ 3: Đang Luân Chuyển Bưu Tá -->
  <div class="col-xl-3 col-md-6 col-12">
    <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="card border-0 shadow-xs h-100 bg-white p-3 text-decoration-none hover-shadow transition-all" style="border-radius: 16px; border-left: 4px solid #0284c7 !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Đang Vận Chuyển</span>
          <h3 class="fw-black text-info mb-0 mt-1 font-monospace">{{ number_format($statusCounts['shipping'] ?? 0) }}</h3>
          <small class="text-muted" style="font-size: 0.72rem;">Kiện hàng trên đường bưu tá giao</small>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-info-subtle text-info" style="width: 48px; height: 48px; font-size: 1.25rem;">
          <i class="fa-solid fa-truck-fast"></i>
        </div>
      </div>
    </a>
  </div>

  <!-- Thẻ 4: Giao Thành Công & Hoàn Tất -->
  @php
    $successCount = ($statusCounts['delivered'] ?? 0) + ($statusCounts['completed'] ?? 0);
  @endphp
  <div class="col-xl-3 col-md-6 col-12">
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="card border-0 shadow-xs h-100 bg-white p-3 text-decoration-none hover-shadow transition-all" style="border-radius: 16px; border-left: 4px solid #10b981 !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Giao Thành Công</span>
          <h3 class="fw-black text-success mb-0 mt-1 font-monospace">{{ number_format($successCount) }}</h3>
          <small class="text-muted" style="font-size: 0.72rem;">
            Đã giao: <strong>{{ $statusCounts['delivered'] ?? 0 }}</strong> • Hoàn tất: <strong>{{ $statusCounts['completed'] ?? 0 }}</strong>
          </small>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 48px; height: 48px; font-size: 1.25rem;">
          <i class="fa-solid fa-circle-check"></i>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- DOANH THU & ĐỐI SOÁT TỔNG HỢP -->
<div class="row g-3 mb-4">
  <div class="col-md-4 col-12">
    <div class="card border-0 shadow-xs h-100 bg-white p-4" style="border-radius: 16px;">
      <div class="d-flex align-items-center gap-2 mb-2">
        <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
          <i class="fa-solid fa-coins"></i>
        </div>
        <h6 class="text-muted mb-0 fw-bold fs-10 text-uppercase">Doanh Thu Hôm Nay</h6>
      </div>
      <h3 class="fw-black text-success font-monospace mb-1">{{ number_format($todayRevenue, 0, ',', '.') }}₫</h3>
      <span class="text-muted fs-10"><i class="fa-regular fa-clock me-1"></i> {{ $todayOrders }} đơn phát sinh hôm nay</span>
    </div>
  </div>

  <div class="col-md-4 col-12">
    <div class="card border-0 shadow-xs h-100 bg-white p-4" style="border-radius: 16px;">
      <div class="d-flex align-items-center gap-2 mb-2">
        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
          <i class="fa-solid fa-calendar-days"></i>
        </div>
        <h6 class="text-muted mb-0 fw-bold fs-10 text-uppercase">Doanh Thu Tháng Này</h6>
      </div>
      <h3 class="fw-black text-primary font-monospace mb-1">{{ number_format($thisMonthRevenue, 0, ',', '.') }}₫</h3>
      <span class="text-muted fs-10"><i class="fa-solid fa-cart-shopping me-1"></i> {{ $thisMonthOrders }} đơn trong tháng {{ now()->format('m/Y') }}</span>
    </div>
  </div>

  <div class="col-md-4 col-12">
    <div class="card border-0 shadow-xs h-100 bg-white p-4" style="border-radius: 16px;">
      <div class="d-flex align-items-center gap-2 mb-2">
        <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
          <i class="fa-solid fa-vault"></i>
        </div>
        <h6 class="text-muted mb-0 fw-bold fs-10 text-uppercase">Tổng Doanh Thu Đối Soát</h6>
      </div>
      <h3 class="fw-black text-dark font-monospace mb-1">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h3>
      <span class="text-muted fs-10"><i class="fa-solid fa-circle-check text-success me-1"></i> Tính trên các đơn đã thu tiền / giao thành công</span>
    </div>
  </div>
</div>

<!-- PHÂN TÍCH CHI TIẾT THEO TRẠNG THÁI & ĐỐI TÁC -->
<div class="row g-3 mb-4">
  <!-- Cột 1: Phân bố theo Trạng Thái Đơn Hàng -->
  <div class="col-lg-6 col-12">
    <div class="card border-0 shadow-xs h-100 bg-white p-4" style="border-radius: 16px;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold text-dark mb-0">
          <i class="fa-solid fa-bars-progress text-primary me-2"></i> Tiến Trình Trạng Thái Đơn Hàng
        </h5>
        <span class="badge bg-light text-muted border fs-10">Tổng: {{ number_format($statusCounts['all'] ?? 0) }} đơn</span>
      </div>

      @php
        $totalAll = max(1, $statusCounts['all'] ?? 1);
        $statuses = [
          ['name' => 'Chờ xác nhận', 'code' => 'pending', 'count' => $statusCounts['pending'] ?? 0, 'color' => '#f59e0b', 'badge' => 'bg-warning'],
          ['name' => 'Đã xác nhận', 'code' => 'confirmed', 'count' => $statusCounts['confirmed'] ?? 0, 'color' => '#64748b', 'badge' => 'bg-secondary'],
          ['name' => 'Đang đóng gói', 'code' => 'processing', 'count' => $statusCounts['processing'] ?? 0, 'color' => '#0ea5e9', 'badge' => 'bg-info'],
          ['name' => 'Đang giao hàng', 'code' => 'shipping', 'count' => $statusCounts['shipping'] ?? 0, 'color' => '#3b82f6', 'badge' => 'bg-primary'],
          ['name' => 'Đã giao hàng', 'code' => 'delivered', 'count' => $statusCounts['delivered'] ?? 0, 'color' => '#10b981', 'badge' => 'bg-success'],
          ['name' => 'Hoàn tất thành công', 'code' => 'completed', 'count' => $statusCounts['completed'] ?? 0, 'color' => '#059669', 'badge' => 'bg-success'],
          ['name' => 'Đã hủy đơn', 'code' => 'cancelled', 'count' => $statusCounts['cancelled'] ?? 0, 'color' => '#ef4444', 'badge' => 'bg-danger'],
        ];
      @endphp

      <div class="d-flex flex-column gap-3">
        @foreach($statuses as $st)
          @php
            $pct = round(($st['count'] / $totalAll) * 100, 1);
          @endphp
          <div>
            <div class="d-flex justify-content-between align-items-center mb-1 fs-10">
              <span class="fw-bold text-dark">
                <span class="badge {{ $st['badge'] }} rounded-circle me-1.5 p-1 d-inline-block align-middle" style="width: 8px; height: 8px;"> </span>
                {{ $st['name'] }}
              </span>
              <span>
                <a href="{{ route('admin.orders.index', ['status' => $st['code']]) }}" class="text-decoration-none fw-bold font-monospace text-dark">
                  {{ number_format($st['count']) }} đơn
                </a>
                <span class="text-muted ms-1">({{ $pct }}%)</span>
              </span>
            </div>
            <div class="progress" style="height: 6px; border-radius: 10px; background-color: #f1f5f9;">
              <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%; background-color: {{ $st['color'] }};" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Cột 2: Đối Tác Vận Chuyển & Hình Thức Thanh Toán -->
  <div class="col-lg-6 col-12">
    <div class="d-flex flex-column gap-3 h-100">
      
      <!-- Box 1: Đối tác vận chuyển -->
      <div class="card border-0 shadow-xs bg-white p-4 flex-grow-1" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">
          <i class="fa-solid fa-truck-fast text-info me-2"></i> Đối Tác Vận Chuyển
        </h5>
        @if($carrierStats->isEmpty())
          <div class="text-muted text-center py-3 fs-10">Chưa có thông tin bưu tá vận chuyển</div>
        @else
          <div class="d-flex flex-column gap-2">
            @foreach($carrierStats as $c)
              <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light fs-10">
                <span class="fw-bold text-dark">
                  <i class="fa-solid fa-box text-warning me-1.5"></i>{{ $c->shipping_carrier }}
                </span>
                <span class="badge bg-primary-subtle text-primary font-monospace fw-bold px-2 py-1">
                  {{ number_format($c->total) }} kiện hàng
                </span>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Box 2: Hình thức thanh toán -->
      <div class="card border-0 shadow-xs bg-white p-4 flex-grow-1" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">
          <i class="fa-solid fa-credit-card text-success me-2"></i> Phương Thức Thanh Toán
        </h5>
        <div class="d-flex flex-column gap-2">
          @foreach($paymentStats as $pm)
            @php
              $pmName = match(strtolower($pm->payment_method ?? '')) {
                'cod' => 'Thanh toán khi nhận hàng (COD)',
                'vnpay' => 'Cổng VNPAY (ATM / QR Pay)',
                'momo' => 'Ví MoMo',
                'bank_transfer' => 'Chuyển khoản ngân hàng',
                default => strtoupper($pm->payment_method ?: 'Khác'),
              };
            @endphp
            <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light fs-10">
              <span class="fw-semibold text-dark">
                <i class="fa-solid fa-wallet text-secondary me-1.5"></i>{{ $pmName }}
              </span>
              <div class="text-end">
                <span class="font-monospace fw-bold text-dark">{{ number_format($pm->total) }} đơn</span>
                <span class="text-danger font-monospace fw-bold ms-2">({{ number_format($pm->amount ?? 0, 0, ',', '.') }}₫)</span>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ĐƠN HÀNG MỚI PHÁT SINH GẦN ĐÂY -->
<div class="card border-0 shadow-xs bg-white p-4" style="border-radius: 16px;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-dark mb-0">
      <i class="fa-solid fa-clock-rotate-left text-warning me-2"></i> Giao Dịch Đơn Hàng Gần Đây
    </h5>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm fs-10 fw-bold">
      Xem Toàn Bộ Đơn Hàng <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0 fs-10">
      <thead class="bg-light text-muted">
        <tr>
          <th>Mã Đơn</th>
          <th>Thời Gian</th>
          <th>Khách Hàng</th>
          <th>Giá Trị</th>
          <th>Thanh Toán</th>
          <th>Trạng Thái</th>
          <th class="text-end">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentOrders as $ro)
          <tr>
            <td class="font-monospace fw-bold text-primary">#{{ $ro->order_code }}</td>
            <td class="text-muted">{{ $ro->created_at ? $ro->created_at->format('d/m/Y H:i') : '' }}</td>
            <td class="fw-semibold">{{ $ro->customer_name }}</td>
            <td class="font-monospace fw-bold text-danger">{{ number_format($ro->total_amount, 0, ',', '.') }}₫</td>
            <td>
              <span class="badge {{ $ro->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }}">
                {{ $ro->payment_status_label }}
              </span>
            </td>
            <td>
              <span class="badge bg-light text-dark border">
                {{ $ro->shipping_status_label }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('admin.orders.show', $ro->id) }}" class="btn btn-xs btn-outline-dark fw-bold">
                Chi Tiết <i class="fa-solid fa-chevron-right ms-1"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-3 text-muted">Chưa có giao dịch đơn hàng nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
