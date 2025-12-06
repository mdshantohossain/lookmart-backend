<?php

namespace App\Jobs;

use App\Models\ProductVariant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessProductImagesAndVariantsJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected int $productId;
    protected $otherImages;
    protected $variants;

    public function __construct($productId, array $otherImages, array $variants)
    {
        $this->productId = $productId;
        $this->otherImages = $otherImages;
        $this->variants = $variants;
    }

    public function handle(): void
    {
        DB::transaction(function () {
            // Insert other images
            if (!empty($this->otherImages)) {
                DB::table('other_images')->insert($this->otherImages);
            }

            // Insert variants
            if (!empty($this->variants)) {
                foreach ($this->variants as $variant) {
                    ProductVariant::updateOrCreate(
                        [
                            'id' => $variant['id'] ?? null
                        ],
                        $variant
                    );
                }
            }
        });
    }
}
