<?php

use App\Http\Controllers\Admin\TicketCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'usershowproduct'])
    ->name('shop.index');

Route::get('/category/{id}', [ProductController::class, 'categoryWiseProducts'])
    ->name('category.products');

Route::get('/subcategory/{id}', [ProductController::class, 'subcategoryWiseProducts'])
    ->name('subcategory.products');

Route::get('/product/{product}', [ProductController::class, 'productView'])
    ->name('product.view');

Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{blog}', [BlogController::class, 'show'])
    ->name('blog.show');

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer,user'])->group(function () {

    Route::get('tickets', [TicketController::class, 'index'])
        ->name('tickets.index');

    Route::get('tickets/create', [TicketController::class, 'create'])
        ->name('tickets.create');

    Route::post('tickets', [TicketController::class, 'store'])
        ->name('tickets.store');

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('/cart/add/{product}', [CartController::class, 'add'])
        ->name('cart.add');

    Route::post('/cart/update/{item}', [CartController::class, 'update'])
        ->name('cart.update');

    Route::post('/cart/remove/{item}', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::post('/cart/clear', [CartController::class, 'clear'])
        ->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout.store');

    Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])
        ->name('checkout.coupon.apply');

    Route::post('/checkout/coupon/remove', [CheckoutController::class, 'removeCoupon'])
        ->name('checkout.coupon.remove');

    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('wishlist.index');

    Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])
        ->name('wishlist.toggle');
});

/*
|--------------------------------------------------------------------------
| SELLER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/admin/sellerdashboard', [ProductController::class, 'sellerDashboard'])
    ->name('seller.dashboard');

});

/*
|--------------------------------------------------------------------------
| user / ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer,admin'])->group(function () {

    Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');

    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');

    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');

    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

});

/*
|--------------------------------------------------------------------------
| SELLER / ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:seller,admin'])->group(function () {

    // ADMIN PANEL

    Route::resource('categories', CategoryController::class);

    Route::resource('products', ProductController::class);

    Route::post('/products/{product}/submit', [ProductController::class, 'submitForReview'])
        ->name('seller.products.submit');

    Route::delete('/product-images/{image}', [ProductController::class, 'destroyImage'])
        ->name('products.images.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('ticket-categories', TicketCategoryController::class);

    Route::get('/admin/products/pending', [ProductController::class, 'pending'])
        ->name('products.pending');

    Route::post('/products/{product}/approve', [ProductController::class, 'approve'])
        ->name('products.approve');

    Route::post('/products/{product}/reject', [ProductController::class, 'reject'])
        ->name('products.reject');

    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->name('admin.tickets.status.update');

    Route::patch('/tickets/{ticket}/close', [TicketController::class, 'close'])
        ->name('tickets.close');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])
        ->name('tickets.destroy');

    Route::get('admin/tickets', [TicketController::class, 'adminIndex'])->name('admin.tickets');



    Route::get('/admin/dashboard', [ProductController::class, 'dashboard'])
    ->name('admin.dashboard');

    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.user');

    Route::get('/blog/create', [BlogController::class, 'create'])
        ->name('blog.create');

    Route::post('/blog', [BlogController::class, 'store'])
        ->name('blog.store');

    Route::get('/blog/{blog}/edit', [BlogController::class, 'edit'])
        ->name('blog.edit');

    Route::put('/blog/{blog}', [BlogController::class, 'update'])
        ->name('blog.update');

    Route::delete('/blog/{blog}', [BlogController::class, 'destroy'])
        ->name('blog.destroy');

    Route::post('/admin/users/{id}/role', [UserController::class, 'updateRole']);

    Route::resource('coupons', CouponController::class)
        ->except('show');
});

/*
|--------------------------------------------------------------------------
| COMMON AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])
        ->name('orders.invoice');

    Route::get('/orders/{order}/invoice/download', [OrderController::class, 'downloadInvoice'])
        ->name('orders.invoice.download');

    Route::get('tickets/{ticket}', [TicketController::class, 'show'])
        ->name('tickets.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
