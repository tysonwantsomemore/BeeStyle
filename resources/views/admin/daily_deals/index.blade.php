@extends('layouts.admin')

@section('title', 'Quản Lý Ưu Đãi Trong Ngày (Flash Sale) | BeeStyle Admin')

@section('content')
<!-- TOP HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-auto">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-danger text-uppercase fw-bold fs-10">
        <span class="fa-solid fa-bolt me-1"></span>Flash Sale Giờ Vàng
      </span>
    </div>
    <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Ưu Đãi Trong Ngày</h2>
    <p class="text-body-tertiary mb-0">Cấu hình sản phẩm khuyến mãi chớp nhoáng theo khung giờ vàng, theo dõi doanh thu và tài khoản khách mua</p>
  </div>
  <div class="col-auto d-flex align-items-center gap-2 flex-wrap">
    @if($statusFilter === 'running')
      <a href="{{ route('client.daily-deals.index', ['tab' => 'running']) }}" target="_blank" class="btn btn-phoenix-danger btn-sm">
        <span class="fa-solid fa-bolt me-1"></span>Xem Khách Thấy ({{ $runningDealsCount }} SP) <span class="fa-solid fa-arrow-up-right-from-square ms-1"></span>
      </a>
    @elseif($statusFilter === 'today' || $dateFilter === 'today')
      <a href="{{ route('client.daily-deals.index', ['tab' => 'all']) }}" target="_blank" class="btn btn-phoenix-warning btn-sm">
        <span class="fa-solid fa-clock me-1"></span>Xem Khách Thấy ({{ $todayDealsCount }} SP) <span class="fa-solid fa-arrow-up-right-from-square ms-1"></span>
      </a>
    @else
      <a href="{{ route('client.daily-deals.index') }}" target="_blank" class="btn btn-phoenix-secondary btn-sm">
        <span class="fa-solid fa-store me-1"></span>Xem Trang Ưu Đãi <span class="fa-solid fa-arrow-up-right-from-square ms-1"></span>
      </a>
    @endif
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDealModal">
      <span class="fa-solid fa-plus me-2"></span>Thêm Ưu Đãi Mới
    </button>
  </div>
</div>

