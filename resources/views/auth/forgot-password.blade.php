@extends('layouts.client')

@section('title', 'Quên Mật Khẩu | BeeStyle Menswear')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      
      <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
        <div class="text-center mb-4">
          <a href="{{ route('client.home') }}" class="d-inline-flex align-items-center gap-2 mb-3 text-decoration-none group select-none">
            <div class="d-flex align-items-center justify-content-center bg-warning text-dark rounded-3 shadow-xs" style="width: 42px; height: 42px; font-size: 1.2rem; background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%) !important;">
              <i class="fa-solid fa-gem text-dark"></i>
            </div>
            <div class="brand-logo-text text-start lh-1">
              <div class="fw-bold text-dark tracking-wider" style="font-size: 1.4rem; font-weight: 900; letter-spacing: 0.06em;">BEE<span class="text-warning" style="color: #f59e0b !important;">STYLE</span></div>
              <div class="text-muted fw-bold text-uppercase mt-1" style="font-size: 0.62rem; letter-spacing: 0.22em;">MENSWEAR &amp; ATELIER</div>
            </div>
          </a>
          <h4 class="fw-bold text-dark mb-1 text-uppercase">KHÔI PHỤC MẬT KHẨU</h4>
          <p class="text-muted small">Nhập địa chỉ email đăng ký của bạn để nhận hướng dẫn đặt lại mật khẩu</p>
        </div>

        @if (session('status'))
          <div class="alert alert-success d-flex align-items-start gap-2 small p-3 mb-4 rounded-3 border-0 bg-success-subtle text-success">
            <i class="fa-solid fa-circle-check fs-5 flex-shrink-0 mt-0.5"></i>
            <div>
              <strong>Thành công!</strong> {{ session('status') }}
              @if(session('dev_reset_url'))
                <div class="mt-2 pt-2 border-top border-success-subtle">
                  <span class="d-block text-dark fw-bold mb-1"><i class="fa-solid fa-link me-1"></i> Liên kết đặt lại mật khẩu (Môi trường Thử nghiệm):</span>
                  <a href="{{ session('dev_reset_url') }}" class="btn btn-sm btn-dark text-white fw-bold px-3 py-1.5 rounded-pill">
                    Đặt Lại Mật Khẩu Ngay <i class="fa-solid fa-arrow-right ms-1"></i>
                  </a>
                </div>
              @endif
            </div>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger d-flex align-items-center gap-2 small p-3 mb-4 rounded-3 border-0 bg-danger-subtle text-danger">
            <i class="fa-solid fa-circle-exclamation fs-5 flex-shrink-0"></i>
            <div>{{ session('error') }}</div>
          </div>
        @endif

        <form action="{{ route('auth.password.email') }}" method="POST">
          @csrf

          <!-- Email -->
          <div class="mb-4">
            <label class="form-label small fw-semibold text-dark">Địa chỉ Email đăng ký <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-regular fa-envelope"></i></span>
              <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Ví dụ: ban@gmail.com..." required autofocus>
            </div>
            @error('email')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn btn-bee-primary w-100 py-3 mb-3">
            <i class="fa-solid fa-paper-plane me-2"></i> GỬI LIÊN KẾT ĐẶT LẠI MẬT KHẨU
          </button>

          <!-- Back to login -->
          <div class="text-center text-muted small">
            Nhớ lại mật khẩu? 
            <a href="{{ route('auth.login') }}" class="text-danger fw-bold text-decoration-none">Quay lại Đăng nhập</a>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>
@endsection
