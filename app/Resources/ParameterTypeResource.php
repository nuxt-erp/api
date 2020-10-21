<?php

namespace App\Resources;


class ParameterTypeResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'value'         => $this->value,
            'description'   => $this->description,
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
