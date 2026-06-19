@extends('layouts.admin')
@section('title', 'Chi Tiết Liên Hệ #' . $contact->id)
@section('page-title', 'Chi Tiết Liên Hệ')

@push('styles')
<style>
    .ai-flash-glow {
        animation: aiFlashGlow 1.5s ease-in-out;
    }
    @keyframes aiFlashGlow {
        0%, 100% {
            box-shadow: none;
            border-color: #dee2e6;
        }
        50% {
            box-shadow: 0 0 15px rgba(201, 168, 76, 0.6);
            border-color: #c9a84c;
            background-color: rgba(201, 168, 76, 0.03);
        }
    }
    
    #btn-ai-suggest:hover {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%) !important;
        color: #0b0b14 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3) !important;
    }
</style>
@endpush

@section('content')
<div class="row g-4">

    {{-- LEFT: Nội dung liên hệ --}}
    <div class="col-lg-7">

        {{-- Header card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $contact->subject }}</h5>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="badge bg-{{ $contact->statusColor() }} rounded-pill">{{ $contact->statusLabel() }}</span>
                            <span class="badge bg-light text-dark border rounded-pill" style="font-size:0.7rem;">
                                @php $typeIcons=['feedback'=>'💬','report'=>'🚨','question'=>'❓','other'=>'📋']; @endphp
                                {{ $typeIcons[$contact->type] ?? '' }} {{ $contact->typeLabel() }}
                            </span>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $contact->created_at->format('H:i – d/m/Y') }}</small>
                        </div>
                    </div>
                    {{-- Đổi trạng thái --}}
                    <form method="POST" action="{{ route('admin.contacts.status', $contact->id) }}" class="d-flex gap-2">
                        @csrf
                        <select name="status" class="form-select form-select-sm" style="width:140px;">
                            @foreach(['new'=>'Chưa đọc','read'=>'Đã đọc','replied'=>'Đã phản hồi','closed'=>'Đã đóng'] as $val=>$lbl)
                            <option value="{{ $val }}" {{ $contact->status===$val?'selected':'' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-outline-dark">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Nội dung tin nhắn --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold" style="font-size:0.875rem;">
                <i class="bi bi-chat-text me-2" style="color:var(--bs-primary);"></i>Nội Dung Tin Nhắn
            </div>
            <div class="card-body">
                <p style="white-space:pre-wrap;font-size:0.9rem;line-height:1.75;color:#333;">{{ $contact->message }}</p>
            </div>
        </div>

        {{-- Phản hồi của admin (nếu có) --}}
        @if($contact->admin_reply)
        <div class="card border-0 shadow-sm mb-4" style="border-left:3px solid #22c55e!important;">
            <div class="card-header bg-white fw-bold" style="font-size:0.875rem;color:#16a34a;">
                <i class="bi bi-reply-all me-2"></i>Phản Hồi Của Admin
                <small class="text-muted fw-normal ms-2">{{ $contact->replied_at?->format('H:i – d/m/Y') }}</small>
            </div>
            <div class="card-body">
                <p style="white-space:pre-wrap;font-size:0.9rem;line-height:1.75;color:#333;">{{ $contact->admin_reply }}</p>
            </div>
        </div>
        @endif

        {{-- Form phản hồi --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold" style="font-size:0.875rem;">
                <i class="bi bi-send me-2" style="color:#6366f1;"></i>
                {{ $contact->admin_reply ? 'Cập Nhật Phản Hồi' : 'Gửi Phản Hồi' }}
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.contacts.reply', $contact->id) }}">
                    @csrf
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0" style="font-size:0.8rem;">Nội dung phản hồi <span class="text-danger">*</span></label>
                            <button type="button" id="btn-ai-suggest" class="btn btn-sm d-inline-flex align-items-center gap-2 border-0 shadow-sm" 
                                    style="background: linear-gradient(135deg, #1a1a2e 0%, #2d2d4e 100%); color: var(--gold); font-size: 0.78rem; font-weight: 600; padding: 5px 12px; border-radius: 6px; transition: all 0.25s;">
                                <i class="bi bi-stars"></i>
                                <span id="ai-btn-text">Gợi Ý Phản Hồi Bằng AI</span>
                            </button>
                        </div>
                        <textarea name="admin_reply" rows="5"
                                  class="form-control @error('admin_reply') is-invalid @enderror"
                                  style="font-size:0.875rem;"
                                  placeholder="Nhập nội dung phản hồi tới khách hàng...">{{ old('admin_reply', $contact->admin_reply) }}</textarea>
                        @error('admin_reply')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Phản hồi sẽ được lưu vào hệ thống và trạng thái sẽ chuyển sang "Đã phản hồi".</div>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send me-1"></i>{{ $contact->admin_reply ? 'Cập Nhật Phản Hồi' : 'Gửi Phản Hồi' }}
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại
                    </a>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Thông tin khách hàng --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold" style="font-size:0.875rem;">
                <i class="bi bi-person-circle me-2"></i>Thông Tin Người Gửi
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0" style="font-size:0.875rem;">
                    <tr>
                        <td class="text-muted fw-500" style="width:110px;">Họ tên</td>
                        <td class="fw-bold">{{ $contact->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                    </tr>
                    @if($contact->phone)
                    <tr>
                        <td class="text-muted">Điện thoại</td>
                        <td><a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a></td>
                    </tr>
                    @endif
                    @if($contact->user)
                    <tr>
                        <td class="text-muted">Tài khoản</td>
                        <td>
                            <span class="badge bg-success">Thành viên</span>
                            <small class="text-muted ms-1">ID #{{ $contact->user_id }}</small>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td class="text-muted">Tài khoản</td>
                        <td><span class="badge bg-secondary">Khách vãng lai</span></td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Gửi lúc</td>
                        <td>{{ $contact->created_at->format('H:i – d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold" style="font-size:0.875rem;">
                <i class="bi bi-lightning me-2"></i>Thao Tác Nhanh
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="mailto:{{ $contact->email }}?subject=Re: {{ urlencode($contact->subject) }}"
                   class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-envelope me-1"></i>Gửi Email Trực Tiếp
                </a>
                @if($contact->phone)
                <a href="tel:{{ $contact->phone }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-telephone me-1"></i>Gọi {{ $contact->phone }}
                </a>
                @endif
                <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}"
                      onsubmit="return confirm('Xoá liên hệ này?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="bi bi-trash me-1"></i>Xoá Liên Hệ
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAiSuggest = document.getElementById('btn-ai-suggest');
    const textareaReply = document.querySelector('textarea[name="admin_reply"]');
    
    if (btnAiSuggest && textareaReply) {
        btnAiSuggest.addEventListener('click', async function() {
            // Hiển thị trạng thái Loading
            const originalHTML = btnAiSuggest.innerHTML;
            btnAiSuggest.disabled = true;
            btnAiSuggest.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                <span>AI đang suy nghĩ...</span>
            `;
            
            try {
                const res = await fetch("{{ route('admin.contacts.ai-suggest', $contact->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                });
                
                const data = await res.json();
                
                if (data.success) {
                    // Đổ dữ liệu gợi ý vào textarea
                    textareaReply.value = data.suggestion;
                    
                    // Thêm hiệu ứng nhấp nháy phát sáng vàng kim
                    textareaReply.classList.add('ai-flash-glow');
                    setTimeout(() => {
                        textareaReply.classList.remove('ai-flash-glow');
                    }, 1500);
                    
                    // Hiển thị Toast thông báo thành công
                    showAiToast('Đã sinh gợi ý phản hồi thông minh dựa trên thông tin & chính sách của shop!', true);
                } else {
                    showAiToast('Lỗi gợi ý AI: ' + data.message, false);
                }
            } catch (err) {
                showAiToast('Lỗi kết nối máy chủ AI!', false);
            } finally {
                // Khôi phục nút bấm
                btnAiSuggest.disabled = false;
                btnAiSuggest.innerHTML = originalHTML;
            }
        });
    }
});

function showAiToast(message, isSuccess = true) {
    const toastContainer = document.getElementById('ai-toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white border-0 mb-2 shadow`;
    toast.style.background = isSuccess 
        ? 'linear-gradient(135deg, #1e1e38 0%, #0d0d21 100%)' 
        : 'linear-gradient(135deg, #721c24 0%, #3e1116 100%)';
    toast.style.minWidth = '320px';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body p-3">
                <strong class="text-warning"><i class="bi bi-stars me-1"></i>Trợ Lý Phản Hồi AI:</strong><br>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 6000 });
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'ai-toast-container';
    container.style.position = 'fixed';
    container.style.bottom = '20px';
    container.style.right = '20px';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>
@endpush
