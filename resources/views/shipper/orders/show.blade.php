<!DOCTYPE html>
<html lang="vi" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chi Tiết Đơn Hàng #{{ $order->order_code }} - BeeStyle Express</title>
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
  <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    body {
      background-color: #f1f5f9;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      padding-bottom: 75px;
    }
    .order-card {
      border: 1px solid #e2e8f0;
      border-radius: 18px;
      background: #ffffff;
      box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    }
    .cod-badge {
      background: #fef2f2;
      color: #dc2626;
      border: 1px dashed #f87171;
      font-size: 1.15rem;
      font-weight: 800;
      border-radius: 12px;
      padding: 8px 14px;
      display: inline-block;
    }
    .paid-badge {
      background: #f0fdf4;
      color: #16a34a;
      border: 1px solid #bbf7d0;
      font-weight: 700;
      border-radius: 12px;
      padding: 8px 14px;
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
    .pod-preview-box {
      width: 100%;
      height: 220px;
      border: 2px dashed #cbd5e1;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background-color: #f8fafc;
    }
    .pod-preview-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>
</head>
<body class="p-2 p-md-3">
  <div class="container" style="max-width: 720px;">
    
    <!-- TOP BAR -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <a href="{{ route('shipper.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Danh Sách Đơn
      </a>
      <div class="d-flex align-items-center gap-1.5">
        <span class="badge bg-primary text-white font-monospace fs-9 px-3 py-1.5 rounded-pill shadow-xs">
          #{{ $order->order_code }}
        </span>
      </div>
    </div>

    <!-- FLASH MESSAGES -->
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

    <div class="order-card p-3 p-md-4 mb-3">
      <!-- HEADER CARD -->
      <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3 flex-wrap gap-2">
        <div>
          <span class="text-muted fs-11 text-uppercase fw-bold d-block">Mã Vận Đơn Bưu Tá</span>
          <h4 class="text-primary font-monospace fw-bold mb-0">
            <i class="fa-solid fa-barcode me-1.5"></i>{{ $order->tracking_code ?: 'GHTK-' . strtoupper(substr(md5($order->order_code), 0, 8)) }}
          </h4>
        </div>
        <div class="text-end">
          @if($order->shipping_status === 'shipping')
            <span class="badge bg-info-subtle text-info fw-bold rounded-pill px-3 py-1.5 fs-9">
              <i class="fa-solid fa-truck-moving me-1"></i> Đang Đi Giao
            </span>
          @elseif($order->shipping_status === 'delivered')
            <span class="badge bg-success-subtle text-success fw-bold rounded-pill px-3 py-1.5 fs-9">
              <i class="fa-solid fa-circle-check me-1"></i> Đã Giao Hàng
            </span>
          @elseif($order->shipping_status === 'completed')
            <span class="badge bg-success text-white fw-bold rounded-pill px-3 py-1.5 fs-9">
              <i class="fa-solid fa-check-double me-1"></i> Hoàn Tất
            </span>
          @elseif(in_array($order->shipping_status, ['confirmed', 'processing']))
            <span class="badge bg-warning-subtle text-warning-emphasis fw-bold rounded-pill px-3 py-1.5 fs-9 border border-warning">
              <i class="fa-solid fa-boxes-packing me-1"></i> Chờ Bưu Tá Lấy Hàng
            </span>
          @else
            <span class="badge bg-secondary-subtle text-secondary fw-bold rounded-pill px-3 py-1.5 fs-9">
              {{ $order->status_label }}
            </span>
          @endif
          <div class="text-muted fs-11 mt-1">
            <i class="fa-regular fa-clock me-1"></i> {{ $order->estimated_delivery_text }}
          </div>
        </div>
      </div>

      <!-- THÔNG TIN NGƯỜI NHẬN -->
      <div class="mb-4">
        <h6 class="fw-bold text-dark mb-2 fs-8">
          <i class="fa-solid fa-user-tag me-1.5 text-primary"></i> Thông Tin Người Nhận
        </h6>
        <div class="bg-light p-3 rounded-3 border">
          <div class="d-flex justify-content-between align-items-center mb-1.5">
            <strong class="fs-7 text-dark">{{ $order->customer_name }}</strong>
            <span class="badge bg-white text-dark border font-monospace fs-9">{{ $order->customer_phone }}</span>
          </div>
          <p class="text-secondary small mb-2.5 lh-base">
            <i class="fa-solid fa-location-dot me-1 text-danger"></i>
            {{ $order->full_shipping_address }}
          </p>

          @if($order->notes)
            <div class="p-2 bg-white rounded-2 border text-dark fs-10 mb-2.5">
              <i class="fa-regular fa-message me-1 text-warning"></i>
              <strong>Khách dặn:</strong> {{ $order->notes }}
            </div>
          @endif

          <!-- 2 NÚT THAO TÁC: GỌI ĐIỆN & CHỈ ĐƯỜNG BẢN ĐỒ -->
          <div class="d-flex gap-2">
            <a href="tel:{{ $order->customer_phone }}" class="btn action-btn-call btn-sm flex-fill d-flex align-items-center justify-content-center gap-1.5 py-2">
              <i class="fa-solid fa-phone"></i> Gọi Điện: {{ $order->customer_phone }}
            </a>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->full_shipping_address) }}" target="_blank" class="btn action-btn-map btn-sm flex-fill d-flex align-items-center justify-content-center gap-1.5 py-2">
              <i class="fa-solid fa-map-location-dot"></i> Bản Đồ Dẫn Đường
            </a>
          </div>
        </div>
      </div>

      <!-- SẢN PHẨM TRONG KIỆN HÀNG -->
      <div class="mb-4">
        <h6 class="fw-bold text-dark mb-2 fs-8 d-flex justify-content-between align-items-center">
          <span><i class="fa-solid fa-box-open me-1.5 text-primary"></i> Sản Phẩm Trong Kiện ({{ $order->items->sum('quantity') }} cái)</span>
          <small class="text-muted fw-normal fs-11">{{ $order->shipping_carrier ?: 'GHTK' }}</small>
        </h6>
        <div class="list-group rounded-3 shadow-2xs">
          @foreach($order->items as $item)
            <div class="list-group-item d-flex justify-content-between align-items-center py-2.5 px-3">
              <div class="min-w-0 pr-2">
                <div class="fw-bold text-dark text-truncate fs-9">• {{ $item->product_name }}</div>
                <small class="text-muted">Phân loại: <strong>{{ $item->color ?? 'Chuẩn' }}</strong> | Size: <strong>{{ $item->size ?? 'M' }}</strong></small>
              </div>
              <span class="badge bg-light text-dark border fs-9 fw-bold shrink-0">x{{ $item->quantity }}</span>
            </div>
          @endforeach
        </div>
      </div>

      <!-- KHỐI TÀI CHÍNH & COD -->
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

      <div class="mb-4 p-3.5 bg-light rounded-3 border">
        <div class="d-flex justify-content-between align-items-center mb-1.5">
          <span class="text-muted small">Phương thức thanh toán:</span>
          <span class="fw-bold text-dark">{{ $order->payment_method_name }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-1.5">
          <span class="text-muted small">Trạng thái thanh toán:</span>
          <span class="badge {{ $isFullyPaid ? 'bg-success' : ($order->payment_status === 'deposit_paid' ? 'bg-warning text-dark' : 'bg-secondary') }}">
            {{ $order->payment_status_label }}
          </span>
        </div>
        @if($order->is_deposit_required || $order->payment_status === 'deposit_paid')
          <div class="d-flex justify-content-between align-items-center mb-1.5 text-primary">
            <span class="small fw-semibold">Đã cọc trước (50%):</span>
            <strong class="font-monospace">{{ number_format($order->deposit_amount ?: round($order->total_amount * 0.5), 0, ',', '.') }}₫</strong>
          </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="text-muted small">Tổng giá trị đơn hàng:</span>
          <span class="fw-bold font-monospace">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
        </div>

        <hr class="my-2 border-secondary border-opacity-25">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-1">
          <div>
            <strong class="text-dark fs-9 d-block">TIỀN COD BƯU TÁ PHẢI THU:</strong>
            @if($hasRemainingCod && $codAmount > 0)
              <small class="text-danger fw-semibold">
                {{ ($order->is_deposit_required || $order->payment_status === 'deposit_paid') ? 'Thu 50% tiền mặt còn lại khi trao hàng' : 'Thu tiền mặt khi nhận hàng' }}
              </small>
            @else
              <small class="text-success fw-semibold">Đã thanh toán trước 100%, không thu tiền mặt</small>
            @endif
          </div>
          <div class="fs-4 fw-black {{ ($hasRemainingCod && $codAmount > 0) ? 'text-danger' : 'text-success' }} font-monospace">
            {{ number_format($codAmount, 0, ',', '.') }}₫
          </div>
        </div>
      </div>

      <!-- KHỐI HÀNH ĐỘNG DÀNH CHO BƯU TÁ -->
      <div class="pt-2 border-top">
        @if(in_array($order->shipping_status, ['confirmed', 'processing']))
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="badge bg-warning text-dark border border-warning fs-9 fw-bold p-2">
              <i class="fa-solid fa-boxes-packing me-1"></i> Kho Đã Đóng Gói Xong (Chờ Bưu Tá Lấy)
            </span>
            <form action="{{ route('shipper.orders.startDelivery', $order->id) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-primary btn-sm fw-bold rounded-pill px-4 shadow-sm">
                <i class="fa-solid fa-truck-ramp-box me-1.5"></i> Tiếp Nhận &amp; Bắt Đầu Đi Giao
              </button>
            </form>
          </div>
        @elseif($order->shipping_status === 'shipping')
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <button type="button" class="btn btn-warning btn-sm fw-bold rounded-pill text-dark px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#reportModal{{ $order->id }}">
              <i class="fa-solid fa-triangle-exclamation me-1"></i> Báo Sự Cố
            </button>
            <button type="button" class="btn btn-success btn-sm fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#deliverModal{{ $order->id }}">
              <i class="fa-solid fa-camera me-1.5"></i> Chụp Ảnh &amp; Xác Nhận Đã Giao
            </button>
          </div>
        @elseif(in_array($order->shipping_status, ['delivered', 'completed']))
          <div class="alert alert-success d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 rounded-3 mb-0">
            <div>
              <i class="fa-solid fa-circle-check text-success fs-5 me-2"></i>
              <strong>Đơn hàng đã được giao thành công!</strong>
              <div class="text-muted fs-11 mt-0.5">Thời gian: {{ $order->delivered_at ? $order->delivered_at->format('H:i, d/m/Y') : 'Vừa xong' }}</div>
            </div>
            @if($order->delivery_proof_image)
              <a href="{{ $order->delivery_proof_url }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill fw-bold">
                <i class="fa-solid fa-image me-1"></i> Xem Ảnh POD
              </a>
            @endif
          </div>
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
                  <i class="fa-solid fa-triangle-exclamation me-2"></i> Báo Cáo Sự Cố Giao Hàng #{{ $order->order_code }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body p-4">
                <div class="mb-3">
                  <label class="form-label fw-bold text-dark fs-9">Loại sự cố gặp phải <span class="text-danger">*</span></label>
                  <select name="issue_type" class="form-select rounded-3 fs-9" required>
                    <option value="" disabled selected>-- Chọn loại sự cố --</option>
                    <option value="Khách không nghe máy (Gọi 3 lần)">Khách không nghe máy (Gọi 3 lần)</option>
                    <option value="Khách hẹn giao lại vào hôm sau">Khách hẹn giao lại vào hôm sau</option>
                    <option value="Khách đi vắng / Không có nhà">Khách đi vắng / Không có nhà</option>
                    <option value="Khách từ chối nhận (Không đúng hàng / Đổi ý)">Khách từ chối nhận (Không đúng hàng / Đổi ý)</option>
                    <option value="Sai địa chỉ / Không tìm thấy số nhà">Sai địa chỉ / Không tìm thấy số nhà</option>
                    <option value="Thời tiết xấu / Xe hỏng hóc">Thời tiết xấu / Xe hỏng hóc</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold text-dark fs-9">Chi tiết cụ thể <span class="text-danger">*</span></label>
                  <textarea name="issue_note" rows="3" class="form-control rounded-3 fs-9" required placeholder="Ghi chú thêm: VD khách hẹn giao sau 18h hoặc người nhà nhận thay..."></textarea>
                </div>
              </div>
              <div class="modal-footer bg-light py-2.5 px-4 d-flex justify-content-between rounded-bottom-4">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-warning btn-sm rounded-pill fw-bold text-dark px-4 shadow-sm">
                  <i class="fa-solid fa-paper-plane me-1"></i> Gửi Báo Cáo
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif

  </div>

  <script src="{{ asset('assets/js/phoenix.js') }}"></script>
  <script>
    function previewShipperPod(input, orderId) {
      const previewBox = document.getElementById('podPreviewBox' + orderId);
      const previewImg = document.getElementById('podPreviewImg' + orderId);
      const sampleHidden = document.getElementById('podSampleHidden' + orderId);
      if (sampleHidden) sampleHidden.value = '';

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          if (previewImg) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
          }
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    function useSamplePod(orderId, sampleUrl) {
      const previewImg = document.getElementById('podPreviewImg' + orderId);
      const sampleHidden = document.getElementById('podSampleHidden' + orderId);
      const fileInput = document.getElementById('podInput' + orderId);

      if (previewImg) {
        previewImg.src = '{{ asset("") }}' + sampleUrl;
        previewImg.style.display = 'block';
      }
      if (sampleHidden) {
        sampleHidden.value = sampleUrl;
      }
      if (fileInput) {
        fileInput.value = '';
      }
      alert('Đã chọn ảnh mẫu bằng chứng giao hàng (POD) thử nghiệm!');
    }
  </script>
</body>
</html>
