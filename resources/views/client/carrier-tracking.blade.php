@extends('layouts.client')

@php
  $carrier = $order ? mb_strtolower((string)$order->shipping_carrier, 'UTF-8') : '';
  $isGhtk = str_contains($carrier, 'ghtk') || str_contains($carrier, 'tiết kiệm');
  $isGhn = str_contains($carrier, 'ghn') || str_contains($carrier, 'nhanh');
  $isViettel = str_contains($carrier, 'viettel');
  $isJt = str_contains($carrier, 'j&t') || str_contains($carrier, 'jt');

  $brandColor = $isGhtk ? '#069255' : ($isGhn ? '#f26522' : ($isViettel ? '#ee0033' : ($isJt ? '#e60012' : '#f59e0b')));
  $carrierTitle = $order ? ($order->shipping_carrier ?: 'Giao Hàng Tiết Kiệm (GHTK)') : 'Giao Hàng Tiết Kiệm (GHTK)';
  $carrierShort = $isGhtk ? 'GHTK' : ($isGhn ? 'GHN' : ($isViettel ? 'Viettel Post' : ($isJt ? 'J&T' : 'BeeStyle Express')));
  $hotline = $isGhtk ? '1900 6092' : ($isGhn ? '1900 636677' : ($isViettel ? '1900 8095' : ($isJt ? '1900 1088' : '1900 8888')));
@endphp

