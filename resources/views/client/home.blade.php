@extends('layouts.client')

@section('title', 'BEESTYLE — Contemporary Fashion & Studio • BST Sartorial 2026')

@section('content')
<!-- ========================================================================= -->
<!-- 1. HERO SECTION: Haute Couture Editorial Fashion Banner -->
<!-- ========================================================================= -->
<section id="home" class="relative w-full min-h-[85vh] md:min-h-[90vh] bg-neutral-900 flex items-center justify-center overflow-hidden">
  <div class="absolute inset-0 z-0">
    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2000&auto=format&fit=crop" alt="Beestyle Fashion Editorial 2026" class="w-full h-full object-cover object-top opacity-60">
    <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/40 to-transparent"></div>
  </div>

  <div class="relative z-10 max-w-5xl mx-auto px-6 text-center text-white flex flex-col items-center">
    <span class="text-xs md:text-sm tracking-[0.4em] uppercase text-amber-300/90 font-medium mb-4 animate-fade-in">
      THE 2026 SARTORIAL COLLECTION
    </span>
    <h1 class="font-serif-luxury text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-light tracking-wide leading-none mb-6">
      Nghệ Thuật Cắt May<br><span class="italic font-serif">Đương Đại &amp; Tối Giản</span>
    </h1>
    <p class="text-sm md:text-base text-neutral-300 font-light max-w-xl mb-10 leading-relaxed">
      Tôn vinh vẻ đẹp tự nhiên thông qua chất liệu lụa tơ tằm dệt tay, len dạ Cashmere và kỹ thuật may đo chuẩn Ý.
    </p>
    
    <div class="flex flex-col sm:flex-row items-center gap-4">
      <a href="{{ route('client.products.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-neutral-950 text-xs tracking-[0.25em] uppercase font-semibold hover:bg-amber-400 hover:text-black transition-all duration-300 shadow-2xl rounded-sm">
        Khám Phá Sản Phẩm
      </a>
      <a href="#lookbook" class="w-full sm:w-auto px-8 py-4 bg-transparent border border-white/60 text-white text-xs tracking-[0.25em] uppercase font-semibold hover:bg-white/10 transition-all duration-300 rounded-sm">
        Xem Lookbook 2026
      </a>
    </div>
  </div>

  <!-- Scroll Indicator -->
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 text-white/60 flex flex-col items-center gap-2">
    <span class="text-[9px] tracking-[0.3em] uppercase">Cuộn xuống</span>
    <div class="w-4 h-8 border border-white/30 rounded-full flex justify-center p-1">
      <div class="w-1 h-2 bg-white/70 rounded-full animate-bounce"></div>
    </div>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 2. STORE VALUE PROPOSITION / HIGHLIGHTS BAR -->
<!-- ========================================================================= -->
<section class="w-full bg-white border-b border-neutral-200/80 py-10 px-6">
  <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
    
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-full bg-brand-100 flex items-center justify-center text-neutral-800 shrink-0">
        <i data-lucide="sparkles" class="w-5 h-5"></i>
      </div>
      <div>
        <h4 class="text-xs uppercase tracking-wider font-semibold text-neutral-900">Thiết Kế Độc Bản</h4>
        <p class="text-xs text-neutral-500 font-light mt-0.5">May đo giới hạn số lượng</p>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-full bg-brand-100 flex items-center justify-center text-neutral-800 shrink-0">
        <i data-lucide="truck" class="w-5 h-5"></i>
      </div>
      <div>
        <h4 class="text-xs uppercase tracking-wider font-semibold text-neutral-900">Freeship Toàn Quốc</h4>
        <p class="text-xs text-neutral-500 font-light mt-0.5">Áp dụng đơn từ 500.000₫</p>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-full bg-brand-100 flex items-center justify-center text-neutral-800 shrink-0">
        <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
      </div>
      <div>
        <h4 class="text-xs uppercase tracking-wider font-semibold text-neutral-900">Đổi Trả 30 Ngày</h4>
        <p class="text-xs text-neutral-500 font-light mt-0.5">Hỗ trợ đổi size tận nơi</p>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-full bg-brand-100 flex items-center justify-center text-neutral-800 shrink-0">
        <i data-lucide="shield-check" class="w-5 h-5"></i>
      </div>
      <div>
        <h4 class="text-xs uppercase tracking-wider font-semibold text-neutral-900">Bảo Hành Đường May</h4>
        <p class="text-xs text-neutral-500 font-light mt-0.5">Cam kết 100% chất lượng</p>
      </div>
    </div>

  </div>
