<?php

namespace Modules\Inventory\Entities;

use App\Models\Currency;
use App\Models\ModelService;
use Illuminate\Validation\Rule;
use App\Models\Supplier;

class ProductSuppliers extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_suppliers';

    protected $fillable = [
        'product_id', 'supplier_id', 'product_name', 
        'product_sku', 'currency_id', 'last_price', 
        'minimum_order', 'last_supplied'
    ];

    public function getRules($request, $item = null)
    {

        // generic rules
        $rules = [
            'product_id'          => ['nullable', 'exists:tenant.inv_products,id'],
            'supplier_id'         => ['nullable', 'exists:tenant.suppliers,id'],
            'currency_id'         => ['exists:tenant.currencies,id'],
            'product_name'        => ['string', 'max:255'],
            'product_sku'         => ['string', 'max:255'],
            'last_price'          => ['nullable'],
            'minimum_order'       => ['nullable'],
            'last_supplied'       => ['nullable', 'date']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['currency_id'][] = 'required';
            $rules['supplier_id'][] = 'required';
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

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function locations(){
        return $this->hasMany(ProductSupplierLocations::class, 'product_supplier_id', 'id');
    }
}
