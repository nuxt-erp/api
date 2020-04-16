<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockTakeDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'stocktake_id'    => $this->stocktake_id,
            'product_id'      => $this->product_id,
            'qty'             => $this->qty,
            'stock_on_hand'   => $this->stock_on_hand,
            'variance'        => $this->variance,
            'notes'           => $this->notes,
            'can_be_deleted'  => true
        ];
    }
}
