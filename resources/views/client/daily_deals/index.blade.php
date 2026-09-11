@extends('layouts.client')

@section('title', 'Ưu Đãi Trong Ngày — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Ưu Đãi Trong Ngày (Flash Sale)</span>
  </nav>

  <!-- Hero Flash Sale Banner -->
  <div class="bg-neutral-950 text-white rounded-2xl p-8 md:p-12 mb-12 relative overflow-hidden shadow-2xl">
    <div class="max-w-2xl relative z-10">
      <span class="px-3 py-1 bg-amber-400 text-neutral-950 text-[10px] tracking-widest uppercase font-bold rounded inline-block mb-3">
        ĐẶC QUYỀN GIỜ VÀNG
      </span>
      <h1 class="font-serif-luxury text-3xl sm:text-5xl font-light mb-3 leading-tight">
        Siêu Ưu Đãi Trong Ngày
      </h1>
      <p class="text-xs text-neutral-400 font-light leading-relaxed mb-6">
        Cơ hội sở hữu các thiết kế sơ mi, blazer và polo độc quyền từ Atelier Beestyle với mức chiết khấu lên đến <strong class="text-amber-400">{{ $maxDiscount ?? 30 }}%</strong>.
      </p>

      <div class="flex flex-wrap gap-4 text-xs text-neutral-300">
        <div class="flex items-center gap-2 bg-neutral-900 px-3.5 py-2 rounded-lg border border-neutral-800">
          <i data-lucide="tag" class="w-4 h-4 text-amber-400"></i>
          <span>Giảm đến {{ $maxDiscount ?? 30 }}%</span>
        </div>
        <div class="flex items-center gap-2 bg-neutral-900 px-3.5 py-2 rounded-lg border border-neutral-800">
          <i data-lucide="truck" class="w-4 h-4 text-amber-400"></i>
          <span>Freeship từ 500K</span>
        </div>
        <div class="flex items-center gap-2 bg-neutral-900 px-3.5 py-2 rounded-lg border border-neutral-800">
          <i data-lucide="rotate-ccw" class="w-4 h-4 text-amber-400"></i>
          <span>Đổi trả 30 ngày</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Deals Grid -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
    @forelse($deals as $deal)
      @php
        $p = $deal->product;
        $img = $p->primaryImage->image_path ?? $p->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=600&auto=format&fit=crop';
        if (!str_starts_with($img, 'http')) {
          $img = asset($img);
        }
      @endphp
      <div class="group flex flex-col bg-white rounded-2xl border border-neutral-200/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
        <a href="{{ route('client.products.show', $p->id) }}" class="relative aspect-[3/4] bg-neutral-100 overflow-hidden block">
          <img src="{{ $img }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          <span class="absolute top-3 left-3 px-2 py-0.5 bg-rose-600 text-white text-[10px] font-bold rounded shadow">
            -{{ $deal->discount_percent }}%
          </span>
        </a>

        <div class="p-4 flex flex-col justify-between flex-grow">
          <div>
            <span class="text-[10px] tracking-widest uppercase text-neutral-400 font-semibold block mb-1">{{ $p->category->name ?? 'Beestyle' }}</span>
            <a href="{{ route('client.products.show', $p->id) }}" class="font-serif-luxury text-base font-semibold text-neutral-900 hover:text-amber-800 transition-colors line-clamp-1">
              {{ $p->name }}
            </a>
          </div>

          <div class="mt-3 pt-3 border-t border-neutral-100 flex flex-col gap-2">
            <div class="flex items-baseline justify-between">
              <span class="font-serif-luxury text-base font-bold text-rose-700 block">
                {{ number_format($deal->deal_price, 0, ',', '.') }}₫
              </span>
              <span class="text-[11px] text-neutral-400 line-through">
                {{ number_format($p->price, 0, ',', '.') }}₫
              </span>
            </div>
            <div class="grid grid-cols-2 gap-1.5">
              <button type="button" 
                      onclick="openQuickVariantModal({{ $p->id }}, false, this)" 
                      class="w-full py-2 bg-neutral-950 hover:bg-neutral-800 text-white text-[11px] font-bold uppercase rounded-lg flex items-center justify-center gap-1 shadow-xs transition-all active:scale-95 cursor-pointer" 
                      title="Thêm vào giỏ hàng">
                <i data-lucide="shopping-bag" class="w-3 h-3"></i>
                <span class="truncate">Thêm Giỏ</span>
              </button>
              <button type="button" 
                      onclick="openQuickVariantModal({{ $p->id }}, true, this)" 
                      class="w-full py-2 bg-amber-400 hover:bg-amber-500 text-neutral-950 text-[11px] font-black uppercase rounded-lg flex items-center justify-center gap-1 shadow-xs transition-all active:scale-95 cursor-pointer" 
                      title="Mua ngay — Thanh toán tức thì">
                <i data-lucide="zap" class="w-3 h-3"></i>
                <span class="truncate">Mua Ngay</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-neutral-200">
        <i data-lucide="clock" class="w-12 h-12 mx-auto text-neutral-400 mb-3 stroke-1"></i>
        <h3 class="font-serif-luxury text-xl font-bold text-neutral-800 mb-1">Chưa có khung giờ Flash Sale tiếp theo</h3>
        <p class="text-xs text-neutral-500">Các chương trình ưu đãi mới sẽ sớm được cập nhật trên trang chủ Beestyle Studio.</p>
      </div>
    @endforelse
  </div>

</main>
@endsection