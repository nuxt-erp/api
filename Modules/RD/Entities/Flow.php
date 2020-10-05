<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Flow extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_flows';

    protected $fillable = ['phase_id', 'next_phase_id', 'start', 'end'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'phase_id'            => ['exists:tenant.rd_phases,id'],
            'next_phase_id'       => ['nullable', 'exists:tenant.rd_phases,id'],
            'start'               => ['nullable', 'boolean'],
            'end'                 => ['nullable', 'boolean'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['phase_id'][] = 'required';
            
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }
    public function phase()
    {
        return $this->belongsTo(Phase::class, 'phase_id', 'id');
    }
    public function next_phase()
    {
        return $this->belongsTo(Phase::class, 'next_phase_id', 'id');
    }
}
