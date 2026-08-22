@extends('layouts.admin')

@section('title', 'Quản Lý Ưu Đãi Trong Ngày (Daily Deals) | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-danger text-white fw-bold px-2.5 py-1 rounded-pill">
          <i class="fa-solid fa-bolt me-1"></i> FLASH SALE
        </span>
        <h3 class="fw-bold text-dark mb-0">Quản Lý Ưu Đãi Trong Ngày</h3>
      </div>
      <p class="text-muted small mb-0">Thiết lập sản phẩm khuyến mãi chớp nhoáng theo khung giờ và tỷ lệ giảm giá trong ngày</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('client.home') }}#flash-sale" target="_blank" class="btn btn-outline-dark btn-sm px-3">
        <i class="fa-solid fa-eye me-1.5"></i> Xem Trên Trang Chủ
      </a>
      <button type="button" class="btn btn-bee-primary btn-sm px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addDealModal">
        <i class="fa-solid fa-plus me-1.5"></i> Thêm Ưu Đãi Mới
      </button>
    </div>
  </div>
</div>

<!-- STATS SUMMARY CARDS -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #fff1f2 0%, #ffffff 100%); border-left: 4px solid #ef4444 !important;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Đang Chạy Lúc Này</p>
          <h3 class="fw-bold text-danger mb-0">{{ $runningDealsCount }}</h3>
        </div>
        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-fire fs-5"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%); border-left: 4px solid #f59e0b !important;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Ưu Đãi Hôm Nay</p>
          <h3 class="fw-bold text-warning mb-0">{{ $todayDealsCount }}</h3>
        </div>
        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-clock fs-5"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border-left: 4px solid #10b981 !important;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Đã Bán Qua Deal</p>
          <h3 class="fw-bold text-success mb-0">{{ number_format($totalSoldInDeals) }}</h3>
        </div>
        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-bag-shopping fs-5"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-md-3">
    <div class="card border-0 shadow-sm p-3 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); border-left: 4px solid #64748b !important;">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">Tổng Chiến Dịch</p>
          <h3 class="fw-bold text-dark mb-0">{{ $totalDeals }}</h3>
        </div>
        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-tags fs-5"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FILTERS & SEARCH -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
  <div class="card-body p-3">
    <form action="{{ route('admin.daily-deals.index') }}" method="GET" class="row g-2 align-items-center">
      <div class="col-12 col-md-4">
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
          <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0" placeholder="Tìm theo tên sản phẩm hoặc mã SKU...">
        </div>
      </div>

      <div class="col-6 col-md-3">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
          <option value="running" {{ $statusFilter === 'running' ? 'selected' : '' }}>⚡ Đang diễn ra bây giờ</option>
          <option value="upcoming" {{ $statusFilter === 'upcoming' ? 'selected' : '' }}>⏳ Sắp diễn ra hôm nay</option>
          <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>⏸️ Đã tạm dừng</option>
        </select>
      </div>

      <div class="col-6 col-md-3">
        <select name="date" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Tất cả các ngày</option>
          <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>Áp dụng hôm nay ({{ now()->format('d/m') }})</option>
        </select>
      </div>

      <div class="col-12 col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-dark btn-sm w-100">Lọc</button>
        @if($search || $statusFilter !== 'all' || $dateFilter)
          <a href="{{ route('admin.daily-deals.index') }}" class="btn btn-outline-secondary btn-sm" title="Đặt lại bộ lọc"><i class="fa-solid fa-rotate-left"></i></a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- DEALS TABLE -->
