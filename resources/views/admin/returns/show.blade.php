@extends('layouts.admin')

@section('title', 'Chi Tiết Phiếu Đổi Trả #' . $return->return_code . ' | BeeStyle Admin')

@section('content')
<!-- TOP HEADER -->
<div class="mb-4 d-print-none">
  <div class="row gy-3 justify-content-between align-items-center">
    <div class="col-md">
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.returns.index') }}" class="btn btn-phoenix-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="mb-0 text-body-emphasis fw-bold font-monospace">#{{ $return->return_code }}</h2>
        {!! $return->status_badge !!}
        <span class="badge badge-phoenix {{ $return->type === 'exchange' ? 'badge-phoenix-info' : ($return->type === 'refund_only' ? 'badge-phoenix-warning' : 'badge-phoenix-danger') }} fs-10">
          {{ $return->type_label }}
        </span>
      </div>
      <p class="text-body-tertiary mb-0 fs-9">
        Đơn gốc: <a href="{{ route('admin.orders.show', $return->order_id) }}" class="fw-bold font-monospace text-primary text-decoration-none">#{{ $return->order->order_code ?? 'N/A' }}</a>
        • Ngày tạo: <strong>{{ $return->created_at ? $return->created_at->format('d/m/Y H:i:s') : 'N/A' }}</strong>
        • Khách hàng: <strong class="text-body-emphasis">{{ $return->user->name ?? ($return->order->customer_name ?? 'Khách Hàng') }}</strong>
      </p>
    </div>

    <div class="col-auto">
      <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-phoenix-primary btn-sm" onclick="window.print()">
          <i class="fa-solid fa-print me-1"></i> In Phiếu RMA
        </button>
        <a href="{{ route('admin.orders.show', $return->order_id) }}" class="btn btn-phoenix-secondary btn-sm">
          <i class="fa-solid fa-cart-shopping me-1"></i> Xem Đơn Gốc
        </a>
        <a href="{{ route('admin.returns.index') }}" class="btn btn-phoenix-secondary btn-sm">
          <i class="fa-solid fa-list me-1"></i> Danh Sách RMA
        </a>
      </div>
    </div>
  </div>
</div>

<!-- PRINT ONLY RMA SLIP -->
<div class="d-none d-print-block mb-4 p-4 border rounded bg-white text-dark">
  <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
    <div>
      <h3 class="fw-bold mb-0">BEESTYLE MENSWEAR</h3>
      <p class="small text-muted mb-0">Website: beestyle.vn • Hotline: 1900 8888 • Bộ Phận Chăm Sóc Khách Hàng</p>
    </div>
    <div class="text-end">
      <h4 class="fw-bold font-monospace mb-0">PHIẾU TIẾP NHẬN &amp; THẨM ĐỊNH ĐỔI TRẢ (RMA)</h4>
      <p class="small text-muted mb-0">Mã RMA: <strong>#{{ $return->return_code }}</strong> • Mã Đơn: <strong>#{{ $return->order->order_code ?? '' }}</strong></p>
    </div>
  </div>

  <div class="row g-3 small mb-3">
    <div class="col-6">
      <strong>Khách Hàng:</strong> {{ $return->user->name ?? $return->order->customer_name }} - {{ $return->user->phone ?? $return->order->customer_phone }}<br>
      <strong>Địa Chỉ:</strong> {{ $return->order->shipping_address ?? 'N/A' }}<br>
      <strong>Hình Thức RMA:</strong> {{ $return->type_label }}<br>
      <strong>Lý Do:</strong> {{ $return->reason }}
    </div>
    <div class="col-6 text-end">
      <strong>Ngày Tạo Phiếu:</strong> {{ $return->created_at ? $return->created_at->format('d/m/Y H:i') : '' }}<br>
      <strong>Số Tiền Quyết Toán:</strong> <strong class="fs-6 text-danger">{{ number_format($return->refund_amount, 0, ',', '.') }}₫</strong><br>
      @if($return->bank_name)
        <strong>Tài Khoản Hoàn Tiền:</strong> {{ $return->bank_name }} - {{ $return->bank_account_number }} ({{ $return->bank_account_name }})
      @endif
    </div>
  </div>

  <div class="border rounded p-3 mb-4">
    <h6 class="fw-bold mb-2 border-bottom pb-1">BẢNG KIỂM ĐỊNH CHẤT LƯỢNG KHO (QC CHECKLIST)</h6>
    <div class="row g-2 small">
      <div class="col-4">[ ] 1. Tem mác nguyên vẹn</div>
      <div class="col-4">[ ] 2. Hàng chưa qua giặt ủi</div>
      <div class="col-4">[ ] 3. Đầy đủ phụ kiện</div>
      <div class="col-4">[ ] 4. Lỗi đúng như phản ánh</div>
      <div class="col-4">[ ] 5. Đủ điều kiện nhập kho</div>
      <div class="col-4">[ ] 6. Đã quyết toán/đổi hàng</div>
    </div>
  </div>
