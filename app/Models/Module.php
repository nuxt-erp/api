<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Module extends ModelService
{
    protected $dates = [
        'enabled_at', 'disabled_at'
    ];

    protected $fillable = [
        'name', 'is_enabled'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'unique:modules';
            $rules['name'][] = 'required';
        }
        else {
            //update
            $rules['name'][]    = Rule::unique('modules')->ignore($item->id);
        }
        return $rules;
    }

}
