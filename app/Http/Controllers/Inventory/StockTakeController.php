<?php

namespace App\Http\Controllers\Inventory;


use App\Http\Controllers\ControllerService;
use App\Repositories\StockTakeRepository;
use App\Resources\StockTakeResource;

class StockTakeController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(StockTakeRepository $repository, StockTakeResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function finish($stocktake_id)
    {
        $status = $this->repository->finish($stocktake_id);
        return $this->setStatusCode(201)->respondWithObject($this->repository->model, $this->resource);
    }
}
