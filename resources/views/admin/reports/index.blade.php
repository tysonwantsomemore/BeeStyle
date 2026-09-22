@extends('layouts.admin')

@section('title', 'Báo Cáo & Thống Kê Kinh Doanh Toàn Hệ Thống | BeeStyle Admin')

@section('content')
<!-- BREADCRUMB & HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-auto">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge bg-warning text-dark fs-10 fw-bold px-2 py-1 shadow-xs"><i class="fa-solid fa-chart-pie me-1"></i> BÁO CÁO &amp; PHÂN TÍCH HỆ THỐNG</span>
      <span class="badge bg-primary-subtle text-primary fs-10 fw-bold border border-primary-subtle">THỜI GIAN THỰC</span>
    </div>
    <h2 class="mb-0 text-dark fw-bolder fs-4" style="color: #0f172a !important;">Báo Cáo Hoạt Động &amp; Hiệu Suất Kinh Doanh</h2>
    <p class="text-muted mb-0 fw-medium" style="color: #475569 !important;">Phân tích dòng tiền toàn hệ thống, biến động đơn hàng theo kỳ, top sản phẩm bán chạy và khách hàng VIP</p>
  </div>
  <div class="col-auto d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary text-dark fw-bold bg-white shadow-xs btn-sm px-3">
      <span class="fa-solid fa-arrow-left me-1.5 text-secondary"></span>Bảng Điều Khiển
    </a>
    <a href="{{ route('admin.revenue.monthly') }}" class="btn btn-primary fw-bold shadow-xs btn-sm px-3">
      <span class="fa-solid fa-receipt me-1.5"></span>Chi Tiết Doanh Thu Tháng
    </a>
    <a href="{{ route('admin.reports.inventory') }}" class="btn btn-outline-warning text-dark fw-bold bg-white shadow-xs btn-sm px-3">
      <span class="fa-solid fa-boxes-stacked me-1.5 text-warning"></span>Báo Cáo Tồn Kho
    </a>
    <button type="button" class="btn btn-outline-dark fw-bold bg-white shadow-xs btn-sm px-3" onclick="window.print()">
      <span class="fa-solid fa-print me-1.5"></span>In Báo Cáo
    </button>
  </div>
</div>

