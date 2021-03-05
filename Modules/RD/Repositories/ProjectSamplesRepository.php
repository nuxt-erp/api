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

        if (!$user->hasRole('admin', 'rd_requester', 'rd_supervisor', 'rd_quality_control')) {
            $this->queryBuilder->where('assignee_id', $user->id);
        }

        if ($user->hasRole('rd_quality_control')) {
            if (!empty($searchCriteria['tab'])) {
                $tab = Arr::pull($searchCriteria, 'tab');
                $this->queryBuilder
                    ->where('status', 'ILIKE', $tab == 'pending' ? 'waiting qc' : 'ready');
            } else {
                $this->queryBuilder
                    ->where('status', 'ILIKE', 'waiting qc')
                    ->orWhere('status', 'ILIKE', 'ready');
            }
        } else {
            if (!empty($searchCriteria['tab'])) {
                $tab = Arr::pull($searchCriteria, 'tab');
                if ($tab == 'pending') {
                    $this->queryBuilder->where('status', '<>', 'approved');
                } else {
                    $this->queryBuilder->where('status', 'approved');
                }
            }
        }

        if (!empty($searchCriteria['status'])) {
            $this->queryBuilder->where('status', strtolower(Arr::pull($searchCriteria, 'status')));
        }

        if (!empty($searchCriteria['created_at'])) {
            $this->queryBuilder->whereBetween('created_at', Arr::pull($searchCriteria, 'created_at'));
        }

        if (!empty($searchCriteria['type'])) {
            $this->queryBuilder->whereHas('recipe', function ($query) use ($searchCriteria) {
                $query->whereIn('type_id', Arr::pull($searchCriteria, 'type'));
            });
        }

        if (!empty($searchCriteria['name'])) {
            $text = '%' . Arr::pull($searchCriteria, 'name') . '%';

            $this->queryBuilder->where(function ($query) use ($text) {
                $query->where('name', 'ILIKE', $text)
                    ->orWhere('internal_code', 'ILIKE', $text)
                    ->orWhere('external_code', 'ILIKE', $text);
            });
        }

        if (empty($searchCriteria['order_by'])) {
            $this->queryBuilder->orderBy('name', 'asc');
        }

        return parent::findBy($searchCriteria);
    }

    public function store(array $data)
    {
        DB::transaction(function () use ($data) {

            if (!empty($data['id'])) {
                $sample = ProjectSamples::find($data['id']);
                $this->update($sample, $data);
            } else {
                $user               = auth()->user();
                $data['author_id']  = $user->id;

                if (!empty($data['assignee_id'])) {
                    $data['status'] = 'assigned';
                }
                if (!empty($data['recipe_id'])) {
                    $recipe = Recipe::find($data['recipe_id']);
                    if (!empty($recipe->type_id)) {
                        $data['internal_code'] = !empty($recipe->internal_code) ? $recipe->internal_code : $recipe->type->value . '-' . $data['recipe_id'];
                    }
                }

                // option 1 - no status in the array - find the first phase in the flow
                if (empty($data['status'])) {
                    $flow = Flow::where('start', 1)->first();
                    $data['status']     = $flow->phase->name;
                    $data['phase_id']   = $flow->phase_id;
                }
                // option 2 - status came, but no phase id - find the phase with this name
                elseif (!empty($data['status']) && empty($data['phase_id'])) {
                    //$data['status']     = strtolower($data['status']);
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

        DB::transaction(function () use ($model, &$data) {

            lad($data);

            $approved                    = !empty($data['supervisor_approval']) && $data['supervisor_approval'];
            $rejected                    = !empty($data['supervisor_reject']) && $data['supervisor_reject'];
            $finished                    = !empty($data['flavorist_finish']) && $data['flavorist_finish'];
            $sent                        = !empty($data['requester_sent']) && $data['requester_sent'];
            $customer_approved           = !empty($data['customer_approved']) && $data['customer_approved'];
            $customer_rejected           = !empty($data['customer_rejected']) && $data['customer_rejected'];
            $supervisor_reassigned       = !empty($data['supervisor_reassigned']) && $data['supervisor_reassigned'];
            $assigned                    = $model->status === 'pending' && empty($model->assignee_id) && !empty($data['assignee_id']);
            $user                        = auth()->user();

            $recipe = $model->recipe;

            //@todo maybe should we use the logic to find the next phase?
            // STATUS HANDLE ======>
            if ($approved) {
                $flow = Flow::where('phase_id', $model->phase_id)->first();
                $data['phase_id']   = $flow->next_phase_id;
                $data['status']     = strtolower($flow->next_phase->name);
                $data['feedback']   = null;

                //set recipe to approved to show
                $recipe->approver_id = $user->id;
                $recipe->approved_at = now();
                $recipe->status      = Recipe::APPROVED_RECIPE;
                $recipe->save();
            } elseif ($finished) {
                $flow = Flow::where('phase_id', $model->phase_id)->first();
                $data['phase_id']       = $flow->next_phase_id;
                $data['status']         = strtolower($flow->next_phase->name);
                $data['finished_at']    = now();
            } elseif ($supervisor_reassigned) {
                $data['phase_id']   = Phase::where('name', 'assigned')->first()->id;
                $data['status']     = 'assigned';
            } elseif ($sent) {
                $data['phase_id']   = Phase::where('name', 'sent')->first()->id;
                $data['status']     = 'sent';
            } elseif ($customer_approved) {
                $data['phase_id']   = Phase::where('name', 'approved')->first()->id;
                $data['status']     = 'approved';

                $recipe->approver_id = $user->id;
                $recipe->approved_at = now();
                $recipe->status     = Recipe::APPROVED_RECIPE;
                $recipe->save();
            } elseif ($rejected || $customer_rejected) {
                $data['phase_id']       = Phase::where('name', 'rework')->first()->id;
                $data['status']         = 'rework';
                $data['finished_at']    = null;
                $data['started_at']     = null;
            } elseif ($assigned) {
                $data['phase_id']   = Phase::where('name', 'assigned')->first()->id;
                $data['status']     = 'assigned';
            }

            // FLAVORIST RECIPE UPDATE
            if (!empty($data['recipe'])) {

                $recipe_from_scratch = empty($data['recipe']['id']);
                $new_version         = $data['recipe']['new_version'];

                if ($recipe_from_scratch) {
                    $recipe                     =  new Recipe();
                    $recipe->fill($data['recipe']);
                    $recipe->author_id          = $user->id;
                    $recipe->last_updater_id    = $user->id;
                    $recipe->status             = Recipe::NEW_RECIPE;
                    $recipe->last_version       = TRUE;
                    $recipe->save();

                    $this->syncIngredients($recipe->id, $data['recipe']['ingredients']);
                    $data['recipe_id']  = $recipe->id;
                } else {

                    $recipe = Recipe::findOrFail($data['recipe']['id']);

                    // GENERATE A NEW VERSION
                    if ($new_version && $recipe) {
                        // NEW VERSION BASED ON AN OLD RECIPE
                        if (!$recipe->last_version) {
                            $last_recipe    = Recipe::where('last_version', TRUE)
                                ->where('code', $recipe->code)
                                ->orderBy('version', 'DESC') // let's make sure we are getting the last version
                                ->first();
                        }
                        // CURRENT RECIPE IS THE LAST VERSION
                        else {
                            $last_recipe    = $recipe;
                        }
                        // not the last version anymore
                        $last_recipe->last_version  = FALSE;
                        $last_recipe->save();

                        // NEW RECIPE VERSION ---->
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
                        // add new version if is finished
                        $new_recipe->version            = $last_recipe->version + 1;
                        $new_recipe->last_version       = TRUE;
                        $new_recipe->save();

                        // copy ingredients
                        $this->syncIngredients($new_recipe->id, $data['recipe']['ingredients']);
                        // RECIPE ID now is the new version
                        $data['recipe_id']  = $new_recipe->id;
                        $recipe             = $new_recipe;
                    }
                    // UPDATE THE RECIPE
                    // else{
                    //     // IF THE CARRIER IS CHANGED
                    //     $recipe->carrier_id = $data['recipe']['carrier_id'];
                    //     $recipe->save();
                    //     // IF DEVELOPING A NEW VERSION, UPDATE INGREDIENTS
                    //     if($new_version){
                    //         $this->syncIngredients($data['recipe']['id'], $data['recipe']['ingredients']);
                    //     }
                    // }
                }

                // RECIPE UPDATE + assigned = IN PROGRESS
                if ($model->status == 'assigned') {
                    $data['phase_id']   = Phase::where('name', 'in progress')->first()->id;
                    $data['status']     = 'in progress';
                    $data['started_at'] = now();
                }
            }

            // UPDATE SAMPLE INTERNAL CODE
            if ($recipe && $recipe->type) {
                $data['internal_code'] = !empty($recipe->internal_code) ? $recipe->internal_code : $recipe->type->value . '-' . $recipe->id;
            }

            parent::update($model, $data);

            $this->model->recipe = $recipe;

            if (Arr::has($data, 'attribute_ids')) {
                $this->model->attributes()->sync($data['attribute_ids']);
            }
            $this->createLog($this->model);
        });
    }

    private function syncIngredients($recipe_id, $ingredients)
    {

        if (count($ingredients) > 0) {
            RecipeItems::where('recipe_id', $recipe_id)->delete();

            foreach ($ingredients as $ingredient) {

                $recipe_ingredient = new RecipeItems();
                $recipe_ingredient->fill($ingredient);
                $recipe_ingredient->recipe_id = $recipe_id;
                $recipe_ingredient->save();
            }
        }
    }

    private function createLog($model, $created = FALSE)
    {

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
