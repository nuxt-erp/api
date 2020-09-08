<?php

namespace Modules\Production\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Flow extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'prod_flows';

    protected $fillable = ['name', 'first_phase_id'];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'              => ['string', 'max:255'],
            'first_phase_id'    => ['nullable', 'exists:phases,id']

        ];
        //create
        if (is_null($item)) {
            $rules['name'][]    = 'unique:tenant.prod_flows';
        } else {
            //update
            $rules['name'][]    = Rule::unique('tenant.prod_flows')->ignore($item->id);
        }

        return $rules;
    }
    public function first_phase()
    {
        return $this->belongsTo(Phase::class, 'first_phase_id');
    }
}
