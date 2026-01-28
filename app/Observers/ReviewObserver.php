<?php

namespace App\Observers;

use App\Jobs\ProductCacheJob;
use App\Models\Admin\Product;
use App\Models\Review;

class ReviewObserver
{
    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $this->revalidateProductCached($review->product_id);
    }

    public function updated(Review $review): void
    {
        $this->revalidateProductCached($review->product_id);
    }

    public function deleted(Review $review): void
    {
        $this->revalidateProductCached($review->product_id);
    }

    public function revalidateProductCached(int $id): void
    {
        $product = Product::find($id);
        ProductCacheJob::dispatch($product->id, $product->slug, $product->category_id);
    }
}
