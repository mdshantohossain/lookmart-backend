<?php

namespace App\Services;

use App\Jobs\ProcessProductImagesAndVariantsJob;
use App\Models\Admin\Product;
use App\Models\OtherImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ProductService
{
    /**
     * @param array $data
     * @param Product|null $product
     * @return Product|null
     * @throws Throwable
     */
    public function updateOrCreate(array $data, ?Product $product = null): ?Product
    {
        try {
            // Handle image thumbnail
            if (!empty($data['image_thumbnail'])) {

                // remove previous thumbnail from store
                if($product && $data['remove_previous_image_thumbnail'] == 1) {
                    removeImage($product->image_thumbnail);
                }

                $image_thumbnail = is_string($data['image_thumbnail']) ? $data['image_thumbnail'] : getImageUrl(
                    $data['image_thumbnail'],
                    'assets/images/uploaded-images/product-images'
                );
            }

            // remove previous video thumbnail from store
            if($product && $data['remove_video_thumbnail'] == 1) {
                if($product->video_thumbnail) {
                    removeImage($product->video_thumbnail);
                }
                 $video_thumbnail = null;
            } else {
                $video_thumbnail = $product?->video_thumbnail;
            }

            // Handle video thumbnail
            if (!empty($data['video_thumbnail'])) {
                $video_thumbnail = is_string($data['video_thumbnail']) ? $data['video_thumbnail'] : getImageUrl(
                    $data['video_thumbnail'],
                    'assets/images/uploaded-images/product-images'
                );
            }

            DB::beginTransaction();

            // product
            $inputs = [
                'category_id' => $data['category_id'],
                'sub_category_id' => $data['sub_category_id'],
                'name' => $data['name'],
                'original_price' => $data['original_price'],
                'selling_price' => $data['selling_price'],
                'discount' => $data['discount'],
                'quantity' => $data['quantity'],
                'sku' => $data['sku'],
                'image_thumbnail' => $image_thumbnail ?? $product->image_thumbnail,
                'video_thumbnail' => $video_thumbnail,
                'short_description' => $data['short_description'],
                'long_description' => $data['long_description'],
                'variants_title' => $data['variants_title'] ?? null,
                'total_sold' => $data['total_sold'] ?? null,
                'total_day_to_delivery' => $data['total_day_to_delivery'] ?? null,
                'tags' => $data['tags'],
                'meta_title' => $data['meta_title'] ?? null     ,
                'meta_keywords' => $data['meta_keywords'] ?? null   ,
                'meta_description' => $data['meta_description'] ?? null     ,
                'product_policy_id' => !empty($data['product_policy_id']) ? json_encode($data['product_policy_id']) : null,
                'is_featured' => $data['is_featured'] ?? 0,
                'is_trending' => $data['is_trending'] ?? 0,
                'is_free_delivery' => $data['is_free_delivery'] ?? 0,
                'product_owner' => $data['product_owner'],
                'status' => $data['status'],
                'slug' => $product ? $product->slug : generateUniqueSlug($data['name'])
            ];

            if(!empty($data['cj_id'])) {
                $inputs['cj_id'] = $data['cj_id'];
            }

            if(!empty($data['buy_price'])) {
                $inputs['buy_price'] = $data['buy_price'];
            }

            // Create product
            $storedProduct = $product ? tap($product)->update($inputs) : Product::create($inputs);

            // remove other images
            $removeImages = json_decode($data['remove_other_images'] ?? '[]', true);

            if(!empty($removeImages)) {
                OtherImage::whereIn('id', $removeImages)->each(function ($otherImage) {

                    if($otherImage->image) {
                        removeImage($otherImage->image);
                    }

                    // delete entier other image row
                    $otherImage->delete();
                });
            }

            // Handle other image to save
            $otherImages = collect($data['other_images'] ?? [])->map(function ($image) use($storedProduct) {
                return [
                    'product_id' => $storedProduct->id,
                    'image' => is_string($image) ? $image : getImageUrl($image, 'admin/assets/uploaded-images/product-other-images'),
                ];
            })->toArray();


            // remove variants when product update and select for remove
            $removeVariants = json_decode($data['remove_variants'] ?? '[]', true);

            if (!empty($removeVariants)) {
                ProductVariant::whereIn('id', $removeVariants)->each(function ($variant) {
                    logger($variant);
                    if ($variant->image) {
                        removeImage($variant->image);
                    }

                    $variant->delete();
                });
            }

//            exit();


            // remove variant images
            $removeVariantsImage = json_decode($data['remove_variant_images'] ?? '[]', true);

            if (!empty($removeVariantsImage)) {
                ProductVariant::whereIn('id', $removeVariantsImage)->pluck('image')->filter()->each(function ($variant) {
                    if($variant->image) {
                        removeImage($variant->image);
                    }
                });

                // Update DB once
                ProductVariant::whereIn('id', $removeVariantsImage)
                    ->update(['image' => null]);
            }

            // variant process
            $variants = collect($data['variants'])->map(function ($variant) use ($storedProduct, $data) {
                $variantImage = null;

                $image = !empty($data['variant_images'][$variant['image']]) ? $data['variant_images'][$variant['image']] : null;

                if($image) {
                    $variantImage = is_string($image) ? $image : getImageUrl($image, 'assets/images/uploaded-images/variant-images');
                }

                $processedVariant = [
                    'id' => $variant['id'] ?? null,
                    'product_id' => $storedProduct->id,
                    'vid' => $variant['vid'] ?? null,
                    'sku' => $variant['sku'] ?? null,
                    'variant_key' => $variant['variant_key'] ?? null,
                    'buy_price' => $variant['buy_price'] ?: null,
                    'selling_price' => $variant['selling_price'] ?: null,
                    'suggested_price' => $variant['suggested_price'] ?? null,
                    'image' => null
                ];

                if(!empty($variantImage)) {
                    $processedVariant['image'] = $variantImage;
                }

                return $processedVariant;
            })->toArray();

            // Queue job for insert or update variants and other images
            ProcessProductImagesAndVariantsJob::dispatch($storedProduct->id, $otherImages, $variants);

            DB::commit();

            return $storedProduct;
        } catch (Throwable $exception) {
            DB::rollBack();
            report($exception);
            throw $exception;
        }
    }
}
