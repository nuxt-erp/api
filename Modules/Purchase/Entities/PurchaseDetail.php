<?php

namespace Modules\Purchase\Entities;

use App\Models\Location;
use App\Models\ModelService;
use App\Models\Supplier;
use App\Models\TaxRule;

use Illuminate\Validation\Rule;
use Modules\Inventory\Entities\LocationBin;
use Modules\Inventory\Entities\Product;

class PurchaseDetail extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'pur_purchase_details';

    protected $fillable = [
        'purchase_id', 'product_id', 'qty',
        'price', 'sub_total', 'total', 'qty_allocated',
        'estimated_date', 'qty_received', 'received_date',
        'ref', 'item_status', 'taxes', 'allocation_created',
        'discounts','tax_rule_id', 'bin_id', 'location_id'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'product_id'    => ['exists:inv_products,id'],
            'bin_id'        => ['exists:inv_location_bins,id'],
            'location_id'   => ['exists:tenant.locations,id'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            //$rules['field'][] = 'required';
        }
        // rules when updating the item
        else{

        }

        return $rules;
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function bin()
    {
        return $this->belongsTo(LocationBin::class, 'bin_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function tax_rules()
    {
        return $this->belongsTo(TaxRule::class, 'tax_rule_id');
    }
}
