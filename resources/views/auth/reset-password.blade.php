@extends('layouts.client')

@section('title', 'Đặt Lại Mật Khẩu | BeeStyle Menswear')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      
      <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
        <div class="text-center mb-4">
          <a href="{{ route('client.home') }}" class="beestyle-brand-link justify-content-center mb-3 text-decoration-none d-inline-flex" title="BEESTYLE">
            <span class="beestyle-logo-text" style="font-size: 2.2rem;"><span class="text-danger">BEE</span>STYLE</span>
          </a>
          <h4 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">ĐẶT LẠI MẬT KHẨU</h4>
          <p class="text-muted small">Tạo mật khẩu mới an toàn cho tài khoản BeeStyle của bạn</p>
        </div>

        @if (session('error'))
          <div class="alert alert-danger d-flex align-items-center gap-2 small p-3 mb-4 rounded-3 border-0 bg-danger-subtle text-danger">
            <i class="fa-solid fa-circle-exclamation fs-5 flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
          </div>
        @endif

        <form action="{{ route('auth.password.update') }}" method="POST">
          @csrf

          <!-- Hidden Token -->
          <input type="hidden" name="token" value="{{ $token }}">

          <!-- Email -->
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-regular fa-envelope"></i></span>
              <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" placeholder="Ví dụ: ban@gmail.com..." required autofocus>
            </div>
            @error('email')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- New Password -->
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
              <input type="password" name="password" id="newPasswordInput" class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự..." required>
              <button class="input-group-text bg-light border-start-0 text-muted cursor-pointer" type="button" onclick="var p=document.getElementById('newPasswordInput'); p.type = (p.type==='password'?'text':'password');">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
            @error('password')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Confirm Password -->
          <div class="mb-4">
            <label class="form-label small fw-semibold text-dark">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-shield-halved"></i></span>
              <input type="password" name="password_confirmation" id="confirmPasswordInput" class="form-control border-start-0 border-end-0 ps-0" placeholder="Nhập lại mật khẩu mới..." required>
              <button class="input-group-text bg-light border-start-0 text-muted cursor-pointer" type="button" onclick="var p=document.getElementById('confirmPasswordInput'); p.type = (p.type==='password'?'text':'password');">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn btn-bee-primary w-100 py-3 mb-3">
            <i class="fa-solid fa-key me-2"></i> LƯU MẬT KHẨU MỚI
          </button>

          <!-- Back to login -->
          <div class="text-center text-muted small">
            <a href="{{ route('auth.login') }}" class="text-muted text-decoration-none">
              <i class="fa-solid fa-arrow-left me-1"></i> Quay lại Đăng nhập
            </a>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
@endsection
