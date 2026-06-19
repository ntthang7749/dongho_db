<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'phone',
        'subject', 'type', 'message',
        'status', 'admin_reply', 'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /* ── Relations ── */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ── Helpers ── */
    public function typeLabel(): string
    {
        return match($this->type) {
            'feedback'  => 'Phản hồi / Góp ý',
            'report'    => 'Báo cáo vấn đề',
            'question'  => 'Câu hỏi',
            'other'     => 'Khác',
            default     => ucfirst($this->type),
        };
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'new'     => 'Chưa đọc',
            'read'    => 'Đã đọc',
            'replied' => 'Đã phản hồi',
            'closed'  => 'Đã đóng',
            default   => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'new'     => 'danger',
            'read'    => 'warning',
            'replied' => 'success',
            'closed'  => 'secondary',
            default   => 'secondary',
        };
    }
}
