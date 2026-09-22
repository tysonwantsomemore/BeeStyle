<!-- MODAL CHI TIẾT SẢN LƯỢNG BÁN & DANH SÁCH KHÁCH HÀNG MUA HÀNG -->
<div class="modal fade" id="salesBuyersModal" tabindex="-1" aria-labelledby="salesBuyersModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      
      <!-- MODAL HEADER -->
      <div class="modal-header bg-light border-bottom border-translucent py-3 px-4">
        <div class="d-flex align-items-center gap-2.5">
          <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 42px; height: 42px;">
            <i class="fa-solid fa-users-viewfinder fs-7"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-dark mb-0 fs-8" id="salesBuyersModalLabel">Lịch Sử Bán Hàng &amp; Danh Sách Khách Mua</h5>
            <small class="text-muted fw-medium" id="modalProductSubtitle">Chi tiết từng đơn hàng, số lượng mua và thông tin người nhận</small>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- MODAL BODY -->
      <div class="modal-body p-4 bg-light-subtle">
        
        <!-- LOADING SPINNER -->
        <div id="modalLoadingSpinner" class="text-center py-5">
          <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Đang tải...</span>
          </div>
          <p class="text-muted mt-3 mb-0 fw-semibold">Đang truy xuất thông tin đơn hàng và khách mua từ cơ sở dữ liệu...</p>
        </div>

        <!-- MODAL MAIN CONTENT (Hidden while loading) -->
        <div id="modalMainContent" style="display: none;">
          
          <!-- PRODUCT SUMMARY CARD -->
          <div class="card border-0 shadow-sm mb-4 bg-white" style="border-radius: 14px;">
            <div class="card-body p-3">
              <div class="row align-items-center gy-3">
                <div class="col-md-auto text-center text-md-start">
                  <img id="modalProductImg" src="" alt="Product" class="rounded-3 border border-translucent bg-light shadow-xs" style="width: 76px; height: 76px; object-fit: contain;">
                </div>
                <div class="col-md">
                  <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold fs-11" id="modalCategoryBadge">Danh mục</span>
                    <span class="badge bg-secondary-subtle text-dark border border-secondary-subtle font-monospace fw-bold fs-11" id="modalSkuBadge">SKU</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold fs-11" id="modalStockBadge">Tồn kho</span>
                  </div>
                  <h5 class="fw-bolder text-dark mb-1 fs-8 text-truncate" style="max-width: 500px;" id="modalProductName">Tên sản phẩm</h5>
                  <div class="d-flex align-items-center gap-2">
                    <span class="text-muted fs-10">Giá niêm yết:</span>
                    <strong class="text-danger fw-black font-monospace fs-8" id="modalProductPrice">0₫</strong>
                  </div>
                </div>
                
                <!-- 4 KPI BLOCKS IN MODAL -->
                <div class="col-md-auto">
                  <div class="d-flex gap-2 flex-wrap justify-content-md-end">
                    <div class="bg-light p-2.5 rounded-3 border border-translucent text-center" style="min-width: 105px;">
                      <span class="text-secondary fs-11 fw-bold text-uppercase d-block mb-1">Tổng Đã Bán</span>
                      <h4 class="fw-black text-success mb-0 font-monospace fs-7" id="modalTotalSoldQty">0</h4>
                      <small class="text-muted fs-11">sản phẩm</small>
                    </div>
                    <div class="bg-light p-2.5 rounded-3 border border-translucent text-center" style="min-width: 125px;">
                      <span class="text-secondary fs-11 fw-bold text-uppercase d-block mb-1">Doanh Thu Thuần</span>
                      <h4 class="fw-black text-primary mb-0 font-monospace fs-7" id="modalTotalRevenue">0₫</h4>
                      <small class="text-muted fs-11">thực tế</small>
                    </div>
                    <div class="bg-light p-2.5 rounded-3 border border-translucent text-center" style="min-width: 95px;">
                      <span class="text-secondary fs-11 fw-bold text-uppercase d-block mb-1">Số Đơn Hàng</span>
                      <h4 class="fw-black text-dark mb-0 font-monospace fs-7" id="modalOrdersCount">0</h4>
                      <small class="text-muted fs-11">lượt đặt</small>
                    </div>
                    <div class="bg-light p-2.5 rounded-3 border border-translucent text-center" style="min-width: 95px;">
                      <span class="text-secondary fs-11 fw-bold text-uppercase d-block mb-1">Người Mua</span>
                      <h4 class="fw-black text-info mb-0 font-monospace fs-7" id="modalBuyersCount">0</h4>
                      <small class="text-muted fs-11">khách hàng</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- CONTROLS & SEARCH BAR -->
          <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
              <span class="fw-bold text-dark fs-9"><i class="fa-solid fa-list-check me-1 text-primary"></i> Chi Tiết Khách Hàng Đã Mua:</span>
              <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold font-monospace" id="modalOrderRowsCount">0 lượt mua</span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <div class="input-group input-group-sm" style="width: 260px;">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" id="modalSearchInput" class="form-control border-start-0 ps-0" placeholder="Lọc theo tên, SĐT, mã đơn..." oninput="filterModalBuyersTable()">
              </div>
              <select id="modalStatusFilter" class="form-select form-select-sm" style="width: 150px;" onchange="filterModalBuyersTable()">
                <option value="">Tất cả trạng thái</option>
                <option value="completed">Hoàn tất</option>
                <option value="delivered">Đã giao hàng</option>
                <option value="shipping">Đang giao hàng</option>
                <option value="processing">Đang chuẩn bị</option>
                <option value="cancelled">Đã hủy</option>
              </select>
            </div>
          </div>

          <!-- BUYERS TABLE -->
          <div class="card border-0 shadow-sm bg-white" style="border-radius: 14px;">
            <div class="table-responsive scrollbar" style="max-height: 420px;">
              <table class="table table-hover table-sm fs-9 mb-0 align-middle">
                <thead class="sticky-top bg-light shadow-xs" style="z-index: 10;">
                  <tr class="border-bottom border-translucent">
                    <th class="ps-3 py-2.5 text-center" style="width: 45px; color: #0f172a !important; font-weight: 800;">STT</th>
                    <th class="py-2.5" style="width: 140px; color: #0f172a !important; font-weight: 800;">Mã Đơn Hàng</th>
                    <th class="py-2.5" style="color: #0f172a !important; font-weight: 800;">Khách Hàng Mua</th>
                    <th class="py-2.5" style="color: #0f172a !important; font-weight: 800;">Phân Loại Mua</th>
                    <th class="py-2.5 text-center" style="width: 110px; color: #0f172a !important; font-weight: 800;">Số Lượng</th>
                    <th class="py-2.5 text-end" style="width: 120px; color: #0f172a !important; font-weight: 800;">Thành Tiền</th>
                    <th class="py-2.5 text-center" style="width: 130px; color: #0f172a !important; font-weight: 800;">Trạng Thái</th>
                    <th class="py-2.5" style="width: 140px; color: #0f172a !important; font-weight: 800;">Thời Gian Đặt</th>
                    <th class="pe-3 py-2.5 text-end" style="width: 90px; color: #0f172a !important; font-weight: 800;">Thao Tác</th>
                  </tr>
                </thead>
                <tbody id="modalBuyersTableBody">
                  <!-- Dynamic JS rows -->
                </tbody>
              </table>
            </div>

            <!-- EMPTY RESULTS -->
            <div id="modalEmptyState" class="text-center py-5 d-none">
              <div class="mb-3 text-secondary opacity-50">
                <i class="fa-solid fa-box-open" style="font-size: 3rem;"></i>
              </div>
              <h6 class="text-dark fw-bold mb-1">Chưa tìm thấy dữ liệu mua hàng</h6>
              <p class="text-muted fs-9 mb-0">Chưa có khách hàng nào khớp với điều kiện lọc hoặc sản phẩm này chưa phát sinh đơn.</p>
            </div>
          </div>

        </div>
      </div>

      <!-- MODAL FOOTER -->
      <div class="modal-footer bg-light border-top border-translucent py-2 px-4 d-flex justify-content-between">
        <a id="modalProductEditLink" href="#" target="_blank" class="btn btn-sm btn-outline-secondary fw-bold px-3">
          <i class="fa-regular fa-pen-to-square me-1.5"></i> Chỉnh Sửa Sản Phẩm Này
        </a>
        <button type="button" class="btn btn-sm btn-secondary fw-bold px-4" data-bs-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>
