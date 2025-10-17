<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CjController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $response = Http::withHeaders([
            'CJ-Access-Token' => env('CJ_ACCESS_TOKEN'),
        ])->get("https://developers.cjdropshipping.com/api2.0/v1/product/query?productSku=$query");

        $data = $response->json('data');

        if($data) {
            return response()->json([
                'code' => 200,
                'data' => $data
            ]);
        }

        return response()->json([
            'code' => 404,
            'data' => [],
        ]);
    }
}
