@extends('layouts.client')

@section('title', 'Tra Cứu Hành Trình Đơn Hàng | BeeStyle')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tra cứu đơn hàng</li>
    </ol>
  </nav>

  <!-- SEARCH ORDER BOX -->
  <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-3 mb-lg-0">
        <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-truck-fast text-warning me-2"></i> Tra Cứu Hành Trình Đơn Hàng</h4>
        <p class="text-muted small mb-0">Nhập mã đơn hàng hoặc số điện thoại để kiểm tra trạng thái vận chuyển thời gian thực</p>
      </div>
      <div class="col-lg-6">
        <form action="{{ route('client.order-tracking') }}" method="GET" class="d-flex gap-2">
          <input type="text" name="code" value="{{ $code }}" class="form-control" placeholder="Nhập mã đơn hàng (VD: BEE-2026-0816-01)..." required>
          <button type="submit" class="btn btn-bee-primary px-4 text-nowrap">Tra Cứu</button>
        </form>
      </div>
    </div>
  </div>

  @if($currentOrder)
    <!-- ORDER STATUS & TRACKER -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-3 border-bottom">
        <div>
          <span class="text-muted small">Mã đơn hàng:</span>
          <h5 class="fw-bold text-dark mb-0 font-monospace">{{ $currentOrder['order_code'] }}</h5>
        </div>
        <div>
          <span class="text-muted small">Thời gian đặt:</span>
          <div class="fw-semibold text-dark">{{ $currentOrder['created_at'] }}</div>
        </div>
        <div>
          <span class="text-muted small">Trạng thái:</span>
          <div><span class="badge bg-warning text-dark px-3 py-2 fw-bold">{{ $currentOrder['shipping_status'] }}</span></div>
        </div>
        <div>
          <span class="text-muted small">Tổng tiền:</span>
          <div class="fw-bold text-danger fs-5">{{ number_format($currentOrder['total_amount'], 0, ',', '.') }}₫</div>
        </div>
      </div>

      <!-- 6-STEP TIMELINE TRACKER -->
      <div class="bee-timeline-steps my-5">
        @php
          $steps = [
            1 => 'Đặt hàng',
            2 => 'Xác nhận',
            3 => 'Đóng gói',
            4 => 'Đang giao',
            5 => 'Đến bưu cục',
            6 => 'Hoàn tất'
          ];
        @endphp

        @foreach($steps as $stepNum => $stepLabel)
          <div class="bee-timeline-step {{ $currentOrder['status_step'] > $stepNum ? 'completed' : ($currentOrder['status_step'] == $stepNum ? 'active' : '') }}">
            <div class="bee-timeline-step-icon">
              @if($currentOrder['status_step'] > $stepNum)
                <i class="fa-solid fa-check"></i>
              @else
                {{ $stepNum }}
              @endif
            </div>
            <div class="bee-timeline-step-label">{{ $stepLabel }}</div>
          </div>
        @endforeach
      </div>

      <!-- ORDER DETAILS & CUSTOMER INFO -->
      <div class="row g-4 pt-3 border-top">
        <div class="col-md-6 border-end">
          <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user me-2 text-warning"></i> Thông Tin Nhận Hàng</h6>
          <p class="small mb-1"><strong>Người nhận:</strong> {{ $currentOrder['customer_name'] }}</p>
          <p class="small mb-1"><strong>Số điện thoại:</strong> {{ $currentOrder['customer_phone'] }}</p>
          <p class="small mb-1"><strong>Email:</strong> {{ $currentOrder['customer_email'] }}</p>
          <p class="small mb-1"><strong>Địa chỉ giao:</strong> {{ $currentOrder['customer_address'] }}</p>
          <p class="small mb-0"><strong>Phương thức thanh toán:</strong> {{ $currentOrder['payment_method'] }} ({{ $currentOrder['payment_status'] }})</p>
        </div>

        <div class="col-md-6">
          <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-box-open me-2 text-warning"></i> Sản Phẩm Đã Đặt</h6>
          <div class="d-flex flex-column gap-2">
            @foreach($currentOrder['items'] as $item)
              <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 45px; height: 45px; object-fit: contain;">
                  <div>
                    <div class="small fw-bold text-dark">{{ $item['name'] }}</div>
                    <small class="text-muted">{{ $item['color'] }} / Size {{ $item['size'] }} • x{{ $item['quantity'] }}</small>
                  </div>
                </div>
                <div class="fw-bold small text-dark">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

    </div>
  @endif

</div>
@endsection
