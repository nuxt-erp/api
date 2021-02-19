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
        Route::resource('flavors', 'FlavorController');
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
        Route::resource('product_custom_prices', 'ProductCustomPriceController');
        Route::resource('product_import_settings', 'ProductImportSettingsController');
        Route::resource('availability_import_settings', 'AvailabilityImportSettingsController');
        Route::resource('bin_import_settings', 'BinImportSettingsController');
        Route::resource('product_tags', 'ProductTagController');
        Route::resource('family_attributes', 'FamilyAttributeController');


        Route::resource('product_images', 'ProductImagesController');
        Route::get('carrier_products', 'ProductController@findCarriers');
        Route::get('raw_material_products', 'ProductController@findRawMaterials');
        Route::get('find_sku/{sku} ', 'ProductController@findBySKU');

        Route::resource('location_bins', 'LocationBinController');
        Route::resource('receiving', 'ReceivingController');
        Route::resource('receiving_details', 'ReceivingDetailController');
        Route::get('receiving/finish/{id?}', 'ReceivingController@finish'); // ADJUST AND FINISH RECEIVING
        Route::post('receiving/po_allocate', 'ReceivingController@poAllocation'); // ALLOCATE QTY RECEIVED TO PURCHASES

        Route::resource('stock_count_filters', 'StockCountFilterController');
        Route::resource('stock_adjustments', 'StockAdjustmentController');
        Route::resource('stock_adjustment_details', 'StockAdjustmentDetailController');
        Route::get('stock_adjustment_export/{id?}', 'StockAdjustmentController@export');
        Route::resource('stock_count', 'StockCountController');
        Route::get('stock_count_statuses', 'StockCountController@getStatuses');
        Route::resource('stock_count_details', 'StockCountDetailController');
        Route::get('stock_count/finish/{id?}', 'StockCountController@finish'); // ADJUST AND FINISH STOCK TAKE
        Route::get('stock_count/export/{id?}', 'StockCountController@export');
        Route::get('stock_on_hand', 'AvailabilityController@stockOnHand');
        Route::get('stock_count_data', 'ProductController@stockCountData');
        Route::get('start_stock_count', 'StockCountController@start');
        Route::get('start_stock_count_mobile', 'StockCountController@startMobile');

        Route::resource('transfers', 'TransferController');
        Route::get('transfer/packingSlip/{id?}', 'TransferController@exportPackingSlip'); // EXPORT PACKING SLIP
        Route::resource('transfer_details', 'TransferDetailsController');
        //Route::resource('product_families', 'ProductFamilyController');
        //Route::get('families/remove/{id?}', 'ProductFamilyController@remove'); //@todo review this
        //Route::get('families/get_products', 'ProductFamilyController@getListProducts'); //@todo review this
        Route::get('getListProducts/{id?}', 'FamilyController@getListProducts');
        Route::resource('families', 'FamilyController');

        Route::resource('availabilities', 'AvailabilityController');
        Route::resource('family_attributes', 'FamilyAttributeController');

        Route::get('sku_suppliers', 'ProductSuppliersController@skuSuppliers');
        Route::resource('price_tiers', 'PriceTierController');
        Route::post('price_tiers/apply', 'PriceTierController@applyChanges');
        Route::resource('price_tier_items', 'PriceTierItemsController');


        //Route::resource('specifications', 'SpecificationController');
        //Route::resource('subspecifications', 'SubSpecificationController');
        Route::resource('customer_discounts', 'CustomerDiscountController');
        // XLS IMPORT
        Route::post('products_import/{type}', 'ImportController@productsImport');
        Route::post('bins_import/{type}', 'ImportController@binsImport');
        Route::post('availabilities_import/{type}', 'ImportController@availabilityImport');

        // Availabilities
        Route::resource('availabilities', 'AvailabilityController');
        Route::get('product_availabilities', 'AvailabilityController@productAvailabilities');
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
        Route::post('xls/stock_adjustments', 'ImportController@xlsAdjustStock');
    });
});
