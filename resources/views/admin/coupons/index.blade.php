@extends('layouts.admin')

@section('title', 'Mã Giảm Giá & Khuyến Mãi | BeeStyle Admin')

@section('content')
<!-- BREADCRUMB & HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-auto">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning text-uppercase fw-bold fs-10">Khuyến Mãi & Marketing</span>
    </div>
    <h2 class="mb-0 text-body-emphasis fw-bold">Mã Giảm Giá &amp; Chiến Dịch Voucher</h2>
    <p class="text-body-tertiary mb-0">Quản lý mã coupon giảm giá trực tiếp, ưu đãi theo tỷ lệ % và voucher miễn phí vận chuyển (Freeship)</p>
  </div>
  <div class="col-auto">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCouponModal">
      <span class="fa-solid fa-plus me-2"></span>Tạo Voucher Mới
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

@if(isset($errors) && $errors->any())
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

<!-- 4 THẺ THỐNG KÊ VOUCHER -->
@php
  $totalCouponsCount = \App\Models\Coupon::count();
  $activeCouponsCount = \App\Models\Coupon::where('is_active', true)->where(function($q) {
      $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
  })->count();
  $totalUsedCouponsCount = \App\Models\Coupon::sum('used_count');
  $shippingCouponsCount = \App\Models\Coupon::where('discount_type', 'shipping')->count();
