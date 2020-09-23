<?php

use Illuminate\Http\Request;

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
    Route::group(['prefix' => 'production'], function () {

        // CRUD manager
        Route::resource('operations', 'OperationController');
        Route::resource('operation_results', 'OperationResultController');
        Route::resource('phases', 'PhaseController');
        Route::resource('flows', 'FlowController');
        Route::resource('flow_actions', 'FlowActionController');
        Route::resource('actions', 'ActionController');
        Route::resource('machines', 'MachineController');
        Route::resource('productions', 'ProductionController');

    });
});
