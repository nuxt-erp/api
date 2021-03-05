<?php

namespace Modules\Sales\Transformers;

use App\Resources\ResourceService;

class SaleDetailsResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'sale_id'                   => $this->sale_id,
            'product_id'                => $this->product_id,
            'location_id'               => $this->location_id,
            'fulfillment_status_id'     => $this->fulfillment_status_id,
            'fulfillment_status_name'   => optional($this->fulfillment_status)->name,
            'shopify_id'                => $this->shopify_id,
            'qty'                       => $this->qty,
            'price'                     => $this->price,
            'discount_value'            => $this->discount_value,
            'discount_percent'          => $this->discount_percent,
            'total_item'                => $this->total_item,
            'qty_fulfilled'             => $this->qty_fulfilled,
            'fulfillment_date'          => $this->fulfillment_date,
            'product_name'              => optional($this->product)->full_description,
            'display_name'              => optional($this->product)->getDetailsAttributeValue(),
            'tax_rule_id'               =>  $this->tax_rule_id,
            'name'                      => optional($this->product)->sku . ' - ' . optional($this->product)->full_description,
        ];
    }
}
