<?php

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/categories', [CategoryController::class, 'getAllCategories']);
Route::get('/exclusive-products', [ProductController::class, 'getAllProducts']);
Route::get('/trending-products', [ProductController::class, 'getAllProducts']);
Route::get('/category-products', [ProductController::class, 'getCategoryProducts']);
Route::get('/sub-category-products/{slug}', [ProductController::class, 'getSubCategoryProducts']);
Route::get('/search-products', [SearchController::class, 'getSearchProducts']);


// auth
Route::post('/auth/register',  [RegistrationController::class, 'index']);
Route::get('/auth/verify-email',  [RegistrationController::class, 'verifyEmail']);
Route::get('/auth/resend-verification-email',  [RegistrationController::class, 'resendVerifyEmail']);
Route::post('/auth/login',  [LoginController::class, 'login']);
Route::get('/auth/forgot-password',  [ForgotPasswordController::class, 'index']);
Route::post('/auth/reset-password',  [ForgotPasswordController::class, 'store']);

// social login
Route::post('/auth/social-login',  [SocialLoginController::class, 'socialLogin']);

Route::get('/app-info', [AppManageController::class, 'getAppInfo']);
Route::get('/product-details/{slug}', [ProductController::class, 'getProductDetail']);
