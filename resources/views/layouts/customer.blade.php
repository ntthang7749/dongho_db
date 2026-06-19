<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang Chủ') — Đồng Hồ Online</title>
    <meta name="description" content="Đồng Hồ Online - Cửa hàng đồng hồ chính hãng cao cấp tại Việt Nam">

    <!-- Preconnect CDNs -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== DESIGN TOKENS ===== */
        :root {
            --gold:        #c9a84c;
            --gold-light:  #e8c97a;
            --gold-dark:   #a07c30;
            --dark-bg:     #0a0a0f;
            --dark-nav:    #0d0d18;
            --dark-card:   #131320;
            --dark-card2:  #181828;
            --border:      rgba(201,168,76,0.18);
            --text:        #f0ece0;
            --text-muted:  rgba(240,236,224,0.52);
            --primary:     #1a1a2e;
            --secondary:   #e94560;
            --transition:  all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f5f4f0;
            color: #1a1a2e;
            margin: 0;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: var(--dark-bg);
            color: var(--text-muted);
            font-size: 11.5px;
            padding: 7px 0;
            border-bottom: 1px solid rgba(201,168,76,0.08);
            letter-spacing: 0.2px;
        }
        .topbar a { color: var(--gold); text-decoration: none; transition: color 0.2s; }
        .topbar a:hover { color: var(--gold-light); }
        .topbar .divider { color: rgba(201,168,76,0.3); margin: 0 8px; }

        /* ===== NAVBAR ===== */
        .navbar-main {
            background: rgba(10, 10, 15, 0.97) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 24px rgba(0,0,0,0.35);
            padding: 0;
            transition: var(--transition);
        }

        .navbar-main .container { padding: 0 24px; }

        /* Logo */
        .navbar-brand-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            padding: 14px 0;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(201,168,76,0.35);
        }

        .brand-text { line-height: 1.1; }
        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--gold);
            display: block;
            letter-spacing: 0.5px;
        }
        .brand-tagline {
            font-size: 9px;
            color: var(--text-muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* Nav links */
        .navbar-main .nav-link {
            color: rgba(240,236,224,0.78) !important;
            font-size: 0.855rem;
            font-weight: 500;
            padding: 20px 14px !important;
            letter-spacing: 0.2px;
            position: relative;
            transition: color 0.2s;
        }
        .navbar-main .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 14px; right: 14px;
            height: 2px;
            background: var(--gold);
            transform: scaleX(0);
            transition: transform 0.25s;
            border-radius: 2px 2px 0 0;
        }
        .navbar-main .nav-link:hover { color: var(--gold) !important; }
        .navbar-main .nav-link:hover::after { transform: scaleX(1); }
        .navbar-main .nav-link.active { color: var(--gold) !important; }
        .navbar-main .nav-link.active::after { transform: scaleX(1); }

        /* Dropdown */
        .navbar-main .dropdown-menu {
            background: #0f0f1e;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 16px 48px rgba(0,0,0,0.5);
            padding: 8px;
            margin-top: 0;
            min-width: 200px;
            animation: dropIn 0.2s ease;
        }
        @media (min-width: 992px) {
            .navbar-main .dropdown:hover .dropdown-menu {
                display: block !important;
            }
        }
        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .navbar-main .dropdown-item {
            color: rgba(240,236,224,0.72);
            font-size: 0.845rem;
            padding: 9px 14px;
            border-radius: 8px;
            transition: var(--transition);
        }
        .navbar-main .dropdown-item:hover {
            background: rgba(201,168,76,0.1);
            color: var(--gold);
        }
        .navbar-main .dropdown-divider { border-color: var(--border); margin: 4px 8px; }

        /* Search bar */
        .search-form { position: relative; }
        .search-input {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 100px;
            color: var(--text);
            font-size: 0.83rem;
            padding: 8px 40px 8px 16px;
            width: 240px;
            transition: var(--transition);
            outline: none;
        }
        .search-input::placeholder { color: var(--text-muted); }
        .search-input:focus {
            border-color: var(--gold);
            background: rgba(255,255,255,0.09);
            width: 280px;
            box-shadow: 0 0 0 3px rgba(201,168,76,0.1);
            color: var(--text);
        }
        .search-btn {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gold);
            cursor: pointer;
            padding: 0;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .search-btn:hover { color: var(--gold-light); }

        /* Nav action icons */
        .nav-action {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            color: rgba(240,236,224,0.78);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            font-size: 1.1rem;
        }
        .nav-action:hover { background: rgba(201,168,76,0.1); color: var(--gold); }

        /* Cart badge */
        .cart-badge {
            position: absolute;
            top: 3px; right: 3px;
            background: var(--secondary);
            color: white;
            border-radius: 50%;
            font-size: 9px;
            width: 16px; height: 16px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            border: 1.5px solid var(--dark-nav);
        }

        /* Login button */
        .btn-nav-login {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border: none;
            border-radius: 100px;
            color: #0a0a0f !important;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 7px 18px;
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
        }
        .btn-nav-login:hover {
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(201,168,76,0.35);
        }

        /* Avatar dropdown */
        .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            border: 2px solid rgba(201,168,76,0.4);
            object-fit: cover;
            cursor: pointer;
            transition: border-color 0.2s;
        }
        .user-avatar:hover { border-color: var(--gold); }
        .user-initial {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #0a0a0f;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px;
            cursor: pointer;
            border: none;
            outline: none;
        }
        .navbar-main .dropdown-menu-end { right: 0; left: auto; }

        /* Hamburger */
        .navbar-toggler {
            border: 1px solid rgba(201,168,76,0.25);
            border-radius: 8px;
            padding: 7px 10px;
            color: var(--text);
        }
        .navbar-toggler:focus { box-shadow: 0 0 0 3px rgba(201,168,76,0.15); }

        /* ===== FLASH ALERTS ===== */
        .flash-container { padding: 12px 0 0; }
        .alert-luxury {
            border: none;
            border-radius: 12px;
            font-size: 0.875rem;
            padding: 12px 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-luxury.success { background: rgba(34,197,94,0.1); border-left: 3px solid #22c55e; color: #16a34a; }
        .alert-luxury.error   { background: rgba(239,68,68,0.1);  border-left: 3px solid #ef4444; color: #dc2626; }
        .alert-luxury.warning { background: rgba(245,158,11,0.1); border-left: 3px solid #f59e0b; color: #d97706; }

        /* ===== PRODUCT CARD ===== */
        .product-card {
            background: #fff;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: var(--transition);
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.14);
        }
        .product-card .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .product-card:hover .card-img-top { transform: scale(1.04); }
        .product-card .price-new { color: var(--secondary); font-weight: 700; font-size: 1.05rem; }
        .product-card .price-old { color: #aaa; font-size: 0.82rem; text-decoration: line-through; }
        .badge-sale {
            background: var(--secondary);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }
        .badge-new {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #0a0a0f;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
        }

        /* ===== SECTION TITLE ===== */
        .section-title {
            position: relative;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
            padding-bottom: 14px;
            margin-bottom: 32px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 48px; height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 2px;
        }
        .section-title .badge-tag {
            font-size: 0.7rem;
            background: rgba(201,168,76,0.12);
            color: var(--gold-dark);
            border: 1px solid rgba(201,168,76,0.3);
            border-radius: 100px;
            padding: 2px 10px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            vertical-align: middle;
            margin-left: 10px;
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb-wrap {
            background: #f8f7f3;
            padding: 10px 0;
            border-bottom: 1px solid #ebe9e0;
        }
        .breadcrumb { margin: 0; font-size: 0.82rem; }
        .breadcrumb-item + .breadcrumb-item::before { color: #bbb; }
        .breadcrumb-item.active { color: #666; }

        /* ===== FOOTER ===== */
        .footer-main {
            background: var(--dark-bg);
            color: var(--text-muted);
            padding: 60px 0 0;
            border-top: 1px solid var(--border);
        }
        .footer-main h5 { font-family: 'Playfair Display', serif; color: var(--gold); font-size: 1.1rem; }
        .footer-main h6 {
            color: rgba(240,236,224,0.85);
            font-weight: 600;
            font-size: 0.82rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        .footer-main a { color: var(--text-muted); text-decoration: none; font-size: 0.855rem; transition: color 0.2s; }
        .footer-main a:hover { color: var(--gold); }
        .footer-main ul li { margin-bottom: 8px; }

        .footer-brand-desc { font-size: 0.845rem; line-height: 1.75; color: var(--text-muted); }
        .footer-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px; height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.95rem;
            transition: var(--transition);
        }
        .footer-social a:hover {
            background: rgba(201,168,76,0.12);
            border-color: var(--gold);
            color: var(--gold);
            transform: translateY(-2px);
        }
        .footer-divider { border-color: rgba(201,168,76,0.1); margin: 32px 0 0; }
        .footer-bottom {
            padding: 18px 0;
            font-size: 0.8rem;
            color: rgba(240,236,224,0.35);
        }

        /* ===== TOAST ===== */
        .toast-container { z-index: 9999; }

        /* ===== CHATBOT BUTTON ===== */
        .chatbot-fab {
            position: fixed;
            bottom: 28px; right: 28px;
            z-index: 999;
            width: 58px; height: 58px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border: none;
            color: #0a0a0f;
            font-size: 1.4rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 28px rgba(201,168,76,0.45);
            cursor: pointer;
            transition: var(--transition);
            animation: fabPulse 3s ease-in-out infinite;
        }
        .chatbot-fab:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 36px rgba(201,168,76,0.6);
        }
        @keyframes fabPulse {
            0%, 100% { box-shadow: 0 8px 28px rgba(201,168,76,0.45); }
            50%       { box-shadow: 0 8px 40px rgba(201,168,76,0.65); }
        }

        /* ===== CHATBOT OFFCANVAS ===== */
        #chatbot {
            background: #0d0d18;
            border-left: 1px solid var(--border);
        }
        #chatbot .offcanvas-header {
            background: linear-gradient(135deg, #111128, #1a1a35);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
        }
        #chatbot .offcanvas-body { background: #0a0a14; padding: 0; }
        #chatMessages {
            background: #0d0d1a;
            scrollbar-width: thin;
            scrollbar-color: rgba(201,168,76,0.2) transparent;
        }
        .chat-bubble-bot {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(201,168,76,0.1);
            border-radius: 14px 14px 14px 4px;
            color: rgba(240,236,224,0.88);
            font-size: 0.875rem;
        }
        .chat-bubble-user {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #0a0a0f;
            border-radius: 14px 14px 4px 14px;
            font-size: 0.875rem;
        }
        #chatbot .quick-reply {
            background: rgba(201,168,76,0.08);
            border: 1px solid rgba(201,168,76,0.2);
            color: rgba(240,236,224,0.7);
            border-radius: 100px;
            font-size: 0.78rem;
            padding: 5px 12px;
            transition: var(--transition);
        }
        #chatbot .quick-reply:hover {
            background: rgba(201,168,76,0.18);
            border-color: var(--gold);
            color: var(--gold);
        }
        #chatbot .input-group .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(201,168,76,0.2);
            color: rgba(240,236,224,0.88);
            font-size: 0.875rem;
        }
        #chatbot .input-group .form-control:focus {
            background: rgba(255,255,255,0.08);
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201,168,76,0.1);
            color: rgba(240,236,224,0.88);
        }
        #chatbot .input-group .form-control::placeholder { color: rgba(240,236,224,0.3); }
        #chatbot .btn-send {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border: none;
            color: #0a0a0f;
            font-weight: 700;
            padding: 10px 16px;
        }
        #chatbot .btn-send:hover { background: linear-gradient(135deg, var(--gold-light), var(--gold)); }
        #chatbot .input-wrap { background: rgba(10,10,20,0.95); border-top: 1px solid var(--border); }
        #chatbot .resize-handle:hover, #chatbot .resize-handle.active {
            background: rgba(201, 168, 76, 0.4);
        }

        /* Typing dots */
        .typing-dot {
            width: 6px; height: 6px;
            background: var(--gold);
            border-radius: 50%;
            animation: typingAnim 1.2s infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typingAnim {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.6; }
            30% { transform: translateY(-6px); opacity: 1; }
        }
        .chat-message { animation: fadeIn 0.3s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(201,168,76,0.25); border-radius: 4px; }

        /* ===== UTILITIES ===== */
        .text-gold { color: var(--gold) !important; }
        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #0a0a0f;
            border: none;
            font-weight: 700;
            transition: var(--transition);
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(201,168,76,0.35);
            color: #0a0a0f;
        }
        .btn-outline-gold {
            border: 1.5px solid var(--gold);
            color: var(--gold-dark);
            background: transparent;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-outline-gold:hover {
            background: var(--gold);
            color: #0a0a0f;
            box-shadow: 0 4px 16px rgba(201,168,76,0.3);
        }

        /* ── FLOATING COMPARE BAR & BUTTONS ── */
        .compare-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(10, 10, 15, 0.95);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-top: 1px solid var(--border);
            padding: 12px 0;
            z-index: 1040;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 -8px 32px rgba(0,0,0,0.5);
            color: var(--text);
        }
        .compare-bar.show {
            transform: translateY(0);
        }
        .compare-bar-title h6 {
            font-size: 0.95rem;
            color: var(--gold);
            letter-spacing: 0.5px;
        }
        .compare-bar-title small {
            font-size: 0.72rem;
            color: var(--text-muted);
        }
        .compare-item {
            position: relative;
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px dashed rgba(201, 168, 76, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
            transition: var(--transition);
        }
        .compare-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 7px;
        }
        .compare-item.empty {
            color: rgba(201, 168, 76, 0.3);
            font-size: 1.1rem;
        }
        .compare-item-remove {
            position: absolute;
            top: -5px;
            right: -5px;
            color: #e94560;
            cursor: pointer;
            font-size: 0.85rem;
            line-height: 1;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }
        .compare-item-remove:hover {
            color: #ff2a51;
            transform: scale(1.2);
        }

        /* CARD COMPARE BUTTON STYLE */
        .pcard-compare {
            position: absolute;
            top: 48px;
            right: 8px;
            width: 34px; height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 3;
            opacity: 0;
            color: #1a1a2e;
        }
        .pcard:hover .pcard-compare { opacity: 1; }
        .pcard-compare:hover {
            background: #fff;
            transform: scale(1.12);
            box-shadow: 0 4px 14px rgba(0,0,0,0.18);
            color: var(--gold);
        }
        .pcard-compare.active {
            background: var(--gold) !important;
            color: #0a0a0f !important;
            opacity: 1 !important;
            box-shadow: 0 4px 14px rgba(201,168,76,0.35);
        }
        .pcard-compare.active i {
            color: #0a0a0f !important;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ════════════ TOPBAR ════════════ --}}
<div class="topbar">
    <div class="container d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-telephone me-1" style="color:var(--gold);"></i>1800 6868
            <span class="divider">|</span>
            <i class="bi bi-clock me-1" style="color:var(--gold);"></i>8:00 - 21:00 hàng ngày
        </span>
        <span>
            @auth
                <i class="bi bi-person-check me-1" style="color:var(--gold);"></i>
                Xin chào, <strong style="color:rgba(240,236,224,0.88);">{{ auth()->user()->name }}</strong>
                <a href="{{ route('logout') }}" class="ms-2"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Đăng xuất
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
            @else
                <a href="{{ route('login') }}">Đăng nhập</a>
                <span class="divider">|</span>
                <a href="{{ route('register') }}">Đăng ký</a>
            @endauth
        </span>
    </div>
</div>

{{-- ════════════ NAVBAR ════════════ --}}
<nav class="navbar navbar-main navbar-expand-lg sticky-top">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand-custom" href="{{ route('home') }}">
            <div class="brand-icon">⌚</div>
            <div class="brand-text">
                <span class="brand-name">ĐỒNG HỒ ONLINE</span>
                <span class="brand-tagline">Premium Watch Store</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="bi bi-list text-white fs-5"></i>
        </button>

        <div class="collapse navbar-collapse ms-4" id="navMenu">
            {{-- Menu chính --}}
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">Trang Chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('products.*') && !request()->routeIs('products.suggestions') ? 'active' : '' }}"
                       href="{{ route('products.index') }}" data-bs-toggle="dropdown">Sản Phẩm</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('products.index', ['category' => 'dong-ho-nam']) }}">
                            <i class="bi bi-watch me-2" style="color:var(--gold);"></i>Đồng Hồ Nam
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['category' => 'dong-ho-nu']) }}">
                            <i class="bi bi-watch me-2" style="color:var(--gold);"></i>Đồng Hồ Nữ
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['category' => 'dong-ho-treo-tuong']) }}">
                            <i class="bi bi-clock me-2" style="color:var(--gold);"></i>Đồng Hồ Treo Tường
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('products.index') }}">
                            <i class="bi bi-grid me-2" style="color:var(--gold);"></i>Tất Cả Sản Phẩm
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('ai.image.form') }}">
                            <i class="bi bi-camera me-2" style="color:var(--gold);"></i>
                            Nhận Diện AI
                            <span class="badge ms-1" style="background:linear-gradient(135deg,var(--gold),var(--gold-dark));color:#0a0a0f;font-size:9px;padding:2px 6px;border-radius:4px;">Mới</span>
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.suggestions') ? 'active' : '' }}"
                       href="{{ route('products.suggestions') }}">Gợi Ý Sản Phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}"
                       href="{{ route('news.index') }}">Tin Tức</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}"
                       href="{{ route('contact.index') }}">Liên Hệ</a>
                </li>
            </ul>

            {{-- Search --}}
            <form class="search-form me-3" action="{{ route('products.search') }}" method="GET">
                <input class="search-input" type="search" name="q"
                       placeholder="Tìm kiếm đồng hồ..."
                       value="{{ request('q') }}"
                       autocomplete="off">
                <button class="search-btn" type="submit"><i class="bi bi-search"></i></button>
            </form>

            {{-- Action Icons --}}
            <div class="d-flex align-items-center gap-2">
                {{-- Wishlist --}}
                @auth
                <a href="{{ route('wishlist.index') }}" class="nav-action" title="Yêu thích">
                    <i class="bi bi-heart"></i>
                </a>
                @endauth

                {{-- Giỏ hàng --}}
                <a href="{{ route('cart.index') }}" class="nav-action" title="Giỏ hàng">
                    <i class="bi bi-bag"></i>
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- Tài khoản --}}
                @auth
                <div class="dropdown">
                    <button class="user-initial dropdown-toggle border-0 bg-transparent p-0"
                            style="background:linear-gradient(135deg,var(--gold),var(--gold-dark))!important;"
                            data-bs-toggle="dropdown" aria-label="Tài khoản">
                        @if(auth()->user()->avatarUrl())
                            <img src="{{ auth()->user()->avatarUrl() }}" class="user-avatar" alt="Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div style="padding:10px 14px 6px;">
                                <p style="color:rgba(240,236,224,0.88);font-weight:600;font-size:0.875rem;margin:0;">{{ auth()->user()->name }}</p>
                                <p style="color:var(--text-muted);font-size:0.75rem;margin:0;">{{ auth()->user()->email }}</p>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2" style="color:var(--gold);"></i>Tài khoản</a></li>
                        <li><a class="dropdown-item" href="{{ route('orders.history') }}"><i class="bi bi-bag me-2" style="color:var(--gold);"></i>Đơn hàng</a></li>
                        <li><a class="dropdown-item" href="{{ route('wishlist.index') }}"><i class="bi bi-heart me-2" style="color:var(--gold);"></i>Yêu thích</a></li>
                        @if(auth()->user()->isAdmin())
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}" style="color:#e94560;">
                            <i class="bi bi-shield-check me-2"></i>Admin Panel
                        </a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" style="color:#e94560;"
                               onclick="document.getElementById('logout-form').submit()">
                                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-nav-login">Đăng nhập</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ════════════ FLASH MESSAGES ════════════ --}}
