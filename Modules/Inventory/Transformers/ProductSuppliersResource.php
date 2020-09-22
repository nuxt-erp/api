<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductSuppliersResource extends ResourceService
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
            'name'              => $this->name,
            'product_id'        => $this->product_id,
            'product_sku'       => optional($this->product)->sku,
            'product_name'      => optional($this->product)->name,
            'supplier_id'       => $this->supplier_id,
            'lead_time'         => optional($this->supplier)->lead_time,
            'currency'          => $this->currency,
            'last_price'        => $this->last_price,
            'last_supplied'     => $this->last_supplied,
            'minimum_order'     => $this->minimum_order,
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
