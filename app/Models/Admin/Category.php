<?php

namespace App\Models\Admin;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function sliders(): MorphMany
    {
        return $this->morphMany(Slider::class, 'sliderable');
    }
}
