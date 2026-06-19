<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category_id', 'brand_id', 'description',
        'price', 'sale_price', 'stock', 'thumbnail', 'sku',
        'material', 'glass_material', 'band_material',
        'water_resistance', 'movement', 'case_size', 'color',
        'is_active', 'is_featured', 'view_count',
        'ai_description', 'rating_avg', 'rating_count',
    ];

    // Lấy giá hiển thị (ưu tiên giá sale)
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    // Quan hệ
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
