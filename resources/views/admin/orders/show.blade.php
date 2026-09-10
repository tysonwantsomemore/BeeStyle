@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng #' . $order->order_code . ' | BeeStyle Admin')

@section('content')
<!-- TOP HEADER -->
<div class="mb-4 d-print-none">
  <div class="row gy-3 justify-content-between align-items-center">
    <div class="col-md">
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-phoenix-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="mb-0 text-body-emphasis fw-bold font-monospace">#{{ $order->order_code }}</h2>
        <span class="badge badge-phoenix {{ $order->shipping_status === 'completed' ? 'badge-phoenix-success' : ($order->shipping_status === 'cancelled' ? 'badge-phoenix-danger' : 'badge-phoenix-warning') }} fs-10">
          {{ $order->status_label }}
        </span>
        <span class="badge badge-phoenix {{ $order->payment_status === 'paid' ? 'badge-phoenix-success' : 'badge-phoenix-warning' }} fs-10">
          <i class="fa-solid {{ $order->payment_status === 'paid' ? 'fa-circle-check' : 'fa-clock' }} me-1"></i> {{ $order->payment_status_label }}
        </span>
        @if($order->is_deposit_required)
          <span class="badge bg-warning text-dark px-3 py-1.5 fw-bold rounded-pill shadow-xs">
            <i class="fa-solid fa-coins me-1"></i> Cọc 50% ({{ number_format($order->deposit_amount ?: round($order->total_amount * 0.5), 0, ',', '.') }}₫)
          </span>
        @endif
      </div>
      <p class="text-body-tertiary mb-0 fs-9">
        <i class="fa-regular fa-clock me-1"></i> Thời gian đặt: <strong>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i:s') : 'N/A' }}</strong> 
        • Thanh toán: <strong class="text-body-emphasis">{{ $order->payment_method_name }}</strong>
      </p>
    </div>

    <!-- ACTION BUTTONS -->
    <div class="col-auto">
      <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-phoenix-primary btn-sm" onclick="window.print()">
          <i class="fa-solid fa-print me-1"></i> In Phiếu Giao Hàng
        </button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-phoenix-secondary btn-sm">
          <i class="fa-solid fa-list me-1"></i> Danh Sách Đơn
        </a>
      </div>
    </div>
  </div>
</div>

<!-- PRINT ONLY PACKING SLIP -->
<div class="d-none d-print-block mb-4 p-4 border rounded bg-white text-dark">
  <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
    <div>
      <h3 class="fw-bold mb-0">BEESTYLE MENSWEAR</h3>
      <p class="small text-muted mb-0">Website: beestyle.vn • Hotline: 1900 8888</p>
    </div>
    <div class="text-end">
      <h4 class="fw-bold font-monospace mb-0">PHIẾU ĐÓNG GÓI &amp; GIAO HÀNG</h4>
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
      <strong>Tổng Thu Người Nhận:</strong> <strong class="fs-6 text-danger">{{ $order->payment_status === 'paid' ? '0₫ (Đã thanh toán Online)' : number_format($order->total_amount, 0, ',', '.') . '₫ (Thu COD)' }}</strong>
    </div>
  </div>
</div>

