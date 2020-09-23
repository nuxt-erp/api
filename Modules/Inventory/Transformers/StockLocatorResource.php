<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class StockLocatorResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,         
            'name'          => $this->name,
            'is_enabled'    => $this->is_enabled,
            'disabled_at'   => optional($this->disabled_at)->format('Y-m-d H:i:s'),
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}