<?php

namespace Modules\Production\Transformers;

use App\Resources\ResourceService;

class FlowActionResource extends ResourceService
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
            'flow_id'                  => $this->flow_id,
            'previous_phase_id'        => $this->previous_phase_id,
            'destination_phase_id'     => $this->destination_phase_id,
            'destination_location_id'  => $this->destination_location_id,
            'name'                     => $this->name,
            'created_at'               => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'               => optional($this->updated_at)->format('Y-m-d H:i:s')
        ];
    }
}
