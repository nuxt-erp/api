<?php

namespace Modules\Sales\Transformers;

use App\Resources\ResourceService;

class DiscountTagResource extends ResourceService
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
            'id'               => $this->id,
            'tag_id'           => $this->tag_id,
            'discount_id'      => $this->discount_id,
            'type'             => $this->type,
            'created_at'       => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'       => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
