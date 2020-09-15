<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectSamplesResource extends ResourceService
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
            'id'            => $this->id,
            'project_id'    => $this->project_id,
            'recipe_id'     => $this->recipe_id,
            'assignee_id'   => $this->assignee_id,
            'name'          => $this->name,
            'status'        => $this->status,
            'target_cost'   => $this->target_cost,
            'feedback'      => $this->feedback,
            'comment'       => $this->comment,
            'created_at'    => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'    => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