<!-- 4 THẺ CHỈ SỐ TOÀN HỆ THỐNG (LIFETIME SYSTEM KPIS) -->
<div class="card border-0 shadow-sm mb-4 bg-white" style="border-radius: 16px;">
  <div class="card-header border-bottom border-translucent bg-light py-2.5 px-3 d-flex justify-content-between align-items-center" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
    <div class="d-flex align-items-center gap-2">
      <i class="fa-solid fa-globe text-primary fs-9"></i>
      <h6 class="fw-bold text-dark mb-0 fs-9" style="color: #0f172a !important;">CHỈ SỐ TỔNG HỢP TOÀN HỆ THỐNG (TỔNG TÍCH LŨY TOÀN THỜI GIAN)</h6>
    </div>
    <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold fs-11">
      <i class="fa-solid fa-circle-check me-1"></i> Dữ liệu đồng bộ tự động
    </span>
  </div>
  <div class="card-body p-3">
    <div class="row g-3">
      <!-- 1. Revenue -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-xs h-100 bg-light" style="border-radius: 14px; border-top: 4px solid #f59e0b !important;">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="text-secondary text-uppercase fw-bold fs-10 tracking-wider d-block mb-1" style="letter-spacing: 0.05em; color: #475569 !important;">DOANH THU HỆ THỐNG</span>
                <h3 class="text-dark mb-1 fw-bolder font-monospace" style="color: #0f172a !important;">{{ number_format($systemStats['total_revenue'], 0, ',', '.') }}₫</h3>
                <small class="text-muted" style="color: #64748b !important;">Tổng tích lũy thực nhận</small>
              </div>
              <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle shadow-xs" style="width: 44px; height: 44px; font-size: 1.15rem;">
                <span class="fa-solid fa-wallet"></span>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-translucent">
              <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold fs-10">
                <span class="fa-solid fa-arrow-trend-up me-1"></span> {{ $systemStats['revenue_growth'] }}
              </span>
              <a href="{{ route('admin.revenue.monthly') }}" class="fs-9 fw-bold text-primary text-decoration-none">
                Chi tiết <span class="fas fa-chevron-right ms-1 fs-11"></span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Orders -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-xs h-100 bg-light" style="border-radius: 14px; border-top: 4px solid #3b82f6 !important;">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="text-secondary text-uppercase fw-bold fs-10 tracking-wider d-block mb-1" style="letter-spacing: 0.05em; color: #475569 !important;">TỔNG ĐƠN HÀNG</span>
                <h3 class="text-dark mb-1 fw-bolder font-monospace" style="color: #0f172a !important;">{{ number_format($systemStats['total_orders']) }}</h3>
                <small class="text-muted" style="color: #64748b !important;">Đơn mua toàn thời gian</small>
              </div>
              <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle shadow-xs" style="width: 44px; height: 44px; font-size: 1.15rem;">
                <span class="fa-solid fa-cart-shopping"></span>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-translucent">
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold fs-10">
                <span class="fa-solid fa-arrow-trend-up me-1"></span> {{ $systemStats['orders_growth'] }}
              </span>
              <a href="{{ route('admin.orders.index') }}" class="fs-9 fw-bold text-primary text-decoration-none">
                Xem đơn <span class="fas fa-chevron-right ms-1 fs-11"></span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. Customers -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-xs h-100 bg-light" style="border-radius: 14px; border-top: 4px solid #06b6d4 !important;">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="text-secondary text-uppercase fw-bold fs-10 tracking-wider d-block mb-1" style="letter-spacing: 0.05em; color: #475569 !important;">KHÁCH HÀNG THÀNH VIÊN</span>
                <h3 class="text-dark mb-1 fw-bolder font-monospace" style="color: #0f172a !important;">{{ number_format($systemStats['total_customers']) }}</h3>
                <small class="text-muted" style="color: #64748b !important;">Tài khoản đăng ký hệ thống</small>
              </div>
              <div class="d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle shadow-xs" style="width: 44px; height: 44px; font-size: 1.15rem;">
                <span class="fa-solid fa-users"></span>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-translucent">
              <span class="badge bg-info-subtle text-info border border-info-subtle fw-bold fs-10">
                <span class="fa-solid fa-arrow-trend-up me-1"></span> {{ $systemStats['customers_growth'] }}
              </span>
              <a href="{{ route('admin.customers.index') }}" class="fs-9 fw-bold text-info text-decoration-none">
                Hồ sơ khách <span class="fas fa-chevron-right ms-1 fs-11"></span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- 4. Reviews & Rating -->
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-xs h-100 bg-light" style="border-radius: 14px; border-top: 4px solid #10b981 !important;">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="text-secondary text-uppercase fw-bold fs-10 tracking-wider d-block mb-1" style="letter-spacing: 0.05em; color: #475569 !important;">ĐÁNH GIÁ &amp; PHẢN HỒI</span>
                <h3 class="text-warning mb-1 fw-bolder font-monospace">
                  {{ $systemStats['total_reviews'] }} 
                  <span class="fs-8 text-dark fw-bold" style="color: #0f172a !important;">({{ number_format($systemStats['avg_rating'], 1) }} ⭐)</span>
                </h3>
                <small class="text-muted" style="color: #64748b !important;">Phản hồi chất lượng sản phẩm</small>
              </div>
              <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle shadow-xs" style="width: 44px; height: 44px; font-size: 1.15rem;">
                <span class="fa-solid fa-star"></span>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-translucent">
              <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold fs-10">
                <span class="fa-solid fa-circle-check me-1"></span> {{ $systemStats['satisfaction_rate'] }} hài lòng
              </span>
              <a href="{{ route('admin.reviews.index') }}" class="fs-9 fw-bold text-success text-decoration-none">
                Nhận xét <span class="fas fa-chevron-right ms-1 fs-11"></span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BỘ LỌC KHOẢNG THỜI GIAN THEO KỲ -->
