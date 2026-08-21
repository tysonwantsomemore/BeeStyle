@extends('layouts.client')

@section('title', 'Tra Cứu Hành Trình Đơn Hàng | BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Tra cứu đơn hàng</li>
    </ol>
  </nav>

  <!-- SEARCH ORDER BOX -->
  <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-3 mb-lg-0">
        <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-truck-fast text-warning me-2"></i> Tra Cứu Hành Trình Đơn Hàng</h4>
        <p class="text-muted small mb-0">Nhập mã đơn hàng (VD: BEE-2026-0816-01) để kiểm tra trạng thái vận chuyển thời gian thực</p>
      </div>
      <div class="col-lg-6">
        <form action="{{ route('client.order-tracking') }}" method="GET" class="d-flex gap-2">
          <input type="text" name="code" value="{{ $code }}" class="form-control" placeholder="Nhập mã đơn hàng..." required>
          <button type="submit" class="btn btn-bee-primary px-4 text-nowrap">Tra Cứu</button>
        </form>
      </div>
    </div>
  </div>

  @if($currentOrder)
    <!-- ORDER STATUS & TRACKER -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px;">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-3 border-bottom">
        <div>
          <span class="text-muted small">Mã đơn hàng:</span>
          <h5 class="fw-bold text-dark mb-0 font-monospace">{{ $currentOrder->order_code }}</h5>
        </div>
        <div>
          <span class="text-muted small">Thời gian đặt:</span>
          <div class="fw-semibold text-dark">{{ $currentOrder->created_at ? $currentOrder->created_at->format('d/m/Y H:i') : '16/08/2026' }}</div>
        </div>
        <div>
          <span class="text-muted small">Trạng thái:</span>
          <div>
            <span class="badge bg-warning text-dark px-3 py-2 fw-bold">
              {{ $currentOrder->status_label }}
            </span>
          </div>
        </div>
        <div>
          <span class="text-muted small">Tổng tiền:</span>
          <div class="fw-bold text-danger fs-5">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</div>
        </div>
      </div>

      <!-- 6-STEP TIMELINE TRACKER -->
      <div class="bee-timeline-steps my-5">
        @php
          $steps = [
            1 => 'Chờ xác nhận',
            2 => 'Đã xác nhận',
            3 => 'Đang đóng gói',
            4 => 'Đang giao hàng',
            5 => 'Đã giao hàng',
            6 => 'Hoàn tất'
          ];
          $currentStep = $currentOrder->status_step;
        @endphp

        @foreach($steps as $stepNum => $stepLabel)
          <div class="bee-timeline-step {{ $currentStep > $stepNum ? 'completed' : ($currentStep == $stepNum ? 'active' : '') }}">
            <div class="bee-timeline-step-icon">
              @if($currentStep > $stepNum)
                <i class="fa-solid fa-check"></i>
              @else
                {{ $stepNum }}
              @endif
            </div>
            <div class="bee-timeline-step-label">{{ $stepLabel }}</div>
          </div>
        @endforeach
      </div>

      <!-- COMPLETED ORDER REVIEW NOTIFICATION BANNER -->
      @if($currentOrder->status_step >= 5 || in_array($currentOrder->shipping_status, ['delivered', 'completed']))
        <div class="alert alert-success border-0 shadow-sm p-3 p-md-4 my-4 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: #ecfdf5; border-left: 5px solid #10b981 !important;">
          <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
              <i class="fa-solid fa-gift fs-4"></i>
            </div>
            <div>
              <h6 class="fw-bold text-success mb-1 fs-6">ĐƠN HÀNG ĐÃ GIAO THÀNH CÔNG!</h6>
              <p class="mb-0 text-muted small">Cảm ơn bạn đã mua sắm tại BeeStyle! Hãy dành 1 phút đánh giá chất lượng sản phẩm để nhận ngay <strong>+20 điểm thưởng VIP</strong> nhé.</p>
            </div>
          </div>
          <a href="{{ route('client.products.show', $currentOrder->items->first()->product_id ?? 1) }}#reviews" class="btn btn-bee-primary px-4 py-2 text-nowrap">
            <i class="fa-solid fa-star text-warning me-1"></i> ĐÁNH GIÁ SẢN PHẨM NGAY
          </a>
        </div>
      @endif

      <!-- ORDER DETAILS & CUSTOMER INFO -->
      <div class="row g-4 pt-3 border-top">
        <div class="col-md-6 border-end">
          <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user me-2 text-warning"></i> Thông Tin Nhận Hàng</h6>
          <p class="small mb-1"><strong>Người nhận:</strong> {{ $currentOrder->customer_name }}</p>
          <p class="small mb-1"><strong>Số điện thoại:</strong> {{ $currentOrder->customer_phone }}</p>
          @if($currentOrder->customer_email)
            <p class="small mb-1"><strong>Email:</strong> {{ $currentOrder->customer_email }}</p>
          @endif
          <p class="small mb-1"><strong>Địa chỉ giao:</strong> {{ $currentOrder->shipping_address }}{{ $currentOrder->city ? ', ' . $currentOrder->city : '' }}</p>
          <p class="small mb-1"><strong>Hình thức thanh toán:</strong> {{ $currentOrder->payment_method_name }}</p>
          <p class="small mb-0"><strong>Trạng thái thanh toán:</strong> <span class="badge {{ $currentOrder->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $currentOrder->payment_status_label }}</span></p>
          @if($currentOrder->notes)
            <p class="small text-muted mt-2 mb-0"><strong>Ghi chú:</strong> "{{ $currentOrder->notes }}"</p>
          @endif
        </div>

        <div class="col-md-6">
          <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-box-open me-2 text-warning"></i> Sản Phẩm Trong Đơn Hàng</h6>
          <div class="d-flex flex-column gap-2">
            @foreach($currentOrder->items as $item)
              <div class="d-flex align-items-center justify-content-between p-2 bg-light rounded-3">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($item->image ?? '/assets/img/products/1.png') }}" alt="{{ $item->product_name }}" style="width: 45px; height: 45px; object-fit: contain;">
                  <div>
                    <div class="small fw-bold text-dark">{{ $item->product_name }}</div>
                    <small class="text-muted">{{ $item->color ?? 'Tiêu chuẩn' }} / Size {{ $item->size ?? 'M' }} • x{{ $item->quantity }}</small>
                  </div>
                </div>
                <div class="text-end">
                  <div class="fw-bold small text-dark">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</div>
                  @if($currentOrder->status_step >= 5 || in_array($currentOrder->shipping_status, ['delivered', 'completed']))
                    @php
                      $isReviewed = false;
                      if (Auth::check()) {
                        $isReviewed = \App\Models\Review::where('product_id', $item->product_id)->where('user_id', Auth::id())->exists();
                      }
                    @endphp
                    @if($isReviewed)
                      <span class="badge bg-success-subtle text-success py-1 px-2 mt-1 small" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-circle-check me-1"></i> Đã đánh giá
                      </span>
                    @else
                      <a href="{{ route('client.products.show', $item->product_id ?? 1) }}#reviews" class="btn btn-sm btn-outline-danger py-0 px-2 text-nowrap mt-1 fw-bold" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-star text-warning me-1"></i> Đánh giá
                      </a>
                    @endif
                  @endif
                </div>
              </div>
            @endforeach
          </div>


          <div class="mt-3 pt-2 border-top small">
            <div class="d-flex justify-content-between text-muted">
              <span>Tạm tính:</span>
              <span>{{ number_format($currentOrder->subtotal, 0, ',', '.') }}₫</span>
            </div>
            @if($currentOrder->discount_amount > 0)
              <div class="d-flex justify-content-between text-success">
                <span>Giảm giá ({{ $currentOrder->coupon_code ?? 'VOUCHER' }}):</span>
                <span>-{{ number_format($currentOrder->discount_amount, 0, ',', '.') }}₫</span>
              </div>
            @endif
            <div class="d-flex justify-content-between text-muted">
              <span>Phí vận chuyển:</span>
              <span>{{ $currentOrder->shipping_fee > 0 ? number_format($currentOrder->shipping_fee, 0, ',', '.') . '₫' : 'Miễn phí' }}</span>
            </div>
            <div class="d-flex justify-content-between fw-bold text-dark fs-6 mt-1">
              <span>Tổng tiền:</span>
              <span class="text-danger">{{ number_format($currentOrder->total_amount, 0, ',', '.') }}₫</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  @else
    <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: 16px;">
      <i class="fa-solid fa-magnifying-glass fs-1 text-muted mb-3"></i>
      <h5 class="fw-bold text-dark">Không tìm thấy đơn hàng</h5>
      <p class="text-muted small">Vui lòng kiểm tra lại mã đơn hàng chính xác hoặc liên hệ hotline 1900 8888 để được hỗ trợ.</p>
    </div>
  @endif

</div>
@endsection
