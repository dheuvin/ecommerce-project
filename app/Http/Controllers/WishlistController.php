<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product.images', 'product.category', 'product.user')
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request, Product $product)
    {
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
            $message = 'Removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);

            $inWishlist = true;
            $message = 'Added to wishlist.';
        }

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'product_id' => $product->id,
                'in_wishlist' => $inWishlist,
                'wishlist_count' => $wishlistCount,
            ]);
        }

        return back()->with('success', $message);
    }
}