<!-- 4 THẺ THỐNG KÊ TỔNG QUAN (CLICKABLE KPI CARDS) -->
<div class="row g-3 mb-4">
  <!-- CARD 1: ĐANG CHẠY LÚC NÀY -->
  <div class="col-6 col-lg-3">
    <a href="{{ route('admin.daily-deals.index', ['status' => 'running']) }}" class="text-decoration-none d-block h-100">
      <div class="card border-0 shadow-sm h-100 {{ $statusFilter === 'running' ? 'border border-danger' : '' }}">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center">
              <div class="badge-phoenix-icon me-2 badge-phoenix-danger">
                <span class="fa-solid fa-fire fs-9"></span>
              </div>
              <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Đang Chạy Lúc Này</h6>
            </div>
          </div>
          <div class="d-flex align-items-baseline gap-2">
            <h3 class="mb-0 text-danger fw-bolder">{{ $runningDealsCount }}</h3>
            <span class="fs-10 text-body-tertiary">sản phẩm</span>
          </div>
          <div class="mt-2 pt-2 border-top border-translucent d-flex align-items-center justify-content-between text-danger fs-10 fw-semibold">
            <span>{{ $statusFilter === 'running' ? '● Đang lọc mục này' : 'Bấm xem danh sách' }}</span>
            <span class="fa-solid fa-arrow-right"></span>
          </div>
        </div>
      </div>
    </a>
  </div>

  <!-- CARD 2: ƯU ĐÃI HÔM NAY -->
  <div class="col-6 col-lg-3">
    <a href="{{ route('admin.daily-deals.index', ['status' => 'today']) }}" class="text-decoration-none d-block h-100">
      <div class="card border-0 shadow-sm h-100 {{ ($statusFilter === 'today' || ($dateFilter === 'today' && $statusFilter === 'all')) ? 'border border-warning' : '' }}">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center">
              <div class="badge-phoenix-icon me-2 badge-phoenix-warning">
                <span class="fa-solid fa-clock fs-9"></span>
              </div>
              <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Ưu Đãi Hôm Nay</h6>
            </div>
          </div>
          <div class="d-flex align-items-baseline gap-2">
            <h3 class="mb-0 text-warning fw-bolder">{{ $todayDealsCount }}</h3>
            <span class="fs-10 text-body-tertiary">chiến dịch</span>
          </div>
          <div class="mt-2 pt-2 border-top border-translucent d-flex align-items-center justify-content-between text-warning fs-10 fw-semibold">
            <span>{{ ($statusFilter === 'today' || ($dateFilter === 'today' && $statusFilter === 'all')) ? '● Đang lọc mục này' : 'Bấm xem danh sách' }}</span>
            <span class="fa-solid fa-arrow-right"></span>
          </div>
        </div>
      </div>
    </a>
  </div>

  <!-- CARD 3: ĐÃ BÁN QUA DEAL -->
  <div class="col-6 col-lg-3">
    <a href="{{ route('admin.daily-deals.index', ['status' => 'sold']) }}" class="text-decoration-none d-block h-100">
      <div class="card border-0 shadow-sm h-100 {{ $statusFilter === 'sold' ? 'border border-success' : '' }}">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center">
              <div class="badge-phoenix-icon me-2 badge-phoenix-success">
                <span class="fa-solid fa-bag-shopping fs-9"></span>
              </div>
              <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Đã Bán Qua Deal</h6>
            </div>
          </div>
          <div class="d-flex align-items-baseline gap-2">
            <h3 class="mb-0 text-success fw-bolder">{{ number_format($totalSoldInDeals) }}</h3>
            <span class="fs-10 text-body-tertiary">sản phẩm</span>
          </div>
          <div class="mt-2 pt-2 border-top border-translucent d-flex align-items-center justify-content-between text-success fs-10 fw-semibold">
            <span><span class="fa-solid fa-coins me-1"></span>{{ number_format($totalDealRevenue ?? 0, 0, ',', '.') }}₫</span>
            <span class="fa-solid fa-arrow-right"></span>
          </div>
        </div>
      </div>
    </a>
  </div>

  <!-- CARD 4: TỔNG CHIẾN DỊCH -->
  <div class="col-6 col-lg-3">
    <a href="{{ route('admin.daily-deals.index', ['status' => 'all']) }}" class="text-decoration-none d-block h-100">
      <div class="card border-0 shadow-sm h-100 {{ ($statusFilter === 'all' && empty($dateFilter) && empty($search)) ? 'border border-primary' : '' }}">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center">
              <div class="badge-phoenix-icon me-2 badge-phoenix-primary">
                <span class="fa-solid fa-tags fs-9"></span>
              </div>
              <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Tổng Chiến Dịch</h6>
            </div>
          </div>
          <div class="d-flex align-items-baseline gap-2">
            <h3 class="mb-0 text-body-emphasis fw-bolder">{{ $totalDeals }}</h3>
            <span class="fs-10 text-body-tertiary">tất cả</span>
          </div>
          <div class="mt-2 pt-2 border-top border-translucent d-flex align-items-center justify-content-between text-body-tertiary fs-10 fw-semibold">
            <span>{{ ($statusFilter === 'all' && empty($dateFilter) && empty($search)) ? '● Đang xem tất cả' : 'Bấm xem tất cả' }}</span>
            <span class="fa-solid fa-arrow-right"></span>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- TOOLBAR & BỘ LỌC TÌM KIẾM -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-3">
    <!-- QUICK PILLS FILTER BAR -->
    <div class="d-flex align-items-center gap-2 overflow-auto pb-2 mb-3 border-bottom border-translucent">
      <span class="fs-10 fw-bold text-body-tertiary text-uppercase text-nowrap me-1">Bộ lọc nhanh:</span>
      <a href="{{ route('admin.daily-deals.index', ['status' => 'all']) }}" class="btn btn-sm {{ $statusFilter === 'all' && empty($dateFilter) ? 'btn-primary' : 'btn-phoenix-secondary' }} px-3 py-1 text-nowrap fs-10">
        Tất Cả ({{ $totalDeals }})
      </a>
      <a href="{{ route('admin.daily-deals.index', ['status' => 'running']) }}" class="btn btn-sm {{ $statusFilter === 'running' ? 'btn-danger' : 'btn-phoenix-secondary' }} px-3 py-1 text-nowrap fs-10">
        <span class="fa-solid fa-bolt me-1"></span>Đang Diễn Ra ({{ $runningDealsCount }})
      </a>
      <a href="{{ route('admin.daily-deals.index', ['status' => 'today']) }}" class="btn btn-sm {{ $statusFilter === 'today' || $dateFilter === 'today' ? 'btn-warning' : 'btn-phoenix-secondary' }} px-3 py-1 text-nowrap fs-10">
        <span class="fa-regular fa-calendar me-1"></span>Áp Dụng Hôm Nay ({{ $todayDealsCount }})
      </a>
      <a href="{{ route('admin.daily-deals.index', ['status' => 'sold']) }}" class="btn btn-sm {{ $statusFilter === 'sold' ? 'btn-success' : 'btn-phoenix-secondary' }} px-3 py-1 text-nowrap fs-10">
        <span class="fa-solid fa-coins me-1"></span>Đã Bán &amp; Doanh Thu
      </a>
      <a href="{{ route('admin.daily-deals.index', ['status' => 'upcoming']) }}" class="btn btn-sm {{ $statusFilter === 'upcoming' ? 'btn-info' : 'btn-phoenix-secondary' }} px-3 py-1 text-nowrap fs-10">
        <span class="fa-solid fa-hourglass-start me-1"></span>Sắp Diễn Ra
      </a>
      <a href="{{ route('admin.daily-deals.index', ['status' => 'inactive']) }}" class="btn btn-sm {{ $statusFilter === 'inactive' ? 'btn-secondary' : 'btn-phoenix-secondary' }} px-3 py-1 text-nowrap fs-10">
        <span class="fa-solid fa-pause me-1"></span>Đã Tạm Dừng
      </a>
    </div>

    <!-- FORM SEARCH & SELECT CONTROLS -->
    <form action="{{ route('admin.daily-deals.index') }}" method="GET" class="row g-2 align-items-center">
      <div class="col-12 col-md-5">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-body-emphasis border-end-0 text-body-tertiary">
            <span class="fa-solid fa-magnifying-glass"></span>
          </span>
          <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0" placeholder="Tìm theo tên sản phẩm hoặc mã SKU...">
          @if($search)
            <a href="{{ route('admin.daily-deals.index', ['status' => $statusFilter]) }}" class="input-group-text bg-body-emphasis border-start-0 text-body-tertiary" title="Xóa tìm kiếm">
              <span class="fa-solid fa-xmark"></span>
            </a>
          @endif
        </div>
      </div>

      <div class="col-6 col-md-3">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
          <option value="running" {{ $statusFilter === 'running' ? 'selected' : '' }}>⚡ Đang diễn ra bây giờ</option>
          <option value="today" {{ $statusFilter === 'today' ? 'selected' : '' }}>📅 Áp dụng hôm nay</option>
          <option value="sold" {{ $statusFilter === 'sold' ? 'selected' : '' }}>🔥 Đã có lượt bán qua Deal</option>
          <option value="upcoming" {{ $statusFilter === 'upcoming' ? 'selected' : '' }}>⏳ Sắp diễn ra hôm nay</option>
          <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>⏸️ Đã tạm dừng</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <select name="date" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Tất cả các ngày</option>
          <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>Áp dụng hôm nay ({{ now()->format('d/m') }})</option>
        </select>
      </div>

      <div class="col-12 col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">Lọc dữ liệu</button>
        @if($search || $statusFilter !== 'all' || $dateFilter)
          <a href="{{ route('admin.daily-deals.index') }}" class="btn btn-phoenix-secondary btn-sm" title="Đặt lại bộ lọc">
            <span class="fa-solid fa-rotate-left"></span>
          </a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- BẢNG DANH SÁCH CHIẾN DỊCH ƯU ĐÃI -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <span class="fa-solid fa-bolt text-danger fs-8"></span>
      <h5 class="mb-0 text-body-emphasis">Danh Sách Ưu Đãi Flash Sale</h5>
      <span class="badge badge-phoenix badge-phoenix-secondary ms-2">{{ $deals->total() }} chiến dịch</span>
    </div>
    @if($statusFilter !== 'all' || $dateFilter || $search)
      <div class="fs-10 text-body-tertiary">
        Đang lọc: <strong class="text-body-emphasis">{{ $deals->total() }}</strong> kết quả
      </div>
    @endif
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle table-hover">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-4 py-3" style="min-width: 260px;">SẢN PHẨM ÁP DỤNG</th>
            <th class="py-3">MỨC GIẢM</th>
            <th class="py-3">KHUNG GIỜ VÀNG</th>
            <th class="py-3">NGÀY ÁP DỤNG</th>
            <th class="py-3" style="min-width: 150px;">TIẾN ĐỘ &amp; DOANH THU</th>
            <th class="py-3">ĐÁNH GIÁ</th>
            <th class="py-3">TRẠNG THÁI</th>
            <th class="pe-4 py-3 text-end" style="min-width: 170px;">THAO TÁC</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($deals as $deal)
            @php
              $product = $deal->product;
              $isRunning = $deal->is_running;
              $dealRevenue = $deal->sold_count * $deal->deal_price;
              $avgRating = $product ? round($product->reviews->avg('rating') ?: 5, 1) : 5.0;
              $revCount = $product ? $product->allReviews->count() : 0;
            @endphp
            <tr>
              <!-- 1. Sản phẩm áp dụng -->
              <td class="ps-4 py-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="position-relative" style="width: 48px; height: 48px; min-width: 48px;">
                    @if($product && $product->image)
                      <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 rounded-2 object-fit-cover border border-translucent">
                    @else
                      <div class="w-100 h-100 rounded-2 bg-body-secondary d-flex align-items-center justify-content-center">
                        <span class="fa-solid fa-shirt text-body-tertiary"></span>
                      </div>
                    @endif
                    @if($isRunning)
                      <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-danger border border-2 border-white rounded-circle" title="Đang mở bán trực tiếp trên Web"></span>
                    @endif
                  </div>
                  <div>
                    <div class="fw-bold text-body-emphasis mb-0.5" style="max-width: 250px;">
                      @if($product)
                        <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="text-body-emphasis text-decoration-none">
                          {{ $product->name }}
                        </a>
                      @else
                        <span class="text-danger">[Sản phẩm đã bị xóa]</span>
                      @endif
                    </div>
                    <div class="d-flex align-items-center gap-2 fs-10 text-body-tertiary">
                      <span>SKU: <strong class="text-body-secondary font-monospace">{{ $product->sku ?? 'N/A' }}</strong></span>
                      <span>•</span>
                      <span>{{ $product->category->name ?? 'Thời trang Nam' }}</span>
                    </div>
                  </div>
                </div>
              </td>

              <!-- 2. Mức giảm & Giá bán -->
              <td class="py-3">
                <div class="d-flex flex-column gap-1">
                  <div>
                    <span class="badge badge-phoenix badge-phoenix-danger fw-bold">
                      <span class="fa-solid fa-arrow-down me-0.5"></span>Giảm {{ $deal->discount_percent }}%
                    </span>
                  </div>
                  <div class="d-flex align-items-baseline gap-1.5">
                    <strong class="text-danger fw-bold fs-9">{{ number_format($deal->deal_price, 0, ',', '.') }}₫</strong>
                    @if($product && $product->price > $deal->deal_price)
                      <small class="text-body-tertiary text-decoration-line-through fs-11">{{ number_format($product->price, 0, ',', '.') }}₫</small>
                    @endif
                  </div>
                </div>
              </td>

              <!-- 3. Khung giờ vàng -->
              <td class="py-3">
                <span class="badge badge-phoenix badge-phoenix-warning font-monospace fw-bold fs-9 px-2 py-1">
                  <span class="fa-regular fa-clock me-1"></span>
                  {{ substr($deal->start_time, 0, 5) }} - {{ substr($deal->end_time, 0, 5) }}
                </span>
                @if($deal->slot_name && $deal->slot_name !== (substr($deal->start_time, 0, 5) . ' - ' . substr($deal->end_time, 0, 5)))
                  <div class="fs-10 text-body-tertiary mt-1">{{ $deal->slot_name }}</div>
                @endif
              </td>

              <!-- 4. Ngày áp dụng -->
              <td class="py-3">
                @if($deal->deal_date)
                  <span class="badge badge-phoenix badge-phoenix-primary fw-medium px-2 py-1">
                    <span class="fa-regular fa-calendar me-1"></span>
                    {{ $deal->deal_date->format('d/m/Y') }}
                  </span>
                  @if($deal->deal_date->isToday())
                    <span class="badge badge-phoenix badge-phoenix-success fs-11 d-block mt-1" style="width: fit-content;">Hôm nay</span>
                  @endif
                @else
                  <span class="badge badge-phoenix badge-phoenix-info fw-bold px-2 py-1">
                    <span class="fa-solid fa-repeat me-1"></span>Hàng ngày
                  </span>
                @endif
              </td>

              <!-- 5. Tiến độ & Doanh thu -->
              <td class="py-3">
                <div class="d-flex flex-column gap-1" style="min-width: 130px;">
                  <div class="d-flex justify-content-between fs-10 text-body-tertiary">
                    <span>Đã bán: <strong class="text-body-emphasis">{{ $deal->sold_count }}</strong></span>
                    <span>{{ $deal->quantity_limit > 0 ? '/ ' . $deal->quantity_limit : '(KGH)' }}</span>
                  </div>
                  @php
                    $pct = $deal->quantity_limit > 0 ? min(100, round(($deal->sold_count / $deal->quantity_limit) * 100)) : 100;
                  @endphp
                  <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pct }}%"></div>
                  </div>
                  <div class="mt-0.5">
                    <span class="badge badge-phoenix badge-phoenix-success fw-bold fs-11">
                      <span class="fa-solid fa-coins me-0.5"></span>{{ number_format($dealRevenue, 0, ',', '.') }}₫
                    </span>
                  </div>
                </div>
              </td>

              <!-- 6. Đánh giá -->
              <td class="py-3">
                <div class="d-flex flex-column">
                  <div class="text-warning fs-10 mb-0.5">
                    <span class="fa-solid fa-star"></span>
                    <strong class="text-body-emphasis ms-1">{{ $avgRating }}</strong>
                    <span class="text-body-tertiary">/5</span>
                  </div>
                  <span class="fs-11 text-body-tertiary">{{ $revCount }} đánh giá</span>
                </div>
              </td>

              <!-- 7. Trạng thái -->
              <td class="py-3">
                @if(!$deal->is_active)
                  <span class="badge badge-phoenix badge-phoenix-secondary">
                    <span class="fa-solid fa-pause me-1"></span>Tạm dừng
                  </span>
                @elseif($isRunning)
                  <span class="badge badge-phoenix badge-phoenix-danger">
                    <span class="fa-solid fa-bolt me-1"></span>Đang mở bán
                  </span>
                @else
                  <span class="badge {{ $deal->status_badge_class }}">
                    {{ $deal->status_label }}
                  </span>
                @endif
                <div class="fs-11 text-body-tertiary mt-1">
                  Còn {{ $deal->quantity_limit > 0 ? max(0, $deal->quantity_limit - $deal->sold_count) : '∞' }} suất
                </div>
              </td>

              <!-- 8. Thao tác -->
              <td class="pe-4 py-3 text-end">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <!-- Báo cáo & Khách mua -->
                  <button type="button" class="btn btn-phoenix-primary btn-sm px-2 py-1" data-bs-toggle="modal" data-bs-target="#salesAnalyticsModal_{{ $deal->id }}" title="Xem chi tiết doanh thu & khách đã mua">
                    <span class="fa-solid fa-chart-pie me-1"></span>Báo cáo
                  </button>

                  <!-- Sửa -->
                  <button type="button" class="btn btn-phoenix-secondary btn-sm px-2 py-1" data-bs-toggle="modal" data-bs-target="#editDealModal_{{ $deal->id }}" title="Chỉnh sửa thông số ưu đãi">
                    <span class="fa-solid fa-pen-to-square"></span>
                  </button>

                  <!-- Gia hạn -->
                  <button type="button" class="btn btn-phoenix-warning btn-sm px-2 py-1" data-bs-toggle="modal" data-bs-target="#renewDealModal_{{ $deal->id }}" title="Gia hạn thời gian Flash Sale">
                    <span class="fa-solid fa-clock-rotate-left"></span>
                  </button>

                  <!-- Bật/Tắt toggle -->
                  <form action="{{ route('admin.daily-deals.toggle', $deal->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn {{ $deal->is_active ? 'btn-phoenix-secondary' : 'btn-phoenix-success' }} btn-sm px-2 py-1" title="{{ $deal->is_active ? 'Tạm dừng ưu đãi' : 'Kích hoạt mở bán' }}">
                      <span class="fa-solid {{ $deal->is_active ? 'fa-pause' : 'fa-play' }}"></span>
                    </button>
                  </form>

                  <!-- Xóa -->
                  <form action="{{ route('admin.daily-deals.destroy', $deal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn gỡ sản phẩm này khỏi danh sách Ưu Đãi Trong Ngày?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-phoenix-danger btn-sm px-2 py-1" title="Xóa ưu đãi">
                      <span class="fa-regular fa-trash-can"></span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="py-3">
                  <span class="fa-solid fa-bolt text-body-tertiary fs-2 mb-2 d-block"></span>
                  <h6 class="text-body-emphasis fw-bold mb-1">Chưa có ưu đãi nào phù hợp với bộ lọc</h6>
                  <p class="text-body-tertiary fs-9 mb-3">Hãy chọn bộ lọc khác hoặc tạo ưu đãi mới để đưa sản phẩm lên sàn Flash Sale.</p>
                  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDealModal">
                    <span class="fa-solid fa-plus me-1"></span>Thêm Ưu Đãi Mới
                  </button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($deals->hasPages())
    <div class="card-footer border-top border-translucent py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="fs-9 text-body-tertiary">
        Hiển thị từ <span class="fw-semibold text-body-emphasis">{{ $deals->firstItem() }}</span> đến <span class="fw-semibold text-body-emphasis">{{ $deals->lastItem() }}</span> trong tổng số <span class="fw-semibold text-body-emphasis">{{ $deals->total() }}</span> ưu đãi
      </div>
      <div>
        {{ $deals->links() }}
      </div>
    </div>
  @endif
