@extends('layouts.client')

@section('title', 'Thanh Toán Đơn Hàng | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('client.cart') }}" class="text-decoration-none text-muted">Giỏ hàng</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Thanh toán</li>
    </ol>
  </nav>

  <form action="{{ route('client.checkout.process') }}" method="POST">
    @csrf
    <div class="row g-4">
      <!-- SHIPPING & PAYMENT FORM -->
      <div class="col-lg-7">
        
        <!-- SECTION 1: SHIPPING ADDRESS -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
          <h5 class="fw-bold text-dark mb-3">
            <i class="fa-solid fa-location-dot me-2 text-warning"></i> 1. Thông Tin Nhận Hàng
          </h5>

          @if(isset($addresses) && $addresses->count() > 0)
            <div class="mb-3 p-3 bg-light rounded-3 border">
              <label class="form-label small fw-bold text-dark mb-2">
                <i class="fa-solid fa-address-book me-1 text-warning"></i> Chọn nhanh từ sổ địa chỉ đã lưu:
              </label>
              <div class="d-flex flex-column gap-2">
                @foreach($addresses as $addr)
                  <div class="form-check p-2 border rounded-2 bg-white d-flex align-items-center">
                    <input class="form-check-input ms-1 me-2 saved-address-radio" type="radio" name="saved_address_picker" id="addr_pick_{{ $addr->id }}"
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
              <label class="form-label small fw-semibold">Địa chỉ Email (Nhận mã đơn &amp; hóa đơn)</label>
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

          <div class="d-flex flex-column gap-3">
            <!-- Method 1: COD -->
            <label class="bee-pay-card d-flex align-items-center justify-content-between cursor-pointer" for="pay_cod">
              <div class="d-flex align-items-center">
                <input class="form-check-input me-3 mt-0" type="radio" name="payment_method" id="pay_cod" value="cod" checked>
                <div>
                  <strong class="text-dark d-block">Thanh toán khi nhận hàng (COD)</strong>
                  <span class="text-muted small">Thanh toán tiền mặt cho bưu tá khi nhận và thử hàng tận nhà</span>
                </div>
              </div>
              <i class="fa-solid fa-hand-holding-dollar fs-3 text-warning"></i>
            </label>

            <!-- Method 2: VietQR -->
            <label class="bee-pay-card d-flex align-items-center justify-content-between cursor-pointer" for="pay_vietqr">
              <div class="d-flex align-items-center">
                <input class="form-check-input me-3 mt-0" type="radio" name="payment_method" id="pay_vietqr" value="vietqr">
                <div>
                  <strong class="text-dark d-block">Chuyển khoản VietQR 24/7 (Khuyên dùng)</strong>
                  <span class="text-muted small">Quét mã QR qua app mọi ngân hàng - Tự động xác nhận giao dịch</span>
                </div>
              </div>
              <i class="fa-solid fa-qrcode fs-3 text-warning"></i>
            </label>

            <!-- Method 3: VNPAY / MoMo -->
            <label class="bee-pay-card d-flex align-items-center justify-content-between cursor-pointer" for="pay_vnpay">
              <div class="d-flex align-items-center">
                <input class="form-check-input me-3 mt-0" type="radio" name="payment_method" id="pay_vnpay" value="vnpay">
                <div>
                  <strong class="text-dark d-block">Ví điện tử VNPAY / MoMo / Thẻ Quốc Tế</strong>
                  <span class="text-muted small">Cổng thanh toán online an toàn bảo mật tiêu chuẩn quốc tế</span>
                </div>
              </div>
              <i class="fa-solid fa-wallet fs-3 text-info"></i>
            </label>
          </div>
        </div>

      </div>

      <!-- ORDER SUMMARY & CONFIRM -->
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; position: sticky; top: 100px;">
          <h5 class="fw-bold text-dark mb-3">Đơn Hàng Của Bạn ({{ count($cartItems) }})</h5>

          <!-- Items list -->
          <div class="d-flex flex-column gap-3 mb-3">
            @foreach($cartItems as $item)
              <div class="d-flex align-items-center justify-content-between gap-2 pb-2 border-bottom">
                <div class="d-flex align-items-center gap-2">
                  <div class="position-relative bg-light rounded-2 p-1" style="width: 50px; height: 50px;">
                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-100 h-100 object-fit-contain">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark small">{{ $item['quantity'] }}</span>
                  </div>
                  <div>
                    <h6 class="small fw-bold text-dark mb-0 text-truncate" style="max-width: 200px;">{{ $item['name'] }}</h6>
                    <small class="text-muted">{{ $item['color'] }} / {{ $item['size'] }}</small>
                  </div>
                </div>
                <span class="small fw-bold text-dark">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</span>
              </div>
            @endforeach
          </div>

          <!-- Price Calculation -->
          <div class="d-flex flex-column gap-2 small mb-3">
            <div class="d-flex justify-content-between">
              <span class="text-muted">Tạm tính:</span>
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
                <span class="fw-semibold text-success">Miễn phí (Freeship)</span>
              @else
                <span class="fw-semibold text-dark">{{ number_format($shipping, 0, ',', '.') }}₫</span>
              @endif
            </div>
          </div>

          <hr class="border-secondary-subtle my-2">

          <div class="d-flex justify-content-between align-items-baseline mb-4">
            <span class="fw-bold text-dark fs-6">Tổng thanh toán:</span>
            <span class="fs-4 fw-bold text-danger">{{ number_format($total, 0, ',', '.') }}₫</span>
          </div>

          <button type="submit" class="btn btn-bee-primary w-100 py-3 fs-6">
            <i class="fa-solid fa-lock me-2"></i> Xác Nhận Đặt Hàng
          </button>

          <p class="text-muted small text-center mt-3 mb-0">
            Bằng việc nhấn Đặt Hàng, bạn đồng ý với <a href="#" class="text-warning">Điều khoản mua hàng của BeeStyle</a>.
          </p>
        </div>
      </div>
    </div>
  </form>
</div>

@push('scripts')
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
</script>
@endpush
@endsection
