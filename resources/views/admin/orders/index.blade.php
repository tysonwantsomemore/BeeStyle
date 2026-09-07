@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">GIAO DỊCH &amp; VẬN CHUYỂN</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Đơn Hàng</h2>
    </div>
    <p class="text-body-tertiary mb-0">Theo dõi tiến trình xử lý, tài khoản đặt hàng, đóng gói, vận chuyển và đối soát doanh thu</p>
  </div>
</div>

<!-- ORDERS TABLE CARD -->
<div class="card border-0 shadow-sm mb-4">
  <!-- FILTER TOOLBAR -->
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <div class="position-relative">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm search-input" placeholder="Tìm mã đơn, tài khoản, SĐT..." style="width: 240px;">
      </div>
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
      <button type="submit" class="btn btn-sm btn-phoenix-secondary">Lọc</button>
      @if(request('q') || request('status'))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>
    <div class="text-body-tertiary fs-10">
      Tổng số: <strong class="text-body-emphasis">{{ $orders->total() }}</strong> đơn hàng
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2">Mã Đơn Hàng</th>
            <th class="py-2">Thời Gian</th>
            <th class="py-2">Tài Khoản Đặt</th>
            <th class="py-2">Người Nhận Hàng</th>
            <th class="py-2">Sản Phẩm</th>
            <th class="py-2">Tổng Tiền</th>
            <th class="py-2">Thanh Toán</th>
            <th class="py-2">Tiến Trình Giao Hàng</th>
            <th class="text-end pe-3 py-2">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($orders as $order)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <td class="ps-3 py-2">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-monospace fw-bold text-primary text-decoration-none">
                  {{ $order->order_code }}
                </a>
              </td>
              <td class="py-2"><small class="text-body-tertiary text-nowrap fs-10">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</small></td>
              
              <!-- TÀI KHOẢN ĐẶT HÀNG -->
              <td class="py-2">
                @if($order->user)
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-10 flex-shrink-0" style="width: 32px; height: 32px;">
                      {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div>
                      <a href="{{ route('admin.customers.show', $order->user->id) }}" class="fw-bold text-body-emphasis text-decoration-none d-block fs-9">
                        {{ $order->user->name }}
                      </a>
                      <small class="text-body-tertiary d-block text-truncate fs-10" style="max-width: 140px;">
                        {{ $order->user->email }}
                      </small>
                      <span class="badge badge-phoenix badge-phoenix-primary fs-11">
                        Thành viên #{{ $order->user->id }}
                      </span>
                    </div>
                  </div>
                @else
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-body-tertiary text-body-tertiary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                      <i class="fa-solid fa-user-slash fs-10"></i>
                    </div>
                    <div>
                      <span class="badge badge-phoenix badge-phoenix-secondary fs-10">
                        Khách Vãng Lai
                      </span>
                    </div>
                  </div>
                @endif
              </td>

              <!-- NGƯỜI NHẬN HÀNG -->
              <td class="py-2">
                <div class="fw-bold text-body-emphasis fs-9">{{ $order->customer_name }}</div>
                <div class="text-body-tertiary fs-10">
                  <i class="fa-solid fa-phone me-1"></i>{{ $order->customer_phone }}
                </div>
                @if($order->shipping_address)
                  <small class="text-body-tertiary text-truncate d-block fs-10" style="max-width: 160px;" title="{{ $order->shipping_address }}">
                    <i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $order->shipping_address }}
                  </small>
                @endif
              </td>

              <td class="py-2"><span class="badge badge-phoenix badge-phoenix-secondary">{{ $order->items->count() }} món</span></td>
              <td class="py-2"><strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
              <td class="py-2">
                <span class="badge badge-phoenix {{ $order->payment_status === 'paid' ? 'badge-phoenix-success' : 'badge-phoenix-warning' }}">
                  {{ $order->payment_status_label }}
                </span>
                <div class="text-body-tertiary fs-10">{{ $order->payment_method_name }}</div>
              </td>
              <td class="py-2">
                @if($order->shipping_status === 'completed')
                  <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Bước 6/6: Hoàn tất</span>
                @elseif($order->shipping_status === 'delivered')
                  <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-box-open me-1"></i> Bước 5/6: Đã giao</span>
                @elseif($order->shipping_status === 'shipping')
                  <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-truck-fast me-1"></i> Bước 4/6: Đang giao</span>
                @elseif($order->shipping_status === 'processing')
                  <span class="badge badge-phoenix badge-phoenix-info"><i class="fa-solid fa-boxes-packing me-1"></i> Bước 3/6: Đóng gói</span>
                @elseif($order->shipping_status === 'confirmed')
                  <span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-clipboard-check me-1"></i> Bước 2/6: Đã xác nhận</span>
                @elseif($order->shipping_status === 'cancelled')
                  <span class="badge badge-phoenix badge-phoenix-danger"><i class="fa-solid fa-ban me-1"></i> Đã hủy</span>
                  @if($order->cancel_reason)
                    <small class="text-body-tertiary d-block text-truncate fs-10" style="max-width: 140px;" title="{{ $order->cancel_reason }}">
                      {{ $order->cancel_reason }}
                    </small>
                  @endif
                @else
                  <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-clock me-1"></i> Bước 1/6: Chờ duyệt</span>
                @endif

                @if($order->latestReturn)
                  <div class="mt-1">
                    <a href="{{ route('admin.returns.show', $order->latestReturn->id) }}" class="badge badge-phoenix badge-phoenix-warning text-decoration-none fs-11">
                      <i class="fa-solid fa-arrow-rotate-left me-1"></i> RMA: {{ $order->latestReturn->status_label }}
                    </a>
                  </div>
                @endif
              </td>
              <td class="text-end pe-3 py-2">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10">
                  Xử Lý Đơn <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-cart-shopping fs-4 text-body-tertiary mb-2 d-block"></i>
                Không tìm thấy đơn hàng nào.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($orders->hasPages())
    <div class="card-footer d-flex justify-content-center py-3 bg-body-emphasis border-top border-translucent">
      {{ $orders->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