@if(session('success') || session('error') || session('warning'))
<div class="container flash-container">
    @if(session('success'))
        <div class="alert-luxury success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-luxury error alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert-luxury warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('warning') }}</span>
            <button type="button" class="btn-close btn-close-sm ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- ════════════ MAIN CONTENT ════════════ --}}
@yield('content')

{{-- ════════════ AI CHATBOT FAB ════════════ --}}
<button class="chatbot-fab" data-bs-toggle="offcanvas" data-bs-target="#chatbot" aria-label="Chat AI">
    <i class="bi bi-robot"></i>
</button>

{{-- ════════════ CHATBOT OFFCANVAS ════════════ --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="chatbot" style="width:400px;">
    <!-- Drag resize handle -->
    <div class="resize-handle" style="position: absolute; left: 0; top: 0; bottom: 0; width: 6px; cursor: ew-resize; z-index: 1060; transition: background 0.2s;"></div>
    <div class="offcanvas-header">
        <div class="d-flex align-items-center gap-3">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                🤖
            </div>
            <div>
                <h6 class="mb-0 fw-bold" style="color:rgba(240,236,224,0.95);font-size:0.95rem;">Trợ Lý AI</h6>
                <small style="color:#22c55e;font-size:0.75rem;">
                    <span style="display:inline-block;width:6px;height:6px;background:#22c55e;border-radius:50%;margin-right:4px;"></span>
                    Đang hoạt động
                </small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-0">
        {{-- Messages --}}
        <div id="chatMessages" class="flex-grow-1 p-3 overflow-auto" style="max-height:calc(100vh - 200px);">
            {{-- Welcome --}}
            <div class="d-flex gap-2 mb-3 chat-message">
                <div style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;align-self:flex-end;">🤖</div>
                <div class="chat-bubble-bot p-3" style="max-width:85%;">
                    👋 Xin chào! Tôi là <strong style="color:var(--gold);">Trợ lý AI</strong> của Đồng Hồ Online.<br><br>
                    Tôi có thể giúp bạn:<br>
                    🕐 Tư vấn chọn đồng hồ<br>
                    💰 So sánh giá cả<br>
                    📦 Hỗ trợ đặt hàng<br><br>
                    Bạn cần tư vấn gì?
                </div>
            </div>

            {{-- Quick replies --}}
            <div class="d-flex flex-wrap gap-2 mb-3" id="quickReplies">
                @foreach(['Đồng hồ nam dưới 2 triệu', 'Đồng hồ nữ dự tiệc', 'So sánh Casio và Seiko', 'Chính sách đổi trả'] as $quick)
                <button class="btn quick-reply" data-msg="{{ $quick }}">{{ $quick }}</button>
                @endforeach
            </div>
        </div>

        {{-- Typing --}}
        <div id="typingIndicator" class="px-3 pb-2 d-none">
            <div class="d-flex gap-2 align-items-center">
                <div style="width:24px;height:24px;border-radius:8px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:10px;flex-shrink:0;">🤖</div>
                <div class="chat-bubble-bot px-3 py-2">
                    <div class="d-flex gap-1 align-items-center">
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="input-wrap p-3">
            <div class="input-group">
                <input type="text" id="chatInput"
                    class="form-control rounded-start-pill"
                    placeholder="Nhập câu hỏi..."
                    maxlength="500">
                <button class="btn btn-send rounded-end-pill px-3" id="sendChat">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
            <div class="text-center mt-2">
                <small style="font-size:10.5px;color:rgba(240,236,224,0.3);">
                    <i class="bi bi-shield-check me-1" style="color:var(--gold);"></i>
                    Được hỗ trợ bởi Google Gemini AI
                </small>
            </div>
        </div>
    </div>
</div>

{{-- ════════════ FOOTER ════════════ --}}
<footer class="footer-main mt-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-3 mb-16" style="margin-bottom:16px;">
                    <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow:0 6px 20px rgba(201,168,76,0.3);">⌚</div>
                    <div>
                        <h5 class="mb-0">ĐỒNG HỒ ONLINE</h5>
                        <small style="color:rgba(201,168,76,0.6);font-size:10px;letter-spacing:1px;">PREMIUM WATCH STORE</small>
                    </div>
                </div>
                <p class="footer-brand-desc">Chuyên cung cấp đồng hồ chính hãng cao cấp với đa dạng thương hiệu nổi tiếng thế giới. Cam kết chất lượng 100%.</p>
                <div class="footer-social d-flex gap-2 mt-3">
                    <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" title="YouTube"><i class="bi bi-youtube"></i></a>
                    <a href="#" title="TikTok"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <div class="col-md-2">
                <h6>Sản Phẩm</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('products.index', ['category' => 'dong-ho-nam']) }}">Đồng hồ nam</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'dong-ho-nu']) }}">Đồng hồ nữ</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'dong-ho-treo-tuong']) }}">Đồng hồ treo tường</a></li>
                    <li><a href="{{ route('products.index') }}">Tất cả sản phẩm</a></li>
                </ul>
            </div>

            <div class="col-md-2">
                <h6>Hỗ Trợ</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('support.returns') }}">Chính sách đổi trả</a></li>
                    <li><a href="{{ route('support.warranty') }}">Bảo hành sản phẩm</a></li>
                    <li><a href="{{ route('support.buying_guide') }}">Hướng dẫn mua hàng</a></li>
                    <li><a href="{{ route('support.faq') }}">Câu hỏi thường gặp</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h6>Liên Hệ</h6>
                <ul class="list-unstyled" style="font-size:0.855rem;">
                    <li style="margin-bottom:10px;">
                        <i class="bi bi-geo-alt me-2" style="color:var(--gold);"></i>Hà Nội, Việt Nam
                    </li>
                    <li style="margin-bottom:10px;">
                        <i class="bi bi-telephone me-2" style="color:var(--gold);"></i>1800 6868
                    </li>
                    <li style="margin-bottom:10px;">
                        <i class="bi bi-envelope me-2" style="color:var(--gold);"></i>20222062@eaut.edu.vn
                    </li>
                    <li>
                        <i class="bi bi-clock me-2" style="color:var(--gold);"></i>8:00 - 21:00 hàng ngày
                    </li>
                </ul>
            </div>
        </div>

        <hr class="footer-divider">
        <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>© {{ date('Y') }} Đồng Hồ Online. All rights reserved.</span>
            <span>
                <i class="bi bi-shield-check me-1" style="color:var(--gold);"></i>
                Thanh toán an toàn &amp; bảo mật
            </span>
        </div>
    </div>
