@extends('layouts.client')

@section('title', 'Tài Khoản & Cài Đặt Hồ Sơ | BeeStyle Menswear')

@section('content')
@php
  $addresses = $addresses ?? ($user->addresses ?? collect());
  
  $totalSpent = $orders->where('shipping_status', 'completed')->sum('total_amount');
  if ($totalSpent <= 0) {
    $totalSpent = $orders->where('payment_status', 'paid')->sum('total_amount');
  }
  
  if ($totalSpent >= 10000000) {
    $tierName = 'VIP Kim Cương (Diamond)';
    $tierBadgeClass = 'bg-dark text-warning border border-warning';
    $tierIcon = 'fa-gem';
    $nextTierName = 'Hạng Cao Nhất';
    $nextTierTarget = 10000000;
    $progressPercent = 100;
    $neededMore = 0;
  } elseif ($totalSpent >= 5000000) {
    $tierName = 'VIP Vàng (Gold)';
    $tierBadgeClass = 'bg-warning text-dark';
    $tierIcon = 'fa-crown';
    $nextTierName = 'VIP Kim Cương';
    $nextTierTarget = 10000000;
    $progressPercent = min(100, round(($totalSpent / 10000000) * 100));
    $neededMore = 10000000 - $totalSpent;
  } elseif ($totalSpent >= 2000000) {
    $tierName = 'Hội Viên Bạc (Silver)';
    $tierBadgeClass = 'bg-secondary text-white';
    $tierIcon = 'fa-medal';
    $nextTierName = 'VIP Vàng';
    $nextTierTarget = 5000000;
    $progressPercent = min(100, round(($totalSpent / 5000000) * 100));
    $neededMore = 5000000 - $totalSpent;
  } else {
    $tierName = 'Thành Viên Đồng (Bronze)';
    $tierBadgeClass = 'bg-light text-dark border';
    $tierIcon = 'fa-award';
    $nextTierName = 'Hội Viên Bạc';
    $nextTierTarget = 2000000;
    $progressPercent = min(100, round(($totalSpent / 2000000) * 100));
    $neededMore = 2000000 - $totalSpent;
  }
