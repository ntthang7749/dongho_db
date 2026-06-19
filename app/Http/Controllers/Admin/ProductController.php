<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ActivityLogger;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])->latest();

        // Tìm kiếm
        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('sku', 'LIKE', "%{$request->search}%");
        }

        // Lọc danh mục
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc sắp hết hàng
        if ($request->filter === 'low_stock') {
            $query->where('stock', '<=', 5);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)
            ->with('children')->whereNull('parent_id')->get();
        $brands = Brand::where('is_active', true)->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:25600',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:25600',
        ], [
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
        ]);

        $data = $request->except(['thumbnail', 'images', '_token']);
        $data['slug'] = Str::slug($request->name).'-'.Str::random(5);

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('products/thumbnails', 'public');
        }

        $product = Product::create($data);

        // Ghi log hoạt động
        ActivityLogger::log('Thêm mới', 'Sản phẩm', $product->id, 'Đã thêm mới sản phẩm: "' . $product->name . '" (SKU: ' . $product->sku . ', Giá: ' . number_format($product->price) . 'đ)');


        // Upload nhiều ảnh gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                $path = $img->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $i,
                ]);
            }
        }

        if ($request->export_pdf == '1') {
            session()->flash('download_invoice_id', $product->id);
        }

        return redirect()->route('admin.products.index')
            ->with('success', "✅ Đã thêm sản phẩm \"{$product->name}\"!");
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)
            ->with('children')->whereNull('parent_id')->get();
        $brands = Brand::where('is_active', true)->get();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:25600',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:25600',
        ]);

        $data = $request->except(['thumbnail', 'images', '_token', '_method']);

        // Upload thumbnail mới
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('products/thumbnails', 'public');
        }

        $product->update($data);

        // Ghi log hoạt động
        ActivityLogger::log('Cập nhật', 'Sản phẩm', $product->id, 'Đã cập nhật sản phẩm: "' . $product->name . '" (SKU: ' . $product->sku . ')');

        // Upload ảnh gallery thêm mới
        if ($request->hasFile('images')) {
            $maxOrder = $product->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $i => $img) {
                $path = $img->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $maxOrder + $i + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "✅ Đã cập nhật sản phẩm \"{$product->name}\"!");
    }

    public function destroy(Product $product)
    {
        // Xoá ảnh
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image);
        }
        // Ghi log hoạt động
        ActivityLogger::log('Xóa', 'Sản phẩm', $product->id, 'Đã xóa sản phẩm: "' . $product->name . '" (SKU: ' . $product->sku . ')');

        $product->delete();

        return back()->with('success', '✅ Đã xoá sản phẩm!');
    }

    public function invoice($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);

        $image_base64 = null;
        if ($product->thumbnail) {
            $path = Storage::disk('public')->path($product->thumbnail);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = @file_get_contents($path);
                if ($data !== false) {
                    $image_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }
        }

        $pdf = Pdf::loadView('admin.products.invoice', compact('product', 'image_base64'));
        
        return $pdf->stream('HoaDonNhapHang_' . ($product->sku ?? 'SP') . '_' . $product->id . '.pdf');
    }
}
