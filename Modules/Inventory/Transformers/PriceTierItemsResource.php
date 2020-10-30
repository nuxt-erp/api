<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class PriceTierItemsResource extends ResourceService
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
            'price_tier_id'     => $this->price_tier_id,
            'product_id'        => $this->product_id,
            'name'              => $this->product->name,
            'sku'               => $this->product->sku,
            'cost'              => $this->product->cost,
            'msrp'              => $this->product->msrp,
            'custom_price'      => $this->custom_price,
            'markup'            => $this->price_tier->markup,
            'brand_name'        => optional($this->product->brand)->name,
            'category_name'     => optional($this->product->category)->name,
        ];
    }
}
