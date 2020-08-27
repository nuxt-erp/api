<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;

class ExpensesApproval extends ModelService
{
    protected $table = 'exp_ap_approvals';
    
    protected $fillable = [
        'expenses_proposal_id', 'approver_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'expenses_proposal_id'   => ['exists:exp_ap_expenses_proposals,id'], 
            'approver_id'           => ['exists:users,id'], 
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['expenses_proposal_id'][]     = 'required';
            $rules['approver_id'][]             = 'required';

        }

        return $rules;
    }

    public function expenses_proposal()
    {
        return $this->belongsTo(ExpensesProposal::class, 'expense_proposal_id');
    }
    
    public function team_leader()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
