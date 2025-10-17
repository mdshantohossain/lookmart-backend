<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Website\OrderController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Website\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AppCredentialController;
use App\Http\Controllers\ShippingChargeController;
use App\Http\Controllers\AppManageController;
use App\Http\Controllers\CjController;
use App\Http\Controllers\ProductPolicyController;

// middleware
use App\Http\Middleware\AuthenticatedForUserMiddleware;
use App\Http\Middleware\AdminAuthenticatedMiddleware;

// custom login route
Route::post('/login', [LoginController::class, 'login'])->name('login');

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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    Route::middleware(AuthenticatedForUserMiddleware::class)->group(function () {
        Route::get('/profile', [UserController::class, 'index'])->name('user.profile');
        Route::resource('wishlist', WishlistController::class)->except(['create', 'show', 'edit', 'update']);
    });

    // admin routes
    Route::middleware(AdminAuthenticatedMiddleware::class)->group(function () {
        // admin dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // product module
        Route::resource('/categories', CategoryController::class)->except(['show']);
        Route::resource('/sub-categories', SubCategoryController::class)->except(['show']);
        Route::get('/get-sub-categories/{categoryId}', [SubCategoryController::class,  'getSubCategories']);
        Route::resource('products', ProductController::class);
        Route::resource('product-policies', ProductPolicyController::class)->except(['show']);

        Route::get( '/download/invoice/{order}', [OrderController::class, 'invoice'])->name('download.invoice');

        // order routes
        Route::resource( '/order', OrderController::class)->except( 'create', 'store');

        // shipping manage
        Route::resource('shipping',  ShippingChargeController::class)->except( 'show');

        // app manage
        Route::get('/app-credential', [AppCredentialController::class, 'index'])->name('app.credential');
        Route::get('/app-manage', [AppManageController::class, 'index'])->name('app.manage');
        Route::post('/app-manage', [AppManageController::class, 'store'])->name('app.manage');
    });

    // cj search product
    Route::post('/cj-search-product',[CjController::class, 'index'])->name('cj.product.search');
});

// application cache clear
Route::get('/app-cache-clear', function () {
    Artisan::call('optimize:clear');
    return back()->with('success', 'App cache is cleared');
})->name('app-cache.clear');
