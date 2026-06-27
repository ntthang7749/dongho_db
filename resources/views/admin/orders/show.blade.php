@extends('layouts.admin')
@section('title', 'Chi Tiết Đơn Hàng')
@section('page-title', 'Chi Tiết Đơn Hàng #{{ $order->order_code }}')

@section('content')
@php
$statusColors = ['pending'=>'warning','confirmed'=>'info','shipping'=>'primary','delivered'=>'success','cancelled'=>'danger'];
$statusLabels = ['pending'=>'Chờ xác nhận','confirmed'=>'Đã xác nhận','shipping'=>'Đang giao','delivered'=>'Đã giao','cancelled'=>'Đã huỷ'];
@endphp

<div class="row g-4">
    <div class="col-md-8">
        {{-- Sản phẩm --}}
        <div class="card table-card mb-4">
            <div class="card-header">
                <i class="bi bi-bag me-2"></i>Sản Phẩm Đặt Hàng
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ img_url($item->product_image) }}"
                                         width="50" height="50"
                                         style="object-fit:cover; border-radius:6px;">
                                    <span class="fw-semibold small">{{ $item->product_name }}</span>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->price) }}đ</td>
                            <td class="text-end fw-bold">{{ number_format($item->subtotal) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end">Tạm tính:</td>
                            <td class="text-end">{{ number_format($order->subtotal) }}đ</td>
                        </tr>
                        @if($order->discount > 0)
                        <tr>
                            <td colspan="3" class="text-end text-success">
                                Giảm giá ({{ $order->coupon->code ?? '' }}):
                            </td>
                            <td class="text-end text-success">-{{ number_format($order->discount) }}đ</td>
                        </tr>
                        @endif
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end fs-6">Tổng cộng:</td>
                            <td class="text-end text-danger fs-6">{{ number_format($order->total) }}đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Thông tin đơn --}}
        <div class="card table-card mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Thông Tin Đơn</div>
            <div class="card-body">
                <p class="mb-2"><span class="text-muted">Mã đơn:</span>
                    <strong>{{ $order->order_code }}</strong></p>
                <p class="mb-2"><span class="text-muted">Ngày đặt:</span>
                    {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-2"><span class="text-muted">Thanh toán:</span>
                    @if($order->payment_method === 'cod')
                        COD
                    @elseif($order->payment_method === 'vnpay')
                        VNPay
                    @elseif($order->payment_method === 'qr')
                        Chuyển khoản QR
                    @else
                        {{ strtoupper($order->payment_method) }}
                    @endif
                </p>
                <p class="mb-3"><span class="text-muted">TT Thanh toán:</span>
                    {{-- Trong card thông tin đơn --}}
                    @if($order->vnpay_transaction_id)
                    <div class="row mb-2">
                        <div class="col-5 text-muted small">Mã GD VNPay</div>
                        <div class="col-7">
                            <code class="small">{{ $order->vnpay_transaction_id }}</code>
                        </div>
                    </div>
                    @endif
                    {{-- Thêm vào card thông tin đơn, sau form cập nhật trạng thái --}}
                    @if($order->payment_method === 'qr' && $order->payment_status !== 'paid')
                    <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded border border-warning">
                        <p class="small fw-bold text-warning mb-2">
                            <i class="bi bi-qr-code me-1"></i>
                            Đơn hàng đang chờ xác nhận QR
                        </p>
                        <p class="small text-muted mb-2">
                            Kiểm tra tài khoản ngân hàng, nếu đã nhận tiền hãy xác nhận:
                        </p>
                        <form method="POST"
                            action="{{ route('admin.orders.confirm-qr', $order->id) }}">
                            @csrf
                            <button type="submit"
                                    class="btn btn-success btn-sm w-100"
                                    onclick="return confirm('Xác nhận đã nhận được tiền chuyển khoản?')">
                                <i class="bi bi-check-circle me-1"></i>
                                Xác Nhận Đã Nhận Tiền
                            </button>
                        </form>
                    </div>
                    @endif

                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                    </span>
                </p>
                <hr>
                <p class="fw-semibold mb-2">Cập nhật trạng thái:</p>
                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                    @csrf
                    <select name="status" class="form-select mb-2">
                        @foreach($statusLabels as $key => $label)
                            <option value="{{ $key }}"
                                    {{ $order->status === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-dark w-100">
                        <i class="bi bi-check2 me-1"></i>Cập Nhật
                    </button>
                </form>
            </div>
        </div>

        {{-- Thông tin giao hàng --}}
        <div class="card table-card">
            <div class="card-header"><i class="bi bi-geo-alt me-2"></i>Thông Tin Giao Hàng</div>
            <div class="card-body">
                <p class="mb-2"><i class="bi bi-person me-2"></i>{{ $order->receiver_name }}</p>
                <p class="mb-2"><i class="bi bi-telephone me-2"></i>{{ $order->receiver_phone }}</p>
                <p class="mb-2"><i class="bi bi-geo me-2"></i>{{ $order->receiver_address }}</p>
                @if($order->note)
                    <hr>
                    <p class="mb-0 small text-muted">
                        <i class="bi bi-chat-left me-1"></i>{{ $order->note }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
    <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-success">
        <i class="bi bi-file-earmark-pdf me-1"></i> Xuất Hóa Đơn PDF
    </a>
</div>
@endsection