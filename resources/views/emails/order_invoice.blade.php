<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Hóa Đơn Điện Tử Đơn Hàng #{{ $order->order_code }} - BeeStyle</title>
  <style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #1e293b; }
    .container { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .header { background: #0f172a; padding: 25px; text-align: center; color: #ffffff; }
    .header h2 { margin: 0; color: #f59e0b; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
    .body { padding: 30px; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    .badge-paid { background: #dcfce7; color: #166534; }
    .badge-unpaid { background: #fef3c7; color: #92400e; }
    .info-box { background: #f8fafc; border-radius: 10px; padding: 15px; margin: 20px 0; font-size: 13px; }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
    th { text-align: left; padding: 10px; background: #f1f5f9; color: #475569; border-radius: 4px; }
    td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; }
    .total-box { margin-top: 20px; padding-top: 15px; border-top: 2px dashed #cbd5e1; }
    .btn { display: inline-block; background: #f59e0b; color: #0f172a; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; text-align: center; margin-top: 20px; font-size: 14px; }
    .footer { background: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h2>BEESTYLE MENSWEAR</h2>
      <p style="margin: 5px 0 0; font-size: 13px; color: #cbd5e1;">Xác Nhận Hóa Đơn Điện Tử Đơn Hàng</p>
    </div>

    <!-- Body -->
    <div class="body">
      <h3 style="margin-top: 0; color: #0f172a;">Kính gửi {{ $order->customer_name }},</h3>
      <p style="font-size: 14px; line-height: 1.6; color: #475569;">
        Cảm ơn bạn đã mua sắm tại <strong>BeeStyle</strong>! Đơn hàng của bạn đã được tiếp nhận và xử lý thành công.
      </p>

      <!-- Order Info Box -->
      <div class="info-box">
        <div class="info-row">
          <span style="color: #64748b;">Mã đơn hàng:</span>
          <strong style="color: #0284c7; font-family: monospace; font-size: 14px;">{{ $order->order_code }}</strong>
        </div>
        <div class="info-row">
          <span style="color: #64748b;">Thời gian đặt:</span>
          <strong>{{ $order->created_at ? $order->created_at->format('H:i, d/m/Y') : date('H:i, d/m/Y') }}</strong>
        </div>
        <div class="info-row">
          <span style="color: #64748b;">Phương thức thanh toán:</span>
          <strong>{{ $order->payment_method_name }}</strong>
        </div>
        <div class="info-row">
          <span style="color: #64748b;">Trạng thái thanh toán:</span>
          <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
            {{ $order->payment_status_label }}
          </span>
        </div>
        <div class="info-row">
          <span style="color: #64748b;">Địa chỉ nhận hàng:</span>
          <strong>{{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}</strong>
        </div>
      </div>

      <!-- Items Table -->
      <h4 style="margin: 20px 0 10px; color: #0f172a;">Chi Tiết Sản Phẩm:</h4>
      <table>
        <thead>
          <tr>
            <th>Sản phẩm</th>
            <th style="text-align: center;">SL</th>
            <th style="text-align: right;">Đơn giá</th>
            <th style="text-align: right;">Thành tiền</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $item)
            <tr>
              <td>
                <strong>{{ $item->product_name }}</strong><br>
                <span style="color: #64748b; font-size: 11px;">Màu: {{ $item->color }} | Size: {{ $item->size }}</span>
              </td>
              <td style="text-align: center;">{{ $item->quantity }}</td>
              <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }}₫</td>
              <td style="text-align: right; font-weight: bold;">{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Total Calculation -->
      <div class="total-box">
        <div class="info-row">
          <span style="color: #64748b;">Tạm tính tiền hàng:</span>
          <strong>{{ number_format($order->subtotal, 0, ',', '.') }}₫</strong>
        </div>
        @if($order->discount_amount > 0)
          <div class="info-row">
            <span style="color: #64748b;">Giảm giá Voucher ({{ $order->coupon_code }}):</span>
            <strong style="color: #16a34a;">-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</strong>
          </div>
        @endif
        <div class="info-row">
          <span style="color: #64748b;">Phí vận chuyển:</span>
          <strong>{{ $order->shipping_fee == 0 ? 'Miễn phí (Freeship)' : number_format($order->shipping_fee, 0, ',', '.') . '₫' }}</strong>
        </div>
        <div class="info-row" style="font-size: 16px; margin-top: 10px;">
          <strong style="color: #0f172a;">TỔNG THANH TOÁN:</strong>
          <strong style="color: #dc2626; font-size: 18px;">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
        </div>
      </div>

      <!-- Action Button -->
      <div style="text-align: center; margin-top: 25px;">
        <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="btn">
          🔍 TRA CỨU HÀNH TRÌNH ĐƠN HÀNG
        </a>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p style="margin: 0 0 5px;">Mọi thắc mắc xin vui lòng liên hệ Hotline: <strong>1900 6868</strong> hoặc Email: <strong>support@beestyle.com</strong></p>
      <p style="margin: 0;">BeeStyle Menswear - Phong Cách Đích Thực Dành Cho Phái Mạnh.</p>
    </div>
  </div>
</body>
</html>
