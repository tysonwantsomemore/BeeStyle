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
        <div class="position-relative bg-light rounded-4 p-4 text-center mb-3" style="min-height: 420px; display: flex; align-items: center; justify-content: center;">
          @if($product->original_price && $product->original_price > $product->price)
            <span class="position-absolute top-0 start-0 m-3 badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm">
              -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
            </span>
          @endif
          <img id="mainProductImg" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 380px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
        </div>

        <!-- THUMBNAILS -->
        <div class="d-flex gap-2 justify-content-center flex-wrap">
          <div class="border rounded-3 p-1 bg-white cursor-pointer border-danger border-2 thumb-item" style="width: 70px; height: 70px; cursor: pointer;" onclick="changeMainImg('{{ asset($product->image) }}', this)">
            <img src="{{ asset($product->image) }}" alt="thumb" class="w-100 h-100 object-fit-cover rounded">
          </div>
          @if($product->images)
            @foreach($product->images as $img)
              @if($img->image_path !== $product->image)
                <div class="border rounded-3 p-1 bg-white cursor-pointer thumb-item" style="width: 70px; height: 70px; cursor: pointer;" onclick="changeMainImg('{{ asset($img->image_path) }}', this)">
                  <img src="{{ asset($img->image_path) }}" alt="thumb" class="w-100 h-100 object-fit-cover rounded">
                </div>
              @endif
            @endforeach
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

          <!-- RATING & REVIEWS SUMMARY -->
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="text-warning small">
              @for($i=1; $i<=5; $i++)
                <i class="fa-solid fa-star {{ $i <= round($product->rating) ? 'text-warning' : 'text-secondary-subtle' }}"></i>
              @endfor
            </div>
            <span class="small fw-bold text-dark">{{ number_format($product->rating, 1) }}</span>
            <span class="text-muted small">•</span>
            <a href="#reviews" class="text-muted small text-decoration-underline" onclick="var t=new bootstrap.Tab(document.getElementById('reviews-tab')); t.show();">
              {{ $product->reviews_count }} đánh giá
            </a>
            <span class="text-muted small">•</span>
            <span class="small text-muted">Đã bán <strong>{{ number_format($product->sold_count ?? 128) }}</strong></span>
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
          <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Khách Mua ({{ $product->reviews_count }})
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
        <div class="row g-4">
          <!-- Left Column: Rating Overview & Review Form -->
          <div class="col-lg-4 col-md-5 border-end-md pe-lg-4">
            <div class="text-center p-3 bg-light rounded-3 mb-4 border">
              <h1 class="display-3 fw-bold text-warning mb-0">{{ number_format($product->rating, 1) }}</h1>
              <div class="text-warning mb-2 fs-5">
                @for($i=1; $i<=5; $i++)
                  <i class="fa-solid fa-star {{ $i <= round($product->rating) ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                @endfor
              </div>
              <p class="text-muted small mb-0">Dựa trên <strong>{{ $product->reviews_count }}</strong> lượt đánh giá từ khách hàng</p>
            </div>

            <!-- REVIEW SUBMISSION FORM BOX -->
            <div class="card border-0 shadow-sm p-3 rounded-3" style="background: #ffffff; border: 1px solid var(--atino-border) !important;">
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

          <!-- Right Column: List of Reviews -->
          <div class="col-lg-8 col-md-7 ps-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="fw-bold text-dark mb-0" style="font-family: var(--atino-font-heading);">
                Nhận Xét Từ Khách Hàng <span id="productPageReviewsCountHeader">({{ $product->reviews->count() }})</span>
              </h5>
              @php
                $reviewsWithPhotos = $product->reviews->filter(fn($r) => !empty($r->images_urls))->count();
              @endphp
              @if($reviewsWithPhotos > 0)
                <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-semibold px-2 py-1 small" id="productPagePhotosBadge">
                  <i class="fa-solid fa-image text-warning me-1"></i> {{ $reviewsWithPhotos }} đánh giá có ảnh
                </span>
              @endif
            </div>

            <div class="d-flex flex-column gap-3" id="productPageReviewsList">
              @forelse($product->reviews as $rev)
                <div class="p-3 bg-light rounded-3 border">
                  <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ $rev->user_avatar_url }}" alt="{{ $rev->user_name }}" class="rounded-circle border bg-white" style="width: 34px; height: 34px; object-fit: cover;">
                      <div>
                        <strong class="text-dark fs-9">{{ $rev->user_name }}</strong>
                        <span class="badge bg-success-subtle text-success ms-2 small" style="font-size: 0.75rem;">
                          <i class="fa-solid fa-circle-check me-1"></i> Đã mua hàng
                        </span>
                      </div>
                    </div>
                    <small class="text-muted">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : 'Vừa xong' }}</small>
                  </div>

                  <div class="text-warning small mb-2">
                    @for($i=1; $i<=5; $i++)
                      <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                    @endfor
                    <span class="text-dark fw-bold ms-1">({{ $rev->rating }}/5)</span>
                  </div>

                  <p class="small text-secondary mb-0 leading-relaxed">
                    {{ $rev->comment }}
                  </p>

                  <!-- Customer Uploaded Review Photos -->
                  @if(!empty($rev->images_urls))
                    <div class="d-flex gap-2 flex-wrap mt-2.5 pt-2 border-top border-secondary border-opacity-10">
                      @foreach($rev->images_urls as $photoUrl)
                        <div class="position-relative" style="cursor: pointer;" onclick="openReviewImageLightbox('{{ $photoUrl }}')">
                          <img src="{{ $photoUrl }}" alt="Ảnh đánh giá" class="rounded border shadow-xs" style="width: 68px; height: 68px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                          <span class="position-absolute bottom-0 end-0 bg-dark text-white px-1 py-0.5 rounded-start" style="font-size: 0.65rem; opacity: 0.85;">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                          </span>
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              @empty
                <div class="p-4 bg-light rounded-3 text-center border">
                  <i class="fa-regular fa-comment-dots fs-1 text-muted mb-2"></i>
                  <p class="text-dark fw-semibold mb-1">Chưa có đánh giá nào cho sản phẩm này</p>
                  <p class="small text-muted mb-0">Hãy là người đầu tiên mua và trải nghiệm chất lượng thời trang của BeeStyle!</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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

