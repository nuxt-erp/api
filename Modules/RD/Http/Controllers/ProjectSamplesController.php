<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Resources\ListResource;
use App\Http\Controllers\ControllerService;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Repositories\ProjectSamplesRepository;
use Modules\RD\Transformers\ProjectSamplesResource;

class ProjectSamplesController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(ProjectSamplesRepository $repository, ProjectSamplesResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function getSampleStatuses(array $searchCriteria = []){
        $keyValue = [];
        $sample_id = request()->query('sample_id');

        if(!is_null($sample_id))
        {

            $sample = ProjectSamples::find($sample_id);
            $statuses = $this->repository->model->getStatuses();

            $i = 0;
            $user = auth()->user();
            $role = '';

            if($user->hasRole('rd_requester')) {
                $role = 'rd_requester';
            } else if($user->hasRole('rd_supervisor')) {
                $role = 'rd_supervisor';
            }

            $filtered = array_filter(
                $statuses,
                function ($key) use ($role) {
                    return $key === $role;
                },
                ARRAY_FILTER_USE_KEY
            );

            foreach ($filtered[$role] as $status) {
                $keyValue[$i]['is_default'] = ucfirst($status) === 'pending' ? 1 : 0;
                $keyValue[$i]['name'] = ucfirst($status);
                $keyValue[$i]['value'] = ucfirst($status);
                $i++;
            }

        }


        return $this->sendArray($keyValue);
    }
}

