<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Đồng Hồ Online</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #0b0b14;
            --sidebar-width: 260px;
            --gold: #c9a84c;
            --gold-light: #e8c97a;
            --gold-dark: #a07c30;
            --border-color: rgba(201, 168, 76, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body { 
            background: #f8f9fc; 
            font-family: 'Inter', sans-serif;
            color: #2b303a;
        }

        /* ===== PREMIUM SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #0b0b14 0%, #111122 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: var(--transition);
            border-right: 1px solid var(--border-color);
        }
        
        /* Premium custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(11, 11, 20, 0.5);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gold-dark);
            border-radius: 3px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--gold);
        }

        .sidebar-brand {
            padding: 20px 20px;
            border-bottom: 1px solid var(--border-color);
            color: var(--gold);
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar .nav-link {
            color: rgba(240, 236, 224, 0.65);
            padding: 9px 24px;
            border-radius: 0;
            transition: var(--transition);
            font-size: 0.88rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar .nav-link:hover {
            color: var(--gold-light);
            background: rgba(201, 168, 76, 0.06);
            padding-left: 28px;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(90deg, rgba(201, 168, 76, 0.15) 0%, transparent 100%);
            border-left: 3px solid var(--gold);
            padding-left: 28px;
            font-weight: 600;
        }
        .sidebar .nav-link i { 
            font-size: 1.1rem; 
            color: rgba(240, 236, 224, 0.45);
            transition: var(--transition);
        }
        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            color: var(--gold);
        }
        .sidebar .nav-section {
            color: rgba(201, 168, 76, 0.45);
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 15px 24px 4px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
        }

        /* ===== PREMIUM TOPBAR ===== */
        .admin-topbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 16px 28px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }

        /* ===== PAGE CONTENT ===== */
        .page-content { padding: 28px; }

        /* ===== DESIGN SYSTEMS & BOOTSTRAP OVERRIDES ===== */
        /* Table Premium styles */
        .table-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            overflow: hidden;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.03);
        }
        .table-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f2f4f8;
            font-weight: 700;
            padding: 20px 24px;
            color: #1a1a2e;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.76rem;
            letter-spacing: 0.5px;
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        .table tbody td {
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.86rem;
        }
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(201, 168, 76, 0.03) !important;
        }

        /* Badge mờ sang trọng */
        .badge {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.72rem;
            letter-spacing: 0.3px;
        }

        /* Form Premium styling */
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 0.88rem;
            transition: var(--transition);
            background-color: #fff;
            color: #1e293b;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.12);
            background-color: #fff;
            outline: none;
        }
        .form-control-sm, .form-select-sm {
            padding: 6px 12px;
            font-size: 0.82rem;
            border-radius: 8px;
        }
        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
        }

        /* Premium Buttons */
        .btn {
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 0.86rem;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-sm {
            padding: 6px 14px;
            font-size: 0.8rem;
            border-radius: 8px;
        }
        .btn-dark {
            background: linear-gradient(135deg, #0b0b14, #1a1a2e);
            border: 1px solid var(--border-color);
            color: var(--gold-light);
        }
        .btn-dark:hover {
            background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(201,168,76,0.15);
        }
        .btn-warning {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border: none;
            color: #0b0b14;
        }
        .btn-warning:hover {
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
            color: #0b0b14;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(201,168,76,0.3);
        }

        /* STATS CARD */
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.02);
            transition: var(--transition);
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }
        .stat-card:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 12px 28px rgba(0,0,0,0.06);
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            transition: var(--transition);
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.08) rotate(3deg);
        }
        #adminAiChat .resize-handle:hover, #adminAiChat .resize-handle.active {
            background: rgba(201, 168, 76, 0.4);
        }

        /* RESPONSIVE MOBILE sidebar */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-clock me-2"></i>ADMIN PANEL
    </div>

    <ul class="nav flex-column mt-2">
        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        {{-- Quản lý sản phẩm --}}
        <li class="nav-section">Sản Phẩm</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
               href="{{ route('admin.products.index') }}">
                <i class="bi bi-watch me-2"></i> Sản Phẩm
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
               href="{{ route('admin.categories.index') }}">
                <i class="bi bi-grid me-2"></i> Danh Mục
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}"
               href="{{ route('admin.brands.index') }}">
                <i class="bi bi-tag me-2"></i> Thương Hiệu
            </a>
        </li>

        {{-- Kinh doanh --}}
        <li class="nav-section">Kinh Doanh</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
               href="{{ route('admin.orders.index') }}">
                <i class="bi bi-bag me-2"></i> Đơn Hàng
                @php
                    $pendingOrders = \App\Models\Order::where('status','pending')->count();
                @endphp
                @if($pendingOrders > 0)
                    <span class="badge bg-danger float-end">{{ $pendingOrders }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
               href="{{ route('admin.coupons.index') }}">
                <i class="bi bi-ticket-perforated me-2"></i> Mã Giảm Giá
            </a>
        </li>

        {{-- Nội dung --}}
        <li class="nav-section">Nội Dung</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"
               href="{{ route('admin.banners.index') }}">
                <i class="bi bi-image me-2"></i> Banner
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
               href="{{ route('admin.news.index') }}">
                <i class="bi bi-newspaper me-2"></i> Tin Tức
            </a>
        </li>

        {{-- Cộng đồng --}}
        <li class="nav-section">Cộng Đồng</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               href="{{ route('admin.users.index') }}">
                <i class="bi bi-people me-2"></i> Người Dùng
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
               href="{{ route('admin.reviews.index') }}">
                <i class="bi bi-star me-2"></i> Đánh Giá
                @php $pendingReviews = \App\Models\Review::where('status','pending')->count(); @endphp
                @if($pendingReviews > 0)
                    <span class="badge bg-warning text-dark float-end">{{ $pendingReviews }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}"
               href="{{ route('admin.comments.index') }}">
                <i class="bi bi-chat me-2"></i> Bình Luận
                @php $pendingComments = \App\Models\Comment::where('status','pending')->count(); @endphp
                @if($pendingComments > 0)
                    <span class="badge bg-warning text-dark float-end">{{ $pendingComments }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
               href="{{ route('admin.contacts.index') }}">
                <i class="bi bi-envelope-heart me-2"></i> Liên Hệ
                @php $newContacts = \App\Models\Contact::where('status','new')->count(); @endphp
                @if($newContacts > 0)
                    <span class="badge bg-danger float-end">{{ $newContacts }}</span>
                @endif
            </a>
        </li>

        <li class="nav-section">Hệ Thống</li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}"
               href="{{ route('admin.activity-logs.index') }}">
                <i class="bi bi-clock-history me-2"></i> Nhật Ký Hoạt Động
            </a>
        </li>

        <li class="nav-section">Khác</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                <i class="bi bi-box-arrow-up-right me-2"></i> Xem Website
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="#"
               onclick="document.getElementById('admin-logout').submit()">
                <i class="bi bi-box-arrow-right me-2"></i> Đăng Xuất
            </a>
            <form id="admin-logout" method="POST" action="{{ route('logout') }}" class="d-none">@csrf</form>
        </li>
    </ul>
</nav>

{{-- MAIN CONTENT --}}
<div class="main-content">

    {{-- TOPBAR --}}
    <div class="admin-topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none"
                    onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <h6 class="mb-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-dark">
                <i class="bi bi-shop me-1"></i> Về trang khách
            </a>
            <span class="text-muted small">
                <i class="bi bi-person-circle me-1"></i>
                {{ auth()->user()->name }}
            </span>
        </div>
    </div>

    {{-- FLASH --}}
    @if(session('success') || session('error'))
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-x-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
    @endif

    {{-- NỘI DUNG --}}
    <div class="page-content">
        @yield('content')
    </div>
</div>

{{-- AI ASSISTANT BUBBLE (ADMIN ONLY) --}}
<button id="aiBubble" class="btn btn-warning shadow-lg position-fixed"
        style="bottom:24px; right:24px; width:56px; height:56px; border-radius:50%; z-index:1050;"
        type="button" data-bs-toggle="offcanvas" data-bs-target="#adminAiChat" aria-controls="adminAiChat">
    <i class="bi bi-robot fs-4"></i>
</button>

<div class="offcanvas offcanvas-end" tabindex="-1" id="adminAiChat" style="width:420px;">
    <!-- Drag resize handle -->
    <div class="resize-handle" style="position: absolute; left: 0; top: 0; bottom: 0; width: 6px; cursor: ew-resize; z-index: 1060; transition: background 0.2s;"></div>
    <div class="offcanvas-header bg-dark text-warning">
        <h6 class="offcanvas-title fw-bold mb-0">
            <i class="bi bi-robot me-1"></i> Trợ Lý Phân Tích (Admin)
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column" style="background:#f8f9fa;">
        <div id="adminChatMessages" class="flex-grow-1 overflow-auto p-3" style="min-height:400px;">
            <div class="text-center text-muted small py-3">
                <i class="bi bi-info-circle me-1"></i>
                Hỏi về doanh thu, đơn hàng, tồn kho, top sản phẩm…
                <div class="mt-2 d-flex flex-wrap gap-1 justify-content-center">
                    <button class="btn btn-sm btn-outline-secondary admin-suggest"
                            data-q="Doanh thu tháng này bao nhiêu?">Doanh thu tháng này</button>
                    <button class="btn btn-sm btn-outline-secondary admin-suggest"
                            data-q="Có sản phẩm nào sắp hết hàng không?">Tồn kho thấp</button>
                    <button class="btn btn-sm btn-outline-secondary admin-suggest"
                            data-q="Top 5 sản phẩm bán chạy">Top bán chạy</button>
                    <button class="btn btn-sm btn-outline-secondary admin-suggest"
                            data-q="Có bao nhiêu khách hàng mới tháng này?">Khách mới</button>
                </div>
            </div>
        </div>
        <div class="border-top p-2 bg-white">
            <form id="adminChatForm" class="d-flex gap-2">
                <input type="text" id="adminChatInput" class="form-control"
                       placeholder="Hỏi về số liệu kinh doanh…" autocomplete="off" maxlength="500">
                <button type="submit" class="btn btn-warning" id="adminChatSend">
                    <i class="bi bi-send"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
(function () {
    const messagesEl = document.getElementById('adminChatMessages');
    const inputEl = document.getElementById('adminChatInput');
    const formEl = document.getElementById('adminChatForm');
    const sendBtn = document.getElementById('adminChatSend');
    const history = []; // {role, content}

    function appendMsg(role, content) {
        const wrap = document.createElement('div');
        wrap.className = 'mb-2 d-flex ' + (role === 'user' ? 'justify-content-end' : 'justify-content-start');
        const bubble = document.createElement('div');
        bubble.className = role === 'user'
            ? 'px-3 py-2 rounded-3 bg-dark text-white small'
            : 'px-3 py-2 rounded-3 bg-white border small';
        bubble.style.maxWidth = '80%';
        bubble.style.whiteSpace = 'pre-wrap';
        bubble.textContent = content;
        wrap.appendChild(bubble);
        messagesEl.appendChild(wrap);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    async function send(message) {
        const msg = (message || inputEl.value).trim();
        if (!msg) return;
        appendMsg('user', msg);
        history.push({ role: 'user', content: msg });
        inputEl.value = '';
        sendBtn.disabled = true;

        const typing = document.createElement('div');
        typing.className = 'mb-2 d-flex justify-content-start';
        typing.innerHTML = '<div class="px-3 py-2 rounded-3 bg-white border small text-muted">Đang phân tích…</div>';
        messagesEl.appendChild(typing);
        messagesEl.scrollTop = messagesEl.scrollHeight;

        try {
            const res = await fetch('{{ route('admin.ai.chat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ message: msg, history: history.slice(-6) }),
            });
            const data = await res.json();
            typing.remove();
            if (data.success) {
                appendMsg('assistant', data.response);
                history.push({ role: 'assistant', content: data.response });
            } else {
                appendMsg('assistant', 'Có lỗi xảy ra. Vui lòng thử lại.');
            }
        } catch (e) {
            typing.remove();
            appendMsg('assistant', 'Không kết nối được tới server.');
        } finally {
            sendBtn.disabled = false;
        }
    }

    formEl.addEventListener('submit', e => { e.preventDefault(); send(); });
    document.querySelectorAll('.admin-suggest').forEach(b =>
        b.addEventListener('click', () => send(b.dataset.q)));

    // ── CHATBOT RESIZE DRAG LOGIC ──
    (function() {
        const chatbot = document.getElementById('adminAiChat');
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
})();
</script>

<script>
// Tự động ẩn alert
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => bootstrap.Alert.getOrCreateInstance(el)?.close(), 4000);
});

// Confirm xoá
function confirmDelete(formId) {
    if (confirm('Bạn chắc chắn muốn xoá?')) {
        document.getElementById(formId).submit();
    }
}
</script>

@if(session('download_invoice_id'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        const url = "{{ route('admin.products.invoice', session('download_invoice_id')) }}";
        const newWindow = window.open(url, '_blank');
        if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') {
            // Popup blocker triggered
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show mx-4 mt-3';
            alertDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Trình duyệt của bạn đã chặn popup tự động. 
                <a href="${url}" target="_blank" class="fw-bold text-decoration-underline text-dark">Bấm vào đây để tải hóa đơn nhập hàng PDF của sản phẩm</a>.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            const mainContentEl = document.querySelector('.main-content');
            if (mainContentEl) {
                mainContentEl.insertBefore(alertDiv, mainContentEl.children[1] || mainContentEl.firstChild);
            }
        }
    });
</script>
@endif

@stack('scripts')
</body>
</html>