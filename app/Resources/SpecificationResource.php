<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'company_id'    => $this->company_id,
            'can_be_deleted'=> true
        ];
    }
}
