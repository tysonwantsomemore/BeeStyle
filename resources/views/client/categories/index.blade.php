@extends('layouts.client')

@section('title', 'Danh Mục Thời Trang May Đo — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Danh Mục Thiết Kế</span>
  </nav>

  <!-- Banner -->
  <div class="bg-neutral-950 text-white rounded-2xl p-8 md:p-12 mb-12 relative overflow-hidden shadow-2xl">
    <div class="max-w-2xl relative z-10">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-400 font-semibold block mb-2">DANH MỤC THỜI TRANG CAO CẤP</span>
      <h1 class="font-serif-luxury text-3xl sm:text-5xl font-light mb-4">Các Dòng Sản Phẩm BeeStyle</h1>
      <p class="text-xs text-neutral-400 font-light leading-relaxed">
        Từ sơ mi lụa dệt tay, blazer may đo chuẩn Ý đến polo luxury cotton dệt tổ ong kháng khuẩn.
      </p>
    </div>
  </div>

  <!-- Categories Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($categories as $cat)
      @php
        $catImg = $cat->image ?? 'https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?q=80&w=800&auto=format&fit=crop';
        if (!str_starts_with($catImg, 'http')) {
          $catImg = asset($catImg);
        }
      @endphp
      <div class="group relative h-[380px] rounded-2xl overflow-hidden shadow-md flex flex-col justify-end p-6">
        <img src="{{ $catImg }}" alt="{{ $cat->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/90 via-neutral-950/40 to-transparent"></div>
        <div class="relative z-10 text-white">
          <span class="text-[10px] tracking-widest uppercase text-amber-300 font-semibold block">ATELIER CATEGORY</span>
          <h3 class="font-serif-luxury text-2xl font-bold mt-1 mb-2">{{ $cat->name }}</h3>
          <p class="text-xs text-neutral-300 font-light mb-4 line-clamp-2">{{ $cat->description ?? 'Tuyển tập các thiết kế cao cấp.' }}</p>
          <a href="{{ route('client.products.index', ['category' => $cat->slug]) }}" class="inline-flex items-center gap-2 text-xs font-semibold tracking-wider uppercase text-white hover:text-amber-400 transition-colors">
            <span>Khám Phá Danh Mục</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
          </a>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-neutral-200">
        <p class="text-xs text-neutral-500">Đang cập nhật danh mục sản phẩm...</p>
      </div>
    @endforelse
  </div>

</main>
@endsection
