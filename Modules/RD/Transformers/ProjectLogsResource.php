<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectLogsResource extends ResourceService
{
    public function toArray($request)
    {
        $key_names = ['status' => 'status', 'comment' => 'comment', 'start_at' => 'start date', 'closed_at' => 'end date'];
        $actions = [];

        foreach ($this->resource->toArray() as $key => $value) {
            if(array_key_exists($key, $key_names) && !empty($value)) {
                array_push($actions, 'Updated ' . $key_names[$key] . ' to ' . $value . '.' );
            } else if ($key === 'is_start' && $value) {
                array_push($actions, 'Project was created.');
            }
        }
        $arrayData = [
            'id'                 => $this->id,
            'project_id'         => $this->project_id,
            'updater_id'         => $this->updater_id,
            'updater_name'       => ($this->updater)->name,
            'project'            => optional($this->project),
            'status'             => $this->status,
            'actions'            => $actions,
            'comment'            => $this->comment,
            'start_at'           => optional($this->start_at)->format('Y-m-d H:i:s'),
            'closed_at'          => optional($this->closed_at)->format('Y-m-d H:i:s'),
            'created_at'         => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'         => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        return $arrayData;
    }
}
