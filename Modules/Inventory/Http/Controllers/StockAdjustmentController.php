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

    public function export($stockadjustment_id)
    {
        $result = $this->repository->exportStockAdjustment($stockadjustment_id);
        return $this->setStatusCode(201)->sendArray($result);
    }
}
