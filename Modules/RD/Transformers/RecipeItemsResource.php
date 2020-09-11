<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class RecipeItemsResource extends ResourceService
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
            'product_id'       => $this->product_id,
            'recipe_id'        => $this->recipe_id,
            'quantity'         => $this->quantity,
            'percent'          => $this->percent,
            'cost'             => $this->cost,
            'created_at'       => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'       => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
