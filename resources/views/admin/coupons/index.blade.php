@extends('layouts.admin')

@section('title', 'Mã Giảm Giá & Khuyến Mãi | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Mã Giảm Giá &amp; Ưu Đãi</h3>
      <p class="text-muted small mb-0">Quản lý các chương trình voucher, mã giảm giá và chiến dịch khuyến mãi</p>
    </div>
    <button type="button" class="btn btn-bee-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCouponModal">
      <i class="fa-solid fa-plus me-1"></i> Tạo Voucher Mới
    </button>
  </div>
</div>

<div class="bee-table-card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Mã Voucher</th>
          <th>Tên Chương Trình</th>
          <th>Mức Giảm</th>
          <th>Đơn Tối Thiểu</th>
          <th>Lượt Đã Dùng</th>
          <th>Hạn Sử Dụng</th>
          <th>Trạng Thái</th>
        </tr>
      </thead>
      <tbody>
        @forelse($coupons as $coupon)
          <tr>
            <td>
              <span class="badge bg-warning-subtle text-dark font-monospace fw-bold fs-9 px-2 py-1">
                {{ $coupon->code }}
              </span>
            </td>
            <td><strong class="text-dark">{{ $coupon->title }}</strong></td>
            <td>
              @if($coupon->discount_type === 'percent')
                <span class="badge bg-danger">Giảm {{ $coupon->discount_value }}%</span>
              @elseif($coupon->discount_type === 'shipping')
                <span class="badge bg-info">Freeship ({{ number_format($coupon->discount_value, 0, ',', '.') }}₫)</span>
              @else
                <span class="badge bg-success">Giảm {{ number_format($coupon->discount_value, 0, ',', '.') }}₫</span>
              @endif
            </td>
            <td><span>{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</span></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span class="small fw-semibold">{{ $coupon->used_count }} / {{ $coupon->total_limit }}</span>
                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                  <div class="progress-bar bg-warning" style="width: {{ $coupon->total_limit > 0 ? min(100, ($coupon->used_count / $coupon->total_limit) * 100) : 0 }}%"></div>
                </div>
              </div>
            </td>
            <td><small class="text-muted">{{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Vô thời hạn' }}</small></td>
            <td>
              @if($coupon->is_active)
                <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Đang diễn ra</span>
              @else
                <span class="badge bg-secondary-subtle text-secondary fw-bold">Đã dừng</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">Chưa có mã giảm giá nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
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
            <input type="text" name="code" class="form-control font-monospace" placeholder="Ví dụ: SALE50K, FREESHIP..." required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tiêu đề chương trình <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" placeholder="Ví dụ: Giảm 50.000đ cho đơn từ 499.000đ..." required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Loại giảm giá</label>
              <select name="discount_type" class="form-select">
                <option value="fixed">Tiền mặt cố định (VNĐ)</option>
                <option value="percent">Phần trăm (%)</option>
                <option value="shipping">Miễn phí ship</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Giá trị giảm <span class="text-danger">*</span></label>
              <input type="number" name="discount_value" class="form-control fw-bold" placeholder="50000 hoặc 15" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Đơn hàng tối thiểu</label>
              <input type="number" name="min_order_value" class="form-control" value="0" placeholder="499000">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tổng lượt phát hành</label>
              <input type="number" name="total_limit" class="form-control" value="1000" placeholder="1000">
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
