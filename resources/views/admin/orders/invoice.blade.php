<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa Đơn Đơn Hàng #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a1a2e;
            padding-bottom: 10px;
        }
        .header table {
            width: 100%;
        }
        .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: #c9a84c; /* Gold color matching premium watch branding */
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .shop-info {
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .title {
            text-align: center;
            text-transform: uppercase;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 3px;
            color: #1a1a2e;
        }
        .invoice-code {
            text-align: center;
            font-style: italic;
            font-size: 11px;
            margin-bottom: 20px;
            color: #555;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a2e;
            border-left: 3px solid #c9a84c;
            padding-left: 8px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-block-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-block-table td {
            width: 50%;
            vertical-align: top;
            padding: 5px;
        }
        .info-card {
            border: 1px solid #e9ecef;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            min-height: 80px;
        }
        .info-card table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-card table td {
            width: auto;
            padding: 3px 0;
            border: none;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 110px;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th {
            background-color: #1a1a2e;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 10px;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #1a1a2e;
        }
        .main-table td {
            padding: 8px 10px;
            border: 1px solid #e9ecef;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .summary-table td {
            padding: 4px 10px;
            border: none;
        }
        .summary-label {
            text-align: right;
            font-weight: bold;
            color: #555;
        }
        .summary-value {
            text-align: right;
            width: 120px;
        }
        .signature-section {
            width: 100%;
            margin-top: 40px;
        }
        .signature-box {
            width: 50%;
            float: left;
            text-align: center;
        }
        .signature-space {
            margin-top: 50px;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 160px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px dashed #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <span class="logo-text">ĐỒNG HỒ ONLINE</span><br>
                    <small style="color: #666; font-weight: bold;">HỆ THỐNG CỬA HÀNG ĐỒNG HỒ CHÍNH HÃNG PREMIUM</small>
                </td>
                <td class="shop-info">
                    <strong>Địa chỉ:</strong> 123 Đường Ba Tháng Hai, Quận 10, TP. HCM<br>
                    <strong>Hotline:</strong> 0987.654.321 | <strong>Email:</strong> support@donghoonline.com<br>
                    <strong>Website:</strong> www.donghoonline.com
                </td>
            </tr>
        </table>
    </div>

    <!-- Title -->
    <div class="title">HÓA ĐƠN BÁN HÀNG & THANH TOÁN</div>
    <div class="invoice-code">Mã hóa đơn: <strong>{{ $order->order_code }}</strong> | Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</div>

    <!-- Details Blocks -->
    <table class="info-block-table">
        <tr>
            <td>
                <div class="section-title">Thông tin giao nhận</div>
                <div class="info-card">
                    <table>
                        <tr>
                            <td class="info-label">Khách hàng:</td>
                            <td>{{ $order->receiver_name }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Số điện thoại:</td>
                            <td>{{ $order->receiver_phone }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Địa chỉ giao:</td>
                            <td>{{ $order->receiver_address }}</td>
                        </tr>
                        @if($order->note)
                        <tr>
                            <td class="info-label">Ghi chú khách:</td>
                            <td style="font-style: italic; color: #555;">"{{ $order->note }}"</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </td>
            <td>
                <div class="section-title">Trạng thái thanh toán</div>
                <div class="info-card">
                    <table>
                        <tr>
                            <td class="info-label">Thanh toán:</td>
                            <td>
                                @if($order->payment_method === 'cod')
                                    Thanh toán khi nhận hàng (COD)
                                @elseif($order->payment_method === 'vnpay')
                                    Cổng thanh toán VNPay
                                @elseif($order->payment_method === 'qr')
                                    Chuyển khoản QR Ngân hàng
                                @else
                                    {{ strtoupper($order->payment_method) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Trạng thái tiền:</td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge badge-success">Đã thanh toán</span>
                                @elseif($order->payment_status === 'failed')
                                    <span class="badge badge-danger">Thanh toán lỗi</span>
                                @else
                                    <span class="badge badge-warning">Chưa thanh toán</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Trạng thái đơn:</td>
                            <td>
                                @if($order->status === 'pending')
                                    <span class="badge badge-warning">Chờ xác nhận</span>
                                @elseif($order->status === 'confirmed')
                                    <span class="badge badge-info">Đã xác nhận</span>
                                @elseif($order->status === 'shipping')
                                    <span class="badge badge-info">Đang giao hàng</span>
                                @elseif($order->status === 'delivered')
                                    <span class="badge badge-success">Đã giao thành công</span>
                                @elseif($order->status === 'cancelled')
                                    <span class="badge badge-danger">Đã hủy đơn</span>
                                @else
                                    <span class="badge badge-info">{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @if($order->vnpay_transaction_id)
                        <tr>
                            <td class="info-label">Mã giao dịch:</td>
                            <td style="font-size: 9px; color: #666;">{{ $order->vnpay_transaction_id }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Main Order Items Table -->
    <div class="section-title">Danh sách sản phẩm mua</div>
    <table class="main-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">STT</th>
                <th width="12%" class="text-center">Hình ảnh</th>
                <th width="48%">Tên sản phẩm</th>
                <th width="10%" class="text-center">Số lượng</th>
                <th width="12%" class="text-right">Đơn giá</th>
                <th width="13%" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    @if(isset($items_images[$item->id]) && $items_images[$item->id])
                        <img class="product-img" src="{{ $items_images[$item->id] }}">
                    @else
                        <div style="font-size: 8px; color: #999; border: 1px solid #eee; padding: 10px 2px; border-radius: 4px;">No image</div>
                    @endif
                </td>
                <td>
                    <span class="fw-bold" style="font-size: 12px; color: #1a1a2e;">{{ $item->product_name }}</span><br>
                    @if($item->product && $item->product->sku)
                        <small style="color: #666;">Mã SKU: <strong>{{ $item->product->sku }}</strong></small>
                    @endif
                </td>
                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price) }}đ</td>
                <td class="text-right fw-bold" style="color: #1a1a2e;">{{ number_format($item->subtotal) }}đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Order Financial Calculation -->
    <table class="summary-table">
        <tr>
            <td colspan="4"></td>
            <td class="summary-label">Tạm tính:</td>
            <td class="summary-value">{{ number_format($order->subtotal) }}đ</td>
        </tr>
        @if($order->discount > 0)
        <tr>
            <td colspan="4"></td>
            <td class="summary-label" style="color: #c9a84c;">Giảm giá (Khuyến mãi):</td>
            <td class="summary-value" style="color: #c9a84c;">-{{ number_format($order->discount) }}đ</td>
        </tr>
        @endif
        <tr>
            <td colspan="4"></td>
            <td class="summary-label">Phí vận chuyển:</td>
            <td class="summary-value">Miễn phí</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="summary-label" style="font-size: 13px; color: #d9534f; border-top: 1px solid #ddd; padding-top: 6px;">Tổng thanh toán:</td>
            <td class="summary-value fw-bold" style="font-size: 14px; color: #d9534f; border-top: 1px solid #ddd; padding-top: 6px;">
                {{ number_format($order->total) }}đ
            </td>
        </tr>
    </table>

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <span><strong>Người mua hàng</strong></span><br>
            <small style="color: #666;">(Ký nhận và xác nhận hàng hóa)</small>
            <div class="signature-space" style="margin-top: 40px; font-weight: normal; color: #888;">
                (Đã đặt nhận hàng trực tuyến)
            </div>
        </div>
        <div class="signature-box">
            <span><strong>Đại diện Cửa hàng</strong></span><br>
            <small style="color: #666;">(Ký tên, đóng dấu và xác nhận tiền)</small>
            <div class="signature-space" style="margin-top: 40px; color: #c9a84c;">
                ĐỒNG HỒ ONLINE ONLINE
            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="footer-note">
        Cảm ơn quý khách đã mua sắm tại Đồng Hồ Online! Hân hạnh được phục vụ quý khách lần sau.<br>
        Tài liệu này được tạo tự động bởi Hệ Thống Website Đồng Hồ Online lúc {{ date('H:i:s d/m/Y') }}.
    </div>
</div>

</body>
</html>
