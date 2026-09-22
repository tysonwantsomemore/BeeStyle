@extends('layouts.admin')

@section('title', 'Quản Lý Người Dùng & Phân Quyền | BeeStyle Admin')

@section('content')
<!-- BREADCRUMB & HEADER -->
<div class="mb-4">
  <div class="row gy-3 justify-content-between align-items-center">
    <div class="col-md">
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge badge-phoenix badge-phoenix-primary fs-10 fw-bold px-2 py-1">
          <i class="fa-solid fa-shield-halved me-1"></i> BẢO MẬT &amp; PHÂN QUYỀN
        </span>
        <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Người Dùng &amp; Phân Quyền</h2>
      </div>
      <p class="text-body-tertiary mb-0 fs-9">
        Quản lý tài khoản quản trị viên và thành viên, phân quyền hạn truy cập, cấp phát mật khẩu và kiểm soát an ninh hệ thống
      </p>
    </div>
    <div class="col-auto">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Nút Thêm Mới Quản Trị Viên -->
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
          <i class="fa-solid fa-user-plus me-1"></i> Thêm Quản Trị Viên Mới
        </button>

        <!-- Nút Xuất File Excel / CSV -->
        <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-phoenix-success btn-sm">
          <i class="fa-solid fa-file-excel me-1 text-success"></i> Xuất File Excel / CSV
        </a>

        <!-- Nút liên kết sang Quản lý khách hàng -->
        <a href="{{ route('admin.customers.index') }}" class="btn btn-phoenix-secondary btn-sm">
          <i class="fa-solid fa-users me-1 text-info"></i> Quản Lý Khách Hàng (CRM)
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

