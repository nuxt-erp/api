<?php

namespace App\Resources;

class ProvinceResource extends ResourceService
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
