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

Route::post('login', 'LoginController@issueToken');

Route::middleware('auth:api')->group(function () {

    // Route::get('dashboard', 'DashboardController@index');
    // Route::get('shopify_orders', 'ShopifyController@getShopifyOrder');
    Route::get('me', 'General\UserController@findMe');

    Route::group(['prefix' => 'general'], function () {
        Route::resource('configs', 'General\ConfigController');
        Route::resource('users', 'General\UserController');
        Route::resource('roles', 'General\RoleController');
        Route::resource('parameters', 'General\ParameterController');
        Route::resource('locations', 'General\LocationController');
        Route::resource('countries', 'General\CountryController');
        Route::resource('provinces', 'General\ProvinceController');
        Route::resource('suppliers', 'General\SupplierController');
    });



});
