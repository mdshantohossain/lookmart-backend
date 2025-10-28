<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Website\OrderController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Website\UserController as ClientController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AppCredentialController;
use App\Http\Controllers\ShippingChargeController;
use App\Http\Controllers\AppManageController;
use App\Http\Controllers\CjController;
use App\Http\Controllers\ProductPolicyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AliExpressController;

// middleware
use App\Http\Middleware\AuthenticatedForUserMiddleware;
use App\Http\Middleware\AdminAuthenticatedMiddleware;

// custom login route
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/aliexpress/redirect', [AliExpressController::class, 'redirectToAliExpress']);
Route::get('/aliexpress/callback', [AliExpressController::class, 'handleCallback']);
Route::get('/aliexpress/refresh', [AliExpressController::class, 'refreshToken']);
Route::get('/aliexpress/security-token', [AliExpressController::class, 'getSecurityToken']);
Route::get('/aliexpress/products', [AliExpressController::class, 'getProducts']);
Route::get('/aliexpress/categories', [AliExpressController::class, 'getCategories']);

// order routes
Route::post( '/order-store', [OrderController::class, 'store'])->name( 'order.store');

// login with socialite route
Route::get('/auth/google', function () {
    return 'ok';
})->name('auth.google');
Route::get('/auth/google/callback', function () {
    return 'ok';
});
Route::get('/auth/facebook', function () {
    return 'ok';
})->name('auth.facebook');
Route::get('/auth/facebook/callback', function () {
    return 'ok';
});

Route::middleware(['auth', config('jetstream.auth_session'), 'verified'])->group(function () {

    Route::middleware(AuthenticatedForUserMiddleware::class)->group(function () {
        Route::get('/profile', [ClientController::class, 'index'])->name('user.profile');
        Route::resource('wishlist', WishlistController::class)->except(['create', 'show', 'edit', 'update']);
    });

    // admin routes
    Route::middleware(AdminAuthenticatedMiddleware::class)->group(function () {
        // admin dashboard
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware(['permission:dashboard show']);

        // catalog module
        Route::resource('/categories', CategoryController::class)
            ->except(['show'])
            ->middleware(['permission:category create|category edit|category destroy']);

        Route::resource('/sub-categories', SubCategoryController::class)
            ->except(['show'])
            ->middleware(['permission:sub-category create|sub-category edit|sub-category destroy']);

        Route::resource('products', ProductController::class)
            ->middleware(['permission:product create|product edit|product destroy']);

        Route::resource('product-policies', ProductPolicyController::class)->except(['show'])
            ->middleware(['permission:product policy create|product policy edit|product policy destroy']);

        Route::get( '/download/invoice/{order}', [OrderController::class, 'invoice'])
            ->name('download.invoice');

        // order routes
        Route::resource( 'orders', OrderController::class)->except( 'create', 'store')
            ->middleware(['permission:order module']);

        // shipping manage
        Route::resource('shipping',  ShippingChargeController::class)
            ->except( 'show');

        // app manage
        Route::get('/app-credential', [AppCredentialController::class, 'index'])
            ->name('app.credential')
            ->middleware(['permission:permission module']);

        Route::get('/app-manage', [AppManageController::class, 'index'])
            ->name('app.manage')
            ->middleware(['permission:permission module']);

        Route::post('/app-manage', [AppManageController::class, 'store'])
            ->name('app.manage')
            ->middleware(['permission:permission module']);

        // role, permission and user
        Route::resource('permissions', PermissionController::class)
            ->except(['show'])
            ->middleware(['permission:permission create']);

        Route::resource('roles', RoleController::class)
            ->except(['show'])
            ->middleware(['permission:role create|role edit|role destroy']);

        Route::resource('users', UserController::class)
            ->except(['show'])
            ->middleware(['permission:user create|user edit|user destroy']);
    });

    // AJAX REQUEST
    // cj search product
    Route::post('/cj-search-product',[CjController::class, 'index'])->name('cj.product.search');
    // get sub category via category id
    Route::get('/get-sub-categories/{categoryId}', [SubCategoryController::class,  'getSubCategories']);
});

// application cache clear
Route::get('/app-cache-clear', function () {
    Artisan::call('optimize:clear');
    return back()->with('success', 'App cache is cleared');
})->name('app-cache.clear');