</div>

<!-- ========================================================================= -->
<!-- TOÀN BỘ MODALS ĐƯỢC ĐẶT NGOÀI BẢNG -->
<!-- ========================================================================= -->

@foreach($deals as $deal)
  @php
    $product = $deal->product;
    $isRunning = $deal->is_running;
    $dealRevenue = $deal->sold_count * $deal->deal_price;
    $avgRating = $product ? round($product->reviews->avg('rating') ?: 5, 1) : 5.0;
    $revCount = $product ? $product->allReviews->count() : 0;
    $orderItems = $product ? $product->orderItems : collect();
    $pct = $deal->quantity_limit > 0 ? min(100, round(($deal->sold_count / $deal->quantity_limit) * 100)) : 100;
  @endphp

  <!-- 1. MODAL BÁO CÁO DOANH THU & KHÁCH MUA (SALES ANALYTICS) -->
  <div class="modal fade" id="salesAnalyticsModal_{{ $deal->id }}" tabindex="-1" aria-labelledby="salesAnalyticsModalLabel_{{ $deal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
      <div class="modal-content border-0 shadow-lg">
        <!-- MODAL HEADER -->
        <div class="modal-header border-bottom border-translucent bg-body-emphasis py-3 px-4">
          <div class="d-flex align-items-center gap-3">
            <div class="position-relative" style="width: 48px; height: 48px; min-width: 48px;">
              <img src="{{ asset($product->image ?? 'assets/img/team/40x40/58.webp') }}" alt="{{ $product->name ?? 'SP' }}" class="w-100 h-100 rounded-2 object-fit-cover border border-translucent">
              <span class="position-absolute top-0 start-100 translate-middle badge badge-phoenix badge-phoenix-danger fs-11">
                -{{ $deal->discount_percent }}%
              </span>
            </div>
            <div>
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="salesAnalyticsModalLabel_{{ $deal->id }}">
                  {{ $product->name ?? 'Sản phẩm Flash Sale' }}
                </h5>
                <span class="badge {{ $deal->status_badge_class }} fs-10">{{ $deal->status_label }}</span>
              </div>
              <div class="fs-10 text-body-tertiary d-flex align-items-center gap-2 mt-1 flex-wrap">
                <span>SKU: <strong class="text-body-secondary font-monospace">{{ $product->sku ?? 'N/A' }}</strong></span>
                <span>•</span>
                <span>Khung giờ: <strong class="text-body-emphasis">{{ substr($deal->start_time, 0, 5) }} - {{ substr($deal->end_time, 0, 5) }}</strong></span>
                <span>•</span>
                <span>Giá Flash Sale: <strong class="text-danger fw-bold">{{ number_format($deal->deal_price, 0, ',', '.') }}₫</strong> (Gốc: {{ number_format($product->price ?? 0, 0, ',', '.') }}₫)</span>
              </div>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>

        <!-- MODAL BODY -->
        <div class="modal-body bg-body p-4">
          <!-- 3 THẺ TỔNG KẾT DOANH THU & HIỆU QUẢ -->
          <div class="row g-3 mb-4">
            <!-- Doanh thu -->
            <div class="col-md-4">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="fs-10 text-body-tertiary text-uppercase fw-semibold">Tổng Doanh Thu Deal</span>
                    <div class="badge-phoenix-icon badge-phoenix-success"><span class="fa-solid fa-coins fs-9"></span></div>
                  </div>
                  <h4 class="fw-bolder text-success mb-1">{{ number_format($dealRevenue, 0, ',', '.') }}₫</h4>
                  <div class="fs-10 text-body-tertiary">
                    Tiết kiệm cho khách: <strong class="text-danger">{{ number_format(max(0, (($product->price ?? 0) - $deal->deal_price) * $deal->sold_count), 0, ',', '.') }}₫</strong>
                  </div>
                </div>
              </div>
            </div>

            <!-- Số lượng bán -->
            <div class="col-md-4">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="fs-10 text-body-tertiary text-uppercase fw-semibold">Số Lượng Đã Bán</span>
                    <div class="badge-phoenix-icon badge-phoenix-danger"><span class="fa-solid fa-bag-shopping fs-9"></span></div>
                  </div>
                  <h4 class="fw-bolder text-danger mb-1">{{ $deal->sold_count }} <span class="fs-9 text-body-tertiary fw-normal">/ {{ $deal->quantity_limit ?: 'Không giới hạn' }}</span></h4>
                  <div class="progress mt-1.5" style="height: 5px;">
                    <div class="progress-bar bg-danger" style="width: {{ $pct }}%"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Đánh giá -->
            <div class="col-md-4">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="fs-10 text-body-tertiary text-uppercase fw-semibold">Đánh Giá Trung Bình</span>
                    <div class="badge-phoenix-icon badge-phoenix-warning"><span class="fa-solid fa-star fs-9"></span></div>
                  </div>
                  <h4 class="fw-bolder text-warning mb-1">{{ $avgRating }} <span class="fs-9 text-body-tertiary fw-normal">/ 5.0 ⭐</span></h4>
                  <div class="fs-10 text-body-tertiary">Tổng cộng <strong class="text-body-emphasis">{{ $revCount }}</strong> lượt đánh giá của khách</div>
                </div>
              </div>
            </div>
          </div>

          <!-- TABS ĐIỀU HƯỚNG -->
          <ul class="nav nav-underline border-bottom border-translucent mb-3" id="analyticsTab_{{ $deal->id }}" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="orders-tab-{{ $deal->id }}" data-bs-toggle="tab" data-bs-target="#orders-pane-{{ $deal->id }}" type="button" role="tab">
                <span class="fa-solid fa-users text-danger me-1.5"></span>Khách Hàng Đã Mua ({{ $orderItems->count() }})
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="reviews-tab-{{ $deal->id }}" data-bs-toggle="tab" data-bs-target="#reviews-pane-{{ $deal->id }}" type="button" role="tab">
                <span class="fa-solid fa-star text-warning me-1.5"></span>Đánh Giá Sản Phẩm ({{ $revCount }})
              </button>
            </li>
          </ul>

          <!-- TAB CONTENT -->
          <div class="tab-content" id="analyticsTabContent_{{ $deal->id }}">
            <!-- TAB 1: KHÁCH HÀNG ĐÃ MUA -->
            <div class="tab-pane fade show active" id="orders-pane-{{ $deal->id }}" role="tabpanel">
              @if($orderItems->isNotEmpty())
                <div class="table-responsive scrollbar border border-translucent rounded-3">
                  <table class="table table-sm fs-9 mb-0 align-middle table-hover">
                    <thead class="bg-body-tertiary text-body-tertiary">
                      <tr>
                        <th class="ps-3 py-2.5">KHÁCH HÀNG</th>
                        <th class="py-2.5">MÃ ĐƠN HÀNG</th>
                        <th class="py-2.5">PHÂN LOẠI</th>
                        <th class="py-2.5">SỐ LƯỢNG &amp; GIÁ</th>
                        <th class="py-2.5">THÀNH TIỀN</th>
                        <th class="py-2.5">THỜI GIAN ĐẶT</th>
                        <th class="py-2.5">TRẠNG THÁI</th>
                        <th class="pe-3 py-2.5 text-end">CHI TIẾT</th>
                      </tr>
                    </thead>
                    <tbody class="list">
                      @foreach($orderItems as $item)
                        @php
                          $order = $item->order;
                          $user = $order ? $order->user : null;
                        @endphp
                        <tr>
                          <!-- Khách Hàng -->
                          <td class="ps-3 py-2.5">
                            <div class="d-flex align-items-center gap-2">
                              <div class="avatar avatar-s rounded-circle bg-body-secondary d-flex align-items-center justify-content-center fw-bold text-primary">
                                {{ mb_substr($order->customer_name ?? ($user->name ?? 'KH'), 0, 1) }}
                              </div>
                              <div>
                                <div class="fw-bold text-body-emphasis">{{ $order->customer_name ?? ($user->name ?? 'Khách vãng lai') }}</div>
                                <div class="fs-10 text-body-tertiary">
                                  {{ $order->customer_email ?? ($user->email ?? 'N/A') }}
                                  @if($order && $order->customer_phone)
                                    • {{ $order->customer_phone }}
                                  @endif
                                </div>
                              </div>
                            </div>
                          </td>

                          <!-- Mã Đơn -->
                          <td class="py-2.5">
                            <span class="font-monospace fw-bold text-primary">{{ $order->order_code ?? '#' . $item->order_id }}</span>
                          </td>

                          <!-- Phân loại -->
                          <td class="py-2.5">
                            @if($item->color || $item->size)
                              <span class="badge badge-phoenix badge-phoenix-secondary fs-11">
                                {{ $item->color ?? '' }} {{ $item->size ? '• Size ' . $item->size : '' }}
                              </span>
                            @else
                              <span class="text-body-tertiary">Mặc định</span>
                            @endif
                          </td>

                          <!-- Số lượng & Giá -->
                          <td class="py-2.5">
                            <div><strong class="text-danger">x{{ $item->quantity }}</strong></div>
                            <div class="fs-10 text-body-tertiary">{{ number_format($item->price, 0, ',', '.') }}₫</div>
                          </td>

                          <!-- Thành tiền -->
                          <td class="py-2.5">
                            <strong class="text-success fw-bold">
                              {{ number_format($item->subtotal ?: ($item->price * $item->quantity), 0, ',', '.') }}₫
                            </strong>
                          </td>

                          <!-- Thời gian -->
                          <td class="py-2.5">
                            <span class="text-body-secondary fs-10">
                              {{ $order && $order->created_at ? $order->created_at->format('d/m/Y H:i') : ($item->created_at ? $item->created_at->format('d/m/Y H:i') : 'N/A') }}
                            </span>
                          </td>

                          <!-- Trạng thái -->
                          <td class="py-2.5">
                            @if($order)
                              @if($order->shipping_status === 'completed' || $order->shipping_status === 'delivered')
                                <span class="badge badge-phoenix badge-phoenix-success">Hoàn tất</span>
                              @elseif($order->shipping_status === 'shipping')
                                <span class="badge badge-phoenix badge-phoenix-warning">Đang giao</span>
                              @elseif($order->shipping_status === 'cancelled')
                                <span class="badge badge-phoenix badge-phoenix-danger">Đã hủy</span>
                              @else
                                <span class="badge badge-phoenix badge-phoenix-info">Đang xử lý</span>
                              @endif
                            @else
                              <span class="badge badge-phoenix badge-phoenix-secondary">N/A</span>
                            @endif
                          </td>

                          <!-- Link đơn hàng -->
                          <td class="pe-3 py-2.5 text-end">
                            @if($order)
                              <a href="{{ route('admin.orders.show', $order->id) }}" target="_blank" class="btn btn-phoenix-secondary btn-sm px-2 py-1 fs-10">
                                Xem Đơn <span class="fa-solid fa-arrow-up-right-from-square ms-1"></span>
                              </a>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="p-4 text-center bg-body-tertiary rounded-3 border border-translucent">
                  <span class="fa-solid fa-box-open fs-2 text-body-tertiary mb-2 d-block"></span>
                  <h6 class="fw-bold text-body-emphasis mb-1">Chưa có đơn hàng nào được ghi nhận</h6>
                  <p class="fs-10 text-body-tertiary mb-0">Các đơn hàng mua sản phẩm này khi áp dụng mức giảm giá Flash Sale sẽ tự động được hiển thị tại đây.</p>
                </div>
              @endif
            </div>

            <!-- TAB 2: ĐÁNH GIÁ -->
            <div class="tab-pane fade" id="reviews-pane-{{ $deal->id }}" role="tabpanel">
              @if($product && $product->allReviews->isNotEmpty())
                <div class="d-flex flex-column gap-2">
                  @foreach($product->allReviews as $rev)
                    <div class="p-3 bg-body-tertiary rounded-3 border border-translucent d-flex justify-content-between align-items-start gap-3">
                      <div class="d-flex align-items-start gap-2.5">
                        <img src="{{ $rev->user_avatar_url }}" alt="{{ $rev->user_name }}" class="avatar avatar-m rounded-circle border border-translucent object-fit-cover">
                        <div>
                          <div class="d-flex align-items-center gap-2">
                            <strong class="text-body-emphasis fs-9">{{ $rev->user_name }}</strong>
                            <div class="text-warning fs-10">
                              @for($i=1; $i<=5; $i++)
                                <span class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-body-tertiary' }}"></span>
                              @endfor
                            </div>
                            @if($rev->status === 'approved')
                              <span class="badge badge-phoenix badge-phoenix-success fs-11">Đã duyệt</span>
                            @else
                              <span class="badge badge-phoenix badge-phoenix-warning fs-11">Chờ duyệt</span>
                            @endif
                          </div>
                          <p class="mb-1 text-body-secondary fs-9 mt-1">{{ $rev->comment }}</p>
                          <small class="text-body-tertiary fs-11">
                            <span class="fa-regular fa-clock me-1"></span>{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}
                          </small>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="p-4 text-center bg-body-tertiary rounded-3 border border-translucent">
                  <span class="fa-solid fa-star-half-stroke fs-2 text-body-tertiary mb-2 d-block"></span>
                  <h6 class="fw-bold text-body-emphasis mb-1">Chưa có đánh giá nào cho sản phẩm này</h6>
                  <p class="fs-10 text-body-tertiary mb-0">Khi khách hàng đã mua và gửi đánh giá nhận xét, thông tin sẽ được cập nhật tại đây.</p>
                </div>
              @endif
            </div>
          </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="modal-footer border-top border-translucent bg-body-emphasis py-3 px-4 d-flex justify-content-between align-items-center">
          <div class="fs-10 text-body-tertiary">
            <span class="fa-solid fa-circle-info text-primary me-1"></span>Dữ liệu doanh thu được tổng hợp trực tiếp từ các đơn hàng liên kết với sản phẩm này.
          </div>
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. MODAL GIA HẠN ƯU ĐÃI (RENEW DEAL) -->
  <div class="modal fade" id="renewDealModal_{{ $deal->id }}" tabindex="-1" aria-labelledby="renewDealModalLabel_{{ $deal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-bottom border-translucent bg-body-emphasis py-3 px-4">
          <div class="d-flex align-items-center gap-2">
            <span class="badge badge-phoenix badge-phoenix-warning p-2 rounded-circle">
              <span class="fa-solid fa-clock-rotate-left"></span>
            </span>
            <div>
              <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="renewDealModalLabel_{{ $deal->id }}">
                Gia Hạn Ưu Đãi Trong Ngày
              </h5>
              <small class="text-body-tertiary">{{ Str::limit($product->name ?? 'Sản phẩm', 35) }}</small>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        
        <form action="{{ route('admin.daily-deals.renew', $deal->id) }}" method="POST">
          @csrf
          <div class="modal-body bg-body p-4">
            <!-- Trạng thái hiện tại -->
            <div class="p-3 bg-body-tertiary rounded-3 mb-3 border border-translucent d-flex justify-content-between align-items-center">
              <div>
                <span class="fs-10 text-body-tertiary d-block">Trạng thái:</span>
                <span class="badge {{ $deal->status_badge_class }} fs-10">{{ $deal->status_label }}</span>
              </div>
              <div class="text-end">
                <span class="fs-10 text-body-tertiary d-block">Khung giờ hiện tại:</span>
                <strong class="fs-9 text-body-emphasis font-monospace">{{ substr($deal->start_time, 0, 5) }} - {{ substr($deal->end_time, 0, 5) }}</strong>
              </div>
            </div>

            <!-- Chọn phương thức gia hạn -->
            <label class="form-label fs-10 fw-bold text-body-emphasis text-uppercase mb-2">Chọn phương thức gia hạn:</label>
            <div class="d-flex flex-column gap-2 mb-3">
              <!-- Option 1: Đến hết hôm nay -->
              <label class="p-3 border border-translucent rounded-3 d-flex align-items-center justify-content-between cursor-pointer">
                <div class="d-flex align-items-center gap-2.5">
                  <input type="radio" name="renew_type" value="today_end" class="form-check-input mt-0" checked onchange="toggleCustomRenewFields('{{ $deal->id }}', false)">
                  <div>
                    <strong class="text-body-emphasis d-block fs-9">⚡ Gia hạn đến hết hôm nay (23:59)</strong>
                    <small class="text-body-tertiary fs-10">Mở bán tiếp tục cho đến hết ngày hôm nay</small>
                  </div>
                </div>
                <span class="badge badge-phoenix badge-phoenix-danger fs-11 fw-bold">Khuyên dùng</span>
              </label>

              <!-- Option 2: Thêm 2 tiếng -->
              <label class="p-3 border border-translucent rounded-3 d-flex align-items-center justify-content-between cursor-pointer">
                <div class="d-flex align-items-center gap-2.5">
                  <input type="radio" name="renew_type" value="plus_hours" class="form-check-input mt-0" onchange="toggleCustomRenewFields('{{ $deal->id }}', false)">
                  <div>
                    <strong class="text-body-emphasis d-block fs-9">⏱️ Gia hạn thêm 2 tiếng</strong>
                    <small class="text-body-tertiary fs-10">Kéo dài thêm 2 giờ Flash Sale tức thì</small>
                  </div>
                </div>
                <span class="badge badge-phoenix badge-phoenix-warning fs-11 fw-bold">+2 Giờ</span>
              </label>

              <!-- Option 3: Sang ngày mai -->
              <label class="p-3 border border-translucent rounded-3 d-flex align-items-center justify-content-between cursor-pointer">
                <div class="d-flex align-items-center gap-2.5">
                  <input type="radio" name="renew_type" value="tomorrow" class="form-check-input mt-0" onchange="toggleCustomRenewFields('{{ $deal->id }}', false)">
                  <div>
                    <strong class="text-body-emphasis d-block fs-9">📅 Gia hạn mở bán sang ngày mai</strong>
                    <small class="text-body-tertiary fs-10">Áp dụng cho ngày mai từ 08:00 đến 22:00</small>
                  </div>
                </div>
                <span class="badge badge-phoenix badge-phoenix-info fs-11 fw-bold">Ngày mai</span>
              </label>

              <!-- Option 4: Tùy chỉnh -->
              <label class="p-3 border border-translucent rounded-3 d-flex align-items-center justify-content-between cursor-pointer">
                <div class="d-flex align-items-center gap-2.5">
                  <input type="radio" name="renew_type" value="custom" class="form-check-input mt-0" onchange="toggleCustomRenewFields('{{ $deal->id }}', true)">
                  <div>
                    <strong class="text-body-emphasis d-block fs-9">⚙️ Tùy chỉnh ngày &amp; khung giờ mới</strong>
                    <small class="text-body-tertiary fs-10">Tự chỉ định ngày và giờ bắt đầu / kết thúc</small>
                  </div>
                </div>
                <span class="badge badge-phoenix badge-phoenix-secondary fs-11">Tùy chọn</span>
              </label>
            </div>

            <!-- Khối tùy chỉnh (Ẩn mặc định) -->
            <div id="customRenewBox_{{ $deal->id }}" class="p-3 bg-body-tertiary rounded-3 border border-translucent mb-3 d-none">
              <div class="mb-2">
                <label class="form-label fs-10 fw-bold text-body-emphasis">Ngày áp dụng:</label>
                <input type="date" name="custom_date" value="{{ now()->toDateString() }}" class="form-control form-control-sm">
              </div>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label fs-10 fw-bold text-body-emphasis">Bắt đầu:</label>
                  <input type="time" name="custom_start" value="{{ now()->format('H:i') }}" class="form-control form-control-sm">
                </div>
                <div class="col-6">
                  <label class="form-label fs-10 fw-bold text-body-emphasis">Kết thúc:</label>
                  <input type="time" name="custom_end" value="23:59" class="form-control form-control-sm">
                </div>
              </div>
            </div>

            <!-- Mức giảm % & Số lượng -->
            <div class="row g-3 mb-3">
              <div class="col-6">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Mức giảm (%):</label>
                <input type="number" name="discount_percent" value="{{ $deal->discount_percent }}" min="1" max="99" class="form-control" required>
              </div>
              <div class="col-6">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Số lượng mở bán:</label>
                <input type="number" name="quantity_limit" value="{{ $deal->quantity_limit }}" min="0" class="form-control">
              </div>
            </div>

            <!-- Reset số lượng đã bán -->
            <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between p-3 bg-body-tertiary rounded-3 border border-translucent">
              <label class="form-check-label fs-9 fw-semibold text-body-emphasis cursor-pointer mb-0" for="resetSold_{{ $deal->id }}">
                Khôi phục số lượng đã bán về 0 (Đã bán: {{ $deal->sold_count }})
              </label>
              <input class="form-check-input ms-2" type="checkbox" name="reset_sold" value="1" id="resetSold_{{ $deal->id }}" checked>
            </div>
          </div>

          <div class="modal-footer border-top border-translucent bg-body-emphasis py-3 px-4">
            <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-warning btn-sm px-3">
              <span class="fa-solid fa-bolt me-1"></span>Xác Nhận Gia Hạn
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- 3. MODAL CHỈNH SỬA ƯU ĐÃI -->
  <div class="modal fade" id="editDealModal_{{ $deal->id }}" tabindex="-1" aria-labelledby="editDealModalLabel_{{ $deal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-bottom border-translucent bg-body-emphasis py-3 px-4">
          <div class="d-flex align-items-center gap-2">
            <span class="badge badge-phoenix badge-phoenix-primary p-2 rounded-circle">
              <span class="fa-solid fa-bolt"></span>
            </span>
            <div>
              <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="editDealModalLabel_{{ $deal->id }}">
                Cập Nhật Ưu Đãi Flash Sale
              </h5>
              <small class="text-body-tertiary">{{ $product->name ?? 'Sản phẩm' }}</small>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>

        <form action="{{ route('admin.daily-deals.update', $deal->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body bg-body p-4">
            <div class="row g-3">
              <!-- Chọn sản phẩm -->
              <div class="col-12">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Sản phẩm áp dụng <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select select-product-edit" data-deal-id="{{ $deal->id }}" required>
                  @foreach($products as $prod)
                    <option value="{{ $prod->id }}" data-price="{{ $prod->price }}" {{ $deal->product_id == $prod->id ? 'selected' : '' }}>
                      [{{ $prod->sku }}] {{ $prod->name }} - Giá gốc: {{ number_format($prod->price, 0, ',', '.') }}₫
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Mức giảm % -->
              <div class="col-md-6">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Mức giảm giá (%) <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="number" name="discount_percent" value="{{ $deal->discount_percent }}" min="1" max="99" class="form-control input-discount-edit" data-deal-id="{{ $deal->id }}" required>
                  <span class="input-group-text bg-body-secondary fw-bold">%</span>
                </div>
              </div>

              <!-- Giới hạn số lượng bán -->
              <div class="col-md-6">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Giới hạn số lượng bán (Suất)</label>
                <input type="number" name="quantity_limit" value="{{ $deal->quantity_limit }}" min="0" class="form-control" placeholder="0 = Không giới hạn">
              </div>

              <!-- Thời gian bắt đầu -->
              <div class="col-md-6">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Giờ bắt đầu trong ngày <span class="text-danger">*</span></label>
                <input type="time" name="start_time" value="{{ substr($deal->start_time, 0, 5) }}" class="form-control" required>
              </div>

              <!-- Thời gian kết thúc -->
              <div class="col-md-6">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Giờ kết thúc trong ngày <span class="text-danger">*</span></label>
                <input type="time" name="end_time" value="{{ substr($deal->end_time, 0, 5) }}" class="form-control" required>
              </div>

              <!-- Ngày áp dụng -->
              <div class="col-md-6">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Ngày diễn ra (Bỏ trống nếu lặp lại hàng ngày)</label>
                <input type="date" name="deal_date" value="{{ $deal->deal_date ? $deal->deal_date->format('Y-m-d') : '' }}" class="form-control">
              </div>

              <!-- Tên khung giờ -->
              <div class="col-md-6">
                <label class="form-label fs-9 fw-bold text-body-emphasis">Tên khung giờ hiển thị</label>
                <input type="text" name="slot_name" value="{{ $deal->slot_name }}" class="form-control" placeholder="Ví dụ: Giờ vàng sáng (08:00 - 12:00)">
              </div>

              <!-- Giá sau giảm dự kiến -->
              <div class="col-12">
                <div class="p-3 bg-body-tertiary rounded-3 border border-translucent d-flex align-items-center justify-content-between">
                  <span class="fs-9 fw-semibold text-body-secondary">Giá sau giảm dự kiến:</span>
                  <span class="fs-7 fw-bold text-danger preview-deal-price-edit-{{ $deal->id }}">
                    {{ number_format($deal->deal_price, 0, ',', '.') }}₫
                  </span>
                </div>
              </div>

              <!-- Trạng thái kích hoạt -->
              <div class="col-12">
                <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between p-3 bg-body-tertiary rounded-3 border border-translucent">
                  <label class="form-check-label fs-9 fw-semibold text-body-emphasis cursor-pointer mb-0" for="editActive_{{ $deal->id }}">
                    Kích hoạt ưu đãi này (Hiển thị cho khách hàng)
                  </label>
                  <input class="form-check-input ms-2" type="checkbox" name="is_active" value="1" id="editActive_{{ $deal->id }}" {{ $deal->is_active ? 'checked' : '' }}>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer border-top border-translucent bg-body-emphasis py-3 px-4">
            <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary btn-sm px-3">
              <span class="fa-solid fa-floppy-disk me-1"></span>Lưu Thay Đổi
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

