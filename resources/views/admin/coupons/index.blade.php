@extends('layouts.admin')

@section('title', 'Mã Giảm Giá & Khuyến Mãi | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Mã Giảm Giá &amp; Ưu Đãi</h3>
      <p class="text-muted small mb-0">Quản lý các chương trình voucher, mã giảm giá và chiến dịch khuyến mãi</p>
    </div>
    <button type="button" class="btn btn-bee-primary btn-sm">
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
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @foreach($coupons as $coupon)
          <tr>
            <td>
              <span class="badge bg-warning-subtle text-dark font-monospace fw-bold fs-9 px-2 py-1">
                {{ $coupon['code'] }}
              </span>
            </td>
            <td><strong class="text-dark">{{ $coupon['title'] }}</strong></td>
            <td>
              @if($coupon['type'] === 'percent')
                <span class="badge bg-danger">Giảm {{ $coupon['discount_amount'] }}%</span>
              @elseif($coupon['type'] === 'shipping')
                <span class="badge bg-info">Freeship (30.000₫)</span>
              @else
                <span class="badge bg-success">Giảm {{ number_format($coupon['discount_amount'], 0, ',', '.') }}₫</span>
              @endif
            </td>
            <td><span>{{ number_format($coupon['min_order'], 0, ',', '.') }}₫</span></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span class="small fw-semibold">{{ $coupon['used_count'] }} / {{ $coupon['total_limit'] }}</span>
                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                  <div class="progress-bar bg-warning" style="width: {{ ($coupon['used_count'] / $coupon['total_limit']) * 100 }}%"></div>
                </div>
              </div>
            </td>
            <td><small class="text-muted">{{ $coupon['expires_at'] }}</small></td>
            <td><span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> {{ $coupon['status'] }}</span></td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-warning text-dark"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="btn btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
