@extends('layouts.admin')
@section('title', 'Quản Lý Đơn Hàng')
@section('page-title', 'Quản Lý Đơn Hàng')

@section('content')
@php
$statusColors = ['pending'=>'warning','confirmed'=>'info','shipping'=>'primary','delivered'=>'success','cancelled'=>'danger'];
$statusLabels = ['pending'=>'Chờ XN','confirmed'=>'Đã XN','shipping'=>'Đang giao','delivered'=>'Đã giao','cancelled'=>'Đã huỷ'];
@endphp

{{-- TABS TRẠNG THÁI --}}
<div class="d-flex gap-2 flex-wrap mb-4">
    <a href="{{ route('admin.orders.index') }}"
       class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-outline-dark' }}">
        Tất cả <span class="badge bg-secondary ms-1">{{ $statusCounts->sum() }}</span>
    </a>
    @foreach($statusLabels as $key => $label)
    <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
       class="btn btn-sm {{ request('status') === $key ? 'btn-'.$statusColors[$key] : 'btn-outline-'.$statusColors[$key] }}">
        {{ $label }}
        <span class="badge bg-dark ms-1">{{ $statusCounts[$key] ?? 0 }}</span>
    </a>
    @endforeach
</div>

{{-- TÌM KIẾM --}}
<div class="card table-card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" class="form-control"
                   placeholder="Tìm theo mã đơn, tên khách..."
                   value="{{ request('search') }}">
            <button class="btn btn-dark px-4"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

{{-- BẢNG --}}
<div class="card table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><code class="text-dark fw-bold">{{ $order->order_code }}</code></td>
                    <td>
                        <p class="mb-0 fw-semibold small">{{ $order->user->name ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $order->receiver_phone }}</small>
                    </td>
                    <td class="text-danger fw-bold">{{ number_format($order->total) }}đ</td>
                    <td>
                        {{-- Trong cột "Thanh toán", thêm badge QR --}}
                        @if($order->payment_method === 'qr')
                        <span class="badge bg-primary">
                            <i class="bi bi-qr-code me-1"></i>QR
                            @if($order->payment_status !== 'paid')
                                — <span class="text-warning">Chờ xác nhận</span>
                            @else
                                — Đã TT
                            @endif
                        </span>
                        @endif
                        
                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $order->payment_method === 'cod' ? 'COD' : 'VNPay' }}
                            — {{ $order->payment_status === 'paid' ? 'Đã TT' : 'Chưa TT' }}
                        </span>
                    </td>
                    <td>
                        <form method="POST"
                              action="{{ route('admin.orders.status', $order->id) }}"
                              class="d-inline">
                            @csrf
                            <select name="status" class="form-select form-select-sm"
                                    style="width:140px;"
                                    onchange="this.form.submit()">
                                @foreach($statusLabels as $key => $label)
                                    <option value="{{ $key }}"
                                            {{ $order->status === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </small>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="btn btn-sm btn-outline-dark" title="Xem Chi Tiết">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank"
                           class="btn btn-sm btn-outline-success ms-1" title="Xuất Hóa Đơn PDF">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Không có đơn hàng nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $orders->links() }}</div>
</div>
@endsection