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

    // EXPENSES APPROVAL
    Route::group(['prefix' => 'expenses_approval'], function () {
        
        Route::resource('categories', 'CategoryController');
        Route::resource('approvals', 'ExpensesApprovalController');
        Route::resource('attachments', 'ExpensesAttachmentController');
        Route::resource('proposals', 'ExpensesProposalController');
        Route::resource('rules', 'ExpensesRuleController');        
    });    
});