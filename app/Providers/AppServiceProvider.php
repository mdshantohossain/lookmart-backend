<?php

namespace App\Providers;

use App\Models\Admin\Category;
use App\Models\AppManage;
use App\Models\Wishlist;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Debugbar', Debugbar::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        View::composer('*', function ($view) {
            $view->with([
                'globalCategories' => Category::where('status', 1)->with('subCategories')->get(),
                'wishlistCounts' => Wishlist::where('user_id', auth()->id())->count(),
                'app' => AppManage::first(),
            ]);
        });
    }
}
