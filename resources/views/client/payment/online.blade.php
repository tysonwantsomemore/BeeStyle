@extends('layouts.client')

@section('title', 'Cổng Giả Lập Thanh Toán Online Sandbox — Đơn Hàng #' . $order->order_code)

@section('content')
<div class="min-h-screen bg-[#f7f8fa] py-8 px-4 sm:px-6 flex items-center justify-center font-sans">
  <div class="w-full max-w-4xl bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.06)] border border-neutral-200/80 overflow-hidden">
    
    @php
      $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
      $payAmount = $isDeposit ? $order->deposit_amount : $order->total_amount;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
      
      <!-- ========================================================================= -->
      <!-- CỘT TRÁI: THÔNG TIN ĐƠN HÀNG & BỘ ĐẾM THỜI GIAN (4.5 cols) -->
      <!-- ========================================================================= -->
      <div class="lg:col-span-5 p-6 sm:p-8 bg-[#fafbfc] border-b lg:border-b-0 lg:border-r border-neutral-200/80 flex flex-col justify-between space-y-6 text-xs">
        
        <div class="space-y-5">
          <!-- Logo & Nhà cung cấp -->
          <div class="flex items-center gap-3 pb-4 border-b border-neutral-200/80">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-600 to-amber-400 text-white flex items-center justify-center font-bold shadow-sm shrink-0">
              <i data-lucide="cpu" class="w-5 h-5 text-white"></i>
            </div>
            <div>
              <span class="text-[10px] uppercase tracking-wider text-neutral-400 font-semibold block">Cổng Thanh Toán</span>
              <strong class="text-neutral-900 text-sm font-bold">Online Sandbox Simulator</strong>
            </div>
          </div>

          <!-- Mã đơn hàng -->
          <div>
            <span class="text-neutral-400 text-[11px] block mb-0.5">Mã đơn hàng</span>
            <strong class="font-mono text-neutral-900 text-sm font-bold tracking-wide">{{ $order->order_code }}</strong>
          </div>

          <!-- Mô tả -->
          <div>
            <span class="text-neutral-400 text-[11px] block mb-0.5">Mô tả</span>
            <strong class="text-neutral-800 text-xs font-semibold block">
              Thanh toán trực tuyến đơn hàng #{{ $order->order_code }}
            </strong>
          </div>

          <!-- Số tiền -->
          <div class="pt-1">
            <span class="text-neutral-400 text-[11px] block mb-0.5">Số tiền</span>
            <div class="font-serif-luxury text-3xl font-black text-neutral-950 font-mono tracking-tight">
              {{ number_format($payAmount, 0, ',', '.') }}đ
            </div>
            @if($isDeposit)
              <span class="inline-block mt-1 px-2 py-0.5 bg-amber-100 text-amber-900 border border-amber-300 rounded text-[10px] font-bold">
                TIỀN CỌC 50%
              </span>
            @endif
          </div>

          <!-- Khung đếm ngược hết hạn -->
          <div class="p-4 bg-[#fff6ed] border border-[#fed7aa] rounded-2xl text-center space-y-2">
            <span class="text-[#c2410c] font-bold text-[11px] block">Đơn hàng sẽ hết hạn sau:</span>
            <div class="flex items-center justify-center gap-2">
              <div class="flex items-center gap-1.5">
                <span id="minBox" class="bg-[#fef3c7] text-[#92400e] px-3 py-1.5 rounded-lg font-mono font-black text-base shadow-2xs">09</span>
                <span class="text-[11px] font-semibold text-[#92400e]">Phút</span>
              </div>
              <div class="flex items-center gap-1.5">
                <span id="secBox" class="bg-[#fef3c7] text-[#92400e] px-3 py-1.5 rounded-lg font-mono font-black text-base shadow-2xs">59</span>
                <span class="text-[11px] font-semibold text-[#92400e]">Giây</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer Links -->
        <div class="pt-4 border-t border-neutral-200/80 space-y-2 text-center">
          <button type="button" onclick="openSafeGuideModal()" class="text-blue-600 hover:text-blue-800 font-semibold text-xs transition-colors block mx-auto">
            Hướng dẫn thanh toán an toàn
          </button>
          <a href="{{ route('client.checkout') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs transition-colors block">
            Quay về
          </a>
        </div>

      </div>

      <!-- ========================================================================= -->
      <!-- CỘT PHẢI: THẺ NAPAS & FORM NHẬP TÀI KHOẢN DEVELOPER (7.5 cols) -->
      <!-- ========================================================================= -->
      <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between space-y-6">
        
        <!-- Thanh chọn nhanh tài khoản test Developer hỗ trợ -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-600 flex items-center gap-1.5">
              <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-600"></i>
              Tài Khoản Thẻ Test Có Sẵn (Developer Sandbox):
            </span>
            <span class="text-[10px] font-mono bg-amber-50 text-amber-800 px-2 py-0.5 rounded font-bold border border-amber-200">
              1-CLICK AUTOFILL
            </span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <button type="button" onclick="selectTestCard('saigonbank')" class="test-card-btn p-2 rounded-xl border border-pink-300 bg-pink-50/60 hover:bg-pink-100 text-left transition-all text-[11px] focus:ring-2 focus:ring-pink-500">
              <strong class="text-pink-950 font-bold block text-[11px]">Saigonbank Napas</strong>
              <span class="text-neutral-500 font-mono text-[10px]">...0018 (Mặc định)</span>
            </button>
            <button type="button" onclick="selectTestCard('vietcombank')" class="test-card-btn p-2 rounded-xl border border-neutral-200 hover:border-pink-300 hover:bg-neutral-50 text-left transition-all text-[11px] focus:ring-2 focus:ring-pink-500">
              <strong class="text-neutral-800 font-bold block text-[11px]">Vietcombank Connect</strong>
              <span class="text-neutral-500 font-mono text-[10px]">...0001 (Test OTP)</span>
            </button>
            <button type="button" onclick="selectTestCard('techcombank')" class="test-card-btn p-2 rounded-xl border border-neutral-200 hover:border-pink-300 hover:bg-neutral-50 text-left transition-all text-[11px] focus:ring-2 focus:ring-pink-500">
              <strong class="text-neutral-800 font-bold block text-[11px]">Techcombank Debit</strong>
              <span class="text-neutral-500 font-mono text-[10px]">...0002 (Test OTP)</span>
            </button>
          </div>
        </div>

        <!-- MÔ PHỎNG THẺ ATM / NAPAS (REALISTIC PINK CARD) -->
        <div class="flex justify-center my-2">
          <div id="visualCard" class="w-full max-w-[340px] h-[190px] rounded-2xl p-5 text-white shadow-xl relative overflow-hidden flex flex-col justify-between transition-all duration-300"
               style="background: linear-gradient(135deg, #d82d8b 0%, #b81772 50%, #8f0653 100%);">
            
            <!-- Họa tiết lượn sóng mờ trên thẻ -->
            <div class="absolute inset-0 opacity-15 pointer-events-none bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 rounded-full border-4 border-white/10 pointer-events-none"></div>

            <!-- Top Row: Bank Name + Chip -->
            <div class="flex justify-between items-start relative z-10">
              <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center p-1 backdrop-blur-xs">
                  <i data-lucide="landmark" class="w-3.5 h-3.5 text-white"></i>
                </div>
                <span id="cardBankDisplay" class="font-bold text-sm tracking-wide text-white drop-shadow-xs">Saigonbank</span>
              </div>
              <span class="text-[9px] font-mono tracking-widest text-pink-200 uppercase">DEBIT CARD</span>
            </div>

            <!-- Middle Row: EMV Chip + Last 4 Digits -->
            <div class="flex items-center justify-between my-auto relative z-10">
              <div class="w-9 h-7 rounded bg-gradient-to-r from-amber-300 via-amber-200 to-amber-400 border border-amber-500/50 shadow-inner flex items-center justify-center">
                <div class="w-full h-[1px] bg-amber-600/40"></div>
              </div>
              <div class="text-right">
                <span class="text-[10px] text-pink-200 block tracking-widest leading-none">•••• •••• ••••</span>
                <span id="cardLastDigitsDisplay" class="font-mono text-xl font-bold tracking-widest drop-shadow-xs">0018</span>
              </div>
            </div>

            <!-- Bottom Row: Cardholder Name, Valid Date & Napas Logo -->
            <div class="flex justify-between items-end relative z-10 pt-1">
              <div>
                <span class="text-[8px] uppercase tracking-wider text-pink-200 block leading-none mb-0.5">VALID FROM: <span id="cardDateDisplay" class="font-mono text-white text-[10px] font-bold">03/07</span></span>
                <span id="cardHolderDisplay" class="font-mono text-xs font-bold tracking-wider uppercase truncate max-w-[170px] block text-white drop-shadow-xs">NGUYEN VAN A</span>
              </div>
              <div class="flex items-center gap-1">
                <span class="font-sans font-black italic text-base tracking-tighter text-white drop-shadow-sm">napas<span class="text-amber-300">»</span></span>
              </div>
            </div>

          </div>
        </div>

        <!-- FORM NHẬP THÔNG TIN THẺ -->
        <form id="onlineCardForm" onsubmit="handleOnlineSubmit(event)" class="space-y-4 text-xs">
          
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            
            <!-- Số thẻ -->
            <div class="relative">
              <label class="block text-[11px] font-semibold text-neutral-700 mb-1">
                Số thẻ
              </label>
              <div class="relative">
                <input type="text" id="inputCardNumber" name="card_number" value="9704 0000 0000 0018" required
                       placeholder="9704 xxxx xxxx xxxx"
                       oninput="onCardNumberChange(this)"
                       class="w-full bg-[#f8fafc] border border-neutral-300 rounded-xl px-3.5 py-2.5 font-mono text-xs text-neutral-900 focus:outline-none focus:border-pink-600 focus:bg-white transition-all pr-8">
                <span id="checkCardNumber" class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-600 font-bold text-sm">✓</span>
              </div>
            </div>

            <!-- Ngày phát hành -->
            <div class="relative">
              <label class="block text-[11px] font-semibold text-neutral-700 mb-1">
                Ngày phát hành
              </label>
              <div class="relative">
                <input type="text" id="inputCardDate" name="card_date" value="03/07" required
                       placeholder="MM/YY"
                       oninput="onCardDateChange(this)"
                       class="w-full bg-[#f8fafc] border border-neutral-300 rounded-xl px-3.5 py-2.5 font-mono text-xs text-neutral-900 focus:outline-none focus:border-pink-600 focus:bg-white transition-all pr-8">
                <span id="checkCardDate" class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-600 font-bold text-sm">✓</span>
              </div>
            </div>

            <!-- Tên chủ thẻ -->
            <div class="relative">
              <label class="block text-[11px] font-semibold text-neutral-700 mb-1">
                Tên chủ thẻ
              </label>
              <div class="relative">
                <input type="text" id="inputCardHolder" name="card_holder" value="NGUYEN VAN A" required
                       placeholder="NGUYEN VAN A"
                       oninput="onCardHolderChange(this)"
                       class="w-full bg-[#f8fafc] border border-neutral-300 rounded-xl px-3.5 py-2.5 font-mono text-xs uppercase text-neutral-900 focus:outline-none focus:border-pink-600 focus:bg-white transition-all pr-8">
                <span id="checkCardHolder" class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-600 font-bold text-sm">✓</span>
              </div>
            </div>

            <!-- Số điện thoại -->
            <div class="relative">
              <label class="flex items-center gap-1 text-[11px] font-semibold text-neutral-700 mb-1">
                Số điện thoại
                <span title="Số điện thoại nhận mã OTP xác thực" class="text-neutral-400 cursor-help">(?)</span>
              </label>
              <div class="relative">
                <input type="tel" id="inputCardPhone" name="card_phone" value="0968238770" required
                       placeholder="0987654321"
                       class="w-full bg-[#f8fafc] border border-neutral-300 rounded-xl px-3.5 py-2.5 font-mono text-xs text-neutral-900 focus:outline-none focus:border-pink-600 focus:bg-white transition-all pr-8">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-600 font-bold text-sm">✓</span>
              </div>
            </div>

          </div>

          <!-- Nút Thanh Toán -->
          <div class="pt-2">
            <button type="submit" id="btnPaySubmit" class="w-full py-3.5 bg-[#d82d8b] hover:bg-[#c2187a] active:bg-[#a50064] text-white font-bold text-sm tracking-wider uppercase rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
              <span id="btnPaySpinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <span id="btnPayText">Thanh Toán</span>
            </button>
          </div>
        </form>

        <!-- Ngân hàng chấp nhận thanh toán -->
        <div class="pt-3 border-t border-neutral-200/80 text-center space-y-2">
          <span class="text-[10px] uppercase tracking-wider text-neutral-400 font-semibold block">
            Ngân hàng chấp nhận thanh toán Napas &amp; MoMo Gateway
          </span>
          <div class="flex flex-wrap items-center justify-center gap-2 opacity-80">
            <span class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 rounded font-bold text-[9px] text-neutral-700">NAPAS</span>
            <span class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 rounded font-bold text-[9px] text-neutral-700">SAIGONBANK</span>
            <span class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 rounded font-bold text-[9px] text-neutral-700">VIETCOMBANK</span>
            <span class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 rounded font-bold text-[9px] text-neutral-700">TECHCOMBANK</span>
            <span class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 rounded font-bold text-[9px] text-neutral-700">MB BANK</span>
            <span class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 rounded font-bold text-[9px] text-neutral-700">BIDV</span>
            <span class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 rounded font-bold text-[9px] text-neutral-700">VIETINBANK</span>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL XÁC THỰC MÃ OTP (ONLINE DEVELOPER SANDBOX OTP MODAL) -->
