<?php

namespace App\Services;

use App\Models\Admin\SubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubCategoryService
{
    public function updateOrCreate(array $data, ?SubCategory $subCategory = null): ?SubCategory
    {
        DB::beginTransaction();
        try {
            $inputs = collect($data)->except(['_token', '_method'])->toArray();

            $inputs['slug'] = Str::slug($inputs['name']);
            DB::commit();
            return $subCategory ? tap($subCategory)->update($inputs) : SubCategory::create($inputs);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e);
            return null;
        }
    }
}
