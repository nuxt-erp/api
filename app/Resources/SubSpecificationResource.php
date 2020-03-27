<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubSpecificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'spec_id'           => $this->spec_id,
            'spec_name'         => optional($this->specification)->name,
            'can_be_deleted'    => true
        ];
    }
}
