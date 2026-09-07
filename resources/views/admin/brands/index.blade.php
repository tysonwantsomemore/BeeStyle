@extends('layouts.admin')

@section('title', 'Quản Lý Thương Hiệu Thời Trang | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">NHÃN HÀNG &amp; ĐỐI TÁC</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Thương Hiệu Thời Trang</h2>
    </div>
    <p class="text-body-tertiary mb-0">Quản lý danh sách các nhãn hàng, thương hiệu và đối tác sản phẩm trong hệ thống</p>
  </div>
  <div class="col-auto">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
      <i class="fa-solid fa-plus me-1"></i> Thêm Thương Hiệu Mới
    </button>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(isset($errors) && $errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
    <i class="fa-solid fa-circle-xmark me-2"></i> <strong>Đã xảy ra lỗi nhập liệu:</strong>
    <ul class="mb-0 mt-1 small ps-3">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Tổng Thương Hiệu</h6>
          <h3 class="text-body-emphasis mb-0 fw-bold">{{ count($brands) }}</h3>
        </div>
        <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-copyright fs-8"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Đang Hoạt Động</h6>
          <h3 class="text-success mb-0 fw-bold">{{ $brands->where('is_active', true)->count() }}</h3>
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
          <h6 class="text-body-tertiary text-uppercase mb-1 fw-semibold fs-10">Sản Phẩm Đã Liên Kết</h6>
          <h3 class="text-primary mb-0 fw-bold">{{ $brands->sum('products_count') }}</h3>
        </div>
        <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 44px; height: 44px;">
          <i class="fa-solid fa-shirt fs-8"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BRANDS TABLE CARD -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2" style="width: 60px;">ID</th>
            <th class="py-2">Thương Hiệu</th>
            <th class="py-2">Đường Dẫn (Slug)</th>
            <th class="py-2">Website</th>
            <th class="py-2 text-center" style="width: 90px;">Thứ Tự</th>
            <th class="py-2 text-center">Số Sản Phẩm</th>
            <th class="py-2">Mô Tả</th>
            <th class="py-2 text-center">Trạng Thái</th>
            <th class="text-end pe-3 py-2" style="width: 140px;">Hành Động</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($brands as $brand)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <td class="ps-3 py-2">
                <span class="font-monospace fw-bold text-body-tertiary">#{{ $brand->id }}</span>
              </td>
              <td class="py-2">
                <div class="d-flex align-items-center gap-2">
                  <div class="border border-translucent rounded p-1 d-flex align-items-center justify-content-center bg-body-emphasis" style="width: 40px; height: 40px; min-width: 40px;">
                    @if($brand->logo)
                      <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    @else
                      <i class="fa-solid fa-award text-body-tertiary fs-8"></i>
                    @endif
                  </div>
                  <div>
                    <strong class="text-body-emphasis d-block fs-9">{{ $brand->name }}</strong>
                    @if($brand->banner)
                      <small class="text-body-tertiary fs-10"><i class="fa-solid fa-image me-1"></i>Có banner</small>
                    @endif
                  </div>
                </div>
              </td>
              <td class="py-2">
                <code class="text-body-tertiary fs-10">{{ $brand->slug }}</code>
              </td>
              <td class="py-2">
                @if($brand->website)
                  <a href="{{ $brand->website }}" target="_blank" class="text-primary fs-10 text-decoration-none" title="{{ $brand->website }}">
                    <i class="fa-solid fa-globe me-1"></i> {{ parse_url($brand->website, PHP_URL_HOST) ?? $brand->website }}
                  </a>
                @else
                  <span class="text-body-tertiary fs-10">---</span>
                @endif
              </td>
              <td class="py-2 text-center">
                <span class="badge badge-phoenix badge-phoenix-secondary">{{ $brand->sort_order }}</span>
              </td>
              <td class="py-2 text-center">
                <span class="badge badge-phoenix badge-phoenix-warning">{{ $brand->products_count }} SP</span>
              </td>
              <td class="py-2">
                <small class="text-body-tertiary text-truncate d-inline-block fs-10" style="max-width: 220px;">
                  {{ $brand->description ?? 'Chưa có mô tả' }}
                </small>
              </td>
              <td class="py-2 text-center">
                <form action="{{ route('admin.brands.toggleStatus', $brand->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-link p-0 border-0 text-decoration-none" title="Bấm để đổi trạng thái">
                    @if($brand->is_active)
                      <span class="badge badge-phoenix badge-phoenix-success"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span>
                    @else
                      <span class="badge badge-phoenix badge-phoenix-secondary"><i class="fa-solid fa-circle-pause me-1"></i> Tạm ẩn</span>
                    @endif
                  </button>
                </form>
              </td>
              <td class="text-end pe-3 py-2">
                <div class="d-flex align-items-center justify-content-end gap-1">
                  <a href="{{ route('client.brands.show', $brand->slug) }}" target="_blank" class="btn btn-sm btn-phoenix-secondary py-1 px-2 fs-10" title="Xem trên website">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <button type="button" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10 btn-edit-brand"
                    data-id="{{ $brand->id }}"
                    data-name="{{ $brand->name }}"
                    data-website="{{ $brand->website }}"
                    data-sort="{{ $brand->sort_order }}"
                    data-active="{{ $brand->is_active ? '1' : '0' }}"
                    data-logo="{{ $brand->logo }}"
                    data-description="{{ $brand->description }}"
                    title="Chỉnh sửa thương hiệu">
                    <i class="fa-solid fa-pen-to-square"></i>
                  </button>
                  <button type="button" class="btn btn-sm btn-phoenix-danger py-1 px-2 fs-10 btn-delete-brand"
                    data-id="{{ $brand->id }}"
                    data-name="{{ $brand->name }}"
                    data-count="{{ $brand->products_count }}"
                    title="Xóa thương hiệu">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-award fs-4 text-body-tertiary mb-2 d-block"></i>
                Chưa có thương hiệu thời trang nào. Hãy tạo thương hiệu đầu tiên!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($brands->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $brands->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL ADD BRAND -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="modal-title fw-bold text-body-emphasis" id="addBrandModalLabel">
          <i class="fa-solid fa-copyright me-2 text-primary"></i> Thêm Thương Hiệu Mới
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Tên thương hiệu <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Gucci, Nike, BeeStyle Signature..." required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label fs-9 fw-semibold">Website chính thức</label>
              <input type="url" name="website" class="form-control" placeholder="https://example.com">
            </div>
            <div class="col-md-4">
              <label class="form-label fs-9 fw-semibold">Thứ tự hiển thị</label>
              <input type="number" name="sort_order" class="form-control" value="{{ count($brands) + 1 }}" min="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Ảnh Logo thương hiệu</label>
            <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
            <small class="text-body-tertiary fs-10 d-block mt-1">Hoặc nhập URL ảnh có sẵn:</small>
            <input type="text" name="logo_url" class="form-control form-control-sm mt-1" placeholder="https://example.com/logo.png">
          </div>
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Ảnh Banner thương hiệu (Tùy chọn)</label>
            <input type="file" name="banner" class="form-control form-control-sm" accept="image/*">
          </div>
          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Mô tả tóm tắt</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Giới thiệu đôi nét về lịch sử, phong cách của thương hiệu..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary btn-sm px-3">Tạo Thương Hiệu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT BRAND -->
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="modal-title fw-bold text-body-emphasis" id="editBrandModalLabel">
          <i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Chỉnh Sửa Thương Hiệu
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editBrandForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Tên thương hiệu <span class="text-danger">*</span></label>
            <input type="text" id="edit_name" name="name" class="form-control" required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label fs-9 fw-semibold">Website chính thức</label>
              <input type="url" id="edit_website" name="website" class="form-control">
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
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Đổi Logo thương hiệu mới</label>
            <div id="current_logo_preview" class="mb-2 d-none">
              <small class="text-body-tertiary fs-10 d-block mb-1">Logo hiện tại:</small>
              <img id="edit_logo_img" src="" alt="Logo" class="border border-translucent rounded p-1 bg-body-emphasis" style="max-height: 48px;">
            </div>
            <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
            <small class="text-body-tertiary fs-10 d-block mt-1">Hoặc nhập URL ảnh mới:</small>
            <input type="text" id="edit_logo_url" name="logo_url" class="form-control form-control-sm mt-1" placeholder="https://example.com/logo.png">
          </div>
          <div class="mb-3">
            <label class="form-label fs-9 fw-semibold">Đổi Banner thương hiệu mới</label>
            <input type="file" name="banner" class="form-control form-control-sm" accept="image/*">
          </div>
          <div class="mb-0">
            <label class="form-label fs-9 fw-semibold">Mô tả tóm tắt</label>
            <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer border-top border-translucent bg-body-emphasis">
          <button type="button" class="btn btn-phoenix-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary btn-sm px-3">Cập Nhật Thương Hiệu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL DELETE BRAND -->
<div class="modal fade" id="deleteBrandModal" tabindex="-1" aria-labelledby="deleteBrandModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header border-bottom border-translucent bg-body-emphasis">
        <h5 class="modal-title fw-bold text-danger" id="deleteBrandModalLabel">
          <i class="fa-solid fa-triangle-exclamation me-2"></i> Xóa Thương Hiệu
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="deleteBrandForm" action="" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body p-4 text-center">
          <p class="mb-2 text-body-emphasis">Bạn có chắc chắn muốn xóa thương hiệu <strong id="delete_brand_name" class="text-danger"></strong> không?</p>
          <div id="delete_warning" class="alert alert-warning fs-10 py-2 mb-0 d-none">
            <i class="fa-solid fa-exclamation-triangle me-1"></i> Thương hiệu này hiện có sản phẩm. Bạn sẽ không thể xóa được!
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
  const editModal = new bootstrap.Modal(document.getElementById('editBrandModal'));
  const editForm = document.getElementById('editBrandForm');
  const currentLogoPreview = document.getElementById('current_logo_preview');
  const editLogoImg = document.getElementById('edit_logo_img');
  const currentBannerPreview = document.getElementById('current_banner_preview');
  const editBannerImg = document.getElementById('edit_banner_img');

  document.querySelectorAll('.btn-edit-brand').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      const name = this.dataset.name;
      const website = this.dataset.website;
      const sort = this.dataset.sort;
      const active = this.dataset.active;
      const logo = this.dataset.logo;
      const banner = this.dataset.banner;
      const description = this.dataset.description;

      editForm.action = `/admin/brands/${id}`;
      document.getElementById('edit_name').value = name || '';
      document.getElementById('edit_website').value = website || '';
      document.getElementById('edit_sort_order').value = sort || '0';
      document.getElementById('edit_is_active').checked = (active === '1');
      document.getElementById('edit_description').value = description || '';
      document.getElementById('edit_logo_url').value = '';
      if (document.getElementById('edit_banner_url')) {
        document.getElementById('edit_banner_url').value = '';
      }

      if (logo) {
        editLogoImg.src = logo;
        currentLogoPreview.classList.remove('d-none');
      } else {
        currentLogoPreview.classList.add('d-none');
      }

      if (banner && editBannerImg) {
        editBannerImg.src = banner;
        currentBannerPreview.classList.remove('d-none');
      } else if (currentBannerPreview) {
        currentBannerPreview.classList.add('d-none');
      }

      editModal.show();
    });
  });

  const deleteModal = new bootstrap.Modal(document.getElementById('deleteBrandModal'));
  const deleteForm = document.getElementById('deleteBrandForm');
  const deleteNameSpan = document.getElementById('delete_brand_name');
  const deleteWarning = document.getElementById('delete_warning');
  const btnConfirmDelete = document.getElementById('btnConfirmDelete');

  document.querySelectorAll('.btn-delete-brand').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.dataset.id;
      const name = this.dataset.name;
      const count = parseInt(this.dataset.count || '0');

      deleteForm.action = `/admin/brands/${id}`;
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
