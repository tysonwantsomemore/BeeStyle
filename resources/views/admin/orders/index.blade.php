@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng & Vận Chuyển | BeeStyle Admin')

@push('styles')
<style>
  .sticky-table-header th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #f1f5f9 !important;
    color: #0f172a !important;
    font-weight: 800 !important;
    font-size: 0.78rem !important;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 2px solid #cbd5e1 !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    padding-top: 12px;
    padding-bottom: 12px;
  }
  .orders-table-wrapper {
    max-height: 720px;
    overflow-y: auto;
    position: relative;
  }
</style>
@endpush

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-3 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">GIAO DỊCH &amp; VẬN CHUYỂN</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Đơn Hàng</h2>
    </div>
    <p class="text-body-tertiary mb-0">Quản lý danh sách đơn hàng, vận chuyển, đối soát và trạng thái giao nhận</p>
  </div>
  <div class="col-auto d-flex align-items-center gap-2 flex-wrap">
    <a href="{{ route('shipper.orders.index') }}" target="_blank" class="btn btn-phoenix-info btn-sm fw-bold d-inline-flex align-items-center gap-1.5 shadow-xs px-3">
      <i class="fa-solid fa-motorcycle me-1 text-info"></i> Cổng Bưu Tá (Shipper Portal)
    </a>
    <a href="{{ route('admin.orders.statistics') }}" class="btn btn-phoenix-primary btn-sm fw-bold d-inline-flex align-items-center gap-1.5 shadow-xs px-3">
      <i class="fa-solid fa-chart-pie me-1 text-primary"></i> Báo Cáo &amp; Thống Kê Đơn Hàng
    </a>
    <a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-phoenix-secondary btn-sm fw-bold">
      <i class="fa-solid fa-file-excel me-1 text-success"></i> Xuất Excel / CSV
    </a>
  </div>
</div>

<!-- STATUS FILTER TABS (CHUẨN TMĐT CHUYÊN NGHIỆP) -->
<div class="card border-0 shadow-xs mb-3 bg-white" style="border-radius: 14px;">
  <div class="card-body p-2 d-flex align-items-center gap-1 overflow-x-auto flex-nowrap" style="scrollbar-width: thin;">
    @php
      $currentTab = request('status', '');
      $baseQuery = request()->except('status', 'page');
    @endphp
    <a href="{{ route('admin.orders.index', $baseQuery) }}" class="btn btn-sm text-nowrap rounded-3 fw-bold px-3 {{ $currentTab === '' ? 'btn-dark' : 'btn-light text-secondary' }}">
      Tất Cả Đơn <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge($baseQuery, ['status' => 'pending'])) }}" class="btn btn-sm text-nowrap rounded-3 fw-bold px-3 {{ $currentTab === 'pending' ? 'btn-warning text-dark' : 'btn-light text-secondary' }}">
      <i class="fa-regular fa-clock me-1"></i> 1. Chờ Duyệt <span class="badge {{ ($statusCounts['pending'] ?? 0) > 0 ? 'bg-danger text-white' : 'bg-secondary text-white' }} ms-1">{{ $statusCounts['pending'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge($baseQuery, ['status' => 'confirmed'])) }}" class="btn btn-sm text-nowrap rounded-3 fw-bold px-3 {{ $currentTab === 'confirmed' ? 'btn-primary' : 'btn-light text-secondary' }}">
      <i class="fa-solid fa-clipboard-check me-1"></i> 2. Đã Xác Nhận <span class="badge bg-secondary ms-1">{{ $statusCounts['confirmed'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge($baseQuery, ['status' => 'processing'])) }}" class="btn btn-sm text-nowrap rounded-3 fw-bold px-3 {{ $currentTab === 'processing' ? 'btn-info text-white' : 'btn-light text-secondary' }}">
      <i class="fa-solid fa-boxes-packing me-1"></i> 3. Đang Đóng Gói <span class="badge bg-secondary ms-1">{{ $statusCounts['processing'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge($baseQuery, ['status' => 'shipping'])) }}" class="btn btn-sm text-nowrap rounded-3 fw-bold px-3 {{ $currentTab === 'shipping' ? 'btn-warning text-dark' : 'btn-light text-secondary' }}">
      <i class="fa-solid fa-truck-fast me-1"></i> 4. Đang Giao Hàng <span class="badge bg-secondary ms-1">{{ $statusCounts['shipping'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge($baseQuery, ['status' => 'delivered'])) }}" class="btn btn-sm text-nowrap rounded-3 fw-bold px-3 {{ $currentTab === 'delivered' ? 'btn-success' : 'btn-light text-secondary' }}">
      <i class="fa-solid fa-box-open me-1"></i> 5. Đã Giao Hàng <span class="badge bg-secondary ms-1">{{ $statusCounts['delivered'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge($baseQuery, ['status' => 'completed'])) }}" class="btn btn-sm text-nowrap rounded-3 fw-bold px-3 {{ $currentTab === 'completed' ? 'btn-success' : 'btn-light text-secondary' }}">
      <i class="fa-solid fa-circle-check me-1"></i> 6. Hoàn Tất <span class="badge bg-secondary ms-1">{{ $statusCounts['completed'] ?? 0 }}</span>
    </a>
    <a href="{{ route('admin.orders.index', array_merge($baseQuery, ['status' => 'cancelled'])) }}" class="btn btn-sm text-nowrap rounded-3 fw-bold px-3 {{ $currentTab === 'cancelled' ? 'btn-danger' : 'btn-light text-secondary' }}">
      <i class="fa-solid fa-ban me-1"></i> Đã Hủy <span class="badge bg-secondary ms-1">{{ $statusCounts['cancelled'] ?? 0 }}</span>
    </a>
  </div>
</div>

