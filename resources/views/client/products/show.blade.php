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

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
      <a href="{{ route('client.cart') }}" class="fw-bold text-success text-decoration-underline ms-2">Xem giỏ hàng ngay</a>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- PRODUCT MAIN SECTION -->
  <div class="card border-0 shadow-sm p-4 p-md-5 mb-5" style="border-radius: 20px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
    <div class="row g-4 g-lg-5">
      
      <!-- IMAGE GALLERY -->
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
          <form action="{{ route('client.cart.add') }}" method="POST" id="productForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <!-- COLOR SELECTION -->
            @php
              $prodColors = is_array($product->colors) ? $product->colors : ['Đen', 'Trắng', 'Xanh Navy'];
            @endphp
            @if(count($prodColors) > 0)
              <div class="mb-3">
                <label class="form-label small fw-semibold text-dark mb-2">
                  Màu Sắc: <strong class="text-dark" id="selectedColorText">{{ $prodColors[0] }}</strong>
                </label>
                <div class="d-flex flex-wrap gap-2">
                  @foreach($prodColors as $c)
                    <input type="radio" class="btn-check" name="color" id="color_{{ $loop->index }}" value="{{ $c }}" {{ $loop->first ? 'checked' : '' }} onchange="document.getElementById('selectedColorText').innerText = this.value">
                    <label class="btn btn-outline-dark btn-sm px-3 py-1 rounded-pill" for="color_{{ $loop->index }}">
                      {{ $c }}
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
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="form-label small fw-semibold text-dark mb-0">
                    Kích Thước: <strong class="text-dark" id="selectedSizeText">{{ $prodSizes[0] }}</strong>
                  </label>
                  <button type="button" class="btn btn-link text-decoration-none p-0 small text-danger fw-bold" data-bs-toggle="modal" data-bs-target="#sizeGuideModal">
                    <i class="fa-solid fa-ruler-horizontal me-1"></i> Bảng quy đổi Size
                  </button>
                </div>
                <div class="d-flex flex-wrap gap-2">
                  @foreach($prodSizes as $s)
                    <input type="radio" class="btn-check" name="size" id="size_{{ $loop->index }}" value="{{ $s }}" {{ $loop->first ? 'checked' : '' }} onchange="document.getElementById('selectedSizeText').innerText = this.value">
                    <label class="btn btn-outline-dark btn-sm px-3 py-1 rounded-2 fw-semibold" for="size_{{ $loop->index }}">
                      {{ $s }}
                    </label>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- QUANTITY & ACTION BUTTONS -->
            <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
              <div class="input-group" style="width: 130px;">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="stepQty(-1)">-</button>
                <input type="number" name="quantity" id="productQty" class="form-control form-control-sm text-center fw-bold" value="1" min="1" max="{{ $product->stock }}">
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="stepQty(1)">+</button>
              </div>

              <div class="small text-muted">
                Kho: <strong class="text-dark" id="displayStockCount">{{ $product->stock }}</strong> sản phẩm có sẵn
              </div>
            </div>

            <!-- BUTTONS -->
            <div class="row g-2 mb-4">
              <div class="col-6">
                <button type="submit" class="btn btn-bee-outline w-100 py-2.5 fs-6" id="btnAddToCart">
                  <i class="fa-solid fa-cart-plus me-2"></i> Thêm Vào Giỏ
                </button>
              </div>
              <div class="col-6">
                <button type="submit" name="buy_now" value="1" class="btn btn-bee-primary w-100 py-2.5 fs-6" id="btnBuyNow">
                  <i class="fa-solid fa-bolt me-2"></i> Mua Ngay
                </button>
              </div>
            </div>
          </form>

          <!-- PROMISES / TRUST BADGES -->
          <div class="border-top pt-3">
            <div class="row g-2 text-muted small">
              <div class="col-6 d-flex align-items-center gap-2">
                <i class="fa-solid fa-truck-fast text-danger fs-6"></i>
                <span>Freeship toàn quốc từ 300k</span>
              </div>
              <div class="col-6 d-flex align-items-center gap-2">
                <i class="fa-solid fa-rotate-left text-danger fs-6"></i>
                <span>Đổi size miễn phí 30 ngày</span>
              </div>
              <div class="col-6 d-flex align-items-center gap-2">
                <i class="fa-solid fa-shield-check text-danger fs-6"></i>
                <span>Cam kết chính hãng 100%</span>
              </div>
              <div class="col-6 d-flex align-items-center gap-2">
                <i class="fa-solid fa-box-open text-danger fs-6"></i>
                <span>Kiểm tra hàng trước khi nhận</span>
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
  function changeMainImg(src, el) {
    document.getElementById('mainProductImg').src = src;
    document.querySelectorAll('.thumb-item').forEach(item => {
      item.classList.remove('border-danger', 'border-2');
    });
    if (el) {
      el.classList.add('border-danger', 'border-2');
    }
  }

  function stepQty(amount) {
    const qtyInput = document.getElementById('productQty');
    let val = parseInt(qtyInput.value) || 1;
    val += amount;
    if (val < 1) val = 1;
    if (val > {{ $product->stock }}) val = {{ $product->stock }};
    qtyInput.value = val;
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
