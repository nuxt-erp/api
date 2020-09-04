<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;
use App\Models\Parameter;
use App\Models\User;

class ExpensesProposal extends ModelService
{
    protected $connection = 'tenant';
    
    protected $table = 'exp_ap_proposals';

    protected $dates = [
        'purchase_date',
    ];

    protected $fillable = [
        'expenses_category_id', 'author_id', 'item', 
        'reason', 'supplier_link', 'subtotal', 'hst', 
        'ship', 'total_cost', 'status_id', 'purchase_date'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'expenses_category_id'  => ['exists:exp_ap_categories,id'],
            'author_id'             => ['exists:users,id'], 
            'item'                  => ['string', 'max:255'],
            'supplier_link'         => ['nullable'],
            'status_id'             => ['exists:parameters,id'], 
            'purchase_date'         => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['expenses_category_id'][]    = 'required';
            $rules['item'][]                    = 'required';
            $rules['reason'][]                  = 'required';
            $rules['subtotal'][]                = 'required';
            $rules['hst'][]                     = 'required';
            $rules['ship'][]                    = 'required';
            $rules['total_cost'][]              = 'required';
        }

        return $rules;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'expenses_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    } 
    
    public function status()
    {
        return $this->belongsTo(Parameter::class, 'status_id');
    } 

    public function approvals()
    {
        return $this->hasMany(ExpensesApproval::class, 'expenses_proposal_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(ExpensesAttachment::class, 'expenses_proposal_id', 'id');
    }

    public function rule()
    {
        $rule = ExpensesRule::where('start_value', '<', $this->total_cost)->where('end_value', '>=', $this->total_cost)->orWhereNull('end_value')->orderBy('start_value')->first();
        return $rule;
    }

    public function approvers()
    {
        $category = $this->category;
        $rule = $this->rule();

        if ($category && $rule) {
            if($rule->team_leader_approval && $rule->director_approval){
                return $category->director->name . ', ' . $category->team_leader->name;
            } else if($rule->team_leader_approval && !$rule->director_approval){
                return $category->team_leader->name;
            } 
        } else {
            return '';
        }
    }

}