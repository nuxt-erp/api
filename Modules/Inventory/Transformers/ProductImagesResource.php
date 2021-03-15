<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;
use Illuminate\Support\Facades\Storage;

class ProductImagesResource extends ResourceService
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
            'product_id'    => $this->product_id,
            'product'       => $this->product,
            'path'          => $this->path,
            'thumb_path'    => $this->thumb_path,
            'order'         => $this->order,
            'is_default'    => $this->is_default
        ];
    }
}
