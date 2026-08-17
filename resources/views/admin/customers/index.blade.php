@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Danh Sách Khách Hàng</h3>
      <p class="text-muted small mb-0">Theo dõi thông tin thành viên, xếp hạng khách hàng và tổng chi tiêu</p>
    </div>
  </div>
</div>

<div class="bee-table-card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-2">
      <input type="text" class="form-control form-control-sm" placeholder="Tìm kiếm tên, email, SĐT..." style="width: 260px;">
    </div>
    <div class="text-muted small">
      Tổng số: <strong>{{ count($customers) }}</strong> khách hàng
    </div>
  </div>

  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>Khách Hàng</th>
          <th>Liên Hệ</th>
          <th>Ngày Tham Gia</th>
          <th>Số Đơn Đã Mua</th>
          <th>Tổng Chi Tiêu</th>
          <th>Hạng Thành Viên</th>
          <th>Trạng Thái</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @foreach($customers as $customer)
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="{{ asset($customer['avatar']) }}" alt="{{ $customer['name'] }}" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                <strong class="text-dark">{{ $customer['name'] }}</strong>
              </div>
            </td>
            <td>
              <div class="small text-dark">{{ $customer['phone'] }}</div>
              <small class="text-muted">{{ $customer['email'] }}</small>
            </td>
            <td><small class="text-muted">{{ $customer['created_at'] }}</small></td>
            <td><span class="badge bg-light text-dark border">{{ $customer['orders_count'] }} đơn</span></td>
            <td><strong class="text-danger">{{ number_format($customer['total_spent'], 0, ',', '.') }}₫</strong></td>
            <td>
              <span class="badge bg-warning-subtle text-dark fw-bold">
                <i class="fa-solid fa-crown me-1 text-warning"></i> {{ $customer['status'] }}
              </span>
            </td>
            <td><span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span></td>
            <td class="text-end">
              <button type="button" class="btn btn-sm btn-outline-warning text-dark"><i class="fa-solid fa-eye me-1"></i> Xem Chi Tiết</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
