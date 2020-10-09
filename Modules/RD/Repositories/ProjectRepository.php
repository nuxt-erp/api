<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\Phase;
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

        DB::transaction(function () use ($data)
        {

            // PROJECT
            $user = auth()->user();
            $data['author_id'] = $user->id;
            parent::store($data);

            ProjectLogs::create([
                'project_id'   => $this->model->id,
                'updater_id'   => $user->id,
                'status'       => $this->model->status,
                'comment'      => $this->model->comment,
                'start_at'     => $this->model->start_at,
                'closed_at'    => $this->model->closed_at,
                'is_start'     => 1
            ]);

            // PROJECT SAMPLE HANDLE
            //$sample_repo = resolve(ProjectSamplesRepository::class);
            $sample_repo = new ProjectSamplesRepository(new ProjectSamples());
            if(!empty($data["samples"])) {
                foreach($data["samples"] as $sample) {
                    $sample['project_id'] = $this->model->id;
                    $sample_repo->store($sample);
                }
            }
        });

    }

    public function update($model, array $data)
    {

        DB::transaction(function () use ($data, $model)
        {
            $user = auth()->user();
            $dirty = parent::getDirty($model, $data);

            $project_log = [
                'project_id'   => $model->id,
                'updater_id'   => $user->id,
                'status'       => null,
                'comment'      => null,
                'start_at'     => null,
                'closed_at'    => null
            ];

            if(!empty($dirty)) {
                foreach ($dirty as $key => $value) {
                    if (array_key_exists($key, $project_log)) {
                        $project_log[$key] = $value;
                    }
                }
                parent::update($model, $data);
            }

<<<<<<< HEAD


            $sample_ids = ProjectSamples::where('project_id', $model->id)->get()->pluck('id')->toArray();
            if(!empty($data["samples"])) {
                $approved = true;
                foreach ($data["samples"] as $sample)
                {
                    $sampleArray = [
                        'id'                    => $sample['id'] ?? null,
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
                    if(!empty($sample['assignee_id'])) {
                        $sampleArray['status'] = 'in progress';
                    }
                    if(strtolower($sampleArray['status']) === 'sent' && $model['status'] !== 'awaiting feedback') {
                        $model->update(array('status' => 'awaiting feedback'));
                        $project_log['status'] = 'awaiting feedback';

                    } else if(strtolower($sampleArray['status']) === 'rework' && $model['status'] !== 'updated') {
                        $model->update(array('status' => 'updated'));
                        $project_log['status'] = 'updated';
                    }
                    if(strtolower($sampleArray['status']) !== 'approved') {
                        $approved = false;
                    }
                    if(!empty($sampleArray['id'])) {
                        $find_sample = ProjectSamples::find($sampleArray['id']);

                        if($find_sample->project->id === $model->id) {
                            $sample_repo->update($find_sample, $sampleArray);
                            $index = array_search($sampleArray['id'], $sample_ids);
                            array_splice($sample_ids, $index, 1);
                        } else {
                            unset($sampleArray['id']);
                            $sampleArray['name'] = $model->id . ' - ' . $sampleArray['name'];
                            $sample_repo->store($sampleArray);
                        }

                    } else {
                        $sample_repo->store($sampleArray);
                    }

                    $index = array_search($sample['id'], $sample_ids);
                    if($index) {
                        array_splice($sample_ids, $index, 1);
                    }
                }
                if($approved) {
                    $model->update(array('status' => 'finished'));
                    $project_log['status'] = 'finished';
                }
                foreach($sample_ids as $id) {
                    ProjectSamples::find($id)->delete();
                }
                ProjectLogs::create($project_log);
=======
            $sample_repo = new ProjectSamplesRepository(new ProjectSamples());
            foreach ($data["samples"] as $sample) {
                $sample['project_id'] = $this->model->id;
                $sample_repo->store($sample);
>>>>>>> da9d19b3bed322ba14dc0991748f9c37db192eae
            }

        });

    }


}
