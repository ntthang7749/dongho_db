@extends('layouts.customer')
@section('title', 'Chính Sách Đổi Trả')

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
    .support-card-title, .support-card-body h5 {
        font-family: 'Playfair Display', serif;
    }

    /* Highlight box */
    .highlight-box {
        background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(201,168,76,0.03));
        border-left: 4px solid var(--gold);
        border-radius: 4px 12px 12px 4px;
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
                <li class="breadcrumb-item active text-white-50" aria-current="page">Chính sách đổi trả</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-white mb-1">
            <i class="bi bi-arrow-left-right me-2" style="color: var(--gold); font-size: 1.3rem; vertical-align: middle;"></i>Chính Sách Đổi Trả
        </h1>
        <p class="text-white-50 mb-0 small">Cam kết bảo vệ quyền lợi tối đa cho khách hàng mua sắm tại Đồng Hồ Online</p>
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
                        <h2 class="support-card-title h5 text-white fw-bold m-0">Chính Sách Đổi Mới Hàng Lỗi & Không Phù Hợp</h2>
                    </div>
                    <div class="p-4 text-secondary lh-lg fs-6" style="font-size: 0.9rem;">
                        <div class="highlight-box p-3 px-4 mb-4">
                            <p class="m-0 fw-semibold text-dark"><i class="bi bi-gift-fill me-2" style="color: var(--gold);"></i>Hỗ trợ đổi mới sản phẩm 1 ĐỔI 1 MIỄN PHÍ trong 7 ngày đầu tiên nếu phát sinh lỗi từ nhà sản xuất.</p>
                        </div>

                        <h5 class="text-dark fw-bold h6 mt-4 mb-3 d-flex align-items-center gap-2"><i class="bi bi-dot text-warning fs-4"></i>1. Trường Hợp Được Đổi Trả Hàng</h5>
                        <ul class="ps-3 mb-4">
                            <li class="mb-2"><strong>Lỗi do nhà sản xuất:</strong> Đồng hồ chạy sai giờ vượt mức cho phép, chết máy đột ngột, lỗi các nút bấm, lỗi bong tróc lớp mạ bề mặt kim loại hoặc hư hỏng linh kiện bên trong không phải do lực cơ học mạnh bên ngoài tác động.</li>
                            <li class="mb-2"><strong>Lỗi do quá trình vận chuyển:</strong> Sản phẩm bị trầy xước, nứt vỡ mặt kính, móp méo hộp đựng khi khách hàng kiểm tra và nhận hàng từ đối tác vận chuyển.</li>
                            <li class="mb-2"><strong>Gửi sai mẫu mã:</strong> Hàng nhận được không đúng model, màu sắc, chất liệu dây đeo hoặc các thông số kỹ thuật như khách hàng đã đặt.</li>
                            <li class="mb-2"><strong>Thay đổi nhu cầu cá nhân:</strong> Khách hàng muốn đổi sang mẫu mã khác có giá trị tương đương hoặc cao hơn trong vòng <strong>3 ngày</strong> kể từ khi nhận sản phẩm (chỉ áp dụng đối với hàng chưa qua sử dụng, còn đầy đủ seal dán bảo vệ).</li>
                        </ul>

                        <h5 class="text-dark fw-bold h6 mt-4 mb-3 d-flex align-items-center gap-2"><i class="bi bi-dot text-warning fs-4"></i>2. Điều Kiện Áp Dụng Đổi Trả</h5>
                        <p class="mb-3 text-secondary">Để quy trình đổi trả hàng diễn ra thuận tiện và nhanh chóng nhất, sản phẩm đổi trả bắt buộc phải thỏa mãn đầy đủ các điều kiện sau:</p>
                        <ul class="ps-3 mb-4">
                            <li class="mb-2">Đồng hồ còn nguyên trạng mới 100%, không có vết trầy xước ở vỏ, khóa, dây đeo hay trầy xước lớp mạ bảo vệ.</li>
                            <li class="mb-2">Nguyên tem bảo hành của nhà phân phối, nguyên seal nylon bảo vệ mặt số và nắp đáy sau của đồng hồ.</li>
                            <li class="mb-2">Còn đầy đủ phụ kiện kèm theo bao gồm: Hộp đựng da cao cấp, sách hướng dẫn sử dụng của hãng, thẻ bảo hành quốc tế, thẻ bảo hành của cửa hàng, túi giấy đựng quà.</li>
                            <li class="mb-2">Có hóa đơn mua hàng (hoặc hóa đơn điện tử được gửi qua email/số điện thoại) đi kèm.</li>
                        </ul>

                        <h5 class="text-dark fw-bold h6 mt-4 mb-3 d-flex align-items-center gap-2"><i class="bi bi-dot text-warning fs-4"></i>3. Các Trường Hợp Từ Chối Đổi Trả</h5>
                        <ul class="ps-3 mb-4">
                            <li class="mb-2">Sản phẩm đã quá hạn <strong>7 ngày</strong> đổi hàng (hoặc quá hạn 3 ngày đổi hàng do thay đổi nhu cầu cá nhân).</li>
                            <li class="mb-2">Sản phẩm bị hư hỏng mặt kính, trầy xước do va chạm vật lý bên ngoài hoặc rơi rớt trong quá trình sử dụng của quý khách.</li>
                            <li class="mb-2">Sản phẩm có dấu hiệu tự ý cạy mở, sửa chữa hoặc đã mang đến các đơn vị bên ngoài không thuộc ủy quyền bảo hành của hãng.</li>
                            <li class="mb-2">Sử dụng sai quy cách chống nước ghi trên mặt số (ví dụ mang đi bơi/lặn đối với sản phẩm có chỉ số chống nước thấp 3ATM).</li>
                        </ul>

                        <h5 class="text-dark fw-bold h6 mt-4 mb-3 d-flex align-items-center gap-2"><i class="bi bi-dot text-warning fs-4"></i>4. Quy Định Hoàn Tiền</h5>
                        <ul class="ps-3 mb-0">
                            <li class="mb-2">Trong trường hợp đổi mới lỗi do nhà sản xuất nhưng cửa hàng đã **hết mẫu mã đó** và quý khách không chọn được sản phẩm thay thế phù hợp, chúng tôi sẽ hoàn trả 100% số tiền đã mua hàng.</li>
                            <li class="mb-2">Hình thức hoàn tiền: Chuyển khoản ngân hàng trực tiếp vào số tài khoản do quý khách chỉ định trong vòng 24 - 48 giờ làm việc.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Quy trình đổi trả --}}
                <div class="bg-white rounded-4 border border-light-subtle shadow-sm p-4 mb-4">
                    <h4 class="h6 text-dark fw-bold text-uppercase mb-4 d-flex align-items-center gap-2" style="letter-spacing: 0.5px;">
                        <i class="bi bi-card-checklist" style="color: var(--gold);"></i>Quy Trình 4 Bước Đổi Trả
                    </h4>
                    <div class="process-timeline">
                        <div class="process-step mb-4">
                            <span class="process-step-num">1</span>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Liên hệ tổng đài</div>
                            <div class="text-secondary" style="font-size: 0.78rem; line-height: 1.45;">Gọi hotline <strong>1800 6868</strong> hoặc gửi tin nhắn qua biểu tượng chat trực tuyến để báo cáo sự cố sản phẩm.</div>
                        </div>
                        <div class="process-step mb-4">
                            <span class="process-step-num">2</span>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Xác nhận tình trạng</div>
                            <div class="text-secondary" style="font-size: 0.78rem; line-height: 1.45;">Chuyên viên hỗ trợ sẽ yêu cầu gửi ảnh chụp hoặc video ngắn quay tình trạng sản phẩm để xác minh lỗi ban đầu.</div>
                        </div>
                        <div class="process-step mb-4">
                            <span class="process-step-num">3</span>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Gửi hàng về cửa hàng</div>
                            <div class="text-secondary" style="font-size: 0.78rem; line-height: 1.45;">Đóng gói đồng hồ đầy đủ hộp sổ, thẻ bảo hành gửi về địa chỉ tiếp nhận bảo hành của chúng tôi.</div>
                        </div>
                        <div class="process-step mb-0">
                            <span class="process-step-num">4</span>
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Đổi mới & Hoàn tất</div>
                            <div class="text-secondary" style="font-size: 0.78rem; line-height: 1.45;">Sau khi kiểm định sản phẩm thỏa mãn điều kiện, bộ phận kho sẽ chuyển sản phẩm mới 100% đến quý khách.</div>
                        </div>
                    </div>
                </div>

                {{-- Hỗ trợ trực tiếp --}}
                <div class="bg-white rounded-4 border border-light-subtle shadow-sm p-4 mb-4">
                    <h4 class="h6 text-dark fw-bold text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.5px;">
                        <i class="bi bi-chat-dots-fill" style="color: var(--gold);"></i>Hỗ Trợ Trực Tiếp
                    </h4>
                    <p class="text-secondary mb-4" style="font-size: 0.83rem; line-height: 1.55;">Bộ phận chăm sóc khách hàng luôn sẵn sàng tiếp nhận yêu cầu đổi trả của bạn từ 8:00 – 21:00 hàng ngày.</p>
                    
                    <div class="rounded-3 p-3 border border-light-subtle mb-3" style="background: #faf9f5;">
                        <small class="text-black-50 fw-semibold text-uppercase d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Hotline Đổi Trả</small>
                        <a href="tel:18006868" class="fw-bold text-decoration-none d-flex align-items-center gap-2" style="color: var(--gold-dark); font-size: 1.1rem;">
                            <i class="bi bi-telephone-outbound"></i>1800 6868
                        </a>
                    </div>
                    
                    <div class="rounded-3 p-3 border border-light-subtle" style="background: #faf9f5;">
                        <small class="text-black-50 fw-semibold text-uppercase d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">Email Hỗ Trợ</small>
                        <a href="mailto:support@donghoonline.vn" class="fw-bold text-decoration-none d-flex align-items-center gap-2 text-dark" style="font-size: 0.83rem;">
                            <i class="bi bi-envelope" style="color: var(--gold);"></i>support@donghoonline.vn
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
