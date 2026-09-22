@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm Thời Trang | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">KHO HÀNG &amp; SẢN PHẨM</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Sản Phẩm Thời Trang</h2>
    </div>
    <p class="text-body-tertiary mb-0">Theo dõi tồn kho, giá bán niêm yết, bật/tắt kinh doanh và quản lý các phân loại màu sắc &amp; kích cỡ</p>
  </div>
  <div class="col-auto d-flex gap-2">
    <a href="{{ route('admin.reports.inventory') }}" class="btn btn-outline-warning text-dark fw-bold bg-white shadow-xs">
      <i class="fa-solid fa-chart-pie me-1.5 text-warning"></i> Báo Cáo Tồn Kho
    </a>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
      <span class="fas fa-plus me-1"></span> Thêm Sản Phẩm Mới
    </a>
  </div>
</div>

<!-- THÔNG BÁO CHUYỂN HƯỚNG BÁO CÁO TỒN KHO -->
<div class="card border-0 shadow-xs mb-4 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
  <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
      <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle shadow-xs" style="width: 42px; height: 42px; min-width: 42px; font-size: 1.1rem;">
        <i class="fa-solid fa-boxes-stacked"></i>
      </div>
      <div>
        <h6 class="mb-0 fw-bold text-dark fs-9">Báo Cáo Tồn Kho &amp; Định Giá Tài Sản Lưu Kho Chuyên Sâu</h6>
        <p class="mb-0 text-muted fs-10">
          Chỉ số tổng hợp kho hàng, tình trạng mở bán, hàng tạm dừng và cảnh báo tồn kho (≤ 5 mẫu cần nhập gấp) đã được chuyển thành trang báo cáo riêng biệt.
        </p>
      </div>
    </div>
    <div>
      <a href="{{ route('admin.reports.inventory') }}" class="btn btn-sm btn-warning text-dark fw-bold px-3 shadow-xs">
        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Xem Báo Cáo Tồn Kho Chi Tiết
      </a>
    </div>
  </div>
</div>

