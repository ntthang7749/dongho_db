@extends('layouts.customer')
@section('title', 'Bảo Hành Sản Phẩm')

@push('styles')
<style>
    /* ── Hero Gradient ── */
    .support-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
        border-bottom: 1px solid rgba(201,168,76,0.18);
    }
    .support-hero h1 {
        font-family: 'Playfair Display', serif;
    }
    
    /* ── Card & Headers ── */
    .support-card-header {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
    }
    .support-card-icon {
        width: 44px; height: 44px;
        background: rgba(201,168,76,0.18);
        border: 1px solid rgba(201,168,76,0.3);
        color: var(--gold);
    }
    .support-card-title, .support-card-body h5, .badge-details h4 {
        font-family: 'Playfair Display', serif;
    }

    /* ── Warranty Badge ── */
    .badge-icon {
        width: 70px; height: 70px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        box-shadow: 0 4px 15px rgba(201,168,76,0.4);
    }

    /* ── Timeline ── */
    .process-timeline {
        position: relative;
        padding-left: 24px;
        border-left: 2px dashed rgba(201,168,76,0.3);
        margin-left: 12px;
    }
    .process-step {
        position: relative;
    }
    .process-step-num {
        position: absolute;
        left: -35px;
        top: 2px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--gold);
        color: #0a0a0f;
        font-size: 0.75rem;
        font-weight: 700;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(201,168,76,0.4);
    }
</style>
@endpush

@section('content')

{{-- ── Hero ── --}}
<div class="support-hero py-4 mb-0">
    <div class="container py-2">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.79rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--gold);"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item active text-white-50" aria-current="page">Bảo hành sản phẩm</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-white mb-1">
            <i class="bi bi-patch-check me-2" style="color: var(--gold); font-size: 1.3rem; vertical-align: middle;"></i>Chế Độ Bảo Hành
        </h1>
        <p class="text-white-50 mb-0 small">Chế độ bảo hành Vàng dài hạn tối đa dành riêng cho chủ nhân đồng hồ cao cấp</p>
    </div>
</div>

