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

Route::get('product_images/{path}', 'ProductImagesController@getImage');

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
        Route::resource('product_suppliers', 'ProductSuppliersController');
        Route::resource('product_supplier_locations', 'ProductSupplierLocationsController');
        Route::resource('product_reorder_levels', 'ProductReorderLevelController');
        Route::resource('product_promos', 'ProductPromoController');

        Route::resource('product_images', 'ProductImagesController');

        Route::resource('stock_count', 'StockCountController');
        Route::resource('stock_count_details', 'StockCountDetailController');
        Route::get('stock_count/finish/{id?}', 'StockCountController@finish'); // ADJUST AND FINISH STOCK TAKE
        Route::resource('transfers', 'TransferController');
        Route::get('transfer/remove/{id?}', 'TransferController@remove');
        Route::get('transfer/packingSlip/{id?}', 'TransferController@exportPackingSlip'); // EXPORT PACKING SLIP
        Route::resource('transfer_details', 'TransferDetailsController');
        //Route::resource('product_families', 'ProductFamilyController');
        //Route::get('families/remove/{id?}', 'ProductFamilyController@remove'); //@todo review this
        //Route::get('families/get_products', 'ProductFamilyController@getListProducts'); //@todo review this
        Route::get('getListProducts/{id?}', 'FamilyController@getListProducts');
        Route::resource('families', 'FamilyController');
        Route::resource('family_attributes', 'FamilyAttributeController');
        Route::resource('availabilities', 'AvailabilityController');
        Route::get('products_availabilities', 'ProductController@productAvailabilities');
        //Route::resource('specifications', 'SpecificationController');
        //Route::resource('subspecifications', 'SubSpecificationController');
        Route::resource('customer_discounts', 'CustomerDiscountController');



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
