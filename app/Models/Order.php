<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_number',
        'status',
        'payment_method',
        'payment_status',
        'subtotal',
        'discount_total',
        'total',
        'customer_name',
        'customer_email',
        'customer_phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'notes',
        'invoice_path',
        'invoice_generated_at',
        'placed_at',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'is_returned',
        'return_requested_at',
        'return_approved_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        'invoice_generated_at' => 'datetime',
        'placed_at' => 'datetime',
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
