<?php

namespace App\Models\Admin;

use App\Models\OtherImage;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = [];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function otherImages(): HasMany
    {
        return $this->hasMany(OtherImage::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }
    public function variants(): HasMany
    {
        return  $this->hasMany(ProductVariant::class);
    }
}
