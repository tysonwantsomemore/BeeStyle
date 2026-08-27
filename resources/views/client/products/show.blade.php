@extends('layouts.client')

@section('title', $product->name . ' | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumbs -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('client.products.index', ['category' => $product->category->slug ?? '']) }}" class="text-decoration-none text-muted">{{ $product->category->name ?? 'Thời trang nam' }}</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $product->name }}</li>
    </ol>
  </nav>



  <!-- PRODUCT MAIN SECTION -->
  <div class="card border-0 shadow-sm p-4 p-md-5 mb-5" style="border-radius: 20px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
    <div class="row g-4 g-lg-5">
      
      <!-- IMAGE GALLERY (CHUẨN TMĐT CHUYÊN NGHIỆP: HOVER ZOOM, ARROWS, LIGHTBOX, THUMBNAIL FILMSTRIP) -->
      @php
        $allGalleryImages = collect([$product->image]);
        if ($product->images) {
          foreach ($product->images as $pImg) {
            if ($pImg->image_path && $pImg->image_path !== $product->image) {
              $allGalleryImages->push($pImg->image_path);
            }
          }
        }
        $allGalleryImages = $allGalleryImages->unique()->values();
      @endphp
      <div class="col-lg-6">
        <!-- MAIN IMAGE VIEWER WITH HOVER ZOOM & NAVIGATION CHEVRONS -->
        <div class="position-relative bg-light rounded-4 overflow-hidden mb-3 border shadow-xs main-gallery-container" 
             id="mainImgViewer" 
             style="height: 460px; display: flex; align-items: center; justify-content: center; cursor: zoom-in; user-select: none;"
             onclick="openGalleryModal(currentImgIndex)">
          
          <!-- BADGES (TOP-LEFT) -->
          <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1.5 z-2 pointer-events-none">
            @if($product->original_price && $product->original_price > $product->price)
              <span class="badge bg-danger fs-6 px-3 py-1.5 rounded-pill shadow-sm fw-bold">
                <i class="fa-solid fa-fire me-1"></i> -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
              </span>
            @endif
            @if($product->is_best_seller)
              <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill small fw-bold shadow-xs">
                <i class="fa-solid fa-crown me-1"></i> Bán Chạy Nhất
              </span>
            @endif
            @if($product->is_new)
              <span class="badge bg-primary text-white px-2.5 py-1 rounded-pill small fw-bold shadow-xs">
                <i class="fa-solid fa-sparkles me-1"></i> Mới Về
              </span>
            @endif
          </div>

          <!-- ACTIONS (TOP-RIGHT: WISHLIST & EXPAND) -->
          <div class="position-absolute top-0 end-0 m-3 d-flex flex-column gap-2 z-2">
            <button type="button" class="btn btn-white bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center border transition-all hover-scale" 
                    style="width: 38px; height: 38px;" title="Phóng to ảnh (Fullscreen Lightbox)" onclick="event.stopPropagation(); openGalleryModal(currentImgIndex)">
              <i class="fa-solid fa-expand text-dark"></i>
            </button>
            <form action="{{ route('client.wishlist.toggle') }}" method="POST" class="d-inline" onclick="event.stopPropagation();">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <button type="submit" class="btn btn-white bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center border transition-all hover-scale" style="width: 38px; height: 38px;" title="Yêu thích">
                <i class="fa-heart {{ Auth::check() && Auth::user()->wishlist && Auth::user()->wishlist->contains('product_id', $product->id) ? 'fa-solid text-danger' : 'fa-regular text-muted' }}"></i>
              </button>
            </form>
          </div>

          <!-- CHEVRON NAVIGATION ARROWS (HOVER REVEAL) -->
          @if($allGalleryImages->count() > 1)
            <button type="button" class="btn btn-dark bg-opacity-60 text-white rounded-circle position-absolute top-50 start-0 translate-middle-y ms-2.5 z-2 d-flex align-items-center justify-content-center shadow-xs transition-all hover-scale" 
                    style="width: 40px; height: 40px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(6px);" onclick="event.stopPropagation(); prevGalleryImg();" title="Ảnh trước (←)">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button type="button" class="btn btn-dark bg-opacity-60 text-white rounded-circle position-absolute top-50 end-0 translate-middle-y me-2.5 z-2 d-flex align-items-center justify-content-center shadow-xs transition-all hover-scale" 
                    style="width: 40px; height: 40px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(6px);" onclick="event.stopPropagation(); nextGalleryImg();" title="Ảnh tiếp theo (→)">
              <i class="fa-solid fa-chevron-right"></i>
            </button>
          @endif

          <!-- MAIN IMAGE ELEMENT WITH ZOOM CONTAINER -->
          <div class="w-100 h-100 position-relative overflow-hidden d-flex align-items-center justify-content-center p-3" id="zoomContainer">
            <img id="mainProductImg" src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                 class="img-fluid rounded-3" 
                 style="max-height: 420px; width: 100%; object-fit: contain; transition: transform 0.15s ease-out, transform-origin 0.15s ease-out;">
          </div>

          <!-- FOOTER HINT ON MAIN IMAGE -->
          <div class="position-absolute bottom-0 start-50 translate-middle-x mb-2.5 badge bg-dark bg-opacity-70 text-white px-3 py-1.5 rounded-pill small z-2 shadow-xs d-flex align-items-center gap-1.5 pointer-events-none" style="backdrop-filter: blur(6px); font-size: 0.72rem;">
            <i class="fa-solid fa-magnifying-glass-plus text-warning"></i>
            <span>Rê chuột để phóng to 2x • Nhấp để xem toàn màn hình</span>
          </div>
        </div>

        <!-- THUMBNAILS CAROUSEL WITH ACTIVE INDICATOR & ANGLE LABELS -->
        @php
          if (!function_exists('getProductAngleTag')) {
            function getProductAngleTag($path, $idx) {
              $p = strtolower($path);
              if (str_contains($p, 'collar')) return 'Cổ & Ngực';
              if (str_contains($p, 'fabric')) return 'Chất vải';
              if (str_contains($p, 'fit')) return 'Phom dáng';
              if (str_contains($p, 'front')) return 'Mặt trước';
              if (str_contains($p, 'back')) return 'Mặt sau';
              if (str_contains($p, 'side') || str_contains($p, 'pose') || str_contains($p, 'model')) return 'Dáng mẫu';
              if ($idx === 0) return 'Toàn cảnh';
              if ($idx === 1) return 'Cổ & Ngực';
              if ($idx === 2) return 'Chất vải';
              if ($idx === 3) return 'Phom dáng';
              return 'Góc ' . ($idx + 1);
            }
          }
        @endphp
        @if($allGalleryImages->count() > 1)
          <div class="position-relative">
            <div class="d-flex gap-2 justify-content-start overflow-x-auto py-1 px-1 scrollbar-none" id="thumbStrip" style="scroll-behavior: smooth;">
              @foreach($allGalleryImages as $idx => $imgSrc)
                <div class="border rounded-3 p-1 bg-white flex-shrink-0 cursor-pointer thumb-item transition-all position-relative overflow-hidden {{ $idx === 0 ? 'border-warning border-2 shadow-sm ring-1 ring-warning' : 'border-muted' }}" 
                     style="width: 78px; height: 78px; cursor: pointer; border-radius: 12px !important;" 
                     onclick="setGalleryIndex({{ $idx }})"
                     title="{{ getProductAngleTag($imgSrc, $idx) }}">
                  <img src="{{ asset($imgSrc) }}" alt="thumb {{ $idx }}" class="w-100 h-100 rounded object-fit-cover">
                  <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-dark bg-opacity-75 text-white px-1 py-0.5 rounded-pill mb-1 shadow-xs" style="font-size: 0.58rem; white-space: nowrap; pointer-events: none; backdrop-filter: blur(2px);">
                    {{ getProductAngleTag($imgSrc, $idx) }}
                  </span>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- GUARANTEE TRUST BADGES UNDER GALLERY -->
        <div class="row g-2 mt-3 text-center small">
          <div class="col-4">
            <div class="p-2 rounded-3 bg-light border text-muted d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.74rem;">
              <i class="fa-solid fa-camera text-warning"></i> 100% Ảnh Chụp Thật
            </div>
          </div>
          <div class="col-4">
            <div class="p-2 rounded-3 bg-light border text-muted d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.74rem;">
              <i class="fa-solid fa-rotate-left text-success"></i> 7 Ngày Đổi Trả
            </div>
          </div>
          <div class="col-4">
            <div class="p-2 rounded-3 bg-light border text-muted d-flex align-items-center justify-content-center gap-1.5" style="font-size: 0.74rem;">
              <i class="fa-solid fa-shield-halved text-primary"></i> Bảo Hành Chuẩn
            </div>
          </div>
        </div>
      </div>

      <!-- PRODUCT INFO & ACTIONS -->
      <div class="col-lg-6">
        <div class="ps-lg-3">
          <!-- Top Category & SKU -->
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1">{{ $product->category->name ?? 'Thời trang nam' }}</span>
            <span class="text-muted small ms-auto">SKU: <strong id="displaySku" class="text-dark font-monospace">{{ $product->sku }}</strong></span>
          </div>

          <h1 class="fw-bold text-dark mb-2" style="font-size: 1.5rem; line-height: 1.3; font-family: var(--atino-font-heading);">
            {{ $product->name }}
          </h1>

          <!-- RATING, REVIEWS & VIEW COUNT SUMMARY -->
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            <div class="text-warning small">
              @for($i=1; $i<=5; $i++)
                <i class="fa-solid fa-star {{ $i <= round($product->rating) ? 'text-warning' : 'text-secondary-subtle' }}"></i>
              @endfor
            </div>
            <span class="small fw-bold text-dark">{{ number_format($product->rating, 1) }}</span>
            <span class="text-muted small">•</span>
            <a href="#reviews" class="text-muted small text-decoration-underline" onclick="event.preventDefault(); var t=new bootstrap.Tab(document.getElementById('reviews-tab')); t.show(); document.getElementById('reviews-tab').scrollIntoView({behavior: 'smooth'});">
              {{ $product->reviews->count() }} đánh giá
            </a>
            <span class="text-muted small">•</span>
            <span class="small text-muted">Đã bán <strong>{{ number_format($product->sold_count ?? 128) }}</strong></span>
            <span class="text-muted small">•</span>
            <span class="small text-muted"><i class="fa-regular fa-eye text-primary me-1"></i><strong>{{ number_format($product->views ?? 256) }}</strong> lượt xem</span>
          </div>

          <!-- SOCIAL PROOF & LIVE ACTIVITY BADGE -->
          <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            <div class="d-inline-flex align-items-center gap-1.5 py-1 px-2.5 rounded-pill bg-danger-subtle text-danger border border-danger-subtle small fw-semibold" style="font-size: 0.76rem;">
              <span class="spinner-grow spinner-grow-sm text-danger" style="width: 7px; height: 7px;"></span>
              <span>Đang có <strong id="liveViewerCount">{{ rand(9, 24) }}</strong> người cùng xem</span>
            </div>
            @if($product->stock <= 15 && $product->stock > 0)
              <div class="d-inline-flex align-items-center gap-1.5 py-1 px-2.5 rounded-pill bg-warning-subtle text-dark border border-warning-subtle small fw-bold" style="font-size: 0.76rem;">
                <i class="fa-solid fa-bolt text-danger"></i>
                <span>Chỉ còn lại <strong class="text-danger">{{ $product->stock }}</strong> sản phẩm</span>
              </div>
            @endif
          </div>

          <!-- PRICE -->
          <div class="p-3 bg-light rounded-3 mb-3 d-flex align-items-baseline gap-3 flex-wrap">
            <h2 class="fw-bold text-danger mb-0" id="displayPrice" style="font-family: var(--atino-font-heading);">
              {{ number_format($product->price, 0, ',', '.') }}₫
            </h2>
            @if($product->original_price && $product->original_price > $product->price)
              <span class="text-muted text-decoration-line-through fs-6" id="displayOriginalPrice">
                {{ number_format($product->original_price, 0, ',', '.') }}₫
              </span>
              <span class="badge bg-danger-subtle text-danger fw-bold" id="displayDiscountBadge">
                Tiết kiệm {{ number_format($product->original_price - $product->price, 0, ',', '.') }}₫
              </span>
            @endif
          </div>

          <!-- MÃ GIẢM GIÁ / VOUCHER CỦA SHOP (SHOPEE STYLE) -->
          @if(isset($availableCoupons) && $availableCoupons->count() > 0)
            <div class="p-3 rounded-3 mb-3 border" style="background: #fffbeb; border-color: #fde68a !important;">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small fw-bold text-dark d-flex align-items-center gap-1.5">
                  <i class="fa-solid fa-ticket text-warning fs-6"></i>
                  <span>Mã Giảm Giá Của Shop:</span>
                </span>
                <span class="badge bg-warning text-dark font-monospace fw-bold" style="font-size: 0.72rem;">Áp dụng khi thanh toán</span>
              </div>
              <div class="d-flex gap-2 overflow-x-auto pb-1 scrollbar-none">
                @foreach($availableCoupons as $cpn)
                  <div class="d-inline-flex align-items-center gap-2 p-1.5 px-2.5 bg-white border border-warning rounded-2 shadow-xs flex-shrink-0 cursor-pointer transition-all hover-lift" 
                       onclick="copyCouponCode('{{ $cpn->code }}')" 
                       title="Nhấp để sao chép mã {{ $cpn->code }}">
                    <div class="d-flex flex-column">
                      <span class="fw-bold text-danger font-monospace fs-12">{{ $cpn->code }}</span>
                      <small class="text-muted" style="font-size: 0.68rem;">
                        @if($cpn->discount_type === 'percent')
                          Giảm {{ $cpn->discount_value }}% (Đơn từ {{ number_format($cpn->min_order_value, 0, ',', '.') }}₫)
                        @else
                          Giảm {{ number_format($cpn->discount_value, 0, ',', '.') }}₫ (Đơn từ {{ number_format($cpn->min_order_value, 0, ',', '.') }}₫)
                        @endif
                      </small>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-xs py-0.5 px-2 rounded-pill fw-semibold" style="font-size: 0.68rem;">
                      Lưu mã
                    </button>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <!-- SHORT DESCRIPTION -->
          @if($product->short_description)
            <p class="text-muted small mb-4 leading-relaxed">
              {{ $product->short_description }}
            </p>
          @endif

          <!-- FORM ADD TO CART -->
          <!-- FORM ADD TO CART -->
          <form action="{{ route('client.cart.add') }}" method="POST" id="productForm" onsubmit="return handleProductFormSubmit(event);">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <!-- COLOR SELECTION -->
            @php
              $prodColors = is_array($product->colors) ? $product->colors : ['Đen', 'Trắng', 'Xanh Navy'];
              if (!function_exists('getShowColorHex')) {
                function getShowColorHex($name) {
                  $c = mb_strtolower(trim($name));
                  if (str_contains($c, 'đen') || str_contains($c, 'black')) return '#0f172a';
                  if (str_contains($c, 'trắng') || str_contains($c, 'white')) return '#ffffff';
                  if (str_contains($c, 'navy') || str_contains($c, 'than')) return '#1e3a8a';
                  if (str_contains($c, 'xám ghi') || str_contains($c, 'ghi') || str_contains($c, 'xám')) return '#64748b';
                  if (str_contains($c, 'đỏ') || str_contains($c, 'burgundy')) return '#881337';
                  if (str_contains($c, 'be') || str_contains($c, 'khaki')) return '#d4b996';
                  if (str_contains($c, 'rêu') || str_contains($c, 'olive')) return '#365314';
                  if (str_contains($c, 'nâu') || str_contains($c, 'coffee')) return '#78350f';
                  if (str_contains($c, 'vàng')) return '#d97706';
                  return '#334155';
                }
              }
              if (!function_exists('getShowSizeHint')) {
                function getShowSizeHint($sz) {
                  $s = strtoupper(trim($sz));
                  if ($s === 'S') return '50-58kg';
                  if ($s === 'M') return '58-65kg';
                  if ($s === 'L') return '65-72kg';
                  if ($s === 'XL') return '72-80kg';
                  if ($s === 'XXL' || $s === '2XL') return '80-88kg';
                  if ($s === '3XL') return '> 88kg';
                  return 'Chuẩn form';
                }
              }
            @endphp
            @if(count($prodColors) > 0)
              <div class="mb-3.5 p-3 rounded-3 border" id="colorGroupSection" style="transition: all 0.3s ease; background: #ffffff;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="form-label small fw-bold text-dark mb-0">
                    <i class="fa-solid fa-palette text-warning me-1"></i> 1. Chọn Màu Sắc:
                    <span class="badge bg-light text-muted border px-2 py-0.5 ms-1 fw-bold" id="selectedColorText">Chưa chọn</span>
                  </label>
                  <span class="badge bg-danger-subtle text-danger fw-bold fs-11">* Bắt buộc chọn</span>
                </div>
                <div class="d-flex flex-wrap gap-2" id="colorOptionList">
                  @foreach($prodColors as $c)
                    @php
                      $cHex = getShowColorHex($c);
                      $isWhite = ($cHex === '#ffffff');
                    @endphp
                    <input type="radio" class="btn-check product-color-radio" name="color" id="color_{{ $loop->index }}" value="{{ $c }}" onchange="selectProductColor(this.value)">
                    <label class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2 rounded-pill px-3 py-1.5 fw-semibold shadow-xs" for="color_{{ $loop->index }}" style="font-size: 0.84rem;">
                      <span class="rounded-circle d-inline-block border" style="width: 15px; height: 15px; background-color: {{ $cHex }}; border-color: {{ $isWhite ? '#cbd5e1' : 'transparent' }} !important;"></span>
                      <span>{{ $c }}</span>
                    </label>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- SIZE SELECTION -->
            @php
              $prodSizes = is_array($product->sizes) ? $product->sizes : ['M', 'L', 'XL', 'XXL'];
            @endphp
            @if(count($prodSizes) > 0)
              <div class="mb-3.5 p-3 rounded-3 border" id="sizeGroupSection" style="transition: all 0.3s ease; background: #ffffff;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="form-label small fw-bold text-dark mb-0">
                    <i class="fa-solid fa-ruler-combined text-warning me-1"></i> 2. Chọn Kích Thước (Size):
                    <span class="badge bg-light text-muted border px-2 py-0.5 ms-1 fw-bold" id="selectedSizeText">Chưa chọn</span>
                  </label>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger-subtle text-danger fw-bold fs-11">* Bắt buộc chọn</span>
                    <button type="button" class="btn btn-link text-decoration-none p-0 small text-danger fw-bold" data-bs-toggle="modal" data-bs-target="#sizeGuideModal">
                      <i class="fa-solid fa-ruler-horizontal me-1"></i> Bảng Size
                    </button>
                  </div>
                </div>
                <div class="d-flex flex-wrap gap-2" id="sizeOptionList">
                  @foreach($prodSizes as $s)
                    <input type="radio" class="btn-check product-size-radio" name="size" id="size_{{ $loop->index }}" value="{{ $s }}" onchange="selectProductSize(this.value, '{{ getShowSizeHint($s) }}')">
                    <label class="btn btn-outline-secondary btn-sm d-flex flex-column align-items-center justify-content-center rounded-3 p-1.5 shadow-xs" for="size_{{ $loop->index }}" style="min-width: 64px; height: 48px;">
                      <span class="fw-bold fs-6 lh-1">{{ $s }}</span>
                      <span class="text-muted lh-1 mt-1" style="font-size: 0.65rem;">{{ getShowSizeHint($s) }}</span>
                    </label>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- QUANTITY STEPPER (GIỚI HẠN TỐI ĐA 10 SẢN PHẨM) -->
            <div class="mb-4 p-3.5 rounded-3 border" style="background: #f8fafc;">
              <div class="d-flex justify-content-between align-items-center mb-2.5">
                <label class="form-label small fw-bold text-dark mb-0 d-flex align-items-center gap-1.5">
                  <i class="fa-solid fa-calculator text-warning"></i>
                  <span>3. Số Lượng Mua:</span>
                </label>
                <span class="badge bg-dark text-warning border border-warning px-2.5 py-1 fw-bold d-inline-flex align-items-center gap-1.5" style="font-size: 0.82rem;">
                  <i class="fa-solid fa-cart-shopping"></i> Đã chọn: <span id="showQtyLiveBadge" class="text-white fs-6 fw-bolder">1</span> sản phẩm
                </span>
              </div>

              <div class="d-flex align-items-center gap-3 flex-wrap">
                <!-- Stepper Box -->
                <div class="bee-qty-stepper">
                  <button type="button" class="btn-step-minus" id="btnMinusQty" onclick="stepProductQty(-1)" title="Giảm 1 sản phẩm">
                    <i class="fa-solid fa-minus"></i>
                  </button>
                  <input type="number" name="quantity" id="productQty" class="qty-display-input" 
                    value="1" min="1" max="10" readonly>
                  <button type="button" class="btn-step-plus" id="btnPlusQty" onclick="stepProductQty(1)" title="Tăng 1 sản phẩm">
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>

                <!-- Live Subtotal Preview & Stock -->
                <div>
                  <div class="d-flex align-items-baseline gap-1.5">
                    <span class="text-muted small">Tạm tính:</span>
                    <strong class="text-danger fs-5 fw-bold" id="productSubtotalLive">{{ number_format($product->price, 0, ',', '.') }}₫</strong>
                  </div>
                  <small class="text-muted fs-11 d-block">
                    Kho: <strong class="text-dark" id="displayStockCount">{{ $product->stock }}</strong> có sẵn • Tối đa 10 cái / đơn
                  </small>
                </div>
              </div>

              <div id="maxLimitMsg" class="alert alert-warning border-0 py-2 px-3 small rounded-3 mt-2.5 mb-0 d-none fw-semibold" style="font-size: 0.8rem;">
                <i class="fa-solid fa-circle-exclamation text-danger me-1"></i> Quý khách đã chọn số lượng tối đa cho phép (10 sản phẩm) trong một lần đặt hàng.
              </div>
            </div>

            <!-- CẢNH BÁO CHƯA CHỌN MÀU / SIZE -->
            <div class="alert alert-danger py-2.5 px-3 rounded-3 mb-3 d-none shadow-xs" id="productFormAlert" style="font-size: 0.85rem;">
              <i class="fa-solid fa-triangle-exclamation me-1.5 fs-6 align-middle"></i>
              <span id="productFormAlertText">Vui lòng chọn Màu sắc và Kích thước (Size) trước khi mua!</span>
            </div>

            <!-- BUTTONS & WISHLIST -->
            <div class="row g-2 mb-4 align-items-center">
              <div class="col-5">
                <button type="submit" class="btn btn-bee-outline w-100 py-2.5 fs-6 shadow-xs" id="btnAddToCart">
                  <i class="fa-solid fa-cart-plus me-1.5"></i> Thêm Vào Giỏ
                </button>
              </div>
              <div class="col-5">
                <button type="submit" name="buy_now" value="1" class="btn btn-bee-primary w-100 py-2.5 fs-6 shadow-xs" id="btnBuyNow">
                  <i class="fa-solid fa-bolt me-1.5"></i> Mua Ngay
                </button>
              </div>
              <div class="col-2">
                <button type="button" class="btn btn-outline-secondary w-100 py-2.5 fs-6 btn-wishlist-toggle btn-wishlist-{{ $product->id }} {{ \App\Services\WishlistService::isFavorite($product->id) ? 'active text-danger border-danger' : '' }} shadow-xs" 
                  onclick="toggleWishlist({{ $product->id }}, this)" 
                  title="Thêm vào danh sách yêu thích" style="border-radius: 8px;">
                  <i class="{{ \App\Services\WishlistService::isFavorite($product->id) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }} fs-5"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- PROMISES / TRUST BADGES -->
          <div class="border-top pt-3.5 mt-2">
            <div class="row g-2">
              <div class="col-6">
                <div class="bee-trust-badge-card d-flex align-items-center gap-2.5">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="fa-solid fa-truck-fast text-warning fs-6"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold text-dark fs-12">Freeship toàn quốc</span>
                    <small class="text-muted fs-11">Đơn hàng từ 300.000₫</small>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="bee-trust-badge-card d-flex align-items-center gap-2.5">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="fa-solid fa-rotate-left text-warning fs-6"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold text-dark fs-12">Đổi size tận nơi</span>
                    <small class="text-muted fs-11">Miễn phí trong 30 ngày</small>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="bee-trust-badge-card d-flex align-items-center gap-2.5">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="fa-solid fa-shield-check text-warning fs-6"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold text-dark fs-12">Chính hãng 100%</span>
                    <small class="text-muted fs-11">Chuẩn form may đo</small>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="bee-trust-badge-card d-flex align-items-center gap-2.5">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="fa-solid fa-box-open text-warning fs-6"></i>
                  </div>
                  <div>
                    <span class="d-block fw-bold text-dark fs-12">Đồng kiểm hàng</span>
                    <small class="text-muted fs-11">Ưng ý mới thanh toán</small>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- TABS: DESCRIPTION, SIZE GUIDE & REVIEWS -->
  <div class="card border-0 shadow-sm p-4 p-md-5 mb-5" style="border-radius: 20px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
    <ul class="nav nav-tabs border-bottom mb-4" id="productTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold text-uppercase py-3" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">
          <i class="fa-solid fa-file-lines me-2 text-danger"></i> Chi Tiết Sản Phẩm
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-uppercase py-3" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab">
          <i class="fa-solid fa-sliders me-2 text-danger"></i> Thông Số &amp; Bảo Quản
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-uppercase py-3" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
          <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Khách Mua ({{ $product->reviews->count() }})
        </button>
      </li>
    </ul>

    <div class="tab-content" id="productTabsContent">
      <!-- Tab 1: Description -->
      <div class="tab-pane fade show active" id="desc" role="tabpanel">
        <div class="product-description-content text-secondary leading-relaxed">
          {!! $product->description ?? nl2br(e($product->short_description)) !!}
        </div>
      </div>

      <!-- Tab 2: Specs & Care -->
      <div class="tab-pane fade" id="specs" role="tabpanel">
        <div class="row g-4">
          <div class="col-lg-8">
            <h6 class="fw-bold text-dark mb-3">Thông số chi tiết sản phẩm:</h6>
            <div class="table-responsive">
              <table class="table table-bordered small align-middle">
                <tbody>
                  <tr>
                    <td class="fw-semibold text-dark bg-light" style="width: 200px;">Chất liệu chính</td>
                    <td>Cotton Compact 100% cao cấp, dệt sợi đôi siêu bền</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold text-dark bg-light">Kiểu dáng (Fit)</td>
                    <td>Regular Fit tôn dáng, thoải mái vận động cả ngày</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold text-dark bg-light">Tính năng vượt trội</td>
                    <td>Thấm hút mồ hôi 3 giây, kháng khuẩn khử mùi, chống nhăn tự nhiên</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold text-dark bg-light">Hướng dẫn giặt ủi</td>
                    <td>Giặt máy ở nhiệt độ thường, không ngâm hóa chất tẩy mạnh, ủi ở nhiệt độ trung bình</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold text-dark bg-light">Xuất xứ</td>
                    <td>Việt Nam (Tiêu chuẩn xuất khẩu chất lượng cao)</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="p-3 bg-light rounded-3 text-center border">
              <i class="fa-solid fa-award text-warning fs-1 mb-2"></i>
              <h6 class="fw-bold text-dark mb-1">Bảo Hành Đường May 1 Năm</h6>
              <p class="small text-muted mb-0">BeeStyle hỗ trợ đổi mới miễn phí nếu có lỗi từ nhà sản xuất trong vòng 30 ngày.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab 3: Reviews -->
      <div class="tab-pane fade" id="reviews" role="tabpanel">
        @php
          $allReviews = $product->reviews ?? collect([]);
          $totalRevCount = $allReviews->count();
          $avgRating = $totalRevCount > 0 ? round($allReviews->avg('rating'), 1) : 5.0;
          $count5 = $allReviews->where('rating', 5)->count();
          $count4 = $allReviews->where('rating', 4)->count();
          $count3 = $allReviews->where('rating', 3)->count();
          $count2 = $allReviews->where('rating', 2)->count();
          $count1 = $allReviews->where('rating', 1)->count();
          $pct5 = $totalRevCount > 0 ? round(($count5 / $totalRevCount) * 100) : 0;
          $pct4 = $totalRevCount > 0 ? round(($count4 / $totalRevCount) * 100) : 0;
          $pct3 = $totalRevCount > 0 ? round(($count3 / $totalRevCount) * 100) : 0;
          $pct2 = $totalRevCount > 0 ? round(($count2 / $totalRevCount) * 100) : 0;
          $pct1 = $totalRevCount > 0 ? round(($count1 / $totalRevCount) * 100) : 0;
          $reviewsWithPhotos = $allReviews->filter(fn($r) => !empty($r->images_urls))->count();
        @endphp

        <div class="row g-4">
          <!-- Left Column: Rating Overview & Review Form -->
          <div class="col-lg-4 col-md-5 border-end-md pe-lg-4">
            <!-- RATING SCORE & PROGRESS BARS -->
            <div class="p-3.5 bg-light rounded-4 mb-4 border">
              <div class="text-center mb-3">
                <h1 class="display-3 fw-bold text-warning mb-0" style="letter-spacing: -1px;">{{ number_format($avgRating, 1) }}</h1>
                <div class="text-warning mb-1 fs-5">
                  @for($i=1; $i<=5; $i++)
                    <i class="fa-solid fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                  @endfor
                </div>
                <p class="text-muted small mb-0">Dựa trên <strong>{{ $totalRevCount }}</strong> lượt đánh giá từ khách hàng đã mua</p>
              </div>

              <!-- Star breakdown bars -->
              <div class="d-flex flex-column gap-2 small pt-2 border-top">
                <div class="d-flex align-items-center gap-2">
                  <span class="text-muted text-nowrap" style="width: 48px;">5 <i class="fa-solid fa-star text-warning small"></i></span>
                  <div class="progress flex-grow-1" style="height: 7px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pct5 }}%;"></div>
                  </div>
                  <span class="text-muted text-end" style="width: 32px; font-size: 0.72rem;">{{ $count5 }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="text-muted text-nowrap" style="width: 48px;">4 <i class="fa-solid fa-star text-warning small"></i></span>
                  <div class="progress flex-grow-1" style="height: 7px;">
                    <div class="progress-bar bg-warning opacity-75" role="progressbar" style="width: {{ $pct4 }}%;"></div>
                  </div>
                  <span class="text-muted text-end" style="width: 32px; font-size: 0.72rem;">{{ $count4 }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="text-muted text-nowrap" style="width: 48px;">3 <i class="fa-solid fa-star text-warning small"></i></span>
                  <div class="progress flex-grow-1" style="height: 7px;">
                    <div class="progress-bar bg-secondary opacity-50" role="progressbar" style="width: {{ $pct3 }}%;"></div>
                  </div>
                  <span class="text-muted text-end" style="width: 32px; font-size: 0.72rem;">{{ $count3 }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="text-muted text-nowrap" style="width: 48px;">2 <i class="fa-solid fa-star text-warning small"></i></span>
                  <div class="progress flex-grow-1" style="height: 7px;">
                    <div class="progress-bar bg-secondary opacity-50" role="progressbar" style="width: {{ $pct2 }}%;"></div>
                  </div>
                  <span class="text-muted text-end" style="width: 32px; font-size: 0.72rem;">{{ $count2 }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="text-muted text-nowrap" style="width: 48px;">1 <i class="fa-solid fa-star text-warning small"></i></span>
                  <div class="progress flex-grow-1" style="height: 7px;">
                    <div class="progress-bar bg-secondary opacity-50" role="progressbar" style="width: {{ $pct1 }}%;"></div>
                  </div>
                  <span class="text-muted text-end" style="width: 32px; font-size: 0.72rem;">{{ $count1 }}</span>
                </div>
              </div>
            </div>

            <!-- REVIEW SUBMISSION FORM BOX -->
            <div class="card border-0 shadow-sm p-3 rounded-4" style="background: #ffffff; border: 1px solid var(--atino-border) !important;">
              <h6 class="fw-bold text-dark mb-3 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-pen-nib me-2 text-danger"></i> <span id="productReviewFormTitle">Viết Nhận Xét &amp; Đính Kèm Ảnh</span>
              </h6>

              <div id="productReviewAlertBox" class="alert d-none mb-3 small py-2.5 px-3 rounded-3"></div>

              @auth
                @if($userHasPurchased)
                  <form id="productPageReviewForm" action="{{ route('client.products.review', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Star Rating Choice -->
                    <div class="mb-3">
                      <label class="form-label small fw-semibold text-dark mb-1">1. Đánh giá chất lượng:</label>
                      <div class="d-flex align-items-center gap-2 flex-wrap">
                        @for($s=5; $s>=1; $s--)
                          <input type="radio" class="btn-check" name="rating" id="star_{{ $s }}" value="{{ $s }}" {{ old('rating', $userReview->rating ?? 5) == $s ? 'checked' : '' }} required>
                          <label class="btn btn-sm btn-outline-warning text-dark fw-bold px-2 py-1" for="star_{{ $s }}">
                            {{ $s }} <i class="fa-solid fa-star text-warning"></i>
                          </label>
                        @endfor
                      </div>
                    </div>

                    <!-- Comment textarea -->
                    <div class="mb-3">
                      <label class="form-label small fw-semibold text-dark mb-1">2. Chia sẻ cảm nhận của bạn:</label>
                      <textarea name="comment" id="productPageCommentInput" class="form-control form-control-sm" rows="3" placeholder="Chất liệu vải, form dáng, độ vừa vặn khi mặc..." required>{{ old('comment', $userReview->comment ?? '') }}</textarea>
                    </div>

                    <!-- 3. Photo Upload Box with Instant Preview -->
                    <div class="mb-3">
                      <label class="form-label small fw-semibold text-dark mb-1 d-flex align-items-center justify-content-between">
                        <span><i class="fa-solid fa-camera text-warning me-1"></i> 3. Đính kèm ảnh thực tế:</span>
                        <span class="text-muted fs-11" id="reviewImgCount">Tối đa 5 ảnh</span>
                      </label>
                      <div class="p-2.5 rounded-3 border bg-light text-center position-relative" style="border: 2px dashed #cbd5e1 !important; cursor: pointer;" onclick="document.getElementById('reviewImagesInput').click()">
                        <i class="fa-solid fa-cloud-arrow-up text-warning fs-3 mb-1 d-block"></i>
                        <span class="small text-dark fw-bold d-block">Bấm để chọn hoặc kéo thả ảnh chụp áo</span>
                        <small class="text-muted fs-11">Hỗ trợ JPG, PNG, WEBP (tối đa 5MB/ảnh)</small>
                        <input type="file" name="review_images[]" id="reviewImagesInput" class="d-none" multiple accept="image/*" onchange="previewReviewPhotos(this, 'showReviewPhotoPreview')">
                      </div>
                      
                      <!-- Preview Container -->
                      <div id="showReviewPhotoPreview" class="d-flex gap-2 flex-wrap mt-2">
                        @if(isset($userReview) && !empty($userReview->images_urls))
                          @foreach($userReview->images_urls as $imgUrl)
                            <div class="position-relative">
                              <img src="{{ $imgUrl }}" class="rounded border shadow-xs" style="width: 56px; height: 56px; object-fit: cover;">
                            </div>
                          @endforeach
                        @endif
                      </div>
                    </div>

                    <button type="submit" id="productReviewSubmitBtn" class="btn btn-bee-primary btn-sm w-100 py-2 fw-bold">
                      <i class="fa-solid fa-paper-plane me-1"></i> <span id="productReviewBtnText">{{ $userReview ? 'CẬP NHẬT ĐÁNH GIÁ' : 'GỬI ĐÁNH GIÁ NGAY' }}</span>
                    </button>
                    @if($userReview)
                      <small class="d-block text-center text-success mt-1 fs-11"><i class="fa-solid fa-circle-check"></i> Bạn đã đánh giá sản phẩm này</small>
                    @endif
                  </form>
                @else
                  <div class="alert alert-warning border-0 p-3 mb-0 rounded-3 small">
                    <div class="d-flex align-items-start gap-2">
                      <i class="fa-solid fa-shield-halved text-warning fs-5 mt-1"></i>
                      <div>
                        <strong>Xác thực người mua:</strong>
                        <p class="mb-0 text-muted mt-1">Để đảm bảo tính khách quan và trung thực, chỉ khách hàng <strong>đã từng mua sản phẩm này</strong> mới có thể viết đánh giá kèm hình ảnh.</p>
                      </div>
                    </div>
                  </div>
                @endif
              @else
                <div class="text-center py-3 bg-light rounded-3">
                  <i class="fa-solid fa-user-lock fs-3 text-muted mb-2"></i>
                  <p class="small text-muted mb-3">Vui lòng đăng nhập với tài khoản đã mua hàng để gửi nhận xét &amp; hình ảnh.</p>
                  <a href="{{ route('auth.login') }}" class="btn btn-bee-primary btn-sm px-4 fw-bold">
                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Đăng Nhập Để Đánh Giá
                  </a>
                </div>
              @endauth
            </div>
          </div>

          <!-- Right Column: List of Reviews with Filters -->
          <div class="col-lg-8 col-md-7 ps-lg-4">
            <!-- FILTER PILL TABS -->
            <div class="p-3 bg-light rounded-4 border mb-4">
              <div class="d-flex align-items-center justify-content-between mb-2.5 flex-wrap gap-2">
                <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-family: var(--atino-font-heading); font-size: 0.92rem;">
                  <i class="fa-solid fa-comments text-warning me-1.5"></i> Nhận Xét Khách Hàng ({{ $totalRevCount }})
                </h6>
                <span class="small text-muted">100% Đánh giá thật từ người mua</span>
              </div>
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-sm btn-dark px-3 py-1 rounded-pill filter-review-btn active shadow-xs" data-filter="all" onclick="filterReviews('all', this)">
                  Tất cả ({{ $totalRevCount }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill filter-review-btn" data-filter="5" onclick="filterReviews('5', this)">
                  5 Sao ({{ $count5 }})
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill filter-review-btn" data-filter="4" onclick="filterReviews('4', this)">
                  4 Sao ({{ $count4 }})
                </button>
                @if($count3 > 0)
                  <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill filter-review-btn" data-filter="3" onclick="filterReviews('3', this)">
                    3 Sao ({{ $count3 }})
                  </button>
                @endif
                @if($reviewsWithPhotos > 0)
                  <button type="button" class="btn btn-sm btn-outline-warning text-dark px-3 py-1 rounded-pill filter-review-btn fw-semibold" data-filter="photo" onclick="filterReviews('photo', this)">
                    <i class="fa-solid fa-camera text-warning me-1"></i> Có hình ảnh ({{ $reviewsWithPhotos }})
                  </button>
                @endif
              </div>
            </div>

            <!-- REVIEWS LIST -->
            <div class="d-flex flex-column gap-3" id="productPageReviewsList">
              @forelse($allReviews as $rev)
                <div class="p-3.5 bg-light rounded-4 border review-card-item transition-all" data-rating="{{ $rev->rating }}" data-has-photo="{{ !empty($rev->images_urls) ? '1' : '0' }}">
                  <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2.5">
                      <div class="position-relative cursor-pointer hover-scale" onclick="openReviewerModal({{ $rev->id }})" title="Xem hồ sơ người mua {{ $rev->user_name }}">
                        <img src="{{ $rev->user_avatar_url }}" alt="{{ $rev->user_name }}" class="rounded-circle border shadow-xs bg-white" style="width: 42px; height: 42px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 15px; height: 15px; font-size: 8px; border: 2px solid #fff;">
                          <i class="fa-solid fa-check"></i>
                        </span>
                      </div>
                      <div>
                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                          <strong class="text-dark fs-9 cursor-pointer" onclick="openReviewerModal({{ $rev->id }})" title="Xem hồ sơ người mua {{ $rev->user_name }}">{{ $rev->user_name }}</strong>
                          <button type="button" class="btn btn-outline-secondary btn-xs py-0 px-2 rounded-pill border small d-inline-flex align-items-center gap-1 hover-lift" style="font-size: 0.68rem;" onclick="openReviewerModal({{ $rev->id }})" title="Xem hồ sơ người mua">
                            <i class="fa-solid fa-id-card text-warning"></i> <span>Xem hồ sơ</span>
                          </button>
                          <span class="badge bg-success-subtle text-success small px-2 py-0.5" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-circle-check me-1"></i> Đã mua hàng từ BeeStyle
                          </span>
                        </div>
                        <div class="text-muted fs-11 mt-0.5">
                          <i class="fa-regular fa-clock me-1"></i>{{ $rev->created_at ? $rev->created_at->diffForHumans() : 'Vừa xong' }}
                          <span class="mx-1">•</span>
                          <span class="text-muted"><i class="fa-solid fa-shirt me-1 text-warning"></i> Phân loại: {{ $product->category->name ?? 'Thời trang nam' }}</span>
                        </div>
                      </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-1 text-warning small bg-white px-2.5 py-1 rounded-pill border shadow-xs">
                      @for($i=1; $i<=5; $i++)
                        <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                      @endfor
                      <span class="text-dark fw-bold ms-1" style="font-size: 0.75rem;">{{ $rev->rating }}/5</span>
                    </div>
                  </div>

                  <p class="small text-secondary mb-0 leading-relaxed ps-md-5 ms-md-2">
                    {{ $rev->comment }}
                  </p>

                  <!-- Customer Uploaded Review Photos -->
                  @if(!empty($rev->images_urls))
                    <div class="d-flex gap-2 flex-wrap mt-2.5 pt-2 border-top border-secondary border-opacity-10 ps-md-5 ms-md-2">
                      @foreach($rev->images_urls as $photoUrl)
                        <div class="position-relative cursor-pointer hover-scale" style="cursor: pointer;" onclick="openReviewImageLightbox('{{ $photoUrl }}')" title="Bấm để xem ảnh phóng to">
                          <img src="{{ $photoUrl }}" alt="Ảnh đánh giá" class="rounded-3 border shadow-xs" style="width: 72px; height: 72px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                          <span class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white px-1.5 py-0.5 rounded-start" style="font-size: 0.65rem;">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                          </span>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              @empty
                <div class="p-4 bg-light rounded-4 text-center border">
                  <i class="fa-regular fa-comment-dots fs-1 text-muted mb-2"></i>
                  <p class="text-dark fw-semibold mb-1">Chưa có đánh giá nào cho sản phẩm này</p>
                  <p class="small text-muted mb-0">Hãy là người đầu tiên mua và trải nghiệm chất lượng thời trang của BeeStyle!</p>
                </div>
              @endforelse

              <!-- Empty Filter Notice -->
              <div id="reviewFilterEmptyMsg" class="p-4 bg-light rounded-4 text-center border" style="display: none;">
                <i class="fa-regular fa-face-meh fs-2 text-muted mb-2"></i>
                <p class="text-dark fw-semibold mb-0">Không có đánh giá nào phù hợp với bộ lọc đã chọn</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- RECENTLY VIEWED PRODUCTS (SẢN PHẨM ĐÃ XEM GẦN ĐÂY) -->
  @if(isset($recentlyViewedProducts) && $recentlyViewedProducts->count() > 0)
    <div class="mb-5">
      <div class="bee-section-header">
        <div>
          <h3 class="bee-section-title d-flex align-items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-warning"></i>
            <span>Sản Phẩm Bạn Đã Xem Gần Đây</span>
          </h3>
          <p class="bee-section-subtitle">Dễ dàng xem lại các sản phẩm thời trang bạn vừa quan tâm</p>
        </div>
      </div>

      <div class="row g-3 g-md-4">
        @foreach($recentlyViewedProducts as $rItem)
          <div class="col-lg-2 col-md-4 col-6">
            <div class="bee-product-card h-100 shadow-xs border transition-all hover-lift" style="border-radius: 14px;">
              <div class="bee-product-img-wrapper position-relative overflow-hidden" style="border-radius: 12px 12px 0 0;">
                <img src="{{ asset($rItem->image) }}" alt="{{ $rItem->name }}" class="w-100 object-fit-cover" style="height: 180px;">
                @if($rItem->original_price && $rItem->original_price > $rItem->price)
                  <span class="position-absolute top-0 start-0 m-2 badge bg-danger text-white rounded-pill small fw-bold">
                    -{{ round((($rItem->original_price - $rItem->price) / $rItem->original_price) * 100) }}%
                  </span>
                @endif
              </div>
              <div class="bee-product-body p-2.5">
                <span class="bee-product-category fs-11 text-muted d-block text-truncate">{{ $rItem->category->name ?? 'Thời Trang Nam' }}</span>
                <a href="{{ route('client.products.show', $rItem->id) }}" class="bee-product-title text-dark fw-semibold text-decoration-none d-block text-truncate small" title="{{ $rItem->name }}">
                  {{ $rItem->name }}
                </a>
                <div class="bee-product-price-row mt-1">
                  <span class="bee-product-price text-danger fw-bold small">{{ number_format($rItem->price, 0, ',', '.') }}₫</span>
                  @if($rItem->original_price && $rItem->original_price > $rItem->price)
                    <span class="text-muted text-decoration-line-through fs-11 ms-1">{{ number_format($rItem->original_price, 0, ',', '.') }}₫</span>
                  @endif
                </div>
                <a href="{{ route('client.products.show', $rItem->id) }}" class="btn btn-outline-dark btn-xs w-100 mt-2 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                  Xem Lại
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  <!-- RELATED PRODUCTS -->
  @if($relatedProducts->count() > 0)
    <div class="mb-5">
      <div class="bee-section-header">
        <div>
          <h3 class="bee-section-title">Gợi Ý Sản Phẩm Cùng Danh Mục</h3>
          <p class="bee-section-subtitle">Có thể bạn cũng sẽ thích những mẫu thời trang này</p>
        </div>
      </div>

      <div class="row g-4">
        @foreach($relatedProducts as $item)
          <div class="col-lg-3 col-md-6 col-6">
            <div class="bee-product-card">
              <div class="bee-product-img-wrapper">
                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
              </div>
              <div class="bee-product-body">
                <span class="bee-product-category">{{ $item->category->name ?? 'Thời Trang Nam' }}</span>
                <a href="{{ route('client.products.show', $item->id) }}" class="bee-product-title">{{ $item->name }}</a>
                <div class="bee-product-price-row">
                  <span class="bee-product-price">{{ number_format($item->price, 0, ',', '.') }}₫</span>
                </div>
                <a href="{{ route('client.products.show', $item->id) }}" class="btn btn-bee-outline btn-sm w-100 mt-2">Xem Chi Tiết</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>

<!-- STICKY ADD TO CART BOTTOM BAR (TMĐT CHUYÊN NGHIỆP) -->
<div id="stickyAddToCartBar" class="sticky-bottom-cart-bar position-fixed bottom-0 start-0 end-0 bg-white border-top shadow-lg py-2.5 px-3 px-md-4 z-3 d-none transition-all" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.96) !important;">
  <div class="container d-flex align-items-center justify-content-between gap-3">
    <!-- Left: Product Snapshot -->
    <div class="d-flex align-items-center gap-3 min-w-0">
      <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="rounded-3 border shadow-xs flex-shrink-0" style="width: 48px; height: 48px; object-fit: cover;">
      <div class="min-w-0">
        <h6 class="fw-bold text-dark mb-0 text-truncate small" style="max-width: 320px;">{{ $product->name }}</h6>
        <div class="d-flex align-items-center gap-2">
          <span class="text-danger fw-bold fs-6">{{ number_format($product->price, 0, ',', '.') }}₫</span>
          @if($product->original_price && $product->original_price > $product->price)
            <small class="text-muted text-decoration-line-through fs-11">{{ number_format($product->original_price, 0, ',', '.') }}₫</small>
          @endif
          <span class="badge bg-light text-muted border fs-11 d-none d-md-inline" id="stickySelectedVariantText">Mặc định</span>
        </div>
      </div>
    </div>

    <!-- Right: Action Buttons -->
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
      <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 py-2 fw-semibold d-none d-sm-inline-flex align-items-center gap-1.5" onclick="scrollToProductOptions()">
        <i class="fa-solid fa-sliders text-warning"></i> Chọn Size/Màu
      </button>
      <button type="button" class="btn btn-bee-outline btn-sm rounded-pill px-3 py-2 fw-bold" onclick="triggerStickySubmit(false)">
        <i class="fa-solid fa-cart-plus me-1"></i> Thêm Vào Giỏ
      </button>
      <button type="button" class="btn btn-bee-primary btn-sm rounded-pill px-3.5 py-2 fw-bold shadow-xs" onclick="triggerStickySubmit(true)">
        <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
      </button>
    </div>
  </div>
</div>

<!-- SIZE GUIDE & SMART FIT FINDER MODAL (TƯ VẤN SIZE THÔNG MINH) -->
<div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-bottom pb-3">
        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
          <i class="fa-solid fa-ruler-combined text-warning"></i>
          <span>Tư Vấn Kích Thước &amp; Bảng Quy Đổi Size Chuẩn</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Tabs in Modal -->
        <ul class="nav nav-pills nav-fill mb-3 bg-light p-1 rounded-pill" id="sizeModalTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill fw-bold small py-2" id="smart-calc-tab" data-bs-toggle="pill" data-bs-target="#smart-calc" type="button" role="tab">
              <i class="fa-solid fa-wand-magic-sparkles text-warning me-1.5"></i> Tính Size Tự Động Cho Bạn
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-bold small py-2" id="table-size-tab" data-bs-toggle="pill" data-bs-target="#table-size" type="button" role="tab">
              <i class="fa-solid fa-table-list text-warning me-1.5"></i> Bảng Thông Số Chuẩn
            </button>
          </li>
        </ul>

        <div class="tab-content" id="sizeModalTabsContent">
          <!-- Tab 1: Smart Size Calculator -->
          <div class="tab-pane fade show active" id="smart-calc" role="tabpanel">
            <div class="p-3.5 bg-light rounded-4 border mb-3">
              <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-user-check text-warning me-1.5"></i> Nhập số đo để BeeStyle gợi ý size chính xác:</h6>
              <div class="row g-3 align-items-end">
                <div class="col-md-4 col-6">
                  <label class="form-label small fw-semibold text-muted mb-1">Chiều cao của bạn (cm):</label>
                  <div class="input-group input-group-sm">
                    <input type="number" id="calcHeight" class="form-control" placeholder="VD: 172" min="140" max="210" value="170">
                    <span class="input-group-text">cm</span>
                  </div>
                </div>
                <div class="col-md-4 col-6">
                  <label class="form-label small fw-semibold text-muted mb-1">Cân nặng của bạn (kg):</label>
                  <div class="input-group input-group-sm">
                    <input type="number" id="calcWeight" class="form-control" placeholder="VD: 68" min="35" max="130" value="65">
                    <span class="input-group-text">kg</span>
                  </div>
                </div>
                <div class="col-md-4 col-12">
                  <button type="button" class="btn btn-bee-primary btn-sm w-100 py-2 fw-bold" onclick="calculateSmartFitSize()">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Gợi Ý Size Ngay
                  </button>
                </div>
              </div>
            </div>

            <!-- Result Box -->
            <div id="smartFitResultBox" class="p-3.5 rounded-4 border bg-white text-center shadow-xs">
              <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning-subtle text-dark mb-2" style="width: 54px; height: 54px;">
                <span class="fs-4 fw-bold text-warning font-monospace" id="suggestedSizeBadge">L</span>
              </div>
              <h5 class="fw-bold text-dark mb-1">Size khuyên dùng cho bạn: <span class="text-danger fw-bolder fs-4" id="suggestedSizeName">Size L</span></h5>
              <p class="small text-muted mb-3" id="suggestedSizeDesc">Phù hợp hoàn hảo với chiều cao 170cm và cân nặng 65kg, mặc vừa vặn, tôn dáng chuẩn Regular Fit.</p>
              
              <button type="button" class="btn btn-dark btn-sm px-4 py-2 rounded-pill fw-bold" onclick="applySuggestedSize()">
                <i class="fa-solid fa-check me-1.5 text-warning"></i> Chọn Size Này Cho Sản Phẩm
              </button>
            </div>
          </div>

          <!-- Tab 2: Standard Size Table -->
          <div class="tab-pane fade" id="table-size" role="tabpanel">
            <p class="small text-muted mb-3">Thông số may đo chuẩn theo vóc dáng nam giới Việt Nam:</p>
            <div class="table-responsive">
              <table class="table table-bordered text-center align-middle small mb-0">
                <thead class="table-dark">
                  <tr>
                    <th>Size</th>
                    <th>Chiều Cao (cm)</th>
                    <th>Cân Nặng (kg)</th>
                    <th>Rộng Ngực (cm)</th>
                    <th>Dài Áo (cm)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td><strong class="text-warning">S</strong></td><td>155 - 165</td><td>48 - 55</td><td>92 - 96</td><td>66</td></tr>
                  <tr><td><strong class="text-warning">M</strong></td><td>164 - 172</td><td>56 - 65</td><td>96 - 100</td><td>68</td></tr>
                  <tr><td><strong class="text-warning">L</strong></td><td>170 - 178</td><td>66 - 74</td><td>100 - 104</td><td>70</td></tr>
                  <tr><td><strong class="text-warning">XL</strong></td><td>175 - 183</td><td>75 - 82</td><td>104 - 108</td><td>72</td></tr>
                  <tr><td><strong class="text-warning">XXL</strong></td><td>180 - 190</td><td>83 - 92</td><td>108 - 114</td><td>74</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FULLSCREEN PRODUCT GALLERY LIGHTBOX MODAL (CHUẨN TMĐT CAO CẤP) -->
<div class="modal fade" id="productGalleryModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(12px); background: rgba(0, 0, 0, 0.88);">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content border-0 bg-transparent text-white">
      <!-- LIGHTBOX TOP TOOLBAR -->
      <div class="d-flex justify-content-between align-items-center p-3 p-md-4 position-absolute top-0 start-0 end-0 z-3" style="background: linear-gradient(180deg, rgba(0,0,0,0.75) 0%, transparent 100%);">
        <div class="d-flex align-items-center gap-3">
          <span class="badge bg-warning text-dark fw-bold px-3 py-1.5 rounded-pill fs-7 shadow-sm" id="modalImageCounter">
            Ảnh 1 / {{ $allGalleryImages->count() }}
          </span>
          <span class="d-none d-md-inline fw-semibold text-truncate text-white" style="max-width: 400px; text-shadow: 0 2px 4px rgba(0,0,0,0.6);">
            {{ $product->name }}
          </span>
        </div>

        <div class="d-flex align-items-center gap-2">
          <!-- ZOOM CONTROLS -->
          <button type="button" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 40px; height: 40px; backdrop-filter: blur(4px);" onclick="modalZoomIn()" title="Phóng to (+)">
            <i class="fa-solid fa-magnifying-glass-plus"></i>
          </button>
          <button type="button" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 40px; height: 40px; backdrop-filter: blur(4px);" onclick="modalZoomOut()" title="Thu nhỏ (-)">
            <i class="fa-solid fa-magnifying-glass-minus"></i>
          </button>
          <button type="button" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 40px; height: 40px; backdrop-filter: blur(4px);" onclick="modalZoomReset()" title="Kích thước chuẩn (1:1)">
            <i class="fa-solid fa-rotate-left"></i>
          </button>
          <button type="button" class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center ms-2 shadow-xs" style="width: 40px; height: 40px;" data-bs-dismiss="modal" title="Đóng (Esc)">
            <i class="fa-solid fa-xmark fs-5"></i>
          </button>
        </div>
      </div>

      <!-- LIGHTBOX MAIN VIEWPORT -->
      <div class="modal-body p-0 d-flex align-items-center justify-content-center position-relative overflow-hidden" id="modalViewport">
        <!-- CHEVRON PREV / NEXT -->
        @if($allGalleryImages->count() > 1)
          <button type="button" class="btn btn-dark bg-opacity-60 text-white rounded-circle position-absolute top-50 start-0 translate-middle-y ms-3 ms-md-4 z-3 d-flex align-items-center justify-content-center shadow-lg transition-all hover-scale" 
                  style="width: 52px; height: 52px; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px);" onclick="prevGalleryImg()" title="Ảnh trước (←)">
            <i class="fa-solid fa-chevron-left fs-4"></i>
          </button>
          <button type="button" class="btn btn-dark bg-opacity-60 text-white rounded-circle position-absolute top-50 end-0 translate-middle-y me-3 me-md-4 z-3 d-flex align-items-center justify-content-center shadow-lg transition-all hover-scale" 
                  style="width: 52px; height: 52px; border: 1px solid rgba(255,255,255,0.25); backdrop-filter: blur(8px);" onclick="nextGalleryImg()" title="Ảnh tiếp theo (→)">
            <i class="fa-solid fa-chevron-right fs-4"></i>
          </button>
        @endif

        <!-- MODAL IMAGE ELEMENT WITH PAN & ZOOM -->
        <img id="modalGalleryImg" src="{{ asset($product->image) }}" alt="Preview" 
             class="img-fluid" 
             style="max-height: 82vh; max-width: 92vw; object-fit: contain; transform: scale(1); transition: transform 0.2s ease-out; cursor: grab; user-select: none;">
      </div>

      <!-- LIGHTBOX BOTTOM THUMBNAIL FILMSTRIP -->
      @if($allGalleryImages->count() > 1)
        <div class="p-3 position-absolute bottom-0 start-0 end-0 z-3 text-center" style="background: linear-gradient(0deg, rgba(0,0,0,0.85) 0%, transparent 100%);">
          <div class="d-inline-flex gap-2 p-1.5 rounded-4 bg-dark bg-opacity-70 border border-white border-opacity-15 overflow-x-auto" style="max-width: 92vw; backdrop-filter: blur(12px);">
            @foreach($allGalleryImages as $mIdx => $mSrc)
              <div class="rounded-3 p-1 cursor-pointer modal-thumb-item transition-all position-relative overflow-hidden {{ $mIdx === 0 ? 'border border-warning ring-2 ring-warning' : 'border border-transparent opacity-60' }}" 
                   style="width: 62px; height: 62px; cursor: pointer; border-radius: 10px !important;" 
                   onclick="setGalleryIndex({{ $mIdx }})"
                   title="{{ getProductAngleTag($mSrc, $mIdx) }}">
                <img src="{{ asset($mSrc) }}" alt="thumb" class="w-100 h-100 rounded object-fit-cover">
                <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-dark bg-opacity-80 text-white px-1 rounded-pill mb-0.5 shadow-xs" style="font-size: 0.52rem; white-space: nowrap; pointer-events: none;">
                  {{ getProductAngleTag($mSrc, $mIdx) }}
                </span>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- MODAL: REVIEWER CUSTOMER PROFILE (HỒ SƠ KHÁCH HÀNG ĐÁNH GIÁ) -->
<div class="modal fade" id="customerReviewerProfileModal" tabindex="-1" aria-labelledby="customerReviewerProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <!-- Gradient Header Banner -->
      <div class="p-4 text-white position-relative" style="background: linear-gradient(135deg, #111827 0%, #1e293b 50%, #0f172a 100%);">
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative flex-shrink-0">
            <img id="revModalAvatar" src="" alt="Avatar" class="rounded-circle border border-2 border-warning shadow" style="width: 64px; height: 64px; object-fit: cover; background: #fff;">
            <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 20px; height: 20px; font-size: 10px; border: 2px solid #111827;" title="Tài khoản đã xác thực">
              <i class="fa-solid fa-check"></i>
            </span>
          </div>
          <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <h5 class="fw-bold mb-0 text-white text-truncate" id="revModalName">Nguyễn Văn Hùng</h5>
              <span id="revModalRankBadge" class="badge bg-warning text-dark px-2 py-0.5"><i class="fa-solid fa-crown me-1"></i> Hội Viên Vàng</span>
            </div>
            <div class="small text-white-50 mt-1 d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.76rem;">
              <span id="revModalJoined"><i class="fa-solid fa-calendar-check me-1 text-warning"></i> Thành viên từ 2025</span>
              <span>•</span>
              <span class="text-success fw-semibold"><i class="fa-solid fa-shield-check me-1"></i> Người mua xác thực</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Activity Stats 3-Column Grid -->
      <div class="p-3 bg-light border-bottom">
        <div class="row g-2 text-center">
          <div class="col-4">
            <div class="p-2 bg-white rounded-3 border shadow-xs">
              <span class="fs-11 text-muted d-block mb-0.5">Đơn hoàn thành</span>
              <strong class="fs-6 text-dark" id="revModalOrdersCount">12</strong> <span class="fs-11 text-muted">đơn</span>
            </div>
          </div>
          <div class="col-4">
            <div class="p-2 bg-white rounded-3 border shadow-xs">
              <span class="fs-11 text-muted d-block mb-0.5">Đánh giá đã viết</span>
              <strong class="fs-6 text-warning" id="revModalReviewsCount">8</strong> <span class="fs-11 text-muted">bài</span>
            </div>
          </div>
          <div class="col-4">
            <div class="p-2 bg-white rounded-3 border shadow-xs">
              <span class="fs-11 text-muted d-block mb-0.5">Tỷ lệ hữu ích</span>
              <strong class="fs-6 text-success">100%</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Body: Trust Badges & Recent Reviews by this Customer -->
      <div class="modal-body p-3.5">
        <!-- Trust Badges -->
        <div class="mb-3 p-2.5 rounded-3 bg-success-subtle border border-success border-opacity-25 small d-flex align-items-center gap-2">
          <i class="fa-solid fa-badge-check text-success fs-5"></i>
          <div>
            <strong class="text-success d-block" style="font-size: 0.82rem;">Tài Khoản Đã Xác Thực Mua Hàng</strong>
            <span class="text-muted" style="font-size: 0.74rem;">Khách hàng này đã nhận sản phẩm thực tế từ BeeStyle và xác nhận đánh giá chất lượng.</span>
          </div>
        </div>

        <!-- Section: Other Products Reviewed by this Customer -->
        <h6 class="fw-bold text-dark mb-2.5 small text-uppercase" style="letter-spacing: 0.5px; font-size: 0.78rem;">
          <i class="fa-solid fa-bag-shopping text-warning me-1.5"></i> Các Sản Phẩm Khác Khách Hàng Này Đã Đánh Giá:
        </h6>
        
        <div id="revModalOtherReviewsList" class="d-flex flex-column gap-2" style="max-height: 220px; overflow-y: auto;">
          <!-- Populated by JS -->
        </div>

        <!-- Admin Quick Link if current user is admin -->
        <div id="revModalAdminBox" class="mt-3 pt-2.5 border-top d-none text-center">
          <a id="revModalAdminLink" href="#" class="btn btn-dark btn-sm w-100 fw-bold">
            <i class="fa-solid fa-user-gear me-1.5 text-warning"></i> Quản Lý Chi Tiết Khách Hàng Này Trong Admin
          </a>
        </div>
      </div>

      <div class="modal-footer p-2 bg-light border-0 justify-content-center">
        <button type="button" class="btn btn-outline-secondary btn-sm px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">Đóng (Esc)</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // PRO E-COMMERCE GALLERY STATE
  const galleryImages = @json($allGalleryImages->map(fn($img) => asset($img)));
  let currentImgIndex = 0;
  let modalScale = 1;

  function setGalleryIndex(index) {
    if (index < 0) index = galleryImages.length - 1;
    if (index >= galleryImages.length) index = 0;
    currentImgIndex = index;

    const targetSrc = galleryImages[currentImgIndex];

    // Update main image
    const mainImg = document.getElementById('mainProductImg');
    if (mainImg) {
      mainImg.style.opacity = '0.4';
      setTimeout(() => {
        mainImg.src = targetSrc;
        mainImg.style.opacity = '1';
      }, 100);
    }

    // Update main thumbnails
    document.querySelectorAll('.thumb-item').forEach((item, idx) => {
      if (idx === currentImgIndex) {
        item.className = 'border rounded-3 p-1 bg-white flex-shrink-0 cursor-pointer thumb-item transition-all border-warning border-2 shadow-sm ring-1 ring-warning';
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      } else {
        item.className = 'border rounded-3 p-1 bg-white flex-shrink-0 cursor-pointer thumb-item transition-all border-muted';
      }
    });

    // Update modal elements
    const modalImg = document.getElementById('modalGalleryImg');
    if (modalImg) {
      modalImg.src = targetSrc;
      modalZoomReset();
    }
    const counter = document.getElementById('modalImageCounter');
    if (counter) {
      counter.textContent = `Ảnh ${currentImgIndex + 1} / ${galleryImages.length}`;
    }
    document.querySelectorAll('.modal-thumb-item').forEach((item, idx) => {
      if (idx === currentImgIndex) {
        item.className = 'rounded-3 p-1 cursor-pointer modal-thumb-item transition-all border border-warning ring-2 ring-warning';
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      } else {
        item.className = 'rounded-3 p-1 cursor-pointer modal-thumb-item transition-all border border-transparent opacity-60';
      }
    });
  }

  function nextGalleryImg() {
    setGalleryIndex(currentImgIndex + 1);
  }

  function prevGalleryImg() {
    setGalleryIndex(currentImgIndex - 1);
  }

  function openGalleryModal(index = 0) {
    setGalleryIndex(index);
    const modalEl = document.getElementById('productGalleryModal');
    if (modalEl) {
      const modal = new bootstrap.Modal(modalEl);
      modal.show();
    }
  }

  function modalZoomIn() {
    modalScale = Math.min(modalScale + 0.5, 3);
    applyModalZoom();
  }

  function modalZoomOut() {
    modalScale = Math.max(modalScale - 0.5, 1);
    applyModalZoom();
  }

  function modalZoomReset() {
    modalScale = 1;
    applyModalZoom();
  }

  function applyModalZoom() {
    const modalImg = document.getElementById('modalGalleryImg');
    if (modalImg) {
      modalImg.style.transform = `scale(${modalScale})`;
      modalImg.style.cursor = modalScale > 1 ? 'move' : 'zoom-in';
    }
  }

  // Magnifier Hover Zoom on Main Image Container
  document.addEventListener('DOMContentLoaded', function () {
    const viewer = document.getElementById('mainImgViewer');
    const mainImg = document.getElementById('mainProductImg');

    if (viewer && mainImg) {
      viewer.addEventListener('mousemove', function (e) {
        const rect = viewer.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        mainImg.style.transformOrigin = `${x}% ${y}%`;
        mainImg.style.transform = 'scale(2)';
      });

      viewer.addEventListener('mouseleave', function () {
        mainImg.style.transformOrigin = 'center center';
        mainImg.style.transform = 'scale(1)';
      });
    }

    // Keyboard Arrow navigation when Lightbox is open
    document.addEventListener('keydown', function (e) {
      const modalEl = document.getElementById('productGalleryModal');
      if (modalEl && modalEl.classList.contains('show')) {
        if (e.key === 'ArrowRight') nextGalleryImg();
        if (e.key === 'ArrowLeft') prevGalleryImg();
        if (e.key === '+' || e.key === '=') modalZoomIn();
        if (e.key === '-' || e.key === '_') modalZoomOut();
        if (e.key === '0') modalZoomReset();
      }
    });
  });

  function changeMainImg(src, el) {
    const foundIdx = galleryImages.findIndex(img => img === src || src.includes(img));
    if (foundIdx !== -1) {
      setGalleryIndex(foundIdx);
    } else {
      document.getElementById('mainProductImg').src = src;
    }
  }

  // VARIANT MATRIX & DYNAMIC DATA
  const PRODUCT_VARIANTS = @json($product->variants ?? []);
  let selectedVariant = null;

  function selectProductColor(color) {
    const el = document.getElementById('selectedColorText');
    if (el) {
      el.className = 'badge bg-dark text-warning border border-warning px-2 py-0.5 ms-1 fw-bold';
      el.textContent = color;
    }
    const colorSec = document.getElementById('colorGroupSection');
    if (colorSec) {
      colorSec.style.borderColor = '#e2e8f0';
      colorSec.style.backgroundColor = '#ffffff';
    }
    hideFormAlert();

    // Đồng bộ với Sticky Bottom Bar
    updateStickyVariantLabel();

    // 1. Tự động chuyển ảnh nếu có ảnh theo màu sắc
    if (galleryImages && galleryImages.length > 1) {
      const colorLower = color.toLowerCase();
      const matchIdx = galleryImages.findIndex(img => img.toLowerCase().includes(colorLower));
      if (matchIdx !== -1) {
        setGalleryIndex(matchIdx);
      }
    }

    // 2. Kiểm tra tồn kho chéo cho các Size theo Màu đã chọn (Matrix Checking)
    if (PRODUCT_VARIANTS && PRODUCT_VARIANTS.length > 0) {
      const sizeRadios = document.querySelectorAll('.product-size-radio');
      sizeRadios.forEach(radio => {
        const sVal = radio.value;
        const matchedVariant = PRODUCT_VARIANTS.find(v => (v.color === color || !v.color) && v.size === sVal);
        const label = document.querySelector(`label[for="${radio.id}"]`);
        if (label) {
          if (matchedVariant && matchedVariant.stock <= 0) {
            label.classList.add('opacity-50', 'text-decoration-line-through');
            label.title = 'Hết hàng tạm thời';
          } else {
            label.classList.remove('opacity-50', 'text-decoration-line-through');
            label.title = '';
          }
        }
      });
    }

    // 3. Cập nhật biến thể hoàn chỉnh nếu đã chọn cả Size
    checkAndApplyFullVariant();
  }

  function selectProductSize(size, hint) {
    const el = document.getElementById('selectedSizeText');
    if (el) {
      el.className = 'badge bg-dark text-warning border border-warning px-2 py-0.5 ms-1 fw-bold';
      el.textContent = 'Size ' + size + (hint ? ' (' + hint + ')' : '');
    }
    const sizeSec = document.getElementById('sizeGroupSection');
    if (sizeSec) {
      sizeSec.style.borderColor = '#e2e8f0';
      sizeSec.style.backgroundColor = '#ffffff';
    }
    hideFormAlert();

    // Đồng bộ với Sticky Bottom Bar
    updateStickyVariantLabel();

    // Cập nhật biến thể hoàn chỉnh nếu đã chọn cả Màu
    checkAndApplyFullVariant();
  }

  function checkAndApplyFullVariant() {
    const checkedColor = document.querySelector('input[name="color"]:checked');
    const checkedSize = document.querySelector('input[name="size"]:checked');
    const color = checkedColor ? checkedColor.value : null;
    const size = checkedSize ? checkedSize.value : null;

    if (color && size && PRODUCT_VARIANTS && PRODUCT_VARIANTS.length > 0) {
      selectedVariant = PRODUCT_VARIANTS.find(v => v.color === color && v.size === size);
      if (selectedVariant) {
        // Cập nhật SKU nếu có
        if (selectedVariant.sku) {
          const skuEl = document.getElementById('displaySku');
          if (skuEl) skuEl.textContent = selectedVariant.sku;
        }

        // Cập nhật tồn kho
        const stockEl = document.getElementById('displayStockCount');
        if (stockEl) stockEl.textContent = selectedVariant.stock;

        // Cập nhật giá bán nếu có giá riêng
        if (selectedVariant.price && selectedVariant.price > 0) {
          const priceEl = document.getElementById('displayPrice');
          if (priceEl) priceEl.textContent = selectedVariant.price.toLocaleString('vi-VN') + '₫';
        }

        // Cập nhật ảnh chính nếu biến thể có ảnh riêng
        if (selectedVariant.image) {
          const mainImg = document.getElementById('mainProductImg');
          if (mainImg) {
            mainImg.src = `/${selectedVariant.image}`;
          }
        }
      }
    }
  }

  function updateStickyVariantLabel() {
    const checkedColor = document.querySelector('input[name="color"]:checked');
    const checkedSize = document.querySelector('input[name="size"]:checked');
    const stickyText = document.getElementById('stickySelectedVariantText');
    if (stickyText) {
      const c = checkedColor ? checkedColor.value : '';
      const s = checkedSize ? 'Size ' + checkedSize.value : '';
      if (c || s) {
        stickyText.textContent = [c, s].filter(Boolean).join(' • ');
        stickyText.className = 'badge bg-warning text-dark border border-warning fs-11 d-none d-md-inline fw-bold';
      } else {
        stickyText.textContent = 'Mặc định';
        stickyText.className = 'badge bg-light text-muted border fs-11 d-none d-md-inline';
      }
    }
  }

  // STICKY BOTTOM BAR CONTROLLER
  window.addEventListener('scroll', function () {
    const stickyBar = document.getElementById('stickyAddToCartBar');
    const mainFormBtn = document.getElementById('btnAddToCart');
    if (!stickyBar || !mainFormBtn) return;

    const btnRect = mainFormBtn.getBoundingClientRect();
    if (btnRect.bottom < 0) {
      stickyBar.classList.remove('d-none');
    } else {
      stickyBar.classList.add('d-none');
    }
  });

  function scrollToProductOptions() {
    const target = document.getElementById('colorGroupSection') || document.getElementById('sizeGroupSection') || document.getElementById('productForm');
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  function triggerStickySubmit(isBuyNow) {
    const form = document.getElementById('productForm');
    if (!form) return;

    if (isBuyNow) {
      let buyNowInput = form.querySelector('input[name="buy_now"]');
      if (!buyNowInput) {
        buyNowInput = document.createElement('input');
        buyNowInput.type = 'hidden';
        buyNowInput.name = 'buy_now';
        buyNowInput.value = '1';
        form.appendChild(buyNowInput);
      } else {
        buyNowInput.value = '1';
      }
    }

    if (handleProductFormSubmit({ preventDefault: () => {} })) {
      form.submit();
    }
  }

  // SMART SIZE FIT CALCULATOR
  function calculateSmartFitSize() {
    const height = parseInt(document.getElementById('calcHeight').value) || 170;
    const weight = parseInt(document.getElementById('calcWeight').value) || 65;

    let suggestedSize = 'L';
    let description = '';

    if (weight < 55 && height < 165) {
      suggestedSize = 'S';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, dáng người gọn nhẹ, bạn nên chọn Size S để áo ôm vừa vặn, không bị thùng thình.`;
    } else if (weight <= 65 && height <= 172) {
      suggestedSize = 'M';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, dáng người cân đối chuẩn, Size M sẽ giúp tôn dáng cực đẹp và thoải mái cả ngày.`;
    } else if (weight <= 74 && height <= 178) {
      suggestedSize = 'L';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, vóc dáng đậm người cao ráo, Size L là lựa chọn hoàn hảo nhất cho phom dáng Regular Fit.`;
    } else if (weight <= 82 && height <= 184) {
      suggestedSize = 'XL';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, dáng to cao đầy đặn, Size XL sẽ mang lại sự thoải mái tối đa cho phần ngực và vai.`;
    } else {
      suggestedSize = 'XXL';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, bạn nên chọn Size XXL để đảm bảo sự rộng rãi, thoải mái khi vận động.`;
    }

    document.getElementById('suggestedSizeBadge').textContent = suggestedSize;
    document.getElementById('suggestedSizeName').textContent = 'Size ' + suggestedSize;
    document.getElementById('suggestedSizeDesc').textContent = description;
  }

  function applySuggestedSize() {
    const suggestedSize = document.getElementById('suggestedSizeBadge').textContent.trim();
    const sizeRadios = document.querySelectorAll('.product-size-radio');
    let found = false;

    sizeRadios.forEach(radio => {
      if (radio.value.toUpperCase() === suggestedSize.toUpperCase()) {
        radio.checked = true;
        radio.dispatchEvent(new Event('change'));
        found = true;
      }
    });

    // Đóng modal
    const modalEl = document.getElementById('sizeGuideModal');
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }

    if (found) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'success',
          title: 'Đã Chọn ' + suggestedSize + '!',
          text: 'Hệ thống đã tự động áp dụng kích cỡ này vào đơn hàng của bạn.',
          timer: 2000,
          showConfirmButton: false,
          toast: true,
          position: 'top-end'
        });
      }
    }
  }

  // COPY COUPON CODE FUNCTION (SHOPEE STYLE)
  function copyCouponCode(code) {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(code).then(() => {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Đã Sao Chép Mã: ' + code,
            text: 'Mã giảm giá đã được lưu vào bộ nhớ tạm. Hãy dán mã vào bước thanh toán nhé!',
            timer: 2500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
          });
        } else {
          alert('Đã sao chép mã giảm giá: ' + code);
        }
      });
    }
  }

  const PRODUCT_UNIT_PRICE = {{ $product->price }};

  function updateQtyDisplay(val) {
    const input = document.getElementById('productQty');
    const badge = document.getElementById('showQtyLiveBadge');
    const subtotal = document.getElementById('productSubtotalLive');
    const btnMinus = document.getElementById('btnMinusQty');
    const btnPlus = document.getElementById('btnPlusQty');
    const maxMsg = document.getElementById('maxLimitMsg');

    if (input) input.value = val;
    if (badge) {
      badge.textContent = val;
      badge.classList.remove('animate-scale');
      void badge.offsetWidth;
      badge.classList.add('animate-scale');
    }
    if (subtotal) {
      const total = PRODUCT_UNIT_PRICE * val;
      subtotal.textContent = total.toLocaleString('vi-VN') + '₫';
    }
    if (btnMinus) {
      btnMinus.disabled = (val <= 1);
      btnMinus.style.opacity = (val <= 1) ? '0.45' : '1';
    }
    if (btnPlus) {
      btnPlus.disabled = (val >= 10);
      btnPlus.style.opacity = (val >= 10) ? '0.45' : '1';
    }
    if (maxMsg) {
      if (val >= 10) {
        maxMsg.classList.remove('d-none');
      } else {
        maxMsg.classList.add('d-none');
      }
    }
  }

  function stepProductQty(amount) {
    const input = document.getElementById('productQty');
    if (!input) return;
    let val = parseInt(input.value) || 1;
    val += amount;
    if (val < 1) val = 1;
    if (val > 10) val = 10;
    updateQtyDisplay(val);
  }

  function validateProductQty(input) {
    let val = parseInt(input.value);
    if (isNaN(val) || val < 1) val = 1;
    if (val > 10) val = 10;
    updateQtyDisplay(val);
  }

  function showFormAlert(text) {
    const alertEl = document.getElementById('productFormAlert');
    const textEl = document.getElementById('productFormAlertText');
    if (alertEl && textEl) {
      textEl.textContent = text;
      alertEl.classList.remove('d-none');
      alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  function hideFormAlert() {
    const alertEl = document.getElementById('productFormAlert');
    if (alertEl) alertEl.classList.add('d-none');
  }

  function handleProductFormSubmit(e) {
    if (!IS_AUTHENTICATED) {
      e.preventDefault();
      requireAuthPrompt('thêm sản phẩm vào giỏ hàng hoặc mua ngay');
      return false;
    }

    const checkedColor = document.querySelector('input[name="color"]:checked');
    const hasColors = document.querySelectorAll('input[name="color"]').length > 0;
    if (hasColors && !checkedColor) {
      e.preventDefault();
      const colorSec = document.getElementById('colorGroupSection');
      if (colorSec) {
        colorSec.style.borderColor = '#e11d48';
        colorSec.style.backgroundColor = '#fff1f2';
        colorSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      showFormAlert('Vui lòng chọn 1 Màu Sắc cho sản phẩm trước khi mua hàng!');
      return false;
    }

    const checkedSize = document.querySelector('input[name="size"]:checked');
    const hasSizes = document.querySelectorAll('input[name="size"]').length > 0;
    if (hasSizes && !checkedSize) {
      e.preventDefault();
      const sizeSec = document.getElementById('sizeGroupSection');
      if (sizeSec) {
        sizeSec.style.borderColor = '#e11d48';
        sizeSec.style.backgroundColor = '#fff1f2';
        sizeSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      showFormAlert('Vui lòng chọn 1 Kích Thước (Size) cho sản phẩm trước khi mua hàng!');
      return false;
    }

    const qtyInput = document.getElementById('productQty');
    if (qtyInput) {
      let qty = parseInt(qtyInput.value) || 1;
      if (qty > 10) {
        qtyInput.value = 10;
      }
    }

    return true;
  }

  // Hàm xem trước ảnh tải lên cho Form đánh giá
  function previewReviewPhotos(input, previewContainerId) {
    const container = document.getElementById(previewContainerId);
    if (!container) return;
    container.innerHTML = '';

    if (input.files && input.files.length > 0) {
      const maxFiles = Math.min(input.files.length, 5);
      const countEl = document.getElementById('reviewImgCount');
      if (countEl) countEl.textContent = `Đã chọn ${maxFiles}/5 ảnh`;

      for (let i = 0; i < maxFiles; i++) {
        const file = input.files[i];
        const reader = new FileReader();
        reader.onload = function (e) {
          const wrapper = document.createElement('div');
          wrapper.className = 'position-relative animate-scale';
          wrapper.innerHTML = `
            <img src="${e.target.result}" class="rounded border shadow-sm" style="width: 58px; height: 58px; object-fit: cover;">
            <span class="badge bg-danger position-absolute top-0 end-0 p-0.5 rounded-circle" style="transform: translate(30%, -30%); cursor: pointer;" onclick="this.parentElement.remove();">
              <i class="fa-solid fa-xmark" style="font-size: 0.6rem;"></i>
            </span>
          `;
          container.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
      }
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    // Tự động kích hoạt Tab Đánh giá và cuộn xuống nếu URL có hash #reviews hoặc param review=1
    if (window.location.hash === '#reviews' || window.location.search.includes('review=1')) {
      const reviewTabBtn = document.getElementById('reviews-tab');
      if (reviewTabBtn) {
        const tabTrigger = new bootstrap.Tab(reviewTabBtn);
        tabTrigger.show();
        
        setTimeout(() => {
          const reviewSection = document.getElementById('reviews');
          if (reviewSection) {
            reviewSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            
            const commentInput = reviewSection.querySelector('textarea[name="comment"]');
            if (commentInput) {
              commentInput.focus();
              commentInput.style.boxShadow = '0 0 0 4px rgba(225, 29, 72, 0.25)';
              commentInput.style.borderColor = '#e11d48';
            }
          }
        }, 300);
      }
    }

    // Xử lý gửi Form Đánh Giá AJAX trực tiếp trên Trang Sản Phẩm
    const pageReviewForm = document.getElementById('productPageReviewForm');
    if (pageReviewForm) {
      pageReviewForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const ratingInput = pageReviewForm.querySelector('input[name="rating"]:checked');
        const commentInput = document.getElementById('productPageCommentInput');
        const submitBtn = document.getElementById('productReviewSubmitBtn');
        const alertBox = document.getElementById('productReviewAlertBox');
        const fileInput = document.getElementById('reviewImagesInput');

        if (!ratingInput || !commentInput || !commentInput.value.trim()) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang gửi...';

        const formData = new FormData(pageReviewForm);

        fetch(pageReviewForm.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> CẬP NHẬT ĐÁNH GIÁ';

          if (data.success) {
            if (alertBox) {
              alertBox.className = 'alert alert-success border-0 py-2.5 px-3 rounded-3 mb-3 small';
              alertBox.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> ${data.message}`;
              alertBox.classList.remove('d-none');
            }

            const pTitle = document.getElementById('productReviewFormTitle');
            if (pTitle) pTitle.textContent = 'Cập Nhật Đánh Giá & Hình Ảnh Của Bạn';
            const pBtn = document.getElementById('productReviewBtnText');
            if (pBtn) pBtn.textContent = 'CẬP NHẬT ĐÁNH GIÁ';

            if (data.product_reviews_count) {
              const headerCount = document.getElementById('productPageReviewsCountHeader');
              if (headerCount) headerCount.textContent = `(${data.product_reviews_count})`;
            }

            // Render đánh giá vừa gửi lên đầu danh sách nhận xét
            if (data.review) {
              const listEl = document.getElementById('productPageReviewsList');
              if (listEl) {
                // Xóa empty message nếu có
                if (listEl.querySelector('.fa-comment-dots')) {
                  listEl.innerHTML = '';
                }

                let starsHtml = '';
                const rRating = parseInt(data.review.rating) || 5;
                for (let i = 1; i <= 5; i++) {
                  starsHtml += `<i class="fa-solid fa-star ${i <= rRating ? 'text-warning' : 'text-secondary-subtle'}"></i>`;
                }

                let photosHtml = '';
                if (data.review.images && data.review.images.length > 0) {
                  photosHtml += '<div class="d-flex gap-2 flex-wrap mt-2.5 pt-2 border-top border-secondary border-opacity-10">';
                  data.review.images.forEach(photo => {
                    photosHtml += `
                      <div class="position-relative" style="cursor: pointer;" onclick="openReviewImageLightbox('${photo}')">
                        <img src="${photo}" alt="Ảnh đánh giá" class="rounded border shadow-xs" style="width: 68px; height: 68px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <span class="position-absolute bottom-0 end-0 bg-dark text-white px-1 py-0.5 rounded-start" style="font-size: 0.65rem; opacity: 0.85;">
                          <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </span>
                      </div>
                    `;
                  });
                  photosHtml += '</div>';
                }

                const itemDiv = document.createElement('div');
                itemDiv.className = 'p-3 bg-light rounded-3 border animate-fade-in';
                itemDiv.id = 'page-review-item-' + (data.review.id || 'new');
                itemDiv.innerHTML = `
                  <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                    <div class="d-flex align-items-center gap-2">
                      <img src="${data.review.user_avatar}" alt="${data.review.user_name}" class="rounded-circle border bg-white" style="width: 34px; height: 34px; object-fit: cover;">
                      <div>
                        <strong class="text-dark fs-9">${data.review.user_name}</strong>
                        <span class="badge bg-success-subtle text-success ms-2 small" style="font-size: 0.75rem;">
                          <i class="fa-solid fa-circle-check me-1"></i> Đã mua hàng
                        </span>
                      </div>
                    </div>
                    <small class="text-muted">${data.review.time_ago || 'Vừa xong'}</small>
                  </div>
                  <div class="text-warning small mb-2">
                    ${starsHtml} <span class="text-dark fw-bold ms-1">(${rRating}/5)</span>
                  </div>
                  <p class="small text-secondary mb-0 leading-relaxed">
                    ${data.review.comment}
                  </p>
                  ${photosHtml}
                `;

                const existingItem = document.getElementById('page-review-item-' + data.review.id);
                if (existingItem) {
                  existingItem.replaceWith(itemDiv);
                } else {
                  listEl.insertBefore(itemDiv, listEl.firstChild);
                }
              }
            }

            // Scroll nhẹ xuống nhận xét
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          } else {
            alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
            alertBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> ${data.message || 'Có lỗi xảy ra khi gửi nhận xét'}`;
            alertBox.classList.remove('d-none');
          }
        })
        .catch(err => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> GỬI ĐÁNH GIÁ';
          alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
          alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> Có lỗi xảy ra khi gửi nhận xét.';
          alertBox.classList.remove('d-none');
        });
      });
    }
  });

  // Filter Reviews by Stars or Photos
  function filterReviews(filterType, btnEl) {
    document.querySelectorAll('.filter-review-btn').forEach(btn => {
      btn.classList.remove('btn-dark', 'active', 'shadow-xs');
      btn.classList.add('btn-outline-secondary');
    });
    btnEl.classList.remove('btn-outline-secondary');
    btnEl.classList.add('btn-dark', 'active', 'shadow-xs');

    const items = document.querySelectorAll('.review-card-item');
    let visibleCount = 0;
    items.forEach(item => {
      const rating = item.getAttribute('data-rating');
      const hasPhoto = item.getAttribute('data-has-photo');
      let show = false;
      if (filterType === 'all') {
        show = true;
      } else if (filterType === 'photo') {
        show = (hasPhoto === '1');
      } else {
        show = (rating === filterType);
      }

      if (show) {
        item.style.display = '';
        visibleCount++;
      } else {
        item.style.display = 'none';
      }
    });

    const emptyEl = document.getElementById('reviewFilterEmptyMsg');
    if (emptyEl) {
      emptyEl.style.display = (visibleCount === 0) ? 'block' : 'none';
    }
  }

  // Open customer review photo in Ultra-HD Lightbox
  function openReviewImageLightbox(imgUrl) {
    const modalImg = document.getElementById('modalGalleryImg');
    if (modalImg) {
      modalImg.src = imgUrl;
      const modalEl = document.getElementById('galleryModal');
      if (modalEl) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
      }
    }
  }

  // Open Reviewer Profile Modal (Xem hồ sơ khách hàng đánh giá)
  function openReviewerModal(reviewId) {
    fetch(`/san-pham/api-reviewer-profile/${reviewId}`)
      .then(res => res.json())
      .then(data => {
        if (!data.success) return;

        document.getElementById('revModalName').textContent = data.user_name || 'Khách Hàng';
        document.getElementById('revModalAvatar').src = data.avatar_url;
        document.getElementById('revModalJoined').innerHTML = `<i class="fa-solid fa-calendar-check me-1 text-warning"></i> ${data.joined_at}`;
        document.getElementById('revModalOrdersCount').textContent = data.total_orders;
        document.getElementById('revModalReviewsCount').textContent = data.total_reviews;

        const rankBadge = document.getElementById('revModalRankBadge');
        if (rankBadge) {
          rankBadge.className = data.rank_class + ' px-2 py-0.5';
          rankBadge.innerHTML = `<i class="fa-solid ${data.rank_icon} me-1"></i> ${data.rank_name}`;
        }

        // Populate other reviews list
        const listEl = document.getElementById('revModalOtherReviewsList');
        if (listEl) {
          listEl.innerHTML = '';
          if (data.other_reviews && data.other_reviews.length > 0) {
            data.other_reviews.forEach(or => {
              let stars = '';
              for (let i = 1; i <= 5; i++) {
                stars += `<i class="fa-solid fa-star ${i <= or.rating ? 'text-warning' : 'text-secondary-subtle'}"></i>`;
              }
              const itemHtml = `
                <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-2.5">
                  <img src="${or.product_image}" alt="${or.product_name}" class="rounded border shadow-xs" style="width: 48px; height: 48px; object-fit: cover;">
                  <div class="flex-grow-1 min-w-0">
                    <a href="${or.product_url}" class="text-dark fw-bold text-decoration-none d-block text-truncate small" style="font-size: 0.8rem;">${or.product_name}</a>
                    <div class="d-flex align-items-center gap-1.5 small text-warning" style="font-size: 0.7rem;">
                      ${stars} <span class="text-muted ms-1">${or.date}</span>
                    </div>
                    <p class="text-muted mb-0 small text-truncate" style="font-size: 0.72rem;">${or.comment}</p>
                  </div>
                  <a href="${or.product_url}" class="btn btn-outline-dark btn-xs px-2 rounded-pill flex-shrink-0" style="font-size: 0.68rem;">Xem</a>
                </div>
              `;
              listEl.innerHTML += itemHtml;
            });
          } else {
            listEl.innerHTML = `
              <div class="p-3 bg-light rounded-3 text-center border">
                <small class="text-muted">Khách hàng này hiện tại chưa chia sẻ thêm bài đánh giá nào khác.</small>
              </div>
            `;
          }
        }

        // Admin Link
        const adminBox = document.getElementById('revModalAdminBox');
        const adminLink = document.getElementById('revModalAdminLink');
        if (adminBox && adminLink) {
          if (data.is_admin && data.admin_customer_url) {
            adminLink.href = data.admin_customer_url;
            adminBox.classList.remove('d-none');
          } else {
            adminBox.classList.add('d-none');
          }
        }

        // Show Modal
        const modalEl = document.getElementById('customerReviewerProfileModal');
        if (modalEl) {
          const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
          modal.show();
        }
      })
      .catch(err => console.error('Error loading reviewer profile:', err));
  }
</script>
@endpush
@endsection
