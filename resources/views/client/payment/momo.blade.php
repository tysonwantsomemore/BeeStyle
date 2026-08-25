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
      
      <!-- LIVE RADAR STATUS BOX (CHỜ THANH TOÁN THẬT) -->
      <div class="p-3 rounded-4 mb-4 border text-center shadow-xs" style="background: #fdf2f8; border-color: #fbcfe8 !important;">
        <div class="d-flex align-items-center justify-content-center gap-2 font-bold" style="color: #a50064;">
          <div class="spinner-grow spinner-grow-sm text-danger" role="status"></div>
          <span class="fw-bold">HỆ THỐNG ĐANG LẮNG NGHE CHUYỂN TIỀN TỪ VÍ MOMO...</span>
        </div>
        <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
          <i class="fa-solid fa-circle-check text-success me-1"></i> Quét mã QR bằng App MoMo hoặc App Ngân Hàng. <strong>Khi chuyển khoản thành công</strong>, hệ thống sẽ tự động xác nhận &amp; đưa bạn về Trang Chủ!
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

        <!-- CỘT 2: MÃ QR MOMO & HƯỚNG DẪN THANH TOÁN -->
        <div class="col-lg-6 text-center">
          
          <div class="p-4 bg-white rounded-4 border shadow-sm d-inline-block w-100" style="max-width: 360px;">
            
            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
              <span class="badge text-white fw-bold px-2 py-0.5" style="background-color: #d82d8b; font-size: 0.7rem;">
                MOMO QR 24/7
              </span>
              <span class="text-muted small" style="font-size: 0.72rem;">
                <i class="fa-solid fa-satellite-dish text-success me-0.5 fa-fade"></i> Tự động khớp lệnh
              </span>
            </div>

            <!-- DYNAMIC QR CODE TECHCOMBANK -->
            @php
              $momoQrUrl = "https://img.vietqr.io/image/TCB-77427842310105-compact2.png?amount=" . $order->total_amount . "&addInfo=" . urlencode($order->order_code) . "&accountName=" . urlencode("NGUYEN XUAN BAC");
            @endphp
            <div class="p-2.5 rounded-3 border position-relative my-2 shadow-xs" style="background: #faf5ff;">
              <img src="{{ $momoQrUrl }}" alt="MoMo QR Code" style="max-width: 240px; width: 100%; height: auto;" class="rounded mx-auto d-block">
              <div class="mt-2 text-muted small" style="font-size: 0.75rem;">
                Quét mã bằng ứng dụng <strong>MoMo</strong> hoặc App Ngân Hàng
              </div>
            </div>

            <div class="text-start bg-light p-2.5 rounded-3 small text-muted my-2.5" style="font-size: 0.76rem;">
              <div><strong class="text-dark">Chủ TK:</strong> <span class="text-dark fw-bold">NGUYEN XUAN BAC</span></div>
              <div><strong class="text-dark">Ngân Hàng:</strong> Techcombank - STK: <strong class="text-danger font-monospace">77427842310105</strong></div>
              <div><strong class="text-dark">Nội Dung:</strong> <span class="text-primary fw-bold font-monospace">{{ $order->order_code }}</span></div>
            </div>

            <!-- 3 BƯỚC THANH TOÁN -->
            <div class="text-start bg-light p-2.5 rounded-3 small text-muted my-2" style="font-size: 0.76rem;">
              <div class="mb-1"><strong class="text-dark">Bước 1:</strong> Mở ứng dụng <strong>MoMo</strong> hoặc App Bank.</div>
              <div class="mb-1"><strong class="text-dark">Bước 2:</strong> Quét mã QR và kiểm tra số tiền.</div>
              <div><strong class="text-dark">Bước 3:</strong> Chuyển tiền $\rightarrow$ Hệ thống tự động xác nhận ngay!</div>
            </div>

            <!-- NÚT THAO TÁC & HỦY ĐƠN -->
            <form action="{{ route('client.checkout.momo.success', $order->order_code) }}" method="POST" id="momoSuccessForm" class="mb-2">
              @csrf
              <button type="submit" class="btn text-white w-100 py-2.5 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2"
                      style="background: linear-gradient(135deg, #d82d8b, #a50064);">
                <i class="fa-solid fa-circle-check"></i> Tôi Đã Chuyển Khoản Xong (Xác Nhận Ngay)
              </button>
            </form>

            <div class="d-flex justify-content-between align-items-center px-1 mt-2">
              <button type="button" class="btn btn-sm btn-outline-secondary" onclick="simulatePaymentDemo()" style="font-size: 0.75rem;">
                <i class="fa-solid fa-bolt text-danger me-1"></i> Giả lập MoMo Báo Có (Demo)
              </button>

              <form action="{{ route('client.checkout.expire', $order->order_code) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này để hoàn trả kho?')">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-danger text-decoration-none" style="font-size: 0.75rem;">
                  <i class="fa-solid fa-xmark me-1"></i> Hủy &amp; Hoàn kho
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
            window.location.href = "{{ route('client.home') }}";
          }, 1500);
        }
      }).catch(err => console.log(err));
  }, 2500);

  // Nút hỗ trợ Demo nhanh khi cần test luồng Webhook
  function simulatePaymentDemo() {
    if (isCompleted) return;
    fetch("{{ route('client.checkout.auto-confirm', $order->order_code) }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json'
      }
    }).then(res => res.json())
      .then(data => {
        // Status polling ở trên sẽ bắt được 'paid' và tự nhảy trang
      });
  }
</script>
@endpush
@endsection
