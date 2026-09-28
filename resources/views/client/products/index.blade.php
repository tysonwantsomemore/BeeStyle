@extends('layouts.client')

@section('title', !empty($search) ? ('Kết quả tìm kiếm cho: "' . $search . '" — BEESTYLE Studio') : (!empty($currentCategory) ? ($currentCategory->name . ' — BEESTYLE Studio') : 'Tất Cả Tác Phẩm Thiết Kế — BEESTYLE Studio • BST Sartorial 2026'))

@section('content')
<!-- ========================================================================= -->
<!-- 1. SHOP HEADER BANNER -->
<!-- ========================================================================= -->
<section class="w-full bg-neutral-950 text-white py-14 md:py-20 px-6 relative overflow-hidden">
  <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
  <div class="max-w-7xl mx-auto relative z-10 text-center">
    
    @if(!empty($search))
      <!-- Banner khi đang tìm kiếm -->
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-400 text-[10px] md:text-xs font-bold tracking-[0.25em] uppercase mb-3">
        <i data-lucide="search" class="w-3.5 h-3.5"></i> KẾT QUẢ TÌM KIẾM
      </span>
      <h1 class="font-serif-luxury text-3xl md:text-5xl font-normal tracking-wide mb-3">
        Từ khóa: &ldquo;<span class="text-amber-400 font-medium">{{ $search }}</span>&rdquo;
      </h1>
      <p class="text-xs md:text-sm text-neutral-300 max-w-xl mx-auto font-light leading-relaxed mb-6">
        Tìm thấy <strong class="text-white font-bold">{{ $products->total() }}</strong> tác phẩm phù hợp với phong cách &amp; tiêu chuẩn may đo của bạn.
      </p>

      <!-- Thanh tìm kiếm lại ngay trên Hero Banner -->
      <div class="max-w-xl mx-auto">
        <form action="{{ route('client.products.index') }}" method="GET" class="relative flex items-center rounded-full bg-white/10 backdrop-blur-md border border-white/20 p-1.5 focus-within:bg-white focus-within:border-white transition-all group/hero shadow-xl">
          @if($categorySlug)<input type="hidden" name="category" value="{{ $categorySlug }}">@endif
          @if(request('brand'))<input type="hidden" name="brand" value="{{ request('brand') }}">@endif
          @if(request('price_range'))<input type="hidden" name="price_range" value="{{ request('price_range') }}">@endif
          @if(request('size'))<input type="hidden" name="size" value="{{ request('size') }}">@endif
          @if(request('color'))<input type="hidden" name="color" value="{{ request('color') }}">@endif
          @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif

          <div class="flex items-center flex-grow pl-3">
            <i data-lucide="search" class="w-4 h-4 text-neutral-400 group-focus-within/hero:text-neutral-900 transition-colors shrink-0"></i>
            <input type="text" 
                   name="q" 
                   value="{{ $search }}" 
                   placeholder="Nhập từ khóa khác (ví dụ: áo polo, sơ mi, blazer...)" 
                   class="w-full bg-transparent px-3 py-2 text-xs text-white group-focus-within/hero:text-neutral-900 placeholder:text-neutral-400 focus:outline-none font-medium">
          </div>
          <button type="submit" class="px-5 py-2.5 rounded-full bg-amber-400 hover:bg-amber-500 text-neutral-950 text-xs font-black uppercase tracking-wider transition-colors shrink-0 shadow cursor-pointer">
            Tìm Lại
          </button>
        </form>
      </div>

    @else
      <!-- Banner mặc định hoặc theo danh mục -->
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400/10 border border-amber-400/20 text-amber-400 text-[10px] md:text-xs font-bold tracking-[0.25em] uppercase mb-3">
        CATALOG CHÍNH THỨC 2026
      </span>
      <h1 class="font-serif-luxury text-3xl md:text-5xl font-light tracking-wide mb-3">
        {{ $currentCategory ? $currentCategory->name : 'Tất Cả Tác Phẩm Thiết Kế' }}
      </h1>
      <p class="text-xs md:text-sm text-neutral-300 max-w-xl mx-auto font-light leading-relaxed">
        Khám phá bộ sưu tập thời trang may đo, sơ mi lụa tơ tằm, blazer chuẩn Ý và áo phông định lượng 250GSM cao cấp.
      </p>
    @endif

  </div>
