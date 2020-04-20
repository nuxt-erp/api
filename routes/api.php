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
        // Route::resource('actions', 'Admin\ActionController');
        Route::resource('employees', 'Admin\EmployeeController');
        Route::resource('locations', 'Admin\LocationController');
        Route::resource('countries', 'Admin\CountryController');
        Route::resource('provinces', 'Admin\ProvinceController');
        Route::resource('suppliers', 'Admin\SupplierController');
        Route::resource('parameters', 'Admin\SystemParameterController');
    });

    // PARAMETERS
    Route::get('admin/getCountTypeList', 'Admin\SystemParameterController@getCountTypeList');


    // BASIC ADMIN
    Route::group(['prefix' => 'inventory'], function () {
        Route::resource('brands', 'Inventory\BrandController');
        Route::resource('attributes', 'Inventory\AttributeController');
        Route::resource('categories', 'Inventory\CategoryController');
        Route::resource('specifications', 'Inventory\SpecificationController');
        Route::resource('subspecifications', 'Inventory\SubSpecificationController');
        Route::resource('products', 'Inventory\ProductController');
        Route::resource('product_attributes', 'Inventory\ProductAttributeController');
        Route::resource('product_families', 'Inventory\ProductFamilyController');
        Route::resource('product_family_attributes', 'Inventory\ProductFamilyAttributeController');
        Route::resource('product_specifications', 'Inventory\ProductSpecificationController');
        Route::resource('availabilities', 'Inventory\AvailabilityController');
        Route::resource('stocktake', 'Inventory\StockTakeController');
        Route::resource('stocktake_details', 'Inventory\StockTakeDetailsController');
    });

    // STOCK TAKE COUNT - USE TO BRING PRODUCTS AND STOCK AVAILABILITY
    Route::get('inventory/product_availabilities', 'Inventory\ProductController@productAvailabilities');

    // PRODUCT FAMILY
    Route::get('product_families/get_products', 'Inventory\ProductFamilyController@getListProducts'); // LIST ALL PRODUCTS FROM CURRENT FAMILY


     // IMPORTS
    Route::group(['prefix' => 'import'], function () {
        Route::get('brands', 'ImportController@dearSyncBrands');
        Route::get('categories', 'ImportController@dearSyncCategories');
        Route::get('locations', 'ImportController@dearSyncLocations');
        Route::get('products', 'ImportController@dearSyncProducts');
        Route::get('availabilities', 'ImportController@dearSyncAvailabilities');
        Route::get('products/{sku}', 'ImportController@syncProduct'); // sync in DEAR only one product
    });


});
