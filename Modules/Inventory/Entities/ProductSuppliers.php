<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;
use App\Models\Supplier;

class ProductSuppliers extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_suppliers';
    protected $fillable = [
        'name', 'product_id', 'supplier_id', 
        'currency', 'last_price', 'minimum_order', 'last_supplied'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'name'                => ['string', 'max:255'],
            'product_id'          => ['nullable', 'exists:tenant.inv_products,id'],
            'supplier_id'         => ['nullable', 'exists:tenant.suppliers,id'],
            'currency'            => ['string', 'max:255'],
            'last_price'          => ['nullable'],
            'minimum_order'       => ['nullable'],
            'last_supplied'       => ['nullable', 'date']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['name'][] = 'required';
            $rules['currency'][] = 'required';
        }
        // rules when updating the item
        else{

        }
        return $rules;
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
