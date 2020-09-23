<?php

namespace App\Resources;

class CountryResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name
        ];
    }
}
