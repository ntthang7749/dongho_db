@extends('layouts.customer')
@section('title', 'Nhận Diện Đồng Hồ Bằng AI')

@push('styles')
<style>
    .ai-hero-icon {
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f;
        box-shadow: 0 8px 20px rgba(201, 168, 76, 0.3);
    }
    
    /* ── Drop Zone ── */
    .drop-zone-custom {
        border: 2.5px dashed rgba(201, 168, 76, 0.25) !important;
        background: rgba(201, 168, 76, 0.01);
        cursor: pointer;
        min-height: 240px;
        transition: all 0.3s ease;
    }
    .drop-zone-custom:hover, .drop-zone-custom.drag-hover {
        border-color: var(--gold) !important;
        background: rgba(201, 168, 76, 0.05);
        box-shadow: inset 0 0 20px rgba(201, 168, 76, 0.08);
    }
    
    .preview-image-box {
        max-height: 220px;
        object-fit: contain;
        border: 1px solid rgba(201, 168, 76, 0.15);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    }

    /* ── Card AI ── */
    .ai-result-card {
        border: 1px solid rgba(201, 168, 76, 0.2) !important;
        border-radius: 16px !important;
        overflow: hidden;
    }
    .ai-result-card .card-header {
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%) !important;
        border-bottom: 1px solid rgba(201,168,76,0.18) !important;
        color: var(--gold) !important;
    }

    /* ── Button ── */
    .btn-ai-analyze {
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #0a0a0f !important;
        border: none;
        transition: all 0.25s;
        box-shadow: 0 4px 14px rgba(201, 168, 76, 0.25);
    }
    .btn-ai-analyze:hover {
        background: linear-gradient(135deg, var(--gold-light), var(--gold));
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(201, 168, 76, 0.4);
    }
</style>
@endpush

@section('content')
<div class="breadcrumb-wrap py-3 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nhận diện AI</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="text-center mb-5">
        <div class="ai-hero-icon d-inline-flex align-items-center justify-content-center rounded-circle mb-3">
            <i class="bi bi-cpu fs-2"></i>
        </div>
        <h3 class="fw-bold" style="font-family: 'Playfair Display', serif;">Trí Tuệ Nhân Tạo Nhận Diện Đồng Hồ</h3>
        <p class="text-muted small mx-auto" style="max-width: 500px;">
            Hãy tải lên một tấm ảnh đồng hồ bất kỳ. Thuật toán AI tiên tiến sẽ phân tích và đề xuất các sản phẩm tương đồng nhất trong cửa hàng của chúng tôi.
        </p>
    </div>

    <div class="row justify-content-center g-4">
        {{-- UPLOAD FORM --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-upload text-gold me-2"></i>Tải Ảnh Đồng Hồ Lên
                    </h6>

                    {{-- Drop zone --}}
                    <div id="dropZone"
                         class="drop-zone-custom rounded-4 p-4 text-center mb-3 d-flex flex-column align-items-center justify-content-center"
                         onclick="document.getElementById('imageInput').click()"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event)">

                        <div id="dropContent" class="py-3">
                            <i class="bi bi-cloud-arrow-up fs-1 text-gold opacity-75 mb-2 d-block"></i>
                            <p class="text-dark fw-medium small mb-1">
                                Kéo & thả ảnh vào đây hoặc click để chọn
                            </p>
                            <small class="text-muted fs-8">
                                Hỗ trợ JPG, PNG, WEBP — dung lượng tối đa 5MB
                            </small>
                        </div>

                        <img id="previewImg" src="" class="preview-image-box img-fluid rounded-3 d-none">
                    </div>

                    <input type="file" id="imageInput" class="d-none"
                           accept="image/*"
                           onchange="previewImage(this)">

                    <button id="analyzeBtn"
                            class="btn btn-ai-analyze w-100 py-3 fw-bold rounded-3 d-none"
                            onclick="analyzeImage()">
                        <i class="bi bi-stars me-2"></i> Phân Tích Bằng AI
                    </button>

                    <div id="loadingState" class="text-center d-none py-4">
                        <div class="spinner-border text-warning mb-2" style="width:2.5rem; height:2.5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted small mb-0 fw-medium">
                            AI đang bóc tách đặc trưng hình ảnh...
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KẾT QUẢ --}}
        <div class="col-md-7" id="resultSection" style="display:none;">

            {{-- Thông tin nhận diện --}}
            <div class="card ai-result-card border-0 shadow-sm mb-4" id="recognitionCard">
                <div class="card-header fw-bold py-3 px-4">
                    <i class="bi bi-robot me-2"></i>Báo Cáo Phân Tích AI
                </div>
                <div class="card-body p-4 bg-white" id="recognitionBody">
                </div>
            </div>

            {{-- Sản phẩm tương tự --}}
            <div id="similarSection" class="d-none">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-grid me-2 text-gold"></i>Đề Xuất Sản Phẩm Tương Tự
                </h6>
                <div class="row g-3" id="similarProducts"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedFile = null;

