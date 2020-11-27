<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class LocationBinResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'barcode'       => $this->barcode,
            'location_id'   => $this->location_id,
            'location_name' => optional($this->location)->name,
            'is_enabled'    => $this->is_enabled,
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
