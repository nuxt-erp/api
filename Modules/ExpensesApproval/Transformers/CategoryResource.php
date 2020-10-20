<?php

namespace Modules\ExpensesApproval\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'lead_id'               => $this->lead_id,
            'lead_name'             => $this->lead->name,
            //@todo get primary sponsor and the list of other sponsors
            //'sponsor_id'            => $this->sponsor_id,
            //'sponsor_name'          => $this->sponsors->name,
            //'sponsors'              => optional($this->sponsors)->pluck('id')->toArray(),
            'buyer_id'              => $this->buyer_id,
            'buyer_name'            => $this->buyer->name,
            'is_finished'           => $this->is_finished ? 1 : 0,
            'finished_at'           => optional($this->finished_at)->format('Y-m-d H:i:s'),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
