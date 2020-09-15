<?php

namespace Modules\Inventory\Http\Controllers;


use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockTakeDetailRepository;
use Modules\Inventory\Transformers\StockTakeDetailResource;

class StockTakeDetailController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(StockTakeDetailRepository $repository, StockTakeDetailResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
