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
                        <i class="fa-solid fa-coins text-warning fs-5"></i>
                        <div style="font-size: 0.75rem;">
                          Đánh giá nhận ngay <strong>+20 Điểm Thưởng VIP</strong> vào tài khoản!
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
                                <i class="fa-solid fa-star text-warning me-1"></i> Đánh Giá (+20đ)
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
                    <span class="badge bg-light text-dark fs-10 border">{{ Auth::user()->points ?? 0 }} Điểm</span>
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


          <!-- Shop -->
          <a href="{{ route('client.products.index') }}" class="bee-icon-btn" title="Tất cả sản phẩm">
            <i class="fa-solid fa-store"></i>
          </a>

          <!-- Cart -->
          <a href="{{ route('client.cart') }}" class="bee-icon-btn position-relative" title="Giỏ hàng">
            <i class="fa-solid fa-bag-shopping"></i>
            <span class="bee-badge-count">{{ \App\Services\CartService::count() }}</span>
          </a>

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

            <!-- BST Mùa Hè -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'bo-suu-tap-mua-he' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'bo-suu-tap-mua-he']) }}">
                <i class="fa-solid fa-sun me-1 text-warning"></i> BST Mùa Hè
              </a>
            </li>

            <!-- Thương Hiệu (Brands) -->
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->routeIs('client.brands.*') ? 'active' : '' }}" href="{{ route('client.brands.index') }}">
                <i class="fa-solid fa-crown me-1 text-warning"></i> Thương Hiệu
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
            <li><a href="{{ route('client.products.index', ['category' => 'bo-suu-tap-mua-he']) }}">BST Mùa Hè</a></li>
          </ul>
        </div>

        <!-- Customer Service -->
        <div class="col-lg-3 col-md-6 col-6">
          <h6 class="text-white fw-bold mb-3">CHĂM SÓC KHÁCH HÀNG</h6>
          <ul class="list-unstyled d-flex flex-column gap-2 small">
            <li><a href="{{ route('client.order-tracking') }}">Tra cứu hành trình đơn hàng</a></li>
            <li><a href="#">Chính sách đổi trả trong 30 ngày</a></li>
            <li><a href="#">Bảng quy đổi Size nam chuẩn</a></li>
            <li><a href="#">Chính sách tích điểm VIP</a></li>
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
                <i class="fa-solid fa-coins text-warning fs-5"></i>
                <div>
                  Nhận ngay <strong>+20 Điểm Thưởng VIP</strong> vào tài khoản cho mỗi đánh giá nhận xét sản phẩm!
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

            <!-- Review Form -->
            <div class="card border-0 p-3.5 rounded-3 mb-4 shadow-sm" style="background: #ffffff; border: 1px solid var(--atino-border) !important;">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0 text-uppercase" style="font-size: 0.85rem;">
                  <i class="fa-solid fa-pen-nib text-danger me-1.5"></i> <span id="qrmFormTitle">Viết Đánh Giá Của Bạn</span>
                </h6>
                <span class="badge bg-warning text-dark fw-bold">+20 điểm VIP</span>
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

                <div class="d-flex justify-content-between align-items-center">
                  <button type="submit" id="qrmSubmitBtn" class="btn btn-bee-primary btn-sm px-4 py-2 fw-bold">
                    <i class="fa-solid fa-paper-plane me-1"></i> <span id="qrmBtnText">GỬI ĐÁNH GIÁ (+20Đ VIP)</span>
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

  <!-- Scripts -->
  <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
  <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('vendors/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
  <script>
    if (typeof feather !== 'undefined') {
      feather.replace();
    }

    // Hàm mở Modal Đánh Giá & Xem Nhận Xét Sản Phẩm Nhanh
    function openQuickReviewModal(productId) {
      const modalEl = document.getElementById('quickProductReviewModal');
      if (!modalEl) return;

      const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
      const loadingEl = document.getElementById('qrmLoading');
      const contentEl = document.getElementById('qrmContent');
      const alertBox = document.getElementById('qrmAlertBox');

      loadingEl.style.display = 'block';
      contentEl.style.display = 'none';
      alertBox.style.display = 'none';
      bsModal.show();

      // Fetch dữ liệu sản phẩm & toàn bộ đánh giá
      fetch(`/san-pham/${productId}/danh-gia-chi-tiet`, {
        headers: { 'Accept': 'application/json' }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const p = data.product;
          document.getElementById('qrmProductId').value = p.id;
          document.getElementById('qrmProductName').textContent = p.name;
          document.getElementById('qrmProductImg').src = p.image;
          document.getElementById('qrmProductPrice').textContent = p.price;
          document.getElementById('qrmProductCat').textContent = p.category_name;
          document.getElementById('qrmProductLink').href = p.url;
          document.getElementById('qrmProductRating').innerHTML = `<i class="fa-solid fa-star"></i> <strong>${p.rating}</strong> (${p.reviews_count} nhận xét)`;
          document.getElementById('qrmReviewsCount').textContent = data.reviews.length;

          // Xử lý Form đánh giá của chính khách
          if (data.user_review) {
            document.getElementById('qrmFormTitle').textContent = 'Cập Nhật Đánh Giá Của Bạn';
            document.getElementById('qrmBtnText').textContent = 'CẬP NHẬT ĐÁNH GIÁ';
            document.getElementById('qrmComment').value = data.user_review.comment;
            const starRadio = document.querySelector(`input[name="qrm_rating"][value="${data.user_review.rating}"]`);
            if (starRadio) starRadio.checked = true;
          } else {
            document.getElementById('qrmFormTitle').textContent = 'Viết Đánh Giá Của Bạn';
            document.getElementById('qrmBtnText').textContent = 'GỬI ĐÁNH GIÁ (+20Đ VIP)';
            document.getElementById('qrmComment').value = '';
            document.getElementById('qrm_star_5').checked = true;
          }

          // Render danh sách đánh giá của các khách hàng khác
          const listEl = document.getElementById('qrmReviewsList');
          listEl.innerHTML = '';

          if (data.reviews && data.reviews.length > 0) {
            data.reviews.forEach(r => {
              let starsHtml = '';
              for (let i = 1; i <= 5; i++) {
                starsHtml += `<i class="fa-solid fa-star ${i <= r.rating ? 'text-warning' : 'text-secondary-subtle'}"></i>`;
              }

              const itemDiv = document.createElement('div');
              itemDiv.className = 'p-3 bg-light rounded-3 border';
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
                  <small class="text-muted" style="font-size: 0.72rem;">${r.time_ago}</small>
                </div>
                <div class="text-warning small mb-1.5">
                  ${starsHtml} <span class="text-dark fw-bold ms-1">(${r.rating}/5)</span>
                </div>
                <p class="small text-dark mb-0 fst-italic leading-relaxed" style="font-size: 0.8rem;">
                  "${r.comment}"
                </p>
              `;
              listEl.appendChild(itemDiv);
            });
          } else {
            listEl.innerHTML = `
              <div class="text-center py-4 bg-light rounded-3 border">
                <i class="fa-regular fa-comment-dots fs-2 text-muted mb-2 d-block"></i>
                <p class="small text-muted mb-0">Chưa có đánh giá nào khác. Hãy là người đầu tiên nhận xét sản phẩm này!</p>
              </div>
            `;
          }

          loadingEl.style.display = 'none';
          contentEl.style.display = 'block';
        }
      })
      .catch(err => {
        console.error(err);
        loadingEl.innerHTML = '<p class="text-danger small">Lỗi tải dữ liệu đánh giá sản phẩm. Vui lòng thử lại!</p>';
      });
    }

    // Xử lý gửi Form Đánh Giá AJAX trong Modal
    document.addEventListener("DOMContentLoaded", function () {
      const qrmForm = document.getElementById('qrmForm');
      if (qrmForm) {
        qrmForm.addEventListener('submit', function (e) {
          e.preventDefault();
          const productId = document.getElementById('qrmProductId').value;
          const ratingInput = document.querySelector('input[name="qrm_rating"]:checked');
          const comment = document.getElementById('qrmComment').value;
          const submitBtn = document.getElementById('qrmSubmitBtn');
          const alertBox = document.getElementById('qrmAlertBox');

          if (!ratingInput || !comment.trim()) return;

          submitBtn.disabled = true;
          submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang gửi...';

          fetch(`/san-pham/${productId}/danh-gia`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              rating: ratingInput.value,
              comment: comment
            })
          })
          .then(res => res.json())
          .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-check me-1"></i> HOÀN TẤT!';

            if (data.success) {
              alertBox.className = 'alert alert-success border-0 py-2.5 px-3 rounded-3 mb-3 small';
              alertBox.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> ${data.message}`;
              alertBox.style.display = 'block';

              // Tải lại danh sách đánh giá sau 1s
              setTimeout(() => {
                openQuickReviewModal(productId);
              }, 1200);
            } else {
              alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
              alertBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-1"></i> ${data.message || 'Không thể gửi đánh giá'}`;
              alertBox.style.display = 'block';
            }
          })
          .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> GỬI ĐÁNH GIÁ';
            alertBox.className = 'alert alert-danger border-0 py-2.5 px-3 rounded-3 mb-3 small';
            alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> Có lỗi xảy ra khi gửi đánh giá.';
            alertBox.style.display = 'block';
          });
        });
      }
    });

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
  @stack('scripts')

</body>
</html>