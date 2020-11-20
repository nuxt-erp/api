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
Route::post('register', 'RegisterController@create');

Route::middleware('auth:api')->group(function () {

    // Route::get('dashboard', 'DashboardController@index');
    // Route::get('shopify_orders', 'ShopifyController@getShopifyOrder');
    Route::get('me', 'General\UserController@findMe');
    Route::post('profile', 'DashboardController@updateProfile');

    Route::group(['prefix' => 'general'], function () {
        Route::resource('configs', 'General\ConfigController');
        Route::resource('users', 'General\UserController');
        Route::resource('roles', 'General\RoleController');
        Route::resource('parameters', 'General\ParameterController');
        Route::resource('locations', 'General\LocationController');
        Route::resource('countries', 'General\CountryController');
        Route::resource('provinces', 'General\ProvinceController');
        Route::resource('suppliers', 'General\SupplierController');
        Route::resource('contacts', 'General\ContactController');
        Route::resource('customers', 'General\CustomerController');
        Route::resource('tax_rules', 'General\TaxRuleController');
        Route::get('tax_rule_constants', 'General\TaxRuleController@getStatuses');
        Route::get('tax_rule_scope_constants', 'General\TaxRuleController@getScopes');
        Route::get('tax_rule_computation_constants', 'General\TaxRuleController@getComputations');
        Route::resource('tax_rule_components', 'General\TaxRuleComponentController');
        Route::resource('tax_rule_scopes', 'General\TaxRuleScopeController');
        Route::resource('sales_reps', 'General\SalesRepController');
        Route::resource('parameter_types', 'General\ParameterTypeController');
        Route::resource('tags', 'General\TagController');
        Route::resource('customer_tags', 'General\CustomerTagController');
        Route::resource('settings_images', 'General\SettingsImagesController');

    });

});
