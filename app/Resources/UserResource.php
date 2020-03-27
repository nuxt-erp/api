<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'password'              => '[keep password]',
            'roles'                 => optional($this->roles)->pluck('id')->toArray(),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
            'can_be_deleted'        => true
            //'relation_field'  => optional($this->relation)->field,
            //'many_relation'   => EntityResource::collection($this->many_relation)
            //'one_relation'    => new PessoaResource($this->one_relation),
        ];
    }
}