<!-- ========================================================================= -->
<div id="onlineOtpModal" class="fixed inset-0 z-50 bg-neutral-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden animate-fade-in">
  <div class="w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-neutral-200 space-y-5 text-center relative">
    
    <!-- Icon Sandbox OTP -->
    <div class="w-14 h-14 mx-auto rounded-2xl bg-pink-100 text-[#a50064] flex items-center justify-center shadow-inner">
      <i data-lucide="shield-check" class="w-8 h-8"></i>
    </div>

    <div>
      <h3 class="font-bold text-lg text-neutral-900">Xác Thực Giao Dịch Sandbox</h3>
      <p class="text-xs text-neutral-500 mt-1">
        Mã xác thực OTP giả lập đã được gửi đến số <strong id="modalPhoneText" class="font-mono text-neutral-900">0968***770</strong>
      </p>
    </div>

    <!-- OTP Input Box -->
    <div class="p-4 bg-pink-50/60 border border-pink-200 rounded-2xl space-y-3">
      <span class="text-[11px] text-neutral-600 block">
        Mã OTP Developer Sandbox mặc định: <strong class="font-mono text-[#a50064] font-bold text-sm">000000</strong>
      </span>
      <input type="text" id="otpCodeInput" value="000000" maxlength="6"
             class="w-full text-center font-mono text-2xl font-bold tracking-[0.4em] py-2.5 bg-white border border-pink-300 rounded-xl focus:outline-none focus:border-[#a50064] text-neutral-900">
    </div>

    <!-- Nút Xác Nhận Thành Công -->
    <form action="{{ route('client.checkout.online.success', $order->order_code) }}" method="POST">
      @csrf
      <button type="submit" class="w-full py-3.5 bg-[#d82d8b] hover:bg-[#c2187a] active:bg-[#a50064] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
        <i data-lucide="check" class="w-4 h-4"></i>
        <span>Xác Nhận Thanh Toán Thành Công</span>
      </button>
    </form>

    <!-- Nút Giả lập Thất Bại / Hủy -->
    <form action="{{ route('client.checkout.online.failed', $order->order_code) }}" method="POST">
      @csrf
      <button type="submit" class="w-full py-2.5 text-neutral-500 hover:text-rose-600 font-semibold text-xs transition-colors">
        Giả lập nhập sai OTP / Hủy giao dịch (Mã lỗi: 105)
      </button>
    </form>

    <!-- Close button -->
    <button type="button" onclick="closeOnlineOtpModal()" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-700">
      <i data-lucide="x" class="w-5 h-5"></i>
    </button>
  </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL HƯỚNG DẪN THANH TOÁN AN TOÀN -->
