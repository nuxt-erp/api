<?php

namespace Modules\Production\Transformers;

use App\Resources\ResourceService;

class ProductionResource extends ResourceService
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
            'id'                    => $this->id,
            'machine_id'            => $this->machine_id,
            'phase_id'              => $this->phase_id,
            'previous_phase_id'     => $this->previous_phase_id,
            'location_id'           => $this->location_id,
            'product_id'            => $this->product_id,
            'author_id'             => $this->author_id,
            'last_updater_id'       => $this->last_updater_id,
            'relation_id'           => $this->relation_id,
            'relation_type'         => $this->relation_type,
            'requester_id'          => $this->requester_id,
            'requester_type'        => $this->requester_type,
            'status'                => $this->status,
            'code'                  => $this->code,
            'reference_code'        => $this->reference_code,
            'sequence'              => $this->sequence,
            'scheduled'             => $this->scheduled,
            'requested_qty'         => $this->requested_qty,
            'requested_volume'      => $this->requested_volume,
            'scheduled_qty'         => $this->scheduled_qty,
            'scheduled_volume'      => $this->scheduled_volume,
            'finished_qty'          => $this->finished_qty,
            'finished_volume'       => $this->finished_volume,
            'started_at'            => optional($this->started_at)->format('Y-m-d H:i:s'),
            'finished_at'           => optional($this->finished_at)->format('Y-m-d H:i:s'),
            'expected_start_date'   => optional($this->expected_start_date)->format('Y-m-d H:i:s'),
            'expected_end_date'     => optional($this->expected_start_date)->format('Y-m-d H:i:s'),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s')
        ];
    }
}
