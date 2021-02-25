<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class TransferDetailsResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'transfer_id'       => $this->transfer_id,
            'product_id'        => $this->product_id,
            'product_name'      => optional($this->product)->name,
            'display_name'      => optional($this->product)->getDetailsAttributeValue(),
            'name'              => optional($this->product)->name,
            'on_hand'           => $this->getAvailabilitiesStatus()[0],
            'qty'               => $this->qty,
            'qty_received'      => $this->qty_received,
            'qty_sent'          => $this->qty_sent,
            'variance'          => $this->variance,
            'status'            => $this->getAvailabilitiesStatus()[1],
            'can_be_deleted'    => true
        ];
    }
}
