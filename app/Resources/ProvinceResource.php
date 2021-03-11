<?php

namespace App\Resources;

class ProvinceResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'code'          => $this->code,
            'country_id'    => $this->country_id,
            'location_id'   => $this->location_id,
            'country_name'  => optional($this->country)->name,
            'location_name'  => optional($this->location)->name
        ];
    }
}
