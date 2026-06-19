@extends('layouts.admin')
@section('title', 'Tin Tức')
@section('page-title', 'Quản Lý Tin Tức')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <span class="text-muted small">Tổng: <strong>{{ $news->total() }}</strong> bài viết</span>
    <a href="{{ route('admin.news.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i> Viết Bài Mới
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Bài viết</th>
                    <th>Tác giả</th>
                    <th class="text-center">Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($item->thumbnail)
                                <img src="{{ asset('storage/'.$item->thumbnail) }}"
                                     width="70" height="50"
                                     style="object-fit:cover; border-radius:6px;">
                            @endif
                            <div>
                                <p class="mb-0 fw-semibold">{{ Str::limit($item->title, 60) }}</p>
                                <small class="text-muted">{{ Str::limit($item->summary, 80) }}</small>
                            </div>
                        </div>
                    </td>
                    <td><small>{{ $item->author->name ?? 'N/A' }}</small></td>
                    <td class="text-center">
                        <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $item->is_active ? 'Đã đăng' : 'Nháp' }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $item->created_at->format('d/m/Y') }}</small></td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('news.show', $item->slug) }}" target="_blank"
                               class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.news.edit', $item->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.news.destroy', $item->id) }}"
                                  id="del-news-{{ $item->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-news-{{ $item->id }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có bài viết nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $news->links() }}</div>
</div>
@endsection