@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng ' . $order['order_code'] . ' | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <h3 class="fw-bold text-dark mb-0">Đơn Hàng: <span class="font-monospace text-primary">{{ $order['order_code'] }}</span></h3>
        <span class="badge bg-warning text-dark px-3 py-1 fw-bold">{{ $order['shipping_status'] }}</span>
      </div>
      <p class="text-muted small mb-0">Thời gian tạo đơn: {{ $order['created_at'] }} • Phương thức: {{ $order['payment_method'] }}</p>
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
    
    <!-- 6-STEP STATUS UPDATE -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
      <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-route me-2 text-warning"></i> Cập Nhật Tiến Trình Vận Chuyển</h5>
      
      <form action="{{ route('admin.orders.updateStatus', $order['id']) }}" method="POST" class="d-flex align-items-center gap-3">
        @csrf
        <select name="status_step" class="form-select">
          <option value="1" {{ $order['status_step'] == 1 ? 'selected' : '' }}>Bước 1: Chờ xác nhận đơn hàng</option>
          <option value="2" {{ $order['status_step'] == 2 ? 'selected' : '' }}>Bước 2: Đã xác nhận thông tin</option>
          <option value="3" {{ $order['status_step'] == 3 ? 'selected' : '' }}>Bước 3: Đang đóng gói bưu phẩm</option>
          <option value="4" {{ $order['status_step'] == 4 ? 'selected' : '' }}>Bước 4: Đang vận chuyển bưu tá</option>
          <option value="5" {{ $order['status_step'] == 5 ? 'selected' : '' }}>Bước 5: Đang giao tới địa chỉ</option>
          <option value="6" {{ $order['status_step'] == 6 ? 'selected' : '' }}>Bước 6: Giao thành công &amp; Hoàn tất</option>
        </select>
        <button type="submit" class="btn btn-bee-primary text-nowrap px-4">Cập Nhật Trạng Thái</button>
      </form>
    </div>

    <!-- ITEMS TABLE -->
    <div class="bee-table-card mb-4">
      <div class="card-header">
        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-box-open me-2 text-warning"></i> Danh Sách Sản Phẩm Đặt Mua</h5>
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
            @foreach($order['items'] as $item)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 45px; height: 45px; object-fit: contain;" class="border rounded bg-light">
                    <div>
                      <div class="fw-bold small text-dark">{{ $item['name'] }}</div>
                      <small class="text-muted">Màu: {{ $item['color'] }} | Size: {{ $item['size'] }}</small>
                    </div>
                  </div>
                </td>
                <td><span class="font-monospace small text-muted">{{ $item['sku'] }}</span></td>
                <td>{{ number_format($item['price'], 0, ',', '.') }}₫</td>
                <td><span class="badge bg-light text-dark border">x{{ $item['quantity'] }}</span></td>
                <td class="text-end fw-bold text-dark">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="table-light">
            <tr>
              <td colspan="4" class="text-end fw-bold">Tổng tiền sản phẩm:</td>
              <td class="text-end fw-bold">{{ number_format($order['total_amount'], 0, ',', '.') }}₫</td>
            </tr>
            <tr>
              <td colspan="4" class="text-end fw-bold">Phí vận chuyển:</td>
              <td class="text-end text-success fw-bold">0₫ (Freeship)</td>
            </tr>
            <tr>
              <td colspan="4" class="text-end fw-bold text-dark fs-6">Tổng Thanh Toán:</td>
              <td class="text-end fw-bold text-danger fs-5">{{ number_format($order['total_amount'], 0, ',', '.') }}₫</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

  </div>

  <!-- RIGHT: CUSTOMER INFO -->
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
      <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user me-2 text-warning"></i> Thông Tin Khách Hàng</h5>
      
      <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
        <div class="avatar avatar-l bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center fs-4">
          <i class="fa-solid fa-user"></i>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-0">{{ $order['customer_name'] }}</h6>
          <small class="text-muted">{{ $order['customer_email'] }}</small>
        </div>
      </div>

      <div class="d-flex flex-column gap-2 small">
        <div>
          <strong class="text-dark d-block mb-1">Số điện thoại:</strong>
          <span class="text-muted">{{ $order['customer_phone'] }}</span>
        </div>
        <div>
          <strong class="text-dark d-block mb-1">Địa chỉ giao hàng:</strong>
          <span class="text-muted">{{ $order['customer_address'] }}</span>
        </div>
        <div>
          <strong class="text-dark d-block mb-1">Trạng thái thanh toán:</strong>
          <span class="badge {{ $order['payment_status'] === 'Đã thanh toán' ? 'bg-success' : 'bg-warning text-dark' }}">
            {{ $order['payment_status'] }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
