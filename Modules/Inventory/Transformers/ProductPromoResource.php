<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductPromoResource extends ResourceService
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
            'discount_percentage'   => $this->discount_percentage,
            'buy_qty'               => $this->buy_qty,
            'get_qty'               => $this->get_qty,
            'date_from'             => optional($this->date_from)->format('Y-m-d'),
            'date_to'               => optional($this->date_to)->format('Y-m-d'),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
