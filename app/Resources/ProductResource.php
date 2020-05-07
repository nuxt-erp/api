<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Resources\ProductAttributeResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        // Eager load
        $this->resource->load('attributes');

        return [
            'id'                    => $this->id,
            'sku'                   => $this->sku,
            'launch_date'           => $this->launch_date,
            'name'                  => $this->name,
            'company_id'            => $this->company_id,
            'company_name'          => optional($this->company)->name,
            'description'           => $this->description,
            'cost'                  => $this->cost,
            'status'                => $this->status,
            'barcode'               => $this->barcode,
            'length'                => $this->length,
            'width'                 => $this->width,
            'height'                => $this->height,
            'weight'                => $this->weight,
            'sales_chanel'          => $this->sales_chanel,
            'brand_id'              => $this->brand_id,
            'brand_name'            => optional($this->brand)->name,
            'category_id'           => $this->category_id,
            'category_name'         => optional($this->category)->name,
            'supplier_id'           => $this->supplier_id,
            'supplier_name'         => optional($this->supplier)->name,
            'location_id'           => $this->location_id,
            'location_name'         => optional($this->location)->name,
            'can_be_deleted'        => true,
            'product_attributes'    => $this->getOnlyAttribute(),
            'in_transit_suppliers'  => $this->getInTransitAttribute($this->id)
        ];
    }
}
