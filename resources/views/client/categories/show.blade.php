@extends('layouts.client')

@section('title', ($category->name ?? 'Danh Mục') . ' — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <a href="{{ route('client.categories.index') }}" class="hover:text-black">Danh Mục</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">{{ $category->name }}</span>
  </nav>

  <!-- Category Banner -->
  <div class="bg-neutral-950 text-white rounded-2xl p-8 md:p-12 mb-12 relative overflow-hidden shadow-2xl">
    <div class="max-w-2xl relative z-10">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-400 font-semibold block mb-2">BỘ SƯU TẬP THEO DÒNG</span>
      <h1 class="font-serif-luxury text-3xl sm:text-5xl font-light mb-3">{{ $category->name }}</h1>
      <p class="text-xs text-neutral-400 font-light leading-relaxed">
        {{ $category->description ?? 'Tuyển tập các tác phẩm may đo cao cấp tại xưởng may BeeStyle.' }}
      </p>
    </div>
  </div>

  <!-- Products Grid -->
  <div class="mb-8 flex justify-between items-center pb-4 border-b border-neutral-200">
    <h2 class="font-serif-luxury text-2xl font-bold text-neutral-900">Danh Sách Sản Phẩm</h2>
    <span class="text-xs text-neutral-500">{{ $products->total() ?? $products->count() }} sản phẩm</span>
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
          <div class="mt-3 pt-3 border-t border-neutral-100 flex items-center justify-between">
            <span class="font-serif-luxury text-base font-bold text-neutral-950">
              {{ number_format($p->price, 0, ',', '.') }}₫
            </span>
            <a href="{{ route('client.products.show', $p->id) }}" class="px-3 py-1.5 bg-neutral-950 text-white text-xs tracking-wider uppercase font-semibold rounded hover:bg-amber-400 hover:text-black transition-colors">
              Xem
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-neutral-200">
        <p class="text-xs text-neutral-500">Chưa có sản phẩm nào trong danh mục này.</p>
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
  @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
    <div class="mt-12 flex justify-center">
      {{ $products->links() }}
    </div>
  @endif

</main>
@endsection
