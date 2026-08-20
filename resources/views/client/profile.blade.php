@extends('layouts.client')

<<<<<<< HEAD
@section('title', 'Tài Khoản & Cài Đặt Hồ Sơ | BeeStyle Menswear')
=======
@section('title', 'Tài Khoản & Hồ Sơ Cá Nhân | BeeStyle Menswear')
>>>>>>> ae0973d0eb9f4662314af3ad5ec267e0a9340ca0

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

  @if($errors->any())
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
<<<<<<< HEAD
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
=======
      <div class="card border-0 shadow-sm p-4 text-center mb-4" style="border-radius: 16px;">
        <div class="position-relative d-inline-block mx-auto mb-3">
          <img class="rounded-circle border border-3 border-warning shadow-sm" src="{{ asset($user->avatar ?? '/assets/img/team/40x40/58.webp') }}" alt="{{ $user->name }}" style="width: 95px; height: 95px; object-fit: cover;">
          <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success border border-2 border-white p-2">
            <span class="visually-hidden">Online</span>
          </span>
        </div>

        <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
        <p class="text-muted small mb-2">{{ $user->email }}</p>
        
        <div class="d-flex justify-content-center gap-2 mb-3 flex-wrap">
          <span class="badge bg-warning-subtle text-dark fw-bold px-3 py-2 rounded-pill">
            <i class="fa-solid fa-crown me-1 text-warning"></i> {{ $user->rank ?? 'Thành viên Mới' }}
>>>>>>> ae0973d0eb9f4662314af3ad5ec267e0a9340ca0
          </span>
          <span class="badge bg-light text-dark fw-bold px-3 py-2 rounded-pill border">
            <i class="fa-solid fa-coins me-1 text-warning"></i> {{ number_format($user->points ?? 0) }} Điểm
          </span>
        </div>

