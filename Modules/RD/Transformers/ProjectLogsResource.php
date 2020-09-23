<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectLogsResource extends ResourceService
{
    public function toArray($request)
    {
        $arrayData = [
            'id'                 => $this->id,
            'project_id'         => $this->project_id,
            'project'            => optional($this->project),
            'code'               => $this->code,
            'status'             => $this->status,
            'comment'            => $this->comment,
            'start_at'           => optional($this->start_at)->format('Y-m-d H:i:s'),
            'closed_at'          => optional($this->closed_at)->format('Y-m-d H:i:s'),
            'created_at'         => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'         => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        return $arrayData;
    }
}
