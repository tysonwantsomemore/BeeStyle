@extends('layouts.client')

@section('title', 'Đăng Nhập Tài Khoản | BeeStyle Menswear')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      
      <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
        <div class="text-center mb-4">
          <a href="{{ route('client.home') }}" class="d-inline-flex justify-content-center mb-3 text-decoration-none">
            <img src="{{ asset('assets/img/beestyle-logo.svg') }}" alt="BeeStyle" height="42">
          </a>
          <h4 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">ĐĂNG NHẬP HỆ THỐNG</h4>
          <p class="text-muted small">Khám phá thế giới thời trang nam cao cấp &amp; ưu đãi thành viên</p>
        </div>

        <form action="{{ route('auth.login.post') }}" method="POST">
          @csrf

          <!-- Email or Phone -->
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Email hoặc Số điện thoại <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-regular fa-envelope"></i></span>
              <input type="text" name="login_id" class="form-control border-start-0 ps-0" value="{{ old('login_id') }}" placeholder="admin@beestyle.com hoặc SĐT..." required autofocus>
            </div>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <label class="form-label small fw-semibold text-dark mb-0">Mật khẩu <span class="text-danger">*</span></label>
              <a href="#" class="small text-danger text-decoration-none">Quên mật khẩu?</a>
            </div>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
              <input type="password" name="password" id="passwordInput" class="form-control border-start-0 border-end-0 ps-0" placeholder="Nhập mật khẩu..." required>
              <button class="input-group-text bg-light border-start-0 text-muted cursor-pointer" type="button" onclick="var p=document.getElementById('passwordInput'); p.type = (p.type==='password'?'text':'password');">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Remember me -->
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label small text-muted cursor-pointer" for="rememberMe">
                Ghi nhớ đăng nhập
              </label>
            </div>
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn btn-bee-primary w-100 py-3 mb-3">
            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> ĐĂNG NHẬP NGAY
          </button>

          <!-- Register prompt -->
          <div class="text-center text-muted small mb-4">
            Chưa có tài khoản BeeStyle? 
            <a href="{{ route('auth.register') }}" class="text-danger fw-bold text-decoration-none">Đăng ký thành viên mới</a>
          </div>

          <!-- Quick Test Credentials Box with 1-Click Fill -->
          <div class="p-3 bg-light rounded-3 border">
            <div class="d-flex align-items-center gap-2 mb-2">
              <i class="fa-solid fa-key text-danger"></i>
              <strong class="small text-dark text-uppercase">Tài Khoản Mẫu Trải Nghiệm:</strong>
            </div>
            
            <div class="d-flex flex-column gap-2">
              <button type="button" class="btn btn-outline-dark btn-sm text-start py-2 d-flex justify-content-between align-items-center" onclick="document.querySelector('input[name=login_id]').value='admin@beestyle.com'; document.querySelector('input[name=password]').value='password';">
                <div>
                  <span class="badge bg-danger text-white me-1">ADMIN</span>
                  <strong>admin@beestyle.com</strong>
                </div>
                <span class="small text-muted">Pass: <code>password</code> (Bấm để điền)</span>
              </button>

              <button type="button" class="btn btn-outline-secondary btn-sm text-start py-2 d-flex justify-content-between align-items-center" onclick="document.querySelector('input[name=login_id]').value='hung.nguyen@gmail.com'; document.querySelector('input[name=password]').value='password';">
                <div>
                  <span class="badge bg-secondary text-white me-1">KHÁCH</span>
                  <strong>hung.nguyen@gmail.com</strong>
                </div>
                <span class="small text-muted">Pass: <code>password</code> (Bấm để điền)</span>
              </button>
            </div>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
@endsection
