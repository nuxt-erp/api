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
        Route::resource('stock_locator', 'StockLocatorController');
        Route::resource('measure', 'MeasureController');
        Route::resource('products', 'ProductController');
        Route::resource('product_attributes', 'ProductAttributeController');
        Route::resource('product_logs', 'ProductLogController');

        //Route::resource('product_families', 'ProductFamilyController');
        //Route::get('families/remove/{id?}', 'ProductFamilyController@remove'); //@todo review this
        //Route::get('families/get_products', 'ProductFamilyController@getListProducts'); //@todo review this
        Route::resource('families', 'FamilyController');
        Route::resource('family_attributes', 'ProductFamilyAttributeController');
        Route::resource('availabilities', 'AvailabilityController');
        //Route::resource('specifications', 'SpecificationController');
        //Route::resource('subspecifications', 'SubSpecificationController');
        
    });

    Route::group(['prefix' => 'import'], function () {
        Route::get('brands', 'ImportController@dearSyncBrands');
        Route::get('categories', 'ImportController@dearSyncCategories');
        Route::get('suppliers', 'ImportController@dearSyncSuppliers');
        Route::get('locations', 'ImportController@dearSyncLocations');
        Route::get('products', 'ImportController@dearSyncProducts');
        Route::get('availabilities', 'ImportController@dearSyncAvailabilities');
        Route::get('products/{sku}', 'ImportController@syncProduct'); // sync in DEAR only one product
        Route::post('xls/stock_count', 'ImportController@xlsInsertStock');
    });

});