<script>
  let currentModalBuyersData = [];

  function openSalesBuyersModal(productId, productName) {
    const modalEl = document.getElementById('salesBuyersModal');
    if (!modalEl) return;

    const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    bsModal.show();

    // Reset UI
    document.getElementById('modalLoadingSpinner').style.display = 'block';
    document.getElementById('modalMainContent').style.display = 'none';
    document.getElementById('modalSearchInput').value = '';
    document.getElementById('modalStatusFilter').value = '';

    document.getElementById('salesBuyersModalLabel').innerHTML = `<i class="fa-solid fa-users-viewfinder text-primary me-2"></i> Lịch Sử Bán Hàng: ${productName || 'Sản phẩm'}`;

    // Fetch API endpoint
    fetch(`/admin/products/${productId}/sales-buyers`)
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          alert('Không thể tải thông tin bán hàng của sản phẩm.');
          bsModal.hide();
          return;
        }

        const p = data.product;
        currentModalBuyersData = data.orders || [];

        // Populate product summary card
        document.getElementById('modalProductName').textContent = p.name;
        document.getElementById('modalProductName').title = p.name;
        document.getElementById('modalProductImg').src = p.image;
        document.getElementById('modalProductPrice').textContent = p.price_formatted;
        document.getElementById('modalCategoryBadge').textContent = p.category_name;
        document.getElementById('modalSkuBadge').textContent = 'SKU: ' + p.sku;
        document.getElementById('modalStockBadge').textContent = 'Kho: ' + p.stock + ' cái';
        document.getElementById('modalStockBadge').className = `badge ${p.stock <= 5 ? 'bg-danger-subtle text-danger border-danger-subtle' : 'bg-success-subtle text-success border-success-subtle'} border fw-bold fs-11`;

        document.getElementById('modalTotalSoldQty').textContent = (p.total_sold_qty || 0).toLocaleString('vi-VN');
        document.getElementById('modalTotalRevenue').textContent = p.total_revenue_formatted;
        document.getElementById('modalOrdersCount').textContent = (p.orders_count || 0).toLocaleString('vi-VN');
        document.getElementById('modalBuyersCount').textContent = (p.buyers_count || 0).toLocaleString('vi-VN');

        document.getElementById('modalProductEditLink').href = p.edit_url;

        // Render table
        renderModalBuyersRows(currentModalBuyersData);

        // Hide spinner & show content
        document.getElementById('modalLoadingSpinner').style.display = 'none';
        document.getElementById('modalMainContent').style.display = 'block';
      })
      .catch(err => {
        console.error('Lỗi khi tải dữ liệu người mua:', err);
        alert('Đã xảy ra lỗi kết nối khi tải danh sách người mua.');
        bsModal.hide();
      });
  }

  function renderModalBuyersRows(buyers) {
    const tbody = document.getElementById('modalBuyersTableBody');
    const emptyState = document.getElementById('modalEmptyState');
    const countBadge = document.getElementById('modalOrderRowsCount');

    tbody.innerHTML = '';
    countBadge.textContent = `${buyers.length} lượt mua`;

    if (!buyers || buyers.length === 0) {
      emptyState.classList.remove('d-none');
      return;
    }

    emptyState.classList.add('d-none');

    buyers.forEach((b, index) => {
      const tr = document.createElement('tr');
      tr.className = 'hover-actions-trigger border-bottom border-translucent';

      tr.innerHTML = `
        <td class="ps-3 py-2.5 text-center fw-bold text-muted fs-10">${index + 1}</td>
        <td class="py-2.5">
          <a href="${b.order_url}" target="_blank" class="font-monospace fw-bolder text-primary text-decoration-none fs-9 d-inline-flex align-items-center gap-1">
            <span>#${b.order_code}</span>
            <i class="fa-solid fa-arrow-up-right-from-square fs-11 opacity-50"></i>
          </a>
        </td>
        <td class="py-2.5">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-10" style="width: 32px; height: 32px; flex-shrink: 0;">
              ${b.customer_name ? b.customer_name.charAt(0).toUpperCase() : 'K'}
            </div>
            <div>
              <div class="fw-bold text-dark fs-9 d-flex align-items-center gap-1.5">
                <span>${b.customer_name}</span>
                ${b.is_registered ? '<span class="badge bg-info-subtle text-info border border-info-subtle fs-11 fw-bold">Thành viên</span>' : '<span class="badge bg-secondary-subtle text-muted border border-secondary-subtle fs-11">Khách lẻ</span>'}
              </div>
              <div class="d-flex align-items-center gap-2 text-muted fs-11">
                <span><i class="fa-solid fa-phone me-1 text-secondary"></i>${b.customer_phone}</span>
                ${b.customer_email ? `<span><i class="fa-regular fa-envelope me-1 text-secondary"></i>${b.customer_email}</span>` : ''}
              </div>
            </div>
          </div>
        </td>
        <td class="py-2.5">
          <div class="d-flex align-items-center gap-1.5 flex-wrap">
            <span class="badge bg-light text-dark border border-secondary-subtle fw-semibold fs-11"><i class="fa-solid fa-palette me-1 text-secondary"></i>${b.color}</span>
            <span class="badge bg-light text-dark border border-secondary-subtle fw-bold fs-11 font-monospace"><i class="fa-solid fa-maximize me-1 text-secondary"></i>${b.size}</span>
          </div>
        </td>
        <td class="py-2.5 text-center">
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-black font-monospace fs-9 px-2.5 py-1">
            x${b.quantity} cái
          </span>
        </td>
        <td class="py-2.5 text-end">
          <strong class="text-danger fw-bolder font-monospace fs-9">${b.subtotal_formatted}</strong>
          <div class="text-muted fs-11">(${b.price_formatted}/cái)</div>
        </td>
        <td class="py-2.5 text-center">
          <span class="badge ${b.status_badge_class} border fw-bold fs-10 px-2 py-1">
            ${b.status_label}
          </span>
        </td>
        <td class="py-2.5">
          <div class="text-dark fw-bold fs-10 font-monospace">${b.created_at}</div>
          <small class="text-muted fs-11">${b.created_at_human}</small>
        </td>
        <td class="pe-3 py-2.5 text-end">
          <a href="${b.order_url}" target="_blank" class="btn btn-xs btn-outline-primary fw-bold fs-11 px-2.5 py-1 shadow-xs">
            Xem đơn
          </a>
        </td>
      `;
      tbody.appendChild(tr);
    });
  }

  function filterModalBuyersTable() {
    const query = (document.getElementById('modalSearchInput').value || '').toLowerCase().trim();
    const status = (document.getElementById('modalStatusFilter').value || '').trim();

    const filtered = currentModalBuyersData.filter(b => {
      const matchQuery = !query || 
        (b.customer_name && b.customer_name.toLowerCase().includes(query)) ||
        (b.customer_phone && b.customer_phone.toLowerCase().includes(query)) ||
        (b.customer_email && b.customer_email.toLowerCase().includes(query)) ||
        (b.order_code && b.order_code.toLowerCase().includes(query));

      const matchStatus = !status || b.shipping_status === status;

      return matchQuery && matchStatus;
    });

    renderModalBuyersRows(filtered);
  }
</script>
