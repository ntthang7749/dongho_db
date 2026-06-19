<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['orders', 'reviews'])->latest();

        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('email', 'LIKE', "%{$request->search}%")
                ->orWhere('username', 'LIKE', "%{$request->search}%");
        }
        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Khoá / mở khoá tài khoản
    public function toggleActive(int $id)
    {
        $user = User::findOrFail($id);

        // Không cho khoá chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể khoá tài khoản của chính bạn!');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $statusStr = $user->is_active ? 'Mở khóa' : 'Khóa';
        ActivityLogger::log($statusStr, 'Người dùng', $user->id, 'Đã ' . mb_strtolower($statusStr) . ' tài khoản người dùng: "' . $user->name . '" (Email: ' . $user->email . ')');

        $msg = $user->is_active ? 'Đã mở khoá tài khoản!' : 'Đã khoá tài khoản!';


        return back()->with('success', $msg);
    }

    // Đổi role
    public function changeRole(Request $request, int $id)
    {
        $request->validate(['role' => 'required|in:customer,admin']);

        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể đổi role của chính bạn!');
        }

        $user->update(['role' => $request->role]);

        ActivityLogger::log('Cập nhật quyền', 'Người dùng', $user->id, 'Đã thay đổi vai trò người dùng "' . $user->name . '" (Email: ' . $user->email . ') thành: ' . ($user->role === 'admin' ? 'Quản trị viên (admin)' : 'Khách hàng (customer)'));


        return back()->with('success', '✅ Đã cập nhật quyền!');
    }
}
