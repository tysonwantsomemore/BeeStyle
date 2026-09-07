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

  <div class="card-body">
    @php
      $steps = [
        1 => ['code' => 'pending', 'label' => '1. Chờ Xác Nhận', 'icon' => 'fa-clipboard-list', 'desc' => 'Đơn hàng mới tạo'],
        2 => ['code' => 'confirmed', 'label' => '2. Đã Xác Nhận', 'icon' => 'fa-clipboard-check', 'desc' => 'Đã duyệt thông tin'],
        3 => ['code' => 'processing', 'label' => '3. Đang Đóng Gói', 'icon' => 'fa-box-open', 'desc' => 'Kho nhặt hàng & gói'],
        4 => ['code' => 'shipping', 'label' => '4. Đang Giao Hàng', 'icon' => 'fa-truck-fast', 'desc' => 'Bưu tá vận chuyển'],
        5 => ['code' => 'delivered', 'label' => '5. Đã Giao Hàng', 'icon' => 'fa-handshake', 'desc' => 'Khách nhận kiểm tra'],
        6 => ['code' => 'completed', 'label' => '6. Hoàn Tất', 'icon' => 'fa-circle-check', 'desc' => 'Thành công'],
      ];
      $currentStep = $order->shipping_status === 'cancelled' ? 0 : ($order->status_step ?? 1);
    @endphp

    @if($order->shipping_status === 'cancelled')
      <div class="alert alert-danger py-3 px-4 rounded d-flex align-items-center gap-3 mb-0">
        <i class="fa-solid fa-ban fs-2 text-danger"></i>
        <div>
          <strong class="fs-9 d-block">ĐƠN HÀNG ĐÃ BỊ HỦY (CANCELLED)</strong>
          <span class="fs-10 text-danger">Lý do hủy: <strong>{{ $order->cancel_reason ?: 'Không có ghi chú' }}</strong> • Người hủy: <strong>{{ $order->cancelled_by === 'customer' ? 'Khách hàng tự hủy' : ($order->cancelled_by === 'admin' ? 'Quản trị viên' : 'Hệ thống tự động') }}</strong></span>
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
              <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-check me-1"></i> Bước 2: Xác Nhận Đơn Hàng
              </button>
            </form>
          @elseif($order->shipping_status === 'confirmed')
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="shipping_status" value="processing">
              <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold">
                <i class="fa-solid fa-box-open me-1"></i> Bước 3: Cho Kho Đóng Gói
              </button>
            </form>
          @elseif($order->shipping_status === 'processing')
            <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#dispatchCarrierModal">
              <i class="fa-solid fa-truck-fast me-1"></i> Bước 4: Tạo Vận Đơn &amp; Giao Bưu Tá
            </button>
          @elseif($order->shipping_status === 'shipping')
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="shipping_status" value="delivered">
              <button type="submit" class="btn btn-sm btn-success">
                <i class="fa-solid fa-handshake me-1"></i> Bước 5: Báo Giao Thành Công
              </button>
            </form>
          @elseif($order->shipping_status === 'delivered')
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
              @csrf
              <input type="hidden" name="shipping_status" value="completed">
              <input type="hidden" name="payment_status" value="paid">
              <button type="submit" class="btn btn-sm btn-success">
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

          <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn HỦY đơn hàng #{{ $order->order_code }}? Toàn bộ sản phẩm sẽ được tự động hoàn lại vào kho hàng!')">
            @csrf
            <input type="hidden" name="shipping_status" value="cancelled">
            <button type="submit" class="btn btn-sm btn-phoenix-danger">
              <i class="fa-solid fa-xmark me-1"></i> Hủy Đơn
            </button>
          </form>
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
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <!-- UPDATE STATUS FORM -->
    <div class="card border-0 shadow-sm mb-4 d-print-none">
      <div class="card-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="fw-bold text-body-emphasis mb-0">
          <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Cập Nhật Trạng Thái &amp; Ghi Chú Đơn Hàng
        </h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
          @csrf
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fs-9 fw-semibold">Trạng thái vận chuyển:</label>
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
              <label class="form-label fs-9 fw-semibold">Trạng thái thanh toán:</label>
              <select name="payment_status" class="form-select">
                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Ghi chú nội bộ admin &amp; Mã vận đơn:</label>
            <input type="text" name="admin_notes" class="form-control" value="{{ $order->admin_notes }}" placeholder="VD: Mã vận đơn GHTK: S21894982 - Bưu tá đã lấy hàng lúc 14h30...">
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
            <small class="text-body-tertiary d-block fs-10">Đơn hàng được đặt mà không đăng nhập tài khoản.</small>
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

<!-- MODAL TẠO VẬN ĐƠN -->
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
              <input type="text" name="tracking_code" id="trackingCodeInput" class="form-control font-monospace fw-bold text-primary" value="GHTK-{{ strtoupper(Str::random(8)) }}" required>
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
