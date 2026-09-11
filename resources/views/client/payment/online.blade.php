@extends('layouts.client')

@section('title', 'Cổng Thanh Toán Trực Tuyến VietQR — Đơn Hàng #' . $order->order_code)

@section('content')
<main class="w-full flex-grow py-12 px-6 max-w-4xl mx-auto">
  
  <!-- Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-neutral-200">
    <div>
      <span class="text-xs tracking-widest uppercase text-amber-800 font-semibold block mb-1">CỔNG THANH TOÁN VIETQR 24/7</span>
      <h1 class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-900">Đơn Hàng #{{ $order->order_code }}</h1>
    </div>
    <div class="px-3.5 py-1.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-full text-xs font-semibold flex items-center gap-1.5">
      <i data-lucide="clock" class="w-3.5 h-3.5"></i>
      <span>Hết hạn sau: <strong id="onlineCountdown" class="font-mono">09:59</strong></span>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-neutral-200 shadow-xl overflow-hidden">
    
    <!-- Top banner -->
    <div class="bg-neutral-950 text-white p-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-neutral-800 flex items-center justify-center text-amber-400">
          <i data-lucide="credit-card" class="w-5 h-5"></i>
        </div>
        <div>
          <h2 class="font-serif-luxury text-xl font-semibold">Chuyển Khoản Tự Động Khớp Lệnh</h2>
          <p class="text-xs text-neutral-400 font-light">Hỗ trợ tất cả ngân hàng Việt Nam qua chuẩn VietQR NAPAS 247</p>
        </div>
      </div>
      <span class="text-xs text-emerald-400 font-semibold flex items-center gap-1">
        <i data-lucide="shield-check" class="w-4 h-4"></i> SSL 256-Bit
      </span>
    </div>

    @php
      $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
      $payAmount = $isDeposit ? $order->deposit_amount : $order->total_amount;
      $vietQrUrl = "https://img.vietqr.io/image/TCB-77427842310105-compact2.png?amount=" . $payAmount . "&addInfo=" . urlencode($order->order_code) . "&accountName=" . urlencode("NGUYEN XUAN BAC");
    @endphp

    <div class="p-6 md:p-10 grid grid-cols-1 md:grid-cols-12 gap-8 items-start text-xs">
      
      <!-- Cột trái: Thông tin đơn hàng & Số tiền cần chuyển (5 cols) -->
      <div class="md:col-span-5 space-y-4">
        
        <div class="p-5 bg-neutral-50 rounded-xl border border-neutral-200 space-y-3">
          <div class="flex justify-between pb-2 border-b border-neutral-200/60 font-semibold text-neutral-900">
            <span>Thông Tin Đơn Hàng</span>
            <span class="text-amber-800 text-[10px] uppercase font-mono">BeeStyle Atelier</span>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Khách hàng:</span>
            <strong class="text-neutral-900">{{ $order->customer_name }}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Số điện thoại:</span>
            <strong class="text-neutral-900">{{ $order->customer_phone }}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Mã đơn hàng:</span>
            <strong class="font-mono text-neutral-950">{{ $order->order_code }}</strong>
          </div>
        </div>

        <!-- Box Số tiền cần thanh toán -->
        @if($isDeposit)
          <div class="p-4 rounded-xl text-center bg-amber-50 border-2 border-dashed border-amber-300 space-y-1">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-200 text-amber-900 font-bold rounded-full text-[10px]">
              <i data-lucide="shield-alert" class="w-3 h-3"></i> ĐẶT CỌC 50% (ĐƠN SỐ LƯỢNG LỚN)
            </span>
            <span class="text-neutral-500 uppercase font-semibold text-[10px] block pt-1">Số tiền cọc cần chuyển ngay</span>
            <h2 class="font-serif-luxury text-2xl md:text-3xl font-bold text-rose-600 font-mono">
              {{ number_format($order->deposit_amount, 0, ',', '.') }}₫
            </h2>
            <p class="text-[11px] text-neutral-600 pt-1 border-t border-amber-200">
              Còn lại thu COD khi nhận hàng: <strong class="text-neutral-900 font-mono">{{ number_format($order->remaining_amount, 0, ',', '.') }}₫</strong>
            </p>
          </div>
        @else
          <div class="p-4 rounded-xl text-center bg-sky-50 border-2 border-dashed border-sky-300 space-y-1">
            <span class="text-neutral-500 uppercase font-semibold text-[10px] block">Số tiền cần thanh toán</span>
            <h2 class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-950 font-mono">
              {{ number_format($order->total_amount, 0, ',', '.') }}₫
            </h2>
            <span class="text-[10px] text-emerald-700 font-semibold block">Đã bao gồm VAT &amp; Phí vận chuyển</span>
          </div>
        @endif

        <!-- Danh sách tóm tắt tác phẩm -->
        <div class="p-4 bg-white rounded-xl border border-neutral-200">
          <span class="text-neutral-400 uppercase font-semibold text-[10px] tracking-wider block mb-2">Tác phẩm đặt may ({{ $order->items->count() }})</span>
          <div class="space-y-2 max-h-36 overflow-y-auto pr-1">
            @foreach($order->items as $it)
              <div class="flex items-center justify-between gap-2 text-[11px] text-neutral-600 pb-1.5 border-b border-neutral-100 last:border-0 last:pb-0">
                <span class="truncate max-w-[180px] font-medium text-neutral-800">{{ $it->product_name }} <span class="text-neutral-400">×{{ $it->quantity }}</span></span>
                <span class="font-semibold text-neutral-900 shrink-0">{{ number_format($it->subtotal ?? ($it->price * $it->quantity), 0, ',', '.') }}₫</span>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Hủy đơn & hoàn kho -->
        <div class="pt-1 text-center">
          <form action="{{ route('client.checkout.expire', $order->order_code) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này để hoàn trả số lượng sản phẩm về kho?')">
            @csrf
            <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold underline transition-colors">
              Hủy giao dịch &amp; hoàn trả giỏ hàng
            </button>
          </form>
        </div>

      </div>

      <!-- Cột phải: Mã QR VietQR & Chi tiết tài khoản (7 cols) -->
      <div class="md:col-span-7 space-y-4">
        
        <!-- Tab selector chuyển đổi view QR / Ngân hàng -->
        <div class="flex bg-neutral-100 p-1 rounded-xl gap-1">
          <button type="button" id="tabBtnQr" onclick="switchOnlineTab('qr')" class="flex-1 py-2 rounded-lg text-xs font-semibold bg-white text-neutral-900 shadow-xs transition-all flex items-center justify-center gap-1.5">
            <i data-lucide="qr-code" class="w-3.5 h-3.5 text-amber-700"></i> Quét Mã VietQR 24/7
          </button>
          <button type="button" id="tabBtnBank" onclick="switchOnlineTab('bank')" class="flex-1 py-2 rounded-lg text-xs font-semibold text-neutral-500 hover:text-neutral-900 transition-all flex items-center justify-center gap-1.5">
            <i data-lucide="building-2" class="w-3.5 h-3.5"></i> Danh Sách Ngân Hàng
          </button>
        </div>

        <!-- VIEW 1: QUÉT MÃ QR TECHCOMBANK NAPAS 247 -->
        <div id="tabContentQr" class="p-6 bg-neutral-50 rounded-2xl border border-neutral-200 text-center space-y-4">
          <div class="flex justify-between items-center px-1">
            <span class="px-2 py-0.5 bg-rose-100 text-rose-800 rounded font-mono font-bold text-[10px]">
              TECHCOMBANK NAPAS 247
            </span>
            <span class="text-emerald-700 font-semibold text-[11px] flex items-center gap-1">
              <i data-lucide="radio" class="w-3 h-3 text-emerald-600"></i> Tự động khớp lệnh 24/7
            </span>
          </div>

          <div class="inline-block bg-white p-3 rounded-2xl border border-neutral-300 shadow-sm">
            <img src="{{ $vietQrUrl }}" alt="VietQR Techcombank Payment" class="w-56 h-56 object-contain mx-auto rounded-lg">
          </div>
          <p class="text-[11px] text-neutral-500">Mở ứng dụng ngân hàng bất kỳ (Techcombank, VCB, MB...) để quét mã tự động điền số tiền và nội dung.</p>

          <!-- Chi tiết tài khoản dạng list trực quan -->
          <div class="bg-white p-4 rounded-xl border border-neutral-200 text-left space-y-2 text-[11px]">
            <div class="flex justify-between items-center pb-1.5 border-b border-neutral-100">
              <span class="text-neutral-500">Ngân hàng:</span>
              <strong class="text-neutral-900 font-semibold">Techcombank (TCB)</strong>
            </div>
            <div class="flex justify-between items-center pb-1.5 border-b border-neutral-100">
              <span class="text-neutral-500">Chủ tài khoản:</span>
              <strong class="text-neutral-900 font-bold tracking-wide">NGUYEN XUAN BAC</strong>
            </div>
            <div class="flex justify-between items-center pb-1.5 border-b border-neutral-100">
              <span class="text-neutral-500">Số tài khoản:</span>
              <div class="flex items-center gap-2">
                <strong class="font-mono text-neutral-950 font-bold" id="accNumberVal">77427842310105</strong>
                <button type="button" onclick="copyOnlineText('77427842310105', this)" class="px-2 py-0.5 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded font-semibold text-[10px] transition-colors">
                  Copy
                </button>
              </div>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-neutral-500">Nội dung chuyển khoản:</span>
              <div class="flex items-center gap-2">
                <strong class="font-mono px-2 py-0.5 bg-amber-100 border border-amber-300 text-amber-900 rounded font-bold">
                  {{ $order->order_code }}
                </strong>
                <button type="button" onclick="copyOnlineText('{{ $order->order_code }}', this)" class="px-2 py-0.5 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded font-semibold text-[10px] transition-colors">
                  Copy
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- VIEW 2: DANH SÁCH NGÂN HÀNG HỖ TRỢ -->
        <div id="tabContentBank" class="p-6 bg-neutral-50 rounded-2xl border border-neutral-200 hidden space-y-3">
          <label class="font-semibold text-neutral-800 block text-xs">Các ngân hàng hỗ trợ quét VietQR liên thông:</label>
          @php
            $banks = [
              ['name' => 'Techcombank', 'code' => 'TCB', 'bg' => 'bg-rose-600'],
              ['name' => 'Vietcombank', 'code' => 'VCB', 'bg' => 'bg-emerald-700'],
              ['name' => 'MB Bank', 'code' => 'MB', 'bg' => 'bg-blue-700'],
              ['name' => 'VietinBank', 'code' => 'CTG', 'bg' => 'bg-sky-700'],
              ['name' => 'BIDV', 'code' => 'BIDV', 'bg' => 'bg-teal-700'],
              ['name' => 'ACB Bank', 'code' => 'ACB', 'bg' => 'bg-blue-600'],
              ['name' => 'VPBank', 'code' => 'VPB', 'bg' => 'bg-emerald-600'],
              ['name' => 'TPBank', 'code' => 'TPB', 'bg' => 'bg-purple-700'],
            ];
          @endphp
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            @foreach($banks as $b)
              <div class="p-2.5 bg-white border border-neutral-200 rounded-xl text-center hover:border-neutral-400 transition-all cursor-pointer">
                <span class="inline-block text-white px-2 py-0.5 rounded font-bold text-[10px] mb-1 {{ $b['bg'] }}">{{ $b['code'] }}</span>
                <span class="block text-[11px] font-semibold text-neutral-800 truncate">{{ $b['name'] }}</span>
              </div>
            @endforeach
          </div>
          <div class="p-3 bg-sky-50 border border-sky-200 rounded-xl text-sky-900 text-[11px] flex items-center gap-2">
            <i data-lucide="info" class="w-4 h-4 shrink-0 text-sky-600"></i>
            <span>Chuyển tiền nhanh Napas 24/7 từ bất kỳ ứng dụng ngân hàng nào ở trên sẽ được kích hoạt đơn tức thì.</span>
          </div>
        </div>

        <!-- Nút xác nhận thanh toán -->
        <form action="{{ route('client.checkout.online.success', $order->order_code) }}" method="POST">
          @csrf
          <button type="submit" class="w-full py-3.5 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold text-xs tracking-wider uppercase rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
            <span>{{ $isDeposit ? 'Tôi Đã Chuyển Khoản 50% Tiền Cọc (Xác Nhận Ngay)' : 'Tôi Đã Chuyển Khoản Thành Công' }}</span>
          </button>
        </form>

        <div class="text-center">
          <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="text-neutral-500 hover:text-neutral-900 text-[11px] font-semibold transition-colors">
            Kiểm tra trạng thái đơn hàng trên hệ thống &rarr;
          </a>
        </div>

      </div>

    </div>

  </div>
