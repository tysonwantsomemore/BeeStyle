@extends('layouts.client')

@section('title', 'Cổng Thanh Toán Online Techcombank Napas 247 | Đơn Hàng #' . $order->order_code)

@section('content')
<div class="container py-5" style="max-width: 1050px;">
  
  <!-- BREADCRUMB / TOP NOTIFICATION -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <span class="badge px-3 py-1.5 rounded-pill text-white fw-bold shadow-xs bg-primary" style="font-size: 0.85rem;">
        <i class="fa-solid fa-building-columns me-1.5"></i> CỔNG THANH TOÁN ONLINE TECHCOMBANK NAPAS 247
      </span>
      <span class="text-muted small">Mã đơn hàng: <strong class="text-dark font-monospace">{{ $order->order_code }}</strong></span>
    </div>
    <div class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill small fw-semibold">
      <i class="fa-solid fa-clock me-1"></i> Giao dịch hết hạn sau: <span id="onlineCountdown" class="font-monospace fw-bold">09:59</span>
    </div>
  </div>

  <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 24px; background: #ffffff;">
    
    <!-- BRAND HEADER -->
    <div class="p-4 text-white d-flex justify-content-between align-items-center flex-wrap gap-3" 
         style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0284c7 100%);">
      <div class="d-flex align-items-center gap-3">
        <div class="bg-white rounded-3 p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fa-solid fa-credit-card fs-3 text-primary"></i>
        </div>
        <div>
          <h4 class="fw-bold mb-0 text-white">Thanh Toán Trực Tuyến Tự Động Khớp Lệnh</h4>
          <small class="text-white text-opacity-90">Hỗ trợ Napas 247, Techcombank, Internet Banking &amp; Thẻ Quốc Tế Visa/Mastercard</small>
        </div>
      </div>
      <div class="d-flex align-items-center gap-2 text-white small">
        <i class="fa-solid fa-shield-halved text-warning fs-5"></i>
        <span>Chứng nhận <strong>PCI-DSS 256-Bit</strong></span>
      </div>
    </div>

    <!-- MAIN BODY -->
    <div class="card-body p-4 p-lg-5">
      <div class="row g-4 g-lg-5 align-items-center">
        
        <!-- CỘT 1: THÔNG TIN ĐƠN HÀNG & SỐ TIỀN -->
        <div class="col-lg-5">
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
            <div class="p-3 rounded-3 text-center my-3" style="background: #f0f9ff; border: 1.5px dashed #0284c7;">
              <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Số tiền thanh toán</span>
              <h2 class="fw-black mb-0 font-monospace text-primary">
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
                      <span class="text-truncate" style="max-width: 160px;">{{ $it->product_name }} (x{{ $it->quantity }})</span>
                    </div>
                    <span class="text-dark fw-semibold">{{ number_format($it->subtotal, 0, ',', '.') }}₫</span>
                  </div>
                @endforeach
              </div>
            </div>

          </div>
        </div>

        <!-- CỘT 2: CỔNG THANH TOÁN NAPAS & NGÂN HÀNG -->
        <div class="col-lg-7">
          
          <!-- LIVE RADAR STATUS BOX (CHỜ THANH TOÁN THẬT) -->
          <div class="p-3 rounded-4 mb-3 border text-center shadow-xs" style="background: #ecfdf5; border-color: #a7f3d0 !important;">
            <div class="d-flex align-items-center justify-content-center gap-2 text-success fw-bold">
              <div class="spinner-grow spinner-grow-sm text-success" role="status"></div>
              <span>HỆ THỐNG ĐANG LẮNG NGHE CHUYỂN KHOẢN TỪ NGÂN HÀNG...</span>
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.78rem;">
              <i class="fa-solid fa-circle-check text-success me-1"></i> Quét mã QR bên dưới trên App Ngân Hàng. <strong>Khi chuyển khoản thành công</strong>, hệ thống sẽ tự động phát hiện và chuyển bạn về Trang Chủ!
            </small>
          </div>

          <!-- NAV TABS CHỌN HÌNH THỨC -->
          <ul class="nav nav-pills nav-fill mb-3 bg-light p-1.5 rounded-3 border" id="onlinePayTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active fw-bold small py-2 rounded-2" id="tab-qr-btn" data-bs-toggle="pill" data-bs-target="#tab-qr" type="button" role="tab">
                <i class="fa-solid fa-qrcode me-1"></i> Quét Mã Techcombank Napas 247
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link fw-bold small py-2 rounded-2" id="tab-bank-btn" data-bs-toggle="pill" data-bs-target="#tab-bank" type="button" role="tab">
                <i class="fa-solid fa-building-columns me-1"></i> Chọn Ngân Hàng / Thẻ
              </button>
            </li>
          </ul>

          <div class="tab-content" id="onlinePayTabContent">
            
            <!-- TAB 1: QUÉT QR TECHCOMBANK NAPAS 247 -->
            <div class="tab-pane fade show active text-center" id="tab-qr" role="tabpanel">
              @php
                $vietQrUrl = "https://img.vietqr.io/image/TCB-77427842310105-compact2.png?amount=" . $order->total_amount . "&addInfo=" . urlencode($order->order_code) . "&accountName=" . urlencode("NGUYEN XUAN BAC");
              @endphp
              <div class="p-3 bg-white rounded-4 border shadow-sm d-inline-block w-100" style="max-width: 380px;">
                <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                  <span class="badge bg-primary text-white fw-bold px-2 py-0.5" style="font-size: 0.7rem;">
                    TECHCOMBANK / NAPAS 247
                  </span>
                  <span class="text-success small fw-semibold" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-satellite-dish me-0.5 fa-fade"></i> Tự động khớp lệnh 24/7
                  </span>
                </div>

                <div class="p-2 rounded-3 border position-relative my-2 shadow-xs bg-light">
                  <img src="{{ $vietQrUrl }}" alt="Techcombank Napas 247 QR" style="max-width: 250px; width: 100%; height: auto;" class="rounded mx-auto d-block">
                  <div class="mt-2 text-muted small" style="font-size: 0.75rem;">
                    Mở <strong>App Ngân Hàng bất kỳ</strong> để quét mã chuyển tiền
                  </div>
                </div>

                <div class="text-start bg-light p-2.5 rounded-3 small text-muted my-2.5" style="font-size: 0.76rem;">
                  <div><strong class="text-dark">Chủ TK:</strong> <span class="text-dark fw-bold">NGUYEN XUAN BAC</span></div>
                  <div><strong class="text-dark">Ngân Hàng:</strong> Techcombank - STK: <strong class="text-primary font-monospace">77427842310105</strong></div>
                  <div><strong class="text-dark">Nội Dung CK:</strong> <span class="text-primary fw-bold font-monospace">{{ $order->order_code }}</span></div>
                </div>
              </div>
            </div>

            <!-- TAB 2: CHỌN NGÂN HÀNG & THẺ -->
            <div class="tab-pane fade" id="tab-bank" role="tabpanel">
              <div class="p-3 bg-white rounded-4 border shadow-sm">
                <label class="form-label small fw-bold text-dark mb-2">Chọn Ngân hàng phát hành thẻ / Internet Banking:</label>
                <div class="row g-2 mb-3">
                  @php
                    $banks = [
                      ['name' => 'Techcombank', 'code' => 'TCB', 'color' => '#ed1c24'],
                      ['name' => 'Vietcombank', 'code' => 'VCB', 'color' => '#005f27'],
                      ['name' => 'MB Bank', 'code' => 'MB', 'color' => '#1428a0'],
                      ['name' => 'VietinBank', 'code' => 'CTG', 'color' => '#005baa'],
                      ['name' => 'BIDV', 'code' => 'BIDV', 'color' => '#0c8040'],
                      ['name' => 'ACB Bank', 'code' => 'ACB', 'color' => '#006cb7'],
                      ['name' => 'VPBank', 'code' => 'VPB', 'color' => '#00a651'],
                      ['name' => 'TPBank', 'code' => 'TPB', 'color' => '#7b2082'],
                    ];
                  @endphp
                  @foreach($banks as $b)
                    <div class="col-6 col-md-3">
                      <div class="p-2 border rounded-2 text-center bank-item cursor-pointer transition-all hover-lift bg-light {{ $b['code'] === 'TCB' ? 'active' : '' }}"
                           onclick="selectBankCard(this, '{{ $b['name'] }}')" style="cursor: pointer;">
                        <span class="badge text-white d-block py-1 mb-1" style="background-color: {{ $b['color'] }}; font-size: 0.75rem;">
                          {{ $b['code'] }}
                        </span>
                        <small class="fw-semibold text-dark text-truncate d-block" style="font-size: 0.75rem;">{{ $b['name'] }}</small>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="alert alert-info py-2 px-3 small mb-0 rounded-3" id="bankSelectionNotice" style="font-size: 0.78rem;">
                  <i class="fa-solid fa-circle-check me-1 text-primary"></i> Đã chọn Techcombank. Vui lòng chuyển tiền đúng nội dung để hệ thống tự khớp lệnh.
                </div>
              </div>
            </div>

          </div>

          <!-- NÚT THAO TÁC & HỦY ĐƠN HOÀN KHO -->
          <div class="mt-3 text-center">
            
            <form action="{{ route('client.checkout.online.success', $order->order_code) }}" method="POST" id="onlineSuccessForm" class="mb-2">
              @csrf
              <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                <i class="fa-solid fa-circle-check"></i> Tôi Đã Chuyển Khoản Xong (Xác Nhận Ngay)
              </button>
            </form>

            <div class="d-flex justify-content-between align-items-center px-1 mt-2">
              <button type="button" class="btn btn-sm btn-outline-secondary" onclick="simulatePaymentDemo()" style="font-size: 0.75rem;">
                <i class="fa-solid fa-bolt text-warning me-1"></i> Giả lập Ngân Hàng Báo Có (Demo)
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
      <i class="fa-solid fa-lock me-1 text-success"></i> Giao dịch bảo đảm an toàn bởi Cổng Ngân Hàng Napas 247 &amp; BeeStyle Menswear.
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
      <h5 class="fw-black text-dark mb-1">ĐÃ NHẬN ĐƯỢC THANH TOÁN!</h5>
      <p class="text-muted small mb-3">Hệ thống Napas 247 đã khớp lệnh giao dịch đơn hàng <strong class="text-primary font-monospace">{{ $order->order_code }}</strong>.</p>
      <div class="spinner-border text-primary spinner-border-sm mx-auto mb-2" role="status"></div>
      <small class="text-muted d-block">Đang tự động chuyển về Trang Chủ...</small>
    </div>
  </div>
