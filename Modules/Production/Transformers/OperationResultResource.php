<?php

namespace Modules\Production\Transformers;

use App\Resources\ResourceService;

class OperationResultResource extends ResourceService
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
            'production_id'         => $this->production_id,
            'operation_id'          => $this->operation_id,
            'author_id'             => $this->author_id,
            'start_at'              => optional($this->start_at)->format('Y-m-d H:i:s'),
            'end_at'                => optional($this->end_at)->format('Y-m-d H:i:s'),
            'machine_id'            => $this->machine_id,
            'handled_qty'           => $this->handled_qty,
            'to_handle_qty'         => $this->to_handle_qty,
            'handled_volume'        => $this->handled_volume,
            'to_handle_volume'      => $this->to_handle_volume,
            'process_code'          => $this->process_code,
            'comment'               => $this->comment,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
