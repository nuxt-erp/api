<?php

namespace App\Resources;

class SupplierResource extends ResourceService
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'supplier_type_id'      => $this->supplier_type_id,
            'supplier_type_name'    => optional($this->supplier_type)->value,
            'brand_id'              => $this->brand_id,
            'brand_name'            => optional($this->brand)->name,
            'contacts'              => optional($this->contacts)->toArray(),
            'name'                  => $this->name,
            'lead_time'             => $this->lead_time,
            'ordering_cycle'        => $this->ordering_cycle,
            'last_order_at'         => optional($this->last_order_at)->format('Y-m-d'),
            'is_enabled'            => $this->is_enabled,
            'disabled_at'           => optional($this->disabled_at)->format('Y-m-d H:i:s'),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
