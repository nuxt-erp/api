<?php

namespace App\Models;

class Parameter extends ModelService
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'value', 'order',
        'description', 'is_internal', 'is_default'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'name'          => ['max:255'],
            'value'         => ['max:255'],
            'description'   => ['nullable', 'max:255'],
            'order'         => ['nullable', 'integer'],
            'is_internal'   => ['nullable', 'integer'],
            'is_default'    => ['nullable', 'integer'],

        ];
        //create
        if (is_null($item)) {
            $rules['name'][]    = 'required';
            $rules['value'][]   = 'required';
        }

        return $rules;
    }

}
