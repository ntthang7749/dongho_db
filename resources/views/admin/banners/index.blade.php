@extends('layouts.admin')
@section('title', 'Quản Lý Banner')
@section('page-title', 'Quản Lý Banner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted small">Tổng: <strong>{{ $banners->count() }}</strong> banner</span>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i> Thêm Banner
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Thứ tự</th>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Link</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td class="text-center fw-bold">{{ $banner->sort_order }}</td>
                    <td>
                        <img src="{{ img_url($banner->image) }}"
                             style="height:60px; width:120px; object-fit:cover; border-radius:6px;">
                    </td>
                    <td class="fw-semibold">{{ $banner->title }}</td>
                    <td>
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank"
                               class="text-truncate d-block small text-muted"
                               style="max-width:200px;">
                                {{ $banner->link }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.banners.edit', $banner->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.banners.destroy', $banner->id) }}"
                                  id="del-banner-{{ $banner->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-banner-{{ $banner->id }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Chưa có banner nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection