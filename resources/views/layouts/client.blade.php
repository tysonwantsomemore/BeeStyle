<!DOCTYPE html>
<html lang="vi" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'BeeStyle - Thời Trang Nam Chuẩn Casual & Thanh Lịch')</title>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
  <meta name="theme-color" content="#111827">

  <!-- Google Fonts: Montserrat + Plus Jakarta Sans (Atino Minimalist Style) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Core Theme & Vendors Styles -->
  <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
  <link href="{{ asset('vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Atino Inspired BeeStyle Custom Design System -->
  <link href="{{ asset('assets/css/beestyle.css') }}" rel="stylesheet">

  @stack('styles')
</head>

<body>
  <!-- ANNOUNCEMENT TOP BAR -->
  <div class="bee-announcement-bar">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="d-flex align-items-center gap-3">
        <span><i class="fa-solid fa-truck-fast me-1 text-danger"></i> FREESHIP TOÀN QUỐC CHO ĐƠN TỪ <strong>300.000₫</strong></span>
        <span class="d-none d-md-inline text-secondary">|</span>
        <span class="d-none d-md-inline"><i class="fa-solid fa-phone me-1"></i> HOTLINE: <strong>1900 8888</strong></span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="{{ route('client.order-tracking') }}"><i class="fa-solid fa-location-dot me-1"></i> Tra cứu đơn hàng</a>
        <span class="text-secondary">|</span>
        @auth
          @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-gear me-1"></i> Quản Trị (Admin)</a>
          @else
            <a href="{{ route('client.profile') }}" class="text-white text-decoration-none"><i class="fa-solid fa-user me-1 text-danger"></i> Chào, <strong>{{ Auth::user()->name }}</strong></a>
          @endif
          <span class="text-secondary">|</span>
          <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-white-50 p-0 fs-11 text-decoration-none hover-white">
              <i class="fa-solid fa-arrow-right-from-bracket me-1 text-danger"></i> Đăng xuất
            </button>
          </form>
        @else
          <a href="{{ route('auth.login') }}" class="text-white"><i class="fa-solid fa-arrow-right-to-bracket me-1 text-danger"></i> Đăng nhập</a>
          <span class="text-secondary">/</span>
          <a href="{{ route('auth.register') }}" class="text-danger fw-bold">Đăng ký</a>
        @endauth
      </div>
    </div>
  </div>

  <!-- MAIN HEADER NAVBAR -->
  <header class="bee-navbar-main">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between gap-3">
        
        <!-- LOGO -->
        <a href="{{ route('client.home') }}" class="beestyle-logo">
          <span class="logo-badge"><i class="fa-solid fa-gem"></i></span>
          <span>BEE<span class="brand-highlight">STYLE</span></span>
        </a>

        <!-- SEARCH BAR -->
        <div class="d-none d-lg-block flex-grow-1 mx-4" style="max-width: 520px;">
          <form action="{{ route('client.products.index') }}" method="GET" class="bee-search-wrapper">
            <i class="fa-solid fa-magnifying-glass bee-search-icon"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control bee-search-input" placeholder="Tìm kiếm áo polo nam, sơ mi, áo phông, blazer, sweater...">
          </form>
        </div>

        <!-- RIGHT ACTIONS -->
        <div class="d-flex align-items-center gap-2">
          
          <!-- NOTIFICATION CENTER (TRUNG TÂM THÔNG BÁO TỔNG HỢP: ĐÁNH GIÁ, ĐƠN HÀNG, VOUCHER, VIP) -->
          @auth
            <div class="dropdown bee-notification-dropdown" id="beeNotificationDropdown">
              <button class="btn bee-icon-btn position-relative border-0" type="button" id="notifBellBtn" data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo & Đánh giá đơn hàng">
                <i class="fa-solid fa-bell {{ (isset($pendingReviewItems) && $pendingReviewItems->count() > 0) ? 'text-warning' : 'text-secondary' }} fs-5"></i>
                @if(isset($pendingReviewItems) && $pendingReviewItems->count() > 0)
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light animate-pulse" style="font-size: 0.65rem;">
                    {{ $pendingReviewItems->count() }}
                  </span>
                @endif
              </button>
              
              <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0 mt-2 bee-notif-menu" style="border-radius: 18px; width: 420px; max-width: 94vw; overflow: hidden; z-index: 1060;">
                <!-- Header -->
                <div class="p-3 text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #111827, #1f2937);">
                  <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-bell text-warning fs-5"></i>
                    <div>
                      <strong class="fs-9 text-uppercase d-block leading-none">Trung Tâm Thông Báo</strong>
                      <small class="text-white-50" style="font-size: 0.72rem;">Cập nhật đơn hàng &amp; ưu đãi của bạn</small>
                    </div>
                  </div>
                  @if(isset($pendingReviewItems) && $pendingReviewItems->count() > 0)
                    <span class="badge bg-danger text-white fs-11 fw-bold rounded-pill">
                      {{ $pendingReviewItems->count() }} cần đánh giá
                    </span>
                  @endif
                </div>

                <!-- Notification Navigation Filter Tabs -->
                <ul class="nav nav-tabs nav-fill bg-light border-bottom small fw-bold px-2 pt-2" id="notifTabs" role="tablist" style="font-size: 0.78rem;">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active py-2 px-1 text-dark border-0 border-bottom border-2 border-danger" id="notif-all-tab" data-bs-toggle="tab" data-bs-target="#notif-all" type="button" role="tab">
                      Tất Cả ({{ isset($allShopNotifications) ? $allShopNotifications->count() : 0 }})
                    </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link py-2 px-1 text-secondary border-0" id="notif-reviews-tab" data-bs-toggle="tab" data-bs-target="#notif-reviews" type="button" role="tab">
                      ⭐ Đánh Giá ({{ isset($pendingReviewItems) ? $pendingReviewItems->count() : 0 }})
                    </button>
                  </li>
                  <li class="nav-item" role="presentation">
                    <button class="nav-link py-2 px-1 text-secondary border-0" id="notif-orders-tab" data-bs-toggle="tab" data-bs-target="#notif-orders" type="button" role="tab">
                      📦 Đơn Hàng ({{ isset($recentCustomerOrders) ? $recentCustomerOrders->count() : 0 }})
                    </button>
                  </li>
                </ul>

                <!-- Notification Body Content -->
                <div class="tab-content" id="notifTabsContent" style="max-height: 380px; overflow-y: auto;">
                  
                  <!-- TAB 1: TẤT CẢ THÔNG BÁO -->
                  <div class="tab-pane fade show active p-3" id="notif-all" role="tabpanel">
                    @if(isset($allShopNotifications) && $allShopNotifications->count() > 0)
                      <div class="d-flex flex-column gap-2.5">
                        @foreach($allShopNotifications as $notif)
                          <div class="p-2.5 rounded-3 border transition-all hover-lift {{ $notif['type'] === 'review' ? 'bg-warning-subtle border-warning' : 'bg-light' }}" style="cursor: pointer;" onclick="window.location='{{ $notif['link'] }}'">
                            <div class="d-flex align-items-start gap-2.5">
                              @if(!empty($notif['image']))
                                <img src="{{ $notif['image'] }}" alt="thumb" style="width: 44px; height: 44px; object-fit: cover;" class="rounded-2 border bg-white flex-shrink-0">
                              @else
                                <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                  <i class="{{ $notif['icon'] }} fs-5"></i>
                                </div>
                              @endif
                              
                              <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                  <span class="badge {{ $notif['badge_class'] }}" style="font-size: 0.68rem;">{{ $notif['badge'] }}</span>
                                  <small class="text-muted" style="font-size: 0.7rem;">{{ $notif['time_ago'] }}</small>
                                </div>
                                <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 0.82rem;">{{ $notif['title'] }}</h6>
                                <p class="text-secondary small mb-2 text-truncate-2" style="font-size: 0.74rem; line-height: 1.35;">
                                  {{ $notif['content'] }}
                                </p>
                                <div class="text-end">
                                  <a href="{{ $notif['link'] }}" class="btn btn-sm {{ $notif['type'] === 'review' ? 'btn-bee-primary' : 'btn-bee-outline' }} py-0.5 px-2.5 fw-bold text-nowrap" style="font-size: 0.72rem;">
                                    {{ $notif['action_text'] }} <i class="fa-solid fa-arrow-right ms-1"></i>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @else
                      <div class="text-center py-4">
                        <i class="fa-regular fa-bell-slash fs-2 text-muted mb-2"></i>
                        <p class="small text-muted mb-0">Bạn chưa có thông báo mới nào.</p>
                      </div>
                    @endif
                  </div>

                  <!-- TAB 2: CHỈ THÔNG BÁO ĐÁNH GIÁ -->
                  <div class="tab-pane fade p-3" id="notif-reviews" role="tabpanel">
                    @if(isset($pendingReviewItems) && $pendingReviewItems->count() > 0)
                      <div class="alert alert-warning border-0 p-2 rounded-2 mb-3 d-flex align-items-center gap-2 small" style="background: #fffbeb;">
                        <i class="fa-solid fa-heart text-danger fs-5"></i>
                        <div style="font-size: 0.75rem;">
                          Cảm ơn bạn đã mua hàng! Hãy chia sẻ cảm nhận về sản phẩm nhé.
                        </div>
                      </div>
                      <div class="d-flex flex-column gap-2">
                        @foreach($pendingReviewItems as $pItem)
                          <div class="p-2.5 bg-light rounded-3 border">
                            <div class="d-flex align-items-center gap-2.5 mb-2">
                              <img src="{{ asset($pItem->image ?? ($pItem->product->image ?? '/assets/img/products/1.png')) }}" alt="{{ $pItem->product_name }}" style="width: 44px; height: 44px; object-fit: cover;" class="rounded border bg-white">
                              <div class="flex-grow-1 text-truncate">
                                <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.8rem;">{{ $pItem->product_name }}</h6>
                                <div class="text-muted" style="font-size: 0.72rem;">
                                  <span>Đơn: <strong class="text-dark font-monospace">{{ $pItem->order->order_code ?? '' }}</strong></span>
                                  @if($pItem->color || $pItem->size)
                                    <span> • {{ $pItem->color ?? '' }} / {{ $pItem->size ?? '' }}</span>
                                  @endif
                                </div>
                              </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-1 border-top">
                              <span class="text-success small" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-circle-check me-1"></i> Đã hoàn tất
                              </span>
                              <button type="button" onclick="openQuickReviewModal({{ $pItem->product_id }})" class="btn btn-sm btn-bee-primary py-1 px-3 fw-bold text-nowrap" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá ngay
                              </button>
                            </div>
                          </div>
                        @endforeach
                      </div>

                    @else
                      <div class="text-center py-4">
                        <i class="fa-solid fa-circle-check fs-2 text-success mb-2"></i>
                        <p class="small text-dark fw-bold mb-1">Tuyệt vời!</p>
                        <p class="small text-muted mb-0">Bạn đã đánh giá tất cả các sản phẩm đã mua.</p>
                      </div>
                    @endif
                  </div>

                  <!-- TAB 3: CHỈ ĐƠN HÀNG GẦN ĐÂY -->
                  <div class="tab-pane fade p-3" id="notif-orders" role="tabpanel">
                    @if(isset($recentCustomerOrders) && $recentCustomerOrders->count() > 0)
                      <div class="d-flex flex-column gap-2">
                        @foreach($recentCustomerOrders as $rOrder)
                          <div class="p-2.5 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                            <div>
                              <strong class="text-dark font-monospace" style="font-size: 0.8rem;">{{ $rOrder->order_code }}</strong>
                              <div class="text-muted" style="font-size: 0.72rem;">
                                {{ number_format($rOrder->total_amount, 0, ',', '.') }}₫ • {{ $rOrder->created_at ? $rOrder->created_at->format('d/m/Y') : '' }}
                              </div>
                            </div>
                            <div class="text-end">
                              @if($rOrder->shipping_status === 'completed')
                                <span class="badge bg-success-subtle text-success" style="font-size: 0.7rem;">Hoàn tất</span>
                              @elseif($rOrder->shipping_status === 'delivered')
                                <span class="badge bg-success-subtle text-success" style="font-size: 0.7rem;">Đã giao</span>
                              @elseif($rOrder->shipping_status === 'shipping')
                                <span class="badge bg-warning-subtle text-dark" style="font-size: 0.7rem;">Đang giao</span>
                              @elseif($rOrder->shipping_status === 'cancelled')
                                <span class="badge bg-danger-subtle text-danger" style="font-size: 0.7rem;">Đã hủy</span>
                              @else
                                <span class="badge bg-info-subtle text-info" style="font-size: 0.7rem;">Đang xử lý</span>
                              @endif
                              <a href="{{ route('client.order-tracking', ['code' => $rOrder->order_code]) }}" class="d-block text-secondary small text-decoration-none mt-1 fw-bold" style="font-size: 0.7rem;">
                                Tra cứu <i class="fa-solid fa-arrow-right"></i>
                              </a>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    @else
                      <div class="text-center py-4">
                        <i class="fa-solid fa-box-open fs-2 text-muted mb-2"></i>
                        <p class="small text-muted mb-0">Bạn chưa có đơn hàng nào gần đây.</p>
                      </div>
                    @endif
                  </div>

                </div>

                <!-- Footer -->
                <div class="p-2.5 bg-light text-center border-top">
                  <a href="{{ route('client.profile') }}" class="small text-danger fw-bold text-decoration-none">
                    Xem tất cả trong Hồ sơ cá nhân <i class="fa-solid fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
          @endauth



          <!-- USER DROPDOWN -->
          @auth
            <div class="dropdown">
              <button class="btn bee-icon-btn d-flex align-items-center gap-2 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản của tôi">
                <img src="{{ asset(Auth::user()->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                <span class="d-none d-md-inline small fw-semibold text-dark">{{ Str::limit(Auth::user()->name, 12) }}</span>
                <i class="fa-solid fa-chevron-down fs-10 text-muted"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-3 mt-2" style="border-radius: 12px; min-width: 240px;">
                <li class="pb-2 mb-2 border-bottom">
                  <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                  <div class="small text-muted">{{ Auth::user()->email }}</div>
                  <div class="d-flex align-items-center gap-1 mt-1">
                    <span class="badge bg-danger-subtle text-danger fs-10 fw-bold">{{ Auth::user()->rank ?? 'Thành viên' }}</span>
                    <span class="badge bg-light text-dark fs-10 border"><i class="fa-solid fa-circle-check text-success me-1"></i>Đã xác thực</span>
                  </div>
                </li>
                @if(Auth::user()->isAdmin())
                  <li>
                    <a class="dropdown-item py-2 rounded-2 text-danger fw-bold" href="{{ route('admin.dashboard') }}">
                      <i class="fa-solid fa-gauge-high me-2"></i> Bảng Quản Trị Admin
                    </a>
                  </li>
                @endif
                 <li>
                  <a class="dropdown-item py-2 rounded-2" href="{{ route('client.wishlist.index') }}">
                    <i class="fa-solid fa-heart me-2 text-danger"></i> Sản Phẩm Yêu Thích
                    @if(\App\Services\WishlistService::count() > 0)
                      <span class="badge bg-danger ms-1 small">{{ \App\Services\WishlistService::count() }}</span>
                    @endif
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2 rounded-2" href="{{ route('client.profile') }}">
                    <i class="fa-regular fa-user me-2 text-muted"></i> Hồ Sơ &amp; Đơn Hàng
                    @if(isset($pendingReviewItems) && $pendingReviewItems->count() > 0)
                      <span class="badge bg-danger ms-1 small">{{ $pendingReviewItems->count() }}</span>
                    @endif
                  </a>
                </li>
                <li>
                  <a class="dropdown-item py-2 rounded-2" href="{{ route('client.order-tracking') }}">
                    <i class="fa-solid fa-truck-fast me-2 text-muted"></i> Tra Cứu Đơn Hàng
                  </a>
                </li>
                <li class="pt-2 mt-2 border-top">
                  <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 rounded-2 text-danger">
                      <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng Xuất
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('auth.login') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold">
              <i class="fa-regular fa-user me-1"></i> Đăng Nhập
            </a>
          @endauth

          <!-- Wishlist (Sản phẩm yêu thích) -->
          @auth
            <a href="{{ route('client.wishlist.index') }}" class="bee-icon-btn position-relative" title="Sản phẩm yêu thích">
              <i class="fa-solid fa-heart text-danger"></i>
              <span class="bee-badge-count bg-danger" id="wishlistCountBadge" style="display: {{ \App\Services\WishlistService::count() > 0 ? 'flex' : 'none' }};">
                {{ \App\Services\WishlistService::count() }}
              </span>
            </a>
          @else
            <a href="javascript:void(0)" onclick="requireAuthPrompt('xem danh sách sản phẩm yêu thích')" class="bee-icon-btn position-relative" title="Sản phẩm yêu thích">
              <i class="fa-solid fa-heart text-danger"></i>
            </a>
          @endauth

          <!-- Shop -->
          <a href="{{ route('client.products.index') }}" class="bee-icon-btn" title="Tất cả sản phẩm">
            <i class="fa-solid fa-store"></i>
          </a>

          <!-- Cart -->
          @auth
            <a href="{{ route('client.cart') }}" class="bee-icon-btn position-relative" title="Giỏ hàng">
              <i class="fa-solid fa-bag-shopping"></i>
              <span class="bee-badge-count" id="cartCountBadge">{{ \App\Services\CartService::count() }}</span>
            </a>
          @else
            <a href="javascript:void(0)" onclick="requireAuthPrompt('xem giỏ hàng và đặt mua sản phẩm')" class="bee-icon-btn position-relative" title="Giỏ hàng">
              <i class="fa-solid fa-bag-shopping"></i>
            </a>
          @endauth



          <!-- Mobile Toggle Button -->
          <button class="navbar-toggler d-lg-none bee-icon-btn ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#beeMainNav">
            <i class="fa-solid fa-bars"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- CATEGORY & BRAND NAVIGATION BAR -->
    <nav class="navbar navbar-expand-lg py-2 mt-2 border-top">
      <div class="container">
        <div class="collapse navbar-collapse" id="beeMainNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1 align-items-center">
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->routeIs('client.home') ? 'active' : '' }}" href="{{ route('client.home') }}">
                TRANG CHỦ
              </a>
            </li>

            <!-- Áo Polo Nam -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-polo-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}">
                Áo Polo
              </a>
            </li>

            <!-- Áo Sơ Mi Nam -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-so-mi-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}">
                Áo Sơ Mi
              </a>
            </li>

            <!-- Áo Phông & T-Shirt -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-phong-tshirt-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-phong-tshirt-nam']) }}">
                Áo Phông
              </a>
            </li>

            <!-- Áo Khoác & Blazer -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-khoac-blazer-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}">
                Áo Khoác &amp; Blazer
              </a>
            </li>

            <!-- Áo Thun Nam -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-thun-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}">
                Áo Thun
              </a>
            </li>

            <!-- Áo Thu Đông -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-thu-dong-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-thu-dong-nam']) }}">
                Áo Thu Đông
              </a>
            </li>


            <!-- Thương Hiệu (Brands) -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->routeIs('client.brands.*') ? 'active' : '' }}" href="{{ route('client.brands.index') }}">
                <i class="fa-solid fa-crown me-1 text-warning"></i> Thương Hiệu
              </a>
            </li>

            <!-- Ưu Đãi Trong Ngày (Daily Deals) -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link text-danger fw-bold {{ request()->routeIs('client.daily-deals.*') ? 'active' : '' }}" href="{{ route('client.daily-deals.index') }}">
                <i class="fa-solid fa-bolt me-1 text-danger"></i> ƯU ĐÃI TRONG NGÀY
                <span class="badge bg-danger ms-1 text-white fs-11 px-1.5 py-0.5 rounded-pill shadow-xs">HOT</span>
              </a>
            </li>
          </ul>

          <div class="d-flex align-items-center gap-3">
            <span class="badge bg-danger-subtle text-danger px-3 py-2 fw-semibold rounded-pill">
              <i class="fa-solid fa-tags me-1"></i> VOUCHER: BEESTYLEVIP
            </span>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- MAIN CONTENT -->
  <main class="bee-main-content">
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="bee-footer pt-5 pb-3 mt-5">
    <div class="container">
      <div class="row g-4 pb-4 border-bottom border-secondary-subtle">
        <!-- Brand Info -->
        <div class="col-lg-4 col-md-6">
          <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bee-logo-icon">
              <i class="fa-solid fa-b"></i>
            </div>
            <span class="fs-4 fw-black tracking-wide text-white">BEE<span class="text-warning">STYLE</span></span>
          </div>
          <p class="text-secondary small mb-3">
            Thương hiệu thời trang áo nam cao cấp hàng đầu Việt Nam. Định hình phong cách lịch lãm, hiện đại và trẻ trung cho phái mạnh với chất lượng vượt trội.
          </p>
          <div class="d-flex gap-2">
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-youtube"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6 col-6">
          <h6 class="text-white fw-bold mb-3">DANH MỤC ÁO NAM</h6>
          <ul class="list-unstyled d-flex flex-column gap-2 small">
            <li><a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}">Áo Polo Nam</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}">Áo Sơ Mi Nam</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'ao-phong-tshirt-nam']) }}">Áo Phông (T-Shirt)</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}">Áo Khoác &amp; Blazer</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}">Áo Thun Nam</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'ao-thu-dong-nam']) }}">Áo Thu Đông</a></li>
          </ul>
        </div>

        <!-- Customer Service -->
        <div class="col-lg-3 col-md-6 col-6">
          <h6 class="text-white fw-bold mb-3">CHĂM SÓC KHÁCH HÀNG</h6>
          <ul class="list-unstyled d-flex flex-column gap-2 small">
            <li><a href="{{ route('client.order-tracking') }}">Tra cứu hành trình đơn hàng</a></li>
            <li><a href="#">Chính sách đổi trả trong 30 ngày</a></li>
            <li><a href="#">Bảng quy đổi Size nam chuẩn</a></li>
            <li><a href="#">Chính sách chăm sóc khách hàng</a></li>
            <li><a href="#">Hệ thống cửa hàng toàn quốc</a></li>
            <li><a href="#">Bảo mật thông tin khách hàng</a></li>
          </ul>
        </div>

        <!-- Contact & Newsletter -->
        <div class="col-lg-3 col-md-6">
          <h6 class="text-white fw-bold mb-3">NHẬN BẢN TIN ƯU ĐÃI</h6>
          <p class="text-secondary small mb-3">Đăng ký email để nhận mã giảm giá <strong>50.000₫</strong> cho đơn hàng đầu tiên.</p>
          <div class="input-group mb-3">
            <input type="email" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Nhập email của bạn...">
            <button class="btn btn-bee-accent btn-sm px-3" type="button">ĐĂNG KÝ</button>
          </div>
          <div class="mt-3">
            <p class="small text-secondary mb-1">Phương thức thanh toán bảo mật:</p>
            <div class="d-flex gap-2 flex-wrap text-white-50 fs-4">
              <i class="fa-brands fa-cc-visa text-white"></i>
              <i class="fa-brands fa-cc-mastercard text-white"></i>
              <i class="fa-solid fa-qrcode text-warning" title="VietQR"></i>
              <i class="fa-solid fa-wallet text-info" title="Ví MoMo/VNPAY"></i>
              <i class="fa-solid fa-hand-holding-dollar text-success" title="COD"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="bee-footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="text-secondary">
          &copy; {{ date('Y') }} <strong>BEESTYLE MENSWEAR</strong> - Always Be Casual. All Rights Reserved.
        </div>
        <div class="d-flex gap-3 text-secondary small">
          <a href="#">Điều khoản sử dụng</a>
          <span>•</span>
          <a href="#">Chính sách giao hàng</a>
          <span>•</span>
          <a href="#">Hotline: 1900 8888</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- MODAL THÔNG BÁO ĐƠN HÀNG HOÀN TẤT (CHỈ HIỂN THỊ 1 LẦN DUY NHẤT CHO MỖI ĐƠN HÀNG) -->
  @auth
    @if(isset($unnotifiedReviewItems) && $unnotifiedReviewItems->count() > 0)
      @php
        $notifiedOrderIds = $unnotifiedReviewItems->pluck('order_id')->unique()->values()->toArray();
      @endphp
      <div class="modal fade" id="reviewPromptModal" tabindex="-1" aria-labelledby="reviewPromptModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header border-0 pb-0 text-white position-relative" style="background: linear-gradient(135deg, #111827, #1f2937); padding: 24px 24px 16px;">
              <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; min-width: 48px;">
                  <i class="fa-solid fa-gift fs-4"></i>
                </div>
                <div>
                  <span class="badge bg-warning text-dark fw-bold px-2 py-0.5 rounded-pill small mb-1">ĐƠN HÀNG HOÀN TẤT</span>
                  <h5 class="modal-title fw-bold text-white mb-0" id="reviewPromptModalLabel">Bạn Có Sản Phẩm Chờ Đánh Giá!</h5>
                </div>
              </div>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="markReviewNotified()"></button>
            </div>
            
            <div class="modal-body p-4">
              <div class="alert alert-warning border-0 p-2.5 rounded-3 mb-3 d-flex align-items-center gap-2 small" style="background: #fffbeb;">
                <i class="fa-solid fa-heart text-danger fs-5"></i>
                <div>
                  <strong>Cảm ơn bạn đã mua sắm tại BeeStyle!</strong> Ý kiến đánh giá của bạn là nguồn động lực lớn để chúng tôi ngày càng hoàn thiện.
                </div>
              </div>

              <p class="small text-muted mb-3">Đơn hàng của bạn đã được giao thành công. Hãy chia sẻ cảm nhận về chất lượng và độ vừa vặn của sản phẩm:</p>

              <div class="d-flex flex-column gap-2 mb-3" style="max-height: 250px; overflow-y: auto;">
                @foreach($unnotifiedReviewItems as $pItem)
                  <div class="d-flex align-items-center justify-content-between p-2.5 bg-light rounded-3 border">
                    <div class="d-flex align-items-center gap-3">
                      <img src="{{ asset($pItem->image ?? ($pItem->product->image ?? '/assets/img/products/1.png')) }}" alt="{{ $pItem->product_name }}" style="width: 48px; height: 48px; object-fit: cover;" class="rounded border bg-white">
                      <div>
                        <h6 class="small fw-bold text-dark mb-0 text-truncate" style="max-width: 200px;">{{ $pItem->product_name }}</h6>
                        <small class="text-muted">Đơn: <span class="font-monospace fw-semibold">{{ $pItem->order->order_code ?? '' }}</span></small>
                      </div>
                    </div>
                    <button type="button" onclick="const modal = bootstrap.Modal.getInstance(document.getElementById('reviewPromptModal')); if(modal) modal.hide(); markReviewNotified(); openQuickReviewModal({{ $pItem->product_id }});" class="btn btn-bee-primary btn-sm px-3 fw-bold text-nowrap" style="font-size: 0.8rem;">
                      <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá
                    </button>
                  </div>
                @endforeach
              </div>
            </div>


            <div class="modal-footer border-top bg-light py-2.5 px-4 d-flex justify-content-between align-items-center">
              <a href="{{ route('client.profile') }}" onclick="markReviewNotified()" class="small text-muted text-decoration-underline">Xem trong lịch sử đơn hàng</a>
              <button type="button" class="btn btn-secondary btn-sm px-3 rounded-2" data-bs-dismiss="modal" onclick="markReviewNotified()">
                Đã hiểu / Để sau
              </button>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endauth

  <!-- QUICK PRODUCT REVIEW MODAL (HIỆN THÔNG TIN SẢN PHẨM & ĐÁNH GIÁ TRỰC QUAN) -->
  <div class="modal fade" id="quickProductReviewModal" tabindex="-1" aria-labelledby="quickProductReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #111827, #1f2937); padding: 18px 24px;">
          <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-star text-warning fs-5"></i>
            <h5 class="modal-title fw-bold text-white mb-0" id="quickProductReviewModalLabel">Đánh Giá &amp; Nhận Xét Sản Phẩm</h5>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto;">
          <!-- Loading Spinner -->
          <div id="qrmLoading" class="text-center py-5">
            <div class="spinner-border text-warning" role="status">
              <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="small text-muted mt-2 mb-0">Đang tải thông tin sản phẩm và các đánh giá...</p>
          </div>

          <div id="qrmContent" style="display: none;">
            <!-- Product Header Card -->
            <div class="p-3 bg-light rounded-3 border mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
              <div class="d-flex align-items-center gap-3">
                <img id="qrmProductImg" src="" alt="product" style="width: 60px; height: 60px; object-fit: cover;" class="rounded border bg-white shadow-sm">
                <div>
                  <span class="badge bg-danger-subtle text-danger fs-11 fw-bold" id="qrmProductCat">Áo Polo Nam</span>
                  <h6 class="fw-bold text-dark mb-1 mt-0.5" id="qrmProductName">Tên sản phẩm</h6>
                  <div class="d-flex align-items-center gap-2 small">
                    <span class="text-danger fw-bold fs-6" id="qrmProductPrice">0₫</span>
                    <span class="text-muted">|</span>
                    <span class="text-warning small" id="qrmProductRating">
                      <i class="fa-solid fa-star"></i> <strong>5.0</strong> (0 nhận xét)
                    </span>
                  </div>
                </div>
              </div>
              <a href="#" id="qrmProductLink" target="_blank" class="btn btn-sm btn-outline-dark fw-bold">
                Xem trang SP <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
              </a>
            </div>

            <!-- Alert status message -->
            <div id="qrmAlertBox" style="display: none;" class="alert alert-success border-0 py-2.5 px-3 rounded-3 mb-3 small"></div>

            <!-- Notice when not purchased -->
            <div id="qrmUnpurchasedNotice" class="alert alert-warning border-0 small p-3 rounded-3 mb-4 d-flex align-items-center gap-2.5 shadow-xs" style="display: none;">
              <i class="fa-solid fa-shield-halved text-warning fs-4 flex-shrink-0"></i>
              <div>
                <strong class="text-dark d-block">Xác thực người mua hàng:</strong>
                <span class="text-muted" style="font-size: 0.8rem;">Chỉ tài khoản khách hàng đã từng mua sản phẩm này tại BeeStyle mới có thể viết đánh giá xác thực.</span>
              </div>
            </div>

            <!-- Review Form -->
            <div id="qrmFormCard" class="card border-0 p-3.5 rounded-3 mb-4 shadow-sm" style="background: #ffffff; border: 1px solid var(--atino-border) !important;">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-size: 0.85rem;">
                  <i class="fa-solid fa-pen-nib text-danger me-1.5"></i> <span id="qrmFormTitle">Viết Đánh Giá Của Bạn</span>
                </h6>
                <span class="badge bg-light text-dark border fw-semibold"><i class="fa-solid fa-heart text-danger me-1"></i> Đóng góp ý kiến</span>
              </div>

              <form id="qrmForm">
                <input type="hidden" id="qrmProductId" value="">
                
                <!-- Star selection -->
                <div class="mb-3">
                  <label class="form-label small fw-semibold text-dark mb-1">1. Đánh giá độ hài lòng của bạn:</label>
                  <div class="d-flex align-items-center gap-2 flex-wrap" id="qrmStarGroup">
                    <input type="radio" class="btn-check" name="qrm_rating" id="qrm_star_5" value="5" checked>
                    <label class="btn btn-sm btn-outline-warning text-dark fw-bold px-2.5 py-1" for="qrm_star_5">5 <i class="fa-solid fa-star text-warning"></i> Tuyệt vời</label>

                    <input type="radio" class="btn-check" name="qrm_rating" id="qrm_star_4" value="4">
                    <label class="btn btn-sm btn-outline-warning text-dark fw-bold px-2.5 py-1" for="qrm_star_4">4 <i class="fa-solid fa-star text-warning"></i> Hài lòng</label>

                    <input type="radio" class="btn-check" name="qrm_rating" id="qrm_star_3" value="3">
                    <label class="btn btn-sm btn-outline-warning text-dark fw-bold px-2.5 py-1" for="qrm_star_3">3 <i class="fa-solid fa-star text-warning"></i> Bình thường</label>

                    <input type="radio" class="btn-check" name="qrm_rating" id="qrm_star_2" value="2">
                    <label class="btn btn-sm btn-outline-warning text-dark fw-bold px-2.5 py-1" for="qrm_star_2">2 <i class="fa-solid fa-star text-warning"></i> Chưa ưng</label>

                    <input type="radio" class="btn-check" name="qrm_rating" id="qrm_star_1" value="1">
                    <label class="btn btn-sm btn-outline-warning text-dark fw-bold px-2.5 py-1" for="qrm_star_1">1 <i class="fa-solid fa-star text-warning"></i> Kém</label>
                  </div>
                </div>

                <!-- Comment -->
                <div class="mb-3">
                  <label class="form-label small fw-semibold text-dark mb-1">2. Chia sẻ cảm nhận chi tiết (chất vải, form dáng, độ vừa vặn):</label>
                  <textarea id="qrmComment" class="form-control form-control-sm" rows="3" placeholder="Ví dụ: Vải cotton dệt tổ ong mặc rất mát, form áo lên chuẩn dáng, đường may rất chắc chắn..." required></textarea>
                </div>

                <!-- 3. Photo Upload with Live Preview -->
                <div class="mb-3">
                  <label class="form-label small fw-semibold text-dark mb-1 d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-camera text-warning me-1"></i> 3. Đính kèm ảnh thực tế:</span>
                    <span class="text-muted fs-11" id="qrmImgCount">Tối đa 5 ảnh</span>
                  </label>
                  <div class="p-2.5 rounded-3 border bg-light text-center" style="border: 2px dashed #cbd5e1 !important; cursor: pointer;" onclick="document.getElementById('qrmImagesInput').click()">
                    <i class="fa-solid fa-cloud-arrow-up text-warning fs-3 mb-1 d-block"></i>
                    <span class="small text-dark fw-bold d-block">Bấm để chọn hoặc kéo thả ảnh chụp áo</span>
                    <small class="text-muted fs-11">Hỗ trợ JPG, PNG, WEBP (tối đa 5MB/ảnh)</small>
                    <input type="file" id="qrmImagesInput" class="d-none" multiple accept="image/*" onchange="previewReviewPhotos(this, 'qrmImagesPreview')">
                  </div>
                  <div id="qrmImagesPreview" class="d-flex gap-2 flex-wrap mt-2"></div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                  <button type="submit" id="qrmSubmitBtn" class="btn btn-bee-primary btn-sm px-4 py-2 fw-bold">
                    <i class="fa-solid fa-paper-plane me-1"></i> <span id="qrmBtnText">GỬI ĐÁNH GIÁ NGAY</span>
                  </button>
                  <small class="text-muted" style="font-size: 0.75rem;">BeeStyle bảo mật &amp; trân trọng mọi góp ý</small>
                </div>
              </form>
            </div>

            <!-- Reviews List Section -->
            <div>
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-size: 0.85rem;">
                  <i class="fa-solid fa-comments text-warning me-1.5"></i> Đánh Giá Từ Khách Hàng Đã Mua (<span id="qrmReviewsCount">0</span>)
                </h6>
              </div>

              <div id="qrmReviewsList" class="d-flex flex-column gap-2.5">
                <!-- Dynamically populated reviews -->
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- GLOBAL REVIEW PHOTO LIGHTBOX MODAL -->
  <div class="modal fade" id="reviewPhotoLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-transparent border-0 text-center position-relative">
        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
        <img id="reviewPhotoLightboxImg" src="" class="img-fluid rounded-4 shadow-lg mx-auto" style="max-height: 85vh; object-fit: contain; background: rgba(0,0,0,0.85); border: 2px solid rgba(255,255,255,0.2);">
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
  <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('vendors/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
  <script>
    if (typeof feather !== 'undefined') {
      feather.replace();
    }

    // Hàm mở Modal phóng to ảnh đánh giá (Global Lightbox)
    function openReviewImageLightbox(imgSrc) {
      if (!imgSrc) return;
      const lightboxModal = document.getElementById('reviewPhotoLightboxModal');
      const lightboxImg = document.getElementById('reviewPhotoLightboxImg');
      if (lightboxModal && lightboxImg) {
        lightboxImg.src = imgSrc;
        bootstrap.Modal.getOrCreateInstance(lightboxModal).show();
      }
    }

    // Hàm xem trước ảnh tải lên cho Form đánh giá
    function previewReviewPhotos(input, previewContainerId) {
      const container = document.getElementById(previewContainerId);
      if (!container) return;
      container.innerHTML = '';

      if (input.files && input.files.length > 0) {
        const maxFiles = Math.min(input.files.length, 5);
        for (let i = 0; i < maxFiles; i++) {
          const file = input.files[i];
          const reader = new FileReader();
          reader.onload = function (e) {
            const wrapper = document.createElement('div');
            wrapper.className = 'position-relative';
            wrapper.innerHTML = `
              <img src="${e.target.result}" class="rounded border shadow-xs" style="width: 58px; height: 58px; object-fit: cover;">
            `;
            container.appendChild(wrapper);
          };
          reader.readAsDataURL(file);
        }
      }
    }

    // Hàm hiển thị nhanh đánh giá trực tiếp vào danh sách trong Modal
    function renderReviewDirectlyInModal(r, totalCount) {
      const listEl = document.getElementById('qrmReviewsList');
      if (!listEl || !r) return;

      if (totalCount !== undefined) {
        const countEl = document.getElementById('qrmReviewsCount');
        if (countEl) countEl.textContent = totalCount;
      }

      let starsHtml = '';
      const rRating = parseInt(r.rating) || 5;
      for (let i = 1; i <= 5; i++) {
        starsHtml += `<i class="fa-solid fa-star ${i <= rRating ? 'text-warning' : 'text-secondary-subtle'}"></i>`;
      }

      let photosHtml = '';
      if (r.images && r.images.length > 0) {
        photosHtml += '<div class="d-flex gap-2 flex-wrap mt-2 pt-2 border-top border-secondary border-opacity-10">';
        r.images.forEach(photo => {
          photosHtml += `
            <div class="position-relative" style="cursor: pointer;" onclick="openReviewImageLightbox('${photo}')">
              <img src="${photo}" alt="Ảnh đánh giá" class="rounded border shadow-xs" style="width: 60px; height: 60px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
              <span class="position-absolute bottom-0 end-0 bg-dark text-white px-1 py-0.5 rounded-start" style="font-size: 0.6rem; opacity: 0.85;">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
              </span>
            </div>
          `;
        });
        photosHtml += '</div>';
      }

      const itemDiv = document.createElement('div');
      itemDiv.className = 'p-3 bg-light rounded-3 border';
      itemDiv.id = 'qrm-review-item-' + (r.id || 'new');
      itemDiv.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-1.5 flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2">
            <img src="${r.user_avatar}" alt="${r.user_name}" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
            <div>
              <strong class="text-dark small d-block leading-none">${r.user_name}</strong>
              <span class="badge bg-success-subtle text-success py-0 px-1" style="font-size: 0.65rem;">
                <i class="fa-solid fa-circle-check"></i> Đã mua hàng
              </span>
            </div>
          </div>
          <small class="text-muted" style="font-size: 0.72rem;">${r.time_ago || 'Vừa xong'}</small>
        </div>
        <div class="text-warning small mb-1.5">
          ${starsHtml} <span class="text-dark fw-bold ms-1">(${rRating}/5)</span>
        </div>
        <p class="small text-dark mb-0 fst-italic leading-relaxed" style="font-size: 0.8rem;">
          "${r.comment}"
        </p>
        ${photosHtml}
      `;

      // Xóa empty message nếu có
      if (listEl.querySelector('.fa-comment-dots')) {
        listEl.innerHTML = '';
      }

      const existingEl = document.getElementById('qrm-review-item-' + r.id);
      if (existingEl) {
        existingEl.replaceWith(itemDiv);
      } else {
        listEl.insertBefore(itemDiv, listEl.firstChild);
      }
    }

    // Hàm mở Modal Đánh Giá & Xem Nhận Xét Sản Phẩm Nhanh
    function openQuickReviewModal(productId, isSilent = false) {
      const modalEl = document.getElementById('quickProductReviewModal');
      if (!modalEl) return;

      const pId = parseInt(productId) || 1;
      const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
      const loadingEl = document.getElementById('qrmLoading');
      const contentEl = document.getElementById('qrmContent');
      const alertBox = document.getElementById('qrmAlertBox');
      const imgPreview = document.getElementById('qrmImagesPreview');
      const imgInput = document.getElementById('qrmImagesInput');

      if (!isSilent) {
        if (imgInput) imgInput.value = '';
        if (imgPreview) imgPreview.innerHTML = '';

        loadingEl.innerHTML = `
          <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Đang tải...</span>
          </div>
          <p class="small text-muted mt-2 mb-0">Đang tải thông tin sản phẩm và các đánh giá...</p>
        `;
        loadingEl.style.display = 'block';
        contentEl.style.display = 'none';
        alertBox.style.display = 'none';
        bsModal.show();
      }

      // Fetch dữ liệu sản phẩm & toàn bộ đánh giá bằng đường dẫn tuyệt đối theo Window Origin
      const fetchUrl = window.location.origin + "/san-pham/" + pId + "/danh-gia-chi-tiet";
      fetch(fetchUrl, {
        headers: { 
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => {
        if (!res.ok) throw new Error('HTTP error ' + res.status);
        return res.json();
      })
      .then(data => {
        if (data && data.success) {
          const p = data.product;
          const pIdEl = document.getElementById('qrmProductId');
          if (pIdEl) pIdEl.value = p.id;
          const pNameEl = document.getElementById('qrmProductName');
          if (pNameEl) pNameEl.textContent = p.name || '';
          const pImgEl = document.getElementById('qrmProductImg');
          if (pImgEl) pImgEl.src = p.image || '';
          const pPriceEl = document.getElementById('qrmProductPrice');
          if (pPriceEl) pPriceEl.textContent = p.price || '';
          const pCatEl = document.getElementById('qrmProductCat');
          if (pCatEl) pCatEl.textContent = p.category_name || '';
          const pLinkEl = document.getElementById('qrmProductLink');
          if (pLinkEl) pLinkEl.href = p.url || '#';
          const pRatingEl = document.getElementById('qrmProductRating');
          if (pRatingEl) pRatingEl.innerHTML = `<i class="fa-solid fa-star"></i> <strong>${p.rating}</strong> (${p.reviews_count} nhận xét)`;
          const pRevCountEl = document.getElementById('qrmReviewsCount');
          if (pRevCountEl) pRevCountEl.textContent = data.reviews ? data.reviews.length : 0;

          // Kiểm tra quyền đánh giá (chỉ khách đã mua mới thấy form nhập)
          const formCard = document.getElementById('qrmFormCard');
          const noticeCard = document.getElementById('qrmUnpurchasedNotice');

          if (data.user_has_purchased) {
            if (formCard) formCard.style.display = 'block';
            if (noticeCard) noticeCard.style.display = 'none';

            const fTitle = document.getElementById('qrmFormTitle');
            const fBtn = document.getElementById('qrmBtnText');
            const fComment = document.getElementById('qrmComment');

            if (data.user_review) {
              if (fTitle) fTitle.textContent = 'Cập Nhật Đánh Giá & Hình Ảnh';
              if (fBtn) fBtn.textContent = 'CẬP NHẬT ĐÁNH GIÁ';
              if (fComment) fComment.value = data.user_review.comment || '';
              const starRadio = document.querySelector(`input[name="qrm_rating"][value="${data.user_review.rating}"]`);
              if (starRadio) starRadio.checked = true;

              // Render existing user photos
              if (data.user_review.images && data.user_review.images.length > 0 && imgPreview) {
                imgPreview.innerHTML = '';
                data.user_review.images.forEach(img => {
                  imgPreview.innerHTML += `<img src="${img}" class="rounded border shadow-xs" style="width: 58px; height: 58px; object-fit: cover;">`;
                });
              }
            } else {
              if (fTitle) fTitle.textContent = 'Viết Đánh Giá & Đính Kèm Ảnh';
              if (fBtn) fBtn.textContent = 'GỬI ĐÁNH GIÁ NGAY';
              if (!isSilent) {
                if (fComment) fComment.value = '';
                const star5 = document.getElementById('qrm_star_5');
                if (star5) star5.checked = true;
              }
            }
          } else {
            if (formCard) formCard.style.display = 'none';
            if (noticeCard) noticeCard.style.display = 'flex';
          }

          // Render danh sách đánh giá của các khách hàng khác
          const listEl = document.getElementById('qrmReviewsList');
          if (listEl) {
            listEl.innerHTML = '';

            if (data.reviews && data.reviews.length > 0) {
              data.reviews.forEach(r => {
                renderReviewDirectlyInModal(r);
              });
            } else {
              listEl.innerHTML = `
                <div class="text-center py-4 bg-light rounded-3 border">
                  <i class="fa-regular fa-comment-dots fs-2 text-muted mb-2 d-block"></i>
                  <p class="small text-muted mb-0">Chưa có đánh giá nào khác. Hãy là người đầu tiên nhận xét sản phẩm này!</p>
                </div>
              `;
            }
          }

          if (loadingEl) loadingEl.style.display = 'none';
          if (contentEl) contentEl.style.display = 'block';
        } else {
          throw new Error('Dữ liệu không hợp lệ');
        }
      })
      .catch(err => {
        console.error('Error fetching review data:', err);
        if (!isSilent && contentEl && contentEl.style.display !== 'block' && loadingEl) {
          loadingEl.innerHTML = `
            <div class="text-center py-4">
              <i class="fa-solid fa-circle-exclamation text-danger fs-2 mb-2 d-block"></i>
              <p class="text-dark fw-semibold small mb-2">Chưa thể tải dữ liệu đánh giá sản phẩm</p>
              <button type="button" class="btn btn-sm btn-outline-dark px-3 fw-bold rounded-pill" onclick="openQuickReviewModal(${pId})">
                <i class="fa-solid fa-rotate-right me-1"></i> Bấm để thử lại
              </button>
            </div>
          `;
        }
      });
    }

    // Xử lý gửi Form Đánh Giá AJAX (kèm hỗ trợ tải lên file ảnh FormData) trong Modal
    document.addEventListener("DOMContentLoaded", function () {
      const qrmForm = document.getElementById('qrmForm');
      if (qrmForm) {
        qrmForm.addEventListener('submit', function (e) {
          e.preventDefault();
          const pIdInput = document.getElementById('qrmProductId');
          const productId = parseInt(pIdInput ? pIdInput.value : 1) || 1;
          const ratingInput = document.querySelector('input[name="qrm_rating"]:checked');
          const commentInput = document.getElementById('qrmComment');
          const comment = commentInput ? commentInput.value : '';
          const imgInput = document.getElementById('qrmImagesInput');
          const submitBtn = document.getElementById('qrmSubmitBtn');
          const alertBox = document.getElementById('qrmAlertBox');

          if (!ratingInput || !comment.trim()) return;

          if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang gửi đánh giá & ảnh...';
          }

          const formData = new FormData();
          formData.append('rating', ratingInput.value);
          formData.append('comment', comment);
          if (imgInput && imgInput.files && imgInput.files.length > 0) {
            const maxFiles = Math.min(imgInput.files.length, 5);
            for (let i = 0; i < maxFiles; i++) {
              formData.append('review_images[]', imgInput.files[i]);
            }
          }

          const postUrl = window.location.origin + "/san-pham/" + productId + "/danh-gia";
          const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
          formData.append('_token', csrfToken);

          fetch(postUrl, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
          })
          .then(async res => {
            const isJson = res.headers.get('content-type')?.includes('application/json');
            const data = isJson ? await res.json() : null;

            if (!res.ok) {
              const errMsg = (data && data.message) ? data.message : `Lỗi ${res.status}: Vui lòng kiểm tra lại thông tin gửi đánh giá`;
              throw new Error(errMsg);
            }

            return data;
          })
          .then(data => {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> CẬP NHẬT ĐÁNH GIÁ';
            }

            if (data && data.success) {
              if (alertBox) {
                alertBox.className = 'alert alert-success border-0 py-2.5 px-3 rounded-3 mb-3 small';
                alertBox.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> ${data.message}`;
                alertBox.style.display = 'block';
              }

              // Cập nhật title form
              const fTitle = document.getElementById('qrmFormTitle');
              if (fTitle) fTitle.textContent = 'Cập Nhật Đánh Giá & Hình Ảnh';
              const fBtn = document.getElementById('qrmBtnText');
              if (fBtn) fBtn.textContent = 'CẬP NHẬT ĐÁNH GIÁ';

              // Cập nhật rating và reviews_count trên header modal
              const pRating = document.getElementById('qrmProductRating');
              if (pRating && data.product_rating) {
                pRating.innerHTML = `<i class="fa-solid fa-star"></i> <strong>${data.product_rating}</strong> (${data.product_reviews_count} nhận xét)`;
              }

              // Render review ngay lập tức vào danh sách hiển thị
              if (data.review) {
                renderReviewDirectlyInModal(data.review, data.product_reviews_count);
                syncReviewToProfilePage(data.review, productId);
              }

              // Xóa input file đã gửi
              if (imgInput) imgInput.value = '';

              // Sync dữ liệu ngầm sau 500ms mà không làm giật modal
              setTimeout(() => {
                openQuickReviewModal(productId, true);
              }, 500);
            } else {
              if (alertBox) {
                alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
                alertBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> ${data?.message || 'Không thể gửi đánh giá'}`;
                alertBox.style.display = 'block';
              }
            }
          })
          .catch(err => {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> GỬI ĐÁNH GIÁ';
            }
            if (alertBox) {
              alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
              alertBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> ${err.message || 'Có lỗi xảy ra khi gửi đánh giá.'}`;
              alertBox.style.display = 'block';
            }
          });
        });
      }
    });

    // Hàm đồng bộ dữ liệu đánh giá vừa sửa sang Trang Hồ Sơ Tài Khoản
    function syncReviewToProfilePage(reviewData, productId) {
      if (!reviewData || !productId) return;

      const commentEl = document.getElementById('profile-rev-comment-' + productId);
      if (commentEl) {
        commentEl.textContent = `"${reviewData.comment}"`;
      }

      const starsEl = document.getElementById('profile-rev-stars-' + productId);
      if (starsEl) {
        let starsHtml = '';
        const rRating = parseInt(reviewData.rating) || 5;
        for (let i = 1; i <= 5; i++) {
          starsHtml += `<i class="fa-solid fa-star ${i <= rRating ? 'text-warning' : 'text-secondary-subtle'}"></i> `;
        }
        starsHtml += `<span class="fw-bold text-dark ms-1">(${rRating}/5)</span>`;
        starsEl.innerHTML = starsHtml;
      }

      const photosEl = document.getElementById('profile-rev-photos-' + productId);
      if (photosEl) {
        if (reviewData.images && reviewData.images.length > 0) {
          photosEl.classList.remove('d-none');
          photosEl.style.display = 'flex';
          photosEl.innerHTML = '';
          reviewData.images.forEach(photoUrl => {
            photosEl.innerHTML += `
              <div class="position-relative" style="cursor: pointer;" onclick="openReviewImageLightbox('${photoUrl}')">
                <img src="${photoUrl}" alt="Ảnh đánh giá" class="rounded border shadow-xs" style="width: 54px; height: 54px; object-fit: cover;">
                <span class="position-absolute bottom-0 end-0 bg-dark text-white px-1 py-0.5 rounded-start" style="font-size: 0.6rem; opacity: 0.85;">
                  <i class="fa-solid fa-magnifying-glass-plus"></i>
                </span>
              </div>
            `;
          });
        }
      }

      // Đổi nút Đơn Hàng sang "Xem / Sửa Đánh Giá"
      const orderBtn = document.getElementById('order-btn-review-' + productId);
      if (orderBtn) {
        orderBtn.className = 'btn btn-sm btn-outline-success py-0.5 px-2 mt-1 fw-bold text-nowrap';
        orderBtn.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Xem / Sửa Đánh Giá';
      }
    }

    // Hàm đánh dấu đã gửi thông báo 1 lần duy nhất trên Database & Trình duyệt
    function markReviewNotified() {
      @auth
        @if(isset($notifiedOrderIds) && count($notifiedOrderIds) > 0)
          const orderIds = @json($notifiedOrderIds);
          
          // Gửi request ngầm cập nhật Database review_notified = true
          fetch("{{ route('client.reviews.dismissNotification') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ order_ids: orderIds })
          }).catch(err => console.log(err));

          // Lưu vào localStorage
          let savedNotified = JSON.parse(localStorage.getItem('beestyle_notified_orders') || '[]');
          orderIds.forEach(id => {
            if (!savedNotified.includes(id)) savedNotified.push(id);
          });
          localStorage.setItem('beestyle_notified_orders', JSON.stringify(savedNotified));
        @endif
      @endauth
    }


    // Tự động mở Modal Thông báo Đánh giá đúng 1 LẦN DUY NHẤT khi khách hàng có đơn hoàn tất mới
    document.addEventListener("DOMContentLoaded", function () {
      @auth
        @if(isset($unnotifiedReviewItems) && $unnotifiedReviewItems->count() > 0)
          const orderIds = @json($notifiedOrderIds ?? []);
          const savedNotified = JSON.parse(localStorage.getItem('beestyle_notified_orders') || '[]');
          
          // Kiểm tra xem tất cả các đơn hàng này đã được thông báo 1 lần ở trình duyệt chưa
          const hasUnnotifiedInBrowser = orderIds.some(id => !savedNotified.includes(id));

          if (hasUnnotifiedInBrowser) {
            setTimeout(function () {
              const modalEl = document.getElementById('reviewPromptModal');
              if (modalEl) {
                const promptModal = new bootstrap.Modal(modalEl);
                promptModal.show();
                
                // Đánh dấu ngay khi modal đã xuất hiện
                markReviewNotified();
              }
            }, 800);
          }
        @endif

        // Hỗ trợ Hover và Click thông minh cho Trung tâm thông báo
        const notifDropdown = document.getElementById('beeNotificationDropdown');
        const notifBtn = document.getElementById('notifBellBtn');
        if (notifDropdown && notifBtn && typeof bootstrap !== 'undefined') {
          let hideTimeout = null;
          const bsDropdown = bootstrap.Dropdown.getOrCreateInstance(notifBtn);

          // Rê chuột vào để mở
          notifDropdown.addEventListener('mouseenter', function () {
            clearTimeout(hideTimeout);
            bsDropdown.show();
          });

          // Rê chuột ra ngoài có độ trễ 250ms tránh tắt đột ngột
          notifDropdown.addEventListener('mouseleave', function () {
            hideTimeout = setTimeout(function () {
              bsDropdown.hide();
            }, 250);
          });
        }
      @endauth
    });
  </script>


  <!-- ========================================== -->
  <!-- MODAL 1: CHỌN MÀU SẮC & THÔNG SỐ SIZE NHANH -->
  <!-- ========================================== -->
  <div class="modal fade" id="quickVariantModal" tabindex="-1" aria-hidden="true" style="z-index: 1080;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 22px; overflow: hidden;">
        
        <!-- Header -->
        <div class="modal-header border-0 pb-0 pt-3 px-4 d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">CHỌN PHÂN LOẠI</span>
            <span class="text-muted small" id="qvmCategoryName">Thời trang nam</span>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body p-4">
          <!-- THÔNG TIN SẢN PHẨM NHANH -->
          <div class="d-flex gap-3 mb-3 p-3 bg-light rounded-3 border">
            <div class="position-relative flex-shrink-0 bg-white rounded-3 p-2 border shadow-xs" style="width: 105px; height: 105px; display: flex; align-items: center; justify-content: center;">
              <img src="" id="qvmProductImage" alt="Sản phẩm" class="img-fluid rounded" style="max-height: 90px; object-fit: contain;">
              <span class="position-absolute top-0 start-0 badge bg-danger rounded-pill m-1 shadow-xs" id="qvmDiscountBadge" style="font-size: 0.68rem; display: none;">-15%</span>
            </div>
            <div class="flex-grow-1">
              <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2 py-0.5 small mb-1" id="qvmCategoryName">Thời trang nam</span>
              <h6 class="fw-bold text-dark mb-1" id="qvmProductName" style="font-size: 0.98rem; line-height: 1.35;">Tên sản phẩm</h6>
              <div class="d-flex align-items-baseline gap-2 mb-1.5">
                <span class="text-danger fw-bold fs-5" id="qvmProductPrice">0₫</span>
                <small class="text-muted text-decoration-line-through" id="qvmProductOriginalPrice" style="display: none;">0₫</small>
              </div>
              <div class="d-flex align-items-center gap-2 small">
                <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold" id="qvmStockBadge">
                  <i class="fa-solid fa-circle-check me-1"></i> Còn <strong id="qvmStockNumber">...</strong> trong kho
                </span>
                <span class="text-muted small">• Miễn phí đổi size 7 ngày</span>
              </div>
            </div>
          </div>

          <!-- 1. CHỌN MÀU SẮC (LUXURY COLOR SWATCHES) -->
          <div class="mb-3.5">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label small fw-bold text-dark mb-0">
                <i class="fa-solid fa-palette text-warning me-1"></i> 1. Chọn Màu Sắc:
                <span class="badge bg-dark text-warning border border-warning px-2 py-0.5 ms-1 fw-bold" id="qvmSelectedColorText">Chưa chọn</span>
              </label>
              <span class="text-danger small" style="font-size: 0.72rem;">* Bắt buộc</span>
            </div>
            <div class="d-flex flex-wrap gap-2" id="qvmColorsContainer">
              <!-- Render động các nút màu sắc cao cấp -->
            </div>
          </div>

          <!-- 2. CHỌN THÔNG SỐ KÍCH THƯỚC (SIZE MATRIX VỚI GỢI Ý CÂN NẶNG) -->
          <div class="mb-3.5">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label small fw-bold text-dark mb-0">
                <i class="fa-solid fa-ruler-combined text-warning me-1"></i> 2. Chọn Kích Thước (Size):
                <span class="badge bg-dark text-warning border border-warning px-2 py-0.5 ms-1 fw-bold" id="qvmSelectedSizeText">Chưa chọn</span>
              </label>
              <span class="text-muted small" style="font-size: 0.72rem;">
                <i class="fa-solid fa-arrows-alt-v me-0.5 text-warning"></i> Chuẩn Form Quý Ông
              </span>
            </div>
            <div class="d-flex flex-wrap gap-2" id="qvmSizesContainer">
              <!-- Render động các nút size cao cấp -->
            </div>
          </div>

          <!-- 3. CHỌN SỐ LƯỢNG MUA (1 - 10 SẢN PHẨM) -->
          <div class="mb-3 p-3 rounded-3 border" style="background: #f8fafc;">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label small fw-bold text-dark mb-0 d-flex align-items-center gap-1.5">
                <i class="fa-solid fa-calculator text-warning"></i>
                <span>3. Số Lượng Mua:</span>
              </label>
              <span class="badge bg-dark text-warning border border-warning px-2.5 py-1 fw-bold d-inline-flex align-items-center gap-1.5" style="font-size: 0.8rem;">
                <i class="fa-solid fa-cart-shopping"></i> Đã chọn: <span id="qvmQtyLiveBadge" class="text-white fs-6 fw-bolder">1</span> cái
              </span>
            </div>
            
            <!-- Ô nhập số lượng + nút tăng giảm cộng trừ (Tối đa 10) -->
            <div class="d-flex align-items-center gap-3 flex-wrap">
              <div class="bee-qty-stepper">
                <button type="button" class="btn-step-minus" id="qvmBtnMinus" onclick="changeQvmQuantity(-1)" title="Giảm 1 sản phẩm">
                  <i class="fa-solid fa-minus"></i>
                </button>
                <input type="number" id="qvmQuantityInput" value="1" min="1" max="10" class="qty-display-input" readonly>
                <button type="button" class="btn-step-plus" id="qvmBtnPlus" onclick="changeQvmQuantity(1)" title="Tăng 1 sản phẩm">
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>

              <div>
                <div class="d-flex align-items-baseline gap-1.5">
                  <span class="text-muted small">Tạm tính:</span>
                  <strong class="text-danger fs-6 fw-bold" id="qvmSubtotalLive">0₫</strong>
                </div>
                <small class="text-muted fs-11 d-block">
                  Kho: <strong class="text-dark" id="qvmStockNumber">999</strong> có sẵn • Tối đa 10 cái
                </small>
              </div>
            </div>

            <div id="qvmMaxLimitMsg" class="alert alert-warning border-0 py-2 px-3 small rounded-3 mt-2 mb-0 d-none fw-semibold" style="font-size: 0.78rem;">
              <i class="fa-solid fa-circle-exclamation text-danger me-1"></i> Số lượng mua tối đa cho phép là 10 sản phẩm.
            </div>
          </div>

          <!-- Cảnh Báo Chọn Biến Thể -->
          <div class="alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-0 small shadow-xs" id="qvmValidationAlert" style="display: none; font-size: 0.82rem;">
            <i class="fa-solid fa-circle-exclamation text-danger me-1 fs-6 align-middle"></i> Vui lòng chọn đầy đủ <strong>Màu sắc</strong> và <strong>Kích thước (Size)</strong> trước khi tiếp tục!
          </div>
        </div>

        <!-- Footer Nút Bấm Cao Cấp -->
        <div class="modal-footer border-top bg-light p-3 d-flex gap-2">
          <button type="button" class="btn btn-outline-warning text-dark flex-fill fw-bold py-2.5 rounded-3 shadow-xs" id="qvmAddToCartBtn" onclick="submitQvmAction(false)" style="font-size: 0.92rem;">
            <i class="fa-solid fa-cart-plus me-1.5 text-warning"></i> Thêm Vào Giỏ Hàng
          </button>
          <button type="button" class="btn btn-bee-primary flex-fill fw-bold py-2.5 rounded-3 shadow-xs" id="qvmBuyNowBtn" onclick="submitQvmAction(true)" style="font-size: 0.92rem;">
            <i class="fa-solid fa-bolt me-1.5"></i> Mua Ngay
          </button>
        </div>


      </div>
    </div>
  </div>

  <!-- ========================================== -->
  <!-- MODAL 2: THÔNG BÁO THÊM GIỎ HÀNG THÀNH CÔNG -->
  <!-- ========================================== -->
  <div class="modal fade" id="cartSuccessModal" tabindex="-1" aria-hidden="true" style="z-index: 1090;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 22px; overflow: hidden;">
        
        <div class="modal-body p-4 text-center">
          <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center mx-auto mb-3 shadow-xs" style="width: 64px; height: 64px;">
            <i class="fa-solid fa-circle-check fs-2"></i>
          </div>

          <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Đã Thêm Vào Giỏ Hàng!</h5>
          <p class="text-muted small mb-3">Sản phẩm đã được thêm vào giỏ hàng của bạn thành công.</p>

          <!-- Thẻ Sản Phẩm Vừa Thêm -->
          <div class="p-3 bg-light rounded-3 border text-start d-flex align-items-center gap-3 mb-4">
            <img src="" id="csmProductImage" alt="Sản phẩm" class="rounded border bg-white flex-shrink-0" style="width: 56px; height: 56px; object-fit: contain;">
            <div class="flex-grow-1 text-truncate">
              <h6 class="fw-bold text-dark mb-1 text-truncate" id="csmProductName" style="font-size: 0.88rem;">Tên sản phẩm</h6>
              <div class="text-muted small" style="font-size: 0.78rem;">
                Phân loại: <strong class="text-dark" id="csmVariantText">Đen / Size L</strong> • SL: <strong class="text-dark" id="csmQuantityText">1</strong>
              </div>
              <strong class="text-danger" id="csmPriceText">0₫</strong>
            </div>
          </div>

          <!-- 2 Nút Hành Động Rõ Ràng -->
          <div class="d-flex flex-column gap-2">
            <a href="{{ route('client.cart') }}" class="btn btn-warning text-dark fw-bold py-2.5 rounded-3 shadow-xs w-100">
              <i class="fa-solid fa-cart-shopping me-1.5"></i> Xem Giỏ Hàng &amp; Kiểm Tra Ngay
            </a>
            <button type="button" class="btn btn-outline-secondary py-2 rounded-3 w-100 small fw-semibold" data-bs-dismiss="modal">
              <i class="fa-solid fa-arrow-left me-1"></i> Tiếp Tục Chọn Mua Thêm
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ========================================== -->
  <!-- MODAL 3: YÊU CẦU ĐĂNG NHẬP ĐỂ THAO TÁC -->
  <!-- ========================================== -->
  <div class="modal fade" id="authRequiredModal" tabindex="-1" aria-hidden="true" style="z-index: 1095;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 22px; overflow: hidden;">
        
        <div class="modal-body p-4 text-center">
          <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center mx-auto mb-3 shadow-xs" style="width: 68px; height: 68px;">
            <i class="fa-solid fa-lock text-dark fs-2"></i>
          </div>

          <h5 class="fw-bold text-dark mb-2" style="font-size: 1.2rem;">Yêu Cầu Đăng Nhập Tài Khoản</h5>
          <p class="text-muted small mb-4" style="line-height: 1.5;">
            Để thực hiện <strong class="text-dark" id="authRequiredActionText">yêu thích, thêm giỏ hàng hoặc mua sắm</strong>, quý khách vui lòng đăng nhập hoặc đăng ký tài khoản thành viên BeeStyle.
          </p>

          <!-- 2 Nút Đăng Nhập & Đăng Ký -->
          <div class="d-flex flex-column gap-2">
            <a href="{{ route('auth.login') }}" class="btn btn-bee-primary fw-bold py-2.5 rounded-3 shadow-xs w-100">
              <i class="fa-solid fa-arrow-right-to-bracket me-1.5"></i> Đăng Nhập Ngay
            </a>
            <a href="{{ route('auth.register') }}" class="btn btn-outline-dark fw-bold py-2.5 rounded-3 shadow-xs w-100">
              <i class="fa-solid fa-user-plus me-1.5"></i> Đăng Ký Tài Khoản Mới
            </a>
            <button type="button" class="btn btn-link text-muted text-decoration-none py-1 small" data-bs-dismiss="modal">
              Để sau, tôi muốn tiếp tục xem sản phẩm
            </button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- SCRIPT XỬ LÝ CHỌN BIẾN THỂ & THÊM GIỎ HÀNG TOÀN HỆ THỐNG -->
  <script>
    const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};

    function requireAuthPrompt(actionName = 'thực hiện thao tác này') {
      const actEl = document.getElementById('authRequiredActionText');
      if (actEl) actEl.textContent = actionName;
      const modalEl = document.getElementById('authRequiredModal');
      if (modalEl) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
      } else {
        window.location.href = "{{ route('auth.login') }}";
      }
    }

    let currentQvmProduct = null;
    let selectedColor = null;
    let selectedSize = null;
    let quickVariantBsModal = null;
    let cartSuccessBsModal = null;
    let isBuyNowMode = false;


    // Mở Modal Chọn Biến Thể Khi Bấm [Thêm Giỏ] hoặc [Mua Ngay]
    function openQuickVariantModal(productId, isBuyNow = false, btnEl = null) {
      if (!IS_AUTHENTICATED) {
        requireAuthPrompt(isBuyNow ? 'mua hàng ngay' : 'thêm sản phẩm vào giỏ hàng');
        return;
      }

      isBuyNowMode = isBuyNow;
      
      const modalEl = document.getElementById('quickVariantModal');
      if (!modalEl) return;

      quickVariantBsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
      
      // Reset trạng thái
      selectedColor = null;
      selectedSize = null;
      document.getElementById('qvmSelectedColorText').textContent = 'Chưa chọn';
      document.getElementById('qvmSelectedColorText').className = 'badge bg-light text-muted border px-2 py-0.5 ms-1';
      document.getElementById('qvmSelectedSizeText').textContent = 'Chưa chọn';
      document.getElementById('qvmSelectedSizeText').className = 'badge bg-light text-muted border px-2 py-0.5 ms-1';
      document.getElementById('qvmQuantityInput').value = 1;
      document.getElementById('qvmValidationAlert').style.display = 'none';
      const maxNotice = document.getElementById('qvmMaxLimitMsg');
      if (maxNotice) maxNotice.classList.add('d-none');

      // Đổi class nút bấm nếu ở chế độ Mua Ngay
      if (isBuyNow) {
        document.getElementById('qvmBuyNowBtn').className = 'btn btn-bee-primary flex-fill fw-bold py-2.5 rounded-3 shadow-xs border-2 border-dark';
      }

      // 1. KIỂM TRA NẾU CÓ DỮ LIỆU SẴN TỪ NÚT BẤM (INSTANT RENDERING)
      if (btnEl && btnEl.dataset && btnEl.dataset.name) {
        try {
          const ds = btnEl.dataset;
          let parsedColors = ['Đen', 'Trắng', 'Xanh Navy'];
          let parsedSizes = ['S', 'M', 'L', 'XL', 'XXL'];
          try { if (ds.colors) parsedColors = JSON.parse(ds.colors); } catch(e){}
          try { if (ds.sizes) parsedSizes = JSON.parse(ds.sizes); } catch(e){}

          currentQvmProduct = {
            id: productId,
            name: ds.name,
            category_name: ds.category || 'Thời trang nam',
            price: parseInt(ds.price) || 0,
            price_formatted: ds.priceFormatted || ds.price + '₫',
            original_price_formatted: ds.originalPriceFormatted || '',
            discount_percent: parseInt(ds.discount) || 0,
            image: ds.image || '',
            stock: parseInt(ds.stock) || 999,
            colors: parsedColors,
            sizes: parsedSizes
          };

          renderQvmProductData(currentQvmProduct);
        } catch (e) {
          console.warn('Fallback parsing error:', e);
        }
      } else {
        // Hiển thị skeleton loading nhẹ
        document.getElementById('qvmProductName').textContent = 'Đang tải thông tin...';
        document.getElementById('qvmColorsContainer').innerHTML = '<div class="spinner-border spinner-border-sm text-warning"></div>';
        document.getElementById('qvmSizesContainer').innerHTML = '<div class="spinner-border spinner-border-sm text-warning"></div>';
      }

      quickVariantBsModal.show();

      // 2. FETCH DỮ LIỆU TỪ API CHUẨN XÁC
      const apiUrl = "{{ url('/san-pham/api-quick-view') }}/" + productId;
      fetch(apiUrl, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(res => {
          if (!res.ok) throw new Error('HTTP error ' + res.status);
          return res.json();
        })
        .then(data => {
          if (data && data.success) {
            currentQvmProduct = data;
            renderQvmProductData(data);
          }
        })
        .catch(err => {
          console.warn('API Quick View Fetch notice:', err);
          if (!currentQvmProduct || currentQvmProduct.id !== productId) {
            currentQvmProduct = {
              id: productId,
              name: 'Sản phẩm BeeStyle',
              category_name: 'Thời trang nam cao cấp',
              price: 0,
              price_formatted: 'Liên hệ',
              original_price_formatted: '',
              discount_percent: 0,
              image: '{{ asset("assets/img/logo.png") }}',
              stock: 999,
              colors: ['Đen', 'Trắng', 'Xanh Navy', 'Xám Ghi'],
              sizes: ['S', 'M', 'L', 'XL', 'XXL']
            };
            renderQvmProductData(currentQvmProduct);
          }
        });
    }

    // Bản đồ màu sắc chuẩn thời trang nam
    function getColorHex(colorName) {
      if (!colorName) return '#475569';
      const c = colorName.toLowerCase().trim();
      if (c.includes('đen') || c.includes('black')) return '#0f172a';
      if (c.includes('trắng') || c.includes('white')) return '#ffffff';
      if (c.includes('navy') || c.includes('xanh than') || c.includes('xanh đen')) return '#1e3a8a';
      if (c.includes('xám ghi') || c.includes('ghi') || c.includes('xám tiêu') || c.includes('gray')) return '#64748b';
      if (c.includes('xám nhạt') || c.includes('bạc')) return '#cbd5e1';
      if (c.includes('đỏ') || c.includes('burgundy') || c.includes('mận')) return '#881337';
      if (c.includes('be') || c.includes('beige') || c.includes('khaki') || c.includes('kem')) return '#d4b996';
      if (c.includes('rêu') || c.includes('olive') || c.includes('xanh lá')) return '#365314';
      if (c.includes('nâu') || c.includes('brown') || c.includes('coffee')) return '#78350f';
      if (c.includes('vàng') || c.includes('mustard')) return '#d97706';
      if (c.includes('xanh dương') || c.includes('blue') || c.includes('pastel')) return '#0284c7';
      if (c.includes('cam')) return '#ea580c';
      return '#334155';
    }

    // Gợi ý cân nặng chuẩn vóc dáng quý ông Việt Nam
    function getSizeHint(sizeName) {
      if (!sizeName) return '';
      const s = sizeName.toUpperCase().trim();
      if (s === 'S') return '50-58kg';
      if (s === 'M') return '58-65kg';
      if (s === 'L') return '65-72kg';
      if (s === 'XL') return '72-80kg';
      if (s === 'XXL' || s === '2XL') return '80-88kg';
      if (s === '3XL' || s === 'XXXL') return '> 88kg';
      if (s === 'FREESIZE') return 'Chuẩn form';
      return 'Chuẩn dáng';
    }

    // Hàm render dữ liệu vào Modal với giao diện cao cấp
    function renderQvmProductData(data) {
      document.getElementById('qvmCategoryName').textContent = data.category_name || 'Thời trang nam';
      document.getElementById('qvmProductName').textContent = data.name || 'Sản phẩm';
      document.getElementById('qvmProductPrice').textContent = data.price_formatted || '0₫';
      if (data.image) document.getElementById('qvmProductImage').src = data.image;

      if (data.original_price_formatted) {
        const origEl = document.getElementById('qvmProductOriginalPrice');
        origEl.textContent = data.original_price_formatted;
        origEl.style.display = 'inline';
      } else {
        document.getElementById('qvmProductOriginalPrice').style.display = 'none';
      }

      if (data.discount_percent > 0) {
        const discEl = document.getElementById('qvmDiscountBadge');
        discEl.textContent = `-${data.discount_percent}%`;
        discEl.style.display = 'block';
      } else {
        document.getElementById('qvmDiscountBadge').style.display = 'none';
      }

      // Cập nhật thông tin tồn kho
      document.getElementById('qvmStockNumber').textContent = (data.stock && data.stock > 0) ? data.stock : 999;
      
      const colors = (data.colors && data.colors.length > 0) ? data.colors : ['Tiêu chuẩn'];
      const sizes = (data.sizes && data.sizes.length > 0) ? data.sizes : ['Freesize'];

      // Render Danh Sách Màu Sắc (Color Swatches Luxury)
      const colorsHtml = colors.map(col => {
        const hex = getColorHex(col);
        const isWhite = hex === '#ffffff';
        return `
          <button type="button" class="btn btn-sm d-flex align-items-center gap-2 rounded-pill px-3 py-1.5 qvm-color-item transition-all" 
            onclick="selectQvmColor('${col}', this)"
            style="border: 1.5px solid #e2e8f0; background: #ffffff; color: #1e293b; font-weight: 600; font-size: 0.82rem;">
            <span class="rounded-circle d-inline-block shadow-xs" style="width: 15px; height: 15px; background-color: ${hex}; border: ${isWhite ? '1px solid #cbd5e1' : '1px solid rgba(0,0,0,0.15)'};"></span>
            <span>${col}</span>
          </button>
        `;
      }).join('');
      document.getElementById('qvmColorsContainer').innerHTML = colorsHtml;

      // Render Danh Sách Size (Size Matrix Box)
      const sizesHtml = sizes.map(sz => {
        return `
          <button type="button" class="btn btn-sm d-flex flex-column align-items-center justify-content-center rounded-3 p-1.5 qvm-size-item transition-all" 
            onclick="selectQvmSize('${sz}', this)"
            style="min-width: 62px; height: 48px; border: 1.5px solid #e2e8f0; background: #ffffff; color: #1e293b;">
            <span class="fw-bold fs-6 lh-1">${sz}</span>
            <span class="text-muted lh-1 mt-1" style="font-size: 0.65rem;">${getSizeHint(sz)}</span>
          </button>
        `;
      }).join('');
      document.getElementById('qvmSizesContainer').innerHTML = sizesHtml;

      // Cập nhật hiển thị số lượng & tổng tiền ban đầu
      updateQvmQtyDisplay(1);
    }

    // Xử lý chọn màu
    function selectQvmColor(color, btn) {
      selectedColor = color;
      const colorBadge = document.getElementById('qvmSelectedColorText');
      colorBadge.innerHTML = `${color} <i class="fa-solid fa-check ms-1 text-warning"></i>`;
      colorBadge.className = 'badge bg-dark text-warning border border-warning px-2 py-0.5 ms-1 fw-bold shadow-xs';
      document.getElementById('qvmValidationAlert').style.display = 'none';

      document.querySelectorAll('.qvm-color-item').forEach(b => {
        b.style.border = '1.5px solid #e2e8f0';
        b.style.background = '#ffffff';
        b.style.color = '#1e293b';
        b.style.boxShadow = 'none';
      });

      btn.style.border = '2px solid #d97706';
      btn.style.background = '#fffbeb';
      btn.style.color = '#92400e';
      btn.style.boxShadow = '0 4px 12px rgba(217, 119, 6, 0.2)';
    }

    // Xử lý chọn size
    function selectQvmSize(size, btn) {
      selectedSize = size;
      const sizeBadge = document.getElementById('qvmSelectedSizeText');
      sizeBadge.innerHTML = `Size ${size} (${getSizeHint(size)}) <i class="fa-solid fa-check ms-1 text-warning"></i>`;
      sizeBadge.className = 'badge bg-dark text-warning border border-warning px-2 py-0.5 ms-1 fw-bold shadow-xs';
      document.getElementById('qvmValidationAlert').style.display = 'none';

      document.querySelectorAll('.qvm-size-item').forEach(b => {
        b.style.border = '1.5px solid #e2e8f0';
        b.style.background = '#ffffff';
        b.style.color = '#1e293b';
        b.style.boxShadow = 'none';
        const hint = b.querySelector('.text-muted, .text-warning-emphasis');
        if (hint) hint.className = 'text-muted lh-1 mt-1';
      });

      btn.style.border = '2px solid #d97706';
      btn.style.background = '#0f172a';
      btn.style.color = '#f59e0b';
      btn.style.boxShadow = '0 4px 14px rgba(15, 23, 42, 0.25)';
      const activeHint = btn.querySelector('span:nth-child(2)');
      if (activeHint) activeHint.className = 'text-warning-emphasis lh-1 mt-1';
    }

    function updateQvmQtyDisplay(val) {
      const qtyInput = document.getElementById('qvmQuantityInput');
      const badge = document.getElementById('qvmQtyLiveBadge');
      const subtotal = document.getElementById('qvmSubtotalLive');
      const btnMinus = document.getElementById('qvmBtnMinus');
      const btnPlus = document.getElementById('qvmBtnPlus');
      const maxMsg = document.getElementById('qvmMaxLimitMsg');

      if (qtyInput) qtyInput.value = val;
      if (badge) {
        badge.textContent = val;
        badge.classList.remove('animate-scale');
        void badge.offsetWidth;
        badge.classList.add('animate-scale');
      }
      if (subtotal && currentQvmProduct && currentQvmProduct.price) {
        const total = currentQvmProduct.price * val;
        subtotal.textContent = total.toLocaleString('vi-VN') + '₫';
      } else if (subtotal && currentQvmProduct && currentQvmProduct.price_formatted) {
        subtotal.textContent = currentQvmProduct.price_formatted;
      }
      if (btnMinus) {
        btnMinus.disabled = (val <= 1);
        btnMinus.style.opacity = (val <= 1) ? '0.45' : '1';
      }
      if (btnPlus) {
        btnPlus.disabled = (val >= 10);
        btnPlus.style.opacity = (val >= 10) ? '0.45' : '1';
      }
      if (maxMsg) {
        if (val >= 10) {
          maxMsg.classList.remove('d-none');
        } else {
          maxMsg.classList.add('d-none');
        }
      }
    }

    // Tăng / giảm số lượng bằng nút bấm (Tối đa 10)
    function changeQvmQuantity(delta) {
      const qtyInput = document.getElementById('qvmQuantityInput');
      if (!qtyInput) return;
      let current = parseInt(qtyInput.value) || 1;
      current += delta;
      if (current < 1) current = 1;
      if (current > 10) current = 10;
      updateQvmQtyDisplay(current);
    }

    // Kiểm tra tính hợp lệ khi khách hàng tự gõ số lượng (1 - 10)
    function validateQvmQuantity(input) {
      let val = parseInt(input.value);
      if (isNaN(val) || val < 1) val = 1;
      if (val > 10) val = 10;
      updateQvmQtyDisplay(val);
    }


    // Submit Thêm Vào Giỏ hoặc Mua Ngay
    function submitQvmAction(isBuyNow) {
      if (!currentQvmProduct) return;

      // Kiểm tra xem khách đã chọn màu và size chưa
      if (!selectedColor || !selectedSize) {
        document.getElementById('qvmValidationAlert').style.display = 'block';
        return;
      }

      const quantity = parseInt(document.getElementById('qvmQuantityInput').value) || 1;
      const payload = {
        product_id: currentQvmProduct.id,
        color: selectedColor,
        size: selectedSize,
        quantity: quantity,
        buy_now: isBuyNow ? 1 : 0
      };

      const actionBtn = isBuyNow ? document.getElementById('qvmBuyNowBtn') : document.getElementById('qvmAddToCartBtn');
      const originalText = actionBtn.innerHTML;
      actionBtn.disabled = true;
      actionBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang xử lý...';

      fetch('{{ route("client.cart.add") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json())
      .then(data => {
        actionBtn.disabled = false;
        actionBtn.innerHTML = originalText;

        if (data.success) {
          // Cập nhật số lượng giỏ hàng trên Header
          document.querySelectorAll('.bee-badge-count').forEach(badge => {
            badge.textContent = data.cart_count;
          });

          // Đóng Modal Chọn Biến Thể
          quickVariantBsModal.hide();

          if (isBuyNow) {
            // Nếu là Mua Ngay -> Chuyển thẳng sang trang Thanh Toán
            window.location.href = '{{ route("client.checkout") }}';
          } else {
            // Nếu là Thêm Vào Giỏ -> Hiển thị Modal Kiểm Tra Giỏ Hàng
            document.getElementById('csmProductImage').src = currentQvmProduct.image;
            document.getElementById('csmProductName').textContent = currentQvmProduct.name;
            document.getElementById('csmVariantText').textContent = `${selectedColor} / Size ${selectedSize}`;
            document.getElementById('csmQuantityText').textContent = quantity;
            document.getElementById('csmPriceText').textContent = currentQvmProduct.price_formatted;

            const cartSuccessEl = document.getElementById('cartSuccessModal');
            cartSuccessBsModal = bootstrap.Modal.getOrCreateInstance(cartSuccessEl);
            cartSuccessBsModal.show();
          }
        } else {
          alert(data.message || 'Không thể thêm sản phẩm vào giỏ hàng.');
        }
      })
      .catch(err => {
        console.error(err);
        actionBtn.disabled = false;
        actionBtn.innerHTML = originalText;
        alert('Có lỗi xảy ra khi thêm vào giỏ hàng.');
      });
    }

    // ========================================================
    // XỬ LÝ SẢN PHẨM YÊU THÍCH (WISHLIST AJAX & LIVE TOAST)
    // ========================================================
    function toggleWishlist(productId, btnEl) {
      if (!IS_AUTHENTICATED) {
        requireAuthPrompt('thêm sản phẩm vào danh sách yêu thích');
        return;
      }

      if (!productId) return;

      const heartIcon = btnEl ? btnEl.querySelector('i') : null;
      if (heartIcon) {
        heartIcon.className = 'fa-solid fa-spinner fa-spin text-warning';
      }

      fetch('{{ route("client.wishlist.toggle") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Cập nhật tất cả các nút trái tim của sản phẩm này trên trang
          document.querySelectorAll(`.btn-wishlist-${productId}`).forEach(btn => {
            const icon = btn.querySelector('i');
            if (data.is_favorite) {
              btn.classList.add('active');
              if (icon) icon.className = 'fa-solid fa-heart text-danger fs-6 animate-heart';
            } else {
              btn.classList.remove('active');
              if (icon) icon.className = 'fa-regular fa-heart text-dark fs-6';
            }
          });

          if (heartIcon) {
            if (data.is_favorite) {
              if (btnEl) btnEl.classList.add('active');
              heartIcon.className = 'fa-solid fa-heart text-danger fs-6 animate-heart';
            } else {
              if (btnEl) btnEl.classList.remove('active');
              heartIcon.className = 'fa-regular fa-heart text-dark fs-6';
            }
          }

          // Cập nhật Badge số lượng trên Header
          const badge = document.getElementById('wishlistCountBadge');
          if (badge) {
            badge.textContent = data.count;
            badge.style.display = data.count > 0 ? 'flex' : 'none';
          }

          // Hiển thị Toast thông báo nhanh
          showWishlistToast(data.message, data.is_favorite);
        } else {
          if (heartIcon) heartIcon.className = 'fa-regular fa-heart text-dark';
          alert(data.message || 'Không thể thực hiện thao tác.');
        }
      })
      .catch(err => {
        console.error(err);
        if (heartIcon) heartIcon.className = 'fa-regular fa-heart text-dark';
      });
    }

    // Hiển thị Toast thông báo yêu thích
    function showWishlistToast(message, isFavorite) {
      let toastContainer = document.getElementById('beeToastContainer');
      if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'beeToastContainer';
        toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
      }

      const toastId = 'toast_' + Date.now();
      const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white ${isFavorite ? 'bg-dark' : 'bg-secondary'} border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 12px;">
          <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2 py-2.5 px-3">
              <i class="fa-solid ${isFavorite ? 'fa-heart text-danger fs-5' : 'fa-circle-check text-warning fs-5'}"></i>
              <span class="small fw-semibold">${message}</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      `;

      toastContainer.insertAdjacentHTML('beforeend', toastHtml);
      const toastEl = document.getElementById(toastId);
      const bsToast = new bootstrap.Toast(toastEl, { delay: 3000 });
      bsToast.show();

      toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
      });
    }
  </script>

  <style>
    @keyframes heartBeat {
      0% { transform: scale(1); }
      25% { transform: scale(1.3); }
      50% { transform: scale(1); }
      75% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }
    .animate-heart {
      animation: heartBeat 0.45s ease-in-out;
    }
    .btn-wishlist-toggle {
      transition: all 0.2s ease;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(4px);
    }
    .btn-wishlist-toggle:hover {
      background: #ffffff;
      transform: scale(1.1);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-wishlist-toggle.active {
      background: #ffffff;
      border-color: #fee2e2 !important;
    }
  </style>

  @stack('scripts')
</body>
</html>