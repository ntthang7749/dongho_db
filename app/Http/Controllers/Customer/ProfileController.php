<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $orders = $user->orders()->latest()->take(5)->get();

        return view('customer.profile.index', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'avatar.image' => 'File phải là hình ảnh.',
            'avatar.max' => 'Ảnh không quá 2MB.',
        ]);

        $data = $request->only(['name', 'phone', 'address']);

        // Upload avatar mới
        if ($request->hasFile('avatar')) {
            // Xoá ảnh cũ nếu không phải URL Google
            if ($user->avatar && ! str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')
                ->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return back()->with('success', '✅ Cập nhật thông tin thành công!');
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', '❌ Mật khẩu hiện tại không đúng!');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', '✅ Đổi mật khẩu thành công!');
    }
}
