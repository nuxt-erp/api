<?php

namespace Modules\ExpensesApproval\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SubcategoryResource extends JsonResource
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
            'id'                        => $this->id,
            'name'                      => $this->name,
            'created_at'                => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'                => optional($this->updated_at)->format('Y-m-d H:i:s'),
            'can_be_deleted'            => true
        ];
    }
}
