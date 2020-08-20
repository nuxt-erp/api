<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService as ModelsModelService;

class Category extends ModelsModelService
{
    protected $table = 'inv_categories';

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
            $rules['dear_id'][]     = 'unique:inv_categories';
            $rules['name'][]        = 'unique:inv_categories';
            $rules['name'][]        = 'required';
        } else {
            //update
            $rules['dear_id'][]    = Rule::unique('inv_categories')->ignore($item->id);
            $rules['name'][]       = Rule::unique('inv_categories')->ignore($item->id);
        }

        return $rules;
    }
}
