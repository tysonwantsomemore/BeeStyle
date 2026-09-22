<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chi Tiết Đơn Hàng #{{ $order->order_code }} - BeeStyle Express</title>
  <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="bg-light p-3">
  <div class="container" style="max-width: 700px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <a href="{{ route('shipper.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
        <i class="fa-solid fa-arrow-left me-1"></i> Quay Lại Danh Sách
      </a>
      <span class="badge bg-primary fs-9">#{{ $order->order_code }}</span>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
      <div class="card-body p-4">
        <h5 class="fw-bold text-dark mb-3">Thông Tin Người Nhận</h5>
        <div class="mb-2"><strong>Khách hàng:</strong> {{ $order->customer_name }}</div>
        <div class="mb-2"><strong>Số điện thoại:</strong> <a href="tel:{{ $order->customer_phone }}" class="fw-bold">{{ $order->customer_phone }}</a></div>
        <div class="mb-3"><strong>Địa chỉ:</strong> {{ $order->full_shipping_address }}</div>

        <hr>

        <h5 class="fw-bold text-dark mb-3">Sản Phẩm Trong Kiện</h5>
        <ul class="list-group list-group-flush mb-3">
          @foreach($order->items as $item)
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ $item->product_name }}</div>
                <small class="text-muted">{{ $item->color }} / {{ $item->size }}</small>
              </div>
              <span class="badge bg-light text-dark border">x{{ $item->quantity }}</span>
            </li>
          @endforeach
        </ul>

        <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
          <div>
            <div class="text-muted small">Hình thức: {{ $order->payment_method_name }}</div>
            <div class="fw-bold text-dark">{{ $order->payment_status_label }}</div>
          </div>
          <div class="fs-6 fw-black text-danger">
            {{ number_format($order->total_amount, 0, ',', '.') }}₫
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
