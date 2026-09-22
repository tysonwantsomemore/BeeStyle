@extends('layouts.client')

@section('title', 'Kết Quả Thanh Toán MoMo Payment' . ($order ? ' | Đơn Hàng #' . $order->order_code : ''))

@section('content')
<div class="container py-10 md:py-16 mx-auto px-4" style="max-width: 860px;">
  
  <div class="border-0 shadow-2xl overflow-hidden rounded-3xl bg-white border border-neutral-200">
    
    <!-- MOMO PAYMENT HEADER -->
    <div class="p-6 md:p-8 text-white flex justify-between items-center flex-wrap gap-4" 
         style="background: linear-gradient(135deg, #a50064 0%, #d82d8b 100%);">
      <div class="flex items-center gap-4">
        <div class="bg-white rounded-2xl p-1.5 shadow-md flex items-center justify-center shrink-0" style="width: 52px; height: 52px;">
          <img src="{{ asset('assets/img/logos/momo.svg') }}" alt="MoMo Logo" class="w-full h-full rounded-xl object-contain" onerror="this.src='{{ asset('assets/img/logos/momo.png') }}'">
        </div>
        <div>
          <div class="flex items-center gap-2 flex-wrap">
            <h1 class="font-bold text-lg md:text-2xl text-white mb-0">Cổng Thanh Toán MoMo Payment</h1>
            <span class="text-[10px] bg-white/20 text-white px-2.5 py-0.5 rounded-full font-bold">ATM Gateway V2</span>
          </div>
          <p class="text-white text-opacity-90 text-xs mt-1">Hệ thống xử lý thanh toán trực tuyến bảo mật tiêu chuẩn quốc tế PCI DSS</p>
        </div>
      </div>
      <div class="flex items-center gap-1.5 text-white text-xs bg-black/20 px-3.5 py-1.5 rounded-full border border-white/15">
        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-300"></i>
        <span>Bảo mật <strong>SSL 256-Bit</strong></span>
      </div>
    </div>

    <!-- MAIN BODY -->
    <div class="p-6 md:p-12 text-center">

      @php
        $isPaid = ($order && in_array(strtoupper((string)$order->payment_status), ['PAID', 'DEPOSIT_PAID'])) || $resultCode === 0;
        $isCancelled = ($order && strtoupper((string)$order->payment_status) === 'CANCELLED') || $resultCode === 1006;
        $isExpired = ($order && strtoupper((string)$order->payment_status) === 'EXPIRED') || $resultCode === 49;
      @endphp

      @if($isPaid)
        <!-- TRẠNG THÁI THÀNH CÔNG -->
        <div class="mb-5">
          <div class="rounded-full bg-emerald-50 text-emerald-600 inline-flex items-center justify-center shadow-sm border border-emerald-200" 
               style="width: 84px; height: 84px;">
            <i data-lucide="check-circle" class="w-12 h-12 text-emerald-600"></i>
          </div>
        </div>

        <span class="text-xs uppercase tracking-widest text-emerald-600 font-bold block mb-1">GIAO DỊCH XÁC THỰC THÀNH CÔNG</span>
        <h2 class="font-black text-2xl md:text-3xl text-neutral-950 mb-3">THANH TOÁN THÀNH CÔNG!</h2>
        <p class="text-neutral-600 mb-8 text-xs md:text-sm max-w-xl mx-auto leading-relaxed">
          Cảm ơn bạn đã lựa chọn mua sắm tại BeeStyle. Giao dịch qua <strong class="text-[#a50064]">Cổng Thanh Toán MoMo Payment</strong> cho đơn hàng <strong class="text-neutral-950 font-mono">#{{ $order ? $order->order_code : '' }}</strong> đã được xử lý hoàn tất.
        </p>

        @if($order)
        <div class="p-6 bg-neutral-50 rounded-2xl border border-neutral-200 text-left mb-8 max-w-xl mx-auto shadow-sm">
          <div class="flex items-center justify-between pb-3 mb-4 border-b border-neutral-200">
            <span class="font-bold text-neutral-900 text-xs uppercase tracking-wider flex items-center gap-2">
              <i data-lucide="receipt" class="w-4 h-4 text-[#a50064]"></i>
              <span>Biên Nhận Giao Dịch MoMo</span>
            </span>
            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full text-[10px] font-black border border-emerald-200 flex items-center gap-1">
              <i data-lucide="check" class="w-3 h-3"></i> ĐÃ THANH TOÁN
            </span>
          </div>

          <div class="flex flex-col gap-3 text-xs text-neutral-700">
            <div class="flex justify-between items-center">
              <span class="text-neutral-500">Mã đơn hàng:</span>
              <strong class="font-mono text-neutral-950 font-bold text-sm">#{{ $order->order_code }}</strong>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-neutral-500">Mã giao dịch MoMo:</span>
              <strong class="font-mono text-neutral-900 font-semibold">{{ $order->momo_trans_id ?: ($transId ?: 'MOMO_' . time()) }}</strong>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-neutral-500">Người nhận hàng:</span>
              <span class="text-neutral-900 font-semibold">{{ $order->customer_name }} ({{ $order->customer_phone }})</span>
            </div>
            <div class="flex justify-between items-start gap-3">
              <span class="text-neutral-500 shrink-0">Địa chỉ nhận hàng:</span>
              <span class="text-neutral-800 text-right max-w-xs text-[11px] leading-tight font-medium" title="{{ $order->full_shipping_address }}">
                {{ $order->full_shipping_address }}
              </span>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-neutral-200/80">
              <span class="text-neutral-500">Phương thức thanh toán:</span>
              <span class="text-neutral-950 font-semibold flex items-center gap-2">
                <img src="{{ asset('assets/img/logos/momo.svg') }}" alt="MoMo" class="w-4 h-4 object-contain" onerror="this.src='{{ asset('assets/img/logos/momo.png') }}'">
                <span>MoMo Payment (Thẻ ATM Nội Địa)</span>
              </span>
            </div>

            @if($order->is_deposit_required)
              <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 mt-2 space-y-1.5 text-amber-900 text-xs">
                <div class="flex justify-between font-medium">
                  <span>Tiền cọc 50% đã thanh toán:</span>
                  <strong class="font-mono text-emerald-700 font-bold text-sm">{{ number_format($order->deposit_amount, 0, ',', '.') }}₫</strong>
                </div>
                <div class="flex justify-between text-neutral-600 text-[11px]">
                  <span>Còn lại thanh toán khi nhận hàng (COD):</span>
                  <strong class="font-mono text-neutral-900">{{ number_format($order->remaining_amount, 0, ',', '.') }}₫</strong>
                </div>
              </div>
            @endif

            <div class="flex justify-between items-baseline pt-3 border-t border-neutral-200 mt-2">
              <span class="text-neutral-900 font-bold uppercase text-xs">Tổng tiền đã thanh toán:</span>
              <span class="font-serif-luxury text-2xl font-bold text-[#a50064]">
                {{ number_format($order->is_deposit_required ? $order->deposit_amount : $order->total_amount, 0, ',', '.') }}₫
              </span>
            </div>
          </div>
        </div>
        @endif

        <div class="flex justify-center gap-3 flex-wrap pt-2">
          @if($order)
            <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" 
               class="px-6 py-3 text-white font-bold text-xs rounded-xl shadow-md inline-flex items-center gap-2 hover:opacity-95 hover:shadow-lg transition-all"
               style="background: linear-gradient(135deg, #a50064, #d82d8b);">
              <i data-lucide="truck" class="w-4 h-4"></i>
              <span>Tra Cứu Hành Trình Đơn Hàng</span>
            </a>
          @endif
          <a href="{{ route('client.home') }}" class="px-6 py-3 bg-neutral-100 hover:bg-neutral-200 text-neutral-800 font-bold text-xs rounded-xl transition-all border border-neutral-300 inline-flex items-center gap-2">
            <i data-lucide="home" class="w-4 h-4"></i>
            <span>Về Trang Chủ</span>
          </a>
        </div>

      @elseif($isCancelled)
        <!-- TRẠNG THÁI KHÁCH HỦY GIAO DỊCH -->
        <div class="mb-5">
          <div class="rounded-full bg-amber-50 text-amber-600 inline-flex items-center justify-center shadow-sm border border-amber-200" 
               style="width: 84px; height: 84px;">
            <i data-lucide="alert-triangle" class="w-12 h-12 text-amber-600"></i>
          </div>
        </div>

        <span class="text-xs uppercase tracking-widest text-amber-600 font-bold block mb-1">GIAO DỊCH ĐÃ ĐƯỢC HỦY</span>
        <h2 class="font-black text-2xl md:text-3xl text-neutral-900 mb-3">BẠN ĐÃ HỦY GIAO DỊCH MOMO</h2>
        <p class="text-neutral-600 mb-8 text-xs md:text-sm max-w-lg mx-auto leading-relaxed">
          Giao dịch thanh toán qua <strong class="text-[#a50064]">MoMo Payment</strong> cho đơn hàng <strong class="text-neutral-900 font-mono">#{{ $order ? $order->order_code : '' }}</strong> chưa được trừ tiền. Bạn có thể bấm nút dưới đây để kết nối lại cổng MoMo hoặc quay về giỏ hàng.
        </p>

        <div class="flex justify-center gap-3 flex-wrap">
          @if($order)
            <form action="{{ route('client.checkout.momo.redirect', $order->order_code) }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="px-6 py-3 text-white font-bold text-xs rounded-xl shadow-md inline-flex items-center gap-2 hover:opacity-95 hover:shadow-lg transition-all cursor-pointer"
                      style="background: linear-gradient(135deg, #a50064, #d82d8b);">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Thanh Toán Lại Qua Cổng MoMo (ATM)</span>
              </button>
            </form>
          @endif
          <a href="{{ route('client.cart') }}" class="px-6 py-3 bg-neutral-900 text-white font-bold text-xs rounded-xl hover:bg-neutral-800 transition-all inline-flex items-center gap-2 shadow-sm">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
            <span>Quay Lại Giỏ Hàng</span>
          </a>
          <a href="{{ route('client.products.index') }}" class="px-6 py-3 bg-neutral-100 hover:bg-neutral-200 text-neutral-800 font-bold text-xs rounded-xl transition-all border border-neutral-300">
            Tiếp Tục Mua Sắm
          </a>
        </div>

      @elseif($isExpired)
        <!-- TRẠNG THÁI HẾT HẠN -->
        <div class="mb-5">
          <div class="rounded-full bg-neutral-100 text-neutral-500 inline-flex items-center justify-center shadow-sm border border-neutral-300" 
               style="width: 84px; height: 84px;">
            <i data-lucide="clock" class="w-12 h-12 text-neutral-500"></i>
          </div>
        </div>

        <span class="text-xs uppercase tracking-widest text-neutral-500 font-bold block mb-1">PHIÊN KẾT NỐI HẾT HẠN</span>
        <h2 class="font-black text-2xl md:text-3xl text-neutral-800 mb-3">PHIÊN GIAO DỊCH ĐÃ HẾT HẠN</h2>
        <p class="text-neutral-600 mb-8 text-xs md:text-sm max-w-lg mx-auto leading-relaxed">
          Phiên giao dịch thanh toán MoMo Payment đã quá hạn thời gian cho phép. Quý khách vui lòng tiến hành đặt hàng hoặc thanh toán lại để giữ sản phẩm.
        </p>

        <div class="flex justify-center gap-3 flex-wrap">
          @if($order)
            <form action="{{ route('client.checkout.momo.redirect', $order->order_code) }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="px-6 py-3 text-white font-bold text-xs rounded-xl shadow-md inline-flex items-center gap-2 hover:opacity-95 transition-all cursor-pointer"
                      style="background: linear-gradient(135deg, #a50064, #d82d8b);">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Tạo Phiên Mới Trên MoMo (ATM)</span>
              </button>
            </form>
          @endif
          <a href="{{ route('client.cart') }}" class="px-6 py-3 bg-neutral-900 text-white font-bold text-xs rounded-xl hover:bg-neutral-800 transition-all shadow-sm inline-flex items-center gap-2">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
            <span>Quay Lại Giỏ Hàng</span>
          </a>
        </div>

      @else
        <!-- TRẠNG THÁI THẤT BẠI HOẶC CHƯA HOÀN TẤT -->
        <div class="mb-5">
          <div class="rounded-full bg-rose-50 text-rose-600 inline-flex items-center justify-center shadow-sm border border-rose-200" 
               style="width: 84px; height: 84px;">
            <i data-lucide="x-circle" class="w-12 h-12 text-rose-600"></i>
          </div>
        </div>

        <span class="text-xs uppercase tracking-widest text-rose-600 font-bold block mb-1">GIAO DỊCH CHƯA HOÀN TẤT</span>
        <h2 class="font-black text-2xl md:text-3xl text-rose-700 mb-3">THANH TOÁN CHƯA HOÀN TẤT</h2>
        <p class="text-neutral-600 mb-8 text-xs md:text-sm max-w-lg mx-auto leading-relaxed">
          {{ $message ?: 'Đã xảy ra sự cố trong quá trình xử lý giao dịch qua cổng MoMo Payment.' }} 
          @if($resultCode)<span class="block text-xs text-neutral-400 mt-2 font-mono">(Mã lỗi từ MoMo: {{ $resultCode }})</span>@endif
        </p>

        <div class="flex justify-center gap-3 flex-wrap">
          @if($order)
            <form action="{{ route('client.checkout.momo.redirect', $order->order_code) }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="px-6 py-3 text-white font-bold text-xs rounded-xl shadow-md inline-flex items-center gap-2 hover:opacity-95 transition-all cursor-pointer"
                      style="background: linear-gradient(135deg, #a50064, #d82d8b);">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                <span>Thử Thanh Toán Lại (MoMo ATM)</span>
              </button>
            </form>
          @endif
          <a href="{{ route('client.cart') }}" class="px-6 py-3 bg-neutral-100 hover:bg-neutral-200 text-neutral-800 font-bold text-xs rounded-xl transition-all border border-neutral-300 inline-flex items-center gap-2">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
            <span>Quay Lại Giỏ Hàng</span>
          </a>
        </div>

      @endif

    </div>

    <!-- FOOTER -->
    <div class="p-4 text-center text-neutral-500 text-xs border-t border-neutral-200 bg-neutral-50 flex items-center justify-center gap-2">
      <i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-600"></i>
      <span>Giao dịch được bảo đảm an toàn bởi Cổng Thanh Toán MoMo Payment &amp; BeeStyle Atelier.</span>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
      window.lucide.createIcons();
    }
  });
</script>
@endpush