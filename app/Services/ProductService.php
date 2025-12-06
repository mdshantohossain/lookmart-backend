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
    public function updateOrCreate(array $data, ?Product $product = null): ?Product
    {
        try {
            // Handle main image
            if (!empty($data['thumbnail'])) {

                // remove previous thumbnail from store
                if($product && $data['remove_previous_thumbnail'] == 1) {
                    removeImage($product->thumbnail);
                }

                $thumbnail = is_string($data['thumbnail']) ? $data['thumbnail'] : getImageUrl(
                    $data['thumbnail'],
                    'assets/images/uploaded-images/product-images'
                );
            }

            DB::beginTransaction();

            $inputs = [
                'cj_id' => $data['cj_id'],
                'buy_price' => $data['buy_price'],
                'category_id' => $data['category_id'],
                'sub_category_id' => $data['sub_category_id'],
                'name' => $data['name'],
                'original_price' => $data['original_price'],
                'selling_price' => $data['selling_price'],
                'discount' => $data['discount'],
                'quantity' => $data['quantity'],
                'sku' => $data['sku'],
                'thumbnail' => $thumbnail ?? $product->thumbnail,
                'short_description' => $data['short_description'],
                'long_description' => $data['long_description'],
                'variants_title' => $data['variants_title'],
                'total_sold' => $data['total_sold'],
                'total_day_to_delivery' => $data['total_day_to_delivery'],
                'tags' => $data['tags'],
                'meta_title' => $data['meta_title'],
                'meta_keywords' => $data['meta_keywords'],
                'meta_description' => $data['meta_description'],
                'product_policy_id' => !empty($data['product_policy_id']) ? json_encode($data['product_policy_id']) : null,
                'is_featured' => $data['is_featured'] ?? 0,
                'is_trending' => $data['is_trending'] ?? 0,
                'product_owner' => $data['product_owner'],
                'status' => $data['status'],
                'slug' => $product ? $product->slug : generateUniqueSlug($data['name'])
            ];

            // Create product
            $storedProduct = $product ? tap($product)->update($inputs) : Product::create($inputs);

            if(!empty($data['remove_other_image'])) {
                $deleteIds = array_keys(array_filter($data['remove_other_image'], fn($v) => $v == 1));

                if ($deleteIds) {
                    OtherImage::whereIn('id', $deleteIds)->delete();
                }
            }

            // Handle other image
            $otherImages = collect($data['other_images'] ?? [])->map(function ($image) use($storedProduct) {
                return [
                    'product_id' => $storedProduct->id,
                    'image' => is_string($image) ? $image : getImageUrl($image, 'admin/assets/uploaded-images/product-other-images'),
                ];
            })->toArray();

            // remove variants
            if (!empty($data['remove_variants'])) {

                // get IDs to remove
                $ids = array_keys(array_filter($data['remove_variants'], fn($f) => $f == 1));

                if (!empty($ids)) {

                    // fetch all variants for product
                    $variants = ProductVariant::whereIn('id', $ids)->get();

                    // remove all images (no DB query)
                    foreach ($variants as $variant) {
                        if ($variant->image) {
                            removeImage($variant->image);
                        }
                    }

                    // delete all variants for product
                    ProductVariant::whereIn('id', $ids)->delete();
                }
            }

            // variant process
            $variants = collect($data['variants'] ?? [])->map(function ($variant) use ($storedProduct) {
                $variantImage = null;

                // removing image logic
                if (!empty($variant['remove_image']) && $variant['remove_image'] == 1) {

                    if (!empty($variant['id'])) {
                        $old = ProductVariant::find($variant['id']);
                        if ($old && $old->image) {
                            removeImage($old->image);
                        }
                    }
                }

                if(!empty($variant['image'])) {
                    $variantImage = is_string($variant['image']) ? $variant['image'] : getImageUrl($variant['image'], 'assets/images/uploaded-images/variant-images');
                }

                $processedVariant = [
                    'id' => $variant['id'] ?? null,
                    'product_id' => $storedProduct->id,
                    'vid' => $variant['vid'] ?? null,
                    'sku' => $variant['sku'] ?? null,
                    'variant_key' => $variant['variant_key'] ?? null,
                    'buy_price' => $variant['buy_price'] ?? null,
                    'selling_price' => $variant['selling_price'] ?? null,
                    'suggested_price' => $variant['suggested_price'] ?? null,
                    'image' => null
                ];

                if(!empty($variantImage)) {
                    $processedVariant['image'] = $variantImage;
                }

                return $processedVariant;
            })->toArray();

            // Queue job
            ProcessProductImagesAndVariantsJob::dispatch($storedProduct->id, $otherImages, $variants);

            DB::commit();

            return $storedProduct;
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            DB::rollBack();
            return null;
        }
    }
}
