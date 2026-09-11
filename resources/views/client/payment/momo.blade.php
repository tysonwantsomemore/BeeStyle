@extends('layouts.client')

@section('title', 'Thanh Toán Ví MoMo — Đơn Hàng #' . $order->order_code)

@section('content')
<main class="w-full flex-grow py-12 px-6 max-w-4xl mx-auto">
  
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-neutral-200">
    <div>
      <span class="text-xs tracking-widest uppercase text-pink-700 font-semibold block mb-1">CỔNG THANH TOÁN VÍ MOMO</span>
      <h1 class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-900">Đơn Hàng #{{ $order->order_code }}</h1>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-neutral-200 shadow-xl overflow-hidden">
    <div class="bg-pink-900 text-white p-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-pink-800 flex items-center justify-center font-bold text-white">
          M
        </div>
        <div>
          <h2 class="font-serif-luxury text-xl font-semibold">Thanh Toán Ví Điện Tử MoMo</h2>
          <p class="text-xs text-pink-200 font-light">Quét mã QR bằng ứng dụng MoMo để thanh toán tức thì</p>
        </div>
      </div>
      <span class="text-xs text-pink-200 font-semibold flex items-center gap-1">
        <i data-lucide="shield-check" class="w-4 h-4"></i> Bảo mật MoMo
      </span>
    </div>

    <div class="p-6 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8 items-center text-xs">
      <div class="space-y-4">
        <div class="p-5 bg-pink-50/50 rounded-xl border border-pink-200 space-y-3">
          <div class="flex justify-between">
            <span class="text-neutral-500">Khách hàng:</span>
            <strong class="text-neutral-900">{{ $order->customer_name }}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Số điện thoại:</span>
            <strong class="text-neutral-900">{{ $order->customer_phone }}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Tài khoản nhận:</span>
            <strong class="text-neutral-900 font-mono">0987654321 (BEESTYLE)</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-neutral-500">Nội dung chuyển:</span>
            <strong class="text-pink-900 bg-pink-100 px-2 py-0.5 rounded font-mono">{{ $order->order_code }}</strong>
          </div>
        </div>

        <div class="flex justify-between items-baseline pt-2">
          <span class="text-neutral-600 uppercase font-semibold text-[11px]">Tổng Số Tiền:</span>
          <span class="font-serif-luxury text-3xl font-bold text-neutral-950">
            {{ number_format($order->total_amount, 0, ',', '.') }}₫
          </span>
        </div>
      </div>

      <div class="text-center p-6 bg-neutral-50 rounded-xl border border-neutral-200 flex flex-col items-center">
        <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-600 mb-3">Mở MoMo quét mã thanh toán</span>
        <div class="bg-white p-3 rounded-xl border border-neutral-300 shadow-md">
          <img src="https://api.vietqr.io/image/970422-0987654321-compact2.jpg?amount={{ $order->total_amount }}&addInfo={{ $order->order_code }}&accountName=BEESTYLE%20MOMO" alt="MoMo QR" class="w-56 h-56 object-contain">
        </div>
        <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="mt-4 px-6 py-2.5 bg-pink-700 text-white font-semibold uppercase tracking-wider text-xs rounded-lg hover:bg-pink-800 transition-colors">
          Đã Thanh Toán &rarr; Kiểm Tra
        </a>
      </div>
    </div>
  </div>
</main>
@endsection
