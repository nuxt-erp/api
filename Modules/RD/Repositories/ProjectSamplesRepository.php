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

        if(empty($searchCriteria['project_id']) && $user->hasRole('rd_supervisor')){
            $this->queryBuilder->where('status', 'ILIKE', 'waiting approval');
        }

        if(empty($searchCriteria['project_id']) && $user->hasRole('rd_flavorist')){
            $this->queryBuilder->where('status', 'ILIKE', 'in progress');
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

            if(!empty($data['id'])){
                $sample = ProjectSamples::find($data['id']);
                if(empty($sample->assignee_id) && !empty($data['assignee_id'])){
                    $data['status'] = 'in progress';
                }
                $this->update($sample, $data);
            }
            else{
                $user               = auth()->user();
                $data['author_id']  = $user->id;

                if(!empty($data['assignee_id'])){
                    $data['status'] = 'in progress';
                }

                // option 1 - no status in the array - find the first phase in the flow
                if(empty($data['status'])){
                    $flow = Flow::where('start', 1)->first();
                    $data['status']     = $flow->phase->name;
                    $data['phase_id']   = $flow->phase_id;
                }
                // option 2 - status came, but no phase id - find the phase with this name
                elseif(!empty($data['status']) && empty($data['phase_id'])){
                    $data['status']     = strtolower($data['status']);
                    $phase = Phase::where('name', $data['status'])->first();
                    $data['phase_id']   = $phase->id;
                }

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
            $reject         = !empty($data['supervisor_reject']) && $data['supervisor_reject'];
            $finish         = !empty($data['flavorist_finish']) && $data['flavorist_finish'];
            $recipe_update  = !empty($data['recipe']);
            $user           = auth()->user();
            lad($approval);
            // FLAVORIST UPDATE
            if($recipe_update){

                // existing recipe
                if(!empty($data['recipe']['id'])){

                    // create the next version
                    if($data['recipe']['new_version']){

                            // get current recipe
                            $recipe         = Recipe::findOrFail($data['recipe']['id']);

                            $new_recipe                     = $recipe->replicate();
                            $new_recipe->author_id          = $user->id;
                            $new_recipe->last_updater_id    = $user->id;
                            $new_recipe->approver_id        = null;
                            $new_recipe->approved_at        = null;
                            $new_recipe->status             = Recipe::NEW_RECIPE;
                            $new_recipe->last_version       = FALSE;
                            $new_recipe->carrier_id         = $data['recipe']['carrier_id'] ?? null;
                            //$new_recipe->cost             = $data['recipe']['cost']; //@todo sum from the ingredients?
                            //$new_recipe->total            = $data['recipe']['total']; //@todo sum from the ingredients?
                            $new_recipe->version            = $recipe->version + 1;
                            $new_recipe->last_version       = 1;

                            $new_recipe->save();

                            $recipe->last_version = 0;
                            $recipe->save();

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
                $data['status']     = strtolower($flow->next_phase->name);
            }
            if($reject){
                $data['phase_id']   = 2;
                $data['status']     = 'in progress';
            }
        

            // option 1 - recipe update without start
            // option 2 - finish without start
            else{
                if( ($model->status == 'pending' || empty($model->assignee_id)) && !empty($data['assignee_id'])){
                    $data['status']     = 'in progress';
                }

                // IF FRONTEND SEND STATUS OR WE GET SOMETHING
                if(!empty($data['status'])){
                    $data['status']     = strtolower($data['status']);
                    $data['phase_id']   = Phase::where('name', $data['status'])->first()->id;
                }
            }

            // HANDLE START AT
            if(!$model->started_at && ($recipe_update || $finish)){
                $data['started_at'] = now();
            }

            parent::update($model, $data);

            if (Arr::has($data, 'attribute_ids')) {
                $this->model->attributes()->sync($data['attribute_ids']);
            }
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
