<?php

namespace App\Resources;

class LocationResource extends ResourceService
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
            'dear_id'       => $this->dear_id,
            'shopify_id'    => $this->shopify_id,
            'name'          => $this->name,
            'short_name'    => $this->short_name,
            'is_enabled'    => $this->is_enabled,
            'disabled_at'   => optional($this->disabled_at)->format('Y-m-d H:i:s'),
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
