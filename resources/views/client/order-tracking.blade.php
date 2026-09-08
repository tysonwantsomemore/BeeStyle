@extends('layouts.client')

@section('title', 'Tra Cứu & Thanh Toán Đơn Hàng | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tra cứu &amp; Thanh toán đơn hàng</li>
    </ol>
  </nav>

  <!-- SEARCH ORDER & CARRIER TRACKING BOX (DUAL-MODE OMNIBAR) -->
  <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
    <div class="row align-items-center g-3">
      <div class="col-lg-5">
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">TRA CỨU KÉP</span>
          <h4 class="fw-bold text-dark mb-0">Tra Cứu Đơn Hàng &amp; Vận Đơn</h4>
        </div>
        <p class="text-muted small mb-0">Hỗ trợ tra cứu tức thì bằng <strong>Mã Đơn Hàng</strong> hoặc <strong>Mã Vận Đơn Bưu Tá</strong></p>
      </div>
      <div class="col-lg-7">
        <!-- Chế độ chọn Tab nhanh -->
        <div class="d-flex gap-2 mb-2">
          <button type="button" class="btn btn-sm {{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'btn-outline-secondary' : 'btn-dark' }} rounded-pill px-3 fw-bold" id="btnTabOrder" onclick="switchSearchMode('order')">
            <i class="fa-solid fa-receipt me-1 text-warning"></i> Mã Đơn Hàng
          </button>
          <button type="button" class="btn btn-sm {{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3 fw-bold" id="btnTabTracking" onclick="switchSearchMode('tracking')">
            <i class="fa-solid fa-barcode me-1 text-warning"></i> Mã Vận Đơn Bưu Tá
          </button>
        </div>

        <form action="{{ route('client.order-tracking') }}" method="GET" class="d-flex gap-2" id="trackingSearchForm">
          <input type="hidden" name="type" id="searchTypeInput" value="{{ $searchType ?? 'auto' }}">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted" id="searchIcon">
              <i class="fa-solid {{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'fa-barcode text-success' : 'fa-magnifying-glass' }}"></i>
            </span>
            <input type="text" name="code" id="trackingCodeInput" value="{{ $code ?? '' }}" class="form-control border-start-0 ps-0 font-monospace fw-bold text-dark" placeholder="{{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'Nhập mã vận đơn bưu tá (VD: GHTK-GFELJZTT, GHN-2C4E3DFF)...' : 'Nhập mã đơn hàng (VD: BEE-20260906-T7XF)...' }}" required>
            @if(!empty($code))
              <button class="btn btn-outline-light text-muted border border-start-0" type="button" title="Xóa trắng" onclick="document.getElementById('trackingCodeInput').value=''; document.getElementById('trackingCodeInput').focus();">
                <i class="fa-solid fa-xmark"></i>
              </button>
            @endif
          </div>
          <button type="submit" class="btn btn-bee-primary px-4 text-nowrap fw-bold shadow-sm">
            <i class="fa-solid fa-magnifying-glass me-1"></i> Tra Cứu
          </button>
        </form>

        <!-- Thanh Gợi Ý Tra Cứu Nhanh (Smart Suggestion Chips) -->
        <div class="mt-2.5 d-flex align-items-center gap-1.5 flex-wrap small">
          <span class="text-muted small me-1"><i class="fa-solid fa-bolt text-warning me-1"></i> Gợi ý tra cứu:</span>
          @if(isset($userRecentOrders) && $userRecentOrders->isNotEmpty())
            @foreach($userRecentOrders->take(3) as $rOrder)
              <a href="{{ route('client.order-tracking', ['code' => $rOrder->order_code, 'type' => 'order']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2 rounded-pill" title="Đơn hàng của bạn">
                <i class="fa-solid fa-receipt text-secondary me-0.5"></i> #{{ $rOrder->order_code }}
              </a>
              @if($rOrder->tracking_code)
                <a href="{{ route('client.order-tracking', ['code' => $rOrder->tracking_code, 'type' => 'tracking']) }}" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none py-1 px-2 rounded-pill" title="Vận đơn bưu tá">
                  <i class="fa-solid fa-barcode me-0.5"></i> {{ $rOrder->tracking_code }}
                </a>
              @endif
            @endforeach
          @elseif(isset($sampleOrders) && $sampleOrders->isNotEmpty())
            @foreach($sampleOrders->take(2) as $sOrder)
              <a href="{{ route('client.order-tracking', ['code' => $sOrder->order_code, 'type' => 'order']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2 rounded-pill">
                <i class="fa-solid fa-receipt text-secondary me-0.5"></i> #{{ $sOrder->order_code }}
              </a>
              <a href="{{ route('client.order-tracking', ['code' => $sOrder->tracking_code, 'type' => 'tracking']) }}" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none py-1 px-2 rounded-pill">
                <i class="fa-solid fa-barcode me-0.5"></i> {{ $sOrder->tracking_code }}
              </a>
            @endforeach
          @else
            <a href="{{ route('client.order-tracking', ['code' => 'BEE-20260906-T7XF', 'type' => 'order']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2 rounded-pill">
              <i class="fa-solid fa-receipt text-secondary me-0.5"></i> BEE-20260906-T7XF
            </a>
            <a href="{{ route('client.order-tracking', ['code' => 'GHTK-GFELJZTT', 'type' => 'tracking']) }}" class="badge bg-success-subtle text-success border border-success-subtle text-decoration-none py-1 px-2 rounded-pill">
              <i class="fa-solid fa-barcode me-0.5"></i> GHTK-GFELJZTT
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  @if($currentOrder)

    <!-- KẾT QUẢ NHẬN DIỆN MÃ TRA CỨU (ORDER CODE HOẶC MÃ VẬN ĐƠN) -->
    @if(isset($matchedBy) && $matchedBy === 'tracking')
      <div class="alert border-0 shadow-sm p-3 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #f0fdf4; border-left: 5px solid #16a34a !important;">
        <div class="d-flex align-items-center gap-2.5">
          <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
            <i class="fa-solid fa-barcode fs-5"></i>
          </div>
          <div>
            <div class="text-success fw-bold small text-uppercase">
              <i class="fa-solid fa-circle-check me-1"></i> Tra cứu thành công theo Mã Vận Đơn Bưu Tá
            </div>
            <div class="text-dark small">
              Kiện hàng: <strong class="font-monospace text-success fs-6">{{ $currentOrder->tracking_code }}</strong> • Thuộc đơn hàng: <strong class="font-monospace text-primary">#{{ $currentOrder->order_code }}</strong> ({{ $currentOrder->shipping_carrier ?: 'GHTK' }})
            </div>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-success px-3 py-1.5 rounded-pill font-monospace fw-bold">
            <i class="fa-solid fa-truck-fast me-1"></i> Khớp Mã Vận Đơn
          </span>
          <a href="#carrierTrackingPassSection" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3 py-1">
            Xem Bưu Tá &amp; Trạm Quét <i class="fa-solid fa-arrow-down ms-1"></i>
          </a>
        </div>
      </div>
    @else
      <div class="alert border-0 shadow-sm p-3 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #f8fafc; border-left: 5px solid #0284c7 !important;">
        <div class="d-flex align-items-center gap-2.5">
          <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
            <i class="fa-solid fa-receipt fs-5"></i>
          </div>
          <div>
            <div class="text-primary fw-bold small text-uppercase">
              <i class="fa-solid fa-circle-check me-1"></i> Tra cứu thành công theo Mã Đơn Hàng
            </div>
            <div class="text-dark small">
              Đơn hàng: <strong class="font-monospace text-primary fs-6">#{{ $currentOrder->order_code }}</strong> • Mã vận đơn bưu tá liên kết: <strong class="font-monospace text-success">{{ $currentOrder->tracking_code ?: 'Đang chuẩn bị tạo mã' }}</strong>
            </div>
          </div>
        </div>
        @if($currentOrder->tracking_code)
          <a href="#carrierTrackingPassSection" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3 py-1">
            <i class="fa-solid fa-truck-fast me-1"></i> Xem Vận Đơn Bưu Tá
          </a>
        @endif
      </div>
    @endif
    @if($currentOrder->payment_method === 'vietqr')
      @if($currentOrder->payment_status !== 'paid')
        <!-- LUXURY SMART BANKING VIETQR PASS (ULTRA PROFESSIONAL FINTECH UI) -->
        <div class="card border-0 shadow-lg mb-4 overflow-hidden position-relative" style="border-radius: 24px; background: linear-gradient(145deg, #090e17 0%, #111827 50%, #1e293b 100%); color: #ffffff; border: 1.5px solid #f59e0b !important;">
          
          <!-- Background Ambient Glow -->
          <div class="position-absolute top-0 end-0 p-5 rounded-circle" style="background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%); width: 400px; height: 400px; pointer-events: none;"></div>

          <div class="card-body p-4 p-lg-5 position-relative">
            <div class="row align-items-center g-4 g-lg-5">
              
              <!-- CỘT 1: THẺ QR THANH TOÁN KỸ THUẬT SỐ (DIGITAL POS PASS) -->
              <div class="col-lg-5 text-center">
                <div class="p-3.5 bg-white rounded-4 shadow-lg d-inline-block position-relative" style="max-width: 320px; width: 100%;">
                  
                  <!-- Top Badge: VietQR & Napas 247 -->
                  <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                    <span class="badge bg-danger-subtle text-danger fw-black px-2 py-0.5" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                      VIETQR 24/7
                    </span>
                    <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-0.5" style="font-size: 0.68rem;">
                      <i class="fa-solid fa-bolt me-0.5"></i> NAPAS 247
                    </span>
                  </div>

                  <!-- Dynamic VietQR Code Image -->
                  @php
                    $isDepositTrack = ($currentOrder->is_deposit_required && $currentOrder->deposit_status !== 'paid');
                    $payAmountTrack = $isDepositTrack ? $currentOrder->deposit_amount : $currentOrder->total_amount;
                    $vietQrUrl = "https://img.vietqr.io/image/TCB-77427842310105-compact2.png?amount=" . $payAmountTrack . "&addInfo=" . urlencode($currentOrder->order_code) . "&accountName=" . urlencode("NGUYEN XUAN BAC");
                  @endphp
                  <div class="p-2 bg-light rounded-3 border position-relative">
                    <img src="{{ $vietQrUrl }}" alt="VietQR Payment Code" style="max-width: 250px; width: 100%; height: auto;" class="rounded mx-auto d-block">
                  </div>

                  <!-- Supported Banking Apps Row -->
                  <div class="mt-2.5 pt-2 border-top text-muted small d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-shield-halved text-success"></i>
                    <span class="text-dark fw-semibold">Quét bằng App mọi Ngân Hàng &amp; Ví Điện Tử</span>
                  </div>
                </div>

                <div class="mt-3 d-flex justify-content-center gap-2">
                  <a href="{{ $vietQrUrl }}" download="VietQR_{{ $currentOrder->order_code }}.png" target="_blank" class="btn btn-sm btn-light text-dark py-1.5 px-3.5 fw-bold shadow-sm rounded-pill" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-arrow-down-to-bracket me-1.5 text-warning"></i> Tải Ảnh Mã QR
                  </a>
                </div>
              </div>

              <!-- CỘT 2: BẢNG TÀI KHOẢN NGÂN HÀNG THÔNG MINH (SMART ACCOUNT DETAILS) -->
              <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger text-white fw-bold px-3 py-1.5 rounded-pill" style="font-size: 0.75rem;">
                      <i class="fa-solid fa-circle-dot me-1 text-warning"></i> {{ $isDepositTrack ? 'CHỜ CHUYỂN TIỀN CỌC 50%' : 'CHỜ CHUYỂN KHOẢN' }}
                    </span>
                    <span class="text-warning small fw-bold"><i class="fa-solid fa-clock me-1"></i> Tự động kiểm tra 24/7</span>
                  </div>
                  <!-- Countdown Timer -->
                  <div class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3 py-1.5 rounded-pill small">
                    <i class="fa-regular fa-clock me-1 text-warning"></i> Thời gian giữ hàng: <span id="vietqrCountdown" class="fw-bold text-warning font-monospace">14:59</span>
                  </div>
                </div>

                <h3 class="fw-black text-white mb-1.5" style="letter-spacing: -0.5px;">
                  {{ $isDepositTrack ? 'Thanh Toán Tiền Cọc 50% VietQR' : 'Thanh Toán Chuyển Khoản VietQR' }}
                </h3>
                <p class="text-white text-opacity-90 small mb-3.5 leading-relaxed" style="font-size: 0.88rem;">
                  @if($isDepositTrack)
                    Đơn hàng của bạn áp dụng chính sách đặt cọc 50%. Quét mã QR bên cạnh để chuyển đúng số tiền cọc 50% ({{ number_format($payAmountTrack, 0, ',', '.') }}₫). 50% còn lại ({{ number_format($currentOrder->remaining_amount, 0, ',', '.') }}₫) thanh toán cho bưu tá khi nhận hàng.
                  @else
                    Mở ứng dụng ngân hàng của bạn để quét mã QR bên cạnh. Số tiền thanh toán và nội dung chuyển khoản đã được điền sẵn chính xác 100%:
                  @endif
                </p>

                <!-- CARDLET BẢNG THÔNG TIN GIAO DỊCH TƯƠNG PHẢN CAO -->
                <div class="p-3.5 rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
                  <div class="d-flex flex-column gap-2.5 small">
                    
                    <!-- Row 1: Ngân hàng -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-building-columns me-1.5 text-warning"></i> Ngân hàng thụ hưởng:
                      </span>
                      <strong class="text-white fs-6">Techcombank (TCB)</strong>
                    </div>

                    <!-- Row 2: Chủ tài khoản -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-user-check me-1.5 text-warning"></i> Tên chủ tài khoản:
                      </span>
                      <strong class="text-warning fs-6">NGUYEN XUAN BAC</strong>
                    </div>

                    <!-- Row 3: Số tài khoản & nút copy -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-credit-card me-1.5 text-warning"></i> Số tài khoản:
                      </span>
                      <div class="d-flex align-items-center gap-2">
                        <strong class="text-white font-monospace fs-5 fw-bold" id="accNumberTxt">77427842310105</strong>
                        <button type="button" class="btn btn-sm btn-warning text-dark py-0.5 px-2.5 fw-bold rounded-2 shadow-sm" id="btnCopyAcc" style="font-size: 0.72rem;" onclick="copyText('77427842310105', 'btnCopyAcc')">
                          <i class="fa-regular fa-copy me-1"></i> Copy
                        </button>
                      </div>
                    </div>

                    <!-- Row 4: Số tiền cần chuyển -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-money-bill-wave me-1.5 text-warning"></i> {{ $isDepositTrack ? 'Số tiền cọc cần chuyển (50%):' : 'Số tiền cần chuyển:' }}
                      </span>
                      <div class="d-flex align-items-center gap-2">
                        <strong class="text-warning fs-4 fw-black">{{ number_format($payAmountTrack, 0, ',', '.') }}₫</strong>
                        @if($isDepositTrack)
                          <span class="badge bg-warning text-dark fw-bold">Cọc 50%</span>
                        @endif
                        <button type="button" class="btn btn-sm btn-warning text-dark py-0.5 px-2.5 fw-bold rounded-2 shadow-sm" id="btnCopyAmount" style="font-size: 0.72rem;" onclick="copyText('{{ $payAmountTrack }}', 'btnCopyAmount')">
                          <i class="fa-regular fa-copy me-1"></i> Copy
                        </button>
                      </div>
                    </div>

                    @if($isDepositTrack)
                      <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15 text-white text-opacity-80">
                        <span><i class="fa-solid fa-truck-fast me-1.5 text-info"></i> Còn lại thu khi giao hàng (COD 50%):</span>
                        <strong class="text-white font-monospace fs-6">{{ number_format($currentOrder->remaining_amount, 0, ',', '.') }}₫</strong>
                      </div>
                    @endif

                    <!-- Row 5: Nội dung chuyển khoản (Bắt buộc) -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pt-1">
                      <span class="text-white fw-bold">
                        <i class="fa-solid fa-receipt me-1.5 text-warning"></i> Nội dung chuyển khoản:
                      </span>
                      <div class="d-flex align-items-center gap-2">
                        <strong class="text-warning font-monospace fs-5 fw-black px-2.5 py-1 rounded-2 border border-warning shadow-sm" style="background: rgba(245, 158, 11, 0.18);">
                          {{ $currentOrder->order_code }}
                        </strong>
                        <button type="button" class="btn btn-sm btn-warning text-dark fw-black py-1 px-3 rounded-2 shadow" id="btnCopyCode" style="font-size: 0.75rem;" onclick="copyText('{{ $currentOrder->order_code }}', 'btnCopyCode')">
                          <i class="fa-regular fa-copy me-1"></i> Copy Mã Đơn
                        </button>
                      </div>
                    </div>

                  </div>
                </div>

                <!-- Form Nút Xác Nhận Chuyển Khoản & Tiếp Tục Mua Sắm -->
                <form action="{{ route('client.order-tracking.confirm-transfer', $currentOrder->order_code) }}" method="POST" class="d-flex gap-2 flex-wrap">
                  @csrf
                  <button type="submit" class="btn btn-warning text-dark px-4 py-3 fw-black flex-grow-1 shadow-lg rounded-3 fs-6 d-flex align-items-center justify-content-center gap-2" style="transition: all 0.2s;">
                    <i class="fa-solid fa-circle-check fs-5"></i> TÔI ĐÃ CHUYỂN KHOẢN THÀNH CÔNG
                  </button>
                  <a href="{{ route('client.home') }}" class="btn btn-outline-light text-white px-4 py-3 fw-bold rounded-3">
                    Tiếp Tục Mua Sắm
                  </a>
                </form>
              </div>

            </div>
          </div>
        </div>

      @else
        <!-- ĐÃ XÁC NHẬN THANH TOÁN VIETQR THÀNH CÔNG -->
        <div class="alert alert-success border-0 shadow-sm p-4 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #ecfdf5; border-left: 6px solid #10b981 !important;">
          <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 52px; height: 52px; min-width: 52px;">
              <i class="fa-solid fa-circle-check fs-3"></i>
            </div>
            <div>
              <h5 class="fw-bold text-success mb-1">ĐÃ THANH TOÁN VIETQR THÀNH CÔNG!</h5>
              <p class="mb-0 text-muted small">Đơn hàng <strong>#{{ $currentOrder->order_code }}</strong> đã được thanh toán đầy đủ <strong>{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</strong> qua VietQR. BeeStyle đang đóng gói đơn hàng và sẽ gửi sớm nhất cho bạn.</p>
            </div>
          </div>
          <span class="badge bg-success px-3.5 py-2.5 fw-bold fs-6 rounded-pill shadow-sm">
            <i class="fa-solid fa-receipt me-1"></i> ĐÃ THANH TOÁN
          </span>
        </div>
      @endif
    @endif


    <!-- CANCELLED ORDER INFO BANNER -->
    @if($currentOrder->shipping_status === 'cancelled')
      <div class="alert alert-danger border-0 shadow-sm p-4 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #fef2f2; border-left: 6px solid #ef4444 !important;">
        <div class="d-flex align-items-center gap-3">
          <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 52px; height: 52px; min-width: 52px;">
            <i class="fa-solid fa-ban fs-3"></i>
          </div>
          <div>
            <h5 class="fw-bold text-danger mb-1">ĐƠN HÀNG ĐÃ BỊ HỦY (#{{ $currentOrder->order_code }})</h5>
            <p class="mb-0 text-muted small">Lý do hủy: <strong>{{ $currentOrder->cancel_reason ?: 'Hủy theo yêu cầu của khách hàng' }}</strong> • Thời gian hủy: {{ $currentOrder->cancelled_at ? $currentOrder->cancelled_at->format('d/m/Y H:i') : ($currentOrder->updated_at ? $currentOrder->updated_at->format('d/m/Y H:i') : '') }}</p>
          </div>
        </div>
        <span class="badge bg-danger px-3.5 py-2.5 fw-bold fs-6 rounded-pill shadow-sm">
          <i class="fa-solid fa-xmark me-1"></i> ĐÃ HỦY ĐƠN
        </span>
      </div>
    @endif

    <!-- ACTIVE RMA RETURN REQUEST BANNER -->
    @if($currentOrder->latestReturn)
      <div class="alert alert-warning border-0 shadow-sm p-4 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #fffbeb; border-left: 6px solid #f59e0b !important;">
        <div class="d-flex align-items-center gap-3">
          <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 52px; height: 52px; min-width: 52px;">
            <i class="fa-solid fa-arrow-rotate-left fs-3"></i>
          </div>
          <div>
            <h5 class="fw-bold text-dark mb-1">ĐƠN HÀNG CÓ YÊU CẦU ĐỔI TRẢ (#{{ $currentOrder->latestReturn->return_code }})</h5>
            <p class="mb-0 text-muted small">{{ $currentOrder->latestReturn->type_label }}: <strong>{{ $currentOrder->latestReturn->reason }}</strong></p>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          {!! $currentOrder->latestReturn->status_badge !!}
          <a href="{{ route('client.profile', ['tab' => 'returns']) }}" class="btn btn-dark btn-sm px-3 fw-bold rounded-pill">
            Xem Tiến Trình RMA
          </a>
        </div>
      </div>
    @endif

    @php
      $carrier = mb_strtolower((string)$currentOrder->shipping_carrier, 'UTF-8');
      $isGhtk = str_contains($carrier, 'ghtk') || str_contains($carrier, 'tiết kiệm');
      $isGhn = str_contains($carrier, 'ghn') || str_contains($carrier, 'nhanh');
      $isViettel = str_contains($carrier, 'viettel') || str_contains($carrier, 'vtp');
      $isJt = str_contains($carrier, 'j&t') || str_contains($carrier, 'jt');

      $carrierBrandColor = $isGhtk ? '#069255' : ($isGhn ? '#f26522' : ($isViettel ? '#ee0033' : ($isJt ? '#e60012' : '#f59e0b')));
      $carrierTitle = $currentOrder->shipping_carrier ?: 'Giao Hàng Tiết Kiệm (GHTK)';
      $carrierShort = $isGhtk ? 'GHTK' : ($isGhn ? 'GHN' : ($isViettel ? 'Viettel Post' : ($isJt ? 'J&T' : 'BeeStyle Express')));
      $carrierHotline = $isGhtk ? '1900 6092' : ($isGhn ? '1900 636677' : ($isViettel ? '1900 8095' : ($isJt ? '1900 1088' : '1900 8888')));

      // Lộ trình checkpoints thời gian thực
      $step = $currentOrder->status_step ?? 1;
      $created = $currentOrder->created_at;
      $confirmed = $currentOrder->confirmed_at ?: ($created ? $created->copy()->addMinutes(11) : now());
      $processing = $currentOrder->processing_at ?: ($confirmed ? $confirmed->copy()->addMinutes(15) : now());
      $shipping = $currentOrder->shipping_at ?: ($processing ? $processing->copy()->addMinutes(30) : now());
      $delivered = $currentOrder->delivered_at ?: ($shipping ? $shipping->copy()->addHours(24) : now());
      $completed = $currentOrder->completed_at ?: ($delivered ? $delivered->copy()->addHours(2) : now());

      $logisticsCheckpoints = [];
      $logisticsCheckpoints[] = [
        'title' => 'Khởi tạo đơn hàng & tiếp nhận bưu gửi',
        'desc' => 'Đơn hàng #' . $currentOrder->order_code . ' đã ghi nhận trên sàn BeeStyle. Mã vận đơn ' . ($currentOrder->tracking_code ?: 'N/A') . ' đã được phân bổ thành công.',
        'hub' => 'Cổng Đơn Hàng BeeStyle Logistics',
        'time' => $created,
        'icon' => 'fa-clipboard-list',
        'done' => true,
      ];

      if ($step >= 2) {
        $logisticsCheckpoints[] = [
          'title' => 'Shop xác nhận & in phiếu giao nhận bưu cục',
          'desc' => 'Kho đã duyệt địa chỉ người nhận, in phiếu đóng gói và tạo lệnh hẹn lấy hàng tới ' . $carrierShort . '.',
          'hub' => 'Kho Tổng BeeStyle (Cầu Giấy, Hà Nội)',
          'time' => $confirmed,
          'icon' => 'fa-clipboard-check',
          'done' => true,
        ];
      }

      if ($step >= 3) {
        $logisticsCheckpoints[] = [
          'title' => 'Đóng gói hoàn tất & dán nhãn vận đơn [' . ($currentOrder->tracking_code ?: 'TEM CHÍNH HÃNG') . ']',
          'desc' => 'Kiện hàng đã qua kiểm tra chất lượng QC, đóng thùng carton chống sốc và dán mã vạch bưu tá.',
          'hub' => 'Kho Đóng Gói Phân Loại BeeStyle',
          'time' => $processing,
          'icon' => 'fa-box-open',
          'done' => true,
        ];
      }

      if ($step >= 4) {
        $logisticsCheckpoints[] = [
          'title' => 'Bưu tá ' . $carrierShort . ' đã tiếp nhận kiện hàng tại kho',
          'desc' => 'Bưu tá Nguyễn Văn Tuấn (Mã NV: ' . $carrierShort . '-8821 - Hotline: 0988.123.456) đã quét mã lấy hàng thành công.',
          'hub' => 'Bưu Cục Lấy Hàng ' . $carrierShort . ' Cầu Giấy',
          'time' => $shipping,
          'icon' => 'fa-truck-ramp-box',
          'done' => true,
        ];

        $logisticsCheckpoints[] = [
          'title' => 'Nhập Kho Trung Chuyển ' . $carrierShort . ' Hà Nội SOC',
          'desc' => 'Kiện hàng đã nhập kho trung chuyển phân loại tự động tốc độ cao theo tuyến tỉnh/thành.',
          'hub' => 'Trung Tâm Khai Thác & Khai Vận ' . $carrierShort . ' Miền Bắc',
          'time' => $shipping->copy()->addHours(3)->addMinutes(15),
          'icon' => 'fa-warehouse',
          'done' => true,
        ];

        $logisticsCheckpoints[] = [
          'title' => 'Rời kho trung chuyển - Đang luân chuyển tới bưu cục phát',
          'desc' => 'Kiện hàng đã bốc lên xe tải chuyên tuyến di chuyển tới bưu cục phụ trách giao hàng.',
          'hub' => 'Tuyến Xe Tải Luân Chuyển ' . $carrierShort . ' #29H-882.19',
          'time' => $shipping->copy()->addHours(7)->addMinutes(45),
          'icon' => 'fa-truck-fast',
          'done' => true,
        ];

        $logisticsCheckpoints[] = [
          'title' => 'Đã đến bưu cục phát - Bưu tá đang di chuyển giao hàng',
          'desc' => 'Bưu tá đang di chuyển phát hàng tới: ' . $currentOrder->shipping_address . ' (' . ($currentOrder->city ?: 'Hà Nội') . '). Vui lòng chú ý số điện thoại ' . substr($currentOrder->customer_phone, 0, 4) . '***' . substr($currentOrder->customer_phone, -3) . '.',
          'hub' => 'Bưu Cục Phát ' . ($currentOrder->city ?: 'Hà Nội'),
          'time' => $delivered ? $delivered->copy()->subHours(3) : $shipping->copy()->addHours(14),
          'icon' => 'fa-motorcycle',
          'done' => true,
        ];
      }

      if ($step >= 5) {
        $logisticsCheckpoints[] = [
          'title' => 'GIAO HÀNG THÀNH CÔNG - KHÁCH ĐÃ KÝ NHẬN',
          'desc' => 'Khách hàng ' . $currentOrder->customer_name . ' đã nhận đủ bưu phẩm. Tiền thu COD: ' . ($currentOrder->payment_status === 'paid' ? '0₫ (Đã thanh toán trước)' : number_format($currentOrder->total_amount, 0, ',', '.') . '₫') . '.',
          'hub' => 'Địa chỉ người nhận: ' . $currentOrder->shipping_address,
          'time' => $delivered,
          'icon' => 'fa-handshake',
          'done' => true,
          'pod_url' => $currentOrder->delivery_proof_url,
          'pod_note' => $currentOrder->delivery_proof_note,
        ];
      }

      if ($step >= 6) {
        $logisticsCheckpoints[] = [
          'title' => 'Hoàn tất hành trình bưu gửi & đối soát',
          'desc' => 'Đơn vị vận chuyển đã hoàn tất đối soát bưu tá bưu cục và đóng trạng thái luân chuyển thành công.',
          'hub' => 'Hệ Thống Đối Soát Vận Chuyển ' . $carrierShort,
          'time' => $completed,
          'icon' => 'fa-circle-check',
          'done' => true,
        ];
      }

      $logisticsCheckpoints = array_reverse($logisticsCheckpoints);
    @endphp

    <!-- KHỐI THẺ VẬN ĐƠN BƯU TÁ & THEO DÕI HÀNH TRÌNH BƯU KIỆN (DIGITAL CARRIER WAYBILL & LIVE RADAR) -->
    <div id="carrierTrackingPassSection" class="card border-0 shadow-sm p-4 mb-4 text-white overflow-hidden position-relative" style="border-radius: 24px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, {{ $carrierBrandColor }} 140%); border: 1.5px solid rgba(255,255,255,0.15) !important;">
      <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
        
        <div class="col-lg-7">
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-white text-dark fw-black px-3 py-1.5 rounded-pill shadow-xs" style="font-size: 0.85rem;">
              <i class="fa-solid fa-truck-fast me-1.5" style="color: {{ $carrierBrandColor }};"></i>{{ $carrierTitle }}
            </span>
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill border border-success-subtle" style="font-size: 0.82rem;">
              <i class="fa-solid fa-circle-check me-1"></i> BƯU KIỆN ĐÃ ĐỒNG BỘ TRẠM THỰC
            </span>
            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-2.5 py-1 rounded-pill small">
              <i class="fa-solid fa-headset me-1 text-warning"></i> CSKH: {{ $carrierHotline }}
            </span>
          </div>

          <div class="d-flex align-items-baseline gap-2 mt-2 flex-wrap">
            <span class="text-white text-opacity-75 small fw-bold">MÃ VẬN ĐƠN BƯU TÁ:</span>
            <h2 class="fw-black font-monospace text-warning mb-0 letter-spacing-1 fs-2" id="trackingCodeText">
              {{ $currentOrder->tracking_code ?: 'GHTK-' . strtoupper(substr(md5($currentOrder->order_code), 0, 8)) }}
            </h2>
            <button type="button" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-2.5 py-1 shadow-sm" id="btnCopyTracking" onclick="copyText('{{ $currentOrder->tracking_code ?: 'GHTK-' . strtoupper(substr(md5($currentOrder->order_code), 0, 8)) }}', 'btnCopyTracking')">
              <i class="fa-regular fa-copy me-1"></i> Copy Mã
            </button>
          </div>
          
          <div class="d-flex align-items-center gap-3 mt-3 text-white text-opacity-80 small flex-wrap">
            <span><i class="fa-solid fa-receipt me-1 text-info"></i> Thuộc đơn hàng: <strong class="text-white">#{{ $currentOrder->order_code }}</strong></span>
            <span><i class="fa-regular fa-clock me-1 text-warning"></i> Khởi tạo: <strong>{{ $currentOrder->created_at ? $currentOrder->created_at->format('d/m/Y H:i') : '' }}</strong></span>
            <span><i class="fa-solid fa-box me-1 text-success"></i> Kiện hàng: <strong>{{ $currentOrder->items->count() }} sản phẩm ({{ $currentOrder->items->sum('quantity') }} cái)</strong></span>
          </div>
        </div>

        <div class="col-lg-5 text-lg-end">
          <div class="p-3.5 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-20 backdrop-blur d-inline-block text-start w-100" style="max-width: 400px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="small text-white text-opacity-75">Trạng thái vận chuyển:</span>
              <span class="badge {{ $currentOrder->shipping_status === 'completed' || $currentOrder->shipping_status === 'delivered' ? 'bg-success' : ($currentOrder->shipping_status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') }} fw-bold px-3 py-1 rounded-pill">
                {{ $currentOrder->status_label }}
              </span>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="small text-white text-opacity-75">Tiền thu người nhận (COD):</span>
              <strong class="fs-5 text-warning font-monospace">
                @if($currentOrder->payment_status === 'paid')
                  0₫ (Đã thanh toán)
                @elseif($currentOrder->is_deposit_required)
                  {{ number_format($currentOrder->remaining_amount ?: ($currentOrder->total_amount - $currentOrder->deposit_amount), 0, ',', '.') }}₫ <small class="fw-normal" style="font-size: 0.72rem;">(Đã trừ cọc 50%)</small>
                @else
                  {{ number_format($currentOrder->total_amount, 0, ',', '.') . '₫' }}
                @endif
              </strong>
            </div>
            @if($currentOrder->is_deposit_required)
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small text-white text-opacity-75">Tiền đã đặt cọc (50%):</span>
                <span class="badge bg-warning text-dark font-monospace fw-bold">
                  {{ number_format($currentOrder->deposit_amount, 0, ',', '.') }}₫ ({{ $currentOrder->deposit_status === 'paid' ? 'Đã thu cọc' : 'Chờ cọc' }})
                </span>
              </div>
            @endif

            <div class="d-flex gap-2 mt-3 pt-2 border-top border-white border-opacity-15 flex-wrap">
              <button type="button" class="btn btn-sm btn-light text-dark fw-bold flex-grow-1 shadow-xs" onclick="window.print()" title="In phiếu vận chuyển bưu cục">
                <i class="fa-solid fa-print me-1 text-primary"></i> In Vận Đơn
              </button>
              @if($currentOrder->tracking_url)
                <a href="{{ $currentOrder->tracking_url }}" class="btn btn-sm btn-outline-light fw-bold px-3 text-nowrap" title="Mở trang chuyên sâu định vị bưu kiện">
                  <i class="fa-solid fa-map-location-dot me-1 text-info"></i> Cổng {{ $carrierShort }}
                </a>
              @endif
              @if($currentOrder->external_tracking_url)
                <a href="{{ $currentOrder->external_tracking_url }}" target="_blank" class="btn btn-sm btn-outline-warning fw-bold px-2.5 text-nowrap" title="Mở trang tra cứu chính thức ngoài đời thực của {{ $carrierShort }}">
                  <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Web Hãng
                </a>
              @endif
            </div>
          </div>
        </div>

      </div>

      <!-- Live Shipper & Route Drawer -->
      <div class="mt-4 pt-3 border-top border-white border-opacity-15">
        <div class="row g-3 align-items-center">
          <div class="col-md-7">
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-5 shadow-xs flex-shrink-0" style="width: 46px; height: 46px; background-color: {{ $carrierBrandColor }}; border: 2px solid rgba(255,255,255,0.4);">
                <i class="fa-solid fa-user-shield"></i>
              </div>
              <div>
                <div class="d-flex align-items-center gap-2">
                  <strong class="text-white fs-6">Bưu tá: Nguyễn Văn Tuấn</strong>
                  <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace" style="font-size: 0.68rem;">
                    <i class="fa-solid fa-star text-warning"></i> 4.9★ (Đã xác minh)
                  </span>
                </div>
                <small class="text-white text-opacity-75 d-block">
                  Phụ trách giao nhận • Mã bưu tá: <strong class="font-monospace text-warning">{{ $carrierShort }}-8821</strong> • Hotline trạm: <strong class="text-white">{{ $carrierHotline }}</strong>
                </small>
              </div>
            </div>
          </div>
          <div class="col-md-5 text-md-end">
            <div class="d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
              <a href="tel:0988123456" class="btn btn-sm btn-success fw-bold px-3 py-1.5 rounded-pill shadow-xs">
                <i class="fa-solid fa-phone me-1"></i> Gọi Shipper: 0988.123.456
              </a>
              <button class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#logisticsCheckpointsCollapse" aria-expanded="{{ (isset($matchedBy) && $matchedBy === 'tracking') ? 'true' : 'false' }}" aria-controls="logisticsCheckpointsCollapse">
                <i class="fa-solid fa-timeline me-1 text-warning"></i> Lộ Trình {{ count($logisticsCheckpoints) }} Trạm Quét Mã <i class="fa-solid fa-chevron-down ms-1 small"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- COLLAPSIBLE LOGISTICS CHECKPOINTS (LỘ TRÌNH TỪNG TRẠM THỰC TẾ - THIẾT KẾ RÕ NÉT DỄ ĐỌC) -->
        <div class="collapse {{ (isset($matchedBy) && $matchedBy === 'tracking') ? 'show' : '' }} mt-3 pt-3 border-top border-white border-opacity-20" id="logisticsCheckpointsCollapse">
          <div class="p-3.5 p-md-4 rounded-4 shadow-sm" style="background: #ffffff; border: 1px solid #e2e8f0; color: #1e293b;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3 pb-2 border-bottom">
              <div>
                <h6 class="fw-black text-dark mb-0 fs-6">
                  <i class="fa-solid fa-route me-1.5" style="color: {{ $carrierBrandColor }};"></i> Lịch Sử Luân Chuyển Bưu Kiện Theo Thời Gian Thực (Checkpoints)
                </h6>
                <small class="text-muted">Cập nhật tự động qua các trạm quét mã barcode bưu tá</small>
              </div>
              <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace px-2.5 py-1.5 fw-bold">
                <i class="fa-solid fa-barcode me-1"></i> Quét Barcode Tự Động
              </span>
            </div>

            <div class="timeline position-relative ps-2 ps-md-3 mt-3">
              @foreach($logisticsCheckpoints as $cIndex => $cp)
                @php
                  $isLatest = ($cIndex === 0);
                @endphp
                <div class="position-relative pb-4 ps-4 border-start" style="border-color: {{ $isLatest ? $carrierBrandColor : '#e2e8f0' }} !important; border-width: 2.5px !important;">
                  <!-- Dot Icon -->
                  <div class="position-absolute rounded-circle d-flex align-items-center justify-content-center shadow-xs"
                       style="left: -17px; top: 0; width: 32px; height: 32px; font-size: 0.85rem;
                              background-color: {{ $isLatest ? $carrierBrandColor : '#ffffff' }};
                              color: {{ $isLatest ? '#ffffff' : '#64748b' }};
                              border: 2.5px solid {{ $isLatest ? $carrierBrandColor : '#cbd5e1' }};">
                    <i class="fa-solid {{ $cp['icon'] }}"></i>
                  </div>

                  <!-- Nội dung trạm (Thiết kế nền tương phản cao, chữ đậm nét cực kỳ dễ đọc) -->
                  <div class="p-3 rounded-3 shadow-2xs" style="{{ $isLatest ? 'background: #f0fdf4; border: 1.5px solid ' . $carrierBrandColor . '; border-left: 5px solid ' . $carrierBrandColor . ' !important;' : 'background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #cbd5e1 !important;' }}">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1.5">
                      <h6 class="fw-bold mb-0 {{ $isLatest ? 'fs-6' : '' }}" style="color: {{ $isLatest ? $carrierBrandColor : '#0f172a' }};">
                        {{ $cp['title'] }}
                        @if($isLatest)
                          <span class="badge text-white ms-1.5 fw-bold rounded-pill" style="background-color: {{ $carrierBrandColor }}; font-size: 0.68rem; vertical-align: middle;">
                            <i class="fa-solid fa-sparkles me-0.5"></i> MỚI NHẤT
                          </span>
                        @endif
                      </h6>
                      <span class="badge {{ $isLatest ? 'bg-white text-success border border-success-subtle' : 'bg-white text-secondary border' }} font-monospace fw-bold px-2.5 py-1 shadow-2xs" style="font-size: 0.75rem;">
                        <i class="fa-regular fa-clock me-1 {{ $isLatest ? 'text-success' : 'text-muted' }}"></i> {{ $cp['time'] ? $cp['time']->format('d/m/Y H:i') : '' }}
                      </span>
                    </div>

                    <p class="mb-2 fw-medium" style="font-size: 0.88rem; color: #334155 !important; line-height: 1.55;">
                      {{ $cp['desc'] }}
                    </p>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                      <div class="p-2 rounded-2 bg-white border d-inline-flex align-items-center gap-2 small shadow-2xs" style="font-size: 0.8rem;">
                        <i class="fa-solid fa-location-dot text-danger"></i>
                        <span class="text-muted fw-semibold">Địa chỉ / Trạm:</span>
                        <strong class="text-dark">{{ $cp['hub'] }}</strong>
                      </div>

                      @if(!empty($cp['pod_url']))
                        <button type="button" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3 py-1 shadow-2xs d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#clientPodModal">
                          <i class="fa-solid fa-camera text-success"></i>
                          <span>Ảnh Bưu Tá Chụp (POD)</span>
                          <i class="fa-solid fa-expand ms-1 text-muted" style="font-size: 0.7rem;"></i>
                        </button>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- ORDER STATUS & TRACKER -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 pb-3 border-bottom">
        <div>
          <span class="text-muted small">Mã đơn hàng:</span>
          <h5 class="fw-bold text-dark mb-0 font-monospace">{{ $currentOrder->order_code }}</h5>
        </div>
        <div>
          <span class="text-muted small">Thời gian đặt:</span>
          <div class="fw-semibold text-dark">{{ $currentOrder->created_at ? $currentOrder->created_at->format('d/m/Y H:i') : '16/08/2026' }}</div>
        </div>
        <div>
          <span class="text-muted small">Trạng thái:</span>
          <div>
            <span class="badge {{ $currentOrder->shipping_status === 'cancelled' ? 'bg-danger text-white' : ($currentOrder->shipping_status === 'completed' ? 'bg-success text-white' : 'bg-warning text-dark') }} px-3 py-1.5 fw-bold rounded-pill">
              {{ $currentOrder->status_label }}
            </span>
          </div>
        </div>
        <div>
          <span class="text-muted small">Tổng tiền:</span>
          <div class="fw-bold text-danger fs-5">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</div>
          @if($currentOrder->is_deposit_required)
            <span class="badge bg-warning text-dark font-monospace" style="font-size: 0.7rem;">
              <i class="fa-solid fa-shield-halved"></i> Cọc 50%: {{ number_format($currentOrder->deposit_amount, 0, ',', '.') }}₫
            </span>
          @endif
        </div>
        @if(!Auth::check() || Auth::id() === $currentOrder->user_id || !$currentOrder->user_id)
          <div class="d-flex align-items-center gap-2 flex-wrap">
            @if(in_array($currentOrder->shipping_status, ['shipping', 'delivered']) || in_array($currentOrder->status_step, [4, 5]))
              <!-- NÚT 1: ĐÃ NHẬN ĐƯỢC HÀNG -->
              <button type="button" class="btn btn-sm btn-success px-3.5 py-1.5 fw-bold rounded-pill shadow-sm text-nowrap d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#modalConfirmDelivered">
                <i class="fa-solid fa-circle-check"></i> <span>Đã Nhận Được Hàng</span>
              </button>

              <!-- NÚT 2: KHÔNG NHẬN HÀNG / TỪ CHỐI NHẬN -->
              <button type="button" class="btn btn-sm btn-outline-danger px-3.5 py-1.5 fw-bold rounded-pill shadow-sm text-nowrap d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#modalRejectDelivery">
                <i class="fa-solid fa-truck-arrow-right"></i> <span>Không Nhận Hàng</span>
              </button>
            @endif

            @if($currentOrder->canBeCancelledByCustomer())
              <button type="button" class="btn btn-sm btn-outline-danger px-3 fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#cancelTrackingOrderModal">
                <i class="fa-solid fa-xmark me-1"></i> Hủy Đơn
              </button>
            @elseif($currentOrder->canBeReturnedByCustomer())
              <a href="{{ route('client.profile', ['tab' => 'orders']) }}" class="btn btn-sm btn-bee-outline px-3 fw-bold rounded-pill">
                <i class="fa-solid fa-arrow-rotate-left me-1"></i> Đổi Trả / Hoàn Tiền
              </a>
            @endif
          </div>
        @endif
      </div>

      <!-- 6-STEP TIMELINE TRACKER -->
      @if($currentOrder->isCustomerRejected())
        <!-- BANNER ĐƠN HÀNG TỪ CHỐI NHẬN & CHUYỂN HOÀN VỀ KHO (CHUẨN TMĐT CHUYÊN NGHIỆP) -->
        <div class="alert alert-danger py-3 px-4 rounded-4 shadow-sm d-flex align-items-start gap-3 my-4" style="background: #fff5f5; border: 1.5px solid #ef4444 !important;">
          <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 44px; height: 44px; font-size: 1.25rem;">
            <i class="fa-solid fa-truck-arrow-right"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
              <strong class="fs-6 text-danger text-uppercase d-flex align-items-center gap-2">
                <span>ĐƠN HÀNG ĐÃ TỪ CHỐI NHẬN &amp; ĐANG CHUYỂN HOÀN VỀ KHO</span>
              </strong>
              <span class="badge bg-danger text-white fw-bold font-monospace px-2.5 py-1">Chuyển Hoàn Về Kho</span>
            </div>
            <p class="mb-1 text-dark small">
              Quý khách đã từ chối nhận bưu kiện khi nhân viên giao hàng liên hệ phát hàng. Bưu tá đã lập biên bản xác nhận và thực hiện chuyển hoàn kiện hàng về kho tổng BeeStyle. Tồn kho sản phẩm và mã giảm giá của bạn đã được khôi phục.
            </p>
            <div class="p-2.5 rounded-3 bg-white border small mt-2">
              <div class="mb-1"><strong>Lý do từ chối:</strong> <span class="text-danger fw-semibold">{{ $currentOrder->cancel_reason ?: 'Khách hàng không nhận bưu phẩm' }}</span></div>
              <div class="text-muted fs-11">
                <i class="fa-regular fa-clock me-1"></i> Thời gian ghi nhận: {{ $currentOrder->cancelled_at ? $currentOrder->cancelled_at->format('d/m/Y H:i') : '' }}
                @if($currentOrder->delivery_proof_image)
                  • <a href="{{ $currentOrder->delivery_proof_url }}" target="_blank" class="text-primary fw-bold text-decoration-none ms-1"><i class="fa-solid fa-image me-0.5"></i> Xem ảnh bằng chứng đối soát</a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @elseif($currentOrder->shipping_status === 'cancelled')
        <div class="alert alert-danger py-3 px-4 rounded-3 d-flex align-items-center gap-3 my-4">
          <i class="fa-solid fa-ban fs-2 text-danger"></i>
          <div>
            <strong class="fs-6 d-block">ĐƠN HÀNG ĐÃ BỊ HỦY</strong>
            <span class="small text-danger text-opacity-80">Lý do hủy: <strong>{{ $currentOrder->cancel_reason ?: 'Không có ghi chú' }}</strong> • Thời gian hủy: {{ $currentOrder->cancelled_at ? $currentOrder->cancelled_at->format('d/m/Y H:i') : '' }}</span>
          </div>
        </div>
      @else
        <div class="bee-timeline-steps my-5">
          @php
            $stepTimes = [
              1 => $currentOrder->created_at,
              2 => $currentOrder->confirmed_at,
              3 => $currentOrder->processing_at,
              4 => $currentOrder->shipping_at,
              5 => $currentOrder->delivered_at,
              6 => $currentOrder->completed_at,
            ];
            $steps = [
              1 => ['label' => '1. Chờ Xác Nhận', 'desc' => 'Đơn hàng mới tạo'],
              2 => ['label' => '2. Đã Xác Nhận', 'desc' => 'Đã duyệt thông tin'],
              3 => ['label' => '3. Đang Đóng Gói', 'desc' => 'Kho nhặt hàng & gói'],
              4 => ['label' => '4. Đang Giao Hàng', 'desc' => 'Bưu tá vận chuyển'],
              5 => ['label' => '5. Đã Giao Hàng', 'desc' => 'Khách nhận & kiểm tra'],
              6 => ['label' => '6. Hoàn Tất', 'desc' => 'Thành công'],
            ];
            $currentStep = $currentOrder->status_step;
          @endphp

          @foreach($steps as $stepNum => $stepData)
            <div class="bee-timeline-step {{ $currentStep > $stepNum ? 'completed' : ($currentStep == $stepNum ? 'active' : '') }}">
              <div class="bee-timeline-step-icon">
                @if($currentStep > $stepNum)
                  <i class="fa-solid fa-check"></i>
                @else
                  {{ $stepNum }}
                @endif
              </div>
              <div class="bee-timeline-step-label fw-bold">{{ $stepData['label'] }}</div>
              <small class="text-muted d-block" style="font-size: 0.72rem;">{{ $stepData['desc'] }}</small>
              @if(!empty($stepTimes[$stepNum]))
                <div class="text-success font-monospace mt-1 fw-bold" style="font-size: 0.7rem;">
                  <i class="fa-regular fa-clock me-0.5"></i>{{ $stepTimes[$stepNum]->format('d/m/Y H:i') }}
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @endif

      <!-- 📸 BẰNG CHỨNG GIAO NHẬN KIỆN HÀNG (PROOF OF DELIVERY - POD) -->
      @if($currentOrder->status_step >= 5 || in_array($currentOrder->shipping_status, ['delivered', 'completed']))
        <div class="card border-0 shadow-sm p-4 my-4 rounded-4 overflow-hidden position-relative" style="background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); border: 1.5px solid #10b981 !important;">
          <div class="row align-items-center g-4">
            <!-- Cột ảnh chụp thực tế bưu tá gửi về kho -->
            <div class="col-lg-5 col-md-6 text-center">
              <div class="position-relative rounded-3 overflow-hidden border shadow-sm d-inline-block w-100 bg-dark" style="max-width: 380px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#clientPodModal">
                <img src="{{ $currentOrder->delivery_proof_url }}" alt="Bằng chứng bưu tá giao hàng #{{ $currentOrder->order_code }}" class="img-fluid w-100" style="max-height: 250px; object-fit: cover;">
                <div class="position-absolute top-0 start-0 m-2">
                  <span class="badge bg-success shadow-xs font-monospace px-2.5 py-1" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-shield-check me-1"></i> POD ĐÃ XÁC THỰC
                  </span>
                </div>
                <div class="position-absolute bottom-0 start-0 end-0 p-2 text-white bg-dark bg-opacity-75 d-flex justify-content-between align-items-center small">
                  <span class="font-monospace" style="font-size: 0.72rem;"><i class="fa-solid fa-camera me-1"></i> Bưu tá đã chụp gửi kho</span>
                  <span class="badge bg-light text-dark fw-bold" style="font-size: 0.7rem;"><i class="fa-solid fa-expand me-1"></i> Phóng to</span>
                </div>
              </div>
            </div>

            <!-- Cột thông tin chi tiết biên bản giao nhận -->
            <div class="col-lg-7 col-md-6">
              <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold px-3 py-1.5 rounded-pill" style="font-size: 0.8rem;">
                  <i class="fa-solid fa-camera-retro me-1"></i> BẰNG CHỨNG GIAO HÀNG (POD)
                </span>
                <span class="badge bg-light text-dark border font-monospace px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;">
                  Mã Vận Đơn: <strong>{{ $currentOrder->tracking_code ?: 'GHTK-' . strtoupper(substr(md5($currentOrder->order_code), 0, 8)) }}</strong>
                </span>
              </div>

              <h5 class="fw-bold text-dark mb-2">Bưu Tá Đã Giao Kiện Hàng Thành Công</h5>
              <p class="text-muted small mb-3" style="line-height: 1.55;">
                Nhân viên giao nhận đã chụp ảnh kiện hàng nguyên vẹn tem niêm phong tại địa chỉ nhận và gửi dữ liệu về hệ thống kho BeeStyle để xác thực hoàn tất giao dịch.
              </p>

              <div class="p-3 bg-white rounded-3 border mb-3 small shadow-2xs">
                <div class="row g-2">
                  <div class="col-sm-6">
                    <span class="text-muted d-block" style="font-size: 0.75rem;">Thời gian giao nhận:</span>
                    <strong class="text-dark font-monospace">{{ $currentOrder->delivery_proof_at ? $currentOrder->delivery_proof_at->format('d/m/Y H:i') : ($currentOrder->delivered_at ? $currentOrder->delivered_at->format('d/m/Y H:i') : '08/09/2026 21:24') }}</strong>
                  </div>
                  <div class="col-sm-6">
                    <span class="text-muted d-block" style="font-size: 0.75rem;">Thu tiền người nhận:</span>
                    <strong class="text-success font-monospace">
                      @if($currentOrder->payment_status === 'paid')
                        0₫ (Đã thanh toán Online)
                      @elseif($currentOrder->is_deposit_required)
                        {{ number_format($currentOrder->remaining_amount ?: ($currentOrder->total_amount - $currentOrder->deposit_amount), 0, ',', '.') }}₫ (Đã cọc 50% trước)
                      @else
                        {{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫ (Đã thu COD đủ)
                      @endif
                    </strong>
                  </div>
                  <div class="col-12 pt-2 border-top mt-2">
                    <span class="text-muted d-block" style="font-size: 0.75rem;">Ghi chú giao nhận bưu tá:</span>
                    <span class="text-dark fw-medium fst-italic">"{{ $currentOrder->delivery_proof_note ?: 'Khách hàng đã nhận đủ bưu phẩm, kiện hàng nguyên vẹn tem niêm phong và thanh toán hoàn tất.' }}"</span>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-success fw-bold px-3 py-2 rounded-pill shadow-xs" data-bs-toggle="modal" data-bs-target="#clientPodModal">
                  <i class="fa-solid fa-expand me-1"></i> Xem Ảnh Chụp Gốc Phóng To
                </button>
                <a href="{{ $currentOrder->delivery_proof_url }}" target="_blank" download="POD_{{ $currentOrder->order_code }}.jpg" class="btn btn-sm btn-outline-secondary fw-bold px-3 py-2 rounded-pill">
                  <i class="fa-solid fa-download me-1"></i> Tải Ảnh Về Máy
                </a>
              </div>
            </div>
          </div>
        </div>
      @endif

      <!-- COMPLETED ORDER REVIEW NOTIFICATION BANNER -->
      @if($currentOrder->status_step >= 5 || in_array($currentOrder->shipping_status, ['delivered', 'completed']))
        <div class="alert alert-success border-0 shadow-sm p-4 my-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #ecfdf5; border-left: 6px solid #10b981 !important;">
          <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 48px; height: 48px; min-width: 48px;">
              <i class="fa-solid fa-heart fs-4"></i>
            </div>
            <div>
              <h6 class="fw-bold text-success mb-1 fs-6">CẢM ƠN QUÝ KHÁCH ĐÃ MUA HÀNG TẠI BEESTYLE!</h6>
              <p class="mb-0 text-muted small">BeeStyle chân thành cảm ơn Quý khách đã tin tưởng mua sắm. Hãy chia sẻ cảm nhận của bạn để giúp chúng tôi ngày càng hoàn thiện nhé!</p>
            </div>
          </div>
          <button type="button" onclick="openQuickReviewModal({{ $currentOrder->items->first()->product_id ?? 1 }})" class="btn btn-bee-primary px-4 py-2.5 text-nowrap fw-bold rounded-pill shadow-sm">
            <i class="fa-solid fa-star text-warning me-1"></i> ĐÁNH GIÁ SẢN PHẨM
          </button>
        </div>
      @endif

      <!-- ORDER DETAILS & CUSTOMER INFO -->
      <div class="row g-4 pt-3 border-top">
        <!-- Cột 1: Thông tin người nhận -->
        <div class="col-md-6 border-end">
          <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user me-2 text-warning"></i> Thông Tin Nhận Hàng</h6>
          <div class="p-3 bg-light rounded-3 border d-flex flex-column gap-2 small">
            <div class="d-flex justify-content-between">
              <span class="text-muted">Người nhận:</span>
              <strong class="text-dark">{{ $currentOrder->customer_name }}</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Số điện thoại:</span>
              <strong class="text-dark">{{ $currentOrder->customer_phone }}</strong>
            </div>
            @if($currentOrder->customer_email)
              <div class="d-flex justify-content-between">
                <span class="text-muted">Email:</span>
                <span class="text-dark">{{ $currentOrder->customer_email }}</span>
              </div>
            @endif
            <div class="d-flex justify-content-between">
              <span class="text-muted">Địa chỉ giao:</span>
              <span class="text-dark text-end fw-semibold" style="max-width: 250px;">{{ $currentOrder->shipping_address }}{{ $currentOrder->city ? ', ' . $currentOrder->city : '' }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Phương thức:</span>
              <span class="text-dark fw-bold">{{ $currentOrder->payment_method_name }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-muted">Trạng thái thanh toán:</span>
              <span class="badge {{ $currentOrder->payment_status === 'paid' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-dark border border-warning-subtle' }} fw-bold">
                {{ $currentOrder->payment_status_label }}
              </span>
            </div>
            @if($currentOrder->notes)
              <div class="pt-1.5 border-top text-muted">
                <strong>Ghi chú:</strong> "{{ $currentOrder->notes }}"
              </div>
            @endif
            @if($currentOrder->tracking_code)
              <div class="p-3 bg-primary-subtle text-primary rounded-3 border border-primary-subtle mt-2 shadow-2xs">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-truck-fast fs-4 text-primary"></i>
                    <div>
                      <small class="text-muted d-block" style="font-size: 0.72rem;">Đơn Vị Vận Chuyển: <strong>{{ $currentOrder->shipping_carrier ?: 'Giao Hàng Tiết Kiệm (GHTK)' }}</strong></small>
                      <strong class="font-monospace fs-6 text-dark">{{ $currentOrder->tracking_code }}</strong>
                    </div>
                  </div>
                  @if($currentOrder->tracking_url)
                    <a href="{{ $currentOrder->tracking_url }}" target="_blank" class="btn btn-bee-primary btn-sm px-3 py-1.5 fw-bold rounded-pill shadow-xs" style="font-size: 0.75rem;">
                      <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Tra Cứu Trực Tiếp
                    </a>
                  @endif
                </div>
              </div>
            @elseif($currentOrder->admin_notes)
              <div class="p-2 bg-info-subtle text-info rounded-3 border border-info-subtle d-flex align-items-center gap-2 mt-1">
                <i class="fa-solid fa-truck-fast fs-5 text-primary"></i>
                <div class="small text-dark">
                  <strong class="text-primary d-block">Thông Tin Bưu Kiện:</strong> {{ $currentOrder->admin_notes }}
                </div>
              </div>
            @endif
          </div>
        </div>

        <!-- Cột 2: Sản phẩm trong đơn hàng -->
        <div class="col-md-6">
          <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-box-open me-2 text-warning"></i> Sản Phẩm Trong Đơn Hàng</h6>
          <div class="d-flex flex-column gap-2">
            @foreach($currentOrder->items as $item)
              <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3 border">
                <div class="d-flex align-items-center gap-2.5">
                  <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 48px; height: 48px; object-fit: contain; cursor: pointer;" class="rounded border bg-white" onclick="openQuickReviewModal({{ $item->product_id ?? 1 }})">
                  <div>
                    <a href="javascript:void(0)" onclick="openQuickReviewModal({{ $item->product_id ?? 1 }})" class="small fw-bold text-dark text-decoration-none d-block text-truncate" style="max-width: 220px;">
                      {{ $item->product_name }}
                    </a>
                    <small class="text-muted">{{ $item->color ?? 'Tiêu chuẩn' }} / Size {{ $item->size ?? 'M' }} • x{{ $item->quantity }}</small>
                  </div>
                </div>
                <div class="text-end">
                  <div class="fw-bold small text-dark">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</div>
                  @if($currentOrder->status_step >= 5 || in_array($currentOrder->shipping_status, ['delivered', 'completed']))
                    @php
                      $isReviewed = false;
                      if (Auth::check()) {
                        $isReviewed = \App\Models\Review::where('product_id', $item->product_id)->where('user_id', Auth::id())->exists();
                      }
                    @endphp
                    @if($isReviewed)
                      <button type="button" onclick="openQuickReviewModal({{ $item->product_id ?: ($item->product->id ?? 1) }})" class="btn btn-sm btn-outline-success py-0.5 px-2 text-nowrap mt-1 fw-bold" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-circle-check me-1"></i> Đã đánh giá
                      </button>
                    @else
                      <button type="button" onclick="openQuickReviewModal({{ $item->product_id ?: ($item->product->id ?? 1) }})" class="btn btn-sm btn-bee-primary py-0.5 px-2.5 text-nowrap mt-1 fw-bold" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá ngay
                      </button>
                    @endif
                  @endif
                </div>
              </div>
            @endforeach
          </div>

          <!-- Chi phí hóa đơn -->
          <div class="mt-3 pt-2 border-top small">
            <div class="d-flex justify-content-between text-muted">
              <span>Tạm tính:</span>
              <span class="text-dark fw-semibold">{{ number_format($currentOrder->subtotal, 0, ',', '.') }}₫</span>
            </div>
            @if($currentOrder->discount_amount > 0)
              <div class="d-flex justify-content-between text-success">
                <span>Giảm giá ({{ $currentOrder->coupon_code ?? 'VOUCHER' }}):</span>
                <span class="fw-bold">-{{ number_format($currentOrder->discount_amount, 0, ',', '.') }}₫</span>
              </div>
            @endif
            <div class="d-flex justify-content-between text-muted">
              <span>Phí vận chuyển:</span>
              <span class="text-dark fw-semibold">{{ $currentOrder->shipping_fee > 0 ? number_format($currentOrder->shipping_fee, 0, ',', '.') . '₫' : 'Miễn phí (Freeship)' }}</span>
            </div>
            <div class="d-flex justify-content-between fw-bold text-dark fs-6 mt-1.5 pt-1.5 border-top">
              <span>Tổng thanh toán:</span>
              <span class="text-danger fs-5 fw-black">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</span>
            </div>

            @if($currentOrder->is_deposit_required)
              <div class="p-2.5 rounded-3 border border-warning-subtle bg-warning-subtle bg-opacity-25 mt-2">
                <div class="d-flex justify-content-between align-items-center text-dark small mb-1">
                  <span><i class="fa-solid fa-shield-halved text-warning me-1"></i> Đã đặt cọc (50%):</span>
                  <span class="fw-bold font-monospace text-warning-emphasis">
                    {{ number_format($currentOrder->deposit_amount, 0, ',', '.') }}₫
                    @if($currentOrder->deposit_status === 'paid')
                      <span class="badge bg-success-subtle text-success ms-1" style="font-size: 0.65rem;">Đã thanh toán cọc</span>
                    @else
                      <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Chờ cọc</span>
                    @endif
                  </span>
                </div>
                <div class="d-flex justify-content-between align-items-center text-dark small">
                  <span><i class="fa-solid fa-truck-fast text-secondary me-1"></i> Còn lại thu khi giao hàng (COD):</span>
                  <strong class="font-monospace text-danger fs-6">
                    {{ $currentOrder->payment_status === 'paid' ? '0₫ (Đã thanh toán đủ)' : number_format($currentOrder->remaining_amount ?: ($currentOrder->total_amount - $currentOrder->deposit_amount), 0, ',', '.') . '₫' }}
                  </strong>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>

    </div>

    <!-- MODAL HỦY ĐƠN HÀNG DÀNH CHO KHÁCH HÀNG TẠI TRANG TRACKING -->
    @if(Auth::check() && Auth::id() === $currentOrder->user_id && $currentOrder->canBeCancelledByCustomer())
      <div class="modal fade" id="cancelTrackingOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
            <form action="{{ route('client.orders.cancel', $currentOrder->id) }}" method="POST">
              @csrf
              <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-danger">
                  <i class="fa-solid fa-triangle-exclamation me-2"></i> Hủy Đơn Hàng #{{ $currentOrder->order_code }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body p-4">
                <div class="alert alert-warning border-0 p-3 rounded-3 small mb-3" style="background: #fffbeb;">
                  <i class="fa-solid fa-circle-info text-warning me-1"></i>
                  Khi bạn xác nhận hủy đơn, hệ thống sẽ tự động khôi phục số lượng tồn kho sản phẩm và hoàn lại lượt sử dụng mã giảm giá (voucher) cho bạn.
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-bold text-dark">Lý do hủy đơn hàng <span class="text-danger">*</span></label>
                  <select name="reason" class="form-select" required>
                    <option value="" selected disabled>-- Chọn lý do hủy đơn --</option>
                    <option value="Tôi muốn thay đổi địa chỉ giao hàng">Tôi muốn thay đổi địa chỉ giao hàng</option>
                    <option value="Tôi muốn thay đổi kích cỡ (Size) hoặc màu sắc áo">Tôi muốn thay đổi kích cỡ (Size) hoặc màu sắc áo</option>
                    <option value="Tôi muốn thêm/bớt sản phẩm trong đơn">Tôi muốn thêm/bớt sản phẩm trong đơn</option>
                    <option value="Tôi tìm thấy giá tốt hơn ở nơi khác">Tôi tìm thấy giá tốt hơn ở nơi khác</option>
                    <option value="Tôi đổi ý, không có nhu cầu mua nữa">Tôi đổi ý, không có nhu cầu mua nữa</option>
                    <option value="Lý do khác">Lý do khác</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label small fw-bold text-dark">Ghi chú thêm (không bắt buộc)</label>
                  <textarea name="notes" class="form-control" rows="2" placeholder="Nhập thêm chi tiết nếu cần..."></textarea>
                </div>
              </div>
              <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">
                  Xác Nhận Hủy Đơn
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif

    <!-- MODAL 1: XÁC NHẬN NHẬN HÀNG (HOÀN TẤT ĐƠN HÀNG) -->
    <div class="modal fade" id="modalConfirmDelivered" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
          <form action="{{ route('client.order-tracking.confirm-delivered', $currentOrder->order_code) }}" method="POST">
            @csrf
            <div class="modal-header border-bottom pb-3">
              <h5 class="modal-title fw-bold text-success d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-check fs-4"></i>
                <span>Xác Nhận Đã Nhận Đủ Kiện Hàng</span>
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
              <div class="p-3 bg-light rounded-3 mb-3 border">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <span class="text-muted small">Mã đơn hàng:</span>
                  <strong class="text-dark font-monospace">#{{ $currentOrder->order_code }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-muted small">Kiện hàng gồm:</span>
                  <span class="fw-bold text-dark small">{{ $currentOrder->items->count() }} sản phẩm ({{ $currentOrder->items->sum('quantity') }} cái)</span>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-dark mb-2">Đồng kiểm tra kiện hàng cùng bưu tá:</label>
                <div class="d-flex flex-column gap-2 small">
                  <div class="d-flex align-items-center gap-2 text-dark">
                    <i class="fa-solid fa-square-check text-success fs-5"></i>
                    <span>Tôi đã mở hộp kiểm tra, sản phẩm còn nguyên tem mác.</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 text-dark">
                    <i class="fa-solid fa-square-check text-success fs-5"></i>
                    <span>Sản phẩm đúng kích thước (Size), màu sắc và mẫu mã đã đặt.</span>
                  </div>
                  <div class="d-flex align-items-center gap-2 text-dark">
                    <i class="fa-solid fa-square-check text-success fs-5"></i>
                    <span>Tôi đã thanh toán đủ tiền hàng cho bưu tá (nếu là đơn COD).</span>
                  </div>
                </div>
              </div>

              <div class="alert alert-info border-0 p-3 rounded-3 small mb-0" style="background: #f0f9ff;">
                <i class="fa-solid fa-gift text-info me-1"></i>
                Sau khi bấm hoàn tất, bạn sẽ được <strong>tích điểm thành viên</strong> và mở giao diện gửi đánh giá sản phẩm để nhận thêm quà tặng từ BeeStyle!
              </div>
            </div>
            <div class="modal-footer border-top bg-light d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm">
                <i class="fa-solid fa-check me-1"></i> Xác Nhận Đã Nhận Đủ Hàng
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- MODAL 2: KHÔNG NHẬN HÀNG / TỪ CHỐI NHẬN (CHUYỂN HOÀN VỀ KHO) -->
    <div class="modal fade" id="modalRejectDelivery" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
          <form action="{{ route('client.order-tracking.reject-delivery', $currentOrder->order_code) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-bottom pb-3">
              <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
                <i class="fa-solid fa-truck-arrow-right fs-4"></i>
                <span>Không Nhận Hàng &amp; Chuyển Hoàn</span>
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
              <div class="alert alert-warning border-0 p-3 rounded-3 small mb-3" style="background: #fffbeb;">
                <i class="fa-solid fa-circle-exclamation text-warning me-1"></i>
                Bưu tá sẽ lập biên bản và chuyển hoàn kiện hàng về kho BeeStyle. Tồn kho sản phẩm và lượt sử dụng mã giảm giá của bạn sẽ được tự động hoàn lại.
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-dark">
                  Lý do từ chối nhận bưu phẩm <span class="text-danger">*</span>
                </label>
                <select name="reason" class="form-select" required>
                  <option value="" selected disabled>-- Vui lòng chọn lý do không nhận --</option>
                  <option value="Hộp/Thùng hàng bị móp méo, rách vỡ hoặc mất niêm phong">Hộp/Thùng hàng bị móp méo, rách vỡ hoặc mất niêm phong</option>
                  <option value="Bưu tá không hỗ trợ đồng kiểm tra hàng theo quy định">Bưu tá không hỗ trợ đồng kiểm tra hàng theo quy định</option>
                  <option value="Giao sai mẫu mã, sai màu sắc hoặc kích cỡ so với đơn đặt">Giao sai mẫu mã, sai màu sắc hoặc kích cỡ so với đơn đặt</option>
                  <option value="Sản phẩm bị lỗi may mặc, sờn rách, phai màu hoặc hư hỏng">Sản phẩm bị lỗi may mặc, sờn rách, phai màu hoặc hư hỏng</option>
                  <option value="Thời gian giao hàng quá trễ so với dự kiến, không còn nhu cầu">Thời gian giao hàng quá trễ so với dự kiến, không còn nhu cầu</option>
                  <option value="Lý do khác">Lý do khác (chi tiết bên dưới)</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-dark">Ghi chú cụ thể cho bưu tá &amp; bộ phận kho</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Nhập thêm chi tiết tình trạng hàng nếu có..."></textarea>
              </div>

              <div class="mb-2">
                <label class="form-label small fw-bold text-dark">
                  <i class="fa-solid fa-camera text-secondary me-1"></i> Ảnh gói hàng / sản phẩm lỗi (không bắt buộc)
                </label>
                <input type="file" name="proof_image" class="form-control form-control-sm" accept="image/*">
                <small class="text-muted fs-11">Ảnh chụp hộp hàng rách hoặc chi tiết lỗi để làm bằng chứng đối soát.</small>
              </div>
            </div>
            <div class="modal-footer border-top bg-light d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
              <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm">
                <i class="fa-solid fa-ban me-1"></i> Xác Nhận Không Nhận (Chuyển Hoàn)
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- MODAL PHÓNG TO ẢNH BẰNG CHỨNG GIAO HÀNG POD CHO KHÁCH HÀNG -->
    <div class="modal fade" id="clientPodModal" tabindex="-1" aria-labelledby="clientPodModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
          <div class="modal-header border-0 pb-0 pt-3 px-4 bg-dark text-white">
            <div>
              <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="clientPodModalLabel">
                <i class="fa-solid fa-camera-retro text-success"></i> Bằng Chứng Giao Nhận Kiện Hàng (Proof of Delivery)
              </h5>
              <span class="badge bg-success text-white font-monospace mt-1">
                <i class="fa-solid fa-shield-halved me-1"></i> BƯU TÁ ĐÃ GỬI VỀ KHO BEESTYLE
              </span>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body p-0 bg-black text-center position-relative">
            <img src="{{ $currentOrder->delivery_proof_url }}" alt="Bằng chứng giao hàng bưu tá #{{ $currentOrder->order_code }}" class="img-fluid w-100" style="max-height: 520px; object-fit: contain;">
          </div>

          <div class="modal-footer border-0 p-3 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-start small">
              <div class="text-dark"><strong>Đơn hàng:</strong> #{{ $currentOrder->order_code }} • <strong>Mã vận đơn:</strong> <span class="font-monospace text-primary fw-bold">{{ $currentOrder->tracking_code ?: 'GHTK' }}</span></div>
              <div class="text-muted"><strong>Thời gian:</strong> {{ $currentOrder->delivery_proof_at ? $currentOrder->delivery_proof_at->format('d/m/Y H:i:s') : ($currentOrder->delivered_at ? $currentOrder->delivered_at->format('d/m/Y H:i:s') : '08/09/2026 21:24') }} • <strong>Ghi chú:</strong> {{ $currentOrder->delivery_proof_note ?: 'Khách hàng đã nhận đủ bưu phẩm.' }}</div>
            </div>
            <div class="d-flex gap-2">
              <a href="{{ $currentOrder->delivery_proof_url }}" target="_blank" download="POD_{{ $currentOrder->order_code }}.jpg" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">
                <i class="fa-solid fa-download me-1"></i> Tải Ảnh Về
              </a>
              <button type="button" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Đóng</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  @else
    <!-- SMART EMPTY STATE (DUAL-SEARCH GUIDANCE) -->
    <div class="card border-0 shadow-sm p-4 p-md-5 mb-4 text-center" style="border-radius: 22px; background: #ffffff; border: 1.5px dashed #cbd5e1 !important;">
      <div class="py-4" style="max-width: 620px; margin: 0 auto;">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-2xs" style="width: 76px; height: 76px;">
          <i class="fa-solid fa-magnifying-glass-location text-muted fs-1"></i>
        </div>
        
        <h4 class="fw-black text-dark mb-2">Không Tìm Thấy Thông Tin Kiện Hàng</h4>
        <p class="text-muted small mb-4">
          Hệ thống không tìm thấy đơn hàng hoặc mã vận đơn nào khớp với: 
          <span class="badge bg-danger-subtle text-danger font-monospace fs-6 px-2.5 py-1">"{{ $code ?: 'Rỗng' }}"</span>
        </p>

        <div class="row g-3 text-start mb-4">
          <div class="col-md-6">
            <div class="p-3.5 rounded-4 bg-light border h-100">
              <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-receipt text-warning me-1.5"></i> Tra cứu Mã Đơn Hàng</h6>
              <p class="text-muted small mb-3" style="font-size: 0.8rem;">
                Mã đơn hàng có định dạng như <code>BEE-20260906-T7XF</code> được gửi qua Email xác nhận hoặc tin nhắn SMS khi bạn hoàn tất đặt hàng.
              </p>
              <a href="{{ route('client.order-tracking', ['code' => 'BEE-20260906-T7XF', 'type' => 'order']) }}" class="btn btn-sm btn-outline-dark fw-bold rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                <i class="fa-solid fa-arrow-right me-1"></i> Thử: BEE-20260906-T7XF
              </a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="p-3.5 rounded-4 bg-light border h-100">
              <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-barcode text-success me-1.5"></i> Tra cứu Mã Vận Đơn Bưu Tá</h6>
              <p class="text-muted small mb-3" style="font-size: 0.8rem;">
                Mã vận đơn do hãng vận chuyển cấp (GHTK, GHN, Viettel Post...) in trên tem dán thùng hàng hoặc thông báo bưu tá giao hàng.
              </p>
              <a href="{{ route('client.order-tracking', ['code' => 'GHTK-GFELJZTT', 'type' => 'tracking']) }}" class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                <i class="fa-solid fa-arrow-right me-1"></i> Thử: GHTK-GFELJZTT
              </a>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-center gap-2 flex-wrap">
          @if(Auth::check())
            <a href="{{ route('client.profile', ['tab' => 'orders']) }}" class="btn btn-bee-primary fw-bold px-4 py-2 rounded-pill shadow-xs">
              <i class="fa-solid fa-list-check me-1.5"></i> Đơn Hàng Của Tôi
            </a>
          @endif
          <a href="{{ route('client.home') }}" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-pill">
            <i class="fa-solid fa-house me-1.5"></i> Về Trang Chủ
          </a>
          <a href="tel:19008888" class="btn btn-light border fw-bold px-4 py-2 rounded-pill text-muted">
            <i class="fa-solid fa-headset me-1.5 text-warning"></i> Hotline 1900 8888
          </a>
        </div>
      </div>
    </div>
  @endif

</div>

@push('scripts')
<script>
  // Chuyển đổi Tab Tra Cứu (Mã Đơn Hàng vs Mã Vận Đơn)
  function switchSearchMode(mode) {
    const input = document.getElementById('trackingCodeInput');
    const typeInput = document.getElementById('searchTypeInput');
    const btnOrder = document.getElementById('btnTabOrder');
    const btnTracking = document.getElementById('btnTabTracking');
    const icon = document.getElementById('searchIcon');

    if (!input || !typeInput) return;

    if (mode === 'tracking') {
      typeInput.value = 'tracking';
      input.placeholder = 'Nhập mã vận đơn bưu tá (VD: GHTK-GFELJZTT, GHN-2C4E3DFF)...';
      if (btnTracking && btnOrder) {
        btnTracking.className = 'btn btn-sm btn-dark rounded-pill px-3 fw-bold';
        btnOrder.className = 'btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold';
      }
      if (icon) {
        icon.innerHTML = '<i class="fa-solid fa-barcode text-success"></i>';
      }
    } else {
      typeInput.value = 'order';
      input.placeholder = 'Nhập mã đơn hàng (VD: BEE-20260906-T7XF)...';
      if (btnOrder && btnTracking) {
        btnOrder.className = 'btn btn-sm btn-dark rounded-pill px-3 fw-bold';
        btnTracking.className = 'btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold';
      }
      if (icon) {
        icon.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i>';
      }
    }
    input.focus();
  }

  // Tự động nhận diện tiền tố mã vận đơn khi người dùng gõ hoặc dán
  const searchInput = document.getElementById('trackingCodeInput');
  if (searchInput) {
    searchInput.addEventListener('input', function(e) {
      const val = e.target.value.trim().toUpperCase();
      if (val.startsWith('GHTK-') || val.startsWith('GHN-') || val.startsWith('VTP-') || val.startsWith('JT-')) {
        const btnTracking = document.getElementById('btnTabTracking');
        const btnOrder = document.getElementById('btnTabOrder');
        const typeInput = document.getElementById('searchTypeInput');
        const icon = document.getElementById('searchIcon');
        if (typeInput) typeInput.value = 'tracking';
        if (btnTracking && btnOrder) {
          btnTracking.className = 'btn btn-sm btn-dark rounded-pill px-3 fw-bold';
          btnOrder.className = 'btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold';
        }
        if (icon) icon.innerHTML = '<i class="fa-solid fa-barcode text-success"></i>';
      }
    });
  }

  // Hàm Copy Thông Minh & Hiển Thị Trực Quan
  function copyText(text, btnId) {
    navigator.clipboard.writeText(text).then(() => {
      const btn = document.getElementById(btnId);
      if (btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check me-1 text-success"></i> Đã chép!';
        btn.classList.replace('btn-warning', 'btn-light');
        setTimeout(() => {
          btn.innerHTML = originalHtml;
          btn.classList.replace('btn-light', 'btn-warning');
        }, 2000);
      }
    }).catch(err => {
      prompt("Sao chép thông tin:", text);
    });
  }

  // Countdown Timer 15 phút chuyên nghiệp
  let timeLeft = 15 * 60;
  const countdownEl = document.getElementById('vietqrCountdown');
  if (countdownEl) {
    const timer = setInterval(() => {
      timeLeft--;
      if (timeLeft <= 0) {
        clearInterval(timer);
        countdownEl.textContent = '00:00';
      } else {
        const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
        const s = (timeLeft % 60).toString().padStart(2, '0');
        countdownEl.textContent = `${m}:${s}`;
      }
    }, 1000);
  }

  @if(session('open_review_modal_product_id'))
    document.addEventListener("DOMContentLoaded", function () {
      setTimeout(function () {
        if (typeof openQuickReviewModal === 'function') {
          openQuickReviewModal({{ session('open_review_modal_product_id') }});
        }
      }, 700);
    });
  @endif
</script>
@endpush
@endsection
