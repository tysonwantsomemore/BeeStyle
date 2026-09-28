<!DOCTYPE html>
<html lang="vi" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cổng Bưu Tá Giao Vận - BeeStyle Express</title>
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
  <meta name="theme-color" content="#2563eb">

  <!-- STYLESHEETS PHOENIX & FONTAWESOME -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
  <link href="{{ asset('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    body {
      background-color: #f1f5f9;
      font-family: 'Nunito Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      padding-bottom: 75px;
    }
    .shipper-header {
      background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
      color: white;
      border-radius: 0 0 24px 24px;
      box-shadow: 0 4px 20px rgba(37, 99, 235, 0.2);
    }
    .order-card {
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      background: #ffffff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
      transition: all 0.2s ease-in-out;
    }
    .order-card:hover {
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      border-color: #cbd5e1;
    }
    .cod-badge {
      background: #fef2f2;
      color: #dc2626;
      border: 1px dashed #f87171;
      font-size: 1.1rem;
      font-weight: 800;
      border-radius: 10px;
      padding: 6px 12px;
      display: inline-block;
    }
    .paid-badge {
      background: #f0fdf4;
      color: #16a34a;
      border: 1px solid #bbf7d0;
      font-weight: 700;
      border-radius: 10px;
      padding: 6px 12px;
      display: inline-block;
    }
    .action-btn-call {
      background: #10b981;
      color: white;
      font-weight: 700;
      border-radius: 12px;
    }
    .action-btn-call:hover {
      background: #059669;
      color: white;
    }
    .action-btn-map {
      background: #0ea5e9;
      color: white;
      font-weight: 700;
      border-radius: 12px;
    }
    .action-btn-map:hover {
      background: #0284c7;
      color: white;
    }
    .nav-tabs-shipper .nav-link {
      color: #64748b;
      font-weight: 700;
      border: none;
      border-bottom: 3px solid transparent;
      padding: 10px 16px;
      border-radius: 0;
    }
    .nav-tabs-shipper .nav-link.active {
      color: #2563eb;
      background: transparent;
      border-bottom: 3px solid #2563eb;
    }
    .pod-preview-box {
      width: 100%;
      height: 220px;
      border: 2px dashed #cbd5e1;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8fafc;
      overflow: hidden;
      position: relative;
    }
    .pod-preview-box img {
      max-width: 100%;
      max-height: 100%;
      object-fit: cover;
    }
  </style>
