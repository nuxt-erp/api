<?php

namespace App\Resources;

class RoleResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'code'                  => $this->code,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
