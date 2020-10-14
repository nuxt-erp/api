<?php

namespace Modules\RD\Repositories;

use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\Flow;


class RecipeSpecificationRepository extends RepositoryService
{

    public function store(array $data)
    {
        DB::transaction(function () use ($data){
            $user = auth()->user();
            lad($user->id);
            $data['approver_id'] = $user->id;
            
            if(!empty($data['id'])){
                $sample = ProjectSamples::find($data['id']);
                $flow = Flow::where('phase_id', $sample->phase_id)->first();
                $sample_data['phase_id']   = $flow->next_phase_id;
                $sample_data['status']     = strtolower($flow->next_phase->name);
                $sample_repo = new ProjectSamplesRepository(new ProjectSamples());
                $sample_repo->update($sample, $sample_data);
            }
            
            parent::store($data);

            if (Arr::has($data, 'attributes')) {
                $this->model->attributes()->sync($data['attributes']);
            }
        });
    }
}
