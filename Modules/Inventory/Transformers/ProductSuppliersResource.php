<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class ProductSuppliersResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    protected $fillable = [
        'name', 'product_id', 'supplier_id', 
        'currency', 'last_price', 'last_supplied'
    ];
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'product_id'        => $this->product_id,
            'product'           => optional($this->product),
            'supplier_id'       => $this->supplier_id,
            'supplier'          => optional($this->supplier),
            'currency'          => $this->currency,
            'last_price'        => $this->last_price,
            'last_supplied'     => $this->last_supplied,
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
