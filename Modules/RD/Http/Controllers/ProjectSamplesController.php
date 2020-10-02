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
            
            lad($sample->status);
            $statuses = $this->repository->model->getStatuses();
            $i = 0;
            $user = auth()->user();                   
            foreach ($statuses as $key => $status) {
                foreach ($status as $key => $value) {
                    if($key === 'rd_requester' && $user->hasRole('rd_requester')) {
                        $keyValue[$i]['is_default'] = ucfirst($value) === 'pending' ? 1 : 0;
                        $keyValue[$i]['name'] = ucfirst($value);
                        $keyValue[$i]['value'] = ucfirst($value);
                    } else if($key === 'rd_supervisor' && $user->hasRole('rd_supervisor')) {
                        $keyValue[$i]['is_default'] = 0;
                        $keyValue[$i]['name'] = ucfirst($value);
                        $keyValue[$i]['value'] = ucfirst($value);
                    }
                }
                $i++;
            }
        }

        
        return $this->sendArray($keyValue);
    }
}

