<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProvinceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'short_name'    => $this->short_name,
            'country_id'    => $this->country_id,
            'country_name'  => optional($this->country)->name
        ];
    }
}
