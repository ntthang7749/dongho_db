<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Lọc theo thao tác (action)
        if ($request->action) {
            $query->where('action', $request->action);
        }

        // Lọc theo loại đối tượng (target_type)
        if ($request->target_type) {
            $query->where('target_type', $request->target_type);
        }

        // Lọc theo người dùng (user_id)
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Tìm kiếm theo nội dung mô tả
        if ($request->search) {
            $query->where('description', 'LIKE', "%{$request->search}%");
        }

        $logs = $query->paginate(20)->withQueryString();

        // Thu thập danh sách duy nhất để điền vào bộ lọc
        $actions = ActivityLog::distinct()->pluck('action');
        $targetTypes = ActivityLog::distinct()->whereNotNull('target_type')->pluck('target_type');
        $usersWithLogs = User::whereHas('activityLogs')->get(); // Chúng ta sẽ định nghĩa quan hệ activityLogs trên User

        return view('admin.activity_logs.index', compact('logs', 'actions', 'targetTypes', 'usersWithLogs'));
    }
}
