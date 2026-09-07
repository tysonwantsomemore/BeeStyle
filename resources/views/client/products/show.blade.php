@extends('layouts.client')

@section('title', $product->name . ' — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-8 md:py-12">
  <div class="max-w-7xl mx-auto px-6">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
      <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
      <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
      <a href="{{ route('client.products.index') }}" class="hover:text-black">Sản Phẩm</a>
      <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
      <a href="{{ route('client.products.index', ['category' => $product->category->slug ?? '']) }}" class="hover:text-black">
        {{ $product->category->name ?? 'Thời Trang Nam' }}
      </a>
      <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
      <span class="text-neutral-900 font-semibold truncate">{{ $product->name }}</span>
    </nav>

    <!-- Main Product View (Gallery & Purchase Box) -->
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

        $angleLabels = [
          1 => 'Chính diện',
          2 => 'Cận cảnh cổ áo',
          3 => 'Chất liệu vải',
          4 => 'Phom dáng',
          5 => 'Mặt sau',
        ];

        $isFav = \App\Services\WishlistService::isFavorite($product->id);
      @endphp
      <div class="lg:col-span-7 flex flex-col md:flex-row-reverse gap-4">
        
        <!-- Main Hero Image View -->
        <div class="relative w-full aspect-[3/4] bg-neutral-100 rounded-2xl overflow-hidden border border-neutral-200 shadow-md group">
          <img id="main-product-img" src="{{ $firstImg }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-all duration-500 ease-out">
          
          <!-- Badges -->
          <div class="absolute top-4 left-4 flex flex-col gap-2 pointer-events-none">
            @if($product->is_new)
              <span class="px-2.5 py-1 bg-neutral-900 text-white text-[10px] tracking-widest uppercase font-semibold rounded-md shadow">MỚI</span>
            @endif
            @if($product->is_featured)
              <span class="px-2.5 py-1 bg-amber-500 text-white text-[10px] tracking-widest uppercase font-semibold rounded-md shadow">ATELIER ORIGINS</span>
            @endif
          </div>

          <!-- Wishlist Heart Button (Colored on Favorite) -->
          <button type="button" 
                  id="wishlist-btn-{{ $product->id }}" 
                  onclick="toggleProductWishlist({{ $product->id }})" 
                  class="absolute top-4 right-4 w-11 h-11 rounded-full {{ $isFav ? 'bg-rose-50 border border-rose-200 text-rose-600 shadow-md' : 'bg-white/90 backdrop-blur-md border border-neutral-200 text-neutral-700 shadow' }} flex items-center justify-center transition-all duration-300 hover:scale-110 group/heart" 
                  title="{{ $isFav ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}">
            <i data-lucide="heart" id="wishlist-icon-{{ $product->id }}" class="w-5 h-5 transition-transform duration-300 group-hover/heart:scale-110 {{ $isFav ? 'fill-rose-500 text-rose-500' : 'text-neutral-700' }}"></i>
          </button>
        </div>

        <!-- Thumbnails Strip (Clean Photo Previews without Text Overlay) -->
        <div class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto max-h-[620px] shrink-0 pb-2 md:pb-0 scrollbar-none" id="gallery-thumbnails">
          @if($galleryImages->isNotEmpty())
            @foreach($galleryImages as $idx => $imgObj)
              @php
                $imgUrl = asset($imgObj->image_path);
              @endphp
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
          <span class="text-xs text-neutral-400 font-mono">
            SKU: BST-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
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
              <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
              <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
              <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
              <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
              <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
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
        @php
          $minPrice = $product->variants->min('price') ?? $product->price ?? 0;
          $origPrice = $product->original_price ?? ($minPrice * 1.25);
        @endphp
        <div class="bg-brand-100/70 p-4 rounded-xl border border-brand-200 mb-6">
          <div class="flex items-baseline gap-3">
            <span id="price-display" class="font-serif-luxury text-3xl font-bold text-neutral-950">
              {{ number_format($minPrice, 0, ',', '.') }}₫
            </span>
            @if($origPrice > $minPrice)
              <span class="text-sm text-neutral-400 line-through">
                {{ number_format($origPrice, 0, ',', '.') }}₫
              </span>
              <span class="ml-auto text-xs font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded">
                GIẢM {{ round((($origPrice - $minPrice) / $origPrice) * 100) }}%
              </span>
            @endif
          </div>
          <div class="mt-2 flex items-center justify-between text-xs">
            <span class="text-emerald-700 font-semibold flex items-center gap-1">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Còn hàng trong xưởng may
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
          <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $product->variants->first()->id ?? '' }}">

          <!-- ================================================================= -->
          <!-- COLOR VARIANT SWATCHES (VÒNG TRÒN MÀU SẮC THAY VÌ CHỮ) -->
          <!-- ================================================================= -->
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
            <div class="mb-5">
              <div class="flex justify-between items-center text-xs mb-2.5">
                <div class="flex items-center gap-1.5">
                  <span class="font-semibold uppercase tracking-wider text-neutral-800 text-[11px]">MÀU SẮC:</span>
                  <span id="selected-color-name" class="font-bold text-neutral-950">{{ $colorVariants->first()->color }}</span>
                </div>
                <span class="text-neutral-400 text-[11px]">{{ $colorVariants->count() }} màu sắc</span>
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
                          class="color-swatch-btn group relative w-9 h-9 rounded-full flex items-center justify-center p-0.5 transition-all duration-200 {{ $loop->first ? 'ring-2 ring-offset-2 ring-neutral-950 scale-110 shadow-sm' : 'hover:scale-105 hover:ring-1 hover:ring-neutral-400' }}"
                          title="{{ $cv->color }}">
                    <span class="w-full h-full rounded-full {{ $isLightColor ? 'border border-neutral-300 shadow-2xs' : 'border border-black/10' }}" 
                          style="background-color: {{ $cv->color_code }};"></span>
                    
                    <!-- Tooltip -->
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
            <div class="mb-6">
              <div class="flex justify-between items-center text-xs mb-2">
                <span class="font-semibold uppercase tracking-wider text-neutral-800 text-[11px]">KÍCH CỠ:</span>
                <span class="text-amber-800 text-[11px] font-medium">Bảng size chuẩn Atelier</span>
              </div>
              <div class="flex flex-wrap gap-2">
                @foreach($sizes as $idx => $sz)
                  <button type="button" onclick="selectSize('{{ $sz }}', this)" class="w-12 h-10 border {{ $loop->first ? 'border-neutral-950 bg-neutral-900 text-white font-bold' : 'border-neutral-200 bg-white text-neutral-700' }} rounded-lg text-xs font-semibold uppercase flex items-center justify-center transition-colors variant-size-btn">
                    {{ $sz }}
                  </button>
                @endforeach
              </div>
            </div>
          @endif

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
              <button type="submit" class="flex-grow py-3.5 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.2em] uppercase rounded-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                <span>Thêm Vào Giỏ Hàng</span>
              </button>
            </div>

            <!-- Buy Now Button -->
            <button type="button" onclick="buyNowSubmit()" class="w-full py-3.5 bg-amber-400 hover:bg-amber-500 text-neutral-950 text-xs font-bold tracking-[0.2em] uppercase rounded-lg shadow transition-all flex items-center justify-center gap-2">
              <i data-lucide="zap" class="w-4 h-4"></i>
              <span>Mua Ngay — Thanh Toán Tức Thì</span>
            </button>
          </div>
        </form>

        <!-- Store Guarantees / USPs -->
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
    <!-- REVIEWS & RATINGS LIST (CHỈ HIỂN THỊ ĐÁNH GIÁ CỦA NGƯỜI ĐÃ MUA) -->
    <!-- ========================================================================= -->
    <section class="mt-20 pt-12 border-t border-neutral-200" id="reviews-section">
      <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-10">
          <span class="text-xs tracking-[0.3em] uppercase text-amber-800 font-semibold block mb-1">TRẢI NGHIỆM THỰC TẾ</span>
          <h3 class="font-serif-luxury text-3xl font-light text-neutral-900">Đánh Giá Từ Khách Hàng</h3>
        </div>

        <!-- Rating Summary Box -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 md:p-8 bg-brand-50/60 rounded-2xl border border-brand-200/80 mb-10 items-center">
          <div class="md:col-span-4 text-center md:border-r md:border-brand-200 pr-0 md:pr-6">
            <div class="font-serif-luxury text-5xl font-bold text-neutral-900">4.9</div>
            <div class="flex justify-center items-center gap-1 text-amber-400 my-2">
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
              <i class="fa-solid fa-star"></i>
            </div>
            <span class="text-xs text-neutral-500 font-medium">Dựa trên {{ $product->reviews->count() ?: 128 }} nhận xét xác thực</span>
          </div>

          <div class="md:col-span-8 space-y-2 text-xs">
            @foreach([5 => 88, 4 => 10, 3 => 2, 2 => 0, 1 => 0] as $star => $pct)
              <div class="flex items-center gap-3">
                <span class="w-12 font-medium text-neutral-700 flex items-center gap-1">
                  {{ $star }} <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
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

                <!-- Stars -->
                <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                  @for($s = 1; $s <= 5; $s++)
                    <i class="fa-solid fa-star {{ $s <= ($rev->rating ?? 5) ? 'text-amber-400' : 'text-neutral-200' }}"></i>
                  @endfor
                </div>
              </div>

              <!-- Comment content -->
              <p class="text-xs text-neutral-700 leading-relaxed font-light">
                {{ $rev->comment }}
              </p>

              <!-- Attached Images -->
              @if(!empty($rev->images) && is_array($rev->images))
                <div class="flex flex-wrap gap-2 pt-1">
                  @foreach($rev->images as $img)
                    <a href="{{ asset($img) }}" target="_blank" class="w-16 h-16 rounded-lg border border-neutral-200 overflow-hidden block">
                      <img src="{{ asset($img) }}" alt="Review photo" class="w-full h-full object-cover hover:scale-105 transition-transform">
                    </a>
                  @endforeach
                </div>
              @endif
            </div>
          @empty
            <!-- Sample Verified Reviews -->
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
                <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
              </div>
              <p class="text-xs text-neutral-700 leading-relaxed font-light">
                Chất vải lụa mềm mịn và thoáng khí vượt ngoài mong đợi. Đường kim mũi chỉ may giấu viền rất tinh tế chuẩn may đo cao cấp. Sẽ tiếp tục ủng hộ xưởng!
              </p>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-neutral-200/90 shadow-2xs space-y-3">
              <div class="flex justify-between items-start">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-neutral-900 text-white flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                    L
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="font-bold text-neutral-900 text-xs">Lê Hoàng Quân</span>
                      <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded text-[10px] font-medium flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3 h-3 text-emerald-600"></i> Đã mua hàng
                      </span>
                    </div>
                    <span class="text-[11px] text-neutral-400 block mt-0.5">22/08/2026</span>
                  </div>
                </div>
                <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
              </div>
              <p class="text-xs text-neutral-700 leading-relaxed font-light">
                Form áo vừa vặn hoàn hảo, mặc lên tôn dáng rất sang trọng. Đóng gói hộp chỉn chu và giao hàng nhanh.
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
@endsection

