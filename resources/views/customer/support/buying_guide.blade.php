@extends('layouts.customer')
@section('title', 'Hướng Dẫn Mua Hàng')

@push('styles')
<style>
    /* ── Hero ── */
    .support-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
        border-bottom: 1px solid rgba(201,168,76,0.18);
    }
    .support-hero h1 {
        font-family: 'Playfair Display', serif;
        color: #f0ece0;
    }
    .support-hero .breadcrumb-item a { color: var(--gold); text-decoration: none; }
    .support-hero .breadcrumb-item.active { color: rgba(240,236,224,0.45); }
    .support-hero .breadcrumb-item+.breadcrumb-item::before { color: rgba(201,168,76,0.35); }

    /* ── Page bg ── */
    .support-page { background: #f5f4f0; }

    /* ── Steps cards ── */
    .step-card {
        transition: all 0.22s;
    }
    .step-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important;
    }
    .step-badge {
        font-family: 'Playfair Display', serif;
        color: rgba(201,168,76,0.15);
    }
    .step-icon {
        background: rgba(201,168,76,0.12);
        color: var(--gold-dark);
        border: 1px solid rgba(201,168,76,0.22);
    }
    .step-title {
        font-family: 'Playfair Display', serif;
    }
</style>
@endpush

@section('content')

{{-- ── Hero ── --}}
<div class="support-hero py-4">
    <div class="container py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 fs-8">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hướng dẫn mua hàng</li>
            </ol>
        </nav>
        <h1 class="fs-3 fw-bold mb-1"><i class="bi bi-cart-check me-2 align-middle" style="color:var(--gold); font-size:1.3rem;"></i>Hướng Dẫn Mua Hàng</h1>
        <p class="text-white-50 fs-7 mb-0">Quy trình mua sắm trực tuyến chuyên nghiệp, an toàn và dễ thực hiện</p>
    </div>
</div>

