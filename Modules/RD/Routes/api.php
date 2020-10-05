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
    Route::group(['prefix' => 'rd'], function () {
        Route::resource('recipes', 'RecipeController');
        Route::resource('recipe_items', 'RecipeItemsController');
        Route::resource('recipe_attributes', 'RecipeAttributesController');

        Route::resource('recipe_proposals', 'RecipeProposalsController');
        Route::resource('recipe_proposal_items', 'RecipeProposalItemsController');

        Route::resource('projects', 'ProjectController');
        Route::resource('project_samples', 'ProjectSamplesController');
        Route::resource('project_sample_attributes', 'ProjectSampleAttributesController');

        Route::resource('project_logs', 'ProjectLogsController');
        Route::resource('project_sample_logs', 'ProjectSampleLogsController');

        Route::resource('phases', 'PhaseController');
        Route::resource('flows', 'FlowController');
        Route::resource('phase_roles', 'PhaseRoleController');


        Route::get('project_statuses', 'ProjectController@getStatuses');
        Route::get('project_samples_statuses', 'ProjectSamplesController@getSampleStatuses');

        Route::get('sample_flows', 'FlowController@getSampleFlows');
    });
});
