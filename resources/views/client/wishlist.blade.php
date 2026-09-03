@extends('layouts.client')

@section('title', 'Sản Phẩm Yêu Thích • BeeStyle Menswear')

@section('content')
<div class="container py-4">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('client.home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
      <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Sản phẩm yêu thích</li>
    </ol>
  </nav>

  <!-- HEADER TIÊU ĐỀ -->
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 pb-3 border-bottom">
    <div>
      <h2 class="fw-bold text-dark mb-1" style="font-size: 1.6rem; letter-spacing: -0.5px;">
        <i class="fa-solid fa-heart text-danger me-2"></i> SẢN PHẨM YÊU THÍCH
      </h2>
      <p class="text-muted small mb-0">
        Danh sách những thiết kế thời trang bạn đã lưu lại để tham khảo và đặt mua
      </p>
    </div>
    
    @if($products->count() > 0)
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-warning-subtle text-dark border border-warning px-3 py-2 fw-bold" style="font-size: 0.85rem;">
          Đã lưu: <strong class="text-danger fs-6">{{ $products->count() }}</strong> sản phẩm
        </span>
        <form action="{{ route('client.wishlist.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi danh sách yêu thích?');">
          @csrf
          <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-1.5 rounded-2 fw-semibold">
            <i class="fa-regular fa-trash-can me-1"></i> Xóa tất cả
          </button>
        </form>
      </div>
    @endif
  </div>

  @if($products->count() > 0)
    <!-- DANH SÁCH SẢN PHẨM YÊU THÍCH -->
    <div class="row g-3 g-md-4">
      @foreach($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
          <div class="card h-100 border-0 shadow-sm transition-all hover-lift" style="border-radius: 16px; overflow: hidden; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
            
            <!-- ẢNH & NÚT XÓA YÊU THÍCH -->
            <div class="position-relative bg-light p-3 text-center" style="height: 220px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
              @if($product->discount_percent > 0)
                <span class="position-absolute top-0 start-0 m-2.5 badge bg-danger rounded-pill shadow-xs" style="font-size: 0.72rem; z-index: 2;">-{{ $product->discount_percent }}%</span>
              @endif

              <!-- NÚT BỎ YÊU THÍCH -->
              <form action="{{ route('client.wishlist.remove', $product->id) }}" method="POST" class="position-absolute top-0 end-0 m-2" style="z-index: 3;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-white rounded-circle shadow-xs border text-danger" title="Xóa khỏi danh sách yêu thích" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; background: #ffffff;">
                  <i class="fa-solid fa-heart fs-6"></i>
                </button>
              </form>

              <a href="{{ route('client.products.show', $product->id) }}" class="d-block w-100 h-100 d-flex align-items-center justify-content-center">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid transition-all" style="max-height: 180px; object-fit: contain;">
              </a>
            </div>

            <!-- BODY CARD -->
            <div class="card-body p-3 d-flex flex-column justify-content-between">
              <div>
                <small class="text-warning fw-bold text-uppercase d-block mb-1" style="font-size: 0.75rem;">
                  {{ $product->category->name ?? 'Thời trang nam' }}
                </small>

                <h6 class="fw-bold text-dark text-truncate-2 mb-2" style="font-size: 0.92rem; min-height: 42px; line-height: 1.35;">
                  <a href="{{ route('client.products.show', $product->id) }}" class="text-decoration-none text-dark hover-warning">
                    {{ $product->name }}
                  </a>
                </h6>
              </div>

              <div>
                <div class="d-flex align-items-baseline gap-2 mb-2.5">
                  <strong class="text-danger fw-bold fs-6">{{ number_format($product->price, 0, ',', '.') }}₫</strong>
                  @if($product->original_price && $product->original_price > $product->price)
                    <small class="text-muted text-decoration-line-through" style="font-size: 0.8rem;">{{ number_format($product->original_price, 0, ',', '.') }}₫</small>
                  @endif
                </div>

                <!-- 2 NÚT THÊM GIỎ HÀNG & MUA NGAY (MỞ POPUP CHỌN MÀU & SIZE) -->
                <div class="d-flex gap-1.5">
                  <button type="button" class="btn btn-outline-warning text-dark btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap shadow-xs" 
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->price }}"
                    data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                    data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                    data-discount="{{ $product->discount_percent ?? 0 }}"
                    data-image="{{ asset($product->image) }}"
                    data-category="{{ $product->category->name ?? 'Thời trang nam' }}"
                    data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                    data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                    data-stock="{{ $product->stock ?? 999 }}"
                    onclick="openQuickVariantModal({{ $product->id }}, false, this)" 
                    title="Thêm vào giỏ hàng" style="font-size: 0.8rem; padding-top: 6px; padding-bottom: 6px;">
                    <i class="fa-solid fa-cart-plus me-1 text-warning"></i> Thêm Giỏ
                  </button>
                  <button type="button" class="btn btn-bee-primary btn-sm flex-fill fw-bold rounded-2 px-1 text-nowrap shadow-xs" 
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->price }}"
                    data-price-formatted="{{ number_format($product->price, 0, ',', '.') }}₫"
                    data-original-price-formatted="{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}"
                    data-discount="{{ $product->discount_percent ?? 0 }}"
                    data-image="{{ asset($product->image) }}"
                    data-category="{{ $product->category->name ?? 'Thời trang nam' }}"
                    data-colors="{{ json_encode($product->colors ?? ['Đen', 'Trắng', 'Xanh Navy']) }}"
                    data-sizes="{{ json_encode($product->sizes ?? ['S', 'M', 'L', 'XL', 'XXL']) }}"
                    data-stock="{{ $product->stock ?? 999 }}"
                    onclick="openQuickVariantModal({{ $product->id }}, true, this)" 
                    title="Mua ngay" style="font-size: 0.8rem; padding-top: 6px; padding-bottom: 6px;">
                    <i class="fa-solid fa-bolt me-1"></i> Mua Ngay
                  </button>
                </div>
              </div>

            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- NÚT TIẾP TỤC MUA SẮM -->
    <div class="text-center mt-5 pt-3 border-top">
      <a href="{{ route('client.products.index') }}" class="btn btn-outline-dark px-4 py-2.5 rounded-3 fw-bold shadow-xs">
        <i class="fa-solid fa-arrow-left me-1.5"></i> Tiếp Tục Khám Phá Sản Phẩm Khác
      </a>
    </div>

  @else
    <!-- EMPTY STATE KHI CHƯA CÓ YÊU THÍCH -->
    <div class="card border-0 shadow-sm p-5 text-center my-4" style="border-radius: 20px; background: #ffffff;">
      <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center mx-auto mb-3 shadow-xs" style="width: 80px; height: 80px;">
        <i class="fa-regular fa-heart fs-1"></i>
      </div>
      <h4 class="fw-bold text-dark mb-2">Chưa có sản phẩm nào trong danh sách yêu thích</h4>
      <p class="text-muted small mx-auto mb-4" style="max-width: 480px;">
        Hãy nhấn vào biểu tượng <i class="fa-solid fa-heart text-danger"></i> trái tim trên mỗi sản phẩm để lưu lại những thiết kế mà bạn ưng ý nhất!
      </p>
      <div>
        <a href="{{ route('client.products.index') }}" class="btn btn-bee-primary px-4 py-2.5 rounded-3 fw-bold shadow-xs">
          <i class="fa-solid fa-shirt me-1.5"></i> Khám Phá Bộ Sưu Tập Ngay
        </a>
      </div>
    </div>
  @endif
</div>
@endsection
