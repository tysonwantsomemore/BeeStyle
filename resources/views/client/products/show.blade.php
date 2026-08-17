@extends('layouts.client')

@section('title', $product['name'] . ' | BeeStyle Fashion')

@section('content')
<div class="container py-4">
  <!-- Breadcrumbs -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('client.products.index', ['category' => $product['category_slug']]) }}" class="text-decoration-none text-muted">{{ $product['category'] }}</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $product['name'] }}</li>
    </ol>
  </nav>

  <!-- PRODUCT MAIN SECTION -->
  <div class="card border-0 shadow-sm p-4 mb-5" style="border-radius: 16px;">
    <div class="row g-4">
      
      <!-- IMAGE GALLERY -->
      <div class="col-lg-6">
        <div class="position-relative bg-light rounded-4 p-4 text-center mb-3" style="min-height: 400px; display: flex; align-items: center; justify-content: center;">
          @if($product['discount'] > 0)
            <span class="position-absolute top-0 start-0 m-3 badge bg-danger fs-6 px-3 py-2 rounded-pill">-{{ $product['discount'] }}%</span>
          @endif
          <img id="mainProductImg" src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="img-fluid" style="max-height: 380px; object-fit: contain; transition: 0.3s ease;">
        </div>

        <!-- THUMBNAILS -->
        <div class="d-flex gap-2 justify-content-center">
          @foreach($product['images'] as $index => $img)
            <div class="border rounded-3 p-1 bg-white cursor-pointer {{ $index === 0 ? 'border-warning border-2' : '' }}" style="width: 70px; height: 70px; cursor: pointer;" onclick="document.getElementById('mainProductImg').src='{{ asset($img) }}'">
              <img src="{{ asset($img) }}" alt="thumb" class="w-100 h-100 object-fit-contain">
            </div>
          @endforeach
        </div>
      </div>

      <!-- PRODUCT INFO & ACTIONS -->
      <div class="col-lg-6">
        <div class="ps-lg-3">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1">{{ $product['category'] }}</span>
            <span class="text-muted small">Mã SKU: <strong>{{ $product['sku'] }}</strong></span>
            <span class="badge bg-success-subtle text-success ms-auto"><i class="fa-solid fa-check me-1"></i> Còn {{ $product['stock'] }} sản phẩm</span>
          </div>

          <h2 class="fw-bold text-dark mb-3" style="font-size: 1.5rem; line-height: 1.3;">
            {{ $product['name'] }}
          </h2>

          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="d-flex align-items-center text-warning gap-1">
              @for($i=1; $i<=5; $i++)
                <i class="fa-solid fa-star"></i>
              @endfor
              <span class="fw-bold text-dark ms-1">{{ $product['rating'] }}</span>
            </div>
            <span class="text-muted">|</span>
            <a href="#reviewsTab" class="text-muted small text-decoration-none">{{ $product['reviews_count'] }} đánh giá</a>
            <span class="text-muted">|</span>
            <span class="text-muted small">Đã bán <strong>{{ $product['sold_count'] }}</strong></span>
          </div>

          <!-- PRICE -->
          <div class="d-flex align-items-baseline gap-3 p-3 bg-light rounded-3 mb-4">
            <span class="fs-3 fw-bold text-danger">{{ number_format($product['price'], 0, ',', '.') }}₫</span>
            @if($product['original_price'] > $product['price'])
              <span class="text-muted text-decoration-line-through fs-6">{{ number_format($product['original_price'], 0, ',', '.') }}₫</span>
              <span class="badge bg-danger">Tiết kiệm {{ number_format($product['original_price'] - $product['price'], 0, ',', '.') }}₫</span>
            @endif
          </div>

          <p class="text-secondary small mb-4">
            {{ $product['short_description'] }}
          </p>

          <form action="{{ route('client.cart') }}" method="GET">
            <!-- COLOR SELECTOR -->
            <div class="mb-3">
              <label class="fw-bold small text-dark mb-2 d-block">Màu Sắc:</label>
              <div class="d-flex flex-wrap gap-2">
                @foreach($product['colors'] as $index => $color)
                  <input type="radio" class="btn-check" name="color" id="color_{{ $index }}" value="{{ $color }}" {{ $index === 0 ? 'checked' : '' }}>
                  <label class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-2" for="color_{{ $index }}">{{ $color }}</label>
                @endforeach
              </div>
            </div>

            <!-- SIZE SELECTOR -->
            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="fw-bold small text-dark mb-0">Kích Thước:</label>
                <a href="#sizeGuideModal" data-bs-toggle="modal" class="text-warning small text-decoration-none"><i class="fa-solid fa-ruler-horizontal me-1"></i> Bảng quy đổi Size</a>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @foreach($product['sizes'] as $index => $size)
                  <input type="radio" class="btn-check" name="size" id="size_{{ $index }}" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }}>
                  <label class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-2 fw-bold" for="size_{{ $index }}">{{ $size }}</label>
                @endforeach
              </div>
            </div>

            <!-- QUANTITY -->
            <div class="mb-4">
              <label class="fw-bold small text-dark mb-2 d-block">Số Lượng:</label>
              <div class="d-flex align-items-center gap-3">
                <div class="input-group" style="width: 130px;">
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var q=document.getElementById('qtyInput'); if(q.value>1) q.value--;">-</button>
                  <input type="number" id="qtyInput" class="form-control form-control-sm text-center fw-bold" value="1" min="1" max="99">
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var q=document.getElementById('qtyInput'); q.value++;">+</button>
                </div>
                <span class="text-muted small">Có sẵn {{ $product['stock'] }} sản phẩm</span>
              </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex gap-3 mb-4">
              <a href="{{ route('client.cart') }}" class="btn btn-bee-primary px-4 py-3 flex-grow-1 fs-6">
                <i class="fa-solid fa-cart-plus me-1"></i> Thêm Vào Giỏ Hàng
              </a>
              <a href="{{ route('client.checkout') }}" class="btn btn-bee-dark px-4 py-3 flex-grow-1 fs-6">
                Mua Ngay
              </a>
            </div>
          </form>

          <!-- VALUE PROPOSITIONS -->
          <div class="border rounded-3 p-3 bg-light-subtle">
            <div class="row g-2 small">
              <div class="col-6"><i class="fa-solid fa-shield-check text-warning me-2"></i> Cam kết 100% chính hãng</div>
              <div class="col-6"><i class="fa-solid fa-truck text-warning me-2"></i> Giao hoả tốc 24h - 48h</div>
              <div class="col-6"><i class="fa-solid fa-box-open text-warning me-2"></i> Kiểm tra hàng trước khi nhận</div>
              <div class="col-6"><i class="fa-solid fa-arrows-rotate text-warning me-2"></i> Đổi size miễn phí 30 ngày</div>
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
          <i class="fa-solid fa-ruler-combined me-2 text-warning"></i> Hướng Dẫn Chọn Size
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-dark px-4 py-3" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
          <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Từ Khách Hàng ({{ $product['reviews_count'] }})
        </button>
      </li>
    </ul>

    <div class="tab-content p-4" id="productTabsContent">
      <!-- Tab 1: Description -->
      <div class="tab-pane fade show active" id="desc" role="tabpanel">
        <h5 class="fw-bold mb-3 text-dark">Thông tin chi tiết về sản phẩm</h5>
        <p class="leading-relaxed text-secondary mb-4">{{ $product['description'] }}</p>
        
        <h6 class="fw-bold mb-2">Đặc điểm nổi bật:</h6>
        <ul class="text-secondary leading-relaxed small">
          <li>Chất liệu sợi tự nhiên cao cấp được xử lý chống co rút, kháng nhăn và thoáng khí tối đa.</li>
          <li>Kỹ thuật dệt vi sợi hiện đại giúp sản phẩm luôn giữ được form dáng chuẩn sau nhiều lần giặt.</li>
          <li>Đường may kép tỉ mỉ, bo cổ và tay áo tinh tế, không bai dão theo thời gian.</li>
          <li>Dễ dàng phối đồ linh hoạt: đi làm công sở, dự sự kiện, dạo phố hay gặp gỡ bạn bè.</li>
        </ul>
      </div>

      <!-- Tab 2: Size Guide -->
      <div class="tab-pane fade" id="guide" role="tabpanel">
        <h5 class="fw-bold mb-3 text-dark">Bảng thông số kích cỡ chuẩn BeeStyle</h5>
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
          <div class="col-md-4 text-center border-end">
            <h1 class="display-3 fw-bold text-warning mb-0">{{ $product['rating'] }}</h1>
            <div class="text-warning mb-2">
              <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="text-muted small">Dựa trên {{ $product['reviews_count'] }} lượt đánh giá của khách hàng</p>
          </div>
          <div class="col-md-8">
            <div class="d-flex flex-column gap-3">
              <div class="p-3 bg-light rounded-3">
                <div class="d-flex justify-content-between mb-2">
                  <div>
                    <strong class="text-dark">Lê Minh Tuấn</strong>
                    <span class="badge bg-success-subtle text-success ms-2 small"><i class="fa-solid fa-circle-check"></i> Đã mua hàng</span>
                  </div>
                  <small class="text-muted">12/08/2026</small>
                </div>
                <div class="text-warning small mb-2"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                <p class="small text-secondary mb-0">Áo mặc cực kỳ ưng ý, vải mềm mịn mặc mát rượi. Form áo đứng dáng rất tôn dáng, đóng gói hộp BeeStyle sang trọng!</p>
              </div>

              <div class="p-3 bg-light rounded-3">
                <div class="d-flex justify-content-between mb-2">
                  <div>
                    <strong class="text-dark">Nguyễn Thu Trang</strong>
                    <span class="badge bg-success-subtle text-success ms-2 small"><i class="fa-solid fa-circle-check"></i> Đã mua hàng</span>
                  </div>
                  <small class="text-muted">08/08/2026</small>
                </div>
                <div class="text-warning small mb-2"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                <p class="small text-secondary mb-0">Giao hàng nhanh trong 24h, chất liệu đường may chuẩn đét. Sẽ ủng hộ shop nhiều hơn trong tương lai!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- RELATED PRODUCTS -->
  <div class="mb-5">
    <div class="bee-section-header">
      <div>
        <h3 class="bee-section-title">Gợi Ý Sản Phẩm Tương Tự</h3>
        <p class="bee-section-subtitle">Có thể bạn cũng sẽ thích những mẫu thời trang này</p>
      </div>
    </div>

    <div class="row g-4">
      @foreach(array_slice($relatedProducts, 0, 4) as $item)
        <div class="col-lg-3 col-md-6 col-6">
          <div class="bee-product-card">
            <div class="bee-product-img-wrapper">
              <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
            </div>
            <div class="bee-product-body">
              <span class="bee-product-category">{{ $item['category'] }}</span>
              <a href="{{ route('client.products.show', $item['id']) }}" class="bee-product-title">{{ $item['name'] }}</a>
              <div class="bee-product-price-row">
                <span class="bee-product-price">{{ number_format($item['price'], 0, ',', '.') }}₫</span>
              </div>
              <a href="{{ route('client.products.show', $item['id']) }}" class="btn btn-bee-outline btn-sm w-100 mt-2">Xem Ngay</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
