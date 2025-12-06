<?php

namespace App\Providers;

use App\Models\Admin\Category;
use App\Models\AppManage;
use App\Models\Wishlist;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
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

        View::composer('*', function ($view) {
            $cached = Redis::hget('app', 'app');

            if($cached) {
                $app = json_decode($cached, true);
            } else {
                $app = AppManage::first();

                Redis::hset('app', 'app', json_encode($app));
            }

            $view->with([
                'app' => $app,
            ]);
        });
    }
}
