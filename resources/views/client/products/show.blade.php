@push('scripts')
<script>
  // PRO E-COMMERCE GALLERY STATE
  const galleryImages = @json($allGalleryImages->map(fn($img) => asset($img)));
  let currentImgIndex = 0;
  let modalScale = 1;

  function setGalleryIndex(index) {
    if (index < 0) index = galleryImages.length - 1;
    if (index >= galleryImages.length) index = 0;
    currentImgIndex = index;

    const targetSrc = galleryImages[currentImgIndex];

    // Update main image
    const mainImg = document.getElementById('mainProductImg');
    if (mainImg) {
      mainImg.style.opacity = '0.4';
      setTimeout(() => {
        mainImg.src = targetSrc;
        mainImg.style.opacity = '1';
      }, 100);
    }

    // Update thumbnails
    document.querySelectorAll('.thumb-item').forEach((item, idx) => {
      if (idx === currentImgIndex) {
        item.className = 'border rounded-3 p-1 bg-white flex-shrink-0 cursor-pointer thumb-item transition-all border-warning border-2 shadow-sm ring-1 ring-warning';
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      } else {
        item.className = 'border rounded-3 p-1 bg-white flex-shrink-0 cursor-pointer thumb-item transition-all border-muted';
      }
    });

    // Update modal elements
    const modalImg = document.getElementById('modalGalleryImg');
    if (modalImg) {
      modalImg.src = targetSrc;
      modalZoomReset();
    }
    const counter = document.getElementById('modalImageCounter');
    if (counter) {
      counter.textContent = `Ảnh ${currentImgIndex + 1} / ${galleryImages.length}`;
    }
    document.querySelectorAll('.modal-thumb-item').forEach((item, idx) => {
      if (idx === currentImgIndex) {
        item.className = 'rounded-3 p-1 cursor-pointer modal-thumb-item transition-all border border-warning ring-2 ring-warning';
        item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      } else {
        item.className = 'rounded-3 p-1 cursor-pointer modal-thumb-item transition-all border border-transparent opacity-60';
      }
    });
  }

  function nextGalleryImg() {
    setGalleryIndex(currentImgIndex + 1);
  }

  function prevGalleryImg() {
    setGalleryIndex(currentImgIndex - 1);
  }

  function openGalleryModal(index = 0) {
    setGalleryIndex(index);
    const modalEl = document.getElementById('productGalleryModal');
    if (modalEl) {
      const modal = new bootstrap.Modal(modalEl);
      modal.show();
    }
  }

  function modalZoomIn() {
    modalScale = Math.min(modalScale + 0.5, 3);
    applyModalZoom();
  }

  function modalZoomOut() {
    modalScale = Math.max(modalScale - 0.5, 1);
    applyModalZoom();
  }

  function modalZoomReset() {
    modalScale = 1;
    applyModalZoom();
  }

  function applyModalZoom() {
    const modalImg = document.getElementById('modalGalleryImg');
    if (modalImg) {
      modalImg.style.transform = `scale(${modalScale})`;
      modalImg.style.cursor = modalScale > 1 ? 'move' : 'zoom-in';
    }
  }

  // Magnifier Hover Zoom on Main Image Container
  document.addEventListener('DOMContentLoaded', function () {
    const viewer = document.getElementById('mainImgViewer');
    const mainImg = document.getElementById('mainProductImg');

    if (viewer && mainImg) {
      viewer.addEventListener('mousemove', function (e) {
        const rect = viewer.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        mainImg.style.transformOrigin = `${x}% ${y}%`;
        mainImg.style.transform = 'scale(2)';
      });

      viewer.addEventListener('mouseleave', function () {
        mainImg.style.transformOrigin = 'center center';
        mainImg.style.transform = 'scale(1)';
      });
    }

    // Keyboard Arrow navigation when Lightbox is open
    document.addEventListener('keydown', function (e) {
      const modalEl = document.getElementById('productGalleryModal');
      if (modalEl && modalEl.classList.contains('show')) {
        if (e.key === 'ArrowRight') nextGalleryImg();
        if (e.key === 'ArrowLeft') prevGalleryImg();
        if (e.key === '+' || e.key === '=') modalZoomIn();
        if (e.key === '-' || e.key === '_') modalZoomOut();
        if (e.key === '0') modalZoomReset();
      }
    });
  });

  function changeMainImg(src, el) {
    const foundIdx = galleryImages.findIndex(img => img === src || src.includes(img));
    if (foundIdx !== -1) {
      setGalleryIndex(foundIdx);
    } else {
      const mainImg = document.getElementById('mainProductImg');
      if (mainImg) mainImg.src = src;
    }
  }

  // VARIANT MATRIX & DYNAMIC DATA
  const productVariants = @json($product->variants->map(function($v) {
    return [
      'id' => $v->id,
      'sku' => $v->sku,
      'color' => $v->color,
      'size' => $v->size,
      'price' => $v->price,
      'price_formatted' => number_format($v->price, 0, ',', '.') . '₫',
      'original_price' => $v->original_price,
      'original_price_formatted' => $v->original_price ? number_format($v->original_price, 0, ',', '.') . '₫' : null,
      'stock' => $v->stock,
      'image' => $v->image ? asset($v->image) : null,
      'status' => $v->status
    ];
  }));

  const baseProduct = {
    id: {{ $product->id }},
    price: {{ $product->price }},
    price_formatted: '{{ number_format($product->price, 0, ',', '.') }}₫',
    original_price: {{ $product->original_price ?? 0 }},
    original_price_formatted: '{{ $product->original_price ? number_format($product->original_price, 0, ',', '.') . '₫' : '' }}',
    sku: '{{ $product->sku }}',
    stock: {{ $product->stock ?? 999 }},
    image: '{{ asset($product->image) }}'
  };

  let selectedProductColor = null;
  let selectedProductSize = null;
  let currentProductUnitPrice = {{ $product->price }};

  function selectProductColor(color) {
    selectedProductColor = color;
    const el = document.getElementById('selectedColorText');
    if (el) {
      el.className = 'badge bg-dark text-warning border border-warning px-2 py-0.5 ms-1 fw-bold';
      el.textContent = color;
    }
    const colorSec = document.getElementById('colorGroupSection');
    if (colorSec) {
      colorSec.style.borderColor = '#e2e8f0';
      colorSec.style.backgroundColor = '#ffffff';
    }
    hideFormAlert();

    // Cập nhật trạng thái tồn kho cho các nút Size tương ứng với màu đã chọn
    if (productVariants && productVariants.length > 0) {
      document.querySelectorAll('.product-size-radio').forEach(input => {
        const sz = input.value;
        const v = productVariants.find(item => item.color === selectedProductColor && item.size === sz);
        const label = document.querySelector(`label[for="${input.id}"]`);
        if (label) {
          if (v && v.stock <= 0) {
            label.style.opacity = '0.45';
            label.title = 'Tạm hết hàng phiên bản này';
          } else {
            label.style.opacity = '1';
            label.title = '';
          }
        }
      });
    }

    // Tự động tìm ảnh phù hợp với màu sắc đã chọn nếu có
    if (galleryImages && galleryImages.length > 1) {
      const colorLower = color.toLowerCase();
      const matchIdx = galleryImages.findIndex(img => img.toLowerCase().includes(colorLower));
      if (matchIdx !== -1) {
        setGalleryIndex(matchIdx);
      }
    }

    updateVariantMatchedState();
    updateStickyVariantLabel();
  }

  function selectProductSize(size, hint) {
    selectedProductSize = size;
    const el = document.getElementById('selectedSizeText');
    if (el) {
      el.className = 'badge bg-dark text-warning border border-warning px-2 py-0.5 ms-1 fw-bold';
      el.textContent = 'Size ' + size + (hint ? ' (' + hint + ')' : '');
    }
    const sizeSec = document.getElementById('sizeGroupSection');
    if (sizeSec) {
      sizeSec.style.borderColor = '#e2e8f0';
      sizeSec.style.backgroundColor = '#ffffff';
    }
    hideFormAlert();

    updateVariantMatchedState();
    updateStickyVariantLabel();
  }

  function updateVariantMatchedState() {
    const feedbackBox = document.getElementById('variantLiveFeedback');
    const feedbackDetail = document.getElementById('variantLiveDetail');
    const feedbackStock = document.getElementById('variantLiveStockBadge');
    const variantIdInput = document.getElementById('selectedVariantId');
    const displayPrice = document.getElementById('displayPrice');
    const displayOriginalPrice = document.getElementById('displayOriginalPrice');
    const displayDiscountBadge = document.getElementById('displayDiscountBadge');
    const displaySku = document.getElementById('displaySku');
    const displayStock = document.getElementById('displayStockCount');
    const btnAddToCart = document.getElementById('btnAddToCart');
    const btnBuyNow = document.getElementById('btnBuyNow');

    if (selectedProductColor && selectedProductSize) {
      let matchedVariant = null;
      if (productVariants && productVariants.length > 0) {
        matchedVariant = productVariants.find(v => v.color === selectedProductColor && v.size === selectedProductSize);
      }

      if (matchedVariant) {
        if (variantIdInput) variantIdInput.value = matchedVariant.id;
        currentProductUnitPrice = matchedVariant.price;

        if (displayPrice) displayPrice.textContent = matchedVariant.price_formatted;
        if (displaySku) displaySku.textContent = matchedVariant.sku;
        if (displayStock) displayStock.textContent = matchedVariant.stock;

        if (matchedVariant.original_price && matchedVariant.original_price > matchedVariant.price) {
          if (displayOriginalPrice) {
            displayOriginalPrice.textContent = matchedVariant.original_price_formatted;
            displayOriginalPrice.style.display = 'inline';
          }
          if (displayDiscountBadge) {
            displayDiscountBadge.textContent = 'Tiết kiệm ' + (matchedVariant.original_price - matchedVariant.price).toLocaleString('vi-VN') + '₫';
            displayDiscountBadge.style.display = 'inline-block';
          }
        }

        if (matchedVariant.image) {
          changeMainImg(matchedVariant.image, null);
        }

        if (feedbackBox && feedbackDetail && feedbackStock) {
          feedbackBox.classList.remove('d-none');
          feedbackBox.classList.add('d-flex');
          feedbackDetail.textContent = `${selectedProductColor} / Size ${selectedProductSize} (SKU: ${matchedVariant.sku})`;
          
          if (matchedVariant.stock > 0) {
            feedbackStock.className = 'badge bg-success-subtle text-success fw-bold';
            feedbackStock.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> Còn ${matchedVariant.stock} sản phẩm`;
            if (btnAddToCart) {
              btnAddToCart.disabled = false;
              btnAddToCart.innerHTML = '<i class="fa-solid fa-cart-plus me-1.5"></i> Thêm Vào Giỏ';
            }
            if (btnBuyNow) btnBuyNow.disabled = false;
          } else {
            feedbackStock.className = 'badge bg-danger-subtle text-danger fw-bold';
            feedbackStock.innerHTML = '<i class="fa-solid fa-ban me-1"></i> Tạm hết hàng';
            if (btnAddToCart) {
              btnAddToCart.disabled = true;
              btnAddToCart.innerHTML = '<i class="fa-solid fa-ban me-1.5"></i> Hết Hàng';
            }
            if (btnBuyNow) btnBuyNow.disabled = true;
          }
        }
      } else {
        if (variantIdInput) variantIdInput.value = '';
        currentProductUnitPrice = baseProduct.price;
        if (displayPrice) displayPrice.textContent = baseProduct.price_formatted;
        if (displaySku) displaySku.textContent = baseProduct.sku;
        if (displayStock) displayStock.textContent = baseProduct.stock;

        if (feedbackBox && feedbackDetail && feedbackStock) {
          feedbackBox.classList.remove('d-none');
          feedbackBox.classList.add('d-flex');
          feedbackDetail.textContent = `${selectedProductColor} / Size ${selectedProductSize}`;
          feedbackStock.className = 'badge bg-success-subtle text-success fw-bold';
          feedbackStock.innerHTML = `<i class="fa-solid fa-circle-check me-1"></i> Sẵn hàng (${baseProduct.stock} cái)`;
        }
        if (btnAddToCart) {
          btnAddToCart.disabled = false;
          btnAddToCart.innerHTML = '<i class="fa-solid fa-cart-plus me-1.5"></i> Thêm Vào Giỏ';
        }
        if (btnBuyNow) btnBuyNow.disabled = false;
      }
    } else {
      if (feedbackBox) {
        feedbackBox.classList.add('d-none');
        feedbackBox.classList.remove('d-flex');
      }
    }

    const qtyInput = document.getElementById('productQty');
    const curQty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
    updateQtyDisplay(curQty);
  }

  function updateStickyVariantLabel() {
    const checkedColor = document.querySelector('input[name="color"]:checked');
    const checkedSize = document.querySelector('input[name="size"]:checked');
    const stickyText = document.getElementById('stickySelectedVariantText');
    if (stickyText) {
      const c = checkedColor ? checkedColor.value : '';
      const s = checkedSize ? 'Size ' + checkedSize.value : '';
      if (c || s) {
        stickyText.textContent = [c, s].filter(Boolean).join(' • ');
        stickyText.className = 'badge bg-warning text-dark border border-warning fs-11 d-none d-md-inline fw-bold';
      } else {
        stickyText.textContent = 'Mặc định';
        stickyText.className = 'badge bg-light text-muted border fs-11 d-none d-md-inline';
      }
    }
  }

  // STICKY BOTTOM BAR CONTROLLER
  window.addEventListener('scroll', function () {
    const stickyBar = document.getElementById('stickyAddToCartBar');
    const mainFormBtn = document.getElementById('btnAddToCart');
    if (!stickyBar || !mainFormBtn) return;

    const btnRect = mainFormBtn.getBoundingClientRect();
    if (btnRect.bottom < 0) {
      stickyBar.classList.remove('d-none');
    } else {
      stickyBar.classList.add('d-none');
    }
  });

  function scrollToProductOptions() {
    const target = document.getElementById('colorGroupSection') || document.getElementById('sizeGroupSection') || document.getElementById('productForm');
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  function triggerStickySubmit(isBuyNow) {
    const form = document.getElementById('productForm');
    if (!form) return;

    if (isBuyNow) {
      let buyNowInput = form.querySelector('input[name="buy_now"]');
      if (!buyNowInput) {
        buyNowInput = document.createElement('input');
        buyNowInput.type = 'hidden';
        buyNowInput.name = 'buy_now';
        buyNowInput.value = '1';
        form.appendChild(buyNowInput);
      } else {
        buyNowInput.value = '1';
      }
    }

    if (handleProductFormSubmit({ preventDefault: () => {} })) {
      form.submit();
    }
  }

  // SMART SIZE FIT CALCULATOR
  function calculateSmartFitSize() {
    const height = parseInt(document.getElementById('calcHeight').value) || 170;
    const weight = parseInt(document.getElementById('calcWeight').value) || 65;

    let suggestedSize = 'L';
    let description = '';

    if (weight < 55 && height < 165) {
      suggestedSize = 'S';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, dáng người gọn nhẹ, bạn nên chọn Size S để áo ôm vừa vặn, không bị thùng thình.`;
    } else if (weight <= 65 && height <= 172) {
      suggestedSize = 'M';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, dáng người cân đối chuẩn, Size M sẽ giúp tôn dáng cực đẹp và thoải mái cả ngày.`;
    } else if (weight <= 74 && height <= 178) {
      suggestedSize = 'L';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, vóc dáng đậm người cao ráo, Size L là lựa chọn hoàn hảo nhất cho phom dáng Regular Fit.`;
    } else if (weight <= 82 && height <= 184) {
      suggestedSize = 'XL';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, dáng to cao đầy đặn, Size XL sẽ mang lại sự thoải mái tối đa cho phần ngực và vai.`;
    } else {
      suggestedSize = 'XXL';
      description = `Với chiều cao ${height}cm và cân nặng ${weight}kg, bạn nên chọn Size XXL để đảm bảo sự rộng rãi, thoải mái khi vận động.`;
    }

    document.getElementById('suggestedSizeBadge').textContent = suggestedSize;
    document.getElementById('suggestedSizeName').textContent = 'Size ' + suggestedSize;
    document.getElementById('suggestedSizeDesc').textContent = description;
  }

  function applySuggestedSize() {
    const suggestedSize = document.getElementById('suggestedSizeBadge').textContent.trim();
    const sizeRadios = document.querySelectorAll('.product-size-radio');
    let found = false;

    sizeRadios.forEach(radio => {
      if (radio.value.toUpperCase() === suggestedSize.toUpperCase()) {
        radio.checked = true;
        radio.dispatchEvent(new Event('change'));
        found = true;
      }
    });

    const modalEl = document.getElementById('sizeGuideModal');
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }

    if (found && typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'success',
        title: 'Đã Chọn ' + suggestedSize + '!',
        text: 'Hệ thống đã tự động áp dụng kích cỡ này vào đơn hàng của bạn.',
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
      });
    }
  }

  // COPY COUPON CODE FUNCTION
  function copyCouponCode(code) {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(code).then(() => {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Đã Sao Chép Mã: ' + code,
            text: 'Mã giảm giá đã được lưu vào bộ nhớ tạm. Hãy dán mã vào bước thanh toán nhé!',
            timer: 2500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
          });
        }
      });
    }
  }

  function updateQtyDisplay(val) {
    const input = document.getElementById('productQty');
    const badge = document.getElementById('showQtyLiveBadge');
    const subtotal = document.getElementById('productSubtotalLive');
    const btnMinus = document.getElementById('btnMinusQty');
    const btnPlus = document.getElementById('btnPlusQty');
    const maxMsg = document.getElementById('maxLimitMsg');

    if (input) input.value = val;
    if (badge) {
      badge.textContent = val;
      badge.classList.remove('animate-scale');
      void badge.offsetWidth;
      badge.classList.add('animate-scale');
    }
    if (subtotal) {
      const total = currentProductUnitPrice * val;
      subtotal.textContent = total.toLocaleString('vi-VN') + '₫';
    }
    if (btnMinus) {
      btnMinus.disabled = (val <= 1);
      btnMinus.style.opacity = (val <= 1) ? '0.45' : '1';
    }
    if (btnPlus) {
      btnPlus.disabled = (val >= 10);
      btnPlus.style.opacity = (val >= 10) ? '0.45' : '1';
    }
    if (maxMsg) {
      if (val >= 10) {
        maxMsg.classList.remove('d-none');
      } else {
        maxMsg.classList.add('d-none');
      }
    }
  }

  function stepProductQty(amount) {
    const input = document.getElementById('productQty');
    if (!input) return;
    let val = parseInt(input.value) || 1;
    val += amount;
    if (val < 1) val = 1;
    if (val > 10) val = 10;
    updateQtyDisplay(val);
  }

  function showFormAlert(text) {
    const alertEl = document.getElementById('productFormAlert');
    const textEl = document.getElementById('productFormAlertText');
    if (alertEl && textEl) {
      textEl.textContent = text;
      alertEl.classList.remove('d-none');
      alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  function hideFormAlert() {
    const alertEl = document.getElementById('productFormAlert');
    if (alertEl) alertEl.classList.add('d-none');
  }

  function handleProductFormSubmit(e) {
    const checkedColor = document.querySelector('input[name="color"]:checked');
    const hasColors = document.querySelectorAll('input[name="color"]').length > 0;
    if (hasColors && !checkedColor) {
      e.preventDefault();
      const colorSec = document.getElementById('colorGroupSection');
      if (colorSec) {
        colorSec.style.borderColor = '#e11d48';
        colorSec.style.backgroundColor = '#fff1f2';
        colorSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      showFormAlert('Vui lòng chọn 1 Màu Sắc cho sản phẩm trước khi mua hàng!');
      return false;
    }

    const checkedSize = document.querySelector('input[name="size"]:checked');
    const hasSizes = document.querySelectorAll('input[name="size"]').length > 0;
    if (hasSizes && !checkedSize) {
      e.preventDefault();
      const sizeSec = document.getElementById('sizeGroupSection');
      if (sizeSec) {
        sizeSec.style.borderColor = '#e11d48';
        sizeSec.style.backgroundColor = '#fff1f2';
        sizeSec.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      showFormAlert('Vui lòng chọn 1 Kích Thước (Size) cho sản phẩm trước khi mua hàng!');
      return false;
    }

    return true;
  }

  // Filter Reviews
  function filterReviews(filterType, btnEl) {
    document.querySelectorAll('.filter-review-btn').forEach(btn => {
      btn.classList.remove('btn-dark', 'active', 'shadow-xs');
      btn.classList.add('btn-outline-secondary');
    });
    btnEl.classList.remove('btn-outline-secondary');
    btnEl.classList.add('btn-dark', 'active', 'shadow-xs');

    const items = document.querySelectorAll('.review-card-item');
    let visibleCount = 0;
    items.forEach(item => {
      const rating = item.getAttribute('data-rating');
      const hasPhoto = item.getAttribute('data-has-photo');
      let show = false;
      if (filterType === 'all') {
        show = true;
      } else if (filterType === 'photo') {
        show = (hasPhoto === '1');
      } else {
        show = (rating === filterType);
      }

      if (show) {
        item.style.display = '';
        visibleCount++;
      } else {
        item.style.display = 'none';
      }
    });

    const emptyEl = document.getElementById('reviewFilterEmptyMsg');
    if (emptyEl) {
      emptyEl.style.display = (visibleCount === 0) ? 'block' : 'none';
    }
  }

  function openReviewerModal(reviewId) {
    fetch(`/san-pham/api-reviewer-profile/${reviewId}`)
      .then(res => res.json())
      .then(data => {
        if (!data.success) return;

        document.getElementById('revModalName').textContent = data.user_name || 'Khách Hàng';
        document.getElementById('revModalAvatar').src = data.avatar_url;
        document.getElementById('revModalJoined').innerHTML = `<i class="fa-solid fa-calendar-check me-1 text-warning"></i> ${data.joined_at}`;
        document.getElementById('revModalOrdersCount').textContent = data.total_orders;
        document.getElementById('revModalReviewsCount').textContent = data.total_reviews;

        const rankBadge = document.getElementById('revModalRankBadge');
        if (rankBadge) {
          rankBadge.className = data.rank_class + ' px-2 py-0.5';
          rankBadge.innerHTML = `<i class="fa-solid ${data.rank_icon} me-1"></i> ${data.rank_name}`;
        }

        const listEl = document.getElementById('revModalOtherReviewsList');
        if (listEl) {
          listEl.innerHTML = '';
          if (data.other_reviews && data.other_reviews.length > 0) {
            data.other_reviews.forEach(or => {
              let stars = '';
              for (let i = 1; i <= 5; i++) {
                stars += `<i class="fa-solid fa-star ${i <= or.rating ? 'text-warning' : 'text-secondary-subtle'}"></i>`;
              }
              const itemHtml = `
                <div class="p-2.5 bg-light rounded-3 border d-flex align-items-center gap-2.5">
                  <img src="${or.product_image}" alt="${or.product_name}" class="rounded border shadow-xs" style="width: 48px; height: 48px; object-fit: cover;">
                  <div class="flex-grow-1 min-w-0">
                    <a href="${or.product_url}" class="text-dark fw-bold text-decoration-none d-block text-truncate small" style="font-size: 0.8rem;">${or.product_name}</a>
                    <div class="d-flex align-items-center gap-1.5 small text-warning" style="font-size: 0.7rem;">
                      ${stars} <span class="text-muted ms-1">${or.date}</span>
                    </div>
                    <p class="text-muted mb-0 small text-truncate" style="font-size: 0.72rem;">${or.comment}</p>
                  </div>
                  <a href="${or.product_url}" class="btn btn-outline-dark btn-xs px-2 rounded-pill flex-shrink-0" style="font-size: 0.68rem;">Xem</a>
                </div>
              `;
              listEl.innerHTML += itemHtml;
            });
          } else {
            listEl.innerHTML = `<div class="p-3 bg-light rounded-3 text-center border"><small class="text-muted">Khách hàng này hiện tại chưa chia sẻ thêm bài đánh giá nào khác.</small></div>`;
          }
        }

        const modalEl = document.getElementById('customerReviewerProfileModal');
        if (modalEl) {
          const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
          modal.show();
        }
      })
      .catch(err => console.error('Error loading reviewer profile:', err));
  }
</script>
@endpush