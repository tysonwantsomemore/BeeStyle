@extends('layouts.client')

@php
  $carrier = $order ? mb_strtolower((string)$order->shipping_carrier, 'UTF-8') : '';
  $isGhtk = str_contains($carrier, 'ghtk') || str_contains($carrier, 'tiết kiệm');
  $isGhn = str_contains($carrier, 'ghn') || str_contains($carrier, 'nhanh');
  $isViettel = str_contains($carrier, 'viettel');
  $isJt = str_contains($carrier, 'j&t') || str_contains($carrier, 'jt');

  $brandPrimary = $isGhtk ? '#00483d' : ($isGhn ? '#d84315' : ($isViettel ? '#ee0033' : ($isJt ? '#e60012' : '#0f172a')));
  $brandAccent = $isGhtk ? '#069255' : ($isGhn ? '#f26522' : ($isViettel ? '#ff1744' : ($isJt ? '#ff3b30' : '#f59e0b')));
  $carrierTitle = $order ? ($order->shipping_carrier ?: 'Giao Hàng Tiết Kiệm (GHTK)') : 'Giao Hàng Tiết Kiệm (GHTK)';
  $carrierShort = $isGhtk ? 'GHTK' : ($isGhn ? 'GHN' : ($isViettel ? 'Viettel Post' : ($isJt ? 'J&T' : 'BeeStyle Express')));
  $hotline = $isGhtk ? '1900 6092' : ($isGhn ? '1900 636677' : ($isViettel ? '1900 8095' : ($isJt ? '1900 1088' : '1900 8888')));
@endphp

