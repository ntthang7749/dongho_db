@extends('layouts.admin')
@section('title', 'Sửa Danh Mục')
@section('page-title', 'Sửa Danh Mục: ' . $category->name)

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card table-card">
    <div class="card-header"><i class="bi bi-pencil me-2"></i>Sửa Danh Mục</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}"
              enctype="multipart/form-data">
        @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Tên danh mục *</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $category->name) }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Danh mục cha</label>
                <select name="parent_id" class="form-select">
                    <option value="">— Danh mục cấp 1 —</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}"
                                {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Mô tả</label>
                <textarea name="description" rows="3"
                          class="form-control">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control"
                           value="{{ old('sort_order', $category->sort_order) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Hình ảnh</label>
                    @if($category->image)
                        <div class="d-flex align-items-center gap-3 mb-2 p-2 rounded border" style="background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.08) !important;">
                            <img src="{{ asset('storage/'.$category->image) }}"
                                 class="rounded animate-hover" style="height:50px; width:50px; object-fit:cover; border: 1px solid rgba(201,168,76,0.2);">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="delete_image" id="delete_image" value="1">
                                <label class="form-check-label text-danger small fw-semibold" for="delete_image">Xóa ảnh hiện tại</label>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control"
                           accept="image/*"
                           onchange="previewImage(this,'imgPreview')">
                    <img id="imgPreview" src="" class="img-fluid rounded mt-2 d-none"
                         style="max-height:80px;">
                </div>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1"
                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <label class="form-check-label">Hiển thị danh mục</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Lưu Thay Đổi
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