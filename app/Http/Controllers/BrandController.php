<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class BrandController extends Controller
{
    public string $cacheKey = 'brands';
    public function getBrands()
    {
        $cached = Redis::get($this->cacheKey);

        if($cached) {
            return response()->json(json_decode($cached));
        }

        $brands = Brand::where('status', 1)->get();

        $response = [
            'success' => true,
            'data' => $brands,
        ];

        Redis::set($cached, json_encode($response));

        return response()->json($response);
    }

    public function revalidateBrandsCache()
    {
        Redis::del($this->cacheKey);

        $brands = Brand::where('status', 1)->get();

        Redis::set($this->cacheKey, json_encode($brands));
    }
}
