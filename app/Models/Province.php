<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Province extends ModelService
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name', 'code', 'country_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:255'],
            'code'          => ['string', 'max:2'],
            'country_id'    => ['exists:countries,id'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]        = 'required';
            $rules['code'][]        = 'required';
            $rules['name'][]        = 'unique:provinces';
            $rules['code'][]        = 'unique:provinces';
            $rules['country_id'][]  = 'required';
        }
        else{
            $rules['name'][]    = Rule::unique('provinces')->ignore($item->id);
            $rules['code'][]    = Rule::unique('provinces')->ignore($item->id);
        }

        return $rules;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
