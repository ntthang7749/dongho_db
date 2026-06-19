<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\ActivityLogger;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'children'])
            ->withCount('products')
            ->whereNull('parent_id')
            ->orderBy('sort_order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')
            ->where('is_active', true)->get();

        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|max:25600',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $data = $request->except(['image', '_token']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('categories', 'public');
        }

        $category = Category::create($data);

        ActivityLogger::log('Thêm mới', 'Danh mục', $category->id, 'Đã thêm mới danh mục sản phẩm: "' . $category->name . '"');


        return redirect()->route('admin.categories.index')
            ->with('success', '✅ Đã thêm danh mục!');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->where('is_active', true)->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|max:25600',
        ]);

        $data = $request->except(['image', '_token', '_method', 'delete_image']);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')
                ->store('categories', 'public');
        } elseif ($request->boolean('delete_image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = null;
        }

        $category->update($data);

        ActivityLogger::log('Cập nhật', 'Danh mục', $category->id, 'Đã cập nhật thông tin danh mục sản phẩm: "' . $category->name . '"');


        return redirect()->route('admin.categories.index')
            ->with('success', '✅ Đã cập nhật danh mục!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error',
                '❌ Không thể xoá vì còn sản phẩm thuộc danh mục này!');
        }
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        ActivityLogger::log('Xóa', 'Danh mục', $category->id, 'Đã xóa danh mục sản phẩm: "' . $category->name . '"');

        $category->delete();

        return back()->with('success', '✅ Đã xoá danh mục!');
    }
}
