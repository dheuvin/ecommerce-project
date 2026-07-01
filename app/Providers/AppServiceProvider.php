<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Wishlist;
use App\Policies\CategoryPolicy;
use App\Policies\CouponPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Coupon::class, CouponPolicy::class);

        View::composer('layouts.user', function ($view): void {
            $cartItemCount = 0;
            $wishlistCount = 0;

            if (Auth::check() && Auth::user()->isCustomer()) {
                $cartId = Auth::user()->cart?->id;

                if ($cartId) {
                    $cartItemCount = (int) Cart::query()
                        ->whereKey($cartId)
                        ->withSum('items as total_items', 'quantity')
                        ->value('total_items');
                }

                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            }

            $view->with([
                'cartItemCount' => $cartItemCount,
                'wishlistCount' => $wishlistCount,
            ]);
        });
        View::composer('layouts.user', function ($view) {

            $categories = Category::whereNull('parent_id')
                ->with('children')
                ->get();

            $view->with('categories', $categories);
        });
        User::created(function ($user) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        });

    }
}
