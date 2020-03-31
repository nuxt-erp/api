<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductAttributeResource extends JsonResource
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
            'product_name'    => optional($this->product)->name
        ];
    }
}
