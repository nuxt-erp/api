<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Country extends ModelService
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'unique:tenant.countries';
            $rules['name'][] = 'required';
        }
        else {
            //update
            $rules['name'][]    = Rule::unique('tenant.countries')->ignore($item->id);
        }
        return $rules;
    }

}
