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
            'date'                      => $this->date,
            'target'                    => $this->target,
            'count_type_id'             => $this->count_type_id,
            'skip_today_received'       => $this->skip_today_received,
            'add_discontinued'          => $this->add_discontinued,
            'variance_last_count_id'    => $this->variance_last_count_id,
            'status'                    => $this->status ? 'Done' : 'In Progress',
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
