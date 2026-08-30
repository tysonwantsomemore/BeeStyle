@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 --}}
 {{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 
@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 
@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 

@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 --}}
 {{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 --}}
 {{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 
@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 
@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 

@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 --}}
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 --}}
 {{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 
@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 
@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 

@extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Quản lý người dùng & phân quyền | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-1">Quản lý người dùng &amp; phân quyền</h3>
    <p class="text-muted mb-0">Theo dõi tài khoản, phân quyền quản trị và khóa/mở khóa tài khoản khi cần.</p>
  </div>
  <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fa-solid fa-shield-halved me-1"></i> Khu vực quản trị</span>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">TỔNG TÀI KHOẢN</small><h3 class="mb-0 mt-2 fw-bold">{{ number_format($stats['total']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">QUẢN TRỊ VIÊN</small><h3 class="mb-0 mt-2 fw-bold text-primary">{{ number_format($stats['admins']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">KHÁCH HÀNG</small><h3 class="mb-0 mt-2 fw-bold text-success">{{ number_format($stats['customers']) }}</h3></div></div></div>
  <div class="col-md-3 col-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted">ĐANG KHÓA</small><h3 class="mb-0 mt-2 fw-bold text-danger">{{ number_format($stats['locked']) }}</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body border-bottom">
    <form class="row g-2" method="GET">
      <div class="col-lg-5"><input class="form-control" name="q" value="{{ $search }}" placeholder="Tìm theo tên, email hoặc số điện thoại"></div>
      <div class="col-lg-2"><select class="form-select" name="role"><option value="">Tất cả quyền</option><option value="admin" @selected($role === 'admin')>Quản trị viên</option><option value="customer" @selected($role === 'customer')>Khách hàng</option></select></div>
      <div class="col-lg-2"><select class="form-select" name="status"><option value="">Tất cả trạng thái</option><option value="active" @selected($status === 'active')>Hoạt động</option><option value="banned" @selected($status === 'banned')>Đang khóa</option></select></div>
      <div class="col-lg-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-filter me-1"></i>Lọc</button> <a class="btn btn-light border" href="{{ route('admin.users.index') }}">Đặt lại</a></div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light"><tr><th>Người dùng</th><th>Liên hệ</th><th>Đơn hàng</th><th>Quyền hiện tại</th><th>Trạng thái</th><th class="text-end">Phân quyền</th></tr></thead>
      <tbody>
      @forelse($users as $user)
        <tr>
          <td><div class="fw-bold">{{ $user->name }}</div><small class="text-muted">Tham gia {{ $user->created_at?->format('d/m/Y') }}</small></td>
          <td><div>{{ $user->email }}</div><small class="text-muted">{{ $user->phone ?: 'Chưa cập nhật SĐT' }}</small></td>
          <td><span class="badge bg-light text-dark border">{{ $user->orders_count }} đơn</span></td>
          <td>@if($user->role === 'admin')<span class="badge bg-primary-subtle text-primary">Quản trị viên</span>@else<span class="badge bg-secondary-subtle text-secondary">Khách hàng</span>@endif</td>
          <td>@if($user->status === 'banned')<span class="badge bg-danger-subtle text-danger">Đang khóa</span>@else<span class="badge bg-success-subtle text-success">Hoạt động</span>@endif</td>
          <td class="text-end">
            @if($user->id === auth()->id())<span class="small text-muted">Tài khoản hiện tại</span>@else<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}"><i class="fa-solid fa-user-gear me-1"></i>Cập nhật</button>@endif
          </td>
        </tr>
        @if($user->id !== auth()->id())
        <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $user) }}">@csrf @method('PUT')
          <div class="modal-header"><h5 class="modal-title">Phân quyền: {{ $user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><div class="mb-3"><label class="form-label fw-semibold">Vai trò tài khoản</label><select class="form-select" name="role"><option value="customer" @selected($user->role === 'customer')>Khách hàng</option><option value="admin" @selected($user->role === 'admin')>Quản trị viên</option></select><div class="form-text">Quản trị viên có quyền truy cập toàn bộ khu vực Admin.</div></div>
          <div><label class="form-label fw-semibold">Trạng thái</label><select class="form-select" name="status"><option value="active" @selected($user->status !== 'banned')>Hoạt động</option><option value="banned" @selected($user->status === 'banned')>Khóa tài khoản</option></select></div></div>
          <div class="modal-footer"><button type="button" class="btn btn-light border" data-bs-dismiss="modal">Hủy</button><button class="btn btn-bee-primary">Lưu thay đổi</button></div>
        </form></div></div>
        @endif
      @empty
        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy tài khoản phù hợp.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
</div>
@endsection
 --}}
