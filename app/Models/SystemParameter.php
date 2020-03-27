<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemParameter extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'param_name', 'param_value', 'is_default', 'description', 'company_id'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'param_name'  => ['string', 'max:100'],
            'param_value' => ['string', 'max:100'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['param_name'][]  = 'required';
            $rules['param_value'][] = 'required';
        }

        return $rules;
    }
}
