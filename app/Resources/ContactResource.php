<?php

namespace App\Resources;

class ContactResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'entity_id'      => $this->entity_id,
            'entity_type'    => $this->entity_type,
            'entity_name'    => optional($this->entity)->name,
            'name'           => $this->name,
            'email'          => $this->email,
            'phone_number'   => $this->phone_number,
            'mobile'         => $this->mobile,
            'is_default'     => $this->is_default,
            'created_at'     => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'     => optional($this->updated_at)->format('Y-m-d H:i:s'),

        ];
    }
}
