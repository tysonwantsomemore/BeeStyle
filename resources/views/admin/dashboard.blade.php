@extends('layouts.admin')

@section('title', 'Bảng Điều Khiển Tổng Quan | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Tổng Quan Hoạt Động Kinh Doanh</h3>
      <p class="text-muted small mb-0">Theo dõi doanh số, đơn hàng và các chỉ số tăng trưởng thời gian thực</p>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.products.create') }}" class="btn btn-bee-primary btn-sm">
        <i class="fa-solid fa-plus me-1"></i> Thêm Sản Phẩm
      </a>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark btn-sm">
        <i class="fa-solid fa-list-check me-1"></i> Xử Lý Đơn Hàng
      </a>
    </div>
  </div>
</div>

<!-- KPI STAT CARDS -->
<div class="row g-3 mb-4">
  <!-- Revenue -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="text-muted small fw-semibold text-uppercase">Doanh Thu Hệ Thống</span>
          <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</h3>
        </div>
        <div class="bee-stat-icon primary">
          <i class="fa-solid fa-wallet"></i>
        </div>
      </div>
      <div class="d-flex align-items-center text-success small fw-semibold">
        <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $stats['revenue_growth'] }} <span class="text-muted ms-1 font-weight-normal">tăng trưởng tháng này</span>
      </div>
    </div>
  </div>

  <!-- Orders -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="text-muted small fw-semibold text-uppercase">Tổng Đơn Hàng</span>
          <h3 class="fw-bold text-dark mb-0 mt-1">{{ $stats['total_orders'] }}</h3>
        </div>
        <div class="bee-stat-icon success">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
      </div>
      <div class="d-flex align-items-center text-success small fw-semibold">
        <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $stats['orders_growth'] }} <span class="text-muted ms-1 font-weight-normal">đơn mới cập nhật</span>
      </div>
    </div>
  </div>

  <!-- Customers -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="text-muted small fw-semibold text-uppercase">Khách Hàng Đăng Ký</span>
          <h3 class="fw-bold text-dark mb-0 mt-1">{{ $stats['total_customers'] }}</h3>
        </div>
        <div class="bee-stat-icon info">
          <i class="fa-solid fa-users"></i>
        </div>
      </div>
      <div class="d-flex align-items-center text-success small fw-semibold">
        <i class="fa-solid fa-arrow-trend-up me-1"></i> {{ $stats['customers_growth'] }} <span class="text-muted ms-1 font-weight-normal">thành viên VIP</span>
      </div>
    </div>
  </div>

  <!-- Total Products -->
  <div class="col-xl-3 col-md-6">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <span class="text-muted small fw-semibold text-uppercase">Sản Phẩm Trong Kho</span>
          <h3 class="fw-bold text-dark mb-0 mt-1">{{ $stats['total_products'] }}</h3>
        </div>
        <div class="bee-stat-icon danger">
          <i class="fa-solid fa-shirt"></i>
        </div>
      </div>
      <div class="d-flex align-items-center text-success small fw-semibold">
        <i class="fa-solid fa-circle-check me-1"></i> Đang hoạt động <span class="text-muted ms-1 font-weight-normal">trên hệ thống</span>
      </div>
    </div>
  </div>
</div>

<!-- RECENT ORDERS TABLE -->
<div class="bee-table-card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-receipt me-2 text-warning"></i> Đơn Hàng Mới Nhất</h5>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">Xem tất cả đơn hàng</a>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Mã Đơn Hàng</th>
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
              <div class="fw-bold text-dark">{{ $order->customer_name }}</div>
              <small class="text-muted">{{ $order->customer_phone }}</small>
            </td>
            <td><span class="badge bg-light text-dark fw-normal border">{{ $order->items->count() }} mặt hàng</span></td>
            <td><strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
            <td><span class="small text-muted">{{ $order->payment_method_name }}</span></td>
            <td>
              @if($order->shipping_status === 'completed')
                <span class="badge bg-success text-white">Hoàn tất</span>
              @elseif($order->shipping_status === 'shipping')
                <span class="badge bg-warning text-dark">Đang giao hàng</span>
              @elseif($order->shipping_status === 'cancelled')
                <span class="badge bg-danger text-white">Đã hủy</span>
              @else
                <span class="badge bg-info text-white">{{ $order->status_label }}</span>
              @endif
            </td>
            <td>
              <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold">
                Chi Tiết
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-3 text-muted">Chưa có đơn hàng nào trong hệ thống.</td>
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
        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-fire me-2 text-danger"></i> Top Sản Phẩm Trong Cửa Hàng</h5>
        <a href="{{ route('admin.products.index') }}" class="small text-warning text-decoration-none">Quản lý kho</a>
      </div>
      <div class="table-responsive">
        <table class="table mb-0">
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
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($p->image) }}" alt="{{ $p->name }}" style="width: 40px; height: 40px; object-fit: contain;">
                    <div>
                      <div class="fw-bold small text-dark text-truncate" style="max-width: 260px;">{{ $p->name }}</div>
                      <small class="text-muted">{{ $p->category->name ?? 'Thời trang nam' }}</small>
                    </div>
                  </div>
                </td>
                <td><strong>{{ number_format($p->price, 0, ',', '.') }}₫</strong></td>
                <td><span class="badge bg-success-subtle text-success fw-bold">{{ $p->sold_count }}</span></td>
                <td><span class="fw-semibold text-dark">{{ $p->stock }}</span></td>
                <td><span class="badge bg-success">Đang bán</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px;">
      <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-layer-group me-2 text-warning"></i> Cơ Cấu Danh Mục</h5>
      <div class="d-flex flex-column gap-3">
        @foreach($categories as $c)
          <div class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-light">
            <div class="d-flex align-items-center gap-2">
              <i class="{{ $c->icon }} text-warning"></i>
              <span class="small fw-semibold text-dark">{{ $c->name }}</span>
            </div>
            <span class="badge bg-white text-dark border">{{ $c->products_count }} SP</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection
