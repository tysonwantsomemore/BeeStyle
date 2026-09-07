@extends('layouts.client')

@section('title', 'Thương Hiệu & Đối Tác May Đo — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Thương Hiệu May Đo</span>
  </nav>

  <!-- Brand Banner -->
  <div class="bg-neutral-950 text-white rounded-2xl p-8 md:p-12 mb-12 relative overflow-hidden shadow-2xl">
    <div class="max-w-2xl relative z-10">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-400 font-semibold block mb-2">BỘ SƯU TẬP THƯƠNG HIỆU</span>
      <h1 class="font-serif-luxury text-3xl sm:text-5xl font-light mb-4">Các Nhà May Đối Tác</h1>
      <p class="text-xs text-neutral-400 font-light leading-relaxed">
        Tổng hợp các thương hiệu thời trang nam, xưởng may đo lụa tơ tằm và đồ da cao cấp đồng hành cùng Beestyle Atelier.
      </p>
    </div>
  </div>

  <!-- Brands Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($brands as $brand)
      <div class="bg-white rounded-2xl border border-neutral-200/90 p-6 flex flex-col justify-between shadow-sm hover:shadow-xl transition-all duration-300">
        <div>
          <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 rounded-xl bg-neutral-50 border border-neutral-200 flex items-center justify-center p-2 shrink-0">
              @if(!empty($brand->logo))
                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="max-h-full max-w-full object-contain">
              @else
                <i data-lucide="crown" class="w-8 h-8 text-amber-600"></i>
              @endif
            </div>
            <div>
              <span class="text-[10px] tracking-widest uppercase text-neutral-400 font-semibold block">ATELIER BRAND</span>
              <h3 class="font-serif-luxury text-xl font-bold text-neutral-900">{{ $brand->name }}</h3>
              <span class="text-[11px] text-emerald-700 font-medium">Chính hãng 100%</span>
            </div>
          </div>
          <p class="text-xs text-neutral-600 leading-relaxed font-light mb-6 line-clamp-3">
            {{ $brand->description ?? 'Thương hiệu thời trang nam may đo cao cấp với chất liệu tự nhiên tuyển chọn.' }}
          </p>
        </div>

        <div class="pt-4 border-t border-neutral-100 flex items-center justify-between">
          <span class="text-xs text-neutral-500">{{ $brand->products_count ?? $brand->products->count() }} tác phẩm</span>
          <a href="{{ route('client.products.index', ['brand' => $brand->slug]) }}" class="px-4 py-2 bg-neutral-950 text-white text-xs tracking-wider uppercase font-semibold rounded-lg hover:bg-amber-400 hover:text-black transition-colors flex items-center gap-1">
            <span>Xem Đồ</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
          </a>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-neutral-200">
        <p class="text-xs text-neutral-500">Đang cập nhật danh sách thương hiệu đối tác...</p>
      </div>
    @endforelse
  </div>

</main>
@endsection