</footer>

{{-- ════════════ FLOATING COMPARE BAR ════════════ --}}
@php
    $compareProductsSession = session('compare_products', []);
    $compareProductsData = $compareProductsSession ? \App\Models\Product::whereIn('id', $compareProductsSession)->get() : collect();
@endphp
<div id="compare-bar" class="compare-bar shadow {{ count($compareProductsSession) > 0 ? 'show' : '' }}">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <div class="compare-bar-title d-none d-md-block">
                <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right text-warning me-2"></i>So sánh sản phẩm</h6>
                <small style="color: var(--text-muted);">Chọn tối đa 3 sản phẩm</small>
            </div>
            <div id="compare-items" class="d-flex align-items-center gap-2">
                @foreach($compareProductsData as $p)
                    <div class="compare-item" data-id="{{ $p->id }}">
                        <img src="{{ $p->thumbnail ? asset('storage/' . $p->thumbnail) : asset('images/no-image.png') }}" alt="{{ $p->name }}" title="{{ $p->name }}">
                        <span class="compare-item-remove" data-id="{{ $p->id }}"><i class="bi bi-x-circle-fill"></i></span>
                    </div>
                @endforeach
                @for($i = count($compareProductsSession); $i < 3; $i++)
                    <div class="compare-item empty">
                        <i class="bi bi-plus"></i>
                    </div>
                @endfor
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button id="compare-clear-btn" class="btn btn-sm btn-outline-light text-white" style="font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.25); border-radius: 8px; padding: 6px 12px; background: transparent;">Xóa hết</button>
            <a href="{{ route('products.compare') }}" class="btn btn-sm" style="font-size: 0.8rem; background: var(--gold); color: #0a0a0f; font-weight: 700; border-radius: 8px; padding: 6px 16px; text-decoration: none;">So sánh ngay (<span id="compare-count">{{ count($compareProductsSession) }}</span>)</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

