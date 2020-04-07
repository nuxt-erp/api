<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductFamilyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'sku'            => $this->sku,
            'name'           => $this->name,
            'launch_date'    => $this->launch_date,
            'company_id'     => $this->company_id,
            'company_name'   => optional($this->company)->name,
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
            'can_be_deleted' => true
        ];
    }
}
