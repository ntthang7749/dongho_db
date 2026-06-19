@extends('layouts.admin')
@section('title', 'Thêm Danh Mục')
@section('page-title', 'Thêm Danh Mục Mới')

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card table-card">
    <div class="card-header"><i class="bi bi-grid me-2"></i>Thông Tin Danh Mục</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}"
              enctype="multipart/form-data">
        @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Tên danh mục *</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="VD: Đồng hồ nam">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Danh mục cha</label>
                <select name="parent_id" class="form-select">
                    <option value="">— Là danh mục cấp 1 —</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}"
                                {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Để trống nếu đây là danh mục cấp 1</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả</label>
                <textarea name="description" rows="3"
                          class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control"
                           value="{{ old('sort_order', 0) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Hình ảnh</label>
                    <input type="file" name="image" class="form-control"
                           accept="image/*"
                           onchange="previewImage(this,'imgPreview')">
                    <img id="imgPreview" src="" class="img-fluid rounded mt-2 d-none"
                         style="max-height:100px;">
                </div>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1"
                       {{ old('is_active', 1) ? 'checked' : '' }}>
                <label class="form-check-label">Hiển thị danh mục</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Lưu Danh Mục
                </button>
                <a href="{{ route('admin.categories.index') }}"
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