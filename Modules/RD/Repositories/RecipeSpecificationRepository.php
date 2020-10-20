<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\Flow;


class RecipeSpecificationRepository extends RepositoryService
{   
    public function store(array $data)
    {
        DB::transaction(function () use ($data)
        {
            $user = auth()->user();
            $data['approver_id'] = $user->id;

            if(!empty($data['project_sample_id'])){
                $sample = ProjectSamples::find($data['project_sample_id']);
                $flow = Flow::where('phase_id', $sample->phase_id)->first();
                $sample->phase_id = $flow->next_phase_id;
                $sample->status = strtolower($flow->next_phase->name);
                // $sample_repo = new ProjectSamplesRepository(new ProjectSamples());
                // $sample_repo->update($sample, $sample_data);
            }

            parent::store($data);

            if (Arr::has($data, 'attributes')) {
                $this->model->attributes()->sync($data['attributes']);
            }
        });

    }
    public function update($model, array $data)
    {

        DB::transaction(function () use ($data, $model)
        {
            $user = auth()->user();
            $data['approver_id'] = $user->id;

            if(!empty($data['project_sample_id']) && !empty($data['finish'])){

                $sample = ProjectSamples::find($data['project_sample_id']);
                $flow = Flow::where('phase_id', $sample->phase_id)->first();
                $sample_data = [];
                $sample_data['phase_id'] = $flow->next_phase_id;
                $sample_data['status'] = strtolower($flow->next_phase->name);
                lad($sample_data);
                $sample_repo = new ProjectSamplesRepository(new ProjectSamples());
                $sample_repo->update($sample, $sample_data);
            }

            parent::update($model, $data);

            if (Arr::has($data, 'attributes')) {
                $this->model->attributes()->sync($data['attributes']);
            }
        });

    }

   
}
