<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockTakeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'sku'                       => $this->sku,
            'name'                      => $this->name,
            'date'                      => $this->date,
            'target'                    => $this->target,
            'count_type_id'             => $this->count_type_id,
            'skip_today_received'       => $this->skip_today_received,
            'add_discontinued'          => $this->add_discontinued,
            'variance_last_count_id'    => $this->variance_last_count_id,
            'company_id'                => $this->company_id,
            'company_name'              => optional($this->company)->name,
            'status'                    => $this->status,
            'brand_id'                  => $this->brand_id,
            'brand_name'                => optional($this->brand)->name,
            'category_id'               => $this->category_id,
            'category_name'             => optional($this->category)->name,
            'supplier_id'               => $this->supplier_id,
            'supplier_name'             => optional($this->supplier)->name,
            'location_name'             => $this->location_name,
            'on_hand'                   => $this->on_hand,
            'qty'                       => $this->available,
            'can_be_deleted'            => true
        ];
    }
}