@section('title', 'Tra Cứu Vận Đơn ' . ($order ? $order->tracking_code : 'Bưu Kiện') . ' | ' . $carrierShort . ' Logistics')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 md:py-8 px-3 sm:px-6">
  <div class="max-w-7xl mx-auto space-y-6">

    <!-- ========================================================================= -->
    <!-- 1. GHTK BRAND HEADER & SEARCH BAR (CHUẨN LOGISTICS CHUYÊN NGHIỆP) -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 sm:p-6 overflow-hidden relative">
      <div class="absolute top-0 left-0 right-0 h-1.5" style="background: linear-gradient(90deg, {{ $brandPrimary }} 0%, {{ $brandAccent }} 100%);"></div>

      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 pt-1">
        <!-- Brand Info -->
        <div class="space-y-1.5">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white text-xs font-black tracking-wider uppercase shadow-xs" style="background-color: {{ $brandAccent }};">
              <i class="fa-solid fa-truck-fast"></i> {{ $carrierShort }} LOGISTICS
            </span>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
              <i class="fa-solid fa-shield-check"></i> Cổng Tra Cứu Trực Tuyến 24/7
            </span>
            <span class="text-xs text-slate-500 font-medium">Hotline hãng: <strong class="text-slate-800 font-mono">{{ $hotline }}</strong></span>
          </div>

          <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
            <span>Tra Cứu Hành Trình Vận Đơn Bưu Tá</span>
            @if($order)
              <span class="font-mono text-emerald-800 text-lg sm:text-xl font-bold bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200">
                {{ $order->tracking_code }}
              </span>
            @endif
          </h1>
          <p class="text-xs text-slate-500 font-medium">
            Hệ thống quét mã tự động thời gian thực tại các trạm trung chuyển SOC &amp; định vị bưu tá phát hàng
          </p>
        </div>

        <!-- Search Omnibar -->
        <div class="w-full lg:w-auto lg:min-w-[460px]">
          <form action="{{ route('client.carrier-tracking') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
            <div class="relative flex-grow">
              <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fa-solid fa-barcode text-base"></i>
              </span>
              <input type="text" name="code" value="{{ $code ?? '' }}" required
                placeholder="Nhập mã vận đơn (VD: GHTK-DUZVWTTA)..."
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-emerald-600 focus:bg-white transition-all shadow-2xs">
            </div>
            <button type="submit" class="px-5 py-2.5 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 shrink-0 hover:opacity-95" style="background-color: {{ $brandAccent }};">
              <i class="fa-solid fa-magnifying-glass"></i>
              <span>Tra Cứu</span>
            </button>
          </form>
          <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-500 justify-between">
            <a href="{{ route('client.order-tracking') }}" class="text-emerald-700 hover:text-emerald-900 font-semibold flex items-center gap-1 transition-colors">
              <i class="fa-solid fa-receipt"></i> Tra cứu theo Mã Đơn Hàng BeeStyle
            </a>
            <a href="{{ route('client.home') }}" class="text-slate-600 hover:text-slate-900 transition-colors">
              Về Trang Chủ Shop
            </a>
          </div>
        </div>
      </div>
    </div>

    @if($order)
      <!-- ========================================================================= -->
      <!-- 2. HERO STATUS CARD (BANNER TRẠNG THÁI CAO CẤP) -->
      <!-- ========================================================================= -->
      @php
        $isCompleted = in_array($order->shipping_status, ['completed', 'delivered']) || ($order->status_step ?? 1) >= 5;
        $isShipping = in_array($order->shipping_status, ['shipping']) || ($order->status_step ?? 1) == 4;
        $isCancelled = $order->shipping_status === 'cancelled';
        
        $codAmount = 0;
        if ($order->payment_status !== 'paid') {
          if ($order->is_deposit_required) {
            $codAmount = $order->remaining_amount ?: ($order->total_amount - $order->deposit_amount);
          } else {
            $codAmount = $order->total_amount;
          }
        }
      @endphp

      <div class="rounded-2xl text-white p-6 sm:p-8 shadow-lg relative overflow-hidden" style="background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, {{ $brandPrimary }} 100%);">
        <!-- Decorative Glow -->
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-emerald-500/20 blur-3xl pointer-events-none"></div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center relative z-10">
          <!-- Col Left: Info -->
          <div class="lg:col-span-7 space-y-3.5">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white text-slate-900 shadow-xs">
                <i class="fa-solid fa-truck text-emerald-600"></i> {{ $carrierTitle }}
              </span>
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $isCompleted ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/40' : ($isCancelled ? 'bg-rose-500/20 text-rose-300 border border-rose-400/40' : 'bg-amber-500/20 text-amber-300 border border-amber-400/40') }}">
                <span class="w-2 h-2 rounded-full {{ $isCompleted ? 'bg-emerald-400 animate-pulse' : ($isCancelled ? 'bg-rose-400' : 'bg-amber-400 animate-ping') }}"></span>
                {{ $order->shipping_status_label ?: 'Đang vận chuyển' }}
              </span>
              <span class="text-xs text-emerald-200/80 font-mono">
                <i class="fa-solid fa-shield-halved text-emerald-400 me-1"></i> DỮ LIỆU ĐỐI SOÁT NỘI BỘ 100%
              </span>
            </div>

            <div>
              <span class="text-xs text-emerald-200/80 uppercase font-semibold tracking-wider block">MÃ VẬN ĐƠN CHÍNH THỨC:</span>
              <div class="flex items-center gap-3 mt-1 flex-wrap">
                <h2 class="text-2xl sm:text-3xl font-black font-mono text-amber-300 tracking-wider letter-spacing-1">
                  {{ $order->tracking_code }}
                </h2>
                <button type="button" onclick="copyTrackingCode('{{ $order->tracking_code }}')" id="btnCopyTrackHero" class="px-3.5 py-1.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold rounded-lg text-xs transition-colors shadow-xs flex items-center gap-1">
                  <i class="fa-regular fa-copy"></i>
                  <span id="btnCopyTrackHeroTxt">Sao Chép</span>
                </button>
              </div>
            </div>

            <!-- Barcode SVG Simulation -->
            <div class="inline-flex items-center gap-1.5 py-1.5 px-3 bg-white/10 rounded-lg border border-white/15 backdrop-blur-xs font-mono text-[11px] text-white/90">
              <span class="tracking-widest font-mono text-xs opacity-75">||||| | ||| |||| | ||||| || |</span>
              <span>CODE-128 STANDARD</span>
            </div>

            <div class="flex items-center gap-3 sm:gap-5 text-xs text-emerald-100/90 flex-wrap pt-1 font-medium">
              <span>Mã đơn BeeStyle: <strong class="text-white font-mono">#{{ $order->order_code }}</strong></span>
              <span class="text-white/40">•</span>
              <span>Khởi tạo lúc: <strong class="text-white">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</strong></span>
              <span class="text-white/40">•</span>
              <span>Kiện hàng: <strong class="text-amber-300">{{ $order->items->count() }} sản phẩm ({{ $order->items->sum('quantity') }} cái)</strong></span>
            </div>
          </div>

          <!-- Col Right: COD & Fast Actions -->
          <div class="lg:col-span-5 bg-white/10 border border-white/20 p-5 rounded-2xl space-y-3.5 backdrop-blur-sm">
            <div class="flex justify-between items-center pb-2.5 border-b border-white/15">
              <span class="text-xs text-emerald-100 font-medium">Tiền thu người nhận (COD):</span>
              <strong class="font-mono text-xl font-bold text-amber-300">
                @if($order->payment_status === 'paid')
                  0₫ <span class="text-xs text-emerald-300 font-sans font-normal">(Đã thanh toán)</span>
                @else
                  {{ number_format($codAmount, 0, ',', '.') }}₫
                @endif
              </strong>
            </div>

            @if($order->is_deposit_required)
              <div class="flex justify-between items-center text-xs pb-2 border-b border-white/15">
                <span class="text-emerald-100">Tiền cọc trước (50%):</span>
                <span class="font-mono text-white font-bold">{{ number_format($order->deposit_amount, 0, ',', '.') }}₫ ({{ $order->deposit_status === 'paid' ? 'Đã thu cọc' : 'Chờ cọc' }})</span>
              </div>
            @endif

            <div class="flex justify-between items-center text-xs pb-1 text-emerald-100">
              <span>Trọng lượng bưu phẩm:</span>
              <strong class="text-white font-mono">~500g (Chuẩn hộp may mặc)</strong>
            </div>

            <div class="flex justify-between items-center text-xs pb-2 border-b border-white/15 text-emerald-100">
              <span>Gói cước vận chuyển:</span>
              <strong class="text-amber-200">{{ $carrierShort }} Tiêu Chuẩn Nhanh (Fast)</strong>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 pt-1 flex-wrap">
              <button type="button" onclick="openPrintModal()" class="flex-1 py-2.5 px-3 bg-white hover:bg-slate-100 text-slate-900 font-bold rounded-xl text-xs transition-colors shadow-xs flex items-center justify-center gap-1.5">
                <i class="fa-solid fa-print text-emerald-700"></i>
                <span>In Phiếu A6</span>
              </button>
              <button type="button" onclick="openSyncModal()" class="py-2.5 px-3 bg-white/15 hover:bg-white/25 text-white font-bold rounded-xl text-xs transition-colors border border-white/20 flex items-center gap-1.5">
                <i class="fa-solid fa-network-wired text-emerald-300"></i>
                <span>Cổng {{ $carrierShort }}</span>
              </button>
              @if(Auth::check() && Auth::user()->is_admin)
                <a href="{{ route('admin.orders.show', $order->id) }}" class="py-2.5 px-3 bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold rounded-xl text-xs transition-colors flex items-center gap-1">
                  <i class="fa-solid fa-gear"></i> Admin
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- 3. MAIN 2-COLUMN SECTION -->
      <!-- ========================================================================= -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- ==================== LEFT COLUMN (8 cols) ==================== -->
        <div class="lg:col-span-8 space-y-6">

          <!-- 3.1 TIMELINE HÀNH TRÌNH LUÂN CHUYỂN BƯU KIỆN (CHECKPOINTS GHTK) -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-7 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-200">
              <div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                  <i class="fa-solid fa-timeline text-emerald-600"></i>
                  <span>Lịch Sử Luân Chuyển Bưu Kiện (Checkpoints)</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Thời gian thực tế ghi nhận tại các điểm trung chuyển &amp; bưu tá giao hàng</p>
              </div>
              <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 w-fit">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Đồng bộ tự động
              </span>
            </div>

            @php
              $step = $order->status_step ?? 1;
              $created = $order->created_at;
              $confirmed = $order->confirmed_at ?: ($created ? $created->copy()->addMinutes(11) : now());
              $processing = $order->processing_at ?: ($confirmed ? $confirmed->copy()->addMinutes(15) : now());
              $shipping = $order->shipping_at ?: ($processing ? $processing->copy()->addMinutes(30) : now());
              $delivered = $order->delivered_at ?: ($shipping ? $shipping->copy()->addHours(24) : now());
              $completed = $order->completed_at ?: ($delivered ? $delivered->copy()->addHours(2) : now());

              $checkpoints = [];

              // 1. Khởi tạo
              $checkpoints[] = [
                'title' => 'Khởi tạo đơn hàng thành công',
                'desc' => 'Đơn hàng #' . $order->order_code . ' đã được tiếp nhận trên hệ thống BeeStyle. Mã vận đơn ' . $order->tracking_code . ' đã được cấp.',
                'hub' => 'Cổng Đơn Hàng BeeStyle Menswear',
                'time' => $created,
                'icon' => 'fa-clipboard-list',
              ];

              // 2. Duyệt
              if ($step >= 2) {
                $checkpoints[] = [
                  'title' => 'Shop xác nhận & in phiếu giao nhận',
                  'desc' => 'Kho tổng BeeStyle đã hoàn tất kiểm tra thông tin người nhận, in phiếu đóng gói và tạo lệnh hẹn lấy hàng tới bưu cục ' . $carrierShort . '.',
                  'hub' => 'Kho Tổng BeeStyle (Cầu Giấy, Hà Nội)',
                  'time' => $confirmed,
                  'icon' => 'fa-clipboard-check',
                ];
              }

              // 3. Đóng gói & dán tem
              if ($step >= 3) {
                $checkpoints[] = [
                  'title' => 'Đóng gói hoàn tất & dán nhãn vận chuyển [' . $order->tracking_code . ']',
                  'desc' => 'Kiện hàng đã được kiểm định chất lượng may mặc QC, đóng thùng carton niêm phong và dán mã vạch bưu phẩm.',
                  'hub' => 'Kho Phân Loại BeeStyle Logistics',
                  'time' => $processing,
                  'icon' => 'fa-box-open',
                ];
              }

              // 4. Bưu tá lấy hàng & trung chuyển
              if ($step >= 4) {
                $checkpoints[] = [
                  'title' => 'Bưu tá ' . $carrierShort . ' đã lấy hàng thành công',
                  'desc' => 'Bưu tá Nguyễn Văn Tuấn (Mã NV: ' . $carrierShort . '-8821 - SĐT: 0988.123.456) đã tiếp nhận kiện hàng tại kho shop.',
                  'hub' => 'Bưu Cục Lấy Hàng ' . $carrierShort . ' Cầu Giấy',
                  'time' => $shipping,
                  'icon' => 'fa-truck-ramp-box',
                ];

                $checkpoints[] = [
                  'title' => 'Nhập Kho Trung Chuyển ' . $carrierShort . ' Hà Nội SOC',
                  'desc' => 'Kiện hàng đã nhập kho trung chuyển phân loại tự động bằng băng chuyền tốc độ cao.',
                  'hub' => 'Trung Tâm Khai Thác & Trung Chuyển ' . $carrierShort . ' Miền Bắc',
                  'time' => $shipping->copy()->addHours(3)->addMinutes(12),
                  'icon' => 'fa-warehouse',
                ];

                $checkpoints[] = [
                  'title' => 'Rời kho trung chuyển - Đang luân chuyển xe tải tới bưu cục phát',
                  'desc' => 'Kiện hàng được bốc xếp lên xe chuyên dụng di chuyển tới bưu cục phát khu vực người nhận.',
                  'hub' => 'Tuyến Trung Chuyển Xe Tải ' . $carrierShort . ' #29H-882.19',
                  'time' => $shipping->copy()->addHours(7)->addMinutes(45),
                  'icon' => 'fa-truck-fast',
                ];

                $checkpoints[] = [
                  'title' => 'Đã đến bưu cục phát - Bưu tá đang tiến hành giao hàng',
                  'desc' => 'Bưu tá đang di chuyển và liên hệ người nhận theo số điện thoại: ' . substr($order->customer_phone, 0, 4) . '***' . substr($order->customer_phone, -3) . '.',
                  'hub' => 'Bưu Cục Phát ' . ($order->city ?: 'Hà Nội'),
                  'time' => $delivered ? $delivered->copy()->subHours(3)->subMinutes(10) : $shipping->copy()->addHours(14),
                  'icon' => 'fa-motorcycle',
                ];
              }

              // 5. Giao hàng thành công
              if ($step >= 5) {
                $checkpoints[] = [
                  'title' => 'GIAO HÀNG THÀNH CÔNG - KHÁCH KÝ NHẬN',
                  'desc' => 'Khách hàng ' . $order->customer_name . ' đã nhận đủ bưu phẩm, kiểm tra tem niêm phong và ký nhận thành công. Bưu tá đã thu tiền COD: ' . ($order->payment_status === 'paid' ? '0₫ (Đã thanh toán)' : number_format($codAmount, 0, ',', '.') . '₫') . '.',
                  'hub' => 'Địa chỉ người nhận: ' . $order->shipping_address,
                  'time' => $delivered,
                  'icon' => 'fa-handshake',
                  'pod_url' => $order->delivery_proof_url,
                  'pod_note' => $order->delivery_proof_note,
                ];
              }

              // 6. Hoàn tất đối soát
              if ($step >= 6) {
                $checkpoints[] = [
                  'title' => 'Hoàn tất hành trình bưu gửi & đối soát',
                  'desc' => 'Đơn vị vận chuyển đã hoàn tất đối soát bưu tá bưu cục và đóng trạng thái luân chuyển thành công.',
                  'hub' => 'Hệ Thống Đối Soát Vận Chuyển ' . $carrierShort,
                  'time' => $completed,
                  'icon' => 'fa-circle-check',
                ];
              }

              // Sắp xếp mốc mới nhất lên đầu
              $checkpoints = array_reverse($checkpoints);
            @endphp

            <!-- Timeline Items -->
            <div class="relative pl-6 sm:pl-8 border-l-2 border-slate-200 ml-3 space-y-6">
              @foreach($checkpoints as $cIndex => $cp)
                @php $isLatest = ($cIndex === 0); @endphp
                <div class="relative">
                  <!-- Timeline Pin Icon -->
                  <div class="absolute -left-[35px] sm:-left-[43px] top-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shadow-xs {{ $isLatest ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-white text-slate-500 border-2 border-slate-300' }}">
                    <i class="fa-solid {{ $cp['icon'] }}"></i>
                  </div>

                  <!-- Content Card -->
                  <div class="p-4 sm:p-5 rounded-2xl border transition-all {{ $isLatest ? 'bg-emerald-50/70 border-emerald-300 shadow-xs' : 'bg-slate-50 border-slate-200' }} space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
                      <h4 class="text-sm sm:text-base font-bold {{ $isLatest ? 'text-emerald-950' : 'text-slate-900' }} flex items-center gap-2">
                        <span>{{ $cp['title'] }}</span>
                        @if($isLatest)
                          <span class="px-2 py-0.5 bg-emerald-600 text-white rounded-full text-[10px] font-black tracking-wider uppercase">
                            MỚI NHẤT
                          </span>
                        @endif
                      </h4>
                      <span class="inline-flex items-center gap-1 font-mono text-xs font-bold {{ $isLatest ? 'text-emerald-700' : 'text-slate-500' }}">
                        <i class="fa-regular fa-clock"></i> {{ $cp['time'] ? $cp['time']->format('H:i - d/m/Y') : '' }}
                      </span>
                    </div>

                    <p class="text-xs leading-relaxed {{ $isLatest ? 'text-emerald-900' : 'text-slate-600' }}">
                      {{ $cp['desc'] }}
                    </p>

                    <div class="flex items-center justify-between gap-3 pt-2 border-t {{ $isLatest ? 'border-emerald-200' : 'border-slate-200' }} text-xs flex-wrap">
                      <div class="flex items-center gap-1.5 text-slate-600">
                        <i class="fa-solid fa-location-dot text-rose-500"></i>
                        <span>Trạm / Điểm quét:</span>
                        <strong class="text-slate-900 font-semibold">{{ $cp['hub'] }}</strong>
                      </div>

                      @if(!empty($cp['pod_url']))
                        <button type="button" onclick="openPodModal()" class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-900 font-bold underline underline-offset-2">
                          <i class="fa-solid fa-camera"></i> Xem Ảnh Bưu Tá Chụp (POD)
                        </button>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- 3.2 BẢNG KÊ CHI TIẾT SẢN PHẨM TRONG KIỆN HÀNG (PACKAGE PICKING MANIFEST) -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-7 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
              <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked text-amber-500"></i>
                <span>Chi Tiết Bưu Phẩm Trong Kiện Hàng ({{ $order->items->count() }} mẫu)</span>
              </h3>
              <span class="text-xs text-slate-500">Quy cách: Hộp niêm phong chống sốc</span>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs">
                <thead>
                  <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase text-[11px]">
                    <th class="pb-2.5">Sản Phẩm</th>
                    <th class="pb-2.5">Phân Loại</th>
                    <th class="pb-2.5 text-center">Số Lượng</th>
                    <th class="pb-2.5 text-right">Đơn Giá</th>
                    <th class="pb-2.5 text-right">Thành Tiền</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($order->items as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="py-3 pr-3">
                        <div class="flex items-center gap-3">
                          @if($item->product && $item->product->thumbnail)
                            <img src="{{ asset($item->product->thumbnail) }}" alt="{{ $item->product_name }}" class="w-11 h-11 object-cover rounded-lg border border-slate-200 shrink-0 bg-white">
                          @else
                            <div class="w-11 h-11 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                              <i class="fa-solid fa-shirt"></i>
                            </div>
                          @endif
                          <div class="min-w-0">
                            <strong class="block text-slate-900 font-bold truncate max-w-[220px]">{{ $item->product_name }}</strong>
                            <span class="text-[11px] text-slate-500 font-mono">SKU: {{ $item->product_sku ?: 'BEE-' . $item->product_id }}</span>
                          </div>
                        </div>
                      </td>
                      <td class="py-3 text-slate-600 whitespace-nowrap">
                        @if($item->color)
                          <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-medium mr-1">{{ $item->color }}</span>
                        @endif
                        @if($item->size)
                          <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-bold">{{ $item->size }}</span>
                        @endif
                        @if(!$item->color && !$item->size)
                          <span class="text-slate-400">Tiêu chuẩn</span>
                        @endif
                      </td>
                      <td class="py-3 text-center font-bold font-mono text-slate-900">x{{ $item->quantity }}</td>
                      <td class="py-3 text-right font-mono text-slate-600">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                      <td class="py-3 text-right font-mono font-bold text-rose-600">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="pt-3 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 text-xs text-slate-600">
              <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>Tất cả sản phẩm đã qua kiểm định chất lượng (QC Passed) trước khi bàn giao bưu tá.</span>
              </div>
              <div class="text-right font-bold text-slate-900">
                Tổng giá trị kiện hàng: <span class="font-mono text-sm text-rose-600">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
              </div>
            </div>
          </div>

        </div>

        <!-- ==================== RIGHT COLUMN (4 cols) ==================== -->
        <div class="lg:col-span-4 space-y-6">

          <!-- 3.3 THẺ BƯU TÁ GIAO HÀNG PHỤ TRÁCH (GHTK SHIPPER) -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-4" style="border-top: 4px solid {{ $brandAccent }};">
            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
              <i class="fa-solid fa-id-badge text-emerald-600"></i>
              <span>Nhân Viên Giao Hàng Phụ Trách</span>
            </h4>

            <div class="flex items-center gap-3.5 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
              <div class="w-12 h-12 rounded-full flex items-center justify-center text-white text-lg font-bold shadow-xs shrink-0" style="background-color: {{ $brandAccent }};">
                <i class="fa-solid fa-user-shield"></i>
              </div>
              <div class="min-w-0">
                <strong class="block text-slate-900 text-sm font-bold truncate">Nguyễn Văn Tuấn</strong>
                <span class="block text-xs text-slate-500 font-mono">Bưu tá {{ $carrierShort }} • Mã: <strong>{{ $carrierShort }}-8821</strong></span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 mt-0.5">
                  <i class="fa-solid fa-star"></i> 4.9 / 5.0 (Đã định danh KYC)
                </span>
              </div>
            </div>

            <div class="space-y-2">
              <a href="tel:0988123456" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition-colors shadow-xs flex items-center justify-center gap-2">
                <i class="fa-solid fa-phone"></i>
                <span>Gọi Bưu Tá: 0988.123.456</span>
              </a>
              <a href="tel:{{ $hotline }}" class="w-full py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition-colors flex items-center justify-center gap-2 border border-slate-200">
                <i class="fa-solid fa-headset text-slate-500"></i>
                <span>Tổng Đài {{ $carrierShort }}: {{ $hotline }}</span>
              </a>
            </div>
          </div>

          <!-- 3.4 BẰNG CHỨNG GIAO HÀNG (POD) NẾU CÓ -->
          @if($step >= 5 || in_array($order->shipping_status, ['delivered', 'completed']))
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3" style="border-left: 4px solid #10b981;">
              <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                  <i class="fa-solid fa-camera text-emerald-600"></i>
                  <span>Ảnh Bưu Tá Chụp (POD)</span>
                </h4>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">ĐÃ XÁC NHẬN</span>
              </div>

              <div class="rounded-xl overflow-hidden border border-slate-200 relative group cursor-pointer bg-slate-900" onclick="openPodModal()">
                <img src="{{ $order->delivery_proof_url }}" alt="Bằng chứng giao hàng bưu tá" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1.5">
                  <i class="fa-solid fa-expand"></i>
                  <span>Bấm để phóng to ảnh HD</span>
                </div>
              </div>

              <div class="text-xs text-slate-600 space-y-1.5 pt-1">
                <div class="flex justify-between">
                  <span>Mốc giờ chụp:</span>
                  <strong class="text-slate-900 font-mono">{{ $order->delivery_proof_at ? $order->delivery_proof_at->format('H:i, d/m/Y') : ($order->delivered_at ? $order->delivered_at->format('H:i, d/m/Y') : 'Vừa xong') }}</strong>
                </div>
                <div>
                  <span class="text-slate-500 block mb-0.5">Ghi chú bưu tá:</span>
                  <p class="p-2.5 bg-slate-50 rounded-lg text-slate-800 italic border border-slate-200 text-[11px]">
                    "{{ $order->delivery_proof_note ?: 'Khách hàng đã kiểm tra đủ kiện hàng, tem niêm phong nguyên vẹn và thanh toán hoàn tất.' }}"
                  </p>
                </div>
                <button type="button" onclick="openPodModal()" class="w-full py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-lg font-bold text-xs transition-colors flex items-center justify-center gap-1.5">
                  <i class="fa-solid fa-magnifying-glass-plus"></i>
                  <span>Xem Chi Tiết Bằng Chứng Giao</span>
                </button>
              </div>
            </div>
          @endif

          <!-- 3.5 THÔNG TIN NGƯỜI GỬI (SHOP) -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3">
            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2 pb-2 border-b border-slate-200">
              <i class="fa-solid fa-store text-amber-500"></i>
              <span>Thông Tin Bên Gửi (Shop)</span>
            </h4>
            <div class="text-xs space-y-1.5 text-slate-700">
              <strong class="block text-slate-900 text-sm">BeeStyle Menswear Official Store</strong>
              <p class="flex items-start gap-1.5">
                <i class="fa-solid fa-location-dot text-rose-500 mt-0.5"></i>
                <span>Kho Tổng: Tòa Nhà BeeStyle, Q. Cầu Giấy, TP. Hà Nội</span>
              </p>
              <p class="flex items-center gap-1.5">
                <i class="fa-solid fa-phone text-slate-400"></i>
                <span>Hotline hỗ trợ: <strong class="text-slate-900 font-mono">1900 8888</strong></span>
              </p>
            </div>
          </div>

          <!-- 3.6 THÔNG TIN NGƯỜI NHẬN (KHÁCH HÀNG THỰC TẾ) -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3.5">
            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2 pb-2 border-b border-slate-200">
              <i class="fa-solid fa-user-check text-emerald-600"></i>
              <span>Thông Tin Người Nhận Hàng</span>
            </h4>

            <div class="text-xs space-y-2.5">
              <div>
                <span class="text-slate-400 uppercase text-[10px] font-bold block">Tên người nhận:</span>
                <strong class="text-slate-900 text-sm">{{ $order->customer_name }}</strong>
              </div>

              <div>
                <span class="text-slate-400 uppercase text-[10px] font-bold block">Số điện thoại:</span>
                <strong class="font-mono text-emerald-800 text-sm">{{ substr($order->customer_phone, 0, 4) . '***' . substr($order->customer_phone, -3) }}</strong>
                <span class="text-[10px] text-slate-400 ml-1">(Đã ẩn bảo mật)</span>
              </div>

              <div>
                <span class="text-slate-400 uppercase text-[10px] font-bold block">Địa chỉ giao hàng:</span>
                <p class="text-slate-800 font-medium leading-relaxed mt-0.5">
                  {{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}
                </p>
              </div>

              <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex justify-between items-center">
                  <span class="text-slate-500">Hình thức trả:</span>
                  <strong class="text-slate-900">{{ $order->payment_method_name }}</strong>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-500">Trạng thái tiền:</span>
                  <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $order->payment_status_label }}
                  </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                  <span class="font-bold text-slate-900">Thu COD bưu tá:</span>
                  <strong class="font-mono text-base font-bold text-rose-600">
                    @if($order->payment_status === 'paid')
                      0₫
                    @else
                      {{ number_format($codAmount, 0, ',', '.') }}₫
                    @endif
                  </strong>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>

    @else
      <!-- ========================================================================= -->
      <!-- EMPTY STATE (KHI KHÔNG TÌM THẤY VẬN ĐƠN) -->
      <!-- ========================================================================= -->
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-8 sm:p-12 text-center max-w-xl mx-auto space-y-4">
        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-2xl">
          <i class="fa-solid fa-box-open"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900">Không Tìm Thấy Vận Đơn "{{ $code }}"</h3>
        <p class="text-xs text-slate-500 leading-relaxed max-w-md mx-auto">
          Mã vận đơn bạn nhập chưa đúng hoặc chưa được đồng bộ trên hệ thống. Vui lòng kiểm tra lại mã trên phiếu bưu tá hoặc tra cứu bằng mã đơn hàng.
        </p>
        <div class="flex justify-center gap-2 pt-2">
          <a href="{{ route('client.carrier-tracking', ['code' => 'GHTK-DUZVWTTA']) }}" class="px-4 py-2 text-white font-bold rounded-xl text-xs transition-colors shadow-xs" style="background-color: {{ $brandAccent }};">
            Xem Vận Đơn Mẫu GHTK-DUZVWTTA
          </a>
          <a href="{{ route('client.order-tracking') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl text-xs transition-colors border border-slate-200">
            Tra Cứu Mã Đơn
          </a>
        </div>
      </div>
    @endif

  </div>
