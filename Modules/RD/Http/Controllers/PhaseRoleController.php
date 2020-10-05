<?php

namespace Modules\RD\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\RD\Repositories\PhaseRoleRepository;
use Modules\RD\Transformers\PhaseRoleResource;

class PhaseRoleController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(PhaseRoleRepository $repository, PhaseRoleResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
