@extends('layouts.client')

@section('title', 'Tài Khoản & Cài Đặt Hồ Sơ | BeeStyle Menswear')

@section('content')
@php
  $addresses = $addresses ?? ($user->addresses ?? collect());
@endphp
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tài khoản cá nhân</li>
    </ol>
  </nav>



  @if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif


  <div class="row g-4">
    <!-- USER PROFILE SIDEBAR -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm p-4 text-center mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
        <div class="position-relative mx-auto mb-3" style="width: 100px; height: 100px;">
          <img id="sidebarAvatarPreview" class="rounded-circle border border-3 border-dark object-fit-cover w-100 h-100 shadow-sm" src="{{ asset($user->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $user->name }}">
          <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning text-dark p-2 border" title="Hội viên chính thức">
            <i class="fa-solid fa-crown fs-11"></i>
          </span>
        </div>

        <h5 class="fw-bold text-dark mb-1" style="font-family: var(--atino-font-heading);">{{ $user->name }}</h5>
        <p class="text-muted small mb-2">{{ $user->email }}</p>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
          <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2 rounded-pill">
            <i class="fa-solid fa-award me-1"></i> {{ $user->rank ?? 'Thành viên Mới' }}
          </span>
          <span class="badge bg-light text-dark fw-bold px-3 py-2 rounded-pill border">
            <i class="fa-solid fa-circle-check me-1 text-success"></i> Khách Hàng Thân Thiết
          </span>
        </div>

        <!-- Navigation Tabs List -->
        <div class="nav flex-column nav-pills text-start small border-top pt-3 gap-1" id="profileTabs" role="tablist">
          <button class="nav-link active fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="orders-tab" data-bs-toggle="pill" data-bs-target="#tab-orders" type="button" role="tab">
            <span><i class="fa-solid fa-box-archive me-2 text-danger"></i> Đơn Hàng Của Tôi</span>
            <span class="badge bg-dark text-white rounded-pill">{{ $orders->count() }}</span>
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="returns-tab" data-bs-toggle="pill" data-bs-target="#tab-returns" type="button" role="tab">
            <span><i class="fa-solid fa-arrow-rotate-left me-2 text-warning"></i> Đổi Trả &amp; Hoàn Tiền</span>
            <span class="badge {{ isset($returns) && $returns->where('status', 'pending')->count() > 0 ? 'bg-warning text-dark' : 'bg-secondary text-white' }} rounded-pill">{{ isset($returns) ? $returns->count() : 0 }}</span>
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="edit-profile-tab" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button" role="tab">
            <i class="fa-solid fa-user-pen me-2 text-secondary"></i> Thông Tin Cá Nhân
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="bank-tab" data-bs-toggle="pill" data-bs-target="#tab-bank" type="button" role="tab">
            <i class="fa-solid fa-building-columns me-2 text-secondary"></i> Tài Khoản Ngân Hàng
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="password-tab" data-bs-toggle="pill" data-bs-target="#tab-password" type="button" role="tab">
            <i class="fa-solid fa-shield-halved me-2 text-secondary"></i> Đổi Mật Khẩu Tài Khoản
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="addresses-tab" data-bs-toggle="pill" data-bs-target="#tab-addresses" type="button" role="tab">
            <i class="fa-solid fa-map-location-dot me-2 text-secondary"></i> Sổ Địa Chỉ ({{ isset($addresses) ? $addresses->count() : 0 }})
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="my-reviews-tab" data-bs-toggle="pill" data-bs-target="#tab-my-reviews" type="button" role="tab">
            <span><i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Của Tôi</span>
            <span class="badge bg-warning-subtle text-dark rounded-pill">{{ $user->reviews->count() }}</span>
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="rewards-tab" data-bs-toggle="pill" data-bs-target="#tab-vip" type="button" role="tab">
            <i class="fa-solid fa-heart me-2 text-danger"></i> Tri Ân Khách Hàng &amp; Đặc Quyền
          </button>


          <a href="{{ route('client.products.index') }}" class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center text-dark">
            <i class="fa-solid fa-store me-2 text-secondary"></i> Mua Sắm Sản Phẩm Mới
          </a>

          @if($user->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center text-danger">
              <i class="fa-solid fa-gauge-high me-2"></i> Bảng Quản Trị Hệ Thống (Admin)
            </a>
          @endif
        </div>

        <!-- Logout Form -->
        <form action="{{ route('auth.logout') }}" method="POST" class="mt-3 pt-3 border-top">
          @csrf
          <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 fw-semibold rounded-pill">
            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng Xuất Tài Khoản
          </button>
        </form>
      </div>
    </div>

    <!-- TAB CONTENTS -->
    <div class="col-lg-8">
      <div class="tab-content" id="profileTabsContent">
        
        <!-- TAB 1: ORDER HISTORY -->
        <div class="tab-pane fade show active" id="tab-orders" role="tabpanel">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="fw-bold text-dark mb-0 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-clock-rotate-left me-2 text-danger"></i> Lịch Sử Đơn Hàng ({{ $orders->count() }})
              </h5>
              <a href="{{ route('client.products.index') }}" class="small text-danger fw-bold text-decoration-none">
                Đặt thêm sản phẩm <i class="fa-solid fa-arrow-right ms-1"></i>
              </a>
            </div>

            <!-- TOP PENDING REVIEWS BANNER (Nổi bật nhắc nhở đánh giá đơn hoàn tất) -->
            @if(isset($pendingReviewItems) && $pendingReviewItems->count() > 0)
              <div class="alert alert-warning border-0 shadow-sm p-3 mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #fffbeb; border-left: 5px solid #f59e0b !important;">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                    <i class="fa-solid fa-gift fs-4"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold text-dark mb-1 small text-uppercase">Bạn có {{ $pendingReviewItems->count() }} sản phẩm từ đơn hàng hoàn tất chưa đánh giá!</h6>
                    <p class="mb-0 text-muted small">Cảm ơn bạn đã mua hàng! Hãy chia sẻ cảm nhận để giúp BeeStyle ngày càng hoàn thiện nhé.</p>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <a href="{{ route('client.products.show', $pendingReviewItems->first()->product_id) }}#reviews" class="btn btn-bee-primary btn-sm px-3 fw-bold text-nowrap">
                    <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá ngay
                  </a>
                </div>
              </div>
            @endif

            <div class="d-flex flex-column gap-3">
              @forelse($orders as $order)
                <div class="border rounded-3 p-3 bg-light-subtle">
                  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-2 border-bottom mb-3">
                    <div>
                      <span class="small text-muted">Mã đơn hàng:</span>
                      <strong class="text-dark font-monospace fs-9">{{ $order->order_code }}</strong>
                      <span class="text-muted small ms-2">({{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }})</span>
                    </div>
                    <div>
                      @if($order->shipping_status === 'completed')
                        <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hoàn tất</span>
                      @elseif($order->shipping_status === 'delivered')
                        <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-box-open me-1"></i> Đã giao hàng</span>
                      @elseif($order->shipping_status === 'shipping')
                        <span class="badge bg-warning-subtle text-dark fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> Đang giao hàng</span>
                      @elseif($order->shipping_status === 'processing')
                        <span class="badge bg-info-subtle text-info fw-bold"><i class="fa-solid fa-box me-1"></i> Đang đóng gói</span>
                      @elseif($order->shipping_status === 'cancelled')
                        <span class="badge bg-danger-subtle text-danger fw-bold"><i class="fa-solid fa-xmark me-1"></i> Đã hủy</span>
                      @else
                        <span class="badge bg-secondary-subtle text-dark fw-bold"><i class="fa-solid fa-hourglass-start me-1"></i> Chờ xác nhận</span>
                      @endif
                    </div>
                  </div>

                  <!-- Cancelled Order Info Banner -->
                  @if($order->shipping_status === 'cancelled')
                    <div class="alert alert-danger border-0 py-2 px-3 mb-3 rounded-2 small d-flex align-items-center gap-2" style="background: #fef2f2;">
                      <i class="fa-solid fa-ban text-danger fs-5"></i>
                      <div>
                        <strong class="text-danger">Đơn hàng đã hủy:</strong>
                        <span class="text-dark">{{ $order->cancel_reason ?: 'Hủy theo yêu cầu của khách hàng' }}</span>
                        <small class="text-muted d-block" style="font-size: 0.72rem;">Thời gian hủy: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i') : ($order->updated_at ? $order->updated_at->format('d/m/Y H:i') : '') }}</small>
                      </div>
                    </div>
                  @endif

                  <!-- Active RMA / Return Request Banner -->
                  @if($order->latestReturn)
                    <div class="alert alert-warning border-0 py-2 px-3 mb-3 rounded-2 small d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #fffbeb; border-left: 4px solid #f59e0b !important;">
                      <div>
                        <i class="fa-solid fa-arrow-rotate-left text-warning me-1"></i>
                        <strong>Yêu Cầu Đổi Trả #{{ $order->latestReturn->return_code }}:</strong> {{ $order->latestReturn->type_label }} - <em>{{ $order->latestReturn->reason }}</em>
                      </div>
                      <div class="d-flex align-items-center gap-2">
                        {!! $order->latestReturn->status_badge !!}
                        <button type="button" class="btn btn-sm btn-outline-dark py-0.5 px-2 fw-bold" style="font-size: 0.72rem;" onclick="document.getElementById('returns-tab').click()">
                          Xem Tiến Trình
                        </button>
                      </div>
                    </div>
                  @endif

                  <!-- Completed Order Prompt Banner -->
                  @if($order->shipping_status === 'delivered' || $order->shipping_status === 'completed' || $order->status_step >= 5)
                    <div class="alert alert-success border-0 py-2 px-3 mb-3 rounded-2 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #ecfdf5;">
                      <div class="small text-success">
                        <i class="fa-solid fa-circle-check me-1"></i> <strong>Đơn hàng đã hoàn tất!</strong> Cảm ơn bạn đã mua sắm tại BeeStyle. Hãy đánh giá các sản phẩm bên dưới.
                      </div>
                    </div>
                  @endif

                  <!-- Order Items -->
                  <div class="d-flex flex-column gap-2 mb-3">
                    @php
                      $userReviewedIds = $user->reviews->pluck('product_id')->toArray();
                    @endphp
                    @foreach($order->items as $item)
                      @php
                        $hasReviewedThisItem = in_array($item->product_id, $userReviewedIds);
                      @endphp
                      <div class="d-flex align-items-center justify-content-between p-2.5 bg-white rounded-3 border">
                        <div class="d-flex align-items-center gap-2.5">
                          <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 48px; height: 48px; object-fit: cover; cursor: pointer;" class="rounded border" onclick="openQuickReviewModal({{ $item->product_id ?? 1 }})">
                          <div>
                            <a href="javascript:void(0)" onclick="openQuickReviewModal({{ $item->product_id ?? 1 }})" class="small fw-semibold text-dark text-decoration-none d-block">
                              {{ $item->product_name }}
                            </a>
                            <small class="text-muted">Màu: {{ $item->color ?? 'Tiêu chuẩn' }} | Size: {{ $item->size ?? 'M' }} • SL: x{{ $item->quantity }}</small>
                          </div>
                        </div>
                        <div class="text-end">
                          <span class="small fw-bold text-dark d-block">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                          @if($order->shipping_status === 'delivered' || $order->shipping_status === 'completed' || $order->status_step >= 5)
                            @if($hasReviewedThisItem)
                              <button type="button" id="order-btn-review-{{ $item->product_id ?: ($item->product->id ?? 1) }}" onclick="openQuickReviewModal({{ $item->product_id ?: ($item->product->id ?? 1) }})" class="btn btn-sm btn-outline-success py-0.5 px-2 mt-1 fw-bold text-nowrap" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-circle-check me-1"></i> Xem / Sửa Đánh Giá
                              </button>
                            @else
                              <button type="button" id="order-btn-review-{{ $item->product_id ?: ($item->product->id ?? 1) }}" onclick="openQuickReviewModal({{ $item->product_id ?: ($item->product->id ?? 1) }})" class="btn btn-sm btn-bee-primary py-0.5 px-2.5 mt-1 fw-bold text-nowrap" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá ngay
                              </button>
                            @endif
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>

                  <!-- Order Footer -->
                  <div class="d-flex justify-content-between align-items-center pt-2 border-top flex-wrap gap-2">
                    <div>
                      <span class="small text-muted">Phương thức: <strong>{{ $order->payment_method_name }}</strong></span>
                      <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : ($order->payment_status === 'refunded' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-dark') }} ms-1">
                        {{ $order->payment_status_label }}
                      </span>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                      <div>
                        <span class="small text-muted">Tổng tiền: </span>
                        <strong class="text-danger fs-6">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                      </div>

                      @if($order->payment_status !== 'paid' && $order->payment_status !== 'refunded' && $order->shipping_status !== 'cancelled' && in_array($order->payment_method, ['online', 'momo', 'zalopay', 'vietqr', 'vnpay']))
                        @php
                          $gatewayRoute = match($order->payment_method) {
                            'momo' => route('client.checkout.momo', $order->order_code),
                            'zalopay' => route('client.checkout.zalopay', $order->order_code),
                            'online', 'vietqr', 'vnpay' => route('client.checkout.online', $order->order_code),
                            default => route('client.order-tracking', ['code' => $order->order_code]),
                          };
                        @endphp
                        <a href="{{ $gatewayRoute }}" class="btn btn-sm btn-bee-primary fw-bold px-3 shadow-xs">
                          <i class="fa-solid fa-credit-card me-1"></i> Mở Cổng Thanh Toán
                        </a>
                      @endif

                      @if($order->canBeCancelledByCustomer())
                        <button type="button" class="btn btn-sm btn-outline-danger fw-bold px-2.5" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->id }}">
                          <i class="fa-solid fa-xmark me-1"></i> Hủy Đơn
                        </button>
                      @endif

                      @if($order->canBeReturnedByCustomer())
                        <button type="button" class="btn btn-sm btn-bee-outline fw-bold px-2.5" data-bs-toggle="modal" data-bs-target="#returnOrderModal{{ $order->id }}">
                          <i class="fa-solid fa-arrow-rotate-left me-1"></i> Đổi Trả / Hoàn Tiền
                        </button>
                      @endif

                      <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="btn btn-sm btn-light border text-dark">
                        <i class="fa-solid fa-truck-fast me-1 text-secondary"></i> Tra Cứu
                      </a>
                    </div>
                  </div>
                </div>

                <!-- MODAL HỦY ĐƠN HÀNG DÀNH CHO KHÁCH HÀNG -->
                @if($order->canBeCancelledByCustomer())
                  <div class="modal fade" id="cancelOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-0 shadow-lg" style="border-radius: 18px;">
                        <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST">
                          @csrf
                          <div class="modal-header border-bottom">
                            <h5 class="modal-title fw-bold text-danger">
                              <i class="fa-solid fa-triangle-exclamation me-2"></i> Hủy Đơn Hàng #{{ $order->order_code }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body p-4">
                            <div class="alert alert-warning border-0 p-3 rounded-3 small mb-3" style="background: #fffbeb;">
                              <i class="fa-solid fa-circle-info text-warning me-1"></i>
                              Khi bạn xác nhận hủy đơn, hệ thống sẽ tự động khôi phục số lượng tồn kho sản phẩm và hoàn lại lượt sử dụng mã giảm giá (voucher) cho bạn.
                            </div>

                            <div class="mb-3">
                              <label class="form-label small fw-bold text-dark">Lý do hủy đơn hàng <span class="text-danger">*</span></label>
                              <select name="reason" class="form-select" required>
                                <option value="" selected disabled>-- Chọn lý do hủy đơn --</option>
                                <option value="Tôi muốn thay đổi địa chỉ giao hàng">Tôi muốn thay đổi địa chỉ giao hàng</option>
                                <option value="Tôi muốn thay đổi kích cỡ (Size) hoặc màu sắc áo">Tôi muốn thay đổi kích cỡ (Size) hoặc màu sắc áo</option>
                                <option value="Tôi muốn thêm/bớt sản phẩm trong đơn">Tôi muốn thêm/bớt sản phẩm trong đơn</option>
                                <option value="Tôi tìm thấy giá tốt hơn ở nơi khác">Tôi tìm thấy giá tốt hơn ở nơi khác</option>
                                <option value="Tôi đổi ý, không có nhu cầu mua nữa">Tôi đổi ý, không có nhu cầu mua nữa</option>
                                <option value="Lý do khác">Lý do khác</option>
                              </select>
                            </div>

                            <div class="mb-3">
                              <label class="form-label small fw-bold text-dark">Ghi chú thêm (không bắt buộc)</label>
                              <textarea name="notes" class="form-control" rows="2" placeholder="Nhập thêm chi tiết nếu cần..."></textarea>
                            </div>
                          </div>
                          <div class="modal-footer border-top bg-light">
                            <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm">
                              Xác Nhận Hủy Đơn
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endif

                <!-- MODAL YÊU CẦU ĐỔI TRẢ & HOÀN TIỀN (RMA) -->
                @if($order->canBeReturnedByCustomer())
                  <div class="modal fade" id="returnOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                      <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px;">
                        <form action="{{ route('client.orders.return.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="modal-header border-bottom p-4">
                            <div>
                              <h5 class="modal-title fw-bold text-dark mb-1">
                                <i class="fa-solid fa-arrow-rotate-left text-warning me-2"></i> Yêu Cầu Đổi Trả / Hoàn Tiền (RMA)
                              </h5>
                              <p class="text-muted small mb-0">Đơn hàng: <strong class="text-dark font-monospace">#{{ $order->order_code }}</strong> (Tổng: {{ number_format($order->total_amount, 0, ',', '.') }}₫)</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>

                          <div class="modal-body p-4">
                            <!-- CHỌN HÌNH THỨC XỬ LÝ -->
                            <div class="mb-4">
                              <label class="form-label small fw-bold text-dark">Hình thức mong muốn <span class="text-danger">*</span></label>
                              <div class="row g-2">
                                <div class="col-md-4">
                                  <label class="p-3 border rounded-3 d-flex align-items-center gap-2 cursor-pointer w-100 h-100 bg-light" style="cursor: pointer;">
                                    <input type="radio" name="type" value="return_refund" class="form-check-input mt-0" checked onchange="toggleRmaFields(this.value, {{ $order->id }})">
                                    <div class="small">
                                      <strong class="d-block text-dark">Trả Hàng &amp; Hoàn Tiền</strong>
                                      <span class="text-muted" style="font-size: 0.72rem;">Gửi hàng về kho nhận lại 100% tiền</span>
                                    </div>
                                  </label>
                                </div>
                                <div class="col-md-4">
                                  <label class="p-3 border rounded-3 d-flex align-items-center gap-2 cursor-pointer w-100 h-100 bg-light" style="cursor: pointer;">
                                    <input type="radio" name="type" value="exchange" class="form-check-input mt-0" onchange="toggleRmaFields(this.value, {{ $order->id }})">
                                    <div class="small">
                                      <strong class="d-block text-dark">Đổi Size / Đổi Màu</strong>
                                      <span class="text-muted" style="font-size: 0.72rem;">Đổi sang size áo vừa vặn hơn</span>
                                    </div>
                                  </label>
                                </div>
                                <div class="col-md-4">
                                  <label class="p-3 border rounded-3 d-flex align-items-center gap-2 cursor-pointer w-100 h-100 bg-light" style="cursor: pointer;">
                                    <input type="radio" name="type" value="refund_only" class="form-check-input mt-0" onchange="toggleRmaFields(this.value, {{ $order->id }})">
                                    <div class="small">
                                      <strong class="d-block text-dark">Chỉ Hoàn Tiền</strong>
                                      <span class="text-muted" style="font-size: 0.72rem;">Hàng lỗi hỏng nặng không cần trả</span>
                                    </div>
                                  </label>
                                </div>
                              </div>
                            </div>

                            <!-- CHỌN SẢN PHẨM MUỐN TRẢ -->
                            <div class="mb-3">
                              <label class="form-label small fw-bold text-dark">Sản phẩm áp dụng đổi/trả</label>
                              <select name="order_item_id" class="form-select">
                                <option value="">Toàn bộ đơn hàng ({{ $order->items->count() }} sản phẩm)</option>
                                @foreach($order->items as $oItem)
                                  <option value="{{ $oItem->id }}">{{ $oItem->product_name }} ({{ $oItem->color ?? 'Chuẩn' }} / Size {{ $oItem->size ?? 'M' }}) - {{ number_format($oItem->subtotal ?: ($oItem->price * $oItem->quantity), 0, ',', '.') }}₫</option>
                                @endforeach
                              </select>
                            </div>

                            <!-- LÝ DO ĐỔI TRẢ -->
                            <div class="mb-3">
                              <label class="form-label small fw-bold text-dark">Lý do đổi trả <span class="text-danger">*</span></label>
                              <select name="reason" class="form-select" required>
                                <option value="" selected disabled>-- Chọn lý do cụ thể --</option>
                                <option value="Sản phẩm bị lỗi vải, rách hoặc bung chỉ từ xưởng">Sản phẩm bị lỗi vải, rách hoặc bung chỉ từ xưởng</option>
                                <option value="Giao sai mẫu, sai màu hoặc sai kích thước (Size)">Giao sai mẫu, sai màu hoặc sai kích thước (Size)</option>
                                <option value="Mặc không vừa kích cỡ (Yêu cầu đổi sang Size khác)">Mặc không vừa kích cỡ (Yêu cầu đổi sang Size khác)</option>
                                <option value="Sản phẩm không đúng với hình ảnh và mô tả trên web">Sản phẩm không đúng với hình ảnh và mô tả trên web</option>
                                <option value="Sản phẩm bị hư hại trong quá trình vận chuyển">Sản phẩm bị hư hại trong quá trình vận chuyển</option>
                                <option value="Lý do khác">Lý do khác</option>
                              </select>
                            </div>

                            <!-- TRƯỜNG HỢP ĐỔI SIZE / MÀU -->
                            <div class="row g-2 mb-3 d-none" id="exchangeFields{{ $order->id }}">
                              <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Size mong muốn đổi</label>
                                <select name="exchange_size" class="form-select">
                                  <option value="S">Size S (48 - 56kg)</option>
                                  <option value="M" selected>Size M (57 - 65kg)</option>
                                  <option value="L">Size L (66 - 73kg)</option>
                                  <option value="XL">Size XL (74 - 82kg)</option>
                                  <option value="2XL">Size 2XL (83 - 92kg)</option>
                                </select>
                              </div>
                              <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark">Màu sắc mong muốn</label>
                                <input type="text" name="exchange_color" class="form-control" placeholder="Ví dụ: Đen Obsidian, Trắng Basic, Xanh Navy...">
                              </div>
                            </div>

                            <!-- THÔNG TIN TÀI KHOẢN NGÂN HÀNG HOÀN TIỀN -->
                            <div id="refundBankFields{{ $order->id }}" class="p-3 bg-light rounded-3 border mb-3">
                              <h6 class="fw-bold text-dark small mb-2"><i class="fa-solid fa-building-columns text-warning me-1"></i> Thông Tin Nhận Tiền Hoàn Trả</h6>
                              <div class="row g-2">
                                <div class="col-md-4">
                                  <label class="form-label small text-muted">Tên Ngân Hàng</label>
                                  <input type="text" name="bank_name" value="{{ $user->bank_name ?? '' }}" class="form-control form-control-sm" placeholder="VD: Vietcombank, MB Bank, Techcombank...">
                                </div>
                                <div class="col-md-4">
                                  <label class="form-label small text-muted">Số Tài Khoản</label>
                                  <input type="text" name="bank_account_number" value="{{ $user->bank_account_number ?? '' }}" class="form-control form-control-sm font-monospace" placeholder="Nhập số tài khoản...">
                                </div>
                                <div class="col-md-4">
                                  <label class="form-label small text-muted">Tên Chủ Tài Khoản</label>
                                  <input type="text" name="bank_account_name" value="{{ $user->bank_account_name ?? $user->name }}" class="form-control form-control-sm text-uppercase" placeholder="NGUYEN VAN A">
                                </div>
                              </div>
                            </div>

                            <!-- TẢI LÊN ẢNH MINH CHỨNG -->
                            <div class="mb-3">
                              <label class="form-label small fw-bold text-dark">Ảnh chụp cận cảnh tem mác và lỗi sản phẩm (Tối đa 4 ảnh)</label>
                              <input type="file" name="image_proofs[]" multiple accept="image/*" class="form-control">
                              <small class="text-muted" style="font-size: 0.72rem;">Hỗ trợ: jpg, png, webp. Dung lượng tối đa 4MB/ảnh.</small>
                            </div>

                            <!-- MÔ TẢ CHI TIẾT -->
                            <div class="mb-0">
                              <label class="form-label small fw-bold text-dark">Mô tả thêm tình trạng sản phẩm</label>
                              <textarea name="customer_notes" class="form-control" rows="2.5" placeholder="Mô tả cụ thể vị trí lỗi hoặc yêu cầu thêm của bạn..."></textarea>
                            </div>
                          </div>

                          <div class="modal-footer border-top bg-light p-3">
                            <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Hủy Bỏ</button>
                            <button type="submit" class="btn btn-bee-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                              <i class="fa-solid fa-paper-plane me-1"></i> Gửi Yêu Cầu Đổi Trả
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endif

              @empty
                <div class="text-center py-5">
                  <i class="fa-solid fa-bag-shopping fs-1 text-muted mb-2"></i>
                  <h6 class="fw-bold text-dark">Bạn chưa có đơn hàng nào tại BeeStyle</h6>
                  <p class="text-muted small mb-3">Hãy khám phá bộ sưu tập áo polo nam, sơ mi và blazer mới nhất!</p>
                  <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary btn-sm px-4">
                    Khám Phá Cửa Hàng Ngay
                  </a>
                </div>
              @endforelse
            </div>
          </div>
        </div>

        <!-- TAB 2: EDIT PROFILE -->
        <div class="tab-pane fade" id="tab-profile" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="mb-4 pb-2 border-bottom">
              <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-user-pen me-2 text-danger"></i> Cập Nhật Hồ Sơ Cá Nhân
              </h5>
              <p class="text-muted small mb-0">Quản lý thông tin tài khoản và địa chỉ nhận hàng mặc định</p>
            </div>

            <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <!-- Avatar Upload with Live Preview -->
              <div class="mb-4">
                <label class="form-label small fw-semibold text-dark">Ảnh đại diện (Avatar)</label>
                <div class="d-flex align-items-center gap-3">
                  <img id="avatarPreview" src="{{ asset($user->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="Avatar" class="rounded-circle border object-fit-cover" style="width: 70px; height: 70px;">
                  <div>
                    <input type="file" name="avatar" id="avatarInput" class="form-control form-control-sm" accept="image/*" onchange="var reader = new FileReader(); reader.onload = function(e){ document.getElementById('avatarPreview').src = e.target.result; document.getElementById('sidebarAvatarPreview').src = e.target.result; }; reader.readAsDataURL(this.files[0]);">
                    <small class="text-muted fs-11">Định dạng: JPG, PNG, WEBP. Tối đa 3MB.</small>
                  </div>
                </div>
              </div>

              <!-- Name & Phone -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Họ và tên <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                  <input type="tel" name="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}" required>
                </div>
              </div>

              <!-- Email, Gender & Date of Birth -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label small fw-semibold text-dark">Giới tính</label>
                  <select name="gender" class="form-select form-select-sm">
                    <option value="Nam" {{ old('gender', $user->gender) === 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('gender', $user->gender) === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ old('gender', $user->gender) === 'Khác' ? 'selected' : '' }}>Khác</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label small fw-semibold text-dark">Ngày sinh</label>
                  <input type="date" name="dob" class="form-control form-control-sm" value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '') }}">
                </div>
              </div>

              <!-- Address, City, District -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Tỉnh / Thành phố</label>
                  <input type="text" name="city" class="form-control form-control-sm" value="{{ old('city', $user->city ?? 'Hà Nội') }}" placeholder="Ví dụ: Hà Nội, TP. Hồ Chí Minh...">
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Quận / Huyện</label>
                  <input type="text" name="district" class="form-control form-control-sm" value="{{ old('district', $user->district ?? '') }}" placeholder="Ví dụ: Cầu Giấy, Quận 1...">
                </div>

                <div class="col-12">
                  <label class="form-label small fw-semibold text-dark">Địa chỉ giao hàng chi tiết (Số nhà, tên đường, phường)</label>
                  <input type="text" name="address" class="form-control form-control-sm" value="{{ old('address', $user->address) }}" placeholder="Ví dụ: Số 18 Phố Huế, Hoàn Kiếm">
                </div>
              </div>

              <!-- Submit -->
              <div class="mt-4 pt-2 border-top text-end">
                <button type="submit" class="btn btn-bee-primary px-4 py-2">
                  <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thay Đổi Hồ Sơ
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- TAB: BANK ACCOUNT / HOÀN TIỀN -->
        <div class="tab-pane fade" id="tab-bank" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="mb-4 pb-2 border-bottom">
              <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-building-columns me-2 text-danger"></i> Tài Khoản Ngân Hàng Nhận Hoàn Tiền
              </h5>
              <p class="text-muted small mb-0">Cung cấp thông tin tài khoản ngân hàng chính chủ để nhận tiền hoàn nhanh chóng khi đổi trả hàng</p>
            </div>

            <!-- Visual Bank Card Mockup -->
            <div class="p-4 rounded-4 text-white mb-4 position-relative overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); max-width: 480px; border: 1px solid rgba(255,255,255,0.12);">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-microchip text-warning fs-3"></i>
                  <i class="fa-solid fa-wifi text-white-50 fs-5" style="transform: rotate(90deg);"></i>
                </div>
                <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 text-uppercase font-monospace" style="letter-spacing: 1px;">
                  {{ $user->bank_name ?: 'BEESTYLE REWARD' }}
                </span>
              </div>
              <div class="mb-4">
                <small class="text-white-50 text-uppercase d-block" style="font-size: 0.7rem; letter-spacing: 1.5px;">Số tài khoản</small>
                <h4 class="fw-bold text-white font-monospace mb-0" style="letter-spacing: 2px;">
                  @if($user->bank_account_number)
                    {{ chunk_split($user->bank_account_number, 4, ' ') }}
                  @else
                    •••• •••• •••• ••••
                  @endif
                </h4>
              </div>
              <div class="d-flex justify-content-between align-items-end">
                <div>
                  <small class="text-white-50 text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Chủ tài khoản</small>
                  <span class="fw-bold text-white text-uppercase font-monospace" style="letter-spacing: 1px; font-size: 0.95rem;">
                    {{ $user->bank_account_name ?: ($user->name ?: 'CHƯA CẬP NHẬT') }}
                  </span>
                </div>
                @if($user->bank_branch)
                  <div class="text-end">
                    <small class="text-white-50 text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 1px;">Chi nhánh</small>
                    <span class="text-white-50 small font-monospace">{{ $user->bank_branch }}</span>
                  </div>
                @endif
              </div>
            </div>

            <form action="{{ route('client.profile.bank') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Ngân hàng thụ hưởng <span class="text-danger">*</span></label>
                  <select name="bank_name" class="form-select form-select-sm" required>
                    <option value="" disabled {{ empty($user->bank_name) ? 'selected' : '' }}>-- Chọn ngân hàng --</option>
                    @php
                      $banks = [
                        'Vietcombank' => 'Vietcombank (Ngoại Thương Việt Nam)',
                        'Techcombank' => 'Techcombank (Kỹ Thương Việt Nam)',
                        'MB Bank' => 'MB Bank (Quân Đội)',
                        'VietinBank' => 'VietinBank (Công Thương Việt Nam)',
                        'BIDV' => 'BIDV (Đầu Tư và Phát Triển)',
                        'ACB' => 'ACB (Á Châu)',
                        'VPBank' => 'VPBank (Việt Nam Thịnh Vượng)',
                        'TPBank' => 'TPBank (Tiên Phong)',
                        'Sacombank' => 'Sacombank (Sài Gòn Thương Tín)',
                        'HDBank' => 'HDBank (Phát Triển TP.HCM)',
                        'VIB' => 'VIB (Quốc Tế Việt Nam)',
                        'MSB' => 'MSB (Hàng Hải)',
                        'OCB' => 'OCB (Phương Đông)',
                        'Agribank' => 'Agribank (Nông Nghiệp & PTNT)',
                        'SeABank' => 'SeABank (Đông Nam Á)',
                        'LPBank' => 'LPBank (Lộc Phát Việt Nam)',
                        'SHB' => 'SHB (Sài Gòn - Hà Nội)',
                      ];
                    @endphp
                    @foreach($banks as $bKey => $bLabel)
                      <option value="{{ $bKey }}" {{ old('bank_name', $user->bank_name) === $bKey ? 'selected' : '' }}>{{ $bLabel }}</option>
                    @endforeach
                    @if($user->bank_name && !array_key_exists($user->bank_name, $banks))
                      <option value="{{ $user->bank_name }}" selected>{{ $user->bank_name }}</option>
                    @endif
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Số tài khoản ngân hàng <span class="text-danger">*</span></label>
                  <input type="text" name="bank_account_number" class="form-control form-control-sm font-monospace" value="{{ old('bank_account_number', $user->bank_account_number) }}" placeholder="Ví dụ: 0071001234567" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Tên chủ tài khoản (Viết hoa không dấu) <span class="text-danger">*</span></label>
                  <input type="text" name="bank_account_name" class="form-control form-control-sm text-uppercase font-monospace" value="{{ old('bank_account_name', $user->bank_account_name) }}" placeholder="Ví dụ: NGUYEN VAN A" required>
                  <small class="text-muted fs-11">Tên chủ tài khoản cần khớp chính xác với thẻ/CCCD để đảm bảo nhận tiền thành công.</small>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Chi nhánh mở thẻ</label>
                  <input type="text" name="bank_branch" class="form-control form-control-sm" value="{{ old('bank_branch', $user->bank_branch) }}" placeholder="Ví dụ: Chi nhánh Hà Nội, Chi nhánh Cầu Giấy...">
                </div>
              </div>

              <div class="p-3 bg-light rounded-3 mb-4">
                <h6 class="small fw-bold text-dark mb-1"><i class="fa-solid fa-shield-halved text-success me-1"></i> Chính sách bảo mật thông tin tài khoản:</h6>
                <ul class="text-muted small ps-3 mb-0">
                  <li>Thông tin tài khoản ngân hàng của bạn được bảo mật tuyệt đối theo tiêu chuẩn an toàn thanh toán.</li>
                  <li>BeeStyle chỉ sử dụng thông tin này để xử lý hoàn tiền tự động khi quý khách có đơn hàng hoàn trả hoặc hủy hợp lệ.</li>
                </ul>
              </div>

              <div class="pt-2 border-top text-end">
                <button type="submit" class="btn btn-bee-primary px-4 py-2">
                  <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thông Tin Ngân Hàng
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- TAB 3: CHANGE PASSWORD -->
        <div class="tab-pane fade" id="tab-password" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="mb-4 pb-2 border-bottom">
              <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-shield-halved me-2 text-danger"></i> Đổi Mật Khẩu An Toàn
              </h5>
              <p class="text-muted small mb-0">Bảo vệ tài khoản BeeStyle bằng mật khẩu mạnh và bảo mật</p>
            </div>

            <form action="{{ route('client.profile.password') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-3" style="max-width: 480px;">
                <label class="form-label small fw-semibold text-dark">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="current_password" id="curPass" class="form-control form-control-sm" placeholder="Nhập mật khẩu hiện tại..." required>
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var p=document.getElementById('curPass'); p.type=(p.type==='password'?'text':'password');">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="mb-3" style="max-width: 480px;">
                <label class="form-label small fw-semibold text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password" id="newPass" class="form-control form-control-sm" placeholder="Tối thiểu 6 ký tự..." required>
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var p=document.getElementById('newPass'); p.type=(p.type==='password'?'text':'password');">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="mb-4" style="max-width: 480px;">
                <label class="form-label small fw-semibold text-dark">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password_confirmation" id="confirmPass" class="form-control form-control-sm" placeholder="Nhập lại mật khẩu mới..." required>
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var p=document.getElementById('confirmPass'); p.type=(p.type==='password'?'text':'password');">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="p-3 bg-light rounded-3 mb-4" style="max-width: 480px;">
                <h6 class="small fw-bold text-dark mb-1"><i class="fa-solid fa-circle-info text-warning me-1"></i> Lưu ý bảo mật:</h6>
                <ul class="text-muted small ps-3 mb-0">
                  <li>Mật khẩu nên chứa ít nhất 6 ký tự.</li>
                  <li>Không chia sẻ mật khẩu tài khoản cho người khác.</li>
                </ul>
              </div>

              <div class="pt-2 border-top">
                <button type="submit" class="btn btn-bee-primary px-4 py-2">
                  <i class="fa-solid fa-lock me-1"></i> Cập Nhật Mật Khẩu Mới
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- TAB 4: ADDRESS BOOK -->
        <div class="tab-pane fade" id="tab-addresses" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom flex-wrap gap-2">
              <div>
                <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                  <i class="fa-solid fa-map-location-dot me-2 text-danger"></i> Sổ Địa Chỉ Nhận Hàng ({{ $addresses->count() }})
                </h5>
                <p class="text-muted small mb-0">Quản lý các địa chỉ giao hàng để thanh toán nhanh hơn khi mua sắm</p>
              </div>
              <button type="button" class="btn btn-bee-primary btn-sm px-3" data-bs-toggle="collapse" data-bs-target="#addAddressBox">
                <i class="fa-solid fa-plus me-1"></i> Thêm Địa Chỉ Mới
              </button>
            </div>

            <!-- Collapse Add Address Box -->
            <div class="collapse mb-4" id="addAddressBox">
              <div class="card card-body bg-light border p-4 rounded-3">
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-location-crosshairs me-1 text-danger"></i> Thêm địa chỉ giao hàng mới:</h6>
                <form action="{{ route('client.profile.address.store') }}" method="POST">
                  @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label small fw-semibold text-dark">Tên người nhận <span class="text-danger">*</span></label>
                      <input type="text" name="recipient_name" class="form-control form-control-sm" value="{{ old('recipient_name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-semibold text-dark">Số điện thoại nhận hàng <span class="text-danger">*</span></label>
                      <input type="tel" name="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}" required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-semibold text-dark">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                      <input type="text" name="city" class="form-control form-control-sm" placeholder="Hà Nội, TP. HCM..." required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-semibold text-dark">Quận / Huyện <span class="text-danger">*</span></label>
                      <input type="text" name="district" class="form-control form-control-sm" placeholder="Cầu Giấy, Quận 1..." required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-semibold text-dark">Phường / Xã</label>
                      <input type="text" name="ward" class="form-control form-control-sm" placeholder="Phường Dịch Vọng...">
                    </div>
                    <div class="col-md-8">
                      <label class="form-label small fw-semibold text-dark">Địa chỉ chi tiết (Số nhà, ngõ, đường) <span class="text-danger">*</span></label>
                      <input type="text" name="address" class="form-control form-control-sm" placeholder="Số 18 Phố Huế..." required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-semibold text-dark">Loại địa chỉ</label>
                      <select name="label" class="form-select form-select-sm">
                        <option value="Nhà riêng">Nhà riêng</option>
                        <option value="Văn phòng">Văn phòng / Công ty</option>
                        <option value="Khác">Khác</option>
                      </select>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefaultCheck">
                        <label class="form-check-label small text-dark cursor-pointer" for="isDefaultCheck">
                          Đặt làm địa chỉ nhận hàng mặc định
                        </label>
                      </div>
                    </div>
                    <div class="col-12 text-end mt-3">
                      <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="collapse" data-bs-target="#addAddressBox">Hủy</button>
                      <button type="submit" class="btn btn-bee-primary btn-sm px-3"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu Địa Chỉ</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Current Addresses List -->
            <div class="d-flex flex-column gap-3">
              @forelse($addresses as $addr)
                <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                      <strong class="text-dark">{{ $addr->recipient_name }}</strong>
                      <span class="text-muted small">|</span>
                      <span class="text-muted small">{{ $addr->phone }}</span>
                      <span class="badge bg-secondary-subtle text-dark small">{{ $addr->label ?? 'Địa chỉ' }}</span>
                      @if($addr->is_default)
                        <span class="badge bg-danger-subtle text-danger small fw-bold"><i class="fa-solid fa-check me-1"></i> Mặc định</span>
                      @endif
                    </div>
                    <p class="text-secondary small mb-0">{{ $addr->address }}{{ $addr->ward ? ', ' . $addr->ward : '' }}{{ $addr->district ? ', ' . $addr->district : '' }}{{ $addr->city ? ', ' . $addr->city : '' }}</p>
                    @if($addr->notes)
                      <small class="text-muted fst-italic">Ghi chú: {{ $addr->notes }}</small>
                    @endif
                  </div>
                  <div>
                    <form action="{{ route('client.profile.address.delete', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2.5">
                        <i class="fa-regular fa-trash-can me-1"></i> Xóa
                      </button>
                    </form>
                  </div>
                </div>
              @empty
                <div class="p-3 bg-light rounded-3 border">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <strong class="text-dark">{{ $user->name }}</strong>
                    <span class="text-muted small">|</span>
                    <span class="text-muted small">{{ $user->phone ?? 'Chưa cập nhật SĐT' }}</span>
                    <span class="badge bg-danger-subtle text-danger small">Hồ sơ chính</span>
                  </div>
                </div>
              @endforelse
            </div>
          </div>
        </div>

        <!-- TAB 5: LỜI TRI ÂN & ĐẶC QUYỀN KHÁCH HÀNG -->
        <div class="tab-pane fade" id="tab-vip" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="mb-4 pb-2 border-bottom">
              <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-heart me-2 text-danger"></i> Tri Ân Khách Hàng &amp; Đặc Quyền Phục Vụ
              </h5>
              <p class="text-muted small mb-0">Lời cảm ơn chân thành và cam kết chất lượng dịch vụ từ BeeStyle</p>
            </div>

            <!-- Customer Appreciation Status Card -->
            <div class="p-4 rounded-3 text-white mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a, #1e293b); border: 1px solid rgba(245, 158, 11, 0.3);">
              <div class="row align-items-center">
                <div class="col-md-8">
                  <span class="badge bg-warning text-dark fw-bold px-3 py-1 text-uppercase mb-2">HỘI VIÊN THÂN THIẾT</span>
                  <h3 class="fw-bold text-white mb-1" style="font-family: var(--atino-font-heading);">{{ $user->name }}</h3>
                  <p class="text-white-50 small mb-0">Hạng tài khoản: <strong class="text-warning">{{ $user->rank ?? 'Thành viên Bạc (Silver)' }}</strong> • Tổng tích lũy mua sắm: <strong class="text-warning">{{ number_format($user->total_spent ?? 0, 0, ',', '.') }}₫</strong></p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                  <span class="badge bg-danger px-3 py-2 fs-6 fw-bold rounded-pill shadow-xs">
                    <i class="fa-solid fa-shield-heart me-1"></i> Khách Hàng Ưu Tiên
                  </span>
                </div>
              </div>
            </div>

            <!-- Thank You Letter from BeeStyle -->
            <div class="p-4 bg-light rounded-3 border mb-4">
              <div class="d-flex align-items-center gap-2 mb-2">
                <i class="fa-solid fa-envelope-open-text text-warning fs-4"></i>
                <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-family: var(--atino-font-heading);">
                  Thư Tri Ân Gửi Đến Quý Khách Hàng
                </h6>
              </div>
              <p class="text-secondary small leading-relaxed mb-2" style="font-size: 0.92rem; line-height: 1.65;">
                Kính gửi Quý khách <strong>{{ $user->name }}</strong>,
              </p>
              <p class="text-secondary small leading-relaxed mb-2" style="font-size: 0.92rem; line-height: 1.65;">
                BeeStyle xin gửi lời cảm ơn chân thành và sâu sắc nhất vì Quý khách đã luôn tin tưởng, lựa chọn các sản phẩm thời trang nam của chúng tôi trong suốt thời gian qua. Sự đồng hành và ủng hộ của Quý khách chính là niềm tự hào to lớn, là động lực để đội ngũ BeeStyle không ngừng nâng tầm chất lượng từ từng đường kim mũi chỉ đến dịch vụ chăm sóc khách hàng tận tâm nhất.
              </p>
              <p class="text-secondary small leading-relaxed mb-0" style="font-size: 0.92rem; line-height: 1.65;">
                Kính chúc Quý khách luôn lịch lãm, tự tin, gặt hái được nhiều thành công trong cuộc sống và luôn có những trải nghiệm mua sắm tuyệt vời tại BeeStyle!
              </p>
            </div>

            <!-- 4 Service Commitments -->
            <h6 class="fw-bold text-dark mb-3">Đặc quyền chăm sóc dành riêng cho bạn:</h6>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100 d-flex align-items-start gap-3">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-truck-fast text-warning fs-6"></i>
                  </div>
                  <div>
                    <strong class="text-dark d-block small">Ưu Tiên Giao Hàng Siêu Tốc</strong>
                    <small class="text-muted">Đơn hàng của hội viên luôn được xử lý đóng gói và vận chuyển ưu tiên hàng đầu.</small>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100 d-flex align-items-start gap-3">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-rotate-left text-warning fs-6"></i>
                  </div>
                  <div>
                    <strong class="text-dark d-block small">Đổi Size Tận Nơi Miễn Phí</strong>
                    <small class="text-muted">Hỗ trợ đổi size tận nhà trong vòng 30 ngày hoàn toàn không phát sinh thêm chi phí.</small>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100 d-flex align-items-start gap-3">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-shield-halved text-warning fs-6"></i>
                  </div>
                  <div>
                    <strong class="text-dark d-block small">Bảo Hành Đường May 1 Năm</strong>
                    <small class="text-muted">Cam kết chất lượng chuẩn may đo xuất khẩu, hỗ trợ bảo hành trọn vẹn 365 ngày.</small>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border h-100 d-flex align-items-start gap-3">
                  <div class="rounded-circle bg-warning-subtle text-dark d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-headset text-warning fs-6"></i>
                  </div>
                  <div>
                    <strong class="text-dark d-block small">Hỗ Trợ &amp; Chăm Sóc Riêng 24/7</strong>
                    <small class="text-muted">Đội ngũ stylist BeeStyle sẵn sàng tư vấn phối đồ và hỗ trợ bất cứ khi nào bạn cần.</small>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- TAB 6: MY REVIEWS (ĐÁNH GIÁ & NHẬN XÉT CỦA TÔI) -->
        <div class="tab-pane fade" id="tab-my-reviews" role="tabpanel">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <div>
                <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                  <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Của Tôi ({{ $user->reviews->count() }})
                </h5>
                <p class="text-muted small mb-0">Xem lại và chỉnh sửa các nhận xét sản phẩm bạn đã từng gửi</p>
              </div>
              <span class="badge bg-light text-dark fw-semibold border"><i class="fa-solid fa-heart text-danger me-1"></i> Đóng góp ý kiến quý báu</span>
            </div>

            <div class="d-flex flex-column gap-3">
              @forelse($user->reviews as $rev)
                <div class="p-3 bg-light rounded-3 border transition-all hover-lift" id="profile-rev-card-{{ $rev->product_id }}">
                  <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                      <img src="{{ asset($rev->product->image ?? '/assets/img/products/1.png') }}" alt="{{ $rev->product->name ?? '' }}" style="width: 48px; height: 48px; object-fit: cover; cursor: pointer;" class="rounded border bg-white" onclick="openQuickReviewModal({{ $rev->product_id }})">
                      <div>
                        <strong class="text-dark small d-block" style="cursor: pointer;" onclick="openQuickReviewModal({{ $rev->product_id }})">{{ $rev->product->name ?? 'Sản phẩm' }}</strong>
                        <small class="text-muted">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}</small>
                      </div>
                    </div>
                    <div class="text-end">
                      <div class="text-warning small mb-1" id="profile-rev-stars-{{ $rev->product_id }}">
                        @for($i=1; $i<=5; $i++)
                          <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                        @endfor
                        <span class="fw-bold text-dark ms-1">({{ $rev->rating }}/5)</span>
                      </div>
                      <button type="button" onclick="openQuickReviewModal({{ $rev->product_id }})" class="btn btn-sm btn-outline-dark py-0.5 px-2 fw-bold" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-pen me-1"></i> Xem / Sửa Đánh Giá
                      </button>
                    </div>
                  </div>
                  <p class="small text-dark mb-0 fst-italic leading-relaxed p-2 bg-white rounded-2 border" id="profile-rev-comment-{{ $rev->product_id }}">
                    "{{ $rev->comment }}"
                  </p>

                  <!-- Photos in Profile Reviews Tab -->
                  <div class="d-flex gap-2 flex-wrap mt-2 pt-2 border-top {{ empty($rev->images_urls) ? 'd-none' : '' }}" id="profile-rev-photos-{{ $rev->product_id }}">
                    @if(!empty($rev->images_urls))
                      @foreach($rev->images_urls as $photoUrl)
                        <div class="position-relative" style="cursor: pointer;" onclick="openReviewImageLightbox('{{ $photoUrl }}')">
                          <img src="{{ $photoUrl }}" alt="Ảnh đánh giá" class="rounded border shadow-xs" style="width: 54px; height: 54px; object-fit: cover;">
                          <span class="position-absolute bottom-0 end-0 bg-dark text-white px-1 py-0.5 rounded-start" style="font-size: 0.6rem; opacity: 0.85;">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                          </span>
                        </div>
                      @endforeach
                    @endif
                  </div>
                </div>
              @empty
                <div class="text-center py-5">
                  <i class="fa-regular fa-comment-dots fs-1 text-muted mb-2"></i>
                  <h6 class="fw-bold text-dark">Bạn chưa viết đánh giá nào</h6>
                  <p class="text-muted small mb-3">Sau khi nhận hàng thành công, hãy chia sẻ cảm nhận để giúp BeeStyle ngày một hoàn thiện hơn nhé!</p>
                  <a href="#tab-orders" data-bs-toggle="pill" data-bs-target="#tab-orders" class="btn btn-bee-primary btn-sm px-4">
                    Xem Đơn Hàng Của Bạn
                  </a>
                </div>
              @endforelse
            </div>
          </div>
        </div>

        <!-- TAB 7: RETURNS & REFUNDS (ĐỔI TRẢ & HOÀN TIỀN CỦA TÔI) -->
        <div class="tab-pane fade" id="tab-returns" role="tabpanel">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <div>
                <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                  <i class="fa-solid fa-arrow-rotate-left me-2 text-warning"></i> Quản Lý Đổi Trả &amp; Hoàn Tiền ({{ isset($returns) ? $returns->count() : 0 }})
                </h5>
                <p class="text-muted small mb-0">Theo dõi tiến trình thẩm định và kết quả xử lý các yêu cầu RMA của bạn</p>
              </div>
              <button type="button" onclick="document.getElementById('orders-tab').click()" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3">
                <i class="fa-solid fa-plus me-1"></i> Tạo Yêu Cầu Đổi Trả Mới
              </button>
            </div>

            <div class="d-flex flex-column gap-3">
              @forelse($returns ?? [] as $ret)
                <div class="p-3 bg-light rounded-3 border">
                  <!-- Header Phiếu RMA -->
                  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-2 border-bottom mb-3">
                    <div>
                      <span class="small text-muted">Mã phiếu RMA:</span>
                      <strong class="text-primary font-monospace fs-9">#{{ $ret->return_code }}</strong>
                      <span class="text-muted small ms-2">({{ $ret->created_at ? $ret->created_at->format('d/m/Y H:i') : '' }})</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge bg-dark text-white">{{ $ret->type_label }}</span>
                      {!! $ret->status_badge !!}
                    </div>
                  </div>

                  <!-- Thông tin đơn hàng & Lý do -->
                  <div class="row g-3 small mb-3">
                    <div class="col-md-6">
                      <div class="text-muted mb-1">Đơn hàng liên quan:</div>
                      @if($ret->order)
                        <a href="{{ route('client.order-tracking', ['code' => $ret->order->order_code]) }}" class="fw-bold font-monospace text-dark text-decoration-none">
                          <i class="fa-solid fa-box me-1 text-warning"></i> #{{ $ret->order->order_code }}
                        </a>
                      @endif
                      <div class="mt-2">
                        <span class="text-muted">Lý do đổi trả:</span>
                        <strong class="text-danger d-block">{{ $ret->reason }}</strong>
                      </div>
                      @if($ret->customer_notes)
                        <div class="mt-1 text-muted">
                          <em>"{{ $ret->customer_notes }}"</em>
                        </div>
                      @endif
                    </div>

                    <div class="col-md-6">
                      @if($ret->type === 'exchange')
                        <div class="p-2.5 bg-white rounded-2 border">
                          <span class="text-muted d-block mb-1">Kích cỡ yêu cầu đổi:</span>
                          <span class="badge bg-warning text-dark fw-bold fs-7">Size {{ $ret->exchange_size ?? 'M' }}</span>
                          @if($ret->exchange_color)
                            <span class="badge bg-light text-dark border ms-1">Màu: {{ $ret->exchange_color }}</span>
                          @endif
                        </div>
                      @else
                        <div class="p-2.5 bg-white rounded-2 border">
                          <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Số tiền hoàn trả:</span>
                            <strong class="text-danger fs-6 font-monospace">{{ number_format($ret->refund_amount, 0, ',', '.') }}₫</strong>
                          </div>
                          <div class="small text-muted">
                            <i class="fa-solid fa-building-columns me-1 text-secondary"></i> {{ $ret->bank_name ?? 'Ngân hàng' }} • <span class="font-monospace text-dark">{{ $ret->bank_account_number ?? '' }}</span>
                          </div>
                        </div>
                      @endif

                      @if(!empty($ret->image_proofs) && is_array($ret->image_proofs))
                        <div class="d-flex gap-1.5 mt-2 flex-wrap">
                          @foreach($ret->image_proofs as $pImg)
                            <a href="{{ asset($pImg) }}" target="_blank">
                              <img src="{{ asset($pImg) }}" alt="Bằng chứng" class="rounded border" style="width: 44px; height: 44px; object-fit: cover;">
                            </a>
                          @endforeach
                        </div>
                      @endif
                    </div>
                  </div>

                  <!-- 4-STEP TIMELINE TRACKER TRỰC QUAN -->
                  @php
                    $rmaSteps = [
                      1 => '1. Đã gửi yêu cầu',
                      2 => '2. CSKH đã duyệt',
                      3 => '3. Kho đã nhận hàng',
                      4 => '4. Hoàn tất & Hoàn tiền'
                    ];
                    $stepMap = ['pending' => 1, 'approved' => 2, 'received' => 3, 'completed' => 4, 'rejected' => 0];
                    $currentRmaStep = $stepMap[$ret->status] ?? 1;
                  @endphp

                  @if($ret->status === 'rejected')
                    <div class="alert alert-danger py-2 px-3 rounded-2 small mb-0 d-flex align-items-center gap-2">
                      <i class="fa-solid fa-circle-xmark text-danger fs-5"></i>
                      <div>
                        <strong>Yêu cầu bị từ chối:</strong> {{ $ret->rejected_reason ?: 'Sản phẩm không đáp ứng đủ điều kiện đổi trả của BeeStyle.' }}
                      </div>
                    </div>
                  @else
                    <div class="bee-timeline-steps my-3 p-3 bg-white rounded-3 border" style="transform: scale(0.95); transform-origin: center;">
                      @foreach($rmaSteps as $sNum => $sLbl)
                        <div class="bee-timeline-step {{ $currentRmaStep > $sNum ? 'completed' : ($currentRmaStep == $sNum ? 'active' : '') }}">
                          <div class="bee-timeline-step-icon">
                            @if($currentRmaStep > $sNum)
                              <i class="fa-solid fa-check"></i>
                            @else
                              {{ $sNum }}
                            @endif
                          </div>
                          <div class="bee-timeline-step-label">{{ $sLbl }}</div>
                        </div>
                      @endforeach
                    </div>
                  @endif

                  @if($ret->admin_notes)
                    <div class="mt-2 p-2 bg-info-subtle text-info rounded-2 small d-flex align-items-center gap-2">
                      <i class="fa-solid fa-message text-primary"></i>
                      <span class="text-dark"><strong>CSKH BeeStyle phản hồi:</strong> {{ $ret->admin_notes }}</span>
                    </div>
                  @endif
                </div>
              @empty
                <div class="text-center py-5">
                  <i class="fa-solid fa-rotate-left fs-1 text-muted mb-2"></i>
                  <h6 class="fw-bold text-dark">Bạn chưa có yêu cầu đổi trả nào</h6>
                  <p class="text-muted small mb-3">BeeStyle hỗ trợ đổi size và hoàn tiền trong vòng 7 ngày cho mọi đơn hàng.</p>
                  <button type="button" onclick="document.getElementById('orders-tab').click()" class="btn btn-bee-primary btn-sm px-4">
                    Xem Đơn Hàng Của Bạn
                  </button>
                </div>
              @endforelse
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- QUICK REVIEW MODAL CHO KHÁCH HÀNG TỰ ĐÁNH GIÁ SẢN PHẨM -->
<div class="modal fade" id="quickReviewModal" tabindex="-1" aria-labelledby="quickReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <!-- Modal Header -->
      <div class="modal-header border-0 text-white p-3.5" style="background: linear-gradient(135deg, #111827 0%, #1e293b 100%);">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-star text-warning fs-5"></i>
          <div>
            <h6 class="modal-title fw-bold text-white mb-0" id="quickReviewModalLabel">Đánh Giá Sản Phẩm Của Bạn</h6>
            <small class="text-white-50" style="font-size: 0.75rem;">Chia sẻ cảm nhận thực tế sau khi nhận hàng để giúp cộng đồng mua sắm</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="quickReviewForm" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="qrProductId" name="product_id" value="">
        <div class="modal-body p-4 bg-light">
          <!-- Alert Box -->
          <div id="quickReviewAlert" class="alert d-none py-2 px-3 mb-3 rounded-3 small"></div>

          <!-- Product Card Preview -->
          <div class="p-3 bg-white rounded-3 border d-flex align-items-center gap-3 mb-3.5 shadow-xs">
            <img id="qrProdImg" src="" alt="product" class="rounded border shadow-xs" style="width: 58px; height: 58px; object-fit: cover;">
            <div class="flex-grow-1 min-w-0">
              <h6 class="fw-bold text-dark mb-1 text-truncate small" id="qrProdName">Tên sản phẩm</h6>
              <div class="text-danger fw-bold small" id="qrProdPrice">0₫</div>
            </div>
          </div>

          <!-- Star Rating Interactive Selector -->
          <div class="p-3 bg-white rounded-3 border mb-3.5 text-center shadow-xs">
            <label class="form-label small fw-bold text-dark text-uppercase mb-1" style="font-size: 0.78rem;">
              Chất lượng sản phẩm &amp; mức độ hài lòng:
            </label>
            <div class="d-flex justify-content-center gap-2 my-2" id="qrStarsContainer">
              @for($i = 1; $i <= 5; $i++)
                <i class="fa-solid fa-star fs-3 cursor-pointer qr-star-item text-warning" data-rating="{{ $i }}" style="cursor: pointer; transition: transform 0.15s, color 0.15s;" onmouseover="hoverQrStars({{ $i }})" onmouseout="resetQrStars()" onclick="selectQrRating({{ $i }})"></i>
              @endfor
            </div>
            <input type="hidden" name="rating" id="qrRatingInput" value="5">
            <div class="small fw-bold text-warning" id="qrRatingLabel">Tuyệt vời (5/5 sao)</div>
          </div>

          <!-- Review Comment Textarea -->
          <div class="mb-3.5">
            <label class="form-label small fw-bold text-dark text-uppercase mb-1" style="font-size: 0.78rem;">
              <i class="fa-solid fa-pen-nib text-warning me-1"></i> Nhận xét chi tiết của bạn: <span class="text-danger">*</span>
            </label>
            <textarea name="comment" id="qrCommentInput" class="form-control rounded-3" rows="3" placeholder="Chia sẻ cảm nhận về chất vải, form dáng, đường may, độ co giãn khi mặc..." required minlength="4" maxlength="1000" style="font-size: 0.88rem; resize: none;"></textarea>
          </div>

          <!-- Image Upload Input -->
          <div>
            <label class="form-label small fw-bold text-dark text-uppercase mb-1" style="font-size: 0.78rem;">
              <i class="fa-solid fa-camera text-warning me-1"></i> Tải ảnh chụp thực tế (tối đa 5 ảnh):
            </label>
            <input type="file" name="review_images[]" id="qrImagesInput" class="form-control form-control-sm rounded-3" multiple accept="image/*" onchange="previewQrImages(this)">
            <div id="qrImagesPreview" class="d-flex gap-2 flex-wrap mt-2"></div>
          </div>
        </div>

        <div class="modal-footer border-0 p-3 bg-white justify-content-between">
          <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Hủy bỏ</button>
          <button type="submit" id="qrSubmitBtn" class="btn btn-bee-primary btn-sm px-4 fw-bold rounded-pill">
            <i class="fa-solid fa-paper-plane me-1"></i> GỬI ĐÁNH GIÁ CỦA TÔI
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  let currentQrRating = 5;
  const qrRatingLabels = {
    1: 'Rất tệ (1/5 sao)',
    2: 'Không hài lòng (2/5 sao)',
    3: 'Bình thường (3/5 sao)',
    4: 'Hài lòng (4/5 sao)',
    5: 'Tuyệt vời (5/5 sao)'
  };

  function selectQrRating(rating) {
    currentQrRating = rating;
    document.getElementById('qrRatingInput').value = rating;
    document.getElementById('qrRatingLabel').textContent = qrRatingLabels[rating] || (rating + '/5 sao');
    updateQrStarsDisplay(rating);
  }

  function hoverQrStars(rating) {
    updateQrStarsDisplay(rating);
    document.getElementById('qrRatingLabel').textContent = qrRatingLabels[rating] || (rating + '/5 sao');
  }

  function resetQrStars() {
    updateQrStarsDisplay(currentQrRating);
    document.getElementById('qrRatingLabel').textContent = qrRatingLabels[currentQrRating] || (currentQrRating + '/5 sao');
  }

  function updateQrStarsDisplay(rating) {
    document.querySelectorAll('.qr-star-item').forEach(star => {
      const starRating = parseInt(star.getAttribute('data-rating'));
      if (starRating <= rating) {
        star.classList.remove('text-secondary-subtle');
        star.classList.add('text-warning');
      } else {
        star.classList.remove('text-warning');
        star.classList.add('text-secondary-subtle');
      }
    });
  }

  function previewQrImages(input) {
    const previewContainer = document.getElementById('qrImagesPreview');
    previewContainer.innerHTML = '';
    if (input.files && input.files.length > 0) {
      Array.from(input.files).slice(0, 5).forEach(file => {
        const reader = new FileReader();
        reader.onload = function (e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'rounded border shadow-xs';
          img.style.width = '52px';
          img.style.height = '52px';
          img.style.objectFit = 'cover';
          previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    }
  }

  function openQuickReviewModal(productId) {
    const modalEl = document.getElementById('quickReviewModal');
    const alertBox = document.getElementById('quickReviewAlert');
    if (alertBox) alertBox.classList.add('d-none');

    // Fetch product review data
    fetch(`/san-pham/${productId}/danh-gia-chi-tiet`)
      .then(res => res.json())
      .then(data => {
        if (data.success && data.product) {
          document.getElementById('qrProductId').value = data.product.id;
          document.getElementById('qrProdName').textContent = data.product.name;
          document.getElementById('qrProdPrice').textContent = data.product.price;
          document.getElementById('qrProdImg').src = data.product.image;

          // If user already reviewed, prefill
          if (data.user_review) {
            selectQrRating(data.user_review.rating || 5);
            document.getElementById('qrCommentInput').value = data.user_review.comment || '';
            document.getElementById('quickReviewModalLabel').textContent = 'Cập Nhật Đánh Giá Của Bạn';
            document.getElementById('qrSubmitBtn').innerHTML = '<i class="fa-solid fa-check me-1"></i> CẬP NHẬT ĐÁNH GIÁ';
          } else {
            selectQrRating(5);
            document.getElementById('qrCommentInput').value = '';
            document.getElementById('quickReviewModalLabel').textContent = 'Đánh Giá Sản Phẩm Của Bạn';
            document.getElementById('qrSubmitBtn').innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> GỬI ĐÁNH GIÁ CỦA TÔI';
          }

          document.getElementById('qrImagesPreview').innerHTML = '';
          document.getElementById('qrImagesInput').value = '';

          const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
          modal.show();
        } else {
          window.location.href = `/san-pham/${productId}#reviews`;
        }
      })
      .catch(err => {
        window.location.href = `/san-pham/${productId}#reviews`;
      });
  }

  function toggleRmaFields(type, orderId) {
    const exchangeFields = document.getElementById('exchangeFields' + orderId);
    const refundBankFields = document.getElementById('refundBankFields' + orderId);
    if (type === 'exchange') {
      if (exchangeFields) exchangeFields.classList.remove('d-none');
      if (refundBankFields) refundBankFields.classList.add('d-none');
    } else {
      if (exchangeFields) exchangeFields.classList.add('d-none');
      if (refundBankFields) refundBankFields.classList.remove('d-none');
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    // Review Form AJAX Submit Handler
    const reviewForm = document.getElementById('quickReviewForm');
    if (reviewForm) {
      reviewForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const pId = document.getElementById('qrProductId').value;
        if (!pId) return;

        const submitBtn = document.getElementById('qrSubmitBtn');
        const alertBox = document.getElementById('quickReviewAlert');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang gửi đánh giá...';

        const formData = new FormData(this);

        fetch(`/san-pham/${pId}/danh-gia`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> GỬI ĐÁNH GIÁ CỦA TÔI';

          if (data.success) {
            if (alertBox) {
              alertBox.className = 'alert alert-success border-0 py-2.5 px-3 rounded-3 mb-3 small';
              alertBox.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> ${data.message || 'Cảm ơn bạn đã gửi đánh giá thành công!'}`;
              alertBox.classList.remove('d-none');
            }

            // Update button on order list
            const btnEl = document.getElementById('order-btn-review-' + pId);
            if (btnEl) {
              btnEl.className = 'btn btn-sm btn-outline-success py-0.5 px-2 mt-1 fw-bold text-nowrap';
              btnEl.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Xem / Sửa Đánh Giá';
            }

            setTimeout(() => {
              const modalEl = document.getElementById('quickReviewModal');
              if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
              }
            }, 1200);
          } else {
            if (alertBox) {
              alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
              alertBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> ${data.message || 'Có lỗi xảy ra khi gửi đánh giá.'}`;
              alertBox.classList.remove('d-none');
            }
          }
        })
        .catch(err => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> GỬI ĐÁNH GIÁ CỦA TÔI';
          if (alertBox) {
            alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
            alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> Có lỗi xảy ra khi kết nối máy chủ.';
            alertBox.classList.remove('d-none');
          }
        });
      });
    }

    // 1. Kiểm tra query param ?tab= hoặc URL hash #
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    const hash = window.location.hash;

    const tabMap = {
      'orders': 'orders-tab',
      'returns': 'returns-tab',
      'profile': 'edit-profile-tab',
      'bank': 'bank-tab',
      'password': 'password-tab',
      'addresses': 'addresses-tab',
      'vip': 'rewards-tab',
      'reviews': 'my-reviews-tab',
      '#tab-orders': 'orders-tab',
      '#tab-returns': 'returns-tab',
      '#tab-profile': 'edit-profile-tab',
      '#tab-bank': 'bank-tab',
      '#tab-password': 'password-tab',
      '#tab-addresses': 'addresses-tab',
      '#tab-vip': 'rewards-tab',
      '#tab-my-reviews': 'my-reviews-tab'
    };

    let targetTabId = null;
    if (tabParam && tabMap[tabParam]) {
      targetTabId = tabMap[tabParam];
    } else if (hash && tabMap[hash]) {
      targetTabId = tabMap[hash];
    }

    // 2. Nếu có lỗi validation của form thì tự động mở tab tương ứng
    @if(isset($errors) && ($errors->has('current_password') || $errors->has('password')))
      targetTabId = 'password-tab';
    @elseif(isset($errors) && ($errors->has('bank_name') || $errors->has('bank_account_number') || $errors->has('bank_account_name') || $errors->has('bank_branch')))
      targetTabId = 'bank-tab';
    @elseif(isset($errors) && ($errors->has('recipient_name') || $errors->has('address') || $errors->has('city')))
      targetTabId = 'addresses-tab';
    @endif

    if (targetTabId) {
      const tabButton = document.getElementById(targetTabId);
      if (tabButton) {
        const tabTrigger = new bootstrap.Tab(tabButton);
        tabTrigger.show();
      }
    }
  });
</script>
@endpush

@endsection

