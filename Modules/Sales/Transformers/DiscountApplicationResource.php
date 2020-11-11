<?php

namespace Modules\Sales\Transformers;

use App\Resources\ResourceService;

class DiscountApplicationResource extends ResourceService
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
            'id'            => $this->id,
            'percent_off'   => $this->percent_off,
            'amount_off'    => $this->amount_off,
            'custom_price'  => $this->custom_price,
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
