@extends('layouts.client')

@section('title', 'BEESTYLE — Contemporary Fashion & Studio • BST Sartorial 2026')

@section('content')
<!-- ========================================================================= -->
<!-- 1. HERO SECTION: Haute Couture Editorial Fashion Banner -->
<!-- ========================================================================= -->
<section id="home" class="relative w-full min-h-[85vh] md:min-h-[90vh] bg-neutral-950 flex items-center justify-center overflow-hidden">
  <div class="absolute inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2000&auto=format&fit=crop" alt="Beestyle Fashion Editorial 2026" class="w-full h-full object-cover object-top opacity-55">
    <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/75 to-neutral-950/40"></div>
  </div>

  <div class="relative z-10 max-w-5xl mx-auto px-6 text-center text-white flex flex-col items-center py-16">
    <!-- Brand Tag Badge -->
    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/20 border border-amber-400/50 text-amber-300 text-xs font-bold uppercase tracking-[0.25em] mb-5 backdrop-blur-md shadow-sm animate-fade-in">
      <i class="fa-solid fa-gem text-amber-400"></i> BEESTYLE ATELIER 2026
    </div>

    <h1 class="font-serif-luxury text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-light tracking-wide leading-none mb-6 text-white drop-shadow-md">
      Nghệ Thuật Cắt May<br><span class="italic font-serif text-amber-300 font-normal">Đương Đại &amp; Tối Giản</span>
    </h1>
    
    <p class="text-sm md:text-base text-neutral-100 font-normal max-w-2xl mb-10 leading-relaxed drop-shadow-sm">
      Tôn vinh vẻ đẹp tự nhiên thông qua chất liệu lụa tơ tằm dệt tay, len dạ Cashmere và kỹ thuật may đo chuẩn Ý. Mỗi trang phục là một tác phẩm nghệ thuật bền vững theo thời gian.
    </p>
    
    <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
      <a href="{{ route('client.products.index') }}" class="w-full sm:w-auto px-8 py-4 bg-amber-400 hover:bg-amber-300 text-neutral-950 text-xs tracking-[0.25em] uppercase font-bold transition-all duration-300 shadow-2xl rounded-xl flex items-center justify-center gap-2">
        <span>Khám Phá Sản Phẩm</span>
        <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
      <a href="#lookbook" class="w-full sm:w-auto px-8 py-4 bg-white/10 border border-white/40 text-white text-xs tracking-[0.25em] uppercase font-bold hover:bg-white/20 transition-all duration-300 rounded-xl backdrop-blur-sm">
        Xem Lookbook 2026
      </a>
    </div>

    <!-- Trust Badges Strip -->
    <div class="mt-14 pt-8 border-t border-white/15 w-full max-w-3xl grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-neutral-200">
      <div class="flex items-center justify-center gap-2">
        <i class="fa-solid fa-bolt text-amber-400"></i>
        <span class="font-semibold">Giao Hàng 2H</span>
      </div>
      <div class="flex items-center justify-center gap-2">
        <i class="fa-solid fa-arrows-rotate text-amber-400"></i>
        <span class="font-semibold">Đổi Size 30 Ngày</span>
      </div>
      <div class="flex items-center justify-center gap-2">
        <i class="fa-solid fa-gem text-amber-400"></i>
        <span class="font-semibold">Chất Liệu Thượng Hạng</span>
      </div>
      <div class="flex items-center justify-center gap-2">
        <i class="fa-solid fa-shield-halved text-amber-400"></i>
        <span class="font-semibold">Bảo Hành Trọn Đời</span>
      </div>
    </div>
  </div>

  <!-- Scroll Indicator -->
  <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 text-white/70 flex flex-col items-center gap-1.5 pointer-events-none">
    <span class="text-[9px] tracking-[0.3em] uppercase font-bold">Cuộn xuống</span>
    <div class="w-4 h-7 border border-white/40 rounded-full flex justify-center p-1">
      <div class="w-1 h-2 bg-amber-400 rounded-full animate-bounce"></div>
    </div>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 2. STORE VALUE PROPOSITION / HIGHLIGHTS BAR -->
