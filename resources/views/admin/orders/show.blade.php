@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng ' . $order->order_code . ' | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <h3 class="fw-bold text-dark mb-0">Đơn Hàng: <span class="font-monospace text-primary">{{ $order->order_code }}</span></h3>
        <span class="badge bg-warning text-dark px-3 py-1 fw-bold">{{ $order->status_label }}</span>
      </div>
      <p class="text-muted small mb-0">
        Thời gian tạo đơn: {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }} • Phương thức: {{ $order->payment_method_name }}
      </p>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
        <i class="fa-solid fa-print me-1"></i> In Hóa Đơn
      </button>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại
      </a>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- LEFT: ORDER ITEMS & TIMELINE -->
  <div class="col-lg-8">
    
    <!-- STATUS & PAYMENT UPDATE FORM -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
      <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-route me-2 text-warning"></i> Cập Nhật Trạng Thái Đơn Hàng</h5>
      
      <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
        @csrf
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Trạng thái vận chuyển:</label>
            <select name="shipping_status" class="form-select">
              <option value="pending" {{ $order->shipping_status === 'pending' ? 'selected' : '' }}>1. Chờ xác nhận đơn hàng</option>
              <option value="confirmed" {{ $order->shipping_status === 'confirmed' ? 'selected' : '' }}>2. Đã xác nhận thông tin</option>
              <option value="processing" {{ $order->shipping_status === 'processing' ? 'selected' : '' }}>3. Đang đóng gói bưu phẩm</option>
              <option value="shipping" {{ $order->shipping_status === 'shipping' ? 'selected' : '' }}>4. Đang giao hàng bưu tá</option>
              <option value="delivered" {{ $order->shipping_status === 'delivered' ? 'selected' : '' }}>5. Đã giao tới người nhận</option>
              <option value="completed" {{ $order->shipping_status === 'completed' ? 'selected' : '' }}>6. Hoàn tất đơn hàng</option>
              <option value="cancelled" {{ $order->shipping_status === 'cancelled' ? 'selected' : '' }}>0. Hủy đơn hàng</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label small fw-semibold">Trạng thái thanh toán:</label>
            <select name="payment_status" class="form-select">
              <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
              <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
              <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Ghi chú nội bộ admin:</label>
          <input type="text" name="admin_notes" class="form-control form-control-sm" value="{{ $order->admin_notes }}" placeholder="Ghi chú về mã vận đơn bưu tá, đơn vị giao hàng ViettelPost/GHTK...">
        </div>

        <button type="submit" class="btn btn-bee-primary btn-sm px-4">
          <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật Trạng Thái
        </button>
      </form>
    </div>

    <!-- ITEMS TABLE -->
    <div class="bee-table-card mb-4">
      <div class="card-header">
        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-box-open me-2 text-warning"></i> Danh Sách Sản Phẩm Đặt Mua ({{ $order->items->count() }})</h5>
      </div>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead>
            <tr>
              <th>Sản Phẩm</th>
              <th>Mã SKU</th>
              <th>Đơn Giá</th>
              <th>Số Lượng</th>
              <th class="text-end">Thành Tiền</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 45px; height: 45px; object-fit: contain;" class="border rounded bg-light">
                    <div>
                      <div class="fw-bold small text-dark">{{ $item->product_name }}</div>
                      <small class="text-muted">Màu: {{ $item->color ?? 'Tiêu chuẩn' }} | Size: {{ $item->size ?? 'M' }}</small>
                    </div>
                  </div>
                </td>
                <td><span class="font-monospace small text-muted">{{ $item->product_sku ?? 'BS-PROD' }}</span></td>
                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                <td><span class="badge bg-light text-dark border">x{{ $item->quantity }}</span></td>
                <td class="text-end fw-bold text-dark">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="table-light">
            <tr>
              <td colspan="4" class="text-end fw-bold">Tạm tính:</td>
              <td class="text-end fw-bold">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
            </tr>
            @if($order->discount_amount > 0)
              <tr>
                <td colspan="4" class="text-end fw-bold text-success">Giảm giá voucher ({{ $order->coupon_code }}):</td>
                <td class="text-end text-success fw-bold">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
              </tr>
            @endif
            <tr>
              <td colspan="4" class="text-end fw-bold">Phí vận chuyển:</td>
              <td class="text-end text-success fw-bold">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . '₫' : 'Miễn phí (0₫)' }}</td>
            </tr>
            <tr>
              <td colspan="4" class="text-end fw-bold text-dark fs-6">Tổng Thanh Toán:</td>
              <td class="text-end fw-bold text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

  </div>

  <!-- RIGHT: CUSTOMER INFO -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
      <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user me-2 text-warning"></i> Thông Tin Người Nhận</h5>
      
      <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
        <div class="avatar avatar-l bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center fs-4" style="width: 45px; height: 45px;">
          <i class="fa-solid fa-user"></i>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-0">{{ $order->customer_name }}</h6>
          <small class="text-muted">{{ $order->customer_email ?? 'Chưa cung cấp email' }}</small>
        </div>
      </div>

      <div class="d-flex flex-column gap-2 small">
        <div>
          <strong class="text-dark d-block mb-1">Số điện thoại:</strong>
          <span class="text-muted">{{ $order->customer_phone }}</span>
        </div>
        <div>
          <strong class="text-dark d-block mb-1">Địa chỉ giao hàng:</strong>
          <span class="text-muted">{{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}</span>
        </div>
        <div>
          <strong class="text-dark d-block mb-1">Phương thức thanh toán:</strong>
          <span class="text-muted">{{ $order->payment_method_name }}</span>
        </div>
        <div>
          <strong class="text-dark d-block mb-1">Trạng thái thanh toán:</strong>
          <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
            {{ $order->payment_status_label }}
          </span>
        </div>
        @if($order->notes)
          <div class="mt-2 pt-2 border-top">
            <strong class="text-dark d-block mb-1">Khách ghi chú:</strong>
            <span class="text-muted font-italic">"{{ $order->notes }}"</span>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
