<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(
        private GeminiService $gemini
    ) {}

    // ── XỬ LÝ TIN NHẮN CHATBOT ──
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'history' => 'nullable|array',
            'history.*.role' => 'required|in:user,assistant',
            'history.*.content' => 'required|string',
        ]);

        $response = $this->gemini->chat(
            userMessage: $request->message,
            history: $request->history ?? []
        );

        return response()->json([
            'success' => true,
            'response' => $response,
        ]);
    }

    // ── GỢI Ý SẢN PHẨM QUA CHATBOT ──
    public function suggestProducts(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:200',
        ]);

        // Tìm kiếm thông minh
        $searchParams = $this->gemini->enhanceSearch($request->query);

        // Query sản phẩm dựa trên params
        $products = Product::with(['brand', 'category'])
            ->where('is_active', true);

        if (! empty($searchParams['brand'])) {
            $products->whereHas('brand', fn ($q) => $q->where('name', 'LIKE', '%'.$searchParams['brand'].'%'));
        }

        if (! empty($searchParams['category'])) {
            $products->whereHas('category', fn ($q) => $q->where('slug', $searchParams['category']));
        }

        if (! empty($searchParams['price_max'])) {
            $products->where(fn ($q) => $q->where('sale_price', '<=', $searchParams['price_max'])
                ->orWhere(fn ($q2) => $q2->whereNull('sale_price')
                    ->where('price', '<=', $searchParams['price_max'])
                )
            );
        }

        if (! empty($searchParams['keywords'])) {
            $keywords = $searchParams['keywords'];
            $products->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('name', 'LIKE', "%{$kw}%");
                }
            });
        }

        $results = $products->take(4)->get()->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => $p->sale_price ?? $p->price,
            'thumbnail' => $p->thumbnail
                ? asset('storage/'.$p->thumbnail)
                : null,
            'url' => route('products.show', $p->slug),
            'brand' => $p->brand->name ?? '',
            'rating' => $p->rating_avg,
        ]);

        return response()->json([
            'success' => true,
            'products' => $results,
            'reason' => $searchParams['reason'] ?? '',
        ]);
    }
}
