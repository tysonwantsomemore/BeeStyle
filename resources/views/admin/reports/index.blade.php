@extends('layouts.admin')

@section('title', 'Báo Cáo & Thống Kê Kinh Doanh | BeeStyle Admin')

@section('content')
<!-- BREADCRUMB & HEADER -->
<div class="row gy-3 mb-4 justify-content-between align-items-center">
  <div class="col-auto">
    <div class="d-flex align-items-center gap-2 mb-1">
      <span class="badge badge-phoenix badge-phoenix-primary text-uppercase fw-bold fs-10">Báo Cáo &amp; Phân Tích</span>
    </div>
    <h2 class="mb-0 text-body-emphasis fw-bold">Báo Cáo Hiệu Quả Kinh Doanh</h2>
    <p class="text-body-tertiary mb-0">Phân tích dòng tiền, biến động đơn hàng, top sản phẩm thịnh hành và khách hàng VIP</p>
  </div>
  <div class="col-auto d-flex gap-2">
    <a href="{{ route('admin.revenue.monthly') }}" class="btn btn-phoenix-secondary btn-sm px-3">
      <span class="fa-solid fa-receipt me-1.5"></span>Chi Tiết Doanh Thu Tháng
    </a>
  </div>
</div>

<!-- BỘ LỌC KHOẢNG THỜI GIAN -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-3">
    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 align-items-end">
      <div class="col-12 col-md-3">
        <label class="form-label fs-10 fw-bold text-body-tertiary text-uppercase mb-1">Từ ngày</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-body-emphasis border-end-0 text-primary">
            <span class="fa-regular fa-calendar"></span>
          </span>
          <input type="date" class="form-control border-start-0" name="from" value="{{ $from->format('Y-m-d') }}">
        </div>
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label fs-10 fw-bold text-body-tertiary text-uppercase mb-1">Đến ngày</label>
        <div class="input-group input-group-sm">
          <span class="input-group-text bg-body-emphasis border-end-0 text-primary">
            <span class="fa-regular fa-calendar"></span>
          </span>
          <input type="date" class="form-control border-start-0" name="to" value="{{ $to->format('Y-m-d') }}">
        </div>
      </div>
      <div class="col-6 col-md-auto">
        <button type="submit" class="btn btn-primary btn-sm px-3">
          <span class="fa-solid fa-chart-column me-1"></span>Xem Báo Cáo
        </button>
      </div>
      <div class="col-6 col-md-auto">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-phoenix-secondary btn-sm px-3">
          <span class="fa-solid fa-rotate-left me-1"></span>Tháng Này
        </a>
      </div>
    </form>
  </div>
</div>

<!-- 4 THẺ CHỈ SỐ TỔNG QUAN -->
<div class="row g-3 mb-4">
  <!-- 1. Doanh thu thuần -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="badge-phoenix-icon me-2 badge-phoenix-danger">
              <span class="fa-solid fa-sack-dollar fs-9"></span>
            </div>
            <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Doanh Thu Thuần</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-danger fw-bolder">{{ number_format($revenue, 0, ',', '.') }}₫</h3>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent fs-10 text-body-tertiary">
          Không bao gồm các đơn hàng đã bị hủy
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Tổng đơn hàng -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="badge-phoenix-icon me-2 badge-phoenix-primary">
              <span class="fa-solid fa-cart-shopping fs-9"></span>
            </div>
            <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Tổng Đơn Hàng</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-body-emphasis fw-bolder">{{ number_format($orderCount) }}</h3>
          <span class="fs-10 text-body-tertiary">đơn phát sinh</span>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent fs-10 text-success fw-semibold">
          <span class="fa-solid fa-circle-check me-1"></span>{{ $validOrderCount }} đơn hợp lệ đang xử lý / hoàn tất
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Đơn giao thành công -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="badge-phoenix-icon me-2 badge-phoenix-success">
              <span class="fa-solid fa-truck-ramp-box fs-9"></span>
            </div>
            <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Đơn Hoàn Tất / Giao</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-success fw-bolder">{{ number_format($completed) }}</h3>
          <span class="fs-10 text-body-tertiary">đơn thành công</span>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent fs-10 text-body-tertiary">
          Tỉ lệ hoàn tất đạt <strong class="text-success">{{ $orderCount ? number_format($completed / $orderCount * 100, 1) : 0 }}%</strong> tổng đơn
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Giá trị trung bình đơn -->
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center">
            <div class="badge-phoenix-icon me-2 badge-phoenix-warning">
              <span class="fa-solid fa-tags fs-9"></span>
            </div>
            <h6 class="mb-0 text-body-secondary text-uppercase fw-semibold fs-10">Giá Trị TB / Đơn (AOV)</h6>
          </div>
        </div>
        <div class="d-flex align-items-baseline gap-2">
          <h3 class="mb-0 text-warning fw-bolder">
            {{ number_format($validOrderCount ? $revenue / $validOrderCount : 0, 0, ',', '.') }}₫
          </h3>
        </div>
        <div class="mt-2 pt-2 border-top border-translucent fs-10 text-body-tertiary">
          Doanh thu trung bình trên mỗi đơn hợp lệ
        </div>
      </div>
    </div>
  </div>