<!-- ========================================================================= -->
<!-- 4. MODAL TẠO MỚI ƯU ĐÃI TRONG NGÀY (ADD DEAL MODAL) -->
<!-- ========================================================================= -->
<div class="modal fade" id="addDealModal" tabindex="-1" aria-labelledby="addDealModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis py-3 px-4">
        <div class="d-flex align-items-center gap-2">
          <span class="badge badge-phoenix badge-phoenix-danger p-2 rounded-circle">
            <span class="fa-solid fa-bolt"></span>
          </span>
          <div>
            <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="addDealModalLabel">
              Thêm Sản Phẩm Vào ƯU ĐÃI TRONG NGÀY
            </h5>
            <small class="text-body-tertiary">Chọn sản phẩm, cài đặt mức giảm giá (%) và khung giờ vàng áp dụng</small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <form action="{{ route('admin.daily-deals.store') }}" method="POST" id="createDealForm">
        @csrf
        <div class="modal-body bg-body p-4">
          <div class="row g-3">
            <!-- CHỌN SẢN PHẨM -->
            <div class="col-12">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Chọn sản phẩm khuyến mãi <span class="text-danger">*</span></label>
              <select name="product_id" id="selectProductCreate" class="form-select" required>
                <option value="">-- Chọn một sản phẩm trong kho hàng --</option>
                @foreach($products as $prod)
                  <option value="{{ $prod->id }}" data-price="{{ $prod->price }}" data-image="{{ asset($prod->image) }}" data-stock="{{ $prod->stock }}">
                    [{{ $prod->sku }}] {{ $prod->name }} - Giá bán: {{ number_format($prod->price, 0, ',', '.') }}₫ (Tồn kho: {{ $prod->stock }})
                  </option>
                @endforeach
              </select>
            </div>

            <!-- MỨC GIẢM % -->
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Phần trăm giảm giá (%) <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number" name="discount_percent" id="dealDiscountPercent" value="30" min="1" max="99" class="form-control" placeholder="Nhập từ 1 đến 99" required>
                <span class="input-group-text bg-body-secondary fw-bold">%</span>
              </div>
              <div class="d-flex gap-1.5 mt-2 flex-wrap">
                <button type="button" class="btn btn-phoenix-secondary btn-sm px-2 py-0.5 fs-11 quick-discount-btn" data-pct="10">10%</button>
                <button type="button" class="btn btn-phoenix-secondary btn-sm px-2 py-0.5 fs-11 quick-discount-btn" data-pct="20">20%</button>
                <button type="button" class="btn btn-phoenix-danger btn-sm px-2 py-0.5 fs-11 quick-discount-btn active" data-pct="30">30%</button>
                <button type="button" class="btn btn-phoenix-danger btn-sm px-2 py-0.5 fs-11 quick-discount-btn" data-pct="50">50%</button>
                <button type="button" class="btn btn-phoenix-danger btn-sm px-2 py-0.5 fs-11 quick-discount-btn" data-pct="70">70%</button>
              </div>
            </div>

            <!-- SỐ LƯỢNG MỞ BÁN -->
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Giới hạn số lượng suất Deal</label>
              <input type="number" name="quantity_limit" value="50" min="0" class="form-control" placeholder="0 = Không giới hạn suất">
              <div class="form-text fs-11">Khi bán hết số lượng này, sản phẩm sẽ tự động đóng ưu đãi.</div>
            </div>

            <!-- KHUNG GIỜ NHANH PRESETS -->
            <div class="col-12">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Chọn nhanh khung giờ có sẵn:</label>
              <div class="d-flex gap-1.5 flex-wrap">
                <button type="button" class="btn btn-phoenix-secondary btn-sm fs-10 preset-slot-btn" data-start="08:00" data-end="12:00" data-name="Giờ vàng sáng (08:00 - 12:00)">
                  🌅 Sáng (08:00 - 12:00)
                </button>
                <button type="button" class="btn btn-phoenix-secondary btn-sm fs-10 preset-slot-btn" data-start="12:00" data-end="16:00" data-name="Flash Sale trưa (12:00 - 16:00)">
                  ☀️ Trưa (12:00 - 16:00)
                </button>
                <button type="button" class="btn btn-phoenix-secondary btn-sm fs-10 preset-slot-btn" data-start="16:00" data-end="20:00" data-name="Giờ vàng chiều (16:00 - 20:00)">
                  🌇 Chiều (16:00 - 20:00)
                </button>
                <button type="button" class="btn btn-phoenix-secondary btn-sm fs-10 preset-slot-btn" data-start="20:00" data-end="23:59" data-name="Flash Sale tối (20:00 - 23:59)">
                  🌙 Tối (20:00 - 23:59)
                </button>
                <button type="button" class="btn btn-phoenix-danger btn-sm fs-10 preset-slot-btn active" data-start="00:00" data-end="23:59" data-name="Cả ngày (00:00 - 23:59)">
                  ⚡ Cả ngày (00:00 - 23:59)
                </button>
              </div>
            </div>

            <!-- THỜI GIAN BẮT ĐẦU & KẾT THÚC -->
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Bắt đầu lúc (Giờ:Phút) <span class="text-danger">*</span></label>
              <input type="time" name="start_time" id="dealStartTime" value="00:00" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Kết thúc lúc (Giờ:Phút) <span class="text-danger">*</span></label>
              <input type="time" name="end_time" id="dealEndTime" value="23:59" class="form-control" required>
            </div>

            <!-- NGÀY ÁP DỤNG -->
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Ngày áp dụng</label>
              <input type="date" name="deal_date" value="{{ now()->toDateString() }}" class="form-control">
              <div class="form-text fs-11">Để trống nếu bạn muốn ưu đãi này lặp lại đều đặn mỗi ngày.</div>
            </div>

            <!-- TÊN KHUNG GIỜ -->
            <div class="col-md-6">
              <label class="form-label fs-9 fw-bold text-body-emphasis">Tiêu đề / Tên khung giờ</label>
              <input type="text" name="slot_name" id="dealSlotName" value="Cả ngày (00:00 - 23:59)" class="form-control" placeholder="Ví dụ: Giờ vàng sáng (08:00 - 12:00)">
            </div>

            <!-- BẢNG TÍNH NHẨM GIÁ BÁN THỰC TẾ REAL-TIME -->
            <div class="col-12">
              <div class="p-3 bg-body-tertiary rounded-3 border border-translucent" id="createPreviewBox" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fs-9 fw-bold text-body-emphasis">Tính toán giá bán Flash Sale:</span>
                  <span class="badge badge-phoenix badge-phoenix-danger" id="previewDiscountBadge">-30%</span>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <small class="text-body-tertiary d-block fs-11">Giá niêm yết ban đầu:</small>
                    <span class="fw-semibold text-body-tertiary text-decoration-line-through fs-9" id="previewBasePrice">0₫</span>
                  </div>
                  <div class="text-center text-body-tertiary">
                    <span class="fa-solid fa-arrow-right-long fs-8 text-danger"></span>
                  </div>
                  <div class="text-end">
                    <small class="text-body-tertiary d-block fs-11">Giá Flash Sale đến tay khách:</small>
                    <span class="fw-bolder text-danger fs-7" id="previewCalculatedPrice">0₫</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- KÍCH HOẠT -->
            <div class="col-12">
              <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between p-3 bg-body-tertiary rounded-3 border border-translucent">
                <label class="form-check-label fs-9 fw-semibold text-body-emphasis cursor-pointer mb-0" for="dealIsActive">
                  Kích hoạt và mở bán ngay trong khung giờ đã chọn
                </label>
                <input class="form-check-input ms-2" type="checkbox" name="is_active" value="1" id="dealIsActive" checked>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="modal-footer border-top border-translucent bg-body-emphasis py-3 px-4">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger btn-sm px-4">
            <span class="fa-solid fa-bolt me-1"></span>Lưu &amp; Đưa Lên Flash Sale
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // 1. Tính toán giá trực tiếp trong Create Modal
    const productSelect = document.getElementById('selectProductCreate');
    const discountInput = document.getElementById('dealDiscountPercent');
    const previewBox = document.getElementById('createPreviewBox');
    const previewBasePrice = document.getElementById('previewBasePrice');
    const previewDiscountBadge = document.getElementById('previewDiscountBadge');
    const previewCalculatedPrice = document.getElementById('previewCalculatedPrice');

    function updateCreatePreview() {
      if (!productSelect || !discountInput || !productSelect.value) {
        if (previewBox) previewBox.style.display = 'none';
        return;
      }
      const selectedOption = productSelect.options[productSelect.selectedIndex];
      const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
      const discount = parseInt(discountInput.value) || 0;
      const finalPrice = Math.max(0, Math.round(price * (1 - discount / 100)));

      if (previewBox) previewBox.style.display = 'block';
      if (previewBasePrice) previewBasePrice.textContent = new Intl.NumberFormat('vi-VN').format(price) + '₫';
      if (previewDiscountBadge) previewDiscountBadge.textContent = '-' + discount + '%';
      if (previewCalculatedPrice) previewCalculatedPrice.textContent = new Intl.NumberFormat('vi-VN').format(finalPrice) + '₫';
    }

    if (productSelect && discountInput) {
      productSelect.addEventListener('change', updateCreatePreview);
      discountInput.addEventListener('input', updateCreatePreview);
    }

    // Quick discount buttons in Create Modal
    document.querySelectorAll('.quick-discount-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.quick-discount-btn').forEach(b => {
          b.classList.remove('btn-danger', 'btn-primary');
          b.classList.add('btn-phoenix-secondary');
        });
        this.classList.remove('btn-phoenix-secondary');
        this.classList.add('btn-danger');
        const pct = this.getAttribute('data-pct');
        if (discountInput) {
          discountInput.value = pct;
          updateCreatePreview();
        }
      });
    });

    // Preset slot buttons in Create Modal
    document.querySelectorAll('.preset-slot-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        document.querySelectorAll('.preset-slot-btn').forEach(b => {
          b.classList.remove('btn-danger');
          b.classList.add('btn-phoenix-secondary');
        });
        this.classList.remove('btn-phoenix-secondary');
        this.classList.add('btn-danger');
        const start = this.getAttribute('data-start');
        const end = this.getAttribute('data-end');
        const name = this.getAttribute('data-name');

        const startEl = document.getElementById('dealStartTime');
        const endEl = document.getElementById('dealEndTime');
        const nameEl = document.getElementById('dealSlotName');

        if (startEl) startEl.value = start;
        if (endEl) endEl.value = end;
        if (nameEl) nameEl.value = name;
      });
    });

    // 2. Tính toán giá trực tiếp trong Edit Modals
    document.querySelectorAll('.select-product-edit').forEach(sel => {
      sel.addEventListener('change', function () {
        const dealId = this.getAttribute('data-deal-id');
        updateEditPreview(dealId);
      });
    });

    document.querySelectorAll('.input-discount-edit').forEach(inp => {
      inp.addEventListener('input', function () {
        const dealId = this.getAttribute('data-deal-id');
        updateEditPreview(dealId);
      });
    });

    function updateEditPreview(dealId) {
      const sel = document.querySelector(`.select-product-edit[data-deal-id="${dealId}"]`);
      const inp = document.querySelector(`.input-discount-edit[data-deal-id="${dealId}"]`);
      const previewEl = document.querySelector(`.preview-deal-price-edit-${dealId}`);

      if (sel && inp && previewEl) {
        const selectedOption = sel.options[sel.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const discount = parseInt(inp.value) || 0;
        const finalPrice = Math.max(0, Math.round(price * (1 - discount / 100)));
        previewEl.textContent = new Intl.NumberFormat('vi-VN').format(finalPrice) + '₫';
      }
    }

    // 3. Ẩn / Hiện form tùy chỉnh trong Modal Gia Hạn
    window.toggleCustomRenewFields = function (dealId, isCustom) {
      const customBox = document.getElementById('customRenewBox_' + dealId);
      if (customBox) {
        if (isCustom) {
          customBox.classList.remove('d-none');
        } else {
          customBox.classList.add('d-none');
        }
      }
    };
  });
</script>
@endpush
@endsection