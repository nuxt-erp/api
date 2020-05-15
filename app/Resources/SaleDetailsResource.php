<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'sale_id'               => $this->sale_id,
            'product_id'            => $this->product_id,
            'product_name'          => optional($this->product)->name,
            'name'                  => optional($this->product)->sku . ' - ' . optional($this->product)->name,
            'qty'                   => $this->qty,
            'price'                 => $this->price,
            'discount_value'        => $this->discount_value,
            'total_item'            => $this->total_item,
            'discount_percent'      => $this->discount_percent,
            'qty_fulfilled'         => $this->qty_fulfilled,
            'received_date'         => $this->received_date,
            'shopify_lineitem'      => $this->shopify_lineitem,
            'fulfillment_status'    => $this->fulfillment_status,
            'fulfillment_date'      => $this->fulfillment_date,
            'can_be_deleted'        => true
        ];
    }
}
