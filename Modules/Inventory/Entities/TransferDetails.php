<?php

namespace Modules\Inventory\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class TransferDetails extends ModelService
{
    const WARNING   = 'warning';
    const OK        = 'ok';
    const RECEIVED  = 'received';

    protected $connection  = 'tenant';

    public $table          = "inv_transfer_details";

    protected $fillable    = [
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

    public function getAvailabilitiesStatus()
    {
        $on_hand = Availability::where(['product_id' => $this->product_id, 'location_id' => $this->transfer->location_from->id])->pluck('on_hand')->first();
        if (!$this->transfer->is_enable) {
            return array($on_hand, TransferDetails::RECEIVED);
        }
        if ($this->qty_sent > $on_hand) {
            return array($on_hand, TransferDetails::WARNING);
        }
        return array($on_hand, TransferDetails::OK);
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
