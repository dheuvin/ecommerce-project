<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Category::class);

        $categories = $this->scopedCategoriesQuery()
            ->with('parent')
            ->latest()
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', Category::class);

        $categories = $this->parentCategoriesQuery()
            ->orderBy('name')
            ->get();

        return view('categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')],
            'parent_id' => 'nullable|integer',
            'status' => 'required|in:0,1',
        ]);

        Category::create([
            'name' => $validated['name'],
            'parent_id' => $this->resolveParentId($request),
                'status' => $validated['status'],
            ...(
                Category::supportsOwnership()
                    ? ['user_id' => Auth::id()]
                    : []
            ),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    public function show(Category $category)
    {
        abort(404);
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        $categories = $this->parentCategoriesQuery()
            ->whereKeyNot($category->id)
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'parent_id' => 'nullable|integer',
             'status' => 'required|in:0,1',
        ]);

        $category->update([
            'name' => $validated['name'],
            'parent_id' => $this->resolveParentId($request, $category),
            'status' => $validated['status'],
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }

    private function scopedCategoriesQuery()
    {
        $query = Category::query();

        if (Category::supportsOwnership() && ! Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    private function parentCategoriesQuery()
    {
        return $this->scopedCategoriesQuery()->whereNull('parent_id');
    }

    private function resolveParentId(Request $request, ?Category $category = null): ?int
    {
        if (! $request->filled('parent_id')) {
            return null;
        }

        $parentQuery = $this->parentCategoriesQuery()->whereKey((int) $request->parent_id);

        if ($category) {
            $parentQuery->whereKeyNot($category->id);
        }

        $parent = $parentQuery->first();

        if (! $parent) {
            throw ValidationException::withMessages([
                'parent_id' => 'Please select a valid parent category.',
            ]);
        }

        return $parent->id;
    }
}
