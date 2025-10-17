<?php

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Wishlist;
use App\Models\ShippingCharge;
use App\Models\Admin\Product;
use Illuminate\Support\Str;


if(!function_exists('removeImage')) {
    function removeImage(string $url)
    {

    }
}

if(!function_exists('getImageUrl')) {
    function getImageUrl(object $image, string $path = 'images'): string
    {
        $imageName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();

        $image->move($path . '/' , $imageName);
        // Return the URL accessible from browser (via public/storage)
        return asset($path. '/'. $imageName);
    }
}

if(!function_exists('generateUniqueSlug')) {
    function generateUniqueSlug($name): string
    {
        // base slug
        $slug = Str::slug($name);

        // check if slug already exists
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}


if(! function_exists('truncateString')) {
    function truncateString($string, $length)
    {
        if (strlen($string) > $length) return substr($string, 0, $length) . '...';
        return $string;
    }
}

if (!function_exists('isProductInCart')) {
    function isProductInCart(int $productId): bool
    {
        foreach (Cart::content() as $item) {
            if ($item->id == $productId) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('isWishlist')) {
    function isWishlist(int $productId)
    {
        return Wishlist::where('product_id', $productId)
                        ->where('user_id', auth()->id())
                        ->exists();
    }
}

if (! function_exists('getDeliveryCharge')) {
    /**
     * Return delivery charge or free if not set.
     *
     * @return int|float
     */
    function getDeliveryCharge(?int $chargeId = null)
    {
        if(!ShippingCharge::whereStatus(1)->exists()){
            return 0;
        }

        if ($chargeId) {
            $dc = ShippingCharge::where('id', $chargeId)->first();

            return $dc->is_free == 0 ? $dc->charge : 0;
        } else {
            $dc = ShippingCharge::where('city_name', 'Dhaka')->first();

            return $dc->is_free == 0 ? $dc->charge : 0;
        }
    }
}
