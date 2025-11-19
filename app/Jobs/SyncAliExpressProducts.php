<?php

namespace App\Jobs;

use App\Models\Admin\Product;
use App\Services\AliExpressService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncAliExpressProducts implements ShouldQueue
{
    use Queueable;

    protected $categoryId;

    /**
     * Create a new job instance.
     */
    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Execute the job.
     */
    public function handle(AliExpressService $service): void
    {
        $response = $service->call('aliexpress.open.product.search', [
            'page_no' => 1,
            'page_size' => 50,
        ]);

        $products = $response['aliexpress_open_product_search_response']['result']['products'] ?? [];

        logger()->info($products);

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['product_id' => $p['product_id']],
                [
                    'title' => $p['product_title'],
                    'price' => $p['sale_price'],
                    'image' => $p['image_url'],
                    'category_id' => $p['category_id'],
                ]
            );
        }
    }
}
