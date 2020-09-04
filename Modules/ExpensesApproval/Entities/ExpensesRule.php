<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ExpensesRule extends ModelService
{
    protected $connection = 'tenant';
    
    protected $table = 'exp_ap_rules';

    protected $fillable = [
        'name', 'team_leader_approval', 'director_approval',
        'start_value', 'end_value'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'                  => ['string', 'max:255'],
            'team_leader_approval'  => ['nullable', 'boolean'],
            'director_approval'     => ['nullable', 'boolean'],            
            'end_value'             => ['nullable'],
        ];

        // CREATE
        if (is_null($item)) {
            $rules['name'][]                    = 'unique:tenant.exp_ap_rules';
            $rules['name'][]                    = 'required';
            $rules['team_leader_approval'][]    = 'required';
            $rules['director_approval'][]       = 'required';
        } else {
            //update
            $rules['name'][] = Rule::unique('tenant.exp_ap_rules')->ignore($item->id);
        }

        return $rules;
    }   
}