</div>

<!-- 4-STEP RMA LIFECYCLE STEPPER -->
<div class="card border-0 shadow-sm mb-4 d-print-none">
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
    <h6 class="fw-bold text-body-emphasis mb-0">
      <i class="fa-solid fa-diagram-project me-2 text-primary"></i> Tiến Trình Xử Lý &amp; Quyết Toán RMA
    </h6>
    <span class="badge badge-phoenix badge-phoenix-secondary">{{ $return->status_label }}</span>
  </div>

  <div class="card-body">
    @php
      $rmaSteps = [
        1 => ['code' => 'pending', 'label' => '1. Khách Gửi Yêu Cầu', 'icon' => 'fa-file-lines', 'desc' => $return->created_at ? $return->created_at->format('d/m/Y H:i') : 'Đã gửi'],
        2 => ['code' => 'approved', 'label' => '2. CSKH Duyệt Phiếu', 'icon' => 'fa-clipboard-check', 'desc' => $return->approved_at ? $return->approved_at->format('d/m/Y H:i') : 'Chờ duyệt'],
        3 => ['code' => 'received', 'label' => '3. Kho Nhận & QC', 'icon' => 'fa-boxes-packing', 'desc' => $return->received_at ? $return->received_at->format('d/m/Y H:i') : 'Chờ nhận'],
        4 => ['code' => 'completed', 'label' => '4. Hoàn Tất Quyết Toán', 'icon' => 'fa-circle-check', 'desc' => $return->completed_at ? $return->completed_at->format('d/m/Y H:i') : 'Chờ hoàn tất'],
      ];

      $stepMap = ['pending' => 1, 'approved' => 2, 'received' => 3, 'completed' => 4, 'rejected' => 0];
      $currentStep = $stepMap[$return->status] ?? 1;
    @endphp

    @if($return->status === 'rejected')
      <div class="alert alert-danger py-3 px-4 rounded d-flex align-items-center gap-3 mb-0">
        <i class="fa-solid fa-ban fs-2 text-danger"></i>
        <div>
          <strong class="fs-9 d-block text-danger">YÊU CẦU ĐỔI TRẢ ĐÃ BỊ TỪ CHỐI (REJECTED)</strong>
          <span class="fs-10 text-danger">Lý do từ chối: {{ $return->rejected_reason ?? 'Không đáp ứng điều kiện theo chính sách đổi trả của BeeStyle.' }} (Thời gian: {{ $return->rejected_at ? $return->rejected_at->format('d/m/Y H:i') : '' }})</span>
        </div>
      </div>
    @else
      <div class="row g-2 text-center position-relative my-2">
        @foreach($rmaSteps as $sNum => $sData)
          @php
            $isDone = $currentStep > $sNum;
            $isCurrent = $currentStep == $sNum;
          @endphp
          <div class="col-3">
            <div class="d-flex flex-column align-items-center">
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm mb-2"
                   style="width: 42px; height: 42px; font-size: 1rem; 
                          background-color: {{ $isCurrent ? '#3874ff' : ($isDone ? '#25b003' : 'var(--phoenix-gray-200)') }}; 
                          color: {{ $isDone || $isCurrent ? '#ffffff' : 'var(--phoenix-gray-600)' }};">
                <i class="fa-solid {{ $isDone ? 'fa-check' : $sData['icon'] }}"></i>
              </div>
              <span class="fw-bold text-truncate d-block fs-10" style="color: {{ $isCurrent ? '#3874ff' : ($isDone ? '#25b003' : 'var(--phoenix-gray-600)') }};">
                {{ $sData['label'] }}
              </span>
              <small class="text-body-tertiary d-none d-md-block fs-11">{{ $sData['desc'] }}</small>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <!-- QUICK ACTION BUTTONS -->
    @if(!$return->isFinalStatus())
      <div class="pt-3 mt-3 border-top border-translucent d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="fs-10 text-body-tertiary">
          <i class="fa-solid fa-bolt text-warning me-1"></i> Chuyển nhanh tiến trình RMA:
        </div>
        <div class="d-flex gap-2 flex-wrap">
          @if($return->status === 'pending')
            <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#approveReturnModal">
              <i class="fa-solid fa-check me-1"></i> Bước 2: Duyệt Phiếu (Gửi Hướng Dẫn)
            </button>
          @elseif($return->status === 'approved')
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#receiveReturnModal">
              <i class="fa-solid fa-boxes-packing me-1"></i> Bước 3: Kho Nhận &amp; QC Hàng
            </button>
          @elseif($return->status === 'received')
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#completeReturnModal">
              <i class="fa-solid fa-circle-check me-1"></i> Bước 4: Hoàn Tất Quyết Toán
            </button>
          @endif

          <button type="button" class="btn btn-sm btn-phoenix-danger" data-bs-toggle="modal" data-bs-target="#rejectReturnModal">
            <i class="fa-solid fa-ban me-1"></i> Từ Chối Yêu Cầu
          </button>
        </div>
      </div>
    @endif
  </div>
