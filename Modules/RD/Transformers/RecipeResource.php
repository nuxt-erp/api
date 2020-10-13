<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class RecipeResource extends ResourceService
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
            'id'                  => $this->id,
            'author_id'           => $this->author_id,
            'author_name'         => optional($this->author)->name,
            'last_updater_id'     => $this->last_updater_id,
            'last_updater_name'   => optional($this->last_updater)->name,
            'approver_id'         => $this->approver_id,
            'approver_name'       => optional($this->approver)->name,
            'type_id'             => $this->type_id,
            'type_name'           => optional($this->type)->name,
            'carrier_id'          => $this->carrier_id,
            'carrier_name'        => optional($this->carrier)->name,
            'internal_code'       => optional($this->type)->value . '-' . $this->id,
            'attribute_names'     => $this->attributes->pluck('name'),
            'attribute_ids'       => optional($this->attributes)->pluck('id')->toArray(),
            'product_id'          => $this->product_id,
            'product_name'        => optional($this->product)->name,
            'status'              => $this->status,
            'name'                => $this->name,
            'category_id'         => $this->category_id,
            'category_name'       => optional($this->category)->name,
            'total'               => $this->total,
            'code'                => $this->code,
            'cost'                => $this->cost,
            'version'             => $this->version,
            'approved_at'         => $this->approved_at,
            'created_at'          => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'          => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
