@extends('layouts.customer')
@section('title', 'Câu Hỏi Thường Gặp (FAQ)')

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

    /* ── Custom FAQ Accordion ── */
    .accordion-item {
        border: 1px solid #f0ece4 !important;
        transition: all 0.25s ease;
    }
    .accordion-item:hover {
        border-color: rgba(201,168,76,0.25) !important;
    }
    .accordion-button:not(.collapsed) {
        background-color: rgba(201,168,76,0.05) !important;
        color: #1a1a2e !important;
    }
    .accordion-button:focus {
        box-shadow: none !important;
    }
    .accordion-button::after {
        background-size: 0.8rem;
    }
    .accordion-button {
        font-family: 'Playfair Display', serif;
    }
    .btn-submit-contact {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        transition: all 0.22s;
    }
    .btn-submit-contact:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        color: #0a0a0f;
        box-shadow: 0 6px 18px rgba(201,168,76,0.3);
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
                <li class="breadcrumb-item active" aria-current="page">Câu hỏi thường gặp</li>
            </ol>
        </nav>
        <h1 class="fs-3 fw-bold mb-1"><i class="bi bi-patch-question me-2 align-middle" style="color:var(--gold); font-size:1.3rem;"></i>Câu Hỏi Thường Gặp</h1>
        <p class="text-white-50 fs-7 mb-0">Giải đáp nhanh chóng các thắc mắc phổ biến của bạn khi mua sắm đồng hồ cao cấp</p>
    </div>
</div>

