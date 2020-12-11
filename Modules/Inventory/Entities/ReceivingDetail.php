<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ReceivingDetail extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'inv_receiving_details';

    protected $fillable = [
        'receiving_id',
        'product_id',
        'item_status',
        'ref',
        'qty_received',
        'qty_allocated',
        'received_date'
    ];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'product_id'    => ['exists:inv_products,id'],
            'location_id'   => ['exists:tenant.locations,id'],
        ];

        return $rules;
    }

    public function receiving()
    {
        return $this->belongsTo(Receiving::class, 'receiving_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
  

}
