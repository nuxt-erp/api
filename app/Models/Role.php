<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Role extends ModelService
{

    protected $connection = 'tenant';

    protected $fillable = [
        'name', 'code'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'name'          => ['string', 'max:255'],
            'code'          => ['string', 'max:255'],

        ];
        //create
        if (is_null($item)) {
            $rules['name'][]    = 'required';
            $rules['code'][]    = 'required';
            $rules['code'][]    = 'unique:tenant.roles';
        } else {
            //update
            $rules['code'][]    = Rule::unique('tenant.roles')->ignore($item->id);
        }

        return $rules;
    }
}