</head>
<body>

  <!-- HEADER BƯU TÁ -->
  <header class="shipper-header px-3 pt-4 pb-4">
    <div class="container" style="max-width: 800px;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2.5">
          <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="rounded-circle border border-2 border-white shadow-sm" style="width: 46px; height: 46px; object-fit: cover;">
          <div>
            <div class="text-white-50 fs-10 text-uppercase fw-bold">BeeStyle Express • Bưu Tá</div>
            <h6 class="text-white mb-0 fw-bold fs-8">{{ Auth::user()->name }}</h6>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold text-primary">
              <i class="fa-solid fa-arrow-left me-1"></i> Về Quản Trị
            </a>
          @endif
          <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-2.5" title="Đăng xuất">
              <i class="fa-solid fa-right-from-bracket"></i>
            </button>
          </form>
        </div>
      </div>

      <!-- 3 THẺ THỐNG KÊ NHANH CHO BƯU TÁ -->
      <div class="row g-2 mt-1">
        <div class="col-4">
          <div class="p-2.5 bg-white bg-opacity-10 rounded-3 text-center border border-white border-opacity-25">
            <span class="d-block text-white-50 fs-10 fw-semibold">Cần Giao</span>
            <strong class="fs-7 text-white fw-black">{{ $deliveringCount }}</strong>
            <span class="d-block text-warning fs-11 fw-bold">Đang phát</span>
          </div>
        </div>
        <div class="col-4">
          <div class="p-2.5 bg-white bg-opacity-10 rounded-3 text-center border border-white border-opacity-25">
            <span class="d-block text-white-50 fs-10 fw-semibold">Đã Giao Hôm Nay</span>
            <strong class="fs-7 text-white fw-black">{{ $deliveredTodayCount }}</strong>
            <span class="d-block text-info fs-11 fw-bold">Thành công</span>
          </div>
        </div>
        <div class="col-4">
          <div class="p-2.5 bg-white bg-opacity-10 rounded-3 text-center border border-white border-opacity-25">
            <span class="d-block text-white-50 fs-10 fw-semibold">COD Cần Thu</span>
            <strong class="fs-8 text-white fw-black">{{ number_format($codNeedToCollect, 0, ',', '.') }}₫</strong>
            <span class="d-block text-white-50 fs-11">Tiền mặt</span>
          </div>
        </div>
      </div>
    </div>
  </header>

  <main class="container my-3" style="max-width: 800px;">
    <!-- THÔNG BÁO FLASH MESSAGE -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm d-flex align-items-center gap-2 mb-3" role="alert">
        <i class="fa-solid fa-circle-check fs-6 text-success"></i>
        <div class="flex-grow-1 fw-bold fs-9">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm d-flex align-items-center gap-2 mb-3" role="alert">
        <i class="fa-solid fa-circle-exclamation fs-6 text-danger"></i>
        <div class="flex-grow-1 fw-bold fs-9">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <!-- ADMIN SWITCHER: Nếu là Admin, có thể chọn xem đơn của từng Bưu tá -->
    @if(Auth::user()->isAdmin() && $shippersList->isNotEmpty())
      <div class="card border-0 shadow-xs mb-3 rounded-3 bg-white p-2.5">
        <form method="GET" action="{{ route('shipper.orders.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
          <input type="hidden" name="tab" value="{{ $tab }}">
          <span class="fs-9 fw-bold text-muted"><i class="fa-solid fa-user-gear me-1"></i> Xem đơn của Bưu tá:</span>
          <select name="shipper_id" class="form-select form-select-sm w-auto rounded-pill border" onchange="this.form.submit()">
            <option value="">-- Tất cả Bưu tá --</option>
            @foreach($shippersList as $shp)
              <option value="{{ $shp->id }}" {{ request('shipper_id') == $shp->id ? 'selected' : '' }}>
                {{ $shp->name }} ({{ $shp->phone ?: 'Chưa có SĐT' }})
              </option>
            @endforeach
          </select>
        </form>
      </div>
    @endif

    <!-- THANH TÌM KIẾM -->
    <div class="mb-3">
      <form method="GET" action="{{ route('shipper.orders.index') }}">
        <input type="hidden" name="tab" value="{{ $tab }}">
        @if(request('shipper_id'))
          <input type="hidden" name="shipper_id" value="{{ request('shipper_id') }}">
        @endif
        <div class="input-group shadow-xs">
          <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3 text-muted">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0 border-end-0 py-2 fs-9" placeholder="Tìm tên khách, số điện thoại, địa chỉ, mã đơn...">
          <button class="btn btn-primary rounded-end-pill px-3 fw-bold" type="submit">Tìm</button>
        </div>
      </form>
    </div>

    <!-- TABS LỌC TRẠNG THÁI -->
    <ul class="nav nav-tabs nav-tabs-shipper bg-white rounded-3 shadow-xs mb-3 px-2">
      <li class="nav-item">
        <a class="nav-link {{ $tab === 'shipping' ? 'active' : '' }}" href="{{ route('shipper.orders.index', array_merge(request()->query(), ['tab' => 'shipping'])) }}">
          <i class="fa-solid fa-truck-fast me-1 text-primary"></i> Đang Đi Giao ({{ $deliveringCount }})
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ $tab === 'pickup' ? 'active' : '' }}" href="{{ route('shipper.orders.index', array_merge(request()->query(), ['tab' => 'pickup'])) }}">
          <i class="fa-solid fa-boxes-packing me-1 text-warning"></i> Chờ Lấy Hàng ({{ $pickupCount }})
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ $tab === 'delivered' ? 'active' : '' }}" href="{{ route('shipper.orders.index', array_merge(request()->query(), ['tab' => 'delivered'])) }}">
          <i class="fa-solid fa-circle-check me-1 text-success"></i> Đã Giao ({{ $deliveredCount }})
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ $tab === 'all' ? 'active' : '' }}" href="{{ route('shipper.orders.index', array_merge(request()->query(), ['tab' => 'all'])) }}">
          <i class="fa-solid fa-boxes-stacked me-1"></i> Tất Cả Đơn
        </a>
      </li>
    </ul>

    <!-- DANH SÁCH ĐƠN HÀNG CỦA BƯU TÁ -->
    <div class="order-list">
      @forelse($orders as $order)
        <div class="order-card p-3 mb-3">
          <!-- CARD HEADER: MÃ ĐƠN & HUY HIỆU -->
          <div class="d-flex justify-content-between align-items-center border-bottom pb-2.5 mb-2.5">
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-primary-subtle text-primary fw-bold font-monospace px-2.5 py-1.5 rounded-pill fs-9">
                #{{ $order->order_code }}
              </span>
              @if($order->tracking_code)
                <span class="badge bg-light text-dark border font-monospace fs-10">
                  <i class="fa-solid fa-barcode me-1 text-muted"></i>{{ $order->tracking_code }}
                </span>
              @endif
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-10 d-none d-sm-inline-block">
                <i class="fa-regular fa-clock me-1"></i> {{ $order->estimated_delivery_text }}
              </span>
            </div>
            <div class="d-flex align-items-center gap-1.5">
              <a href="{{ route('shipper.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-xs rounded-pill px-2.5 py-0.5 fw-bold fs-10">
                Chi Tiết
              </a>
              @if($order->shipping_status === 'shipping')
                <span class="badge bg-info-subtle text-info fw-bold rounded-pill px-2.5 py-1">
                  <i class="fa-solid fa-truck-moving me-1"></i> Đang Đi Giao
                </span>
              @elseif($order->shipping_status === 'delivered')
                <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-2.5 py-1">
                  <i class="fa-solid fa-circle-check me-1"></i> Đã Giao Hàng
                </span>
              @elseif($order->shipping_status === 'completed')
                <span class="badge bg-success text-white fw-bold rounded-pill px-2.5 py-1">
                  <i class="fa-solid fa-check-double me-1"></i> Hoàn Tất
                </span>
              @else
                <span class="badge bg-secondary-subtle text-secondary fw-bold rounded-pill px-2.5 py-1">
                  {{ $order->status_label }}
                </span>
              @endif
            </div>
          </div>

          <!-- THÔNG TIN NGƯỜI NHẬN & ĐỊA CHỈ -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-start mb-1.5">
              <h6 class="fw-bold text-dark mb-0 fs-8">
                <i class="fa-solid fa-user me-1.5 text-primary"></i> {{ $order->customer_name }}
              </h6>
              <span class="text-muted fs-10">
                <i class="fa-regular fa-clock me-1"></i> {{ $order->created_at ? $order->created_at->format('H:i d/m/Y') : '' }}
              </span>
            </div>

            <!-- ĐỊA CHỈ GIAO HÀNG -->
            <p class="text-secondary small mb-2 lh-base">
              <i class="fa-solid fa-location-dot me-1.5 text-danger"></i>
              <strong>Địa chỉ:</strong> {{ $order->full_shipping_address }}
            </p>

            @if($order->notes)
              <div class="p-2 bg-light rounded-2 border text-dark fs-10 mb-2">
                <i class="fa-regular fa-message me-1 text-warning"></i>
                <strong>Khách dặn:</strong> {{ $order->notes }}
              </div>
            @endif

            <!-- 2 NÚT THAO TÁC NHANH: GỌI ĐIỆN & CHỈ ĐƯỜNG BẢN ĐỒ -->
            <div class="d-flex gap-2 mb-3">
              <a href="tel:{{ $order->customer_phone }}" class="btn action-btn-call btn-sm flex-fill d-flex align-items-center justify-content-center gap-1.5 py-2">
                <i class="fa-solid fa-phone"></i> Gọi: {{ $order->customer_phone }}
              </a>
              <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->full_shipping_address) }}" target="_blank" class="btn action-btn-map btn-sm flex-fill d-flex align-items-center justify-content-center gap-1.5 py-2">
                <i class="fa-solid fa-map-location-dot"></i> Bản Đồ
              </a>
            </div>

            <!-- SẢN PHẨM TRONG GÓI HÀNG -->
            <div class="bg-light p-2.5 rounded-3 mb-3 border border-translucent">
              <div class="text-muted fs-11 text-uppercase fw-bold mb-1.5 d-flex justify-content-between">
                <span>Kiện Hàng ({{ $order->items->sum('quantity') }} sản phẩm)</span>
                <span>{{ $order->shipping_carrier ?: 'Nội bộ BeeStyle' }}</span>
              </div>
              @foreach($order->items as $item)
                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light">
                  <span class="text-dark fw-semibold fs-10 text-truncate me-2" style="max-width: 70%;">
                    • {{ $item->product_name }}
                    @if($item->color || $item->size)
                      <small class="text-muted">({{ $item->color }} / {{ $item->size }})</small>
                    @endif
                  </span>
                  <span class="badge bg-white text-dark border fw-bold fs-11">x{{ $item->quantity }}</span>
                </div>
              @endforeach
            </div>

            <!-- THANH TOÁN & TIỀN COD -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-2.5 bg-light rounded-3 border">
              <div>
                <span class="text-muted fs-10 d-block">Hình thức thanh toán:</span>
                <span class="fw-bold text-dark fs-9">{{ $order->payment_method_name }}</span>
                @if($order->is_deposit_required || $order->payment_status === 'deposit_paid')
                  <span class="badge bg-warning text-dark border border-warning ms-1 fs-11 fw-bold">
                    Đã cọc 50% ({{ number_format($order->deposit_amount ?: round($order->total_amount * 0.5), 0, ',', '.') }}₫)
                  </span>
                @endif
              </div>
              <div>
                @php
                  $isFullyPaid = ($order->payment_status === 'paid');
                  $hasRemainingCod = false;
                  $codAmount = 0;

                  if (!$isFullyPaid) {
                    if ($order->payment_status === 'deposit_paid' || ($order->is_deposit_required && $order->deposit_status === 'paid')) {
                      $hasRemainingCod = true;
                      $codAmount = $order->remaining_amount ?: ($order->total_amount - $order->deposit_amount);
                    } elseif ($order->payment_method === 'cod') {
                      $hasRemainingCod = true;
                      $codAmount = $order->is_deposit_required ? ($order->remaining_amount ?: ($order->total_amount - $order->deposit_amount)) : $order->total_amount;
                    }
                  }
                @endphp

                @if($hasRemainingCod && $codAmount > 0)
                  <span class="cod-badge {{ ($order->is_deposit_required || $order->payment_status === 'deposit_paid') ? 'bg-warning bg-opacity-25 text-dark border-warning' : '' }}">
                    <i class="fa-solid fa-hand-holding-dollar me-1 text-danger"></i>
                    @if($order->is_deposit_required || $order->payment_status === 'deposit_paid')
                      Thu COD (50% còn lại): {{ number_format($codAmount, 0, ',', '.') }}₫
                    @else
                      Thu COD: {{ number_format($codAmount, 0, ',', '.') }}₫
                    @endif
                  </span>
                @else
                  <span class="paid-badge">
                    <i class="fa-solid fa-circle-check me-1"></i> Đã Thu Đủ 100% (Thu 0₫)
                  </span>
                @endif
              </div>
            </div>
          </div>

          <!-- FOOTER CARD: HÀNH ĐỘNG CỦA BƯU TÁ -->
          <div class="border-top pt-2.5 d-flex justify-content-between align-items-center flex-wrap gap-2">
            @if($order->shipping_status === 'shipping')
              <button type="button" class="btn btn-warning btn-sm fw-bold rounded-pill text-dark px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#reportModal{{ $order->id }}">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> Báo Sự Cố
              </button>

              <!-- NÚT CHÍNH: XÁC NHẬN ĐÃ GIAO & TẢI ẢNH POD -->
              <button type="button" class="btn btn-success btn-sm fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#deliverModal{{ $order->id }}">
                <i class="fa-solid fa-camera me-1.5"></i> Chụp Ảnh &amp; Xác Nhận Đã Giao
              </button>
            @elseif(in_array($order->shipping_status, ['delivered', 'completed']))
              <div class="text-muted fs-10">
                <i class="fa-regular fa-circle-check text-success me-1"></i>
                Đã giao lúc: <strong>{{ $order->delivered_at ? $order->delivered_at->format('H:i d/m/Y') : 'Vừa xong' }}</strong>
              </div>
              @if($order->delivery_proof_image)
                <button type="button" class="btn btn-outline-success btn-sm rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#viewPodModal{{ $order->id }}">
                  <i class="fa-solid fa-image me-1"></i> Xem Ảnh Giao (POD)
                </button>
              @endif
            @elseif(in_array($order->shipping_status, ['confirmed', 'processing']))
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 w-100">
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning fs-10 fw-bold">
                  <i class="fa-solid fa-boxes-packing me-1"></i> Kho Đã Đóng Gói (Chờ Lấy Hàng)
                </span>
                <form action="{{ route('shipper.orders.startDelivery', $order->id) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-sm fw-bold rounded-pill px-4 shadow-sm">
                    <i class="fa-solid fa-truck-ramp-box me-1.5"></i> Tiếp Nhận &amp; Bắt Đầu Đi Giao
                  </button>
                </form>
              </div>
            @else
              <span class="text-muted fs-10">Đơn hàng đang ở trạng thái: {{ $order->status_label }}</span>
            @endif
          </div>
        </div>

        <!-- MODAL 1: BƯU TÁ XÁC NHẬN ĐÃ GIAO HÀNG & BẮT BUỘC 1 ẢNH POD -->
        @if($order->shipping_status === 'shipping')
          <div class="modal fade" id="deliverModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content rounded-4 border-0 shadow-lg">
                <form action="{{ route('shipper.orders.deliver', $order->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="modal-header bg-success text-white py-3 px-4 rounded-top-4">
                    <h5 class="modal-title text-white fw-bold fs-8">
                      <i class="fa-solid fa-camera-retro me-2"></i> Xác Nhận Đã Giao Đơn #{{ $order->order_code }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body p-4">
                    <!-- THÔNG BÁO QUAN TRỌNG -->
                    <div class="alert alert-warning py-2.5 px-3 rounded-3 d-flex align-items-center gap-2 mb-3">
                      <i class="fa-solid fa-triangle-exclamation text-warning fs-5"></i>
                      <div class="fs-10 text-dark">
                        <strong>Quy định bưu tá:</strong> Bắt buộc phải chụp/tải lên <strong>1 ảnh bằng chứng giao hàng (POD)</strong> để hoàn tất đơn hàng và đối soát tiền COD.
                      </div>
                    </div>

                    <!-- KHỐI CHỤP / TẢI ẢNH BẰNG CHỨNG GIAO HÀNG -->
                    <div class="mb-3">
                      <label class="form-label fw-bold text-dark fs-9">
                        <i class="fa-solid fa-camera text-primary me-1"></i> 1. Chụp / Tải Lên Ảnh Giao Hàng (Bắt Buộc) <span class="text-danger">*</span>
                      </label>
                      <input type="file" name="delivery_proof_file" id="podInput{{ $order->id }}" accept="image/*" capture="environment" class="form-control rounded-3" onchange="previewShipperPod(this, '{{ $order->id }}')">
                      <input type="hidden" name="delivery_proof_image" id="podSampleHidden{{ $order->id }}" value="">
                    </div>

                    <!-- KHUNG XEM TRƯỚC ẢNH POD -->
                    <div class="mb-3">
                      <span class="form-label fw-bold text-muted fs-11 d-block mb-1">Xem trước ảnh bằng chứng (POD):</span>
                      <div class="pod-preview-box" id="podPreviewBox{{ $order->id }}">
                        <img id="podPreviewImg{{ $order->id }}" src="{{ asset('assets/img/delivery-proofs/sample_pod_1.jpg') }}" alt="Ảnh POD" style="display: block;">
                      </div>
                      <div class="d-flex justify-content-between align-items-center mt-1.5">
                        <small class="text-muted fs-11">Chụp rõ gói hàng trước cửa hoặc khách cầm gói hàng</small>
                        <button type="button" class="btn btn-link btn-sm p-0 fs-11 text-decoration-none" onclick="useSamplePod('{{ $order->id }}', 'assets/img/delivery-proofs/sample_pod_1.jpg')">
                          <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Dùng ảnh mẫu thử nghiệm
                        </button>
                      </div>
                    </div>

                    <!-- GHI CHÚ BƯU TÁ -->
                    <div class="mb-3">
                      <label class="form-label fw-bold text-dark fs-9">2. Ghi chú giao hàng (Tùy chọn):</label>
                      <textarea name="delivery_proof_note" rows="2" class="form-control rounded-3 fs-9" placeholder="Ví dụ: Đã giao tận tay khách hàng, kiểm tra niêm phong nguyên vẹn..."></textarea>
                    </div>

                    <!-- XÁC NHẬN TIỀN COD ĐÃ THU -->
                    @if($hasRemainingCod && $codAmount > 0)
                      <div class="p-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-bold text-danger fs-9">
                            <i class="fa-solid fa-coins me-1"></i>
                            @if($order->is_deposit_required || $order->payment_status === 'deposit_paid')
                              Tiền mặt phải thu (50% còn lại):
                            @else
                              Tiền COD phải thu từ khách:
                            @endif
                          </span>
                          <span class="fs-7 fw-black text-danger">{{ number_format($codAmount, 0, ',', '.') }}₫</span>
                        </div>
                        @if($order->is_deposit_required || $order->payment_status === 'deposit_paid')
                          <div class="text-primary fs-11 fw-bold mt-1">
                            <i class="fa-solid fa-circle-info me-1"></i> Khách hàng đã thanh toán trước 50% tiền cọc ({{ number_format($order->deposit_amount ?: round($order->total_amount * 0.5), 0, ',', '.') }}₫) qua {{ $order->payment_method_name }}. Bưu tá chỉ thu đúng số tiền 50% còn lại.
                          </div>
                        @endif
                        <small class="text-muted fs-11 d-block mt-1">Khi bấm xác nhận, hệ thống sẽ tự động cập nhật đơn hàng sang trạng thái "ĐÃ THANH TOÁN ĐỦ (100%)".</small>
                      </div>
                    @else
                      <div class="p-3 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="fw-bold text-success fs-9"><i class="fa-solid fa-circle-check me-1"></i> Tiền COD phải thu:</span>
                          <span class="fs-7 fw-black text-success">0₫ (Đã thanh toán trước 100%)</span>
                        </div>
                        <small class="text-muted fs-11 d-block mt-1">Đơn hàng đã được thanh toán trực tuyến 100%. Bưu tá chỉ giao kiện hàng và chụp ảnh POD xác nhận, KHÔNG thu thêm tiền mặt từ khách.</small>
                      </div>
                    @endif
                  </div>

                  <div class="modal-footer bg-light py-2.5 px-4 d-flex justify-content-between rounded-bottom-4">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill fw-bold px-4 shadow-sm">
                      <i class="fa-solid fa-check-circle me-1.5"></i> Hoàn Tất Giao Hàng
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- MODAL 2: BÁO CÁO SỰ CỐ / HẸN LẠI -->
          <div class="modal fade" id="reportModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content rounded-4 border-0 shadow-lg">
                <form action="{{ route('shipper.orders.reportIssue', $order->id) }}" method="POST">
                  @csrf
                  <div class="modal-header bg-warning text-dark py-3 px-4 rounded-top-4">
                    <h5 class="modal-title fw-bold fs-8">
                      <i class="fa-solid fa-triangle-exclamation me-1.5"></i> Báo Cáo Sự Cố - Đơn #{{ $order->order_code }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body p-4">
                    <div class="mb-3">
                      <label class="form-label fw-bold text-dark fs-9">Lý do giao hàng chưa thành công: <span class="text-danger">*</span></label>
                      <select name="issue_type" class="form-select rounded-3 fs-9" required>
                        <option value="Khách không nghe máy">Khách không nghe máy (Gọi nhiều lần)</option>
                        <option value="Khách hẹn giao lại vào hôm sau">Khách hẹn giao lại vào hôm sau</option>
                        <option value="Khách hẹn giao buổi tối">Khách hẹn giao vào buổi tối</option>
                        <option value="Sai địa chỉ hoặc số điện thoại">Sai địa chỉ hoặc số điện thoại</option>
                        <option value="Khách từ chối nhận hàng">Khách từ chối nhận hàng (Yêu cầu chuyển hoàn)</option>
                        <option value="Thời tiết xấu / Xe hỏng">Thời tiết xấu / Phương tiện giao hàng gặp sự cố</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold text-dark fs-9">Ghi chú cụ thể: <span class="text-danger">*</span></label>
                      <textarea name="issue_note" rows="3" class="form-control rounded-3 fs-9" required placeholder="Nhập chi tiết thời gian đã gọi, khách nói gì hoặc hẹn lại lúc nào..."></textarea>
                    </div>
                  </div>
                  <div class="modal-footer bg-light py-2.5 px-4 d-flex justify-content-between rounded-bottom-4">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning btn-sm rounded-pill text-dark fw-bold px-4">
                      <i class="fa-solid fa-paper-plane me-1"></i> Lưu Báo Cáo Về Kho
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @endif

        <!-- MODAL 3: XEM ẢNH POD ĐÃ GIAO -->
        @if($order->delivery_proof_image)
          <div class="modal fade" id="viewPodModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3 px-4 rounded-top-4">
                  <h6 class="modal-title text-white fw-bold fs-9">
                    <i class="fa-solid fa-image me-1.5"></i> Ảnh Bằng Chứng Giao Hàng #{{ $order->order_code }}
                  </h6>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3 text-center bg-black bg-opacity-10">
                  <img src="{{ $order->delivery_proof_url }}" alt="POD" class="img-fluid rounded-3 shadow-sm" style="max-height: 380px; object-fit: contain;">
                  @if($order->delivery_proof_note)
                    <div class="p-2.5 bg-white text-dark small rounded-3 border mt-2 text-start fs-10">
                      <strong>Ghi chú:</strong> {{ $order->delivery_proof_note }}
                    </div>
                  @endif
                </div>
                <div class="modal-footer bg-light py-2 px-3 d-flex justify-content-end rounded-bottom-4">
                  <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                </div>
              </div>
            </div>
          </div>
        @endif

      @empty
        <div class="card border-0 shadow-xs rounded-4 p-5 text-center bg-white my-4">
          <div class="text-muted mb-2">
            <i class="fa-solid fa-box-open fs-2 text-secondary opacity-50"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1">Hiện không có đơn hàng nào</h6>
          <p class="text-muted small mb-0">Bạn đã xử lý hết các đơn hàng trong mục này. Hãy kiểm tra lại tab khác hoặc chờ kho bàn giao thêm kiện hàng mới.</p>
        </div>
      @endforelse

      <!-- PHÂN TRANG -->
      @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-3">
          {{ $orders->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function previewShipperPod(input, orderId) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('podPreviewImg' + orderId);
          if (preview) {
            preview.src = e.target.result;
            preview.style.display = 'block';
          }
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function useSamplePod(orderId, imgPath) {
      const preview = document.getElementById('podPreviewImg' + orderId);
      const hiddenInput = document.getElementById('podSampleHidden' + orderId);
      const fileInput = document.getElementById('podInput' + orderId);
      if (preview) {
        preview.src = '{{ asset("") }}' + imgPath;
        preview.style.display = 'block';
      }
      if (hiddenInput) {
        hiddenInput.value = imgPath;
      }
      if (fileInput) {
        fileInput.value = '';
      }
    }
  </script>
</body>
</html>