<!-- ========================================================================= -->
<section class="w-full bg-white border-b border-neutral-200 py-10 px-6 shadow-xs">
  <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
    
    <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-neutral-50 transition-colors">
      <div class="w-12 h-12 rounded-2xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-900 shrink-0 shadow-xs">
        <i class="fa-solid fa-scissors text-lg"></i>
      </div>
      <div>
        <h4 class="text-xs uppercase tracking-wider font-bold text-neutral-950">Thiết Kế Độc Bản</h4>
        <p class="text-xs text-neutral-600 font-medium mt-0.5">May đo giới hạn số lượng</p>
      </div>
    </div>

    <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-neutral-50 transition-colors">
      <div class="w-12 h-12 rounded-2xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-900 shrink-0 shadow-xs">
        <i class="fa-solid fa-truck-fast text-lg"></i>
      </div>
      <div>
        <h4 class="text-xs uppercase tracking-wider font-bold text-neutral-950">Freeship Toàn Quốc</h4>
        <p class="text-xs text-neutral-600 font-medium mt-0.5">Áp dụng đơn từ 500.000₫</p>
      </div>
    </div>

    <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-neutral-50 transition-colors">
      <div class="w-12 h-12 rounded-2xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-900 shrink-0 shadow-xs">
        <i class="fa-solid fa-repeat text-lg"></i>
      </div>
      <div>
        <h4 class="text-xs uppercase tracking-wider font-bold text-neutral-950">Đổi Trả 30 Ngày</h4>
        <p class="text-xs text-neutral-600 font-medium mt-0.5">Hỗ trợ đổi size tận nơi</p>
      </div>
    </div>

    <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-neutral-50 transition-colors">
      <div class="w-12 h-12 rounded-2xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-900 shrink-0 shadow-xs">
        <i class="fa-solid fa-medal text-lg"></i>
      </div>
      <div>
        <h4 class="text-xs uppercase tracking-wider font-bold text-neutral-950">Bảo Hành Đường May</h4>
        <p class="text-xs text-neutral-600 font-medium mt-0.5">Cam kết 100% chất lượng</p>
      </div>
    </div>

  </div>
</section>

<!-- ========================================================================= -->
<!-- 3. FEATURED COLLECTIONS GRID -->
<!-- ========================================================================= -->
<section id="collections" class="w-full py-20 px-6 max-w-7xl mx-auto">
  <div class="text-center max-w-2xl mx-auto mb-14">
    <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-bold block mb-2">BỘ SƯU TẬP ĐỘC BẢN 2026</span>
    <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium">
      Danh Mục Tuyển Chọn
    </h2>
    <p class="text-xs md:text-sm text-neutral-600 font-medium mt-3">
      Trang phục may đo chuẩn phong cách Ý, chế tác từ lụa tơ tằm nguyên bản và sợi tự nhiên bền vững
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- Collection 1: Sơ Mi Lụa & May Đo -->
    <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="collection-card group relative h-[480px] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-end p-7">
      <img src="https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?q=80&w=800&auto=format&fit=crop" alt="Thời Trang Nam Beestyle" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/95 via-neutral-950/50 to-transparent"></div>
      <div class="relative z-10 text-white">
        <span class="text-[11px] tracking-[0.3em] uppercase text-amber-300 font-bold block mb-1">ATELIER TAILORING</span>
        <h3 class="font-serif-luxury text-2xl font-bold mb-2 text-white drop-shadow-sm">Sơ Mi Lụa Nam</h3>
        <p class="text-xs text-neutral-200 font-normal mb-4 line-clamp-2 drop-shadow-sm">Sơ mi lụa tơ tằm dệt tay, cổ ép keo Đức và khuy xà cừ tự nhiên.</p>
        <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-300 group-hover:text-amber-200 transition-colors">
          Khám Phá Bộ Sưu Tập <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
        </span>
      </div>
    </a>

    <!-- Collection 2: Blazer & Áo Khoác -->
    <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="collection-card group relative h-[480px] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-end p-7">
      <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop" alt="Blazer May Đo Beestyle" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/95 via-neutral-950/50 to-transparent"></div>
      <div class="relative z-10 text-white">
        <span class="text-[11px] tracking-[0.3em] uppercase text-amber-300 font-bold block mb-1">ITALIAN SARTORIAL</span>
        <h3 class="font-serif-luxury text-2xl font-bold mb-2 text-white drop-shadow-sm">Blazer May Đo Chuẩn Ý</h3>
        <p class="text-xs text-neutral-200 font-normal mb-4 line-clamp-2 drop-shadow-sm">Phom suông hiện đại, đệm vai tự nhiên tôn trọn vóc dáng nam tính.</p>
        <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-300 group-hover:text-amber-200 transition-colors">
          Khám Phá Bộ Sưu Tập <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
        </span>
      </div>
    </a>

    <!-- Collection 3: Polo Luxury Cotton -->
    <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="collection-card group relative h-[480px] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-end p-7">
      <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?q=80&w=800&auto=format&fit=crop" alt="Polo Nam Luxury" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/95 via-neutral-950/50 to-transparent"></div>
      <div class="relative z-10 text-white">
        <span class="text-[11px] tracking-[0.3em] uppercase text-amber-300 font-bold block mb-1">PREMIUM COTTON</span>
        <h3 class="font-serif-luxury text-2xl font-bold mb-2 text-white drop-shadow-sm">Polo Dệt Tổ Ong</h3>
        <p class="text-xs text-neutral-200 font-normal mb-4 line-clamp-2 drop-shadow-sm">100% Sợi Cotton chải kỹ kháng khuẩn, giữ phom cổ bẻ thẳng thớm.</p>
        <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-300 group-hover:text-amber-200 transition-colors">
          Khám Phá Bộ Sưu Tập <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
        </span>
      </div>
    </a>

    <!-- Collection 4: Áo Thun Streetwear Boxy -->
    <a href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}" class="collection-card group relative h-[480px] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-end p-7">
      <img src="https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop" alt="Áo Thun Nam" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/95 via-neutral-950/50 to-transparent"></div>
      <div class="relative z-10 text-white">
        <span class="text-[11px] tracking-[0.3em] uppercase text-amber-300 font-bold block mb-1">STREETWEAR 250GSM</span>
        <h3 class="font-serif-luxury text-2xl font-bold mb-2 text-white drop-shadow-sm">Áo Phông Foam Boxy</h3>
        <p class="text-xs text-neutral-200 font-normal mb-4 line-clamp-2 drop-shadow-sm">Định lượng Heavyweight dày dặn, phom suông rộng thoải mái cá tính.</p>
        <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-300 group-hover:text-amber-200 transition-colors">
          Khám Phá Bộ Sưu Tập <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
        </span>
      </div>
    </a>

  </div>
