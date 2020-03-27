<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;

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
            $rules['name'][] = 'required';
        }

        return $rules;
    }

}
