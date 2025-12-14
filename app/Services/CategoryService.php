<?php

namespace App\Services;

use App\Models\Admin\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class  CategoryService
{
    public function updateOrCreate(array $data, ?Category $category = null): ?Category
    {
        DB::beginTransaction();
        try {
            $inputs = collect($data)->except(['remove_image', '_method'])->toArray();

            if( !empty($data['remove_image']) && $data['remove_image'] == '1') {
                $inputs['image'] = null;
            }

            if(! empty($inputs['image'])) {
                if($category?->image) {
                    removeImage($category->image);
                }

                $inputs['image'] = getImageUrl($inputs['image'], 'admin/assets/uploaded-images/category-images/');
            }


            // Slug
            $inputs['slug'] = Str::slug($inputs['name']);

            // Create or update inside transaction
            if ($category) {
                $category->update($inputs);
            } else {
                $category = Category::create($inputs);
            }

            DB::commit();
            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e);
            return null;
        }
    }
}
