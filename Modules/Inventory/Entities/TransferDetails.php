<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class TransferDetails extends ModelService
{
   
    protected $connection = 'tenant';

    public $table       = "inv_transfer_details";

    protected $fillable = [
        'transfer_id', 'product_id', 'qty', 'qty_received', 'qty_sent', 'variance'
    ];

    public function getRules($request, $item = null)
    {
        $rules = [
            'product_id'    => ['exists:inv_products,id'],
            'transfer_id'    => ['exists:inv_transfers,id']
        ];
        return $rules;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

}
