@extends('layouts.admin')

@section('title', 'Hồ Sơ Khách Hàng: ' . $customer->name . ' | BeeStyle Admin')

@section('content')
<!-- BREADCRUMB & HEADER -->
<div class="mb-4">
  <div class="row gy-3 justify-content-between align-items-center">
    <div class="col-md">
      <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('admin.customers.index') }}" class="btn btn-phoenix-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Quay lại danh sách khách hàng">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="mb-0 text-body-emphasis fw-bold">{{ $customer->name }}</h2>
        <span class="badge badge-phoenix badge-phoenix-primary fs-10 font-monospace">
          #CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
        </span>
        <span class="badge badge-phoenix {{ $customer->rank_badge_class ?? 'badge-phoenix-warning' }} fs-10">
          <i class="fa-solid fa-crown me-1 text-warning"></i> {{ $customer->rank }}
        </span>
        @if($customer->status === 'banned')
          <span class="badge badge-phoenix badge-phoenix-danger fs-10">
            <i class="fa-solid fa-ban me-1"></i> Bị khóa
          </span>
        @else
          <span class="badge badge-phoenix badge-phoenix-success fs-10">
            <i class="fa-solid fa-circle-check me-1"></i> Hoạt động
          </span>
        @endif
      </div>
      <p class="text-body-tertiary mb-0 fs-9">
        Hồ sơ tài khoản thành viên đăng nhập, tổng chi tiêu tích lũy, các địa chỉ giao hàng và toàn bộ lịch sử đơn hàng
      </p>
    </div>
    <div class="col-auto">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <!-- Nút sửa thông tin mở modal -->
        <button type="button" class="btn btn-phoenix-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
          <i class="fa-regular fa-pen-to-square me-1"></i> Sửa Thông Tin
        </button>

        <!-- Nút Khóa / Mở Khóa Tài Khoản -->
        <form action="{{ route('admin.customers.toggleStatus', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn {{ $customer->status === 'banned' ? 'MỞ KHÓA' : 'KHÓA' }} tài khoản của {{ addslashes($customer->name) }}?');">
          @csrf
          @method('PATCH')
          @if($customer->status === 'banned')
            <button type="submit" class="btn btn-subtle-success btn-sm">
              <i class="fa-solid fa-lock-open me-1"></i> Mở Khóa Tài Khoản
            </button>
          @else
            <button type="submit" class="btn btn-subtle-danger btn-sm">
              <i class="fa-solid fa-lock me-1"></i> Khóa Tài Khoản
            </button>
          @endif
        </form>

        <a href="{{ route('admin.customers.index') }}" class="btn btn-phoenix-secondary btn-sm">
          <i class="fa-solid fa-users me-1"></i> Danh Sách
        </a>
      </div>
    </div>
  </div>
</div>

