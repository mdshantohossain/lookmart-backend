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
                $inputs['image'] = getImageUrl($inputs['image'], 'admin/assets/uploaded-images/category-images/');
            }

            $inputs['slug'] = Str::slug($inputs['name']);

            DB::commit();
            return $category ? tap($category)->update($inputs) : Category::create($inputs);

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e);
            return null;
        }
    }
}
