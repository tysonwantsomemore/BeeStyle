@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng #' . $order->order_code . ' | BeeStyle Admin')

@section('content')
<!-- TOP HEADER & BREADCRUMB -->
<div class="mb-4 d-print-none">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h3 class="fw-black text-dark mb-0 font-monospace">#{{ $order->order_code }}</h3>
        <span class="badge {{ $order->shipping_status === 'completed' ? 'bg-success' : ($order->shipping_status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') }} px-3 py-1.5 fw-bold rounded-pill shadow-xs">
          {{ $order->status_label }}
        </span>
        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }} px-3 py-1.5 fw-bold rounded-pill">
          <i class="fa-solid {{ $order->payment_status === 'paid' ? 'fa-circle-check' : 'fa-clock' }} me-1"></i> {{ $order->payment_status_label }}
        </span>
      </div>
      <p class="text-muted small mb-0">
        <i class="fa-regular fa-clock me-1"></i> Thời gian đặt hàng: <strong>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i:s') : 'N/A' }}</strong> 
        • Kênh thanh toán: <strong class="text-dark">{{ $order->payment_method_name }}</strong>
        @if($order->tracking_code)
          • <span class="badge bg-info-subtle text-info border border-info-subtle fw-semibold font-monospace">
            <i class="fa-solid fa-truck-fast me-1"></i> {{ $order->shipping_carrier }}: 
            @if($order->tracking_url)
              <a href="{{ $order->tracking_url }}" target="_blank" class="text-decoration-none fw-bold text-primary">{{ $order->tracking_code }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.68rem;"></i></a>
            @else
              <strong>{{ $order->tracking_code }}</strong>
            @endif
          </span>
        @endif
      </p>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="d-flex align-items-center gap-2">
      <button type="button" class="btn btn-outline-primary btn-sm px-3 fw-bold rounded-3 shadow-xs d-flex align-items-center gap-1.5" onclick="window.print()">
        <i class="fa-solid fa-print"></i> In Phiếu Đóng Gói (Packing Slip)
      </button>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-dark btn-sm px-3 fw-bold rounded-3 shadow-xs">
        <i class="fa-solid fa-list me-1"></i> Danh Sách Đơn
      </a>
    </div>
  </div>
</div>

<!-- PRINT ONLY PACKING SLIP (CHUẨN TMĐT A4/A5) -->
<div class="d-none d-print-block mb-4 p-4 border rounded-3 bg-white">
  <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
    <div>
      <h3 class="fw-black text-dark mb-0">BEE<span class="text-warning">STYLE</span> MENSWEAR</h3>
      <p class="small text-muted mb-0">Website: beestyle.vn • Hotline: 1900 8888</p>
    </div>
    <div class="text-end">
      <h4 class="fw-bold text-dark font-monospace mb-0">PHIẾU ĐÓNG GÓI &amp; GIAO HÀNG</h4>
      <p class="small text-muted mb-0">Mã đơn: <strong>#{{ $order->order_code }}</strong></p>
    </div>
  </div>
  <div class="row g-3 small mb-3">
    <div class="col-6">
      <strong>Người Nhận:</strong> {{ $order->customer_name }} - {{ $order->customer_phone }}<br>
      <strong>Địa Chỉ:</strong> {{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}
    </div>
    <div class="col-6 text-end">
      <strong>Thanh Toán:</strong> {{ $order->payment_method_name }} ({{ $order->payment_status_label }})<br>
      <strong>Tổng Thu Người Nhận:</strong> <strong class="fs-6 text-danger">{{ $order->payment_status === 'paid' ? '0₫ (Đã thanh toán Online)' : number_format($order->total_amount, 0, ',', '.') . '₫ (Thu Tiền COD)' }}</strong>
    </div>
  </div>
</div>

<!-- ACTIVE RMA RETURN REQUEST ALERT FOR ADMIN -->
@if($order->returns && $order->returns->count() > 0)
  @php $latestRma = $order->returns->first(); @endphp
  <div class="alert alert-warning border-0 shadow-sm p-4 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3 d-print-none" style="background: #fffbeb; border-left: 6px solid #f59e0b !important;">
    <div class="d-flex align-items-center gap-3">
      <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 52px; height: 52px; min-width: 52px;">
        <i class="fa-solid fa-arrow-rotate-left fs-3"></i>
      </div>
      <div>
        <h5 class="fw-bold text-dark mb-1">ĐƠN HÀNG CÓ PHIẾU YÊU CẦU ĐỔI TRẢ (#{{ $latestRma->return_code }})</h5>
        <p class="mb-0 text-muted small">Hình thức: <strong>{{ $latestRma->type_label }}</strong> • Lý do: <strong>{{ $latestRma->reason }}</strong> • Trạng thái: {!! $latestRma->status_badge !!}</p>
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('admin.returns.show', $latestRma->id) }}" class="btn btn-bee-primary btn-sm px-3.5 py-2 fw-bold rounded-pill shadow-xs">
        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Xử Lý Phiếu RMA
      </a>
    </div>
  </div>
@endif

<!-- VISUAL ORDER FULFILLMENT STEPPER (TIẾN TRÌNH 6 BƯỚC TMĐT CHUYÊN NGHIỆP) -->
<div class="card border-0 shadow-sm p-4 mb-4 d-print-none" style="border-radius: 18px; background: #ffffff;">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold text-dark mb-0">
      <i class="fa-solid fa-truck-ramp-box me-2 text-warning"></i> Quy Trình Xử Lý &amp; Vận Chuyển Đơn Hàng
    </h6>
    <span class="badge bg-light text-muted border small">Bước {{ $order->status_step ?? 1 }}/6</span>
  </div>

  @php
    $steps = [
      1 => ['code' => 'pending', 'label' => '1. Chờ Xác Nhận', 'icon' => 'fa-clipboard-list', 'desc' => 'Đơn hàng mới tạo', 'time' => $order->created_at],
      2 => ['code' => 'confirmed', 'label' => '2. Đã Xác Nhận', 'icon' => 'fa-clipboard-check', 'desc' => 'Đã duyệt thông tin', 'time' => $order->confirmed_at],
      3 => ['code' => 'processing', 'label' => '3. Đang Đóng Gói', 'icon' => 'fa-box-open', 'desc' => 'Kho nhặt hàng & gói', 'time' => $order->processing_at],
      4 => ['code' => 'shipping', 'label' => '4. Đang Giao Hàng', 'icon' => 'fa-truck-fast', 'desc' => 'Bưu tá vận chuyển', 'time' => $order->shipping_at],
      5 => ['code' => 'delivered', 'label' => '5. Đã Giao Hàng', 'icon' => 'fa-handshake', 'desc' => 'Khách nhận & kiểm tra', 'time' => $order->delivered_at],
      6 => ['code' => 'completed', 'label' => '6. Hoàn Tất', 'icon' => 'fa-circle-check', 'desc' => 'Thành công', 'time' => $order->completed_at],
    ];
    $currentStep = $order->shipping_status === 'cancelled' ? 0 : ($order->status_step ?? 1);
  @endphp

  @if($order->shipping_status === 'cancelled')
    <div class="alert alert-danger py-3 px-4 rounded-3 d-flex align-items-center gap-3 mb-0">
      <i class="fa-solid fa-ban fs-2 text-danger"></i>
      <div>
        <strong class="fs-6 d-block">ĐƠN HÀNG ĐÃ BỊ HỦY (CANCELLED)</strong>
        <span class="small text-danger text-opacity-80">Lý do hủy: <strong>{{ $order->cancel_reason ?: 'Không có ghi chú' }}</strong> • Thực hiện bởi: <strong>{{ $order->cancelled_by === 'customer' ? 'Khách hàng tự hủy' : ($order->cancelled_by === 'admin' ? 'Quản trị viên' : 'Hệ thống tự động') }}</strong> • Thời gian: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i:s') : ($order->updated_at ? $order->updated_at->format('d/m/Y H:i:s') : '') }}</span>
      </div>
    </div>
  @else
    <div class="row g-2 text-center position-relative my-2">
      @foreach($steps as $sIndex => $sData)
        @php
          $isDone = $currentStep >= $sIndex;
          $isCurrent = $currentStep === $sIndex;
        @endphp
        <div class="col-2">
          <div class="d-flex flex-column align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-xs transition-all mb-2"
                 style="width: 46px; height: 46px; font-size: 1.15rem; 
                        background-color: {{ $isCurrent ? '#f59e0b' : ($isDone ? '#10b981' : '#f1f5f9') }}; 
                        color: {{ $isDone || $isCurrent ? '#ffffff' : '#94a3b8' }};">
              <i class="fa-solid {{ $sData['icon'] }}"></i>
            </div>
            <span class="fw-bold text-truncate d-block" style="font-size: 0.78rem; color: {{ $isCurrent ? '#d97706' : ($isDone ? '#059669' : '#64748b') }};">
              {{ $sData['label'] }}
            </span>
            <small class="text-muted d-none d-md-block" style="font-size: 0.68rem;">{{ $sData['desc'] }}</small>
            
            <!-- MỐC THỜI GIAN NGÀY & GIỜ CỤ THỂ -->
            @if(!empty($sData['time']))
              <span class="badge {{ $isCurrent ? 'bg-warning-subtle text-dark border border-warning' : 'bg-success-subtle text-success border border-success-subtle' }} font-monospace px-1.5 py-0.5 mt-1.5 shadow-2xs text-nowrap" style="font-size: 0.68rem;" title="Mốc thời gian xác nhận">
                <i class="fa-regular fa-clock me-0.5"></i> {{ $sData['time']->format('d/m/Y H:i') }}
              </span>
            @elseif($isDone)
              <span class="badge bg-light text-muted border font-monospace px-1.5 py-0.5 mt-1.5 text-nowrap" style="font-size: 0.68rem;">
                <i class="fa-solid fa-check text-success me-0.5"></i> Đã duyệt
              </span>
            @else
              <span class="badge bg-light text-muted px-1.5 py-0.5 mt-1.5 text-nowrap" style="font-size: 0.68rem;">
                Chờ thực hiện
              </span>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif

  <!-- QUICK ACTION BUTTONS (1-CHẠM CẬP NHẬT TIẾN TRÌNH TMĐT) -->
  @if($order->shipping_status !== 'cancelled' && $order->shipping_status !== 'completed')
    <div class="pt-3 mt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
      <div class="small text-muted">
        <i class="fa-solid fa-bolt text-warning me-1"></i> Thao tác nhanh chuyển bước:
      </div>
      <div class="d-flex gap-2 flex-wrap">
        @if($order->shipping_status === 'pending')
          <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="shipping_status" value="confirmed">
            <button type="submit" class="btn btn-sm btn-primary fw-bold px-3 shadow-xs">
              <i class="fa-solid fa-check me-1"></i> Bước 2: Xác Nhận Đơn Hàng
            </button>
          </form>
        @elseif($order->shipping_status === 'confirmed')
          <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="shipping_status" value="processing">
            <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold px-3 shadow-xs">
              <i class="fa-solid fa-box-open me-1"></i> Bước 3: Chuyển Cho Kho Đóng Gói
            </button>
          </form>
        @elseif($order->shipping_status === 'processing')
          <button type="button" class="btn btn-sm btn-info text-white fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#dispatchCarrierModal">
            <i class="fa-solid fa-truck-fast me-1"></i> Bước 4: Tạo Vận Đơn &amp; Giao Bưu Tá
          </button>
        @elseif($order->shipping_status === 'shipping')
          <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="shipping_status" value="delivered">
            <button type="submit" class="btn btn-sm btn-success fw-bold px-3 shadow-xs">
              <i class="fa-solid fa-handshake me-1"></i> Bước 5: Shipper Báo Giao Thành Công
            </button>
          </form>
        @elseif($order->shipping_status === 'delivered')
          <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="shipping_status" value="completed">
            <input type="hidden" name="payment_status" value="paid">
            <button type="submit" class="btn btn-sm btn-success fw-bold px-3 shadow-xs">
              <i class="fa-solid fa-circle-check me-1"></i> Bước 6: Hoàn Tất Đơn Hàng
            </button>
          </form>
        @endif


        @if($order->payment_status !== 'paid')
          <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="shipping_status" value="{{ $order->shipping_status }}">
            <input type="hidden" name="payment_status" value="paid">
            <button type="submit" class="btn btn-sm btn-outline-success fw-bold px-3">
              <i class="fa-solid fa-money-bill-wave me-1"></i> Đã Thu Tiền (Mark Paid)
            </button>
          </form>
        @endif

        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn HỦY đơn hàng #{{ $order->order_code }}? Toàn bộ sản phẩm sẽ được tự động hoàn lại vào kho hàng!')">
          @csrf
          <input type="hidden" name="shipping_status" value="cancelled">
          <button type="submit" class="btn btn-sm btn-outline-danger px-2.5">
            <i class="fa-solid fa-xmark me-1"></i> Hủy Đơn
          </button>
        </form>
      </div>
    </div>
  @endif
</div>

<div class="row g-4">
  
  <!-- LEFT COLUMN: PRODUCT PICKING LIST & FINANCIAL SUMMARY -->
  <div class="col-lg-8">
    
    <!-- PRODUCT PICKING LIST (DANH SÁCH NHẶT HÀNG & TRỪ KHO) -->
    <div class="bee-table-card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-dark mb-0">
          <i class="fa-solid fa-boxes-stacked me-2 text-warning"></i> Danh Sách Sản Phẩm Đóng Gói ({{ $order->items->count() }} Mẫu)
        </h5>
        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1">
          <i class="fa-solid fa-check me-1"></i> Đã Tự Động Trừ Kho
        </span>
      </div>
      
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>Sản Phẩm &amp; Phân Loại</th>
              <th>Mã SKU</th>
              <th>Đơn Giá</th>
              <th>Số Lượng</th>
              <th class="text-end">Thành Tiền</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->items as $item)
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" 
                         style="width: 52px; height: 52px; object-fit: contain;" class="border rounded-3 bg-light p-1 shadow-2xs flex-shrink-0">
                    <div>
                      <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $item->product_name }}</div>
                      <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                        <span class="badge bg-light text-dark border fw-semibold" style="font-size: 0.72rem;">
                          Màu: {{ $item->color ?? 'Tiêu chuẩn' }}
                        </span>
                        <span class="badge bg-light text-dark border fw-semibold" style="font-size: 0.72rem;">
                          Size: {{ $item->size ?? 'M' }}
                        </span>
                        @if($item->product)
                          <span class="badge bg-primary-subtle text-primary" style="font-size: 0.7rem;" title="Số lượng còn lại trong kho">
                            Tồn kho còn: {{ $item->product->stock }} cái
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </td>
                <td><span class="font-monospace small text-muted fw-semibold">{{ $item->product_sku ?? 'BS-PROD' }}</span></td>
                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                <td>
                  <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-black px-2.5 py-1">
                    x{{ $item->quantity }}
                  </span>
                </td>
                <td class="text-end fw-bold text-dark font-monospace fs-6">
                  {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="table-light">
            <tr>
              <td colspan="4" class="text-end text-muted small">Tạm tính tiền hàng:</td>
              <td class="text-end fw-bold font-monospace">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
            </tr>
            @if($order->discount_amount > 0)
              <tr>
                <td colspan="4" class="text-end text-success small">
                  <i class="fa-solid fa-tags me-1"></i> Giảm giá Voucher (<strong>{{ $order->coupon_code }}</strong>):
                </td>
                <td class="text-end text-success fw-bold font-monospace">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
              </tr>
            @endif
            <tr>
              <td colspan="4" class="text-end text-muted small">Phí vận chuyển:</td>
              <td class="text-end text-success fw-bold font-monospace">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . '₫' : 'Miễn Phí (0₫)' }}</td>
            </tr>
            <tr style="background-color: #fefce8;">
              <td colspan="4" class="text-end fw-black text-dark fs-6">TỔNG TIỀN ĐƠN HÀNG:</td>
              <td class="text-end fw-black text-danger fs-5 font-monospace">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- UPDATE STATUS & INTERNAL ADMIN NOTES FORM -->
    <div class="card border-0 shadow-sm p-4 mb-4 d-print-none" style="border-radius: 18px; background: #ffffff;">
      <h5 class="fw-bold text-dark mb-3">
        <i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Cập Nhật Trạng Thái &amp; Ghi Chú Đơn Hàng
      </h5>
      
      <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
        @csrf
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Trạng thái vận chuyển:</label>
            <select name="shipping_status" class="form-select">
              <option value="pending" {{ $order->shipping_status === 'pending' ? 'selected' : '' }}>1. Chờ xác nhận đơn hàng</option>
              <option value="confirmed" {{ $order->shipping_status === 'confirmed' ? 'selected' : '' }}>2. Đã xác nhận thông tin</option>
              <option value="processing" {{ $order->shipping_status === 'processing' ? 'selected' : '' }}>3. Đang đóng gói bưu phẩm</option>
              <option value="shipping" {{ $order->shipping_status === 'shipping' ? 'selected' : '' }}>4. Đang giao hàng bưu tá</option>
              <option value="delivered" {{ $order->shipping_status === 'delivered' ? 'selected' : '' }}>5. Đã giao tới người nhận</option>
              <option value="completed" {{ $order->shipping_status === 'completed' ? 'selected' : '' }}>6. Hoàn tất đơn hàng</option>
              <option value="cancelled" {{ $order->shipping_status === 'cancelled' ? 'selected' : '' }}>0. Hủy đơn hàng (Hoàn kho)</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label small fw-semibold">Trạng thái thanh toán:</label>
            <select name="payment_status" class="form-select">
              <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
              <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
              <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
            </select>
          </div>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Đối tác vận chuyển:</label>
            <select name="shipping_carrier" class="form-select">
              <option value="">-- Chưa gán đơn vị vận chuyển --</option>
              <option value="Giao Hàng Tiết Kiệm (GHTK)" {{ str_contains((string)$order->shipping_carrier, 'GHTK') ? 'selected' : '' }}>Giao Hàng Tiết Kiệm (GHTK)</option>
              <option value="Giao Hàng Nhanh (GHN)" {{ str_contains((string)$order->shipping_carrier, 'GHN') ? 'selected' : '' }}>Giao Hàng Nhanh (GHN)</option>
              <option value="Viettel Post" {{ str_contains((string)$order->shipping_carrier, 'Viettel') ? 'selected' : '' }}>Viettel Post</option>
              <option value="J&T Express" {{ str_contains((string)$order->shipping_carrier, 'J&T') ? 'selected' : '' }}>J&T Express</option>
              <option value="Ninja Van" {{ str_contains((string)$order->shipping_carrier, 'Ninja') ? 'selected' : '' }}>Ninja Van</option>
              <option value="Shipper Nội Bộ BeeStyle" {{ str_contains((string)$order->shipping_carrier, 'Nội Bộ') ? 'selected' : '' }}>Shipper Nội Bộ BeeStyle</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-semibold">Mã vận đơn bưu tá (Tracking Code):</label>
            <div class="input-group">
              <input type="text" name="tracking_code" value="{{ $order->tracking_code }}" class="form-control font-monospace fw-bold text-primary" placeholder="VD: GHTK-8829182">
              @if($order->tracking_url)
                <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-outline-primary" title="Mở trang tra cứu bưu phẩm của hãng vận chuyển">
                  <i class="fa-solid fa-arrow-up-right-from-square"></i> Tra cứu
                </a>
              @endif
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold">Ghi chú nội bộ admin:</label>
          <input type="text" name="admin_notes" class="form-control" value="{{ $order->admin_notes }}" placeholder="VD: Bưu tá đã lấy hàng lúc 14h30, hàng dễ vỡ...">
        </div>

        <button type="submit" class="btn btn-warning text-dark fw-bold btn-sm px-4 shadow-xs">
          <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật Đơn Hàng
        </button>
      </form>
    </div>

    <!-- LỊCH SỬ MỐC THỜI GIAN XÁC NHẬN CÁC BƯỚC (TIMELINE AUDIT TRAIL) -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 18px; background: #ffffff;">
      <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
        <h5 class="fw-bold text-dark mb-0">
          <i class="fa-solid fa-timeline me-2 text-warning"></i> Lịch Sử Mốc Thời Gian Xác Nhận Các Bước
        </h5>
        <span class="badge bg-primary-subtle text-primary font-monospace small fw-bold">
          <i class="fa-solid fa-calendar-check me-1"></i> Ngày &amp; Giờ Chuẩn Hệ Thống
        </span>
      </div>

      <div class="timeline-items d-flex flex-column gap-2.5">
        
        <!-- Bước 1 -->
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-light border">
          <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 38px; height: 38px; font-size: 0.95rem;">
            <i class="fa-solid fa-clipboard-list"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
              <strong class="text-dark">Bước 1: Tạo Đơn Hàng Thành Công</strong>
              <span class="badge bg-white text-success border font-monospace fw-bold px-2 py-1">
                <i class="fa-regular fa-clock me-1"></i> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i:s') : 'N/A' }}
              </span>
            </div>
            <small class="text-muted d-block mt-0.5">Khách hàng <strong>{{ $order->customer_name }}</strong> đặt hàng thành công qua phương thức <strong>{{ $order->payment_method_name }}</strong>.</small>
          </div>
        </div>

        <!-- Bước 2 -->
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 {{ $order->status_step >= 2 ? 'bg-light border' : 'bg-white border border-dashed opacity-60' }}">
          <div class="rounded-circle {{ $order->status_step >= 2 ? 'bg-primary text-white shadow-xs' : 'bg-secondary-subtle text-muted' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.95rem;">
            <i class="fa-solid fa-clipboard-check"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
              <strong class="text-dark">Bước 2: Quản Trị Viên Xác Nhận Đơn Hàng</strong>
              @if($order->confirmed_at)
                <span class="badge bg-white text-primary border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->confirmed_at->format('d/m/Y H:i:s') }}
                </span>
              @elseif($order->status_step >= 2)
                <span class="badge bg-white text-primary border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i:s') : 'Đã xác nhận' }}
                </span>
              @else
                <span class="badge bg-light text-muted border font-monospace">Chưa xác nhận</span>
              @endif
            </div>
            <small class="text-muted d-block mt-0.5">Quản trị viên duyệt thông tin người nhận, số điện thoại và địa chỉ giao hàng.</small>
          </div>
        </div>

        <!-- Bước 3 -->
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 {{ $order->status_step >= 3 ? 'bg-light border' : 'bg-white border border-dashed opacity-60' }}">
          <div class="rounded-circle {{ $order->status_step >= 3 ? 'bg-warning text-dark shadow-xs' : 'bg-secondary-subtle text-muted' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.95rem;">
            <i class="fa-solid fa-box-open"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
              <strong class="text-dark">Bước 3: Chuyển Cho Kho Đóng Gói Bưu Phẩm</strong>
              @if($order->processing_at)
                <span class="badge bg-white text-warning text-dark border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->processing_at->format('d/m/Y H:i:s') }}
                </span>
              @elseif($order->status_step >= 3)
                <span class="badge bg-white text-warning text-dark border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i:s') : 'Đang đóng gói' }}
                </span>
              @else
                <span class="badge bg-light text-muted border font-monospace">Chờ chuyển kho</span>
              @endif
            </div>
            <small class="text-muted d-block mt-0.5">Nhân viên kho in phiếu đóng gói, kiểm tra chất lượng sản phẩm và niêm phong kiện hàng.</small>
          </div>
        </div>

        <!-- Bước 4 -->
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 {{ $order->status_step >= 4 ? 'bg-light border' : 'bg-white border border-dashed opacity-60' }}">
          <div class="rounded-circle {{ $order->status_step >= 4 ? 'bg-info text-white shadow-xs' : 'bg-secondary-subtle text-muted' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.95rem;">
            <i class="fa-solid fa-truck-fast"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
              <strong class="text-dark">Bước 4: Bàn Giao Cho Bưu Tá / Đang Giao Hàng</strong>
              @if($order->shipping_at)
                <span class="badge bg-white text-info border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->shipping_at->format('d/m/Y H:i:s') }}
                </span>
              @elseif($order->status_step >= 4)
                <span class="badge bg-white text-info border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i:s') : 'Đang giao' }}
                </span>
              @else
                <span class="badge bg-light text-muted border font-monospace">Chờ bàn giao bưu tá</span>
              @endif
            </div>
            <small class="text-muted d-block mt-0.5">Kiện hàng đã xuất kho và được bàn giao cho đối tác vận chuyển tiến hành giao tới khách.</small>
          </div>
        </div>

        <!-- Bước 5 -->
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 {{ $order->status_step >= 5 ? 'bg-light border' : 'bg-white border border-dashed opacity-60' }}">
          <div class="rounded-circle {{ $order->status_step >= 5 ? 'bg-success text-white shadow-xs' : 'bg-secondary-subtle text-muted' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.95rem;">
            <i class="fa-solid fa-handshake"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
              <strong class="text-dark">Bước 5: Giao Hàng Thành Công Đến Khách</strong>
              @if($order->delivered_at)
                <span class="badge bg-white text-success border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->delivered_at->format('d/m/Y H:i:s') }}
                </span>
              @elseif($order->status_step >= 5)
                <span class="badge bg-white text-success border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i:s') : 'Đã giao' }}
                </span>
              @else
                <span class="badge bg-light text-muted border font-monospace">Chờ giao thành công</span>
              @endif
            </div>
            <small class="text-muted d-block mt-0.5">Khách hàng nhận bưu phẩm, kiểm tra sản phẩm và ký nhận với nhân viên giao hàng.</small>
          </div>
        </div>

        <!-- Bước 6 -->
        <div class="d-flex align-items-start gap-3 p-3 rounded-3 {{ $order->status_step >= 6 ? 'bg-light border' : 'bg-white border border-dashed opacity-60' }}">
          <div class="rounded-circle {{ $order->status_step >= 6 ? 'bg-success text-white shadow-xs' : 'bg-secondary-subtle text-muted' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; font-size: 0.95rem;">
            <i class="fa-solid fa-circle-check"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
              <strong class="text-dark">Bước 6: Hoàn Tất Đơn Hàng</strong>
              @if($order->completed_at)
                <span class="badge bg-white text-success border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->completed_at->format('d/m/Y H:i:s') }}
                </span>
              @elseif($order->status_step >= 6)
                <span class="badge bg-white text-success border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->updated_at ? $order->updated_at->format('d/m/Y H:i:s') : 'Hoàn tất' }}
                </span>
              @else
                <span class="badge bg-light text-muted border font-monospace">Chờ đối soát hoàn tất</span>
              @endif
            </div>
            <small class="text-muted d-block mt-0.5">Giao dịch đã hoàn tất trọn vẹn, hệ thống tự động cộng điểm thưởng cho khách hàng.</small>
          </div>
        </div>

        @if($order->paid_at)
          <!-- Mốc thanh toán -->
          <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-success-subtle border border-success-subtle">
            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 38px; height: 38px; font-size: 0.95rem;">
              <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                <strong class="text-success">Thanh Toán Đã Thu Đủ Tiền</strong>
                <span class="badge bg-white text-success border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->paid_at->format('d/m/Y H:i:s') }}
                </span>
              </div>
              <small class="text-success text-opacity-85 d-block mt-0.5">Đã thu {{ number_format($order->total_amount, 0, ',', '.') }}₫ qua {{ $order->payment_method_name }}.</small>
            </div>
          </div>
        @endif

        @if($order->cancelled_at)
          <!-- Mốc hủy đơn -->
          <div class="d-flex align-items-start gap-3 p-3 rounded-3 bg-danger-subtle border border-danger-subtle">
            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 38px; height: 38px; font-size: 0.95rem;">
              <i class="fa-solid fa-ban"></i>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                <strong class="text-danger">Đơn Hàng Đã Bị Hủy</strong>
                <span class="badge bg-white text-danger border font-monospace fw-bold px-2 py-1">
                  <i class="fa-regular fa-clock me-1"></i> {{ $order->cancelled_at->format('d/m/Y H:i:s') }}
                </span>
              </div>
              <small class="text-danger text-opacity-85 d-block mt-0.5">Lý do hủy: {{ $order->cancel_reason ?: 'Không có' }} (Thực hiện bởi: {{ $order->cancelled_by === 'customer' ? 'Khách hàng' : ($order->cancelled_by === 'admin' ? 'Quản trị viên' : 'Hệ thống') }}).</small>
            </div>
          </div>
        @endif

      </div>
    </div>

  </div>

  <!-- RIGHT COLUMN: CUSTOMER 360 & SHIPPING RECEIVER -->
  <div class="col-lg-4">
    
    <!-- TÀI KHOẢN ĐẶT HÀNG (CUSTOMER 360 ACCOUNT INFO) -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 18px; background: #ffffff;">
      <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-id-card-clip me-1.5 text-primary"></i> Tài Khoản Đặt Hàng</span>
        <span class="badge bg-light text-muted small">Hệ Thống</span>
      </h6>
      
      @if($order->user)
        <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
          <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4 flex-shrink-0 shadow-xs" style="width: 48px; height: 48px;">
            {{ strtoupper(substr($order->user->name, 0, 1)) }}
          </div>
          <div class="overflow-hidden">
            <h6 class="fw-bold text-dark mb-0 text-truncate">{{ $order->user->name }}</h6>
            <small class="text-muted d-block text-truncate">{{ $order->user->email }}</small>
            <span class="badge bg-primary-subtle text-primary fw-semibold mt-1" style="font-size: 0.72rem;">
              <i class="fa-solid fa-user-check me-1"></i> Thành viên #{{ $order->user->id }}
            </span>
          </div>
        </div>

        <div class="d-flex flex-column gap-2.5 small">
          <div class="d-flex justify-content-between">
            <span class="text-muted">SĐT tài khoản:</span>
            <strong class="text-dark">{{ $order->user->phone ?? 'Chưa cập nhật' }}</strong>
          </div>
          <div class="d-flex justify-content-between">
            <span class="text-muted">Ngày đăng ký:</span>
            <span class="text-dark">{{ $order->user->created_at ? $order->user->created_at->format('d/m/Y') : 'N/A' }}</span>
          </div>
          @if(isset($order->user->points))
            <div class="d-flex justify-content-between">
              <span class="text-muted">Điểm tích lũy:</span>
              <span class="text-warning fw-bold"><i class="fa-solid fa-gem me-1"></i>{{ number_format($order->user->points) }} pts</span>
            </div>
          @endif
          @if(isset($order->user->total_spent))
            <div class="d-flex justify-content-between">
              <span class="text-muted">Tổng chi tiêu:</span>
              <span class="text-danger fw-bold font-monospace">{{ number_format($order->user->total_spent, 0, ',', '.') }}₫</span>
            </div>
          @endif
          
          <div class="mt-2 pt-2 border-top">
            <a href="{{ route('admin.customers.show', $order->user->id) }}" class="btn btn-outline-primary btn-sm w-100 py-1.5 fw-bold" style="font-size: 0.78rem;">
              <i class="fa-solid fa-address-card me-1"></i> Xem Hồ Sơ &amp; Lịch Sử Mua Hàng
            </a>
          </div>
        </div>
      @else
        <div class="p-3 bg-light rounded-3 text-center">
          <div class="rounded-circle bg-secondary-subtle text-secondary d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-user-slash fs-5"></i>
          </div>
          <h6 class="fw-bold text-dark mb-1">Khách Vãng Lai</h6>
          <small class="text-muted d-block">Đơn hàng được đặt mà không đăng nhập tài khoản hệ thống.</small>
        </div>
      @endif
    </div>

    <!-- THÔNG TIN NGƯỜI NHẬN HÀNG & GIAO VẬN -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 18px; background: #ffffff;">
      <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-location-dot me-1.5 text-danger"></i> Thông Tin Giao Nhận Hàng</span>
        <span class="badge bg-light text-muted small">Bưu Tá</span>
      </h6>
      
      <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
        <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center fs-4 flex-shrink-0" style="width: 46px; height: 46px;">
          <i class="fa-solid fa-user-tag text-warning"></i>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-0">{{ $order->customer_name }}</h6>
          <small class="text-muted">{{ $order->customer_email ?? 'Chưa cung cấp email' }}</small>
        </div>
      </div>

      <div class="d-flex flex-column gap-2.5 small">
        <div>
          <span class="text-muted d-block mb-1">Số điện thoại nhận:</span>
          <a href="tel:{{ $order->customer_phone }}" class="text-primary fw-bold text-decoration-none fs-6">
            <i class="fa-solid fa-phone me-1"></i> {{ $order->customer_phone }}
          </a>
        </div>
        <div>
          <span class="text-muted d-block mb-1">Địa chỉ giao hàng:</span>
          <strong class="text-dark d-block leading-snug">
            <i class="fa-solid fa-house me-1 text-danger"></i> {{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}
          </strong>
        </div>
        <div>
          <span class="text-muted d-block mb-1">Phương thức thanh toán:</span>
          <span class="badge bg-light text-dark border fw-bold">{{ $order->payment_method_name }}</span>
        </div>
        <div>
          <span class="text-muted d-block mb-1">Trạng thái thanh toán:</span>
          <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success text-white' : 'bg-warning text-dark' }} fw-bold">
            <i class="fa-solid {{ $order->payment_status === 'paid' ? 'fa-check' : 'fa-clock' }} me-1"></i> {{ $order->payment_status_label }}
          </span>
        </div>
        @if($order->notes)
          <div class="mt-2 pt-2 border-top bg-light p-2.5 rounded-3">
            <span class="text-muted d-block mb-1 fw-bold"><i class="fa-regular fa-comment-dots me-1 text-warning"></i> Ghi chú của khách:</span>
            <span class="text-dark fst-italic">"{{ $order->notes }}"</span>
          </div>
        @endif
      </div>
    </div>

    <!-- TÀI KHOẢN THỤ HƯỞNG & DÒNG TIỀN DOANH NGHIỆP -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 18px; background: #ffffff;">
      <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
        <i class="fa-solid fa-building-columns me-1.5 text-primary"></i> Tài Khoản Thụ Hưởng Doanh Nghiệp
      </h6>
      <div class="p-3 bg-light rounded-3 small">
        <div class="d-flex justify-content-between mb-1.5">
          <span class="text-muted">Chủ tài khoản:</span>
          <strong class="text-dark">NGUYEN XUAN BAC</strong>
        </div>
        <div class="d-flex justify-content-between mb-1.5">
          <span class="text-muted">Ngân hàng:</span>
          <strong class="text-primary">Techcombank (TCB)</strong>
        </div>
        <div class="d-flex justify-content-between">
          <span class="text-muted">Số tài khoản:</span>
          <strong class="text-danger font-monospace">77427842310105</strong>
        </div>
      </div>
    </div>

  </div>

</div>

<!-- MODAL TẠO VẬN ĐƠN & BÀN GIAO CHO BƯU TÁ (DISPATCH SHIPMENT) -->
<div class="modal fade" id="dispatchCarrierModal" tabindex="-1" aria-labelledby="dispatchCarrierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
    <div class="modal-content border-0 shadow-2xl rounded-4">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark" id="dispatchCarrierModalLabel">
          <i class="fa-solid fa-truck-fast text-info me-2"></i> Bàn Giao Hàng Cho Bưu Tá
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
        @csrf
        <input type="hidden" name="shipping_status" value="shipping">
        <div class="modal-body py-3">
          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Chọn Đơn Vị Vận Chuyển Đối Tác:</label>
            <select name="shipping_carrier" class="form-select" required id="carrierSelect" onchange="generateTrackingCode(this.value)">
              <option value="Giao Hàng Tiết Kiệm (GHTK)">Giao Hàng Tiết Kiệm (GHTK)</option>
              <option value="Giao Hàng Nhanh (GHN)">Giao Hàng Nhanh (GHN)</option>
              <option value="Viettel Post">Viettel Post</option>
              <option value="J&T Express">J&T Express</option>
              <option value="Ninja Van">Ninja Van</option>
              <option value="Shipper Nội Bộ BeeStyle">Shipper Nội Bộ BeeStyle</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold text-dark">Mã Vận Đơn Bưu Tá (Tracking Code):</label>
            <div class="input-group">
              <input type="text" name="tracking_code" id="trackingCodeInput" class="form-control font-monospace fw-bold text-primary" value="GHTK-{{ strtoupper(Str::random(8)) }}" required>
              <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generateRandomTracking()">
                <i class="fa-solid fa-arrows-rotate"></i> Tạo Mới
              </button>
            </div>
            <small class="text-muted" style="font-size: 0.75rem;">Mã này sẽ hiển thị trực tiếp trên trang Tra Cứu Đơn Hàng của khách.</small>
          </div>

          <div class="alert alert-info py-2.5 px-3 rounded-3 small mb-0">
            <i class="fa-solid fa-circle-info me-1"></i> Sau khi bấm xác nhận, trạng thái đơn hàng sẽ chuyển sang <strong>"Bước 4: Đang Giao Hàng"</strong>.
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-info text-white fw-bold btn-sm px-4 shadow-xs">
            <i class="fa-solid fa-paper-plane me-1"></i> Bàn Giao Vận Chuyển Ngay
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function generateTrackingCode(carrier) {
    let prefix = 'GHTK';
    if (carrier.includes('GHN')) prefix = 'GHN';
    else if (carrier.includes('Viettel')) prefix = 'VTP';
    else if (carrier.includes('J&T')) prefix = 'JT';
    else if (carrier.includes('Ninja')) prefix = 'NJV';
    else if (carrier.includes('Nội Bộ')) prefix = 'BEE';

    const randomStr = Math.random().toString(36).substring(2, 10).toUpperCase();
    document.getElementById('trackingCodeInput').value = prefix + '-' + randomStr;
  }

  function generateRandomTracking() {
    const carrier = document.getElementById('carrierSelect').value;
    generateTrackingCode(carrier);
  }
</script>
@endpush
@endsection
