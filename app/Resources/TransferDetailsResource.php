<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'transfer_id'       => $this->transfer_id,
            'product_id'        => $this->product_id,
            'product_name'      => optional($this->product)->getConcatNameAttribute(),
            'name'              => optional($this->product)->sku . ' - ' . optional($this->product)->getConcatNameAttribute(),
            'qty'               => $this->qty,
            'qty_received'      => $this->qty_received,
            'qty_sent'          => $this->qty_sent,
            'variance'          => $this->variance,
            'can_be_deleted'    => true
        ];
    }
}