<script>
// Auto-hide flash alerts after 4s
document.querySelectorAll('.alert-luxury').forEach(el => {
    setTimeout(() => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(-8px)';
        el.style.transition = 'all 0.4s ease';
        setTimeout(() => el.remove(), 400);
    }, 4000);
});

// ── WISHLIST TOGGLE ──
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.wishlist-btn');
    if (!btn) return;
    e.preventDefault();

    const id   = btn.dataset.id;
    if (!id) return;

    const icon = btn.querySelector('i');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrf) { window.location.href = '{{ route("login") }}'; return; }

    btn.disabled = true;
    fetch(`/yeu-thich/toggle/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && icon) {
            const isOn = icon.className.includes('heart-fill');
            icon.className = isOn ? 'bi bi-heart small' : 'bi bi-heart-fill text-danger small';
        }
    })
    .catch(() => alert('Có lỗi xảy ra, thử lại sau.'))
    .finally(() => { btn.disabled = false; });
});

// ── CHATBOT LOGIC ──
const chatMessages = document.getElementById('chatMessages');
const chatInput    = document.getElementById('chatInput');
const sendBtn      = document.getElementById('sendChat');
const typingEl     = document.getElementById('typingIndicator');

let history = [];

async function sendMessage(message = null) {
    const msg = (message || chatInput.value).trim();
    if (!msg) return;

    document.getElementById('quickReplies')?.remove();
    appendMessage(msg, 'user');
    chatInput.value = '';
    history.push({ role: 'user', content: msg });

    typingEl.classList.remove('d-none');
    scrollToBottom();

    try {
        const res  = await fetch('{{ route("ai.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message: msg, history: history.slice(-6) }),
        });

        const data = await res.json();
        typingEl.classList.add('d-none');

        if (data.success) {
            appendMessage(data.response, 'bot');
            history.push({ role: 'assistant', content: data.response });

            if (/(đồng hồ|casio|seiko|citizen|tissot|gợi ý|tư vấn)/i.test(msg)) {
                loadProductSuggestions(msg);
            }
        } else {
            appendMessage(data.message || 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại!', 'bot');
        }
    } catch (e) {
        typingEl.classList.add('d-none');
        appendMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại!', 'bot');
    }
}

function appendMessage(text, role) {
    const isBot = role === 'bot';
    const div   = document.createElement('div');
    div.className = 'chat-message d-flex gap-2 mb-3' + (isBot ? '' : ' flex-row-reverse');

    const formattedText = text.replace(/\n/g, '<br>');

    div.innerHTML = `
        ${isBot ? `<div style="width:30px;height:30px;border-radius:10px;background:linear-gradient(135deg,#c9a84c,#a07c30);display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;align-self:flex-end;">🤖</div>` : ''}
        <div class="${isBot ? 'chat-bubble-bot' : 'chat-bubble-user'} p-3" style="max-width:85%;">${formattedText}</div>
        ${!isBot ? `<div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#c9a84c,#a07c30);display:flex;align-items:center;justify-content:center;color:#0a0a0f;font-weight:700;font-size:12px;flex-shrink:0;align-self:flex-end;">{{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}</div>` : ''}
    `;

    chatMessages.appendChild(div);
    scrollToBottom();
}

async function loadProductSuggestions(query) {
    try {
        const res  = await fetch('{{ route("ai.suggest") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ query }),
        });

        const data = await res.json();

        if (data.success && data.products.length > 0) {
            const productsHtml = data.products.map(p => `
                <a href="${p.url}" class="text-decoration-none" target="_blank">
                    <div class="d-flex align-items-center gap-2 p-2 mb-1" style="background:rgba(255,255,255,0.04);border:1px solid rgba(201,168,76,0.1);border-radius:10px;">
                        <img src="${p.thumbnail || '/images/no-image.png'}" width="40" height="40"
                            style="object-fit:cover;border-radius:8px;flex-shrink:0;">
                        <div class="overflow-hidden">
                            <p class="mb-0 fw-semibold text-truncate" style="font-size:0.78rem;color:rgba(240,236,224,0.88);">${p.name}</p>
                            <span style="font-size:0.78rem;color:var(--gold);font-weight:700;">${Number(p.price).toLocaleString('vi')}đ</span>
                        </div>
                    </div>
                </a>
            `).join('');

            const div = document.createElement('div');
            div.className = 'chat-message mb-3';
            div.innerHTML = `
                <p style="font-size:0.78rem;color:rgba(201,168,76,0.7);margin-bottom:8px;">
                    <i class="bi bi-stars me-1"></i><strong>Sản phẩm gợi ý:</strong>
                </p>
                ${productsHtml}
            `;
            chatMessages.appendChild(div);
            scrollToBottom();
        }
    } catch (e) { /* ignore */ }
}

function scrollToBottom() { chatMessages.scrollTop = chatMessages.scrollHeight; }

// ── CHATBOT RESIZE DRAG LOGIC ──
(function() {
    const chatbot = document.getElementById('chatbot');
    const handle = chatbot.querySelector('.resize-handle');
    let isResizing = false;

    handle.addEventListener('mousedown', function(e) {
        isResizing = true;
        handle.classList.add('active');
        document.body.style.cursor = 'ew-resize';
        document.body.style.userSelect = 'none';
        e.preventDefault();
    });

    document.addEventListener('mousemove', function(e) {
        if (!isResizing) return;
        const newWidth = window.innerWidth - e.clientX;
        if (newWidth >= 320 && newWidth <= window.innerWidth * 0.85) {
            chatbot.style.width = newWidth + 'px';
        }
    });

    document.addEventListener('mouseup', function() {
        if (isResizing) {
            isResizing = false;
            handle.classList.remove('active');
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        }
    });
})();

// Click quick replies
document.addEventListener('click', function(e) {
    const quickBtn = e.target.closest('.quick-reply');
    if (quickBtn) {
        e.preventDefault();
        const msg = quickBtn.dataset.msg;
        if (msg) {
            sendMessage(msg);
        }
    }
});

sendBtn.addEventListener('click', () => sendMessage());
chatInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});
// ── COMPARE SYSTEM ──
function updateCompareBar(products, count) {
    const bar = document.getElementById('compare-bar');
    const itemsContainer = document.getElementById('compare-items');
    const countSpan = document.getElementById('compare-count');
    
    if (countSpan) countSpan.textContent = count;
    
    if (count > 0) {
        if (bar) bar.classList.add('show');
    } else {
        if (bar) bar.classList.remove('show');
    }
    
    if (itemsContainer) {
        // Re-render items
        let html = '';
        for (let i = 0; i < 3; i++) {
            if (products && products[i]) {
                const p = products[i];
                html += `
                    <div class="compare-item" data-id="${p.id}">
                        <img src="${p.thumbnail}" alt="${p.name}" title="${p.name}">
                        <span class="compare-item-remove" data-id="${p.id}"><i class="bi bi-x-circle-fill"></i></span>
                    </div>
                `;
            } else {
                html += `
                    <div class="compare-item empty">
                        <i class="bi bi-plus"></i>
                    </div>
                `;
            }
        }
        itemsContainer.innerHTML = html;
    }
    
    // Update all compare buttons on the page to match active state
    document.querySelectorAll('.compare-btn').forEach(btn => {
        const id = parseInt(btn.dataset.id);
        const inCompare = products && products.some(p => p.id === id);
        const icon = btn.querySelector('i');
        
        if (inCompare) {
            btn.classList.add('active');
            if (icon) {
                icon.className = 'bi bi-arrow-left-right small text-warning';
            }
        } else {
            btn.classList.remove('active');
            if (icon) {
                icon.className = 'bi bi-arrow-left-right small';
            }
        }
    });
}

document.addEventListener('click', function(e) {
    // 1. Click on compare button
    const compareBtn = e.target.closest('.compare-btn');
    if (compareBtn) {
        e.preventDefault();
        const id = compareBtn.dataset.id;
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrf) return;
        
        const isDelete = compareBtn.classList.contains('active');
        const url = isDelete ? '{{ route("products.compare.remove") }}' : '{{ route("products.compare.add") }}';
        
        compareBtn.disabled = true;
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_id: id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showLuxuryToast(data.message, 'success');
                updateCompareBar(data.products, data.count);
            } else {
                showLuxuryToast(data.message, data.status || 'error');
            }
        })
        .catch(() => showLuxuryToast('Có lỗi xảy ra, vui lòng thử lại!', 'error'))
        .finally(() => { compareBtn.disabled = false; });
        return;
    }
    
    // 2. Click on remove item from compare bar
    const removeBtn = e.target.closest('.compare-item-remove');
    if (removeBtn) {
        e.preventDefault();
        const id = removeBtn.dataset.id;
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrf) return;
        
        fetch('{{ route("products.compare.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_id: id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showLuxuryToast(data.message, 'success');
                updateCompareBar(data.products, data.count);
                
                // If we are on the compare page, reload or hide column
                if (window.location.pathname.includes('/so-sanh')) {
                    window.location.reload();
                }
            }
        });
        return;
    }
});

// 3. Clear all comparison
const clearCompareBtn = document.getElementById('compare-clear-btn');
if (clearCompareBtn) {
    clearCompareBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrf) return;
        
        fetch('{{ route("products.compare.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showLuxuryToast(data.message, 'success');
                updateCompareBar([], 0);
                
                if (window.location.pathname.includes('/so-sanh')) {
                    window.location.reload();
                }
            }
        });
    });
}

// Helper to show a beautiful glassmorphic toast notification
function showLuxuryToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert-luxury shadow-lg d-flex align-items-center gap-2`;
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.padding = '12px 24px';
    toast.style.borderRadius = '12px';
    toast.style.color = '#fff';
    toast.style.fontSize = '0.88rem';
    toast.style.fontWeight = '600';
    toast.style.transition = 'all 0.4s ease';
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-10px)';
    toast.style.backdropFilter = 'blur(10px)';
    toast.style.webkitBackdropFilter = 'blur(10px)';
    
    let bg = 'rgba(10, 10, 15, 0.95)';
    let border = '1px solid rgba(201, 168, 76, 0.3)';
    let icon = 'bi-check-circle-fill text-warning';
    
    if (type === 'error') {
        bg = 'rgba(233, 69, 96, 0.95)';
        border = '1px solid rgba(233, 69, 96, 0.3)';
        icon = 'bi-exclamation-triangle-fill text-white';
    } else if (type === 'info') {
        bg = 'rgba(10, 10, 15, 0.95)';
        border = '1px solid rgba(255, 255, 255, 0.2)';
        icon = 'bi-info-circle-fill text-info';
    }
    
    toast.style.background = bg;
    toast.style.border = border;
    
    toast.innerHTML = `<i class="bi ${icon}"></i> <span>${message}</span>`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 50);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

