<?php

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


    Route::get('dashboard', 'DashboardController@index');
    Route::get('shopify_orders', 'ShopifyController@getShopifyOrder');

    Route::get('me', 'Admin\UserController@findMe');

    // BASIC ADMIN
    Route::group(['prefix' => 'admin'], function () {
        Route::resource('users', 'Admin\UserController');
        Route::resource('roles', 'Admin\RoleController');
        Route::resource('actions', 'Admin\ActionController');
        Route::resource('employees', 'Admin\EmployeeController');
        Route::resource('brands', 'Admin\BrandController');
        Route::resource('attributes', 'Admin\AttributeController');
        Route::resource('categories', 'Admin\CategoryController');
        Route::resource('specifications', 'Admin\SpecificationController');
        Route::resource('subspecifications', 'Admin\SubSpecificationController');
        Route::resource('countries', 'Admin\CountryController');
        Route::resource('provinces', 'Admin\ProvinceController');
        Route::resource('suppliers', 'Admin\SupplierController');
        Route::resource('parameters', 'Admin\SystemParameterController');
        Route::resource('products', 'Admin\ProductController');
        Route::resource('product_attributes', 'Admin\ProductAttributeController');
        Route::resource('product_specifications', 'Admin\ProductSpecificationController');
    });

});
