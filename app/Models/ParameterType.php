<?php

namespace App\Models;

class ParameterType extends ModelService
{
    protected $connection = 'tenant';
    protected $table = 'parameter_types';

    protected $fillable = [
        'value', 'description'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'value'         => ['max:255'],
            'description'   => ['max:255'],           
        ];
        //create
        if (is_null($item)) {
            $rules['value'][]    = 'required';
            $rules['description'][]   = 'required';
        }

        return $rules;
    }    
}
