<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>In Phiếu Đóng Gói Hàng Loạt ({{ $orders->count() }} đơn) | BeeStyle</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f1f5f9;
      color: #0f172a;
    }
    .print-controls {
      max-width: 800px;
      margin: 0 auto 20px auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #ffffff;
      padding: 15px 25px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .btn {
      padding: 10px 20px;
      font-weight: 700;
      font-size: 14px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }
    .btn-primary {
      background-color: #0284c7;
      color: #ffffff;
    }
    .btn-secondary {
      background-color: #e2e8f0;
      color: #334155;
    }
    .packing-slip {
      max-width: 800px;
      margin: 0 auto 30px auto;
      background: #ffffff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.06);
      page-break-after: always;
      border: 1px solid #e2e8f0;
    }
    .slip-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 2px solid #0f172a;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }
    .brand-title {
      font-size: 24px;
      font-weight: 900;
      letter-spacing: -0.5px;
      margin: 0;
    }
    .brand-title span { color: #f59e0b; }
    .slip-title {
      font-size: 18px;
      font-weight: 800;
      text-align: right;
      margin: 0;
      color: #0f172a;
    }
    .order-code {
      font-family: monospace;
      font-size: 16px;
      color: #0284c7;
      font-weight: 700;
    }
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
      font-size: 13px;
      line-height: 1.6;
    }
    .info-box {
      background: #f8fafc;
      padding: 12px 16px;
      border-radius: 8px;
      border: 1px solid #e2e8f0;
    }
    .info-box h5 {
      margin: 0 0 6px 0;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      font-size: 13px;
    }
    th {
      background: #f1f5f9;
      color: #334155;
      text-align: left;
      padding: 10px 12px;
      font-weight: 700;
      border-bottom: 1px solid #cbd5e1;
    }
    td {
      padding: 10px 12px;
      border-bottom: 1px solid #e2e8f0;
    }
    .text-end { text-align: right; }
    .text-center { text-align: center; }
    .summary-box {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      border-top: 2px solid #e2e8f0;
      padding-top: 15px;
    }
    .cod-badge {
      background: #fee2e2;
      color: #b91c1c;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 800;
      display: inline-block;
    }
    .paid-badge {
      background: #dcfce7;
      color: #15803d;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 800;
      display: inline-block;
    }
    .carrier-tag {
      display: inline-block;
      background: #e0f2fe;
      color: #0369a1;
      padding: 4px 8px;
      border-radius: 6px;
      font-weight: 700;
      font-size: 12px;
    }

    @media print {
      body { background: #ffffff; padding: 0; }
      .print-controls { display: none !important; }
      .packing-slip {
        box-shadow: none !important;
        border: none !important;
        padding: 20px 0 !important;
        margin: 0 !important;
        page-break-after: always;
      }
    }
  </style>
</head>
<body>

  <div class="print-controls">
    <div>
      <h4 style="margin: 0 0 4px 0; font-weight: 800;">PHIẾU ĐÓNG GÓI BƯU KIỆN (PACKING SLIPS)</h4>
      <span style="color: #64748b; font-size: 13px;">Đang chuẩn bị in đồng loạt <strong>{{ $orders->count() }}</strong> đơn hàng được chọn</span>
    </div>
    <div style="display: flex; gap: 10px;">
      <button type="button" class="btn btn-secondary" onclick="window.close()">
        <i class="fa-solid fa-xmark"></i> Đóng
      </button>
      <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="fa-solid fa-print"></i> Bấm Để In Ngay
      </button>
    </div>
  </div>

  @foreach($orders as $order)
    <div class="packing-slip">
      <div class="slip-header">
        <div>
          <h2 class="brand-title">BEE<span>STYLE</span> MENSWEAR</h2>
          <small style="color: #64748b;">Website: beestyle.vn • Hotline: 1900 8888</small>
        </div>
        <div style="text-align: right;">
          <h3 class="slip-title">PHIẾU ĐÓNG GÓI &amp; GIAO VẬN</h3>
          <div class="order-code">#{{ $order->order_code }}</div>
          <small style="color: #64748b;">Ngày tạo: {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</small>
        </div>
      </div>

      <div class="info-grid">
        <div class="info-box">
          <h5><i class="fa-solid fa-user me-1"></i> Thông Tin Người Nhận</h5>
          <strong>{{ $order->customer_name }}</strong> - <strong>{{ $order->customer_phone }}</strong><br>
          <span>{{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}</span><br>
          @if($order->notes)
            <em style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">Ghi chú: "{{ $order->notes }}"</em>
          @endif
        </div>
        <div class="info-box">
          <h5><i class="fa-solid fa-truck-fast me-1"></i> Vận Chuyển &amp; Bưu Tá</h5>
          <span>Đơn vị: <strong class="carrier-tag">{{ $order->shipping_carrier ?: 'Chưa phân bổ' }}</strong></span><br>
          <span>Mã vận đơn: <strong style="font-family: monospace;">{{ $order->tracking_code ?: 'Tự động bưu tá lấy' }}</strong></span><br>
          <span>Phương thức: <strong>{{ $order->payment_method_name }}</strong></span>
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th style="width: 40px;" class="text-center">STT</th>
            <th>Tên Sản Phẩm &amp; Phân Loại</th>
            <th class="text-center" style="width: 80px;">Số Lượng</th>
            <th class="text-end" style="width: 110px;">Đơn Giá</th>
            <th class="text-end" style="width: 130px;">Thành Tiền</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $idx => $item)
            <tr>
              <td class="text-center">{{ $idx + 1 }}</td>
              <td>
                <strong>{{ $item->product_name }}</strong><br>
                <small style="color: #64748b;">Màu: {{ $item->color ?? 'Tiêu chuẩn' }} | Size: {{ $item->size ?? 'M' }}</small>
              </td>
              <td class="text-center"><strong>x{{ $item->quantity }}</strong></td>
              <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}₫</td>
              <td class="text-end"><strong>{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}₫</strong></td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div class="summary-box">
        <div>
          @if($order->payment_status === 'paid')
            <div class="paid-badge">
              <i class="fa-solid fa-circle-check me-1"></i> ĐÃ THANH TOÁN (THU BƯU TÁ: 0₫)
            </div>
          @else
            <div class="cod-badge">
              <i class="fa-solid fa-hand-holding-dollar me-1"></i> THU TIỀN COD: {{ number_format($order->total_amount, 0, ',', '.') }}₫
            </div>
          @endif
        </div>
        <div style="text-align: right; font-size: 13px; line-height: 1.8;">
          <div>Tạm tính tiền hàng: <strong>{{ number_format($order->subtotal, 0, ',', '.') }}₫</strong></div>
          @if($order->discount_amount > 0)
            <div style="color: #16a34a;">Voucher giảm giá: -{{ number_format($order->discount_amount, 0, ',', '.') }}₫</div>
          @endif
          <div>Phí vận chuyển: {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . '₫' : 'Miễn phí (0₫)' }}</div>
          <div style="font-size: 16px; font-weight: 900; color: #b91c1c; margin-top: 4px;">
            TỔNG TIỀN ĐƠN: {{ number_format($order->total_amount, 0, ',', '.') }}₫
          </div>
        </div>
      </div>

      <div style="margin-top: 25px; padding-top: 15px; border-top: 1px dashed #cbd5e1; display: flex; justify-content: space-between; font-size: 12px; color: #64748b;">
        <span>Người lập phiếu: Quản trị viên BeeStyle</span>
        <span>Chữ ký người nhận hàng (Ký &amp; ghi rõ họ tên)</span>
      </div>
    </div>
  @endforeach

</body>
</html>
