<?php

namespace App\Providers;

use App\Models\Admin\Product;
use App\Models\Review;
use App\Observers\ProductObserver;
use App\Observers\ReviewObserver;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;
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
        if ($this->app->environment('local')) {
            $loader = AliasLoader::getInstance();
            $loader->alias('Debugbar', Debugbar::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // notification
        View::composer('admin.layouts.master', function ($view) {
            if(auth()->check() && auth()->user()->can('notification module')) {
                $notifications = auth()->user()
                    ->unreadNotifications()
                    ->limit(6)
                    ->get();

                $notificationCount = auth()->user()
                    ->unreadNotifications()
                    ->count();

                $view->with([
                    'notifications' => $notifications,
                    'notificationCount' => $notificationCount,
                ]);
            }
        });

        Product::observe(ProductObserver::class);
        Review::observe(ReviewObserver::class);

        // Define IOP_SDK_WORK_DIR if not defined
        if (!defined('App\Services\AliExpressSDK\iop\IOP_SDK_WORK_DIR')) {
            define(
                'App\Services\AliExpressSDK\iop\IOP_SDK_WORK_DIR',
                storage_path('app/aliexpress')
            );
        }

        // Make sure directory exists
        if (!File::exists(storage_path('app/aliexpress'))) {
            File::makeDirectory(storage_path('app/aliexpress'), 0755, true);
        }

        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}
