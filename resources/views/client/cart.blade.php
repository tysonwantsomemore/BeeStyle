@extends('layouts.client')

@section('title', 'Túi Mua Hàng — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Túi Mua Hàng ({{ $cartCount ?? 0 }} sản phẩm)</span>
  </nav>

  @if(empty($cartItems) || count($cartItems) === 0)
    <!-- Empty Cart View -->
    <div class="bg-white p-12 md:p-16 rounded-2xl border border-neutral-200 text-center max-w-md mx-auto shadow-sm my-8">
      <div class="w-20 h-20 rounded-full bg-brand-100 flex items-center justify-center mx-auto mb-4 text-neutral-400">
        <i data-lucide="shopping-bag" class="w-10 h-10 stroke-1"></i>
      </div>
      <h2 class="font-serif-luxury text-2xl md:text-3xl text-neutral-900 mb-2 font-medium">Túi hàng của bạn đang trống</h2>
      <p class="text-xs text-neutral-500 font-light mb-8 leading-relaxed">
        Khám phá những thiết kế sơ mi lụa tơ tằm, blazer may đo chuẩn Ý và các phụ kiện độc bản trong bộ sưu tập 2026.
      </p>
      <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-neutral-950 text-white text-xs font-semibold tracking-[0.2em] uppercase rounded-lg hover:bg-neutral-800 transition-all shadow-lg">
        <span>Khám Phá Bộ Sưu Tập</span>
        <i data-lucide="arrow-right" class="w-4 h-4 text-amber-400"></i>
      </a>
    </div>
  @else
    <!-- Free Shipping Progress -->
    @php
      $freeShippingThreshold = 500000;
      $sub = $subtotal ?? 0;
      $fsPercent = min(100, round(($sub / $freeShippingThreshold) * 100));
      $isFs = $sub >= $freeShippingThreshold;
    @endphp
    <div class="bg-brand-100 border border-brand-200 p-4 rounded-xl mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-3 text-xs">
        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-amber-800 shrink-0 shadow-xs">
          <i data-lucide="truck" class="w-4 h-4"></i>
        </div>
        <div>
          @if($isFs)
            <span class="font-semibold text-emerald-800">🎉 Đơn hàng của bạn đủ điều kiện FREESHIP TOÀN QUỐC!</span>
          @else
            <span class="text-neutral-700">Mua thêm <strong class="text-neutral-950 font-bold">{{ number_format($freeShippingThreshold - $sub, 0, ',', '.') }}₫</strong> để nhận <strong class="text-emerald-700">Miễn Phí Vận Chuyển</strong></span>
          @endif
        </div>
      </div>
      <div class="w-full sm:w-48 bg-white rounded-full h-2 overflow-hidden border border-brand-200 shrink-0">
        <div class="bg-neutral-900 h-full rounded-full transition-all duration-500" style="width: {{ $fsPercent }}%;"></div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
      
      <!-- Cart Items Table (8 cols) -->
      <div class="lg:col-span-8 bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-neutral-100">
          <h2 class="font-serif-luxury text-2xl font-bold text-neutral-900">Danh Sách Tác Phẩm</h2>
          <a href="{{ route('client.products.index') }}" class="text-xs text-neutral-500 hover:text-black font-semibold flex items-center gap-1">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Thêm sản phẩm khác
          </a>
        </div>

        <div class="divide-y divide-neutral-100">
          @foreach($cartItems as $item)
            @php
              $itemImg = $item['image'] ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=400&auto=format&fit=crop';
              if (!str_starts_with($itemImg, 'http')) {
                $itemImg = asset($itemImg);
              }
            @endphp
            <div class="py-6 flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between">
              <!-- Thumbnail & Info -->
              <div class="flex gap-4 items-center">
                <a href="{{ route('client.products.show', $item['product_id']) }}" class="w-20 h-24 rounded-lg bg-neutral-100 overflow-hidden shrink-0 border border-neutral-200">
                  <img src="{{ $itemImg }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                </a>
                <div class="space-y-1">
                  <span class="text-[10px] tracking-widest uppercase text-neutral-400 font-semibold block">ATELIER TAILORING</span>
                  <a href="{{ route('client.products.show', $item['product_id']) }}" class="font-serif-luxury text-base font-semibold text-neutral-900 hover:text-amber-800 transition-colors">
                    {{ $item['name'] }}
                  </a>
                  <p class="text-xs text-neutral-500">
                    @if(!empty($item['color'])) Màu: <strong class="text-neutral-800">{{ $item['color'] }}</strong> @endif
                    @if(!empty($item['size'])) | Size: <strong class="text-neutral-800">{{ $item['size'] }}</strong> @endif
                  </p>
                  <span class="text-xs font-semibold text-neutral-900 block sm:hidden">
                    {{ number_format($item['price'], 0, ',', '.') }}₫
                  </span>
                </div>
              </div>

              <!-- Price, Quantity, Subtotal & Delete -->
              <div class="flex items-center justify-between w-full sm:w-auto gap-6">
                <span class="font-serif-luxury text-sm font-semibold text-neutral-900 hidden sm:block">
                  {{ number_format($item['price'], 0, ',', '.') }}₫
                </span>

                <!-- Quantity Form -->
                <form action="{{ route('client.cart.update') }}" method="POST" class="flex items-center border border-neutral-300 rounded-lg overflow-hidden bg-white">
                  @csrf
                  <input type="hidden" name="cart_key" value="{{ $item['key'] }}">
                  <button type="submit" name="action" value="decrease" class="px-2.5 py-1.5 text-xs text-neutral-600 hover:bg-neutral-100 transition-colors">-</button>
                  <span class="px-3 text-xs font-bold text-neutral-900">{{ $item['quantity'] }}</span>
                  <button type="submit" name="action" value="increase" class="px-2.5 py-1.5 text-xs text-neutral-600 hover:bg-neutral-100 transition-colors">+</button>
                </form>

                <!-- Subtotal for item -->
                <span class="font-serif-luxury text-base font-bold text-neutral-950 min-w-[90px] text-right">
                  {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫
                </span>

                <!-- Remove Button -->
                <form action="{{ route('client.cart.remove') }}" method="POST">
                  @csrf
                  <input type="hidden" name="cart_key" value="{{ $item['key'] }}">
                  <button type="submit" class="p-1.5 text-neutral-400 hover:text-rose-600 transition-colors" title="Xóa sản phẩm">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </form>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Cart Summary (4 cols) -->
      <div class="lg:col-span-4 space-y-6">
        
        <!-- Coupon Form -->
        <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm">
          <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 mb-3 uppercase tracking-wider">Mã Ưu Đãi</h3>
          <form action="{{ route('client.cart.apply-coupon') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="coupon_code" placeholder="Nhập mã (BEESTYLE15...)" value="{{ $appliedCoupon->code ?? '' }}" class="bg-neutral-50 border border-neutral-200 rounded-lg px-3 py-2 text-xs text-neutral-900 uppercase focus:outline-none focus:border-neutral-950 flex-grow font-mono">
            <button type="submit" class="px-4 py-2 bg-neutral-950 text-white text-xs font-semibold tracking-wider uppercase rounded-lg hover:bg-neutral-800 transition-colors">
              Áp Dụng
            </button>
          </form>
          @if(isset($appliedCoupon) && $appliedCoupon)
            <div class="mt-2 text-[11px] text-emerald-700 flex items-center justify-between">
              <span>Đã áp dụng: <strong>{{ $appliedCoupon->code }}</strong> (-{{ number_format($discount ?? 0, 0, ',', '.') }}₫)</span>
            </div>
          @endif
        </div>

        <!-- Summary Calculation Box -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm space-y-4 text-xs">
          <h3 class="font-serif-luxury text-xl font-bold text-neutral-900 pb-3 border-b border-neutral-100">Tóm Tắt Chi Phí</h3>

          <div class="space-y-2.5 text-neutral-600">
            <div class="flex justify-between">
              <span>Tạm tính:</span>
              <span class="font-semibold text-neutral-900">{{ number_format($subtotal ?? 0, 0, ',', '.') }}₫</span>
            </div>
            <div class="flex justify-between">
              <span>Phí vận chuyển:</span>
              <span class="font-semibold text-neutral-900">{{ ($shipping ?? 0) == 0 ? 'MIỄN PHÍ' : number_format($shipping, 0, ',', '.') . '₫' }}</span>
            </div>
            @if(isset($discount) && $discount > 0)
              <div class="flex justify-between text-rose-700 font-semibold">
                <span>Ưu đãi giảm giá:</span>
                <span>-{{ number_format($discount, 0, ',', '.') }}₫</span>
              </div>
            @endif
          </div>

          <div class="pt-4 border-t border-neutral-200 flex justify-between items-baseline">
            <span class="font-bold uppercase tracking-wider text-neutral-900 text-xs">Tổng Thanh Toán:</span>
            <div class="text-right">
              <span class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-950 block">
                {{ number_format($total ?? 0, 0, ',', '.') }}₫
              </span>
              <span class="text-[10px] text-neutral-400">Đã bao gồm thuế VAT</span>
            </div>
          </div>

          <a href="{{ route('client.checkout') }}" class="w-full py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.25em] uppercase rounded-xl shadow-xl transition-all flex items-center justify-center gap-2 mt-4">
            <i data-lucide="lock" class="w-4 h-4"></i>
            <span>Tiến Hành Đặt Hàng</span>
          </a>
        </div>

      </div>

    </div>
  @endif

</main>
@endsection
