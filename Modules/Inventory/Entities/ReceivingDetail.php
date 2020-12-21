<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class ReceivingDetail extends ModelService
{
    const NEW_RECEIVING         = 'new';
    const PARTIALLY_ALLOCATED   = 'partially allocated';
    const ALLOCATED             = 'allocated';
    
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
            'receiving_id'  => ['exists:tenant.inv_receiving,id'],
            'product_id'    => ['exists:tenant.inv_products,id']
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