// Global AJAX Add to Cart Handler
let lastSubmitterFormAction = null;
document.addEventListener('click', function(e) {
    const btn = e.target.closest('[formaction]');
    lastSubmitterFormAction = btn ? btn.getAttribute('formaction') : null;
});

document.addEventListener('submit', function(e) {
    const form = e.target.closest('#addToCartForm, .add-to-cart-form');
    if (!form) return;
    
    // If submit has a custom formaction (e.g. Mua Ngay), submit normally
    if (lastSubmitterFormAction) {
        return;
    }
    
    e.preventDefault();
    
    const formData = new FormData(form);
    const action = form.getAttribute('action');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    
    const btn = form.querySelector('button[type="submit"]');
    if (btn) btn.disabled = true;
    
    fetch(action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => { throw new Error(err.message || 'Có lỗi xảy ra!'); });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            if (typeof showLuxuryToast === 'function') {
                showLuxuryToast(data.message, 'success');
            } else {
                alert(data.message);
            }
            
            // Update cart badge in header
            const cartLink = document.querySelector('a[href*="gio-hang"]');
            if (cartLink) {
                let badge = cartLink.querySelector('.cart-badge');
                if (badge) {
                    badge.textContent = data.cart_count;
                    badge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                } else if (data.cart_count > 0) {
                    badge = document.createElement('span');
                    badge.className = 'cart-badge';
                    badge.textContent = data.cart_count;
                    cartLink.appendChild(badge);
                }
            }
        } else {
            if (typeof showLuxuryToast === 'function') {
                showLuxuryToast(data.message, 'error');
            } else {
                alert(data.message);
            }
        }
    })
    .catch(err => {
        let errMsg = err.message;
        if (errMsg === 'Unauthenticated.') {
            errMsg = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!';
        }
        if (typeof showLuxuryToast === 'function') {
            showLuxuryToast(errMsg, 'error');
        } else {
            alert(errMsg);
        }
    })
    .finally(() => {
        if (btn) btn.disabled = false;
    });
});

// Dropdown link navigation on desktop
document.querySelectorAll('.navbar-main .dropdown-toggle').forEach(el => {
    el.addEventListener('click', function(e) {
        if (window.innerWidth >= 992) {
            const href = this.getAttribute('href');
            if (href && href !== '#') {
                window.location.href = href;
            }
        }
    });
});
</script>

@stack('scripts')
</body>
</html>