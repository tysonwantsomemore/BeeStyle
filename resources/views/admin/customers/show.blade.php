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
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">ID: #{{ $customer->id }}</span>
      </div>
      <p class="text-muted small mb-0 ps-4 ms-2">Xem thông tin tài khoản đăng nhập, tổng chi tiêu cá nhân và thống kê chi tiêu của tất cả khách hàng</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-dark btn-sm px-3">
        <i class="fa-solid fa-users me-1.5"></i> Danh Sách Khách Hàng
      </a>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-bee-primary btn-sm px-3">
        <i class="fa-solid fa-receipt me-1.5"></i> Quản Lý Đơn Hàng
      </a>
    </div>
  </div>
</div>

<!-- 4 THẺ THỐNG KÊ CHI TIÊU TOÀN DIỆN (TOÀN SHOP & KHÁCH HÀNG HIỆN TẠI) -->
<div class="row g-3 mb-4">
  <!-- 1. Tổng Chi Tiêu Tất Cả Khách Hàng Toàn Shop -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm p-3.5 h-100" style="border-radius: 14px; background: #ffffff; border-left: 4px solid var(--bee-primary, #f59e0b) !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tổng Chi Tiêu Tất Cả Khách Hàng</span>
        <div class="rounded-circle p-2 bg-warning-subtle text-warning" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-sack-dollar fs-6"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-1" style="font-size: 1.55rem;">{{ number_format($totalAllCustomersSpent, 0, ',', '.') }}₫</h3>
      <div class="text-muted small fs-11">
        <i class="fa-solid fa-circle-check text-success me-1"></i> Hoàn tất: <strong>{{ number_format($totalCompletedSpent, 0, ',', '.') }}₫</strong>
      </div>
    </div>
  </div>

  <!-- 2. Tổng Tài Khoản Đã Mua Hàng -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm p-3.5 h-100" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #06b6d4 !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Tài Khoản Đã Mua Hàng</span>
        <div class="rounded-circle p-2 bg-info-subtle text-info" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-users-line fs-6"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-1" style="font-size: 1.55rem;">{{ $totalPurchasingAccounts }} <span class="fs-6 text-muted fw-normal">/ {{ $totalAllRegisteredCustomers }} tài khoản</span></h3>
      <div class="text-muted small fs-11">
        <i class="fa-solid fa-arrow-trend-up text-info me-1"></i> Chi tiêu TB: <strong>{{ number_format($averageSpendPerAccount, 0, ',', '.') }}₫</strong> / khách
      </div>
    </div>
  </div>

  <!-- 3. Chi Tiêu Của Khách Hàng Hiện Tại -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm p-3.5 h-100" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #10b981 !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small fw-bold text-uppercase text-truncate" style="letter-spacing: 0.05em; font-size: 0.72rem;">Chi Tiêu Của {{ $customer->name }}</span>
        <div class="rounded-circle p-2 bg-success-subtle text-success" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-wallet fs-6"></i>
        </div>
      </div>
      <h3 class="fw-bold text-danger mb-1" style="font-size: 1.55rem;">{{ number_format($customerTotalSpent, 0, ',', '.') }}₫</h3>
      <div class="text-muted small fs-11">
        <i class="fa-solid fa-pie-chart text-success me-1"></i> Chiếm <strong>{{ $customerContributionPercent }}%</strong> tổng chi tiêu toàn shop
      </div>
    </div>
  </div>

  <!-- 4. Vị Trí Xếp Hạng Chi Tiêu Của Khách -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm p-3.5 h-100" style="border-radius: 14px; background: #ffffff; border-left: 4px solid #8b5cf6 !important;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em; font-size: 0.72rem;">Xếp Hạng & TB Đơn Hàng</span>
        <div class="rounded-circle p-2 bg-primary-subtle text-primary" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
          <i class="fa-solid fa-trophy fs-6"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-1" style="font-size: 1.55rem;">Top #{{ $customerRankPosition }} <span class="fs-6 text-muted fw-normal">/ {{ $totalPurchasingAccounts }}</span></h3>
      <div class="text-muted small fs-11">
        <i class="fa-solid fa-calculator text-primary me-1"></i> TB: <strong>{{ number_format($customerAverageOrderValue, 0, ',', '.') }}₫</strong> / đơn ({{ $customerOrdersCount }} đơn)
      </div>
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
          <i class="fa-solid fa-circle-check me-1 text-success"></i> Khách Hàng Thân Thiết
        </span>
      </div>

      <!-- Information List -->
      <div class="text-start border-top pt-3 small">
        <div class="d-flex justify-content-between py-1.5 border-bottom">
          <span class="text-muted">Mã khách hàng:</span>
          <strong class="text-dark font-monospace">#CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</strong>
        </div>
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
          <strong class="text-danger fs-6">{{ number_format($customerTotalSpent, 0, ',', '.') }}₫</strong>
        </div>
        <div class="d-flex justify-content-between py-1.5 border-bottom">
          <span class="text-muted">Đơn hàng hoàn tất:</span>
          <strong class="text-success">{{ $customerCompletedOrdersCount }} / {{ $customerOrdersCount }} đơn</strong>
        </div>
        <div class="d-flex justify-content-between py-1.5 border-bottom">
          <span class="text-muted">Đóng góp doanh thu:</span>
          <strong class="text-primary">{{ $customerContributionPercent }}% toàn shop</strong>
        </div>
        <div class="d-flex justify-content-between py-1.5">
          <span class="text-muted">Địa chỉ nhận hàng:</span>
          <strong class="text-dark text-end" style="max-width: 200px;">{{ $customer->address ? ($customer->address . ', ' . $customer->district . ', ' . $customer->city) : 'Chưa cập nhật' }}</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT COLUMN: ORDERS & ALL CUSTOMERS TABS -->
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff;">
      <ul class="nav nav-tabs border-bottom mb-4" id="custTabs" role="tablist">
        <!-- Tab 1: Đơn Hàng Của Khách Này -->
        <li class="nav-item" role="presentation">
          <button class="nav-link active fw-bold text-uppercase py-2.5 px-3" id="cust-orders-tab" data-bs-toggle="tab" data-bs-target="#cust-orders" type="button" role="tab">
            <i class="fa-solid fa-box-archive me-1 text-danger"></i> Đơn Hàng Của Khách ({{ $customer->orders->count() }})
          </button>
        </li>
        <!-- Tab 2: Tổng Hợp Chi Tiêu TẤT CẢ Khách Hàng -->
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold text-uppercase py-2.5 px-3" id="cust-all-spending-tab" data-bs-toggle="tab" data-bs-target="#cust-all-spending" type="button" role="tab">
            <i class="fa-solid fa-chart-pie me-1 text-warning"></i> Chi Tiêu Tất Cả Khách Hàng ({{ $allPurchasingCustomers->count() }})
          </button>
        </li>
        <!-- Tab 3: Đánh Giá Đã Viết -->
        <li class="nav-item" role="presentation">
          <button class="nav-link fw-bold text-uppercase py-2.5 px-3" id="cust-reviews-tab" data-bs-toggle="tab" data-bs-target="#cust-reviews" type="button" role="tab">
            <i class="fa-solid fa-star me-1 text-warning"></i> Đánh Giá Đã Viết ({{ $customer->reviews->count() }})
          </button>
        </li>
      </ul>

      <div class="tab-content" id="custTabsContent">
        <!-- Tab 1: Orders của khách hiện tại -->
        <div class="tab-pane fade show active" id="cust-orders" role="tabpanel">
          <div class="table-responsive">
            <table class="table align-middle small mb-0">
              <thead class="table-light">
                <tr>
                  <th>Mã Đơn</th>
                  <th>Sản Phẩm Đã Mua</th>
                  <th>Ngày Đặt</th>
                  <th>Tổng Tiền</th>
                  <th>Trạng Thái</th>
                  <th>Thao Tác</th>
                </tr>
              </thead>
              <tbody>
                @forelse($customer->orders as $order)
                  <tr>
                    <td><strong class="font-monospace text-primary">#{{ $order->order_code }}</strong></td>
                    <td>
                      <div class="d-flex align-items-center gap-2 flex-wrap" style="max-width: 280px;">
                        @foreach($order->items->take(2) as $it)
                          <div class="d-flex align-items-center gap-1.5 p-1 bg-light rounded border">
                            <img src="{{ $it->image ? asset($it->image) : asset('/assets/img/products/tshirt_01.jpg') }}" alt="{{ $it->product_name }}" style="width: 32px; height: 32px; object-fit: cover;" class="rounded bg-white">
                            <span class="small text-dark text-truncate" style="max-width: 100px; font-size: 0.75rem;">{{ $it->product_name }}</span>
                          </div>
                        @endforeach
                        @if($order->items->count() > 2)
                          <span class="badge bg-secondary-subtle text-secondary small">+{{ $order->items->count() - 2 }}</span>
                        @endif
                      </div>
                    </td>
                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</td>
                    <td><strong class="text-danger fs-6">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
                    <td>
                      @if($order->shipping_status === 'completed')
                        <span class="badge bg-success text-white">Hoàn tất</span>
                      @elseif($order->shipping_status === 'delivered')
                        <span class="badge bg-success-subtle text-success border border-success">Đã giao hàng</span>
                      @elseif($order->shipping_status === 'shipping')
                        <span class="badge bg-warning text-dark">Đang giao</span>
                      @elseif($order->shipping_status === 'processing')
                        <span class="badge bg-info text-white">Đang đóng gói</span>
                      @elseif($order->shipping_status === 'cancelled')
                        <span class="badge bg-danger text-white">Đã hủy</span>
                      @else
                        <span class="badge bg-secondary text-white">{{ $order->status_label ?? 'Đã hoàn tất' }}</span>
                      @endif
                    </td>
                    <td>
                      <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold">
                        <i class="fa-regular fa-eye me-1"></i> Chi Tiết
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

        <!-- Tab 2: Tổng hợp chi tiêu của TẤT CẢ các tài khoản khách hàng -->
        <div class="tab-pane fade" id="cust-all-spending" role="tabpanel">
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
              <h6 class="fw-bold text-dark mb-0">Bảng Xếp Hạng Chi Tiêu Toàn Bộ Khách Hàng</h6>
              <small class="text-muted">Tổng hợp tất cả các tài khoản đã mua hàng của shop từ trước đến nay</small>
            </div>
            <span class="badge bg-warning text-dark px-3 py-2 fw-bold rounded-pill">
              Tổng chi tiêu: {{ number_format($totalAllCustomersSpent, 0, ',', '.') }}₫
            </span>
          </div>

          <div class="table-responsive">
            <table class="table align-middle small mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width: 60px;">Hạng</th>
                  <th>Khách Hàng</th>
                  <th>Số Đơn</th>
                  <th>Tổng Chi Tiêu</th>
                  <th>Tỷ Lệ Toàn Shop</th>
                  <th>Hạng Thành Viên</th>
                  <th>Hành Động</th>
                </tr>
              </thead>
              <tbody>
                @forelse($allPurchasingCustomers as $idx => $c)
                  @php
                    $isCurrent = ($c->id === $customer->id);
                    $share = $totalAllCustomersSpent > 0 ? round(($c->total_spent / $totalAllCustomersSpent) * 100, 1) : 0;
                  @endphp
                  <tr class="{{ $isCurrent ? 'table-warning fw-bold' : '' }}">
                    <td>
                      @if($idx === 0)
                        <span class="badge bg-warning text-dark rounded-circle p-2" title="Top 1"><i class="fa-solid fa-crown"></i></span>
                      @elseif($idx === 1)
                        <span class="badge bg-secondary text-white rounded-circle p-2" title="Top 2">2</span>
                      @elseif($idx === 2)
                        <span class="badge bg-danger-subtle text-danger rounded-circle p-2" title="Top 3">3</span>
                      @else
                        <span class="text-muted ps-1">#{{ $idx + 1 }}</span>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img src="{{ $c->avatar_url ?? asset('/assets/img/team/40x40/58.webp') }}" alt="{{ $c->name }}" class="rounded-circle border bg-white" style="width: 38px; height: 38px; object-fit: cover;">
                        <div>
                          <strong class="text-dark d-block">
                            {{ $c->name }}
                            @if($isCurrent)
                              <span class="badge bg-danger text-white ms-1" style="font-size: 0.65rem;">(Đang xem)</span>
                            @endif
                          </strong>
                          <small class="text-muted">{{ $c->email }} • {{ $c->phone ?? 'Chưa có SĐT' }}</small>
                        </div>
                      </div>
                    </td>
                    <td><span class="badge bg-light text-dark border px-2 py-1">{{ $c->orders_count }} đơn</span></td>
                    <td><strong class="text-danger fs-6">{{ number_format($c->total_spent, 0, ',', '.') }}₫</strong></td>
                    <td>
                      <div class="d-flex align-items-center gap-1.5">
                        <div class="progress flex-grow-1" style="height: 6px; width: 60px;">
                          <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $share }}%"></div>
                        </div>
                        <span class="fw-bold small text-dark">{{ $share }}%</span>
                      </div>
                    </td>
                    <td>
                      <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1">
                        {{ $c->rank ?? 'Hội Viên' }}
                      </span>
                    </td>
                    <td>
                      @if($isCurrent)
                        <span class="badge bg-dark text-white py-1.5 px-2">Hồ sơ hiện tại</span>
                      @else
                        <a href="{{ route('admin.customers.show', $c->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold text-nowrap py-1 px-2" style="font-size: 0.75rem;">
                          <i class="fa-regular fa-eye me-1"></i> Xem Hồ Sơ
                        </a>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Chưa có khách hàng nào phát sinh đơn hàng.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 3: Reviews -->
        <div class="tab-pane fade" id="cust-reviews" role="tabpanel">
          <div class="d-flex flex-column gap-3">
            @forelse($customer->reviews as $rev)
              <div class="p-3 bg-light rounded-3 border">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ $rev->product ? asset($rev->product->image) : asset('/assets/img/products/tshirt_01.jpg') }}" alt="{{ $rev->product->name ?? '' }}" style="width: 44px; height: 44px; object-fit: cover;" class="rounded border bg-white shadow-xs">
                    <div>
                      <strong class="text-dark small d-block">{{ $rev->product->name ?? 'Sản phẩm thời trang nam' }}</strong>
                      <small class="text-muted">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}</small>
                    </div>
                  </div>
                  <div class="text-warning small bg-white px-2.5 py-1 rounded-pill border shadow-xs">
                    @for($i=1; $i<=5; $i++)
                      <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                    @endfor
                    <span class="fw-bold text-dark ms-1">({{ $rev->rating }}/5)</span>
                  </div>
                </div>
                <p class="small text-secondary mb-2 fst-italic leading-relaxed">
                  "{{ $rev->comment }}"
                </p>
                @if(!empty($rev->images_urls))
                  <div class="d-flex gap-2 flex-wrap mt-2 pt-2 border-top border-secondary border-opacity-10">
                    @foreach($rev->images_urls as $pImg)
                      <a href="{{ $pImg }}" target="_blank" class="d-inline-block">
                        <img src="{{ $pImg }}" alt="ảnh đánh giá" class="rounded border shadow-xs" style="width: 52px; height: 52px; object-fit: cover;">
                      </a>
                    @endforeach
                  </div>
                @endif
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

