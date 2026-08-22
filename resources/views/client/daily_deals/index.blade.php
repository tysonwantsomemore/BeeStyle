@extends('layouts.client')

@section('title', 'Ưu Đãi Trong Ngày | Flash Sale Siêu Hấp Dẫn - BeeStyle')

@section('content')
<div class="container py-4">

  <!-- BREADCRUMB -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0 py-2 small fw-medium">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-secondary text-decoration-none"><i class="fa-solid fa-house me-1"></i> Trang Chủ</a></li>
      <li class="breadcrumb-item active text-danger fw-bold" aria-current="page"><i class="fa-solid fa-bolt me-1 text-danger"></i> Ưu Đãi Trong Ngày</li>
    </ol>
  </nav>

  <!-- HERO FLASH SALE BANNER -->
  <div class="card border-0 text-white p-4 p-md-5 mb-4 shadow-sm position-relative overflow-hidden" style="border-radius: 20px; background: linear-gradient(135deg, #111827 0%, #1f2937 40%, #b91c1c 100%);">
    <div class="position-absolute end-0 top-0 opacity-10 d-none d-lg-block" style="transform: translate(15%, -20%); font-size: 24rem;">
      <i class="fa-solid fa-bolt"></i>
    </div>

    <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
      <div class="col-lg-7">
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-danger bg-opacity-75 text-white fw-bold fs-9 mb-3 shadow-xs">
          <i class="fa-solid fa-fire text-warning"></i> FLASH SALE THEO KHUNG GIỜ HÔM NAY
        </div>
        <h1 class="fw-black text-white mb-2" style="font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-family: var(--atino-font-heading); letter-spacing: -0.5px;">
          SIÊU ƯU ĐÃI TRONG NGÀY
        </h1>
        <p class="text-white-50 mb-4 fs-6" style="max-width: 540px;">
          Cơ hội sở hữu những thiết kế áo nam cao cấp chính hãng BeeStyle với mức chiết khấu giảm tới <strong class="text-warning">{{ $maxDiscount }}%</strong> trong các khung giờ vàng hôm nay.
        </p>

        <!-- Highlights Features -->
        <div class="d-flex flex-wrap gap-3">
          <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-3">
            <i class="fa-solid fa-tag text-warning fs-5"></i>
            <div>
              <small class="text-white-50 d-block leading-none" style="font-size: 0.7rem;">Mức giảm cao nhất</small>
              <strong class="text-white small">Giảm đến {{ $maxDiscount }}%</strong>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-3">
            <i class="fa-solid fa-truck-fast text-info fs-5"></i>
            <div>
              <small class="text-white-50 d-block leading-none" style="font-size: 0.7rem;">Chính sách ship</small>
              <strong class="text-white small">Freeship từ 300K</strong>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-3">
            <i class="fa-solid fa-shield-halved text-success fs-5"></i>
            <div>
              <small class="text-white-50 d-block leading-none" style="font-size: 0.7rem;">Bảo hành đổi trả</small>
              <strong class="text-white small">Đổi size trong 30 ngày</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- COUNTDOWN CLOCK BOX -->
      <div class="col-lg-5 text-center text-lg-end">
        <div class="p-4 rounded-4 bg-black bg-opacity-40 border border-white border-opacity-10 d-inline-block text-center shadow-lg" style="min-width: 280px;" id="dealsPageCountdownWrapper" data-target="{{ $targetCountdown }}">
          <div class="d-flex align-items-center justify-content-center gap-1.5 mb-2">
            <span class="badge bg-danger rounded-pill px-2 py-1 fs-10 fw-bold animate-pulse">
              <i class="fa-solid fa-circle me-1" style="font-size: 6px;"></i> {{ $isLive ? 'ĐANG DIỄN RA' : 'ĐỢT DEAL TIẾP THEO' }}
            </span>
          </div>
          <div class="small text-white-50 fw-semibold text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">
            {{ $isLive ? 'Ưu đãi kết thúc sau:' : 'Khung giờ bắt đầu sau:' }}
          </div>

          <div class="d-flex justify-content-center align-items-center gap-2 my-2">
            <div class="bg-dark text-white p-2.5 rounded-3 border border-white border-opacity-20" style="min-width: 60px;">
              <span class="fs-3 fw-black font-monospace d-block leading-none" id="pgHours">00</span>
              <small class="text-white-50" style="font-size: 0.65rem;">GIỜ</small>
            </div>
            <span class="fs-4 fw-bold text-danger">:</span>
            <div class="bg-dark text-white p-2.5 rounded-3 border border-white border-opacity-20" style="min-width: 60px;">
              <span class="fs-3 fw-black font-monospace d-block leading-none" id="pgMinutes">00</span>
              <small class="text-white-50" style="font-size: 0.65rem;">PHÚT</small>
            </div>
            <span class="fs-4 fw-bold text-danger">:</span>
            <div class="bg-dark text-white p-2.5 rounded-3 border border-white border-opacity-20" style="min-width: 60px;">
              <span class="fs-3 fw-black font-monospace text-warning d-block leading-none" id="pgSeconds">00</span>
              <small class="text-white-50" style="font-size: 0.65rem;">GIÂY</small>
            </div>
          </div>

          @if(!empty($currentSlotTitle))
            <div class="mt-2 text-warning small fw-bold">
              <i class="fa-regular fa-clock me-1"></i> {{ $currentSlotTitle }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- NAVIGATION TABS & FILTER BAR -->
  <div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: 16px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      
      <!-- STATUS TABS -->
      <ul class="nav nav-pills gap-1.5" id="dealStatusTabs">
        <li class="nav-item">
          <a class="nav-link btn-sm px-3.5 py-2 fw-bold rounded-pill {{ $tab === 'all' ? 'active bg-danger text-white' : 'bg-light text-dark' }}" href="{{ route('client.daily-deals.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'all'])) }}">
            <i class="fa-solid fa-list-check me-1"></i> Tất Cả Hôm Nay ({{ $totalTodayCount }})
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn-sm px-3.5 py-2 fw-bold rounded-pill {{ $tab === 'running' ? 'active bg-danger text-white' : 'bg-light text-dark' }}" href="{{ route('client.daily-deals.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'running'])) }}">
            <i class="fa-solid fa-fire me-1 text-danger"></i> Đang Diễn Ra ({{ $runningCount }})
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn-sm px-3.5 py-2 fw-bold rounded-pill {{ $tab === 'upcoming' ? 'active bg-danger text-white' : 'bg-light text-dark' }}" href="{{ route('client.daily-deals.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'upcoming'])) }}">
            <i class="fa-regular fa-clock me-1 text-warning"></i> Sắp Mở Bán ({{ $upcomingCount }})
          </a>
        </li>
      </ul>

      <!-- SEARCH & SORT CONTROLS -->
      <form action="{{ route('client.daily-deals.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap ms-auto">
        <input type="hidden" name="tab" value="{{ $tab }}">
        @if($categorySlug)
          <input type="hidden" name="category" value="{{ $categorySlug }}">
        @endif

        <div class="input-group input-group-sm" style="width: 220px;">
          <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
          <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0" placeholder="Tìm sản phẩm...">
        </div>

        <select name="sort" class="form-select form-select-sm" style="width: 175px;" onchange="this.form.submit()">
          <option value="discount_desc" {{ $sort === 'discount_desc' ? 'selected' : '' }}>Giảm nhiều nhất</option>
          <option value="sold_desc" {{ $sort === 'sold_desc' ? 'selected' : '' }}>Bán chạy nhất</option>
          <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
          <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến thấp</option>
          <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Mới nhất</option>
        </select>
      </form>
    </div>

    <!-- CATEGORY PILLS FILTER -->
    @if($categories->isNotEmpty())
      <div class="d-flex align-items-center gap-1.5 overflow-auto pt-3 mt-3 border-top pb-1">
        <span class="small fw-bold text-muted text-nowrap me-1">Danh mục:</span>
        <a href="{{ route('client.daily-deals.index', array_merge(request()->except(['category', 'page']))) }}" class="badge {{ empty($categorySlug) ? 'bg-dark text-white' : 'bg-light text-secondary border' }} text-decoration-none px-3 py-1.5 rounded-pill fw-medium fs-9">
          Tất cả danh mục
        </a>
        @foreach($categories as $cat)
          <a href="{{ route('client.daily-deals.index', array_merge(request()->except(['category', 'page']), ['category' => $cat->slug])) }}" class="badge {{ $categorySlug === $cat->slug ? 'bg-danger text-white' : 'bg-light text-secondary border' }} text-decoration-none px-3 py-1.5 rounded-pill fw-medium fs-9 text-nowrap">
            {{ $cat->name }}
          </a>
        @endforeach
      </div>
    @endif
  </div>

  <!-- PRODUCTS GRID -->
  @if($deals->isNotEmpty())
    <div class="row g-3 g-md-4 mb-5">
      @foreach($deals as $deal)
        @php
          $product = $deal->product;
          $isRunning = $deal->is_running;
        @endphp
        @if($product)
          <div class="col-lg-3 col-md-4 col-6" id="deal-card-{{ $deal->id }}">
            <div class="bee-product-card h-100 d-flex flex-column position-relative shadow-xs" style="border: 1px solid #ffe4e6;">
              
              <!-- BADGE GIẢM GIÁ -->
              <span class="bee-product-badge sale shadow-xs">-{{ $deal->discount_percent }}%</span>

              <!-- ACTION BUTTONS -->
              <div class="bee-product-actions">
                <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Xem chi tiết"><i class="fa-solid fa-eye"></i></a>
                <a href="{{ route('client.products.show', $product->id) }}" class="btn-action" title="Mua ngay"><i class="fa-solid fa-cart-plus"></i></a>
              </div>

              <!-- PRODUCT IMAGE -->
              <div class="bee-product-img-wrapper">
                <a href="{{ route('client.products.show', $product->id) }}">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                </a>
              </div>

              <!-- PRODUCT BODY -->
              <div class="bee-product-body d-flex flex-column flex-grow-1">
                
                <!-- Category & Time Slot Badge -->
                <div class="d-flex justify-content-between align-items-center mb-1.5 flex-wrap gap-1">
                  <span class="bee-product-category text-muted">{{ $product->category->name ?? 'Áo Nam' }}</span>
                  <span class="badge bg-warning-subtle text-dark border border-warning fs-10 px-2 py-0.5 fw-bold">
                    <i class="fa-regular fa-clock me-0.5"></i> {{ substr($deal->start_time, 0, 5) }} - {{ substr($deal->end_time, 0, 5) }}
                  </span>
                </div>

                <a href="{{ route('client.products.show', $product->id) }}" class="bee-product-title mb-2">
                  {{ $product->name }}
                </a>

                <!-- SOLD PROGRESS BAR -->
                <div class="mb-2">
                  @if($deal->quantity_limit > 0)
                    @php
                      $soldPct = min(100, round(($deal->sold_count / $deal->quantity_limit) * 100));
                    @endphp
                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.72rem;">
                      <span><i class="fa-solid fa-fire text-danger me-1"></i>Đã bán: <strong class="text-dark">{{ $deal->sold_count }}</strong></span>
                      <span>Còn {{ max(0, $deal->quantity_limit - $deal->sold_count) }} suất</span>
                    </div>
                    <div class="progress mt-1" style="height: 6px; border-radius: 99px; background: #fee2e2;">
                      <div class="progress-bar bg-danger" style="width: {{ $soldPct }}%"></div>
                    </div>
                  @else
                    <div class="d-flex align-items-center justify-content-between text-muted" style="font-size: 0.75rem;">
                      <div class="text-warning">
                        @for($i=1; $i<=5; $i++)
                          <i class="fa-solid fa-star {{ $i <= round($product->rating) ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                        @endfor
                      </div>
                      <span>Đã bán {{ number_format($product->sold_count) }}</span>
                    </div>
                  @endif
                </div>

                <!-- PRICE & SAVINGS -->
                <div class="bee-product-price-row mt-auto pt-2 border-top">
                  <div>
                    <span class="bee-product-price text-danger fs-5">{{ number_format($deal->deal_price, 0, ',', '.') }}₫</span>
                    <span class="bee-product-old-price fs-9">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                  </div>
                  @if($deal->savings_amount > 0)
                    <span class="badge bg-danger-subtle text-danger fs-11 ms-auto fw-bold">
                      Tiết kiệm {{ number_format($deal->savings_amount, 0, ',', '.') }}₫
                    </span>
                  @endif
                </div>

                <!-- CTA BUTTON -->
                <a href="{{ route('client.products.show', $product->id) }}" class="btn btn-bee-primary btn-sm w-100 mt-2.5">
                  <i class="fa-solid fa-bolt me-1"></i> {{ $isRunning ? 'SĂN DEAL NGAY' : 'XEM CHI TIẾT' }}
                </a>
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>

    <!-- PAGINATION -->
    @if($deals->hasPages())
      <div class="d-flex justify-content-center mb-5">
        {{ $deals->links() }}
      </div>
    @endif
  @else
    <div class="card border-0 shadow-sm p-5 text-center mb-5" style="border-radius: 20px;">
      <div class="py-4">
        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-4 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
          <i class="fa-solid fa-bolt fs-1"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">Không tìm thấy sản phẩm ưu đãi phù hợp</h4>
        <p class="text-muted small mb-4" style="max-width: 480px; margin: 0 auto;">
          Hiện tại các chương trình Flash Sale trong tiêu chí lọc này chưa diễn ra hoặc đã kết thúc. Vui lòng quay lại sau hoặc tham khảo toàn bộ bộ sưu tập thời trang của chúng tôi!
        </p>
        <div class="d-flex justify-content-center gap-2">
          <a href="{{ route('client.daily-deals.index') }}" class="btn btn-outline-danger btn-sm px-3.5 py-2 fw-bold rounded-pill">
            <i class="fa-solid fa-rotate-left me-1"></i> Xem Tất Cả Ưu Đãi
          </a>
          <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary btn-sm px-3.5 py-2 fw-bold rounded-pill">
            <i class="fa-solid fa-shirt me-1"></i> Khám Phá Sản Phẩm
          </a>
        </div>
      </div>
    </div>
  @endif

</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Đồng hồ đếm ngược trang Ưu Đãi Trong Ngày
    const countdownWrapper = document.getElementById("dealsPageCountdownWrapper");
    const hEl = document.getElementById("pgHours");
    const mEl = document.getElementById("pgMinutes");
    const sEl = document.getElementById("pgSeconds");

    if (countdownWrapper && hEl && mEl && sEl) {
      const targetStr = countdownWrapper.getAttribute("data-target");
      let targetTime = targetStr ? new Date(targetStr).getTime() : (Date.now() + 8 * 3600 * 1000);

      function updatePageCountdown() {
        const now = Date.now();
        const diff = Math.max(0, targetTime - now);

        const totalSecs = Math.floor(diff / 1000);
        const hours = Math.floor(totalSecs / 3600);
        const minutes = Math.floor((totalSecs % 3600) / 60);
        const seconds = totalSecs % 60;

        hEl.textContent = String(hours).padStart(2, '0');
        mEl.textContent = String(minutes).padStart(2, '0');
        sEl.textContent = String(seconds).padStart(2, '0');
      }

      updatePageCountdown();
      setInterval(updatePageCountdown, 1000);
    }
  });
</script>
@endpush
@endsection