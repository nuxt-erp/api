<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemParameterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'param_name'    => $this->param_name,
            'param_value'   => $this->param_value,
            'company_id'    => $this->company_id,
            'is_default'    => $this->is_default,
            'description'   => $this->description,
        ];
    }
}
