<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Currency extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'currencies';

    protected $fillable = [
        'name', 'code'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name'  => ['string', 'max:255'],
            'code'  => ['nullable', 'string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]    = 'unique:tenant.currencies';
            $rules['name'][]    = 'required';
            $rules['code'][]    = 'unique:tenant.currencies';
            $rules['code'][]    = 'required';
        }
        else {
            //update
            $rules['name'][]    = Rule::unique('tenant.currencies')->ignore($item->id);
            $rules['code'][]    = Rule::unique('tenant.currencies')->ignore($item->id);
        }

        return $rules;
    }

    public function getDescriptionAttribute()
    {
        return $this->code ? ($this->code . ' - ' . $this->name) : $this->name;
    }
}
