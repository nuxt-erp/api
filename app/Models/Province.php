<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'short_name', 'country_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'          => ['string', 'max:255'],
            'short_name'    => ['string', 'max:2']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]        = 'required';
            $rules['country_id'][]  = 'required';
        }

        return $rules;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
