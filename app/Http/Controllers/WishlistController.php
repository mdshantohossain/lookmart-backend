<?php

namespace App\Http\Controllers;

use App\Models\Admin\Product;
use App\Models\Website\Wishlist;
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

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'user') {
            return response()->json([
                'message' => 'You do not have permission to perform this action.',
                'status' => 'You are unauthorized.',
                'code' => '403',
            ], 403);
        }

        $request->validate([
            'slug' =>  'required|exists:products,slug',
        ]);

        try {
            $product = Product::where('slug', $request->slug)->first();

            if (!Wishlist::where('product_id', $product->id)->exists()) {
                Wishlist::create([
                    'user_id' => auth()->id(),
                    'product_id' =>  $product->id,
                ]);

                return response()->json(['success' => 'Wishlist created successfully']);
            }
            return response()->json(['warning' => 'Wishlist already added in wishlist.']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Wishlist $wishlist)
    {
        try {
            if(auth()->id() == $wishlist->user_id) {
                $wishlist->delete();
            } else {
                abort(403);
            }

            return back()->with('success','Wishlist deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
