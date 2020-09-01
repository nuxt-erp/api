<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;

class RDController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(EntityRepository $repository, EntityResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

}