<div class="card border-0 shadow-sm mb-4 bg-white" style="border-radius: 16px;">
  <div class="card-body p-3">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 align-items-end">
      <div class="col-12 col-md-3">
        <label class="form-label fs-10 fw-bold text-secondary text-uppercase mb-1">Từ ngày</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-light border-end-0 text-primary">
            <span class="fa-regular fa-calendar"></span>
          </span>
          <input type="date" class="form-control border-start-0 fw-bold text-dark" name="from" id="reportInputFrom" value="{{ $from->format('Y-m-d') }}">
        </div>
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label fs-10 fw-bold text-secondary text-uppercase mb-1">Đến ngày</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-light border-end-0 text-primary">
            <span class="fa-regular fa-calendar"></span>
          </span>
          <input type="date" class="form-control border-start-0 fw-bold text-dark" name="to" id="reportInputTo" value="{{ $to->format('Y-m-d') }}">
        </div>
      </div>
      <div class="col-6 col-md-auto">
        <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold shadow-xs">
          <span class="fa-solid fa-filter me-1"></span>Lọc Dữ Liệu
        </button>
      </div>
      <div class="col-6 col-md-auto d-flex gap-1 flex-wrap">
        <button type="button" class="btn btn-outline-secondary btn-sm px-2.5 fw-bold" onclick="setReportPeriod('today')">Hôm nay</button>
        <button type="button" class="btn btn-outline-secondary btn-sm px-2.5 fw-bold" onclick="setReportPeriod('7days')">7 Ngày</button>
        <button type="button" class="btn btn-outline-secondary btn-sm px-2.5 fw-bold" onclick="setReportPeriod('this_month')">Tháng này</button>
        <button type="button" class="btn btn-outline-secondary btn-sm px-2.5 fw-bold" onclick="setReportPeriod('this_year')">Năm 2026</button>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-danger btn-sm px-2.5 fw-bold" title="Đặt lại bộ lọc">
          <span class="fa-solid fa-rotate-left"></span>
        </a>
      </div>
    </form>
  </div>
</div>

