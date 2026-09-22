@extends('layouts.client')

@section('title', 'Đặt Hàng Thành Công #' . $order->order_code . ' — BEESTYLE Atelier')

@section('content')
<main class="w-full flex-grow py-12 px-4 sm:px-6 max-w-5xl mx-auto">

  <!-- ========================================================================= -->
  <!-- 1. HERO SUCCESS BANNER -->
  <!-- ========================================================================= -->
  <div class="text-center mb-10">
    <div class="w-20 h-20 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm animate-bounce">
      <i data-lucide="check" class="w-10 h-10 stroke-[2.5]"></i>
    </div>
    <span class="text-xs tracking-[0.35em] uppercase text-emerald-800 font-bold block mb-1">CẢM ƠN BẠN ĐÃ LỰA CHỌN BEESTYLE</span>
    <h1 class="font-serif-luxury text-3xl sm:text-4xl md:text-5xl text-neutral-900 font-bold mb-2">
      Đặt Hàng Thành Công!
    </h1>
    <p class="text-xs sm:text-sm text-neutral-600 font-medium max-w-lg mx-auto leading-relaxed">
      Hệ thống xưởng may BeeStyle đã tiếp nhận đơn hàng của bạn. Thông tin xác nhận và hóa đơn điện tử đã được lưu trữ an toàn.
    </p>
  </div>

  <!-- ========================================================================= -->
  <!-- 2. ORDER CODE HIGHLIGHT CARD -->
  <!-- ========================================================================= -->
  <div class="bg-gradient-to-r from-neutral-900 via-neutral-950 to-neutral-900 text-white rounded-2xl p-6 sm:p-8 mb-8 shadow-xl border border-neutral-800 flex flex-col sm:flex-row items-center justify-between gap-6">
    <div>
      <span class="text-[11px] tracking-widest uppercase text-amber-400 font-bold block mb-1">MÃ ĐƠN HÀNG CỦA BẠN</span>
      <div class="flex items-center gap-3">
        <h2 class="font-mono text-2xl sm:text-3xl font-black tracking-wider text-white">
          #{{ $order->order_code }}
        </h2>
        <button type="button" onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); showGlobalToast('Đã sao chép mã đơn hàng', 'success')" class="p-1.5 bg-neutral-800 hover:bg-neutral-700 text-neutral-300 rounded-lg transition-colors" title="Sao chép mã đơn">
          <i data-lucide="copy" class="w-4 h-4"></i>
        </button>
      </div>
      <p class="text-xs text-neutral-400 mt-1">
        Thời gian đặt: {{ $order->created_at ? $order->created_at->format('H:i - d/m/Y') : now()->format('H:i - d/m/Y') }}
      </p>
    </div>

    <!-- Status Badges -->
    <div class="flex flex-col sm:items-end gap-2 text-xs">
      @if($order->payment_status === 'paid')
        <span class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 font-bold uppercase tracking-wider flex items-center gap-1.5">
          <i data-lucide="check-circle-2" class="w-4 h-4"></i> Đã Thanh Toán Trực Tuyến
        </span>
      @elseif($order->is_deposit_required && $order->deposit_status === 'paid')
        <span class="px-3.5 py-1.5 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/40 font-bold uppercase tracking-wider flex items-center gap-1.5">
          <i data-lucide="coins" class="w-4 h-4"></i> Đã Cọc 50% ({{ number_format($order->deposit_amount, 0, ',', '.') }}₫)
        </span>
      @else
        <span class="px-3.5 py-1.5 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/40 font-bold uppercase tracking-wider flex items-center gap-1.5">
          <i data-lucide="clock" class="w-4 h-4"></i> Chờ Thanh Toán Khi Nhận Hàng (COD)
        </span>
      @endif

      <span class="text-neutral-400 text-[11px]">
        Trạng thái: <strong class="text-amber-400 font-bold uppercase">{{ $order->shipping_status_text ?? 'Đang chuẩn bị hàng' }}</strong>
      </span>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- 3. DETAILS GRID: INFO & ORDER ITEMS -->
  <!-- ========================================================================= -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-10">

    <!-- CỘT TRÁI: THÔNG TIN GIAO NHẬN & THANH TOÁN (5 COLS) -->
    <div class="lg:col-span-5 space-y-6">
      
      <!-- Thông tin giao hàng -->
      <div class="bg-white p-6 rounded-2xl border border-neutral-200/90 shadow-sm space-y-4 text-xs">
        <div class="flex items-center gap-2 pb-3 border-b border-neutral-100">
          <i data-lucide="map-pin" class="w-4 h-4 text-amber-700"></i>
          <h3 class="font-serif-luxury text-base font-bold text-neutral-900">Địa Chỉ Nhận Hàng</h3>
        </div>

        <div class="space-y-2 leading-relaxed">
          <p class="text-sm font-bold text-neutral-950">{{ $order->customer_name }}</p>
          <p class="text-neutral-600 font-mono font-semibold">{{ $order->customer_phone }}</p>
          @if($order->customer_email)
            <p class="text-neutral-500">{{ $order->customer_email }}</p>
          @endif
          <p class="text-neutral-700 pt-1">
            {{ $order->shipping_address }}
            @if($order->ward), {{ $order->ward }}@endif
            @if($order->district), {{ $order->district }}@endif
            @if($order->city), {{ $order->city }}@endif
          </p>
          @if($order->notes)
            <div class="p-2.5 bg-neutral-50 rounded-lg text-neutral-600 text-[11px] border border-neutral-200 mt-2">
              <strong class="text-neutral-800">Ghi chú:</strong> {{ $order->notes }}
            </div>
          @endif
        </div>
      </div>

      <!-- Phương thức thanh toán & Vận chuyển -->
      <div class="bg-white p-6 rounded-2xl border border-neutral-200/90 shadow-sm space-y-4 text-xs">
        <div class="flex items-center gap-2 pb-3 border-b border-neutral-100">
          <i data-lucide="credit-card" class="w-4 h-4 text-amber-700"></i>
          <h3 class="font-serif-luxury text-base font-bold text-neutral-900">Thanh Toán &amp; Vận Chuyển</h3>
        </div>

        <div class="space-y-3">
          <div>
            <span class="text-[10px] uppercase text-neutral-400 font-bold block mb-0.5">Phương thức thanh toán:</span>
            <strong class="text-neutral-950 font-semibold text-xs">{{ $order->payment_method_name }}</strong>
          </div>

          <div>
            <span class="text-[10px] uppercase text-neutral-400 font-bold block mb-0.5">Đơn vị vận chuyển:</span>
            <span class="text-neutral-800 font-semibold">{{ $order->shipping_carrier ?: 'Giao Hàng Tiết Kiệm (GHTK)' }}</span>
          </div>

          @if($order->tracking_code)
            <div>
              <span class="text-[10px] uppercase text-neutral-400 font-bold block mb-0.5">Mã vận đơn bưu tá:</span>
              <a href="{{ route('client.carrier-tracking', ['code' => $order->tracking_code]) }}" class="font-mono font-bold text-emerald-700 hover:underline">
                {{ $order->tracking_code }} &rarr;
              </a>
            </div>
          @endif
        </div>
      </div>

    </div>

    <!-- CỘT PHẢI: CHI TIẾT SẢN PHẨM & TỔNG TIỀN (7 COLS) -->
    <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-2xl border border-neutral-200/90 shadow-sm space-y-6">
      <div class="flex items-center justify-between pb-3 border-b border-neutral-100">
        <div class="flex items-center gap-2">
          <i data-lucide="package" class="w-4 h-4 text-amber-700"></i>
          <h3 class="font-serif-luxury text-base font-bold text-neutral-900">Kiện Hàng ({{ $order->items->count() }} sản phẩm)</h3>
        </div>
        <span class="text-xs text-neutral-500 font-medium">Chi tiết thanh toán</span>
      </div>

      <!-- Danh sách món hàng -->
      <div class="divide-y divide-neutral-100">
        @foreach($order->items as $item)
          @php
            $itemImg = $item->image ?: ($item->product->primaryImage->image_path ?? ($item->product->image ?? 'assets/img/products/1.png'));
            if (!str_starts_with($itemImg, 'http') && !str_starts_with($itemImg, '/')) {
              $itemImg = asset($itemImg);
            }
          @endphp
          <div class="py-3.5 flex items-center justify-between gap-4 text-xs">
            <div class="flex items-center gap-3 min-w-0">
              <img src="{{ $itemImg }}" alt="{{ $item->product_name }}" class="w-12 h-14 rounded-lg object-cover border border-neutral-200 shrink-0 bg-neutral-50">
              <div class="min-w-0">
                <h4 class="font-bold text-neutral-900 truncate leading-snug">{{ $item->product_name }}</h4>
                <div class="text-[11px] text-neutral-500 flex items-center gap-2 mt-0.5">
                  @if(!empty($item->color))
                    <span>Màu: <strong class="text-neutral-800">{{ $item->color }}</strong></span>
                  @endif
                  @if(!empty($item->size))
                    <span>• Size: <strong class="text-neutral-800">{{ $item->size }}</strong></span>
                  @endif
                  <span>• SL: <strong class="text-neutral-950 font-bold">x{{ $item->quantity }}</strong></span>
                </div>
              </div>
            </div>
            <div class="text-right shrink-0">
              <strong class="font-mono text-sm font-bold text-neutral-950 block">
                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
              </strong>
              <span class="text-[10px] text-neutral-400 font-mono">{{ number_format($item->price, 0, ',', '.') }}₫/cái</span>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Bảng tính chi phí -->
      <div class="pt-4 border-t border-neutral-200 text-xs space-y-2.5">
        <div class="flex justify-between text-neutral-600">
          <span>Tạm tính tiền hàng:</span>
          <span class="font-mono font-semibold text-neutral-900">{{ number_format($order->subtotal ?: $order->total_amount, 0, ',', '.') }}₫</span>
        </div>

        @if($order->discount_amount > 0)
          <div class="flex justify-between text-emerald-700 font-medium">
            <span>Chiết khấu Voucher ({{ $order->coupon_code }}):</span>
            <span class="font-mono font-bold">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
          </div>
        @endif

        <div class="flex justify-between text-neutral-600">
          <span>Phí vận chuyển:</span>
          @if(($order->shipping_fee ?? 0) <= 0)
            <span class="text-emerald-700 font-bold">Miễn phí (Freeship)</span>
          @else
            <span class="font-mono font-semibold text-neutral-900">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
          @endif
        </div>

        <div class="pt-3 border-t border-neutral-200 flex justify-between items-baseline">
          <span class="text-sm font-bold uppercase tracking-wider text-neutral-950">Tổng thanh toán:</span>
          <strong class="font-serif-luxury text-2xl font-black text-neutral-950">
            {{ number_format($order->total_amount, 0, ',', '.') }}₫
          </strong>
        </div>

        @if($order->is_deposit_required)
          <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 mt-3 space-y-1 text-[11px]">
            <div class="flex justify-between text-amber-950 font-bold">
              <span>Đã cọc trước 50%:</span>
              <span class="font-mono text-rose-600">{{ number_format($order->deposit_amount, 0, ',', '.') }}₫</span>
            </div>
            <div class="flex justify-between text-neutral-700">
              <span>Còn lại thanh toán COD khi nhận hàng:</span>
              <span class="font-mono font-bold text-neutral-950">{{ number_format($order->remaining_amount ?: ($order->total_amount - $order->deposit_amount), 0, ',', '.') }}₫</span>
            </div>
          </div>
        @endif
      </div>

    </div>

  </div>

  <!-- ========================================================================= -->
  <!-- 4. ACTION BUTTONS STRIP -->
  <!-- ========================================================================= -->
  <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-xs font-bold uppercase tracking-wider">
    <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="w-full sm:w-auto px-8 py-4 bg-neutral-950 hover:bg-neutral-800 text-white rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
      <i data-lucide="map-pin" class="w-4 h-4 text-amber-400"></i>
      <span>Theo Dõi Hành Trình Đơn Hàng</span>
    </a>

    <a href="{{ route('client.products.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-neutral-100 text-neutral-900 border border-neutral-300 rounded-xl transition-all flex items-center justify-center gap-2">
      <i data-lucide="arrow-left" class="w-4 h-4"></i>
      <span>Tiếp Tục Mua Sắm</span>
    </a>

    <button type="button" onclick="window.print()" class="w-full sm:w-auto px-6 py-4 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-xl transition-all flex items-center justify-center gap-2">
      <i data-lucide="printer" class="w-4 h-4"></i>
      <span>In Biên Nhận</span>
    </button>
  </div>

</main>
@endsection