</section>

<!-- ========================================================================= -->
<!-- 3. FEATURED COLLECTIONS GRID -->
<!-- ========================================================================= -->
<section id="collections" class="w-full py-24 px-6 max-w-7xl mx-auto">
  <div class="text-center max-w-xl mx-auto mb-16">
    <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-semibold block mb-2">BỘ SƯU TẬP 2026</span>
    <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-900 font-light">
      Danh Mục Tuyển Chọn
    </h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    
    <!-- Collection 1: Sơ Mi Lụa & May Đo -->
    <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="collection-card group relative h-[480px] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-end p-8">
      <img src="https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?q=80&w=800&auto=format&fit=crop" alt="Thời Trang Nam Beestyle" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/90 via-neutral-950/30 to-transparent"></div>
      <div class="relative z-10 text-white">
        <span class="text-[10px] tracking-[0.3em] uppercase text-amber-300 font-medium">ATELIER TAILORING</span>
        <h3 class="font-serif-luxury text-2xl font-normal mt-1 mb-2">Sơ Mi Lụa Nam</h3>
        <p class="text-xs text-neutral-300 font-light mb-4 opacity-90 line-clamp-2">Sơ mi lụa tơ tằm dệt tay, cổ ép keo Đức và khuy xà cừ tự nhiên.</p>
        <span class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-white group-hover:text-amber-400 transition-colors">
          Khám Phá <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
        </span>
      </div>
    </a>

    <!-- Collection 2: Blazer & Áo Khoác -->
    <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="collection-card group relative h-[480px] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-end p-8">
      <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop" alt="Blazer May Đo Beestyle" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/90 via-neutral-950/30 to-transparent"></div>
      <div class="relative z-10 text-white">
        <span class="text-[10px] tracking-[0.3em] uppercase text-amber-300 font-medium">ITALIAN SARTORIAL</span>
        <h3 class="font-serif-luxury text-2xl font-normal mt-1 mb-2">Blazer May Đo Chuẩn Ý</h3>
        <p class="text-xs text-neutral-300 font-light mb-4 opacity-90 line-clamp-2">Phom suông hiện đại, đệm vai tự nhiên tôn trọn vóc dáng nam tính.</p>
        <span class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-white group-hover:text-amber-400 transition-colors">
          Khám Phá <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
        </span>
      </div>
    </a>

    <!-- Collection 3: Polo Luxury Cotton -->
    <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="collection-card group relative h-[480px] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-end p-8">
      <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?q=80&w=800&auto=format&fit=crop" alt="Polo Nam Luxury" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/90 via-neutral-950/30 to-transparent"></div>
      <div class="relative z-10 text-white">
        <span class="text-[10px] tracking-[0.3em] uppercase text-amber-300 font-medium">PREMIUM COTTON</span>
        <h3 class="font-serif-luxury text-2xl font-normal mt-1 mb-2">Polo Dệt Tổ Ong</h3>
        <p class="text-xs text-neutral-300 font-light mb-4 opacity-90 line-clamp-2">100% Sợi Cotton chải kỹ kháng khuẩn, giữ phom cổ bẻ thẳng thớm.</p>
        <span class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-white group-hover:text-amber-400 transition-colors">
          Khám Phá <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
        </span>
      </div>
    </a>

    <!-- Collection 4: Áo Thun Streetwear Boxy -->
    <a href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}" class="collection-card group relative h-[480px] rounded-2xl overflow-hidden shadow-lg flex flex-col justify-end p-8">
      <img src="https://images.unsplash.com/photo-1614252369475-531eba835eb1?q=80&w=800&auto=format&fit=crop" alt="Áo Thun Nam" class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/90 via-neutral-950/30 to-transparent"></div>
      <div class="relative z-10 text-white">
        <span class="text-[10px] tracking-[0.3em] uppercase text-amber-300 font-medium">STREETWEAR 250GSM</span>
        <h3 class="font-serif-luxury text-2xl font-normal mt-1 mb-2">Áo Phông Foam Boxy</h3>
        <p class="text-xs text-neutral-300 font-light mb-4 opacity-90 line-clamp-2">Định lượng Heavyweight dày dặn, form suông rộng thoải mái cá tính.</p>
        <span class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-white group-hover:text-amber-400 transition-colors">
          Khám Phá <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
        </span>
      </div>
    </a>

  </div>
