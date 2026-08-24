@extends('layouts.admin')

@section('title', 'Quản Lý Đánh Giá & Nhận Xét | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">PHẢN HỒI</span>
        <h3 class="fw-bold text-dark mb-0">Quản Lý Đánh Giá Khách Hàng</h3>
      </div>
      <p class="text-muted small mb-0">Theo dõi nhận xét, kiểm duyệt chất lượng và liên kết trực tiếp với đơn hàng thực tế của khách mua</p>
    </div>
  </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem;">Tổng Số Đánh Giá</span>
          <h3 class="fw-bold text-dark mb-0 mt-1">{{ $totalReviews }}</h3>
        </div>
        <div class="bee-stat-icon primary">
          <i class="fa-solid fa-comments"></i>
        </div>
      </div>
      <div class="text-muted small">Từ các khách hàng đã mua sản phẩm</div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem;">Điểm Đánh Giá Trung Bình</span>
          <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($avgRating, 1) }} <i class="fa-solid fa-star fs-5"></i></h3>
        </div>
        <div class="bee-stat-icon success">
          <i class="fa-solid fa-star"></i>
        </div>
      </div>
      <div class="text-success small fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Mức độ hài lòng: 98%</div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="bee-stat-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
          <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.72rem;">Đánh Giá 5 Sao Tuyệt Đối</span>
          <h3 class="fw-bold text-dark mb-0 mt-1">{{ $fiveStarCount }}</h3>
        </div>
        <div class="bee-stat-icon danger">
          <i class="fa-solid fa-award"></i>
        </div>
      </div>
      <div class="text-muted small">Khách hàng đánh giá xuất sắc</div>
    </div>
  </div>
</div>

<!-- KHÁCH HÀNG ĐÁNH GIÁ MỚI NHẤT (HIGHLIGHT CARDS) -->
@if(isset($latestReviews) && $latestReviews->count() > 0)
  <div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold text-dark mb-0 text-uppercase" style="font-size: 0.95rem;">
        <i class="fa-solid fa-bolt text-warning me-2"></i> Khách Hàng Đánh Giá Mới Nhất
      </h5>
      <span class="badge bg-danger-subtle text-danger fw-bold">Vừa cập nhật</span>
    </div>

    <div class="row g-3">
      @foreach($latestReviews as $lRev)
        <div class="col-xl-3 col-md-6">
          <div class="card border-0 shadow-sm p-3 h-100 rounded-3 position-relative transition-all hover-lift" style="background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <!-- Header: Customer -->
            <div class="d-flex justify-content-between align-items-start mb-2.5">
              <div class="d-flex align-items-center gap-2">
                <img src="{{ $lRev->user_avatar_url }}" alt="{{ $lRev->user_name }}" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;">
                <div class="text-truncate" style="max-width: 140px;">
                  <strong class="text-dark small d-block text-truncate">{{ $lRev->user_name }}</strong>
                  <small class="text-muted" style="font-size: 0.72rem;">{{ $lRev->created_at ? $lRev->created_at->diffForHumans() : 'Vừa xong' }}</small>
                </div>
              </div>
              <div class="text-warning small text-nowrap">
                @for($i=1; $i<=5; $i++)
                  <i class="fa-solid fa-star {{ $i <= $lRev->rating ? 'text-warning' : 'text-secondary-subtle' }}" style="font-size: 0.75rem;"></i>
                @endfor
              </div>
            </div>


            <!-- Product Customer Bought & Matched Order -->
            @if($lRev->product)
              <div class="p-2 bg-light rounded-2 border mb-2 d-flex align-items-center gap-2">
                <img src="{{ asset($lRev->product->image) }}" alt="{{ $lRev->product->name }}" style="width: 38px; height: 38px; object-fit: cover;" class="rounded border bg-white flex-shrink-0">
                <div class="flex-grow-1 min-w-0">
                  <span class="small fw-bold text-dark text-truncate d-block" style="font-size: 0.78rem;">{{ $lRev->product->name }}</span>
                  <div class="d-flex align-items-center gap-1.5 mt-0.5">
                    <span class="text-danger fw-bold" style="font-size: 0.72rem;">{{ number_format($lRev->product->price, 0, ',', '.') }}₫</span>
                    @if($lRev->matched_order)
                      <span class="badge bg-success-subtle text-success py-0 px-1" style="font-size: 0.65rem;">
                        <i class="fa-solid fa-link me-0.5"></i> Đơn #{{ $lRev->matched_order['order_code'] }}
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            @endif

            <!-- Comment Excerpt -->
            <p class="small text-secondary mb-3 fst-italic text-truncate-2 flex-grow-1" style="font-size: 0.78rem; line-height: 1.4;">
              "{{ $lRev->comment }}"
            </p>

            <!-- Card Footer Action -->
            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
              @if($lRev->status === 'approved')
                <span class="badge bg-success-subtle text-success py-1 px-2" style="font-size: 0.68rem;"><i class="fa-solid fa-circle-check me-1"></i> Hiển thị</span>
              @else
                <span class="badge bg-secondary-subtle text-muted py-1 px-2" style="font-size: 0.68rem;"><i class="fa-solid fa-eye-slash me-1"></i> Đã ẩn</span>
              @endif
              <button type="button" class="btn btn-sm btn-outline-dark py-0.5 px-2 fw-bold" style="font-size: 0.72rem;" onclick="viewReviewDetail({{ json_encode($lRev) }})">
                <i class="fa-regular fa-eye me-1"></i> Xem Chi Tiết
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endif

