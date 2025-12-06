<?php

namespace App\Services;

use App\Models\ProductPolicy;
use Illuminate\Support\Facades\DB;

class ProductPolicyService
{
    public function updateOrCreate(array $data, ?ProductPolicy $productPolicy = null): ?ProductPolicy
    {
        DB::beginTransaction();
        try {
            $inputs = collect($data)->except('_token', 'remove_image')->toArray();

            if( !empty($data['remove_image']) && $data['remove_image'] == '1') {
                $inputs['image'] = null;
            }

            if(!empty($inputs['image'])) {
                // remove image if exists
                if($productPolicy) {
                    if(file_exists($productPolicy->image)) {
                        unlink($productPolicy->image);
                    }
                }

                // get new url after save image
                $inputs['image'] = getImageUrl($inputs['image'], 'admin/assets/uploaded-images/product-policy-images/');
            }

            $inputs['slug'] = generateUniqueSlug($inputs['policy']);

            DB::commit();
            return $productPolicy ? tap($productPolicy)->update($inputs) : ProductPolicy::create($inputs);
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception->getMessage());
            return null;
        }
    }
}
