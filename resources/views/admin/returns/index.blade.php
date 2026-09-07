@extends('layouts.admin')

@section('title', 'Quản Lý Đổi Trả & Hoàn Tiền (RMA) | BeeStyle Admin')

@section('content')
<!-- HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-md">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-warning fs-10 fw-bold px-2 py-1">ĐỔI TRẢ &amp; HOÀN TIỀN</span>
      <h2 class="mb-0 text-body-emphasis fw-bold">Quản Lý Đổi Trả &amp; Hoàn Tiền (RMA)</h2>
    </div>
    <p class="text-body-tertiary mb-0">Trung tâm tiếp nhận, thẩm định chất lượng và quyết toán tài chính các yêu cầu đổi size, đổi màu hoặc hoàn tiền</p>
  </div>
  <div class="col-auto">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-phoenix-secondary">
      <i class="fa-solid fa-cart-shopping me-1"></i> Quản Lý Đơn Hàng
    </a>
  </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
  <!-- 1. Tất cả -->
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('admin.returns.index') }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm h-100 {{ !$status ? 'border-primary' : '' }}">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
              <i class="fa-solid fa-layer-group fs-9"></i>
            </div>
            <span class="badge badge-phoenix badge-phoenix-primary fs-11">Tất cả</span>
          </div>
          <h4 class="fw-bold text-body-emphasis mb-0">{{ $totalCount }}</h4>
          <small class="text-body-tertiary fs-10">Tổng phiếu RMA</small>
        </div>
      </div>
    </a>
  </div>

  <!-- 2. Chờ duyệt -->
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm h-100 {{ $status === 'pending' ? 'border-warning' : '' }}">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
              <i class="fa-solid fa-hourglass-half fs-9"></i>
            </div>
            <span class="badge badge-phoenix badge-phoenix-warning fs-11">Cần Duyệt</span>
          </div>
          <h4 class="fw-bold text-warning mb-0">{{ $pendingCount }}</h4>
          <small class="text-body-tertiary fs-10">Chờ CSKH duyệt</small>
        </div>
      </div>
    </a>
  </div>

  <!-- 3. Chờ gửi hàng -->
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('admin.returns.index', ['status' => 'approved']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm h-100 {{ $status === 'approved' ? 'border-info' : '' }}">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
              <i class="fa-solid fa-box fs-9"></i>
            </div>
            <span class="badge badge-phoenix badge-phoenix-info fs-11">Chờ Hàng</span>
          </div>
          <h4 class="fw-bold text-info mb-0">{{ $approvedCount }}</h4>
          <small class="text-body-tertiary fs-10">Khách đang gửi về</small>
        </div>
      </div>
    </a>
  </div>

  <!-- 4. Kho đã nhận -->
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('admin.returns.index', ['status' => 'received']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm h-100 {{ $status === 'received' ? 'border-primary' : '' }}">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
              <i class="fa-solid fa-boxes-packing fs-9"></i>
            </div>
            <span class="badge badge-phoenix badge-phoenix-primary fs-11">Kiểm Định</span>
          </div>
          <h4 class="fw-bold text-primary mb-0">{{ $receivedCount }}</h4>
          <small class="text-body-tertiary fs-10">Kho nhận &amp; QC</small>
        </div>
      </div>
    </a>
  </div>

  <!-- 5. Đã hoàn tất -->
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('admin.returns.index', ['status' => 'completed']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm h-100 {{ $status === 'completed' ? 'border-success' : '' }}">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
              <i class="fa-solid fa-circle-check fs-9"></i>
            </div>
            <span class="badge badge-phoenix badge-phoenix-success fs-11">Xong</span>
          </div>
          <h4 class="fw-bold text-success mb-0">{{ $completedCount }}</h4>
          <small class="text-body-tertiary fs-10">Đã hoàn tất</small>
        </div>
      </div>
    </a>
  </div>

  <!-- 6. Từ chối -->
  <div class="col-6 col-md-4 col-xl-2">
    <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm h-100 {{ $status === 'rejected' ? 'border-danger' : '' }}">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
              <i class="fa-solid fa-ban fs-9"></i>
            </div>
            <span class="badge badge-phoenix badge-phoenix-danger fs-11">Từ Chối</span>
          </div>
          <h4 class="fw-bold text-danger mb-0">{{ $rejectedCount }}</h4>
          <small class="text-body-tertiary fs-10">Không hợp lệ</small>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- RMA TABLE CARD -->
<div class="card border-0 shadow-sm mb-4">
  <!-- FILTER BAR -->
  <div class="card-header border-bottom border-translucent bg-body-emphasis d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="d-flex align-items-center gap-1 flex-wrap">
      <a href="{{ route('admin.returns.index') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-phoenix-secondary' }} px-3">
        Tất cả ({{ $totalCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-phoenix-secondary' }} px-3">
        Chờ duyệt ({{ $pendingCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-info text-white' : 'btn-phoenix-secondary' }} px-3">
        Chờ gửi hàng ({{ $approvedCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'received']) }}" class="btn btn-sm {{ $status === 'received' ? 'btn-primary' : 'btn-phoenix-secondary' }} px-3">
        Kho đã nhận ({{ $receivedCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'completed']) }}" class="btn btn-sm {{ $status === 'completed' ? 'btn-success' : 'btn-phoenix-secondary' }} px-3">
        Hoàn tất ({{ $completedCount }})
      </a>
      <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-danger' : 'btn-phoenix-secondary' }} px-3">
        Từ chối ({{ $rejectedCount }})
      </a>
    </div>

    <!-- SEARCH FORM -->
    <form action="{{ route('admin.returns.index') }}" method="GET" class="d-flex gap-2">
      @if($status)
        <input type="hidden" name="status" value="{{ $status }}">
      @endif
      <div class="input-group input-group-sm" style="width: 260px;">
        <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Mã RMA, mã đơn, tên khách...">
        @if($search)
          <a href="{{ route('admin.returns.index', $status ? ['status' => $status] : []) }}" class="btn btn-outline-secondary"><i class="fa-solid fa-xmark"></i></a>
        @endif
        <button class="btn btn-phoenix-secondary" type="submit">Tìm</button>
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive scrollbar">
      <table class="table table-sm fs-9 mb-0 align-middle">
        <thead class="bg-body-tertiary text-body-tertiary">
          <tr>
            <th class="ps-3 py-2" style="width: 140px;">Mã Phiếu RMA</th>
            <th class="py-2" style="width: 240px;">Khách Hàng &amp; Đơn Gốc</th>
            <th class="py-2">Hình Thức &amp; Lý Do</th>
            <th class="py-2" style="width: 160px;">Quyết Toán / Đổi Size</th>
            <th class="py-2" style="width: 130px;">Ảnh Minh Chứng</th>
            <th class="py-2" style="width: 150px;">Trạng Thái</th>
            <th class="text-end pe-3 py-2" style="width: 120px;">Thao Tác</th>
          </tr>
        </thead>
        <tbody class="list">
          @forelse($returns as $ret)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static border-bottom border-translucent">
              <!-- MÃ RMA -->
              <td class="ps-3 py-2">
                <a href="{{ route('admin.returns.show', $ret->id) }}" class="fw-bold font-monospace text-primary text-decoration-none d-block fs-9">
                  #{{ $ret->return_code }}
                </a>
                <div class="text-body-tertiary fs-10">
                  <i class="fa-regular fa-clock me-1"></i>{{ $ret->created_at ? $ret->created_at->format('d/m/Y H:i') : '' }}
                </div>
              </td>

              <!-- KHÁCH HÀNG & ĐƠN HÀNG -->
              <td class="py-2">
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle bg-warning-subtle text-warning fw-bold d-flex align-items-center justify-content-center flex-shrink-0 fs-10" style="width: 34px; height: 34px;">
                    {{ strtoupper(substr($ret->user->name ?? ($ret->order->customer_name ?? 'K'), 0, 1)) }}
                  </div>
                  <div>
                    <div class="fw-bold text-body-emphasis fs-9">{{ $ret->user->name ?? ($ret->order->customer_name ?? 'Khách Hàng') }}</div>
                    <div class="text-body-tertiary fs-10">
                      <i class="fa-solid fa-phone me-1"></i>{{ $ret->user->phone ?? ($ret->order->customer_phone ?? '') }}
                    </div>
                    @if($ret->order)
                      <a href="{{ route('admin.orders.show', $ret->order->id) }}" class="fs-10 font-monospace text-body-tertiary text-decoration-none d-inline-block">
                        <i class="fa-solid fa-box me-1 text-primary"></i>Đơn: #{{ $ret->order->order_code }}
                      </a>
                    @endif
                  </div>
                </div>
              </td>

              <!-- HÌNH THỨC & LÝ DO -->
              <td class="py-2">
                <span class="badge badge-phoenix {{ $ret->type === 'exchange' ? 'badge-phoenix-info' : ($ret->type === 'refund_only' ? 'badge-phoenix-warning' : 'badge-phoenix-danger') }} mb-1">
                  {{ $ret->type_label }}
                </span>
                <div class="text-body-emphasis fw-semibold text-truncate fs-9" style="max-width: 240px;" title="{{ $ret->reason }}">
                  {{ $ret->reason }}
                </div>
                @if($ret->customer_notes)
                  <small class="text-body-tertiary text-truncate d-block fs-10" style="max-width: 240px;" title="{{ $ret->customer_notes }}">
                    "{{ $ret->customer_notes }}"
                  </small>
                @endif
              </td>

              <!-- QUYẾT TOÁN / ĐỔI HÀNG -->
              <td class="py-2">
                @if($ret->type === 'exchange')
                  <div class="p-1 bg-body-tertiary rounded border border-translucent fs-10">
                    <span class="badge badge-phoenix badge-phoenix-warning fs-11">Size {{ $ret->exchange_size ?? 'M' }}</span>
                    @if($ret->exchange_color)
                      <span class="text-body-emphasis d-block mt-0.5 text-truncate fs-11">Màu: {{ $ret->exchange_color }}</span>
                    @endif
                  </div>
                @else
                  <strong class="text-danger font-monospace fs-9 d-block">{{ number_format($ret->refund_amount, 0, ',', '.') }}₫</strong>
                  <div class="text-body-tertiary text-truncate fs-10" style="max-width: 140px;">
                    <i class="fa-solid fa-building-columns me-1"></i>{{ $ret->bank_name ? $ret->bank_name . ' (' . substr($ret->bank_account_number, -4) . ')' : 'STK Khách' }}
                  </div>
                @endif
              </td>

              <!-- ẢNH MINH CHỨNG -->
              <td class="py-2">
                @if(!empty($ret->image_proofs) && is_array($ret->image_proofs) && count($ret->image_proofs) > 0)
                  <div class="d-flex gap-1">
                    @foreach(array_slice($ret->image_proofs, 0, 2) as $img)
                      <a href="{{ asset($img) }}" target="_blank">
                        <img src="{{ asset($img) }}" alt="Proof" class="rounded border border-translucent" style="width: 36px; height: 36px; object-fit: cover;">
                      </a>
                    @endforeach
                    @if(count($ret->image_proofs) > 2)
                      <span class="badge badge-phoenix badge-phoenix-secondary d-flex align-items-center justify-content-center rounded" style="width: 36px; height: 36px;">
                        +{{ count($ret->image_proofs) - 2 }}
                      </span>
                    @endif
                  </div>
                @else
                  <span class="badge badge-phoenix badge-phoenix-secondary fs-11">Không ảnh</span>
                @endif
              </td>

              <!-- TRẠNG THÁI -->
              <td class="py-2">
                {!! $ret->status_badge !!}
                @if($ret->admin_notes)
                  <small class="text-body-tertiary d-block text-truncate mt-0.5 fs-10" style="max-width: 140px;" title="{{ $ret->admin_notes }}">
                    <i class="fa-solid fa-message me-1 text-primary"></i>{{ $ret->admin_notes }}
                  </small>
                @endif
              </td>

              <!-- THAO TÁC -->
              <td class="text-end pe-3 py-2">
                <a href="{{ route('admin.returns.show', $ret->id) }}" class="btn btn-sm btn-phoenix-primary py-1 px-2 fs-10">
                  Xử Lý <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-5 text-body-tertiary">
                <i class="fa-solid fa-box-open fs-4 text-body-tertiary mb-2 d-block"></i>
                Không có phiếu yêu cầu đổi trả nào.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($returns->hasPages())
    <div class="card-footer bg-body-emphasis p-3 border-top border-translucent d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div class="fs-10 text-body-tertiary">
        Hiển thị <strong>{{ $returns->firstItem() }}</strong> - <strong>{{ $returns->lastItem() }}</strong> trên tổng số <strong>{{ $returns->total() }}</strong> yêu cầu
      </div>
      <div>{{ $returns->links('pagination::bootstrap-5') }}</div>
    </div>
  @endif
</div>
@endsection
