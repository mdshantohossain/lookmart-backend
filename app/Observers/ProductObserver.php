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
        if($product->wasChanged(['name', 'category_id', 'regular_price', 'selling_price',
            'discount', 'short_description', 'description',
            'image_thumbnail', 'video_thumbnail', 'meta_title', 'meta_description', 'is_trending', 'is_featured', 'is_free_delivery',
            'total_day_to_delivery', 'variants_title'
        ])) {
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
