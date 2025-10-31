<?php

namespace App\Services;

use App\Http\Requests\ProductCreateRequest;
use App\Jobs\ProcessProductImagesAndVariantsJob;
use App\Models\Admin\Product;
use App\Models\OtherImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * @param array $data
     * @param Product|null $product
     * @return Product|null
     */
    public function createOrUpdate(array $data, ?Product $product = null): ?Product
    {
        try {
            // Handle main image
            $mainImage = is_string($data['main_image']) ? $data['main_image'] : getImageUrl(
                $data['main_image'],
                'assets/images/uploaded/product-images/'
            );

            // Handle color image
            $colorImages = collect($data['color_images'] ?? [])->map(function ($image) {
                return is_string($image) ? $image : getImageUrl($image, 'assets/uploaded-images/product-color-images');
            });

            // Handle other image
            $otherImages = collect($data['other_images'] ?? [])->map(function ($image) {
                return [
                    'image' => is_string($image) ? $image : getImageUrl($image, 'admin/assets/upload-images/product-other-images'),
                ];
            })->toArray();

            // Variants
            $variants = collect($data['variants'] ?? [])->map(function ($variant) {
                return [
                    'vid' => $variant['vid'] ?? null,
                    'sku' => $variant['sku'] ?? null,
                    'variant_key' => $variant['variant_key'] ?? null,
                    'buy_price' => $variant['buy_price'] ?? null,
                    'selling_price' => $variant['selling_price'] ?? null,
                    'suggested_price' => $variant['suggestion_sell_price'] ?? null,
                    'image' => !empty($variant['image']) ? (is_string($variant['image']) ? $variant['image'] : getImageUrl($variant['image'], 'admin/assets/uploaded-images/variant-images')) : null,
                ];
            })->toArray();

            DB::beginTransaction();

            // Create product
            $product = Product::create([
                'cj_id' => $data['cj_id'],
                'buy_price' => $data['buy_price'],
                'category_id' => $data['category_id'],
                'sub_category_id' => $data['sub_category_id'],
                'name' => $data['name'],
                'regular_price' => $data['regular_price'],
                'selling_price' => $data['selling_price'],
                'discount' => $data['discount'],
                'quantity' => $data['quantity'],
                'sku' => $data['sku'],
                'sizes' => $data['sizes'],
                'color_images' => json_encode($colorImages),
                'main_image' => $mainImage,
                'short_description' => $data['short_description'],
                'long_description' => $data['long_description'],
                'variants_title' => $data['variants_title'],
                'tags' => $data['tags'],
                'meta_title' => $data['meta_title'],
                'meta_description' => $data['meta_description'],
                'is_featured' => $data['is_featured'] ?? 0,
                'is_trending' => $data['is_trending'] ?? 0,
                'status' => $data['status'],
                'variant_json' => $data['variant_json'] ?? null,
                'slug' => generateUniqueSlug($data['name'])
            ]);

            DB::commit();

            // Queue job
            ProcessProductImagesAndVariantsJob::dispatch($product, $otherImages, $variants);

            return $product;
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            DB::rollBack();
            return null;
        }
    }
}
