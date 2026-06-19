@extends('layouts.admin')
@section('title', 'Danh Mục')
@section('page-title', 'Quản Lý Danh Mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted small">Tổng: <strong>{{ $categories->count() }}</strong> danh mục</span>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i> Thêm Danh Mục
    </a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Danh mục</th>
                    <th>Danh mục cha</th>
                    <th class="text-center">Sản phẩm</th>
                    <th class="text-center">Thứ tự</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                {{-- Danh mục cha --}}
                <tr class="table-light">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($cat->image)
                                <img src="{{ asset('storage/'.$cat->image) }}"
                                     width="36" height="36"
                                     style="object-fit:cover; border-radius:6px;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center
                                            justify-content-center text-white"
                                     style="width:36px; height:36px;">
                                    <i class="bi bi-grid small"></i>
                                </div>
                            @endif
                            <span class="fw-bold">{{ $cat->name }}</span>
                        </div>
                    </td>
                    <td><span class="text-muted small">—</span></td>
                    <td class="text-center">
                        <span class="badge bg-info text-dark">{{ $cat->products_count }}</span>
                    </td>
                    <td class="text-center">{{ $cat->sort_order }}</td>
                    <td class="text-center">
                        <span class="badge {{ $cat->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $cat->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.categories.edit', $cat->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.categories.destroy', $cat->id) }}"
                                  id="del-cat-{{ $cat->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-cat-{{ $cat->id }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Danh mục con --}}
                @foreach($cat->children as $child)
                <tr>
                    <td class="ps-5">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-arrow-return-right text-muted"></i>
                            <span>{{ $child->name }}</span>
                        </div>
                    </td>
                    <td><small class="text-muted">{{ $cat->name }}</small></td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark">{{ $child->products_count ?? 0 }}</span>
                    </td>
                    <td class="text-center">{{ $child->sort_order }}</td>
                    <td class="text-center">
                        <span class="badge {{ $child->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $child->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.categories.edit', $child->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.categories.destroy', $child->id) }}"
                                  id="del-cat-{{ $child->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-cat-{{ $child->id }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach

                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Chưa có danh mục nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection