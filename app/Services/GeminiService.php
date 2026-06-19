<?php

namespace App\Services;

use App\Models\AiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;

    private string $model;

    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        $this->model = config('gemini.model');
        $this->baseUrl = config('gemini.base_url');
    }

    // ══════════════════════════════════════════
    // CORE: GỌI GEMINI API HỖ TRỢ ROTATE KEYS
    // ══════════════════════════════════════════
    private function sendRequest(string $action, array $payload): ?\Illuminate\Http\Client\Response
    {
        $keys = config('gemini.api_keys', []);
        
        if (empty($keys) || (count($keys) === 1 && empty($keys[0]))) {
            $keys = [$this->apiKey];
        }

        $keys = array_filter(array_map('trim', $keys));

        if (empty($keys)) {
            Log::error('Gemini Service: No API Key configured!');
            return null;
        }

        // Xáo trộn API keys ngẫu nhiên để chia đều tải và kéo dài hạn ngạch (Rate Limit)
        shuffle($keys);
        $lastException = null;
        
        foreach ($keys as $key) {
            try {
                $response = Http::timeout(config('gemini.timeout', 30))
                    ->post("{$this->baseUrl}/{$this->model}:{$action}?key={$key}", $payload);

                if ($response->successful()) {
                    return $response;
                }

                // Nếu gặp lỗi cạn hạn ngạch (429) hoặc sai/hết hạn Key (403), thử xoay sang Key khác
                if ($response->status() === 429 || $response->status() === 403) {
                    Log::warning("Gemini Key bị hạn chế (Status: {$response->status()}). Đang thử xoay vòng sang API Key tiếp theo...");
                    continue;
                }

                // Trả về luôn nếu là lỗi cú pháp hoặc cấu hình sai (ví dụ 400 Bad Request) để tránh lặp vô hạn
                return $response;

            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning("Lỗi kết nối Gemini với Key hiện tại: " . $e->getMessage() . ". Đang thử Key khác...");
            }
        }

        Log::error('Gemini Service: Tất cả các API Key trong danh sách xoay vòng đều thất bại!', [
            'total_keys' => count($keys),
            'last_error' => $lastException ? $lastException->getMessage() : 'Rate-limit / Quota exhausted'
        ]);

        return null;
    }

    // ══════════════════════════════════════════
    // CORE: GỌI GEMINI API (TEXT)
    // ══════════════════════════════════════════
    public function ask(
        string $prompt,
        ?string $systemPrompt = null,
        int $maxTokens = 1024
    ): string {
        try {
            $contents = [];

            // System prompt (nếu có)
            if ($systemPrompt) {
                $contents[] = [
                    'role' => 'user',
                    'parts' => [['text' => $systemPrompt]],
                ];
                $contents[] = [
                    'role' => 'model',
                    'parts' => [['text' => 'Tôi hiểu. Tôi sẽ tuân theo hướng dẫn trên.']],
                ];
            }

            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $prompt]],
            ];

            $generationConfig = [
                'maxOutputTokens' => $maxTokens,
                'temperature' => 0.7,
            ];

            $response = $this->sendRequest('generateContent', [
                'contents' => $contents,
                'generationConfig' => $generationConfig,
            ]);

            if ($response && $response->successful()) {
                $data = $response->json();

                return $data['candidates'][0]['content']['parts'][0]['text']
                    ?? 'Không có phản hồi từ AI.';
            }

            if ($response) {
                Log::error('Gemini API error after rotating', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }

            return 'Xin lỗi, AI đang bận hoặc cạn kiệt hạn ngạch. Vui lòng thử lại sau!';

        } catch (\Exception $e) {
            Log::error('Gemini exception: '.$e->getMessage());

            return 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại!';
        }
    }

    // ══════════════════════════════════════════
    // CORE: GỌI GEMINI API (TEXT + ẢNH)
    // ══════════════════════════════════════════
    public function askWithImage(
        string $prompt,
        string $imageBase64,
        string $mimeType = 'image/jpeg'
    ): string {
        try {
            $response = $this->sendRequest('generateContent', [
                'contents' => [[
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imageBase64,
                            ],
                        ],
                    ],
                ]],
                'generationConfig' => [
                    'maxOutputTokens' => 1024,
                    'temperature' => 0.5,
                ],
            ]);

            if ($response && $response->successful()) {
                return $response->json()['candidates'][0]['content']['parts'][0]['text']
                    ?? 'Không nhận diện được.';
            }

            return 'Không thể phân tích ảnh do lỗi kết nối hoặc cạn kiệt hạn ngạch.';

        } catch (\Exception $e) {
            Log::error('Gemini vision error: '.$e->getMessage());

            return 'Lỗi khi phân tích ảnh.';
        }
    }

    // ══════════════════════════════════════════
    // CORE: GỌI VÀ YÊU CẦU TRẢ VỀ JSON
    // ══════════════════════════════════════════
    public function askJSON(string $prompt, ?string $systemPrompt = null): array
    {
        $jsonPrompt = $prompt."\n\nQUAN TRỌNG: Chỉ trả về JSON thuần túy, "
            .'không có markdown, không có ```json, không có giải thích.';

        $raw = $this->ask($jsonPrompt, $systemPrompt, 512);

        // Làm sạch response
        $clean = preg_replace('/```json\s*|\s*```/', '', trim($raw));
        $clean = preg_replace('/^[^{[]*/', '', $clean); // bỏ text trước {
        $clean = preg_replace('/[^}\]]*$/', '', $clean); // bỏ text sau }

        try {
            return json_decode($clean, true) ?? [];
        } catch (\Exception $e) {
            Log::error('JSON parse error: '.$e->getMessage());

            return [];
        }
    }

    // ══════════════════════════════════════════
    // CORE: GHI LOG AI
    // ══════════════════════════════════════════
    public function log(
        string $type,
        string $input,
        string $output,
        ?int $userId = null
    ): void {
        try {
            AiLog::create([
                'user_id' => $userId ?? auth()->id(),
                'type' => $type,
                'input' => $input,
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            Log::error('AI log error: '.$e->getMessage());
        }
    }

    // ══════════════════════════════════════════
    // FEATURE 1: CHATBOT TƯ VẤN SẢN PHẨM
    // ══════════════════════════════════════════
    public function chat(string $userMessage, array $history = []): string
    {
        $systemPrompt = "Bạn là trợ lý tư vấn của website 'Đồng Hồ Online' — phục vụ KHÁCH HÀNG.

ĐƯỢC PHÉP trả lời:
- Tư vấn chọn đồng hồ theo nhu cầu (giới tính, ngân sách, mục đích, phong cách)
- Giải thích thông số kỹ thuật (chống nước ATM, bộ máy quartz/automatic, sapphire, …)
- Thông tin sản phẩm có trên website (tên, giá, mô tả, thương hiệu, danh mục)
- Còn hàng / hết hàng (chỉ trả lời 'còn' hoặc 'tạm hết', không nói số lượng cụ thể)
- Hướng dẫn đặt hàng, thanh toán (COD / VNPay / QR), vận chuyển
- Chính sách: đổi trả 30 ngày, bảo hành 12 tháng

TUYỆT ĐỐI TỪ CHỐI (không trả lời, lịch sự đổi chủ đề):
- Số lượng tồn kho cụ thể (vd: 'còn bao nhiêu chiếc?')
- Doanh thu, doanh số, số đơn hàng, số khách hàng
- Thông tin user khác, đơn hàng người khác
- Dữ liệu admin, cấu hình hệ thống, API key, mã nguồn
- Câu hỏi ngoài chủ đề đồng hồ / dịch vụ shop (chính trị, tin tức, code, học tập…)

Cách từ chối: 'Mình chỉ hỗ trợ tư vấn đồng hồ và đặt hàng nha 😊 Bạn muốn xem loại nào?'

QUY TẮC GIAO TIẾP:
- Tiếng Việt thân thiện, có thể dùng emoji vừa phải
- Trả lời ngắn gọn 2-4 câu, KHÔNG dài dòng
- KHÔNG tiết lộ bạn là Gemini/AI — tự xưng 'Trợ lý của Đồng Hồ Online'
- KHÔNG bịa thông tin sản phẩm — nếu không chắc, gợi ý khách dùng search hoặc xem trang sản phẩm";

        $contents = [];

        // Giả lập System Prompt (đảm bảo đồng bộ với phương thức ask)
        if ($systemPrompt) {
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $systemPrompt]],
            ];
            $contents[] = [
                'role' => 'model',
                'parts' => [['text' => 'Tôi hiểu. Tôi sẽ tuân theo hướng dẫn trên.']],
            ];
        }

        // Định dạng lịch sử hội thoại thành các lượt (turns) riêng biệt
        if (! empty($history)) {
            foreach (array_slice($history, -6) as $msg) {
                $role = $msg['role'] === 'user' ? 'user' : 'model';
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $msg['content']]],
                ];
            }
        }

        // Thêm câu hỏi hiện tại
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        $generationConfig = [
            'maxOutputTokens' => 1024, // Tăng giới hạn token để tránh cắt cụt câu trả lời dài
            'temperature' => 0.7,
        ];

        try {
            $response = $this->sendRequest('generateContent', [
                'contents' => $contents,
                'generationConfig' => $generationConfig,
            ]);

            if ($response && $response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Không có phản hồi từ AI.';

                // Log
                $this->log('chatbot', $userMessage, $reply);

                return $reply;
            }

            if ($response) {
                Log::error('Gemini chatbot API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }

            return 'Xin lỗi, AI đang bận hoặc cạn kiệt hạn ngạch. Vui lòng thử lại sau!';

        } catch (\Exception $e) {
            Log::error('Gemini chatbot exception: '.$e->getMessage());
            return 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại!';
        }
    }

    // ══════════════════════════════════════════
    // FEATURE 1b: CHATBOT ADMIN (CÓ CONTEXT DỮ LIỆU)
    // ══════════════════════════════════════════
    public function chatAdmin(string $userMessage, array $context, array $history = []): string
    {
        $systemPrompt = "Bạn là trợ lý phân tích dữ liệu nội bộ của 'Đồng Hồ Online' — phục vụ ADMIN.

Admin có quyền hỏi:
- Doanh thu, đơn hàng, số khách hàng, top sản phẩm bán chạy
- Tồn kho từng sản phẩm (số lượng cụ thể), sản phẩm sắp hết hàng
- Tỷ lệ huỷ đơn, tỷ lệ thanh toán COD vs VNPay
- Khuyến nghị kinh doanh dựa vào số liệu được cấp

QUY TẮC:
- CHỈ trả lời dựa vào BUSINESS CONTEXT bên dưới — KHÔNG bịa.
- Nếu admin hỏi thông tin KHÔNG có trong context, nói rõ 'Dữ liệu này chưa có trong báo cáo. Bạn có thể xem chi tiết ở trang [chỗ phù hợp].'
- Định dạng số: thêm dấu chấm/phẩy cho dễ đọc (vd 1.990.000đ).
- Tiếng Việt chuyên nghiệp, ngắn gọn (3-6 câu), KHÔNG emoji.
- KHÔNG nhắc API key, password, hash, dữ liệu cá nhân user (email, sđt, địa chỉ).
- KHÔNG tiết lộ bạn là Gemini.";

        $contextText = "BUSINESS CONTEXT (snapshot lúc " . now('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') . "):\n";
        foreach ($context as $key => $value) {
            if (is_array($value)) {
                $contextText .= "- {$key}:\n";
                foreach ($value as $k => $v) {
                    $contextText .= "    • {$k}: " . (is_scalar($v) ? $v : json_encode($v, JSON_UNESCAPED_UNICODE)) . "\n";
                }
            } else {
                $contextText .= "- {$key}: {$value}\n";
            }
        }

        $prompt = $contextText . "\n";
        if (! empty($history)) {
            $prompt .= "LỊCH SỬ HỘI THOẠI:\n";
            foreach (array_slice($history, -6) as $msg) {
                $role = $msg['role'] === 'user' ? 'Admin' : 'Trợ lý';
                $prompt .= "{$role}: {$msg['content']}\n";
            }
        }
        $prompt .= "\nAdmin: {$userMessage}";

        $response = $this->ask($prompt, $systemPrompt, 2048);
        $this->log('chatbot_admin', $userMessage, $response);

        return $response;
    }

    // ══════════════════════════════════════════
    // FEATURE 2: TẠO MÔ TẢ SẢN PHẨM
    // ══════════════════════════════════════════
    public function generateProductDescription(array $productInfo): string
    {
        // System role: chuyên gia copywriter cho e-commerce đồng hồ
        $systemPrompt = "Bạn là chuyên gia copywriter cho website e-commerce 'Đồng Hồ Online'.
Phong cách viết: chuyên nghiệp, lôi cuốn, kích thích cảm xúc mua hàng nhưng không sáo rỗng.
Khi tả đồng hồ NAM: chú trọng phong thái lịch lãm, đẳng cấp, mạnh mẽ.
Khi tả đồng hồ NỮ: chú trọng tinh tế, thanh lịch, nữ tính, phụ kiện thời trang.
Khi tả đồng hồ TREO TƯỜNG: chú trọng decor không gian, gia đình, ý nghĩa thời gian.
TUYỆT ĐỐI:
- Không phóng đại trắng trợn (\"rẻ nhất\", \"tốt nhất thế giới\"...)
- Không nhắc đến đối thủ
- Không dùng cụm dập khuôn (\"không thể bỏ lỡ\", \"siêu phẩm\")
- Không bịa thông số kỹ thuật ngoài data được cấp
- Không ghi giá trong bài (sẽ hiển thị ở chỗ khác)";

        // Chỉ giữ field có giá trị thật (bỏ rỗng/Không rõ)
        $specs = [
            'Chất liệu vỏ' => $productInfo['material'] ?? null,
            'Kính' => $productInfo['glass_material'] ?? null,
            'Dây' => $productInfo['band_material'] ?? null,
            'Chống nước' => $productInfo['water_resistance'] ?? null,
            'Bộ máy' => $productInfo['movement'] ?? null,
            'Kích thước mặt' => $productInfo['case_size'] ?? null,
            'Màu sắc' => $productInfo['color'] ?? null,
        ];
        $specsLines = '';
        foreach ($specs as $label => $value) {
            $v = trim((string) $value);
            if ($v !== '' && mb_strtolower($v) !== 'không rõ') {
                $specsLines .= "- {$label}: {$v}\n";
            }
        }
        if ($specsLines === '') {
            $specsLines = "(Chưa có thông số kỹ thuật cụ thể — hãy viết tổng quát, dựa vào tên sản phẩm và thương hiệu.)\n";
        }

        $brand = trim((string) ($productInfo['brand'] ?? ''));
        $category = trim((string) ($productInfo['category'] ?? ''));

        $prompt = "Viết mô tả sản phẩm cho website bán đồng hồ.

THÔNG TIN SẢN PHẨM:
- Tên: {$productInfo['name']}".
            ($brand !== '' && mb_strtolower($brand) !== 'không rõ' ? "\n- Thương hiệu: {$brand}" : '').
            ($category !== '' && mb_strtolower($category) !== 'đồng hồ' ? "\n- Danh mục: {$category}" : '')."

THÔNG SỐ KỸ THUẬT:
{$specsLines}

CẤU TRÚC MÔ TẢ (viết liền mạch, KHÔNG dùng heading/bullet):
1) Câu mở đầu (1 dòng): tạo ấn tượng — gợi phong cách, đối tượng hoặc khoảnh khắc đeo.
2) Đoạn 1 (~60 từ): thiết kế tổng thể, cảm hứng, ngoại hình, ai phù hợp đeo.
3) Đoạn 2 (~80 từ): điểm sáng kỹ thuật — chỉ nhắc thông số có trong data ở trên, lồng vào lợi ích thực tế (bền, sang, dùng được khi nào).
4) Đoạn 3 (~60 từ): cảm xúc + dịp sử dụng (đi làm, dự tiệc, hẹn hò, quà tặng…).
5) Câu kết (1 dòng): CTA nhẹ nhàng (vd: \"Đặt ngay để cảm nhận sự khác biệt.\").

YÊU CẦU OUTPUT:
- Chỉ trả về phần mô tả (không kèm tiêu đề \"Mô tả:\", không kèm phần giải thích).
- Tiếng Việt tự nhiên, văn nói chuyên nghiệp.
- 200-300 từ tổng, chia thành ~4 đoạn ngắn cách nhau bằng dòng trống.
- KHÔNG markdown, KHÔNG emoji, KHÔNG liệt kê bằng gạch đầu dòng.";

        // maxTokens=4096: gemini-2.5-flash dùng nhiều token cho thinking trước output —
        // phải cấp đủ budget để output 200-300 từ mô tả không bị cắt.
        $response = $this->ask($prompt, $systemPrompt, 4096);
        $this->log('description', $productInfo['name'], $response);

        return $response;
    }

    // ══════════════════════════════════════════
    // FEATURE 3: GỢI Ý TIÊU ĐỀ BÀI VIẾT
    // ══════════════════════════════════════════
    public function suggestNewsTitles(string $content, int $count = 5): array
    {
        $prompt = "Dựa vào nội dung bài viết sau, hãy gợi ý {$count} tiêu đề hấp dẫn:

Nội dung: ".substr($content, 0, 500).'

Yêu cầu:
- Tiêu đề ngắn gọn, hấp dẫn, gây tò mò
- Phù hợp với chủ đề đồng hồ
- Có thể dùng số liệu, câu hỏi hoặc cảm xúc
- Trả về JSON: {"titles": ["tiêu đề 1", "tiêu đề 2", ...]}';

        $result = $this->askJSON($prompt);
        $this->log('description', substr($content, 0, 100), json_encode($result));

        return $result['titles'] ?? [];
    }

    // ══════════════════════════════════════════
    // FEATURE 4: PHÂN TÍCH CẢM XÚC ĐÁNH GIÁ
    // ══════════════════════════════════════════
    public function analyzeSentiment(string $reviewText): array
    {
        $prompt = "Phân tích cảm xúc của đánh giá sản phẩm đồng hồ sau:

Đánh giá: \"{$reviewText}\"

Trả về JSON với format:
{
  \"sentiment\": \"positive\" hoặc \"neutral\" hoặc \"negative\",
  \"score\": số thực từ 0.0 đến 1.0 (1.0 = rất tích cực),
  \"keywords\": [\"từ khoá cảm xúc 1\", \"từ khoá 2\"],
  \"summary\": \"tóm tắt 1 câu ngắn\"
}";

        $result = $this->askJSON($prompt);
        $this->log('sentiment', $reviewText, json_encode($result));

        return [
            'sentiment' => $result['sentiment'] ?? 'neutral',
            'score' => (float) ($result['score'] ?? 0.5),
            'keywords' => $result['keywords'] ?? [],
            'summary' => $result['summary'] ?? '',
        ];
    }

    // ══════════════════════════════════════════
    // FEATURE 5: TÓM TẮT ĐÁNH GIÁ SẢN PHẨM
    // ══════════════════════════════════════════
    public function summarizeReviews(array $reviews, string $productName): string
    {
        if (empty($reviews)) {
            return 'Chưa có đánh giá nào.';
        }

        $reviewsText = '';
        foreach (array_slice($reviews, 0, 20) as $i => $r) {
            $reviewsText .= ($i + 1).". [{$r['rating']} sao] {$r['comment']}\n";
        }

        $prompt = "Tóm tắt các đánh giá của sản phẩm đồng hồ '{$productName}':

{$reviewsText}

Yêu cầu:
- Tóm tắt điểm mạnh nổi bật nhất
- Tóm tắt điểm cần cải thiện (nếu có)
- Đánh giá tổng thể khách quan
- Độ dài: 3-5 câu
- Ngôn ngữ: Tiếng Việt, khách quan, chuyên nghiệp";

        $response = $this->ask($prompt, null, 512);
        $this->log('summary', $productName, $response);

        return $response;
    }

    // ══════════════════════════════════════════
    // FEATURE 6: LỌC SPAM BÌNH LUẬN
    // ══════════════════════════════════════════
    public function detectSpam(string $comment): array
    {
        $prompt = "Hãy thực hiện kiểm duyệt nội dung (content moderation) cho bình luận dưới đây.
Kiểm tra xem bình luận này có vi phạm bất kỳ quy tắc nào trong các nhóm sau không:

1. Spam / Quảng cáo / Nhảm nhí: Chứa liên kết (links) quảng cáo lạ, rao vặt, nội dung lặp đi lặp lại vô nghĩa, quảng bá dịch vụ bên thứ ba trái phép.
2. Ngôn từ bậy bạ / Tục tĩu / Nhạy cảm: Chứa các từ chửi thề, tục tĩu, thô thiển, ngôn từ xúc phạm nhân phẩm danh dự người khác hoặc từ ngữ nhạy cảm thô tục.
3. Phân biệt vùng miền / Chủng tộc / Kỳ thị: Chứa các phát ngôn gây chia rẽ, phân biệt đối xử, chửi bới, kỳ thị vùng miền (như Nam Kỳ, Bắc Kỳ, phân biệt Bắc Nam...), chủng tộc, sắc tộc hoặc tôn giáo.
4. Mất trật tự mạng xã hội / Kích động / Bạo lực: Kích động bạo lực, biểu tình trái phép, đe dọa người khác, phá hoại trật tự công cộng, hoặc tuyên truyền sai lệch nghiêm trọng.

Bình luận cần đánh giá: \"{$comment}\"

Yêu cầu trả về định dạng JSON thuần túy như sau:
{
  \"is_spam\": true (nếu vi phạm bất kỳ quy tắc nào ở trên) hoặc false (nếu hoàn toàn sạch sẽ, lịch sự, an toàn),
  \"confidence\": mức độ tin cậy từ 0.0 đến 1.0 (ví dụ: 0.95),
  \"reason\": \"Hãy cung cấp một câu giải thích bằng tiếng Việt chi tiết, lịch sự và rõ ràng vì sao bình luận vi phạm quy tắc cụ thể nào, hoặc ghi 'Bình luận hợp lệ và an toàn' nếu bình luận sạch sẽ.\"
}";

        $result = $this->askJSON($prompt);
        $this->log('spam_filter', $comment, json_encode($result, JSON_UNESCAPED_UNICODE));

        return [
            'is_spam' => (bool) ($result['is_spam'] ?? false),
            'confidence' => (float) ($result['confidence'] ?? 0.0),
            'reason' => $result['reason'] ?? 'Bình luận hợp lệ và an toàn',
        ];
    }

    // ══════════════════════════════════════════
    // FEATURE 6b: LỌC SPAM ĐÁNH GIÁ SẢN PHẨM
    // ══════════════════════════════════════════
    public function detectReviewSpam(string $review): array
    {
        $prompt = "Hãy thực hiện kiểm duyệt nội dung (content moderation) cho đánh giá sản phẩm (product review) dưới đây.
Kiểm tra xem đánh giá này có vi phạm bất kỳ quy tắc nào trong các nhóm sau không:

1. Spam / Quảng cáo / Nhảm nhí: Chứa liên kết (links) quảng cáo lạ, rao vặt, nội dung lặp đi lặp lại vô nghĩa, quảng bá dịch vụ bên thứ ba trái phép, hoặc văn bản nhảm nhí không liên quan gì đến sản phẩm đồng hồ.
2. Ngôn từ bậy bạ / Tục tĩu / Nhạy cảm: Chứa các từ chửi thề, tục tĩu, thô thiển, ngôn từ xúc phạm nhân phẩm danh dự người khác hoặc từ ngữ nhạy cảm thô tục.
3. Phân biệt vùng miền / Chủng tộc / Kỳ thị: Chứa các phát ngôn gây chia rẽ, phân biệt đối xử, chửi bới, kỳ thị vùng miền (như Nam Kỳ, Bắc Kỳ, phân biệt Bắc Nam...), chủng tộc, sắc tộc hoặc tôn giáo.
4. Mất trật tự mạng xã hội / Kích động / Bạo lực: Kích động bạo lực, biểu tình trái phép, đe dọa người khác, phá hoại trật tự công cộng, hoặc tuyên truyền sai lệch nghiêm trọng.

Đánh giá cần kiểm duyệt: \"{$review}\"

Yêu cầu trả về định dạng JSON thuần túy như sau:
{
  \"is_spam\": true (nếu vi phạm bất kỳ quy tắc nào ở trên) hoặc false (nếu hoàn toàn sạch sẽ, lịch sự, an toàn),
  \"confidence\": mức độ tin cậy từ 0.0 đến 1.0 (ví dụ: 0.95),
  \"reason\": \"Hãy cung cấp một câu giải thích bằng tiếng Việt chi tiết, lịch sự và rõ ràng vì sao đánh giá vi phạm quy tắc cụ thể nào, hoặc ghi 'Đánh giá hợp lệ và an toàn' nếu đánh giá sạch sẽ.\"
}";

        $result = $this->askJSON($prompt);
        $this->log('review_spam_filter', $review, json_encode($result, JSON_UNESCAPED_UNICODE));

        return [
            'is_spam' => (bool) ($result['is_spam'] ?? false),
            'confidence' => (float) ($result['confidence'] ?? 0.0),
            'reason' => $result['reason'] ?? 'Đánh giá hợp lệ và an toàn',
        ];
    }

    // ══════════════════════════════════════════
    // FEATURE 7: GỢI Ý SẢN PHẨM CÁ NHÂN HOÁ
    // ══════════════════════════════════════════
    public function getProductRecommendations(
        array $viewedProducts,
        array $allProducts,
        int $limit = 4
    ): array {
        if (empty($viewedProducts)) {
            return [];
        }

        $viewedText = '';
        foreach ($viewedProducts as $p) {
            $viewedText .= "- {$p['name']} ({$p['category']}, {$p['brand']}, "
                .number_format($p['price'])."đ)\n";
        }

        $catalogText = '';
        foreach (array_slice($allProducts, 0, 30) as $p) {
            $catalogText .= "ID:{$p['id']} - {$p['name']} "
                ."({$p['category']}, {$p['brand']}, "
                .number_format($p['price'])."đ)\n";
        }

        $prompt = "Dựa trên lịch sử xem đồng hồ của khách:

ĐÃ XEM:
{$viewedText}

DANH MỤC SẢN PHẨM:
{$catalogText}

Gợi ý {$limit} sản phẩm phù hợp nhất (không trùng đã xem).
Trả về JSON: {\"ids\": [id1, id2, id3, id4], \"reason\": \"lý do ngắn\"}";

        $result = $this->askJSON($prompt);
        $this->log('suggest', $viewedText, json_encode($result));

        return [
            'ids' => array_slice($result['ids'] ?? [], 0, $limit),
            'reason' => $result['reason'] ?? 'Dựa trên lịch sử xem của bạn',
        ];
    }

    // ══════════════════════════════════════════
    // FEATURE 8: TÌM KIẾM THÔNG MINH
    // ══════════════════════════════════════════
    public function enhanceSearch(string $query): array
    {
        $prompt = "Người dùng tìm kiếm đồng hồ với từ khoá: \"{$query}\"

Phân tích và mở rộng từ khoá tìm kiếm.
Trả về JSON:
{
  \"keywords\": [\"từ khoá 1\", \"từ khoá 2\", \"từ khoá 3\"],
  \"category\": \"dong-ho-nam\" hoặc \"dong-ho-nu\" hoặc \"dong-ho-treo-tuong\" hoặc null,
  \"brand\": \"tên thương hiệu\" hoặc null,
  \"price_max\": số tiền tối đa hoặc null,
  \"features\": [\"tính năng 1\", \"tính năng 2\"]
}

Ví dụ: \"đồng hồ casio chống nước giá rẻ\" → brand: casio, features: [chống nước], price_max: 2000000";

        $result = $this->askJSON($prompt);
        $this->log('suggest', $query, json_encode($result));

        return $result;
    }

    // ══════════════════════════════════════════
    // FEATURE 9: NHẬN DIỆN HÌNH ẢNH ĐỒNG HỒ
    // ══════════════════════════════════════════
    public function recognizeWatch(string $imageBase64, string $mimeType = 'image/jpeg'): array
    {
        $prompt = 'Phân tích hình ảnh đồng hồ này và trả về thông tin chi tiết.

Hãy xác định:
1. Thương hiệu (nếu nhận ra)
2. Loại đồng hồ (nam/nữ/treo tường)
3. Phong cách (thể thao/cổ điển/hiện đại/sang trọng)
4. Màu sắc chủ đạo
5. Chất liệu dây (da/thép/nhựa/...)
6. Kích thước ước tính
7. Tính năng đặc biệt thấy được
8. Mức giá ước tính (VNĐ)
9. Mô tả ngắn

Trả về JSON:
{
  "brand": "tên thương hiệu hoặc Không rõ",
  "type": "nam/nữ/treo tường",
  "style": "phong cách",
  "color": "màu sắc",
  "band_material": "chất liệu dây",
  "estimated_size": "kích thước mm",
  "features": ["tính năng 1", "tính năng 2"],
  "estimated_price_min": số,
  "estimated_price_max": số,
  "description": "mô tả ngắn",
  "confidence": 0.0-1.0
}';

        $raw = $this->askWithImage($prompt, $imageBase64, $mimeType);
        $clean = preg_replace('/```json\s*|\s*```/', '', trim($raw));
        $clean = preg_replace('/^[^{]*/', '', $clean);
        $clean = preg_replace('/[^}]*$/', '', $clean);

        try {
            $result = json_decode($clean, true) ?? [];
        } catch (\Exception $e) {
            $result = ['description' => $raw];
        }

        $this->log('image_recognition', 'image_upload', json_encode($result));

        return $result;
    }

    // ══════════════════════════════════════════
    // FEATURE 10: TỰ ĐỘNG VIẾT BÀI VIẾT MỚI
    // ══════════════════════════════════════════
    public function generateFullNews(string $prompt): array
    {
        $systemPrompt = "Bạn là biên tập viên tin tức chuyên nghiệp, am hiểu sâu sắc về thế giới đồng hồ (đồng hồ đeo tay, đồng hồ treo tường, các thương hiệu như Rolex, Casio, Seiko, Citizen, Orient, Tissot, Fossil, ...).
Nhiệm vụ của bạn là nhận từ khóa hoặc ý tưởng ngắn từ người dùng và viết một bài viết tin tức hoàn chỉnh, lôi cuốn, chuyên nghiệp chuẩn SEO.

Bài viết cần có cấu trúc:
1. Tiêu đề hấp dẫn, gây tò mò, giật tít nhưng không quá đà.
2. Tóm tắt ngắn gọn (~2-3 câu) khái quát bài viết.
3. Nội dung bài viết chi tiết, dài khoảng 300-600 từ, được định dạng đẹp mắt bằng các đoạn văn rõ ràng. Bạn có thể sử dụng các thẻ HTML cơ bản như <p>, <strong>, <h3> để làm cho bài viết chuyên nghiệp, dễ đọc.

QUY TẮC:
- Trả về kết quả hoàn toàn bằng tiếng Việt.
- Chỉ trả về duy nhất định dạng JSON thuần túy, không có markdown, không có ```json, không có văn bản giải thích thừa.
- JSON trả về phải khớp chính xác cấu trúc sau:
{
  \"title\": \"Tiêu đề bài viết\",
  \"summary\": \"Tóm tắt bài viết\",
  \"content\": \"Nội dung bài viết...\"
}";

        $userPrompt = "Hãy viết một bài viết tin tức chất lượng cao về chủ đề: \"{$prompt}\"";

        $result = $this->askJSON($userPrompt, $systemPrompt);

        // Ghi log hoạt động AI
        $this->log('generate_news', $prompt, json_encode($result, JSON_UNESCAPED_UNICODE));

        return $result;
    }

    // ══════════════════════════════════════════
    // FEATURE 11: GỢI Ý PHẢN HỒI LIÊN HỆ (ĐỌC THÔNG TIN SHOP)
    // ══════════════════════════════════════════
    public function suggestContactReply(
        string $customerName,
        string $contactSubject,
        string $contactMessage,
        string $contactType
    ): string {
        $systemPrompt = "Bạn là Trợ lý Chăm sóc Khách hàng chuyên nghiệp của cửa hàng 'Đồng Hồ Online'.
Nhiệm vụ của bạn là soạn thảo thư/tin nhắn trả lời phản hồi, câu hỏi hoặc báo cáo của khách hàng một cách lịch sự, tận tâm, chuyên nghiệp và có xưng hô cá nhân hóa rõ ràng (ví dụ: 'Chào bạn [Tên khách hàng],...').

Dưới đây là THÔNG TIN CỬA HÀNG của chúng tôi để bạn tham khảo và trả lời chính xác:
1. Thông tin liên hệ cơ bản:
   - Tên cửa hàng: Đồng Hồ Online
   - Địa chỉ showroom: 123 Đường Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh
   - Hotline hỗ trợ: 1800 6868 (Miễn phí cuộc gọi)
   - Email chăm sóc khách hàng: support@donghoonline.vn
   - Giờ làm việc: Thứ 2 – Chủ nhật: 8:00 – 21:00 (Kể cả ngày lễ)

2. Chính sách Bảo hành:
   - Chương trình BẢO HÀNH VÀNG 5 NĂM: Độc quyền bảo dưỡng & chăm sóc toàn diện tại trung tâm bảo hành của Đồng Hồ Online. Trọn vẹn an tâm sử dụng trong suốt 5 năm.
   - Thay pin miễn phí trọn đời cho tất cả đồng hồ mua tại cửa hàng của chúng tôi.
   - Hỗ trợ lau dầu, chống nước, đánh bóng mặt kính định kỳ với mức chiết khấu ưu đãi cho khách hàng cũ.

3. Chính sách Đổi trả & Hoàn tiền:
   - Hỗ trợ đổi mới sản phẩm 1 ĐỔI 1 MIỄN PHÍ trong 7 ngày đầu tiên nếu phát sinh lỗi từ nhà sản xuất hoặc sản phẩm bị trầy xước, nứt vỡ mặt kính do quá trình vận chuyển.
   - Điều kiện đổi trả: Sản phẩm phải còn nguyên vẹn 100% vỏ, hộp, thẻ bảo hành, tem chống giả, chưa có dấu hiệu tự ý tháo mở máy và không bị hư hại cơ học do va đập hay sử dụng sai cách từ phía khách hàng.

4. Chính sách Giao hàng & Vận chuyển:
   - Miễn phí giao hàng toàn quốc (Free shipping) cho tất cả các đơn hàng từ 1.000.000đ trở lên.
   - Thời gian giao hàng dự kiến: Nội thành TP.HCM & Hà Nội: 1–2 ngày làm việc; Các tỉnh thành khác trên toàn quốc: 2–4 ngày làm việc.

5. Phương thức Thanh toán:
   - Hỗ trợ Thanh toán khi nhận hàng (COD), Chuyển khoản ngân hàng qua mã QR động (VietQR), hoặc Thanh toán trực tuyến an toàn qua cổng VNPay.

QUY TẮC PHẢN HỒI:
- Bắt đầu bằng lời chào thân thiện, xưng hô cá nhân hóa, ví dụ: 'Kính chào Anh/Chị [Tên khách hàng],' hoặc 'Chào bạn [Tên khách hàng],'.
- Bày tỏ sự trân trọng đối với phản hồi/đóng góp ý kiến của khách hàng.
- Nếu khách hàng phản ánh sự cố hoặc lỗi vận chuyển/sản phẩm: Bắt đầu bằng một lời xin lỗi chân thành vì trải nghiệm không tốt, sau đó đưa ra giải pháp rõ ràng dựa trên chính sách của shop (đổi mới trong 7 ngày, bảo hành vàng 5 năm, liên hệ hotline, đem trực tiếp qua showroom, ...).
- Trả lời đúng trọng tâm câu hỏi của khách hàng, sử dụng chính xác các thông tin cửa hàng cung cấp ở trên. Không được bịa đặt thông tin khác.
- Ngôn ngữ: Tiếng Việt lịch sự, nhã nhặn, chuyên nghiệp và ấm áp.
- Độ dài: Khoảng 100-250 từ, chia đoạn rõ ràng để dễ đọc.
- KHÔNG sử dụng định dạng Markdown trong câu trả lời (như **, #, etc.), chỉ dùng văn bản thuần túy có xuống dòng tự nhiên, vì nội dung này sẽ được điền trực tiếp vào ô nhập liệu phản hồi của Admin.";

        $prompt = "Hãy viết thư phản hồi cho khách hàng dựa trên thông tin sau:
- Tên khách hàng: {$customerName}
- Loại liên hệ: {$contactType}
- Tiêu đề liên hệ: {$contactSubject}
- Nội dung tin nhắn của khách hàng: \"{$contactMessage}\"";

        $response = $this->ask($prompt, $systemPrompt, 1024);

        // Ghi log hoạt động AI
        $this->log('suggest_contact_reply', "Contact Subject: {$contactSubject} | Name: {$customerName}", $response);

        return $response;
    }
}

