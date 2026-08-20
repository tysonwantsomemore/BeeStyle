@extends('layouts.client')

@section('title', 'Đăng Ký Thành Viên | BeeStyle Menswear')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      
      <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
        <div class="text-center mb-4">
          <div class="beestyle-logo justify-content-center mb-2">
            <span class="logo-badge"><i class="fa-solid fa-gem"></i></span>
            <span>BEE<span class="brand-highlight">STYLE</span></span>
          </div>
          <h4 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">ĐĂNG KÝ THÀNH VIÊN</h4>
          <p class="text-muted small">Nhận ngay <strong>100 điểm tích lũy</strong> và ưu đãi giảm giá độc quyền</p>
        </div>

        <!-- WELCOME GIFT PROMO -->
        <div class="alert alert-danger border-0 d-flex align-items-center gap-3 p-3 mb-4 rounded-3" style="background: #ffe4e6; color: #be123c;">
          <i class="fa-solid fa-gift fs-2"></i>
          <div class="small">
            <strong class="d-block text-uppercase">Quà Tặng Chào Mừng:</strong>
            <span>Tặng 100 điểm thưởng + Mã freeship toàn quốc cho đơn hàng đầu tiên.</span>
          </div>
        </div>

        <form action="{{ route('auth.register.post') }}" method="POST">
          @csrf

          <!-- Full Name -->
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Họ và tên <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-regular fa-user"></i></span>
              <input type="text" name="name" class="form-control border-start-0 ps-0" value="{{ old('name') }}" placeholder="Ví dụ: Nguyễn Văn An" required autofocus>
            </div>
          </div>

          <!-- Email & Phone -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" name="email" class="form-control border-start-0 ps-0" value="{{ old('email') }}" placeholder="email@gmail.com" required>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold text-dark">Số điện thoại <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-phone"></i></span>
                <input type="tel" name="phone" class="form-control border-start-0 ps-0" value="{{ old('phone') }}" placeholder="0987654321" required>
              </div>
            </div>
          </div>

          <!-- Address -->
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Địa chỉ nhận hàng mặc định</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-location-dot"></i></span>
              <input type="text" name="address" class="form-control border-start-0 ps-0" value="{{ old('address') }}" placeholder="Số nhà, tên đường, quận/huyện...">
            </div>
          </div>

          <!-- Password & Confirm Password -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-dark">Mật khẩu <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Tối thiểu 6 ký tự" required>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold text-dark">Xác nhận mật khẩu <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password_confirmation" class="form-control border-start-0 ps-0" placeholder="Nhập lại mật khẩu" required>
              </div>
            </div>
          </div>

          <!-- Terms -->
          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="terms" value="1" id="termsCheck" required checked>
            <label class="form-check-label small text-muted cursor-pointer" for="termsCheck">
              Tôi đồng ý với <a href="#" class="text-danger">Điều khoản sử dụng</a> &amp; <a href="#" class="text-danger">Chính sách bảo mật</a> của BeeStyle.
            </label>
          </div>

          <!-- Submit -->
          <button type="submit" class="btn btn-bee-primary w-100 py-3 mb-3">
            <i class="fa-solid fa-user-plus me-2"></i> HOÀN TẤT ĐĂNG KÝ
          </button>

          <!-- Login prompt -->
          <div class="text-center text-muted small">
            Đã có tài khoản? 
            <a href="{{ route('auth.login') }}" class="text-danger fw-bold text-decoration-none">Đăng nhập tại đây</a>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
@endsection
