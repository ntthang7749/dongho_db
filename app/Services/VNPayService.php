<?php

namespace App\Services;

class VNPayService
{
    private string $tmnCode;

    private string $hashSecret;

    private string $vnpayUrl;

    private string $returnUrl;

    public function __construct()
    {
        $this->tmnCode = config('vnpay.tmn_code');
        $this->hashSecret = config('vnpay.hash_secret');
        $this->vnpayUrl = config('vnpay.url');
        $this->returnUrl = config('vnpay.return_url');
    }

    // ── TẠO URL THANH TOÁN ──
    public function createPaymentUrl(
        string $orderCode,
        int $amount,
        string $orderInfo = '',
        string $ipAddr = '127.0.0.1'
    ): string {
        // VNPay chấp nhận amount từ 5.000đ đến dưới 1 tỷ đồng.
        if ($amount < 5000 || $amount >= 1_000_000_000) {
            throw new \InvalidArgumentException(
                "Số tiền không hợp lệ ({$amount}đ). VNPay yêu cầu từ 5.000đ đến dưới 1 tỷ đồng."
            );
        }

        // Thời gian tạo giao dịch — VNPay yêu cầu giờ Việt Nam (GMT+7).
        // KHÔNG dùng date()/strtotime() vì sẽ theo timezone PHP (UTC trong Laravel mặc định)
        // → lệch 7h → vnp_ExpireDate rơi vào quá khứ → VNPay reject "quá thời gian chờ".
        $vnTz = new \DateTimeZone('Asia/Ho_Chi_Minh');
        $createDate = (new \DateTime('now', $vnTz))->format('YmdHis');
        $expireDate = (new \DateTime('+15 minutes', $vnTz))->format('YmdHis');

        // Tham số gửi lên VNPay
        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $this->tmnCode,
            'vnp_Amount' => $amount * 100, // VNPay tính theo đơn vị VNĐ * 100
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => $createDate,
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $ipAddr,
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => $orderInfo ?: "Thanh toan don hang {$orderCode}",
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $this->returnUrl,
            'vnp_TxnRef' => $orderCode,    // Mã đơn hàng (unique)
            'vnp_ExpireDate' => $expireDate,
        ];

        // Sắp xếp theo key alphabet
        ksort($inputData);

        // Tạo chuỗi query
        $query = '';
        $hashData = '';
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i === 1) {
                $hashData .= '&'.urlencode($key).'='.urlencode($value);
            } else {
                $hashData .= urlencode($key).'='.urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key).'='.urlencode($value).'&';
        }

        // Tạo chữ ký HMAC SHA512
        $vnpSecureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        // URL thanh toán cuối cùng
        $paymentUrl = $this->vnpayUrl.'?'.$query
            .'vnp_SecureHash='.$vnpSecureHash;

        return $paymentUrl;
    }

    // ── XÁC THỰC CALLBACK TỪ VNPAY ──
    public function verifyReturn(array $data): bool
    {
        $vnpSecureHash = $data['vnp_SecureHash'] ?? '';

        // Loại bỏ các key không cần hash
        $inputData = $data;
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        // Sắp xếp theo key alphabet
        ksort($inputData);

        $hashData = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i === 1) {
                $hashData .= '&'.urlencode($key).'='.urlencode($value);
            } else {
                $hashData .= urlencode($key).'='.urlencode($value);
                $i = 1;
            }
        }

        // Tạo lại chữ ký để so sánh
        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return hash_equals($secureHash, $vnpSecureHash);
    }

    // ── KIỂM TRA GIAO DỊCH THÀNH CÔNG ──
    public function isSuccess(array $data): bool
    {
        return isset($data['vnp_ResponseCode'])
            && $data['vnp_ResponseCode'] === '00';
    }

    // ── LẤY MÃ GIAO DỊCH VNPAY ──
    public function getTransactionId(array $data): string
    {
        return $data['vnp_TransactionNo'] ?? '';
    }

    // ── LẤY MÃ ĐƠN HÀNG TỪ CALLBACK ──
    public function getOrderCode(array $data): string
    {
        return $data['vnp_TxnRef'] ?? '';
    }

    // ── MÃ PHẢN HỒI → TIN NHẮN ──
    public function getResponseMessage(string $code): string
    {
        $messages = [
            '00' => 'Giao dịch thành công',
            '07' => 'Trừ tiền thành công nhưng nghi ngờ gian lận',
            '09' => 'Thẻ/Tài khoản chưa đăng ký Internet Banking',
            '10' => 'Xác thực thông tin thẻ/tài khoản quá 3 lần',
            '11' => 'Hết hạn chờ thanh toán (15 phút)',
            '12' => 'Thẻ/Tài khoản bị khoá',
            '13' => 'Sai mật khẩu OTP',
            '24' => 'Khách hàng huỷ giao dịch',
            '51' => 'Tài khoản không đủ số dư',
            '65' => 'Vượt hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng đang bảo trì',
            '79' => 'Sai mật khẩu quá số lần quy định',
            '99' => 'Lỗi không xác định',
        ];

        return $messages[$code] ?? "Lỗi không xác định (Code: {$code})";
    }
}
