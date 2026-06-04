<?php

namespace App\Http\Controllers;

use App\Mail\ProductApprovedMail;
use App\Mail\ProductRejectedMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Product::class);

        $productsQuery = Product::with('category', 'images')->latest();

        if (! Auth::user()->isAdmin()) {
            $productsQuery->where('user_id', Auth::id());
        }

        $products = $productsQuery->get();

        return view('products.index', compact('products'));
    }

    public function dashboard()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $totalUsers = User::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalProducts = Product::count();
        $pendingProducts = Product::where('status', 'pending')->count();
        $totalOrders = Order::count();
        $openTickets = Ticket::whereIn('status', ['open', 'pending'])->count();

        $recentOrders = Order::latest()
            ->take(5)
            ->get();

        $topProducts = OrderItem::select(
            'product_name',
            DB::raw('SUM(quantity) as sold_count'),
            DB::raw('SUM(line_total) as revenue')
        )
            ->groupBy('product_name')
            ->orderByDesc('sold_count')
            ->take(5)
            ->get();

        $orderStatusCounts = [
            'Completed' => Order::whereIn('status', ['confirmed', 'completed'])->count(),
            'Processing' => Order::where('status', 'processing')->count(),
            'Pending' => Order::where('status', 'pending')->count(),
            'Cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $salesSeries = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);

            return [
                'label' => $date->format('d M'),
                'total' => (float) Order::whereDate('created_at', $date)->sum('total'),
            ];
        });

        $totalRevenue = (float) Order::sum('total');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSellers',
            'totalProducts',
            'pendingProducts',
            'totalOrders',
            'openTickets',
            'recentOrders',
            'topProducts',
            'orderStatusCounts',
            'salesSeries',
            'totalRevenue',
        ));
    }

    public function sellerDashboard()
    {
        $totalUsers = User::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalProducts = Product::count();
        $pendingProducts = Product::where('status', 'pending')->count();

        return view('admin.sellerdashboard', compact(
            'totalUsers',
            'totalSellers',
            'totalProducts',
            'pendingProducts'
        ));
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        $categories = $this->availableCategoriesQuery()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories'));
    }

    public function submitForReview(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        abort_unless(in_array($product->status, ['draft', 'rejected'], true), 403);

        $product->update([
            'status' => 'pending',
            'admin_note' => null,
        ]);

        return back()->with('success', 'Product submitted for review');
    }

    public function pending()
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $products = Product::with('user', 'category')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('products.pending', compact('products'));
    }

    // APPROVE PRODUCT
    public function approve(Product $product)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        abort_unless($product->status === 'pending', 403);

        $product->update([
            'status' => 'active',
            'admin_note' => null,
        ]);

        try {

            Mail::to($product->user->email)
                ->send(new ProductApprovedMail($product));

        } catch (\Exception $e) {

            dd($e->getMessage());
        }

        return back()->with('success', 'Product approved successfully');
    }

    // REJECT PRODUCT
    public function reject(Request $request, Product $product)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        abort_unless($product->status === 'pending', 403);

        $request->validate([
            'note' => 'required|string',
        ]);

        $product->update([
            'status' => 'rejected',
            'admin_note' => $request->note,
        ]);

        try {

            Mail::to($product->user->email)
                ->send(new ProductRejectedMail($product));
        } catch (\Exception $e) {

            dd($e->getMessage());
        }

        return back()->with('success', 'Product rejected');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $validated = $this->validateProductRequest($request);
        $imagePath = $request->hasFile('main_image')
            ? $request->file('main_image')->store('products', 'public')
            : null;

        $product = Product::create([
            'user_id' => Auth::id(),
            'category_id' => $this->resolveCategoryId($request),
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'main_image' => $imagePath,

            'status' => 'draft',
        ]);

        $this->storeGalleryImages($request, $product);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    public function usershowproduct(Request $request)
    {
        $products = Product::where('status', 'active')
            ->with('images', 'user', 'category');

        if ($request->filled('search')) {
            $search = $request->search;

            $products->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $products->latest()->get();

        $wishlistProductIds = $this->wishlistProductIds();

        return view('welcome', compact('products', 'wishlistProductIds'));
    }

    public function categoryWiseProducts($id)
    {
        $products = Product::where('category_id', $id)
            ->where('status', 'active')
            ->with('category', 'images', 'user')
            ->latest()
            ->get();

        $wishlistProductIds = $this->wishlistProductIds();

        return view('welcome', compact(
            'products',
            'wishlistProductIds'
        ));
    }

    public function subcategoryWiseProducts($id)
    {
        $products = Product::where('category_id', $id)
            ->where('status', 'active')
            ->with('category', 'images', 'user')
            ->latest()
            ->get();

        $wishlistProductIds = $this->wishlistProductIds();

        return view('welcome', compact(
            'products',
            'wishlistProductIds'
        ));
    }

    public function productView(Product $product)
    {
        if (
            $product->status !== 'active'
            && (
                ! Auth::check()
                || (! Auth::user()->isAdmin() && $product->user_id !== Auth::id())
            )
        ) {
            abort(404);
        }

        $product->load('category', 'reviews.user', 'images', 'user');

        $wishlistProductIds = $this->wishlistProductIds();

        // ⭐ AMAZON STYLE RELATED PRODUCTS (SAME CATEGORY)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active') // only active products
            ->latest()
            ->take(8)
            ->get();

        return view('products.public_show', compact(
            'product',
            'wishlistProductIds',
            'relatedProducts'
        ));
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        $product->load('category', 'images', 'user');

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $product->load('category', 'images');

        $categories = $this->availableCategoriesQuery()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('name')
            ->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $this->validateProductRequest($request, $product);
        $imagePath = $product->main_image;

        if ($request->hasFile('main_image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('main_image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $this->resolveCategoryId($request),
            'sku' => $validated['sku'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'main_image' => $imagePath,
            'status' => $product->status === 'rejected' ? 'draft' : $product->status,
            'admin_note' => $product->status === 'rejected' ? null : $product->admin_note,
        ]);

        $this->storeGalleryImages($request, $product);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->load('images');

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function destroyImage(ProductImage $image)
    {
        $product = $image->product;

        if (! $product) {
            abort(404);
        }

        $this->authorize('update', $product);
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Product image removed successfully');
    }

    private function validateProductRequest(Request $request, ?Product $product = null): array
    {
        $remainingSlots = max(0, 4 - ($product?->images()->count() ?? 0));

        return $request->validate([
            'category_id' => 'nullable|integer',
            'subcategory_id' => 'nullable|integer',
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'main_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images' => ['nullable', 'array', 'max:'.$remainingSlots],
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'images.max' => $remainingSlots === 0
                ? 'This product already has the maximum number of gallery images.'
                : 'You can upload only '.$remainingSlots.' more gallery image(s).',
        ]);
    }

    private function availableCategoriesQuery()
    {
        $query = Category::query();

        if (Category::supportsOwnership() && ! Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    private function resolveCategoryId(Request $request): int
    {
        $categoryId = $request->integer('subcategory_id') ?: $request->integer('category_id');

        if (! $categoryId) {
            throw ValidationException::withMessages([
                'category_id' => 'Please select a category.',
            ]);
        }

        $category = $this->availableCategoriesQuery()
            ->whereKey($categoryId)
            ->first();

        if (! $category) {
            throw ValidationException::withMessages([
                'category_id' => 'Please select a valid category.',
            ]);
        }

        return $category->id;
    }

    private function storeGalleryImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        foreach ($request->file('images') as $image) {
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $image->store('products', 'public'),
            ]);
        }
    }

    private function wishlistProductIds(): array
    {
        if (! Auth::check() || ! Auth::user()->isCustomer()) {
            return [];
        }

        return Wishlist::where('user_id', Auth::id())
            ->pluck('product_id')
            ->all();
    }
}
