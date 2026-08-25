@extends('layouts.admin')

@section('title', 'Chi Tiết Phiếu Đổi Trả #' . $return->return_code . ' | BeeStyle Admin')

@section('content')
<!-- TOP HEADER & BREADCRUMB -->
<div class="mb-4 d-print-none">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center shadow-xs" style="width: 34px; height: 34px;">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h3 class="fw-black text-dark mb-0 font-monospace" style="letter-spacing: -0.5px;">#{{ $return->return_code }}</h3>
        {!! $return->status_badge !!}
        <span class="badge {{ $return->type === 'exchange' ? 'bg-info-subtle text-info' : ($return->type === 'refund_only' ? 'bg-warning-subtle text-dark' : 'bg-danger-subtle text-danger') }} border px-3 py-1.5 fw-bold rounded-pill">
          <i class="fa-solid {{ $return->type === 'exchange' ? 'fa-arrow-right-arrow-left' : ($return->type === 'refund_only' ? 'fa-hand-holding-dollar' : 'fa-box-open') }} me-1"></i>
          {{ $return->type_label }}
        </span>
      </div>
      <p class="text-muted small mb-0">
        Đơn hàng gốc: <a href="{{ route('admin.orders.show', $return->order_id) }}" class="fw-bold font-monospace text-primary text-decoration-none">#{{ $return->order->order_code ?? 'N/A' }}</a>
        • Ngày gửi yêu cầu: <strong>{{ $return->created_at ? $return->created_at->format('d/m/Y H:i:s') : 'N/A' }}</strong>
        • Khách hàng: <strong class="text-dark">{{ $return->user->name ?? ($return->order->customer_name ?? 'Khách Hàng') }}</strong>
      </p>
    </div>

    <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-primary btn-sm px-3.5 py-2 fw-bold rounded-pill shadow-xs d-flex align-items-center gap-1.5" onclick="window.print()">
        <i class="fa-solid fa-print"></i> In Phiếu Thẩm Định RMA
      </button>
      <a href="{{ route('admin.orders.show', $return->order_id) }}" class="btn btn-outline-secondary btn-sm px-3.5 py-2 fw-bold rounded-pill shadow-xs">
        <i class="fa-solid fa-cart-shopping me-1 text-secondary"></i> Xem Đơn Gốc
      </a>
      <a href="{{ route('admin.returns.index') }}" class="btn btn-dark btn-sm px-3.5 py-2 fw-bold rounded-pill shadow-xs">
        <i class="fa-solid fa-list me-1"></i> Danh Sách RMA
      </a>
    </div>
  </div>
</div>

