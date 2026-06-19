<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Banners slider
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')->get();

        // Sản phẩm nổi bật (is_featured = true)
        $featuredProducts = Product::with(['brand', 'images'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()->take(8)->get();

        // Sản phẩm mới nhất
        $newProducts = Product::with(['brand', 'images'])
            ->where('is_active', true)
            ->latest()->take(8)->get();

        // Sản phẩm bán chạy (nhiều order_items nhất)
        $bestSellers = Product::with(['brand', 'images'])
            ->withCount('orderItems')
            ->where('is_active', true)
            ->orderByDesc('order_items_count')
            ->take(8)->get();

        // Danh mục chính
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('sort_order')->get();

        // Thương hiệu
        $brands = Brand::where('is_active', true)->get();

        return view('customer.home', compact(
            'banners', 'featuredProducts', 'newProducts',
            'bestSellers', 'categories', 'brands'
        ));
    }
}
