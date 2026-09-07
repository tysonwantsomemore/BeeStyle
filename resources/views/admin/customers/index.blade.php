@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">THÀNH VIÊN</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Tài Khoản Khách Hàng</h2>
    </div>
    <p class="text-body-tertiary mb-0">Theo dõi thông tin tài khoản đăng nhập, tổng chi tiêu tích lũy và toàn bộ lịch sử mua hàng</p>
  </div>
  <div class="col-auto">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-phoenix-secondary">
      <i class="fa-solid fa-receipt me-1"></i> Quản Lý Đơn Hàng
    </a>
  </div>
</div>

<!-- 4 THẺ THỐNG KÊ CHI TIÊU -->
<div class="row g-3 mb-4">
  <!-- 1. Tổng Chi Tiêu -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Tổng Chi Tiêu Khách Hàng</h6>
            <h3 class="text-body-emphasis mb-0 fw-bold">{{ number_format($totalAllCustomersSpent, 0, ',', '.') }}₫</h3>
          </div>
          <div class="rounded-circle p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-sack-dollar fs-8"></i>
          </div>
        </div>
        <div class="text-body-tertiary fs-10">
          <i class="fa-solid fa-circle-check text-success me-1"></i> Hoàn tất: <strong>{{ number_format($totalCompletedSpent, 0, ',', '.') }}₫</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Tài Khoản Đã Mua Hàng -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Tài Khoản Đã Mua Hàng</h6>
            <h3 class="text-info mb-0 fw-bold">{{ $totalPurchasingAccounts }} <span class="fs-9 text-body-tertiary fw-normal">/ {{ $totalRegisteredCustomers }}</span></h3>
          </div>
          <div class="rounded-circle p-2 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-users-line fs-8"></i>
          </div>
        </div>
        <div class="text-body-tertiary fs-10">
          <i class="fa-solid fa-chart-pie text-info me-1"></i> Tỷ lệ mua: <strong>{{ $totalRegisteredCustomers > 0 ? round(($totalPurchasingAccounts / $totalRegisteredCustomers) * 100, 1) : 0 }}%</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Tổng Đơn Hàng Thành Công -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Đơn Hàng Thành Công</h6>
            <h3 class="text-success mb-0 fw-bold">{{ $totalOrdersCount }} <span class="fs-9 text-body-tertiary fw-normal">đơn</span></h3>
          </div>
          <div class="rounded-circle p-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-cart-shopping fs-8"></i>
          </div>
        </div>
        <div class="text-body-tertiary fs-10">
          <i class="fa-solid fa-bag-shopping text-success me-1"></i> Không tính các đơn đã hủy
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Chi Tiêu Trung Bình / Khách -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Chi Tiêu TB / Khách</h6>
            <h3 class="text-body-emphasis mb-0 fw-bold">{{ number_format($averageSpendPerCustomer, 0, ',', '.') }}₫</h3>
          </div>
          <div class="rounded-circle p-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-calculator fs-8"></i>
          </div>
        </div>
        <div class="text-body-tertiary fs-10">
          <i class="fa-solid fa-arrow-trend-up text-primary me-1"></i> Tính trên các tài khoản đã mua
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CUSTOMERS TABLE CARD -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex align-items-center gap-2">
      <div class="position-relative">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm search-input" placeholder="Tìm tên, email, SĐT..." style="width: 240px;">
      </div>
      <button type="submit" class="btn btn-sm btn-phoenix-secondary">Tìm kiếm</button>
      @if(request('q'))
        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>
    <div class="text-body-tertiary fs-10">
      Tổng số: <strong class="text-body-emphasis">{{ $customers->total() }}</strong> khách hàng đăng ký
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2">Khách Hàng (Tài khoản)</th>
            <th class="py-2">Thông Tin Liên Hệ</th>
            <th class="py-2">Ngày Tham Gia</th>
            <th class="py-2">Đơn Đã Mua</th>
            <th class="py-2">Tổng Chi Tiêu</th>
            <th class="py-2">Hạng Thành Viên</th>
            <th class="py-2">Trạng Thái</th>
            <th class="text-end pe-3 py-2">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($customers as $customer)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <td class="ps-3 py-2">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($customer->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $customer->name }}" class="rounded-circle border border-translucent bg-body-emphasis" style="width: 36px; height: 36px; object-fit: cover;">
                  <div>
                    <strong class="text-body-emphasis d-block fs-9">{{ $customer->name }}</strong>
                    <span class="badge badge-phoenix badge-phoenix-secondary fs-11">{{ $customer->reviews_count }} đánh giá</span>
                  </div>
                </div>
              </td>
              <td class="py-2">
                <div class="fw-semibold text-body-emphasis fs-10">{{ $customer->phone ?? 'Chưa cập nhật SĐT' }}</div>
                <small class="text-body-tertiary fs-11">{{ $customer->email }}</small>
              </td>
              <td class="py-2"><small class="text-body-tertiary fs-10">{{ $customer->created_at ? $customer->created_at->format('d/m/Y') : '' }}</small></td>
              <td class="py-2"><span class="badge badge-phoenix badge-phoenix-secondary fs-10">{{ $customer->orders_count }} đơn</span></td>
              <td class="py-2"><strong class="text-danger fs-9">{{ number_format($customer->actual_total_spent ?? $customer->total_spent, 0, ',', '.') }}₫</strong></td>
              <td class="py-2">
                <span class="badge badge-phoenix badge-phoenix-warning fs-10">
                  <i class="fa-solid fa-crown me-1 text-warning"></i> {{ $customer->rank }}
                </span>
              </td>
              <td class="py-2"><span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span></td>
              <td class="text-end pe-3 py-2">
                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10">
                  <i class="fa-regular fa-eye me-1"></i> Xem Hồ Sơ
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-users fs-4 text-body-tertiary mb-2 d-block"></i>
                Chưa có dữ liệu khách hàng phù hợp.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($customers->hasPages())
    <div class="card-footer d-flex justify-content-center py-3 bg-body-emphasis border-top border-translucent">
      {{ $customers->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
