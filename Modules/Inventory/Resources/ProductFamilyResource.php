<?php

namespace Modules\Inventory\Resources;

use App\Resources\ResourceService;

class ProductFamilyResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'sku'            => $this->sku,
            'name'           => $this->name,
            'launch_date'    => $this->launch_date,
            'description'    => $this->description,
            'status'         => $this->status,
            'brand_id'       => $this->brand_id,
            'brand_name'     => optional($this->brand)->name,
            'category_id'    => $this->category_id,
            'category_name'  => optional($this->category)->name,
            'location_id'    => $this->location_id,
            'location_name'  => optional($this->location)->name,
            'supplier_id'    => $this->supplier_id,
            'supplier_name'  => optional($this->supplier)->name,
            'total_variants' => optional($this->product)->count(),
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
