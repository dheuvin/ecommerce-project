<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Support\CartTotals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = $this->preparedCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Add products to your cart before checkout.');
        }

        $coupon = $this->sessionCoupon($cart);
        $summary = CartTotals::calculate($cart, $coupon);

        return view('checkout.index', compact('cart', 'coupon', 'summary'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $cart = $this->preparedCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $summary = CartTotals::calculate($cart);
        $coupon = Coupon::whereRaw('LOWER(code) = ?', [Str::lower($request->string('code'))])->first();

        if (! $coupon || ! $coupon->isAvailableFor($summary['subtotal'])) {
            throw ValidationException::withMessages([
                'code' => 'This coupon is invalid or not available for your cart.',
            ]);
        }

        session(['checkout.coupon_code' => $coupon->code]);

        return back()->with('success', 'Coupon applied successfully.');
    }

    public function removeCoupon()
    {
        session()->forget('checkout.coupon_code');

        return back()->with('success', 'Coupon removed.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'required|string|max:120',
            'postal_code' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash_on_delivery',
        ]);

        $cart = $this->preparedCart();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $couponCode = session('checkout.coupon_code');

        $order = DB::transaction(function () use ($cart, $couponCode, $validated) {
            $cart->load('items.product');

            $products = Product::whereIn('id', $cart->items->pluck('product_id')->filter()->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cart->items as $item) {
                $product = $products->get($item->product_id);

                if (! $product || ! $product->status || $product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'cart' => 'One or more products no longer have enough stock.',
                    ]);
                }

                if ((float) $item->price !== (float) $product->price) {
                    $item->update(['price' => $product->price]);
                }
            }

            $coupon = null;

            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->lockForUpdate()->first();
            }

            $summary = CartTotals::calculate($cart->fresh('items.product'), $coupon);

            if ($summary['items_count'] < 1) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart is empty.',
                ]);
            }

            if ($coupon && ! $coupon->isAvailableFor($summary['subtotal'])) {
                throw ValidationException::withMessages([
                    'code' => 'The selected coupon is no longer valid.',
                ]);
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'coupon_id' => $coupon?->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'confirmed',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'subtotal' => $summary['subtotal'],
                'discount_total' => $summary['discount_total'],
                'total' => $summary['total'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'notes' => $validated['notes'] ?? null,
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $product = $products->get($item->product_id);
                $commissionPercent =
                PlatformSetting::first()->commission_percent;

                $lineTotal =
                    $product->price * $item->quantity;

                $platformCommission =
                    ($lineTotal * $commissionPercent) / 100;

                $sellerEarning =
                    $lineTotal - $platformCommission;

                OrderItem::create([

                    'order_id' => $order->id,

                    'product_id' => $product->id,

                    'seller_id' => $product->user_id,

                    'product_name' => $product->name,

                    'product_sku' => $product->sku,

                    'price' => $product->price,

                    'quantity' => $item->quantity,

                    'line_total' => $lineTotal,

                    'platform_commission_percent' => $commissionPercent,

                    'platform_commission_amount' => $platformCommission,

                    'seller_earning' => $sellerEarning,

                ]);

                $product->decrement('stock', $item->quantity);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            $cart->items()->delete();

            return $order;
        });

        session()->forget('checkout.coupon_code');
        OrderPlaced::dispatch($order);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully.');
    }

    private function preparedCart(): Cart
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cart->load('items.product.images');

        foreach ($cart->items as $item) {
            if (! $item->product || ! $item->product->status || $item->product->stock < 1) {
                $item->delete();

                continue;
            }

            $updates = [];

            if ($item->quantity > $item->product->stock) {
                $updates['quantity'] = $item->product->stock;
            }

            if ((float) $item->price !== (float) $item->product->price) {
                $updates['price'] = $item->product->price;
            }

            if ($updates !== []) {
                $item->update($updates);
            }
        }

        return $cart->fresh('items.product.images');
    }

    private function sessionCoupon(Cart $cart): ?Coupon
    {
        $couponCode = session('checkout.coupon_code');

        if (! $couponCode) {
            return null;
        }

        $coupon = Coupon::where('code', $couponCode)->first();
        $summary = CartTotals::calculate($cart);

        if (! $coupon || ! $coupon->isAvailableFor($summary['subtotal'])) {
            session()->forget('checkout.coupon_code');

            return null;
        }

        return $coupon;
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-'.Str::upper(Str::random(10));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
