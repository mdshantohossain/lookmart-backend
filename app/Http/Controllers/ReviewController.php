<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Admin\Product;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.review.index', [
            'products' =>  Product::whereHas('reviews')
                ->with('reviews', 'reviews.user')
                ->latest()
                ->get(['id', 'sku', 'name', 'image_thumbnail']),
        ]);
    }

    public function create(): View
    {
        // check permission of request user
        isAuthorized('review create');

        return view('admin.review.create');
    }

    public function store(ReviewRequest $request, ReviewService $reviewService)
    {
        // check permission of request user
        isAuthorized('review create');

        $review = $reviewService->updateOrCreate($request->validated());

        if(!$review) {
            return back()->with('error', 'Review could not be created');
        }

        return redirect()->route('reviews.index')->with('success', 'Review created successful');
    }

    public function show(Product $product): View
    {
        // check permission of request user
        isAuthorized('review show');

        $product->load('reviews', 'reviews.user', 'reviews.images');

        return view('admin.review.detail', compact('product'));
    }

    public function edit(Product $product)
    {
        // check permission of request user
        isAuthorized('review edit');

        $product->load('reviews', 'reviews.user');

        return view('admin.review.edit', compact('product'));
    }

    public function update(ReviewRequest $request, Review $review, ReviewService $reviewService): RedirectResponse
    {
        isAuthorized('review update');

        $review = $reviewService->updateOrCreate($request->validated());

        if(!$review) {
            return back()->with('error', 'Review could not be updated');
        }

        return redirect()->route('reviews.index')->with('success', 'Review updated successful');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // check permission of request user
        isAuthorized('review destroy');

        foreach ($product->reviews as $review) {
            foreach ($review->images as $image) {
                if($image->image) {
                    removeImage($image->image);
                }
            }

            $review->delete();
        }

        return back()->with('success', 'Review created successful');
    }
}
