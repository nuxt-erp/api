<?php

namespace Modules\Purchase\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseDetailResource extends JsonResource
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
            'id'                  => $this->id,
            'purchase_id'         => $this->purchase_id,
            'product_id'          => $this->product_id,
            'product_name'        => optional($this->product)->name,
            'product_full_name'   => $this->product ? $this->product->sku . ' - ' . $this->product->name : null,
            'location_id'         => $this->location_id,
            'location_name'       => optional($this->location)->name,
            'bin_id'              => $this->bin_id,
            'bin_name'            => optional($this->bin)->name,
            'name'                => optional($this->product)->name,
            'display_name'        => optional($this->product)->getDetailsAttributeValue(),
            'qty'                 => $this->qty,
            'price'               => $this->price,
            'sub_total'           => $this->gross_total,
            'total'               => $this->total,
            'taxes'               => $this->taxes,
            'discounts'           => $this->discounts ?? 0,
            'estimated_date'      => $this->estimated_date,
            'qty_received'        => $this->qty_received,
            'qty_allocated'       => $this->qty_allocated,
            'received_date'       => $this->received_date,
            'ref'                 => $this->ref,
            'item_status'         => $this->item_status,
            'allocation_created'  => $this->allocation_created,
            'can_be_deleted'      => true,
            'tax_rule_id'         => $this->tax_rule_id
        ];
    }
}
