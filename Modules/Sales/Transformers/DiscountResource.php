<?php

namespace Modules\Sales\Transformers;

use App\Resources\ResourceService;

class DiscountResource extends ResourceService
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
            'id'                       => $this->id,
            'title'                    => $this->title,
            'order_rule_operation'     => $this->order_rule_operation,
            'order_rule_value'         => $this->order_rule_value,
            'start_date'               => optional($this->start_date)->format('Y-m-d H:i:s'),
            'end_date'                 => optional($this->end_date)->format('Y-m-d H:i:s'),
            'created_at'               => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'               => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
