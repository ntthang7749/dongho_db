@extends('layouts.admin')
@section('title', 'Sửa Mã Giảm Giá')
@section('page-title', 'Sửa Mã: ' . $coupon->code)

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card table-card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}">
        @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mã code</label>
                    <input type="text" class="form-control bg-light"
                           value="{{ $coupon->code }}" readonly
                           style="letter-spacing:2px; font-weight:bold;">
                    <small class="text-muted">Mã code không thể thay đổi</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Loại giảm</label>
                    <select name="type" class="form-select" id="couponType"
                            onchange="toggleValueLabel()">
                        <option value="percent" {{ $coupon->type==='percent' ? 'selected':'' }}>Phần trăm (%)</option>
                        <option value="fixed"   {{ $coupon->type==='fixed' ? 'selected':'' }}>Tiền cố định (đ)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" id="valueLabel">Giá trị *</label>
                    <input type="number" name="value" class="form-control"
                           value="{{ old('value', $coupon->value) }}" min="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Đơn tối thiểu (đ)</label>
                    <input type="number" name="min_order" class="form-control"
                           value="{{ old('min_order', $coupon->min_order) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Số lượt tối đa *</label>
                    <input type="number" name="max_usage" class="form-control"
                           value="{{ old('max_usage', $coupon->max_usage) }}" min="1">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ngày bắt đầu</label>
                    <input type="datetime-local" name="starts_at" class="form-control"
                           value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ngày hết hạn</label>
                    <input type="datetime-local" name="expires_at" class="form-control"
                           value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}">
                </div>
            </div>

            <div class="form-check form-switch mt-3 mb-4">
                <input class="form-check-input" type="checkbox"
                       name="is_active" value="1"
                       {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                <label class="form-check-label">Kích hoạt mã</label>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Lưu Thay Đổi
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
    document.getElementById('valueLabel').textContent =
        type === 'percent' ? 'Giá trị (%) *' : 'Giá trị (đ) *';
}
toggleValueLabel();
</script>
@endpush
@endsection