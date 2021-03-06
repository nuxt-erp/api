<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ProductCategory extends ModelService
{

    protected $connection = 'tenant';
    protected $table = 'inv_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'dear'
    ];

    public function getRules($request, $item = null)
    {

        $rules = [
            'name'          => ['string', 'max:255'],
            'dear'          => ['nullable', 'max:255']
        ];

        if (is_null($item)) {
            //create
            $rules['name'][]    = 'required';
            $rules['name'][]    = 'unique:tenant.product_categories';
            $rules['dear'][]    = 'unique:tenant.product_categories';
        } else {
            //update
            $rules['name'][]    = Rule::unique('tenant.product_categories')->ignore($item->id);
            $rules['dear'][]    = Rule::unique('tenant.product_categories')->ignore($item->id);
        }

        return $rules;
    }
}
