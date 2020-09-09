<?php

namespace Modules\ExpensesApproval\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'team_leader_id'        => $this->team_leader_id,
            'team_leader_name'      => $this->team_leader->name,
            'director_id'           => $this->director_id,
            'director_name'         => $this->director->name,
            'is_finished'           => $this->is_finished ? 1 : 0,
            'finished_at'           => optional($this->finished_at)->format('Y-m-d H:i:s'),
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
