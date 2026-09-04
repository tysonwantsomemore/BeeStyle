@extends('layouts.admin')

@section('title', 'Mã Giảm Giá & Khuyến Mãi | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">MARKETING</span>
        <h3 class="fw-bold text-dark mb-0">Mã Giảm Giá &amp; Chiến Dịch Khuyến Mãi</h3>
      </div>
      <p class="text-muted small mb-0">Quản lý các chương trình voucher, mã giảm giá trực tiếp và ưu đãi vận chuyển Freeship</p>
    </div>
    <button type="button" class="btn btn-bee-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addCouponModal">
      <i class="fa-solid fa-plus me-1.5"></i> Tạo Voucher Mới
    </button>
  </div>
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-circle-xmark me-2"></i> <strong>Đã xảy ra lỗi nhập liệu:</strong>
    <ul class="mb-0 mt-1 small ps-3">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-ticket fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Tổng Số Voucher</div>
          <div class="fs-4 fw-bold text-dark">{{ $totalCoupons ?? count($coupons) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-circle-check fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Đang Áp Dụng</div>
          <div class="fs-4 fw-bold text-success">{{ $activeCouponsCount ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-fire fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Tổng Lượt Đã Sử Dụng</div>
          <div class="fs-4 fw-bold text-danger">{{ $totalUsedCount ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.coupons.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm mã hoặc tên voucher..." style="width: 250px;">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 170px;">
        <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang diễn ra</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Đã dừng</option>
      </select>
      <button type="submit" class="btn btn-sm btn-outline-secondary">Lọc</button>
      @if(request('q') || (request('status') && request('status') !== 'all'))
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>
    <div class="text-muted small">
      Hiển thị: <strong>{{ $coupons->count() }}</strong> / <strong>{{ $coupons->total() }}</strong> voucher
    </div>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Mã Voucher</th>
          <th>Tên Chương Trình</th>
          <th>Mức Ưu Đãi</th>
          <th>Đơn Tối Thiểu</th>
          <th>Lượt Đã Dùng</th>
          <th>Hạn Sử Dụng</th>
          <th>Trạng Thái</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($coupons as $coupon)
          <tr>
            <td>
              <span class="badge bg-warning-subtle text-dark font-monospace fw-bold fs-9 px-2.5 py-1 border border-warning">
                {{ $coupon->code }}
              </span>
            </td>
            <td><strong class="text-dark small">{{ $coupon->title }}</strong></td>
            <td>
              @if($coupon->discount_type === 'percent')
                <span class="badge bg-danger-subtle text-danger fw-bold py-1 px-2">Giảm {{ $coupon->discount_value }}%</span>
              @elseif($coupon->discount_type === 'shipping')
                <span class="badge bg-info-subtle text-info fw-bold py-1 px-2">Freeship ({{ number_format($coupon->discount_value, 0, ',', '.') }}₫)</span>
              @else
                <span class="badge bg-success-subtle text-success fw-bold py-1 px-2">Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}₫</span>
              @endif
            </td>
            <td><strong class="text-dark small">{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</strong></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span class="small fw-semibold">{{ $coupon->used_count }} / {{ $coupon->total_limit }}</span>
                <div class="progress flex-grow-1" style="height: 6px; width: 80px; border-radius: 99px;">
                  <div class="progress-bar bg-warning" style="width: {{ $coupon->total_limit > 0 ? min(100, ($coupon->used_count / $coupon->total_limit) * 100) : 0 }}%"></div>
                </div>
              </div>
            </td>
            <td><small class="text-muted text-nowrap">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Vô thời hạn' }}</small></td>
            <td>
              @if($coupon->is_active)
                <span class="badge bg-success-subtle text-success fw-bold py-1 px-2"><i class="fa-solid fa-circle-check me-1"></i> Đang diễn ra</span>
              @else
                <span class="badge bg-secondary-subtle text-muted fw-bold py-1 px-2">Đã dừng</span>
              @endif
            </td>
            <td class="text-end">
              <div class="d-flex align-items-center justify-content-end gap-1.5">
                <button type="button" class="btn btn-sm btn-outline-dark py-1 px-2.5 fw-bold" data-bs-toggle="modal" data-bs-target="#editCouponModal_{{ $coupon->id }}" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                </button>
                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã voucher này?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Xóa voucher">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>

          <!-- MODAL EDIT COUPON -->
          <div class="modal fade" id="editCouponModal_{{ $coupon->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom">
                  <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Sửa Mã: {{ $coupon->code }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Mã code <span class="text-danger">*</span></label>
                      <input type="text" name="code" class="form-control form-control-sm font-monospace" value="{{ old('code', $coupon->code) }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Tiêu đề chương trình <span class="text-danger">*</span></label>
                      <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title', $coupon->title) }}" required>
                    </div>
                    <div class="row g-3 mb-3">
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Loại giảm giá</label>
                        <select name="discount_type" class="form-select form-select-sm">
                          <option value="fixed" {{ $coupon->discount_type === 'fixed' ? 'selected' : '' }}>Tiền mặt cố định (VNĐ)</option>
                          <option value="percent" {{ $coupon->discount_type === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                          <option value="shipping" {{ $coupon->discount_type === 'shipping' ? 'selected' : '' }}>Miễn phí ship</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Giá trị giảm <span class="text-danger">*</span></label>
                        <input type="number" name="discount_value" class="form-control form-control-sm fw-bold" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                      </div>
                    </div>
                    <div class="row g-3 mb-3">
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Đơn hàng tối thiểu</label>
                        <input type="number" name="min_order_value" class="form-control form-control-sm" value="{{ old('min_order_value', $coupon->min_order_value) }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Giảm tối đa (VNĐ)</label>
                        <input type="number" name="max_discount_value" class="form-control form-control-sm" value="{{ old('max_discount_value', $coupon->max_discount_value) }}" placeholder="Bỏ trống nếu không giới hạn">
                      </div>
                    </div>
                    <div class="row g-3 mb-3">
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Tổng lượt phát hành</label>
                        <input type="number" name="total_limit" class="form-control form-control-sm" value="{{ old('total_limit', $coupon->total_limit) }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Hạn sử dụng</label>
                        <input type="date" name="expires_at" class="form-control form-control-sm" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}">
                      </div>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="couponActive_{{ $coupon->id }}" {{ $coupon->is_active ? 'checked' : '' }}>
                      <label class="form-check-label small" for="couponActive_{{ $coupon->id }}">Kích hoạt đang áp dụng (Hiển thị cho khách hàng)</label>
                    </div>

                  </div>
                  <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-bee-primary btn-sm px-3">Lưu Thay Đổi</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="fa-solid fa-ticket fs-2 text-muted mb-2 d-block"></i>
              Chưa có mã giảm giá nào được tạo.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($coupons->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $coupons->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL ADD COUPON -->
<div class="modal fade" id="addCouponModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-ticket me-2 text-warning"></i> Tạo Mã Voucher Giảm Giá Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Mã code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control form-control-sm font-monospace" placeholder="Ví dụ: SALE50K, FREESHIP..." required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tiêu đề chương trình <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-sm" placeholder="Ví dụ: Giảm 50.000đ cho đơn từ 499.000đ..." required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Loại giảm giá</label>
              <select name="discount_type" class="form-select form-select-sm">
                <option value="fixed">Tiền mặt cố định (VNĐ)</option>
                <option value="percent">Phần trăm (%)</option>
                <option value="shipping">Miễn phí ship</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Giá trị giảm <span class="text-danger">*</span></label>
              <input type="number" name="discount_value" class="form-control form-control-sm fw-bold" placeholder="50000 hoặc 15" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Đơn hàng tối thiểu</label>
              <input type="number" name="min_order_value" class="form-control form-control-sm" value="0" placeholder="499000">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tổng lượt phát hành</label>
              <input type="number" name="total_limit" class="form-control form-control-sm" value="1000" placeholder="1000">
            </div>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-bee-primary btn-sm px-3">Tạo Voucher</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
{{-- @extends('layouts.admin')

@section('title', 'Mã Giảm Giá & Khuyến Mãi | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">MARKETING</span>
        <h3 class="fw-bold text-dark mb-0">Mã Giảm Giá &amp; Chiến Dịch Khuyến Mãi</h3>
      </div>
      <p class="text-muted small mb-0">Quản lý các chương trình voucher, mã giảm giá trực tiếp và ưu đãi vận chuyển Freeship</p>
    </div>
    <button type="button" class="btn btn-bee-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addCouponModal">
      <i class="fa-solid fa-plus me-1.5"></i> Tạo Voucher Mới
    </button>
  </div>
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-circle-xmark me-2"></i> <strong>Đã xảy ra lỗi nhập liệu:</strong>
    <ul class="mb-0 mt-1 small ps-3">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-ticket fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Tổng Số Voucher</div>
          <div class="fs-4 fw-bold text-dark">{{ $totalCoupons ?? count($coupons) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-circle-check fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Đang Áp Dụng</div>
          <div class="fs-4 fw-bold text-success">{{ $activeCouponsCount ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-fire fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Tổng Lượt Đã Sử Dụng</div>
          <div class="fs-4 fw-bold text-danger">{{ $totalUsedCount ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.coupons.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm mã hoặc tên voucher..." style="width: 250px;">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 170px;">
        <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang diễn ra</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Đã dừng</option>
      </select>
      <button type="submit" class="btn btn-sm btn-outline-secondary">Lọc</button>
      @if(request('q') || (request('status') && request('status') !== 'all'))
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>
    <div class="text-muted small">
      Hiển thị: <strong>{{ $coupons->count() }}</strong> / <strong>{{ $coupons->total() }}</strong> voucher
    </div>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Mã Voucher</th>
          <th>Tên Chương Trình</th>
          <th>Mức Ưu Đãi</th>
          <th>Đơn Tối Thiểu</th>
          <th>Lượt Đã Dùng</th>
          <th>Hạn Sử Dụng</th>
          <th>Trạng Thái</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($coupons as $coupon)
          <tr>
            <td>
              <span class="badge bg-warning-subtle text-dark font-monospace fw-bold fs-9 px-2.5 py-1 border border-warning">
                {{ $coupon->code }}
              </span>
            </td>
            <td><strong class="text-dark small">{{ $coupon->title }}</strong></td>
            <td>
              @if($coupon->discount_type === 'percent')
                <span class="badge bg-danger-subtle text-danger fw-bold py-1 px-2">Giảm {{ $coupon->discount_value }}%</span>
              @elseif($coupon->discount_type === 'shipping')
                <span class="badge bg-info-subtle text-info fw-bold py-1 px-2">Freeship ({{ number_format($coupon->discount_value, 0, ',', '.') }}₫)</span>
              @else
                <span class="badge bg-success-subtle text-success fw-bold py-1 px-2">Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}₫</span>
              @endif
            </td>
            <td><strong class="text-dark small">{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</strong></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span class="small fw-semibold">{{ $coupon->used_count }} / {{ $coupon->total_limit }}</span>
                <div class="progress flex-grow-1" style="height: 6px; width: 80px; border-radius: 99px;">
                  <div class="progress-bar bg-warning" style="width: {{ $coupon->total_limit > 0 ? min(100, ($coupon->used_count / $coupon->total_limit) * 100) : 0 }}%"></div>
                </div>
              </div>
            </td>
            <td><small class="text-muted text-nowrap">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Vô thời hạn' }}</small></td>
            <td>
              @if($coupon->is_active)
                <span class="badge bg-success-subtle text-success fw-bold py-1 px-2"><i class="fa-solid fa-circle-check me-1"></i> Đang diễn ra</span>
              @else
                <span class="badge bg-secondary-subtle text-muted fw-bold py-1 px-2">Đã dừng</span>
              @endif
            </td>
            <td class="text-end">
              <div class="d-flex align-items-center justify-content-end gap-1.5">
                <button type="button" class="btn btn-sm btn-outline-dark py-1 px-2.5 fw-bold" data-bs-toggle="modal" data-bs-target="#editCouponModal_{{ $coupon->id }}" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                </button>
                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã voucher này?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Xóa voucher">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>

          <!-- MODAL EDIT COUPON -->
          <div class="modal fade" id="editCouponModal_{{ $coupon->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom">
                  <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Sửa Mã: {{ $coupon->code }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Mã code <span class="text-danger">*</span></label>
                      <input type="text" name="code" class="form-control form-control-sm font-monospace" value="{{ old('code', $coupon->code) }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Tiêu đề chương trình <span class="text-danger">*</span></label>
                      <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title', $coupon->title) }}" required>
                    </div>
                    <div class="row g-3 mb-3">
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Loại giảm giá</label>
                        <select name="discount_type" class="form-select form-select-sm">
                          <option value="fixed" {{ $coupon->discount_type === 'fixed' ? 'selected' : '' }}>Tiền mặt cố định (VNĐ)</option>
                          <option value="percent" {{ $coupon->discount_type === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                          <option value="shipping" {{ $coupon->discount_type === 'shipping' ? 'selected' : '' }}>Miễn phí ship</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Giá trị giảm <span class="text-danger">*</span></label>
                        <input type="number" name="discount_value" class="form-control form-control-sm fw-bold" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                      </div>
                    </div>
                    <div class="row g-3 mb-3">
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Đơn hàng tối thiểu</label>
                        <input type="number" name="min_order_value" class="form-control form-control-sm" value="{{ old('min_order_value', $coupon->min_order_value) }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Giảm tối đa (VNĐ)</label>
                        <input type="number" name="max_discount_value" class="form-control form-control-sm" value="{{ old('max_discount_value', $coupon->max_discount_value) }}" placeholder="Bỏ trống nếu không giới hạn">
                      </div>
                    </div>
                    <div class="row g-3 mb-3">
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Tổng lượt phát hành</label>
                        <input type="number" name="total_limit" class="form-control form-control-sm" value="{{ old('total_limit', $coupon->total_limit) }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Hạn sử dụng</label>
                        <input type="date" name="expires_at" class="form-control form-control-sm" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}">
                      </div>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="couponActive_{{ $coupon->id }}" {{ $coupon->is_active ? 'checked' : '' }}>
                      <label class="form-check-label small" for="couponActive_{{ $coupon->id }}">Kích hoạt đang áp dụng (Hiển thị cho khách hàng)</label>
                    </div>

                  </div>
                  <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-bee-primary btn-sm px-3">Lưu Thay Đổi</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="fa-solid fa-ticket fs-2 text-muted mb-2 d-block"></i>
              Chưa có mã giảm giá nào được tạo.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($coupons->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $coupons->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL ADD COUPON -->
<div class="modal fade" id="addCouponModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-ticket me-2 text-warning"></i> Tạo Mã Voucher Giảm Giá Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Mã code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control form-control-sm font-monospace" placeholder="Ví dụ: SALE50K, FREESHIP..." required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tiêu đề chương trình <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control form-control-sm" placeholder="Ví dụ: Giảm 50.000đ cho đơn từ 499.000đ..." required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Loại giảm giá</label>
              <select name="discount_type" class="form-select form-select-sm">
                <option value="fixed">Tiền mặt cố định (VNĐ)</option>
                <option value="percent">Phần trăm (%)</option>
                <option value="shipping">Miễn phí ship</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Giá trị giảm <span class="text-danger">*</span></label>
              <input type="number" name="discount_value" class="form-control form-control-sm fw-bold" placeholder="50000 hoặc 15" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Đơn hàng tối thiểu</label>
              <input type="number" name="min_order_value" class="form-control form-control-sm" value="0" placeholder="499000">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tổng lượt phát hành</label>
              <input type="number" name="total_limit" class="form-control form-control-sm" value="1000" placeholder="1000">
            </div>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-bee-primary btn-sm px-3">Tạo Voucher</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
 --}}