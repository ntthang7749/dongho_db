@extends('layouts.admin')
@section('title', 'Quản Lý Đánh Giá')
@section('page-title', 'Quản Lý Đánh Giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reviews.index') }}"
           class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-dark' }}">Tất cả</a>
        <a href="{{ route('admin.reviews.index', ['status'=>'pending']) }}"
           class="btn btn-sm {{ request('status')==='pending' ? 'btn-warning' : 'btn-outline-warning' }}">Chờ duyệt</a>
        <a href="{{ route('admin.reviews.index', ['status'=>'approved']) }}"
           class="btn btn-sm {{ request('status')==='approved' ? 'btn-success' : 'btn-outline-success' }}">Đã duyệt</a>
    </div>
    <div>
        <button type="button" class="btn btn-warning btn-sm fw-bold text-dark px-3 shadow-sm"
                id="btnBulkAiCheck" onclick="runBulkAiCheck()">
            <span class="spinner-border spinner-border-sm me-2 d-none" id="bulkAiSpinner" role="status" aria-hidden="true"></span>
            <i class="bi bi-stars me-1" id="bulkAiIcon"></i> Quét AI Tất Cả Chờ Duyệt
        </button>
    </div>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Khách hàng</th>
                    <th>Sản phẩm</th>
                    <th class="text-center">Rating</th>
                    <th>Nội dung</th>
                    <th class="text-center">Kiểm Duyệt AI</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr id="review-row-{{ $review->id }}" class="{{ $review->is_spam ? 'table-danger' : '' }}">
                    <td>
                        <p class="mb-0 fw-semibold small">{{ $review->user->name ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <small class="fw-semibold">{{ Str::limit($review->product->name ?? 'N/A', 40) }}</small>
                    </td>
                    <td class="text-center">
                        <div class="text-nowrap">
                            @for($i=1; $i<=5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"
                                   style="color:#ffc107; font-size:12px;"></i>
                            @endfor
                        </div>
                    </td>
                    <td style="max-width:250px;">
                        <small class="text-muted">{{ Str::limit($review->comment, 80) }}</small>
                    </td>
                    <td class="text-center" id="ai-moderation-cell-{{ $review->id }}">
                        <div class="d-flex flex-column align-items-center gap-1">
                            @if($review->ai_reason)
                                @if($review->is_spam)
                                    <span class="badge bg-danger cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $review->ai_reason }}">
                                        ⚠ Vi Phạm
                                    </span>
                                @else
                                    <span class="badge bg-success cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $review->ai_reason }}">
                                        ✓ Hợp Lệ
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Chưa quét AI</span>
                            @endif

                            @if($review->sentiment)
                                @php
                                    $sentimentColors = [
                                        'positive' => 'bg-success-subtle text-success border border-success-subtle',
                                        'neutral'  => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                        'negative' => 'bg-danger-subtle text-danger border border-danger-subtle'
                                    ];
                                    $sentimentLabels = [
                                        'positive' => '😊 Tích cực',
                                        'neutral'  => '😐 Trung lập',
                                        'negative' => '😠 Tiêu cực'
                                    ];
                                @endphp
                                <span class="badge {{ $sentimentColors[$review->sentiment] ?? 'bg-secondary' }}" style="font-size: 0.72rem; padding: 2px 6px;">
                                    {{ $sentimentLabels[$review->sentiment] ?? $review->sentiment }} ({{ number_format($review->sentiment_score, 1) }})
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="text-center" id="status-badge-cell-{{ $review->id }}">
                        <span class="badge
                            {{ $review->status === 'approved' ? 'bg-success'
                                : ($review->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                            {{ ['pending'=>'Chờ duyệt','approved'=>'Đã duyệt','rejected'=>'Từ chối'][$review->status] }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <!-- Nút quét AI nhanh -->
                            <button type="button" class="btn btn-sm btn-outline-warning btn-ai-check"
                                    id="btn-ai-check-{{ $review->id }}"
                                    title="Quét kiểm duyệt bằng AI"
                                    onclick="runSingleAiCheck({{ $review->id }})">
                                <i class="bi bi-stars" id="icon-ai-{{ $review->id }}"></i>
                                <span class="spinner-border spinner-border-sm d-none" id="spinner-ai-{{ $review->id }}" role="status" aria-hidden="true"></span>
                            </button>

                            <form method="POST"
                                  action="{{ route('admin.reviews.approve', $review->id) }}">
                                @csrf
                                <button class="btn btn-sm {{ $review->status === 'approved' ? 'btn-warning' : 'btn-success' }}"
                                        title="{{ $review->status === 'approved' ? 'Bỏ duyệt' : 'Duyệt' }}">
                                    <i class="bi bi-{{ $review->status === 'approved' ? 'x' : 'check' }}"></i>
                                </button>
                            </form>
                            <form method="POST"
                                  action="{{ route('admin.reviews.destroy', $review->id) }}"
                                  id="del-review-{{ $review->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-review-{{ $review->id }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Không có đánh giá nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $reviews->links() }}</div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    initTooltips();
});

function initTooltips() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => {
        const oldTooltip = bootstrap.Tooltip.getInstance(el);
        if (oldTooltip) {
            oldTooltip.dispose();
        }
        new bootstrap.Tooltip(el);
    });
}

async function runSingleAiCheck(id) {
    const btn = document.getElementById(`btn-ai-check-${id}`);
    const icon = document.getElementById(`icon-ai-${id}`);
    const spinner = document.getElementById(`spinner-ai-${id}`);
    const row = document.getElementById(`review-row-${id}`);
    const aiCell = document.getElementById(`ai-moderation-cell-${id}`);
    const statusCell = document.getElementById(`status-badge-cell-${id}`);

    btn.disabled = true;
    icon.classList.add('d-none');
    spinner.classList.remove('d-none');

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        const res = await fetch(`/admin/reviews/${id}/ai-check`, {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            let badgeClass = data.is_spam ? 'bg-danger' : 'bg-success';
            let badgeText = data.is_spam ? '⚠ Vi Phạm' : '✓ Hợp Lệ';

            let sentimentColors = {
                'positive': 'bg-success-subtle text-success border border-success-subtle',
                'neutral': 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                'negative': 'bg-danger-subtle text-danger border border-danger-subtle'
            };
            let sentimentLabels = {
                'positive': '😊 Tích cực',
                'neutral': '😐 Trung lập',
                'negative': '😠 Tiêu cực'
            };
            
            let sentimentHtml = '';
            if (data.sentiment) {
                let sColor = sentimentColors[data.sentiment] || 'bg-secondary';
                let sLabel = sentimentLabels[data.sentiment] || data.sentiment;
                let sScore = parseFloat(data.sentiment_score).toFixed(1);
                sentimentHtml = `
                    <span class="badge ${sColor}" style="font-size: 0.72rem; padding: 2px 6px; margin-top: 4px;">
                        ${sLabel} (${sScore})
                    </span>
                `;
            }

            aiCell.innerHTML = `
                <div class="d-flex flex-column align-items-center gap-1">
                    <span class="badge ${badgeClass} cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="${data.reason}">
                        ${badgeText}
                    </span>
                    ${sentimentHtml}
                </div>
            `;

            if (data.is_spam) {
                row.classList.add('table-danger');
            } else {
                row.classList.remove('table-danger');
            }

            let statusBadgeClass = data.status === 'approved' ? 'bg-success' : (data.status === 'pending' ? 'bg-warning text-dark' : 'bg-danger');
            statusCell.innerHTML = `
                <span class="badge ${statusBadgeClass}">
                    ${data.status_text}
                </span>
            `;

            initTooltips();
            showAiToast(data.is_spam, data.reason);
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    } catch (e) {
        alert('Lỗi kết nối kiểm duyệt AI!');
    } finally {
        btn.disabled = false;
        spinner.classList.add('d-none');
        icon.classList.remove('d-none');
    }
}

async function runBulkAiCheck() {
    const btn = document.getElementById('btnBulkAiCheck');
    const icon = document.getElementById('bulkAiIcon');
    const spinner = document.getElementById('bulkAiSpinner');

    // Lấy danh sách ID của các đánh giá hiển thị trên trang hiện tại
    const ids = Array.from(document.querySelectorAll('tr[id^="review-row-"]'))
                     .map(tr => tr.id.replace('review-row-', ''));

    if (ids.length === 0) {
        alert('Không có đánh giá nào trên trang này để quét.');
        return;
    }

    if (!confirm('Hệ thống sẽ tự động quét AI các đánh giá ở trang này (chưa duyệt hoặc chưa phân tích cảm xúc) để kiểm duyệt và cập nhật thông tin cảm xúc. Bạn có muốn tiếp tục?')) {
        return;
    }

    btn.disabled = true;
    icon.classList.add('d-none');
    spinner.classList.remove('d-none');

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    ids.forEach(id => formData.append('ids[]', id));

    try {
        const res = await fetch('/admin/reviews/bulk-ai-check', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            if (data.scanned_count > 0) {
                data.results.forEach(res => {
                    const id = res.id;
                    const row = document.getElementById(`review-row-${id}`);
                    const aiCell = document.getElementById(`ai-moderation-cell-${id}`);
                    const statusCell = document.getElementById(`status-badge-cell-${id}`);

                    if (row && aiCell && statusCell) {
                        let badgeClass = res.is_spam ? 'bg-danger' : 'bg-success';
                        let badgeText = res.is_spam ? '⚠ Vi Phạm' : '✓ Hợp Lệ';

                        let sentimentColors = {
                            'positive': 'bg-success-subtle text-success border border-success-subtle',
                            'neutral': 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                            'negative': 'bg-danger-subtle text-danger border border-danger-subtle'
                        };
                        let sentimentLabels = {
                            'positive': '😊 Tích cực',
                            'neutral': '😐 Trung lập',
                            'negative': '😠 Tiêu cực'
                        };
                        
                        let sentimentHtml = '';
                        if (res.sentiment) {
                            let sColor = sentimentColors[res.sentiment] || 'bg-secondary';
                            let sLabel = sentimentLabels[res.sentiment] || res.sentiment;
                            let sScore = parseFloat(res.sentiment_score).toFixed(1);
                            sentimentHtml = `
                                <span class="badge ${sColor}" style="font-size: 0.72rem; padding: 2px 6px; margin-top: 4px;">
                                    ${sLabel} (${sScore})
                                </span>
                            `;
                        }

                        aiCell.innerHTML = `
                            <div class="d-flex flex-column align-items-center gap-1">
                                <span class="badge ${badgeClass} cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="${res.reason}">
                                    ${badgeText}
                                </span>
                                ${sentimentHtml}
                            </div>
                        `;

                        if (res.is_spam) {
                            row.classList.add('table-danger');
                        } else {
                            row.classList.remove('table-danger');
                        }

                        let statusBadgeClass = res.status === 'approved' ? 'bg-success' : (res.status === 'pending' ? 'bg-warning text-dark' : 'bg-danger');
                        statusCell.innerHTML = `
                            <span class="badge ${statusBadgeClass}">
                                ${res.status_text}
                            </span>
                        `;
                    }
                });

                initTooltips();
                alert(`✨ Đã hoàn thành kiểm duyệt AI hàng loạt!\nĐã quét: ${data.scanned_count} đánh giá.`);
                window.location.reload();
            } else {
                alert('Không có đánh giá nào ở trạng thái "Chờ duyệt" để kiểm duyệt.');
            }
        } else {
            alert('Có lỗi xảy ra: ' + data.message);
        }
    } catch (e) {
        alert('Lỗi kết nối kiểm duyệt AI hàng loạt!');
    } finally {
        btn.disabled = false;
        spinner.classList.add('d-none');
        icon.classList.remove('d-none');
    }
}

function showAiToast(isViolation, reason) {
    const toastContainer = document.getElementById('ai-toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white border-0 mb-2 shadow`;
    toast.style.background = isViolation ? 'linear-gradient(135deg, #721c24 0%, #3e1116 100%)' : 'linear-gradient(135deg, #155724 0%, #0c3614 100%)';
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body p-3">
                <strong class="text-warning"><i class="bi bi-stars me-1"></i>Trợ Lý Kiểm Duyệt AI:</strong><br>
                ${reason}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, { delay: 8000 });
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'ai-toast-container';
    container.style.position = 'fixed';
    container.style.bottom = '20px';
    container.style.right = '20px';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>
@endpush
@endsection