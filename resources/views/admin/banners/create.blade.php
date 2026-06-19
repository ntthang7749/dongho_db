@extends('layouts.admin')
@section('title', 'Thêm Banner')
@section('page-title', 'Thêm Banner Mới')

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card table-card">
    <div class="card-header"><i class="bi bi-image me-2"></i>Thông Tin Banner</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.banners.store') }}"
              enctype="multipart/form-data">
        @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Tiêu đề *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title') }}"
                       placeholder="VD: Đồng Hồ Casio - Sale 50%">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Hình ảnh banner *</label>
                <input type="file" name="image" class="form-control"
                       accept="image/*" required
                       onchange="previewImage(this,'bannerPreview')">
                <small class="text-muted">Khuyến nghị kích thước: 1920x500px</small>
                <img id="bannerPreview" src="" class="img-fluid rounded mt-2 d-none"
                     style="max-height:200px; object-fit:cover;">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Link khi click (tuỳ chọn)</label>
                <input type="url" name="link" class="form-control"
                       value="{{ old('link') }}"
                       placeholder="https://...">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="form-control"
                       value="{{ old('sort_order', 0) }}" min="0">
            </div>
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1"
                       {{ old('is_active', 1) ? 'checked' : '' }}>
                <label class="form-check-label">Hiển thị banner</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Lưu Banner
                </button>
                <a href="{{ route('admin.banners.index') }}"
                   class="btn btn-outline-secondary">Huỷ</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

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
</script>
@endpush
@endsection