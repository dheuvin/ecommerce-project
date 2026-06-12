<?php

namespace App\Models;
use App\Models\Product;
use App\Models\Size;


use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
   protected $fillable = ['product_id', 'size', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function cartItems()
{
    return $this->hasMany(CartItem::class, 'variant_id');
}

}
