<?php

namespace App\Services;

class VietQRService
{
    private string $bankId;

    private string $accountNo;

    private string $accountName;

    public function __construct()
    {
        $this->bankId = config('vietqr.bank_id');
        $this->accountNo = config('vietqr.account_no');
        $this->accountName = config('vietqr.account_name');
    }

    // ── TẠO URL QR VIETQR API ──
    // Dùng API của VietQR (không cần thư viện thêm)
    public function getQRImageUrl(
        int $amount,
        string $orderCode,
        string $description = ''
    ): string {
        // Nội dung chuyển khoản (ngắn gọn, không dấu)
        $addInfo = $description
            ?: 'Thanh toan DH '.preg_replace('/[^A-Z0-9]/', '', strtoupper($orderCode));

        // Giới hạn 25 ký tự (chuẩn VietQR)
        $addInfo = substr($addInfo, 0, 25);

        // URL API VietQR (miễn phí, không cần key)
        $url = "https://img.vietqr.io/image/{$this->bankId}-{$this->accountNo}-compact2.png"
            ."?amount={$amount}"
            .'&addInfo='.urlencode($addInfo)
            .'&accountName='.urlencode($this->accountName);

        return $url;
    }

    // ── TẠO QR STRING (EMVCo Standard) ──
    // Dùng để tạo QR bằng thư viện local
    public function generateQRString(
        int $amount,
        string $orderCode
    ): string {
        $addInfo = 'Thanh toan '.$orderCode;

        // Chuẩn VietQR EMVCo
        $bankBin = $this->bankId;
        $acNo = $this->accountNo;

        // Tạo payload EMVCo
        $payload = $this->buildEMVCoPayload($bankBin, $acNo, $amount, $addInfo);

        return $payload;
    }

    // ── BUILD PAYLOAD EMVCO ──
    private function buildEMVCoPayload(
        string $bankBin,
        string $accountNo,
        int $amount,
        string $addInfo
    ): string {
        // Merchant Account Info
        $guid = '0010A0000007270102';
        $acField = $this->tlv('01', $accountNo);
        $mai = $this->tlv('00', $guid).$this->tlv('01', $acField);

        // Payload
        $data = '';
        $data .= $this->tlv('00', '01');             // Payload Format Indicator
        $data .= $this->tlv('01', '12');             // Point of Initiation
        $data .= $this->tlv('38', $mai);             // Merchant Account Info VietQR
        $data .= $this->tlv('52', '5999');           // Merchant Category Code
        $data .= $this->tlv('53', '704');            // Transaction Currency (VND)
        $data .= $this->tlv('54', (string) $amount);  // Amount
        $data .= $this->tlv('58', 'VN');             // Country Code
        $data .= $this->tlv('62',                    // Additional Data
            $this->tlv('08', $addInfo)
        );

        // CRC
        $data .= '6304';
        $data .= strtoupper($this->crc16($data));

        return $data;
    }

    private function tlv(string $tag, string $value): string
    {
        return $tag.str_pad(strlen($value), 2, '0', STR_PAD_LEFT).$value;
    }

    private function crc16(string $data): string
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc <<= 1;
                }
                $crc &= 0xFFFF;
            }
        }

        return sprintf('%04X', $crc);
    }

    // Getter cho thông tin tài khoản
    public function getBankId(): string
    {
        return $this->bankId;
    }

    public function getAccountNo(): string
    {
        return $this->accountNo;
    }

    public function getAccountName(): string
    {
        return $this->accountName;
    }

    // Lấy tên ngân hàng từ bank_id
    public function getBankName(): string
    {
        $banks = [
            '970422' => 'MB Bank',
            '970436' => 'Vietcombank',
            '970415' => 'VietinBank',
            '970418' => 'BIDV',
            '970405' => 'Agribank',
            '970432' => 'VPBank',
            '970423' => 'TPBank',
            '970416' => 'ACB',
            '970407' => 'Techcombank',
        ];

        return $banks[$this->bankId] ?? 'Ngân hàng';
    }
}
