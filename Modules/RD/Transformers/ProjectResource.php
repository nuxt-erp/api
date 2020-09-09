<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectResource extends ResourceService
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
            'id'             => $this->id,
            'author_id'      => $this->author_id,
            'customer_id'    => $this->customer_id,
            'status'         => $this->status,
            'code'           => $this->code,
            'comments'       => $this->comments,
            'start_at'       => optional($this->start_at)->format('Y-m-d H:i:s'),
            'closed_at'      => optional($this->closed_at)->format('Y-m-d H:i:s'),
            'created_at'     => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'     => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
