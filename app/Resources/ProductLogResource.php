<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductLogResource extends JsonResource
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
            'product_id'    => $this->product_id,
            'quantity'      => $this->quantity,
            'date'          => $this->date,
            'ref_code_id'   => $this->ref_code_id,
            'type'          => $this->type,
            'location_id'   => $this->location_id,
            'location_name' => optional($this->location)->name,
            'source'        => optional($this->source),
        ];
    }
}
