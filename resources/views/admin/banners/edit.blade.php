@extends('layouts.admin')
@section('title', 'Sửa Banner')
@section('page-title', 'Sửa Banner: ' . $banner->title)

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card table-card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.banners.update', $banner->id) }}"
              enctype="multipart/form-data">
        @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Tiêu đề *</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $banner->title) }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Hình ảnh hiện tại</label>
                <img src="{{ img_url($banner->image) }}"
                     class="d-block img-fluid rounded mb-2"
                     style="max-height:150px; object-fit:cover;">
                <label class="form-label fw-semibold">Thay ảnh mới</label>
                <input type="file" name="image" class="form-control"
                       accept="image/*"
                       onchange="previewImage(this,'bannerPreview')">
                <img id="bannerPreview" src="" class="img-fluid rounded mt-2 d-none"
                     style="max-height:150px; object-fit:cover;">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Link khi click</label>
                <input type="url" name="link" class="form-control"
                       value="{{ old('link', $banner->link) }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Thứ tự</label>
                <input type="number" name="sort_order" class="form-control"
                       value="{{ old('sort_order', $banner->sort_order) }}" min="0">
            </div>
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1"
                       {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                <label class="form-check-label">Hiển thị banner</label>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Lưu Thay Đổi
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