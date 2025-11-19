<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use App\Models\Website\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return view('website.wishlist.index', [
            'wishlists' => Wishlist::with('product')
                                ->where('user_id', auth()->id())
                                ->orderBy('id', 'DESC')
                                ->get()
        ]);
    }

    public function store(Request $request): JsonResponse
    {

    }

    public function destroy(Wishlist $wishlist): JsonResponse
    {
        try {
            if(auth()->id() == $wishlist->user_id) {
                $wishlist->delete();

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Server error occurred. Please try again later.',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Wishlist deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
