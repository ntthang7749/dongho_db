<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ProductAIController extends Controller
{
    public function __construct(
        private GeminiService $gemini
    ) {}

    // ── GENERATE MÔ TẢ SẢN PHẨM (Admin) ──
    public function generateDescription(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'brand' => 'nullable|string',
            'category' => 'nullable|string',
            'price' => 'nullable|numeric',
            'material' => 'nullable|string',
            'glass_material' => 'nullable|string',
            'band_material' => 'nullable|string',
            'water_resistance' => 'nullable|string',
            'movement' => 'nullable|string',
            'case_size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        // Pass-through giá trị rỗng — service tự lọc field không có data.
        $description = $this->gemini->generateProductDescription([
            'name' => $request->name,
            'brand' => $request->brand,
            'category' => $request->category,
            'price' => $request->price,
            'material' => $request->material,
            'glass_material' => $request->glass_material,
            'band_material' => $request->band_material,
            'water_resistance' => $request->water_resistance,
            'movement' => $request->movement,
            'case_size' => $request->case_size,
            'color' => $request->color,
        ]);

        return response()->json([
            'success' => true,
            'description' => $description,
        ]);
    }

    // ── GỢI Ý SẢN PHẨM CÁ NHÂN HOÁ ──
    public function getPersonalizedSuggestions(Request $request)
    {
        // Lấy lịch sử xem từ session
        $viewedIds = session('viewed_products', []);

        if (empty($viewedIds)) {
            return response()->json(['success' => true, 'products' => []]);
        }

        $viewedProducts = Product::with(['brand', 'category'])
            ->whereIn('id', $viewedIds)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'brand' => $p->brand->name ?? '',
                'category' => $p->category->name ?? '',
                'price' => $p->sale_price ?? $p->price,
            ])
            ->toArray();

        $allProducts = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->whereNotIn('id', $viewedIds)
            ->inRandomOrder()
            ->take(30)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'brand' => $p->brand->name ?? '',
                'category' => $p->category->name ?? '',
                'price' => $p->sale_price ?? $p->price,
            ])
            ->toArray();

        $result = $this->gemini->getProductRecommendations(
            $viewedProducts,
            $allProducts,
            4
        );

        // Lấy sản phẩm theo IDs được gợi ý
        $products = [];
        if (! empty($result['ids'])) {
            $products = Product::with(['brand'])
                ->whereIn('id', $result['ids'])
                ->where('is_active', true)
                ->get()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->sale_price ?? $p->price,
                    'old_price' => $p->sale_price ? $p->price : null,
                    'thumbnail' => $p->thumbnail
                        ? asset('storage/'.$p->thumbnail)
                        : null,
                    'url' => route('products.show', $p->slug),
                    'brand' => $p->brand->name ?? '',
                    'rating' => $p->rating_avg,
                ])
                ->toArray();
        }

        return response()->json([
            'success' => true,
            'products' => $products,
            'reason' => $result['reason'] ?? '',
        ]);
    }

    // ── SMART SEARCH ──
    public function smartSearch(Request $request)
    {
        $request->validate(['q' => 'required|string|max:200']);

        $params = $this->gemini->enhanceSearch($request->q);

        // Build query
        $query = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true);

        // Áp dụng các filter từ AI
        if (! empty($params['brand'])) {
            $query->whereHas('brand', fn ($q) => $q->where('name', 'LIKE', '%'.$params['brand'].'%'));
        }

        if (! empty($params['category'])) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $params['category']));
        }

        if (! empty($params['price_max'])) {
            $query->where(fn ($q) => $q->where('sale_price', '<=', $params['price_max'])
                ->orWhere(fn ($q2) => $q2->whereNull('sale_price')
                    ->where('price', '<=', $params['price_max'])
                )
            );
        }

        // Tìm theo keywords
        if (! empty($params['keywords'])) {
            $query->where(function ($q) use ($params) {
                foreach ($params['keywords'] as $kw) {
                    $q->orWhere('name', 'LIKE', "%{$kw}%")
                        ->orWhere('description', 'LIKE', "%{$kw}%");
                }
            });
        }

        // Features
        if (! empty($params['features'])) {
            $query->where(function ($q) use ($params) {
                foreach ($params['features'] as $feature) {
                    $q->orWhere('description', 'LIKE', "%{$feature}%")
                        ->orWhere('water_resistance', 'LIKE', "%{$feature}%")
                        ->orWhere('movement', 'LIKE', "%{$feature}%");
                }
            });
        }

        $products = $query->latest()->paginate(12);

        return view('customer.products.smart-search', compact('products', 'params'));
    }
}