<!-- PRINT ONLY RMA ASSESSMENT SLIP (CHUẨN TMĐT KHỔ A4/A5) -->
<div class="d-none d-print-block mb-4 p-4 border rounded-3 bg-white">
  <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
    <div>
      <h3 class="fw-black text-dark mb-0">BEE<span class="text-warning">STYLE</span> MENSWEAR</h3>
      <p class="small text-muted mb-0">Website: beestyle.vn • Hotline: 1900 8888 • Bộ Phận Chăm Sóc Khách Hàng</p>
    </div>
    <div class="text-end">
      <h4 class="fw-bold text-dark font-monospace mb-0">PHIẾU TIẾP NHẬN &amp; THẨM ĐỊNH ĐỔI TRẢ (RMA)</h4>
      <p class="small text-muted mb-0">Mã RMA: <strong>#{{ $return->return_code }}</strong> • Mã Đơn: <strong>#{{ $return->order->order_code ?? '' }}</strong></p>
    </div>
  </div>

  <div class="row g-3 small mb-3">
    <div class="col-6">
      <strong>Khách Hàng:</strong> {{ $return->user->name ?? $return->order->customer_name }} - {{ $return->user->phone ?? $return->order->customer_phone }}<br>
      <strong>Địa Chỉ Nhận Hàng:</strong> {{ $return->order->shipping_address ?? 'N/A' }}<br>
      <strong>Hình Thức RMA:</strong> {{ $return->type_label }}<br>
      <strong>Lý Do Phản Hồi:</strong> {{ $return->reason }}
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
    <h6 class="fw-bold text-dark mb-2 border-bottom pb-1">BẢNG KIỂM ĐỊNH CHẤT LƯỢNG KHI KHO NHẬN HÀNG (QC CHECKLIST)</h6>
    <div class="row g-2 small">
      <div class="col-4">[ ] 1. Tem mác sản phẩm còn nguyên vẹn</div>
      <div class="col-4">[ ] 2. Hàng chưa qua giặt ủi, không mùi lạ</div>
      <div class="col-4">[ ] 3. Đầy đủ phụ kiện / quà tặng đi kèm</div>
      <div class="col-4">[ ] 4. Lỗi đúng như phản ánh của khách</div>
      <div class="col-4">[ ] 5. Đủ điều kiện nhập kho lại</div>
      <div class="col-4">[ ] 6. Đã giải quyết chuyển khoản / gửi đổi</div>
    </div>
  </div>

  <div class="row text-center small mt-5 pt-3">
    <div class="col-4">
      <strong>Khách Hàng</strong><br>
      <span class="text-muted">(Ký và ghi rõ họ tên)</span>
    </div>
    <div class="col-4">
      <strong>Nhân Viên Kiểm Hàng (Kho/QC)</strong><br>
      <span class="text-muted">(Ký và ghi rõ họ tên)</span>
    </div>
    <div class="col-4">
      <strong>Kế Toán / Phê Duyệt</strong><br>
      <span class="text-muted">(Ký và ghi rõ họ tên)</span>
    </div>
  </div>
</div>

