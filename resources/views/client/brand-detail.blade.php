@extends('layouts.client')

@section('title', ($brand->name ?? 'Thương Hiệu') . ' — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <a href="{{ route('client.brands.index') }}" class="hover:text-black">Thương Hiệu</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">{{ $brand->name }}</span>
  </nav>

  <!-- Brand Detail Header -->
  <div class="bg-white rounded-2xl border border-neutral-200 p-8 md:p-10 mb-12 shadow-sm flex flex-col md:flex-row items-center gap-8">
    <div class="w-24 h-24 rounded-2xl bg-neutral-50 border border-neutral-200 flex items-center justify-center p-3 shrink-0 shadow-sm">
      @if(!empty($brand->logo))
        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="max-h-full max-w-full object-contain">
      @else
        <i data-lucide="crown" class="w-12 h-12 text-amber-600"></i>
      @endif
    </div>
    <div class="text-center md:text-left">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-semibold block mb-1">ATELIER PARTNER</span>
      <h1 class="font-serif-luxury text-3xl md:text-4xl font-bold text-neutral-900 mb-2">{{ $brand->name }}</h1>
      <p class="text-xs text-neutral-600 font-light leading-relaxed max-w-2xl">
        {{ $brand->description ?? 'Thương hiệu thời trang nam may đo cao cấp với chất liệu tự nhiên tuyển chọn.' }}
      </p>
    </div>
  </div>

  <!-- Products from this Brand -->
  <div class="mb-8 flex justify-between items-center pb-4 border-b border-neutral-200">
    <h2 class="font-serif-luxury text-2xl font-bold text-neutral-900">Các Tác Phẩm Thuộc {{ $brand->name }}</h2>
    <span class="text-xs text-neutral-500">{{ $products->count() }} sản phẩm</span>
  </div>

  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
    @forelse($products as $p)
      @php
        $primaryImg = $p->primaryImage->image_path ?? $p->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=600&auto=format&fit=crop';
        if (!str_starts_with($primaryImg, 'http')) {
          $primaryImg = asset($primaryImg);
        }
      @endphp
      <div class="group flex flex-col bg-white rounded-2xl border border-neutral-200/80 overflow-hidden shadow-sm hover:shadow-xl transition-all">
        <a href="{{ route('client.products.show', $p->id) }}" class="aspect-[3/4] bg-neutral-100 overflow-hidden block">
          <img src="{{ $primaryImg }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
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
              <span class="font-serif-luxury text-base font-bold text-neutral-950">
                {{ number_format($p->price, 0, ',', '.') }}₫
              </span>
              @if($p->original_price && $p->original_price > $p->price)
                <span class="text-xs text-neutral-400 line-through font-medium">
                  {{ number_format($p->original_price, 0, ',', '.') }}₫
                </span>
              @endif
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
        <p class="text-xs text-neutral-500">Chưa có sản phẩm nào thuộc thương hiệu này.</p>
      </div>
    @endforelse
  </div>

</main>
@endsection
