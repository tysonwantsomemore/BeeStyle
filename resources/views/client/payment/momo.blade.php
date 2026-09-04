@extends('layouts.client')

@section('title', 'Cổng Thanh Toán MoMo | Đơn Hàng #' . $order->order_code)

@section('content')
<div class="container py-5" style="max-width: 1000px;">
  
  <!-- BREADCRUMB / TOP NOTIFICATION -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <span class="badge px-3 py-1.5 rounded-pill text-white fw-bold shadow-xs" style="background-color: #d82d8b; font-size: 0.85rem;">
        <i class="fa-solid fa-wallet me-1.5"></i> CỔNG THANH TOÁN MOMO
      </span>
      <span class="text-muted small">Mã đơn hàng: <strong class="text-dark font-monospace">{{ $order->order_code }}</strong></span>
    </div>
    <div class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill small fw-semibold">
      <i class="fa-solid fa-clock me-1"></i> Giao dịch hết hạn sau: <span id="momoCountdown" class="font-monospace fw-bold">09:59</span>
    </div>
  </div>

  <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 24px; background: #ffffff;">
    
    <!-- MOMO BRAND HEADER -->
    <div class="p-4 text-white d-flex justify-content-between align-items-center flex-wrap gap-3" 
         style="background: linear-gradient(135deg, #a50064 0%, #d82d8b 100%);">
      <div class="d-flex align-items-center gap-3">
        <div class="bg-white rounded-3 p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <span class="fw-black" style="color: #a50064; font-size: 1.1rem; letter-spacing: -0.5px;">MoMo</span>
        </div>
        <div>
          <h4 class="fw-bold mb-0 text-white">Thanh Toán Qua Ví MoMo Tự Động Khớp Lệnh</h4>
          <small class="text-white text-opacity-90">Cổng thanh toán điện tử an toàn bảo mật tiêu chuẩn Quốc tế</small>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2 text-white small">
        <i class="fa-solid fa-shield-halved text-warning fs-5"></i>
        <span>Bảo mật <strong>SSL 256-Bit</strong></span>
      </div>
    </div>

    <!-- MAIN BODY -->
    <div class="card-body p-4 p-lg-5">
      
      @if(session('error'))
        <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3 p-3">
          <i class="fa-solid fa-circle-exclamation fs-4 text-danger flex-shrink-0"></i>
          <div>
            <strong class="d-block text-danger">Thông báo từ Cổng MoMo:</strong>
            <span class="small">{{ session('error') }}</span>
          </div>
        </div>
      @endif

      @if(session('warning'))
        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4 d-flex align-items-center gap-3 p-3">
          <i class="fa-solid fa-triangle-exclamation fs-4 text-warning flex-shrink-0"></i>
          <div>
            <strong class="d-block text-warning">Lưu ý:</strong>
            <span class="small">{{ session('warning') }}</span>
          </div>
        </div>
      @endif

      <!-- LIVE RADAR STATUS BOX (CHỜ THANH TOÁN THẬT) -->
      <div class="p-3 rounded-4 mb-4 border text-center shadow-xs" style="background: #fdf2f8; border-color: #fbcfe8 !important;">
        <div class="d-flex align-items-center justify-content-center gap-2 font-bold" style="color: #a50064;">
          <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
          <span class="fw-bold">HỆ THỐNG ĐANG ĐỢI PHẢN HỒI KẾT QUẢ TỪ VÍ MOMO...</span>
        </div>
        <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
          <i class="fa-solid fa-circle-check text-success me-1"></i> Bấm "Mở Cổng Thanh Toán MoMo" bên dưới để hoàn tất giao dịch trên App MoMo hoặc cổng MoMo chính thức.
        </small>
      </div>

      <div class="row g-4 g-lg-5 align-items-center">
        
        <!-- CỘT 1: THÔNG TIN ĐƠN HÀNG & SỐ TIỀN -->
        <div class="col-lg-6">
          <div class="p-4 bg-light rounded-4 border">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex justify-content-between align-items-center">
              <span><i class="fa-solid fa-receipt me-2 text-secondary"></i> Thông Tin Đơn Hàng</span>
              <span class="badge bg-secondary-subtle text-secondary small">BeeStyle Store</span>
            </h6>

            <div class="d-flex flex-column gap-2.5 small mb-3">
              <div class="d-flex justify-content-between">
                <span class="text-muted">Nhà bán hàng:</span>
                <strong class="text-dark">BeeStyle Menswear</strong>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Khách hàng:</span>
                <strong class="text-dark">{{ $order->customer_name }}</strong>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Số điện thoại:</span>
                <span class="text-dark fw-semibold">{{ $order->customer_phone }}</span>
              </div>
              <div class="d-flex justify-content-between">
                <span class="text-muted">Mã giao dịch:</span>
                <span class="font-monospace text-primary fw-bold">{{ $order->order_code }}</span>
              </div>
            </div>

            <!-- TỔNG TIỀN NỔI BẬT -->
            <div class="p-3 rounded-3 text-center my-3" style="background: #fdf2f8; border: 1.5px dashed #d82d8b;">
              <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Số tiền thanh toán</span>
              <h2 class="fw-black mb-0 font-monospace" style="color: #a50064;">
                {{ number_format($order->total_amount, 0, ',', '.') }}₫
              </h2>
            </div>

            <!-- DANH SÁCH SẢN PHẨM THU GỌN -->
            <div class="pt-2">
              <span class="text-muted small fw-bold text-uppercase d-block mb-2" style="font-size: 0.72rem;">Sản phẩm đặt mua ({{ $order->items->count() }})</span>
              <div class="d-flex flex-column gap-2" style="max-height: 140px; overflow-y: auto;">
                @foreach($order->items as $it)
                  <div class="d-flex align-items-center justify-content-between gap-2 text-muted small">
                    <div class="d-flex align-items-center gap-2 text-truncate">
                      <img src="{{ asset($it->image) }}" alt="{{ $it->product_name }}" style="width: 28px; height: 28px; object-fit: contain;" class="rounded border bg-white">
                      <span class="text-truncate" style="max-width: 180px;">{{ $it->product_name }} (x{{ $it->quantity }})</span>
                    </div>
                    <span class="text-dark fw-semibold">{{ number_format($it->subtotal, 0, ',', '.') }}₫</span>
                  </div>
                @endforeach
              </div>
            </div>

          </div>
        </div>

        <!-- CỘT 2: CỔNG THANH TOÁN TRỰC TUYẾN MOMO (KHÔNG HIỂN THỊ QR) -->
        <div class="col-lg-6 text-center">
          
          <div class="p-4 bg-white rounded-4 border shadow-sm d-inline-block w-100" style="max-width: 380px;">
            
            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
              <span class="badge text-white fw-bold px-2.5 py-1" style="background-color: #d82d8b; font-size: 0.75rem;">
                <i class="fa-solid fa-wallet me-1"></i> MOMO GATEWAY
              </span>
              <span class="text-muted small" style="font-size: 0.72rem;">
                <i class="fa-solid fa-shield-check text-success me-0.5"></i> Cổng MoMo chính thức
              </span>
            </div>

            <!-- BIỂU TƯỢNG VÍ MOMO NỔI BẬT -->
            <div class="p-4 rounded-4 mb-3" style="background: linear-gradient(135deg, #fdf2f8, #fce7f3); border: 1.5px solid #fbcfe8;">
              <div class="rounded-circle bg-white shadow-sm mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
                <i class="fa-solid fa-mobile-screen-button fs-1" style="color: #a50064;"></i>
              </div>
              <h5 class="fw-black text-dark mb-1">Thanh Toán Trực Tuyến MoMo</h5>
              <p class="text-muted small mb-0" style="font-size: 0.8rem;">
                Mở trực tiếp ứng dụng MoMo trên điện thoại hoặc chuyển tới cổng thanh toán MoMo Sandbox.
              </p>
            </div>

            <!-- NÚT CHUYỂN TIẾP TRỰC TIẾP SANG MOMO -->
            <form action="{{ route('client.checkout.momo.redirect', $order->order_code) }}" method="POST" class="mb-3">
              @csrf
              <button type="submit" class="btn text-white w-100 py-3 fw-black rounded-3 shadow d-flex align-items-center justify-content-center gap-2"
                      style="background: linear-gradient(135deg, #a50064, #d82d8b); font-size: 0.95rem;">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Mở Cổng Thanh Toán MoMo
              </button>
            </form>

            <!-- HỘP THÔNG TIN TÀI KHOẢN TEST DEVELOPER -->
            <div class="text-start p-3 rounded-3 small mb-3 text-secondary" style="background: #fdf2f8; border: 1px dashed #f472b6; font-size: 0.76rem;">
              <div class="fw-bold text-dark mb-1.5 d-flex align-items-center justify-content-between">
                <span><i class="fa-solid fa-flask text-danger me-1"></i> Tài Khoản Test MoMo Sandbox:</span>
                <span class="badge bg-danger-subtle text-danger px-1.5 py-0.5" style="font-size: 0.65rem;">Môi trường Test</span>
              </div>
              <div class="mb-1"><span class="text-muted">• Số ĐT Test MoMo:</span> <strong class="text-danger font-monospace">0968238772</strong></div>
              <div class="mb-1"><span class="text-muted">• Mã OTP Test:</span> <strong class="text-primary font-monospace">000000</strong></div>
              <div><span class="text-muted">• Thẻ ATM Test:</span> <strong class="text-dark font-monospace">9704000000000018</strong></div>
            </div>

            <div class="text-start bg-light p-2.5 rounded-3 small text-muted my-2.5" style="font-size: 0.76rem;">
              <i class="fa-solid fa-circle-info text-primary me-1"></i> Giao dịch sẽ được xác nhận tự động thông qua Webhook IPN từ máy chủ MoMo.
            </div>

            <!-- HỦY ĐƠN HÀNG -->
            <div class="text-center mt-3 pt-2 border-top">
              <form action="{{ route('client.checkout.expire', $order->order_code) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này để hoàn trả kho?')">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none" style="font-size: 0.78rem;">
                  <i class="fa-solid fa-xmark me-1"></i> Hủy giao dịch &amp; Quay lại giỏ hàng
                </button>
              </form>
            </div>

          </div>

        </div>

      </div>
    </div>

    <!-- FOOTER INFO -->
    <div class="card-footer bg-light p-3 text-center text-muted small border-top">
      <i class="fa-solid fa-lock me-1 text-success"></i> Giao dịch được bảo đảm an toàn bởi Ví điện tử MoMo &amp; BeeStyle Menswear.
    </div>

  </div>
