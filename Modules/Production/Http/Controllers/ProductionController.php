<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;

class ProductionController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(EntityRepository $repository, EntityResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

}
