<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 500px; margin: 30px auto; background: white;
                     border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .logo { text-align: center; margin-bottom: 20px; }
        .logo h2 { color: #1a1a2e; }
        .otp-box { background: #f0f4ff; border: 2px dashed #4361ee;
                   border-radius: 8px; text-align: center; padding: 20px; margin: 20px 0; }
        .otp-code { font-size: 36px; font-weight: bold; color: #4361ee; letter-spacing: 8px; }
        .note { color: #666; font-size: 13px; text-align: center; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
<div class="container">
    <div class="logo">
        <h2>⌚ Đồng Hồ Online</h2>
    </div>

    @if($type === 'register')
        <p>Xin chào! Cảm ơn bạn đã đăng ký tài khoản.</p>
        <p>Vui lòng sử dụng mã OTP bên dưới để xác nhận email của bạn:</p>
    @else
        <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu từ tài khoản của bạn.</p>
        <p>Vui lòng sử dụng mã OTP bên dưới để tiếp tục:</p>
    @endif

    <div class="otp-box">
        <div class="otp-code">{{ $otp }}</div>
    </div>

    <p class="note">⏱️ Mã OTP có hiệu lực trong <strong>10 phút</strong></p>
    <p class="note">🚫 Không chia sẻ mã này với bất kỳ ai!</p>

    <div class="footer">
        <p>Nếu bạn không thực hiện yêu cầu này, hãy bỏ qua email này.</p>
        <p>© 2024 Đồng Hồ Online</p>
    </div>
</div>
</body>
</html>