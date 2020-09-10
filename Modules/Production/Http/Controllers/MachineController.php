<?php

namespace Modules\Production\Http\Controllers;

use Illuminate\Http\Request;
use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Production\Repositories\MachineRepository;
use Modules\Production\Transformers\MachineResource;


class MachineController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(MachineRepository $repository, MachineResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
