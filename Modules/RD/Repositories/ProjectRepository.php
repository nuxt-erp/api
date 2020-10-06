<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\ProjectSamples;

class ProjectRepository extends RepositoryService
{

    public function store(array $data)
    {
        $this->samples = !empty($data["samples"]);

        DB::transaction(function () use ($data)
        {
            $user = auth()->user();
            $data['author_id'] = $user->id;
            lad($data);
            parent::store($data);
        });
        
        if(!empty($data["samples"])) {
            foreach($data["samples"] as $sample) {
                $new = ProjectSamples::create([
                    'project_id'            => $this->model->id,
                    'recipe_id'             => $sample['recipe_id'],
                    'assignee_id'           => $sample['assignee_id'],
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
            }
        }

    }

    public function update($model, array $data)
    {
        lad($data);
        lad($model);

        DB::transaction(function () use ($data, $model)
        {
            $user = auth()->user();
            
            
            parent::update($model, $data);
            
            ProjectSamples::where('project_id', $model->id)->delete();
            if(!empty($data["samples"])) {
                foreach ($data["samples"] as $sample)
                {
                    $sampleArray = [
                        'project_id'            => $model->id,
                        'recipe_id'             => $sample['recipe_id'],
                        'assignee_id'           => $sample['assignee_id'],
                        'name'                  => $sample['name'],
                        'status'                => strtolower($sample['status']),
                        'target_cost'           => $sample['target_cost'],
                        'feedback'              => $sample['feedback'],
                        'comment'               => $sample['comment'],
                        'internal_code'         => $sample['internal_code'],
                        'external_code'         => $sample['external_code'],
                    ];
                    if(!empty($sample['id'])) {
                        $new = ProjectSamples::updateOrCreate(['id' =>  $sample['id']], $sampleArray);
                    } else {
                        $new = ProjectSamples::updateOrCreate($sampleArray);  
                    }
                    if (Arr::has($sample, 'attribute_ids')) {
                        $new->attributes()->sync($sample['attribute_ids']);
                    }
                }
            }
        });

    }


}