<!-- PRODUCTS TABLE CARD -->
<div class="card border-0 shadow-sm mb-4">
  <!-- FILTER TOOLBAR -->
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <div class="position-relative">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm search-input" placeholder="Tìm tên hoặc mã SKU..." style="width: 220px;">
      </div>
      
      <!-- Lọc Danh mục -->
      <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 170px;">
        <option value="">Tất cả danh mục</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>

      <!-- Lọc Thương hiệu -->
      <select name="brand_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 170px;">
        <option value="">Tất cả thương hiệu</option>
        @foreach($brands as $b)
          <option value="{{ $b->id }}" {{ request('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
        @endforeach
      </select>

      <!-- Lọc Trạng thái Kinh doanh / Tồn kho -->
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 180px;">
        <option value="">Tất cả trạng thái</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Đang kinh doanh</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>⚪ Đang tạm dừng</option>
        <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>🔴 Sắp hết / Hết hàng (≤5)</option>
      </select>

      <button type="submit" class="btn btn-sm btn-phoenix-secondary">Lọc</button>
      @if(request('q') || request('category_id') || request('brand_id') || request('status'))
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>

    <div class="text-body-tertiary fs-10">
      Tổng cộng: <strong class="text-body-emphasis">{{ $products->total() }}</strong> sản phẩm
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2">Mã SKU</th>
            <th class="py-2">Sản Phẩm</th>
            <th class="py-2">Danh Mục / Hiệu</th>
            <th class="py-2">Giá Bán</th>
            <th class="py-2">Giá Gốc</th>
            <th class="py-2">Tồn Kho</th>
            <th class="py-2">Đã Bán</th>
            <th class="py-2">Đánh Giá</th>
            <th class="py-2">Trạng Thái (1-Click)</th>
            <th class="text-end pe-3 py-2">Hành Động</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($products as $product)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <td class="ps-3 py-2"><span class="font-monospace fw-bold text-primary">{{ $product->sku }}</span></td>
              <td class="py-2">
                <div class="d-flex align-items-center gap-2">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 40px; height: 40px; object-fit: contain;" class="rounded border border-translucent bg-body-emphasis">
                  <div>
                    <a href="{{ route('admin.products.show', $product->id) }}" class="fw-bold text-body-emphasis text-decoration-none d-block text-truncate fs-9" style="max-width: 200px;" title="Xem chi tiết: {{ $product->name }}">
                      {{ $product->name }}
                    </a>
                    <small class="text-body-tertiary fs-10">{{ $product->variants->count() }} biến thể màu/size</small>
                  </div>
                </div>
              </td>
              <td class="py-2">
                <div class="d-flex flex-column gap-1">
                  <span class="badge badge-phoenix badge-phoenix-secondary w-fit">{{ $product->category->name ?? 'Thời trang nam' }}</span>
                  @if($product->brand)
                    <small class="text-body-tertiary fs-10"><i class="fa-solid fa-tag me-1"></i>{{ $product->brand->name }}</small>
                  @endif
                </div>
              </td>
              <td class="py-2"><strong class="text-danger">{{ number_format($product->price, 0, ',', '.') }}₫</strong></td>
              <td class="py-2"><span class="text-body-tertiary text-decoration-line-through fs-10">{{ number_format($product->original_price, 0, ',', '.') }}₫</span></td>
              <td class="py-2">
                @if($product->stock <= 0)
                  <span class="badge badge-phoenix badge-phoenix-danger"><i class="fa-solid fa-xmark me-1"></i> Hết hàng</span>
                @elseif($product->stock <= 5)
                  <span class="badge badge-phoenix badge-phoenix-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> Còn {{ $product->stock }}</span>
                @else
                  <span class="fw-semibold text-body-emphasis">{{ $product->stock }} cái</span>
                @endif
              </td>
              <td class="py-2"><span class="badge badge-phoenix badge-phoenix-success">{{ $product->sold_count }}</span></td>
              <td class="py-2">
                <span class="text-warning fs-10 fw-bold">
                  <i class="fa-solid fa-star"></i> {{ $product->rating }}
                </span>
                <small class="text-body-tertiary fs-10">({{ $product->reviews_count }})</small>
              </td>
              <!-- 1-CLICK TOGGLE BUTTON -->
              <td class="py-2">
                <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" class="d-inline">
                  @csrf
                  @if(!$product->is_active)
                    <button type="submit" class="btn btn-sm btn-phoenix-secondary py-1 px-2 fs-10" title="Bấm để mở bán lại">
                      <i class="fa-solid fa-eye-slash me-1"></i> Tạm dừng
                    </button>
                  @elseif($product->stock <= 0)
                    <button type="submit" class="btn btn-sm btn-phoenix-danger py-1 px-2 fs-10" title="Hết hàng (Bấm để ẩn)">
                      <i class="fa-solid fa-circle-exclamation me-1"></i> Hết hàng
                    </button>
                  @else
                    <button type="submit" class="btn btn-sm btn-phoenix-success py-1 px-2 fs-10" title="Bấm để tạm dừng kinh doanh">
                      <i class="fa-solid fa-circle-check me-1"></i> Kinh doanh
                    </button>
                  @endif
                </form>
              </td>
              <td class="text-end pe-3 py-2 text-nowrap">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <!-- Nút Xem chi tiết sản phẩm -->
                  <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-phoenix-info py-1 px-2 fs-10" title="Xem chi tiết sản phẩm">
                    <i class="fa-solid fa-eye me-1"></i> Xem
                  </a>
                  <!-- Nút Xem ngoài Web (Tab mới) -->
                  <a href="{{ route('client.products.show', $product->id) }}" target="_blank" class="btn btn-sm btn-phoenix-secondary py-1 px-1.5 fs-10" title="Xem trên giao diện khách hàng (Mở tab mới)">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                  </a>
                  <!-- Nút Sửa -->
                  <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10" title="Chỉnh sửa sản phẩm">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                  </a>
                  <!-- Nút Xóa -->
                  <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi hệ thống?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-phoenix-danger py-1 px-2 fs-10" title="Xóa">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-shirt fs-4 text-body-tertiary mb-2 d-block"></i>
                Không tìm thấy sản phẩm nào phù hợp.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($products->hasPages())
    <div class="card-footer d-flex justify-content-center py-3 bg-body-emphasis border-top border-translucent">
      {{ $products->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

@endsection