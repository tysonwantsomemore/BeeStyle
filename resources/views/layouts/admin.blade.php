<!DOCTYPE html>
<html lang="vi" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'BeeStyle Admin - Quản Trị Hệ Thống')</title>

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
  
  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Unified BeeStyle Custom Design System -->
  <link href="{{ asset('assets/css/beestyle.css') }}" rel="stylesheet">

  @stack('styles')
</head>

<body>
  <main class="main" id="top">
    <!-- SIDEBAR NAVIGATION -->
    <nav class="navbar navbar-vertical navbar-expand-lg">
      <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content">
          <ul class="navbar-nav flex-column" id="navbarVerticalNav">
            
            <!-- ADMIN UNIFIED LOGO -->
            <li class="nav-item mb-4 px-3 pt-3">
              <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                <span class="fs-4 fw-black text-white" style="letter-spacing: 1.5px; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 900;">
                  BEE<span class="text-warning">STYLE</span>
                </span>
              </a>
            </li>


            <!-- TỔNG QUAN HỆ THỐNG -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Tổng Quan Hệ Thống</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-chart-pie"></i></span>
                  <span class="nav-link-text">Bảng Điều Khiển (Dashboard)</span>
                </a>
              </div>
            </li>

            <!-- SẢN PHẨM & KHO HÀNG -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Sản Phẩm &amp; Kho Hàng</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-shirt"></i></span>
                  <span class="nav-link-text">Tất Cả Sản Phẩm</span>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}" href="{{ route('admin.products.create') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-square-plus"></i></span>
                  <span class="nav-link-text">Thêm Sản Phẩm Mới</span>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-layer-group"></i></span>
                  <span class="nav-link-text">Danh Mục Áo Nam</span>
                </a>
              </div>
            </li>

            <!-- ĐƠN HÀNG & GIAO DỊCH -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Đơn Hàng &amp; Vận Chuyển</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></span>
                  <span class="nav-link-text">Quản Lý Đơn Hàng</span>
                </a>
              </div>
            </li>

            <!-- KHÁCH HÀNG & PHẢN HỒI -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Khách Hàng &amp; Đánh Giá</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-users"></i></span>
                  <span class="nav-link-text">Tài Khoản Khách Hàng</span>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-star"></i></span>
                  <span class="nav-link-text">Đánh Giá &amp; Nhận Xét</span>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-tags"></i></span>
                  <span class="nav-link-text">Mã Giảm Giá (Voucher)</span>
                </a>
              </div>
            </li>

            <!-- TRỞ VỀ CỬA HÀNG -->
            <li class="nav-item mt-4 pt-3 border-top border-secondary border-opacity-25 px-2">
              <a class="btn btn-bee-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2" href="{{ route('client.home') }}" target="_blank">
                <i class="fa-solid fa-store"></i>
                <span>Xem Cửa Hàng Web</span>
                <i class="fa-solid fa-arrow-up-right-from-square fs-11"></i>
              </a>
            </li>


          </ul>
        </div>
      </div>
    </nav>

    <!-- TOPBAR -->
    <nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
      <div class="collapse navbar-collapse justify-content-between">
        <div class="navbar-logo">
          <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse">
            <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
          </button>
          <a class="navbar-brand me-1 me-sm-3 d-lg-none" href="{{ route('admin.dashboard') }}">
            <div class="d-flex align-items-center">
              <span class="fs-5 fw-black text-dark" style="letter-spacing: 1px;">BEE<span class="text-warning">STYLE</span></span>
            </div>
          </a>

        </div>

        <div class="search-box navbar-top-search-box d-none d-lg-block" style="width:25rem;">
          <form action="{{ route('admin.orders.index') }}" method="GET" class="position-relative">
            <input name="q" class="form-control form-control-sm rounded-pill search-input" type="search" placeholder="Tìm kiếm mã đơn hàng, sản phẩm, SĐT..." />
            <span class="fas fa-search search-box-icon"></span>
          </form>
        </div>

        <ul class="navbar-nav navbar-nav-icons flex-row align-items-center gap-3">
          <!-- User Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link lh-1 pe-0 d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
              <div class="avatar avatar-m">
                <img class="rounded-circle border" src="{{ asset(Auth::user()->avatar ?? '/assets/img/team/40x40/57.webp') }}" alt="Admin" style="width: 36px; height: 36px; object-fit: cover;" />
              </div>
              <div class="d-none d-md-block text-start">
                <div class="fw-bold small text-dark">{{ Auth::user()->name ?? 'Admin BeeStyle' }}</div>
                <div class="text-muted fs-10">{{ Auth::user()->email ?? 'admin@beestyle.com' }}</div>
              </div>
              <i class="fa-solid fa-chevron-down fs-10 text-muted ms-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-2 shadow-lg border-0 mt-2" style="border-radius: 12px; min-width: 220px;">
              <a class="dropdown-item py-2" href="{{ route('client.home') }}" target="_blank"><i class="fa-solid fa-store me-2 text-warning"></i> Xem Cửa Hàng Web</a>
              <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-pie me-2 text-secondary"></i> Bảng Tổng Quan</a>
              <a class="dropdown-item py-2" href="{{ route('client.profile', ['tab' => 'password']) }}"><i class="fa-solid fa-key me-2 text-primary"></i> Đổi Mật Khẩu</a>
              <div class="dropdown-divider"></div>

              <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item py-2 text-danger">
                  <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng Xuất Admin
                </button>
              </form>
            </div>
          </li>
        </ul>
      </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <div class="content">
      <!-- FLASH NOTIFICATIONS -->
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center border-0 shadow-sm mb-4" role="alert">
          <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
          <div>{{ session('success') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center border-0 shadow-sm mb-4" role="alert">
          <i class="fa-solid fa-circle-exclamation fs-5 me-2 text-danger"></i>
          <div>{{ session('error') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(isset($errors) && $errors->any())
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
          <ul class="mb-0 small ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif


      <!-- MAIN PAGE CONTENT -->
      @yield('content')

      <!-- ADMIN FOOTER -->
      <footer class="footer position-relative mt-5 pt-3 border-top text-secondary small d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>&copy; {{ date('Y') }} <strong>BeeStyle Menswear</strong> - Hệ thống Quản trị Bán hàng Đồ án Tốt nghiệp.</div>
        <div>Phiên bản 2.0 • Laravel 13.x • MySQL 8.x</div>
      </footer>
    </div>
  </main>

  <!-- Core JavaScripts -->
  <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
  <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
  <script>
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  </script>
  @stack('scripts')
</body>
</html>