<div class="bee-table-card">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Sản Phẩm Áp Dụng</th>
          <th>Mức Khuyến Mãi</th>
          <th>Khung Giờ Trong Ngày</th>
          <th>Ngày Áp Dụng</th>
          <th>Tiến Độ Bán</th>
          <th>Trạng Thái</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($deals as $deal)
          @php
            $product = $deal->product;
            $isRunning = $deal->is_running;
          @endphp
          <tr>
            <!-- SẢN PHẨM -->
            <td>
              <div class="d-flex align-items-center gap-3">
                <div class="position-relative" style="width: 52px; height: 52px; min-width: 52px;">
                  @if($product && $product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 rounded-3 object-fit-cover border">
                  @else
                    <div class="w-100 h-100 rounded-3 bg-light d-flex align-items-center justify-content-center border">
                      <i class="fa-solid fa-shirt text-muted"></i>
                    </div>
                  @endif
                  @if($isRunning)
                    <span class="position-absolute top-0 start-100 translate-middle p-1.5 bg-danger border border-light rounded-circle" title="Đang chạy"></span>
                  @endif
                </div>
                <div>
                  <div class="fw-bold text-dark small mb-0.5">
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
                    <span>{{ $product->category->name ?? 'Thời trang nam' }}</span>
                  </div>
                </div>
              </div>
            </td>

            <!-- MỨC GIẢM GIÁ & GIÁ DEAL -->
            <td>
              <div class="d-flex flex-column gap-1">
                <div>
                  <span class="badge bg-danger fw-bold fs-9 px-2 py-1 rounded-pill">
                    <i class="fa-solid fa-arrow-down me-0.5"></i> Giảm {{ $deal->discount_percent }}%
                  </span>
                </div>
                <div class="d-flex align-items-baseline gap-1.5">
                  <strong class="text-danger fw-bold fs-8">{{ number_format($deal->deal_price, 0, ',', '.') }}₫</strong>
                  @if($product && $product->price > $deal->deal_price)
                    <small class="text-muted text-decoration-line-through fs-10">{{ number_format($product->price, 0, ',', '.') }}₫</small>
                  @endif
                </div>
              </div>
            </td>

            <!-- KHUNG GIỜ -->
            <td>
              <div class="d-flex align-items-center gap-1.5">
                <span class="badge bg-warning-subtle text-dark border border-warning fw-bold fs-9 px-2 py-1">
                  <i class="fa-regular fa-clock me-1 text-warning"></i>
                  {{ substr($deal->start_time, 0, 5) }} - {{ substr($deal->end_time, 0, 5) }}
                </span>
              </div>
              @if($deal->slot_name && $deal->slot_name !== (substr($deal->start_time, 0, 5) . ' - ' . substr($deal->end_time, 0, 5)))
                <small class="text-muted d-block mt-0.5" style="font-size: 0.75rem;">{{ $deal->slot_name }}</small>
              @endif
            </td>

            <!-- NGÀY ÁP DỤNG -->
            <td>
              @if($deal->deal_date)
                <span class="badge bg-light text-dark border fw-medium px-2 py-1">
                  <i class="fa-regular fa-calendar me-1 text-primary"></i>
                  {{ $deal->deal_date->format('d/m/Y') }}
                </span>
                @if($deal->deal_date->isToday())
                  <span class="badge bg-success-subtle text-success fs-10 px-1.5 py-0.5 rounded-pill">Hôm nay</span>
                @endif
              @else
                <span class="badge bg-info-subtle text-info border border-info fw-bold px-2 py-1">
                  <i class="fa-solid fa-repeat me-1"></i> Hàng ngày
                </span>
              @endif
            </td>

            <!-- TIẾN ĐỘ BÁN HÀNG -->
            <td>
              <div class="d-flex flex-column gap-1" style="min-width: 110px;">
                <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                  <span>Đã bán: <strong class="text-dark">{{ $deal->sold_count }}</strong></span>
                  <span>{{ $deal->quantity_limit > 0 ? '/ ' . $deal->quantity_limit : '(KGH)' }}</span>
                </div>
                <div class="progress" style="height: 6px; border-radius: 99px;">
                  @php
                    $pct = $deal->quantity_limit > 0 ? min(100, round(($deal->sold_count / $deal->quantity_limit) * 100)) : 100;
                  @endphp
                  <div class="progress-bar bg-danger" style="width: {{ $pct }}%"></div>
                </div>
              </div>
            </td>

            <!-- TRẠNG THÁI -->
            <td>
              @if(!$deal->is_active)
                <span class="badge bg-secondary-subtle text-muted fw-bold py-1 px-2.5 rounded-pill">
                  <i class="fa-solid fa-pause me-1"></i> Tạm dừng
                </span>
              @elseif($isRunning)
                <span class="badge bg-danger text-white fw-bold py-1 px-2.5 rounded-pill shadow-xs animate-pulse">
                  <i class="fa-solid fa-bolt me-1"></i> Đang diễn ra
                </span>
              @else
                <span class="badge {{ $deal->status_badge_class }} fw-bold py-1 px-2.5 rounded-pill">
                  {{ $deal->status_label }}
                </span>
              @endif
            </td>

            <!-- HÀNH ĐỘNG -->
            <td class="text-end">
              <div class="d-flex align-items-center justify-content-end gap-1.5">
                <!-- Nút Gia Hạn Ưu Đãi -->
                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2.5 fw-bold" data-bs-toggle="modal" data-bs-target="#renewDealModal_{{ $deal->id }}" title="Gia hạn thêm thời gian ưu đãi">
                  <i class="fa-solid fa-clock-rotate-left me-1"></i> Gia hạn
                </button>

                <!-- Toggle Bật/Tắt -->
                <form action="{{ route('admin.daily-deals.toggle', $deal->id) }}" method="POST" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-sm {{ $deal->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} py-1 px-2" title="{{ $deal->is_active ? 'Tạm dừng ưu đãi' : 'Kích hoạt ưu đãi' }}">
                    <i class="fa-solid {{ $deal->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                  </button>
                </form>

                <!-- Sửa -->
                <button type="button" class="btn btn-sm btn-outline-dark py-1 px-2.5 fw-bold" data-bs-toggle="modal" data-bs-target="#editDealModal_{{ $deal->id }}" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                </button>

                <!-- Xóa -->
                <form action="{{ route('admin.daily-deals.destroy', $deal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn gỡ sản phẩm này khỏi danh sách Ưu Đãi Trong Ngày?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Xóa ưu đãi">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>

          <!-- MODAL GIA HẠN ƯU ĐÃI (RENEW DEAL MODAL) -->
          <div class="modal fade" id="renewDealModal_{{ $deal->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom py-3">
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

                  <div class="modal-footer border-top py-2.5">
                    <button type="button" class="btn btn-light btn-sm fw-semibold" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger btn-sm px-3.5 fw-bold shadow-sm">
                      <i class="fa-solid fa-bolt me-1"></i> Xác Nhận Gia Hạn Ngay
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- MODAL CHỈNH SỬA ƯU ĐÃI -->
          <div class="modal fade" id="editDealModal_{{ $deal->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom py-3">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger text-white p-2 rounded-circle"><i class="fa-solid fa-bolt"></i></span>
                    <div>
                      <h5 class="modal-title fw-bold text-dark mb-0">Cập Nhật Ưu Đãi Trong Ngày</h5>
                      <small class="text-muted">{{ $product->name ?? 'Sản phẩm' }}</small>
                    </div>
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.daily-deals.update', $deal->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-body p-4 text-start">
                    <div class="row g-3">
                      
                      <!-- Chọn sản phẩm -->
                      <div class="col-12">
                        <label class="form-label small fw-semibold">Sản phẩm áp dụng <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select form-select-sm select-product-edit" data-deal-id="{{ $deal->id }}" required>
                          @foreach($products as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-original="{{ $p->original_price }}" data-sku="{{ $p->sku }}" {{ $deal->product_id == $p->id ? 'selected' : '' }}>
                              {{ $p->name }} (Mã SKU: {{ $p->sku }} - Giá: {{ number_format($p->price, 0, ',', '.') }}₫)
                            </option>
                          @endforeach
                        </select>
                      </div>

                      <!-- Mức giảm giá % -->
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Mức giảm giá (%) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                          <input type="number" name="discount_percent" class="form-control form-control-sm fw-bold text-danger input-discount-edit" data-deal-id="{{ $deal->id }}" value="{{ old('discount_percent', $deal->discount_percent) }}" min="1" max="99" required>
                          <span class="input-group-text fw-bold bg-light">%</span>
                        </div>
                        <small class="text-muted">Nhập từ 1 đến 99%</small>
                      </div>

                      <!-- Preview Giá sau giảm -->
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Giá bán sau khi giảm ưu đãi</label>
                        <div class="p-2 rounded bg-light border text-center">
                          <strong class="text-danger fs-6 fw-bold preview-deal-price-edit-{{ $deal->id }}">
                            {{ number_format($deal->deal_price, 0, ',', '.') }}₫
                          </strong>
                        </div>
                      </div>

                      <!-- Ngày áp dụng -->
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Ngày áp dụng</label>
                        <input type="date" name="deal_date" class="form-control form-control-sm" value="{{ old('deal_date', $deal->deal_date ? $deal->deal_date->format('Y-m-d') : '') }}">
                        <small class="text-muted">Để trống nếu muốn áp dụng lặp lại hàng ngày</small>
                      </div>

                      <!-- Giới hạn số lượng -->
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Giới hạn số lượng deal mở bán</label>
                        <input type="number" name="quantity_limit" class="form-control form-control-sm" value="{{ old('quantity_limit', $deal->quantity_limit) }}" min="0" placeholder="0 = Không giới hạn">
                        <small class="text-muted">0 tương đương bán theo toàn bộ tồn kho</small>
                      </div>

                      <!-- Khung giờ bắt đầu & kết thúc -->
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Thời gian bắt đầu trong ngày <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" class="form-control form-control-sm" value="{{ old('start_time', substr($deal->start_time, 0, 5)) }}" required>
                      </div>

                      <div class="col-md-6">
                        <label class="form-label small fw-semibold">Thời gian kết thúc trong ngày <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" class="form-control form-control-sm" value="{{ old('end_time', substr($deal->end_time, 0, 5)) }}" required>
                      </div>

                      <!-- Ghi chú khung giờ / Tiêu đề -->
                      <div class="col-md-8">
                        <label class="form-label small fw-semibold">Tên khung giờ / Tiêu đề hiển thị (Tùy chọn)</label>
                        <input type="text" name="slot_name" class="form-control form-control-sm" value="{{ old('slot_name', $deal->slot_name) }}" placeholder="Ví dụ: Giờ vàng Flash Sale, 08:00 - 12:00">
                      </div>

                      <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch mb-1">
                          <input class="form-check-input" type="checkbox" name="is_active" value="1" id="switchActive_{{ $deal->id }}" {{ $deal->is_active ? 'checked' : '' }}>
                          <label class="form-check-label small fw-semibold" for="switchActive_{{ $deal->id }}">Kích hoạt ưu đãi</label>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-bee-primary btn-sm px-4">
                      <i class="fa-solid fa-check me-1.5"></i> Cập Nhật Ưu Đãi
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5">
              <div class="text-muted">
                <i class="fa-solid fa-bolt fs-1 text-secondary opacity-50 mb-3"></i>
                <p class="mb-2 fw-semibold">Chưa có sản phẩm nào được cấu hình trong chương trình Ưu Đãi Trong Ngày.</p>
                <button type="button" class="btn btn-bee-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDealModal">
                  <i class="fa-solid fa-plus me-1.5"></i> Thêm Sản Phẩm Ngay
                </button>
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($deals->hasPages())
    <div class="p-3 border-top d-flex justify-content-end">
      {{ $deals->links() }}
    </div>
  @endif
</div>

<!-- ========================================================== -->
<!-- MODAL TẠO MỚI ƯU ĐÃI TRONG NGÀY (ADD DEAL MODAL) -->
<!-- ========================================================== -->
<div class="modal fade" id="addDealModal" tabindex="-1" aria-labelledby="addDealModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom py-3">
        <div class="d-flex align-items-center gap-2">
          <span class="badge bg-danger text-white p-2 rounded-circle"><i class="fa-solid fa-bolt"></i></span>
          <div>
            <h5 class="modal-title fw-bold text-dark mb-0" id="addDealModalLabel">Thêm Sản Phẩm Vào Ưu Đãi Trong Ngày</h5>
            <small class="text-muted">Chọn bất kỳ sản phẩm nào, cấu hình % giảm giá và khung giờ khuyến mãi trong ngày</small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('admin.daily-deals.store') }}" method="POST" id="createDealForm">
        @csrf
        <div class="modal-body p-4 text-start">
          <div class="row g-3">
            
            <!-- 1. Chọn sản phẩm bất kỳ -->
            <div class="col-12">
              <label class="form-label small fw-semibold">Chọn sản phẩm khuyến mãi <span class="text-danger">*</span></label>
              <select name="product_id" id="dealProductId" class="form-select form-select-sm" required>
                <option value="" selected disabled>-- Chọn một sản phẩm trong hệ thống --</option>
                @foreach($products as $p)
                  <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-original="{{ $p->original_price }}" data-sku="{{ $p->sku }}" data-stock="{{ $p->stock }}">
                    {{ $p->name }} — SKU: {{ $p->sku }} — Giá: {{ number_format($p->price, 0, ',', '.') }}₫ (Tồn kho: {{ $p->stock }})
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Card hiển thị tóm tắt sản phẩm & tính toán trực tiếp -->
            <div class="col-12" id="productPreviewBox" style="display: none;">
              <div class="p-3 rounded-3 border bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                  <small class="text-muted d-block">Giá niêm yết hiện tại:</small>
                  <strong class="text-dark fs-6" id="previewBasePrice">0₫</strong>
                </div>
                <div class="text-center">
                  <small class="text-muted d-block">Phần trăm giảm:</small>
                  <span class="badge bg-danger fs-6 px-2.5 py-1" id="previewDiscountBadge">-0%</span>
                </div>
                <div class="text-end">
                  <small class="text-muted d-block">Giá khuyến mãi Flash Sale:</small>
                  <strong class="text-danger fs-5 fw-bold" id="previewCalculatedPrice">0₫</strong>
                </div>
              </div>
            </div>

            <!-- 2. Phần trăm khuyến mãi (%) -->
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Mức giảm giá (%) <span class="text-danger">*</span></label>
              <div class="input-group input-group-sm">
                <input type="number" name="discount_percent" id="dealDiscountPercent" class="form-control form-control-sm fw-bold text-danger" value="20" min="1" max="99" required>
                <span class="input-group-text fw-bold bg-light">%</span>
              </div>
              <div class="d-flex gap-1.5 mt-1.5 flex-wrap">
                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 quick-discount-btn" data-pct="10">10%</button>
                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 quick-discount-btn" data-pct="20">20%</button>
                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 quick-discount-btn" data-pct="30">30%</button>
                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 quick-discount-btn" data-pct="50">50%</button>
                <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 quick-discount-btn" data-pct="70">70%</button>
              </div>
            </div>

            <!-- 3. Ngày áp dụng -->
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Ngày áp dụng</label>
              <input type="date" name="deal_date" id="dealDate" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}">
              <div class="d-flex gap-1.5 mt-1.5">
                <button type="button" class="btn btn-xs btn-outline-dark py-0.5 px-2" onclick="document.getElementById('dealDate').value='{{ now()->format('Y-m-d') }}'">Hôm nay</button>
                <button type="button" class="btn btn-xs btn-outline-dark py-0.5 px-2" onclick="document.getElementById('dealDate').value='{{ now()->addDay()->format('Y-m-d') }}'">Ngày mai</button>
                <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2" onclick="document.getElementById('dealDate').value=''">Lặp lại hàng ngày</button>
              </div>
            </div>

            <!-- 4. Chọn nhanh khung giờ trong ngày (Presets) -->
            <div class="col-12">
              <label class="form-label small fw-semibold mb-1">Chọn nhanh khung giờ ưu đãi trong ngày</label>
              <div class="d-flex flex-wrap gap-1.5 mb-2">
                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2.5 preset-slot-btn" data-start="08:00" data-end="12:00" data-name="Khung Sáng (08:00 - 12:00)">
                  <i class="fa-regular fa-sun me-1"></i> Sáng (08:00 - 12:00)
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2.5 preset-slot-btn" data-start="12:00" data-end="16:00" data-name="Khung Trưa (12:00 - 16:00)">
                  <i class="fa-solid fa-cloud-sun me-1"></i> Trưa (12:00 - 16:00)
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2.5 preset-slot-btn" data-start="16:00" data-end="20:00" data-name="Khung Chiều (16:00 - 20:00)">
                  <i class="fa-regular fa-clock me-1"></i> Chiều (16:00 - 20:00)
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2.5 preset-slot-btn" data-start="20:00" data-end="23:59" data-name="Khung Tối (20:00 - 23:59)">
                  <i class="fa-regular fa-moon me-1"></i> Tối (20:00 - 23:59)
                </button>
                <button type="button" class="btn btn-sm btn-outline-dark py-1 px-2.5 preset-slot-btn" data-start="00:00" data-end="23:59" data-name="Cả ngày (00:00 - 23:59)">
                  <i class="fa-solid fa-calendar-day me-1"></i> Cả ngày (00:00 - 23:59)
                </button>
              </div>
            </div>

            <!-- Giờ bắt đầu và kết thúc -->
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Thời gian bắt đầu trong ngày <span class="text-danger">*</span></label>
              <input type="time" name="start_time" id="dealStartTime" class="form-control form-control-sm" value="08:00" required>
            </div>

            <div class="col-md-6">
              <label class="form-label small fw-semibold">Thời gian kết thúc trong ngày <span class="text-danger">*</span></label>
              <input type="time" name="end_time" id="dealEndTime" class="form-control form-control-sm" value="22:00" required>
            </div>

            <!-- Giới hạn số lượng bán -->
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Giới hạn số lượng deal mở bán</label>
              <input type="number" name="quantity_limit" class="form-control form-control-sm" value="0" min="0" placeholder="0 = Bán không giới hạn">
              <small class="text-muted">Nhập 0 để bán theo số lượng tồn kho của sản phẩm</small>
            </div>

            <!-- Tên khung giờ / Tiêu đề -->
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Tên chương trình / Ghi chú</label>
              <input type="text" name="slot_name" id="dealSlotName" class="form-control form-control-sm" placeholder="Ví dụ: Deal Sốc Giờ Vàng">
            </div>

            <!-- Kích hoạt -->
            <div class="col-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="switchCreateActive" checked>
                <label class="form-check-label small fw-semibold" for="switchCreateActive">Kích hoạt ưu đãi ngay sau khi tạo</label>
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer border-top bg-light p-3">
          <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Hủy bỏ</button>
          <button type="submit" class="btn btn-bee-primary btn-sm px-4">
            <i class="fa-solid fa-bolt me-1.5"></i> Tạo Ưu Đãi Trong Ngày
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
    const productSelect = document.getElementById('dealProductId');
    const discountInput = document.getElementById('dealDiscountPercent');
    const previewBox = document.getElementById('productPreviewBox');
    const previewBasePrice = document.getElementById('previewBasePrice');
    const previewDiscountBadge = document.getElementById('previewDiscountBadge');
    const previewCalculatedPrice = document.getElementById('previewCalculatedPrice');

    function updateCreatePreview() {
      const selectedOption = productSelect.options[productSelect.selectedIndex];
      if (!selectedOption || !selectedOption.value) {
        if (previewBox) previewBox.style.display = 'none';
        return;
      }

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
        const start = this.getAttribute('data-start');
        const end = this.getAttribute('data-end');
        const name = this.getAttribute('data-name');

        const startEl = document.getElementById('dealStartTime');
        const endEl = document.getElementById('dealEndTime');
        const nameEl = document.getElementById('dealSlotName');

        if (startEl) startEl.value = start;
        if (endEl) endEl.value = end;
        if (nameEl && (!nameEl.value || nameEl.value.includes('Khung') || nameEl.value.includes('Cả ngày'))) {
          nameEl.value = name;
        }
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