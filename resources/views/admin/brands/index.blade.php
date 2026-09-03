@extends('layouts.admin')

@section('title', 'Quản Lý Thương Hiệu Thời Trang | BeeStyle Admin')

@section('content')
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h3 class="fw-bold text-dark mb-1">Thương Hiệu Thời Trang</h3>
      <p class="text-muted small mb-0">Quản lý danh sách các nhãn hàng, thương hiệu và đối tác sản phẩm trong hệ thống</p>
    </div>
    <button type="button" class="btn btn-bee-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBrandModal">
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

@if($errors->any())
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
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-copyright fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Tổng Thương Hiệu</div>
          <div class="fs-4 fw-bold text-dark">{{ $totalBrands ?? count($brands) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-circle-check fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Đang Hoạt Động</div>
          <div class="fs-4 fw-bold text-success">{{ $activeBrandsCount ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-3 p-3">
      <div class="d-flex align-items-center">
        <div class="avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
          <i class="fa-solid fa-shirt fs-4"></i>
        </div>
        <div>
          <div class="text-muted small fw-semibold">Sản Phẩm Đã Liên Kết</div>
          <div class="fs-4 fw-bold text-primary">{{ $totalLinkedProducts ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="bee-table-card">
  <!-- FILTER TOOLBAR -->
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <form action="{{ route('admin.brands.index') }}" method="GET" class="d-flex align-items-center gap-2 flex-wrap">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Tìm tên hoặc mô tả thương hiệu..." style="width: 260px;">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 170px;">
        <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Tất cả trạng thái</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tạm ẩn</option>
      </select>
      <button type="submit" class="btn btn-sm btn-outline-secondary">Lọc</button>
      @if(request('q') || (request('status') && request('status') !== 'all'))
        <a href="{{ route('admin.brands.index') }}" class="btn btn-sm btn-link text-danger p-0 ms-1">Xóa lọc</a>
      @endif
    </form>
    <div class="text-muted small">
      Hiển thị: <strong>{{ $brands->count() }}</strong> / <strong>{{ $brands->total() }}</strong> thương hiệu
    </div>
  </div>
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th style="width: 60px;">ID</th>
          <th>Thương Hiệu</th>
          <th>Đường Dẫn (Slug)</th>
          <th>Website</th>
          <th class="text-center" style="width: 90px;">Thứ Tự</th>
          <th class="text-center">Số Sản Phẩm</th>
          <th>Mô Tả</th>
          <th class="text-center">Trạng Thái</th>
          <th class="text-end" style="width: 140px;">Hành Động</th>
        </tr>
      </thead>
      <tbody>
        @forelse($brands as $brand)
          <tr>
            <td><strong>#{{ $brand->id }}</strong></td>
            <td>
              <div class="d-flex align-items-center gap-3">
                <div class="avatar border rounded p-1 d-flex align-items-center justify-content-center bg-white" style="width: 48px; height: 48px; min-width: 48px;">
                  <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <div>
                  <strong class="text-dark d-block mb-0 fs-6">{{ $brand->name }}</strong>
                  @if($brand->has_banner)
                    <small class="text-muted"><i class="fa-solid fa-image me-1"></i>Có ảnh Banner</small>
                  @endif
                </div>
              </div>
            </td>
            <td><code class="text-muted small">{{ $brand->slug }}</code></td>
            <td>
              @if($brand->website)
                <a href="{{ $brand->website }}" target="_blank" class="text-primary small text-decoration-none" title="{{ $brand->website }}">
                  <i class="fa-solid fa-globe me-1"></i> {{ parse_url($brand->website, PHP_URL_HOST) ?? $brand->website }}
                </a>
              @else
                <span class="text-muted small">---</span>
              @endif
            </td>
            <td class="text-center"><span class="badge bg-light text-dark border">{{ $brand->sort_order }}</span></td>
            <td class="text-center"><span class="badge bg-warning-subtle text-dark border fw-bold">{{ $brand->products_count }} SP</span></td>
            <td>
              <small class="text-muted text-truncate d-inline-block" style="max-width: 220px;">
                {{ $brand->description ?? 'Chưa có mô tả' }}
              </small>
            </td>
            <td class="text-center">
              <form action="{{ route('admin.brands.toggleStatus', $brand->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-link p-0 border-0 text-decoration-none" title="Bấm để đổi trạng thái">
                  @if($brand->is_active)
                    <span class="badge bg-success-subtle text-success fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Hoạt động</span>
                  @else
                    <span class="badge bg-secondary-subtle text-secondary fw-bold"><i class="fa-solid fa-circle-pause me-1"></i> Tạm ẩn</span>
                  @endif
                </button>
              </form>
            </td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('client.brands.show', $brand->slug) }}" target="_blank" class="btn btn-outline-secondary" title="Xem trên website">
                  <i class="fa-solid fa-eye"></i>
                </a>
                <button type="button" class="btn btn-outline-primary btn-edit-brand"
                  data-id="{{ $brand->id }}"
                  data-name="{{ $brand->name }}"
                  data-website="{{ $brand->website }}"
                  data-sort="{{ $brand->sort_order }}"
                  data-active="{{ $brand->is_active ? '1' : '0' }}"
                  data-logo="{{ $brand->logo_url }}"
                  data-banner="{{ $brand->banner_url }}"
                  data-description="{{ $brand->description }}"
                  title="Chỉnh sửa thương hiệu">
                  <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button type="button" class="btn btn-outline-danger btn-delete-brand"
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
            <td colspan="9" class="text-center py-4 text-muted">
              <i class="fa-solid fa-award fs-1 mb-2 d-block text-warning"></i>
              Chưa có thương hiệu thời trang nào. Hãy tạo thương hiệu đầu tiên!
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($brands->hasPages())
    <div class="card-footer d-flex justify-content-center py-3">
      {{ $brands->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

<!-- MODAL ADD BRAND -->
<div class="modal fade" id="addBrandModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-copyright me-2 text-warning"></i> Thêm Thương Hiệu Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tên thương hiệu <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Gucci, Nike, BeeStyle Signature..." required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label small fw-semibold">Website chính thức</label>
              <input type="url" name="website" class="form-control" placeholder="https://example.com">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold">Thứ tự hiển thị</label>
              <input type="number" name="sort_order" class="form-control" value="{{ count($brands) + 1 }}" min="0">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Ảnh Logo thương hiệu</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
            <small class="text-muted d-block mt-1">Hoặc nhập URL ảnh có sẵn:</small>
            <input type="text" name="logo_url" class="form-control form-control-sm mt-1" placeholder="https://example.com/logo.png">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Ảnh Banner thương hiệu (Tùy chọn)</label>
            <input type="file" name="banner" class="form-control" accept="image/*">
            <small class="text-muted d-block mt-1">Hoặc nhập URL ảnh Banner có sẵn:</small>
            <input type="text" name="banner_url" class="form-control form-control-sm mt-1" placeholder="https://example.com/banner.png">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Mô tả tóm tắt</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Giới thiệu đôi nét về lịch sử, phong cách của thương hiệu..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-bee-primary btn-sm px-3">Tạo Thương Hiệu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT BRAND -->
<div class="modal fade" id="editBrandModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Chỉnh Sửa Thương Hiệu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="editBrandForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tên thương hiệu <span class="text-danger">*</span></label>
            <input type="text" id="edit_name" name="name" class="form-control" required>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="form-label small fw-semibold">Website chính thức</label>
              <input type="url" id="edit_website" name="website" class="form-control">
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
            <label class="form-label small fw-semibold">Đổi Logo thương hiệu mới</label>
            <div id="current_logo_preview" class="mb-2 d-none">
              <small class="text-muted d-block mb-1">Logo hiện tại:</small>
              <img id="edit_logo_img" src="" alt="Logo" class="border rounded p-1" style="max-height: 50px;">
            </div>
            <input type="file" name="logo" class="form-control" accept="image/*">
            <small class="text-muted d-block mt-1">Hoặc nhập URL ảnh mới:</small>
            <input type="text" id="edit_logo_url" name="logo_url" class="form-control form-control-sm mt-1" placeholder="https://example.com/logo.png">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Đổi Banner thương hiệu mới</label>
            <div id="current_banner_preview" class="mb-2 d-none">
              <small class="text-muted d-block mb-1">Banner hiện tại:</small>
              <img id="edit_banner_img" src="" alt="Banner" class="border rounded p-1" style="max-height: 60px; max-width: 100%; object-fit: cover;">
            </div>
            <input type="file" name="banner" class="form-control" accept="image/*">
            <small class="text-muted d-block mt-1">Hoặc nhập URL ảnh Banner mới:</small>
            <input type="text" id="edit_banner_url" name="banner_url" class="form-control form-control-sm mt-1" placeholder="https://example.com/banner.png">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Mô tả tóm tắt</label>
            <textarea id="edit_description" name="description" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary btn-sm px-3">Cập Nhật Thương Hiệu</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL DELETE BRAND -->
<div class="modal fade" id="deleteBrandModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i> Xóa Thương Hiệu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="deleteBrandForm" action="" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body p-4 text-center">
          <p class="mb-2">Bạn có chắc chắn muốn xóa thương hiệu <strong id="delete_brand_name" class="text-dark"></strong> không?</p>
          <div id="delete_warning" class="alert alert-warning small py-2 mb-0 d-none">
            <i class="fa-solid fa-exclamation-triangle me-1"></i> Thương hiệu này hiện có sản phẩm. Bạn sẽ không thể xóa được!
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
  // Edit Brand Modal handling
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

  // Delete Brand Modal handling
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
