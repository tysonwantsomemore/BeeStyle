@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục Thời Trang | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">PHÂN LOẠI</span>
        <h3 class="fw-bold text-dark mb-0">Danh Mục Thời Trang Nam</h3>
      </div>
      <p class="text-muted small mb-0">Tổ chức phân loại các dòng sản phẩm thời trang trong hệ thống BeeStyle Menswear</p>
    </div>
    <button type="button" class="btn btn-bee-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
      <i class="fa-solid fa-plus me-1.5"></i> Thêm Danh Mục Mới
    </button>
  </div>
</div>

<div class="bee-table-card">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Mã ID</th>
          <th>Tên Danh Mục</th>
          <th>Đường Dẫn (Slug)</th>
          <th>Biểu Tượng</th>
          <th>Số Sản Phẩm</th>
          <th>Mô Tả</th>
          <th>Trạng Thái</th>
          <th class="text-end">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $category)
          <tr>
            <td><strong class="font-monospace text-secondary">#{{ $category->id }}</strong></td>
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                  <i class="{{ $category->icon ?? 'fa-solid fa-shirt' }} fs-6"></i>
                </div>
                <strong class="text-dark small">{{ $category->name }}</strong>
              </div>
            </td>
            <td><code class="text-muted small">{{ $category->slug }}</code></td>
            <td><i class="{{ $category->icon ?? 'fa-solid fa-shirt' }} text-warning fs-5"></i></td>
            <td><span class="badge bg-light text-dark border px-2 py-1">{{ $category->products_count }} sản phẩm</span></td>
            <td><small class="text-muted">{{ Str::limit($category->description, 40) }}</small></td>
            <td>
              @if($category->is_active)
                <span class="badge bg-success-subtle text-success fw-bold py-1 px-2"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span>
              @else
                <span class="badge bg-secondary-subtle text-muted fw-bold py-1 px-2">Tạm ẩn</span>
              @endif
            </td>
            <td class="text-end">
              <div class="d-flex align-items-center justify-content-end gap-1.5">
                <a href="{{ route('client.products.index', ['category' => $category->slug]) }}" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Xem trên website">
                  <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
                <button type="button" class="btn btn-sm btn-outline-dark py-1 px-2.5 fw-bold" data-bs-toggle="modal" data-bs-target="#editCategoryModal_{{ $category->id }}" title="Chỉnh sửa">
                  <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                </button>
                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Xóa danh mục">
                    <i class="fa-regular fa-trash-can"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>

          <!-- MODAL EDIT CATEGORY -->
          <div class="modal fade" id="editCategoryModal_{{ $category->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom">
                  <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Sửa Danh Mục: {{ $category->name }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                      <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $category->name) }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Icon FontAwesome</label>
                      <input type="text" name="icon" class="form-control form-control-sm font-monospace" value="{{ old('icon', $category->icon) }}">
                    </div>
                    <div class="mb-3">
                      <label class="form-label small fw-semibold">Mô tả tóm tắt</label>
                      <textarea name="description" class="form-control form-control-sm" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="catActive_{{ $category->id }}" {{ $category->is_active ? 'checked' : '' }}>
                      <label class="form-check-label small" for="catActive_{{ $category->id }}">Đang hoạt động (Hiển thị trên website)</label>
                    </div>
                  </div>
                  <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-bee-primary btn-sm px-3">Lưu Cập Nhật</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL ADD CATEGORY -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-layer-group me-2 text-warning"></i> Thêm Danh Mục Thời Trang Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-sm" placeholder="Ví dụ: Áo Sơ Mi Nam Công Sở..." required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Icon FontAwesome</label>
            <input type="text" name="icon" class="form-control form-control-sm font-monospace" value="fa-solid fa-shirt" placeholder="fa-solid fa-shirt">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Mô tả tóm tắt</label>
            <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Mô tả chất liệu, phong cách của nhóm danh mục này..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-bee-primary btn-sm px-3">Tạo Danh Mục</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
