<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Flavor extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_flavors';

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
            $rules['dear_id'][]     = 'unique:tenant.inv_flavors';
            $rules['name'][]        = 'unique:tenant.inv_flavors';
            $rules['name'][]        = 'required';
        } else {
            //update
            $rules['dear_id'][]    = Rule::unique('tenant.inv_flavors')->ignore($item->id);
            $rules['name'][]       = Rule::unique('tenant.inv_flavors')->ignore($item->id);
        }

        return $rules;
    }
}
