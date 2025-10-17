<?php

namespace App\Providers;

use App\Models\Admin\Category;
use App\Models\AppManage;
use App\Models\Wishlist;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with([
                'globalCategories' => Category::where('status', 1)->with('subCategories')->get(),
                'wishlistCounts' => Wishlist::where('user_id', auth()->id())->count(),
                'app' => AppManage::first(),
            ]);
        });
    }
}