<!-- 4 THẺ CHỈ SỐ TRONG KỲ ĐÃ LỌC -->
<div class="row g-3 mb-4">
  <!-- 1. Doanh thu thuần trong kỳ -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px;">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-2 shadow-xs" style="width: 36px; height: 36px;">
              <span class="fa-solid fa-sack-dollar fs-9"></span>
            </div>
            <h6 class="mb-0 text-secondary text-uppercase fw-bold fs-10">DOANH THU KỲ LỌC</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-danger fw-bolder font-monospace">{{ number_format($revenue, 0, ',', '.') }}₫</h3>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent fs-10 text-muted">
          Doanh thu các đơn hợp lệ từ {{ $from->format('d/m/Y') }} – {{ $to->format('d/m/Y') }}
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Tổng đơn hàng trong kỳ -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px;">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-2 shadow-xs" style="width: 36px; height: 36px;">
              <span class="fa-solid fa-cart-shopping fs-9"></span>
            </div>
            <h6 class="mb-0 text-secondary text-uppercase fw-bold fs-10">ĐƠN HÀNG TRONG KỲ</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-dark fw-bolder font-monospace" style="color: #0f172a !important;">{{ number_format($orderCount) }}</h3>
          <span class="fs-10 text-muted fw-semibold">đơn phát sinh</span>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent fs-10 text-success fw-semibold">
          <span class="fa-solid fa-circle-check me-1"></span>{{ $validOrderCount }} đơn hợp lệ đang xử lý / hoàn tất
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Đơn giao thành công -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px;">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center me-2 shadow-xs" style="width: 36px; height: 36px;">
              <span class="fa-solid fa-truck-ramp-box fs-9"></span>
            </div>
            <h6 class="mb-0 text-secondary text-uppercase fw-bold fs-10">ĐƠN HOÀN TẤT / GIAO</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-success fw-bolder font-monospace">{{ number_format($completed) }}</h3>
          <span class="fs-10 text-muted fw-semibold">đơn thành công</span>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent fs-10 text-muted">
          Tỉ lệ hoàn tất đạt <strong class="text-success">{{ $orderCount ? number_format($completed / $orderCount * 100, 1) : 0 }}%</strong> tổng đơn trong kỳ
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Giá trị trung bình đơn (AOV) -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 14px;">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center me-2 shadow-xs" style="width: 36px; height: 36px;">
              <span class="fa-solid fa-tags fs-9"></span>
            </div>
            <h6 class="mb-0 text-secondary text-uppercase fw-bold fs-10">GIÁ TRỊ TB / ĐƠN (AOV)</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-warning fw-bolder font-monospace">
            {{ number_format($validOrderCount ? $revenue / $validOrderCount : 0, 0, ',', '.') }}₫
          </h3>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent fs-10 text-muted">
          Doanh thu trung bình trên mỗi đơn mua thành công
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BIỂU ĐỒ XU HƯỚNG DOANH THU & ĐƠN HÀNG -->
<div class="card border-0 shadow-sm mb-4 bg-white" style="border-radius: 16px;">
  <div class="card-header border-bottom border-translucent bg-light py-3 d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
    <div class="d-flex align-items-center gap-2.5">
      <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 36px; height: 36px;">
        <i class="fa-solid fa-chart-line fs-9"></i>
      </div>
      <div>
        <h5 class="mb-0 text-dark fw-bold fs-8" style="color: #0f172a !important;">Biểu Đồ Xu Hướng Doanh Thu &amp; Đơn Hàng Theo Ngày</h5>
        <small class="text-muted fw-semibold">Khoảng thời gian: {{ $from->format('d/m/Y') }} – {{ $to->format('d/m/Y') }}</small>
      </div>
    </div>
    <div class="d-flex align-items-center gap-3 fs-9">
      <div class="d-flex align-items-center gap-1.5">
        <span class="d-inline-block rounded-circle" style="width: 12px; height: 12px; background: #f59e0b;"></span>
        <span class="text-dark fw-bold">Doanh thu (VNĐ)</span>
      </div>
      <div class="d-flex align-items-center gap-1.5">
        <span class="d-inline-block rounded-circle" style="width: 12px; height: 12px; background: #2563eb;"></span>
        <span class="text-dark fw-bold">Đơn hàng</span>
      </div>
    </div>
  </div>
  <div class="card-body p-3">
    <div style="height: 330px">
      <canvas id="reportChart"></canvas>
    </div>
  </div>
</div>

