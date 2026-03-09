<?php

namespace App\Jobs;

use App\Models\Admin\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProductCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $productId, public string $slug, public int $categoryId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->rebuildProductCache();
        $this->rebuildRelatedProductsCache();
    }

    protected function rebuildProductCache(): void
    {
        $cacheKey = "product_detail:{$this->slug}";

        Redis::del($cacheKey);

        $product = Product::with(['category', 'otherImages', 'reviews' => fn ($query) => $query->latest(), 'reviews.user', 'variants'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('slug', $this->slug)
            ->first();

        if (!$product) {
            return;
        }

        $product->policies = $product->policies()->get();

        $response = [
            'success' => true,
            'data' => $product,
        ];

        logger()->info($response);

        Redis::set($cacheKey, json_encode($response));
    }

    // product's related product
    protected function rebuildRelatedProductsCache(): void
    {
        $cacheKey = "related_products:{$this->slug}";

        Redis::del($cacheKey);

        // Fetch related products by product category
        $relatedProducts = Product::with('variants')
            ->select(['id', 'category_id', 'name', 'slug', 'image_thumbnail', 'sku',
                'video_thumbnail', 'selling_price', 'original_price', 'discount',
                'total_day_to_delivery', 'total_sold', 'is_free_delivery']) // add more field if needed
            ->where('category_id', $this->categoryId)
            ->where('slug', '!=', $this->slug)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->take(20)
            ->get();

        $response = [
            'success' => true,
            'data' => $relatedProducts,
        ];

        Redis::set($cacheKey, json_encode($response));
    }
}
