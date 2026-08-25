@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">GIAO DỊCH</span>
        <h3 class="fw-bold text-dark mb-0">Quản Lý Đơn Hàng &amp; Vận Chuyển</h3>
      </div>
      <p class="text-muted small mb-0">Theo dõi tiến trình xử lý, tài khoản đặt hàng, đóng gói, vận chuyển và đối soát doanh thu đơn hàng</p>
    </div>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm theo mã đơn, tài khoản, SĐT..." style="width: 280px;">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 190px;">
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
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Mã Đơn Hàng</th>
          <th>Thời Gian</th>
          <th>Tài Khoản Đặt Hàng</th>
          <th>Người Nhận Hàng</th>
          <th>Sản Phẩm</th>
          <th>Tổng Giá Trị</th>
          <th>Thanh Toán</th>
          <th>Tiến Trình Giao Hàng</th>
          <th class="text-end">Thao Tác</th>
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
            <td><small class="text-muted text-nowrap">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</small></td>
            
            <!-- CỘT TÀI KHOẢN ĐẶT HÀNG -->
            <td>
              @if($order->user)
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.82rem;">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                  </div>
                  <div>
                    <a href="{{ route('admin.customers.show', $order->user->id) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block" style="font-size: 0.84rem;">
                      {{ $order->user->name }}
                    </a>
                    <small class="text-muted d-block text-truncate" style="max-width: 150px; font-size: 0.74rem;">
                      <i class="fa-regular fa-envelope me-1"></i>{{ $order->user->email }}
                    </small>
                    <span class="badge bg-primary-subtle text-primary fw-semibold" style="font-size: 0.68rem;">
                      <i class="fa-solid fa-user-check me-0.5"></i> Thành viên #{{ $order->user->id }}
                    </span>
                  </div>
                </div>
              @else
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.82rem;">
                    <i class="fa-solid fa-user-slash"></i>
                  </div>
                  <div>
                    <span class="badge bg-secondary-subtle text-muted fw-bold" style="font-size: 0.72rem;">
                      <i class="fa-solid fa-user-clock me-0.5"></i> Khách Vãng Lai
                    </span>
                    <small class="text-muted d-block" style="font-size: 0.72rem;">(Chưa đăng nhập)</small>
                  </div>
                </div>
              @endif
            </td>

            <!-- CỘT NGƯỜI NHẬN HÀNG -->
            <td>
              <div class="fw-bold text-dark small">{{ $order->customer_name }}</div>
              <div class="text-muted small" style="font-size: 0.76rem;">
                <i class="fa-solid fa-phone me-1 text-secondary"></i>{{ $order->customer_phone }}
              </div>
              @if($order->shipping_address)
                <small class="text-muted text-truncate d-block" style="max-width: 160px; font-size: 0.72rem;" title="{{ $order->shipping_address }}">
                  <i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $order->shipping_address }}
                </small>
              @endif
            </td>

            <td><span class="badge bg-light text-dark border px-2 py-1">{{ $order->items->count() }} sản phẩm</span></td>
            <td><strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
            <td>
              <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }} py-1 px-2 fw-bold">
                {{ $order->payment_status_label }}
              </span>
              <div class="text-muted" style="font-size: 0.72rem;">{{ $order->payment_method_name }}</div>
            </td>
            <td>
              @if($order->shipping_status === 'completed')
                <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Bước 6/6: Hoàn tất</span>
              @elseif($order->shipping_status === 'delivered')
                <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold"><i class="fa-solid fa-box-open me-1"></i> Bước 5/6: Đã giao</span>
              @elseif($order->shipping_status === 'shipping')
                <span class="badge bg-warning-subtle text-dark py-1 px-2 fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> Bước 4/6: Đang giao</span>
              @elseif($order->shipping_status === 'processing')
                <span class="badge bg-info-subtle text-info py-1 px-2 fw-bold"><i class="fa-solid fa-boxes-packing me-1"></i> Bước 3/6: Đóng gói</span>
              @elseif($order->shipping_status === 'confirmed')
                <span class="badge bg-secondary-subtle text-dark py-1 px-2 fw-bold"><i class="fa-solid fa-clipboard-check me-1"></i> Bước 2/6: Đã xác nhận</span>
              @elseif($order->shipping_status === 'cancelled')
                <span class="badge bg-danger-subtle text-danger py-1 px-2 fw-bold"><i class="fa-solid fa-ban me-1"></i> Đã hủy đơn</span>
              @else
                <span class="badge bg-warning-subtle text-dark py-1 px-2 fw-bold"><i class="fa-solid fa-clock me-1"></i> Bước 1/6: Chờ xác nhận</span>
              @endif
            </td>
            <td class="text-end">
              <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold py-1 px-2.5" style="font-size: 0.75rem;">
                Xử Lý Đơn <i class="fa-solid fa-chevron-right ms-1"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center py-5 text-muted">
              <i class="fa-solid fa-cart-shopping fs-2 text-muted mb-2 d-block"></i>
              Không tìm thấy đơn hàng nào.
            </td>
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
