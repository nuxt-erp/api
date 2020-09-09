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
            'last_updater_id'     => $this->last_updater_id,
            'approver_id'         => $this->approver_id,
            'type_id'             => $this->type_id,
            'product_id'          => $this->product_id,
            'status'              => $this->status,
            'name'                => $this->name,
            'category'            => $this->category,
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
