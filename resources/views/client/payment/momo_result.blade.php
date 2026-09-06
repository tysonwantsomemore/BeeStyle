@extends('layouts.client')

@section('title', 'Kết Quả Thanh Toán MoMo' . ($order ? ' | Đơn Hàng #' . $order->order_code : ''))

@section('content')
<div class="container py-5" style="max-width: 820px;">
  
  <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 24px; background: #ffffff;">
    
    <!-- MOMO BRAND HEADER -->
    <div class="p-4 text-white d-flex justify-content-between align-items-center flex-wrap gap-3" 
         style="background: linear-gradient(135deg, #a50064 0%, #d82d8b 100%);">
      <div class="d-flex align-items-center gap-3">
        <div class="bg-white rounded-3 p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
          <span class="fw-black" style="color: #a50064; font-size: 1.1rem; letter-spacing: -0.5px;">MoMo</span>
        </div>
        <div>
          <h4 class="fw-bold mb-0 text-white">Kết Quả Thanh Toán Trực Tuyến MoMo</h4>
          <small class="text-white text-opacity-90">Cổng thanh toán MoMo Sandbox E-Commerce Gateway</small>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2 text-white small">
        <i class="fa-solid fa-shield-halved text-warning fs-5"></i>
        <span>Bảo mật <strong>SSL 256-Bit</strong></span>
      </div>
    </div>

    <!-- MAIN BODY -->
    <div class="card-body p-4 p-lg-5 text-center">

      @php
        $isPaid = ($order && strtoupper((string)$order->payment_status) === 'PAID') || $resultCode === 0;
        $isCancelled = ($order && strtoupper((string)$order->payment_status) === 'CANCELLED') || $resultCode === 1006;
        $isExpired = ($order && strtoupper((string)$order->payment_status) === 'EXPIRED') || $resultCode === 49;
      @endphp

      @if($isPaid)
        <!-- TRẠNG THÁI THÀNH CÔNG -->
        <div class="mb-4">
          <div class="rounded-circle bg-success-subtle text-success p-3 d-inline-flex align-items-center justify-content-center shadow-sm" 
               style="width: 86px; height: 86px;">
            <i class="fa-solid fa-check fs-1"></i>
          </div>
        </div>

        <h3 class="fw-black text-success mb-2">THANH TOÁN THÀNH CÔNG!</h3>
        <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto;">
          Chúc mừng bạn đã thanh toán thành công đơn hàng <strong class="text-dark font-monospace">#{{ $order ? $order->order_code : '' }}</strong> qua Ví MoMo. BeeStyle đã tiếp nhận và đang tiến hành xử lý đóng gói sản phẩm.
        </p>

        @if($order)
        <div class="p-4 bg-light rounded-4 border text-start mb-4 mx-auto" style="max-width: 560px;">
          <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
            <i class="fa-solid fa-receipt text-secondary me-2"></i> Chi Tiết Giao Dịch
          </h6>
          <div class="d-flex flex-column gap-2 small">
            <div class="d-flex justify-content-between">
              <span class="text-muted">Mã đơn hàng:</span>
              <strong class="font-monospace text-primary">{{ $order->order_code }}</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Mã giao dịch MoMo:</span>
              <strong class="font-monospace text-dark">{{ $order->momo_trans_id ?: ($transId ?: 'Đang cập nhật') }}</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Khách hàng:</span>
              <span class="text-dark fw-semibold">{{ $order->customer_name }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Phương thức thanh toán:</span>
              <span class="text-dark fw-semibold">Thanh toán trực tuyến qua ví MoMo (Redirect/Deep Link)</span>
            </div>
            <div class="d-flex justify-content-between pt-2 border-top">
              <span class="text-muted fw-bold">Tổng tiền thanh toán:</span>
              <h5 class="fw-black font-monospace text-danger mb-0">{{ number_format($order->total_amount, 0, ',', '.') }}₫</h5>
            </div>
          </div>
        </div>
        @endif

        <div class="d-flex justify-content-center gap-3 flex-wrap">
          @if($order)
            <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" 
               class="btn text-white px-4 py-2.5 fw-bold rounded-3 shadow-sm d-inline-flex align-items-center gap-2"
               style="background: linear-gradient(135deg, #a50064, #d82d8b);">
              <i class="fa-solid fa-truck-fast"></i> Tra Cứu Đơn Hàng
            </a>
          @endif
          <a href="{{ route('client.home') }}" class="btn btn-outline-secondary px-4 py-2.5 fw-semibold rounded-3">
            <i class="fa-solid fa-house me-1"></i> Về Trang Chủ
          </a>
        </div>

      @elseif($isCancelled)
        <!-- TRẠNG THÁI KHÁCH HỦY GIAO DỊCH -->
        <div class="mb-4">
          <div class="rounded-circle bg-warning-subtle text-warning p-3 d-inline-flex align-items-center justify-content-center shadow-sm" 
               style="width: 86px; height: 86px;">
            <i class="fa-solid fa-ban fs-1"></i>
          </div>
        </div>

        <h3 class="fw-black text-warning mb-2">BẠN ĐÃ HỦY GIAO DỊCH</h3>
        <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto;">
          Giao dịch thanh toán MoMo cho đơn hàng <strong class="text-dark font-monospace">#{{ $order ? $order->order_code : '' }}</strong> đã được hủy theo yêu cầu của bạn. Tồn kho sản phẩm đã được tự động hoàn trả.
        </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
          @if($order)
            <form action="{{ route('client.checkout.momo.redirect', $order->order_code) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn text-white px-4 py-2.5 fw-bold rounded-3 shadow-sm d-inline-flex align-items-center gap-2"
                      style="background: linear-gradient(135deg, #a50064, #d82d8b);">
                <i class="fa-solid fa-rotate-right"></i> Thanh Toán Lại
              </button>
            </form>
          @endif
          <a href="{{ route('client.cart') }}" class="btn btn-dark px-4 py-2.5 fw-bold rounded-3 shadow-sm">
            <i class="fa-solid fa-cart-shopping me-1"></i> Quay Lại Giỏ Hàng
          </a>
          <a href="{{ route('client.products.index') }}" class="btn btn-outline-secondary px-4 py-2.5 fw-semibold rounded-3">
            Tiếp Tục Mua Sắm
          </a>
        </div>

      @elseif($isExpired)
        <!-- TRẠNG THÁI HẾT HẠN -->
        <div class="mb-4">
          <div class="rounded-circle bg-secondary-subtle text-secondary p-3 d-inline-flex align-items-center justify-content-center shadow-sm" 
               style="width: 86px; height: 86px;">
            <i class="fa-solid fa-clock-rotate-left fs-1"></i>
          </div>
        </div>

        <h3 class="fw-black text-secondary mb-2">GIAO DỊCH HẾT HẠN</h3>
        <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto;">
          Phiên giao dịch MoMo đã quá hạn thời gian cho phép. Vui lòng tiến hành tạo lại đơn hàng để tiếp tục.
        </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
          <a href="{{ route('client.cart') }}" class="btn btn-dark px-4 py-2.5 fw-bold rounded-3 shadow-sm">
            <i class="fa-solid fa-cart-shopping me-1"></i> Quay Lại Giỏ Hàng
          </a>
        </div>

      @else
        <!-- TRẠNG THÁI THẤT BẠI -->
        <div class="mb-4">
          <div class="rounded-circle bg-danger-subtle text-danger p-3 d-inline-flex align-items-center justify-content-center shadow-sm" 
               style="width: 86px; height: 86px;">
            <i class="fa-solid fa-xmark fs-1"></i>
          </div>
        </div>

        <h3 class="fw-black text-danger mb-2">THANH TOÁN KHÔNG THÀNH CÔNG</h3>
        <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto;">
          {{ $message ?: 'Đã xảy ra lỗi trong quá trình xử lý thanh toán qua MoMo.' }} 
          @if($resultCode)<span class="d-block small text-secondary mt-1">(Mã phản hồi MoMo: {{ $resultCode }})</span>@endif
        </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
          @if($order)
            <form action="{{ route('client.checkout.momo.redirect', $order->order_code) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn text-white px-4 py-2.5 fw-bold rounded-3 shadow-sm d-inline-flex align-items-center gap-2"
                      style="background: linear-gradient(135deg, #a50064, #d82d8b);">
                <i class="fa-solid fa-rotate-right"></i> Thanh Toán Lại
              </button>
            </form>
          @endif
          <a href="{{ route('client.cart') }}" class="btn btn-outline-secondary px-4 py-2.5 fw-semibold rounded-3">
            <i class="fa-solid fa-cart-shopping me-1"></i> Quay Lại Giỏ Hàng
          </a>
        </div>

      @endif

    </div>

    <!-- FOOTER -->
    <div class="card-footer bg-light p-3 text-center text-muted small border-top">
      <i class="fa-solid fa-lock me-1 text-success"></i> Giao dịch được bảo đảm an toàn bởi Ví điện tử MoMo &amp; BeeStyle Menswear.
    </div>

  </div>
</div>
@endsection