<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function getSearchProducts(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        try {
            $query = $request->query('query');
            $category = $request->query('category');

            $products = Product::query()
                ->select(['id', 'name', 'slug', 'image_thumbnail', 'sku',
                    'video_thumbnail', 'selling_price', 'original_price', 'discount',
                    'total_day_to_delivery', 'total_sold', 'is_free_delivery'])
                ->with(['variants'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)

                // apply search only if query exists
                ->when($query, function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                })
                // apply category only if not "all" and not null
                ->when($category && $category !== 'all', function ($q) use ($category) {
                    $q->where('category_id', $category);
                })
                ->get();

//                ->latest()
//                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Throwable $e) {
            report($e);
            throw $e;
        }
    }

    public function filterProducts(Request $request): JsonResponse
    {
        try {
            $filters = [
                'categories' => $request->categories,
                'brands' => $request->brands,
                'sizes' => $request->sizes,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'page' => $request->page ?? 1,
            ];

            $cacheKey = 'products:' . md5(json_encode($filters));

            return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($filters) {
                $query = Product::query();

                if($filters['categories']) {
                    $query->whereIn('category_id', explode(',', $filters['categories']));
                }

                if($filters['brands']) {
                    $query->whereIn('brand_id', explode(',', $filters['brands']));
                }

                if($filters['sizes']) {
                    $query->whereHas('variants', function ($q) use ($filters) {
                        $q->whereIn('variant_key', $filters['sizes']);
                    });
                }

                if ($filters['min_price']) {
                    $query->where('selling_price', '>=', $filters['min_price']);
                }

                if ($filters['max_price']) {
                    $query->where('selling_price', '<=', $filters['max_price']);
                }

                return response()->json([
                    'success' => true,
                    'data' => $query->paginate(2),
                ]);
            });

        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
