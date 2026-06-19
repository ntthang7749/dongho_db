@extends('layouts.admin')
@section('title', 'Sửa Bài Viết')
@section('page-title', 'Sửa Bài Viết')

@section('content')
<form method="POST" action="{{ route('admin.news.update', $news->id) }}"
      enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-md-8">
        <!-- Hộp Trợ Lý AI Sinh Bài Viết Siêu Tốc -->
        <div class="card table-card mb-4 border-0 shadow" style="background: linear-gradient(135deg, #1e1e2f 0%, #11111d 100%) !important;">
            <div class="card-body p-4 text-white">
                <h6 class="fw-bold text-warning mb-2"><i class="bi bi-stars me-2"></i>Trợ Lý Viết Bài AI Siêu Tốc</h6>
                <p class="text-white-50 small mb-3">Nhập vài từ khóa hoặc ý tưởng ngắn (ví dụ: "Rolex Daytona 2026", "Lịch sử đồng hồ Seiko", "Mẹo bảo quản dây da"), AI sẽ tự động viết toàn bộ tiêu đề, tóm tắt và nội dung bài viết định dạng chuẩn SEO cho bạn trong vài giây!</p>
                <div class="input-group">
                    <input type="text" id="aiKeywords" class="form-control text-white border-warning-subtle" placeholder="Nhập chủ đề hoặc từ khóa tại đây..." style="background: rgba(255,255,255,0.07); border-color: rgba(201, 168, 76, 0.25);">
                    <button class="btn btn-warning fw-bold text-dark px-4" type="button" id="btnAIGenerateNews" onclick="generateNewsAI()">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="aiGenSpinner" role="status" aria-hidden="true"></span>
                        <i class="bi bi-magic me-1" id="aiGenIcon"></i> Sinh Bài Viết
                    </button>
                </div>
                <div class="text-danger small mt-2 d-none" id="aiGenError"></div>
            </div>
        </div>

        <div class="card table-card">
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-semibold mb-0">Tiêu đề bài viết *</label>
                        <button type="button" class="btn btn-sm btn-outline-warning"
                                onclick="suggestTitles()">
                            <i class="bi bi-stars me-1"></i> AI Gợi Ý Tiêu Đề
                        </button>
                    </div>
                    <input type="text" name="title" class="form-control"
                        id="titleField"
                        value="{{ old('title', $news->title) }}"
                        placeholder="Tiêu đề hấp dẫn...">

                    {{-- Gợi ý tiêu đề --}}
                    <div id="titleSuggestions" class="mt-2 d-none">
                        <small class="text-muted">Chọn tiêu đề gợi ý:</small>
                        <div id="titleList" class="mt-1"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tóm tắt</label>
                    <textarea name="summary" id="summaryField" rows="2" class="form-control"
                              placeholder="Mô tả ngắn hiển thị ở danh sách bài viết...">{{ old('summary', $news->summary) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nội dung *</label>
                    <textarea name="content" id="contentField" rows="15"
                              class="form-control">{{ old('content', $news->content) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card table-card mb-3">
            <div class="card-header">Ảnh đại diện</div>
            <div class="card-body">
                @if($news->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$news->thumbnail) }}"
                             class="img-fluid rounded"
                             style="max-height:120px; object-fit:cover;">
                    </div>
                @endif
                <input type="file" name="thumbnail" class="form-control"
                       accept="image/*"
                       onchange="previewImage(this,'thumbPreview')">
                <img id="thumbPreview" src="" class="img-fluid rounded mt-2 d-none"
                     style="max-height:120px; object-fit:cover;">
            </div>
        </div>
        <div class="card table-card mb-3">
            <div class="card-body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"
                           name="is_active" value="1"
                           {{ old('is_active', $news->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold">Hiển thị bài viết</label>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-dark">
                <i class="bi bi-save me-1"></i> Lưu Thay Đổi
            </button>
            <a href="{{ route('admin.news.index') }}"
               class="btn btn-outline-secondary">Huỷ</a>
        </div>
    </div>
</div>
</form>
@push('scripts')
<script>
function previewImage(input, id) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const el = document.getElementById(id);
            el.src = e.target.result;
            el.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

async function suggestTitles() {
    const content = document.querySelector('textarea[name="content"]').value;

    if (content.length < 50) {
        alert('Vui lòng nhập nội dung trước (ít nhất 50 ký tự)!');
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('content', content);

    try {
        const res  = await fetch('{{ route("admin.ai.titles") }}', {
            method: 'POST',
            body:   formData,
        });

        const data = await res.json();

        if (data.success && data.titles) {
            const list = document.getElementById('titleList');
            list.innerHTML = data.titles.map((t, i) => `
                <div class="p-2 border rounded mb-1 cursor-pointer bg-light
                            title-suggestion-item"
                    style="cursor:pointer; transition:.2s;"
                    onmouseover="this.style.background='#fff3cd'"
                    onmouseout="this.style.background='#f8f9fa'"
                    onclick="selectTitle('${t.replace(/'/g, "\\'")}')">
                    <span class="badge bg-secondary me-2">${i + 1}</span>
                    ${t}
                </div>
            `).join('');

            document.getElementById('titleSuggestions').classList.remove('d-none');
        }

    } catch (e) {
        alert('Có lỗi khi gợi ý tiêu đề!');
    }
}

function selectTitle(title) {
    document.getElementById('titleField').value = title;

    // Highlight
    document.getElementById('titleField').style.border = '2px solid #ffc107';
    setTimeout(() => {
        document.getElementById('titleField').style.border = '';
    }, 2000);

    document.getElementById('titleSuggestions').classList.add('d-none');
}

async function generateNewsAI() {
    const prompt = document.getElementById('aiKeywords').value.trim();
    if (!prompt) {
        alert('Vui lòng nhập từ khóa hoặc chủ đề bài viết gợi ý!');
        return;
    }

    const btn = document.getElementById('btnAIGenerateNews');
    const spinner = document.getElementById('aiGenSpinner');
    const icon = document.getElementById('aiGenIcon');
    const errDiv = document.getElementById('aiGenError');

    // Loading state
    btn.disabled = true;
    spinner.classList.remove('d-none');
    icon.classList.add('d-none');
    errDiv.classList.add('d-none');

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('prompt', prompt);

    try {
        const res = await fetch('{{ route("admin.ai.generate-news") }}', {
            method: 'POST',
            body: formData,
        });

        const data = await res.json();

        if (data.success && data.news) {
            // Populate fields
            document.getElementById('titleField').value = data.news.title || '';
            document.getElementById('summaryField').value = data.news.summary || '';
            document.getElementById('contentField').value = data.news.content || '';

            // Flash highlights
            const fields = ['titleField', 'summaryField', 'contentField'];
            fields.forEach(fid => {
                const el = document.getElementById(fid);
                if (el) {
                    el.style.border = '2.5px solid #ffc107';
                    el.style.boxShadow = '0 0 15px rgba(201, 168, 76, 0.4)';
                    setTimeout(() => {
                        el.style.border = '';
                        el.style.boxShadow = '';
                    }, 3000);
                }
            });
        } else {
            errDiv.textContent = 'AI phản hồi không đúng cấu trúc hoặc đang bận. Vui lòng thử lại!';
            errDiv.classList.remove('d-none');
        }
    } catch (e) {
        errDiv.textContent = 'Có lỗi xảy ra trong quá trình sinh bài viết!';
        errDiv.classList.remove('d-none');
    } finally {
        // Restore state
        btn.disabled = false;
        spinner.classList.add('d-none');
        icon.classList.remove('d-none');
    }
}
</script>
@endpush
@endsection