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

  <!-- Main 2-Column Checkout Form -->
  <form action="{{ route('client.checkout.process') }}" method="POST" id="checkout-form">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
      
      <!-- Left Column: Delivery Info & Payment Methods (7 cols) -->
      <div class="lg:col-span-7 space-y-8">
        
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
              <select id="savedAddressSelect" onchange="fillSavedAddress(this)" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-800 focus:outline-none focus:border-neutral-950">
                <option value="">-- Nhập địa chỉ mới --</option>
                @foreach($addresses as $addr)
                  <option value="{{ $addr->id }}" 
                    data-name="{{ $addr->receiver_name }}" 
                    data-phone="{{ $addr->receiver_phone }}" 
                    data-address="{{ $addr->detail_address }}"
                    data-city="{{ $addr->province_name }}"
                    data-district="{{ $addr->district_name }}"
                    data-ward="{{ $addr->ward_name }}"
                    {{ (isset($defaultAddress) && $defaultAddress && $defaultAddress->id === $addr->id) ? 'selected' : '' }}>
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

          @if(!empty($depositInfo['is_required']))
            <!-- Thông Báo Đặt Cọc 50% Cho Đơn Hàng >= 10 Sản Phẩm -->
            <div class="p-4 mb-6 rounded-xl border border-amber-300 bg-amber-50 text-xs">
              <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-full bg-amber-400 text-neutral-950 flex items-center justify-center shrink-0 shadow-sm">
                  <i data-lucide="coins" class="w-5 h-5"></i>
                </div>
                <div class="w-full space-y-2">
                  <div class="flex items-center justify-between flex-wrap gap-2">
                    <strong class="text-neutral-950 font-bold uppercase tracking-wide flex items-center gap-1.5">
                      <i data-lucide="shield-alert" class="w-4 h-4 text-amber-700"></i> Chính Sách Đặt Cọc 50% Đơn Hàng Số Lượng Lớn
                    </strong>
                    <span class="px-2 py-0.5 bg-amber-200 text-amber-900 rounded font-mono font-bold text-[10px]">
                      {{ $depositInfo['total_quantity'] }} Sản Phẩm (≥ 10)
                    </span>
                  </div>
                  <p class="text-neutral-700 leading-relaxed text-[11px]">
                    Đơn hàng của quý khách có tổng <strong>{{ $depositInfo['total_quantity'] }} sản phẩm</strong>. Theo chính sách của BeeStyle, quý khách vui lòng <strong>đặt cọc trước 50%</strong> để xưởng chuẩn bị may đo và đóng gói xuất kho. Số tiền 50% còn lại thanh toán cho bưu tá khi nhận hàng.
                  </p>
                  <div class="p-3 bg-white rounded-lg border border-amber-200 flex items-center justify-between flex-wrap gap-3">
                    <div>
                      <span class="text-neutral-400 text-[10px] block uppercase">Cọc trước (50%):</span>
                      <strong class="text-rose-600 font-mono text-sm">{{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫</strong>
                    </div>
                    <div class="text-right">
                      <span class="text-neutral-400 text-[10px] block uppercase">Còn lại thanh toán COD (50%):</span>
                      <strong class="text-neutral-900 font-mono text-sm">{{ number_format($depositInfo['remaining_amount'], 0, ',', '.') }}₫</strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endif

          <div class="space-y-3.5 text-xs" id="paymentMethodContainer">
            
            <!-- COD -->
            <label class="pay-option-card block p-4 border-2 border-neutral-950 bg-neutral-50 rounded-xl cursor-pointer transition-all active" id="card_pay_cod">
              <div class="flex items-center justify-between">
                <div class="flex items-start gap-3">
                  <input type="radio" name="payment_method" id="pay_cod" value="cod" checked class="pay-radio mt-0.5 text-neutral-900 focus:ring-neutral-900" onchange="updatePayOptionCards()">
                  <div>
                    <div class="flex items-center gap-2 flex-wrap">
                      <strong class="text-neutral-950 text-sm font-semibold">
                        @if(!empty($depositInfo['is_required']))
                          Đặt cọc 50% &amp; Thu COD 50% khi nhận hàng
                        @else
                          Thanh toán khi nhận hàng (COD)
                        @endif
                      </strong>
                      <span class="px-2 py-0.5 {{ !empty($depositInfo['is_required']) ? 'bg-amber-100 text-amber-800' : 'bg-neutral-200 text-neutral-800' }} rounded text-[10px] font-bold">
                        {{ !empty($depositInfo['is_required']) ? 'Cọc 50%' : 'Phổ Biến' }}
                      </span>
                    </div>
                    <p class="text-neutral-500 mt-1 text-[11px] leading-relaxed">
                      @if(!empty($depositInfo['is_required']))
                        Cọc trước 50% ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫) để xưởng chuẩn bị hàng. Số tiền {{ number_format($depositInfo['remaining_amount'], 0, ',', '.') }}₫ còn lại thanh toán trực tiếp cho bưu tá.
                      @else
                        Kiểm tra sản phẩm tận tay và thanh toán tiền mặt trực tiếp cho nhân viên bưu tá.
                      @endif
                    </p>
                  </div>
                </div>
                <i data-lucide="banknote" class="w-5 h-5 text-neutral-700 shrink-0 ml-2"></i>
              </div>
              <div class="pay-desc-box mt-3 pt-2.5 border-t border-neutral-200 text-neutral-600 text-[11px]" id="desc_pay_cod">
                @if(!empty($depositInfo['is_required']))
                  <span class="text-amber-700 font-semibold">• Lưu ý:</span> Nhân viên Atelier sẽ liên hệ xác nhận và hướng dẫn chuyển khoản tiền cọc 50% trước khi xử lý đơn hàng.
                @else
                  <span class="text-emerald-700 font-semibold">• Đặc quyền:</span> Quý khách được mở gói hàng đồng kiểm và thử form dáng trang phục trước khi thanh toán.
                @endif
              </div>
            </label>

            <!-- Online Banking / VietQR -->
            <label class="pay-option-card block p-4 border border-neutral-200 rounded-xl cursor-pointer hover:border-neutral-400 transition-all" id="card_pay_online">
              <div class="flex items-center justify-between">
                <div class="flex items-start gap-3">
                  <input type="radio" name="payment_method" id="pay_online" value="online" class="pay-radio mt-0.5 text-neutral-900 focus:ring-neutral-900" onchange="updatePayOptionCards()">
                  <div>
                    <div class="flex items-center gap-2 flex-wrap">
                      <strong class="text-neutral-950 text-sm font-semibold">
                        @if(!empty($depositInfo['is_required']))
                          Chuyển khoản cọc 50% qua VietQR (Techcombank)
                        @else
                          Chuyển khoản Ngân Hàng / Quét mã VietQR 24/7
                        @endif
                      </strong>
                      <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded text-[10px] font-bold">
                        VietQR Techcombank
                      </span>
                    </div>
                    <p class="text-neutral-500 mt-1 text-[11px] leading-relaxed">
                      @if(!empty($depositInfo['is_required']))
                        Quét mã QR qua app ngân hàng để chuyển đúng 50% tiền cọc ({{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫).
                      @else
                        Mở app mọi ngân hàng (Techcombank, Vietcombank, MB...) quét mã xác thực tự động 24/7.
                      @endif
                    </p>
                  </div>
                </div>
                <i data-lucide="qr-code" class="w-5 h-5 text-emerald-700 shrink-0 ml-2"></i>
              </div>
              <div class="pay-desc-box mt-3 pt-2.5 border-t border-neutral-200 text-neutral-600 text-[11px] hidden" id="desc_pay_online">
                Sau khi bấm "Xác Nhận Đặt Hàng", mã QR Techcombank (STK: <strong>77427842310105</strong> - NGUYEN XUAN BAC) sẽ hiển thị với số tiền {{ !empty($depositInfo['is_required']) ? number_format($depositInfo['deposit_amount'], 0, ',', '.') : number_format($total, 0, ',', '.') }}₫ cùng mã đối soát tự động.
              </div>
            </label>

            <!-- MoMo -->
            <label class="pay-option-card block p-4 border border-neutral-200 rounded-xl cursor-pointer hover:border-neutral-400 transition-all" id="card_pay_momo">
              <div class="flex items-center justify-between">
                <div class="flex items-start gap-3">
                  <input type="radio" name="payment_method" id="pay_momo" value="momo" class="pay-radio mt-0.5 text-neutral-900 focus:ring-neutral-900" onchange="updatePayOptionCards()">
                  <div>
                    <div class="flex items-center gap-2 flex-wrap">
                      <strong class="text-neutral-950 text-sm font-semibold">
                        Ví Điện Tử MoMo (Chuyển Hướng Ứng Dụng)
                      </strong>
                      <span class="px-2 py-0.5 bg-pink-100 text-pink-700 rounded text-[10px] font-bold">
                        MOMO App
                      </span>
                    </div>
                    <p class="text-neutral-500 mt-1 text-[11px] leading-relaxed">
                      Hệ thống tự động chuyển tiếp sang ứng dụng MoMo để xác nhận giao dịch an toàn không cần nhập lại số tiền.
                    </p>
                  </div>
                </div>
                <span class="px-2.5 py-1 bg-[#d82d8b] text-white font-bold rounded-md text-[10px] shrink-0 ml-2">MOMO</span>
              </div>
              <div class="pay-desc-box mt-3 pt-2.5 border-t border-neutral-200 text-neutral-600 text-[11px] hidden" id="desc_pay_momo">
                Xác nhận thanh toán số tiền {{ !empty($depositInfo['is_required']) ? number_format($depositInfo['deposit_amount'], 0, ',', '.') : number_format($total, 0, ',', '.') }}₫ qua cổng thanh toán MoMo Official Gateway.
              </div>
            </label>

            <!-- ZaloPay -->
            <label class="pay-option-card block p-4 border border-neutral-200 rounded-xl cursor-pointer hover:border-neutral-400 transition-all" id="card_pay_zalopay">
              <div class="flex items-center justify-between">
                <div class="flex items-start gap-3">
                  <input type="radio" name="payment_method" id="pay_zalopay" value="zalopay" class="pay-radio mt-0.5 text-neutral-900 focus:ring-neutral-900" onchange="updatePayOptionCards()">
                  <div>
                    <div class="flex items-center gap-2 flex-wrap">
                      <strong class="text-neutral-950 text-sm font-semibold">
                        Ví Điện Tử ZaloPay Gateway
                      </strong>
                      <span class="px-2 py-0.5 bg-sky-100 text-sky-700 rounded text-[10px] font-bold">
                        ZaloPay
                      </span>
                    </div>
                    <p class="text-neutral-500 mt-1 text-[11px] leading-relaxed">
                      Thanh toán tiện lợi qua ví ZaloPay hoặc quét mã trực tiếp trên ứng dụng chat Zalo.
                    </p>
                  </div>
                </div>
                <span class="px-2.5 py-1 bg-[#008fe5] text-white font-bold rounded-md text-[10px] shrink-0 ml-2">ZaloPay</span>
              </div>
              <div class="pay-desc-box mt-3 pt-2.5 border-t border-neutral-200 text-neutral-600 text-[11px] hidden" id="desc_pay_zalopay">
                Chuyển tiếp bảo mật sang cổng ZaloPay Gateway để thanh toán đơn hàng.
              </div>
            </label>

          </div>
        </div>

        <!-- Submit Button for Mobile Screens -->
        <div class="block lg:hidden pt-2">
          <button type="submit" class="w-full py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.25em] uppercase rounded-xl shadow-xl transition-all flex items-center justify-center gap-2">
            <i data-lucide="lock" class="w-4 h-4"></i>
            <span>{{ !empty($depositInfo['is_required']) ? 'Xác Nhận Đặt Cọc 50% & Đặt Hàng' : 'Xác Nhận Đặt Hàng An Toàn' }}</span>
          </button>
        </div>

      </div>

      <!-- Right Column: Order Summary & Voucher (5 cols) -->
      <div class="lg:col-span-5 bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm sticky top-28 space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-neutral-100">
          <h3 class="font-serif-luxury text-xl font-bold text-neutral-900">Tóm Tắt Đơn Hàng</h3>
          <span class="text-xs text-neutral-500">{{ $cartCount ?? count($cartItems) }} sản phẩm</span>
        </div>

        <!-- Items List -->
        <div class="space-y-4 max-h-72 overflow-y-auto pr-1">
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

        <!-- Khối Áp Dụng Voucher Tại Checkout -->
        <div class="p-3.5 rounded-xl border border-amber-200 bg-amber-50/50 text-xs">
          <div class="flex justify-between items-center mb-2">
            <span class="font-bold text-neutral-900 uppercase text-[11px] flex items-center gap-1.5">
              <i data-lucide="ticket" class="w-3.5 h-3.5 text-amber-700"></i> Mã Giảm Giá BeeStyle
            </span>
            @if(isset($coupons) && $coupons->count() > 0)
              <button type="button" onclick="openVoucherModal()" class="text-rose-600 hover:underline text-[11px] font-semibold flex items-center gap-0.5">
                Xem mã ({{ $coupons->count() }}) <i data-lucide="chevron-right" class="w-3 h-3"></i>
              </button>
            @endif
          </div>

          @if(!empty($appliedCoupon))
            <div class="p-2.5 bg-white rounded-lg border border-amber-300 flex justify-between items-center">
              <div>
                <div class="flex items-center gap-1.5">
                  <span class="px-2 py-0.5 bg-rose-600 text-white font-mono font-bold text-[11px] rounded">{{ $appliedCoupon->code }}</span>
                  <span class="font-semibold text-neutral-900 text-xs">{{ $appliedCoupon->title }}</span>
                </div>
                <span class="text-[11px] text-emerald-700 font-semibold block mt-0.5">
                  ✓ Giảm {{ number_format($discount ?? 0, 0, ',', '.') }}₫
                </span>
              </div>
              <button type="button" onclick="removeCheckoutCoupon()" class="text-rose-600 hover:text-rose-800 text-[11px] font-semibold px-2 py-1 rounded hover:bg-rose-50 transition-colors">
                Bỏ mã
              </button>
            </div>
          @else
            <div class="flex gap-2">
              <input type="text" id="manualCheckoutCouponInput" placeholder="Nhập mã voucher..." class="w-full bg-white border border-neutral-300 rounded-lg px-3 py-2 text-xs uppercase font-mono focus:outline-none focus:border-neutral-950">
              <button type="button" onclick="applyManualCheckoutCoupon()" class="px-3.5 py-2 bg-neutral-950 text-white font-semibold text-xs rounded-lg hover:bg-neutral-800 uppercase tracking-wider transition-colors shrink-0">
                Áp Dụng
              </button>
            </div>
          @endif
        </div>

        <!-- Price Breakdown -->
        <div class="space-y-2.5 text-xs text-neutral-600 pb-4 border-b border-neutral-200">
          <div class="flex justify-between">
            <span>Tạm tính giỏ hàng:</span>
            <span class="font-semibold text-neutral-900">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
          </div>
          <div class="flex justify-between">
            <span>Phí vận chuyển:</span>
            @if(($shipping ?? 0) == 0)
              <span class="font-semibold text-emerald-700">MIỄN PHÍ</span>
            @else
              <span class="font-semibold text-neutral-900">{{ number_format($shipping, 0, ',', '.') }}₫</span>
            @endif
          </div>
          @if(isset($discount) && $discount > 0)
            <div class="flex justify-between text-rose-700 font-semibold">
              <span>Ưu đãi giảm giá (Coupon):</span>
              <span>-{{ number_format($discount, 0, ',', '.') }}₫</span>
            </div>
          @endif
        </div>

        <!-- Chi tiết Cọc hoặc Tổng Thanh Toán -->
        @if(!empty($depositInfo['is_required']))
          <div class="p-3.5 rounded-xl border border-amber-300 bg-amber-50 text-xs space-y-2">
            <div class="flex justify-between items-center">
              <span class="font-bold text-neutral-900">Số tiền đặt cọc trước (50%):</span>
              <strong class="text-rose-600 font-mono text-base">{{ number_format($depositInfo['deposit_amount'], 0, ',', '.') }}₫</strong>
            </div>
            <div class="flex justify-between items-center text-neutral-600">
              <span>Thu COD khi nhận hàng (50%):</span>
              <strong class="text-neutral-900 font-mono">{{ number_format($depositInfo['remaining_amount'], 0, ',', '.') }}₫</strong>
            </div>
            <div class="pt-2 border-t border-amber-200 flex justify-between items-baseline text-neutral-500 text-[11px]">
              <span>Tổng giá trị đơn hàng:</span>
              <span class="font-semibold text-neutral-900">{{ number_format($total, 0, ',', '.') }}₫</span>
            </div>
          </div>
        @else
          <div class="flex justify-between items-baseline">
            <span class="text-xs uppercase font-bold tracking-wider text-neutral-900">Tổng Thanh Toán:</span>
            <div class="text-right">
              <span class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-950 block">
                {{ number_format($total, 0, ',', '.') }}₫
              </span>
              <span class="text-[10px] text-neutral-400">Đã gồm VAT &amp; Phí đóng gói Atelier</span>
            </div>
          </div>
        @endif

        <!-- Desktop Submit Button -->
        <button type="submit" class="hidden lg:flex w-full py-4 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-[0.25em] uppercase rounded-xl shadow-xl transition-all items-center justify-center gap-2">
          <i data-lucide="lock" class="w-4 h-4"></i>
          <span>{{ !empty($depositInfo['is_required']) ? 'Xác Nhận Đặt Cọc 50% & Đặt Hàng' : 'Xác Nhận Đặt Hàng An Toàn' }}</span>
        </button>

        <!-- Guarantees Badge -->
        <div class="p-3.5 bg-brand-50 rounded-xl border border-brand-200 text-[11px] text-neutral-600 space-y-1.5">
          <div class="flex items-center gap-2 text-neutral-800 font-semibold">
            <i data-lucide="shield-check" class="w-4 h-4 text-amber-800"></i>
            <span>ĐẶC QUYỀN KHÁCH HÀNG BEESTYLE:</span>
          </div>
          <p>• Cam kết 100% may đo thủ công chuẩn Atelier.</p>
          <p>• Đồng kiểm tra sản phẩm tận tay trước khi thanh toán.</p>
          <p>• Miễn phí đổi size trong 30 ngày tại nhà.</p>
        </div>

      </div>

    </div>
  </form>

</main>

<!-- Modal Chọn Voucher -->
<div id="checkoutVoucherModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
  <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden animate-fade-in">
    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
      <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 flex items-center gap-2">
        <i data-lucide="ticket" class="w-5 h-5 text-amber-600"></i>
        <span>Kho Mã Giảm Giá BeeStyle</span>
      </h3>
      <button type="button" onclick="closeVoucherModal()" class="text-neutral-400 hover:text-neutral-900">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <div class="p-5 space-y-4">
      <div class="flex gap-2">
        <input type="text" id="modalCouponInput" placeholder="Nhập mã voucher cá nhân..." class="w-full bg-neutral-50 border border-neutral-300 rounded-lg px-3 py-2 text-xs uppercase font-mono focus:outline-none focus:border-neutral-950">
        <button type="button" onclick="applyFromModalInput()" class="px-4 py-2 bg-neutral-950 text-white font-semibold text-xs rounded-lg hover:bg-neutral-800 uppercase tracking-wider transition-colors shrink-0">
          Áp Dụng
        </button>
      </div>

      <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
        @if(isset($coupons) && $coupons->count() > 0)
          @foreach($coupons as $cp)
            @php
              $isEligible = ($subtotal >= $cp->min_order_value);
              $isCurrentlyUsing = (!empty($appliedCoupon) && strcasecmp($appliedCoupon->code, $cp->code) === 0);
            @endphp
            <div class="p-3.5 rounded-xl border {{ $isCurrentlyUsing ? 'border-amber-500 bg-amber-50/60' : ($isEligible ? 'border-neutral-200 bg-white hover:border-neutral-400' : 'border-neutral-200 bg-neutral-50 opacity-60') }} flex items-center justify-between gap-3 text-xs transition-all">
              <div class="min-w-0">
                <div class="flex items-center gap-2 mb-1">
                  <span class="px-2 py-0.5 {{ $isEligible ? 'bg-rose-600 text-white' : 'bg-neutral-400 text-white' }} font-mono font-bold rounded text-[11px]">{{ $cp->code }}</span>
                  <span class="px-1.5 py-0.5 bg-amber-100 text-amber-900 font-semibold rounded text-[10px]">
                    {{ $cp->discount_type === 'percent' ? 'Giảm ' . $cp->discount_value . '%' : ($cp->discount_type === 'shipping' ? 'Freeship' : 'Giảm ' . number_format($cp->discount_value, 0, ',', '.') . '₫') }}
                  </span>
                </div>
                <strong class="text-neutral-900 block font-semibold text-xs">{{ $cp->title }}</strong>
                <span class="text-neutral-500 text-[11px] block mt-0.5">
                  Đơn tối thiểu: <strong>{{ number_format($cp->min_order_value, 0, ',', '.') }}₫</strong>
                  @if($cp->expires_at) • HSD: {{ $cp->expires_at->format('d/m/Y') }} @endif
                </span>
                @if(!$isEligible)
                  <span class="text-rose-600 text-[10px] block mt-1">
                    Cần mua thêm {{ number_format($cp->min_order_value - $subtotal, 0, ',', '.') }}₫ để dùng mã này
                  </span>
                @endif
              </div>

              <div class="shrink-0">
                @if($isCurrentlyUsing)
                  <button type="button" onclick="removeCheckoutCoupon()" class="px-3 py-1.5 border border-rose-600 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-semibold">
                    Đang Dùng (Bỏ)
                  </button>
                @elseif($isEligible)
                  <button type="button" onclick="executeApplyCheckoutCoupon('{{ $cp->code }}')" class="px-3.5 py-1.5 bg-neutral-950 hover:bg-neutral-800 text-white rounded-lg text-xs font-semibold">
                    Áp Dụng
                  </button>
                @else
                  <button type="button" disabled class="px-3 py-1.5 bg-neutral-200 text-neutral-400 rounded-lg text-xs font-semibold cursor-not-allowed">
                    Chưa Đủ ĐK
                  </button>
                @endif
              </div>
            </div>
          @endforeach
        @else
          <p class="text-center text-xs text-neutral-400 py-6">Hiện tại chưa có mã giảm giá nào khả dụng.</p>
        @endif
      </div>
    </div>
  </div>
</div>
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

  function updatePayOptionCards() {
    const radios = document.querySelectorAll('.pay-radio');
    radios.forEach(radio => {
      const card = document.getElementById('card_' + radio.id);
      const desc = document.getElementById('desc_' + radio.id);

      if (radio.checked) {
        if (card) {
          card.classList.add('border-neutral-950', 'bg-neutral-50');
          card.classList.remove('border-neutral-200');
        }
        if (desc) desc.classList.remove('hidden');
      } else {
        if (card) {
          card.classList.remove('border-neutral-950', 'bg-neutral-50');
          card.classList.add('border-neutral-200');
        }
        if (desc) desc.classList.add('hidden');
      }
    });
  }

  function openVoucherModal() {
    const modal = document.getElementById('checkoutVoucherModal');
    if (modal) modal.classList.remove('hidden');
  }

  function closeVoucherModal() {
    const modal = document.getElementById('checkoutVoucherModal');
    if (modal) modal.classList.add('hidden');
  }

  function applyManualCheckoutCoupon() {
    const input = document.getElementById('manualCheckoutCouponInput');
    if (!input || !input.value.trim()) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'warning', title: 'Nhắc nhở', text: 'Vui lòng nhập mã giảm giá!' });
      } else {
        alert('Vui lòng nhập mã giảm giá!');
      }
      return;
    }
    executeApplyCheckoutCoupon(input.value.trim());
  }

  function applyFromModalInput() {
    const input = document.getElementById('modalCouponInput');
    if (!input || !input.value.trim()) {
      if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'warning', title: 'Nhắc nhở', text: 'Vui lòng nhập mã voucher!' });
      } else {
        alert('Vui lòng nhập mã voucher!');
      }
      return;
    }
    executeApplyCheckoutCoupon(input.value.trim());
  }

  function executeApplyCheckoutCoupon(code) {
    closeVoucherModal();

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Đang áp dụng voucher...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }

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
      if (data.success) {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Áp Dụng Thành Công!',
            text: data.message,
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            window.location.reload();
          });
        } else {
          window.location.reload();
        }
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'error',
            title: 'Không thể áp dụng',
            text: data.message || 'Mã giảm giá không hợp lệ.'
          });
        } else {
          alert(data.message || 'Mã giảm giá không hợp lệ.');
        }
      }
    })
    .catch(err => {
      console.error('Error applying coupon:', err);
      window.location.reload();
    });
  }

  function removeCheckoutCoupon() {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Đang hủy voucher...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });
    }

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
      if (typeof Swal !== 'undefined') {
        Swal.fire({
          icon: 'info',
          title: 'Đã hủy voucher',
          text: data.message || 'Đã gỡ mã giảm giá.',
          timer: 1200,
          showConfirmButton: false
        }).then(() => {
          window.location.reload();
        });
      } else {
        window.location.reload();
      }
    })
    .catch(err => {
      console.error('Error removing coupon:', err);
      window.location.reload();
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    updatePayOptionCards();
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });
</script>
@endpush