@endphp
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng voucher -->
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="badge-phoenix-icon me-2 badge-phoenix-primary">
            <span class="fa-solid fa-ticket fs-9"></span>
          </div>
          <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Tổng Số Voucher</h6>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-body-emphasis fw-bolder">{{ number_format($totalCouponsCount) }}</h3>
          <span class="fs-10 text-body-tertiary">chương trình</span>
        </div>
        <div class="mt-2 text-body-tertiary fs-10">Tất cả voucher trên toàn hệ thống</div>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Đang hoạt động -->
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="badge-phoenix-icon me-2 badge-phoenix-success">
            <span class="fa-solid fa-circle-check fs-9"></span>
          </div>
          <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Đang Khả Dụng</h6>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-success fw-bolder">{{ number_format($activeCouponsCount) }}</h3>
          <span class="fs-10 text-body-tertiary">mã áp dụng</span>
        </div>
        <div class="mt-2 text-success fs-10 fw-semibold">
          <span class="fa-solid fa-bolt me-1"></span>Khách hàng đang lưu &amp; sử dụng
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Lượt đã dùng -->
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="badge-phoenix-icon me-2 badge-phoenix-warning">
            <span class="fa-solid fa-hand-holding-dollar fs-9"></span>
          </div>
          <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Lượt Đã Dùng</h6>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-warning fw-bolder">{{ number_format($totalUsedCouponsCount) }}</h3>
          <span class="fs-10 text-body-tertiary">lượt đặt đơn</span>
        </div>
        <div class="mt-2 text-body-tertiary fs-10">Tổng số đơn hàng kích hoạt giảm</div>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Voucher Freeship -->
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2">
          <div class="badge-phoenix-icon me-2 badge-phoenix-info">
            <span class="fa-solid fa-truck-fast fs-9"></span>
          </div>
          <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Ưu Đãi Freeship</h6>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-info fw-bolder">{{ number_format($shippingCouponsCount) }}</h3>
          <span class="fs-10 text-body-tertiary">mã miễn phí ship</span>
        </div>
        <div class="mt-2 text-info fs-10 fw-semibold">Hỗ trợ trợ giá vận chuyển toàn quốc</div>
      </div>
    </div>
  </div>
  @if($coupons->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $coupons->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- BẢNG DANH SÁCH MÃ GIẢM GIÁ -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <span class="fa-solid fa-ticket text-primary fs-8"></span>
      <h5 class="mb-0 text-body-emphasis">Danh Sách Mã Giảm Giá &amp; Khuyến Mãi</h5>
      <span class="badge badge-phoenix badge-phoenix-secondary ms-2">{{ $coupons->total() }} mã</span>
    </div>
    <div>
      <button type="button" class="btn btn-phoenix-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCouponModal">
        <span class="fa-solid fa-plus me-1"></span>Thêm Mới
      </button>
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle table-hover">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-4 py-3" style="min-width: 140px;">MÃ VOUCHER</th>
            <th class="py-3" style="min-width: 220px;">TÊN CHƯƠNG TRÌNH</th>
            <th class="py-3">MỨC ƯU ĐÃI</th>
            <th class="py-3">ĐƠN TỐI THIỂU</th>
            <th class="py-3" style="min-width: 160px;">TIẾN ĐỘ SỬ DỤNG</th>
            <th class="py-3">HẠN SỬ DỤNG</th>
            <th class="py-3">TRẠNG THÁI</th>
            <th class="pe-4 py-3 text-end">THAO TÁC</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($coupons as $coupon)
            @php
              $isExpired = $coupon->expires_at && $coupon->expires_at->isPast();
              $usagePercent = $coupon->total_limit > 0 ? min(100, round(($coupon->used_count / $coupon->total_limit) * 100)) : 0;
            @endphp
            <tr>
              <!-- Mã Voucher -->
              <td class="ps-4 py-3">
                <span class="badge badge-phoenix badge-phoenix-primary font-monospace fw-bold fs-9 px-2.5 py-1">
                  {{ $coupon->code }}
                </span>
              </td>

              <!-- Tên chương trình -->
              <td class="py-3">
                <div class="fw-bold text-body-emphasis mb-0.5">{{ $coupon->title }}</div>
                @if($coupon->max_discount_value)
                  <div class="fs-10 text-body-tertiary">
                    Giảm tối đa: <strong class="text-body-secondary">{{ number_format($coupon->max_discount_value, 0, ',', '.') }}₫</strong>
                  </div>
                @endif
              </td>

              <!-- Mức ưu đãi -->
              <td class="py-3">
                @if($coupon->discount_type === 'percent')
                  <span class="badge badge-phoenix badge-phoenix-danger fw-bold py-1 px-2">
                    <span class="fa-solid fa-percent me-1"></span>Giảm {{ $coupon->discount_value }}%
                  </span>
                @elseif($coupon->discount_type === 'shipping')
                  <span class="badge badge-phoenix badge-phoenix-info fw-bold py-1 px-2">
                    <span class="fa-solid fa-truck-fast me-1"></span>Freeship ({{ number_format($coupon->discount_value, 0, ',', '.') }}₫)
                  </span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-success fw-bold py-1 px-2">
                    <span class="fa-solid fa-tag me-1"></span>Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}₫
                  </span>
                @endif
              </td>

              <!-- Đơn tối thiểu -->
              <td class="py-3">
                <span class="fw-semibold text-body-emphasis">{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</span>
              </td>

              <!-- Lượt đã dùng -->
              <td class="py-3">
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height: 6px;">
                    <div class="progress-bar {{ $usagePercent >= 90 ? 'bg-danger' : ($usagePercent >= 60 ? 'bg-warning' : 'bg-primary') }}" 
                         role="progressbar" 
                         style="width: {{ $usagePercent }}%" 
                         aria-valuenow="{{ $usagePercent }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                  </div>
                  <span class="fs-10 fw-bold text-body-secondary text-nowrap">{{ $coupon->used_count }}/{{ $coupon->total_limit }}</span>
                </div>
                <div class="fs-11 text-body-tertiary mt-1">Đã dùng {{ $usagePercent }}% tổng lượt phát hành</div>
              </td>

              <!-- Hạn sử dụng -->
              <td class="py-3">
                @if($coupon->expires_at)
                  <div class="d-flex align-items-center gap-1">
                    <span class="fa-regular fa-calendar-xmark text-body-tertiary fs-10"></span>
                    <span class="text-body-emphasis fw-medium">{{ $coupon->expires_at->format('d/m/Y') }}</span>
                  </div>
                  @if($isExpired)
                    <span class="badge badge-phoenix badge-phoenix-danger fs-11 mt-1">Đã hết hạn</span>
                  @else
                    <span class="fs-11 text-body-tertiary">Còn {{ $coupon->expires_at->diffForHumans() }}</span>
                  @endif
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary fs-10">Vô thời hạn</span>
                @endif
              </td>

              <!-- Trạng thái -->
              <td class="py-3">
                @if(!$coupon->is_active)
                  <span class="badge badge-phoenix badge-phoenix-secondary">
                    <span class="fa-solid fa-pause me-1"></span>Tạm ngưng
                  </span>
                @elseif($isExpired)
                  <span class="badge badge-phoenix badge-phoenix-danger">
                    <span class="fa-solid fa-clock-rotate-left me-1"></span>Hết hiệu lực
                  </span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-success">
                    <span class="fa-solid fa-circle-check me-1"></span>Đang diễn ra
                  </span>
                @endif
              </td>

              <!-- Hành động -->
              <td class="pe-4 py-3 text-end">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <button type="button" class="btn btn-phoenix-secondary btn-sm px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#editCouponModal_{{ $coupon->id }}" title="Chỉnh sửa mã voucher">
                    <span class="fa-solid fa-pen-to-square me-1"></span>Sửa
                  </button>
                  <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã voucher {{ $coupon->code }} này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-phoenix-danger btn-sm px-2 py-1" title="Xóa voucher">
                      <span class="fa-regular fa-trash-can"></span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>

            <!-- MODAL EDIT COUPON -->
            <div class="modal fade" id="editCouponModal_{{ $coupon->id }}" tabindex="-1" aria-labelledby="editCouponModalLabel_{{ $coupon->id }}" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                  <div class="modal-header border-bottom border-translucent bg-body-emphasis py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge badge-phoenix badge-phoenix-primary p-2 rounded-circle">
                        <span class="fa-solid fa-ticket"></span>
                      </span>
                      <div>
                        <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="editCouponModalLabel_{{ $coupon->id }}">
                          Cập Nhật Voucher: {{ $coupon->code }}
                        </h5>
                        <small class="text-body-tertiary">Chỉnh sửa điều kiện và chính sách giảm giá</small>
                      </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                  </div>
                  <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body bg-body p-4 text-start">
                      <div class="mb-3">
                        <label class="form-label fs-9 fw-bold text-body-emphasis">Mã Code Voucher <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control font-monospace text-uppercase fw-bold" value="{{ old('code', $coupon->code) }}" placeholder="Ví dụ: BEESTYLE50K" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label fs-9 fw-bold text-body-emphasis">Tiêu Đề Chương Trình <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $coupon->title) }}" placeholder="Ví dụ: Giảm 50.000đ cho đơn từ 499.000đ..." required>
                      </div>
                      <div class="row g-3 mb-3">
                        <div class="col-md-6">
                          <label class="form-label fs-9 fw-bold text-body-emphasis">Loại Giảm Giá</label>
                          <select name="discount_type" class="form-select">
                            <option value="fixed" {{ $coupon->discount_type === 'fixed' ? 'selected' : '' }}>Tiền mặt cố định (VNĐ)</option>
                            <option value="percent" {{ $coupon->discount_type === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                            <option value="shipping" {{ $coupon->discount_type === 'shipping' ? 'selected' : '' }}>Miễn phí vận chuyển (Freeship)</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label fs-9 fw-bold text-body-emphasis">Giá Trị Giảm <span class="text-danger">*</span></label>
                          <input type="number" name="discount_value" class="form-control fw-bold" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                        </div>
                      </div>
                      <div class="row g-3 mb-3">
                        <div class="col-md-6">
                          <label class="form-label fs-9 fw-bold text-body-emphasis">Đơn Hàng Tối Thiểu (VNĐ)</label>
                          <input type="number" name="min_order_value" class="form-control" value="{{ old('min_order_value', $coupon->min_order_value) }}" placeholder="0">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label fs-9 fw-bold text-body-emphasis">Giảm Tối Đa (VNĐ)</label>
                          <input type="number" name="max_discount_value" class="form-control" value="{{ old('max_discount_value', $coupon->max_discount_value) }}" placeholder="Để trống nếu không giới hạn">
                        </div>
                      </div>
                      <div class="row g-3 mb-3">
                        <div class="col-md-6">
                          <label class="form-label fs-9 fw-bold text-body-emphasis">Tổng Lượt Phát Hành</label>
                          <input type="number" name="total_limit" class="form-control" value="{{ old('total_limit', $coupon->total_limit) }}" placeholder="1000">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label fs-9 fw-bold text-body-emphasis">Hạn Sử Dụng</label>
                          <input type="date" name="expires_at" class="form-control" value="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}">
                        </div>
                      </div>
                      <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between p-3 bg-body-tertiary rounded-3 border border-translucent">
                        <label class="form-check-label fs-9 fw-semibold text-body-emphasis cursor-pointer mb-0" for="couponActive_{{ $coupon->id }}">
                          Kích hoạt voucher (Khách hàng có thể nhìn thấy và áp dụng)
                        </label>
                        <input class="form-check-input ms-2" type="checkbox" name="is_active" value="1" id="couponActive_{{ $coupon->id }}" {{ $coupon->is_active ? 'checked' : '' }}>
                      </div>
                    </div>
                    <div class="modal-footer border-top border-translucent bg-body-emphasis py-3 px-4">
                      <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                      <button type="submit" class="btn btn-primary btn-sm px-3">
                        <span class="fa-solid fa-floppy-disk me-1"></span>Lưu Thay Đổi
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @empty
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="py-3">
                  <span class="fa-solid fa-ticket text-body-tertiary fs-2 mb-2 d-block"></span>
                  <h6 class="text-body-emphasis fw-bold mb-1">Chưa có mã giảm giá nào</h6>
                  <p class="text-body-tertiary fs-9 mb-3">Hãy tạo mã voucher đầu tiên để bắt đầu các chiến dịch marketing khuyến mãi.</p>
                  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCouponModal">
                    <span class="fa-solid fa-plus me-1"></span>Tạo Voucher Mới
                  </button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($coupons->hasPages())
    <div class="card-footer border-top border-translucent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="fs-9 text-body-tertiary">
        Hiển thị từ <span class="fw-semibold text-body-emphasis">{{ $coupons->firstItem() }}</span> đến <span class="fw-semibold text-body-emphasis">{{ $coupons->lastItem() }}</span> trong tổng số <span class="fw-semibold text-body-emphasis">{{ $coupons->total() }}</span> mã
      </div>
      <div>
        {{ $coupons->links() }}
      </div>
    </div>
  @endif
</div>

<!-- MODAL TẠO MÃ GIẢM GIÁ MỚI -->
<div class="modal fade" id="addCouponModal" tabindex="-1" aria-labelledby="addCouponModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis py-3 px-4">
        <div class="d-flex align-items-center gap-2">
          <span class="badge badge-phoenix badge-phoenix-primary p-2 rounded-circle">
            <span class="fa-solid fa-plus"></span>
          </span>
          <div>
            <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="addCouponModalLabel">
              Tạo Mã Voucher Khuyến Mãi Mới
            </h5>
            <small class="text-body-tertiary">Thiết lập chương trình giảm giá và điều kiện áp dụng</small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        <div class="modal-body bg-body p-4 text-start">
          <div class="mb-3">
            <label class="form-label fs-9 fw-bold text-body-emphasis">Mã Code Voucher <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control font-monospace text-uppercase fw-bold" placeholder="Ví dụ: SALE50K, FREESHIP..." required>
            <div class="form-text fs-10">Mã voucher mà khách hàng sẽ nhập vào ô giảm giá khi thanh toán.</div>
          </div>
          <div class="mb-3">
            <label class="form-label fs-9 fw-bold text-body-emphasis">Tiêu Đề Chương Trình <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="Ví dụ: Giảm 50.000đ cho đơn từ 499.000đ..." required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Loại Giảm Giá</label>
              <select name="discount_type" class="form-select">
                <option value="fixed">Tiền mặt cố định (VNĐ)</option>
                <option value="percent">Phần trăm (%)</option>
                <option value="shipping">Miễn phí vận chuyển (Freeship)</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Giá Trị Giảm <span class="text-danger">*</span></label>
              <input type="number" name="discount_value" class="form-control fw-bold" placeholder="Ví dụ: 50000 hoặc 15" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Đơn Hàng Tối Thiểu (VNĐ)</label>
              <input type="number" name="min_order_value" class="form-control" value="0" placeholder="0">
            </div>
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Giảm Tối Đa (VNĐ)</label>
              <input type="number" name="max_discount_value" class="form-control" placeholder="Để trống nếu không giới hạn">
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Tổng Lượt Phát Hành</label>
              <input type="number" name="total_limit" class="form-control" value="1000" placeholder="1000">
            </div>
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Hạn Sử Dụng</label>
              <input type="date" name="expires_at" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis py-3 px-4">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary btn-sm px-3">
            <span class="fa-solid fa-ticket me-1"></span>Tạo Voucher
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
