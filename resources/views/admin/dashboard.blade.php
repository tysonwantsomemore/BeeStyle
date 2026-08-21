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
    <div class="bee-stat-card">
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
        <span class="text-muted small">Tháng này</span>
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
                <td><span class="fw-semibold text-dark">{{ $p->stock }}</span></td>
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
@endsection
