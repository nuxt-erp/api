<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class StockAdjustmentResource extends ResourceService
{

    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'notes'             => $this->notes,
            'name'              => $this->name,
            'author_id'         => $this->author_id,
            'location_id'       => $this->location_id,
            'location_name'     => optional($this->location)->name,
            'author_name'       => optional($this->author)->name,
            'effective_date'    => optional($this->effective_date)->format('Y-m-d H:i'),
            'created_at'        => optional($this->created_at)->format('Y-m-d'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
