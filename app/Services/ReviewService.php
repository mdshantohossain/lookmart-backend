<?php

namespace App\Services;

use App\Models\Review;

class ReviewService
{
    /**
     * @param array $data
     * @param Review|null $review
     * @return Review|null
     */
    public function updateOrCreate(array $data, ?Review $review = null): ?Review
    {
        try {
            $inputs = collect($data)->toArray();

            return $review ? tap($review)->update($inputs) : Review::create($inputs);
        } catch (\Exception $e) {
            logger()->error($e);
            return null;
        }
    }
}
