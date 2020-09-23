<?php

namespace Modules\Inventory\Http\Controllers;

use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockLocatorRepository;
use Modules\Inventory\Transformers\StockLocatorResource;

class StockLocatorController extends ControllerService implements CheckPolicies
{

    protected $repository;
    protected $resource;

    public function __construct(StockLocatorRepository $repository, StockLocatorResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}