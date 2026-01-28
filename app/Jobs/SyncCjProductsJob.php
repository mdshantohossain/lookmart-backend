<?php

namespace App\Jobs;

use App\Models\Admin\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SyncCjProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $page;
    protected $pageSize;

    /**
     * Create a new job instance.
     */
    public function __construct($page, $pageSize = 100)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::withHeaders([
            'CJ-Access-Token' => env('CJ_ACCESS_TOKEN'),
        ])->get("https://developers.cjdropshipping.com/api2.0/v1/product/list", [
            'pageNum' => $this->page,
            'pageSize' => $this->pageSize,
        ]);

        $data = $response->json('data');

        \Log::info($data);

        if (!empty($data['list'])) {
            foreach ($data['list'] as $item) {
                \Log::info($item);
//                Product::updateOrCreate(
//                    ['cj_id' => $item['pid']], // adjust based on CJ response
//                    [
//                        'name' => $item['productNameEn'],
//                        'sku' => $item['sku'],
//                        'selling_price' => $item['sellPrice'],
//                        'category_id' => $item['categoryId'],
//                    ]
//                );
            }
        } else {
            \Log::info('No data');
        }
    }
}
