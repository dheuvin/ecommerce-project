<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'main_image',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->oldestOfMany();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // public function getTotalStockAttribute()
    // {
    //     return $this->variants->sum('stock');
    // }

    public function getPrimaryImagePathAttribute(): ?string
    {
        if ($this->main_image) {
            return $this->main_image;
        }

        if ($this->relationLoaded('images')) {
            return $this->images->first()?->image;
        }

        return $this->images()->value('image');
    }
}
