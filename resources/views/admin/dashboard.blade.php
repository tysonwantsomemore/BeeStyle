@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển Tổng Quan | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">HỆ THỐNG TRỰC TUYẾN</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Tổng Quan Hoạt Động Kinh Doanh</h2>
    </div>
    <p class="text-body-tertiary mb-0">Theo dõi doanh thu, đơn hàng, phản hồi khách hàng và tồn kho thời gian thực</p>
  </div>
  <div class="col-auto">
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <a class="btn btn-phoenix-primary" href="{{ route('admin.products.create') }}">
        <span class="fas fa-plus me-1"></span> Thêm Sản Phẩm
      </a>
      <a class="btn btn-phoenix-secondary" href="{{ route('admin.orders.index') }}">
        <span class="fas fa-list-check me-1"></span> Xử Lý Đơn Hàng
      </a>
      <a class="btn btn-phoenix-warning" href="{{ route('admin.reviews.index') }}">
        <span class="fas fa-star me-1"></span> Đánh Giá Mới
      </a>
    </div>
  </div>
</div>

<!-- KPI STAT CARDS -->
<div class="row g-3 mb-4">
  <!-- Revenue -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-2 fw-semibold fs-10" style="letter-spacing: 0.05em;">Doanh Thu Hệ Thống</h6>
            <h3 class="text-body-emphasis mb-1 fw-bold">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</h3>
          </div>
          <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 44px; height: 44px;">
            <span class="fa-solid fa-wallet fs-8"></span>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-translucent">
          <span class="badge badge-phoenix badge-phoenix-success fs-10">
            <span class="fa-solid fa-arrow-trend-up me-1"></span> {{ $stats['revenue_growth'] }}
          </span>
          <a href="{{ route('admin.revenue.monthly') }}" class="fs-9 fw-semibold text-primary text-decoration-none">
            Chi tiết <span class="fas fa-chevron-right ms-1 fs-11"></span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Orders -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-2 fw-semibold fs-10" style="letter-spacing: 0.05em;">Tổng Đơn Hàng</h6>
            <h3 class="text-body-emphasis mb-1 fw-bold">{{ number_format($stats['total_orders']) }}</h3>
          </div>
          <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle" style="width: 44px; height: 44px;">
            <span class="fa-solid fa-cart-shopping fs-8"></span>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-translucent">
          <span class="badge badge-phoenix badge-phoenix-success fs-10">
            <span class="fa-solid fa-arrow-trend-up me-1"></span> {{ $stats['orders_growth'] }}
          </span>
          <a href="{{ route('admin.orders.index') }}" class="fs-9 fw-semibold text-primary text-decoration-none">
            Xem đơn <span class="fas fa-chevron-right ms-1 fs-11"></span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Customers -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-2 fw-semibold fs-10" style="letter-spacing: 0.05em;">Khách Hàng Thành Viên</h6>
            <h3 class="text-body-emphasis mb-1 fw-bold">{{ number_format($stats['total_customers']) }}</h3>
          </div>
          <div class="d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle" style="width: 44px; height: 44px;">
            <span class="fa-solid fa-users fs-8"></span>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-translucent">
          <span class="badge badge-phoenix badge-phoenix-success fs-10">
            <span class="fa-solid fa-arrow-trend-up me-1"></span> {{ $stats['customers_growth'] }}
          </span>
          <a href="{{ route('admin.customers.index') }}" class="fs-9 fw-semibold text-primary text-decoration-none">
            Hồ sơ khách <span class="fas fa-chevron-right ms-1 fs-11"></span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Reviews & Rating -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-2 fw-semibold fs-10" style="letter-spacing: 0.05em;">Đánh Giá &amp; Phản Hồi</h6>
            <h3 class="text-warning mb-1 fw-bold">
              {{ $stats['total_reviews'] }} 
              <span class="fs-8 text-body-emphasis fw-bold">({{ number_format($stats['avg_rating'], 1) }} ⭐)</span>
            </h3>
          </div>
          <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width: 44px; height: 44px;">
            <span class="fa-solid fa-star fs-8"></span>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-translucent">
          <span class="badge badge-phoenix badge-phoenix-info fs-10">
            <span class="fa-solid fa-circle-check me-1"></span> 98% hài lòng
          </span>
          <a href="{{ route('admin.reviews.index') }}" class="fs-9 fw-semibold text-warning text-decoration-none">
            Nhận xét <span class="fas fa-chevron-right ms-1 fs-11"></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BIỂU ĐỒ TĂNG TRƯỞNG DOANH THU & ĐƠN HÀNG -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis">
    <div class="row justify-content-between align-items-center gy-3">
      <div class="col-auto">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-chart-line fs-8"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2">
              <h5 class="fw-bold text-body-emphasis mb-0">Tăng Trưởng Doanh Thu &amp; Đơn Hàng</h5>
              <span class="badge badge-phoenix badge-phoenix-success fs-10">
                <i class="fa-solid fa-circle-dot me-1"></i> TRỰC TIẾP
              </span>
            </div>
            <small class="text-body-tertiary">Dữ liệu phân tích doanh số và quy mô đơn hàng theo thời gian thực</small>
          </div>
        </div>
      </div>

      <!-- Segmented Filter Buttons & Date Range Picker By Calendar -->
      <div class="col-auto">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <!-- Presets -->
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-phoenix-primary active" id="btnChart7Days" onclick="setDateRangePreset('7days')">
              7 Ngày
            </button>
            <button type="button" class="btn btn-sm btn-phoenix-secondary" id="btnChart30Days" onclick="setDateRangePreset('30days')">
              30 Ngày
            </button>
            <button type="button" class="btn btn-sm btn-phoenix-secondary" id="btnChartThisMonth" onclick="setDateRangePreset('this_month')">
              Tháng Này
            </button>
            <button type="button" class="btn btn-sm btn-phoenix-secondary" id="btnChartMonths" onclick="setDateRangePreset('12months')">
              12 Tháng (2026)
            </button>
          </div>

          <!-- Bộ lọc Lịch Tùy Chọn Từ Ngày -> Đến Ngày -->
          <div class="d-flex align-items-center gap-1 bg-body-tertiary p-1 rounded border border-translucent">
            <input type="date" id="chartDateStart" class="form-control form-control-sm bg-body border-0 shadow-none fs-9" style="width: 125px;" value="{{ now()->subDays(6)->format('Y-m-d') }}" title="Từ ngày">
            <span class="text-body-tertiary fs-10 fw-bold px-1">➔</span>
            <input type="date" id="chartDateEnd" class="form-control form-control-sm bg-body border-0 shadow-none fs-9" style="width: 125px;" value="{{ now()->format('Y-m-d') }}" title="Đến ngày">
            <button type="button" class="btn btn-sm btn-primary px-2.5 py-1" onclick="applyCustomDateFilter()" title="Áp dụng lọc">
              <i class="fa-solid fa-filter me-1"></i> Lọc
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card-body">
    <!-- 4 Ô CHỈ SỐ TÀI CHÍNH NỔI KHỐI -->
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="p-3 bg-body-tertiary rounded border border-translucent d-flex align-items-center justify-content-between h-100">
          <div>
            <span class="text-body-tertiary fs-10 fw-semibold d-block text-uppercase">DOANH THU KỲ</span>
            <h4 class="fw-bold text-body-emphasis mb-0 mt-1" id="summaryRevenueTxt">{{ $chartData['seven_days']['summary_revenue'] }}</h4>
            <span class="badge badge-phoenix badge-phoenix-success fs-10 mt-1" id="summaryGrowthTxt">
              <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $chartData['seven_days']['growth'] }}
            </span>
          </div>
          <div class="rounded-circle bg-primary-subtle text-primary p-2 text-center">
            <i class="fa-solid fa-wallet fs-8"></i>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="p-3 bg-body-tertiary rounded border border-translucent d-flex align-items-center justify-content-between h-100">
          <div>
            <span class="text-body-tertiary fs-10 fw-semibold d-block text-uppercase">ĐƠN HÀNG HOÀN TẤT</span>
            <h4 class="fw-bold text-body-emphasis mb-0 mt-1" id="summaryOrdersTxt">{{ $chartData['seven_days']['summary_orders'] }}</h4>
            <small class="text-success fw-semibold fs-10"><i class="fa-solid fa-circle-check me-1"></i> Tỷ lệ giao đạt 98.5%</small>
          </div>
          <div class="rounded-circle bg-success-subtle text-success p-2 text-center">
            <i class="fa-solid fa-box-open fs-8"></i>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="p-3 bg-body-tertiary rounded border border-translucent d-flex align-items-center justify-content-between h-100">
          <div>
            <span class="text-body-tertiary fs-10 fw-semibold d-block text-uppercase">GIÁ TRỊ TB / ĐƠN (AOV)</span>
            <h4 class="fw-bold text-danger mb-0 mt-1">{{ $stats['total_orders'] > 0 ? number_format($stats['total_revenue'] / $stats['total_orders'], 0, ',', '.') : '389.000' }}₫</h4>
            <small class="text-body-tertiary fs-10">Chuẩn thời trang nam cao cấp</small>
          </div>
          <div class="rounded-circle bg-danger-subtle text-danger p-2 text-center">
            <i class="fa-solid fa-tags fs-8"></i>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="p-3 bg-body-tertiary rounded border border-translucent d-flex align-items-center justify-content-between h-100">
          <div>
            <span class="text-body-tertiary fs-10 fw-semibold d-block text-uppercase">TỶ LỆ CHUYỂN ĐỔI</span>
            <h4 class="fw-bold text-info mb-0 mt-1">{{ $stats['conversion_rate'] }}</h4>
            <small class="text-success fw-semibold fs-10"><i class="fa-solid fa-arrow-up me-0.5"></i> +1.2% so với tháng trước</small>
          </div>
          <div class="rounded-circle bg-info-subtle text-info p-2 text-center">
            <i class="fa-solid fa-chart-pie fs-8"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Chart Canvas -->
    <div class="position-relative" style="height: 350px; width: 100%;">
      <canvas id="revenueGrowthChart"></canvas>
    </div>
  </div>