</section>

<!-- ========================================================================= -->
<!-- FLASH SALE GIỜ VÀNG (REALTIME DAILY DEALS) -->
<!-- ========================================================================= -->
@if(isset($runningDailyDeals) && $runningDailyDeals->isNotEmpty())
<section class="w-full max-w-7xl mx-auto px-6 mb-20" id="flash-sale-section">
  <!-- Flash Sale Header Container -->
  <div class="rounded-3xl bg-gradient-to-br from-neutral-950 via-neutral-900 to-neutral-950 border border-neutral-800 p-6 md:p-8 shadow-2xl relative overflow-hidden">
    
    <!-- Ambient Glow Background -->
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-rose-600/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

    <!-- Header bar with Title, Fire Badge and Countdown Timer -->
    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-6 border-b border-neutral-800">
      <div>
        <div class="flex items-center gap-3 mb-2 flex-wrap">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-600 text-white text-xs font-black uppercase tracking-wider shadow-md animate-pulse">
            <i class="fa-solid fa-bolt text-amber-300"></i> FLASH SALE
          </span>
          <span class="text-xs tracking-widest uppercase text-amber-400 font-bold font-mono">
            {{ $currentSlotName ?? 'ĐẶC QUYỀN GIỜ VÀNG' }}
          </span>
        </div>
        <h2 class="font-serif-luxury text-2xl sm:text-4xl font-bold text-white tracking-wide">
          Săn Deal Giờ Vàng — Giảm Đến 70%
        </h2>
      </div>

      <!-- Realtime Countdown Clock -->
      <div class="flex items-center gap-3 bg-neutral-900/90 border border-neutral-800 px-5 py-3 rounded-2xl shadow-inner shrink-0">
        <span class="text-[11px] font-bold text-neutral-300 uppercase tracking-widest hidden sm:inline">KẾT THÚC SAU:</span>
        <div class="flex items-center gap-1.5 text-center">
          <div class="bg-neutral-950 border border-neutral-700 px-2.5 py-1.5 rounded-lg min-w-[38px]">
            <span id="flashHours" class="font-mono font-black text-white text-base md:text-lg">00</span>
            <span class="block text-[8px] text-neutral-400 font-bold uppercase">GIỜ</span>
          </div>
          <span class="text-amber-400 font-black text-lg">:</span>
          <div class="bg-neutral-950 border border-neutral-700 px-2.5 py-1.5 rounded-lg min-w-[38px]">
            <span id="flashMinutes" class="font-mono font-black text-white text-base md:text-lg">00</span>
            <span class="block text-[8px] text-neutral-400 font-bold uppercase">PHÚT</span>
          </div>
          <span class="text-amber-400 font-black text-lg">:</span>
          <div class="bg-neutral-950 border border-rose-600/60 px-2.5 py-1.5 rounded-lg min-w-[38px]">
            <span id="flashSeconds" class="font-mono font-black text-rose-500 text-base md:text-lg">00</span>
            <span class="block text-[8px] text-rose-400 font-bold uppercase">GIÂY</span>
          </div>
        </div>
        <a href="{{ route('client.daily-deals.index') }}" class="hidden lg:inline-flex items-center gap-1.5 text-xs font-bold text-amber-400 hover:text-amber-300 transition-colors ml-3 pl-3 border-l border-neutral-700">
          <span>Xem tất cả</span>
          <i class="fa-solid fa-chevron-right text-[10px]"></i>
        </a>
      </div>
    </div>

    <!-- Product Cards Grid -->
    <div class="relative z-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 pt-8">
      @foreach($runningDailyDeals as $deal)
        @php
          $p = $deal->product;
          if (!$p) continue;
          $img = $p->primaryImage->image_path ?? $p->image ?? 'assets/img/products/1.png';
          if (!str_starts_with($img, 'http')) {
            $img = asset($img);
          }
          $soldCount = $deal->sold_count ?? rand(12, 38);
          $limitCount = $deal->quantity_limit ?: 50;
          $soldPercent = min(98, max(25, round(($soldCount / max(1, $limitCount)) * 100)));
          $savings = max(0, $p->price - $deal->deal_price);
        @endphp
        <div class="group flex flex-col bg-white rounded-2xl border border-neutral-200 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300">
          
          <!-- Image Container with Discount Badge -->
          <a href="{{ route('client.products.show', $p->id) }}" class="relative aspect-[3/4] bg-neutral-100 overflow-hidden block">
            <img src="{{ $img }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            
            <!-- Top Badges -->
            <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 pointer-events-none">
              <span class="px-2 py-0.5 bg-rose-600 text-white text-[10px] font-black uppercase rounded shadow flex items-center gap-1">
                <i class="fa-solid fa-bolt text-amber-300 text-[9px]"></i> -{{ $deal->discount_percent }}%
              </span>
            </div>

            @if($savings > 0)
              <span class="absolute bottom-2.5 left-2.5 px-2 py-0.5 bg-neutral-950/80 backdrop-blur-md text-amber-300 text-[9px] font-bold rounded">
                Tiết kiệm {{ number_format($savings, 0, ',', '.') }}₫
              </span>
            @endif
          </a>

          <!-- Details -->
          <div class="p-4 flex flex-col justify-between flex-grow">
            <div>
              <span class="text-[10px] tracking-widest uppercase text-amber-600 font-bold block mb-1">
                {{ $p->category->name ?? 'Beestyle Atelier' }}
              </span>
              <a href="{{ route('client.products.show', $p->id) }}" class="font-serif-luxury text-sm md:text-base font-bold text-neutral-950 hover:text-amber-700 transition-colors line-clamp-2 leading-snug">
                {{ $p->name }}
              </a>
            </div>

            <div class="mt-3 pt-3 border-t border-neutral-100">
              <!-- Pricing -->
              <div class="flex items-baseline gap-2 mb-2 flex-wrap">
                <span class="font-serif-luxury text-lg md:text-xl font-black text-rose-600">
                  {{ number_format($deal->deal_price, 0, ',', '.') }}₫
                </span>
                <span class="text-xs text-neutral-500 line-through font-medium">
                  {{ number_format($p->price, 0, ',', '.') }}₫
                </span>
              </div>

              <!-- Sold Progress Bar -->
              <div class="mb-3">
                <div class="w-full bg-neutral-100 rounded-full h-3.5 relative overflow-hidden border border-neutral-200">
                  <div class="bg-gradient-to-r from-amber-500 to-rose-600 h-full rounded-full transition-all duration-500" style="width: {{ $soldPercent }}%;"></div>
                  <span class="absolute inset-0 flex items-center justify-center text-[9px] font-bold text-neutral-900 tracking-wider">
                    🔥 Đã bán {{ $soldPercent }}%
                  </span>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="grid grid-cols-2 gap-2">
                <button type="button" 
                        onclick="openQuickVariantModal({{ $p->id }}, false, this)" 
                        class="w-full py-2 bg-neutral-950 hover:bg-neutral-800 text-white text-[11px] font-bold uppercase rounded-xl transition-all shadow text-center flex items-center justify-center gap-1 active:scale-95 cursor-pointer" 
                        title="Thêm vào giỏ hàng">
                  <i class="fa-solid fa-cart-plus text-[10px]"></i>
                  <span>Thêm Giỏ</span>
                </button>
                <button type="button" 
                        onclick="openQuickVariantModal({{ $p->id }}, true, this)" 
                        class="w-full py-2 bg-amber-400 hover:bg-amber-500 text-neutral-950 text-[11px] font-black uppercase rounded-xl transition-all shadow text-center flex items-center justify-center gap-1 active:scale-95 cursor-pointer" 
                        title="Mua ngay">
                  <i class="fa-solid fa-bolt text-[10px]"></i>
                  <span>Mua Ngay</span>
                </button>
              </div>
            </div>
          </div>

        </div>
      @endforeach
    </div>

    <!-- Bottom Promo Bar -->
    <div class="relative z-10 mt-8 pt-6 border-t border-neutral-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-300">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-ticket text-amber-400 text-sm"></i>
        <span>Nhập mã <strong class="text-amber-400 font-mono bg-neutral-900 px-2 py-0.5 rounded border border-amber-400/40 cursor-pointer" onclick="copyCouponTopBar('BEESTYLE15')">BEESTYLE15</strong> giảm thêm 15% khi thanh toán!</span>
      </div>
      <a href="{{ route('client.daily-deals.index') }}" class="inline-flex items-center gap-2 font-bold text-amber-400 hover:text-white transition-colors uppercase tracking-wider text-xs">
        <span>Xem tất cả ưu đãi trong ngày</span>
        <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>

  </div>