<!-- SIZE GUIDE MODAL -->
<div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-ruler-horizontal text-warning me-2"></i> Bảng Quy Đổi Size Nam Chuẩn BeeStyle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <p class="small text-muted mb-3">Thông số được đo chuẩn theo thể trạng người Việt Nam. Nếu bạn phân vân giữa 2 size, nên chọn size lớn hơn để mặc thoải mái.</p>
        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle small mb-0">
            <thead class="table-dark">
              <tr>
                <th>Size</th>
                <th>Chiều Cao</th>
                <th>Cân Nặng</th>
                <th>Dáng Người</th>
              </tr>
            </thead>
            <tbody>
              <tr><td><strong class="text-warning">S</strong></td><td>1m55 - 1m65</td><td>48 - 55 kg</td><td>Gầy / Nhỏ</td></tr>
              <tr><td><strong class="text-warning">M</strong></td><td>1m64 - 1m72</td><td>56 - 65 kg</td><td>Cân đối</td></tr>
              <tr><td><strong class="text-warning">L</strong></td><td>1m70 - 1m78</td><td>66 - 74 kg</td><td>Đậm người / Cao</td></tr>
              <tr><td><strong class="text-warning">XL</strong></td><td>1m75 - 1m83</td><td>75 - 82 kg</td><td>To cao</td></tr>
              <tr><td><strong class="text-warning">XXL</strong></td><td>1m80 - 1m90</td><td>83 - 92 kg</td><td>Ngoại cỡ</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function changeMainImg(src, el) {
    document.getElementById('mainProductImg').src = src;
    document.querySelectorAll('.thumb-item').forEach(item => {
      item.classList.remove('border-danger', 'border-2');
    });
    if (el) {
      el.classList.add('border-danger', 'border-2');
    }
  }

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

  // ========================================================
  // BỘ TÍNH SIZE THÔNG MINH AI & TỰ ĐỘNG CHỌN SIZE CHO KHÁCH
  // ========================================================
  let currentTabCalculatedSize = 'L';
  let currentModalCalculatedSize = 'L';

  function calculateSizeByHeightWeight(h, w) {
    h = parseInt(h) || 170;
    w = parseInt(w) || 65;
    let recSize = 'L';
    let desc = 'Vừa vặn thoải mái, tôn dáng chuẩn phom';

    if (w < 58 && h < 168) {
      recSize = 'S';
      desc = 'Chuẩn phom dáng vừa vặn, gọn gàng (50-58kg, 1m55-1m65)';
    } else if (w <= 65 && h <= 173) {
      recSize = 'M';
      desc = 'Phom dáng Slim Fit hiện đại, ôm nhẹ (58-65kg, 1m65-1m72)';
    } else if (w <= 72 && h <= 178) {
      recSize = 'L';
      desc = 'Vừa vặn thoải mái, vận động tự tin cả ngày (65-72kg, 1m70-1m77)';
    } else if (w <= 80 && h <= 183) {
      recSize = 'XL';
      desc = 'Phom Regular Fit rộng rãi, thoáng mát (72-80kg, 1m75-1m82)';
    } else if (w <= 88) {
      recSize = 'XXL';
      desc = 'Form dáng rộng thoải mái, che khuyết điểm (80-88kg, 1m78-1m88)';
    } else {
      recSize = '3XL';
      desc = 'Form dáng cực rộng thoải mái (> 88kg)';
    }
    return { size: recSize, desc: desc, height: h, weight: w };
  }

  // 1. Tính toán trong Tab 3 (Dưới phần Tabs chi tiết)
  function calculateTabRecommendedSize() {
    const hInput = document.getElementById('tabCalcHeight');
    const wInput = document.getElementById('tabCalcWeight');
    const h = hInput ? hInput.value : 170;
    const w = wInput ? wInput.value : 65;
    const res = calculateSizeByHeightWeight(h, w);
    currentTabCalculatedSize = res.size;

    const badge = document.getElementById('tabRecommendedSizeBadge');
    const descEl = document.getElementById('tabRecommendedSizeDesc');
    const resBox = document.getElementById('tabCalcSizeResult');

    if (badge) badge.textContent = 'Size ' + res.size;
    if (descEl) descEl.textContent = res.desc;
    if (resBox) resBox.classList.remove('d-none');
  }

  function applyTabRecommendedSize() {
    applySizeToProductForm(currentTabCalculatedSize);
  }

  // 2. Tính toán trong Modal Hướng Dẫn Chọn Size
  function calculateModalSmartFitSize() {
    const hInput = document.getElementById('modalCalcHeight');
    const wInput = document.getElementById('modalCalcWeight');
    const h = hInput ? hInput.value : 170;
    const w = wInput ? wInput.value : 65;
    const res = calculateSizeByHeightWeight(h, w);
    currentModalCalculatedSize = res.size;

    const badge = document.getElementById('suggestedSizeBadge');
    const nameEl = document.getElementById('suggestedSizeName');
    const descEl = document.getElementById('suggestedSizeDesc');

    if (badge) badge.textContent = res.size;
    if (nameEl) nameEl.textContent = `Size ${res.size} (Phom Regular Fit)`;
    if (descEl) descEl.textContent = `Dựa trên chiều cao ${res.height}cm và cân nặng ${res.weight}kg, size ${res.size} sẽ mang lại cảm giác thoải mái và tôn dáng nhất (${res.desc}).`;
  }

  function applySuggestedSize() {
    applySizeToProductForm(currentModalCalculatedSize);
  }

  // 3. Hàm áp dụng size trực tiếp vào Form mua hàng (chọn radio size, kích hoạt tick vàng, cuộn lên form)
  function applySizeToProductForm(size) {
    if (!size) return;
    const sizeRadios = document.querySelectorAll('.product-size-radio');
    let matched = false;
    sizeRadios.forEach(r => {
      if (r.value.toUpperCase() === size.toUpperCase()) {
        r.checked = true;
        matched = true;
        r.dispatchEvent(new Event('change'));
      }
    });

    // Gọi trực tiếp hàm selectProductSize nếu có
    if (typeof selectProductSize === 'function') {
      selectProductSize(size, typeof getShowSizeHint === 'function' ? getShowSizeHint(size) : '');
    }

    // Đóng modal Size Guide nếu đang mở
    const modalEl = document.getElementById('sizeGuideModal');
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }

    // Cuộn mượt mà lên vị trí chọn size
    const sizeSec = document.getElementById('sizeGroupSection');
    if (sizeSec) {
      sizeSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
      sizeSec.style.border = '2px solid #f59e0b';
      sizeSec.style.backgroundColor = '#fffbeb';
      sizeSec.style.boxShadow = '0 0 16px rgba(245, 158, 11, 0.35)';
      setTimeout(() => {
        sizeSec.style.border = '1px solid var(--atino-border)';
        sizeSec.style.backgroundColor = '#ffffff';
        sizeSec.style.boxShadow = 'none';
      }, 2500);
    }

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'success',
        title: 'Đã Chọn Size Thành Công!',
        text: matched 
          ? `Hệ thống đã tự động chọn Size ${size} cho bạn. Bạn chỉ cần chọn thêm Màu sắc là có thể đặt hàng ngay!`
          : `Sản phẩm này hiện có các size khác. Bạn có thể chọn size phù hợp trong danh sách bên trên!`,
        toast: true,
        position: 'top-end',
        timer: 3500,
        showConfirmButton: false
      });
    }
  }

  // HIỆU ỨNG TƯƠNG TÁC LƯỢT XEM TRỰC TIẾP (LIVE VIEWERS PULSE)
  setInterval(() => {
    const viewerEl = document.getElementById('liveViewerCount');
    if (viewerEl) {
      const current = parseInt(viewerEl.textContent) || 14;
      const delta = (Math.random() > 0.5 ? 1 : -1) * Math.floor(Math.random() * 3 + 1);
      let next = current + delta;
      if (next < 9) next = 11;
      if (next > 26) next = 22;
      viewerEl.textContent = next;
    }
  }, 9000);

  // HIỆU ỨNG THÔNG BÁO MUA HÀNG THỜI GIAN THỰC (REALTIME SOCIAL PROOF TOAST)
  const sampleBuyers = [
    { name: 'Anh Minh Tuấn', loc: 'Đống Đa, Hà Nội', time: '2 phút trước', variant: 'Size L / Đen' },
    { name: 'Anh Hoàng Nam', loc: 'Quận 1, TP.HCM', time: '5 phút trước', variant: 'Size XL / Trắng' },
    { name: 'Anh Đức Hải', loc: 'Hải Châu, Đà Nẵng', time: '8 phút trước', variant: 'Size M / Xanh Navy' },
    { name: 'Anh Quốc Bảo', loc: 'Cầu Giấy, Hà Nội', time: '11 phút trước', variant: 'Size L / Xám Ghi' },
    { name: 'Anh Việt Hưng', loc: 'Thủ Đức, TP.HCM', time: '14 phút trước', variant: 'Size XXL / Đen' },
    { name: 'Anh Quang Huy', loc: 'Ninh Kiều, Cần Thơ', time: '18 phút trước', variant: 'Size M / Be' }
  ];

  function triggerLivePurchaseToast() {
    const randomBuyer = sampleBuyers[Math.floor(Math.random() * sampleBuyers.length)];
    let toastContainer = document.getElementById('beeLivePurchaseToast');
    
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.id = 'beeLivePurchaseToast';
      toastContainer.style.position = 'fixed';
      toastContainer.style.bottom = '24px';
      toastContainer.style.left = '24px';
      toastContainer.style.zIndex = '1070';
      toastContainer.style.maxWidth = '360px';
      toastContainer.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
      toastContainer.style.transform = 'translateY(120px)';
      toastContainer.style.opacity = '0';
      document.body.appendChild(toastContainer);
    }

    toastContainer.innerHTML = `
      <div class="card border-0 shadow-lg p-2.5 rounded-4 bg-white border border-warning-subtle d-flex flex-row align-items-center gap-2.5">
        <div class="position-relative flex-shrink-0">
          <img src="{{ asset($product->image) }}" class="rounded-3 border bg-light" style="width: 52px; height: 52px; object-fit: contain;">
          <span class="position-absolute top-0 start-0 badge bg-danger rounded-circle p-1" style="transform: translate(-30%, -30%);">
            <i class="fa-solid fa-bolt" style="font-size: 8px;"></i>
          </span>
        </div>
        <div class="flex-grow-1 min-w-0">
          <div class="d-flex justify-content-between align-items-center mb-0.5">
            <strong class="text-dark small text-truncate" style="font-size: 0.82rem;">${randomBuyer.name}</strong>
            <small class="text-muted" style="font-size: 0.7rem;">${randomBuyer.time}</small>
          </div>
          <p class="mb-0 text-muted small text-truncate" style="font-size: 0.74rem;">
            Vừa đặt mua <strong class="text-dark">{{ $product->name }}</strong>
          </p>
          <div class="d-flex align-items-center gap-1.5 mt-0.5" style="font-size: 0.68rem;">
            <span class="text-success fw-bold"><i class="fa-solid fa-circle-check"></i> Đã xác nhận</span>
            <span class="text-muted">• ${randomBuyer.loc}</span>
          </div>
        </div>
        <button type="button" class="btn-close btn-sm p-1 align-self-start" onclick="this.closest('#beeLivePurchaseToast').style.opacity='0'; this.closest('#beeLivePurchaseToast').style.transform='translateY(120px)'" style="font-size: 0.65rem;"></button>
      </div>
    `;

    setTimeout(() => {
      toastContainer.style.opacity = '1';
      toastContainer.style.transform = 'translateY(0)';
    }, 100);

    setTimeout(() => {
      toastContainer.style.opacity = '0';
      toastContainer.style.transform = 'translateY(120px)';
    }, 5500);
  }

  // ========================================================
  // THANH MUA HÀNG NỔI DƯỚI ĐÁY (STICKY FLOATING PURCHASE BAR)
  // ========================================================
  const stickyBar = document.getElementById('stickyAddToCartBar');

  function updateStickyBarVariantInfo() {
    const variantBadge = document.getElementById('stickySelectedVariantText');
    const subtotalEl = document.getElementById('stickySubtotalText');
    const qtyInput = document.getElementById('productQty');
    const qty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;

    if (variantBadge) {
      if (selectedProductColor && selectedProductSize) {
        variantBadge.textContent = `${selectedProductColor} / Size ${selectedProductSize} (SL: ${qty})`;
        variantBadge.className = 'badge bg-dark text-warning border border-warning px-2 py-0.5 fw-bold';
      } else if (selectedProductColor) {
        variantBadge.textContent = `${selectedProductColor} / Chưa chọn size`;
        variantBadge.className = 'badge bg-light text-warning border border-warning px-2 py-0.5';
      } else if (selectedProductSize) {
        variantBadge.textContent = `Chưa chọn màu / Size ${selectedProductSize}`;
        variantBadge.className = 'badge bg-light text-warning border border-warning px-2 py-0.5';
      } else {
        variantBadge.textContent = 'Chưa chọn màu & size';
        variantBadge.className = 'badge bg-light text-muted border px-2 py-0.5';
      }
    }

    if (subtotalEl) {
      const unitPrice = currentProductUnitPrice || {{ (int)$product->price }};
      const total = unitPrice * qty;
      subtotalEl.textContent = total.toLocaleString('vi-VN') + '₫';
    }
  }

  // Xử lý hiện / ẩn thanh dính khi cuộn chuột qua form sản phẩm
  window.addEventListener('scroll', function () {
    if (!stickyBar) return;
    const triggerPoint = 460;
    if (window.scrollY > triggerPoint) {
      stickyBar.style.transform = 'translateY(0)';
    } else {
      stickyBar.style.transform = 'translateY(120%)';
    }
  });

  // Xử lý bấm nút trên thanh Sticky Bottom Bar
  function triggerStickySubmit(isBuyNow) {
    if (!selectedProductColor || !selectedProductSize) {
      // Cuộn mượt mà lên vùng chọn biến thể
      const targetSec = (!selectedProductColor) ? document.getElementById('colorGroupSection') : document.getElementById('sizeGroupSection');
      if (targetSec) {
        targetSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
        targetSec.style.border = '2px solid #ef4444';
        targetSec.style.backgroundColor = '#fef2f2';
        setTimeout(() => {
          targetSec.style.border = '1px solid var(--atino-border)';
          targetSec.style.backgroundColor = '#ffffff';
        }, 2500);
      }

      const alertEl = document.getElementById('productFormAlert');
      if (alertEl) {
        alertEl.classList.remove('d-none');
        document.getElementById('productFormAlertText').textContent = 'Quý khách vui lòng chọn Màu sắc và Kích thước (Size) trước khi tiếp tục!';
      }

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'warning',
          title: 'Chưa Chọn Biến Thể',
          text: 'Quý khách vui lòng chọn Màu sắc và Kích thước (Size) trước khi đặt hàng!',
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });
      }
      return;
    }

    const form = document.getElementById('productForm');
    if (form) {
      if (isBuyNow) {
        let buyNowInput = form.querySelector('input[name="buy_now"]');
        if (!buyNowInput) {
          buyNowInput = document.createElement('input');
          buyNowInput.type = 'hidden';
          buyNowInput.name = 'buy_now';
          form.appendChild(buyNowInput);
        }
        buyNowInput.value = '1';
      }
      form.submit();
    }
  }

  // Khởi tạo trạng thái ban đầu cho thanh Sticky Bar
  setTimeout(updateStickyBarVariantInfo, 500);
</script>
@endpush
