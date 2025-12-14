<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewService
{
    /**
     * @param array $data
     * @param Review|null $review
     * @return Review|null
     */
    public function updateOrCreate(array $data): bool
    {
        DB::beginTransaction();

        try {
            foreach ($data['reviews'] as $review) {

               $storedReview = Review::updateOrCreate([
                    'id' => $review['id'] ?? null,
                ], [
                  'product_id' => $data['product_id'],
                  'user_id' => $data['user_id'] ?? null,
                  'name' => $review['name'],
                  'message' => $review['message'],
                  'rating' => $review['rating'],
                  'slug' => Str::random(64),
                  'published_at' => $review['date'],
                ]);

                // handle review images
                if (!empty($review['images'])) {
                    foreach ($review['images'] as $image) {

                        // UploadedFile case handling
                        $path = getImageUrl(
                            $image,
                            'admin/assets/uploaded-images/review-images'
                        );

                        ReviewImage::create([
                            'review_id' => $storedReview->id,
                            'image' => $path,
                        ]);
                    }
                }
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e);
            return false;
        }
    }
}