<!-- THÔNG BÁO FLASH -->
@if(session('success'))
  <div class="alert alert-subtle-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <i class="fa-solid fa-circle-check fs-6 text-success me-2"></i>
    <div class="flex-grow-1 fs-9 fw-semibold text-body-emphasis">{{ session('success') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-subtle-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
    <i class="fa-solid fa-triangle-exclamation fs-6 text-danger me-2"></i>
    <div class="flex-grow-1 fs-9 fw-semibold text-body-emphasis">{{ session('error') }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- 4 THẺ STATS KPI CỦA RIÊNG KHÁCH HÀNG NÀY -->
<div class="row g-3 mb-4">
  <!-- Thẻ 1: Tổng chi tiêu -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body-emphasis">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-bold fs-10 tracking-wider">Tổng Chi Tiêu Tích Lũy</h6>
            <h3 class="text-danger mb-0 fw-bolder">{{ number_format($customerTotalSpent, 0, ',', '.') }}₫</h3>
          </div>
          <div class="rounded-3 p-2 bg-danger-subtle text-danger d-flex align-items-center justify-content-center shadow-xs" style="width: 46px; height: 46px;">
            <i class="fa-solid fa-wallet fs-7"></i>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent fs-10">
          <span class="text-body-tertiary">Đóng góp toàn shop:</span>
          <strong class="text-primary">{{ $customerContributionPercent }}%</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Đơn hàng & Tỷ lệ hoàn tất -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body-emphasis">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-bold fs-10 tracking-wider">Đơn Hàng Mua Sắm</h6>
            <h3 class="text-success mb-0 fw-bolder">{{ $customerOrdersCount }} <span class="fs-9 fw-normal text-muted">đơn</span></h3>
          </div>
          <div class="rounded-3 p-2 bg-success-subtle text-success d-flex align-items-center justify-content-center shadow-xs" style="width: 46px; height: 46px;">
            <i class="fa-solid fa-bag-shopping fs-7"></i>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent fs-10">
          <span class="text-body-tertiary">Hoàn tất: <strong class="text-success">{{ $customerCompletedOrdersCount }}</strong> | Hủy: <strong class="text-danger">{{ $customerCancelledOrdersCount }}</strong></span>
          <span class="badge badge-phoenix badge-phoenix-success fs-11">{{ $customerSuccessRate }}% thành công</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 3: Giá trị trung bình đơn (AOV) -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body-emphasis">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-bold fs-10 tracking-wider">Chi Tiêu TB / Đơn (AOV)</h6>
            <h3 class="text-body-emphasis mb-0 fw-bolder">{{ number_format($customerAverageOrderValue, 0, ',', '.') }}₫</h3>
          </div>
          <div class="rounded-3 p-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center shadow-xs" style="width: 46px; height: 46px;">
            <i class="fa-solid fa-calculator fs-7"></i>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent fs-10">
          <span class="text-body-tertiary">Điểm tích lũy:</span>
          <strong class="text-warning"><i class="fa-solid fa-coins me-1"></i>{{ number_format($customer->points ?? 0) }} điểm</strong>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 4: Xếp hạng toàn shop -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card border-0 shadow-sm h-100 bg-body-emphasis">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <h6 class="text-body-tertiary text-uppercase mb-1 fw-bold fs-10 tracking-wider">Xếp Hạng Chi Tiêu</h6>
            <h3 class="text-warning mb-0 fw-bolder">Top #{{ $customerRankPosition }} <span class="fs-9 fw-normal text-muted">/ {{ $totalPurchasingAccounts }}</span></h3>
          </div>
          <div class="rounded-3 p-2 bg-warning-subtle text-warning d-flex align-items-center justify-content-center shadow-xs" style="width: 46px; height: 46px;">
            <i class="fa-solid fa-trophy fs-7"></i>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-translucent fs-10">
          <span class="text-body-tertiary">Toàn shop: <strong class="text-body-emphasis">{{ number_format($totalAllCustomersSpent, 0, ',', '.') }}₫</strong></span>
          <span class="badge badge-phoenix {{ $customer->rank_badge_class ?? 'badge-phoenix-warning' }} fs-11">{{ $customer->rank }}</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <!-- CỘT TRÁI: HỒ SƠ TÀI KHOẢN -->
  <div class="col-12 col-lg-4">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body p-4 text-center">
        <!-- Avatar lớn -->
        <div class="position-relative mx-auto mb-3" style="width: 96px; height: 96px;">
          <img class="rounded-circle border border-2 border-warning object-fit-cover w-100 h-100 shadow-sm" src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}">
          <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning text-dark p-1.5 border border-2 border-white shadow-xs">
            <i class="fa-solid fa-crown fs-10"></i>
          </span>
        </div>

        <h5 class="fw-bold text-body-emphasis mb-1">{{ $customer->name }}</h5>
        <p class="text-body-tertiary fs-10 mb-2">{{ $customer->email }}</p>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
          <span class="badge badge-phoenix {{ $customer->rank_badge_class ?? 'badge-phoenix-warning' }} fs-10">
            <i class="fa-solid fa-award me-1"></i> {{ $customer->rank }}
          </span>
          @if($customer->status === 'banned')
            <span class="badge badge-phoenix badge-phoenix-danger fs-10">
              <i class="fa-solid fa-ban me-1"></i> Tài khoản bị khóa
            </span>
          @else
            <span class="badge badge-phoenix badge-phoenix-success fs-10">
              <i class="fa-solid fa-circle-check me-1"></i> Đang hoạt động
            </span>
          @endif
        </div>

        <!-- Chi tiết hồ sơ -->
        <div class="text-start border-top border-translucent pt-3 fs-10">
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Mã khách hàng:</span>
            <strong class="text-body-emphasis font-monospace">#CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</strong>
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Số điện thoại:</span>
            <strong class="text-body-emphasis">{{ $customer->phone ?: 'Chưa cập nhật' }}</strong>
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Giới tính:</span>
            <strong class="text-body-emphasis">{{ $customer->gender ?? 'Chưa rõ' }}</strong>
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Ngày sinh:</span>
            <strong class="text-body-emphasis">{{ $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('d/m/Y') : 'Chưa cập nhật' }}</strong>
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Ngày đăng ký:</span>
            <strong class="text-body-emphasis">{{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i') : 'N/A' }}</strong>
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Xác thực Email:</span>
            @if($customer->email_verified_at)
              <span class="text-success fw-bold"><i class="fa-solid fa-check-double me-1"></i> Đã xác thực</span>
            @else
              <span class="text-secondary"><i class="fa-regular fa-clock me-1"></i> Chưa xác thực</span>
            @endif
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Xác thực Số điện thoại:</span>
            @if($customer->phone_verified_at)
              <span class="text-success fw-bold"><i class="fa-solid fa-check-double me-1"></i> Đã xác thực</span>
            @else
              <span class="text-secondary"><i class="fa-regular fa-clock me-1"></i> Chưa xác thực</span>
            @endif
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Điểm thưởng tích lũy:</span>
            <strong class="text-warning font-monospace fs-9">{{ number_format($customer->points ?? 0) }} điểm</strong>
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
            <span class="text-body-tertiary">Địa chỉ đăng ký:</span>
            @php
              $fullAddr = trim(($customer->address ?? '') . ($customer->district ? ', ' . $customer->district : '') . ($customer->city ? ', ' . $customer->city : ''));
            @endphp
            <strong class="text-body-emphasis text-end" style="max-width: 190px;">
              {{ $fullAddr ?: 'Chưa cập nhật' }}
            </strong>
          </div>
          @if($customer->bank_account_number)
            <div class="d-flex justify-content-between py-2 border-bottom border-translucent">
              <span class="text-body-tertiary">Tài khoản hoàn tiền:</span>
              <strong class="text-body-emphasis text-end font-monospace">
                {{ $customer->bank_name }} - {{ $customer->bank_account_number }} ({{ $customer->bank_account_name }})
              </strong>
            </div>
          @endif
        </div>

        <div class="mt-3 pt-2 d-grid gap-2">
          <button type="button" class="btn btn-phoenix-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="fa-regular fa-pen-to-square me-1"></i> Chỉnh Sửa Thông Tin
          </button>
          <a href="mailto:{{ $customer->email }}?subject=BeeStyle CSKH hỗ trợ bạn" class="btn btn-phoenix-secondary btn-sm w-100">
            <i class="fa-regular fa-envelope me-1"></i> Gửi Email Khách Hàng
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- CỘT PHẢI: HỆ THỐNG TABS ĐẦY ĐỦ -->
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header border-bottom border-translucent bg-body-emphasis pb-0 pt-3">
        <ul class="nav nav-underline fs-9" id="custTabs" role="tablist">
          <!-- Tab 1: Đơn Hàng -->
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="cust-orders-tab" data-bs-toggle="tab" href="#cust-orders" role="tab">
              <i class="fa-solid fa-box-archive me-1 text-primary"></i> Đơn Hàng ({{ $customer->orders->count() }})
            </a>
          </li>
          <!-- Tab 2: Sổ Địa Chỉ -->
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="cust-addresses-tab" data-bs-toggle="tab" href="#cust-addresses" role="tab">
              <i class="fa-solid fa-map-location-dot me-1 text-info"></i> Sổ Địa Chỉ &amp; Giao Nhận
            </a>
          </li>
          <!-- Tab 3: Đánh Giá -->
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="cust-reviews-tab" data-bs-toggle="tab" href="#cust-reviews" role="tab">
              <i class="fa-solid fa-star me-1 text-warning"></i> Đánh Giá ({{ $customer->reviews->count() }})
            </a>
          </li>
          <!-- Tab 4: Thống Kê Toàn Shop -->
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="cust-all-spending-tab" data-bs-toggle="tab" href="#cust-all-spending" role="tab">
              <i class="fa-solid fa-chart-pie me-1 text-success"></i> Top Chi Tiêu Toàn Shop ({{ $allPurchasingCustomers->count() }})
            </a>
          </li>
        </ul>
      </div>

      <div class="card-body p-0">
        <div class="tab-content" id="custTabsContent">
          <!-- TAB 1: DANH SÁCH ĐƠN HÀNG CỦA KHÁCH -->
          <div class="tab-pane fade show active p-3" id="cust-orders" role="tabpanel">
            <div class="table-responsive scrollbar">
              <table class="table table-hover table-sm fs-9 mb-0 align-middle">
                <thead class="bg-body-tertiary text-body-tertiary border-bottom border-translucent">
                  <tr>
                    <th class="ps-2 py-2">Mã Đơn</th>
                    <th class="py-2">Sản Phẩm</th>
                    <th class="py-2">Ngày Đặt</th>
                    <th class="py-2">Thanh Toán</th>
                    <th class="py-2">Vận Chuyển</th>
                    <th class="py-2">Tổng Tiền</th>
                    <th class="text-end pe-2 py-2">Thao Tác</th>
                  </tr>
                </thead>
                <tbody class="list">
                  @forelse($customer->orders as $order)
                    <tr class="border-bottom border-translucent">
                      <!-- Mã đơn -->
                      <td class="ps-2 py-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="font-monospace fw-bold text-primary text-decoration-none">
                          #{{ $order->order_code }}
                        </a>
                      </td>

                      <!-- Sản phẩm -->
                      <td class="py-2">
                        <div class="d-flex align-items-center gap-1.5 flex-wrap" style="max-width: 260px;">
                          @foreach($order->items->take(2) as $it)
                            <div class="d-flex align-items-center gap-1.5 p-1 bg-body-tertiary rounded border border-translucent">
                              <img src="{{ $it->image ? asset($it->image) : asset($it->product->thumbnail ?? 'assets/img/products/1.png') }}" alt="{{ $it->product_name }}" style="width: 28px; height: 28px; object-fit: cover;" class="rounded bg-body-emphasis">
                              <span class="text-body-emphasis text-truncate fs-11" style="max-width: 90px;" title="{{ $it->product_name }}">
                                {{ $it->product_name }}
                              </span>
                              <span class="badge badge-phoenix badge-phoenix-secondary fs-11">x{{ $it->quantity }}</span>
                            </div>
                          @endforeach
                          @if($order->items->count() > 2)
                            <span class="badge badge-phoenix badge-phoenix-secondary fs-11">+{{ $order->items->count() - 2 }}</span>
                          @endif
                        </div>
                      </td>

                      <!-- Ngày đặt -->
                      <td class="py-2">
                        <div class="text-body-emphasis fs-10">{{ $order->created_at ? $order->created_at->format('d/m/Y') : '' }}</div>
                        <small class="text-body-tertiary fs-11">{{ $order->created_at ? $order->created_at->format('H:i') : '' }}</small>
                      </td>

                      <!-- Thanh toán -->
                      <td class="py-2">
                        <div class="text-uppercase fs-11 fw-bold mb-0.5 text-body-tertiary">{{ $order->payment_method }}</div>
                        @if($order->payment_status === 'paid')
                          <span class="badge badge-phoenix badge-phoenix-success fs-11"><i class="fa-solid fa-check me-1"></i> Đã TT</span>
                        @elseif($order->payment_status === 'refunded')
                          <span class="badge badge-phoenix badge-phoenix-warning fs-11"><i class="fa-solid fa-rotate-left me-1"></i> Đã hoàn</span>
                        @elseif($order->payment_status === 'cancelled')
                          <span class="badge badge-phoenix badge-phoenix-danger fs-11">Đã hủy</span>
                        @else
                          <span class="badge badge-phoenix badge-phoenix-secondary fs-11">Chưa TT</span>
                        @endif
                      </td>

                      <!-- Vận chuyển -->
                      <td class="py-2">
                        @if($order->shipping_status === 'completed')
                          <span class="badge badge-phoenix badge-phoenix-success fs-10">Hoàn tất</span>
                        @elseif($order->shipping_status === 'delivered')
                          <span class="badge badge-phoenix badge-phoenix-success fs-10">Đã giao</span>
                        @elseif($order->shipping_status === 'shipping')
                          <span class="badge badge-phoenix badge-phoenix-warning fs-10">Đang giao</span>
                        @elseif($order->shipping_status === 'processing')
                          <span class="badge badge-phoenix badge-phoenix-info fs-10">Đóng gói</span>
                        @elseif($order->shipping_status === 'cancelled')
                          <span class="badge badge-phoenix badge-phoenix-danger fs-10">Đã hủy</span>
                        @else
                          <span class="badge badge-phoenix badge-phoenix-secondary fs-10">Chờ duyệt</span>
                        @endif
                      </td>

                      <!-- Tổng tiền -->
                      <td class="py-2">
                        <strong class="text-danger font-monospace fs-9">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                      </td>

                      <!-- Thao tác -->
                      <td class="text-end pe-2 py-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10" title="Xem chi tiết đơn hàng này">
                          Chi Tiết
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center py-5 text-body-tertiary">
                        <i class="fa-solid fa-box-open fs-3 text-body-tertiary mb-2 d-block"></i>
                        Khách hàng này chưa có đơn hàng nào phát sinh trên hệ thống.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 2: SỔ ĐỊA CHỈ & THÔNG TIN GIAO NHẬN -->
          <div class="tab-pane fade p-3" id="cust-addresses" role="tabpanel">
            <div class="row g-3">
              <!-- Địa chỉ chính từ Profile -->
              @if(!empty($customer->address))
                <div class="col-12 col-md-6">
                  <div class="p-3 bg-body-tertiary rounded-3 border border-primary h-100 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="badge badge-phoenix badge-phoenix-primary fs-11">
                        <i class="fa-solid fa-house-chimney me-1"></i> Địa Chỉ Hồ Sơ Chính
                      </span>
                      <span class="text-body-tertiary fs-11 font-monospace">Hồ sơ cá nhân</span>
                    </div>
                    <h6 class="fw-bold text-body-emphasis mb-1">{{ $customer->name }}</h6>
                    <div class="text-body-secondary fs-10 mb-1">
                      <i class="fa-solid fa-phone text-muted me-1"></i> {{ $customer->phone ?: 'Chưa cập nhật SĐT' }}
                    </div>
                    <div class="text-body-secondary fs-10">
                      <i class="fa-solid fa-location-dot text-danger me-1"></i>
                      <span>{{ $customer->address }}</span>
                      @if($customer->district)<span>, {{ $customer->district }}</span>@endif
                      @if($customer->city)<span>, {{ $customer->city }}</span>@endif
                    </div>
                  </div>
                </div>
              @endif

              <!-- Các địa chỉ trong Sổ địa chỉ (UserAddress) -->
              @foreach($customer->addresses as $cAddr)
                <div class="col-12 col-md-6">
                  <div class="p-3 bg-body-tertiary rounded-3 border border-translucent h-100 {{ $cAddr->is_default ? 'border-success' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      @if($cAddr->is_default)
                        <span class="badge badge-phoenix badge-phoenix-success fs-11">
                          <i class="fa-solid fa-star me-1"></i> Sổ Địa Chỉ Mặc Định
                        </span>
                      @else
                        <span class="badge badge-phoenix badge-phoenix-secondary fs-11">
                          <i class="fa-solid fa-address-book me-1"></i> Sổ Địa Chỉ Đã Lưu
                        </span>
                      @endif
                      <span class="text-body-tertiary fs-11">{{ $cAddr->created_at ? $cAddr->created_at->format('d/m/Y') : '' }}</span>
                    </div>
                    <h6 class="fw-bold text-body-emphasis mb-1">{{ $cAddr->recipient_name }}</h6>
                    <div class="text-body-secondary fs-10 mb-1">
                      <i class="fa-solid fa-phone text-muted me-1"></i> {{ $cAddr->phone }}
                    </div>
                    <div class="text-body-secondary fs-10">
                      <i class="fa-solid fa-location-dot text-danger me-1"></i>
                      <span>{{ $cAddr->address }}</span>
                      @if($cAddr->ward)<span>, {{ $cAddr->ward }}</span>@endif
                      @if($cAddr->district)<span>, {{ $cAddr->district }}</span>@endif
                      @if($cAddr->city)<span>, {{ $cAddr->city }}</span>@endif
                    </div>
                  </div>
                </div>
              @endforeach

              <!-- Các địa chỉ nhận hàng từ Lịch sử Đơn hàng (Đảm bảo không bao giờ trống) -->
              @foreach($orderShippingAddresses as $orderAddr)
                <div class="col-12 col-md-6">
                  <div class="p-3 bg-body-tertiary rounded-3 border border-translucent h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="badge badge-phoenix badge-phoenix-info fs-11">
                        <i class="fa-solid fa-truck-fast me-1"></i> Địa Chỉ Nhận Hàng Thực Tế
                      </span>
                      <a href="{{ route('admin.orders.show', $orderAddr->id) }}" class="font-monospace fs-11 text-primary text-decoration-none">
                        #{{ $orderAddr->order_code }}
                      </a>
                    </div>
                    <h6 class="fw-bold text-body-emphasis mb-1">{{ $orderAddr->customer_name }}</h6>
                    <div class="text-body-secondary fs-10 mb-1">
                      <i class="fa-solid fa-phone text-muted me-1"></i> {{ $orderAddr->customer_phone }}
                    </div>
                    <div class="text-body-secondary fs-10">
                      <i class="fa-solid fa-location-dot text-danger me-1"></i>
                      <span>{{ $orderAddr->shipping_address }}</span>
                      @if($orderAddr->district)<span>, {{ $orderAddr->district }}</span>@endif
                      @if($orderAddr->city)<span>, {{ $orderAddr->city }}</span>@endif
                    </div>
                  </div>
                </div>
              @endforeach

              @if(empty($customer->address) && $customer->addresses->isEmpty() && $orderShippingAddresses->isEmpty())
                <div class="col-12 text-center py-5 text-body-tertiary">
                  <i class="fa-solid fa-map-pin fs-3 text-body-tertiary mb-2 d-block"></i>
                  Khách hàng này chưa cập nhật địa chỉ nào trong hồ sơ hoặc lịch sử giao dịch.
                </div>
              @endif
            </div>
          </div>

          <!-- TAB 3: ĐÁNH GIÁ & NHẬN XÉT SẢN PHẨM -->
          <div class="tab-pane fade p-3" id="cust-reviews" role="tabpanel">
            <div class="d-flex flex-column gap-3">
              @forelse($customer->reviews as $rev)
                <div class="p-3 bg-body-tertiary rounded-3 border border-translucent">
                  <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ $rev->product ? asset($rev->product->thumbnail) : asset('assets/img/products/1.png') }}" alt="{{ $rev->product->name ?? '' }}" style="width: 42px; height: 42px; object-fit: cover;" class="rounded border border-translucent bg-body-emphasis">
                      <div>
                        @if($rev->product)
                          <a href="{{ route('admin.products.show', $rev->product->id) }}" class="fw-bold text-body-emphasis fs-10 d-block text-decoration-none hover-primary">
                            {{ $rev->product->name }}
                          </a>
                        @else
                          <strong class="text-body-emphasis fs-10 d-block">Sản phẩm</strong>
                        @endif
                        <small class="text-body-tertiary fs-11">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}</small>
                      </div>
                    </div>
                    <div class="text-warning fs-10">
                      @for($i=1; $i<=5; $i++)
                        <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-body-tertiary' }}"></i>
                      @endfor
                      <span class="fw-bold text-body-emphasis ms-1">({{ $rev->rating }}/5)</span>
                    </div>
                  </div>
                  <p class="fs-10 text-body-secondary mb-0 fst-italic ps-1 border-start border-3 border-warning">
                    "{{ $rev->comment }}"
                  </p>
                </div>
              @empty
                <div class="text-center py-5 text-body-tertiary">
                  <i class="fa-regular fa-comment-dots fs-3 text-body-tertiary mb-2 d-block"></i>
                  Khách hàng này chưa để lại đánh giá nào cho các sản phẩm đã mua.
                </div>
              @endforelse
            </div>
          </div>

          <!-- TAB 4: BẢNG XẾP HẠNG DOANH THU TOÀN SHOP -->
          <div class="tab-pane fade p-3" id="cust-all-spending" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 class="fw-bold text-body-emphasis mb-0">Bảng Xếp Hạng Doanh Thu Tất Cả Thành Viên</h6>
                <small class="text-body-tertiary fs-11">So sánh tổng chi tiêu và tỷ trọng đóng góp của khách hàng này so với toàn bộ khách trong shop</small>
              </div>
              <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold">
                {{ $allPurchasingCustomers->count() }} khách hàng đã mua
              </span>
            </div>

            <div class="table-responsive scrollbar">
              <table class="table table-sm fs-9 mb-0 align-middle">
                <thead class="bg-body-tertiary text-body-tertiary border-bottom border-translucent">
                  <tr>
                    <th class="ps-2 py-2" style="width: 60px;">Hạng</th>
                    <th class="py-2">Khách Hàng</th>
                    <th class="py-2 text-center">Số Đơn</th>
                    <th class="py-2">Tổng Chi Tiêu</th>
                    <th class="py-2" style="min-width: 130px;">Tỷ Trọng Shop</th>
                    <th class="py-2">Hạng VIP</th>
                    <th class="text-end pe-2 py-2">Thao Tác</th>
                  </tr>
                </thead>
                <tbody class="list">
                  @forelse($allPurchasingCustomers as $idx => $c)
                    @php
                      $isCurrent = ($c->id === $customer->id);
                      $share = $totalAllCustomersSpent > 0 ? round(($c->total_spent / $totalAllCustomersSpent) * 100, 1) : 0;
                    @endphp
                    <tr class="border-bottom border-translucent {{ $isCurrent ? 'bg-warning-subtle fw-bold' : '' }}">
                      <!-- Hạng -->
                      <td class="ps-2 py-2">
                        @if($idx === 0)
                          <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-crown me-1"></i>#1</span>
                        @elseif($idx === 1)
                          <span class="badge badge-phoenix badge-phoenix-info">#2</span>
                        @elseif($idx === 2)
                          <span class="badge badge-phoenix badge-phoenix-primary">#3</span>
                        @else
                          <span class="text-body-tertiary font-monospace">#{{ $idx + 1 }}</span>
                        @endif
                      </td>

                      <!-- Khách hàng -->
                      <td class="py-2">
                        <div class="d-flex align-items-center gap-2">
                          <img src="{{ $c->avatar_url }}" alt="{{ $c->name }}" class="rounded-circle border border-translucent" style="width: 32px; height: 32px; object-fit: cover;">
                          <div>
                            <span class="text-body-emphasis d-block fs-10">
                              {{ $c->name }}
                              @if($isCurrent)
                                <span class="badge badge-phoenix badge-phoenix-danger ms-1 fs-11">(Đang xem)</span>
                              @endif
                            </span>
                            <small class="text-body-tertiary fs-11">{{ $c->email }}</small>
                          </div>
                        </div>
                      </td>

                      <!-- Số đơn -->
                      <td class="py-2 text-center">
                        <span class="badge badge-phoenix badge-phoenix-secondary">{{ $c->orders_count }} đơn</span>
                      </td>

                      <!-- Tổng chi tiêu -->
                      <td class="py-2">
                        <strong class="text-danger font-monospace fs-9">{{ number_format($c->total_spent, 0, ',', '.') }}₫</strong>
                      </td>

                      <!-- Tỷ lệ đóng góp -->
                      <td class="py-2">
                        <div class="d-flex align-items-center gap-1.5">
                          <div class="progress flex-grow-1" style="height: 6px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $share }}%"></div>
                          </div>
                          <span class="fw-bold fs-11 text-body-emphasis font-monospace">{{ $share }}%</span>
                        </div>
                      </td>

                      <!-- Hạng thành viên -->
                      <td class="py-2">
                        <span class="badge badge-phoenix badge-phoenix-warning fs-11">{{ $c->rank ?? 'Thành viên' }}</span>
                      </td>

                      <!-- Thao tác -->
                      <td class="text-end pe-2 py-2">
                        @if(!$isCurrent)
                          <a href="{{ route('admin.customers.show', $c->id) }}" class="btn btn-sm btn-phoenix-secondary py-0.5 px-2 fs-10">
                            Xem
                          </a>
                        @else
                          <span class="text-muted fs-11">Hiện tại</span>
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
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL SỬA THÔNG TIN KHÁCH HÀNG -->
<div class="modal fade text-start" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h6 class="modal-title fw-bold">
            <i class="fa-solid fa-user-pen me-2 text-primary"></i>
            Chỉnh Sửa Hồ Sơ Khách Hàng #CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-sm" value="{{ $customer->name }}" required>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Số điện thoại</label>
              <input type="text" name="phone" class="form-control form-control-sm" value="{{ $customer->phone }}">
            </div>
            <div class="col-6">
              <label class="form-label">Giới tính</label>
              <select name="gender" class="form-select form-select-sm">
                <option value="Nam" {{ ($customer->gender ?? 'Nam') === 'Nam' ? 'selected' : '' }}>Nam</option>
                <option value="Nữ" {{ ($customer->gender ?? '') === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                <option value="Khác" {{ ($customer->gender ?? '') === 'Khác' ? 'selected' : '' }}>Khác</option>
              </select>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Ngày sinh</label>
              <input type="date" name="dob" class="form-control form-control-sm" value="{{ $customer->dob ? date('Y-m-d', strtotime($customer->dob)) : '' }}">
            </div>
            <div class="col-6">
              <label class="form-label">Điểm tích lũy</label>
              <input type="number" name="points" min="0" class="form-control form-control-sm" value="{{ $customer->points ?? 0 }}">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="address" class="form-control form-control-sm" value="{{ $customer->address }}" placeholder="Số nhà, tên đường...">
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Quận / Huyện</label>
              <input type="text" name="district" class="form-control form-control-sm" value="{{ $customer->district }}">
            </div>
            <div class="col-6">
              <label class="form-label">Tỉnh / Thành phố</label>
              <input type="text" name="city" class="form-control form-control-sm" value="{{ $customer->city ?? 'Hồ Chí Minh' }}">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Trạng thái tài khoản <span class="text-danger">*</span></label>
            <select name="status" class="form-select form-select-sm" required>
              <option value="active" {{ $customer->status === 'active' ? 'selected' : '' }}>Hoạt động (Active)</option>
              <option value="banned" {{ $customer->status === 'banned' ? 'selected' : '' }}>Khóa tài khoản (Banned)</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary btn-sm">Lưu Thay Đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
