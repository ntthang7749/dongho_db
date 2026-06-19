@extends('layouts.admin')
@section('title', 'Quản Lý Sản Phẩm')
@section('page-title', 'Quản Lý Sản Phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="text-muted small">Tổng: <strong>{{ $products->total() }}</strong> sản phẩm</span>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i> Thêm Sản Phẩm
    </a>
</div>

{{-- BỘ LỌC --}}
<div class="card table-card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                       placeholder="Tìm theo tên, SKU..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="filter" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="low_stock" {{ request('filter') === 'low_stock' ? 'selected' : '' }}>
                        Sắp hết hàng
                    </option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- BẢNG --}}
<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th class="text-center">Tồn kho</th>
                    <th class="text-center">Nổi bật</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $i => $p)
                <tr>
                    <td class="text-muted small">{{ $products->firstItem() + $i }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $p->thumbnail
                                ? asset('storage/' . $p->thumbnail)
                                : asset('images/no-image.png') }}"
                                 width="50" height="50"
                                 style="object-fit:cover; border-radius:8px;">
                            <div>
                                <p class="mb-0 fw-semibold small">{{ $p->name }}</p>
                                <small class="text-muted">{{ $p->brand->name ?? '' }}</small>
                            </div>
                        </div>
                    </td>
                    <td><small>{{ $p->category->name ?? '—' }}</small></td>
                    <td>
                        @if($p->sale_price)
                            <span class="text-danger fw-bold">
                                {{ number_format($p->sale_price) }}đ
                            </span><br>
                            <small class="text-muted text-decoration-line-through">
                                {{ number_format($p->price) }}đ
                            </small>
                        @else
                            <span class="fw-bold">{{ number_format($p->price) }}đ</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge
                            {{ $p->stock == 0 ? 'bg-danger'
                                : ($p->stock <= 5 ? 'bg-warning text-dark' : 'bg-success') }}">
                            {{ $p->stock }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $p->is_featured ? 'bg-warning text-dark' : 'bg-light text-muted' }}">
                            {{ $p->is_featured ? '⭐ Có' : 'Không' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $p->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $p->is_active ? 'Đang bán' : 'Ẩn' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('admin.products.invoice', $p->id) }}"
                               class="btn btn-sm btn-outline-success" target="_blank"
                               title="Xuất Hóa Đơn PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $p->id) }}"
                               class="btn btn-sm btn-outline-primary"
                               title="Sửa sản phẩm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST"
                                  action="{{ route('admin.products.destroy', $p->id) }}"
                                  id="del-product-{{ $p->id }}">
                                @csrf @method('DELETE')
                            </form>
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('del-product-{{ $p->id }}')"
                                    title="Xoá sản phẩm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        Không có sản phẩm nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">
        {{ $products->links() }}
    </div>
</div>
@endsection