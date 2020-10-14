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
        $actions = [];

        switch ($this->status) {
            case 'approved':
            case 'waiting qc':
                $actions[] = [
                    'name'  => 'Generate Specs',
                    'code'  => 'generate',
                    'type'  => 'primary'
                ];
            case 'ready':
            case 'pending':
            case 'sent':
                $actions[] = [
                    'name'  => 'Preview',
                    'code'  => 'sample',
                    'type'  => 'primary'
                ];
                break;
            case 'in progress':
            case 'rework':
                $actions[] = [
                    'name'  => !$this->recipe_id ? 'Develop' : 'Edit / Preview',
                    'code'  => 'sample',
                    'type'  => 'primary'
                ];
                break;
            case 'waiting approval':
                $actions[] = [
                    'name'  => 'Approve Sample',
                    'code'  => 'sample',
                    'type'  => 'primary'
                ];
                break;
        }

        return [
            'id'                => $this->id,
            'project_id'        => $this->project_id,
            'recipe_id'         => $this->recipe_id,
            'recipe_name'       => optional($this->recipe)->name,
            'recipe_version'    => optional($this->recipe)->version,
            'recipe_type'       => $this->recipe ? optional($this->recipe->type)->name : null,
            'recipe_version_qty'=> $this->recipe_version_qty,
            'phase_id'          => $this->phase_id,
            'actions'           => collect($actions),
            'attributes'        => implode(', ', $this->attributes->pluck('name')->toArray()),
            'attribute_names'   => $this->attributes->pluck('name'),
            'attribute_ids'     => optional($this->attributes)->pluck('id')->toArray(),
            'assignee_id'       => $this->assignee_id,
            'assignee_name'     => optional($this->assignee)->name,
            'name'              => $this->name,
            'internal_code'     => $this->internal_code,
            'external_code'     => $this->external_code,
            'status'            => $this->status,
            'status_name'       => ucwords($this->status),
            'target_cost'       => $this->target_cost,
            'feedback'          => $this->feedback,
            'comment'           => $this->comment,
            'started_at'        => optional($this->started_at)->format('Y-m-d H:i:s'),
            'finished_at'       => optional($this->finished_at)->format('Y-m-d H:i:s'),
            'created_at'        => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'        => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