@if(isset($errors) && $errors->any())
  <div class="alert alert-subtle-danger alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center mb-1">
      <i class="fa-solid fa-circle-xmark fs-6 text-danger me-2"></i>
      <strong class="fs-9 text-body-emphasis">Có lỗi xảy ra, vui lòng kiểm tra lại dữ liệu nhập:</strong>
    </div>
    <ul class="mb-0 ps-3 fs-10 text-danger">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- 4 THẺ THỐNG KÊ NGƯỜI DÙNG CHUẨN PHOENIX -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng Tài Khoản -->
  <div class="col-6 col-md-3">
    <div class="card h-100 border-0 shadow-sm bg-body-emphasis">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fs-10 fw-bold tracking-wider">Tổng Tài Khoản</h6>
            <h3 class="text-body-highlight mb-0 fw-bolder">{{ number_format($stats['total']) }}</h3>
          </div>
          <div class="rounded-3 p-2.5 bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-users fs-7"></i>
          </div>
        </div>
        <div class="pt-2 border-top border-translucent fs-10 text-body-tertiary">
          <i class="fa-solid fa-calendar-plus text-primary me-1"></i> Tháng này: <strong>+{{ $stats['newThisMonth'] }}</strong> tài khoản mới
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Quản Trị Viên -->
  <div class="col-6 col-md-3">
    <div class="card h-100 border-0 shadow-sm bg-body-emphasis">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fs-10 fw-bold tracking-wider">Quản Trị Viên (Admin)</h6>
            <h3 class="text-primary mb-0 fw-bolder">{{ number_format($stats['admins']) }}</h3>
          </div>
          <div class="rounded-3 p-2.5 bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-shield-halved fs-7"></i>
          </div>
        </div>
        <div class="pt-2 border-top border-translucent fs-10 text-body-tertiary">
          <i class="fa-solid fa-key text-primary me-1"></i> Toàn quyền quản trị hệ thống
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Khách Hàng -->
  <div class="col-6 col-md-3">
    <div class="card h-100 border-0 shadow-sm bg-body-emphasis">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fs-10 fw-bold tracking-wider">Khách Hàng (Customer)</h6>
            <h3 class="text-success mb-0 fw-bolder">{{ number_format($stats['customers']) }}</h3>
          </div>
          <div class="rounded-3 p-2.5 bg-success-subtle text-success d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-user-check fs-7"></i>
          </div>
        </div>
        <div class="pt-2 border-top border-translucent fs-10 text-body-tertiary">
          <i class="fa-solid fa-cart-shopping text-success me-1"></i> Mua sắm &amp; giao dịch tại storefront
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Đang Bị Khóa -->
  <div class="col-6 col-md-3">
    <div class="card h-100 border-0 shadow-sm bg-body-emphasis">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fs-10 fw-bold tracking-wider">Đang Bị Khóa</h6>
            <h3 class="text-danger mb-0 fw-bolder">{{ number_format($stats['locked']) }}</h3>
          </div>
          <div class="rounded-3 p-2.5 bg-danger-subtle text-danger d-flex align-items-center justify-content-center shadow-xs" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-user-lock fs-7"></i>
          </div>
        </div>
        <div class="pt-2 border-top border-translucent fs-10 text-body-tertiary">
          <i class="fa-solid fa-shield-check text-info me-1"></i> Đã xác thực: <strong>{{ $stats['verified'] }}</strong> tài khoản
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BỘ LỌC TÌM KIẾM ĐA NĂNG -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-3">
    <form class="row g-2 align-items-center" method="GET" action="{{ route('admin.users.index') }}">
      <!-- Tìm kiếm từ khóa -->
      <div class="col-12 col-md-4 col-lg-3">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-body-tertiary border-end-0 text-body-tertiary">
            <i class="fa-solid fa-magnifying-glass"></i>
          </span>
          <input class="form-control border-start-0" name="q" value="{{ $search }}" placeholder="Tên, email, SĐT, ID..." aria-label="Tìm kiếm">
        </div>
      </div>

      <!-- Lọc Vai Trò -->
      <div class="col-6 col-md-2 col-lg-2">
        <select class="form-select form-select-sm" name="role">
          <option value="">-- Tất cả vai trò --</option>
          <option value="admin" @selected($role === 'admin')>Quản trị viên (Admin)</option>
          <option value="customer" @selected($role === 'customer')>Khách hàng (Customer)</option>
        </select>
      </div>

      <!-- Lọc Trạng Thái -->
      <div class="col-6 col-md-2 col-lg-2">
        <select class="form-select form-select-sm" name="status">
          <option value="">-- Tất cả trạng thái --</option>
          <option value="active" @selected($status === 'active')>Đang hoạt động</option>
          <option value="banned" @selected($status === 'banned')>Đang bị khóa</option>
        </select>
      </div>

      <!-- Lọc Bảo Mật -->
      <div class="col-6 col-md-2 col-lg-2">
        <select class="form-select form-select-sm" name="security">
          <option value="">-- Tình trạng bảo mật --</option>
          <option value="verified" @selected(($security ?? '') === 'verified')>Đã xác thực (Email/SĐT)</option>
          <option value="unverified" @selected(($security ?? '') === 'unverified')>Chưa xác thực</option>
          <option value="temp_locked" @selected(($security ?? '') === 'temp_locked')>Khóa tạm thời (Sai pass)</option>
        </select>
      </div>

      <!-- Sắp Xếp -->
      <div class="col-6 col-md-2 col-lg-2">
        <select class="form-select form-select-sm" name="sort">
          <option value="latest" @selected(($sort ?? 'latest') === 'latest')>Mới tham gia nhất</option>
          <option value="admin_first" @selected(($sort ?? '') === 'admin_first')>Quản trị viên trước</option>
          <option value="orders_desc" @selected(($sort ?? '') === 'orders_desc')>Số đơn hàng nhiều nhất</option>
          <option value="name_asc" @selected(($sort ?? '') === 'name_asc')>Tên A &rarr; Z</option>
          <option value="oldest" @selected(($sort ?? '') === 'oldest')>Cũ nhất</option>
        </select>
      </div>

      <!-- Nút Lọc & Đặt Lại -->
      <div class="col-12 col-lg-1 d-flex gap-1 justify-content-end">
        <button class="btn btn-sm btn-primary flex-fill" type="submit" title="Áp dụng bộ lọc">
          <i class="fa-solid fa-filter"></i> Lọc
        </button>
        @if(request()->hasAny(['q', 'role', 'status', 'security', 'sort']) && (request('q') || request('role') || request('status') || request('security') || request('sort') !== 'latest'))
          <a class="btn btn-sm btn-phoenix-secondary" href="{{ route('admin.users.index') }}" title="Đặt lại bộ lọc">
            <i class="fa-solid fa-rotate-left"></i>
          </a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- BẢNG DANH SÁCH NGƯỜI DÙNG & PHÂN QUYỀN -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <h5 class="mb-0 text-body-emphasis fw-bold">Danh Sách Tài Khoản &amp; Quyền Hạn</h5>
      <span class="badge badge-phoenix badge-phoenix-primary fs-10 fw-bold">{{ $users->total() }} tài khoản</span>
    </div>
    <div class="text-body-tertiary fs-10">
      Hiển thị trang <strong>{{ $users->currentPage() }}</strong> / <strong>{{ $users->lastPage() ?: 1 }}</strong>
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-hover table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary border-bottom border-translucent">
          <tr>
            <th class="ps-3 py-3 text-uppercase" style="min-width: 240px;">Tài Khoản &amp; Người Dùng</th>
            <th class="py-3 text-uppercase" style="min-width: 170px;">Liên Hệ &amp; Xác Thực</th>
            <th class="py-3 text-uppercase text-center" style="min-width: 100px;">Đơn Hàng</th>
            <th class="py-3 text-uppercase" style="min-width: 150px;">Vai Trò Hiện Tại</th>
            <th class="py-3 text-uppercase" style="min-width: 140px;">Trạng Thái &amp; Bảo Mật</th>
            <th class="text-end pe-3 py-3 text-uppercase" style="min-width: 140px;">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($users as $user)
            @php
              $isCurrentUser = ($user->id === auth()->id());
              $isAdmin = ($user->role === 'admin');
              $isBanned = ($user->status === 'banned');
              $isTempLocked = (!empty($user->locked_until) && now()->isBefore($user->locked_until));
            @endphp
            <tr class="hover-actions-trigger btn-reveal-trigger border-bottom border-translucent {{ $isCurrentUser ? 'bg-primary-subtle bg-opacity-25' : ($isBanned ? 'bg-danger-subtle bg-opacity-25' : '') }}">
              <!-- 1. Tên, Avatar, ID -->
              <td class="ps-3 py-3">
                <div class="d-flex align-items-center gap-2.5">
                  <div class="position-relative">
                    <img class="rounded-circle border {{ $isAdmin ? 'border-primary border-2' : 'border-translucent' }} shadow-xs" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width: 40px; height: 40px; object-fit: cover;">
                    @if($isAdmin)
                      <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-primary text-white p-0.5 border border-white" title="Quản trị viên">
                        <i class="fa-solid fa-shield-halved" style="font-size: 8px;"></i>
                      </span>
                    @endif
                  </div>
                  <div>
                    <div class="d-flex align-items-center gap-1.5 flex-wrap">
                      @if($user->role === 'customer')
                        <a href="{{ route('admin.customers.show', $user->id) }}" class="fw-bold text-body-emphasis text-decoration-none fs-9 hover-primary" title="Xem chi tiết hồ sơ khách hàng">
                          {{ $user->name }}
                        </a>
                      @else
                        <strong class="text-body-emphasis fs-9">{{ $user->name }}</strong>
                      @endif

                      @if($isCurrentUser)
                        <span class="badge badge-phoenix badge-phoenix-warning fs-11 py-0.5 px-1.5">
                          <i class="fa-solid fa-user-check me-1"></i> Bạn
                        </span>
                      @endif
                    </div>
                    <div class="d-flex align-items-center gap-1 fs-11 text-body-tertiary">
                      <span class="font-monospace">#USER-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                      <span>&bull;</span>
                      <span>Tham gia: {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                  </div>
                </div>
              </td>

              <!-- 2. Email & Số điện thoại -->
              <td class="py-3">
                <div class="d-flex align-items-center gap-1 text-body-emphasis fw-semibold fs-10">
                  <i class="fa-regular fa-envelope text-muted fs-11"></i>
                  <span class="text-truncate" style="max-width: 160px;" title="{{ $user->email }}">{{ $user->email }}</span>
                  @if($user->email_verified_at)
                    <i class="fa-solid fa-circle-check text-success fs-11" title="Email đã xác thực"></i>
                  @endif
                </div>
                <div class="d-flex align-items-center gap-1 text-body-tertiary fs-11 mt-0.5">
                  <i class="fa-solid fa-phone text-muted fs-11"></i>
                  <span>{{ $user->phone ?: 'Chưa có SĐT' }}</span>
                  @if($user->phone_verified_at)
                    <i class="fa-solid fa-circle-check text-success fs-11" title="SĐT đã xác thực"></i>
                  @endif
                </div>
              </td>

              <!-- 3. Đơn hàng -->
              <td class="py-3 text-center">
                @if($user->orders_count > 0)
                  <a href="{{ route('admin.customers.show', $user->id) }}#cust-orders" class="badge badge-phoenix badge-phoenix-info fs-10 text-decoration-none" title="Xem các đơn hàng của người này">
                    <i class="fa-solid fa-bag-shopping me-1"></i> {{ $user->orders_count }} đơn
                  </a>
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary fs-10">0 đơn</span>
                @endif
              </td>

              <!-- 4. Vai trò (Role) -->
              <td class="py-3">
                @if($isAdmin)
                  <span class="badge badge-phoenix badge-phoenix-primary fs-10">
                    <i class="fa-solid fa-shield-halved me-1"></i> Quản trị viên (Admin)
                  </span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary fs-10">
                    <i class="fa-solid fa-user me-1"></i> Khách hàng (Customer)
                  </span>
                @endif
              </td>

              <!-- 5. Trạng thái & Bảo mật -->
              <td class="py-3">
                @if($isBanned)
                  <span class="badge badge-phoenix badge-phoenix-danger fs-10 mb-0.5 d-inline-block">
                    <i class="fa-solid fa-lock me-1"></i> Đang bị khóa
                  </span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-success fs-10 mb-0.5 d-inline-block">
                    <i class="fa-solid fa-circle-check me-1"></i> Đang hoạt động
                  </span>
                @endif

                @if($isTempLocked)
                  <div class="badge badge-phoenix badge-phoenix-warning fs-11 d-block mt-1" title="Bị khóa tạm do đăng nhập sai mật khẩu nhiều lần">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Tạm khóa đến {{ $user->locked_until->format('H:i') }}
                  </div>
                @endif
              </td>

              <!-- 6. Thao tác -->
              <td class="text-end pe-3 py-3">
                <div class="d-inline-flex align-items-center gap-1">
                  @if($isCurrentUser)
                    <button class="btn btn-sm btn-phoenix-secondary py-1 px-2 fs-10" data-bs-toggle="modal" data-bs-target="#editSelfModal">
                      <i class="fa-solid fa-user-pen me-1"></i> Tài khoản của tôi
                    </button>
                  @else
                    <!-- Nút Phân quyền nhanh mở modal -->
                    <button class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}" title="Chỉnh sửa phân quyền & thông tin">
                      <i class="fa-solid fa-user-gear me-1"></i> Phân Quyền
                    </button>

                    <!-- Dropdown thao tác mở rộng -->
                    <div class="dropdown font-sans-serif">
                      <button class="btn btn-sm btn-phoenix-secondary dropdown-toggle dropdown-caret-none py-1 px-2 fs-10" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end py-1 shadow-sm fs-10">
                        <!-- Đặt lại mật khẩu -->
                        <button type="button" class="dropdown-item py-1.5" data-bs-toggle="modal" data-bs-target="#resetPassModal{{ $user->id }}">
                          <i class="fa-solid fa-key me-2 text-warning"></i> Đặt lại mật khẩu
                        </button>

                        <!-- Khóa / Mở khóa nhanh -->
                        <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn {{ $isBanned ? 'MỞ KHÓA' : 'KHÓA' }} tài khoản {{ addslashes($user->name) }}?');">
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

                        <!-- Liên kết xem CRM Khách Hàng -->
                        @if($user->role === 'customer')
                          <a class="dropdown-item py-1.5" href="{{ route('admin.customers.show', $user->id) }}">
                            <i class="fa-regular fa-id-card me-2 text-primary"></i> Xem hồ sơ khách hàng
                          </a>
                        @endif

                        <div class="dropdown-divider my-1"></div>

                        <!-- Xóa tài khoản -->
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Bạn có chắc chắn muốn XÓA VĨNH VIỄN tài khoản người dùng {{ addslashes($user->name) }}? Hành động này không thể hoàn tác!');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="dropdown-item py-1.5 text-danger">
                            <i class="fa-regular fa-trash-can me-2"></i> Xóa tài khoản
                          </button>
                        </form>
                      </div>
                    </div>
                  @endif
                </div>

                <!-- MODAL PHÂN QUYỀN & CHỈNH SỬA TÀI KHOẢN -->
                @if(!$isCurrentUser)
                  <div class="modal fade text-start" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                          <h6 class="modal-title fw-bold">
                            <i class="fa-solid fa-user-shield me-2 text-primary"></i>
                            Phân Quyền: {{ $user->name }}
                          </h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $user->name }}" required>
                          </div>

                          <div class="mb-3">
                            <label class="form-label fw-bold">Email tài khoản</label>
                            <input type="text" class="form-control form-control-sm bg-body-tertiary" value="{{ $user->email }}" disabled>
                            <div class="form-text fs-11 text-body-tertiary">Email là định danh đăng nhập chính không thể thay đổi.</div>
                          </div>

                          <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control form-control-sm" value="{{ $user->phone }}" placeholder="09xxxxxxxxx">
                          </div>

                          <div class="mb-3">
                            <label class="form-label fw-bold">Vai trò tài khoản <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="role" required>
                              <option value="customer" @selected($user->role === 'customer')>Khách hàng (Customer - Chỉ mua sắm)</option>
                              <option value="admin" @selected($user->role === 'admin')>Quản trị viên (Admin - Toàn quyền quản trị hệ thống)</option>
                            </select>
                            <div class="form-text fs-11 text-body-tertiary">Quản trị viên có toàn quyền truy cập khu vực Admin và chỉnh sửa dữ liệu shop.</div>
                          </div>

                          <div class="mb-3">
                            <label class="form-label fw-bold">Trạng thái hoạt động <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" name="status" required>
                              <option value="active" @selected($user->status !== 'banned')>Đang hoạt động (Active)</option>
                              <option value="banned" @selected($user->status === 'banned')>Khóa tài khoản (Banned - Chặn đăng nhập)</option>
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

                  <!-- MODAL ĐẶT LẠI MẬT KHẨU CHO NGƯỜI DÙNG -->
                  <div class="modal fade text-start" id="resetPassModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <form class="modal-content" method="POST" action="{{ route('admin.users.resetPassword', $user) }}">
                        @csrf
                        <div class="modal-header">
                          <h6 class="modal-title fw-bold">
                            <i class="fa-solid fa-key me-2 text-warning"></i>
                            Đặt Lại Mật Khẩu: {{ $user->name }}
                          </h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                          <p class="fs-10 text-body-tertiary mb-3">
                            Thiết lập mật khẩu mới cho tài khoản <strong class="text-body-emphasis">{{ $user->email }}</strong>. Sau khi cập nhật, người dùng có thể đăng nhập bằng mật khẩu mới này.
                          </p>

                          <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" name="new_password" class="form-control form-control-sm" placeholder="Tối thiểu 6 ký tự" required minlength="6">
                          </div>

                          <div class="mb-3">
                            <label class="form-label fw-bold">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" name="new_password_confirmation" class="form-control form-control-sm" placeholder="Nhập lại mật khẩu mới" required minlength="6">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                          <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-check me-1"></i> Xác Nhận Đặt Lại Mật Khẩu
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                @endif

              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-body-tertiary py-5">
                <i class="fa-solid fa-users-slash fs-3 d-block mb-3 text-body-quaternary"></i>
                <h6 class="fw-bold text-body-emphasis">Không tìm thấy tài khoản người dùng phù hợp!</h6>
                <p class="fs-10 text-body-tertiary mb-3">Vui lòng kiểm tra lại từ khóa tìm kiếm hoặc các điều kiện lọc vai trò, trạng thái.</p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-phoenix-primary btn-sm">
                  <i class="fa-solid fa-rotate-left me-1"></i> Xóa tất cả bộ lọc
                </a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($users->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center py-3 bg-body-emphasis border-top border-translucent flex-wrap gap-2">
      <div class="text-body-tertiary fs-10">
        Hiển thị từ <strong>{{ $users->firstItem() }}</strong> đến <strong>{{ $users->lastItem() }}</strong> trên tổng số <strong>{{ $users->total() }}</strong> tài khoản
      </div>
      <div>
        {{ $users->links('pagination::bootstrap-5') }}
      </div>
    </div>
  @endif
</div>

<!-- MODAL THÊM MỚI QUẢN TRỊ VIÊN / TÀI KHOẢN -->
<div class="modal fade text-start" id="createUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('admin.users.store') }}">
      @csrf
      <div class="modal-header">
        <h6 class="modal-title fw-bold">
          <i class="fa-solid fa-user-plus me-2 text-primary"></i>
          Thêm Tài Khoản / Quản Trị Viên Mới
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control form-control-sm" placeholder="VD: Nguyễn Văn A" value="{{ old('name') }}" required>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-12 col-md-6">
            <label class="form-label fw-bold">Địa chỉ Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control form-control-sm" placeholder="admin@beestyle.vn" value="{{ old('email') }}" required>
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label fw-bold">Số điện thoại</label>
            <input type="text" name="phone" class="form-control form-control-sm" placeholder="09xxxxxxxxx" value="{{ old('phone') }}">
          </div>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-12 col-md-6">
            <label class="form-label fw-bold">Vai trò tài khoản <span class="text-danger">*</span></label>
            <select class="form-select form-select-sm" name="role" required>
              <option value="admin" selected>Quản trị viên (Admin)</option>
              <option value="customer">Khách hàng (Customer)</option>
            </select>
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label fw-bold">Trạng thái ban đầu <span class="text-danger">*</span></label>
            <select class="form-select form-select-sm" name="status" required>
              <option value="active" selected>Hoạt động (Active)</option>
              <option value="banned">Khóa tài khoản (Banned)</option>
            </select>
          </div>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-12 col-md-6">
            <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control form-control-sm" placeholder="Tối thiểu 6 ký tự" required minlength="6">
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label fw-bold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Nhập lại mật khẩu" required minlength="6">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary btn-sm">
          <i class="fa-solid fa-plus me-1"></i> Tạo Tài Khoản
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL XEM HỒ SƠ TÀI KHOẢN CỦA CHÍNH MÌNH (SELF) -->
<div class="modal fade text-start" id="editSelfModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-bold">
          <i class="fa-solid fa-user-shield me-2 text-primary"></i>
          Tài Khoản Đang Đăng Nhập Của Bạn
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body text-center p-4">
        <img class="rounded-circle border border-primary border-2 mb-3 shadow-xs" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width: 72px; height: 72px; object-fit: cover;">
        <h5 class="fw-bold text-body-emphasis mb-1">{{ auth()->user()->name }}</h5>
        <p class="text-body-tertiary fs-10 mb-3">{{ auth()->user()->email }}</p>
        <span class="badge badge-phoenix badge-phoenix-primary fs-10 mb-3">
          <i class="fa-solid fa-shield-halved me-1"></i> Quản Trị Viên Cấp Cao
        </span>
        <div class="alert alert-subtle-info fs-10 text-start mb-0">
          <i class="fa-solid fa-info-circle me-1"></i> Để bảo đảm an toàn hệ thống, bạn không thể tự hạ quyền hoặc tự khóa tài khoản của chính mình từ bảng phân quyền.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
@endsection
