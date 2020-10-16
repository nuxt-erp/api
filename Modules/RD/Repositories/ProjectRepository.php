<?php

namespace Modules\RD\Repositories;
use Illuminate\Support\Facades\DB;
use App\Repositories\RepositoryService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Entities\ProjectLogs;
class ProjectRepository extends RepositoryService
{
    public function findBy(array $searchCriteria = [])
    {
        $user = auth()->user();
        if(!empty($searchCriteria['search'])){
            $text = '%' . Arr::pull($searchCriteria, 'search') . '%';
            $this->queryBuilder->where(function ($query) use($text) {
                $query->where('comment', 'ILIKE', $text)
                ->orWhere('id', 'ILIKE', $text)
                ->orWhere('status', 'ILIKE', $text);
            });
        }
        if(!empty($searchCriteria['start_at'])){
            $this->queryBuilder->whereBetween('start_at', $searchCriteria['start_at']);
        }

        if(!empty($searchCriteria['status'])){
            $text = '%' . Arr::pull($searchCriteria, 'status') . '%';
            $this->queryBuilder->where('status', 'ILIKE', $text);
        }

        if(!empty($searchCriteria['sample_status'])){
            lad($searchCriteria['sample_status']);
            $text = '%' . Arr::pull($searchCriteria, 'sample_status') . '%';
            $this->queryBuilder->whereHas('samples', function ($query) use($text) {
                $query->where('status', 'ILIKE', $text);
            });
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

            //@todo find samples missing and delete
            $sample_repo    = new ProjectSamplesRepository(new ProjectSamples());
            $all_approved   = TRUE;
            $sample_sent    = FALSE;
            $sample_rework  = FALSE;

            foreach ($data["samples"] as $sample) {
                $sample['project_id'] = $this->model->id;
                $sample_repo->store($sample);
                $stored_sample = $sample_repo->model;
                if(strtolower($stored_sample->status) !== 'approved'){
                    $all_approved = FALSE;
                }
                if(strtolower($stored_sample->status) === 'sent'){
                    $sample_sent = TRUE;
                }
                if(strtolower($stored_sample->status) === 'rework'){
                    $sample_rework = TRUE;
                }
            }
            // ALL SAMPLES APPROVED
            if($all_approved){
                $this->model->status = 'finished';
                $this->model->closed_at = now();

                $project_log['status'] = 'finished';
            }
            // SAMPLE SENT TO FEEDBACK
            elseif($sample_sent && $model['status'] !== 'awaiting feedback'){
                $this->model->status = 'awaiting feedback';
                $project_log['status'] = 'awaiting feedback';
            }
            // SAMPLE SENT TO REWORK
            elseif($sample_rework && $model['status'] !== 'updated'){
                $this->model->status = 'updated';
                $project_log['status'] = 'updated';
            }

            $this->model->save();

            // ONLY CREATE LOG IF SOMETHING HAPPENED
            if(!empty($project_log['status'])){
                ProjectLogs::create($project_log);
            }
        });

    }


}
