@extends('layouts.admin')

@section('title', 'Quản Lý Danh Mục Thời Trang | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">PHÂN LOẠI SẢN PHẨM</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Danh Mục Thời Trang</h2>
    </div>
    <p class="text-body-tertiary mb-0">Tổ chức, phân cấp và quản lý các nhóm sản phẩm thời trang trong hệ thống</p>
  </div>
  <div class="col-auto">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
      <i class="fa-solid fa-plus me-1"></i> Thêm Danh Mục Mới
    </button>
  </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Tổng Danh Mục</h6>
          <h3 class="text-body-emphasis mb-0 fw-bold">{{ count($categories) }}</h3>
        </div>
        <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-layer-group fs-8"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Đang Hoạt Động</h6>
          <h3 class="text-success mb-0 fw-bold">{{ $categories->where('is_active', true)->count() }}</h3>
        </div>
        <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-circle-check fs-8"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Sản Phẩm Đang Chứa</h6>
          <h3 class="text-info mb-0 fw-bold">{{ $categories->sum('products_count') }}</h3>
        </div>
        <div class="d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-shirt fs-8"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CATEGORIES TABLE CARD -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2" style="width: 60px;">ID</th>
            <th class="py-2">Tên Danh Mục</th>
            <th class="py-2">Danh Mục Cha</th>
            <th class="py-2">Đường Dẫn (Slug)</th>
            <th class="py-2 text-center" style="width: 90px;">Thứ Tự</th>
            <th class="py-2 text-center">Số Sản Phẩm</th>
            <th class="py-2">Mô Tả</th>
            <th class="py-2 text-center">Trạng Thái</th>
            <th class="text-end pe-3 py-2" style="width: 140px;">Hành Động</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($categories as $category)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <td class="ps-3 py-2">
                <span class="font-monospace fw-bold text-body-tertiary">#{{ $category->id }}</span>
              </td>
              <td class="py-2">
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="{{ $category->icon ?? 'fa-solid fa-shirt' }}"></i>
                  </div>
                  <strong class="text-body-emphasis fs-9">{{ $category->name }}</strong>
                </div>
              </td>
              <td class="py-2">
                @if($category->parent)
                  <span class="badge badge-phoenix badge-phoenix-info">
                    <i class="fa-solid fa-level-up-alt rotate-90 me-1"></i> {{ $category->parent->name }}
                  </span>
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary">Danh mục gốc</span>
                @endif
              </td>
              <td class="py-2">
                <code class="text-body-tertiary fs-10">{{ $category->slug }}</code>
              </td>
              <td class="py-2 text-center">
                <span class="badge badge-phoenix badge-phoenix-secondary">{{ $category->sort_order }}</span>
              </td>
              <td class="py-2 text-center">
                <span class="badge badge-phoenix badge-phoenix-warning">{{ $category->products_count }} SP</span>
              </td>
              <td class="py-2">
                <small class="text-body-tertiary text-truncate d-inline-block fs-10" style="max-width: 200px;">
                  {{ $category->description ?? 'Chưa có mô tả' }}
                </small>
              </td>
              <td class="py-2 text-center">
                <form action="{{ route('admin.categories.toggleStatus', $category->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-link p-0 border-0 text-decoration-none" title="Bấm để đổi trạng thái">
                    @if($category->is_active)
                      <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span>
                    @else
                      <span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-circle-pause me-1"></i> Tạm ẩn</span>
                    @endif
                  </button>
                </form>
              </td>
              <td class="text-end pe-3 py-2">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <a href="{{ route('client.products.index', ['category' => $category->slug]) }}" target="_blank" class="btn btn-sm btn-phoenix-secondary py-1 px-2 fs-10" title="Xem trên website">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <button type="button" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10 btn-edit-category"
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
                  <button type="button" class="btn btn-sm btn-phoenix-danger py-1 px-2 fs-10 btn-delete-category"
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
              <td colspan="9" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-folder-open fs-4 text-body-tertiary mb-2 d-block"></i>
                Chưa có danh mục thời trang nào. Hãy tạo danh mục đầu tiên!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- MODAL ADD CATEGORY -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="modal-title fw-bold text-body-emphasis" id="addCategoryModalLabel">
          <i class="fa-solid fa-layer-group me-2 text-primary"></i> Thêm Danh Mục Thời Trang Mới
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Áo Sơ Mi Nam, Quần Âu..." required>
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Danh mục cha</label>
            <select name="parent_id" class="form-select">
              <option value="">-- Danh mục gốc (Không có cha) --</option>
              @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label fs-9 fw-semibold">Icon FontAwesome</label>
              <input type="text" name="icon" class="form-control font-monospace" value="fa-solid fa-shirt" placeholder="fa-solid fa-shirt">
            </div>
            <div class="col-md-4">
              <label class="form-label fs-9 fw-semibold">Thứ tự hiển thị</label>
              <input type="number" name="sort_order" class="form-control" value="{{ count($categories) + 1 }}" min="0">
            </div>
          </div>

          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Mô tả tóm tắt</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Mô tả phong cách, chất liệu của nhóm sản phẩm này..."></textarea>
          </div>
        </div>

        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary btn-sm px-3">Tạo Danh Mục</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT CATEGORY -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="modal-title fw-bold text-body-emphasis" id="editCategoryModalLabel">
          <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Chỉnh Sửa Danh Mục Thời Trang
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="editCategoryForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" id="edit_name" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Danh mục cha</label>
            <select id="edit_parent_id" name="parent_id" class="form-select">
              <option value="">-- Danh mục gốc (Không có cha) --</option>
              @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label fs-9 fw-semibold">Icon FontAwesome</label>
              <input type="text" id="edit_icon" name="icon" class="form-control font-monospace">
            </div>
            <div class="col-md-4">
              <label class="form-label fs-9 fw-semibold">Thứ tự hiển thị</label>
              <input type="number" id="edit_sort_order" name="sort_order" class="form-control" min="0">
            </div>
          </div>

          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
              <label class="form-check-label fw-semibold fs-9 text-body-emphasis" for="edit_is_active">Bật trạng thái hoạt động</label>
            </div>
          </div>

          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Mô tả tóm tắt</label>
            <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
          </div>
        </div>

        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary btn-sm px-3">Cập Nhật Danh Mục</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL DELETE CATEGORY -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="modal-title fw-bold text-danger" id="deleteCategoryModalLabel">
          <i class="fa-solid fa-triangle-exclamation me-2"></i> Xóa Danh Mục
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="deleteCategoryForm" action="" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body p-4 text-center">
          <p class="mb-2 text-body-emphasis">Bạn có chắc chắn muốn xóa danh mục <strong id="delete_category_name" class="text-danger"></strong> không?</p>
          <div id="delete_warning" class="alert alert-warning fs-10 py-2 mb-0 d-none">
            <i class="fa-solid fa-exclamation-triangle me-1"></i> Danh mục này hiện có sản phẩm. Bạn sẽ không thể xóa được!
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis justify-content-center">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
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