<?php

namespace App\Resources;


class ParameterResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'value'         => $this->value,
            'order'         => $this->order,
            'description'   => $this->description,
            'is_internal'   => $this->is_internal,
            'is_default'    => $this->is_default,
        ];
    }
}
