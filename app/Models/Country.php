<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Country extends ModelService
{
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
            $rules['name'][] = 'unique:countries';
            $rules['name'][] = 'required';
        }
        else {
            //update
            $rules['name'][]    = Rule::unique('countries')->ignore($item->id);
        }
        return $rules;
    }

}
