<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {

    // INVENTORY
    Route::group(['prefix' => 'inventory'], function () {
        Route::resource('brands', 'BrandController');
        Route::resource('categories', 'CategoryController');

        Route::resource('attributes', 'AttributeController');
        Route::resource('products', 'ProductController');
        Route::resource('product_families', 'ProductFamilyController');
        Route::resource('availabilities', 'AvailabilityController');
    });
});