<!-- 4 KHỐI PHÂN TÍCH CHI TIẾT -->
<div class="row g-4 mb-4">
  <!-- 1. Top sản phẩm bán chạy -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 16px;">
      <div class="card-header border-bottom border-translucent bg-light py-3 d-flex justify-content-between align-items-center" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;">
            <span class="fa-solid fa-fire fs-9"></span>
          </div>
          <div>
            <h5 class="mb-0 text-dark fw-bold fs-8" style="color: #0f172a !important;">Top Sản Phẩm Bán Chạy Trong Kỳ</h5>
            <small class="text-muted fs-11">Bấm cột "Đã bán" để xem danh sách khách mua</small>
          </div>
        </div>
        <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold fs-10">Top {{ $topProducts->count() }}</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive scrollbar">
          <table class="table table-sm fs-9 mb-0 align-middle table-hover">
            <thead style="background-color: #f1f5f9;">
              <tr>
                <th class="ps-3 py-2.5" style="color: #0f172a !important; font-weight: 800;">SẢN PHẨM</th>
                <th class="py-2.5 text-center" style="color: #0f172a !important; font-weight: 800;">
                  ĐÃ BÁN <i class="fa-solid fa-arrow-pointer ms-0.5 text-primary fs-11" title="Bấm vào để xem danh sách khách mua"></i>
                </th>
                <th class="pe-3 py-2.5 text-end" style="color: #0f172a !important; font-weight: 800;">DOANH THU</th>
              </tr>
            </thead>
            <tbody class="list">
              @forelse($topProducts as $index => $product)
                <tr class="border-bottom border-translucent">
                  <td class="ps-3 py-2.5">
                    <div class="d-flex align-items-center gap-2.5">
                      <span class="badge rounded-circle p-1 d-inline-flex align-items-center justify-content-center shadow-xs {{ $index === 0 ? 'bg-warning text-dark' : ($index === 1 ? 'bg-secondary-subtle text-dark' : 'bg-primary-subtle text-primary') }}" style="width: 24px; height: 24px; font-size: 0.7rem; font-weight: 800;">
                        {{ $index + 1 }}
                      </span>
                      @if(!empty($product->product_image))
                        <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" class="rounded border border-translucent bg-light shadow-xs" style="width: 38px; height: 38px; object-fit: contain;">
                      @endif
                      <div>
                        <span class="fw-bold text-dark d-block text-truncate fs-9" style="max-width: 220px; color: #0f172a !important;">{{ $product->product_name }}</span>
                        @if($product->product_id)
                          <small class="text-muted fs-11 font-monospace">ID: #{{ $product->product_id }}</small>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td class="py-2.5 text-center">
                    @if($product->product_id)
                      <button type="button" 
                              class="btn btn-sm btn-subtle-success rounded-pill px-2.5 py-1 fw-bold fs-10 d-inline-flex align-items-center gap-1 shadow-xs border border-success-subtle hover-scale"
                              onclick="openSalesBuyersModal({{ $product->product_id }}, '{{ addslashes($product->product_name) }}')"
                              title="Bấm để xem danh sách khách mua chi tiết">
                        <i class="fa-solid fa-users text-success fs-11"></i>
                        <span>{{ number_format($product->quantity) }} đã bán</span>
                        <i class="fa-solid fa-arrow-up-right-from-square ms-0.5 text-success opacity-75 fs-11"></i>
                      </button>
                    @else
                      <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold fs-10">{{ number_format($product->quantity) }} đã bán</span>
                    @endif
                  </td>
                  <td class="pe-3 py-2.5 text-end text-danger fw-bolder font-monospace fs-9">
                    {{ number_format($product->revenue, 0, ',', '.') }}₫
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted py-4 fw-semibold">Chưa có dữ liệu sản phẩm trong khoảng thời gian này.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Khách hàng mua nhiều nhất -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 16px;">
      <div class="card-header border-bottom border-translucent bg-light py-3 d-flex justify-content-between align-items-center" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;">
            <span class="fa-solid fa-crown fs-9"></span>
          </div>
          <h5 class="mb-0 text-dark fw-bold fs-8" style="color: #0f172a !important;">Top Khách Hàng VIP Chi Tiêu Cao Nhất</h5>
        </div>
        <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-bold fs-10">Top {{ $topCustomers->count() }} VIP</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive scrollbar">
          <table class="table table-sm fs-9 mb-0 align-middle table-hover">
            <thead style="background-color: #f1f5f9;">
              <tr>
                <th class="ps-3 py-2.5" style="color: #0f172a !important; font-weight: 800;">KHÁCH HÀNG</th>
                <th class="py-2.5 text-center" style="color: #0f172a !important; font-weight: 800;">SỐ ĐƠN</th>
                <th class="pe-3 py-2.5 text-end" style="color: #0f172a !important; font-weight: 800;">TỔNG CHI TIÊU</th>
              </tr>
            </thead>
            <tbody class="list">
              @forelse($topCustomers as $index => $customer)
                <tr class="border-bottom border-translucent">
                  <td class="ps-3 py-2.5">
                    <div class="d-flex align-items-center gap-2.5">
                      <span class="badge rounded-circle p-1 d-inline-flex align-items-center justify-content-center shadow-xs {{ $index === 0 ? 'bg-warning text-dark' : 'bg-light text-secondary border border-translucent' }}" style="width: 24px; height: 24px; font-size: 0.7rem; font-weight: 800;">
                        {{ $index + 1 }}
                      </span>
                      <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-10" style="width: 34px; height: 34px; flex-shrink: 0;">
                        {{ $customer->name ? mb_substr($customer->name, 0, 1, 'UTF-8') : 'K' }}
                      </div>
                      <div>
                        <div class="fw-bold text-dark fs-9" style="color: #0f172a !important;">{{ $customer->name }}</div>
                        <small class="text-muted fs-11">{{ $customer->email ?: ($customer->phone ?: 'Khách hàng thân thiết') }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-2.5 text-center">
                    <span class="badge bg-info-subtle text-info border border-info-subtle fw-bold font-monospace">{{ $customer->orders_count }} đơn</span>
                  </td>
                  <td class="pe-3 py-2.5 text-end text-danger fw-bolder font-monospace fs-9">
                    {{ number_format($customer->spent, 0, ',', '.') }}₫
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted py-4 fw-semibold">Chưa có dữ liệu khách hàng trong khoảng thời gian này.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Phân bố trạng thái đơn hàng -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 16px;">
      <div class="card-header border-bottom border-translucent bg-light py-3 d-flex justify-content-between align-items-center" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;">
            <span class="fa-solid fa-boxes-packing fs-9"></span>
          </div>
          <h5 class="mb-0 text-dark fw-bold fs-8" style="color: #0f172a !important;">Phân Bố Trạng Thái Đơn Hàng Trong Kỳ</h5>
        </div>
        <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle fw-bold fs-10 font-monospace">{{ $orderCount }} đơn</span>
      </div>
      <div class="card-body p-3">
        @php
          $statusNames = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang chuẩn bị',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'completed' => 'Hoàn tất thành công',
            'cancelled' => 'Đã hủy đơn'
          ];
          $statusColors = [
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'processing' => 'bg-primary',
            'shipping' => 'bg-info',
            'delivered' => 'bg-success',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger'
          ];
        @endphp
        @forelse($statusBreakdown as $key => $total)
          @php
            $pctStatus = $orderCount > 0 ? round(($total / $orderCount) * 100, 1) : 0;
          @endphp
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center fs-9 mb-1">
              <span class="fw-bold text-dark" style="color: #0f172a !important;">{{ $statusNames[$key] ?? $key }}</span>
              <span class="text-muted"><strong class="text-dark font-monospace">{{ $total }}</strong> đơn ({{ $pctStatus }}%)</span>
            </div>
            <div class="progress" style="height: 7px; border-radius: 4px;">
              <div class="progress-bar {{ $statusColors[$key] ?? 'bg-secondary' }}" role="progressbar" style="width: {{ $pctStatus }}%"></div>
            </div>
          </div>
        @empty
          <p class="text-muted mb-0 text-center py-4 fw-semibold">Chưa có dữ liệu trạng thái đơn hàng trong kỳ này.</p>
        @endforelse
      </div>
    </div>
  </div>

  <!-- 4. Doanh thu theo hình thức thanh toán -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 16px;">
      <div class="card-header border-bottom border-translucent bg-light py-3 d-flex justify-content-between align-items-center" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 32px; height: 32px;">
            <span class="fa-solid fa-credit-card fs-9"></span>
          </div>
          <h5 class="mb-0 text-dark fw-bold fs-8" style="color: #0f172a !important;">Doanh Thu Theo Phương Thức Thanh Toán</h5>
        </div>
        <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold fs-10">Thanh toán</span>
      </div>
      <div class="card-body p-3">
        @php
          $paymentNames = [
            'cod' => 'Tiền mặt khi nhận hàng (COD)',
            'vietqr' => 'Chuyển khoản Ngân hàng (VietQR)',
            'momo' => 'Ví điện tử MoMo',
            'vnpay' => 'Cổng thanh toán VNPAY',
            'zalopay' => 'Ví điện tử ZaloPay'
          ];
          $paymentIcons = [
            'cod' => 'fa-solid fa-hand-holding-dollar text-warning',
            'vietqr' => 'fa-solid fa-qrcode text-primary',
            'momo' => 'fa-solid fa-wallet text-danger',
            'vnpay' => 'fa-solid fa-credit-card text-info',
            'zalopay' => 'fa-solid fa-money-bill text-primary'
          ];
        @endphp
        @forelse($paymentBreakdown as $key => $total)
          @php
            $pctPay = $revenue > 0 ? round(($total / $revenue) * 100, 1) : 0;
          @endphp
          <div class="p-2.5 bg-light rounded-3 border border-translucent mb-2.5 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2.5">
              <span class="{{ $paymentIcons[$key] ?? 'fa-solid fa-money-bill text-secondary' }} fs-8"></span>
              <div>
                <strong class="text-dark d-block fs-9" style="color: #0f172a !important;">{{ $paymentNames[$key] ?? strtoupper($key) }}</strong>
                <small class="text-muted fs-11">Chiếm {{ $pctPay }}% tổng doanh thu kỳ</small>
              </div>
            </div>
            <div class="text-end">
              <strong class="text-danger fs-8 font-monospace">{{ number_format($total, 0, ',', '.') }}₫</strong>
            </div>
          </div>
        @empty
          <p class="text-muted mb-0 text-center py-4 fw-semibold">Chưa có dữ liệu thanh toán trong khoảng thời gian này.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

