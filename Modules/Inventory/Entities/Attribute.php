<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;

class Attribute extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_attributes';

    protected $dates = [
        'disabled_at',
    ];

    protected $fillable = [
        'code', 'name', 'is_enabled',
        'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'code'          => ['string', 'max:255'],
            'name'          => ['nullable', 'string', 'max:255'],
            'is_enabled'    => ['nullable', 'boolean'],
            'disabled_at'   => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['code'][]    = 'required';
            $rules['code'][]    = 'unique:inv_attributes';
        }
        else {
            //update
            $rules['code'][]    = Rule::unique('inv_attributes')->ignore($item->id);
        }

        return $rules;
    }
}
