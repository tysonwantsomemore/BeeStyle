@extends('layouts.admin')

@section('title', 'Quản Lý Ưu Đãi Trong Ngày (Daily Deals) | BeeStyle Admin')

@section('content')
<div class="container-fluid px-0">

  <!-- TOP HEADER -->
  <div class="mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge bg-danger text-white fw-bold px-2.5 py-1 rounded-pill shadow-xs">
            <i class="fa-solid fa-bolt me-1"></i> FLASH SALE
          </span>
          <h3 class="fw-bold text-dark mb-0 font-heading">Quản Lý Ưu Đãi Trong Ngày</h3>
        </div>
        <p class="text-muted small mb-0">Cấu hình sản phẩm khuyến mãi chớp nhoáng theo khung giờ vàng, theo dõi doanh thu và tài khoản khách mua</p>
      </div>
      <div class="d-flex align-items-center gap-2 flex-wrap">
        @if($statusFilter === 'running')
          <a href="{{ route('client.daily-deals.index', ['tab' => 'running']) }}" target="_blank" class="btn btn-danger btn-sm px-3 fw-bold rounded-pill shadow-xs">
            <i class="fa-solid fa-bolt me-1.5"></i> Xem Khách Thấy ({{ $runningDealsCount }} SP Đang Chạy) <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
        @elseif($statusFilter === 'today' || $dateFilter === 'today')
          <a href="{{ route('client.daily-deals.index', ['tab' => 'all']) }}" target="_blank" class="btn btn-warning text-dark btn-sm px-3 fw-bold rounded-pill shadow-xs">
            <i class="fa-solid fa-clock me-1.5"></i> Xem Khách Thấy ({{ $todayDealsCount }} Ưu Đãi Hôm Nay) <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
        @else
          <a href="{{ route('client.daily-deals.index') }}" target="_blank" class="btn btn-outline-dark btn-sm px-3 fw-semibold rounded-pill">
            <i class="fa-solid fa-store me-1.5"></i> Xem Trang Ưu Đãi Khách Hàng <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
          </a>
        @endif
        <a href="{{ route('client.home') }}#flash-sale" target="_blank" class="btn btn-outline-secondary btn-sm px-3 fw-semibold rounded-pill">
          <i class="fa-solid fa-eye me-1.5"></i> Xem Trang Chủ
        </a>
        <button type="button" class="btn btn-danger btn-sm px-3.5 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#addDealModal" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
          <i class="fa-solid fa-plus me-1.5"></i> Thêm Ưu Đãi Mới
        </button>
      </div>
    </div>
  </div>

  <!-- 4 THẺ THỐNG KÊ TỔNG QUAN (CLICKABLE KPI CARDS) -->
  <div class="row g-3 mb-4">
    
    <!-- CARD 1: ĐANG CHẠY LÚC NÀY -->
    <div class="col-6 col-lg-3">
      <a href="{{ route('admin.daily-deals.index', ['status' => 'running']) }}" class="text-decoration-none d-block h-100">
        <div class="card border-0 shadow-sm p-3.5 h-100 position-relative deal-stat-card {{ $statusFilter === 'running' ? 'active-stat-card border-danger' : '' }}" style="border-radius: 16px; background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%); border-left: 5px solid #ef4444 !important; cursor: pointer;">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Đang Chạy Lúc Này</p>
              <div class="d-flex align-items-baseline gap-1.5">
                <h3 class="fw-black text-danger mb-0">{{ $runningDealsCount }}</h3>
                <span class="small text-muted">sản phẩm</span>
              </div>
            </div>
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.12); color: #ef4444;">
              <i class="fa-solid fa-fire fs-5 {{ $runningDealsCount > 0 ? 'animate-pulse' : '' }}"></i>
            </div>
          </div>
          <div class="mt-3 pt-2 border-top border-danger border-opacity-10 d-flex align-items-center justify-content-between text-danger small fw-bold" style="font-size: 0.75rem;">
            <span>{{ $statusFilter === 'running' ? '● Đang lọc mục này' : 'Bấm xem danh sách' }}</span>
            <i class="fa-solid fa-arrow-right"></i>
          </div>
        </div>
      </a>
    </div>

    <!-- CARD 2: ƯU ĐÃI HÔM NAY -->
    <div class="col-6 col-lg-3">
      <a href="{{ route('admin.daily-deals.index', ['status' => 'today']) }}" class="text-decoration-none d-block h-100">
        <div class="card border-0 shadow-sm p-3.5 h-100 position-relative deal-stat-card {{ ($statusFilter === 'today' || ($dateFilter === 'today' && $statusFilter === 'all')) ? 'active-stat-card border-warning' : '' }}" style="border-radius: 16px; background: linear-gradient(135deg, #fffdf2 0%, #ffffff 100%); border-left: 5px solid #f59e0b !important;">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Ưu Đãi Hôm Nay</p>
              <div class="d-flex align-items-baseline gap-1.5">
                <h3 class="fw-black text-warning mb-0">{{ $todayDealsCount }}</h3>
                <span class="small text-muted">chiến dịch</span>
              </div>
            </div>
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
              <i class="fa-solid fa-clock fs-5"></i>
            </div>
          </div>
          <div class="mt-3 pt-2 border-top border-warning border-opacity-10 d-flex align-items-center justify-content-between text-warning-emphasis small fw-bold" style="font-size: 0.75rem;">
            <span>{{ ($statusFilter === 'today' || ($dateFilter === 'today' && $statusFilter === 'all')) ? '● Đang lọc mục này' : 'Bấm xem danh sách' }}</span>
            <i class="fa-solid fa-arrow-right"></i>
          </div>
        </div>
      </a>
    </div>

    <!-- CARD 3: ĐÃ BÁN QUA DEAL -->
    <div class="col-6 col-lg-3">
      <a href="{{ route('admin.daily-deals.index', ['status' => 'sold']) }}" class="text-decoration-none d-block h-100">
        <div class="card border-0 shadow-sm p-3.5 h-100 position-relative deal-stat-card {{ $statusFilter === 'sold' ? 'active-stat-card border-success' : '' }}" style="border-radius: 16px; background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border-left: 5px solid #10b981 !important;">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Đã Bán Qua Deal</p>
              <div class="d-flex align-items-baseline gap-1.5">
                <h3 class="fw-black text-success mb-0">{{ number_format($totalSoldInDeals) }}</h3>
                <span class="small text-muted">sản phẩm</span>
              </div>
              <div class="small fw-bold text-success-emphasis mt-0.5" style="font-size: 0.78rem;">
                <i class="fa-solid fa-coins me-0.5"></i> {{ number_format($totalDealRevenue ?? 0, 0, ',', '.') }}₫
              </div>
            </div>
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.12); color: #059669;">
              <i class="fa-solid fa-bag-shopping fs-5"></i>
            </div>
          </div>
          <div class="mt-2.5 pt-2 border-top border-success border-opacity-10 d-flex align-items-center justify-content-between text-success small fw-bold" style="font-size: 0.75rem;">
            <span>{{ $statusFilter === 'sold' ? '● Đang xem doanh số' : 'Bấm xem chi tiết đã bán' }}</span>
            <i class="fa-solid fa-arrow-right"></i>
          </div>
        </div>
      </a>
    </div>

    <!-- CARD 4: TỔNG CHIẾN DỊCH -->
    <div class="col-6 col-lg-3">
      <a href="{{ route('admin.daily-deals.index', ['status' => 'all']) }}" class="text-decoration-none d-block h-100">
        <div class="card border-0 shadow-sm p-3.5 h-100 position-relative deal-stat-card {{ ($statusFilter === 'all' && empty($dateFilter) && empty($search)) ? 'active-stat-card border-secondary' : '' }}" style="border-radius: 16px; background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border-left: 5px solid #64748b !important;">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Tổng Chiến Dịch</p>
              <div class="d-flex align-items-baseline gap-1.5">
                <h3 class="fw-black text-dark mb-0">{{ $totalDeals }}</h3>
                <span class="small text-muted">tất cả</span>
              </div>
            </div>
            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center shadow-xs" style="width: 48px; height: 48px; background: rgba(100, 116, 139, 0.12); color: #475569;">
              <i class="fa-solid fa-tags fs-5"></i>
            </div>
          </div>
          <div class="mt-3 pt-2 border-top border-secondary border-opacity-10 d-flex align-items-center justify-content-between text-secondary small fw-bold" style="font-size: 0.75rem;">
            <span>{{ ($statusFilter === 'all' && empty($dateFilter) && empty($search)) ? '● Đang xem tất cả' : 'Bấm xem tất cả' }}</span>
            <i class="fa-solid fa-arrow-right"></i>
          </div>
        </div>
      </a>
    </div>

  </div>

  <!-- TOOLBAR & BỘ LỌC TÌM KIẾM -->
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      
      <!-- QUICK PILLS FILTER BAR -->
      <div class="d-flex align-items-center gap-1.5 overflow-auto pb-2.5 mb-3 border-bottom">
        <span class="small fw-bold text-muted text-nowrap me-1">Bộ lọc nhanh:</span>
        <a href="{{ route('admin.daily-deals.index', ['status' => 'all']) }}" class="badge {{ $statusFilter === 'all' && empty($dateFilter) ? 'bg-dark text-white' : 'bg-light text-secondary border' }} text-decoration-none px-3 py-2 rounded-pill fw-semibold fs-10 text-nowrap">
          Tất Cả ({{ $totalDeals }})
        </a>
        <a href="{{ route('admin.daily-deals.index', ['status' => 'running']) }}" class="badge {{ $statusFilter === 'running' ? 'bg-danger text-white' : 'bg-light text-secondary border' }} text-decoration-none px-3 py-2 rounded-pill fw-semibold fs-10 text-nowrap">
          ⚡ Đang Diễn Ra ({{ $runningDealsCount }})
        </a>
        <a href="{{ route('admin.daily-deals.index', ['status' => 'today']) }}" class="badge {{ $statusFilter === 'today' || $dateFilter === 'today' ? 'bg-warning text-dark' : 'bg-light text-secondary border' }} text-decoration-none px-3 py-2 rounded-pill fw-semibold fs-10 text-nowrap">
          📅 Áp Dụng Hôm Nay ({{ $todayDealsCount }})
        </a>
        <a href="{{ route('admin.daily-deals.index', ['status' => 'sold']) }}" class="badge {{ $statusFilter === 'sold' ? 'bg-success text-white' : 'bg-light text-secondary border' }} text-decoration-none px-3 py-2 rounded-pill fw-semibold fs-10 text-nowrap">
          🔥 Đã Bán &amp; Doanh Thu
        </a>
        <a href="{{ route('admin.daily-deals.index', ['status' => 'upcoming']) }}" class="badge {{ $statusFilter === 'upcoming' ? 'bg-info text-dark' : 'bg-light text-secondary border' }} text-decoration-none px-3 py-2 rounded-pill fw-semibold fs-10 text-nowrap">
          ⏳ Sắp Diễn Ra
        </a>
        <a href="{{ route('admin.daily-deals.index', ['status' => 'inactive']) }}" class="badge {{ $statusFilter === 'inactive' ? 'bg-secondary text-white' : 'bg-light text-secondary border' }} text-decoration-none px-3 py-2 rounded-pill fw-semibold fs-10 text-nowrap">
          ⏸️ Đã Tạm Dừng
        </a>
      </div>

      <!-- FORM SEARCH & SELECT CONTROLS -->
      <form action="{{ route('admin.daily-deals.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0" placeholder="Tìm theo tên sản phẩm hoặc mã SKU...">
            @if($search)
              <a href="{{ route('admin.daily-deals.index', ['status' => $statusFilter]) }}" class="input-group-text bg-white border-start-0 text-muted" title="Xóa tìm kiếm"><i class="fa-solid fa-xmark"></i></a>
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
          <button type="submit" class="btn btn-dark btn-sm w-100 fw-semibold">Lọc dữ liệu</button>
          @if($search || $statusFilter !== 'all' || $dateFilter)
            <a href="{{ route('admin.daily-deals.index') }}" class="btn btn-outline-secondary btn-sm" title="Đặt lại bộ lọc"><i class="fa-solid fa-rotate-left"></i></a>
          @endif
        </div>
      </form>
    </div>
  </div>

  <!-- BANNER THÔNG BÁO BỘ LỌC ĐANG CHẠY & ĐỒNG BỘ TRANG KHÁCH -->
  @if($statusFilter !== 'all' || $dateFilter || $search)
    <div class="alert {{ $statusFilter === 'running' ? 'alert-danger bg-danger-subtle border-danger-subtle' : ($statusFilter === 'today' ? 'alert-warning bg-warning-subtle border-warning-subtle' : 'alert-light border') }} shadow-xs d-flex align-items-center justify-content-between py-2.5 px-3.5 mb-3 rounded-3 flex-wrap gap-2">
      <div class="d-flex align-items-center gap-2 small flex-wrap">
        <i class="fa-solid {{ $statusFilter === 'running' ? 'fa-bolt text-danger' : ($statusFilter === 'today' ? 'fa-clock text-warning' : 'fa-filter text-primary') }} fs-6"></i>
        <span class="fw-bold text-dark">Đang hiển thị danh sách:</span>
        @if($statusFilter === 'running')
          <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-fire me-1"></i> Đang Chạy Lúc Này (Trong khung giờ vàng)</span>
          <span class="text-danger fw-bold ms-1">● Đồng bộ: Khách hàng đang nhìn thấy đúng {{ $deals->total() }} sản phẩm này trên Web!</span>
        @elseif($statusFilter === 'today' || $dateFilter === 'today')
          <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-clock me-1"></i> Ưu Đãi Hôm Nay</span>
          <span class="text-dark fw-semibold ms-1">● Khách hàng đang có thể xem toàn bộ {{ $deals->total() }} chiến dịch của ngày hôm nay.</span>
        @elseif($statusFilter === 'sold')
          <span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-bag-shopping me-1"></i> Đã Bán Qua Deal (Xếp theo lượt bán nhiều nhất)</span>
        @elseif($statusFilter === 'upcoming')
          <span class="badge bg-info text-dark px-2 py-1"><i class="fa-solid fa-hourglass-start me-1"></i> Sắp Diễn Ra</span>
        @elseif($statusFilter === 'inactive')
          <span class="badge bg-secondary text-white px-2 py-1"><i class="fa-solid fa-pause me-1"></i> Đã Tạm Dừng</span>
        @endif

        @if($search)
          <span class="badge bg-light text-dark border px-2 py-1">Từ khóa: "{{ $search }}"</span>
        @endif

        <span class="text-muted fw-medium">({{ $deals->total() }} chiến dịch)</span>
      </div>
      <div class="d-flex align-items-center gap-2">
        @if($statusFilter === 'running')
          <a href="{{ route('client.daily-deals.index', ['tab' => 'running']) }}" target="_blank" class="btn btn-danger btn-sm py-1 px-3 fw-bold rounded-pill" style="font-size: 0.75rem;">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Mở Trang Khách Đang Thấy
          </a>
        @elseif($statusFilter === 'today' || $dateFilter === 'today')
          <a href="{{ route('client.daily-deals.index', ['tab' => 'all']) }}" target="_blank" class="btn btn-warning text-dark btn-sm py-1 px-3 fw-bold rounded-pill" style="font-size: 0.75rem;">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Mở Trang Khách Hôm Nay
          </a>
        @endif
        <a href="{{ route('admin.daily-deals.index') }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 fw-bold rounded-pill" style="font-size: 0.75rem;">
          <i class="fa-solid fa-xmark me-1"></i> Bỏ lọc
        </a>
      </div>
    </div>
  @endif

  <!-- BẢNG DANH SÁCH CHIẾN DỊCH ƯU ĐÃI -->
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light text-muted small fw-bold text-uppercase" style="font-size: 0.74rem; letter-spacing: 0.5px;">
          <tr>
            <th class="ps-4 py-3">Sản Phẩm Áp Dụng</th>
            <th class="py-3">Mức Giảm</th>
            <th class="py-3">Khung Giờ Vàng</th>
            <th class="py-3">Ngày Áp Dụng</th>
            <th class="py-3">Tiến Độ &amp; Doanh Thu</th>
            <th class="py-3">Đánh Giá</th>
            <th class="py-3">Trạng Thái &amp; Trang Khách</th>
            <th class="pe-4 py-3 text-end">Hành Động</th>
          </tr>
        </thead>
        <tbody>
          @forelse($deals as $deal)
            @php
              $product = $deal->product;
              $isRunning = $deal->is_running;
              $dealRevenue = $deal->sold_count * $deal->deal_price;
              $avgRating = $product ? round($product->reviews->avg('rating') ?: 5, 1) : 5.0;
              $revCount = $product ? $product->allReviews->count() : 0;
            @endphp
            <tr class="bg-white" style="background-color: #ffffff !important;">
              
              <!-- 1. SẢN PHẨM ÁP DỤNG -->
              <td class="ps-4 py-3 bg-white" style="background-color: #ffffff !important;">
                <div class="d-flex align-items-center gap-3">
                  <div class="position-relative bg-white rounded-3 p-0.5 border shadow-2xs" style="width: 54px; height: 54px; min-width: 54px; background-color: #ffffff !important;">
                    @if($product && $product->image)
                      <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 rounded-2 object-fit-cover" style="background-color: #ffffff;">
                    @else
                      <div class="w-100 h-100 rounded-2 bg-white d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-shirt text-muted"></i>
                      </div>
                    @endif
                    @if($isRunning)
                      <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-danger border border-light rounded-circle animate-pulse" title="Đang mở bán trực tiếp trên Web"></span>
                    @endif
                  </div>
                  <div>
                    <div class="fw-bold text-dark small mb-0.5" style="max-width: 280px;">
                      @if($product)
                        <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="text-dark text-decoration-none hover-primary">
                          {{ $product->name }}
                        </a>
                      @else
                        <span class="text-danger">[Sản phẩm đã bị xóa]</span>
                      @endif
                    </div>
                    <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.75rem;">
                      <span>SKU: <strong class="text-secondary font-monospace">{{ $product->sku ?? 'N/A' }}</strong></span>
                      <span>•</span>
                      <span class="badge bg-light text-dark border fs-11 px-1.5 py-0.5">{{ $product->category->name ?? 'Áo Nam' }}</span>
                    </div>
                  </div>
                </div>
              </td>

              <!-- 2. MỨC GIẢM GIÁ & GIÁ BÁN -->
              <td class="py-3">
                <div class="d-flex flex-column gap-1">
                  <div>
                    <span class="badge bg-danger fw-bold fs-9 px-2.5 py-1 rounded-pill shadow-2xs">
                      <i class="fa-solid fa-arrow-down me-0.5"></i> Giảm {{ $deal->discount_percent }}%
                    </span>
                  </div>
                  <div class="d-flex align-items-baseline gap-1.5">
                    <strong class="text-danger fw-black fs-8">{{ number_format($deal->deal_price, 0, ',', '.') }}₫</strong>
                    @if($product && $product->price > $deal->deal_price)
                      <small class="text-muted text-decoration-line-through fs-10">{{ number_format($product->price, 0, ',', '.') }}₫</small>
                    @endif
                  </div>
                </div>
              </td>

              <!-- 3. KHUNG GIỜ VÀNG -->
              <td class="py-3">
                <div class="d-flex align-items-center gap-1.5">
                  <span class="badge bg-warning-subtle text-dark border border-warning fw-bold fs-9 px-2.5 py-1">
                    <i class="fa-regular fa-clock me-1 text-warning"></i>
                    {{ substr($deal->start_time, 0, 5) }} - {{ substr($deal->end_time, 0, 5) }}
                  </span>
                </div>
                @if($deal->slot_name && $deal->slot_name !== (substr($deal->start_time, 0, 5) . ' - ' . substr($deal->end_time, 0, 5)))
                  <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">{{ $deal->slot_name }}</small>
                @endif
              </td>

              <!-- 4. NGÀY ÁP DỤNG -->
              <td class="py-3">
                @if($deal->deal_date)
                  <span class="badge bg-light text-dark border fw-medium px-2.5 py-1">
                    <i class="fa-regular fa-calendar me-1 text-primary"></i>
                    {{ $deal->deal_date->format('d/m/Y') }}
                  </span>
                  @if($deal->deal_date->isToday())
                    <span class="badge bg-success-subtle text-success fs-10 px-1.5 py-0.5 rounded-pill d-block mt-1 text-center" style="width: fit-content;">Hôm nay</span>
                  @endif
                @else
                  <span class="badge bg-info-subtle text-info border border-info fw-bold px-2.5 py-1">
                    <i class="fa-solid fa-repeat me-1"></i> Hàng ngày
                  </span>
                @endif
              </td>

              <!-- 5. TIẾN ĐỘ BÁN & DOANH THU -->
              <td class="py-3">
                <div class="d-flex flex-column gap-1" style="min-width: 130px;">
                  <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                    <span>Đã bán: <strong class="text-dark">{{ $deal->sold_count }}</strong></span>
                    <span>{{ $deal->quantity_limit > 0 ? '/ ' . $deal->quantity_limit : '(KGH)' }}</span>
                  </div>
                  <div class="progress" style="height: 6px; border-radius: 99px; background: #fee2e2;">
                    @php
                      $pct = $deal->quantity_limit > 0 ? min(100, round(($deal->sold_count / $deal->quantity_limit) * 100)) : 100;
                    @endphp
                    <div class="progress-bar bg-danger" style="width: {{ $pct }}%"></div>
                  </div>
                  <div class="mt-0.5">
                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold" style="font-size: 0.72rem;">
                      <i class="fa-solid fa-coins me-0.5"></i> {{ number_format($dealRevenue, 0, ',', '.') }}₫
                    </span>
                  </div>
                </div>
              </td>

              <!-- 6. ĐÁNH GIÁ SẢN PHẨM -->
              <td class="py-3">
                <div class="d-flex flex-column">
                  <div class="text-warning small mb-0.5">
                    <i class="fa-solid fa-star"></i>
                    <strong class="text-dark ms-1">{{ $avgRating }}</strong>
                    <span class="text-muted" style="font-size: 0.7rem;">/ 5</span>
                  </div>
                  <span class="text-muted" style="font-size: 0.75rem;">
                    {{ $revCount }} đánh giá
                  </span>
                </div>
              </td>

              <!-- 7. TRẠNG THÁI & HIỂN THỊ TRÊN TRANG KHÁCH -->
              <td class="py-3">
                @if(!$deal->is_active)
                  <span class="badge bg-secondary-subtle text-muted fw-bold py-1 px-2.5 rounded-pill">
                    <i class="fa-solid fa-pause me-1"></i> Đang tạm dừng
                  </span>
                @elseif($isRunning)
                  <span class="badge bg-danger text-white fw-bold py-1 px-2.5 rounded-pill shadow-xs animate-pulse">
                    <i class="fa-solid fa-bolt me-1"></i> Đang mở bán trực tiếp
                  </span>
                @else
                  <span class="badge {{ $deal->status_badge_class }} fw-bold py-1 px-2.5 rounded-pill">
                    {{ $deal->status_label }}
                  </span>
                @endif
                <div class="small text-muted mt-1" style="font-size: 0.72rem;">
                  @if($isRunning)
                    <span class="text-success fw-bold"><i class="fa-solid fa-circle-check me-0.5"></i> Khách đang thấy</span>
                  @else
                    <span class="text-muted"><i class="fa-solid fa-circle-minus me-0.5"></i> Chưa/Không hiện</span>
                  @endif
                  • Còn {{ $deal->quantity_limit > 0 ? max(0, $deal->quantity_limit - $deal->sold_count) : '∞' }} suất
                </div>
              </td>

              <!-- 8. HÀNH ĐỘNG -->
              <td class="pe-4 py-3 text-end">
                <div class="d-flex align-items-center justify-content-end gap-1.5">
                  <!-- Xem Trực Tiếp Trên Trang Khách -->
                  <a href="{{ route('client.daily-deals.index') }}#deal-card-{{ $deal->id }}" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2.5 fw-semibold rounded-pill" title="Mở và kiểm tra vị trí sản phẩm trên giao diện khách hàng">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Xem Web
                  </a>

                  <!-- Nút Báo Cáo & Khách Mua -->
                  <button type="button" class="btn btn-sm btn-primary text-white py-1 px-2.5 fw-bold shadow-xs rounded-pill" data-bs-toggle="modal" data-bs-target="#salesAnalyticsModal_{{ $deal->id }}" title="Xem chi tiết doanh thu, khách hàng đã mua &amp; đánh giá">
                    <i class="fa-solid fa-chart-pie me-1"></i> Báo cáo
                  </button>

                  <!-- Nút Sửa (Chỉnh sửa ngay) -->
                  <button type="button" class="btn btn-sm btn-outline-dark py-1 px-2 rounded-circle" data-bs-toggle="modal" data-bs-target="#editDealModal_{{ $deal->id }}" title="Chỉnh sửa thông số ưu đãi">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </button>

                  <!-- Nút Gia Hạn Ưu Đãi -->
                  <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2 fw-bold rounded-circle" data-bs-toggle="modal" data-bs-target="#renewDealModal_{{ $deal->id }}" title="Gia hạn thêm thời gian ưu đãi">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                  </button>

                  <!-- Toggle Bật/Tắt -->
                  <form action="{{ route('admin.daily-deals.toggle', $deal->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $deal->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} py-1 px-2 rounded-circle" title="{{ $deal->is_active ? 'Tạm dừng ưu đãi (Ẩn khỏi trang khách)' : 'Kích hoạt ưu đãi (Hiện trên trang khách)' }}">
                      <i class="fa-solid {{ $deal->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                    </button>
                  </form>

                  <!-- Xóa -->
                  <form action="{{ route('admin.daily-deals.destroy', $deal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn gỡ sản phẩm này khỏi danh sách Ưu Đãi Trong Ngày?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 rounded-circle" title="Xóa ưu đãi">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-5">
                <div class="py-4">
                  <div class="rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-2" style="width: 64px; height: 64px; background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="fa-solid fa-bolt fs-2"></i>
                  </div>
                  <h6 class="fw-bold text-dark mb-1">Chưa có chương trình ưu đãi nào phù hợp với bộ lọc</h6>
                  <p class="small text-muted mb-3">Hãy chọn mục khác hoặc bấm "Thêm Ưu Đãi Mới" để lên lịch khuyến mãi trong ngày.</p>
                  <button type="button" class="btn btn-danger btn-sm px-3.5 fw-bold rounded-pill shadow-xs" data-bs-toggle="modal" data-bs-target="#addDealModal">
                    <i class="fa-solid fa-plus me-1"></i> Tạo Ưu Đãi Ngay
                  </button>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- PHÂN TRANG -->
    @if($deals->hasPages())
      <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">
          Hiển thị từ {{ $deals->firstItem() }} đến {{ $deals->lastItem() }} trong tổng số {{ $deals->total() }} ưu đãi
        </small>
        <div>
          {{ $deals->links() }}
        </div>
      </div>
    @endif
  </div>