</div>

@push('scripts')
<style>
  .bank-item.active {
    border-color: #0284c7 !important;
    background-color: #f0f9ff !important;
    box-shadow: 0 4px 10px rgba(2, 132, 199, 0.15);
  }
</style>

<script>
  let isCompleted = false;

  // Đếm ngược 10 phút thanh toán
  let duration = 600;
  const timerDisplay = document.getElementById('onlineCountdown');
  
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

  function selectBankCard(el, bankName) {
    document.querySelectorAll('.bank-item').forEach(b => b.classList.remove('active'));
    if (el) el.classList.add('active');
    const notice = document.getElementById('bankSelectionNotice');
    if (notice) {
      notice.innerHTML = `<i class="fa-solid fa-circle-check me-1 text-primary"></i> Đã chọn ngân hàng <strong>${bankName}</strong>. Vui lòng chuyển tiền đúng cú pháp để khớp lệnh.`;
    }
  }

  // HỆ THỐNG LẮNG NGHE TRẠNG THÁI THANH TOÁN THỰC TẾ (POLLING MỖI 2.5 GIÂY)
  // CHỈ KHI NGÂN HÀNG XÁC NHẬN TIỀN ĐÃ VÀO (HOẶC ADMIN DUYỆT) THÌ MỚI ĐƯỢC TỰ ĐỘNG CHUYỂN TRANG
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
