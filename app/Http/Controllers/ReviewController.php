<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string',
    ]);

    $review = Review::where('user_id', auth()->id())
                    ->where('product_id', $product->id)
                    ->first();

    if ($review) {
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review updated successfully');
    }

    Review::create([
        'user_id' => auth()->id(),
        'product_id' => $product->id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return back()->with('success', 'Review added successfully');
}

    public function edit(Review $review)
    {
        if ($review->user_id != auth()->id()) {
            abort(403);
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id != auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully');
    }
}