<!-- ACTIVE RMA RETURN REQUEST ALERT -->
@if($order->returns && $order->returns->count() > 0)
  @php $latestRma = $order->returns->first(); @endphp
  <div class="alert alert-warning border border-translucent shadow-sm p-3 mb-4 rounded d-flex align-items-center justify-content-between flex-wrap gap-3 d-print-none">
    <div class="d-flex align-items-center gap-3">
      <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
        <i class="fa-solid fa-arrow-rotate-left fs-7"></i>
      </div>
      <div>
        <h5 class="fw-bold text-body-emphasis mb-1">ĐƠN HÀNG CÓ PHIẾU YÊU CẦU ĐỔI TRẢ (#{{ $latestRma->return_code }})</h5>
        <p class="mb-0 text-body-tertiary fs-10">Hình thức: <strong>{{ $latestRma->type_label }}</strong> • Lý do: <strong>{{ $latestRma->reason }}</strong> • Trạng thái: {!! $latestRma->status_badge !!}</p>
      </div>
    </div>
    <div>
      <a href="{{ route('admin.returns.show', $latestRma->id) }}" class="btn btn-warning btn-sm fw-bold">
        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Xử Lý Phiếu RMA
      </a>
    </div>
  </div>
@endif

<!-- VISUAL ORDER FULFILLMENT STEPPER -->
<div class="card border-0 shadow-sm mb-4 d-print-none">
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
    <h6 class="fw-bold text-body-emphasis mb-0">
      <i class="fa-solid fa-truck-ramp-box me-2 text-primary"></i> Quy Trình Xử Lý &amp; Vận Chuyển Đơn Hàng
    </h6>
    <span class="badge badge-phoenix badge-phoenix-secondary">Bước {{ $order->status_step ?? 1 }}/6</span>
  </div>

  @php
    $steps = [
      1 => ['code' => 'pending', 'label' => '1. Chờ Xác Nhận', 'icon' => 'fa-clipboard-list', 'desc' => 'Đơn hàng mới tạo', 'time' => $order->created_at],
      2 => ['code' => 'confirmed', 'label' => '2. Đã Xác Nhận', 'icon' => 'fa-clipboard-check', 'desc' => 'Đã duyệt thông tin', 'time' => $order->confirmed_at],
      3 => ['code' => 'processing', 'label' => '3. Đang Đóng Gói', 'icon' => 'fa-box-open', 'desc' => 'Kho nhặt hàng & gói', 'time' => $order->processing_at],
      4 => ['code' => 'shipping', 'label' => '4. Đang Giao Hàng', 'icon' => 'fa-truck-fast', 'desc' => 'Bưu tá vận chuyển', 'time' => $order->shipping_at],
      5 => ['code' => 'delivered', 'label' => '5. Đã Giao Hàng', 'icon' => 'fa-handshake', 'desc' => 'Khách nhận kiểm tra', 'time' => $order->delivered_at],
      6 => ['code' => 'completed', 'label' => '6. Hoàn Tất', 'icon' => 'fa-circle-check', 'desc' => 'Thành công', 'time' => $order->completed_at],
    ];
    $currentStep = $order->shipping_status === 'cancelled' ? 0 : ($order->status_step ?? 1);
  @endphp

  <div class="card-body">
    @if(method_exists($order, 'isCustomerRejected') && $order->isCustomerRejected())
      <div class="alert alert-danger py-3 px-4 rounded d-flex align-items-center gap-3 mb-0" style="background: #fff5f5; border: 1.5px solid #ef4444;">
        <i class="fa-solid fa-truck-arrow-right fs-2 text-danger"></i>
        <div>
          <strong class="fs-9 d-block text-danger">ĐƠN HÀNG BỊ KHÁCH TỪ CHỐI NHẬN - ĐANG CHUYỂN HOÀN VỀ KHO</strong>
          <span class="fs-10 text-danger text-opacity-80">
            Lý do từ chối: <strong>{{ $order->cancel_reason ?: 'Không nhận bưu phẩm' }}</strong> • Thực hiện bởi: <strong>Khách hàng từ chối nhận khi bưu tá giao</strong> • Thời gian: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i:s') : '' }}
          </span>
          @if($order->delivery_proof_image)
            <div class="mt-1">
              <a href="{{ $order->delivery_proof_url }}" target="_blank" class="btn btn-xs btn-outline-danger py-0.5 px-2 rounded-pill fw-bold">
                <i class="fa-solid fa-image me-1"></i> Xem ảnh bằng chứng đối soát
              </a>
            </div>
          @endif
        </div>
      </div>
    @elseif($order->shipping_status === 'cancelled')
      <div class="alert alert-danger py-3 px-4 rounded d-flex align-items-center gap-3 mb-0">
        <i class="fa-solid fa-ban fs-2 text-danger"></i>
        <div>
          <strong class="fs-9 d-block">ĐƠN HÀNG ĐÃ BỊ HỦY (CANCELLED)</strong>
          <span class="fs-10 text-danger">
            Lý do hủy: <strong>{{ $order->cancel_reason ?: 'Không có ghi chú' }}</strong> • Người hủy: <strong>{{ $order->cancelled_by === 'customer' ? 'Khách hàng tự hủy' : ($order->cancelled_by === 'admin' ? 'Quản trị viên' : 'Hệ thống tự động') }}</strong> • Thời gian: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i:s') : ($order->updated_at ? $order->updated_at->format('d/m/Y H:i:s') : '') }}
          </span>
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
              <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm mb-2"
                   style="width: 42px; height: 42px; font-size: 1rem; 
                          background-color: {{ $isCurrent ? '#3874ff' : ($isDone ? '#25b003' : 'var(--phoenix-gray-200)') }}; 
                          color: {{ $isDone || $isCurrent ? '#ffffff' : 'var(--phoenix-gray-600)' }};">
                <i class="fa-solid {{ $sData['icon'] }}"></i>
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

    <!-- QUICK ACTIONS -->
    @if($order->shipping_status !== 'cancelled' && $order->shipping_status !== 'completed')
      <div class="pt-3 mt-3 border-top border-translucent d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="fs-10 text-body-tertiary">
          <i class="fa-solid fa-bolt text-warning me-1"></i> Thao tác nhanh chuyển bước:
        </div>
        <div class="d-flex gap-2 flex-wrap">
          @if($order->shipping_status === 'pending')
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="shipping_status" value="confirmed">
              <button type="submit" class="btn btn-sm btn-primary fw-bold shadow-xs">
                <i class="fa-solid fa-check me-1"></i> Bước 2: Xác Nhận Đơn Hàng
              </button>
            </form>
          @elseif($order->shipping_status === 'confirmed')
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="shipping_status" value="processing">
              <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold shadow-xs">
                <i class="fa-solid fa-box-open me-1"></i> Bước 3: Cho Kho Đóng Gói
              </button>
            </form>
          @elseif($order->shipping_status === 'processing')
            <button type="button" class="btn btn-sm btn-info text-white fw-bold shadow-xs" data-bs-toggle="modal" data-bs-target="#dispatchCarrierModal">
              <i class="fa-solid fa-truck-fast me-1"></i> Bước 4: Tạo Vận Đơn &amp; Giao Bưu Tá
            </button>
          @elseif($order->shipping_status === 'shipping')
            <button type="button" class="btn btn-sm btn-success fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#adminPodDeliveryModal">
              <i class="fa-solid fa-camera me-1"></i> Bước 5: Bưu Tá Báo Giao (Ảnh POD)
            </button>
          @elseif($order->shipping_status === 'delivered')
            @if($order->delivery_proof_url)
              <button type="button" class="btn btn-sm btn-outline-success fw-bold px-3" data-bs-toggle="modal" data-bs-target="#viewPodDetailModal">
                <i class="fa-solid fa-image me-1"></i> Xem Ảnh POD Bưu Tá
              </button>
            @endif
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
              <button type="submit" class="btn btn-sm btn-phoenix-success">
                <i class="fa-solid fa-money-bill-wave me-1"></i> Đã Thu Tiền (Mark Paid)
              </button>
            </form>
          @endif

          @if($order->canTransitionTo('cancelled'))
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn HỦY đơn hàng #{{ $order->order_code }}? Toàn bộ sản phẩm sẽ được tự động hoàn lại vào kho hàng!')">
              @csrf
              <input type="hidden" name="shipping_status" value="cancelled">
              <button type="submit" class="btn btn-sm btn-phoenix-danger">
                <i class="fa-solid fa-xmark me-1"></i> Hủy Đơn
              </button>
            </form>
          @endif
        </div>
      </div>
    @endif
  </div>
</div>

<div class="row g-4">
  <!-- LEFT COLUMN: PRODUCT PICKING LIST -->
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
        <h5 class="fw-bold text-body-emphasis mb-0">
          <i class="fa-solid fa-boxes-stacked me-2 text-primary"></i> Sản Phẩm Đóng Gói ({{ $order->items->count() }} Món)
        </h5>
        <span class="badge badge-phoenix badge-phoenix-success">
          <i class="fa-solid fa-check me-1"></i> Đã Trừ Kho
        </span>
      </div>
      
      <div class="card-body p-0">
        <div class="table-responsive scrollbar">
          <table class="table table-sm fs-9 mb-0 align-middle">
            <thead class="bg-body-tertiary text-body-tertiary">
              <tr>
                <th class="ps-3 py-2">Sản Phẩm &amp; Phân Loại</th>
                <th class="py-2">Mã SKU</th>
                <th class="py-2">Đơn Giá</th>
                <th class="py-2">Số Lượng</th>
                <th class="text-end pe-3 py-2">Thành Tiền</th>
              </tr>
            </thead>
            <tbody class="list">
              @foreach($order->items as $item)
                <tr class="border-bottom border-translucent">
                  <td class="ps-3 py-2">
                    <div class="d-flex align-items-center gap-2.5">
                      <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" 
                           style="width: 44px; height: 44px; object-fit: contain;" class="border border-translucent rounded bg-body-emphasis p-1 flex-shrink-0">
                      <div>
                        <div class="fw-bold text-body-emphasis fs-9">{{ $item->product_name }}</div>
                        <div class="d-flex align-items-center gap-1 mt-0.5 flex-wrap">
                          <span class="badge badge-phoenix badge-phoenix-secondary fs-11">
                            Màu: {{ $item->color ?? 'Tiêu chuẩn' }}
                          </span>
                          <span class="badge badge-phoenix badge-phoenix-secondary fs-11">
                            Size: {{ $item->size ?? 'M' }}
                          </span>
                          @if($item->product)
                            <span class="badge badge-phoenix badge-phoenix-info fs-11">
                              Kho còn: {{ $item->product->stock }}
                            </span>
                          @endif
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="py-2"><span class="font-monospace fs-10 text-body-tertiary">{{ $item->product_sku ?? 'BS-PROD' }}</span></td>
                  <td class="py-2">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                  <td class="py-2">
                    <span class="badge badge-phoenix badge-phoenix-danger fw-bold">
                      x{{ $item->quantity }}
                    </span>
                  </td>
                  <td class="text-end pe-3 py-2 fw-bold text-body-emphasis font-monospace">
                    {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot class="bg-body-tertiary">
              <tr>
                <td colspan="4" class="text-end text-body-tertiary fs-10 ps-3">Tạm tính tiền hàng:</td>
                <td class="text-end pe-3 fw-bold font-monospace">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
              </tr>
              @if($order->discount_amount > 0)
                <tr>
                  <td colspan="4" class="text-end text-success fs-10 ps-3">
                    <i class="fa-solid fa-tags me-1"></i> Giảm giá Voucher (<strong>{{ $order->coupon_code }}</strong>):
                  </td>
                  <td class="text-end pe-3 text-success fw-bold font-monospace">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
                </tr>
              @endif
              <tr>
                <td colspan="4" class="text-end text-body-tertiary fs-10 ps-3">Phí vận chuyển:</td>
                <td class="text-end pe-3 text-success fw-bold font-monospace">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . '₫' : 'Miễn Phí (0₫)' }}</td>
              </tr>
              <tr class="bg-body-emphasis">
                <td colspan="4" class="text-end fw-bold text-body-emphasis fs-9 ps-3">TỔNG TIỀN ĐƠN HÀNG:</td>
                <td class="text-end pe-3 fw-bold text-danger fs-8 font-monospace">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
              </tr>
              @if($order->is_deposit_required)
                <tr class="table-warning">
                  <td colspan="4" class="text-end fw-bold text-dark small ps-3">
                    <i class="fa-solid fa-coins text-warning me-1"></i> TIỀN ĐẶT CỌC 50% (ĐƠN TỪ 10 SẢN PHẨM):
                  </td>
                  <td class="text-end pe-3 fw-bold text-danger font-monospace fs-8">{{ number_format($order->deposit_amount ?: round($order->total_amount * 0.5), 0, ',', '.') }}₫</td>
                </tr>
                <tr class="table-info">
                  <td colspan="4" class="text-end fw-bold text-dark small ps-3">
                    <i class="fa-solid fa-hand-holding-dollar text-primary me-1"></i> TIỀN CÒN LẠI THU BƯU TÁ (COD):
                  </td>
                  <td class="text-end pe-3 fw-bold text-primary font-monospace fs-8">{{ number_format($order->remaining_amount ?: ($order->total_amount - round($order->total_amount * 0.5)), 0, ',', '.') }}₫</td>
                </tr>
              @endif
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <!-- UPDATE STATUS & CARRIER FORM -->
    <div class="card border-0 shadow-sm mb-4 d-print-none">
      <div class="card-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="fw-bold text-body-emphasis mb-0">
          <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Cập Nhật Trạng Thái &amp; Vận Đơn
        </h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @if($order->isFinalStatus())
            <div class="alert alert-subtle-success d-flex align-items-center gap-2 py-2 px-3 mb-3 fs-10 rounded">
              <i class="fa-solid fa-lock text-success fs-9"></i>
              <div>
                <strong>Tiến trình đơn hàng đã kết thúc ({{ $order->status_label }}):</strong> 
                Trạng thái vận chuyển đã được khóa an toàn để bảo vệ số liệu kế toán, đối soát bưu tá và điểm thưởng tích lũy của khách hàng.
              </div>
            </div>
          @endif

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fs-9 fw-semibold">Trạng thái vận chuyển:</label>
              @php
                $allStatusOptions = [
                  'pending'    => '1. Chờ xác nhận đơn hàng',
                  'confirmed'  => '2. Đã xác nhận thông tin',
                  'processing' => '3. Đang đóng gói bưu phẩm',
                  'shipping'   => '4. Đang giao hàng bưu tá',
                  'delivered'  => '5. Đã giao tới người nhận (POD)',
                  'completed'  => '6. Hoàn tất đơn hàng',
                  'cancelled'  => '0. Hủy đơn hàng (Hoàn kho)',
                ];
              @endphp
              <select name="shipping_status" class="form-select" {{ $order->isFinalStatus() ? 'disabled' : '' }}>
                @foreach($allStatusOptions as $optKey => $optLabel)
                  @php
                    $isCurrent = $order->shipping_status === $optKey;
                    $canSelect = $isCurrent || $order->canTransitionTo($optKey);
                  @endphp
                  <option value="{{ $optKey }}" {{ $isCurrent ? 'selected' : '' }} {{ !$canSelect ? 'disabled class=text-muted' : '' }}>
                    {{ $optLabel }} {{ !$canSelect ? '(Đã qua bước này)' : ($isCurrent ? '— [Hiện tại]' : '') }}
                  </option>
                @endforeach
              </select>
              @if($order->isFinalStatus())
                <input type="hidden" name="shipping_status" value="{{ $order->shipping_status }}">
              @endif
              <small class="text-body-tertiary fs-11 mt-1 d-block">
                <i class="fa-solid fa-shield-halved text-primary me-1"></i> Hệ thống tự động khóa các bước trước đó theo quy tắc vận hành TMĐT 1 chiều.
              </small>
            </div>

            <div class="col-md-6">
              <label class="form-label fs-9 fw-semibold">Trạng thái thanh toán:</label>
              <select name="payment_status" class="form-select">
                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền (RMA)</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fs-9 fw-semibold">Đối tác vận chuyển:</label>
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
              <label class="form-label fs-9 fw-semibold">Mã vận đơn bưu tá (Tracking Code):</label>
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

          <!-- UPLOAD BẰNG CHỨNG GIAO HÀNG (POD) -->
          <div class="p-3 bg-body-tertiary rounded-3 mb-3 border border-translucent">
            <label class="form-label fs-9 fw-bold text-body-emphasis d-flex align-items-center gap-1.5 mb-1.5">
              <i class="fa-solid fa-camera text-success"></i> Tải Ảnh Bằng Chứng Bưu Tá Giao Hàng (POD) Gửi Về Kho:
            </label>
            <div class="row g-2 align-items-center">
              <div class="col-md-6">
                <input type="file" name="delivery_proof_file" class="form-control form-control-sm" accept="image/*">
                <small class="text-body-tertiary fs-11">Chấp nhận JPG, PNG, WEBP (tối đa 10MB)</small>
              </div>
              <div class="col-md-6">
                <input type="text" name="delivery_proof_note" class="form-control form-control-sm" value="{{ $order->delivery_proof_note }}" placeholder="Ghi chú giao nhận bưu tá...">
              </div>
            </div>
            @if($order->delivery_proof_image || in_array($order->shipping_status, ['delivered', 'completed']))
              <div class="mt-2 small text-success fw-semibold d-flex align-items-center gap-1">
                <i class="fa-solid fa-circle-check"></i> Đơn đã có ảnh POD: 
                <a href="{{ $order->delivery_proof_url }}" target="_blank" class="text-success text-decoration-underline font-monospace">Xem ảnh lưu trữ kho</a>
              </div>
            @endif
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Ghi chú nội bộ admin:</label>
            <input type="text" name="admin_notes" class="form-control" value="{{ $order->admin_notes }}" placeholder="VD: Bưu tá đã lấy hàng lúc 14h30, hàng dễ vỡ...">
          </div>

          <button type="submit" class="btn btn-primary btn-sm px-4">
            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật Đơn Hàng
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- RIGHT COLUMN -->
  <div class="col-12 col-lg-4">
    <!-- TÀI KHOẢN ĐẶT HÀNG -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-body-emphasis mb-0">
          <i class="fa-solid fa-id-card-clip me-1 text-primary"></i> Tài Khoản Đặt Hàng
        </h6>
        <span class="badge badge-phoenix badge-phoenix-secondary">Hệ Thống</span>
      </div>
      
      <div class="card-body">
        @if($order->user)
          <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom border-translucent">
            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-7 flex-shrink-0" style="width: 44px; height: 44px;">
              {{ strtoupper(substr($order->user->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
              <h6 class="fw-bold text-body-emphasis mb-0 text-truncate">{{ $order->user->name }}</h6>
              <small class="text-body-tertiary d-block text-truncate fs-10">{{ $order->user->email }}</small>
              <span class="badge badge-phoenix badge-phoenix-primary mt-1 fs-11">
                Thành viên #{{ $order->user->id }}
              </span>
            </div>
          </div>

          <div class="d-flex flex-column gap-2 fs-10">
            <div class="d-flex justify-content-between">
              <span class="text-body-tertiary">SĐT tài khoản:</span>
              <strong class="text-body-emphasis">{{ $order->user->phone ?? 'Chưa cập nhật' }}</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-body-tertiary">Ngày đăng ký:</span>
              <span class="text-body-emphasis">{{ $order->user->created_at ? $order->user->created_at->format('d/m/Y') : 'N/A' }}</span>
            </div>
            @if(isset($order->user->points))
              <div class="d-flex justify-content-between">
                <span class="text-body-tertiary">Điểm tích lũy:</span>
                <span class="text-warning fw-bold"><i class="fa-solid fa-gem me-1"></i>{{ number_format($order->user->points) }} pts</span>
              </div>
            @endif
            @if(isset($order->user->total_spent))
              <div class="d-flex justify-content-between">
                <span class="text-body-tertiary">Tổng chi tiêu:</span>
                <span class="text-danger fw-bold font-monospace">{{ number_format($order->user->total_spent, 0, ',', '.') }}₫</span>
              </div>
            @endif
            
            <div class="mt-2 pt-2 border-top border-translucent">
              <a href="{{ route('admin.customers.show', $order->user->id) }}" class="btn btn-phoenix-primary btn-sm w-100 fs-10">
                <i class="fa-solid fa-address-card me-1"></i> Xem Hồ Sơ Khách Hàng
              </a>
            </div>
          </div>
        @else
          <div class="p-3 bg-body-tertiary rounded text-center">
            <div class="rounded-circle bg-body-emphasis text-body-tertiary d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
              <i class="fa-solid fa-user-slash fs-8"></i>
            </div>
            <h6 class="fw-bold text-body-emphasis mb-1 fs-9">Khách Vãng Lai</h6>
            <small class="text-body-tertiary d-block fs-10">Đơn hàng được đặt mà không đăng nhập tài khoản hệ thống.</small>
          </div>
        @endif
      </div>
    </div>

    <!-- BẰNG CHỨNG GIAO HÀNG BƯU TÁ (POD) -->
    <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid {{ in_array($order->shipping_status, ['delivered', 'completed']) ? '#25b003' : '#f59e0b' }} !important;">
      <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2">
          <i class="fa-solid fa-camera-retro text-success"></i>
          <span>Ảnh Chụp Xác Nhận Giao (POD)</span>
        </h6>
        @if(in_array($order->shipping_status, ['delivered', 'completed']) || $order->delivery_proof_image)
          <span class="badge badge-phoenix badge-phoenix-success fs-11">
            <i class="fa-solid fa-circle-check me-0.5"></i> Đã Gửi Về Kho
          </span>
        @else
          <span class="badge badge-phoenix badge-phoenix-warning fs-11">
            <i class="fa-solid fa-clock me-0.5"></i> Chờ Bưu Tá Chụp
          </span>
        @endif
      </div>

      <div class="card-body">
        @if(in_array($order->shipping_status, ['delivered', 'completed']) || $order->delivery_proof_image)
          <div class="position-relative rounded overflow-hidden border mb-3 text-center bg-dark" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#viewPodDetailModal">
            <img src="{{ $order->delivery_proof_url }}" alt="Bằng chứng giao hàng bưu tá" class="img-fluid w-100" style="max-height: 200px; object-fit: cover;">
            <div class="position-absolute bottom-0 start-0 end-0 p-2 text-white bg-dark bg-opacity-75 d-flex justify-content-between align-items-center fs-10">
              <span><i class="fa-solid fa-shield-check text-success me-1"></i> Bưu tá xác nhận</span>
              <span class="badge bg-light text-dark fw-bold"><i class="fa-solid fa-magnifying-glass-plus me-1"></i> Phóng to HD</span>
            </div>
          </div>

          <div class="d-flex flex-column gap-2 fs-10">
            <div class="d-flex justify-content-between">
              <span class="text-body-tertiary">Mốc giờ gửi về kho:</span>
              <strong class="text-body-emphasis font-monospace">{{ $order->delivery_proof_at ? $order->delivery_proof_at->format('d/m/Y H:i:s') : ($order->delivered_at ? $order->delivered_at->format('d/m/Y H:i:s') : 'N/A') }}</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-body-tertiary">Bưu tá phụ trách:</span>
              <strong class="text-body-emphasis">{{ $order->shipping_carrier ?: 'GHTK' }}</strong>
            </div>
            <div>
              <span class="text-body-tertiary d-block mb-1">Ghi chú bưu tá giao hàng:</span>
              <div class="p-2 bg-body-tertiary rounded text-body-emphasis fst-italic fs-10">
                "{{ $order->delivery_proof_note ?: 'Khách hàng đã nhận đủ kiện hàng nguyên vẹn tem niêm phong và thanh toán đầy đủ.' }}"
              </div>
            </div>
            <div class="d-flex gap-2 mt-2 pt-2 border-top border-translucent">
              <button type="button" class="btn btn-outline-success btn-sm w-100 fw-bold fs-10" data-bs-toggle="modal" data-bs-target="#viewPodDetailModal">
                <i class="fa-solid fa-expand me-1"></i> Phóng To Ảnh
              </button>
              <button type="button" class="btn btn-outline-secondary btn-sm text-nowrap fs-10" data-bs-toggle="modal" data-bs-target="#adminPodDeliveryModal" title="Tải ảnh mới hoặc sửa ghi chú">
                <i class="fa-solid fa-camera"></i> Đổi Ảnh
              </button>
            </div>
          </div>
        @else
          <div class="p-3 bg-body-tertiary rounded text-center">
            <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center mb-2" style="width: 44px; height: 44px;">
              <i class="fa-solid fa-camera fs-5"></i>
            </div>
            <h6 class="fw-bold text-body-emphasis mb-1 fs-9">Chưa Có Ảnh Chụp Giao Hàng</h6>
            <p class="text-body-tertiary fs-10 mb-3">Khi bưu tá giao hàng tới địa chỉ nhận, ảnh chụp kiện hàng sẽ gửi về hệ thống kho để xác nhận giao thành công.</p>
            <button type="button" class="btn btn-phoenix-primary btn-sm px-3 fw-bold rounded-pill" data-bs-toggle="modal" data-bs-target="#adminPodDeliveryModal">
              <i class="fa-solid fa-upload me-1"></i> Bưu Tá Gửi Ảnh POD
            </button>
          </div>
        @endif
      </div>
    </div>

    <!-- THÔNG TIN GIAO NHẬN HÀNG -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center">
        <h6 class="fw-bold text-body-emphasis mb-0">
          <i class="fa-solid fa-location-dot me-1 text-danger"></i> Thông Tin Giao Nhận Hàng
        </h6>
        <span class="badge badge-phoenix badge-phoenix-secondary">Bưu Tá</span>
      </div>
      
      <div class="card-body">
        <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom border-translucent">
          <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center fs-8 flex-shrink-0" style="width: 40px; height: 40px;">
            <i class="fa-solid fa-user-tag"></i>
          </div>
          <div>
            <h6 class="fw-bold text-body-emphasis mb-0">{{ $order->customer_name }}</h6>
            <small class="text-body-tertiary fs-10">{{ $order->customer_email ?? 'Chưa cung cấp email' }}</small>
          </div>
        </div>

        <div class="d-flex flex-column gap-2 fs-10">
          <div>
            <span class="text-body-tertiary d-block mb-1">Số điện thoại nhận:</span>
            <a href="tel:{{ $order->customer_phone }}" class="text-primary fw-bold text-decoration-none fs-9">
              <i class="fa-solid fa-phone me-1"></i> {{ $order->customer_phone }}
            </a>
          </div>
          <div>
            <span class="text-body-tertiary d-block mb-1">Địa chỉ giao hàng:</span>
            <strong class="text-body-emphasis d-block">
              <i class="fa-solid fa-house me-1 text-danger"></i> {{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}
            </strong>
          </div>
          <div>
            <span class="text-body-tertiary d-block mb-1">Phương thức thanh toán:</span>
            <span class="badge badge-phoenix badge-phoenix-secondary">{{ $order->payment_method_name }}</span>
          </div>
          <div>
            <span class="text-body-tertiary d-block mb-1">Trạng thái thanh toán:</span>
            <span class="badge badge-phoenix {{ $order->payment_status === 'paid' ? 'badge-phoenix-success' : 'badge-phoenix-warning' }}">
              <i class="fa-solid {{ $order->payment_status === 'paid' ? 'fa-check' : 'fa-clock' }} me-1"></i> {{ $order->payment_status_label }}
            </span>
          </div>
          @if($order->notes)
            <div class="mt-2 pt-2 border-top border-translucent bg-body-tertiary p-2 rounded">
              <span class="text-body-tertiary d-block mb-1 fw-bold fs-10"><i class="fa-regular fa-comment-dots me-1 text-warning"></i> Ghi chú của khách:</span>
              <span class="text-body-emphasis fst-italic fs-10">"{{ $order->notes }}"</span>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL BÀN GIAO CHO BƯU TÁ (BƯỚC 4) -->
<div class="modal fade" id="dispatchCarrierModal" tabindex="-1" aria-labelledby="dispatchCarrierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="modal-title fw-bold text-body-emphasis" id="dispatchCarrierModalLabel">
          <i class="fa-solid fa-truck-fast text-primary me-2"></i> Bàn Giao Hàng Cho Bưu Tá
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
        @csrf
        <input type="hidden" name="shipping_status" value="shipping">
        <div class="modal-body py-3">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold text-body-emphasis">Chọn Đơn Vị Vận Chuyển Đối Tác:</label>
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
            <label class="form-label fs-9 fw-semibold text-body-emphasis">Mã Vận Đơn Bưu Tá (Tracking Code):</label>
            <div class="input-group">
              <input type="text" name="tracking_code" id="trackingCodeInput" class="form-control font-monospace fw-bold text-primary" value="{{ $order->tracking_code ?: 'GHTK-' . strtoupper(\Illuminate\Support\Str::random(8)) }}" required>
              <button type="button" class="btn btn-phoenix-secondary btn-sm" onclick="generateRandomTracking()">
                <i class="fa-solid fa-arrows-rotate"></i> Tạo Mới
              </button>
            </div>
            <small class="text-body-tertiary fs-10">Mã này sẽ hiển thị trực tiếp trên trang Tra Cứu Đơn Hàng của khách.</small>
          </div>

          <div class="alert alert-info py-2 px-3 rounded fs-10 mb-0">
            <i class="fa-solid fa-circle-info me-1"></i> Sau khi xác nhận, đơn hàng sẽ chuyển sang <strong>"Bước 4: Đang Giao Hàng"</strong>.
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary btn-sm px-4">
            <i class="fa-solid fa-paper-plane me-1"></i> Bàn Giao Vận Chuyển
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL XÁC NHẬN GIAO HÀNG & TẢI ẢNH POD (BƯỚC 5) -->
<div class="modal fade" id="adminPodDeliveryModal" tabindex="-1" aria-labelledby="adminPodDeliveryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
    <div class="modal-content border-0 shadow-2xl rounded-4">
      <div class="modal-header border-0 pb-0 pt-4 px-4">
        <div>
          <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="adminPodDeliveryModalLabel">
            <i class="fa-solid fa-camera-retro text-success fs-4"></i> Xác Nhận Giao Hàng &amp; Lưu Ảnh POD
          </h5>
          <p class="text-muted small mb-0">Đơn hàng #{{ $order->order_code }} • Khách: <strong>{{ $order->customer_name }}</strong></p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="shipping_status" value="delivered">
        @if($order->payment_method === 'cod')
          <input type="hidden" name="payment_status" value="paid">
        @endif

        <div class="modal-body py-3 px-4">
          <div class="alert alert-success py-2.5 px-3 rounded-3 small mb-3 border-0" style="background: #ecfdf5;">
            <i class="fa-solid fa-circle-check text-success me-1"></i>
            Theo chuẩn vận hành TMĐT, bưu tá sau khi giao hàng sẽ chụp ảnh kiện hàng gửi về hệ thống kho để lưu bằng chứng giao nhận (Proof of Delivery).
          </div>

          <!-- Tải ảnh từ máy -->
          <div class="mb-3">
            <label class="form-label small fw-bold text-dark">
              <i class="fa-solid fa-cloud-arrow-up me-1 text-primary"></i> 1. Tải ảnh bưu tá chụp gói hàng từ thiết bị:
            </label>
            <input type="file" name="delivery_proof_file" class="form-control form-control-sm" accept="image/*" id="showPodFileInput" onchange="previewShowPodImage(this)">
            <small class="text-muted" style="font-size: 0.74rem;">Chấp nhận file: JPG, PNG, WEBP (tối đa 10MB)</small>
          </div>

          <!-- Mẫu ảnh nhanh -->
          <div class="mb-3">
            <label class="form-label small fw-bold text-dark d-flex justify-content-between">
              <span><i class="fa-solid fa-wand-magic-sparkles text-warning me-1"></i> 2. Hoặc chọn nhanh ảnh chụp mẫu:</span>
              <span class="text-muted fw-normal" style="font-size: 0.75rem;">(Bưu tá GHTK/GHN)</span>
            </label>
            <div class="row g-2">
              <div class="col-6">
                <div class="border rounded-3 p-2 text-center position-relative cursor-pointer hover-shadow bg-light sample-card" onclick="selectShowSamplePod('assets/img/delivery-proofs/sample_pod_1.jpg', this)">
                  <img src="{{ asset('assets/img/delivery-proofs/sample_pod_1.jpg') }}" alt="Mẫu Kiện Hàng" class="img-fluid rounded mb-1 border" style="height: 70px; object-fit: cover; width: 100%;">
                  <div class="fw-bold text-dark small" style="font-size: 0.75rem;">Kiện hàng tại địa chỉ</div>
                  <small class="text-success" style="font-size: 0.68rem;"><i class="fa-solid fa-check"></i> Đã dán tem bưu tá</small>
                </div>
              </div>
              <div class="col-6">
                <div class="border rounded-3 p-2 text-center position-relative cursor-pointer hover-shadow bg-light sample-card" onclick="selectShowSamplePod('assets/img/delivery-proofs/sample_pod_2.jpg', this)">
                  <img src="{{ asset('assets/img/delivery-proofs/sample_pod_2.jpg') }}" alt="Mẫu Biên Bản Ký" class="img-fluid rounded mb-1 border" style="height: 70px; object-fit: cover; width: 100%;">
                  <div class="fw-bold text-dark small" style="font-size: 0.75rem;">Biên bản ký nhận</div>
                  <small class="text-primary" style="font-size: 0.68rem;"><i class="fa-solid fa-signature"></i> Đầy đủ chữ ký khách</small>
                </div>
              </div>
            </div>
            <input type="hidden" name="delivery_proof_image" id="showPodImageSampleInput" value="{{ $order->delivery_proof_image ?: '' }}">
          </div>

          <!-- Preview box -->
          <div class="mb-3" id="showPodPreviewBox" style="display: {{ $order->delivery_proof_url ? 'block' : 'none' }};">
            <label class="form-label small fw-bold text-dark">Ảnh xác thực sẽ lưu vào kho:</label>
            <div class="border rounded-3 p-2 bg-dark text-center position-relative">
              <img id="showPodPreviewImg" src="{{ $order->delivery_proof_url ?: '' }}" alt="Xem trước ảnh POD" class="img-fluid rounded" style="max-height: 180px; object-fit: contain;">
              <span class="badge bg-success position-absolute top-0 start-0 m-2 font-monospace">
                <i class="fa-solid fa-shield-check me-1"></i> POD VERIFIED
              </span>
            </div>
          </div>

          <!-- Ghi chú bưu tá -->
          <div class="mb-2">
            <label class="form-label small fw-bold text-dark">
              <i class="fa-solid fa-comment-dots text-secondary me-1"></i> 3. Ghi chú của bưu tá giao hàng:
            </label>
            <textarea name="delivery_proof_note" class="form-control form-control-sm" rows="2" placeholder="VD: Khách hàng đã kiểm tra kiện hàng còn nguyên niêm phong, thanh toán đủ và ký nhận.">{{ $order->delivery_proof_note ?: 'Khách hàng ' . $order->customer_name . ' đã nhận đủ bưu phẩm, kiện hàng nguyên vẹn tem niêm phong và thanh toán COD thành công.' }}</textarea>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0 pb-4 px-4">
          <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Hủy Bỏ</button>
          <button type="submit" class="btn btn-success fw-bold btn-sm px-4 rounded-pill shadow-xs">
            <i class="fa-solid fa-circle-check me-1"></i> Xác Nhận Giao &amp; Lưu Bằng Chứng
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL PHÓNG TO ẢNH POD (HD LIGHTBOX) -->
<div class="modal fade" id="viewPodDetailModal" tabindex="-1" aria-labelledby="viewPodDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
      <div class="modal-header border-0 pb-0 pt-3 px-4 bg-dark text-white">
        <div>
          <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="viewPodDetailModalLabel">
            <i class="fa-solid fa-camera-retro text-success"></i> Bằng Chứng Giao Hàng Bưu Tá Gửi Về Kho (POD)
          </h5>
          <span class="badge bg-success text-white font-monospace mt-1">
            <i class="fa-solid fa-shield-halved me-1"></i> HỆ THỐNG LƯU TRỮ KHO BEESTYLE
          </span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-0 bg-black text-center position-relative">
        <img src="{{ $order->delivery_proof_url }}" alt="Bằng chứng giao hàng bưu tá #{{ $order->order_code }}" class="img-fluid w-100" style="max-height: 520px; object-fit: contain;">
      </div>

      <div class="modal-footer border-0 p-3 bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-start small">
          <div class="text-dark"><strong>Đơn hàng:</strong> #{{ $order->order_code }} • <strong>Vận đơn:</strong> <span class="font-monospace text-primary fw-bold">{{ $order->tracking_code ?: 'N/A' }}</span></div>
          <div class="text-muted"><strong>Mốc giờ:</strong> {{ $order->delivery_proof_at ? $order->delivery_proof_at->format('d/m/Y H:i:s') : ($order->delivered_at ? $order->delivered_at->format('d/m/Y H:i:s') : 'N/A') }} • <strong>Ghi chú:</strong> {{ $order->delivery_proof_note ?: 'Khách đã nhận kiện hàng nguyên vẹn.' }}</div>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ $order->delivery_proof_url }}" target="_blank" download="POD_{{ $order->order_code }}.jpg" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">
            <i class="fa-solid fa-download me-1"></i> Tải Ảnh Về
          </a>
          <button type="button" class="btn btn-dark btn-sm rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Đóng</button>
        </div>
      </div>
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
    const input = document.getElementById('trackingCodeInput');
    if (input) {
      input.value = prefix + '-' + randomStr;
    }
  }

  function generateRandomTracking() {
    const carrierEl = document.getElementById('carrierSelect');
    if (carrierEl) {
      generateTrackingCode(carrierEl.value);
    }
  }

  function previewShowPodImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.getElementById('showPodPreviewImg');
        const box = document.getElementById('showPodPreviewBox');
        if (preview) preview.src = e.target.result;
        if (box) box.style.display = 'block';

        const sampleInput = document.getElementById('showPodImageSampleInput');
        if (sampleInput) sampleInput.value = '';
        document.querySelectorAll('.sample-card').forEach(el => el.classList.remove('border-success', 'bg-success-subtle'));
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function selectShowSamplePod(samplePath, element) {
    const sampleInput = document.getElementById('showPodImageSampleInput');
    const fileInput = document.getElementById('showPodFileInput');
    const preview = document.getElementById('showPodPreviewImg');
    const box = document.getElementById('showPodPreviewBox');

    if (sampleInput) sampleInput.value = samplePath;
    if (fileInput) fileInput.value = '';
    if (preview) preview.src = '{{ asset('') }}' + samplePath;
    if (box) box.style.display = 'block';

    document.querySelectorAll('.sample-card').forEach(el => el.classList.remove('border-success', 'bg-success-subtle'));
    if (element) {
      element.classList.add('border-success', 'bg-success-subtle');
    }
  }
</script>
@endpush
@endsection