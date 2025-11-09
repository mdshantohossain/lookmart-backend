<?php

namespace App\Jobs;

use App\Models\Admin\Product;
use App\Models\OtherImage;
use App\Models\ProductVariant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessProductImagesAndVariantsJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $product;
    protected $otherImages;
    protected $variants;

    public function __construct(Product $product, array $otherImages, array $variants)
    {
        $this->product = $product;
        $this->otherImages = $otherImages;
        $this->variants = $variants;
    }

    public function handle(): void
    {
        // Insert other images
        if (!empty($this->otherImages)) {
            foreach ($this->otherImages as &$img) {
                $img['product_id'] = $this->product->id;
            }
            DB::table('other_images')->insert($this->otherImages);
        }

        // Insert variants
        if (!empty($this->variants)) {
            foreach ($this->variants as &$v) {
                $v['product_id'] = $this->product->id;
            }
            DB::table('product_variants')->insert($this->variants);
        }
    }
}
