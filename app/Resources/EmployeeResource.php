<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'address'       => $this->address,
            'city'          => $this->city,
            'state'         => $this->state,
            'postal_code'   => $this->postal_code,
            'phone_number'  => $this->phone_number,
            'mobile_number' => $this->mobile_number,
            'image'         => $this->image,
            'contact_name'  => $this->contact_name,
            'user_id'       => $this->user_id,
            'user_name'     => optional($this->user)->name,
            'type_id'       => $this->type_id,
            'type_name'     => optional($this->type)->parameter_value,
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
            'can_be_deleted'=> true
        ];
    }
}
