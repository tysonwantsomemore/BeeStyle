@extends('layouts.client')

@section('title', 'Thanh Toán Đơn Hàng | BeeStyle Fashion')

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

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Họ và tên người nhận <span class="text-danger">*</span></label>
              <input type="text" name="customer_name" class="form-control form-control-sm" value="Nguyễn Văn Hùng" required placeholder="Nhập đầy đủ họ tên...">
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Số điện thoại liên hệ <span class="text-danger">*</span></label>
              <input type="tel" name="customer_phone" class="form-control form-control-sm" value="0987 654 321" required placeholder="Ví dụ: 0987654321">
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Địa chỉ Email (Nhận thông báo đơn hàng)</label>
              <input type="email" name="customer_email" class="form-control form-control-sm" value="hung.nguyen@gmail.com" placeholder="email@domain.com">
            </div>

            <div class="col-md-4">
              <label class="form-label small fw-semibold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
              <select class="form-select form-select-sm" required>
                <option value="HCM" selected>TP. Hồ Chí Minh</option>
                <option value="HN">Hà Nội</option>
                <option value="DN">Đà Nẵng</option>
                <option value="HP">Hải Phòng</option>
                <option value="CT">Cần Thơ</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label small fw-semibold">Quận / Huyện <span class="text-danger">*</span></label>
              <select class="form-select form-select-sm" required>
                <option value="Q1" selected>Quận 1</option>
                <option value="Q3">Quận 3</option>
                <option value="Q7">Quận 7</option>
                <option value="BT">Bình Thạnh</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label small fw-semibold">Phường / Xã <span class="text-danger">*</span></label>
              <select class="form-select form-select-sm" required>
                <option value="BN" selected>Phường Bến Nghé</option>
                <option value="BT">Phường Bến Thành</option>
                <option value="DK">Phường Đa Kao</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Địa chỉ số nhà, tên đường cụ thể <span class="text-danger">*</span></label>
              <input type="text" name="customer_address" class="form-control form-control-sm" value="Số 45 Đường Lê Duẩn" required placeholder="Số nhà, ngõ ngách, tên đường...">
            </div>

            <div class="col-12">
              <label class="form-label small fw-semibold">Ghi chú giao hàng (Tùy chọn)</label>
              <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Ví dụ: Giao hàng vào giờ hành chính, gọi trước khi đến..."></textarea>
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
            <div class="form-check p-3 border rounded-3 bg-light-subtle d-flex align-items-center">
              <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_cod" value="COD" checked>
              <label class="form-check-label d-flex align-items-center justify-content-between flex-grow-1 cursor-pointer" for="pay_cod">
                <div>
                  <strong class="text-dark d-block">Thanh toán khi nhận hàng (COD)</strong>
                  <span class="text-muted small">Thanh toán tiền mặt cho bưu tá khi nhận và kiểm tra hàng</span>
                </div>
                <i class="fa-solid fa-hand-holding-dollar fs-4 text-warning"></i>
              </label>
            </div>

            <!-- Method 2: VietQR -->
            <div class="form-check p-3 border rounded-3 bg-light-subtle d-flex align-items-center">
              <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_vietqr" value="VIETQR">
              <label class="form-check-label d-flex align-items-center justify-content-between flex-grow-1 cursor-pointer" for="pay_vietqr">
                <div>
                  <strong class="text-dark d-block">Chuyển khoản VietQR 24/7 (Khuyên dùng)</strong>
                  <span class="text-muted small">Quét mã QR qua app ngân hàng - Xác nhận tự động không cần chụp màn hình</span>
                </div>
                <i class="fa-solid fa-qrcode fs-4 text-warning"></i>
              </label>
            </div>

            <!-- Method 3: VNPAY / MoMo -->
            <div class="form-check p-3 border rounded-3 bg-light-subtle d-flex align-items-center">
              <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_vnpay" value="VNPAY">
              <label class="form-check-label d-flex align-items-center justify-content-between flex-grow-1 cursor-pointer" for="pay_vnpay">
                <div>
                  <strong class="text-dark d-block">Ví điện tử VNPAY / MoMo / Thẻ ATM</strong>
                  <span class="text-muted small">Cổng thanh toán an toàn, bảo mật thông tin tài khoản</span>
                </div>
                <i class="fa-solid fa-wallet fs-4 text-info"></i>
              </label>
            </div>
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
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary small">{{ $item['quantity'] }}</span>
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
            <div class="d-flex justify-content-between">
              <span class="text-muted">Voucher giảm giá (BEESTYLE50):</span>
              <span class="fw-semibold text-success">-{{ number_format($discount, 0, ',', '.') }}₫</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Phí giao hàng:</span>
              <span class="fw-semibold text-success">Miễn phí (Freeship)</span>
            </div>
          </div>

          <hr class="border-secondary-subtle my-2">

          <div class="d-flex justify-content-between align-items-baseline mb-4">
            <span class="fw-bold text-dark fs-6">Tổng thanh toán:</span>
            <span class="fs-4 fw-bold text-danger">{{ number_format($total, 0, ',', '.') }}₫</span>
          </div>

          <button type="submit" class="btn btn-bee-primary w-100 py-3 fs-6">
            <i class="fa-solid fa-lock me-2"></i> Đặt Hàng Ngay
          </button>

          <p class="text-muted small text-center mt-3 mb-0">
            Bằng việc nhấn Đặt Hàng, bạn đồng ý với <a href="#" class="text-warning">Điều khoản mua hàng của BeeStyle</a>.
          </p>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection
