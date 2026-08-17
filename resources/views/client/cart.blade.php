@extends('layouts.client')

@section('title', 'Giỏ Hàng Của Bạn | BeeStyle Fashion')

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
    <i class="fa-solid fa-bag-shopping me-2 text-warning"></i> Giỏ Hàng ({{ count($cartItems) }} sản phẩm)
  </h3>

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
                        <h6 class="fw-bold text-dark mb-1 fs-9">{{ $item['name'] }}</h6>
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
                    <div class="input-group input-group-sm" style="width: 100px;">
                      <button class="btn btn-outline-secondary" type="button">-</button>
                      <input type="text" class="form-control text-center fw-bold" value="{{ $item['quantity'] }}">
                      <button class="btn btn-outline-secondary" type="button">+</button>
                    </div>
                  </td>

                  <!-- Total -->
                  <td>
                    <span class="fw-bold text-danger">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</span>
                  </td>

                  <!-- Delete -->
                  <td>
                    <button class="btn btn-link text-danger p-0" title="Xóa"><i class="fa-regular fa-trash-can"></i></button>
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
          <button type="button" class="btn btn-light btn-sm text-secondary border">
            <i class="fa-solid fa-arrows-rotate me-1"></i> Cập nhật giỏ hàng
          </button>
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
          <div class="input-group">
            <input type="text" class="form-control form-control-sm" value="BEESTYLE50" placeholder="Nhập mã ưu đãi...">
            <button class="btn btn-bee-primary btn-sm px-3" type="button">Áp Dụng</button>
          </div>
          <small class="text-success mt-1 d-block"><i class="fa-solid fa-circle-check me-1"></i> Đã áp dụng mã giảm 50.000₫</small>
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
            <span class="fw-semibold text-success">Miễn phí (Freeship)</span>
          </div>
        </div>

        <hr class="border-secondary-subtle my-2">

        <div class="d-flex justify-content-between align-items-baseline mb-4">
          <span class="fw-bold text-dark fs-6">Tổng thanh toán:</span>
          <span class="fs-4 fw-bold text-danger">{{ number_format($total, 0, ',', '.') }}₫</span>
        </div>

        <a href="{{ route('client.checkout') }}" class="btn btn-bee-primary w-100 py-3 fs-6">
          Tiến Hành Thanh Toán <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>

        <div class="mt-3 text-center text-muted small">
          <i class="fa-solid fa-lock me-1 text-warning"></i> Giao dịch bảo mật 100% chuẩn SSL
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
