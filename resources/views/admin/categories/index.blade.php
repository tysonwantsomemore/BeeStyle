@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục Thời Trang | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Danh Mục Thời Trang</h3>
      <p class="text-muted small mb-0">Tổ chức, phân cấp và quản lý các nhóm sản phẩm thời trang trong hệ thống</p>
    </div>
    <button type="button" class="btn btn-bee-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
      <i class="fa-solid fa-plus me-1"></i> Thêm Danh Mục Mới
    </button>
  </div>
</div>

<div class="bee-table-card">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th style="width: 60px;">ID</th>
          <th>Tên Danh Mục</th>
          <th>Danh Mục Cha</th>
          <th>Đường Dẫn (Slug)</th>
          <th class="text-center" style="width: 90px;">Thứ Tự</th>
          <th class="text-center">Số Sản Phẩm</th>
          <th>Mô Tả</th>
          <th class="text-center">Trạng Thái</th>
          <th class="text-end" style="width: 140px;">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
          <tr>
            <td><strong>#{{ $category->id }}</strong></td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="avatar bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                  <i class="{{ $category->icon ?? 'fa-solid fa-shirt' }}"></i>
                </div>
                <div>
                  <strong class="text-dark d-block">{{ $category->name }}</strong>
                </div>
              </div>
            </td>
            <td>
              @if($category->parent)
                <span class="badge bg-info-subtle text-info border"><i class="fa-solid fa-level-up-alt rotate-90 me-1"></i> {{ $category->parent->name }}</span>
              @else
                <span class="badge bg-light text-muted border">Danh mục gốc</span>
              @endif
            </td>
            <td><code class="text-muted small">{{ $category->slug }}</code></td>
            <td class="text-center"><span class="badge bg-light text-dark border">{{ $category->sort_order }}</span></td>
            <td class="text-center"><span class="badge bg-warning-subtle text-dark border fw-bold">{{ $category->products_count }} SP</span></td>
            <td>
              <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;">
                {{ $category->description ?? 'Chưa có mô tả' }}
              </small>
            </td>
            <td class="text-center">
              <form action="{{ route('admin.categories.toggleStatus', $category->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-link p-0 border-0 text-decoration-none" title="Bấm để đổi trạng thái">
                  @if($category->is_active)
                    <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span>
                  @else
                    <span class="badge bg-secondary-subtle text-secondary fw-bold"><i class="fa-solid fa-circle-pause me-1"></i> Tạm ẩn</span>
                  @endif
                </button>
              </form>
            </td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('client.products.index', ['category' => $category->slug]) }}" target="_blank" class="btn btn-outline-secondary" title="Xem trên website">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <button type="button" class="btn btn-outline-primary btn-edit-category"
                  data-id="{{ $category->id }}"
                  data-name="{{ $category->name }}"
                  data-parent="{{ $category->parent_id }}"
                  data-icon="{{ $category->icon }}"
                  data-sort="{{ $category->sort_order }}"
                  data-active="{{ $category->is_active ? '1' : '0' }}"
                  data-description="{{ $category->description }}"
                  title="Chỉnh sửa danh mục">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-delete-category"
                  data-id="{{ $category->id }}"
                  data-name="{{ $category->name }}"
                  data-count="{{ $category->products_count }}"
                  title="Xóa danh mục">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center py-4 text-muted">
              <i class="fa-solid fa-folder-open fs-2 mb-2 d-block text-warning"></i>
              Chưa có danh mục thời trang nào. Hãy tạo danh mục đầu tiên!
            </td>
          </tr>
        @endforelse
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
            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Áo Sơ Mi Nam, Quần Âu..." required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Danh mục cha</label>
            <select name="parent_id" class="form-select">
              <option value="">-- Danh mục gốc (Không có cha) --</option>
              @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label small fw-semibold">Icon FontAwesome</label>
              <input type="text" name="icon" class="form-control font-monospace" value="fa-solid fa-shirt" placeholder="fa-solid fa-shirt">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Thứ tự hiển thị</label>
              <input type="number" name="sort_order" class="form-control" value="{{ count($categories) + 1 }}" min="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Mô tả tóm tắt</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Mô tả phong cách, chất liệu của nhóm sản phẩm này..."></textarea>
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

<!-- MODAL EDIT CATEGORY -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Chỉnh Sửa Danh Mục Thời Trang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editCategoryForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" id="edit_name" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Danh mục cha</label>
            <select id="edit_parent_id" name="parent_id" class="form-select">
              <option value="">-- Danh mục gốc (Không có cha) --</option>
              @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label small fw-semibold">Icon FontAwesome</label>
              <input type="text" id="edit_icon" name="icon" class="form-control font-monospace">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Thứ tự hiển thị</label>
              <input type="number" id="edit_sort_order" name="sort_order" class="form-control" min="0">
            </div>
          </div>
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
              <label class="form-check-label fw-semibold small" for="edit_is_active">Bật trạng thái hoạt động</label>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Mô tả tóm tắt</label>
            <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary btn-sm px-3">Cập Nhật Danh Mục</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL DELETE CATEGORY -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Xóa Danh Mục</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="deleteCategoryForm" action="" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body p-4 text-center">
          <p class="mb-2">Bạn có chắc chắn muốn xóa danh mục <strong id="delete_category_name" class="text-dark"></strong> không?</p>
          <div id="delete_warning" class="alert alert-warning small py-2 mb-0 d-none">
            <i class="fa-solid fa-exclamation-triangle me-1"></i> Danh mục này hiện có sản phẩm. Bạn sẽ không thể xóa được!
          </div>
        </div>
        <div class="modal-footer border-top justify-content-center">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" id="btnConfirmDelete" class="btn btn-danger btn-sm px-3">Xác Nhận Xóa</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Edit Category Modal handling
  const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
  const editForm = document.getElementById('editCategoryForm');

  document.querySelectorAll('.btn-edit-category').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      const name = this.dataset.name;
      const parent = this.dataset.parent;
      const icon = this.dataset.icon;
      const sort = this.dataset.sort;
      const active = this.dataset.active;
      const description = this.dataset.description;

      editForm.action = `/admin/categories/${id}`;
      document.getElementById('edit_name').value = name || '';
      document.getElementById('edit_parent_id').value = parent || '';
      document.getElementById('edit_icon').value = icon || 'fa-solid fa-shirt';
      document.getElementById('edit_sort_order').value = sort || '0';
      document.getElementById('edit_is_active').checked = (active === '1');
      document.getElementById('edit_description').value = description || '';

      editModal.show();
    });
  });

  // Delete Category Modal handling
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
  const deleteForm = document.getElementById('deleteCategoryForm');
  const deleteNameSpan = document.getElementById('delete_category_name');
  const deleteWarning = document.getElementById('delete_warning');
  const btnConfirmDelete = document.getElementById('btnConfirmDelete');

  document.querySelectorAll('.btn-delete-category').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      const name = this.dataset.name;
      const count = parseInt(this.dataset.count || '0');

      deleteForm.action = `/admin/categories/${id}`;
      deleteNameSpan.textContent = name;

      if (count > 0) {
        deleteWarning.classList.remove('d-none');
        btnConfirmDelete.disabled = true;
      } else {
        deleteWarning.classList.add('d-none');
        btnConfirmDelete.disabled = false;
      }

      deleteModal.show();
    });
  });
});
</script>
@endpush