<!-- 4-STEP RMA LIFECYCLE STEPPER -->
<div class="card border-0 shadow-sm p-4 mb-4 d-print-none" style="border-radius: 20px; background: #ffffff; border: 1px solid #f1f5f9 !important;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
      <div class="rounded-circle bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
        <i class="fa-solid fa-diagram-project"></i>
      </div>
      <h6 class="fw-bold text-dark mb-0">Tiến Trình Xử Lý &amp; Quyết Toán (RMA Workflow)</h6>
    </div>
    <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill small fw-bold">{{ $return->status_label }}</span>
  </div>

  @php
    $rmaSteps = [
      1 => ['code' => 'pending', 'label' => '1. Khách Gửi Yêu Cầu', 'icon' => 'fa-file-lines', 'desc' => $return->created_at ? $return->created_at->format('d/m/Y H:i') : 'Đã gửi'],
      2 => ['code' => 'approved', 'label' => '2. CSKH Duyệt Phiếu', 'icon' => 'fa-clipboard-check', 'desc' => $return->approved_at ? $return->approved_at->format('d/m/Y H:i') : 'Chờ duyệt'],
      3 => ['code' => 'received', 'label' => '3. Kho Nhận &amp; QC', 'icon' => 'fa-boxes-packing', 'desc' => $return->received_at ? $return->received_at->format('d/m/Y H:i') : 'Chờ nhận'],
      4 => ['code' => 'completed', 'label' => '4. Hoàn Tất Quyết Toán', 'icon' => 'fa-circle-check', 'desc' => $return->completed_at ? $return->completed_at->format('d/m/Y H:i') : 'Chờ hoàn tất'],
    ];

    $stepMap = ['pending' => 1, 'approved' => 2, 'received' => 3, 'completed' => 4, 'rejected' => 0];
    $currentStep = $stepMap[$return->status] ?? 1;
  @endphp

  @if($return->status === 'rejected')
    <div class="alert alert-danger py-3 px-4 rounded-4 d-flex align-items-center gap-3 mb-0" style="background: #fef2f2; border: 1px solid #fee2e2;">
      <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
        <i class="fa-solid fa-ban fs-4"></i>
      </div>
      <div>
        <strong class="fs-6 d-block text-danger">YÊU CẦU ĐỔI TRẢ ĐÃ BỊ TỪ CHỐI (REJECTED)</strong>
        <span class="small text-danger text-opacity-80">Lý do từ chối: {{ $return->rejected_reason ?? 'Không đáp ứng điều kiện theo chính sách đổi trả của BeeStyle.' }} (Thời gian: {{ $return->rejected_at ? $return->rejected_at->format('d/m/Y H:i') : '' }})</span>
      </div>
    </div>
  @else
    <!-- PROGRESS STEPPER BAR -->
    <div class="bee-timeline-steps my-3 p-3 bg-light rounded-4 border">
      @foreach($rmaSteps as $sNum => $sData)
        @php
          $isDone = $currentStep > $sNum;
          $isCurrent = $currentStep == $sNum;
        @endphp
        <div class="bee-timeline-step {{ $isDone ? 'completed' : ($isCurrent ? 'active' : '') }}">
          <div class="bee-timeline-step-icon shadow-xs">
            @if($isDone)
              <i class="fa-solid fa-check"></i>
            @else
              <i class="fa-solid {{ $sData['icon'] }}" style="font-size: 0.9rem;"></i>
            @endif
          </div>
          <div class="bee-timeline-step-label fw-bold text-truncate" style="font-size: 0.78rem;">{!! $sData['label'] !!}</div>
          <small class="text-muted d-block" style="font-size: 0.7rem;">{{ $sData['desc'] }}</small>
        </div>
      @endforeach
    </div>
  @endif

  <!-- QUICK ACTION BUTTONS -->
  @if($return->status !== 'completed' && $return->status !== 'rejected')
    <div class="pt-3 mt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="small text-muted">
        <i class="fa-solid fa-bolt text-warning me-1"></i> Chuyển nhanh tiến trình RMA:
      </div>
      <div class="d-flex gap-2 flex-wrap">
        @if($return->status === 'pending')
          <button type="button" class="btn btn-sm btn-info text-white fw-bold px-3.5 py-2 rounded-pill shadow-xs" data-bs-toggle="modal" data-bs-target="#approveReturnModal">
            <i class="fa-solid fa-check me-1.5"></i> Bước 2: Duyệt Phiếu (Gửi Hướng Dẫn)
          </button>
        @elseif($return->status === 'approved')
          <button type="button" class="btn btn-sm btn-primary fw-bold px-3.5 py-2 rounded-pill shadow-xs" data-bs-toggle="modal" data-bs-target="#receiveReturnModal">
            <i class="fa-solid fa-boxes-packing me-1.5"></i> Bước 3: Kho Nhận &amp; QC Hàng
          </button>
        @elseif($return->status === 'received')
          <button type="button" class="btn btn-sm btn-success fw-bold px-3.5 py-2 rounded-pill shadow-xs" data-bs-toggle="modal" data-bs-target="#completeReturnModal">
            <i class="fa-solid fa-circle-check me-1.5"></i> Bước 4: Hoàn Tất Quyết Toán (Hoàn Tiền / Đổi Size)
          </button>
        @endif

        <button type="button" class="btn btn-sm btn-outline-danger px-3 py-2 fw-bold rounded-pill shadow-xs" data-bs-toggle="modal" data-bs-target="#rejectReturnModal">
          <i class="fa-solid fa-ban me-1"></i> Từ Chối Yêu Cầu
        </button>
      </div>
    </div>
  @endif
</div>

