<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
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
            'sku'           => isset($this->sku) ? $this->sku : '',
            'value'         => isset($this->parameter_value) ? $this->parameter_value : $this->id,
            'name'          => isset($this->name_full) ? $this->name_full : $this->name,
            // 'name'          => !empty($this->description) ? $this->description : $this->name,
            'is_default'    => isset($this->is_default) ? $this->is_default : 0
        ];
    }
}