</section>
@endif

<!-- ========================================================================= -->
<!-- 4. EDITORIAL LOOKBOOK SECTION -->
<!-- ========================================================================= -->
<section id="lookbook" class="w-full bg-neutral-950 text-white py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16 pb-6 border-b border-neutral-800">
      <div>
        <span class="text-xs tracking-[0.4em] uppercase text-amber-400 font-bold block mb-2">EDITORIAL CAMPAIGN 2026</span>
        <h2 class="font-serif-luxury text-3xl md:text-5xl font-light text-white">
          Lookbook: "Vũ Điệu Của Lụa &amp; Dạ"
        </h2>
      </div>
      <p class="text-xs md:text-sm text-neutral-300 max-w-md font-normal leading-relaxed">
        Mỗi khung hình là câu chuyện về ánh sáng, phom dáng và sự thăng hoa của vật liệu tự nhiên trong không gian sống đương đại.
      </p>
    </div>

    <!-- Editorial Magazine Layout -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
      
      <div class="md:col-span-7 aspect-[4/5] rounded-2xl overflow-hidden bg-neutral-900 shadow-2xl relative group">
        <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1200&auto=format&fit=crop" alt="Lookbook Editorial Shot 1" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        <div class="absolute bottom-6 left-6 right-6 p-6 bg-neutral-950/85 backdrop-blur-md rounded-2xl border border-neutral-700/60 text-white">
          <span class="text-[10px] tracking-widest uppercase text-amber-300 font-bold block mb-1">LOOK 01 — THE AUTUMN COAT</span>
          <h3 class="font-serif-luxury text-xl font-bold mb-2 text-white">Măng Tô Dạ Cashmere &amp; Sơ Mi Lụa</h3>
          <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="text-xs text-amber-300 hover:text-white inline-flex items-center gap-1.5 font-bold transition-colors">
            <span>Xem tác phẩm chi tiết</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>

      <div class="md:col-span-5 flex flex-col gap-8">
        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-neutral-900 shadow-xl relative group">
          <img src="https://images.unsplash.com/photo-1539008835657-9e8e9680c956?q=80&w=800&auto=format&fit=crop" alt="Lookbook Editorial Shot 2" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          <div class="absolute bottom-4 left-4 right-4 p-4 bg-neutral-950/85 backdrop-blur-md rounded-xl border border-neutral-700/60 text-white">
            <span class="text-[9px] tracking-widest uppercase text-amber-300 font-bold block">LOOK 02 — THE SILK SHIRT</span>
            <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="font-serif-luxury text-base font-bold hover:text-amber-300 transition-colors block text-white mt-0.5">Sơ Mi Lụa Dệt Tay Thắt Nơ Cổ</a>
          </div>
        </div>

        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-neutral-900 shadow-xl relative group">
          <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop" alt="Lookbook Editorial Shot 3" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          <div class="absolute bottom-4 left-4 right-4 p-4 bg-neutral-950/85 backdrop-blur-md rounded-xl border border-neutral-700/60 text-white">
            <span class="text-[9px] tracking-widest uppercase text-amber-300 font-bold block">LOOK 03 — ITALIAN SARTORIAL</span>
            <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="font-serif-luxury text-base font-bold hover:text-amber-300 transition-colors block text-white mt-0.5">Blazer May Đo Peak Lapel Chuẩn Ý</a>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- ========================================================================= -->