</div>

<!-- AUTO SUCCESS TOAST / MODAL -->
<div class="modal fade" id="autoSuccessModal" tabindex="-1" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
    <div class="modal-content text-center p-4 border-0 shadow-2xl rounded-4">
      <div class="my-3">
        <span class="rounded-circle bg-success-subtle text-success p-3 d-inline-flex align-items-center justify-content-center shadow" style="width: 76px; height: 76px;">
          <i class="fa-solid fa-check fs-1"></i>
        </span>
      </div>
      <h5 class="fw-black text-dark mb-1">ĐÃ NHẬN ĐƯỢC THANH TOÁN MOMO!</h5>
      <p class="text-muted small mb-3">Hệ thống MoMo đã khớp lệnh đơn hàng <strong class="text-primary font-monospace">{{ $order->order_code }}</strong>.</p>
      <div class="spinner-border text-danger spinner-border-sm mx-auto mb-2" role="status"></div>
      <small class="text-muted d-block">Đang tự động chuyển về Trang Chủ...</small>
    </div>
  </div>
</div>

@push('scripts')
<script>
  let isCompleted = false;

  // Đếm ngược 10 phút thanh toán
  let duration = 600;
  const timerDisplay = document.getElementById('momoCountdown');
  
  const timer = setInterval(function () {
    let minutes = parseInt(duration / 60, 10);
    let seconds = parseInt(duration % 60, 10);

    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    if (timerDisplay) {
      timerDisplay.textContent = minutes + ":" + seconds;
    }

    if (--duration < 0) {
      clearInterval(timer);
      if (timerDisplay) {
        timerDisplay.textContent = "HẾT HẠN";
      }
      triggerAutoExpire();
    }
  }, 1000);

  function triggerAutoExpire() {
    if (isCompleted) return;
    fetch("{{ route('client.checkout.expire', $order->order_code) }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json'
      }
    }).then(() => {
      alert("Đơn hàng đã hết thời gian chờ thanh toán (10 phút) và đã được tự động hủy để hoàn trả tồn kho.");
      window.location.href = "{{ route('client.cart') }}";
    });
  }

  // HỆ THỐNG LẮNG NGHE TRẠNG THÁI THANH TOÁN THỰC TẾ (POLLING MỖI 2.5 GIÂY)
  // CHỈ KHI NGÂN HÀNG/MOMO XÁC NHẬN TIỀN ĐÃ VÀO THÌ MỚI TỰ ĐỘNG CHUYỂN TRANG
  const statusChecker = setInterval(function() {
    if (isCompleted) return;
    fetch("{{ route('client.checkout.check-status', $order->order_code) }}")
      .then(res => res.json())
      .then(data => {
        if (data.status === 'paid') {
          isCompleted = true;
          clearInterval(statusChecker);
          
          const modalEl = document.getElementById('autoSuccessModal');
          const modal = new bootstrap.Modal(modalEl);
          modal.show();

          setTimeout(() => {
            window.location.href = "{{ route('payment.momo.result', ['orderId' => $order->order_code]) }}";
          }, 1500);
        }
      }).catch(err => console.log(err));
  }, 2500);
</script>
@endpush
@endsection
