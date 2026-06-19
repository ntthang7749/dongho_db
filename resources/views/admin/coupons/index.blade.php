@extends('layouts.admin')
@section('title', 'Mã Giảm Giá')
@section('page-title', 'Quản Lý Mã Giảm Giá')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <span class="text-muted small">Tổng: <strong>{{ $coupons->total() }}</strong> mã</span>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i> Tạo Mã Mới
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã code</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Đơn tối thiểu</th>
                    <th class="text-center">Đã dùng / Tổng</th>
                    <th>Hết hạn</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <code class="fs-6 fw-bold text-dark">{{ $coupon->code }}</code>
                    </td>
                    <td>
                        <span class="badge {{ $coupon->type === 'percent' ? 'bg-info text-dark' : 'bg-primary' }}">
                            {{ $coupon->type === 'percent' ? 'Phần trăm' : 'Tiền cố định' }}
                        </span>
                    </td>
                    <td class="fw-bold text-danger">
                        {{ $coupon->type === 'percent'
                            ? $coupon->value . '%'
                            : number_format($coupon->value) . 'đ' }}
                    </td>
                    <td class="small">{{ number_format($coupon->min_order) }}đ</td>
                    <td class="text-center">
                        <span class="badge bg-secondary">
                            {{ $coupon->usage_count }} / {{ $coupon->max_usage }}
                        </span>
                    </td>
                    <td class="small">
                        @if($coupon->expires_at)
                            <span class="{{ $coupon->expires_at->isPast() ? 'text-danger' : 'text-success' }}">
                                {{ $coupon->expires_at->format('d/m/Y') }}
                                {{ $coupon->expires_at->isPast() ? '(Hết hạn)' : '' }}
                            </span>
                        @else
                            <span class="text-muted">Không giới hạn</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $coupon->is_active ? 'Hoạt động' : 'Tắt' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                  id="del-coupon-{{ $coupon->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-coupon-{{ $coupon->id }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Chưa có mã giảm giá nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $coupons->links() }}</div>
</div>
@endsection