<div class="support-page py-5">
    <div class="container">
        <div class="row g-4">
            
            {{-- Main Content --}}
            <div class="col-lg-8">
                
                {{-- Step 1 --}}
                <div class="step-card card border-0 rounded-4 shadow-sm p-4 mb-4 position-relative">
                    <span class="step-badge position-absolute top-0 end-0 m-4 fs-1 fw-black lh-1 text-gold-50">01</span>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="step-icon rounded-3 d-flex align-items-center justify-content-center fs-5" style="width: 38px; height: 38px;"><i class="bi bi-search"></i></div>
                        <h4 class="step-title text-dark fw-bold mb-0 fs-5">Bước 1: Tìm Kiếm & Lựa Chọn Đồng Hồ</h4>
                    </div>
                    <div class="text-muted fs-7 lh-lg">
                        Truy cập website Đồng Hồ Online, bạn có thể dễ dàng tìm kiếm sản phẩm theo nhiều cách linh hoạt:
                        <ul class="ps-3 mt-2 mb-0">
                            <li class="mb-1">Nhấp chọn danh mục <strong>"Đồng hồ nam"</strong>, <strong>"Đồng hồ nữ"</strong> hoặc <strong>"Đồng hồ treo tường"</strong> trên thanh menu điều hướng.</li>
                            <li class="mb-1">Sử dụng thanh <strong>Tìm kiếm thông minh</strong> ở trên cùng, nhập tên sản phẩm, mã model hoặc thương hiệu yêu thích (như Citizen, Casio, Seiko, Tissot...).</li>
                            <li class="mb-0">Sử dụng tính năng <strong>Bộ lọc nâng cao</strong> để giới hạn khoảng giá mong muốn, chất liệu dây (dây da, dây kim loại) và loại máy (Automatic, Quartz).</li>
                        </ul>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="step-card card border-0 rounded-4 shadow-sm p-4 mb-4 position-relative">
                    <span class="step-badge position-absolute top-0 end-0 m-4 fs-1 fw-black lh-1 text-gold-50">02</span>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="step-icon rounded-3 d-flex align-items-center justify-content-center fs-5" style="width: 38px; height: 38px;"><i class="bi bi-journal-text"></i></div>
                        <h4 class="step-title text-dark fw-bold mb-0 fs-5">Bước 2: Xem Chi Tiết & Thêm Vào Giỏ Hàng</h4>
                    </div>
                    <div class="text-muted fs-7 lh-lg">
                        Nhấp trực tiếp vào hình ảnh hoặc tên đồng hồ để mở trang chi tiết sản phẩm:
                        <ul class="ps-3 mt-2 mb-0">
                            <li class="mb-1">Đọc kỹ mô tả chi tiết, kích cỡ mặt số (mm), độ chịu nước và các thông số kỹ thuật cao cấp của sản phẩm.</li>
                            <li class="mb-1">Xem hình ảnh thực tế được chụp sắc nét ở các góc cạnh khác nhau.</li>
                            <li class="mb-0">Nếu hài lòng, nhấp nút <strong>"Thêm vào giỏ hàng"</strong> để tiếp tục mua sắm các mẫu khác, hoặc nhấp <strong>"Mua ngay"</strong> để đi thẳng tới trang thanh toán.</li>
                        </ul>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="step-card card border-0 rounded-4 shadow-sm p-4 mb-4 position-relative">
                    <span class="step-badge position-absolute top-0 end-0 m-4 fs-1 fw-black lh-1 text-gold-50">03</span>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="step-icon rounded-3 d-flex align-items-center justify-content-center fs-5" style="width: 38px; height: 38px;"><i class="bi bi-cart3"></i></div>
                        <h4 class="step-title text-dark fw-bold mb-0 fs-5">Bước 3: Kiểm Tra Giỏ Hàng & Áp Mã Coupon</h4>
                    </div>
                    <div class="text-muted fs-7 lh-lg">
                        Truy cập biểu tượng Giỏ hàng trên thanh công cụ góc phải màn hình:
                        <ul class="ps-3 mt-2 mb-0">
                            <li class="mb-1">Kiểm tra chính xác số lượng sản phẩm cần mua và tổng số tiền đơn hàng tạm tính.</li>
                            <li class="mb-1">Nhập mã giảm giá (nếu có) vào ô <strong>"Mã giảm giá/Coupon"</strong> và nhấn áp dụng để được khấu trừ chiết khấu ưu đãi trực tiếp.</li>
                            <li class="mb-0">Nhấn nút <strong>"Tiến hành đặt thanh toán"</strong> để chuyển sang bước điền thông tin giao nhận.</li>
                        </ul>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="step-card card border-0 rounded-4 shadow-sm p-4 mb-4 position-relative">
                    <span class="step-badge position-absolute top-0 end-0 m-4 fs-1 fw-black lh-1 text-gold-50">04</span>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="step-icon rounded-3 d-flex align-items-center justify-content-center fs-5" style="width: 38px; height: 38px;"><i class="bi bi-wallet2"></i></div>
                        <h4 class="step-title text-dark fw-bold mb-0 fs-5">Bước 4: Nhập Thông Tin Nhận Hàng & Thanh Toán</h4>
                    </div>
                    <div class="text-muted fs-7 lh-lg">
                        Tại đây, quý khách vui lòng nhập đầy đủ thông tin để bưu tá giao hàng thuận lợi nhất:
                        <ul class="ps-3 mt-2 mb-0">
                            <li class="mb-1">Họ tên người nhận, Số điện thoại chính xác, Tỉnh/Thành phố, Quận/Huyện, Địa chỉ số nhà/Đường phố cụ thể.</li>
                            <li class="mb-0">Chọn một trong ba phương thức thanh toán an toàn:
                                <ul class="ps-3 mt-1 mb-0">
                                    <li class="mb-1"><strong>COD (Giao hàng thu tiền):</strong> Nhận đồng hồ, kiểm tra hàng đầy đủ rồi thanh toán tiền mặt cho nhân viên giao hàng.</li>
                                    <li class="mb-1"><strong>QR Chuyển Khoản Ngân Hàng:</strong> Hệ thống tự động tạo mã QR có sẵn số tiền và nội dung chuyển khoản, an toàn và chính xác tuyệt đối.</li>
                                    <li class="mb-0"><strong>Thanh Toán Online (VNPAY):</strong> Kết nối trực tiếp đến ứng dụng ngân hàng di động hoặc thẻ quốc tế để thanh toán ngay lập tức.</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Step 5 --}}
                <div class="step-card card border-0 rounded-4 shadow-sm p-4 mb-4 position-relative">
                    <span class="step-badge position-absolute top-0 end-0 m-4 fs-1 fw-black lh-1 text-gold-50">05</span>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="step-icon rounded-3 d-flex align-items-center justify-content-center fs-5" style="width: 38px; height: 38px;"><i class="bi bi-truck"></i></div>
                        <h4 class="step-title text-dark fw-bold mb-0 fs-5">Bước 5: Xác Nhận Đơn Hàng & Theo Dõi Hành Trình</h4>
                    </div>
                    <div class="text-muted fs-7 lh-lg">
                        Sau khi hoàn thành đặt hàng thành công:
                        <ul class="ps-3 mt-2 mb-0">
                            <li class="mb-1">Hệ thống tự động gửi Email xác nhận chi tiết đơn hàng kèm hóa đơn VAT điện tử (nếu có yêu cầu).</li>
                            <li class="mb-1">Nhân viên CSKH của chúng tôi sẽ liên hệ trực tiếp qua điện thoại trong vòng 10 phút để xác thực lại thông tin địa chỉ trước khi bàn giao cho đơn vị chuyển phát.</li>
                            <li class="mb-0">Qúy khách có thể tra cứu trạng thái đơn hàng của mình bất cứ lúc nào trong mục <strong>"Lịch sử đơn hàng"</strong> của tài khoản cá nhân.</li>
                        </ul>
                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Phương thức vận chuyển --}}
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                    <h4 class="fw-bold fs-7 text-uppercase text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                        <i class="bi bi-truck-flatbed text-gold"></i>Phương Thức Giao Hàng
                    </h4>
                    <div class="mb-3">
                        <span class="fw-bold fs-7 text-dark d-block"><i class="bi bi-lightning-fill me-1 text-gold"></i>1. Giao hàng hỏa tốc (2H)</span>
                        <p class="fs-8 text-muted ms-3 mt-1 mb-0">Áp dụng cho đơn hàng nội thành Hà Nội. Giao hàng nhanh bằng shipper riêng trong vòng 2 giờ kể từ khi duyệt đơn.</p>
                    </div>
                    <div>
                        <span class="fw-bold fs-7 text-dark d-block"><i class="bi bi-box-seam-fill me-1 text-gold"></i>2. Chuyển phát nhanh (1-3 Ngày)</span>
                        <p class="fs-8 text-muted ms-3 mt-1 mb-0">Giao hàng toàn quốc thông qua đối tác vận chuyển chuyên nghiệp Viettel Post, GHTK. Có mã định vị đơn hàng theo thời gian thực.</p>
                    </div>
                </div>

                {{-- Cam kết mua sắm --}}
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                    <h4 class="fw-bold fs-7 text-uppercase text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                        <i class="bi bi-patch-check-fill text-gold"></i>Cam Kết Mua Sắm
                    </h4>
                    <ul class="list-unstyled fs-8 text-muted mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-gold"></i>Đồng hồ chính hãng 100% đầy đủ phụ kiện hãng.</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill me-2 text-gold"></i>Cho phép mở hộp kiểm tra hàng trước khi thanh toán.</li>
                        <li class="mb-0"><i class="bi bi-check-circle-fill me-2 text-gold"></i>Hỗ trợ tư vấn size mặt và mẫu mã 24/7 hoàn toàn miễn phí.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
