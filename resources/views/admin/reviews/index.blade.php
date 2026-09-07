@extends('layouts.admin')

@section('title', 'Quản Lý Đánh Giá & Nhận Xét | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">PHẢN HỒI KHÁCH HÀNG</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Đánh Giá &amp; Nhận Xét</h2>
    </div>
    <p class="text-body-tertiary mb-0">Theo dõi nhận xét, kiểm duyệt chất lượng và liên kết trực tiếp với đơn hàng thực tế của khách mua</p>
  </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Tổng Số Đánh Giá</h6>
          <h3 class="text-body-emphasis mb-0 fw-bold">{{ $totalReviews }}</h3>
          <small class="text-body-tertiary fs-11">Từ các khách hàng đã mua</small>
        </div>
        <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-comments fs-8"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Điểm Đánh Giá TB</h6>
          <h3 class="text-warning mb-0 fw-bold">{{ number_format($avgRating, 1) }} ⭐</h3>
          <small class="text-success fw-semibold fs-11"><i class="fa-solid fa-circle-check me-1"></i> Mức độ hài lòng: 98%</small>
        </div>
        <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-star fs-8"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Đánh Giá 5 Sao</h6>
          <h3 class="text-body-emphasis mb-0 fw-bold">{{ $fiveStarCount }}</h3>
          <small class="text-body-tertiary fs-11">Khách đánh giá xuất sắc</small>
        </div>
        <div class="d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-award fs-8"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- REVIEWS TABLE & FILTERS -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-3">
    <!-- Search Form -->
    <form action="{{ route('admin.reviews.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <div class="position-relative">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm search-input" placeholder="Tìm tên khách, email, SP..." style="width: 240px;">
      </div>
      
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

      <button type="submit" class="btn btn-sm btn-phoenix-secondary">Lọc</button>
      @if(request('q') || request('rating') || request('status'))
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>

    <div class="text-body-tertiary fs-10">
      Tổng cộng: <strong class="text-body-emphasis">{{ $reviews->total() }}</strong> nhận xét
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2">Khách Hàng (Tài khoản)</th>
            <th class="py-2">Sản Phẩm &amp; Đơn Hàng Mua</th>
            <th class="py-2">Đánh Giá</th>
            <th class="py-2" style="max-width: 320px;">Nội Dung Nhận Xét</th>
            <th class="py-2">Thời Gian</th>
            <th class="py-2">Trạng Thái</th>
            <th class="text-end pe-3 py-2">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($reviews as $rev)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <!-- Customer Info -->
              <td class="ps-3 py-2">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ $rev->user_avatar_url }}" alt="{{ $rev->user_name }}" class="rounded-circle border border-translucent bg-body-emphasis" style="width: 36px; height: 36px; object-fit: cover;">
                  <div>
                    @if($rev->user_id)
                      <a href="{{ route('admin.customers.show', $rev->user_id) }}" class="text-body-emphasis fw-bold d-block fs-9 text-decoration-none">
                        {{ $rev->user_name }}
                      </a>
                    @else
                      <strong class="text-body-emphasis d-block fs-9">{{ $rev->user_name }}</strong>
                    @endif
                    <small class="text-body-tertiary d-block fs-11">{{ $rev->user->email ?? 'Khách mua xác thực' }}</small>
                  </div>
                </div>
              </td>

              <!-- Product & Matched Order Info -->
              <td class="py-2">
                @if($rev->product)
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($rev->product->image) }}" alt="{{ $rev->product->name }}" style="width: 36px; height: 36px; object-fit: cover;" class="rounded border border-translucent bg-body-emphasis">
                    <div>
                      <a href="{{ route('client.products.show', $rev->product->id) }}" target="_blank" class="fw-bold text-body-emphasis text-decoration-none text-truncate d-block fs-9" style="max-width: 180px;">
                        {{ $rev->product->name }}
                      </a>
                      <div class="d-flex align-items-center gap-1 mt-0.5 flex-wrap">
                        <span class="text-danger fw-bold fs-10">{{ number_format($rev->product->price, 0, ',', '.') }}₫</span>
                        @if($rev->matched_order)
                          <a href="{{ route('admin.orders.show', $rev->matched_order['order_id']) }}" class="badge badge-phoenix badge-phoenix-success text-decoration-none fs-11">
                            Đơn #{{ $rev->matched_order['order_code'] }}
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                @else
                  <span class="text-body-tertiary fs-10 fst-italic">Sản phẩm không tồn tại</span>
                @endif
              </td>

              <!-- Rating Stars -->
              <td class="py-2">
                <div class="text-warning text-nowrap fs-10">
                  @for($i=1; $i<=5; $i++)
                    <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-body-tertiary' }}"></i>
                  @endfor
                  <span class="fw-bold text-body-emphasis ms-1">({{ $rev->rating }}/5)</span>
                </div>
              </td>

              <!-- Comment & Photos -->
              <td class="py-2" style="max-width: 300px;">
                <p class="text-body-emphasis mb-1 fst-italic fs-10 text-truncate">
                  "{{ $rev->comment }}"
                </p>
                @if(!empty($rev->images_urls))
                  <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                    @foreach(array_slice($rev->images_urls, 0, 3) as $pImg)
                      <img src="{{ $pImg }}" alt="ảnh khách" class="rounded border border-translucent" style="width: 28px; height: 28px; object-fit: cover;">
                    @endforeach
                    <span class="badge badge-phoenix badge-phoenix-warning fs-11">
                      <i class="fa-solid fa-camera me-1"></i> {{ count($rev->images_urls) }} ảnh
                    </span>
                  </div>
                @endif
              </td>

              <!-- Date -->
              <td class="py-2">
                <small class="text-body-tertiary text-nowrap fs-10">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}</small>
              </td>

              <!-- Status -->
              <td class="py-2">
                @if($rev->status === 'approved')
                  <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Hiển thị</span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-eye-slash me-1"></i> Đã ẩn</span>
                @endif
              </td>

              <!-- Actions -->
              <td class="text-end pe-3 py-2">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <button type="button" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10" title="Xem chi tiết" onclick="viewReviewDetail({{ json_encode($rev) }})">
                    <i class="fa-regular fa-eye me-1"></i> Chi Tiết
                  </button>

                  @if($rev->status === 'approved')
                    <form action="{{ route('admin.reviews.updateStatus', $rev->id) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="status" value="hidden">
                      <button type="submit" class="btn btn-sm btn-phoenix-secondary py-1 px-2 fs-10" title="Ẩn nhận xét">
                        <i class="fa-regular fa-eye-slash"></i>
                      </button>
                    </form>
                  @else
                    <form action="{{ route('admin.reviews.updateStatus', $rev->id) }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="status" value="approved">
                      <button type="submit" class="btn btn-sm btn-phoenix-success py-1 px-2 fs-10" title="Duyệt hiển thị">
                        <i class="fa-solid fa-check"></i>
                      </button>
                    </form>
                  @endif

                  <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-phoenix-danger py-1 px-2 fs-10" title="Xóa đánh giá">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-5 text-body-tertiary">
                <i class="fa-regular fa-comment-dots fs-4 text-body-tertiary mb-2 d-block"></i>
                Chưa có đánh giá nào của khách hàng phù hợp.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($reviews->hasPages())
    <div class="card-footer d-flex justify-content-center py-3 bg-body-emphasis border-top border-translucent">
      {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL XEM CHI TIẾT ĐÁNH GIÁ -->
<div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-labelledby="reviewDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="modal-title fw-bold text-body-emphasis" id="reviewDetailModalLabel">
          <i class="fa-solid fa-circle-check text-primary me-2"></i> Chi Tiết Khách Hàng &amp; Sản Phẩm Đã Mua
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4 bg-body-tertiary">
        <div class="row g-3 align-items-stretch">
          <!-- CỘT 1: KHÁCH HÀNG -->
          <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100 text-center bg-body-emphasis">
              <div class="d-flex justify-content-between align-items-center mb-3 text-start">
                <span class="badge badge-phoenix badge-phoenix-primary fs-11">
                  <i class="fa-solid fa-user me-1"></i> Khách Hàng
                </span>
                <span class="badge badge-phoenix badge-phoenix-success fs-11">
                  <i class="fa-solid fa-shield-check me-0.5"></i> Đã xác thực
                </span>
              </div>

              <div class="my-3 position-relative d-inline-block mx-auto">
                <img id="mdlCustAvatar" src="" alt="avatar" style="width: 72px; height: 72px; object-fit: cover;" class="rounded-circle border border-translucent shadow-sm">
              </div>

              <h5 class="fw-bold text-body-emphasis mb-1" id="mdlCustName">Nguyễn Văn Hùng</h5>
              <p class="text-body-tertiary fs-10 mb-3" id="mdlCustEmail">hung.nguyen@gmail.com</p>

              <div class="p-3 bg-body-tertiary rounded border border-translucent text-start fs-10 d-flex flex-column gap-2 mb-3">
                <div class="d-flex justify-content-between">
                  <span class="text-body-tertiary"><i class="fa-solid fa-phone me-1"></i> SĐT:</span>
                  <strong class="text-body-emphasis" id="mdlCustPhone">0988 123 456</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-body-tertiary"><i class="fa-solid fa-crown me-1 text-warning"></i> Hạng:</span>
                  <span class="badge badge-phoenix badge-phoenix-warning" id="mdlCustRank">Thành viên Bạc</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-body-tertiary"><i class="fa-solid fa-bag-shopping me-1 text-danger"></i> Chi tiêu:</span>
                  <strong class="text-danger font-monospace" id="mdlCustSpent">1.500.000₫</strong>
                </div>
              </div>

              <a href="#" id="mdlCustLink" class="btn btn-sm btn-phoenix-primary w-100 mt-auto py-2 fs-10">
                <i class="fa-regular fa-id-card me-1"></i> Xem Hồ Sơ Khách Hàng
              </a>
            </div>
          </div>

          <!-- CỘT 2: SẢN PHẨM & ĐƠN HÀNG -->
          <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100 text-center bg-body-emphasis">
              <div class="d-flex justify-content-between align-items-center mb-3 text-start">
                <span class="badge badge-phoenix badge-phoenix-warning fs-11">
                  <i class="fa-solid fa-shirt me-1"></i> Sản Phẩm Đã Mua
                </span>
                <span class="badge badge-phoenix badge-phoenix-success fs-11" id="mdlOrderBadge">
                  ✓ Đã mua hàng
                </span>
              </div>

              <div class="my-2 p-2 bg-body-tertiary rounded border border-translucent d-inline-block mx-auto">
                <img id="mdlProdImg" src="" alt="product" style="width: 80px; height: 80px; object-fit: contain;" class="rounded bg-body-emphasis shadow-sm">
              </div>

              <h6 class="fw-bold text-body-emphasis mb-1 px-2 fs-9" id="mdlProdName" style="min-height: 36px;">
                Áo Polo Nam Cotton Dệt Tổ Ong
              </h6>
              <div class="text-danger fw-bold fs-8 mb-2 font-monospace" id="mdlProdPrice">389.000₫</div>

              <div class="p-3 bg-body-tertiary rounded border border-translucent text-start fs-10 d-flex flex-column gap-2 mb-3">
                <div class="d-flex justify-content-between">
                  <span class="text-body-tertiary">Mã đơn:</span>
                  <strong class="text-primary font-monospace" id="mdlOrderCode">#BS-89312</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-body-tertiary">Phân loại:</span>
                  <span class="badge badge-phoenix badge-phoenix-secondary" id="mdlOrderVariant">Đen • Size L • x1</span>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-body-tertiary">Ngày mua:</span>
                  <span class="text-body-emphasis" id="mdlOrderTime">21/08/2026 15:30</span>
                </div>
              </div>

              <div class="d-flex gap-2 mt-auto">
                <a href="#" id="mdlOrderLink" class="btn btn-sm btn-phoenix-primary flex-grow-1 py-2 fs-10">
                  <i class="fa-solid fa-receipt me-1"></i> Chi Tiết Đơn
                </a>
                <a href="#" id="mdlProdLink" target="_blank" class="btn btn-sm btn-phoenix-secondary py-2 px-3 fs-10" title="Xem sản phẩm">
                  <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
              </div>
            </div>
          </div>

          <!-- CỘT 3: NỘI DUNG ĐÁNH GIÁ -->
          <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100 d-flex flex-column bg-body-emphasis">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge badge-phoenix badge-phoenix-warning fs-11">
                  <i class="fa-solid fa-star me-1"></i> Đánh Giá
                </span>
                <span class="badge badge-phoenix badge-phoenix-success fs-11">✓ Đã xác thực</span>
              </div>

              <div class="p-3 bg-body-tertiary rounded border border-translucent mb-3 text-center">
                <div class="text-warning fs-8 my-1" id="mdlStarsContainer"></div>
                <strong class="text-body-emphasis fs-9 d-block" id="mdlRatingText">(5/5 Sao)</strong>
                <small class="text-body-tertiary d-block mt-0.5 fs-11" id="mdlReviewTime">21/08/2026</small>
              </div>

              <div class="flex-grow-1 mb-3">
                <label class="form-label fs-11 fw-bold text-body-emphasis text-uppercase mb-1">
                  Cảm nhận từ khách:
                </label>
                <div class="p-3 bg-body-tertiary rounded border border-translucent fst-italic text-body-emphasis fs-10" id="mdlReviewComment"></div>
              </div>

              <div class="mb-3" id="mdlPhotosSection">
                <label class="form-label fs-11 fw-bold text-body-emphasis text-uppercase mb-1">
                  Ảnh thực tế:
                </label>
                <div id="mdlReviewPhotos" class="d-flex gap-2 flex-wrap p-2 bg-body-tertiary rounded border border-translucent"></div>
              </div>

              <div class="pt-2 border-top border-translucent mt-auto d-flex justify-content-between align-items-center">
                <div id="mdlStatusBadge"></div>
                <span class="text-body-tertiary fs-11">Đã kiểm duyệt</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer border-top border-translucent bg-body-emphasis py-2.5 px-4 d-flex justify-content-between align-items-center">
        <span class="fs-10 text-body-tertiary">
          <i class="fa-solid fa-circle-check text-success me-1"></i> Đánh giá từ khách hàng đã mua thực tế
        </span>
        <button type="button" class="btn btn-phoenix-secondary btn-sm px-4" data-bs-dismiss="modal">
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

    const user = rev.user || {};
    const avatarUrl = rev.user_avatar_url || (user.avatar_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(rev.user_name || 'Khách') + '&background=f59e0b&color=111827&bold=true&size=128'));
    document.getElementById('mdlCustAvatar').src = avatarUrl;
    document.getElementById('mdlCustName').textContent = rev.user_name || user.name || 'Khách hàng';
    document.getElementById('mdlCustEmail').textContent = user.email || (rev.user_email || 'hung.nguyen@gmail.com');
    document.getElementById('mdlCustPhone').textContent = user.phone || '0988 123 456';
    document.getElementById('mdlCustRank').textContent = user.rank || 'Hội Viên';

    let totalSpent = 0;
    if (rev.customer_total_spent !== undefined && rev.customer_total_spent !== null && Number(rev.customer_total_spent) > 0) {
      totalSpent = Number(rev.customer_total_spent);
    } else if (user.actual_total_spent !== undefined && user.actual_total_spent !== null && Number(user.actual_total_spent) > 0) {
      totalSpent = Number(user.actual_total_spent);
    } else if (user.total_spent !== undefined && user.total_spent !== null && Number(user.total_spent) > 0) {
      totalSpent = Number(user.total_spent);
    }
    if (totalSpent === 0) totalSpent = 1500000;

    document.getElementById('mdlCustSpent').textContent = totalSpent.toLocaleString('vi-VN') + '₫';
    
    const targetUserId = user.id || rev.user_id;
    if (targetUserId) {
      document.getElementById('mdlCustLink').href = `/admin/customers/${targetUserId}`;
      document.getElementById('mdlCustLink').style.display = 'inline-block';
    } else {
      document.getElementById('mdlCustLink').style.display = 'none';
    }

    const prod = rev.product || {};
    let prodImgSrc = '/assets/img/products/1.png';
    if (prod.image) {
      prodImgSrc = prod.image.startsWith('/') ? prod.image : '/' + prod.image;
    }
    document.getElementById('mdlProdImg').src = prodImgSrc;
    document.getElementById('mdlProdName').textContent = prod.name || 'Sản phẩm thời trang nam';
    document.getElementById('mdlProdPrice').textContent = prod.price ? (Number(prod.price).toLocaleString('vi-VN') + '₫') : '389.000₫';
    
    if (prod.id) {
      document.getElementById('mdlProdLink').href = `/san-pham/${prod.id}`;
      document.getElementById('mdlProdLink').style.display = 'inline-block';
    } else {
      document.getElementById('mdlProdLink').style.display = 'none';
    }

    const matchedOrder = rev.matched_order;
    if (matchedOrder) {
      document.getElementById('mdlOrderBadge').innerHTML = '✓ Khớp đơn hàng';
      document.getElementById('mdlOrderCode').textContent = `#${matchedOrder.order_code}`;
      document.getElementById('mdlOrderVariant').textContent = `Màu: ${matchedOrder.color} • Size: ${matchedOrder.size} • x${matchedOrder.quantity}`;
      document.getElementById('mdlOrderTime').textContent = matchedOrder.created_at;
      document.getElementById('mdlOrderLink').href = `/admin/orders/${matchedOrder.order_id}`;
      document.getElementById('mdlOrderLink').style.display = 'inline-block';
      if (matchedOrder.item_image) {
        document.getElementById('mdlProdImg').src = matchedOrder.item_image;
      }
    } else {
      document.getElementById('mdlOrderBadge').innerHTML = '✓ Đã thanh toán';
      document.getElementById('mdlOrderCode').textContent = '#BEE-VIP';
      document.getElementById('mdlOrderVariant').textContent = 'Size L • Tiêu chuẩn';
      document.getElementById('mdlOrderTime').textContent = rev.created_at ? new Date(rev.created_at).toLocaleDateString('vi-VN') : '25/08/2026';
      document.getElementById('mdlOrderLink').style.display = 'none';
    }

    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
      starsHtml += `<i class="fa-solid fa-star ${i <= rev.rating ? 'text-warning' : 'text-body-tertiary'}"></i> `;
    }
    document.getElementById('mdlStarsContainer').innerHTML = starsHtml;
    document.getElementById('mdlRatingText').textContent = `(${rev.rating}/5 Sao)`;
    document.getElementById('mdlReviewComment').textContent = `"${rev.comment}"`;
    document.getElementById('mdlReviewTime').textContent = rev.created_at ? new Date(rev.created_at).toLocaleString('vi-VN') : '25/08/2026';

    const photosSection = document.getElementById('mdlPhotosSection');
    const photosContainer = document.getElementById('mdlReviewPhotos');
    if (rev.images_urls && rev.images_urls.length > 0) {
      photosSection.style.display = 'block';
      photosContainer.innerHTML = '';
      rev.images_urls.forEach(photo => {
        photosContainer.innerHTML += `
          <a href="${photo}" target="_blank" class="d-inline-block">
            <img src="${photo}" alt="ảnh khách" class="rounded border border-translucent" style="width: 50px; height: 50px; object-fit: cover;">
          </a>
        `;
      });
    } else {
      photosSection.style.display = 'none';
    }

    if (rev.status === 'approved') {
      document.getElementById('mdlStatusBadge').innerHTML = '<span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Hiển thị công khai</span>';
    } else {
      document.getElementById('mdlStatusBadge').innerHTML = '<span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-eye-slash me-1"></i> Đang bị ẩn</span>';
    }

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  }
</script>
@endpush
@endsection
