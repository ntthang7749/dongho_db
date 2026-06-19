<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order', 'max_discount',
        'max_usage', 'usage_count', 'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = ['starts_at' => 'datetime', 'expires_at' => 'datetime'];

    // Kiểm tra coupon còn hiệu lực không
    public function isValid(): bool
    {
        return $this->is_active
            && $this->usage_count < $this->max_usage
            && (! $this->expires_at || $this->expires_at->isFuture());
    }
}