// Preview ảnh
function previewImage(input) {
    if (!input.files || !input.files[0]) return;

    selectedFile = input.files[0];

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('dropContent').classList.add('d-none');
        const preview = document.getElementById('previewImg');
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        document.getElementById('analyzeBtn').classList.remove('d-none');
        document.getElementById('resultSection').style.display = 'none';
    };
    reader.readAsDataURL(selectedFile);
}

// Drag & Drop
function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-hover');
}

function handleDragLeave(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-hover');
}

function handleDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-hover');

    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        selectedFile = file;
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('imageInput').files = dt.files;
        previewImage(document.getElementById('imageInput'));
    }
}

// Phân tích ảnh
async function analyzeImage() {
    if (!selectedFile) return;

    document.getElementById('analyzeBtn').classList.add('d-none');
    document.getElementById('loadingState').classList.remove('d-none');
    document.getElementById('resultSection').style.display = 'none';

    const formData = new FormData();
    formData.append('image', selectedFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        const res  = await fetch('{{ route("ai.image.recognize") }}', {
            method: 'POST',
            body:   formData,
        });

        const data = await res.json();

        document.getElementById('loadingState').classList.add('d-none');
        document.getElementById('analyzeBtn').classList.remove('d-none');

        if (data.success) {
            renderRecognition(data.recognition);
            renderSimilarProducts(data.similar_products);
            document.getElementById('resultSection').style.display = 'block';
        }

    } catch (e) {
        document.getElementById('loadingState').classList.add('d-none');
        document.getElementById('analyzeBtn').classList.remove('d-none');
        alert('Có lỗi xảy ra trong quá trình nhận diện. Vui lòng thử lại!');
    }
}

