<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\FlavorRepository;
use Modules\Inventory\Transformers\FlavorResource;

class FlavorController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(FlavorRepository $repository, FlavorResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
