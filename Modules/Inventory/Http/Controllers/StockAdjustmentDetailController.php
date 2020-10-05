<?php

namespace Modules\Inventory\Http\Controllers;


use App\Concerns\CheckPolicies;
use App\Http\Controllers\ControllerService;
use Modules\Inventory\Repositories\StockAdjustmentDetailRepository;
use Modules\Inventory\Transformers\StockAdjustmentDetailResource;

class StockAdjustmentDetailController extends ControllerService implements CheckPolicies
{
    protected $repository;
    protected $resource;

    public function __construct(StockAdjustmentDetailRepository $repository, StockAdjustmentDetailResource $resource)
    {
        $this->repository = $repository;
        $this->resource = $resource;
        parent::__construct();
    }
}
