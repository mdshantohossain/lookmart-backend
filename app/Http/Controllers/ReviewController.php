<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Admin\Product;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.review.index', [
            'products' =>  Product::whereHas('reviews')
                ->with('reviews', 'reviews.user')
                ->latest()
                ->get(['id', 'sku', 'name', 'main_image']),
        ]);
    }

    public function create(): View
    {
        // check permission of request user
        isAuthorized('review create');

        return view('admin.review.create', [
            'products' => Product::select(['id', 'name', 'main_image'])->latest()->get()
        ]);
    }

    public function store(ReviewRequest $request, ReviewService $reviewService)
    {
        $review = $reviewService->updateOrCreate($request->validated());

        if(!$review) {
            return back()->with('error', 'Review could not be created');
        }

        return redirect()->route('reviews.index')->with('success', 'Review created successful');
    }

    public function show(Review $review): View
    {
        // check permission of request user
        isAuthorized('review show');

        return view('admin.review.show', compact('review'));
    }

    public function edit(Review $review): View
    {
        // check permission of request user
        isAuthorized('review edit');

        return view('admin.review.edit', compact('review'));
    }

    public function update(ReviewRequest $request, Review $review, ReviewService $reviewService): RedirectResponse
    {
        isAuthorized('review update');

        $review = $reviewService->updateOrCreate($request->validated(), $review);

        if(!$review) {
            return back()->with('error', 'Review could not be updated');
        }

        return redirect()->route('reviews.index')->with('success', 'Review updated successful');
    }

    public function destroy(Review $review): RedirectResponse
    {
        // check permission of request user
        isAuthorized('review destroy');

        $review->delete();

        return back()->with('success', 'Review created successful');
    }
}
