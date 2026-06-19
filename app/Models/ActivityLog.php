<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'description',
        'ip_address',
        'user_agent',
    ];

    // Quan hệ với người dùng thực hiện hành động
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