</div>

<!-- BIỂU ĐỒ XU HƯỚNG DOANH THU & ĐƠN HÀNG -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <h5 class="mb-0 text-body-emphasis">Biểu Đồ Xu Hướng Doanh Thu &amp; Đơn Hàng</h5>
      <small class="text-body-tertiary">Khoảng thời gian: {{ $from->format('d/m/Y') }} – {{ $to->format('d/m/Y') }}</small>
    </div>
    <div class="d-flex align-items-center gap-3 fs-10">
      <div class="d-flex align-items-center gap-1.5">
        <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #f59e0b;"></span>
        <span class="text-body-secondary fw-semibold">Doanh thu (VNĐ)</span>
      </div>
      <div class="d-flex align-items-center gap-1.5">
        <span class="d-inline-block rounded-circle" style="width: 10px; height: 10px; background: #2563eb;"></span>
        <span class="text-body-secondary fw-semibold">Đơn hàng</span>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div style="height: 330px">
      <canvas id="reportChart"></canvas>
    </div>
  </div>
</div>

<!-- 4 KHỐI PHÂN TÍCH CHI TIẾT -->
<div class="row g-4">
  <!-- 1. Top sản phẩm bán chạy -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <span class="fa-solid fa-fire text-danger fs-9"></span>
          <h5 class="mb-0 text-body-emphasis">Top Sản Phẩm Bán Chạy</h5>
        </div>
        <span class="badge badge-phoenix badge-phoenix-danger fs-11">Top 5</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive scrollbar">
          <table class="table table-sm fs-9 mb-0 align-middle table-hover">
            <thead class="bg-body-tertiary text-body-tertiary">
              <tr>
                <th class="ps-3 py-2.5">SẢN PHẨM</th>
                <th class="py-2.5 text-center">ĐÃ BÁN</th>
                <th class="pe-3 py-2.5 text-end">DOANH THU</th>
              </tr>
            </thead>
            <tbody class="list">
              @forelse($topProducts as $index => $product)
                <tr>
                  <td class="ps-3 py-2.5">
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge badge-phoenix {{ $index === 0 ? 'badge-phoenix-warning' : ($index === 1 ? 'badge-phoenix-secondary' : 'badge-phoenix-primary') }} rounded-circle p-1" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.68rem;">
                        {{ $index + 1 }}
                      </span>
                      <span class="fw-semibold text-body-emphasis">{{ $product->product_name }}</span>
                    </div>
                  </td>
                  <td class="py-2.5 text-center">
                    <span class="badge badge-phoenix badge-phoenix-primary fw-bold">{{ number_format($product->quantity) }}</span>
                  </td>
                  <td class="pe-3 py-2.5 text-end text-danger fw-bold">
                    {{ number_format($product->revenue, 0, ',', '.') }}₫
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-body-tertiary py-4">Chưa có dữ liệu sản phẩm trong khoảng thời gian này.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Khách hàng mua nhiều nhất -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <span class="fa-solid fa-crown text-warning fs-9"></span>
          <h5 class="mb-0 text-body-emphasis">Khách Hàng Chi Tiêu Cao Nhất</h5>
        </div>
        <span class="badge badge-phoenix badge-phoenix-warning fs-11">Top 5 VIP</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive scrollbar">
          <table class="table table-sm fs-9 mb-0 align-middle table-hover">
            <thead class="bg-body-tertiary text-body-tertiary">
              <tr>
                <th class="ps-3 py-2.5">KHÁCH HÀNG</th>
                <th class="py-2.5 text-center">SỐ ĐƠN</th>
                <th class="pe-3 py-2.5 text-end">TỔNG CHI TIÊU</th>
              </tr>
            </thead>
            <tbody class="list">
              @forelse($topCustomers as $index => $customer)
                <tr>
                  <td class="ps-3 py-2.5">
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge badge-phoenix {{ $index === 0 ? 'badge-phoenix-warning' : ($index === 1 ? 'badge-phoenix-secondary' : 'badge-phoenix-primary') }} rounded-circle p-1" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.68rem;">
                        {{ $index + 1 }}
                      </span>
                      <div>
                        <div class="fw-semibold text-body-emphasis">{{ $customer->name }}</div>
                        <small class="text-body-tertiary fs-11">{{ $customer->email }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-2.5 text-center">
                    <span class="badge badge-phoenix badge-phoenix-info fw-bold">{{ $customer->orders_count }} đơn</span>
                  </td>
                  <td class="pe-3 py-2.5 text-end text-danger fw-bold">
                    {{ number_format($customer->spent, 0, ',', '.') }}₫
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-body-tertiary py-4">Chưa có dữ liệu khách hàng trong khoảng thời gian này.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Phân bố trạng thái đơn hàng -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <span class="fa-solid fa-boxes-packing text-primary fs-9"></span>
          <h5 class="mb-0 text-body-emphasis">Phân Bố Trạng Thái Đơn Hàng</h5>
        </div>
        <span class="badge badge-phoenix badge-phoenix-secondary fs-11">{{ $orderCount }} đơn</span>
      </div>
      <div class="card-body">
        @php
          $statusNames = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang đóng gói',
            'shipping' => 'Đang vận chuyển',
            'delivered' => 'Đã giao hàng',
            'completed' => 'Hoàn tất thành công',
            'cancelled' => 'Đã hủy đơn'
          ];
          $statusColors = [
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'processing' => 'bg-primary',
            'shipping' => 'bg-warning',
            'delivered' => 'bg-success',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger'
          ];
        @endphp
        @forelse($statusBreakdown as $key => $total)
          @php
            $pctStatus = $orderCount > 0 ? round(($total / $orderCount) * 100, 1) : 0;
          @endphp
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center fs-9 mb-1">
              <span class="fw-semibold text-body-emphasis">{{ $statusNames[$key] ?? $key }}</span>
              <span class="text-body-tertiary"><strong class="text-body-emphasis">{{ $total }}</strong> đơn ({{ $pctStatus }}%)</span>
            </div>
            <div class="progress" style="height: 6px;">
              <div class="progress-bar {{ $statusColors[$key] ?? 'bg-secondary' }}" role="progressbar" style="width: {{ $pctStatus }}%"></div>
            </div>
          </div>
        @empty
          <p class="text-body-tertiary mb-0 text-center py-4">Chưa có dữ liệu trạng thái đơn hàng.</p>
        @endforelse
      </div>
    </div>
  </div>

  <!-- 4. Doanh thu theo hình thức thanh toán -->
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header border-bottom border-translucent bg-body-emphasis py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
          <span class="fa-solid fa-credit-card text-success fs-9"></span>
          <h5 class="mb-0 text-body-emphasis">Doanh Thu Theo Phương Thức Thanh Toán</h5>
        </div>
        <span class="badge badge-phoenix badge-phoenix-success fs-11">Thanh toán</span>
      </div>
      <div class="card-body">
        @php
          $paymentNames = [
            'cod' => 'Thanh toán khi nhận hàng (COD)',
            'vietqr' => 'Chuyển khoản Ngân hàng (VietQR)',
            'momo' => 'Ví điện tử MoMo',
            'vnpay' => 'Cổng thanh toán VNPAY'
          ];
          $paymentIcons = [
            'cod' => 'fa-solid fa-hand-holding-dollar text-warning',
            'vietqr' => 'fa-solid fa-qrcode text-primary',
            'momo' => 'fa-solid fa-wallet text-danger',
            'vnpay' => 'fa-solid fa-credit-card text-info'
          ];
        @endphp
        @forelse($paymentBreakdown as $key => $total)
          @php
            $pctPay = $revenue > 0 ? round(($total / $revenue) * 100, 1) : 0;
          @endphp
          <div class="p-3 bg-body-tertiary rounded-3 border border-translucent mb-2.5 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2.5">
              <span class="{{ $paymentIcons[$key] ?? 'fa-solid fa-money-bill text-secondary' }} fs-8"></span>
              <div>
                <strong class="text-body-emphasis d-block fs-9">{{ $paymentNames[$key] ?? strtoupper($key) }}</strong>
                <small class="text-body-tertiary fs-11">Chiếm {{ $pctPay }}% tổng doanh thu</small>
              </div>
            </div>
            <div class="text-end">
              <strong class="text-danger fs-8">{{ number_format($total, 0, ',', '.') }}₫</strong>
            </div>
          </div>
        @empty
          <p class="text-body-tertiary mb-0 text-center py-4">Chưa có dữ liệu thanh toán trong khoảng thời gian này.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const chartEl = document.getElementById('reportChart');
    if (chartEl) {
      new Chart(chartEl, {
        type: 'line',
        data: {
          labels: @json($labels),
          datasets: [
            {
              label: 'Doanh thu (VNĐ)',
              data: @json($revenueSeries),
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.12)',
              fill: true,
              tension: 0.35,
              yAxisID: 'y',
              pointRadius: 4,
              pointHoverRadius: 6
            },
            {
              label: 'Đơn hàng',
              data: @json($orderSeries),
              borderColor: '#2563eb',
              backgroundColor: 'rgba(37, 99, 235, 0.08)',
              tension: 0.35,
              yAxisID: 'y1',
              pointRadius: 4,
              pointHoverRadius: 6
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            mode: 'index',
            intersect: false
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  if (context.datasetIndex === 0) {
                    return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + '₫';
                  } else {
                    return 'Đơn hàng: ' + context.parsed.y + ' đơn';
                  }
                }
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false
              }
            },
            y: {
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  if (value >= 1000000) return (value / 1000000) + 'Tr';
                  if (value >= 1000) return (value / 1000) + 'k';
                  return value;
                }
              }
            },
            y1: {
              beginAtZero: true,
              position: 'right',
              grid: {
                drawOnChartArea: false
              },
              ticks: {
                precision: 0
              }
            }
          }
        }
      });
    }
  });
</script>
@endpush
