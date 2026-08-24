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

  <!-- SEARCH ORDER BOX (MODERN LUXURY HEADER) -->
  <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0 !important;">
    <div class="row align-items-center g-3">
      <div class="col-lg-6">
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">ĐƠN HÀNG</span>
          <h4 class="fw-bold text-dark mb-0">Tra Cứu &amp; Thanh Toán Trực Tuyến</h4>
        </div>
        <p class="text-muted small mb-0">Theo dõi tiến trình vận chuyển theo thời gian thực và quét mã VietQR tự động</p>
      </div>
      <div class="col-lg-6">
        <form action="{{ route('client.order-tracking') }}" method="GET" class="d-flex gap-2">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">
              <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input type="text" name="code" value="{{ $code ?? '' }}" class="form-control border-start-0 ps-0" placeholder="Nhập mã đơn hàng (VD: BEE-2026-0816-01)..." required>
          </div>
          <button type="submit" class="btn btn-bee-primary px-4 text-nowrap fw-bold shadow-sm">
            Tra Cứu
          </button>
        </form>
      </div>
    </div>
  </div>

  @if($currentOrder)

    <!-- KHỐI THÔNG TIN THANH TOÁN TƯƠNG ỨNG VỚI PHƯƠNG THỨC -->
    @if(in_array($currentOrder->payment_method, ['online', 'momo', 'zalopay', 'vietqr', 'vnpay']))
      @if($currentOrder->payment_status !== 'paid')
        <!-- CARD THANH TOÁN ONLINE / MOMO / ZALOPAY -->
        @php
          $isMomo = $currentOrder->payment_method === 'momo';
          $isZalo = $currentOrder->payment_method === 'zalopay';
          $cardBorder = $isMomo ? '#d82d8b' : ($isZalo ? '#008fe5' : '#f59e0b');
          $methodTitle = $isMomo ? 'Thanh Toán Qua Ví MoMo' : ($isZalo ? 'Thanh Toán Qua Ví ZaloPay' : 'Thanh Toán Online (ATM / Internet Banking / Visa)');
          $methodDesc = $isMomo ? 'Mở ứng dụng ví MoMo để chuyển tiền thanh toán đơn hàng với thông tin bên dưới:' : ($isZalo ? 'Mở ứng dụng ví ZaloPay hoặc app Zalo để thanh toán đơn hàng:' : 'Vui lòng chuyển khoản trực tuyến qua Internet Banking với thông tin bên dưới:');
          $badgeLabel = $isMomo ? 'VÍ MOMO' : ($isZalo ? 'VÍ ZALOPAY' : 'ONLINE BANKING');
          $badgeColor = $isMomo ? 'background-color: #d82d8b;' : ($isZalo ? 'background-color: #008fe5;' : 'background-color: #f59e0b; color: #111827;');
        @endphp
        <div class="card border-0 shadow-lg mb-4 overflow-hidden position-relative" style="border-radius: 24px; background: linear-gradient(145deg, #090e17 0%, #111827 50%, #1e293b 100%); color: #ffffff; border: 1.5px solid {{ $cardBorder }} !important;">
          
          <div class="card-body p-4 p-lg-5 position-relative">
            <div class="row align-items-center g-4 g-lg-5">
              
              <!-- CỘT 1: BIỂU TƯỢNG VÀ TÓM TẮT PHƯƠNG THỨC -->
              <div class="col-lg-5 text-center">
                <div class="p-4 bg-white rounded-4 shadow-lg d-inline-block position-relative" style="max-width: 320px; width: 100%;">
                  
                  <div class="mb-3">
                    <span class="badge text-white fw-bold px-3 py-1.5 rounded-pill shadow-xs" style="{{ $badgeColor }} font-size: 0.85rem;">
                      {{ $badgeLabel }}
                    </span>
                  </div>

                  <div class="p-4 bg-light rounded-3 border text-center my-2">
                    @if($isMomo)
                      <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-2 shadow-sm" style="width: 72px; height: 72px; background-color: #d82d8b; font-size: 2rem;">
                        <i class="fa-solid fa-wallet"></i>
                      </div>
                      <h6 class="fw-bold text-dark mb-1">Ví Điện Tử MoMo</h6>
                      <span class="text-muted small">Hotline: 0988.889.999</span>
                    @elseif($isZalo)
                      <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white mb-2 shadow-sm" style="width: 72px; height: 72px; background-color: #008fe5; font-size: 2rem;">
                        <i class="fa-solid fa-wallet"></i>
                      </div>
                      <h6 class="fw-bold text-dark mb-1">Ví Điện Tử ZaloPay</h6>
                      <span class="text-muted small">Hotline: 0988.889.999</span>
                    @else
                      <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-dark mb-2 shadow-sm" style="width: 72px; height: 72px; background-color: #f59e0b; font-size: 2rem;">
                        <i class="fa-solid fa-credit-card"></i>
                      </div>
                      <h6 class="fw-bold text-dark mb-1">Thanh Toán Trực Tuyến</h6>
                      <span class="text-muted small">MB Bank / Thẻ ATM / Visa</span>
                    @endif
                  </div>

                  <div class="mt-2 text-muted small">
                    <i class="fa-solid fa-shield-halved text-success me-1"></i> Giao dịch bảo mật 100%
                  </div>
                </div>
              </div>

              <!-- CỘT 2: BẢNG THÔNG TIN THANH TOÁN -->
              <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                  <span class="badge bg-danger text-white fw-bold px-3 py-1.5 rounded-pill" style="font-size: 0.75rem;">
                    <i class="fa-solid fa-circle-dot me-1 text-warning"></i> CHỜ THANH TOÁN
                  </span>
                  <div class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3 py-1.5 rounded-pill small">
                    <i class="fa-regular fa-clock me-1 text-warning"></i> Thời gian giữ hàng: <span class="fw-bold text-warning font-monospace">14:59</span>
                  </div>
                </div>

                <h3 class="fw-black text-white mb-1.5" style="letter-spacing: -0.5px;">{{ $methodTitle }}</h3>
                <p class="text-white text-opacity-90 small mb-3.5 leading-relaxed" style="font-size: 0.88rem;">
                  {{ $methodDesc }}
                </p>

                <!-- THÔNG TIN TÀI KHOẢN / VÍ NHẬN TIỀN -->
                <div class="p-3.5 rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
                  <div class="d-flex flex-column gap-2.5 small">
                    
                    @if($isMomo || $isZalo)
                      <!-- Ví & Số Điện Thoại -->
                      <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                        <span class="text-white text-opacity-80 fw-semibold">
                          <i class="fa-solid fa-phone me-1.5 text-warning"></i> Số tài khoản ví / Ngân hàng liên kết:
                        </span>
                        <div class="d-flex align-items-center gap-2">
                          <strong class="text-white font-monospace fs-5 fw-bold" id="accNumberTxt">77427842310105</strong>
                          <button type="button" class="btn btn-sm btn-warning text-dark py-0.5 px-2.5 fw-bold rounded-2 shadow-sm" id="btnCopyAcc" style="font-size: 0.72rem;" onclick="copyText('77427842310105', 'btnCopyAcc')">
                            <i class="fa-regular fa-copy me-1"></i> Copy
                          </button>
                        </div>
                      </div>
                    @else
                      <!-- Ngân hàng & STK -->
                      <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                        <span class="text-white text-opacity-80 fw-semibold">
                          <i class="fa-solid fa-building-columns me-1.5 text-warning"></i> Ngân hàng thụ hưởng:
                        </span>
                        <strong class="text-white fs-6">Techcombank (Ngân Hàng Kỹ Thương)</strong>
                      </div>
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
                    @endif

                    <!-- Tên người nhận -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-user-check me-1.5 text-warning"></i> Người nhận tiền:
                      </span>
                      <strong class="text-warning fs-6">NGUYEN XUAN BAC</strong>
                    </div>


                    <!-- Số tiền -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-money-bill-wave me-1.5 text-warning"></i> Số tiền cần thanh toán:
                      </span>
                      <div class="d-flex align-items-center gap-2">
                        <strong class="text-warning fs-4 fw-black">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</strong>
                        <button type="button" class="btn btn-sm btn-warning text-dark py-0.5 px-2.5 fw-bold rounded-2 shadow-sm" id="btnCopyAmount" style="font-size: 0.72rem;" onclick="copyText('{{ $currentOrder->total_amount }}', 'btnCopyAmount')">
                          <i class="fa-regular fa-copy me-1"></i> Copy
                        </button>
                      </div>
                    </div>

                    <!-- Nội dung chuyển khoản -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pt-1">
                      <span class="text-white fw-bold">
                        <i class="fa-solid fa-receipt me-1.5 text-warning"></i> Lời nhắn / Nội dung:
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

                <!-- Form Nút Xác Nhận Thanh Toán & Mở Cổng Gateway -->
                <div class="d-flex flex-column gap-2">
                  @if($currentOrder->payment_method === 'momo')
                    <a href="{{ route('client.checkout.momo', $currentOrder->order_code) }}" class="btn text-white px-4 py-3 fw-black shadow-lg rounded-3 fs-6 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #d82d8b, #a50064);">
                      <i class="fa-solid fa-wallet fs-5"></i> MỞ CỔNG THANH TOÁN MOMO GATEWAY
                    </a>
                  @elseif($currentOrder->payment_method === 'zalopay')
                    <a href="{{ route('client.checkout.zalopay', $currentOrder->order_code) }}" class="btn text-white px-4 py-3 fw-black shadow-lg rounded-3 fs-6 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #008fe5, #0056b3);">
                      <i class="fa-solid fa-wallet fs-5"></i> MỞ CỔNG THANH TOÁN ZALOPAY GATEWAY
                    </a>
                  @elseif($currentOrder->payment_method === 'online')
                    <a href="{{ route('client.checkout.online', $currentOrder->order_code) }}" class="btn text-white px-4 py-3 fw-black shadow-lg rounded-3 fs-6 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #0284c7, #1e3a8a);">
                      <i class="fa-solid fa-credit-card fs-5"></i> MỞ CỔNG THANH TOÁN NAPAS 247 GATEWAY
                    </a>
                  @endif

                  <form action="{{ route('client.order-tracking.confirm-transfer', $currentOrder->order_code) }}" method="POST" class="d-flex gap-2 flex-wrap">
                    @csrf
                    <button type="submit" class="btn btn-warning text-dark px-4 py-2.5 fw-black flex-grow-1 shadow-md rounded-3 fs-6 d-flex align-items-center justify-content-center gap-2">
                      <i class="fa-solid fa-circle-check fs-5"></i> TÔI ĐÃ HOÀN TẤT THANH TOÁN
                    </button>
                    <a href="{{ route('client.home') }}" class="btn btn-outline-light text-white px-4 py-2.5 fw-bold rounded-3">
                      Tiếp Tục Mua Sắm
                    </a>
                  </form>
                </div>

              </div>

            </div>
          </div>
        </div>

      @else
        <!-- ĐÃ XÁC NHẬN THANH TOÁN THÀNH CÔNG -->
        <div class="alert alert-success border-0 shadow-sm p-4 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #ecfdf5; border-left: 6px solid #10b981 !important;">
          <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 52px; height: 52px; min-width: 52px;">
              <i class="fa-solid fa-circle-check fs-3"></i>
            </div>
            <div>
              <h5 class="fw-bold text-success mb-1">ĐÃ THANH TOÁN {{ mb_strtoupper($currentOrder->payment_method_name) }} THÀNH CÔNG!</h5>
              <p class="mb-0 text-muted small">Đơn hàng <strong>#{{ $currentOrder->order_code }}</strong> đã được thanh toán đầy đủ <strong>{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</strong>. BeeStyle đang chuẩn bị đơn hàng và sẽ gửi sớm nhất cho bạn.</p>
            </div>
          </div>
          <span class="badge bg-success px-3.5 py-2.5 fw-bold fs-6 rounded-pill shadow-sm">
            <i class="fa-solid fa-receipt me-1"></i> ĐÃ THANH TOÁN
          </span>
        </div>
      @endif
    @endif


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
            <span class="badge bg-warning text-dark px-3 py-1.5 fw-bold rounded-pill">
              {{ $currentOrder->status_label }}
            </span>
          </div>
        </div>
        <div>
          <span class="text-muted small">Tổng tiền:</span>
          <div class="fw-bold text-danger fs-5">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</div>
        </div>
      </div>

      <!-- 6-STEP TIMELINE TRACKER -->
      <div class="bee-timeline-steps my-5">
        @php
          $steps = [
            1 => 'Chờ xác nhận',
            2 => 'Đã xác nhận',
            3 => 'Đang đóng gói',
            4 => 'Đang giao hàng',
            5 => 'Đã giao hàng',
            6 => 'Hoàn tất'
          ];
          $currentStep = $currentOrder->status_step;
        @endphp

        @foreach($steps as $stepNum => $stepLabel)
          <div class="bee-timeline-step {{ $currentStep > $stepNum ? 'completed' : ($currentStep == $stepNum ? 'active' : '') }}">
            <div class="bee-timeline-step-icon">
              @if($currentStep > $stepNum)
                <i class="fa-solid fa-check"></i>
              @else
                {{ $stepNum }}
              @endif
            </div>
            <div class="bee-timeline-step-label">{{ $stepLabel }}</div>
          </div>
        @endforeach
      </div>

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
            @if($currentOrder->admin_notes)
              <div class="p-2 bg-info-subtle text-info rounded-3 border border-info-subtle d-flex align-items-center gap-2 mt-1">
                <i class="fa-solid fa-truck-fast fs-5 text-primary"></i>
                <div class="small text-dark">
                  <strong class="text-primary d-block">Vận Đơn Giao Hàng:</strong> {{ $currentOrder->admin_notes }}
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
          </div>
        </div>
      </div>

    </div>
  @else
    <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: 20px; background: #ffffff;">
      <i class="fa-solid fa-magnifying-glass fs-1 text-muted mb-3"></i>
      <h5 class="fw-bold text-dark">Không tìm thấy đơn hàng</h5>
      <p class="text-muted small">Vui lòng kiểm tra lại mã đơn hàng chính xác hoặc liên hệ hotline 1900 8888 để được hỗ trợ.</p>
    </div>
  @endif

</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection
