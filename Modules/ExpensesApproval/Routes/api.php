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
Route::get('expenses-approval/attachments/download/{file_name}', 'ExpensesAttachmentController@downloadFile');
Route::get('expenses-approval/attachments/view/{file_name}', 'ExpensesAttachmentController@viewFile');

Route::middleware('auth:api')->group(function () {

    // EXPENSES APPROVAL
    Route::group(['prefix' => 'expenses_approval'], function () {
        
        Route::resource('categories', 'CategoryController');
        Route::resource('approvals', 'ExpensesApprovalController');
        Route::resource('attachments', 'ExpensesAttachmentController');
        Route::resource('requests', 'ExpensesProposalController');
        Route::resource('rules', 'ExpensesRuleController');
        Route::resource('subcategories', 'SubcategoryController');

        // get Expenses Proposals
        Route::get('get_pending_requests', 'ExpensesProposalController@getPendingProposals');        
        Route::get('get_processed_requests', 'ExpensesProposalController@getProcessedProposals');

        // approve or disapprove Expenses Proposal
        Route::post('approve_request/{id}', 'ExpensesProposalController@approveProposal');    
        Route::post('disapprove_request/{id}', 'ExpensesProposalController@disapproveProposal');
        Route::post('cancel_request/{id}', 'ExpensesProposalController@cancelProposal');

        // save attachments to AWS S3
        Route::post('attachments/save', 'ExpensesAttachmentController@saveFile');
        Route::delete('attachments/delete/{file_name}', 'ExpensesAttachmentController@deleteFile');

    });    
});