@extends('layouts.client')

@section('title', 'Tra Cứu Đơn Hàng — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-5xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Tra Cứu &amp; Thanh Toán Đơn Hàng</span>
  </nav>

  <!-- Search Header Card -->
  <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm mb-8">
    <div class="max-w-2xl mx-auto text-center mb-6">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-semibold block mb-1">DỊCH VỤ TRỰC TUYẾN</span>
      <h1 class="font-serif-luxury text-2xl md:text-3xl font-light text-neutral-900">Tra Cứu Hành Trình Đơn Hàng</h1>
      <p class="text-xs text-neutral-500 mt-1 font-light">Nhập mã đơn hàng (Ví dụ: BEE-2026-0901-XXXX) để xem tiến độ đóng gói và vận chuyển</p>
    </div>

    <form action="{{ route('client.order-tracking') }}" method="GET" class="max-w-lg mx-auto flex gap-2">
      <div class="relative flex-grow">
        <i data-lucide="search" class="w-4 h-4 text-neutral-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
        <input type="text" name="code" value="{{ $code ?? '' }}" placeholder="Nhập mã đơn hàng..." required
          class="w-full pl-10 pr-4 py-3 bg-neutral-50 border border-neutral-200 rounded-xl text-xs uppercase font-mono text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
      </div>
      <button type="submit" class="px-6 py-3 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-wider uppercase rounded-xl transition-colors shadow">
        Tra Cứu
      </button>
    </form>
  </div>

  @if(isset($currentOrder) && $currentOrder)
    <!-- Order Details View -->
    <div class="space-y-6">
      
      <!-- Order Status Summary Banner -->
      <div class="bg-neutral-950 text-white p-6 md:p-8 rounded-2xl shadow-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <div class="flex items-center gap-3 mb-1">
            <span class="text-xs tracking-widest text-amber-400 uppercase font-mono">ĐƠN HÀNG: {{ $currentOrder->order_code }}</span>
            <span class="px-2 py-0.5 bg-neutral-800 text-neutral-300 rounded text-[10px] font-semibold uppercase">
              {{ $currentOrder->status_label ?? $currentOrder->status }}
            </span>
          </div>
          <h2 class="font-serif-luxury text-2xl font-light">Ngày đặt: {{ $currentOrder->created_at->format('d/m/Y H:i') }}</h2>
        </div>
        <div class="text-right">
          <span class="text-xs text-neutral-400 block mb-1">Tổng thanh toán:</span>
          <span class="font-serif-luxury text-2xl md:text-3xl font-bold text-amber-400">
            {{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫
          </span>
        </div>
      </div>

      <!-- Payment QR Card (if unpaid & online) -->
      @if(in_array($currentOrder->payment_method, ['online', 'momo', 'vietqr', 'zalopay', 'vnpay']) && $currentOrder->payment_status !== 'paid')
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-2xl text-xs text-amber-950 flex flex-col md:flex-row items-center justify-between gap-6">
          <div>
            <span class="px-2 py-0.5 bg-amber-200 text-amber-900 rounded font-bold uppercase text-[10px] inline-block mb-2">CHỜ THANH TOÁN</span>
            <h3 class="font-serif-luxury text-xl font-bold mb-1">Quét mã QR để hoàn tất thanh toán</h3>
            <p class="text-neutral-600 max-w-md">Vui lòng chuyển khoản đúng số tiền <strong>{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</strong> với nội dung chuyển khoản là mã đơn hàng <strong>{{ $currentOrder->order_code }}</strong>.</p>
          </div>
          <div class="bg-white p-3 rounded-xl border border-amber-300 shrink-0 text-center shadow-sm">
            <img src="https://api.vietqr.io/image/970422-0987654321-compact2.jpg?amount={{ $currentOrder->total_amount }}&addInfo={{ $currentOrder->order_code }}&accountName=BEESTYLE%20ATELIER" alt="VietQR Payment" class="w-48 h-48 object-contain mx-auto">
            <span class="text-[10px] text-neutral-500 mt-1 block">VietQR Tự Động Xác Nhận</span>
          </div>
        </div>
      @endif

      <!-- Delivery Details & Items Grid -->
      <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <!-- Items List (7 cols) -->
        <div class="md:col-span-7 bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm">
          <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 pb-3 mb-4 border-b border-neutral-100">Các Tác Phẩm Trong Đơn</h3>
          <div class="space-y-4">
            @foreach($currentOrder->items as $item)
              <div class="flex gap-4 items-center text-xs pb-3 border-b border-neutral-100 last:border-0 last:pb-0">
                <div class="w-14 h-16 bg-neutral-100 rounded-lg overflow-hidden shrink-0 border border-neutral-200">
                  <img src="{{ asset($item->product->primaryImage->image_path ?? $item->product->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=400&auto=format&fit=crop') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-grow">
                  <h4 class="font-semibold text-neutral-900 line-clamp-1">{{ $item->product_name }}</h4>
                  <p class="text-[11px] text-neutral-500">Số lượng: {{ $item->quantity }} | Đơn giá: {{ number_format($item->price, 0, ',', '.') }}₫</p>
                </div>
                <span class="font-serif-luxury text-sm font-bold text-neutral-950">
                  {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                </span>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Customer & Shipping Info (5 cols) -->
        <div class="md:col-span-5 bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm space-y-4 text-xs">
          <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 pb-3 border-b border-neutral-100">Thông Tin Giao Nhận</h3>
          <div>
            <span class="text-neutral-400 uppercase text-[10px] tracking-wider font-semibold block">Người nhận:</span>
            <p class="font-semibold text-neutral-900 text-sm mt-0.5">{{ $currentOrder->customer_name }} — {{ $currentOrder->customer_phone }}</p>
          </div>
          <div>
            <span class="text-neutral-400 uppercase text-[10px] tracking-wider font-semibold block">Địa chỉ giao hàng:</span>
            <p class="text-neutral-700 mt-0.5 leading-relaxed">{{ $currentOrder->shipping_address }}</p>
          </div>
          <div>
            <span class="text-neutral-400 uppercase text-[10px] tracking-wider font-semibold block">Hình thức thanh toán:</span>
            <p class="text-neutral-900 font-semibold uppercase mt-0.5">{{ $currentOrder->payment_method_label ?? $currentOrder->payment_method }}</p>
          </div>
        </div>

      </div>

    </div>
  @elseif(request('code'))
    <div class="bg-white p-12 rounded-2xl border border-neutral-200 text-center max-w-md mx-auto shadow-sm">
      <i data-lucide="package-x" class="w-12 h-12 mx-auto text-neutral-400 mb-3 stroke-1"></i>
      <h3 class="font-serif-luxury text-xl font-bold text-neutral-800 mb-1">Không tìm thấy đơn hàng</h3>
      <p class="text-xs text-neutral-500">Vui lòng kiểm tra lại mã đơn hàng <strong>{{ request('code') }}</strong> hoặc liên hệ tổng đài 1900 8899 để được hỗ trợ.</p>
    </div>
  @endif

</main>
@endsection
