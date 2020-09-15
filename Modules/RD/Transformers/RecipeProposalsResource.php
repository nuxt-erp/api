<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class RecipeProposalsResource extends ResourceService
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
            'id'                  => $this->id,
            'recipe_id'           => $this->recipe_id,
            'author_id'           => $this->author_id,
            'approver_id'         => $this->approver_id,
            'status'              => $this->status,
            'comments'            => $this->comments,
            'approved_at'         => $this->approved_at,
            'created_at'          => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'          => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
