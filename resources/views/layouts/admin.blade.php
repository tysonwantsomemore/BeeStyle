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
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
                  <div class="d-flex align-items-center">
                    <span class="nav-link-icon"><i class="fa-solid fa-copyright"></i></span>
                    <span class="nav-link-text">Thương Hiệu Thời Trang</span>
                  </div>
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
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}" href="{{ route('admin.returns.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-arrow-rotate-left text-danger"></i></span>
                  <span class="nav-link-text">Đổi Trả &amp; Hoàn Tiền</span>
                  @php
                    $pendingRmaBadge = \App\Models\OrderReturn::where('status', 'pending')->count();
                  @endphp
                  @if($pendingRmaBadge > 0)
                    <span class="badge bg-warning text-dark ms-auto fs-10 px-1.5 py-0.5 rounded-pill">{{ $pendingRmaBadge }}</span>
                  @endif
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}" href="{{ route('admin.revenue.monthly') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-sack-dollar text-warning"></i></span>
                  <span class="nav-link-text">Doanh Thu Tháng Này</span>
                </a>
              </div>
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-chart-line"></i></span>
                  <span class="nav-link-text">Báo Cáo &amp; Thống Kê</span>
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
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-user-shield"></i></span>
                  <span class="nav-link-text">Người Dùng &amp; Phân Quyền</span>
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
              <div class="nav-item-wrapper">
                <a class="nav-link {{ request()->routeIs('admin.daily-deals.*') ? 'active' : '' }}" href="{{ route('admin.daily-deals.index') }}">
                  <span class="nav-link-icon"><i class="fa-solid fa-bolt text-warning"></i></span>
                  <span class="nav-link-text">Ưu Đãi Trong Ngày</span>
                  <span class="badge bg-danger ms-auto fs-10 px-1.5 py-0.5 rounded-pill">HOT</span>
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
