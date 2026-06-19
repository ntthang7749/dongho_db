<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'coupon_id',
        'receiver_name', 'receiver_phone', 'receiver_address',
        'subtotal', 'discount', 'total',
        'payment_method', 'payment_status', 'vnpay_transaction_id',
        'status', 'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
