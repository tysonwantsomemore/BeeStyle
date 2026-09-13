@extends('layouts.client')

@section('title', 'Túi Mua Hàng — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-8 md:py-12 px-4 sm:px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center justify-between gap-2 text-xs text-neutral-500 mb-6 overflow-x-auto whitespace-nowrap pb-2 border-b border-neutral-100">
    <div class="flex items-center gap-2">
      <a href="{{ route('client.home') }}" class="hover:text-black transition-colors">Trang Chủ</a>
      <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
      <span class="text-neutral-900 font-semibold">Túi Mua Hàng (<span id="cartCountTitle">{{ $cartCount ?? 0 }}</span> sản phẩm)</span>
    </div>
    <a href="{{ route('client.products.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs text-neutral-600 hover:text-black font-medium transition-colors">
      <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
      <span>Tiếp tục mua sắm</span>
    </a>
  </nav>

  <!-- ========================================== -->
  <!-- 1. EMPTY CART VIEW (KHI GIỎ HÀNG TRỐNG)   -->
  <!-- ========================================== -->
  <div id="cartEmptyContainer" class="bg-white p-10 md:p-16 rounded-2xl border border-neutral-200 text-center max-w-lg mx-auto shadow-sm my-8 {{ (!empty($cartItems) && count($cartItems) > 0) ? 'hidden' : '' }}">
    <div class="w-20 h-20 rounded-full bg-brand-50 border border-brand-200 flex items-center justify-center mx-auto mb-5 text-neutral-400">
      <i data-lucide="shopping-bag" class="w-10 h-10 stroke-1 text-neutral-700"></i>
    </div>
    <h2 class="font-serif-luxury text-2xl md:text-3xl text-neutral-900 mb-2 font-bold">Túi hàng của bạn đang trống</h2>
    <p class="text-xs text-neutral-500 font-normal mb-8 leading-relaxed max-w-md mx-auto">
      Hiện chưa có sản phẩm nào trong túi mua hàng của bạn. Hãy khám phá ngay các bộ sưu tập sơ mi lụa, polo may đo và quần âu chuẩn phom quý ông BeeStyle.
    </p>
    <a href="{{ route('client.products.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-neutral-950 text-white text-xs font-semibold tracking-[0.2em] uppercase rounded-xl hover:bg-neutral-800 transition-all shadow-md hover:shadow-lg">
      <span>Khám Phá Sản Phẩm Ngay</span>
      <i data-lucide="arrow-right" class="w-4 h-4 text-amber-400"></i>
    </a>
  </div>

  <!-- ========================================== -->
  <!-- 2. MAIN CART VIEW (KHI CÓ SẢN PHẨM)       -->
  <!-- ========================================== -->
  <div id="cartMainContainer" class="{{ (empty($cartItems) || count($cartItems) === 0) ? 'hidden' : '' }}">
    
    <!-- Free Shipping Progress Bar -->
    @php
      $threshold = $freeShippingThreshold ?? 300000;
      $sub = $subtotal ?? 0;
      $fsPercent = $freeShippingPercent ?? ($sub > 0 ? min(100, round(($sub / $threshold) * 100)) : 0);
      $isFs = $isFreeShipping ?? ($sub >= $threshold);
      $needed = $freeShippingNeeded ?? max(0, $threshold - $sub);
    @endphp
    <div class="bg-gradient-to-r from-amber-50/80 via-white to-amber-50/50 border border-amber-200/80 p-4 rounded-xl mb-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
      <div class="flex items-center gap-3 text-xs w-full sm:w-auto">
        <div class="w-9 h-9 rounded-full {{ $isFs ? 'bg-emerald-500 text-white' : 'bg-amber-100 text-amber-900' }} flex items-center justify-center shrink-0 shadow-xs transition-colors">
          <i data-lucide="{{ $isFs ? 'check' : 'truck' }}" class="w-4 h-4"></i>
        </div>
        <div id="freeShippingStatusText" class="flex-grow">
          @if($isFs)
            <span class="font-bold text-emerald-800 flex items-center gap-1">
              <span>🎉 Tuyệt vời! Đơn hàng của bạn đã đủ điều kiện</span>
              <span class="bg-emerald-100 text-emerald-900 px-2 py-0.5 rounded font-black">FREESHIP TOÀN QUỐC</span>
            </span>
          @else
            <span class="text-neutral-700">
              Mua thêm <strong class="text-neutral-950 font-bold font-mono">{{ number_format($needed, 0, ',', '.') }}₫</strong> để nhận ưu đãi <strong class="text-emerald-700 font-bold">Miễn Phí Vận Chuyển</strong> (Đơn từ {{ number_format($threshold, 0, ',', '.') }}₫)
            </span>
          @endif
        </div>
      </div>
      <div class="w-full sm:w-56 bg-neutral-200/70 rounded-full h-2.5 overflow-hidden border border-neutral-200 shrink-0">
        <div id="freeShippingProgressBar" class="{{ $isFs ? 'bg-emerald-600' : 'bg-amber-600' }} h-full rounded-full transition-all duration-500" style="width: {{ $fsPercent }}%;"></div>
      </div>
    </div>

    <!-- Alert cảnh báo nếu có mặt hàng hết hàng -->
    @if(!empty($hasOutOfStockItems))
      <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 text-rose-600"></i>
        <div>
          <strong class="font-bold block">Túi hàng có sản phẩm hiện đã hết hàng trong kho:</strong>
          <span>Vui lòng gỡ bỏ hoặc chọn màu/size khác để tiếp tục hoàn tất đặt hàng.</span>
        </div>
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
      
      <!-- CỘT TRÁI: DANH SÁCH MẶT HÀNG (8 COLS) -->
      <div class="lg:col-span-8 space-y-6">
        
        <div class="bg-white p-5 md:p-8 rounded-2xl border border-neutral-200 shadow-xs">
          <!-- Table Header Controls -->
          <div class="flex items-center justify-between pb-4 mb-2 border-b border-neutral-100">
            <h2 class="font-serif-luxury text-xl md:text-2xl font-bold text-neutral-900 flex items-center gap-2">
              <span>Sản Phẩm Trong Túi</span>
              <span class="text-xs font-sans font-semibold text-neutral-400 px-2 py-0.5 bg-neutral-100 rounded-full">
                {{ count($cartItems ?? []) }} mục
              </span>
            </h2>
            <div class="flex items-center gap-3">
              <button type="button" onclick="clearFullCart()" class="text-xs text-rose-600 hover:text-rose-800 font-semibold flex items-center gap-1 transition-colors px-2 py-1 rounded hover:bg-rose-50" title="Xóa tất cả sản phẩm khỏi túi hàng">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Xóa tất cả</span>
              </button>
            </div>
          </div>

          <!-- Product Item Rows -->
          <div class="divide-y divide-neutral-100" id="cartItemsList">
            @if(!empty($cartItems))
              @foreach($cartItems as $item)
                @php
                  $itemKey = $item['key'] ?? $loop->index;
                  $itemImg = $item['image'] ?? '/assets/img/products/polo_01.jpg';
                  if (!str_starts_with($itemImg, 'http') && !str_starts_with($itemImg, '/')) {
                    $itemImg = asset($itemImg);
                  }
                  $isOut = !empty($item['is_out_of_stock']);
                  $stockWarn = !empty($item['has_stock_warning']);
                  $cStock = $item['current_stock'] ?? 10;
                @endphp
                <div id="cartRow_{{ $itemKey }}" class="py-5 flex flex-col sm:flex-row gap-4 sm:gap-5 items-start sm:items-center justify-between transition-all duration-200 {{ $isOut ? 'opacity-70 bg-rose-50/30 p-3 rounded-xl border border-rose-100' : '' }}">
                  
                  <!-- Thumbnail & Info -->
                  <div class="flex gap-3.5 sm:gap-4 items-center min-w-0 flex-1">
                    <a href="{{ route('client.products.show', $item['product_id']) }}" class="w-20 h-24 rounded-lg bg-neutral-100 overflow-hidden shrink-0 border border-neutral-200 relative group">
                      <img src="{{ $itemImg }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300">
                      @if(!empty($item['is_daily_deal']))
                        <span class="absolute top-1 left-1 px-1.5 py-0.5 bg-rose-600 text-white text-[9px] font-black rounded uppercase shadow-sm">
                          -{{ $item['deal_discount'] }}%
                        </span>
                      @endif
                      @if($isOut)
                        <div class="absolute inset-0 bg-neutral-950/60 flex items-center justify-center text-white text-[10px] font-bold uppercase tracking-wider">
                          Hết hàng
                        </div>
                      @endif
                    </a>
                    
                    <div class="space-y-1 min-w-0 flex-1">
                      <span class="text-[9px] tracking-widest uppercase text-neutral-400 font-bold block">BEESTYLE ATELIER</span>
                      <a href="{{ route('client.products.show', $item['product_id']) }}" class="font-serif-luxury text-sm sm:text-base font-bold text-neutral-900 hover:text-amber-700 transition-colors block truncate">
                        {{ $item['name'] }}
                      </a>
                      
                      <!-- Variant Attributes (Color & Size) & Flash Sale Badge -->
                      <div class="flex items-center flex-wrap gap-2 text-xs text-neutral-500">
                        @if(!empty($item['color']))
                          <span class="inline-flex items-center gap-1 bg-neutral-100 px-2 py-0.5 rounded text-[11px] font-medium text-neutral-800">
                            Màu: <strong class="text-neutral-950 font-bold">{{ $item['color'] }}</strong>
                          </span>
                        @endif
                        @if(!empty($item['size']))
                          <span class="inline-flex items-center gap-1 bg-neutral-100 px-2 py-0.5 rounded text-[11px] font-medium text-neutral-800">
                            Size: <strong class="text-neutral-950 font-bold">{{ $item['size'] }}</strong>
                          </span>
                        @endif
                        @if(!empty($item['is_daily_deal']))
                          <span class="inline-flex items-center gap-1 bg-rose-600 text-white px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider shadow-2xs">
                            <i class="fa-solid fa-bolt text-amber-300 text-[9px]"></i> Flash Sale -{{ $item['deal_discount'] }}%
                          </span>
                        @endif
                      </div>

                      <!-- Stock Warnings -->
                      @if($isOut)
                        <span class="text-[11px] font-semibold text-rose-600 block mt-0.5">
                          <i data-lucide="x-circle" class="w-3 h-3 inline me-0.5"></i> Mặt hàng này hiện đã hết hàng
                        </span>
                      @elseif($stockWarn)
                        <span class="text-[11px] font-semibold text-amber-700 block mt-0.5">
                          <i data-lucide="alert-circle" class="w-3 h-3 inline me-0.5"></i> Chỉ còn {{ $cStock }} sản phẩm trong kho
                        </span>
                      @endif

                      <!-- Mobile Unit Price & Actions -->
                      <div class="flex items-center gap-2 pt-1 sm:hidden flex-wrap">
                        <span class="text-xs font-bold font-mono {{ !empty($item['is_daily_deal']) ? 'text-rose-600' : 'text-neutral-900' }}">
                          {{ number_format($item['price'], 0, ',', '.') }}₫
                        </span>
                        @if(!empty($item['original_price']) && $item['original_price'] > $item['price'])
                          <span class="text-[10px] text-neutral-400 line-through font-mono">
                            {{ number_format($item['original_price'], 0, ',', '.') }}₫
                          </span>
                        @endif
                        <button type="button" onclick="saveItemForLater('{{ $itemKey }}', this)" class="text-[11px] text-neutral-400 hover:text-amber-700 flex items-center gap-1 ml-auto">
                          <i data-lucide="bookmark" class="w-3 h-3"></i> Lưu sau
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Price, Quantity, Subtotal & Delete -->
                  <div class="flex items-center justify-between w-full sm:w-auto gap-4 sm:gap-6 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-neutral-100">
                    <!-- Unit price (desktop) with strikethrough original price if on sale -->
                    <div class="text-right hidden sm:block">
                      <span class="font-mono text-xs font-bold block {{ !empty($item['is_daily_deal']) ? 'text-rose-600' : 'text-neutral-900' }}">
                        {{ number_format($item['price'], 0, ',', '.') }}₫
                      </span>
                      @if(!empty($item['original_price']) && $item['original_price'] > $item['price'])
                        <span class="font-mono text-[10px] text-neutral-400 line-through block">
                          {{ number_format($item['original_price'], 0, ',', '.') }}₫
                        </span>
                      @endif
                    </div>

                    <!-- Quantity Stepper Controls -->
                    <div class="flex items-center border border-neutral-300 rounded-lg overflow-hidden bg-white shadow-2xs">
                      <button type="button" 
                              onclick="updateCartItemQty('{{ $itemKey }}', {{ $item['quantity'] - 1 }}, this)" 
                              {{ $item['quantity'] <= 1 ? 'disabled' : '' }} 
                              class="btn-step-minus px-2.5 py-1.5 text-xs font-bold text-neutral-600 hover:bg-neutral-100 disabled:opacity-30 disabled:hover:bg-white transition-colors"
                              title="Giảm số lượng">-</button>
                      <input type="text" 
                             readonly 
                             value="{{ $item['quantity'] }}" 
                             class="cart-qty-input w-10 text-center text-xs font-bold font-mono text-neutral-900 focus:outline-none bg-transparent">
                      <button type="button" 
                              onclick="updateCartItemQty('{{ $itemKey }}', {{ $item['quantity'] + 1 }}, this)" 
                              class="btn-step-plus px-2.5 py-1.5 text-xs font-bold text-neutral-600 hover:bg-neutral-100 transition-colors"
                              title="Tăng số lượng">+</button>
                    </div>

                    <!-- Row Subtotal -->
                    <span id="subtotal_{{ $itemKey }}" class="font-mono text-sm sm:text-base font-bold text-neutral-950 min-w-[95px] text-right">
                      {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫
                    </span>

                    <!-- Row Remove Button -->
                    <button type="button" 
                            onclick="removeCartItem('{{ $itemKey }}', this)" 
                            class="p-2 text-neutral-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" 
                            title="Xóa sản phẩm khỏi giỏ">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                  </div>

                </div>
              @endforeach
            @endif
          </div>
        </div>

        <!-- ========================================== -->
        <!-- GỢI Ý MUA KÈM ĐỂ GOM ĐƠN FREESHIP          -->
        <!-- ========================================== -->
        @if(!empty($crossSellProducts) && $crossSellProducts->count() > 0)
          <div class="bg-white p-5 md:p-6 rounded-2xl border border-neutral-200 shadow-xs">
            <div class="flex items-center justify-between mb-4">
              <div>
                <h3 class="font-serif-luxury text-base md:text-lg font-bold text-neutral-900 flex items-center gap-1.5">
                  <i data-lucide="sparkles" class="w-4 h-4 text-amber-600"></i>
                  <span>Gợi Ý Mua Kèm — Gom Đơn Freeship</span>
                </h3>
                <p class="text-xs text-neutral-500">Các thiết kế phụ kiện &amp; áo cơ bản được mua cùng nhiều nhất</p>
              </div>
              <a href="{{ route('client.products.index') }}" class="text-xs text-neutral-700 hover:text-black font-semibold">Xem thêm &rarr;</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
              @foreach($crossSellProducts as $csProd)
                <div class="border border-neutral-100 rounded-xl p-2.5 bg-neutral-50/50 hover:bg-white hover:border-amber-300 hover:shadow-xs transition-all flex flex-col justify-between">
                  <a href="{{ route('client.products.show', $csProd->id) }}" class="block mb-2 overflow-hidden rounded-lg bg-neutral-100 aspect-square">
                    <img src="{{ asset($csProd->image) }}" alt="{{ $csProd->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                  </a>
                  <div>
                    <a href="{{ route('client.products.show', $csProd->id) }}" class="font-serif-luxury text-xs font-bold text-neutral-900 hover:text-amber-700 line-clamp-1 block mb-1">
                      {{ $csProd->name }}
                    </a>
                    <div class="font-mono text-xs font-bold text-rose-700 mb-2">
                      {{ number_format($csProd->price, 0, ',', '.') }}₫
                    </div>
                    <a href="{{ route('client.products.show', $csProd->id) }}" class="w-full py-1.5 bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-900 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors flex items-center justify-center gap-1">
                      <i data-lucide="eye" class="w-3 h-3"></i> Xem chi tiết
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

      </div>

      <!-- ========================================== -->
      <!-- CỘT PHẢI: VOUCHER, TÓM TẮT & CHECKOUT (4 COLS)-->
      <!-- ========================================== -->
      <div class="lg:col-span-4 space-y-6">
        
        <!-- KHỐI 1: MÃ ƯU ĐÃI & VÍ VOUCHER KHẢ DỤNG -->
        <div class="bg-white p-5 md:p-6 rounded-2xl border border-neutral-200 shadow-xs space-y-4">
          <div class="flex items-center justify-between">
            <h3 class="font-serif-luxury text-base font-bold text-neutral-900 uppercase tracking-wider flex items-center gap-1.5">
              <i data-lucide="tag" class="w-4 h-4 text-amber-700"></i>
              <span>Mã Ưu Đãi (Voucher)</span>
            </h3>
            @if(isset($appliedCoupon) && $appliedCoupon)
              <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded">Đã áp dụng</span>
            @endif
          </div>
          
          <!-- Input nhập mã voucher thủ công -->
          <div id="couponInputBox" class="{{ (isset($appliedCoupon) && $appliedCoupon) ? 'hidden' : 'flex' }} gap-2">
            <input type="text" 
                   id="manualCouponInput" 
                   placeholder="Nhập mã voucher (VD: GIAM50K)..." 
                   value="{{ $appliedCoupon->code ?? '' }}" 
                   class="bg-neutral-50 border border-neutral-300 rounded-lg px-3 py-2 text-xs text-neutral-900 uppercase focus:outline-none focus:border-neutral-950 focus:bg-white flex-grow font-mono transition-colors">
            <button type="button" 
                    onclick="applyManualCoupon()" 
                    class="px-4 py-2 bg-neutral-950 text-white text-xs font-bold tracking-wider uppercase rounded-lg hover:bg-neutral-800 transition-colors shrink-0">
              Áp Dụng
            </button>
          </div>

          <!-- Thông tin voucher đang được áp dụng -->
          <div id="appliedCouponBox" class="text-xs text-emerald-800 {{ (isset($appliedCoupon) && $appliedCoupon) ? 'flex' : 'hidden' }} items-center justify-between bg-emerald-50 border border-emerald-200 p-3 rounded-xl">
            <div class="flex items-center gap-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
              <div>
                <span id="appliedCouponTitle" class="font-medium text-[11px] block text-emerald-700">Đã áp dụng:</span>
                <strong id="appliedCouponCode" class="font-mono text-xs font-bold text-emerald-950">{{ $appliedCoupon->code ?? '' }}</strong>
              </div>
            </div>
            <button type="button" onclick="removeCartCoupon()" class="text-rose-600 hover:text-rose-800 font-bold text-xs underline ml-2">
              Gỡ bỏ
            </button>
          </div>

          <!-- Gợi ý danh sách Voucher có sẵn để 1-click áp dụng -->
          @if(!empty($coupons) && $coupons->count() > 0)
            <div class="pt-2 border-t border-neutral-100">
              <span class="text-[11px] font-bold text-neutral-500 uppercase tracking-wider block mb-2">Mã ưu đãi dành cho bạn:</span>
              <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                @foreach($coupons as $cp)
                  @php
                    $isApplied = (isset($appliedCoupon) && $appliedCoupon && $appliedCoupon->code === $cp->code);
                    $canApply = ($sub >= $cp->min_order_value);
                  @endphp
                  <div class="border {{ $isApplied ? 'border-emerald-500 bg-emerald-50/50' : 'border-neutral-200 bg-neutral-50' }} rounded-lg p-2.5 flex items-center justify-between gap-2 text-xs">
                    <div>
                      <div class="flex items-center gap-1.5">
                        <span class="font-mono font-bold text-neutral-900 uppercase bg-white border border-neutral-300 px-1.5 py-0.5 rounded text-[11px]">
                          {{ $cp->code }}
                        </span>
                        <span class="font-bold text-rose-700 text-[11px]">
                          @if($cp->discount_type === 'percent')
                            Giảm {{ $cp->discount_value }}%
                          @elseif($cp->discount_type === 'shipping')
                            Freeship
                          @else
                            Giảm {{ number_format($cp->discount_value, 0, ',', '.') }}₫
                          @endif
                        </span>
                      </div>
                      <small class="text-neutral-500 text-[10px] block mt-0.5">
                        Đơn tối thiểu từ {{ number_format($cp->min_order_value, 0, ',', '.') }}₫
                      </small>
                    </div>

                    @if($isApplied)
                      <span class="text-[10px] font-bold text-emerald-700 flex items-center gap-1">
                        <i data-lucide="check" class="w-3 h-3"></i> Đang dùng
                      </span>
                    @else
                      <button type="button" 
                              onclick="quickApplyCoupon('{{ $cp->code }}')" 
                              class="px-2.5 py-1 text-[11px] font-bold rounded {{ $canApply ? 'bg-neutral-900 text-white hover:bg-neutral-800' : 'bg-neutral-200 text-neutral-500' }} transition-colors">
                        Áp dụng
                      </button>
                    @endif
                  </div>
                @endforeach
              </div>
            </div>
          @endif

        </div>

        <!-- KHỐI 2: TÓM TẮT CHI PHÍ (ORDER SUMMARY BOX) -->
        <div class="bg-white p-5 md:p-6 rounded-2xl border border-neutral-200 shadow-sm space-y-4 text-xs">
          <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 pb-3 border-b border-neutral-100 flex items-center justify-between">
            <span>Tóm Tắt Chi Phí</span>
            <span class="text-xs font-sans font-normal text-neutral-400">Đơn hàng</span>
          </h3>
          @php
            $totalDealSavings = 0;
            if(!empty($cartItems)) {
              foreach($cartItems as $it) {
                if (!empty($it['is_daily_deal']) && !empty($it['original_price']) && $it['original_price'] > $it['price']) {
                  $totalDealSavings += ($it['original_price'] - $it['price']) * ($it['quantity'] ?? 1);
                }
              }
            }
          @endphp

          @if($totalDealSavings > 0)
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-2.5 text-xs text-rose-900 shadow-2xs">
              <i class="fa-solid fa-bolt text-rose-600 text-sm shrink-0"></i>
              <span>Ưu đãi Flash Sale: Tiết kiệm <strong class="text-rose-700 font-bold font-mono">{{ number_format($totalDealSavings, 0, ',', '.') }}₫</strong></span>
            </div>
          @endif

          <div class="space-y-3 text-neutral-600">
            <div class="flex justify-between items-center">
              <span>Tạm tính (<span id="cartCountSummary" class="font-bold text-neutral-800">{{ $cartCount ?? 0 }}</span> món):</span>
              <span id="cartSubtotalText" class="font-mono font-bold text-neutral-950 text-sm">
                {{ number_format($subtotal ?? 0, 0, ',', '.') }}₫
              </span>
            </div>

            <div class="flex justify-between items-center">
              <span>Phí vận chuyển:</span>
              <span id="cartShippingText" class="font-semibold {{ ($shipping ?? 0) == 0 ? 'text-emerald-700 font-bold' : 'text-neutral-900 font-mono' }}">
                {{ ($shipping ?? 0) == 0 ? 'MIỄN PHÍ' : number_format($shipping, 0, ',', '.') . '₫' }}
              </span>
            </div>

            <div id="discountRow" class="flex justify-between items-center text-rose-700 font-semibold {{ (isset($discount) && $discount > 0) ? '' : 'hidden' }}">
              <span>Ưu đãi mã giảm giá:</span>
              <span id="cartDiscountText" class="font-mono font-bold text-rose-700">
                -{{ number_format($discount ?? 0, 0, ',', '.') }}₫
              </span>
            </div>
          </div>

          <!-- Total Calculation -->
          <div class="pt-4 border-t border-neutral-200 flex justify-between items-baseline">
            <div>
              <span class="font-bold uppercase tracking-wider text-neutral-900 text-xs block">Tổng Thanh Toán:</span>
              <span class="text-[10px] text-neutral-400">Đã bao gồm thuế GTGT (VAT)</span>
            </div>
            <div class="text-right">
              <span id="cartTotalText" class="font-serif-luxury text-2xl md:text-3xl font-black text-neutral-950 font-mono block">
                {{ number_format($total ?? 0, 0, ',', '.') }}₫
              </span>
            </div>
          </div>

          <!-- Checkout CTA Button -->
          <a href="{{ route('client.checkout') }}" 
             class="w-full py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-bold tracking-[0.25em] uppercase rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 mt-4 text-center group">
            <i data-lucide="lock" class="w-4 h-4 text-amber-400 group-hover:scale-110 transition-transform"></i>
            <span>Tiến Hành Đặt Hàng</span>
          </a>
        </div>

        <!-- KHỐI 3: CAM KẾT CHẤT LƯỢNG & AN TÂM MUA SẮM (TRUST BADGES) -->
        <div class="bg-neutral-50/80 p-4 rounded-xl border border-neutral-200/80 space-y-2.5 text-xs text-neutral-600">
          <div class="flex items-center gap-2.5">
            <i data-lucide="shield-check" class="w-4 h-4 text-amber-700 shrink-0"></i>
            <span>100% Sản phẩm may đo chính hãng cao cấp</span>
          </div>
          <div class="flex items-center gap-2.5">
            <i data-lucide="refresh-cw" class="w-4 h-4 text-emerald-700 shrink-0"></i>
            <span>Đổi trả miễn phí trong vòng 30 ngày</span>
          </div>
          <div class="flex items-center gap-2.5">
            <i data-lucide="package-check" class="w-4 h-4 text-blue-700 shrink-0"></i>
            <span>Giao hàng toàn quốc — Đồng kiểm khi nhận</span>
          </div>
        </div>

      </div>

    </div>
  </div>

</main>
@endsection

@push('scripts')
<script>
  // CẬP NHẬT GIAO DIỆN TỔNG QUAN GIỎ HÀNG TỪ DỮ LIỆU JSON TRẢ VỀ
  function renderCartSummary(cart) {
    if (!cart) return;

    // 1. Cập nhật số lượng
    const countTitle = document.getElementById('cartCountTitle');
    const countSummary = document.getElementById('cartCountSummary');
    if (countTitle) countTitle.textContent = cart.count;
    if (countSummary) countSummary.textContent = cart.count;

    // Cập nhật badge giỏ hàng trên Header toàn trang
    const headerCartBadge = document.getElementById('cartCountBadge') || document.querySelector('.bee-cart-count');
    if (headerCartBadge) {
      headerCartBadge.textContent = cart.count;
      headerCartBadge.style.display = cart.count > 0 ? 'inline-block' : 'none';
    }

    // 2. Cập nhật tiền
    const subtotalEl = document.getElementById('cartSubtotalText');
    const discountEl = document.getElementById('cartDiscountText');
    const discountRow = document.getElementById('discountRow');
    const shippingEl = document.getElementById('cartShippingText');
    const totalEl = document.getElementById('cartTotalText');

    if (subtotalEl) subtotalEl.textContent = cart.subtotal_formatted || (cart.subtotal ? cart.subtotal.toLocaleString('vi-VN') + '₫' : '0₫');
    
    if (cart.discount > 0) {
      if (discountRow) discountRow.classList.remove('hidden');
      if (discountEl) discountEl.textContent = '-' + (cart.discount_formatted || cart.discount.toLocaleString('vi-VN') + '₫');
    } else {
      if (discountRow) discountRow.classList.add('hidden');
    }

    if (shippingEl) {
      if (cart.shipping == 0) {
        shippingEl.textContent = 'MIỄN PHÍ';
        shippingEl.className = 'font-semibold text-emerald-700 font-bold';
      } else {
        shippingEl.textContent = cart.shipping_formatted || cart.shipping.toLocaleString('vi-VN') + '₫';
        shippingEl.className = 'font-semibold text-neutral-900 font-mono';
      }
    }
    if (totalEl) totalEl.textContent = cart.total_formatted || (cart.total ? cart.total.toLocaleString('vi-VN') + '₫' : '0₫');

    // 3. Cập nhật thanh tiến trình Freeship
    const fsProgress = document.getElementById('freeShippingProgressBar');
    const fsStatusText = document.getElementById('freeShippingStatusText');
    if (fsProgress && fsStatusText) {
      fsProgress.style.width = cart.free_shipping_percent + '%';
      if (cart.is_free_shipping) {
        fsProgress.className = 'bg-emerald-600 h-full rounded-full transition-all duration-500';
        fsStatusText.innerHTML = '<span class="font-bold text-emerald-800 flex items-center gap-1"><span>🎉 Tuyệt vời! Đơn hàng của bạn đã đủ điều kiện</span> <span class="bg-emerald-100 text-emerald-900 px-2 py-0.5 rounded font-black">FREESHIP TOÀN QUỐC</span></span>';
      } else {
        fsProgress.className = 'bg-amber-600 h-full rounded-full transition-all duration-500';
        fsStatusText.innerHTML = `<span class="text-neutral-700">Mua thêm <strong class="text-neutral-950 font-bold font-mono">${cart.free_shipping_needed_formatted || ''}</strong> để nhận ưu đãi <strong class="text-emerald-700 font-bold">Miễn Phí Vận Chuyển</strong> (Đơn từ ${(cart.free_shipping_threshold || 300000).toLocaleString('vi-VN')}₫)</span>`;
      }
    }

    // 4. Cập nhật trạng thái Voucher
    const appliedBox = document.getElementById('appliedCouponBox');
    const inputBox = document.getElementById('couponInputBox');
    const appliedCode = document.getElementById('appliedCouponCode');
    const appliedTitle = document.getElementById('appliedCouponTitle');

    if (cart.coupon) {
      if (appliedBox) {
        appliedBox.classList.remove('hidden');
        appliedBox.classList.add('flex');
      }
      if (inputBox) {
        inputBox.classList.add('hidden');
        inputBox.classList.remove('flex');
      }
      if (appliedCode) appliedCode.textContent = cart.coupon.code;
      if (appliedTitle) appliedTitle.textContent = `Đã áp dụng (${cart.coupon.title || cart.coupon.code}):`;
    } else {
      if (appliedBox) {
        appliedBox.classList.add('hidden');
        appliedBox.classList.remove('flex');
      }
      if (inputBox) {
        inputBox.classList.remove('hidden');
        inputBox.classList.add('flex');
      }
    }

    // 5. Kiểm tra nếu giỏ hàng trống hoàn toàn
    if (cart.count <= 0) {
      const mainBox = document.getElementById('cartMainContainer');
      const emptyBox = document.getElementById('cartEmptyContainer');
      if (mainBox) mainBox.classList.add('hidden');
      if (emptyBox) emptyBox.classList.remove('hidden');
    }
  }

  // 1. CẬP NHẬT SỐ LƯỢNG MẶT HÀNG TRONG GIỎ QUA AJAX
  function updateCartItemQty(key, newQty, triggerEl) {
    if (newQty < 1) newQty = 1;

    const row = document.getElementById('cartRow_' + key);
    if (!row) return;

    const input = row.querySelector('.cart-qty-input');
    if (input) input.value = newQty;

    fetch('{{ route("client.cart.update") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ key: key, quantity: newQty })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.cart) {
        const itemSubtotalEl = document.getElementById('subtotal_' + key);
        if (itemSubtotalEl && data.cart.items && data.cart.items[key]) {
          itemSubtotalEl.textContent = data.cart.items[key].subtotal_formatted;
        }

        const minusBtn = row.querySelector('.btn-step-minus');
        const plusBtn = row.querySelector('.btn-step-plus');
        if (minusBtn) minusBtn.disabled = (newQty <= 1);
        if (plusBtn) plusBtn.disabled = false;

        renderCartSummary(data.cart);
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'warning', title: 'Thông báo tồn kho', text: data.message || 'Không thể cập nhật số lượng.' });
        } else {
          alert(data.message || 'Không thể cập nhật số lượng.');
        }
      }
    })
    .catch(err => console.error('Error updating cart:', err));
  }

  // 2. XÓA MỘT SẢN PHẨM KHỎI GIỎ HÀNG QUA AJAX
  function removeCartItem(key, triggerEl) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Xác nhận xóa?',
        text: 'Bạn có chắc chắn muốn gỡ sản phẩm này khỏi túi mua hàng?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0a0a0a',
        cancelButtonColor: '#737373',
        confirmButtonText: 'Đồng ý xóa',
        cancelButtonText: 'Hủy bỏ'
      }).then((result) => {
        if (result.isConfirmed) {
          executeRemoveCartItem(key);
        }
      });
    } else {
      if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi túi mua hàng?')) {
        executeRemoveCartItem(key);
      }
    }
  }

  function executeRemoveCartItem(key) {
    const row = document.getElementById('cartRow_' + key);
    if (row) {
      row.style.opacity = '0.3';
      row.style.pointerEvents = 'none';
    }

    const removeUrl = '{{ url("gio-hang/xoa") }}/' + encodeURIComponent(key);
    fetch(removeUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ cart_key: key })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (row) row.remove();
        renderCartSummary(data.cart);
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'success', title: 'Đã xóa', text: data.message, timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
        }
      } else {
        if (row) {
          row.style.opacity = '1';
          row.style.pointerEvents = 'auto';
        }
        alert(data.message || 'Không thể xóa sản phẩm.');
      }
    })
    .catch(err => console.error('Error removing item:', err));
  }

  // 3. LƯU SẢN PHẨM VÀO DANH SÁCH YÊU THÍCH (SAVE FOR LATER)
  function saveItemForLater(key, triggerEl) {
    const row = document.getElementById('cartRow_' + key);
    if (row) {
      row.style.opacity = '0.3';
      row.style.pointerEvents = 'none';
    }

    fetch(`/gio-hang/luu-tam/${key}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (row) row.remove();
        renderCartSummary(data.cart);

        const wishlistBadge = document.getElementById('wishlistCountBadge');
        if (wishlistBadge && data.wishlist_count !== undefined) {
          wishlistBadge.textContent = data.wishlist_count;
          wishlistBadge.style.display = data.wishlist_count > 0 ? 'flex' : 'none';
        }

        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'success', title: 'Đã lưu yêu thích', text: data.message, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        }
      } else {
        if (row) {
          row.style.opacity = '1';
          row.style.pointerEvents = 'auto';
        }
        alert(data.message || 'Không thể lưu sản phẩm.');
      }
    })
    .catch(err => console.error('Error saving for later:', err));
  }

  // 4. XÓA SẠCH TOÀN BỘ GIỎ HÀNG
  function clearFullCart() {
    const executeClear = () => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("client.cart.clear") }}';
      form.innerHTML = '@csrf';
      document.body.appendChild(form);
      form.submit();
    };

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Xóa tất cả sản phẩm?',
        text: 'Tất cả sản phẩm đã chọn sẽ bị xóa sạch khỏi giỏ hàng.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0a0a0a',
        cancelButtonColor: '#737373',
        confirmButtonText: 'Xóa tất cả ngay',
        cancelButtonText: 'Giữ lại'
      }).then((result) => {
        if (result.isConfirmed) executeClear();
      });
    } else {
      if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')) executeClear();
    }
  }

  // 5. ÁP DỤNG MÃ GIẢM GIÁ (NHẬP TAY HOẶC 1-CLICK TỪ DANH SÁCH)
  function applyManualCoupon() {
    const input = document.getElementById('manualCouponInput');
    if (!input || !input.value.trim()) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'warning', title: 'Nhắc nhở', text: 'Vui lòng nhập mã voucher trước khi áp dụng!' });
      } else {
        alert('Vui lòng nhập mã voucher.');
      }
      return;
    }
    executeApplyCoupon(input.value.trim());
  }

  function quickApplyCoupon(code) {
    const input = document.getElementById('manualCouponInput');
    if (input) input.value = code;
    executeApplyCoupon(code);
  }

  function executeApplyCoupon(code) {
    fetch('{{ route("client.cart.applyCoupon") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ coupon_code: code })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.cart) {
        renderCartSummary(data.cart);
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'success', title: 'Áp dụng thành công!', text: data.message, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        }
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'error', title: 'Không thể áp dụng', text: data.message || 'Mã giảm giá không hợp lệ hoặc chưa đủ điều kiện.' });
        } else {
          alert(data.message || 'Mã giảm giá không hợp lệ.');
        }
      }
    })
    .catch(err => console.error('Error applying coupon:', err));
  }

  function removeCartCoupon() {
    fetch('{{ route("client.cart.removeCoupon") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.cart) {
        renderCartSummary(data.cart);
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'info', title: 'Đã gỡ voucher', text: data.message, timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
        }
      }
    })
    .catch(err => console.error('Error removing coupon:', err));
  }

  // Khởi tạo Lucide icons
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });
</script>
@endpush