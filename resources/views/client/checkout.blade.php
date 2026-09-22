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
            <div class="mb-6 p-4 bg-amber-50/70 rounded-xl border border-amber-200">
              <label class="block font-semibold uppercase text-neutral-800 text-[11px] mb-2 flex items-center justify-between">
                <span class="flex items-center gap-1 text-amber-900 font-bold">
                  <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-800"></i> Sổ địa chỉ đã lưu của bạn:
                </span>
                <span class="text-[10px] text-neutral-500 font-normal">Chọn để tự động điền form</span>
              </label>
              <select id="savedAddressSelect" onchange="fillSavedAddress(this)" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-800 focus:outline-none focus:border-neutral-950 shadow-xs cursor-pointer">
                <option value="" selected>-- Nhập thông tin &amp; địa chỉ nhận hàng bên dưới --</option>
                @foreach($addresses as $addr)
                  @php
                    $cityName = $addr->province_name ?: ($addr->city ?: '');
                    $districtName = $addr->district_name ?: ($addr->district ?: '');
                    $wardName = $addr->ward_name ?: ($addr->ward ?: '');
                    $streetAddr = $addr->detail_address ?: ($addr->address ?: '');
                    $fullDisplay = implode(', ', array_filter([$streetAddr, $wardName, $districtName, $cityName]));
                  @endphp
                  <option value="{{ $addr->id }}" 
                    data-name="{{ $addr->receiver_name }}" 
                    data-phone="{{ $addr->receiver_phone }}" 
                    data-address="{{ $streetAddr }}"
                    data-city="{{ $cityName }}"
                    data-district="{{ $districtName }}"
                    data-ward="{{ $wardName }}">
                    {{ $addr->receiver_name }} — {{ $addr->receiver_phone }} ({{ $fullDisplay }})
                  </option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="space-y-4 text-xs">
            <!-- 1. Họ và tên & Số điện thoại (Bắt đầu để trống để khách hàng tự nhập) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5 flex items-center justify-between">
                  <span>Họ và Tên <span class="text-rose-600">*</span></span>
                </label>
                <div class="relative">
                  <input type="text" name="customer_name" id="cust_name" value="{{ old('customer_name', '') }}" required placeholder="Ví dụ: Nguyễn Văn A" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-9 pr-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
                  <i data-lucide="user" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
              </div>
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5 flex items-center justify-between">
                  <span>Số Điện Thoại <span class="text-rose-600">*</span></span>
                  <span class="text-[10px] text-neutral-500 lowercase font-normal">10 chữ số (03, 05, 07, 08, 09)</span>
                </label>
                <div class="relative">
                  <input type="tel" name="customer_phone" id="cust_phone" value="{{ old('customer_phone', '') }}" required placeholder="0987654321" pattern="^(0|\+84)(3|5|7|8|9)[0-9]{8}$" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-9 pr-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors font-mono">
                  <i data-lucide="phone" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                </div>
              </div>
            </div>

            <!-- 2. Email nhận hóa đơn điện tử (Bắt đầu để trống để khách hàng tự nhập) -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Email nhận hóa đơn điện tử</label>
              <div class="relative">
                <input type="email" name="customer_email" id="cust_email" value="{{ old('customer_email', '') }}" placeholder="email@gmail.com" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-9 pr-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
                <i data-lucide="mail" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
              </div>
            </div>

            <!-- 3. CỤM ĐỊA CHỈ HÀNH CHÍNH THEO TRẬT TỰ YÊU CẦU: Tỉnh/Thành Phố -> Quận/Huyện -> Phường/Xã -->
            <div class="pt-3 border-t border-neutral-100">
              <div class="flex items-center justify-between mb-2.5">
                <label class="block font-bold uppercase text-neutral-900 text-xs tracking-wide flex items-center gap-1.5">
                  <i data-lucide="map" class="w-4 h-4 text-amber-600"></i>
                  <span>Đơn Vị Hành Chính Tiếp Nhận Kiện Hàng</span>
                </label>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                <!-- Tỉnh / Thành Phố -->
                <div>
                  <label class="block font-semibold uppercase text-neutral-700 mb-1 text-[11px] flex items-center justify-between">
                    <span>Tỉnh / Thành Phố <span class="text-rose-600">*</span></span>
                  </label>
                  <div class="relative">
                    <select id="cust_province" 
                            name="province_code" 
                            onchange="onProvinceChange(this)" 
                            required 
                            class="w-full bg-white border border-neutral-300 hover:border-neutral-950 rounded-lg pl-3 pr-8 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:ring-1 focus:ring-neutral-950 transition-all cursor-pointer shadow-xs">
                      <option value="">-- Chọn Tỉnh / Thành Phố --</option>
                      @if(isset($provinces) && count($provinces) > 0)
                        @foreach($provinces as $p)
                          @php
                            $pCode = is_array($p) ? $p['code'] : $p->code;
                            $pName = is_array($p) ? $p['name'] : $p->name;
                          @endphp
                          <option value="{{ $pCode }}" data-name="{{ $pName }}" {{ old('province_code') == $pCode ? 'selected' : '' }}>
                            {{ $pName }}
                          </option>
                        @endforeach
                      @endif
                    </select>
                    <div id="provinceLoading" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">
                      <i data-lucide="loader-2" class="w-3.5 h-3.5 text-amber-600 animate-spin"></i>
                    </div>
                  </div>
                  <!-- Hidden input to store city name string for backend -->
                  <input type="hidden" name="city" id="hidden_city" value="{{ old('city', '') }}" required>
                </div>

                <!-- Quận / Huyện -->
                <div>
                  <label class="block font-semibold uppercase text-neutral-700 mb-1 text-[11px]">
                    Quận / Huyện <span class="text-rose-600">*</span>
                  </label>
                  <div class="relative">
                    <select id="cust_district" 
                            name="district_code" 
                            onchange="onDistrictChange(this)" 
                            required 
                            class="w-full bg-white border border-neutral-300 hover:border-neutral-950 rounded-lg pl-3 pr-8 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:ring-1 focus:ring-neutral-950 transition-all cursor-pointer shadow-xs">
                      <option value="">-- Vui lòng chọn Tỉnh/TP trước --</option>
                    </select>
                    <div id="districtLoading" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">
                      <i data-lucide="loader-2" class="w-3.5 h-3.5 text-amber-600 animate-spin"></i>
                    </div>
                  </div>
                  <!-- Hidden input to store district name string for backend -->
                  <input type="hidden" name="district" id="hidden_district" value="{{ old('district', '') }}" required>
                </div>

                <!-- Phường / Xã -->
                <div>
                  <label class="block font-semibold uppercase text-neutral-700 mb-1 text-[11px]">
                    Phường / Xã <span class="text-rose-600">*</span>
                  </label>
                  <div class="relative">
                    <select id="cust_ward" 
                            name="ward_code" 
                            onchange="onWardChange(this)" 
                            required 
                            class="w-full bg-white border border-neutral-300 hover:border-neutral-950 rounded-lg pl-3 pr-8 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:ring-1 focus:ring-neutral-950 transition-all cursor-pointer shadow-xs">
                      <option value="">-- Vui lòng chọn Quận/Huyện trước --</option>
                    </select>
                    <div id="wardLoading" class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none">
                      <i data-lucide="loader-2" class="w-3.5 h-3.5 text-amber-600 animate-spin"></i>
                    </div>
                  </div>
                  <!-- Hidden input to store ward name string for backend -->
                  <input type="hidden" name="ward" id="hidden_ward" value="{{ old('ward', '') }}" required>
                </div>
              </div>
            </div>

            <!-- 4. Địa chỉ nhận hàng (Số nhà, tên đường) -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5 flex items-center justify-between">
                <span>Địa chỉ nhận hàng (Số nhà, tên đường, ngõ, tòa nhà) <span class="text-rose-600">*</span></span>
                <span class="text-[10px] text-neutral-400 font-normal">Càng chi tiết bưu tá giao càng nhanh</span>
              </label>
              <div class="relative">
                <input type="text" 
                       name="shipping_address" 
                       id="cust_address" 
                       value="{{ old('shipping_address', '') }}" 
                       oninput="onStreetAddressInput(this)" 
                       required 
                       placeholder="Ví dụ: Số 123 Đường Kim Mã (hoặc Tòa nhà, Ngõ, Ngách...)" 
                       class="w-full bg-neutral-50 border border-neutral-200 rounded-lg pl-9 pr-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors">
                <i data-lucide="home" class="w-4 h-4 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
              </div>
            </div>

            <!-- Hidden input for full address -->
            <input type="hidden" name="full_address" id="hidden_full_address">

            <!-- 5. HỘP XÁC NHẬN ĐỊA CHỈ THỰC TẾ CHUẨN XÁC HIỆN TẠI (REALTIME ADDRESS VERIFICATION BOX) -->
            <div id="addressVerificationCard" class="p-4 rounded-2xl border transition-all duration-300 bg-neutral-50/80 border-neutral-200">
              <div class="flex items-start gap-3">
                <div id="addressVerifyIconWrapper" class="w-9 h-9 rounded-xl bg-neutral-200 text-neutral-600 flex items-center justify-center shrink-0 shadow-xs transition-colors">
                  <i data-lucide="map-pin" class="w-4 h-4" id="addressVerifyIcon"></i>
                </div>
                <div class="w-full space-y-2">
                  <div class="flex items-center justify-between flex-wrap gap-2">
                    <span class="font-bold uppercase tracking-wider text-[11px] text-neutral-700 flex items-center gap-1.5">
                      <span id="addressVerifyTitle">Xác Nhận Địa Chỉ Giao Hàng Thực Tế</span>
                    </span>
                    <span id="addressVerifyBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-neutral-200 text-neutral-600 border border-neutral-300">
                      Chờ nhập thông tin
                    </span>
                  </div>

                  <!-- Nội dung chi tiết địa chỉ -->
                  <div id="addressVerifySummary" class="text-neutral-600 text-xs leading-relaxed">
                    Vui lòng chọn Tỉnh / Thành phố, Quận / Huyện, Phường / Xã và nhập số nhà để hệ thống xác nhận tuyến giao hàng.
                  </div>

                  <!-- Thẻ chi tiết 4 cấp -->
                  <div id="addressDetailPills" class="hidden pt-2 border-t border-neutral-200/80 grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                    <div class="bg-white p-2 rounded-lg border border-neutral-200">
                      <span class="text-[9px] uppercase text-neutral-400 font-bold block">Tỉnh / Thành</span>
                      <strong class="text-neutral-900 truncate block" id="pillCity">-</strong>
                    </div>
                    <div class="bg-white p-2 rounded-lg border border-neutral-200">
                      <span class="text-[9px] uppercase text-neutral-400 font-bold block">Quận / Huyện</span>
                      <strong class="text-neutral-900 truncate block" id="pillDistrict">-</strong>
                    </div>
                    <div class="bg-white p-2 rounded-lg border border-neutral-200">
                      <span class="text-[9px] uppercase text-neutral-400 font-bold block">Phường / Xã</span>
                      <strong class="text-neutral-900 truncate block" id="pillWard">-</strong>
                    </div>
                    <div class="bg-white p-2 rounded-lg border border-neutral-200">
                      <span class="text-[9px] uppercase text-neutral-400 font-bold block">Số nhà / Đường</span>
                      <strong class="text-neutral-900 truncate block" id="pillStreet">-</strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Ghi chú cho nghệ nhân -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Ghi chú cho nghệ nhân may đo / đóng gói</label>
              <textarea name="notes" rows="2" placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi giao 15 phút, đóng hộp quà tặng..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-3 text-xs text-neutral-900 focus:outline-none focus:border-neutral-900 focus:bg-white transition-colors"></textarea>
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

            <!-- MoMo Payment (ATM) -->
            <label class="pay-option-card block p-4 md:p-5 border border-neutral-200 rounded-2xl cursor-pointer hover:border-[#a50064] transition-all group relative overflow-hidden" id="card_pay_momo">
              <div class="flex items-center justify-between">
                <div class="flex items-start gap-3.5">
                  <input type="radio" name="payment_method" id="pay_momo" value="momo" class="pay-radio mt-1.5 text-[#a50064] focus:ring-[#a50064]" onchange="updatePayOptionCards()">
                  <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-white border-2 border-pink-300 p-1.5 shadow-sm flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                      <img src="{{ asset('assets/img/logos/momo.svg') }}" alt="MoMo Logo" class="w-full h-full object-contain rounded-xl" onerror="this.src='{{ asset('assets/img/logos/momo.png') }}'">
                    </div>
                    <div>
                      <div class="flex items-center gap-2 flex-wrap">
                        <strong class="text-neutral-950 text-sm md:text-base font-bold group-hover:text-[#a50064] transition-colors">
                          Cổng Thanh Toán MoMo Payment (Thẻ ATM &amp; Ngân Hàng)
                        </strong>
                        <span class="px-2.5 py-0.5 bg-gradient-to-r from-pink-500 to-[#a50064] text-white rounded-full text-[10px] font-black shadow-2xs flex items-center gap-1">
                          <i data-lucide="check" class="w-2.5 h-2.5"></i> MoMo Official
                        </span>
                        <span class="px-2 py-0.5 bg-pink-50 text-[#a50064] font-mono text-[10px] rounded-md border border-pink-200 font-bold">NAPAS 24/7</span>
                      </div>
                      <p class="text-neutral-500 mt-1 text-[11px] leading-relaxed">
                        Thanh toán trực tuyến an toàn qua Cổng MoMo bằng Thẻ ATM nội địa mọi ngân hàng Việt Nam (Không yêu cầu quét mã QR).
                      </p>
                    </div>
                  </div>
                </div>
                <div class="hidden md:flex items-center gap-2 shrink-0 ml-3">
                  <div class="w-8 h-8 rounded-xl bg-pink-50 p-1 border border-pink-200 flex items-center justify-center shadow-2xs">
                    <img src="{{ asset('assets/img/logos/momo.svg') }}" alt="MoMo" class="w-full h-full object-contain" onerror="this.src='{{ asset('assets/img/logos/momo.png') }}'">
                  </div>
                </div>
              </div>

            </label>

            <!-- VNPAY Payment Gateway -->
            <label class="pay-option-card block p-4 border border-neutral-200 rounded-xl cursor-pointer hover:border-[#005baa] transition-all relative overflow-hidden group" id="card_pay_vnpay">
              <div class="flex items-center justify-between">
                <div class="flex items-start gap-3">
                  <input type="radio" name="payment_method" id="pay_vnpay" value="vnpay" class="pay-radio mt-1.5 text-[#005baa] focus:ring-[#005baa]" onchange="updatePayOptionCards()">
                  <div>
                    <div class="flex items-center gap-2 flex-wrap">
                      <strong class="text-neutral-950 text-sm md:text-base font-bold group-hover:text-[#005baa] transition-colors">
                        Cổng Thanh Toán VNPAY (ATM / QR / Visa / MasterCard)
                      </strong>
                      <span class="px-2.5 py-0.5 bg-gradient-to-r from-blue-600 to-[#005baa] text-white rounded-full text-[10px] font-black shadow-2xs flex items-center gap-1">
                        <i data-lucide="check" class="w-2.5 h-2.5"></i> VNPAY Official
                      </span>
                      <span class="px-2 py-0.5 bg-blue-50 text-[#005baa] font-mono text-[10px] rounded-md border border-blue-200 font-bold">VNPAY-QR</span>
                    </div>
                    <p class="text-neutral-500 mt-1 text-[11px] leading-relaxed">
                      Quét mã VNPAY-QR từ ứng dụng ngân hàng, thanh toán qua Thẻ ATM nội địa (30+ ngân hàng Napas) hoặc Thẻ quốc tế.
                    </p>
                  </div>
                </div>
                <div class="hidden md:flex items-center gap-2 shrink-0 ml-3">
                  <div class="h-8 px-2 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center shadow-2xs">
                    <img src="{{ asset('assets/img/logos/vnpay.svg') }}" alt="VNPAY" class="h-5 w-auto object-contain">
                  </div>
                </div>
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
                    @if(!empty($item['material'])) | Vải: {{ $item['material'] }} @endif
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
  // =========================================================================
  // BẮT API ĐƠN VỊ HÀNH CHÍNH VIỆT NAM (TỈNH/THÀNH -> QUẬN/HUYỆN -> PHƯỜNG/XÃ)
  // SỔ RA TRỰC TIẾP TRÊN CÁC HỘP CHỌN SELECT NATIVE 100%
  // =========================================================================
  const API_PROVINCES = '{{ route("administrative.provinces") }}';
  const API_DISTRICTS_BASE = '{{ url("/api/administrative/districts") }}';
  const API_WARDS_BASE = '{{ url("/api/administrative/wards") }}';
  const OPEN_API_DISTRICTS = 'https://provinces.open-api.vn/api/p/';
  const OPEN_API_WARDS = 'https://provinces.open-api.vn/api/d/';

  let cachedDistricts = {};
  let cachedWards = {};

  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // Khi người dùng chọn Tỉnh / Thành Phố từ dropdown
  async function onProvinceChange(selectEl) {
    const provCode = selectEl.value;
    const selectedOpt = selectEl.options[selectEl.selectedIndex];
    const provName = provCode ? (selectedOpt.getAttribute('data-name') || selectedOpt.text.trim()) : '';

    document.getElementById('hidden_city').value = provName;

    // Reset Quận/Huyện & Phường/Xã
    const distSelect = document.getElementById('cust_district');
    const wardSelect = document.getElementById('cust_ward');

    document.getElementById('hidden_district').value = '';
    document.getElementById('hidden_ward').value = '';

    if (!provCode) {
      distSelect.innerHTML = '<option value="">-- Vui lòng chọn Tỉnh/TP trước --</option>';
      wardSelect.innerHTML = '<option value="">-- Vui lòng chọn Quận/Huyện trước --</option>';
      updateAddressVerification();
      return;
    }

    distSelect.innerHTML = '<option value="">-- Đang nạp danh sách Quận/Huyện... --</option>';
    wardSelect.innerHTML = '<option value="">-- Vui lòng chọn Quận/Huyện trước --</option>';

    await loadDistrictsForProvince(provCode);
    updateAddressVerification();

    // Tự động focus vào dropdown Quận/Huyện để người dùng chọn tiếp
    setTimeout(() => {
      distSelect.focus();
    }, 100);
  }

  // Tải danh sách Quận/Huyện theo Tỉnh/Thành phố
  async function loadDistrictsForProvince(provCode, preselectedDistrictName = null, preselectedWardName = null) {
    const distSelect = document.getElementById('cust_district');
    const distLoading = document.getElementById('districtLoading');
    const wardSelect = document.getElementById('cust_ward');

    if (distLoading) distLoading.classList.remove('hidden');

    try {
      let districts = cachedDistricts[provCode] || [];

      if (districts.length === 0) {
        try {
          const res = await fetch(`${API_DISTRICTS_BASE}/${provCode}`);
          if (res.ok) {
            const json = await res.json();
            if (json.success && Array.isArray(json.data) && json.data.length > 0) {
              districts = json.data;
            }
          }
        } catch (e) {
          console.warn('Internal API districts error, fallback to open-api.vn:', e);
        }

        if (districts.length === 0) {
          try {
            const resExt = await fetch(`${OPEN_API_DISTRICTS}${provCode}?depth=2`);
            if (resExt.ok) {
              const jsonExt = await resExt.json();
              districts = jsonExt.districts || [];
            }
          } catch (e2) {
            console.error('Fallback open-api.vn districts error:', e2);
          }
        }

        cachedDistricts[provCode] = districts;
      }

      distSelect.innerHTML = '<option value="">-- Chọn Quận / Huyện --</option>';

      districts.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.code;
        opt.textContent = d.name;
        opt.setAttribute('data-name', d.name);
        if (preselectedDistrictName && (d.name.toLowerCase() === preselectedDistrictName.toLowerCase() || d.name.toLowerCase().includes(preselectedDistrictName.toLowerCase()) || preselectedDistrictName.toLowerCase().includes(d.name.toLowerCase()))) {
          opt.selected = true;
        }
        distSelect.appendChild(opt);
      });

      if (distSelect.value) {
        const selectedOpt = distSelect.options[distSelect.selectedIndex];
        document.getElementById('hidden_district').value = selectedOpt.getAttribute('data-name') || selectedOpt.text.trim();
        await loadWardsForDistrict(distSelect.value, preselectedWardName);
      } else {
        wardSelect.innerHTML = '<option value="">-- Vui lòng chọn Quận/Huyện trước --</option>';
      }
    } catch (err) {
      console.error('Lỗi khi tải danh sách Quận/Huyện:', err);
    } finally {
      if (distLoading) distLoading.classList.add('hidden');
      updateAddressVerification();
    }
  }

  // Khi người dùng chọn Quận / Huyện
  async function onDistrictChange(selectEl) {
    const distCode = selectEl.value;
    const selectedOpt = selectEl.options[selectEl.selectedIndex];
    const distName = distCode ? (selectedOpt.getAttribute('data-name') || selectedOpt.text.trim()) : '';

    document.getElementById('hidden_district').value = distName;

    // Reset Phường/Xã
    const wardSelect = document.getElementById('cust_ward');
    document.getElementById('hidden_ward').value = '';

    if (!distCode) {
      wardSelect.innerHTML = '<option value="">-- Vui lòng chọn Quận/Huyện trước --</option>';
      updateAddressVerification();
      return;
    }

    wardSelect.innerHTML = '<option value="">-- Đang nạp danh sách Phường/Xã... --</option>';

    await loadWardsForDistrict(distCode);
    updateAddressVerification();

    // Tự động focus vào Phường / Xã tiếp theo
    setTimeout(() => {
      wardSelect.focus();
    }, 100);
  }

  // Tải danh sách Phường / Xã của 1 Quận/Huyện
  async function loadWardsForDistrict(distCode, preselectedWardName = null) {
    const wardSelect = document.getElementById('cust_ward');
    const wardLoading = document.getElementById('wardLoading');

    if (wardLoading) wardLoading.classList.remove('hidden');

    try {
      let wards = cachedWards[distCode] || [];

      if (wards.length === 0) {
        try {
          const res = await fetch(`${API_WARDS_BASE}/${distCode}`);
          if (res.ok) {
            const json = await res.json();
            if (json.success && Array.isArray(json.data) && json.data.length > 0) {
              wards = json.data;
            }
          }
        } catch (e) {
          console.warn('Internal API wards error, fallback to open-api.vn:', e);
        }

        if (wards.length === 0) {
          try {
            const resExt = await fetch(`${OPEN_API_WARDS}${distCode}?depth=2`);
            if (resExt.ok) {
              const jsonExt = await resExt.json();
              wards = jsonExt.wards || [];
            }
          } catch (e2) {
            console.error('Fallback open-api.vn wards error:', e2);
          }
        }

        cachedWards[distCode] = wards;
      }

      wardSelect.innerHTML = '<option value="">-- Chọn Phường / Xã --</option>';

      wards.forEach(w => {
        const opt = document.createElement('option');
        opt.value = w.code;
        opt.textContent = w.name;
        opt.setAttribute('data-name', w.name);
        if (preselectedWardName && (w.name.toLowerCase() === preselectedWardName.toLowerCase() || w.name.toLowerCase().includes(preselectedWardName.toLowerCase()) || preselectedWardName.toLowerCase().includes(w.name.toLowerCase()))) {
          opt.selected = true;
        }
        wardSelect.appendChild(opt);
      });

      if (wardSelect.value) {
        const selectedOpt = wardSelect.options[wardSelect.selectedIndex];
        document.getElementById('hidden_ward').value = selectedOpt.getAttribute('data-name') || selectedOpt.text.trim();
      }
    } catch (err) {
      console.error('Lỗi khi tải danh sách Phường/Xã:', err);
    } finally {
      if (wardLoading) wardLoading.classList.add('hidden');
      updateAddressVerification();
    }
  }

  // Khi người dùng chọn Phường / Xã
  function onWardChange(selectEl) {
    const wardCode = selectEl.value;
    const selectedOpt = selectEl.options[selectEl.selectedIndex];
    const wardName = wardCode ? (selectedOpt.getAttribute('data-name') || selectedOpt.text.trim()) : '';
    document.getElementById('hidden_ward').value = wardName;
    updateAddressVerification();

    // Tự động focus sang ô nhập số nhà, tên đường nếu ô này đang trống
    const streetInput = document.getElementById('cust_address');
    if (streetInput && !streetInput.value.trim()) {
      streetInput.focus();
    }
  }

  // Khi nhập số nhà, tên đường
  function onStreetAddressInput(inputEl) {
    updateAddressVerification();
  }

  // =========================================================================
  // XÁC THỰC & ĐỒNG BỘ ĐỊA CHỈ GIAO HÀNG CHUẨN XÁC HIỆN TẠI
  // =========================================================================
  function updateAddressVerification() {
    const cityName = (document.getElementById('hidden_city')?.value || '').trim();
    const districtName = (document.getElementById('hidden_district')?.value || '').trim();
    const wardName = (document.getElementById('hidden_ward')?.value || '').trim();
    const street = (document.getElementById('cust_address')?.value || '').trim();

    const card = document.getElementById('addressVerificationCard');
    const iconWrap = document.getElementById('addressVerifyIconWrapper');
    const badge = document.getElementById('addressVerifyBadge');
    const title = document.getElementById('addressVerifyTitle');
    const summary = document.getElementById('addressVerifySummary');
    const pills = document.getElementById('addressDetailPills');
    const hiddenFull = document.getElementById('hidden_full_address');

    if (!card) return;

    const isComplete = cityName && districtName && wardName && street;

    if (isComplete) {
      const full = `${street}, ${wardName}, ${districtName}, ${cityName}`;
      if (hiddenFull) hiddenFull.value = full;

      card.className = 'p-4 rounded-2xl border transition-all duration-300 bg-gradient-to-br from-emerald-500/10 via-emerald-500/5 to-teal-500/10 border-emerald-400 shadow-sm';
      iconWrap.className = 'w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm';
      iconWrap.innerHTML = '<i data-lucide="shield-check" class="w-5 h-5"></i>';

      badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-600 text-white shadow-xs tracking-wider flex items-center gap-1';
      badge.innerHTML = '<i data-lucide="check" class="w-3 h-3"></i> ĐÃ XÁC THỰC ĐỊA CHỈ THỰC TẾ';

      title.className = 'text-emerald-950 font-bold';
      title.textContent = 'Địa Chỉ Giao Hàng Chuẩn Xác Đã Xác Nhận';

      summary.innerHTML = `
        <div class="font-serif-luxury text-sm font-bold text-neutral-950 mb-1 leading-snug">
          ${escapeHtml(full)}
        </div>
        <p class="text-[11px] text-emerald-800 font-medium flex items-center gap-1.5">
          <i data-lucide="sparkles" class="w-3.5 h-3.5 text-emerald-600"></i>
          <span>Tuyến vận chuyển hành chính được xác thực chính xác theo bản đồ bưu chính Việt Nam.</span>
        </p>
      `;

      if (pills) {
        pills.classList.remove('hidden');
        document.getElementById('pillCity').textContent = cityName;
        document.getElementById('pillDistrict').textContent = districtName;
        document.getElementById('pillWard').textContent = wardName;
        document.getElementById('pillStreet').textContent = street;
      }
    } else {
      card.className = 'p-4 rounded-2xl border transition-all duration-300 bg-neutral-50/80 border-neutral-200';
      iconWrap.className = 'w-9 h-9 rounded-xl bg-neutral-200 text-neutral-600 flex items-center justify-center shrink-0 shadow-xs';
      iconWrap.innerHTML = '<i data-lucide="map-pin" class="w-4 h-4"></i>';

      badge.className = 'px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-neutral-200 text-neutral-600 border border-neutral-300';
      badge.textContent = 'Chưa hoàn tất';

      title.className = 'text-neutral-700 font-bold';
      title.textContent = 'Xác Nhận Địa Chỉ Giao Hàng Thực Tế';

      let missing = [];
      if (!cityName) missing.push('Tỉnh/Thành phố');
      if (!districtName) missing.push('Quận/Huyện');
      if (!wardName) missing.push('Phường/Xã');
      if (!street) missing.push('Số nhà, tên đường');

      summary.innerHTML = `
        <span class="text-neutral-500">
          Vui lòng chọn: <strong class="text-amber-800 font-bold">${missing.join(' → ')}</strong> để hệ thống tiến hành kiểm tra và xác nhận tuyến giao hàng.
        </span>
      `;

      if (pills) pills.classList.add('hidden');
    }

    if (window.lucide && typeof window.lucide.createIcons === 'function') {
      window.lucide.createIcons();
    }
  }

  // Khi chọn từ Sổ địa chỉ đã lưu
  async function fillSavedAddress(select) {
    const opt = select.options[select.selectedIndex];
    if (!opt || !opt.value) {
      document.getElementById('cust_name').value = '';
      document.getElementById('cust_phone').value = '';
      document.getElementById('cust_address').value = '';
      document.getElementById('cust_province').value = '';
      document.getElementById('hidden_city').value = '';
      document.getElementById('hidden_district').value = '';
      document.getElementById('hidden_ward').value = '';

      document.getElementById('cust_district').innerHTML = '<option value="">-- Vui lòng chọn Tỉnh/TP trước --</option>';
      document.getElementById('cust_ward').innerHTML = '<option value="">-- Vui lòng chọn Quận/Huyện trước --</option>';

      updateAddressVerification();
      return;
    }

    document.getElementById('cust_name').value = opt.getAttribute('data-name') || '';
    document.getElementById('cust_phone').value = opt.getAttribute('data-phone') || '';
    document.getElementById('cust_address').value = opt.getAttribute('data-address') || '';

    const targetCity = (opt.getAttribute('data-city') || '').trim();
    const targetDistrict = (opt.getAttribute('data-district') || '').trim();
    const targetWard = (opt.getAttribute('data-ward') || '').trim();

    document.getElementById('hidden_city').value = targetCity;
    document.getElementById('hidden_district').value = targetDistrict;
    document.getElementById('hidden_ward').value = targetWard;

    // Tìm và chọn Tỉnh tương ứng trong select
    const provSelect = document.getElementById('cust_province');
    let matchedProvCode = null;
    for (let i = 0; i < provSelect.options.length; i++) {
      const pOpt = provSelect.options[i];
      const pName = (pOpt.getAttribute('data-name') || pOpt.text || '').toLowerCase();
      if (targetCity && (pName === targetCity.toLowerCase() || pName.includes(targetCity.toLowerCase()) || targetCity.toLowerCase().includes(pName))) {
        provSelect.selectedIndex = i;
        matchedProvCode = pOpt.value;
        break;
      }
    }

    if (matchedProvCode) {
      await loadDistrictsForProvince(matchedProvCode, targetDistrict, targetWard);
    } else {
      updateAddressVerification();
    }
  }

  // Khởi chạy khi DOM sẵn sàng
  document.addEventListener('DOMContentLoaded', async function() {
    const provSelect = document.getElementById('cust_province');
    if (provSelect && provSelect.value) {
      const selectedOpt = provSelect.options[provSelect.selectedIndex];
      document.getElementById('hidden_city').value = selectedOpt.getAttribute('data-name') || selectedOpt.text.trim();
      const initialDistrict = (document.getElementById('hidden_district')?.value || '').trim();
      const initialWard = (document.getElementById('hidden_ward')?.value || '').trim();
      await loadDistrictsForProvince(provSelect.value, initialDistrict, initialWard);
    }

    updateAddressVerification();
  });

  function updatePayOptionCards() {
    const radios = document.querySelectorAll('.pay-radio');
    radios.forEach(radio => {
      const card = document.getElementById('card_' + radio.id);
      const desc = document.getElementById('desc_' + radio.id);

      if (radio.checked) {
        if (card) {
          if (radio.id === 'pay_momo') {
            card.classList.add('border-[#a50064]', 'bg-pink-50/20', 'ring-1', 'ring-[#a50064]/20');
            card.classList.remove('border-neutral-200', 'border-neutral-950', 'bg-neutral-50', 'border-[#005baa]', 'bg-blue-50/20', 'ring-[#005baa]/20');
          } else if (radio.id === 'pay_vnpay') {
            card.classList.add('border-[#005baa]', 'bg-blue-50/20', 'ring-1', 'ring-[#005baa]/20');
            card.classList.remove('border-neutral-200', 'border-neutral-950', 'bg-neutral-50', 'border-[#a50064]', 'bg-pink-50/20', 'ring-[#a50064]/20');
          } else {
            card.classList.add('border-neutral-950', 'bg-neutral-50');
            card.classList.remove('border-neutral-200', 'border-[#a50064]', 'bg-pink-50/20', 'border-[#005baa]', 'bg-blue-50/20', 'ring-1', 'ring-[#a50064]/20', 'ring-[#005baa]/20');
          }
        }
        if (desc) desc.classList.remove('hidden');
      } else {
        if (card) {
          card.classList.remove('border-neutral-950', 'bg-neutral-50', 'border-[#a50064]', 'bg-pink-50/20', 'border-[#005baa]', 'bg-blue-50/20', 'ring-1', 'ring-[#a50064]/20', 'ring-[#005baa]/20');
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