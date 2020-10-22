<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductTagResource extends ResourceService
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
            'product_id'            => $this->product_id,
            'product_name'          => optional($this->product)->name,
            'tag_id'                => $this->tag_id,
            'tag_name'              => optional($this->tag)->name,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
