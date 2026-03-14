<?php

namespace App\Models\Admin;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SubCategory extends Model
{
    protected  $guarded = [];

    public function getRouteKeyName():  string
    {
        return 'slug';
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sliders(): MorphMany
    {
        return $this->morphMany(Slider::class, 'sliderable');
    }
}