</div>

<!-- KHÁCH HÀNG VỪA ĐÁNH GIÁ MỚI NHẤT -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
        <i class="fa-solid fa-star fs-9"></i>
      </div>
      <h5 class="fw-bold text-body-emphasis mb-0">Đánh Giá Sản Phẩm Mới Nhất</h5>
    </div>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-link btn-sm text-decoration-none fw-semibold p-0">
      Quản lý toàn bộ đánh giá <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2">Khách Hàng</th>
            <th class="py-2">Sản Phẩm Đánh Giá</th>
            <th class="py-2">Số Sao</th>
            <th class="py-2" style="max-width: 320px;">Nội Dung Nhận Xét</th>
            <th class="py-2">Thời Gian</th>
            <th class="py-2">Trạng Thái</th>
            <th class="text-end pe-3 py-2">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($recentReviews as $rev)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <td class="ps-3 py-2">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($rev->user->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $rev->user_name }}" class="rounded-circle border border-translucent" style="width: 34px; height: 34px; object-fit: cover;">
                  <div>
                    <strong class="text-body-emphasis d-block fs-9">{{ $rev->user_name }}</strong>
                    <small class="text-body-tertiary fs-10">{{ $rev->user->email ?? 'Khách' }}</small>
                  </div>
                </div>
              </td>
              <td class="py-2">
                @if($rev->product)
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($rev->product->image) }}" alt="{{ $rev->product->name }}" style="width: 34px; height: 34px; object-fit: cover;" class="rounded border border-translucent bg-body-emphasis">
                    <div>
                      <span class="fw-bold text-body-emphasis text-truncate d-block fs-9" style="max-width: 180px;">{{ $rev->product->name }}</span>
                      <span class="text-danger fw-bold fs-10">{{ number_format($rev->product->price, 0, ',', '.') }}₫</span>
                    </div>
                  </div>
                @endif
              </td>
              <td class="py-2">
                <div class="text-warning text-nowrap fs-10">
                  @for($i=1; $i<=5; $i++)
                    <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-body-tertiary' }}"></i>
                  @endfor
                  <span class="fw-bold text-body-emphasis ms-1">({{ $rev->rating }}/5)</span>
                </div>
              </td>
              <td class="py-2" style="max-width: 320px;">
                <p class="text-body-secondary mb-0 fst-italic text-truncate fs-9">
                  "{{ $rev->comment }}"
                </p>
              </td>
              <td class="py-2"><small class="text-body-tertiary text-nowrap fs-10">{{ $rev->created_at ? $rev->created_at->diffForHumans() : '' }}</small></td>
              <td class="py-2">
                @if($rev->status === 'approved')
                  <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Đã duyệt</span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-eye-slash me-1"></i> Đã ẩn</span>
                @endif
              </td>
              <td class="text-end pe-3 py-2">
                <a href="{{ route('admin.reviews.index', ['q' => $rev->user_name]) }}" class="btn btn-sm btn-phoenix-secondary fs-10 py-1 px-2">
                  <i class="fa-regular fa-eye me-1"></i> Chi Tiết
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-4 text-body-tertiary">Chưa có đánh giá nào từ khách hàng.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- RECENT ORDERS TABLE -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
        <i class="fa-solid fa-receipt fs-9"></i>
      </div>
      <h5 class="fw-bold text-body-emphasis mb-0">Đơn Hàng Mới Nhất Cần Xử Lý</h5>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-link btn-sm text-decoration-none fw-semibold p-0">
      Xem tất cả đơn hàng <i class="fa-solid fa-arrow-right ms-1"></i>
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2">Mã Đơn</th>
            <th class="py-2">Khách Hàng</th>
            <th class="py-2">Sản Phẩm</th>
            <th class="py-2">Tổng Tiền</th>
            <th class="py-2">Phương Thức</th>
            <th class="py-2">Trạng Thái</th>
            <th class="text-end pe-3 py-2">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($orders as $order)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <td class="ps-3 py-2"><span class="font-monospace fw-bold text-primary">{{ $order->order_code }}</span></td>
              <td class="py-2">
                <div class="fw-bold text-body-emphasis fs-9">{{ $order->customer_name }}</div>
                <small class="text-body-tertiary fs-10">{{ $order->customer_phone }}</small>
              </td>
              <td class="py-2"><span class="badge badge-phoenix badge-phoenix-secondary">{{ $order->items->count() }} mặt hàng</span></td>
              <td class="py-2"><strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
              <td class="py-2"><span class="text-body-secondary fs-10">{{ $order->payment_method_name }}</span></td>
              <td class="py-2">
                @if($order->shipping_status === 'completed')
                  <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Hoàn tất</span>
                @elseif($order->shipping_status === 'delivered')
                  <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-box-open me-1"></i> Đã giao</span>
                @elseif($order->shipping_status === 'shipping')
                  <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-truck-fast me-1"></i> Đang giao hàng</span>
                @elseif($order->shipping_status === 'cancelled')
                  <span class="badge badge-phoenix badge-phoenix-danger"><i class="fa-solid fa-xmark me-1"></i> Đã hủy</span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-info"><i class="fa-solid fa-box me-1"></i> {{ $order->status_label }}</span>
                @endif
              </td>
              <td class="text-end pe-3 py-2">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-phoenix-primary fs-10 py-1 px-2.5">
                  Chi Tiết
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-4 text-body-tertiary">Chưa có đơn hàng nào trong hệ thống.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- TOP PRODUCTS & CATEGORIES STATS -->
<div class="row g-4 mb-4">
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
            <i class="fa-solid fa-fire fs-9"></i>
          </div>
          <h5 class="fw-bold text-body-emphasis mb-0">Top Sản Phẩm Bán Chạy Nhất</h5>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-link btn-sm text-decoration-none fw-semibold p-0">
          Quản lý kho <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive scrollbar">
          <table class="table table-sm fs-9 mb-0 align-middle">
            <thead class="bg-body-tertiary text-body-tertiary">
              <tr>
                <th class="ps-3 py-2">Sản phẩm</th>
                <th class="py-2">Giá niêm yết</th>
                <th class="py-2">Đã bán</th>
                <th class="py-2">Tồn kho</th>
                <th class="text-end pe-3 py-2">Trạng thái</th>
              </tr>
            </thead>
            <tbody class="list">
              @foreach($products as $p)
                <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
                  <td class="ps-3 py-2">
                    <div class="d-flex align-items-center gap-2.5">
                      <img src="{{ asset($p->image) }}" alt="{{ $p->name }}" style="width: 38px; height: 38px; object-fit: contain;" class="rounded border border-translucent bg-body-emphasis">
                      <div>
                        <div class="fw-bold text-body-emphasis text-truncate fs-9" style="max-width: 240px;">{{ $p->name }}</div>
                        <small class="text-body-tertiary fs-10">{{ $p->category->name ?? 'Thời trang nam' }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-2"><strong>{{ number_format($p->price, 0, ',', '.') }}₫</strong></td>
                  <td class="py-2"><span class="badge badge-phoenix badge-phoenix-success">{{ $p->sold_count }} đã bán</span></td>
                  <td class="py-2">
                    @if($p->stock <= 5)
                      <span class="badge badge-phoenix badge-phoenix-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i> Còn {{ $p->stock }}</span>
                    @else
                      <span class="fw-semibold text-body-emphasis">{{ $p->stock }} cái</span>
                    @endif
                  </td>
                  <td class="text-end pe-3 py-2"><span class="badge badge-phoenix badge-phoenix-success">Đang bán</span></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex align-items-center gap-2">
        <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
          <i class="fa-solid fa-layer-group fs-9"></i>
        </div>
        <h5 class="fw-bold text-body-emphasis mb-0">Cơ Cấu Danh Mục Áo Nam</h5>
      </div>
      <div class="card-body">
        <div class="d-flex flex-column gap-2">
          @foreach($categories as $c)
            <div class="d-flex align-items-center justify-content-between p-2 rounded bg-body-tertiary border border-translucent">
              <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-body-emphasis border border-translucent d-flex align-items-center justify-content-center text-primary" style="width: 32px; height: 32px;">
                  <i class="{{ $c->icon }}"></i>
                </div>
                <span class="fw-bold text-body-emphasis fs-9">{{ $c->name }}</span>
              </div>
              <span class="badge badge-phoenix badge-phoenix-primary">{{ $c->products_count }} SP</span>
            </div>
          @endforeach
        </div>
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
    
    // Gradient fill
    const goldGradient = chartContext.createLinearGradient(0, 0, 0, 320);
    goldGradient.addColorStop(0, 'rgba(56, 116, 255, 0.25)');
    goldGradient.addColorStop(0.5, 'rgba(56, 116, 255, 0.08)');
    goldGradient.addColorStop(1, 'rgba(56, 116, 255, 0.00)');

    const config = {
      type: 'line',
      data: {
        labels: chartDataRaw.seven_days.labels,
        datasets: [
          {
            label: 'Doanh Thu Thuần (VNĐ)',
            data: chartDataRaw.seven_days.revenue,
            borderColor: '#3874ff',
            backgroundColor: goldGradient,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#3874ff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: '#3874ff',
            pointHoverBorderColor: '#ffffff',
            pointHoverBorderWidth: 2,
            yAxisID: 'y',
          },
          {
            label: 'Số Đơn Hàng Hoàn Tất (Đơn)',
            data: chartDataRaw.seven_days.orders,
            borderColor: '#25b003',
            backgroundColor: 'transparent',
            borderWidth: 2,
            borderDash: [5, 5],
            fill: false,
            tension: 0.4,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#25b003',
            pointBorderWidth: 2,
            pointRadius: 3.5,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: '#25b003',
            pointHoverBorderColor: '#ffffff',
            yAxisID: 'y1',
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
          duration: 700,
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
              padding: 15,
              font: {
                family: "Nunito Sans, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",
                size: 12,
                weight: '600'
              },
              color: '#6b7280'
            }
          },
          tooltip: {
            backgroundColor: 'rgba(15, 23, 42, 0.9)',
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1,
            titleColor: '#ffffff',
            bodyColor: '#f1f5f9',
            padding: 12,
            cornerRadius: 8,
            boxPadding: 4,
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
                size: 11,
                weight: '500'
              },
              color: '#9ca3af'
            }
          },
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            grid: {
              color: 'rgba(226, 232, 240, 0.4)',
              drawBorder: false,
            },
            ticks: {
              font: {
                size: 11,
                weight: '500'
              },
              color: '#9ca3af',
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
                size: 11,
                weight: '500'
              },
              color: '#25b003',
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

  // Hàm chọn nhanh theo Preset
  function setDateRangePreset(preset) {
    const btn7 = document.getElementById('btnChart7Days');
    const btn30 = document.getElementById('btnChart30Days');
    const btnThisMonth = document.getElementById('btnChartThisMonth');
    const btnMonths = document.getElementById('btnChartMonths');
    const dateStartInput = document.getElementById('chartDateStart');
    const dateEndInput = document.getElementById('chartDateEnd');

    // Reset styles
    [btn7, btn30, btnThisMonth, btnMonths].forEach(b => {
      if (b) {
        b.className = 'btn btn-sm btn-phoenix-secondary';
        b.classList.remove('active');
      }
    });

    const today = new Date();
    const formatDate = (d) => d.toISOString().split('T')[0];

    if (preset === '7days') {
      btn7.className = 'btn btn-sm btn-phoenix-primary active';
      const past7 = new Date();
      past7.setDate(today.getDate() - 6);
      dateStartInput.value = formatDate(past7);
      dateEndInput.value = formatDate(today);
      updateChartWithData(chartDataRaw.seven_days);
    } else if (preset === '30days') {
      btn30.className = 'btn btn-sm btn-phoenix-primary active';
      const past30 = new Date();
      past30.setDate(today.getDate() - 29);
      dateStartInput.value = formatDate(past30);
      dateEndInput.value = formatDate(today);
      updateChartWithData(chartDataRaw.thirty_days);
    } else if (preset === 'this_month') {
      btnThisMonth.className = 'btn btn-sm btn-phoenix-primary active';
      const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
      dateStartInput.value = formatDate(firstDay);
      dateEndInput.value = formatDate(today);
      fetchRevenueByDateRange(dateStartInput.value, dateEndInput.value);
    } else if (preset === '12months') {
      btnMonths.className = 'btn btn-sm btn-phoenix-primary active';
      updateChartWithData(chartDataRaw.monthly);
    }
  }

  // Hàm áp dụng lọc theo khoảng ngày Lịch
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
      if (b) {
        b.className = 'btn btn-sm btn-phoenix-secondary';
        b.classList.remove('active');
      }
    });

    fetchRevenueByDateRange(startDate, endDate);
  }

  // Fetch dữ liệu từ API endpoint theo khoảng ngày
  function fetchRevenueByDateRange(startDate, endDate) {
    const summaryRev = document.getElementById('summaryRevenueTxt');
    if (summaryRev) summaryRev.innerHTML = '<span class="spinner-border spinner-border-sm text-primary"></span> Đang tải...';

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

    if (dataset.summary_revenue) document.getElementById('summaryRevenueTxt').textContent = dataset.summary_revenue;
    if (dataset.summary_orders) document.getElementById('summaryOrdersTxt').textContent = dataset.summary_orders;
    if (dataset.growth) document.getElementById('summaryGrowthTxt').innerHTML = `<i class="fa-solid fa-arrow-trend-up me-1"></i> ${dataset.growth}`;

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
