<?php

namespace Modules\ExpensesApproval\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesRuleResource extends JsonResource
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
            'id'                        => $this->id,
            'name'                      => $this->name,
            'team_leader_required'      => $this->team_leader_approval,
            'director_required'         => $this->director_approval,
            'start_value'               => $this->start_value,
            'end_value'                 => $this->end_value,
            'created_at'                => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'                => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
