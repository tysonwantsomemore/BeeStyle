@extends('layouts.client')

@section('title', 'Tài Khoản Của Tôi | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Hồ sơ tài khoản</li>
    </ol>
  </nav>

  <div class="row g-4">
    <!-- USER PROFILE SIDEBAR -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm p-4 text-center mb-4" style="border-radius: 16px;">
        <div class="avatar avatar-5xl mx-auto mb-3">
          <img class="rounded-circle border border-3 border-warning" src="{{ asset($user->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $user->name ?? 'Khách Hàng' }}" style="width: 90px; height: 90px; object-fit: cover;">
        </div>
        <h5 class="fw-bold text-dark mb-1">{{ $user->name ?? 'Nguyễn Văn Hùng' }}</h5>
        <p class="text-muted small mb-2">{{ $user->email ?? 'hung.nguyen@gmail.com' }}</p>
        <div class="d-flex justify-content-center gap-2 mb-3">
          <span class="badge bg-warning-subtle text-dark fw-bold px-3 py-2 rounded-pill"><i class="fa-solid fa-crown me-1 text-warning"></i> {{ $user->rank ?? 'Thành viên Bạc' }}</span>
          <span class="badge bg-light text-dark fw-bold px-3 py-2 rounded-pill"><i class="fa-solid fa-coins me-1 text-warning"></i> {{ $user->points ?? 1250 }} Điểm</span>
        </div>

        <div class="list-group list-group-flush text-start small border-top pt-3">
          <a href="#" class="list-group-item list-group-item-action active bg-warning border-warning text-dark fw-bold rounded-2 mb-1">
            <i class="fa-solid fa-box-archive me-2"></i> Đơn Hàng Của Tôi
          </a>
          <a href="#" class="list-group-item list-group-item-action text-dark rounded-2 mb-1">
            <i class="fa-solid fa-user-pen me-2 text-muted"></i> Thông Tin Cá Nhân
          </a>
          <a href="#" class="list-group-item list-group-item-action text-dark rounded-2 mb-1">
            <i class="fa-solid fa-map-location-dot me-2 text-muted"></i> Sổ Địa Chỉ: {{ $user->address ?? 'Quận 1, TP. Hồ Chí Minh' }}
          </a>
          <a href="{{ route('client.products.index') }}" class="list-group-item list-group-item-action text-dark rounded-2 mb-1">
            <i class="fa-solid fa-bag-shopping me-2 text-muted"></i> Tiếp Tục Mua Sắm
          </a>
          @if($user->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action text-warning fw-bold rounded-2 mb-1">
              <i class="fa-solid fa-gauge-high me-2"></i> Quản Trị Hệ Thống (Admin)
            </a>
          @endif
          
          <!-- LOGOUT BUTTON FORM -->
          <form action="{{ route('auth.logout') }}" method="POST" class="mt-2 pt-2 border-top">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 fw-semibold rounded-2">
              <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng Xuất Tài Khoản
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- ORDERS HISTORY -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
        <h5 class="fw-bold text-dark mb-3">
          <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i> Lịch Sử Đơn Hàng
        </h5>

        <div class="d-flex flex-column gap-3">
          @forelse($orders as $order)
            <div class="border rounded-3 p-3 bg-light-subtle">
              <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-2 border-bottom mb-3">
                <div>
                  <span class="small text-muted">Mã đơn:</span>
                  <strong class="text-dark font-monospace">{{ $order->order_code }}</strong>
                  <span class="text-muted small ms-2">({{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }})</span>
                </div>
                <div>
                  @if($order->status_step == 6)
                    <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-check me-1"></i> {{ $order->status_label }}</span>
                  @else
                    <span class="badge bg-warning-subtle text-dark fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> {{ $order->status_label }}</span>
                  @endif
                </div>
              </div>

              <!-- Item preview -->
              <div class="d-flex flex-column gap-2 mb-3">
                @foreach($order->items as $item)
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 40px; height: 40px; object-fit: contain;">
                      <div>
                        <div class="small fw-semibold text-dark">{{ $item->product_name }}</div>
                        <small class="text-muted">{{ $item->color }} / {{ $item->size }} • SL: {{ $item->quantity }}</small>
                      </div>
                    </div>
                    <span class="small fw-bold">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                  </div>
                @endforeach
              </div>

              <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                <div>
                  <span class="small text-muted">Tổng thanh toán: </span>
                  <strong class="text-danger fs-6">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                </div>
                <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold">
                  Chi Tiết &amp; Vận Chuyển <i class="fa-solid fa-chevron-right ms-1"></i>
                </a>
              </div>
            </div>
          @empty
            <div class="text-center py-4">
              <p class="text-muted small">Bạn chưa có đơn hàng nào tại BeeStyle.</p>
              <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary btn-sm">Khám Phá Sản Phẩm Ngay</a>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