<!-- 5. DYNAMIC FEATURED PRODUCTS FROM DATABASE -->
<!-- ========================================================================= -->
<section class="w-full py-24 px-6 max-w-7xl mx-auto">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-14">
    <div>
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-bold block mb-2">TUYỂN TẬP CAO CẤP</span>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium">
        Sản Phẩm Đang Được Yêu Thích
      </h2>
    </div>
    <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-neutral-900 hover:text-amber-700 transition-colors">
      <span>Xem Tất Cả Sản Phẩm</span>
      <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>

  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
    @php
      $displayProducts = $featuredProducts->isNotEmpty() ? $featuredProducts : $products;
    @endphp

    @forelse($displayProducts->take(8) as $item)
      @php
        $minPrice = $item->variants->min('price') ?? $item->price ?? 0;
        $primaryImg = $item->primaryImage->image_path ?? $item->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=600&auto=format&fit=crop';
        if (!str_starts_with($primaryImg, 'http')) {
          $primaryImg = asset($primaryImg);
        }
      @endphp
      <div class="group flex flex-col bg-white rounded-2xl border border-neutral-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
        <!-- Thumbnail -->
        <a href="{{ route('client.products.show', $item->id) }}" class="aspect-[3/4] relative bg-neutral-100 overflow-hidden block">
          <img src="{{ $primaryImg }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          
          @if($item->is_new)
            <span class="absolute top-3 left-3 px-2.5 py-1 bg-neutral-950 text-white text-[10px] tracking-widest uppercase font-bold rounded-md shadow-sm">MỚI</span>
          @else
            <span class="absolute top-3 left-3 px-2.5 py-1 bg-amber-400 text-neutral-950 text-[10px] tracking-widest uppercase font-black rounded-md shadow-sm">BÁN CHẠY</span>
          @endif

          <div class="absolute inset-0 bg-neutral-950/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-4">
            <span class="w-full py-2.5 bg-white text-neutral-950 text-xs font-bold tracking-wider uppercase rounded-xl shadow-lg text-center hover:bg-amber-400 transition-colors">Xem Chi Tiết</span>
          </div>
        </a>

        <!-- Body -->
        <div class="p-5 flex flex-col flex-grow justify-between">
          <div>
            <span class="text-[11px] tracking-wider uppercase text-amber-800 font-bold block mb-1">
              {{ $item->category->name ?? 'Beestyle Studio' }}
            </span>
            <a href="{{ route('client.products.show', $item->id) }}" class="font-serif-luxury text-base font-bold text-neutral-950 hover:text-amber-700 transition-colors line-clamp-2 leading-snug">
              {{ $item->name }}
            </a>
            <div class="flex items-center gap-1.5 text-xs text-amber-500 font-semibold mt-1.5">
              <span>★★★★★</span>
              <span class="text-[11px] text-neutral-600 font-medium">(5.0 • Đã bán 120+)</span>
            </div>
          </div>

          <div class="mt-4 pt-3 border-t border-neutral-100 flex flex-col gap-2.5">
            <div class="flex items-baseline justify-between">
              <span class="font-serif-luxury text-lg font-black text-neutral-950">
                {{ number_format($minPrice, 0, ',', '.') }}₫
              </span>
              @if($item->original_price && $item->original_price > $minPrice)
                <span class="text-xs text-neutral-500 line-through">
                  {{ number_format($item->original_price, 0, ',', '.') }}₫
                </span>
              @endif
            </div>

            <!-- Cặp nút Thêm Giỏ & Mua Ngay -->
            <div class="grid grid-cols-2 gap-2">
              <button type="button" 
                      onclick="openQuickVariantModal({{ $item->id }}, false, this)" 
                      class="w-full py-2 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl flex items-center justify-center gap-1.5 shadow-sm transition-all active:scale-95 cursor-pointer" 
                      title="Thêm vào giỏ hàng">
                <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                <span class="truncate">Thêm Giỏ</span>
              </button>
              <button type="button" 
                      onclick="openQuickVariantModal({{ $item->id }}, true, this)" 
                      class="w-full py-2 bg-amber-400 hover:bg-amber-500 text-neutral-950 text-xs font-black uppercase tracking-wider rounded-xl flex items-center justify-center gap-1.5 shadow-sm transition-all active:scale-95 cursor-pointer" 
                      title="Mua ngay — Thanh toán tức thì">
                <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                <span class="truncate">Mua Ngay</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-4 text-center py-12 text-neutral-600 text-sm font-medium">
        Đang cập nhật các tác phẩm may đo mới nhất...
      </div>
    @endforelse
  </div>
