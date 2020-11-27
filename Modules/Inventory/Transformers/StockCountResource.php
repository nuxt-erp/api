<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class StockCountResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'name'                      => $this->name,
            'date'                      => $this->date->format('Y-m-d'),
            'target'                    => $this->target,
            'count_type_id'             => $this->count_type_id,
            'is_enabled'                => $this->add_discontinued,
            'status'                    => $this->status,
            'status_name'               => $this->status ? 'Done' : 'In Progress',
            'brand_id'                  => $this->brand_id,
            'brand_name'                => optional($this->brand)->name,
            'category_id'               => $this->category_id,
            'category_name'             => optional($this->category)->name,
            'location_id'               => $this->location_id,
            'location_name'             => optional($this->location)->name,
            'net_variance'              => $this->net_variance,
            'abs_variance'              => $this->abs_variance,
            'success_rate'              => $this->success_rate,
            'can_be_deleted'            => true
        ];
    }
}
