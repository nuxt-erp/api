<?php

namespace App\Models;

use Illuminate\Validation\Rule;

class Warehouse extends ModelService
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_active'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'name'      => ['string', 'max:255'],
            'is_active' => ['nullable', 'boolean']
        ];

        //create
        if (is_null($item)) {
            $rules['name'][]    = Rule::unique('warehouses');
        } else {
            //update
            $rules['name'][]    = Rule::unique('warehouses')->ignore($item->id);
        }

        return $rules;
    }

}