</section>

<!-- ========================================================================= -->
<!-- 6. SOCIAL PROOF & VERIFIED REVIEWS -->
<!-- ========================================================================= -->
<section class="w-full bg-neutral-50 border-y border-neutral-200 py-20 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center max-w-2xl mx-auto mb-14">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-bold block mb-2">ĐÁNH GIÁ TỪ QUÝ KHÁCH HÀNG</span>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium">
        Trải Nghiệm Khách Hàng Thực Tế
      </h2>
      <div class="flex items-center justify-center gap-2 mt-3 text-sm text-neutral-700 font-semibold">
        <span class="text-amber-500 font-bold text-base">★★★★★</span>
        <span>4.9 / 5.0 Điểm đánh giá dựa trên 1.250+ khách hàng thân thiết</span>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white p-7 rounded-2xl border border-neutral-200 shadow-sm flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-3">
            <div class="text-amber-500 text-sm">★★★★★</div>
            <span class="badge bg-success-subtle text-success text-[10px] font-bold px-2 py-0.5 rounded-full border border-success-subtle">
              <i class="fa-solid fa-circle-check me-1"></i> Đã mua hàng
            </span>
          </div>
          <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed italic mb-4">
            "Chất vải lụa tơ tằm cực kỳ thoáng mát và sang trọng. Tôi mặc dự tiệc cưới ai cũng khen phom áo đứng dáng và chuẩn mực. Đóng gói rất chu đáo như một hộp quà cao cấp!"
          </p>
        </div>
        <div class="pt-4 border-t border-neutral-100 flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-neutral-900 text-amber-300 flex items-center justify-center font-bold text-sm">MH</div>
          <div>
            <strong class="text-xs text-neutral-950 block">Nguyễn Minh Hoàng</strong>
            <span class="text-[11px] text-neutral-500 font-medium">Sơ Mi Lụa Atelier • TP. Hồ Chí Minh</span>
          </div>
        </div>
      </div>

      <div class="bg-white p-7 rounded-2xl border border-neutral-200 shadow-sm flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-3">
            <div class="text-amber-500 text-sm">★★★★★</div>
            <span class="badge bg-success-subtle text-success text-[10px] font-bold px-2 py-0.5 rounded-full border border-success-subtle">
              <i class="fa-solid fa-circle-check me-1"></i> Đã mua hàng
            </span>
          </div>
          <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed italic mb-4">
            "Áo Blazer may đo chuẩn Ý, đệm vai tự nhiên tôn dáng. Lần đầu tôi mua online mà vừa vặn như được thợ đo trực tiếp. Dịch vụ tư vấn size cực kỳ nhiệt tình."
          </p>
        </div>
        <div class="pt-4 border-t border-neutral-100 flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-neutral-900 text-amber-300 flex items-center justify-center font-bold text-sm">TA</div>
          <div>
            <strong class="text-xs text-neutral-950 block">Trần Tuấn Anh (KTS)</strong>
            <span class="text-[11px] text-neutral-500 font-medium">Blazer Peak Lapel • Hà Nội</span>
          </div>
        </div>
      </div>

      <div class="bg-white p-7 rounded-2xl border border-neutral-200 shadow-sm flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between mb-3">
            <div class="text-amber-500 text-sm">★★★★★</div>
            <span class="badge bg-success-subtle text-success text-[10px] font-bold px-2 py-0.5 rounded-full border border-success-subtle">
              <i class="fa-solid fa-circle-check me-1"></i> Đã mua hàng
            </span>
          </div>
          <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed italic mb-4">
            "Chính sách đổi size tận nhà của BeeStyle quá chuyên nghiệp. Bưu tá mang áo size mới đến tận cửa để tôi thử và thu hồi size cũ không hề tính thêm phí."
          </p>
        </div>
        <div class="pt-4 border-t border-neutral-100 flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-neutral-900 text-amber-300 flex items-center justify-center font-bold text-sm">QH</div>
          <div>
            <strong class="text-xs text-neutral-950 block">Lê Quang Huy (Designer)</strong>
            <span class="text-[11px] text-neutral-500 font-medium">Polo Dệt Tổ Ong • Đà Nẵng</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 7. BRAND STORY SECTION -->
