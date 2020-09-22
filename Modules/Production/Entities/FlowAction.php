<?php

namespace Modules\Production\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class FlowAction extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'prod_flow_actions';

    protected $fillable = ['flow_id', 'phase_id' , 'destination_phase_id',
                            'destination_location_id', 'name'
        ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'flow_id'                    => ['exists:tenant.prod_flows,id'],
            'phase_id'                   => ['exists:tenant.prod_phases,id'],
            'destination_phase_id'       => ['nullable', 'exists:tenant.prod_phases,id'],
            'destination_location_id'    => ['nullable', 'exists:tenant.locations,id'],
            'name'                       => ['string', 'max:255'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['flow_id'][]    = 'required';
            $rules['phase_id'][]    = 'required';
            $rules['name'][]    = 'required';
            $rules['name'][]    = 'unique:tenant.prod_flows';

        }


        return $rules;
    }
    public function flow_id()
    {
        return $this->belongsTo(Flow::class, 'flow_id');
    }
    public function previous_phase_id()
    {
        return $this->belongsTo(Phase::class, 'previous_phase_id');
    }
    public function destination_phase_id()
    {
        return $this->belongsTo(Phase::class, 'destination_phase_id');
    }
    public function destination_location_id()
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }
}
