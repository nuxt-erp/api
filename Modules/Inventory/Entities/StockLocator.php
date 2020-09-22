<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class StockLocator extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_stock_locator';

    protected $dates = [
        'disabled_at',
    ];

    protected $fillable = [
        'name', 'is_enabled',
        'disabled_at'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [          
            'name'          => ['string', 'max:255'],
            'is_enabled'    => ['nullable', 'boolean'],
            'disabled_at'   => ['nullable', 'date']
        ];

        // CREATE
        if (is_null($item))
        {           
            $rules['name'][]        = 'unique:tenant.inv_stock_locator';
            $rules['name'][]        = 'required';
        } else {
            //update        
            $rules['name'][]       = Rule::unique('tenant.inv_stock_locator')->ignore($item->id);
        }
        return $rules;
    }
}