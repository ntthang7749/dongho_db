@extends('layouts.admin')
@section('title', 'Thêm Thương Hiệu')
@section('page-title', 'Thêm Thương Hiệu Mới')

@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card table-card">
    <div class="card-header"><i class="bi bi-tag me-2"></i>Thông Tin Thương Hiệu</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.brands.store') }}"
              enctype="multipart/form-data">
        @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Tên thương hiệu *</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="VD: Casio, Seiko...">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả</label>
                <textarea name="description" rows="3"
                          class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Logo thương hiệu</label>
                <input type="file" name="logo" class="form-control"
                       accept="image/*"
                       onchange="previewImage(this,'logoPreview')">
                <img id="logoPreview" src="" class="mt-2 d-none rounded"
                     style="max-height:80px; object-fit:contain;">
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1"
                       {{ old('is_active', 1) ? 'checked' : '' }}>
                <label class="form-check-label">Hiển thị thương hiệu</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Lưu
                </button>
                <a href="{{ route('admin.brands.index') }}"
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