<!-- ADVANCED MULTI-CRITERIA FILTERS BAR (BỘ LỌC ĐA TẦNG CHUYÊN NGHIỆP) -->
<div class="card border-0 shadow-xs mb-3 bg-white" style="border-radius: 14px;">
  <div class="card-body p-3">
    <form action="{{ route('admin.orders.index') }}" method="GET" id="filterForm">
      <input type="hidden" name="status" value="{{ request('status') }}">
      
      <div class="row g-2 align-items-center">
        <!-- Tìm kiếm từ khóa -->
        <div class="col-lg-3 col-md-6">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Mã đơn, vận đơn, Tên KH, SĐT...">
          </div>
        </div>

        <!-- Tình trạng in phiếu đóng gói -->
        <div class="col-lg-2 col-md-3 col-6">
          <select name="print_status" class="form-select form-select-sm">
            <option value="">-- Phiếu Đóng Gói --</option>
            <option value="printed" {{ request('print_status') === 'printed' ? 'selected' : '' }}>Đã in phiếu</option>
            <option value="unprinted" {{ request('print_status') === 'unprinted' ? 'selected' : '' }}>Chưa in phiếu</option>
          </select>
        </div>

        <!-- Bưu tá giao hàng -->
        <div class="col-lg-2 col-md-3 col-6">
          <select name="shipper_id" class="form-select form-select-sm">
            <option value="">-- Bưu Tá Phụ Trách --</option>
            @foreach($shippers as $shp)
              <option value="{{ $shp->id }}" {{ request('shipper_id') == $shp->id ? 'selected' : '' }}>
                {{ $shp->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Khoảng thời gian -->
        <div class="col-lg-2 col-md-3 col-6">
          <select name="date_preset" class="form-select form-select-sm" onchange="toggleCustomDate(this.value)">
            <option value="">-- Mốc Thời Gian --</option>
            <option value="today" {{ request('date_preset') === 'today' ? 'selected' : '' }}>Hôm nay</option>
            <option value="yesterday" {{ request('date_preset') === 'yesterday' ? 'selected' : '' }}>Hôm qua</option>
            <option value="7days" {{ request('date_preset') === '7days' ? 'selected' : '' }}>7 ngày gần nhất</option>
            <option value="30days" {{ request('date_preset') === '30days' ? 'selected' : '' }}>30 ngày gần nhất</option>
            <option value="this_month" {{ request('date_preset') === 'this_month' ? 'selected' : '' }}>Tháng này</option>
            <option value="custom" {{ (request('date_preset') === 'custom' || request('date_from') || request('date_to')) ? 'selected' : '' }}>Tùy chọn ngày...</option>
          </select>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="col-lg-2 col-md-3 col-6">
          <select name="payment_method" class="form-select form-select-sm">
            <option value="">-- Kênh Thanh Toán --</option>
            <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>Tiền mặt (COD)</option>
            <option value="momo" {{ request('payment_method') === 'momo' ? 'selected' : '' }}>Ví MoMo</option>
            <option value="zalopay" {{ request('payment_method') === 'zalopay' ? 'selected' : '' }}>Ví ZaloPay</option>
            <option value="online" {{ request('payment_method') === 'online' ? 'selected' : '' }}>Online Banking</option>
            <option value="vietqr" {{ request('payment_method') === 'vietqr' ? 'selected' : '' }}>VietQR</option>
          </select>
        </div>

        <!-- Nút áp dụng & Xóa bộ lọc -->
        <div class="col-lg-1 col-md-4 d-flex align-items-center gap-1">
          <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">
            Lọc
          </button>
          @if(request('q') || request('payment_method') || request('payment_status') || request('date_preset') || request('date_from') || request('date_to') || request('carrier') || request('print_status') || request('shipper_id'))
            <a href="{{ route('admin.orders.index', ['status' => request('status')]) }}" class="btn btn-sm btn-outline-danger px-2" title="Xóa tất cả tiêu chí lọc">
              <i class="fa-solid fa-rotate-left"></i>
            </a>
          @endif
        </div>
      </div>

      <!-- Hàng phụ: Tùy chọn ngày cụ thể (Date Range) -->
      <div id="customDateRow" class="row g-2 mt-1 {{ (request('date_preset') === 'custom' || request('date_from') || request('date_to')) ? '' : 'd-none' }}">
        <div class="col-md-3 offset-lg-3 col-6">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted">Từ</span>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light text-muted">Đến</span>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- FLOATING BULK ACTIONS TOOLBAR (THANH THAO TÁC HÀNG LOẠT) -->
<div id="bulkActionBar" class="card border-0 shadow-lg p-3 mb-3 bg-dark text-white rounded-4 d-none transition-all" style="position: sticky; top: 15px; z-index: 1020;">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <span class="badge bg-warning text-dark fw-black fs-6 px-3 py-1.5 rounded-pill" id="selectedCountBadge">0</span>
      <span class="fw-bold">đơn hàng đang được chọn đồng thời</span>
    </div>
    
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <button type="button" class="btn btn-sm btn-light text-dark fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkPrint()">
        <i class="fa-solid fa-print text-primary"></i> In Phiếu Đóng Gói Hàng Loạt
      </button>
      <button type="button" class="btn btn-sm btn-primary fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('confirm')">
        <i class="fa-solid fa-check"></i> Duyệt Đơn
      </button>
      <button type="button" class="btn btn-sm btn-warning text-dark fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('processing')">
        <i class="fa-solid fa-box-open"></i> Kho Đóng Gói
      </button>
      <button type="button" class="btn btn-sm btn-info text-white fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('shipping')">
        <i class="fa-solid fa-truck-fast"></i> Giao Bưu Tá
      </button>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('delivered')">
        <i class="fa-solid fa-handshake"></i> Đã Giao Hàng
      </button>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('completed')">
        <i class="fa-solid fa-circle-check"></i> Hoàn Tất Đơn
      </button>
      <button type="button" class="btn btn-sm btn-link text-white text-opacity-75 text-decoration-none" onclick="deselectAll()">
        Bỏ chọn
      </button>
    </div>
  </div>
</div>

<!-- FORM ẨN XỬ LÝ BULK ACTIONS & IN HÀNG LOẠT -->
<form id="bulkActionForm" action="{{ route('admin.orders.bulkAction') }}" method="POST" class="d-none">
  @csrf
  <input type="hidden" name="action" id="bulkActionInput" value="">
  <div id="bulkOrderIdsContainer"></div>
</form>

<form id="bulkPrintForm" action="{{ route('admin.orders.bulkPrint') }}" method="POST" target="_blank" class="d-none">
  @csrf
  <div id="bulkPrintIdsContainer"></div>
</form>

<div class="card border-0 shadow-sm mb-4">
  <!-- SEARCH SUMMARY HEADER -->
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-3 py-2.5">
    <div class="d-flex align-items-center gap-2">
      <span class="badge bg-light text-dark border fw-semibold">
        Hiển thị <strong>{{ $orders->count() }}</strong> / <strong>{{ $orders->total() }}</strong> đơn hàng
      </span>
      @if(request('status'))
        <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase">
          Tab: {{ request('status') }}
        </span>
      @endif
    </div>
    <div class="text-body-tertiary fs-10">
      Tổng số: <strong class="text-body-emphasis">{{ $orders->total() }}</strong> đơn hàng
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive orders-table-wrapper scrollbar">
      <table class="table align-middle mb-0 fs-9">
        <thead class="sticky-table-header">
          <tr>
            <th style="width: 42px;" class="text-center ps-3">
              <input type="checkbox" id="selectAllCheckbox" class="form-check-input" onchange="toggleSelectAll(this)" title="Chọn tất cả đơn trên trang này">
            </th>
            <th style="width: 38%; min-width: 310px;">Thông Tin Giao Hàng</th>
            <th style="width: 18%; min-width: 170px;">Sản Phẩm</th>
            <th style="width: 16%; min-width: 150px;">Tổng Tiền &amp; Thanh Toán</th>
            <th style="width: 14%; min-width: 140px;">Vận Chuyển</th>
            <th style="width: 14%; min-width: 140px;" class="text-end pe-3">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($orders as $order)
            <tr id="row_order_{{ $order->id }}" class="border-bottom border-translucent">
              <!-- CHECKBOX CHỌN ĐỒNG BỘ -->
              <td class="text-center ps-3 align-top pt-3">
                <input type="checkbox" class="form-check-input order-item-checkbox" value="{{ $order->id }}" onchange="handleItemCheckboxChange(this)">
              </td>

              <!-- CỘT GỘP THÔNG TIN GIAO HÀNG (MÃ ĐƠN, TRẠNG THÁI, THỜI GIAN, TÀI KHOẢN & NGƯỜI NHẬN / ĐỊA CHỈ) -->
              <td class="align-top py-3">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                  <a href="{{ route('admin.orders.show', $order->id) }}" class="font-monospace fw-bold fs-9 text-primary text-decoration-none">
                    #{{ $order->order_code }}
                  </a>
                  @php
                    $statusBadgeClass = match($order->shipping_status) {
                      'completed' => 'bg-success text-white',
                      'delivered' => 'bg-success-subtle text-success border border-success-subtle',
                      'shipping' => 'bg-info-subtle text-info border border-info-subtle',
                      'processing' => 'bg-warning-subtle text-dark border border-warning-subtle',
                      'confirmed' => 'bg-secondary-subtle text-dark border border-secondary-subtle',
                      'cancelled' => 'bg-danger-subtle text-danger border border-danger-subtle',
                      default => 'bg-warning text-dark',
                    };
                  @endphp
                  <span class="badge {{ $statusBadgeClass }} fw-bold" style="font-size: 0.72rem;">
                    {{ $order->shipping_status_label }}
                  </span>
                  @if($order->is_deposit_required)
                    <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.65rem;" title="Đơn hàng yêu cầu đặt cọc 50%">
                      <i class="fa-solid fa-hand-holding-dollar me-0.5"></i> CỌC 50%
                    </span>
                  @endif

                  <!-- HUY HIỆU ĐÃ IN PHIẾU ĐÓNG GÓI -->
                  @if($order->is_printed)
                    <span class="badge bg-purple-subtle text-purple border border-purple-subtle fw-bold" style="font-size: 0.68rem;" title="Đã in lúc: {{ $order->printed_at ? $order->printed_at->format('d/m/Y H:i') : '' }}">
                      <i class="fa-solid fa-print me-0.5"></i> ĐÃ IN PHIẾU (x{{ $order->print_count }})
                    </span>
                  @else
                    <span class="badge bg-secondary-subtle text-muted fw-normal" style="font-size: 0.68rem;" title="Chưa in phiếu đóng gói">
                      <i class="fa-solid fa-print me-0.5"></i> Chưa In Phiếu
                    </span>
                  @endif

                  <!-- HUY HIỆU BƯU TÁ -->
                  @if($order->shipper)
                    <span class="badge bg-info-subtle text-info border border-info-subtle fw-bold" style="font-size: 0.68rem;" title="Bưu tá: {{ $order->shipper->name }} ({{ $order->shipper->phone }})">
                      <i class="fa-solid fa-motorcycle me-0.5"></i> {{ \Illuminate\Support\Str::limit($order->shipper->name, 16) }}
                    </span>
                  @endif
                </div>

                <!-- THỜI GIAN TẠO -->
                <div class="text-muted fs-10 mb-1.5">
                  <i class="fa-regular fa-clock me-1 text-secondary"></i> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}
                  <span class="text-secondary">({{ $order->created_at ? $order->created_at->diffForHumans() : '' }})</span>
                </div>

                <!-- TÀI KHOẢN ĐẶT HÀNG -->
                <div class="d-flex align-items-center gap-1.5 mb-2 fs-10">
                  <span class="text-muted"><i class="fa-regular fa-user me-1"></i>Tài khoản:</span>
                  @if($order->user)
                    <a href="{{ route('admin.customers.show', $order->user->id) }}" class="fw-bold text-dark text-decoration-none">
                      {{ $order->user->name }}
                    </a>
                    <span class="badge badge-phoenix badge-phoenix-primary fs-11 ms-1">
                      Thành viên #{{ $order->user->id }}
                    </span>
                  @else
                    <span class="badge bg-light text-muted border fs-11">
                      <i class="fa-solid fa-user-slash me-1"></i> Khách Vãng Lai
                    </span>
                  @endif
                </div>

                <!-- NGƯỜI NHẬN & ĐỊA CHỈ GIAO HÀNG (BOX TINH GỌN, DỄ ĐỌC) -->
                <div class="p-2.5 rounded-3 bg-light border border-translucent fs-10">
                  <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                    <span class="fw-bold text-dark">
                      <i class="fa-solid fa-user-check text-success me-1"></i>{{ $order->customer_name }}
                    </span>
                    <a href="tel:{{ $order->customer_phone }}" class="fw-bold text-primary font-monospace text-decoration-none">
                      <i class="fa-solid fa-phone text-success me-1"></i>{{ $order->customer_phone }}
                    </a>
                  </div>
                  <div class="text-secondary lh-sm">
                    <i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $order->shipping_address }}
                  </div>
                  @if($order->customer_notes)
                    <div class="text-muted fst-italic mt-1 pt-1 border-top border-translucent">
                      <i class="fa-regular fa-comment-dots me-1"></i>{{ $order->customer_notes }}
                    </div>
                  @endif
                  @if($order->cancel_reason && $order->shipping_status === 'cancelled')
                    <div class="text-danger small mt-1 pt-1 border-top border-danger-subtle">
                      <i class="fa-solid fa-ban me-1"></i>Lý do hủy: <strong>{{ $order->cancel_reason }}</strong>
                      @if($order->cancelled_at)
                        <span class="font-monospace">({{ $order->cancelled_at->format('d/m/Y H:i') }})</span>
                      @endif
                    </div>
                  @endif
                </div>
              </td>

              <!-- CỘT SẢN PHẨM -->
              <td class="align-top py-3">
                <span class="badge bg-light text-dark border px-2 py-1 fw-bold mb-1.5 d-inline-block">
                  {{ $order->items->count() }} mẫu ({{ $order->items->sum('quantity') }} cái)
                </span>
                <div class="d-flex flex-column gap-1.5 mt-1">
                  @foreach($order->items->take(2) as $item)
                    <div class="d-flex align-items-center gap-1.5 fs-10">
                      <span class="badge bg-secondary-subtle text-dark px-1.5 py-0.5 font-monospace fw-bold">x{{ $item->quantity }}</span>
                      <span class="text-truncate text-dark fw-semibold" style="max-width: 140px;" title="{{ $item->product_name }}">
                        {{ $item->product_name }}
                      </span>
                      @if($item->size || $item->color)
                        <small class="text-muted">({{ $item->color ?: '-' }} / {{ $item->size ?: '-' }})</small>
                      @endif
                    </div>
                  @endforeach
                  @if($order->items->count() > 2)
                    <small class="text-muted fst-italic">+{{ $order->items->count() - 2 }} sản phẩm khác</small>
                  @endif
                </div>
              </td>

              <!-- CỘT TỔNG TIỀN & THANH TOÁN -->
              <td class="align-top py-3">
                <div class="text-danger font-monospace fw-black fs-8 mb-1">
                  {{ number_format($order->total_amount, 0, ',', '.') }}₫
                </div>
                @if($order->is_deposit_required)
                  <div class="mb-1">
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle d-inline-flex align-items-center gap-1" style="font-size: 0.68rem;" title="Đơn hàng yêu cầu cọc 50%">
                      <i class="fa-solid fa-shield-halved text-warning"></i> Cọc: {{ number_format($order->deposit_amount, 0, ',', '.') }}₫
                    </span>
                    <div class="text-muted mt-0.5" style="font-size: 0.68rem;">
                      Còn lại: {{ number_format($order->remaining_amount ?: ($order->total_amount - $order->deposit_amount), 0, ',', '.') }}₫
                    </div>
                  </div>
                @endif

                <div class="mt-1">
                  <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-dark border border-warning-subtle' }} py-1 px-2 fw-bold d-inline-block text-nowrap mb-1">
                    {{ $order->payment_status_label }}
                  </span>
                  <small class="text-muted text-truncate d-block" style="max-width: 130px; font-size: 0.72rem;" title="{{ $order->payment_method_name }}">
                    {{ $order->payment_method_name }}
                  </small>
                </div>
              </td>

              <!-- CỘT ĐƠN VỊ VẬN CHUYỂN -->
              <td class="align-top py-3">
                @if($order->tracking_code)
                  @php
                    $carrierLower = mb_strtolower((string)$order->shipping_carrier, 'UTF-8');
                    $isGhtk = str_contains($carrierLower, 'ghtk') || str_contains($carrierLower, 'tiết kiệm');
                    $isGhn = str_contains($carrierLower, 'ghn') || str_contains($carrierLower, 'nhanh');
                    $isViettel = str_contains($carrierLower, 'viettel') || str_contains($carrierLower, 'vtp');
                    $isJt = str_contains($carrierLower, 'j&t') || str_contains($carrierLower, 'jt');
                    $carrierBadgeClass = $isGhtk ? 'bg-success-subtle text-success border-success-subtle' : ($isGhn ? 'bg-warning-subtle text-dark border-warning-subtle' : ($isViettel ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-info-subtle text-info border-info-subtle'));
                    $carrierShort = $isGhtk ? 'GHTK' : ($isGhn ? 'GHN' : ($isViettel ? 'Viettel Post' : ($isJt ? 'J&T' : ($order->shipping_carrier ?: 'GHTK'))));
                  @endphp
                  <div class="d-flex flex-column gap-1">
                    <span class="badge {{ $carrierBadgeClass }} border fw-bold text-nowrap" style="font-size: 0.72rem;">
                      <i class="fa-solid fa-truck-fast me-1"></i> {{ $carrierShort }}
                    </span>
                    @if($order->tracking_url)
                      <a href="{{ $order->tracking_url }}" target="_blank" class="badge bg-light text-dark border text-decoration-none font-monospace fw-bold py-1 px-1.5 text-truncate d-inline-flex align-items-center justify-content-between gap-1 shadow-2xs" style="max-width: 130px; font-size: 0.72rem;" title="Tra cứu hành trình vận đơn {{ $order->tracking_code }}">
                        <span>{{ $order->tracking_code }}</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-primary" style="font-size: 0.65rem;"></i>
                      </a>
                    @else
                      <span class="font-monospace fw-bold text-dark small d-block" style="font-size: 0.72rem;">
                        {{ $order->tracking_code }}
                      </span>
                    @endif

                    @if($order->shipper)
                      <small class="text-primary fw-semibold" style="font-size: 0.7rem;">
                        <i class="fa-solid fa-motorcycle me-0.5"></i> {{ $order->shipper->name }}
                      </small>
                    @endif

                    @if($order->handover_image)
                      <button type="button" class="btn btn-link btn-xs p-0 text-decoration-none text-muted text-start" style="font-size: 0.68rem;" data-bs-toggle="modal" data-bs-target="#viewHandoverModal{{ $order->id }}">
                        <i class="fa-solid fa-box-check text-info me-0.5"></i> Ảnh xuất kho
                      </button>
                    @endif

                    <!-- DỰ KIẾN GIAO HÀNG (EDD) -->
                    @if($order->estimated_delivery_text && !in_array($order->shipping_status, ['cancelled', 'completed', 'delivered']))
                      <div class="text-primary fw-semibold mt-0.5" style="font-size: 0.68rem;" title="Thời gian ước tính giao hàng">
                        <i class="fa-solid fa-business-time me-0.5"></i> {{ $order->estimated_delivery_text }}
                      </div>
                    @endif

                    @if($order->delivered_at && $order->shipping_status === 'delivered')
                      @php
                        $daysAgo = (int)$order->delivered_at->diffInDays(now());
                        $remDays = max(0, 7 - $daysAgo);
                      @endphp
                      <span class="badge bg-success-subtle text-success border border-success-subtle mt-1 text-wrap text-start lh-sm" style="font-size: 0.65rem;">
                        <i class="fa-solid fa-clock-rotate-left me-0.5"></i> Đã giao {{ $daysAgo }} ngày (Tự hoàn tất sau {{ $remDays }} ngày)
                      </span>
                    @endif
                  </div>
                @elseif(in_array($order->shipping_status, ['processing', 'confirmed']))
                  <div class="d-flex flex-column gap-1">
                    <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.72rem;">
                      <i class="fa-solid fa-box-open me-1 text-warning"></i> Chờ bưu tá
                    </span>
                    @if($order->estimated_delivery_text)
                      <span class="text-primary fw-semibold" style="font-size: 0.68rem;">
                        <i class="fa-solid fa-business-time me-0.5"></i> {{ $order->estimated_delivery_text }}
                      </span>
                    @endif
                  </div>
                @elseif($order->shipping_status === 'pending')
                  <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">Chưa duyệt</span>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              <!-- CỘT THAO TÁC (CHUYỂN BƯỚC TỪNG BƯỚC 1 CHUẨN XÁC) -->
              <td class="text-end pe-3 align-top py-3">
                <div class="d-flex flex-column align-items-end gap-1.5">
                  <!-- NÚT CHUYỂN BƯỚC TUẦN TỰ -->
                  @if($order->shipping_status === 'pending')
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline w-100 text-end">
                      @csrf
                      <input type="hidden" name="shipping_status" value="confirmed">
                      <button type="submit" class="btn btn-sm btn-primary fw-bold py-1 px-2.5 shadow-xs w-100" style="font-size: 0.75rem;" title="Xác nhận ngay đơn hàng này">
                        <i class="fa-solid fa-check me-1"></i> Duyệt Đơn
                      </button>
                    </form>
                  @elseif($order->shipping_status === 'confirmed')
                    <!-- BƯỚC 2 -> 3: IN PHIẾU ĐÓNG GÓI & TỰ ĐỘNG CHUYỂN SANG PROCESSING -->
                    <a href="{{ route('admin.orders.printSlip', $order->id) }}" target="_blank" class="btn btn-sm text-white fw-bold py-1 px-2.5 shadow-xs w-100 text-center" style="background-color: #7c3aed; font-size: 0.75rem;" title="In phiếu đóng gói và tự động chuyển sang Đang Đóng Gói">
                      <i class="fa-solid fa-print me-1"></i> In &amp; Đóng Gói
                    </a>
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline w-100 text-end mt-1">
                      @csrf
                      <input type="hidden" name="shipping_status" value="processing">
                      <button type="submit" class="btn btn-xs btn-outline-warning text-dark fw-bold py-0.5 px-2 w-100" style="font-size: 0.7rem;" title="Chuyển kho đóng gói không cần in">
                        <i class="fa-solid fa-box-open me-1"></i> Kho Gói
                      </button>
                    </form>
                  @elseif($order->shipping_status === 'processing')
                    <!-- BƯỚC 3 -> 4: ĐÓNG GÓI XONG -> TỰ ĐỘNG CHUYỂN SANG BƯU TÁ -->
                    <form action="{{ route('admin.orders.finishPacking', $order->id) }}" method="POST" class="d-inline w-100 text-end">
                      @csrf
                      <button type="submit" class="btn btn-sm text-white fw-bold py-1.5 px-2 shadow-xs w-100 d-flex align-items-center justify-content-center gap-1 text-nowrap" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); font-size: 0.73rem;" title="Bấm khi đóng gói xong: Tự động chuyển đơn sang cho Bưu tá giao hàng">
                        <i class="fa-solid fa-box-archive"></i>
                        <span>Đóng Gói Xong</span>
                        <i class="fa-solid fa-arrow-right fs-11"></i>
                      </button>
                    </form>
                    <div class="d-flex align-items-center gap-1 w-100 mt-1">
                      <button type="button" class="btn btn-xs btn-outline-info fw-bold py-0.5 px-1.5 flex-grow-1" style="font-size: 0.68rem;" data-bs-toggle="modal" data-bs-target="#handoverModal{{ $order->id }}" title="Chỉ định bưu tá hoặc tải ảnh kiện hàng xuất kho">
                        <i class="fa-solid fa-user-tag me-0.5"></i> Gán bưu tá
                      </button>
                      <a href="{{ route('admin.orders.printSlip', $order->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary py-0.5 px-1.5 text-center flex-grow-1" style="font-size: 0.68rem;" title="In lại phiếu đóng gói">
                        <i class="fa-solid fa-print me-0.5"></i> In lại
                      </a>
                    </div>
                  @elseif($order->shipping_status === 'shipping')
                    <!-- BƯỚC 4: ĐANG GIAO HÀNG -> DO BƯU TÁ XỬ LÝ TRÊN CỔNG BƯU TÁ -->
                    <div class="p-2 rounded-3 border border-info-subtle bg-info-subtle bg-opacity-30 text-start w-100 mb-1" style="font-size: 0.72rem;">
                      <div class="fw-bold text-primary d-flex align-items-center gap-1 mb-0.5">
                        <i class="fa-solid fa-motorcycle text-info"></i>
                        <span class="text-truncate" style="max-width: 120px;" title="{{ $order->shipper ? $order->shipper->name : 'BeeStyle Courier' }}">
                          {{ $order->shipper ? $order->shipper->name : 'BeeStyle Courier' }}
                        </span>
                      </div>
                      @if($order->shipper && $order->shipper->phone)
                        <div class="text-muted font-monospace fs-11">
                          <i class="fa-solid fa-phone fs-12 text-success me-0.5"></i> {{ $order->shipper->phone }}
                        </div>
                      @endif
                      <div class="text-secondary fs-11 mt-1 fst-italic">
                        <i class="fa-solid fa-spinner fa-spin me-0.5 text-primary"></i> Chờ shipper giao &amp; chụp POD
                      </div>
                    </div>
                  @elseif($order->shipping_status === 'delivered')
                    <!-- BƯỚC 5: ĐÃ GIAO HÀNG (TỰ ĐỘNG HOÀN TẤT SAU 7 NGÀY HOẶC BẤM HOÀN TẤT SỚM) -->
                    <div class="d-flex flex-column gap-1 w-100">
                      <div class="badge bg-success-subtle text-success border border-success-subtle py-1 px-1.5 text-start w-100" style="font-size: 0.7rem;">
                        <i class="fa-solid fa-circle-check me-0.5"></i> Shipper đã giao hàng
                      </div>
                      <div class="d-flex align-items-center gap-1 w-100">
                        <button type="button" class="btn btn-xs btn-outline-success fw-bold py-1 px-2 shadow-2xs flex-grow-1" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#viewPodModal{{ $order->id }}" title="Xem ảnh bưu tá chụp gửi về kho">
                          <i class="fa-solid fa-image me-0.5"></i> POD
                        </button>
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline flex-grow-1">
                          @csrf
                          <input type="hidden" name="shipping_status" value="completed">
                          <input type="hidden" name="payment_status" value="paid">
                          <button type="submit" class="btn btn-xs btn-success fw-bold py-1 px-1.5 shadow-xs w-100 text-nowrap" style="font-size: 0.72rem;" title="Hoàn tất đơn hàng sớm">
                            <i class="fa-solid fa-check-double me-0.5"></i> Hoàn Tất
                          </button>
                        </form>
                      </div>
                    </div>
                  @elseif($order->shipping_status === 'completed')
                    <!-- BƯỚC 6: HOÀN TẤT -->
                    <div class="d-flex align-items-center justify-content-end gap-1 w-100">
                      <button type="button" class="btn btn-xs btn-outline-success fw-bold py-1 px-2 shadow-2xs" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#viewPodModal{{ $order->id }}" title="Xem ảnh bưu tá chụp gửi về kho">
                        <i class="fa-solid fa-image me-1"></i> POD
                      </button>
                      <span class="badge bg-success-subtle text-success py-1 px-2 fw-semibold" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-check-double me-1"></i> Hoàn tất
                      </span>
                    </div>
                  @endif

                  <div class="d-flex align-items-center gap-1 w-100 justify-content-end flex-wrap">
                    <!-- NÚT CHI TIẾT -->
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-xs btn-outline-dark fw-bold py-1 px-2 fs-10 flex-grow-1 text-center">
                      Chi Tiết <i class="fa-solid fa-chevron-right ms-0.5"></i>
                    </a>

                    <!-- NÚT THU TIỀN / ĐÃ THANH TOÁN (TỪNG ĐƠN 1) -->
                    @if($order->payment_status === 'paid')
                      <button type="button" class="btn btn-xs btn-success fw-bold py-1 px-2 shadow-2xs opacity-75 pe-none text-nowrap" style="font-size: 0.72rem;" title="Đơn hàng này đã thanh toán đủ">
                        <i class="fa-solid fa-circle-check me-0.5"></i> Đã thanh toán
                      </button>
                    @elseif($order->shipping_status !== 'cancelled')
                      <form action="{{ route('admin.orders.markPaid', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận ĐÃ THU ĐỦ TIỀN cho đơn hàng #{{ $order->order_code }} ({{ number_format($order->total_amount, 0, ',', '.') }}₫)?');">
                        @csrf
                        <button type="submit" class="btn btn-xs btn-outline-success fw-bold py-1 px-2 shadow-2xs text-nowrap" style="font-size: 0.72rem;" title="Đánh dấu đã thu tiền đơn này">
                          <i class="fa-solid fa-money-bill-wave me-0.5"></i> Thu Tiền
                        </button>
                      </form>
                    @else
                      <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 opacity-50 pe-none text-nowrap" style="font-size: 0.72rem;" title="Đơn đã hủy, không thu tiền">
                        <i class="fa-solid fa-ban me-0.5"></i> Chưa thu
                      </button>
                    @endif

                    <!-- NÚT HỦY ĐƠN (TỪNG ĐƠN 1) -->
                    @if(in_array($order->shipping_status, ['pending', 'confirmed', 'processing', 'shipping']))
                      <button type="button" class="btn btn-xs btn-outline-danger fw-bold py-1 px-1.5 shadow-2xs text-nowrap" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->id }}" title="Hủy đơn hàng này">
                        <i class="fa-solid fa-xmark me-0.5"></i> Hủy
                      </button>
                    @elseif($order->shipping_status === 'cancelled')
                      <button type="button" class="btn btn-xs btn-danger fw-bold py-1 px-1.5 shadow-2xs opacity-75 pe-none text-nowrap" style="font-size: 0.72rem;" title="Đơn hàng này đã bị hủy">
                        <i class="fa-solid fa-ban me-0.5"></i> Đã hủy
                      </button>
                    @else
                      <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-1.5 opacity-50 pe-none text-nowrap" style="font-size: 0.72rem;" title="Đơn hàng đã giao hoặc hoàn tất, không thể hủy">
                        <i class="fa-solid fa-xmark me-0.5"></i> Hủy
                      </button>
                    @endif
                  </div>
                </div>
              </td>
            </tr>

            <!-- MODAL HỦY TỪNG ĐƠN HÀNG RIÊNG BIỆT (ADMIN CHUẨN TMĐT) -->
            <div class="modal fade" id="cancelOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
                <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
                  <div class="modal-header bg-danger-subtle text-danger py-3 px-4 border-bottom border-danger-subtle">
                    <div class="d-flex align-items-center gap-2">
                      <div class="bg-danger text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                        <i class="fa-solid fa-triangle-exclamation fs-6"></i>
                      </div>
                      <div>
                        <h6 class="modal-title fw-bold text-danger mb-0">
                          Hủy Đơn Hàng #{{ $order->order_code }}
                        </h6>
                        <small class="text-danger-emphasis fs-11">Xác nhận thao tác hủy đơn từ Quản trị viên</small>
                      </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('admin.orders.cancelSingle', $order->id) }}" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='<span class=\'spinner-border spinner-border-sm me-1\'></span> Đang xử lý...';">
                    @csrf
                    <div class="modal-body p-4 text-start">
                      <!-- Tóm tắt đơn hàng cần hủy -->
                      <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span class="text-secondary small">Khách hàng:</span>
                          <strong class="text-dark small">{{ $order->customer_name }} @if($order->phone) - {{ $order->phone }} @endif</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <span class="text-secondary small">Giá trị đơn hàng:</span>
                          <strong class="text-danger fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="text-secondary small">Thanh toán:</span>
                          <span class="small">
                            {{ $order->payment_method_name ?? $order->payment_method }}
                            @if($order->payment_status === 'paid')
                              <span class="badge bg-success-subtle text-success border border-success-subtle ms-1">Đã thanh toán (Sẽ hoàn tiền)</span>
                            @else
                              <span class="badge bg-secondary-subtle text-secondary ms-1">Chưa thu tiền</span>
                            @endif
                          </span>
                        </div>
                      </div>

                      <!-- Cảnh báo hoàn kho -->
                      <div class="alert alert-warning py-2 px-3 small border-0 mb-3 d-flex align-items-start gap-2">
                        <i class="fa-solid fa-circle-info text-warning mt-1"></i>
                        <div class="text-dark">
                          Khi xác nhận hủy, hệ thống sẽ <strong>tự động hoàn lại số lượng tồn kho</strong> cho toàn bộ sản phẩm và <strong>khôi phục mã voucher</strong> (nếu có).
                        </div>
                      </div>

                      <!-- 1. Lý do hủy đơn -->
                      <div class="mb-3">
                        <label class="form-label small fw-bold text-dark mb-1">
                          1. Lý do hủy đơn hàng <span class="text-danger">*</span>
                        </label>
                        <select name="reason" class="form-select form-select-sm" required>
                          <option value="" disabled selected>-- Chọn lý do hủy từ Shop / Vận hành --</option>
                          <option value="Khách hàng liên hệ yêu cầu hủy đơn">Khách hàng liên hệ yêu cầu hủy đơn</option>
                          <option value="Hết hàng tồn kho / Đứt mẫu vải sản xuất">Hết hàng tồn kho / Đứt mẫu vải sản xuất</option>
                          <option value="Không thể liên lạc với khách để xác nhận (Nhiều lần không nghe máy)">Không thể liên lạc với khách để xác nhận (Nhiều lần không nghe máy)</option>
                          <option value="Khách hàng muốn thay đổi địa chỉ nhận hàng">Khách hàng muốn thay đổi địa chỉ nhận hàng</option>
                          <option value="Khách hàng muốn đổi Size / Màu sắc sản phẩm">Khách hàng muốn đổi Size / Màu sắc sản phẩm</option>
                          <option value="Đơn hàng nghi ngờ đặt ảo / Trùng lặp đơn">Đơn hàng nghi ngờ đặt ảo / Trùng lặp đơn</option>
                          <option value="Khách hàng không đồng ý phí giao hàng hoặc thời gian giao">Khách hàng không đồng ý phí giao hàng hoặc thời gian giao</option>
                          <option value="Lý do vận hành / Kỹ thuật khác">Lý do vận hành / Kỹ thuật khác</option>
                        </select>
                      </div>

                      <!-- 2. Ghi chú nội bộ -->
                      <div class="mb-2">
                        <label class="form-label small fw-semibold text-secondary mb-1">
                          2. Ghi chú chi tiết nội bộ (Không bắt buộc)
                        </label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Nhập thêm chi tiết ghi chú cho bộ phận CSKH/Kho (Ví dụ: Khách hẹn sang tuần đặt lại, gọi 3 lần không liên lạc được...)"></textarea>
                      </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-between">
                      <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">
                        Giữ Lại Đơn Hàng
                      </button>
                      <button type="submit" class="btn btn-danger btn-sm fw-bold px-3 shadow-xs">
                        <i class="fa-solid fa-ban me-1"></i> Xác Nhận Hủy Đơn
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- MODAL BÀN GIAO CHO BƯU TÁ (BƯỚC 4 - BẮT BUỘC 1 ẢNH KIỆN HÀNG) -->
            <div class="modal fade" id="handoverModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
                  <div class="modal-header bg-info text-white py-3 px-4">
                    <h5 class="modal-title fw-bold text-white mb-0 fs-6">
                      <i class="fa-solid fa-truck-fast me-2"></i> Bàn Giao Bưu Tá Vận Chuyển (#{{ $order->order_code }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('admin.orders.handoverShipper', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4 text-start">
                      <div class="alert alert-info-subtle border border-info-subtle py-2 px-3 rounded-3 small mb-3 text-dark">
                        <i class="fa-solid fa-circle-info text-info me-1"></i> Bàn giao kiện hàng từ kho đóng gói cho bưu tá. <strong>Bắt buộc tải lên 1 ảnh kiện hàng xuất kho</strong> để chuyển sang <strong>Bước 4: Đang Giao Hàng</strong>.
                      </div>

                      <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">
                          <i class="fa-solid fa-motorcycle text-primary me-1"></i> Chọn Bưu Tá Phụ Trách <span class="text-danger">*</span>:
                        </label>
                        <select name="shipper_id" class="form-select form-select-sm" required>
                          <option value="">-- Chọn bưu tá nhận đơn giao --</option>
                          @foreach($shippers as $shp)
                            <option value="{{ $shp->id }}" {{ $order->shipper_id == $shp->id ? 'selected' : '' }}>
                              {{ $shp->name }} ({{ $shp->phone ?: $shp->email }})
                            </option>
                          @endforeach
                        </select>
                        @if($shippers->isEmpty())
                          <small class="text-danger d-block mt-1">Chưa có tài khoản bưu tá. Hệ thống đã tạo sẵn tài khoản <strong>shipper1@beestyle.vn</strong>.</small>
                        @endif
                      </div>

                      <div class="row g-2 mb-3">
                        <div class="col-md-6">
                          <label class="form-label small fw-semibold text-dark">Đơn Vị Vận Chuyển:</label>
                          <select name="shipping_carrier" class="form-select form-select-sm" id="carrierSelect{{ $order->id }}" onchange="generateOrderTracking('{{ $order->id }}', this.value)">
                            <option value="BeeStyle Express" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'BeeStyle')) ? 'selected' : '' }}>BeeStyle Express (Nội Bộ)</option>
                            <option value="Giao Hàng Tiết Kiệm (GHTK)" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'GHTK')) ? 'selected' : '' }}>Giao Hàng Tiết Kiệm (GHTK)</option>
                            <option value="Giao Hàng Nhanh (GHN)" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'GHN')) ? 'selected' : '' }}>Giao Hàng Nhanh (GHN)</option>
                            <option value="Viettel Post" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'Viettel')) ? 'selected' : '' }}>Viettel Post</option>
                            <option value="J&T Express" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'J&T')) ? 'selected' : '' }}>J&T Express</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label small fw-semibold text-dark">Mã Vận Đơn (Tracking):</label>
                          <div class="input-group input-group-sm">
                            <input type="text" name="tracking_code" id="trackingCodeInput{{ $order->id }}" class="form-control font-monospace fw-bold text-primary" value="{{ $order->tracking_code ?: 'BEE-' . strtoupper(\Illuminate\Support\Str::random(8)) }}" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="generateRandomOrderTracking('{{ $order->id }}')" title="Tạo mã ngẫu nhiên">
                              <i class="fa-solid fa-arrows-rotate"></i>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">
                          <i class="fa-solid fa-camera text-primary me-1"></i> Chụp / Tải 1 Ảnh Kiện Hàng Xuất Kho <span class="text-danger">*</span>:
                        </label>
                        <input type="file" name="handover_image_file" class="form-control form-control-sm" accept="image/*" id="handoverFileInput{{ $order->id }}" onchange="previewHandoverImg(this, '{{ $order->id }}')">
                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Ảnh chụp kiện hàng đã dán tem mã vận đơn trước khi đưa cho shipper.</small>
                      </div>

                      <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted d-block">Xem trước ảnh xuất kho:</label>
                        <div class="rounded-3 border bg-light text-center p-2 position-relative overflow-hidden" style="min-height: 140px; max-height: 200px;">
                          <img id="handoverPreviewImg{{ $order->id }}" src="{{ $order->handover_image_url ?: asset('assets/img/delivery-proofs/sample_pod_1.jpg') }}" alt="Handover Preview" class="img-fluid rounded-2 object-fit-contain" style="max-height: 175px; width: auto;">
                          <input type="hidden" name="handover_image" id="handoverSampleInput{{ $order->id }}" value="{{ $order->handover_image ?: 'assets/img/delivery-proofs/sample_pod_1.jpg' }}">
                        </div>
                      </div>

                      <div class="mb-2">
                        <label class="form-label small fw-semibold text-muted d-block">Hoặc chọn nhanh ảnh mẫu đóng gói tại kho:</label>
                        <div class="d-flex gap-2">
                          <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 rounded-pill fw-bold small flex-grow-1" onclick="useSampleHandover('{{ $order->id }}', 'assets/img/delivery-proofs/sample_pod_1.jpg')">
                            <i class="fa-solid fa-box text-warning me-1"></i> Kiện Hàng Niêm Phong
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 rounded-pill fw-bold small flex-grow-1" onclick="useSampleHandover('{{ $order->id }}', 'assets/img/delivery-proofs/sample_pod_2.jpg')">
                            <i class="fa-solid fa-signature text-primary me-1"></i> Phiếu Bàn Giao Kho
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-between">
                      <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                      <button type="submit" class="btn btn-info text-white btn-sm rounded-pill px-4 fw-bold shadow-xs">
                        <i class="fa-solid fa-paper-plane me-1"></i> Bàn Giao Vận Chuyển
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- MODAL XEM ẢNH KIỆN HÀNG XUẤT KHO (VIEW HANDOVER IMAGE) -->
            @if($order->handover_image)
            <div class="modal fade" id="viewHandoverModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
                  <div class="modal-header bg-dark text-white py-3 px-4">
                    <h5 class="modal-title fw-bold mb-0 fs-6">
                      <i class="fa-solid fa-box-open text-info me-2"></i> Ảnh Kiện Hàng Xuất Kho Bàn Giao Bưu Tá - Đơn #{{ $order->order_code }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body p-4 bg-light text-start">
                    <div class="row g-3 align-items-center">
                      <div class="col-lg-7 text-center">
                        <div class="p-2 bg-white rounded-3 border shadow-xs d-inline-block w-100">
                          <img src="{{ $order->handover_image_url }}" alt="Handover Proof" class="img-fluid rounded-2 shadow-2xs" style="max-height: 380px; width: auto; object-fit: contain;">
                        </div>
                        <div class="mt-2 text-center">
                          <a href="{{ $order->handover_image_url }}" target="_blank" class="btn btn-xs btn-outline-dark fw-bold rounded-pill px-3 py-1">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Mở Ảnh Kích Thước Gốc
                          </a>
                        </div>
                      </div>
                      <div class="col-lg-5">
                        <div class="card border-0 shadow-xs p-3.5 bg-white rounded-3 h-100">
                          <span class="badge bg-info-subtle text-info border border-info-subtle fw-bold mb-2 py-1.5 px-2.5 rounded-pill align-self-start">
                            <i class="fa-solid fa-box-check me-1"></i> ĐÃ XÁC THỰC XUẤT KHO
                          </span>
                          <h6 class="fw-bold text-dark mb-1">Kiện Hàng Đã Bàn Giao Cho Bưu Tá</h6>
                          <p class="text-muted small mb-2">Ảnh chụp kiện hàng xuất kho trước khi bưu tá đi phát hàng.</p>
                          <div class="small d-flex flex-column gap-2 border-top pt-2 mt-1">
                            <div>
                              <span class="text-muted">Bưu tá phụ trách:</span>
                              <strong class="d-block text-primary">{{ $order->shipper ? $order->shipper->name : 'BeeStyle Courier' }}</strong>
                            </div>
                            <div>
                              <span class="text-muted">Đơn vị vận chuyển:</span>
                              <strong class="d-block text-dark">{{ $order->shipping_carrier ?: 'N/A' }}</strong>
                            </div>
                            <div>
                              <span class="text-muted">Mã vận đơn:</span>
                              <strong class="d-block font-monospace text-primary">{{ $order->tracking_code ?: 'N/A' }}</strong>
                            </div>
                            <div>
                              <span class="text-muted">Thời gian xuất kho:</span>
                              <strong class="d-block font-monospace text-dark">{{ $order->shipping_at ? $order->shipping_at->format('d/m/Y H:i:s') : 'N/A' }}</strong>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer bg-white border-top py-2.5 px-4">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                  </div>
                </div>
              </div>
            </div>
            @endif

            <!-- MODAL XÁC NHẬN GIAO HÀNG & LƯU ẢNH POD (BƯỚC 5) -->
            <div class="modal fade" id="podModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
                  <div class="modal-header bg-success text-white py-3 px-4">
                    <h5 class="modal-title fw-bold mb-0 fs-6">
                      <i class="fa-solid fa-camera me-2"></i> Xác Nhận Giao Hàng &amp; Lưu Ảnh POD (#{{ $order->order_code }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="shipping_status" value="delivered">
                    
                    <div class="modal-body p-4 text-start">
                      <div class="alert alert-success-subtle text-success border border-success-subtle py-2 px-3 rounded-3 small mb-3">
                        <i class="fa-solid fa-circle-check me-1"></i> Bưu tá xác nhận đã chuyển kiện hàng thành công tới khách hàng: <strong>{{ $order->customer_name }}</strong> ({{ $order->customer_phone }}).
                      </div>

                      <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">
                          <i class="fa-solid fa-image text-primary me-1"></i> Tải Lên Ảnh Bưu Tá Chụp Xác Nhận <span class="text-danger">*</span>:
                        </label>
                        <input type="file" name="delivery_proof_file" class="form-control form-control-sm" accept="image/*" id="podFileInput{{ $order->id }}" onchange="previewPodImage(this, '{{ $order->id }}')">
                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Định dạng: JPG, PNG, WEBP (Tối đa 10MB). Ảnh chụp kiện hàng trước cửa, trên tay khách hoặc biên bản ký nhận.</small>
                      </div>

                      <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted d-block">Xem trước ảnh xác nhận giao hàng:</label>
                        <div class="rounded-3 border bg-light text-center p-2 position-relative overflow-hidden" style="min-height: 160px; max-height: 220px;">
                          <img id="podPreviewImg{{ $order->id }}" src="{{ $order->delivery_proof_url ?: asset('assets/img/delivery-proofs/sample_pod_1.jpg') }}" alt="POD Preview" class="img-fluid rounded-2 object-fit-contain" style="max-height: 190px; width: auto;">
                          <input type="hidden" name="delivery_proof_image" id="podSampleInput{{ $order->id }}" value="{{ $order->delivery_proof_image ?: 'assets/img/delivery-proofs/sample_pod_1.jpg' }}">
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted d-block">Hoặc chọn nhanh ảnh mẫu:</label>
                        <div class="d-flex gap-2">
                          <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-pill fw-bold small flex-grow-1" onclick="selectSamplePod('{{ $order->id }}', 'assets/img/delivery-proofs/sample_pod_1.jpg')">
                            <i class="fa-solid fa-box text-warning me-1"></i> Kiện Hàng &amp; Dấu POD
                          </button>
                          <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-pill fw-bold small flex-grow-1" onclick="selectSamplePod('{{ $order->id }}', 'assets/img/delivery-proofs/sample_pod_2.jpg')">
                            <i class="fa-solid fa-signature text-primary me-1"></i> Biên Bản Ký Nhận
                          </button>
                        </div>
                      </div>

                      <div class="mb-2">
                        <label class="form-label small fw-bold text-dark">Ghi chú bưu tá gửi về kho:</label>
                        <textarea name="delivery_proof_note" class="form-control form-control-sm" rows="2" placeholder="Ghi chú chi tiết người nhận, vị trí đặt kiện hàng...">Bưu tá xác nhận đã trao kiện hàng tận tay người nhận: {{ $order->customer_name }} tại địa chỉ {{ $order->shipping_address }}, kiểm tra niêm phong nguyên vẹn.</textarea>
                      </div>
                    </div>

                    <div class="modal-footer bg-light border-top py-2 px-4 d-flex justify-content-between">
                      <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                      <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-xs">
                        <i class="fa-solid fa-check-double me-1"></i> Lưu Ảnh Về Kho &amp; Đã Giao Hàng
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- MODAL XEM ẢNH BẰNG CHỨNG GIAO HÀNG (VIEW POD) -->
            <div class="modal fade" id="viewPodModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
                  <div class="modal-header bg-dark text-white py-3 px-4">
                    <h5 class="modal-title fw-bold mb-0 fs-6">
                      <i class="fa-solid fa-image text-warning me-2"></i> Bằng Chứng Giao Hàng (POD) - Đơn #{{ $order->order_code }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body p-4 bg-light text-start">
                    <div class="row g-3 align-items-center">
                      <div class="col-lg-7 text-center">
                        <div class="p-2 bg-white rounded-3 border shadow-xs d-inline-block w-100">
                          <img src="{{ $order->delivery_proof_url }}" alt="Proof of delivery" class="img-fluid rounded-2 shadow-2xs" style="max-height: 380px; width: auto; object-fit: contain;">
                        </div>
                        <div class="mt-2 text-center">
                          <a href="{{ $order->delivery_proof_url }}" target="_blank" class="btn btn-xs btn-outline-dark fw-bold rounded-pill px-3 py-1">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Mở Ảnh Kích Thước Gốc
                          </a>
                        </div>
                      </div>
                      <div class="col-lg-5">
                        <div class="card border-0 shadow-xs p-3.5 bg-white rounded-3 h-100">
                          <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold mb-2 py-1.5 px-2.5 rounded-pill align-self-start">
                            <i class="fa-solid fa-circle-check me-1"></i> ĐÃ LƯU BẰNG CHỨNG TẠI KHO
                          </span>
                          <h6 class="fw-bold text-dark mb-1">Xác Thực Bưu Tá Bưu Cục</h6>
                          <p class="text-muted small mb-2">Ảnh chụp xác nhận khi kiện hàng được trao tới khách hàng.</p>
                          
                          <div class="small d-flex flex-column gap-2 border-top pt-2 mt-1">
                            <div>
                              <span class="text-muted">Đơn vị vận chuyển:</span>
                              <strong class="d-block text-dark">{{ $order->shipping_carrier ?: 'Giao Hàng Tiết Kiệm (GHTK)' }}</strong>
                            </div>
                            <div>
                              <span class="text-muted">Mã vận đơn:</span>
                              <strong class="d-block font-monospace text-primary">{{ $order->tracking_code ?: 'N/A' }}</strong>
                            </div>
                            <div>
                              <span class="text-muted">Thời gian ghi nhận:</span>
                              <strong class="d-block font-monospace text-dark">{{ $order->delivery_proof_at ? $order->delivery_proof_at->format('d/m/Y H:i:s') : ($order->delivered_at ? $order->delivered_at->format('d/m/Y H:i:s') : now()->format('d/m/Y H:i:s')) }}</strong>
                            </div>
                            <div>
                              <span class="text-muted">Ghi chú bưu tá:</span>
                              <p class="mb-0 text-dark fw-medium small p-2 bg-light rounded-2 border mt-1">
                                {{ $order->delivery_proof_note ?: 'Bưu tá xác nhận đã trao kiện hàng tận tay khách hàng thành công.' }}
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="modal-footer bg-white border-top py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#podModal{{ $order->id }}">
                      <i class="fa-solid fa-camera-rotate me-1"></i> Cập Nhật / Đổi Ảnh Khác
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Đóng</button>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <tr>
              <td colspan="6" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-cart-shopping fs-4 text-body-tertiary mb-2 d-block"></i>
                Không tìm thấy đơn hàng nào phù hợp với bộ lọc.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 px-4 bg-white border-top border-translucent">
    <div class="text-muted fs-10">
      Hiển thị <strong>{{ $orders->firstItem() ?? 0 }}</strong> - <strong>{{ $orders->lastItem() ?? 0 }}</strong> trên tổng số <strong>{{ $orders->total() }}</strong> đơn hàng (Trang {{ $orders->currentPage() }}/{{ $orders->lastPage() }})
    </div>
    <div class="pagination-container">
      {{ $orders->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

@push('scripts')
<script>
  function toggleCustomDate(preset) {
    const row = document.getElementById('customDateRow');
    if (!row) return;
    if (preset === 'custom') {
      row.classList.remove('d-none');
    } else {
      row.classList.add('d-none');
    }
  }

  function toggleSelectAll(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.order-item-checkbox');
    checkboxes.forEach(cb => {
      cb.checked = masterCheckbox.checked;
      const row = document.getElementById('row_order_' + cb.value);
      if (row) {
        if (masterCheckbox.checked) {
          row.classList.add('table-warning');
        } else {
          row.classList.remove('table-warning');
        }
      }
    });
    updateSelectedState();
  }

  function handleItemCheckboxChange(cb) {
    const row = document.getElementById('row_order_' + cb.value);
    if (row) {
      if (cb.checked) {
        row.classList.add('table-warning');
      } else {
        row.classList.remove('table-warning');
      }
    }

    const allCheckboxes = document.querySelectorAll('.order-item-checkbox');
    const checkedCount = document.querySelectorAll('.order-item-checkbox:checked').length;
    const master = document.getElementById('selectAllCheckbox');
    if (master) {
      master.checked = (allCheckboxes.length > 0 && checkedCount === allCheckboxes.length);
    }

    updateSelectedState();
  }

  function updateSelectedState() {
    const checkedBoxes = document.querySelectorAll('.order-item-checkbox:checked');
    const count = checkedBoxes.length;
    const bar = document.getElementById('bulkActionBar');
    const countBadge = document.getElementById('selectedCountBadge');

    if (!bar || !countBadge) return;

    if (count > 0) {
      bar.classList.remove('d-none');
      countBadge.textContent = count;
    } else {
      bar.classList.add('d-none');
      countBadge.textContent = '0';
    }
  }

  function deselectAll() {
    const master = document.getElementById('selectAllCheckbox');
    if (master) master.checked = false;
    toggleSelectAll({ checked: false });
  }

  function submitBulkPrint() {
    const checkedBoxes = document.querySelectorAll('.order-item-checkbox:checked');
    if (checkedBoxes.length === 0) {
      alert('Vui lòng chọn ít nhất một đơn hàng để in phiếu đóng gói!');
      return;
    }

    const container = document.getElementById('bulkPrintIdsContainer');
    container.innerHTML = '';
    checkedBoxes.forEach(cb => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'order_ids[]';
      input.value = cb.value;
      container.appendChild(input);
    });

    document.getElementById('bulkPrintForm').submit();
  }

  function submitBulkAction(actionType) {
    const checkedBoxes = document.querySelectorAll('.order-item-checkbox:checked');
    if (checkedBoxes.length === 0) {
      alert('Vui lòng chọn ít nhất một đơn hàng để thực hiện!');
      return;
    }

    const actionTextMap = {
      'confirm': 'DUYỆT ĐƠN HÀNG',
      'processing': 'CHUYỂN KHO ĐÓNG GÓI',
      'shipping': 'BÀN GIAO CHO BƯU TÁ VẬN CHUYỂN',
      'delivered': 'GIAO HÀNG THÀNH CÔNG',
      'completed': 'HOÀN TẤT ĐƠN HÀNG'
    };

    const actionName = actionTextMap[actionType] || actionType;
    if (!confirm(`Bạn có chắc chắn muốn thực hiện "${actionName}" đồng bộ cho ${checkedBoxes.length} đơn hàng đã chọn?`)) {
      return;
    }

    const container = document.getElementById('bulkOrderIdsContainer');
    container.innerHTML = '';
    checkedBoxes.forEach(cb => {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'order_ids[]';
      input.value = cb.value;
      container.appendChild(input);
    });

    document.getElementById('bulkActionInput').value = actionType;
    document.getElementById('bulkActionForm').submit();
  }

  function generateOrderTracking(orderId, carrier) {
    let prefix = 'GHTK';
    if (carrier.includes('GHN')) prefix = 'GHN';
    else if (carrier.includes('Viettel')) prefix = 'VTP';
    else if (carrier.includes('J&T')) prefix = 'JT';
    else if (carrier.includes('Ninja')) prefix = 'NJV';
    else if (carrier.includes('Nội Bộ')) prefix = 'BEE';

    const randomStr = Math.random().toString(36).substring(2, 10).toUpperCase();
    const input = document.getElementById('trackingCodeInput' + orderId);
    if (input) {
      input.value = prefix + '-' + randomStr;
    }
  }

  function generateRandomOrderTracking(orderId) {
    const select = document.getElementById('carrierSelect' + orderId);
    if (select) {
      generateOrderTracking(orderId, select.value);
    }
  }

  function previewHandoverImg(input, orderId) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.getElementById('handoverPreviewImg' + orderId);
        if (preview) {
          preview.src = e.target.result;
        }
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function useSampleHandover(orderId, imgPath) {
    const preview = document.getElementById('handoverPreviewImg' + orderId);
    const hiddenInput = document.getElementById('handoverSampleInput' + orderId);
    const fileInput = document.getElementById('handoverFileInput' + orderId);
    if (preview) {
      preview.src = '{{ asset("") }}' + imgPath;
    }
    if (hiddenInput) {
      hiddenInput.value = imgPath;
    }
    if (fileInput) {
      fileInput.value = '';
    }
  }

  function previewPodImage(input, orderId) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.getElementById('podPreviewImg' + orderId);
        if (preview) {
          preview.src = e.target.result;
        }
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function selectSamplePod(orderId, imgPath) {
    const preview = document.getElementById('podPreviewImg' + orderId);
    const hiddenInput = document.getElementById('podSampleInput' + orderId);
    const fileInput = document.getElementById('podFileInput' + orderId);
    if (preview) {
      preview.src = '{{ asset("") }}' + imgPath;
    }
    if (hiddenInput) {
      hiddenInput.value = imgPath;
    }
    if (fileInput) {
      fileInput.value = '';
    }
  }
</script>
@endpush
@endsection