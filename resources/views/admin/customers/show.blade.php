@extends('layouts.admin')

@section('title', 'Chi Tiết Khách Hàng: ' . $customer->name . ' | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="mb-4">
  <div class="row gy-3 justify-content-between align-items-center">
    <div class="col-md">
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-phoenix-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="mb-0 text-body-emphasis fw-bold">Hồ Sơ Khách Hàng: {{ $customer->name }}</h2>
        <span class="badge badge-phoenix badge-phoenix-primary fs-10">ID: #{{ $customer->id }}</span>
      </div>
      <p class="text-body-tertiary mb-0 fs-9">Xem thông tin tài khoản đăng nhập, tổng chi tiêu cá nhân và thống kê chi tiêu toàn shop</p>
    </div>
    <div class="col-auto">
      <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-phoenix-secondary btn-sm">
          <i class="fa-solid fa-users me-1"></i> Danh Sách Khách Hàng
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">
          <i class="fa-solid fa-receipt me-1"></i> Quản Lý Đơn Hàng
        </a>
      </div>
    </div>
  </div>
</div>

<!-- 4 THẺ STATS -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Tổng Chi Tiêu Toàn Shop</h6>
            <h3 class="text-body-emphasis mb-0 fw-bold">{{ number_format($totalAllCustomersSpent, 0, ',', '.') }}₫</h3>
          </div>
          <div class="rounded-circle p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-sack-dollar fs-8"></i>
          </div>
        </div>
        <div class="text-body-tertiary fs-10">
          <i class="fa-solid fa-circle-check text-success me-1"></i> Hoàn tất: <strong>{{ number_format($totalCompletedSpent, 0, ',', '.') }}₫</strong>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Tài Khoản Đã Mua Hàng</h6>
            <h3 class="text-info mb-0 fw-bold">{{ $totalPurchasingAccounts }} <span class="fs-9 text-body-tertiary fw-normal">/ {{ $totalAllRegisteredCustomers }}</span></h3>
          </div>
          <div class="rounded-circle p-2 bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-users-line fs-8"></i>
          </div>
        </div>
        <div class="text-body-tertiary fs-10">
          <i class="fa-solid fa-arrow-trend-up text-info me-1"></i> TB: <strong>{{ number_format($averageSpendPerAccount, 0, ',', '.') }}₫</strong> / khách
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10 text-truncate">Chi Tiêu Của {{ $customer->name }}</h6>
            <h3 class="text-danger mb-0 fw-bold">{{ number_format($customerTotalSpent, 0, ',', '.') }}₫</h3>
          </div>
          <div class="rounded-circle p-2 bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-wallet fs-8"></i>
          </div>
        </div>
        <div class="text-body-tertiary fs-10">
          <i class="fa-solid fa-chart-pie text-success me-1"></i> Chiếm <strong>{{ $customerContributionPercent }}%</strong> tổng chi tiêu
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Xếp Hạng &amp; TB Đơn</h6>
            <h3 class="text-body-emphasis mb-0 fw-bold">Top #{{ $customerRankPosition }} <span class="fs-9 text-body-tertiary fw-normal">/ {{ $totalPurchasingAccounts }}</span></h3>
          </div>
          <div class="rounded-circle p-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-trophy fs-8"></i>
          </div>
        </div>
        <div class="text-body-tertiary fs-10">
          <i class="fa-solid fa-calculator text-primary me-1"></i> TB: <strong>{{ number_format($customerAverageOrderValue, 0, ',', '.') }}₫</strong> / đơn ({{ $customerOrdersCount }} đơn)
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- LEFT COLUMN: PROFILE CARD -->
  <div class="col-12 col-lg-4">
    <div class="card border-0 shadow-sm text-center mb-4">
      <div class="card-body p-4">
        <div class="position-relative mx-auto mb-3" style="width: 80px; height: 80px;">
          <img class="rounded-circle border border-2 border-translucent object-fit-cover w-100 h-100 shadow-sm" src="{{ asset($customer->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $customer->name }}">
          <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning text-dark p-1 border border-translucent">
            <i class="fa-solid fa-crown fs-11"></i>
          </span>
        </div>

        <h5 class="fw-bold text-body-emphasis mb-1">{{ $customer->name }}</h5>
        <p class="text-body-tertiary fs-10 mb-3">{{ $customer->email }}</p>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
          <span class="badge badge-phoenix badge-phoenix-warning fs-10">
            <i class="fa-solid fa-award me-1"></i> {{ $customer->rank }}
          </span>
          <span class="badge badge-phoenix badge-phoenix-success fs-10">
            <i class="fa-solid fa-circle-check me-1"></i> Thành Viên
          </span>
        </div>

        <div class="text-start border-top border-translucent pt-3 fs-10">
          <div class="d-flex justify-content-between py-1.5 border-bottom border-translucent">
            <span class="text-body-tertiary">Mã khách:</span>
            <strong class="text-body-emphasis font-monospace">#CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</strong>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom border-translucent">
            <span class="text-body-tertiary">Số điện thoại:</span>
            <strong class="text-body-emphasis">{{ $customer->phone ?? 'Chưa cập nhật' }}</strong>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom border-translucent">
            <span class="text-body-tertiary">Giới tính:</span>
            <strong class="text-body-emphasis">{{ $customer->gender ?? 'Nam' }}</strong>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom border-translucent">
            <span class="text-body-tertiary">Ngày sinh:</span>
            <strong class="text-body-emphasis">{{ $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('d/m/Y') : 'Chưa cập nhật' }}</strong>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom border-translucent">
            <span class="text-body-tertiary">Ngày tham gia:</span>
            <strong class="text-body-emphasis">{{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : '' }}</strong>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom border-translucent">
            <span class="text-body-tertiary">Tổng chi tiêu:</span>
            <strong class="text-danger fs-9 font-monospace">{{ number_format($customerTotalSpent, 0, ',', '.') }}₫</strong>
          </div>
          <div class="d-flex justify-content-between py-1.5 border-bottom border-translucent">
            <span class="text-body-tertiary">Đơn hoàn tất:</span>
            <strong class="text-success">{{ $customerCompletedOrdersCount }} / {{ $customerOrdersCount }} đơn</strong>
          </div>
          <div class="d-flex justify-content-between py-1.5">
            <span class="text-body-tertiary">Địa chỉ nhận hàng:</span>
            <strong class="text-body-emphasis text-end" style="max-width: 180px;">{{ $customer->address ? ($customer->address . ', ' . $customer->district . ', ' . $customer->city) : 'Chưa cập nhật' }}</strong>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT COLUMN: TABS -->
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis pb-0">
        <ul class="nav nav-underline fs-9" id="custTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="cust-orders-tab" data-bs-toggle="tab" href="#cust-orders" role="tab">
              <i class="fa-solid fa-box-archive me-1 text-primary"></i> Đơn Hàng ({{ $customer->orders->count() }})
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="cust-addresses-tab" data-bs-toggle="tab" href="#cust-addresses" role="tab">
              <i class="fa-solid fa-map-location-dot me-1 text-info"></i> Sổ Địa Chỉ ({{ $customer->addresses->count() }})
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="cust-all-spending-tab" data-bs-toggle="tab" href="#cust-all-spending" role="tab">
              <i class="fa-solid fa-chart-pie me-1 text-warning"></i> Chi Tiêu Toàn Shop ({{ $allPurchasingCustomers->count() }})
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="cust-reviews-tab" data-bs-toggle="tab" href="#cust-reviews" role="tab">
              <i class="fa-solid fa-star me-1 text-warning"></i> Đánh Giá ({{ $customer->reviews->count() }})
            </a>
          </li>
        </ul>
      </div>

      <div class="card-body p-0">
        <div class="tab-content" id="custTabsContent">
          <!-- Tab 1: Orders -->
          <div class="tab-pane fade show active p-3" id="cust-orders" role="tabpanel">
            <div class="table-responsive scrollbar">
              <table class="table table-sm fs-9 mb-0 align-middle">
                <thead class="bg-body-tertiary text-body-tertiary">
                  <tr>
                    <th class="ps-2 py-2">Mã Đơn</th>
                    <th class="py-2">Sản Phẩm Đã Mua</th>
                    <th class="py-2">Ngày Đặt</th>
                    <th class="py-2">Tổng Tiền</th>
                    <th class="py-2">Trạng Thái</th>
                    <th class="text-end pe-2 py-2">Thao Tác</th>
                  </tr>
                </thead>
                <tbody class="list">
                  @forelse($customer->orders as $order)
                    <tr class="border-bottom border-translucent">
                      <td class="ps-2 py-2"><strong class="font-monospace text-primary">#{{ $order->order_code }}</strong></td>
                      <td class="py-2">
                        <div class="d-flex align-items-center gap-1 flex-wrap" style="max-width: 240px;">
                          @foreach($order->items->take(2) as $it)
                            <div class="d-flex align-items-center gap-1 p-1 bg-body-tertiary rounded border border-translucent">
                              <img src="{{ $it->image ? asset($it->image) : asset('/assets/img/products/1.png') }}" alt="{{ $it->product_name }}" style="width: 28px; height: 28px; object-fit: cover;" class="rounded bg-body-emphasis">
                              <span class="text-body-emphasis text-truncate fs-11" style="max-width: 80px;">{{ $it->product_name }}</span>
                            </div>
                          @endforeach
                          @if($order->items->count() > 2)
                            <span class="badge badge-phoenix badge-phoenix-secondary fs-11">+{{ $order->items->count() - 2 }}</span>
                          @endif
                        </div>
                      </td>
                      <td class="py-2"><small class="text-body-tertiary fs-10">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</small></td>
                      <td class="py-2"><strong class="text-danger font-monospace fs-9">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
                      <td class="py-2">
                        @if($order->shipping_status === 'completed')
                          <span class="badge badge-phoenix badge-phoenix-success">Hoàn tất</span>
                        @elseif($order->shipping_status === 'delivered')
                          <span class="badge badge-phoenix badge-phoenix-success">Đã giao</span>
                        @elseif($order->shipping_status === 'shipping')
                          <span class="badge badge-phoenix badge-phoenix-warning">Đang giao</span>
                        @elseif($order->shipping_status === 'processing')
                          <span class="badge badge-phoenix badge-phoenix-info">Đóng gói</span>
                        @elseif($order->shipping_status === 'cancelled')
                          <span class="badge badge-phoenix badge-phoenix-danger">Đã hủy</span>
                        @else
                          <span class="badge badge-phoenix badge-phoenix-secondary">{{ $order->status_label ?? 'Chờ xác nhận' }}</span>
                        @endif
                      </td>
                      <td class="text-end pe-2 py-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10">
                          Chi Tiết
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center py-4 text-body-tertiary">Khách hàng chưa có đơn hàng nào.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Tab 2: Addresses -->
          <div class="tab-pane fade p-3" id="cust-addresses" role="tabpanel">
            <div class="d-flex flex-column gap-2">
              @forelse($customer->addresses as $cAddr)
                <div class="p-3 bg-body-tertiary rounded border border-translucent {{ $cAddr->is_default ? 'border-primary' : '' }}">
                  <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                      <strong class="text-body-emphasis fs-9">{{ $cAddr->recipient_name }}</strong>
                      <span class="text-body-tertiary fs-10 font-monospace"><i class="fa-solid fa-phone fs-11 text-muted me-1"></i>{{ $cAddr->phone }}</span>
                      @if($cAddr->is_default)
                        <span class="badge badge-phoenix badge-phoenix-primary fs-11"><i class="fa-solid fa-circle-check me-1"></i> Mặc định</span>
                      @endif
                    </div>
                    <small class="text-body-tertiary fs-11">Ngày tạo: {{ $cAddr->created_at ? $cAddr->created_at->format('d/m/Y') : 'N/A' }}</small>
                  </div>
                  <p class="text-body-secondary fs-10 mb-0">
                    <i class="fa-solid fa-location-dot text-danger me-1"></i>
                    <span>{{ $cAddr->address }}</span>@if($cAddr->ward)<span>, {{ $cAddr->ward }}</span>@endif@if($cAddr->district)<span>, {{ $cAddr->district }}</span>@endif@if($cAddr->city)<span>, {{ $cAddr->city }}</span>@endif
                  </p>
                </div>
              @empty
                <div class="text-center py-4 text-body-tertiary">
                  Khách hàng này chưa thêm địa chỉ nào vào sổ địa chỉ.
                </div>
              @endforelse
            </div>
          </div>

          <!-- Tab 3: All Customers Spending -->
          <div class="tab-pane fade p-3" id="cust-all-spending" role="tabpanel">
            <div class="table-responsive scrollbar">
              <table class="table table-sm fs-9 mb-0 align-middle">
                <thead class="bg-body-tertiary text-body-tertiary">
                  <tr>
                    <th class="ps-2 py-2" style="width: 50px;">Hạng</th>
                    <th class="py-2">Khách Hàng</th>
                    <th class="py-2">Số Đơn</th>
                    <th class="py-2">Tổng Chi Tiêu</th>
                    <th class="py-2">Tỷ Lệ Toàn Shop</th>
                    <th class="py-2">Hạng Thành Viên</th>
                    <th class="text-end pe-2 py-2">Hành Động</th>
                  </tr>
                </thead>
                <tbody class="list">
                  @forelse($allPurchasingCustomers as $idx => $c)
                    @php
                      $isCurrent = ($c->id === $customer->id);
                      $share = $totalAllCustomersSpent > 0 ? round(($c->total_spent / $totalAllCustomersSpent) * 100, 1) : 0;
                    @endphp
                    <tr class="border-bottom border-translucent {{ $isCurrent ? 'bg-warning-subtle' : '' }}">
                      <td class="ps-2 py-2">
                        @if($idx === 0)
                          <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-crown"></i> 1</span>
                        @else
                          <span class="text-body-tertiary">#{{ $idx + 1 }}</span>
                        @endif
                      </td>
                      <td class="py-2">
                        <div class="d-flex align-items-center gap-2">
                          <img src="{{ $c->avatar_url ?? asset('/assets/img/team/40x40/58.webp') }}" alt="{{ $c->name }}" class="rounded-circle border border-translucent bg-body-emphasis" style="width: 32px; height: 32px; object-fit: cover;">
                          <div>
                            <strong class="text-body-emphasis d-block fs-10">
                              {{ $c->name }}
                              @if($isCurrent)
                                <span class="badge badge-phoenix badge-phoenix-danger ms-1 fs-11">(Đang xem)</span>
                              @endif
                            </strong>
                            <small class="text-body-tertiary fs-11">{{ $c->email }}</small>
                          </div>
                        </div>
                      </td>
                      <td class="py-2"><span class="badge badge-phoenix badge-phoenix-secondary">{{ $c->orders_count }} đơn</span></td>
                      <td class="py-2"><strong class="text-danger font-monospace fs-9">{{ number_format($c->total_spent, 0, ',', '.') }}₫</strong></td>
                      <td class="py-2">
                        <div class="d-flex align-items-center gap-1">
                          <div class="progress flex-grow-1" style="height: 5px; width: 50px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $share }}%"></div>
                          </div>
                          <span class="fw-semibold fs-11 text-body-emphasis">{{ $share }}%</span>
                        </div>
                      </td>
                      <td class="py-2">
                        <span class="badge badge-phoenix badge-phoenix-warning fs-11">{{ $c->rank ?? 'Hội Viên' }}</span>
                      </td>
                      <td class="text-end pe-2 py-2">
                        @if(!$isCurrent)
                          <a href="{{ route('admin.customers.show', $c->id) }}" class="btn btn-sm btn-phoenix-secondary py-1 px-2 fs-10">
                            Xem
                          </a>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center py-4 text-body-tertiary">Chưa có khách hàng nào phát sinh đơn hàng.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Tab 4: Reviews -->
          <div class="tab-pane fade p-3" id="cust-reviews" role="tabpanel">
            <div class="d-flex flex-column gap-2">
              @forelse($customer->reviews as $rev)
                <div class="p-3 bg-body-tertiary rounded border border-translucent">
                  <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ $rev->product ? asset($rev->product->image) : asset('/assets/img/products/1.png') }}" alt="{{ $rev->product->name ?? '' }}" style="width: 36px; height: 36px; object-fit: cover;" class="rounded border border-translucent bg-body-emphasis">
                      <div>
                        <strong class="text-body-emphasis fs-10 d-block">{{ $rev->product->name ?? 'Sản phẩm' }}</strong>
                        <small class="text-body-tertiary fs-11">{{ $rev->created_at ? $rev->created_at->format('d/m/Y') : '' }}</small>
                      </div>
                    </div>
                    <div class="text-warning fs-10">
                      @for($i=1; $i<=5; $i++)
                        <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-body-tertiary' }}"></i>
                      @endfor
                      <span class="fw-bold text-body-emphasis ms-1">({{ $rev->rating }}/5)</span>
                    </div>
                  </div>
                  <p class="fs-10 text-body-secondary mb-0 fst-italic">
                    "{{ $rev->comment }}"
                  </p>
                </div>
              @empty
                <div class="text-center py-4 text-body-tertiary">
                  Khách hàng này chưa viết đánh giá nào.
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
