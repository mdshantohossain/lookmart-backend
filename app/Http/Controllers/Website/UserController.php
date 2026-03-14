<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return view('website.user.index', [
            'orders' => Order::with(['orderDetails', 'orderDetails.product'])
                            ->where('user_id', auth()->user()->id)
                            ->orderBy('id', 'DESC')
                            ->get()
        ]);
    }
}
