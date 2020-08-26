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
    Route::group(['prefix' => 'sales'], function () {
        Route::resource('sales', 'SaleController');
        Route::resource('sale_details', 'SaleDetailsController');
        Route::get('shopify_import', 'SaleController@importShopify');
    });

});
