<?php

namespace Modules\ExpensesApproval\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ExpensesAttachment extends Model
{
    protected $connection = 'tenant';
    
    protected $table = 'exp_ap_attachments';
    
    protected $fillable = [
        'expenses_proposal_id', 'file_name'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'expenses_proposal_id'   => ['exists:exp_ap_expenses_proposals,id'], 
            'file_name'             => ['string', 'max:255'], 
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['expenses_proposal_id'][]    = 'required';
            $rules['file_name'][]               = 'required';
        } 

        return $rules;
    }

    public function expenses_proposal()
    {
        return $this->belongsTo(ExpensesProposal::class, 'expenses_proposal_id');
    }  
}