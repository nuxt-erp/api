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
            'quantity'               => ['nullable', 'float'],
            'percent'                => ['nullable', 'percent'],
        ];

         // rules when creating the item
         if (is_null($item)) {
            $rules['recipe_proposal_id'][] = 'required';
            $rules['recipe_item_id'][] = 'required';
            $rules['percent'][]     =
                function ($attribute, $value, $fail) use ($request) {
                    $sum = self::where('recipe_item_id', $request->input('recipe_item_id')->recipe_id)->sum('percent');
                    if ((($sum * 100) + $value) > 100) {
                        $fail('more100%');
                    }
                };
            
        }
        // rules when updating the item
        else{
            $rules['percent'][]     =
            function ($attribute, $value, $fail) use ($item) {
                $sum = self::where('id', '<>', $item->id)->where('recipe_item_id', $item->recipe_item_id->recipe_id)->sum('percent');
                if ((($sum * 100) + $value) > 100) {
                    $fail('more100%');
                }
            };
        }


        return $rules;
    }
}
