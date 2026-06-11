<?php

namespace App\Support;

use App\Models\Cart;
use App\Models\Coupon;

class CartTotals
{
    public static function calculate(Cart $cart, ?Coupon $coupon = null): array
    {
        $items = $cart->items->filter(function ($item) {
            return $item->product && $item->product->status === 'active';
        });

        $itemsCount = (int) $items->sum('quantity');
        $subtotal = round((float) $items->sum(function ($item) {
            return (float) $item->price * $item->quantity;
        }), 2);

        $discount = $coupon ? $coupon->discountFor($subtotal) : 0.0;
        $tax = 0.0;
        $total = round(max($subtotal - $discount + $tax, 0), 2);

        return [
            'items_count' => $itemsCount,
            'subtotal' => $subtotal,
            'discount_total' => $discount,
            'tax_total' => $tax,
            'total' => $total,
            'coupon' => $coupon,
            'items' => $items,
        ];
    }
}
