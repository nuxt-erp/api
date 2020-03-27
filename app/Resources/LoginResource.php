<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->name,
            'email'                 => $this->email,
            'company_id'            => $this->company_id,
            'roles'                 => $this->roles->flatMap(function ($role) {
                return [$role->name];
            }),
            'employee_id'           => optional($this->employee)->id,
            'employee_name'         => optional($this->employee)->name,
            'created_at'            => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'            => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
