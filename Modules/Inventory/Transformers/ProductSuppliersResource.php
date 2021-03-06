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
            'product_id'        => $this->product_id,
            'product_sku'       => $this->product_sku,
            'product_name'      => $this->product_name,
            'supplier_name'     => optional($this->supplier)->name,
            'supplier_id'       => $this->supplier_id,
            'lead_time'         => optional($this->supplier)->lead_time,
            'currency_id'       => $this->currency_id,
            'currency_name'     => optional($this->currency)->description,
            'last_price'        => $this->last_price,
            'last_supplied'     => $this->last_supplied,
            'minimum_order'     => $this->minimum_order,
            'locations'         => ProductSupplierLocationsResource::collection($this->locations),
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
