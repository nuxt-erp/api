<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockTakeResource extends JsonResource
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
            'company_id'                => $this->company_id,
            'company_name'              => optional($this->company)->name,
            'status'                    => $this->status == 1 ? "COMPLETED" : "IN PROGRESS",
            'brand_id'                  => $this->brand_id,
            'brand_name'                => optional($this->brand)->name,
            'category_id'               => $this->category_id,
            'category_name'             => optional($this->category)->name,
            'location_id'               => $this->location_id,
            'location_name'             => optional($this->location)->name,
            'net_var'                   => $this->net_var,
            'abs_var'                   => $this->abs_var,
            'success_rate'              => $this->success_rate,
            'can_be_deleted'            => true
        ];
    }
}
