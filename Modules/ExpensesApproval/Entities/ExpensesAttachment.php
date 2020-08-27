<?php

namespace Modules\ExpensesApproval\Entities;

use Illuminate\Database\Eloquent\Model;

class ExpensesAttachment extends Model
{
    protected $table = 'exp_ap_attachments';
    
    protected $fillable = [
        'expense_proposal_id', 'file_name', 'attachment_url'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'expense_proposal_id'   => ['exists:exp_ap_expenses_proposals,id'], 
            'file_name'             => ['string', 'max:255'], 
            'file_url'                   => ['string', 'max:255'], 

        ];

        // CREATE
        if (is_null($item))
        {
            $rules['expense_proposal_id'][]     = 'required';
            $rules['file_name'][]               = 'required';
            $rules['file_url'][]                = 'required';

        }

        return $rules;
    }

    public function expenses_proposal()
    {
        return $this->belongsTo(ExpensesProposal::class, 'expense_proposal_id');
    }  
}