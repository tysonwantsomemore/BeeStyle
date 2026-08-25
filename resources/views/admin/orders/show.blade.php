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
      1 => ['code' => 'pending', 'label' => '1. Chờ Xác Nhận', 'icon' => 'fa-clipboard-list', 'desc' => 'Đơn hàng mới tạo'],
      2 => ['code' => 'confirmed', 'label' => '2. Đã Xác Nhận', 'icon' => 'fa-clipboard-check', 'desc' => 'Đã duyệt thông tin'],
      3 => ['code' => 'processing', 'label' => '3. Đang Đóng Gói', 'icon' => 'fa-box-open', 'desc' => 'Kho nhặt hàng & gói'],
      4 => ['code' => 'shipping', 'label' => '4. Đang Giao Hàng', 'icon' => 'fa-truck-fast', 'desc' => 'Bưu tá vận chuyển'],
      5 => ['code' => 'delivered', 'label' => '5. Đã Giao Hàng', 'icon' => 'fa-handshake', 'desc' => 'Khách nhận & kiểm tra'],
      6 => ['code' => 'completed', 'label' => '6. Hoàn Tất', 'icon' => 'fa-circle-check', 'desc' => 'Thành công'],
    ];
    $currentStep = $order->shipping_status === 'cancelled' ? 0 : ($order->status_step ?? 1);
  @endphp

  @if($order->shipping_status === 'cancelled')
    <div class="alert alert-danger py-3 px-4 rounded-3 d-flex align-items-center gap-3 mb-0">
      <i class="fa-solid fa-ban fs-2 text-danger"></i>
      <div>
        <strong class="fs-6 d-block">ĐƠN HÀNG ĐÃ BỊ HỦY (CANCELLED)</strong>
        <span class="small text-danger text-opacity-80">Toàn bộ sản phẩm trong đơn hàng đã được hệ thống tự động hoàn trả lại số lượng tồn kho.</span>
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
            <span class="fw-bold text-truncate d-block" style="font-size: 0.76rem; color: {{ $isCurrent ? '#d97706' : ($isDone ? '#059669' : '#64748b') }};">
              {{ $sData['label'] }}
            </span>
            <small class="text-muted d-none d-md-block" style="font-size: 0.68rem;">{{ $sData['desc'] }}</small>
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

        <div class="mb-3">
          <label class="form-label small fw-semibold">Ghi chú nội bộ admin &amp; Mã vận đơn:</label>
          <input type="text" name="admin_notes" class="form-control" value="{{ $order->admin_notes }}" placeholder="VD: Mã vận đơn GHTK: S21894982 - Bưu tá đã lấy hàng lúc 14h30...">
        </div>

        <button type="submit" class="btn btn-warning text-dark fw-bold btn-sm px-4 shadow-xs">
          <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Cập Nhật Đơn Hàng
        </button>
      </form>
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