<!-- ========================================================================= -->
<section id="about" class="w-full bg-white py-24 px-6">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
    
    <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl bg-neutral-100 border border-neutral-200">
      <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1200&auto=format&fit=crop" alt="Beestyle Atelier Studio" class="w-full h-full object-cover">
    </div>

    <div class="lg:pl-6">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-bold block mb-2">TRIẾT LÝ THIẾT KẾ</span>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium mb-6 leading-tight">
        BeeStyle: Nét Đẹp May Đo Tối Giản &amp; Chuẩn Mực
      </h2>
      <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed mb-4">
        Thành lập tại Sài Gòn, BeeStyle ra đời với ước vọng kiến tạo nên những tác phẩm may mặc vượt qua vòng xoáy của thời trang nhanh. Chúng tôi tập trung vào 3 giá trị cốt lõi: <strong>Chất liệu tự nhiên cao cấp</strong>, <strong>Kỹ thuật may đo thủ công</strong> và <strong>Phom dáng tối giản bền vững</strong>.
      </p>
      <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed mb-8">
        Mỗi chiếc áo sơ mi lụa, blazer hay polo đều được người nghệ nhân dồn trọn tâm huyết, mang đến cho bạn trải nghiệm mặc êm ái, thanh lịch và tự tin trong mọi khoảnh khắc.
      </p>

      <div class="grid grid-cols-3 gap-6 pt-6 border-t border-neutral-200 text-center">
        <div class="p-3 bg-neutral-50 rounded-2xl border border-neutral-200">
          <span class="font-serif-luxury text-3xl md:text-4xl text-neutral-950 font-black block text-amber-600">100%</span>
          <span class="text-[11px] tracking-wider uppercase text-neutral-700 font-bold mt-1 block">Chất liệu tự nhiên</span>
        </div>
        <div class="p-3 bg-neutral-50 rounded-2xl border border-neutral-200">
          <span class="font-serif-luxury text-3xl md:text-4xl text-neutral-950 font-black block text-amber-600">12+</span>
          <span class="text-[11px] tracking-wider uppercase text-neutral-700 font-bold mt-1 block">Nghệ nhân Atelier</span>
        </div>
        <div class="p-3 bg-neutral-50 rounded-2xl border border-neutral-200">
          <span class="font-serif-luxury text-3xl md:text-4xl text-neutral-950 font-black block text-amber-600">30 Ngày</span>
          <span class="text-[11px] tracking-wider uppercase text-neutral-700 font-bold mt-1 block">Đổi trả tại nhà</span>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ========================================================================= -->
