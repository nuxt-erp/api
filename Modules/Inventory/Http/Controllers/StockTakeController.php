<?php

namespace Modules\Inventory\Http\Controllers;


use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockTakeRepository;
use Modules\Inventory\Transformers\StockTakeResource;

class StockTakeController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(StockTakeRepository $repository, StockTakeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function finish($stocktake_id)
    {
        $status = $this->repository->finish($stocktake_id);
        return $this->setStatusCode(201)->respondWithObject($this->repository->model, $this->resource);
    }
}
