<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class StockCountFilterResource extends ResourceService
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'type'              => $this->type,
            'type_id'           => $this->type_id,
            'type_name'         => optional($this->type_entity)->name,
            'stocktake_id'      => $this->stocktake_id,
            'stocktake'         => optional($this->stock_count)->name,
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s')
        ];
    }
}
