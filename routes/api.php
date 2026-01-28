<?php

use App\Http\Controllers\WishlistController;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AppManageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\OrderController;
use \App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;

Route::middleware('auth:sanctum')->group(function () {
    // user
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/save-address', [UserController::class, 'saveAddress']);
    Route::post('/update-address', [UserController::class, 'updateAddress']);
    Route::get('/delete-address/{id}', [UserController::class, 'deleteAddress']);
    Route::post('/update-password', [UserController::class, 'updatePassword']);

    // product review
    Route::post('/create-review', [ReviewController::class, 'addReview']);
    // logout
    Route::post('/auth/logout',  [LoginController::class, 'logout']);
});

Route::get('/categories', [CategoryController::class, 'getAllCategories']);
Route::get('/exclusive-products', [ProductController::class, 'getExclusiveProducts']);
Route::get('/trending-products', [ProductController::class, 'getTrendingProducts']);
Route::get('/category-products', [ProductController::class, 'getCategoryProducts']);
Route::get('/sub-category-products', [ProductController::class, 'getSubCategoryProducts']);
Route::get('/search-products', [SearchController::class, 'getSearchProducts']);

// get products slug for ssg
Route::get('/products/slugs', [ProductController::class, 'getProductsSlugs']);

// auth
Route::post('/auth/register',  [RegistrationController::class, 'index']);
Route::get('/auth/verify-email',  [RegistrationController::class, 'verifyEmail']);
Route::get('/auth/resend-verification-email',  [RegistrationController::class, 'resendVerifyEmail']);
Route::post('/auth/login',  [LoginController::class, 'login']);

// social login
Route::post('/auth/social-login',  [SocialLoginController::class, 'socialLogin']);

Route::get('/auth/forgot-password',  [ForgotPasswordController::class, 'index']);
Route::post('/auth/reset-password',  [ForgotPasswordController::class, 'store']);

// refresh access_token
Route::post('/auth/refresh-token', [LoginController::class, 'refreshToken']);

// place order
Route::post('/place-order', [OrderController::class, 'placeOrder']);

// wishlist
Route::post('/wishlist-add', [WishlistController::class, 'store']);

Route::get('/app-info', [AppManageController::class, 'getAppInfo']);
Route::get('/product-details/{slug}', [ProductController::class, 'getProductDetail']);
Route::get('/related-products/{slug}', [ProductController::class, 'getRelatedProducts']);
