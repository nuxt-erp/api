<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;
use App\Models\User;
use Illuminate\Validation\Rule;

class Category extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'exp_ap_categories';

    protected $dates = [
        'finished_at',
    ];

    protected $fillable = [
        'name', 'team_leader_id', 'sponsor_id',
        'buyer_id', 'is_finished', 'finished_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'              => ['string', 'max:255'],
            'team_leader_id'    => ['nullable', 'exists:public.users,id'],
            'sponsor_id'       => ['nullable', 'exists:public.users,id'],
            'buyer_id'          => ['nullable', 'exists:public.users,id'],
            'is_finished'       => ['nullable', 'boolean'],
            'finished_at'       => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {
            // $rules['name'][]            = 'unique:tenant.exp_ap_categories,id';
            $rules['name'][]            = 'required';
            $rules['team_leader_id'][]  = 'required';
            $rules['sponsor_id'][]     = 'required';
            $rules['buyer_id'][]        = 'required';

        } else {
            //update
            // $rules['name'][] = Rule::unique('tenant.exp_ap_categories')->ignore($item->id);
        }

        return $rules;
    }

    public function team_leader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
