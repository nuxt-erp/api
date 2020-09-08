<?php

namespace Modules\Production\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Machine extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'prod_machines';

    protected $fillable = ['flow_id', 'working_hours',
                            'capacity', 'name'
        ];
    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'flow_id'                    => ['nullable', 'exists:tenant.prod_flows, id'],
            'capacity'                   => ['nullable', 'integer'],
            'working_hours'              => ['nullable', 'integer'],
            'name'                       => ['string', 'max:50'],
        ];
        // rules when creating the item
        if (is_null($item)) {
            $rules['name'][] = 'required';
            $rules['name'][]    = 'unique:tenant.prod_machines';
        }

        return $rules;
    }
    public function flow_id()
    {
        return $this->belongsTo(Flow::class, 'flow_id');
    }
}
