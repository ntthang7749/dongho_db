<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa Đơn Nhập Hàng #HDN-{{ $product->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
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
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header table {
            width: 100%;
        }
        .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: #c9a84c; /* Gold color matching admin theme */
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .shop-info {
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .title {
            text-align: center;
            text-transform: uppercase;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #1a1a2e;
        }
        .invoice-code {
            text-align: center;
            font-style: italic;
            font-size: 12px;
            margin-bottom: 25px;
            color: #555;
        }
        .info-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .main-table th {
            background-color: #1a1a2e;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 10px;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #1a1a2e;
        }
        .main-table td {
            padding: 10px;
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
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .spec-section {
            margin-top: 20px;
            margin-bottom: 35px;
        }
        .spec-title {
            font-size: 13px;
            font-weight: bold;
            color: #1a1a2e;
            border-left: 3px solid #c9a84c;
            padding-left: 8px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .spec-table {
            width: 100%;
            border-collapse: collapse;
        }
        .spec-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #f1f1f1;
        }
        .spec-label {
            font-weight: bold;
            color: #555;
            width: 250px;
        }
        .signature-section {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            width: 50%;
            float: left;
            text-align: center;
        }
        .signature-space {
            margin-top: 60px;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 180px;
            text-align: center;
            font-size: 10px;
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
                    <small style="color: #666; font-weight: bold;">HỆ THỐNG PHÂN PHỐI ĐỒNG HỒ CAO CẤP</small>
                </td>
                <td class="shop-info">
                    <strong>Địa chỉ:</strong> 123 Đường Ba Tháng Hai, Quận 10, TP. HCM<br>
                    <strong>Hotline:</strong> 0987.654.321 | <strong>Email:</strong> admin@donghoonline.com<br>
                    <strong>Website:</strong> www.donghoonline.com
                </td>
            </tr>
        </table>
    </div>

    <!-- Title -->
    <div class="title">PHIẾU NHẬP KHO & HÓA ĐƠN SẢN PHẨM</div>
    <div class="invoice-code">Số hóa đơn: HDN-{{ $product->id }}-{{ time() }} | Ngày lập: {{ date('d/m/Y H:i') }}</div>

    <!-- Info -->
    <table class="info-table">
        <tr>
            <td class="info-label">Người lập phiếu:</td>
            <td>{{ Auth::user()->name }} (Ban quản trị)</td>
            <td class="info-label" style="text-align: right; width: 100px;">Trạng thái:</td>
            <td style="text-align: right; color: green; font-weight: bold;">ĐÃ NHẬP KHO</td>
        </tr>
        <tr>
            <td class="info-label">Hình thức:</td>
            <td>Nhập kho sản phẩm mới / Cập nhật số lượng</td>
            <td class="info-label" style="text-align: right;">Loại tiền:</td>
            <td style="text-align: right;">VNĐ (đ)</td>
        </tr>
    </table>

    <!-- Main Table -->
    <table class="main-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">STT</th>
                <th width="15%" class="text-center">Ảnh sản phẩm</th>
                <th width="35%">Tên hàng & SKU</th>
                <th width="15%">Thương hiệu</th>
                <th width="10%" class="text-center">Số lượng</th>
                <th width="10%" class="text-right">Đơn giá gốc</th>
                <th width="10%" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="text-center">
                    @if($image_base64)
                        <img class="product-img" src="{{ $image_base64 }}">
                    @else
                        <div style="font-size: 9px; color: #999; border: 1px solid #ccc; padding: 15px 5px;">Không có ảnh</div>
                    @endif
                </td>
                <td>
                    <span class="fw-bold" style="font-size: 13px; color: #1a1a2e;">{{ $product->name }}</span><br>
                    <small style="color: #666;">Mã sản phẩm (SKU): <strong>{{ $product->sku ?? '—' }}</strong></small><br>
                    <small style="color: #666;">Danh mục: <strong>{{ $product->category->name ?? '—' }}</strong></small>
                </td>
                <td>{{ $product->brand->name ?? '—' }}</td>
                <td class="text-center fw-bold" style="font-size: 13px;">{{ $product->stock }}</td>
                <td class="text-right">{{ number_format($product->price) }}đ</td>
                <td class="text-right fw-bold" style="color: #c9a84c;">{{ number_format($product->price * $product->stock) }}đ</td>
            </tr>
            <!-- Total -->
            <tr>
                <td colspan="4" class="fw-bold text-right" style="background-color: #f8f9fa;">TỔNG CỘNG LÔ HÀNG NHẬP:</td>
                <td class="text-center fw-bold" style="background-color: #f8f9fa; font-size: 13px;">{{ $product->stock }}</td>
                <td class="text-right" style="background-color: #f8f9fa;">—</td>
                <td class="text-right fw-bold" style="background-color: #f8f9fa; color: #d9534f; font-size: 14px;">
                    {{ number_format($product->price * $product->stock) }}đ
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Specs Section -->
    <div class="spec-section">
        <div class="spec-title">Thông số kỹ thuật chi tiết của đồng hồ</div>
        <table class="spec-table">
            <tr>
                <td class="spec-label">Bộ máy (Movement):</td>
                <td>{{ $product->movement ?? 'Chưa cập nhật' }}</td>
            </tr>
            <tr>
                <td class="spec-label">Chất liệu vỏ (Case Material):</td>
                <td>{{ $product->material ?? 'Chưa cập nhật' }}</td>
            </tr>
            <tr>
                <td class="spec-label">Chất liệu kính (Glass Material):</td>
                <td>{{ $product->glass_material ?? 'Chưa cập nhật' }}</td>
            </tr>
            <tr>
                <td class="spec-label">Chất liệu dây (Band Material):</td>
                <td>{{ $product->band_material ?? 'Chưa cập nhật' }}</td>
            </tr>
            <tr>
                <td class="spec-label">Kích thước mặt (Case Size):</td>
                <td>{{ $product->case_size ?? 'Chưa cập nhật' }}</td>
            </tr>
            <tr>
                <td class="spec-label">Độ chống nước (Water Resistance):</td>
                <td>{{ $product->water_resistance ?? 'Chưa cập nhật' }}</td>
            </tr>
            <tr>
                <td class="spec-label">Màu sắc (Color):</td>
                <td>{{ $product->color ?? 'Chưa cập nhật' }}</td>
            </tr>
        </table>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <span><strong>Người lập biểu</strong></span><br>
            <small style="color: #666;">(Ký và ghi rõ họ tên)</small>
            <div class="signature-space">{{ Auth::user()->name }}</div>
        </div>
        <div class="signature-box">
            <span><strong>Thủ kho nhập xuất</strong></span><br>
            <small style="color: #666;">(Ký và ghi rõ họ tên)</small>
            <div class="signature-space">.........................................</div>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="footer-note">
        Tài liệu này được tạo tự động bởi Hệ Thống Quản Lý Cửa Hàng Đồng Hồ Online lúc {{ date('H:i:s d/m/Y') }}.<br>
        Mọi thắc mắc xin liên hệ bộ phận hỗ trợ kỹ thuật qua Hotline hoặc Email của công ty.
    </div>
</div>

</body>
</html>
