<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\Flow;
use Modules\RD\Entities\Phase;
use Modules\RD\Entities\ProjectSampleLogs;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\Recipe;
use Modules\RD\Entities\RecipeItems;

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

            $this->queryBuilder->where(function ($query) use($text) {
                $query->where('name', 'ILIKE', $text)
                ->orWhere('internal_code', 'LIKE', $text)
                ->orWhere('external_code', 'LIKE', $text);
            });
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

            if(!empty($data['id'])){
                $sample = ProjectSamples::find($data['id']);
                $this->update($sample, $data);
            }
            else{
                parent::store($data);
                if (Arr::has($data, 'attribute_ids')) {
                    $this->model->attributes()->sync($data['attribute_ids']);
                }
                $this->createLog($this->model, TRUE);
            }

        });

    }

    public function update($model, array $data)
    {
        DB::transaction(function () use ($model, &$data){

            $approval       = !empty($data['supervisor_approval']) && $data['supervisor_approval'];
            $finish         = !empty($data['flavorist_finish']) && $data['flavorist_finish'];
            $recipe_update  = !empty($data['recipe']);
            $user           = auth()->user();

            // FLAVORIST UPDATE
            if($recipe_update){

                // existing recipe
                if(!empty($data['recipe']['id'])){

                    // create the next version
                    if($data['recipe']['new_version']){

                            // get current recipe
                            $recipe                         = Recipe::findOrFail($data['recipe']['id']);

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
                            $this->syncIngredients($new_recipe->id, $data['recipe']['ingredients']);
                            // update sample recipe id
                            $data['recipe_id'] = $new_recipe->id;

                    }
                }
                else{
                    $recipe                     =  new Recipe();
                    $recipe->fill($data['recipe']);
                    $recipe->author_id          = $user->id;
                    $recipe->last_updater_id    = $user->id;
                    $recipe->status             = Recipe::NEW_RECIPE;
                    $recipe->save();

                    $this->syncIngredients($recipe->id, $data['recipe']['ingredients']);
                    $data['recipe_id'] = $recipe->id;
                }

            }

            // STATUS HANDLE ======>
            // FINISH
            if($finish || $approval){
                $flow = Flow::where('phase_id', $model->phase_id)->first();
                $data['phase_id']   = $flow->next_phase_id;
                if($finish){
                    $data['finished_at']= now();
                }
                $data['status']     = $flow->next_phase->name;
            }
            else{
                // IF FRONTEND SEND STATUS
                if(!empty($data['status'])){
                    $data['status']     = strtolower($data['status']);
                    $data['phase_id']   = Phase::where('name', strtolower($data['status']))->first()->id;
                }
            }
            // STATUS HANDLE <======

            // HANDLE START AT
            if(!$model->started_at && ($recipe_update || $finish)){
                $data['started_at'] = now();
            }

            parent::update($model, $data);

            $this->createLog($this->model);
        });


    }

    private function syncIngredients($recipe_id, $ingredients){

        if(count($ingredients) > 0){
            RecipeItems::where('recipe_id', $recipe_id)->delete();

            foreach ($ingredients as $ingredient) {

                $recipe_ingredient = new RecipeItems();
                $recipe_ingredient->fill($ingredient);
                $recipe_ingredient->recipe_id = $recipe_id;
                $recipe_ingredient->save();
            }
        }
    }

    private function createLog($model, $created = FALSE){

        $user           = auth()->user();

        ProjectSampleLogs::create([
            'project_sample_id'   => $model->id,
            'project_id'          => $model->project_id,
            'updater_id'          => $user->id,
            'recipe_id'           => $model->recipe_id,
            'status'              => $model->status,
            'feedback'            => $model->feedback,
            'comment'             => $model->comment,
            'name'                => $model->name,
            'internal_code'       => $model->internal_code,
            'external_code'       => $model->external_code,
            'is_start'            => $created
        ]);

    }
}
