<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewService
{
    /**
     * @param array $data
     * @param Review|null $review
     * @return Review|null
     */
    public function updateOrCreate(array $data, ?Review $review = null): bool|null
    {
        DB::beginTransaction();

        try {
            foreach ($data['reviews'] as $value) {
                DB::table('reviews')->updateOrInsert([
                    'id' => $value['id'] ?? null,
                ], [
                  'product_id' => $data['product_id'],
                  'user_id' => $data['user_id'] ?? null,
                  'name' => $value['name'],
                  'message' => $value['message'] ?? now(),
                  'rating' => $value['rating'],
                  'slug' => Str::random(64),
                  'published_at' => $value['date'],
                ]);
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e);
            return null;
        }
    }
}
