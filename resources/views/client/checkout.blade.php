@extends('layouts.client')

@section('title', 'Thanh Toán Đơn Hàng | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  
  <!-- CHECKOUT STEP PROGRESS BAR -->
  <div class="card border-0 shadow-sm p-3 mb-4 rounded-4 bg-white">
    <div class="d-flex align-items-center justify-content-center gap-2 gap-md-4 flex-wrap text-center">
      <div class="d-flex align-items-center gap-2 text-success fw-bold small">
        <span class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;"><i class="fa-solid fa-check"></i></span>
        <span>1. Giỏ Hàng</span>
      </div>
      <i class="fa-solid fa-chevron-right text-muted small d-none d-sm-inline"></i>
      <div class="d-flex align-items-center gap-2 text-warning fw-bold small">
        <span class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">2</span>
        <span class="text-dark">2. Thông Tin &amp; Thanh Toán</span>
      </div>
      <i class="fa-solid fa-chevron-right text-muted small d-none d-sm-inline"></i>
      <div class="d-flex align-items-center gap-2 text-muted small">
        <span class="rounded-circle bg-light border text-muted d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">3</span>
        <span>3. Hoàn Tất Đơn Hàng</span>
      </div>
    </div>
  </div>

  <form action="{{ route('client.checkout.process') }}" method="POST" id="checkoutForm">
    @csrf
    <div class="row g-4">
      
      <!-- CỘT TRÁI: THÔNG TIN NHẬN HÀNG & PHƯƠNG THỨC THANH TOÁN -->
      <div class="col-lg-7">
        
        <!-- KHỐI 1: THÔNG TIN GIAO HÀNG -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">
              <i class="fa-solid fa-location-dot me-2 text-warning"></i> 1. Thông Tin Nhận Hàng
            </h5>
            <span class="badge bg-warning-subtle text-dark fw-semibold small">Giao tận nơi</span>
          </div>

          @if(isset($addresses) && $addresses->count() > 0)
            <div class="mb-3 p-3 bg-light rounded-3 border">
              <label class="form-label small fw-bold text-dark mb-2">
                <i class="fa-solid fa-address-book me-1 text-warning"></i> Chọn nhanh từ sổ địa chỉ đã lưu:
              </label>
              <div class="d-flex flex-column gap-2">
                @foreach($addresses as $addr)
                  <div class="form-check p-2.5 border rounded-2 bg-white d-flex align-items-center transition-all hover-lift">
                    <input class="form-check-input ms-1 me-2.5 saved-address-radio" type="radio" name="saved_address_picker" id="addr_pick_{{ $addr->id }}"
                      data-name="{{ $addr->recipient_name }}"
                      data-phone="{{ $addr->phone }}"
                      data-address="{{ $addr->address }}"
                      data-city="{{ $addr->city }}"
                      data-district="{{ $addr->district }}"
                      data-ward="{{ $addr->ward }}"
                      data-notes="{{ $addr->notes }}"
                      {{ $addr->is_default ? 'checked' : '' }}
                      onchange="applySavedAddress(this)">
                    <label class="form-check-label small d-flex justify-content-between align-items-center flex-grow-1 cursor-pointer" for="addr_pick_{{ $addr->id }}">
                      <div>
                        <strong>{{ $addr->recipient_name }}</strong> ({{ $addr->phone }})
                        <span class="badge bg-secondary ms-1">{{ $addr->label ?? 'Nhà riêng' }}</span>
                        @if($addr->is_default)
                          <span class="badge bg-warning text-dark ms-1">Mặc định</span>
                        @endif
                        <div class="text-muted" style="font-size: 0.75rem;">{{ $addr->full_address }}</div>
                      </div>
                    </label>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Họ và tên người nhận <span class="text-danger">*</span></label>
              <input type="text" name="customer_name" id="input_customer_name" class="form-control form-control-sm" value="{{ old('customer_name', $defaultAddress->recipient_name ?? $user->name ?? '') }}" required placeholder="Ví dụ: Nguyễn Văn Hùng">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Số điện thoại liên hệ <span class="text-danger">*</span></label>
              <input type="tel" name="customer_phone" id="input_customer_phone" class="form-control form-control-sm" value="{{ old('customer_phone', $defaultAddress->phone ?? $user->phone ?? '') }}" required placeholder="Ví dụ: 0987654321">
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Địa chỉ Email (Nhận mã đơn &amp; hóa đơn điện tử)</label>
              <input type="email" name="customer_email" class="form-control form-control-sm" value="{{ old('customer_email', $user->email ?? '') }}" placeholder="email@gmail.com">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
              <input type="text" name="city" id="input_city" class="form-control form-control-sm" value="{{ old('city', $defaultAddress->city ?? $user->city ?? 'Hồ Chí Minh') }}" required placeholder="Ví dụ: TP. Hồ Chí Minh">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Quận / Huyện</label>
              <input type="text" name="district" id="input_district" class="form-control form-control-sm" value="{{ old('district', $defaultAddress->district ?? $user->district ?? 'Quận 1') }}" placeholder="Ví dụ: Quận 1">
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Địa chỉ chi tiết (Số nhà, tên đường, phường xã) <span class="text-danger">*</span></label>
              <input type="text" name="shipping_address" id="input_shipping_address" class="form-control form-control-sm" value="{{ old('shipping_address', $defaultAddress->address ?? $user->address ?? '') }}" required placeholder="Ví dụ: Số 45 Đường Lê Duẩn, Phường Bến Nghé">
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Ghi chú giao hàng (Tùy chọn)</label>
              <textarea name="notes" id="input_notes" class="form-control form-control-sm" rows="2" placeholder="Ví dụ: Giao hàng vào giờ hành chính, gọi trước khi giao 15 phút...">{{ old('notes', $defaultAddress->notes ?? '') }}</textarea>
            </div>
          </div>
        </div>

        <!-- SECTION 2: PAYMENT METHOD -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
          <h5 class="fw-bold text-dark mb-3">
            <i class="fa-solid fa-credit-card me-2 text-warning"></i> 2. Phương Thức Thanh Toán
          </h5>

          @if(!empty($depositInfo['is_required']))
            <!-- THÔNG BÁO CHÍNH SÁCH ĐẶT CỌC 50% CHO ĐƠN HÀNG SỐ LƯỢNG LỚN (>= 10 SẢN PHẨM) -->
            <div class="alert border-0 p-3.5 mb-4 rounded-4 shadow-sm" style="background: #fffbeb; border-left: 5px solid #f59e0b !important;">
              <div class="d-flex align-items-start gap-3">
                <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 44px; height: 44px;">
                  <i class="fa-solid fa-coins fs-5"></i>
                </div>
                <div class="w-100">
                  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                    <strong class="text-dark fs-6 d-flex align-items-center gap-1.5">
                      <i class="fa-solid fa-shield-halved text-warning"></i> CHÍNH SÁCH ĐẶT CỌC 50% ĐƠN HÀNG SỐ LƯỢNG LỚN
                    </strong>
                    <span class="badge bg-warning text-dark font-monospace fw-bold px-2.5 py-1">
                      {{ $depositInfo['total_quantity'] }} Sản Phẩm (≥ 10)
                    </span>
                  </div>
                  <p class="text-dark text-opacity-80 small mb-2.5" style="line-height: 1.55;">
                    Đơn hàng của quý khách có tổng số lượng <strong>{{ $depositInfo['total_quantity'] }} sản phẩm</strong> (từ 10 sản phẩm trở lên). Theo chính sách đơn hàng số lượng lớn của BeeStyle, quý khách vui lòng <strong>đặt cọc trước 50% giá trị đơn hàng</strong> để kho tiến hành chuẩn bị và xuất kho. Số tiền 50% còn lại thanh toán cho bưu tá khi nhận hàng.
                  </p>
                  <div class="p-3 bg-white rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-3 small shadow-2xs">
                    <div>
                      <span class="text-muted d-block" style="font-size: 0.75rem;">Số tiền đặt cọc trước (50%):</span>
                      <strong class="text-danger fs-5 font-monospace">{{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫</strong>
                    </div>
                    <div class="text-md-end">
                      <span class="text-muted d-block" style="font-size: 0.75rem;">Còn lại thanh toán khi nhận hàng COD (50%):</span>
                      <strong class="text-dark fs-5 font-monospace">{{ number_format($depositInfo['remaining_amount'], 0, ',', '.') }}₫</strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif

          <div class="d-flex flex-column gap-3" id="paymentMethodContainer">
            
            <!-- PHƯƠNG THỨC 1: COD -->
            <label class="pay-option-card d-block p-3.5 border rounded-3 transition-all cursor-pointer active" for="pay_cod" id="card_pay_cod">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <input class="form-check-input mt-0 pay-radio" type="radio" name="payment_method" id="pay_cod" value="cod" checked onchange="updatePayOptionCards()">
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <strong class="text-dark">
                        @if(!empty($depositInfo['is_required']))
                          Đặt cọc 50% &amp; Thu COD 50% khi nhận hàng
                        @else
                          Thanh toán khi nhận hàng (COD)
                        @endif
                      </strong>
                      <span class="badge {{ !empty($depositInfo['is_required']) ? 'bg-warning text-dark' : 'bg-warning-subtle text-dark' }} fw-bold px-2 py-0.5" style="font-size: 0.68rem;">
                        {{ !empty($depositInfo['is_required']) ? 'Cọc 50%' : 'Phổ biến' }}
                      </span>
                    </div>
                    <small class="text-muted d-block mt-0.5">
                      @if(!empty($depositInfo['is_required']))
                        Cọc trước 50% ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫) để xuất kho, 50% còn lại ({{ number_format($depositInfo['remaining_amount'], 0, ',', '.') }}₫) thanh toán tiền mặt cho bưu tá khi nhận hàng
                      @else
                        Thanh toán tiền mặt cho bưu tá khi nhận và kiểm tra hàng tận nhà
                      @endif
                    </small>
                  </div>
                </div>
                <div class="text-warning fs-3 ms-2">
                  <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
              </div>
              <div class="pay-desc-box mt-2.5 pt-2 border-top small text-secondary" id="desc_pay_cod">
                @if(!empty($depositInfo['is_required']))
                  <i class="fa-solid fa-shield-halved text-warning me-1"></i> Đơn hàng số lượng lớn ({{ $depositInfo['total_quantity'] }} sản phẩm) áp dụng <strong>chính sách đặt cọc 50% ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫)</strong>. Sau khi đặt hàng, bạn có thể chuyển khoản cọc qua VietQR hoặc nhân viên BeeStyle sẽ liên hệ hướng dẫn cọc trước khi xuất kho. Số tiền 50% còn lại ({{ number_format($depositInfo['remaining_amount'], 0, ',', '.') }}₫) thanh toán cho bưu tá khi nhận hàng.
                @else
                  <i class="fa-solid fa-circle-info text-warning me-1"></i> Quý khách được mở gói hàng đồng kiểm và thử đồ trước khi thanh toán tiền mặt cho nhân viên bưu tá.
                @endif
              </div>
            </label>

            <!-- PHƯƠNG THỨC 2: THANH TOÁN ONLINE / CHUYỂN KHOẢN VIETQR -->
            <label class="pay-option-card d-block p-3.5 border rounded-3 transition-all cursor-pointer" for="pay_online" id="card_pay_online">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <input class="form-check-input mt-0 pay-radio" type="radio" name="payment_method" id="pay_online" value="online" onchange="updatePayOptionCards()">
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <strong class="text-dark">
                        @if(!empty($depositInfo['is_required']))
                          Chuyển khoản cọc 50% qua VietQR 24/7 (Techcombank)
                        @else
                          Chuyển khoản Ngân Hàng 24/7 / Quét mã VietQR (Techcombank)
                        @endif
                      </strong>
                      <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-0.5" style="font-size: 0.68rem;">
                        <i class="fa-solid fa-qrcode me-0.5"></i> {{ !empty($depositInfo['is_required']) ? 'Cọc 50% VietQR' : 'VietQR / Napas 247' }}
                      </span>
                    </div>
                    <small class="text-muted d-block mt-0.5">
                      @if(!empty($depositInfo['is_required']))
                        Quét mã VietQR thanh toán tự động 50% tiền cọc ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫) qua ứng dụng mọi ngân hàng
                      @else
                        Quét mã QR qua ứng dụng mọi Ngân Hàng (Techcombank, Vietcombank, MB, BIDV, VPBank...) để thanh toán tự động
                      @endif
                    </small>
                  </div>
                </div>
                <div class="text-primary fs-3 ms-2">
                  <i class="fa-solid fa-qrcode"></i>
                </div>
              </div>
              <div class="pay-desc-box mt-2.5 pt-2 border-top small text-secondary d-none" id="desc_pay_online">
                @if(!empty($depositInfo['is_required']))
                  <i class="fa-solid fa-circle-info text-primary me-1"></i> Sau khi nhấn "Xác Nhận Đặt Cọc 50% &amp; Đặt Hàng", hệ thống sẽ hiển thị <strong>Mã VietQR Techcombank (STK: 77427842310105 - NGUYEN XUAN BAC)</strong> được điền sẵn chính xác <strong>50% số tiền cọc ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫)</strong> và mã đơn hàng để khớp lệnh tự động 24/7.
                @else
                  <i class="fa-solid fa-circle-info text-primary me-1"></i> Sau khi nhấn "Xác Nhận Đặt Hàng", hệ thống sẽ hiển thị <strong>Mã VietQR Techcombank (STK: 77427842310105 - NGUYEN XUAN BAC)</strong> được điền sẵn chính xác số tiền thanh toán <strong>({{ number_format($total, 0, ',', '.') }}₫)</strong> và mã đơn hàng để khớp lệnh tự động 24/7.
                @endif
              </div>
            </label>

            <!-- PHƯƠNG THỨC 2: THANH TOÁN TRỰC TUYẾN QUA MOMO -->
            <label class="pay-option-card d-block p-3.5 border rounded-3 transition-all cursor-pointer" for="pay_momo" id="card_pay_momo">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <input class="form-check-input mt-0 pay-radio" type="radio" name="payment_method" id="pay_momo" value="momo" onchange="updatePayOptionCards()">
                  <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                      <strong class="text-dark">
                        @if(!empty($depositInfo['is_required']))
                          Thanh toán cọc 50% trực tuyến qua ví MoMo (Deep Link)
                        @else
                          Thanh toán trực tuyến qua ví MoMo bằng chuyển hướng ứng dụng (Redirect/Deep Link)
                        @endif
                      </strong>
                      <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Redirect / Deep Link</span>
                    </div>
                    <small class="text-muted d-block mt-1">
                      @if(!empty($depositInfo['is_required']))
                        Mở ứng dụng MoMo để thanh toán 50% số tiền cọc ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫)
                      @else
                        BeeStyle tích hợp thanh toán trực tuyến qua ví điện tử MoMo. Hệ thống sử dụng hình thức chuyển hướng (Redirect/Deep Link), cho phép khách hàng mở ứng dụng MoMo và xác nhận thanh toán mà không cần quét mã QR.
                      @endif
                    </small>
                  </div>
                </div>
                <span class="badge text-white fw-bold px-2.5 py-1.5 rounded-2 shadow-xs ms-2 flex-shrink-0" style="background-color: #d82d8b; font-size: 0.85rem;">
                  <i class="fa-solid fa-wallet me-1"></i> MoMo
                </span>
              </div>
              <div class="pay-desc-box mt-2.5 pt-2 border-top small text-secondary d-none" id="desc_pay_momo">
                @if(!empty($depositInfo['is_required']))
                  <i class="fa-solid fa-circle-info text-danger me-1"></i> Chuyển hướng sang ứng dụng MoMo để thanh toán <strong>50% tiền cọc ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫)</strong>.
                @else
                  <i class="fa-solid fa-circle-info text-danger me-1"></i> BeeStyle tích hợp thanh toán trực tuyến qua ví điện tử MoMo. Hệ thống sử dụng hình thức chuyển hướng (Redirect/Deep Link), cho phép khách hàng mở ứng dụng MoMo và xác nhận thanh toán mà không cần quét mã QR.
                @endif
              </div>
            </label>

            <!-- PHƯƠNG THỨC 3: VÍ ZALOPAY -->
            <label class="pay-option-card d-block p-3.5 border rounded-3 transition-all cursor-pointer" for="pay_zalopay" id="card_pay_zalopay">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <input class="form-check-input mt-0 pay-radio" type="radio" name="payment_method" id="pay_zalopay" value="zalopay" onchange="updatePayOptionCards()">
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <strong class="text-dark">
                        @if(!empty($depositInfo['is_required']))
                          Ví Điện Tử ZaloPay (Cọc 50%)
                        @else
                          Ví Điện Tử ZaloPay
                        @endif
                      </strong>
                      <span class="badge bg-info-subtle text-info fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Zalo / ZaloPay</span>
                    </div>
                    <small class="text-muted d-block mt-0.5">
                      @if(!empty($depositInfo['is_required']))
                        Thanh toán 50% tiền cọc ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫) qua tài khoản ví ZaloPay
                      @else
                        Thanh toán tiện lợi qua tài khoản ví ZaloPay hoặc trực tiếp trên ứng dụng Zalo
                      @endif
                    </small>
                  </div>
                </div>
                <span class="badge text-white fw-bold px-2.5 py-1.5 rounded-2 shadow-xs ms-2" style="background-color: #008fe5; font-size: 0.85rem;">
                  <i class="fa-solid fa-wallet me-1"></i> ZaloPay
                </span>
              </div>
              <div class="pay-desc-box mt-2.5 pt-2 border-top small text-secondary d-none" id="desc_pay_zalopay">
                @if(!empty($depositInfo['is_required']))
                  <i class="fa-solid fa-circle-info text-info me-1"></i> Sau khi nhấn "Xác Nhận Đặt Cọc 50% &amp; Đặt Hàng", hệ thống sẽ chuyển bạn sang <strong>Cổng Thanh Toán ZaloPay Gateway</strong> để thanh toán 50% số tiền cọc ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫).
                @else
                  <i class="fa-solid fa-circle-info text-info me-1"></i> Sau khi nhấn "Xác Nhận Đặt Hàng", hệ thống sẽ chuyển bạn sang <strong>Cổng Thanh Toán ZaloPay Gateway</strong> để quét mã QR và xác nhận giao dịch.
                @endif
              </div>
            </label>

          </div>

        </div>

      </div>

      <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG & NÚT ĐẶT HÀNG -->
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 20px; position: sticky; top: 100px;">
          
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">Đơn Hàng Của Bạn</h5>
            <span class="badge bg-dark text-white rounded-pill px-2.5 py-1">{{ count($cartItems) }} món</span>
          </div>

          <!-- DANH SÁCH MÓN HÀNG -->
          <div class="d-flex flex-column gap-2.5 mb-3" style="max-height: 260px; overflow-y: auto;">
            @foreach($cartItems as $item)
              <div class="d-flex align-items-center justify-content-between gap-2 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2.5">
                  <div class="position-relative bg-white rounded-2 border p-1" style="width: 48px; height: 48px; min-width: 48px;">
                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-100 h-100 object-fit-contain">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark small">{{ $item['quantity'] }}</span>
                  </div>
                  <div>
                    <h6 class="small fw-bold text-dark mb-0 text-truncate" style="max-width: 180px;">{{ $item['name'] }}</h6>
                    <small class="text-muted" style="font-size: 0.72rem;">Màu: {{ $item['color'] }} • Size: {{ $item['size'] }}</small>
                  </div>
                </div>
                <span class="small fw-bold text-dark text-end">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</span>
              </div>
            @endforeach
          </div>

          <!-- KHỐI MÃ GIẢM GIÁ / VOUCHER (CHUẨN TMĐT SHOPEE / LAZADA) -->
          <div class="mb-3 p-3 rounded-3 border" style="background: #fffdf5; border-color: #fde68a !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="small fw-bold text-dark mb-0 d-flex align-items-center gap-1.5">
                <i class="fa-solid fa-ticket text-warning fs-6"></i> Mã Giảm Giá BeeStyle
              </label>
              @if(isset($coupons) && $coupons->count() > 0)
                <button type="button" class="btn btn-link text-danger p-0 small fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#checkoutVoucherModal">
                  <i class="fa-solid fa-tags me-1"></i> Chọn mã ({{ $coupons->count() }}) <i class="fa-solid fa-chevron-right ms-0.5" style="font-size: 0.65rem;"></i>
                </button>
              @endif
            </div>

            @if($appliedCoupon)
              <!-- Voucher Đang Áp Dụng -->
              <div class="p-2.5 bg-white rounded-3 border border-warning shadow-2xs d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                  <div class="bg-danger text-white rounded-2 px-2 py-1 font-monospace fw-bold fs-12 flex-shrink-0">
                    {{ $appliedCoupon->code }}
                  </div>
                  <div>
                    <span class="small fw-bold text-dark d-block mb-0">{{ $appliedCoupon->title }}</span>
                    <span class="badge bg-success-subtle text-success fw-bold" style="font-size: 0.7rem;">
                      <i class="fa-solid fa-circle-check me-0.5"></i> Giảm {{ number_format($discount, 0, ',', '.') }}₫
                    </span>
                  </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger py-0.5 px-2 rounded-pill fw-bold" onclick="removeCheckoutCoupon()" title="Hủy áp dụng mã này" style="font-size: 0.72rem;">
                  <i class="fa-solid fa-xmark me-0.5"></i> Bỏ mã
                </button>
              </div>
            @else
              <!-- Nhập Voucher Thủ Công -->
              <div class="input-group input-group-sm">
                <input type="text" id="manualCheckoutCouponInput" class="form-control font-monospace text-uppercase" placeholder="Nhập mã voucher...">
                <button class="btn btn-bee-primary px-3 fw-bold" type="button" onclick="applyManualCheckoutCoupon()">
                  Áp Dụng
                </button>
              </div>
              <small class="text-muted d-block mt-1.5" style="font-size: 0.72rem;">
                <i class="fa-solid fa-circle-info text-secondary me-0.5"></i> Bấm "Chọn mã" để xem tất cả mã ưu đãi &amp; freeship khả dụng
              </small>
            @endif
          </div>

          <!-- BẢNG TÍNH TIỀN -->
          <div class="d-flex flex-column gap-2 small mb-3">
            <div class="d-flex justify-content-between">
              <span class="text-muted">Tạm tính tiền hàng:</span>
              <span class="fw-semibold text-dark">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
            </div>
            @if($discount > 0)
              <div class="d-flex justify-content-between">
                <span class="text-muted">Giảm giá voucher ({{ $appliedCoupon->code ?? 'VOUCHER' }}):</span>
                <span class="fw-semibold text-success">-{{ number_format($discount, 0, ',', '.') }}₫</span>
              </div>
            @endif
            <div class="d-flex justify-content-between">
              <span class="text-muted">Phí giao hàng:</span>
              @if($shipping == 0)
                <span class="fw-semibold text-success"><i class="fa-solid fa-truck-fast me-1"></i> Miễn phí (Freeship)</span>
              @else
                <span class="fw-semibold text-dark">{{ number_format($shipping, 0, ',', '.') }}₫</span>
              @endif
            </div>
          </div>

          <hr class="border-secondary-subtle my-2">

          <!-- TỔNG CỘNG THANH TOÁN -->
          <div class="d-flex justify-content-between align-items-baseline mb-3">
            <div>
              <span class="fw-bold text-dark fs-6 d-block">Tổng giá trị đơn hàng:</span>
              <small class="text-muted" style="font-size: 0.75rem;">(Đã gồm VAT &amp; phí vận chuyển)</small>
            </div>
            <span class="fs-4 fw-bold text-danger font-monospace">{{ number_format($total, 0, ',', '.') }}₫</span>
          </div>

          @if(!empty($depositInfo['is_required']))
            <!-- PHÂN RÃ SỐ TIỀN ĐẶT CỌC 50% -->
            <div class="p-3 rounded-3 mb-3 border" style="background: #fffbeb; border-left: 4px solid #f59e0b !important;">
              <div class="d-flex justify-content-between align-items-center mb-1.5">
                <span class="small fw-bold text-dark">
                  <i class="fa-solid fa-coins text-warning me-1"></i> Số tiền đặt cọc trước (50%):
                </span>
                <strong class="text-danger font-monospace fs-6">{{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫</strong>
              </div>
              <div class="d-flex justify-content-between align-items-center small">
                <span class="text-muted">Số tiền thu COD khi nhận hàng:</span>
                <strong class="text-dark font-monospace">{{ number_format($depositInfo['remaining_amount'], 0, ',', '.') }}₫</strong>
              </div>
              <small class="text-muted d-block mt-1 pt-1 border-top" style="font-size: 0.72rem;">
                * Áp dụng chính sách cọc 50% cho đơn mua số lượng lớn trên 10 sản phẩm.
              </small>
            </div>
          @endif

          <!-- NÚT XÁC NHẬN ĐẶT HÀNG -->
          <button type="submit" class="btn btn-bee-primary w-100 py-3 fs-6 fw-bold shadow-md rounded-3">
            <i class="fa-solid fa-lock me-2"></i> {{ !empty($depositInfo['is_required']) ? 'XÁC NHẬN ĐẶT CỌC 50% & ĐẶT HÀNG' : 'XÁC NHẬN ĐẶT HÀNG' }}
          </button>

          <!-- CAM KẾT SÀN TMĐT CHUYÊN NGHIỆP -->
          <div class="mt-4 pt-3 border-top">
            <div class="d-flex flex-column gap-2 text-muted" style="font-size: 0.78rem;">
              <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-check text-success"></i>
                <span>Cam kết 100% hàng chính hãng <strong>BeeStyle Menswear</strong></span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-box-open text-primary"></i>
                <span>Được kiểm tra &amp; mặc thử đồ trước khi thanh toán</span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-rotate-left text-warning"></i>
                <span>Hỗ trợ đổi size linh hoạt trong vòng 30 ngày</span>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </form>
</div>

<!-- MODAL CHỌN VOUCHER SHOPEE STYLE TẠI TRANG THANH TOÁN -->
<div class="modal fade" id="checkoutVoucherModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-bottom pb-3">
        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
          <i class="fa-solid fa-ticket text-warning"></i>
          <span>Kho Mã Giảm Giá BeeStyle</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3.5">
        <!-- Ô nhập mã nhanh trong modal -->
        <div class="input-group input-group-sm mb-3">
          <input type="text" id="modalCouponInput" class="form-control font-monospace text-uppercase" placeholder="Nhập mã ưu đãi của bạn...">
          <button class="btn btn-bee-primary px-3 fw-bold" type="button" onclick="applyFromModalInput()">Áp Dụng</button>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
          <span class="small fw-bold text-dark">Mã Giảm Giá Sẵn Có</span>
          <span class="badge bg-light text-muted border">{{ count($coupons ?? []) }} mã</span>
        </div>

        <div class="d-flex flex-column gap-2.5" style="max-height: 380px; overflow-y: auto;">
          @if(isset($coupons) && $coupons->count() > 0)
            @foreach($coupons as $cp)
              @php
                $isEligible = ($subtotal >= $cp->min_order_value);
                $isCurrentlyUsing = ($appliedCoupon && strcasecmp($appliedCoupon->code, $cp->code) === 0);
              @endphp
              <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between gap-3 transition-all {{ $isCurrentlyUsing ? 'border-warning bg-warning-subtle' : ($isEligible ? 'bg-light hover-lift' : 'bg-white opacity-75') }}">
                <div class="min-w-0">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge {{ $isEligible ? 'bg-danger' : 'bg-secondary' }} text-white font-monospace fw-bold fs-12">{{ $cp->code }}</span>
                    <span class="badge {{ $isEligible ? 'bg-warning-subtle text-dark' : 'bg-light text-muted' }} fw-bold" style="font-size: 0.68rem;">
                      {{ $cp->discount_type === 'percent' ? 'Giảm ' . $cp->discount_value . '%' : ($cp->discount_type === 'shipping' ? 'Freeship' : 'Giảm ' . number_format($cp->discount_value, 0, ',', '.') . '₫') }}
                    </span>
                  </div>
                  <strong class="text-dark d-block small mb-0.5">{{ $cp->title }}</strong>
                  <small class="text-muted fs-11 d-block">
                    Đơn tối thiểu: <strong>{{ number_format($cp->min_order_value, 0, ',', '.') }}₫</strong> • HSD: {{ $cp->expires_at ? $cp->expires_at->format('d/m/Y') : 'Vô thời hạn' }}
                  </small>
                  @if(!$isEligible)
                    <small class="text-danger fw-semibold d-block mt-0.5" style="font-size: 0.7rem;">
                      <i class="fa-solid fa-circle-exclamation me-0.5"></i> Mua thêm {{ number_format($cp->min_order_value - $subtotal, 0, ',', '.') }}₫ để dùng mã này
                    </small>
                  @endif
                </div>

                <div class="flex-shrink-0">
                  @if($isCurrentlyUsing)
                    <button type="button" class="btn btn-outline-danger btn-sm px-3 py-1.5 rounded-pill fw-bold text-nowrap" onclick="removeCheckoutCoupon()">
                      Đang Dùng (Bỏ)
                    </button>
                  @elseif($isEligible)
                    <button type="button" class="btn btn-bee-primary btn-sm px-3 py-1.5 rounded-pill fw-bold text-nowrap" onclick="executeApplyCheckoutCoupon('{{ $cp->code }}')">
                      Áp Dụng
                    </button>
                  @else
                    <button type="button" class="btn btn-light text-muted btn-sm px-3 py-1.5 rounded-pill fw-semibold text-nowrap border" disabled>
                      Chưa Đủ ĐK
                    </button>
                  @endif
                </div>
              </div>
            @endforeach
          @else
            <div class="text-center py-4">
              <i class="fa-regular fa-ticket text-muted fs-2 mb-2"></i>
              <p class="small text-muted mb-0">Hiện tại chưa có mã giảm giá nào khả dụng.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<style>
  .pay-option-card {
    cursor: pointer;
    border: 1.5px solid #e2e8f0 !important;
    background: #ffffff;
    transition: all 0.2s ease-in-out;
  }
  .pay-option-card:hover {
    border-color: #94a3b8 !important;
    background: #f8fafc;
  }

  .pay-option-card.active#card_pay_cod {
    border-color: #f59e0b !important;
    background: #fffbeb !important;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.12);
  }
  .pay-option-card.active#card_pay_online {
    border-color: #0284c7 !important;
    background: #f0f9ff !important;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.12);
  }
  .pay-option-card.active#card_pay_momo {
    border-color: #d82d8b !important;
    background: #fdf2f8 !important;
    box-shadow: 0 4px 12px rgba(216, 45, 139, 0.12);
  }
  .pay-option-card.active#card_pay_zalopay {
    border-color: #008fe5 !important;
    background: #f0f9ff !important;
    box-shadow: 0 4px 12px rgba(0, 143, 229, 0.15);
  }
