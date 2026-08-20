@extends('layouts.client')

@section('title', $product->name . ' | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumbs -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('client.products.index', ['category' => $product->category->slug ?? '']) }}" class="text-decoration-none text-muted">{{ $product->category->name ?? 'Thời trang nam' }}</a></li>
      @if($product->brand)
        <li class="breadcrumb-item"><a href="{{ route('client.brands.show', $product->brand->slug) }}" class="text-decoration-none text-muted">{{ $product->brand->name }}</a></li>
      @endif
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
  <div class="card border-0 shadow-sm p-4 p-md-5 mb-5" style="border-radius: 20px; background: #ffffff;">
    <div class="row g-4 g-lg-5">
      
      <!-- IMAGE GALLERY -->
      <div class="col-lg-6">
        <div class="position-relative bg-light rounded-4 p-4 text-center mb-3" style="min-height: 420px; display: flex; align-items: center; justify-content: center;">
          @if($product->discount_percent > 0)
            <span class="position-absolute top-0 start-0 m-3 badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm" id="discountBadge">-{{ $product->discount_percent }}%</span>
          @endif
          <img id="mainProductImg" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 380px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
        </div>

        <!-- THUMBNAILS -->
        <div class="d-flex gap-2 justify-content-center flex-wrap">
          <div class="border rounded-3 p-1 bg-white cursor-pointer border-warning border-2 thumb-item" style="width: 70px; height: 70px; cursor: pointer;" onclick="changeMainImg('{{ asset($product->image) }}', this)">
            <img src="{{ asset($product->image) }}" alt="thumb" class="w-100 h-100 object-fit-contain">
          </div>
          @if($product->images)
            @foreach($product->images as $img)
              @if($img->image_path !== $product->image)
                <div class="border rounded-3 p-1 bg-white cursor-pointer thumb-item" style="width: 70px; height: 70px; cursor: pointer;" onclick="changeMainImg('{{ asset($img->image_path) }}', this)">
                  <img src="{{ asset($img->image_path) }}" alt="thumb" class="w-100 h-100 object-fit-contain">
                </div>
              @endif
            @endforeach
          @endif
        </div>
      </div>

      <!-- PRODUCT INFO & ACTIONS -->
      <div class="col-lg-6">
        <div class="ps-lg-3">
          <!-- Top Tags & Brand -->
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1">{{ $product->category->name ?? 'Thời trang nam' }}</span>
            @if($product->brand)
              <a href="{{ route('client.brands.show', $product->brand->slug) }}" class="badge bg-dark text-white text-decoration-none px-2 py-1">
                <i class="fa-solid fa-crown me-1 text-warning"></i> {{ $product->brand->name }}
              </a>
            @endif
            <span class="text-muted small ms-auto">SKU: <strong id="displaySku" class="text-dark font-monospace">{{ $product->sku }}</strong></span>
          </div>

          <h1 class="fw-bold text-dark mb-2" style="font-size: 1.5rem; line-height: 1.3;">
            {{ $product->name }}
          </h1>

          <div class="d-flex align-items-center gap-3 mb-3 flex-wrap">
            <div class="d-flex align-items-center text-warning gap-1">
              @for($i=1; $i<=5; $i++)
                <i class="fa-solid fa-star"></i>
              @endfor
              <span class="fw-bold text-dark ms-1">{{ $product->rating }}</span>
            </div>
            <span class="text-muted">|</span>
            <a href="#productTabs" class="text-muted small text-decoration-none">{{ $product->reviews_count }} đánh giá</a>
            <span class="text-muted">|</span>
            <span class="text-muted small">Đã bán <strong>{{ number_format($product->sold_count) }}</strong></span>
            <span class="text-muted">|</span>
            <span class="badge bg-success-subtle text-success" id="stockStatusBadge">
              <i class="fa-solid fa-check me-1"></i> Còn <span id="displayStockCount">{{ $product->stock }}</span> sản phẩm
            </span>
          </div>

          <!-- PRICE CONTAINER -->
          <div class="d-flex align-items-baseline gap-3 p-3 bg-light rounded-3 mb-3">
            <span class="fs-2 fw-bold text-danger" id="displayPrice">{{ number_format($product->price, 0, ',', '.') }}₫</span>
            @if($product->original_price && $product->original_price > $product->price)
              <span class="text-muted text-decoration-line-through fs-5" id="displayOriginalPrice">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
              <span class="badge bg-danger" id="displayDiscountBadge">Tiết kiệm {{ number_format($product->original_price - $product->price, 0, ',', '.') }}₫</span>
            @endif
          </div>

          <p class="text-secondary small mb-4" style="line-height: 1.6;">
            {{ $product->short_description }}
          </p>

          <form action="{{ route('client.cart.add') }}" method="POST" id="addToCartForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $product->variants->first()->id ?? '' }}">

            <!-- COLOR SELECTOR -->
            @if(is_array($product->colors) && count($product->colors) > 0)
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="fw-bold small text-dark mb-0">Màu Sắc: <span class="text-warning fw-bold" id="selectedColorText">{{ $product->colors[0] }}</span></label>
                </div>
                <div class="d-flex flex-wrap gap-2" id="colorOptionGroup">
                  @foreach($product->colors as $index => $color)
                    <input type="radio" class="btn-check variant-color-input" name="color" id="color_{{ $index }}" value="{{ $color }}" {{ $index === 0 ? 'checked' : '' }} onchange="onVariantChange()">
                    <label class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-2" for="color_{{ $index }}">
                      {{ $color }}
                    </label>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- SIZE SELECTOR -->
            @if(is_array($product->sizes) && count($product->sizes) > 0)
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="fw-bold small text-dark mb-0">Kích Thước (Size): <span class="text-warning fw-bold" id="selectedSizeText">{{ $product->sizes[0] }}</span></label>
                  <a href="#sizeGuideModal" data-bs-toggle="modal" class="text-warning small text-decoration-none fw-semibold"><i class="fa-solid fa-ruler-horizontal me-1"></i> Bảng quy đổi Size</a>
                </div>
                <div class="d-flex flex-wrap gap-2" id="sizeOptionGroup">
                  @foreach($product->sizes as $index => $size)
                    <input type="radio" class="btn-check variant-size-input" name="size" id="size_{{ $index }}" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }} onchange="onVariantChange()">
                    <label class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-2" for="size_{{ $index }}">
                      {{ $size }}
                    </label>
                  @endforeach
                </div>
              </div>
            @endif

            <!-- QUANTITY & ACTIONS -->
            <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
              <div class="d-flex align-items-center border rounded-2 bg-white" style="width: 130px;">
                <button type="button" class="btn btn-link text-dark p-2 text-decoration-none" onclick="stepQty(-1)"><i class="fa-solid fa-minus"></i></button>
                <input type="number" name="quantity" id="productQty" class="form-control border-0 text-center fw-bold p-0" value="1" min="1" max="99">
                <button type="button" class="btn btn-link text-dark p-2 text-decoration-none" onclick="stepQty(1)"><i class="fa-solid fa-plus"></i></button>
              </div>

              <button type="submit" class="btn btn-outline-warning text-dark flex-grow-1 py-2.5 fw-bold rounded-2" id="btnAddToCart">
                <i class="fa-solid fa-cart-plus me-2"></i> Thêm Vào Giỏ Hàng
              </button>

              <button type="submit" name="buy_now" value="1" class="btn btn-bee-primary px-4 py-2.5 fw-bold rounded-2" id="btnBuyNow">
                <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
              </button>
            </div>
          </form>

          <!-- PROMISES BADGES -->
          <div class="row g-2 border-top pt-3 text-secondary small">
            <div class="col-6 d-flex align-items-center gap-2">
              <i class="fa-solid fa-shield-check text-warning fs-5"></i>
              <span>100% Chính hãng BeeStyle</span>
            </div>
            <div class="col-6 d-flex align-items-center gap-2">
              <i class="fa-solid fa-truck-fast text-warning fs-5"></i>
              <span>Freeship đơn từ 300.000₫</span>
            </div>
            <div class="col-6 d-flex align-items-center gap-2">
              <i class="fa-solid fa-arrows-rotate text-warning fs-5"></i>
              <span>Đổi size miễn phí trong 15 ngày</span>
            </div>
            <div class="col-6 d-flex align-items-center gap-2">
              <i class="fa-solid fa-box-open text-warning fs-5"></i>
              <span>Được kiểm tra hàng trước khi nhận</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- TABS: SPECIFICATIONS & DESCRIPTION & REVIEWS -->
  <div class="card border-0 shadow-sm p-4 mb-5" style="border-radius: 16px;" id="productTabs">
    <ul class="nav nav-tabs border-bottom mb-4" id="pTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold text-dark" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button" role="tab">
          <i class="fa-solid fa-list-check me-2 text-warning"></i> Thông Số Kỹ Thuật
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-dark" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">
          <i class="fa-solid fa-file-lines me-2 text-warning"></i> Mô Tả Chi Tiết
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-dark" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button" role="tab">
          <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá ({{ $product->reviews_count }})
        </button>
      </li>
    </ul>

    <div class="tab-content" id="pTabContent">
      <!-- TAB 1: SPECIFICATIONS -->
      <div class="tab-pane fade show active" id="specs-pane" role="tabpanel">
        <div class="row">
          <div class="col-lg-8">
            <h6 class="fw-bold text-dark mb-3">Chi Tiết Thuộc Tính &amp; Thông Số Sản Phẩm</h6>
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle small mb-0">
                <tbody>
                  @if($product->brand)
                    <tr>
                      <th class="bg-light text-dark fw-bold" style="width: 200px;">Thương hiệu</th>
                      <td><strong>{{ $product->brand->name }}</strong></td>
                    </tr>
                  @endif
                  <tr>
                    <th class="bg-light text-dark fw-bold">Danh mục</th>
                    <td>{{ $product->category->name ?? 'Thời trang nam' }}</td>
                  </tr>
                  <tr>
                    <th class="bg-light text-dark fw-bold">Mã sản phẩm (SKU)</th>
                    <td class="font-monospace fw-semibold">{{ $product->sku }}</td>
                  </tr>
                  <tr>
                    <th class="bg-light text-dark fw-bold">Loại sản phẩm</th>
                    <td>{{ $product->product_type === 'variant' ? 'Sản phẩm đa biến thể (Kích thước & Màu sắc)' : 'Sản phẩm đơn' }}</td>
                  </tr>
                  @if($product->specifications && is_array($product->specifications))
                    @foreach($product->specifications as $key => $val)
                      <tr>
                        <th class="bg-light text-dark fw-bold">{{ $key }}</th>
                        <td>{{ $val }}</td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <th class="bg-light text-dark fw-bold">Chất liệu</th>
                      <td>Cotton cao cấp co giãn 4 chiều, mềm mại và thoáng khí</td>
                    </tr>
                    <tr>
                      <th class="bg-light text-dark fw-bold">Phom dáng</th>
                      <td>Chuẩn phom Regular / Slimfit tôn dáng phái mạnh</td>
                    </tr>
                    <tr>
                      <th class="bg-light text-dark fw-bold">Xuất xứ</th>
                      <td>Việt Nam (Tiêu chuẩn xuất khẩu cao cấp)</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 p-3 bg-light rounded-3 text-center">
              <i class="fa-solid fa-medal text-warning fs-1 mb-2"></i>
              <h6 class="fw-bold text-dark mb-1">Cam Kết Chất Lượng BeeStyle</h6>
              <p class="small text-muted mb-0">Tất cả sản phẩm đều được kiểm định chất lượng đường may, chỉ tiêu kháng khuẩn và độ bền màu trước khi xuất xưởng.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 2: DESCRIPTION -->
      <div class="tab-pane fade" id="desc-pane" role="tabpanel">
        <div class="product-description" style="line-height: 1.8;">
          {!! $product->description ?? '<p>' . $product->short_description . '</p>' !!}
        </div>
      </div>

      <!-- TAB 3: REVIEWS -->
      <div class="tab-pane fade" id="reviews-pane" role="tabpanel">
        <div class="d-flex flex-column gap-3">
          @forelse($product->reviews as $rev)
            <div class="border-bottom pb-3">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar avatar-sm bg-warning rounded-circle text-dark fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    {{ substr($rev->user_name, 0, 1) }}
                  </div>
                  <div>
                    <strong class="text-dark small">{{ $rev->user_name }}</strong>
                    <div class="text-warning small">
                      @for($i=1; $i<=$rev->rating; $i++)
                        <i class="fa-solid fa-star"></i>
                      @endfor
                    </div>
                  </div>
                </div>
                <small class="text-muted">{{ $rev->created_at ? $rev->created_at->format('d/m/Y') : '' }}</small>
              </div>
              <p class="small text-secondary mb-0">{{ $rev->comment }}</p>
            </div>
          @empty
            <div class="text-center py-4">
              <p class="text-muted small">Chưa có đánh giá nào cho sản phẩm này.</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- RELATED PRODUCTS -->
  @if($relatedProducts->count() > 0)
    <div class="mb-4">
      <h4 class="fw-bold text-dark mb-3">
        <i class="fa-solid fa-sparkles me-2 text-warning"></i> Sản Phẩm Tương Tự
      </h4>
      <div class="row g-3">
        @foreach($relatedProducts as $rel)
          <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 14px; overflow: hidden;">
              <div class="bg-light p-3 text-center" style="height: 180px; display: flex; align-items: center; justify-content: center;">
                <a href="{{ route('client.products.show', $rel->id) }}">
                  <img src="{{ asset($rel->image) }}" alt="{{ $rel->name }}" class="img-fluid" style="max-height: 160px; object-fit: contain;">
                </a>
              </div>
              <div class="card-body p-3">
                <h6 class="fw-bold text-dark text-truncate mb-1" style="font-size: 0.9rem;">
                  <a href="{{ route('client.products.show', $rel->id) }}" class="text-decoration-none text-dark hover-warning">
                    {{ $rel->name }}
                  </a>
                </h6>
                <strong class="text-danger small">{{ number_format($rel->price, 0, ',', '.') }}₫</strong>
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

