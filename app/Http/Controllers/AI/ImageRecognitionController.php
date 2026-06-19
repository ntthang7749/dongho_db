<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ImageRecognitionController extends Controller
{
    public function __construct(
        private GeminiService $gemini
    ) {}

    // ── TRANG NHẬN DIỆN ẢNH ──
    public function index()
    {
        return view('customer.ai.image-recognition');
    }

    // ── NHẬN DIỆN ĐỒNG HỒ TỪ ẢNH ──
    public function recognize(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'image.required' => 'Vui lòng chọn ảnh.',
            'image.image' => 'File phải là ảnh.',
            'image.max' => 'Ảnh tối đa 5MB.',
        ]);

        $imageFile = $request->file('image');
        $uploadedFilePath = $imageFile->path();

        // 1. Kiểm tra ảnh trùng khớp 100% trong kho trước (so sánh mã băm MD5) để tiết kiệm API Token
        $matchedProduct = $this->findProductByHash($uploadedFilePath);

        if ($matchedProduct) {
            $matchedProduct->load(['brand', 'category']);

            $recognition = [
                'brand' => $matchedProduct->brand->name ?? 'Không rõ',
                'type' => str_contains($matchedProduct->category->slug ?? '', 'nu') ? 'Đồng hồ Nữ' : 'Đồng hồ Nam',
                'style' => 'Đồng hồ thời trang cao cấp',
                'color' => 'Trùng khớp hình ảnh',
                'band_material' => 'Thép không gỉ / Da cao cấp',
                'estimated_size' => 'Tiêu chuẩn gốc',
                'estimated_price_min' => (int)($matchedProduct->sale_price ?? $matchedProduct->price),
                'estimated_price_max' => (int)($matchedProduct->sale_price ?? $matchedProduct->price),
                'description' => 'Tìm thấy sản phẩm "' . $matchedProduct->name . '" trùng khớp 100% với ảnh của bạn tải lên trong hệ thống.',
                'features' => ['Ảnh gốc hệ thống', '100% Chính hãng', 'Bảo hành đầy đủ'],
                'confidence' => 1.0,
                'is_exact_match' => true
            ];

            // Đặt sản phẩm trùng khớp ở đầu, sau đó lấy thêm 3 sản phẩm cùng thương hiệu làm gợi ý tương tự
            $similarProducts = [
                [
                    'id' => $matchedProduct->id,
                    'name' => $matchedProduct->name,
                    'price' => number_format($matchedProduct->sale_price ?? $matchedProduct->price).'đ',
                    'thumbnail' => $matchedProduct->thumbnail ? asset('storage/'.$matchedProduct->thumbnail) : null,
                    'url' => route('products.show', $matchedProduct->slug),
                    'brand' => $matchedProduct->brand->name ?? '',
                    'rating' => $matchedProduct->rating_avg,
                ]
            ];

            $otherProducts = Product::with(['brand'])
                ->where('is_active', true)
                ->where('id', '!=', $matchedProduct->id);

            if ($matchedProduct->brand_id) {
                $otherProducts->where('brand_id', $matchedProduct->brand_id);
            }

            $otherProducts = $otherProducts->take(3)->get()->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => number_format($p->sale_price ?? $p->price).'đ',
                'thumbnail' => $p->thumbnail ? asset('storage/'.$p->thumbnail) : null,
                'url' => route('products.show', $p->slug),
                'brand' => $p->brand->name ?? '',
                'rating' => $p->rating_avg,
            ])->toArray();

            $similarProducts = array_merge($similarProducts, $otherProducts);

            return response()->json([
                'success' => true,
                'recognition' => $recognition,
                'similar_products' => $similarProducts,
            ]);
        }

        // 2. Nếu không có ảnh trùng trong hệ thống -> Mới gọi AI nhận diện (tốn token)
        $imageData = base64_encode(file_get_contents($uploadedFilePath));
        $mimeType = $imageFile->getMimeType();

        // Gọi AI nhận diện
        $recognition = $this->gemini->recognizeWatch($imageData, $mimeType);

        // Tìm sản phẩm tương tự trong DB
        $similarProducts = $this->findSimilarProducts($recognition);

        return response()->json([
            'success' => true,
            'recognition' => $recognition,
            'similar_products' => $similarProducts,
        ]);
    }

    // ── TÌM SẢN PHẨM TƯƠNG TỰ ──
    private function findSimilarProducts(array $recognition): array
    {
        $query = Product::with(['brand'])
            ->where('is_active', true);

        // Tìm theo thương hiệu
        if (! empty($recognition['brand']) && $recognition['brand'] !== 'Không rõ') {
            $query->whereHas('brand', fn ($q) => $q->where('name', 'LIKE', '%'.$recognition['brand'].'%'));
        }

        // Tìm theo loại
        if (! empty($recognition['type'])) {
            $categoryMap = [
                'nam' => 'dong-ho-nam',
                'nữ' => 'dong-ho-nu',
                'treo tường' => 'dong-ho-treo-tuong',
            ];
            foreach ($categoryMap as $key => $slug) {
                if (str_contains(strtolower($recognition['type']), $key)) {
                    $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
                    break;
                }
            }
        }

        // Tìm theo giá ước tính
        if (! empty($recognition['estimated_price_min'])) {
            $priceMin = $recognition['estimated_price_min'] * 0.7;
            $priceMax = ($recognition['estimated_price_max'] ?? $recognition['estimated_price_min'] * 2) * 1.3;

            $query->where(fn ($q) => $q->whereBetween('price', [$priceMin, $priceMax])
                ->orWhereBetween('sale_price', [$priceMin, $priceMax])
            );
        }

        return $query->take(4)->get()->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => number_format($p->sale_price ?? $p->price).'đ',
            'thumbnail' => $p->thumbnail
                ? asset('storage/'.$p->thumbnail)
                : null,
            'url' => route('products.show', $p->slug),
            'brand' => $p->brand->name ?? '',
            'rating' => $p->rating_avg,
        ])->toArray();
    }

    // ── TÌM SẢN PHẨM BẰNG MÃ BĂM ẢNH (MD5) ──
    private function findProductByHash(string $uploadedFilePath): ?Product
    {
        $uploadedHash = md5_file($uploadedFilePath);

        // Lấy tất cả sản phẩm đang kích hoạt cùng với hình ảnh bổ sung
        $products = Product::with(['images', 'brand', 'category'])
            ->where('is_active', true)
            ->get();

        foreach ($products as $product) {
            // Kiểm tra ảnh đại diện (thumbnail)
            if ($product->thumbnail) {
                $thumbnailPaths = [
                    storage_path('app/public/' . $product->thumbnail),
                    public_path($product->thumbnail),
                    public_path('storage/' . $product->thumbnail)
                ];

                foreach ($thumbnailPaths as $path) {
                    if (file_exists($path) && is_file($path)) {
                        if (md5_file($path) === $uploadedHash) {
                            return $product;
                        }
                    }
                }
            }

            // Kiểm tra các hình ảnh bổ sung trong bảng product_images (nếu có)
            if ($product->images->count() > 0) {
                foreach ($product->images as $prodImg) {
                    if ($prodImg->image) {
                        $imagePaths = [
                            storage_path('app/public/' . $prodImg->image),
                            public_path($prodImg->image),
                            public_path('storage/' . $prodImg->image)
                        ];

                        foreach ($imagePaths as $path) {
                            if (file_exists($path) && is_file($path)) {
                                if (md5_file($path) === $uploadedHash) {
                                    return $product;
                                }
                            }
                        }
                    }
                }
            }
        }

        return null;
    }
}

