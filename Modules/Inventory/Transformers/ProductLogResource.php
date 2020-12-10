<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductLogResource extends ResourceService
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
            'id'                => $this->id,
            'product_id'        => $this->product_id,
            'location_id'       => $this->location_id,
            'location_name'     => optional($this->location)->name,
            'bin_id'            => $this->bin_id,
            'bin_name'          => optional($this->bin)->name,
            'type_id'           => $this->type_id,
            'type_name'         => optional($this->type)->value,
            'quantity'          => $this->quantity,
            'customer_supplier' => $this->getSourceAttribute(),
            'description'       => $this->description,
            'ref_code'          => $this->ref_code_id,
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
            'user_id'           => $this->id,
            'user_name'         => optional($this->user)->name,
        ];
    }
}
