<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Tag extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'tags';

    protected $fillable = [
        'name', 'type'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'type' => ['string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]        = 'required';
            $rules['type'][]        = 'required';
        } else {
            //update
        }

        return $rules;
    }
}