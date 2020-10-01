<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectSamplesFlavoristResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'internal_code'     => $this->internal_code,
            'external_code'     => $this->external_code,
            'name'              => $this->name,
            'target_cost'       => $this->target_cost,
            'status'            => $this->status,
            'status_name'       => ucwords($this->status),
            'attributes_name'   => $this->attributes->pluck('name'),
            'recipe_version'    => optional($this->recipe)->version,
            'recipe_version_qty'=> $this->recipe_version_qty,
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s')
        ];
    }
}
