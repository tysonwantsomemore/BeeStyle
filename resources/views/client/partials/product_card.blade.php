@php
  $isSaleActive = $item->is_sale_active;
  $minPrice = ($item->variants && $item->variants->isNotEmpty()) 
    ? $item->variants->min(fn($v) => $v->effective_price) 
    : ($item->effective_price ?? ($item->price ?? 0));
  $primaryImg = $item->primaryImage->image_path ?? ($item->image ?? ($item->thumbnail ?? 'assets/img/products/1.png'));
  if (!str_starts_with($primaryImg, 'http')) {
    $primaryImg = asset(ltrim($primaryImg, '/'));
  }
  $isFav = in_array($item->id, $wishlistIds ?? []);
  $hasDiscount = $isSaleActive && ($item->original_price && $item->original_price > $minPrice);
  $discPct = $hasDiscount ? round((($item->original_price - $minPrice) / $item->original_price) * 100) : 0;
  $pRating = round((float)($item->rating ?: 5.0), 1);
  $pSold = (int)($item->sold_count ?: 0);
  $fullStars = floor($pRating);
  $isBestSellerTab = ($badgeType ?? '') === 'bestseller';
  $cardDateRange = $item->period_short_range ?? ($dateRange ?? null);
@endphp

<div class="group flex flex-col bg-white rounded-2xl border border-neutral-200 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300">
  <!-- Thumbnail Container -->
  <div class="aspect-[3/4] relative bg-neutral-100 overflow-hidden block">
    <a href="{{ route('client.products.show', $item->id) }}" class="block w-full h-full">
      <img src="{{ $primaryImg }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
    </a>

    <!-- Wishlist Heart Button -->
    <button type="button" 
            onclick="toggleWishlist({{ $item->id }}, this)" 
            class="btn-wishlist-{{ $item->id }} absolute top-2.5 right-2.5 z-10 w-8 h-8 rounded-full bg-white/90 hover:bg-white backdrop-blur-md flex items-center justify-center text-neutral-600 hover:text-rose-600 shadow-sm transition-all active:scale-90 cursor-pointer {{ $isFav ? 'text-rose-600' : '' }}" 
            title="Thêm vào yêu thích">
      <i class="fa-solid fa-heart text-xs {{ $isFav ? 'text-rose-500' : 'text-neutral-400' }}"></i>
    </button>

    <!-- Top Status Badges -->
    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 pointer-events-none max-w-[85%]">
      @if($isBestSellerTab)
        <span class="px-2 py-0.5 bg-rose-600 text-white text-[9px] tracking-wider uppercase font-black rounded shadow flex items-center gap-1">
          <i class="fa-solid fa-fire text-amber-300"></i> BÁN CHẠY ({{ $cardDateRange ?? '30 ngày qua' }})
        </span>
      @endif

      @if($discPct > 0)
        <span class="px-2 py-0.5 bg-rose-600 text-white text-[10px] tracking-widest uppercase font-black rounded shadow">
          -{{ $discPct }}%
        </span>
      @elseif($item->is_new)
        <span class="px-2 py-0.5 bg-neutral-950 text-white text-[10px] tracking-widest uppercase font-bold rounded shadow">
          MỚI
        </span>
      @elseif($item->is_featured && !$isBestSellerTab)
        <span class="px-2 py-0.5 bg-amber-400 text-neutral-950 text-[10px] tracking-widest uppercase font-black rounded shadow">
          HOT
        </span>
      @endif
    </div>

    <!-- Quick View hover overlay -->
    <div class="absolute inset-0 bg-neutral-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-3 pointer-events-none">
      <span class="w-full py-2 bg-white/95 text-neutral-950 text-xs font-bold tracking-wider uppercase rounded-xl shadow-lg text-center backdrop-blur-xs">
        Xem Chi Tiết
      </span>
    </div>
  </div>

  <!-- Card Content Body -->
  <div class="p-4 md:p-5 flex flex-col flex-grow justify-between">
    <div>
      <span class="text-[10px] tracking-widest uppercase text-amber-700 font-bold block mb-1">
        {{ $item->category->name ?? 'Beestyle Studio' }}
      </span>
      <a href="{{ route('client.products.show', $item->id) }}" class="font-serif-luxury text-sm md:text-base font-bold text-neutral-950 hover:text-amber-800 transition-colors line-clamp-2 leading-snug">
        {{ $item->name }}
      </a>
      
      <!-- Thống kê đánh giá & lượt bán -->
      <div class="mt-2">
        @if($isBestSellerTab && isset($item->period_sold) && $item->period_sold > 0)
          <!-- Hiển thị nổi bật số lượng bán trong khoảng thời gian đã xác định -->
          <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-rose-50 border border-rose-200 text-rose-700 text-[10px] font-bold">
            <i class="fa-solid fa-fire text-rose-500"></i>
            <span>Đã bán <strong>{{ $item->period_sold }}</strong> sp ({{ $cardDateRange ?? '30 ngày' }})</span>
          </div>
        @else
          <div class="flex items-center gap-1.5 text-xs text-amber-500 font-semibold">
            <div class="flex items-center gap-0.5 text-amber-400">
              @for($st = 1; $st <= 5; $st++)
                <i class="fa-solid fa-star text-[10px] {{ $st <= $fullStars ? 'text-amber-400' : 'text-neutral-300' }}"></i>
              @endfor
            </div>
            <span class="text-[11px] text-neutral-600 font-medium">
              {{ number_format($pRating, 1) }} ({{ $pSold > 0 ? (number_format($pSold, 0, ',', '.') . ' đã bán') : 'Mới' }})
            </span>
          </div>
        @endif
      </div>
    </div>

    <!-- Price & Actions -->
    <div class="mt-4 pt-3 border-t border-neutral-100 flex flex-col gap-3">
      <div class="flex items-baseline justify-between gap-1 flex-wrap">
        <span class="font-serif-luxury text-base md:text-lg font-black text-neutral-950">
          {{ number_format($minPrice, 0, ',', '.') }}₫
        </span>
        @if($hasDiscount)
          <span class="text-xs text-neutral-400 line-through font-medium">
            {{ number_format($item->original_price, 0, ',', '.') }}₫
          </span>
        @endif
      </div>

      <!-- Action Buttons -->
      <div class="grid grid-cols-2 gap-2">
        <button type="button" 
                onclick="openQuickVariantModal({{ $item->id }}, false, this)" 
                class="w-full py-2 px-1 bg-neutral-950 hover:bg-neutral-800 text-white text-[11px] font-bold uppercase rounded-xl flex items-center justify-center gap-1 shadow-sm transition-all active:scale-95 cursor-pointer" 
                title="Thêm vào giỏ hàng">
          <i class="fa-solid fa-cart-plus text-[10px]"></i>
          <span class="truncate">Thêm Giỏ</span>
        </button>
        <button type="button" 
                onclick="openQuickVariantModal({{ $item->id }}, true, this)" 
                class="w-full py-2 px-1 bg-amber-400 hover:bg-amber-500 text-neutral-950 text-[11px] font-black uppercase rounded-xl flex items-center justify-center gap-1 shadow-sm transition-all active:scale-95 cursor-pointer" 
                title="Mua ngay — Thanh toán tức thì">
          <i class="fa-solid fa-bolt text-[10px]"></i>
          <span class="truncate">Mua Ngay</span>
        </button>
      </div>
    </div>
  </div>

</div>