</div>

<div class="row g-4 d-print-none">
  <!-- CỘT TRÁI -->
  <div class="col-12 col-lg-7">
    <!-- NỘI DUNG YÊU CẦU & BẰNG CHỨNG -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-body-emphasis mb-0">Nội Dung Yêu Cầu &amp; Minh Chứng</h5>
        <span class="badge badge-phoenix {{ $return->type === 'exchange' ? 'badge-phoenix-info' : ($return->type === 'refund_only' ? 'badge-phoenix-warning' : 'badge-phoenix-danger') }}">
          {{ $return->type_label }}
        </span>
      </div>

      <div class="card-body">
        <div class="p-3 bg-body-tertiary rounded border border-translucent d-flex flex-column gap-2 fs-10 mb-3">
          <div class="d-flex justify-content-between">
            <span class="text-body-tertiary">Hình thức xử lý:</span>
            <strong class="text-body-emphasis">{{ $return->type_label }}</strong>
          </div>
          <div class="d-flex justify-content-between">
            <span class="text-body-tertiary">Lý do đổi trả:</span>
            <strong class="text-danger">{{ $return->reason }}</strong>
          </div>
          @if($return->type === 'exchange')
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-body-tertiary">Kích cỡ / Màu sắc đổi:</span>
              <span class="badge badge-phoenix badge-phoenix-warning">Size {{ $return->exchange_size ?? 'M' }} | Màu: {{ $return->exchange_color ?? 'Chuẩn' }}</span>
            </div>
          @else
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-body-tertiary">Số tiền hoàn dự kiến:</span>
              <strong class="text-danger fs-8 fw-bold font-monospace">{{ number_format($return->refund_amount, 0, ',', '.') }}₫</strong>
            </div>
          @endif
          @if($return->customer_notes)
            <div class="pt-2 border-top border-translucent">
              <span class="text-body-tertiary d-block mb-1">Ghi chú từ khách hàng:</span>
              <div class="p-2 bg-body-emphasis rounded border border-translucent text-body-emphasis fst-italic fs-10">
                "{{ $return->customer_notes }}"
              </div>
            </div>
          @endif
        </div>

        <!-- HÌNH ẢNH / BẰNG CHỨNG -->
        <h6 class="fw-bold text-body-emphasis mb-2 fs-10">
          <i class="fa-solid fa-images me-1 text-warning"></i> Ảnh Minh Chứng Tem Mác &amp; Lỗi Sản Phẩm ({{ count($return->image_proofs ?? []) }})
        </h6>
        @if(!empty($return->image_proofs) && is_array($return->image_proofs) && count($return->image_proofs) > 0)
          <div class="row g-2">
            @foreach($return->image_proofs as $img)
              <div class="col-4 col-md-3">
                <div class="position-relative overflow-hidden rounded border border-translucent bg-body-tertiary" onclick="openImageLightbox('{{ asset($img) }}')" style="cursor: pointer;">
                  <img src="{{ asset($img) }}" alt="Proof Image" class="img-fluid w-100" style="height: 100px; object-fit: cover;">
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="p-3 bg-body-tertiary rounded text-body-tertiary fs-10 text-center border border-dashed border-translucent">
            <i class="fa-regular fa-image fs-4 d-block mb-1"></i>
            Khách hàng không đính kèm hình ảnh bổ sung.
          </div>
        @endif
      </div>
    </div>

    <!-- TÀI KHOẢN NGÂN HÀNG HOÀN TIỀN -->
    @if($return->type !== 'exchange')
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
          <h5 class="fw-bold text-body-emphasis mb-0">Tài Khoản Hoàn Tiền</h5>
          <span class="badge badge-phoenix badge-phoenix-success">
            <i class="fa-solid fa-money-bill-transfer me-1"></i> Chuyển Khoản
          </span>
        </div>

        <div class="card-body">
          <div class="p-3 rounded text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-bold text-warning font-monospace fs-10">
                <i class="fa-solid fa-building-columns me-1"></i>{{ $return->bank_name ?: 'NGÂN HÀNG THƯƠNG MẠI' }}
              </span>
              <i class="fa-solid fa-microchip fs-6 text-warning"></i>
            </div>
            
            <div class="d-flex align-items-center justify-content-between my-2">
              <span class="fs-6 fw-bold font-monospace" id="bankAccText">
                {{ $return->bank_account_number ?: '•••• •••• •••• ••••' }}
              </span>
              @if($return->bank_account_number)
                <button type="button" class="btn btn-sm btn-light bg-opacity-20 text-white border-0 px-2 py-1 fs-11" onclick="copyToClipboard('{{ $return->bank_account_number }}', this)">
                  <i class="fa-regular fa-copy me-1"></i> Chép STK
                </button>
              @endif
            </div>

            <div class="d-flex justify-content-between align-items-end pt-2 border-top border-white border-opacity-10 mt-2 fs-10">
              <div>
                <small class="text-white-50 d-block fs-11">CHỦ TÀI KHOẢN</small>
                <strong class="text-uppercase text-white">{{ $return->bank_account_name ?: 'CHƯA CẬP NHẬT' }}</strong>
              </div>
              <div class="text-end">
                <small class="text-white-50 d-block fs-11">SỐ TIỀN HOÀN</small>
                <strong class="text-warning fs-9 font-monospace">{{ number_format($return->refund_amount, 0, ',', '.') }}₫</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  <!-- CỘT PHẢI -->
  <div class="col-12 col-lg-5">
    <!-- SẢN PHẨM LIÊN QUAN TRONG ĐƠN -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-body-emphasis mb-0">Sản Phẩm Đổi / Trả</h5>
        <span class="badge badge-phoenix badge-phoenix-secondary">{{ $return->order ? $return->order->items->count() : 0 }} món</span>
      </div>

      <div class="card-body">
        @if($return->order)
          <div class="d-flex flex-column gap-2 mb-3">
            @foreach($return->order->items as $item)
              @php
                $isTargetItem = $return->order_item_id && $return->order_item_id == $item->id;
              @endphp
              <div class="p-2 rounded border border-translucent d-flex align-items-center justify-content-between {{ $isTargetItem ? 'bg-warning-subtle' : 'bg-body-tertiary' }}">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 40px; height: 40px; object-fit: contain;" class="rounded border border-translucent bg-body-emphasis">
                  <div>
                    <strong class="fs-10 text-body-emphasis d-block text-truncate" style="max-width: 160px;">{{ $item->product_name }}</strong>
                    <small class="text-body-tertiary fs-11">Màu: {{ $item->color ?? 'Chuẩn' }} / Size {{ $item->size ?? 'M' }} • x{{ $item->quantity }}</small>
                  </div>
                </div>
                <div class="text-end">
                  <span class="fs-10 fw-bold text-body-emphasis font-monospace">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                  @if($isTargetItem)
                    <span class="badge badge-phoenix badge-phoenix-danger d-block mt-0.5 fs-11">Mục RMA</span>
                  @endif
                </div>
              </div>
            @endforeach
          </div>

          <div class="p-3 bg-body-tertiary rounded border border-translucent fs-10">
            <div class="d-flex justify-content-between text-body-tertiary mb-1">
              <span>Mã đơn gốc:</span>
              <a href="{{ route('admin.orders.show', $return->order->id) }}" class="fw-bold font-monospace text-primary text-decoration-none">#{{ $return->order->order_code }}</a>
            </div>
            <div class="d-flex justify-content-between text-body-tertiary mb-1">
              <span>Tổng tiền đơn:</span>
              <strong class="text-danger font-monospace fs-9">{{ number_format($return->order->total_amount, 0, ',', '.') }}₫</strong>
            </div>
            <div class="d-flex justify-content-between text-body-tertiary mb-1">
              <span>Phương thức thanh toán:</span>
              <span class="text-body-emphasis fw-semibold">{{ $return->order->payment_method_name }}</span>
            </div>
            <div class="d-flex justify-content-between text-body-tertiary">
              <span>Trạng thái thanh toán:</span>
              <span class="badge badge-phoenix {{ $return->order->payment_status === 'refunded' ? 'badge-phoenix-danger' : ($return->order->payment_status === 'paid' ? 'badge-phoenix-success' : 'badge-phoenix-warning') }}">
                {{ $return->order->payment_status_label }}
              </span>
            </div>
          </div>
        @endif
      </div>
    </div>

    <!-- GHI CHÚ NỘI BỘ & CẬP NHẬT TRẠNG THÁI -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="fw-bold text-body-emphasis mb-0">Cập Nhật Trạng Thái &amp; Ghi Chú Admin</h5>
      </div>

      <div class="card-body">
        <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
          @csrf

          @if($return->isFinalStatus())
            <div class="alert alert-subtle-{{ $return->status === 'completed' ? 'success' : 'danger' }} d-flex align-items-center gap-2 py-2 px-3 mb-3 fs-10 rounded">
              <i class="fa-solid fa-lock text-{{ $return->status === 'completed' ? 'success' : 'danger' }} fs-9"></i>
              <div>
                <strong>Tiến trình RMA đã kết thúc ({{ $return->status_label }}):</strong> 
                Trạng thái phiếu đổi trả đã được khóa an toàn để bảo vệ số liệu hoàn tiền, đơn đổi mới và tồn kho.
              </div>
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Trạng thái phiếu:</label>
            @php
              $allReturnStatuses = [
                'pending'   => '1. Chờ duyệt yêu cầu',
                'approved'  => '2. Đã duyệt (Chờ khách gửi hàng về kho)',
                'received'  => '3. Kho đã nhận hàng & kiểm tra QC',
                'completed' => '4. Hoàn tất xử lý (Hoàn tiền / Đổi size)',
                'rejected'  => '0. Từ chối yêu cầu',
              ];
            @endphp
            <select name="status" class="form-select" {{ $return->isFinalStatus() ? 'disabled' : '' }}>
              @foreach($allReturnStatuses as $optKey => $optLabel)
                @php
                  $isCurrent = $return->status === $optKey;
                  $canSelect = $isCurrent || $return->canTransitionTo($optKey);
                @endphp
                <option value="{{ $optKey }}" {{ $isCurrent ? 'selected' : '' }} {{ !$canSelect ? 'disabled class=text-muted' : '' }}>
                  {{ $optLabel }} {{ !$canSelect ? '(Đã khóa/không hợp lệ)' : ($isCurrent ? '— [Hiện tại]' : '') }}
                </option>
              @endforeach
            </select>
            @if($return->isFinalStatus())
              <input type="hidden" name="status" value="{{ $return->status }}">
            @endif
            <small class="text-body-tertiary fs-11 mt-1 d-block">
              <i class="fa-solid fa-shield-halved text-primary me-1"></i> Hệ thống tự động khóa các bước trước đó theo quy tắc vận hành TMĐT 1 chiều.
            </small>
          </div>

          @if(!$return->isFinalStatus())
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="restock" value="1" id="restockCheck" checked>
                <label class="form-check-label fs-10 text-body-tertiary" for="restockCheck">
                  Tự động cộng lại số lượng vào kho hàng khi hoàn tất
                </label>
              </div>
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Ghi chú nội bộ:</label>
            <textarea name="admin_notes" class="form-control" rows="3" placeholder="Nhập ghi chú xử lý hoặc mã chuyển tiền hoàn...">{{ $return->admin_notes }}</textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 fs-9">
            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- MODALS THAO TÁC -->
