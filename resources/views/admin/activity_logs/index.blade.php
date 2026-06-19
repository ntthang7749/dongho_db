@extends('layouts.admin')

@section('title', 'Nhật Ký Hoạt Động Hệ Thống')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb & Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-bold text-dark">📋 Nhật Ký Hoạt Động Hệ Thống</h4>
                <p class="text-muted small mb-0">Theo dõi toàn bộ lịch sử thao tác của các quản trị viên và giao dịch quan trọng của khách hàng.</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-3">
                        <label class="form-label text-secondary fw-semibold small">Tìm kiếm mô tả</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-0" 
                                   placeholder="Ví dụ: tên SP, mã đơn..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Thao tác -->
                    <div class="col-md-2">
                        <label class="form-label text-secondary fw-semibold small">Loại thao tác</label>
                        <select name="action" class="form-select bg-light border-0">
                            <option value="">-- Tất cả --</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ $action }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Đối tượng tác động -->
                    <div class="col-md-2">
                        <label class="form-label text-secondary fw-semibold small">Đối tượng</label>
                        <select name="target_type" class="form-select bg-light border-0">
                            <option value="">-- Tất cả --</option>
                            @foreach($targetTypes as $type)
                                <option value="{{ $type }}" {{ request('target_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Người dùng thực hiện -->
                    <div class="col-md-3">
                        <label class="form-label text-secondary fw-semibold small">Người thực hiện</label>
                        <select name="user_id" class="form-select bg-light border-0">
                            <option value="">-- Tất cả thành viên --</option>
                            @foreach($usersWithLogs as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }} ({{ $u->role === 'admin' ? 'Admin' : 'Khách' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action buttons -->
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-3">
                            <i class="bi bi-funnel me-1"></i> Lọc
                        </button>
                        @if(request()->anyFilled(['search', 'action', 'target_type', 'user_id']))
                            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-light w-100 rounded-3 border">
                                <i class="bi bi-x-circle me-1"></i> Hủy
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Logs Table Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-list-stars text-primary me-2"></i>Danh sách nhật ký</h5>
                    <span class="badge bg-primary rounded-pill">Tổng số: {{ $logs->total() }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th class="py-3 ps-4" style="width: 80px;">ID</th>
                                <th class="py-3">Thành viên</th>
                                <th class="py-3" style="width: 140px;">Thao tác</th>
                                <th class="py-3" style="width: 120px;">Đối tượng</th>
                                <th class="py-3">Mô tả chi tiết</th>
                                <th class="py-3" style="width: 150px;">IP / Thiết bị</th>
                                <th class="py-3 ps-3 pe-4" style="width: 160px;">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4 text-muted font-monospace small">#{{ $log->id }}</td>
                                    <td>
                                        @if($log->user)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm flex-shrink-0" style="width: 32px; height: 32px;">
                                                    @if($log->user->avatarUrl())
                                                        <img src="{{ $log->user->avatarUrl() }}" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;" alt="avatar">
                                                    @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold small text-white" 
                                                             style="background: linear-gradient(135deg, #c9a84c, #a07c30); width: 32px; height: 32px;">
                                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold small text-dark" style="font-size: 0.85rem;">{{ $log->user->name }}</h6>
                                                    <span class="badge {{ $log->user->role === 'admin' ? 'bg-danger-subtle text-danger' : 'bg-info-subtle text-info' }}" style="font-size: 0.65rem;">
                                                        {{ $log->user->role === 'admin' ? 'Admin' : 'Customer' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center text-secondary" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-robot small"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold small text-secondary" style="font-size: 0.85rem;">Hệ thống</h6>
                                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.65rem;">System</span>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $actionClass = 'bg-secondary-subtle text-secondary';
                                            if (in_array($log->action, ['Thêm mới'])) {
                                                $actionClass = 'bg-success-subtle text-success border border-success-subtle';
                                            } elseif (in_array($log->action, ['Cập nhật', 'Cập nhật liên hệ', 'Cập nhật đơn hàng', 'Cập nhật quyền'])) {
                                                $actionClass = 'bg-primary-subtle text-primary border border-primary-subtle';
                                            } elseif (in_array($log->action, ['Xóa', 'Xóa đánh giá', 'Xóa bình luận', 'Xóa liên hệ'])) {
                                                $actionClass = 'bg-danger-subtle text-danger border border-danger-subtle';
                                            } elseif (in_array($log->action, ['Duyệt', 'Duyệt đánh giá', 'Duyệt bình luận', 'Mở khóa'])) {
                                                $actionClass = 'bg-info-subtle text-info border border-info-subtle';
                                            } elseif (in_array($log->action, ['Đặt hàng', 'Thanh toán đơn hàng', 'Xác nhận thanh toán QR'])) {
                                                $actionClass = 'bg-emerald text-white';
                                            } elseif (in_array($log->action, ['Hủy đơn hàng', 'Hủy', 'Khóa'])) {
                                                $actionClass = 'bg-warning text-dark border border-warning-subtle';
                                            }
                                        @endphp
                                        <span class="badge {{ $actionClass }} rounded-pill px-2.5 py-1.5 fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.1px;">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-secondary small fw-medium">{{ $log->target_type ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <p class="mb-0 text-dark small fw-medium" style="max-width: 450px; line-height: 1.5;">
                                            {{ $log->description }}
                                        </p>
                                        @if($log->target_id)
                                            <small class="text-muted font-monospace" style="font-size: 0.7rem;">ID Tác động: #{{ $log->target_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="small font-monospace text-dark"><i class="bi bi-laptop me-1 text-muted"></i>{{ $log->ip_address ?? '127.0.0.1' }}</span>
                                            <span class="text-muted text-truncate" style="max-width: 150px; font-size: 0.7rem;" title="{{ $log->user_agent }}">
                                                {{ $log->user_agent }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="ps-3 pe-4">
                                        <div class="d-flex flex-column text-end">
                                            <span class="small text-dark fw-semibold" style="font-size: 0.8rem;">{{ $log->created_at->format('H:i d/m/Y') }}</span>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-center text-muted">
                                        <i class="bi bi-journal-x fs-1 d-block mb-3 text-secondary"></i>
                                        Chưa có nhật ký hoạt động nào được ghi nhận.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($logs->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-center">
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .bg-emerald {
        background-color: #10b981 !important;
    }
</style>
@endsection
