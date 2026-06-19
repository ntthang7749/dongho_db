<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Ghi lại nhật ký hoạt động hệ thống
     *
     * @param string $action Hành động thực hiện (ví dụ: 'Thêm mới', 'Cập nhật', 'Xóa')
     * @param string $targetType Loại đối tượng (ví dụ: 'Sản phẩm', 'Đơn hàng')
     * @param int|null $targetId ID đối tượng cụ thể
     * @param string $description Mô tả chi tiết
     * @return ActivityLog
     */
    public static function log($action, $targetType, $targetId = null, $description = '')
    {
        return ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