<!-- 1. DUYỆT YÊU CẦU -->
<div class="modal fade" id="approveReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="approved">
        <div class="modal-header border-bottom border-translucent bg-body-emphasis">
          <h5 class="modal-title fw-bold text-body-emphasis"><i class="fa-solid fa-check-circle me-2 text-info"></i> Duyệt Yêu Cầu Đổi Trả</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <p class="fs-10 text-body-tertiary mb-3">Xác nhận duyệt yêu cầu đổi trả này và gửi thông tin hướng dẫn gửi hàng cho khách:</p>
          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Hướng dẫn đóng gói &amp; địa chỉ kho:</label>
            <textarea name="warehouse_instruction" class="form-control" rows="3">Quý khách vui lòng đóng gói sản phẩm còn nguyên tem mác và gửi về: Tổng Kho BeeStyle - Số 123 Cầu Giấy, Hà Nội (Hotline: 1900 8888). Thời hạn gửi trong vòng 3 ngày.</textarea>
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-info text-white btn-sm px-3">Xác Nhận Duyệt Phiếu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 2. KHO NHẬN HÀNG -->
<div class="modal fade" id="receiveReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="received">
        <div class="modal-header border-bottom border-translucent bg-body-emphasis">
          <h5 class="modal-title fw-bold text-body-emphasis"><i class="fa-solid fa-boxes-packing me-2 text-primary"></i> Kho Xác Nhận Đã Nhận Kiện Hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <p class="fs-10 text-body-tertiary mb-3">Vui lòng kiểm định chất lượng sản phẩm thực tế khi bưu tá bàn giao về kho:</p>
          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Tình trạng thực tế sản phẩm:</label>
            <select name="received_condition" class="form-select">
              <option value="Hàng nguyên vẹn tem mác, đúng quy cách, đạt chuẩn đổi trả">Hàng nguyên vẹn tem mác, đúng quy cách, đạt chuẩn đổi trả</option>
              <option value="Hàng đúng lỗi sản xuất như khách báo">Hàng đúng lỗi sản xuất như khách báo</option>
              <option value="Hàng bị móp méo hộp ngoài nhưng sản phẩm áo bên trong nguyên vẹn">Hàng bị móp méo hộp ngoài nhưng sản phẩm áo bên trong nguyên vẹn</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary btn-sm px-3">Xác Nhận Nhận Hàng</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 3. HOÀN TẤT QUYẾT TOÁN -->
