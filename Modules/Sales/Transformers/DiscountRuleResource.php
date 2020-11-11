<?php

namespace Modules\Sales\Transformers;

use App\Resources\ResourceService;

class DiscountRuleResource extends ResourceService
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
            'id'                       => $this->id,
            'type'                     => $this->type,
            'type_id'                  => $this->type_id,
            'type_name'                => optional($this->type_entity)->name,
            'discount_id'              => $this->discount_id,
            'discount_application_id'  => $this->discount_application_id,
            'include'                  => $this->include,
            'exclude'                  => $this->exclude,
            'stackable'                => $this->stackable,
            'all_products'             => $this->all_products,
            'created_at'               => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'               => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
