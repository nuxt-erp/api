<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class CustomerDiscountResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'customer_id'   => $this->customer_id,
            'customer_name' => optional($this->customer)->name,
            'perc_value'    => $this->perc_value,
            'reason'        => $this->reason,
            'end_date'      => optional($this->end_date)->format('Y-m-d '),
            'start_date'    => optional($this->start_date)->format('Y-m-d '),
        ];
    }
}
