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
        'product_id', 'supplier_id', 'product_name', 'product_sku',
        'currency', 'last_price', 'minimum_order', 'last_supplied'
    ];

    public function getRules($request, $item = null)
    {

        // generic rules
        $rules = [
            'product_id'          => ['nullable', 'exists:tenant.inv_products,id'],
            'supplier_id'         => ['nullable', 'exists:tenant.suppliers,id'],
            'product_name'        => ['string', 'max:255'],
            'product_sku'         => ['string', 'max:255'],
            'currency'            => ['string', 'max:255'],
            'last_price'          => ['nullable'],
            'minimum_order'       => ['nullable'],
            'last_supplied'       => ['nullable', 'date']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['currency'][] = 'required';
            $rules['product_name'][] = 'required';
            $rules['product_sku'][] = 'required';
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
