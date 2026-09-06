@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng & Vận Chuyển | BeeStyle Admin')

@section('content')
<!-- TOP TITLE & BATCH ACTION BANNER -->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">GIAO DỊCH &amp; FULFILLMENT</span>
        <h3 class="fw-bold text-dark mb-0">Quản Lý Đơn Hàng &amp; Vận Chuyển</h3>
      </div>
      <p class="text-muted small mb-0">Theo dõi tiến trình xử lý, đóng gói, bàn giao bưu tá, đối soát doanh thu và in phiếu bưu gửi đồng bộ</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
      <!-- NÚT XUẤT EXCEL / CSV ĐỐI SOÁT -->
      <a href="{{ route('admin.orders.export', request()->all()) }}" class="btn btn-outline-success fw-bold btn-sm px-3.5 py-2 rounded-3 shadow-xs d-flex align-items-center gap-2" title="Xuất dữ liệu đơn hàng ra file Excel / CSV chuẩn UTF-8">
        <i class="fa-solid fa-file-excel fs-6"></i>
        <span>Xuất Báo Cáo Excel</span>
      </a>

      <!-- NÚT TỰ ĐỘNG XÁC NHẬN TẤT CẢ ĐƠN CHỜ DUYỆT -->
      <form action="{{ route('admin.orders.confirmAllPending') }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn TỰ ĐỘNG XÁC NHẬN TẤT CẢ {{ $statusCounts['pending'] ?? 0 }} đơn hàng đang chờ duyệt không?')">
        @csrf
        <button type="submit" class="btn {{ ($statusCounts['pending'] ?? 0) > 0 ? 'btn-warning text-dark' : 'btn-outline-secondary' }} fw-bold btn-sm px-3.5 py-2 rounded-3 shadow-xs d-flex align-items-center gap-2">
          <i class="fa-solid fa-bolt"></i>
          <span>Xác Nhận Tất Cả Đơn Chờ</span>
          <span class="badge {{ ($statusCounts['pending'] ?? 0) > 0 ? 'bg-danger text-white' : 'bg-secondary text-white' }} rounded-pill font-monospace">
            {{ $statusCounts['pending'] ?? 0 }}
          </span>
        </button>
      </form>
    </div>
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
>>>>>>> 15d4964 ( fix Theo dõi trạng thái đơn hàng)
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
      <!-- NÚT IN PHIẾU ĐÓNG GÓI HÀNG LOẠT -->
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

