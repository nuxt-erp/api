<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Resources\ListResource;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\ProjectSamplesRepository;
use Modules\RD\Transformers\ProjectSamplesFlavoristResource;
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

    public function getStatuses(){

        $statuses = $this->repository->model->getStatuses();
        $keyValue = [];
        $i = 0;

        foreach ($statuses as $key => $status) {
            $keyValue[$i]['name'] = ucfirst($status);
            $keyValue[$i]['value'] = $status;
            $i++;
        }
        return $this->sendArray($keyValue);
    }

    public function index(Request $request)
    {
        $isList = $request->has('list') && $request->list;

        if($this instanceof CheckPolicies){
            // call the police associated with this model
            if($isList){
                $this->authorize('list', get_class($this->repository->model));
            }
            else{
                $this->authorize('index', get_class($this->repository->model));
            }
        }

        $items = $this->repository->findBy($request->all());
        if($isList){
            return $this->sendCollectionResponse($items, ListResource::class);
        }
        else{
            // $user = auth()->user();
            // if($user->hasRole('admin')){
            //     return $this->sendFullCollectionResponse($items, $this->resource);
            // }
            // else{
                //rd_flavorist
                return $this->sendFullCollectionResponse($items, ProjectSamplesFlavoristResource::class);
            // }

        }
    }
}