<div class="row g-4 d-print-none">
  <!-- CỘT TRÁI: THÔNG TIN KHÁCH HÀNG, YÊU CẦU & TÀI KHOẢN NGÂN HÀNG -->
  <div class="col-lg-7">
    <!-- CHI TIẾT YÊU CẦU & BẰNG CHỨNG -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border: 1px solid #f1f5f9 !important;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
            <i class="fa-solid fa-file-lines"></i>
          </div>
          <h5 class="fw-bold text-dark mb-0">Nội Dung Yêu Cầu &amp; Minh Chứng</h5>
        </div>
        <span class="badge {{ $return->type === 'exchange' ? 'bg-info-subtle text-info' : ($return->type === 'refund_only' ? 'bg-warning-subtle text-dark' : 'bg-danger-subtle text-danger') }} fw-bold px-3 py-1.5 rounded-pill">
          {{ $return->type_label }}
        </span>
      </div>

      <div class="p-3.5 bg-light rounded-4 border d-flex flex-column gap-2.5 small mb-3">
        <div class="d-flex justify-content-between">
          <span class="text-muted">Hình thức xử lý:</span>
          <strong class="text-dark">{{ $return->type_label }}</strong>
        </div>
        <div class="d-flex justify-content-between">
          <span class="text-muted">Lý do đổi trả:</span>
          <strong class="text-danger">{{ $return->reason }}</strong>
        </div>
        @if($return->type === 'exchange')
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted">Kích cỡ / Màu sắc đổi:</span>
            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1">Size {{ $return->exchange_size ?? 'M' }} | Màu: {{ $return->exchange_color ?? 'Chuẩn' }}</span>
          </div>
        @else
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted">Số tiền hoàn dự kiến:</span>
            <strong class="text-danger fs-5 fw-black font-monospace">{{ number_format($return->refund_amount, 0, ',', '.') }}₫</strong>
          </div>
        @endif
        @if($return->customer_notes)
          <div class="pt-2 border-top">
            <span class="text-muted d-block mb-1">Ghi chú phản ánh từ khách hàng:</span>
            <div class="p-3 bg-white rounded-3 border text-dark shadow-xs fst-italic">
              "{{ $return->customer_notes }}"
            </div>
          </div>
        @endif
      </div>

      <!-- HÌNH ẢNH / BẰNG CHỨNG KHÁCH TẢI LÊN -->
      <h6 class="fw-bold text-dark mb-2">
        <i class="fa-solid fa-images me-1.5 text-warning"></i> Ảnh Minh Chứng Tem Mác &amp; Lỗi Sản Phẩm ({{ count($return->image_proofs ?? []) }})
      </h6>
      @if(!empty($return->image_proofs) && is_array($return->image_proofs) && count($return->image_proofs) > 0)
        <div class="row g-2">
          @foreach($return->image_proofs as $img)
            <div class="col-4 col-md-3">
              <div class="position-relative overflow-hidden rounded-3 border shadow-xs bg-light cursor-pointer group" onclick="openImageLightbox('{{ asset($img) }}')" style="cursor: pointer;">
                <img src="{{ asset($img) }}" alt="Proof Image" class="img-fluid w-100 transition-all hover-scale" style="height: 120px; object-fit: cover;">
                <span class="position-absolute bottom-0 end-0 m-1 badge bg-dark bg-opacity-75 text-white" style="font-size: 0.65rem;">
                  <i class="fa-solid fa-magnifying-glass-plus"></i> Xem lớn
                </span>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="p-3.5 bg-light rounded-4 text-muted small text-center border border-dashed">
          <i class="fa-regular fa-image fs-4 d-block mb-1 text-secondary"></i>
          Khách hàng không đính kèm hình ảnh bổ sung.
        </div>
      @endif
    </div>

    <!-- TÀI KHOẢN NGÂN HÀNG HOÀN TIỀN (THIẾT KẾ DẠNG THẺ ATM CAO CẤP) -->
    @if($return->type !== 'exchange')
      <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border: 1px solid #f1f5f9 !important;">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
              <i class="fa-solid fa-building-columns"></i>
            </div>
            <h5 class="fw-bold text-dark mb-0">Tài Khoản Quyết Toán Hoàn Tiền</h5>
          </div>
          <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1 rounded-pill">
            <i class="fa-solid fa-money-bill-transfer me-1"></i> Chuyển Khoản Ngân Hàng
          </span>
        </div>

        <!-- BANK CARD VISUAL -->
        <div class="p-4 rounded-4 text-white shadow-sm position-relative overflow-hidden mb-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-bold text-warning font-monospace fs-6">
              <i class="fa-solid fa-building-columns me-1.5"></i>{{ $return->bank_name ?: 'NGÂN HÀNG THƯƠNG MẠI' }}
            </span>
            <i class="fa-solid fa-microchip fs-3 text-warning"></i>
          </div>
          
          <div class="d-flex align-items-center justify-content-between my-2">
            <span class="fs-4 fw-bold font-monospace letter-spacing-2" id="bankAccText">
              {{ $return->bank_account_number ?: '•••• •••• •••• ••••' }}
            </span>
            @if($return->bank_account_number)
              <button type="button" class="btn btn-sm btn-light bg-opacity-20 text-white border-0 px-2.5 py-1 rounded-pill shadow-xs" onclick="copyToClipboard('{{ $return->bank_account_number }}', this)">
                <i class="fa-regular fa-copy me-1"></i> Chép STK
              </button>
            @endif
          </div>

          <div class="d-flex justify-content-between align-items-end pt-2 border-top border-white border-opacity-10 mt-3 small">
            <div>
              <small class="text-white-50 d-block" style="font-size: 0.68rem;">CHỦ TÀI KHOẢN</small>
              <strong class="text-uppercase text-white">{{ $return->bank_account_name ?: 'CHƯA CẬP NHẬT' }}</strong>
            </div>
            <div class="text-end">
              <small class="text-white-50 d-block" style="font-size: 0.68rem;">SỐ TIỀN HOÀN</small>
              <strong class="text-warning fs-6 font-monospace">{{ number_format($return->refund_amount, 0, ',', '.') }}₫</strong>
            </div>
          </div>
        </div>
      </div>
    @endif

    <!-- THÔNG TIN TÀI KHOẢN KHÁCH HÀNG -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border: 1px solid #f1f5f9 !important;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
            <i class="fa-solid fa-user"></i>
          </div>
          <h5 class="fw-bold text-dark mb-0">Hồ Sơ Khách Hàng</h5>
        </div>
        @if($return->user)
          <a href="{{ route('admin.customers.show', $return->user->id) }}" class="btn btn-sm btn-outline-dark px-3 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
            Xem Hồ Sơ <i class="fa-solid fa-arrow-right ms-1"></i>
          </a>
        @endif
      </div>

      <div class="d-flex align-items-center gap-3 mb-3">
        <img src="{{ $return->user->avatar_url ?? 'https://ui-avatars.com/api/?name=User&background=f59e0b&color=111827' }}" alt="Avatar" class="rounded-circle border shadow-xs" style="width: 52px; height: 52px; object-fit: cover;">
        <div>
          <h6 class="fw-bold text-dark mb-0">
            {{ $return->user->name ?? ($return->order->customer_name ?? 'Khách Hàng') }}
          </h6>
          <span class="text-muted small">{{ $return->user->email ?? ($return->order->customer_email ?? '') }} • {{ $return->user->phone ?? ($return->order->customer_phone ?? '') }}</span>
        </div>
      </div>

      @if($return->user)
        <div class="row g-2 text-center small">
          <div class="col-4">
            <div class="p-2.5 bg-light rounded-3 border">
              <span class="text-muted d-block" style="font-size: 0.72rem;">Hạng thành viên</span>
              <span class="badge bg-warning text-dark fw-bold text-uppercase">{{ $return->user->rank ?? 'Đồng' }}</span>
            </div>
          </div>
          <div class="col-4">
            <div class="p-2.5 bg-light rounded-3 border">
              <span class="text-muted d-block" style="font-size: 0.72rem;">Tổng số đơn</span>
              <strong class="text-dark">{{ $return->user->orders ? $return->user->orders->count() : 0 }} đơn</strong>
            </div>
          </div>
          <div class="col-4">
            <div class="p-2.5 bg-light rounded-3 border">
              <span class="text-muted d-block" style="font-size: 0.72rem;">Tổng chi tiêu</span>
              <strong class="text-danger">{{ number_format($return->user->actual_total_spent, 0, ',', '.') }}₫</strong>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>

  <!-- CỘT PHẢI: SẢN PHẨM LIÊN QUAN, ĐƠN HÀNG GỐC & GHI CHÚ NỘI BỘ -->
  <div class="col-lg-5">
    <!-- SẢN PHẨM TRONG ĐƠN HÀNG -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border: 1px solid #f1f5f9 !important;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-2">
          <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
            <i class="fa-solid fa-shirt"></i>
          </div>
          <h5 class="fw-bold text-dark mb-0">Sản Phẩm Đổi / Trả</h5>
        </div>
        <span class="badge bg-light text-muted border small">{{ $return->order ? $return->order->items->count() : 0 }} món</span>
      </div>

      @if($return->order)
        <div class="d-flex flex-column gap-2.5 mb-3">
          @foreach($return->order->items as $item)
            @php
              $isTargetItem = $return->order_item_id && $return->order_item_id == $item->id;
            @endphp
            <div class="p-3 rounded-4 border d-flex align-items-center justify-content-between {{ $isTargetItem ? 'bg-warning bg-opacity-10 border-warning shadow-xs' : 'bg-light' }}">
              <div class="d-flex align-items-center gap-2.5">
                <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 48px; height: 48px; object-fit: contain;" class="rounded-3 border bg-white shadow-xs">
                <div>
                  <strong class="small text-dark d-block text-truncate" style="max-width: 170px;">{{ $item->product_name }}</strong>
                  <small class="text-muted">Màu: {{ $item->color ?? 'Chuẩn' }} / Size {{ $item->size ?? 'M' }} • x{{ $item->quantity }}</small>
                </div>
              </div>
              <div class="text-end">
                <span class="small fw-bold text-dark d-block font-monospace">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                @if($isTargetItem)
                  <span class="badge bg-danger text-white px-2 py-0.5 mt-1" style="font-size: 0.65rem;">Món yêu cầu đổi/trả</span>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <div class="p-3.5 bg-light rounded-4 small">
          <div class="d-flex justify-content-between text-muted mb-1.5">
            <span>Mã đơn hàng gốc:</span>
            <a href="{{ route('admin.orders.show', $return->order->id) }}" class="fw-bold font-monospace text-primary text-decoration-none">#{{ $return->order->order_code }}</a>
          </div>
          <div class="d-flex justify-content-between text-muted mb-1.5">
            <span>Tổng tiền đơn hàng:</span>
            <strong class="text-danger font-monospace fs-6">{{ number_format($return->order->total_amount, 0, ',', '.') }}₫</strong>
          </div>
          <div class="d-flex justify-content-between text-muted mb-1.5">
            <span>Phương thức thanh toán:</span>
            <span class="text-dark fw-semibold">{{ $return->order->payment_method_name }}</span>
          </div>
          <div class="d-flex justify-content-between text-muted">
            <span>Trạng thái thanh toán:</span>
            <span class="badge {{ $return->order->payment_status === 'refunded' ? 'bg-danger-subtle text-danger' : ($return->order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark') }} fw-bold px-2 py-1">
              {{ $return->order->payment_status_label }}
            </span>
          </div>
        </div>
      @endif
    </div>

    <!-- GHI CHÚ NỘI BỘ & LỊCH SỬ CSKH -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 20px; background: #ffffff; border: 1px solid #f1f5f9 !important;">
      <div class="d-flex align-items-center gap-2 mb-3">
        <div class="rounded-circle bg-dark bg-opacity-10 text-dark d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
          <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
        <h5 class="fw-bold text-dark mb-0">Nhật Ký Xử Lý &amp; Ghi Chú Admin</h5>
      </div>

      <div class="d-flex flex-column gap-2 mb-3 small">
        <div class="p-3 bg-light rounded-3 border">
          <strong class="text-dark d-block mb-1">Ghi chú nội bộ hiện thời:</strong>
          <span class="text-muted">{{ $return->admin_notes ?: 'Chưa có ghi chú nội bộ.' }}</span>
        </div>
      </div>

      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <div class="mb-3">
          <label class="form-label small fw-semibold">Cập nhật nhanh trạng thái:</label>
          <select name="status" class="form-select rounded-3">
            <option value="pending" {{ $return->status === 'pending' ? 'selected' : '' }}>1. Chờ duyệt yêu cầu</option>
            <option value="approved" {{ $return->status === 'approved' ? 'selected' : '' }}>2. Đã duyệt (Chờ khách gửi hàng về kho)</option>
            <option value="received" {{ $return->status === 'received' ? 'selected' : '' }}>3. Kho đã nhận hàng &amp; kiểm tra</option>
            <option value="completed" {{ $return->status === 'completed' ? 'selected' : '' }}>4. Hoàn tất xử lý (Hoàn tiền / Đổi size)</option>
            <option value="rejected" {{ $return->status === 'rejected' ? 'selected' : '' }}>0. Từ chối yêu cầu</option>
          </select>
        </div>

        <div class="mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="restock" value="1" id="restockCheck" checked>
            <label class="form-check-label small text-muted" for="restockCheck">
              Tự động cộng lại số lượng vào kho hàng khi hoàn tất
            </label>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Cập nhật thêm ghi chú:</label>
          <textarea name="admin_notes" class="form-control rounded-3" rows="3" placeholder="Nhập ghi chú xử lý hoặc mã chuyển tiền hoàn...">{{ $return->admin_notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-bee-primary w-100 py-2.5 fw-bold rounded-pill shadow-sm">
          <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật
        </button>
      </form>
    </div>
  </div>
</div>

<!-- ================= MODALS THAO TÁC THEO TỪNG BƯỚC ================= -->

<!-- 1. MODAL DUYỆT YÊU CẦU (APPROVE) -->
<div class="modal fade" id="approveReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="approved">
        <div class="modal-header border-bottom p-4">
          <h5 class="modal-title fw-bold text-info"><i class="fa-solid fa-check-circle me-2"></i> Duyệt Yêu Cầu Đổi Trả</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <p class="small text-muted mb-3">Xác nhận duyệt yêu cầu đổi trả này và gửi thông tin hướng dẫn gửi hàng cho khách:</p>
          <div class="mb-3">
            <label class="form-label small fw-bold">Hướng dẫn đóng gói &amp; địa chỉ kho nhận hàng:</label>
            <textarea name="warehouse_instruction" class="form-control rounded-3" rows="3">Quý khách vui lòng đóng gói sản phẩm còn nguyên tem mác và gửi về: Tổng Kho BeeStyle - Số 123 Cầu Giấy, Hà Nội (Hotline: 1900 8888). Thời hạn gửi trong vòng 3 ngày.</textarea>
          </div>
        </div>
        <div class="modal-footer border-top bg-light p-3">
          <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-info text-white btn-sm rounded-pill px-4 fw-bold shadow-xs">Xác Nhận Duyệt Phiếu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 2. MODAL KHO NHẬN HÀNG (RECEIVE) -->
<div class="modal fade" id="receiveReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="received">
        <div class="modal-header border-bottom p-4">
          <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-boxes-packing me-2"></i> Kho Xác Nhận Đã Nhận Kiện Hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <p class="small text-muted mb-3">Vui lòng kiểm định chất lượng sản phẩm thực tế khi bưu tá bàn giao về kho:</p>
          <div class="mb-3">
            <label class="form-label small fw-bold">Tình trạng thực tế sản phẩm nhận được:</label>
            <select name="received_condition" class="form-select rounded-3">
              <option value="Hàng nguyên vẹn tem mác, đúng quy cách, đạt chuẩn đổi trả">Hàng nguyên vẹn tem mác, đúng quy cách, đạt chuẩn đổi trả</option>
              <option value="Hàng đúng lỗi sản xuất như khách báo">Hàng đúng lỗi sản xuất như khách báo</option>
              <option value="Hàng bị móp méo hộp ngoài nhưng sản phẩm áo bên trong nguyên vẹn">Hàng bị móp méo hộp ngoài nhưng sản phẩm áo bên trong nguyên vẹn</option>
            </select>
          </div>
        </div>
        <div class="modal-footer border-top bg-light p-3">
          <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-xs">Xác Nhận Nhận Hàng</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 3. MODAL HOÀN TẤT & QUYẾT TOÁN (COMPLETE) -->
<div class="modal fade" id="completeReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="completed">
        <div class="modal-header border-bottom p-4">
          <h5 class="modal-title fw-bold text-success"><i class="fa-solid fa-circle-check me-2"></i> Hoàn Tất Quyết Toán RMA</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          @if($return->type === 'exchange')
            <p class="small text-muted mb-3">Nhập thông tin kiện hàng đổi size/màu mới gửi lại cho khách hàng:</p>
            <div class="mb-3">
              <label class="form-label small fw-bold">Đơn vị vận chuyển:</label>
              <input type="text" name="exchange_carrier" value="Giao Hàng Nhanh (GHN)" class="form-control rounded-3">
            </div>
            <div class="mb-3">
              <label class="form-label small fw-bold">Mã vận đơn mới:</label>
              <input type="text" name="exchange_tracking_code" placeholder="VD: GHN-8823910" class="form-control rounded-3 font-monospace" required>
            </div>
          @else
            <p class="small text-muted mb-3">Xác nhận chuyển khoản hoàn tiền cho khách hàng:</p>
            <div class="mb-3">
              <label class="form-label small fw-bold">Số tiền hoàn thực tế (VNĐ):</label>
              <input type="number" name="refund_amount" value="{{ $return->refund_amount }}" class="form-control rounded-3 font-monospace text-danger fw-bold fs-6">
            </div>
            <div class="mb-3">
              <label class="form-label small fw-bold">Mã giao dịch ngân hàng / Trace No:</label>
              <input type="text" name="bank_ref_code" placeholder="VD: VCB-982341 hoặc MB-0912" class="form-control rounded-3 font-monospace" required>
            </div>
          @endif

          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" name="restock" value="1" id="modalRestockCheck" checked>
            <label class="form-check-label small text-muted" for="modalRestockCheck">
              Tự động cập nhật số lượng tồn kho sản phẩm
            </label>
          </div>
        </div>
        <div class="modal-footer border-top bg-light p-3">
          <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">Xác Nhận Quyết Toán</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 4. MODAL TỪ CHỐI YÊU CẦU (REJECT) -->
<div class="modal fade" id="rejectReturnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
      <form action="{{ route('admin.returns.updateStatus', $return->id) }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="rejected">
        <div class="modal-header border-bottom p-4">
          <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-ban me-2"></i> Từ Chối Yêu Cầu Đổi Trả</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <p class="small text-muted mb-3">Vui lòng nhập lý do từ chối để thông báo minh bạch tới khách hàng:</p>
          <div class="mb-3">
            <label class="form-label small fw-bold">Lý do từ chối <span class="text-danger">*</span></label>
            <textarea name="rejected_reason" class="form-control rounded-3" rows="3" required placeholder="Ví dụ: Sản phẩm đã bị cắt tem mác hoặc đã qua giặt ủi, không đủ điều kiện đổi trả theo chính sách của BeeStyle..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top bg-light p-3">
          <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">Xác Nhận Từ Chối</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- LIGHTBOX MODAL FOR IMAGES -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 bg-transparent">
      <div class="modal-body p-0 text-center position-relative">
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal"></button>
        <img id="lightboxImg" src="" alt="Proof Preview" class="img-fluid rounded-4 shadow-2xl border" style="max-height: 80vh; object-fit: contain;">
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
      btn.classList.replace('text-white', 'text-success');
      setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.classList.replace('text-success', 'text-white');
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
