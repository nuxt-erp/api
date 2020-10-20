<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Resources\ListResource;
use App\Http\Controllers\ControllerService;
use Illuminate\Support\Arr;
use Modules\RD\Entities\ProjectSamples;
use Modules\RD\Repositories\ProjectSamplesRepository;
use Modules\RD\Transformers\ProjectSamplesResource;

class ProjectSamplesController extends ControllerService
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


        $statuses = $this->repository->model->getStatuses();


        $user = auth()->user();
        $role = '';

        if($user->hasRole('rd_requester')) {
            $role = 'rd_requester';
        } else if($user->hasRole('rd_supervisor')) {
            $role = 'rd_supervisor';
        } else if($user->hasRole('rd_flavorist')) {
            $role = 'rd_flavorist';
        } else if($user->hasRole('rd_quality_control')) {
            $role = 'rd_quality_control';
        }

        $keyValue   = [];
        $i          = 0;
        if($user->hasRole('admin')){
            $status_list = [];
            foreach (Arr::flatten($statuses) as $status) {
                $status_list[$status] = $status;
            }
            foreach ($status_list as $status) {
                $keyValue[$i]['is_default'] = ucfirst($status) === 'pending' ? 1 : 0;
                $keyValue[$i]['name'] = ucfirst($status);
                $keyValue[$i]['value'] = ucfirst($status);
                $i++;
            }
        }
        else{
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

    public function index(Request $request)
    {
        $result = $this->indexCollection($request);
        // lad('$result', $result);

        $user = auth()->user();
        foreach ($result as $item) {
            $actions = [];
            switch ($item->status) {
                case 'approved':
                case 'waiting qc':
                    $actions[] = [
                        'name'  => 'Generate Specs',
                        'code'  => 'generate',
                        'type'  => 'primary'
                    ];
                break;

                case 'ready':
                    $actions[] = [
                        'name'  => 'View Specs',
                        'code'  => 'generate',
                        'type'  => 'primary'
                    ];
                break;

                case 'pending':
                case 'sent':
                    $actions[] = [
                        'name'  => 'Preview',
                        'code'  => 'sample',
                        'type'  => 'primary'
                    ];
                    break;
                case 'assigned':
                case 'in progress':
                case 'rework':
                    $actions[] = [
                        'name'  => $user->hasRole('rd_flavorist') ?  'Develop' : 'Preview',
                        'code'  => 'sample',
                        'type'  => 'primary'
                    ];
                    break;
                case 'waiting approval':
                    $actions[] = [
                        'name'  => $user->hasRole('rd_supervisor') ? 'Approve Sample' : 'Preview',
                        'code'  => 'sample',
                        'type'  => 'primary'
                    ];
                    break;
            }
            $item->actions = collect($actions);
        }

        return $this->indexResponse($request, $result);
    }
}

