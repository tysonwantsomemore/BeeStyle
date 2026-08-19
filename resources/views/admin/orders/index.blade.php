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
    <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm theo mã đơn hoặc SĐT..." style="width: 250px;">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 180px;">
        <option value="">Tất cả trạng thái</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang đóng gói</option>
        <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn tất</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
      </select>
      <button type="submit" class="btn btn-sm btn-outline-secondary">Lọc</button>
      @if(request('q') || request('status'))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>
    <div class="text-muted small">
      Tổng số: <strong>{{ $orders->total() }}</strong> đơn hàng
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
          <th>Tiến Trình</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <td>
              <a href="{{ route('admin.orders.show', $order->id) }}" class="font-monospace fw-bold text-primary text-decoration-none">
                {{ $order->order_code }}
              </a>
            </td>
            <td><small class="text-muted">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</small></td>
            <td>
              <div class="fw-bold text-dark">{{ $order->customer_name }}</div>
              <small class="text-muted">{{ $order->customer_phone }}</small>
            </td>
            <td><span class="badge bg-light text-dark border">{{ $order->items->count() }} SP</span></td>
            <td><strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
            <td>
              <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }}">
                {{ $order->payment_status_label }}
              </span>
              <div class="fs-10 text-muted">{{ $order->payment_method_name }}</div>
            </td>
            <td>
              @if($order->shipping_status === 'completed')
                <span class="badge bg-success">Bước 6/6: Hoàn tất</span>
              @elseif($order->shipping_status === 'shipping')
                <span class="badge bg-warning text-dark">Bước 4/6: Đang giao</span>
              @elseif($order->shipping_status === 'processing')
                <span class="badge bg-info text-white">Bước 3/6: Đang đóng gói</span>
              @elseif($order->shipping_status === 'confirmed')
                <span class="badge bg-secondary">Bước 2/6: Đã xác nhận</span>
              @elseif($order->shipping_status === 'cancelled')
                <span class="badge bg-danger">Đã hủy đơn</span>
              @else
                <span class="badge bg-warning-subtle text-dark">Bước 1/6: Chờ xác nhận</span>
              @endif
            </td>
            <td class="text-end">
              <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold">
                Xử Lý Đơn <i class="fa-solid fa-chevron-right ms-1"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">Không tìm thấy đơn hàng nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($orders->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $orders->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
