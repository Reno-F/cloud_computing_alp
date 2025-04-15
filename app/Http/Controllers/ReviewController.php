<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = new Review();
        $review->product_id = $product->id;
        $review->user_id = Auth::id();
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();

        return redirect()->route('catalog-detail', $product->id)->with('success', 'Review added successfully!');
    }
    public function edit(Review $review)
{
    if (Auth::id() !== $review->user_id) {
        abort(403); // Forbidden
    }

    return view('reviews.edit', compact('review'));
}
public function update(Request $request, Review $review)
{
    if (Auth::id() !== $review->user_id) {
        abort(403);
    }

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
    ]);

    $review->update([
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return redirect()->route('catalog-detail', $review->product_id)->with('success', 'Review updated!');
}
public function destroy(Review $review)
{
    if (Auth::id() !== $review->user_id) {
        abort(403);
    }

    $review->delete();

    return redirect()->route('catalog-detail', $review->product_id)->with('success', 'Review deleted!');
}


}
