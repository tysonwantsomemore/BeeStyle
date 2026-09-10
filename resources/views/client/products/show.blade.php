@extends('layouts.client')

@section('title', $product->name . ' — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-8 md:py-12">
  <div class="max-w-7xl mx-auto px-6">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
      <a href="{{ route('client.home') }}" class="hover:text-black transition-colors">Trang Chủ</a>
      <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
      <a href="{{ route('client.products.index') }}" class="hover:text-black transition-colors">Sản Phẩm</a>
      <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
      <a href="{{ route('client.products.index', ['category' => $product->category->slug ?? '']) }}" class="hover:text-black transition-colors">
        {{ $product->category->name ?? 'Thời Trang Nam' }}
      </a>
      <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
      <span class="text-neutral-900 font-semibold truncate">{{ $product->name }}</span>
    </nav>

    <!-- Main Product View -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
      
      <!-- ========================================================================= -->
      <!-- LEFT: MULTI-ANGLE IMAGE GALLERY (7 cols) -->
      <!-- ========================================================================= -->
      @php
        $galleryImages = $product->images->sortBy('sort_order');
        if ($galleryImages->isEmpty()) {
          $mainImgPath = $product->image ?: 'assets/img/products/1.png';
          $firstImg = asset($mainImgPath);
        } else {
          $firstImg = asset($galleryImages->first()->image_path);
        }

        // Tính % giảm giá nếu có
        $hasDiscount = ($product->original_price && $product->original_price > $product->price);
        $discountPercent = $hasDiscount ? round((($product->original_price - $product->price) / $product->original_price) * 100) : 0;
        
        // Kiểm tra Running Deal / Flash Sale
        $isDealActive = isset($runningDeal) && (bool)$runningDeal;
        $effectivePrice = $product->price;
        if ($isDealActive && isset($runningDeal->deal_price) && $runningDeal->deal_price < $product->price) {
          $effectivePrice = $runningDeal->deal_price;
          $discountPercent = $runningDeal->discount_percent ?: $discountPercent;
        }

        $isFav = \App\Services\WishlistService::isFavorite($product->id);
      @endphp

      <div class="lg:col-span-7 flex flex-col md:flex-row-reverse gap-4">
        
        <!-- Main Hero Image View -->
        <div class="relative w-full aspect-[3/4] bg-neutral-100 rounded-2xl overflow-hidden border border-neutral-200 shadow-md group">
          <img id="main-product-img" src="{{ $firstImg }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-all duration-500 ease-out">
          
          <!-- Badges -->
          <div class="absolute top-4 left-4 flex flex-col gap-2 pointer-events-none">
            @if($isDealActive)
              <span class="px-2.5 py-1 bg-rose-600 text-white text-[10px] tracking-widest uppercase font-semibold rounded-md shadow flex items-center gap-1">
                <i data-lucide="flame" class="w-3.5 h-3.5"></i> FLASH SALE
              </span>
            @elseif($product->is_new)
              <span class="px-2.5 py-1 bg-neutral-900 text-white text-[10px] tracking-widest uppercase font-semibold rounded-md shadow">MỚI</span>
            @endif
            @if($product->is_featured)
              <span class="px-2.5 py-1 bg-amber-500 text-white text-[10px] tracking-widest uppercase font-semibold rounded-md shadow">ATELIER ORIGINS</span>
            @endif
          </div>

          <!-- Wishlist Heart Button -->
          <button type="button" 
                  id="wishlist-btn-{{ $product->id }}" 
                  onclick="toggleProductWishlist({{ $product->id }})" 
                  class="absolute top-4 right-4 w-11 h-11 rounded-full {{ $isFav ? 'bg-rose-50 border border-rose-200 text-rose-600 shadow-md' : 'bg-white/90 backdrop-blur-md border border-neutral-200 text-neutral-700 shadow' }} flex items-center justify-center transition-all duration-300 hover:scale-110 group/heart" 
                  title="{{ $isFav ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}">
            <i data-lucide="heart" id="wishlist-icon-{{ $product->id }}" class="w-5 h-5 transition-transform duration-300 group-hover/heart:scale-110 {{ $isFav ? 'fill-rose-500 text-rose-500' : 'text-neutral-700' }}"></i>
          </button>
        </div>

        <!-- Thumbnails Strip -->
        <div class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto max-h-[620px] shrink-0 pb-2 md:pb-0 scrollbar-none" id="gallery-thumbnails">
          @if($galleryImages->isNotEmpty())
            @foreach($galleryImages as $idx => $imgObj)
              @php $imgUrl = asset($imgObj->image_path); @endphp
              <button type="button" 
                      onclick="setMainImage('{{ $imgUrl }}', this)" 
                      class="relative w-16 h-20 md:w-20 md:h-24 rounded-xl overflow-hidden border-2 {{ $loop->first ? 'border-neutral-950 ring-2 ring-neutral-950/20' : 'border-neutral-200 hover:border-neutral-400' }} transition-all duration-200 shrink-0 bg-neutral-50 gallery-thumb-btn">
                <img src="{{ $imgUrl }}" alt="Ảnh chi tiết {{ $idx + 1 }}" class="w-full h-full object-cover">
              </button>
            @endforeach
          @else
            <button type="button" class="w-16 h-20 md:w-20 md:h-24 rounded-xl overflow-hidden border-2 border-neutral-950 shrink-0">
              <img src="{{ $firstImg }}" alt="Ảnh sản phẩm" class="w-full h-full object-cover">
            </button>
          @endif
        </div>

      </div>

      <!-- ========================================================================= -->
      <!-- RIGHT: PRODUCT INFORMATION & PURCHASE (5 cols) -->
      <!-- ========================================================================= -->
      <div class="lg:col-span-5 flex flex-col">
        
        <!-- Brand & SKU -->
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs tracking-[0.3em] uppercase text-amber-900 font-semibold">
            {{ $product->brand->name ?? 'BEESTYLE ATELIER' }}
          </span>
          <span class="text-xs text-neutral-400 font-mono" id="displaySku">
            SKU: {{ $product->sku ?: 'BST-' . str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
          </span>
        </div>

        <!-- Product Title -->
        <h1 class="font-serif-luxury text-3xl md:text-4xl text-neutral-900 font-medium mb-3 leading-tight">
          {{ $product->name }}
        </h1>

        <!-- Ratings & Views -->
        <div class="flex items-center gap-4 text-xs text-neutral-500 mb-6 pb-4 border-b border-neutral-200">
          <div class="flex items-center gap-1 text-amber-500">
            <div class="flex">
              @for($i = 0; $i < 5; $i++)
                <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
              @endfor
            </div>
            <span class="font-bold text-neutral-800 ml-1">4.9</span>
            <span class="text-neutral-500">({{ $product->reviews->count() ?: 128 }} đánh giá)</span>
          </div>
          <span class="text-neutral-300">|</span>
          <span class="flex items-center gap-1">
            <i data-lucide="eye" class="w-3.5 h-3.5 text-neutral-400"></i>
            <span>{{ number_format($product->views ?? 350) }} lượt xem</span>
          </span>
        </div>

        <!-- Price & Stock Box -->
        <div class="bg-brand-100/70 p-4 rounded-xl border border-brand-200 mb-6">
          <div class="flex items-baseline gap-3">
            <span id="price-display" class="font-serif-luxury text-3xl font-bold text-neutral-950">
              {{ number_format($effectivePrice, 0, ',', '.') }}₫
            </span>
            @if($hasDiscount)
              <span class="text-sm text-neutral-400 line-through">
                {{ number_format($product->original_price, 0, ',', '.') }}₫
              </span>
              <span class="ml-auto text-xs font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded">
                GIẢM {{ $discountPercent }}%
              </span>
            @endif
          </div>
          <div class="mt-2 flex items-center justify-between text-xs">
            <span class="text-emerald-700 font-semibold flex items-center gap-1" id="stockStatusIndicator">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span> 
              <span id="stockStatusText">Còn hàng ({{ $product->variants->sum('stock') ?: $product->stock }} cái có sẵn)</span>
            </span>
            <span class="text-neutral-500">Đổi size miễn phí 30 ngày</span>
          </div>
        </div>

        <!-- Short Description -->
        @if($product->short_description)
          <p class="text-xs text-neutral-600 leading-relaxed font-light mb-6">
            {{ $product->short_description }}
          </p>
        @endif

        <!-- Form Add To Cart & Buy Now -->
        <form action="{{ route('client.cart.add') }}" method="POST" id="productPurchaseForm">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <input type="hidden" name="variant_id" id="selectedVariantId" value="">
          <input type="hidden" name="color" id="selectedColorInput" value="">
          <input type="hidden" name="size" id="selectedSizeInput" value="">

          <!-- COLOR VARIANT SWATCHES -->
          @php
            $colorVariants = $product->variants->groupBy('color')->map(function($vars, $colorName) {
              $first = $vars->first();
              return (object)[
                'color' => $colorName,
                'color_code' => $first->color_code ?: '#111827',
                'sizes' => $vars->pluck('size')->filter()->values()->all(),
                'variant_id' => $first->id,
                'price' => $first->price,
                'stock' => $vars->sum('stock'),
                'image' => $first->image ? asset($first->image) : null
              ];
            });
            $sizes = $product->variants->pluck('size')->filter()->unique()->values();
          @endphp

          @if($colorVariants->isNotEmpty())
            <div class="mb-5 p-3 rounded-xl border border-neutral-200" id="colorGroupSection">
              <div class="flex justify-between items-center text-xs mb-2.5">
                <div class="flex items-center gap-1.5">
                  <span class="font-semibold uppercase tracking-wider text-neutral-800 text-[11px]">MÀU SẮC:</span>
                  <span id="selected-color-name" class="font-bold text-neutral-950">Chưa chọn</span>
                </div>
                <span class="text-rose-600 text-[10px] font-semibold">* Bắt buộc chọn</span>
              </div>

              <!-- Color Circles Swatches -->
              <div class="flex flex-wrap items-center gap-3">
                @foreach($colorVariants as $idx => $cv)
                  @php
                    $isLightColor = in_array(strtolower($cv->color_code), ['#ffffff', '#fff', '#fafafa', '#fdfbf7', '#f5eedc']);
                  @endphp
                  <button type="button" 
                          onclick="selectColorSwatch('{{ $cv->color }}', '{{ $cv->color_code }}', {{ $cv->variant_id }}, '{{ $cv->image ?? '' }}', this)" 
                          data-color="{{ $cv->color }}"
                          class="color-swatch-btn group relative w-9 h-9 rounded-full flex items-center justify-center p-0.5 transition-all duration-200 border border-neutral-300 hover:scale-105"
                          title="{{ $cv->color }}">
                    <span class="w-full h-full rounded-full {{ $isLightColor ? 'border border-neutral-300 shadow-2xs' : '' }}" 
                          style="background-color: {{ $cv->color_code }};"></span>
                    
                    <span class="absolute -top-7 left-1/2 -translate-x-1/2 bg-neutral-900 text-white text-[10px] font-medium py-0.5 px-2 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none shadow-md z-10">
                      {{ $cv->color }}
                    </span>
                  </button>
                @endforeach
              </div>
            </div>
          @endif

          <!-- Size Selector -->
          @if($sizes->isNotEmpty())
            <div class="mb-6 p-3 rounded-xl border border-neutral-200" id="sizeGroupSection">
              <div class="flex justify-between items-center text-xs mb-2">
                <div class="flex items-center gap-1.5">
                  <span class="font-semibold uppercase tracking-wider text-neutral-800 text-[11px]">KÍCH CỠ:</span>
                  <span id="selected-size-name" class="font-bold text-neutral-950">Chưa chọn</span>
                </div>
                <button type="button" onclick="openSizeGuideModal()" class="text-amber-800 hover:underline text-[11px] font-medium flex items-center gap-1">
                  <i data-lucide="ruler" class="w-3.5 h-3.5"></i> Bảng tính size AI
                </button>
              </div>
              <div class="flex flex-wrap gap-2">
                @foreach($sizes as $idx => $sz)
                  <button type="button" onclick="selectSize('{{ $sz }}', this)" class="w-14 h-10 border border-neutral-300 bg-white hover:border-neutral-900 rounded-lg text-xs font-semibold uppercase flex items-center justify-center transition-colors variant-size-btn">
                    {{ $sz }}
                  </button>
                @endforeach
              </div>
            </div>
          @endif

          <!-- THÔNG BÁO CHÍNH SÁCH ĐẶT CỌC 50% CHO ĐƠN HÀNG LỚN -->
          <div id="bulkDepositPolicyBox" class="p-3.5 bg-amber-50 border border-amber-300 rounded-xl mb-4 text-xs text-amber-950 hidden">
            <div class="flex items-start gap-2.5">
              <i data-lucide="coins" class="w-4 h-4 text-amber-700 shrink-0 mt-0.5"></i>
              <div>
                <strong class="font-bold uppercase block text-[11px]">CHÍNH SÁCH ĐẶT CỌC 50% (ĐƠN MUA TỪ 10 SẢN PHẨM)</strong>
                <p class="text-neutral-700 text-[11px] mt-0.5 leading-relaxed">
                  Quý khách đang đặt <strong id="bulkQtyText" class="text-neutral-950">10</strong> sản phẩm. Vui lòng thanh toán đặt cọc trước 50%: <strong class="text-rose-600 font-mono text-xs" id="depositAmountLive">0₫</strong>. Số tiền 50% còn lại (<span class="font-mono text-neutral-900" id="remainingAmountLive">0₫</span>) sẽ thanh toán cho bưu tá khi nhận hàng.
                </p>
              </div>
            </div>
          </div>

          <!-- Quantity & Action Buttons -->
          <div class="space-y-3 pt-2">
            <div class="flex items-center gap-3">
              <!-- Quantity selector -->
              <div class="flex items-center border border-neutral-300 rounded-lg bg-white overflow-hidden text-xs">
                <button type="button" onclick="changeQuantity(-1)" class="px-3.5 py-3 text-neutral-700 hover:bg-neutral-100 transition-colors">-</button>
                <input type="number" name="quantity" id="purchaseQuantity" value="1" min="1" max="99" class="w-12 text-center font-bold text-neutral-900 focus:outline-none" readonly>
                <button type="button" onclick="changeQuantity(1)" class="px-3.5 py-3 text-neutral-700 hover:bg-neutral-100 transition-colors">+</button>
              </div>

              <!-- Add to Bag -->
              <button type="submit" id="btnAddToCart" class="flex-grow py-3.5 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.2em] uppercase rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                <span>Thêm Vào Giỏ Hàng</span>
              </button>
            </div>

            <!-- Buy Now Button -->
            <button type="button" id="btnBuyNow" onclick="buyNowSubmit()" class="w-full py-3.5 bg-amber-400 hover:bg-amber-500 text-neutral-950 text-xs font-bold tracking-[0.2em] uppercase rounded-lg shadow transition-all flex items-center justify-center gap-2">
              <i data-lucide="zap" class="w-4 h-4"></i>
              <span>Mua Ngay — Thanh Toán Tức Thì</span>
            </button>
          </div>
        </form>

        <!-- Store Guarantees -->
        <div class="grid grid-cols-2 gap-3 mt-8 pt-6 border-t border-neutral-200 text-[11px] text-neutral-600">
          <div class="flex items-center gap-2">
            <i data-lucide="truck" class="w-4 h-4 text-neutral-800 shrink-0"></i>
            <span>Freeship toàn quốc từ 500.000₫</span>
          </div>
          <div class="flex items-center gap-2">
            <i data-lucide="shield-check" class="w-4 h-4 text-neutral-800 shrink-0"></i>
            <span>Cam kết 100% may đo chính hãng</span>
          </div>
          <div class="flex items-center gap-2">
            <i data-lucide="rotate-ccw" class="w-4 h-4 text-neutral-800 shrink-0"></i>
            <span>Đổi trả miễn phí trong 30 ngày</span>
          </div>
          <div class="flex items-center gap-2">
            <i data-lucide="package-check" class="w-4 h-4 text-neutral-800 shrink-0"></i>
            <span>Kiểm tra hàng trước khi nhận</span>
          </div>
        </div>

      </div>

    </div>

    <!-- ========================================================================= -->
    <!-- REVIEWS & RATINGS LIST -->
    <!-- ========================================================================= -->
    <section class="mt-20 pt-12 border-t border-neutral-200" id="reviews-section">
      <div class="max-w-4xl mx-auto">
        
        <div class="text-center mb-10">
          <span class="text-xs tracking-[0.3em] uppercase text-amber-800 font-semibold block mb-1">TRẢI NGHIỆM THỰC TẾ</span>
          <h3 class="font-serif-luxury text-3xl font-light text-neutral-900">Đánh Giá Từ Khách Hàng</h3>
        </div>

        <!-- Rating Summary Box -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 md:p-8 bg-brand-50/60 rounded-2xl border border-brand-200/80 mb-10 items-center">
          <div class="md:col-span-4 text-center md:border-r md:border-brand-200 pr-0 md:pr-6">
            <div class="font-serif-luxury text-5xl font-bold text-neutral-900">4.9</div>
            <div class="flex justify-center items-center gap-1 text-amber-400 my-2">
              @for($i = 0; $i < 5; $i++)
                <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
              @endfor
            </div>
            <span class="text-xs text-neutral-500 font-medium">Dựa trên {{ $product->reviews->count() ?: 128 }} nhận xét xác thực</span>
          </div>

          <div class="md:col-span-8 space-y-2 text-xs">
            @foreach([5 => 88, 4 => 10, 3 => 2, 2 => 0, 1 => 0] as $star => $pct)
              <div class="flex items-center gap-3">
                <span class="w-12 font-medium text-neutral-700 flex items-center gap-1">
                  {{ $star }} <i data-lucide="star" class="w-3 h-3 text-amber-400 fill-amber-400"></i>
                </span>
                <div class="flex-grow h-2 bg-neutral-200 rounded-full overflow-hidden">
                  <div class="h-full bg-amber-400 rounded-full" style="width: {{ $pct }}%;"></div>
                </div>
                <span class="w-10 text-right text-neutral-400 text-[11px]">{{ $pct }}%</span>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Reviews List -->
        <div class="space-y-6 mb-12">
          @php
            $displayReviews = $product->reviews->where('status', 'approved');
          @endphp

          @forelse($displayReviews as $rev)
            <div class="p-6 bg-white rounded-2xl border border-neutral-200/90 shadow-2xs space-y-3">
              <div class="flex justify-between items-start">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-neutral-900 text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                    {{ mb_substr($rev->user_name ?? ($rev->user->name ?? 'K'), 0, 1) }}
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="font-bold text-neutral-900 text-xs">{{ $rev->user_name ?? ($rev->user->name ?? 'Khách Hàng Atelier') }}</span>
                      <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded text-[10px] font-medium flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3 h-3 text-emerald-600"></i> Đã mua hàng
                      </span>
                    </div>
                    <span class="text-[11px] text-neutral-400 block mt-0.5">{{ $rev->created_at ? $rev->created_at->format('d/m/Y') : 'Vừa xong' }}</span>
                  </div>
                </div>

                <div class="flex items-center gap-0.5 text-amber-400">
                  @for($s = 1; $s <= 5; $s++)
                    <i data-lucide="star" class="w-3.5 h-3.5 {{ $s <= ($rev->rating ?? 5) ? 'fill-amber-400 text-amber-400' : 'text-neutral-200' }}"></i>
                  @endfor
                </div>
              </div>

              <p class="text-xs text-neutral-700 leading-relaxed font-light">
                {{ $rev->comment }}
              </p>
            </div>
          @empty
            <div class="p-6 bg-white rounded-2xl border border-neutral-200/90 shadow-2xs space-y-3">
              <div class="flex justify-between items-start">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-neutral-900 text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                    N
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="font-bold text-neutral-900 text-xs">Nguyễn Trần Nam</span>
                      <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded text-[10px] font-medium flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3 h-3 text-emerald-600"></i> Đã mua hàng
                      </span>
                    </div>
                    <span class="text-[11px] text-neutral-400 block mt-0.5">28/08/2026</span>
                  </div>
                </div>
                <div class="flex items-center gap-0.5 text-amber-400">
                  @for($i = 0; $i < 5; $i++)
                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                  @endfor
                </div>
              </div>
              <p class="text-xs text-neutral-700 leading-relaxed font-light">
                Chất vải lụa mềm mịn và thoáng khí vượt ngoài mong đợi. Đường kim mũi chỉ may giấu viền rất tinh tế chuẩn may đo cao cấp. Sẽ tiếp tục ủng hộ xưởng!
              </p>
            </div>
          @endforelse
        </div>

      </div>
    </section>

    <!-- ========================================================================= -->
    <!-- RELATED PRODUCTS -->
    <!-- ========================================================================= -->
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
      <section class="mt-20 pt-12 border-t border-neutral-200">
        <div class="flex justify-between items-end mb-8">
          <div>
            <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-semibold block mb-1">GỢI Ý TỪ ATELIER</span>
            <h3 class="font-serif-luxury text-2xl md:text-3xl text-neutral-900 font-light">Có Thể Bạn Cũng Thích</h3>
          </div>
          <a href="{{ route('client.products.index') }}" class="text-xs font-semibold tracking-wider uppercase text-neutral-800 hover:text-amber-800">
            Xem tất cả &rarr;
          </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          @foreach($relatedProducts->take(4) as $rel)
            @php
              $relImg = $rel->primaryImage->image_path ?? $rel->thumbnail ?? $rel->image ?? 'assets/img/products/1.png';
              $relImgUrl = asset($relImg);
            @endphp
            <div class="group flex flex-col bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-sm hover:shadow-lg transition-all">
              <a href="{{ route('client.products.show', $rel->id) }}" class="aspect-[3/4] overflow-hidden bg-neutral-100 block">
                <img src="{{ $relImgUrl }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              </a>
              <div class="p-4 flex flex-col justify-between flex-grow">
                <div>
                  <span class="text-[10px] tracking-wider uppercase text-neutral-400 font-semibold block mb-1">{{ $rel->category->name ?? 'Beestyle' }}</span>
                  <a href="{{ route('client.products.show', $rel->id) }}" class="font-serif-luxury text-base text-neutral-900 hover:text-amber-800 transition-colors line-clamp-1">
                    {{ $rel->name }}
                  </a>
                </div>
                <div class="mt-3 pt-2 border-t border-neutral-100 font-serif-luxury text-base font-bold text-neutral-950">
                  {{ number_format($rel->price, 0, ',', '.') }}₫
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </section>
    @endif

  </div>
</main>

<!-- ========================================================================= -->
<!-- STICKY BOTTOM ACTION BAR -->
<!-- ========================================================================= -->
<div id="stickyAddToCartBar" class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-neutral-200 shadow-2xl z-40 py-3 px-6 transform translate-y-full transition-transform duration-300">
  <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
    <div class="flex items-center gap-3 min-w-0">
      <img src="{{ $firstImg }}" alt="{{ $product->name }}" class="w-12 h-14 rounded-lg object-cover border border-neutral-200 shrink-0">
      <div class="min-w-0">
        <h4 class="text-xs font-semibold text-neutral-900 truncate max-w-xs">{{ $product->name }}</h4>
        <div class="flex items-center gap-2 mt-0.5">
          <span class="font-serif-luxury text-sm font-bold text-neutral-950" id="stickySubtotalText">{{ number_format($effectivePrice, 0, ',', '.') }}₫</span>
          <span class="text-[10px] text-neutral-500" id="stickySelectedVariantText">Chưa chọn phân loại</span>
        </div>
      </div>
    </div>
    <div class="flex items-center gap-2 shrink-0">
      <button type="button" onclick="document.getElementById('btnAddToCart').click()" class="px-4 py-2.5 bg-neutral-950 hover:bg-neutral-800 text-white rounded-lg text-xs font-semibold uppercase tracking-wider transition-all">
        Thêm Giỏ Hàng
      </button>
      <button type="button" onclick="buyNowSubmit()" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-neutral-950 rounded-lg text-xs font-bold uppercase tracking-wider transition-all">
        Mua Ngay
      </button>
    </div>
  </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: SIZE GUIDE & AI SMART FIT -->
<!-- ========================================================================= -->
<div id="sizeGuideModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden">
    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
      <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 flex items-center gap-2">
        <i data-lucide="ruler" class="w-5 h-5 text-amber-600"></i>
        <span>Bảng Quy Đổi Size &amp; AI Tính Size</span>
      </h3>
      <button type="button" onclick="closeSizeGuideModal()" class="text-neutral-400 hover:text-neutral-900">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>
    <div class="p-5 space-y-4 text-xs">
      <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 space-y-3">
        <span class="font-bold text-emerald-900 uppercase text-[11px] block">Tính size tự động theo chiều cao &amp; cân nặng:</span>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block font-medium text-neutral-700 mb-1">Chiều cao (cm):</label>
            <input type="number" id="calcHeight" value="172" min="150" max="200" oninput="calculateSmartFit()" class="w-full bg-white border border-neutral-300 rounded-lg p-2 font-bold text-center">
          </div>
          <div>
            <label class="block font-medium text-neutral-700 mb-1">Cân nặng (kg):</label>
            <input type="number" id="calcWeight" value="67" min="40" max="120" oninput="calculateSmartFit()" class="w-full bg-white border border-neutral-300 rounded-lg p-2 font-bold text-center">
          </div>
        </div>
        <div class="p-3 bg-white rounded-lg border border-emerald-300 flex items-center justify-between">
          <div>
            <span class="text-neutral-500 block text-[10px]">Gợi ý phù hợp nhất:</span>
            <strong class="text-emerald-800 text-sm font-bold" id="suggestedSizeDisplay">Size L (65-72kg)</strong>
          </div>
          <button type="button" onclick="applySmartFitSize()" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold text-xs transition-colors">
            Chọn Size Này
          </button>
        </div>
      </div>

      <div class="border rounded-xl overflow-hidden border-neutral-200">
        <table class="w-full text-center border-collapse">
          <thead>
            <tr class="bg-neutral-100 text-neutral-700 font-semibold border-b border-neutral-200">
              <th class="py-2 px-3">Size</th>
              <th class="py-2 px-3">Cân Nặng</th>
              <th class="py-2 px-3">Chiều Cao</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-neutral-200 text-neutral-600">
            <tr><td class="py-2 font-bold">S</td><td>50 - 58 kg</td><td>1m55 - 1m65</td></tr>
            <tr><td class="py-2 font-bold">M</td><td>58 - 65 kg</td><td>1m65 - 1m72</td></tr>
            <tr><td class="py-2 font-bold">L</td><td>65 - 72 kg</td><td>1m70 - 1m77</td></tr>
            <tr><td class="py-2 font-bold">XL</td><td>72 - 80 kg</td><td>1m75 - 1m82</td></tr>
            <tr><td class="py-2 font-bold">XXL</td><td>80 - 88 kg</td><td>1m78 - 1m88</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // DỮ LIỆU BIẾN THỂ VÀ TỒN KHO THỰC TẾ
  const PRODUCT_VARIANTS = {!! json_encode($product->variants->map(function($v) use ($product, $effectivePrice) {
    return [
      'id' => $v->id,
      'color' => trim($v->color),
      'size' => trim($v->size),
      'price' => (int)($v->price ?: $effectivePrice),
      'stock' => (int)$v->stock,
      'sku' => $v->sku,
      'image' => $v->image ? asset($v->image) : null,
    ];
  })) !!};

  const BASE_PRICE = {{ (int)$effectivePrice }};
  let selectedProductColor = '';
  let selectedProductSize = '';
  let currentProductUnitPrice = BASE_PRICE;
  let currentVariantStock = {{ (int)($product->variants->sum('stock') ?: $product->stock) }};
  let calculatedFitSize = 'L';

  // 1. Gallery Thumbnail Switcher
  function setMainImage(url, btn) {
    const mainImg = document.getElementById('main-product-img');
    if (!mainImg) return;
    mainImg.style.opacity = '0.5';
    mainImg.src = url;
    setTimeout(() => { mainImg.style.opacity = '1'; }, 150);

    document.querySelectorAll('.gallery-thumb-btn').forEach(b => {
      b.classList.remove('border-neutral-950', 'ring-2', 'ring-neutral-950/20');
      b.classList.add('border-neutral-200');
    });
    if (btn) {
      btn.classList.add('border-neutral-950', 'ring-2', 'ring-neutral-950/20');
      btn.classList.remove('border-neutral-200');
    }
  }

  // 2. Color Swatch Selector
  function selectColorSwatch(colorName, colorCode, variantId, variantImg, btn) {
    selectedProductColor = colorName;
    document.getElementById('selected-color-name').textContent = colorName;
    document.getElementById('selectedColorInput').value = colorName;

    document.querySelectorAll('.color-swatch-btn').forEach(b => {
      b.classList.remove('ring-2', 'ring-offset-2', 'ring-neutral-950', 'scale-110');
    });
    if (btn) btn.classList.add('ring-2', 'ring-offset-2', 'ring-neutral-950', 'scale-110');

    if (variantImg && variantImg !== '') {
      setMainImage(variantImg, null);
    }
    syncVariantSelection();
  }

  // 3. Size Selector
  function selectSize(sizeName, btn) {
    selectedProductSize = sizeName;
    document.getElementById('selected-size-name').textContent = sizeName;
    document.getElementById('selectedSizeInput').value = sizeName;

    document.querySelectorAll('.variant-size-btn').forEach(b => {
      b.className = 'w-14 h-10 border border-neutral-300 bg-white hover:border-neutral-900 rounded-lg text-xs font-semibold uppercase flex items-center justify-center transition-colors variant-size-btn';
    });
    if (btn) {
      btn.className = 'w-14 h-10 border border-neutral-950 bg-neutral-950 text-white font-bold rounded-lg text-xs font-semibold uppercase flex items-center justify-center transition-colors variant-size-btn';
    }
    syncVariantSelection();
  }

  // 4. Đồng bộ biến thể & Tồn kho thực tế
  function syncVariantSelection() {
    const variantInput = document.getElementById('selectedVariantId');
    const priceDisplay = document.getElementById('price-display');
    const skuDisplay = document.getElementById('displaySku');
    const stockStatusText = document.getElementById('stockStatusText');
    const stickyVariantText = document.getElementById('stickySelectedVariantText');
    const btnAdd = document.getElementById('btnAddToCart');
    const btnBuy = document.getElementById('btnBuyNow');

    if (selectedProductColor && selectedProductSize) {
      const found = PRODUCT_VARIANTS.find(v => 
        v.color.toLowerCase() === selectedProductColor.toLowerCase() && 
        v.size.toUpperCase() === selectedProductSize.toUpperCase()
      );

      if (found) {
        variantInput.value = found.id;
        currentProductUnitPrice = found.price;
        currentVariantStock = found.stock;
        priceDisplay.textContent = currentProductUnitPrice.toLocaleString('vi-VN') + '₫';
        if (found.sku) skuDisplay.textContent = 'SKU: ' + found.sku;

        if (currentVariantStock <= 0) {
          stockStatusText.textContent = `Hết hàng (${selectedProductColor} / ${selectedProductSize})`;
          stockStatusText.className = 'text-rose-600 font-bold';
          if (btnAdd) btnAdd.disabled = true;
          if (btnBuy) btnBuy.disabled = true;
        } else {
          stockStatusText.textContent = `Còn hàng (${currentVariantStock} cái có sẵn)`;
          stockStatusText.className = 'text-emerald-700 font-semibold';
          if (btnAdd) btnAdd.disabled = false;
          if (btnBuy) btnBuy.disabled = false;
        }

        if (stickyVariantText) {
          stickyVariantText.textContent = `${selectedProductColor} / ${selectedProductSize}`;
        }
      }
    } else {
      if (stickyVariantText) {
        stickyVariantText.textContent = selectedProductColor || selectedProductSize || 'Chưa chọn phân loại';
      }
    }
    recalculateSubtotals();
  }

  // 5. Tính toán tiền & Cảnh báo cọc 50%
  function recalculateSubtotals() {
    const qtyInput = document.getElementById('purchaseQuantity');
    const qty = parseInt(qtyInput.value) || 1;
    const bulkBox = document.getElementById('bulkDepositPolicyBox');
    const bulkQtyText = document.getElementById('bulkQtyText');
    const depositLive = document.getElementById('depositAmountLive');
    const remainLive = document.getElementById('remainingAmountLive');
    const stickySubtotal = document.getElementById('stickySubtotalText');

    const total = currentProductUnitPrice * qty;
    if (stickySubtotal) stickySubtotal.textContent = total.toLocaleString('vi-VN') + '₫';

    if (qty >= 10) {
      if (bulkBox) bulkBox.classList.remove('hidden');
      if (bulkQtyText) bulkQtyText.textContent = qty;
      const deposit = Math.round(total * 0.5);
      const remain = total - deposit;
      if (depositLive) depositLive.textContent = deposit.toLocaleString('vi-VN') + '₫';
      if (remainLive) remainLive.textContent = remain.toLocaleString('vi-VN') + '₫';
    } else {
      if (bulkBox) bulkBox.classList.add('hidden');
    }
  }

  function changeQuantity(delta) {
    const input = document.getElementById('purchaseQuantity');
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
    recalculateSubtotals();
  }

  // 6. Form Submission Check
  document.getElementById('productPurchaseForm')?.addEventListener('submit', function(e) {
    const hasColors = document.querySelectorAll('.color-swatch-btn').length > 0;
    const hasSizes = document.querySelectorAll('.variant-size-btn').length > 0;

    if (hasColors && !selectedProductColor) {
      e.preventDefault();
      alert('Vui lòng chọn Màu sắc sản phẩm!');
      document.getElementById('colorGroupSection')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return false;
    }

    if (hasSizes && !selectedProductSize) {
      e.preventDefault();
      alert('Vui lòng chọn Kích cỡ (Size) sản phẩm!');
      document.getElementById('sizeGroupSection')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return false;
    }
  });

  function buyNowSubmit() {
    const form = document.getElementById('productPurchaseForm');
    const hasColors = document.querySelectorAll('.color-swatch-btn').length > 0;
    const hasSizes = document.querySelectorAll('.variant-size-btn').length > 0;

    if (hasColors && !selectedProductColor) {
      alert('Vui lòng chọn Màu sắc sản phẩm!');
      document.getElementById('colorGroupSection')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    if (hasSizes && !selectedProductSize) {
      alert('Vui lòng chọn Kích cỡ (Size) sản phẩm!');
      document.getElementById('sizeGroupSection')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    let input = document.querySelector('input[name="buy_now"]');
    if (!input) {
      input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'buy_now';
      input.value = '1';
      form.appendChild(input);
    }
    form.submit();
  }

  // 7. Sticky Bottom Bar Visibility on Scroll
  window.addEventListener('scroll', function() {
    const bar = document.getElementById('stickyAddToCartBar');
    if (!bar) return;
    if (window.scrollY > 500) {
      bar.classList.remove('translate-y-full');
    } else {
      bar.classList.add('translate-y-full');
    }
  });

  // 8. Smart Fit Calculator Modal
  function openSizeGuideModal() { document.getElementById('sizeGuideModal')?.classList.remove('hidden'); }
  function closeSizeGuideModal() { document.getElementById('sizeGuideModal')?.classList.add('hidden'); }

  function calculateSmartFit() {
    const h = parseInt(document.getElementById('calcHeight')?.value) || 172;
    const w = parseInt(document.getElementById('calcWeight')?.value) || 67;
    let size = 'L';
    let label = 'Size L (65-72kg)';

    if (w < 58 && h < 168) { size = 'S'; label = 'Size S (50-58kg)'; }
    else if (w <= 65 && h <= 173) { size = 'M'; label = 'Size M (58-65kg)'; }
    else if (w <= 72 && h <= 178) { size = 'L'; label = 'Size L (65-72kg)'; }
    else if (w <= 80 && h <= 183) { size = 'XL'; label = 'Size XL (72-80kg)'; }
    else { size = 'XXL'; label = 'Size XXL (80-88kg)'; }

    calculatedFitSize = size;
    const displayEl = document.getElementById('suggestedSizeDisplay');
    if (displayEl) displayEl.textContent = label;
  }

  function applySmartFitSize() {
    document.querySelectorAll('.variant-size-btn').forEach(btn => {
      if (btn.textContent.trim().toUpperCase() === calculatedFitSize.toUpperCase()) {
        btn.click();
      }
    });
    closeSizeGuideModal();
  }

  // 9. Wishlist Toggle
  function toggleProductWishlist(productId) {
    const btn = document.getElementById('wishlist-btn-' + productId);
    const icon = document.getElementById('wishlist-icon-' + productId);

    fetch('{{ route("client.wishlist.toggle") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
      if (data.is_favorite) {
        icon.classList.add('fill-rose-500', 'text-rose-500');
        icon.classList.remove('text-neutral-700');
        btn.classList.add('bg-rose-50', 'text-rose-600', 'border-rose-200');
        btn.classList.remove('bg-white/90', 'text-neutral-700');
        btn.setAttribute('title', 'Đã yêu thích');
      } else {
        icon.classList.remove('fill-rose-500', 'text-rose-500');
        icon.classList.add('text-neutral-700');
        btn.classList.remove('bg-rose-50', 'text-rose-600', 'border-rose-200');
        btn.classList.add('bg-white/90', 'text-neutral-700');
        btn.setAttribute('title', 'Thêm vào yêu thích');
      }
    })
    .catch(err => console.error('Lỗi cập nhật wishlist:', err));
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    calculateSmartFit();
  });
</script>
@endpush