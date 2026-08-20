@extends('layouts.client')

@section('title', 'Giỏ Hàng Của Bạn | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Giỏ hàng</li>
    </ol>
  </nav>

  <h3 class="fw-bold text-dark mb-4">
    <i class="fa-solid fa-bag-shopping me-2 text-warning"></i> Giỏ Hàng ({{ $cartCount }} sản phẩm)
  </h3>

  @if(count($cartItems) > 0)
    <div class="row g-4">
      <!-- CART ITEMS LIST -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
          <div class="table-responsive">
            <table class="table align-middle">
              <thead class="table-light">
                <tr class="small text-uppercase text-muted">
                  <th>Sản phẩm</th>
                  <th>Đơn giá</th>
                  <th>Số lượng</th>
                  <th>Thành tiền</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($cartItems as $item)
                  <tr>
                    <!-- Product details -->
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-3 p-1" style="width: 70px; height: 70px;">
                          <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-100 h-100 object-fit-contain">
                        </div>
                        <div>
                          <a href="{{ route('client.products.show', $item['product_id']) }}" class="fw-bold text-dark mb-1 fs-9 text-decoration-none d-block">
                            {{ $item['name'] }}
                          </a>
                          <div class="text-muted small">
                            <span>Màu: <strong>{{ $item['color'] }}</strong></span> | 
                            <span>Size: <strong>{{ $item['size'] }}</strong></span>
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- Price -->
                    <td>
                      <span class="fw-bold text-dark">{{ number_format($item['price'], 0, ',', '.') }}₫</span>
                    </td>

                    <!-- Quantity -->
                    <td>
                      <div class="d-flex align-items-center">
                        <form action="{{ route('client.cart.update') }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="key" value="{{ $item['key'] }}">
                          <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                          <button class="btn btn-outline-secondary btn-sm" type="submit" style="width: 28px; height: 28px; padding: 0;">-</button>
                        </form>

                        <span class="px-2 fw-bold text-dark">{{ $item['quantity'] }}</span>

                        <form action="{{ route('client.cart.update') }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="key" value="{{ $item['key'] }}">
                          <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                          <button class="btn btn-outline-secondary btn-sm" type="submit" style="width: 28px; height: 28px; padding: 0;">+</button>
                        </form>
                      </div>
                    </td>

                    <!-- Total -->
                    <td>
                      <span class="fw-bold text-danger">{{ number_format($item['subtotal'], 0, ',', '.') }}₫</span>
                    </td>

                    <!-- Delete -->
                    <td>
                      <form action="{{ route('client.cart.remove', $item['key']) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger p-0" title="Xóa"><i class="fa-regular fa-trash-can"></i></button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('client.products.index') }}" class="btn btn-outline-dark btn-sm rounded-2">
              <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục mua sắm
            </a>
            <form action="{{ route('client.cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?');">
              @csrf
              <button type="submit" class="btn btn-light btn-sm text-danger border">
                <i class="fa-solid fa-trash me-1"></i> Xóa sạch giỏ hàng
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- ORDER SUMMARY SIDEBAR -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4" style="border-radius: 16px; position: sticky; top: 100px;">
          <h5 class="fw-bold text-dark mb-3">Tóm Tắt Đơn Hàng</h5>

          <!-- Voucher Code Input -->
          <div class="mb-3">
            <label class="small text-muted mb-1">Mã giảm giá / Voucher</label>
            @if($appliedCoupon)
              <div class="p-2 bg-warning-subtle rounded-3 d-flex justify-content-between align-items-center mb-2">
                <div>
                  <span class="fw-bold text-dark font-monospace">{{ $appliedCoupon->code }}</span>
                  <small class="d-block text-success"><i class="fa-solid fa-check me-1"></i> {{ $appliedCoupon->title }}</small>
                </div>
                <form action="{{ route('client.cart.removeCoupon') }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hủy mã"><i class="fa-solid fa-xmark fs-5"></i></button>
                </form>
              </div>
            @else
              <form action="{{ route('client.cart.applyCoupon') }}" method="POST">
                @csrf
                <div class="input-group">
                  <input type="text" name="code" class="form-control form-control-sm" placeholder="Nhập mã BEESTYLE50..." required>
                  <button class="btn btn-bee-primary btn-sm px-3" type="submit">Áp Dụng</button>
                </div>
              </form>
            @endif
          </div>

          <hr class="border-secondary-subtle my-3">

          <!-- Breakdown -->
          <div class="d-flex flex-column gap-2 small mb-3">
            <div class="d-flex justify-content-between">
              <span class="text-muted">Tạm tính:</span>
              <span class="fw-semibold text-dark">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Giảm giá voucher:</span>
              <span class="fw-semibold text-success">-{{ number_format($discount, 0, ',', '.') }}₫</span>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Phí vận chuyển:</span>
              @if($shipping == 0)
                <span class="fw-semibold text-success">Miễn phí (Freeship)</span>
              @else
                <span class="fw-semibold text-dark">{{ number_format($shipping, 0, ',', '.') }}₫</span>
              @endif
            </div>
          </div>

          <hr class="border-secondary-subtle my-2">

          <div class="d-flex justify-content-between align-items-baseline mb-4">
            <span class="fw-bold text-dark fs-6">Tổng thanh toán:</span>
            <span class="fs-4 fw-bold text-danger">{{ number_format($total, 0, ',', '.') }}₫</span>
          </div>

          @auth
            <a href="{{ route('client.checkout') }}" class="btn btn-bee-primary w-100 py-3 fs-6">
              Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          @else
            <div class="alert alert-warning border-0 d-flex align-items-center gap-2 small p-2 mb-3" style="border-radius: 8px;">
              <i class="fa-solid fa-shield-halved text-warning fs-5"></i>
              <div>
                <strong>Yêu cầu đăng nhập:</strong> Vui lòng đăng nhập để tiến hành thanh toán và lưu lịch sử đơn hàng.
              </div>
            </div>
            <a href="{{ route('client.checkout') }}" class="btn btn-bee-accent w-100 py-3 fs-6">
              <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Đăng Nhập Để Thanh Toán <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
          @endauth

          <div class="mt-3 text-center text-muted small">
            <i class="fa-solid fa-lock me-1 text-warning"></i> Giao dịch bảo mật 100% chuẩn SSL
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: 16px;">
      <div class="mb-3">
        <i class="fa-solid fa-bag-shopping text-warning" style="font-size: 4rem;"></i>
      </div>
      <h4 class="fw-bold text-dark mb-2">Giỏ hàng của bạn đang trống</h4>
      <p class="text-muted small mb-4">Hãy khám phá ngay các bộ sưu tập áo polo nam, blazer và phụ kiện cao cấp của BeeStyle!</p>
      <div>
        <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary px-4 py-2">
          <i class="fa-solid fa-bag-shopping me-1"></i> Mua Sắm Ngay
        </a>
      </div>
    </div>
  @endif
</div>
@endsection
