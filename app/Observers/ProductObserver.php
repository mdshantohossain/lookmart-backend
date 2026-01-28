<?php

namespace App\Observers;
use App\Jobs\ProductCacheJob;
use App\Jobs\RevalidateProductPageJob;
use App\Models\Admin\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        RevalidateProductPageJob::dispatch($product->slug);
        ProductCacheJob::dispatch($product->id, $product->slug, $product->category_id);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if($product->wasChanged(['name', 'regular_price', 'selling_price', 'discount', 'short_description', 'description', 'image_thumbnail', 'video_thumbnail'])) {
            RevalidateProductPageJob::dispatch($product->slug);
            ProductCacheJob::dispatch($product->id, $product->slug, $product->category_id);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        RevalidateProductPageJob::dispatch($product->slug);
    }
}
