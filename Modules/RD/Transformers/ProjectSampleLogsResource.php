<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectSampleLogsResource extends ResourceService
{
    public function toArray($request)
    {
        $arrayData = [
            'id'                 => $this->id,
            'project_sample_id'  => $this->project_sample_id,
            'project_sample_id'  => optional($this->project_sample),
            'assignee_id'        => $this->assignee_id,
            'assignee'           => optional($this->assignee),
            'status'             => $this->status,
            'comment'            => $this->comment,
            'feedback'           => $this->comment,
            'created_at'         => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'         => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        return $arrayData;
    }
}
