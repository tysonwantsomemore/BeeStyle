@extends('layouts.admin')

@section('title', 'Quản Lý Đổi Trả & Hoàn Tiền (RMA) | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
  <div>
    <div class="d-flex align-items-center gap-2 mb-1">
      <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
        <i class="fa-solid fa-arrow-rotate-left fs-5"></i>
      </div>
      <h3 class="fw-black text-dark mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.5px;">
        Quản Lý Đổi Trả &amp; Hoàn Tiền <span class="text-warning">(RMA)</span>
      </h3>
    </div>
    <p class="text-muted small mb-0">Trung tâm tiếp nhận, thẩm định chất lượng và quyết toán tài chính các yêu cầu đổi size, đổi màu hoặc hoàn tiền</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm px-3.5 py-2 fw-bold rounded-pill shadow-xs">
      <i class="fa-solid fa-cart-shopping me-1.5 text-secondary"></i> Quản Lý Đơn Hàng
    </a>
  </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
  <!-- 1. Tất cả -->
  <div class="col-6 col-md-4 col-lg-2">
    <a href="{{ route('admin.returns.index') }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3.5 h-100 transition-all hover-lift position-relative overflow-hidden {{ !$status ? 'ring-2 ring-primary border-primary' : '' }}" 
           style="border-radius: 16px; background: #ffffff; border: 1px solid {{ !$status ? '#3b82f6' : '#f1f5f9' }} !important;">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div class="rounded-circle bg-dark bg-opacity-10 text-dark d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-layer-group fs-6"></i>
          </div>
          <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.7rem;">Tất cả</span>
        </div>
        <div class="fs-4 fw-black text-dark mb-0">{{ $totalCount }}</div>
        <small class="text-muted" style="font-size: 0.72rem;">Tổng phiếu RMA</small>
      </div>
    </a>
  </div>

  <!-- 2. Chờ duyệt -->
  <div class="col-6 col-md-4 col-lg-2">
    <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3.5 h-100 transition-all hover-lift position-relative overflow-hidden {{ $status === 'pending' ? 'ring-2 ring-warning' : '' }}" 
           style="border-radius: 16px; background: #ffffff; border-left: 4px solid #f59e0b !important; border: 1px solid {{ $status === 'pending' ? '#f59e0b' : '#f1f5f9' }};">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div class="rounded-circle bg-warning bg-opacity-15 text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-hourglass-half fs-6"></i>
          </div>
          <span class="badge bg-warning-subtle text-dark fw-bold px-2 py-1" style="font-size: 0.7rem;">Cần Duyệt</span>
        </div>
        <div class="fs-4 fw-black text-warning mb-0">{{ $pendingCount }}</div>
        <small class="text-muted" style="font-size: 0.72rem;">Chờ CSKH duyệt</small>
      </div>
    </a>
  </div>

  <!-- 3. Đã duyệt / Chờ gửi hàng -->
  <div class="col-6 col-md-4 col-lg-2">
    <a href="{{ route('admin.returns.index', ['status' => 'approved']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3.5 h-100 transition-all hover-lift position-relative overflow-hidden {{ $status === 'approved' ? 'ring-2 ring-info' : '' }}" 
           style="border-radius: 16px; background: #ffffff; border-left: 4px solid #0ea5e9 !important; border: 1px solid {{ $status === 'approved' ? '#0ea5e9' : '#f1f5f9' }};">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div class="rounded-circle bg-info bg-opacity-15 text-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-box fs-6"></i>
          </div>
          <span class="badge bg-info-subtle text-info fw-bold px-2 py-1" style="font-size: 0.7rem;">Chờ Hàng</span>
        </div>
        <div class="fs-4 fw-black text-info mb-0">{{ $approvedCount }}</div>
        <small class="text-muted" style="font-size: 0.72rem;">Khách đang gửi về</small>
      </div>
    </a>
  </div>

  <!-- 4. Kho đã nhận -->
  <div class="col-6 col-md-4 col-lg-2">
    <a href="{{ route('admin.returns.index', ['status' => 'received']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3.5 h-100 transition-all hover-lift position-relative overflow-hidden {{ $status === 'received' ? 'ring-2 ring-primary' : '' }}" 
           style="border-radius: 16px; background: #ffffff; border-left: 4px solid #3b82f6 !important; border: 1px solid {{ $status === 'received' ? '#3b82f6' : '#f1f5f9' }};">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div class="rounded-circle bg-primary bg-opacity-15 text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-boxes-packing fs-6"></i>
          </div>
          <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1" style="font-size: 0.7rem;">Kiểm Định</span>
        </div>
        <div class="fs-4 fw-black text-primary mb-0">{{ $receivedCount }}</div>
        <small class="text-muted" style="font-size: 0.72rem;">Kho nhận &amp; QC</small>
      </div>
    </a>
  </div>

  <!-- 5. Đã hoàn tất -->
  <div class="col-6 col-md-4 col-lg-2">
    <a href="{{ route('admin.returns.index', ['status' => 'completed']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3.5 h-100 transition-all hover-lift position-relative overflow-hidden {{ $status === 'completed' ? 'ring-2 ring-success' : '' }}" 
           style="border-radius: 16px; background: #ffffff; border-left: 4px solid #10b981 !important; border: 1px solid {{ $status === 'completed' ? '#10b981' : '#f1f5f9' }};">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div class="rounded-circle bg-success bg-opacity-15 text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-circle-check fs-6"></i>
          </div>
          <span class="badge bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 0.7rem;">Xong</span>
        </div>
        <div class="fs-4 fw-black text-success mb-0">{{ $completedCount }}</div>
        <small class="text-muted" style="font-size: 0.72rem;">Đã hoàn tiền / đổi</small>
      </div>
    </a>
  </div>

  <!-- 6. Từ chối -->
  <div class="col-6 col-md-4 col-lg-2">
    <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3.5 h-100 transition-all hover-lift position-relative overflow-hidden {{ $status === 'rejected' ? 'ring-2 ring-danger' : '' }}" 
           style="border-radius: 16px; background: #ffffff; border-left: 4px solid #ef4444 !important; border: 1px solid {{ $status === 'rejected' ? '#ef4444' : '#f1f5f9' }};">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div class="rounded-circle bg-danger bg-opacity-15 text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-ban fs-6"></i>
          </div>
          <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1" style="font-size: 0.7rem;">Từ Chối</span>
        </div>
        <div class="fs-4 fw-black text-danger mb-0">{{ $rejectedCount }}</div>
        <small class="text-muted" style="font-size: 0.72rem;">Không hợp lệ</small>
      </div>
    </a>
  </div>
</div>

<!-- SEARCH & TABLE CARD -->
<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px; background: #ffffff; border: 1px solid #f1f5f9 !important;">
  <!-- FILTER BAR -->
  <div class="card-header bg-white p-3.5 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <a href="{{ route('admin.returns.index') }}" class="btn btn-sm rounded-pill fw-bold {{ !$status ? 'btn-dark' : 'btn-light border text-dark' }} px-3">
        Tất cả ({{ $totalCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="btn btn-sm rounded-pill fw-bold {{ $status === 'pending' ? 'btn-warning text-dark' : 'btn-light border text-dark' }} px-3">
        <i class="fa-solid fa-hourglass-half me-1"></i> Chờ duyệt ({{ $pendingCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'approved']) }}" class="btn btn-sm rounded-pill fw-bold {{ $status === 'approved' ? 'btn-info text-white' : 'btn-light border text-dark' }} px-3">
        <i class="fa-solid fa-box me-1"></i> Chờ gửi hàng ({{ $approvedCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'received']) }}" class="btn btn-sm rounded-pill fw-bold {{ $status === 'received' ? 'btn-primary' : 'btn-light border text-dark' }} px-3">
        <i class="fa-solid fa-boxes-packing me-1"></i> Kho đã nhận ({{ $receivedCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'completed']) }}" class="btn btn-sm rounded-pill fw-bold {{ $status === 'completed' ? 'btn-success' : 'btn-light border text-dark' }} px-3">
        <i class="fa-solid fa-circle-check me-1"></i> Đã hoàn tất ({{ $completedCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="btn btn-sm rounded-pill fw-bold {{ $status === 'rejected' ? 'btn-danger' : 'btn-light border text-dark' }} px-3">
        <i class="fa-solid fa-ban me-1"></i> Từ chối ({{ $rejectedCount }})
      </a>
    </div>

    <!-- SEARCH FORM -->
    <form action="{{ route('admin.returns.index') }}" method="GET" class="d-flex gap-2">
      @if($status)
        <input type="hidden" name="status" value="{{ $status }}">
      @endif
      <div class="input-group input-group-sm" style="width: 280px;">
        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
        <input type="text" name="q" value="{{ $search }}" class="form-control border-start-0 ps-0" placeholder="Mã RMA, mã đơn, tên khách...">
        @if($search)
          <a href="{{ route('admin.returns.index', $status ? ['status' => $status] : []) }}" class="btn btn-outline-secondary border-start-0"><i class="fa-solid fa-xmark"></i></a>
        @endif
        <button class="btn btn-dark" type="submit">Tìm</button>
      </div>
    </form>
  </div>

  <!-- TABLE -->
  <div class="table-responsive">
    <table class="table align-middle mb-0 table-hover">
      <thead class="table-light text-muted small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
        <tr>
          <th style="width: 140px;" class="ps-4">Mã Phiếu RMA</th>
          <th style="width: 240px;">Khách Hàng &amp; Đơn Gốc</th>
          <th>Hình Thức &amp; Lý Do</th>
          <th style="width: 160px;">Quyết Toán / Đổi Size</th>
          <th style="width: 130px;">Ảnh Minh Chứng</th>
          <th style="width: 150px;">Trạng Thái</th>
          <th class="text-end pe-4" style="width: 120px;">Thao Tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($returns as $ret)
          <tr>
            <!-- CỘT 1: MÃ RMA -->
            <td class="ps-4">
              <a href="{{ route('admin.returns.show', $ret->id) }}" class="fw-bold font-monospace text-primary text-decoration-none d-block hover-primary" style="font-size: 0.88rem;">
                #{{ $ret->return_code }}
              </a>
              <div class="small text-muted" style="font-size: 0.72rem;">
                <i class="fa-regular fa-clock me-1"></i>{{ $ret->created_at ? $ret->created_at->format('d/m/Y H:i') : '' }}
              </div>
            </td>

            <!-- CỘT 2: KHÁCH HÀNG & ĐƠN HÀNG -->
            <td>
              <div class="d-flex align-items-center gap-2.5">
                <div class="rounded-circle bg-warning bg-opacity-20 text-dark fw-bold d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 36px; height: 36px; font-size: 0.85rem;">
                  {{ strtoupper(substr($ret->user->name ?? ($ret->order->customer_name ?? 'K'), 0, 1)) }}
                </div>
                <div>
                  <div class="fw-bold text-dark small">{{ $ret->user->name ?? ($ret->order->customer_name ?? 'Khách Hàng') }}</div>
                  <div class="text-muted" style="font-size: 0.74rem;">
                    <i class="fa-solid fa-phone me-1 text-secondary"></i>{{ $ret->user->phone ?? ($ret->order->customer_phone ?? '') }}
                  </div>
                  @if($ret->order)
                    <a href="{{ route('admin.orders.show', $ret->order->id) }}" class="small font-monospace text-secondary text-decoration-none d-inline-block mt-0.5" style="font-size: 0.72rem;">
                      <i class="fa-solid fa-box me-1 text-warning"></i>Đơn: #{{ $ret->order->order_code }}
                    </a>
                  @endif
                </div>
              </div>
            </td>

            <!-- CỘT 3: HÌNH THỨC & LÝ DO -->
            <td>
              <span class="badge {{ $ret->type === 'exchange' ? 'bg-info-subtle text-info' : ($ret->type === 'refund_only' ? 'bg-warning-subtle text-dark' : 'bg-danger-subtle text-danger') }} fw-bold mb-1 px-2 py-0.5" style="font-size: 0.72rem;">
                <i class="fa-solid {{ $ret->type === 'exchange' ? 'fa-arrow-right-arrow-left' : ($ret->type === 'refund_only' ? 'fa-hand-holding-dollar' : 'fa-box-open') }} me-1"></i>
                {{ $ret->type_label }}
              </span>
              <div class="small text-dark fw-semibold text-truncate" style="max-width: 260px;" title="{{ $ret->reason }}">
                {{ $ret->reason }}
              </div>
              @if($ret->customer_notes)
                <small class="text-muted text-truncate d-block" style="max-width: 260px; font-size: 0.72rem;" title="{{ $ret->customer_notes }}">
                  "{{ $ret->customer_notes }}"
                </small>
              @endif
            </td>

            <!-- CỘT 4: QUYẾT TOÁN / ĐỔI HÀNG -->
            <td>
              @if($ret->type === 'exchange')
                <div class="p-1.5 bg-light rounded-2 border small">
                  <span class="text-muted d-block" style="font-size: 0.68rem;">Đổi sang:</span>
                  <span class="badge bg-warning text-dark fw-bold" style="font-size: 0.72rem;">Size {{ $ret->exchange_size ?? 'M' }}</span>
                  @if($ret->exchange_color)
                    <span class="text-dark small d-block mt-0.5 text-truncate" style="font-size: 0.7rem;">Màu: {{ $ret->exchange_color }}</span>
                  @endif
                </div>
              @else
                <strong class="text-danger font-monospace fs-6 d-block">{{ number_format($ret->refund_amount, 0, ',', '.') }}₫</strong>
                <div class="small text-muted text-truncate" style="font-size: 0.72rem; max-width: 150px;">
                  <i class="fa-solid fa-building-columns me-1 text-secondary"></i>{{ $ret->bank_name ? $ret->bank_name . ' (' . substr($ret->bank_account_number, -4) . ')' : 'STK Khách hàng' }}
                </div>
              @endif
            </td>

            <!-- CỘT 5: ẢNH MINH CHỨNG -->
            <td>
              @if(!empty($ret->image_proofs) && is_array($ret->image_proofs) && count($ret->image_proofs) > 0)
                <div class="d-flex gap-1">
                  @foreach(array_slice($ret->image_proofs, 0, 2) as $img)
                    <a href="{{ asset($img) }}" target="_blank" class="position-relative">
                      <img src="{{ asset($img) }}" alt="Proof" class="rounded border shadow-xs transition-all hover-scale" style="width: 38px; height: 38px; object-fit: cover;">
                    </a>
                  @endforeach
                  @if(count($ret->image_proofs) > 2)
                    <span class="badge bg-secondary d-flex align-items-center justify-content-center rounded shadow-xs" style="width: 38px; height: 38px; font-size: 0.7rem;">
                      +{{ count($ret->image_proofs) - 2 }}
                    </span>
                  @endif
                </div>
              @else
                <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">Không ảnh</span>
              @endif
            </td>

            <!-- CỘT 6: TRẠNG THÁI -->
            <td>
              {!! $ret->status_badge !!}
              @if($ret->admin_notes)
                <small class="text-muted d-block text-truncate mt-0.5" style="max-width: 140px; font-size: 0.68rem;" title="{{ $ret->admin_notes }}">
                  <i class="fa-solid fa-message me-0.5 text-primary"></i>{{ $ret->admin_notes }}
                </small>
              @endif
            </td>

            <!-- CỘT 7: THAO TÁC -->
            <td class="text-end pe-4">
              <a href="{{ route('admin.returns.show', $ret->id) }}" class="btn btn-sm btn-outline-primary py-1 px-3 fw-bold rounded-pill shadow-xs d-inline-flex align-items-center gap-1">
                <span>Xử Lý</span>
                <i class="fa-solid fa-arrow-right fs-11"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3 shadow-xs" style="width: 64px; height: 64px;">
                <i class="fa-solid fa-box-open fs-2 text-secondary"></i>
              </div>
              <h6 class="fw-bold text-dark mb-1">Không có phiếu yêu cầu đổi trả nào</h6>
              <p class="small text-muted mb-0">Tất cả các đơn hàng đều đang trong trạng thái vận hành ổn định.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($returns->hasPages())
    <div class="card-footer bg-white p-3.5 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="small text-muted">
        Hiển thị <strong>{{ $returns->firstItem() }}</strong> - <strong>{{ $returns->lastItem() }}</strong> trên tổng số <strong>{{ $returns->total() }}</strong> yêu cầu
      </div>
      <div>{{ $returns->links('pagination::bootstrap-5') }}</div>
    </div>
  @endif
</div>
@endsection
