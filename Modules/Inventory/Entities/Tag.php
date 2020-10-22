<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class Tag extends ModelService
{

    protected $connection = 'tenant';

    protected $table = 'inv_tags';

    protected $fillable = [
        'name'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'name' => ['string', 'max:255'],
        ];

        // CREATE
        if (is_null($item))
        {
            $rules['name'][]        = 'unique:tenant.inv_tags';
            $rules['name'][]        = 'required';
        } else {
            //update
            $rules['name'][]       = Rule::unique('tenant.inv_tags')->ignore($item->id);
        }

        return $rules;
    }
}