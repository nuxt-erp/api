<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class FamilyResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'brand_id'          => $this->brand_id,
            'brand_name'        => optional($this->brand)->name,
            'category_id'       => $this->category_id,
            'category_name'     => optional($this->category)->name,
            'supplier_id'       => $this->supplier_id,
            'supplier_name'     => optional($this->supplier)->name,
            'location_id'       => $this->location_id,
            'location_name'     => optional($this->location)->name,
            'dear_id'           => $this->dear_id,
            'name'              => $this->name,
            'description'       => $this->description,
            'sku'               => $this->sku,
            'launch_at'         => optional($this->launch_at)->format('Y-m-d H:i:s'),
            'total_variants'    => optional($this->product)->count(),
            'is_enabled'        => $this->is_enabled,
            'disabled_at'       => optional($this->disabled_at)->format('Y-m-d H:i:s'),
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
