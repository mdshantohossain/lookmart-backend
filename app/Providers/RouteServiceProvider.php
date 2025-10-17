<?php

namespace App\Providers;

use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\SubCategory;
use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // route binding for product
        Route::bind('product', function (string $slug) {
            return Product::where('slug', $slug)->firstOrFail();
        });

        // route binding for category
        Route::bind('category', function (string $slug) {
            return Category::where('slug', $slug)->firstOrFail();
        });

        // route binding for sub category
        Route::bind('subCategory', function (string $slug) {
            return SubCategory::where('slug', $slug)->firstOrFail();
        });

        Route::bind('order', function (string $slug) {
            return Order::where('slug', $slug)->firstOrFail();
        });
    }
}
