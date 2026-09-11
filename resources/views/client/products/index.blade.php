@extends('layouts.client')

@section('title', 'Tất Cả Tác Phẩm Thiết Kế — BEESTYLE Studio • BST Sartorial 2026')

@section('content')
<!-- ========================================================================= -->
<!-- 1. SHOP HEADER BANNER -->
<!-- ========================================================================= -->
<section class="w-full bg-neutral-900 text-white py-16 px-6 relative overflow-hidden">
  <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
  <div class="max-w-7xl mx-auto relative z-10 text-center">
    <span class="text-xs tracking-[0.4em] uppercase text-amber-400 font-semibold block mb-2">CATALOG CHÍNH THỨC</span>
    <h1 class="font-serif-luxury text-3xl md:text-5xl font-light tracking-wide mb-3">Tất Cả Tác Phẩm Thiết Kế</h1>
    <p class="text-xs text-neutral-300 max-w-xl mx-auto font-light leading-relaxed">
      Khám phá bộ sưu tập thời trang may đo, sơ mi lụa tơ tằm, blazer chuẩn Ý và áo phông định lượng 250GSM.
    </p>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 2. MAIN CATALOG & FILTER -->
<!-- ========================================================================= -->
<main class="w-full flex-grow py-12 px-6 max-w-7xl mx-auto">
  
  <!-- Category Tabs -->
  <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-6 border-b border-neutral-200">
    <div class="flex flex-wrap gap-2 text-xs tracking-widest uppercase font-medium">
      <a href="{{ route('client.products.index', request()->except(['category', 'page'])) }}" class="px-4 py-2 rounded-full transition-colors {{ empty($categorySlug) ? 'bg-neutral-900 text-white font-bold' : 'bg-neutral-100 hover:bg-neutral-200 text-neutral-700' }}">
        Tất Cả
      </a>
      @foreach($categories as $cat)
        <a href="{{ route('client.products.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" class="px-4 py-2 rounded-full transition-colors {{ $categorySlug === $cat->slug ? 'bg-neutral-900 text-white font-bold' : 'bg-neutral-100 hover:bg-neutral-200 text-neutral-700' }}">
          {{ $cat->name }}
        </a>
      @endforeach
      <a href="{{ route('client.daily-deals.index') }}" class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-full transition-colors font-semibold flex items-center gap-1">
        <span>Flash Sale</span>
        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
      </a>
    </div>

    <div class="text-xs text-neutral-500">
      Hiển thị <strong class="text-neutral-900 font-semibold">{{ $products->total() ?? $products->count() }}</strong> tác phẩm
    </div>
  </div>

  <!-- Filter Toolbar Form -->
  <form id="filter-form" action="{{ route('client.products.index') }}" method="GET" class="bg-white p-4 rounded-xl border border-neutral-200 mb-8 flex flex-wrap items-center justify-between gap-4 text-xs">
    @if($categorySlug)
      <input type="hidden" name="category" value="{{ $categorySlug }}">
    @endif
    @if(request('q'))
      <input type="hidden" name="q" value="{{ request('q') }}">
    @endif

    <div class="flex flex-wrap items-center gap-4">
      <!-- Brand filter -->
      <div class="flex items-center gap-1.5">
        <span class="tracking-wider uppercase text-neutral-500 font-semibold text-[11px]">Thương hiệu:</span>
        <select name="brand" onchange="document.getElementById('filter-form').submit()" class="bg-neutral-50 border border-neutral-200 rounded px-3 py-1.5 text-xs text-neutral-800 focus:outline-none focus:border-neutral-900">
          <option value="">Tất cả thương hiệu</option>
          @foreach($brands as $b)
            <option value="{{ $b->slug }}" {{ request('brand') === $b->slug ? 'selected' : '' }}>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Price Range filter -->
      <div class="flex items-center gap-1.5">
        <span class="tracking-wider uppercase text-neutral-500 font-semibold text-[11px]">Khoảng giá:</span>
        <select name="price_range" onchange="document.getElementById('filter-form').submit()" class="bg-neutral-50 border border-neutral-200 rounded px-3 py-1.5 text-xs text-neutral-800 focus:outline-none focus:border-neutral-900">
          <option value="">Tất cả mức giá</option>
          <option value="under_500" {{ request('price_range') === 'under_500' ? 'selected' : '' }}>Dưới 500.000₫</option>
          <option value="500_1000" {{ request('price_range') === '500_1000' ? 'selected' : '' }}>500.000₫ — 1.000.000₫</option>
          <option value="above_1000" {{ request('price_range') === 'above_1000' ? 'selected' : '' }}>Trên 1.000.000₫</option>
        </select>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <span class="tracking-wider uppercase text-neutral-500 font-semibold text-[11px]">Sắp xếp theo:</span>
      <select name="sort" onchange="document.getElementById('filter-form').submit()" class="bg-neutral-50 border border-neutral-200 rounded px-3 py-1.5 text-xs text-neutral-800 focus:outline-none focus:border-neutral-900 font-medium">
        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Mới Nhất</option>
        <option value="bestseller" {{ request('sort') === 'bestseller' ? 'selected' : '' }}>Bán Chạy Nhất</option>
        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
      </select>
      @if($categorySlug || request('brand') || request('price_range') || request('sort') || request('q'))
        <a href="{{ route('client.products.index') }}" class="text-rose-600 hover:text-rose-800 font-semibold uppercase tracking-wider text-[11px] ml-2">Xóa lọc</a>
      @endif
    </div>
  </form>

  <!-- Product Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @forelse($products as $p)
      @php
        $minPrice = $p->variants->min('price') ?? $p->price ?? 0;
        $primaryImg = $p->primaryImage->image_path ?? $p->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=600&auto=format&fit=crop';
        if (!str_starts_with($primaryImg, 'http')) {
          $primaryImg = asset($primaryImg);
        }
      @endphp
      <div class="group flex flex-col bg-white rounded-2xl border border-neutral-200/80 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500">
        <!-- Thumbnail -->
        <a href="{{ route('client.products.show', $p->id) }}" class="aspect-[3/4] relative bg-neutral-100 overflow-hidden block">
          <img src="{{ $primaryImg }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          
          @if($p->is_new)
            <span class="absolute top-3 left-3 px-2 py-0.5 bg-neutral-900 text-white text-[10px] tracking-widest uppercase font-semibold rounded">MỚI</span>
          @endif

          <div class="absolute inset-0 bg-neutral-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-4">
            <span class="px-5 py-2.5 bg-white text-neutral-950 text-xs font-semibold tracking-wider uppercase rounded shadow-lg">Khám Phá Chi Tiết</span>
          </div>
        </a>

        <!-- Body -->
        <div class="p-5 flex flex-col flex-grow justify-between">
          <div>
            <span class="text-[10px] tracking-widest uppercase text-amber-600 font-bold block mb-1">
              {{ $p->category->name ?? 'Beestyle Studio' }}
            </span>
            <a href="{{ route('client.products.show', $p->id) }}" class="font-serif-luxury text-lg font-bold text-neutral-950 hover:text-amber-700 transition-colors line-clamp-2">
              {{ $p->name }}
            </a>
          </div>

          <div class="mt-4 pt-3 border-t border-neutral-100 flex flex-col gap-3">
            <div class="flex items-baseline justify-between">
              <div>
                <span class="font-serif-luxury text-xl font-black text-neutral-950 block">
                  {{ number_format($minPrice, 0, ',', '.') }}₫
                </span>
                @if($p->original_price && $p->original_price > $minPrice)
                  <span class="text-xs text-neutral-500 line-through font-medium">
                    {{ number_format($p->original_price, 0, ',', '.') }}₫
                  </span>
                @endif
              </div>
              @if($p->discount_percent > 0)
                <span class="px-2 py-0.5 bg-rose-600 text-white text-[10px] font-black rounded shadow-xs">
                  -{{ $p->discount_percent }}%
                </span>
              @endif
            </div>

            <!-- Cặp nút Thêm vào giỏ & Mua ngay cạnh nhau -->
            <div class="grid grid-cols-2 gap-2">
              <button type="button" 
                      onclick="openQuickVariantModal({{ $p->id }}, false, this)" 
                      class="w-full py-2.5 px-2 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl flex items-center justify-center gap-1.5 shadow-sm transition-all active:scale-95 cursor-pointer group/btn" 
                      title="Thêm vào giỏ hàng">
                <i data-lucide="shopping-bag" class="w-3.5 h-3.5 shrink-0 group-hover/btn:scale-110 transition-transform"></i>
                <span class="truncate">Thêm Giỏ</span>
              </button>
              
              <button type="button" 
                      onclick="openQuickVariantModal({{ $p->id }}, true, this)" 
                      class="w-full py-2.5 px-2 bg-amber-400 hover:bg-amber-500 text-neutral-950 text-xs font-black uppercase tracking-wider rounded-xl flex items-center justify-center gap-1.5 shadow-sm transition-all active:scale-95 cursor-pointer group/btn" 
                      title="Mua ngay — Thanh toán tức thì">
                <i data-lucide="zap" class="w-3.5 h-3.5 shrink-0 group-hover/btn:scale-110 transition-transform"></i>
                <span class="truncate">Mua Ngay</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-neutral-200">
        <div class="w-16 h-16 rounded-full bg-brand-100 flex items-center justify-center mx-auto mb-4 text-neutral-400">
          <i data-lucide="package-open" class="w-8 h-8"></i>
        </div>
        <h3 class="font-serif-luxury text-2xl font-normal text-neutral-800 mb-2">Không tìm thấy tác phẩm phù hợp</h3>
        <p class="text-xs text-neutral-500 max-w-md mx-auto mb-6">Vui lòng thử tìm kiếm với từ khóa khác hoặc điều chỉnh lại bộ lọc danh mục và khoảng giá.</p>
        <a href="{{ route('client.products.index') }}" class="px-6 py-3 bg-neutral-900 text-white text-xs tracking-widest uppercase font-semibold rounded hover:bg-neutral-800 transition-colors">
          Xem Toàn Bộ Sản Phẩm
        </a>
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