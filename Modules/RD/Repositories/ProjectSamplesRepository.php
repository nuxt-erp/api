<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\Recipe;

class ProjectSamplesRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        $user = auth()->user();
        if(!$user->hasRole('admin', 'rd_requester', 'rd_supervisor')){
            $this->queryBuilder->where('assignee_id', $user->id);
        }

        if(!empty($searchCriteria['created_at'])){
            $this->queryBuilder->whereBetween('created_at', $searchCriteria['created_at']);
        }

        if(!empty($searchCriteria['type'])){
            $this->queryBuilder->whereHas('recipe', function ($query) use ($searchCriteria) {
                $query->whereIn('type_id', $searchCriteria['type']);
            });
        }

        if(!empty($searchCriteria['name'])){
            $text = '%' . Arr::pull($searchCriteria, 'name') . '%';
            $this->queryBuilder->where('name', 'ILIKE', $text);
        }

        return parent::findBy($searchCriteria);
    }

    public function store(array $data)
    {
        DB::transaction(function () use ($data)
        {

            $user = auth()->user();
            $data['author_id'] = $user->id;

            parent::store($data);

            if (Arr::has($data, 'attribute_ids')) {
                $this->model->attributes()->sync($data['attribute_ids']);
            }

        });

    }

    public function update($model, array $data)
    {
        // FLAVORIST UPDATE
        if(!empty($data['recipe'])){

            $recipe_id  = null;
            $user       = auth()->user();

            // existing recipe
            if(!empty($data['recipe']['id'])){
                if($data['new_version']){
                    $recipe = Recipe::find($data['recipe']['id']);

                    $new_recipe                     = $recipe->replicate();
                    $new_recipe->author_id          = $user->id;
                    $new_recipe->last_updater_id    = $user->id;
                    $new_recipe->approver_id        = null;
                    $new_recipe->approved_at        = null;
                    $new_recipe->status             = Recipe::NEW_RECIPE;
                    $new_recipe->last_version       = FALSE;
                    //$new_recipe->cost               = $data['recipe']['cost']; //@todo sum from the ingredients
                    //$new_recipe->total              = $data['recipe']['total']; //@todo sum from the ingredients
                    //$new_recipe->carrier_id = $data['recipe']['carrier_id'];
                    $new_recipe->version++;
                    $new_recipe->push();

                    // copy ingredients?
                    //$new_recipe->ingredients()->sync($model->ingredients);
                }
                else{
                    $recipe_id = $data['recipe']['id'];
                }
            }
            else{
                // new recipe
                // $recipe = Recipe::create([

                // ]);
                //$recipe_id= $recipe->id;
            }


            parent::update($model, [
                'recipe_id' => $recipe_id
            ]);
        }
        else{
            parent::update($model, $data);
        }
    }
}