<div class="modal fade" id="completeReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="completed">
        <div class="modal-header border-bottom border-translucent bg-body-emphasis">
          <h5 class="modal-title fw-bold text-body-emphasis"><i class="fa-solid fa-circle-check me-2 text-success"></i> Hoàn Tất Quyết Toán RMA</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          @if($return->type === 'exchange')
            <p class="fs-10 text-body-tertiary mb-3">Nhập thông tin kiện hàng đổi size/màu mới gửi lại cho khách hàng:</p>
            <div class="mb-3">
              <label class="form-label fs-9 fw-semibold">Đơn vị vận chuyển:</label>
              <input type="text" name="exchange_carrier" value="Giao Hàng Nhanh (GHN)" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label fs-9 fw-semibold">Mã vận đơn mới:</label>
              <input type="text" name="exchange_tracking_code" placeholder="VD: GHN-8823910" class="form-control font-monospace" required>
            </div>
          @else
            <p class="fs-10 text-body-tertiary mb-3">Xác nhận chuyển khoản hoàn tiền cho khách hàng:</p>
            <div class="mb-3">
              <label class="form-label fs-9 fw-semibold">Số tiền hoàn thực tế (VNĐ):</label>
              <input type="number" name="refund_amount" value="{{ $return->refund_amount }}" class="form-control font-monospace text-danger fw-bold fs-8">
            </div>
            <div class="mb-3">
              <label class="form-label fs-9 fw-semibold">Mã giao dịch ngân hàng / Trace No:</label>
              <input type="text" name="bank_ref_code" placeholder="VD: VCB-982341 hoặc MB-0912" class="form-control font-monospace" required>
            </div>
          @endif

          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" name="restock" value="1" id="modalRestockCheck" checked>
            <label class="form-check-label fs-10 text-body-tertiary" for="modalRestockCheck">
              Tự động cập nhật số lượng tồn kho sản phẩm
            </label>
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-success btn-sm px-3">Xác Nhận Quyết Toán</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 4. TỪ CHỐI YÊU CẦU -->
<div class="modal fade" id="rejectReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="rejected">
        <div class="modal-header border-bottom border-translucent bg-body-emphasis">
          <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-ban me-2"></i> Từ Chối Yêu Cầu Đổi Trả</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <p class="fs-10 text-body-tertiary mb-3">Vui lòng nhập lý do từ chối để thông báo minh bạch tới khách hàng:</p>
          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Lý do từ chối <span class="text-danger">*</span></label>
            <textarea name="rejected_reason" class="form-control" rows="3" required placeholder="Ví dụ: Sản phẩm đã bị cắt tem mác hoặc đã qua giặt ủi, không đủ điều kiện đổi trả theo chính sách của BeeStyle..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-danger btn-sm px-3">Xác Nhận Từ Chối</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- LIGHTBOX MODAL -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 bg-transparent">
      <div class="modal-body p-0 text-center position-relative">
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal"></button>
        <img id="lightboxImg" src="" alt="Proof Preview" class="img-fluid rounded shadow-lg border" style="max-height: 80vh; object-fit: contain;">
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
      const originalHtml = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check text-success"></i> Đã chép!';
      setTimeout(() => {
        btn.innerHTML = originalHtml;
      }, 2000);
    });
  }

  function openImageLightbox(url) {
    const modalImg = document.getElementById('lightboxImg');
    if (modalImg) modalImg.src = url;
    const modal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
    modal.show();
  }
</script>
@endpush
@endsection
