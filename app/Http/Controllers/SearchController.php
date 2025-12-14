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
            'query' => 'required|string'
        ]);

        try {
            $query = $request->query('query');

            $products = Product::with(['variants'])
                ->select(['id', 'name', 'slug', 'thumbnail', 'selling_price', 'original_price', 'discount']) // add more field if needed
                ->where('name', 'like', "%$query%")
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('status', 1)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
