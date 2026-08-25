@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">THÀNH VIÊN</span>
        <h3 class="fw-bold text-dark mb-0">Quản Lý Tài Khoản Khách Hàng</h3>
      </div>
      <p class="text-muted small mb-0">Theo dõi thông tin tài khoản đăng nhập, tổng chi tiêu tích lũy và toàn bộ lịch sử mua hàng</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('admin.orders.index') }}" class="btn btn-bee-primary btn-sm px-3">
        <i class="fa-solid fa-receipt me-1.5"></i> Quản Lý Đơn Hàng
      </a>
    </div>
  </div>
</div>

<!-- 4 THẺ THỐNG KÊ CHI TIÊU TOÀN DIỆN HỆ THỐNG KHÁCH HÀNG -->
<div class="row g-3 mb-4">
  <!-- 1. Tổng Chi Tiêu Toàn Bộ Khách Hàng -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm p-3.5 h-100" style="border-radius: 14px; background: #ffffff; border-left: 4px solid var(--bee-primary, #f59e0b) !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Chi Tiêu Tất Cả Khách Hàng</span>
        <div class="rounded-circle p-2 bg-warning-subtle text-warning" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-sack-dollar fs-6"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-1" style="font-size: 1.55rem;">{{ number_format($totalAllCustomersSpent, 0, ',', '.') }}₫</h3>
      <div class="text-muted small fs-11">
        <i class="fa-solid fa-circle-check text-success me-1"></i> Hoàn tất: <strong>{{ number_format($totalCompletedSpent, 0, ',', '.') }}₫</strong>
      </div>
    </div>
  </div>

  <!-- 2. Tài Khoản Đã Phát Sinh Đơn Hàng -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm p-3.5 h-100" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #06b6d4 !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tài Khoản Đã Mua Hàng</span>
        <div class="rounded-circle p-2 bg-info-subtle text-info" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-users-line fs-6"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-1" style="font-size: 1.55rem;">{{ $totalPurchasingAccounts }} <span class="fs-6 text-muted fw-normal">/ {{ $totalRegisteredCustomers }} tài khoản</span></h3>
      <div class="text-muted small fs-11">
        <i class="fa-solid fa-chart-pie text-info me-1"></i> Tỷ lệ mua: <strong>{{ $totalRegisteredCustomers > 0 ? round(($totalPurchasingAccounts / $totalRegisteredCustomers) * 100, 1) : 0 }}%</strong>
      </div>
    </div>
  </div>

  <!-- 3. Tổng Đơn Hàng Của Khách Hàng -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm p-3.5 h-100" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #10b981 !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Đơn Hàng Thành Công</span>
        <div class="rounded-circle p-2 bg-success-subtle text-success" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-cart-shopping fs-6"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-1" style="font-size: 1.55rem;">{{ $totalOrdersCount }} <span class="fs-6 text-muted fw-normal">đơn hàng</span></h3>
      <div class="text-muted small fs-11">
        <i class="fa-solid fa-bag-shopping text-success me-1"></i> Không tính các đơn đã hủy
      </div>
    </div>
  </div>

  <!-- 4. Chi Tiêu Trung Bình Mỗi Khách -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm p-3.5 h-100" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #8b5cf6 !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Chi Tiêu Trung Bình / Khách</span>
        <div class="rounded-circle p-2 bg-primary-subtle text-primary" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-calculator fs-6"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-1" style="font-size: 1.55rem;">{{ number_format($averageSpendPerCustomer, 0, ',', '.') }}₫</h3>
      <div class="text-muted small fs-11">
        <i class="fa-solid fa-arrow-trend-up text-primary me-1"></i> Tính trên các tài khoản đã mua
      </div>
    </div>
  </div>
</div>

<div class="bee-table-card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex align-items-center gap-2">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm kiếm tên, email, SĐT..." style="width: 280px;">
      <button type="submit" class="btn btn-sm btn-outline-secondary">Tìm kiếm</button>
      @if(request('q'))
        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>
    <div class="text-muted small">
      Tổng số: <strong>{{ $customers->total() }}</strong> khách hàng đăng ký
    </div>
  </div>

  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Khách Hàng (Tài khoản)</th>
          <th>Thông Tin Liên Hệ</th>
          <th>Ngày Tham Gia</th>
          <th>Đơn Đã Mua</th>
          <th>Tổng Chi Tiêu</th>
          <th>Hạng Thành Viên</th>
          <th>Trạng Thái</th>
          <th>Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $customer)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <img src="{{ asset($customer->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $customer->name }}" class="rounded-circle border bg-white" style="width: 42px; height: 42px; object-fit: cover;">
                <div>
                  <strong class="text-dark d-block small">{{ $customer->name }}</strong>
                  <span class="badge bg-light text-secondary border" style="font-size: 0.68rem;">{{ $customer->reviews_count }} đánh giá</span>
                </div>
              </div>
            </td>
            <td>
              <div class="small fw-semibold text-dark">{{ $customer->phone ?? 'Chưa cập nhật SĐT' }}</div>
              <small class="text-muted">{{ $customer->email }}</small>
            </td>
            <td><small class="text-muted">{{ $customer->created_at ? $customer->created_at->format('d/m/Y') : '' }}</small></td>
            <td><span class="badge bg-light text-dark border px-2 py-1 fw-bold">{{ $customer->orders_count }} đơn hàng</span></td>
            <td><strong class="text-danger">{{ number_format($customer->actual_total_spent ?? $customer->total_spent, 0, ',', '.') }}₫</strong></td>
            <td>
              <span class="badge bg-warning-subtle text-dark fw-bold px-2.5 py-1">
                <i class="fa-solid fa-crown me-1 text-warning"></i> {{ $customer->rank }}
              </span>
            </td>
            <td><span class="badge bg-success-subtle text-success fw-bold py-1 px-2"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span></td>
            <td>
              <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold text-nowrap py-1 px-2.5" style="font-size: 0.75rem;">
                <i class="fa-regular fa-eye me-1"></i> Xem Hồ Sơ
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="fa-solid fa-users fs-2 text-muted mb-2 d-block"></i>
              Chưa có dữ liệu khách hàng phù hợp.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($customers->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $customers->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