</style>

<script>
  function applySavedAddress(el) {
    if (!el) return;
    const name = el.getAttribute('data-name');
    const phone = el.getAttribute('data-phone');
    const addr = el.getAttribute('data-address');
    const city = el.getAttribute('data-city');
    const district = el.getAttribute('data-district');
    const notes = el.getAttribute('data-notes');

    if (name) document.getElementById('input_customer_name').value = name;
    if (phone) document.getElementById('input_customer_phone').value = phone;
    if (addr) document.getElementById('input_shipping_address').value = addr;
    if (city) document.getElementById('input_city').value = city;
    if (district) document.getElementById('input_district').value = district;
    if (notes) document.getElementById('input_notes').value = notes;
  }

  function updatePayOptionCards() {
    const cards = document.querySelectorAll('.pay-option-card');
    cards.forEach(card => {
      const radio = card.querySelector('.pay-radio');
      const desc = card.querySelector('.pay-desc-box');
      if (radio && radio.checked) {
        card.classList.add('active');
        if (desc) desc.classList.remove('d-none');
      } else {
        card.classList.remove('active');
        if (desc) desc.classList.add('d-none');
      }
    });
  }

  // --- XỬ LÝ ÁP DỤNG VÀ HỦY VOUCHER TẠI TRANG CHECKOUT ---
  function applyManualCheckoutCoupon() {
    const input = document.getElementById('manualCheckoutCouponInput');
    if (!input || !input.value.trim()) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'warning', title: 'Nhắc nhở', text: 'Vui lòng nhập mã giảm giá!' });
      } else {
        alert('Vui lòng nhập mã giảm giá!');
      }
      return;
    }
    executeApplyCheckoutCoupon(input.value.trim());
  }

  function applyFromModalInput() {
    const input = document.getElementById('modalCouponInput');
    if (!input || !input.value.trim()) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'warning', title: 'Nhắc nhở', text: 'Vui lòng nhập mã voucher!' });
      } else {
        alert('Vui lòng nhập mã voucher!');
      }
      return;
    }
    executeApplyCheckoutCoupon(input.value.trim());
  }

  function executeApplyCheckoutCoupon(code) {
    const modalEl = document.getElementById('checkoutVoucherModal');
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Đang áp dụng voucher...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }

    fetch('{{ route("client.cart.applyCoupon") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ code: code })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Áp Dụng Thành Công!',
            text: data.message,
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            window.location.reload();
          });
        } else {
          window.location.reload();
        }
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Không thể áp dụng',
            text: data.message || 'Mã giảm giá không hợp lệ.'
          });
        } else {
          alert(data.message || 'Mã giảm giá không hợp lệ.');
        }
      }
    })
    .catch(err => {
      console.error('Error applying coupon:', err);
      window.location.reload();
    });
  }

  function removeCheckoutCoupon() {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Đang hủy voucher...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }

    fetch('{{ route("client.cart.removeCoupon") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'info',
          title: 'Đã hủy voucher',
          text: data.message || 'Đã gỡ mã giảm giá.',
          timer: 1200,
          showConfirmButton: false
        }).then(() => {
          window.location.reload();
        });
      } else {
        window.location.reload();
      }
    })
    .catch(err => {
      console.error('Error removing coupon:', err);
      window.location.reload();
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Gắn sự kiện lắng nghe click/change trên toàn bộ các radio
    document.querySelectorAll('.pay-radio').forEach(radio => {
      radio.addEventListener('change', updatePayOptionCards);
    });

    updatePayOptionCards();
  });
</script>
@endpush

@endsection
