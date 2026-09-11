@extends('layouts.client')

@section('title', 'Tra Cứu & Theo Dõi Hành Trình Đơn Hàng — BEESTYLE Atelier')

@section('content')
<main class="w-full flex-grow py-8 md:py-12 px-4 sm:px-6 max-w-5xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-600 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-neutral-950 font-medium transition-colors">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Tra Cứu Hành Trình &amp; Quản Lý Bưu Kiện</span>
  </nav>

  <!-- ========================================================================= -->
  <!-- 1. SEARCH OMNIBAR: TRA CỨU ĐƠN HÀNG & MÃ VẬN ĐƠN BƯU TÁ -->
  <!-- ========================================================================= -->
  <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200/90 shadow-sm mb-8">
    <div class="max-w-2xl mx-auto text-center mb-6">
      <span class="text-xs tracking-[0.3em] uppercase text-amber-800 font-bold block mb-1">DỊCH VỤ TRỰC TUYẾN CHÍNH HÃNG</span>
      <h1 class="font-serif-luxury text-2xl md:text-3xl font-bold text-neutral-900">Tra Cứu Hành Trình Đơn Hàng</h1>
      <p class="text-xs text-neutral-600 mt-1.5 font-normal leading-relaxed">
        Hỗ trợ tra cứu tức thì theo <strong>Mã Đơn Hàng</strong> hoặc <strong>Mã Vận Đơn Bưu Tá</strong> (GHTK, GHN, Viettel Post, J&amp;T...)
      </p>
    </div>

    <!-- Mode Tabs -->
    <div class="flex justify-center gap-2 mb-5">
      <button type="button" id="btnTabOrder" onclick="switchSearchMode('order')" class="px-5 py-2 rounded-full text-xs font-bold transition-all {{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200' : 'bg-neutral-950 text-white shadow-xs' }}">
        <i data-lucide="receipt" class="w-3.5 h-3.5 inline mr-1 text-amber-400"></i> Mã Đơn Hàng
      </button>
      <button type="button" id="btnTabTracking" onclick="switchSearchMode('tracking')" class="px-5 py-2 rounded-full text-xs font-bold transition-all {{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'bg-neutral-950 text-white shadow-xs' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200' }}">
        <i data-lucide="barcode" class="w-3.5 h-3.5 inline mr-1 text-amber-400"></i> Mã Vận Đơn Bưu Tá
      </button>
    </div>

    <!-- Search Form -->
    <form action="{{ route('client.order-tracking') }}" method="GET" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-2.5" id="trackingSearchForm">
      <input type="hidden" name="type" id="searchTypeInput" value="{{ $searchType ?? 'auto' }}">
      <div class="relative flex-grow">
        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-500" id="searchIcon">
          @if(($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking'))
            <i data-lucide="barcode" class="w-4 h-4 text-emerald-600"></i>
          @else
            <i data-lucide="search" class="w-4 h-4 text-neutral-500"></i>
          @endif
        </span>
        <input type="text" name="code" id="trackingCodeInput" value="{{ $code ?? '' }}" 
          placeholder="{{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'Nhập mã vận đơn bưu tá (VD: GHTK-GFELJZTT, GHN-2C4E3DFF)...' : 'Nhập mã đơn hàng (VD: BEE-20260906-T7XF)...' }}" required
          class="w-full pl-10 pr-10 py-3.5 bg-neutral-50 border border-neutral-300 rounded-xl text-xs uppercase font-mono font-bold text-neutral-900 placeholder:text-neutral-500 focus:outline-none focus:border-neutral-950 focus:bg-white transition-all shadow-2xs">
        @if(!empty($code))
          <button type="button" onclick="document.getElementById('trackingCodeInput').value=''; document.getElementById('trackingCodeInput').focus();" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-700 p-1" title="Xóa">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        @endif
      </div>
      <button type="submit" class="px-8 py-3.5 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-bold tracking-wider uppercase rounded-xl transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 shrink-0">
        <i data-lucide="search" class="w-4 h-4"></i>
        <span>Tra Cứu</span>
      </button>
    </form>

    <!-- Smart Suggestion Chips -->
    <div class="mt-4 flex items-center justify-center gap-2 flex-wrap text-xs">
      <span class="text-neutral-600 font-semibold flex items-center gap-1">
        <i data-lucide="zap" class="w-3.5 h-3.5 text-amber-600"></i> Gợi ý nhanh:
      </span>
      @if(isset($userRecentOrders) && $userRecentOrders->isNotEmpty())
        @foreach($userRecentOrders->take(3) as $rOrder)
          <a href="{{ route('client.order-tracking', ['code' => $rOrder->order_code, 'type' => 'order']) }}" class="px-3 py-1 bg-neutral-100 hover:bg-neutral-200 border border-neutral-300 rounded-full font-mono text-neutral-800 font-medium transition-colors">
            #{{ $rOrder->order_code }}
          </a>
          @if($rOrder->tracking_code)
            <a href="{{ route('client.order-tracking', ['code' => $rOrder->tracking_code, 'type' => 'tracking']) }}" class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-300 hover:bg-emerald-100 rounded-full font-mono font-medium transition-colors">
              {{ $rOrder->tracking_code }}
            </a>
          @endif
        @endforeach
      @elseif(isset($sampleOrders) && $sampleOrders->isNotEmpty())
        @foreach($sampleOrders->take(2) as $sOrder)
          <a href="{{ route('client.order-tracking', ['code' => $sOrder->order_code, 'type' => 'order']) }}" class="px-3 py-1 bg-neutral-100 hover:bg-neutral-200 border border-neutral-300 rounded-full font-mono text-neutral-800 font-medium transition-colors">
            #{{ $sOrder->order_code }}
          </a>
          @if($sOrder->tracking_code)
            <a href="{{ route('client.order-tracking', ['code' => $sOrder->tracking_code, 'type' => 'tracking']) }}" class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-300 hover:bg-emerald-100 rounded-full font-mono font-medium transition-colors">
              {{ $sOrder->tracking_code }}
            </a>
          @endif
        @endforeach
      @else
        <a href="{{ route('client.order-tracking', ['code' => 'BEE-20260906-T7XF', 'type' => 'order']) }}" class="px-3 py-1 bg-neutral-100 hover:bg-neutral-200 border border-neutral-300 rounded-full font-mono text-neutral-800 font-medium transition-colors">
          BEE-20260906-T7XF
        </a>
        <a href="{{ route('client.order-tracking', ['code' => 'GHTK-GFELJZTT', 'type' => 'tracking']) }}" class="px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-300 hover:bg-emerald-100 rounded-full font-mono font-medium transition-colors">
          GHTK-GFELJZTT
        </a>
      @endif
    </div>
  </div>

  @if(isset($currentOrder) && $currentOrder)

    <!-- ========================================================================= -->
    <!-- 2. KẾT QUẢ KHỚP MÃ TRA CỨU -->
    <!-- ========================================================================= -->
    @if(isset($matchedBy) && $matchedBy === 'tracking')
      <div class="bg-emerald-50 border border-emerald-300 p-4 rounded-xl mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-emerald-950 shadow-2xs">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
            <i data-lucide="barcode" class="w-5 h-5"></i>
          </div>
          <div>
            <span class="font-bold text-emerald-800 uppercase tracking-wider block text-[10px]">Tra cứu thành công theo Mã Vận Đơn Bưu Tá</span>
            <span class="text-neutral-800">Kiện hàng: <strong class="font-mono text-emerald-900 text-sm">{{ $currentOrder->tracking_code }}</strong> • Thuộc đơn hàng: <strong class="font-mono text-neutral-900">#{{ $currentOrder->order_code }}</strong> ({{ $currentOrder->shipping_carrier ?: 'GHTK' }})</span>
          </div>
        </div>
        <a href="#carrierTrackingPassSection" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-bold shrink-0 transition-colors shadow-2xs flex items-center gap-1.5">
          <span>Xem Bưu Tá &amp; Trạm Quét</span>
          <i data-lucide="arrow-down" class="w-3.5 h-3.5"></i>
        </a>
      </div>
    @else
      <div class="bg-sky-50 border border-sky-300 p-4 rounded-xl mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-sky-950 shadow-2xs">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-sky-600 text-white flex items-center justify-center shrink-0 shadow-xs">
            <i data-lucide="receipt" class="w-5 h-5"></i>
          </div>
          <div>
            <span class="font-bold text-sky-800 uppercase tracking-wider block text-[10px]">Tra cứu thành công theo Mã Đơn Hàng</span>
            <span class="text-neutral-800">Đơn hàng: <strong class="font-mono text-neutral-950 text-sm">#{{ $currentOrder->order_code }}</strong> • Vận đơn bưu tá liên kết: <strong class="font-mono text-emerald-800 text-sm">{{ $currentOrder->tracking_code ?: 'Đang chuẩn bị điều phối bưu cục' }}</strong></span>
          </div>
        </div>
        @if($currentOrder->tracking_code)
          <a href="#carrierTrackingPassSection" class="px-4 py-2 bg-sky-700 hover:bg-sky-800 text-white rounded-lg font-bold shrink-0 transition-colors shadow-2xs flex items-center gap-1.5">
            <span>Xem Vận Đơn Bưu Tá</span>
            <i data-lucide="arrow-down" class="w-3.5 h-3.5"></i>
          </a>
        @endif
      </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 3. KHỐI THANH TOÁN VIETQR FINTECH (NẾU CHƯA TRẢ ĐỦ) -->
    <!-- ========================================================================= -->
    @if(in_array($currentOrder->payment_method, ['online', 'vietqr']) && $currentOrder->payment_status !== 'paid')
      @php
        $isDepositTrack = ($currentOrder->is_deposit_required && $currentOrder->deposit_status !== 'paid');
        $payAmountTrack = $isDepositTrack ? $currentOrder->deposit_amount : $currentOrder->total_amount;
        $vietQrUrl = "https://img.vietqr.io/image/TCB-77427842310105-compact2.png?amount=" . $payAmountTrack . "&addInfo=" . urlencode($currentOrder->order_code) . "&accountName=" . urlencode("NGUYEN XUAN BAC");
      @endphp
      <div class="bg-neutral-900 text-white p-6 md:p-8 rounded-2xl shadow-xl border border-amber-500/50 mb-8 relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
          
          <!-- Cột 1: QR Pass -->
          <div class="lg:col-span-5 text-center">
            <div class="p-4 bg-white rounded-2xl shadow-lg inline-block w-full max-w-[280px]">
              <div class="flex justify-between items-center mb-2 text-[10px] font-bold">
                <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded font-mono">VIETQR 24/7</span>
                <span class="px-2 py-0.5 bg-sky-100 text-sky-700 rounded font-mono">NAPAS 247</span>
              </div>
              <div class="p-2 bg-neutral-50 rounded-xl border border-neutral-200">
                <img src="{{ $vietQrUrl }}" alt="VietQR Payment Code" class="w-full h-auto rounded-lg mx-auto">
              </div>
              <span class="text-[11px] text-neutral-700 mt-2 block font-semibold">Quét bằng App mọi Ngân Hàng &amp; Ví Điện Tử</span>
            </div>
            <div class="mt-3">
              <a href="{{ $vietQrUrl }}" download="VietQR_{{ $currentOrder->order_code }}.png" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white/15 hover:bg-white/25 text-white rounded-full text-xs font-bold transition-colors">
                <i data-lucide="download" class="w-3.5 h-3.5 text-amber-400"></i>
                <span>Tải Ảnh Mã QR</span>
              </a>
            </div>
          </div>

          <!-- Cột 2: Chi tiết chuyển khoản -->
          <div class="lg:col-span-7 space-y-4 text-xs">
            <div class="flex items-center justify-between flex-wrap gap-2">
              <span class="px-3.5 py-1 bg-rose-500/30 border border-rose-400/50 text-rose-200 font-bold rounded-full text-xs tracking-wide">
                ● {{ $isDepositTrack ? 'CHỜ CHUYỂN TIỀN CỌC 50%' : 'CHỜ CHUYỂN KHOẢN' }}
              </span>
              <div class="px-3 py-1 bg-white/15 rounded-full border border-white/20 text-xs font-medium text-neutral-200">
                Giữ đơn hàng: <span id="vietqrCountdown" class="font-mono font-bold text-amber-300">14:59</span>
              </div>
            </div>

            <h3 class="font-serif-luxury text-xl md:text-2xl font-bold text-white">
              {{ $isDepositTrack ? 'Thanh Toán Tiền Cọc 50% VietQR' : 'Thanh Toán Chuyển Khoản VietQR' }}
            </h3>
            <p class="text-neutral-200 leading-relaxed text-xs">
              @if($isDepositTrack)
                Đơn hàng áp dụng chính sách cọc 50%. Quét mã QR bên cạnh để chuyển đúng số tiền cọc (<strong class="text-amber-300">{{ number_format($payAmountTrack, 0, ',', '.') }}₫</strong>). Phần còn lại {{ number_format($currentOrder->remaining_amount, 0, ',', '.') }}₫ sẽ thanh toán cho bưu tá khi nhận hàng.
              @else
                Mở ứng dụng ngân hàng bất kỳ để quét mã. Số tiền thanh toán và nội dung chuyển khoản đã được điền sẵn chuẩn xác 100%:
              @endif
            </p>

            <div class="p-4 rounded-xl bg-white/10 border border-white/20 backdrop-blur space-y-3">
              <div class="flex justify-between items-center pb-2.5 border-b border-white/15">
                <span class="text-neutral-300 font-medium">Ngân hàng thụ hưởng:</span>
                <strong class="text-white font-bold text-sm">Techcombank (TCB)</strong>
              </div>
              <div class="flex justify-between items-center pb-2.5 border-b border-white/15">
                <span class="text-neutral-300 font-medium">Tên chủ tài khoản:</span>
                <strong class="text-amber-300 font-bold tracking-wider text-sm">NGUYEN XUAN BAC</strong>
              </div>
              <div class="flex justify-between items-center pb-2.5 border-b border-white/15">
                <span class="text-neutral-300 font-medium">Số tài khoản:</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono text-white text-base font-bold" id="accNumberTxt">77427842310105</strong>
                  <button type="button" onclick="copyText('77427842310105', 'btnCopyAcc')" id="btnCopyAcc" class="px-2.5 py-1 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded text-xs transition-colors">
                    Copy
                  </button>
                </div>
              </div>
              <div class="flex justify-between items-center pb-2.5 border-b border-white/15">
                <span class="text-neutral-300 font-medium">{{ $isDepositTrack ? 'Số tiền cọc cần chuyển (50%):' : 'Số tiền cần chuyển:' }}</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono text-amber-300 text-lg font-bold">{{ number_format($payAmountTrack, 0, ',', '.') }}₫</strong>
                  <button type="button" onclick="copyText('{{ $payAmountTrack }}', 'btnCopyAmount')" id="btnCopyAmount" class="px-2.5 py-1 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded text-xs transition-colors">
                    Copy
                  </button>
                </div>
              </div>
              <div class="flex justify-between items-center pt-1">
                <span class="text-neutral-300 font-semibold">Nội dung chuyển khoản:</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono px-2.5 py-1 bg-amber-400/25 border border-amber-400/50 text-amber-300 font-bold rounded text-xs tracking-wider">
                    {{ $currentOrder->order_code }}
                  </strong>
                  <button type="button" onclick="copyText('{{ $currentOrder->order_code }}', 'btnCopyCode')" id="btnCopyCode" class="px-3 py-1 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded text-xs transition-colors">
                    Copy Mã
                  </button>
                </div>
              </div>
            </div>

            <form action="{{ route('client.order-tracking.confirm-transfer', $currentOrder->order_code) }}" method="POST" class="flex gap-2.5 flex-wrap pt-1">
              @csrf
              <button type="submit" class="flex-grow py-3 px-5 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded-xl transition-all shadow text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>Tôi Đã Chuyển Khoản Thành Công</span>
              </button>
              <a href="{{ route('client.home') }}" class="py-3 px-5 bg-white/15 hover:bg-white/25 text-white rounded-xl text-xs font-bold transition-colors">
                Tiếp Tục Mua Sắm
              </a>
            </form>
          </div>

        </div>
      </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 4. KHỐI THẺ VẬN ĐƠN BƯU TÁ & THEO DÕI HÀNH TRÌNH (#carrierTrackingPassSection) -->
    <!-- ========================================================================= -->
    @php
      $carrier = mb_strtolower((string)$currentOrder->shipping_carrier, 'UTF-8');
      $isGhtk = str_contains($carrier, 'ghtk') || str_contains($carrier, 'tiết kiệm');
      $isGhn = str_contains($carrier, 'ghn') || str_contains($carrier, 'nhanh');
      $isViettel = str_contains($carrier, 'viettel') || str_contains($carrier, 'vtp');
      $isJt = str_contains($carrier, 'j&t') || str_contains($carrier, 'jt');

      $carrierTitle = $currentOrder->shipping_carrier ?: 'Giao Hàng Tiết Kiệm (GHTK)';
      $carrierShort = $isGhtk ? 'GHTK' : ($isGhn ? 'GHN' : ($isViettel ? 'Viettel Post' : ($isJt ? 'J&T' : 'BeeStyle Express')));
      $carrierHotline = $isGhtk ? '1900 6092' : ($isGhn ? '1900 636677' : ($isViettel ? '1900 8095' : ($isJt ? '1900 1088' : '1900 8888')));

      $step = $currentOrder->status_step ?? 1;
      $created = $currentOrder->created_at;
      $confirmed = $currentOrder->confirmed_at ?: ($created ? $created->copy()->addMinutes(11) : now());
      $processing = $currentOrder->processing_at ?: ($confirmed ? $confirmed->copy()->addMinutes(15) : now());
      $shipping = $currentOrder->shipping_at ?: ($processing ? $processing->copy()->addMinutes(30) : now());
      $delivered = $currentOrder->delivered_at ?: ($shipping ? $shipping->copy()->addHours(24) : now());
      $completed = $currentOrder->completed_at ?: ($delivered ? $delivered->copy()->addHours(2) : now());

      $logisticsCheckpoints = [];
      $logisticsCheckpoints[] = [
        'title' => 'Khởi tạo đơn hàng & tiếp nhận bưu gửi',
        'desc' => 'Đơn hàng #' . $currentOrder->order_code . ' đã ghi nhận. Mã vận đơn ' . ($currentOrder->tracking_code ?: 'N/A') . ' đã được phân bổ thành công.',
        'hub' => 'Cổng Đơn Hàng BeeStyle Logistics',
        'time' => $created,
        'icon' => 'clipboard-list',
        'done' => true,
      ];

      if ($step >= 2) {
        $logisticsCheckpoints[] = [
          'title' => 'Shop xác nhận & in phiếu giao nhận bưu cục',
          'desc' => 'Kho đã duyệt địa chỉ người nhận, in phiếu đóng gói và tạo lệnh hẹn lấy hàng tới ' . $carrierShort . '.',
          'hub' => 'Kho Tổng BeeStyle (Cầu Giấy, Hà Nội)',
          'time' => $confirmed,
          'icon' => 'clipboard-check',
          'done' => true,
        ];
      }

      if ($step >= 3) {
        $logisticsCheckpoints[] = [
          'title' => 'Đóng gói hoàn tất & dán nhãn vận đơn [' . ($currentOrder->tracking_code ?: 'TEM CHÍNH HÃNG') . ']',
          'desc' => 'Kiện hàng đã qua kiểm tra chất lượng QC, đóng thùng carton chống sốc và dán mã vạch bưu tá.',
          'hub' => 'Kho Đóng Gói Phân Loại BeeStyle',
          'time' => $processing,
          'icon' => 'package-check',
          'done' => true,
        ];
      }

      if ($step >= 4) {
        $logisticsCheckpoints[] = [
          'title' => 'Bưu tá ' . $carrierShort . ' đã tiếp nhận kiện hàng tại kho',
          'desc' => 'Bưu tá Nguyễn Văn Tuấn (Mã NV: ' . $carrierShort . '-8821) đã quét mã lấy hàng thành công.',
          'hub' => 'Bưu Cục Lấy Hàng ' . $carrierShort . ' Cầu Giấy',
          'time' => $shipping,
          'icon' => 'truck',
          'done' => true,
        ];
        $logisticsCheckpoints[] = [
          'title' => 'Nhập Kho Trung Chuyển ' . $carrierShort . ' SOC',
          'desc' => 'Kiện hàng đã nhập kho trung chuyển phân loại tự động tốc độ cao theo tuyến giao.',
          'hub' => 'Trung Tâm Khai Thác ' . $carrierShort . ' Miền Bắc',
          'time' => $shipping->copy()->addHours(3)->addMinutes(15),
          'icon' => 'warehouse',
          'done' => true,
        ];
        $logisticsCheckpoints[] = [
          'title' => 'Đến bưu cục phát - Bưu tá đang di chuyển giao hàng',
          'desc' => 'Bưu tá đang phát hàng tới: ' . $currentOrder->shipping_address . ' (' . ($currentOrder->city ?: 'Hà Nội') . '). Vui lòng chú ý điện thoại.',
          'hub' => 'Bưu Cục Phát ' . ($currentOrder->city ?: 'Hà Nội'),
          'time' => $delivered ? $delivered->copy()->subHours(3) : $shipping->copy()->addHours(14),
          'icon' => 'navigation',
          'done' => true,
        ];
      }

      if ($step >= 5) {
        $logisticsCheckpoints[] = [
          'title' => 'GIAO HÀNG THÀNH CÔNG - KHÁCH ĐÃ KÝ NHẬN',
          'desc' => 'Khách hàng ' . $currentOrder->customer_name . ' đã nhận đủ bưu phẩm. Tiền thu COD: ' . ($currentOrder->payment_status === 'paid' ? '0₫ (Đã thanh toán trước)' : number_format($currentOrder->total_amount, 0, ',', '.') . '₫') . '.',
          'hub' => 'Địa chỉ người nhận: ' . $currentOrder->shipping_address,
          'time' => $delivered,
          'icon' => 'check-circle-2',
          'done' => true,
          'pod_url' => $currentOrder->delivery_proof_url,
          'pod_note' => $currentOrder->delivery_proof_note,
        ];
      }

      if ($step >= 6) {
        $logisticsCheckpoints[] = [
          'title' => 'Hoàn tất hành trình bưu gửi & đối soát',
          'desc' => 'Đơn vị vận chuyển đã hoàn tất đối soát bưu tá bưu cục và đóng trạng thái luân chuyển thành công.',
          'hub' => 'Hệ Thống Đối Soát Vận Chuyển ' . $carrierShort,
          'time' => $completed,
          'icon' => 'shield-check',
          'done' => true,
        ];
      }

      $logisticsCheckpoints = array_reverse($logisticsCheckpoints);
    @endphp

    <!-- THẺ VẬN ĐƠN BƯU TÁ (LOGISTICS BOARDING PASS) -->
    <div id="carrierTrackingPassSection" class="bg-neutral-950 text-white p-6 md:p-8 rounded-2xl shadow-xl border border-neutral-800 mb-8 scroll-mt-24">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
        
        <div class="lg:col-span-7 space-y-3.5">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="px-3.5 py-1 bg-white text-neutral-950 font-bold rounded-full text-xs shadow-xs">
              {{ $carrierTitle }}
            </span>
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 font-bold rounded-full text-xs flex items-center gap-1">
              <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400"></i> ĐÃ ĐỒNG BỘ TRẠM THỰC
            </span>
            <span class="text-neutral-300 text-xs font-medium">
              Hotline hãng: <strong class="text-white font-bold">{{ $carrierHotline }}</strong>
            </span>
          </div>

          <div>
            <span class="text-neutral-300 text-xs uppercase tracking-wider block font-bold">MÃ VẬN ĐƠN BƯU TÁ:</span>
            <div class="flex items-center gap-3 mt-1.5 flex-wrap">
              <h2 class="font-mono text-2xl md:text-3xl font-bold text-amber-400 tracking-wider">
                {{ $currentOrder->tracking_code ?: 'GHTK-' . strtoupper(substr(md5($currentOrder->order_code), 0, 8)) }}
              </h2>
              <button type="button" onclick="copyText('{{ $currentOrder->tracking_code ?: 'GHTK-' . strtoupper(substr(md5($currentOrder->order_code), 0, 8)) }}', 'btnCopyTracking')" id="btnCopyTracking" class="px-3.5 py-1.5 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded-full text-xs transition-colors shadow-xs">
                Copy Mã Vận Đơn
              </button>
            </div>
          </div>

          <div class="flex items-center gap-3 md:gap-5 text-xs text-neutral-200 flex-wrap pt-1 font-medium">
            <span>Đơn hàng: <strong class="text-white font-bold">#{{ $currentOrder->order_code }}</strong></span>
            <span class="text-neutral-600">•</span>
            <span>Đặt lúc: <strong class="text-white font-bold">{{ $currentOrder->created_at ? $currentOrder->created_at->format('d/m/Y H:i') : '' }}</strong></span>
            <span class="text-neutral-600">•</span>
            <span>Kiện hàng: <strong class="text-amber-300 font-bold">{{ $currentOrder->items->count() }} sản phẩm ({{ $currentOrder->items->sum('quantity') }} cái)</strong></span>
          </div>
        </div>

        <div class="lg:col-span-5 bg-white/10 border border-white/15 p-5 rounded-xl space-y-3 text-xs backdrop-blur-xs">
          <div class="flex justify-between items-center">
            <span class="text-neutral-200 font-medium">Trạng thái vận chuyển:</span>
            <span class="px-3 py-1 {{ $currentOrder->shipping_status === 'completed' || $currentOrder->shipping_status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-neutral-950' }} rounded-full font-bold text-xs shadow-2xs">
              {{ $currentOrder->status_label }}
            </span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-neutral-200 font-medium">Tiền thu người nhận (COD):</span>
            <strong class="text-amber-300 font-mono text-base font-bold">
              @if($currentOrder->payment_status === 'paid')
                0₫ (Đã thanh toán trước)
              @elseif($currentOrder->is_deposit_required)
                {{ number_format($currentOrder->remaining_amount ?: ($currentOrder->total_amount - $currentOrder->deposit_amount), 0, ',', '.') }}₫
              @else
                {{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫
              @endif
            </strong>
          </div>
          @if($currentOrder->is_deposit_required)
            <div class="flex justify-between items-center text-amber-200">
              <span class="font-medium">Đã đặt cọc trước (50%):</span>
              <strong class="font-mono text-white font-bold">{{ number_format($currentOrder->deposit_amount, 0, ',', '.') }}₫ ({{ $currentOrder->deposit_status === 'paid' ? 'Đã cọc' : 'Chờ cọc' }})</strong>
            </div>
          @endif
          <div class="flex items-center gap-2 pt-2 border-t border-white/15 flex-wrap">
            <button type="button" onclick="window.print()" class="flex-grow py-2.5 px-3 bg-white text-neutral-950 font-bold rounded-lg hover:bg-neutral-100 transition-colors text-center text-xs shadow-xs flex items-center justify-center gap-1.5">
              <i data-lucide="printer" class="w-4 h-4"></i>
              <span>In Vận Đơn</span>
            </button>
            @if($currentOrder->tracking_url)
              <a href="{{ $currentOrder->tracking_url }}" target="_blank" class="py-2.5 px-3 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-bold transition-colors">
                Cổng {{ $carrierShort }}
              </a>
            @endif
            @if((!Auth::check() || Auth::id() === $currentOrder->user_id || !$currentOrder->user_id) && $currentOrder->canBeCancelledByCustomer())
              <button type="button" onclick="openCancelModal()" class="py-2.5 px-3 bg-rose-600/30 hover:bg-rose-600 text-rose-200 hover:text-white rounded-lg text-xs font-bold transition-colors border border-rose-500/40">
                Hủy Đơn
              </button>
            @endif
          </div>
        </div>

      </div>

      <!-- Bưu Tá Phụ Trách & Nút Mở Lộ Trình Checkpoints -->
      <div class="mt-6 pt-4 border-t border-white/15 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-amber-400 text-neutral-950 flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
            <i data-lucide="shield-check" class="w-5 h-5"></i>
          </div>
          <div class="text-xs">
            <div class="flex items-center gap-2">
              <strong class="text-white font-bold text-sm">Bưu tá: Nguyễn Văn Tuấn</strong>
              <span class="text-amber-300 font-bold text-xs">★ 4.9 (Đã xác minh)</span>
            </div>
            <span class="text-neutral-300 text-xs">Mã NV: <strong class="text-white">{{ $carrierShort }}-8821</strong> • Hotline trạm: <strong class="text-white">{{ $carrierHotline }}</strong></span>
          </div>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
          <a href="tel:0988123456" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
            <span>Gọi Bưu Tá 0988.123.456</span>
          </a>
          <button type="button" onclick="toggleCheckpoints()" class="px-3.5 py-2 bg-white/15 hover:bg-white/25 text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5 border border-white/20">
            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-300"></i>
            <span>Lộ Trình {{ count($logisticsCheckpoints) }} Trạm Quét</span>
          </button>
        </div>
      </div>

      <!-- LỘ TRÌNH CHI TIẾT (DRAWER CHECKPOINTS) -->
      <div id="checkpointsDrawer" class="{{ (isset($matchedBy) && $matchedBy === 'tracking') ? 'block' : 'hidden' }} mt-6 pt-5 border-t border-white/15">
        <div class="bg-white text-neutral-900 p-6 md:p-7 rounded-xl space-y-4 shadow-md">
          <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
            <h4 class="font-serif-luxury text-base md:text-lg font-bold text-neutral-900 flex items-center gap-2">
              <i data-lucide="route" class="w-5 h-5 text-amber-600"></i>
              <span>Lịch Sử Luân Chuyển Bưu Kiện Thời Gian Thực</span>
            </h4>
            <span class="text-xs font-semibold text-neutral-600">Cập nhật bởi {{ $carrierShort }} API</span>
          </div>
          <div class="relative border-l-2 border-neutral-300 ml-4 space-y-6 pt-2">
            @foreach($logisticsCheckpoints as $cIndex => $cp)
              @php $isLatest = ($cIndex === 0); @endphp
              <div class="relative pl-6">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $isLatest ? 'bg-emerald-600 ring-4 ring-emerald-100' : 'bg-neutral-400' }}"></div>
                <div class="p-4 rounded-xl border {{ $isLatest ? 'border-emerald-300 bg-emerald-50/60' : 'border-neutral-200 bg-neutral-50' }} text-xs">
                  <div class="flex justify-between items-start flex-wrap gap-1 mb-1.5">
                    <strong class="font-bold text-sm {{ $isLatest ? 'text-emerald-950' : 'text-neutral-900' }}">
                      {{ $cp['title'] }}
                      @if($isLatest) <span class="px-2 py-0.5 bg-emerald-600 text-white rounded-full text-[10px] font-bold ml-1.5">MỚI NHẤT</span> @endif
                    </strong>
                    <span class="text-neutral-700 font-mono font-bold text-xs">{{ $cp['time'] ? $cp['time']->format('d/m/Y H:i') : '' }}</span>
                  </div>
                  <p class="text-neutral-700 text-xs leading-relaxed mb-2 font-normal">{{ $cp['desc'] }}</p>
                  <div class="flex items-center justify-between flex-wrap gap-2 text-xs text-neutral-600 font-medium">
                    <span>Trạm ghi nhận: <strong class="text-neutral-900 font-semibold">{{ $cp['hub'] }}</strong></span>
                    @if(!empty($cp['pod_url']))
                      <button type="button" onclick="openPodModal()" class="text-emerald-700 hover:text-emerald-800 font-bold flex items-center gap-1 underline underline-offset-2">
                        <i data-lucide="camera" class="w-3.5 h-3.5"></i> Xem Ảnh Giao Hàng (POD)
                      </button>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 5. KHỐI THÔNG BÁO HÀNH ĐỘNG GIAO HÀNG / HOÀN TẤT / ĐỔI TRẢ (CALLOUT) -->
    <!-- ========================================================================= -->
    @php
      $isDeliveringOrDelivered = in_array($currentOrder->shipping_status, ['shipping', 'delivered']) || in_array($currentOrder->status_step, [4, 5]);
      $hasActiveReturn = $currentOrder->latestReturn && in_array($currentOrder->latestReturn->status, ['pending', 'approved', 'received', 'completed']);
    @endphp

    @if($isDeliveringOrDelivered && $currentOrder->shipping_status !== 'completed' && $currentOrder->shipping_status !== 'cancelled' && !$hasActiveReturn)
      <!-- THÔNG BÁO BƯU TÁ ĐÃ PHÁT: 2 NÚT HÀNH ĐỘNG SONG SONG RÕ RÀNG -->
      <div class="bg-gradient-to-r from-emerald-50 via-white to-sky-50 border-2 border-emerald-500 rounded-2xl shadow-md p-6 md:p-7 mb-8 relative overflow-hidden">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm text-xl font-bold">
              <i data-lucide="package-check" class="w-6 h-6"></i>
            </div>
            <div>
              <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                <span class="px-2.5 py-0.5 bg-emerald-600 text-white text-[11px] font-bold rounded-full uppercase tracking-wider flex items-center gap-1">
                  <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> BƯU TÁ ĐÃ PHÁT KIỆN HÀNG ĐẾN BẠN
                </span>
                @if($currentOrder->tracking_code)
                  <span class="px-2.5 py-0.5 bg-white border border-neutral-300 font-mono text-neutral-800 text-xs font-bold rounded-full">
                    Mã vận đơn: {{ $currentOrder->tracking_code }}
                  </span>
                @endif
              </div>
              <h3 class="font-serif-luxury text-lg md:text-xl font-bold text-neutral-950 mb-1">
                Kiện hàng đã được giao đến tay bạn! Vui lòng đồng kiểm tra &amp; xác nhận
              </h3>
              <p class="text-neutral-700 text-xs leading-relaxed max-w-2xl font-normal">
                Bưu tá <strong>{{ $currentOrder->shipping_carrier ?: 'Giao Hàng Tiết Kiệm (GHTK)' }}</strong> ghi nhận đã phát kiện hàng thành công. Quý khách vui lòng đồng kiểm tra trang phục.
                Nếu hoàn toàn hài lòng, hãy bấm <strong>"Đã Nhận Được Hàng"</strong> để hoàn tất đơn và nhận điểm thưởng. 
                Nếu sản phẩm có lỗi, chật rộng hoặc bạn muốn từ chối nhận/hoàn tiền, hãy bấm <strong>"Hủy Hàng &amp; Hoàn Tiền"</strong> để hệ thống hỗ trợ ngay lập tức.
              </p>
            </div>
          </div>

          <!-- 2 NÚT HÀNH ĐỘNG NẰM CẠNH NHAU (SIDE-BY-SIDE) -->
          <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap w-full lg:w-auto shrink-0 justify-end">
            <!-- Nút 1: ĐÃ NHẬN ĐƯỢC HÀNG -->
            <button type="button" onclick="openDeliveredModal()" class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-sm hover:shadow flex items-center justify-center gap-2">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              <span>Đã Nhận Được Hàng</span>
            </button>

            <!-- Nút 2: HỦY HÀNG & HOÀN TIỀN -->
            <button type="button" onclick="openRefundModal()" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-rose-50 border-2 border-rose-500 text-rose-700 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-sm hover:shadow flex items-center justify-center gap-2">
              <i data-lucide="hand-coins" class="w-4 h-4 text-rose-600"></i>
              <span>Hủy Hàng &amp; Hoàn Tiền</span>
            </button>
          </div>
        </div>
      </div>
    @elseif($currentOrder->shipping_status === 'completed')
      <!-- THÔNG BÁO HOÀN TẤT ĐƠN HÀNG -->
      <div class="bg-emerald-50 border-l-4 border-emerald-600 p-5 rounded-2xl shadow-2xs mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <i data-lucide="check-circle-2" class="w-7 h-7 text-emerald-600 shrink-0"></i>
          <div>
            <strong class="text-emerald-950 font-bold block text-sm uppercase">Đơn Hàng Đã Giao Thành Công &amp; Hoàn Tất</strong>
            <span class="text-neutral-800 text-xs">Kiện hàng đã được bạn xác nhận hoàn tất{{ $currentOrder->completed_at ? ' lúc ' . $currentOrder->completed_at->format('H:i, d/m/Y') : '' }}. Cảm ơn bạn đã tin chọn BeeStyle!</span>
          </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          @if($currentOrder->items->first() && $currentOrder->items->first()->product_id)
            @php
              $otFirstItem = $currentOrder->items->first();
              $otThumb = asset($otFirstItem->product->primaryImage->image_path ?? $otFirstItem->product->thumbnail ?? 'assets/img/products/1.png');
            @endphp
            <button type="button" onclick="openGlobalReviewModal({{ $otFirstItem->product_id }}, '{{ addslashes($otFirstItem->product_name) }}', '{{ $otThumb }}', '{{ $currentOrder->order_code }}')" class="px-4 py-2 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded-xl text-xs transition-colors flex items-center gap-1.5 shadow-2xs">
              <i data-lucide="star" class="w-4 h-4 fill-neutral-950"></i>
              <span>Đánh Giá Sản Phẩm (Tặng Voucher)</span>
            </button>
          @endif
          @if($currentOrder->canBeReturnedByCustomer())
            <button type="button" onclick="openRefundModal()" class="px-4 py-2 bg-white hover:bg-neutral-100 border border-neutral-300 text-neutral-800 font-bold rounded-xl text-xs transition-colors flex items-center gap-1.5">
              <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
              <span>Yêu Cầu Đổi Trả (7 Ngày)</span>
            </button>
          @endif
        </div>
      </div>
    @elseif($hasActiveReturn)
      <!-- THÔNG BÁO YÊU CẦU ĐỔI TRẢ ĐANG XỬ LÝ -->
      <div class="bg-amber-50 border-l-4 border-amber-500 p-5 rounded-2xl shadow-2xs mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-amber-400 text-neutral-950 flex items-center justify-center shrink-0 shadow-2xs">
            <i data-lucide="hand-coins" class="w-5 h-5"></i>
          </div>
          <div>
            <div class="flex items-center gap-2 mb-1 flex-wrap">
              <strong class="text-neutral-950 font-bold text-sm">Yêu Cầu Hủy Hàng / Hoàn Tiền Đang Được Xử Lý</strong>
              <span class="px-2 py-0.5 bg-amber-200 text-amber-900 font-mono font-bold text-xs rounded">#{{ $currentOrder->latestReturn->return_code }}</span>
              <span class="px-2 py-0.5 {{ $currentOrder->latestReturn->status === 'completed' ? 'bg-emerald-600 text-white' : 'bg-sky-600 text-white' }} font-bold text-xs rounded">
                {{ $currentOrder->latestReturn->status === 'completed' ? 'Đã Hoàn Tiền' : 'Chờ CSKH Duyệt' }}
              </span>
            </div>
            <p class="text-neutral-700 text-xs">
              Lý do: <strong class="text-neutral-900">{{ $currentOrder->latestReturn->reason }}</strong>
              • Số tiền hoàn dự kiến: <strong class="text-rose-600 font-bold font-mono">{{ number_format($currentOrder->latestReturn->refund_amount, 0, ',', '.') }}₫</strong>
              @if($currentOrder->latestReturn->bank_name)
                • Nhận qua: <strong class="text-neutral-900">{{ $currentOrder->latestReturn->bank_name }} ({{ $currentOrder->latestReturn->bank_account_number }})</strong>
              @endif
            </p>
          </div>
        </div>
        <span class="px-3 py-1.5 bg-white border border-amber-200 text-neutral-800 text-xs font-semibold rounded-full shrink-0 flex items-center gap-1">
          <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i> Xử lý trong 24h làm việc
        </span>
      </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 6. TIẾN ĐỘ 6 BƯỚC ĐƠN HÀNG (TIMELINE TRACKER) -->
    <!-- ========================================================================= -->
    <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200/90 shadow-sm mb-8">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 mb-6 border-b border-neutral-200">
        <div>
          <span class="text-xs tracking-widest text-amber-800 uppercase font-mono font-bold">MÃ ĐƠN HÀNG #{{ $currentOrder->order_code }}</span>
          <h3 class="font-serif-luxury text-xl font-bold text-neutral-900 mt-0.5">Tiến Độ Xử Lý &amp; Vận Chuyển</h3>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          @if(in_array($currentOrder->shipping_status, ['shipping', 'delivered']) || in_array($currentOrder->status_step, [4, 5]))
            <button type="button" onclick="openDeliveredModal()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1.5">
              <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
              <span>Đã Nhận Được Hàng</span>
            </button>
            <button type="button" onclick="openRejectModal()" class="px-4 py-2 border border-rose-500 text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
              <i data-lucide="truck" class="w-3.5 h-3.5"></i>
              <span>Không Nhận Hàng</span>
            </button>
          @endif
          @if($currentOrder->canBeCancelledByCustomer())
            <button type="button" onclick="openCancelModal()" class="px-4 py-2 border border-neutral-300 text-neutral-700 hover:text-rose-600 hover:border-rose-300 rounded-xl text-xs font-bold transition-all">
              Hủy Đơn
            </button>
          @endif
        </div>
      </div>

      <!-- 6 Bước Tiến Độ (Độ tương phản cao, chữ không bị mờ) -->
      @php
        $steps = [
          1 => ['label' => 'Chờ Xác Nhận', 'desc' => 'Đơn mới tạo'],
          2 => ['label' => 'Đã Xác Nhận', 'desc' => 'Đã duyệt đơn'],
          3 => ['label' => 'Đang Đóng Gói', 'desc' => 'Kho kiểm tra & dán tem'],
          4 => ['label' => 'Đang Giao Hàng', 'desc' => 'Bưu tá đang phát'],
          5 => ['label' => 'Đã Giao Hàng', 'desc' => 'Khách nhận kiện hàng'],
          6 => ['label' => 'Hoàn Tất', 'desc' => 'Đơn hàng thành công'],
        ];
        $currentStep = $currentOrder->status_step ?? 1;
      @endphp
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-center text-xs">
        @foreach($steps as $sNum => $sInfo)
          @php
            $isDone = $currentStep > $sNum;
            $isActive = $currentStep == $sNum;
          @endphp
          <div class="p-3.5 rounded-xl border transition-all {{ $isActive ? 'border-neutral-950 bg-neutral-950 text-white shadow-md' : ($isDone ? 'border-emerald-300 bg-emerald-50/70 text-neutral-900' : 'border-neutral-200 bg-neutral-50 text-neutral-800') }} space-y-1.5">
            <div class="w-7 h-7 rounded-full mx-auto flex items-center justify-center font-bold text-xs {{ $isActive ? 'bg-amber-400 text-neutral-950' : ($isDone ? 'bg-emerald-600 text-white' : 'bg-neutral-200 text-neutral-800') }}">
              {{ $isDone ? '✓' : $sNum }}
            </div>
            <strong class="block font-bold text-xs {{ $isActive ? 'text-white' : ($isDone ? 'text-emerald-950' : 'text-neutral-900') }}">
              {{ $sInfo['label'] }}
            </strong>
            <span class="text-[11px] block font-medium {{ $isActive ? 'text-amber-200' : ($isDone ? 'text-emerald-800' : 'text-neutral-600') }}">
              {{ $sInfo['desc'] }}
            </span>
          </div>
        @endforeach
      </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 7. THÔNG TIN ĐƠN HÀNG & DANH SÁCH TÁC PHẨM -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start mb-8">
      
      <!-- Cột 1: Sản phẩm (7 cols) -->
      <div class="md:col-span-7 bg-white p-6 md:p-7 rounded-2xl border border-neutral-200/90 shadow-sm space-y-4">
        <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 pb-3 border-b border-neutral-200">Các Tác Phẩm Trong Đơn Hàng</h3>
        <div class="divide-y divide-neutral-100">
          @foreach($currentOrder->items as $item)
            <div class="py-3.5 flex gap-3.5 items-center text-xs">
              <div class="w-16 h-18 bg-neutral-100 rounded-xl overflow-hidden shrink-0 border border-neutral-200">
                <img src="{{ asset($item->product->primaryImage->image_path ?? $item->product->thumbnail ?? 'assets/img/products/1.png') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
              </div>
              <div class="flex-grow min-w-0">
                <h4 class="font-bold text-neutral-950 text-sm line-clamp-1">{{ $item->product_name }}</h4>
                <div class="flex items-center gap-2 text-xs text-neutral-600 mt-1 flex-wrap">
                  <span>Màu: <strong class="text-neutral-900">{{ $item->color ?? 'Tiêu chuẩn' }}</strong></span>
                  <span>•</span>
                  <span>Size: <strong class="px-1.5 py-0.2 bg-neutral-100 border border-neutral-300 text-neutral-900 rounded font-bold">{{ $item->size ?? 'M' }}</strong></span>
                  <span>•</span>
                  <span>SL: <strong class="text-neutral-900">x{{ $item->quantity }}</strong></span>
                </div>
                <p class="text-neutral-500 text-[11px] mt-0.5">Đơn giá: {{ number_format($item->price, 0, ',', '.') }}₫</p>
                @if($currentOrder->shipping_status === 'completed' && $item->product_id)
                  @php
                    $itemThumb = asset($item->product->primaryImage->image_path ?? $item->product->thumbnail ?? 'assets/img/products/1.png');
                  @endphp
                  <div class="mt-1.5">
                    <button type="button" onclick="openGlobalReviewModal({{ $item->product_id }}, '{{ addslashes($item->product_name) }}', '{{ $itemThumb }}', '{{ $currentOrder->order_code }}')" class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 rounded-lg text-[10px] font-bold transition-colors">
                      <i data-lucide="star" class="w-3 h-3 text-amber-500 fill-amber-500"></i> Đánh giá nhận voucher
                    </button>
                  </div>
                @endif
              </div>
              <span class="font-serif-luxury text-base font-bold text-neutral-950 min-w-[90px] text-right">
                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
              </span>
            </div>
          @endforeach
        </div>

        <div class="pt-4 border-t border-neutral-200 text-xs space-y-2 text-neutral-700">
          <div class="flex justify-between items-center">
            <span class="font-medium">Tạm tính tiền hàng:</span>
            <span class="font-bold text-neutral-900 text-sm font-mono">{{ number_format($currentOrder->subtotal, 0, ',', '.') }}₫</span>
          </div>
          @if($currentOrder->discount_amount > 0)
            <div class="flex justify-between items-center text-rose-700 font-semibold">
              <span>Ưu đãi Voucher ({{ $currentOrder->coupon_code ?? 'GIẢM GIÁ' }}):</span>
              <span class="font-mono font-bold">-{{ number_format($currentOrder->discount_amount, 0, ',', '.') }}₫</span>
            </div>
          @endif
          <div class="flex justify-between items-center">
            <span class="font-medium">Phí vận chuyển bưu tá:</span>
            <span class="font-bold text-neutral-900 font-mono">{{ $currentOrder->shipping_fee > 0 ? number_format($currentOrder->shipping_fee, 0, ',', '.') . '₫' : 'MIỄN PHÍ' }}</span>
          </div>
          <div class="flex justify-between items-baseline pt-3 border-t border-neutral-200 font-bold text-neutral-950 text-sm">
            <span>Tổng thanh toán:</span>
            <span class="font-serif-luxury text-2xl font-bold text-rose-600">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</span>
          </div>
        </div>
      </div>

      <!-- Cột 2: Thông tin giao nhận (5 cols) -->
      <div class="md:col-span-5 bg-white p-6 md:p-7 rounded-2xl border border-neutral-200/90 shadow-sm space-y-4 text-xs">
        <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 pb-3 border-b border-neutral-200">Thông Tin Giao Nhận</h3>
        <div>
          <span class="text-neutral-600 uppercase text-[11px] tracking-wider font-bold block mb-1">Người nhận:</span>
          <p class="font-bold text-neutral-950 text-sm">{{ $currentOrder->customer_name }} — <span class="font-mono">{{ $currentOrder->customer_phone }}</span></p>
        </div>
        <div>
          <span class="text-neutral-600 uppercase text-[11px] tracking-wider font-bold block mb-1">Địa chỉ giao hàng:</span>
          <p class="text-neutral-800 font-medium leading-relaxed">{{ $currentOrder->shipping_address }}{{ $currentOrder->city ? ', ' . $currentOrder->city : '' }}</p>
        </div>
        <div>
          <span class="text-neutral-600 uppercase text-[11px] tracking-wider font-bold block mb-1">Phương thức thanh toán:</span>
          <p class="text-neutral-950 font-bold uppercase">{{ $currentOrder->payment_method_name ?? $currentOrder->payment_method }}</p>
        </div>
        <div>
          <span class="text-neutral-600 uppercase text-[11px] tracking-wider font-bold block mb-1">Trạng thái thanh toán:</span>
          <span class="inline-block mt-0.5 px-3 py-1 {{ $currentOrder->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-900 border border-amber-300' }} rounded-full font-bold text-xs">
            {{ $currentOrder->payment_status_label ?? $currentOrder->payment_status }}
          </span>
        </div>
        @if($currentOrder->notes)
          <div class="pt-2 border-t border-neutral-200">
            <span class="text-neutral-600 uppercase text-[11px] tracking-wider font-bold block mb-1">Ghi chú của khách:</span>
            <p class="text-neutral-700 italic font-medium bg-neutral-50 p-2.5 rounded-lg border border-neutral-200">"{{ $currentOrder->notes }}"</p>
          </div>
        @endif
      </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 8. HỆ THỐNG MODAL THUẦN TAILWIND CSS (ĐỒNG BỘ 100%, KHÔNG DÙNG BOOTSTRAP) -->
    <!-- ========================================================================= -->

    <!-- MODAL 1: XÁC NHẬN ĐÃ NHẬN ĐỦ KIỆN HÀNG -->
    <div id="modalConfirmDelivered" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
      <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden transform transition-all">
        <form action="{{ route('client.order-tracking.confirm-delivered', $currentOrder->order_code) }}" method="POST">
          @csrf
          <div class="flex items-center justify-between p-5 border-b border-neutral-200 bg-neutral-50">
            <h3 class="font-serif-luxury text-lg font-bold text-emerald-800 flex items-center gap-2">
              <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
              <span>Xác Nhận Đã Nhận Đủ Kiện Hàng</span>
            </h3>
            <button type="button" onclick="closeDeliveredModal()" class="text-neutral-400 hover:text-neutral-900 p-1">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <div class="p-6 text-xs text-neutral-700 space-y-3.5">
            <p class="leading-relaxed">Xác nhận bạn đã đồng kiểm tra kiện hàng cùng bưu tá, trang phục nguyên vẹn tem mác, chuẩn size mẫu và đã thanh toán đủ tiền hàng.</p>
            <div class="p-3.5 bg-emerald-50/80 rounded-xl border border-emerald-200 text-neutral-900">
              Đơn hàng: <strong class="font-mono text-emerald-950">#{{ $currentOrder->order_code }}</strong> ({{ $currentOrder->items->count() }} sản phẩm)
            </div>
          </div>
          <div class="p-4 border-t border-neutral-200 bg-neutral-50 flex justify-end gap-2.5">
            <button type="button" onclick="closeDeliveredModal()" class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded-xl text-xs font-bold transition-colors">Đóng</button>
            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition-colors">Xác Nhận Đã Nhận</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 2: YÊU CẦU HỦY HÀNG & HOÀN TIỀN / ĐỔI TRẢ (THUẦN TAILWIND) -->
    <div id="modalRequestRefund" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden overflow-y-auto">
      <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden my-8 max-h-[90vh] flex flex-col">
        <form action="{{ route('client.order-tracking.request-refund', $currentOrder->order_code) }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-grow overflow-hidden">
          @csrf
          
          <div class="flex items-center justify-between p-5 border-b border-neutral-200 bg-neutral-50 shrink-0">
            <div>
              <h3 class="font-serif-luxury text-lg md:text-xl font-bold text-rose-700 flex items-center gap-2">
                <i data-lucide="hand-coins" class="w-5 h-5 text-rose-600"></i>
                <span>Yêu Cầu Hủy Hàng, Đổi Hàng &amp; Hoàn Tiền</span>
              </h3>
              <span class="text-neutral-600 text-xs mt-0.5 block">Đơn hàng <strong class="font-mono text-neutral-900">#{{ $currentOrder->order_code }}</strong> • CSKH BeeStyle hỗ trợ 24/7</span>
            </div>
            <button type="button" onclick="closeRefundModal()" class="text-neutral-400 hover:text-neutral-900 p-1">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>

          <div class="p-6 text-xs text-neutral-800 space-y-4 overflow-y-auto">
            <!-- HỘP THÔNG TIN SỐ TIỀN HOÀN DỰ KIẾN -->
            @php
              $estRefund = $currentOrder->is_deposit_required && $currentOrder->payment_status === 'deposit_paid' ? $currentOrder->deposit_amount : $currentOrder->total_amount;
            @endphp
            <div class="p-4 rounded-xl bg-gradient-to-r from-rose-50 to-white border border-rose-200 flex items-center justify-between flex-wrap gap-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                  <i data-lucide="banknote" class="w-5 h-5"></i>
                </div>
                <div>
                  <span class="text-neutral-600 text-xs block font-medium">Số tiền dự kiến hoàn trả:</span>
                  <strong class="text-xl font-mono text-rose-600 font-bold">{{ number_format($estRefund, 0, ',', '.') }}₫</strong>
                </div>
              </div>
              <div class="text-right">
                <span class="px-3 py-1 bg-white border border-neutral-300 rounded-full font-bold text-neutral-800 text-xs">
                  {{ $currentOrder->payment_status_label }}
                </span>
                <span class="text-neutral-500 text-[11px] block mt-1">Hoàn tiền 100% trong 24h làm việc</span>
              </div>
            </div>

            <!-- HÌNH THỨC YÊU CẦU -->
            <div>
              <label class="block font-bold text-neutral-900 text-xs mb-2">Hình thức bạn mong muốn <span class="text-rose-600">*</span></label>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                <label class="p-3 rounded-xl border-2 border-neutral-300 bg-neutral-50 cursor-pointer hover:border-neutral-900 transition-colors flex items-start gap-2">
                  <input type="radio" name="type" value="return_refund" class="mt-0.5 accent-neutral-950" checked>
                  <div>
                    <strong class="block text-neutral-950 text-xs font-bold">Trả Hàng Hoàn Tiền</strong>
                    <span class="text-neutral-600 text-[11px]">Chuyển hoàn kiện &amp; nhận lại tiền</span>
                  </div>
                </label>
                <label class="p-3 rounded-xl border-2 border-neutral-300 bg-neutral-50 cursor-pointer hover:border-neutral-900 transition-colors flex items-start gap-2">
                  <input type="radio" name="type" value="refund_only" class="mt-0.5 accent-neutral-950">
                  <div>
                    <strong class="block text-neutral-950 text-xs font-bold">Từ Chối Nhận Ngay</strong>
                    <span class="text-neutral-600 text-[11px]">Bưu tá chuyển hoàn về kho shop</span>
                  </div>
                </label>
                <label class="p-3 rounded-xl border-2 border-neutral-300 bg-neutral-50 cursor-pointer hover:border-neutral-900 transition-colors flex items-start gap-2">
                  <input type="radio" name="type" value="exchange" class="mt-0.5 accent-neutral-950">
                  <div>
                    <strong class="block text-neutral-950 text-xs font-bold">Đổi Size / Đổi Màu</strong>
                    <span class="text-neutral-600 text-[11px]">Shop gửi đổi sản phẩm vừa vặn</span>
                  </div>
                </label>
              </div>
            </div>

            <!-- CHỌN SẢN PHẨM CẦN HỖ TRỢ TRONG ĐƠN -->
            <div>
              <label class="block font-bold text-neutral-900 text-xs mb-2">Sản phẩm cần hỗ trợ trong đơn <span class="text-rose-600">*</span></label>
              <div class="space-y-2 max-h-40 overflow-y-auto p-1 border border-neutral-200 rounded-xl bg-neutral-50">
                @foreach($currentOrder->items as $idx => $item)
                  <label class="flex items-center justify-between p-2.5 rounded-lg border border-neutral-200 bg-white cursor-pointer hover:border-neutral-400 transition-colors">
                    <div class="flex items-center gap-2.5 min-w-0">
                      <input type="radio" name="order_item_id" value="{{ $item->id }}" class="accent-neutral-950 shrink-0" {{ $loop->first ? 'checked' : '' }}>
                      <img src="{{ asset($item->product->primaryImage->image_path ?? $item->product->thumbnail ?? 'assets/img/products/1.png') }}" alt="{{ $item->product_name }}" class="w-10 h-10 rounded object-cover border border-neutral-200 shrink-0">
                      <div class="min-w-0">
                        <strong class="block text-neutral-950 text-xs font-bold truncate">{{ $item->product_name }}</strong>
                        <span class="text-neutral-600 text-[11px]">Màu: {{ $item->color ?? 'Mặc định' }} | Size: <strong class="text-neutral-900 font-bold">{{ $item->size ?? 'M' }}</strong> • SL: x{{ $item->quantity }}</span>
                      </div>
                    </div>
                    <span class="font-bold font-mono text-neutral-900 shrink-0 ml-2">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                  </label>
                @endforeach
              </div>
            </div>

            <!-- THÔNG TIN ĐỔI SIZE / MÀU (HIỆN KHI CHỌN EXCHANGE) -->
            <div id="trackingExchangeSection" class="p-4 rounded-xl bg-sky-50 border border-sky-300 space-y-3" style="display: none;">
              <strong class="text-xs font-bold text-sky-950 flex items-center gap-1.5">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-sky-700"></i>
                <span>Thông tin phân loại trang phục mới muốn đổi:</span>
              </strong>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block font-bold text-neutral-900 text-xs mb-1">Size mới muốn đổi <span class="text-rose-600">*</span></label>
                  <select name="exchange_size" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-medium focus:outline-none focus:border-neutral-950">
                    <option value="S">Size S (48 - 56kg)</option>
                    <option value="M" selected>Size M (57 - 65kg)</option>
                    <option value="L">Size L (66 - 73kg)</option>
                    <option value="XL">Size XL (74 - 82kg)</option>
                    <option value="XXL">Size XXL (83 - 90kg)</option>
                    <option value="3XL">Size 3XL (&gt; 90kg)</option>
                  </select>
                </div>
                <div>
                  <label class="block font-bold text-neutral-900 text-xs mb-1">Màu sắc mới muốn đổi:</label>
                  <input type="text" name="exchange_color" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-medium focus:outline-none focus:border-neutral-950" placeholder="VD: Đen, Trắng, Xanh Navy, Ghi xám...">
                </div>
              </div>
              <div class="text-neutral-800 text-xs bg-white p-2.5 rounded-lg border border-sky-200">
                <strong class="text-neutral-950">Địa chỉ giao nhận đổi hàng:</strong> {{ $currentOrder->customer_name }} • {{ $currentOrder->customer_phone }} ({{ $currentOrder->shipping_address }})
              </div>
            </div>

            <!-- LÝ DO HỦY HOÀN TIỀN -->
            <div>
              <label class="block font-bold text-neutral-900 text-xs mb-1">Lý do yêu cầu đổi trả / hoàn tiền <span class="text-rose-600">*</span></label>
              <select name="reason" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-medium focus:outline-none focus:border-neutral-950" required>
                <option value="" selected disabled>-- Vui lòng chọn lý do chi tiết --</option>
                <option value="Tôi mặc thử không vừa kích cỡ (cần hỗ trợ đổi size hoặc hoàn tiền)">Tôi mặc thử không vừa kích cỡ (cần hỗ trợ đổi size hoặc hoàn tiền)</option>
                <option value="Muốn đổi sang màu sắc hoặc mẫu mã khác hợp phong cách hơn">Muốn đổi sang màu sắc hoặc mẫu mã khác hợp phong cách hơn</option>
                <option value="Sản phẩm bị lỗi may mặc, sờn rách, phai màu hoặc hư hỏng">Sản phẩm bị lỗi may mặc, sờn rách, phai màu hoặc hư hỏng</option>
                <option value="Bưu tá giao sai mẫu mã, sai màu sắc hoặc kích cỡ so với đơn đặt">Bưu tá giao sai mẫu mã, sai màu sắc hoặc kích cỡ so với đơn đặt</option>
                <option value="Sản phẩm không đúng với hình ảnh / mô tả quảng cáo">Sản phẩm không đúng với hình ảnh / mô tả quảng cáo trên web</option>
                <option value="Hộp/Thùng hàng bị móp méo, rách vỡ, mất niêm phong bưu tá">Hộp/Thùng hàng bị móp méo, rách vỡ, mất niêm phong bưu tá</option>
                <option value="Thời gian giao hàng quá trễ, tôi không còn nhu cầu mua nữa">Thời gian giao hàng quá trễ, tôi không còn nhu cầu mua nữa</option>
                <option value="Lý do khác">Lý do khác (chi tiết trong phần ghi chú)</option>
              </select>
            </div>

            <!-- THÔNG TIN TÀI KHOẢN NHẬN TIỀN HOÀN -->
            <div id="trackingBankSection" class="p-4 rounded-xl bg-neutral-50 border border-neutral-300 space-y-3">
              <div class="flex items-center justify-between">
                <label class="font-bold text-neutral-950 text-xs flex items-center gap-1.5">
                  <i data-lucide="landmark" class="w-4 h-4 text-sky-700"></i>
                  <span>Thông tin tài khoản nhận tiền hoàn:</span>
                </label>
                <span class="text-neutral-500 text-[11px] font-medium">Chuyển khoản 24/7</span>
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <input type="text" name="bank_name" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-medium focus:outline-none focus:border-neutral-950" placeholder="Tên Ngân Hàng (VD: Techcombank, Vietcombank, MB...)">
                <input type="text" name="bank_account_number" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs font-mono text-neutral-900 font-bold focus:outline-none focus:border-neutral-950" placeholder="Số Tài Khoản Ngân Hàng...">
                <div class="sm:col-span-2">
                  <input type="text" name="bank_account_name" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs uppercase font-bold text-neutral-900 focus:outline-none focus:border-neutral-950" placeholder="Họ Tên Chủ Tài Khoản (Không dấu)...">
                </div>
              </div>
            </div>

            <!-- GHI CHÚ BỔ SUNG -->
            <div>
              <label class="block font-bold text-neutral-900 text-xs mb-1">Ghi chú bổ sung (tùy chọn):</label>
              <textarea name="customer_notes" rows="2" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-normal focus:outline-none focus:border-neutral-950" placeholder="Ghi chú thêm về thời gian thuận tiện nhận hàng hoàn hoặc phản ánh chất lượng..."></textarea>
            </div>

            <!-- TẢI ẢNH MINH HỌA -->
            <div>
              <label class="block font-bold text-neutral-900 text-xs mb-1">Ảnh hoặc Video minh chứng (Hỏng hóc, tem mác, nhầm mẫu - tối đa 5 tệp):</label>
              <input type="file" name="proof_images[]" multiple accept="image/*" class="text-xs text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-neutral-200 file:text-neutral-800 hover:file:bg-neutral-300 cursor-pointer">
            </div>
          </div>

          <div class="p-4 border-t border-neutral-200 bg-neutral-50 flex justify-end gap-2.5 shrink-0">
            <button type="button" onclick="closeRefundModal()" class="px-5 py-2.5 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded-xl text-xs font-bold transition-colors">Hủy Bỏ</button>
            <button type="submit" id="btnSubmitTrackingRefund" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow transition-colors flex items-center gap-2">
              <i data-lucide="send" class="w-4 h-4"></i>
              <span>Gửi Yêu Cầu Hủy Hàng &amp; Hoàn Tiền</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 3: TỪ CHỐI NHẬN HÀNG (CHUYỂN HOÀN) -->
    <div id="modalRejectDelivery" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
      <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden">
        <form action="{{ route('client.order-tracking.reject-delivery', $currentOrder->order_code) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="flex items-center justify-between p-5 border-b border-neutral-200 bg-neutral-50">
            <h3 class="font-serif-luxury text-lg font-bold text-rose-700 flex items-center gap-2">
              <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
              <span>Không Nhận Hàng &amp; Chuyển Hoàn</span>
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-neutral-400 hover:text-neutral-900 p-1">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <div class="p-5 text-xs text-neutral-800 space-y-3.5">
            <div>
              <label class="block font-bold mb-1 text-neutral-900">Lý do từ chối bưu phẩm <span class="text-rose-600">*</span></label>
              <select name="reason" required class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-medium focus:outline-none focus:border-neutral-950">
                <option value="" disabled selected>-- Chọn lý do --</option>
                <option value="Thùng hàng bị móp méo, rách vỡ">Thùng hàng bị móp méo, rách vỡ</option>
                <option value="Giao sai mẫu mã, sai màu hoặc size">Giao sai mẫu mã, sai màu hoặc size</option>
                <option value="Sản phẩm bị lỗi may mặc hoặc hư hỏng">Sản phẩm bị lỗi may mặc hoặc hư hỏng</option>
                <option value="Thời gian giao quá trễ, không còn nhu cầu">Thời gian giao quá trễ, không còn nhu cầu</option>
                <option value="Lý do khác">Lý do khác</option>
              </select>
            </div>
            <div>
              <label class="block font-bold mb-1 text-neutral-900">Ghi chú cụ thể:</label>
              <textarea name="notes" rows="2" placeholder="Chi tiết tình trạng kiện hàng..." class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-normal focus:outline-none focus:border-neutral-950"></textarea>
            </div>
          </div>
          <div class="p-4 border-t border-neutral-200 bg-neutral-50 flex justify-end gap-2.5">
            <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded-xl text-xs font-bold transition-colors">Đóng</button>
            <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow transition-colors">Xác Nhận Chuyển Hoàn</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 4: HỦY ĐƠN HÀNG DÀNH CHO KHÁCH -->
    @if(Auth::check() && Auth::id() === $currentOrder->user_id && $currentOrder->canBeCancelledByCustomer())
      <div id="cancelTrackingOrderModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden">
          <form action="{{ route('client.orders.cancel', $currentOrder->id) }}" method="POST">
            @csrf
            <div class="flex items-center justify-between p-5 border-b border-neutral-200 bg-neutral-50">
              <h3 class="font-serif-luxury text-lg font-bold text-rose-700 flex items-center gap-2">
                <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
                <span>Hủy Đơn Hàng #{{ $currentOrder->order_code }}</span>
              </h3>
              <button type="button" onclick="closeCancelModal()" class="text-neutral-400 hover:text-neutral-900 p-1">
                <i data-lucide="x" class="w-5 h-5"></i>
              </button>
            </div>
            <div class="p-5 text-xs text-neutral-800 space-y-3.5">
              <div>
                <label class="block font-bold mb-1 text-neutral-900">Lý do hủy đơn <span class="text-rose-600">*</span></label>
                <select name="reason" required class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-medium focus:outline-none focus:border-neutral-950">
                  <option value="" disabled selected>-- Chọn lý do hủy --</option>
                  <option value="Tôi muốn thay đổi địa chỉ giao hàng">Tôi muốn thay đổi địa chỉ giao hàng</option>
                  <option value="Tôi muốn đổi size hoặc màu sắc">Tôi muốn đổi size hoặc màu sắc</option>
                  <option value="Tôi tìm thấy giá tốt hơn">Tôi tìm thấy giá tốt hơn</option>
                  <option value="Tôi đổi ý, không có nhu cầu nữa">Tôi đổi ý, không có nhu cầu nữa</option>
                </select>
              </div>
            </div>
            <div class="p-4 border-t border-neutral-200 bg-neutral-50 flex justify-end gap-2.5">
              <button type="button" onclick="closeCancelModal()" class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded-xl text-xs font-bold transition-colors">Đóng</button>
              <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow transition-colors">Xác Nhận Hủy</button>
            </div>
          </form>
        </div>
      </div>
    @endif

    <!-- MODAL 5: PHÓNG TO ẢNH POD -->
    <div id="clientPodModal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-xs flex items-center justify-center p-4 hidden">
      <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between p-4 bg-neutral-950 text-white">
          <span class="font-bold text-xs">Bằng Chứng Giao Nhận Kiện Hàng (POD)</span>
          <button type="button" onclick="closePodModal()" class="text-neutral-400 hover:text-white p-1">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
        <div class="p-2 bg-black text-center">
          <img src="{{ $currentOrder->delivery_proof_url }}" alt="POD #{{ $currentOrder->order_code }}" class="max-h-[70vh] w-auto mx-auto object-contain">
        </div>
        <div class="p-4 bg-white flex justify-between items-center text-xs">
          <span class="text-neutral-700">Đơn hàng: <strong class="text-neutral-950">#{{ $currentOrder->order_code }}</strong></span>
          <button type="button" onclick="closePodModal()" class="px-4 py-2 bg-neutral-950 hover:bg-neutral-800 text-white rounded-lg font-bold">Đóng</button>
        </div>
      </div>
    </div>

  @else
    <!-- ========================================================================= -->
    <!-- SMART EMPTY STATE (KHI KHÔNG TÌM THẤY ĐƠN) -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-2xl border-2 border-dashed border-neutral-300 p-8 md:p-12 mb-8 text-center shadow-sm">
      <div class="max-w-xl mx-auto">
        <div class="w-16 h-16 rounded-full bg-neutral-100 text-neutral-600 flex items-center justify-center mx-auto mb-4">
          <i data-lucide="search-x" class="w-8 h-8"></i>
        </div>
        
        <h3 class="font-serif-luxury text-xl md:text-2xl font-bold text-neutral-950 mb-2">Không Tìm Thấy Thông Tin Kiện Hàng</h3>
        <p class="text-neutral-700 text-xs mb-6 leading-relaxed">
          Hệ thống không tìm thấy đơn hàng hoặc mã vận đơn nào khớp với từ khóa: 
          <span class="px-2.5 py-1 bg-rose-50 border border-rose-200 text-rose-700 font-mono font-bold text-xs rounded-md">"{{ $code ?: 'Trống' }}"</span>
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left mb-6">
          <div class="p-4 rounded-xl bg-neutral-50 border border-neutral-200 space-y-2">
            <h4 class="font-bold text-neutral-950 text-xs flex items-center gap-1.5">
              <i data-lucide="receipt" class="w-4 h-4 text-amber-600"></i>
              <span>Tra cứu bằng Mã Đơn Hàng</span>
            </h4>
            <p class="text-neutral-600 text-[11px] leading-relaxed">
              Mã đơn có định dạng như <code class="font-bold text-neutral-900">BEE-20260906-T7XF</code> được gửi qua email hoặc tin nhắn khi hoàn tất đặt hàng.
            </p>
            <a href="{{ route('client.order-tracking', ['code' => 'BEE-20260906-T7XF', 'type' => 'order']) }}" class="inline-flex items-center gap-1 text-xs text-neutral-900 hover:text-amber-800 font-bold underline underline-offset-4">
              <span>Thử tra cứu mẫu</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
          </div>

          <div class="p-4 rounded-xl bg-neutral-50 border border-neutral-200 space-y-2">
            <h4 class="font-bold text-neutral-950 text-xs flex items-center gap-1.5">
              <i data-lucide="barcode" class="w-4 h-4 text-emerald-600"></i>
              <span>Tra cứu bằng Mã Vận Đơn</span>
            </h4>
            <p class="text-neutral-600 text-[11px] leading-relaxed">
              Mã vận đơn do hãng vận chuyển cấp (GHTK, GHN, Viettel Post...) in trên tem niêm phong dán trên thùng hàng.
            </p>
            <a href="{{ route('client.order-tracking', ['code' => 'GHTK-GFELJZTT', 'type' => 'tracking']) }}" class="inline-flex items-center gap-1 text-xs text-emerald-800 hover:text-emerald-900 font-bold underline underline-offset-4">
              <span>Thử tra mã bưu tá mẫu</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
          </div>
        </div>

        <div class="flex justify-center gap-3 flex-wrap text-xs">
          @if(Auth::check())
            <a href="{{ route('client.profile', ['tab' => 'orders']) }}" class="px-5 py-2.5 bg-neutral-950 hover:bg-neutral-800 text-white font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5">
              <i data-lucide="package" class="w-4 h-4"></i>
              <span>Đơn Hàng Của Tôi</span>
            </a>
          @endif
          <a href="{{ route('client.home') }}" class="px-5 py-2.5 bg-white hover:bg-neutral-100 border border-neutral-300 text-neutral-800 font-bold rounded-xl transition-colors flex items-center gap-1.5">
            <i data-lucide="home" class="w-4 h-4"></i>
            <span>Về Trang Chủ</span>
          </a>
          <a href="tel:19008888" class="px-5 py-2.5 bg-neutral-100 hover:bg-neutral-200 text-neutral-800 font-bold rounded-xl transition-colors flex items-center gap-1.5">
            <i data-lucide="phone-call" class="w-4 h-4 text-amber-600"></i>
            <span>Hotline 1900 8888</span>
          </a>
        </div>
      </div>
    </div>
  @endif

</main>
@endsection

@push('scripts')
<script>
  function switchSearchMode(mode) {
    const input = document.getElementById('trackingCodeInput');
    const typeInput = document.getElementById('searchTypeInput');
    const btnOrder = document.getElementById('btnTabOrder');
    const btnTracking = document.getElementById('btnTabTracking');
    const icon = document.getElementById('searchIcon');

    if (!input || !typeInput) return;

    if (mode === 'tracking') {
      typeInput.value = 'tracking';
      input.placeholder = 'Nhập mã vận đơn bưu tá (VD: GHTK-GFELJZTT, GHN-2C4E3DFF)...';
      if (btnTracking && btnOrder) {
        btnTracking.className = 'px-5 py-2 rounded-full text-xs font-bold transition-all bg-neutral-950 text-white shadow-xs';
        btnOrder.className = 'px-5 py-2 rounded-full text-xs font-bold transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200';
      }
      if (icon) icon.innerHTML = '<i data-lucide="barcode" class="w-4 h-4 text-emerald-600"></i>';
    } else {
      typeInput.value = 'order';
      input.placeholder = 'Nhập mã đơn hàng (VD: BEE-20260906-T7XF)...';
      if (btnOrder && btnTracking) {
        btnOrder.className = 'px-5 py-2 rounded-full text-xs font-bold transition-all bg-neutral-950 text-white shadow-xs';
        btnTracking.className = 'px-5 py-2 rounded-full text-xs font-bold transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200';
      }
      if (icon) icon.innerHTML = '<i data-lucide="search" class="w-4 h-4 text-neutral-500"></i>';
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
    input.focus();
  }

  function toggleCheckpoints() {
    const drawer = document.getElementById('checkpointsDrawer');
    if (drawer) {
      drawer.classList.toggle('hidden');
      if (!drawer.classList.contains('hidden')) {
        drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    }
  }

  function copyText(text, btnId) {
    navigator.clipboard.writeText(text).then(() => {
      const btn = document.getElementById(btnId);
      if (btn) {
        const originalText = btn.textContent;
        btn.textContent = 'Đã chép!';
        setTimeout(() => { btn.textContent = originalText; }, 1800);
      }
    });
  }

  // Countdown timer 15 phút
  let timeLeft = 15 * 60;
  const countdownEl = document.getElementById('vietqrCountdown');
  if (countdownEl) {
    const timer = setInterval(() => {
      timeLeft--;
      if (timeLeft <= 0) {
        clearInterval(timer);
        countdownEl.textContent = '00:00';
      } else {
        const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
        const s = (timeLeft % 60).toString().padStart(2, '0');
        countdownEl.textContent = `${m}:${s}`;
      }
    }, 1000);
  }

  // Pure Tailwind Modal Handlers
  function openDeliveredModal() { document.getElementById('modalConfirmDelivered')?.classList.remove('hidden'); }
  function closeDeliveredModal() { document.getElementById('modalConfirmDelivered')?.classList.add('hidden'); }

  function openRefundModal() { document.getElementById('modalRequestRefund')?.classList.remove('hidden'); }
  function closeRefundModal() { document.getElementById('modalRequestRefund')?.classList.add('hidden'); }

  function openRejectModal() { document.getElementById('modalRejectDelivery')?.classList.remove('hidden'); }
  function closeRejectModal() { document.getElementById('modalRejectDelivery')?.classList.add('hidden'); }

  function openCancelModal() { document.getElementById('cancelTrackingOrderModal')?.classList.remove('hidden'); }
  function closeCancelModal() { document.getElementById('cancelTrackingOrderModal')?.classList.add('hidden'); }

  function openPodModal() { document.getElementById('clientPodModal')?.classList.remove('hidden'); }
  function closePodModal() { document.getElementById('clientPodModal')?.classList.add('hidden'); }

  // Đóng modal khi bấm phím ESC hoặc bấm ra ngoài nền mờ
  window.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeDeliveredModal();
      closeRefundModal();
      closeRejectModal();
      closeCancelModal();
      closePodModal();
    }
  });

  // Chuyển đổi giao diện Đổi Hàng vs Trả Hàng trong Modal Tra Cứu Đơn
  document.addEventListener("DOMContentLoaded", function () {
    const typeRadios = document.querySelectorAll('#modalRequestRefund input[name="type"]');
    typeRadios.forEach(function(radio) {
      radio.addEventListener('change', function() {
        const exSec = document.getElementById('trackingExchangeSection');
        const bankSec = document.getElementById('trackingBankSection');
        const btn = document.getElementById('btnSubmitTrackingRefund');
        if (this.value === 'exchange') {
          if (exSec) exSec.style.display = 'block';
          if (bankSec) bankSec.style.display = 'none';
          if (btn) {
            btn.className = 'px-6 py-2.5 bg-neutral-950 hover:bg-neutral-800 text-white rounded-xl text-xs font-bold shadow transition-colors flex items-center gap-2';
            btn.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4"></i><span>Gửi Yêu Cầu Đổi Hàng Mới</span>';
          }
        } else {
          if (exSec) exSec.style.display = 'none';
          if (bankSec) bankSec.style.display = 'block';
          if (btn) {
            btn.className = 'px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow transition-colors flex items-center gap-2';
            btn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i><span>Gửi Yêu Cầu Hủy Hàng &amp; Hoàn Tiền</span>';
          }
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
      });
    });

    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }

    // Auto scroll if URL has hash #carrierTrackingPassSection
    if (window.location.hash === '#carrierTrackingPassSection') {
      setTimeout(() => {
        const sec = document.getElementById('carrierTrackingPassSection');
        if (sec) {
          sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
          sec.classList.add('ring-4', 'ring-amber-400');
          setTimeout(() => sec.classList.remove('ring-4', 'ring-amber-400'), 2000);
        }
      }, 300);
    }
  });
</script>
@endpush
