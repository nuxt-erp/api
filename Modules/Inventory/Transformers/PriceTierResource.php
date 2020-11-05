<?php

namespace Modules\Inventory\Transformers;

use App\Resources\ResourceService;

class PriceTierResource extends ResourceService
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
            'name'              => $this->name,
            'markup'            => $this->markup,
            'markup_type'       => $this->markup_type==null?'custom':$this->markup_type,
            'custom_price'      => $this->custom_price,
            'author_id'         => $this->author_id,
            'author_name'       => optional($this->author)->name,
            'last_updater_id'   => $this->last_updater_id,
            'last_updater_name' => optional($this->last_updater)->name,
            'items'             => PriceTierItemsResource::collection($this->items),
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
            'can_be_deleted'    => TRUE
        ];
    }
}
