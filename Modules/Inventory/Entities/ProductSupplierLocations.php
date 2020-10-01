<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;
use App\Models\Location;

class ProductSupplierLocations extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_supplier_locations';
    protected $fillable = [
        'product_supplier_id', 'location_id', 
        'lead_time', 'safe_stock', 'reorder_qty'
    ];
    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'location_id'          => ['nullable', 'exists:tenant.locations,id'],
            'product_supplier_id'  => ['nullable', 'exists:tenant.inv_suppliers,id'],
            'lead_time'            => ['string', 'max:255']
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['location_id'][] = 'required';
        }
        // rules when updating the item
        else{

        }
        return $rules;
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    
    public function product_supplier()
    {
        return $this->belongsTo(ProductSuppliers::class, 'product_supplier_id');
    }
}
