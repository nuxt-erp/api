<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectSampleLogsResource extends ResourceService
{
    
    public function toArray($request)
    {
        $key_names = [
            'status'        => 'status', 
            'comment'       => 'comment', 
            'feedback'      => 'feedback',  
            'name'          => 'name',
            'internal_code' => 'internal_code'
        ];
        $actions = [];

        foreach ($this->resource->toArray() as $key => $value) {
            if(array_key_exists($key, $key_names) && !empty($value)) {
                array_push($actions, 'Updated ' . $key_names[$key] . ' to ' . $value . '.' );
            } else if ($key === 'is_start') {
                array_push($actions, 'Sample was created.');
            }
        }
        $arrayData = [
            'id'                 => $this->id,
            'project_sample_id'  => $this->project_sample_id,
            'project_sample_id'  => optional($this->project_sample),
            'assignee_id'        => $this->assignee_id,
            'updater_id'         => $this->assignee_id,
            'assignee'           => optional($this->assignee),
            'updated_name'       => optional($this->updater)->name,
            'actions'            => $actions,
            'status'             => $this->status,
            'comment'            => $this->comment,
            'feedback'           => $this->comment,
            'created_at'         => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'         => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        return $arrayData;
    }
}
