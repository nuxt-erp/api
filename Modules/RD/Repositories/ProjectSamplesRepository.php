<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\Flow;
use Modules\RD\Entities\Phase;
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
        DB::transaction(function () use ($data){

            $user = auth()->user();
            $data['author_id'] = $user->id;
            //@todo
            //1 - get the first phase_id from the flow
            //2 - get status
            // $data['status']     = ;

            parent::store($data);

            if (Arr::has($data, 'attribute_ids')) {
                $this->model->attributes()->sync($data['attribute_ids']);
            }

        });

    }

    public function update($model, array $data)
    {
        $approval        = !empty($data['supervisor_approval']) && $data['supervisor_approval'];
        $finish          = !empty($data['flavorist_finish']) && $data['flavorist_finish'];
        $recipe_update   = !empty($data['recipe']);

        // FLAVORIST UPDATE
        if($recipe_update){

            // existing recipe
            if(!empty($data['recipe']['id'])){

                // create the next version
                if($data['recipe']['new_version']){

                    DB::transaction(function () use (&$data){
                        // get current recipe
                        $recipe     = Recipe::findOrFail($data['recipe']['id']);
                        $user       = auth()->user();

                        $new_recipe                     = $recipe->replicate();
                        $new_recipe->author_id          = $user->id;
                        $new_recipe->last_updater_id    = $user->id;
                        $new_recipe->approver_id        = null;
                        $new_recipe->approved_at        = null;
                        $new_recipe->status             = Recipe::NEW_RECIPE;
                        $new_recipe->last_version       = FALSE;
                        $new_recipe->carrier_id         = $data['recipe']['carrier_id'];
                        //$new_recipe->cost             = $data['recipe']['cost']; //@todo sum from the ingredients?
                        //$new_recipe->total            = $data['recipe']['total']; //@todo sum from the ingredients?
                        $new_recipe->version++;
                        $new_recipe->push();

                        // copy ingredients
                        $new_recipe->ingredients->sync($data['recipe']['ingredients']);
                        // update sample recipe id
                        $data['recipe_id'] = $new_recipe->id;
                    });
                }
            }
            else{
                // @todo new recipe
                // 1 add the recipe
                // 2 add ingredients to the recipe
                // 3 update sample with recipe id

                $recipe = Recipe::create($data['recipe']);
                $recipe->ingredients->sync($data['recipe']['ingredients']);
                $data['recipe_id'] = $recipe->id;
            }

        }

        // FINISH
        if($finish || $approval){
            $flow = Flow::where('phase_id', $model->phase_id)->first();
            $data['phase_id']   = $flow->next_phase_id;
            if($finish){
                $data['finished_at']= now();
            }
            $data['status']     = $flow->next_phase->name;
        }


        // option 1 - recipe update without start
        // option 2 - finish without start
        if(!$model->started_at && ($recipe_update || $finish)){
            $data['started_at'] = now();
            //@todo when we set to in progress? and how we should do it?
            //$data['status']     = $data['status'] ?? $model->phase->name;
        }

        parent::update($model, $data);


    }
}
