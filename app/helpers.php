<?php

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Wishlist;
use App\Models\ShippingCharge;
use App\Models\Admin\Product;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


// check authorization
if(!function_exists('isAuthorized')) {
    function isAuthorized(string $permission): void {
      if(! auth()?->user()->can($permission)) {
          abort(403);
      }
    }
}

// get file type
if(!function_exists('getFileType')) {
    function getFileType(string $url): string
    {
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        if (in_array($extension, ['mp4', 'mov', 'avi', 'wmv'])) {
            $type = "video";
        } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $type = "image";
        } else {
            $type = "unknown";
        }

        return $type;
    }
}

// status generate
if (!function_exists('getStatus')) {
    /**
     * Generate HTML badge for status
     *
     * @param int $status
     * @param string $type  'user' or 'product'
     * @return string
     */
    function getStatus(int $status, string $type = 'user'): string
    {
        $statuses = [
            'activity' => [
                0 => ['label' => 'Offline', 'class' => 'badge-soft-secondary'],
                1 => ['label' => 'Online', 'class' => 'badge-soft-success'],
            ],
            'catalog' => [
                0 => ['label' => 'Unpublished', 'class' => 'badge-soft-secondary'],
                1 => ['label' => 'Published', 'class' => 'badge-soft-success'],
            ],
            'user' => [
                0 => ['label' => 'Inactive', 'class' => 'badge-soft-secondary'],
                1 => ['label' => 'Active', 'class' => 'badge-soft-success'],
                2 => ['label' => 'Blocked', 'class' => 'badge-soft-danger']
            ],
            'order' => [
                0 => ['label' => 'Pending', 'class' => 'badge-soft-warning'],
                1 => ['label' => 'Processing', 'class' => 'badge-soft-info'],
                2 => ['label' => 'Delivered', 'class' => 'badge-soft-success'],
                3 => ['label' => 'Canceled', 'class' => 'badge-soft-danger'],
                4 => ['label' => 'Returned', 'class' => 'badge-soft-secondary'],
            ],
        ];

        $data = $statuses[$type][$status] ?? ['label' => 'Unknown', 'class' => 'badge-soft-dark'];

        return "<span class='badge badge-pill {$data['class']} font-size-11'>{$data['label']}</span>";
    }
}

if(!function_exists('removeImage')) {
    function removeImage(string $url): void
    {
        // Remove domain from URL → get relative path
        $relativePath = str_replace(asset(''), '', $url);

        // Convert to full server path
        $fullPath = public_path($relativePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}

if(!function_exists('getImageUrl')) {
    function getImageUrl(object $file, string $path = 'uploaded'): string
    {
        // ensure directory exists
        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $fullPath = $path . '/' . $filename;

        // IMAGE UPLOAD + RESIZE
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {

            // Read image
            $image = Image::read($file->getRealPath())->resize(400, 300);

            // Save with 90% quality
            $image->save($fullPath, quality: 90);

            return asset("$path/$filename");
        }

        // OTHER FILE TYPE
        $file->move($fullPath, $filename);

        return asset("$path/$filename");
    }
}

if(!function_exists('generateUniqueSlug')) {
    function generateUniqueSlug($name): string
    {
        $slug = Str::slug($name);
        $exists = Product::where('slug', $slug)->exists();
        if ($exists) {
            $slug .= '-' . Str::random(15);
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
