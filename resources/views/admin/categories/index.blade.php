@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục Thời Trang | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Danh Mục Thời Trang</h3>
      <p class="text-muted small mb-0">Tổ chức và phân loại các nhóm sản phẩm thời trang trong hệ thống</p>
    </div>
    <button type="button" class="btn btn-bee-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
      <i class="fa-solid fa-plus me-1"></i> Thêm Danh Mục Mới
    </button>
  </div>
</div>

<div class="bee-table-card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>ID</th>
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
            <td><strong>#{{ $category['id'] }}</strong></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-m bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center">
                  <i class="{{ $category['icon'] }}"></i>
                </div>
                <strong class="text-dark">{{ $category['name'] }}</strong>
              </div>
            </td>
            <td><code class="text-muted small">{{ $category['slug'] }}</code></td>
            <td><i class="{{ $category['icon'] }} text-warning fs-5"></i></td>
            <td><span class="badge bg-light text-dark border">{{ $category['item_count'] }} SP</span></td>
            <td><small class="text-muted">{{ $category['description'] }}</small></td>
            <td><span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Đang hoạt động</span></td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-warning text-dark" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></button>
                <button type="button" class="btn btn-outline-danger" title="Xóa"><i class="fa-solid fa-trash"></i></button>
              </div>
            </td>
          </tr>
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
            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Áo Thun & Phụ Kiện..." required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Icon FontAwesome</label>
            <input type="text" name="icon" class="form-control font-monospace" placeholder="fa-solid fa-shirt">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Mô tả tóm tắt</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Mô tả nhóm danh mục này..."></textarea>
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
