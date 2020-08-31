<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Brand extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_brands';

    protected $dates = [
        'disabled_at',
    ];

    protected $fillable = [
        'dear_id', 'name', 'is_enabled',
        'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'dear_id'       => ['nullable', 'max:255'],
            'name'          => ['string', 'max:255'],
            'is_enabled'    => ['nullable', 'boolean'],
            'disabled_at'   => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['dear_id'][]     = 'unique:inv_brands';
            $rules['name'][]        = 'unique:inv_brands';
            $rules['name'][]        = 'required';
        } else {
            //update
            $rules['dear_id'][]    = Rule::unique('inv_brands')->ignore($item->id);
            $rules['name'][]       = Rule::unique('inv_brands')->ignore($item->id);
        }

        return $rules;
    }
}
