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

    <div class="p-6 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8 items-center text-xs">
      
      <!-- Order Info -->
      <div class="space-y-4">
        <div class="p-5 bg-brand-50 rounded-xl border border-brand-200 space-y-3">
          <div class="flex justify-between">
            <span class="text-neutral-500">Khách hàng:</span>
            <strong class="text-neutral-900">{{ $order->customer_name }}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Số điện thoại:</span>
            <strong class="text-neutral-900">{{ $order->customer_phone }}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Ngân hàng thụ hưởng:</span>
            <strong class="text-neutral-900">MB Bank (Quân Đội)</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Số tài khoản:</span>
            <strong class="text-neutral-900 font-mono">0987654321</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Chủ tài khoản:</span>
            <strong class="text-neutral-900 font-mono">BEESTYLE ATELIER</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Nội dung chuyển khoản:</span>
            <strong class="text-amber-900 bg-amber-100 px-2 py-0.5 rounded font-mono">{{ $order->order_code }}</strong>
          </div>
        </div>

        <div class="flex justify-between items-baseline pt-2">
          <span class="text-neutral-600 uppercase font-semibold text-[11px]">Tổng Số Tiền:</span>
          <span class="font-serif-luxury text-3xl font-bold text-neutral-950">
            {{ number_format($order->total_amount, 0, ',', '.') }}₫
          </span>
        </div>
      </div>

      <!-- VietQR Image -->
      <div class="text-center p-6 bg-neutral-50 rounded-xl border border-neutral-200 flex flex-col items-center">
        <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 mb-3">Mở ứng dụng ngân hàng quét mã QR</span>
        <div class="bg-white p-3 rounded-xl border border-neutral-300 shadow-md">
          <img src="https://api.vietqr.io/image/970422-0987654321-compact2.jpg?amount={{ $order->total_amount }}&addInfo={{ $order->order_code }}&accountName=BEESTYLE%20ATELIER" alt="VietQR" class="w-56 h-56 object-contain">
        </div>
        <p class="text-[10px] text-neutral-400 mt-3">Hệ thống sẽ tự động xác nhận đơn hàng sau khi nhận được tiền.</p>
        <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="mt-4 px-6 py-2.5 bg-neutral-950 text-white font-semibold uppercase tracking-wider text-xs rounded-lg hover:bg-neutral-800 transition-colors">
          Đã Chuyển Khoản &rarr; Kiểm Tra
        </a>
      </div>

    </div>

  </div>
</main>
@endsection

@push('scripts')
<script>
  let sec = 599;
  const timer = setInterval(() => {
    sec--;
    if (sec <= 0) {
      clearInterval(timer);
      document.getElementById('onlineCountdown').textContent = '00:00';
      return;
    }
    const m = String(Math.floor(sec / 60)).padStart(2, '0');
    const s = String(sec % 60).padStart(2, '0');
    document.getElementById('onlineCountdown').textContent = `${m}:${s}`;
  }, 1000);
</script>
@endpush