<div class="bee-table-card">
  <!-- SEARCH SUMMARY HEADER -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3 py-2.5">
    <div class="d-flex align-items-center gap-2">
      <span class="badge bg-light text-dark border fw-semibold">
        Hiển thị <strong>{{ $orders->count() }}</strong> / <strong>{{ $orders->total() }}</strong> đơn hàng
      </span>
      @if(request('status'))
        <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase">
          Tab: {{ $filters['status'] }}
        </span>
      @endif
    </div>
    
    <div class="small text-muted">
      <i class="fa-solid fa-circle-info text-info me-1"></i> Tích chọn nhiều ô để in phiếu hoặc cập nhật bước hàng loạt
    </div>
  </div>

  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th style="width: 42px;" class="text-center">
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
          <th class="text-end">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
          <tr id="row_order_{{ $order->id }}">
            <!-- CHECKBOX CHỌN ĐỒNG BỘ -->
            <td class="text-center">
              <input type="checkbox" class="form-check-input order-item-checkbox" value="{{ $order->id }}" onchange="handleItemCheckboxChange(this)">
            </td>

            <td>
              <a href="{{ route('admin.orders.show', $order->id) }}" class="font-monospace fw-bold text-primary text-decoration-none d-block">
                #{{ $order->order_code }}
              </a>
              <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $order->id }}</small>
            </td>
            <td>
              <small class="text-muted text-nowrap d-block">
                <i class="fa-regular fa-clock me-0.5"></i> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}
              </small>
            </td>
            <!-- CỘT TÀI KHOẢN ĐẶT HÀNG -->
            <td>
              @if($order->user)
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem;">
                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                  </div>
                  <div>
                    <a href="{{ route('admin.customers.show', $order->user->id) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block" style="font-size: 0.84rem;">
                      {{ $order->user->name }}
                    </a>
                    <span class="badge bg-primary-subtle text-primary fw-semibold" style="font-size: 0.68rem;">
                      Thành viên #{{ $order->user->id }}
                    </span>
                  </div>
                </div>
              @else
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.8rem;">
                    <i class="fa-solid fa-user-slash"></i>
                  </div>
                  <div>
                    <span class="badge bg-secondary-subtle text-muted fw-bold" style="font-size: 0.72rem;">
                      Khách Vãng Lai
                    </span>
                  </div>
                </div>
              @endif
            </td>

            <!-- CỘT NGƯỜI NHẬN HÀNG -->
            <td>
              <div class="fw-bold text-dark small">{{ $order->customer_name }}</div>
              <div class="text-muted small" style="font-size: 0.76rem;">
                <i class="fa-solid fa-phone me-1 text-secondary"></i>{{ $order->customer_phone }}
              </div>
              @if($order->shipping_address)
                <small class="text-muted text-truncate d-block" style="max-width: 140px; font-size: 0.72rem;" title="{{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}">
                  <i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $order->shipping_address }}
                </small>
              @endif
            </td>

            <!-- CỘT ĐƠN VỊ VẬN CHUYỂN & MÃ VẬN ĐƠN (CHUẨN TMĐT) -->
            <td>
              @if($order->tracking_code)
                <div class="d-flex flex-column gap-1">
                  <span class="badge bg-info-subtle text-info border border-info-subtle fw-semibold" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-truck-fast me-1"></i> {{ $order->shipping_carrier ?: 'GHTK' }}
                  </span>
                  @if($order->tracking_url)
                    <a href="{{ $order->tracking_url }}" target="_blank" class="badge bg-light text-primary border text-decoration-none font-monospace fw-bold py-1 px-1.5 text-truncate d-inline-block" style="max-width: 130px; font-size: 0.72rem;" title="Bấm để tra cứu hành trình trực tiếp từ hãng vận chuyển">
                      <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>{{ $order->tracking_code }}
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
            </td>

            <!-- CỘT THANH TOÁN -->
            <td>
              <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }} py-1 px-2 fw-bold d-block text-nowrap mb-1">
                {{ $order->payment_status_label }}
              </span>
              <small class="text-muted text-truncate d-block" style="max-width: 120px; font-size: 0.7rem;" title="{{ $order->payment_method_name }}">
                {{ $order->payment_method_name }}
              </small>
            <!-- CỘT TIẾN TRÌNH ĐƠN HÀNG KÈM MỐC THỜI GIAN & NÚT XEM 6 BƯỚC -->
            <td>
              <div class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#orderProgressModal{{ $order->id }}" role="button" title="Bấm để xem chi tiết tiến trình 6 bước của đơn hàng #{{ $order->order_code }}">
                @if($order->shipping_status === 'completed')
                  <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold d-block text-nowrap"><i class="fa-solid fa-circle-check me-1"></i> Bước 6: Hoàn tất</span>
                  @if($order->completed_at)
                    <small class="text-muted font-monospace d-block mt-0.5" style="font-size: 0.68rem;">{{ $order->completed_at->format('d/m/Y H:i') }}</small>
                  @endif
                @elseif($order->shipping_status === 'delivered')
                  <span class="badge bg-success-subtle text-success py-1 px-2 fw-bold d-block text-nowrap"><i class="fa-solid fa-handshake me-1"></i> Bước 5: Đã giao</span>
                  @if($order->delivered_at)
                    <small class="text-muted font-monospace d-block mt-0.5" style="font-size: 0.68rem;">{{ $order->delivered_at->format('d/m/Y H:i') }}</small>
                  @endif
                @elseif($order->shipping_status === 'shipping')
                  <span class="badge bg-warning-subtle text-dark py-1 px-2 fw-bold d-block text-nowrap"><i class="fa-solid fa-truck-fast me-1"></i> Bước 4: Đang giao</span>
                  @if($order->shipping_at)
                    <small class="text-muted font-monospace d-block mt-0.5" style="font-size: 0.68rem;">{{ $order->shipping_at->format('d/m/Y H:i') }}</small>
                  @endif
                @elseif($order->shipping_status === 'processing')
                  <span class="badge bg-info-subtle text-info py-1 px-2 fw-bold d-block text-nowrap"><i class="fa-solid fa-boxes-packing me-1"></i> Bước 3: Đóng gói</span>
                  @if($order->processing_at)
                    <small class="text-muted font-monospace d-block mt-0.5" style="font-size: 0.68rem;">{{ $order->processing_at->format('d/m/Y H:i') }}</small>
                  @endif
                @elseif($order->shipping_status === 'confirmed')
                  <span class="badge bg-primary-subtle text-primary py-1 px-2 fw-bold d-block text-nowrap"><i class="fa-solid fa-clipboard-check me-1"></i> Bước 2: Đã duyệt</span>
                  @if($order->confirmed_at)
                    <small class="text-primary font-monospace d-block mt-0.5" style="font-size: 0.68rem;">{{ $order->confirmed_at->format('d/m/Y H:i') }}</small>
                  @endif
                @elseif($order->shipping_status === 'cancelled')
                  <span class="badge bg-danger-subtle text-danger py-1 px-2 fw-bold d-block text-nowrap"><i class="fa-solid fa-ban me-1"></i> Đã hủy đơn</span>
                  @if($order->cancelled_at)
                    <small class="text-danger font-monospace d-block mt-0.5" style="font-size: 0.68rem;">{{ $order->cancelled_at->format('d/m/Y H:i') }}</small>
                  @endif
                @else
                  <span class="badge bg-warning-subtle text-dark py-1 px-2 fw-bold d-block text-nowrap"><i class="fa-solid fa-clock me-1"></i> Bước 1: Chờ duyệt</span>
                  <small class="text-muted font-monospace d-block mt-0.5" style="font-size: 0.68rem;">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</small>
                @endif

                <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fw-bold mt-1 d-inline-flex align-items-center gap-1 shadow-2xs" style="font-size: 0.7rem;">
                  <i class="fa-solid fa-timeline text-warning"></i> Xem 6 Bước
                </button>
              </div>
            </td>

            <!-- CỘT THAO TÁC (QUICK 1-CLICK ACTION CHO MỌI BƯỚC) -->
            <td class="text-end text-nowrap">
              <div class="d-flex align-items-center justify-content-end gap-1.5 flex-wrap">
                
                <!-- NÚT 1-CHẠM NHANH THEO TIẾN TRÌNH -->
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
                  <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="shipping_status" value="delivered">
                    <button type="submit" class="btn btn-sm btn-success fw-bold py-1 px-2.5 shadow-xs" style="font-size: 0.75rem;" title="Shipper báo đã giao hàng thành công (Bước 5)">
                      <i class="fa-solid fa-handshake me-1"></i> Đã Giao
                    </button>
                  </form>
                @elseif($order->shipping_status === 'delivered')
                  <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="shipping_status" value="completed">
                    <input type="hidden" name="payment_status" value="paid">
                    <button type="submit" class="btn btn-sm btn-success fw-bold py-1 px-2.5 shadow-xs" style="font-size: 0.75rem;" title="Hoàn tất đơn hàng & tích điểm (Bước 6)">
                      <i class="fa-solid fa-circle-check me-1"></i> Hoàn Tất
                    </button>
                  </form>
                @elseif($order->shipping_status === 'completed')
                  <span class="badge bg-success-subtle text-success py-1 px-2 fw-semibold" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-circle-check me-1"></i> Hoàn tất
                  </span>
                @endif

                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary fw-bold py-1 px-2.5" style="font-size: 0.75rem;" title="Xem toàn bộ chi tiết đơn hàng">
                  Chi Tiết <i class="fa-solid fa-chevron-right ms-0.5"></i>
                </a>
              </div>
            </td>
          </tr>

          <!-- MODAL XEM CHI TIẾT 6 BƯỚC TIẾN TRÌNH ĐƠN HÀNG (CHUẨN TMĐT) -->
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

                  @if($order->shipping_status === 'cancelled')
                    <div class="alert alert-danger py-3 px-4 rounded-3 d-flex align-items-center gap-3 mb-3">
                      <i class="fa-solid fa-ban fs-2 text-danger"></i>
                      <div>
                        <strong class="fs-6 d-block">ĐƠN HÀNG ĐÃ BỊ HỦY (CANCELLED)</strong>
                        <span class="small text-danger text-opacity-80">Lý do: <strong>{{ $order->cancel_reason ?: 'Không có ghi chú' }}</strong> • Thời gian: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i') : ($order->updated_at ? $order->updated_at->format('d/m/Y H:i') : '') }}</span>
                      </div>
                    </div>
                  @endif

                  <!-- DANH SÁCH 6 BƯỚC ĐÚNG THIẾT KẾ YÊU CẦU -->
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

          <!-- MODAL BÀN GIAO CHO BƯU TÁ (DISPATCH SHIPMENT BƯỚC 4) -->
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
                        <input type="text" name="tracking_code" id="trackingCodeInput{{ $order->id }}" class="form-control font-monospace fw-bold text-primary" value="{{ $order->tracking_code ?: 'GHTK-' . strtoupper(Str::random(8)) }}" required>
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
        @empty
          <tr>
            <td colspan="11" class="text-center py-5 text-muted">
              <i class="fa-solid fa-cart-shopping fs-2 text-muted mb-2 d-block"></i>
              Không tìm thấy đơn hàng nào phù hợp với bộ lọc.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($orders->hasPages())
    <div class="card-footer d-flex justify-content-center py-3 bg-white border-top">
      {{ $orders->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

@push('scripts')
<script>
  function toggleCustomDate(preset) {
    const row = document.getElementById('customDateRow');
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
</script>
@endpush
@endsection