</section>

<!-- ========================================================================= -->
<!-- 4. EDITORIAL LOOKBOOK SECTION -->
<!-- ========================================================================= -->
<section id="lookbook" class="w-full bg-neutral-900 text-white py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-16 pb-6 border-b border-neutral-800">
      <div>
        <span class="text-xs tracking-[0.4em] uppercase text-amber-400 font-semibold block mb-2">EDITORIAL 2026</span>
        <h2 class="font-serif-luxury text-3xl md:text-5xl font-light">
          Lookbook: "Vũ Điệu Của Lụa &amp; Dạ"
        </h2>
      </div>
      <p class="text-xs text-neutral-400 max-w-md font-light leading-relaxed">
        Mỗi khung hình là câu chuyện về ánh sáng, phom dáng và sự thăng hoa của vật liệu tự nhiên trong không gian sống đương đại.
      </p>
    </div>

    <!-- Editorial Magazine Layout -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
      
      <div class="md:col-span-7 aspect-[4/5] rounded-2xl overflow-hidden bg-neutral-800 shadow-2xl relative group">
        <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=1200&auto=format&fit=crop" alt="Lookbook Editorial Shot 1" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        <div class="absolute bottom-6 left-6 right-6 p-6 bg-black/60 backdrop-blur-md rounded-xl text-white">
          <span class="text-[10px] tracking-widest uppercase text-amber-300 block mb-1">LOOK 01 — THE AUTUMN COAT</span>
          <h3 class="font-serif-luxury text-xl mb-2">Măng Tô Dạ Cashmere &amp; Sơ Mi Lụa</h3>
          <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="text-xs text-amber-200 hover:text-white inline-flex items-center gap-1 font-semibold">
            Xem tác phẩm chi tiết <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
          </a>
        </div>
      </div>

      <div class="md:col-span-5 flex flex-col gap-8">
        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-neutral-800 shadow-xl relative group">
          <img src="https://images.unsplash.com/photo-1539008835657-9e8e9680c956?q=80&w=800&auto=format&fit=crop" alt="Lookbook Editorial Shot 2" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          <div class="absolute bottom-4 left-4 p-4 bg-black/60 backdrop-blur-md rounded-lg text-white">
            <span class="text-[9px] tracking-widest uppercase text-amber-300 block">LOOK 02 — THE SILK SHIRT</span>
            <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="font-serif-luxury text-base hover:underline block">Sơ Mi Lụa Dệt Tay Thắt Nơ Cổ</a>
          </div>
        </div>

        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-neutral-800 shadow-xl relative group">
          <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop" alt="Lookbook Editorial Shot 3" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          <div class="absolute bottom-4 left-4 p-4 bg-black/60 backdrop-blur-md rounded-lg text-white">
            <span class="text-[9px] tracking-widest uppercase text-amber-300 block">LOOK 03 — ITALIAN SARTORIAL</span>
            <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="font-serif-luxury text-base hover:underline block">Blazer May Đo Peak Lapel Chuẩn Ý</a>
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
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-semibold block mb-2">TUYỂN TẬP CAO CẤP</span>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-900 font-light">
        Sản Phẩm Đang Được Yêu Thích
      </h2>
    </div>
    <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold tracking-widest uppercase text-neutral-800 hover:text-amber-800 transition-colors">
      Xem Tất Cả Sản Phẩm <i data-lucide="arrow-right" class="w-4 h-4"></i>
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
      <div class="group flex flex-col bg-white rounded-xl border border-neutral-200/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
        <!-- Thumbnail -->
        <a href="{{ route('client.products.show', $item->id) }}" class="aspect-[3/4] relative bg-neutral-100 overflow-hidden block">
          <img src="{{ $primaryImg }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
          
          @if($item->is_new)
            <span class="absolute top-3 left-3 px-2 py-0.5 bg-neutral-900 text-white text-[10px] tracking-widest uppercase font-semibold rounded">MỚI</span>
          @endif

          <div class="absolute inset-0 bg-neutral-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-4">
            <span class="px-4 py-2 bg-white text-neutral-950 text-xs font-semibold tracking-wider uppercase rounded shadow-lg">Xem Chi Tiết</span>
          </div>
        </a>

        <!-- Body -->
        <div class="p-4 flex flex-col flex-grow justify-between">
          <div>
            <span class="text-[10px] tracking-widest uppercase text-neutral-400 font-semibold block mb-1">
              {{ $item->category->name ?? 'Beestyle Studio' }}
            </span>
            <a href="{{ route('client.products.show', $item->id) }}" class="font-serif-luxury text-base font-medium text-neutral-900 hover:text-amber-800 transition-colors line-clamp-2">
              {{ $item->name }}
            </a>
          </div>

          <div class="mt-3 pt-3 border-t border-neutral-100 flex items-center justify-between">
            <span class="font-serif-luxury text-lg font-bold text-neutral-950">
              {{ number_format($minPrice, 0, ',', '.') }}₫
            </span>
            <form action="{{ route('client.cart.add') }}" method="POST">
              @csrf
              <input type="hidden" name="product_id" value="{{ $item->id }}">
              <input type="hidden" name="variant_id" value="{{ $item->variants->first()->id ?? '' }}">
              <input type="hidden" name="quantity" value="1">
              <button type="submit" class="p-2 text-neutral-600 hover:text-black hover:bg-brand-100 rounded-full transition-colors" title="Thêm vào giỏ">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col-span-4 text-center py-12 text-neutral-500 text-sm">
        Đang cập nhật các tác phẩm may đo mới nhất...
      </div>
    @endforelse
  </div>
