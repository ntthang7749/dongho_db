@extends('layouts.admin')
@section('title', 'Sửa Sản Phẩm')
@section('page-title', 'Sửa Sản Phẩm: ' . $product->name)

@section('content')
<form method="POST" action="{{ route('admin.products.update', $product->id) }}"
      enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-4">
    {{-- LEFT --}}
    <div class="col-md-8">

        {{-- THÔNG TIN CƠ BẢN --}}
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Thông Tin Cơ Bản
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                        {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->id }}"
                                            {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;└ {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Thương hiệu <span class="text-danger">*</span></label>
                        <select name="brand_id"
                                class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">Mô tả sản phẩm</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- GIÁ & TỒN KHO --}}
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-cash me-2"></i>Giá & Tồn Kho
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá gốc (đ) *</label>
                        <input type="number" name="price" class="form-control"
                               value="{{ old('price', $product->price) }}" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá khuyến mãi (đ)</label>
                        <input type="number" name="sale_price" class="form-control"
                               value="{{ old('sale_price', $product->sale_price) }}"
                               placeholder="Để trống nếu không KM">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tồn kho *</label>
                        <input type="number" name="stock" class="form-control"
                               value="{{ old('stock', $product->stock) }}" min="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">SKU</label>
                        <input type="text" name="sku" class="form-control"
                               value="{{ old('sku', $product->sku) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- THÔNG SỐ KỸ THUẬT --}}
        @php
            $options = [
                'material' => [
                    'Thép không gỉ 316L',
                    'Titanium siêu nhẹ',
                    'Nhựa chuyên dụng / Resin',
                    'Vàng nguyên khối 18K'
                ],
                'glass_material' => [
                    'Kính Sapphire chống xước',
                    'Kính khoáng Mineral Glass',
                    'Kính Hardlex độc quyền'
                ],
                'band_material' => [
                    'Dây kim loại / Thép không gỉ',
                    'Dây da bò / Da cá sấu cao cấp',
                    'Dây cao su / Silicone năng động',
                    'Dây vải / Nato bền bỉ'
                ],
                'water_resistance' => [
                    '3 ATM (Rửa tay, đi mưa nhẹ)',
                    '5 ATM (Tắm bồn, đi mưa lớn)',
                    '10 ATM (Bơi lội thoải mái)',
                    '20 ATM (Lặn biển chuyên nghiệp)'
                ],
                'movement' => [
                    'Automatic (Máy cơ tự động)',
                    'Quartz (Máy pin thạch anh)',
                    'Eco-Drive / Solar (Năng lượng ánh sáng)'
                ],
                'case_size' => [
                    '36mm',
                    '38mm',
                    '40mm',
                    '42mm',
                    '44mm'
                ],
                'color' => [
                    'Đen',
                    'Bạc',
                    'Vàng Gold',
                    'Xanh Navy',
                    'Trắng'
                ]
            ];
        @endphp
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-sliders me-2"></i>Thông Số Kỹ Thuật
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach([
                        ['material',        'Chất liệu vỏ',     'VD: Thép không gỉ 316L'],
                        ['glass_material',  'Chất liệu kính',   'VD: Kính Sapphire chống xước'],
                        ['band_material',   'Chất liệu dây',    'VD: Dây da, dây kim loại'],
                        ['water_resistance','Chống nước',       'VD: 5 ATM, 10 ATM'],
                        ['movement',        'Bộ máy',           'VD: Quartz, Automatic'],
                        ['case_size',       'Kích thước mặt',   'VD: 40mm, 42mm'],
                        ['color',           'Màu sắc',          'VD: Đen, Bạc, Vàng Gold'],
                    ] as [$name, $label, $placeholder])
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ $label }}</label>
                        <input type="text" name="{{ $name }}" list="list_{{ $name }}" 
                               class="form-control @error($name) is-invalid @enderror"
                               value="{{ old($name, $product->$name) }}" placeholder="{{ $placeholder }}">
                        <datalist id="list_{{ $name }}">
                            @foreach($options[$name] as $opt)
                                <option value="{{ $opt }}">
                            @endforeach
                        </datalist>
                        @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- GALLERY ẢNH HIỆN TẠI --}}
        @if($product->images->count() > 0)
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-images me-2"></i>Ảnh Gallery Hiện Tại
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($product->images as $img)
                    <div class="col-3 position-relative" id="img-{{ $img->id }}">
                        <img src="{{ asset('storage/' . $img->image) }}"
                             class="img-fluid rounded" style="height:100px; object-fit:cover;">
                        <button type="button"
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0"
                                style="width:22px; height:22px; font-size:11px;"
                                onclick="deleteImage({{ $img->id }})">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT --}}
    <div class="col-md-4">

        {{-- THUMBNAIL --}}
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-image me-2"></i>Hình Ảnh
            </div>
            <div class="card-body">
                {{-- Thumbnail hiện tại --}}
                @if($product->thumbnail)
                <div class="mb-3">
                    <p class="small text-muted mb-1">Ảnh đại diện hiện tại:</p>
                    <img src="{{ asset('storage/' . $product->thumbnail) }}"
                         class="img-fluid rounded" style="max-height:150px; object-fit:cover;">
                </div>
                @endif

                <label class="form-label fw-semibold">Thay ảnh đại diện</label>
                <input type="file" name="thumbnail" class="form-control mb-2"
                       accept="image/*" id="thumbnailInput"
                       onchange="previewImage(this, 'thumbnailPreview')">
                <img id="thumbnailPreview" src="" class="img-fluid rounded d-none mb-3"
                     style="max-height:150px; object-fit:cover;">

                <label class="form-label fw-semibold mt-2">Thêm ảnh gallery</label>
                <input type="file" name="images[]" class="form-control"
                       accept="image/*" multiple>
                <small class="text-muted">Giữ Ctrl để chọn nhiều ảnh</small>
            </div>
        </div>

        {{-- TUỲ CHỌN --}}
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-toggle-on me-2"></i>Tuỳ Chọn
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox"
                           name="is_active" id="is_active" value="1"
                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Đang bán</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox"
                           name="is_featured" id="is_featured" value="1"
                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">⭐ Sản phẩm nổi bật</label>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-dark btn-lg">
                <i class="bi bi-save me-1"></i> Lưu Thay Đổi
            </button>
            <a href="{{ route('admin.products.invoice', $product->id) }}"
               class="btn btn-outline-success btn-lg" target="_blank">
                <i class="bi bi-file-earmark-pdf me-1"></i> Xuất Hóa Đơn PDF
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="btn btn-outline-secondary">Huỷ bỏ</a>
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

// Xoá ảnh gallery bằng AJAX
function deleteImage(id) {
    if (!confirm('Xoá ảnh này?')) return;
    fetch(`/admin/product-images/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(r => r.json()).then(data => {
        if (data.success) {
            document.getElementById(`img-${id}`).remove();
        }
    });
}
</script>
@endpush
@endsection