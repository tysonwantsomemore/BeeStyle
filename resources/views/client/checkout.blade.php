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

        <!-- KHỐI 2: CHỌN PHƯƠNG THỨC THANH TOÁN (E-COMMERCE STANDARD) -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">
              <i class="fa-solid fa-credit-card me-2 text-warning"></i> 2. Phương Thức Thanh Toán
            </h5>
            <span class="badge bg-success-subtle text-success small fw-bold"><i class="fa-solid fa-shield-halved me-1"></i> Bảo Mật 100%</span>
          </div>

          <div class="d-flex flex-column gap-3" id="paymentMethodContainer">
            
            <!-- PHƯƠNG THỨC 1: COD -->
            <label class="pay-option-card d-block p-3.5 border rounded-3 transition-all cursor-pointer" for="pay_cod" id="card_pay_cod">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <input class="form-check-input mt-0 pay-radio" type="radio" name="payment_method" id="pay_cod" value="cod" checked onchange="updatePayOptionCards()">
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <strong class="text-dark">Thanh toán khi nhận hàng (COD)</strong>
                      <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Phổ biến</span>
                    </div>
                    <small class="text-muted d-block mt-0.5">Thanh toán tiền mặt cho bưu tá khi nhận và kiểm tra hàng tận nhà</small>
                  </div>
                </div>
                <div class="text-warning fs-3 ms-2">
                  <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
              </div>
              <div class="pay-desc-box mt-2.5 pt-2 border-top small text-secondary" id="desc_pay_cod">
                <i class="fa-solid fa-circle-info text-warning me-1"></i> Bạn được mở gói hàng kiểm tra và thử đồ trước khi thanh toán cho nhân viên giao hàng.
              </div>
            </label>

            <!-- PHƯƠNG THỨC 2: THANH TOÁN ONLINE -->
            <label class="pay-option-card d-block p-3.5 border rounded-3 transition-all cursor-pointer" for="pay_online" id="card_pay_online">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <input class="form-check-input mt-0 pay-radio" type="radio" name="payment_method" id="pay_online" value="online" onchange="updatePayOptionCards()">
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <strong class="text-dark">Thanh toán Online (ATM Nội Địa / Internet Banking / Visa)</strong>
                      <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Napas / Visa</span>
                    </div>
                    <small class="text-muted d-block mt-0.5">Chuyển khoản trực tuyến bảo mật qua cổng Ngân hàng điện tử &amp; Thẻ quốc tế</small>
                  </div>
                </div>
                <div class="text-primary fs-3 ms-2">
                  <i class="fa-solid fa-credit-card"></i>
                </div>
              </div>
              <div class="pay-desc-box mt-2.5 pt-2 border-top small text-secondary d-none" id="desc_pay_online">
                <i class="fa-solid fa-circle-info text-primary me-1"></i> Giao dịch trực tuyến bảo mật qua cổng Ngân hàng điện tử SSL 256-Bit. Đơn hàng tự động xác nhận ngay.
              </div>
            </label>

            <!-- PHƯƠNG THỨC 3: VÍ MOMO -->
            <label class="pay-option-card d-block p-3.5 border rounded-3 transition-all cursor-pointer" for="pay_momo" id="card_pay_momo">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <input class="form-check-input mt-0 pay-radio" type="radio" name="payment_method" id="pay_momo" value="momo" onchange="updatePayOptionCards()">
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <strong class="text-dark">Ví Điện Tử MoMo (Khuyên dùng)</strong>
                      <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-0.5" style="font-size: 0.68rem;">1-Chạm Siêu Tốc</span>
                    </div>
                    <small class="text-muted d-block mt-0.5">Thanh toán nhanh chóng, tiện lợi và an toàn qua ứng dụng ví điện tử MoMo</small>
                  </div>
                </div>
                <span class="badge text-white fw-bold px-2.5 py-1.5 rounded-2 shadow-xs ms-2" style="background-color: #d82d8b; font-size: 0.85rem;">
                  <i class="fa-solid fa-wallet me-1"></i> MoMo
                </span>
              </div>
              <div class="pay-desc-box mt-2.5 pt-2 border-top small text-secondary d-none" id="desc_pay_momo">
                <i class="fa-solid fa-circle-info text-danger me-1"></i> Sau khi nhấn "Xác Nhận Đặt Hàng", hệ thống sẽ chuyển bạn sang <strong>Cổng Thanh Toán MoMo Gateway</strong> để quét mã QR và hoàn tất giao dịch.
              </div>
            </label>

            <!-- PHƯƠNG THỨC 4: VÍ ZALOPAY -->
            <label class="pay-option-card d-block p-3.5 border rounded-3 transition-all cursor-pointer" for="pay_zalopay" id="card_pay_zalopay">
              <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                  <input class="form-check-input mt-0 pay-radio" type="radio" name="payment_method" id="pay_zalopay" value="zalopay" onchange="updatePayOptionCards()">
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <strong class="text-dark">Ví Điện Tử ZaloPay</strong>
                      <span class="badge bg-info-subtle text-info fw-bold px-2 py-0.5" style="font-size: 0.68rem;">Zalo / ZaloPay</span>
                    </div>
                    <small class="text-muted d-block mt-0.5">Thanh toán tiện lợi qua tài khoản ví ZaloPay hoặc trực tiếp trên ứng dụng Zalo</small>
                  </div>
                </div>
                <span class="badge text-white fw-bold px-2.5 py-1.5 rounded-2 shadow-xs ms-2" style="background-color: #008fe5; font-size: 0.85rem;">
                  <i class="fa-solid fa-wallet me-1"></i> ZaloPay
                </span>
              </div>
              <div class="pay-desc-box mt-2.5 pt-2 border-top small text-secondary d-none" id="desc_pay_zalopay">
                <i class="fa-solid fa-circle-info text-info me-1"></i> Sau khi nhấn "Xác Nhận Đặt Hàng", hệ thống sẽ chuyển bạn sang <strong>Cổng Thanh Toán ZaloPay Gateway</strong> để quét mã QR và xác nhận giao dịch.
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
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" style="font-size: 0.65rem;">{{ $item['quantity'] }}</span>
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
          <div class="d-flex justify-content-between align-items-baseline mb-4">
            <div>
              <span class="fw-bold text-dark fs-6 d-block">Tổng thanh toán:</span>
              <small class="text-muted" style="font-size: 0.75rem;">(Đã bao gồm thuế VAT &amp; phí vận chuyển)</small>
            </div>
            <span class="fs-4 fw-bold text-danger font-monospace">{{ number_format($total, 0, ',', '.') }}₫</span>
          </div>

          <!-- NÚT XÁC NHẬN ĐẶT HÀNG -->
          <button type="submit" class="btn btn-bee-primary w-100 py-3 fs-6 fw-bold shadow-md rounded-3">
            <i class="fa-solid fa-lock me-2"></i> XÁC NHẬN ĐẶT HÀNG
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
