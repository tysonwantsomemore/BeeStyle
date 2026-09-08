@extends('layouts.client')

@section('title', 'Tra Cứu Đơn Hàng — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-5xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Tra Cứu &amp; Thanh Toán Đơn Hàng</span>
  </nav>

  <!-- SEARCH ORDER & CARRIER TRACKING BOX (DUAL-MODE OMNIBAR) -->
  <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm mb-8">
    <div class="max-w-2xl mx-auto text-center mb-6">
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-semibold block mb-1">DỊCH VỤ TRỰC TUYẾN</span>
      <h1 class="font-serif-luxury text-2xl md:text-3xl font-light text-neutral-900">Tra Cứu Hành Trình Đơn Hàng</h1>
      <p class="text-xs text-neutral-500 mt-1 font-light">Hỗ trợ tra cứu tức thì bằng <strong>Mã Đơn Hàng</strong> hoặc <strong>Mã Vận Đơn Bưu Tá</strong> (GHTK, GHN, Viettel Post...)</p>
    </div>

    <!-- Mode Tabs -->
    <div class="flex justify-center gap-2 mb-4">
      <button type="button" id="btnTabOrder" onclick="switchSearchMode('order')" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' : 'bg-neutral-950 text-white shadow-xs' }}">
        <i data-lucide="receipt" class="w-3.5 h-3.5 inline mr-1 text-amber-400"></i> Mã Đơn Hàng
      </button>
      <button type="button" id="btnTabTracking" onclick="switchSearchMode('tracking')" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-all {{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'bg-neutral-950 text-white shadow-xs' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200' }}">
        <i data-lucide="barcode" class="w-3.5 h-3.5 inline mr-1 text-amber-400"></i> Mã Vận Đơn Bưu Tá
      </button>
    </div>

    <!-- Search Form -->
    <form action="{{ route('client.order-tracking') }}" method="GET" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-2" id="trackingSearchForm">
      <input type="hidden" name="type" id="searchTypeInput" value="{{ $searchType ?? 'auto' }}">
      <div class="relative flex-grow">
        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" id="searchIcon">
          @if(($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking'))
            <i data-lucide="barcode" class="w-4 h-4 text-emerald-600"></i>
          @else
            <i data-lucide="search" class="w-4 h-4"></i>
          @endif
        </span>
        <input type="text" name="code" id="trackingCodeInput" value="{{ $code ?? '' }}" 
          placeholder="{{ ($searchType ?? '') === 'tracking' || (isset($matchedBy) && $matchedBy === 'tracking') ? 'Nhập mã vận đơn (VD: GHTK-GFELJZTT, GHN-2C4E3DFF)...' : 'Nhập mã đơn hàng (VD: BEE-20260906-T7XF)...' }}" required
          class="w-full pl-10 pr-9 py-3 bg-neutral-50 border border-neutral-200 rounded-xl text-xs uppercase font-mono text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
        @if(!empty($code))
          <button type="button" onclick="document.getElementById('trackingCodeInput').value=''; document.getElementById('trackingCodeInput').focus();" class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-700">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        @endif
      </div>
      <button type="submit" class="px-7 py-3 bg-neutral-950 hover:bg-neutral-800 text-white text-xs font-semibold tracking-wider uppercase rounded-xl transition-colors shadow flex items-center justify-center gap-1.5 shrink-0">
        <i data-lucide="search" class="w-3.5 h-3.5"></i>
        <span>Tra Cứu</span>
      </button>
    </form>

    <!-- Smart Suggestion Chips -->
    <div class="mt-4 flex items-center justify-center gap-2 flex-wrap text-[11px]">
      <span class="text-neutral-400 flex items-center gap-1"><i data-lucide="zap" class="w-3 h-3 text-amber-500"></i> Gợi ý:</span>
      @if(isset($userRecentOrders) && $userRecentOrders->isNotEmpty())
        @foreach($userRecentOrders->take(3) as $rOrder)
          <a href="{{ route('client.order-tracking', ['code' => $rOrder->order_code, 'type' => 'order']) }}" class="px-2.5 py-1 bg-neutral-100 hover:bg-neutral-200 rounded-full font-mono text-neutral-700 transition-colors">
            #{{ $rOrder->order_code }}
          </a>
          @if($rOrder->tracking_code)
            <a href="{{ route('client.order-tracking', ['code' => $rOrder->tracking_code, 'type' => 'tracking']) }}" class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 rounded-full font-mono transition-colors">
              {{ $rOrder->tracking_code }}
            </a>
          @endif
        @endforeach
      @elseif(isset($sampleOrders) && $sampleOrders->isNotEmpty())
        @foreach($sampleOrders->take(2) as $sOrder)
          <a href="{{ route('client.order-tracking', ['code' => $sOrder->order_code, 'type' => 'order']) }}" class="px-2.5 py-1 bg-neutral-100 hover:bg-neutral-200 rounded-full font-mono text-neutral-700 transition-colors">
            #{{ $sOrder->order_code }}
          </a>
          @if($sOrder->tracking_code)
            <a href="{{ route('client.order-tracking', ['code' => $sOrder->tracking_code, 'type' => 'tracking']) }}" class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 rounded-full font-mono transition-colors">
              {{ $sOrder->tracking_code }}
            </a>
          @endif
        @endforeach
      @else
        <a href="{{ route('client.order-tracking', ['code' => 'BEE-20260906-T7XF', 'type' => 'order']) }}" class="px-2.5 py-1 bg-neutral-100 hover:bg-neutral-200 rounded-full font-mono text-neutral-700 transition-colors">
          BEE-20260906-T7XF
        </a>
        <a href="{{ route('client.order-tracking', ['code' => 'GHTK-GFELJZTT', 'type' => 'tracking']) }}" class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 rounded-full font-mono transition-colors">
          GHTK-GFELJZTT
        </a>
      @endif
    </div>
  </div>

  @if(isset($currentOrder) && $currentOrder)

    <!-- KẾT QUẢ KHỚP MÃ TRA CỨU -->
    @if(isset($matchedBy) && $matchedBy === 'tracking')
      <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-xl mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-emerald-950">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
            <i data-lucide="barcode" class="w-5 h-5"></i>
          </div>
          <div>
            <span class="font-bold text-emerald-800 uppercase tracking-wider block text-[10px]">Tra cứu thành công theo Mã Vận Đơn Bưu Tá</span>
            <span>Kiện hàng: <strong class="font-mono text-emerald-900">{{ $currentOrder->tracking_code }}</strong> • Thuộc đơn hàng: <strong class="font-mono text-neutral-900">#{{ $currentOrder->order_code }}</strong> ({{ $currentOrder->shipping_carrier ?: 'GHTK' }})</span>
          </div>
        </div>
        <a href="#carrierTrackingPassSection" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold shrink-0 transition-colors">
          Xem Bưu Tá &amp; Trạm Quét ↓
        </a>
      </div>
    @else
      <div class="bg-sky-50 border border-sky-200 p-4 rounded-xl mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-sky-950">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full bg-sky-600 text-white flex items-center justify-center shrink-0 shadow-xs">
            <i data-lucide="receipt" class="w-5 h-5"></i>
          </div>
          <div>
            <span class="font-bold text-sky-800 uppercase tracking-wider block text-[10px]">Tra cứu thành công theo Mã Đơn Hàng</span>
            <span>Đơn hàng: <strong class="font-mono text-neutral-900">#{{ $currentOrder->order_code }}</strong> • Vận đơn bưu tá liên kết: <strong class="font-mono text-emerald-800">{{ $currentOrder->tracking_code ?: 'Đang chuẩn bị điều phối' }}</strong></span>
          </div>
        </div>
        @if($currentOrder->tracking_code)
          <a href="#carrierTrackingPassSection" class="px-3 py-1.5 bg-sky-700 hover:bg-sky-800 text-white rounded-lg font-semibold shrink-0 transition-colors">
            Xem Vận Đơn Bưu Tá ↓
          </a>
        @endif
      </div>
    @endif

    <!-- KHỐI THANH TOÁN VIETQR FINTECH NẾU CHƯA TRẢ ĐỦ -->
    @if(in_array($currentOrder->payment_method, ['online', 'vietqr']) && $currentOrder->payment_status !== 'paid')
      @php
        $isDepositTrack = ($currentOrder->is_deposit_required && $currentOrder->deposit_status !== 'paid');
        $payAmountTrack = $isDepositTrack ? $currentOrder->deposit_amount : $currentOrder->total_amount;
        $vietQrUrl = "https://img.vietqr.io/image/TCB-77427842310105-compact2.png?amount=" . $payAmountTrack . "&addInfo=" . urlencode($currentOrder->order_code) . "&accountName=" . urlencode("NGUYEN XUAN BAC");
      @endphp
      <div class="bg-gradient-to-br from-neutral-950 via-neutral-900 to-neutral-800 text-white p-6 md:p-8 rounded-2xl shadow-xl border border-amber-500/40 mb-8 relative overflow-hidden">
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
              <span class="text-[10px] text-neutral-500 mt-2 block font-medium">Quét bằng App mọi Ngân Hàng &amp; Ví Điện Tử</span>
            </div>
            <div class="mt-3">
              <a href="{{ $vietQrUrl }}" download="VietQR_{{ $currentOrder->order_code }}.png" target="_blank" class="inline-flex items-center gap-1 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-full text-xs font-semibold transition-colors">
                <i data-lucide="download" class="w-3.5 h-3.5 text-amber-400"></i> Tải Ảnh Mã QR
              </a>
            </div>
          </div>

          <!-- Cột 2: Chi tiết chuyển khoản -->
          <div class="lg:col-span-7 space-y-4 text-xs">
            <div class="flex items-center justify-between flex-wrap gap-2">
              <span class="px-3 py-1 bg-rose-600/30 border border-rose-500/40 text-rose-300 font-bold rounded-full text-[11px]">
                ● {{ $isDepositTrack ? 'CHỜ CHUYỂN TIỀN CỌC 50%' : 'CHỜ CHUYỂN KHOẢN' }}
              </span>
              <div class="px-3 py-1 bg-white/10 rounded-full border border-white/20 text-[11px]">
                Giữ đơn hàng: <span id="vietqrCountdown" class="font-mono font-bold text-amber-400">14:59</span>
              </div>
            </div>

            <h3 class="font-serif-luxury text-xl font-bold text-white">
              {{ $isDepositTrack ? 'Thanh Toán Tiền Cọc 50% VietQR' : 'Thanh Toán Chuyển Khoản VietQR' }}
            </h3>
            <p class="text-neutral-300 leading-relaxed text-[11px]">
              @if($isDepositTrack)
                Đơn hàng áp dụng chính sách cọc 50%. Quét mã QR bên cạnh để chuyển đúng số tiền cọc ({{ number_format($payAmountTrack, 0, ',', '.') }}₫). Phần còn lại {{ number_format($currentOrder->remaining_amount, 0, ',', '.') }}₫ thanh toán cho bưu tá khi nhận hàng.
              @else
                Mở app ngân hàng bất kỳ để quét mã. Số tiền thanh toán và nội dung chuyển khoản đã được điền sẵn chuẩn xác 100%:
              @endif
            </p>

            <div class="p-4 rounded-xl bg-white/5 border border-white/10 backdrop-blur space-y-2.5">
              <div class="flex justify-between items-center pb-2 border-b border-white/10">
                <span class="text-neutral-400">Ngân hàng thụ hưởng:</span>
                <strong class="text-white font-semibold">Techcombank (TCB)</strong>
              </div>
              <div class="flex justify-between items-center pb-2 border-b border-white/10">
                <span class="text-neutral-400">Tên chủ tài khoản:</span>
                <strong class="text-amber-400 font-bold tracking-wide">NGUYEN XUAN BAC</strong>
              </div>
              <div class="flex justify-between items-center pb-2 border-b border-white/10">
                <span class="text-neutral-400">Số tài khoản:</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono text-white text-sm font-bold" id="accNumberTxt">77427842310105</strong>
                  <button type="button" onclick="copyText('77427842310105', 'btnCopyAcc')" id="btnCopyAcc" class="px-2 py-0.5 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded text-[10px] transition-colors">
                    Copy
                  </button>
                </div>
              </div>
              <div class="flex justify-between items-center pb-2 border-b border-white/10">
                <span class="text-neutral-400">{{ $isDepositTrack ? 'Số tiền cọc cần chuyển (50%):' : 'Số tiền cần chuyển:' }}</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono text-amber-400 text-base font-bold">{{ number_format($payAmountTrack, 0, ',', '.') }}₫</strong>
                  <button type="button" onclick="copyText('{{ $payAmountTrack }}', 'btnCopyAmount')" id="btnCopyAmount" class="px-2 py-0.5 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded text-[10px] transition-colors">
                    Copy
                  </button>
                </div>
              </div>
              <div class="flex justify-between items-center pt-1">
                <span class="text-neutral-400 font-semibold">Nội dung chuyển khoản:</span>
                <div class="flex items-center gap-2">
                  <strong class="font-mono px-2 py-0.5 bg-amber-400/20 border border-amber-400/40 text-amber-300 font-bold rounded text-xs">
                    {{ $currentOrder->order_code }}
                  </strong>
                  <button type="button" onclick="copyText('{{ $currentOrder->order_code }}', 'btnCopyCode')" id="btnCopyCode" class="px-2.5 py-1 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded text-[10px] transition-colors">
                    Copy Mã
                  </button>
                </div>
              </div>
            </div>

            <form action="{{ route('client.order-tracking.confirm-transfer', $currentOrder->order_code) }}" method="POST" class="flex gap-2 flex-wrap pt-1">
              @csrf
              <button type="submit" class="flex-grow py-3 px-4 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded-xl transition-all shadow text-xs uppercase tracking-wider flex items-center justify-center gap-1.5">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Tôi Đã Chuyển Khoản Thành Công
              </button>
              <a href="{{ route('client.home') }}" class="py-3 px-4 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-semibold transition-colors">
                Tiếp Tục Mua Sắm
              </a>
            </form>
          </div>

        </div>
      </div>
    @endif

    <!-- KHỐI THẺ VẬN ĐƠN BƯU TÁ & THEO DÕI HÀNH TRÌNH BƯU KIỆN -->
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

    <div id="carrierTrackingPassSection" class="bg-gradient-to-br from-neutral-950 to-neutral-900 text-white p-6 md:p-8 rounded-2xl shadow-xl border border-neutral-800 mb-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
        
        <div class="lg:col-span-7 space-y-3">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="px-3 py-1 bg-white text-neutral-950 font-bold rounded-full text-xs shadow-xs">
              {{ $carrierTitle }}
            </span>
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-semibold rounded-full text-xs">
              ✓ ĐÃ ĐỒNG BỘ TRẠM THỰC
            </span>
            <span class="text-neutral-400 text-xs">
              CSKH: <strong class="text-white">{{ $carrierHotline }}</strong>
            </span>
          </div>

          <div>
            <span class="text-neutral-400 text-[11px] uppercase tracking-wider block font-semibold">Mã Vận Đơn Bưu Tá:</span>
            <div class="flex items-center gap-3 mt-1 flex-wrap">
              <h2 class="font-mono text-2xl md:text-3xl font-bold text-amber-400">
                {{ $currentOrder->tracking_code ?: 'GHTK-' . strtoupper(substr(md5($currentOrder->order_code), 0, 8)) }}
              </h2>
              <button type="button" onclick="copyText('{{ $currentOrder->tracking_code ?: 'GHTK-' . strtoupper(substr(md5($currentOrder->order_code), 0, 8)) }}', 'btnCopyTracking')" id="btnCopyTracking" class="px-3 py-1 bg-amber-400 hover:bg-amber-300 text-neutral-950 font-bold rounded-full text-xs transition-colors">
                Copy Mã
              </button>
            </div>
          </div>

          <div class="flex items-center gap-4 text-xs text-neutral-300 flex-wrap pt-1">
            <span>Đơn hàng: <strong class="text-white">#{{ $currentOrder->order_code }}</strong></span>
            <span>Đặt lúc: <strong>{{ $currentOrder->created_at ? $currentOrder->created_at->format('d/m/Y H:i') : '' }}</strong></span>
            <span>Kiện hàng: <strong>{{ $currentOrder->items->count() }} sản phẩm ({{ $currentOrder->items->sum('quantity') }} cái)</strong></span>
          </div>
        </div>

        <div class="lg:col-span-5 bg-white/5 border border-white/10 p-4 rounded-xl space-y-2.5 text-xs">
          <div class="flex justify-between items-center">
            <span class="text-neutral-400">Trạng thái vận chuyển:</span>
            <span class="px-2.5 py-0.5 {{ $currentOrder->shipping_status === 'completed' || $currentOrder->shipping_status === 'delivered' ? 'bg-emerald-500 text-white' : 'bg-amber-400 text-neutral-950' }} rounded-full font-bold text-[10px]">
              {{ $currentOrder->status_label }}
            </span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-neutral-400">Tiền thu người nhận (COD):</span>
            <strong class="text-amber-400 font-mono text-base">
              @if($currentOrder->payment_status === 'paid')
                0₫ (Đã thanh toán Online)
              @elseif($currentOrder->is_deposit_required)
                {{ number_format($currentOrder->remaining_amount ?: ($currentOrder->total_amount - $currentOrder->deposit_amount), 0, ',', '.') }}₫
              @else
                {{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫
              @endif
            </strong>
          </div>
          @if($currentOrder->is_deposit_required)
            <div class="flex justify-between items-center text-amber-300">
              <span>Đã đặt cọc trước (50%):</span>
              <strong class="font-mono">{{ number_format($currentOrder->deposit_amount, 0, ',', '.') }}₫ ({{ $currentOrder->deposit_status === 'paid' ? 'Đã cọc' : 'Chờ cọc' }})</strong>
            </div>
          @endif
          <div class="flex gap-2 pt-2 border-t border-white/10 flex-wrap">
            <button type="button" onclick="window.print()" class="flex-grow py-2 bg-white text-neutral-950 font-bold rounded-lg hover:bg-neutral-200 transition-colors text-center text-xs">
              In Vận Đơn
            </button>
            @if($currentOrder->tracking_url)
              <a href="{{ $currentOrder->tracking_url }}" class="py-2 px-3 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition-colors">
                Cổng {{ $carrierShort }}
              </a>
            @endif
          </div>
        </div>

      </div>

      <!-- Live Shipper Bar & Lộ Trình Toggle -->
      <div class="mt-6 pt-4 border-t border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-amber-400 text-neutral-950 flex items-center justify-center font-bold text-sm shrink-0">
            <i data-lucide="shield-check" class="w-5 h-5"></i>
          </div>
          <div class="text-xs">
            <div class="flex items-center gap-2">
              <strong class="text-white font-semibold">Bưu tá: Nguyễn Văn Tuấn</strong>
              <span class="text-amber-400 font-bold text-[10px]">★ 4.9 (Đã xác minh)</span>
            </div>
            <span class="text-neutral-400 text-[11px]">Mã NV: {{ $carrierShort }}-8821 • Hotline trạm: {{ $carrierHotline }}</span>
          </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <a href="tel:0988123456" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
            <i data-lucide="phone" class="w-3.5 h-3.5"></i> 0988.123.456
          </a>
          <button type="button" onclick="toggleCheckpoints()" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-400"></i> Lộ Trình {{ count($logisticsCheckpoints) }} Trạm Quét
          </button>
        </div>
      </div>

      <!-- COLLAPSIBLE CHECKPOINTS -->
      <div id="checkpointsDrawer" class="{{ (isset($matchedBy) && $matchedBy === 'tracking') ? 'block' : 'hidden' }} mt-6 pt-4 border-t border-white/10">
        <div class="bg-white text-neutral-900 p-6 rounded-xl space-y-4">
          <h4 class="font-serif-luxury text-base font-bold text-neutral-900 border-b border-neutral-100 pb-2">
            Lịch Sử Luân Chuyển Bưu Kiện Theo Thời Gian Thực (Checkpoints)
          </h4>
          <div class="relative border-l-2 border-neutral-200 ml-4 space-y-6">
            @foreach($logisticsCheckpoints as $cIndex => $cp)
              @php $isLatest = ($cIndex === 0); @endphp
              <div class="relative pl-6">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $isLatest ? 'bg-emerald-600 ring-4 ring-emerald-100' : 'bg-neutral-300' }}"></div>
                <div class="p-3.5 rounded-xl border {{ $isLatest ? 'border-emerald-300 bg-emerald-50/50' : 'border-neutral-200 bg-neutral-50' }} text-xs">
                  <div class="flex justify-between items-start flex-wrap gap-1 mb-1">
                    <strong class="font-semibold {{ $isLatest ? 'text-emerald-900' : 'text-neutral-900' }}">
                      {{ $cp['title'] }}
                      @if($isLatest) <span class="px-1.5 py-0.5 bg-emerald-600 text-white rounded text-[9px] font-bold ml-1">MỚI NHẤT</span> @endif
                    </strong>
                    <span class="text-neutral-500 font-mono text-[10px]">{{ $cp['time'] ? $cp['time']->format('d/m/Y H:i') : '' }}</span>
                  </div>
                  <p class="text-neutral-600 text-[11px] leading-relaxed mb-2">{{ $cp['desc'] }}</p>
                  <div class="flex items-center justify-between flex-wrap gap-2 text-[10px] text-neutral-500">
                    <span>Trạm: <strong>{{ $cp['hub'] }}</strong></span>
                    @if(!empty($cp['pod_url']))
                      <button type="button" onclick="openPodModal()" class="text-emerald-700 hover:underline font-bold flex items-center gap-1">
                        <i data-lucide="camera" class="w-3 h-3"></i> Xem Ảnh Giao Hàng (POD)
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

    <!-- TIẾN ĐỘ 6 BƯỚC HOÀN TẤT ĐƠN HÀNG (TIMELINE TRACKER) -->
    <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm mb-8">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 mb-6 border-b border-neutral-100">
        <div>
          <span class="text-xs tracking-widest text-amber-800 uppercase font-mono font-bold">ĐƠN HÀNG #{{ $currentOrder->order_code }}</span>
          <h3 class="font-serif-luxury text-xl font-bold text-neutral-900 mt-0.5">Tiến Độ Xử Lý &amp; Vận Chuyển</h3>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          @if(in_array($currentOrder->shipping_status, ['shipping', 'delivered']) || in_array($currentOrder->status_step, [4, 5]))
            <button type="button" onclick="openDeliveredModal()" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold transition-all shadow-xs flex items-center gap-1">
              <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Đã Nhận Được Hàng
            </button>
            <button type="button" onclick="openRejectModal()" class="px-3.5 py-2 border border-rose-600 text-rose-600 hover:bg-rose-50 rounded-xl text-xs font-semibold transition-all flex items-center gap-1">
              <i data-lucide="truck" class="w-3.5 h-3.5"></i> Không Nhận Hàng
            </button>
          @endif
          @if($currentOrder->canBeCancelledByCustomer())
            <button type="button" onclick="openCancelModal()" class="px-3.5 py-2 border border-neutral-300 text-neutral-600 hover:text-rose-600 hover:border-rose-300 rounded-xl text-xs font-semibold transition-all">
              Hủy Đơn
            </button>
          @endif
        </div>
      </div>

      <!-- Timeline Bar -->
      @php
        $steps = [
          1 => ['label' => 'Chờ Xác Nhận', 'desc' => 'Đơn mới tạo'],
          2 => ['label' => 'Đã Xác Nhận', 'desc' => 'Đã duyệt đơn'],
          3 => ['label' => 'Đang Đóng Gói', 'desc' => 'Kho xử lý'],
          4 => ['label' => 'Đang Giao Hàng', 'desc' => 'Bưu tá phát'],
          5 => ['label' => 'Đã Giao Hàng', 'desc' => 'Khách nhận'],
          6 => ['label' => 'Hoàn Tất', 'desc' => 'Thành công'],
        ];
        $currentStep = $currentOrder->status_step ?? 1;
      @endphp
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-center text-xs">
        @foreach($steps as $sNum => $sInfo)
          @php
            $isDone = $currentStep > $sNum;
            $isActive = $currentStep == $sNum;
          @endphp
          <div class="p-3 rounded-xl border {{ $isActive ? 'border-neutral-950 bg-neutral-50 ring-2 ring-neutral-950/10' : ($isDone ? 'border-emerald-200 bg-emerald-50/50' : 'border-neutral-200 bg-white opacity-60') }} space-y-1">
            <div class="w-6 h-6 rounded-full mx-auto flex items-center justify-center font-bold text-[11px] {{ $isActive ? 'bg-neutral-950 text-white' : ($isDone ? 'bg-emerald-600 text-white' : 'bg-neutral-200 text-neutral-600') }}">
              {{ $isDone ? '✓' : $sNum }}
            </div>
            <strong class="block font-semibold text-neutral-900 text-[11px]">{{ $sInfo['label'] }}</strong>
            <span class="text-neutral-500 text-[10px] block">{{ $sInfo['desc'] }}</span>
          </div>
        @endforeach
      </div>

    </div>

    <!-- THÔNG TIN ĐƠN HÀNG & DANH SÁCH TÁC PHẨM -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
      
      <!-- Cột 1: Sản phẩm (7 cols) -->
      <div class="md:col-span-7 bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm space-y-4">
        <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 pb-3 border-b border-neutral-100">Các Tác Phẩm Trong Đơn</h3>
        <div class="divide-y divide-neutral-100">
          @foreach($currentOrder->items as $item)
            <div class="py-3 flex gap-3.5 items-center text-xs">
              <div class="w-14 h-16 bg-neutral-100 rounded-lg overflow-hidden shrink-0 border border-neutral-200">
                <img src="{{ asset($item->product->primaryImage->image_path ?? $item->product->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=400&auto=format&fit=crop') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
              </div>
              <div class="flex-grow">
                <h4 class="font-semibold text-neutral-900 line-clamp-1">{{ $item->product_name }}</h4>
                <p class="text-neutral-500 text-[11px] mt-0.5">Số lượng: {{ $item->quantity }} | Đơn giá: {{ number_format($item->price, 0, ',', '.') }}₫</p>
              </div>
              <span class="font-serif-luxury text-sm font-bold text-neutral-950 min-w-[80px] text-right">
                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
              </span>
            </div>
          @endforeach
        </div>

        <div class="pt-3 border-t border-neutral-200 text-xs space-y-1.5 text-neutral-600">
          <div class="flex justify-between">
            <span>Tạm tính:</span>
            <span class="font-semibold text-neutral-900">{{ number_format($currentOrder->subtotal, 0, ',', '.') }}₫</span>
          </div>
          @if($currentOrder->discount_amount > 0)
            <div class="flex justify-between text-rose-700 font-semibold">
              <span>Ưu đãi ({{ $currentOrder->coupon_code ?? 'VOUCHER' }}):</span>
              <span>-{{ number_format($currentOrder->discount_amount, 0, ',', '.') }}₫</span>
            </div>
          @endif
          <div class="flex justify-between">
            <span>Phí vận chuyển:</span>
            <span class="font-semibold text-neutral-900">{{ $currentOrder->shipping_fee > 0 ? number_format($currentOrder->shipping_fee, 0, ',', '.') . '₫' : 'MIỄN PHÍ' }}</span>
          </div>
          <div class="flex justify-between items-baseline pt-2 border-t border-neutral-200 font-bold text-neutral-950 text-sm">
            <span>Tổng thanh toán:</span>
            <span class="font-serif-luxury text-xl font-bold text-rose-600">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</span>
          </div>
        </div>
      </div>

      <!-- Cột 2: Thông tin nhận hàng (5 cols) -->
      <div class="md:col-span-5 bg-white p-6 rounded-2xl border border-neutral-200 shadow-sm space-y-4 text-xs">
        <h3 class="font-serif-luxury text-lg font-bold text-neutral-900 pb-3 border-b border-neutral-100">Thông Tin Giao Nhận</h3>
        <div>
          <span class="text-neutral-400 uppercase text-[10px] tracking-wider font-semibold block">Người nhận:</span>
          <p class="font-semibold text-neutral-900 text-sm mt-0.5">{{ $currentOrder->customer_name }} — {{ $currentOrder->customer_phone }}</p>
        </div>
        <div>
          <span class="text-neutral-400 uppercase text-[10px] tracking-wider font-semibold block">Địa chỉ giao hàng:</span>
          <p class="text-neutral-700 mt-0.5 leading-relaxed">{{ $currentOrder->shipping_address }}{{ $currentOrder->city ? ', ' . $currentOrder->city : '' }}</p>
        </div>
        <div>
          <span class="text-neutral-400 uppercase text-[10px] tracking-wider font-semibold block">Phương thức thanh toán:</span>
          <p class="text-neutral-900 font-semibold uppercase mt-0.5">{{ $currentOrder->payment_method_name ?? $currentOrder->payment_method }}</p>
        </div>
        <div>
          <span class="text-neutral-400 uppercase text-[10px] tracking-wider font-semibold block">Trạng thái thanh toán:</span>
          <span class="inline-block mt-1 px-2 py-0.5 {{ $currentOrder->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} rounded font-bold text-[10px]">
            {{ $currentOrder->payment_status_label ?? $currentOrder->payment_status }}
          </span>
        </div>
        @if($currentOrder->notes)
          <div class="pt-2 border-t border-neutral-100">
            <span class="text-neutral-400 uppercase text-[10px] tracking-wider font-semibold block">Ghi chú:</span>
            <p class="text-neutral-600 italic mt-0.5">"{{ $currentOrder->notes }}"</p>
          </div>
        @endif
      </div>

    </div>

    <!-- MODAL 1: XÁC NHẬN NHẬN HÀNG -->
    <div id="modalConfirmDelivered" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
      <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden">
        <form action="{{ route('client.order-tracking.confirm-delivered', $currentOrder->order_code) }}" method="POST">
          @csrf
          <div class="flex items-center justify-between p-5 border-b border-neutral-100">
            <h3 class="font-serif-luxury text-lg font-bold text-emerald-800 flex items-center gap-2">
              <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
              <span>Xác Nhận Đã Nhận Đủ Kiện Hàng</span>
            </h3>
            <button type="button" onclick="closeDeliveredModal()" class="text-neutral-400 hover:text-neutral-900">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <div class="p-5 text-xs text-neutral-600 space-y-3">
            <p>Xác nhận bạn đã đồng kiểm tra kiện hàng với bưu tá, trang phục nguyên vẹn tem mác và đã thanh toán đủ tiền hàng (nếu là COD).</p>
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200 text-neutral-800">
              Đơn hàng: <strong>#{{ $currentOrder->order_code }}</strong> ({{ $currentOrder->items->count() }} sản phẩm)
            </div>
          </div>
          <div class="p-4 border-t border-neutral-100 bg-neutral-50 flex justify-end gap-2">
            <button type="button" onclick="closeDeliveredModal()" class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 rounded-lg text-xs font-semibold">Đóng</button>
            <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold">Xác Nhận Đã Nhận</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 2: TỪ CHỐI NHẬN HÀNG (CHUYỂN HOÀN) -->
    <div id="modalRejectDelivery" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
      <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden">
        <form action="{{ route('client.order-tracking.reject-delivery', $currentOrder->order_code) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="flex items-center justify-between p-5 border-b border-neutral-100">
            <h3 class="font-serif-luxury text-lg font-bold text-rose-700 flex items-center gap-2">
              <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
              <span>Không Nhận Hàng &amp; Chuyển Hoàn</span>
            </h3>
            <button type="button" onclick="closeRejectModal()" class="text-neutral-400 hover:text-neutral-900">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <div class="p-5 text-xs text-neutral-700 space-y-3">
            <div>
              <label class="block font-semibold mb-1">Lý do từ chối bưu phẩm <span class="text-rose-600">*</span></label>
              <select name="reason" required class="w-full bg-neutral-50 border border-neutral-300 rounded-lg p-2 text-xs focus:outline-none focus:border-neutral-950">
                <option value="" disabled selected>-- Chọn lý do --</option>
                <option value="Thùng hàng bị móp méo, rách vỡ">Thùng hàng bị móp méo, rách vỡ</option>
                <option value="Giao sai mẫu mã, sai màu hoặc size">Giao sai mẫu mã, sai màu hoặc size</option>
                <option value="Sản phẩm bị lỗi may mặc hoặc hư hỏng">Sản phẩm bị lỗi may mặc hoặc hư hỏng</option>
                <option value="Thời gian giao quá trễ, không còn nhu cầu">Thời gian giao quá trễ, không còn nhu cầu</option>
                <option value="Lý do khác">Lý do khác</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold mb-1">Ghi chú cụ thể:</label>
              <textarea name="notes" rows="2" placeholder="Chi tiết tình trạng kiện hàng..." class="w-full bg-neutral-50 border border-neutral-300 rounded-lg p-2 text-xs focus:outline-none focus:border-neutral-950"></textarea>
            </div>
          </div>
          <div class="p-4 border-t border-neutral-100 bg-neutral-50 flex justify-end gap-2">
            <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-neutral-200 text-neutral-800 rounded-lg text-xs font-semibold">Đóng</button>
            <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold">Xác Nhận Chuyển Hoàn</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL 3: HỦY ĐƠN HÀNG DÀNH CHO KHÁCH -->
    @if(Auth::check() && Auth::id() === $currentOrder->user_id && $currentOrder->canBeCancelledByCustomer())
      <div id="cancelTrackingOrderModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden">
          <form action="{{ route('client.orders.cancel', $currentOrder->id) }}" method="POST">
            @csrf
            <div class="flex items-center justify-between p-5 border-b border-neutral-100">
              <h3 class="font-serif-luxury text-lg font-bold text-rose-700 flex items-center gap-2">
                <i data-lucide="x-circle" class="w-5 h-5"></i>
                <span>Hủy Đơn Hàng #{{ $currentOrder->order_code }}</span>
              </h3>
              <button type="button" onclick="closeCancelModal()" class="text-neutral-400 hover:text-neutral-900">
                <i data-lucide="x" class="w-5 h-5"></i>
              </button>
            </div>
            <div class="p-5 text-xs text-neutral-700 space-y-3">
              <div>
                <label class="block font-semibold mb-1">Lý do hủy đơn <span class="text-rose-600">*</span></label>
                <select name="reason" required class="w-full bg-neutral-50 border border-neutral-300 rounded-lg p-2 text-xs focus:outline-none focus:border-neutral-950">
                  <option value="" disabled selected>-- Chọn lý do hủy --</option>
                  <option value="Tôi muốn thay đổi địa chỉ giao hàng">Tôi muốn thay đổi địa chỉ giao hàng</option>
                  <option value="Tôi muốn đổi size hoặc màu sắc">Tôi muốn đổi size hoặc màu sắc</option>
                  <option value="Tôi tìm thấy giá tốt hơn">Tôi tìm thấy giá tốt hơn</option>
                  <option value="Tôi đổi ý, không có nhu cầu nữa">Tôi đổi ý, không có nhu cầu nữa</option>
                </select>
              </div>
            </div>
            <div class="p-4 border-t border-neutral-100 bg-neutral-50 flex justify-end gap-2">
              <button type="button" onclick="closeCancelModal()" class="px-4 py-2 bg-neutral-200 text-neutral-800 rounded-lg text-xs font-semibold">Đóng</button>
              <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold">Xác Nhận Hủy</button>
            </div>
          </form>
        </div>
      </div>
    @endif

    <!-- MODAL 4: PHÓNG TO ẢNH POD -->
    <div id="clientPodModal" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-xs flex items-center justify-center p-4 hidden">
      <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between p-4 bg-neutral-950 text-white">
          <span class="font-semibold text-xs">Bằng Chứng Giao Nhận Kiện Hàng (POD)</span>
          <button type="button" onclick="closePodModal()" class="text-neutral-400 hover:text-white">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
        </div>
        <div class="p-2 bg-black text-center">
          <img src="{{ $currentOrder->delivery_proof_url }}" alt="POD #{{ $currentOrder->order_code }}" class="max-h-[70vh] w-auto mx-auto object-contain">
        </div>
        <div class="p-4 bg-white flex justify-between items-center text-xs">
          <span class="text-neutral-500">Đơn hàng: <strong>#{{ $currentOrder->order_code }}</strong></span>
          <button type="button" onclick="closePodModal()" class="px-4 py-2 bg-neutral-950 text-white rounded-lg font-semibold">Đóng</button>
        </div>
      </div>
    </div>

  @elseif(request('code'))
    <!-- Empty State -->
    <div class="bg-white p-12 rounded-2xl border border-neutral-200 text-center max-w-md mx-auto shadow-sm">
      <i data-lucide="package-x" class="w-12 h-12 mx-auto text-neutral-400 mb-3 stroke-1"></i>
      <h3 class="font-serif-luxury text-xl font-bold text-neutral-800 mb-1">Không tìm thấy đơn hàng</h3>
      <p class="text-xs text-neutral-500 mb-6">Hệ thống không tìm thấy mã <strong>"{{ request('code') }}"</strong>. Vui lòng kiểm tra lại hoặc liên hệ hotline 1900 8888 để được hỗ trợ.</p>
      <a href="{{ route('client.home') }}" class="inline-flex items-center px-6 py-2.5 bg-neutral-950 text-white text-xs font-semibold rounded-lg uppercase tracking-wider hover:bg-neutral-800 transition-colors">
        Về Trang Chủ
      </a>
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
        btnTracking.className = 'px-4 py-1.5 rounded-full text-xs font-semibold transition-all bg-neutral-950 text-white shadow-xs';
        btnOrder.className = 'px-4 py-1.5 rounded-full text-xs font-semibold transition-all bg-neutral-100 text-neutral-600 hover:bg-neutral-200';
      }
      if (icon) icon.innerHTML = '<i data-lucide="barcode" class="w-4 h-4 text-emerald-600"></i>';
    } else {
      typeInput.value = 'order';
      input.placeholder = 'Nhập mã đơn hàng (VD: BEE-20260906-T7XF)...';
      if (btnOrder && btnTracking) {
        btnOrder.className = 'px-4 py-1.5 rounded-full text-xs font-semibold transition-all bg-neutral-950 text-white shadow-xs';
        btnTracking.className = 'px-4 py-1.5 rounded-full text-xs font-semibold transition-all bg-neutral-100 text-neutral-600 hover:bg-neutral-200';
      }
      if (icon) icon.innerHTML = '<i data-lucide="search" class="w-4 h-4"></i>';
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
    input.focus();
  }

  function toggleCheckpoints() {
    const drawer = document.getElementById('checkpointsDrawer');
    if (drawer) drawer.classList.toggle('hidden');
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

  // Modal Handlers
  function openDeliveredModal() { document.getElementById('modalConfirmDelivered')?.classList.remove('hidden'); }
  function closeDeliveredModal() { document.getElementById('modalConfirmDelivered')?.classList.add('hidden'); }

  function openRejectModal() { document.getElementById('modalRejectDelivery')?.classList.remove('hidden'); }
  function closeRejectModal() { document.getElementById('modalRejectDelivery')?.classList.add('hidden'); }

  function openCancelModal() { document.getElementById('cancelTrackingOrderModal')?.classList.remove('hidden'); }
  function closeCancelModal() { document.getElementById('cancelTrackingOrderModal')?.classList.add('hidden'); }

  function openPodModal() { document.getElementById('clientPodModal')?.classList.remove('hidden'); }
  function closePodModal() { document.getElementById('clientPodModal')?.classList.add('hidden'); }

  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
  });
</script>
@endpush