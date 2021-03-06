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

    // module prefix
    Route::group(['prefix' => 'purchases'], function () {
        Route::resource('purchases', 'PurchaseController');
        Route::resource('purchase_details', 'PurchaseDetailController');
        Route::resource('purchase_tracking_numbers', 'PurchaseTrackingNumberController');
        Route::get('remove_item/{id?}', 'PurchaseDetailController@remove');
        Route::get('purchase/delete/{id?}', 'PurchaseController@destroy');
        Route::get('get_po_number', 'PurchaseController@getNextPONumber');
        Route::get('check_po_number/{po_number}', 'PurchaseController@checkPoNumber');
        Route::post('check_po_items', 'PurchaseController@checkPoItems');
        Route::get('purchase_statuses', 'PurchaseController@getStatuses');
        Route::get('clone_purchase/{id?}', 'PurchaseController@clone');


    });
    Route::group(['prefix' => 'import'], function () {
        Route::post('xls/purchase', 'ImportController@xlsInsertPurchase');
    });
});
