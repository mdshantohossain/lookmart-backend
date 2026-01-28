<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        logger()->info($request->all());

        return response()->json([
            'status' => true,
            'data' => [],
        ], 201);
    }
}