<!-- JAVASCRIPT FOR DYNAMIC VARIANT SWITCHER -->
@push('scripts')
<script>
  // Product Variants JSON Data
  const variantsData = @json($product->variants);
  const defaultProduct = {
    id: {{ $product->id }},
    sku: "{{ $product->sku }}",
    price: {{ $product->price }},
    original_price: {{ $product->original_price ?? 0 }},
    stock: {{ $product->stock }},
    image: "{{ asset($product->image) }}"
  };

  function changeMainImg(src, el) {
    document.getElementById('mainProductImg').src = src;
    document.querySelectorAll('.thumb-item').forEach(item => item.classList.remove('border-warning', 'border-2'));
    if (el) el.classList.add('border-warning', 'border-2');
  }

  function stepQty(amount) {
    const qtyInput = document.getElementById('productQty');
    let val = parseInt(qtyInput.value) || 1;
    val += amount;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    qtyInput.value = val;
  }

  function formatMoney(num) {
    return new Intl.NumberFormat('vi-VN').format(num) + '₫';
  }

  function onVariantChange() {
    const selectedColorEl = document.querySelector('input[name="color"]:checked');
    const selectedSizeEl = document.querySelector('input[name="size"]:checked');

    const selectedColor = selectedColorEl ? selectedColorEl.value : null;
    const selectedSize = selectedSizeEl ? selectedSizeEl.value : null;

    if (selectedColor) {
      document.getElementById('selectedColorText').innerText = selectedColor;
    }
    if (selectedSize) {
      document.getElementById('selectedSizeText').innerText = selectedSize;
    }

    // Find matched variant
    let matchedVariant = null;
    if (variantsData && variantsData.length > 0) {
      matchedVariant = variantsData.find(v => {
        const colorMatch = selectedColor ? v.color === selectedColor : true;
        const sizeMatch = selectedSize ? v.size === selectedSize : true;
        return colorMatch && sizeMatch;
      });
    }

    const price = matchedVariant ? matchedVariant.price : defaultProduct.price;
    const originalPrice = matchedVariant ? (matchedVariant.original_price || defaultProduct.original_price) : defaultProduct.original_price;
    const stock = matchedVariant ? matchedVariant.stock : defaultProduct.stock;
    const sku = matchedVariant ? matchedVariant.sku : defaultProduct.sku;

    // Update DOM
    document.getElementById('displayPrice').innerText = formatMoney(price);
    document.getElementById('displaySku').innerText = sku;
    document.getElementById('displayStockCount').innerText = stock;

    if (matchedVariant) {
      document.getElementById('selectedVariantId').value = matchedVariant.id;
    }

    // Original price update
    const origPriceEl = document.getElementById('displayOriginalPrice');
    const discountBadge = document.getElementById('displayDiscountBadge');
    if (originalPrice && originalPrice > price) {
      if (origPriceEl) origPriceEl.innerText = formatMoney(originalPrice);
      if (discountBadge) discountBadge.innerText = 'Tiết kiệm ' + formatMoney(originalPrice - price);
    }

    // Stock status
    const stockBadge = document.getElementById('stockStatusBadge');
    const btnAdd = document.getElementById('btnAddToCart');
    const btnBuy = document.getElementById('btnBuyNow');

    if (stock > 0) {
      stockBadge.className = 'badge bg-success-subtle text-success';
      stockBadge.innerHTML = '<i class="fa-solid fa-check me-1"></i> Còn ' + stock + ' sản phẩm';
      btnAdd.disabled = false;
      btnBuy.disabled = false;
      btnAdd.innerHTML = '<i class="fa-solid fa-cart-plus me-2"></i> Thêm Vào Giỏ Hàng';
    } else {
      stockBadge.className = 'badge bg-danger-subtle text-danger';
      stockBadge.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> Tạm hết hàng';
      btnAdd.disabled = true;
      btnBuy.disabled = true;
      btnAdd.innerHTML = '<i class="fa-solid fa-ban me-2"></i> Hết Hàng';
    }
  }

  // Trigger initial variant setup
  document.addEventListener('DOMContentLoaded', function() {
    onVariantChange();
  });
</script>
@endpush
@endsection