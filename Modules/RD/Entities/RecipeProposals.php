<?php

namespace Modules\RD\Entities;

use App\Models\ModelService;
use Illuminate\Validation\Rule;

class RecipeProposals extends ModelService
{
    protected $connection = 'tenant';

    protected $table = 'rd_recipe_proposals';

    protected $fillable = ['recipe_id', 'author_id', 'approver_id', 'status', 'comment', 'approved_at'];

    public function getRules($request, $item = null)
    {
        // generic rules
        $rules = [
            'recipe_id'             => ['exists:tenant.rd_recipes,id'],
            'author_id'             => ['exists:public.users,id'],
            'approver_id'           => ['nullable', 'exists:public.users,id'],
            'status'                => ['string', 'max:255'],
            'comments'              => ['nullable', 'string', 'max:255'],
            'approved_at'           => ['nullable', 'date'],
        ];

        // rules when creating the item
        if (is_null($item)) {
            $rules['recipe_id'][] = 'required';
            $rules['author_id'][] = 'required';
            $rules['status'][] = 'required';
        }

        return $rules;
    }
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