<<<<<<< HEAD
        <!-- Navigation Tabs List -->
        <div class="nav flex-column nav-pills text-start small border-top pt-3 gap-1" id="profileTabs" role="tablist">
          <button class="nav-link active fw-bold py-2 px-3 rounded-3 text-start d-flex align-items-center justify-content-between" id="orders-tab" data-bs-toggle="pill" data-bs-target="#tab-orders" type="button" role="tab">
            <span><i class="fa-solid fa-box-archive me-2 text-danger"></i> Đơn Hàng Của Tôi</span>
            <span class="badge bg-dark text-white rounded-pill">{{ $orders->count() }}</span>
          </button>

          <button class="nav-link fw-bold py-2 px-3 rounded-3 text-start d-flex align-items-center" id="edit-profile-tab" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button" role="tab">
            <i class="fa-solid fa-user-pen me-2 text-secondary"></i> Cập Nhật Thông Tin Cá Nhân
          </button>

          <button class="nav-link fw-bold py-2 px-3 rounded-3 text-start d-flex align-items-center" id="password-tab" data-bs-toggle="pill" data-bs-target="#tab-password" type="button" role="tab">
            <i class="fa-solid fa-shield-halved me-2 text-secondary"></i> Đổi Mật Khẩu Tài Khoản
          </button>

          <button class="nav-link fw-bold py-2 px-3 rounded-3 text-start d-flex align-items-center" id="rewards-tab" data-bs-toggle="pill" data-bs-target="#tab-vip" type="button" role="tab">
            <i class="fa-solid fa-gem me-2 text-warning"></i> Quyền Lợi &amp; Điểm Thưởng VIP
          </button>

          <a href="{{ route('client.products.index') }}" class="nav-link fw-bold py-2 px-3 rounded-3 text-start d-flex align-items-center text-dark">
            <i class="fa-solid fa-store me-2 text-secondary"></i> Mua Sắm Sản Phẩm Mới
          </a>

          @if($user->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link fw-bold py-2 px-3 rounded-3 text-start d-flex align-items-center text-danger">
              <i class="fa-solid fa-gauge-high me-2"></i> Bảng Quản Trị Hệ Thống (Admin)
=======
        <!-- NAVIGATION TABS -->
        <div class="nav flex-column nav-pills text-start small border-top pt-3 gap-1" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <button class="nav-link text-start py-2.5 px-3 rounded-2 fw-semibold {{ $activeTab === 'profile' ? 'active bg-warning text-dark' : 'text-dark' }}" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab">
            <i class="fa-solid fa-user-pen me-2 text-warning"></i> Thông Tin Cá Nhân
          </button>

          <button class="nav-link text-start py-2.5 px-3 rounded-2 fw-semibold {{ $activeTab === 'security' ? 'active bg-warning text-dark' : 'text-dark' }}" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab">
            <i class="fa-solid fa-shield-halved me-2 text-warning"></i> Bảo Mật &amp; Đổi Mật Khẩu
          </button>

          <button class="nav-link text-start py-2.5 px-3 rounded-2 fw-semibold {{ $activeTab === 'bank' ? 'active bg-warning text-dark' : 'text-dark' }}" id="v-pills-bank-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bank" type="button" role="tab">
            <i class="fa-solid fa-building-columns me-2 text-warning"></i> Tài Khoản Ngân Hàng Hoàn Tiền
          </button>

          <button class="nav-link text-start py-2.5 px-3 rounded-2 fw-semibold {{ $activeTab === 'addresses' ? 'active bg-warning text-dark' : 'text-dark' }}" id="v-pills-addresses-tab" data-bs-toggle="pill" data-bs-target="#v-pills-addresses" type="button" role="tab">
            <i class="fa-solid fa-map-location-dot me-2 text-warning"></i> Sổ Địa Chỉ Nhận Hàng ({{ $addresses->count() }})
          </button>

          <button class="nav-link text-start py-2.5 px-3 rounded-2 fw-semibold {{ $activeTab === 'orders' ? 'active bg-warning text-dark' : 'text-dark' }}" id="v-pills-orders-tab" data-bs-toggle="pill" data-bs-target="#v-pills-orders" type="button" role="tab">
            <i class="fa-solid fa-box-archive me-2 text-warning"></i> Lịch Sử Đơn Hàng ({{ $orders->count() }})
          </button>

          @if($user->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-start py-2.5 px-3 rounded-2 text-warning fw-bold border border-warning-subtle mt-2">
              <i class="fa-solid fa-gauge-high me-2"></i> Trang Quản Trị Hệ Thống
>>>>>>> ae0973d0eb9f4662314af3ad5ec267e0a9340ca0
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

<<<<<<< HEAD
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
                        <i class="fa-solid fa-circle-check me-1"></i> Đơn hàng đã giao thành công! Bạn có thể đánh giá từng sản phẩm dưới đây.
                      </div>
                      <a href="{{ route('client.products.show', $order->items->first()->product_id ?? 1) }}#reviews" class="btn btn-sm btn-outline-success py-0 px-2 fw-bold text-nowrap" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-star me-1"></i> Đánh giá ngay
                      </a>
                    </div>
                  @endif

                  <!-- Order Items -->
                  <div class="d-flex flex-column gap-2 mb-3">
                    @foreach($order->items as $item)
                      <div class="d-flex align-items-center justify-content-between p-2 bg-white rounded-2 border">
                        <div class="d-flex align-items-center gap-2">
                          <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 48px; height: 48px; object-fit: cover;" class="rounded border">
                          <div>
                            <a href="{{ route('client.products.show', $item->product_id ?? 1) }}" class="small fw-semibold text-dark text-decoration-none d-block">
                              {{ $item->product_name }}
                            </a>
                            <small class="text-muted">Màu: {{ $item->color ?? 'Tiêu chuẩn' }} | Size: {{ $item->size ?? 'M' }} • SL: x{{ $item->quantity }}</small>
                          </div>
                        </div>
                        <div class="text-end">
                          <span class="small fw-bold text-dark d-block">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                          @if($order->shipping_status === 'delivered' || $order->shipping_status === 'completed' || $order->status_step >= 5)
                            <a href="{{ route('client.products.show', $item->product_id ?? 1) }}#reviews" class="btn btn-sm btn-bee-primary py-0 px-2 mt-1 fw-bold text-nowrap" style="font-size: 0.75rem;">
                              <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá SP
                            </a>
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

              <!-- Email -->
              <div class="mb-3">
                <label class="form-label small fw-semibold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email', $user->email) }}" required>
                <small class="text-muted fs-11">Email dùng để nhận thông báo đơn hàng và mã giảm giá sinh nhật.</small>
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

        <!-- TAB 4: VIP BENEFITS & LOYALTY POINTS -->
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
=======
    <!-- MAIN CONTENT TAB PANES -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
        <div class="tab-content" id="v-pills-tabContent">
          
          <!-- TAB 1: THÔNG TIN CÁ NHÂN -->
          <div class="tab-pane fade {{ $activeTab === 'profile' ? 'show active' : '' }}" id="v-pills-profile" role="tabpanel">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
              <i class="fa-solid fa-user-pen me-2 text-warning"></i> Cập Nhật Thông Tin Cá Nhân
            </h5>

            <form action="{{ route('client.profile.update') }}" method="POST">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Họ và tên <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Số điện thoại <span class="text-danger">*</span></label>
                  <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
                  <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="col-md-3">
                  <label class="form-label small fw-bold text-dark">Giới tính</label>
                  <select name="gender" class="form-select">
                    <option value="Nam" {{ old('gender', $user->gender) === 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ old('gender', $user->gender) === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ old('gender', $user->gender) === 'Khác' ? 'selected' : '' }}>Khác</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <label class="form-label small fw-bold text-dark">Ngày sinh</label>
                  <input type="date" name="dob" class="form-control" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}">
                </div>

                <div class="col-12">
                  <label class="form-label small fw-bold text-dark">Địa chỉ chính</label>
                  <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" placeholder="Số nhà, tên đường...">
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Tỉnh / Thành phố</label>
                  <input type="text" name="city" class="form-control" value="{{ old('city', $user->city ?? 'Hồ Chí Minh') }}">
                </div>

                <div class="col-12 mt-4">
                  <button type="submit" class="btn btn-bee-primary px-4 py-2 fw-bold">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thay Đổi
                  </button>
                </div>
              </div>
            </form>
          </div>

          <!-- TAB 2: BẢO MẬT & ĐỔI MẬT KHẨU -->
          <div class="tab-pane fade {{ $activeTab === 'security' ? 'show active' : '' }}" id="v-pills-security" role="tabpanel">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
              <i class="fa-solid fa-shield-halved me-2 text-warning"></i> Bảo Mật Tài Khoản &amp; Đổi Mật Khẩu
            </h5>

            <!-- Password change state alert -->
            <div class="p-3 mb-4 rounded-3 border {{ $user->hasChangedPassword() ? 'bg-success-subtle border-success-subtle' : 'bg-warning-subtle border-warning-subtle' }}">
              <div class="d-flex align-items-center">
                <i class="fa-solid {{ $user->hasChangedPassword() ? 'fa-circle-check text-success' : 'fa-triangle-exclamation text-warning' }} fs-4 me-3"></i>
                <div>
                  <h6 class="fw-bold mb-1 {{ $user->hasChangedPassword() ? 'text-success' : 'text-dark' }}">
                    Trạng Thái Đổi Mật Khẩu: {{ $user->hasChangedPassword() ? 'Đã thiết lập mật khẩu riêng' : 'Mật khẩu mặc định' }}
                  </h6>
                  <p class="small mb-0 text-secondary">
                    @if($user->password_changed_at)
                      Mật khẩu đã được thay đổi lần gần nhất vào lúc <strong>{{ $user->password_changed_at->format('d/m/Y H:i:s') }}</strong>.
                    @else
                      Tài khoản của bạn đang sử dụng mật khẩu ban đầu. Vui lòng đổi mật khẩu định kỳ để nâng cao bảo mật!
                    @endif
                  </p>
                </div>
              </div>
            </div>

            <form action="{{ route('client.profile.password') }}" method="POST">
              @csrf
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label small fw-bold text-dark">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" name="current_password" id="inputCurrentPassword" class="form-control @error('current_password') is-invalid @enderror" required placeholder="Nhập mật khẩu bạn đang dùng...">
                    <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="inputCurrentPassword" title="Hiện/ẩn mật khẩu">
                      <i class="fa-solid fa-eye"></i>
                    </button>
                    @error('current_password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" name="password" id="inputNewPassword" class="form-control @error('password') is-invalid @enderror" required minlength="6" placeholder="Tối thiểu 6 ký tự...">
                    <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="inputNewPassword" title="Hiện/ẩn mật khẩu">
                      <i class="fa-solid fa-eye"></i>
                    </button>
                    @error('password')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-text small text-muted">
                    <i class="fa-solid fa-circle-info me-1"></i> Mật khẩu phải có tối thiểu 6 ký tự và khác mật khẩu cũ.
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <input type="password" name="password_confirmation" id="inputConfirmPassword" class="form-control @error('password_confirmation') is-invalid @enderror" required minlength="6" placeholder="Nhập lại mật khẩu mới...">
                    <button class="btn btn-outline-secondary toggle-password-btn" type="button" data-target="inputConfirmPassword" title="Hiện/ẩn mật khẩu">
                      <i class="fa-solid fa-eye"></i>
                    </button>
                    @error('password_confirmation')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="col-12 mt-4">
                  <button type="submit" class="btn btn-bee-primary px-4 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-key me-1"></i> Cập Nhật Mật Khẩu Mới
                  </button>
                </div>
              </div>
            </form>
          </div>

          <!-- TAB 3: TÀI KHOẢN NGÂN HÀNG HOÀN TIỀN -->
          <div class="tab-pane fade {{ $activeTab === 'bank' ? 'show active' : '' }}" id="v-pills-bank" role="tabpanel">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
              <i class="fa-solid fa-building-columns me-2 text-warning"></i> Thông Tin Ngân Hàng Phục Vụ Hoàn Tiền
            </h5>

            <p class="small text-muted mb-4">
              Thông tin tài khoản ngân hàng này sẽ được BeeStyle sử dụng để hoàn tiền tự động trong các trường hợp hủy đơn hàng, đổi trả hoặc giải quyết khiếu nại.
            </p>

            <form action="{{ route('client.profile.bank') }}" method="POST">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Ngân hàng thụ hưởng <span class="text-danger">*</span></label>
                  <select name="bank_name" class="form-select" required>
                    <option value="">-- Chọn ngân hàng --</option>
                    @php
                      $banks = ['Vietcombank', 'Techcombank', 'MB Bank (Quân Đội)', 'ACB (Á Châu)', 'VPBank', 'TPBank', 'BIDV', 'Agribank', 'VietinBank', 'Sacombank', 'HDBank', 'MSB', 'OCB', 'SHB', 'VIB'];
                    @endphp
                    @foreach($banks as $b)
                      <option value="{{ $b }}" {{ old('bank_name', $user->bank_name) === $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Số tài khoản ngân hàng <span class="text-danger">*</span></label>
                  <input type="text" name="bank_account_number" class="form-control font-monospace" value="{{ old('bank_account_number', $user->bank_account_number) }}" placeholder="Ví dụ: 0071001234567" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Tên chủ tài khoản (Viết hoa không dấu) <span class="text-danger">*</span></label>
                  <input type="text" name="bank_account_name" class="form-control text-uppercase" value="{{ old('bank_account_name', $user->bank_account_name) }}" placeholder="NGUYEN VAN A" required>
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Chi nhánh ngân hàng (Tùy chọn)</label>
                  <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch', $user->bank_branch) }}" placeholder="Ví dụ: Chi nhánh TP. Hồ Chí Minh">
                </div>

                <div class="col-12 mt-4">
                  <button type="submit" class="btn btn-bee-primary px-4 py-2 fw-bold">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Thông Tin Ngân Hàng
                  </button>
                </div>
              </div>
            </form>
          </div>

          <!-- TAB 4: SỔ ĐỊA CHỈ GIAO HÀNG -->
          <div class="tab-pane fade {{ $activeTab === 'addresses' ? 'show active' : '' }}" id="v-pills-addresses" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
              <h5 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-map-location-dot me-2 text-warning"></i> Sổ Địa Chỉ Giao Hàng
              </h5>
              <button type="button" class="btn btn-bee-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                <i class="fa-solid fa-plus me-1"></i> Thêm Địa Chỉ Mới
              </button>
            </div>

            <div class="d-flex flex-column gap-3">
              @forelse($addresses as $addr)
                <div class="border rounded-3 p-3 position-relative {{ $addr->is_default ? 'border-warning bg-warning-subtle bg-opacity-10' : 'bg-light' }}">
                  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                    <div>
                      <strong class="text-dark me-2">{{ $addr->recipient_name }}</strong>
                      <span class="badge bg-secondary me-2">{{ $addr->label ?? 'Nhà riêng' }}</span>
                      @if($addr->is_default)
                        <span class="badge bg-warning text-dark fw-bold"><i class="fa-solid fa-star me-1"></i> Mặc định</span>
                      @endif
                    </div>
                    <div class="d-flex gap-2">
                      @if(!$addr->is_default)
                        <form action="{{ route('client.profile.address.default', $addr->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none text-muted small hover-warning">
                            Đặt làm mặc định
                          </button>
                        </form>
                        <span class="text-muted">|</span>
                      @endif

                      <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none text-primary small" data-bs-toggle="modal" data-bs-target="#modalEditAddress{{ $addr->id }}">
                        Chỉnh sửa
                      </button>
                      <span class="text-muted">|</span>

                      <form action="{{ route('client.profile.address.delete', $addr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none text-danger small">
                          Xóa
                        </button>
                      </form>
                    </div>
                  </div>

                  <p class="small text-secondary mb-1">
                    <i class="fa-solid fa-phone me-1 text-muted"></i> Số điện thoại: <strong>{{ $addr->phone }}</strong>
                  </p>
                  <p class="small text-secondary mb-1">
                    <i class="fa-solid fa-location-dot me-1 text-muted"></i> Địa chỉ: {{ $addr->full_address }}
                  </p>
                  @if($addr->notes)
                    <p class="small text-muted mb-0 fst-italic">
                      <i class="fa-solid fa-circle-info me-1"></i> Ghi chú: {{ $addr->notes }}
                    </p>
                  @endif
                </div>

                <!-- EDIT ADDRESS MODAL -->
                <div class="modal fade" id="modalEditAddress{{ $addr->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                      <form action="{{ route('client.profile.address.update', $addr->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header bg-light">
                          <h6 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-1 text-warning"></i> Chỉnh Sửa Địa Chỉ Nhận Hàng</h6>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                          <div class="row g-3">
                            <div class="col-md-6">
                              <label class="form-label small fw-bold">Tên người nhận <span class="text-danger">*</span></label>
                              <input type="text" name="recipient_name" class="form-control form-control-sm" value="{{ $addr->recipient_name }}" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label small fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                              <input type="text" name="phone" class="form-control form-control-sm" value="{{ $addr->phone }}" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label small fw-bold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                              <input type="text" name="city" class="form-control form-control-sm" value="{{ $addr->city }}" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label small fw-bold">Quận / Huyện <span class="text-danger">*</span></label>
                              <input type="text" name="district" class="form-control form-control-sm" value="{{ $addr->district }}" required>
                            </div>
                            <div class="col-md-6">
                              <label class="form-label small fw-bold">Phường / Xã</label>
                              <input type="text" name="ward" class="form-control form-control-sm" value="{{ $addr->ward }}">
                            </div>
                            <div class="col-md-6">
                              <label class="form-label small fw-bold">Loại địa chỉ</label>
                              <select name="label" class="form-select form-select-sm">
                                <option value="Nhà riêng" {{ $addr->label === 'Nhà riêng' ? 'selected' : '' }}>Nhà riêng</option>
                                <option value="Văn phòng" {{ $addr->label === 'Văn phòng' ? 'selected' : '' }}>Văn phòng</option>
                                <option value="Khác" {{ $addr->label === 'Khác' ? 'selected' : '' }}>Khác</option>
                              </select>
                            </div>
                            <div class="col-12">
                              <label class="form-label small fw-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                              <input type="text" name="address" class="form-control form-control-sm" value="{{ $addr->address }}" required>
                            </div>
                            <div class="col-12">
                              <label class="form-label small fw-bold">Ghi chú giao hàng</label>
                              <input type="text" name="notes" class="form-control form-control-sm" value="{{ $addr->notes }}">
                            </div>
                            <div class="col-12">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="defaultCheck{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }}>
                                <label class="form-check-label small" for="defaultCheck{{ $addr->id }}">
                                  Đặt làm địa chỉ giao hàng mặc định
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                          <button type="submit" class="btn btn-bee-primary btn-sm fw-bold">Lưu Cập Nhật</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @empty
                <div class="text-center py-4">
                  <p class="text-muted small mb-3">Bạn chưa có địa chỉ nhận hàng nào trong sổ địa chỉ.</p>
                  <button type="button" class="btn btn-bee-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                    <i class="fa-solid fa-plus me-1"></i> Thêm Địa Chỉ Mới
                  </button>
                </div>
              @endforelse
            </div>
          </div>

          <!-- TAB 5: LỊCH SỬ ĐƠN HÀNG -->
          <div class="tab-pane fade {{ $activeTab === 'orders' ? 'show active' : '' }}" id="v-pills-orders" role="tabpanel">
            <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
              <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i> Lịch Sử Đơn Hàng Của Bạn
            </h5>

            <div class="d-flex flex-column gap-3">
              @forelse($orders as $order)
                <div class="border rounded-3 p-3 bg-light-subtle">
                  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-2 border-bottom mb-3">
                    <div>
                      <span class="small text-muted">Mã đơn:</span>
                      <strong class="text-dark font-monospace">{{ $order->order_code }}</strong>
                      <span class="text-muted small ms-2">({{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }})</span>
                    </div>
                    <div>
                      @if($order->status_step == 6)
                        <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-check me-1"></i> {{ $order->status_label ?? 'Hoàn tất' }}</span>
                      @else
                        <span class="badge bg-warning-subtle text-dark fw-bold"><i class="fa-solid fa-truck-fast me-1"></i> {{ $order->status_label ?? 'Đang xử lý' }}</span>
                      @endif
                    </div>
                  </div>

                  <!-- Items Preview -->
                  <div class="d-flex flex-column gap-2 mb-3">
                    @foreach($order->items as $item)
                      <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                          <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 40px; height: 40px; object-fit: contain;">
                          <div>
                            <div class="small fw-semibold text-dark">{{ $item->product_name }}</div>
                            <small class="text-muted">{{ $item->color }} / {{ $item->size }} • SL: {{ $item->quantity }}</small>
                          </div>
                        </div>
                        <span class="small fw-bold">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</span>
                      </div>
                    @endforeach
                  </div>

                  <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <div>
                      <span class="small text-muted">Tổng thanh toán: </span>
                      <strong class="text-danger fs-6">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                    </div>
                    <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="btn btn-sm btn-outline-warning text-dark fw-bold">
                      Chi Tiết &amp; Vận Chuyển <i class="fa-solid fa-chevron-right ms-1"></i>
                    </a>
                  </div>
                </div>
              @empty
                <div class="text-center py-4">
                  <p class="text-muted small">Bạn chưa có đơn hàng nào tại BeeStyle.</p>
                  <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary btn-sm">Khám Phá Sản Phẩm Ngay</a>
                </div>
              @endforelse
            </div>
          </div>

>>>>>>> ae0973d0eb9f4662314af3ad5ec267e0a9340ca0
        </div>

      </div>
    </div>
  </div>
</div>

<!-- MODAL ADD ADDRESS -->
<div class="modal fade" id="modalAddAddress" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form action="{{ route('client.profile.address.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-light">
          <h6 class="modal-title fw-bold text-dark"><i class="fa-solid fa-map-pin me-1 text-warning"></i> Thêm Địa Chỉ Nhận Hàng Mới</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-bold">Tên người nhận <span class="text-danger">*</span></label>
              <input type="text" name="recipient_name" class="form-control form-control-sm" value="{{ old('recipient_name', $user->name) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Số điện thoại <span class="text-danger">*</span></label>
              <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $user->phone) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
              <input type="text" name="city" class="form-control form-control-sm" placeholder="TP. Hồ Chí Minh" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Quận / Huyện <span class="text-danger">*</span></label>
              <input type="text" name="district" class="form-control form-control-sm" placeholder="Quận 1" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Phường / Xã</label>
              <input type="text" name="ward" class="form-control form-control-sm" placeholder="Phường Bến Nghé">
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-bold">Loại địa chỉ</label>
              <select name="label" class="form-select form-select-sm">
                <option value="Nhà riêng">Nhà riêng</option>
                <option value="Văn phòng">Văn phòng</option>
                <option value="Khác">Khác</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
              <input type="text" name="address" class="form-control form-control-sm" placeholder="Số 123 Đường Nguyễn Trãi..." required>
            </div>
            <div class="col-12">
              <label class="form-label small fw-bold">Ghi chú giao hàng</label>
              <input type="text" name="notes" class="form-control form-control-sm" placeholder="Ví dụ: Gọi trước khi giao 15 phút">
            </div>
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="defaultCheckAdd" checked>
                <label class="form-check-label small" for="defaultCheckAdd">
                  Đặt làm địa chỉ giao hàng mặc định
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-bee-primary btn-sm fw-bold">Lưu Địa Chỉ</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle logic
    document.querySelectorAll('.toggle-password-btn').forEach(function(button) {
      button.addEventListener('click', function() {
        var targetId = this.getAttribute('data-target');
        var input = document.getElementById(targetId);
        var icon = this.querySelector('i');
        if (input) {
          if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          }
        }
      });
    });
  });
</script>
@endpush

