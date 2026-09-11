@extends('layouts.client')

@section('title', 'Thanh Toán Ví ZaloPay — Đơn Hàng #' . $order->order_code)

@section('content')
<main class="w-full flex-grow py-12 px-6 max-w-4xl mx-auto">
  
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-neutral-200">
    <div>
      <span class="text-xs tracking-widest uppercase text-sky-700 font-semibold block mb-1">CỔNG THANH TOÁN VÍ ZALOPAY</span>
      <h1 class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-900">Đơn Hàng #{{ $order->order_code }}</h1>
    </div>
    <div class="px-3.5 py-1.5 bg-sky-50 border border-sky-200 text-sky-800 rounded-full text-xs font-semibold flex items-center gap-1.5">
      <i data-lucide="clock" class="w-3.5 h-3.5 text-sky-600"></i>
      <span>Hết hạn sau: <strong id="zaloCountdown" class="font-mono">14:59</strong></span>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-neutral-200 shadow-xl overflow-hidden">
    <!-- Header banner -->
    <div class="bg-sky-900 text-white p-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-sky-800 flex items-center justify-center font-bold text-white shadow-inner">
          Z
        </div>
        <div>
          <h2 class="font-serif-luxury text-xl font-semibold">Thanh Toán Ví Điện Tử ZaloPay Gateway</h2>
          <p class="text-xs text-sky-200 font-light">Quét mã QR bằng ứng dụng Zalo, ZaloPay hoặc App Ngân Hàng để thanh toán tức thì</p>
        </div>
      </div>
      <span class="text-xs text-sky-200 font-semibold flex items-center gap-1">
        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i> Bảo mật ZaloPay
      </span>
    </div>

    @php
      $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
      $payAmount = $isDeposit ? $order->deposit_amount : $order->total_amount;
      $zaloQrUrl = "https://img.vietqr.io/image/TCB-77427842310105-compact2.png?amount=" . $payAmount . "&addInfo=" . urlencode($order->order_code) . "&accountName=" . urlencode("NGUYEN XUAN BAC");
    @endphp

    <div class="p-6 md:p-10 space-y-6">
      
      <!-- Live Radar Status Box -->
      <div class="p-3.5 rounded-xl border border-sky-200 bg-sky-50 text-center text-xs text-sky-900">
        <div class="flex items-center justify-center gap-2 font-bold uppercase tracking-wider text-[11px]">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-sky-600"></span>
          </span>
          <span>Hệ thống đang tự động lắng nghe giao dịch từ ví ZaloPay...</span>
        </div>
        <p class="text-[11px] text-neutral-600 mt-1">
          Quét mã QR bên dưới. Sau khi hệ thống nhận được tiền, giao dịch sẽ được kích hoạt tức thì.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start text-xs">
        
        <!-- Cột 1: Thông tin đơn hàng & Số tiền (5 cols) -->
        <div class="md:col-span-5 space-y-4">
          <div class="p-5 bg-neutral-50 rounded-xl border border-neutral-200 space-y-2.5">
            <div class="flex justify-between pb-2 border-b border-neutral-200/70 font-semibold text-neutral-900">
              <span>Thông Tin Đơn Hàng</span>
              <span class="text-sky-800 text-[10px] uppercase font-mono">BeeStyle Menswear</span>
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
              <span class="text-neutral-500">Mã giao dịch:</span>
              <strong class="font-mono text-neutral-950">{{ $order->order_code }}</strong>
            </div>
          </div>

          <!-- Box Số tiền cần thanh toán -->
          @if($isDeposit)
            <div class="p-4 rounded-xl text-center bg-amber-50 border-2 border-dashed border-amber-300 space-y-1">
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-200 text-amber-900 font-bold rounded-full text-[10px]">
                <i data-lucide="shield-alert" class="w-3 h-3"></i> CHÍNH SÁCH ĐẶT CỌC 50%
              </span>
              <span class="text-neutral-500 uppercase font-semibold text-[10px] block pt-1">Số tiền cọc cần chuyển (50%)</span>
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
              <h2 class="font-serif-luxury text-2xl md:text-3xl font-bold text-sky-900 font-mono">
                {{ number_format($order->total_amount, 0, ',', '.') }}₫
              </h2>
              <span class="text-[10px] text-emerald-700 font-semibold block">Đã bao gồm VAT &amp; Phí vận chuyển</span>
            </div>
          @endif

          <!-- Danh sách tóm tắt tác phẩm -->
          <div class="p-4 bg-white rounded-xl border border-neutral-200">
            <span class="text-neutral-400 uppercase font-semibold text-[10px] tracking-wider block mb-2">Sản phẩm đặt mua ({{ $order->items->count() }})</span>
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

        <!-- Cột 2: Mã QR ZaloPay & Hướng dẫn thanh toán (7 cols) -->
        <div class="md:col-span-7 space-y-4">
          
          <div class="p-6 bg-neutral-50 rounded-2xl border border-neutral-200 text-center space-y-4">
            <div class="flex justify-between items-center px-1">
              <span class="px-2.5 py-0.5 bg-[#008fe5] text-white rounded-md font-bold text-[10px]">
                ZALOPAY QR 24/7
              </span>
              <span class="text-emerald-700 font-semibold text-[11px] flex items-center gap-1">
                <i data-lucide="radio" class="w-3 h-3 text-emerald-600"></i> Tự động khớp lệnh
              </span>
            </div>

            <div class="inline-block bg-white p-3 rounded-2xl border border-neutral-300 shadow-sm">
              <img src="{{ $zaloQrUrl }}" alt="ZaloPay QR Code" class="w-56 h-56 object-contain mx-auto rounded-lg">
            </div>
            <p class="text-[11px] text-neutral-500">Quét mã bằng ứng dụng <strong>ZaloPay</strong>, <strong>Zalo</strong> hoặc App Ngân Hàng bất kỳ.</p>

            <!-- Chi tiết tài khoản nhận -->
            <div class="bg-white p-4 rounded-xl border border-neutral-200 text-left space-y-2 text-[11px]">
              <div class="flex justify-between items-center pb-1.5 border-b border-neutral-100">
                <span class="text-neutral-500">Chủ tài khoản:</span>
                <strong class="text-neutral-900 font-bold tracking-wide">NGUYEN XUAN BAC</strong>
              </div>
              <div class="flex justify-between items-center pb-1.5 border-b border-neutral-100">
                <span class="text-neutral-500">Ngân hàng thụ hưởng:</span>
                <strong class="text-neutral-900 font-semibold">Techcombank (TCB)</strong>
              </div>
              <div class="flex justify-between items-center pb-1.5 border-b border-neutral-100">
                <span class="text-neutral-500">Số tài khoản:</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono text-neutral-950 font-bold">77427842310105</strong>
                  <button type="button" onclick="copyZaloText('77427842310105', this)" class="px-2 py-0.5 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded font-semibold text-[10px] transition-colors">
                    Copy
                  </button>
                </div>
              </div>
              <div class="flex justify-between items-center pb-1.5 border-b border-neutral-100">
                <span class="text-neutral-500">{{ $isDeposit ? 'Số tiền cọc cần chuyển (50%):' : 'Số tiền cần chuyển:' }}</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono {{ $isDeposit ? 'text-rose-600' : 'text-sky-900' }} font-bold text-sm">
                    {{ number_format($payAmount, 0, ',', '.') }}₫
                  </strong>
                  <button type="button" onclick="copyZaloText('{{ $payAmount }}', this)" class="px-2 py-0.5 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded font-semibold text-[10px] transition-colors">
                    Copy
                  </button>
                </div>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-neutral-500">Nội dung chuyển tiền:</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono px-2 py-0.5 bg-sky-100 border border-sky-300 text-sky-900 rounded font-bold">
                    {{ $order->order_code }}
                  </strong>
                  <button type="button" onclick="copyZaloText('{{ $order->order_code }}', this)" class="px-2 py-0.5 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded font-semibold text-[10px] transition-colors">
                    Copy
                  </button>
                </div>
              </div>
            </div>

            <!-- 3 Bước Hướng Dẫn -->
            <div class="text-left bg-white p-3 rounded-xl border border-neutral-200 text-[11px] text-neutral-600 space-y-1">
              <div><strong class="text-neutral-900">Bước 1:</strong> Mở ứng dụng <strong>ZaloPay</strong>, <strong>Zalo</strong> hoặc App Ngân Hàng.</div>
              <div><strong class="text-neutral-900">Bước 2:</strong> Quét mã QR và kiểm tra số tiền khớp đúng với đơn hàng.</div>
              <div><strong class="text-neutral-900">Bước 3:</strong> Xác nhận thanh toán &rarr; Hệ thống tự động xác nhận đơn ngay lập tức!</div>
            </div>
          </div>

          <!-- Nút hành động -->
          <form action="{{ route('client.checkout.zalopay.success', $order->order_code) }}" method="POST" id="zaloSuccessForm">
            @csrf
            <button type="submit" class="w-full py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-semibold text-xs tracking-wider uppercase rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
              <i data-lucide="check-circle-2" class="w-4 h-4"></i>
              <span>{{ $isDeposit ? 'Tôi Đã Chuyển Khoản 50% Tiền Cọc (Xác Nhận Ngay)' : 'Tôi Đã Thanh Toán Qua ZaloPay (Xác Nhận Ngay)' }}</span>
            </button>
          </form>

          <div class="flex justify-between items-center px-1 text-[11px]">
            <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="text-neutral-500 hover:text-neutral-900 font-semibold transition-colors">
              Kiểm tra tình trạng đơn hàng &rarr;
            </a>
          </div>

        </div>

      </div>

    </div>

  </div>
</main>
@endsection

@push('scripts')
<script>
  // Đếm ngược 15 phút
  let sec = 15 * 60 - 1;
  const timer = setInterval(() => {
    sec--;
    if (sec <= 0) {
      clearInterval(timer);
      const cdEl = document.getElementById('zaloCountdown');
      if (cdEl) cdEl.textContent = '00:00';
      return;
    }
    const m = String(Math.floor(sec / 60)).padStart(2, '0');
    const s = String(sec % 60).padStart(2, '0');
    const cdEl = document.getElementById('zaloCountdown');
    if (cdEl) cdEl.textContent = `${m}:${s}`;
  }, 1000);

  function copyZaloText(text, btn) {
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