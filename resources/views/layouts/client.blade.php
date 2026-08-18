<!DOCTYPE html>
<html lang="vi" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'BeeStyle - Thời Trang Nam Đẳng Cấp & Thanh Lịch')</title>

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
  <meta name="theme-color" content="#f59e0b">

  <!-- Unified Google Font: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Core Theme & Vendors Styles -->
  <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet" id="style-default">
  <link href="{{ asset('vendors/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Unified BeeStyle Custom Design System -->
  <link href="{{ asset('assets/css/beestyle.css') }}" rel="stylesheet">

  @stack('styles')
</head>

<body>
  <!-- ANNOUNCEMENT TOP BAR -->
  <div class="bee-announcement-bar">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="d-flex align-items-center gap-3">
        <span><i class="fa-solid fa-truck-fast text-warning me-1"></i> Miễn phí vận chuyển toàn quốc cho đơn hàng từ <strong>300.000₫</strong></span>
        <span class="d-none d-md-inline text-secondary">|</span>
        <span class="d-none d-md-inline"><i class="fa-solid fa-phone me-1 text-warning"></i> Hotline: <strong>1900 8888</strong> (8:00 - 22:00)</span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <a href="{{ route('client.order-tracking') }}"><i class="fa-solid fa-location-dot me-1"></i> Tra cứu đơn hàng</a>
        <span class="text-secondary">|</span>
        @auth
          @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-gear me-1"></i> Trang Quản Trị (Admin)</a>
          @else
            <a href="{{ route('client.profile') }}" class="text-white text-decoration-none"><i class="fa-solid fa-user me-1 text-warning"></i> Chào, <strong>{{ Auth::user()->name }}</strong></a>
          @endif
          <span class="text-secondary">|</span>
          <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-white-50 p-0 fs-11 text-decoration-none hover-white">
              <i class="fa-solid fa-arrow-right-from-bracket me-1 text-danger"></i> Đăng xuất
            </button>
          </form>
        @else
          <a href="{{ route('auth.login') }}" class="text-white"><i class="fa-solid fa-arrow-right-to-bracket me-1 text-warning"></i> Đăng nhập</a>
          <span class="text-secondary">/</span>
          <a href="{{ route('auth.register') }}" class="text-warning fw-semibold">Đăng ký</a>
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
          <span>Bee<span class="brand-highlight">Style</span></span>
        </a>

        <!-- SEARCH BAR -->
        <div class="d-none d-lg-block flex-grow-1 mx-4" style="max-width: 540px;">
          <form action="{{ route('client.products.index') }}" method="GET" class="bee-search-wrapper">
            <i class="fa-solid fa-magnifying-glass bee-search-icon"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control bee-search-input" placeholder="Tìm kiếm áo polo nam, sơ mi, blazer, quần âu, giày da...">
          </form>
        </div>

        <!-- RIGHT ACTIONS -->
        <div class="d-flex align-items-center gap-2">
          
          <!-- USER DROPDOWN -->
          @auth
            <div class="dropdown">
              <button class="btn bee-icon-btn d-flex align-items-center gap-2 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản của tôi">
                <img src="{{ asset(Auth::user()->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ Auth::user()->name }}" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover;">
                <span class="d-none d-md-inline small fw-semibold text-dark">{{ Str::limit(Auth::user()->name, 12) }}</span>
                <i class="fa-solid fa-chevron-down fs-10 text-muted"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-3 mt-2" style="border-radius: 14px; min-width: 240px;">
                <li class="pb-2 mb-2 border-bottom">
                  <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                  <div class="small text-muted">{{ Auth::user()->email }}</div>
                  <div class="d-flex align-items-center gap-1 mt-1">
                    <span class="badge bg-warning-subtle text-dark fs-10 fw-bold">{{ Auth::user()->rank }}</span>
                    <span class="badge bg-light text-dark fs-10 border">{{ Auth::user()->points }} Điểm</span>
                  </div>
                </li>
                @if(Auth::user()->isAdmin())
                  <li>
                    <a class="dropdown-item py-2 rounded-2 text-warning fw-bold" href="{{ route('admin.dashboard') }}">
                      <i class="fa-solid fa-gauge-high me-2"></i> Bảng Quản Trị Admin
                    </a>
                  </li>
                @endif
                <li>
                  <a class="dropdown-item py-2 rounded-2" href="{{ route('client.profile') }}">
                    <i class="fa-regular fa-user me-2 text-muted"></i> Hồ Sơ &amp; Đơn Hàng
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
            <a href="{{ route('auth.login') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
              <i class="fa-regular fa-user me-1"></i> Đăng Nhập
            </a>
          @endauth

          <!-- Wishlist / Shop -->
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

    <!-- CATEGORY NAVIGATION BAR -->
    <nav class="navbar navbar-expand-lg py-2 mt-2 border-top">
      <div class="container">
        <div class="collapse navbar-collapse" id="beeMainNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->routeIs('client.home') ? 'active' : '' }}" href="{{ route('client.home') }}">
                <i class="fa-solid fa-house me-1"></i> Trang Chủ
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-polo-tshirt-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-polo-tshirt-nam']) }}">
                Áo Polo &amp; T-Shirt
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-so-mi-nam-cong-so' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam-cong-so']) }}">
                Áo Sơ Mi Nam
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'ao-khoac-blazer' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer']) }}">
                Áo Khoác &amp; Blazer
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'quan-au-kaki-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'quan-au-kaki-nam']) }}">
                Quần Âu &amp; Kaki
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'giay-da-loafer-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'giay-da-loafer-nam']) }}">
                Giày Da &amp; Loafer
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link bee-nav-link {{ request()->get('category') == 'that-lung-phu-kien-nam' ? 'active' : '' }}" href="{{ route('client.products.index', ['category' => 'that-lung-phu-kien-nam']) }}">
                Thắt Lưng &amp; Phụ Kiện
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link bee-nav-link text-danger fw-bold" href="{{ route('client.products.index') }}">
                <i class="fa-solid fa-fire text-danger me-1"></i> BST Mới 2026
              </a>
            </li>
          </ul>

          <div class="d-flex align-items-center gap-3">
            <span class="badge bg-danger-subtle text-danger px-3 py-2 fw-semibold rounded-pill">
              <i class="fa-solid fa-tags me-1"></i> Mã BEESTYLE50 giảm 50k
            </span>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- FLASH MESSAGES -->
  @if(session('success'))
    <div class="container mt-3">
      <div class="alert alert-success alert-dismissible fade show d-flex align-items-center border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="container mt-3">
      <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center border-0 shadow-sm" role="alert">
        <i class="fa-solid fa-circle-exclamation fs-5 me-2 text-danger"></i>
        <div>{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  @if($errors->any())
    <div class="container mt-3">
      <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
        <ul class="mb-0 small ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <!-- MAIN CONTENT SLOT -->
  <main>
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="bee-footer">
    <div class="container">
      <div class="row g-4">
        <!-- Brand Info -->
        <div class="col-lg-4 col-md-6">
          <div class="beestyle-logo text-white mb-3">
            <span class="logo-badge"><i class="fa-solid fa-gem"></i></span>
            <span class="text-white">Bee<span class="brand-highlight">Style</span></span>
          </div>
          <p class="text-secondary small leading-relaxed pe-lg-4">
            BeeStyle là thương hiệu thời trang nam cao cấp định hình phong cách tối giản, chuẩn mực và lịch lãm. Chúng tôi cam kết chất liệu cotton dệt tổ ong, vải lụa bamboo và da bò nguyên tấm chuẩn quốc tế.
          </p>
          <div class="d-flex gap-2 mt-3">
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="fa-brands fa-youtube"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6 col-6">
          <h6 class="text-white fw-bold mb-3">Danh Mục Nam</h6>
          <ul class="list-unstyled d-flex flex-column gap-2 small">
            <li><a href="{{ route('client.products.index', ['category' => 'ao-polo-tshirt-nam']) }}">Áo Polo &amp; T-Shirt</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam-cong-so']) }}">Áo Sơ Mi Nam</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer']) }}">Áo khoác &amp; Blazer</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'quan-au-kaki-nam']) }}">Quần Âu Nam</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'giay-da-loafer-nam']) }}">Giày Da &amp; Loafer</a></li>
            <li><a href="{{ route('client.products.index', ['category' => 'that-lung-phu-kien-nam']) }}">Thắt Lưng Da Thật</a></li>
          </ul>
        </div>

        <!-- Customer Service -->
        <div class="col-lg-3 col-md-6 col-6">
          <h6 class="text-white fw-bold mb-3">Chăm Sóc Khách Hàng</h6>
          <ul class="list-unstyled d-flex flex-column gap-2 small">
            <li><a href="{{ route('client.order-tracking') }}">Tra cứu hành trình đơn hàng</a></li>
            <li><a href="#">Chính sách đổi trả trong 30 ngày</a></li>
            <li><a href="#">Hướng dẫn chọn Size nam chuẩn</a></li>
            <li><a href="#">Chính sách khách hàng VIP</a></li>
            <li><a href="#">Hệ thống cửa hàng toàn quốc</a></li>
            <li><a href="#">Bảo mật thông tin khách hàng</a></li>
          </ul>
        </div>

        <!-- Contact & Newsletter -->
        <div class="col-lg-3 col-md-6">
          <h6 class="text-white fw-bold mb-3">Nhận Ưu Đãi Độc Quyền</h6>
          <p class="text-secondary small mb-3">Đăng ký email để nhận mã giảm <strong>15%</strong> cho đơn hàng đầu tiên.</p>
          <div class="input-group mb-3">
            <input type="email" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Email của bạn...">
            <button class="btn btn-bee-primary btn-sm px-3" type="button">Đăng Ký</button>
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
          &copy; {{ date('Y') }} <strong>BeeStyle Menswear Group</strong>. Đồ án tốt nghiệp - All Rights Reserved.
        </div>
        <div class="d-flex gap-3 text-secondary small">
          <a href="#">Điều khoản sử dụng</a>
          <span>•</span>
          <a href="#">Chính sách thanh toán</a>
          <span>•</span>
          <a href="#">Hotline: 1900 8888</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
  <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('vendors/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
  <script>
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  </script>
  @stack('scripts')
</body>
</html>
