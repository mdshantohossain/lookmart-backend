<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
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
}
