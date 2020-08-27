<?php

namespace Modules\ExpensesApproval\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Category extends ModelService
{
    protected $table = 'exp_ap_categories';

    protected $dates = [
        'finished_at',
    ];

    protected $fillable = [
        'name', 'team_leader_id', 'director_id',
        'is_finished', 'finished_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'              => ['string', 'max:255'],
            'team_leader_id'    => ['nullable', 'exists:users,id'], 
            'director_id'       => ['nullable', 'exists:users,id'], 
            'is_finished'       => ['nullable', 'boolean'],
            'finished_at'       => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]            = 'unique:exp_ap_categories';
            $rules['name'][]            = 'required';
            $rules['team_leader_id'][]  = 'required';
            $rules['director_id'][]     = 'required';

        } else {
            //update
            $rules['name'][] = Rule::unique('exp_ap_categories')->ignore($item->id);
        }

        return $rules;
    }
    
    public function team_leader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    public function director()
    {
        return $this->belongsTo(User::class, 'director_id');
    }
}
