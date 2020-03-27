<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSpecificationResource extends JsonResource
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
            'id'            => $this->id,
            'value'         => $this->value,
            'product_id'    => $this->product_id,
            'spec_id'       => $this->attribute_id,
            'spec_name'     => optional($this->specification)->name,
            'sub_spec_id'   => $this->sub_spec_id,
        ];
    }
}
