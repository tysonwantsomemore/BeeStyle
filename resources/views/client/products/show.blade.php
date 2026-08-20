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
  <div class="card border-0 shadow-sm p-4 p-md-5 mb-5" style="border-radius: 20px; background: #ffffff;">
    <div class="row g-4 g-lg-5">
      
      <!-- IMAGE GALLERY -->
      <div class="col-lg-6">
        <div class="position-relative bg-light rounded-4 p-4 text-center mb-3" style="min-height: 420px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--bee-border);">
          @if($product->discount_percent > 0)
            <span class="position-absolute top-0 start-0 m-3 bee-product-badge sale fs-6 px-3 py-2">-{{ $product->discount_percent }}%</span>
          @elseif($product->is_new)
            <span class="position-absolute top-0 start-0 m-3 bee-product-badge new fs-6 px-3 py-2">NEW ARRIVAL</span>
          @endif
          <img id="mainProductImg" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 380px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
        </div>

        <!-- THUMBNAILS -->
        <div class="d-flex gap-2 justify-content-center flex-wrap">
          <div class="border rounded-3 p-1 bg-white cursor-pointer border-warning border-2" style="width: 72px; height: 72px; cursor: pointer;" onclick="document.getElementById('mainProductImg').src='{{ asset($product->image) }}'">
            <img src="{{ asset($product->image) }}" alt="thumb" class="w-100 h-100 object-fit-cover rounded-2">
          </div>
          @if($product->images)
            @foreach($product->images as $img)
              @if($img->image_path !== $product->image)
                <div class="border rounded-3 p-1 bg-white cursor-pointer" style="width: 72px; height: 72px; cursor: pointer;" onclick="document.getElementById('mainProductImg').src='{{ asset($img->image_path) }}'">
                  <img src="{{ asset($img->image_path) }}" alt="thumb" class="w-100 h-100 object-fit-cover rounded-2">
                </div>
              @endif
            @endforeach
          @endif
        </div>
      </div>

      <!-- PRODUCT INFO & ACTIONS -->
      <div class="col-lg-6">
        <div class="ps-lg-2">
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-warning-subtle text-dark fw-bold px-3 py-1 rounded-pill">{{ $product->category->name ?? 'Thời trang nam' }}</span>
            <span class="text-muted small">Mã SKU: <strong>{{ $product->sku }}</strong></span>
            <span class="badge bg-success-subtle text-success ms-auto"><i class="fa-solid fa-circle-check me-1"></i> Còn {{ $product->stock }} sản phẩm</span>
          </div>

          <h1 class="fw-bold text-dark mb-3" style="font-size: 1.65rem; line-height: 1.35; letter-spacing: -0.02em;">
            {{ $product->name }}
          </h1>

          <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
            <div class="d-flex align-items-center text-warning gap-1">
              @for($i=1; $i<=5; $i++)
                <i class="fa-solid fa-star"></i>
              @endfor
              <span class="fw-bold text-dark ms-1">{{ $product->rating }}</span>
            </div>
            <span class="text-secondary">|</span>
            <a href="#reviewsTab" class="text-muted small text-decoration-none">{{ $product->reviews_count }} đánh giá từ khách mua</a>
            <span class="text-secondary">|</span>
            <span class="text-muted small">Đã bán <strong>{{ $product->sold_count }}</strong></span>
          </div>

          <!-- PRICE ROW -->
          <div class="d-flex align-items-baseline gap-3 p-3 bg-light rounded-3 mb-4 border">
            <span class="fs-2 fw-bold text-danger">{{ number_format($product->price, 0, ',', '.') }}₫</span>
            @if($product->original_price && $product->original_price > $product->price)
              <span class="text-muted text-decoration-line-through fs-6">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
              <span class="badge bg-danger rounded-pill px-2 py-1">Tiết kiệm {{ number_format($product->original_price - $product->price, 0, ',', '.') }}₫</span>
            @endif
          </div>

          <p class="text-secondary small mb-4 leading-relaxed">
            {{ $product->short_description }}
          </p>

          <form action="{{ route('client.cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <!-- COLOR SELECTOR -->
            <div class="mb-4">
              <label class="fw-bold small text-dark mb-2 d-block">1. Chọn Màu Sắc:</label>
              <div class="d-flex flex-wrap gap-2">
                @if(is_array($product->colors))
                  @foreach($product->colors as $index => $color)
                    <div>
                      <input type="radio" class="bee-color-radio" name="color" id="color_{{ $index }}" value="{{ $color }}" {{ $index === 0 ? 'checked' : '' }}>
                      <label class="bee-color-label" for="color_{{ $index }}">
                        <i class="fa-solid fa-circle text-warning fs-11"></i> {{ $color }}
                      </label>
                    </div>
                  @endforeach
                @else
                  <div>
                    <input type="radio" class="bee-color-radio" name="color" id="color_0" value="Tiêu chuẩn" checked>
                    <label class="bee-color-label" for="color_0">Tiêu chuẩn</label>
                  </div>
                @endif
              </div>
            </div>

            <!-- SIZE SELECTOR -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="fw-bold small text-dark mb-0">2. Chọn Kích Thước (Size Chuẩn):</label>
                <a href="#sizeGuideModal" data-bs-toggle="modal" class="text-warning small text-decoration-none fw-semibold"><i class="fa-solid fa-ruler-horizontal me-1"></i> Bảng quy đổi Size</a>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @if(is_array($product->sizes))
                  @foreach($product->sizes as $index => $size)
                    <div>
                      <input type="radio" class="bee-size-radio" name="size" id="size_{{ $index }}" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }}>
                      <label class="bee-size-label" for="size_{{ $index }}">{{ $size }}</label>
                    </div>
                  @endforeach
                @else
                  <div>
                    <input type="radio" class="bee-size-radio" name="size" id="size_0" value="Free Size" checked>
                    <label class="bee-size-label" for="size_0">Free Size</label>
                  </div>
                @endif
              </div>
            </div>

            <!-- QUANTITY -->
            <div class="mb-4">
              <label class="fw-bold small text-dark mb-2 d-block">3. Số Lượng Mua:</label>
              <div class="d-flex align-items-center gap-3">
                <div class="input-group" style="width: 130px;">
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var q=document.getElementById('qtyInput'); if(q.value>1) q.value--;">-</button>
                  <input type="number" id="qtyInput" name="quantity" class="form-control form-control-sm text-center fw-bold" value="1" min="1" max="{{ $product->stock }}">
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var q=document.getElementById('qtyInput'); if(q.value<{{ $product->stock }}) q.value++;">+</button>
                </div>
                <span class="text-muted small">Kho hàng: <strong>{{ $product->stock }}</strong> sản phẩm</span>
              </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex gap-3 mb-4">
              <button type="submit" class="btn btn-bee-primary px-4 py-3 flex-grow-1 fs-6">
                <i class="fa-solid fa-cart-plus me-1"></i> Thêm Vào Giỏ Hàng
              </button>
              <button type="submit" name="buy_now" value="1" class="btn btn-bee-dark px-4 py-3 flex-grow-1 fs-6">
                <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
              </button>
            </div>
          </form>

          <!-- VALUE PROPOSITIONS -->
          <div class="border rounded-3 p-3 bg-light-subtle">
            <div class="row g-2 small">
              <div class="col-6"><i class="fa-solid fa-shield-halved text-warning me-2"></i> Cam kết 100% chính hãng</div>
              <div class="col-6"><i class="fa-solid fa-truck-fast text-warning me-2"></i> Giao hỏa tốc 24h - 48h</div>
              <div class="col-6"><i class="fa-solid fa-box-open text-warning me-2"></i> Kiểm tra hàng trước khi nhận</div>
              <div class="col-6"><i class="fa-solid fa-arrows-rotate text-warning me-2"></i> Đổi size tận nhà trong 30 ngày</div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- DETAILS & REVIEWS TABS -->
  <div class="card border-0 shadow-sm p-4 mb-5" style="border-radius: 16px;">
    <ul class="nav nav-tabs border-bottom" id="productTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold text-dark px-4 py-3" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc" type="button" role="tab">
          <i class="fa-solid fa-circle-info me-2 text-warning"></i> Mô Tả Chi Tiết
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-dark px-4 py-3" id="guide-tab" data-bs-toggle="tab" data-bs-target="#guide" type="button" role="tab">
          <i class="fa-solid fa-ruler-combined me-2 text-warning"></i> Bảng Thông Số Size
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-dark px-4 py-3" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
          <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Khách Mua ({{ $product->reviews_count }})
        </button>
      </li>
    </ul>

    <div class="tab-content p-4" id="productTabsContent">
      <!-- Tab 1: Description -->
      <div class="tab-pane fade show active" id="desc" role="tabpanel">
        <h5 class="fw-bold mb-3 text-dark">Thông tin chi tiết về sản phẩm</h5>
        <p class="leading-relaxed text-secondary mb-4">{{ $product->description }}</p>
        
        <h6 class="fw-bold mb-2">Đặc điểm nổi bật:</h6>
        <ul class="text-secondary leading-relaxed small">
          <li>Chất liệu sợi tự nhiên cao cấp được xử lý chống co rút, kháng nhăn và thoáng khí tối đa.</li>
          <li>Kỹ thuật may đúp tỉ mỉ giúp sản phẩm luôn giữ được form dáng chuẩn sau nhiều lần giặt.</li>
          <li>Đường may kép tỉ mỉ, bo cổ và tay áo tinh tế, không bai dão theo thời gian.</li>
          <li>Dễ dàng phối đồ linh hoạt: đi làm công sở, dự sự kiện, dạo phố hay gặp gỡ bạn bè.</li>
        </ul>
      </div>

      <!-- Tab 2: Size Guide -->
      <div class="tab-pane fade" id="guide" role="tabpanel">
        <h5 class="fw-bold mb-3 text-dark">Bảng thông số kích cỡ chuẩn BeeStyle Menswear</h5>
        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>Size</th>
                <th>Chiều Cao (cm)</th>
                <th>Cân Nặng (kg)</th>
                <th>Vòng Ngực (cm)</th>
                <th>Rộng Vai (cm)</th>
              </tr>
            </thead>
            <tbody>
              <tr><td><strong>S</strong></td><td>155 - 165</td><td>48 - 55</td><td>86 - 90</td><td>40 - 42</td></tr>
              <tr><td><strong>M</strong></td><td>164 - 172</td><td>56 - 65</td><td>90 - 94</td><td>42 - 44</td></tr>
              <tr><td><strong>L</strong></td><td>170 - 178</td><td>66 - 74</td><td>94 - 98</td><td>44 - 46</td></tr>
              <tr><td><strong>XL</strong></td><td>175 - 183</td><td>75 - 82</td><td>98 - 104</td><td>46 - 48</td></tr>
              <tr><td><strong>XXL</strong></td><td>180 - 190</td><td>83 - 92</td><td>104 - 110</td><td>48 - 50</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tab 3: Reviews -->
      <div class="tab-pane fade" id="reviews" role="tabpanel">
        <div class="row g-4">
          <!-- Left Column: Rating Overview & Review Form -->
          <div class="col-lg-4 col-md-5 border-end-md pe-lg-4">
            <div class="text-center p-3 bg-light rounded-3 mb-4">
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
                <i class="fa-solid fa-pen-nib me-2 text-danger"></i> Viết Nhận Xét Sản Phẩm
              </h6>

              @auth
                @if($userHasPurchased)
                  <form action="{{ route('client.products.review', $product->id) }}" method="POST">
                    @csrf
                    <!-- Star Rating Choice -->
                    <div class="mb-3">
                      <label class="form-label small fw-semibold text-dark mb-1">1. Đánh giá chất lượng:</label>
                      <div class="d-flex align-items-center gap-2">
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
                      <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Chất liệu vải, form dáng, độ vừa vặn khi mặc..." required>{{ old('comment', $userReview->comment ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-bee-primary btn-sm w-100 py-2">
                      <i class="fa-solid fa-paper-plane me-1"></i> {{ $userReview ? 'CẬP NHẬT ĐÁNH GIÁ' : 'GỬI ĐÁNH GIÁ NGAY' }}
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
                        <p class="mb-0 text-muted mt-1">Để đảm bảo tính khách quan và trung thực, chỉ khách hàng <strong>đã từng mua sản phẩm này</strong> mới có thể viết đánh giá.</p>
                      </div>
                    </div>
                  </div>
                @endif
              @else
                <div class="text-center py-3 bg-light rounded-3">
                  <i class="fa-solid fa-user-lock fs-3 text-muted mb-2"></i>
                  <p class="small text-muted mb-3">Vui lòng đăng nhập với tài khoản đã mua hàng để gửi nhận xét.</p>
                  <a href="{{ route('auth.login') }}" class="btn btn-bee-primary btn-sm px-4">
                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Đăng Nhập Để Đánh Giá
                  </a>
                </div>
              @endauth
            </div>
          </div>

          <!-- Right Column: List of Reviews -->
          <div class="col-lg-8 col-md-7 ps-lg-4">
            <h5 class="fw-bold text-dark mb-3" style="font-family: var(--atino-font-heading);">
              Nhận Xét Từ Khách Hàng ({{ $product->reviews->count() }})
            </h5>

            <div class="d-flex flex-column gap-3">
              @forelse($product->reviews as $rev)
                <div class="p-3 bg-light rounded-3 border">
                  <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                    <div class="d-flex align-items-center gap-2">
                      <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                        {{ mb_substr($rev->user_name ?? 'KH', 0, 1) }}
                      </div>
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
            
            // Focus vào ô textarea nếu có
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
  });
</script>
@endpush
@endsection
