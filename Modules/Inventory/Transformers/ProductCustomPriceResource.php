<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductCustomPriceResource extends ResourceService
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
            'id'                    => $this->id,
            'product_id'            => $this->product_id,
            'product_name'          => optional($this->product)->name,
            'customer_id'           => $this->customer_id,
            'customer_name'         => optional($this->customer)->name,
            'currency'              => $this->currency,
            'custom_price'          => $this->custom_price,
            'is_enabled'            => $this->is_enabled,
            'disabled_at'           => optional($this->disabled_at)->format('Y-m-d H:i:s'),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
