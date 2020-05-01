<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ControllerService;
use App\Repositories\SystemParameterRepository;
use App\Resources\SystemParameterResource;
use Illuminate\Http\Request;

class SystemParameterController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(SystemParameterRepository $repository, SystemParameterResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function getCountTypeList(Request $request) {

        // TO POPULATE DROPDOWN WITH COUNT TYPES
        $itens = $this->repository->getCountTypeList($request->all());
        return $this->respondWithNativeCollection($itens, SystemParameterResource::class);
    }

}
