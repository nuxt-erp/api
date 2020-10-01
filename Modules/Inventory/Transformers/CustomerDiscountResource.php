<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class CustomerDiscountResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'product_id'    =>$this->product_id,
            'customer_id'   => $this->customer_id,
            'customer_name' => optional($this->customer)->name,
            'perc_value'    => $this->perc_value,
            'reason'        => $this->reason,
            'end_date'      => ($this->end_date),
            'start_date'    => ($this->start_date),
        ];
    }
}
