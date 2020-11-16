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
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}