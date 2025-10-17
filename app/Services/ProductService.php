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
    public function create(array $data): ?Product
    {
        DB::beginTransaction();
        try {
            $inputs = collect($data)->only([
                'category_id',
                'sub_category_id',
                'name',
                'regular_price',
                'selling_price',
                'cj_id',
                'buy_price',
                'sku',
                'discount',
                'quantity',
                'short_description',
                'long_description',
                'status'
            ])->toArray();

            $variants = $data['variants'];
            // Handle main image
            if (!empty($data['cj_id']) && !empty($data['cj_main_image'])) {
                $inputs['main_image'] = $data['cj_main_image'];
            } elseif (!empty($data['main_image'])) {
                // check is image uploaded on previous image
                if ( gettype($data['main_image']) !== 'string' ) {
                    $inputs['main_image'] = getImageUrl(
                        $data['main_image'],
                        'admin/assets/images/product-images/'
                    );
                } else {
                    $inputs['main_image'] =  $data['main_image'];
                }
            }

            // Generate slug
            $inputs['slug'] = Str::slug($inputs['name']) . '-' . Str::random(8);

            // Create product
            $product = Product::create($inputs);

            // Handle other images
            if (!empty($data['cj_id']) && !empty($data['cj_other_images'])) {
                foreach ($data['cj_other_images'] as $otherImage) {
                    OtherImage::create([
                        'product_id' => $product->id,
                        'image'      => $otherImage
                    ]);
                }
            } elseif (!empty($data['other_images'])) {
                foreach ($data['other_images'] as $otherImage) {
                    if(gettype($otherImage) !== 'string') {
                        OtherImage::create([
                            'product_id' => $product->id,
                            'image'      => getImageUrl(
                                $otherImage,
                                'admin/assets/images/other-images/'
                            )
                        ]);
                    } else {
                        OtherImage::create([
                            'product_id' => $product->id,
                            'image'      => $otherImage
                        ]);
                    }
                }
            }


            foreach ($variants as $variant) {

                ProductVariant::create([
                    'product_id'      => $product->id,
                    'cj_variant_id'   => $variant['vid'] ?? null,
                    'cj_variant_sku'  => $variant['variantSku'] ?? null,
                    'price'           => $variant['variantSellPrice'] ?? null,
                    'variant_image'   => $variant['variantImage'] ?? null,
                    'product_length'  => $variant['variantLength'] ?? null,
                    'width'           => $variant['variantWidth'] ?? null,
                    'height'          => $variant['variantHeight'] ?? null,
                    'weight'          => $variant['variantWeight'] ?? null,
                    'images'          => json_encode([$variant['variantImage']]),
                ]);
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
