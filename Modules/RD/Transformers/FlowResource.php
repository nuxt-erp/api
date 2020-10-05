<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class FlowResource extends ResourceService
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
            'phase_id'          => $this->phase_id,
            'next_phase_id'     => $this->next_phase_id,
            'start'             => $this->start,
            'end'               => $this->end,
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
