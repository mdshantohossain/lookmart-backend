<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Slider extends Model
{
    protected $guarded = [];

    public function sliderable(): MorphTo
    {
        return $this->morphTo();
    }
}
