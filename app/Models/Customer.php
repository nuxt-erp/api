<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'company_id', 'name', 'address1', 'address2', 'email', 'country_id', 'province_id', 'city', 'postal_code', 'phone_number', 'notes'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:60'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'required';
        }

        return $rules;
    }

}
