<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;

class ExpensesProposal extends ModelService
{
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
            $rules['author_id'][]               = 'required';
            $rules['item'][]                    = 'required';
            $rules['reason'][]                  = 'required';
            $rules['subtotal'][]                = 'required';
            $rules['hst'][]                     = 'required';
            $rules['ship'][]                    = 'required';
            $rules['total_cost'][]              = 'required';
            $rules['status_id'][]               = 'required';
        }

        return $rules;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'expenses_category_id');
    }

    public function author()
    {
        return $this->belongsTo(Category::class, 'author_id');
    } 
    
    public function status()
    {
        return $this->belongsTo(Parameter::class, 'status_id');
    } 

    public function approvals()
    {
        return $this->hasMany(ExpensesApproval::class, 'expense_proposal_id', 'id');
    }

    public function attachments()
    {
        return $this->hasMany(ExpensesAttachments::class, 'expense_proposal_id', 'id');
    }

    public function approvers()
    {
        $category = Category::where('id', 'expenses_category_id')->first();
        if ($category) {
            return $category->director->name . ', ' . $category->team_leader->name;
        } else {
            return '';
        }
    }

}