@extends('layouts.client')

@section('title', 'Đăng Ký Thành Viên — BEESTYLE Studio')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-6 bg-brand-50">
  <div class="w-full max-w-lg bg-white rounded-2xl border border-neutral-200/90 shadow-xl p-8 sm:p-10">
    
    <!-- Brand Header -->
    <div class="text-center mb-8">
      <span class="font-serif-luxury text-2xl md:text-3xl font-bold tracking-[0.25em] text-neutral-900 uppercase block">
        BEESTYLE
      </span>
      <span class="block text-[8px] tracking-[0.4em] text-neutral-500 uppercase -mt-0.5 font-sans mb-3">
        STUDIO • 2026
      </span>
      <h1 class="font-serif-luxury text-2xl text-neutral-900 font-medium">Đăng Ký Tài Khoản Thành Viên</h1>
      <p class="text-xs text-neutral-500 mt-1 font-light">Nhận ngay đặc quyền may đo độc bản và ưu đãi thành viên</p>
    </div>

    <!-- Welcome Gift Promo Box -->
    <div class="bg-amber-50 border border-amber-200/80 rounded-xl p-4 mb-6 flex items-center gap-3 text-amber-900 text-xs">
      <div class="w-8 h-8 rounded-full bg-amber-200/80 flex items-center justify-center shrink-0">
        <i data-lucide="gift" class="w-4 h-4 text-amber-800"></i>
      </div>
      <div>
        <strong class="font-semibold block uppercase tracking-wider text-[11px]">Đặc Quyền Thành Viên Mới:</strong>
        <span class="text-neutral-600">Tặng mã <strong>BEESTYLE15</strong> giảm 15% &amp; Freeship toàn quốc cho đơn hàng đầu tiên.</span>
      </div>
    </div>

    <!-- Error Alert Box -->
    @if ($errors->any())
      <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 mb-6 text-rose-800 text-xs animate-fade-in">
        <div class="flex items-center gap-2 font-semibold mb-2 text-rose-900">
          <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
          <span>Vui lòng kiểm tra lại các thông tin sau:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 text-rose-700 pl-2">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('auth.register.post') }}" method="POST" id="registerForm" class="space-y-5" onsubmit="return validateClientForm(event)">
      @csrf

      <!-- Full Name -->
      <div>
        <label for="reg_name" class="block text-xs uppercase tracking-wider font-semibold text-neutral-700 mb-1.5">
          Họ và Tên <span class="text-rose-600">*</span>
        </label>
        <div class="relative">
          <i data-lucide="user" class="w-4 h-4 text-neutral-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
          <input type="text" name="name" id="reg_name" value="{{ old('name') }}" placeholder="Ví dụ: Nguyễn Văn An" required autofocus
            class="w-full pl-10 pr-4 py-3 bg-neutral-50 border {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-neutral-200' }} rounded-xl text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors"
            oninput="checkName(this)">
        </div>
        @error('name')
          <p class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
          </p>
        @enderror
        <p id="name_feedback" class="text-[11px] text-rose-600 mt-1 hidden"></p>
      </div>

      <!-- Email & Phone Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Email -->
        <div>
          <label for="reg_email" class="block text-xs uppercase tracking-wider font-semibold text-neutral-700 mb-1.5">
            Email <span class="text-rose-600">*</span>
          </label>
          <div class="relative">
            <i data-lucide="mail" class="w-4 h-4 text-neutral-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input type="email" name="email" id="reg_email" value="{{ old('email') }}" placeholder="email@gmail.com" required
              class="w-full pl-10 pr-4 py-3 bg-neutral-50 border {{ $errors->has('email') ? 'border-rose-400 bg-rose-50/30' : 'border-neutral-200' }} rounded-xl text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors"
              oninput="checkEmail(this)">
          </div>
          @error('email')
            <p class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
              <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
            </p>
          @enderror
          <p id="email_feedback" class="text-[11px] text-rose-600 mt-1 hidden"></p>
        </div>

        <!-- Phone -->
        <div>
          <label for="reg_phone" class="block text-xs uppercase tracking-wider font-semibold text-neutral-700 mb-1.5">
            Số Điện Thoại <span class="text-rose-600">*</span>
          </label>
          <div class="relative">
            <i data-lucide="phone" class="w-4 h-4 text-neutral-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input type="tel" name="phone" id="reg_phone" value="{{ old('phone') }}" placeholder="0987654321" required
              class="w-full pl-10 pr-4 py-3 bg-neutral-50 border {{ $errors->has('phone') ? 'border-rose-400 bg-rose-50/30' : 'border-neutral-200' }} rounded-xl text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors"
              oninput="checkPhone(this)">
          </div>
          @error('phone')
            <p class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
              <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
            </p>
          @enderror
          <p id="phone_feedback" class="text-[11px] text-rose-600 mt-1 hidden"></p>
        </div>
      </div>

      <!-- Password & Confirm Password -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Password -->
        <div>
          <label for="reg_password" class="block text-xs uppercase tracking-wider font-semibold text-neutral-700 mb-1.5">
            Mật Khẩu <span class="text-rose-600">*</span>
          </label>
          <div class="relative">
            <i data-lucide="lock" class="w-4 h-4 text-neutral-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input type="password" name="password" id="reg_password" placeholder="Tối thiểu 8 ký tự" required
              class="w-full pl-10 pr-10 py-3 bg-neutral-50 border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50/30' : 'border-neutral-200' }} rounded-xl text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors"
              oninput="checkPassword(this)">
            <button type="button" onclick="togglePass('reg_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-black">
              <i data-lucide="eye" class="w-4 h-4"></i>
            </button>
          </div>
          @error('password')
            <p class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
              <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="reg_password_confirmation" class="block text-xs uppercase tracking-wider font-semibold text-neutral-700 mb-1.5">
            Xác Nhận Mật Khẩu <span class="text-rose-600">*</span>
          </label>
          <div class="relative">
            <i data-lucide="lock" class="w-4 h-4 text-neutral-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
            <input type="password" name="password_confirmation" id="reg_password_confirmation" placeholder="Nhập lại mật khẩu" required
              class="w-full pl-10 pr-10 py-3 bg-neutral-50 border {{ $errors->has('password_confirmation') ? 'border-rose-400 bg-rose-50/30' : 'border-neutral-200' }} rounded-xl text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors"
              oninput="checkConfirmPassword(this)">
            <button type="button" onclick="togglePass('reg_password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-black">
              <i data-lucide="eye" class="w-4 h-4"></i>
            </button>
          </div>
          @error('password_confirmation')
            <p class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
              <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
            </p>
          @enderror
          <p id="confirm_feedback" class="text-[11px] text-rose-600 mt-1 hidden"></p>
        </div>
      </div>

      <!-- Real-time Password Rules Indicator -->
      <div id="password_checklist" class="p-3 bg-neutral-50 rounded-xl border border-neutral-200/80 text-[11px] space-y-1 text-neutral-500">
        <p class="font-semibold text-neutral-700 uppercase tracking-wider text-[10px] mb-1">Yêu cầu bảo mật mật khẩu:</p>
        <div id="rule_len" class="flex items-center gap-1.5"><i data-lucide="circle" class="w-3 h-3 text-neutral-400"></i> Tối thiểu 8 ký tự</div>
        <div id="rule_upper" class="flex items-center gap-1.5"><i data-lucide="circle" class="w-3 h-3 text-neutral-400"></i> Có ít nhất 1 chữ in hoa (A-Z)</div>
        <div id="rule_lower" class="flex items-center gap-1.5"><i data-lucide="circle" class="w-3 h-3 text-neutral-400"></i> Có ít nhất 1 chữ in thường (a-z)</div>
        <div id="rule_num" class="flex items-center gap-1.5"><i data-lucide="circle" class="w-3 h-3 text-neutral-400"></i> Có ít nhất 1 chữ số (0-9)</div>
        <div id="rule_spec" class="flex items-center gap-1.5"><i data-lucide="circle" class="w-3 h-3 text-neutral-400"></i> Có ít nhất 1 ký tự đặc biệt (@, #, $, %...)</div>
      </div>

      <!-- Terms & Policies Checkbox -->
      <div class="pt-2">
        <label class="flex items-start gap-2.5 cursor-pointer text-xs text-neutral-600">
          <input type="checkbox" name="terms" value="1" id="reg_terms" required checked
            class="mt-0.5 w-4 h-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900">
          <span>Tôi đồng ý với <a href="#" class="font-semibold text-neutral-950 underline">Điều khoản dịch vụ</a> và <a href="#" class="font-semibold text-neutral-950 underline">Chính sách bảo mật</a> của BeeStyle.</span>
        </label>
        @error('terms')
          <p class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
          </p>
        @enderror
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.25em] uppercase rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        <span>Hoàn Tất Đăng Ký Tài Khoản</span>
      </button>

      <!-- Login Link -->
      <div class="text-center pt-2 text-xs text-neutral-600">
        Đã có tài khoản thành viên?
        <a href="{{ route('auth.login') }}" class="font-semibold text-neutral-950 hover:underline ml-1">Đăng nhập tại đây</a>
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

  function checkName(input) {
    const val = input.value.trim();
    const fb = document.getElementById('name_feedback');
    const regex = /^[\p{L}\s]+$/u;
    if (val.length > 0 && (val.length < 2 || val.length > 50 || !regex.test(val))) {
      fb.textContent = 'Họ và tên gồm 2-50 chữ cái tiếng Việt, không chứa số hay ký tự đặc biệt.';
      fb.classList.remove('hidden');
    } else {
      fb.classList.add('hidden');
    }
  }

  function checkEmail(input) {
    const val = input.value.trim();
    const fb = document.getElementById('email_feedback');
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (val.length > 0 && !regex.test(val)) {
      fb.textContent = 'Địa chỉ email không đúng định dạng chuẩn (ví dụ: user@example.com).';
      fb.classList.remove('hidden');
    } else {
      fb.classList.add('hidden');
    }
  }

  function checkPhone(input) {
    const val = input.value.trim().replace(/[\s\-\.]/g, '');
    const fb = document.getElementById('phone_feedback');
    const regex = /^(03|05|07|08|09)\d{8}$/;
    if (val.length > 0 && !regex.test(val)) {
      fb.textContent = 'Số điện thoại Việt Nam gồm 10 chữ số (đầu số 03, 05, 07, 08, 09).';
      fb.classList.remove('hidden');
    } else {
      fb.classList.add('hidden');
    }
  }

  function checkPassword(input) {
    const val = input.value;
    updateRule('rule_len', val.length >= 8);
    updateRule('rule_upper', /[A-Z]/.test(val));
    updateRule('rule_lower', /[a-z]/.test(val));
    updateRule('rule_num', /[0-9]/.test(val));
    updateRule('rule_spec', /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(val));
    checkConfirmPassword(document.getElementById('reg_password_confirmation'));
  }

  function updateRule(elementId, isValid) {
    const el = document.getElementById(elementId);
    if (!el) return;
    if (isValid) {
      el.className = 'flex items-center gap-1.5 text-emerald-600 font-semibold';
      el.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 text-emerald-600"></i>' + el.textContent.trim();
    } else {
      el.className = 'flex items-center gap-1.5 text-neutral-500 font-normal';
      el.innerHTML = '<i data-lucide="circle" class="w-3 h-3 text-neutral-400"></i>' + el.textContent.trim();
    }
    if (window.lucide) lucide.createIcons();
  }

  function checkConfirmPassword(input) {
    const pass = document.getElementById('reg_password').value;
    const confirm = input.value;
    const fb = document.getElementById('confirm_feedback');
    if (confirm.length > 0 && pass !== confirm) {
      fb.textContent = 'Mật khẩu xác nhận không khớp với mật khẩu đã nhập.';
      fb.classList.remove('hidden');
    } else {
      fb.classList.add('hidden');
    }
  }

  function validateClientForm(e) {
    const pass = document.getElementById('reg_password').value;
    const confirm = document.getElementById('reg_password_confirmation').value;
    if (pass !== confirm) {
      alert('Mật khẩu xác nhận không trùng khớp!');
      e.preventDefault();
      return false;
    }
    return true;
  }
</script>
@endpush
