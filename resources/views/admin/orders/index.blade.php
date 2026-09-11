@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng & Vận Chuyển | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">GIAO DỊCH &amp; VẬN CHUYỂN</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Đơn Hàng</h2>
    </div>
    <p class="text-body-tertiary mb-0">Theo dõi tiến trình xử lý, tài khoản đặt hàng, đóng gói, vận chuyển và đối soát doanh thu</p>
  </div>
</div>

<!-- FULFILLMENT KPI METRICS CARDS (EXECUTIVE DASHBOARD) -->
<div class="row g-3 mb-3">
  <!-- Thẻ 1: Tổng đơn hàng -->
  <div class="col-xl-3 col-md-6 col-12">
    <div class="card border-0 shadow-xs h-100 bg-white p-3" style="border-radius: 16px; border-left: 4px solid #3b82f6 !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Tổng Đơn Hàng</span>
          <h3 class="fw-black text-dark mb-0 mt-1 font-monospace">{{ number_format($statusCounts['all'] ?? 0) }}</h3>
          <small class="text-muted" style="font-size: 0.72rem;">Toàn bộ giao dịch hệ thống</small>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 48px; height: 48px; font-size: 1.25rem;">
          <i class="fa-solid fa-boxes-stacked"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Thẻ 2: Cần Xử Lý Ngay (Chờ duyệt + Đóng gói) -->
  @php
    $actionRequiredCount = ($statusCounts['pending'] ?? 0) + ($statusCounts['confirmed'] ?? 0) + ($statusCounts['processing'] ?? 0);
  @endphp
  <div class="col-xl-3 col-md-6 col-12">
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="card border-0 shadow-xs h-100 bg-white p-3 text-decoration-none hover-shadow transition-all" style="border-radius: 16px; border-left: 4px solid #f59e0b !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="d-flex align-items-center gap-1.5">
            <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Cần Xử Lý Ngay</span>
            @if(($statusCounts['pending'] ?? 0) > 0)
              <span class="badge bg-danger text-white rounded-pill" style="font-size: 0.65rem;">MỚI</span>
            @endif
          </div>
          <h3 class="fw-black text-warning mb-0 mt-1 font-monospace">{{ number_format($actionRequiredCount) }}</h3>
          <small class="text-muted" style="font-size: 0.72rem;">
            Chờ duyệt: <strong>{{ $statusCounts['pending'] ?? 0 }}</strong> • Gói hàng: <strong>{{ $statusCounts['processing'] ?? 0 }}</strong>
          </small>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning" style="width: 48px; height: 48px; font-size: 1.25rem;">
          <i class="fa-solid fa-bell"></i>
        </div>
      </div>
    </a>
  </div>

  <!-- Thẻ 3: Đang Luân Chuyển Bưu Tá -->
  <div class="col-xl-3 col-md-6 col-12">
    <a href="{{ route('admin.orders.index', ['status' => 'shipping']) }}" class="card border-0 shadow-xs h-100 bg-white p-3 text-decoration-none hover-shadow transition-all" style="border-radius: 16px; border-left: 4px solid #0284c7 !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Đang Vận Chuyển</span>
          <h3 class="fw-black text-info mb-0 mt-1 font-monospace">{{ number_format($statusCounts['shipping'] ?? 0) }}</h3>
          <small class="text-muted" style="font-size: 0.72rem;">Kiện hàng trên đường bưu tá giao</small>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-info-subtle text-info" style="width: 48px; height: 48px; font-size: 1.25rem;">
          <i class="fa-solid fa-truck-fast"></i>
        </div>
      </div>
    </a>
  </div>

  <!-- Thẻ 4: Giao Thành Công & Hoàn Tất -->
  @php
    $successCount = ($statusCounts['delivered'] ?? 0) + ($statusCounts['completed'] ?? 0);
  @endphp
  <div class="col-xl-3 col-md-6 col-12">
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="card border-0 shadow-xs h-100 bg-white p-3 text-decoration-none hover-shadow transition-all" style="border-radius: 16px; border-left: 4px solid #10b981 !important;">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Giao Thành Công</span>
          <h3 class="fw-black text-success mb-0 mt-1 font-monospace">{{ number_format($successCount) }}</h3>
          <small class="text-muted" style="font-size: 0.72rem;">
            Đã giao: <strong>{{ $statusCounts['delivered'] ?? 0 }}</strong> • Hoàn tất: <strong>{{ $statusCounts['completed'] ?? 0 }}</strong>
          </small>
        </div>
        <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 48px; height: 48px; font-size: 1.25rem;">
          <i class="fa-solid fa-circle-check"></i>
        </div>
      </div>
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
      <i class="fa-regular fa-clock me-1"></i> 1. Chờ Xác Nhận <span class="badge {{ ($statusCounts['pending'] ?? 0) > 0 ? 'bg-danger text-white' : 'bg-secondary text-white' }} ms-1">{{ $statusCounts['pending'] ?? 0 }}</span>
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
      
      <div class="row g-2.5 align-items-center">
        <!-- Tìm kiếm từ khóa -->
        <div class="col-lg-3 col-md-6">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Mã đơn, vận đơn, Tên KH, SĐT...">
          </div>
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

        <!-- Trạng thái thanh toán -->
        <div class="col-lg-2 col-md-4 col-6">
          <select name="payment_status" class="form-select form-select-sm">
            <option value="">-- Trạng Thái Tiền --</option>
            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thu tiền (Paid)</option>
            <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Chưa thu (Unpaid)</option>
            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền (Refunded)</option>
          </select>
        </div>

        <!-- Đơn vị vận chuyển -->
        <div class="col-lg-2 col-md-4 col-6">
          <select name="carrier" class="form-select form-select-sm">
            <option value="">-- Đơn Vị Vận Chuyển --</option>
            <option value="GHTK" {{ str_contains((string)request('carrier'), 'GHTK') ? 'selected' : '' }}>GHTK</option>
            <option value="GHN" {{ str_contains((string)request('carrier'), 'GHN') ? 'selected' : '' }}>GHN</option>
            <option value="Viettel" {{ str_contains((string)request('carrier'), 'Viettel') ? 'selected' : '' }}>Viettel Post</option>
            <option value="J&T" {{ str_contains((string)request('carrier'), 'J&T') ? 'selected' : '' }}>J&T Express</option>
            <option value="Ninja" {{ str_contains((string)request('carrier'), 'Ninja') ? 'selected' : '' }}>Ninja Van</option>
            <option value="Nội Bộ" {{ str_contains((string)request('carrier'), 'Nội Bộ') ? 'selected' : '' }}>Shipper Nội Bộ</option>
          </select>
        </div>

        <!-- Nút áp dụng & Xóa bộ lọc -->
        <div class="col-lg-1 col-md-4 d-flex align-items-center gap-1">
          <button type="submit" class="btn btn-sm btn-dark w-100 fw-bold">
            Lọc
          </button>
          @if(request('q') || request('payment_method') || request('payment_status') || request('date_preset') || request('date_from') || request('date_to') || request('carrier'))
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
        <i class="fa-solid fa-check"></i> Xác Nhận (Bước 2)
      </button>
      <button type="button" class="btn btn-sm btn-warning text-dark fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('processing')">
        <i class="fa-solid fa-box-open"></i> Kho Đóng Gói (Bước 3)
      </button>
      <button type="button" class="btn btn-sm btn-info text-white fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('shipping')">
        <i class="fa-solid fa-truck-fast"></i> Giao Bưu Tá (Bước 4)
      </button>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('delivered')">
        <i class="fa-solid fa-handshake"></i> Đã Giao (Bước 5)
      </button>
      <button type="button" class="btn btn-sm btn-success fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('completed')">
        <i class="fa-solid fa-circle-check"></i> Hoàn Tất (Bước 6)
      </button>
      <button type="button" class="btn btn-sm btn-outline-success text-white border-success fw-bold px-3 shadow-xs d-flex align-items-center gap-1.5" onclick="submitBulkAction('mark_paid')">
        <i class="fa-solid fa-money-bill-wave"></i> Đã Thu Tiền
      </button>
      <button type="button" class="btn btn-sm btn-outline-danger text-white border-danger px-2.5" onclick="submitBulkAction('cancel')">
        <i class="fa-solid fa-xmark"></i> Hủy Hàng Loạt
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
    <div class="table-responsive scrollbar">
      <table class="table align-middle mb-0 fs-9">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th style="width: 42px;" class="text-center ps-3">
              <input type="checkbox" id="selectAllCheckbox" class="form-check-input" onchange="toggleSelectAll(this)" title="Chọn tất cả đơn trên trang này">
            </th>
            <th>Mã Đơn Hàng</th>
            <th>Thời Gian Tạo</th>
            <th>Khách Hàng / Tài Khoản</th>
            <th>Người Nhận &amp; Địa Chỉ</th>
            <th>Vận Chuyển &amp; Vận Đơn</th>
            <th>Sản Phẩm</th>
            <th>Tổng Giá Trị</th>
            <th>Thanh Toán</th>
            <th>Tiến Trình Đơn Hàng</th>
            <th class="text-end pe-3">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($orders as $order)
            <tr id="row_order_{{ $order->id }}" class="border-bottom border-translucent">
              <!-- CHECKBOX CHỌN ĐỒNG BỘ -->
              <td class="text-center ps-3">
                <input type="checkbox" class="form-check-input order-item-checkbox" value="{{ $order->id }}" onchange="handleItemCheckboxChange(this)">
              </td>

              <td>
                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-monospace fw-bold text-primary text-decoration-none d-block">
                  #{{ $order->order_code }}
                </a>
                <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $order->id }}</small>
                @if($order->is_deposit_required)
                  <span class="badge bg-warning text-dark fw-bold d-block mt-1" style="font-size: 0.65rem; width: fit-content;" title="Đơn hàng yêu cầu đặt cọc 50%">
                    <i class="fa-solid fa-hand-holding-dollar me-0.5"></i> CỌC 50%
                  </span>
                @endif
              </td>

              <td>
                <small class="text-muted text-nowrap d-block fs-10">
                  <i class="fa-regular fa-clock me-0.5"></i> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}
                </small>
              </td>

              <!-- CỘT TÀI KHOẢN ĐẶT HÀNG -->
              <td>
                @if($order->user)
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-10 flex-shrink-0" style="width: 32px; height: 32px;">
                      {{ strtoupper(substr($order->user->name, 0, 1)) }}
                    </div>
                    <div>
                      <a href="{{ route('admin.customers.show', $order->user->id) }}" class="fw-bold text-body-emphasis text-decoration-none d-block fs-9">
                        {{ $order->user->name }}
                      </a>
                      <small class="text-body-tertiary d-block text-truncate fs-10" style="max-width: 140px;">
                        {{ $order->user->email }}
                      </small>
                      <span class="badge badge-phoenix badge-phoenix-primary fs-11">
                        Thành viên #{{ $order->user->id }}
                      </span>
                    </div>
                  </div>
                @else
                  <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-body-tertiary text-body-tertiary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                      <i class="fa-solid fa-user-slash fs-10"></i>
                    </div>
                    <div>
                      <span class="badge badge-phoenix badge-phoenix-secondary fs-10">
                        Khách Vãng Lai
                      </span>
                    </div>
                  </div>
                @endif
              </td>

              <!-- CỘT NGƯỜI NHẬN HÀNG -->
              <td>
                <div class="fw-bold text-body-emphasis fs-9">{{ $order->customer_name }}</div>
                <div class="text-body-tertiary fs-10">
                  <i class="fa-solid fa-phone me-1"></i>{{ $order->customer_phone }}
                </div>
                @if($order->shipping_address)
                  <small class="text-body-tertiary text-truncate d-block fs-10" style="max-width: 160px;" title="{{ $order->shipping_address }}">
                    <i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $order->shipping_address }}
                  </small>
                @endif
              </td>

              <!-- CỘT ĐƠN VỊ VẬN CHUYỂN & MÃ VẬN ĐƠN -->
              <td>
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
                      <a href="{{ $order->tracking_url }}" target="_blank" class="badge bg-light text-dark border text-decoration-none font-monospace fw-bold py-1 px-1.5 text-truncate d-inline-flex align-items-center justify-content-between gap-1 shadow-2xs" style="max-width: 140px; font-size: 0.72rem;" title="Tra cứu hành trình vận đơn {{ $order->tracking_code }}">
                        <span>{{ $order->tracking_code }}</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-primary" style="font-size: 0.65rem;"></i>
                      </a>
                    @else
                      <span class="font-monospace fw-bold text-dark small d-block" style="font-size: 0.72rem;">
                        {{ $order->tracking_code }}
                      </span>
                    @endif
                  </div>
                @elseif(in_array($order->shipping_status, ['processing', 'confirmed']))
                  <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-box-open me-1 text-warning"></i> Chờ bưu tá
                  </span>
                @elseif($order->shipping_status === 'pending')
                  <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">Chưa duyệt</span>
                @else
                  <span class="text-muted small">-</span>
                @endif
              </td>

              <!-- CỘT SẢN PHẨM -->
              <td>
                <span class="badge bg-light text-dark border px-2 py-1 fw-bold">
                  {{ $order->items->count() }} mẫu ({{ $order->items->sum('quantity') }} cái)
                </span>
              </td>

              <!-- CỘT GIÁ TRỊ -->
              <td>
                <strong class="text-danger font-monospace fs-6">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                @if($order->is_deposit_required)
                  <div class="mt-1">
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle d-inline-flex align-items-center gap-1" style="font-size: 0.68rem;" title="Đơn hàng yêu cầu cọc 50%">
                      <i class="fa-solid fa-shield-halved text-warning"></i> Cọc: {{ number_format($order->deposit_amount, 0, ',', '.') }}₫
                    </span>
                    <div class="text-muted" style="font-size: 0.68rem;">
                      Còn lại: {{ number_format($order->remaining_amount ?: ($order->total_amount - $order->deposit_amount), 0, ',', '.') }}₫
                    </div>
                  </div>
                @endif
              </td>

              <!-- CỘT THANH TOÁN -->
              <td>
                <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }} py-1 px-2 fw-bold d-block text-nowrap mb-1">
                  {{ $order->payment_status_label }}
                </span>
                <small class="text-muted text-truncate d-block" style="max-width: 120px; font-size: 0.7rem;" title="{{ $order->payment_method_name }}">
                  {{ $order->payment_method_name }}
                </small>
              </td>

              <!-- CỘT TIẾN TRÌNH ĐƠN HÀNG KÈM MỐC THỜI GIAN & NÚT XEM 6 BƯỚC -->
              <td>
                <div class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#orderProgressModal{{ $order->id }}" role="button" title="Bấm để xem chi tiết tiến trình 6 bước của đơn hàng #{{ $order->order_code }}">
                  @if($order->shipping_status === 'completed')
                    <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Bước 6/6: Hoàn tất</span>
                  @elseif($order->shipping_status === 'delivered')
                    <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-box-open me-1"></i> Bước 5/6: Đã giao</span>
                  @elseif($order->shipping_status === 'shipping')
                    <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-truck-fast me-1"></i> Bước 4/6: Đang giao</span>
                  @elseif($order->shipping_status === 'processing')
                    <span class="badge badge-phoenix badge-phoenix-info"><i class="fa-solid fa-boxes-packing me-1"></i> Bước 3/6: Đóng gói</span>
                  @elseif($order->shipping_status === 'confirmed')
                    <span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-clipboard-check me-1"></i> Bước 2/6: Đã xác nhận</span>
                  @elseif($order->shipping_status === 'cancelled')
                    @if(isset($order->cancelled_by) && $order->cancelled_by === 'customer_rejected')
                      <span class="badge bg-danger text-white py-1 px-2 fw-bold d-block text-nowrap" title="Khách từ chối nhận hàng khi bưu tá giao">
                        <i class="fa-solid fa-truck-arrow-right me-1"></i> Khách Không Nhận
                      </span>
                    @else
                      <span class="badge bg-danger-subtle text-danger py-1 px-2 fw-bold d-block text-nowrap">
                        <i class="fa-solid fa-ban me-1"></i> Đã hủy đơn
                      </span>
                    @endif
                    @if($order->cancel_reason)
                      <small class="text-body-tertiary d-block text-truncate fs-10 mt-0.5" style="max-width: 140px;" title="{{ $order->cancel_reason }}">
                        {{ $order->cancel_reason }}
                      </small>
                    @endif
                    @if($order->cancelled_at)
                      <small class="text-danger font-monospace d-block mt-0.5" style="font-size: 0.68rem;">{{ $order->cancelled_at->format('d/m/Y H:i') }}</small>
                    @endif
                  @else
                    <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-clock me-1"></i> Bước 1/6: Chờ duyệt</span>
                  @endif

                  @if(isset($order->latestReturn) && $order->latestReturn)
                    <div class="mt-1">
                      <a href="{{ route('admin.returns.show', $order->latestReturn->id) }}" class="badge badge-phoenix badge-phoenix-warning text-decoration-none fs-11">
                        <i class="fa-solid fa-arrow-rotate-left me-1"></i> RMA: {{ $order->latestReturn->status_label }}
                      </a>
                    </div>
                  @endif

                  <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fw-bold mt-1 d-inline-flex align-items-center gap-1 shadow-2xs" style="font-size: 0.7rem;">
                    <i class="fa-solid fa-timeline text-warning"></i> Xem 6 Bước
                  </button>
                </div>
              </td>

              <!-- CỘT THAO TÁC -->
              <td class="text-end pe-3 text-nowrap">
                <div class="d-flex align-items-center justify-content-end gap-1.5 flex-wrap">
                  <!-- NÚT 1-CHẠM THEO TIẾN TRÌNH -->
                  @if($order->shipping_status === 'pending')
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="shipping_status" value="confirmed">
                      <button type="submit" class="btn btn-sm btn-primary fw-bold py-1 px-2.5 shadow-xs" style="font-size: 0.75rem;" title="Xác nhận ngay đơn hàng này (Bước 2)">
                        <i class="fa-solid fa-check me-1"></i> Duyệt Đơn
                      </button>
                    </form>
                  @elseif($order->shipping_status === 'confirmed')
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="shipping_status" value="processing">
                      <button type="submit" class="btn btn-sm btn-warning text-dark fw-bold py-1 px-2.5 shadow-xs" style="font-size: 0.75rem;" title="Chuyển cho kho đóng gói (Bước 3)">
                        <i class="fa-solid fa-box-open me-1"></i> Kho Gói
                      </button>
                    </form>
                  @elseif($order->shipping_status === 'processing')
                    <button type="button" class="btn btn-sm btn-info text-white fw-bold py-1 px-2.5 shadow-xs" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#dispatchModal{{ $order->id }}" title="Bàn giao bưu tá vận chuyển (Bước 4)">
                      <i class="fa-solid fa-truck-fast me-1"></i> Giao Bưu Tá
                    </button>
                  @elseif($order->shipping_status === 'shipping')
                    <button type="button" class="btn btn-sm btn-success fw-bold py-1 px-2.5 shadow-xs" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#podModal{{ $order->id }}" title="Chụp / Upload ảnh bưu tá giao hàng gửi về kho (Bước 5)">
                      <i class="fa-solid fa-camera me-1"></i> Xác Nhận Giao (POD)
                    </button>
                  @elseif($order->shipping_status === 'delivered')
                    <div class="d-flex align-items-center gap-1">
                      <button type="button" class="btn btn-sm btn-outline-success fw-bold py-1 px-2 shadow-2xs" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#viewPodModal{{ $order->id }}" title="Xem ảnh bưu tá chụp gửi về kho">
                        <i class="fa-solid fa-image me-1"></i> Ảnh POD
                      </button>
                      <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="shipping_status" value="completed">
                        <input type="hidden" name="payment_status" value="paid">
                        <button type="submit" class="btn btn-sm btn-success fw-bold py-1 px-2.5 shadow-xs" style="font-size: 0.75rem;" title="Hoàn tất đơn hàng & tích điểm (Bước 6)">
                          <i class="fa-solid fa-circle-check me-1"></i> Hoàn Tất
                        </button>
                      </form>
                    </div>
                  @elseif($order->shipping_status === 'completed')
                    <div class="d-flex align-items-center gap-1">
                      <button type="button" class="btn btn-sm btn-outline-success fw-bold py-1 px-2 shadow-2xs" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#viewPodModal{{ $order->id }}" title="Xem ảnh bưu tá chụp gửi về kho">
                        <i class="fa-solid fa-image me-1"></i> Ảnh POD
                      </button>
                      <span class="badge bg-success-subtle text-success py-1 px-2 fw-semibold" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-circle-check me-1"></i> Hoàn tất
                      </span>
                    </div>
                  @endif

                  <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark py-1 px-2 fs-10 fw-bold">
                    Chi Tiết <i class="fa-solid fa-chevron-right ms-1"></i>
                  </a>
                </div>
              </td>
            </tr>

            <!-- MODAL XEM CHI TIẾT 6 BƯỚC TIẾN TRÌNH ĐƠN HÀNG -->
            <div class="modal fade" id="orderProgressModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderProgressModalLabel{{ $order->id }}" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-2xl rounded-4">
                  <div class="modal-header bg-light border-0 py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
                    <div>
                      <div class="d-flex align-items-center gap-2">
                        <h5 class="modal-title fw-bold text-dark mb-0" id="orderProgressModalLabel{{ $order->id }}">
                          <i class="fa-solid fa-truck-ramp-box text-warning me-2"></i> Tiến Trình 6 Bước Đơn Hàng #{{ $order->order_code }}
                        </h5>
                        <span class="badge {{ $order->shipping_status === 'completed' ? 'bg-success' : ($order->shipping_status === 'cancelled' ? 'bg-danger' : 'bg-primary') }} rounded-pill">
                          {{ $order->shipping_status_label }}
                        </span>
                      </div>
                      <small class="text-muted">
                        Khách: <strong>{{ $order->customer_name }}</strong> ({{ $order->customer_phone }}) • Tổng tiền: <strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong> ({{ $order->payment_status_label }})
                      </small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body p-4">
                    @php
                      $modalSteps = [
                        1 => [
                          'title' => '1. Chờ Xác Nhận',
                          'desc' => 'Đơn hàng mới tạo',
                          'icon' => 'fa-clipboard-list',
                          'time' => $order->created_at,
                        ],
                        2 => [
                          'title' => '2. Đã Xác Nhận',
                          'desc' => 'Đã duyệt thông tin',
                          'icon' => 'fa-clipboard-check',
                          'time' => $order->confirmed_at,
                        ],
                        3 => [
                          'title' => '3. Đang Đóng Gói',
                          'desc' => 'Kho nhặt hàng & gói',
                          'icon' => 'fa-box-open',
                          'time' => $order->processing_at,
                        ],
                        4 => [
                          'title' => '4. Đang Giao Hàng',
                          'desc' => 'Bưu tá vận chuyển',
                          'icon' => 'fa-truck-fast',
                          'time' => $order->shipping_at,
                          'carrier' => $order->shipping_carrier,
                          'tracking' => $order->tracking_code,
                          'tracking_url' => $order->tracking_url,
                        ],
                        5 => [
                          'title' => '5. Đã Giao Hàng',
                          'desc' => 'Khách nhận & kiểm tra',
                          'icon' => 'fa-handshake',
                          'time' => $order->delivered_at,
                        ],
                        6 => [
                          'title' => '6. Hoàn Tất',
                          'desc' => 'Thành công',
                          'icon' => 'fa-circle-check',
                          'time' => $order->completed_at,
                        ],
                      ];
                      $currentStepNum = $order->shipping_status === 'cancelled' ? 0 : ($order->status_step ?? 1);
                    @endphp

                    @if(method_exists($order, 'isCustomerRejected') && $order->isCustomerRejected())
                      <div class="alert alert-danger py-3 px-4 rounded-3 d-flex align-items-center gap-3 mb-3" style="background: #fff5f5; border: 1px solid #ef4444;">
                        <i class="fa-solid fa-truck-arrow-right fs-2 text-danger"></i>
                        <div>
                          <strong class="fs-6 d-block text-danger">KHÁCH TỪ CHỐI NHẬN HÀNG - ĐANG CHUYỂN HOÀN VỀ KHO</strong>
                          <span class="small text-danger text-opacity-80">Lý do khách phản ánh: <strong>{{ $order->cancel_reason ?: 'Không nhận bưu phẩm' }}</strong> • Thời gian: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i') : '' }}</span>
                        </div>
                      </div>
                    @elseif($order->shipping_status === 'cancelled')
                      <div class="alert alert-danger py-3 px-4 rounded-3 d-flex align-items-center gap-3 mb-3">
                        <i class="fa-solid fa-ban fs-2 text-danger"></i>
                        <div>
                          <strong class="fs-6 d-block">ĐƠN HÀNG ĐÃ BỊ HỦY (CANCELLED)</strong>
                          <span class="small text-danger text-opacity-80">Lý do: <strong>{{ $order->cancel_reason ?: 'Không có ghi chú' }}</strong> • Thời gian: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i') : ($order->updated_at ? $order->updated_at->format('d/m/Y H:i') : '') }}</span>
                        </div>
                      </div>
                    @endif

                    <!-- DANH SÁCH 6 BƯỚC -->
                    <div class="row g-3">
                      @foreach($modalSteps as $idx => $step)
                        @php
                          $isStepDone = ($currentStepNum >= $idx && $order->shipping_status !== 'cancelled');
                          $isStepActive = ($currentStepNum === $idx && $order->shipping_status !== 'cancelled');
                        @endphp
                        <div class="col-md-6 col-12">
                          <div class="p-3 rounded-4 border transition-all h-100 {{ $isStepActive ? 'border-warning bg-warning bg-opacity-10 shadow-xs' : ($isStepDone ? 'border-success-subtle bg-success bg-opacity-10' : 'border-light bg-light text-muted opacity-75') }}" style="border-width: 2px !important;">
                            <div class="d-flex align-items-start gap-3">
                              <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-2xs"
                                   style="width: 44px; height: 44px; font-size: 1.15rem;
                                          background-color: {{ $isStepActive ? '#f59e0b' : ($isStepDone ? '#10b981' : '#cbd5e1') }};
                                          color: #ffffff;">
                                <i class="fa-solid {{ $step['icon'] }}"></i>
                              </div>
                              <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                  <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">
                                    {{ $step['title'] }}
                                  </h6>
                                  @if($isStepActive)
                                    <span class="badge bg-warning text-dark fw-bold px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">Đang diễn ra</span>
                                  @elseif($isStepDone)
                                    <span class="badge bg-success text-white fw-bold px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;"><i class="fa-solid fa-check me-0.5"></i> Đã hoàn tất</span>
                                  @else
                                    <span class="badge bg-secondary-subtle text-muted fw-normal px-2 py-0.5 rounded-pill" style="font-size: 0.68rem;">Chờ xử lý</span>
                                  @endif
                                </div>
                                
                                <p class="mb-1 text-secondary fw-semibold small" style="font-size: 0.83rem;">
                                  {{ $step['desc'] }}
                                </p>

                                @if(!empty($step['carrier']) || !empty($step['tracking']))
                                  <div class="mb-1.5 small">
                                    @if(!empty($step['tracking_url']))
                                      <a href="{{ $step['tracking_url'] }}" target="_blank" rel="noopener noreferrer" class="badge bg-info-subtle text-primary border border-info-subtle font-monospace py-1 px-2 text-decoration-none shadow-2xs d-inline-flex align-items-center gap-1" style="font-size: 0.74rem;" title="Mở trang tra cứu bưu phẩm của hãng vận chuyển">
                                        <i class="fa-solid fa-truck-fast text-info"></i> {{ $step['carrier'] ?: 'GHTK' }}: <strong>{{ $step['tracking'] }}</strong>
                                        <i class="fa-solid fa-arrow-up-right-from-square ms-0.5 text-primary" style="font-size: 0.65rem;"></i>
                                      </a>
                                    @else
                                      <span class="badge bg-info-subtle text-info border border-info-subtle font-monospace py-0.5 px-1.5" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-truck-fast me-1"></i> {{ $step['carrier'] ?: 'GHTK' }}: {{ $step['tracking'] }}
                                      </span>
                                    @endif
                                  </div>
                                @endif

                                <div class="text-nowrap font-monospace fw-bold small" style="font-size: 0.78rem; color: {{ $isStepActive ? '#b45309' : ($isStepDone ? '#047857' : '#94a3b8') }};">
                                  @if(!empty($step['time']))
                                    <i class="fa-regular fa-clock me-1"></i> {{ $step['time']->format('d/m/Y H:i') }}
                                  @elseif($isStepDone)
                                    <i class="fa-regular fa-clock me-1"></i> Đã hoàn thành
                                  @else
                                    <i class="fa-regular fa-circle me-1"></i> Chưa diễn ra
                                  @endif
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>

                    <!-- QUICK ACTION CHUYỂN BƯỚC TRỰC TIẾP TRONG MODAL -->
                    @if($order->shipping_status !== 'cancelled' && $order->shipping_status !== 'completed')
                      <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span class="small text-muted fw-semibold">
                          <i class="fa-solid fa-bolt text-warning me-1"></i> Chuyển nhanh sang bước tiếp theo:
                        </span>
                        <div class="d-flex gap-2 flex-wrap">
                          @if($order->shipping_status === 'pending')
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                              @csrf
                              <input type="hidden" name="shipping_status" value="confirmed">
                              <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                                <i class="fa-solid fa-check me-1"></i> Duyệt Đơn (Bước 2: Đã Xác Nhận)
                              </button>
                            </form>
                          @elseif($order->shipping_status === 'confirmed')
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                              @csrf
                              <input type="hidden" name="shipping_status" value="processing">
                              <button type="submit" class="btn btn-warning text-dark btn-sm fw-bold px-3">
                                <i class="fa-solid fa-box-open me-1"></i> Chuyển Kho Gói (Bước 3: Đang Đóng Gói)
                              </button>
                            </form>
                          @elseif($order->shipping_status === 'processing')
                            <button type="button" class="btn btn-info text-white btn-sm fw-bold px-3" data-bs-toggle="modal" data-bs-target="#dispatchModal{{ $order->id }}">
                              <i class="fa-solid fa-truck-fast me-1"></i> Giao Bưu Tá (Bước 4: Đang Giao Hàng)
                            </button>
                          @elseif($order->shipping_status === 'shipping')
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                              @csrf
                              <input type="hidden" name="shipping_status" value="delivered">
                              <button type="submit" class="btn btn-success btn-sm fw-bold px-3">
                                <i class="fa-solid fa-handshake me-1"></i> Đã Giao Hàng (Bước 5: Khách Nhận)
                              </button>
                            </form>
                          @elseif($order->shipping_status === 'delivered')
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                              @csrf
                              <input type="hidden" name="shipping_status" value="completed">
                              <input type="hidden" name="payment_status" value="paid">
                              <button type="submit" class="btn btn-success btn-sm fw-bold px-3">
                                <i class="fa-solid fa-circle-check me-1"></i> Hoàn Tất Đơn Hàng (Bước 6)
                              </button>
                            </form>
                          @endif
                        </div>
                      </div>
                    @endif
                  </div>

                  <div class="modal-footer bg-light border-0 py-2.5 px-4 rounded-bottom-4 d-flex justify-content-between">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm fw-bold">
                      <i class="fa-solid fa-eye me-1"></i> Xem Toàn Bộ Chi Tiết &amp; Sản Phẩm
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- MODAL BÀN GIAO CHO BƯU TÁ (BƯỚC 4) -->
            <div class="modal fade" id="dispatchModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                <div class="modal-content border-0 shadow-2xl rounded-4">
                  <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark">
                      <i class="fa-solid fa-truck-fast text-info me-2"></i> Bàn Giao Bưu Tá (#{{ $order->order_code }})
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="shipping_status" value="shipping">
                    <div class="modal-body py-3">
                      <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Chọn Đơn Vị Vận Chuyển:</label>
                        <select name="shipping_carrier" class="form-select" required id="carrierSelect{{ $order->id }}" onchange="generateOrderTracking('{{ $order->id }}', this.value)">
                          <option value="Giao Hàng Tiết Kiệm (GHTK)" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'GHTK')) ? 'selected' : '' }}>Giao Hàng Tiết Kiệm (GHTK)</option>
                          <option value="Giao Hàng Nhanh (GHN)" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'GHN')) ? 'selected' : '' }}>Giao Hàng Nhanh (GHN)</option>
                          <option value="Viettel Post" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'Viettel')) ? 'selected' : '' }}>Viettel Post</option>
                          <option value="J&T Express" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'J&T')) ? 'selected' : '' }}>J&T Express</option>
                          <option value="Ninja Van" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'Ninja')) ? 'selected' : '' }}>Ninja Van</option>
                          <option value="Shipper Nội Bộ BeeStyle" {{ ($order->shipping_carrier && str_contains($order->shipping_carrier, 'Nội Bộ')) ? 'selected' : '' }}>Shipper Nội Bộ BeeStyle</option>
                        </select>
                      </div>

                      <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Mã Vận Đơn Bưu Tá (Tracking Code):</label>
                        <div class="input-group">
                          <input type="text" name="tracking_code" id="trackingCodeInput{{ $order->id }}" class="form-control font-monospace fw-bold text-primary" value="{{ $order->tracking_code ?: 'GHTK-' . strtoupper(\Illuminate\Support\Str::random(8)) }}" required>
                          <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generateRandomOrderTracking('{{ $order->id }}')">
                            <i class="fa-solid fa-arrows-rotate"></i> Tạo Mới
                          </button>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">Mã này được đồng bộ để người mua và quản trị viên tra cứu hành trình trực tiếp.</small>
                      </div>

                      <div class="alert alert-info py-2.5 px-3 rounded-3 small mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i> Sau khi bàn giao, đơn hàng chuyển sang <strong>"Bước 4: Đang Giao Hàng"</strong>.
                      </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                      <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                      <button type="submit" class="btn btn-info text-white fw-bold btn-sm px-4 shadow-xs">
                        <i class="fa-solid fa-paper-plane me-1"></i> Bàn Giao Vận Chuyển
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

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
              <td colspan="11" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-cart-shopping fs-4 text-body-tertiary mb-2 d-block"></i>
                Không tìm thấy đơn hàng nào phù hợp với bộ lọc.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($orders->hasPages())
    <div class="card-footer d-flex justify-content-center py-3 bg-body-emphasis border-top border-translucent">
      {{ $orders->links('pagination::bootstrap-5') }}
    </div>
  @endif
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
      'confirm': 'XÁC NHẬN (BƯỚC 2: ĐÃ XÁC NHẬN)',
      'processing': 'CHUYỂN KHO ĐÓNG GÓI (BƯỚC 3)',
      'shipping': 'BÀN GIAO CHO BƯU TÁ VẬN CHUYỂN (BƯỚC 4)',
      'delivered': 'GIAO HÀNG THÀNH CÔNG (BƯỚC 5)',
      'completed': 'HOÀN TẤT ĐƠN HÀNG (BƯỚC 6)',
      'mark_paid': 'ĐÁNH DẤU ĐÃ THU ĐỦ TIỀN',
      'cancel': 'HỦY ĐƠN HÀNG VÀ HOÀN LẠI TOÀN BỘ KHO HÀNG'
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