</section>

<!-- ========================================================================= -->
<!-- 6. CURATED CATALOG CALLOUT -->
<!-- ========================================================================= -->
<section class="w-full py-20 px-6 bg-brand-100 border-y border-brand-200 text-center">
  <div class="max-w-3xl mx-auto">
    <span class="text-xs tracking-[0.4em] uppercase text-amber-900 font-semibold block mb-3">KHÁM PHÁ TOÀN DIỆN</span>
    <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-900 font-light mb-4">
      Bộ Sưu Tập Đầy Đủ 2026
    </h2>
    <p class="text-xs md:text-sm text-neutral-600 font-light leading-relaxed mb-8 max-w-xl mx-auto">
      Tất cả các sản phẩm sơ mi, blazer, polo và áo thun được phân loại chi tiết với bộ lọc chuyên sâu tại danh mục Sản Phẩm.
    </p>
    <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-neutral-950 text-white text-xs font-semibold tracking-[0.25em] uppercase rounded-sm shadow-xl hover:bg-neutral-800 transition-all">
      <span>Xem Tất Cả Sản Phẩm</span>
      <i data-lucide="arrow-right" class="w-4 h-4 text-amber-400"></i>
    </a>
  </div>
</section>

<!-- ========================================================================= -->
<!-- 7. BRAND STORY SECTION -->
<!-- ========================================================================= -->
<section id="about" class="w-full bg-white py-24 px-6">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
    
    <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-xl bg-neutral-200">
      <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=1200&auto=format&fit=crop" alt="Beestyle Atelier Studio" class="w-full h-full object-cover">
    </div>

    <div class="lg:pl-6">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-semibold block mb-2">TRIẾT LÝ THIẾT KẾ</span>
      <h2 class="font-serif-luxury text-3xl md:text-5xl text-neutral-900 font-light mb-6 leading-tight">
        Beestyle: Tôn Vinh Nét Đẹp Tối Giản &amp; Chuẩn Mực
      </h2>
      <p class="text-xs md:text-sm text-neutral-600 font-light leading-relaxed mb-4">
        Thành lập tại Sài Gòn, Beestyle ra đời với ước vọng kiến tạo nên những tác phẩm may mặc vượt qua vòng xoáy của thời trang nhanh. Chúng tôi tập trung vào 3 giá trị cốt lõi: <strong>Chất liệu tự nhiên cao cấp</strong>, <strong>Kỹ thuật may đo thủ công</strong> và <strong>Phom dáng tối giản bền vững</strong>.
      </p>
      <p class="text-xs md:text-sm text-neutral-600 font-light leading-relaxed mb-8">
        Mỗi chiếc áo sơ mi lụa, blazer hay đôi giày tây đều được người nghệ nhân dồn trọn tâm huyết, mang đến cho bạn trải nghiệm mặc êm ái, thanh lịch và tự tin trong mọi khoảnh khắc.
      </p>

      <div class="grid grid-cols-3 gap-6 pt-6 border-t border-neutral-200 text-center">
        <div>
          <span class="font-serif-luxury text-3xl md:text-4xl text-neutral-900 font-bold block">100%</span>
          <span class="text-[10px] tracking-wider uppercase text-neutral-500">Chất liệu tự nhiên</span>
        </div>
        <div>
          <span class="font-serif-luxury text-3xl md:text-4xl text-neutral-900 font-bold block">12+</span>
          <span class="text-[10px] tracking-wider uppercase text-neutral-500">Nghệ nhân Atelier</span>
        </div>
        <div>
          <span class="font-serif-luxury text-3xl md:text-4xl text-neutral-900 font-bold block">2026</span>
          <span class="text-[10px] tracking-wider uppercase text-neutral-500">Lookbook Mới Nhất</span>
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
    <span class="text-xs tracking-[0.4em] uppercase text-amber-400 block mb-2">BEESTYLE PRIVÉ</span>
    <h2 class="font-serif-luxury text-3xl md:text-4xl font-light mb-4">Đăng Ký Nhận Đặc Quyền Thành Viên</h2>
    <p class="text-xs text-neutral-400 font-light leading-relaxed mb-8">
      Nhận ngay <strong>mã giảm 15% (BEESTYLE15)</strong> cho đơn hàng đầu tiên cùng đặc quyền nhận vé mời các buổi ra mắt bộ sưu tập kín.
    </p>

    <form onsubmit="handleNewsletter(event)" class="flex flex-col sm:flex-row gap-2 max-w-md mx-auto">
      <input type="email" id="newsletter-email" placeholder="Nhập địa chỉ email của bạn..." required class="bg-neutral-900 border border-neutral-800 rounded px-4 py-3 text-xs text-white placeholder-neutral-500 focus:outline-none focus:border-amber-400 flex-grow">
      <button type="submit" class="px-6 py-3 bg-white text-neutral-950 text-xs tracking-widest uppercase font-semibold hover:bg-amber-400 transition-colors rounded">
        Đăng Ký
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
    alert(`Cảm ơn bạn (${email}) đã đăng ký nhận tin Beestyle Privé! Mã BEESTYLE15 đã sẵn sàng sử dụng.`);
    document.getElementById('newsletter-email').value = '';
  }
</script>
@endpush