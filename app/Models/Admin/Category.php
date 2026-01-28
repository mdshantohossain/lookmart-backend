<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $guarded = [];

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }

    public  function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
