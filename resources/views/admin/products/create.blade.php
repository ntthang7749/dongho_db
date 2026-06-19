@extends('layouts.admin')
@section('title', 'Thêm Sản Phẩm')
@section('page-title', 'Thêm Sản Phẩm Mới')

@section('content')
<form method="POST" action="{{ route('admin.products.store') }}"
      enctype="multipart/form-data">
@csrf

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
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="VD: Đồng Hồ Casio G-Shock GA-2100">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @foreach($cat->children as $child)
                                    <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                        &nbsp;&nbsp;└ {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Thương hiệu <span class="text-danger">*</span></label>
                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="">-- Chọn thương hiệu --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fw-semibold mb-0">Mô tả sản phẩm</label>
                        <button type="button" class="btn btn-sm btn-outline-warning"
                                id="aiDescBtn" onclick="generateAIDescription()">
                            <i class="bi bi-stars me-1"></i>
                            AI Tạo Mô Tả
                        </button>
                    </div>
                    <textarea name="description" id="descriptionField"
                            rows="5" class="form-control"
                            placeholder="Nhập mô tả hoặc dùng AI tạo tự động...">{{ old('description') }}</textarea>
                    <div id="aiDescLoading" class="d-none text-center py-2">
                        <div class="spinner-border spinner-border-sm text-warning me-2"></div>
                        <small class="text-muted">AI đang tạo mô tả...</small>
                    </div>
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
                        <label class="form-label fw-semibold">Giá gốc (đ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" placeholder="0" min="0">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Giá khuyến mãi (đ)</label>
                        <input type="number" name="sale_price"
                               class="form-control @error('sale_price') is-invalid @enderror"
                               value="{{ old('sale_price') }}" placeholder="Để trống nếu không KM">
                        @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tồn kho <span class="text-danger">*</span></label>
                        <input type="number" name="stock"
                               class="form-control @error('stock') is-invalid @enderror"
                               value="{{ old('stock', 0) }}" min="0">
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">SKU</label>
                        <input type="text" name="sku" class="form-control"
                               value="{{ old('sku') }}" placeholder="SP001">
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
                               value="{{ old($name) }}" placeholder="{{ $placeholder }}">
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
    </div>

    {{-- RIGHT --}}
    <div class="col-md-4">

        {{-- ẢNH --}}
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-image me-2"></i>Hình Ảnh
            </div>
            <div class="card-body">
                <label class="form-label fw-semibold">Ảnh đại diện (thumbnail)</label>
                <input type="file" name="thumbnail" class="form-control mb-2"
                       accept="image/*" id="thumbnailInput"
                       onchange="previewImage(this, 'thumbnailPreview')">
                <img id="thumbnailPreview" src="" class="img-fluid rounded d-none mb-3"
                     style="max-height:180px; object-fit:cover;">

                <label class="form-label fw-semibold mt-2">Ảnh gallery (nhiều ảnh)</label>
                <input type="file" name="images[]" class="form-control"
                       accept="image/*" multiple>
                <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc (Ctrl + Click)</small>
            </div>
        </div>

        {{-- TUỲ CHỌN --}}
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-toggle-on me-2"></i>Tuỳ Chọn
            </div>
            <div class="card-body">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active"
                           id="is_active" value="1"
                           {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Đang bán (hiện trên website)
                    </label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_featured"
                           id="is_featured" value="1"
                           {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">
                        ⭐ Sản phẩm nổi bật (hiện trang chủ)
                    </label>
                </div>
                <hr class="my-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="export_pdf"
                           id="export_pdf" value="1"
                           {{ old('export_pdf', 1) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold text-success" for="export_pdf">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Tự động xuất hóa đơn PDF
                    </label>
                </div>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-dark btn-lg">
                <i class="bi bi-save me-1"></i> Lưu Sản Phẩm
            </button>
            <a href="{{ route('admin.products.index') }}"
               class="btn btn-outline-secondary">Huỷ bỏ</a>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

async function generateAIDescription() {
    const name = document.querySelector('input[name="name"]').value.trim();
    const brand = document.querySelector('select[name="brand_id"]');
    const cat = document.querySelector('select[name="category_id"]');
    const brandName = (brand.options[brand.selectedIndex]?.text || '')
        .replace(/^\s*--.*--\s*$/, '').trim();
    // Bỏ ký tự "└" + khoảng trắng decoration trong text danh mục con
    const categoryName = (cat.options[cat.selectedIndex]?.text || '')
        .replace(/[└]/g, '').replace(/^\s*--.*--\s*$/, '').trim();

    if (!name) {
        alert('Vui lòng nhập tên sản phẩm trước!');
        return;
    }

    document.getElementById('aiDescBtn').disabled = true;
    document.getElementById('aiDescLoading').classList.remove('d-none');

    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        formData.append('name', name);
        formData.append('brand', brandName);
        formData.append('category', categoryName);
        formData.append('price', document.querySelector('input[name="price"]')?.value || '');
            formData.append('material',         document.querySelector('[name="material"]')?.value || '');
            formData.append('glass_material',   document.querySelector('[name="glass_material"]')?.value || '');
            formData.append('band_material',    document.querySelector('[name="band_material"]')?.value || '');
            formData.append('water_resistance', document.querySelector('[name="water_resistance"]')?.value || '');
            formData.append('movement',         document.querySelector('[name="movement"]')?.value || '');
            formData.append('case_size',        document.querySelector('[name="case_size"]')?.value || '');
            formData.append('color',            document.querySelector('[name="color"]')?.value || '');

            const res  = await fetch('{{ route("admin.ai.description") }}', {
                method: 'POST',
                body:   formData,
            });

            const data = await res.json();

            if (data.success) {
                document.getElementById('descriptionField').value = data.description;

                // Highlight textarea
                document.getElementById('descriptionField').style.border = '2px solid #ffc107';
                setTimeout(() => {
                    document.getElementById('descriptionField').style.border = '';
                }, 2000);
            }

        } catch (e) {
            alert('Có lỗi khi tạo mô tả. Vui lòng thử lại!');
        }

    document.getElementById('aiDescBtn').disabled = false;
    document.getElementById('aiDescLoading').classList.add('d-none');
}
</script>
@endpush