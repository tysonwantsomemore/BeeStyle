@extends('layouts.client')

@section('title', 'Tài Khoản & Hồ Sơ Cá Nhân | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Hồ sơ tài khoản</li>
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
          </span>
          <span class="badge bg-light text-dark fw-bold px-3 py-2 rounded-pill border">
            <i class="fa-solid fa-coins me-1 text-warning"></i> {{ number_format($user->points ?? 0) }} Điểm
          </span>
        </div>

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
            </a>
          @endif
          
          <!-- LOGOUT BUTTON FORM -->
          <form action="{{ route('auth.logout') }}" method="POST" class="mt-2 pt-2 border-top">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 fw-semibold rounded-2">
              <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Đăng Xuất Tài Khoản
            </button>
          </form>
        </div>
      </div>
    </div>

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
                  <input type="password" name="current_password" class="form-control" required placeholder="Nhập mật khẩu bạn đang dùng...">
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
                  <input type="password" name="password" class="form-control" required minlength="6" placeholder="Tối thiểu 6 ký tự...">
                </div>

                <div class="col-md-6">
                  <label class="form-label small fw-bold text-dark">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                  <input type="password" name="password_confirmation" class="form-control" required minlength="6" placeholder="Nhập lại mật khẩu mới...">
                </div>

                <div class="col-12 mt-4">
                  <button type="submit" class="btn btn-bee-primary px-4 py-2 fw-bold">
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
