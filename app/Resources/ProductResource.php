<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'company_id'    => $this->company_id,
            'description'   => $this->description,
            'cost'          => $this->cost,
            'status'        => $this->status,
            'barcode'       => $this->barcode,
            'sales_chanel'  => $this->sales_chanel,
            'brand_id'      => $this->brand_id,
            'brand_name'    => optional($this->brand)->name,
            'category_id'   => $this->company_id,
            'category_name' => optional($this->category)->name,
            'supplier_id'   => $this->company_id,
            'supplier_name' => optional($this->supplier)->name,
            'company_id'    => $this->company_id,
            'company_name'  => optional($this->company)->name,
        ];
    }
}
