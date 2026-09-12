@extends('layouts.admin')

@section('title', 'Quản Lý Người Dùng & Phân Quyền | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <h2 class="mb-1 text-body-emphasis fw-bold">Quản Lý Người Dùng &amp; Phân Quyền</h2>
      <p class="text-body-tertiary mb-0">Theo dõi danh sách tài khoản, phân quyền quản trị viên và khóa/mở khóa tài khoản người dùng.</p>
    </div>
    <span class="badge badge-phoenix badge-phoenix-primary px-3 py-2 fs-9">
      <i class="fa-solid fa-shield-halved me-1"></i> Khu vực Quản Trị Hệ Thống
    </span>
  </div>
</div>

<!-- 4 THẺ THỐNG KÊ NGƯỜI DÙNG CHUẨN PHOENIX -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fs-10 fw-bold">Tổng Tài Khoản</h6>
            <h3 class="text-body-highlight mb-0 fw-bolder">{{ number_format($stats['total']) }}</h3>
          </div>
          <div class="badge badge-phoenix fs-7 badge-phoenix-secondary p-2.5 rounded-3">
            <span data-feather="users"></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fs-10 fw-bold">Quản Trị Viên</h6>
            <h3 class="text-primary mb-0 fw-bolder">{{ number_format($stats['admins']) }}</h3>
          </div>
          <div class="badge badge-phoenix fs-7 badge-phoenix-primary p-2.5 rounded-3">
            <span data-feather="shield"></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fs-10 fw-bold">Khách Hàng</h6>
            <h3 class="text-success mb-0 fw-bolder">{{ number_format($stats['customers']) }}</h3>
          </div>
          <div class="badge badge-phoenix fs-7 badge-phoenix-success p-2.5 rounded-3">
            <span data-feather="user-check"></span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card h-100 border-0 shadow-sm">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fs-10 fw-bold">Đang Bị Khóa</h6>
            <h3 class="text-danger mb-0 fw-bolder">{{ number_format($stats['locked']) }}</h3>
          </div>
          <div class="badge badge-phoenix fs-7 badge-phoenix-danger p-2.5 rounded-3">
            <span data-feather="user-x"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BẢNG DANH SÁCH NGƯỜI DÙNG & BỘ LỌC CHUẨN PHOENIX -->
<div class="card border-0 shadow-sm">
  <div class="card-header border-bottom border-translucent bg-body-emphasis py-3">
    <form class="row g-2 align-items-center" method="GET" action="{{ route('admin.users.index') }}">
      <div class="col-lg-5">
        <div class="search-box">
          <div class="position-relative">
            <input class="form-control form-control-sm search-input" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại...">
            <span class="fas fa-search search-box-icon"></span>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <select class="form-select form-select-sm" name="role">
          <option value="">Tất cả vai trò</option>
          <option value="admin" @selected($role === 'admin')>Quản trị viên (Admin)</option>
          <option value="customer" @selected($role === 'customer')>Khách hàng (Customer)</option>
        </select>
      </div>
      <div class="col-sm-6 col-lg-2">
        <select class="form-select form-select-sm" name="status">
          <option value="">Tất cả trạng thái</option>
          <option value="active" @selected($status === 'active')>Đang hoạt động</option>
          <option value="banned" @selected($status === 'banned')>Đang bị khóa</option>
        </select>
      </div>
      <div class="col-lg-auto d-flex gap-2">
        <button class="btn btn-sm btn-primary px-3" type="submit">
          <i class="fa-solid fa-filter me-1"></i> Lọc
        </button>
        <a class="btn btn-sm btn-phoenix-secondary" href="{{ route('admin.users.index') }}">
          <i class="fa-solid fa-rotate-left me-1"></i> Đặt lại
        </a>
      </div>
    </form>
  </div>

  <div class="table-responsive scrollbar">
    <table class="table table-sm fs-9 mb-0 align-middle">
      <thead class="bg-body-tertiary">
        <tr>
          <th class="ps-3 py-3" style="min-width: 200px;">Người Dùng</th>
          <th style="min-width: 180px;">Liên Hệ</th>
          <th class="text-center" style="min-width: 100px;">Đơn Hàng</th>
          <th style="min-width: 140px;">Vai Trò Hiện Tại</th>
          <th style="min-width: 130px;">Trạng Thái</th>
          <th class="text-end pe-3" style="min-width: 120px;">Thao Tác</th>
        </tr>
      </thead>
      <tbody class="list">
        @forelse($users as $user)
          <tr>
            <td class="ps-3 py-3">
              <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-m">
                  <img class="rounded-circle border" src="{{ asset($user->avatar ?? 'assets/img/team/40x40/57.webp') }}" alt="{{ $user->name }}" style="width: 38px; height: 38px; object-fit: cover;">
                </div>
                <div>
                  <div class="fw-bold text-body-emphasis">{{ $user->name }}</div>
                  <div class="fs-10 text-body-tertiary">Tham gia: {{ $user->created_at?->format('d/m/Y') }}</div>
                </div>
              </div>
            </td>
            <td>
              <div class="text-body-emphasis fw-semibold">{{ $user->email }}</div>
              <div class="fs-10 text-body-tertiary">{{ $user->phone ?: 'Chưa có SĐT' }}</div>
            </td>
            <td class="text-center">
              <span class="badge badge-phoenix badge-phoenix-info">{{ $user->orders_count }} đơn</span>
            </td>
            <td>
              @if($user->role === 'admin')
                <span class="badge badge-phoenix badge-phoenix-primary"><i class="fa-solid fa-shield-halved me-1"></i> Quản trị viên</span>
              @else
                <span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-user me-1"></i> Khách hàng</span>
              @endif
            </td>
            <td>
              @if($user->status === 'banned')
                <span class="badge badge-phoenix badge-phoenix-danger"><i class="fa-solid fa-lock me-1"></i> Đang bị khóa</span>
              @else
                <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span>
              @endif
            </td>
            <td class="text-end pe-3">
              @if($user->id === auth()->id())
                <span class="badge badge-phoenix badge-phoenix-warning">Tài khoản của bạn</span>
              @else
                <button class="btn btn-sm btn-phoenix-primary px-2 py-1" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                  <i class="fa-solid fa-user-gear me-1"></i> Phân Quyền
                </button>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-body-tertiary py-5">
              <i class="fa-solid fa-users-slash fs-4 d-block mb-2 text-body-quaternary"></i>
              Không tìm thấy tài khoản người dùng phù hợp.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($users->hasPages())
    <div class="card-footer border-top border-translucent py-2">
      {{ $users->links() }}
    </div>
  @endif
</div>

<!-- CÁC MODAL PHÂN QUYỀN & KHÓA TÀI KHOẢN (ĐẶT NGOÀI BẢNG ĐỂ TRÁNH LỖI GIAO DIỆN & TRONG SUỐT) -->
@foreach($users as $user)
  @if($user->id !== auth()->id())
    <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-labelledby="userModalLabel{{ $user->id }}" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-translucent shadow-lg bg-body-emphasis rounded-3">
          <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="modal-header border-bottom border-translucent bg-body-tertiary px-4 py-3">
              <div class="d-flex align-items-center gap-2">
                <div class="badge badge-phoenix badge-phoenix-primary p-2 rounded-2">
                  <i class="fa-solid fa-user-shield fs-8"></i>
                </div>
                <div>
                  <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="userModalLabel{{ $user->id }}">Phân Quyền &amp; Trạng Thái</h5>
                  <div class="fs-10 text-body-tertiary">Chỉnh sửa vai trò quản trị &amp; khóa tài khoản</div>
                </div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body p-4 bg-body-emphasis">
              <!-- Thẻ tóm tắt thông tin người dùng -->
              <div class="card border border-translucent bg-body-tertiary mb-4">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                  <div class="avatar avatar-l">
                    <img class="rounded-circle border shadow-xs" src="{{ asset($user->avatar ?? 'assets/img/team/40x40/57.webp') }}" alt="{{ $user->name }}" style="width: 44px; height: 44px; object-fit: cover;">
                  </div>
                  <div class="flex-grow-1 min-w-0">
                    <div class="fw-bold text-body-emphasis text-truncate">{{ $user->name }}</div>
                    <div class="fs-10 text-body-tertiary text-truncate">{{ $user->email }}</div>
                    <div class="fs-10 text-body-tertiary">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</div>
                  </div>
                  <div class="text-end">
                    @if($user->role === 'admin')
                      <span class="badge badge-phoenix badge-phoenix-primary mb-1 d-block">Admin</span>
                    @else
                      <span class="badge badge-phoenix badge-phoenix-secondary mb-1 d-block">Khách hàng</span>
                    @endif

                    @if($user->status === 'banned')
                      <span class="badge badge-phoenix badge-phoenix-danger d-block">Đang khóa</span>
                    @else
                      <span class="badge badge-phoenix badge-phoenix-success d-block">Hoạt động</span>
                    @endif
                  </div>
                </div>
              </div>

              <!-- Chọn vai trò -->
              <div class="mb-3">
                <label class="form-label fw-bold text-body-emphasis fs-9 d-flex align-items-center justify-content-between">
                  <span><i class="fa-solid fa-id-badge text-primary me-1"></i> Vai trò tài khoản <span class="text-danger">*</span></span>
                </label>
                <select class="form-select bg-body" name="role" required>
                  <option value="customer" @selected($user->role === 'customer')>👤 Khách hàng (Customer - Mua hàng &amp; cá nhân)</option>
                  <option value="admin" @selected($user->role === 'admin')>🛡️ Quản trị viên (Admin - Toàn quyền quản trị hệ thống)</option>
                </select>
                <div class="form-text fs-10 text-body-tertiary mt-1">
                  <i class="fa-solid fa-circle-info text-info me-1"></i> Tài khoản Admin có thể thêm/sửa sản phẩm, quản lý đơn hàng và truy cập trang quản trị.
                </div>
              </div>

              <!-- Chọn trạng thái -->
              <div class="mb-2">
                <label class="form-label fw-bold text-body-emphasis fs-9 d-flex align-items-center justify-content-between">
                  <span><i class="fa-solid fa-toggle-on text-success me-1"></i> Trạng thái hoạt động <span class="text-danger">*</span></span>
                </label>
                <select class="form-select bg-body" name="status" required>
                  <option value="active" @selected($user->status !== 'banned') class="text-success fw-semibold">🟢 Đang hoạt động bình thường</option>
                  <option value="banned" @selected($user->status === 'banned') class="text-danger fw-semibold">🔴 Khóa tài khoản (Chặn đăng nhập)</option>
                </select>
                <div class="form-text fs-10 text-body-tertiary mt-1">
                  <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i> Nếu bị khóa, người dùng sẽ không thể đăng nhập hoặc thực hiện bất kỳ giao dịch nào.
                </div>
              </div>
            </div>

            <div class="modal-footer border-top border-translucent bg-body-tertiary px-4 py-3">
              <button type="button" class="btn btn-phoenix-secondary px-3" data-bs-dismiss="modal">
                <i class="fa-solid fa-xmark me-1"></i> Hủy
              </button>
              <button type="submit" class="btn btn-primary px-4 shadow-sm">
                <i class="fa-solid fa-check me-1"></i> Lưu Thay Đổi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endforeach
@endsection
