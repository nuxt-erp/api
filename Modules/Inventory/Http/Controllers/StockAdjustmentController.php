<?php

namespace Modules\Inventory\Http\Controllers;


use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockAdjustmentRepository;
use Modules\Inventory\Transformers\StockAdjustmentResource;

class StockAdjustmentController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(StockAdjustmentRepository $repository, StockAdjustmentResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }

    public function findStockAdjustmentLocations(Request $request)
    {
        $result = $this->repository->findStockAdjustmentLocations($request->all());
        return $this->sendArray($result);
    }
}
