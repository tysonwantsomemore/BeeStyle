@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng | BeeStyle Admin')

@section('content')
<!-- BREADCRUMB & HEADER -->
<div class="mb-4">
  <div class="row gy-3 justify-content-between align-items-center">
    <div class="col-md">
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">
          <i class="fa-solid fa-users-gear me-1"></i> THÀNH VIÊN BEESTYLE
        </span>
        <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Tài Khoản Khách Hàng</h2>
      </div>
      <p class="text-body-tertiary mb-0 fs-9">
        Quản lý tất cả khách hàng đăng nhập, thống kê chi tiêu tích lũy, hạng VIP và lịch sử giao dịch mua sắm
      </p>
    </div>
    <div class="col-auto">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('admin.customers.export', request()->query()) }}" class="btn btn-phoenix-success btn-sm">
          <i class="fa-solid fa-file-excel me-1 text-success"></i> Xuất File Excel / CSV
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-phoenix-secondary btn-sm">
          <i class="fa-solid fa-receipt me-1 text-primary"></i> Quản Lý Đơn Hàng
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-phoenix-secondary btn-sm">
          <i class="fa-solid fa-shield me-1 text-info"></i> Phân Quyền Quản Trị
        </a>
      </div>
    </div>
  </div>
</div>

<!-- THÔNG BÁO FLASH -->
@if(session('success'))
  <div class="alert alert-subtle-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <i class="fa-solid fa-circle-check fs-6 text-success me-2"></i>
    <div class="flex-grow-1 fs-9 fw-semibold text-body-emphasis">{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-subtle-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <i class="fa-solid fa-triangle-exclamation fs-6 text-danger me-2"></i>
    <div class="flex-grow-1 fs-9 fw-semibold text-body-emphasis">{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- 4 THẺ THỐNG KÊ KPI CAO CẤP -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng Khách Hàng -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body-emphasis">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-bold fs-10 tracking-wider">Tổng Khách Hàng</h6>
            <h3 class="text-body-emphasis mb-0 fw-bolder">{{ number_format($totalRegisteredCustomers) }} <span class="fs-9 fw-normal text-muted">thành viên</span></h3>
          </div>
          <div class="rounded-3 p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-xs" style="width: 46px; height: 46px;">
            <i class="fa-solid fa-users fs-7"></i>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2 pt-2 border-top border-translucent fs-10">
          <span class="badge badge-phoenix badge-phoenix-success py-0.5 px-1.5 fs-11">
            <i class="fa-solid fa-user-plus me-1"></i> +{{ $newCustomersThisMonth }}
          </span>
          <span class="text-body-tertiary">Đăng ký mới tháng này</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Khách Đã Mua Hàng -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body-emphasis">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-bold fs-10 tracking-wider">Khách Đã Mua Hàng</h6>
            <h3 class="text-info mb-0 fw-bolder">{{ number_format($totalPurchasingAccounts) }} <span class="fs-9 fw-normal text-muted">/ {{ $totalRegisteredCustomers }}</span></h3>
          </div>
          <div class="rounded-3 p-2 bg-info-subtle text-info d-flex align-items-center justify-content-center shadow-xs" style="width: 46px; height: 46px;">
            <i class="fa-solid fa-cart-shopping fs-7"></i>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent fs-10">
          <span class="text-body-tertiary">Tỷ lệ kích hoạt mua:</span>
          <strong class="text-info font-monospace">{{ $totalRegisteredCustomers > 0 ? round(($totalPurchasingAccounts / $totalRegisteredCustomers) * 100, 1) : 0 }}%</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Doanh Thu Thành Viên -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body-emphasis">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-bold fs-10 tracking-wider">Doanh Thu Thành Viên</h6>
            <h3 class="text-success mb-0 fw-bolder">{{ number_format($registeredTotalSpent, 0, ',', '.') }}₫</h3>
          </div>
          <div class="rounded-3 p-2 bg-success-subtle text-success d-flex align-items-center justify-content-center shadow-xs" style="width: 46px; height: 46px;">
            <i class="fa-solid fa-sack-dollar fs-7"></i>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent fs-10">
          <span class="text-body-tertiary">Toàn shop (kèm vãng lai):</span>
          <strong class="text-body-emphasis font-monospace">{{ number_format($totalAllCustomersSpent, 0, ',', '.') }}₫</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Chi Tiêu Trung Bình & VIP -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body-emphasis">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-bold fs-10 tracking-wider">Chi Tiêu TB / Khách</h6>
            <h3 class="text-warning mb-0 fw-bolder">{{ number_format($averageSpendPerCustomer, 0, ',', '.') }}₫</h3>
          </div>
          <div class="rounded-3 p-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center shadow-xs" style="width: 46px; height: 46px;">
            <i class="fa-solid fa-crown fs-7"></i>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent fs-10">
          <span class="text-body-tertiary">Khách VIP (≥5Tr): <strong class="text-warning">{{ $vipCustomersCount }}</strong></span>
          <span class="text-body-tertiary">Tài khoản bị khóa: <strong class="text-danger">{{ $totalBannedAccounts }}</strong></span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BỘ LỌC TÌM KIẾM ĐA NĂNG -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-3">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-center">
      <!-- Tìm kiếm từ khóa -->
      <div class="col-12 col-md-4 col-lg-3">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-body-tertiary border-end-0 text-body-tertiary">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Tên, Email, SĐT, #CUST..." aria-label="Tìm kiếm">
        </div>
      </div>

      <!-- Lọc Trạng Thái -->
      <div class="col-6 col-md-2 col-lg-2">
        <select name="status" class="form-select form-select-sm">
          <option value="">-- Trạng thái --</option>
          <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
          <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Bị khóa</option>
        </select>
      </div>

      <!-- Lọc Hạng Thành Viên -->
      <div class="col-6 col-md-3 col-lg-2">
        <select name="tier" class="form-select form-select-sm">
          <option value="">-- Hạng thành viên --</option>
          <option value="diamond" {{ request('tier') === 'diamond' ? 'selected' : '' }}>💎 VIP Kim Cương (≥10Tr)</option>
          <option value="gold" {{ request('tier') === 'gold' ? 'selected' : '' }}>🥇 VIP Vàng (≥5Tr)</option>
          <option value="silver" {{ request('tier') === 'silver' ? 'selected' : '' }}>🥈 Hội Viên Bạc (≥2Tr)</option>
          <option value="bronze" {{ request('tier') === 'bronze' ? 'selected' : '' }}>🥉 Thành Viên Đồng (&lt;2Tr)</option>
        </select>
      </div>

      <!-- Lọc Hành Vi Mua Sắm -->
      <div class="col-6 col-md-3 col-lg-2">
        <select name="behavior" class="form-select form-select-sm">
          <option value="">-- Lịch sử mua --</option>
          <option value="has_orders" {{ request('behavior') === 'has_orders' ? 'selected' : '' }}>Đã có đơn hàng</option>
          <option value="no_orders" {{ request('behavior') === 'no_orders' ? 'selected' : '' }}>Chưa từng mua</option>
        </select>
      </div>

      <!-- Sắp Xếp -->
      <div class="col-6 col-md-3 col-lg-2">
        <select name="sort" class="form-select form-select-sm">
          <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Mới đăng ký nhất</option>
          <option value="spent_desc" {{ request('sort') === 'spent_desc' ? 'selected' : '' }}>Chi tiêu cao nhất</option>
          <option value="spent_asc" {{ request('sort') === 'spent_asc' ? 'selected' : '' }}>Chi tiêu thấp nhất</option>
          <option value="orders_desc" {{ request('sort') === 'orders_desc' ? 'selected' : '' }}>Đơn mua nhiều nhất</option>
          <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Tên A &rarr; Z</option>
          <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
        </select>
      </div>

      <!-- Nút Thao Tác Lọc -->
      <div class="col-12 col-lg-1 d-flex gap-1 justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm flex-fill" title="Áp dụng bộ lọc">
          <i class="fa-solid fa-filter"></i> Lọc
        </button>
        @if(request()->hasAny(['q', 'status', 'tier', 'behavior', 'sort']) && (request('q') || request('status') || request('tier') || request('behavior') || request('sort') !== 'latest'))
          <a href="{{ route('admin.customers.index') }}" class="btn btn-phoenix-secondary btn-sm" title="Đặt lại bộ lọc">
            <i class="fa-solid fa-rotate-left"></i>
          </a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- BẢNG DANH SÁCH KHÁCH HÀNG -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <h5 class="mb-0 text-body-emphasis fw-bold">Danh Sách Tài Khoản Khách Hàng</h5>
      <span class="badge badge-phoenix badge-phoenix-primary fs-10 fw-bold">{{ $customers->total() }} khách hàng</span>
    </div>
    <div class="text-body-tertiary fs-10">
      Hiển thị trang <strong>{{ $customers->currentPage() }}</strong> / <strong>{{ $customers->lastPage() ?: 1 }}</strong>
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-hover table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary border-bottom border-translucent">
          <tr>
            <th class="ps-3 py-3 text-uppercase" style="min-width: 240px;">Khách Hàng (Tài Khoản)</th>
            <th class="py-3 text-uppercase" style="min-width: 150px;">Liên Hệ &amp; Xác Thực</th>
            <th class="py-3 text-uppercase" style="min-width: 120px;">Ngày Tham Gia</th>
            <th class="py-3 text-uppercase text-center" style="min-width: 90px;">Đơn Hàng</th>
            <th class="py-3 text-uppercase" style="min-width: 130px;">Tổng Chi Tiêu</th>
            <th class="py-3 text-uppercase" style="min-width: 140px;">Hạng VIP &amp; Điểm</th>
            <th class="py-3 text-uppercase text-center" style="min-width: 110px;">Trạng Thái</th>
            <th class="text-end pe-3 py-3 text-uppercase" style="min-width: 120px;">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($customers as $customer)
            @php
              $totalSpent = (int) ($customer->actual_total_spent ?? $customer->total_spent ?? 0);
              $isBanned = ($customer->status === 'banned');
            @endphp
            <tr class="hover-actions-trigger btn-reveal-trigger border-bottom border-translucent {{ $isBanned ? 'bg-danger-subtle bg-opacity-25' : '' }}">
              <!-- 1. Tên & Avatar -->
              <td class="ps-3 py-3">
                <div class="d-flex align-items-center gap-2.5">
                  <div class="position-relative">
                    <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}" class="rounded-circle border border-translucent shadow-xs" style="width: 40px; height: 40px; object-fit: cover;">
                    @if($totalSpent >= 10000000)
                      <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning text-dark p-0.5 border border-white" title="VIP Kim Cương">
                        <i class="fa-solid fa-crown" style="font-size: 8px;"></i>
                      </span>
                    @endif
                  </div>
                  <div>
                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="fw-bold text-body-emphasis text-decoration-none d-block fs-9 hover-primary">
                      {{ $customer->name }}
                    </a>
                    <div class="d-flex align-items-center gap-1">
                      <span class="text-body-tertiary fs-11 font-monospace">#CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</span>
                      <span class="text-muted fs-11">&bull;</span>
                      <span class="text-muted fs-11">{{ $customer->reviews_count }} đánh giá</span>
                    </div>
                  </div>
                </div>
              </td>

              <!-- 2. Thông tin liên hệ -->
              <td class="py-3">
                <div class="fw-semibold text-body-emphasis fs-10">
                  <i class="fa-solid fa-phone text-muted me-1 fs-11"></i>
                  {{ $customer->phone ?? 'Chưa cập nhật SĐT' }}
                </div>
                <div class="text-body-tertiary fs-11 text-truncate" style="max-width: 180px;" title="{{ $customer->email }}">
                  <i class="fa-regular fa-envelope text-muted me-1 fs-11"></i>
                  {{ $customer->email }}
                </div>
              </td>

              <!-- 3. Ngày tham gia -->
              <td class="py-3">
                <div class="text-body-emphasis fs-10 fw-semibold">
                  {{ $customer->created_at ? $customer->created_at->format('d/m/Y') : 'N/A' }}
                </div>
                <small class="text-body-tertiary fs-11">
                  {{ $customer->created_at ? $customer->created_at->format('H:i') : '' }} ({{ $customer->created_at ? $customer->created_at->diffForHumans() : '' }})
                </small>
              </td>

              <!-- 4. Số đơn hàng -->
              <td class="py-3 text-center">
                @if($customer->orders_count > 0)
                  <a href="{{ route('admin.customers.show', $customer->id) }}#cust-orders" class="badge badge-phoenix badge-phoenix-primary fs-10 text-decoration-none" title="Xem danh sách đơn hàng">
                    <i class="fa-solid fa-bag-shopping me-1"></i> {{ $customer->orders_count }} đơn
                  </a>
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary fs-10">0 đơn</span>
                @endif
              </td>

              <!-- 5. Tổng chi tiêu -->
              <td class="py-3">
                <strong class="text-danger fs-9 font-monospace d-block">
                  {{ number_format($totalSpent, 0, ',', '.') }}₫
                </strong>
                @if($customer->orders_count > 0 && $totalSpent > 0)
                  <small class="text-body-tertiary fs-11">TB: {{ number_format(round($totalSpent / $customer->orders_count), 0, ',', '.') }}₫/đơn</small>
                @else
                  <small class="text-muted fs-11">Chưa phát sinh</small>
                @endif
              </td>

              <!-- 6. Hạng thành viên & Điểm thưởng -->
              <td class="py-3">
                <span class="badge badge-phoenix {{ $customer->rank_badge_class ?? 'badge-phoenix-secondary' }} fs-10 mb-1 d-inline-block">
                  <i class="fa-solid fa-award me-1"></i> {{ $customer->rank }}
                </span>
                <div class="text-body-tertiary fs-11">
                  <i class="fa-solid fa-coins text-warning me-1"></i> <strong>{{ number_format($customer->points ?? 0) }}</strong> điểm
                </div>
              </td>

              <!-- 7. Trạng thái tài khoản (Đồng bộ chính xác) -->
              <td class="py-3 text-center">
                @if($isBanned)
                  <span class="badge badge-phoenix badge-phoenix-danger fs-10" title="Tài khoản đang bị khóa">
                    <i class="fa-solid fa-ban me-1"></i> Bị khóa
                  </span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-success fs-10" title="Tài khoản đang hoạt động bình thường">
                    <i class="fa-solid fa-circle-check me-1"></i> Hoạt động
                  </span>
                @endif
              </td>

              <!-- 8. Thao tác -->
              <td class="text-end pe-3 py-3">
                <div class="d-inline-flex align-items-center gap-1">
                  <!-- Xem hồ sơ -->
                  <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10" title="Xem chi tiết hồ sơ & lịch sử đơn hàng">
                    <i class="fa-regular fa-eye me-1"></i> Xem
                  </a>

                  <!-- Menu thao tác bổ sung -->
                  <div class="dropdown font-sans-serif">
                    <button class="btn btn-sm btn-phoenix-secondary dropdown-toggle dropdown-caret-none py-1 px-2 fs-10" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end py-1 shadow-sm fs-10">
                      <!-- Xem chi tiết -->
                      <a class="dropdown-item py-1.5" href="{{ route('admin.customers.show', $customer->id) }}">
                        <i class="fa-regular fa-id-card me-2 text-primary"></i> Xem toàn bộ hồ sơ
                      </a>

                      <!-- Chỉnh sửa nhanh -->
                      <button type="button" class="dropdown-item py-1.5" data-bs-toggle="modal" data-bs-target="#editCustomerModal-{{ $customer->id }}">
                        <i class="fa-regular fa-pen-to-square me-2 text-info"></i> Sửa thông tin
                      </button>

                      <div class="dropdown-divider my-1"></div>

                      <!-- Khóa / Mở khóa tài khoản -->
                      <form action="{{ route('admin.customers.toggleStatus', $customer->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn {{ $isBanned ? 'MỞ KHÓA' : 'KHÓA' }} tài khoản của khách hàng {{ addslashes($customer->name) }}?');">
                        @csrf
                        @method('PATCH')
                        @if($isBanned)
                          <button type="submit" class="dropdown-item py-1.5 text-success">
                            <i class="fa-solid fa-lock-open me-2"></i> Mở khóa tài khoản
                          </button>
                        @else
                          <button type="submit" class="dropdown-item py-1.5 text-danger">
                            <i class="fa-solid fa-lock me-2"></i> Khóa tài khoản
                          </button>
                        @endif
                      </form>

                      <!-- Gửi Email trực tiếp -->
                      <a class="dropdown-item py-1.5" href="mailto:{{ $customer->email }}?subject=Liên hệ từ Quản trị viên BeeStyle">
                        <i class="fa-regular fa-envelope me-2 text-secondary"></i> Gửi email liên hệ
                      </a>
                    </div>
                  </div>
                </div>

                <!-- MODAL SỬA NHANH KHÁCH HÀNG -->
                <div class="modal fade text-start" id="editCustomerModal-{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                          <h6 class="modal-title fw-bold">
                            <i class="fa-solid fa-user-pen me-2 text-primary"></i>
                            Cập Nhật Khách Hàng #CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
                          </h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $customer->name }}" required>
                          </div>

                          <div class="row g-2 mb-3">
                            <div class="col-6">
                              <label class="form-label">Số điện thoại</label>
                              <input type="text" name="phone" class="form-control form-control-sm" value="{{ $customer->phone }}">
                            </div>
                            <div class="col-6">
                              <label class="form-label">Giới tính</label>
                              <select name="gender" class="form-select form-select-sm">
                                <option value="Nam" {{ ($customer->gender ?? 'Nam') === 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ ($customer->gender ?? '') === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ ($customer->gender ?? '') === 'Khác' ? 'selected' : '' }}>Khác</option>
                              </select>
                            </div>
                          </div>

                          <div class="row g-2 mb-3">
                            <div class="col-6">
                              <label class="form-label">Ngày sinh</label>
                              <input type="date" name="dob" class="form-control form-control-sm" value="{{ $customer->dob ? date('Y-m-d', strtotime($customer->dob)) : '' }}">
                            </div>
                            <div class="col-6">
                              <label class="form-label">Điểm tích lũy</label>
                              <input type="number" name="points" min="0" class="form-control form-control-sm" value="{{ $customer->points ?? 0 }}">
                            </div>
                          </div>

                          <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" class="form-control form-control-sm" value="{{ $customer->address }}" placeholder="Số nhà, tên đường...">
                          </div>

                          <div class="row g-2 mb-3">
                            <div class="col-6">
                              <label class="form-label">Quận / Huyện</label>
                              <input type="text" name="district" class="form-control form-control-sm" value="{{ $customer->district }}">
                            </div>
                            <div class="col-6">
                              <label class="form-label">Tỉnh / Thành phố</label>
                              <input type="text" name="city" class="form-control form-control-sm" value="{{ $customer->city ?? 'Hồ Chí Minh' }}">
                            </div>
                          </div>

                          <div class="mb-3">
                            <label class="form-label">Trạng thái tài khoản <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm" required>
                              <option value="active" {{ $customer->status === 'active' ? 'selected' : '' }}>Hoạt động (Active)</option>
                              <option value="banned" {{ $customer->status === 'banned' ? 'selected' : '' }}>Khóa tài khoản (Banned)</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                          <button type="submit" class="btn btn-primary btn-sm">Lưu Thay Đổi</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-users-slash fs-3 text-body-tertiary mb-3 d-block"></i>
                <h6 class="fw-bold text-body-emphasis">Không tìm thấy khách hàng nào phù hợp!</h6>
                <p class="fs-10 text-body-tertiary mb-3">Vui lòng thử điều chỉnh lại từ khóa tìm kiếm hoặc các điều kiện lọc phía trên.</p>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-phoenix-primary btn-sm">
                  <i class="fa-solid fa-rotate-left me-1"></i> Xóa tất cả bộ lọc
                </a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($customers->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-3 bg-body-emphasis border-top border-translucent flex-wrap gap-2">
      <div class="text-body-tertiary fs-10">
        Hiển thị từ <strong>{{ $customers->firstItem() }}</strong> đến <strong>{{ $customers->lastItem() }}</strong> trên tổng số <strong>{{ $customers->total() }}</strong> khách hàng
      </div>
      <div>
        {{ $customers->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>
@endsection