@endphp
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tài khoản cá nhân</li>
    </ol>
  </nav>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <i class="fa-solid fa-circle-info me-2"></i> {{ session('info') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

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
          <span class="badge {{ $tierBadgeClass }} fw-bold px-3 py-2 rounded-pill shadow-xs">
            <i class="fa-solid {{ $tierIcon }} me-1"></i> {{ $tierName }}
          </span>
        </div>

        <!-- Mini Stats Summary -->
        <div class="row g-2 p-2.5 rounded-3 bg-light border mb-3 text-center">
          <div class="col-4 border-end">
            <small class="text-muted d-block" style="font-size: 0.72rem;">Đơn Hàng</small>
            <strong class="text-dark fs-6">{{ $orders->count() }}</strong>
          </div>
          <div class="col-4 border-end">
            <small class="text-muted d-block" style="font-size: 0.72rem;">Đánh Giá</small>
            <strong class="text-warning fs-6">{{ $user->reviews->count() }}</strong>
          </div>
          <div class="col-4">
            <small class="text-muted d-block" style="font-size: 0.72rem;">Tích Lũy</small>
            <strong class="text-danger fs-6" style="font-size: 0.85rem !important;">{{ number_format($totalSpent / 1000, 0) }}k</strong>
          </div>
        </div>

        <!-- Tier Progress Bar -->
        @if($neededMore > 0)
          <div class="p-2.5 rounded-3 border mb-3 text-start" style="background: #fafafa;">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <small class="text-muted fw-semibold" style="font-size: 0.73rem;">Tiến trình lên {{ $nextTierName }}</small>
              <small class="text-danger fw-bold" style="font-size: 0.73rem;">{{ $progressPercent }}%</small>
            </div>
            <div class="progress" style="height: 6px;">
              <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $progressPercent }}%"></div>
            </div>
            <small class="text-muted d-block mt-1" style="font-size: 0.68rem;">
              Chi tiêu thêm <strong class="text-dark">{{ number_format($neededMore, 0, ',', '.') }}₫</strong> để nâng hạng đặc quyền.
            </small>
          </div>
        @else
          <div class="p-2 rounded-3 border mb-3 text-center bg-dark text-warning" style="font-size: 0.75rem;">
            <i class="fa-solid fa-gem me-1"></i> Quý khách đã đạt hạng <strong>{{ $tierName }}</strong> cao nhất!
          </div>
        @endif

        <!-- Navigation Tabs List -->
        <div class="nav flex-column nav-pills text-start small border-top pt-3 gap-1" id="profileTabs" role="tablist">
          <button class="nav-link active fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="orders-tab" data-bs-toggle="pill" data-bs-target="#tab-orders" type="button" role="tab">
            <span><i class="fa-solid fa-box-archive me-2 text-danger"></i> Đơn Hàng Của Tôi</span>
            <span class="badge bg-dark text-white rounded-pill">{{ $orders->count() }}</span>
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="returns-tab" data-bs-toggle="pill" data-bs-target="#tab-returns" type="button" role="tab">
            <span><i class="fa-solid fa-arrow-rotate-left me-2 text-primary"></i> Đổi Trả &amp; Bảo Hành</span>
            @php $returnsCount = isset($returns) ? $returns->count() : 0; @endphp
            <span class="badge {{ $returnsCount > 0 ? 'bg-primary text-white' : 'bg-light text-muted' }} rounded-pill">{{ $returnsCount }}</span>
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

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="pending-reviews-tab" data-bs-toggle="pill" data-bs-target="#tab-pending-reviews" type="button" role="tab">
            <span><i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i> Chờ Đánh Giá</span>
            <span class="badge {{ ($pendingReviewItems->count() > 0) ? 'bg-danger text-white' : 'bg-light text-muted' }} rounded-pill" id="profilePendingCountBadge">
              {{ $pendingReviewItems->count() }}
            </span>
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
                        @if($order->isCustomerRejected())
                          <span class="badge bg-danger text-white fw-bold"><i class="fa-solid fa-truck-arrow-right me-1"></i> Khách không nhận (Chuyển hoàn)</span>
                        @else
                          <span class="badge bg-danger-subtle text-danger fw-bold"><i class="fa-solid fa-xmark me-1"></i> Đã hủy</span>
                        @endif
                      @else
                        <span class="badge bg-secondary-subtle text-dark fw-bold"><i class="fa-solid fa-hourglass-start me-1"></i> Chờ xác nhận</span>
                      @endif
                    </div>
                  </div>

                  <!-- Cancelled / Rejected Order Info Banner -->
                  @if($order->isCustomerRejected())
                    <div class="alert alert-danger border-0 py-2.5 px-3 mb-3 rounded-2 small d-flex align-items-center gap-2.5" style="background: #fff5f5; border-left: 4px solid #ef4444 !important;">
                      <i class="fa-solid fa-truck-arrow-right text-danger fs-4 flex-shrink-0"></i>
                      <div>
                        <strong class="text-danger">Đơn hàng đã từ chối nhận (Đang chuyển hoàn về kho):</strong>
                        <span class="text-dark">{{ $order->cancel_reason ?: 'Khách hàng từ chối nhận bưu phẩm' }}</span>
                        <small class="text-muted d-block" style="font-size: 0.72rem;">Thời gian ghi nhận: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i') : '' }} • Bưu kiện đang được chuyển hoàn về kho BeeStyle</small>
                      </div>
                    </div>
                  @elseif($order->shipping_status === 'cancelled')
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
                        <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="btn btn-sm btn-outline-dark py-0.5 px-2 fw-bold" style="font-size: 0.72rem;">
                          <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Xem Tiến Trình
                        </a>
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



                  <!-- Order Footer: Payment Status & Actions -->
                  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2.5 border-top">
                    <div class="d-flex align-items-center gap-2">
                      <span class="small text-muted">Thanh toán:</span>
                      <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }} fw-bold">
                        {{ $order->payment_status_label }}
                      </span>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                      <div>
                        <span class="small text-muted">Tổng tiền: </span>
                        <strong class="text-danger fs-6">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                        @if($order->is_deposit_required)
                          <span class="badge bg-warning text-dark ms-1" style="font-size: 0.68rem;" title="Đơn hàng yêu cầu cọc 50%">
                            <i class="fa-solid fa-shield-halved me-0.5"></i> Cọc 50%: {{ number_format($order->deposit_amount, 0, ',', '.') }}₫
                          </span>
                        @endif
                      </div>
                      @if($order->payment_method === 'vietqr' && $order->payment_status !== 'paid')
                        <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="btn btn-sm btn-bee-primary fw-bold px-3">
                          <i class="fa-solid fa-qrcode me-1"></i> Quét Mã VietQR
                        </a>
                      @endif
                      @if(in_array($order->shipping_status, ['shipping', 'delivered']) || in_array($order->status_step, [4, 5]))
                        <!-- THÔNG BÁO GIAO HÀNG & 2 NÚT HÀNH ĐỘNG CẠNH NHAU -->
                        <div class="d-flex align-items-center gap-2">
                          <button type="button" class="btn btn-sm btn-success fw-bold px-3 py-1.5 rounded-pill shadow-xs d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#confirmModalOrder{{ $order->id }}">
                            <i class="fa-solid fa-circle-check"></i> <span>Đã Nhận Được Hàng</span>
                          </button>

                          <button type="button" class="btn btn-sm btn-outline-danger fw-bold px-3 py-1.5 rounded-pill shadow-xs d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#refundModalOrder{{ $order->id }}">
                            <i class="fa-solid fa-hand-holding-dollar"></i> <span>Hủy Hàng Hoàn Tiền</span>
                          </button>
                        </div>
                      @elseif($order->shipping_status === 'completed')
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill small fw-bold">
                          <i class="fa-solid fa-circle-check me-0.5"></i> Đã Hoàn Tất
                        </span>
                        @if($order->canBeReturnedByCustomer())
                          <button type="button" class="btn btn-sm btn-outline-danger px-3 py-1.5 fw-bold rounded-pill shadow-xs d-inline-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#refundModalOrder{{ $order->id }}">
                            <i class="fa-solid fa-arrow-rotate-left"></i> <span>Đổi Trả / Hoàn Tiền</span>
                          </button>
                        @endif
                      @endif

                      <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="btn btn-sm btn-bee-outline">
                        <i class="fa-solid fa-truck-fast me-1"></i> Tra Cứu Vận Chuyển
                      </a>
                    </div>
                  </div>

                  <!-- MODAL 1: XÁC NHẬN ĐÃ NHẬN HÀNG TRONG PROFILE -->
                  @if(in_array($order->shipping_status, ['shipping', 'delivered']) || in_array($order->status_step, [4, 5]))
                    <div class="modal fade" id="confirmModalOrder{{ $order->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                          <form action="{{ route('client.order-tracking.confirm-delivered', $order->order_code) }}" method="POST">
                            @csrf
                            <div class="modal-header border-bottom py-3 px-4 bg-light">
                              <h6 class="modal-title fw-bold text-success d-flex align-items-center gap-2 mb-0">
                                <i class="fa-solid fa-circle-check fs-5"></i>
                                <span>Xác Nhận Đã Nhận Đủ Hàng #{{ $order->order_code }}</span>
                              </h6>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4 text-start">
                              <div class="p-3 bg-light rounded-3 border mb-3 small">
                                <div class="d-flex justify-content-between mb-1">
                                  <span class="text-muted">Mã đơn hàng:</span>
                                  <strong class="font-monospace text-dark">#{{ $order->order_code }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                  <span class="text-muted">Tổng thanh toán:</span>
                                  <strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                                </div>
                              </div>
                              <p class="small text-dark mb-2">
                                Quý khách xác nhận đã nhận trọn vẹn kiện hàng, mở kiểm tra sản phẩm đúng kích thước (size), màu sắc và hài lòng với chất lượng?
                              </p>
                              <div class="alert alert-info border-0 p-2.5 rounded-3 small mb-0" style="background: #f0f9ff;">
                                <i class="fa-solid fa-gift text-info me-1"></i> Sau khi hoàn tất, bạn sẽ được <strong>tích lũy điểm thưởng</strong> và mở mục Đánh giá sản phẩm nhận quà voucher!
                              </div>
                            </div>
                            <div class="modal-footer border-top bg-light py-2 px-4">
                              <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                              <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-xs">
                                <i class="fa-solid fa-check me-1"></i> Tôi Đã Nhận Đủ Hàng
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @endif

                  <!-- MODAL 2: YÊU CẦU ĐỔI HÀNG (SIZE/MÀU) & TRẢ HÀNG HOÀN TIỀN CHUYÊN NGHIỆP -->
                  @if(in_array($order->shipping_status, ['shipping', 'delivered']) || in_array($order->status_step, [4, 5]) || ($order->shipping_status === 'completed' && $order->canBeReturnedByCustomer()))
                    <div class="modal fade" id="refundModalOrder{{ $order->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                          <form action="{{ route('client.order-tracking.request-refund', $order->order_code) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="from_profile" value="1">

                            <div class="modal-header border-bottom py-3 px-4 bg-light">
                              <div>
                                <h6 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                                  <i class="fa-solid fa-arrows-rotate text-primary fs-5"></i>
                                  <span>Yêu Cầu Đổi Hàng &amp; Hoàn Tiền #{{ $order->order_code }}</span>
                                </h6>
                                <span class="text-muted fs-11">Chính sách đổi size tận nhà trong 7 ngày hoặc hoàn tiền qua tài khoản ngân hàng</span>
                              </div>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body p-4 text-start">
                              <!-- BƯỚC 1: LỰA CHỌN HÌNH THỨC -->
                              <div class="mb-3">
                                <label class="form-label small fw-bold text-dark mb-2">1. Chọn hình thức bạn mong muốn: <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                  <!-- OPTION: ĐỔI SIZE / ĐỔI MÀU -->
                                  <div class="{{ in_array($order->shipping_status, ['shipping', 'delivered']) ? 'col-md-4' : 'col-md-6' }}">
                                    <label class="d-block p-3 rounded-3 border bg-white cursor-pointer h-100 return-type-card active border-primary shadow-xs" id="cardExchange{{ $order->id }}" onclick="selectReturnType({{ $order->id }}, 'exchange')">
                                      <div class="d-flex align-items-start gap-2.5">
                                        <input type="radio" name="type" id="radioEx{{ $order->id }}" value="exchange" class="form-check-input mt-1" checked onchange="selectReturnType({{ $order->id }}, 'exchange')">
                                        <div>
                                          <div class="d-flex align-items-center gap-1.5 mb-1">
                                            <strong class="text-primary small d-block">ĐỔI SIZE / MÀU</strong>
                                            <span class="badge bg-primary text-white" style="font-size: 0.65rem;">Khuyên Dùng</span>
                                          </div>
                                          <p class="text-muted mb-0 small" style="font-size: 0.72rem; line-height: 1.35;">Shipper giao size/màu mới tận nhà và lấy lại hàng cũ. Miễn phí đổi trong 7 ngày.</p>
                                        </div>
                                      </div>
                                    </label>
                                  </div>

                                  <!-- OPTION: TRẢ HÀNG & HOÀN TIỀN -->
                                  <div class="{{ in_array($order->shipping_status, ['shipping', 'delivered']) ? 'col-md-4' : 'col-md-6' }}">
                                    <label class="d-block p-3 rounded-3 border bg-white cursor-pointer h-100 return-type-card" id="cardReturnRefund{{ $order->id }}" onclick="selectReturnType({{ $order->id }}, 'return_refund')">
                                      <div class="d-flex align-items-start gap-2.5">
                                        <input type="radio" name="type" id="radioRe{{ $order->id }}" value="return_refund" class="form-check-input mt-1" onchange="selectReturnType({{ $order->id }}, 'return_refund')">
                                        <div>
                                          <div class="d-flex align-items-center gap-1.5 mb-1">
                                            <strong class="text-danger small d-block">TRẢ HÀNG HOÀN TIỀN</strong>
                                            <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem;">Hoàn 100%</span>
                                          </div>
                                          <p class="text-muted mb-0 small" style="font-size: 0.72rem; line-height: 1.35;">Gửi trả sản phẩm về kho BeeStyle và nhận tiền chuyển khoản về tài khoản ngân hàng.</p>
                                        </div>
                                      </div>
                                    </label>
                                  </div>

                                  @if(in_array($order->shipping_status, ['shipping', 'delivered']))
                                    <!-- OPTION: TỪ CHỐI NHẬN HÀNG (KHI ĐANG GIAO) -->
                                    <div class="col-md-4">
                                      <label class="d-block p-3 rounded-3 border bg-white cursor-pointer h-100 return-type-card" id="cardRefundOnly{{ $order->id }}" onclick="selectReturnType({{ $order->id }}, 'refund_only')">
                                        <div class="d-flex align-items-start gap-2.5">
                                          <input type="radio" name="type" id="radioCan{{ $order->id }}" value="refund_only" class="form-check-input mt-1" onchange="selectReturnType({{ $order->id }}, 'refund_only')">
                                          <div>
                                            <div class="d-flex align-items-center gap-1.5 mb-1">
                                              <strong class="text-dark small d-block">TỪ CHỐI NHẬN</strong>
                                              <span class="badge bg-secondary text-white" style="font-size: 0.65rem;">Chuyển Hoàn</span>
                                            </div>
                                            <p class="text-muted mb-0 small" style="font-size: 0.72rem; line-height: 1.35;">Bưu tá lập tức chuyển hoàn kiện hàng về kho (dành cho đơn shipper đang giao).</p>
                                          </div>
                                        </div>
                                      </label>
                                    </div>
                                  @endif
                                </div>
                              </div>

                              <!-- BƯỚC 2: CHỌN SẢN PHẨM TRONG ĐƠN CẦN ĐỔI/TRẢ -->
                              <div class="mb-3">
                                <label class="form-label small fw-bold text-dark mb-1.5">2. Chọn sản phẩm trong đơn cần xử lý: <span class="text-danger">*</span></label>
                                <div class="d-flex flex-column gap-2 max-vh-25 overflow-y-auto p-1 border rounded-3 bg-light">
                                  @foreach($order->items as $idx => $item)
                                    <label class="d-flex align-items-center justify-content-between p-2.5 rounded-2 border bg-white cursor-pointer hover-shadow transition-all">
                                      <div class="d-flex align-items-center gap-2.5">
                                        <input type="radio" name="order_item_id" value="{{ $item->id }}" class="form-check-input mt-0" {{ $loop->first ? 'checked' : '' }}>
                                        <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 44px; height: 44px; object-fit: cover;" class="rounded border">
                                        <div>
                                          <strong class="small d-block text-dark line-clamp-1">{{ $item->product_name }}</strong>
                                          <small class="text-muted">Màu: {{ $item->color ?? 'Mặc định' }} | Size: <span class="badge bg-dark text-white fw-bold">{{ $item->size ?? 'M' }}</span> • SL: x{{ $item->quantity }}</small>
                                        </div>
                                      </div>
                                      <div class="text-end">
                                        <span class="small fw-bold text-dark">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                                      </div>
                                    </label>
                                  @endforeach
                                </div>
                              </div>

                              <!-- KHỐI 3A: CẤU HÌNH ĐỔI HÀNG (HIỂN THỊ KHI CHỌN ĐỔI SIZE / MÀU) -->
                              <div id="sectionExchange{{ $order->id }}" class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle mb-3">
                                <strong class="small text-primary d-block mb-2">
                                  <i class="fa-solid fa-arrows-rotate me-1"></i> Thông tin phân loại mới muốn đổi:
                                </strong>
                                <div class="row g-2 mb-2.5">
                                  <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark mb-1">Size mới muốn đổi <span class="text-danger">*</span></label>
                                    <select name="exchange_size" id="exchangeSize{{ $order->id }}" class="form-select form-select-sm">
                                      <option value="S">Size S (48 - 56kg)</option>
                                      <option value="M" selected>Size M (57 - 65kg)</option>
                                      <option value="L">Size L (66 - 73kg)</option>
                                      <option value="XL">Size XL (74 - 82kg)</option>
                                      <option value="XXL">Size XXL (83 - 90kg)</option>
                                      <option value="3XL">Size 3XL (&gt; 90kg)</option>
                                    </select>
                                  </div>
                                  <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark mb-1">Màu sắc mới muốn đổi</label>
                                    <input type="text" name="exchange_color" class="form-control form-control-sm" placeholder="VD: Giữ nguyên màu hoặc Đen, Trắng, Ghi...">
                                  </div>
                                </div>
                                <div class="small text-dark p-2 bg-white rounded-2 border" style="font-size: 0.75rem;">
                                  <i class="fa-solid fa-location-dot text-danger me-1"></i> <strong>Địa chỉ giao nhận đổi hàng tận nơi:</strong> {{ $order->customer_name }} • {{ $order->customer_phone }} ({{ $order->shipping_address }})
                                </div>
                              </div>

                              <!-- KHỐI 3B: CẤU HÌNH HOÀN TIỀN (HIỂN THỊ KHI CHỌN TRẢ HÀNG & HOÀN TIỀN) -->
                              <div id="sectionRefund{{ $order->id }}" class="p-3 rounded-3 bg-danger-subtle border border-danger-subtle mb-3" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-2.5">
                                  <div>
                                    <span class="small text-muted d-block" style="font-size: 0.75rem;">Số tiền dự kiến hoàn trả:</span>
                                    <strong class="fs-5 text-danger font-monospace">
                                      {{ number_format($order->is_deposit_required && $order->payment_status === 'deposit_paid' ? $order->deposit_amount : $order->total_amount, 0, ',', '.') }}₫
                                    </strong>
                                  </div>
                                  <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success text-white' : 'bg-warning text-dark' }} px-2.5 py-1 rounded-pill small">
                                    {{ $order->payment_status_label }}
                                  </span>
                                </div>

                                <!-- TÀI KHOẢN NGÂN HÀNG -->
                                <strong class="small text-dark d-block mb-1.5"><i class="fa-solid fa-building-columns text-primary me-1"></i> Tài khoản ngân hàng nhận tiền hoàn:</strong>
                                <div class="row g-2">
                                  <div class="col-md-5">
                                    <input type="text" name="bank_name" class="form-control form-control-sm" placeholder="Tên ngân hàng (VD: MB Bank, VCB...)" value="{{ Auth::user()->bank_name ?? '' }}">
                                  </div>
                                  <div class="col-md-4">
                                    <input type="text" name="bank_account_number" class="form-control form-control-sm font-monospace" placeholder="Số tài khoản..." value="{{ Auth::user()->bank_account_number ?? '' }}">
                                  </div>
                                  <div class="col-md-3">
                                    <input type="text" name="bank_account_name" class="form-control form-control-sm text-uppercase" placeholder="Chủ tài khoản..." value="{{ Auth::user()->bank_account_name ?? '' }}">
                                  </div>
                                </div>
                              </div>

                              <!-- BƯỚC 4: LÝ DO YÊU CẦU -->
                              <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">3. Lý do yêu cầu <span class="text-danger">*</span></label>
                                <select name="reason" class="form-select form-select-sm" required>
                                  <option value="" selected disabled>-- Vui lòng chọn lý do cụ thể --</option>
                                  <option value="Tôi mặc thử không vừa kích cỡ (muốn đổi size rộng/chật hơn)">Tôi mặc thử không vừa kích cỡ (muốn đổi size rộng/chật hơn)</option>
                                  <option value="Muốn đổi sang màu sắc hoặc mẫu mã khác hợp phong cách hơn">Muốn đổi sang màu sắc hoặc mẫu mã khác hợp phong cách hơn</option>
                                  <option value="Sản phẩm bị lỗi may mặc, sờn rách, ố vải hoặc lỗi khóa kéo">Sản phẩm bị lỗi may mặc, sờn rách, ố vải hoặc lỗi khóa kéo</option>
                                  <option value="Bưu tá giao sai mẫu mã, sai màu sắc hoặc kích cỡ so với đơn đặt">Bưu tá giao sai mẫu mã, sai màu sắc hoặc kích cỡ so với đơn đặt</option>
                                  <option value="Sản phẩm không đúng với hình ảnh / mô tả trên website">Sản phẩm không đúng với hình ảnh / mô tả trên website</option>
                                  <option value="Hộp/Thùng hàng bị móp méo, rách vỡ, mất niêm phong">Hộp/Thùng hàng bị móp méo, rách vỡ, mất niêm phong</option>
                                  <option value="Thời gian giao hàng quá trễ, không còn nhu cầu">Thời gian giao hàng quá trễ, không còn nhu cầu</option>
                                  <option value="Lý do khác">Lý do khác</option>
                                </select>
                              </div>

                              <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">4. Ghi chú chi tiết thêm cho nhân viên BeeStyle</label>
                                <textarea name="customer_notes" class="form-control form-control-sm" rows="2" placeholder="Ví dụ: Đổi sang size L áo phông polo, thời gian shipper đến lấy vào buổi chiều..."></textarea>
                              </div>

                              <div class="mb-2">
                                <label class="form-label small fw-bold text-dark">5. Ảnh chụp minh chứng sản phẩm (nếu có lỗi)</label>
                                <input type="file" name="image_proofs[]" class="form-control form-control-sm" accept="image/*" multiple>
                                <small class="text-muted fs-11">Tải tối đa 5 ảnh sản phẩm hoặc chi tiết cần hỗ trợ.</small>
                              </div>
                            </div>

                            <div class="modal-footer border-top bg-light py-2 px-4 d-flex justify-content-between align-items-center">
                              <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                              <button type="submit" id="btnSubmitReturn{{ $order->id }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-xs">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> Gửi Yêu Cầu Đổi Hàng Mới
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>

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

        <!-- TAB: RMA / RETURN & EXCHANGE REQUESTS -->
        <div class="tab-pane fade" id="tab-returns" role="tabpanel">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 pb-2 border-bottom">
              <div>
                <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                  <i class="fa-solid fa-arrow-rotate-left me-2 text-primary"></i> Yêu Cầu Đổi Trả &amp; Bảo Hành ({{ isset($returns) ? $returns->count() : 0 }})
                </h5>
                <p class="text-muted small mb-0">Theo dõi tiến trình đổi size, đổi màu và hoàn tiền cho các đơn hàng của bạn</p>
              </div>
              <button type="button" onclick="document.getElementById('orders-tab').click()" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-box-archive me-1"></i> Xem Danh Sách Đơn Hàng
              </button>
            </div>

            <!-- POLICY BANNER -->
            <div class="p-3 mb-4 rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%); border-color: #bfdbfe !important;">
              <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                  <i class="fa-solid fa-shield-heart fs-4"></i>
                </div>
                <div>
                  <h6 class="fw-bold text-dark mb-0.5 small text-uppercase">Chính Sách Đổi Trả Tận Nhà Độc Quyền BeeStyle Menswear</h6>
                  <p class="text-muted mb-0 small" style="font-size: 0.78rem;">
                    Miễn phí đổi Size / Màu trong <strong>7 ngày</strong> • Shipper mang sản phẩm mới đến tận nhà đổi đồng thời • Hoàn tiền 100% nếu sản phẩm lỗi
                  </p>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1.5 rounded-pill"><i class="fa-solid fa-check me-1"></i> Đổi tận nơi</span>
                <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1.5 rounded-pill"><i class="fa-solid fa-truck-fast me-1"></i> 24-48 Giờ</span>
              </div>
            </div>

            <!-- RETURNS LIST -->
            <div class="d-flex flex-column gap-3">
              @if(isset($returns) && $returns->count() > 0)
                @foreach($returns as $ret)
                  <div class="border rounded-3 p-3.5 bg-light-subtle shadow-2xs">
                    <!-- RETURN HEADER -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-2.5 border-bottom mb-3">
                      <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                          <strong class="text-dark font-monospace fs-6">#{{ $ret->return_code }}</strong>
                          @if($ret->type === 'exchange')
                            <span class="badge bg-primary text-white fw-bold"><i class="fa-solid fa-arrows-rotate me-1"></i> Đổi Hàng (Size/Màu)</span>
                          @elseif($ret->type === 'refund_only')
                            <span class="badge bg-warning text-dark fw-bold"><i class="fa-solid fa-ban me-1"></i> Từ Chối Nhận Hàng</span>
                          @else
                            <span class="badge bg-danger text-white fw-bold"><i class="fa-solid fa-hand-holding-dollar me-1"></i> Trả Hàng &amp; Hoàn Tiền</span>
                          @endif
                        </div>
                        <small class="text-muted mt-0.5 d-block" style="font-size: 0.72rem;">
                          Đơn gốc: <strong class="text-dark font-monospace">#{{ $ret->order->order_code ?? 'N/A' }}</strong> 
                          • Ngày gửi: {{ $ret->created_at ? $ret->created_at->format('d/m/Y H:i') : '' }}
                        </small>
                      </div>
                      <div class="text-end">
                        {!! $ret->status_badge !!}
                      </div>
                    </div>

                    <!-- STEPPER TRACKER 4 BƯỚC -->
                    <div class="p-3 bg-white rounded-3 border mb-3">
                      <div class="row g-2 text-center position-relative">
                        @php
                          $stepNum = match($ret->status) {
                            'pending' => 1,
                            'approved' => 2,
                            'received' => 3,
                            'completed' => 4,
                            'rejected' => 0,
                            default => 1,
                          };
                        @endphp
                        <div class="col-3">
                          <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold {{ $stepNum >= 1 ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width: 28px; height: 28px; font-size: 0.75rem;">
                            <i class="fa-solid fa-check"></i>
                          </div>
                          <span class="small fw-semibold {{ $stepNum >= 1 ? 'text-dark' : 'text-muted' }}" style="font-size: 0.72rem;">1. Gửi Yêu Cầu</span>
                        </div>
                        <div class="col-3">
                          <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold {{ $stepNum >= 2 ? 'bg-success text-white' : ($stepNum === 1 ? 'bg-warning text-dark' : 'bg-light text-muted border') }}" style="width: 28px; height: 28px; font-size: 0.75rem;">
                            @if($stepNum >= 2) <i class="fa-solid fa-check"></i> @elseif($stepNum === 1) <i class="fa-solid fa-ellipsis"></i> @else 2 @endif
                          </div>
                          <span class="small fw-semibold {{ $stepNum >= 2 ? 'text-dark' : 'text-muted' }}" style="font-size: 0.72rem;">2. Shop Tiếp Nhận</span>
                        </div>
                        <div class="col-3">
                          <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold {{ $stepNum >= 3 ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width: 28px; height: 28px; font-size: 0.75rem;">
                            @if($stepNum >= 3) <i class="fa-solid fa-check"></i> @else 3 @endif
                          </div>
                          <span class="small fw-semibold {{ $stepNum >= 3 ? 'text-dark' : 'text-muted' }}" style="font-size: 0.72rem;">3. {{ $ret->type === 'exchange' ? 'Giao Đổi Tận Nơi' : 'Thu Hồi Bưu Kiện' }}</span>
                        </div>
                        <div class="col-3">
                          <div class="rounded-circle mx-auto mb-1 d-flex align-items-center justify-content-center fw-bold {{ $stepNum === 4 ? 'bg-success text-white' : ($ret->status === 'rejected' ? 'bg-danger text-white' : 'bg-light text-muted border') }}" style="width: 28px; height: 28px; font-size: 0.75rem;">
                            @if($stepNum === 4) <i class="fa-solid fa-check"></i> @elseif($ret->status === 'rejected') <i class="fa-solid fa-xmark"></i> @else 4 @endif
                          </div>
                          <span class="small fw-semibold {{ $stepNum === 4 ? 'text-success fw-bold' : ($ret->status === 'rejected' ? 'text-danger fw-bold' : 'text-muted') }}" style="font-size: 0.72rem;">
                            {{ $ret->status === 'rejected' ? 'Bị Từ Chối' : 'Hoàn Tất' }}
                          </span>
                        </div>
                      </div>
                    </div>

                    <!-- RETURN DETAIL INFO -->
                    <div class="p-3 bg-white rounded-3 border mb-3">
                      <div class="row g-3">
                        <div class="col-md-7 border-end-md">
                          <div class="mb-2">
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">Lý do yêu cầu:</span>
                            <strong class="text-dark small"><i class="fa-solid fa-circle-question text-warning me-1"></i> {{ $ret->reason }}</strong>
                          </div>
                          @if($ret->customer_notes)
                            <div class="mb-2">
                              <span class="small text-muted d-block" style="font-size: 0.75rem;">Ghi chú của bạn:</span>
                              <span class="small text-dark fst-italic">"{{ $ret->customer_notes }}"</span>
                            </div>
                          @endif
                          @if($ret->type === 'exchange')
                            <div class="p-2.5 rounded-2 bg-primary-subtle border border-primary-subtle mt-2">
                              <div class="d-flex align-items-center gap-2 flex-wrap">
                                <strong class="small text-primary"><i class="fa-solid fa-arrows-rotate me-1"></i> Thông tin đổi hàng:</strong>
                                @if($ret->exchange_size)
                                  <span class="badge bg-primary text-white">Size mới: {{ $ret->exchange_size }}</span>
                                @endif
                                @if($ret->exchange_color)
                                  <span class="badge bg-dark text-white">Màu mới: {{ $ret->exchange_color }}</span>
                                @endif
                              </div>
                              <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Shipper sẽ mang sản phẩm mới đến địa chỉ của bạn và nhận lại sản phẩm cũ cùng lúc.</small>
                            </div>
                          @elseif($ret->refund_amount > 0)
                            <div class="p-2.5 rounded-2 bg-danger-subtle border border-danger-subtle mt-2">
                              <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Số tiền hoàn:</span>
                                <strong class="text-danger fs-6">{{ number_format($ret->refund_amount, 0, ',', '.') }}₫</strong>
                              </div>
                              @if($ret->bank_account_number)
                                <div class="mt-1 small text-dark" style="font-size: 0.75rem;">
                                  <i class="fa-solid fa-building-columns text-secondary me-1"></i> {{ $ret->bank_name ?? 'Ngân hàng' }}: <span class="font-monospace fw-bold">{{ $ret->bank_account_number }}</span> ({{ $ret->bank_account_name }})
                                </div>
                              @endif
                            </div>
                          @endif
                        </div>

                        <div class="col-md-5">
                          @if(!empty($ret->image_proofs) && is_array($ret->image_proofs))
                            <span class="small text-muted d-block mb-1.5" style="font-size: 0.75rem;">Ảnh minh chứng ({{ count($ret->image_proofs) }} ảnh):</span>
                            <div class="d-flex gap-1.5 flex-wrap">
                              @foreach($ret->image_proofs as $img)
                                <img src="{{ asset($img) }}" alt="Minh chứng" class="rounded border cursor-pointer object-fit-cover shadow-2xs" style="width: 50px; height: 50px;" onclick="openReviewImageLightbox('{{ asset($img) }}')">
                              @endforeach
                            </div>
                          @else
                            <div class="h-100 d-flex align-items-center justify-content-center text-center p-2">
                              <small class="text-muted fst-italic" style="font-size: 0.75rem;"><i class="fa-solid fa-camera text-secondary me-1"></i> Không có ảnh đính kèm</small>
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>

                    <!-- ADMIN NOTES BANNER -->
                    @if($ret->admin_notes)
                      <div class="alert alert-info border-0 py-2.5 px-3 mb-3 rounded-2 small d-flex align-items-start gap-2" style="background: #f0f9ff; border-left: 4px solid #0284c7 !important;">
                        <i class="fa-solid fa-headset text-info fs-5 flex-shrink-0 mt-0.5"></i>
                        <div>
                          <strong class="text-info d-block">Phản hồi từ CSKH BeeStyle:</strong>
                          <span class="text-dark">{{ $ret->admin_notes }}</span>
                        </div>
                      </div>
                    @endif

                    @if($ret->rejected_reason)
                      <div class="alert alert-danger border-0 py-2.5 px-3 mb-3 rounded-2 small d-flex align-items-start gap-2" style="background: #fef2f2; border-left: 4px solid #ef4444 !important;">
                        <i class="fa-solid fa-circle-exclamation text-danger fs-5 flex-shrink-0 mt-0.5"></i>
                        <div>
                          <strong class="text-danger d-block">Lý do từ chối:</strong>
                          <span class="text-dark">{{ $ret->rejected_reason }}</span>
                        </div>
                      </div>
                    @endif

                    <!-- FOOTER ACTIONS -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                      <div class="small text-muted" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-clock me-1"></i> Cập nhật gần nhất: {{ $ret->updated_at ? $ret->updated_at->format('d/m/Y H:i') : '' }}
                      </div>
                      @if($ret->order)
                        <a href="{{ route('client.order-tracking', ['code' => $ret->order->order_code]) }}" class="btn btn-sm btn-bee-outline rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                          <i class="fa-solid fa-truck-fast me-1"></i> Chi Tiết Tra Cứu Vận Chuyển
                        </a>
                      @endif
                    </div>
                  </div>
                @endforeach
              @else
                <div class="text-center py-5">
                  <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fa-solid fa-arrow-rotate-left fs-2 text-muted"></i>
                  </div>
                  <h6 class="fw-bold text-dark mb-1">Bạn chưa có yêu cầu đổi trả hoặc bảo hành nào</h6>
                  <p class="text-muted small mb-3">Mọi sản phẩm mua tại BeeStyle đều được hưởng quyền lợi đổi size miễn phí tận nhà trong 7 ngày.</p>
                  <button type="button" class="btn btn-bee-primary btn-sm px-4 rounded-pill fw-bold" onclick="document.getElementById('orders-tab').click()">
                    <i class="fa-solid fa-box-archive me-1"></i> Xem Danh Sách Đơn Hàng
                  </button>
                </div>
              @endif
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

        <!-- TAB: PENDING REVIEWS (SẢN PHẨM CHỜ ĐÁNH GIÁ) -->
        <div class="tab-pane fade" id="tab-pending-reviews" role="tabpanel">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <div>
                <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                  <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i> Sản Phẩm Chờ Đánh Giá (<span id="profilePendingCountText">{{ $pendingReviewItems->count() }}</span>)
                </h5>
                <p class="text-muted small mb-0">Các món đồ bạn đã nhận từ các đơn hàng hoàn tất. Hãy chia sẻ cảm nhận thực tế để giúp cộng đồng mua sắm và nhận quà từ BeeStyle!</p>
              </div>
              <span class="badge bg-warning-subtle text-dark fw-bold border"><i class="fa-solid fa-gift text-warning me-1"></i> Tích lũy điểm hội viên</span>
            </div>

            <div class="d-flex flex-column gap-3" id="profilePendingReviewsList">
              @forelse($pendingReviewItems as $pItem)
                <div class="p-3 bg-light rounded-3 border transition-all hover-lift d-flex align-items-center justify-content-between flex-wrap gap-3" id="pending-rev-row-{{ $pItem->product_id }}">
                  <div class="d-flex align-items-center gap-3">
                    <img src="{{ asset($pItem->image ?? ($pItem->product->image ?? '/assets/img/products/1.png')) }}" alt="{{ $pItem->product_name }}" style="width: 56px; height: 56px; object-fit: cover; cursor: pointer;" class="rounded border bg-white shadow-xs" onclick="openQuickReviewModal({{ $pItem->product_id }})">
                    <div>
                      <strong class="text-dark small d-block" style="cursor: pointer;" onclick="openQuickReviewModal({{ $pItem->product_id }})">
                        {{ $pItem->product_name }}
                      </strong>
                      <div class="text-muted small mt-0.5" style="font-size: 0.75rem;">
                        <span>Đơn hàng: <strong class="text-dark font-monospace">{{ $pItem->order->order_code ?? '' }}</strong></span>
                        @if($pItem->color || $pItem->size)
                          <span class="ms-2">| Phân loại: {{ $pItem->color ?? '' }} / {{ $pItem->size ?? '' }}</span>
                        @endif
                      </div>
                      <div class="text-danger fw-bold small mt-0.5">{{ number_format($pItem->price, 0, ',', '.') }}₫</div>
                    </div>
                  </div>
                  <div class="text-end">
                    <button type="button" onclick="openQuickReviewModal({{ $pItem->product_id }})" class="btn btn-bee-primary btn-sm px-3.5 py-1.5 fw-bold text-nowrap shadow-xs">
                      <i class="fa-solid fa-star text-warning me-1"></i> Đánh Giá Sản Phẩm
                    </button>
                  </div>
                </div>
              @empty
                <div class="text-center py-5" id="profilePendingEmptyState">
                  <div class="bg-success-subtle text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-circle-check fs-2"></i>
                  </div>
                  <h6 class="fw-bold text-dark mb-1">Tuyệt Vời! Bạn Đã Đánh Giá Tất Cả Sản Phẩm</h6>
                  <p class="text-muted small mb-3">Cảm ơn bạn đã luôn tin tưởng và đóng góp nhận xét chân thực cho BeeStyle.</p>
                  <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary btn-sm px-4 fw-bold">
                    Tiếp Tục Khám Phá Cửa Hàng
                  </a>
                </div>
              @endforelse
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

      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
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

    const targetTabId = tabMap[tabParam] || tabMap[hash];
    if (targetTabId) {
      const tabTrigger = document.getElementById(targetTabId);
      if (tabTrigger) {
        const bsTab = new bootstrap.Tab(tabTrigger);
        bsTab.show();
      }
    }

    // Cập nhật hash trên URL khi bấm chuyển tab
    const tabButtons = document.querySelectorAll('#profileTabs button[data-bs-toggle="pill"]');
    tabButtons.forEach(btn => {
      btn.addEventListener('shown.bs.tab', function (e) {
        const target = e.target.getAttribute('data-bs-target');
        if (target && history.replaceState) {
          history.replaceState(null, null, target);
        }
      });
    });
  });

  // Chuyển đổi giao diện Đổi Hàng (Exchange) vs Trả Hàng (Refund) trong Modal
  function selectReturnType(orderId, type) {
    const cardEx = document.getElementById('cardExchange' + orderId);
    const cardRe = document.getElementById('cardReturnRefund' + orderId);
    const cardCan = document.getElementById('cardRefundOnly' + orderId);
    const secEx = document.getElementById('sectionExchange' + orderId);
    const secRe = document.getElementById('sectionRefund' + orderId);
    const btnSub = document.getElementById('btnSubmitReturn' + orderId);

    const radioEx = document.getElementById('radioEx' + orderId);
    const radioRe = document.getElementById('radioRe' + orderId);
    const radioCan = document.getElementById('radioCan' + orderId);

    if (type === 'exchange') {
      if (radioEx) radioEx.checked = true;
      if (cardEx) cardEx.classList.add('active', 'border-primary', 'shadow-xs');
      if (cardRe) cardRe.classList.remove('active', 'border-primary', 'shadow-xs', 'border-danger');
      if (cardCan) cardCan.classList.remove('active', 'border-primary', 'shadow-xs', 'border-danger', 'border-dark');
      if (secEx) secEx.style.display = 'block';
      if (secRe) secRe.style.display = 'none';
      if (btnSub) {
        btnSub.className = 'btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-xs';
        btnSub.innerHTML = '<i class="fa-solid fa-arrows-rotate me-1"></i> Gửi Yêu Cầu Đổi Hàng Mới';
      }
    } else if (type === 'return_refund') {
      if (radioRe) radioRe.checked = true;
      if (cardRe) cardRe.classList.add('active', 'border-danger', 'shadow-xs');
      if (cardEx) cardEx.classList.remove('active', 'border-primary', 'shadow-xs');
      if (cardCan) cardCan.classList.remove('active', 'border-primary', 'shadow-xs', 'border-danger', 'border-dark');
      if (secEx) secEx.style.display = 'none';
      if (secRe) secRe.style.display = 'block';
      if (btnSub) {
        btnSub.className = 'btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-xs';
        btnSub.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Gửi Yêu Cầu Trả Hàng &amp; Hoàn Tiền';
      }
    } else if (type === 'refund_only') {
      if (radioCan) radioCan.checked = true;
      if (cardCan) cardCan.classList.add('active', 'border-dark', 'shadow-xs');
      if (cardEx) cardEx.classList.remove('active', 'border-primary', 'shadow-xs');
      if (cardRe) cardRe.classList.remove('active', 'border-danger', 'shadow-xs');
      if (secEx) secEx.style.display = 'none';
      if (secRe) secRe.style.display = 'none';
      if (btnSub) {
        btnSub.className = 'btn btn-dark btn-sm rounded-pill px-4 fw-bold shadow-xs';
        btnSub.innerHTML = '<i class="fa-solid fa-ban me-1"></i> Xác Nhận Từ Chối Nhận Hàng';
      }
    }
  }

  // Lightbox xem ảnh review phóng to
  function openReviewImageLightbox(imgUrl) {
    let modalEl = document.getElementById('profileReviewImgModal');
    if (!modalEl) {
      modalEl = document.createElement('div');
      modalEl.id = 'profileReviewImgModal';
      modalEl.className = 'modal fade';
      modalEl.tabIndex = -1;
      modalEl.innerHTML = `
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content bg-transparent border-0 shadow-none text-center">
            <div class="modal-body p-0 position-relative">
              <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 p-2 bg-dark rounded-circle" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1055;"></button>
              <img id="profileReviewFullImg" src="" class="img-fluid rounded-4 shadow-lg" style="max-height: 85vh; object-fit: contain;">
            </div>
          </div>
        </div>
      `;
      document.body.appendChild(modalEl);
    }
    const fullImg = document.getElementById('profileReviewFullImg');
    if (fullImg) fullImg.src = imgUrl;
    const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    bsModal.show();
  }
</script>
@endpush
@endsection
