<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\MeasureRepository;
use Modules\Inventory\Transformers\MeasureResource;

class MeasureController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(MeasureRepository $repository, MeasureResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}