<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Đồng Hồ Online')</title>
    <meta name="description" content="Đồng Hồ Online - Cửa hàng đồng hồ cao cấp chính hãng">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --gold: #c9a84c;
            --gold-light: #e8c97a;
            --gold-dark: #a07c30;
            --dark-bg: #0a0a0f;
            --dark-card: rgba(255,255,255,0.04);
            --dark-border: rgba(201,168,76,0.2);
            --text-primary: #f0ece0;
            --text-muted: rgba(240,236,224,0.55);
            --input-bg: rgba(255,255,255,0.06);
            --input-border: rgba(201,168,76,0.25);
            --input-focus: rgba(201,168,76,0.6);
            --shadow-gold: 0 0 40px rgba(201,168,76,0.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: var(--dark-bg);
            overflow-x: hidden;
            position: relative;
        }

        /* ===== Animated Background ===== */
        .auth-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(201,168,76,0.07) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(99,60,150,0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 60% 80%, rgba(201,168,76,0.05) 0%, transparent 50%),
                        linear-gradient(135deg, #0a0a0f 0%, #0f0e1a 50%, #0a0a0f 100%);
        }

        .auth-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 25% 25%, rgba(201,168,76,0.08) 0%, transparent 40%),
                radial-gradient(circle at 75% 75%, rgba(120,80,200,0.06) 0%, transparent 40%);
            animation: bgShift 10s ease-in-out infinite alternate;
        }

        @keyframes bgShift {
            0% { opacity: 0.6; transform: scale(1); }
            100% { opacity: 1; transform: scale(1.05); }
        }

        /* Floating particles */
        .particle {
            position: fixed;
            width: 2px;
            height: 2px;
            background: var(--gold);
            border-radius: 50%;
            opacity: 0;
            animation: floatUp linear infinite;
        }
        .particle:nth-child(1)  { left:10%; animation-duration:12s; animation-delay:0s;   width:3px; height:3px; }
        .particle:nth-child(2)  { left:25%; animation-duration:9s;  animation-delay:2s;   }
        .particle:nth-child(3)  { left:40%; animation-duration:14s; animation-delay:4s;   width:2px; height:2px; }
        .particle:nth-child(4)  { left:60%; animation-duration:11s; animation-delay:1s;   width:3px; height:3px; }
        .particle:nth-child(5)  { left:75%; animation-duration:8s;  animation-delay:6s;   }
        .particle:nth-child(6)  { left:88%; animation-duration:13s; animation-delay:3s;   width:2px; height:2px; }
        .particle:nth-child(7)  { left:5%;  animation-duration:16s; animation-delay:5s;   }
        .particle:nth-child(8)  { left:50%; animation-duration:10s; animation-delay:8s;   width:3px; height:3px; }

        @keyframes floatUp {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 0.6; }
            90%  { opacity: 0.3; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* ===== Main Content ===== */
        .auth-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        /* ===== Auth Card ===== */
        .auth-card {
            width: 100%;
            max-width: 480px;
            background: rgba(15, 14, 26, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--dark-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.6), var(--shadow-gold), inset 0 1px 0 rgba(201,168,76,0.15);
            animation: cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(32px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ===== Auth Header ===== */
        .auth-header {
            padding: 36px 40px 28px;
            text-align: center;
            position: relative;
            background: linear-gradient(180deg, rgba(201,168,76,0.06) 0%, transparent 100%);
            border-bottom: 1px solid rgba(201,168,76,0.1);
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 18px;
            font-size: 28px;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(201,168,76,0.35);
            animation: logoPulse 3s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% { box-shadow: 0 8px 24px rgba(201,168,76,0.35); }
            50%       { box-shadow: 0 8px 36px rgba(201,168,76,0.55); }
        }

        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: 0.3px;
            margin-bottom: 4px;
        }

        .auth-subtitle {
            font-size: 0.85rem;
            color: var(--text-muted);
            letter-spacing: 0.2px;
        }

        /* ===== Card Body ===== */
        .auth-body {
            padding: 32px 40px 36px;
        }

        /* ===== Alerts ===== */
        .alert {
            border: none;
            border-radius: 12px;
            font-size: 0.875rem;
            padding: 12px 16px;
            margin-bottom: 20px;
            animation: alertIn 0.3s ease both;
        }

        @keyframes alertIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: rgba(239,68,68,0.12);
            border-left: 3px solid #ef4444;
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34,197,94,0.12);
            border-left: 3px solid #22c55e;
            color: #86efac;
        }

        /* ===== Form Labels ===== */
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        /* ===== Input Group ===== */
        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold);
            font-size: 0.95rem;
            z-index: 5;
            transition: color 0.3s;
        }

        .form-control {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px !important;
            color: var(--text-primary);
            font-size: 0.9rem;
            padding: 13px 16px 13px 44px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control::placeholder { color: rgba(240,236,224,0.25); }

        .form-control:focus {
            background: rgba(255,255,255,0.08);
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.12), 0 4px 16px rgba(201,168,76,0.08);
            color: var(--text-primary);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,0.1);
        }

        .invalid-feedback {
            font-size: 0.78rem;
            color: #fca5a5;
            margin-top: 6px;
            display: block;
        }

        /* Password toggle button */
        .btn-toggle-pass {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            padding: 4px 6px;
            cursor: pointer;
            z-index: 5;
            transition: color 0.2s;
            border-radius: 6px;
        }

        .btn-toggle-pass:hover { color: var(--gold); }

        .has-toggle .form-control { padding-right: 44px; }

        /* ===== Form Group spacing ===== */
        .form-group { margin-bottom: 20px; }
        .form-group:last-of-type { margin-bottom: 0; }

        /* ===== Checkbox ===== */
        .form-check-input {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 5px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--gold);
            border-color: var(--gold);
        }

        .form-check-input:focus { box-shadow: 0 0 0 3px rgba(201,168,76,0.15); }

        .form-check-label {
            font-size: 0.835rem;
            color: var(--text-muted);
            cursor: pointer;
            padding-left: 4px;
        }

        /* ===== Links ===== */
        .auth-link {
            color: var(--gold);
            font-size: 0.835rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .auth-link:hover { color: var(--gold-light); text-decoration: underline; }

        /* ===== Primary Button ===== */
        .btn-auth-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border: none;
            border-radius: 12px;
            color: #0a0a0f;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(201,168,76,0.3);
            margin-top: 8px;
        }

        .btn-auth-primary::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-auth-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(201,168,76,0.45);
        }

        .btn-auth-primary:hover::before { opacity: 1; }
        .btn-auth-primary:active { transform: translateY(0); }

        /* ===== Google Button ===== */
        .btn-google {
            width: 100%;
            padding: 13px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-google:hover {
            background: rgba(255,255,255,0.09);
            border-color: rgba(255,255,255,0.2);
            color: var(--text-primary);
            transform: translateY(-1px);
        }

        /* ===== Divider ===== */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(201,168,76,0.15);
        }

        .auth-divider span {
            font-size: 0.75rem;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* ===== Footer text ===== */
        .auth-footer-text {
            text-align: center;
            font-size: 0.845rem;
            color: var(--text-muted);
            margin-top: 24px;
        }

        /* ===== OTP Input ===== */
        .otp-input-styled {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 16px;
            color: var(--text-primary);
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 20px;
            text-align: center;
            padding: 20px 16px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .otp-input-styled:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
            background: rgba(255,255,255,0.08);
            outline: none;
            color: var(--text-primary);
        }

        .otp-input-styled::placeholder { color: rgba(240,236,224,0.2); letter-spacing: 16px; }

        /* OTP icon */
        .otp-icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.05));
            border: 1px solid rgba(201,168,76,0.25);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            animation: iconFloat 3s ease-in-out infinite;
        }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-6px); }
        }

        /* Email badge */
        .email-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(201,168,76,0.1);
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 100px;
            padding: 6px 16px;
            font-size: 0.83rem;
            color: var(--gold-light);
            margin-bottom: 20px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 520px) {
            .auth-body  { padding: 24px 24px 28px; }
            .auth-header { padding: 28px 24px 20px; }
            .auth-title { font-size: 1.3rem; }
        }

        /* ===== Small text helper ===== */
        .text-hint {
            font-size: 0.76rem;
            color: var(--text-muted);
            margin-top: 6px;
        }

        /* Required star */
        .req { color: #ef4444; }

        /* Success alert styled */
        .success-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.2);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.875rem;
            color: #86efac;
            margin-bottom: 24px;
        }

        /* Back link */
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-muted);
            font-size: 0.835rem;
            text-decoration: none;
            transition: color 0.2s;
            margin-top: 20px;
        }

        .btn-back:hover { color: var(--gold); }

        /* Resend button */
        .btn-resend {
            background: none;
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 10px;
            color: var(--text-muted);
            font-size: 0.835rem;
            padding: 8px 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-resend:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: rgba(201,168,76,0.05);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Animated Background -->
    <div class="auth-bg"></div>

    <!-- Floating particles -->
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>

    <!-- Main content -->
    <div class="auth-wrapper">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>