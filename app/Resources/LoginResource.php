<?php

namespace App\Resources;

class LoginResource extends ResourceService
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
            'roles'                 => $this->roles->flatMap(function ($role) {
                return [$role->code];
            }),
            'modules'                 => $this->company->modules->flatMap(function ($module) {
                return [$module->name];
            }),
            'created_at'            => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'            => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
