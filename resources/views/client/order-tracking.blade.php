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

    <!-- KHỐI THANH TOÁN VIETQR TỰ ĐỘNG KHI CHỌN PHƯƠNG THỨC CHUYỂN KHOẢN -->
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
                    $vietQrUrl = "https://img.vietqr.io/image/MB-0988889999-compact2.png?amount=" . $currentOrder->total_amount . "&addInfo=" . urlencode($currentOrder->order_code) . "&accountName=" . urlencode("BEESTYLE MENSWEAR");
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
                      <i class="fa-solid fa-circle-dot me-1 text-warning"></i> CHỜ CHUYỂN KHOẢN
                    </span>
                    <span class="text-warning small fw-bold"><i class="fa-solid fa-clock me-1"></i> Tự động kiểm tra 24/7</span>
                  </div>
                  <!-- Countdown Timer -->
                  <div class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3 py-1.5 rounded-pill small">
                    <i class="fa-regular fa-clock me-1 text-warning"></i> Thời gian giữ hàng: <span id="vietqrCountdown" class="fw-bold text-warning font-monospace">14:59</span>
                  </div>
                </div>

                <h3 class="fw-black text-white mb-1.5" style="letter-spacing: -0.5px;">Thanh Toán Chuyển Khoản VietQR</h3>
                <p class="text-white text-opacity-90 small mb-3.5 leading-relaxed" style="font-size: 0.88rem;">
                  Mở ứng dụng ngân hàng của bạn để quét mã QR bên cạnh. Số tiền thanh toán và nội dung chuyển khoản đã được điền sẵn chính xác 100%:
                </p>

                <!-- CARDLET BẢNG THÔNG TIN GIAO DỊCH TƯƠNG PHẢN CAO -->
                <div class="p-3.5 rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
                  <div class="d-flex flex-column gap-2.5 small">
                    
                    <!-- Row 1: Ngân hàng -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-building-columns me-1.5 text-warning"></i> Ngân hàng thụ hưởng:
                      </span>
                      <strong class="text-white fs-6">MB Bank (Ngân Hàng TMCP Quân Đội)</strong>
                    </div>

                    <!-- Row 2: Chủ tài khoản -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-user-check me-1.5 text-warning"></i> Tên chủ tài khoản:
                      </span>
                      <strong class="text-warning fs-6">BEESTYLE MENSWEAR</strong>
                    </div>

                    <!-- Row 3: Số tài khoản & nút copy -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-credit-card me-1.5 text-warning"></i> Số tài khoản:
                      </span>
                      <div class="d-flex align-items-center gap-2">
                        <strong class="text-white font-monospace fs-5 fw-bold" id="accNumberTxt">0988889999</strong>
                        <button type="button" class="btn btn-sm btn-warning text-dark py-0.5 px-2.5 fw-bold rounded-2 shadow-sm" id="btnCopyAcc" style="font-size: 0.72rem;" onclick="copyText('0988889999', 'btnCopyAcc')">
                          <i class="fa-regular fa-copy me-1"></i> Copy
                        </button>
                      </div>
                    </div>

                    <!-- Row 4: Số tiền cần chuyển -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 pb-2 border-bottom border-white border-opacity-15">
                      <span class="text-white text-opacity-80 fw-semibold">
                        <i class="fa-solid fa-money-bill-wave me-1.5 text-warning"></i> Số tiền cần chuyển:
                      </span>
                      <div class="d-flex align-items-center gap-2">
                        <strong class="text-warning fs-4 fw-black">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</strong>
                        <button type="button" class="btn btn-sm btn-warning text-dark py-0.5 px-2.5 fw-bold rounded-2 shadow-sm" id="btnCopyAmount" style="font-size: 0.72rem;" onclick="copyText('{{ $currentOrder->total_amount }}', 'btnCopyAmount')">
                          <i class="fa-regular fa-copy me-1"></i> Copy
                        </button>
                      </div>
                    </div>

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
