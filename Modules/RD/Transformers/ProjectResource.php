<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectResource extends ResourceService
{
    public function toArray($request)
    {
        lad($this->started_at);
        lad($this->closed_at);
        $arrayData = [
            'id'                 => $this->id,
            'author_id'          => $this->author_id,
            'author_name'        => $this->author->name,
            'customer_id'        => $this->customer_id,
            'customer_name'      => $this->customer->name,
            'attribute_names'    => $this->attributes->pluck('name'),
            'status'             => $this->status,
            'code'               => $this->code,
            'comment'            => $this->comment,
            'start_at'           => optional($this->start_at)->format('Y-m-d H:i:s'),
            'closed_at'          => optional($this->closed_at)->format('Y-m-d H:i:s'),
            'started'            => ($this->start_at !== null ? 1 : 0),
            'closed'             => ($this->closed_at !== null ? 1 : 0),
            'created_at'         => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'         => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        return $arrayData;
    }
}
