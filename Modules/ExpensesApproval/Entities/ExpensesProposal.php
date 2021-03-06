<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;
use App\Models\Parameter;
use App\Models\User;

class ExpensesProposal extends ModelService
{
    const PENDING       = 'pending_approval';
    const APPROVED      = 'approved';
    const PURCHASED     = 'purchased';
    const DENIED        = 'denied';

    protected $connection = 'tenant';

    protected $table = 'exp_ap_proposals';

    protected $dates = [
        'purchase_date',
    ];

    protected $fillable = [
        'expenses_category_id', 'author_id', 'item',
        'reason', 'supplier_link', 'subtotal', 'hst',
        'ship', 'total_cost', 'status', 'purchase_date',
        'subcategory_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'expenses_category_id'  => ['exists:tenant.exp_ap_categories,id'],
            'author_id'             => ['exists:public.users,id'],
            'supplier_link'         => ['nullable'],
            'purchase_date'         => ['nullable', 'date'],
            'subcategory_id'        => ['nullable'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['expenses_category_id'][]    = 'required';
            $rules['subcategory_id'][]          = 'required';
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

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
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

    public function sponsor_approval()
    {
        return ExpensesApproval::where('expenses_proposal_id', $this->id)->where('approver_id', $this->category->sponsor_id)->first();
    }

    public function team_leader_approval()
    {
        return ExpensesApproval::where('expenses_proposal_id', $this->id)->where('approver_id', $this->category->team_leader_id)->first();

    }

}
