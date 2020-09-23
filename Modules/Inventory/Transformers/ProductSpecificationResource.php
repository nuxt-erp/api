<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductSpecificationResource extends ResourceService
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
