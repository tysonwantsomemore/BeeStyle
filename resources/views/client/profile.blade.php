@extends('layouts.client')

@section('title', 'Tài Khoản & Cài Đặt Hồ Sơ | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tài khoản cá nhân</li>
    </ol>
  </nav>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
      <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif


  <div class="row g-4">
    <!-- USER PROFILE SIDEBAR -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm p-4 text-center mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
        <div class="position-relative mx-auto mb-3" style="width: 100px; height: 100px;">
          <img id="sidebarAvatarPreview" class="rounded-circle border border-3 border-dark object-fit-cover w-100 h-100 shadow-sm" src="{{ asset($user->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $user->name }}">
          <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning text-dark p-2 border" title="Hội viên chính thức">
            <i class="fa-solid fa-crown fs-11"></i>
          </span>
        </div>

        <h5 class="fw-bold text-dark mb-1" style="font-family: var(--atino-font-heading);">{{ $user->name }}</h5>
        <p class="text-muted small mb-2">{{ $user->email }}</p>

        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
          <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-2 rounded-pill">
            <i class="fa-solid fa-award me-1"></i> {{ $user->rank ?? 'Thành viên Mới' }}
          </span>
          <span class="badge bg-light text-dark fw-bold px-3 py-2 rounded-pill border">
            <i class="fa-solid fa-coins me-1 text-warning"></i> {{ number_format($user->points ?? 0) }} Điểm
          </span>
        </div>

        <!-- Navigation Tabs List -->
        <div class="nav flex-column nav-pills text-start small border-top pt-3 gap-1" id="profileTabs" role="tablist">
          <button class="nav-link active fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="orders-tab" data-bs-toggle="pill" data-bs-target="#tab-orders" type="button" role="tab">
            <span><i class="fa-solid fa-box-archive me-2 text-danger"></i> Đơn Hàng Của Tôi</span>
            <span class="badge bg-dark text-white rounded-pill">{{ $orders->count() }}</span>
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="edit-profile-tab" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button" role="tab">
            <i class="fa-solid fa-user-pen me-2 text-secondary"></i> Thông Tin Cá Nhân
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="password-tab" data-bs-toggle="pill" data-bs-target="#tab-password" type="button" role="tab">
            <i class="fa-solid fa-shield-halved me-2 text-secondary"></i> Đổi Mật Khẩu Tài Khoản
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="addresses-tab" data-bs-toggle="pill" data-bs-target="#tab-addresses" type="button" role="tab">
            <i class="fa-solid fa-map-location-dot me-2 text-secondary"></i> Sổ Địa Chỉ ({{ isset($addresses) ? $addresses->count() : 0 }})
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="my-reviews-tab" data-bs-toggle="pill" data-bs-target="#tab-my-reviews" type="button" role="tab">
            <span><i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Của Tôi</span>
            <span class="badge bg-warning-subtle text-dark rounded-pill">{{ $user->reviews->count() }}</span>
          </button>

          <button class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center" id="rewards-tab" data-bs-toggle="pill" data-bs-target="#tab-vip" type="button" role="tab">
            <i class="fa-solid fa-gem me-2 text-warning"></i> Quyền Lợi &amp; Điểm Thưởng VIP
          </button>


          <a href="{{ route('client.products.index') }}" class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center text-dark">
            <i class="fa-solid fa-store me-2 text-secondary"></i> Mua Sắm Sản Phẩm Mới
          </a>

          @if($user->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link fw-bold py-2.5 px-3 rounded-3 text-start d-flex align-items-center text-danger">
              <i class="fa-solid fa-gauge-high me-2"></i> Bảng Quản Trị Hệ Thống (Admin)
            </a>
          @endif
        </div>

        <!-- Logout Form -->
        <form action="{{ route('auth.logout') }}" method="POST" class="mt-3 pt-3 border-top">
          @csrf
          <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 fw-semibold rounded-pill">
            <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng Xuất Tài Khoản
          </button>
        </form>
      </div>
    </div>

    <!-- TAB CONTENTS -->
    <div class="col-lg-8">
      <div class="tab-content" id="profileTabsContent">
        
        <!-- TAB 1: ORDER HISTORY -->
        <div class="tab-pane fade show active" id="tab-orders" role="tabpanel">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="fw-bold text-dark mb-0 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-clock-rotate-left me-2 text-danger"></i> Lịch Sử Đơn Hàng ({{ $orders->count() }})
              </h5>
              <a href="{{ route('client.products.index') }}" class="small text-danger fw-bold text-decoration-none">
                Đặt thêm sản phẩm <i class="fa-solid fa-arrow-right ms-1"></i>
              </a>
            </div>

            <!-- TOP PENDING REVIEWS BANNER (Nổi bật nhắc nhở đánh giá đơn hoàn tất) -->
            @if(isset($pendingReviewItems) && $pendingReviewItems->count() > 0)
              <div class="alert alert-warning border-0 shadow-sm p-3 mb-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #fffbeb; border-left: 5px solid #f59e0b !important;">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                    <i class="fa-solid fa-gift fs-4"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold text-dark mb-1 small text-uppercase">Bạn có {{ $pendingReviewItems->count() }} sản phẩm từ đơn hàng hoàn tất chưa đánh giá!</h6>
                    <p class="mb-0 text-muted small">Hãy đánh giá sản phẩm để nhận ngay <strong>+20 Điểm VIP</strong> vào tài khoản nhé.</p>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <a href="{{ route('client.products.show', $pendingReviewItems->first()->product_id) }}#reviews" class="btn btn-bee-primary btn-sm px-3 fw-bold text-nowrap">
                    <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá ngay
                  </a>
                </div>
              </div>
            @endif

            <div class="d-flex flex-column gap-3">
              @forelse($orders as $order)
                <div class="border rounded-3 p-3 bg-light-subtle">
                  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-2 border-bottom mb-3">
                    <div>
                      <span class="small text-muted">Mã đơn hàng:</span>
                      <strong class="text-dark font-monospace fs-9">{{ $order->order_code }}</strong>
                      <span class="text-muted small ms-2">({{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }})</span>
                    </div>
                    <div>
                      @if($order->shipping_status === 'completed')
                        <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hoàn tất</span>
                      @elseif($order->shipping_status === 'delivered')
                        <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-box-open me-1"></i> Đã giao hàng</span>
                      @elseif($order->shipping_status === 'shipping')
                        <span class="badge bg-warning-subtle text-dark fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> Đang giao hàng</span>
                      @elseif($order->shipping_status === 'processing')
                        <span class="badge bg-info-subtle text-info fw-bold"><i class="fa-solid fa-box me-1"></i> Đang đóng gói</span>
                      @elseif($order->shipping_status === 'cancelled')
                        <span class="badge bg-danger-subtle text-danger fw-bold"><i class="fa-solid fa-xmark me-1"></i> Đã hủy</span>
                      @else
                        <span class="badge bg-secondary-subtle text-dark fw-bold"><i class="fa-solid fa-hourglass-start me-1"></i> Chờ xác nhận</span>
                      @endif
                    </div>
                  </div>

                  <!-- Completed Order Prompt Banner -->
                  @if($order->shipping_status === 'delivered' || $order->shipping_status === 'completed' || $order->status_step >= 5)
                    <div class="alert alert-success border-0 py-2 px-3 mb-3 rounded-2 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #ecfdf5;">
                      <div class="small text-success">
                        <i class="fa-solid fa-circle-check me-1"></i> <strong>Đơn hàng đã hoàn tất!</strong> Cảm ơn bạn đã mua sắm tại BeeStyle. Hãy đánh giá các sản phẩm bên dưới.
                      </div>
                    </div>
                  @endif

                  <!-- Order Items -->
                  <div class="d-flex flex-column gap-2 mb-3">
                    @php
                      $userReviewedIds = $user->reviews->pluck('product_id')->toArray();
                    @endphp
                    @foreach($order->items as $item)
                      @php
                        $hasReviewedThisItem = in_array($item->product_id, $userReviewedIds);
                      @endphp
                      <div class="d-flex align-items-center justify-content-between p-2.5 bg-white rounded-3 border">
                        <div class="d-flex align-items-center gap-2.5">
                          <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 48px; height: 48px; object-fit: cover; cursor: pointer;" class="rounded border" onclick="openQuickReviewModal({{ $item->product_id ?? 1 }})">
                          <div>
                            <a href="javascript:void(0)" onclick="openQuickReviewModal({{ $item->product_id ?? 1 }})" class="small fw-semibold text-dark text-decoration-none d-block">
                              {{ $item->product_name }}
                            </a>
                            <small class="text-muted">Màu: {{ $item->color ?? 'Tiêu chuẩn' }} | Size: {{ $item->size ?? 'M' }} • SL: x{{ $item->quantity }}</small>
                          </div>
                        </div>
                        <div class="text-end">
                          <span class="small fw-bold text-dark d-block">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                          @if($order->shipping_status === 'delivered' || $order->shipping_status === 'completed' || $order->status_step >= 5)
                            @if($hasReviewedThisItem)
                              <button type="button" onclick="openQuickReviewModal({{ $item->product_id ?? 1 }})" class="btn btn-sm btn-outline-success py-0.5 px-2 mt-1 fw-bold text-nowrap" style="font-size: 0.72rem;">
                                <i class="fa-solid fa-circle-check me-1"></i> Xem / Sửa Đánh Giá
                              </button>
                            @else
                              <button type="button" onclick="openQuickReviewModal({{ $item->product_id ?? 1 }})" class="btn btn-sm btn-bee-primary py-0.5 px-2.5 mt-1 fw-bold text-nowrap" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá (+20đ)
                              </button>
                            @endif
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>



                  <!-- Order Footer -->
                  <div class="d-flex justify-content-between align-items-center pt-2 border-top flex-wrap gap-2">
                    <div>
                      <span class="small text-muted">Phương thức: <strong>{{ $order->payment_method_name }}</strong></span>
                      <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }} ms-1">
                        {{ $order->payment_status_label }}
                      </span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                      <div>
                        <span class="small text-muted">Tổng tiền: </span>
                        <strong class="text-danger fs-6">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                      </div>
                      <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="btn btn-sm btn-bee-outline">
                        <i class="fa-solid fa-truck-fast me-1"></i> Tra Cứu Vận Chuyển
                      </a>
                    </div>
                  </div>
                </div>
              @empty
                <div class="text-center py-5">
                  <i class="fa-solid fa-bag-shopping fs-1 text-muted mb-2"></i>
                  <h6 class="fw-bold text-dark">Bạn chưa có đơn hàng nào tại BeeStyle</h6>
                  <p class="text-muted small mb-3">Hãy khám phá bộ sưu tập áo polo nam, sơ mi và blazer mới nhất!</p>
                  <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary btn-sm px-4">
                    Khám Phá Cửa Hàng Ngay
                  </a>
                </div>
              @endforelse
            </div>
          </div>
        </div>

        <!-- TAB 2: EDIT PROFILE -->
        <div class="tab-pane fade" id="tab-profile" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="mb-4 pb-2 border-bottom">
              <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-user-pen me-2 text-danger"></i> Cập Nhật Hồ Sơ Cá Nhân
              </h5>
              <p class="text-muted small mb-0">Quản lý thông tin tài khoản và địa chỉ nhận hàng mặc định</p>
            </div>

            <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <!-- Avatar Upload with Live Preview -->
              <div class="mb-4">
                <label class="form-label small fw-semibold text-dark">Ảnh đại diện (Avatar)</label>
                <div class="d-flex align-items-center gap-3">
                  <img id="avatarPreview" src="{{ asset($user->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="Avatar" class="rounded-circle border object-fit-cover" style="width: 70px; height: 70px;">
                  <div>
                    <input type="file" name="avatar" id="avatarInput" class="form-control form-control-sm" accept="image/*" onchange="var reader = new FileReader(); reader.onload = function(e){ document.getElementById('avatarPreview').src = e.target.result; document.getElementById('sidebarAvatarPreview').src = e.target.result; }; reader.readAsDataURL(this.files[0]);">
                    <small class="text-muted fs-11">Định dạng: JPG, PNG, WEBP. Tối đa 3MB.</small>
                  </div>
                </div>
              </div>

              <!-- Name & Phone -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Họ và tên <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Số điện thoại liên hệ <span class="text-danger">*</span></label>
                  <input type="tel" name="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}" required>
                </div>
              </div>

              <!-- Email, Gender & Date of Birth -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-3">
                  <label class="form-label small fw-semibold text-dark">Giới tính</label>
                  <select name="gender" class="form-select form-select-sm">
                    <option value="Nam" {{ old('gender', $user->gender) === 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('gender', $user->gender) === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ old('gender', $user->gender) === 'Khác' ? 'selected' : '' }}>Khác</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label class="form-label small fw-semibold text-dark">Ngày sinh</label>
                  <input type="date" name="dob" class="form-control form-control-sm" value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '') }}">
                </div>
              </div>

              <!-- Address, City, District -->
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Tỉnh / Thành phố</label>
                  <input type="text" name="city" class="form-control form-control-sm" value="{{ old('city', $user->city ?? 'Hà Nội') }}" placeholder="Ví dụ: Hà Nội, TP. Hồ Chí Minh...">
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-semibold text-dark">Quận / Huyện</label>
                  <input type="text" name="district" class="form-control form-control-sm" value="{{ old('district', $user->district ?? '') }}" placeholder="Ví dụ: Cầu Giấy, Quận 1...">
                </div>

                <div class="col-12">
                  <label class="form-label small fw-semibold text-dark">Địa chỉ giao hàng chi tiết (Số nhà, tên đường, phường)</label>
                  <input type="text" name="address" class="form-control form-control-sm" value="{{ old('address', $user->address) }}" placeholder="Ví dụ: Số 18 Phố Huế, Hoàn Kiếm">
                </div>
              </div>

              <!-- Submit -->
              <div class="mt-4 pt-2 border-top text-end">
                <button type="submit" class="btn btn-bee-primary px-4 py-2">
                  <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thay Đổi Hồ Sơ
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- TAB 3: CHANGE PASSWORD -->
        <div class="tab-pane fade" id="tab-password" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="mb-4 pb-2 border-bottom">
              <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-shield-halved me-2 text-danger"></i> Đổi Mật Khẩu An Toàn
              </h5>
              <p class="text-muted small mb-0">Bảo vệ tài khoản BeeStyle bằng mật khẩu mạnh và bảo mật</p>
            </div>

            <form action="{{ route('client.profile.password') }}" method="POST">
              @csrf
              @method('PUT')

              <div class="mb-3" style="max-width: 480px;">
                <label class="form-label small fw-semibold text-dark">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="current_password" id="curPass" class="form-control form-control-sm" placeholder="Nhập mật khẩu hiện tại..." required>
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var p=document.getElementById('curPass'); p.type=(p.type==='password'?'text':'password');">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="mb-3" style="max-width: 480px;">
                <label class="form-label small fw-semibold text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password" id="newPass" class="form-control form-control-sm" placeholder="Tối thiểu 6 ký tự..." required>
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var p=document.getElementById('newPass'); p.type=(p.type==='password'?'text':'password');">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="mb-4" style="max-width: 480px;">
                <label class="form-label small fw-semibold text-dark">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="password" name="password_confirmation" id="confirmPass" class="form-control form-control-sm" placeholder="Nhập lại mật khẩu mới..." required>
                  <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var p=document.getElementById('confirmPass'); p.type=(p.type==='password'?'text':'password');">
                    <i class="fa-regular fa-eye"></i>
                  </button>
                </div>
              </div>

              <div class="p-3 bg-light rounded-3 mb-4" style="max-width: 480px;">
                <h6 class="small fw-bold text-dark mb-1"><i class="fa-solid fa-circle-info text-warning me-1"></i> Lưu ý bảo mật:</h6>
                <ul class="text-muted small ps-3 mb-0">
                  <li>Mật khẩu nên chứa ít nhất 6 ký tự.</li>
                  <li>Không chia sẻ mật khẩu tài khoản cho người khác.</li>
                </ul>
              </div>

              <div class="pt-2 border-top">
                <button type="submit" class="btn btn-bee-primary px-4 py-2">
                  <i class="fa-solid fa-lock me-1"></i> Cập Nhật Mật Khẩu Mới
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- TAB 4: ADDRESS BOOK -->
        <div class="tab-pane fade" id="tab-addresses" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom flex-wrap gap-2">
              <div>
                <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                  <i class="fa-solid fa-map-location-dot me-2 text-danger"></i> Sổ Địa Chỉ Nhận Hàng ({{ $addresses->count() }})
                </h5>
                <p class="text-muted small mb-0">Quản lý các địa chỉ giao hàng để thanh toán nhanh hơn khi mua sắm</p>
              </div>
              <button type="button" class="btn btn-bee-primary btn-sm px-3" data-bs-toggle="collapse" data-bs-target="#addAddressBox">
                <i class="fa-solid fa-plus me-1"></i> Thêm Địa Chỉ Mới
              </button>
            </div>

            <!-- Collapse Add Address Box -->
            <div class="collapse mb-4" id="addAddressBox">
              <div class="card card-body bg-light border p-4 rounded-3">
                <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-location-crosshairs me-1 text-danger"></i> Thêm địa chỉ giao hàng mới:</h6>
                <form action="{{ route('client.profile.address.store') }}" method="POST">
                  @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label small fw-semibold text-dark">Tên người nhận <span class="text-danger">*</span></label>
                      <input type="text" name="recipient_name" class="form-control form-control-sm" value="{{ old('recipient_name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-semibold text-dark">Số điện thoại nhận hàng <span class="text-danger">*</span></label>
                      <input type="tel" name="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}" required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-semibold text-dark">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                      <input type="text" name="city" class="form-control form-control-sm" placeholder="Hà Nội, TP. HCM..." required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-semibold text-dark">Quận / Huyện <span class="text-danger">*</span></label>
                      <input type="text" name="district" class="form-control form-control-sm" placeholder="Cầu Giấy, Quận 1..." required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-semibold text-dark">Phường / Xã</label>
                      <input type="text" name="ward" class="form-control form-control-sm" placeholder="Phường Dịch Vọng...">
                    </div>
                    <div class="col-md-8">
                      <label class="form-label small fw-semibold text-dark">Địa chỉ chi tiết (Số nhà, ngõ, đường) <span class="text-danger">*</span></label>
                      <input type="text" name="address" class="form-control form-control-sm" placeholder="Số 18 Phố Huế..." required>
                    </div>
                    <div class="col-md-4">
                      <label class="form-label small fw-semibold text-dark">Loại địa chỉ</label>
                      <select name="label" class="form-select form-select-sm">
                        <option value="Nhà riêng">Nhà riêng</option>
                        <option value="Văn phòng">Văn phòng / Công ty</option>
                        <option value="Khác">Khác</option>
                      </select>
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefaultCheck">
                        <label class="form-check-label small text-dark cursor-pointer" for="isDefaultCheck">
                          Đặt làm địa chỉ nhận hàng mặc định
                        </label>
                      </div>
                    </div>
                    <div class="col-12 text-end mt-3">
                      <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="collapse" data-bs-target="#addAddressBox">Hủy</button>
                      <button type="submit" class="btn btn-bee-primary btn-sm px-3"><i class="fa-solid fa-floppy-disk me-1"></i> Lưu Địa Chỉ</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Current Addresses List -->
            <div class="d-flex flex-column gap-3">
              @forelse($addresses as $addr)
                <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center flex-wrap gap-2">
                  <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                      <strong class="text-dark">{{ $addr->recipient_name }}</strong>
                      <span class="text-muted small">|</span>
                      <span class="text-muted small">{{ $addr->phone }}</span>
                      <span class="badge bg-secondary-subtle text-dark small">{{ $addr->label ?? 'Địa chỉ' }}</span>
                      @if($addr->is_default)
                        <span class="badge bg-danger-subtle text-danger small fw-bold"><i class="fa-solid fa-check me-1"></i> Mặc định</span>
                      @endif
                    </div>
                    <p class="text-secondary small mb-0">{{ $addr->address }}{{ $addr->ward ? ', ' . $addr->ward : '' }}{{ $addr->district ? ', ' . $addr->district : '' }}{{ $addr->city ? ', ' . $addr->city : '' }}</p>
                    @if($addr->notes)
                      <small class="text-muted fst-italic">Ghi chú: {{ $addr->notes }}</small>
                    @endif
                  </div>
                  <div>
                    <form action="{{ route('client.profile.address.delete', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2.5">
                        <i class="fa-regular fa-trash-can me-1"></i> Xóa
                      </button>
                    </form>
                  </div>
                </div>
              @empty
                <div class="p-3 bg-light rounded-3 border">
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <strong class="text-dark">{{ $user->name }}</strong>
                    <span class="text-muted small">|</span>
                    <span class="text-muted small">{{ $user->phone ?? 'Chưa cập nhật SĐT' }}</span>
                    <span class="badge bg-danger-subtle text-danger small">Hồ sơ chính</span>
                  </div>
                </div>
              @endforelse
            </div>
          </div>
        </div>

        <!-- TAB 5: VIP BENEFITS & LOYALTY POINTS -->
        <div class="tab-pane fade" id="tab-vip" role="tabpanel">
          <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="mb-4 pb-2 border-bottom">
              <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                <i class="fa-solid fa-gem me-2 text-warning"></i> Chính Sách Điểm Thưởng &amp; Hội Viên VIP
              </h5>
              <p class="text-muted small mb-0">Quyền lợi đặc quyền dành riêng cho khách hàng thân thiết của BeeStyle</p>
            </div>

            <!-- VIP Status Card -->
            <div class="p-4 rounded-3 text-white mb-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #111827, #1f2937);">
              <div class="row align-items-center">
                <div class="col-md-7">
                  <span class="badge bg-warning text-dark fw-bold px-3 py-1 text-uppercase mb-2">HẠNG HIỆN TẠI</span>
                  <h3 class="fw-bold text-white mb-1" style="font-family: var(--atino-font-heading);">{{ $user->rank ?? 'Thành viên Bạc (Silver)' }}</h3>
                  <p class="text-white-50 small mb-0">Tổng chi tiêu tích lũy: <strong class="text-warning">{{ number_format($user->total_spent ?? 0, 0, ',', '.') }}₫</strong></p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                  <div class="display-6 fw-bold text-warning mb-0">{{ number_format($user->points ?? 0) }}</div>
                  <small class="text-white-50 text-uppercase">ĐIỂM TÍCH LŨY KHẢ DỤNG</small>
                </div>
              </div>
            </div>

            <!-- VIP Tiers Table -->
            <h6 class="fw-bold text-dark mb-3">Bảng phân hạng Hội viên BeeStyle:</h6>
            <div class="table-responsive">
              <table class="table table-bordered align-middle small mb-0 text-center">
                <thead class="table-dark">
                  <tr>
                    <th>Hạng Hội Viên</th>
                    <th>Điều Kiện Chi Tiêu</th>
                    <th>Tỷ Lệ Tích Điểm</th>
                    <th>Đặc Quyền</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><strong class="text-secondary">Thành viên Mới</strong></td>
                    <td>Đăng ký tài khoản</td>
                    <td>10.000₫ = 1 điểm</td>
                    <td>Tặng 100 điểm + Freeship đơn đầu</td>
                  </tr>
                  <tr class="table-warning">
                    <td><strong class="text-dark">Thành viên Bạc (Silver)</strong></td>
                    <td>Chi tiêu từ 2.000.000₫</td>
                    <td>10.000₫ = 1.2 điểm</td>
                    <td>Giảm 5% sinh nhật + Ưu tiên giao hàng</td>
                  </tr>
                  <tr>
                    <td><strong class="text-warning">Thành viên Vàng (Gold)</strong></td>
                    <td>Chi tiêu từ 5.000.000₫</td>
                    <td>10.000₫ = 1.5 điểm</td>
                    <td>Giảm 10% sinh nhật + Quà tặng độc quyền</td>
                  </tr>
                  <tr>
                    <td><strong class="text-danger">Thành viên Kim Cương (Diamond)</strong></td>
                    <td>Chi tiêu từ 10.000.000₫</td>
                    <td>10.000₫ = 2.0 điểm</td>
                    <td>Giảm 15% trọn đời + Hotline phục vụ riêng</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 6: MY REVIEWS (ĐÁNH GIÁ & NHẬN XÉT CỦA TÔI) -->
        <div class="tab-pane fade" id="tab-my-reviews" role="tabpanel">
          <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--atino-border) !important;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <div>
                <h5 class="fw-bold text-dark mb-1 text-uppercase" style="font-family: var(--atino-font-heading);">
                  <i class="fa-solid fa-star me-2 text-warning"></i> Đánh Giá Của Tôi ({{ $user->reviews->count() }})
                </h5>
                <p class="text-muted small mb-0">Xem lại và chỉnh sửa các nhận xét sản phẩm bạn đã từng gửi</p>
              </div>
              <span class="badge bg-warning text-dark fw-bold">+20 điểm/đánh giá</span>
            </div>

            <div class="d-flex flex-column gap-3">
              @forelse($user->reviews as $rev)
                <div class="p-3 bg-light rounded-3 border transition-all hover-lift">
                  <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                      <img src="{{ asset($rev->product->image ?? '/assets/img/products/1.png') }}" alt="{{ $rev->product->name ?? '' }}" style="width: 48px; height: 48px; object-fit: cover; cursor: pointer;" class="rounded border bg-white" onclick="openQuickReviewModal({{ $rev->product_id }})">
                      <div>
                        <strong class="text-dark small d-block" style="cursor: pointer;" onclick="openQuickReviewModal({{ $rev->product_id }})">{{ $rev->product->name ?? 'Sản phẩm' }}</strong>
                        <small class="text-muted">{{ $rev->created_at ? $rev->created_at->format('d/m/Y H:i') : '' }}</small>
                      </div>
                    </div>
                    <div class="text-end">
                      <div class="text-warning small mb-1">
                        @for($i=1; $i<=5; $i++)
                          <i class="fa-solid fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary-subtle' }}"></i>
                        @endfor
                        <span class="fw-bold text-dark ms-1">({{ $rev->rating }}/5)</span>
                      </div>
                      <button type="button" onclick="openQuickReviewModal({{ $rev->product_id }})" class="btn btn-sm btn-outline-dark py-0.5 px-2 fw-bold" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-pen me-1"></i> Xem / Sửa Đánh Giá
                      </button>
                    </div>
                  </div>
                  <p class="small text-dark mb-0 fst-italic leading-relaxed p-2 bg-white rounded-2 border">
                    "{{ $rev->comment }}"
                  </p>
                </div>
              @empty
                <div class="text-center py-5">
                  <i class="fa-regular fa-comment-dots fs-1 text-muted mb-2"></i>
                  <h6 class="fw-bold text-dark">Bạn chưa viết đánh giá nào</h6>
                  <p class="text-muted small mb-3">Sau khi nhận hàng thành công, hãy đánh giá sản phẩm để nhận ngay +20 điểm thưởng VIP nhé!</p>
                  <a href="#tab-orders" data-bs-toggle="pill" data-bs-target="#tab-orders" class="btn btn-bee-primary btn-sm px-4">
                    Xem Đơn Hàng Của Bạn
                  </a>
                </div>
              @endforelse
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // 1. Kiểm tra query param ?tab= hoặc URL hash #
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    const hash = window.location.hash;

    const tabMap = {
      'orders': 'orders-tab',
      'profile': 'edit-profile-tab',
      'password': 'password-tab',
      'addresses': 'addresses-tab',
      'vip': 'rewards-tab',
      'reviews': 'my-reviews-tab',
      '#tab-orders': 'orders-tab',
      '#tab-profile': 'edit-profile-tab',
      '#tab-password': 'password-tab',
      '#tab-addresses': 'addresses-tab',
      '#tab-vip': 'rewards-tab',
      '#tab-my-reviews': 'my-reviews-tab'
    };

    let targetTabId = null;
    if (tabParam && tabMap[tabParam]) {
      targetTabId = tabMap[tabParam];
    } else if (hash && tabMap[hash]) {
      targetTabId = tabMap[hash];
    }

    // 2. Nếu có lỗi validation của form đổi mật khẩu (current_password, password) thì tự mở tab password
    @if(isset($errors) && ($errors->has('current_password') || $errors->has('password')))
      targetTabId = 'password-tab';
    @elseif(isset($errors) && ($errors->has('recipient_name') || $errors->has('address') || $errors->has('city')))
      targetTabId = 'addresses-tab';
    @endif

    if (targetTabId) {
      const tabButton = document.getElementById(targetTabId);
      if (tabButton) {
        const tabTrigger = new bootstrap.Tab(tabButton);
        tabTrigger.show();
      }
    }
  });
</script>
@endpush

@endsection

