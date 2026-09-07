@extends('layouts.client')

@section('title', 'Thanh Toán Đơn Hàng — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-6xl mx-auto">
  
  <!-- Breadcrumb Steps -->
  <div class="flex items-center justify-center gap-4 text-xs font-semibold uppercase tracking-wider text-neutral-400 mb-10 pb-4 border-b border-neutral-200">
    <a href="{{ route('client.cart') }}" class="text-neutral-700 hover:text-black flex items-center gap-1.5">
      <span class="w-5 h-5 rounded-full bg-neutral-200 text-neutral-700 flex items-center justify-center text-[10px] font-bold">1</span>
      Túi Mua Hàng
    </a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-neutral-300"></i>
    <span class="text-neutral-950 font-bold flex items-center gap-1.5">
      <span class="w-5 h-5 rounded-full bg-neutral-950 text-white flex items-center justify-center text-[10px] font-bold">2</span>
      Thông Tin &amp; Thanh Toán
    </span>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-neutral-300"></i>
    <span class="flex items-center gap-1.5 text-neutral-400">
      <span class="w-5 h-5 rounded-full bg-neutral-100 text-neutral-400 flex items-center justify-center text-[10px] font-bold">3</span>
      Hoàn Tất
    </span>
  </div>

  @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl mb-6 text-xs animate-fade-in">
      <div class="flex items-center gap-2 font-semibold mb-1">
        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
        <span>Vui lòng kiểm tra lại thông tin giao hàng:</span>
      </div>
      <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-2">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Main 2-Column Checkout Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
    
    <!-- Left Column: Delivery Info & Payment Methods (7 cols) -->
    <div class="lg:col-span-7 space-y-8">
      
      <form action="{{ route('client.checkout.process') }}" method="POST" id="checkout-form" class="space-y-8">
        @csrf

        <!-- STEP 1: Thông Tin Giao Hàng -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm">
          <div class="flex items-center justify-between pb-4 mb-6 border-b border-neutral-100">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-neutral-900 text-white flex items-center justify-center text-xs font-bold font-mono">1</span>
              <h3 class="font-serif-luxury text-xl font-bold text-neutral-900">Thông Tin Người Nhận</h3>
            </div>
            @guest
              <a href="{{ route('auth.login') }}" class="text-xs text-amber-800 hover:underline font-semibold">Đăng nhập để chọn địa chỉ</a>
            @endguest
          </div>

          @if(isset($addresses) && $addresses->isNotEmpty())
            <!-- Saved Address Selector -->
            <div class="mb-6 p-4 bg-brand-50 rounded-xl border border-brand-200">
              <label class="block font-semibold uppercase text-neutral-800 text-[11px] mb-2">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 inline text-amber-800 mr-1"></i> Chọn sổ địa chỉ đã lưu:
              </label>
              <select id="savedAddressSelect" onchange="fillSavedAddress(this)" class="w-full bg-white border border-neutral-300 rounded-lg p-2 text-xs text-neutral-800 focus:outline-none focus:border-neutral-950">
                <option value="">-- Nhập địa chỉ mới --</option>
                @foreach($addresses as $addr)
                  <option value="{{ $addr->id }}" 
                    data-name="{{ $addr->receiver_name }}" 
                    data-phone="{{ $addr->receiver_phone }}" 
                    data-address="{{ $addr->detail_address }}"
                    data-city="{{ $addr->province_name }}"
                    data-district="{{ $addr->district_name }}"
                    data-ward="{{ $addr->ward_name }}"
                    {{ $defaultAddress && $defaultAddress->id === $addr->id ? 'selected' : '' }}>
                    {{ $addr->receiver_name }} — {{ $addr->receiver_phone }} ({{ $addr->detail_address }}, {{ $addr->ward_name }}, {{ $addr->district_name }}, {{ $addr->province_name }})
                  </option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Họ và Tên <span class="text-rose-600">*</span></label>
                <input type="text" name="customer_name" id="cust_name" value="{{ old('customer_name', $user->name ?? '') }}" required class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
              </div>
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Số Điện Thoại <span class="text-rose-600">*</span></label>
                <input type="tel" name="customer_phone" id="cust_phone" value="{{ old('customer_phone', $user->phone ?? '') }}" required placeholder="0987654321" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
              </div>
            </div>

            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Email nhận hóa đơn điện tử</label>
              <input type="email" name="customer_email" id="cust_email" value="{{ old('customer_email', $user->email ?? '') }}" placeholder="email@gmail.com" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
            </div>

            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Địa chỉ nhận hàng (Số nhà, tên đường) <span class="text-rose-600">*</span></label>
              <input type="text" name="shipping_address" id="cust_address" value="{{ old('shipping_address', $defaultAddress->detail_address ?? '') }}" required placeholder="Ví dụ: 88 Lê Lợi..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Tỉnh / Thành Phố</label>
                <input type="text" name="city" id="cust_city" value="{{ old('city', $defaultAddress->province_name ?? 'TP. Hồ Chí Minh') }}" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
              </div>
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Quận / Huyện</label>
                <input type="text" name="district" id="cust_district" value="{{ old('district', $defaultAddress->district_name ?? 'Quận 1') }}" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
              </div>
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Phường / Xã</label>
                <input type="text" name="ward" id="cust_ward" value="{{ old('ward', $defaultAddress->ward_name ?? 'Bến Nghé') }}" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
              </div>
            </div>

            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Ghi chú cho nghệ nhân may đo / đóng gói</label>
              <textarea name="notes" rows="2" placeholder="Ví dụ: Giao giờ hành chính, đóng hộp quà tặng..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-3 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors"></textarea>
            </div>
          </div>
        </div>

        <!-- STEP 2: Phương Thức Thanh Toán -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm">
          <div class="flex items-center gap-2 pb-4 mb-6 border-b border-neutral-100">
            <span class="w-6 h-6 rounded-full bg-neutral-900 text-white flex items-center justify-center text-xs font-bold font-mono">2</span>
            <h3 class="font-serif-luxury text-xl font-bold text-neutral-900">Phương Thức Thanh Toán</h3>
          </div>

          <div class="space-y-3 text-xs">
            <!-- COD -->
            <label class="flex items-start gap-3 p-4 border-2 border-neutral-950 bg-neutral-50 rounded-xl cursor-pointer payment-option transition-all">
              <input type="radio" name="payment_method" value="cod" checked class="mt-0.5 text-neutral-900 focus:ring-neutral-900">
              <div class="flex-grow">
                <div class="flex items-center justify-between">
                  <span class="font-bold text-neutral-900 uppercase">Thanh toán khi nhận hàng (COD)</span>
                  <i data-lucide="banknote" class="w-4 h-4 text-neutral-700"></i>
                </div>
                <p class="text-neutral-500 mt-0.5 text-[11px]">Kiểm tra sản phẩm và thanh toán tiền mặt trực tiếp cho bưu tá.</p>
              </div>
            </label>

            <!-- MoMo -->
            <label class="flex items-start gap-3 p-4 border border-neutral-200 hover:border-neutral-900 rounded-xl cursor-pointer payment-option transition-all">
              <input type="radio" name="payment_method" value="momo" class="mt-0.5 text-neutral-900 focus:ring-neutral-900">
              <div class="flex-grow">
                <div class="flex items-center justify-between">
                  <span class="font-bold text-neutral-900 uppercase">Ví Điện Tử MoMo (Quét Mã QR)</span>
                  <span class="px-2 py-0.5 bg-pink-100 text-pink-700 rounded text-[10px] font-bold">MOMO QR</span>
                </div>
                <p class="text-neutral-500 mt-0.5 text-[11px]">Thanh toán tức thì qua ứng dụng MoMo an toàn 100%.</p>
              </div>
            </label>

            <!-- Online Banking / VietQR -->
            <label class="flex items-start gap-3 p-4 border border-neutral-200 hover:border-neutral-900 rounded-xl cursor-pointer payment-option transition-all">
              <input type="radio" name="payment_method" value="online" class="mt-0.5 text-neutral-900 focus:ring-neutral-900">
              <div class="flex-grow">
                <div class="flex items-center justify-between">
                  <span class="font-bold text-neutral-900 uppercase">Chuyển Khoản Ngân Hàng (VietQR)</span>
                  <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold">VIETQR 24/7</span>
                </div>
                <p class="text-neutral-500 mt-0.5 text-[11px]">Chuyển khoản liên ngân hàng 24/7 với mã QR tự động xác thực.</p>
              </div>
            </label>
          </div>
        </div>

        <!-- Submit Button for Mobile -->
        <button type="submit" class="w-full py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.25em] uppercase rounded-xl shadow-xl transition-all flex items-center justify-center gap-2">
          <i data-lucide="lock" class="w-4 h-4"></i>
          <span>Xác Nhận Đặt Hàng An Toàn</span>
        </button>

      </form>

    </div>

    <!-- Right Column: Order Summary & Item List (5 cols) -->
    <div class="lg:col-span-5 bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm sticky top-28">
      <div class="flex items-center justify-between pb-4 mb-6 border-b border-neutral-100">
        <h3 class="font-serif-luxury text-xl font-bold text-neutral-900">Tóm Tắt Đơn Hàng</h3>
        <span class="text-xs text-neutral-500">{{ $cartCount ?? count($cartItems) }} sản phẩm</span>
      </div>

      <!-- Items List -->
      <div class="space-y-4 max-h-80 overflow-y-auto pr-1 mb-6">
        @foreach($cartItems as $item)
          @php
            $itemImg = $item['image'] ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=400&auto=format&fit=crop';
            if (!str_starts_with($itemImg, 'http')) {
              $itemImg = asset($itemImg);
            }
          @endphp
          <div class="flex gap-3.5 pb-3 border-b border-neutral-100">
            <div class="w-16 h-20 rounded-lg bg-neutral-100 overflow-hidden shrink-0 border border-neutral-200">
              <img src="{{ $itemImg }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
            </div>
            <div class="flex-grow flex flex-col justify-between text-xs">
              <div>
                <h4 class="font-semibold text-neutral-900 line-clamp-1">{{ $item['name'] }}</h4>
                <p class="text-[11px] text-neutral-500 mt-0.5">
                  @if(!empty($item['color'])) Màu: {{ $item['color'] }} @endif
                  @if(!empty($item['size'])) | Size: {{ $item['size'] }} @endif
                </p>
                <span class="text-neutral-400 text-[11px]">Số lượng: {{ $item['quantity'] }}</span>
              </div>
              <div class="font-serif-luxury text-sm font-bold text-neutral-950">
                {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}₫
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Price Breakdown -->
      <div class="space-y-2.5 text-xs text-neutral-600 pb-4 mb-4 border-b border-neutral-200">
        <div class="flex justify-between">
          <span>Tạm tính giỏ hàng:</span>
          <span class="font-semibold text-neutral-900">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
        </div>
        <div class="flex justify-between">
          <span>Phí vận chuyển toàn quốc:</span>
          @if($shipping == 0)
            <span class="font-semibold text-emerald-700">MIỄN PHÍ</span>
          @else
            <span class="font-semibold text-neutral-900">{{ number_format($shipping, 0, ',', '.') }}₫</span>
          @endif
        </div>
        @if($discount > 0)
          <div class="flex justify-between text-rose-700">
            <span>Ưu đãi giảm giá (Coupon):</span>
            <span class="font-semibold">-{{ number_format($discount, 0, ',', '.') }}₫</span>
          </div>
        @endif
      </div>

      <!-- Total Price -->
      <div class="flex justify-between items-baseline mb-6">
        <span class="text-xs uppercase font-bold tracking-wider text-neutral-900">Tổng Thanh Toán:</span>
        <div class="text-right">
          <span class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-950 block">
            {{ number_format($total, 0, ',', '.') }}₫
          </span>
          <span class="text-[10px] text-neutral-400">Đã bao gồm thuế VAT &amp; Phí đóng gói Atelier</span>
        </div>
      </div>

      <!-- Guarantees Badge -->
      <div class="p-3 bg-brand-50 rounded-xl border border-brand-200 text-[11px] text-neutral-600 space-y-1.5">
        <div class="flex items-center gap-2 text-neutral-800 font-semibold">
          <i data-lucide="shield-check" class="w-4 h-4 text-amber-800"></i>
          <span>ĐẶC QUYỀN KHÁCH HÀNG BEESTYLE:</span>
        </div>
        <p>• Đồng kiểm tra sản phẩm trước khi thanh toán.</p>
        <p>• Hỗ trợ đổi size tận nơi miễn phí 30 ngày.</p>
      </div>

    </div>

  </div>
</main>
@endsection

@push('scripts')
<script>
  function fillSavedAddress(select) {
    const opt = select.options[select.selectedIndex];
    if (opt.value) {
      document.getElementById('cust_name').value = opt.getAttribute('data-name') || '';
      document.getElementById('cust_phone').value = opt.getAttribute('data-phone') || '';
      document.getElementById('cust_address').value = opt.getAttribute('data-address') || '';
      document.getElementById('cust_city').value = opt.getAttribute('data-city') || '';
      document.getElementById('cust_district').value = opt.getAttribute('data-district') || '';
      document.getElementById('cust_ward').value = opt.getAttribute('data-ward') || '';
    }
  }

  // Highlight selected payment method
  document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
      document.querySelectorAll('.payment-option').forEach(el => {
        el.className = el.className.replace('border-neutral-950 bg-neutral-50', 'border-neutral-200');
      });
      this.closest('.payment-option').className = this.closest('.payment-option').className.replace('border-neutral-200', 'border-neutral-950 bg-neutral-50');
    });
  });
</script>
@endpush
