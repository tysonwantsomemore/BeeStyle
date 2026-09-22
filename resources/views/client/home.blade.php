@extends('layouts.client')

@section('title', 'BEESTYLE — Thời Trang Nam Cao Cấp • BST Sartorial 2026')

@section('content')
<!-- ========================================================================= -->
<!-- 1. HERO SECTION: Haute Couture Editorial Fashion Banner (DYNAMIC) -->
<!-- ========================================================================= -->
<section id="home" class="relative w-full min-h-[85vh] md:min-h-[90vh] bg-neutral-950 flex items-center justify-center overflow-hidden">
  <div class="absolute inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2000&auto=format&fit=crop" alt="Beestyle Fashion Editorial 2026" class="w-full h-full object-cover object-top opacity-50">
    <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/75 to-neutral-950/40"></div>
  </div>

  <div class="relative z-10 max-w-5xl mx-auto px-6 text-center text-white flex flex-col items-center py-16">
    <!-- Brand Tag Badge (Động từ thống kê kho sản phẩm) -->
    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/20 border border-amber-400/50 text-amber-300 text-xs font-bold uppercase tracking-[0.25em] mb-5 backdrop-blur-md shadow-sm">
      <i class="fa-solid fa-gem text-amber-400"></i> BEESTYLE ATELIER 2026 • {{ $totalActiveProducts ?? 60 }}+ THIẾT KẾ ĐỘC BẢN
    </div>

    <h1 class="font-serif-luxury text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-light tracking-wide leading-none mb-6 text-white drop-shadow-md">
      Nghệ Thuật Cắt May<br><span class="italic font-serif text-amber-300 font-normal">Đương Đại &amp; Tối Giản</span>
    </h1>
    
    <p class="text-sm md:text-base text-neutral-100 font-normal max-w-2xl mb-8 leading-relaxed drop-shadow-sm">
      Tôn vinh vẻ đẹp tự nhiên thông qua chất liệu lụa tơ tằm dệt tay, len dạ Cashmere và kỹ thuật may đo chuẩn Ý. Mỗi trang phục là một tác phẩm nghệ thuật bền vững theo thời gian.
    </p>

    @if(isset($bestCoupon) && $bestCoupon)
      <div class="mb-8 inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/10 border border-amber-400/40 backdrop-blur-md text-xs text-amber-300">
        <i class="fa-solid fa-tag text-amber-400"></i>
        <span>Ưu đãi hôm nay: Nhập mã <strong class="text-white font-mono bg-neutral-900/80 px-2 py-0.5 rounded cursor-pointer hover:bg-neutral-800" onclick="copyCouponCode('{{ $bestCoupon->code }}')">{{ $bestCoupon->code }}</strong> để được giảm {{ $bestCoupon->discount_type === 'percent' ? ($bestCoupon->discount_value . '%') : (number_format($bestCoupon->discount_value, 0, ',', '.') . '₫') }}</span>
      </div>
    @endif
    
    <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
      <a href="{{ route('client.products.index') }}" class="w-full sm:w-auto px-8 py-4 bg-amber-400 hover:bg-amber-300 text-neutral-950 text-xs tracking-[0.25em] uppercase font-bold transition-all duration-300 shadow-2xl rounded-xl flex items-center justify-center gap-2">
        <span>Khám Phá Sản Phẩm</span>
        <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
      <a href="#bestsellers-section" class="w-full sm:w-auto px-8 py-4 bg-white/10 border border-white/40 text-white text-xs tracking-[0.25em] uppercase font-bold hover:bg-white/20 transition-all duration-300 rounded-xl backdrop-blur-sm flex items-center justify-center gap-2">
        <i class="fa-solid fa-fire text-rose-400"></i>
        <span>Xem Bán Chạy ({{ $bestSellerShortRange }})</span>
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
<section class="w-full bg-white border-b border-neutral-200 py-8 px-6 shadow-xs">
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
<!-- 3. KHO VOUCHER & ĐẶC QUYỀN ƯU ĐÃI (1 DÒNG DYNAMIC + NÚT XEM TẤT CẢ) -->
<!-- ========================================================================= -->
@if(isset($coupons) && $coupons->isNotEmpty())
<section class="w-full bg-gradient-to-b from-neutral-950 via-neutral-900 to-neutral-950 text-white py-14 px-6 border-b border-neutral-800 relative overflow-hidden" id="voucher-hub-section">
  <!-- Ambient background glow effects -->
  <div class="absolute top-0 right-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>

  <div class="max-w-7xl mx-auto relative z-10">
    
    <!-- Section Header với Nút Xem Tất Cả & Điều Hướng Trượt 1 Dòng -->
    <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-6 mb-8 pb-6 border-b border-neutral-800">
      <div>
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-amber-400/20 via-rose-400/20 to-amber-400/20 text-amber-300 text-[11px] font-black uppercase tracking-[0.2em] mb-2.5 border border-amber-400/40 shadow-sm">
          <i class="fa-solid fa-gift text-amber-400 animate-pulse"></i> ĐẶC QUYỀN MUA SẮM • SỐ LƯỢNG CÓ HẠN
        </div>
        <h2 class="font-serif-luxury text-2xl sm:text-3xl md:text-4xl text-white font-semibold tracking-wide">
          Kho Voucher &amp; Mã Giảm Giá Hôm Nay
        </h2>
        <p class="text-xs text-neutral-400 mt-1.5 max-w-xl">
          Thu thập mã giảm giá để nhận chiết khấu trực tiếp khi thanh toán. <span class="text-amber-300 font-semibold">Quy định: Mỗi mã chỉ được lấy 1 lần duy nhất</span>.
        </p>
      </div>

      <!-- Controls & Nút Xem Tất Cả -->
      <div class="flex items-center gap-3 shrink-0 flex-wrap">
        <!-- Bộ đếm ví voucher -->
        <div class="hidden lg:flex items-center gap-2 bg-neutral-900/90 border border-neutral-800 px-3.5 py-2 rounded-2xl shadow-inner text-xs">
          <i class="fa-solid fa-wallet text-amber-400 text-sm"></i>
          <span class="text-neutral-400 font-medium">Đã thu thập:</span>
          <strong class="text-amber-400 font-black"><span id="claimedCountDisplay">0</span>/{{ $coupons->count() }}</strong>
        </div>

        <!-- Nút Xem Tất Cả Vouchers -->
        <button type="button" 
                onclick="openAllVouchersModal()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-neutral-900 hover:bg-neutral-800 text-amber-300 hover:text-white text-xs font-bold uppercase tracking-wider transition-all border border-neutral-700 hover:border-amber-400/60 shadow-sm cursor-pointer active:scale-95">
          <i class="fa-solid fa-layer-group text-xs text-amber-400"></i>
          <span>Xem tất cả ({{ $coupons->count() }})</span>
          <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </button>

        <!-- Mũi tên trượt 1 dòng (Prev / Next) -->
        <div class="flex items-center gap-1.5 bg-neutral-900/90 border border-neutral-800 p-1 rounded-2xl">
          <button type="button" 
                  onclick="scrollVouchers(-1)" 
                  class="w-8 h-8 rounded-xl bg-neutral-950 hover:bg-amber-400 hover:text-neutral-950 text-neutral-300 flex items-center justify-center transition-all cursor-pointer shadow-xs" 
                  title="Xem trước">
            <i class="fa-solid fa-chevron-left text-xs"></i>
          </button>
          <button type="button" 
                  onclick="scrollVouchers(1)" 
                  class="w-8 h-8 rounded-xl bg-neutral-950 hover:bg-amber-400 hover:text-neutral-950 text-neutral-300 flex items-center justify-center transition-all cursor-pointer shadow-xs" 
                  title="Xem tiếp">
            <i class="fa-solid fa-chevron-right text-xs"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- TRACK VOUCHER HIỂN THỊ TRÊN 1 DÒNG DUY NHẤT (HORIZONTAL SCROLL) -->
    <div class="relative">
      <div id="voucherScrollTrack" class="flex gap-5 overflow-x-auto no-scrollbar scroll-smooth pb-4 pt-1 snap-x snap-mandatory">
        @foreach($coupons as $coupon)
          @php
            $isPercent = ($coupon->discount_type ?? 'percent') === 'percent';
            $isShipping = ($coupon->discount_type ?? '') === 'shipping';
            $valFormatted = number_format($coupon->discount_value, 0, ',', '.');
            
            if ($isPercent) {
              $badgeText = 'GIẢM ' . $coupon->discount_value . '%';
              $badgeGradient = 'from-amber-400 to-amber-500 text-neutral-950';
              $icon = 'fa-percent';
            } elseif ($isShipping) {
              $badgeText = 'FREESHIP ' . $valFormatted . '₫';
              $badgeGradient = 'from-emerald-400 to-teal-500 text-neutral-950';
              $icon = 'fa-truck-fast';
            } else {
              $badgeText = 'GIẢM ' . $valFormatted . '₫';
              $badgeGradient = 'from-rose-500 to-amber-500 text-white';
              $icon = 'fa-tag';
            }

            $hasLimit = ($coupon->total_limit > 0);
            $usedCount = (int)($coupon->used_count ?: 0);
            $totalLimit = (int)($coupon->total_limit ?: 100);
            $usedPercent = $hasLimit ? min(100, max(8, round(($usedCount / max(1, $totalLimit)) * 100))) : null;
            $remainCount = $hasLimit ? max(0, $totalLimit - $usedCount) : null;
          @endphp

          <!-- Thẻ Voucher trên 1 dòng -->
          <div id="coupon-card-{{ $coupon->code }}" 
               data-coupon-code="{{ $coupon->code }}" 
               class="coupon-card w-[310px] sm:w-[350px] shrink-0 snap-start relative bg-gradient-to-br from-neutral-900/95 via-neutral-900/90 to-neutral-950 border border-neutral-800 hover:border-amber-400/70 rounded-3xl p-5 flex flex-col justify-between transition-all duration-300 group shadow-xl hover:shadow-2xl hover:shadow-amber-500/10 overflow-hidden">
            
            <!-- Perforated ticket cutout notches -->
            <div class="absolute -left-3.5 top-1/2 -translate-y-1/2 w-6 h-6 bg-neutral-950 rounded-full border border-neutral-800 shadow-inner pointer-events-none"></div>
            <div class="absolute -right-3.5 top-1/2 -translate-y-1/2 w-6 h-6 bg-neutral-950 rounded-full border border-neutral-800 shadow-inner pointer-events-none"></div>

            <!-- Top Information Row -->
            <div class="pl-1">
              <div class="flex items-center justify-between gap-2 mb-3">
                <span class="px-3 py-1.5 rounded-xl bg-gradient-to-r {{ $badgeGradient }} font-black text-xs uppercase tracking-wider shadow-md flex items-center gap-1.5">
                  <i class="fa-solid {{ $icon }} text-[10px]"></i>
                  <span>{{ $badgeText }}</span>
                </span>

                <!-- Badge đã lưu (hiện khi đã nhận mã) -->
                <span class="claimed-badge hidden text-[10px] bg-emerald-500/20 text-emerald-300 font-bold px-2.5 py-1 rounded-full border border-emerald-400/40">
                  <i class="fa-solid fa-circle-check text-emerald-400"></i> Đã Lưu
                </span>

                <!-- Hạn sử dụng -->
                <span class="expiry-label text-[10px] text-neutral-400 font-mono">
                  @if($coupon->expires_at)
                    HSD: {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                  @else
                    <span class="text-amber-400">Vô thời hạn</span>
                  @endif
                </span>
              </div>

              <!-- Title -->
              <h4 class="font-bold text-white text-sm line-clamp-2 leading-snug mb-2 group-hover:text-amber-300 transition-colors">
                {{ $coupon->title ?? ('Ưu đãi đặc quyền mã ' . $coupon->code) }}
              </h4>

              <!-- Conditions Details -->
              <div class="text-[11px] text-neutral-400 space-y-1 my-3 bg-neutral-950/60 p-2.5 rounded-xl border border-neutral-800/80">
                <div class="flex items-center gap-1.5">
                  <i class="fa-solid fa-cart-shopping text-amber-400 text-[10px]"></i>
                  @if($coupon->min_order_value > 0)
                    <span>Đơn tối thiểu: <strong class="text-neutral-200">{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</strong></span>
                  @else
                    <span class="text-neutral-200">Áp dụng cho mọi giá trị đơn</span>
                  @endif
                </div>

                @if($coupon->max_discount_value > 0)
                  <div class="flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-down text-rose-400 text-[10px]"></i>
                    <span>Giảm tối đa: <strong class="text-neutral-200">{{ number_format($coupon->max_discount_value, 0, ',', '.') }}₫</strong></span>
                  </div>
                @endif
              </div>

              <!-- Thanh tiến độ lượt dùng nếu có giới hạn -->
              @if($hasLimit)
                <div class="my-2.5">
                  <div class="flex justify-between items-center text-[10px] text-neutral-400 mb-1 font-medium">
                    <span>Đã dùng {{ $usedPercent }}%</span>
                    <span class="text-amber-400 font-bold">Còn {{ $remainCount }} lượt</span>
                  </div>
                  <div class="w-full bg-neutral-800 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-400 to-rose-500 h-full rounded-full transition-all duration-500" style="width: {{ $usedPercent }}%;"></div>
                  </div>
                </div>
              @endif
            </div>

            <!-- Bottom Ticket Footer & Action Button (CHỈ LẤY ĐƯỢC 1 LẦN) -->
            <div class="pt-3.5 mt-2 border-t border-dashed border-neutral-800 flex items-center justify-between gap-3 pl-1">
              <div class="font-mono font-black text-amber-400 text-xs tracking-wider bg-neutral-950 px-3 py-2 rounded-xl border border-neutral-800 shadow-inner flex items-center gap-1.5">
                <i class="fa-regular fa-ticket text-neutral-500 text-[11px]"></i>
                <span class="coupon-code-text">{{ $coupon->code }}</span>
              </div>

              <!-- Nút Lấy Mã (Chỉ bấm lấy được 1 lần, sau đó khóa vĩnh viễn) -->
              <button type="button" 
                      id="btn-coupon-{{ $coupon->code }}" 
                      data-code="{{ $coupon->code }}" 
                      onclick="copyCouponCode('{{ $coupon->code }}', this)" 
                      class="btn-claim-coupon px-3.5 py-2 bg-amber-400 hover:bg-amber-300 text-neutral-950 text-xs font-black uppercase tracking-wider rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-1.5 cursor-pointer shrink-0">
                <i class="fa-solid fa-copy text-[11px]"></i>
                <span>Lấy Mã</span>
              </button>
            </div>

          </div>
        @endforeach

        <!-- Card cuối hàng: Mở xem tất cả vouchers -->
        <div onclick="openAllVouchersModal()" 
             class="w-[260px] sm:w-[280px] shrink-0 snap-start relative bg-neutral-900/60 border-2 border-dashed border-amber-400/40 hover:border-amber-400 rounded-3xl p-6 flex flex-col items-center justify-center text-center cursor-pointer transition-all group">
          <div class="w-14 h-14 rounded-2xl bg-amber-400/20 text-amber-400 flex items-center justify-center text-xl mb-3 group-hover:scale-110 group-hover:bg-amber-400 group-hover:text-neutral-950 transition-all shadow-md">
            <i class="fa-solid fa-layer-group"></i>
          </div>
          <h4 class="font-bold text-white text-sm mb-1">Xem Toàn Bộ Voucher</h4>
          <p class="text-xs text-neutral-400 mb-4">Khám phá tất cả {{ $coupons->count() }} mã ưu đãi hiện có</p>
          <span class="px-4 py-2 bg-amber-400 text-neutral-950 text-xs font-black uppercase rounded-xl shadow-md group-hover:bg-amber-300 transition-colors">
            Mở Danh Sách
          </span>
        </div>

      </div>
    </div>

  </div>

  <!-- MODAL XEM TẤT CẢ VOUCHER (HIỂN THỊ TOÀN BỘ KHO VOUCHER DẠNG LƯỚI ĐẦY ĐỦ) -->
  <div id="allVouchersModal" 
       onclick="if(event.target === this) closeAllVouchersModal()" 
       class="fixed inset-0 z-50 bg-neutral-950/85 backdrop-blur-md hidden flex items-center justify-center p-4 sm:p-6 transition-all duration-300">
    <div class="relative w-full max-w-4xl bg-neutral-900 border border-neutral-700 rounded-3xl p-6 sm:p-8 max-h-[90vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
      
      <!-- Modal Header -->
      <div class="flex items-center justify-between pb-4 border-b border-neutral-800 shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-amber-400/20 border border-amber-400/40 text-amber-300 flex items-center justify-center text-lg">
            <i class="fa-solid fa-ticket"></i>
          </div>
          <div>
            <h3 class="font-serif-luxury text-xl sm:text-2xl text-white font-bold">Toàn Bộ Kho Voucher Hôm Nay</h3>
            <p class="text-xs text-neutral-400 mt-0.5">Thu thập mã giảm giá cho đơn hàng của bạn (Mỗi mã chỉ được lưu 1 lần)</p>
          </div>
        </div>
        <button type="button" 
                onclick="closeAllVouchersModal()" 
                class="w-9 h-9 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-neutral-400 hover:text-white flex items-center justify-center transition-colors cursor-pointer" 
                title="Đóng">
          <i class="fa-solid fa-xmark text-base"></i>
        </button>
      </div>

      <!-- Modal Body (Lưới tất cả vouchers) -->
      <div class="overflow-y-auto py-6 pr-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($coupons as $coupon)
          @php
            $isPercent = ($coupon->discount_type ?? 'percent') === 'percent';
            $isShipping = ($coupon->discount_type ?? '') === 'shipping';
            $valFormatted = number_format($coupon->discount_value, 0, ',', '.');
            
            if ($isPercent) {
              $badgeText = 'GIẢM ' . $coupon->discount_value . '%';
              $badgeGradient = 'from-amber-400 to-amber-500 text-neutral-950';
            } elseif ($isShipping) {
              $badgeText = 'FREESHIP ' . $valFormatted . '₫';
              $badgeGradient = 'from-emerald-400 to-teal-500 text-neutral-950';
            } else {
              $badgeText = 'GIẢM ' . $valFormatted . '₫';
              $badgeGradient = 'from-rose-500 to-amber-500 text-white';
            }
          @endphp
          <div class="bg-neutral-950 border border-neutral-800 rounded-2xl p-4 flex flex-col justify-between hover:border-amber-400/50 transition-all">
            <div>
              <div class="flex items-center justify-between gap-2 mb-2">
                <span class="px-2.5 py-1 rounded-lg bg-gradient-to-r {{ $badgeGradient }} font-black text-xs uppercase">
                  {{ $badgeText }}
                </span>
                <span class="text-[10px] text-neutral-400 font-mono">
                  {{ $coupon->expires_at ? ('HSD: ' . \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y')) : 'Vô thời hạn' }}
                </span>
              </div>
              <h5 class="text-white font-bold text-sm line-clamp-1 mb-1">{{ $coupon->title ?? ('Mã giảm ' . $coupon->code) }}</h5>
              <div class="text-[11px] text-neutral-400 space-y-0.5 mb-3">
                <p>• Đơn tối thiểu: <strong class="text-neutral-200">{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</strong></p>
                @if($coupon->max_discount_value > 0)
                  <p>• Giảm tối đa: <strong class="text-neutral-200">{{ number_format($coupon->max_discount_value, 0, ',', '.') }}₫</strong></p>
                @endif
              </div>
            </div>
            
            <div class="pt-3 border-t border-dashed border-neutral-800 flex items-center justify-between gap-2">
              <span class="font-mono font-bold text-amber-400 text-xs bg-neutral-900 px-2.5 py-1.5 rounded-lg border border-neutral-800">
                {{ $coupon->code }}
              </span>
              <button type="button" 
                      id="btn-modal-coupon-{{ $coupon->code }}" 
                      data-code="{{ $coupon->code }}" 
                      onclick="copyCouponCode('{{ $coupon->code }}', this)" 
                      class="btn-modal-claim px-3 py-1.5 bg-amber-400 hover:bg-amber-300 text-neutral-950 text-xs font-bold uppercase rounded-lg transition-all active:scale-95 flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-copy text-[11px]"></i>
                <span>Lấy Mã</span>
              </button>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Modal Footer -->
      <div class="pt-4 border-t border-neutral-800 flex items-center justify-between text-xs text-neutral-400 shrink-0">
        <span>Nhập mã ưu đãi tại bước thanh toán để được áp dụng giảm giá.</span>
        <button type="button" onclick="closeAllVouchersModal()" class="px-5 py-2 rounded-xl bg-neutral-800 hover:bg-neutral-700 text-white font-bold cursor-pointer">
          Đóng
        </button>
      </div>

    </div>
  </div>
</section>
@endif

<!-- ========================================================================= -->
<!-- 4. FLASH SALE GIỜ VÀNG (REALTIME DYNAMIC DAILY DEALS) -->
<!-- ========================================================================= -->
@if(isset($runningDailyDeals) && $runningDailyDeals->isNotEmpty())
<section class="w-full max-w-7xl mx-auto px-6 my-16" id="flash-sale-section">
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

    <!-- Product Cards Grid (Động từ bảng daily_deals và products) -->
    <div class="relative z-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 pt-8">
      @foreach($runningDailyDeals as $deal)
        @php
          $p = $deal->product;
          if (!$p) continue;
          $img = $p->primaryImage->image_path ?? ($p->image ?? 'assets/img/products/1.png');
          if (!str_starts_with($img, 'http')) {
            $img = asset(ltrim($img, '/'));
          }
          $soldCount = (int) ($deal->sold_count ?: 0);
          $limitCount = (int) ($deal->quantity_limit ?: 50);
          $soldPercent = min(100, max(5, round(($soldCount / max(1, $limitCount)) * 100)));
          $savings = max(0, $p->price - $deal->deal_price);
        @endphp
        <div class="group flex flex-col bg-white rounded-2xl border border-neutral-200 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300">
          
          <!-- Image Container with Discount Badge & Wishlist Heart -->
          <div class="relative aspect-[3/4] bg-neutral-100 overflow-hidden block">
            <a href="{{ route('client.products.show', $p->id) }}" class="block w-full h-full">
              <img src="{{ $img }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
            </a>

            <!-- Wishlist Heart Button -->
            <button type="button" 
                    onclick="toggleWishlist({{ $p->id }}, this)" 
                    class="btn-wishlist-{{ $p->id }} absolute top-2.5 right-2.5 z-10 w-8 h-8 rounded-full bg-white/90 hover:bg-white backdrop-blur-md flex items-center justify-center text-neutral-600 hover:text-rose-600 shadow-sm transition-all active:scale-90 cursor-pointer {{ in_array($p->id, $wishlistIds ?? []) ? 'text-rose-600' : '' }}" 
                    title="Thêm vào yêu thích">
              <i class="fa-solid fa-heart text-xs {{ in_array($p->id, $wishlistIds ?? []) ? 'text-rose-500' : '' }}"></i>
            </button>
            
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
          </div>

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

              <!-- Sold Progress Bar (Động theo dữ liệu thực) -->
              <div class="mb-3">
                <div class="w-full bg-neutral-100 rounded-full h-3.5 relative overflow-hidden border border-neutral-200">
                  <div class="bg-gradient-to-r from-amber-500 to-rose-600 h-full rounded-full transition-all duration-500" style="width: {{ $soldPercent }}%;"></div>
                  <span class="absolute inset-0 flex items-center justify-center text-[9px] font-bold text-neutral-900 tracking-wider">
                    🔥 Đã bán {{ $soldCount }}/{{ $limitCount }} ({{ $soldPercent }}%)
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
        <span>
          @if(isset($bestCoupon) && $bestCoupon)
            Nhập mã <strong class="text-amber-400 font-mono bg-neutral-900 px-2 py-0.5 rounded border border-amber-400/40 cursor-pointer" onclick="copyCouponCode('{{ $bestCoupon->code }}')">{{ $bestCoupon->code }}</strong> để nhận ưu đãi thanh toán!
          @else
            Nhập mã <strong class="text-amber-400 font-mono bg-neutral-900 px-2 py-0.5 rounded border border-amber-400/40 cursor-pointer" onclick="copyCouponCode('BEESTYLE15')">BEESTYLE15</strong> giảm thêm 15% khi thanh toán!
          @endif
        </span>
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
<!-- 5. DANH MỤC TUYỂN CHỌN (1 DÒNG DYNAMIC + NÚT XEM TẤT CẢ) -->
<!-- ========================================================================= -->
<section id="collections" class="w-full py-16 px-6 max-w-7xl mx-auto">
  <!-- Section Header với Nút Xem Tất Cả & Mũi Tên Trượt 1 Dòng -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10 pb-6 border-b border-neutral-200">
    <div>
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-bold block mb-2">BỘ SƯU TẬP ĐỘC BẢN 2026</span>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium">
        Danh Mục Tuyển Chọn
      </h2>
      <p class="text-xs md:text-sm text-neutral-600 font-medium mt-2 max-w-xl">
        Trang phục may đo chuẩn phong cách Ý, chế tác từ lụa tơ tằm nguyên bản và sợi tự nhiên bền vững
      </p>
    </div>

    <!-- Nút Xem Tất Cả & Điều Hướng Trượt -->
    <div class="flex items-center gap-3 shrink-0 flex-wrap">
      <a href="{{ route('client.products.index') }}" 
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-bold uppercase tracking-wider transition-all shadow-md group cursor-pointer">
        <span>Xem tất cả danh mục ({{ $categories->count() }})</span>
        <i class="fa-solid fa-arrow-right text-[10px] text-amber-400 group-hover:translate-x-1 transition-transform"></i>
      </a>

      <!-- Mũi tên trượt 1 dòng (Prev / Next) -->
      <div class="flex items-center gap-1.5 bg-neutral-100 p-1 rounded-2xl border border-neutral-200 shadow-xs">
        <button type="button" 
                onclick="scrollCategories(-1)" 
                class="w-8 h-8 rounded-xl bg-white hover:bg-neutral-950 hover:text-white text-neutral-700 flex items-center justify-center transition-all cursor-pointer shadow-xs" 
                title="Xem trước">
          <i class="fa-solid fa-chevron-left text-xs"></i>
        </button>
        <button type="button" 
                onclick="scrollCategories(1)" 
                class="w-8 h-8 rounded-xl bg-white hover:bg-neutral-950 hover:text-white text-neutral-700 flex items-center justify-center transition-all cursor-pointer shadow-xs" 
                title="Xem tiếp">
          <i class="fa-solid fa-chevron-right text-xs"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- TRACK DANH MỤC HIỂN THỊ TRÊN 1 DÒNG DUY NHẤT (HORIZONTAL SCROLL) -->
  <div class="relative">
    <div id="categoryScrollTrack" class="flex gap-6 overflow-x-auto no-scrollbar scroll-smooth pb-4 pt-1 snap-x snap-mandatory">
      @forelse($categories as $cat)
        @php
          $catImg = $cat->image ?: 'assets/img/products/1.png';
          if (!str_starts_with($catImg, 'http')) {
            $catImg = asset(ltrim($catImg, '/'));
          }
        @endphp
        <a href="{{ route('client.products.index', ['category' => $cat->slug]) }}" 
           class="collection-card group relative w-[280px] sm:w-[320px] md:w-[350px] h-[440px] md:h-[480px] shrink-0 snap-start rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 flex flex-col justify-end p-7">
          <img src="{{ $catImg }}" alt="{{ $cat->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
          <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/95 via-neutral-950/50 to-transparent"></div>
          <div class="relative z-10 text-white">
            <span class="text-[11px] tracking-[0.3em] uppercase text-amber-300 font-bold block mb-1">
              {{ $cat->products_count }} THIẾT KẾ MAY ĐO
            </span>
            <h3 class="font-serif-luxury text-2xl font-bold mb-2 text-white drop-shadow-sm">{{ $cat->name }}</h3>
            <p class="text-xs text-neutral-200 font-normal mb-4 line-clamp-2 drop-shadow-sm">
              {{ $cat->description ?: 'Bộ sưu tập trang phục thiết kế may đo cao cấp chuẩn mực thời trang nam đương đại.' }}
            </p>
            <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-amber-300 group-hover:text-amber-200 transition-colors">
              Khám Phá Bộ Sưu Tập <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1.5 transition-transform"></i>
            </span>
          </div>
        </a>
      @empty
        <div class="w-full text-center py-12 text-neutral-500">
          Đang cập nhật các danh mục sản phẩm...
        </div>
      @endforelse

      <!-- Card Cuối Hàng: Xem Toàn Bộ Sản Phẩm Danh Mục -->
      <a href="{{ route('client.products.index') }}" 
         class="group relative w-[260px] sm:w-[300px] h-[440px] md:h-[480px] shrink-0 snap-start rounded-3xl overflow-hidden shadow-md border-2 border-dashed border-amber-500/40 bg-gradient-to-br from-neutral-900 to-neutral-950 flex flex-col items-center justify-center p-8 text-center hover:border-amber-400 transition-all cursor-pointer">
        <div class="w-16 h-16 rounded-2xl bg-amber-400/20 text-amber-400 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 group-hover:bg-amber-400 group-hover:text-neutral-950 transition-all shadow-md">
          <i class="fa-solid fa-arrow-right"></i>
        </div>
        <h3 class="font-serif-luxury text-xl font-bold text-white mb-2">Xem Toàn Bộ Danh Mục</h3>
        <p class="text-xs text-neutral-400 mb-6">Khám phá hơn {{ $totalActiveProducts ?? '60+' }} mẫu thiết kế may đo hoàn mỹ</p>
        <span class="px-5 py-2.5 rounded-xl bg-amber-400 text-neutral-950 text-xs font-black uppercase tracking-wider group-hover:bg-amber-300 transition-colors shadow-md">
          Khám Phá Ngay
        </span>
      </a>

    </div>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 6. MỤC SẢN PHẨM BÁN CHẠY NHẤT (CÓ RÕ NGÀY BÁN CHẠY THEO YÊU CẦU ĐẶC BIỆT) -->
<!-- ========================================================================= -->
<section class="w-full py-16 px-6 max-w-7xl mx-auto border-t border-neutral-200" id="bestsellers-section">
  
  <!-- Header Phần Bán Chạy Kèm Khoảng Thời Gian Thống Kê Rõ Ràng -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 pb-6 border-b border-neutral-200">
    <div>
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-100 text-rose-800 text-xs font-bold uppercase tracking-wider mb-2.5 border border-rose-200 shadow-xs">
        <i class="fa-solid fa-fire text-rose-600 animate-bounce"></i>
        <span>TOP BÁN CHẠY NHẤT — KỲ {{ $bestSellerShortRange }}</span>
      </div>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium">
        Sản Phẩm Bán Chạy Trong Kỳ
      </h2>
      <p class="text-xs md:text-sm text-neutral-600 font-medium mt-2">
        Tuyển chọn những trang phục may đo được quý khách hàng tin chọn và đặt mua nhiều nhất
      </p>
    </div>

    <!-- Bộ Lọc Nhanh Khoảng Thời Gian Thống Kê (7 ngày / 30 ngày / 60 ngày) -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 bg-neutral-50 p-2.5 rounded-2xl border border-neutral-200 shrink-0">
      <span class="text-xs text-neutral-700 font-bold flex items-center gap-1.5 pl-1">
        <i class="fa-regular fa-clock text-amber-600"></i> Kỳ thống kê:
      </span>
      <div class="inline-flex rounded-xl bg-white p-1 border border-neutral-200 shadow-xs text-xs font-bold">
        <a href="{{ route('client.home', ['best_seller_days' => 7]) }}#bestsellers-section" 
           class="px-3 py-1.5 rounded-lg transition-all {{ ($bestSellerDays ?? 30) == 7 ? 'bg-neutral-950 text-white shadow-xs' : 'text-neutral-600 hover:text-neutral-950' }}">
          7 ngày qua
        </a>
        <a href="{{ route('client.home', ['best_seller_days' => 30]) }}#bestsellers-section" 
           class="px-3 py-1.5 rounded-lg transition-all {{ ($bestSellerDays ?? 30) == 30 ? 'bg-neutral-950 text-white shadow-xs' : 'text-neutral-600 hover:text-neutral-950' }}">
          30 ngày qua (Chuẩn)
        </a>
        <a href="{{ route('client.home', ['best_seller_days' => 60]) }}#bestsellers-section" 
           class="px-3 py-1.5 rounded-lg transition-all {{ ($bestSellerDays ?? 30) == 60 ? 'bg-neutral-950 text-white shadow-xs' : 'text-neutral-600 hover:text-neutral-950' }}">
          60 ngày qua
        </a>
      </div>
    </div>
  </div>

  <!-- BANNER THÔNG BÁO RÕ RÀNG NGÀY BẮT ĐẦU VÀ NGÀY KẾT THÚC BÁN CHẠY -->
  <div class="mb-10 p-4 md:p-5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-rose-500/10 to-amber-500/10 border border-amber-400/40 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm">
    <div class="flex items-center gap-3.5">
      <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-rose-600 text-white flex items-center justify-center font-bold text-xl shadow-md shrink-0">
        <i class="fa-solid fa-calendar-check"></i>
      </div>
      <div>
        <div class="flex items-center gap-2 flex-wrap mb-1">
          <span class="px-2.5 py-0.5 rounded-md bg-neutral-950 text-amber-300 text-[10px] font-black uppercase tracking-wider shadow-xs">
            DỮ LIỆU ĐƠN HÀNG XÁC THỰC
          </span>
          <span class="text-xs font-bold text-neutral-950">
            Chu kỳ thống kê: <span class="text-rose-600 font-extrabold">{{ $bestSellerDays ?? 30 }} ngày gần nhất</span>
          </span>
        </div>
        <p class="text-xs md:text-sm text-neutral-800 font-medium flex items-center gap-2 flex-wrap">
          <span>Khoảng thời gian ghi nhận doanh số:</span>
          <span class="inline-flex items-center gap-1.5 bg-white px-3 py-1 rounded-xl border border-amber-300 text-neutral-950 font-black font-mono shadow-xs">
            <i class="fa-solid fa-calendar-day text-amber-600"></i>
            {{ $bestSellerRangeFormatted }}
          </span>
        </p>
      </div>
    </div>

    <div class="flex items-center gap-3 text-xs text-neutral-600 shrink-0 self-end md:self-center">
      <span class="inline-flex items-center gap-1.5 bg-white/90 px-3 py-1.5 rounded-xl border border-neutral-200 font-semibold text-[11px] shadow-xs">
        <i class="fa-solid fa-circle-check text-emerald-600"></i> Cập nhật tự động theo đơn hoàn tất
      </span>
    </div>
  </div>

  <!-- Lưới 8 Sản Phẩm Bán Chạy Nhất (Kèm nhãn ngày và số lượng bán trong kỳ) -->
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
    @forelse($bestSellers as $item)
      @include('client.partials.product_card', [
        'item' => $item, 
        'badgeType' => 'bestseller', 
        'dateRange' => $bestSellerShortRange
      ])
    @empty
      <div class="col-span-full text-center py-16 text-neutral-500 text-sm font-medium bg-neutral-50 rounded-2xl border border-neutral-200">
        Chưa có số liệu bán chạy trong khoảng thời gian này...
      </div>
    @endforelse
  </div>

  <!-- Bottom link to all products -->
  <div class="text-center mt-10">
    <a href="{{ route('client.products.index', ['sort' => 'sold_desc']) }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md">
      <span>Xem Toàn Bộ Bảng Xếp Hạng Bán Chạy</span>
      <i class="fa-solid fa-arrow-right text-xs text-amber-400"></i>
    </a>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 7. INTERACTIVE CURATED PRODUCT SHOWCASE (TABBED COLLECTIONS DYNAMIC) -->
<!-- ========================================================================= -->
<section class="w-full py-20 px-6 max-w-7xl mx-auto border-t border-neutral-200" id="curated-products-hub">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10 pb-6 border-b border-neutral-200">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-bold uppercase tracking-wider mb-2">
        <i class="fa-solid fa-sparkles text-amber-600"></i> KHÁM PHÁ THEO PHONG CÁCH
      </div>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium">
        Tuyển Tập Sản Phẩm Nam
      </h2>
      <p class="text-xs md:text-sm text-neutral-600 font-medium mt-2">
        Tuyển chọn những phom áo lịch lãm, hiện đại và chuẩn mực dành riêng cho phái mạnh
      </p>
    </div>
    
    <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-neutral-950 hover:text-amber-800 transition-colors pb-1 border-b border-neutral-950 hover:border-amber-800 shrink-0">
      <span>Xem Tất Cả Sản Phẩm</span>
      <i class="fa-solid fa-arrow-right text-xs"></i>
    </a>
  </div>

  <!-- Interactive Filter Tabs Bar (Động theo các Tab chính và Danh mục động từ DB) -->
  <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 no-scrollbar scroll-smooth">
    <button type="button" 
            onclick="switchProductTab('featured', this)" 
            class="product-tab-btn active px-5 py-2.5 rounded-full text-xs font-bold tracking-wider uppercase whitespace-nowrap transition-all shadow-sm bg-neutral-950 text-white cursor-pointer flex items-center gap-2">
      <i class="fa-solid fa-star text-amber-400 text-xs"></i>
      <span>Tuyển Chọn Nổi Bật</span>
    </button>
    
    <button type="button" 
            onclick="switchProductTab('new', this)" 
            class="product-tab-btn px-5 py-2.5 rounded-full text-xs font-bold tracking-wider uppercase whitespace-nowrap transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200 cursor-pointer flex items-center gap-2">
      <i class="fa-solid fa-sparkles text-amber-500 text-xs"></i>
      <span>Hàng Mới Về</span>
    </button>

    <!-- Các Tab Danh Mục Sinh Động Từ Database -->
    @foreach($categories as $cat)
      <button type="button" 
              onclick="switchProductTab('cat-{{ $cat->id }}', this)" 
              class="product-tab-btn px-5 py-2.5 rounded-full text-xs font-bold tracking-wider uppercase whitespace-nowrap transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200 cursor-pointer flex items-center gap-2">
        <span>{{ $cat->name }}</span>
      </button>
    @endforeach
  </div>

  <!-- TAB PANELS -->

  <!-- 1. TAB: TUYỂN CHỌN NỔI BẬT -->
  <div id="tab-panel-featured" class="product-tab-panel transition-opacity duration-300">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
      @forelse($featuredProducts as $item)
        @include('client.partials.product_card', ['item' => $item, 'badgeType' => 'featured'])
      @empty
        <div class="col-span-full text-center py-16 text-neutral-500 text-sm font-medium bg-neutral-50 rounded-2xl border border-neutral-200">
          Đang cập nhật sản phẩm nổi bật...
        </div>
      @endforelse
    </div>
  </div>

  <!-- 2. TAB: HÀNG MỚI VỀ -->
  <div id="tab-panel-new" class="product-tab-panel hidden transition-opacity duration-300">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
      @forelse($newArrivals as $item)
        @include('client.partials.product_card', ['item' => $item, 'badgeType' => 'new'])
      @empty
        <div class="col-span-full text-center py-16 text-neutral-500 text-sm font-medium bg-neutral-50 rounded-2xl border border-neutral-200">
          Đang cập nhật sản phẩm mới về...
        </div>
      @endforelse
    </div>
  </div>

  <!-- 3. CÁC TABS DANH MỤC ĐỘNG -->
  @foreach($categories as $cat)
    @php
      $catItems = $categoryProducts[$cat->id] ?? collect();
    @endphp
    <div id="tab-panel-cat-{{ $cat->id }}" class="product-tab-panel hidden transition-opacity duration-300">
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @forelse($catItems as $item)
          @include('client.partials.product_card', ['item' => $item, 'badgeType' => 'category'])
        @empty
          <div class="col-span-full text-center py-16 text-neutral-500 text-sm font-medium bg-neutral-50 rounded-2xl border border-neutral-200">
            <i class="fa-solid fa-shirt text-2xl text-neutral-400 mb-2 block"></i>
            Đang cập nhật các mẫu thiết kế mới nhất cho danh mục {{ $cat->name }}...
          </div>
        @endforelse
      </div>
    </div>
  @endforeach

  <!-- Section Bottom CTA -->
  <div class="text-center mt-12">
    <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-bold uppercase tracking-[0.2em] rounded-xl transition-all shadow-xl">
      <span>Xem Toàn Bộ {{ $totalActiveProducts ?? '60+' }} Tác Phẩm May Đo</span>
      <i class="fa-solid fa-arrow-right text-xs text-amber-400"></i>
    </a>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 8. EDITORIAL LOOKBOOK SECTION (DYNAMIC TỪ SẢN PHẨM CAO CẤP) -->
<!-- ========================================================================= -->
@if(isset($lookbookProducts) && $lookbookProducts->isNotEmpty())
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

    <!-- Editorial Magazine Layout (Động từ top sản phẩm) -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
      
      @php
        $mainLook = $lookbookProducts->first();
        $subLooks = $lookbookProducts->slice(1, 2);
        $mainImg = $mainLook->primaryImage->image_path ?? ($mainLook->image ?? 'assets/img/products/1.png');
        if (!str_starts_with($mainImg, 'http')) {
          $mainImg = asset(ltrim($mainImg, '/'));
        }
      @endphp

      <!-- Main Look -->
      <div class="md:col-span-7 aspect-[4/5] rounded-2xl overflow-hidden bg-neutral-900 shadow-2xl relative group">
        <img src="{{ $mainImg }}" alt="{{ $mainLook->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
        <div class="absolute bottom-6 left-6 right-6 p-6 bg-neutral-950/85 backdrop-blur-md rounded-2xl border border-neutral-700/60 text-white">
          <span class="text-[10px] tracking-widest uppercase text-amber-300 font-bold block mb-1">
            LOOK 01 — {{ mb_strtoupper($mainLook->category->name ?? 'BEESTYLE ATELIER') }}
          </span>
          <h3 class="font-serif-luxury text-xl font-bold mb-2 text-white line-clamp-1">{{ $mainLook->name }}</h3>
          <div class="flex items-center justify-between gap-4">
            <span class="font-serif-luxury text-base text-amber-300 font-black">
              {{ number_format($mainLook->price, 0, ',', '.') }}₫
            </span>
            <a href="{{ route('client.products.show', $mainLook->id) }}" class="text-xs text-amber-300 hover:text-white inline-flex items-center gap-1.5 font-bold transition-colors">
              <span>Xem tác phẩm chi tiết</span>
              <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Sub Looks -->
      <div class="md:col-span-5 flex flex-col gap-8">
        @foreach($subLooks as $sIdx => $sLook)
          @php
            $sImg = $sLook->primaryImage->image_path ?? ($sLook->image ?? 'assets/img/products/1.png');
            if (!str_starts_with($sImg, 'http')) {
              $sImg = asset(ltrim($sImg, '/'));
            }
          @endphp
          <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-neutral-900 shadow-xl relative group">
            <img src="{{ $sImg }}" alt="{{ $sLook->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
            <div class="absolute bottom-4 left-4 right-4 p-4 bg-neutral-950/85 backdrop-blur-md rounded-xl border border-neutral-700/60 text-white">
              <span class="text-[9px] tracking-widest uppercase text-amber-300 font-bold block">
                LOOK 0{{ $sIdx + 2 }} — {{ mb_strtoupper($sLook->category->name ?? 'SARTORIAL') }}
              </span>
              <div class="flex items-center justify-between gap-2 mt-1">
                <a href="{{ route('client.products.show', $sLook->id) }}" class="font-serif-luxury text-sm font-bold hover:text-amber-300 transition-colors block text-white truncate max-w-[70%]">
                  {{ $sLook->name }}
                </a>
                <span class="text-xs text-amber-300 font-mono font-bold shrink-0">
                  {{ number_format($sLook->price, 0, ',', '.') }}₫
                </span>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>

  </div>
</section>
@endif

<!-- ========================================================================= -->
<!-- 9. BRAND PARTNERS SHOWCASE (DYNAMIC TỪ BẢNG BRANDS) -->
<!-- ========================================================================= -->
@if(isset($brands) && $brands->isNotEmpty())
<section class="w-full bg-white border-b border-neutral-200 py-16 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center max-w-xl mx-auto mb-10">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-bold block mb-2">ĐỐI TÁC THƯƠNG HIỆU</span>
      <h2 class="font-serif-luxury text-2xl sm:text-3xl text-neutral-950 font-medium">
        Các Thương Hiệu Đồng Hành
      </h2>
      <p class="text-xs text-neutral-500 font-medium mt-2">
        Các dòng sản phẩm may đo độc quyền và phong cách thời trang nam chuẩn mực tại BeeStyle Atelier
      </p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 items-stretch max-w-5xl mx-auto">
      @foreach($brands as $brand)
        <a href="{{ route('client.brands.show', $brand->slug) }}" class="group p-6 rounded-2xl bg-neutral-50 hover:bg-white border border-neutral-200 hover:border-amber-400 hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-between text-center">
          <div class="w-20 h-20 rounded-2xl bg-white border border-neutral-200/80 shadow-xs flex items-center justify-center p-2.5 mb-4 group-hover:scale-105 group-hover:border-amber-300 transition-all overflow-hidden shrink-0">
            @if($brand->has_logo)
              <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div class="w-full h-full rounded-xl bg-neutral-900 text-amber-300 items-center justify-center font-bold text-base uppercase" style="display: none;">
                {{ mb_substr($brand->name, 0, 2, 'UTF-8') }}
              </div>
            @else
              <div class="w-full h-full rounded-xl bg-neutral-900 text-amber-300 flex items-center justify-center font-bold text-base uppercase">
                {{ mb_substr($brand->name, 0, 2, 'UTF-8') }}
              </div>
            @endif
          </div>
          
          <div class="w-full">
            <h4 class="text-xs sm:text-sm font-bold text-neutral-950 group-hover:text-amber-800 transition-colors line-clamp-1">
              {{ $brand->name }}
            </h4>
            <span class="text-[11px] text-neutral-500 font-medium block mt-1 line-clamp-1">
              {{ $brand->description ?: 'Dòng thiết kế độc quyền cao cấp' }}
            </span>
          </div>

          <div class="mt-4 pt-3 border-t border-neutral-200/60 w-full flex items-center justify-center gap-1.5 text-[11px] font-semibold text-neutral-600 group-hover:text-amber-800 transition-colors">
            <span>Khám phá thương hiệu</span>
            <i class="fa-solid fa-arrow-right text-[9px] group-hover:translate-x-1 transition-transform"></i>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ========================================================================= -->
<!-- 10. SOCIAL PROOF & CUSTOMER REVIEWS (DYNAMIC TỪ BẢNG REVIEWS) -->
<!-- ========================================================================= -->
<section class="w-full bg-neutral-50 border-b border-neutral-200 py-20 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center max-w-2xl mx-auto mb-14">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-bold block mb-2">ĐÁNH GIÁ TỪ QUÝ KHÁCH HÀNG</span>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium">
        Trải Nghiệm Khách Hàng Thực Tế
      </h2>
      <div class="flex items-center justify-center gap-2 mt-3 text-sm text-neutral-700 font-semibold">
        <span class="text-amber-500 font-bold text-base">★★★★★</span>
        <span>{{ number_format($averageRating ?? 4.9, 1) }} / 5.0 Điểm đánh giá dựa trên {{ number_format($totalApprovedReviews ?? 1250, 0, ',', '.') }}+ đánh giá thực tế</span>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @if(isset($reviews) && $reviews->isNotEmpty())
        @foreach($reviews->take(3) as $rev)
          <div class="bg-white p-7 rounded-2xl border border-neutral-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div>
              <div class="flex items-center justify-between mb-3">
                <div class="text-amber-500 text-sm">
                  @for($s = 1; $s <= 5; $s++)
                    <i class="fa-solid fa-star {{ $s <= ($rev->rating ?? 5) ? 'text-amber-500' : 'text-neutral-300' }} text-xs"></i>
                  @endfor
                </div>
                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-200">
                  <i class="fa-solid fa-circle-check text-emerald-600"></i> Đã mua hàng
                </span>
              </div>
              <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed italic mb-4 line-clamp-3">
                "{{ $rev->comment }}"
              </p>
            </div>
            <div class="pt-4 border-t border-neutral-100 flex items-center gap-3">
              <img src="{{ $rev->user_avatar_url }}" alt="{{ $rev->user_name ?: ($rev->user?->name ?? 'Khách Hàng') }}" class="w-10 h-10 rounded-full object-cover border border-amber-300 shadow-xs" onerror="this.src='https://ui-avatars.com/api/?name=KH&background=f59e0b&color=111827&bold=true'">
              <div class="min-w-0">
                <strong class="text-xs text-neutral-950 block truncate">{{ $rev->user_name ?: ($rev->user?->name ?? 'Khách Hàng Thân Thiết') }}</strong>
                <a href="{{ route('client.products.show', $rev->product_id) }}" class="text-[11px] text-neutral-500 hover:text-amber-700 font-medium block truncate">
                  {{ $rev->product->name ?? 'Sản phẩm Atelier' }}
                </a>
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="bg-white p-7 rounded-2xl border border-neutral-200 shadow-sm flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-3">
              <div class="text-amber-500 text-sm">★★★★★</div>
              <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-200">
                <i class="fa-solid fa-circle-check text-emerald-600"></i> Đã mua hàng
              </span>
            </div>
            <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed italic mb-4">
              "Chất vải lụa tơ tằm cực kỳ thoáng mát và sang trọng. Tôi mặc dự tiệc cưới ai cũng khen phom áo đứng dáng và chuẩn mực. Đóng gói rất chu đáo như một hộp quà cao cấp!"
            </p>
          </div>
          <div class="pt-4 border-t border-neutral-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-neutral-900 text-amber-300 flex items-center justify-center font-bold text-sm shadow-xs">MH</div>
            <div>
              <strong class="text-xs text-neutral-950 block">Nguyễn Minh Hoàng</strong>
              <span class="text-[11px] text-neutral-500 font-medium">Sơ Mi Lụa Atelier • TP. Hồ Chí Minh</span>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 11. BRAND STORY SECTION (DYNAMIC METRICS) -->
<!-- ========================================================================= -->
<section id="about" class="w-full bg-white py-24 px-6">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
    
    <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl bg-neutral-100 border border-neutral-200">
      <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1200&auto=format&fit=crop" alt="Beestyle Atelier Studio" class="w-full h-full object-cover" loading="lazy">
    </div>

    <div class="lg:pl-6">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-bold block mb-2">TRIẾT LÝ THIẾT KẾ</span>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-950 font-medium mb-6 leading-tight">
        BeeStyle: Nét Đẹp May Đo Tối Giản &amp; Chuẩn Mực
      </h2>
      <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed mb-4">
        Thành lập tại Việt Nam, BeeStyle ra đời với ước vọng kiến tạo nên những tác phẩm may mặc vượt qua vòng xoáy của thời trang nhanh. Chúng tôi tập trung vào 3 giá trị cốt lõi: <strong>Chất liệu tự nhiên cao cấp</strong>, <strong>Kỹ thuật may đo thủ công</strong> và <strong>Phom dáng tối giản bền vững</strong>.
      </p>
      <p class="text-xs md:text-sm text-neutral-700 font-normal leading-relaxed mb-8">
        Mỗi chiếc áo sơ mi lụa, blazer hay polo đều được người nghệ nhân dồn trọn tâm huyết, mang đến cho bạn trải nghiệm mặc êm ái, thanh lịch và tự tin trong mọi khoảnh khắc.
      </p>

      <div class="grid grid-cols-3 gap-4 sm:gap-6 pt-6 border-t border-neutral-200 text-center">
        <div class="p-3 bg-neutral-50 rounded-2xl border border-neutral-200">
          <span class="font-serif-luxury text-2xl sm:text-4xl text-neutral-950 font-black block text-amber-600">100%</span>
          <span class="text-[10px] sm:text-[11px] tracking-wider uppercase text-neutral-700 font-bold mt-1 block">Chất liệu tự nhiên</span>
        </div>
        <div class="p-3 bg-neutral-50 rounded-2xl border border-neutral-200">
          <span class="font-serif-luxury text-2xl sm:text-4xl text-neutral-950 font-black block text-amber-600">{{ $totalActiveProducts ?? '60+' }}+</span>
          <span class="text-[10px] sm:text-[11px] tracking-wider uppercase text-neutral-700 font-bold mt-1 block">Thiết kế may đo</span>
        </div>
        <div class="p-3 bg-neutral-50 rounded-2xl border border-neutral-200">
          <span class="font-serif-luxury text-2xl sm:text-4xl text-neutral-950 font-black block text-amber-600">30 Ngày</span>
          <span class="text-[10px] sm:text-[11px] tracking-wider uppercase text-neutral-700 font-bold mt-1 block">Đổi trả tại nhà</span>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ========================================================================= -->
<!-- 12. VIP MEMBER NEWSLETTER (DYNAMIC COUPON PROMO) -->
<!-- ========================================================================= -->
<section class="w-full bg-neutral-950 text-white py-20 px-6 text-center">
  <div class="max-w-2xl mx-auto">
    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/20 text-amber-300 text-xs font-bold uppercase tracking-wider mb-4 border border-amber-400/40">
      <i class="fa-solid fa-gem text-amber-400"></i> BEESTYLE PRIVÉ
    </div>
    <h2 class="font-serif-luxury text-3xl md:text-4xl font-normal mb-4 text-white">Đăng Ký Nhận Đặc Quyền Thành Viên</h2>
    <p class="text-xs md:text-sm text-neutral-300 font-normal leading-relaxed mb-8 max-w-xl mx-auto">
      @if(isset($bestCoupon) && $bestCoupon)
        Nhận ngay mã ưu đãi <strong class="text-amber-400 font-mono">{{ $bestCoupon->code }}</strong> (giảm {{ $bestCoupon->discount_type === 'percent' ? ($bestCoupon->discount_value . '%') : (number_format($bestCoupon->discount_value, 0, ',', '.') . '₫') }} cho đơn hàng tiếp theo) cùng thông báo sớm nhất về các đợt phát hành sản phẩm giới hạn.
      @else
        Nhận ngay mã ưu đãi <strong class="text-amber-400 font-mono">BEESTYLE15</strong> (giảm 15% cho đơn hàng đầu tiên) cùng thông báo sớm nhất về các đợt phát hành sản phẩm giới hạn.
      @endif
    </p>

    <form onsubmit="handleNewsletter(event)" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
      <input type="email" id="newsletter-email" placeholder="Nhập địa chỉ email của bạn..." required class="bg-neutral-900 border border-neutral-700 rounded-xl px-4 py-3.5 text-xs text-white placeholder-neutral-400 focus:outline-none focus:border-amber-400 flex-grow shadow-inner">
      <button type="submit" class="px-7 py-3.5 bg-amber-400 text-neutral-950 text-xs tracking-widest uppercase font-bold hover:bg-amber-300 transition-colors rounded-xl shadow-lg shrink-0 cursor-pointer">
        Đăng Ký Ngay
      </button>
    </form>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // Chuyển đổi qua lại giữa các tab bộ sưu tập trên trang chủ
  function switchProductTab(tabKey, btnEl) {
    // Cập nhật giao diện nút tab
    document.querySelectorAll('.product-tab-btn').forEach(btn => {
      btn.classList.remove('active', 'bg-neutral-950', 'text-white', 'shadow-sm');
      btn.classList.add('bg-neutral-100', 'text-neutral-700');
    });
    if (btnEl) {
      btnEl.classList.add('active', 'bg-neutral-950', 'text-white', 'shadow-sm');
      btnEl.classList.remove('bg-neutral-100', 'text-neutral-700');
    }

    // Hiển thị panel tương ứng
    document.querySelectorAll('.product-tab-panel').forEach(panel => {
      panel.classList.add('hidden');
    });
    const targetPanel = document.getElementById('tab-panel-' + tabKey);
    if (targetPanel) {
      targetPanel.classList.remove('hidden');
    }

    // Re-init Lucide icons nếu có
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
      window.lucide.createIcons();
    }
  }

  // =========================================================================
  // ĐIỀU HƯỚNG TRƯỢT NGANG 1 DÒNG (VOUCHERS & DANH MỤC)
  // =========================================================================
  function scrollVouchers(direction) {
    const track = document.getElementById('voucherScrollTrack');
    if (!track) return;
    const scrollAmount = 370; // 1 voucher card width + gap
    track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
  }

  function openAllVouchersModal() {
    const modal = document.getElementById('allVouchersModal');
    if (modal) {
      modal.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
      // Đồng bộ trạng thái các mã đã lấy sang modal
      const claimedList = getClaimedCouponsList();
      claimedList.forEach(code => {
        markCouponAsClaimedUI(code);
      });
    }
  }

  function closeAllVouchersModal() {
    const modal = document.getElementById('allVouchersModal');
    if (modal) {
      modal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeAllVouchersModal();
    }
  });

  function scrollCategories(direction) {
    const track = document.getElementById('categoryScrollTrack');
    if (!track) return;
    const scrollAmount = 360; // 1 category card width + gap
    track.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
  }

  // =========================================================================
  // QUẢN LÝ KHO VOUCHER: RÀNG BUỘC MỖI MÃ CHỈ COPY / LẤY 1 LẦN DUY NHẤT
  // =========================================================================
  const CLAIMED_COUPONS_STORAGE_KEY = 'beestyle_claimed_vouchers_v2';

  function getClaimedCouponsList() {
    try {
      const stored = localStorage.getItem(CLAIMED_COUPONS_STORAGE_KEY);
      return stored ? JSON.parse(stored) : [];
    } catch (e) {
      return [];
    }
  }

  function isCouponClaimedAlready(code) {
    if (!code) return false;
    return getClaimedCouponsList().includes(code.trim().toUpperCase());
  }

  function updateClaimedCountUI() {
    const list = getClaimedCouponsList();
    const countEl = document.getElementById('claimedCountDisplay');
    if (countEl) {
      countEl.textContent = list.length;
    }
  }

  function markCouponAsClaimedUI(code, passedBtnEl) {
    if (!code) return;
    const cleanCode = code.trim().toUpperCase();

    // 1. Cập nhật nút tại hàng trượt voucher trang chủ
    const homeBtn = document.getElementById('btn-coupon-' + cleanCode) || (passedBtnEl && passedBtnEl.id === 'btn-coupon-' + cleanCode ? passedBtnEl : null);
    if (homeBtn) {
      homeBtn.setAttribute('disabled', 'disabled');
      homeBtn.setAttribute('title', 'Bạn đã lấy mã này rồi. Mỗi mã chỉ được lấy 1 lần.');
      homeBtn.classList.remove('bg-amber-400', 'hover:bg-amber-300', 'text-neutral-950', 'active:scale-95', 'cursor-pointer');
      homeBtn.classList.add('bg-neutral-800', 'text-emerald-400', 'border', 'border-emerald-500/40', 'cursor-not-allowed', 'pointer-events-none', 'opacity-90');
      homeBtn.innerHTML = '<i class="fa-solid fa-circle-check text-[11px] text-emerald-400"></i> <span>Đã Lấy</span>';
    }

    // 2. Cập nhật nút trong modal xem toàn bộ voucher
    const modalBtn = document.getElementById('btn-modal-coupon-' + cleanCode) || (passedBtnEl && passedBtnEl.id === 'btn-modal-coupon-' + cleanCode ? passedBtnEl : null);
    if (modalBtn) {
      modalBtn.setAttribute('disabled', 'disabled');
      modalBtn.setAttribute('title', 'Bạn đã lấy mã này rồi. Mỗi mã chỉ được lấy 1 lần.');
      modalBtn.classList.remove('bg-amber-400', 'hover:bg-amber-300', 'text-neutral-950', 'active:scale-95', 'cursor-pointer');
      modalBtn.classList.add('bg-neutral-800', 'text-emerald-400', 'border', 'border-emerald-500/40', 'cursor-not-allowed', 'pointer-events-none', 'opacity-90');
      modalBtn.innerHTML = '<i class="fa-solid fa-circle-check text-[11px] text-emerald-400"></i> <span>Đã Lấy</span>';
    }

    // 3. Hiển thị huy hiệu Đã Lưu trên thẻ voucher
    const card = document.getElementById('coupon-card-' + cleanCode);
    if (card) {
      const badge = card.querySelector('.claimed-badge');
      if (badge) badge.classList.remove('hidden');
      card.classList.add('border-emerald-500/40');
    }

    updateClaimedCountUI();
  }

  // Hàm sao chép mã ưu đãi — CHỈ THỰC HIỆN ĐƯỢC 1 LẦN DUY NHẤT
  function copyCouponCode(code, btnEl) {
    if (!code) return;
    const cleanCode = code.trim().toUpperCase();

    // 1. Kiểm tra nếu mã đã từng được lấy: Chặn ngay lập tức
    if (isCouponClaimedAlready(cleanCode)) {
      markCouponAsClaimedUI(cleanCode, btnEl);
      if (typeof showGlobalToast === 'function') {
        showGlobalToast(`Bạn đã lấy mã ${cleanCode} rồi! Mỗi khách hàng chỉ được lấy 1 lần duy nhất.`, 'warning');
      } else {
        alert(`Bạn đã lấy mã ${cleanCode} rồi! Mỗi khách hàng chỉ được lấy 1 lần duy nhất.`);
      }
      return;
    }

    // 2. Thực hiện sao chép mã lần đầu tiên
    const performCopy = (navigator.clipboard && navigator.clipboard.writeText) 
      ? navigator.clipboard.writeText(cleanCode) 
      : new Promise((resolve, reject) => {
          try {
            const ta = document.createElement('textarea');
            ta.value = cleanCode;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.focus();
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            resolve();
          } catch(err) {
            reject(err);
          }
        });

    performCopy.then(() => {
      // Ghi nhận mã vào danh sách đã lấy để ngăn chặn lần bấm sau
      const list = getClaimedCouponsList();
      if (!list.includes(cleanCode)) {
        list.push(cleanCode);
        try {
          localStorage.setItem(CLAIMED_COUPONS_STORAGE_KEY, JSON.stringify(list));
        } catch(e) {}
      }

      // Khóa nút vĩnh viễn và đổi trạng thái cả trên trang chủ và modal
      markCouponAsClaimedUI(cleanCode, btnEl);

      if (typeof showGlobalToast === 'function') {
        showGlobalToast(`🎉 Đã lưu mã ${cleanCode} vào ví ưu đãi! Mã đã được sao chép, bạn có thể dán tại bước thanh toán.`, 'coupon');
      } else {
        alert(`Đã lưu mã ${cleanCode} thành công! Áp dụng tại bước thanh toán.`);
      }
    }).catch(() => {
      prompt('Mã ưu đãi của bạn (mỗi mã chỉ được lấy 1 lần):', cleanCode);
      const list = getClaimedCouponsList();
      if (!list.includes(cleanCode)) {
        list.push(cleanCode);
        try {
          localStorage.setItem(CLAIMED_COUPONS_STORAGE_KEY, JSON.stringify(list));
        } catch(e) {}
      }
      markCouponAsClaimedUI(cleanCode, btnEl);
    });
  }

  // Khôi phục trạng thái tất cả các mã đã nhận trước đó khi tải trang
  document.addEventListener('DOMContentLoaded', function() {
    const claimedList = getClaimedCouponsList();
    claimedList.forEach(code => {
      markCouponAsClaimedUI(code);
    });
    updateClaimedCountUI();
  });

  function handleNewsletter(e) {
    e.preventDefault();
    const email = document.getElementById('newsletter-email').value;
    const code = @json($bestCoupon->code ?? 'BEESTYLE15');
    if (typeof showGlobalToast === 'function') {
      showGlobalToast(`Cảm ơn bạn (${email}) đã đăng ký! Mã ${code} đã sẵn sàng sử dụng.`, 'success');
    } else {
      alert(`Cảm ơn bạn (${email}) đã đăng ký nhận tin BeeStyle Privé! Mã ${code} đã sẵn sàng sử dụng.`);
    }
    document.getElementById('newsletter-email').value = '';
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