<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductFamilyAttributeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'value'           => $this->value,
            'family_id'       => $this->family_id,
            'family_name'     => optional($this->family)->name,
            'attribute_id'    => $this->attribute_id,
            'attribute_name'  => optional($this->attribute)->name
        ];
    }
}
