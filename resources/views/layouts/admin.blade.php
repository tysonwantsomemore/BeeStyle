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
            
            <!-- ADMIN LOGO -->
            <li class="nav-item mb-3 px-3 pt-2">
              <a href="{{ route('admin.dashboard') }}" class="beestyle-logo text-white">
                <span class="logo-badge"><i class="fa-solid fa-gem"></i></span>
                <span class="text-white">Bee<span class="brand-highlight">Style</span> <small class="badge bg-warning text-dark fs-10 px-1 py-0 ms-1">ADMIN</small></span>
              </a>
            </li>

            <!-- DASHBOARD -->
            <li class="nav-item">
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-chart-pie"></i></span>
                    <span class="nav-link-text">Tổng Quan (Dashboard)</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- SẢN PHẨM & DANH MỤC -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Sản Phẩm &amp; Danh Mục</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-shirt"></i></span>
                    <span class="nav-link-text">Danh Sách Sản Phẩm</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}" href="{{ route('admin.products.create') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-plus-circle"></i></span>
                    <span class="nav-link-text">Thêm Mới Sản Phẩm</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-layer-group"></i></span>
                    <span class="nav-link-text">Danh Mục Thời Trang</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- ĐƠN HÀNG & GIAO DỊCH -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Đơn Hàng &amp; Giao Dịch</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></span>
                    <span class="nav-link-text">Quản Lý Đơn Hàng</span>
                    <span class="badge ms-auto bg-warning text-dark font-weight-bold">4 mới</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- KHÁCH HÀNG & KHUYẾN MÃI -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Khách Hàng &amp; Khuyến Mãi</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.customers.index') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-users"></i></span>
                    <span class="nav-link-text">Quản Lý Khách Hàng</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.coupons.index') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-ticket"></i></span>
                    <span class="nav-link-text">Mã Giảm Giá (Coupons)</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- CỬA HÀNG CLIENT -->
            <li class="nav-item mt-4">
              <p class="navbar-vertical-label">Lối Tắt</p>
              <div class="nav-item-wrapper">
                <a class="nav-link text-warning fw-bold" href="{{ route('client.home') }}" target="_blank">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                    <span class="nav-link-text">Xem Website Cửa Hàng</span>
                  </div>
                </a>
              </div>
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
              <div class="d-flex align-items-center"><i class="fa-solid fa-gem text-warning fs-4 me-2"></i><p class="logo-text ms-2 d-none d-sm-block">BeeStyle</p></div>
            </div>
          </a>
        </div>

        <div class="search-box navbar-top-search-box d-none d-lg-block" style="width:25rem;">
          <form class="position-relative">
            <input class="form-control form-control-sm rounded-pill search-input fuzzy-search" type="search" placeholder="Tìm kiếm đơn hàng, sản phẩm, khách hàng..." />
            <span class="fas fa-search search-box-icon"></span>
          </form>
        </div>

        <ul class="navbar-nav navbar-nav-icons flex-row align-items-center gap-3">
          <!-- Notification -->
          <li class="nav-item dropdown">
            <a class="nav-link position-relative px-2" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa-regular fa-bell fs-5 text-secondary"></i>
              <span class="position-absolute top-1 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-lg py-2 mt-2 border-0" style="min-width: 280px;">
              <div class="px-3 py-2 border-bottom fw-bold text-dark">Thông báo mới</div>
              <a class="dropdown-item py-2" href="{{ route('admin.orders.index') }}">
                <div class="d-flex align-items-center">
                  <i class="fa-solid fa-bag-shopping text-warning me-2"></i>
                  <div>
                    <p class="mb-0 small fw-semibold">Đơn hàng mới #BEE-2026-0816-01</p>
                    <small class="text-muted">10 phút trước</small>
                  </div>
                </div>
              </a>
              <a class="dropdown-item py-2" href="{{ route('admin.customers.index') }}">
                <div class="d-flex align-items-center">
                  <i class="fa-solid fa-user-plus text-success me-2"></i>
                  <div>
                    <p class="mb-0 small fw-semibold">Khách hàng mới: Trần Thị Mai Phương</p>
                    <small class="text-muted">1 giờ trước</small>
                  </div>
                </div>
              </a>
            </div>
          </li>

          <!-- User Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link lh-1 pe-0 d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
              <div class="avatar avatar-m">
                <img class="rounded-circle border" src="{{ asset('assets/img/team/40x40/57.webp') }}" alt="Admin" />
              </div>
              <div class="d-none d-md-block text-start">
                <div class="fw-bold small text-dark">Admin BeeStyle</div>
                <div class="text-muted fs-10">Quản trị viên cấp cao</div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-1 shadow-lg border-0 mt-2">
              <a class="dropdown-item" href="{{ route('client.home') }}"><i class="fa-solid fa-store me-2 text-warning"></i> Website Khách hàng</a>
              <a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gear me-2 text-secondary"></i> Cài đặt hệ thống</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng xuất</a>
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

      @yield('content')

      <!-- ADMIN FOOTER -->
      <footer class="footer position-absolute">
        <div class="row g-0 justify-content-between align-items-center h-100">
          <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 mt-2 mt-sm-0 text-body">Hệ thống Quản trị Thời trang <strong>BeeStyle</strong> &copy; {{ date('Y') }}</p>
          </div>
          <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 text-body-tertiary text-opacity-85">Phiên bản v2.0 - Laravel 12</p>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <!-- Scripts -->
  <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
  <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
  <script src="{{ asset('vendors/echarts/echarts.min.js') }}"></script>
  <script>
    if (typeof feather !== 'undefined') {
      feather.replace();
    }
  </script>
  @stack('scripts')
</body>
</html>
