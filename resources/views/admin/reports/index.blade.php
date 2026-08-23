@extends('layouts.admin')

@section('title', 'Báo cáo & thống kê | BeeStyle Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
  <div><h3 class="fw-bold text-dark mb-1">Báo cáo &amp; thống kê</h3><p class="text-muted mb-0">Phân tích hiệu quả kinh doanh theo khoảng thời gian lựa chọn.</p></div>
  <a href="{{ route('admin.revenue.monthly') }}" class="btn btn-outline-dark"><i class="fa-solid fa-receipt me-1"></i>Chi tiết doanh thu tháng</a>
</div>

<div class="card border-0 shadow-sm mb-4"><div class="card-body">
  <form method="GET" class="row align-items-end g-3"><div class="col-md-3"><label class="form-label small fw-bold">Từ ngày</label><input type="date" class="form-control" name="from" value="{{ $from->format('Y-m-d') }}"></div><div class="col-md-3"><label class="form-label small fw-bold">Đến ngày</label><input type="date" class="form-control" name="to" value="{{ $to->format('Y-m-d') }}"></div><div class="col-md-auto"><button class="btn btn-bee-primary"><i class="fa-solid fa-chart-column me-1"></i>Xem báo cáo</button></div><div class="col-md-auto"><a class="btn btn-light border" href="{{ route('admin.reports.index') }}">Tháng này</a></div></form>
</div></div>

<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted fw-bold">DOANH THU THUẦN</small><h3 class="fw-bold text-danger mt-2 mb-0">{{ number_format($revenue, 0, ',', '.') }}₫</h3><small class="text-muted">Không tính đơn đã hủy</small></div></div></div>
  <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted fw-bold">TỔNG ĐƠN HÀNG</small><h3 class="fw-bold mt-2 mb-0">{{ number_format($orderCount) }}</h3><small class="text-muted">{{ $validOrderCount }} đơn hợp lệ</small></div></div></div>
  <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted fw-bold">ĐƠN HOÀN TẤT/GIAO</small><h3 class="fw-bold text-success mt-2 mb-0">{{ number_format($completed) }}</h3><small class="text-muted">{{ $orderCount ? number_format($completed / $orderCount * 100, 1) : 0 }}% tổng đơn</small></div></div></div>
  <div class="col-xl-3 col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body"><small class="text-muted fw-bold">GIÁ TRỊ TB/ĐƠN</small><h3 class="fw-bold text-primary mt-2 mb-0">{{ number_format($validOrderCount ? $revenue / $validOrderCount : 0, 0, ',', '.') }}₫</h3><small class="text-muted">Trên đơn không hủy</small></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-4"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-3"><div><h5 class="fw-bold mb-0">Xu hướng doanh thu &amp; đơn hàng</h5><small class="text-muted">{{ $from->format('d/m/Y') }} – {{ $to->format('d/m/Y') }}</small></div></div><div style="height: 320px"><canvas id="reportChart"></canvas></div></div></div>

<div class="row g-4">
  <div class="col-lg-6"><div class="card border-0 shadow-sm h-100"><div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">Top sản phẩm bán chạy</h5></div><div class="table-responsive"><table class="table align-middle mb-0"><thead class="table-light"><tr><th>Sản phẩm</th><th>Đã bán</th><th class="text-end">Doanh thu</th></tr></thead><tbody>@forelse($topProducts as $product)<tr><td class="fw-semibold">{{ $product->product_name }}</td><td>{{ number_format($product->quantity) }}</td><td class="text-end text-danger fw-bold">{{ number_format($product->revenue, 0, ',', '.') }}₫</td></tr>@empty<tr><td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu.</td></tr>@endforelse</tbody></table></div></div></div>
  <div class="col-lg-6"><div class="card border-0 shadow-sm h-100"><div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">Khách hàng mua nhiều nhất</h5></div><div class="table-responsive"><table class="table align-middle mb-0"><thead class="table-light"><tr><th>Khách hàng</th><th>Đơn</th><th class="text-end">Chi tiêu</th></tr></thead><tbody>@forelse($topCustomers as $customer)<tr><td><div class="fw-semibold">{{ $customer->name }}</div><small class="text-muted">{{ $customer->email }}</small></td><td>{{ $customer->orders_count }}</td><td class="text-end text-danger fw-bold">{{ number_format($customer->spent, 0, ',', '.') }}₫</td></tr>@empty<tr><td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu.</td></tr>@endforelse</tbody></table></div></div></div>
  <div class="col-lg-6"><div class="card border-0 shadow-sm h-100"><div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">Trạng thái đơn hàng</h5></div><div class="card-body">@php($statusNames = ['pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','processing'=>'Đang đóng gói','shipping'=>'Đang giao','delivered'=>'Đã giao','completed'=>'Hoàn tất','cancelled'=>'Đã hủy']) @forelse($statusBreakdown as $key => $total)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $statusNames[$key] ?? $key }}</span><strong>{{ $total }} đơn</strong></div>@empty<p class="text-muted mb-0">Chưa có dữ liệu.</p>@endforelse</div></div></div>
  <div class="col-lg-6"><div class="card border-0 shadow-sm h-100"><div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">Doanh thu theo thanh toán</h5></div><div class="card-body">@php($paymentNames = ['cod'=>'COD','vietqr'=>'VietQR','momo'=>'MoMo','vnpay'=>'VNPAY']) @forelse($paymentBreakdown as $key => $total)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $paymentNames[$key] ?? $key }}</span><strong class="text-danger">{{ number_format($total, 0, ',', '.') }}₫</strong></div>@empty<p class="text-muted mb-0">Chưa có dữ liệu.</p>@endforelse</div></div></div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('reportChart'), {type: 'line', data: {labels: @json($labels), datasets: [{label: 'Doanh thu (VNĐ)', data: @json($revenueSeries), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,.14)', fill: true, tension: .35, yAxisID: 'y'}, {label: 'Đơn hàng', data: @json($orderSeries), borderColor: '#2563eb', tension: .35, yAxisID: 'y1'}]}, options: {responsive: true, maintainAspectRatio: false, interaction: {mode: 'index', intersect: false}, scales: {y: {beginAtZero: true, ticks: {callback: value => value.toLocaleString('vi-VN') + '₫'}}, y1: {beginAtZero: true, position: 'right', grid: {drawOnChartArea: false}, ticks: {precision: 0}}}}});
</script>
@endpush