// Render kết quả nhận diện
function renderRecognition(r) {
    const confidence = Math.round((r.confidence || 0) * 100);

    const fields = [
        { label: '🏷️ Thương hiệu',    value: r.brand },
        { label: '👤 Loại đồng hồ',    value: r.type },
        { label: '✨ Phong cách',      value: r.style },
        { label: '🎨 Tone màu chính',  value: r.color },
        { label: '⌚ Chất liệu dây',   value: r.band_material },
        { label: '📏 Size mặt ước lượng', value: r.estimated_size },
        { label: '💰 Khung giá ước tính', value: r.estimated_price_min
            ? `${Number(r.estimated_price_min).toLocaleString('vi')}đ`
              + ` — ${Number(r.estimated_price_max || r.estimated_price_min * 2).toLocaleString('vi')}đ`
            : null },
    ].filter(f => f.value);

    const featuresHtml = (r.features || []).map(f =>
        `<span class="badge bg-light text-secondary border me-1.5 mb-1.5 px-2.5 py-1.5 fs-8 fw-semibold">${f}</span>`
    ).join('');

    let matchAlertHtml = '';
    if (r.is_exact_match) {
        matchAlertHtml = `
            <div class="alert alert-warning border-0 text-dark px-3 py-2.5 rounded-3 mb-4 d-flex align-items-start gap-2.5 shadow-sm" style="background: rgba(201, 168, 76, 0.12); border-left: 4px solid #c9a84c !important;">
                <i class="bi bi-patch-check-fill text-warning fs-5" style="color: #c9a84c !important;"></i>
                <div>
                    <strong class="small fw-bold d-block text-dark mb-0.5">Tìm thấy ảnh trùng khớp 100% trong kho!</strong>
                    <span class="d-block text-secondary" style="font-size: 0.78rem; line-height: 1.4;">Hệ thống đã tự động định vị chính xác sản phẩm gốc trong danh mục cửa hàng mà không cần gọi qua API AI nhận diện (giúp tải trang siêu tốc & tiết kiệm Token).</span>
                </div>
            </div>
        `;
    }

    document.getElementById('recognitionBody').innerHTML = `
        ${matchAlertHtml}

        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small fw-medium">Độ tin cậy AI</span>
            <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1 bg-light rounded-pill" style="width:100px; height:8px;">
                    <div class="progress-bar bg-success rounded-pill" style="width:${confidence}%"></div>
                </div>
                <span class="fw-bold text-success small">${confidence}%</span>
            </div>
        </div>

        <div class="row g-3 mb-4">
            ${fields.map(f => `
            <div class="col-6">
                <div class="p-3 bg-light rounded-3 border-0">
                    <small class="text-muted d-block mb-1 fs-8">${f.label}</small>
                    <strong class="small text-dark fw-bold">${f.value}</strong>
                </div>
            </div>`).join('')}
        </div>

        ${featuresHtml ? `
        <div class="mb-4">
            <small class="text-muted d-block mb-2">⚙️ Đặc tính công nghệ: </small>
            <div class="d-flex flex-wrap">${featuresHtml}</div>
        </div>` : ''}

        ${r.description ? `
        <div class="p-3 rounded-3 small border-start border-3 border-warning bg-light" style="font-style: italic;">
            <i class="bi bi-quote me-1 text-gold fs-5 align-middle"></i>
            <span class="text-secondary">${r.description}</span>
        </div>` : ''}
    `;
}

// Render sản phẩm tương tự
function renderSimilarProducts(products) {
    if (!products || products.length === 0) {
        document.getElementById('similarSection').classList.add('d-none');
        return;
    }

    document.getElementById('similarSection').classList.remove('d-none');

    document.getElementById('similarProducts').innerHTML = products.map(p => `
        <div class="col-6">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.05) !important;">
                <div style="height: 170px; background: #faf9f5; position: relative;">
                    <img src="${p.thumbnail || '/images/no-image.png'}"
                         class="w-100 h-100 object-fit-cover">
                </div>
                <div class="card-body p-3 bg-white d-flex flex-column justify-content-between">
                    <div>
                        <p class="small fw-bold text-dark mb-1 text-truncate">${p.name}</p>
                        <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">${p.brand}</small>
                        <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap gap-1">
                            <span class="text-danger fw-extrabold small">${p.price}</span>
                            <div class="small d-flex align-items-center gap-0.5">
                                <i class="bi bi-star-fill text-warning fs-8"></i>
                                <small class="text-muted font-bold">${p.rating}</small>
                            </div>
                        </div>
                    </div>
                    <a href="${p.url}"
                       class="btn btn-dark btn-sm w-100 mt-3 py-2 rounded-3 border-0 fw-semibold fs-7"
                       style="background: #1a1a2e; transition: all 0.2s;">
                        Xem Sản Phẩm
                    </a>
                </div>
            </div>
        </div>
    `).join('');
}
</script>
@endpush
@endsection