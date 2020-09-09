<?php

namespace App\Resources;

class UserResource extends ResourceService
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
            'password'              => '[keep password]',
            'is_enabled'            => $this->is_enabled ? 1 : 0 ,
            'disabled_at'           => $this->disabled_at,
            'roles'                 => optional($this->roles)->pluck('id')->toArray(),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
