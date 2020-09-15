<?php

namespace Modules\Inventory\Http\Controllers;


use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockCountRepository;
use Modules\Inventory\Transformers\StockCountResource;

class StockCountController extends ControllerService
{
    protected $repository;
    protected $resource;

    public function __construct(StockCountRepository $repository, StockCountResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function finish($stockcount_id)
    {
        $status = $this->repository->finish($stockcount_id);
        return $this->setStatusCode(201)->respondWithObject($this->repository->model, $this->resource);
    }
}
