<?php

namespace Modules\Production\Transformers;

use App\Resources\ResourceService;

class PhaseResource extends ResourceService
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
            'operation_id'              => $this->operation_id,
            'name'                      => $this->name,
            'will_start_counter'        => $this->will_start_counter ?? FALSE,
            'will_end_counter'          => $this->will_end_counter ?? FALSE,
            'created_at'                => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'                => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
