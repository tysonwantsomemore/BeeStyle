<!DOCTYPE html>
<html lang="vi" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'BeeStyle Admin - Hệ Thống Quản Trị Website')</title>

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
  <meta name="theme-color" content="#f59e0b">

  <!-- SimpleBar & Config JS -->
  <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>

  <!-- Google Fonts: Nunito Sans & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;0,900;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Core Theme & Vendor Stylesheets -->
  <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
  <link href="{{ asset('assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
  <link href="{{ asset('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
  <link href="{{ asset('assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
  <link href="{{ asset('assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">

  <script>
    var phoenixIsRTL = window.config.config.phoenixIsRTL;
    if (phoenixIsRTL) {
      var linkDefault = document.getElementById('style-default');
      var userLinkDefault = document.getElementById('user-style-default');
      linkDefault && linkDefault.setAttribute('disabled', true);
      userLinkDefault && userLinkDefault.setAttribute('disabled', true);
      document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
      var linkRTL = document.getElementById('style-rtl');
      var userLinkRTL = document.getElementById('user-style-rtl');
      linkRTL && linkRTL.setAttribute('disabled', true);
      userLinkRTL && userLinkRTL.setAttribute('disabled', true);
    }
  </script>

  <!-- FontAwesome 6 Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body {
      font-family: 'Nunito Sans', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    .brand-logo-text {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 800;
      letter-spacing: -0.02em;
    }
    .navbar-vertical .navbar-vertical-label {
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.68rem;
      letter-spacing: 0.08em;
      color: var(--phoenix-tertiary-color, #8a94ad);
      margin-top: 1.25rem;
      margin-bottom: 0.5rem;
      padding-left: 1rem;
    }
    .badge-phoenix {
      font-weight: 700;
      font-size: 0.72rem;
      padding: 0.25rem 0.5rem;
      border-radius: 6px;
    }
    .table thead th {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-weight: 700;
    }
  </style>

  @stack('styles')
</head>

<body>
  <!-- MAIN LAYOUT WRAPPER -->
  <main class="main" id="top">

    <!-- ===============================================-->
    <!--    VERTICAL SIDEBAR NAVIGATION                -->
    <!-- ===============================================-->
    <nav class="navbar navbar-vertical navbar-expand-lg">
      <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content">
          <ul class="navbar-nav flex-column" id="navbarVerticalNav">

            <!-- LOGO THƯƠNG HIỆU BEESTYLE ADMIN -->
            <li class="nav-item mb-3 px-3 pt-3">
              <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none py-1">
                <div class="d-flex align-items-center justify-content-center bg-warning text-dark rounded-3 me-2 shadow-xs" style="width: 38px; height: 38px; font-size: 1.1rem;">
                  <i class="fa-solid fa-gem"></i>
                </div>
                <div class="brand-logo-text lh-1">
                  <div class="fs-7 text-body-emphasis">BEE<span class="text-warning">STYLE</span></div>
                  <div class="fs-10 text-body-tertiary fw-bold mt-1">QUẢN TRỊ HỆ THỐNG</div>
                </div>
              </a>
            </li>

            <!-- PHẦN 1: TỔNG QUAN -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Tổng Quan</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="pie-chart"></span></span>
                    <span class="nav-link-text">Bảng Điều Khiển</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- PHẦN 2: SẢN PHẨM & KHO HÀNG -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Sản Phẩm &amp; Danh Mục</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="package"></span></span>
                    <span class="nav-link-text">Tất Cả Sản Phẩm</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}" href="{{ route('admin.products.create') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="plus-circle"></span></span>
                    <span class="nav-link-text">Thêm Sản Phẩm Mới</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="grid"></span></span>
                    <span class="nav-link-text">Danh Mục Áo Nam</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="award"></span></span>
                    <span class="nav-link-text">Thương Hiệu Thời Trang</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- PHẦN 3: ĐƠN HÀNG & GIAO DỊCH -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Đơn Hàng &amp; Vận Chuyển</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="shopping-cart"></span></span>
                    <span class="nav-link-text">Quản Lý Đơn Hàng</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}" href="{{ route('admin.returns.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="rotate-ccw"></span></span>
                    <span class="nav-link-text">Đổi Trả &amp; Hoàn Tiền</span>
                    @php
                      $pendingRmaCount = \App\Models\OrderReturn::where('status', 'pending')->count();
                    @endphp
                    @if($pendingRmaCount > 0)
                      <span class="badge badge-phoenix badge-phoenix-warning ms-auto">{{ $pendingRmaCount }}</span>
                    @endif
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}" href="{{ route('admin.revenue.monthly') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="dollar-sign"></span></span>
                    <span class="nav-link-text">Doanh Thu Tháng Này</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="bar-chart-2"></span></span>
                    <span class="nav-link-text">Báo Cáo &amp; Thống Kê</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- PHẦN 4: KHÁCH HÀNG & TÀI KHOẢN -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Khách Hàng &amp; Phân Quyền</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="users"></span></span>
                    <span class="nav-link-text">Tài Khoản Khách Hàng</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="shield"></span></span>
                    <span class="nav-link-text">Quản Trị &amp; Phân Quyền</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="star"></span></span>
                    <span class="nav-link-text">Đánh Giá &amp; Nhận Xét</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- PHẦN 5: KHUYẾN MÃI & TIẾP THỊ -->
            <li class="nav-item">
              <p class="navbar-vertical-label">Khuyến Mãi &amp; Tiếp Thị</p>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="tag"></span></span>
                    <span class="nav-link-text">Mã Giảm Giá (Voucher)</span>
                  </div>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.daily-deals.*') ? 'active' : '' }}" href="{{ route('admin.daily-deals.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><span data-feather="zap"></span></span>
                    <span class="nav-link-text">Ưu Đãi Trong Ngày (Deal)</span>
                    <span class="badge badge-phoenix badge-phoenix-danger ms-auto">HOT</span>
                  </div>
                </a>
              </div>
            </li>

            <!-- PHẦN 6: TRỞ VỀ CỬA HÀNG WEB CLIENT -->
            <li class="nav-item mt-4 pt-3 border-top border-translucent px-2">
              <a class="btn btn-phoenix-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2" href="{{ route('client.home') }}" target="_blank">
                <span data-feather="external-link"></span>
                <span>Xem Cửa Hàng Web</span>
              </a>
            </li>

          </ul>
        </div>
      </div>

      <!-- FOOTER THU GỌN SIDEBAR CHUẨN PHOENIX -->
      <div class="navbar-vertical-footer">
        <button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center">
          <span class="uil uil-left-arrow-to-left fs-8"></span>
          <span class="uil uil-arrow-from-right fs-8"></span>
          <span class="navbar-vertical-footer-text ms-2">Thu Gọn Menu</span>
        </button>
      </div>
    </nav>


    <!-- ===============================================-->
    <!--    TOPBAR NAVIGATION                           -->
    <!-- ===============================================-->
    <nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
      <div class="collapse navbar-collapse justify-content-between">
        
        <!-- Toggle Menu & Mobile Logo -->
        <div class="navbar-logo">
          <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
            <span class="navbar-toggle-icon"><span class="toggle-line"></span></span>
          </button>
          <a class="navbar-brand me-1 me-sm-3 d-lg-none" href="{{ route('admin.dashboard') }}">
            <div class="d-flex align-items-center">
              <span class="badge bg-warning text-dark p-1.5 rounded me-1 fs-9"><i class="fa-solid fa-gem"></i></span>
              <span class="brand-logo-text text-body-emphasis fs-8">BEE<span class="text-warning">STYLE</span></span>
            </div>
          </a>
        </div>

        <!-- Ô TÌM KIẾM NHANH TRÊN HEADER -->
        <div class="search-box navbar-top-search-box d-none d-lg-block" style="width: 25rem;">
          <form class="position-relative" action="{{ route('admin.orders.index') }}" method="GET">
            <input class="form-control search-input rounded-pill form-control-sm" type="search" name="q" placeholder="Tìm kiếm mã đơn, sản phẩm, SĐT khách hàng..." aria-label="Tìm kiếm" />
            <span class="fas fa-search search-box-icon"></span>
          </form>
        </div>

        <!-- ICONS & USER ACCOUNT MENU -->
        <ul class="navbar-nav navbar-nav-icons flex-row align-items-center gap-2">
          
          <!-- CHUYỂN ĐỔI CHẾ ĐỘ SÁNG / TỐI (DARK / LIGHT THEME) -->
          <li class="nav-item">
            <div class="theme-control-toggle feather-icon-wait px-2">
              <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
              <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Chuyển sang chế độ tối" style="height:32px;width:32px;">
                <span class="icon" data-feather="moon"></span>
              </label>
              <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Chuyển sang chế độ sáng" style="height:32px;width:32px;">
                <span class="icon" data-feather="sun"></span>
              </label>
            </div>
          </li>

          <!-- LINK NHANH TỚI WEB CLIENT -->
          <li class="nav-item d-none d-sm-block">
            <a class="nav-link px-2 text-body" href="{{ route('client.home') }}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Xem Cửa Hàng (Giao diện Client)">
              <span class="d-block" style="height:20px;width:20px;"><span data-feather="globe" style="height:20px;width:20px;"></span></span>
            </a>
          </li>

          <!-- USER PROFILE DROPDOWN -->
          <li class="nav-item dropdown">
            <a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
              <div class="avatar avatar-l">
                <img class="rounded-circle border" src="{{ asset(Auth::user()->avatar ?? 'assets/img/team/40x40/57.webp') }}" alt="{{ Auth::user()->name ?? 'Admin' }}" style="width: 36px; height: 36px; object-fit: cover;" />
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser" style="min-width: 240px;">
              <div class="card position-relative border-0">
                <div class="card-body p-0">
                  <div class="text-center pt-4 pb-3 border-bottom border-translucent">
                    <div class="avatar avatar-xl">
                      <img class="rounded-circle border" src="{{ asset(Auth::user()->avatar ?? 'assets/img/team/72x72/57.webp') }}" alt="{{ Auth::user()->name ?? 'Admin' }}" style="width: 56px; height: 56px; object-fit: cover;" />
                    </div>
                    <h6 class="mt-2 text-body-emphasis mb-0 fw-bold">{{ Auth::user()->name ?? 'Quản Trị Viên' }}</h6>
                    <p class="fs-10 text-body-tertiary mb-1">{{ Auth::user()->email ?? 'admin@beestyle.vn' }}</p>
                    <span class="badge badge-phoenix badge-phoenix-warning fs-10">Toàn Quyền Admin</span>
                  </div>
                </div>
                <div class="overflow-auto scrollbar" style="max-height: 14rem;">
                  <ul class="nav d-flex flex-column mb-2 pb-1">
                    <li class="nav-item">
                      <a class="nav-link px-3 d-flex align-items-center py-2" href="{{ route('admin.dashboard') }}">
                        <span class="me-2 text-body" data-feather="pie-chart"></span>
                        <span>Bảng điều khiển</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link px-3 d-flex align-items-center py-2" href="{{ route('client.home') }}" target="_blank">
                        <span class="me-2 text-body" data-feather="external-link"></span>
                        <span>Xem giao diện Website</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link px-3 d-flex align-items-center py-2" href="{{ route('client.profile') }}" target="_blank">
                        <span class="me-2 text-body" data-feather="user"></span>
                        <span>Hồ sơ cá nhân</span>
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="card-footer p-3 border-top border-translucent">
                  <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-phoenix-danger d-flex flex-center w-100 py-2">
                      <span class="me-2" data-feather="log-out"></span>Đăng Xuất Admin
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </li>

        </ul>
      </div>
    </nav>


    <!-- ===============================================-->
    <!--    MAIN CONTENT CONTAINER                      -->
    <!-- ===============================================-->
    <div class="content">

      <!-- FLASH NOTIFICATIONS (THÔNG BÁO HỆ THỐNG) -->
      @if(session('success'))
        <div class="alert alert-subtle-success alert-dismissible fade show d-flex align-items-center border-0 shadow-sm mb-4" role="alert">
          <i class="fa-solid fa-circle-check fs-6 me-2 text-success"></i>
          <div class="fw-semibold">{{ session('success') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-subtle-danger alert-dismissible fade show d-flex align-items-center border-0 shadow-sm mb-4" role="alert">
          <i class="fa-solid fa-circle-exclamation fs-6 me-2 text-danger"></i>
          <div class="fw-semibold">{{ session('error') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
      @endif

      @if(isset($errors) && $errors->any())
        <div class="alert alert-subtle-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
          <div class="d-flex align-items-center mb-1">
            <i class="fa-solid fa-triangle-exclamation fs-6 me-2 text-warning"></i>
            <strong>Vui lòng kiểm tra lại các trường thông tin:</strong>
          </div>
          <ul class="mb-0 small ps-4">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
      @endif

      <!-- MAIN PAGE CONTENT INSERTION -->
      @yield('content')

      <!-- PHOENIX ADMIN FOOTER -->
      <footer class="footer position-relative mt-6 pt-3 border-top border-translucent text-body-tertiary small d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          &copy; {{ date('Y') }} <strong class="text-body-highlight">BEESTYLE MENSWEAR</strong> - Hệ Thống Quản Trị Bán Hàng Trực Tuyến.
        </div>
        <div>
          Phiên bản Phoenix Admin 1.24.0 • Laravel 13.x • MySQL 8.x
        </div>
      </footer>

    </div>
  </main>

  <!-- ===============================================-->
  <!--    JAVASCRIPTS & LIBRARIES                     -->
  <!-- ===============================================-->
  <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
  <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
  <script src="{{ asset('vendors/anchorjs/anchor.min.js') }}"></script>
  <script src="{{ asset('vendors/is/is.min.js') }}"></script>
  <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
  <script src="{{ asset('vendors/lodash/lodash.min.js') }}"></script>
  <script src="{{ asset('vendors/list.js/list.min.js') }}"></script>
  <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
  <script src="{{ asset('vendors/dayjs/dayjs.min.js') }}"></script>
  <script src="{{ asset('assets/js/phoenix.js') }}"></script>

  <!-- Chart.js & SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof feather !== 'undefined') {
        feather.replace();
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
