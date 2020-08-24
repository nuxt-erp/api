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
        Route::resource('product_attributes', 'ProductAttributeController');
        //Route::get('product_logs/get_log', 'ProductLogController@getLog'); //@todo review this
        Route::resource('product_logs', 'ProductLogController');

        Route::resource('product_families', 'ProductFamilyController');

        //Route::get('families/remove/{id?}', 'ProductFamilyController@remove'); //@todo review this
        //Route::get('families/get_products', 'ProductFamilyController@getListProducts'); //@todo review this
        Route::resource('families', 'FamilyController');
        Route::resource('family_attributes', 'ProductFamilyAttributeController');
        Route::resource('availabilities', 'AvailabilityController');
        Route::resource('specifications', 'SpecificationController');
        Route::resource('subspecifications', 'SubSpecificationController');

    });
});
