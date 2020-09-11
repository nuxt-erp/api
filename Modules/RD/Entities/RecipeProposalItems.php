<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class RecipeProposalItems extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipe_proposal_items';

    protected $fillable = ['recipe_proposal_id', 'recipe_item_id', 'quantity', 'percent'];

    public function getRules($request, $item = null)
    {
        $rules = [
            'recipe_proposal_id'     => ['exists:tenant.rd_recipe_proposals,id'],
            'recipe_item_id'         => ['exists:tenant.rd_recipe_items,id'],
            'quantity'               => ['nullable'],
            'percent'                => ['nullable'],
        ];

         // rules when creating the item
         if (is_null($item)) {
            $rules['recipe_proposal_id'][] = 'required';
            $rules['recipe_item_id'][] = 'required';
            
        }
        // rules when updating the item
        else{
        }


        return $rules;
    }
}