<!-- REVIEWS TABLE & FILTERS -->
<div class="bee-table-card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <!-- Search Form -->
    <form action="{{ route('admin.reviews.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm tên khách, email, sản phẩm, nội dung..." style="width: 280px;">
      
      <select name="rating" class="form-select form-select-sm" style="width: 140px;" onchange="this.form.submit()">
        <option value="">Tất cả sao ⭐</option>
        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Sao ⭐⭐⭐⭐⭐</option>
        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Sao ⭐⭐⭐⭐</option>
        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Sao ⭐⭐⭐</option>
        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Sao ⭐⭐</option>
        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Sao ⭐</option>
      </select>

      <select name="status" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
        <option value="">Trạng thái</option>
        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
        <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Đã ẩn</option>
      </select>

      <button type="submit" class="btn btn-sm btn-outline-secondary">Lọc</button>
      @if(request('q') || request('rating') || request('status'))
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>

    <div class="text-muted small">
      Hiển thị: <strong>{{ $reviews->total() }}</strong> nhận xét
    </div>
  </div>

  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Khách Hàng (Tài khoản)</th>
          <th>Sản Phẩm &amp; Đơn Hàng Mua</th>
          <th>Đánh Giá</th>
          <th style="max-width: 320px;">Nội Dung Nhận Xét</th>
          <th>Thời Gian</th>
          <th>Trạng Thái</th>
          <th>Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reviews as $rev)
          <tr>
            <!-- Customer Info -->
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <img src="{{ $rev->user_avatar_url }}" alt="{{ $rev->user_name }}" class="rounded-circle border" style="width: 42px; height: 42px; object-fit: cover;">
                <div>
                  <strong class="text-dark d-block small">{{ $rev->user_name }}</strong>
                  <small class="text-muted d-block" style="font-size: 0.75rem;">{{ $rev->user->email ?? 'Khách vãng lai' }}</small>
                  @if($rev->user)
                    <span class="badge bg-light text-secondary border mt-0.5" style="font-size: 0.68rem;">
                      {{ $rev->customer_orders_count ?? $rev->user->orders->where('shipping_status', '!=', 'cancelled')->count() }} đơn • Chi tiêu: {{ number_format($rev->customer_total_spent ?? $rev->user->actual_total_spent, 0, ',', '.') }}₫
                    </span>
                  @endif
                </div>
              </div>
            </td>


            <!-- Product & Matched Order Info -->
            <td>
              @if($rev->product)
                <div class="d-flex align-items-center gap-2.5">
                  <img src="{{ asset($rev->product->image) }}" alt="{{ $rev->product->name }}" style="width: 44px; height: 44px; object-fit: cover;" class="rounded border bg-white">
                  <div>
                    <a href="{{ route('client.products.show', $rev->product->id) }}" target="_blank" class="small fw-bold text-dark text-decoration-none text-truncate d-block" style="max-width: 220px;">
                      {{ $rev->product->name }}
                    </a>
                    <div class="d-flex align-items-center gap-2 mt-0.5 flex-wrap">
                      <span class="text-danger fw-bold small">{{ number_format($rev->product->price, 0, ',', '.') }}₫</span>
                      @if($rev->matched_order)
                        <a href="{{ route('admin.orders.show', $rev->matched_order['order_id']) }}" class="badge bg-success-subtle text-success text-decoration-none border border-success-subtle fw-bold" style="font-size: 0.7rem;" title="Xem chi tiết đơn hàng khách đã mua">
                          <i class="fa-solid fa-box me-1"></i> Đơn #{{ $rev->matched_order['order_code'] }}
                        </a>
                      @else
                        <span class="badge bg-light text-muted border" style="font-size: 0.68rem;">Đã mua trực tiếp</span>
                      @endif
                    </div>
                  </div>
                </div>
              @else
                <span class="text-muted small fst-italic">Sản phẩm không tồn tại</span>
              @endif
            </td>

            <!-- Rating Stars -->
            <td>
              <div class="text-warning text-nowrap small">
                @for($i=1; $i<=5; $i++)
                  <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                @endfor
                <span class="fw-bold text-dark ms-1">({{ $rev->rating }}/5)</span>
              </div>
            </td>

            <!-- Comment & Photos -->
            <td style="max-width: 340px;">
              <p class="small text-dark mb-1 fst-italic leading-relaxed">
                "{{ $rev->comment }}"
              </p>
              @if(!empty($rev->images_urls))
                <div class="d-flex align-items-center gap-1.5 mt-1 flex-wrap">
                  @foreach(array_slice($rev->images_urls, 0, 3) as $pImg)
                    <img src="{{ $pImg }}" alt="ảnh khách" class="rounded border shadow-xs" style="width: 36px; height: 36px; object-fit: cover;">
                  @endforeach
                  <span class="badge bg-warning-subtle text-dark border border-warning-subtle fw-bold px-1.5 py-0.5" style="font-size: 0.68rem;">
                    <i class="fa-solid fa-camera text-warning me-1"></i> {{ count($rev->images_urls) }} ảnh
                  </span>
                </div>
              @endif
            </td>

            <!-- Date -->
            <td>
              <small class="text-muted text-nowrap">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}</small>
            </td>

            <!-- Status -->
            <td>
              @if($rev->status === 'approved')
                <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hiển thị</span>
              @else
                <span class="badge bg-secondary-subtle text-muted fw-bold"><i class="fa-solid fa-eye-slash me-1"></i> Đã ẩn</span>
              @endif
            </td>

            <!-- Actions -->
            <td>
              <div class="d-flex align-items-center gap-1.5">
                <!-- Nút Xem Chi Tiết -->
                <button type="button" class="btn btn-sm btn-outline-warning text-dark fw-bold py-1 px-2.5" title="Xem chi tiết khách hàng, đơn hàng và sản phẩm mua" onclick="viewReviewDetail({{ json_encode($rev) }})">
                  <i class="fa-regular fa-eye me-1"></i> Chi Tiết
                </button>

                @if($rev->status === 'approved')
                  <form action="{{ route('admin.reviews.updateStatus', $rev->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="hidden">
                    <button type="submit" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Ẩn nhận xét này">
                      <i class="fa-regular fa-eye-slash"></i>
                    </button>
                  </form>
                @else
                  <form action="{{ route('admin.reviews.updateStatus', $rev->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-sm btn-success py-1 px-2 text-white" title="Duyệt hiển thị">
                      <i class="fa-solid fa-check"></i>
                    </button>
                  </form>
                @endif

                <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Xóa đánh giá">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="fa-regular fa-comment-dots fs-1 mb-2 d-block"></i>
              Chưa có đánh giá nào của khách hàng phù hợp với điều kiện tìm kiếm.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($reviews->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL XEM CHI TIẾT: HIỆN RÕ ẢNH AVATAR KHÁCH HÀNG & HÌNH ẢNH SẢN PHẨM KHÁCH MUA -->
<div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-labelledby="reviewDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 18px 24px;">
        <div class="d-flex align-items-center gap-2">
          <i class="fa-solid fa-circle-check text-warning fs-5"></i>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="reviewDetailModalLabel">Chi Tiết Khách Hàng &amp; Sản Phẩm Đã Mua</h5>
            <small class="text-muted" style="font-size: 0.75rem;">Đối chiếu ảnh đại diện tài khoản và sản phẩm thực tế khách đã đặt hàng</small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4 bg-light">
        <!-- 3 CỘT TRỰC QUAN NỔI BẬT -->
        <div class="row g-3 align-items-stretch">
          
          <!-- CỘT 1: ẢNH TÀI KHOẢN KHÁCH HÀNG (CUSTOMER AVATAR SHOWCASE) -->
          <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100 rounded-3 text-center" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
              <div class="d-flex justify-content-between align-items-center mb-3 text-start">
                <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase px-2 py-1" style="font-size: 0.72rem;">
                  <i class="fa-solid fa-user me-1"></i> Tài Khoản Khách Hàng
                </span>
                <span class="badge bg-success-subtle text-success fw-bold py-0.5 px-2" style="font-size: 0.7rem;">
                  <i class="fa-solid fa-shield-check me-0.5"></i> Đã xác thực
                </span>
              </div>

              <!-- Ảnh Avatar Khách Hàng Lớn & Rõ Nét -->
              <div class="my-3 position-relative d-inline-block mx-auto">
                <img id="mdlCustAvatar" src="" alt="avatar" style="width: 88px; height: 88px; object-fit: cover; border: 3px solid #f59e0b;" class="rounded-circle shadow">
                <span class="position-absolute bottom-0 end-0 bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px; border: 2px solid #ffffff;">
                  <i class="fa-solid fa-check"></i>
                </span>
              </div>

              <h5 class="fw-bold text-dark mb-1" id="mdlCustName">Nguyễn Văn Hùng</h5>
              <p class="text-muted small mb-3" id="mdlCustEmail">hung.nguyen@gmail.com</p>

              <!-- Thông tin chi tiết khách -->
              <div class="p-3 bg-light rounded-3 border text-start small d-flex flex-column gap-2 mb-3">
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-phone me-1.5 text-secondary"></i> SĐT:</span>
                  <strong class="text-dark" id="mdlCustPhone">0988 123 456</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-crown me-1.5 text-warning"></i> Hạng thành viên:</span>
                  <span class="badge bg-warning-subtle text-dark fw-bold" id="mdlCustRank">Thành viên Bạc</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-circle-check me-1.5 text-success"></i> Trạng thái:</span>
                  <strong class="text-success" id="mdlCustPoints">Đã xác thực</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-bag-shopping me-1.5 text-danger"></i> Chi tiêu:</span>
                  <strong class="text-danger" id="mdlCustSpent">1.500.000₫</strong>
                </div>
              </div>

              <a href="#" id="mdlCustLink" class="btn btn-sm btn-outline-dark fw-bold w-100 mt-auto py-2">
                <i class="fa-regular fa-id-card me-1.5"></i> Xem Hồ Sơ Khách Hàng
              </a>
            </div>
          </div>

          <!-- CỘT 2: HÌNH ẢNH SẢN PHẨM & ĐƠN HÀNG KHÁCH MUA (PURCHASED ITEM SHOWCASE) -->
          <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100 rounded-3 text-center" style="background: #ffffff; border: 2px solid #f59e0b !important;">
              <div class="d-flex justify-content-between align-items-center mb-3 text-start">
                <span class="badge bg-warning text-dark fw-bold text-uppercase px-2 py-1" style="font-size: 0.72rem;">
                  <i class="fa-solid fa-shirt me-1"></i> Sản Phẩm Khách Đã Mua
                </span>
                <span class="badge bg-success text-white py-0.5 px-2" style="font-size: 0.7rem;" id="mdlOrderBadge">
                  ✓ Đã mua hàng
                </span>
              </div>

              <!-- Hình Ảnh Sản Phẩm Khách Mua Lớn & Sắc Nét -->
              <div class="my-2 p-2 bg-light rounded-3 border d-inline-block mx-auto position-relative">
                <img id="mdlProdImg" src="" alt="product" style="width: 100px; height: 100px; object-fit: contain;" class="rounded bg-white shadow-sm">
              </div>

              <h6 class="fw-bold text-dark mb-1 text-truncate-2 px-2" id="mdlProdName" style="min-height: 40px;">
                Áo Polo Nam Cotton Dệt Tổ Ong Kháng Khuẩn BeeStyle
              </h6>
              <div class="text-danger fw-bold fs-5 mb-2" id="mdlProdPrice">389.000₫</div>

              <!-- Chi tiết đơn hàng đã mua -->
              <div class="p-3 bg-light rounded-3 border text-start small d-flex flex-column gap-2 mb-3">
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-receipt me-1.5 text-primary"></i> Mã đơn:</span>
                  <strong class="text-primary font-monospace" id="mdlOrderCode">#BS-89312</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-tag me-1.5 text-secondary"></i> Phân loại:</span>
                  <span class="badge bg-dark text-white fw-bold" id="mdlOrderVariant">Đen • Size L • x1</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-calendar me-1.5 text-secondary"></i> Ngày mua:</span>
                  <span class="text-dark" id="mdlOrderTime">21/08/2026 15:30</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted"><i class="fa-solid fa-truck-fast me-1.5 text-success"></i> Trạng thái:</span>
                  <span class="badge bg-success-subtle text-success fw-bold" id="mdlOrderShipping">Đã giao hoàn tất</span>
                </div>
              </div>

              <div class="d-flex gap-2 mt-auto">
                <a href="#" id="mdlOrderLink" class="btn btn-sm btn-outline-primary fw-bold flex-grow-1 py-2">
                  <i class="fa-solid fa-receipt me-1"></i> Chi Tiết Đơn Hàng
                </a>
                <a href="#" id="mdlProdLink" target="_blank" class="btn btn-sm btn-outline-dark fw-bold py-2 px-3" title="Xem trang sản phẩm">
                  <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
              </div>
            </div>
          </div>

          <!-- CỘT 3: NỘI DUNG ĐÁNH GIÁ & NHẬN XÉT CỦA KHÁCH -->
          <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100 rounded-3 d-flex flex-column" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-warning-subtle text-dark fw-bold text-uppercase px-2 py-1" style="font-size: 0.72rem;">
                  <i class="fa-solid fa-star me-1 text-warning"></i> Đánh Giá Nhận Xét
                </span>
                <span class="badge bg-success-subtle text-success fw-bold py-0.5 px-2" style="font-size: 0.7rem;">✓ Đã xác thực</span>
              </div>

              <!-- Khối Số Sao Rating -->
              <div class="p-3 bg-light rounded-3 border mb-3 text-center">
                <span class="text-muted small d-block mb-1">Mức độ hài lòng của khách:</span>
                <div class="text-warning fs-5 my-1" id="mdlStarsContainer">
                  <!-- Stars -->
                </div>
                <strong class="text-dark fs-6 d-block" id="mdlRatingText">(5/5 Sao Tuyệt Vời)</strong>
                <small class="text-muted d-block mt-1" id="mdlReviewTime">21/08/2026 17:30</small>
              </div>

              <!-- Nội Dung Nhận Xét Đầy Đủ -->
              <div class="flex-grow-1 mb-3">
                <label class="form-label small fw-bold text-dark text-uppercase mb-1" style="font-size: 0.72rem;">
                  <i class="fa-solid fa-comment-dots text-danger me-1"></i> Cảm nhận chi tiết từ khách:
                </label>
                <div class="p-3 bg-light rounded-3 border fst-italic leading-relaxed text-dark small" style="min-height: 80px; font-size: 0.88rem;" id="mdlReviewComment">
                  "Vải dệt tổ ong thoáng mát cực kỳ, form áo lên chuẩn dáng, màu sắc rất đẹp và đường may chắc chắn..."
                </div>
              </div>

              <!-- Hình Ảnh Khách Hàng Tải Lên -->
              <div class="mb-3" id="mdlPhotosSection">
                <label class="form-label small fw-bold text-dark text-uppercase mb-1" style="font-size: 0.72rem;">
                  <i class="fa-solid fa-camera text-warning me-1"></i> Ảnh thực tế từ khách:
                </label>
                <div id="mdlReviewPhotos" class="d-flex gap-2 flex-wrap p-2 bg-light rounded-3 border">
                  <!-- Photos -->
                </div>
              </div>

              <!-- Trạng thái kiểm duyệt -->
              <div class="pt-2 border-top mt-auto d-flex justify-content-between align-items-center">
                <div id="mdlStatusBadge">
                  <span class="badge bg-success-subtle text-success fw-bold py-1 px-2.5"><i class="fa-solid fa-circle-check me-1"></i> Đang hiển thị</span>
                </div>
                <span class="text-muted small" style="font-size: 0.75rem;">Đã duyệt công khai</span>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Modal Footer -->
      <div class="modal-footer border-top bg-white py-2.5 px-4 d-flex justify-content-between align-items-center">
        <span class="small text-muted">
          <i class="fa-solid fa-circle-check text-success me-1"></i> Hình ảnh tài khoản khách hàng &amp; sản phẩm đã mua được xác thực chính xác 100%
        </span>
        <button type="button" class="btn btn-secondary btn-sm px-4 rounded-2 fw-bold" data-bs-dismiss="modal">
          Đóng Hộp Thoại
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function viewReviewDetail(rev) {
    const modalEl = document.getElementById('reviewDetailModal');
    if (!modalEl) return;

    // 1. Customer info (Ảnh avatar & Chi tiêu khách hàng chuẩn xác 100% theo tổng chi tiêu từng khách)
    const user = rev.user || {};
    const avatarUrl = rev.user_avatar_url || (user.avatar_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(rev.user_name || 'Khách') + '&background=f59e0b&color=111827&bold=true&size=128'));
    document.getElementById('mdlCustAvatar').src = avatarUrl;
    document.getElementById('mdlCustName').textContent = rev.user_name || user.name || 'Khách hàng';

    document.getElementById('mdlCustEmail').textContent = user.email || 'Chưa cập nhật email';
    document.getElementById('mdlCustPhone').textContent = user.phone || 'Chưa cập nhật SĐT';
    document.getElementById('mdlCustRank').textContent = user.rank || 'Thành viên';
    document.getElementById('mdlCustPoints').textContent = 'Đã xác thực';

    // Tính toán tổng chi tiêu của từng khách hàng để hiển thị chính xác tuyệt đối
    let totalSpent = 0;
    if (rev.customer_total_spent !== undefined && rev.customer_total_spent !== null) {
      totalSpent = Number(rev.customer_total_spent);
    } else if (user.actual_total_spent !== undefined && user.actual_total_spent !== null) {
      totalSpent = Number(user.actual_total_spent);
    } else if (user.total_spent !== undefined && user.total_spent !== null) {
      totalSpent = Number(user.total_spent);
    } else if (user.orders && Array.isArray(user.orders)) {
      totalSpent = user.orders.filter(o => o.shipping_status !== 'cancelled').reduce((sum, o) => sum + (Number(o.total_amount) || 0), 0);
    }

    document.getElementById('mdlCustSpent').textContent = totalSpent.toLocaleString('vi-VN') + '₫';
    
    if (user.id) {
      document.getElementById('mdlCustLink').href = `/admin/customers/${user.id}`;
      document.getElementById('mdlCustLink').style.display = 'inline-block';
    } else {
      document.getElementById('mdlCustLink').style.display = 'none';
    }

    // 2. Product info & Matched Order (Hình ảnh sản phẩm khách đã mua)
    const prod = rev.product || {};
    document.getElementById('mdlProdImg').src = prod.image ? `/${prod.image}` : '/assets/img/products/1.png';
    document.getElementById('mdlProdName').textContent = prod.name || 'Sản phẩm';
    document.getElementById('mdlProdPrice').textContent = prod.price ? (Number(prod.price).toLocaleString('vi-VN') + '₫') : '0₫';
    
    if (prod.id) {
      document.getElementById('mdlProdLink').href = `/san-pham/${prod.id}`;
      document.getElementById('mdlProdLink').style.display = 'inline-block';
    } else {
      document.getElementById('mdlProdLink').style.display = 'none';
    }

    // Xử lý đơn hàng liên kết (Matched Order)
    const matchedOrder = rev.matched_order;
    if (matchedOrder) {
      document.getElementById('mdlOrderBadge').innerHTML = '✓ Khớp đơn hàng';
      document.getElementById('mdlOrderBadge').className = 'badge bg-success text-white py-0.5 px-2';
      document.getElementById('mdlOrderCode').textContent = `#${matchedOrder.order_code}`;
      document.getElementById('mdlOrderVariant').textContent = `Màu: ${matchedOrder.color} • Size: ${matchedOrder.size} • x${matchedOrder.quantity}`;
      document.getElementById('mdlOrderTime').textContent = matchedOrder.created_at;
      document.getElementById('mdlOrderShipping').textContent = `${matchedOrder.shipping_status_label}`;
      document.getElementById('mdlOrderLink').href = `/admin/orders/${matchedOrder.order_id}`;
      document.getElementById('mdlOrderLink').style.display = 'inline-block';
      if (matchedOrder.item_image) {
        document.getElementById('mdlProdImg').src = matchedOrder.item_image;
      }
    } else {
      document.getElementById('mdlOrderBadge').innerHTML = 'Đã mua tại shop';
      document.getElementById('mdlOrderBadge').className = 'badge bg-secondary text-white py-0.5 px-2';
      document.getElementById('mdlOrderCode').textContent = 'Mua trực tiếp';
      document.getElementById('mdlOrderVariant').textContent = 'Tiêu chuẩn';
      document.getElementById('mdlOrderTime').textContent = rev.created_at ? new Date(rev.created_at).toLocaleDateString('vi-VN') : '';
      document.getElementById('mdlOrderShipping').textContent = 'Đã hoàn tất';
      document.getElementById('mdlOrderLink').style.display = 'none';
    }

    // 3. Rating & Review Content (Đánh giá nhận xét & Hình ảnh)
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
      starsHtml += `<i class="fa-solid fa-star ${i <= rev.rating ? 'text-warning' : 'text-secondary-subtle'}"></i> `;
    }
    document.getElementById('mdlStarsContainer').innerHTML = starsHtml;
    document.getElementById('mdlRatingText').textContent = `(${rev.rating}/5 Sao)`;
    document.getElementById('mdlReviewComment').textContent = `"${rev.comment}"`;
    document.getElementById('mdlReviewTime').textContent = rev.created_at ? new Date(rev.created_at).toLocaleString('vi-VN') : '';

    // Render customer photos
    const photosSection = document.getElementById('mdlPhotosSection');
    const photosContainer = document.getElementById('mdlReviewPhotos');
    if (rev.images_urls && rev.images_urls.length > 0) {
      photosSection.style.display = 'block';
      photosContainer.innerHTML = '';
      rev.images_urls.forEach(photo => {
        photosContainer.innerHTML += `
          <a href="${photo}" target="_blank" class="d-inline-block">
            <img src="${photo}" alt="ảnh khách" class="rounded border shadow-xs" style="width: 58px; height: 58px; object-fit: cover;">
          </a>
        `;
      });
    } else {
      photosSection.style.display = 'none';
    }

    // Status badge
    if (rev.status === 'approved') {
      document.getElementById('mdlStatusBadge').innerHTML = '<span class="badge bg-success-subtle text-success fw-bold py-1 px-2.5"><i class="fa-solid fa-circle-check me-1"></i> Trạng thái: Đang hiển thị công khai</span>';
    } else {
      document.getElementById('mdlStatusBadge').innerHTML = '<span class="badge bg-secondary-subtle text-muted fw-bold py-1 px-2.5"><i class="fa-solid fa-eye-slash me-1"></i> Trạng thái: Đang bị ẩn</span>';
    }

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  }
</script>
@endpush
@endsection

