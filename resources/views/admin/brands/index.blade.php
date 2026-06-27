@extends('layouts.admin')
@section('title', 'Thương Hiệu')
@section('page-title', 'Quản Lý Thương Hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted small">Tổng: <strong>{{ $brands->count() }}</strong> thương hiệu</span>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-dark">
        <i class="bi bi-plus-lg me-1"></i> Thêm Thương Hiệu
    </a>
</div>

<div class="row g-3">
    @forelse($brands as $brand)
    <div class="col-6 col-md-3">
        <div class="card table-card text-center p-3">
            @if($brand->logo)
                <img src="{{ img_url($brand->logo) }}"
                     style="height:60px; object-fit:contain;" class="mx-auto mb-2">
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center
                            mx-auto mb-2" style="width:60px; height:60px;">
                    <i class="bi bi-tag fs-3 text-muted"></i>
                </div>
            @endif
            <h6 class="fw-bold mb-1">{{ $brand->name }}</h6>
            <small class="text-muted d-block mb-3">{{ $brand->products_count }} sản phẩm</small>
            <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-secondary' }} mb-3">
                {{ $brand->is_active ? 'Hoạt động' : 'Ẩn' }}
            </span>
            <div class="d-flex gap-1 justify-content-center">
                <a href="{{ route('admin.brands.edit', $brand->id) }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil"></i>
                </a>
                <form method="POST"
                      action="{{ route('admin.brands.destroy', $brand->id) }}"
                      id="del-brand-{{ $brand->id }}">
                    @csrf @method('DELETE')
                </form>
                <button class="btn btn-sm btn-outline-danger"
                        onclick="confirmDelete('del-brand-{{ $brand->id }}')">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-tag fs-1"></i>
        <p class="mt-2">Chưa có thương hiệu nào</p>
    </div>
    @endforelse
</div>
@endsection