<?php

namespace App\Resources;

class CustomerTagResource extends ResourceService
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
            'id'                    => $this->id,
            'customer_id'           => $this->customer_id,
            'customer_name'         => optional($this->customer)->name,
            'tag_id'                => $this->tag_id,
            'tag_name'              => optional($this->tag)->name,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