<div class="support-page py-5">
    <div class="container">
        <div class="row g-4">
            
            {{-- Main Content --}}
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    
                    {{-- FAQ 1 --}}
                    <div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden mb-3">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold fs-6 py-3.5 px-4 d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <i class="bi bi-question-circle text-gold fs-5"></i>1. Đồng hồ của cửa hàng có cam kết chính hãng 100% không?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted fs-7 lh-lg px-4 py-3 ps-5">
                                <strong>Đồng Hồ Online cam kết 100% sản phẩm được bán ra đều là hàng chính hãng nhập khẩu nguyên chiếc</strong> từ các thương hiệu danh tiếng Thụy Sĩ, Nhật Bản... Mỗi chiếc đồng hồ khi đến tay khách hàng đều đi kèm đầy đủ: Hộp sổ sang trọng của hãng, thẻ bảo hành quốc tế có dấu mộc xác thực của đại lý phân phối và tem chống hàng giả. Chúng tôi sẵn sàng bồi thường <strong>gấp 10 lần giá trị sản phẩm</strong> nếu quý khách phát hiện hàng giả, hàng nhái từ phía cửa hàng.
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden mb-3">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold fs-6 py-3.5 px-4 d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="bi bi-question-circle text-gold fs-5"></i>2. Các chỉ số chịu nước 3ATM, 5ATM, 10ATM có ý nghĩa gì?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted fs-7 lh-lg px-4 py-3 ps-5">
                                Chỉ số chống nước quyết định môi trường bạn được phép sử dụng đồng hồ:
                                <ul class="ps-3 mt-2 mb-0">
                                    <li class="mb-1"><strong>3ATM (30m):</strong> Chỉ chịu nước ở mức rửa tay nhẹ, đi mưa nhỏ. Không mang đi tắm hay bơi.</li>
                                    <li class="mb-1"><strong>5ATM (50m):</strong> Có thể mang khi tắm vòi hoa sen, đi mưa lớn. Không thích hợp để đi bơi, lặn bể bơi.</li>
                                    <li class="mb-1"><strong>10ATM (100m):</strong> Thoải mái sử dụng khi tắm rửa, bơi lội ở bể bơi nông. Không sử dụng khi đi lặn biển sâu bình khí.</li>
                                </ul>
                                <em class="d-block mt-2 text-warning fs-8">*Lưu ý: Không điều chỉnh hay vặn núm khi đồng hồ đang tiếp xúc trực tiếp với nước.</em>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden mb-3">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold fs-6 py-3.5 px-4 d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="bi bi-question-circle text-gold fs-5"></i>3. Đồng hồ cơ (Automatic) sai số bao nhiêu giây mỗi ngày?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted fs-7 lh-lg px-4 py-3 ps-5">
                                Sai số của đồng hồ cơ là hiện tượng vật lý tự nhiên do tác động của trọng lực và dao động cót:
                                <ul class="ps-3 mt-2 mb-0">
                                    <li class="mb-1">Dòng máy Nhật Bản (Seiko, Citizen, Orient): Sai số trung bình khoảng <strong>-15 đến +25 giây/ngày</strong>.</li>
                                    <li class="mb-1">Dòng máy Thụy Sĩ (Tissot, Longines, Mido): Sai số tinh chuẩn khoảng <strong>-5 đến +15 giây/ngày</strong>.</li>
                                </ul>
                                <span class="d-block mt-2">Để giữ đồng hồ chạy ổn định nhất, quý khách nên đeo liên tục tối thiểu 8 tiếng mỗi ngày hoặc lên cót phụ bằng tay 15-20 vòng núm vặn.</span>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 4 --}}
                    <div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden mb-3">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold fs-6 py-3.5 px-4 d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="bi bi-question-circle text-gold fs-5"></i>4. Cửa hàng có hỗ trợ trả góp lãi suất 0% không?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted fs-7 lh-lg px-4 py-3 ps-5">
                                <strong>Có.</strong> Đồng Hồ Online liên kết cùng hơn 20 ngân hàng uy tín hỗ trợ chương trình <strong>trả góp lãi suất 0%</strong> thông qua Thẻ Tín Dụng (Visa/Mastercard/JCB). Qúy khách có thể tự do lựa chọn kỳ hạn trả góp linh hoạt từ 3, 6, 9 đến 12 tháng. Thủ tục hoàn toàn online trong vòng 5 phút, không phát sinh chi phí ẩn hay phụ phí làm hồ sơ.
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 5 --}}
                    <div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden mb-3">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold fs-6 py-3.5 px-4 d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                <i class="bi bi-question-circle text-gold fs-5"></i>5. Bao lâu thì đồng hồ cần thay pin hoặc lau dầu bảo dưỡng?
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted fs-7 lh-lg px-4 py-3 ps-5">
                                <ul class="ps-3 mb-0">
                                    <li class="mb-1"><strong>Đồng hồ dùng Pin (Quartz):</strong> Tuổi thọ pin thường kéo dài từ <strong>2 – 3 năm</strong>. Khi đồng hồ chạy chậm dần hoặc kim giây nhảy cách quãng 2-4 giây là dấu hiệu sắp hết pin, cần mang tới cửa hàng thay pin để tránh rò rỉ hóa chất làm hỏng máy.</li>
                                    <li class="mb-0"><strong>Đồng hồ cơ (Automatic):</strong> Khoảng <strong>3 – 5 năm</strong> nên lau dầu, kiểm tra gioăng chống nước để hệ bánh răng chuyển động trơn tru nhất và tăng tuổi thọ sử dụng.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- FAQ 6 --}}
                    <div class="accordion-item border-0 rounded-4 shadow-sm overflow-hidden mb-3">
                        <h2 class="accordion-header" id="headingSix">
                            <button class="accordion-button collapsed bg-white text-dark fw-bold fs-6 py-3.5 px-4 d-flex align-items-center gap-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                <i class="bi bi-question-circle text-gold fs-5"></i>6. Nếu tôi mua online đeo không vừa (rộng/chật) thì sao?
                            </button>
                        </h2>
                        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                            <div class="accordion-body bg-light text-muted fs-7 lh-lg px-4 py-3 ps-5">
                                Đối với dòng đồng hồ dây kim loại mắt xích, cửa hàng luôn tặng kèm <strong>bộ dụng cụ cắt mắt xích mini tại nhà</strong> để quý khách tự điều chỉnh. Hoặc quý khách có thể mang trực tiếp ra bất kỳ tiệm đồng hồ nào gần nhất, chúng tôi sẽ hoàn trả phí cắt mắt xích (tối đa 30,000đ). Đối với dây da, nếu dây quá dài/ngắn, chúng tôi hỗ trợ đục thêm lỗ đeo miễn phí hoặc tư vấn đổi size dây đeo tương thích.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Hỏi đáp nhanh --}}
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                    <h4 class="fw-bold fs-7 text-uppercase text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                        <i class="bi bi-patch-question-fill text-gold"></i>Không Tìm Thấy Câu Trả Lời?
                    </h4>
                    <p class="fs-8 text-muted lh-base mb-3">Đội ngũ CSKH chuyên nghiệp luôn trực tuyến 24/7 để lắng nghe mọi thắc mắc chuyên biệt của bạn.</p>
                    <a href="{{ route('contact.index') }}" class="btn btn-submit-contact d-flex align-items-center justify-content-center gap-2 w-100 py-2.5 rounded-3 fw-bold fs-7 text-decoration-none">
                        <i class="bi bi-chat-left-text-fill"></i>Gửi Câu Hỏi Riêng
                    </a>
                </div>

                {{-- Hotline hỗ trợ --}}
                <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 bg-white">
                    <h4 class="fw-bold fs-7 text-uppercase text-dark mb-3 d-flex align-items-center gap-2 border-bottom pb-2">
                        <i class="bi bi-telephone-fill text-gold"></i>Hotline Trợ Giúp
                    </h4>
                    <div class="bg-light rounded-3 p-3 border border-light-subtle">
                        <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 0.65rem;">Hỗ Trợ Nhanh 24/7</small>
                        <a href="tel:18006868" class="text-gold-dark fw-bold fs-5 text-decoration-none d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-outbound"></i>1800 6868
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