<!-- 8. VIP MEMBER NEWSLETTER -->
<!-- ========================================================================= -->
<section class="w-full bg-neutral-950 text-white py-20 px-6 text-center">
  <div class="max-w-2xl mx-auto">
    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/20 text-amber-300 text-xs font-bold uppercase tracking-wider mb-4 border border-amber-400/40">
      <i class="fa-solid fa-gem text-amber-400"></i> BEESTYLE PRIVÉ
    </div>
    <h2 class="font-serif-luxury text-3xl md:text-4xl font-normal mb-4 text-white">Đăng Ký Nhận Đặc Quyền Thành Viên</h2>
    <p class="text-xs md:text-sm text-neutral-300 font-normal leading-relaxed mb-8 max-w-xl mx-auto">
      Nhận ngay mã ưu đãi <strong class="text-amber-400 font-mono">BEESTYLE15</strong> (giảm 15% cho đơn hàng đầu tiên) cùng thông báo sớm nhất về các đợt phát hành sản phẩm giới hạn.
    </p>

    <form onsubmit="handleNewsletter(event)" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
      <input type="email" id="newsletter-email" placeholder="Nhập địa chỉ email của bạn..." required class="bg-neutral-900 border border-neutral-700 rounded-xl px-4 py-3.5 text-xs text-white placeholder-neutral-400 focus:outline-none focus:border-amber-400 flex-grow shadow-inner">
      <button type="submit" class="px-7 py-3.5 bg-amber-400 text-neutral-950 text-xs tracking-widest uppercase font-bold hover:bg-amber-300 transition-colors rounded-xl shadow-lg shrink-0">
        Đăng Ký Ngay
      </button>
    </form>
  </div>
</section>
@endsection

@push('scripts')
<script>
  function handleNewsletter(e) {
    e.preventDefault();
    const email = document.getElementById('newsletter-email').value;
    alert(`Cảm ơn bạn (${email}) đã đăng ký nhận tin BeeStyle Privé! Mã BEESTYLE15 đã sẵn sàng sử dụng.`);
    document.getElementById('newsletter-email').value = '';
  }

  function copyCouponTopBar(code) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(code).then(() => {
        alert('Đã sao chép mã ưu đãi ' + code + ' thành công! Hãy dán mã vào bước thanh toán.');
      }).catch(() => {
        prompt('Mã ưu đãi của bạn:', code);
      });
    } else {
      prompt('Mã ưu đãi của bạn:', code);
    }
  }

  // Khởi chạy đồng hồ đếm ngược Flash Sale thời gian thực
  (function initFlashCountdown() {
    const targetStr = @json($targetCountdown ?? null);
    let targetTime = targetStr ? new Date(targetStr).getTime() : 0;
    
    function tick() {
      const now = new Date().getTime();
      let diff = targetTime - now;
      if (isNaN(diff) || diff <= 0) {
        // Fallback: tính đến 23:59:59 của ngày hôm nay
        const endOfDay = new Date();
        endOfDay.setHours(23, 59, 59, 999);
        diff = Math.max(0, endOfDay.getTime() - now);
      }

      const totalSec = Math.floor(diff / 1000);
      const hours = Math.floor(totalSec / 3600);
      const minutes = Math.floor((totalSec % 3600) / 60);
      const seconds = totalSec % 60;

      const hEl = document.getElementById('flashHours');
      const mEl = document.getElementById('flashMinutes');
      const sEl = document.getElementById('flashSeconds');

      if (hEl) hEl.textContent = String(hours).padStart(2, '0');
      if (mEl) mEl.textContent = String(minutes).padStart(2, '0');
      if (sEl) sEl.textContent = String(seconds).padStart(2, '0');
    }

    tick();
    setInterval(tick, 1000);
  })();
</script>
@endpush