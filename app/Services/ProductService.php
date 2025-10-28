<?php

namespace App\Services;

use App\Http\Requests\ProductCreateRequest;
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
        DB::beginTransaction();
        try {
            $inputs = collect($data)->only([
                'cj_id',
                'buy_price',
                'category_id',
                'sub_category_id',
                'name',
                'regular_price',
                'selling_price',
                'discount',
                'quantity',
                'sku',
                'sizes',
                'short_description',
                'long_description',
                'variants_title',
                'tags',
                'meta_title',
                'meta_description',
                'is_featured',
                'is_trending',
                'status',
                'variant_json',
            ])->toArray();

            // Handle main image
            if (gettype($data['main_image']) !== 'string' ) {
                $inputs['main_image'] = getImageUrl(
                    $data['main_image'],
                    'admin/assets/images/product-images/'
                );
            } else {
                $inputs['main_image'] =  $data['main_image'];
            }

            // Handle color image
            if(isset($data['color_images'])) {
                $colorImages = [];

                foreach ($data['color_images'] as $image) {
                    $colorImages[] = gettype($image) === 'string' ? $image : getImageUrl($image, 'admin/assets/images/product-color-images/');
                }
                $inputs['color_images'] = json_encode($colorImages);
            }

            // Generate slug
            $inputs['slug'] = generateUniqueSlug($inputs['name']);

            // Create product
            $product = Product::create($inputs);

            // Handle other images
            if ( !empty($data['other_images'])) {
                foreach($data['other_images'] as $otherImage) {
                    $image = gettype($otherImage) === 'string' ? $otherImage : getImageUrl($otherImage, 'admin/assets/images/product-other-images/');
                    OtherImage::create([
                        'product_id' => $product->id,
                        'image'      => $image
                    ]);
                }
            }

            // insert product variants
            if(isset($data['variants'])) {
                foreach ($data['variants'] as $variant) {

                    $image = gettype($variant['image']) !== 'string' ? getImageUrl($variant['image'], 'admin/assets/images/'):  $variant['image'];
                    ProductVariant::create([
                        'product_id'      => $product->id,
                        'vid'   => $variant['vid'] ?? null,
                        'sku'  => $variant['sku'] ?? null,
                        'variant_key'  => $variant['variant_key'] ?? null,
                        'buy_price'           => $variant['buy_price'] ?? null,
                        'selling_price'           => $variant['selling_price'] ?? null,
                        'suggested_price'           => $variant['suggestion_sell_price'] ?? null,
                        'image'   => $image ?? null,
                    ]);
                }
            }

            DB::commit();

            return $product;
        } catch (\Exception $exception) {
            logger($exception->getMessage());
            DB::rollBack();
            return null;
        }
    }
}
