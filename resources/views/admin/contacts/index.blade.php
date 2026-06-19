@extends('layouts.admin')
@section('title', 'Quản Lý Liên Hệ')
@section('page-title', 'Quản Lý Liên Hệ')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    @php
    $statCfg = [
        'all'     => ['label'=>'Tổng liên hệ', 'icon'=>'bi-envelope',         'color'=>'#6366f1'],
        'new'     => ['label'=>'Chưa đọc',     'icon'=>'bi-envelope-exclamation','color'=>'#e94560'],
        'read'    => ['label'=>'Đã đọc',        'icon'=>'bi-envelope-open',    'color'=>'#f59e0b'],
        'replied' => ['label'=>'Đã phản hồi',   'icon'=>'bi-reply-all',        'color'=>'#22c55e'],
        'closed'  => ['label'=>'Đã đóng',       'icon'=>'bi-x-circle',         'color'=>'#94a3b8'],
    ];
    @endphp
    @foreach($statCfg as $key => $cfg)
    <div class="col-6 col-md">
        <a href="{{ $key==='all' ? route('admin.contacts.index') : route('admin.contacts.index',['status'=>$key]) }}"
           class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-left:3px solid {{ $cfg['color'] }}!important;">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div style="width:40px;height:40px;border-radius:10px;background:{{ $cfg['color'] }}18;display:flex;align-items:center;justify-content:center;">
                        <i class="bi {{ $cfg['icon'] }}" style="color:{{ $cfg['color'] }};font-size:1.1rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:1.4rem;font-weight:800;color:#1a1a2e;line-height:1;">{{ $counts[$key] }}</div>
                        <div style="font-size:0.72rem;color:#888;font-weight:500;">{{ $cfg['label'] }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm {{ !$status ? 'btn-dark' : 'btn-outline-secondary' }}">Tất cả</a>
        <a href="{{ route('admin.contacts.index',['status'=>'new']) }}" class="btn btn-sm {{ $status==='new' ? 'btn-danger' : 'btn-outline-danger' }}">
            Chưa đọc @if($counts['new']>0)<span class="badge bg-white text-danger ms-1">{{ $counts['new'] }}</span>@endif
        </a>
        <a href="{{ route('admin.contacts.index',['status'=>'read']) }}" class="btn btn-sm {{ $status==='read' ? 'btn-warning' : 'btn-outline-warning' }}">Đã đọc</a>
        <a href="{{ route('admin.contacts.index',['status'=>'replied']) }}" class="btn btn-sm {{ $status==='replied' ? 'btn-success' : 'btn-outline-success' }}">Đã phản hồi</a>
        <a href="{{ route('admin.contacts.index',['status'=>'closed']) }}" class="btn btn-sm {{ $status==='closed' ? 'btn-secondary' : 'btn-outline-secondary' }}">Đã đóng</a>
    </div>
    <div class="ms-auto d-flex gap-2">
        <select class="form-select form-select-sm" style="width:160px;"
                onchange="location.href='{{ route('admin.contacts.index') }}?type='+this.value+'&status={{ $status ?? '' }}'">
            <option value="">-- Tất cả loại --</option>
            <option value="feedback"  {{ $type==='feedback'  ? 'selected':'' }}>Phản hồi / Góp ý</option>
            <option value="report"    {{ $type==='report'    ? 'selected':'' }}>Báo cáo vấn đề</option>
            <option value="question"  {{ $type==='question'  ? 'selected':'' }}>Câu hỏi</option>
            <option value="other"     {{ $type==='other'     ? 'selected':'' }}>Khác</option>
        </select>
    </div>
</div>

{{-- Table --}}
<div class="card table-card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Người gửi</th>
                    <th>Tiêu đề</th>
                    <th class="text-center">Loại</th>
                    <th class="text-center">Trạng thái</th>
                    <th>Thời gian</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $c)
                <tr class="{{ $c->status==='new' ? 'table-danger bg-opacity-25' : '' }}" style="font-size:0.875rem;">
                    <td class="text-muted">{{ $c->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $c->name }}</div>
                        <small class="text-muted">{{ $c->email }}</small>
                        @if($c->phone)<br><small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $c->phone }}</small>@endif
                    </td>
                    <td style="max-width:260px;">
                        <div class="fw-500">{{ Str::limit($c->subject, 55) }}</div>
                        <small class="text-muted">{{ Str::limit($c->message, 70) }}</small>
                    </td>
                    <td class="text-center">
                        @php $typeIcons=['feedback'=>'💬','report'=>'🚨','question'=>'❓','other'=>'📋']; @endphp
                        <span title="{{ $c->typeLabel() }}">{{ $typeIcons[$c->type] ?? '📋' }}</span>
                        <div style="font-size:0.68rem;color:#888;">{{ $c->typeLabel() }}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $c->statusColor() }}">{{ $c->statusLabel() }}</span>
                    </td>
                    <td>
                        <small class="text-muted">{{ $c->created_at->format('d/m/Y') }}<br>{{ $c->created_at->format('H:i') }}</small>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.contacts.show', $c->id) }}" class="btn btn-sm btn-outline-primary" title="Xem & Phản hồi">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.contacts.destroy', $c->id) }}" id="del-c-{{ $c->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger" title="Xoá"
                                    onclick="if(confirm('Xoá liên hệ này?')) document.getElementById('del-c-{{ $c->id }}').submit()">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:10px;"></i>
                        Chưa có liên hệ nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $contacts->links() }}</div>
</div>
@endsection
