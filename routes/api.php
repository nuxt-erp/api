<?php

Route::post('login', 'LoginController@issueToken');

Route::middleware('auth:api')->group(function () {

    Route::get('dashboard', 'DashboardController@index');
    Route::get('shopify_orders', 'ShopifyController@getShopifyOrder');
    Route::get('me', 'Admin\UserController@findMe');

    // BASIC ADMIN
    Route::group(['prefix' => 'admin'], function () {
        Route::resource('users', 'Admin\UserController');
        Route::resource('roles', 'Admin\RoleController');
        Route::resource('employees', 'Admin\EmployeeController');
        Route::resource('locations', 'Admin\LocationController');
        Route::resource('countries', 'Admin\CountryController');
        Route::resource('provinces', 'Admin\ProvinceController');
        Route::resource('parameters', 'Admin\SystemParameterController');
    });

    // PARAMETERS
    Route::get('admin/getCountTypeList', 'Admin\SystemParameterController@getCountTypeList');

    // INVENTORY
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
        Route::resource('transfers', 'Inventory\TransferController');
        Route::resource('product_logs', 'Inventory\ProductLogController');
        Route::resource('transfer_details', 'Inventory\TransferDetailsController');
        Route::get('product_availabilities', 'Inventory\AvailabilityController@productAvailabilities'); // STOCK TAKE COUNT - USE TO BRING PRODUCTS AND STOCK AVAILABILITY
        Route::get('stocktake/finish/{id?}', 'Inventory\StockTakeController@finish'); // ADJUST AND FINISH STOCK TAKE
        Route::get('transfer/packingSlip/{id?}', 'Inventory\TransferController@exportPackingSlip'); // EXPORT PACKING SLIP
        Route::get('product_families/remove/{id?}', 'Inventory\ProductFamilyController@remove');
        Route::get('transfer/remove/{id?}', 'Inventory\TransferController@remove');
    });

    // PURCHASES
    Route::group(['prefix' => 'purchases'], function () {
        Route::resource('suppliers', 'Purchases\SupplierController');
        Route::resource('purchases', 'Purchases\PurchaseController');
        Route::resource('purchase_details', 'Purchases\PurchaseDetailsController');
        Route::get('purchase/remove/{id?}', 'Purchases\PurchaseController@remove');
    });

    // SALES
    Route::group(['prefix' => 'sales'], function () {
        Route::resource('customers', 'Sales\CustomerController');
        Route::resource('sales', 'Sales\SaleController');
        Route::resource('sale_details', 'Sales\SaleDetailsController');
        Route::get('sale/remove/{id?}', 'Sales\SaleController@remove');
        Route::get('shopify_orders', 'Sales\SaleController@importShopify');
    });

    // PRODUCT FAMILY
    Route::get('product_families/get_products', 'Inventory\ProductFamilyController@getListProducts'); // LIST ALL PRODUCTS FROM CURRENT FAMILY

    // IMPORTS
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
