<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Requests\UserReviewRequest;
use App\Models\Admin\Product;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReviewController extends Controller
{

    protected string $cachedKey = 'products';

    public function index(): View
    {
        $products = Cache::remember($this->cachedKey, now()->addDays(), function () {
           return Product::whereHas('reviews')
               ->with('reviews', 'reviews.user')
               ->latest()
               ->get(['id', 'sku', 'name', 'image_thumbnail']);
        });

        return view('admin.review.index', compact('products'));
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

        // invalidate cache
        $this->invalidateCache();

        return redirect()->route('reviews.index')->with('success', 'Review created successful');
    }

    public function show(Product $product): View
    {
        // check permission of request user
        isAuthorized('review show');

        $product->load('reviews', 'reviews.user', 'reviews.images');

        // invalidate cache
        $this->invalidateCache();

        return view('admin.review.detail', compact('product'));
    }

    public function edit(Product $product)
    {
        // check permission of request user
        isAuthorized('review edit');

        $product->load('reviews', 'reviews.user');

        // invalidate cache
        $this->invalidateCache();

        return view('admin.review.edit', compact('product'));
    }

    public function update(ReviewRequest $request, Review $review, ReviewService $reviewService): RedirectResponse
    {
        isAuthorized('review update');

        $review = $reviewService->updateOrCreate($request->validated());

        if(!$review) {
            return back()->with('error', 'Review could not be updated');
        }

        // invalidate cache
        $this->invalidateCache();

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

        // invalidate cache
        $this->invalidateCache();

        return back()->with('success', 'Review created successful');
    }

    public function addReview(UserReviewRequest $request): JsonResponse
    {
        try {
            $user = $request->user();

           $review = Review::create([
               'user_id' => $user->id,
               'product_id' => $request->product_id,
               'message' => $request->message,
               'rating' => $request->rating,
               'published_at' => now(),
               'slug' => Str::random(30),
            ]);

           if(!$review) {
               return response()->json([
                   'success' => false,
                   'message' => 'Review not created',
               ], 201);
           }

            return response()->json([
                'success' => true,
                'message' => 'Review added successful',
            ], 201);
        } catch (\Throwable $th) {
            report($th);
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function invalidateCache()
    {
        Cache::forget($this->cachedKey);
    }
}