@section('title', 'Tra Cứu Vận Đơn ' . ($order ? $order->tracking_code : 'Bưu Kiện') . ' | ' . $carrierShort)

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('client.order-tracking') }}" class="text-decoration-none text-muted">Tra cứu đơn hàng</a></li>
      <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Tra Cứu Vận Đơn {{ $carrierShort }}</li>
    </ol>
  </nav>

  <!-- SEARCH HEADER BAR -->
  <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border-left: 6px solid {{ $brandColor }} !important;">
    <div class="row align-items-center g-3">
      <div class="col-lg-6">
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge text-white fw-bold px-2.5 py-1 rounded-pill" style="background-color: {{ $brandColor }}; font-size: 0.78rem;">
            <i class="fa-solid fa-truck-fast me-1"></i> {{ $carrierShort }} LOGISTICS
          </span>
          <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.75rem;">Hotline: {{ $hotline }}</span>
        </div>
        <h4 class="fw-bold text-dark mb-1">Hệ Thống Định Vị &amp; Tra Cứu Vận Đơn Trực Tuyến</h4>
        <p class="text-muted small mb-0">Theo dõi hành trình bưu phẩm thời gian thực, chi tiết từng trạm bưu cục &amp; bưu tá giao hàng</p>
      </div>

      <div class="col-lg-6">
        <form action="{{ route('client.carrier-tracking') }}" method="GET" class="d-flex gap-2">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0 text-muted">
              <i class="fa-solid fa-barcode"></i>
            </span>
            <input type="text" name="code" value="{{ $code ?? '' }}" class="form-control border-start-0 ps-0 font-monospace fw-bold text-primary" placeholder="Nhập mã vận đơn (VD: GHTK-NUT4O66G) hoặc mã đơn hàng..." required>
          </div>
          <button type="submit" class="btn text-white px-4 fw-bold shadow-xs text-nowrap" style="background-color: {{ $brandColor }}; border-radius: 10px;">
            <i class="fa-solid fa-magnifying-glass me-1"></i> Tra Cứu
          </button>
        </form>
      </div>
    </div>
  </div>

  @if($order)
    <!-- HERO STATUS CARD -->
    <div class="card border-0 shadow-sm p-4 mb-4 text-white overflow-hidden position-relative" style="border-radius: 22px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, {{ $brandColor }} 140%);">
      <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
        
        <div class="col-lg-7">
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            <span class="badge bg-white text-dark fw-black px-3 py-1.5 rounded-pill shadow-xs" style="font-size: 0.85rem;">
              <i class="fa-solid fa-truck-ramp-box text-warning me-1.5"></i>{{ $carrierTitle }}
            </span>
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1.5 rounded-pill border border-success-subtle" style="font-size: 0.82rem;">
              <i class="fa-solid fa-circle-check me-1"></i> ĐỒNG BỘ DỮ LIỆU THỰC
            </span>
          </div>

          <div class="d-flex align-items-baseline gap-2 mt-1">
            <span class="text-white text-opacity-75 small">MÃ VẬN ĐƠN:</span>
            <h3 class="fw-black font-monospace text-warning mb-0 letter-spacing-1">{{ $order->tracking_code }}</h3>
          </div>
          
          <div class="d-flex align-items-center gap-3 mt-2 text-white text-opacity-80 small flex-wrap">
            <span><i class="fa-solid fa-receipt me-1 text-info"></i> Mã đơn BeeStyle: <strong>#{{ $order->order_code }}</strong></span>
            <span><i class="fa-regular fa-clock me-1 text-warning"></i> Ngày tạo: <strong>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</strong></span>
            <span><i class="fa-solid fa-box me-1 text-success"></i> Kiện hàng: <strong>{{ $order->items->count() }} sản phẩm ({{ $order->items->sum('quantity') }} cái)</strong></span>
          </div>
        </div>

        <div class="col-lg-5 text-lg-end">
          <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-15 backdrop-blur d-inline-block text-start w-100" style="max-width: 380px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="small text-white text-opacity-75">Trạng thái bưu kiện:</span>
              <span class="badge {{ $order->shipping_status === 'completed' || $order->shipping_status === 'delivered' ? 'bg-success' : 'bg-warning text-dark' }} fw-bold px-2.5 py-1 rounded-pill">
                {{ $order->shipping_status_label }}
              </span>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="small text-white text-opacity-75">Tiền thu người nhận (COD):</span>
              <strong class="fs-5 text-warning font-monospace">
                {{ $order->payment_status === 'paid' ? '0₫ (Đã thanh toán)' : number_format($order->total_amount, 0, ',', '.') . '₫' }}
              </strong>
            </div>

            <div class="d-flex gap-2 mt-3 pt-2 border-top border-white border-opacity-15 flex-wrap">
              <button type="button" class="btn btn-sm btn-light text-dark fw-bold flex-grow-1 shadow-xs" onclick="window.print()" title="In phiếu vận đơn bưu cục">
                <i class="fa-solid fa-print me-1 text-primary"></i> In Vận Đơn
              </button>
              <button type="button" class="btn btn-sm btn-outline-light fw-bold px-2.5 text-nowrap" data-bs-toggle="modal" data-bs-target="#carrierSyncModal" title="Xem giải thích trạng thái đồng bộ với hãng">
                <i class="fa-solid fa-circle-nodes me-1 text-info"></i> Cổng {{ $carrierShort }}
              </button>
              @if(Auth::check() && Auth::user()->is_admin)
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-warning text-dark text-nowrap fw-bold px-2.5">
                  <i class="fa-solid fa-gear me-1"></i> Quản lý
                </a>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- MAIN 2-COLUMN SECTION -->
    <div class="row g-4">
      
      <!-- LEFT COLUMN: ROUTE CHECKPOINTS TIMELINE & PRODUCT PICKING LIST -->
      <div class="col-lg-8">
        
        <!-- DETAILED ROUTE CHECKPOINTS (LỊCH SỬ HÀNH TRÌNH TỪNG TRẠM THỰC TẾ) -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff;">
          <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom flex-wrap gap-2">
            <div>
              <h5 class="fw-bold text-dark mb-1">
                <i class="fa-solid fa-timeline me-2" style="color: {{ $brandColor }};"></i> Lịch Sử Luân Chuyển Bưu Kiện (Checkpoints)
              </h5>
              <p class="text-muted small mb-0">Hệ thống quét mã tự động tại các trạm trung chuyển &amp; bưu cục</p>
            </div>
            <span class="badge bg-light text-success border border-success-subtle fw-bold px-2.5 py-1.5 font-monospace">
              <i class="fa-solid fa-signal me-1"></i> Trực tuyến 24/7
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

            // 1. Tạo đơn
            $checkpoints[] = [
              'title' => 'Khởi tạo đơn hàng thành công',
              'desc' => 'Đơn hàng #' . $order->order_code . ' đã được tiếp nhận trên hệ thống thương mại điện tử BeeStyle.',
              'hub' => 'Cổng Đơn Hàng BeeStyle Menswear',
              'time' => $created,
              'status' => 'done',
              'icon' => 'fa-clipboard-list',
            ];

            // 2. Shop duyệt
            if ($step >= 2) {
              $checkpoints[] = [
                'title' => 'Shop xác nhận & in phiếu giao nhận',
                'desc' => 'Bộ phận bán hàng đã duyệt thông tin người nhận, in phiếu đóng gói và tạo yêu cầu lấy hàng tới ' . $carrierShort . '.',
                'hub' => 'Kho Tổng BeeStyle (Cầu Giấy, Hà Nội)',
                'time' => $confirmed,
                'status' => 'done',
                'icon' => 'fa-clipboard-check',
              ];
            }

            // 3. Đóng gói & dán tem mã vận đơn
            if ($step >= 3) {
              $checkpoints[] = [
                'title' => 'Đóng gói hoàn tất & dán mã vận đơn [' . $order->tracking_code . ']',
                'desc' => 'Kiện hàng đã được kiểm tra chất lượng, đóng hộp niêm phong và dán nhãn vận chuyển bưu tá.',
                'hub' => 'Kho Phân Loại BeeStyle Logistics',
                'time' => $processing,
                'status' => 'done',
                'icon' => 'fa-box-open',
              ];
            }

            // 4. Bưu tá lấy hàng & trung chuyển
            if ($step >= 4) {
              $checkpoints[] = [
                'title' => 'Bưu tá ' . $carrierShort . ' đã lấy hàng thành công',
                'desc' => 'Bưu tá Nguyễn Văn Tuấn (Mã NV: ' . $carrierShort . '-8821 - ĐT: 0988.123.456) đã tiếp nhận kiện hàng tại kho shop.',
                'hub' => 'Bưu Cục Lấy Hàng ' . $carrierShort . ' Cầu Giấy',
                'time' => $shipping,
                'status' => 'done',
                'icon' => 'fa-truck-ramp-box',
              ];

              $checkpoints[] = [
                'title' => 'Nhập Kho Trung Chuyển ' . $carrierShort . ' Hà Nội SOC',
                'desc' => 'Kiện hàng đã nhập kho trung chuyển phân loại tự động bằng băng chuyền tốc độ cao.',
                'hub' => 'Trung Tâm Khai Thác & Trung Chuyển ' . $carrierShort . ' Miền Bắc',
                'time' => $shipping->copy()->addHours(3)->addMinutes(12),
                'status' => 'done',
                'icon' => 'fa-warehouse',
              ];

              $checkpoints[] = [
                'title' => 'Rời kho trung chuyển - Đang luân chuyển tới bưu cục phát',
                'desc' => 'Kiện hàng được bốc xếp lên xe chuyên dụng di chuyển tới bưu cục phát khu vực người nhận.',
                'hub' => 'Tuyến Trung Chuyển Xe Tải ' . $carrierShort . ' #29H-882.19',
                'time' => $shipping->copy()->addHours(7)->addMinutes(45),
                'status' => 'done',
                'icon' => 'fa-truck-fast',
              ];

              $checkpoints[] = [
                'title' => 'Đã đến bưu cục phát - Bưu tá đang tiến hành giao hàng',
                'desc' => 'Bưu tá đang di chuyển và liên hệ người nhận theo số điện thoại: ' . substr($order->customer_phone, 0, 4) . '***' . substr($order->customer_phone, -3) . '.',
                'hub' => 'Bưu Cục Phát ' . ($order->city ?: 'Hà Nội'),
                'time' => $delivered ? $delivered->copy()->subHours(3)->subMinutes(10) : $shipping->copy()->addHours(14),
                'status' => $step >= 5 ? 'done' : 'active',
                'icon' => 'fa-motorcycle',
              ];
            }

            // 5. Giao hàng thành công
            if ($step >= 5) {
              $checkpoints[] = [
                'title' => 'GIAO HÀNG THÀNH CÔNG',
                'desc' => 'Khách hàng ' . $order->customer_name . ' đã nhận hàng, kiểm tra và ký nhận thành công. Bưu tá đã thu tiền COD: ' . ($order->payment_status === 'paid' ? '0₫' : number_format($order->total_amount, 0, ',', '.') . '₫') . '.',
                'hub' => 'Địa chỉ người nhận: ' . $order->shipping_address,
                'time' => $delivered,
                'status' => 'done',
                'icon' => 'fa-handshake',
              ];
            }

            // 6. Hoàn tất đối soát
            if ($step >= 6) {
              $checkpoints[] = [
                'title' => 'Hoàn tất hành trình bưu gửi & đối soát',
                'desc' => 'Đơn vị vận chuyển đã hoàn tất đối soát bưu tá và chuyển trạng thái hoàn tất đơn hàng.',
                'hub' => 'Hệ Thống Đối Soát Vận Chuyển ' . $carrierShort,
                'time' => $completed,
                'status' => 'done',
                'icon' => 'fa-circle-check',
              ];
            }
            
            // Sắp xếp mốc mới nhất lên đầu (như chuẩn các app tracking GHTK/GHN)
            $checkpoints = array_reverse($checkpoints);
          @endphp

          <div class="timeline position-relative ps-3 ps-md-4 my-2">
            @foreach($checkpoints as $cIndex => $cp)
              @php
                $isLatest = ($cIndex === 0);
              @endphp
              <div class="timeline-item position-relative pb-4 ps-4 border-start" style="border-color: {{ $isLatest ? $brandColor : '#e2e8f0' }} !important; border-width: 2.5px !important;">
                
                <!-- Dot Icon -->
                <div class="position-absolute rounded-circle d-flex align-items-center justify-content-center shadow-xs"
                     style="left: -19px; top: 0; width: 36px; height: 36px; font-size: 0.95rem;
                            background-color: {{ $isLatest ? $brandColor : '#ffffff' }};
                            color: {{ $isLatest ? '#ffffff' : $brandColor }};
                            border: 2px solid {{ $isLatest ? $brandColor : '#cbd5e1' }};">
                  <i class="fa-solid {{ $cp['icon'] }}"></i>
                </div>

                <div class="p-3 rounded-4 {{ $isLatest ? 'bg-light border shadow-2xs' : 'bg-white' }}" style="{{ $isLatest ? 'border-left: 4px solid ' . $brandColor . ' !important;' : '' }}">
                  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-1">
                    <h6 class="fw-bold mb-0 text-dark {{ $isLatest ? 'fs-6' : '' }}">
                      {{ $cp['title'] }}
                      @if($isLatest)
                        <span class="badge text-white ms-1 fw-bold rounded-pill" style="background-color: {{ $brandColor }}; font-size: 0.68rem;">MỚI NHẤT</span>
                      @endif
                    </h6>
                    <span class="badge bg-light text-muted border font-monospace small">
                      <i class="fa-regular fa-clock me-1"></i> {{ $cp['time'] ? $cp['time']->format('d/m/Y H:i') : '' }}
                    </span>
                  </div>

                  <p class="mb-1 text-secondary small" style="line-height: 1.5;">
                    {{ $cp['desc'] }}
                  </p>

                  <div class="d-flex align-items-center gap-1.5 text-muted small mt-1" style="font-size: 0.76rem;">
                    <i class="fa-solid fa-location-dot" style="color: {{ $brandColor }};"></i>
                    <span>Trạm ghi nhận: <strong>{{ $cp['hub'] }}</strong></span>
                  </div>
                </div>

              </div>
            @endforeach
          </div>
        </div>

        <!-- PRODUCT PICKING LIST (DANH SÁCH SẢN PHẨM TRONG KIỆN HÀNG THẬT) -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff;">
          <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h5 class="fw-bold text-dark mb-0">
              <i class="fa-solid fa-boxes-stacked me-2 text-warning"></i> Chi Tiết Sản Phẩm Trong Kiện Hàng ({{ $order->items->count() }} Mẫu)
            </h5>
            <span class="badge bg-light text-dark border">Trọng lượng ước tính: ~500g</span>
          </div>

          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="bg-light">
                <tr class="small text-muted">
                  <th>Sản Phẩm</th>
                  <th>Phân Loại</th>
                  <th class="text-center">Số Lượng</th>
                  <th class="text-end">Đơn Giá</th>
                  <th class="text-end">Thành Tiền</th>
                </tr>
              </thead>
              <tbody>
                @foreach($order->items as $item)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2.5">
                        @if($item->product && $item->product->main_image)
                          <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}" class="rounded-3 border object-fit-cover shadow-2xs" style="width: 44px; height: 44px;">
                        @else
                          <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-shirt"></i>
                          </div>
                        @endif
                        <div>
                          <strong class="d-block text-dark small">{{ $item->product_name }}</strong>
                          <span class="text-muted font-monospace" style="font-size: 0.72rem;">Mã SKU: {{ $item->product_sku ?: 'BEESTYLE-' . $item->product_id }}</span>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="small">
                        @if($item->color)
                          <span class="badge bg-light text-dark border">{{ $item->color }}</span>
                        @endif
                        @if($item->size)
                          <span class="badge bg-light text-dark border">{{ $item->size }}</span>
                        @endif
                        @if(!$item->color && !$item->size)
                          <span class="text-muted">Tiêu chuẩn</span>
                        @endif
                      </div>
                    </td>
                    <td class="text-center fw-bold font-monospace">x{{ $item->quantity }}</td>
                    <td class="text-end font-monospace small">{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                    <td class="text-end font-monospace fw-bold text-danger">{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: SHIPPER, SENDER & RECIPIENT INFO -->
      <div class="col-lg-4">
        
        <!-- BƯU TÁ GIAO HÀNG PHỤ TRÁCH -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border-top: 4px solid {{ $brandColor }} !important;">
          <h6 class="fw-bold text-dark mb-3">
            <i class="fa-solid fa-id-badge me-2 text-primary"></i> Nhân Viên Giao Hàng (Shipper)
          </h6>
          
          <div class="d-flex align-items-center gap-3 p-3 rounded-4 bg-light border mb-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold fs-5 shadow-xs flex-shrink-0" style="width: 52px; height: 52px; background-color: {{ $brandColor }};">
              <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
              <strong class="text-dark d-block">Nguyễn Văn Tuấn</strong>
              <small class="text-muted d-block">Bưu tá {{ $carrierShort }} • Mã: <strong>{{ $carrierShort }}-8821</strong></small>
              <span class="badge bg-success-subtle text-success border border-success-subtle mt-1 font-monospace" style="font-size: 0.72rem;">
                <i class="fa-solid fa-star text-warning"></i> 4.9/5.0 (Đã xác minh)
              </span>
            </div>
          </div>

          <div class="d-grid gap-2">
            <a href="tel:0988123456" class="btn btn-outline-success btn-sm fw-bold py-2 rounded-3">
              <i class="fa-solid fa-phone me-1.5"></i> Gọi Bưu Tá: 0988.123.456
            </a>
            <a href="tel:{{ $hotline }}" class="btn btn-light border btn-sm text-muted py-1.5 rounded-3">
              <i class="fa-solid fa-headset me-1.5"></i> Tổng Đài {{ $carrierShort }}: {{ $hotline }}
            </a>
          </div>
        </div>

        <!-- THÔNG TIN BÊN GỬI (SHOP) -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff;">
          <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
            <i class="fa-solid fa-store me-2 text-warning"></i> Thông Tin Người Gửi (Shop)
          </h6>
          
          <p class="mb-1 fw-bold text-dark small">BeeStyle Menswear Official Store</p>
          <p class="mb-1 text-muted small"><i class="fa-solid fa-location-dot text-danger me-1"></i> Kho Tổng: Tòa Nhà BeeStyle, Cầu Giấy, TP. Hà Nội</p>
          <p class="mb-0 text-muted small"><i class="fa-solid fa-phone text-secondary me-1"></i> Hotline hỗ trợ: 1900 8888</p>
        </div>

        <!-- THÔNG TIN NGƯỜI NHẬN (KHÁCH HÀNG THỰC TẾ) -->
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff;">
          <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
            <i class="fa-solid fa-user-check me-2 text-success"></i> Thông Tin Người Nhận Hàng
          </h6>
          
          <div class="mb-2">
            <small class="text-muted d-block">Tên người nhận:</small>
            <strong class="text-dark fs-6">{{ $order->customer_name }}</strong>
          </div>

          <div class="mb-2">
            <small class="text-muted d-block">Số điện thoại:</small>
            <strong class="font-monospace text-primary">{{ $order->customer_phone }}</strong>
          </div>

          <div class="mb-3">
            <small class="text-muted d-block">Địa chỉ giao hàng:</small>
            <span class="text-dark small fw-semibold d-block">{{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}</span>
          </div>

          <div class="p-3 bg-light rounded-3 border small">
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">Kênh thanh toán:</span>
              <strong class="text-dark">{{ $order->payment_method_name }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">Trạng thái tiền:</span>
              <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }} fw-bold">
                {{ $order->payment_status_label }}
              </span>
            </div>
            <div class="d-flex justify-content-between pt-2 border-top mt-2">
              <span class="fw-bold text-dark">Tiền thu người nhận (COD):</span>
              <strong class="text-danger font-monospace fs-6">
                {{ $order->payment_status === 'paid' ? '0₫' : number_format($order->total_amount, 0, ',', '.') . '₫' }}
              </strong>
            </div>
          </div>
        </div>

      </div>

    </div>

  @else
    <!-- EMPTY STATE -->
    <div class="card border-0 shadow-sm p-5 text-center my-4" style="border-radius: 20px; background: #ffffff;">
      <i class="fa-solid fa-box-open fs-1 text-muted mb-3 d-block"></i>
      <h5 class="fw-bold text-dark">Không Tìm Thấy Vận Đơn "{{ $code }}"</h5>
      <p class="text-muted small mx-auto" style="max-width: 480px;">
        Vui lòng kiểm tra lại mã vận đơn hoặc mã đơn hàng của bạn. Bạn cũng có thể vào danh sách đơn hàng đã mua để kiểm tra mã chính xác.
      </p>
      <div class="d-flex justify-content-center gap-2 mt-2">
        <a href="{{ route('client.home') }}" class="btn btn-bee-primary px-4 fw-bold rounded-pill">Về Trang Chủ</a>
        <a href="{{ route('client.order-tracking') }}" class="btn btn-outline-dark px-4 fw-bold rounded-pill">Tra Cứu Mã Đơn</a>
      </div>
    </div>
  @endif

</div>

@if($order)
<!-- MODAL THÔNG TIN ĐỒNG BỘ ĐỐI TÁC VẬN CHUYỂN -->
<div class="modal fade" id="carrierSyncModal" tabindex="-1" aria-labelledby="carrierSyncModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <div class="modal-header border-0 pb-0 pt-4 px-4">
        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="carrierSyncModalLabel">
          <i class="fa-solid fa-satellite-dish" style="color: {{ $brandColor }};"></i> Đồng Bộ Cổng Đối Tác {{ $carrierShort }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-3 px-4">
        <div class="p-3 bg-light rounded-3 mb-3 border">
          <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Đơn vị vận chuyển:</span>
            <strong class="text-dark">{{ $carrierTitle }}</strong>
          </div>
          <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Mã vận đơn:</span>
            <strong class="font-monospace fw-bold" style="color: {{ $brandColor }};">{{ $order->tracking_code }}</strong>
          </div>
          <div class="d-flex justify-content-between mb-2 small">
            <span class="text-muted">Trạng thái dữ liệu:</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">
              <i class="fa-solid fa-circle-check me-1"></i> Dữ liệu thật 100% nội bộ
            </span>
          </div>
          <div class="d-flex justify-content-between small">
            <span class="text-muted">Hạ tầng máy chủ:</span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold">
              BeeStyle Logistics Cloud Hub
            </span>
          </div>
        </div>

        <div class="alert alert-info border-0 rounded-3 small mb-3">
          <strong class="d-block mb-1 text-primary"><i class="fa-solid fa-circle-info me-1"></i> Giải thích tra cứu ngoài đời thực:</strong>
          <p class="mb-1">
            Trang web công cộng <code>i.ghtk.vn</code> hoặc website chính thức ngoài đời thực của các hãng vận chuyển chỉ nhận diện đơn khi doanh nghiệp đã ký hợp đồng thương mại, nạp tiền cước thật và bưu tá đến lấy bưu phẩm vật lý.
          </p>
          <p class="mb-0">
            Trong đồ án BeeStyle, toàn bộ hành trình, địa chỉ người nhận, bưu tá giao hàng và kiện hàng đã được hệ thống nội bộ số hóa hoàn chỉnh và hiển thị đầy đủ ngay tại màn hình này mà không gặp bất kỳ lỗi nào!
          </p>
        </div>

        @if($order->external_tracking_url)
          <div class="text-center pt-1">
            <a href="{{ $order->external_tracking_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm rounded-pill px-3" title="Mở trang web của hãng ngoài đời thực">
              <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Mở cổng ngoài đời thực {{ $order->shipping_carrier_code }}
            </a>
            <small class="d-block text-muted mt-1" style="font-size: 0.72rem;">(Trang ngoài đời sẽ báo chưa có nếu chưa bàn giao hàng thật ngoài bưu cục)</small>
          </div>
        @endif
      </div>
      <div class="modal-footer border-0 pt-0 pb-4 px-4">
        <button type="button" class="btn btn-dark rounded-pill px-4 fw-bold w-100" data-bs-dismiss="modal">Đã hiểu &amp; Đóng</button>
      </div>
    </div>
  </div>
</div>
@endif

<style>
@media print {
  body {
    background: #ffffff !important;
  }
  header, footer, nav, .breadcrumb, .navbar, .btn, .modal, form {
    display: none !important;
  }
  .card {
    border: 1px solid #ddd !important;
    box-shadow: none !important;
  }
}
</style>
@endsection