</main>
@endsection

@push('scripts')
<script>
  // Đếm ngược 10 phút
  let sec = 599;
  const timer = setInterval(() => {
    sec--;
    if (sec <= 0) {
      clearInterval(timer);
      const cdEl = document.getElementById('onlineCountdown');
      if (cdEl) cdEl.textContent = '00:00';
      return;
    }
    const m = String(Math.floor(sec / 60)).padStart(2, '0');
    const s = String(sec % 60).padStart(2, '0');
    const cdEl = document.getElementById('onlineCountdown');
    if (cdEl) cdEl.textContent = `${m}:${s}`;
  }, 1000);

  // Chuyển tab giữa mã QR và danh sách Bank
  function switchOnlineTab(tab) {
    const qrContent = document.getElementById('tabContentQr');
    const bankContent = document.getElementById('tabContentBank');
    const btnQr = document.getElementById('tabBtnQr');
    const btnBank = document.getElementById('tabBtnBank');

    if (tab === 'bank') {
      qrContent?.classList.add('hidden');
      bankContent?.classList.remove('hidden');
      btnBank?.classList.add('bg-white', 'text-neutral-900', 'shadow-xs');
      btnBank?.classList.remove('text-neutral-500');
      btnQr?.classList.remove('bg-white', 'text-neutral-900', 'shadow-xs');
      btnQr?.classList.add('text-neutral-500');
    } else {
      bankContent?.classList.add('hidden');
      qrContent?.classList.remove('hidden');
      btnQr?.classList.add('bg-white', 'text-neutral-900', 'shadow-xs');
      btnQr?.classList.remove('text-neutral-500');
      btnBank?.classList.remove('bg-white', 'text-neutral-900', 'shadow-xs');
      btnBank?.classList.add('text-neutral-500');
    }
  }

  // Copy nhanh
  function copyOnlineText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
      const orig = btn.textContent;
      btn.textContent = 'Đã chép!';
      setTimeout(() => { btn.textContent = orig; }, 1500);
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
  });
</script>
@endpush