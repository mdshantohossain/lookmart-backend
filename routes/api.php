<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AppManageController;
use App\Http\Controllers\SearchController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/get-all-categories', [CategoryController::class, 'getAllCategories']);
Route::get('/get-exclusive-products', [ProductController::class, 'getAllProducts']);
Route::get('/get-trending-products', [ProductController::class, 'getAllProducts']);
Route::get('/category-products/{slug}', [ProductController::class, 'getCategoryProducts']);
Route::get('/sub-category-products/{slug}', [ProductController::class, 'getSubCategoryProducts']);
Route::get('/search-products', [SearchController::class, 'getSearchProducts']);

Route::post('/login',  [LoginController::class, 'index']);
Route::post('/registration',  [RegistrationController::class, 'index']);

Route::get('/app-info', [AppManageController::class, 'getAppInfo']);
Route::get('/product-details/{slug}', [ProductController::class, 'getProductDetail']);
