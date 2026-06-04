<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Support\CartTotals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function index()
    {
        $cart = $this->preparedCart();
        $summary = CartTotals::calculate($cart);

        return view('cart.index', compact('cart', 'summary'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        abort_if($product->user_id === Auth::id(), 403, 'You cannot add your own product to cart.');

        if (
            $product->status !== 'active'
            && (
                ! Auth::check()
                || (! Auth::user()->isAdmin() && $product->user_id !== Auth::id())
            )
        ) {
            abort(404);
        }

        $cart = $this->getCart();
        $item = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        $newQuantity = ($item?->quantity ?? 0) + $request->integer('quantity');

        if ($newQuantity > $product->stock) {
            return $this->respond($request, $cart, 'Only '.$product->stock.' item(s) available.', 'error', 422);
        }

        if ($item) {
            $item->update([
                'quantity' => $newQuantity,
                'price' => $product->price,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->integer('quantity'),
                'price' => $product->price,
            ]);
        }

        return $this->respond($request, $this->preparedCart(), 'Added to cart');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        abort_unless($item->cart?->user_id === Auth::id(), 404);

        $item->loadMissing('product');

        if (! $item->product || $item->product->status !== 'active') {
            $item->delete();

            return $this->respond($request, $this->preparedCart(), 'This product is no longer available.', 'error', 422);
        }

        if ($request->integer('quantity') > $item->product->stock) {
            return $this->respond(
                $request,
                $this->preparedCart(),
                'Only '.$item->product->stock.' item(s) available.',
                'error',
                422
            );
        }

        $item->update([
            'quantity' => $request->integer('quantity'),
            'price' => $item->product->price,
        ]);

        return $this->respond($request, $this->preparedCart(), 'Cart updated');
    }

    public function remove(Request $request, CartItem $item)
    {
        abort_unless($item->cart?->user_id === Auth::id(), 404);

        $item->delete();

        return $this->respond($request, $this->preparedCart(), 'Item removed');
    }

    public function clear(Request $request)
    {
        $cart = $this->getCart();
        $cart->items()->delete();

        return $this->respond($request, $this->preparedCart(), 'Cart cleared');
    }

    private function preparedCart(): Cart
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.product.images')
            ->first();

        if (! $cart) {
            $cart = new Cart;
            $cart->setRelation('items', collect());

            return $cart;
        }

        foreach ($cart->items as $item) {
            if (! $item->product || $item->product->status !== 'active' || $item->product->stock < 1) {
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

        return Cart::where('user_id', Auth::id())
            ->with('items.product.images')
            ->first() ?? tap(new Cart, function ($cart) {
                $cart->setRelation('items', collect());
            });
    }

    private function respond(
        Request $request,
        Cart $cart,
        string $message,
        string $status = 'success',
        int $code = 200
    ) {
        if ($request->expectsJson() || $request->ajax()) {
            $summary = CartTotals::calculate($cart);

            return response()->json([
                'status' => $status,
                'message' => $message,
                'cart_count' => $summary['items_count'],
                'summary' => [
                    'items_count' => $summary['items_count'],
                    'subtotal' => $summary['subtotal'],
                    'discount_total' => $summary['discount_total'],
                    'total' => $summary['total'],
                ],
                'html' => view('cart.partials.content', compact('cart', 'summary'))->render(),
            ], $code);
        }

        return back()->with($status, $message);
    }
}
