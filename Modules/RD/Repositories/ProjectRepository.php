<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\Phase;
use Modules\RD\Entities\Project;
use Modules\RD\Entities\ProjectLogs;
use Modules\RD\Entities\ProjectSampleLogs;

class ProjectRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        $user = auth()->user();

        if(!empty($searchCriteria['start_at'])){
            $this->queryBuilder->whereBetween('start_at', $searchCriteria['start_at']);
        }

        if(!empty($searchCriteria['status'])){
            $text = '%' . Arr::pull($searchCriteria, 'status') . '%';
            $this->queryBuilder->where('status', 'ILIKE', $text);
        }

        if(!empty($searchCriteria['comment'])){
            $text = '%' . Arr::pull($searchCriteria, 'comment') . '%';
            $this->queryBuilder->where('comment', 'ILIKE', $text);
        }

        return parent::findBy($searchCriteria);
    }

    public function store(array $data)
    {
        $this->samples = !empty($data["samples"]);
        $user = auth()->user();

        DB::transaction(function () use ($data, $user)
        {
            $data['author_id'] = $user->id;
            parent::store($data);
            ProjectLogs::create([
                'project_id'   => $this->model->id,
                'updater_id'   => $user->id,
                'status'       => $this->model->status,
                'code'         => $this->model->code,
                'comment'      => $this->model->comment,
                'start_at'     => $this->model->start_at,
                'closed_at'    => $this->model->closed_at,
                'is_start'     => 1
            ]);
        });
        
        if(!empty($data["samples"])) {
            foreach($data["samples"] as $sample) {
                $new = ProjectSamples::create([
                    'project_id'            => $this->model->id,
                    'recipe_id'             => $sample['recipe_id'],
                    'phase_id'              => Phase::where('name', strtolower($sample['status']))->get()->first()->id,
                    'assignee_id'           => $sample['assignee_id'],
                    'author_id'             => $user->id,
                    'name'                  => $sample['name'],
                    'status'                => $sample['status'],
                    'target_cost'           => $sample['target_cost'],
                    'feedback'              => $sample['feedback'],
                    'comment'               => $sample['comment'],
                    'internal_code'         => $sample['internal_code'],
                    'external_code'         => $sample['external_code'],
                ]);  
                if (Arr::has($sample, 'attribute_ids')) {
                    $new->attributes()->sync($sample['attribute_ids']);
                }  
                ProjectSampleLogs::create([
                    'project_sample_id'   => $new->id,
                    'project_id'          => $this->model->id,
                    'updater_id'          => $user->id,
                    'recipe_id'           => $new->recipe_id,
                    'status'              => $new->status,
                    'feedback'            => $new->feedback,
                    'comment'             => $new->comment,
                    'name'                => $new->name,
                    'internal_code'       => $new->internal_code,
                    'external_code'       => $new->external_code,
                    'is_start'            => 1
                ]);
            }
        }

    }

    public function update($model, array $data)
    {

        DB::transaction(function () use ($data, $model)
        {
            $user = auth()->user();
            parent::update($model, $data);

            // ProjectLogs::create([
            //     'project_id'   => $this->model->id,
            //     'updater_id'   => $user->id,
            //     'status'       => $this->model->status === $data['status'] ? strtolower($data['status']) : null,
            //     'code'         => $this->model->code === $data['code'] ? $data['code'] : null,
            //     'comment'      => $this->model->comment === $data['comment'] ? $data['comment'] : null,
            //     'start_at'     => $this->model->start_at === $data['start_at'] ? $data['start_at'] : null,
            //     'closed_at'    => $this->model->closed_at === $data['closed_at'] ? $data['closed_at'] : null
            // ]);
            
            $sample_ids = ProjectSamples::where('project_id', $model->id)->get()->pluck('id')->toArray();
            if(!empty($data["samples"])) {
                $approved = true;
                foreach ($data["samples"] as $sample)
                {
                    $sampleArray = [
                        'project_id'            => $model->id,
                        'recipe_id'             => $sample['recipe_id'],
                        'assignee_id'           => $sample['assignee_id'],
                        'name'                  => $sample['name'],
                        'status'                => strtolower($sample['status']),
                        'phase_id'              => Phase::where('name', strtolower($sample['status']))->get()->first()->id,
                        'target_cost'           => $sample['target_cost'],
                        'feedback'              => $sample['feedback'],
                        'comment'               => $sample['comment'],
                        'internal_code'         => $sample['internal_code'],
                        'external_code'         => $sample['external_code'],
                    ];
                    if(strtolower($sample['status']) === 'sent' && $model['status'] !== 'awaiting feedback') {
                        $model->update(array('status' => 'awaiting feedback'));
                    } else if(strtolower($sample['status']) === 'rework' && $model['status'] !== 'updated') {
                        $model->update(array('status' => 'updated'));
                    }
                    if(strtolower($sample['status']) !== 'approved') {
                        $approved = false;    
                    }
                    if(!empty($sample['id'])) {
                        $find_sample = ProjectSamples::find($sample['id'])->get()->toArray();
                        if($find_sample->project->id === $model->id) {
                            $new = ProjectSamples::updateOrCreate($sampleArray);
                        } else {
                            unset($find_sample['id']);
                            $new = ProjectSamples::create($find_sample);
                        }
                        // ProjectSampleLogs::create([
                        //     'project_sample_id'   => $new->id,
                        //     'project_id'          => $model->id,
                        //     'updater_id'          => $user->id,
                        //     'recipe_id'           => $new->recipe_id === $sample['recipe_id'] ? $sample['recipe_id'] : null,
                        //     'status'              => $new->status === $sample['status'] ? strtolower($sample['status']) : null,
                        //     'feedback'            => $new->feedback === $sample['feedback'] ? $sample['feedback'] : null,
                        //     'comment'             => $new->comment === $sample['comment'] ? $sample['comment'] : null,
                        //     'name'                => $new->name === $sample['name'] ? $sample['name'] : null,
                        //     'internal_code'       => $new->internal_code === $sample['internal_code'] ? $sample['internal_code'] : null,
                        //     'external_code'       => $new->external_code === $sample['external_code'] ? $sample['external_code'] : null,
                        //     'is_start'            => 0
                        // ]);
                        
                    } else {
                        $new = ProjectSamples::updateOrCreate($sampleArray);  
                        // ProjectSampleLogs::create([
                        //     'project_sample_id'   => $new->id,
                        //     'project_id'          => $model->id,
                        //     'updater_id'          => $user->id,
                        //     'recipe_id'           => $new->recipe_id,
                        //     'status'              => $new->status,
                        //     'feedback'            => $new->feedback,
                        //     'comment'             => $new->comment,
                        //     'name'                => $new->name,
                        //     'internal_code'       => $new->internal_code,
                        //     'external_code'       => $new->external_code,
                        //     'is_start'            => 1
                        // ]);
                    }
                    if (Arr::has($sample, 'attribute_ids')) {
                        $new->attributes()->sync($sample['attribute_ids']);
                    }
                    $index = array_search($sample['id'], $sample_ids);
                    if($index) { 
                        array_splice($sample_ids, $index, 1);
                    }
                }
                if($approved) {
                    $model->update(array('status' => 'finished'));
                }
                
                foreach($sample_ids as $id) {
                    ProjectSamples::find($id)->delete();
                }
            }
        });

    }


}
