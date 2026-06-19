<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Danh sách sản phẩm + lọc
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'images'])
            ->where('is_active', true);

        // Lọc theo danh mục (slug)
        if ($request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                // Lấy cả danh mục con
                $ids = $category->children->pluck('id')->push($category->id);
                $query->whereIn('category_id', $ids);
            }
        }

        // Lọc theo thương hiệu
        if ($request->brand) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $request->brand));
        }

        // Lọc theo giá
        if ($request->price_min) {
            $query->where(function ($q) use ($request) {
                $q->whereNotNull('sale_price')
                    ->where('sale_price', '>=', $request->price_min)
                    ->orWhere(function ($q2) use ($request) {
                        $q2->whereNull('sale_price')
                            ->where('price', '>=', $request->price_min);
                    });
            });
        }
        if ($request->price_max) {
            $query->where(function ($q) use ($request) {
                $q->whereNotNull('sale_price')
                    ->where('sale_price', '<=', $request->price_max)
                    ->orWhere(function ($q2) use ($request) {
                        $q2->whereNull('sale_price')
                            ->where('price', '<=', $request->price_max);
                    });
            });
        }

        // Sản phẩm nổi bật
        if ($request->featured) {
            $query->where('is_featured', true);
        }

        // Sắp xếp
        match ($request->sort ?? 'newest') {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'popular' => $query->orderByDesc('view_count'),
            'rating' => $query->orderByDesc('rating_avg'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->whereNull('parent_id')
            ->with('children')->orderBy('sort_order')->get();
        $brands = Brand::where('is_active', true)->get();

        // Khoảng giá
        $priceRange = [
            'min' => Product::where('is_active', true)->min('price'),
            'max' => Product::where('is_active', true)->max('price'),
        ];

        return view('customer.products.index', compact(
            'products', 'categories', 'brands', 'priceRange'
        ));
    }

    // Chi tiết sản phẩm
    public function show(string $slug)
    {
        $product = Product::with([
            'brand', 'category', 'images',
            'reviews' => fn ($q) => $q->where('status', 'approved')
                ->with('user')->latest(),
        ])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        // Tăng lượt xem
        $product->increment('view_count');

        // ── LƯU LỊCH SỬ XEM VÀO SESSION (cho AI gợi ý) ──
        $viewed = session('viewed_products', []);
        if (! in_array($product->id, $viewed)) {
            array_unshift($viewed, $product->id);  // Thêm vào đầu
            $viewed = array_slice($viewed, 0, 10); // Giữ tối đa 10 sản phẩm
            session(['viewed_products' => $viewed]);
        }

        // Phần còn lại giữ nguyên...
        $related = Product::with(['brand', 'images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)->get();

        $canReview = false;
        $hasReview = false;

        if (auth()->check()) {
            $hasBought = auth()->user()->orders()
                ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
                ->where('status', 'delivered')->exists();

            $hasReview = $product->reviews()
                ->where('user_id', auth()->id())->exists();

            $canReview = $hasBought && ! $hasReview;
        }

        return view('customer.products.show', compact(
            'product', 'related', 'canReview', 'hasReview'
        ));
    }

    // Tìm kiếm
    public function search(Request $request)
    {
        $q = $request->get('q', '');

        $products = Product::with(['brand', 'images'])
            ->where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%")
                    ->orWhereHas('brand', fn ($b) => $b->where('name', 'LIKE', "%{$q}%"))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'LIKE', "%{$q}%"));
            })
            ->latest()
            ->paginate(12)->withQueryString();

        return view('customer.products.search', compact('products', 'q'));
    }

    // So sánh sản phẩm
    public function compare()
    {
        $compareIds = session('compare_products', []);

        $products = Product::with(['brand', 'category', 'images'])
            ->whereIn('id', $compareIds)
            ->where('is_active', true)
            ->get();

        return view('customer.products.compare', compact('products'));
    }

    // Thêm sản phẩm vào danh sách so sánh
    public function addToCompare(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::where('id', $productId)->where('is_active', true)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã bị ẩn!'
            ], 404);
        }

        $compareProducts = session('compare_products', []);

        if (in_array($productId, $compareProducts)) {
            return response()->json([
                'success' => false,
                'status' => 'info',
                'message' => 'Sản phẩm đã có trong danh sách so sánh!'
            ]);
        }

        if (count($compareProducts) >= 3) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Bạn chỉ có thể so sánh tối đa 3 sản phẩm!'
            ]);
        }

        $compareProducts[] = $productId;
        session(['compare_products' => $compareProducts]);

        // Trả về danh sách chi tiết các sản phẩm đang so sánh để AJAX render floating bar
        $productsInCompare = $this->getCompareProductsList($compareProducts);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào danh sách so sánh thành công!',
            'products' => $productsInCompare,
            'count' => count($compareProducts)
        ]);
    }

    // Xóa sản phẩm khỏi danh sách so sánh
    public function removeFromCompare(Request $request)
    {
        $productId = $request->input('product_id');
        $compareProducts = session('compare_products', []);

        if (($key = array_search($productId, $compareProducts)) !== false) {
            unset($compareProducts[$key]);
            // Re-index array
            $compareProducts = array_values($compareProducts);
            session(['compare_products' => $compareProducts]);
        }

        $productsInCompare = $this->getCompareProductsList($compareProducts);

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi danh sách so sánh!',
            'products' => $productsInCompare,
            'count' => count($compareProducts)
        ]);
    }

    // Xóa toàn bộ danh sách so sánh
    public function clearCompare()
    {
        session()->forget('compare_products');

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ danh sách so sánh!',
            'products' => [],
            'count' => 0
        ]);
    }

    // Gợi ý sản phẩm - 8 sản phẩm bán chạy nhất
    public function suggestions()
    {
        $products = Product::with(['brand', 'images', 'reviews'])
            ->where('is_active', true)
            ->withSum('orderItems as sold_count', 'quantity')
            ->orderByRaw('COALESCE(sold_count, 0) DESC')
            ->orderByDesc('view_count')
            ->orderByDesc('rating_avg')
            ->take(8)
            ->get();

        return view('customer.products.suggestions', compact('products'));
    }

    // Helper: Lấy danh sách sản phẩm so sánh định dạng cho AJAX
    private function getCompareProductsList(array $compareIds)
    {
        return Product::whereIn('id', $compareIds)
            ->where('is_active', true)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'thumbnail' => $p->thumbnail ? asset('storage/' . $p->thumbnail) : asset('images/no-image.png'),
                    'url' => route('products.show', $p->slug),
                ];
            });
    }
}