<!-- ========================================================================= -->
<div id="safeGuideModal" class="fixed inset-0 z-50 bg-neutral-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden animate-fade-in">
  <div class="w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-neutral-200 space-y-4 text-xs relative">
    <div class="flex items-center gap-2.5 pb-3 border-b border-neutral-200">
      <i data-lucide="shield-alert" class="w-5 h-5 text-emerald-600"></i>
      <h3 class="font-bold text-sm text-neutral-900">Hướng Dẫn Thanh Toán An Toàn</h3>
    </div>
    <div class="space-y-2.5 text-neutral-600 text-[11px] leading-relaxed">
      <p>• Đây là cổng thanh toán <strong>Developer Sandbox Simulator</strong> mô phỏng trực tiếp quy trình thanh toán thẻ ATM / Napas.</p>
      <p>• Quý khách có thể sử dụng các tài khoản test có sẵn (Saigonbank, Vietcombank, Techcombank) để kiểm thử luồng giao dịch mà không mất bất kỳ chi phí thực tế nào.</p>
      <p>• Mã OTP kiểm thử luôn là <strong class="font-mono text-neutral-900">000000</strong> hoặc <strong class="font-mono text-neutral-900">123456</strong>.</p>
    </div>
    <button type="button" onclick="closeSafeGuideModal()" class="w-full py-2.5 bg-neutral-900 text-white font-semibold rounded-xl hover:bg-neutral-800 transition-colors">
      Đã Hiểu
    </button>
    <button type="button" onclick="closeSafeGuideModal()" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-700">
      <i data-lucide="x" class="w-5 h-5"></i>
    </button>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Dữ liệu tài khoản test có sẵn của Developer Sandbox
  const TEST_CARDS = {
    saigonbank: {
      bank: 'Saigonbank',
      number: '9704 0000 0000 0018',
      date: '03/07',
      holder: 'NGUYEN VAN A',
      phone: '0968238770',
      lastDigits: '0018'
    },
    vietcombank: {
      bank: 'Vietcombank',
      number: '9704 3600 0000 0001',
      date: '03/07',
      holder: 'NGUYEN VAN B',
      phone: '0912345678',
      lastDigits: '0001'
    },
    techcombank: {
      bank: 'Techcombank',
      number: '9704 0700 0000 0002',
      date: '03/07',
      holder: 'NGUYEN VAN C',
      phone: '0987654321',
      lastDigits: '0002'
    }
  };

  // Chọn nhanh tài khoản test
  function selectTestCard(type) {
    const data = TEST_CARDS[type];
    if (!data) return;

    // Fill form
    document.getElementById('inputCardNumber').value = data.number;
    document.getElementById('inputCardDate').value = data.date;
    document.getElementById('inputCardHolder').value = data.holder;
    document.getElementById('inputCardPhone').value = data.phone;

    // Update Visual Card
    document.getElementById('cardBankDisplay').textContent = data.bank;
    document.getElementById('cardLastDigitsDisplay').textContent = data.lastDigits;
    document.getElementById('cardDateDisplay').textContent = data.date;
    document.getElementById('cardHolderDisplay').textContent = data.holder;

    // Active button style
    document.querySelectorAll('.test-card-btn').forEach(btn => {
      btn.classList.remove('border-pink-300', 'bg-pink-50/60');
      btn.classList.add('border-neutral-200');
    });
    event.currentTarget.classList.add('border-pink-300', 'bg-pink-50/60');
    event.currentTarget.classList.remove('border-neutral-200');
  }

  // Live updates
  function onCardNumberChange(input) {
    let val = input.value.replace(/\D/g, '');
    let formatted = val.match(/.{1,4}/g)?.join(' ') || val;
    input.value = formatted;

    const digits = val.slice(-4) || '0018';
    document.getElementById('cardLastDigitsDisplay').textContent = digits;
  }

  function onCardDateChange(input) {
    document.getElementById('cardDateDisplay').textContent = input.value || '03/07';
  }

  function onCardHolderChange(input) {
    input.value = input.value.toUpperCase();
    document.getElementById('cardHolderDisplay').textContent = input.value || 'NGUYEN VAN A';
  }

  // Handle Form Submit -> Mở OTP Modal
  function handleOnlineSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('btnPaySubmit');
    const spinner = document.getElementById('btnPaySpinner');
    const text = document.getElementById('btnPayText');

    spinner.classList.remove('hidden');
    text.textContent = 'Đang xử lý...';
    btn.disabled = true;

    setTimeout(() => {
      spinner.classList.add('hidden');
      text.textContent = 'Thanh Toán';
      btn.disabled = false;

      // Update phone in modal
      const phone = document.getElementById('inputCardPhone').value;
      const maskedPhone = phone.length >= 6 ? phone.slice(0, 4) + '***' + phone.slice(-3) : phone;
      document.getElementById('modalPhoneText').textContent = maskedPhone;

      // Open Modal
      document.getElementById('onlineOtpModal').classList.remove('hidden');
    }, 600);
  }

  function closeOnlineOtpModal() {
    document.getElementById('onlineOtpModal').classList.add('hidden');
  }

  function openSafeGuideModal() {
    document.getElementById('safeGuideModal').classList.remove('hidden');
  }

  function closeSafeGuideModal() {
    document.getElementById('safeGuideModal').classList.add('hidden');
  }

  // Countdown timer 10 phút
  let sec = 599;
  const timer = setInterval(() => {
    sec--;
    if (sec <= 0) {
      clearInterval(timer);
      document.getElementById('minBox').textContent = '00';
      document.getElementById('secBox').textContent = '00';
      return;
    }
    const m = String(Math.floor(sec / 60)).padStart(2, '0');
    const s = String(sec % 60).padStart(2, '0');
    document.getElementById('minBox').textContent = m;
    document.getElementById('secBox').textContent = s;
  }, 1000);

  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
  });
</script>
@endpush