<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Khôi Phục Mật Khẩu - BeeStyle Menswear</title>
  <style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #1e293b; }
    .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .header { background: #0f172a; padding: 30px; text-align: center; color: #ffffff; }
    .header h2 { margin: 0; color: #f59e0b; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
    .header p { margin: 6px 0 0; font-size: 13px; color: #94a3b8; }
    .body { padding: 35px 30px; }
    .greeting { font-size: 16px; font-weight: 600; color: #0f172a; margin-top: 0; }
    .text { font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 20px; }
    .btn-container { text-align: center; margin: 30px 0; }
    .btn { display: inline-block; background-color: #0f172a; color: #f59e0b !important; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: bold; font-size: 15px; letter-spacing: 0.5px; }
    .btn:hover { background-color: #1e293b; }
    .info-box { background: #f8fafc; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0; padding: 15px; margin: 25px 0; font-size: 13px; color: #64748b; }
    .link-alt { word-break: break-all; font-size: 12px; color: #0284c7; }
    .footer { background: #f1f5f9; padding: 20px 30px; text-align: center; font-size: 12px; color: #64748b; line-height: 1.5; }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h2>BEESTYLE MENSWEAR</h2>
      <p>Thời trang nam may đo &amp; cao cấp</p>
    </div>

    <!-- Body -->
    <div class="body">
      <h3 class="greeting">Xin chào {{ $user->full_name ?? 'Quý khách' }},</h3>
      <p class="text">
        Bạn nhận được email này vì hệ thống BeeStyle đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản liên kết với địa chỉ email: <strong>{{ $user->email }}</strong>.
      </p>

      <p class="text">
        Vui lòng nhấn vào nút bên dưới để tiến hành đổi mật khẩu mới. Liên kết này chỉ có hiệu lực trong vòng <strong>60 phút</strong> kể từ thời điểm gửi:
      </p>

      <div class="btn-container">
        <a href="{{ $resetUrl }}" class="btn" target="_blank">
          ĐẶT LẠI MẬT KHẨU CỦA BẠN
        </a>
      </div>

      <div class="info-box">
        <strong>Lưu ý bảo mật:</strong> Nếu bạn không yêu cầu đặt lại mật khẩu, xin hãy bỏ qua email này hoặc thông báo ngay cho đội ngũ hỗ trợ kỹ thuật của BeeStyle. Mật khẩu của bạn vẫn hoàn toàn được giữ bí mật và bảo vệ an toàn.
      </div>

      <p class="text" style="font-size: 12px; color: #94a3b8;">
        Nếu bạn gặp sự cố khi nhấp vào nút "Đặt Lại Mật Khẩu Của Bạn", hãy sao chép và dán liên kết dưới đây vào trình duyệt web:<br>
        <a href="{{ $resetUrl }}" class="link-alt">{{ $resetUrl }}</a>
      </p>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p style="margin: 0 0 6px;">&copy; {{ date('Y') }} BeeStyle Menswear. Bản quyền được bảo lưu.</p>
      <p style="margin: 0;">Hotline hỗ trợ: 0987.654.321 | Email: cskh@beestyle.com</p>
    </div>
  </div>
</body>
</html>
