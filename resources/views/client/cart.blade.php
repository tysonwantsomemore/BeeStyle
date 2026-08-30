@extends('layouts.client')

@section('title', 'Giỏ Hàng Của Bạn | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Giỏ hàng</li>
    </ol>
  </nav>

  <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h3 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
      <i class="fa-solid fa-bag-shopping text-warning"></i>
      <span>Giỏ Hàng (<span id="cartCountTitle">{{ $cartCount }}</span> sản phẩm)</span>
    </h3>
    <a href="{{ route('client.products.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
      <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm
    </a>
  </div>

  <div id="cartMainContainer" class="{{ count($cartItems) > 0 ? '' : 'd-none' }}">
    <!-- 1. FREE SHIPPING PROGRESS BAR (CHUẨN TMĐT CAO CẤP) -->
    <div class="card border-0 shadow-sm p-3.5 mb-4" style="border-radius: 16px; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1px solid #fde68a !important;">
      <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2" id="freeShippingStatusBox">
          <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center shadow-xs flex-shrink-0" style="width: 32px; height: 32px;">
            <i class="fa-solid {{ $isFreeShipping ? 'fa-gift' : 'fa-truck-fast' }} fs-6"></i>
          </div>
          <div id="freeShippingStatusText">
            @if($isFreeShipping)
              <span class="fw-bold text-success fs-9">🎉 Chúc mừng! Đơn hàng của bạn đã đủ điều kiện nhận <strong>FREESHIP TOÀN QUỐC</strong>!</span>
            @else
              <span class="fw-bold text-dark fs-9">🚚 Mua thêm <strong class="text-danger fs-6" id="fsNeededAmount">{{ number_format($freeShippingNeeded, 0, ',', '.') }}₫</strong> để được <strong class="text-success">MIỄN PHÍ VẬN CHUYỂN</strong>!</span>
            @endif
          </div>
        </div>
        <span class="badge bg-dark text-warning border border-warning px-2.5 py-1 rounded-pill fw-bold fs-11" id="fsThresholdBadge">
          Mốc Freeship: 300.000₫
        </span>
      </div>
      <div class="progress" style="height: 10px; border-radius: 20px; background-color: rgba(255,255,255,0.8);">
        <div id="freeShippingProgressBar" class="progress-bar progress-bar-striped progress-bar-animated {{ $isFreeShipping ? 'bg-success' : 'bg-warning' }}" 
             role="progressbar" 
             style="width: {{ $freeShippingPercent }}%; transition: width 0.4s ease, background-color 0.4s ease;">
        </div>
      </div>
    </div>

    <div class="row g-4">
      <!-- CART ITEMS LIST -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr class="small text-uppercase text-muted" style="font-size: 0.76rem;">
                  <th>Sản phẩm</th>
                  <th class="text-center">Đơn giá</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-end">Thành tiền</th>
                  <th class="text-center" style="width: 70px;">Thao tác</th>
                </tr>
              </thead>
              <tbody id="cartTableBody">
                @foreach($cartItems as $item)
                  <tr id="cartRow_{{ $item['key'] }}" class="cart-item-row {{ $item['is_out_of_stock'] ? 'table-danger opacity-75' : '' }}">
                    <!-- Product details -->
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-3 p-1 border position-relative flex-shrink-0" style="width: 72px; height: 72px;">
                          <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-100 h-100 object-fit-contain rounded">
                          @if($item['is_daily_deal'])
                            <span class="position-absolute top-0 start-0 badge bg-danger text-white rounded-pill p-1" style="font-size: 0.6rem;" title="Ưu đãi Flash Sale">
                              <i class="fa-solid fa-bolt"></i>
                            </span>
                          @endif
                        </div>
                        <div class="min-w-0">
                          <a href="{{ route('client.products.show', $item['product_id']) }}" class="fw-bold text-dark mb-1 fs-9 text-decoration-none d-block text-truncate hover-primary" style="max-width: 260px;" title="{{ $item['name'] }}">
                            {{ $item['name'] }}
                          </a>
                          <div class="text-muted small d-flex align-items-center gap-2 flex-wrap" style="font-size: 0.75rem;">
                            <span>Màu: <strong class="text-dark">{{ $item['color'] }}</strong></span>
                            <span>•</span>
                            <span>Size: <strong class="text-dark">{{ $item['size'] }}</strong></span>
                          </div>
                          @if($item['is_out_of_stock'])
                            <span class="badge bg-danger text-white mt-1 fs-11 fw-bold">
                              <i class="fa-solid fa-circle-exclamation me-1"></i> Tạm hết hàng trong kho
                            </span>
                          @elseif($item['has_stock_warning'])
                            <span class="badge bg-warning text-dark mt-1 fs-11 fw-bold">
                              <i class="fa-solid fa-triangle-exclamation me-1"></i> Kho chỉ còn {{ $item['current_stock'] }} cái
                            </span>
                          @endif
                        </div>
                      </div>
                    </td>

                    <!-- Unit Price -->
                    <td class="text-center text-nowrap">
                      <span class="fw-semibold text-dark">{{ number_format($item['price'], 0, ',', '.') }}₫</span>
                      @if($item['original_price'] && $item['original_price'] > $item['price'])
                        <small class="text-muted text-decoration-line-through d-block fs-11">{{ number_format($item['original_price'], 0, ',', '.') }}₫</small>
                      @endif
                    </td>

                    <!-- Quantity -->
                    <td>
                      <div class="d-flex align-items-center">
                        <form action="{{ route('client.cart.update') }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="key" value="{{ $item['key'] }}">
                          <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                          <button class="btn btn-outline-secondary btn-sm" type="submit" style="width: 28px; height: 28px; padding: 0;" title="Giảm số lượng">-</button>
                        </form>

                        <form action="{{ route('client.cart.update') }}" method="POST" class="d-inline mx-1">
                          @csrf
                          <input type="hidden" name="key" value="{{ $item['key'] }}">
                          <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="999" class="form-control form-control-sm text-center fw-bold text-dark px-1 border-secondary-subtle" style="width: 52px; height: 28px;" title="Nhập số lượng bạn muốn mua" onchange="this.form.submit()">
                        </form>

                        <form action="{{ route('client.cart.update') }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="key" value="{{ $item['key'] }}">
                          <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                          <button class="btn btn-outline-secondary btn-sm" type="submit" style="width: 28px; height: 28px; padding: 0;" title="Tăng số lượng">+</button>
                        </form>
                      </div>
                    </td>


                    <!-- Total -->
                    <td>
                      <span class="fw-bold text-danger">{{ number_format($item['subtotal'], 0, ',', '.') }}₫</span>
                    </td>

                    <!-- Actions: Wishlist & Remove -->
                    <td class="text-center text-nowrap">
                      <div class="d-flex align-items-center justify-content-center gap-1.5">
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle border shadow-xs d-flex align-items-center justify-content-center hover-scale" 
                                style="width: 30px; height: 30px;" 
                                onclick="saveItemForLater('{{ $item['key'] }}', this)" 
                                title="Lưu mua sau (Chuyển vào Danh sách Yêu Thích)">
                          <i class="fa-regular fa-heart text-danger" style="font-size: 0.75rem;"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-circle border shadow-xs d-flex align-items-center justify-content-center hover-scale" 
                                style="width: 30px; height: 30px;" 
                                onclick="removeCartItem('{{ $item['key'] }}', this)" 
                                title="Xóa khỏi giỏ hàng">
                          <i class="fa-regular fa-trash-can" style="font-size: 0.75rem;"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 pt-3 border-top">
            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5" onclick="clearFullCart()">
              <i class="fa-solid fa-trash me-1"></i> Xóa sạch giỏ hàng
            </button>
            <div class="small text-muted">
              <i class="fa-solid fa-shield-check text-success me-1"></i> Đổi size miễn phí trong 30 ngày tận nhà
            </div>
          </div>
        </div>

        <!-- 2. GỢI Ý MUA KÈM GIÁ TỐT ĐỂ ĐẠT FREESHIP (CROSS-SELL WIDGET) -->
        @if(isset($crossSellProducts) && $crossSellProducts->count() > 0)
          <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background: #ffffff;">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div>
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                  <i class="fa-solid fa-sparkles text-warning"></i>
                  <span>Gợi Ý Mua Kèm Để Đạt Mốc Freeship 300.000₫</span>
                </h6>
                <small class="text-muted">Các mẫu thời trang &amp; phụ kiện nam bán chạy giá cực tốt</small>
              </div>
              <a href="{{ route('client.products.index') }}" class="btn btn-link text-decoration-none small text-danger fw-bold p-0">Xem thêm</a>
            </div>

            <div class="row g-3">
              @foreach($crossSellProducts as $cp)
                <div class="col-md-6 col-12">
                  <div class="p-2.5 rounded-3 border bg-light d-flex align-items-center justify-content-between gap-2.5 transition-all hover-lift">
                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                      <img src="{{ asset($cp->image) }}" alt="{{ $cp->name }}" class="rounded-2 border bg-white flex-shrink-0" style="width: 52px; height: 52px; object-fit: cover;">
                      <div class="min-w-0">
                        <a href="{{ route('client.products.show', $cp->id) }}" class="text-dark fw-bold text-decoration-none d-block text-truncate small" title="{{ $cp->name }}">{{ $cp->name }}</a>
                        <span class="text-danger fw-bold small">{{ number_format($cp->price, 0, ',', '.') }}₫</span>
                      </div>
                    </div>
                    <button type="button" class="btn btn-bee-primary btn-xs py-1.5 px-2.5 rounded-pill fw-bold text-nowrap flex-shrink-0" onclick="quickAddCrossSell({{ $cp->id }}, this)">
                      <i class="fa-solid fa-cart-plus me-1"></i> + Thêm Nhanh
                    </button>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <!-- ORDER SUMMARY SIDEBAR -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; position: sticky; top: 100px;">
          <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-receipt text-warning"></i>
            <span>Tóm Tắt Đơn Hàng</span>
          </h5>

          <!-- Voucher Section -->
          <div class="mb-3.5 p-3 rounded-3 border" style="background: #fffbeb; border-color: #fde68a !important;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="small fw-bold text-dark mb-0 d-flex align-items-center gap-1.5">
                <i class="fa-solid fa-ticket text-warning"></i> Mã Giảm Giá
              </label>
              @if(isset($coupons) && $coupons->count() > 0)
                <button type="button" class="btn btn-link text-danger p-0 small fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#voucherPickerModal">
                  <i class="fa-solid fa-tags me-1"></i> Chọn mã ({{ $coupons->count() }})
                </button>
              @endif
            </div>

            <!-- Applied Coupon Box -->
            <div id="appliedCouponBox" class="{{ $appliedCoupon ? '' : 'd-none' }} mb-2">
              <div class="p-2 bg-white rounded-3 border border-warning d-flex justify-content-between align-items-center">
                <div>
                  <span class="fw-bold text-danger font-monospace fs-12" id="appliedCouponCode">{{ $appliedCoupon->code ?? '' }}</span>
                  <small class="d-block text-success fs-11" id="appliedCouponTitle"><i class="fa-solid fa-circle-check me-1"></i> {{ $appliedCoupon->title ?? '' }}</small>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeCartCoupon()" title="Hủy mã này">
                  <i class="fa-solid fa-xmark fs-5"></i>
                </button>
              </div>
            </div>

            <!-- Coupon Input Form -->
            <div id="couponInputBox" class="{{ $appliedCoupon ? 'd-none' : '' }}">
              <div class="input-group input-group-sm">
                <input type="text" id="manualCouponInput" class="form-control font-monospace text-uppercase" placeholder="Nhập mã voucher...">
                <button class="btn btn-bee-primary px-3 fw-bold" type="button" onclick="applyManualCoupon()">Áp Dụng</button>
              </div>
            </div>
          </div>

          <!-- Breakdown -->
          <div class="d-flex flex-column gap-2.5 small mb-3">
            <div class="d-flex justify-content-between">
              <span class="text-muted">Tạm tính (<span id="cartCountSummary">{{ $cartCount }}</span> món):</span>
              <span class="fw-semibold text-dark fs-6" id="cartSubtotalText">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Giảm giá voucher:</span>
              <span class="fw-semibold text-success fs-6" id="cartDiscountText">-{{ number_format($discount, 0, ',', '.') }}₫</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-muted">Phí vận chuyển:</span>
              <span class="fw-semibold {{ $shipping == 0 ? 'text-success' : 'text-dark' }}" id="cartShippingText">
                {{ $shipping == 0 ? 'Miễn phí (Freeship)' : number_format($shipping, 0, ',', '.') . '₫' }}
              </span>
            </div>
          </div>

          <hr class="border-secondary-subtle my-2">

          <div class="d-flex justify-content-between align-items-baseline mb-4">
            <span class="fw-bold text-dark fs-6">Tổng thanh toán:</span>
            <h3 class="fw-bold text-danger mb-0" id="cartTotalText">{{ number_format($total, 0, ',', '.') }}₫</h3>
          </div>

          @auth
            <a href="{{ route('client.checkout') }}" class="btn btn-bee-primary w-100 py-3 fs-6">
              Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          @else
            <div class="alert alert-warning border-0 d-flex align-items-center gap-2 small p-2 mb-3" style="border-radius: 8px;">
              <i class="fa-solid fa-shield-halved text-warning fs-5"></i>
              <div>
                <strong>Yêu cầu đăng nhập:</strong> Vui lòng đăng nhập để tiến hành thanh toán và lưu lịch sử đơn hàng.
              </div>
            </div>
            <a href="{{ route('client.checkout') }}" class="btn btn-bee-accent w-100 py-3 fs-6">
              <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Đăng Nhập Để Thanh Toán <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          @endauth

          <div class="mt-3 text-center text-muted small" style="font-size: 0.75rem;">
            <i class="fa-solid fa-lock me-1 text-warning"></i> Giao dịch bảo mật 100% chuẩn mã hóa SSL 256-bit
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- EMPTY CART STATE -->
  <div id="cartEmptyContainer" class="{{ count($cartItems) == 0 ? '' : 'd-none' }}">
    <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: 20px;">
      <div class="mb-3">
        <div class="rounded-circle bg-warning-subtle text-dark d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
          <i class="fa-solid fa-bag-shopping text-warning" style="font-size: 3rem;"></i>
        </div>
      </div>
      <h4 class="fw-bold text-dark mb-2">Giỏ hàng của bạn đang trống</h4>
      <p class="text-muted small mb-4">Hãy khám phá ngay các bộ sưu tập áo polo nam, sơ mi và blazer cao cấp của BeeStyle!</p>
      <div>
        <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary px-4 py-2.5 rounded-pill fw-bold shadow-xs">
          <i class="fa-solid fa-shirt me-1.5"></i> Mua Sắm Ngay
        </a>
      </div>
    </div>
  </div>
</div>

<!-- VOUCHER PICKER MODAL (SHOPEE STYLE) -->
<div class="modal fade" id="voucherPickerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-bottom pb-3">
        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
          <i class="fa-solid fa-ticket text-warning"></i>
          <span>Chọn Mã Giảm Giá Của BeeStyle</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3.5" style="max-height: 420px; overflow-y: auto;">
        @if(isset($coupons) && $coupons->count() > 0)
          <div class="d-flex flex-column gap-2.5">
            @foreach($coupons as $cp)
              <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between gap-3 transition-all hover-lift">
                <div class="min-w-0">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-danger text-white font-monospace fw-bold fs-12">{{ $cp->code }}</span>
                    <span class="badge bg-warning-subtle text-dark fw-bold" style="font-size: 0.68rem;">
                      {{ $cp->discount_type === 'percent' ? 'Giảm ' . $cp->discount_value . '%' : ($cp->discount_type === 'shipping' ? 'Freeship' : 'Giảm ' . number_format($cp->discount_value, 0, ',', '.') . '₫') }}
                    </span>
                  </div>
                  <strong class="text-dark d-block small mb-0.5">{{ $cp->title }}</strong>
                  <small class="text-muted fs-11 d-block">
                    Đơn tối thiểu: <strong>{{ number_format($cp->min_order_value, 0, ',', '.') }}₫</strong> • HSD: {{ $cp->expires_at ? $cp->expires_at->format('d/m/Y') : 'Vô thời hạn' }}
                  </small>
                </div>
                <button type="button" class="btn btn-bee-primary btn-sm px-3 py-1.5 rounded-pill fw-bold text-nowrap flex-shrink-0" onclick="applyCouponFromModal('{{ $cp->code }}')">
                  Áp Dụng
                </button>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-4">
            <i class="fa-regular fa-ticket text-muted fs-2 mb-2"></i>
            <p class="small text-muted mb-0">Hiện tại chưa có mã giảm giá nào khả dụng.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // CẬP NHẬT GIAO DIỆN TỔNG QUAN GIỎ HÀNG TỪ DỮ LIỆU JSON
  function renderCartSummary(cart) {
    if (!cart) return;

    // 1. Cập nhật số lượng
    const countTitle = document.getElementById('cartCountTitle');
    const countSummary = document.getElementById('cartCountSummary');
    if (countTitle) countTitle.textContent = cart.count;
    if (countSummary) countSummary.textContent = cart.count;

    // Cập nhật badge header
    const headerCartBadge = document.getElementById('cartCountBadge') || document.querySelector('.bee-cart-count');
    if (headerCartBadge) {
      headerCartBadge.textContent = cart.count;
      headerCartBadge.style.display = cart.count > 0 ? 'inline-block' : 'none';
    }

    // 2. Cập nhật tiền
    const subtotalEl = document.getElementById('cartSubtotalText');
    const discountEl = document.getElementById('cartDiscountText');
    const shippingEl = document.getElementById('cartShippingText');
    const totalEl = document.getElementById('cartTotalText');

    if (subtotalEl) subtotalEl.textContent = cart.subtotal_formatted;
    if (discountEl) discountEl.textContent = '-' + cart.discount_formatted;
    if (shippingEl) {
      shippingEl.textContent = cart.shipping == 0 ? 'Miễn phí (Freeship)' : cart.shipping_formatted;
      shippingEl.className = 'fw-semibold ' + (cart.shipping == 0 ? 'text-success' : 'text-dark');
    }
    if (totalEl) totalEl.textContent = cart.total_formatted;

    // 3. Cập nhật thanh tiến trình Freeship
    const fsProgress = document.getElementById('freeShippingProgressBar');
    const fsStatusText = document.getElementById('freeShippingStatusText');
    if (fsProgress && fsStatusText) {
      fsProgress.style.width = cart.free_shipping_percent + '%';
      if (cart.is_free_shipping) {
        fsProgress.className = 'progress-bar progress-bar-striped progress-bar-animated bg-success';
        fsStatusText.innerHTML = '<span class="fw-bold text-success fs-9">🎉 Chúc mừng! Đơn hàng của bạn đã đủ điều kiện nhận <strong>FREESHIP TOÀN QUỐC</strong>!</span>';
      } else {
        fsProgress.className = 'progress-bar progress-bar-striped progress-bar-animated bg-warning';
        fsStatusText.innerHTML = `<span class="fw-bold text-dark fs-9">🚚 Mua thêm <strong class="text-danger fs-6">${cart.free_shipping_needed_formatted}</strong> để được <strong class="text-success">MIỄN PHÍ VẬN CHUYỂN</strong>!</span>`;
      }
    }

    // 4. Cập nhật trạng thái Voucher
    const appliedBox = document.getElementById('appliedCouponBox');
    const inputBox = document.getElementById('couponInputBox');
    const appliedCode = document.getElementById('appliedCouponCode');
    const appliedTitle = document.getElementById('appliedCouponTitle');

    if (cart.coupon) {
      if (appliedBox) appliedBox.classList.remove('d-none');
      if (inputBox) inputBox.classList.add('d-none');
      if (appliedCode) appliedCode.textContent = cart.coupon.code;
      if (appliedTitle) appliedTitle.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> ${cart.coupon.title}`;
    } else {
      if (appliedBox) appliedBox.classList.add('d-none');
      if (inputBox) inputBox.classList.remove('d-none');
    }

    // 5. Kiểm tra nếu giỏ hàng trống hoàn toàn
    if (cart.count <= 0) {
      const mainBox = document.getElementById('cartMainContainer');
      const emptyBox = document.getElementById('cartEmptyContainer');
      if (mainBox) mainBox.classList.add('d-none');
      if (emptyBox) emptyBox.classList.remove('d-none');
    }
  }

  // 1. CẬP NHẬT SỐ LƯỢNG MẶT HÀNG TRONG GIỎ QUA AJAX
  function updateCartItemQty(key, newQty, triggerEl) {
    if (newQty < 1) newQty = 1;
    if (newQty > 10) newQty = 10;

    const row = document.getElementById('cartRow_' + key);
    if (!row) return;

    const input = row.querySelector('.cart-qty-input');
    if (input) input.value = newQty;

    fetch('{{ route("client.cart.update") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ key: key, quantity: newQty })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.cart) {
        // Cập nhật thành tiền của riêng món này
        const itemSubtotalEl = document.getElementById('subtotal_' + key);
        if (itemSubtotalEl && data.cart.items && data.cart.items[key]) {
          itemSubtotalEl.textContent = data.cart.items[key].subtotal_formatted;
        }

        // Cập nhật nút trừ / cộng
        const minusBtn = row.querySelector('.btn-step-minus');
        const plusBtn = row.querySelector('.btn-step-plus');
        if (minusBtn) minusBtn.disabled = (newQty <= 1);
        if (plusBtn) plusBtn.disabled = (newQty >= 10);

        renderCartSummary(data.cart);
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'warning', title: 'Thông báo', text: data.message || 'Không thể cập nhật số lượng.' });
        } else {
          alert(data.message || 'Không thể cập nhật số lượng.');
        }
      }
    })
    .catch(err => console.error('Error updating cart:', err));
  }

  // 2. XÓA MỘT SẢN PHẨM KHỎI GIỎ HÀNG QUA AJAX
  function removeCartItem(key, triggerEl) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Xác nhận xóa?',
        text: 'Bạn có chắc chắn muốn gỡ sản phẩm này khỏi giỏ hàng?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Đồng ý xóa',
        cancelButtonText: 'Hủy bỏ'
      }).then((result) => {
        if (result.isConfirmed) {
          executeRemoveCartItem(key);
        }
      });
    } else {
      if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        executeRemoveCartItem(key);
      }
    }
  }

  function executeRemoveCartItem(key) {
    const row = document.getElementById('cartRow_' + key);
    if (row) {
      row.style.opacity = '0.3';
      row.style.pointerEvents = 'none';
    }

    fetch(`/gio-hang/xoa/${key}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (row) row.remove();
        renderCartSummary(data.cart);
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'success', title: 'Đã Xóa', text: data.message, timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
        }
      } else {
        if (row) {
          row.style.opacity = '1';
          row.style.pointerEvents = 'auto';
        }
        alert(data.message || 'Không thể xóa sản phẩm.');
      }
    })
    .catch(err => console.error('Error removing item:', err));
  }

  // 3. LƯU SẢN PHẨM VÀO DANH SÁCH YÊU THÍCH ĐỂ MUA SAU (SAVE FOR LATER)
  function saveItemForLater(key, triggerEl) {
    const row = document.getElementById('cartRow_' + key);
    if (row) {
      row.style.opacity = '0.3';
      row.style.pointerEvents = 'none';
    }

    fetch(`/gio-hang/luu-tam/${key}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (row) row.remove();
        renderCartSummary(data.cart);

        // Cập nhật badge wishlist trên header
        const wishlistBadge = document.getElementById('wishlistCountBadge');
        if (wishlistBadge && data.wishlist_count !== undefined) {
          wishlistBadge.textContent = data.wishlist_count;
          wishlistBadge.style.display = data.wishlist_count > 0 ? 'flex' : 'none';
        }

        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'success', title: 'Đã Lưu Yêu Thích', text: data.message, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        }
      } else {
        if (row) {
          row.style.opacity = '1';
          row.style.pointerEvents = 'auto';
        }
        alert(data.message || 'Không thể lưu sản phẩm.');
      }
    })
    .catch(err => console.error('Error saving for later:', err));
  }

  // 4. XÓA SẠCH TOÀN BỘ GIỎ HÀNG
  function clearFullCart() {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Xóa toàn bộ giỏ hàng?',
        text: 'Tất cả sản phẩm đã chọn sẽ bị xóa sạch khỏi giỏ hàng.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Xóa sạch ngay',
        cancelButtonText: 'Giữ lại'
      }).then((result) => {
        if (result.isConfirmed) {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = '{{ route("client.cart.clear") }}';
          form.innerHTML = '@csrf';
          document.body.appendChild(form);
          form.submit();
        }
      });
    } else {
      if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("client.cart.clear") }}';
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
      }
    }
  }

  // 5. ÁP DỤNG MÃ GIẢM GIÁ TỪ MODAL HOẶC INPUT TAY
  function applyManualCoupon() {
    const input = document.getElementById('manualCouponInput');
    if (!input || !input.value.trim()) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'warning', title: 'Nhắc nhở', text: 'Vui lòng nhập mã giảm giá trước khi bấm áp dụng!' });
      } else {
        alert('Vui lòng nhập mã giảm giá.');
      }
      return;
    }
    executeApplyCoupon(input.value.trim());
  }

  function applyCouponFromModal(code) {
    const modalEl = document.getElementById('voucherPickerModal');
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }
    executeApplyCoupon(code);
  }

  function executeApplyCoupon(code) {
    fetch('{{ route("client.cart.applyCoupon") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ code: code })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.cart) {
        renderCartSummary(data.cart);
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'success', title: 'Áp Dụng Thành Công!', text: data.message, timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        }
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'error', title: 'Không Thể Áp Dụng', text: data.message || 'Mã giảm giá không hợp lệ.' });
        } else {
          alert(data.message || 'Mã giảm giá không hợp lệ.');
        }
      }
    })
    .catch(err => console.error('Error applying coupon:', err));
  }

  function removeCartCoupon() {
    fetch('{{ route("client.cart.removeCoupon") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.cart) {
        renderCartSummary(data.cart);
        if (typeof Swal !== 'undefined') {
          Swal.fire({ icon: 'info', title: 'Đã Hủy Voucher', text: data.message, timer: 1500, showConfirmButton: false, toast: true, position: 'top-end' });
        }
      }
    })
    .catch(err => console.error('Error removing coupon:', err));
  }

  // 6. THÊM NHANH SẢN PHẨM MUA KÈM (1-CLICK QUICK ADD)
  function quickAddCrossSell(productId, btnEl) {
    const originalHtml = btnEl.innerHTML;
    btnEl.disabled = true;
    btnEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang thêm...';

    fetch('{{ route("client.cart.add") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(res => res.json())
    .then(data => {
      btnEl.disabled = false;
      btnEl.innerHTML = originalHtml;

      if (data.success) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Đã Thêm Vào Giỏ!',
            text: data.message,
            timer: 1800,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
          }).then(() => {
            window.location.reload();
          });
        } else {
          window.location.reload();
        }
      } else {
        alert(data.message || 'Không thể thêm sản phẩm.');
      }
    })
    .catch(err => {
      btnEl.disabled = false;
      btnEl.innerHTML = originalHtml;
      console.error('Error adding cross-sell item:', err);
    });
  }
</script>
@endpush

