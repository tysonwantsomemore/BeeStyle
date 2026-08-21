@extends('layouts.admin')

@section('title', 'Chi Tiết Khách Hàng: ' . $customer->name . ' | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h3 class="fw-bold text-dark mb-0">Hồ Sơ Khách Hàng: {{ $customer->name }}</h3>
      </div>
      <p class="text-muted small mb-0 ps-4 ms-2">Xem thông tin tài khoản đăng nhập, lịch sử mua hàng và các đánh giá</p>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- LEFT COLUMN: CUSTOMER PROFILE & VIP -->
  <div class="col-lg-4">
    <!-- Profile Card -->
    <div class="card border-0 shadow-sm p-4 text-center mb-4" style="border-radius: 16px; background: #ffffff;">
      <div class="position-relative mx-auto mb-3" style="width: 90px; height: 90px;">
        <img class="rounded-circle border border-3 border-dark object-fit-cover w-100 h-100 shadow-sm" src="{{ asset($customer->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $customer->name }}">
        <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning text-dark p-2 border">
          <i class="fa-solid fa-crown fs-11"></i>
        </span>
      </div>

      <h5 class="fw-bold text-dark mb-1">{{ $customer->name }}</h5>
      <p class="text-muted small mb-3">{{ $customer->email }}</p>

      <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap">
        <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2 rounded-pill">
          <i class="fa-solid fa-award me-1"></i> {{ $customer->rank }}
        </span>
        <span class="badge bg-light text-dark fw-bold px-3 py-2 rounded-pill border">
          <i class="fa-solid fa-coins me-1 text-warning"></i> {{ number_format($customer->points) }} Điểm
        </span>
      </div>

      <!-- Information List -->
      <div class="text-start border-top pt-3 small">
        <div class="d-flex justify-content-between py-1.5 border-bottom">
          <span class="text-muted">Số điện thoại:</span>
          <strong class="text-dark">{{ $customer->phone ?? 'Chưa cập nhật' }}</strong>
        </div>
        <div class="d-flex justify-content-between py-1.5 border-bottom">
          <span class="text-muted">Giới tính:</span>
          <strong class="text-dark">{{ $customer->gender ?? 'Nam' }}</strong>
        </div>
        <div class="d-flex justify-content-between py-1.5 border-bottom">
          <span class="text-muted">Ngày sinh:</span>
          <strong class="text-dark">{{ $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('d/m/Y') : 'Chưa cập nhật' }}</strong>
        </div>
        <div class="d-flex justify-content-between py-1.5 border-bottom">
          <span class="text-muted">Ngày đăng ký:</span>
          <strong class="text-dark">{{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : '' }}</strong>
        </div>
        <div class="d-flex justify-content-between py-1.5 border-bottom">
          <span class="text-muted">Tổng chi tiêu:</span>
          <strong class="text-danger fs-6">{{ number_format($customer->total_spent, 0, ',', '.') }}₫</strong>
        </div>
        <div class="d-flex justify-content-between py-1.5">
          <span class="text-muted">Địa chỉ:</span>
          <strong class="text-dark text-end" style="max-width: 200px;">{{ $customer->address ? ($customer->address . ', ' . $customer->district . ', ' . $customer->city) : 'Chưa cập nhật' }}</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT COLUMN: ORDERS & REVIEWS TABS -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff;">
      <ul class="nav nav-tabs border-bottom mb-4" id="custTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-bold text-uppercase py-2.5 px-3" id="cust-orders-tab" data-bs-toggle="tab" data-bs-target="#cust-orders" type="button" role="tab">
            <i class="fa-solid fa-box-archive me-1 text-danger"></i> Lịch Sử Đơn Hàng ({{ $customer->orders->count() }})
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold text-uppercase py-2.5 px-3" id="cust-reviews-tab" data-bs-toggle="tab" data-bs-target="#cust-reviews" type="button" role="tab">
            <i class="fa-solid fa-star me-1 text-warning"></i> Đánh Giá Đã Viết ({{ $customer->reviews->count() }})
          </button>
        </li>
      </ul>

      <div class="tab-content" id="custTabsContent">
        <!-- Tab 1: Orders -->
        <div class="tab-pane fade show active" id="cust-orders" role="tabpanel">
          <div class="table-responsive">
            <table class="table align-middle small mb-0">
              <thead class="table-light">
                <tr>
                  <th>Mã Đơn</th>
                  <th>Ngày Đặt</th>
                  <th>Số Lượng SP</th>
                  <th>Tổng Tiền</th>
                  <th>Trạng Thái</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @forelse($customer->orders as $order)
                  <tr>
                    <td><strong class="font-monospace text-primary">{{ $order->order_code }}</strong></td>
                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</td>
                    <td>{{ $order->items->count() }} mặt hàng</td>
                    <td><strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
                    <td>
                      @if($order->shipping_status === 'completed')
                        <span class="badge bg-success text-white">Hoàn tất</span>
                      @elseif($order->shipping_status === 'shipping')
                        <span class="badge bg-warning text-dark">Đang giao</span>
                      @elseif($order->shipping_status === 'cancelled')
                        <span class="badge bg-danger text-white">Đã hủy</span>
                      @else
                        <span class="badge bg-info text-white">{{ $order->status_label }}</span>
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold">
                        Chi Tiết
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Khách hàng chưa có đơn hàng nào.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 2: Reviews -->
        <div class="tab-pane fade" id="cust-reviews" role="tabpanel">
          <div class="d-flex flex-column gap-3">
            @forelse($customer->reviews as $rev)
              <div class="p-3 bg-light rounded-3 border">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($rev->product->image ?? '/assets/img/products/1.png') }}" alt="{{ $rev->product->name ?? '' }}" style="width: 40px; height: 40px; object-fit: cover;" class="rounded border bg-white">
                    <div>
                      <strong class="text-dark small d-block">{{ $rev->product->name ?? 'Sản phẩm' }}</strong>
                      <small class="text-muted">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}</small>
                    </div>
                  </div>
                  <div class="text-warning small">
                    @for($i=1; $i<=5; $i++)
                      <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                    @endfor
                    <span class="fw-bold text-dark ms-1">({{ $rev->rating }}/5)</span>
                  </div>
                </div>
                <p class="small text-secondary mb-0 fst-italic leading-relaxed">
                  "{{ $rev->comment }}"
                </p>
              </div>
            @empty
              <div class="text-center py-4 text-muted">
                <i class="fa-regular fa-comment-dots fs-2 mb-2 d-block"></i>
                Khách hàng này chưa viết đánh giá nào.
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
