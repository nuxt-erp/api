<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'company_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:45'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][] = 'required';
        }

        return $rules;
    }

}