<div class="py-5" style="background: #f5f4f0;">
    <div class="container">
        <div class="row g-4">
            
            {{-- Main Content --}}
            <div class="col-lg-8">
                <div class="bg-white rounded-4 border border-light-subtle shadow-sm overflow-hidden mb-4">
                    <div class="support-card-header p-4 d-flex align-items-center gap-3">
                        <div class="support-card-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 fs-5"><i class="bi bi-shield-check"></i></div>
                        <h2 class="support-card-title h5 text-white fw-bold m-0">Chính Sách Bảo Hành & Bảo Dưỡng Sản Phẩm</h2>
                    </div>
                    <div class="p-4 text-secondary lh-lg fs-6" style="font-size: 0.9rem;">
                        
                        {{-- Gold Warranty Badge --}}
                        <div class="d-flex align-items-center gap-3 rounded-4 p-4 mb-4 border border-warning-subtle" style="background: #0a0a0f; color: #f0ece0;">
                            <div class="badge-icon rounded-circle d-flex align-items-center justify-content-center fw-extrabold fs-2 flex-shrink-0">5Y</div>
                            <div class="badge-details">
                                <h4 class="h5 fw-bold mb-1" style="color: var(--gold);">Chương Trình BẢO HÀNH VÀNG 5 NĂM</h4>
                                <p class="m-0 text-white-50 small" style="font-size: 0.8rem;">Độc quyền bảo dưỡng & chăm sóc toàn diện tại trung tâm bảo hành của Đồng Hồ Online. Trọn vẹn an tâm sử dụng trong suốt nửa thập kỷ.</p>
                            </div>
                        </div>

                        <h5 class="text-dark fw-bold h6 mt-4 mb-3 d-flex align-items-center gap-2"><i class="bi bi-dot text-warning fs-4"></i>1. Phạm Vi & Thời Hạn Bảo Hành</h5>
                        <ul class="ps-3 mb-4">
                            <li class="mb-2"><strong>Bảo hành máy đồng hồ:</strong> Lên tới **5 năm** kể từ ngày mua hàng đối với tất cả lỗi liên quan tới hệ thống cơ học, hệ thống truyền động, IC điều khiển điện tử và bộ dao động thạch anh (Quartz) của đồng hồ.</li>
                            <li class="mb-2"><strong>Chính sách Thay Pin miễn phí TRỌN ĐỜI:</strong> Áp dụng đối với tất cả dòng đồng hồ sử dụng Pin (Quartz) được mua trực tiếp tại hệ thống của chúng tôi.</li>
                            <li class="mb-2"><strong>Lau dầu bảo dưỡng miễn phí:</strong> Miễn phí lau dầu, căn chỉnh độ sai số cho các dòng đồng hồ cơ (Automatic) trong vòng <strong>3 năm đầu tiên</strong> sử dụng.</li>
                        </ul>

                        <h5 class="text-dark fw-bold h6 mt-4 mb-3 d-flex align-items-center gap-2"><i class="bi bi-dot text-warning fs-4"></i>2. Điều Kiện Nhận Bảo Hành Miễn Phí</h5>
                        <p class="mb-3 text-secondary">Chúng tôi chỉ chấp nhận bảo hành miễn phí đối với các trường hợp đảm bảo:</p>
                        <ul class="ps-3 mb-4">
                            <li class="mb-2">Đồng hồ vẫn đang trong thời hạn bảo hành 5 năm của hệ thống.</li>
                            <li class="mb-2">Khách hàng xuất trình được Thẻ Bảo Hành của hãng hoặc Thẻ Bảo Hành Vàng do Đồng Hồ Online cấp (còn rõ mã số sê-ri, ngày mua hàng, chữ ký và đóng dấu giáp lai của cửa hàng).</li>
                            <li class="mb-2">Các lỗi thuộc về linh kiện bên trong hoặc lỗi kỹ thuật phát sinh trong quá trình sản xuất bình thường của sản phẩm.</li>
                        </ul>

                        <h5 class="text-dark fw-bold h6 mt-4 mb-3 d-flex align-items-center gap-2"><i class="bi bi-dot text-warning fs-4"></i>3. Các Trường Hợp Không Thuộc Phạm Vi Bảo Hành Miễn Phí</h5>
                        <p class="mb-3 text-secondary">Qúy khách lưu ý những trường hợp sau đây sẽ **không** được hỗ trợ bảo hành miễn phí mà chỉ được sửa chữa tính phí dịch vụ ưu đãi (giảm giá 30% linh kiện):</p>
                        <ul class="ps-3 mb-0">
                            <li class="mb-2">Sản phẩm đã hết thời hạn bảo hành ghi trên thẻ.</li>
                            <li class="mb-2">Các lỗi hư hỏng bên ngoài do tai nạn vật lý hoặc tự ý tác động cơ học như: mặt kính bị trầy xước, nứt vỡ; nứt vỏ đồng hồ; móp méo núm vặn; xước dăm lớp mạ do cọ xát với chìa khóa hay vật cứng khác.</li>
                            <li class="mb-2">Mòn mỏi hoặc đứt hỏng dây đeo tự nhiên (ví dụ dây da bị mủn do tiếp xúc nhiều với mồ hôi, hóa chất; bay màu lớp mạ dây kim loại theo năm tháng).</li>
                            <li class="mb-2">Hư hại do sử dụng núm vặn sai cách (quên không khóa núm ren vặn làm nước rò rỉ vào khoang máy hoặc chỉnh ngày giờ vào cung giờ cấm từ 22:00 - 4:00 sáng làm gãy bánh xe lịch).</li>
                            <li class="mb-2">Hư hỏng do sử dụng đồng hồ vượt quá chỉ số chịu nước in trên máy (ví dụ tắm nước nóng, xông hơi, đi bơi biển sâu đối với đồng hồ không chuyên dụng).</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Quy trình tiếp nhận --}}
                <div class="bg-white rounded-4 border border-light-subtle shadow-sm p-4 mb-4">
                    <h4 class="h6 text-dark fw-bold text-uppercase mb-4 d-flex align-items-center gap-2" style="letter-spacing: 0.5px;">
                        <i class="bi bi-card-checklist" style="color: var(--gold);"></i>Quy Trình Tiếp Nhận
                    </h4>
                    <div class="process-timeline">
                        <div class="process-step mb-4">
                            <span class="process-step-num">1</span>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Kiểm tra thông tin</div>
                            <div class="text-secondary" style="font-size: 0.78rem; line-height: 1.45;">Khách hàng mang sản phẩm kèm thẻ bảo hành trực tiếp tới trung tâm bảo dưỡng hoặc gửi qua bưu điện.</div>
                        </div>
                        <div class="process-step mb-4">
                            <span class="process-step-num">2</span>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Giám định & Kiểm tra máy</div>
                            <div class="text-secondary" style="font-size: 0.78rem; line-height: 1.45;">Kỹ thuật viên sử dụng máy đo chuyên dụng của Thụy Sĩ để kiểm định chính xác sai số cơ học, mức độ chống nước.</div>
                        </div>
                        <div class="process-step mb-4">
                            <span class="process-step-num">3</span>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Thông báo & Tiến hành</div>
                            <div class="text-secondary" style="font-size: 0.78rem; line-height: 1.45;">Báo cáo khách hàng thời gian dự kiến hoàn thành. Thực hiện lau dầu, thay linh kiện chính hãng hoặc pin.</div>
                        </div>
                        <div class="process-step mb-0">
                            <span class="process-step-num">4</span>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Kiểm tra áp suất & Bàn giao</div>
                            <div class="text-secondary" style="font-size: 0.78rem; line-height: 1.45;">Đồng hồ được test áp suất nước kỹ lưỡng trước khi bàn giao hoàn chỉnh kèm phiếu bảo hành sau sửa chữa.</div>
                        </div>
                    </div>
                </div>

                {{-- Hỗ trợ trực tiếp --}}
                <div class="bg-white rounded-4 border border-light-subtle shadow-sm p-4 mb-4">
                    <h4 class="h6 text-dark fw-bold text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.5px;">
                        <i class="bi bi-chat-dots-fill" style="color: var(--gold);"></i>Trung Tâm Bảo Hành
                    </h4>
                    <p class="text-secondary mb-4" style="font-size: 0.83rem; line-height: 1.55;">Để được hỗ trợ kỹ thuật hoặc tra cứu tiến độ sửa chữa bảo hành, vui lòng liên hệ trực tiếp:</p>
                    
                    <div class="rounded-3 p-3 border border-light-subtle mb-3" style="background: #faf9f5;">
                        <small class="text-black-50 fw-semibold text-uppercase d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Hotline Bảo Hành</small>
                        <a href="tel:18006868" class="fw-bold text-decoration-none d-flex align-items-center gap-2" style="color: var(--gold-dark); font-size: 1.1rem;">
                            <i class="bi bi-telephone-outbound"></i>1800 6868
                        </a>
                    </div>
                    
                    <div class="rounded-3 p-3 border border-light-subtle" style="background: #faf9f5;">
                        <small class="text-black-50 fw-semibold text-uppercase d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Địa Chỉ Trung Tâm</small>
                        <span class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 0.83rem; margin-top: 2px;">
                            <i class="bi bi-geo-alt" style="color: var(--gold);"></i>Hà Nội, Việt Nam
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
