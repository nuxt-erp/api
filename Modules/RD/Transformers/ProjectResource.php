<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class ProjectResource extends ResourceService
{
    public function toArray($request)
    {
        $actions = collect([
            [
                'name'  => 'Edit Project',
                'code'  => 'editProject',
                'icon'  => 'edit',
                'plain' =>  false,
                'type'  => 'primary'
            ]
        ]);
        $arrayData = [
            'id'                 => $this->id,
            'full_id'            => strval($this->id) . "-" . strval($this->iteration),
            'author_id'          => $this->author_id,
            'author_name'        => optional($this->author)->name,
            'customer_id'        => $this->customer_id,
            'customer_name'      => $this->customer->name,
            'actions'            => $actions,
            'last_feedback'      => optional($this->samples->sortByDesc('updated_at')->first())->feedback,
            'iteration'          => $this->iteration,
            'status'             => ucwords($this->status),
            'sample_status'      => ucwords(optional($this->samples->sortByDesc('updated_at')->first())->status),
            'code'               => $this->code,
            'comment'            => $this->comment,
            'start_at'           => optional($this->start_at)->format('Y-m-d'),
            'closed_at'          => optional($this->closed_at)->format('Y-m-d'),
            'started'            => ($this->start_at !== null ? 1 : 0),
            'closed'             => ($this->closed_at !== null ? 1 : 0),
            'created_at'         => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'         => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];

        return $arrayData;
    }
}