@include('admin.partials.sales-buyers-modal')

@push('scripts')
@include('admin.partials.sales-buyers-modal-script')
<script>
  function setReportPeriod(preset) {
    const fromInput = document.getElementById('reportInputFrom');
    const toInput = document.getElementById('reportInputTo');
    const today = new Date();
    const formatDate = (d) => d.toISOString().split('T')[0];

    toInput.value = formatDate(today);

    if (preset === 'today') {
      fromInput.value = formatDate(today);
    } else if (preset === '7days') {
      const past7 = new Date();
      past7.setDate(today.getDate() - 6);
      fromInput.value = formatDate(past7);
    } else if (preset === 'this_month') {
      const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
      fromInput.value = formatDate(firstDay);
    } else if (preset === 'this_year') {
      const firstDayYear = new Date(today.getFullYear(), 0, 1);
      fromInput.value = formatDate(firstDayYear);
    }

    fromInput.closest('form').submit();
  }

  document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.getElementById('reportChart');
    if (chartEl) {
      new Chart(chartEl, {
        type: 'line',
        data: {
          labels: @json($labels),
          datasets: [
            {
              label: 'Doanh thu thuần (VNĐ)',
              data: @json($revenueSeries),
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.12)',
              fill: true,
              tension: 0.35,
              yAxisID: 'y',
              pointRadius: 4,
              pointHoverRadius: 6,
              pointBackgroundColor: '#f59e0b',
            },
            {
              label: 'Đơn hàng (Đơn)',
              data: @json($orderSeries),
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37, 99, 235, 0.08)',
              tension: 0.35,
              yAxisID: 'y1',
              pointRadius: 4,
              pointHoverRadius: 6,
              pointBackgroundColor: '#2563eb',
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false
          },
          plugins: {
            legend: {
              display: true,
              position: 'top',
              align: 'end',
              labels: {
                usePointStyle: true,
                pointStyle: 'circle',
                boxWidth: 8,
                padding: 15,
                font: {
                  weight: '700'
                }
              }
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  if (context.datasetIndex === 0) {
                    return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + '₫';
                  } else {
                    return 'Đơn hàng: ' + context.parsed.y + ' đơn';
                  }
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              },
              ticks: {
                font: {
                  weight: '600'
                }
              }
            },
            y: {
              beginAtZero: true,
              ticks: {
                font: {
                  weight: '600'
                },
                callback: function (value) {
                  if (value >= 1000000) return (value / 1000000) + 'Tr';
                  if (value >= 1000) return (value / 1000) + 'k';
                  return value;
                }
              }
            },
            y1: {
              beginAtZero: true,
              position: 'right',
              grid: {
                drawOnChartArea: false
              },
              ticks: {
                precision: 0,
                font: {
                  weight: '600'
                },
                callback: function (val) {
                  return val + ' đơn';
                }
              }
            }
          }
        }
      });
    }
  });
</script>
@endpush
@endsection
