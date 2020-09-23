<?php

namespace Modules\RD\Transformers;

use App\Resources\ResourceService;

class RecipeProposalItemsResource extends ResourceService
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
            'id'                    => $this->id,
            'recipe_proposal_id'    => $this->recipe_proposal_id,
            'recipe_item_id'        => $this->recipe_item_id,
            'quantity'              => $this->quantity,
            'percent'               => $this->percent,
            'created_at'            => optional($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at'            => optional($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
