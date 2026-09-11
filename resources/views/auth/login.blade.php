@extends('layouts.client')

@section('title', 'Đăng Nhập Tài Khoản — BEESTYLE Studio')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-6 bg-brand-50">
  <div class="w-full max-w-md bg-white rounded-2xl border border-neutral-200/90 shadow-xl p-8 sm:p-10">
    
    <!-- Brand Header -->
    <div class="text-center mb-8">
      <a href="{{ route('client.home') }}" class="inline-flex items-center gap-2.5 text-decoration-none group select-none mb-4">
        <div class="w-11 h-11 rounded-xl bg-amber-400 text-neutral-950 flex items-center justify-center font-bold text-xl shadow-sm group-hover:scale-105 transition-transform shrink-0" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
          <i class="fa-solid fa-gem text-neutral-950"></i>
        </div>
        <div class="brand-logo-text text-left leading-tight">
          <div class="text-2xl font-black text-neutral-950 tracking-wider font-sans">BEE<span class="text-amber-500">STYLE</span></div>
          <div class="text-[9px] text-neutral-600 font-bold tracking-[0.25em] uppercase font-sans -mt-0.5">CONTEMPORARY FASHION</div>
        </div>
      </a>
      <h1 class="text-xl font-bold text-neutral-900 tracking-tight">Đăng Nhập Tài Khoản</h1>
      <p class="text-xs text-neutral-600 mt-1 font-medium">Chào mừng bạn quay trở lại với hệ thống BeeStyle</p>
    </div>

    <!-- Error Alert Box -->
    @if ($errors->any())
      <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 mb-6 text-rose-800 text-xs animate-fade-in">
        <div class="flex items-center gap-2 font-semibold mb-1 text-rose-900">
          <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
          <span>Thông tin đăng nhập không chính xác:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 text-rose-700 pl-2">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('auth.login.post') }}" method="POST" class="space-y-5">
      @csrf

      <!-- Login ID -->
      <div>
        <label for="login_id" class="block text-xs uppercase tracking-wider font-semibold text-neutral-700 mb-1.5">
          Email hoặc Số Điện Thoại <span class="text-rose-600">*</span>
        </label>
        <div class="relative">
          <i data-lucide="mail" class="w-4 h-4 text-neutral-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
          <input type="text" name="login_id" id="login_id" value="{{ old('login_id') }}" placeholder="admin@beestyle.com hoặc SĐT..." required autofocus
            class="w-full pl-10 pr-4 py-3 bg-neutral-50 border {{ $errors->has('login_id') ? 'border-rose-400 bg-rose-50/30' : 'border-neutral-200' }} rounded-xl text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
        </div>
        @error('login_id')
          <p class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
          </p>
        @enderror
      </div>

      <!-- Password -->
      <div>
        <div class="flex items-center justify-between mb-1.5">
          <label for="login_password" class="block text-xs uppercase tracking-wider font-semibold text-neutral-700">
            Mật Khẩu <span class="text-rose-600">*</span>
          </label>
          <a href="{{ route('auth.password.request') }}" class="text-[11px] text-rose-600 hover:text-rose-700 font-medium hover:underline">Quên mật khẩu?</a>
        </div>
        <div class="relative">
          <i data-lucide="lock" class="w-4 h-4 text-neutral-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
          <input type="password" name="password" id="login_password" placeholder="Nhập mật khẩu..." required
            class="w-full pl-10 pr-10 py-3 bg-neutral-50 border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-neutral-200' }} rounded-xl text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
          <button type="button" onclick="togglePass('login_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-black">
            <i data-lucide="eye" class="w-4 h-4"></i>
          </button>
        </div>
        @error('password')
          <p class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
          </p>
        @enderror
      </div>

      <!-- Remember Me -->
      <div class="flex items-center justify-between pt-1">
        <label class="flex items-center gap-2 cursor-pointer text-xs text-neutral-600">
          <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
            class="w-4 h-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900">
          <span>Ghi nhớ đăng nhập</span>
        </label>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.25em] uppercase rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
        <i data-lucide="log-in" class="w-4 h-4"></i>
        <span>Đăng Nhập Ngay</span>
      </button>

      <!-- Register Link -->
      <div class="text-center pt-2 text-xs text-neutral-600">
        Chưa có tài khoản BeeStyle?
        <a href="{{ route('auth.register') }}" class="font-semibold text-neutral-950 hover:underline ml-1">Đăng ký thành viên mới</a>
      </div>

      <!-- 1-Click Quick Demo Accounts Box -->
      <div class="p-4 bg-brand-100/70 rounded-xl border border-brand-200/80 mt-6 text-xs">
        <span class="font-semibold text-neutral-800 uppercase tracking-wider text-[10px] block mb-2">Tài Khoản Mẫu Trải Nghiệm (Bấm để điền):</span>
        <div class="space-y-2">
          <button type="button" onclick="document.getElementById('login_id').value='admin@beestyle.com'; document.getElementById('login_password').value='password';" class="w-full text-left p-2 bg-white rounded-lg border border-neutral-200 hover:border-neutral-900 flex justify-between items-center transition-colors">
            <div>
              <span class="px-1.5 py-0.5 bg-neutral-900 text-white rounded text-[10px] font-bold mr-1">ADMIN</span>
              <strong>admin@beestyle.com</strong>
            </div>
            <span class="text-[10px] text-neutral-400">Pass: password</span>
          </button>
          <button type="button" onclick="document.getElementById('login_id').value='hung.nguyen@gmail.com'; document.getElementById('login_password').value='password';" class="w-full text-left p-2 bg-white rounded-lg border border-neutral-200 hover:border-neutral-900 flex justify-between items-center transition-colors">
            <div>
              <span class="px-1.5 py-0.5 bg-amber-700 text-white rounded text-[10px] font-bold mr-1">KHÁCH</span>
              <strong>hung.nguyen@gmail.com</strong>
            </div>
            <span class="text-[10px] text-neutral-400">Pass: password</span>
          </button>
        </div>
      </div>

    </form>

  </div>
</div>
@endsection

@push('scripts')
<script>
  function togglePass(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
      input.type = 'text';
    } else {
      input.type = 'password';
    }
  }
</script>
@endpush