</div>

@if($order)
  <!-- ========================================================================= -->
  <!-- MODAL 1: PHÓNG TO ẢNH POD (TAILWIND NATIVE MODAL) -->
  <!-- ========================================================================= -->
  <div id="carrierPodModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden border border-slate-700">
      <div class="flex items-center justify-between p-4 bg-slate-950 text-white">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-camera-retro text-emerald-400"></i>
          <span class="font-bold text-xs sm:text-sm">Bằng Chứng Giao Hàng Bưu Tá Gửi Về Kho (POD)</span>
        </div>
        <button type="button" onclick="closePodModal()" class="text-slate-400 hover:text-white p-1">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>
      <div class="p-2 bg-black text-center">
        <img src="{{ $order->delivery_proof_url }}" alt="POD #{{ $order->order_code }}" class="max-h-[70vh] w-auto mx-auto object-contain">
      </div>
      <div class="p-4 bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs border-t border-slate-200">
        <div class="space-y-0.5">
          <div>Mã vận đơn: <strong class="font-mono text-emerald-800">{{ $order->tracking_code }}</strong> • Đơn: #{{ $order->order_code }}</div>
          <div class="text-slate-500">Giờ ghi nhận: {{ $order->delivery_proof_at ? $order->delivery_proof_at->format('H:i:s, d/m/Y') : ($order->delivered_at ? $order->delivered_at->format('H:i:s, d/m/Y') : 'Vừa xong') }}</div>
        </div>
        <div class="flex items-center gap-2">
          <a href="{{ $order->delivery_proof_url }}" download="POD_{{ $order->tracking_code }}.jpg" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg font-bold text-xs transition-colors flex items-center gap-1 border border-slate-300">
            <i class="fa-solid fa-download"></i> Tải Ảnh
          </a>
          <button type="button" onclick="closePodModal()" class="px-4 py-2 bg-slate-950 hover:bg-slate-800 text-white rounded-lg font-bold text-xs transition-colors">
            Đóng
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL 2: IN PHIẾU VẬN ĐƠN A6 (WAYBILL PRINT MODAL) -->
  <!-- ========================================================================= -->
  <div id="carrierWaybillModal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-xs flex items-center justify-center p-4 hidden overflow-y-auto">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden my-6 border border-slate-300">
      <div class="flex items-center justify-between p-4 bg-slate-900 text-white d-print-none">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-receipt text-amber-400"></i>
          <span class="font-bold text-xs sm:text-sm">Phiếu Giao Nhận Vận Đơn Chuẩn A6 — {{ $carrierShort }}</span>
        </div>
        <button type="button" onclick="closePrintModal()" class="text-slate-400 hover:text-white p-1">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <!-- Printable A6 Sheet Area -->
      <div id="printableWaybillArea" class="p-6 bg-white text-slate-900 space-y-4 font-sans text-xs">
        <!-- Waybill Top Bar -->
        <div class="flex justify-between items-center pb-3 border-b-2 border-slate-900">
          <div>
            <h2 class="text-xl font-black tracking-tight" style="color: {{ $brandAccent }};">{{ $carrierShort }} LOGISTICS</h2>
            <span class="text-[10px] text-slate-500 font-semibold block">DỊCH VỤ CHUYỂN PHÁT NHANH TOÀN QUỐC</span>
          </div>
          <div class="text-right">
            <span class="text-[10px] text-slate-500 block">MÃ VẬN ĐƠN:</span>
            <strong class="font-mono text-base font-bold text-slate-950">{{ $order->tracking_code }}</strong>
          </div>
        </div>

        <!-- Barcode Graphic Simulation -->
        <div class="p-2.5 bg-slate-50 rounded border border-slate-200 text-center font-mono space-y-1">
          <div class="text-lg tracking-[0.25em] font-black text-slate-950 select-none">
            ||| | |||| | ||||| | |||| | ||| ||
          </div>
          <span class="text-xs font-bold text-slate-800">{{ $order->tracking_code }}</span>
        </div>

        <!-- Sender / Receiver Grid -->
        <div class="grid grid-cols-2 gap-3 pb-3 border-b border-slate-200 text-[11px]">
          <div class="p-2.5 bg-slate-50 rounded border border-slate-200 space-y-1">
            <span class="text-[10px] font-bold text-slate-500 uppercase block">Người Gửi (From):</span>
            <strong class="text-slate-900 block">BeeStyle Menswear</strong>
            <span class="text-slate-600 block">Kho Cầu Giấy, Hà Nội</span>
            <span class="text-slate-600 block">Hotline: 1900 8888</span>
          </div>
          <div class="p-2.5 bg-slate-50 rounded border border-slate-200 space-y-1">
            <span class="text-[10px] font-bold text-slate-500 uppercase block">Người Nhận (To):</span>
            <strong class="text-slate-900 block">{{ $order->customer_name }}</strong>
            <span class="text-slate-600 block font-mono font-bold">{{ $order->customer_phone }}</span>
            <span class="text-slate-600 block leading-tight">{{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}</span>
          </div>
        </div>

        <!-- COD & Manifest -->
        <div class="flex justify-between items-center p-3 rounded-lg border-2 border-slate-900 bg-amber-50/60">
          <div>
            <span class="text-[10px] font-bold text-slate-600 uppercase block">Tiền Thu Người Nhận (COD):</span>
            <strong class="font-mono text-xl font-black text-rose-600">
              @if($order->payment_status === 'paid')
                0₫ (ĐÃ THANH TOÁN)
              @else
                {{ number_format($codAmount, 0, ',', '.') }}₫
              @endif
            </strong>
          </div>
          <div class="text-right text-[11px]">
            <span class="block">Kiện: <strong>{{ $order->items->count() }} món</strong></span>
            <span class="block">KL: <strong>~500g</strong></span>
          </div>
        </div>

        <!-- Items Checklist -->
        <div class="border border-slate-200 rounded-lg p-2.5 space-y-1 text-[11px]">
          <span class="text-[10px] font-bold text-slate-500 uppercase block">Nội Dung Hàng Hóa:</span>
          @foreach($order->items as $it)
            <div class="flex justify-between text-slate-700">
              <span class="truncate max-w-[280px]">• {{ $it->product_name }} ({{ $it->color ?: 'Mặc định' }} / {{ $it->size ?: 'M' }})</span>
              <span class="font-mono font-bold">x{{ $it->quantity }}</span>
            </div>
          @endforeach
        </div>

        <!-- Signature Box -->
        <div class="grid grid-cols-2 gap-4 text-center pt-2 text-[11px]">
          <div class="space-y-8">
            <span class="font-bold text-slate-700 block">Chữ Ký Người Gửi</span>
            <span class="text-slate-400 block italic">(Đã niêm phong kho)</span>
          </div>
          <div class="space-y-8">
            <span class="font-bold text-slate-700 block">Chữ Ký Người Nhận</span>
            <span class="text-slate-400 block italic">(Xác nhận hàng nguyên vẹn)</span>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-2 d-print-none">
        <button type="button" onclick="closePrintModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold rounded-xl text-xs transition-colors">
          Đóng
        </button>
        <button type="button" onclick="triggerWaybillPrint()" class="px-5 py-2 text-white font-bold rounded-xl text-xs transition-colors shadow-xs flex items-center gap-1.5" style="background-color: {{ $brandAccent }};">
          <i class="fa-solid fa-print"></i>
          <span>In Phiếu Ngay</span>
        </button>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL 3: ĐỒNG BỘ CỔNG ĐỐI TÁC GHTK -->
  <!-- ========================================================================= -->
  <div id="carrierSyncModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-slate-200">
      <div class="p-5 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-network-wired text-emerald-600"></i>
          <h4 class="font-bold text-sm text-slate-900">Đồng Bộ Cổng Đối Tác {{ $carrierShort }}</h4>
        </div>
        <button type="button" onclick="closeSyncModal()" class="text-slate-400 hover:text-slate-900 p-1">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <div class="p-5 text-xs text-slate-700 space-y-3.5">
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
          <div class="flex justify-between">
            <span class="text-slate-500">Đơn vị vận chuyển:</span>
            <strong class="text-slate-900">{{ $carrierTitle }}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Mã vận đơn:</span>
            <strong class="font-mono text-emerald-800">{{ $order->tracking_code }}</strong>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-500">Hạ tầng:</span>
            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 font-bold text-[10px]">GHTK Cloud API v2</span>
          </div>
        </div>

        <div class="p-3 rounded-xl bg-sky-50 border border-sky-200 text-sky-950 space-y-1">
          <strong class="block text-xs font-bold text-sky-900">Giải thích tra cứu thực tế:</strong>
          <p class="text-[11px] leading-relaxed text-sky-800">
            Hệ thống đồ án đã đồng bộ và mô phỏng 100% đầy đủ toàn bộ hành trình, trạm trung chuyển SOC, bưu tá giao hàng và ảnh thực tế giao thành công (POD) ngay trên trang này.
          </p>
        </div>

        @if($order->external_tracking_url)
          <div class="text-center pt-1">
            <a href="{{ $order->external_tracking_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 hover:bg-slate-50 transition-colors">
              <span>Mở website ngoài đời thực {{ $order->shipping_carrier_code }}</span>
              <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
          </div>
        @endif
      </div>

      <div class="p-4 bg-slate-50 border-t border-slate-200 text-right">
        <button type="button" onclick="closeSyncModal()" class="px-5 py-2 bg-slate-950 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition-colors">
          Đã Hiểu &amp; Đóng
        </button>
      </div>
    </div>
  </div>
@endif

<style>
@media print {
  body * {
    visibility: hidden;
  }
  #printableWaybillArea, #printableWaybillArea * {
    visibility: visible;
  }
  #printableWaybillArea {
    position: fixed;
    left: 0;
    top: 0;
    width: 100% !important;
    max-width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    box-shadow: none !important;
  }
}
</style>
@endsection

@push('scripts')
<script>
  function copyTrackingCode(code) {
    if (!code) return;
    navigator.clipboard.writeText(code).then(() => {
      const txt = document.getElementById('btnCopyTrackHeroTxt');
      if (txt) {
        const orig = txt.textContent;
        txt.textContent = 'Đã chép!';
        setTimeout(() => { txt.textContent = orig; }, 1800);
      }
    });
  }

  function openPodModal() {
    document.getElementById('carrierPodModal')?.classList.remove('hidden');
  }
  function closePodModal() {
    document.getElementById('carrierPodModal')?.classList.add('hidden');
  }

  function openPrintModal() {
    document.getElementById('carrierWaybillModal')?.classList.remove('hidden');
  }
  function closePrintModal() {
    document.getElementById('carrierWaybillModal')?.classList.add('hidden');
  }

  function openSyncModal() {
    document.getElementById('carrierSyncModal')?.classList.remove('hidden');
  }
  function closeSyncModal() {
    document.getElementById('carrierSyncModal')?.classList.add('hidden');
  }

  function triggerWaybillPrint() {
    window.print();
  }

  // Close modals on Escape key
  window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closePodModal();
      closePrintModal();
      closeSyncModal();
    }
  });
</script>
@endpush
