@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Quản Lý Đơn Hàng</h3>
      <p class="text-muted small mb-0">Theo dõi tiến trình xử lý, vận chuyển và doanh thu từ các đơn hàng</p>
    </div>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-2">
      <input type="text" class="form-control form-control-sm" placeholder="Tìm theo mã đơn hoặc SĐT..." style="width: 250px;">
      <select class="form-select form-select-sm" style="width: 180px;">
        <option value="">Tất cả trạng thái</option>
        <option value="1">Chờ xác nhận</option>
        <option value="2">Đã xác nhận</option>
        <option value="3">Đang đóng gói</option>
        <option value="4">Đang giao hàng</option>
        <option value="6">Hoàn tất</option>
      </select>
    </div>
    <div class="text-muted small">
      Tổng số: <strong>{{ count($orders) }}</strong> đơn hàng
    </div>
  </div>

  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Mã Đơn</th>
          <th>Thời Gian</th>
          <th>Khách Hàng</th>
          <th>Số Mặt Hàng</th>
          <th>Tổng Giá Trị</th>
          <th>Thanh Toán</th>
          <th>Tiến Trình (6 Bước)</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
          <tr>
            <td>
              <a href="{{ route('admin.orders.show', $order['id']) }}" class="font-monospace fw-bold text-primary text-decoration-none">
                {{ $order['order_code'] }}
              </a>
            </td>
            <td><small class="text-muted">{{ $order['created_at'] }}</small></td>
            <td>
              <div class="fw-bold text-dark">{{ $order['customer_name'] }}</div>
              <small class="text-muted">{{ $order['customer_phone'] }}</small>
            </td>
            <td><span class="badge bg-light text-dark border">{{ $order['items_count'] }} SP</span></td>
            <td><strong class="text-danger">{{ number_format($order['total_amount'], 0, ',', '.') }}₫</strong></td>
            <td>
              <span class="badge {{ $order['payment_status'] === 'Đã thanh toán' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }}">
                {{ $order['payment_status'] }}
              </span>
              <div class="fs-10 text-muted">{{ $order['payment_method'] }}</div>
            </td>
            <td>
              @if($order['status_step'] == 6)
                <span class="badge bg-success">Bước 6/6: Hoàn tất</span>
              @elseif($order['status_step'] == 4)
                <span class="badge bg-warning text-dark">Bước 4/6: Đang giao</span>
              @elseif($order['status_step'] == 3)
                <span class="badge bg-info text-white">Bước 3/6: Đang đóng gói</span>
              @else
                <span class="badge bg-secondary">Bước 2/6: Đã xác nhận</span>
              @endif
            </td>
            <td class="text-end">
              <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold">
                Xử Lý Đơn <i class="fa-solid fa-chevron-right ms-1"></i>
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
