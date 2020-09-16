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
            'launch_at'         => $this->launch_at,
            'stock_locator'     => $this->stock_locator,
            'stock_locator_name'=> optional($this->stock_locator)->name,
            'measure_id'        => $this->measure_id,
            'measure_name'      => optional($this->measure)->name,
            'price'             => $this->price,
            'barcode'           => $this->barcode,
            'length'            => $this->length,
            'width'             => $this->width,
            'height'            => $this->height,
            'weight'            => $this->weight,
            'carton_length'     => $this->carton_length,
            'carton_width'      => $this->carton_width,
            'carton_height'     => $this->carton_height,
            'carton_weight'     => $this->carton_weight,
            'family_attributes' => $this->details,
          //  'total_variants'    => optional($this->product)->count(),
            'is_enabled'        => $this->is_enabled,
            'disabled_at'       => optional($this->disabled_at)->format('Y-m-d H:i:s'),
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}