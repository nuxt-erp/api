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
            'value'         => isset($this->parameter_value) ? $this->parameter_value : $this->id,
            'name'          => !empty($this->description) ? $this->description : $this->name,
            'is_default'    => isset($this->is_default) ? $this->is_default : 0
        ];
    }
}
