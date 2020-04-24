<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'short_name'     => $this->short_name,
            'company_id'     => $this->company_id,
            'can_be_deleted' => true
        ];
    }
}
