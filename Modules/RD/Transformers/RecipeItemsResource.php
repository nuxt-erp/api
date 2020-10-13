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
            'product_name'     => optional($this->product)->name,
            'product_sku'      => optional($this->product)->sku,
            'product_uom'      => $this->product->measure->name ?? '',
            'recipe_id'        => $this->recipe_id,
            'quantity'         => $this->quantity ?? 0,
            'percent'          => $this->percent ?? 0,
            'product_cost'     => optional($this->product)->cost ?? 0,
            'created_at'       => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'       => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