</section>

<!-- ========================================================================= -->
<!-- 2. MAIN CATALOG & ADVANCED FILTER -->
<!-- ========================================================================= -->
<main class="w-full flex-grow py-10 px-4 sm:px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-6 font-medium">
    <a href="{{ route('client.home') }}" class="hover:text-black transition-colors">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-neutral-400"></i>
    <a href="{{ route('client.products.index') }}" class="hover:text-black transition-colors">Sản Phẩm</a>
    @if(!empty($search))
      <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-neutral-400"></i>
      <span class="text-neutral-900 font-semibold truncate max-w-xs">Tìm kiếm: &ldquo;{{ $search }}&rdquo;</span>
    @elseif(!empty($currentCategory))
      <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-neutral-400"></i>
      <span class="text-neutral-900 font-semibold truncate max-w-xs">{{ $currentCategory->name }}</span>
    @endif
  </nav>

  <!-- Category Tabs -->
  <div class="flex flex-wrap items-center justify-between gap-4 mb-6 pb-5 border-b border-neutral-200">
    <div class="flex flex-wrap gap-2 text-xs tracking-wider uppercase font-medium">
      <a href="{{ route('client.products.index', request()->except(['category', 'page'])) }}" 
         class="px-4 py-2 rounded-full transition-all duration-200 {{ empty($categorySlug) ? 'bg-neutral-950 text-white font-bold shadow-xs' : 'bg-neutral-100 hover:bg-neutral-200 text-neutral-700' }}">
        Tất Cả
      </a>
      @foreach($categories as $cat)
        <a href="{{ route('client.products.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" 
           class="px-4 py-2 rounded-full transition-all duration-200 {{ $categorySlug === $cat->slug ? 'bg-neutral-950 text-white font-bold shadow-xs' : 'bg-neutral-100 hover:bg-neutral-200 text-neutral-700' }}">
          {{ $cat->name }}
        </a>
      @endforeach
      <a href="{{ route('client.daily-deals.index') }}" class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-full transition-colors font-semibold flex items-center gap-1.5 shadow-xs">
        <i data-lucide="zap" class="w-3.5 h-3.5 fill-rose-600"></i>
        <span>Flash Sale</span>
        <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
      </a>
    </div>

    <div class="text-xs text-neutral-500 font-medium">
      Hiển thị <strong class="text-neutral-950 font-bold">{{ $products->total() }}</strong> tác phẩm
    </div>
  </div>

  <!-- Advanced Filter Toolbar Form (5 bộ lọc: Thương hiệu, Khoảng giá, Size, Màu sắc, Sắp xếp) -->
  <div class="bg-white p-4 sm:p-5 rounded-2xl border border-neutral-200 mb-6 shadow-xs">
    <form id="filter-form" action="{{ route('client.products.index') }}" method="GET">
      @if($categorySlug)
        <input type="hidden" name="category" value="{{ $categorySlug }}">
      @endif
      @if($search !== '')
        <input type="hidden" name="q" value="{{ $search }}">
      @endif

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 text-xs">
        
        <!-- 1. Bộ lọc Thương hiệu -->
        <div class="flex flex-col gap-1.5">
          <label class="tracking-wider uppercase text-neutral-500 font-bold text-[10px] flex items-center gap-1">
            <i data-lucide="award" class="w-3 h-3 text-amber-600"></i> Thương hiệu:
          </label>
          <select name="brand" onchange="document.getElementById('filter-form').submit()" class="bg-neutral-50 border border-neutral-200 rounded-xl px-3 py-2 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors cursor-pointer font-medium">
            <option value="">Tất cả thương hiệu</option>
            @foreach($brands as $b)
              <option value="{{ $b->slug }}" {{ request('brand') === $b->slug ? 'selected' : '' }}>
                {{ $b->name }} ({{ $b->products_count }})
              </option>
            @endforeach
          </select>
        </div>

        <!-- 2. Bộ lọc Khoảng giá bán -->
        <div class="flex flex-col gap-1.5">
          <label class="tracking-wider uppercase text-neutral-500 font-bold text-[10px] flex items-center gap-1">
            <i data-lucide="badge-dollar-sign" class="w-3 h-3 text-emerald-600"></i> Mức giá:
          </label>
          <select name="price_range" onchange="document.getElementById('filter-form').submit()" class="bg-neutral-50 border border-neutral-200 rounded-xl px-3 py-2 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors cursor-pointer font-medium">
            <option value="">Tất cả mức giá</option>
            <option value="under_500" {{ request('price_range') === 'under_500' ? 'selected' : '' }}>Dưới 500.000₫</option>
            <option value="500_1000" {{ request('price_range') === '500_1000' ? 'selected' : '' }}>500.000₫ — 1.000.000₫</option>
            <option value="above_1000" {{ (request('price_range') === 'above_1000' || request('price_range') === 'over_1000') ? 'selected' : '' }}>Trên 1.000.000₫</option>
          </select>
        </div>

        <!-- 3. Bộ lọc Kích cỡ (Size) -->
        <div class="flex flex-col gap-1.5">
          <label class="tracking-wider uppercase text-neutral-500 font-bold text-[10px] flex items-center gap-1">
            <i data-lucide="ruler" class="w-3 h-3 text-indigo-600"></i> Kích thước (Size):
          </label>
          <select name="size" onchange="document.getElementById('filter-form').submit()" class="bg-neutral-50 border border-neutral-200 rounded-xl px-3 py-2 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors cursor-pointer font-medium">
            <option value="">Tất cả kích cỡ</option>
            @foreach($availableSizes as $sz)
              <option value="{{ $sz }}" {{ request('size') === $sz ? 'selected' : '' }}>Size {{ $sz }}</option>
            @endforeach
          </select>
        </div>

        <!-- 4. Bộ lọc Màu sắc -->
        <div class="flex flex-col gap-1.5">
          <label class="tracking-wider uppercase text-neutral-500 font-bold text-[10px] flex items-center gap-1">
            <i data-lucide="palette" class="w-3 h-3 text-rose-500"></i> Màu sắc:
          </label>
          <select name="color" onchange="document.getElementById('filter-form').submit()" class="bg-neutral-50 border border-neutral-200 rounded-xl px-3 py-2 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors cursor-pointer font-medium">
            <option value="">Tất cả màu sắc</option>
            @foreach($availableColors as $cl)
              <option value="{{ $cl }}" {{ request('color') === $cl ? 'selected' : '' }}>Màu {{ $cl }}</option>
            @endforeach
          </select>
        </div>

        <!-- 5. Sắp xếp thứ tự -->
        <div class="flex flex-col gap-1.5">
          <label class="tracking-wider uppercase text-neutral-500 font-bold text-[10px] flex items-center gap-1">
            <i data-lucide="arrow-up-down" class="w-3 h-3 text-neutral-700"></i> Sắp xếp theo:
          </label>
          <select name="sort" onchange="document.getElementById('filter-form').submit()" class="bg-neutral-50 border border-neutral-200 rounded-xl px-3 py-2 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors cursor-pointer font-semibold">
            <option value="latest" {{ (request('sort') === 'latest' || request('sort') === 'newest') ? 'selected' : '' }}>Mới Nhất</option>
            <option value="bestseller" {{ (request('sort') === 'bestseller' || request('sort') === 'popular') ? 'selected' : '' }}>Bán Chạy Nhất</option>
            <option value="rating_desc" {{ request('sort') === 'rating_desc' ? 'selected' : '' }}>Đánh Giá Tốt Nhất</option>
            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
          </select>
        </div>

      </div>
    </form>
  </div>

  <!-- Active Filter Pills (Dải thẻ hiển thị các bộ lọc đang kích hoạt với nút xóa từng mục) -->
  @if(!empty($activeFilters) && count($activeFilters) > 0)
    <div class="mb-8 p-3.5 bg-neutral-100/80 rounded-xl border border-neutral-200/80 flex flex-wrap items-center gap-2 text-xs animate-fade-in">
      <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500 flex items-center gap-1 mr-1">
        <i data-lucide="filter" class="w-3.5 h-3.5 text-amber-600"></i>
        <span>Đang áp dụng:</span>
      </span>

      @foreach($activeFilters as $key => $af)
        <a href="{{ $af['url'] }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-rose-50 text-neutral-800 hover:text-rose-700 rounded-full border border-neutral-200 text-xs font-semibold shadow-2xs transition-colors group/pill" 
           title="Bỏ tiêu chí này">
          <span>{{ $af['label'] }}</span>
          <span class="w-4 h-4 rounded-full bg-neutral-100 group-hover/pill:bg-rose-100 flex items-center justify-center text-neutral-400 group-hover/pill:text-rose-600 text-[10px]">
            <i data-lucide="x" class="w-3 h-3"></i>
          </span>
        </a>
      @endforeach

      <!-- Nút xóa toàn bộ bộ lọc -->
      <a href="{{ route('client.products.index') }}" 
         class="inline-flex items-center gap-1 px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-full text-xs font-bold transition-colors ml-auto shadow-xs">
        <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
        <span>Xóa tất cả lọc</span>
      </a>
    </div>
  @endif

  <!-- Product Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
    @forelse($products as $p)
      @php
        $isSaleActive = $p->is_sale_active;
        $minPrice = ($p->variants && $p->variants->isNotEmpty()) 
          ? $p->variants->min(fn($v) => $v->effective_price) 
          : ($p->effective_price ?? ($p->price ?? 0));
        $hasDiscount = $isSaleActive && ($p->original_price && $p->original_price > $minPrice);
        $discountPercent = $hasDiscount ? round((($p->original_price - $minPrice) / $p->original_price) * 100) : 0;
        $primaryImg = $p->primaryImage->image_path ?? $p->thumbnail ?? 'assets/img/products/1.png';
        if (!str_starts_with($primaryImg, 'http')) {
          $primaryImg = asset(ltrim($primaryImg, '/'));
        }
      @endphp
      <div class="group flex flex-col bg-white rounded-2xl border border-neutral-200/80 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500">
        <!-- Thumbnail -->
        <a href="{{ route('client.products.show', $p->id) }}" class="aspect-[3/4] relative bg-neutral-100 overflow-hidden block">
          <img src="{{ $primaryImg }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          
          <!-- Badges góc trái -->
          <div class="absolute top-3 left-3 flex flex-col gap-1">
            @if($p->is_new)
              <span class="px-2 py-0.5 bg-neutral-900 text-white text-[9px] tracking-widest uppercase font-bold rounded shadow-xs">MỚI</span>
            @endif
            @if($hasDiscount && $discountPercent > 0)
              <span class="px-2 py-0.5 bg-rose-600 text-white text-[9px] font-black rounded shadow-xs">
                -{{ $discountPercent }}%
              </span>
            @endif
          </div>

          <!-- Nút Yêu thích (Wishlist) -->
          <button type="button" 
                  onclick="toggleWishlist({{ $p->id }}, this)" 
                  class="btn-wishlist-{{ $p->id }} absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 hover:bg-white backdrop-blur-md flex items-center justify-center text-neutral-600 hover:text-rose-600 shadow-sm transition-all active:scale-90 cursor-pointer {{ in_array($p->id, $wishlistIds ?? []) ? 'text-rose-600' : '' }}" 
                  title="Thêm vào yêu thích">
            <i class="fa-solid fa-heart text-xs {{ in_array($p->id, $wishlistIds ?? []) ? 'text-rose-500' : '' }}"></i>
          </button>

          <!-- Overlay Hover khám phá -->
          <div class="absolute inset-0 bg-neutral-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-4">
            <span class="px-4 py-2 bg-white text-neutral-950 text-xs font-semibold tracking-wider uppercase rounded-xl shadow-lg">Khám Phá Chi Tiết</span>
          </div>
        </a>

        <!-- Body -->
        <div class="p-5 flex flex-col flex-grow justify-between">
          <div>
            <div class="flex items-center justify-between gap-1 mb-1">
              <span class="text-[10px] tracking-wider uppercase text-amber-700 font-bold truncate">
                {{ $p->category->name ?? 'Beestyle Studio' }}
              </span>
              @if($p->rating > 0)
                <span class="text-[10px] text-amber-500 font-bold flex items-center gap-0.5">
                  <i data-lucide="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                  <span>{{ number_format($p->rating, 1) }}</span>
                </span>
              @endif
            </div>

            <a href="{{ route('client.products.show', $p->id) }}" class="font-serif-luxury text-lg font-bold text-neutral-950 hover:text-amber-800 transition-colors line-clamp-2 leading-snug">
              {{ $p->name }}
            </a>
          </div>

          <div class="mt-4 pt-3 border-t border-neutral-100 flex flex-col gap-3">
            <div class="flex items-baseline justify-between">
              <div>
                <span class="font-serif-luxury text-xl font-black text-neutral-950 block">
                  {{ number_format($minPrice, 0, ',', '.') }}₫
                </span>
                @if($hasDiscount)
                  <span class="text-xs text-neutral-400 line-through font-medium">
                    {{ number_format($p->original_price, 0, ',', '.') }}₫
                  </span>
                @endif
              </div>
              @if($p->sold_count > 0)
                <span class="text-[10px] text-neutral-400 font-medium">Đã bán {{ $p->sold_count }}</span>
              @endif
            </div>

            <!-- Cặp nút Thêm vào giỏ & Mua ngay -->
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
      <!-- Trạng thái Trống (Empty State) -->
      <div class="col-span-full text-center py-16 px-4 bg-white rounded-3xl border border-neutral-200 shadow-xs">
        <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4">
          <i data-lucide="search-x" class="w-8 h-8"></i>
        </div>
        <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900 mb-2">
          @if(!empty($search))
            Không tìm thấy sản phẩm nào khớp với &ldquo;{{ $search }}&rdquo;
          @else
            Không tìm thấy tác phẩm phù hợp với bộ lọc đã chọn
          @endif
        </h3>
        <p class="text-xs text-neutral-500 max-w-md mx-auto mb-6 leading-relaxed">
          Quý khách vui lòng thử tìm kiếm bằng từ khóa ngắn hơn, kiểm tra lỗi chính tả hoặc điều chỉnh lại các tiêu chí bộ lọc (khoảng giá, thương hiệu, kích cỡ).
        </p>

        <!-- Từ khóa gợi ý phổ biến -->
        <div class="flex flex-wrap justify-center items-center gap-2 mb-8">
          <span class="text-xs text-neutral-400 font-medium">Gợi ý tìm kiếm:</span>
          @foreach(['Áo Polo Nam', 'Áo Sơ Mi Lụa', 'Áo Khoác Blazer', 'Áo Thun Boxy'] as $suggestTag)
            <a href="{{ route('client.products.index', ['q' => $suggestTag]) }}" class="px-3 py-1.5 bg-neutral-100 hover:bg-neutral-900 hover:text-white rounded-full text-xs font-medium transition-colors">
              {{ $suggestTag }}
            </a>
          @endforeach
        </div>

        <div class="flex justify-center gap-3">
          <a href="{{ route('client.products.index') }}" class="px-6 py-3 bg-neutral-950 text-white text-xs tracking-widest uppercase font-bold rounded-xl hover:bg-neutral-800 transition-colors shadow">
            Xem Tất Cả Sản Phẩm
          </a>
        </div>
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