</div>

<!-- ========================================================================= -->
<!-- TOÀN BỘ MODALS ĐƯỢC ĐẶT Ở NGOÀI BẢNG ĐỂ TRÁNH LỖI GIAO DIỆN & BACKDROP -->
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

  <!-- 1. MODAL BÁO CÁO DOANH THU & KHÁCH HÀNG ĐÃ MUA & ĐÁNH GIÁ (SALES ANALYTICS MODAL) -->
  <div class="modal fade" id="salesAnalyticsModal_{{ $deal->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        
        <!-- MODAL HEADER -->
        <div class="modal-header border-bottom py-3.5 px-4 bg-light">
          <div class="d-flex align-items-center gap-3">
            <div class="position-relative" style="width: 52px; height: 52px; min-width: 52px;">
              <img src="{{ asset($product->image ?? 'assets/img/team/40x40/58.webp') }}" alt="{{ $product->name ?? 'SP' }}" class="w-100 h-100 rounded-3 object-fit-cover border shadow-xs">
              <span class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill fs-11">
                -{{ $deal->discount_percent }}%
              </span>
            </div>
            <div>
              <div class="d-flex align-items-center gap-2 flex-wrap">
                <h5 class="modal-title fw-bold text-dark mb-0">{{ $product->name ?? 'Sản phẩm' }}</h5>
                <span class="badge {{ $deal->status_badge_class }} fs-10 px-2 py-0.5 rounded-pill">{{ $deal->status_label }}</span>
              </div>
              <div class="small text-muted d-flex align-items-center gap-2 mt-1 flex-wrap">
                <span>Mã SKU: <strong class="text-dark font-monospace">{{ $product->sku ?? 'N/A' }}</strong></span>
                <span>•</span>
                <span>Khung giờ: <strong class="text-dark">{{ substr($deal->start_time, 0, 5) }} - {{ substr($deal->end_time, 0, 5) }}</strong></span>
                <span>•</span>
                <span>Giá Flash Sale: <strong class="text-danger fw-black">{{ number_format($deal->deal_price, 0, ',', '.') }}₫</strong> (Giá gốc: {{ number_format($product->price, 0, ',', '.') }}₫)</span>
              </div>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- MODAL BODY -->
        <div class="modal-body p-4">
          
          <!-- 3 THẺ TỔNG KẾT DOANH THU & HIỆU QUẢ -->
          <div class="row g-3 mb-4">
            <!-- Doanh thu đạt được -->
            <div class="col-md-4">
              <div class="p-3.5 rounded-3 border h-100 shadow-2xs" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border-left: 4px solid #10b981 !important;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <span class="small text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Tổng Doanh Thu Deal</span>
                  <span class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: rgba(16, 185, 129, 0.12); color: #059669; width: 34px; height: 34px;"><i class="fa-solid fa-coins"></i></span>
                </div>
                <h4 class="fw-black text-success mb-1">{{ number_format($dealRevenue, 0, ',', '.') }}₫</h4>
                <small class="text-muted" style="font-size: 0.75rem;">
                  Tiết kiệm cho khách: <strong class="text-danger">{{ number_format(max(0, ($product->price - $deal->deal_price) * $deal->sold_count), 0, ',', '.') }}₫</strong>
                </small>
              </div>
            </div>

            <!-- Lượng hàng đã bán -->
            <div class="col-md-4">
              <div class="p-3.5 rounded-3 border h-100 shadow-2xs" style="background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%); border-left: 4px solid #ef4444 !important;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <span class="small text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Số Lượng Đã Bán</span>
                  <span class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: rgba(239, 68, 68, 0.12); color: #ef4444; width: 34px; height: 34px;"><i class="fa-solid fa-bag-shopping"></i></span>
                </div>
                <h4 class="fw-black text-danger mb-1">{{ $deal->sold_count }} <span class="fs-6 text-muted fw-normal">/ {{ $deal->quantity_limit ?: 'Không giới hạn' }}</span></h4>
                <div class="progress mt-1.5" style="height: 5px; border-radius: 99px; background: #fee2e2;">
                  <div class="progress-bar bg-danger" style="width: {{ $pct }}%"></div>
                </div>
              </div>
            </div>

            <!-- Điểm đánh giá -->
            <div class="col-md-4">
              <div class="p-3.5 rounded-3 border h-100 shadow-2xs" style="background: linear-gradient(135deg, #fffdf2 0%, #ffffff 100%); border-left: 4px solid #f59e0b !important;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <span class="small text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Đánh Giá Trung Bình</span>
                  <span class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background: rgba(245, 158, 11, 0.12); color: #d97706; width: 34px; height: 34px;"><i class="fa-solid fa-star"></i></span>
                </div>
                <h4 class="fw-black text-warning mb-1">{{ $avgRating }} <span class="fs-6 text-muted fw-normal">/ 5.0 ⭐</span></h4>
                <small class="text-muted" style="font-size: 0.75rem;">
                  Tổng cộng <strong class="text-dark">{{ $revCount }}</strong> lượt đánh giá của khách
                </small>
              </div>
            </div>
          </div>

          <!-- TABS ĐIỀU HƯỚNG: KHÁCH HÀNG ĐÃ MUA & ĐÁNH GIÁ -->
          <ul class="nav nav-tabs border-bottom mb-3 fw-bold small" id="analyticsTab_{{ $deal->id }}" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active py-2.5 px-3.5 text-dark border-0 border-bottom border-2 border-danger" id="orders-tab-{{ $deal->id }}" data-bs-toggle="tab" data-bs-target="#orders-pane-{{ $deal->id }}" type="button" role="tab">
                <i class="fa-solid fa-users text-danger me-1.5"></i> Tài Khoản Khách Hàng Đã Mua ({{ $orderItems->count() }})
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link py-2.5 px-3.5 text-secondary border-0" id="reviews-tab-{{ $deal->id }}" data-bs-toggle="tab" data-bs-target="#reviews-pane-{{ $deal->id }}" type="button" role="tab">
                <i class="fa-solid fa-star text-warning me-1.5"></i> Đánh Giá Của Khách Hàng ({{ $revCount }})
              </button>
            </li>
          </ul>

          <!-- TAB CONTENT -->
          <div class="tab-content" id="analyticsTabContent_{{ $deal->id }}">
            
            <!-- TAB 1: KHÁCH HÀNG ĐÃ MUA -->
            <div class="tab-pane fade show active" id="orders-pane-{{ $deal->id }}" role="tabpanel">
              @if($orderItems->isNotEmpty())
                <div class="table-responsive border rounded-3">
                  <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light text-muted fw-bold text-uppercase" style="font-size: 0.72rem;">
                      <tr>
                        <th class="ps-3">Khách Hàng</th>
                        <th>Mã Đơn Hàng</th>
                        <th>Phân Loại</th>
                        <th>Số Lượng &amp; Đơn Giá</th>
                        <th>Thành Tiền</th>
                        <th>Thời Gian Đặt</th>
                        <th>Trạng Thái Giao</th>
                        <th class="pe-3 text-end">Chi Tiết</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($orderItems as $item)
                        @php
                          $order = $item->order;
                          $user = $order ? $order->user : null;
                        @endphp
                        <tr>
                          <!-- Khách Hàng -->
                          <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                              <div class="rounded-circle bg-danger bg-opacity-10 border border-danger-subtle d-flex align-items-center justify-content-center fw-bold text-danger" style="width: 36px; height: 36px; min-width: 36px; font-size: 0.82rem;">
                                {{ mb_substr($order->customer_name ?? ($user->name ?? 'KH'), 0, 1) }}
                              </div>
                              <div>
                                <div class="fw-bold text-dark">{{ $order->customer_name ?? ($user->name ?? 'Khách vãng lai') }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">
                                  {{ $order->customer_email ?? ($user->email ?? 'N/A') }}
                                  @if($order && $order->customer_phone)
                                    • {{ $order->customer_phone }}
                                  @endif
                                </div>
                              </div>
                            </div>
                          </td>

                          <!-- Mã Đơn Hàng -->
                          <td>
                            <strong class="font-monospace text-dark">{{ $order->order_code ?? '#' . $item->order_id }}</strong>
                          </td>

                          <!-- Phân Loại (Màu, Size) -->
                          <td>
                            @if($item->color || $item->size)
                              <span class="badge bg-light text-dark border fs-11">
                                {{ $item->color ?? '' }} {{ $item->size ? '• Size ' . $item->size : '' }}
                              </span>
                            @else
                              <span class="text-muted">Mặc định</span>
                            @endif
                          </td>

                          <!-- Số lượng & Giá -->
                          <td>
                            <div><strong class="text-danger">x{{ $item->quantity }}</strong></div>
                            <div class="text-muted fs-11">{{ number_format($item->price, 0, ',', '.') }}₫</div>
                          </td>

                          <!-- Thành Tiền -->
                          <td>
                            <strong class="text-success fw-bold">
                              {{ number_format($item->subtotal ?: ($item->price * $item->quantity), 0, ',', '.') }}₫
                            </strong>
                          </td>

                          <!-- Thời Gian -->
                          <td>
                            <span class="text-muted" style="font-size: 0.75rem;">
                              {{ $order && $order->created_at ? $order->created_at->format('d/m/Y H:i') : ($item->created_at ? $item->created_at->format('d/m/Y H:i') : 'N/A') }}
                            </span>
                          </td>

                          <!-- Trạng Thái -->
                          <td>
                            @if($order)
                              @if($order->shipping_status === 'completed' || $order->shipping_status === 'delivered')
                                <span class="badge bg-success-subtle text-success fs-11">Hoàn tất</span>
                              @elseif($order->shipping_status === 'shipping')
                                <span class="badge bg-warning-subtle text-dark fs-11">Đang giao</span>
                              @elseif($order->shipping_status === 'cancelled')
                                <span class="badge bg-danger-subtle text-danger fs-11">Đã hủy</span>
                              @else
                                <span class="badge bg-info-subtle text-info fs-11">Đang xử lý</span>
                              @endif
                            @else
                              <span class="badge bg-light text-muted fs-11">N/A</span>
                            @endif
                          </td>

                          <!-- Link Đơn Hàng -->
                          <td class="pe-3 text-end">
                            @if($order)
                              <a href="{{ route('admin.orders.show', $order->id) }}" target="_blank" class="btn btn-xs btn-outline-dark py-1 px-2 fw-semibold" style="font-size: 0.72rem;">
                                Xem Đơn <i class="fa-solid fa-arrow-up-right-from-square ms-0.5"></i>
                              </a>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="p-5 text-center bg-light rounded-3 border">
                  <i class="fa-solid fa-box-open fs-1 text-muted mb-2 opacity-50"></i>
                  <h6 class="fw-bold text-dark mb-1">Chưa có đơn hàng nào được ghi nhận</h6>
                  <p class="small text-muted mb-0">Các đơn hàng mua sản phẩm này khi áp dụng mức giảm giá Flash Sale sẽ tự động được hiển thị tại đây.</p>
                </div>
              @endif
            </div>

            <!-- TAB 2: ĐÁNH GIÁ CỦA KHÁCH HÀNG -->
            <div class="tab-pane fade" id="reviews-pane-{{ $deal->id }}" role="tabpanel">
              @if($product && $product->allReviews->isNotEmpty())
                <div class="d-flex flex-column gap-2.5">
                  @foreach($product->allReviews as $rev)
                    <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-start gap-3">
                      <div class="d-flex align-items-start gap-2.5">
                        <img src="{{ $rev->user_avatar_url }}" alt="{{ $rev->user_name }}" class="rounded-circle border" style="width: 42px; height: 42px; object-fit: cover;">
                        <div>
                          <div class="d-flex align-items-center gap-2">
                            <strong class="text-dark small">{{ $rev->user_name }}</strong>
                            <div class="text-warning small" style="font-size: 0.75rem;">
                              @for($i=1; $i<=5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                              @endfor
                            </div>
                            @if($rev->status === 'approved')
                              <span class="badge bg-success-subtle text-success fs-11">Đã duyệt</span>
                            @else
                              <span class="badge bg-warning-subtle text-dark fs-11">Chờ duyệt</span>
                            @endif
                          </div>
                          <p class="mb-1 text-dark small mt-1.5" style="line-height: 1.45;">
                            {{ $rev->comment }}
                          </p>
                          <small class="text-muted" style="font-size: 0.72rem;">
                            <i class="fa-regular fa-clock me-1"></i> {{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}
                          </small>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="p-5 text-center bg-light rounded-3 border">
                  <i class="fa-solid fa-star-half-stroke fs-1 text-muted mb-2 opacity-50"></i>
                  <h6 class="fw-bold text-dark mb-1">Chưa có đánh giá nào cho sản phẩm này</h6>
                  <p class="small text-muted mb-0">Khi khách hàng đã mua và gửi đánh giá nhận xét, thông tin sẽ được cập nhật tại đây.</p>
                </div>
              @endif
            </div>

          </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="modal-footer border-top py-3 px-4 bg-light d-flex justify-content-between align-items-center">
          <div class="small text-muted">
            <i class="fa-solid fa-circle-info text-primary me-1"></i> Dữ liệu doanh thu được tự động tổng hợp từ các đơn hàng liên kết với mã sản phẩm này.
          </div>
          <button type="button" class="btn btn-secondary btn-sm px-4 fw-semibold rounded-pill" data-bs-dismiss="modal">Đóng</button>
        </div>

      </div>
    </div>
  </div>

  <!-- 2. MODAL GIA HẠN ƯU ĐÃI (RENEW DEAL MODAL) -->
  <div class="modal fade" id="renewDealModal_{{ $deal->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
        <div class="modal-header border-bottom py-3 px-4 bg-light">
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger text-white p-2 rounded-circle"><i class="fa-solid fa-clock-rotate-left"></i></span>
            <div>
              <h5 class="modal-title fw-bold text-dark mb-0">Gia Hạn Ưu Đãi Trong Ngày</h5>
              <small class="text-muted">{{ Str::limit($product->name ?? 'Sản phẩm', 35) }}</small>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form action="{{ route('admin.daily-deals.renew', $deal->id) }}" method="POST">
          @csrf
          <div class="modal-body p-4">
            <!-- Trạng thái hiện tại -->
            <div class="p-3 bg-light rounded-3 mb-3 border d-flex justify-content-between align-items-center">
              <div>
                <span class="small text-muted d-block">Trạng thái hiện tại:</span>
                <span class="badge {{ $deal->status_badge_class }} fs-9">{{ $deal->status_label }}</span>
              </div>
              <div class="text-end">
                <span class="small text-muted d-block">Khung giờ cũ:</span>
                <strong class="small text-dark font-monospace">{{ substr($deal->start_time, 0, 5) }} - {{ substr($deal->end_time, 0, 5) }}</strong>
              </div>
            </div>

            <!-- Chọn kiểu gia hạn -->
            <label class="form-label fw-bold text-dark fs-9 text-uppercase mb-2">Chọn phương thức gia hạn:</label>
            <div class="d-flex flex-column gap-2 mb-3">
              
              <!-- Option 1: Đến hết hôm nay -->
              <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer hover-bg-light" style="cursor: pointer;">
                <div class="d-flex align-items-center gap-2.5">
                  <input type="radio" name="renew_type" value="today_end" class="form-check-input mt-0" checked onchange="toggleCustomRenewFields('{{ $deal->id }}', false)">
                  <div>
                    <strong class="text-dark d-block fs-9">⚡ Gia hạn đến hết hôm nay (23:59)</strong>
                    <small class="text-muted" style="font-size: 0.75rem;">Mở bán tiếp tục cho đến hết ngày hôm nay (00:00 - 23:59)</small>
                  </div>
                </div>
                <span class="badge bg-danger-subtle text-danger fs-10 fw-bold">Khuyên dùng</span>
              </label>

              <!-- Option 2: Thêm 2 tiếng nữa -->
              <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer hover-bg-light" style="cursor: pointer;">
                <div class="d-flex align-items-center gap-2.5">
                  <input type="radio" name="renew_type" value="plus_hours" class="form-check-input mt-0" onchange="toggleCustomRenewFields('{{ $deal->id }}', false)">
                  <div>
                    <strong class="text-dark d-block fs-9">⏱️ Gia hạn thêm 2 tiếng (tính từ lúc này)</strong>
                    <small class="text-muted" style="font-size: 0.75rem;">Kéo dài thêm 2 giờ Flash Sale tức thì</small>
                  </div>
                </div>
                <span class="badge bg-warning-subtle text-dark fs-10 fw-bold">+2 Giờ</span>
              </label>

              <!-- Option 3: Sang ngày mai -->
              <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer hover-bg-light" style="cursor: pointer;">
                <div class="d-flex align-items-center gap-2.5">
                  <input type="radio" name="renew_type" value="tomorrow" class="form-check-input mt-0" onchange="toggleCustomRenewFields('{{ $deal->id }}', false)">
                  <div>
                    <strong class="text-dark d-block fs-9">📅 Gia hạn mở bán sang ngày mai</strong>
                    <small class="text-muted" style="font-size: 0.75rem;">Áp dụng cho ngày mai từ 08:00 đến 22:00</small>
                  </div>
                </div>
                <span class="badge bg-info-subtle text-info fs-10 fw-bold">Ngày mai</span>
              </label>

              <!-- Option 4: Tùy chỉnh -->
              <label class="p-3 border rounded-3 d-flex align-items-center justify-content-between cursor-pointer hover-bg-light" style="cursor: pointer;">
                <div class="d-flex align-items-center gap-2.5">
                  <input type="radio" name="renew_type" value="custom" class="form-check-input mt-0" onchange="toggleCustomRenewFields('{{ $deal->id }}', true)">
                  <div>
                    <strong class="text-dark d-block fs-9">⚙️ Tùy chỉnh ngày &amp; khung giờ mới</strong>
                    <small class="text-muted" style="font-size: 0.75rem;">Tự chỉ định ngày và giờ bắt đầu / kết thúc</small>
                  </div>
                </div>
                <span class="badge bg-secondary-subtle text-muted fs-10">Tùy chọn</span>
              </label>
            </div>

            <!-- Khối nhập tùy chỉnh (Ẩn mặc định) -->
            <div id="customRenewBox_{{ $deal->id }}" class="p-3 bg-light rounded-3 border mb-3 d-none">
              <div class="mb-2">
                <label class="form-label small fw-bold text-dark">Ngày áp dụng:</label>
                <input type="date" name="custom_date" value="{{ now()->toDateString() }}" class="form-control form-control-sm">
              </div>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label small fw-bold text-dark">Bắt đầu:</label>
                  <input type="time" name="custom_start" value="{{ now()->format('H:i') }}" class="form-control form-control-sm">
                </div>
                <div class="col-6">
                  <label class="form-label small fw-bold text-dark">Kết thúc:</label>
                  <input type="time" name="custom_end" value="23:59" class="form-control form-control-sm">
                </div>
              </div>
            </div>

            <!-- Mức giảm % & Số lượng -->
            <div class="row g-3 mb-3">
              <div class="col-6">
                <label class="form-label small fw-bold text-dark">Mức giảm (%):</label>
                <input type="number" name="discount_percent" value="{{ $deal->discount_percent }}" min="1" max="99" class="form-control form-control-sm" required>
              </div>
              <div class="col-6">
                <label class="form-label small fw-bold text-dark">Số lượng mở bán:</label>
                <input type="number" name="quantity_limit" value="{{ $deal->quantity_limit }}" min="0" class="form-control form-control-sm">
              </div>
            </div>

            <!-- Reset số lượng đã bán -->
            <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between p-2.5 bg-light rounded-2 border">
              <label class="form-check-label small fw-bold text-dark cursor-pointer ms-0" for="resetSold_{{ $deal->id }}">
                Khôi phục số lượng đã bán về 0 (Đã bán: {{ $deal->sold_count }})
              </label>
              <input class="form-check-input ms-2" type="checkbox" name="reset_sold" value="1" id="resetSold_{{ $deal->id }}" checked>
            </div>
          </div>

          <div class="modal-footer border-top py-2.5 px-4 bg-light">
            <button type="button" class="btn btn-light btn-sm fw-semibold rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold rounded-pill shadow-sm">
              <i class="fa-solid fa-bolt me-1"></i> Xác Nhận Gia Hạn Ngay
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- 3. MODAL CHỈNH SỬA ƯU ĐÃI -->
  <div class="modal fade" id="editDealModal_{{ $deal->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="modal-header border-bottom py-3 px-4 bg-light">
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger text-white p-2 rounded-circle"><i class="fa-solid fa-bolt"></i></span>
            <div>
              <h5 class="modal-title fw-bold text-dark mb-0">Cập Nhật Ưu Đãi Trong Ngày</h5>
              <small class="text-muted">{{ $product->name ?? 'Sản phẩm' }}</small>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form action="{{ route('admin.daily-deals.update', $deal->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body p-4">
            <div class="row g-3">
              <!-- Chọn sản phẩm -->
              <div class="col-12">
                <label class="form-label fw-bold text-dark small">Sản phẩm áp dụng <span class="text-danger">*</span></label>
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
                <label class="form-label fw-bold text-dark small">Mức giảm giá (%) <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="number" name="discount_percent" value="{{ $deal->discount_percent }}" min="1" max="99" class="form-control input-discount-edit" data-deal-id="{{ $deal->id }}" required>
                  <span class="input-group-text bg-light fw-bold">%</span>
                </div>
              </div>

              <!-- Giới hạn số lượng bán -->
              <div class="col-md-6">
                <label class="form-label fw-bold text-dark small">Giới hạn số lượng bán (Suất)</label>
                <input type="number" name="quantity_limit" value="{{ $deal->quantity_limit }}" min="0" class="form-control" placeholder="0 = Không giới hạn">
              </div>

              <!-- Thời gian bắt đầu -->
              <div class="col-md-6">
                <label class="form-label fw-bold text-dark small">Giờ bắt đầu trong ngày <span class="text-danger">*</span></label>
                <input type="time" name="start_time" value="{{ substr($deal->start_time, 0, 5) }}" class="form-control" required>
              </div>

              <!-- Thời gian kết thúc -->
              <div class="col-md-6">
                <label class="form-label fw-bold text-dark small">Giờ kết thúc trong ngày <span class="text-danger">*</span></label>
                <input type="time" name="end_time" value="{{ substr($deal->end_time, 0, 5) }}" class="form-control" required>
              </div>

              <!-- Ngày áp dụng -->
              <div class="col-md-6">
                <label class="form-label fw-bold text-dark small">Ngày diễn ra (Bỏ trống nếu lặp lại hàng ngày)</label>
                <input type="date" name="deal_date" value="{{ $deal->deal_date ? $deal->deal_date->format('Y-m-d') : '' }}" class="form-control">
              </div>

              <!-- Tên khung giờ / Tiêu đề hiển thị -->
              <div class="col-md-6">
                <label class="form-label fw-bold text-dark small">Tên khung giờ hiển thị</label>
                <input type="text" name="slot_name" value="{{ $deal->slot_name }}" class="form-control" placeholder="Ví dụ: Giờ vàng sáng (08:00 - 12:00)">
              </div>

              <!-- Giá sau giảm dự kiến -->
              <div class="col-12">
                <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                  <span class="small fw-semibold text-muted">Giá sau giảm dự kiến:</span>
                  <span class="fs-6 fw-bold text-danger preview-deal-price-edit-{{ $deal->id }}">
                    {{ number_format($deal->deal_price, 0, ',', '.') }}₫
                  </span>
                </div>
              </div>

              <!-- Trạng thái kích hoạt -->
              <div class="col-12">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="is_active" value="1" id="editActive_{{ $deal->id }}" {{ $deal->is_active ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold text-dark" for="editActive_{{ $deal->id }}">Kích hoạt ưu đãi này</label>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer border-top py-3 px-4 bg-light">
            <button type="button" class="btn btn-light btn-sm fw-semibold rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-bee-primary btn-sm px-4 fw-bold rounded-pill">Lưu Thay Đổi</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

<!-- ========================================================================= -->
<!-- 4. MODAL TẠO MỚI ƯU ĐÃI TRONG NGÀY (ADD DEAL MODAL) -->
<!-- ========================================================================= -->
<div class="modal fade" id="addDealModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      
      <!-- MODAL HEADER -->
      <div class="modal-header border-bottom py-3.5 px-4 bg-light">
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-danger text-white p-2 rounded-circle"><i class="fa-solid fa-bolt"></i></span>
          <div>
            <h5 class="modal-title fw-bold text-dark mb-0">Thêm Sản Phẩm Vào ƯU ĐÃI TRONG NGÀY</h5>
            <small class="text-muted">Chọn sản phẩm, cài đặt mức giảm giá (%) và khung giờ vàng áp dụng</small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('admin.daily-deals.store') }}" method="POST" id="createDealForm">
        @csrf
        <div class="modal-body p-4">
          <div class="row g-3">
            
            <!-- CHỌN SẢN PHẨM -->
            <div class="col-12">
              <label class="form-label fw-bold text-dark small">Chọn sản phẩm khuyến mãi <span class="text-danger">*</span></label>
              <select name="product_id" id="selectProductCreate" class="form-select select2-products" required>
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
              <label class="form-label fw-bold text-dark small">Phần trăm giảm giá (%) <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="number" name="discount_percent" id="dealDiscountPercent" value="30" min="1" max="99" class="form-control" placeholder="Nhập từ 1 đến 99" required>
                <span class="input-group-text bg-light fw-bold">%</span>
              </div>
              <div class="d-flex gap-1.5 mt-2 flex-wrap">
                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 quick-discount-btn" data-pct="10">10%</button>
                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 quick-discount-btn" data-pct="20">20%</button>
                <button type="button" class="btn btn-xs btn-outline-danger py-0.5 px-2 quick-discount-btn active" data-pct="30">30%</button>
                <button type="button" class="btn btn-xs btn-outline-danger py-0.5 px-2 quick-discount-btn" data-pct="50">50%</button>
                <button type="button" class="btn btn-xs btn-outline-danger py-0.5 px-2 quick-discount-btn" data-pct="70">70%</button>
              </div>
            </div>

            <!-- SỐ LƯỢNG MỞ BÁN -->
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark small">Giới hạn số lượng suất Deal</label>
              <input type="number" name="quantity_limit" value="50" min="0" class="form-control" placeholder="0 = Không giới hạn suất">
              <small class="text-muted fs-11 mt-1 d-block">Khi bán hết số lượng này, sản phẩm sẽ tự động đóng ưu đãi.</small>
            </div>

            <!-- KHUNG GIỜ NHANH PRESETS -->
            <div class="col-12">
              <label class="form-label fw-bold text-dark small">Chọn nhanh khung giờ có sẵn:</label>
              <div class="d-flex gap-1.5 flex-wrap">
                <button type="button" class="btn btn-sm btn-outline-dark preset-slot-btn" data-start="08:00" data-end="12:00" data-name="Giờ vàng sáng (08:00 - 12:00)">
                  🌅 Sáng (08:00 - 12:00)
                </button>
                <button type="button" class="btn btn-sm btn-outline-dark preset-slot-btn" data-start="12:00" data-end="16:00" data-name="Flash Sale trưa (12:00 - 16:00)">
                  ☀️ Trưa (12:00 - 16:00)
                </button>
                <button type="button" class="btn btn-sm btn-outline-dark preset-slot-btn" data-start="16:00" data-end="20:00" data-name="Giờ vàng chiều (16:00 - 20:00)">
                  🌇 Chiều (16:00 - 20:00)
                </button>
                <button type="button" class="btn btn-sm btn-outline-dark preset-slot-btn" data-start="20:00" data-end="23:59" data-name="Flash Sale tối (20:00 - 23:59)">
                  🌙 Tối (20:00 - 23:59)
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger preset-slot-btn active" data-start="00:00" data-end="23:59" data-name="Cả ngày (00:00 - 23:59)">
                  ⚡ Cả ngày (00:00 - 23:59)
                </button>
              </div>
            </div>

            <!-- THỜI GIAN BẮT ĐẦU & KẾT THÚC -->
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark small">Bắt đầu lúc (Giờ:Phút) <span class="text-danger">*</span></label>
              <input type="time" name="start_time" id="dealStartTime" value="00:00" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-bold text-dark small">Kết thúc lúc (Giờ:Phút) <span class="text-danger">*</span></label>
              <input type="time" name="end_time" id="dealEndTime" value="23:59" class="form-control" required>
            </div>

            <!-- NGÀY ÁP DỤNG -->
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark small">Ngày áp dụng</label>
              <input type="date" name="deal_date" value="{{ now()->toDateString() }}" class="form-control">
              <small class="text-muted fs-11 mt-1 d-block">Để trống nếu bạn muốn ưu đãi này lặp lại đều đặn mỗi ngày.</small>
            </div>

            <!-- TÊN KHUNG GIỜ -->
            <div class="col-md-6">
              <label class="form-label fw-bold text-dark small">Tiêu đề / Tên khung giờ</label>
              <input type="text" name="slot_name" id="dealSlotName" value="Cả ngày (00:00 - 23:59)" class="form-control" placeholder="Ví dụ: Giờ vàng sáng (08:00 - 12:00)">
            </div>

            <!-- BẢNG TÍNH NHẨM GIÁ BÁN THỰC TẾ REAL-TIME -->
            <div class="col-12">
              <div class="p-3.5 rounded-3 border" id="createPreviewBox" style="background: #fff8f8; border-color: #fecaca !important; display: none;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="small fw-bold text-dark">Tính toán giá bán Flash Sale:</span>
                  <span class="badge bg-danger rounded-pill fs-10" id="previewDiscountBadge">-30%</span>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Giá niêm yết ban đầu:</small>
                    <span class="fw-semibold text-dark text-decoration-line-through fs-9" id="previewBasePrice">0₫</span>
                  </div>
                  <div class="text-center text-muted">
                    <i class="fa-solid fa-arrow-right-long fs-5 text-danger"></i>
                  </div>
                  <div class="text-end">
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Giá Flash Sale đến tay khách:</small>
                    <span class="fw-black text-danger fs-5" id="previewCalculatedPrice">0₫</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- KÍCH HOẠT -->
            <div class="col-12">
              <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between p-2.5 bg-light rounded-2 border">
                <label class="form-check-label fw-semibold text-dark cursor-pointer ms-0" for="dealIsActive">
                  Kích hoạt và mở bán ngay trong khung giờ đã chọn
                </label>
                <input class="form-check-input ms-2" type="checkbox" name="is_active" value="1" id="dealIsActive" checked>
              </div>
            </div>

          </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="modal-footer border-top py-3 px-4 bg-light">
          <button type="button" class="btn btn-light btn-sm fw-semibold rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <i class="fa-solid fa-bolt me-1"></i> Lưu &amp; Đưa Lên Sàn Flash Sale
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

@push('styles')
<style>
  .deal-stat-card {
    transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.22s ease, border-color 0.22s ease;
  }
  .deal-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
  }
  .active-stat-card {
    border-width: 2.5px !important;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.09) !important;
    transform: translateY(-2px);
  }
  .shadow-2xs {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  }
  .animate-pulse {
    animation: pulseFlash 1.6s infinite;
  }
  @keyframes pulseFlash {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.08); }
  }

  /* Utility font sizes & weights */
  .fs-8 { font-size: 0.875rem !important; }
  .fs-9 { font-size: 0.8125rem !important; }
  .fs-10 { font-size: 0.75rem !important; }
  .fs-11 { font-size: 0.6875rem !important; }
  .fw-black { font-weight: 900 !important; }
  .font-heading { font-family: 'Plus Jakarta Sans', sans-serif; }
  .hover-primary:hover { color: #f59e0b !important; }
  .table > :not(caption) > * > * { padding: 0.85rem 0.65rem; }

  /* ĐỒNG BỘ NỀN TRẮNG TINH KHIẾT CHO TẤT CẢ SẢN PHẨM & CÁC TRANG LỌC */
  .table > tbody > tr,
  .table > tbody > tr > td {
    background-color: #ffffff !important;
  }
  .table-hover > tbody > tr:hover > td,
  .table-hover > tbody > tr:hover {
    background-color: #f8fafc !important;
  }
</style>
@endpush

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
        document.querySelectorAll('.quick-discount-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
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
        document.querySelectorAll('.preset-slot-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
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