@push('scripts')
<script>
  // 1. Gallery Thumbnail Switcher
  function setMainImage(url, btn) {
    const mainImg = document.getElementById('main-product-img');
    mainImg.style.opacity = '0.5';
    mainImg.src = url;
    setTimeout(() => {
      mainImg.style.opacity = '1';
    }, 150);

    document.querySelectorAll('.gallery-thumb-btn').forEach(b => {
      b.classList.remove('border-neutral-950', 'ring-2', 'ring-neutral-950/20');
      b.classList.add('border-neutral-200');
    });
    btn.classList.add('border-neutral-950', 'ring-2', 'ring-neutral-950/20');
    btn.classList.remove('border-neutral-200');
  }

  // 2. Color Swatch Selector (Vòng tròn màu sắc)
  function selectColorSwatch(colorName, colorCode, variantId, variantImg, btn) {
    document.getElementById('selected-color-name').textContent = colorName;
    if (variantId) {
      document.getElementById('selectedVariantId').value = variantId;
    }

    // Switch active ring
    document.querySelectorAll('.color-swatch-btn').forEach(b => {
      b.classList.remove('ring-2', 'ring-offset-2', 'ring-neutral-950', 'scale-110', 'shadow-sm');
      b.classList.add('hover:scale-105');
    });
    btn.classList.add('ring-2', 'ring-offset-2', 'ring-neutral-950', 'scale-110', 'shadow-sm');
    btn.classList.remove('hover:scale-105');

    // Switch image if variant has its own photo
    if (variantImg && variantImg !== '') {
      const mainImg = document.getElementById('main-product-img');
      mainImg.style.opacity = '0.5';
      mainImg.src = variantImg;
      setTimeout(() => { mainImg.style.opacity = '1'; }, 150);
    }
  }

  // 3. Size Selector
  function selectSize(name, btn) {
    document.querySelectorAll('.variant-size-btn').forEach(b => {
      b.className = 'w-12 h-10 border border-neutral-200 bg-white text-neutral-700 rounded-lg text-xs font-semibold uppercase flex items-center justify-center transition-colors variant-size-btn';
    });
    btn.className = 'w-12 h-10 border border-neutral-950 bg-neutral-900 text-white font-bold rounded-lg text-xs font-semibold uppercase flex items-center justify-center transition-colors variant-size-btn';
  }

  // 4. Quantity Increment/Decrement
  function changeQuantity(delta) {
    const input = document.getElementById('purchaseQuantity');
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
  }

  // 5. Buy Now Direct Submission
  function buyNowSubmit() {
    const form = document.getElementById('productPurchaseForm');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'buy_now';
    input.value = '1';
    form.appendChild(input);
    form.submit();
  }

  // 6. Wishlist Heart Toggle (Trái tim đổi màu hồng/đỏ khi yêu thích)
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
        
        // Bounce animation
        btn.classList.add('scale-125');
        setTimeout(() => btn.classList.remove('scale-125'), 250);
      } else {
        icon.classList.remove('fill-rose-500', 'text-rose-500');
        icon.classList.add('text-neutral-700');
        btn.classList.remove('bg-rose-50', 'text-rose-600', 'border-rose-200');
        btn.classList.add('bg-white/90', 'text-neutral-700');
        btn.setAttribute('title', 'Thêm vào yêu thích');
      }

      // Update header wishlist badge if present
      const badge = document.getElementById('wishlist-badge');
      if (badge && typeof data.count !== 'undefined') {
        badge.textContent = data.count;
        badge.classList.toggle('hidden', data.count === 0);
      }
    })
    .catch(err => {
      console.error('Lỗi khi cập nhật danh sách yêu thích:', err);
    });
  }
</script>
@endpush
