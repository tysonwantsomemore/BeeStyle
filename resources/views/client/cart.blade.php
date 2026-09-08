@extends('layouts.client')

@section('title', 'Túi Mua Hàng — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Túi Mua Hàng (<span id="cartCountTitle">{{ $cartCount ?? 0 }}</span> sản phẩm)</span>
  </nav>

  <!-- Empty Cart View -->
  <div id="cartEmptyContainer" class="bg-white p-12 md:p-16 rounded-2xl border border-neutral-200 text-center max-w-md mx-auto shadow-sm my-8 {{ (!empty($cartItems) && count($cartItems) > 0) ? 'hidden' : '' }}">
    <div class="w-20 h-20 rounded-full bg-brand-100 flex items-center justify-center mx-auto mb-4 text-neutral-400">
      <i data-lucide="shopping-bag" class="w-10 h-10 stroke-1"></i>
    </div>
    <h2 class="font-serif-luxury text-2xl md:text-3xl text-neutral-900 mb-2 font-medium">Túi hàng của bạn đang trống</h2>
    <p class="text-xs text-neutral-500 font-light mb-8 leading-relaxed">
      Khám phá những thiết kế sơ mi lụa tơ tằm, blazer may đo chuẩn Ý và các phụ kiện độc bản trong bộ sưu tập 2026.
    </p>
    <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-neutral-950 text-white text-xs font-semibold tracking-[0.2em] uppercase rounded-lg hover:bg-neutral-800 transition-all shadow-lg">
      <span>Khám Phá Bộ Sưu Tập</span>
      <i data-lucide="arrow-right" class="w-4 h-4 text-amber-400"></i>
    </a>
  </div>

  <!-- Main Cart Container -->
  <div id="cartMainContainer" class="{{ (empty($cartItems) || count($cartItems) === 0) ? 'hidden' : '' }}">
    <!-- Free Shipping Progress -->
    @php
      $freeShippingThreshold = 500000;
      $sub = $subtotal ?? 0;
      $fsPercent = min(100, round(($sub / $freeShippingThreshold) * 100));
      $isFs = $sub >= $freeShippingThreshold;
    @endphp
    <div class="bg-brand-100 border border-brand-200 p-4 rounded-xl mb-8 flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-3 text-xs">
        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-amber-800 shrink-0 shadow-xs">
          <i data-lucide="truck" class="w-4 h-4"></i>
        </div>
        <div id="freeShippingStatusText">
          @if($isFs)
            <span class="font-semibold text-emerald-800">🎉 Đơn hàng của bạn đủ điều kiện FREESHIP TOÀN QUỐC!</span>
          @else
            <span class="text-neutral-700">Mua thêm <strong class="text-neutral-950 font-bold">{{ number_format($freeShippingThreshold - $sub, 0, ',', '.') }}₫</strong> để nhận <strong class="text-emerald-700">Miễn Phí Vận Chuyển</strong></span>
          @endif
        </div>
      </div>
      <div class="w-full sm:w-48 bg-white rounded-full h-2 overflow-hidden border border-brand-200 shrink-0">
        <div id="freeShippingProgressBar" class="bg-neutral-900 h-full rounded-full transition-all duration-500" style="width: {{ $fsPercent }}%;"></div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
      
      <!-- Cart Items Table (8 cols) -->
      <div class="lg:col-span-8 bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-neutral-100">
          <h2 class="font-serif-luxury text-2xl font-bold text-neutral-900">Danh Sách Tác Phẩm</h2>
          <div class="flex items-center gap-4">
            <button type="button" onclick="clearFullCart()" class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1">
              <i data-lucide="trash" class="w-3.5 h-3.5"></i> Xóa tất cả
            </button>
            <a href="{{ route('client.products.index') }}" class="text-xs text-neutral-500 hover:text-black font-semibold flex items-center gap-1">
              <i data-lucide="plus" class="w-3.5 h-3.5"></i> Thêm sản phẩm
            </a>
          </div>
        </div>

        <div class="divide-y divide-neutral-100" id="cartItemsList">
          @if(!empty($cartItems))
            @foreach($cartItems as $item)
              @php
                $itemKey = $item['key'] ?? $loop->index;
                $itemImg = $item['image'] ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=400&auto=format&fit=crop';
                if (!str_starts_with($itemImg, 'http')) {
                  $itemImg = asset($itemImg);
                }
              @endphp
              <div id="cartRow_{{ $itemKey }}" class="py-6 flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between transition-opacity duration-200">
                <!-- Thumbnail & Info -->
                <div class="flex gap-4 items-center">
                  <a href="{{ route('client.products.show', $item['product_id']) }}" class="w-20 h-24 rounded-lg bg-neutral-100 overflow-hidden shrink-0 border border-neutral-200">
                    <img src="{{ $itemImg }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                  </a>
                  <div class="space-y-1">
                    <span class="text-[10px] tracking-widest uppercase text-neutral-400 font-semibold block">ATELIER TAILORING</span>
                    <a href="{{ route('client.products.show', $item['product_id']) }}" class="font-serif-luxury text-base font-semibold text-neutral-900 hover:text-amber-800 transition-colors">
                      {{ $item['name'] }}
                    </a>
                    <p class="text-xs text-neutral-500">
                      @if(!empty($item['color'])) Màu: <strong class="text-neutral-800">{{ $item['color'] }}</strong> @endif
                      @if(!empty($item['size'])) | Size: <strong class="text-neutral-800">{{ $item['size'] }}</strong> @endif
                    </p>
                    <div class="flex items-center gap-3 pt-1">
                      <span class="text-xs font-semibold text-neutral-900 block sm:hidden">
                        {{ number_format($item['price'], 0, ',', '.') }}₫
                      </span>
                      <button type="button" onclick="saveItemForLater('{{ $itemKey }}', this)" class="text-[11px] text-neutral-400 hover:text-amber-700 flex items-center gap-1 transition-colors">
                        <i data-lucide="bookmark" class="w-3 h-3"></i> Lưu mua sau
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Price, Quantity, Subtotal & Delete -->
                <div class="flex items-center justify-between w-full sm:w-auto gap-6">
                  <span class="font-serif-luxury text-sm font-semibold text-neutral-900 hidden sm:block">
                    {{ number_format($item['price'], 0, ',', '.') }}₫
                  </span>

                  <!-- Quantity Controls (AJAX enabled) -->
                  <div class="flex items-center border border-neutral-300 rounded-lg overflow-hidden bg-white">
                    <button type="button" onclick="updateCartItemQty('{{ $itemKey }}', {{ $item['quantity'] - 1 }}, this)" {{ $item['quantity'] <= 1 ? 'disabled' : '' }} class="btn-step-minus px-2.5 py-1.5 text-xs text-neutral-600 hover:bg-neutral-100 disabled:opacity-40 transition-colors">-</button>
                    <input type="text" readonly value="{{ $item['quantity'] }}" class="cart-qty-input w-10 text-center text-xs font-bold text-neutral-900 focus:outline-none bg-transparent">
                    <button type="button" onclick="updateCartItemQty('{{ $itemKey }}', {{ $item['quantity'] + 1 }}, this)" class="btn-step-plus px-2.5 py-1.5 text-xs text-neutral-600 hover:bg-neutral-100 transition-colors">+</button>
                  </div>

                  <!-- Subtotal for item -->
                  <span id="subtotal_{{ $itemKey }}" class="font-serif-luxury text-base font-bold text-neutral-950 min-w-[90px] text-right">
                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫
                  </span>

                  <!-- Remove Button (AJAX enabled) -->
                  <button type="button" onclick="removeCartItem('{{ $itemKey }}', this)" class="p-1.5 text-neutral-400 hover:text-rose-600 transition-colors" title="Xóa sản phẩm">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>

      <!-- Cart Summary (4 cols) -->
      <div class="lg:col-span-4 space-y-6">
        
        <!-- Coupon Form -->
        <div class="bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm">
          <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 mb-3 uppercase tracking-wider">Mã Ưu Đãi</h3>
          
          <div id="couponInputBox" class="{{ (isset($appliedCoupon) && $appliedCoupon) ? 'hidden' : 'flex' }} gap-2">
            <input type="text" id="manualCouponInput" placeholder="Nhập mã (BEESTYLE15...)" value="{{ $appliedCoupon->code ?? '' }}" class="bg-neutral-50 border border-neutral-200 rounded-lg px-3 py-2 text-xs text-neutral-900 uppercase focus:outline-none focus:border-neutral-950 flex-grow font-mono">
            <button type="button" onclick="applyManualCoupon()" class="px-4 py-2 bg-neutral-950 text-white text-xs font-semibold tracking-wider uppercase rounded-lg hover:bg-neutral-800 transition-colors">
              Áp Dụng
            </button>
          </div>

          <div id="appliedCouponBox" class="mt-2 text-[11px] text-emerald-700 {{ (isset($appliedCoupon) && $appliedCoupon) ? 'flex' : 'hidden' }} items-center justify-between bg-emerald-50 border border-emerald-200 p-2.5 rounded-lg">
            <div>
              <span id="appliedCouponTitle" class="font-semibold">Đã áp dụng mã:</span>
              <strong id="appliedCouponCode" class="font-mono text-emerald-900 ml-1">{{ $appliedCoupon->code ?? '' }}</strong>
            </div>
            <button type="button" onclick="removeCartCoupon()" class="text-rose-600 hover:underline font-semibold text-xs ml-2">Gỡ bỏ</button>
          </div>
        </div>

        <!-- Summary Calculation Box -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm space-y-4 text-xs">
          <h3 class="font-serif-luxury text-xl font-bold text-neutral-900 pb-3 border-b border-neutral-100">Tóm Tắt Chi Phí</h3>

          <div class="space-y-2.5 text-neutral-600">
            <div class="flex justify-between">
              <span>Tạm tính (<span id="cartCountSummary">{{ $cartCount ?? 0 }}</span> món):</span>
              <span id="cartSubtotalText" class="font-semibold text-neutral-900">{{ number_format($subtotal ?? 0, 0, ',', '.') }}₫</span>
            </div>
            <div class="flex justify-between">
              <span>Phí vận chuyển:</span>
              <span id="cartShippingText" class="font-semibold text-neutral-900">{{ ($shipping ?? 0) == 0 ? 'MIỄN PHÍ' : number_format($shipping, 0, ',', '.') . '₫' }}</span>
            </div>
            <div id="discountRow" class="flex justify-between text-rose-700 font-semibold {{ (isset($discount) && $discount > 0) ? '' : 'hidden' }}">
              <span>Ưu đãi giảm giá:</span>
              <span id="cartDiscountText">-{{ number_format($discount ?? 0, 0, ',', '.') }}₫</span>
            </div>
          </div>

          <div class="pt-4 border-t border-neutral-200 flex justify-between items-baseline">
            <span class="font-bold uppercase tracking-wider text-neutral-900 text-xs">Tổng Thanh Toán:</span>
            <div class="text-right">
              <span id="cartTotalText" class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-950 block">
                {{ number_format($total ?? 0, 0, ',', '.') }}₫
              </span>
              <span class="text-[10px] text-neutral-400">Đã bao gồm thuế VAT</span>
            </div>
          </div>

          <a href="{{ route('client.checkout') }}" class="w-full py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.25em] uppercase rounded-xl shadow-xl transition-all flex items-center justify-center gap-2 mt-4">
            <i data-lucide="lock" class="w-4 h-4"></i>
            <span>Tiến Hành Đặt Hàng</span>
          </a>
        </div>

      </div>

    </div>
  </div>

</main>
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
    const discountRow = document.getElementById('discountRow');
    const shippingEl = document.getElementById('cartShippingText');
    const totalEl = document.getElementById('cartTotalText');

    if (subtotalEl) subtotalEl.textContent = cart.subtotal_formatted || (cart.subtotal ? cart.subtotal.toLocaleString('vi-VN') + '₫' : '0₫');
    
    if (cart.discount > 0) {
      if (discountRow) discountRow.classList.remove('hidden');
      if (discountEl) discountEl.textContent = '-' + (cart.discount_formatted || cart.discount.toLocaleString('vi-VN') + '₫');
    } else {
      if (discountRow) discountRow.classList.add('hidden');
    }

    if (shippingEl) {
      shippingEl.textContent = (cart.shipping == 0) ? 'MIỄN PHÍ' : (cart.shipping_formatted || cart.shipping.toLocaleString('vi-VN') + '₫');
    }
    if (totalEl) totalEl.textContent = cart.total_formatted || (cart.total ? cart.total.toLocaleString('vi-VN') + '₫' : '0₫');

    // 3. Cập nhật thanh tiến trình Freeship
    const fsProgress = document.getElementById('freeShippingProgressBar');
    const fsStatusText = document.getElementById('freeShippingStatusText');
    if (fsProgress && fsStatusText) {
      fsProgress.style.width = cart.free_shipping_percent + '%';
      if (cart.is_free_shipping) {
        fsStatusText.innerHTML = '<span class="font-semibold text-emerald-800">🎉 Đơn hàng của bạn đủ điều kiện FREESHIP TOÀN QUỐC!</span>';
      } else {
        fsStatusText.innerHTML = `<span class="text-neutral-700">Mua thêm <strong class="text-neutral-950 font-bold">${cart.free_shipping_needed_formatted || ''}</strong> để nhận <strong class="text-emerald-700">Miễn Phí Vận Chuyển</strong></span>`;
      }
    }

    // 4. Cập nhật trạng thái Voucher
    const appliedBox = document.getElementById('appliedCouponBox');
    const inputBox = document.getElementById('couponInputBox');
    const appliedCode = document.getElementById('appliedCouponCode');
    const appliedTitle = document.getElementById('appliedCouponTitle');

    if (cart.coupon) {
      if (appliedBox) {
        appliedBox.classList.remove('hidden');
        appliedBox.classList.add('flex');
      }
      if (inputBox) {
        inputBox.classList.add('hidden');
        inputBox.classList.remove('flex');
      }
      if (appliedCode) appliedCode.textContent = cart.coupon.code;
      if (appliedTitle) appliedTitle.textContent = `Đã áp dụng (${cart.coupon.title || cart.coupon.code}):`;
    } else {
      if (appliedBox) {
        appliedBox.classList.add('hidden');
        appliedBox.classList.remove('flex');
      }
      if (inputBox) {
        inputBox.classList.remove('hidden');
        inputBox.classList.add('flex');
      }
    }

    // 5. Kiểm tra nếu giỏ hàng trống hoàn toàn
    if (cart.count <= 0) {
      const mainBox = document.getElementById('cartMainContainer');
      const emptyBox = document.getElementById('cartEmptyContainer');
      if (mainBox) mainBox.classList.add('hidden');
      if (emptyBox) emptyBox.classList.remove('hidden');
    }
  }

  // 1. CẬP NHẬT SỐ LƯỢNG MẶT HÀNG TRONG GIỎ QUA AJAX
  function updateCartItemQty(key, newQty, triggerEl) {
    if (newQty < 1) newQty = 1;

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
        const itemSubtotalEl = document.getElementById('subtotal_' + key);
        if (itemSubtotalEl && data.cart.items && data.cart.items[key]) {
          itemSubtotalEl.textContent = data.cart.items[key].subtotal_formatted;
        }

        const minusBtn = row.querySelector('.btn-step-minus');
        const plusBtn = row.querySelector('.btn-step-plus');
        if (minusBtn) minusBtn.disabled = (newQty <= 1);
        if (plusBtn) plusBtn.disabled = false;

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
        confirmButtonColor: '#171717',
        cancelButtonColor: '#a3a3a3',
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

    fetch('{{ route("client.cart.remove") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ cart_key: key })
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

  // 3. LƯU SẢN PHẨM VÀO DANH SÁCH YÊU THÍCH (SAVE FOR LATER)
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
    const executeClear = () => {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '{{ route("client.cart.clear") }}';
      form.innerHTML = '@csrf';
      document.body.appendChild(form);
      form.submit();
    };

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Xóa toàn bộ giỏ hàng?',
        text: 'Tất cả sản phẩm đã chọn sẽ bị xóa sạch khỏi giỏ hàng.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#171717',
        cancelButtonColor: '#a3a3a3',
        confirmButtonText: 'Xóa sạch ngay',
        cancelButtonText: 'Giữ lại'
      }).then((result) => {
        if (result.isConfirmed) executeClear();
      });
    } else {
      if (confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')) executeClear();
    }
  }

  // 5. ÁP DỤNG MÃ GIẢM GIÁ
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

  function executeApplyCoupon(code) {
    fetch('{{ route("client.cart.apply-coupon") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ coupon_code: code })
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
    fetch('{{ route("client.cart.remove-coupon") }}', {
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

  // Khởi tạo icons nếu dùng Lucide
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });
</script>
@endpush