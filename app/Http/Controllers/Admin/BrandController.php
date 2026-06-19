<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\ActivityLogger;


class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->latest()->get();

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:brands,name',
            'logo' => 'nullable|image|max:25600',
        ]);

        $data = $request->except(['logo', '_token']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')
                ->store('brands', 'public');
        }

        $brand = Brand::create($data);

        ActivityLogger::log('Thêm mới', 'Thương hiệu', $brand->id, 'Đã thêm mới thương hiệu: "' . $brand->name . '"');


        return redirect()->route('admin.brands.index')
            ->with('success', '✅ Đã thêm thương hiệu!');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:brands,name,'.$brand->id,
            'logo' => 'nullable|image|max:25600',
        ]);

        $data = $request->except(['logo', '_token', '_method']);

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')
                ->store('brands', 'public');
        }

        $brand->update($data);

        ActivityLogger::log('Cập nhật', 'Thương hiệu', $brand->id, 'Đã cập nhật thông tin thương hiệu: "' . $brand->name . '"');


        return redirect()->route('admin.brands.index')
            ->with('success', '✅ Đã cập nhật thương hiệu!');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return back()->with('error',
                '❌ Không thể xoá vì còn sản phẩm thuộc thương hiệu này!');
        }
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }
        ActivityLogger::log('Xóa', 'Thương hiệu', $brand->id, 'Đã xóa thương hiệu: "' . $brand->name . '"');

        $brand->delete();

        return back()->with('success', '✅ Đã xoá thương hiệu!');
    }
}
