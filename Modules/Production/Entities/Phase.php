<?php

namespace Modules\Production\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Phase extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'prod_phases';

    protected $fillable = ['operation_id', 'name', 'start', 'end'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'name'                       => ['string', 'max:255'],
            'operation_id'               => ['exists:tenant.prod_operations,id'],
            'will_start_counter'                      => ['nullable', 'boolean'],
            'will_end_counter'                        => ['nullable', 'boolean'],

        ];

        // rules when creating the item
        if (is_null($item)) {
            //$rules['field'][] = 'required';
            $rules['name'][] = 'required';
            $rules['name'][]    = 'unique:tenant.prod_machines';
        }
        

        return $rules;
    }
    public function operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }

}
