@extends('layouts.customer')
@section('title', 'Liên Hệ')

@push('styles')
<style>
    /* ── Hero ── */
    .contact-hero {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
        border-bottom: 1px solid rgba(201,168,76,0.18);
    }
    .contact-hero h1 {
        font-family: 'Playfair Display', serif;
        color: #f0ece0;
    }
    .contact-hero .subtitle { color: rgba(240,236,224,0.5); }
    .contact-hero .breadcrumb-item a { color: var(--gold); text-decoration: none; }
    .contact-hero .breadcrumb-item.active { color: rgba(240,236,224,0.45); }
    .contact-hero .breadcrumb-item+.breadcrumb-item::before { color: rgba(201,168,76,0.35); }

    /* ── Form card ── */
    .contact-form-card {
        border-color: #f0ece4 !important;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    }
    .form-card-header {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
    }
    .form-card-icon {
        background: rgba(201,168,76,0.18);
        border: 1px solid rgba(201,168,76,0.3);
        color: var(--gold);
    }
    .form-card-title {
        font-family: 'Playfair Display', serif;
        color: #f0ece0;
    }
    .form-card-sub { color: rgba(240,236,224,0.5); }

    /* Form fields */
    .field-label {
        color: #1a1a2e;
        letter-spacing: 0.5px;
    }
    .field-label span { color: #e94560; }
    .field-label i { color: var(--gold); }

    .contact-input, .contact-select, .contact-textarea {
        background: #faf9f5;
        border: 1.5px solid #e9e4d8;
        transition: all 0.22s;
        outline: none;
        font-family: 'Inter', sans-serif;
    }
    .contact-input:focus, .contact-select:focus, .contact-textarea:focus {
        border-color: var(--gold);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
    }
    .contact-input.is-invalid, .contact-select.is-invalid, .contact-textarea.is-invalid {
        border-color: #ef4444;
        background: #fff5f5;
    }
    .contact-input::placeholder, .contact-textarea::placeholder { color: #bbb; }
    .contact-textarea { resize: vertical; min-height: 130px; }

    .field-error { color: #dc2626; }

    /* Type cards */
    .type-card label {
        border: 1.5px solid #e9e4d8;
        background: #faf9f5;
        cursor: pointer;
        transition: all 0.2s;
    }
    .type-card input:checked + label {
        border-color: var(--gold);
        background: rgba(201,168,76,0.08);
        box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
    }
    .type-card input:checked + label .type-name { color: var(--gold-dark); }
    .type-card label:hover { border-color: rgba(201,168,76,0.4); background: rgba(201,168,76,0.04); }

    /* Submit btn */
    .btn-submit-contact {
        background: linear-gradient(135deg, #1a1a2e, #2d2d4e);
        transition: all 0.25s;
        letter-spacing: 0.3px;
    }
    .btn-submit-contact:hover {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(201,168,76,0.35);
    }

    /* ── Info cards ── */
    .info-card {
        border-color: #f0ece4 !important;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.22s;
    }
    .info-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.1); transform: translateY(-2px); }

    .contact-info-row {
        border-bottom: 1px solid #f5f2ec;
    }
    .contact-info-row:last-child { border-bottom: none; }
    .contact-info-row .ci-icon {
        background: rgba(201,168,76,0.1);
        border: 1px solid rgba(201,168,76,0.2);
        color: var(--gold);
    }
    .contact-info-row .ci-label { color: #aaa; letter-spacing: 0.4px; }
    .contact-info-row .ci-val { color: #1a1a2e; }

    /* Note card */
    .note-card {
        background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(201,168,76,0.03));
        border: 1px solid rgba(201,168,76,0.25);
    }
    .note-card h6 { color: var(--gold-dark); }
    .note-item i { color: var(--gold); }

    /* Success state */
    .success-icon-wrap {
        background: linear-gradient(135deg, rgba(34,197,94,0.15), rgba(34,197,94,0.05));
        border: 2px solid rgba(34,197,94,0.3);
        animation: scaleIn 0.5s cubic-bezier(0.175,0.885,0.32,1.275);
    }
    @keyframes scaleIn { from { transform: scale(0); opacity:0; } to { transform: scale(1); opacity:1; } }
</style>
@endpush

@section('content')

{{-- ── Hero ── --}}
<div class="contact-hero py-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2" style="font-size: 0.79rem;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item active">Liên hệ</li>
            </ol>
        </nav>
        <h1 class="m-0" style="font-size: 1.7rem; font-weight: 700;"><i class="bi bi-envelope-heart me-2" style="color:var(--gold);font-size:1.3rem;vertical-align:middle;"></i>Liên Hệ Với Chúng Tôi</h1>
        <p class="subtitle m-0" style="font-size: 0.85rem;">Phản hồi, góp ý hoặc báo cáo vấn đề — chúng tôi luôn lắng nghe bạn</p>
    </div>
</div>

<div class="contact-page bg-light py-5">
<div class="container">
<div class="row g-4 align-items-start">

    {{-- ══ FORM ══ --}}
    <div class="col-lg-7">
        <div class="contact-form-card bg-white rounded-4 border overflow-hidden">
            <div class="form-card-header p-4 d-flex align-items-center gap-3">
                <div class="form-card-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px; font-size: 1.2rem;"><i class="bi bi-send"></i></div>
                <div>
                    <div class="form-card-title m-0" style="font-size: 1.15rem; font-weight: 700;">Gửi Tin Nhắn</div>
                    <div class="form-card-sub" style="font-size: 0.78rem;">Điền đầy đủ thông tin để chúng tôi phản hồi nhanh nhất</div>
                </div>
            </div>

            <div class="form-card-body p-4">

                {{-- Success flash --}}
                @if(session('success'))
                <div class="contact-success text-center py-5 px-3">
                    <div class="success-icon-wrap rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 90px; height: 90px;">
                        <i class="bi bi-check-circle-fill" style="font-size: 2.4rem; color: #16a34a;"></i>
                    </div>
                    <h5 class="m-0 mb-2" style="font-family:'Playfair Display',serif;color:#1a1a2e; font-weight: 700;">Gửi thành công!</h5>
                    <p class="text-secondary mb-4" style="font-size:0.875rem;">{{ session('success') }}</p>
                    <a href="{{ route('contact.index') }}" class="btn-submit-contact d-inline-flex align-items-center justify-content-center gap-2 rounded-3 border-0 text-white font-weight-bold" style="width:auto; padding:10px 28px; text-decoration: none;">
                        <i class="bi bi-plus-circle"></i>Gửi thêm
                    </a>
                </div>
                @else

                <form method="POST" action="{{ route('contact.store') }}" id="contactForm">
                    @csrf

                    {{-- Loại liên hệ --}}
                    <div class="mb-4">
                        <div class="field-label mb-2 d-flex align-items-center gap-1 text-uppercase font-weight-bold" style="font-size: 0.8rem;"><i class="bi bi-tag"></i>Loại liên hệ <span>*</span></div>
                        <div class="type-grid d-grid gap-2" style="grid-template-columns: repeat(2, 1fr);">
                            @php
                            $types = [
                                'feedback' => ['icon'=>'💬','name'=>'Phản hồi / Góp ý'],
                                'report'   => ['icon'=>'🚨','name'=>'Báo cáo vấn đề'],
                                'question' => ['icon'=>'❓','name'=>'Câu hỏi'],
                                'other'    => ['icon'=>'📋','name'=>'Khác'],
                            ];
                            @endphp
                            @foreach($types as $val => $t)
                            <div class="type-card">
                                <input type="radio" name="type" id="type-{{ $val }}"
                                       value="{{ $val }}"
                                       {{ old('type','feedback') === $val ? 'checked' : '' }}>
                                <label for="type-{{ $val }}" class="d-flex flex-column align-items-center gap-1 rounded-3 py-3 px-2 text-center" style="font-size: 0.75rem;">
                                    <span class="type-icon" style="font-size: 1.4rem;">{{ $t['icon'] }}</span>
                                    <span class="type-name font-weight-bold">{{ $t['name'] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('type')<div class="field-error mt-1 d-flex align-items-center gap-1" style="font-size: 0.75rem;"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>

                    {{-- Tên + Email --}}
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <div class="field-label mb-2 d-flex align-items-center gap-1 text-uppercase font-weight-bold" style="font-size: 0.8rem;"><i class="bi bi-person"></i>Họ và tên <span>*</span></div>
                            <input type="text" name="name" id="inp-name"
                                   class="contact-input form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   placeholder="Nguyễn Văn A"
                                   value="{{ old('name', auth()->user()?->name) }}">
                            @error('name')<div class="field-error mt-1 d-flex align-items-center gap-1" style="font-size: 0.75rem;"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <div class="field-label mb-2 d-flex align-items-center gap-1 text-uppercase font-weight-bold" style="font-size: 0.8rem;"><i class="bi bi-envelope"></i>Email <span>*</span></div>
                            <input type="email" name="email" id="inp-email"
                                   class="contact-input form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="email@example.com"
                                   value="{{ old('email', auth()->user()?->email) }}">
                            @error('email')<div class="field-error mt-1 d-flex align-items-center gap-1" style="font-size: 0.75rem;"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Điện thoại + Tiêu đề --}}
                    <div class="row g-3 mb-3">
                        <div class="col-sm-5">
                            <div class="field-label mb-2 d-flex align-items-center gap-1 text-uppercase font-weight-bold" style="font-size: 0.8rem;"><i class="bi bi-telephone"></i>Số điện thoại</div>
                            <input type="tel" name="phone" id="inp-phone"
                                   class="contact-input form-control"
                                   placeholder="0901 234 567"
                                   value="{{ old('phone') }}">
                        </div>
                        <div class="col-sm-7">
                            <div class="field-label mb-2 d-flex align-items-center gap-1 text-uppercase font-weight-bold" style="font-size: 0.8rem;"><i class="bi bi-chat-text"></i>Tiêu đề <span>*</span></div>
                            <input type="text" name="subject" id="inp-subject"
                                   class="contact-input form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}"
                                   placeholder="Tóm tắt vấn đề bạn muốn phản ánh..."
                                   value="{{ old('subject') }}">
                            @error('subject')<div class="field-error mt-1 d-flex align-items-center gap-1" style="font-size: 0.75rem;"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Nội dung --}}
                    <div class="mb-4">
                        <div class="field-label mb-2 d-flex justify-content-between text-uppercase font-weight-bold" style="font-size: 0.8rem;">
                            <span><i class="bi bi-pencil-square"></i>Nội dung chi tiết <span style="color:#e94560;">*</span></span>
                            <span id="charCount" class="text-secondary font-weight-normal" style="font-size:0.72rem;text-transform: none;">0 / 2000</span>
                        </div>
                        <textarea name="message" id="inp-message"
                                  class="contact-textarea form-control {{ $errors->has('message') ? 'is-invalid' : '' }}"
                                  placeholder="Mô tả chi tiết vấn đề, đề xuất hoặc câu hỏi của bạn...&#10;&#10;Ví dụ: Đơn hàng #DH... bị sự cố, sản phẩm không đúng mô tả..."
                                  oninput="document.getElementById('charCount').textContent = this.value.length + ' / 2000'">{{ old('message') }}</textarea>
                        @error('message')<div class="field-error mt-1 d-flex align-items-center gap-1" style="font-size: 0.75rem;"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit-contact d-flex align-items-center justify-content-center gap-2 rounded-3 w-100 border-0 p-3 text-white font-weight-bold" style="font-size: 0.95rem;" id="submitBtn">
                        <i class="bi bi-send-fill"></i>Gửi Tin Nhắn
                    </button>
                </form>

                @endif
            </div>
        </div>
    </div>

    {{-- ══ SIDEBAR INFO ══ --}}
    <div class="col-lg-5">
        {{-- Thông tin liên hệ --}}
        <div class="info-card bg-white rounded-4 border p-4 mb-3">
            <div class="info-card-title mb-3 font-weight-bold" style="font-size: 0.875rem; color: #1a1a2e; display: flex; align-items: center; gap: 8px;"><i class="bi bi-building"></i>Thông Tin Liên Hệ</div>
            <div class="contact-info-row d-flex align-items-start gap-3 py-2">
                <div class="ci-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.9rem;"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="ci-label font-weight-bold text-uppercase" style="font-size: 0.7rem; color: #aaa; letter-spacing: 0.4px;">Địa chỉ</div>
                    <div class="ci-val font-weight-bold text-dark" style="font-size: 0.845rem;">123 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh</div>
                </div>
            </div>
            <div class="contact-info-row d-flex align-items-start gap-3 py-2">
                <div class="ci-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.9rem;"><i class="bi bi-telephone-fill"></i></div>
                <div>
                    <div class="ci-label font-weight-bold text-uppercase" style="font-size: 0.7rem; color: #aaa; letter-spacing: 0.4px;">Hotline</div>
                    <div class="ci-val font-weight-bold text-dark" style="font-size: 0.845rem;"><a href="tel:18006868" style="color:#1a1a2e;text-decoration:none;">1800 6868</a> <span class="font-weight-normal text-secondary" style="font-size:0.72rem;">(Miễn phí)</span></div>
                </div>
            </div>
            <div class="contact-info-row d-flex align-items-start gap-3 py-2">
                <div class="ci-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.9rem;"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <div class="ci-label font-weight-bold text-uppercase" style="font-size: 0.7rem; color: #aaa; letter-spacing: 0.4px;">Email hỗ trợ</div>
                    <div class="ci-val font-weight-bold text-dark" style="font-size: 0.845rem;"><a href="mailto:support@donghoonline.vn" style="color:var(--gold-dark);text-decoration:none;">support@donghoonline.vn</a></div>
                </div>
            </div>
            <div class="contact-info-row d-flex align-items-start gap-3 py-2">
                <div class="ci-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.9rem;"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="ci-label font-weight-bold text-uppercase" style="font-size: 0.7rem; color: #aaa; letter-spacing: 0.4px;">Giờ làm việc</div>
                    <div class="ci-val font-weight-bold text-dark" style="font-size: 0.845rem;">Thứ 2 – Chủ nhật: 8:00 – 21:00</div>
                </div>
            </div>
        </div>

        {{-- Thời gian phản hồi --}}
        <div class="info-card bg-white rounded-4 border p-4 mb-3">
            <div class="info-card-title mb-3 font-weight-bold" style="font-size: 0.875rem; color: #1a1a2e; display: flex; align-items: center; gap: 8px;"><i class="bi bi-lightning-charge"></i>Thời Gian Phản Hồi</div>
            <div class="contact-info-row d-flex align-items-start gap-3 py-2">
                <div class="ci-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.9rem; background:rgba(34,197,94,0.1);border-color:rgba(34,197,94,0.2);color:#16a34a;"><i class="bi bi-chat-dots"></i></div>
                <div>
                    <div class="ci-label font-weight-bold text-uppercase" style="font-size: 0.7rem; color: #aaa; letter-spacing: 0.4px;">Chat trực tuyến</div>
                    <div class="ci-val font-weight-bold" style="font-size: 0.845rem; color:#16a34a;">Phản hồi ngay lập tức</div>
                </div>
            </div>
            <div class="contact-info-row d-flex align-items-start gap-3 py-2">
                <div class="ci-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.9rem; background:rgba(245,158,11,0.1);border-color:rgba(245,158,11,0.2);color:#d97706;"><i class="bi bi-envelope"></i></div>
                <div>
                    <div class="ci-label font-weight-bold text-uppercase" style="font-size: 0.7rem; color: #aaa; letter-spacing: 0.4px;">Qua form liên hệ</div>
                    <div class="ci-val font-weight-bold" style="font-size: 0.845rem; color:#d97706;">Trong vòng 2–4 giờ làm việc</div>
                </div>
            </div>
            <div class="contact-info-row d-flex align-items-start gap-3 py-2">
                <div class="ci-icon rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.9rem; background:rgba(99,102,241,0.1);border-color:rgba(99,102,241,0.2);color:#4f46e5;"><i class="bi bi-flag"></i></div>
                <div>
                    <div class="ci-label font-weight-bold text-uppercase" style="font-size: 0.7rem; color: #aaa; letter-spacing: 0.4px;">Báo cáo khẩn</div>
                    <div class="ci-val font-weight-bold" style="font-size: 0.845rem; color:#4f46e5;">Ưu tiên xử lý trong 1 giờ</div>
                </div>
            </div>
        </div>

        {{-- Lưu ý --}}
        <div class="note-card rounded-4 p-4 border">
            <h6 class="font-weight-bold mb-3" style="font-size: 0.83rem;"><i class="bi bi-info-circle-fill me-1"></i>Lưu Ý Khi Liên Hệ</h6>
            <div class="note-item d-flex align-items-start gap-2 mb-2" style="font-size: 0.8rem; color:#555;"><i class="bi bi-check-circle-fill"></i>Mô tả chi tiết vấn đề để được hỗ trợ nhanh hơn.</div>
            <div class="note-item d-flex align-items-start gap-2 mb-2" style="font-size: 0.8rem; color:#555;"><i class="bi bi-check-circle-fill"></i>Đính kèm mã đơn hàng nếu vấn đề liên quan đến đơn hàng.</div>
            <div class="note-item d-flex align-items-start gap-2 mb-2" style="font-size: 0.8rem; color:#555;"><i class="bi bi-check-circle-fill"></i>Không chia sẻ mật khẩu qua form liên hệ này.</div>
            <div class="note-item d-flex align-items-start gap-2 mb-0" style="font-size: 0.8rem; color:#555;"><i class="bi bi-exclamation-triangle-fill" style="color:#d97706;"></i>Nếu cần hỗ trợ khẩn, hãy gọi trực tiếp hotline <strong>1800 6868</strong>.</div>
        </div>
    </div>

</div>
</div>
</div>

@push('scripts')
<script>
// Submit animation
document.getElementById('contactForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i>Đang gửi...';
    btn.disabled = true;
    btn.style.opacity = '0.8';
});
</script>
@endpush

@endsection
