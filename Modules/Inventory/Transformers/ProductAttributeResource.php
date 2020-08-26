<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductAttributeResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'value'           => $this->value,
            'product_id'      => $this->product_id,
            'attribute_id'    => $this->attribute_id,
            'attribute_name'  => optional($this->attribute)->name,
        ];
    }
}
