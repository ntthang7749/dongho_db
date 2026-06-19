@extends('layouts.admin')
@section('title', 'Tạo Mã Giảm Giá')
@section('page-title', 'Tạo Mã Giảm Giá Mới')

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card table-card">
    <div class="card-header"><i class="bi bi-ticket-perforated me-2"></i>Thông Tin Mã Giảm Giá</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mã code *</label>
                    <input type="text" name="code"
                           class="form-control text-uppercase @error('code') is-invalid @enderror"
                           value="{{ old('code') }}"
                           placeholder="VD: SALE20"
                           style="letter-spacing:2px; font-weight:bold;">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Loại giảm *</label>
                    <select name="type" class="form-select" id="couponType"
                            onchange="toggleValueLabel()">
                        <option value="percent"  {{ old('type')==='percent' ? 'selected':'' }}>
                            Phần trăm (%)
                        </option>
                        <option value="fixed"    {{ old('type')==='fixed' ? 'selected':'' }}>
                            Tiền cố định (đ)
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" id="valueLabel">Giá trị (%) *</label>
                    <input type="number" name="value"
                           class="form-control @error('value') is-invalid @enderror"
                           value="{{ old('value') }}" min="1">
                    @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Đơn tối thiểu (đ)</label>
                    <input type="number" name="min_order" class="form-control"
                           value="{{ old('min_order', 0) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Giảm tối đa (đ)</label>
                    <input type="number" name="max_discount" class="form-control"
                           value="{{ old('max_discount') }}"
                           placeholder="Để trống = không giới hạn">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Số lượt dùng tối đa *</label>
                    <input type="number" name="max_usage" class="form-control"
                           value="{{ old('max_usage', 100) }}" min="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Ngày bắt đầu</label>
                    <input type="datetime-local" name="starts_at" class="form-control"
                           value="{{ old('starts_at') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Ngày hết hạn</label>
                    <input type="datetime-local" name="expires_at" class="form-control"
                           value="{{ old('expires_at') }}">
                </div>
            </div>

            <div class="form-check form-switch mt-3 mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1"
                       {{ old('is_active', 1) ? 'checked' : '' }}>
                <label class="form-check-label">Kích hoạt mã ngay</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Tạo Mã
                </button>
                <a href="{{ route('admin.coupons.index') }}"
                   class="btn btn-outline-secondary">Huỷ</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>

@push('scripts')
<script>
function toggleValueLabel() {
    const type  = document.getElementById('couponType').value;
    const label = document.getElementById('valueLabel');
    label.textContent = type === 'percent' ? 'Giá trị (%) *' : 'Giá trị (đ) *';
}
toggleValueLabel();
</script>
@endpush
@endsection